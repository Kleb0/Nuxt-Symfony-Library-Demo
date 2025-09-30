<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statuses = [
            [
                'statusState' => 1,
                'statusName' => 'User',
            ],
            [
                'statusState' => 2,
                'statusName' => 'Admin',
            ],
            [
                'statusState' => 3,
                'statusName' => 'Moderator',
            ],
        ];

        foreach ($statuses as $index => $data) {
            $status = new Status();
            $status->setStatusState($data['statusState']);
            $status->setStatusName($data['statusName']);
            
            $manager->persist($status);
            $this->addReference('status-' . $data['statusState'], $status);
        }
        
        $manager->flush();
    }
}