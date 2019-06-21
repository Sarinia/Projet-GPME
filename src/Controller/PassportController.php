<?php

namespace App\Controller;

use App\Entity\Passport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PassportController extends AbstractController
{
    /**
    * @Route("/passport/show/{id}", name="passport_show")
    */
    public function show(Passport $passport)
    {
        return $this->render('passport/show.html.twig', [
            'controller_name' => 'CardController',
        ]);
    }

    // /**
    // * @Route("/passport/new", name="passport_new")
    // */
    // public function create()
    // {
    //     return $this->render('passport/new.html.twig', [
    //         'controller_name' => 'CardController',
    //     ]);
    // }

    /**
    * @Route("/passport/show/{id}", name="passport_modify")
    */
    public function modify()
    {
        return $this->render('passport/modify.html.twig', [
            'controller_name' => 'CardController',
        ]);
    }

    // /**
    //  * @Route("/passport/delete/{id}", name="passport_delete")
    //  */
    // public function delete()
    // {
    //     return $this->render('passport/.html.twig', [
    //         'controller_name' => 'CardController',
    //     ]);
    // }
}
