<?php

namespace App\Controller;

use App\Repository\ClassroomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    /**
     * @Route("/classes", name="index_classroom")
     */
    public function index_classroom(ClassroomRepository $classroom_repo)
    {
        $classrooms = $classroom_repo->findAll();

        return $this->render('classroom/index_classroom.html.twig', [
            'classrooms' => $classrooms,
        ]);
    }


    /**
     * @Route("/classe", name="show_classroom")
     */
    public function show_classroom()
    {
        return $this->render('classroom/show_classroom.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }


    /**
     * @Route("/creer-classe", name="new_classroom")
     */
    public function new_classroom()
    {
        return $this->render('classroom/new_classroom.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }


    /**
     * @Route("/modifier-classe", name="edit_classroom")
     */
    public function edit_classroom()
    {
        return $this->render('classroom/edit_classroom.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }


    /**
     * @Route("/supprimer-classe", name="delete_classroom")
     */
    public function delete_classroom()
    {
        return $this->render('classroom/delete_classroom.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
}
