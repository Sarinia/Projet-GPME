<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Establishment;
use App\Form\EstablishmentType;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EstablishmentRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
* @IsGranted("ROLE_SADMIN")
*/
class EstablishmentController extends AbstractController
{
    /**
     * @Route("/establishment/show_list", name="establishment_show_list")
     */
    public function index(EstablishmentRepository $establishmentRepo)
    {
        return $this->render('establishment/list.html.twig', [
            'establishments' => $establishmentRepo->findAll(),
        ]);
    }

    /**
     * @Route("/establishment/show/{id}", name="establishment_show")
     */
    public function show(Establishment $establishment, ClassroomRepository $classroomRepo)
    {
        return $this->render('establishment/show.html.twig', [
            'establishment' => $establishment,
            'classrooms' => $classroomRepo->findBy(['establishment' => $establishment]),
        ]);
    }

    /**
     * @Route("/establishment/new", name="establishment_new")
     */
    public function create(ObjectManager $manager, Request $request)
    {        
        $establishment = new Establishment();

        $form = $this->createForm(EstablishmentType::class, $establishment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $establishment->setCreatedAt(new \DateTime());

            $slugify = new Slugify();// on crée le slug
            $slug = $slugify->slugify($establishment->getName());
            $establishment->setSlug($slug);
            
            $manager->persist($establishment);
            $manager->flush();

            $this->addFlash('success','L\'établissement a bien été créé');

            return $this->redirectToRoute('establishment_show_list');
        }

        return $this->render('establishment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /** 
     * @Route("/establishment/modify/{id}", name="establishment_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Establishment $establishment)
    {
        $form = $this->createForm(EstablishmentType::class, $establishment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $slugify = new Slugify();// on crée le slug
            $slug = $slugify->slugify($establishment->getName());
            $establishment->setSlug($slug);

            $manager->persist($establishment);
            $manager->flush();

            $this->addFlash('success','L\'établissement a bien été mis à jour !');

            return $this->redirectToRoute('establishment_show', [
                'id' => $establishment->getId(),
            ]);
        }

        return $this->render('establishment/modify.html.twig', [
            'form' => $form->createView(),
            'establishment' => $establishment,
        ]);
    }

    /**
     * @Route("/establishment/enable/{id}", name="establishment_enable")
     */
    public function enable(ObjectManager $manager, Request $request, Establishment $establishment)
    {
        $establishment->setExist(1);
        $manager->persist($establishment);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/establishment/disable/{id}", name="establishment_disable")
     */
    public function disable(ObjectManager $manager, Request $request, Establishment $establishment)
    {
        $establishment->setExist(0);
        $manager->persist($establishment);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/establishment/delete/{id}", name="establishment_delete")
     */
    public function delete(ObjectManager $manager, Establishment $establishment, Request $request)
    {
        if ($establishment->getExist() == false){

            if (Count($establishment->getClassrooms()) == 0 && Count($establishment->getAdmins()) == 0) {

                $manager->remove($establishment);
                $manager->flush();

                $this->addFlash('success', "L'établissement a bien été supprimé !");

                $referer = $request->headers->get('referer');   
                return new RedirectResponse($referer); 

            } else {

                $this->addFlash('danger', "l'établissement a encore un administrateur ou des classes !");

                $referer = $request->headers->get('referer');   
                return new RedirectResponse($referer);  
            }

        } else {

            $this->addFlash('danger', "L'établissement ne peut être supprimé car il existe !");

            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);  
        } 
    }
}
