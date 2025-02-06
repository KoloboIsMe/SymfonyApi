<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Citation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++) {
            $author = new Author();
            $author->setName("Author$i");
            $manager->persist($author);
            for($j = 1; $j <= 5; $j++) {
                $book = new Book();
                $book->setTitle("Book$j");
                $book->addAuthor($author);
                $manager->persist($book);
                for($k = 1; $k <= 2; $k++) {
                    $citation = new Citation();
                    $citation->setText("Citation$k");
                    $citation->setAuthor($author);
                    $citation->setBook($book);
                    $manager->persist($citation);
                }
            }
        }
        $manager->flush();
    }
}