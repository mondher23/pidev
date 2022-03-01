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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
    if ($formC->isSubmitted()&&$formC->isValid()) {

        $flag = $formC->get('flag')->getData();
            $fichier = $culture->getPays() . '.' . $flag->guessExtension();

            $flag->move(
                $this->getParameter('images_directory'),
                $fichier
            );
           $culture->setFlag($fichier);
    $em= $this->getDoctrine()->getManager();
    $culture->setDateAjout(new \DateTime('now'));
    $em->persist ($culture);
    $em-> flush();
    return $this->redirectToRoute('listCulturesb');
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
    * @Route("/listCulturesb", name="listCulturesb")
    */
    public function getCulturesb()
    {
    $repository =$this->getDoctrine()->getRepository(Culture::class);
    $cultures =$repository-> findAll();
    return $this-> render ('culture/getAllculturesb.html.twig', [
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
    if ($formC->isSubmitted()&&$formC->isValid()) {
        
        $flag = $formC->get('flag')->getData();
            $fichier = $culture->getPays() . 'mod.' . $flag->guessExtension();

            $flag->move(
                $this->getParameter('images_directory'),
                $fichier
            );
           $culture->setFlag($fichier);
    $em= $this->getDoctrine()->getManager();
    $em-> flush();
    return $this->redirectToRoute('listCulturesb');
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
        return $this->redirectToRoute("listCulturesb");
    }

//******************************JSON*********************************************************************

    /**
    * @Route("/addCultureJSON/new",name="addCultureJSON")
    */

    public function addCultureJSON(Request $request,NormalizerInterface $Normalizer)
    {
	    $em = $this->getDoctrine()->getManager();
        $culture = new Culture();
        $culture->setRef($request->get('ref'));
        $culture->setPays($request->get('pays'));
        $culture->setTexte($request->get('texte'));
        $culture->setDateAjout(new \DateTime('now'));
        $culture->setFlag($request->get('flag'));
        $em->persist($culture);
        $em->flush();
        $jsonContent = $Normalizer->normalize($culture, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));;
    }

    /**
    * @Route("/listCulturesJSON", name="listCulturesJSON")
    */
    public function getCulturesJSON()
    {   
        $culture = $this->getDoctrine()
        ->getManager()->getRepository(Culture::class)->findall();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($culture);

        return new JsonResponse($formatted);



        //$repository =$this->getDoctrine()->getRepository(Culture::class);
        //$cultures =$repository-> findAll();

        //$jsonContent = $Normalizer->normalize($cultures, 'json',['groups'=>'post:read']);
    
            //return $this-> render ('culture/getAllculturesJSON.html.twig', [
            //'data' => $jsonContent]);
    
        //return new Response(json_encode($jsonContent));;
    }

    /**
    * @Route("/updateCultureJSON/{ref}",name="updateCultureJSON")
    */

    public function updateCultureJSON(Request $request,NormalizerInterface $Normalizer,$ref)
    {
	    $em = $this->getDoctrine()->getManager();
        $culture = $em->getRepository(Culture::class)->find($ref);
        $culture->setRef($request->get('ref'));
        $culture->setPays($request->get('pays'));
        $culture->setTexte($request->get('texte'));
        $culture->setDateAjout(new \DateTime('now'));
        $culture->setFlag($request->get('flag'));
        $em->flush();
        $jsonContent = $Normalizer->normalize($culture, 'json',['groups'=>'post:read']);
        return new Response("updated successfully".json_encode($jsonContent));;
    }

    /**
    * @Route("/deleteCultureJSON/{ref}", name="deleteCultureJSON")
    */
    public function deleteCultureJSON(Request $request,NormalizerInterface $Normalizer,$ref)
    {
        $em = $this->getDoctrine()->getManager();
        $culture = $em->getRepository(Culture::class)->find($ref);
        $em->remove($culture);
        $em->flush();
    
        $jsonContent = $Normalizer->normalize($culture, 'json',['groups'=>'post:read']);
        return new Response("deleted successfully".json_encode($jsonContent));;
    }









}
