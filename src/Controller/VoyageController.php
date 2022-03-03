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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;

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
    public function addVoyage(Request $request, \Swift_Mailer $mailer){
        $voyage = new Voyage();
        $formV=$this->createForm(VoyageType::class, $voyage);
        $formV->add('Ajouter',SubmitType::class);
        $formV->handleRequest($request);
    if ($formV->isSubmitted()&&$formV->isValid()) {
    $em= $this->getDoctrine()->getManager();
    $voyage->setDone(0);
    $em->persist ($voyage);
    $em-> flush();

    $message = (new \Swift_Message('Le Tour Du Monde!'))
                   ->setFrom('letourdumonde9@gmail.com')
                   ->setContentType("text/html")
                   ->setTo('zizou.doudi@gmail.com')
                   ->setBody(
                    "<p style='color: black;'> Vous avez gagner un voyage  </p>"
                   )
               ;
    
                $mailer->send($message);


    return $this->redirectToRoute('listVoyages');
    }
    return $this->render('voyage/add.html.twig', [
    'formV' => $formV->Createview(),
    ]);
    }

    
    /**
    * @Route("/listVoyages", name="listVoyages")
    */
    public function getVoyages(Request $request, PaginatorInterface $paginator)
    {
    $repository =$this->getDoctrine()->getRepository(Voyage::class);
    $donnees =$repository-> findAll();

    $voyages = $paginator->paginate(
        $donnees,
        $request->query->getInt('page',1),
        2
    );

    return $this-> render ('voyage/getAllvoyages.html.twig', [
    'voyages' => $voyages]);
    }

    /**
    * @Route("/pdfVoyages", name="pdfVoyages")
    */
    public function pdfVoyages()
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $repository =$this->getDoctrine()->getRepository(Voyage::class);
        $voyages =$repository-> findAll();
        //return $this-> render ('voyage/pdf.html.twig', [
          //  'voyages' => $voyages]);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('voyage/pdf.html.twig', [
            'voyages' => $voyages
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("La liste des voyages.pdf", [
            "Attachment" => true
        ]);


    
    }

    /**
     * @Route("/upadteV/{id}", name="updateV")
     */
    public function updateVoyage($id,Request $request){
        $voyage = $this->getDoctrine()->getRepository(Voyage::class)->find($id);
        $formV=$this->createForm(VoyageType::class, $voyage);
        $formV->add('Modifier',SubmitType::class);
        $formV->handleRequest($request);
    if ($formV->isSubmitted()&&$formV->isValid()) {
    $em= $this->getDoctrine()->getManager();
    $em-> flush();
    return $this->redirectToRoute('listVoyages');
    }
    return $this->render('voyage/update.html.twig', [
    'formV' => $formV->Createview(),
    ]);
    }


    /**
    * @Route("/deleteVoyage/{id}", name="deleteVoyage")
    */
    public function deleteVoyage($id,VoyageRepository $repository)
    {
        $voyage = $this->getDoctrine()->getRepository(Voyage::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($voyage);
        $em->flush();
        return $this->redirectToRoute("listVoyages");
    }


    //******************************JSON*********************************************************************

    /**
    * @Route("/addVoyageJSON/new",name="addVoyageJSON")
    */

    public function addVoyageJSON(Request $request,NormalizerInterface $Normalizer)
    {
	    $em = $this->getDoctrine()->getManager();
        $voyage = new Voyage();
        //$voyage->setId($request->get('id'));
        $voyage->setIdU($request->get('id_u'));
        $voyage->setDateDep(new \DateTime('now'));
        $voyage->setHeureDep(new \DateTime('now'));
        $voyage->setDestination($request->get('destination'));
        $voyage->setOffre($request->get('offre'));
        $em->persist($voyage);
        $em->flush();
        $jsonContent = $Normalizer->normalize($voyage, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));;
    }

    /**
    * @Route("/listVoyagesJSON", name="listVoyagesJSON")
    */
    public function getVoyagesJSON(NormalizerInterface $Normalizer)
    {   
        //$voyage = $this->getDoctrine()
        //->getManager()->getRepository(Voyage::class)->findall();
        //$serializer = new Serializer([new ObjectNormalizer()]);
        //$formatted = $serializer->normalize($voyage);

//        return new JsonResponse($formatted);



        $repository =$this->getDoctrine()->getRepository(Voyage::class);
        $Voyages =$repository-> findAll();

        $jsonContent = $Normalizer->normalize($Voyages, 'json',['groups'=>'post:read']);
    
            //return $this-> render ('voyage/getAllVoyagesJSON.html.twig', [
            //'data' => $jsonContent]);
    
        return new Response(json_encode($jsonContent));;
    }

    /**
    * @Route("/updateVoyageJSON/{id}",name="updateVoyageJSON")
    */

    public function updateVoyageJSON(Request $request,NormalizerInterface $Normalizer,$id)
    {
	    $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository(Voyage::class)->find($id);
        //$voyage->setId($request->get('id'));
        $voyage->setIdU($request->get('id_u'));
        $voyage->setDateDep(new \DateTime('now'));
        $voyage->setHeureDep(new \DateTime('now'));
        $voyage->setDestination($request->get('destination'));
        $voyage->setOffre('Voyage');
        $em->flush();
        $jsonContent = $Normalizer->normalize($voyage, 'json',['groups'=>'post:read']);
        return new Response("updated successfully".json_encode($jsonContent));;
    }

    /**
    * @Route("/deleteVoyageJSON/{id}", name="deleteVoyageJSON")
    */
    public function deleteVoyageJSON(Request $request,NormalizerInterface $Normalizer,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository(Voyage::class)->find($id);
        $em->remove($voyage);
        $em->flush();
    
        $jsonContent = $Normalizer->normalize($voyage, 'json',['groups'=>'post:read']);
        return new Response("deleted successfully".json_encode($jsonContent));;
    }


}


