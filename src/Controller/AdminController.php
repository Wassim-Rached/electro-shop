<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductReport;
use App\Repository\ProductReportRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{

    #[Route('/products', name: 'app_admin_products', methods: ['GET'])]
    public function adminProducts(ProductRepository $productRepository): Response
    {
        return $this->render('admin/products_requests.html.twig', [
            'products' => $productRepository->findBy(['status' => 'pending'])
        ]);
    }

    #[Route('/products/{id}/accept', name: 'app_admin_products_accept', methods: ['GET'])]
    public function accept(Product $product, EntityManagerInterface $entityManager): Response
    {
        $product->setStatus('accepted');
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_products', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/products/{id}/reject', name: 'app_admin_products_reject', methods: ['GET'])]
    public function refuse(Product $product, EntityManagerInterface $entityManager): Response
    {
        $product->setStatus('rejected');
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_products', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/reports/', name: 'app_product_report_index', methods: ['GET'])]
    public function index(ProductReportRepository $productReportRepository): Response
    {
        return $this->render('product_report/index.html.twig', [
            'product_reports' => $productReportRepository->findBy(['status' => 'pending']),
        ]);
    }

    #[Route('/reports/accept/{id}', name: 'app_product_report_accept', methods: ['GET'])]
    public function acceptReport(ProductReport $productReport, EntityManagerInterface $entityManager): Response
    {
        $product = $productReport->getToProduct();
        $product->setStatus("blocked");
        $productReport->setStatus("accepted");
        $entityManager->persist($product);
        $entityManager->persist($productReport);
        $entityManager->flush();
        return $this->redirectToRoute('app_product_report_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reports/reject/{id}', name: 'app_product_report_reject', methods: ['GET'])]
    public function rejectReport(ProductReport $productReport, EntityManagerInterface $entityManager): Response
    {
        $productReport->setStatus("rejected");
        $entityManager->persist($productReport);
        $product = $productReport->getToProduct();
        $product->setStatus("accepted");
        $entityManager->persist($product);
        $entityManager->flush();
        return $this->redirectToRoute('app_product_report_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/product/report/{id}', name: 'app_product_report_show', methods: ['GET'])]
    public function show(ProductReport $productReport): Response
    {
        return $this->render('product_report/show.html.twig', [
            'product_report' => $productReport,
            'product' => $productReport->getToProduct(),
        ]);
    }
}
