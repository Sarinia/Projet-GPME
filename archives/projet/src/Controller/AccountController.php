<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login/{slug}", name="account_login")
     */
    public function login($slug, AuthenticationUtils $utils)
    {
    	$error = $utils-> getLastAuthenticationError();

    	dump($error);

    	// if (student) {
    	// 	return $this->render('student/dashboard.html.twig', [
     //        'slug' => $slug,
     //        'hasError' => $error,
     //    ]);
    	// }

    	// if (teacher) {
    	// 	return $this->render('teacher/dashboard.html.twig', [
     //        'slug' => $slug,
     //        'hasError' => $error,
     //    ]);
    	// }

    	// if (admin) {
    	// 	return $this->render('admin/dashboard.html.twig', [
     //        'slug' => $slug,
     //        'hasError' => $error,
     //    ]);
    	// }

    	// if (greatadmin) {
    	// 	return $this->render('greatadmin/dashboard.html.twig', [
     //        'slug' => $slug,
     //        'hasError' => $error,
     //    ]);
    	// }

        return $this->RedirectToRoute('user_index', [
            'slug' => $slug,
            'hasError' => $error,
        ]);
    }


    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {

        return $this->render('account/profile.html.twig', [

        ]);
    }


    /**
     * @Route("/account/profile", name="account_profile")
     */
    public function profile()
    {

        return $this->render('account/profile.html.twig', [

        ]);
    }


    /**
     * @Route("/account/password-update", name="account_password")
     */
    public function updatePassword()
    {

        return $this->render('account/profile.html.twig', [

        ]);
    }
}
