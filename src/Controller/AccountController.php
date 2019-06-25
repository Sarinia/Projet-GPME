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
        // si un utilisateur est connecté
        if ($this->getUser() && !empty($this->getUser())) {

            // on le redirige vers le dashboard
            return $this->redirectToRoute('dashboard');

        // si aucun utilisateur n'est connecté
        } else {

            // on récupére tous les départements dans la base de données
            $depts = $dept_repo->findAll();

            // on récupére les données du formulaire dans des variables
            $dept_choice = $request->request->get('dept_choice');
            $etb_choice = $request->request->get('etb_choice');

            // on vérifie si un établissement a été choisi
            if (isset($etb_choice) && !empty($etb_choice)) {

                // on redirige vers la page de login
                return $this->redirectToRoute('account_login', [
                    'etb_choice' => $etb_choice,
                ]);

            } else {

                // on récupére la liste des établissements en fonction du département choisi
                $etbs = $estab_repo->findBy(['department' => $dept_choice]);

                // on retourne la vue et les données
                return $this->render('account/index.html.twig', [
                    'depts' => $depts,
                    'etbs' => $etbs,
                ]);
            }

            // on retourne la vue et les données
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
        // on récupére l'utilisateur connecté
        $user = $this->getUser();

        // on crée le formulaire
        $form = $this->createForm(AccountType::class, $user);

        // on récupére les données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // on persiste les données
            $manager->persist($user);
            
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
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {

                // on enregistre un message flash
                $this->addFlash( 'danger', 'L\'ancien mot de passe est incorrect !' );
            } else {

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
                return $this->redirectToRoute('dashboard');
            }
        }

        // on retourne la vue et les données
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{etb_choice}/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils, $etb_choice, EstablishmentRepository $estabRepo)
    {
        $establishment = $estabRepo->findOneBy(['slug' => $etb_choice]);

        // on enregistre l'erreur s'il y en a
        $error = $utils->getLastAuthenticationError();

        // on retourne la vue et les données
        return $this->render('account/login.html.twig', [
            'hasError' => $error,
            'establishment' => $establishment,
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(){}
}
