<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Summary of ArticlesController
 */
class ArticlesController extends AbstractController {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route('/articles', methods: ["GET"], name: 'app_articles')]
    public function index(): Response {
        $repo = $this->em->getRepository(Article::class);
        $articles = $repo->findAll();
        // dd($articles);
        return $this->render('articles/index.html.twig', [
            "articles" => $articles
        ]);
    }

    #[Route('/articles/details/{id}', methods: ["GET"], name: 'article_details')]
    public function show_details($id): Response {
        $repo = $this->em->getRepository(Article::class);

        $article = $repo->find($id);
        // dd($article);
        
        return $this->render('articles/show.html.twig', [
            "article" => $article
        ]);
    }

    #[Route('/articles/create', name: 'new_article')]
    public function new(Request $req): Response {
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($req);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $newArticle = $form->getData();

            $this->em->getRepository(Article::class)->save($newArticle, true);

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/articles/edit/{id}', name: 'edit_article')]
    public function edit($id, Request $req) : Response {
        $article = $this->em->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setName($form->get('name')->getData());
            $article->setPrice($form->get('price')->getData());

            $this->em->flush();

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
            // 'article' => $article
        ]);
    }

    #[Route('/articles/delete/{id}', name: 'delete_article')]
    public function delete($id): Response {
        $repo = $this->em->getRepository(Article::class);
        
        $article = $repo->find($id);

        $repo->remove($article, true);

        return $this->redirectToRoute('app_articles');

    }
}
