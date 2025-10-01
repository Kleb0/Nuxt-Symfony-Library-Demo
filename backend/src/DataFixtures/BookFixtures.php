<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $booksData = [
            [
                'titre' => 'One Piece - Tome 1',
                'description' => 'Romance Dawn - Le début de l\'aventure de Monkey D. Luffy',
                'prix' => '7.95',
                'image' => 'images/One_Piece_1.jpg',
                'stock' => 25,
                'isbn' => '978-2-7234-4567-8',
                'datePublication' => '1997-12-24',
                'nombrePages' => 192,
                'authors' => [0],
                'categories' => [0, 1, 2]
            ],
            [
                'titre' => 'One Piece - Tome 2',
                'description' => 'Buggy le Clown - La suite des aventures dans East Blue',
                'prix' => '7.95',
                'image' => 'images/One_Piece_2.jpg',
                'stock' => 20,
                'isbn' => '978-2-7234-4568-9',
                'datePublication' => '1998-04-03',
                'nombrePages' => 192,
                'authors' => [0],
                'categories' => [0, 1, 2]
            ],
            [
                'titre' => 'Naruto - Tome 1',
                'description' => 'Uzumaki Naruto - L\'histoire du ninja le plus imprévisible',
                'prix' => '7.50',
                'image' => 'images/Naruto_1.jpg',
                'stock' => 30,
                'isbn' => '978-2-8050-0234-5',
                'datePublication' => '1999-09-21',
                'nombrePages' => 192,
                'authors' => [1],
                'categories' => [0, 1, 3]
            ],
            [
                'titre' => 'My Hero Academia - Tome 1',
                'description' => 'Izuku Midoriya - Dans un monde où 80% de la population a des super-pouvoirs',
                'prix' => '6.90',
                'image' => 'images/My_Hero_Academia_.jpg',
                'stock' => 35,
                'isbn' => '978-2-5050-1234-6',
                'datePublication' => '2014-07-07',
                'nombrePages' => 192,
                'authors' => [2],
                'categories' => [0, 1, 3]
            ],
            [
                'titre' => 'Vinland Saga - Tome 1',
                'description' => 'L\'époque des Vikings - L\'histoire de Thorfinn et de sa quête de vengeance',
                'prix' => '8.95',
                'image' => 'images/Vinland_Saga_1.jpg',
                'stock' => 15,
                'isbn' => '978-2-7560-1234-7',
                'datePublication' => '2005-04-13',
                'nombrePages' => 216,
                'authors' => [3],
                'categories' => [4, 1, 5]
            ],
            [
                'titre' => 'Vinland Saga - Tome 2',
                'description' => 'La Bande d\'Askeladd - Thorfinn continue son parcours avec les Vikings',
                'prix' => '8.95',
                'image' => 'images/Vinland_Saga_2.jpg',
                'stock' => 12,
                'isbn' => '978-2-7560-1235-8',
                'datePublication' => '2005-09-22',
                'nombrePages' => 216,
                'authors' => [3],
                'categories' => [4, 1, 5]
            ],
            [
                'titre' => 'Vinland Saga - Tome 3',
                'description' => 'La Guerre du Danemark - Les conflits s\'intensifient',
                'prix' => '8.95',
                'image' => 'images/Vinland_Saga_3.jpg',
                'stock' => 18,
                'isbn' => '978-2-7560-1236-9',
                'datePublication' => '2006-02-23',
                'nombrePages' => 216,
                'authors' => [3],
                'categories' => [4, 1, 5]
            ],
            [
                'titre' => 'That Time I Got Reincarnated as a Slime - Tome 1',
                'description' => 'Rimuru Tempest - Un employé de bureau réincarné en slime dans un autre monde',
                'prix' => '7.20',
                'image' => 'images/Me_Slime_1_.jpg',
                'stock' => 22,
                'isbn' => '978-2-8095-1234-0',
                'datePublication' => '2015-10-30',
                'nombrePages' => 180,
                'authors' => [4, 5],
                'categories' => [6, 3, 7]
            ],
            [
                'titre' => 'That Time I Got Reincarnated as a Slime - Tome 2',
                'description' => 'La Forêt de Jura - Rimuru développe sa nouvelle communauté',
                'prix' => '7.20',
                'image' => 'images/Me_Slime_2_.jpg',
                'stock' => 20,
                'isbn' => '978-2-8095-1235-1',
                'datePublication' => '2016-02-27',
                'nombrePages' => 180,
                'authors' => [4, 5],
                'categories' => [6, 3, 7]
            ]
        ];

        foreach ($booksData as $bookData) {
            $book = new Book();
            $book->setTitre($bookData['titre']);
            $book->setDescription($bookData['description']);
            $book->setPrix($bookData['prix']);
            $book->setImage($bookData['image']);
            $book->setStock($bookData['stock']);
            $book->setIsbn($bookData['isbn']);
            $book->setDatePublication(new \DateTime($bookData['datePublication']));
            $book->setNombrePages($bookData['nombrePages']);

            foreach ($bookData['authors'] as $authorIndex) {
                $book->addAuthor($this->getReference('author-' . $authorIndex, Author::class));
            }

            foreach ($bookData['categories'] as $categoryIndex) {
                $book->addCategory($this->getReference('category-' . $categoryIndex, Category::class));
            }

            $manager->persist($book);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class,
            CategoryFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['Books'];
    }
}