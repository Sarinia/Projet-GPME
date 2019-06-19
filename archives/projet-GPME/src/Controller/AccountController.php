<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Repository\DepartmentRepository;
use App\Repository\EstablishmentRepository;
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
    public function index(Request $request, DepartmentRepository $dept_repo, EstablishmentRepository $estab_repo)
    {
        if ($this->getUser() && !empty($this->getUser())) {
            return $this->redirectToRoute('dashboard', []);
        } else {
        // récupération de tous les départements
            $depts = $dept_repo->findAll();

        // récupération des données dans des variables
            $dept = $request->request->get('dept');
            $etb = $request->request->get('etb');

        // test si le département et le lycée ont été choisis
            if (isset($dept) && isset($etb)) {

                if (!empty($dept) && !empty($etb)) {

                    return $this->redirectToRoute('account_login', [
                        'slug'=> $etb]);
                }
            }

        // test si le département a été choisi
            if (isset($dept)) {

                if (!empty($dept)) {

                    $etbs = $estab_repo->findBy(['department' => $dept]);

                    return $this->render('account/index.html.twig', [
                        'depts' => $depts,
                        'etbs' => $etbs,
                    ]);
                }
            }

            return $this->render('account/index.html.twig', [
                'depts' => $depts,
            ]);
        }
    }

    /**
     * @Route("/account/profile", name="account_profile")
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success','Votre profil a bien été mis à jour !');
        }
        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/password-update", name="account_password")
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();

        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                $this->addFlash( 'danger', 'L\'ancien mot de passe est incorrect !' );
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $encoded = $encoder->encodePassword($user, $newPassword);
                $user->setHash($encoded);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success','Le mot de passe a bien été modifié');
                return $this->redirectToRoute('dashboard');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login/{slug}", name="account_login")
     */
    public function login($slug, AuthenticationUtils $utils)
    {
        $error = $utils-> getLastAuthenticationError();
        return $this->render('account/login.html.twig', [
            'slug' => $slug,
            'hasError' => $error,
        ]);
    }

    /**
     * @Route("/login/admin", name="account_loginAdmin")
     */
    public function loginAdmin( AuthenticationUtils $utils)
    {
        $error = $utils-> getLastAuthenticationError();
        return $this->render('account/loginAdmin.html.twig', [
            'hasError' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {

    }
}
