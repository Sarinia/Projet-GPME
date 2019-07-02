<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\ModalityRepository;
use App\Repository\ProblemRepository;
use App\Repository\TaskRepository;
use App\Repository\TermRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReferentielController extends AbstractController
{
    /**
     * @Route("/referentiel/list", name="referentiel_list")
     */
    public function index(ActivityRepository $activityRepo, ProblemRepository $problemRepo, TaskRepository $taskRepo, ModalityRepository $modalityRepo, TermRepository $termRepo)
    {
    	// activités
    	$activities = $activityRepo->findAll();
    	// problèmes
    	$problems = $problemRepo->findAll();
    	// modalités
    	$modalities = $modalityRepo->findAll();
    	// termes
    	$terms = $termRepo->findAll();

    	return $this->render('referentiel/list.html.twig', [
    		'activities' => $activities,
    		'problems' => $problems,
    		'modalities' => $modalities,
    		'terms' => $terms,
    	]);
    }
}