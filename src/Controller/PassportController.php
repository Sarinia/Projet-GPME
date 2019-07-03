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

        $cards = $passport->getCards();

        foreach ($cards as $card) {

            if ($card->getAssociate() == true) {

                $cardsActivity1 = [];
                if ($card->getActivity()->getNumber() == "Activité 1.1") {
                    $cardsActivity1 = $card;
                }
                $cardsActivity2 = [];
                if ($card->getActivity()->getNumber() == "Activité 1.2") {
                    $cardsActivity2 = $card;
                }

                $cardsActivity3 = [];
                if ($card->getActivity()->getNumber() == "Activité 1.3") {
                    $cardsActivity3 = $card;
                }

                $cardsActivity4 = [];
                if ($card->getActivity()->getNumber() == "Activité 1.4") {
                    $cardsActivity4 = $card;
                }

                $cardsActivity5 = [];
                if ($card->getActivity()->getNumber() == "Activité 1.5") {
                    $cardsActivity5 = $card;
                }

                $cardsActivity6 = [];
                if ($card->getActivity()->getNumber() == "Activité 1.6") {
                    $cardsActivity6 = $card;
                }

                return $this->render('passport/show.html.twig', [
                    'passport' => $passport,
                    'student' => $passport->getStudent(),
                    'activities' => $activities,
                    'cardsActivity1' => $cardsActivity1,
                    'cardsActivity2' => $cardsActivity2,
                    'cardsActivity3' => $cardsActivity3,
                    'cardsActivity4' => $cardsActivity4,
                    'cardsActivity5' => $cardsActivity5,
                    'cardsActivity6' => $cardsActivity6,
                ]);
            }
        }

        return $this->render('passport/show.html.twig', [
            'passport' => $passport,
            'student' => $passport->getStudent(),
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
