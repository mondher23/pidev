<?php

namespace App\Controller;
use App\Entity\Depense;
use App\Entity\Fonction;
use App\Form\DepenseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\DepenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class DepensesController extends AbstractController
{
    /**
     * @Route("/depenses", name="depenses")
     */
    public function index(): Response
    {
        return $this->render('depenses/index.html.twig', [
            'controller_name' => 'DepensesController',
        ]);
    }
 /**
     * @Route("/listDepense", name="listDepense")
     */
    public function listDepense()
    {
        $depenses = $this->getDoctrine()->getRepository(Depense::class)->findAll();
        return $this->render('depenses/list.html.twig', ["depenses" => $depenses]);
    }

     /**
     * @Route("/ajoutersalaire", name="ajoutersalaire")
     */
    public function ajoutersalaire(Request $request)
    {
        $depense = new depense();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->add('fonction',EntityType::class,[
            'class'=>Fonction::class,
            'choice_label'=>'salaire',
            'expanded'=>false,
            'multiple'=>false
        ]);

        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $depense->setMontant($form->get('photo')->getData());
            $depense->setType("salaire");
            $em = $this->getDoctrine()->getManager();
            $em->persist($depense);
            $em->flush();
            return $this->redirectToRoute('listDepense');
        }
        return $this->render("depenses/ajoutersalaire.html.twig", array('form' => $form->createView()));
    }
     /**
     * @Route("/ajouterdepense", name="ajouterdepense")
     */
    public function ajouterdepense(Request $request)
    {
        $depense = new depense();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($depense);
            $em->flush();
            return $this->redirectToRoute('listDepense');
        }
        return $this->render("depenses/ajouter.html.twig", array('form' => $form->createView()));
    }

    /**
     * @Route("/supprimerDepense/{id}", name="supprimerDepense")
     */
    public function supprimerdepense($id)
    {
        $depense = $this->getDoctrine()->getRepository(Depense::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($depense);
        $em->flush();
        return $this->redirectToRoute("listDepense");
    } 

     /**
     * @Route("/modifierDepense/{id}", name="modifierDepense")
     */
    public function modifierDepense(Request $request, $id)
    {
        $depense = $this->getDoctrine()->getRepository(Depense::class)->find($id);
        $form = $this->createForm(DepenseType::class, $depense);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listDepense');
        }
        return $this->render("depense/modifier.html.twig", array('form' => $form->createView()));
    }


}
