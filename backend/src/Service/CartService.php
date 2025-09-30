<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Book;
use App\Entity\CartItem;
use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CartRepository $cartRepository,
        private CartItemRepository $cartItemRepository
    ) {}

    public function getOrCreateActiveCartForUser(User $user): Cart
    {
        $activeCart = $user->getActiveCart();
        
        if (!$activeCart) {
            $activeCart = new Cart();
            $activeCart->setTotalPrice('0.00');
            $this->entityManager->persist($activeCart);
            $user->addCart($activeCart);
            $this->entityManager->flush();
        }
        
        return $activeCart;
    }

    public function addItemToCart(User $user, Book $book, int $quantity = 1): array
    {
        $activeCart = $this->getOrCreateActiveCartForUser($user);
        
        $existingCartItem = $this->cartItemRepository->findByCartAndBook($activeCart, $book);
        
        if ($existingCartItem) {
            $existingCartItem->setQuantity($existingCartItem->getQuantity() + $quantity);
        } else {
            $cartItem = new CartItem();
            $cartItem->setCart($activeCart);
            $cartItem->setBook($book);
            $cartItem->setQuantity($quantity);
            $this->entityManager->persist($cartItem);
        }
        
        $this->updateCartTotal($activeCart);
        $this->entityManager->flush();
        
        return [
            'success' => true,
            'cart_id' => $activeCart->getCartId(),
            'total' => $activeCart->getTotalPrice()
        ];
    }

    public function removeItemFromCart(User $user, Book $book): array
    {
        $activeCart = $user->getActiveCart();
        
        if (!$activeCart) {
            return ['success' => false, 'message' => 'No active cart found'];
        }
        
        $cartItem = $this->cartItemRepository->findByCartAndBook($activeCart, $book);
        
        if ($cartItem) {
            $this->entityManager->remove($cartItem);
            $this->updateCartTotal($activeCart);
            $this->entityManager->flush();
        }
        
        return [
            'success' => true,
            'total' => $activeCart->getTotalPrice()
        ];
    }

    public function updateItemQuantity(User $user, Book $book, int $quantity): array
    {
        $activeCart = $user->getActiveCart();
        
        if (!$activeCart) {
            return ['success' => false, 'message' => 'No active cart found'];
        }
        
        $cartItem = $this->cartItemRepository->findByCartAndBook($activeCart, $book);
        
        if ($cartItem) {
            if ($quantity <= 0) {
                $this->entityManager->remove($cartItem);
            } else {
                $cartItem->setQuantity($quantity);
            }
            
            $this->updateCartTotal($activeCart);
            $this->entityManager->flush();
        }
        
        return [
            'success' => true,
            'total' => $activeCart->getTotalPrice()
        ];
    }

    public function getCartItems(User $user): array
    {
        $activeCart = $user->getActiveCart();
        
        if (!$activeCart) {
            return [];
        }
        
        $cartItems = $this->cartItemRepository->findBy(['cart' => $activeCart]);
        $items = [];
        
        foreach ($cartItems as $cartItem) {
            $book = $cartItem->getBook();
            $authors = [];
            foreach ($book->getAuthors() as $author) {
                $authors[] = $author->getFullName();
            }
            
            $items[] = [
                'id' => $book->getId(),
                'titre' => $book->getTitre(),
                'auteur' => implode(', ', $authors),
                'image' => $book->getImage(),
                'unitPrice' => (float) $book->getPrix(),
                'quantity' => $cartItem->getQuantity(),
                'total' => (float) $book->getPrix() * $cartItem->getQuantity()
            ];
        }
        
        return $items;
    }

    private function updateCartTotal(Cart $cart): void
    {
        $cartItems = $this->cartItemRepository->findBy(['cart' => $cart]);
        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += (float) $item->getBook()->getPrix() * $item->getQuantity();
        }
        
        $cart->setTotalPrice((string) $total);
    }
}