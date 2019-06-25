<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\ActivityRepository;
use App\Repository\AdminRepository;
use App\Repository\CardRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\ModalityRepository;
use App\Repository\PassportRepository;
use App\Repository\ProblemRepository;
use App\Repository\SadminRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Repository\TermRepository;
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

            return $this->render('card/list.html.twig', [
                'student' => $student,
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
    public function show(Card $card, ProblemRepository $problemRepo, ModalityRepository $modalityRepo, TermRepository $termRepo, ActivityRepository $activityRepo)
    {
        $problems = $problemRepo->findAll();
        $modalities = $modalityRepo->findAll();
        $terms = $termRepo->findAll();
        $activities = $activityRepo->findAll();

        return $this->render('card/show.html.twig', [
            "card" => $card,
            "problems" => $problems,
            "modalities" => $modalities,
            "terms" => $terms,
            "activities" => $activities,
        ]);
    }

    /**
    * @Route("/card/new", name="card_new")
    */
    public function create(ObjectManager $manager, Request $request, StudentRepository $studentRepo, PassportRepository $passportRepo)
    {
        $card = new Card();

        // on récupére l'utilisateur
        $user = $this->getUser();
        $student = $studentRepo->findOneBy(['user' => $user]);

        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(CardType::class, $card);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            $card->setCreatedAt(new \DateTime());

            $numbersp = Count($student->getCards());
            $numbersp = $numbersp + 1;

            $card->setNumbersp($numbersp);

            $card->setStudent($student);

            $passport = $passportRepo->findOneBy(['student' => $student]);
            $card->setPassport($passport);

            $manager->persist($card);
            $manager->flush();

            return $this->redirectToRoute('card_show_list', []);
        }

        return $this->render('card/new.html.twig', [
            'form' => $form->createView(),
            'student' => $student,
        ]);
    }

    /**
    * @Route("/card/modify/{id}", name="card_modify")
    */
    public function modify(Card $card, ObjectManager $manager, Request $request, StudentRepository $studentRepo)
    {
        // on récupére l'utilisateur
        $user = $this->getUser();
        $student = $studentRepo->findOneBy(['user' => $user]);

        // Création du formulaire à partir du fichier card
        $form = $this->createForm(CardType::class, $card);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($card);
            $manager->flush();

            return $this->redirectToRoute('card_show_list', []);
        }

        return $this->render('card/modify.html.twig', [
            'form' => $form->createView(),
            "card" => $card,
        ]);
    }

    /**
    * @Route("/card/duplicate/{id}", name="card_duplicate")
    */
    public function duplicate(Card $card, ObjectManager $manager, Request $request, StudentRepository $studentRepo, PassportRepository $passportRepo)
    {
        // on récupére l'utilisateur
        $user = $this->getUser();
        $student = $studentRepo->findOneBy(['user' => $user]);

        // Création du formulaire à partir du fichier NewAdminType
        $form = $this->createForm(CardType::class, $card);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            $card = new Card();

            $card->setCreatedAt(new \DateTime());

            $numbersp = Count($student->getCards());
            $numbersp = $numbersp + 1;

            $card->setNumbersp($numbersp);

            $card->setExist($form->getData('card')->getExist());

            $card->setStudent($student);

            $passport = $passportRepo->findOneBy(['student' => $student]);
            $card->setPassport($passport);

            $manager->persist($card);
            $manager->flush();

            return $this->redirectToRoute('card_show_list', []);
        }

        return $this->render('card/modify.html.twig', [
            'form' => $form->createView(),
            "card" => $card,
            "student" => $student,
        ]);
    }

    /**
     * @Route("/card/delete/{id}", name="card_delete")
     */
    public function delete(Card $card, ObjectManager $manager)
    {
        // on récupére l'utilisateur
        $user = $admin->getUser();
        $student = $studentRepo->findOneBy(['user' => $user]);

        // on vérifie que son compte est inactif
        if ($card->getExist() == false && $card->getStudent() == $student || $user->roles() == "ROLE_SADMIN"){

            // on supprime la ligne de la table fiche
            $manager->remove($card);
            $manager->flush();

            // on enregistre un message flash
            $this->addFlash('success', "La fiche a bien été supprimé !");

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('card_show_list');
        } else {

            // on enregistre un message flash
            $this->addFlash('danger', "La fiche ne peut être supprimé car elle existe !");

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('card_show_list');
        }
    }
}
