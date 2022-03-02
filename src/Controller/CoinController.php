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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
    if (($form->isSubmitted())&&($form->isValid())) {
    $em= $this->getDoctrine()->getManager();
    $em->persist ($coin);
    $em-> flush();
    return $this->redirectToRoute('listCoinsb');
    }
    return $this->render('coin/ajouter.html.twig', [
    'form' => $form->Createview(),
    ]);
    }
   

/**
     * @Route("/ajouterCoinJSON/new", name="ajouterCoinJSON")
     */
    public function ajouterCoinJSON(Request $request, NormalizerInterface $Normalizer)
    {
        $coin = new coin();
        
        
        $coin->setNbPlaces($request->get('nb_places'));
        $coin->setPays($request->get('pays'));
        $coin->setImg($request->get('img'));
        $coin->setDescriptionC($request->get('description_c'));

            $em = $this->getDoctrine()->getManager();
            
            $em->persist($coin);
            $em->flush();
            $formatted = $Normalizer->normalize($coin, 'json', ['groups'=>'post:read']);

            return new JsonResponse(json_encode($formatted)); 
        
    }

    /**
    * @Route("/listCoinsb", name="listCoinsb")
    */
    public function afficherbCoins()
    {
    $repository =$this->getDoctrine()->getRepository(Coin::class);
    $coin =$repository-> findAll();
    return $this-> render ('coin/afficherb.html.twig', [
    'coin' => $coin]);
    }
    /**
    * @Route("/listCoinsf", name="listCoinsf")
    */
    public function afficherfCoins()
    {
    $repository =$this->getDoctrine()->getRepository(Coin::class);
    $coin =$repository-> findAll();
    return $this-> render ('coin/afficherf.html.twig', [
    'coin' => $coin]);
    }

    /**
    * @Route("/listCoinsJson", name="listCoinsJson")
    */
    public function afficherCoins(NormalizerInterface $normalizer)
    { $repository =$this->getDoctrine()->getRepository(Coin::class);
        $coin =$repository-> findAll();
        $jsoncontent=$normalizer->normalize($coin,'json',['groups'=>'post:read']);
        return new Jsonresponse($jsoncontent);
    }
    

    /**
     * @Route("/modifCoin/{id}", name="modifCoin")
     */
    public function modifierCoin($id,Request $request){
        $coin = $this->getDoctrine()->getRepository(Coin::class)->find($id);
        $form=$this->createForm(CoinType::class, $coin);
        $form->add('Modifier',SubmitType::class);
        $form->handleRequest($request);
        if(($form->isSubmitted())&&($form->isValid()))  {
    $em= $this->getDoctrine()->getManager();
    $em-> flush();
    return $this->redirectToRoute('listCoinsb');
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
        return $this->redirectToRoute("listCoinsb");
    }
    






}



