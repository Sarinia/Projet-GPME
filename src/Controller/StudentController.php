<?php

namespace App\Controller;

use App\Entity\Passport;
use App\Entity\Student;
use App\Entity\User;
use App\Form\StudentType;
use App\Repository\AdminRepository;
use App\Repository\CardRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\PassportRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
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
     * @Route("/student/show_list", name="student_show_list")
     */
    public function index(UserRepository $userRepo, StudentRepository $studentRepo, TeacherRepository $teacherRepo, AdminRepository $adminRepo, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo, Request $request)
    {
        // liste des enseignants pour le super-admin
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            // on récupère la liste des classes
            $classrooms = $classroomRepo->findAll();

            // on retourne la vue et les données
            return $this->render('student/list.html.twig', [
                'classrooms' => $classrooms,
            ]);
        }

        // liste des enseignants pour l'admin
        if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

            // on récupère les infos de l'admin connecté
            $adminCo = $adminRepo->findOneBy(['user' => $this->getUser()]);

            // on récupère la liste des enseignants
            $classrooms = $classroomRepo->findBy(['establishment' => $adminCo->getEstablishment()->getId()]);

            // on retourne la vue et les données
            return $this->render('student/list.html.twig', [
                'classrooms' => $classrooms,
            ]);
        }

        // liste des enseignants pour l'enseignant
        if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

            // on récupère les infos de l'enseignant connecté
            $teacherCo = $teacherRepo->findOneBy(['user' => $this->getUser()]);

            // on récupère la liste des enseignants
            $classrooms = $teacherCo->getClassrooms();

            // on retourne la vue et les données
            return $this->render('student/list.html.twig', [
                'classrooms' => $classrooms,
            ]);
        }

        // liste des enseignants pour l'étudiant
        if ($this->getUser()->getTitle() == "ROLE_USER") {

            // on récupère les infos de l'étudiant connecté
            $studentCo = $studentRepo->findOneBy(['user' => $this->getUser()]);

            // on récupère la liste des enseignants
            $classrooms = $classroomRepo->findBy(['id' => $studentCo->getClassroom()->getId()]);

            // on retourne la vue et les données
            return $this->render('student/list.html.twig', [
                'classrooms' => $classrooms,
            ]);
        }
    }

    /**
     * @Route("/student/show/{id}", name="student_show")
     */
    public function show(Student $student, CardRepository $cardRepo)
    {
        // on retourne la vue et les données
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    /**
     * @Route("/student/new", name="student_new")
     */
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {
        // on instancie un nouveau user
        $student = new Student();

        // Création du formulaire à partir du fichier NewUserType
        $form = $this->createForm(StudentType::class, $student);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // USER
            // hash
            $passRandom = bin2hex(random_bytes(12));
            $encoded = $encoder->encodePassword($student->getUser(), $passRandom);
            $student->getUser()->setHash($encoded);

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($student->getUser()->getFirstName()."-".$student->getUser()->getLastName());
            $student->getUser()->setSlug($slug);

            // title
            $student->getUser()->setTitle('ROLE_USER');

            // STUDENT
            // on vérifie si une seule classe a été coché
            if (Count($request->request->get('classroom')) == "") {

                // on stocke un message flash
                $this->addFlash('warning',"Aucene classe n'a été sélectionnée !");

                // on redirige vers la liste des administrateurs
                return $this->render('student/new.html.twig', [
                    // on envoie le formulaire à la vue
                    'form' => $form->createView(),
                    'establishments' => $estabRepo->findAll(),
                ]);
            }

            if (Count($request->request->get('classroom')) == 1) {

                // on récupére la classe
                $classroom_id = $request->request->get('classroom');
                $classroom = $classroomRepo->findOneBy(['id' => $classroom_id]);
            } else {
                // on stocke un message flash
                $this->addFlash('warning',"L'étudiant ne peut avoir qu'une seule classe !");

                // on redirige vers la liste des administrateurs
                return $this->render('student/new.html.twig', [
                    // on envoie le formulaire à la vue
                    'form' => $form->createView(),
                    'establishments' => $estabRepo->findAll(),
                ]);
            }

            // classroom
            $student->setClassroom($classroom);

            // CreatedAt
            $student->setCreatedAt(new \DateTime());
            
            $passport = new Passport();
            $passport->setStudent($student);
            $passport->setCreatedAt(new \DateTime());

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($passport);
            $manager->persist($student);
            $manager->flush();

            // on envoie un email au user pour lui indiquer son mot de passe
            mail(
                $student->getUser()->getEmail(),
                'Bonjour',
                'un compte a été créé pour vous sur le site GPME,
                identifiant : '.$student->getUser()->getEmail().'
                votre mot de passe : '.$passRandom
            );

            // on stocke un message flash
            $this->addFlash('success',"L'étudiant a bien été créé !");

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('student_show_list');
        }

        // on regarde si un établissement a été transmis
        if ($request->request->get('establishment_choice')) {
            $estab_id = $request->request->get('establishment_choice');
            $classrooms = $classroomRepo->findBy(['establishment' => $estab_id]);

            // on retourne la vue et les données
            return $this->render('student/new.html.twig', [
                'form' => $form->createView(),
                'establishments' => $estabRepo->findAll(),
            ]);
        }

        return $this->render('student/new.html.twig', [
            'form' => $form->createView(),
            'establishments' => $estabRepo->findAll(),
        ]);
    }

    /**
     * @Route("/student/modify/{id}", name="student_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Student $student, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {
        // Création du formulaire à partir du fichier ModifyUserType
        $form = $this->createForm(StudentType::class, $student);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on vérifie si une seule classe a été coché
            if (Count($request->request->get('classroom')) == "") {

                // on stocke un message flash
                $this->addFlash('warning',"Aucene classe n'a été sélectionnée !");

                // on redirige vers la liste des administrateurs
                return $this->render('student/new.html.twig', [
                    // on envoie le formulaire à la vue
                    'form' => $form->createView(),
                    'establishments' => $estabRepo->findAll(),
                    'student' => $student,
                ]);
            }

            if (Count($request->request->get('classroom')) == 1) {

                // on récupére la classe
                $classroom_id = $request->request->get('classroom');
                $classroom = $classroomRepo->findOneBy(['id' => $classroom_id]);
            } else {
                // on stocke un message flash
                $this->addFlash('warning',"L'étudiant ne peut avoir qu'une seule classe !");

                // on redirige vers la liste des administrateurs
                return $this->render('student/new.html.twig', [
                    // on envoie le formulaire à la vue
                    'form' => $form->createView(),
                    'establishments' => $estabRepo->findAll(),
                    'student' => $student,
                ]);
            }

            // on assigne sa classe
            $student->setClassroom($classroom);

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($student);
            $manager->flush();

            // on stocke un message flash
            $this->addFlash('success',"L'étudiant a bien été mis à jour !");

            // on redirige vers la liste des étudiants
            return $this->redirectToRoute('student_show', [
                'id' => $student->getId(),
            ]);
        }

        // on retourne la vue et les données
        return $this->render('student/modify.html.twig', [
            'form' => $form->createView(),
            'establishments' => $estabRepo->findAll(),
            'student' => $student,
        ]);
    }

    /**
     * @Route("/student/delete/{id}", name="student_delete")
     */
    public function delete(ObjectManager $manager, Student $student, PassportRepository $passportRepo, CardRepository $cardRepo)
    {
        // on récupére l'utilisateur
        $user = $student->getUser();

        // on vérifie que son compte est inactif
        if ($user->getExist() == false){

            // On supprime le passeport
            $passport = $passportRepo->findOneBy(['student' => $student]);
            $cards = $cardRepo->findBy(['student' => $student]);
            foreach ($cards as $card) {
                $manager->remove($card);
            }

            // on supprime la ligne de la table Student et de la table User
            $manager->remove($passport);
            $manager->remove($student);
            $manager->flush();

            // on enregistre un message flash
            $this->addFlash('success', "L'étudiant a bien été supprimé !");

            // on redirige vers la liste des étudiants
            return $this->redirectToRoute('student_show_list');
        } else {

            // on enregistre un message flash
            $this->addFlash('danger', "L'étudiant ne peut pas être supprimé car son compte est toujours actif !");

            // on redirige vers la liste des étudiants
            return $this->redirectToRoute('student_show_list');
        }
    }
}
