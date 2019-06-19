<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\ModifyDepartmentType;
use App\Form\NewDepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    /**
     * Display departments
     * 
     * @Route("/department/show_list", name="department_show_list")
     */
    public function index(DepartmentRepository $deptRepo, Request $request)
    {
        // on récupère la liste de tous les départements
        $depts = $deptRepo->findAll();
        
        // si le champ de recherche != de vide
        if (!empty($request->request->get('search'))) {

            // on fait une recherche par mot clé
            $search = $request->request->get('search');
            $result = $deptRepo->findBy(['name' => $search]);

            // on compte le nombre de ligne que contient le tableau et si c'est == 0
            if (Count($result) == 0) {
                
                // on enregistre un message flash
                $this->addFlash('success','Aucun résultat pour votre recherche');

                return $this->render('department/list.html.twig', [
                    // on envoie des données à la vue
                    'result' => $result,
                ]);
            }
            
            return $this->render('department/list.html.twig', [
                // on envoie des données à la vue
                'result' => $result,
            ]);
        }
        return $this->render('department/list.html.twig', [
            // on envoie des données à la vue
            'depts' => $depts,
        ]);
    }

    /**
     * new department
     * 
     * @Route("/department/new", name="department_new")
     */
    public function create(ObjectManager $manager,Request $request)
    {   
        // on instancie un département
        $department = new Department();

        // on crée le formulaire
        $form = $this->createForm(NewDepartmentType::class, $department);

        // on mémorise les données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et que les champs sont valides
        if ($form->isSubmitted() && $form->isValid()){

            // on persite l'entité et on la sauvegarde
            $manager->persist($department);
            $manager->flush();

            // on enregistre un message flash
            $this->addFlash('success','Le département a bien été créé');

            // on redirige vers la liste des départements
            return $this->redirectToRoute('department_show_list');
        }

        return $this->render('department/new.html.twig', [
            // on envoie des données à la vue
            'form' => $form->createView(),
        ]);
    }

    /**
     * Update a department
     * 
     * @Route("/department/modify/{id}", name="department_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Department $department)
    {        
        /**
         * {id} est automatiquement converti et associé à $department->getId
         */

        // on crée le formulaire
        $form = $this->createForm(ModifyDepartmentType::class, $department);

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
            return $this->redirectToRoute('department_show_list');
        }

        return $this->render('department/modify.html.twig', [
            // on envoie des données à la vue
            'choice_exist' => $department->getExist(),
            'form' => $form->createView(),
            'department' => $department,
        ]);
    }

    /**
     * Delete a department
     * Needs to delete all establisments first
     * 
     * @Route("/department/delete/{id}", name="department_delete")
     */
    public function delete(ObjectManager $manager, Department $department)
    {
        // on vérifie si le département existe
        if ($department->getExist() == false){

            // on supprime le département si possibilité de retout
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
    }
}
