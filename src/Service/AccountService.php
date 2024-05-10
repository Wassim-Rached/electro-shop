<?php

namespace App\Service;

use App\Entity\ApplicationUser;
use App\Repository\ApplicationUserRepository;

class AccountService
{
    private ApplicationUserRepository $applicationUserRepository;
    public function __construct(ApplicationUserRepository $applicationUserRepository)
    {
        $this->applicationUserRepository = $applicationUserRepository;
    }

    public function getAccountById()
    {
        throw new \Exception('Not implemented yet');
    }

    public function getAllAccounts(): array
    {
        return $this->applicationUserRepository->findAll();
    }

    public function createAccount()
    {
        throw new \Exception('Not implemented yet');
    }

    public function updateAccount()
    {
        throw new \Exception('Not implemented yet');
    }

    public function deleteAccount()
    {
        throw new \Exception('Not implemented yet');
    }

}