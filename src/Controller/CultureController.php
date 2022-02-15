<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Culture;
use App\Form\CultureType;
use App\Repository\CultureRepository;

class CultureController extends AbstractController
{
    /**
     * @Route("/culture", name="culture")
     */
    public function index(): Response
    {
        return $this->render('culture/index.html.twig', [
            'controller_name' => 'CultureController',
        ]);
    }

    /**
     * @Route("/addC", name="addC")
     */
    public function addCulture(Request $request){
        $culture = new Culture();
        $formC=$this->createForm(CultureType::class, $culture);
        $formC->add('Ajouter',SubmitType::class);
        $formC->handleRequest($request);
    if ($formC->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();
    $em->persist ($culture);
    $em-> flush();
    return $this->redirectToRoute('listCultures');
    }
    return $this->render('culture/add.html.twig', [
    'formC' => $formC->Createview(),
    ]);
    }

    /**
    * @Route("/listCultures", name="listCultures")
    */
    public function getCultures()
    {
    $repository =$this->getDoctrine()->getRepository(Culture::class);
    $cultures =$repository-> findAll();
    return $this-> render ('culture/getAllcultures.html.twig', [
    'cultures' => $cultures]);
    }

    /**
     * @Route("/upadteC/{ref}", name="updateC")
     */
    public function updateCulture($ref,Request $request){
        $culture = $this->getDoctrine()->getRepository(Culture::class)->find($ref);
        $formC=$this->createForm(CultureType::class, $culture);
        $formC->add('Modifier',SubmitType::class);
        $formC->handleRequest($request);
    if ($formC->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();
    $em-> flush();
    return $this->redirectToRoute('listCultures');
    }
    return $this->render('culture/update.html.twig', [
    'formC' => $formC->Createview(),
    ]);
    }


    /**
    * @Route("/deleteCulture/{ref}", name="deleteCulture")
    */
    public function deleteCulture($ref,CultureRepository $repository)
    {
        $culture = $this->getDoctrine()->getRepository(Culture::class)->find($ref);
        $em = $this->getDoctrine()->getManager();
        $em->remove($culture);
        $em->flush();
        return $this->redirectToRoute("listCultures");
    }






}
