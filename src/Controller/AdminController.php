<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\User;
use App\Form\AdminType;
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
    public function index(AdminRepository $adminRepo)
    {
        // liste des admins pour le super-admin
        if ($this->getUser()->getTitle() == "ROLE_SADMIN") {

            // on récupère la liste des administrateurs
            $admins = $adminRepo->findAll();

            // on retourne la vue et les données
            return $this->render('admin/list.html.twig', [
                'admins' => $admins,
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
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, AdminRepository $adminRepo)
    {
        // on instancie un nouveau user
        $admin = new Admin();

        // Création du formulaire à partir du fichier NewUserType
        $form = $this->createForm(AdminType::class, $admin);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            $admins = $adminRepo->findAll();
            
            foreach ($admins as $value) {
                if ($value->getEstablishment()->getId() == $request->request->get('admin')['establishment']) {

                    // on stocke un message flash
                    $this->addFlash('danger','L\'établissement choisi a déja un administrateur.');

                    // on retourne la vue et les données
                    return $this->render('admin/new.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }

            // hash
            $passRandom = bin2hex(random_bytes(12));
            $encoded = $encoder->encodePassword($admin->getUser(), $passRandom);
            $admin->getUser()->setHash($encoded);

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($admin->getUser()->getFirstName()."-".$admin->getUser()->getLastName());
            $admin->getUser()->setSlug($slug);

            // title
            $admin->getUser()->setTitle('ROLE_ADMIN');
            $manager->persist($admin->getUser());

            // on persiste et on sauvegarde les données du formulaire
            $admin->setCreatedAt(new \DateTime());
            
            $manager->persist($admin);
            $manager->flush();

            // on envoie un email au user pour lui indiquer son mot de passe
            mail(
                $admin->getUser()->getEmail(),
                'Bonjour',
                'un compte a été créé pour vous sur le site GPME,
                identifiant : '.$admin->getUser()->getEmail().'
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

        ]);
    }

    /**
     * @Route("/admin/modify/{id}", name="admin_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Admin $admin, AdminRepository $adminRepo)
    {
        // Création du formulaire à partir du fichier ModifyUserType
        $form = $this->createForm(AdminType::class, $admin);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            if ($admin->getEstablishment()->getId() != $request->request->get('admin')['establishment']) {
                $admins = $adminRepo->findAll();

                foreach ($admins as $value) {
                    if ($value->getEstablishment()->getId() == $request->request->get('admin')['establishment']) {

                    // on stocke un message flash
                        $this->addFlash('danger','L\'établissement choisi a déja un administrateur.');

                    // on retourne la vue et les données
                        return $this->render('admin/new.html.twig', [
                            'form' => $form->createView(),
                            'admin' => $admin,
                        ]);
                    }
                }
            }

            // on persiste et on sauvegarde les données du formulaire
            $manager->persist($admin);
            $manager->flush();

            // on stocke un message flash
            $this->addFlash('success','l\'administrateur a bien été mis à jour !');

            // on redirige vers la liste des administrateurs
            return $this->redirectToRoute('admin_show', [
                'id' => $admin->getId(),
            ]);
        }

        // on retourne la vue et les données
        return $this->render('admin/modify.html.twig', [
            'form' => $form->createView(),
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    public function delete(ObjectManager $manager, Admin $admin)
    {
        // on vérifie que son compte est inactif
        if ($admin->getUser()->getExist() == false){

            // on supprime la ligne de la table Admin
            $manager->remove($admin);
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
