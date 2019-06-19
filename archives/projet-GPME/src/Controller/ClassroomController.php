<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Classroom;
use Cocur\Slugify\Slugify;
use App\Form\NewClassroomType;
use App\Form\ModifyClassroomType;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassroomController extends AbstractController
{
    /**
     * Display classrooms
     * 
     * @Route("/classroom/show_list", name="classroom_show_list")
     */
    public function index(ClassroomRepository $classroomRepo, EstablishmentRepository $estabRepo,Request $request)
    {
        // requete de toutes les classes et tous les établissements de la BDD
        $classrooms = $classroomRepo->findAll();
        $estabs = $estabRepo->findAll();
        
        // si le champ de recherche != de vide
        if (!empty($request->request->get('search'))) {

            // on fait une recherche par mot clé
            $search = $request->request->get('search');

            // dans toutes les colonnes de la table
            $result = $classroomRepo->findBy(['degree' => $search]);
            $result += $classroomRepo->findBy(['startDate' => $search]);
            $result += $classroomRepo->findBy(['endDate' => $search]);

            // on compte le nombre de ligne que contient le tableau et si c'est == 0
            if (Count($result) == 0) {

                // on enregistre un message flash
                $this->addFlash('success','Aucun résultat pour votre recherche');

                return $this->render('classroom/list.html.twig', [
                    // on envoie des données à la vue
                    'result' => $result,
                    'estabs' => $estabs,
                    'search' => $search,
                ]);
            }

            return $this->render('classroom/list.html.twig', [
                    // on envoie des données à la vue
                'result' => $result,
                'estabs' => $estabs,
                'search' => $search,
            ]);
        }

        // si un filtre est selectionné
        if ($request->request->get('establishment_choice')) {
            $estab_choice = $request->request->get('establishment_choice');
            $classrooms = $classroomRepo->findBy(['establishment' => $estab_choice]);

            return $this->render('classroom/list.html.twig', [
            // on envoie des données à la vue
                'classrooms' => $classrooms,
                'estabs' => $estabs,
                'estab_choice' => $estab_choice,
            ]);
        }

        return $this->render('classroom/list.html.twig', [
            // envoie de la liste des classes à la vue
            'classrooms' => $classrooms,
            'estabs' => $estabs,
        ]);
    }

    /**
     * new classroom
     * 
     * @Route("/classroom/new", name="classroom_new")
     */
    public function create(ObjectManager $manager,Request $request, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {   
        // requete de tous les établissements de la BDD
        $estabs = $estabRepo->findAll();

        // on instancie un nouveau user
        $classroom = new Classroom();

        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(NewClassroomType::class, $classroom);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on récupére l'établissement et on l'attribut à la classe
            $estab_id = $request->request->get('id');
            $estab = $estabRepo->findOneBy(['id' => $estab_id]);
            $classroom->setEstablishment($estab);

            // on instancie Slugify
            $slugify = new Slugify();

            // on récupère le diplome
            $degree = $classroom->getDegree();

            // on récupère l'année des dates
            $startDate = date_format($classroom->getStartDate(), 'Y');
            $endDate = date_format($classroom->getEndDate(), 'Y');

            // on récupère le nombre de ligne qu'il y a dans la table classroom pour l'établissement
            $ligne = $classroom->getId();

            // on construit le slug et on le transmet
            $slug = $slugify->slugify($degree."-".$ligne."-".$startDate."-".$endDate);
            $classroom->setSlug($slug);

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($classroom);
            $manager->flush();

            // on stocke un message flash
            $this->addFlash('success','La classe a bien été créé');

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('classroom_show_list');
        }

        return $this->render('classroom/new.html.twig', [
            // on envoie le formulaire à la vue et la liste des établissements
            'estabs' => $estabs,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Update a classroom
     * 
     * @Route("/classroom/modify/{id}", name="classroom_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Classroom $classroom, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {        
        /**
         * {id} est automatiquement converti et associé à $classroom->getId
         */

        // requete de tous les établissements de la BDD
        $estabs = $estabRepo->findAll();

        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(ModifyClassroomType::class, $classroom);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on récupére l'établissement et on l'attribut à la classe
            $estab_id = $request->request->get('id');
            $estab = $estabRepo->findOneBy(['id' => $estab_id]);
            $classroom->setEstablishment($estab);

            // on instancie
            $slugify = new Slugify();

            // on récupère le diplome
            $degree = $classroom->getDegree();

            // on récupère l'année des dates
            $startDate = date_format($classroom->getStartDate(), 'Y');
            $endDate = date_format($classroom->getEndDate(), 'Y');

            // on récupère le nombre de ligne qu'il y a dans la table classroom pour l'établissement
            $ligne = $classroom->getId();

            // on construit le slug et on le transmet
            $slug = $slugify->slugify($degree."-".$ligne."-".$startDate."-".$endDate);
            $classroom->setSlug($slug);
            
            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($classroom);
            $manager->flush();

            // on stocke un message flash
            $this->addFlash('success','Le département a bien été mis à jour !');

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('classroom_show_list');
        }


        return $this->render('classroom/modify.html.twig', [
            // on envoie le formulaire à la vue et la liste des établissements
            'choix_estab' => $classroom->getEstablishment()->getId(),
            'estabs' => $estabs,
            'form' => $form->createView(),
            'classroom' => $classroom,
        ]);
    }

    /**
     * Delete a classroom
     * 
     * @Route("/classroom/delete/{id}", name="classroom_delete")
     */
    public function delete(ObjectManager $manager, Classroom $classroom)
    {        
        if ($classroom->getExist() == false){

            $manager->remove($classroom);
            $manager->flush();

            $this->addFlash('success', "La classe a bien été supprimée !");
            
            return $this->redirectToRoute('classroom_show_list');            
        } else {
            $this->addFlash('danger', "La classe ne peut être supprimée car elle existe !");
            
            return $this->redirectToRoute('classroom_show_list');  
        } 
    }
}
