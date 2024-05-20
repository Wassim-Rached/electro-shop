<?php

namespace App\Controller;

use App\Form\AddressType;
use App\Form\ChangePasswordType;
use App\Repository\ApplicationUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', []);
    }

    #[Route('/profile/change-password',name: 'change-password', methods: ['GET', 'POST'])]
    public function changePassword(
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        EntityManagerInterface $entityManager,
        ApplicationUserRepository $applicationUserRepository
    ): Response
    {
        $user = $this->getUser();
        $applicationUser = $applicationUserRepository->findOneBy(['username' => $user->getUserIdentifier()]);

        $form = $this->createForm(ChangePasswordType::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$passwordHasher->isPasswordValid($applicationUser, $form->get('oldPassword')->getData())) {
                $form->get('oldPassword')->addError(new FormError('Invalid password'));
                return $this->render('main/change-password.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $applicationUser->setPassword($passwordHasher->hashPassword(
                $applicationUser,
                $form->get('newPassword')->getData()
            ));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('main/change-password.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/profile/address', name: 'edit-address')]
    public function address(
        Request $request,
        EntityManagerInterface $entityManager,
        ApplicationUserRepository $applicationUserRepository,
    ): Response
    {
        $applicationUser = $applicationUserRepository->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);
        $address = $applicationUser->getAddress();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($address) {
                $address->setAddressLine1($form->get('address_line1')->getData());
                $address->setAddressLine2($form->get('address_line2')->getData());
                $address->setPostalCode($form->get('postalCode')->getData());
                $address->setCity($form->get('city')->getData());
                $address->setPhoneNumber($form->get('phoneNumber')->getData());
            } else {
                $address = $form->getData();
            }

            $applicationUser->setAddress($address);

            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('main/edit-address.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
