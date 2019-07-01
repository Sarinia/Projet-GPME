<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Modality;
use App\Entity\Problem;
use App\Entity\Task;
use App\Entity\Term;
use App\Form\ActivityType;
use App\Form\ModalityType;
use App\Form\ProblemType;
use App\Form\TaskType;
use App\Form\TermType;
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

    /**
     * @Route("/referentiel/activity/new", name="referentiel_activity_new")
     */
    public function createActivity(ObjectManager $manager, Request $request)
    {
    	// on instancie un nouveau Activity
    	$activity = new Activity();

        // Création du formulaire à partir du fichier ActivityType
    	$form = $this->createForm(ActivityType::class, $activity);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($activity);
            $manager->flush();
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/activity/new.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

    /**
     * @Route("/referentiel/task/new", name="referentiel_task_new")
     */
    public function createTask(ObjectManager $manager, Request $request)
    {
    	// on instancie un nouveau Task
    	$task = new Task();

        // Création du formulaire à partir du fichier TaskType
    	$form = $this->createForm(TaskType::class, $task);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($task);
            $manager->flush();
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/task/new.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

    /**
     * @Route("/referentiel/problem/new", name="referentiel_problem_new")
     */
    public function createProblem(ObjectManager $manager, Request $request)
    {
    	// on instancie un nouveau Problem
    	$problem = new Problem();

        // Création du formulaire à partir du fichier ProblemType
    	$form = $this->createForm(ProblemType::class, $problem);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($problem);
            $manager->flush();
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/problem/new.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

    /**
     * @Route("/referentiel/modality/new", name="referentiel_modality_new")
     */
    public function createModality(ObjectManager $manager, Request $request)
    {
    	// on instancie un nouveau Modality
    	$modality = new Modality();

        // Création du formulaire à partir du fichier ModalityType
    	$form = $this->createForm(ModalityType::class, $modality);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($modality);
            $manager->flush();
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/modality/new.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

    /**
     * @Route("/referentiel/term/new", name="referentiel_term_new")
     */
    public function createTerm(ObjectManager $manager, Request $request)
    {
    	// on instancie un nouveau Term
    	$term = new Term();

        // Création du formulaire à partir du fichier TermType
    	$form = $this->createForm(TermType::class, $term);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($term);
            $manager->flush();
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/term/new.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

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
