<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\ActivityRepository;
use App\Repository\ModalityRepository;
use App\Repository\ProblemRepository;
use App\Repository\StudentRepository;
use App\Repository\TaskRepository;
use App\Repository\TermRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
    * @Route("/card/show_list", name="card_show_list")
    */
    public function index(StudentRepository $studentRepo)
    {
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            return $this->render('card/list.html.twig', [
                'students' => $studentRepo->findAll(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

            $classrooms = $this->getUser()->getAdmin()->getEstablishment()->getClassrooms();

            return $this->render('card/list.html.twig', [
                'classrooms' => $classrooms,
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

            $classrooms = $this->getUser()->getTeacher()->getClassrooms();

            return $this->render('card/list.html.twig', [
                'classrooms' => $classrooms,
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_USER") {

            $student = $this->getUser()->getStudent();

            return $this->render('card/list.html.twig', [
                'student' => $student,
            ]);
        }
    }

    /**
    * @Route("/card/show/{id}", name="card_show")
    */
    public function show(Card $card, TaskRepository $taskRepo, ProblemRepository $problemRepo, ModalityRepository $modalityRepo, TermRepository $termRepo, ActivityRepository $activityRepo)
    {
        dump($card);
        return $this->render('card/show.html.twig', [
            "card" => $card,
            "problems" => $problemRepo->findAll(),
            "modalities" => $modalityRepo->findAll(),
            "terms" => $termRepo->findAll(),
            "activities" => $activityRepo->findAll(),
            "tasks" => $taskRepo->findAll(),
        ]);
    }

    /**
    * @Route("/card/new", name="card_new")
    */
    public function create(ObjectManager $manager, Request $request, StudentRepository $studentRepo, ActivityRepository $activityRepo, TaskRepository $taskRepo)
    {
        $card = new Card();

        $student = $this->getUser()->getStudent();

        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // taches
            if (count($request->request->get('task')) == 1) {
                $tasks = $request->request->get('task');
                foreach ($tasks as $task) {
                    $task = $taskRepo->findOneBy(['id' => $task]);
                    $card->setTask($task);
                }
            } else {

                $this->addFlash('warning', "Vous devez choisir une seule tâche !");

                return $this->render('card/new.html.twig', [
                    'form' => $form->createView(),
                    'student' => $student,
                    'activities' => $activityRepo->findAll(),
                ]);
            }
            
            $card->setCreatedAt(new \DateTime());

            $numbersp = Count($student->getCards());
            $numbersp = $numbersp + 1;

            $card->setNumbersp($numbersp);

            $card->setStudent($student);

            if ($request->request->get('card')['associate'] == true) {
                $passport = $student->getPassport();
                $card->setPassport($passport);
            } else {
                $card->setPassport(null);
            }
            
            $manager->persist($card);
            $manager->flush();

            $this->addFlash('success', "Votre fiche a bien été créée.");

            return $this->redirectToRoute('card_show_list');
        }

        return $this->render('card/new.html.twig', [
            'form' => $form->createView(),
            'student' => $student,
            'activities' => $activityRepo->findAll(),
        ]);
    }

    /**
    * @Route("/card/modify/{id}", name="card_modify")
    */
    public function modify(Card $card, ObjectManager $manager, Request $request, ActivityRepository $activityRepo, TaskRepository $taskRepo)
    {
        if ($this->getUser()->getTitle() != "ROLE_USER") {
            $student = $card->getStudent();
        } else {
            $student = $this->getUser()->getStudent();
        }

        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // taches
            if (count($request->request->get('task')) == 1) {
                $tasks = $request->request->get('task');
                foreach ($tasks as $task) {
                    $task = $taskRepo->findOneBy(['id' => $task]);
                    $card->setTask($task);
                }
            } else {

                $this->addFlash('warning', "Vous devez choisir une seule tâche !");

                return $this->render('card/modify.html.twig', [
                    'form' => $form->createView(),
                    'student' => $student,
                    'activities' => $activityRepo->findAll(),
                ]);
            }

            if ($request->request->get('card')['associate'] == true) {
                $passport = $student->getPassport();
                $card->setPassport($passport);
            } else {
                $card->setPassport(null);
            }

            $manager->persist($card);
            $manager->flush();

            $this->addFlash('success', "Votre fiche a bien été modifiée.");

            return $this->redirectToRoute('card_show_list');
        }

        return $this->render('card/modify.html.twig', [
            'form' => $form->createView(),
            "card" => $card,
            'activities' => $activityRepo->findAll(),
        ]);
    }

    /**
     * @Route("/card/enable/{id}", name="card_enable")
     */
    public function enable(ObjectManager $manager, Request $request, Card $card)
    {
        $card->setExist(1);
        $manager->persist($card);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/card/disable/{id}", name="card_disable")
     */
    public function disable(ObjectManager $manager, Request $request, Card $card)
    {
        $card->setExist(0);
        $manager->persist($card);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
    * @Route("/card/print/{id}", name="card_print")
    */
    public function print(Card $card, ProblemRepository $problemRepo, ModalityRepository $modalityRepo, TermRepository $termRepo, ActivityRepository $activityRepo)
    {
        return $this->render('card/print.html.twig', [
            "card" => $card,
            "problems" => $problemRepo->findAll(),
            "modalities" => $modalityRepo->findAll(),
            "terms" => $termRepo->findAll(),
            "activities" => $activityRepo->findAll(),
        ]);
    }

    /**
    * @Route("/card/duplicate/{id}", name="card_duplicate")
    */
    public function duplicate(Card $card, ObjectManager $manager, Request $request)
    {
        $newCard = clone $card;

        $manager->persist($newCard);
        $manager->flush();

        $this->addFlash('success', "Votre fiche a bien été dupliquée.");

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/card/delete/{id}", name="card_delete")
     */
    public function delete(Card $card, ObjectManager $manager, Request $request)
    {
        if ($card->getExist() == false && $this->getUser()->getTitle() == "ROLE_SADMIN"){

            $manager->remove($card);
            $manager->flush();

            $this->addFlash('success', "La fiche a bien été supprimé !");

            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);
        } else {

            $this->addFlash('danger', "La fiche ne peut être supprimé car elle existe !");

            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);
        }
    }
}