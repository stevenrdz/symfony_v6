<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        $comments = $entityManager->getRepository(Comment::class)->findBy([],[
            'id' => 'DESC'
        ]);
        # return new Response('Pagina de inicio - HomeController');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController', 'comments' => $comments,
            'form' => $form->createView()
        ]);
        #'comments' => $entityManager->getRepository(Comment::class)->findAll()
    }
}
