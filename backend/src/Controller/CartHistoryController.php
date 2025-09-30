<?php

namespace App\Controller;

use App\Entity\CartHistory;
use App\Repository\CartHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/cart-history')]
class CartHistoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CartHistoryRepository $cartHistoryRepository,
        private SerializerInterface $serializer
    ) {}

    #[Route('', name: 'cart_history_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $cartHistories = $this->cartHistoryRepository->findAll();
        
        return $this->json($cartHistories, Response::HTTP_OK, [], ['groups' => 'cart_history:read']);
    }

    #[Route('/{id}', name: 'cart_history_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $cartHistory = $this->cartHistoryRepository->find($id);

        if (!$cartHistory) {
            return $this->json(['message' => 'Cart history not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($cartHistory, Response::HTTP_OK, [], ['groups' => 'cart_history:read']);
    }

    #[Route('', name: 'cart_history_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $cartHistory = new CartHistory();
        
        if (isset($data['list_cart_id'])) {
            $cartHistory->setListCartId($data['list_cart_id']);
        }

        $this->entityManager->persist($cartHistory);
        $this->entityManager->flush();

        return $this->json($cartHistory, Response::HTTP_CREATED, [], ['groups' => 'cart_history:read']);
    }

    #[Route('/{id}', name: 'cart_history_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $cartHistory = $this->cartHistoryRepository->find($id);

        if (!$cartHistory) {
            return $this->json(['message' => 'Cart history not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['list_cart_id'])) {
            $cartHistory->setListCartId($data['list_cart_id']);
        }

        $this->entityManager->flush();

        return $this->json($cartHistory, Response::HTTP_OK, [], ['groups' => 'cart_history:read']);
    }

    #[Route('/{id}', name: 'cart_history_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $cartHistory = $this->cartHistoryRepository->find($id);

        if (!$cartHistory) {
            return $this->json(['message' => 'Cart history not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($cartHistory);
        $this->entityManager->flush();

        return $this->json(['message' => 'Cart history deleted'], Response::HTTP_OK);
    }
}