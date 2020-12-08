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

    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
     
    //$donnees = $articleRepository->findBy([], ['dateCreation'=> 'DESC']) ;
    $dql   = "SELECT a.id, a.titre, a.imageName , a.description, a.dateMAJ from App:Article a order by a.dateCreation desc ";
    $query = $em->createQuery($dql);

    $pagination = $paginator->paginate(
        $query, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        3 /*limit per page*/
    );
 

    $response = new Response(
        'Content',
        Response::HTTP_OK,
        ['content-type' => 'text/html']
    );
    return $response;
}
}
