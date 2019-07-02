<?php

namespace App\Controller;

use App\Entity\Problem;
use App\Form\ProblemType;
use App\Repository\ProblemRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProblemController extends AbstractController
{
    /**
     * @Route("/referentiel/problem/list", name="referentiel_problem_list")
     */
    public function index(ProblemRepository $problemRepo)
    {
    	// problèmes
    	$problems = $problemRepo->findAll();

    	return $this->render('referentiel/problem/list.html.twig', [
    		'problems' => $problems,
    	]);
    }

    /**
     * @Route("/referentiel/problem/new", name="referentiel_problem_new")
     */
    public function create(ObjectManager $manager, Request $request)
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

            $this->addFlash('success','Votre problématique a bien été ajouté !');

    		return $this->redirectToRoute('referentiel_problem_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/problem/new.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

    /**
     * @Route("/referentiel/problem/modify/{id}", name="referentiel_problem_modify")
     */
    public function modify(Problem $problem, ObjectManager $manager, Request $request)
    {
    	// Création du formulaire à partir du fichier ModalityType
    	$form = $this->createForm(ProblemType::class, $problem);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($problem);
    		$manager->flush();

    		$this->addFlash('success','Votre problématique a bien été modifié !');

    		return $this->redirectToRoute('referentiel_problem_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/problem/modify.html.twig', [
            'form' => $form->createView(),
    		'problem' => $problem,
    	]);
    }

    /**
     * @Route("/referentiel/problem/delete/{id}", name="referentiel_problem_delete")
     */
    public function delete(Problem $problem, ObjectManager $manager)
    {
    	$manager->remove($problem);
    	$manager->flush();

    	$this->addFlash('success','Votre problématique a bien été supprimé !');

    	return $this->redirectToRoute('referentiel_problem_list');
    }
}
