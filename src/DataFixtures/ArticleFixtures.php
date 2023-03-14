<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture {
    public function load(ObjectManager $manager): void {
        $article = new Article();
        $article2 = new Article();
        $article3 = new Article();

        $article->setName('Article 1');
        $article->setPrice(1000);

        $manager->persist($article);


        $article2->setName('Article 2');
        $article2->setPrice(2000);

        $manager->persist($article2);

        $article3->setName('Article 3');
        $article3->setPrice(3000);

        $manager->persist($article3);

        $manager->flush();
    }
}
