<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use App\Data\SearchData;
use App\Form\SearchForm;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALIZE =['id','nom','prenom','email','password','photo','cin'];

    
    /**
     * @Route("/profile", name="profile",  methods={"GET", "POST"})
     */
    public function profile(Request $request,  EntityManagerInterface $entityManager,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user=$this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$form->get('photo')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            try{
                $file->move($this->getParameter('images_directory'),$fileName);
            }catch(FileException $e){
    
            }
          
            $user->setPhoto($fileName);
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            $entityManager->flush();

        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository,Request $request, PaginatorInterface $paginator): Response
    {
             
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $users= $userRepository->findSearch($data);

        $this->denyAccessUnlessGranted('ROLE_USER');
        $user =$this->getUser(); 

        $donnees = $this->getDoctrine()->getRepository(User::class)->findBy([],['id'=>'asc']);
        $users = $paginator->paginate(
            $users,
            $request->query->getInt('page',1),
            6
        );
        if($user->getRoles() == "ROLE_ADMIN")
        return $this->render('user/index.html.twig'
        , [

            'users' => $users,
            'form' => $form->createView(),
        
         ]);
        else 
        return $this->render('security/personalized.html.twig', [
            'users' => $users,
       

       
        ]);
    }
    

    /**
     * @Route("/listu", name="user_list", methods={"GET"})
     */
    public function listu(UserRepository $userRepository ): Response
    {
             
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        $users = $userRepository->findAll();
        

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('user/listu.html.twig', [
            'users' => $users,
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false 
        ]);
    }


       
    
    

    /**
     * @Route("/new", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager ,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
    
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/approve", name="user_approve", methods={"GET", "POST"})
     */
    public function Approve(Request $request, User $user, EntityManagerInterface $entityManager,UserRepository $userRepository): Response
    {
       
        $user->setIsVerified(true);

            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        
        return $this->render('user/index.html.twig', [
     
            'users' => $userRepository->findAll(),
      
        ]);
    }

    /**
     * @Route("/ajouter/utilisateur" , name="utilisateur_ajouter" ,  methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function ajouter(Request $request,SerializerInterface $serializer)
    {
      
        $user = new User();
        $nom=$request->query->get('nom');
        $prenom=$request->query->get('prenom');
        $password=$request->query->get('password');
        $photo=$request->query->get('photo');
        $email=$request->query->get('email');
        $cin=$request->query->get('cin');
        $em=$this->getDoctrine()->getManager();
        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setCin($cin);
        $user->setEmail($email);
        $user->setIsVerified(true);
        $user->setPassword($password);
        $user->setPhoto($photo);
        $em->persist($user);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($user);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/utilisateur/list")
     * @param UserRepository $repo
     */
    public function getList(UserRepository $repo,SerializerInterface $serializer):Response{
     
                $users=$repo->findAll();
                $json=$serializer->serialize($users,'json', ['groups' => ['user']]);
        
        
                return $this->json(['user'=>$users],Response::HTTP_OK,[],[
                    'attributes'=>self::ATTRIBUTES_TO_SERIALIZE
                ]);
        
            }
        

   
}
