<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/departement", name="show_department")
     */
    public function show_department()
    {
        return $this->render('department/show_department.html.twig', [
            'controller_name' => 'DepartmentController',
        ]);
    }


    /**
     * @Route("/creer-departement", name="new_department")
     */
    public function new_department()
    {
        return $this->render('department/new_department.html.twig', [
            'controller_name' => 'DepartmentController',
        ]);
    }


    /**
     * @Route("/modifier-departement", name="edit_department")
     */
    public function edit_department()
    {
        return $this->render('department/edit_department.html.twig', [
            'controller_name' => 'DepartmentController',
        ]);
    }


    /**
     * @Route("/supprimer-departement", name="delete_department")
     */
    public function delete_department()
    {
        return $this->render('department/delete_department.html.twig', [
            'controller_name' => 'DepartmentController',
        ]);
    }
}
