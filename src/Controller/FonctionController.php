<?php

namespace App\Controller;


use App\Entity\Fonction;
use App\Form\FonctionType;
use App\Repository\FonctionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class FonctionController extends AbstractController
{
    /**
     * @Route("/fonction", name="fonction")
     */
    public function index(): Response
    {
        return $this->render('fonction/index.html.twig', [
            'controller_name' => 'FonctionController',
        ]);
    }

     
    /**
     * @Route("/listFonction", name="listFonction")
     */
    public function listFonction()
    {
        $fonctions = $this->getDoctrine()->getRepository(Fonction::class)->findAll();
        return $this->render('fonction/list.html.twig', ["fonctions" => $fonctions]);
    }

     /**
     * @Route("/ajouterfonction", name="ajouterfonction")
     */
    public function ajouterfonction(Request $request)
    {
        $fonction = new fonction();
        $form = $this->createForm(FonctionType::class, $fonction);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($fonction);
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("fonction/ajouter.html.twig", array('form' => $form->createView()));
    }

    /**
     * @Route("/supprimerFonction/{id}", name="supprimerFonction")
     */
    public function supprimerfonction($id)
    {
        $fonction = $this->getDoctrine()->getRepository(Fonction::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($fonction);
        $em->flush();
        return $this->redirectToRoute("listFonction");
    } 

     /**
     * @Route("/modifierFonction/{id}", name="modifierFonction")
     */
    public function modifierFonction(Request $request, $id)
    {
        $fonction = $this->getDoctrine()->getRepository(Fonction::class)->find($id);
        $form = $this->createForm(FonctionType::class, $fonction);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("fonction/modifier.html.twig", array('form' => $form->createView()));
    }

}
