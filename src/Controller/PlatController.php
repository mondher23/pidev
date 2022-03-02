<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;

class PlatController extends AbstractController
{
    /**
     * @Route("/plat", name="plat")
     */
    public function index(): Response
    {
        return $this->render('plat/index.html.twig', [
            'controller_name' => 'PlatController',
        ]);
    }
     /**
     * @Route("/ajouterP", name="ajouterP")
     */
    public function ajouterPlat(Request $request){
        $plat = new Plat();
        $form=$this->createForm(PlatType::class, $plat);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
    if (($form->isSubmitted())&&($form->isValid()))  {
    $em= $this->getDoctrine()->getManager();
    $em->persist ($plat);
    $em-> flush();
    return $this->redirectToRoute('listPlatsb');
    }
    return $this->render('plat/ajouter.html.twig', [
    'form' => $form->Createview(),
    ]);
    }

    /**
    * @Route("/listPlatsf", name="listPlatsf")
    */
    public function afficherfPlats()
    {
    $repository =$this->getDoctrine()->getRepository(Plat::class);
    $plat =$repository-> findAll();
    return $this-> render ('plat/afficherf.html.twig', [
    'plat' => $plat]);
    }

    /**
    * @Route("/listPlatsb", name="listPlatsb")
    */
    public function afficherbPlats()
    {
    $repository =$this->getDoctrine()->getRepository(Plat::class);
    $plat =$repository-> findAll();
    return $this-> render ('plat/afficherb.html.twig', [
    'plat' => $plat]);
    }

    /**
     * @Route("/modifPlat/{id}", name="modifPlat")
     */
    public function modifierPlat($id,Request $request){
        $plat = $this->getDoctrine()->getRepository(Plat::class)->find($id);
        $form=$this->createForm(PlatType::class, $plat);
        $form->add('Modifier',SubmitType::class);
        $form->handleRequest($request);
    if(($form->isSubmitted())&&($form->isValid())) {
    $em= $this->getDoctrine()->getManager();
    $em-> flush();
    return $this->redirectToRoute('listPlatsb');
    }
    return $this->render('plat/modifier.html.twig', [
    'form' => $form->Createview(),
    ]);
    }


    /**
    * @Route("/suppPlat/{id}", name="suppPlat")
    */
    public function supprimerPlat($id,PlatRepository $repository)
    {
        $plat = $this->getDoctrine()->getRepository(Plat::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($plat);
        $em->flush();
        return $this->redirectToRoute("listPlatsb");
    }








}
