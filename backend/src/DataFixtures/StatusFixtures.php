<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $adminStatus = new Status();
        $adminStatus->setStatusId(1);
        $adminStatus->setStatusName('Admin');
        $manager->persist($adminStatus);

        $userStatus = new Status();
        $userStatus->setStatusId(2);
        $userStatus->setStatusName('User');
        $manager->persist($userStatus);

        $manager->flush();
    }
}