<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Entity\User;
use App\Form\TeacherType;
use App\Repository\AdminRepository;
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

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher/show_list", name="teacher_show_list")
     */
    public function index(StudentRepository $studentRepo, TeacherRepository $teacherRepo, AdminRepository $adminRepo, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo, Request $request)
    {
        // liste des enseignants pour le super-admin
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            // on récupère la liste des établissements pour le menu filtre
            $establishments = $estabRepo->findAll();

            // on récupère la liste des classes pour le menu filtre
            $classrooms = $classroomRepo->findAll();

            // on récupère la liste des enseignants
            $teachers = $teacherRepo->findAll();

            // si une recherche est faite
            if ($request->request->get('rechercher') == "oui" && $request->request->get('search') != "") {
                $search = $request->request->get('search');

                // on compare les données et on les stocke dans un tableau
                $result = [];
                foreach ($teachers as $teacher) {
                    if ($teacher->getUser()->getFirstName() == $search || $teacher->getUser()->getLastName() == $search || $teacher->getUser()->getEmail() == $search) {
                        $result[] = $teacher;
                    }
                }

                // on compte le nombre de ligne dans le tableau
                if (Count($result) == 0) {

                    // on enregistre un message flash
                    $this->addFlash('warning','Aucun résultat pour votre recherche');

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $teachers,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                } else {

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $result,
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
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $teachers,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }

                // si un filtre d'établissement a été choisi
                if ($request->request->get('establishment_choice') != 0) {
                    $establishment_choice = $request->request->get('establishment_choice');

                    // on compare les données et on les stocke dans un tableau
                    $result = [];
                    foreach ($teachers as $teacher) {
                        if ($teacher->getEstablishment()->getId() == $establishment_choice) {
                            $result[] = $teacher;
                        }
                    }

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $result,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }

                // si un filtre de classe a été choisi
                if ($request->request->get('classroom_choice') != 0) {
                    $classroom_choice = $request->request->get('classroom_choice');

                    // on compare les données et on les stocke dans un tableau
                    $result = [];
                    foreach ($teachers as $teacher) {
                        foreach ($teacher->getClassrooms() as $classroom) {
                            if ($classroom->getId() == $classroom_choice) {
                                $result[] = $teacher;
                            }
                        }
                    }
                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $result,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }
            }

            // on retourne la vue et les données
            return $this->render('teacher/list.html.twig', [
                'teachers' => $teachers,
                'establishments' => $establishments,
                'classrooms' => $classrooms,
            ]);
        }

        // liste des enseignants pour l'admin
        if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

            // on récupère les infos de l'admin connecté
            $adminCo = $adminRepo->findOneBy(['user' => $this->getUser()]);

            // on récupère la liste des établissements pour le menu filtre
            $establishments = $estabRepo->findBy(['id' => $adminCo->getEstablishment()->getId()]);

            // on récupère la liste des classes pour le menu filtre
            $classrooms = $classroomRepo->findBy(['establishment' => $adminCo->getEstablishment()->getId()]);

            // on récupère la liste des étudiants
            $teachers = $teacherRepo->findBy(['establishment' => $adminCo->getEstablishment()->getId()]);

            // si une recherche est faite
            if ($request->request->get('rechercher') == "oui" && $request->request->get('search') != "") {
                $search = $request->request->get('search');

                // on compare les données et on les stocke dans un tableau
                $result = [];
                foreach ($teachers as $teacher) {
                    if ($teacher->getUser()->getFirstName() == $search || $teacher->getUser()->getLastName() == $search || $teacher->getUser()->getEmail() == $search) {
                        $result[] = $teacher;
                    }
                }

                // on compte le nombre de ligne dans le tableau
                if (Count($result) == 0) {

                    // on enregistre un message flash
                    $this->addFlash('warning','Aucun résultat pour votre recherche');

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $teachers,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                } else {

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $result,
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
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $teachers,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }

                // si un filtre d'établissement a été choisi
                if ($request->request->get('establishment_choice') != 0) {
                    $establishment_choice = $request->request->get('establishment_choice');

                    // on compare les données et on les stocke dans un tableau
                    $result = [];
                    foreach ($teachers as $teacher) {
                        if ($teacher->getEstablishment()->getId() == $establishment_choice) {
                            $result[] = $teacher;
                        }
                    }

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $result,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }

                // si un filtre de classe a été choisi
                if ($request->request->get('classroom_choice') != 0) {
                    $classroom_choice = $request->request->get('classroom_choice');

                    // on compare les données et on les stocke dans un tableau
                    $result = [];
                    foreach ($teachers as $teacher) {
                        foreach ($teacher->getClassrooms() as $classroom) {
                            if ($classroom->getId() == $classroom_choice) {
                                $result[] = $teacher;
                            }
                        }
                    }

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $result,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }
            }

            // on retourne la vue et les données
            return $this->render('teacher/list.html.twig', [
                'teachers' => $teachers,
                'establishments' => $establishments,
                'classrooms' => $classrooms,
            ]);
        }

        // liste des enseignants pour l'enseignant
        if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

            // on récupère les infos de l'enseignant connecté
            $teacherCo = $teacherRepo->findOneBy(['user' => $this->getUser()]);

             // on récupère la liste des établissements pour le menu filtre
            $establishments = $estabRepo->findBy(['id' => $teacherCo->getEstablishment()->getId()]);

            // on récupère la liste des classes pour le menu filtre
            $classrooms = $classroomRepo->findBy(['establishment' => $teacherCo->getEstablishment()->getId()]);

            // on récupère la liste des enseigants
            $teachers = $teacherRepo->findBy(['establishment' => $establishments]);

            // si une recherche est faite
            if ($request->request->get('rechercher') == "oui" && $request->request->get('search') != "") {
                $search = $request->request->get('search');

                // on compare les données et on les stocke dans un tableau
                $result = [];
                foreach ($teachers as $teacher) {
                    if ($teacher->getUser()->getFirstName() == $search || $teacher->getUser()->getLastName() == $search || $teacher->getUser()->getEmail() == $search) {
                        $result[] = $teacher;
                    }
                }

                // on compte le nombre de ligne dans le tableau
                if (Count($result) == 0) {

                    // on enregistre un message flash
                    $this->addFlash('warning','Aucun résultat pour votre recherche');

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $teachers,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                } else {

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $result,
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
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $teachers,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }

                // si un filtre d'établissement a été choisi
                if ($request->request->get('establishment_choice') != 0) {
                    $establishment_choice = $request->request->get('establishment_choice');

                    // on compare les données et on les stocke dans un tableau
                    $result = [];
                    foreach ($teachers as $teacher) {
                        if ($teacher->getEstablishment()->getId() == $establishment_choice) {
                            $result[] = $teacher;
                        }
                    }

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $result,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }

                // si un filtre de classe a été choisi
                if ($request->request->get('classroom_choice') != 0) {
                    $classroom_choice = $request->request->get('classroom_choice');

                    // on compare les données et on les stockent dans un tableau
                    $result = [];
                    foreach ($teachers as $teacher) {
                        foreach ($teacher->getClassrooms() as $classroom) {
                            if ($classroom->getId() == $classroom_choice) {
                                $result[] = $teacher;
                            }
                        }
                    }

                    // on retourne la vue et les données
                    return $this->render('teacher/list.html.twig', [
                        'teachers' => $result,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }
            }

            // on retourne la vue et les données
            return $this->render('teacher/list.html.twig', [
                'teachers' => $teachers,
                'establishments' => $establishments,
                'classrooms' => $classrooms,
            ]);
        }

        // liste des enseignants pour l'étudiant
        if ($this->getUser()->getTitle() == "ROLE_USER") {
            // on récupère les infos de l'étudiant connecté
            $studentCo = $studentRepo->findOneBy(['user' => $this->getUser()]);

            // on récupère la liste des établissements pour le menu filtre
            $establishments = $estabRepo->findBy(['id' => $studentCo->getEstablishment()->getId()]);

            // on récupère la liste des classes pour le menu filtre
            $classrooms = $classroomRepo->findBy(['id' => $studentCo->getClassroom()->getId()]);

            // on récupère la liste des étudiants
            $teachers = $studentCo->getClassroom()->getTeachers();

            // on retourne la vue et les données
            return $this->render('teacher/list.html.twig', [
                'teachers' => $teachers,
                'establishments' => $establishments,
                'classrooms' => $classrooms,
            ]);
        }
    }

    /**
     * @Route("/teacher/show/{id}", name="teacher_show")
     */
    public function show(Teacher $teacher)
    {
        // on retourne la vue et les données
        return $this->render('teacher/show.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * @Route("/teacher/new", name="teacher_new")
     */
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {
        // on récupère la liste des etablissements
        $establishments = $estabRepo->findAll();
        $classrooms = 0;

        // on regarde si un établissement a été transmis
        if ($request->request->get('establishment_choice')) {
            $establishment = $request->request->get('establishment_choice');
            $classrooms = $classroomRepo->findBy(['establishment' => $establishment]);
        }

        // on instancie un nouveau user
        $teacher = new Teacher();
        $user = new User();

        // Création du formulaire à partir du fichier NewUserType
        $form = $this->createForm(TeacherType::class, $teacher);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on créé, on le crypte et on transmet le mot de passe
            $passRandom = bin2hex(random_bytes(12));
            $encoded = $encoder->encodePassword($user, $passRandom);
            $user->setHash($encoded);

            // on instancie, on crée le slug et on transmet le slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($user->getFirstName()."-".$user->getLastName());
            $user->setSlug($slug);

            // on le transmet le role au user
            $user->setTitle('ROLE_TEACHER');

            // on récupére l'établissement
            $establishment = $estabRepo->findOneBy(['id' => $establishment]);

            // on assigne son role et son établissement
            $teacher = new Teacher();
            $teacher->setUser($user);
            $teacher->setEstablishment($establishment);
            $teacher->setCreatedAt(new \DateTime());

            // on récupére la classe et on le transmet au teacher
            $classrooms_selected = $request->request->get('classroom');
            foreach ($classrooms_selected as $classroom) {
                $classroom_id = $classroomRepo->findOneBy(['id' => $classroom]);
                $teacher->addClassroom($classroom_id);
            }

            $manager->persist($teacher);

            // on persiste et on sauvegarde les données du formulaire
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

            // on enregistre un message flash
            $this->addFlash('success','L\'enseignant a bien été créé !');

            // on redirige vers la liste des enseignants
            return $this->redirectToRoute('teacher_show_list');
        }

        // on retourne la vue et les données
        return $this->render('teacher/new.html.twig', [
            'form' => $form->createView(),
            'establishments' => $establishments,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * @Route("/teacher/modify/{id}", name="teacher_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Teacher $teacher, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {
        // on récupére l'utilisateur
        $user = $teacher->getUser();

        // on récupère la liste des etablissements
        $establishments = $estabRepo->findAll();

        // Création du formulaire à partir du fichier ModifyUserType
        $form = $this->createForm(TeacherType::class, $teacher);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // on récupére l'établissement choisi
        $establishment = $request->request->get('establishment_choice');
        $establishment = $estabRepo->findOneBy(['id' => $establishment]);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on supprime toutes les classes stockées de l'enseignant dans la BDD
            foreach ($teacher->getClassrooms() as $classroom) {
                $classroom = $classroomRepo->findOneBy(['id' => $classroom]);
                $teacher->removeClassroom($classroom);
            }

            // on récupére la ou les classes et on transmet au teacher
            $classrooms = $request->request->get('classroom');
            foreach ($classrooms as $classroom) {
                $classroom = $classroomRepo->findOneBy(['id' => $classroom]);
                $teacher->addClassroom($classroom);
            }

            // si l'établissement choisi est différent de l'établissement de l'enseignant
            if (!$establishment) {
                $establishment = $teacher->getEstablishment();
            }

            // on assigne son établissement
            $teacher->setEstablishment($establishment);
            $manager->persist($teacher);

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($user);
            $manager->flush();

            // on enregistre un message flash
            $this->addFlash('success','l\'enseignant a bien été mis à jour !');

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('teacher_show_list');
        }

        // on récupére la ou les classes de l'enseignants dans la BDD
        $establishment = $teacher->getEstablishment();
        $classrooms = $classroomRepo->findBy(['establishment' => $establishment]);

        // on retourne la vue et les données
        return $this->render('teacher/modify.html.twig', [
            'form' => $form->createView(),
            'teacher' => $teacher,
            'establishments' => $establishments,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * @Route("/teacher/delete/{id}", name="teacher_delete")
     */
    public function delete(ObjectManager $manager, Teacher $teacher)
    {
        // on récupére l'utilisateur
        $user = $teacher->getUser();

        // on vérifie que son compte est inactif
        if ($user->getExist() == false){

            // on supprime la ligne de la table Teacher et de la table User
            $manager->remove($teacher);
            $manager->remove($user);
            $manager->flush();

            // on enregistre un message flash
            $this->addFlash('success', "L'enseignant a bien été supprimé !");

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('teacher_show_list');
        } else {
            // on enregistre un message flash
            $this->addFlash('danger', "L'enseignant ne peut être supprimé car il existe !");

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('teacher_show_list');
        }
    }
}
