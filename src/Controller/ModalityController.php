<?php

namespace App\Controller;

use App\Entity\Modality;
use App\Form\ModalityType;
use App\Repository\ModalityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ModalityController extends AbstractController
{
    /**
     * @Route("/modality/list", name="referentiel_modality_list")
     */
    public function index(ModalityRepository $modalityRepo)
    {
    	// modalités
    	$modalities = $modalityRepo->findAll();

    	return $this->render('modality/list.html.twig', [
    		'modalities' => $modalities,
    	]);
    }

    /**
     * @Route("/modality/new", name="referentiel_modality_new")
     */
    public function create(ObjectManager $manager, Request $request)
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

            $this->addFlash('success','Votre modalité a bien été ajouté !');

            return $this->redirectToRoute('referentiel_list');
        }

        // on redirige vers la liste des administrateurs
        return $this->render('modality/new.html.twig', [
          'form' => $form->createView(),
      ]);
    }

    /**
     * @Route("/modality/modify/{id}", name="referentiel_modality_modify")
     */
    public function modify(Modality $modality, ObjectManager $manager, Request $request)
    {
    	// Création du formulaire à partir du fichier ModalityType
    	$form = $this->createForm(ModalityType::class, $modality);

        // récupération des données du formulaire
    	$form->handleRequest($request);

        // si le formulaire est soumis et valide
    	if ($form->isSubmitted() && $form->isValid()){
    		$manager->persist($modality);
    		$manager->flush();

    		$this->addFlash('success','Votre modalité a bien été modifié !');

    		return $this->redirectToRoute('referentiel_list');
    	}

        // on redirige vers la liste des administrateurs
    	return $this->render('modality/modify.html.twig', [
            'form' => $form->createView(),
            'modality' => $modality,
        ]);
    }

    /**
     * @Route("/modality/delete/{id}", name="referentiel_modality_delete")
     */
    public function delete(Modality $modality, ObjectManager $manager, Request $request)
    {
    	$manager->remove($modality);
    	$manager->flush();

    	$this->addFlash('success','Votre modalité a bien été supprimé !');

    	$referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }
}