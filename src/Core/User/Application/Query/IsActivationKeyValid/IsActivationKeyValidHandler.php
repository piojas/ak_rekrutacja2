<?php

namespace App\Core\User\Application\Query\IsActivationKeyValid;

use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Domain\User;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class IsActivationKeyValidHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(IsActivationKeyValidQuery $query): array
    {
        $users = $this->userRepository->isActivationKeyValid($query->user);

        return array_map(function (User $user) {
            return new UserDTO(
                $user->getEmail()
            );
        }, $users ?? []);
    }
}
