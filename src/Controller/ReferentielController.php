<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\ModalityRepository;
use App\Repository\ProblemRepository;
use App\Repository\TaskRepository;
use App\Repository\TermRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    	// tâches
    	$tasks = $taskRepo->findAll();
    	// problèmes
    	$problems = $problemRepo->findAll();
    	// modalités
    	$modalities = $modalityRepo->findAll();
    	// termes
    	$terms = $termRepo->findAll();

    	return $this->render('referentiel/list.html.twig', [
    		'activities' => $activities,
    		'tasks' => $tasks,
    		'problems' => $problems,
    		'modalities' => $modalities,
    		'terms' => $terms,
    	]);
    }

    // /**
    //  * @Route("/referentiel/new", name="referentiel_new")
    //  */
    // public function create()
    // {
    // 	// on instancie un nouveau user
    //     $referentiel = new Referentiel();

    //     // Création du formulaire à partir du fichier NewUserType
    //     $form = $this->createForm(ReferentielType::class, $referentiel);

    //     // récupération des données du formulaire
    //     $form->handleRequest($request);

    //     // si le formulaire est soumis et valide
    //     if ($form->isSubmitted() && $form->isValid()){
    // }

    // /**
    //  * @Route("/referentiel/modify", name="referentiel_modify")
    //  */
    // public function modify()
    // {

    // }

    // /**
    //  * @Route("/referentiel/delete", name="referentiel_delete")
    //  */
    // public function delete()
    // {
    	
    // }
}
