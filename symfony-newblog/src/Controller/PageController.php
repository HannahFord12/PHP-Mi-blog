<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactFormType;
use App\Entity\Contact;
use App\Entity\Image;
use App\Entity\Pais;


class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $repository = $doctrine->getRepository(Pais::class);

        $paises = $repository->findAll();

        $imagesRepository = $doctrine->getRepository(Image::class);

        $paises = $repository->findAll();
        $images = $imagesRepository->findAll();
        

        return $this->render('page/index.html.twig', 
        ['paises' => $paises,
         'images' => $images]);
    }
     /**
     * @Route("/about", name="about")
     */
    public function about():Response{
        return $this->render('page/about.html.twig', []);
    }
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(ManagerRegistry $doctrine, Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contacto = $form->getData();    
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($contacto);
            $entityManager->flush();
            return $this->redirectToRoute('index', []);
        }
        return $this->render('page/contact.html.twig', array(
            'form' => $form->createView()    
        ));
    }
}
