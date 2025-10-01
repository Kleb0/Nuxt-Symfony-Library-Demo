<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            [
                'name' => 'Shonen',
                'description' => 'Manga destiné principalement aux jeunes hommes'
            ],
            [
                'name' => 'Action',
                'description' => 'Manga avec des scènes de combat et d\'action'
            ],
            [
                'name' => 'Aventure',
                'description' => 'Manga mettant l\'accent sur les voyages et découvertes'
            ],
            [
                'name' => 'Fantastique',
                'description' => 'Manga avec des éléments surnaturels et magiques'
            ],
            [
                'name' => 'Seinen',
                'description' => 'Manga destiné à un public adulte masculin'
            ],
            [
                'name' => 'Historique',
                'description' => 'Manga se déroulant dans un contexte historique'
            ],
            [
                'name' => 'Isekai',
                'description' => 'Manga où le protagoniste est transporté dans un autre monde'
            ],
            [
                'name' => 'Comédie',
                'description' => 'Manga avec des éléments humoristiques'
            ]
        ];

        foreach ($categoriesData as $index => $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setDescription($categoryData['description']);

            $manager->persist($category);
            $this->addReference('category-' . $index, $category);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['Books'];
    }
}