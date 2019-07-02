<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/referentiel/task/list", name="referentiel_task_list")
     */
    public function index(TaskRepository $taskRepo)
    {
    	// tâches
    	$tasks = $taskRepo->findAll();

    	return $this->render('referentiel/task/list.html.twig', [
    		'tasks' => $tasks,
    	]);
    }

    /**
     * @Route("/referentiel/task/new", name="referentiel_task_new")
     */
    public function create(ObjectManager $manager, Request $request)
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

            $this->addFlash('success','Votre tâche a bien été ajouté !');

    		return $this->redirectToRoute('referentiel_activity_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/task/new.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

    /**
     * @Route("/referentiel/task/modify/{id}", name="referentiel_task_modify")
     */
    public function modify(Task $task, ObjectManager $manager, Request $request)
    {
    	// Création du formulaire à partir du fichier ModalityType
    	$form = $this->createForm(TaskType::class, $task);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($task);
    		$manager->flush();

    		$this->addFlash('success','Votre tâche a bien été modifié !');

    		return $this->redirectToRoute('referentiel_activity_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/task/modify.html.twig', [
            'form' => $form->createView(),
    		'task' => $task,
    	]);
    }

    /**
     * @Route("/referentiel/task/delete/{id}", name="referentiel_task_delete")
     */
    public function delete(Task $task, ObjectManager $manager)
    {
    	$manager->remove($task);
    	$manager->flush();

    	$this->addFlash('success','Votre tâche a bien été supprimé !');

    	return $this->redirectToRoute('referentiel_activity_list');
    }
}
