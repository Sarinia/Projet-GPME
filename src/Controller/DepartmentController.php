<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
* @IsGranted("ROLE_SADMIN")
*/
class DepartmentController extends AbstractController
{
    /**
     * @Route("/department/show_list", name="department_show_list")
     * @IsGranted("ROLE_SADMIN", message="Vous n'avez pas accès à cette page")
     */
    public function index(DepartmentRepository $departmentRepo, Request $request)
    {
        // on récupère tous les départements
        $departments = $departmentRepo->findAll();

        // on retourne la vue et les données
        return $this->render('department/list.html.twig', [
            'departments' => $departments,
        ]);
    }

    /**
     * @Route("/department/show/{id}", name="department_show")
     * @IsGranted("ROLE_SADMIN", message="Vous n'avez pas accès à cette page")
     */
    public function show(Department $department)
    {
        // on retourne la vue et les données
        return $this->render('department/show.html.twig', [
            'department' => $department,
        ]);
    }

    /**
     * @Route("/department/new", name="department_new")
     * @IsGranted("ROLE_SADMIN", message="Vous n'avez pas accès à cette page")
     */
    public function create(ObjectManager $manager,Request $request)
    {   
        // on instancie un département
        $department = new Department();

        // on crée le formulaire
        $form = $this->createForm(DepartmentType::class, $department);

        // on mémorise les données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et que les champs sont valides
        if ($form->isSubmitted() && $form->isValid()){

            // date de création
            $department->setCreatedAt(new \DateTime());

            // on persite l'entité et on la sauvegarde
            $manager->persist($department);
            $manager->flush();

            // on enregistre un message flash
            $this->addFlash('success','Le département a bien été créé');

            // on redirige vers la liste des départements
            return $this->redirectToRoute('department_show_list');
        }
        
        // on retourne la vue et les données
        return $this->render('department/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/department/modify/{id}", name="department_modify")
     * @IsGranted("ROLE_SADMIN", message="Vous n'avez pas accès à cette page")
     */
    public function modify(ObjectManager $manager, Request $request, Department $department)
    {
        // on crée le formulaire
        $form = $this->createForm(DepartmentType::class, $department);

        // on mémorise les données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et que les champs sont valides
        if ($form->isSubmitted() && $form->isValid()){

            // on persite l'entité et on la sauvegarde
            $manager->persist($department);
            $manager->flush();

            // on enregistre un message flash
            $this->addFlash('success','Le département a bien été mis à jour !');

            // on redirige vers la liste des départements
            return $this->redirectToRoute('department_show', [
                'id' => $department->getId(),
            ]);
        }

        // on retourne la vue et les données
        return $this->render('department/modify.html.twig', [
            'form' => $form->createView(),
            'department' => $department,
        ]);
    }

    /**
     * @Route("/department/delete/{id}", name="department_delete")
     * @IsGranted("ROLE_SADMIN", message="Vous n'avez pas accès à cette page")
     */
    public function delete(ObjectManager $manager, Department $department)
    {
        // on vérifie si le département existe
        if ($department->getExist() == false){

            //On vérifie si le département contient encore des établissements
            if (Count($department->getEstablishment()) == 0) {

                // on supprime le département sans possibilité de retour
                $manager->remove($department);
                $manager->flush();

                // on enregistre un message flash
                $this->addFlash('success', "Le département a bien été supprimé !");

                // on redirige vers la liste des départements
                return $this->redirectToRoute('department_show_list');  

            } else {

                // on enregistre un message flash
                $this->addFlash('danger', "Supprimez d'abord tous les établissements du département !");
                
                // on redirige vers la liste des départements
                return $this->redirectToRoute('department_show_list');  
            }

        } else {

            // on enregistre un message flash
            $this->addFlash('danger', "Le département est encore actif et ne peut pas être supprimé");
            
            // on redirige vers la liste des départements
            return $this->redirectToRoute('department_show_list');  
        } 
    }
}
