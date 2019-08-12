<?php

namespace App\Controller;

use App\Entity\PwdForget;
use App\Entity\User;
use App\Repository\PwdForgetRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PwdForgetController extends AbstractController
{
    /**
     * @Route("/pwd/forget/list", name="pwd_forget_show_list")
     */
    public function index(PwdForgetRepository $pwdForgetRepo)
    {
    	$pwdForgets = $pwdForgetRepo->findAll([],['createdAt' => 'DESC']);

    	return $this->render('pwd_forget/list.html.twig', [
    		'pwdForgets' => $pwdForgets,
    	]);
    }

    /**
     * @Route("/pwd/forget/new", name="pwd_forget_new")
     */
    public function create(ObjectManager $manager, Request $request, UserRepository $userRepo)
    {
    	if ($request->request->get('submit_pwdforget') == "envoyer") {

    		if ($request->request->get('email')) {

    			$emailSend = $request->request->get('email');

    			$user = $userRepo->findOneBy(['email' => $emailSend]);

    			if ($user) {
    				$pwdForget = new PwdForget();
    				$pwdForget->setUser($user)
    				->setCreatedAt(new \DateTime())
    				->setUnread(1);

    				$manager->persist($pwdForget);
    				$manager->flush();

    				$sadmin = $userRepo->findOneBy(['title' => "ROLE_SADMIN"]);

    				$from = "gpme.contact@gmail.com";
    				$link = "<a href='http://passeportgpme.estiennedorves.net'>Passeport Numérique</a>";
    				$to = $sadmin->getEmail();
    				$subject = "Demande de nouveau mot de passe";
    				$message = 
    				"
    				Bonjour,<br>
    				Une demande de nouveau mot de passe vous a été faite.<br>
    				Demande de la part de ".$emailSend." .<br>
    				";
    				$headers = "MIME-Version: 1.0"."\r\n";
    				$headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
    				$headers .= "From:" . $from;
    				mail($to,$subject,$message,$headers);

    				$this->addFlash('success','Votre demande a bien été envoyé !');

    				return $this->redirectToRoute('homepage');

    			} else {

    				$this->addFlash('warning','Nous ne sommes pas en mesure de donner suite à votre demande !');

    				return $this->redirectToRoute('homepage');
    			}
    		}
    	}
    	return $this->render('pwd_forget/new.html.twig');
    }

    /**
     * @Route("/pwd/forget/modify/{user}/{forget}", name="pwd_forget_modify")
     */
    public function generate($user, $forget, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, UserRepository $userRepo, PwdForgetRepository $pwdForgetRepo)
    {
    	$user = $userRepo->findOneBy(['id' => $user]);
    	$pwdForget = $pwdForgetRepo->findOneBy(['id' => $forget]);

    	$passRandom = bin2hex(random_bytes(12));
    	$encoded = $encoder->encodePassword($user, $passRandom);
    	$user->setHash($encoded);

    	$pwdForget->setUnread(0);

    	$manager->persist($user);
    	$manager->persist($pwdForget);

    	$manager->flush();

    	$from = "gpme.contact@gmail.com";
    	$link = "<a href='http://passeportgpme.estiennedorves.net'>Passeport Numérique</a>";
    	$to = $user->getEmail();
    	$subject = "Votre mot de passe a été réinitialisé";
    	$message = 
    	"
    	Bonjour,<br>
    	Nous avons le plaisir de vous informer que le mot de passe de votre compte sur le site ".$link." vient d'être réinitialisé.<br>
    	Votre identifiant est : ".$user->getEmail()." .<br>
    	Votre mot de passe est : ".$passRandom." .<br>
    	A réception de cet email, pensez à modifier votre mot de passe.
    	";
    	$headers = "MIME-Version: 1.0"."\r\n";
    	$headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
    	$headers .= "From:" . $from;
    	mail($to,$subject,$message,$headers);

    	$this->addFlash('success','Le mot de passe a bien été généré et envoyé !');

    	$referer = $request->headers->get('referer');   
    	return new RedirectResponse($referer);
    }
}