<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
