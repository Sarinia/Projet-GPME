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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StudentController extends AbstractController
{
    /**
     * @Route("/student/show_list", name="student_show_list")
     */
    public function index(ClassroomRepository $classroomRepo)
    {
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            return $this->render('student/list.html.twig', [
                'classrooms' => $classroomRepo->findAll(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

            $admin = $this->getUser()->getAdmin();

            return $this->render('student/list.html.twig', [
                'classrooms' => $admin->getEstablishment()->getClassrooms(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

            $teacher = $this->getUser()->getTeacher();

            return $this->render('student/list.html.twig', [
                'classrooms' => $teacher->getClassrooms(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_USER") {

            $student = $this->getUser()->getStudent();

            return $this->render('student/list.html.twig', [
                'classrooms' => $student->getClassrooms(),
            ]);
        }
    }

    /**
     * @Route("/student/show/{id}", name="student_show")
     */
    public function show(Student $student)
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    /**
     * @Route("/student/new", name="student_new")
     */
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {
        $student = new Student();

        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

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

            // CreatedAt
            $student->getUser()->setCreatedAt(new \DateTime());

            // STUDENT
            if (Count($request->request->get('classroom')) == 0) {

                $this->addFlash('warning',"Aucune classe n'a été sélectionnée !");

                return $this->render('student/new.html.twig', [
                    'form' => $form->createView(),
                    'establishments' => $estabRepo->findAll(),
                ]);
            }

            if (Count($request->request->get('classroom')) == 1) {

                $classroom_id = $request->request->get('classroom');
                $classroom = $classroomRepo->findOneBy(['id' => $classroom_id]);
            } else {
                $this->addFlash('warning',"L'étudiant ne peut avoir qu'une seule classe !");

                return $this->render('student/new.html.twig', [
                    'form' => $form->createView(),
                    'establishments' => $estabRepo->findAll(),
                ]);
            }

            // classroom
            $student->addClassroom($classroom);
            
            $passport = new Passport();
            $passport->setStudent($student);
            $passport->setCreatedAt(new \DateTime());
            $passport->setExist(1);

            $manager->persist($passport);
            $manager->persist($student);
            $manager->flush();

            $from = "gpme.contact@gmail.com";
            $link = "<a href='http://passeportgpme.estiennedorves.net'>Passeport Numérique</a>";
            $to = $admin->getUser()->getEmail();
            $subject = "Création d'un compte Passeport Numérique";
            $message = 
            "
            Bonjour,<br>
            Nous avons le plaisir de vous informer de la création d'un compte sur le site ".$link." .<br>
            Votre identifiant est : ".$admin->getUser()->getEmail()." .<br>
            Votre mot de passe est : ".$passRandom." .<br>
            A réception de cet email, pensez à modifier votre mot de passe.
            ";
            $headers = "MIME-Version: 1.0"."\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
            $headers .= "From:" . $from;
            mail($to,$subject,$message,$headers);

            $this->addFlash('success',"L'étudiant a bien été créé !");

            return $this->redirectToRoute('student_show_list');
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
            $student->addClassroom($classroom);

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
     * @Route("/student/enable/{id}", name="student_enable")
     */
    public function enable(ObjectManager $manager, Request $request, Student $student)
    {
        $student->getUser()->setExist(1);
        $manager->persist($student);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/student/disable/{id}", name="student_disable")
     */
    public function disable(ObjectManager $manager, Request $request, Student $student)
    {
        $student->getUser()->setExist(0);
        $manager->persist($student);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/student/delete/{id}", name="student_delete")
     */
    public function delete(ObjectManager $manager, Student $student, Request $request)
    {
        if ($student->getUser()->getExist() == false){

            $passport = $student->getPassport();
            $cards = $student->getPassport()->getCards();
            foreach ($cards as $card) {
                $manager->remove($card);
            }

            $manager->remove($passport);
            $manager->remove($student);
            $manager->flush();

            $this->addFlash('success', "L'étudiant a bien été supprimé !");

            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);
            
        } else {

            $this->addFlash('danger', "L'étudiant ne peut pas être supprimé car son compte est toujours actif !");

            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);
        }
    }
}
