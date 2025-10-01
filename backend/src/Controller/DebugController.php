<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/debug', name: 'api_debug_')]
class DebugController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private CartService $cartService
    ) {}

    #[Route('/session', name: 'session', methods: ['GET'])]
    public function sessionDebug(Request $request): JsonResponse
    {
        $session = $request->getSession();
        
        if (!$session->isStarted()) {
            $session->start();
        }
        
        $debugData = [
            'session_id' => $session->getId(),
            'is_started' => $session->isStarted(),
            'user_authenticated' => $session->get('user_authenticated', false),
            'user_id' => $session->get('user_id', null),
            'all_session_data' => $session->all()
        ];
        
        if ($session->has('user_id')) {
            $user = $this->userRepository->find($session->get('user_id'));
            if ($user) {
                $debugData['user_info'] = [
                    'id' => $user->getId(),
                    'pseudo' => $user->getPseudo(),
                    'email' => $user->getMail(),
                    'carts_count' => $user->getCarts()->count(),
                    'carts_ids' => array_map(fn($cart) => $cart->getCartId(), $user->getCarts()->toArray())
                ];
            } else {
                $debugData['user_info'] = 'User not found in database';
            }
        }
        
        return new JsonResponse($debugData);
    }

    #[Route('/cart-test', name: 'cart_test', methods: ['GET'])]
    public function cartTest(Request $request): JsonResponse
    {
        try {
            $session = $request->getSession();
            
            if (!$session->isStarted()) {
                $session->start();
            }
            
            if (!$session->has('user_authenticated') || !$session->get('user_authenticated')) {
                return new JsonResponse(['error' => 'Not authenticated'], 401);
            }

            $userId = $session->get('user_id');
            $user = $this->userRepository->find($userId);

            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }

            // Test direct sur l'utilisateur
            $carts = $user->getCarts();
            
            return new JsonResponse([
                'user_id' => $user->getId(),
                'carts_count' => $carts->count(),
                'carts_type' => get_class($carts),
                'carts_is_empty' => $carts->isEmpty(),
                'carts_array' => array_map(fn($cart) => [
                    'id' => $cart->getCartId(),
                    'total' => $cart->getTotalPrice()
                ], $carts->toArray())
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/cart-simple', name: 'cart_simple', methods: ['GET'])]
    public function cartSimple(Request $request): JsonResponse
    {
        $session = $request->getSession();
        
        if (!$session->isStarted()) {
            $session->start();
        }
        
        return new JsonResponse([
            'message' => 'Simple cart test endpoint works',
            'user_authenticated' => $session->get('user_authenticated', false),
            'user_id' => $session->get('user_id', null)
        ]);
    }
}