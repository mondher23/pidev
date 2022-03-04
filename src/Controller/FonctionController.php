<?php

namespace App\Controller;


use App\Entity\Fonction;
use App\Form\FonctionType;
use App\Repository\FonctionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FonctionController extends AbstractController
{
    /**
     * @Route("/fonction", name="fonction")
     */
    public function index(): Response
    {
        return $this->render('fonction/index.html.twig', [
            'controller_name' => 'FonctionController',
        ]);
    }

     
    /**
     * @Route("/listFonction", name="listFonction")
     */
    public function listFonction(Request $request, PaginatorInterface $paginator)
    {
        $donnees = $this->getDoctrine()->getRepository(Fonction::class)->findAll();
        $fonctions = $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            2);
        return $this->render('fonction/list.html.twig', ["fonctions" => $fonctions]);
    }

     /**
     * @Route("/ajouterfonction", name="ajouterfonction")
     */
    public function ajouterfonction(Request $request)
    {
        $fonction = new fonction();
        $form = $this->createForm(FonctionType::class, $fonction);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($fonction);
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("fonction/ajouter.html.twig", array('form' => $form->createView()));
    }
   
    /**
     * @Route("/supprimerFonction/{id}", name="supprimerFonction")
     */
    public function supprimerfonction($id)
    {
        $fonction = $this->getDoctrine()->getRepository(Fonction::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($fonction);
        $em->flush();
        return $this->redirectToRoute("listFonction");
    } 

     /**
     * @Route("/modifierFonction/{id}", name="modifierFonction")
     */
    public function modifierFonction(Request $request, $id)
    {
        $fonction = $this->getDoctrine()->getRepository(Fonction::class)->find($id);
        $form = $this->createForm(FonctionType::class, $fonction);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("fonction/modifier.html.twig", array('form' => $form->createView()));
    }

    /**
    * @Route("/listFonctionJSON", name="listFonctionJSON")
    */
    public function getFonctionJSON( NormalizerInterface $Normalizer)
    {   
        $fonction = $this->getDoctrine()
        ->getManager()->getRepository(fonction::class)->findall();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $Normalizer->normalize($fonction, 'json', ['groups'=>'post:read']);

        return new JsonResponse($formatted);
    }

      /**
     * @Route("/ajouterfonctionJSON/new", name="ajouterfonctionJSON")
     */
    public function ajouterfonctionJSON(Request $request, NormalizerInterface $Normalizer)
    {
        $fonction = new fonction();
        
        
        $fonction->setNomF($request->get('nom_f'));
        $fonction->setSalaire($request->get('salaire'));
        $fonction->setNbHeure($request->get('nb_heure'));

            $em = $this->getDoctrine()->getManager();
            
            $em->persist($fonction);
            $em->flush();
            $formatted = $Normalizer->normalize($fonction, 'json', ['groups'=>'post:read']);

            return new JsonResponse(json_encode($formatted)); 
        
    }
   

}
