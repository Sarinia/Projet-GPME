<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Form\AccountAdminType;
use App\Form\AccountSadminType;
use App\Form\AccountStudentType;
use App\Form\AccountTeacherType;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\StudentType;
use App\Repository\AdminRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\SadminRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, DepartmentRepository $departmentRepo, EstablishmentRepository $establishmentRepo)
    {
        // si un utilisateur est connecté
        if ($this->getUser() && !empty($this->getUser())) {

            // on le redirige vers le dashboard
            return $this->redirectToRoute('dashboard');

        // si aucun utilisateur n'est connecté
        } else {

            // on récupére tous les départements dans la base de données
            $departments = $departmentRepo->findAll();

            // on récupére les données du formulaire dans des variables
            $establishment = $request->request->get('establishment_choice');

            //on vérifie si un établissement a été choisi
            if (isset($establishment) && !empty($establishment)) {

                // on redirige vers la page de login
                return $this->redirectToRoute('account_login', [
                    'establishment' => $establishment,
                ]);

            } else {

                // on récupére la liste des établissements en fonction du département choisi
                $department = $request->request->get('department_choice');
                $establishments = $establishmentRepo->findBy(['department' => $department]);

                // on retourne la vue et les données
                return $this->render('account/index.html.twig', [
                    'departments' => $departments,
                    'establishments' => $establishments,
                ]);
            }

            // on retourne la vue et les données
            return $this->render('account/index.html.twig', [
                'departments' => $departments,
            ]);
        }
    }

    /**
     * @Route("/{establishment}/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils, $establishment, EstablishmentRepository $establishmentRepo)
    {
        $establishment = $establishmentRepo->findOneBy(['slug' => $establishment]);

        // on enregistre l'erreur s'il y en a
        $error = $utils->getLastAuthenticationError();

        // on retourne la vue et les données
        return $this->render('account/login.html.twig', [
            'hasError' => $error,
            'establishment' => $establishment,
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(){}

    /**
     * @Route("/account/profile", name="account_profile")
     */
    public function profile(Request $request, ObjectManager $manager, StudentRepository $studentRepo, TeacherRepository $teacherRepo, AdminRepository $adminRepo, SadminRepository $sadminRepo)
    {
        if ($this->getUser()->getTitle() == "ROLE_SADMIN" ) {

            // on récupére l'utilisateur connecté
            $user = $this->getUser();
            $sadmin = $sadminRepo->findOneBy(['user' => $user]);

            // on crée le formulaire
            $form = $this->createForm(AccountSadminType::class, $sadmin);

            // on récupére les données du formulaire
            $form->handleRequest($request);

            // si le formulaire est soumis et est valide
            if ($form->isSubmitted() && $form->isValid()) {

                // on persiste les données
                $manager->persist($sadmin);

                // on enregistre les données
                $manager->flush();

                // on enregistre un message flash
                $this->addFlash('success','Votre profil a bien été mis à jour !');
            }
            // on retourne la vue et les données
            return $this->render('account/profile.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_ADMIN" ) {

            // on récupére l'utilisateur connecté
            $user = $this->getUser();
            $admin = $adminRepo->findOneBy(['user' => $user]);

            // on crée le formulaire
            $form = $this->createForm(AccountAdminType::class, $admin);

            // on récupére les données du formulaire
            $form->handleRequest($request);

            // si le formulaire est soumis et est valide
            if ($form->isSubmitted() && $form->isValid()) {

                // on persiste les données
                $manager->persist($admin);

                // on enregistre les données
                $manager->flush();

                // on enregistre un message flash
                $this->addFlash('success','Votre profil a bien été mis à jour !');
            }
            // on retourne la vue et les données
            return $this->render('account/profile.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_TEACHER" ) {

            // on récupére l'utilisateur connecté
            $user = $this->getUser();
            $teacher = $teacherRepo->findOneBy(['user' => $user]);

            // on crée le formulaire
            $form = $this->createForm(AccountTeacherType::class, $teacher);

            // on récupére les données du formulaire
            $form->handleRequest($request);

            // si le formulaire est soumis et est valide
            if ($form->isSubmitted() && $form->isValid()) {

                // on persiste les données
                $manager->persist($teacher);

                // on enregistre les données
                $manager->flush();

                // on enregistre un message flash
                $this->addFlash('success','Votre profil a bien été mis à jour !');
            }
            // on retourne la vue et les données
            return $this->render('account/profile.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_USER" ) {

            // on récupére l'utilisateur connecté
            $user = $this->getUser();
            $student = $studentRepo->findOneBy(['user' => $user]);

            // on crée le formulaire
            $form = $this->createForm(AccountStudentType::class, $student);

            // on récupére les données du formulaire
            $form->handleRequest($request);

            // si le formulaire est soumis et est valide
            if ($form->isSubmitted() && $form->isValid()) {

                // on persiste les données
                $manager->persist($student);

                // on enregistre les données
                $manager->flush();

                // on enregistre un message flash
                $this->addFlash('success','Votre profil a bien été mis à jour !');
            }
            // on retourne la vue et les données
            return $this->render('account/profile.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/account/password-update", name="account_password")
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        // on récupére l'utilisateur connecté
        $user = $this->getUser();

        // on instancie un nouvel objet PasswordUpdate
        $passwordUpdate = new PasswordUpdate();

        // on crée le formulaire
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        // on récupére les données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et est valide 
        if ($form->isSubmitted() && $form->isValid()) {

            // on vérifie que le mot de passe saisie est le même que le mot de passe de la base de données
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {

                // on enregistre un message flash
                $this->addFlash( 'danger', 'L\'ancien mot de passe est incorrect !' );
            } else {

                // on récupére le nouveau mot de passe
                $newPassword = $passwordUpdate->getNewPassword();

                // on crypte le nouveau mot de passe
                $encoded = $encoder->encodePassword($user, $newPassword);

                // on on attribue le nouveau mot de passe
                $user->setHash($encoded);

                // on persiste les données
                $manager->persist($user);

                // on enregistre les données
                $manager->flush();

                // on enregistre un message flash
                $this->addFlash('success','Le mot de passe a bien été modifié');

                // on redirige vers la page de dashboard
                return $this->redirectToRoute('dashboard');
            }
        }

        // on retourne la vue et les données
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}