<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\Product;
use App\Form\CommandType;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/command')]
class CommandController extends AbstractController
{
    #[Route('/product/{id}/new', name: 'app_command_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        EntityManagerInterface $entityManager,
        Product                $product
    ): Response
    {
        $command = new Command();
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command->setProduct($product);
            $command->setForUser($this->getUser());
            $command->setCreatedAt(new \DateTimeImmutable());
            $command->setStatus('pending');
            $command->setTotal($product->getPrice() * $command->getQuantity());
            $entityManager->persist($command);
            $entityManager->flush();

            return $this->redirectToRoute('app_command_show', ['id' => $command->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('command/new.html.twig', [
            'command' => $command,
            'form' => $form,
        ]);
    }


    #[Route('/my-cart', name: 'app_my_cart', methods: ['GET'])]
    public function my_cart(CommandRepository $commandRepository): Response
    {
        return $this->render('command/my_cart.html.twig', [
            'commands' => $commandRepository->findBy(['for_user' => $this->getUser()])
        ]);
    }

    #[Route('/{id}/accept', name: 'app_command_accept', methods: ['GET'])]
    public function accept(Command $command, EntityManagerInterface $entityManager): Response
    {
        if ($command->getProduct()->getCreatedBy() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $command->setStatus('accepted');
        $entityManager->flush();
        return $this->redirectToRoute('app_product_commands', ['id' => $command->getProduct()->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/product/{id}', name: 'app_product_commands', methods: ['GET'])]
    public function product_commands(Product $product, CommandRepository $commandRepository): Response
    {
        if ($product->getCreatedBy() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        return $this->render('command/product_commands.html.twig', [
            'commands' => $commandRepository->findBy(['product' => $product]),
            'product' => $product
        ]);
    }

    #[Route('/{id}', name: 'app_command_show', methods: ['GET'])]
    public function show(Command $command): Response
    {
        return $this->render('command/show.html.twig', [
            'command' => $command,
        ]);
    }

    #[Route('/{id}', name: 'app_command_delete', methods: ['POST'])]
    public function delete(Request $request, Command $command, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $command->getId(), $request->getPayload()->get('_token'))) {
            if ($command->getForUser() !== $this->getUser()) {
                throw $this->createAccessDeniedException();
            }
            $entityManager->remove($command);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
    }

}
