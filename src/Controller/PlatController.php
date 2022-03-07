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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Knp\Component\Pager\PaginatorInterface;

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
        $img = $form->get('img_p')->getData();
            $fichier = $plat->getNomP() . '.' . $img->guessExtension();

            $img->move(
                $this->getParameter('images_directory'),
                $fichier
            );
           $plat->setImgP($fichier);
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
    public function afficherfPlats(Request $request, PaginatorInterface $paginator)
    {
        $donnees = $this->getDoctrine()->getRepository(Plat::class)->findAll();
        $plats = $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            2
        );

    return $this-> render ('plat/afficherf.html.twig', [
    'plat' => $plats]);
    }


    /**
     * @Route("/detailPlat/{id}", name="detailPlat")
     */    
    public function detailPlat($id)
    {
        $plat = $this->getDoctrine()->getRepository(Plat::class)->find($id);
        return $this->render('plat/detail.html.twig', array("plat" => $plat));
    }

    /**
    * @Route("/listPlatsb", name="listPlatsb")
    */
    public function afficherbPlats(Request $request, PaginatorInterface $paginator)
    {
        $donnees = $this->getDoctrine()->getRepository(Plat::class)->findAll();
        $plats = $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),2);
    return $this-> render ('plat/afficherb.html.twig', [
    'plat' => $plats]);
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
        $img = $form->get('img_p')->getData();
            $fichier = $plat->getNomP() . 'modifié.' . $img->guessExtension();

            $img->move(
                $this->getParameter('images_directory'),
                $fichier
            );
           $plat->setImgP($fichier);
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
/**
   * Creates a new ActionItem entity.
   *
   * @Route("/search", name="ajax_search")
   * @Method("GET")
   */
  public function searchAction(Request $request)
  {
      
      $requestString = $request->get('q');
      $repository =$this->getDoctrine()->getRepository(Plat::class);
       $plat =$repository-> findEntities($requestString);
      
      if (!$plat) {
          $result['plats']['error'] = "plat introuvable 🙁 ";
      } else {
          $result['plats'] = $this->getRealEntities($plat);
      }
      return new Response(json_encode($result));
  }

public function getRealEntities($plat){

    foreach ($plat as $plat){
        $realEntities[$plat->getId()] = [$plat->getNomP() ,$plat->getPrix(),$plat->getImgP() ,$plat->getDescription(),$plat->getDispo()];
    }

    return $realEntities;
}
    







}
