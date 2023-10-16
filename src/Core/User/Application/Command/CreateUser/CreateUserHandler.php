<?php

namespace App\Core\User\Application\Command\CreateUser;

use App\Core\User\Domain\User;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Common\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly MailerInterface $mailer
    ) {}

    public function __invoke(CreateUserCommand $command): void
    {
        $user = new User($command->email);
        $this->userRepository->save($user);
    }
}