<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Coin;
use App\Form\CoinType;
use App\Repository\CoinRepository;

class CoinController extends AbstractController
{
    /**
     * @Route("/coin", name="coin")
     */
    public function index(): Response
    {
        return $this->render('coin/index.html.twig', [
            'controller_name' => 'CoinController',
        ]);
    }
    /**
     * @Route("/ajouterC", name="ajouterC")
     */
    public function ajouterCoin(Request $request){
        $coin = new Coin();
        $form=$this->createForm(CoinType::class, $coin);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
    if ($form->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();
    $em->persist ($coin);
    $em-> flush();
    return $this->redirectToRoute('listCoins');
    }
    return $this->render('coin/ajouter.html.twig', [
    'form' => $form->Createview(),
    ]);
    }

    /**
    * @Route("/listCoins", name="listCoins")
    */
    public function afficherCoins()
    {
    $repository =$this->getDoctrine()->getRepository(Coin::class);
    $coin =$repository-> findAll();
    return $this-> render ('coin/afficher.html.twig', [
    'coin' => $coin]);
    }

    /**
     * @Route("/modifCoin/{id}", name="modifCoin")
     */
    public function modifierCoin($id,Request $request){
        $coin = $this->getDoctrine()->getRepository(Coin::class)->find($id);
        $form=$this->createForm(CoinType::class, $coin);
        $form->add('Modifier',SubmitType::class);
        $form->handleRequest($request);
    if ($form->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();
    $em-> flush();
    return $this->redirectToRoute('listCoins');
    }
    return $this->render('coin/modifier.html.twig', [
    'form' => $form->Createview(),
    ]);
    }


    /**
    * @Route("/suppCoin/{id}", name="suppCoin")
    */
    public function supprimerCoin($id,CoinRepository $repository)
    {
        $coin = $this->getDoctrine()->getRepository(Coin::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($coin);
        $em->flush();
        return $this->redirectToRoute("listCoins");
    }






}



