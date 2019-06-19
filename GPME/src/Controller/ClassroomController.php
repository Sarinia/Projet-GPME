<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ModifyClassroomType;
use App\Form\NewClassroomType;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class ClassroomController extends AbstractController
{
    /**
     * @Route("/classroom/show_list", name="classroom_show_list")
     */
    public function index(ClassroomRepository $classroomRepo, EstablishmentRepository $estabRepo,Request $request)
    {
        // requete de toutes les classes et tous les établissements de la BDD
        $classrooms = $classroomRepo->findAll();
        $establishments = $estabRepo->findAll();
        
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
                $this->addFlash('warning','Aucun résultat pour votre recherche');

                // on retourne la vue et les données
                return $this->render('classroom/list.html.twig', [
                    'classrooms' => $classrooms,
                    'establishments' => $establishments,
                ]);
            }

            // on retourne la vue et les données
            return $this->render('classroom/list.html.twig', [
                'classrooms' => $result,
                'establishments' => $establishments,
            ]);
        }

        // si un filtre est selectionné
        if ($request->request->get('establishment_choice')) {
            $establishment = $request->request->get('establishment_choice');
            $classrooms = $classroomRepo->findBy(['establishment' => $establishment]);

            // on retourne la vue et les données
            return $this->render('classroom/list.html.twig', [
                'classrooms' => $classrooms,
                'establishments' => $establishments,
            ]);
        }

        // on retourne la vue et les données
        return $this->render('classroom/list.html.twig', [
            'classrooms' => $classrooms,
            'establishments' => $establishments,
        ]);
    }

    /**
     * @Route("/classroom/show/{id}", name="classroom_show")
     */
    public function show(Classroom $classroom)
    {
        // on retourne la vue et les données
        return $this->render('classroom/show.html.twig', [
            'classroom' => $classroom,
        ]);
    }

    /**
     * @Route("/classroom/new", name="classroom_new")
     */
    public function create(ObjectManager $manager,Request $request, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {   
        // requete de tous les établissements de la BDD
        $establishments = $estabRepo->findAll();

        // on instancie un nouveau Classroom
        $classroom = new Classroom();

        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(NewClassroomType::class, $classroom);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on récupére l'établissement et on l'attribut à la classe
            $establishment = $request->request->get('classroom_choice');
            $establishment = $estabRepo->findOneBy(['id' => $establishment]);
            $classroom->setEstablishment($establishment);

            // on instancie Slugify
            $slugify = new Slugify();

            // on récupère le diplome
            $degree = $classroom->getDegree();

            // on récupère l'année des dates
            $startDate = $classroom->getStartDate();
            $endDate = $classroom->getEndDate();

            // on récupère le nombre de ligne qu'il y a dans la table classroom pour l'établissement
            $ligne = $classroom->getId();

            // on construit le slug et on le transmet
            $slug = $slugify->slugify($degree."-".$ligne."-".$startDate."-".$endDate);
            $classroom->setSlug($slug);

            $classroom->setCreatedAt(new \DateTime());

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($classroom);
            $manager->flush();

            // on stocke un message flash
            $this->addFlash('success','La classe a bien été créé');

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('classroom_show_list');
        }

        // on retourne la vue et les données
        return $this->render('classroom/new.html.twig', [
            'establishments' => $establishments,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/classroom/modify/{id}", name="classroom_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Classroom $classroom, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {        
        // requete de tous les établissements de la BDD
        $establishments = $estabRepo->findAll();

        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(ModifyClassroomType::class, $classroom);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on récupére l'établissement et on l'attribut à la classe
            $establishment = $request->request->get('establishment_choice');
            $establishment = $estabRepo->findOneBy(['id' => $establishment]);
            $classroom->setEstablishment($establishment);

            // on instancie
            $slugify = new Slugify();

            // on récupère le diplome
            $degree = $classroom->getDegree();

            // on récupère l'année des dates
            $startDate = $classroom->getStartDate();
            $endDate = $classroom->getEndDate();

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

        // on retourne la vue et les données
        return $this->render('classroom/modify.html.twig', [
            'form' => $form->createView(),
            'establishments' => $establishments,
            'classroom' => $classroom,
        ]);
    }

    /**
     * @Route("/classroom/delete/{id}", name="classroom_delete")
     */
    public function delete(ObjectManager $manager, Classroom $classroom)
    {    
        // on vérifie que la classe est inactive
        if ($classroom->getExist() == false){

            // on supprime la ligne de la table classe
            $manager->remove($classroom);
            $manager->flush();

            // on stocke un message flash
            $this->addFlash('success', "La classe a bien été supprimée !");
            
            // on redirige vers la liste des classes
            return $this->redirectToRoute('classroom_show_list');            
        } else {

            // on stocke un message flash
            $this->addFlash('danger', "La classe ne peut être supprimée car elle existe !");
            
            // on redirige vers la liste des classes
            return $this->redirectToRoute('classroom_show_list');  
        } 
    }
}
