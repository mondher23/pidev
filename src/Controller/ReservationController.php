<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;

class CultureController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    /**
     * @Route("/ajouterR", name="ajouterR")
     */
    public function addReservation(Request $request){
        $reservation = new Reservation();
        $form=$this->createForm(ReservationType::class, $reservation);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
    if ($form->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();
    $em->persist ($reservation);
    $em-> flush();
    return $this->redirectToRoute('listReservations');
    }
    return $this->render('reservation/ajouter.html.twig', [
    'form' => $form->Createview(),
    ]);
    }

    /**
    * @Route("/listReservations", name="listReservations")
    */
    public function getReservation()
    {
    $repository =$this->getDoctrine()->getRepository(Reservation::class);
    $reservations =$repository-> findAll();
    return $this-> render ('reservation/afficher.html.twig', [
    'reservations' => $reservations]);
    }

    /**
     * @Route("/modifReservation/{ref}", name="modifReservation")
     */
    public function updateReservation($ref,Request $request){
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($ref);
        $form=$this->createForm(ReservationType::class, $reservation);
        $form->add('Modifier',SubmitType::class);
        $form->handleRequest($request);
    if ($form->isSubmitted()) {
    $em= $this->getDoctrine()->getManager();
    $em-> flush();
    return $this->redirectToRoute('listReservations');
    }
    return $this->render('reservation/modifier.html.twig', [
    'form' => $form->Createview(),
    ]);
    }


    /**
    * @Route("/suppReservation/{ref}", name="suppReservation")
    */
    public function deleteReservation($ref,ReservationRepository $repository)
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($ref);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute("listReservations");
    }






}
