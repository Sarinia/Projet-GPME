<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\User;
use App\Form\ModifyUserType;
use App\Form\NewUserType;
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
use Symfony\Component\Validator\Constraints\DateTime;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/show_list", name="admin_show_list")
     */
    public function index(StudentRepository $studentRepo, TeacherRepository $teacherRepo, AdminRepository $adminRepo, EstablishmentRepository $estabRepo, ClassroomRepository $classroomRepo, Request $request)
    {
        // liste des admins pour le super-admin
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            // on récupère la liste des établissements pour le menu filtre
            $establishments = $estabRepo->findAll();

            // on récupère la liste des classes pour le menu filtre
            $classrooms = $classroomRepo->findAll();

            // on récupère la liste des administrateurs
            $admins = $adminRepo->findAll();

            // si une recherche est faite
            if ($request->request->get('rechercher') == "oui" && $request->request->get('search') != "") {
                $search = $request->request->get('search');

                // on compare les données et on les stocke dans un tableau
                $result = [];
                foreach ($admins as $admin) {
                    if ($admin->getUser()->getFirstName() == $search || $admin->getUser()->getLastName() == $search || $admin->getUser()->getEmail() == $search) {
                        $result[] = $admin;
                    }
                }

                // on compte le nombre de ligne dans le tableau
                if (Count($result) == 0) {

                    // on enregistre un message flash
                    $this->addFlash('warning','Aucun résultat pour votre recherche');

                    // on retourne la vue et les données
                    return $this->render('admin/list.html.twig', [
                        'admins' => $admins,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                } else {

                    // on retourne la vue et les données
                    return $this->render('admin/list.html.twig', [
                        'admins' => $result,
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
                    return $this->render('admin/list.html.twig', [
                        'admins' => $admins,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }

                // si un filtre d'établissement a été choisi
                if ($request->request->get('establishment_choice') != 0) {
                    $establishment_choice = $request->request->get('establishment_choice');

                    // on compare les données et on les stocke dans un tableau
                    $result = [];
                    foreach ($admins as $admin) {
                        if ($admin->getEstablishment()->getId() == $establishment_choice) {
                            $result[] = $admin;
                        }
                    }

                    // on retourne la vue et les données
                    return $this->render('admin/list.html.twig', [
                        'admins' => $result,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }

                // si un filtre de classe a été choisi
                if ($request->request->get('classroom_choice') != 0) {
                    $classroom_choice = $request->request->get('classroom_choice');

                    // on compare les données et on les stocke dans un tableau
                    $result = [];
                    foreach ($admins as $admin) {
                        foreach ($admin->getClassrooms() as $classroom) {
                            if ($classroom->getId() == $classroom_choice) {
                                $result[] = $admin;
                            }
                        }
                    }
                    // on retourne la vue et les données
                    return $this->render('admin/list.html.twig', [
                        'admins' => $result,
                        'establishments' => $establishments,
                        'classrooms' => $classrooms,
                    ]);
                }
            }

            // on retourne la vue et les données
            return $this->render('admin/list.html.twig', [
                'admins' => $admins,
                'establishments' => $establishments,
                'classrooms' => $classrooms,
            ]);
        }
    }

    /**
     * @Route("/admin/show/{id}", name="admin_show")
     */
    public function show(Admin $admin)
    {
        // on retourne la vue et les données
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/admin/new", name="admin_new")
     */
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, EstablishmentRepository $estabRepo)
    {
        // on instancie un nouveau user
        $user = new User();

        // on récupère la liste des etablissements
        $establishments = $estabRepo->findAll();

        // Création du formulaire à partir du fichier NewUserType
        $form = $this->createForm(NewUserType::class, $user);

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
            $user->setTitle('ROLE_ADMIN');

            // on récupére l'établissement
            $establishment = $request->request->get('establishment');
            $establishment = $estabRepo->findOneBy(['id' => $establishment]);

            // on assigne son role et son établissement
            $admin = new Admin();
            $admin->setUser($user);
            $admin->setEstablishment($establishment);
            $admin->setCreatedAt(new \DateTime());
            $manager->persist($admin);

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

            // on stocke un message flash
            $this->addFlash('success','L\'administrateur a bien été créé !');

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('admin_show_list');
        }

        // on retourne la vue et les données
        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
            'establishments' => $establishments,
        ]);
    }

    /**
     * @Route("/admin/modify/{id}", name="admin_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Admin $admin, EstablishmentRepository $estabRepo)
    {
        // on récupére l'utilisateur
        $user = $admin->getUser();

        // on récupère la liste des etablissements
        $establishments = $estabRepo->findAll();

        // Création du formulaire à partir du fichier ModifyUserType
        $form = $this->createForm(ModifyUserType::class, $user);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on récupére l'établissement
            $establishment = $request->request->get('establishment');
            $establishment = $estabRepo->findOneBy(['id' => $establishment]);

            // on assigne son établissement
            $admin->setEstablishment($establishment);
            $manager->persist($admin);

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($user);
            $manager->flush();

            // on stocke un message flash
            $this->addFlash('success','l\'administrateur a bien été mis à jour !');

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('admin_show_list');
        }

        // on retourne la vue et les données
        return $this->render('admin/modify.html.twig', [
            'form' => $form->createView(),
            'establishments' => $establishments,
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    public function delete(ObjectManager $manager, Admin $admin)
    {
        // on récupére l'utilisateur
        $user = $admin->getUser();

        // on vérifie que son compte est inactif
        if ($user->getExist() == false){

            // on supprime la ligne de la table Admin et de la table User
            $manager->remove($admin);
            $manager->remove($user);
            $manager->flush();

            // on enregistre un message flash
            $this->addFlash('success', "L'administrateur a bien été supprimé !");

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('admin_show_list');
        } else {

            // on enregistre un message flash
            $this->addFlash('danger', "L'administrateur ne peut être supprimé car il existe !");

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('admin_show_list');
        }
    }
}
