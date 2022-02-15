<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;

class OffreController extends AbstractController
{
    /**
     * @Route("/offre", name="offre")
     */
    public function index(): Response
    {
        return $this->render('offre/index.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }

    /**
     * @Route("/addO", name="addO")
     */
    public function addOffre(Request $request){
        $offre = new Offre();
        $formO=$this->createForm(OffreType::class, $offre);
        $formO->add('Ajouter',SubmitType::class);
        $formO->handleRequest($request);
    if ($formO->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();
    $offre->setExpire(0);
    $em->persist ($offre);
    $em-> flush();
    return $this->redirectToRoute('listOffres');
    }
    return $this->render('offre/add.html.twig', [
    'formO' => $formO->Createview(),
    ]);
    }

    /**
    * @Route("/listOffres", name="listOffres")
    */
    public function getOffres()
    {
    $repository =$this->getDoctrine()->getRepository(Offre::class);
    $offres =$repository-> findAll();
    return $this-> render ('offre/getAlloffres.html.twig', [
    'offres' => $offres]);
    }

    /**
     * @Route("/upadteO/{id_o}", name="updateO")
     */
    public function updateOffre($id_o,Request $request){
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find($id_o);
        $formO=$this->createForm(OffreType::class, $offre);
        $formO->add('Modifier',SubmitType::class);
        $formO->handleRequest($request);
    if ($formO->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();

    $em-> flush();
    return $this->redirectToRoute('listOffres');
    }
    return $this->render('offre/update.html.twig', [
    'formO' => $formO->Createview(),
    ]);
    }


    /**
    * @Route("/deleteOffre/{id_o}", name="deleteOffre")
    */
    public function deleteOffre($id_o,OffreRepository $repository)
    {
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find($id_o);
        $em = $this->getDoctrine()->getManager();
        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute("listOffres");
    }
}
