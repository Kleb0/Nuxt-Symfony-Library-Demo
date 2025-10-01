<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api', name: 'api_')]
class AuthController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Email et mot de passe requis'
            ], Response::HTTP_BAD_REQUEST);
        }

        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userRepository->findOneBy(['mail' => $email]);
        if (!$user) {
            $user = $this->userRepository->findOneBy(['pseudo' => $email]);
        }

        if (!$user || $user->getMotDePasse() !== $password) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $session = $request->getSession();
        $session->start();
        $session->set('user_id', $user->getId());
        $session->set('user_authenticated', true);
        
        // Debug session data
        error_log("Login - Session ID: " . $session->getId());
        error_log("Login - User ID set: " . $user->getId());
        error_log("Login - Session started: " . ($session->isStarted() ? 'true' : 'false'));
        
        // Force session write
        $session->save();
        session_write_close();

        // Créer un token simple pour contourner le problème de session
        $token = base64_encode(json_encode([
            'user_id' => $user->getId(),
            'timestamp' => time(),
            'hash' => md5($user->getId() . $user->getMail() . time())
        ]));

        $response = new JsonResponse([
            'success' => true,
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getMail(),
                'pseudo' => $user->getPseudo(),
                'statuses' => array_map(function($status) {
                    return [
                        'status_id' => $status->getStatusId(),
                        'status_name' => $status->getStatusName(),
                        'status_state' => $status->getStatusState()
                    ];
                }, $user->getStatuses()->toArray())
            ]
        ]);
        
        // Forcer l'envoi du cookie de session
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        
        return $response;
    }

    #[Route('/logout', name: 'logout', methods: ['POST'])]
    public function logout(Request $request): JsonResponse
    {
        $session = $request->getSession();
        $session->clear();
        $session->invalidate();
        
        return new JsonResponse([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    #[Route('/check-session', name: 'check_session', methods: ['GET'])]
    public function checkSession(Request $request): JsonResponse
    {
        $session = $request->getSession();
        
        if (!$session->isStarted()) {
            $session->start();
        }
        
        if ($session->get('user_authenticated') && $session->get('user_id')) {
            $user = $this->userRepository->find($session->get('user_id'));
            if ($user) {
                return $this->buildUserResponse($user);
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
                            return $this->buildUserResponse($user);
                        }
                    }
                }
            } catch (\Exception $e) {
                // Token invalide
            }
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Non connecté'
        ], Response::HTTP_UNAUTHORIZED);
    }
    
    private function buildUserResponse(User $user): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'user' => [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getMail(),
                'pseudo' => $user->getPseudo(),
                'statuses' => array_map(function($status) {
                    return [
                        'status_id' => $status->getStatusId(),
                        'status_name' => $status->getStatusName(),
                        'status_state' => $status->getStatusState()
                    ];
                }, $user->getStatuses()->toArray())
            ]
        ]);
    }

    #[Route('/debug-session', name: 'debug_session', methods: ['GET'])]
    public function debugSession(Request $request): JsonResponse
    {
        $session = $request->getSession();
        
        if (!$session->isStarted()) {
            $session->start();
        }
        
        return new JsonResponse([
            'session_id' => $session->getId(),
            'session_started' => $session->isStarted(),
            'user_authenticated' => $session->get('user_authenticated'),
            'user_id' => $session->get('user_id'),
            'all_session_data' => $session->all(),
            'cookies' => $request->cookies->all(),
            'origin' => $request->headers->get('Origin'),
            'referer' => $request->headers->get('Referer')
        ]);
    }
}
