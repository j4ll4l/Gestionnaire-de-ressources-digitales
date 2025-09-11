<?php

namespace App\Controller;

use App\Entity\Categorie;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class CategorieController extends AbstractController
{
    #[Route('/api/categorie', name: 'app_categorie_show', methods: ['GET'])]
    public function showCategorie(EntityManagerInterface $em): JsonResponse{

        $categories = $em->getRepository(Categorie::class)->findAll();
        $data = [];
        // Transformer les entités en tableau d'objet
        foreach ($categories as $categorie) {
        $data[] = [
            'id' => $categorie->getId(),
            'nom' => $categorie->getNom(),
            'description' => $categorie->getDescription(),
        ];
    }
        return $this->json($data, Response::HTTP_OK);
    }
    #[Route('/api/categorie/add', name: 'app_categorie_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $data = json_decode($request->getContent(), true);

        // Vérifie que le JSON envoyé contient bien les champs 
        if (!$data || !isset($data['nom'], $data['description'])) {
            return $this->json(['error' => 'Champs nom et description requis'], 400);
        }

        // Créer une nouvelle catégorie
        $categorie = new Categorie();
        $categorie->setNom($data['nom']);
        $categorie->setDescription($data['description']);
        $categorie->setPublishedAt(new DateTime);

        $em->persist($categorie);
        $em->flush();
        return $this->json([
            'message' => 'Catégorie ajoutée avec succès',
            'id' => $categorie->getId(),
            'nom' => $categorie->getNom(),
            'description' => $categorie->getDescription(),
        ], 201);
    }
}
