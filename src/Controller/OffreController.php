<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Knp\Component\Pager\PaginatorInterface;

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
    if ($formO->isSubmitted()&&$formO->isValid()) {

        $image = $formO->get('image')->getData();
            $fichier = $offre->getTitre() . '.' . $image->guessExtension();

            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
           $offre->setImage($fichier);
    $em= $this->getDoctrine()->getManager();
    $offre->setExpire(0);
    $em->persist ($offre);
    $em-> flush();
    return $this->redirectToRoute('listOffresb');
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
    $offres =$repository-> listOffresDispo();
    return $this-> render ('offre/getAlloffres.html.twig', [
    'offres' => $offres]);
    }

    /**
    * @Route("/listOffresb", name="listOffresb")
    */
    public function getOffresb(Request $request, PaginatorInterface $paginator)
    {
    $repository =$this->getDoctrine()->getRepository(Offre::class);
    $donnees =$repository-> findAll();

    $offres = $paginator->paginate(
        $donnees,
        $request->query->getInt('page',1),
        2
    );

    return $this-> render ('offre/getAlloffresb.html.twig', [
    'offres' => $offres]);
    }

    /**
     * @Route("/upadteO/{id}", name="updateO")
     */
    public function updateOffre($id,Request $request){
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find($id);
        $formO=$this->createForm(OffreType::class, $offre);
        $formO->add('Modifier',SubmitType::class);
        $formO->handleRequest($request);
    if ($formO->isSubmitted()&&$formO->isValid()) {
        

        $image = $formO->get('image')->getData();
            $fichier = $offre->getid() . 'mod.' . $image->guessExtension();

            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
           $offre->setImage($fichier);
    $em= $this->getDoctrine()->getManager();

    $em-> flush();
    return $this->redirectToRoute('listOffresb');
    }
    return $this->render('offre/update.html.twig', [
    'formO' => $formO->Createview(),
    ]);
    }


    /**
    * @Route("/deleteOffre/{id}", name="deleteOffre")
    */
    public function deleteOffre($id,OffreRepository $repository)
    {
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute("listOffresb");
    }


      //******************************JSON*********************************************************************

    /**
    * @Route("/addOffreJSON/new",name="addOffreJSON")
    */

    public function addOffreJSON(Request $request,NormalizerInterface $Normalizer)
    {
	    $em = $this->getDoctrine()->getManager();
        $offre = new Offre();
        //$offre->setId($request->get('id'));
        $offre->setTitre($request->get('titre'));
        $offre->setDescription($request->get('description'));
        $offre->setRemise($request->get('remise'));
        $offre->setImage($request->get('image'));
        $offre->setDebDate(new \DateTime('now'));
        $offre->setExpDate(new \DateTime('now'));
        $offre->setExpire(0);
        $offre->setBackgroundColor("#e5bc4e");
        $offre->setBorderColor("#000000");
        $offre->setTextColor("#000000");
        
        $em->persist($offre);
        $em->flush();
        $jsonContent = $Normalizer->normalize($offre, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));;
    }

    /**
    * @Route("/listOffresJSON", name="listOffresJSON")
    */
    public function getOffresJSON(NormalizerInterface $Normalizer )
    {   
        //$offre = $this->getDoctrine()
        //->getManager()->getRepository(Offre::class)->findall();
        //$serializer = new Serializer([new ObjectNormalizer()]);
        //$formatted = $serializer->normalize($offre);

        //return new JsonResponse($formatted);



        $repository =$this->getDoctrine()->getRepository(Offre::class);
        $offres =$repository->findAll();

        $jsonContent = $Normalizer->normalize($offres, 'json',['groups'=>'post:read']);
    
            //return $this-> render ('Offre/getAllOffresJSON.html.twig', [
            //'data' => $jsonContent]);
    
        return new Response(json_encode($jsonContent));;
    }

    /**
    * @Route("/updateOffreJSON/{id}",name="updateOffreJSON")
    */

    public function updateOffreJSON(Request $request,NormalizerInterface $Normalizer,$id)
    {
	    $em = $this->getDoctrine()->getManager();
        $offre = $em->getRepository(Offre::class)->find($id);
        //$offre->setId($request->get('id'));
        $offre->setTitre($request->get('titre'));
        $offre->setDescription($request->get('description'));
        $offre->setRemise($request->get('remise'));
        $offre->setImage($request->get('image'));
        $offre->setDebDate(new \DateTime('now'));
        $offre->setExpDate(new \DateTime('now'));
        $em->flush();
        $jsonContent = $Normalizer->normalize($offre, 'json',['groups'=>'post:read']);
        return new Response("updated successfully".json_encode($jsonContent));;
    }

    /**
    * @Route("/deleteOffreJSON/{id}", name="deleteOffreJSON")
    */
    public function deleteOffreJSON(Request $request,NormalizerInterface $Normalizer,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $offre = $em->getRepository(Offre::class)->find($id);
        $em->remove($offre);
        $em->flush();
    
        $jsonContent = $Normalizer->normalize($offre, 'json',['groups'=>'post:read']);
        return new Response("deleted successfully".json_encode($jsonContent));;
    }
}
