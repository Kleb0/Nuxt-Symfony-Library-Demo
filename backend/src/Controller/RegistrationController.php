<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Status;
use App\Entity\Cart;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class RegistrationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StatusRepository $statusRepository,
        private ValidatorInterface $validator
    ) {}

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des champs requis
        $requiredFields = ['nom', 'prenom', 'pseudo', 'mail', 'telephone', 'adresse', 'motDePasse'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return new JsonResponse([
                    'success' => false,
                    'message' => "Le champ {$field} est requis"
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        try {
            // Vérifier si l'email ou le pseudo existe déjà
            $existingUser = $this->entityManager->getRepository(User::class)
                ->createQueryBuilder('u')
                ->where('u.mail = :email OR u.pseudo = :pseudo')
                ->setParameter('email', $data['mail'])
                ->setParameter('pseudo', $data['pseudo'])
                ->getQuery()
                ->getOneOrNullResult();

            if ($existingUser) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Un utilisateur avec cet email ou ce pseudo existe déjà'
                ], Response::HTTP_CONFLICT);
            }

            // Créer le nouvel utilisateur
            $user = new User();
            $user->setNom($data['nom']);
            $user->setPrenom($data['prenom']);
            $user->setPseudo($data['pseudo']);
            $user->setMail($data['mail']);
            $user->setTelephone($data['telephone']);
            $user->setAdresse($data['adresse']);
            $user->setMotDePasse($data['motDePasse']);
            
            // Gérer l'image de profil si fournie
            if (isset($data['imageProfil']) && !empty($data['imageProfil'])) {
                $user->setImageProfil($data['imageProfil']);
            }

            // Attribuer automatiquement le statut "User" (statusState = 1)
            $userStatus = $this->statusRepository->findOneBy(['statusState' => 1]);
            if ($userStatus) {
                $user->addStatus($userStatus);
            }

            // Créer un panier unique pour l'utilisateur
            $cart = new Cart();
            $cart->setTotalPrice('0.00');
            $this->entityManager->persist($cart);
            
            // Associer le panier à l'utilisateur
            $user->addCart($cart);

            // Valider l'entité
            $violations = $this->validator->validate($user);
            if (count($violations) > 0) {
                $errors = [];
                foreach ($violations as $violation) {
                    $errors[] = $violation->getMessage();
                }
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $errors
                ], Response::HTTP_BAD_REQUEST);
            }

            // Sauvegarder en base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new JsonResponse([
                'success' => true,
                'message' => 'Inscription réussie ! Vous pouvez maintenant vous connecter.',
                'user' => [
                    'id' => $user->getId(),
                    'nom' => $user->getNom(),
                    'prenom' => $user->getPrenom(),
                    'pseudo' => $user->getPseudo(),
                    'email' => $user->getMail()
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur lors de l\'inscription: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}