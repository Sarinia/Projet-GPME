<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use App\Repository\EstablishmentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/department/show_list", name="department_show_list")
     */
    public function index(DepartmentRepository $departmentRepo)
    {
        return $this->render('department/list.html.twig', [// on retourne la vue et les données
            'departments' => $departmentRepo->findAll(),
        ]);
    }

    /**
     * @Route("/department/show/{id}", name="department_show")
     */
    public function show(Department $department, EstablishmentRepository $establishmentRepo)
    {
        return $this->render('department/show.html.twig', [// on retourne la vue et les données
            'department' => $department,
            'establishments' => $establishmentRepo->findBy(['department' => $department]),
        ]);
    }

    /**
     * @Route("/department/new", name="department_new")
     */
    public function create(ObjectManager $manager,Request $request)
    {           
        $department = new Department();// on crée une nouvelle instance
        
        $form = $this->createForm(DepartmentType::class, $department);// on crée le formulaire
        
        $form->handleRequest($request);// on mémorise les données du formulaire
        
        if ($form->isSubmitted() && $form->isValid()){// si le formulaire est soumis et que les champs sont valides

            $department->setCreatedAt(new \DateTime());// date de création
            
            $manager->persist($department);// on persite l'entité et on la sauvegarde
            $manager->flush();
            
            $this->addFlash('success','Le département a bien été créé');// on enregistre un message flash
            
            return $this->redirectToRoute('department_show_list');// on redirige vers la liste
        }
        
        return $this->render('department/new.html.twig', [// on retourne la vue et les données
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/department/modify/{id}", name="department_modify")
     */
    public function modify(ObjectManager $manager, Request $request, Department $department)
    {
        $form = $this->createForm(DepartmentType::class, $department);// on crée le formulaire

        $form->handleRequest($request);// on mémorise les données du formulaire

        if ($form->isSubmitted() && $form->isValid()){// si le formulaire est soumis et que les champs sont valides

            $manager->persist($department);// on persite l'entité et on la sauvegarde
            $manager->flush();

            $this->addFlash('success','Le département a bien été mis à jour !');// on enregistre un message flash

            return $this->redirectToRoute('department_show', [// on redirige vers la liste
                'id' => $department->getId(),
            ]);
        }

        return $this->render('department/modify.html.twig', [// on retourne la vue et les données
            'form' => $form->createView(),
            'department' => $department,
        ]);
    }

    /**
     * @Route("/department/enable/{id}", name="department_enable")
     */
    public function enable(ObjectManager $manager, Request $request, Department $department)
    {
        $department->setExist(1);
        $manager->persist($department);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/department/disable/{id}", name="department_disable")
     */
    public function disable(ObjectManager $manager, Request $request, Department $department)
    {
        $department->setExist(0);
        $manager->persist($department);
        $manager->flush();

        $referer = $request->headers->get('referer');   
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/department/delete/{id}", name="department_delete")
     */
    public function delete(ObjectManager $manager, Department $department, Request $request)
    {
        if ($department->getExist() == false){// on vérifie si exist est false

            if (Count($department->getEstablishment()) == 0) {//On vérifie si le département contient encore des établissements

                $manager->remove($department);// on supprime le département sans possibilité de retour
                $manager->flush();

                $this->addFlash('success', "Le département a bien été supprimé !");// on enregistre un message flash

                $referer = $request->headers->get('referer');   
                return new RedirectResponse($referer);

            } else {

                $this->addFlash('danger', "Supprimez d'abord tous les établissements du département !");// on enregistre un message flash
                
                $referer = $request->headers->get('referer');   
                return new RedirectResponse($referer);
                
            }
        } else {

            $this->addFlash('danger', "Le département est encore actif et ne peut pas être supprimé");// on enregistre un message flash
            
            $referer = $request->headers->get('referer');   
            return new RedirectResponse($referer);
        } 
    }
}