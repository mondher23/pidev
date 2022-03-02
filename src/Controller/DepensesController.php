<?php

namespace App\Controller;
use App\Entity\Depense;
use App\Entity\Fonction;
use App\Form\DepenseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\DepenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


class DepensesController extends AbstractController
{
    /**
     * @Route("/depenses", name="depenses")
     */
    public function index(): Response
    {
        return $this->render('depenses/index.html.twig', [
            'controller_name' => 'DepensesController',
        ]);
    }
 /**
     * @Route("/listDepense", name="listDepense")
     */
    public function listDepense()
    {
        $depenses = $this->getDoctrine()->getRepository(Depense::class)->findAll();
        return $this->render('depenses/list.html.twig', ["depenses" => $depenses]);
    }

    /**
     * @Route("/PDF", name="PDF")
     */
    public function pdf()
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $depenses = $this->getDoctrine()->getRepository(Depense::class)->findAll();
        
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('depenses/PDF.html.twig', [
            'depenses' => $depenses
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    
    }

     /**
     * @Route("/ajoutersalaire", name="ajoutersalaire")
     */
    public function ajoutersalaire(Request $request)
    {
        $depense = new depense();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->add('fonction',EntityType::class,[
            'class'=>Fonction::class,
            'choice_label'=>'salaire',
            'expanded'=>false,
            'mapped'=>false,
            'multiple'=>false
        ]);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fonction =  $form->get('fonction')->getdata();
            $depense->setMontant($fonction->getSalaire());
            $depense->setType("salaire");
            $em = $this->getDoctrine()->getManager();
            $em->persist($depense);
            $em->flush();
            return $this->redirectToRoute('listDepense');
        }
        return $this->render("depenses/ajoutersalaire.html.twig", array('form' => $form->createView()));
    }
     /**
     * @Route("/ajouterfacture", name="ajouterfacture")
     */
    public function ajouterfacture(Request $request)
    {
        $depense = new depense();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->add('montant');
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $depense->setType("facture");
            $em = $this->getDoctrine()->getManager();
            $em->persist($depense);
            $em->flush();
            return $this->redirectToRoute('listDepense');
        }
        return $this->render("depenses/ajouterfacture.html.twig", array('form' => $form->createView()));
    }

    /**
     * @Route("/supprimerDepense/{id}", name="supprimerDepense")
     */
    public function supprimerdepense($id)
    {
        $depense = $this->getDoctrine()->getRepository(Depense::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($depense);
        $em->flush();
        return $this->redirectToRoute("listDepense");
    } 

     /**
     * @Route("/modifiersalaire/{id}", name="modifiersalaire")
     */
    public function modifiersalaire(Request $request, $id)
    {
        $depense = $this->getDoctrine()->getRepository(Depense::class)->find($id);
        $form = $this->createForm(DepenseType::class, $depense);
        $form->add('fonction',EntityType::class,[
            'class'=>Fonction::class,
            'choice_label'=>'salaire',
            'expanded'=>false,
            'mapped'=>false,
            'multiple'=>false
        ]);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fonction =  $form->get('fonction')->getdata();
            $depense->setMontant($fonction->getSalaire());
            $depense->setType("salaire");
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listDepense');
        }
        return $this->render("depenses/modifiersalaire.html.twig", array('form' => $form->createView()));
    }
  
     /**
     * @Route("/modifierfacture/{id}", name="modifierfacture")
     */
    public function modifierDepense(Request $request, $id)
    {
        $depense = $this->getDoctrine()->getRepository(Depense::class)->find($id);
        $form = $this->createForm(DepenseType::class, $depense);
        $form->add('montant');
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $depense->setType("facture");
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listDepense');
        }
        return $this->render("depenses/modifierfacture.html.twig", array('form' => $form->createView()));
    }

     /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(DepenseRepository $depenseRep){
        
        $depenses = $depenseRep->findAll();

        $date = [];
        $montant = [];
        

        foreach($depenses as $depense){
            $date[] = $depense->getDate()->format('d-m-Y');
            $montant[] = $depense->getMontant();
            
        }


        return $this->render('depenses/stats.html.twig', [
            'date' => json_encode($date),
            'montant' => json_encode($montant),
        ]);
    }
}
