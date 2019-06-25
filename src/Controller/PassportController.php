<?php

namespace App\Controller;

use App\Entity\Passport;
use App\Repository\ActivityRepository;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\PassportRepository;
use App\Repository\SadminRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PassportController extends AbstractController
{
    /**
    * @Route("/passport/show/{id}", name="passport_show")
    */
    public function show(Passport $passport, ActivityRepository $activityRepo)
    {
        $activities = $activityRepo->findAll();


        return $this->render('passport/show.html.twig', [
            'passport' => $passport,
            'activities' => $activities,
        ]);
    }

    /**
    * @Route("/passport/modify/{id}", name="passport_modify")
    */
    public function modify()
    {
        return $this->render('passport/modify.html.twig', [
            'controller_name' => 'CardController',
        ]);
    }

    /**
     * @Route("/passport/delete/{id}", name="passport_delete")
     */
    public function delete()
    {
        return $this->render('passport/.html.twig', [
            'controller_name' => 'CardController',
        ]);
    }

}
