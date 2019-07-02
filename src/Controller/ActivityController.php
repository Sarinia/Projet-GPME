<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    /**
     * @Route("/referentiel/activity/list", name="referentiel_activity_list")
     */
    public function index(ActivityRepository $activityRepo)
    {
    	// activités
    	$activities = $activityRepo->findAll();

    	return $this->render('referentiel/activity/list.html.twig', [
    		'activities' => $activities,
    	]);
    }

    /**
     * @Route("/referentiel/activity/new", name="referentiel_activity_new")
     */
    public function create(ObjectManager $manager, Request $request)
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

    		$this->addFlash('success','Votre activité a bien été ajouté !');

    		return $this->redirectToRoute('referentiel_activity_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/activity/new.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

    /**
     * @Route("/referentiel/activity/modify/{id}", name="referentiel_activity_modify")
     */
    public function modify(Activity $activity, ObjectManager $manager, Request $request)
    {
    	// Création du formulaire à partir du fichier ModalityType
    	$form = $this->createForm(ActivityType::class, $activity);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($activity);
    		$manager->flush();

    		$this->addFlash('success','Votre activité a bien été modifié !');

    		return $this->redirectToRoute('referentiel_activity_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/activity/modify.html.twig', [
            'form' => $form->createView(),
    		'activity' => $activity,
    	]);
    }

    /**
     * @Route("/referentiel/activity/delete/{id}", name="referentiel_activity_delete")
     */
    public function delete(Activity $activity, ObjectManager $manager)
    {
    	$manager->remove($activity);
    	$manager->flush();

    	$this->addFlash('success','Votre activité a bien été supprimé !');

    	return $this->redirectToRoute('referentiel_activity_list');
    }
}
