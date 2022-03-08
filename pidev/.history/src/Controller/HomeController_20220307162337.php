<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user =$this->getUser();
        
        if($user->getRoles() != "ROLE_ADMIN")
        
        return $this->render('security/personalized.html.twig', [

            'users' => $users,
            'form' => $form->createView(),
        
         ]);
        elseif($user->getRoles() == "ROLE_ADMIN")
        return $this->render('user/index.html.twig', [
            'users' => $users,
       

       
        ]);
    }

    /**
     * @Route("/approved", name="approved")
     */
    public function approved(): Response
    {
        return $this->render('home/approved.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function indexadmin(): Response
    {
        return $this->render('home/indexadmin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
