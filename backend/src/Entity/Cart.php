<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ORM\Table(name: 'carts')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Put(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['cart:read']],
    denormalizationContext: ['groups' => ['cart:write']]
)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['cart:read'])]
    private ?int $cart_id = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank(message: 'Le prix total ne peut pas être vide')]
    #[Assert\PositiveOrZero(message: 'Le prix total doit être positif ou nul')]
    #[Groups(['cart:read', 'cart:write'])]
    private ?string $totalPrice = null;

    #[ORM\ManyToMany(targetEntity: Book::class)]
    #[ORM\JoinTable(
        name: 'cart_items',
        joinColumns: [new ORM\JoinColumn(name: 'cart_id', referencedColumnName: 'cart_id')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'book_id', referencedColumnName: 'id')]
    )]
    #[Groups(['cart:read', 'cart:write'])]
    private Collection $items;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['cart:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['cart:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getCartId(): ?int
    {
        return $this->cart_id;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Book $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function removeItem(Book $item): static
    {
        $this->items->removeElement($item);
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}