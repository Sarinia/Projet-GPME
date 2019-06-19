<?php

namespace App\Controller;

use App\Entity\Admin;
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
    public function index(AdminRepository $adminRepo)
    {        
        $admins = $adminRepo->findAll();

        return $this->render('admin/list.html.twig', [
            'admins' => $admins,
        ]);
    }

    // /**
    //  * Update a admin
    //  * 
    //  * @Route("/admin/new", name="admin_new")
    //  */
    // public function create(UserPasswordEncoderInterface $encoder, ObjectManager $manager, Request $request, EstablishmentRepository $estabRepo)
    // {
    //     /**
    //      * {id} est automatiquement converti et associé à $user->getId
    //      */
    //     // on instancie un nouveau user
    //     $user = new User();

    //     // on récupère la liste des etablissements
    //     $estabs = $estabRepo->findAll();

    //     // Création du formulaire à partir du fichier NewAdminType
    //     $form = $this->createForm(NewAdminType::class, $user);

    //     // récupération des données du formulaire
    //     $form->handleRequest($request);

    //     // si le formulaire est soumis et valide
    //     if ($form->isSubmitted() && $form->isValid()){
            
    //         // on créé, on le crypte et on transmet le mot de passe
    //         $passRandom = bin2hex(random_bytes(12));
    //         $encoded = $encoder->encodePassword($user, $passRandom);
    //         $user->setHash($encoded);

    //         // on instancie, on crée le slug et on transmet le slug
    //         $slugify = new Slugify();
    //         $slug = $slugify->slugify($user->getFirstName()."-".$user->getLastName());
    //         $user->setSlug($slug);

    //         // on récupére l'établissement et on le transmet au user
    //         $estab_id = $request->request->get('establishment');
    //         $establishment = $estabRepo->findOneBy(['id' => $estab_id]);
    //         $user->setEstablishment($establishment);

    //         // on récupére le role et on le transmet au user
    //         $role = $roleRepo->findOneBy(['title' => 'ROLE_ADMIN']);
    //         $user->addUserRole($role);

    //         // on envoie un email au user pour lui indiquer son mot de passe
    //         mail(
    //             $user->getEmail(),
    //             'Bonjour',
    //             'un compte a été créé pour vous sur le site GPME,
    //             identifiant : votre email
    //             votre mot de passe : '.$passRandom
    //         );

    //         // on persiste et on sauvegarde les données du formulaire
    //         $manager->persist($user);
    //         $manager->flush();

    //         // on stocke un message flash
    //         $this->addFlash('success','L\'administrateur a bien été créé !');

    //         // on redirige vers la liste des administrateurs
    //         return $this->redirectToRoute('admin_show_list');
    //     }

    //     return $this->render('admin/new.html.twig', [
    //         // on envoie le formulaire à la vue
    //         'form' => $form->createView(),
    //         'estabs' => $estabs,
    //     ]);
    // }

    // /**
    //  * Update a admin
    //  * 
    //  * @Route("/admin/modify/{slug}/{id}", name="admin_modify")
    //  */
    // public function modify(ObjectManager $manager, Request $request, User $user, EstablishmentRepository $estabRepo)
    // {        
    //     /**
    //      * {id} est automatiquement converti et associé à $user->getId
    //      */

    //     // on récupère la liste des etablissements
    //     $estabs = $estabRepo->findAll();

    //     // Création du formulaire à partir du fichier ModifyAdminType
    //     $form = $this->createForm(ModifyAdminType::class, $user);

    //     // récupération des données du formulaire
    //     $form->handleRequest($request);

    //     // si le formulaire est soumis et valide
    //     if ($form->isSubmitted() && $form->isValid()){

    //         // on récupére l'établissement et on le transmet au user
    //         $estab_id = $request->request->get('establishment');
    //         $establishment = $estabRepo->findOneBy(['id' => $estab_id]);
    //         $user->setEstablishment($establishment);

    //         // on persiste et on sauvegarde les données du formulaire
    //         $manager->persist($user);
    //         $manager->flush();

    //         // on stocke un message flash
    //         $this->addFlash('success','l\'administrateur a bien été mis à jour !');

    //         // on redirige vers la liste des administrateurs
    //         return $this->redirectToRoute('admin_show_list');
    //     }

    //     return $this->render('admin/modify.html.twig', [
    //         // on envoie le formulaire à la vue
    //         'form' => $form->createView(),
    //         'user' => $user,
    //         'estabs' => $estabs,
    //         'choice_estab' => $user->getEstablishment()->getId(),
    //     ]);
    // }

    // /**

    //  * Disable an admin
    //  * 
    //  * @Route("/admin/delete/{id}", name="admin_delete")
    //  */
    // public function delete(ObjectManager $manager, User $user)
    // {        
    //     if ($user->getExist() == false){

    //         $manager->remove($user);
    //         $manager->flush();

    //         $this->addFlash('success', "L'administrateur a bien été supprimé !");
            
    //         return $this->redirectToRoute('admin_show_list');            
    //     } else {
    //         $this->addFlash('danger', "L'administrateur ne peut être supprimé car il existe !");
            
    //         return $this->redirectToRoute('admin_show_list');  
    //     }        
    // }
}
