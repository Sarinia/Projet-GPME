<?php

namespace App\Controller;

use App\Entity\Term;
use App\Form\TermType;
use App\Repository\TermRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TermController extends AbstractController
{
    /**
     * @Route("/referentiel/term/list", name="referentiel_term_list")
     */
    public function index(TermRepository $termRepo)
    {
    	// termes
    	$terms = $termRepo->findAll();

    	return $this->render('referentiel/term/list.html.twig', [
    		'terms' => $terms,
    	]);
    }

    /**
     * @Route("/referentiel/term/new", name="referentiel_term_new")
     */
    public function create(ObjectManager $manager, Request $request)
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

    		$this->addFlash('success','Votre condition a bien été ajouté !');

    		return $this->redirectToRoute('referentiel_term_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/term/new.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

    /**
     * @Route("/referentiel/term/modify/{id}", name="referentiel_term_modify")
     */
    public function modify(Term $term, ObjectManager $manager, Request $request)
    {
    	// Création du formulaire à partir du fichier TermType
    	$form = $this->createForm(TermType::class, $term);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($term);
    		$manager->flush();

    		$this->addFlash('success','Votre condition a bien été modifié !');

    		return $this->redirectToRoute('referentiel_term_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('referentiel/term/modify.html.twig', [
            'form' => $form->createView(),
    		'term' => $term,
    	]);
    }

    /**
     * @Route("/referentiel/term/delete/{id}", name="referentiel_term_delete")
     */
    public function delete(Term $term, ObjectManager $manager)
    {
    	$manager->remove($term);
    	$manager->flush();

    	$this->addFlash('success','Votre condition a bien été supprimé !');

    	return $this->redirectToRoute('referentiel_term_list');
    }
}
