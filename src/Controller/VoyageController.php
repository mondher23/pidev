<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;

class VoyageController extends AbstractController
{
    /**
     * @Route("/voyage", name="voyage")
     */
    public function index(): Response
    {
        return $this->render('voyage/index.html.twig', [
            'controller_name' => 'VoyageController',
        ]);
    }

     /**
     * @Route("/addV", name="addV")
     */
    public function addVoyage(Request $request){
        $voyage = new Voyage();
        $formV=$this->createForm(VoyageType::class, $voyage);
        $formV->add('Ajouter',SubmitType::class);
        $formV->handleRequest($request);
    if ($formV->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();
    $voyage->setDone(0);
    $em->persist ($voyage);
    $em-> flush();
    return $this->redirectToRoute('listVoyages');
    }
    return $this->render('voyage/add.html.twig', [
    'formV' => $formV->Createview(),
    ]);
    }

    
    /**
    * @Route("/listVoyages", name="listVoyages")
    */
    public function getVoyages()
    {
    $repository =$this->getDoctrine()->getRepository(Voyage::class);
    $voyages =$repository-> findAll();
    return $this-> render ('voyage/getAllvoyages.html.twig', [
    'voyages' => $voyages]);
    }

    /**
     * @Route("/upadteV/{id_v}", name="updateV")
     */
    public function updateVoyage($id_v,Request $request){
        $voyage = $this->getDoctrine()->getRepository(Voyage::class)->find($id_v);
        $formV=$this->createForm(VoyageType::class, $voyage);
        $formV->add('Modifier',SubmitType::class);
        $formV->handleRequest($request);
    if ($formV->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();
    $em-> flush();
    return $this->redirectToRoute('listVoyages');
    }
    return $this->render('voyage/update.html.twig', [
    'formV' => $formV->Createview(),
    ]);
    }


    /**
    * @Route("/deleteVoyage/{id_v}", name="deleteVoyage")
    */
    public function deleteVoyage($id_v,VoyageRepository $repository)
    {
        $voyage = $this->getDoctrine()->getRepository(Voyage::class)->find($id_v);
        $em = $this->getDoctrine()->getManager();
        $em->remove($voyage);
        $em->flush();
        return $this->redirectToRoute("listVoyages");
    }


}


