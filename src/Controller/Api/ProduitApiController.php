<?php

namespace App\Controller\Api;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/produits')]
class ProduitApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProduitRepository $produitRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    #[Route('', name: 'api_produits_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $queryBuilder = $this->produitRepository->createQueryBuilder('p');

        // Search by name, description, or category
        if ($search = $request->query->get('search')) {
            $queryBuilder
                ->andWhere('p.nom LIKE :search OR p.description LIKE :search OR p.categorie LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        // Filter by category
        if ($category = $request->query->get('category')) {
            $queryBuilder
                ->andWhere('p.categorie = :category')
                ->setParameter('category', $category);
        }

        // Filter by price range
        if ($minPrice = $request->query->get('minPrice')) {
            $queryBuilder
                ->andWhere('p.prix >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }
        if ($maxPrice = $request->query->get('maxPrice')) {
            $queryBuilder
                ->andWhere('p.prix <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        // Filter by stock quantity
        if ($minStock = $request->query->get('minStock')) {
            $queryBuilder
                ->andWhere('p.quantiteStock >= :minStock')
                ->setParameter('minStock', $minStock);
        }
        if ($maxStock = $request->query->get('maxStock')) {
            $queryBuilder
                ->andWhere('p.quantiteStock <= :maxStock')
                ->setParameter('maxStock', $maxStock);
        }

        // Filter by availability
        if ($available = $request->query->get('available')) {
            $queryBuilder
                ->andWhere('p.disponible = :available')
                ->setParameter('available', $available === 'true');
        }

        // Sorting
        $sort = $request->query->get('sort', 'nom');
        $direction = $request->query->get('direction', 'asc');
        $allowedSortFields = ['nom', 'prix', 'quantiteStock', 'categorie'];
        
        if (in_array($sort, $allowedSortFields)) {
            $queryBuilder->orderBy('p.' . $sort, $direction);
        }

        // Pagination
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);
        $offset = ($page - 1) * $limit;

        $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $produits = $queryBuilder->getQuery()->getResult();
        $data = $this->serializer->serialize($produits, 'json', ['groups' => 'produit:read']);
        
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', name: 'api_produits_show', methods: ['GET'])]
    public function show(Produit $produit): JsonResponse
    {
        $data = $this->serializer->serialize($produit, 'json', ['groups' => 'produit:read']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', name: 'api_produits_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $produit = new Produit();
        
        $produit->setNom($data['nom'] ?? '');
        $produit->setDescription($data['description'] ?? null);
        $produit->setCategorie($data['categorie'] ?? null);
        $produit->setPrix($data['prix'] ?? '0');
        $produit->setQuantiteStock($data['quantiteStock'] ?? 0);
        $produit->setDisponible($data['disponible'] ?? true);
        $produit->setImagePath($data['imagePath'] ?? null);

        $errors = $this->validator->validate($produit);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        $this->entityManager->persist($produit);
        $this->entityManager->flush();

        $data = $this->serializer->serialize($produit, 'json', ['groups' => 'produit:read']);
        return new JsonResponse($data, 201, [], true);
    }

    #[Route('/{id}', name: 'api_produits_update', methods: ['PUT'])]
    public function update(Request $request, Produit $produit): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['nom'])) $produit->setNom($data['nom']);
        if (isset($data['description'])) $produit->setDescription($data['description']);
        if (isset($data['categorie'])) $produit->setCategorie($data['categorie']);
        if (isset($data['prix'])) $produit->setPrix($data['prix']);
        if (isset($data['quantiteStock'])) $produit->setQuantiteStock($data['quantiteStock']);
        if (isset($data['disponible'])) $produit->setDisponible($data['disponible']);
        if (isset($data['imagePath'])) $produit->setImagePath($data['imagePath']);

        $errors = $this->validator->validate($produit);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        $this->entityManager->flush();

        $data = $this->serializer->serialize($produit, 'json', ['groups' => 'produit:read']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', name: 'api_produits_delete', methods: ['DELETE'])]
    public function delete(Produit $produit): JsonResponse
    {
        $this->entityManager->remove($produit);
        $this->entityManager->flush();
        
        return new JsonResponse(null, 204);
    }
} 