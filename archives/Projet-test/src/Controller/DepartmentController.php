<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\ModifyDepartmentType;
use App\Form\NewDepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    /**
     * Display departments
     * 
     * @Route("/department/show_list", name="department_show_list")
     */
    public function index(DepartmentRepository $deptRepo)
    {        
        $depts = $deptRepo->findAll();

        return $this->render('department/list.html.twig', [
            'depts' => $depts,
        ]);
    }

    /**
     * new department
     * 
     * @Route("/department/new", name="department_new")
     */
    public function create(ObjectManager $manager,Request $request)
    {        
        $department = new Department();

        $form = $this->createForm(NewDepartmentType::class, $department);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($department);
            $manager->flush();

            $this->addFlash('success','Le département a bien été créé');

            return $this->redirectToRoute('department_show_list');
        }

        return $this->render('department/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Update a department
     * 
     * @Route("/department/modify/{slug}/{id}", name="department_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Department $department)
    {        
        /**
         * {id} est automatiquement converti et associé à $department->getId
         */

        $form = $this->createForm(ModifyDepartmentType::class, $department);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($department);
            $manager->flush();

            $this->addFlash('success','Le département a bien été mis à jour !');

            return $this->redirectToRoute('department_show_list');
        }

        return $this->render('department/modify.html.twig', [
            'choice_exist' => $department->getExist(),
            'form' => $form->createView(),
            'department' => $department,
        ]);
    }

    /**
     * Delete a department
     * Needs to delete all establisments first
     * 
     * @Route("/department/delete/{id}", name="department_delete")
     */
    public function delete(ObjectManager $manager, Department $department)
    {        
        if ($department->getExist() == false){

            $manager->remove($department);
            $manager->flush();

            $this->addFlash('success', "Le département a bien été supprimé !");
            
            return $this->redirectToRoute('department_show_list');            
        } else {
            $this->addFlash('danger', "Supprimez d'abord tous les établissements du département !");
            
            return $this->redirectToRoute('department_show_list');  
        } 
    }
}
