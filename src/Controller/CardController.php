<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\CardRepository;
use App\Repository\AdminRepository;
use App\Repository\StudentRepository;
use App\Repository\SadminRepository;
use App\Repository\TeacherRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
    * @Route("/card/show_list", name="card_show_list")
    */
    public function index(CardRepository $cardRepo, StudentRepository $studentRepo, TeacherRepository $teacherRepo, AdminRepository $adminRepo, SadminRepository $sadminRepo, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {
        // Récupération de l'utilisateur connecté
        if ($this->getUser()) {

            $user = $this->getUser();

        }

        // ROLE_USER
        if ($user->getTitle() == "ROLE_USER") {

            $student = $studentRepo->findOneBy(['user' => $user]);

            $cards = $cardRepo->findBy(['student' => $student]);

            return $this->render('card/list.html.twig', [
                'cards' => $cards,
            ]);
        }

        // ROLE_TEACHER
        if ($user->getTitle() == "ROLE_TEACHER") {

            $teacher = $teacherRepo->findOneBy(['user' => $user]);

            $establishment = $teacher->getEstablishment();

            $classrooms = $teacher->getClassrooms();

            $students = [];
            foreach ($classrooms as $classroom) {
                $students += $studentRepo->findBy(['classroom' => $classroom]);
            }

            return $this->render('card/list.html.twig', [
                'students' => $students,
            ]);
        }

        // ROLE_ADMIN
        if ($user->getTitle() == "ROLE_ADMIN") {

            $admin = $adminRepo->findOneBy(['user' => $user]);

            $establishment = $admin->getEstablishment();

            $classrooms = $classroomRepo->findBy(['establishment' => $establishment]);

            return $this->render('card/list.html.twig', [
                'classrooms' => $classrooms,
            ]);
        }

        // ROLE_SADMIN
        if ($user->getTitle() == "ROLE_SADMIN") {

            $cards = $cardRepo->findAll();

            return $this->render('card/list.html.twig', [
                'cards' => $cards,
            ]);
        }
    }

    /**
    * @Route("/card/show/{id}", name="card_show")
    */
    public function show(Card $card)
    {

        return $this->render('card/show.html.twig', [
            "card" => $card,
        ]);
    }

    /**
    * @Route("/card/new", name="card_new")
    */
    public function create(ObjectManager $manager, Request $request, StudentRepository $studentRepo)
    {
        $card = new Card();

        $user = $this->getUser();

        $student = $studentRepo->findOneBy(['user' => $user]);

        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(CardType::class, $card);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            return $this->render('card/list.html.twig', [

            ]);
        }

        return $this->render('card/new.html.twig', [
            'form' => $form->createView(),
            'student' => $student,
        ]);
    }

    /**
    * @Route("/card/modify/{id}", name="card_modify")
    */
    public function modify(Card $card, ObjectManager $manager, Request $request)
    {
        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(CardType::class, $card);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            return $this->render('card/list.html.twig', [

            ]);
        }

        return $this->render('card/modify.html.twig', [
            'form' => $form->createView(),
            "card" => $card,
        ]);
    }

    // /**
    //  * @Route("/card/delete/{id}", name="card_delete")
    //  */
    // public function delete()
    // {
    //     return $this->render('card/.html.twig', [
    //         'controller_name' => 'CardController',
    //     ]);
    // }
}
