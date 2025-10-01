<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Book;
use App\Entity\CartItem;
use App\Repository\UserRepository;
use App\Repository\BookRepository;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/cart')]
class CartController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private BookRepository $bookRepository,
        private CartItemRepository $cartItemRepository
    ) {}

    private function getAuthenticatedUser(Request $request): User|JsonResponse
    {
        $session = $request->getSession();
        
        if (!$session->isStarted()) {
            $session->start();
        }
        
        // Vérifier d'abord la session
        if ($session->get('user_authenticated') && $session->get('user_id')) {
            $user = $this->userRepository->find($session->get('user_id'));
            if ($user) {
                return $user;
            }
        }
        
        $authHeader = $request->headers->get('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            try {
                $tokenData = json_decode(base64_decode($token), true);
                if ($tokenData && isset($tokenData['user_id']) && isset($tokenData['timestamp'])) {
                    if (time() - $tokenData['timestamp'] < 3600) {
                        $user = $this->userRepository->find($tokenData['user_id']);
                        if ($user) {
                            return $user;
                        }
                    }
                }
            } catch (\Exception $e) {
                error_log("Token decode error: " . $e->getMessage());
            }
        }
        
        return new JsonResponse(['error' => 'Non authentifié'], Response::HTTP_UNAUTHORIZED);
    }

    #[Route('/current-user', name: 'cart_get', methods: ['GET'])]
    public function getCart(Request $request): JsonResponse
    {
        $userOrResponse = $this->getAuthenticatedUser($request);
        if ($userOrResponse instanceof JsonResponse) {
            return $userOrResponse;
        }
        $user = $userOrResponse;

        $userCarts = $user->getCarts();
        if ($userCarts->isEmpty()) {
            return new JsonResponse([
                'success' => true,
                'items' => [],
                'count' => 0,
                'total' => 0
            ]);
        }

        $activeCart = $userCarts->toArray()[0]; 
        $cartItems = $this->cartItemRepository->findBy(['cart' => $activeCart]);
        
        $items = [];
        $totalCount = 0;
        $totalPrice = 0;
        
        foreach ($cartItems as $cartItem) {
            $book = $cartItem->getBook();
            $authors = [];
            foreach ($book->getAuthors() as $author) {
                $authors[] = $author->getFullName();
            }
            
            $unitPrice = (float) $book->getPrix();
            $quantity = $cartItem->getQuantity();
            $itemTotal = $unitPrice * $quantity;
            
            $items[] = [
                'id' => $book->getId(),
                'titre' => $book->getTitre(),
                'auteur' => implode(', ', $authors),
                'image' => $book->getImage(),
                'unitPrice' => $unitPrice,
                'quantity' => $quantity,
                'total' => $itemTotal
            ];
            
            $totalCount += $quantity;
            $totalPrice += $itemTotal;
        }

        $activeCart->setTotalPrice((string) $totalPrice);
        $this->entityManager->flush();

        return new JsonResponse($items);
    }

    #[Route('/current-user/add', name: 'cart_add_item', methods: ['POST'])]
    public function addItem(Request $request): JsonResponse
    {
        $userOrResponse = $this->getAuthenticatedUser($request);
        if ($userOrResponse instanceof JsonResponse) {
            return $userOrResponse;
        }
        $user = $userOrResponse;

        $data = json_decode($request->getContent(), true);
        $bookId = $data['bookId'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        if (!$bookId) {
            return new JsonResponse(['error' => 'Book ID manquant'], Response::HTTP_BAD_REQUEST);
        }

        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            return new JsonResponse(['error' => 'Livre non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $userCarts = $user->getCarts();
        if ($userCarts->isEmpty()) {
            return new JsonResponse(['error' => 'Aucun panier trouvé'], Response::HTTP_NOT_FOUND);
        }

        $activeCart = $userCarts->toArray()[0];
        
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

        return new JsonResponse([
            'success' => true,
            'message' => 'Article ajouté au panier'
        ]);
    }

    #[Route('/current-user/remove', name: 'cart_remove_item', methods: ['POST'])]
    public function removeItem(Request $request): JsonResponse
    {
        $userOrResponse = $this->getAuthenticatedUser($request);
        if ($userOrResponse instanceof JsonResponse) {
            return $userOrResponse;
        }
        $user = $userOrResponse;

        $data = json_decode($request->getContent(), true);
        $bookId = $data['bookId'] ?? null;

        if (!$bookId) {
            return new JsonResponse(['error' => 'Book ID manquant'], Response::HTTP_BAD_REQUEST);
        }

        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            return new JsonResponse(['error' => 'Livre non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $userCarts = $user->getCarts();
        if ($userCarts->isEmpty()) {
            return new JsonResponse(['error' => 'Aucun panier trouvé'], Response::HTTP_NOT_FOUND);
        }

        $activeCart = $userCarts->toArray()[0];
        $cartItem = $this->cartItemRepository->findByCartAndBook($activeCart, $book);
        
        if ($cartItem) {
            $this->entityManager->remove($cartItem);
            $this->updateCartTotal($activeCart);
            $this->entityManager->flush();
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Article retiré du panier'
        ]);
    }

    #[Route('/current-user/update-quantity', name: 'cart_update_quantity', methods: ['POST'])]
    public function updateQuantity(Request $request): JsonResponse
    {
        $userOrResponse = $this->getAuthenticatedUser($request);
        if ($userOrResponse instanceof JsonResponse) {
            return $userOrResponse;
        }
        $user = $userOrResponse;

        $data = json_decode($request->getContent(), true);
        $bookId = $data['bookId'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        if (!$bookId) {
            return new JsonResponse(['error' => 'Book ID manquant'], Response::HTTP_BAD_REQUEST);
        }

        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            return new JsonResponse(['error' => 'Livre non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $userCarts = $user->getCarts();
        if ($userCarts->isEmpty()) {
            return new JsonResponse(['error' => 'Aucun panier trouvé'], Response::HTTP_NOT_FOUND);
        }

        $activeCart = $userCarts->toArray()[0];
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

        return new JsonResponse([
            'success' => true,
            'message' => 'Quantité mise à jour'
        ]);
    }

    private function updateCartTotal($cart): void
    {
        $cartItems = $this->cartItemRepository->findBy(['cart' => $cart]);
        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += (float) $item->getBook()->getPrix() * $item->getQuantity();
        }
        
        $cart->setTotalPrice((string) $total);
    }
}