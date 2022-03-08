<?php

namespace App\Controller;

use App\Entity\Cartefidelite;
use App\Form\CartefideliteType;
use App\Repository\CartefideliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cartefidelite")
 */
class CartefideliteController extends AbstractController
{
    /**
     * @Route("/", name="cartefidelite_index", methods={"GET"})
     */
    public function index(CartefideliteRepository $cartefideliteRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user =$this->getUser();

        
        $donnees = $this->getDoctrine()->getRepository(Cartefidelite::class)->findBy([],['id'=>'asc']);
        $cartefidelite = $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            4
        );
      //  dd($user->getRoles());
        // if($user->getRoles() != "ROLE_USER")
        
         return $this->render('cartefidelite/index.html.twig', [
         
           'cartefidelites' => $cartefidelite,
         ]);
        //else 
        //return $this->render('security/personalized.html.twig', [
        //    'cartefidelites' => $cartefidelite, 
        //]);
    }


     /**
     * @Route("/stats", name="stats_index" , methods={"GET", "POST"})
     */
    public function statistiques(CartefideliteRepository $cartefideliteRepository)
    {
        
        $cartes = $cartefideliteRepository->findAll();

        $id = [];
        $nbpts = [];
        

        foreach($cartes as $carte){
            $id[] = $cartefidelite->getId();
            $nbpts[] = $cartefidelite->getNbpts() ;
            
        }


        return $this->render('cartefidelite/stats.html.twig', [
            'id' => json_encode($id),
            'nbpts' => json_encode($nbpts),
        ]);
    }


    /**
     * @Route("/listcarte", name="cartefidelite_list", methods={"GET"})
     */
    public function listc(CartefideliteRepository $cartefideliteRepository): Response
    {

        $cartefidelites =  $cartefideliteRepository->findAll();

        return $this->render('cartefidelite/listc.html.twig', [
            'cartefidelite' => $cartefidelite,
            
        ]);
        
    }


    /**
     * @Route("/new", name="cartefidelite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $cartefidelite = new Cartefidelite();
        $form = $this->createForm(CartefideliteType::class, $cartefidelite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
       
            $entityManager->persist($cartefidelite);
            $entityManager->flush();

            return $this->redirectToRoute('cartefidelite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cartefidelite/new.html.twig', [
            'cartefidelite' => $cartefidelite,
            'form' => $form->createView(),
        ]);
    }

    

    /**
     * @Route("/{id}", name="cartefidelite_show", methods={"GET"})
     */
    public function show(Cartefidelite $cartefidelite): Response
    {
        return $this->render('cartefidelite/show.html.twig', [
            'cartefidelite' => $cartefidelite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cartefidelite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Cartefidelite $cartefidelite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CartefideliteType::class, $cartefidelite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('cartefidelite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cartefidelite/edit.html.twig', [
            'cartefidelite' => $cartefidelite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cartefidelite_delete", methods={"POST"})
     */
    public function delete(Request $request, Cartefidelite $cartefidelite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cartefidelite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cartefidelite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cartefidelite_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
