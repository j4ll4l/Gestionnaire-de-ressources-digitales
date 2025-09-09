<?php

namespace App\Controller;

use App\Entity\Categorie;
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
}
