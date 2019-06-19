<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifyStudentType;
use App\Form\NewStudentType;
use App\Repository\ClassroomRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StudentController extends AbstractController
{
    /**
     * Display students
     * 
     * @Route("/student/show_list", name="student_show_list")
     */
    public function index(UserRepository $userRepo, ClassroomRepository $classroomRepo)
    {        
        $users = $userRepo->findAll();

        // requete de toutes les classes de la BDD
        $classrooms = $classroomRepo->findAll();

        return $this->render('student/list.html.twig', [
            'users' => $users,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Update a student
     * 
     * @Route("/student/modify/{slug}/{id}", name="student_modify")
     */
    public function modify(ObjectManager $manager, Request $request, User $user)
    {        
        /**
         * {id} est automatiquement converti et associé à $user->getId
         */
        $form = $this->createForm(ModifyStudentType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // on instancie, on crée le slug et on transmet le slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($user->getFirstName()."-".$user->getLastName());
            $user->setSlug($slug);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success','Votre profil a bien été mis à jour !');

            return $this->redirectToRoute('student_show_list');
        }


        return $this->render('student/modify.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Create a student
     * 
     * @Route("/student/new", name="student_new")
     */
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, RoleRepository $roleRepo)
    {        
        /**
         * {id} est automatiquement converti et associé à $user->getId
         */

        $user = new User();

        $form = $this->createForm(NewStudentType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // on créé, on le crypte et on transmet le mot de passe
            $passRandom = bin2hex(random_bytes(12));
            $encoded = $encoder->encodePassword($user, $passRandom);
            $user->setHash($encoded);

            // on instancie, on crée le slug et on transmet le slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($user->getFirstName()."-".$user->getLastName());
            $user->setSlug($slug);

            // on récupére le role et on le transmet au user
            $role = $roleRepo->findOneBy(['title' => 'ROLE_USER']);
            $user->addUserRole($role);

            // envoyer le mot de passe à l'étudiant
            mail(
                $form->get('email')->getData(), 
                'Mot de passe GPME - Etudiant',
                $passRandom
            );

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success','Un nouvel étudiant a bien été inséré !');

            return $this->redirectToRoute('student_show_list');
        }


        return $this->render('student/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Disable a student
     * 
     * @Route("/student/delete/{id}", name="student_delete")
     */
    public function delete(ObjectManager $manager, User $user)
    {                
        if ($user->getExist() == false){

            $manager->remove($user);
            $manager->flush();

            $this->addFlash('success', "L'étudiant a bien été supprimé !");
            
            return $this->redirectToRoute('student_show_list');            
        } else {
            $this->addFlash('danger', "L'étudiant ne peut être supprimé car il existe !");
            
            return $this->redirectToRoute('student_show_list');  
        }   
    }
}
