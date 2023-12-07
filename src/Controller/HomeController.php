<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository(Article::class)->findActiveArticles();

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }

     /**
     * @Route("/search", name="search_request")
     */
    public function searchRequest(EntityManagerInterface $em, Request $request): Response
    {
        $articles = $em->getRepository(Article::class)->findArticles($request -> get("search"));

        return $this->render('article/searchRequest.html.twig', [
            'articles' => $articles,
        ]);
    }
}
