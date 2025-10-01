<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $authorsData = [
            [
                'firstName' => 'Eiichiro',
                'lastName' => 'Oda',
                'nationality' => 'Japonaise',
                'birthDate' => '1975-01-01',
                'biography' => 'Mangaka japonais créateur de One Piece'
            ],
            [
                'firstName' => 'Masashi',
                'lastName' => 'Kishimoto', 
                'nationality' => 'Japonaise',
                'birthDate' => '1974-11-08',
                'biography' => 'Mangaka japonais créateur de Naruto'
            ],
            [
                'firstName' => 'Kohei',
                'lastName' => 'Horikoshi',
                'nationality' => 'Japonaise', 
                'birthDate' => '1986-11-20',
                'biography' => 'Mangaka japonais créateur de My Hero Academia'
            ],
            [
                'firstName' => 'Makoto',
                'lastName' => 'Yukimura',
                'nationality' => 'Japonaise',
                'birthDate' => '1976-05-08', 
                'biography' => 'Mangaka japonais créateur de Vinland Saga'
            ],
            [
                'firstName' => 'Fuse',
                'lastName' => '',
                'nationality' => 'Japonaise',
                'birthDate' => '1980-01-01',
                'biography' => 'Auteur japonais de light novels'
            ],
            [
                'firstName' => 'Taiki',
                'lastName' => 'Kawakami',
                'nationality' => 'Japonaise', 
                'birthDate' => '1985-01-01',
                'biography' => 'Mangaka japonais adaptateur de That Time I Got Reincarnated as a Slime'
            ]
        ];

        foreach ($authorsData as $index => $authorData) {
            $author = new Author();
            $author->setFirstName($authorData['firstName']);
            $author->setLastName($authorData['lastName']);
            $author->setNationality($authorData['nationality']);
            $author->setBirthDate(new \DateTime($authorData['birthDate']));
            $author->setBiography($authorData['biography']);

            $manager->persist($author);
            $this->addReference('author-' . $index, $author);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['Books'];
    }
}