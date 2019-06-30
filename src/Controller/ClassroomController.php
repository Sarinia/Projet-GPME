<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\TeacherRepository;
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
    public function index(AdminRepository $adminRepo, TeacherRepository $teacherRepo, ClassroomRepository $classroomRepo, EstablishmentRepository $estabRepo,Request $request)
    {

        if ($this->getUser()) {

            // liste des classes pour le super-admin
            if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

                // requete de toutes les classes et tous les établissements de la BDD
                $classrooms = $classroomRepo->findAll();
                $establishments = $estabRepo->findAll();

                // on retourne la vue et les données
                return $this->render('classroom/list.html.twig', [
                    'classrooms' => $classrooms,
                    'establishments' => $establishments,
                ]);
            }

            // liste des classes pour l'admin
            if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

                $admin = $adminRepo->findOneBy(['user' => $this->getUser()]);

                // requete de toutes les classes et tous les établissements de la BDD
                $classrooms = $admin->getEstablishment()->getClassrooms();
                $establishments[] = $admin->getEstablishment();

                // on retourne la vue et les données
                return $this->render('classroom/list.html.twig', [
                    'classrooms' => $classrooms,
                    'establishments' => $establishments,
                ]);
            }

            // liste des classes pour le teacher
            if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

                $teacher = $teacherRepo->findOneBy(['user' => $this->getUser()]);

                // requete de toutes les classes et tous les établissements de la BDD
                $classrooms = $teacher->getClassrooms();
                $establishments[] = $teacher->getEstablishment();

                // on retourne la vue et les données
                return $this->render('classroom/list.html.twig', [
                    'classrooms' => $classrooms,
                    'establishments' => $establishments,
                ]);
            }
        }
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
    public function create(ObjectManager $manager,Request $request)
    {   
        // on instancie un nouveau Classroom
        $classroom = new Classroom();

        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(ClassroomType::class, $classroom);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // slug
            $slugify = new Slugify();
            $degree = $classroom->getDegree();
            $startDate = $classroom->getStartDate();
            $endDate = $classroom->getEndDate();
            $ligne = $classroom->getId();
            $slug = $slugify->slugify($degree."-".$ligne."-".$startDate."-".$endDate);
            $classroom->setSlug($slug);

            // createdAt
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
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/classroom/modify/{id}", name="classroom_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Classroom $classroom, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {        


        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(ClassroomType::class, $classroom);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // slug
            $slugify = new Slugify();
            $degree = $classroom->getDegree();
            $startDate = $classroom->getStartDate();
            $endDate = $classroom->getEndDate();
            $ligne = $classroom->getId();
            $slug = $slugify->slugify($degree."-".$ligne."-".$startDate."-".$endDate);
            $classroom->setSlug($slug);
            
            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($classroom);
            $manager->flush();

            // on stocke un message flash
            $this->addFlash('success','Le département a bien été mis à jour !');

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('classroom_show', [
                'id' => $classroom->getId(),
            ]);
        }

        // on retourne la vue et les données
        return $this->render('classroom/modify.html.twig', [
            'form' => $form->createView(),
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
