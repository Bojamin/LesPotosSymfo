<?php

namespace App\Controller;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id}-{slug}", name="app_article_show")
     * @ParamConverter("article", options={"mapping": {"id": "id", "slug": "slug"}})
     */
    public function show(Article $article): Response
    {
        if ( !$article->getPublishedAt() || $article->getPublishedAt() > new \DateTimeImmutable() ) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}