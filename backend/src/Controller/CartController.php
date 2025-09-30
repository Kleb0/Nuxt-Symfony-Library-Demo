<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Book;
use App\Service\CartService;
use App\Repository\CartRepository;
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
        private CartRepository $cartRepository,
        private CartService $cartService
    ) {}

    #[Route('', name: 'cart_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $carts = $this->cartRepository->findAll();
        
        return $this->json($carts, Response::HTTP_OK, [], ['groups' => 'cart:read']);
    }

    #[Route('/{id}', name: 'cart_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $cart = $this->cartRepository->find($id);

        if (!$cart) {
            return $this->json(['message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($cart, Response::HTTP_OK, [], ['groups' => 'cart:read']);
    }

    #[Route('', name: 'cart_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $cart = new Cart();
        $cart->setTotalPrice($data['totalPrice'] ?? '0.00');

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $this->json($cart, Response::HTTP_CREATED, [], ['groups' => 'cart:read']);
    }

    #[Route('/{id}', name: 'cart_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $cart = $this->cartRepository->find($id);

        if (!$cart) {
            return $this->json(['message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['totalPrice'])) {
            $cart->setTotalPrice($data['totalPrice']);
        }

        $this->entityManager->flush();

        return $this->json($cart, Response::HTTP_OK, [], ['groups' => 'cart:read']);
    }

    #[Route('/{id}', name: 'cart_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $cart = $this->cartRepository->find($id);

        if (!$cart) {
            return $this->json(['message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($cart);
        $this->entityManager->flush();

        return $this->json(['message' => 'Cart deleted'], Response::HTTP_OK);
    }

    #[Route('/user/{userId}', name: 'cart_user_get', methods: ['GET'])]
    public function getUserCart(int $userId): JsonResponse
    {
        try {
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->find($userId);

            if (!$user) {
                return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $items = $this->cartService->getCartItems($user);

            return $this->json($items, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/user/{userId}/test', name: 'cart_user_test', methods: ['GET'])]
    public function testUser(int $userId): JsonResponse
    {
        try {
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->find($userId);

            if (!$user) {
                return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            return $this->json([
                'user_id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'carts_count' => $user->getCarts()->count()
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/user/{userId}/add', name: 'cart_user_add_item', methods: ['POST'])]
    public function addItemToUserCart(int $userId, Request $request): JsonResponse
    {
        try {
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->find($userId);

            if (!$user) {
                return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            $bookId = $data['bookId'] ?? null;
            $quantity = $data['quantity'] ?? 1;

            if (!$bookId) {
                return $this->json(['message' => 'Book ID is required'], Response::HTTP_BAD_REQUEST);
            }

            $bookRepository = $this->entityManager->getRepository(Book::class);
            $book = $bookRepository->find($bookId);

            if (!$book) {
                return $this->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
            }

            $result = $this->cartService->addItemToCart($user, $book, $quantity);

            return $this->json([
                'message' => 'Item added to cart',
                'cart_id' => $result['cart_id'],
                'total' => $result['total']
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/user/{userId}/remove', name: 'cart_user_remove_item', methods: ['POST'])]
    public function removeItemFromUserCart(int $userId, Request $request): JsonResponse
    {
        try {
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->find($userId);

            if (!$user) {
                return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            $bookId = $data['bookId'] ?? null;

            if (!$bookId) {
                return $this->json(['message' => 'Book ID is required'], Response::HTTP_BAD_REQUEST);
            }

            $bookRepository = $this->entityManager->getRepository(Book::class);
            $book = $bookRepository->find($bookId);

            if (!$book) {
                return $this->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
            }

            $result = $this->cartService->removeItemFromCart($user, $book);

            return $this->json([
                'message' => 'Item removed from cart',
                'total' => $result['total']
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/user/{userId}/update-quantity', name: 'cart_user_update_quantity', methods: ['POST'])]
    public function updateItemQuantity(int $userId, Request $request): JsonResponse
    {
        try {
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->find($userId);

            if (!$user) {
                return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            $bookId = $data['bookId'] ?? null;
            $quantity = $data['quantity'] ?? 1;

            if (!$bookId || $quantity < 1) {
                return $this->json(['message' => 'Book ID and valid quantity are required'], Response::HTTP_BAD_REQUEST);
            }

            $bookRepository = $this->entityManager->getRepository(Book::class);
            $book = $bookRepository->find($bookId);

            if (!$book) {
                return $this->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
            }

            $result = $this->cartService->updateItemQuantity($user, $book, $quantity);

            return $this->json([
                'message' => 'Quantity updated',
                'total' => $result['total']
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}