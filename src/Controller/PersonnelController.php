<?php

namespace App\Controller;
use App\Entity\Fonction;
use App\Entity\Personnel;
use App\Form\PersonnelType;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PersonnelController extends AbstractController
{
    /**
     * @Route("/personnel", name="personnel")
     */
    public function index(): Response
    {
        return $this->render('personnel/index.html.twig', [
            'controller_name' => 'PersonnelController',
        ]);
    }


    
    /**
     * @Route("/listPersonnel", name="listPersonnel")
     */
    public function listPersonnel(Request $request, PaginatorInterface $paginator)
    {
        $donnees = $this->getDoctrine()->getRepository(Personnel::class)->findAll();
        $personnels = $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('personnel/list.html.twig', ["personnels" => $personnels]);
    }
    /**
     * @Route("/detailPersonnel/{id}", name="detailPersonnel")
     */
    public function DetailPersonnel($id)
    {
        $personnel = $this->getDoctrine()->getRepository(Personnel::class)->find($id);

        return $this->render('personnel/detail.html.twig', ["personnel" => $personnel]);
    }
     
    /**
     * @Route("/listchef", name="listchef")
     */
    public function listchef()
    {
        $chefs = $this->getDoctrine()->getRepository(Personnel::class)->listchef();
        return $this->render('personnel/listchef.html.twig', ["chefs" => $chefs]);
    }
    

     /**
     * @Route("/ajouterpersonnel", name="ajouterpersonnel")
     */
    public function ajouterpersonnel(Request $request)
    {
        $personnel = new personnel();
        $form = $this->createForm(PersonnelType::class, $personnel);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();
            $fichier = $personnel->getprenom() . '.' . $photo->guessExtension();

            $photo->move(
                $this->getParameter('images_directory'),
                $fichier
            );
           $personnel->setPhoto($fichier);

            $em = $this->getDoctrine()->getManager();
            $em->persist($personnel);
            $em->flush();
            return $this->redirectToRoute('listPersonnel');
        }
        return $this->render("personnel/ajouter.html.twig", array('form' => $form->createView()));
    }

    /**
     * @Route("/supprimerPersonnel/{id}", name="supprimerPersonnel")
     */
    public function supprimerPersonnel($id)
    {
        $personnel = $this->getDoctrine()->getRepository(Personnel::class)->find($id);
    
        $em = $this->getDoctrine()->getManager();
        $em->remove($personnel);
        $em->flush();
        return $this->redirectToRoute("listPersonnel");
    } 

     /**
     * @Route("/modifierPersonnel/{id}", name="modifierPersonnel")
     */
    public function modifierPersonnel(Request $request, $id)
    {
        $personnel = $this->getDoctrine()->getRepository(Personnel::class)->find($id);
        $form = $this->createForm(PersonnelType::class, $personnel);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
         
            $photo = $form->get('photo')->getData();
            $fichier = $personnel->getprenom() . '.' . $photo->guessExtension();

            $photo->move(
                $this->getParameter('images_directory'),
                $fichier
            );
           $personnel->setPhoto($fichier);   
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listPersonnel');
        }
        return $this->render("personnel/modifier.html.twig", array('form' => $form->createView()));
    }
   /**
   * Creates a new ActionItem entity.
   *
   * @Route("/search", name="ajax_search")
   * @Method("GET")
   */
  public function searchAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $requestString = $request->get('q');
      $personnel = $em->getRepository(Personnel::class)->findEntitiesByString($requestString);
      if (!$personnel) {
          $result['personnels']['error'] = "product introuvable 🙁 ";
      } else {
          $result['personnels'] = $this->getRealEntities($personnel);
      }
      return new Response(json_encode($result));
  }
  

public function getRealEntities($personnel){

    foreach ($personnel as $personnel){
        $realEntities[$personnel->getId()] = [$personnel->getNom() ,$personnel->getPrenom(),$personnel->getPhoto()];
    }

    return $realEntities;
}

}
