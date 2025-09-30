<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/users/{id}', name: 'user_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'mail' => $user->getMail(),
            'pseudo' => $user->getPseudo(),
            'telephone' => $user->getTelephone(),
            'adresse' => $user->getAdresse(),
            'status_id' => $user->getStatusId(),
            'status_name' => $user->getStatus() ? $user->getStatus()->getStatusName() : null,
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s')
        ]);
    }

    #[Route('/users', name: 'users_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $userData = [];

        foreach ($users as $user) {
            $userData[] = [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'mail' => $user->getMail(),
                'pseudo' => $user->getPseudo(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s')
            ];
        }

        return new JsonResponse($userData);
    }
}
