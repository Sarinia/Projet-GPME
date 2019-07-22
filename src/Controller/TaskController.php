<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ActivityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/list", name="referentiel_task_list")
     */
    public function index(ActivityRepository $activityRepo)
    {
    	// tâches
    	$activities = $activityRepo->findAll();

    	return $this->render('task/list.html.twig', [
    		'activities' => $activities,
    	]);
    }

    /**
     * @Route("/task/new", name="referentiel_task_new")
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

            return $this->redirectToRoute('referentiel_task_list');
        }

        // on redirige vers la liste des administrateurs
        return $this->render('task/new.html.twig', [
          'form' => $form->createView(),
      ]);
    }

    /**
     * @Route("/task/modify/{id}", name="referentiel_task_modify")
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

    		return $this->redirectToRoute('referentiel_task_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('task/modify.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/task/delete/{id}", name="referentiel_task_delete")
     */
    public function delete(Task $task, ObjectManager $manager, Request $request)
    {
    	$manager->remove($task);
    	$manager->flush();

    	$this->addFlash('success','Votre tâche a bien été supprimé !');

    	$referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }
}