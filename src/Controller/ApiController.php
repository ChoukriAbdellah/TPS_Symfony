<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
class ApiController extends AbstractController
{
    
    public function api(ArticleRepository $articleRepository ,SerializerInterface $serializer): Response
    {
        $data = $articleRepository
        ->findBy(
            array(),        // $where 
            array('dateCreation' => 'DESC'),    // $orderBy
            10,                        // $limit
            0                          // $offset
          );

        //$data = $articleRepository->findBy([], ['dateCreation'=> 'DESC']) ;
        $dataNormalized= $serializer->normalize($data, null, ['groups' => ['post:read']]);

        $json= json_encode($dataNormalized);

        $response= new Response($json, 200, [
            "Content-type" => "application/json"
        ]);
        return $response;
    }

    public function index(EntityManagerInterface $em, ArticleRepository $articleRepository, Request $request): Response
    {
     
    $donnees = $articleRepository->findBy([], ['dateCreation'=> 'DESC']) ;
    // parameters to template
    return $this->render('blog/index2.html.twig', [
        'articles' => $donnees,
    ]);
}
}
