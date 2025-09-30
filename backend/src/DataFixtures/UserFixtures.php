<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Créer l'utilisateur admin demandé
        // Status ID 1 = Admin, Status ID 2 = User
        $adminSpecial = new User();
        $adminSpecial->setNom('Admin');
        $adminSpecial->setPrenom('Super');
        $adminSpecial->setPseudo('admin');
        $adminSpecial->setMail('admin@outlook.com');
        $adminSpecial->setMotDePasse('admin'); 
        $adminSpecial->setAdresse('1 Rue de l\'Administration, 75000 Paris');
        $adminSpecial->setTelephone('0100000000');
        $adminSpecial->setStatusId(1); // Admin
        $adminSpecial->setImageProfil('admin-profile.jpg');
        $manager->persist($adminSpecial);

        // Créer quelques utilisateurs de test
        $user1 = new User();
        $user1->setNom('Martin');
        $user1->setPrenom('Marie');
        $user1->setPseudo('marie');
        $user1->setMail('marie@example.com');
        $user1->setMotDePasse('marie123');
        $user1->setAdresse('123 Rue de Lyon, 69000 Lyon');
        $user1->setTelephone('0123456789');
        $user1->setStatusId(2); // User
        $user1->setImageProfil('user.jpg');
        $manager->persist($user1);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            StatusFixtures::class,
        ];
    }
}