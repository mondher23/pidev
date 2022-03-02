<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OffreRepository;

class CalController extends AbstractController
{
    /**
     * @Route("/cal", name="cal")
     */
    public function index(OffreRepository $offre): Response
    {
        $events = $offre->findAll();
        foreach($events as $event){
            $rdvs[] = [
            'id' => $event->getId(),
            'title' => $event->getTitre(),
            'description' => $event->getDescription(),
            'remise' => $event->getRemise(),
            'image' => $event->getImage(),
            'start' => $event->getDebDate()->format('Y-m-d H:i:s'),
            'end' => $event->getExpDate()->format('Y-m-d H:i:s'),
            'backgroundColor' => $event->getBackgroundColor(),
            'borderColor' => $event->getBorderColor(),
            'textColor' => $event->getTextColor(),
            'expire' => $event->getExpire(),
            ];
        }

        $data = json_encode($rdvs);


        //dd($events);
        return $this->render('cal/index.html.twig', compact('data'));
    }
}
