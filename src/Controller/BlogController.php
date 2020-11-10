<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Form\ArticleType;


class BlogController extends AbstractController
{
    
    public function index(ArticleRepository $articleRepository): Response
    {
     
     $articles = $articleRepository->findBy([], ['dateCreation'=> 'DESC']) ;

    // dd($articles);
        return $this->render('blog/index.html.twig', 
        compact('articles')            
        );
    }
    
    public function post(int $idPost ,ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($idPost) ;
        return $this->render('blog/post.html.twig', 
        compact('idPost', 'article')
        );
    }
    public function newPost(Request $request, EntityManagerInterface $em ): Response
    {

        $form= $this->createForm(ArticleType::Class);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
        $data = $form->getData();
        $article =new Article;
        $article->setTitre($data->getTitre());
        $article->setDescription($data->getDescription());
        $em->persist($article);
        $em->flush();
        $this->addFlash('sucess', 'Article créé avec succès');
        return $this->redirectToRoute('index');
        }

        return $this->render('blog/newPost.html.twig', 
        ['form' => $form->createView()]
        );
    }

    public function editPost(int $idPost,Request $request, EntityManagerInterface $em,ArticleRepository $articleRepository ): Response
    {
        $article = $articleRepository->find($idPost) ;

        $form= $this->createForm(ArticleType::Class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
    
            $em->flush();
            $this->addFlash('sucess', 'Article modifié avec succès');
            return $this->redirectToRoute('index');
        }
        return $this->render('blog/editPost.html.twig', 
        ['form' => $form->createView(),
          'article' => $article,
          
        ]
        );
    }
    
    
    public function deletePost(int $idPost,Request $request, EntityManagerInterface $em,ArticleRepository $articleRepository ): Response
    {
        $article = $articleRepository->find($idPost) ;

        $em->remove($article);
        $em->flush();
        $this->addFlash('sucess', 'Article supprimé avec succès');

        return $this->redirectToRoute('index');
    }


}
