<?php

namespace App\Controller;

use App\Repository\CardspRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CardspController extends AbstractController
{
    /**
     * @Route("/passeport", name="index_passport")
     */
    public function index_passport()
    {
        
        
        return $this->render('cardsp/index_passport.html.twig', [
            
        ]);
    }


    /**
     * @Route("/fichesSP", name="index_cardsp")
     */
    public function index_cardsp(CardspRepository $cardsp_repo)
    {
        $cards = $cardsp_repo->findBy(['user' => 5]);

        return $this->render('cardsp/index_cardsp.html.twig', [
            'cards' => $cards,
        ]);
    }


    /**
     * @Route("/lire-ficheSP", name="show_cardsp")
     */
    public function show_cardsp()
    {
        

        return $this->render('cardsp/show_cardsp.html.twig', [
            
        ]);
    }


    /**
     * @Route("/creer-ficheSP", name="new_cardsp")
     */
    public function new_cardsp()
    {
        return $this->render('cardsp/new_cardsp.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }


    /**
     * @Route("/modifier-ficheSP", name="modify_cardsp")
     */
    public function modify_cardsp()
    {
        return $this->render('cardsp/modify_cardsp.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }


    /**
     * @Route("/dupliquer-ficheSP", name="duplicate_cardsp")
     */
    public function duplicate_cardsp()
    {
        return $this->render('cardsp/duplicate_cardsp.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }


    /**
     * @Route("/imprimer-ficheSP", name="print_cardsp")
     */
    public function print_cardsp()
    {
        return $this->render('cardsp/print_cardsp.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }


    /**
     * @Route("/supprimer-ficheSP", name="delete_cardsp")
     */
    public function delete_cardsp()
    {
        return $this->render('cardsp/delete_cardsp.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
}
