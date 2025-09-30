<?php

namespace App\Repository;

use App\Entity\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Status>
 */
class StatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Status::class);
    }

    /**
     * Trouve un statut par son nom
     */
    public function findByName(string $name): ?Status
    {
        return $this->findOneBy(['statusName' => $name]);
    }

    /**
     * Retourne tous les statuts ordonnés par nom
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.statusName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}