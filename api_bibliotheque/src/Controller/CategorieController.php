<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class CategorieController extends AbstractController
{
    #[Route('/api/categorie/add', name: 'app_categorie_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        // Récupérer le JSON envoyé
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['nom'], $data['description'])) {
            return $this->json(['error' => 'Champs nom et description requis'], 400);
        }

        // Créer une nouvelle catégorie
        $categorie = new Categorie();
        $categorie->setNom($data['nom']);
        $categorie->setDescription($data['description']);

        $em->persist($categorie);
        $em->flush();

        return $this->json([
            'message' => 'Catégorie ajoutée avec succès',
            'id' => $categorie->getId(),
            'nom' => $categorie->getNom(),
            'description' => $categorie->getDescription(),
        ], 201);
    }
    #[Route('/api/categorie', name: 'app_categorie')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CategorieController.php',
        ]);
    }
}
