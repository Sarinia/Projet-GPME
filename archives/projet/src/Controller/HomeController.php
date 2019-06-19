<?php

namespace App\Controller;

use App\Repository\DepartmentRepository;
use App\Repository\EstablishmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, DepartmentRepository $dept_repo, EstablishmentRepository $estab_repo)
    {
        // récupération de tous les départements
    	$depts = $dept_repo->findAll();

        // récupération des données dans des variables
    	$dept = $request->request->get('dept');
    	$etb = $request->request->get('etb');

        // test si le département et le lycée ont été choisis
    	if (isset($dept) && isset($etb)) {

    		if (!empty($dept) && !empty($etb)) {

    			return $this->RedirectToRoute('account_login', [
    				'slug'=> $etb]);
    		}
    	}

        // test si le département a été choisi
    	if (isset($dept)) {

    		if (!empty($dept)) {

    			$etbs = $estab_repo->findBy(['department' => $dept]);

    			return $this->render('home/index.html.twig', [
    				'depts' => $depts,
    				'etbs' => $etbs,
    			]);
    		}
    	}

    	return $this->render('home/index.html.twig', [
    		'depts' => $depts,
    	]);
    }
}
