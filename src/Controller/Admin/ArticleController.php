<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\Type\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/article/new", name="admin_article_new")
     * @Route("/admin/article/edit/{article}", name="admin_article_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, Article $article = null): Response
    {
        if ( !$article ) {
            $article = new Article();
        }

        $article->setAuthor($this->getUser());

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            if ( $article->getSlug() === null ) {
                $article->setSlug($slugger->slug($article->getTitle())->lower());
            }

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article has been saved!');

            return $this->redirectToRoute('admin_article_edit', ['article' => $article->getId()]);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article'       => $article,
            'articleForm'   => $form->createView(),
        ]);
    }
}
