<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $adminCart = new Cart();
        $adminCart->setTotalPrice('0.00');
        
        $manager->persist($adminCart);
        $this->addReference('cart-admin', $adminCart);
        
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['User'];
    }
}