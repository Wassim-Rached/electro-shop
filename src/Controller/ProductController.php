<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Product1Type;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }


    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository,Request $request): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->searchAndFilter(
                $request->query->get('title'),
                $request->query->get('max_price'),
                $request->query->get('min_price'),
                $request->query->get('is_used'),
                $request->query->get('orderby')
            )
        ]);
    }

    #[Route('/profile/my', name: 'app_my_products', methods: ['GET'])]
    public function myProducts(ProductRepository $productRepository): Response
    {
        return $this->render('product/my_products.html.twig', [
            'products' => $productRepository->findBy(['created_by' => $this->getUser()]),
            'user'=> $this->getUser()
        ]);
    }

    #[Route('/profile/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedBy($this->getUser());
            $product->setStatus('pending');

            $photo = $form->get('photo')->getData();
            if ($photo) {
                $photoFileName = $this->fileUploader->upload($photo);
                $product->setPhoto($photoFileName);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_my_products', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }


    #[Route('/profile/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->get('_token'))) {
            if ($this->getUser() !== $product->getCreatedBy()) {
                return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
            }
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
