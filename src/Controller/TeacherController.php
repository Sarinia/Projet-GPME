<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher/show_list", name="teacher_show_list")
     */
    public function index(ClassroomRepository $classroomRepo)
    {
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            return $this->render('teacher/list.html.twig', [
                'classrooms' => $classroomRepo->findAll(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_ADMIN") {

            $admin = $this->getUser()->getAdmin();

            return $this->render('teacher/list.html.twig', [
                'classrooms' => $admin->getEstablishment()->getClassrooms(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_TEACHER") {

            $teacher = $this->getUser()->getTeacher();

            return $this->render('teacher/list.html.twig', [
                'classrooms' => $teacher->getClassrooms(),
            ]);
        }

        if ($this->getUser()->getTitle() == "ROLE_USER") {

            $student = $this->getUser()->getStudent();

            return $this->render('teacher/list.html.twig', [
                'classrooms' => $student->getClassrooms(),
            ]);
        }
    }

    /**
     * @Route("/teacher/show/{id}", name="teacher_show")
     */
    public function show(Teacher $teacher, AdminRepository $adminRepo)
    {
        return $this->render('teacher/show.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * @Route("/teacher/new", name="teacher_new")
     */
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {
        $teacher = new Teacher();

        $form = $this->createForm(TeacherType::class, $teacher);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // USER
            // hash
            $passRandom = bin2hex(random_bytes(12));
            $encoded = $encoder->encodePassword($teacher->getUser(), $passRandom);
            $teacher->getUser()->setHash($encoded);

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($teacher->getUser()->getFirstName()."-".$teacher->getUser()->getLastName());
            $teacher->getUser()->setSlug($slug);

            // title
            $teacher->getUser()->setTitle('ROLE_TEACHER');

            // on assigne son role et son établissement
            $teacher->getUser()->setCreatedAt(new \DateTime());

            // on récupére la classe et on le transmet au teacher
            $classrooms_selected = $request->request->get('classroom');
            foreach ($classrooms_selected as $classroom) {
                $classroom_id = $classroomRepo->findOneBy(['id' => $classroom]);
                $teacher->addClassroom($classroom_id);
            }

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($teacher);
            $manager->flush();

            // on envoie un email au user pour lui indiquer son mot de passe
            $from = "gpme.contact@gmail.com";
            $link = "<a href='http://passeportgpme.estiennedorves.net'>Passeport Numérique</a>";
            $to = $admin->getUser()->getEmail();
            $subject = "Création d'un compte Passeport Numérique";
            $message = 
            "
            Bonjour,<br>
            Nous avons le plaisir de vous informer de la création d'un compte sur le site ".$link." .<br>
            Votre identifiant est : ".$admin->getUser()->getEmail()." .<br>
            Votre mot de passe est : ".$passRandom."<br>
            A réception de cet email, pensez à modifier votre mot de passe.
            ";
            $headers = "MIME-Version: 1.0"."\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
            $headers .= "From:" . $from;
            mail($to,$subject,$message,$headers);

            // on enregistre un message flash
            $this->addFlash('success','L\'enseignant a bien été créé !');

            // on redirige vers la liste des enseignants
            return $this->redirectToRoute('teacher_show_list');
        }

        // on retourne la vue et les données
        return $this->render('teacher/new.html.twig', [
            'form' => $form->createView(),
            'establishments' => $estabRepo->findAll(),
        ]);
    }

    /**
     * @Route("/teacher/modify/{id}", name="teacher_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Teacher $teacher, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo)
    {
        // on récupére l'utilisateur
        $user = $teacher->getUser();

        // Création du formulaire à partir du fichier ModifyUserType
        $form = $this->createForm(TeacherType::class, $teacher);

        // récupération des données du formulaire
        $form->handleRequest($request);

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

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($teacher);
            $manager->flush();

            // on enregistre un message flash
            $this->addFlash('success','l\'enseignant a bien été mis à jour !');

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('teacher_show', [
                'id' => $teacher->getId(),
            ]);
        }

        // on récupére la ou les classes de l'enseignants dans la BDD
        $establishment = $teacher->getEstablishment();
        $classrooms = $classroomRepo->findBy(['establishment' => $establishment]);

        // on retourne la vue et les données
        return $this->render('teacher/modify.html.twig', [
            'form' => $form->createView(),
            'establishments' => $estabRepo->findAll(),
            'teacher' => $teacher,
        ]);
    }

    /**
     * @Route("/teacher/enable/{id}", name="teacher_enable")
     */
    public function enable(ObjectManager $manager, Request $request, Teacher $teacher)
    {
        $teacher->getUser()->setExist(1);
        $manager->persist($teacher);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/teacher/disable/{id}", name="teacher_disable")
     */
    public function disable(ObjectManager $manager, Request $request, Teacher $teacher)
    {
        $teacher->getUser()->setExist(0);
        $manager->persist($teacher);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/teacher/delete/{id}", name="teacher_delete")
     */
    public function delete(ObjectManager $manager, Teacher $teacher, Request $request)
    {
        if ($teacher->getUser()->getExist() == false){

            $manager->remove($teacher);
            $manager->flush();

            $this->addFlash('success', "L'enseignant a bien été supprimé !");

            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);
            
        } else {
            $this->addFlash('danger', "L'enseignant ne peut être supprimé car il existe !");

            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);
        }
    }
}