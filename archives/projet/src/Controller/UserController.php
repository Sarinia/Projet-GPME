<?php

namespace App\Controller;

use App\Repository\CardspRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/index", name="user_index")
     */
    public function index(UserRepository $user_repo, CardspRepository $cardsp_repo)
    {
        $user = $user_repo->findOneBy(['id' => 5]);
        // $users = $user_repo->findAll();
        // dump($user);
        $cards = $cardsp_repo->findBy(['user' => $user]);
        // $cards = $cardsp_repo->findAll();
        // dump($cards);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'cards' => $cards,
        ]);
    }


    /**
     * @Route("/users/etudiant", name="show_student")
     * @Route("/users/enseignant", name="show_teacher")
     * @Route("/users/administrateur", name="show_admin")
     */
    public function show_user(Request $request, UserRepository $user_repo)
    {
        $menu = $request->request->get('menu');

        if ($menu == "student") {

            $users = $user_repo->findBy(['status' => $menu]);
            dump($users);
            return $this->RedirectToRoute('show_student', [
            'users' => $users,
        ]);
        } elseif ($menu == "teacher") {

            $users = $user_repo->findBy(['status' => $menu]);
            dump($users);
            return $this->RedirectToRoute('show_teacher', [
            'users' => $users,
        ]);
        } elseif ($menu == "admin") {

            $users = $user_repo->findBy(['role' => "ROLE_ADMIN"]);
            dump($users);
            return $this->RedirectToRoute('show_admin', [
            'users' => $users,
        ]);
        }
    }


    /**
     * @Route("/creer-user", name="new_user")
     */
    public function new_user()
    {
        return $this->render('user/new_user.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/modifier-user", name="modify_user")
     */
    public function modify_user()
    {
        return $this->render('user/modify_user.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/supprimer-user", name="delete_user")
     */
    public function delete_user()
    {
        return $this->render('user/delete_user.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
