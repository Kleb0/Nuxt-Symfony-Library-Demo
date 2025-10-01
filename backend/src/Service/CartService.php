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
        try {
            $userCarts = $user->getCarts();
            error_log("User ID: " . $user->getId() . " - Number of carts: " . $userCarts->count());
            
            if ($userCarts->isEmpty()) {
                $activeCart = new Cart();
                $activeCart->setTotalPrice('0.00');
                $this->entityManager->persist($activeCart);
                $user->addCart($activeCart);
                $this->entityManager->flush();
                error_log("Created new cart for user " . $user->getId());
                return $activeCart;
            }
            
            // Récupérer le dernier panier de façon plus sûre
            $cartsArray = $userCarts->toArray();
            $lastCart = end($cartsArray);
            error_log("Using existing cart ID: " . $lastCart->getCartId());
            return $lastCart;
        } catch (\Exception $e) {
            error_log("Error in getOrCreateActiveCartForUser: " . $e->getMessage());
            throw $e;
        }
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
        $userCarts = $user->getCarts();
        
        if ($userCarts->isEmpty()) {
            return ['success' => false, 'message' => 'No active cart found'];
        }
        
        $cartsArray = $userCarts->toArray();
        $activeCart = end($cartsArray);
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
        $userCarts = $user->getCarts();
        
        if ($userCarts->isEmpty()) {
            return ['success' => false, 'message' => 'No active cart found'];
        }
        
        $cartsArray = $userCarts->toArray();
        $activeCart = end($cartsArray);
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
        $userCarts = $user->getCarts();
        
        if ($userCarts->isEmpty()) {
            return [];
        }
        
        $cartsArray = $userCarts->toArray();
        $activeCart = end($cartsArray);
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