<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Service\StaticSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/admin/produit')]
class AdminProduitController extends AbstractController
{
    public function __construct(
        private StaticSession $staticSession
    ) {
    }

    #[Route('/', name: 'app_admin_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('P_Cadmin/produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imagePath')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('products_directory'),
                        $newFilename
                    );
                    $produit->setImagePath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error uploading image');
                }
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Product created successfully');
            return $this->redirectToRoute('app_admin_produit_index');
        }

        return $this->render('P_Cadmin/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('P_Cadmin/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imagePath')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    // Delete old image if it exists
                    if ($produit->getImagePath()) {
                        $oldImagePath = $this->getParameter('products_directory').'/'.$produit->getImagePath();
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $imageFile->move(
                        $this->getParameter('products_directory'),
                        $newFilename
                    );
                    $produit->setImagePath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error uploading image');
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Product updated successfully');
            return $this->redirectToRoute('app_admin_produit_index');
        }

        return $this->render('P_Cadmin/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if (!$this->staticSession->isAdmin()) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            // Delete the image file if it exists
            if ($produit->getImagePath()) {
                $imagePath = $this->getParameter('products_directory').'/'.$produit->getImagePath();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Product deleted successfully');
        }

        return $this->redirectToRoute('app_admin_produit_index');
    }
} 