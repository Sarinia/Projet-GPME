<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    /**
     * @Route("/classroom/show_list", name="classroom_show_list")
     */
    public function index(ClassroomRepository $classroomRepo)
    {
        if ($this->getUser()) {

            if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

                return $this->render('classroom/list.html.twig', [
                    'classrooms' => $classroomRepo->findAll(),
                ]);
            }

            if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

                $admin = $this->getUser()->getAdmin();

                return $this->render('classroom/list.html.twig', [
                    'classrooms' => $admin->getEstablishment()->getClassrooms(),
                ]);
            }

            if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

                $teacher = $this->getUser()->getTeacher();

                return $this->render('classroom/list.html.twig', [
                    'classrooms' => $teacher->getClassrooms(),
                ]);
            }

            if ($this->getUser()->getTitle() == "ROLE_USER") {

                $student = $this->getUser()->getStudent();

                return $this->render('classroom/list.html.twig', [
                    'classrooms' => $student->getClassrooms(),
                ]);
            }
        }
    }

    /**
     * @Route("/classroom/show/{id}", name="classroom_show")
     */
    public function show(Classroom $classroom)
    {
        return $this->render('classroom/show.html.twig', [
            'classroom' => $classroom,
            'teachers' => $classroom->getTeachers(),
            'students' => $classroom->getStudents(),
            'establishment' => $classroom->getEstablishment(),
            'admins' => $classroom->getEstablishment()->getAdmins(),
        ]);
    }

    /**
     * @Route("/classroom/new", name="classroom_new")
     */
    public function create(ObjectManager $manager,Request $request, EstablishmentRepository $establishmentRepo, AdminRepository $adminRepo)
    {   
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            $classroom = new Classroom();

            $form = $this->createForm(ClassroomType::class, $classroom);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){

                // établissement
                $establishment = $request->request->get('establishment');
                $establishment = $establishmentRepo->findOneBy(['id' => $establishment]);
                $classroom->setEstablishment($establishment);

                // slug
                $slugify = new Slugify();
                $degree = $classroom->getDegree();
                $startDate = $classroom->getStartDate();
                $endDate = $classroom->getEndDate();
                $slug = $slugify->slugify($degree."-".$startDate."-".$endDate);
                $classroom->setSlug($slug);

                // createdAt
                $classroom->setCreatedAt(new \DateTime());

                $manager->persist($classroom);
                $manager->flush();

                $this->addFlash('success','La classe a bien été créé');

                return $this->redirectToRoute('classroom_show_list');
            }

            return $this->render('classroom/new.html.twig', [
                'form' => $form->createView(),
                'establishments' => $establishmentRepo->findAll(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

            $admin = $this->getUser()->getAdmin();

            $classroom = new Classroom();

            $form = $this->createForm(ClassroomType::class, $classroom);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){

                // établissement
                $classroom->setEstablishment($admin->getEstablishment());

                // slug
                $slugify = new Slugify();
                $degree = $classroom->getDegree();
                $startDate = $classroom->getStartDate();
                $endDate = $classroom->getEndDate();
                $slug = $slugify->slugify($degree."-".$startDate."-".$endDate);
                $classroom->setSlug($slug);

                // createdAt
                $classroom->setCreatedAt(new \DateTime());

                $manager->persist($classroom);
                $manager->flush();

                $this->addFlash('success','La classe a bien été créé');

                return $this->redirectToRoute('classroom_show_list');
            }

            return $this->render('classroom/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/classroom/modify/{id}", name="classroom_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Classroom $classroom, EstablishmentRepository $establishmentRepo, ClassroomRepository $classroomRepo, AdminRepository $adminRepo)
    {
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            $form = $this->createForm(ClassroomType::class, $classroom);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){

                // établissement
                $establishment = $request->request->get('establishment');
                $establishment = $establishmentRepo->findOneBy(['id' => $establishment]);
                $classroom->setEstablishment($establishment);

                // slug
                $slugify = new Slugify();
                $degree = $classroom->getDegree();
                $startDate = $classroom->getStartDate();
                $endDate = $classroom->getEndDate();
                $slug = $slugify->slugify($degree."-".$startDate."-".$endDate);
                $classroom->setSlug($slug);

                $manager->persist($classroom);
                $manager->flush();

                $this->addFlash('success','La classe a bien été mis à jour !');

                return $this->redirectToRoute('classroom_show', [
                    'id' => $classroom->getId(),
                ]);
            }

            return $this->render('classroom/modify.html.twig', [
                'form' => $form->createView(),
                'establishments' => $establishmentRepo->findAll(),
                'classroom' => $classroom,
            ]);
        }

        // liste des classes pour le super-admin
        if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

            $admin = $this->getUser()->getAdmin();

            $form = $this->createForm(ClassroomType::class, $classroom);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){

                // établissement
                $classroom->setEstablishment($admin->getEstablishment());

                // slug
                $slugify = new Slugify();
                $degree = $classroom->getDegree();
                $startDate = $classroom->getStartDate();
                $endDate = $classroom->getEndDate();
                $slug = $slugify->slugify($degree."-".$startDate."-".$endDate);
                $classroom->setSlug($slug);

                $manager->persist($classroom);
                $manager->flush();

                $this->addFlash('success','La classe a bien été mis à jour !');

                return $this->redirectToRoute('classroom_show', [
                    'id' => $classroom->getId(),
                ]);
            }

            return $this->render('classroom/modify.html.twig', [
                'form' => $form->createView(),
                'classroom' => $classroom,
            ]);
        }
    }

    /**
     * @Route("/classroom/enable/{id}", name="classroom_enable")
     */
    public function enable(ObjectManager $manager, Request $request, Classroom $classroom)
    {
        $classroom->setExist(1);
        $manager->persist($classroom);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/classroom/disable/{id}", name="classroom_disable")
     */
    public function disable(ObjectManager $manager, Request $request, Classroom $classroom)
    {
        $classroom->setExist(0);
        $manager->persist($classroom);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/classroom/delete/{id}", name="classroom_delete")
     */
    public function delete(ObjectManager $manager, Classroom $classroom, Request $request)
    {    
        if ($classroom->getExist() == false){

            if (Count($classroom->getTeachers()) == 0 && Count($classroom->getStudents()) == 0) {

                $manager->remove($classroom);
                $manager->flush();

                $this->addFlash('success', "La classe a bien été supprimée !");

                $referer = $request->headers->get('referer');   
                return new RedirectResponse($referer);

            } else {

                $this->addFlash('danger', "la classe a encore des étudiants ou des enseignants !");

                $referer = $request->headers->get('referer');   
                return new RedirectResponse($referer);  
            }
            
        } else {

            $this->addFlash('danger', "La classe ne peut être supprimée car elle existe !");
            
            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);  
        } 
    }
}