<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookImageController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository
    ) {
    }

    #[Route('/api/books/{id}/upload-image', name: 'book_upload_image', methods: ['POST'])]
    public function uploadImage(int $id, Request $request): JsonResponse
    {
        try {
            $book = $this->bookRepository->find($id);
            
            if (!$book) {
                return new JsonResponse(['error' => 'Livre non trouvé'], Response::HTTP_NOT_FOUND);
            }

            /** @var UploadedFile $imageFile */
            $imageFile = $request->files->get('image');
            
            if (!$imageFile) {
                return new JsonResponse(['error' => 'Aucun fichier image fourni'], Response::HTTP_BAD_REQUEST);
            }

            // Vérifier que c'est bien une image
            $mimeType = $imageFile->getMimeType();
            if (!$mimeType || !str_starts_with($mimeType, 'image/')) {
                return new JsonResponse(['error' => 'Le fichier doit être une image'], Response::HTTP_BAD_REQUEST);
            }

            // Vérifier la taille du fichier (max 5MB)
            if ($imageFile->getSize() > 5 * 1024 * 1024) {
                return new JsonResponse(['error' => 'Le fichier est trop volumineux (max 5MB)'], Response::HTTP_BAD_REQUEST);
            }

            // Créer un nom de fichier unique
            $originalFilename = $imageFile->getClientOriginalName();
            if (!$originalFilename) {
                $originalFilename = 'image';
            }
            
            $extension = $imageFile->guessExtension();
            if (!$extension) {
                $extension = 'jpg'; // Extension par défaut
            }
            
            $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($originalFilename, PATHINFO_FILENAME));
            $newFilename = $safeFilename.'_'.uniqid().'.'.$extension;

            // Créer le dossier s'il n'existe pas
            $uploadDir = $this->getParameter('kernel.project_dir').'/public/images';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Déplacer le fichier
            $imageFile->move($uploadDir, $newFilename);
            
            // Supprimer l'ancienne image si elle existe
            if ($book->getImage()) {
                $oldImagePath = $this->getParameter('kernel.project_dir').'/public/'.$book->getImage();
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Mettre à jour le livre avec le nouveau chemin d'image
            $imagePath = 'images/' . $newFilename;
            $book->setImage($imagePath);
            $this->entityManager->flush();
            
            return new JsonResponse([
                'success' => true,
                'imagePath' => $imagePath,
                'message' => 'Image uploadée avec succès'
            ]);
            
        } catch (FileException $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur inattendue: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}