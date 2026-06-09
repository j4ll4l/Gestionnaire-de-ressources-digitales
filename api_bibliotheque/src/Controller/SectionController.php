<?php

namespace App\Controller;

use DateTime;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class SectionController extends AbstractController
{
    #[Route('/api/categorie/add_section', name: 'app_section_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Vérification des permissions
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }

        // Récupérer le JSON envoyé
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['nom'], $data['categorie_id'])) {
    return $this->json(['error' => 'Champs nom et categorie_id requis'], 400);
}

        // Récupérer la catégorie correspondante
        $categorie = $em->getRepository(Categorie::class)->find($data['categorie_id']);
        if (!$categorie) {
            return $this->json(['error' => 'Catégorie non trouvée'], Response::HTTP_NOT_FOUND);
        }

        // Créer la section
        $section = new \App\Entity\Section();
        $section->setNom($data['nom']);
        $section->setIdCategorie($categorie);
        $section->setPublishedAt(new \DateTime());

        $em->persist($section);
        $em->flush();

        return $this->json([
            'message' => 'Section ajoutée avec succès',
            'id' => $section->getId(),
            'nom' => $section->getNom(),
            'categorie_id' => $categorie->getId(),
            'categorie_nom' => $categorie->getNom()
        ], 201);
    }
}
