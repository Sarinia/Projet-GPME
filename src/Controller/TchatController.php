<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Tchat;
use App\Form\MessageType;
use App\Form\TchatType;
use App\Repository\MessageRepository;
use App\Repository\TchatRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TchatController extends AbstractController
{
    /**
     * @Route("/tchat/list", name="tchat_show_list")
     */
    public function index(TchatRepository $tchatRepo)
    {
    	$tchatsRecipient = $tchatRepo->findBy(['recipient' => $this->getUser()],['createdAt' => 'DESC']);
    	$tchatsForwarder = $tchatRepo->findBy(['forwarder' => $this->getUser()],['createdAt' => 'DESC']);

    	return $this->render('tchat/list.html.twig', [
    		'tchatsRecipient' => $tchatsRecipient,
    		'tchatsForwarder' => $tchatsForwarder,
    	]);
    }

    /**
     * @Route("/tchat/show/{id}", name="tchat_show")
     */
    public function show(Tchat $tchat, ObjectManager $manager, Request $request, MessageRepository $messageRepo, TchatRepository $tchatRepo)
    {
    	$message = new Message();

    	$form = $this->createForm(MessageType::class, $message);

    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()){

    		$message->setTchat($tchat);

    		$message->setCreatedAt(new \DateTime());

    		$message->setCreatedBy($this->getUser());

    		$manager->persist($message);
    		$manager->flush();

    		return $this->redirectToRoute('tchat_show', [
    			'id' => $tchat->getId(),
    		]);
    	}

    	return $this->render('tchat/show.html.twig', [
    		'form' => $form->createView(),
    		'tchat' => $tchat,
    		'messages' => $messageRepo->findBy(['tchat' => $tchat],['createdAt' => 'DESC']),
    	]);
    }

    /**
     * @Route("/tchat/new", name="tchat_new")
     */
    public function create(ObjectManager $manager, Request $request, UserRepository $userRepo)
    {
    	$tchat = new Tchat();

    	$form = $this->createForm(TchatType::class, $tchat);

    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()){

    		if ($request->request->get('user')) {
    			$user = $request->request->get('user');
    			$user = $userRepo->findOneBy(['id' => $user]);
    		} else {

    			$this->addFlash('warning','Vous devez choisir un destinataire !');

    			return $this->render('tchat/new.html.twig', [
    				'form' => $form->createView(),
    				'users' => $userRepo->findAll(),
    			]);
    		}

    		$tchat->setRecipient($user);

    		$tchat->setForwarder($this->getUser());

    		$tchat->setCreatedAt(new \DateTime());

    		$manager->persist($tchat);
    		$manager->flush();

    		$this->addFlash('success','Votre discussion a bien été créée !');

    		return $this->redirectToRoute('tchat_show_list');
    	}

    	return $this->render('tchat/new.html.twig', [
    		'form' => $form->createView(),
    		'users' => $userRepo->findAll(),
    	]);
    }
}