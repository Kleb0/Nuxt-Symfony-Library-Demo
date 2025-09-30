<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\CartHistoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CartHistoryRepository::class)]
#[ORM\Table(name: 'cart_histories')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Put(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['cart_history:read']],
    denormalizationContext: ['groups' => ['cart_history:write']]
)]
class CartHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['cart_history:read'])]
    private ?int $cart_history_id = null;

    #[ORM\ManyToMany(targetEntity: Cart::class)]
    #[ORM\JoinTable(
        name: 'cart_history_carts',
        joinColumns: [new ORM\JoinColumn(name: 'cart_history_id', referencedColumnName: 'cart_history_id')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'cart_id', referencedColumnName: 'cart_id')]
    )]
    #[Groups(['cart_history:read', 'cart_history:write'])]
    private Collection $list_cart;

    #[ORM\Column(type: 'json')]
    #[Groups(['cart_history:read', 'cart_history:write'])]
    private array $list_cart_id = [];

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['cart_history:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['cart_history:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->list_cart = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getCartHistoryId(): ?int
    {
        return $this->cart_history_id;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getListCart(): Collection
    {
        return $this->list_cart;
    }

    public function addListCart(Cart $listCart): static
    {
        if (!$this->list_cart->contains($listCart)) {
            $this->list_cart->add($listCart);
            $this->updateCartIdList();
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function removeListCart(Cart $listCart): static
    {
        $this->list_cart->removeElement($listCart);
        $this->updateCartIdList();
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getListCartId(): array
    {
        return $this->list_cart_id;
    }

    public function setListCartId(array $list_cart_id): static
    {
        $this->list_cart_id = $list_cart_id;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    private function updateCartIdList(): void
    {
        $this->list_cart_id = [];
        foreach ($this->list_cart as $cart) {
            $this->list_cart_id[] = $cart->getCartId();
        }
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