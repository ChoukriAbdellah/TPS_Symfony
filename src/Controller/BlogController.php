<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
      /**
     * @Route("/post/{idPost}", name="post")
     */
    public function post(int $idPost ): Response
    {
        
        return $this->render('blog/post.html.twig', [
            'idPost' => $idPost,]
        );
    }
}
