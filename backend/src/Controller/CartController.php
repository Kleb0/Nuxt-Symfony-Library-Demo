<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/cart')]
class CartController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CartRepository $cartRepository,
        private SerializerInterface $serializer
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
}