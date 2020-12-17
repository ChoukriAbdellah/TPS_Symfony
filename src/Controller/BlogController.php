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
use App\Entity\Commentaire;
use App\Form\ArticleType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\CommentaireFormType;
class BlogController extends AbstractController
{
    
    public function index(Security $security,EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
     //check if user is connect
     $user = $security->getUser();
     if($user){
         //current user
        $userId= $user->getID();
     }
     else
     {
        $userId= -1;
     }
    
    
    $dql   = "SELECT  a.id, a.titre, a.imageName , a.description,a.imageName, a.dateMAJ , IDENTITY(a.user) as user from App:Article a order by a.dateCreation desc ";
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
        'userId' => $userId
    ]);


     }

    
    public function post(Security $security,int $idPost ,ArticleRepository $articleRepository, Request $request): Response
    {
         //check if user is connect
     $user = $security->getUser();
     if($user){
         //current user
        $userId= $user->getID();
     }
     else
     {
        $userId= -1;
     }
    

        $article = $articleRepository->find($idPost) ;

        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findBy([
            'article' => $article,
            'actif' => 1
        ],['created_at' => 'desc']);

        // Nous créons l'instance de "Commentaires"
        $commentaire = new Commentaire();

        // Nous créons le formulaire en utilisant "CommentairesType" et on lui passe l'instance
        $form = $this->createForm(CommentaireFormType::class, $commentaire);
        

        // Nous récupérons les données
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (! $this->getUser()) {
                $this->addFlash('danger', 'Vous devez vous connecter pour ajouter un commentaire.');
                return $this->redirectToRoute('app_register');
            }   
        // Hydrate notre commentaire avec l'article
        $commentaire->setArticle($article);
        $commentaire->setActif(true);

        // Hydrate notre commentaire avec la date et l'heure courants
        $commentaire->setCreatedAt(new \DateTime('now'));

        $doctrine = $this->getDoctrine()->getManager();

        // On hydrate notre instance $commentaire
        $doctrine->persist($commentaire);

        // On écrit en base de données
        $doctrine->flush();

        // On redirige l'utilisateur
        return $this->redirectToRoute('post', ['idPost' => $idPost]);
        
    }
    $form=$form->createView();


        return $this->render('blog/post.html.twig', 
        compact('idPost', 'article', 'userId', 'commentaires', 'form')
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
        // 
        
        // On boucle sur les images
        foreach($images as $image){
            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            //L'affiche de l'article sera la dernière images chargé
            $article->setImageName($fichier);
            //
            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );

            // On stocke l'image dans la base de données (son nom)
            $img = new Image();
            $img->setName($fichier);
            $article->addImage($img);
            $HasImage= true;
        }
        //Si le fichier possède une image
        

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
          'article' => $article
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
