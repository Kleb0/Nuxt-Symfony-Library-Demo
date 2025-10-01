<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Status;
use App\Entity\Cart;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setNom('Admin');
        $admin->setPrenom('Super');
        $admin->setPseudo('admin');
        $admin->setAdresse('123 Rue de l\'Administration, 75001 Paris');
        $admin->setMail('admin@library.com');
        $admin->setMotDePasse('admin'); 
        $admin->setImageProfil('images/admin-avatar.jpg');
        $admin->setTelephone('0123456789');
        
        $statusAdmin = $this->getReference('status-2', Status::class);
        $admin->addStatus($statusAdmin);
        
        $cartAdmin = $this->getReference('cart-admin', Cart::class);
        $admin->addCart($cartAdmin);
        
        $manager->persist($admin);
        $this->addReference('user-admin', $admin);
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            StatusFixtures::class,
            CartFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['User'];
    }
}