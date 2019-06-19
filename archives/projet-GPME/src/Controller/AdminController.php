<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\User;
use App\Form\ModifyUserType;
use App\Form\NewUserType;
use App\Repository\AdminRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * Display admins
     * 
     * @Route("/admin/show_list", name="admin_show_list")
     */
    public function index(AdminRepository $adminRepo, UserRepository $userRepo, EstablishmentRepository $estabRepo, Request $request)
    {        
        $admins = $adminRepo->findAll();
        $estabs = $estabRepo->findAll();

        // si le champ de recherche != de vide
        if (!empty($request->request->get('search'))) {
            // on fait une recherche par mot clé
            $search = $request->request->get('search');

            // dans toutes les colonnes de la table
            $users = $userRepo->findBy(['lastName' => $search, 'title' => 'ROLE_ADMIN']);
            $users += $userRepo->findBy(['firstName' => $search, 'title' => 'ROLE_ADMIN']);
            $users += $userRepo->findBy(['email' => $search, 'title' => 'ROLE_ADMIN']);

            // on compte le nombre de ligne que contient le tableau et si c'est == 0
            if (Count($users) == 0) {

                // on enregistre un message flash
                $this->addFlash('success','Aucun résultat pour votre recherche');

                return $this->render('admin/list.html.twig', [
                    // on envoie des données à la vue
                    'result' => $users,
                    'estabs' => $estabs,
                    'search' => $search,
                ]);
            } else {

                // On cherche pour chaque ligne user la ligne admin
                foreach ($users as $user) {
                    $result[] = $adminRepo->findOneBy(['user' => $user]);
                }

                return $this->render('admin/list.html.twig', [
                    // on envoie des données à la vue
                    'result' => $result,
                    'estabs' => $estabs,
                    'search' => $search,
                ]);
            }   
        }

        // si un filtre est selectionné
        if ($request->request->get('establishment_choice')) {
            $estab_choice = $request->request->get('establishment_choice');
            $admins = $adminRepo->findBy(['establishment' => $estab_choice]);

            return $this->render('admin/list.html.twig', [
            // on envoie des données à la vue
                'admins' => $admins,
                'estabs' => $estabs,
                'estab_choice' => $estab_choice,
            ]);
        }

        return $this->render('admin/list.html.twig', [
            'admins' => $admins,
            'estabs' => $estabs,
        ]);
    }

    /**
     * Update a admin
     * 
     * @Route("/admin/new", name="admin_new")
     */
    public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, EstablishmentRepository $estabRepo)
    {
        /**
         * {id} est automatiquement converti et associé à $user->getId
         */

        // on instancie un nouveau user
        $user = new User();

        // on récupère la liste des etablissements
        $estabs = $estabRepo->findAll();

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
            $estab_id = $request->request->get('establishment');
            $establishment = $estabRepo->findOneBy(['id' => $estab_id]);

            // on assigne son role et son établissement
            $admin = new Admin();
            $admin->setUser($user);
            $admin->setEstablishment($establishment);
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

        return $this->render('admin/new.html.twig', [
            // on envoie le formulaire à la vue
            'form' => $form->createView(),
            'estabs' => $estabs,
        ]);
    }

    /**
     * Update a admin
     * 
     * @Route("/admin/modify/{id}", name="admin_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Admin $admin, EstablishmentRepository $estabRepo)
    {        
        /**
         * {id} est automatiquement converti et associé à $user->getId
         */
        $user = $admin->getUser();

        // on récupère la liste des etablissements
        $estabs = $estabRepo->findAll();

        // Création du formulaire à partir du fichier ModifyUserType
        $form = $this->createForm(ModifyUserType::class, $user);

        // récupération des données du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()){

            // on récupére l'établissement
            $estab_id = $request->request->get('establishment');
            $establishment = $estabRepo->findOneBy(['id' => $estab_id]);

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

        return $this->render('admin/modify.html.twig', [
            // on envoie le formulaire à la vue
            'form' => $form->createView(),
            'admin' => $admin,
            'user' => $user,
            'estabs' => $estabs,
            'choice_estab' => $admin->getEstablishment()->getId(),
        ]);
    }

    /**

     * Disable an admin
     * 
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    public function delete(ObjectManager $manager, Admin $admin)
    {
        $user = $admin->getUser();

        if ($user->getExist() == false){

            $manager->remove($admin);
            $manager->remove($user);
            $manager->flush();

            $this->addFlash('success', "L'administrateur a bien été supprimé !");
            
            return $this->redirectToRoute('admin_show_list');            
        } else {
            $this->addFlash('danger', "L'administrateur ne peut être supprimé car il existe !");
            
            return $this->redirectToRoute('admin_show_list');  
        }        
    }
}
