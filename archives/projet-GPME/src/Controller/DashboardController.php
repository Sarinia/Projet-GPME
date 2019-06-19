<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
	/**
     * @Route("/dashboard", name="dashboard")
     */
	public function dashboard()
	{
		if ($this->getUser()->getRoles()) {

			$userRoles = $this->getUser()->getRoles();
			
			foreach ($userRoles as $value) {
				switch ($value) {
					case 'ROLE_USER':
					return $this->RedirectToRoute('dashboard_student', [
						'slug' => $this->getUser()->getSlug(),
					]);
					break;

					case 'ROLE_TEACHER':
					return $this->RedirectToRoute('dashboard_teacher', [
						'slug' => $this->getUser()->getSlug(),
					]);
					break;

					case 'ROLE_ADMIN':
					return $this->RedirectToRoute('dashboard_admin', [
						'slug' => $this->getUser()->getSlug(),
					]);
					break;

					case 'ROLE_SADMIN':
					return $this->RedirectToRoute('dashboard_sadmin', [
						'slug' => $this->getUser()->getSlug(),
					]);
					break;

					default:
                    # code...
					break;
				}
			}
		} else {
			return $this->render('dashboard/index.html.twig', [
				'info' => 'Aucun role ne vous a été attribué, veuillez contacter votre administrateur',
			]);
		}
	}

    /**
     * @Route("/dashboard/etudiant/{slug}", name="dashboard_student")
     */
    public function dashboard_student($slug)
    {
    	return $this->render('dashboard/student.html.twig', [
    		'info' => 'Bonjour l\'étudiant',
    	]);
    }

    /**
     * @Route("/dashboard/enseignant/{slug}", name="dashboard_teacher")
     */
    public function dashboard_teacher($slug)
    {
    	return $this->render('dashboard/teacher.html.twig', [
    		'info' => 'Bonjour l\'enseignant',
    	]);
    }

    /**
     * @Route("/dashboard/admin/{slug}", name="dashboard_admin")
     */
    public function dashboard_admin($slug)
    {
    	return $this->render('dashboard/admin.html.twig', [
    		'info' => 'Bonjour l\'admin',
    	]);
    }

    /**
     * @Route("/dashboard/sadmin/{slug}", name="dashboard_sadmin")
     */
    public function dashboard_sadmin($slug)
    {
    	return $this->render('dashboard/sadmin.html.twig', [
    		'info' => 'Bonjour le super-admin',
    	]);
    }
}
