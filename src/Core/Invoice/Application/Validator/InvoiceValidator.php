<?php

namespace App\Core\Invoice\Application\Validator;

use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\Invoice\Domain\Exception\InvoiceInactiveUserException;
use App\Core\Invoice\Domain\Exception\InvoiceAmountIsZeroOrLessException;

class InvoiceValidator 
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function validateUserIsActiveByEmail(string $email): void
    {
        $user = $this->userRepository->getByEmail($email);

        if (!$user || !$user->getIsActive()) {
            throw new InvoiceInactiveUserException();
        }
    }

    public static function validateInvoiceAmount(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvoiceAmountIsZeroOrLessException();
        }
    }

    public static function validateUserIsActive(bool $active): void
    {
        if ($active === false) {
            throw new InvoiceInactiveUserException();
        }
    }
}