<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Establishment;
use App\Form\ModifyEstablishmentType;
use App\Form\NewEstablishmentType;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EstablishmentRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EstablishmentController extends AbstractController
{
    /**
     * Display establishments
     * 
     * @Route("/establishment/show_list", name="establishment_show_list")
     */
    public function index(EstablishmentRepository $estabRepo, DepartmentRepository $deptRepo, Request $request)
    {
        // on récupère la liste de tous les établissements et de tous les départements
        $estabs = $estabRepo->findAll();
        $depts = $deptRepo->findAll();        

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
                    'result' => $result,
                    'depts' => $depts,
                    'search' => $search,
                ]);
            }
            
            return $this->render('establishment/list.html.twig', [
                    // on envoie des données à la vue
                'result' => $result,
                'depts' => $depts,
                'search' => $search,
            ]);
        }

        // si un filtre est selectionné
        if ($request->request->get('department_choice')) {
            $dept_choice = $request->request->get('department_choice');
            $estabs = $estabRepo->findBy(['department' => $dept_choice]);
            
            return $this->render('establishment/list.html.twig', [
            // on envoie des données à la vue
                'estabs' => $estabs,
                'depts' => $depts,
                'dept_choice' => $dept_choice,
            ]);
        }

        return $this->render('establishment/list.html.twig', [
            // on envoie des données à la vue
            'estabs' => $estabs,
            'depts' => $depts,
        ]);
    }

    /**
     * show establishments
     * 
     * @Route("/establishment/show/{id}", name="establishment_show")
     */
    public function show(EstablishmentRepository $estabRepo, DepartmentRepository $deptRepo, Request $request, Establishment $establishment, ClassroomRepository $classroomRepo, AdminRepository $adminRepo)
    {
        // on récupère la liste de tous les établissements et de tous les départements
        $estab = $estabRepo->findOneBy(['id' => $establishment]);

        $classrooms = $classroomRepo->findBy(['establishment' => $establishment]);

        $admin = $adminRepo->findOneBy(['establishment' => $establishment]);

        return $this->render('establishment/show.html.twig', [
            // on envoie des données à la vue
            'estab' => $estab,
            'admin' => $admin,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * new establishment
     * 
     * @Route("/establishment/new", name="establishment_new")
     */
    public function create(ObjectManager $manager, Request $request, DepartmentRepository $deptRepo)
    {        
        $depts = $deptRepo->findAll();
        
        $establishment = new Establishment();

        $form = $this->createForm(NewEstablishmentType::class, $establishment);

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
            'depts' => $depts,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Update a establishment
     * 
     * @Route("/establishment/modify/{id}", name="establishment_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Establishment $establishment, DepartmentRepository $deptRepo)
    {        
        /**
         * {id} est automatiquement converti et associé à $establishment->getId
         */
        $depts = $deptRepo->findAll();

        $form = $this->createForm(ModifyEstablishmentType::class, $establishment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $dept_id = $request->request->get('id');
            
            $department = $deptRepo->findOneBy(['id' => $dept_id]);

            $establishment->setDepartment($department);

            $slugify = new Slugify();
            $establishment->setSlug($slugify->slugify($establishment->getName()));

            $manager->persist($establishment);
            $manager->flush();

            $this->addFlash('success','L\'établissement a bien été mis à jour !');

            return $this->redirectToRoute('establishment_show_list');
        }

        return $this->render('establishment/modify.html.twig', [
            'choice_exist' => $establishment->getExist(),
            'choice_dept' => $establishment->getDepartment()->getId(),
            'depts' => $depts,
            'form' => $form->createView(),
            'establishment' => $establishment,
        ]);
    }

    /**
     * Disable a establishment
     * 
     * @Route("/establishment/delete/{id}", name="establishment_delete")
     */
    public function delete(ObjectManager $manager, Establishment $establishment)
    {        
        if ($establishment->getExist() == false){

            $manager->remove($establishment);
            $manager->flush();

            $this->addFlash('success', "L'établissement a bien été supprimé !");
            
            return $this->redirectToRoute('establishment_show_list');            
        } else {
            $this->addFlash('danger', "L'établissement ne peut être supprimé car il existe !");
            
            return $this->redirectToRoute('establishment_show_list');  
        } 
    }
}
