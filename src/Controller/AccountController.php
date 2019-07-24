<?php

namespace App\Controller;

use App\Entity\Establishment;
use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountAdminType;
use App\Form\AccountSadminType;
use App\Form\AccountStudentType;
use App\Form\AccountTeacherType;
use App\Form\PasswordUpdateType;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Cocur\Slugify\Slugify;
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
            return $this->redirectToRoute('dashboard',[
                'slug' => $this->getUser()->getSlug(),
            ]);

        // si aucun utilisateur n'est connecté
        } else {

            // on récupére les données du formulaire dans des variables
            $establishment = $request->request->get('establishment_choice');

            //on vérifie si un établissement a été choisi
            if (isset($establishment) && !empty($establishment)) {

                $establishment = $establishmentRepo->findOneBy(['id' => $establishment]);dump($establishment);

                // on redirige vers la page de login
                return $this->redirectToRoute('account_login', [
                    'slug' => $establishment->getSlug(),
                ]);

            } else {

                // on récupére la liste des établissements en fonction du département choisi
                $department = $request->request->get('department_choice');
                $establishments = $establishmentRepo->findBy(['department' => $department]);

                // on retourne la vue et les données
                return $this->render('account/index.html.twig', [
                    'departments' => $departmentRepo->findAll(),
                    'establishments' => $establishments,
                ]);
            }

            // on retourne la vue et les données
            return $this->render('account/index.html.twig', [
                'departments' => $departmentRepo->findAll(),
            ]);
        }
    }

    /**
     * @Route("/{slug}/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils, Establishment $establishment, EstablishmentRepository $establishmentRepo)
    {
        return $this->render('account/login.html.twig', [// on retourne la vue et les données
            'hasError' => $utils->getLastAuthenticationError(),
            'establishment' => $establishment,
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(){}

    /**
    * @Route("/dashboard/{slug}", name="dashboard")
    */
    public function dashboard(User $user, StudentRepository $studentRepo, TeacherRepository $teacherRepo, AdminRepository $adminRepo, ClassroomRepository $classroomRepo, EstablishmentRepository $establishmentRepo, DepartmentRepository $departmentRepo)
    {
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {
            return $this->render('account/dashboard.html.twig', [
            'students' => $studentRepo->findBy([],['id' => 'DESC'],5),// liste des étudiants
            'teachers' => $teacherRepo->findBy([],['id' => 'DESC'],5),// liste des enseignants
            'classrooms' => $classroomRepo->findBy([],['createdAt' => 'DESC'],5),// liste des classes
            'admins' => $adminRepo->findBy([],['id' => 'DESC'],5),// liste des administrateurs
            'establishments' => $establishmentRepo->findBy([],['createdAt' => 'DESC'],5),// liste des établissements
            'departments' => $departmentRepo->findBy([],['createdAt' => 'DESC'],5),// liste des départements
        ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_ADMIN") {
            return $this->render('account/dashboard.html.twig', [
            // messages
            'classrooms' => $this->getUser()->getAdmin()->getEstablishment()->getClassrooms(),// passeports
            // fiches SP
        ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_TEACHER") {
            return $this->render('account/dashboard.html.twig', [
            // messages
            'classrooms' => $this->getUser()->getTeacher()->getClassrooms(),// passeports
            // fiches SP
        ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_USER") {
            return $this->render('account/dashboard.html.twig', [
            // messages
            'student' => $this->getUser()->getStudent(),// passeports
        ]);
        }
    }

    /**
     * @Route("/account/profile", name="account_profile")
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            $sadmin = $this->getUser()->getSadmin();

            // on crée le formulaire
            $form = $this->createForm(AccountSadminType::class, $sadmin);

            // on récupére les données du formulaire
            $form->handleRequest($request);

            // si le formulaire est soumis et est valide
            if ($form->isSubmitted() && $form->isValid()) {

                $slugify = new Slugify();
                $slug = $slugify->slugify($this->getUser()->getFirstName()."-".$this->getUser()->getLastName());
                $this->getUser()->setSlug($slug);

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

        if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

            $admin = $this->getUser()->getAdmin();

            // on crée le formulaire
            $form = $this->createForm(AccountAdminType::class, $admin);

            // on récupére les données du formulaire
            $form->handleRequest($request);

            // si le formulaire est soumis et est valide
            if ($form->isSubmitted() && $form->isValid()) {

                $slugify = new Slugify();
                $slug = $slugify->slugify($this->getUser()->getFirstName()."-".$this->getUser()->getLastName());
                $this->getUser()->setSlug($slug);

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

        if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

            $teacher = $this->getUser()->getTeacher();

            // on crée le formulaire
            $form = $this->createForm(AccountTeacherType::class, $teacher);

            // on récupére les données du formulaire
            $form->handleRequest($request);

            // si le formulaire est soumis et est valide
            if ($form->isSubmitted() && $form->isValid()) {

                $slugify = new Slugify();
                $slug = $slugify->slugify($this->getUser()->getFirstName()."-".$this->getUser()->getLastName());
                $this->getUser()->setSlug($slug);

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

        if ($this->getUser()->getTitle() == "ROLE_USER") {

            $student = $this->getUser()->getStudent();

            // on crée le formulaire
            $form = $this->createForm(AccountStudentType::class, $student);

            // on récupére les données du formulaire
            $form->handleRequest($request);

            // si le formulaire est soumis et est valide
            if ($form->isSubmitted() && $form->isValid()) {

                $slugify = new Slugify();
                $slug = $slugify->slugify($this->getUser()->getFirstName()."-".$this->getUser()->getLastName());
                $this->getUser()->setSlug($slug);

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
            if (password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {

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
                return $this->redirectToRoute('dashboard',[
                    'slug' => $this->getUser()->getSlug(),
                ]);
                
            } else {
                // on enregistre un message flash
                $this->addFlash( 'danger', 'L\'ancien mot de passe est incorrect !' );

                // on retourne la vue et les données
                return $this->render('account/password.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        // on retourne la vue et les données
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}