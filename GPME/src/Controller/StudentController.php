<?php

namespace App\Controller;

use App\Entity\Passport;
use App\Entity\Student;
use App\Entity\User;
use App\Form\ModifyUserType;
use App\Form\NewUserType;
use App\Repository\AdminRepository;
use App\Repository\CardRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
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
        if ($this->getUser()) {

            // liste des étudiants pour le super-admin
            if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

                // on récupère la liste des établissements pour le menu filtre
                $establishments = $estabRepo->findAll();

                // on récupère la liste des classes pour le menu filtre
                $classrooms = $classroomRepo->findAll();

                // on récupère la liste des étudiants
                $students = $studentRepo->findAll();

                // si une recherche est faite
                if ($request->request->get('rechercher') == "oui" && $request->request->get('search') != "") {
                    $search = $request->request->get('search');

                    // on compare les données et on les stockent dans un tableau
                    $result = [];
                    foreach ($students as $student) {
                        if ($student->getUser()->getFirstName() == $search || $student->getUser()->getLastName() == $search || $student->getUser()->getEmail() == $search) {
                            $result[] = $student;
                        }
                    }

                    // on compte le nombre de ligne dans le tableau
                    if (Count($result) == 0) {

                        // on enregistre un message flash
                        $this->addFlash('warning','Aucun résultat pour votre recherche');

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $students,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    } else {

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $result,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }
                }

                // si le bouton voir a été cliqué
                if ($request->request->get('filtrer') == "oui") {

                    // si les deux filtres sont selecionnés
                    if ($request->request->get('establishment_choice') != 0 && $request->request->get('classroom_choice') != 0) {

                        // on enregistre un message flash
                        $this->addFlash('warning','Vous ne pouvez pas filtrer avec deux filtes en même temps !');

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $students,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }

                    // si un filtre d'établissement a été choisi
                    if ($request->request->get('establishment_choice') != 0) {
                        $establishment_choice = $request->request->get('establishment_choice');

                        // on compare les données et on les stocke dans un tableau
                        $result = [];
                        foreach ($students as $student) {
                            if ($student->getEstablishment()->getId() == $establishment_choice) {
                                $result[] = $student;
                            }
                        }

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $result,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }

                    // si un filtre de classe a été choisi
                    if ($request->request->get('classroom_choice') != 0) {
                        $classroom_choice = $request->request->get('classroom_choice');

                        // on compare les données et on les stocke dans un tableau
                        $result = [];
                        foreach ($students as $student) {
                            if ($student->getClassroom()->getId() == $classroom_choice) {
                                $result[] = $student;
                            }
                        }

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $result,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }
                }

                // on retourne la vue et les données
                return $this->render('student/list.html.twig', [
                    'students' => $students,
                    'establishments' => $establishments,
                    'classrooms' => $classrooms,
                ]);
            } 

            // liste des étudiants pour l'admin
            if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

                // on récupère les infos de l'admin connecté
                $adminCo = $adminRepo->findOneBy(['user' => $this->getUser()]);

                // on récupère la liste des établissements pour le menu filtre
                $establishments = $estabRepo->findBy(['id' => $adminCo->getEstablishment()->getId()]);

                // on récupère la liste des classes pour le menu filtre
                $classrooms = $classroomRepo->findBy(['establishment' => $adminCo->getEstablishment()->getId()]);

                // on récupère la liste des étudiants
                $students = $studentRepo->findBy(['establishment' => $adminCo->getEstablishment()->getId()]);

                // si une recherche est faite
                if ($request->request->get('rechercher') == "oui" && $request->request->get('search') != "") {
                    $search = $request->request->get('search');

                    // on compare les données et on les stocke dans un tableau
                    $result = [];
                    foreach ($students as $student) {
                        if ($student->getUser()->getFirstName() == $search || $student->getUser()->getLastName() == $search || $student->getUser()->getEmail() == $search) {
                            $result[] = $student;
                        }
                    }

                    // on compte le nombre de ligne dans le tableau
                    if (Count($result) == 0) {

                        // on enregistre un message flash
                        $this->addFlash('warning','Aucun résultat pour votre recherche');

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $students,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    } else {

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $result,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }
                }

                // si le bouton voir a été cliqué
                if ($request->request->get('filtrer') == "oui") {

                    // si les deux filtres sont selecionnés
                    if ($request->request->get('establishment_choice') != 0 && $request->request->get('classroom_choice') != 0) {

                        // on enregistre un message flash
                        $this->addFlash('warning','Vous ne pouvez pas filtrer avec deux filtes en même temps !');

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $students,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }

                    // si un filtre d'établissement a été choisi
                    if ($request->request->get('establishment_choice') != 0) {
                        $establishment_choice = $request->request->get('establishment_choice');

                        // on compare les données et on les stocke dans un tableau
                        $result = [];
                        foreach ($students as $student) {
                            if ($student->getEstablishment()->getId() == $establishment_choice) {
                                $result[] = $student;
                            }
                        }

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $result,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }

                    // si un filtre de classe a été choisi
                    if ($request->request->get('classroom_choice') != 0) {
                        $classroom_choice = $request->request->get('classroom_choice');

                        // on compare les données et on les stocke dans un tableau
                        $result = [];
                        foreach ($students as $student) {
                            if ($student->getClassroom()->getId() == $classroom_choice) {
                                $result[] = $student;
                            }
                        }

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $result,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }
                }

                // on retourne la vue et les données
                return $this->render('student/list.html.twig', [
                    'students' => $students,
                    'establishments' => $establishments,
                    'classrooms' => $classrooms,
                ]);
            }

            // liste des étudiants pour l'enseigant
            if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

                // on récupère les infos de l'enseignant connecté
                $teacherCo = $teacherRepo->findOneBy(['user' => $this->getUser()]);

                 // on récupère la liste des établissements pour le menu filtre
                $establishments = $estabRepo->findBy(['id' => $teacherCo->getEstablishment()->getId()]);

                // on récupère la liste des classes pour le menu filtre
                $classrooms = $teacherCo->getClassrooms();

                // on récupère la liste des étudiants
                $students = [];
                foreach ($teacherCo->getClassrooms() as $classroom) {
                    $students = array_merge($students,$studentRepo->findBy(['classroom' => $classroom]));
                }

                // si une recherche est faite
                if ($request->request->get('rechercher') == "oui" && $request->request->get('search') != "") {
                    $search = $request->request->get('search');

                    // on compare les données et on les stocke dans un tableau
                    $result = [];
                    foreach ($students as $student) {
                        if ($student->getUser()->getFirstName() == $search || $student->getUser()->getLastName() == $search || $student->getUser()->getEmail() == $search) {
                            $result[] = $student;
                        }
                    }

                    // on compte le nombre de ligne dans le tableau
                    if (Count($result) == 0) {

                        // on enregistre un message flash
                        $this->addFlash('warning','Aucun résultat pour votre recherche');

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $students,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    } else {

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $result,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }
                }

                // si le bouton voir a été cliqué
                if ($request->request->get('filtrer') == "oui") {

                    // si les deux filtres sont selecionnés
                    if ($request->request->get('establishment_choice') != 0 && $request->request->get('classroom_choice') != 0) {

                        // on enregistre un message flash
                        $this->addFlash('warning','Vous ne pouvez pas filtrer avec deux filtes en même temps !');

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $students,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }

                    // si un filtre d'établissement a été choisi
                    if ($request->request->get('establishment_choice') != 0) {
                        $establishment_choice = $request->request->get('establishment_choice');

                        // on compare les données et on les stocke dans un tableau
                        $result = [];
                        foreach ($students as $student) {
                            if ($student->getEstablishment()->getId() == $establishment_choice) {
                                $result[] = $student;
                            }
                        }

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $result,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }

                    // si un filtre de classe a été choisi
                    if ($request->request->get('classroom_choice') != 0) {
                        $classroom_choice = $request->request->get('classroom_choice');

                        // on compare les données et on les stocke dans un tableau
                        $result = [];
                        foreach ($students as $student) {
                            if ($student->getClassroom()->getId() == $classroom_choice) {
                                $result[] = $student;
                            }
                        }

                        // on retourne la vue et les données
                        return $this->render('student/list.html.twig', [
                            'students' => $result,
                            'establishments' => $establishments,
                            'classrooms' => $classrooms,
                        ]);
                    }
                }

                // on retourne la vue et les données
                return $this->render('student/list.html.twig', [
                    'students' => $students,
                    'establishments' => $establishments,
                    'classrooms' => $classrooms,
                ]);
            }

            // liste des étudiants pour l'étudiant
            if ($this->getUser()->getTitle() == "ROLE_USER") {
                // on récupère les infos de l'étudiant connecté
                $studentCo = $studentRepo->findOneBy(['user' => $this->getUser()]);

                // on récupère la liste des établissements pour le menu filtre
                $establishments = $estabRepo->findBy(['id' => $studentCo->getEstablishment()->getId()]);

                // on récupère la liste des classes pour le menu filtre
                $classrooms = $classroomRepo->findBy(['id' => $studentCo->getClassroom()->getId()]);

                // on récupère la liste des étudiants
                $students = $studentRepo->findBy(['classroom' => $studentCo->getClassroom()->getId()]);dump($students);
                foreach ($students as $student) {
                    if ($student->getUser()->getExist() == true) {
                        $result[] = $student;
                    }
                }
                // on retourne la vue et les données
                return $this->render('student/list.html.twig', [
                    'students' => $result,
                    'establishments' => $establishments,
                    'classrooms' => $classrooms,
                ]);
            }

        } else {
            
            // on redirige vers la page de login
            return $this->redirectToRoute('account_login', []);
        }
    }

    /**
     * @Route("/student/show/{id}", name="student_show")
     */
    public function show(Student $student)
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
        // on récupère la liste des etablissements
        $establishments = $estabRepo->findAll();

        // on instancie un nouveau user
        $user = new User();

        // Création du formulaire à partir du fichier NewUserType
        $form = $this->createForm(NewUserType::class, $user);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on regarde si un établissement a été transmis
            $estab_id = $request->request->get('establishment_choice');
            $classrooms = $classroomRepo->findBy(['establishment' => $estab_id]);

            // on créé, on le crypte et on transmet le mot de passe
            $passRandom = bin2hex(random_bytes(12));
            $encoded = $encoder->encodePassword($user, $passRandom);
            $user->setHash($encoded);

            // on instancie, on crée le slug et on transmet le slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($user->getFirstName()."-".$user->getLastName());
            $user->setSlug($slug);

            // on le transmet le role au user
            $user->setTitle('ROLE_USER');

            // on vérifie si une seule classe a été coché
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
                    'establishments' => $establishments,
                    'classrooms' => $classrooms,
                ]);
            }

            // on récupére l'établissement
            $establishment = $request->request->get('establishment_choice');
            $establishment = $estabRepo->findOneBy(['id' => $establishment]);

            // on récupère le numero de candidat et la date de naissance
            $candidateNb = $request->request->get('candidateNb');
            $birthDate = $request->request->get('birthDate');
            $birthDate = new \Datetime($birthDate);

            // on assigne son role et son établissement et sa classe
            $student = new Student();
            $student->setUser($user);
            $student->setEstablishment($establishment);
            $student->setClassroom($classroom);
            $student->setCandidateNb($candidateNb);
            $student->setBirthDate($birthDate);
            $student->setCreatedAt(new \DateTime());
            
            $passport = new Passport();
            $passport->setStudent($student);
            $passport->setCreatedAt(new \DateTime());

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($passport);
            $manager->persist($student);
            $manager->persist($user);
            $manager->flush();

            // on envoie un email au user pour lui indiquer son mot de passe
            mail(
                $user->getEmail(),
                'Bonjour',
                'un compte a été créé pour vous sur le site GPME,
                identifiant : '.$user->getEmail().'
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
                'establishments' => $establishments,
                'classrooms' => $classrooms,
            ]);
        }

        return $this->render('student/new.html.twig', [
            'form' => $form->createView(),
            'establishments' => $establishments,
        ]);
    }

    /**
     * @Route("/student/modify/{id}", name="student_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Student $student, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {
        // on récupére l'utilisateur
        $user = $student->getUser();

        // on récupère la liste des etablissements
        $establishments = $estabRepo->findAll();

        // Création du formulaire à partir du fichier ModifyUserType
        $form = $this->createForm(ModifyUserType::class, $user);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // on récupére l'établissement
        $establishment = $request->request->get('establishment_choice');
        $establishment = $estabRepo->findOneBy(['id' => $establishment]);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on vérifie si une seule classe a été coché
            if (Count($request->request->get('classroom')) == 1) {

                // on récupére la classe
                $classroom_id = $request->request->get('classroom');
                $classroom = $classroomRepo->findOneBy(['id' => $classroom_id]);
            } else {
                // on stocke un message flash
                $this->addFlash('warning',"L'étudiant ne peut avoir qu'une seule classe !");

                // on retourne la vue et les données
                return $this->render('student/new.html.twig', [
                    'form' => $form->createView(),
                    'establishments' => $establishments,
                    'classrooms' => $classrooms,
                    
                ]);
            }

            // on récupère le numero de candidat et la date de naissance
            $candidateNb = $request->request->get('candidateNb');
            $date = $request->request->get('birthDate');
            $birthDate = new \Datetime($date);

            // on assigne son établissement
            $student->setCandidateNb($candidateNb);
            $student->setBirthDate($birthDate);
            $student->setClassroom($classroom);
            $student->setEstablishment($establishment);
            $manager->persist($student);

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($user);
            $manager->flush();

            // on stocke un message flash
            $this->addFlash('success',"L'étudiant a bien été mis à jour !");

            // on redirige vers la liste des étudiants
            return $this->redirectToRoute('student_show_list');
        }

        // on récupére la classe d'étudiant dans la BDD
        $establishment = $student->getEstablishment();
        $classrooms = $classroomRepo->findBy(['establishment' => $establishment]);

        // on retourne la vue et les données
        return $this->render('student/modify.html.twig', [
            'form' => $form->createView(),
            'establishments' => $establishments,
            'classrooms' => $classrooms,
            'student' => $student,
        ]);
    }

    /**
     * @Route("/student/delete/{id}", name="student_delete")
     */
    public function delete(ObjectManager $manager, Student $student)
    {
        // on récupére l'utilisateur
        $user = $student->getUser();

        // on vérifie que son compte est inactif
        if ($user->getExist() == false){

            // on supprime la ligne de la table Student et de la table User
            $manager->remove($student);
            $manager->remove($user);
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
