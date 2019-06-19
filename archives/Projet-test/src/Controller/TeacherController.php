<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifyTeacherType;
use App\Form\NewTeacherType;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TeacherController extends AbstractController
{
    /**
     * Display teachers
     * 
     * @Route("/teacher/show_list", name="teacher_show_list")
     */
    public function index(UserRepository $userRepo)
    {        
        $users = $userRepo->findAll();

        return $this->render('teacher/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Create a teacher
     * 
     * @Route("/teacher/new", name="teacher_new")
     */
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, RoleRepository $roleRepo, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {        
        /**
         * {id} est automatiquement converti et associé à $user->getId
         */
        // on regarde si un établissement a été transmis
        if ($request->request->get('establishment')) {
            $id = $request->request->get('establishment');
            $estabs = $estabRepo->findOneBy(['id' => $id]);
            $classrooms = $classroomRepo->findBy(['establishment' => $estabs]);

        } else {
            $estabs = $estabRepo->findAll();
            $classrooms = 0;
        }
        
        $user = new User();

        $form = $this->createForm(NewTeacherType::class, $user);

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
            $role = $roleRepo->findOneBy(['title' => 'ROLE_TEACHER']);
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

            return $this->redirectToRoute('teacher_show_list');
        }


        return $this->render('teacher/new.html.twig', [
            'form' => $form->createView(),
            'estabs' => $estabs,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Update a teacher
     * 
     * @Route("/teacher/modify/{slug}/{id}", name="teacher_modify")
     */
    public function modify(ObjectManager $manager, Request $request, User $user, EstablishmentRepository $estabRepo)
    {        
        /**
         * {id} est automatiquement converti et associé à $user->getId
         */
        $estabs = $estabRepo->findAll();

        $form = $this->createForm(ModifyTeacherType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // on instancie, on crée le slug et on transmet le slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($user->getFirstName()."-".$user->getLastName());
            $user->setSlug($slug);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success','Votre profil a bien été mis à jour !');

            return $this->redirectToRoute('teacher_show_list');
        }


        return $this->render('teacher/modify.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'estabs' => $estabs,
        ]);
    }

    /**
     * Delete a teacher
     * 
     * @Route("/teacher/delete/{id}", name="teacher_delete")
     */
    public function delete(ObjectManager $manager, User $user)
    {        
        if ($user->getExist() == false){

            $manager->remove($user);
            $manager->flush();

            $this->addFlash('success', "L'enseignant a bien été supprimé !");
            
            return $this->redirectToRoute('teacher_show_list');            
        } else {
            $this->addFlash('danger', "L'enseignant ne peut être supprimé car il existe !");
            
            return $this->redirectToRoute('teacher_show_list');  
        }
    }
}
