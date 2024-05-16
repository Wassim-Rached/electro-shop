<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductReport;
use App\Form\ProductReportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/report/product')]
class ProductReportController extends AbstractController
{

    #[Route('/new/{id}', name: 'app_product_report_new', methods: ['GET', 'POST'])]
    public function new(
                        Product $product,
                        Request $request,
                        EntityManagerInterface $entityManager
    ): Response
    {
        $productReport = new ProductReport();
        $form = $this->createForm(ProductReportType::class, $productReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productReport->setToProduct($product);
            $productReport->setStatus("pending");
            $productReport->setByUser($this->getUser());
            $entityManager->persist($productReport);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product_report/new.html.twig', [
            'product_report' => $productReport,
            'form' => $form,
        ]);
    }

}
