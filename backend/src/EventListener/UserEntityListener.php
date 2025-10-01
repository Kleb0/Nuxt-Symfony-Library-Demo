<?php

namespace App\EventListener;

use App\Entity\User;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;

class UserEntityListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function prePersist($entityOrArgs): void
    {
        if ($entityOrArgs instanceof PrePersistEventArgs) {
            $entity = $entityOrArgs->getObject();
        } else {
            $entity = $entityOrArgs;
        }

        if (!$entity instanceof User) {
            return;
        }

        if ($entity->getStatuses()->isEmpty()) {
            $statusRepository = $this->entityManager->getRepository(Status::class);
            
            if ($entity->getPseudo() === 'admin') {
                $adminStatus = $statusRepository->findOneBy(['statusState' => 2]);
                if ($adminStatus) {
                    $entity->addStatus($adminStatus);
                }
            } else {
                $requestedStatusState = $entity->getStatusState();
                
                if ($requestedStatusState !== null) {
                    $requestedStatus = $statusRepository->findOneBy(['statusState' => $requestedStatusState]);
                    if ($requestedStatus) {
                        $entity->addStatus($requestedStatus);
                    }
                } else {
                    $defaultStatus = $statusRepository->findOneBy(['statusState' => 1]);
                    if ($defaultStatus) {
                        $entity->addStatus($defaultStatus);
                    }
                }
            }
        }
    }
}