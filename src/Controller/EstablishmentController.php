<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Establishment;
use App\Form\EstablishmentType;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EstablishmentRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
* @IsGranted("ROLE_SADMIN")
*/
class EstablishmentController extends AbstractController
{
    /**
     * @Route("/establishment/show_list", name="establishment_show_list")
     */
    public function index(EstablishmentRepository $estabRepo, DepartmentRepository $deptRepo, Request $request)
    {
        // on récupère la liste de tous les établissements et de tous les départements
        $establishments = $estabRepo->findAll();
        $departments = $deptRepo->findAll();        

        // si le champ de recherche != de vide
        if (!empty($request->request->get('search'))) {

            // on fait une recherche par mot clé
            $search = $request->request->get('search');

            // dans toutes les colonnes de la table
            $result = $estabRepo->findBy(['name' => $search]);
            $result += $estabRepo->findBy(['adress' => $search]);
            $result += $estabRepo->findBy(['postalCode' => $search]);
            $result += $estabRepo->findBy(['city' => $search]);
            $result += $estabRepo->findBy(['latitude' => $search]);
            $result += $estabRepo->findBy(['longitude' => $search]);
            $result += $estabRepo->findBy(['backgroundUrl' => $search]);
            $result += $estabRepo->findBy(['slug' => $search]);

            // on compte le nombre de ligne que contient le tableau et si c'est == 0
            if (Count($result) == 0) {

                // on enregistre un message flash
                $this->addFlash('warning','Aucun résultat pour votre recherche');

                return $this->render('department/list.html.twig', [
                    // on envoie des données à la vue
                    'establishments' => $establishments,
                    'departments' => $departments,
                ]);
            }
            
            return $this->render('establishment/list.html.twig', [
                    // on envoie des données à la vue
                'establishments' => $result,
                'departments' => $departments,
            ]);
        }

        // si un filtre est selectionné
        if ($request->request->get('department_choice')) {
            $department = $request->request->get('department_choice');
            $establishments = $estabRepo->findBy(['department' => $department]);
            
            return $this->render('establishment/list.html.twig', [
            // on envoie des données à la vue
                'establishments' => $establishments,
                'departments' => $departments,
            ]);
        }

        return $this->render('establishment/list.html.twig', [
            // on envoie des données à la vue
            'establishments' => $establishments,
            'departments' => $departments,
        ]);
    }

    /**
     * @Route("/establishment/show/{id}", name="establishment_show")
     */
    public function show(Establishment $establishment, AdminRepository $adminRepo)
    {
        $admin = $adminRepo->findOneBy(['establishment' => $establishment]);

        // on retourne la vue et les données
        return $this->render('establishment/show.html.twig', [
            'establishment' => $establishment,
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/establishment/new", name="establishment_new")
     */
    public function create(ObjectManager $manager, Request $request, DepartmentRepository $deptRepo)
    {        
        $departments = $deptRepo->findAll();
        
        $establishment = new Establishment();

        $form = $this->createForm(EstablishmentType::class, $establishment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $dept_id = $request->request->get('id');
            
            $department = $deptRepo->findOneBy(['id' => $dept_id]);

            $establishment->setDepartment($department);
            $establishment->setCreatedAt(new \DateTime());

            if (!$establishment->getSlug()) {
                $slugify = new Slugify();
                $establishment->setSlug($slugify->slugify($establishment->getName()));
            }
            
            $manager->persist($establishment);
            $manager->flush();

            $this->addFlash('success','L\'établissement a bien été créé');

            return $this->redirectToRoute('establishment_show_list');
        }

        return $this->render('establishment/new.html.twig', [
            'form' => $form->createView(),
            'departments' => $departments,
        ]);
    }

    /** 
     * @Route("/establishment/modify/{id}", name="establishment_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Establishment $establishment, DepartmentRepository $deptRepo)
    {        

        $departments = $deptRepo->findAll();

        $form = $this->createForm(EstablishmentType::class, $establishment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $department = $request->request->get('department_choice');
            $department = $deptRepo->findOneBy(['id' => $department]);
            $establishment->setDepartment($department);

            $slugify = new Slugify();
            $establishment->setSlug($slugify->slugify($establishment->getName()));

            $manager->persist($establishment);
            $manager->flush();

            $this->addFlash('success','L\'établissement a bien été mis à jour !');

            return $this->redirectToRoute('establishment_show_list');
        }

        return $this->render('establishment/modify.html.twig', [
            'form' => $form->createView(),
            'departments' => $departments,
            'establishment' => $establishment,
        ]);
    }

    /**
     * @Route("/establishment/delete/{id}", name="establishment_delete")
     */
    public function delete(ObjectManager $manager, Establishment $establishment)
    {
        // on vérifie si l'établissement existe
        if ($establishment->getExist() == false){

            //On vérifie si l'établissement contient encore des classes
            if (Count($establishment->getclassrooms()) == 0) {

                // on supprime l'établissement sans possibilité de retour
                $manager->remove($establishment);
                $manager->flush();

                // on enregistre un message flash
                $this->addFlash('success', "L'établissement a bien été supprimé !");

                // on redirige vers la liste des établissements
                return $this->redirectToRoute('establishment_show_list'); 

            } else {

                // on enregistre un message flash
                $this->addFlash('danger', "Supprimez d'abord toutes les classes de l'établissements !");
                
                // on redirige vers la liste des établissements
                return $this->redirectToRoute('establishment_show_list');  
            }

        } else {

            // on enregistre un message flash
            $this->addFlash('danger', "L'établissement ne peut être supprimé car il existe !");
            
            // on redirige vers la liste des établissements
            return $this->redirectToRoute('establishment_show_list');  
        } 
    }
}
