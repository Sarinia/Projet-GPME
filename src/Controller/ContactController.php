<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request)
    {
    	$envoyer = $request->request->get('envoyer');

    	if (isset($envoyer)) {

    		// on récupère les données du formulaire
    		$sujet = $request->request->get('sujet');
    		$message = $request->request->get('message');

    		if (!empty($sujet) && !empty($message)) {
    			mail('gpme.contact@gmail.com',$sujet,$message);

    			$this->addFlash('success','Votre message a bien été envoyé');

    			return $this->redirectToRoute('dashboard', []);
    		} else {
    			$this->addFlash('danger','Tous les champs doivent être remplis');

    			return $this->render('contact/index.html.twig', []);
    		}
    	}

    	return $this->render('contact/index.html.twig', []);
    }
}
