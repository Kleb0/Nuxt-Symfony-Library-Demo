<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ORM\Table(name: 'status')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    normalizationContext: ['groups' => ['status:read']]
)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['status:read', 'user:read'])]
    private ?int $statusId = null;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    #[Groups(['status:read', 'user:read'])]
    private ?string $statusName = null;

    #[ORM\Column(type: 'integer', unique: true)]
    #[Groups(['status:read', 'user:read'])]
    private ?int $statusState = null;

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    public function setStatusId(int $statusId): static
    {
        $this->statusId = $statusId;

        return $this;
    }

    public function getStatusName(): ?string
    {
        return $this->statusName;
    }

    public function setStatusName(string $statusName): static
    {
        $this->statusName = $statusName;

        return $this;
    }

    public function getStatusState(): ?int
    {
        return $this->statusState;
    }

    public function setStatusState(int $statusState): static
    {
        $this->statusState = $statusState;

        return $this;
    }

    public function __toString(): string
    {
        return $this->statusName ?? '';
    }
}