<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/show_list", name="admin_show_list")
     */
    public function index(UserRepository $userRepo, AdminRepository $adminRepo)
    {
        // $users = $userRepo->findBy(['title' => 'ROLE_ADMIN']);
        $admins = $adminRepo->findAll();

        return $this->render('admin/list.html.twig', [
            'admins' => $admins,
        ]);
    }

    /**
     * @Route("/admin/show/{id}", name="admin_show")
     */
    public function show(Admin $admin, ClassroomRepository $classroomRepo)
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
            'classrooms' => $classroomRepo->findBy(['establishment' => $admin->getEstablishment()]),
        ]);
    }

    /**
     * @Route("/admin/new", name="admin_new")
     */
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, AdminRepository $adminRepo, EstablishmentRepository $establishmentRepo)
    {
        $admin = new Admin();

        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $admins = $adminRepo->findAll();
            
            if ($request->request->get('admin')['establishment']) {
                $establishment = $establishmentRepo->findOneBy(['id' => $request->request->get('admin')['establishment']]);

                if (count($establishment->getAdmins()) > 0) {
                    $this->addFlash('danger','L\'établissement choisi a déja un administrateur.');

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

            $admin->getUser()->setCreatedAt(new \DateTime());
            
            $manager->persist($admin);
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
            Votre mot de passe est : ".$passRandom."<br>
            A réception de cet email, pensez à modifier votre mot de passe.
            ";
            $headers = "MIME-Version: 1.0"."\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
            $headers .= "From:" . $from;
            mail($to,$subject,$message,$headers);

            $this->addFlash('success','L\'administrateur a bien été créé !');

            return $this->redirectToRoute('admin_show_list');
        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/admin/modify/{id}", name="admin_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Admin $admin, AdminRepository $adminRepo, EstablishmentRepository $establishmentRepo)
    {
        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            if ($request->request->get('admin')['establishment']) {

                $establishment = $request->request->get('admin')['establishment'];

                if ($establishment != $admin->getEstablishment()->getId()) {

                    $establishment = $establishmentRepo->findOneBy(['id' => $establishment]);

                    if (count($establishment->getAdmins()) > 0) {
                        $this->addFlash('danger','L\'établissement choisi a déja un administrateur.');

                        return $this->render('admin/modify.html.twig', [
                            'form' => $form->createView(),
                            'admin' => $admin,
                        ]);
                    }
                }
                
            }

            $manager->persist($admin);
            $manager->flush();

            $this->addFlash('success','l\'administrateur a bien été mis à jour !');

            return $this->redirectToRoute('admin_show', [
                'id' => $admin->getId(),
            ]);
        }

        return $this->render('admin/modify.html.twig', [
            'form' => $form->createView(),
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/admin/enable/{id}", name="admin_enable")
     */
    public function enable(ObjectManager $manager, Request $request, Admin $admin)
    {
        $admin->getUser()->setExist(1);
        $manager->persist($admin);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/admin/disable/{id}", name="admin_disable")
     */
    public function disable(ObjectManager $manager, Request $request, Admin $admin)
    {
        $admin->getUser()->setExist(0);
        $manager->persist($admin);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    public function delete(ObjectManager $manager, Admin $admin, Request $request)
    {
        if ($admin->getUser()->getExist() == false){

            $manager->remove($admin);
            $manager->flush();

            $this->addFlash('success', "L'administrateur a bien été supprimé !");

            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);
        } else {

            $this->addFlash('danger', "L'administrateur ne peut être supprimé car il existe !");

            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);
        }
    }
}