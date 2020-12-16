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
use App\Entity\Image;
use App\Form\ArticleType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $article = new Article();
            $form= $this->createForm(ArticleType::Class);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
        $data = $form->getData();
        
        $article =new Article;
        $article->setTitre($data->getTitre());
        $article->setDescription($data->getDescription());
        $article->setUser($this->getUser());
        $article->setImageFile($data->getImageFile());
        $article->setDateMaj(new \DateTimeImmutable );
        //
        $images = $form->get('images')->getData();

        // On boucle sur les images
        foreach($images as $image){
            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );

            // On stocke l'image dans la base de données (son nom)
            $img = new Image();
            $img->setName($fichier);
            $article->addImage($img);
        }
        //

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
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On stocke l'image dans la base de données (son nom)
                $img = new Image();
                $img->setName($fichier);
                $article->addImage($img);
            }
            
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

  
    public function deleteImage(Image $image, Request $request){
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }


}
