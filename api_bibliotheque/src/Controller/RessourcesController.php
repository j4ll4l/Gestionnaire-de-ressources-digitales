<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Section;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class RessourcesController extends AbstractController
{
    #[Route('/api/categorie/{id}/sections-ressources', name: 'categorie_sections_ressources', methods: ['GET'])]
    public function getSectionsWithRessources(
        int $id,
        EntityManagerInterface $em
    ): JsonResponse {
        $categorie = $em->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            return $this->json(['error' => 'Catégorie non trouvée'], 404);
        }

        $sections = $categorie->getSections();
        $data = [];

        for ($i = 0; $i < count($sections); $i++) {
            $section = $sections[$i];
            $ressources = [];
            $sectionRessources = $section->getRessources();
            for ($j = 0; $j < count($sectionRessources); $j++) {
                $ressource = $sectionRessources[$j];
                $ressources[] = [
                    'id' => $ressource->getId(),
                    'nom' => $ressource->getNom(),
                    'url' => $ressource->getUrl(),
                    'description' => $ressource->getDescription(),
                ];
            }
            $data[] = [
                'section_id' => $section->getId(),
                'section_nom' => $section->getNom(),
                'ressources' => $ressources,
            ];
        }

        return $this->json($data);
    }
    #[Route('/api/ressource', name: 'ajouter_ressource', methods: ['POST'])]
    public function ajouterRessource(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        $data = json_decode($request->getContent(), true);

        if (
            !isset($data['nom'], $data['url'], $data['description'], $data['section_id'])
        ) {
            return $this->json(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $section = $em->getRepository(Section::class)->find($data['section_id']);
        if (!$section) {
            return $this->json(['error' => 'Section non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $ressource = new \App\Entity\Ressources();
        $ressource->setNom($data['nom']);
        $ressource->setUrl($data['url']);
        $ressource->setDescription($data['description']);
        $ressource->setIdSection($section);

        $em->persist($ressource);
        $em->flush();

        return $this->json([
            'message' => 'Ressource ajoutée avec succès',
            'id' => $ressource->getId()
        ], Response::HTTP_CREATED);
    }
}
