<?php

namespace App\Controller;

use App\Entity\Passport;
use App\Repository\ActivityRepository;
use App\Repository\CardRepository;
use App\Repository\ClassroomRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PassportController extends AbstractController
{
    /**
     * @Route("/passport/show_list", name="passport_show_list")
     */
    public function index(ClassroomRepository $classroomRepo)
    {
    	if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

    		return $this->render('passport/list.html.twig', [
    			'classrooms' => $classroomRepo->findAll(),
    		]);
    	}

    	if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

    		$classrooms = $this->getUser()->getAdmin()->getEstablishment()->getClassrooms();

    		return $this->render('passport/list.html.twig', [
    			'classrooms' => $classrooms,
    		]);
    	}

    	if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

    		$classrooms = $this->getUser()->getTeacher()->getClassrooms();

    		return $this->render('passport/list.html.twig', [
    			'classrooms' => $classrooms,
    		]);
    	}

    	if ($this->getUser()->getTitle() == "ROLE_USER") {

    		$classrooms = $this->getUser()->getStudent()->getClassrooms();

    		return $this->render('passport/list.html.twig', [
    			'classrooms' => $classrooms,
    		]);
    	}
    }

    /**
     * @Route("/passport/show/{id}", name="passport_show")
     */
    public function show(Passport $passport, ActivityRepository $activityRepo, TaskRepository $taskRepo, CardRepository $cardRepo)
    {
    	return $this->render('passport/show.html.twig', [
            'activities' => $activityRepo->findAll(),
            'tasks' => $taskRepo->findAll(),
            'cards' => $cardRepo->findBy(['passport' => $passport]),
            'passport' => $passport,
        ]);
    }

    /**
    * @Route("/passport/print/{id}", name="passport_print")
    */
    public function print(Passport $passport, ActivityRepository $activityRepo, TaskRepository $taskRepo, CardRepository $cardRepo)
    {
        return $this->render('passport/print.html.twig', [
            'activities' => $activityRepo->findAll(),
            'tasks' => $taskRepo->findAll(),
            'cards' => $cardRepo->findBy(['passport' => $passport]),
            'passport' => $passport,
        ]);
    }
}