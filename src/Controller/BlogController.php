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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
class BlogController extends AbstractController
{
    
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
     
    //$donnees = $articleRepository->findBy([], ['dateCreation'=> 'DESC']) ;

    $dql   = "SELECT a.id, a.titre, a.imageName , a.description, a.dateMAJ from App:Article a order by a.dateCreation desc ";
    $query = $em->createQuery($dql);

    $pagination = $paginator->paginate(
        $query, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        6 /*limit per page*/
    );
    $pagination->setCustomParameters([
        'align' => 'center',
        'size' => 'medium',
        'rounded' => true,
        
    ]);
 
     // parameters to template
     return $this->render('blog/index.html.twig', [
        'pagination' => $pagination,
    ]);


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

        if ($this->getUser()) {
            $form= $this->createForm(ArticleType::Class);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
        $data = $form->getData();
        
        $article =new Article;
        $article->setTitre($data->getTitre());
        $article->setDescription($data->getDescription());
        $article->setUser($this->getUser());
        $article->setImageFile($data->getImageFile());
        $article->setDataMaj(new \DateTimeImmutable );
        $em->persist($article);
        $em->flush();
        $this->addFlash('success', 'Article créé avec succès');
        return $this->redirectToRoute('index');
        }
        return $this->render('blog/newPost.html.twig', 
        ['form' => $form->createView()]
        );
            
        }
        else {
            $this->addFlash('danger', 'Vous devez vous connecter pour ajouter un article.');
            return $this->redirectToRoute('app_register');
        }
        
    }

    public function editPost(int $idPost,Request $request, EntityManagerInterface $em,ArticleRepository $articleRepository ): Response
    {
        $article = $articleRepository->find($idPost) ;

        $form= $this->createForm(ArticleType::Class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $article->setTitre($data->getTitre());
            $article->setDescription($data->getDescription());
            $em->flush();
            $this->addFlash('success', 'Article modifié avec succès');
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
        $this->addFlash('success', 'Article supprimé avec succès');

        return $this->redirectToRoute('index');
    }


}
