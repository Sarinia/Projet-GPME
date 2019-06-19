<?php

namespace App\Controller;

use App\Repository\EstablishmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EstablishmentController extends AbstractController
{
    /**
     * @Route("/etablissements", name="index_establishment")
     */
    public function index_establishment(EstablishmentRepository $estab_repo)
    {
        $establishments = $estab_repo->findAll();

        return $this->render('establishment/index_establishment.html.twig', [
            'establishments' => $establishments,
        ]);
    }


    /**
     * @Route("/lire-etablissement", name="show_establishment")
     */
    public function show_establishment()
    {
        return $this->render('establishment/show_establishment.html.twig', [
            'controller_name' => 'EstablishmentController',
        ]);
    }


    /**
     * @Route("/creer-etablissement", name="new_establishment")
     */
    public function new_establishment()
    {
        return $this->render('establishment/new_establishment.html.twig', [
            'controller_name' => 'EstablishmentController',
        ]);
    }


    /**
     * @Route("/modifier-etablissement", name="edit_establishment")
     */
    public function edit_establishment()
    {
        return $this->render('establishment/edit_establishment.html.twig', [
            'controller_name' => 'EstablishmentController',
        ]);
    }


    /**
     * @Route("/supprimer-etablissement", name="delete_establishment")
     */
    public function delete_establishment()
    {
        return $this->render('establishment/delete_establishment.html.twig', [
            'controller_name' => 'EstablishmentController',
        ]);
    }
}
