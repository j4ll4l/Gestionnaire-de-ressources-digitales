<?php

namespace App\Controller;

use App\Entity\Ressources;
use App\Entity\Categorie;
use App\Entity\Section;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class RessourcesController extends AbstractController
{
    #[Route('/api/categories/admin', name: 'admin_categories', methods: ['GET'])]
    public function getAllCategoriesWithSectionsAndRessources(EntityManagerInterface $em): JsonResponse
    {

        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }

        // Récupérer toutes les catégories
        $categories = $em->getRepository(Categorie::class)->findAll();

        $data = [];

        // Récupérer chaque catégorie
        foreach ($categories as $categorie) {
            $sectionsData = [];

            // Récupérer chaque section de la catégorie
            foreach ($categorie->getSections() as $section) {
                $ressourcesData = [];

                // Récupérer chaque ressource de la section
                foreach ($section->getRessources() as $ressource) {
                    $ressourcesData[] = [
                        'id' => $ressource->getId(),
                        'nom' => $ressource->getNom(),
                        'url' => $ressource->getUrl(),
                        'description' => $ressource->getDescription(),
                    ];
                }

                $sectionsData[] = [
                    'id' => $section->getId(),
                    'nom' => $section->getNom(),
                    'ressources' => $ressourcesData,
                ];
            }

            $data[] = [
                'id' => $categorie->getId(),
                'nom' => $categorie->getNom(),
                'description' => $categorie->getDescription(),
                'sections' => $sectionsData,
            ];
        }

        return $this->json($data, Response::HTTP_OK);
    }
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

        foreach ($sections as $section) {
            $ressources = [];

            foreach ($section->getRessources() as $ressource) {
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

        $ressource = new Ressources();
        $ressource->setNom($data['nom']);
        $ressource->setUrl($data['url']);
        $ressource->setDescription($data['description']);
        $ressource->setIdSection($section);
        $ressource->setPublishedAt(new DateTime);

        $em->persist($ressource);
        $em->flush();

        return $this->json([
            'message' => 'Ressource ajoutée avec succès',
            'id' => $ressource->getId()
        ], Response::HTTP_CREATED);
    }
    #[Route('/api/ressource/{id}', name: 'supprimer_ressource', methods: ['DELETE'])]
    public function supprimerRessource(
        int $id,
        EntityManagerInterface $em
    ): JsonResponse {
        // Vérification des permissions
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }

        // Récupération de la ressource
        $ressource = $em->getRepository(\App\Entity\Ressources::class)->find($id);

        if (!$ressource) {
            return $this->json(['error' => 'Ressource non trouvée'], Response::HTTP_NOT_FOUND);
        }

        // Suppression de la ressource
        $em->remove($ressource);
        $em->flush();

        return $this->json([
            'message' => 'Ressource supprimée avec succès'
        ], Response::HTTP_OK);
    }

    #[Route('api/ressource/{id}', name: 'modifier_ressource', methods: ['PUT'])]
    public function modifierRessource(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Vérification des permissions
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Accès refusé'], Response::HTTP_FORBIDDEN);
        }
        // Récupération de la ressource
        $ressource = $em->getRepository(Ressources::class)->find($id);
        if (!$ressource) {
            return $this->json(['error' => 'Ressource non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nom'])) {
            $ressource->setNom($data['nom']);
        }
        if (isset($data['url'])) {
            $ressource->setUrl($data['url']);
        }
        if (isset($data['description'])) {
            $ressource->setDescription($data['description']);
        }
        if (isset($data['section_id'])) {
            $section = $em->getRepository(Section::class)->find($data['section_id']);
            if ($section) {
                $ressource->setIdSection($section);
            }
        }
        $ressource->setPublishedAt(new DateTime);


        $em->persist($ressource);
        $em->flush();

        return $this->json([
            'message' => 'Ressource modifier avec succès'
        ], Response::HTTP_OK);
    }
}
