<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HealthCheckController extends AbstractController
{
    #[Route('/health-check', name: 'app_health_check')]
    public function index(): JsonResponse
    {
        return $this->json(
            'Server is up and running'
            );
    }

    #[Route('/health-check/db', name: 'app_health_check_db')]
    public function test_db(EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->getConnection()->connect();
            $isConnected = $entityManager->getConnection()->isConnected();
            if ($isConnected) {
                return $this->json('Successfully connected to the database.');
            } else {
                return $this->json('F: Failed to connect to the database.', 500);
            }
        } catch (\Exception $e) {
            return $this->json('E: Failed to connect to the database.', 500);
        }    }
}
