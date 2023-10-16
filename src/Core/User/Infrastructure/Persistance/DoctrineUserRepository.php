<?php

namespace App\Core\User\Infrastructure\Persistance;

use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Psr\EventDispatcher\EventDispatcherInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * @throws NonUniqueResultException
     */
    public function getByEmail(string $email): User
    {
        $user = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :user_email')
            ->setParameter(':user_email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw new UserNotFoundException('Użytkownik nie istnieje');
        }

        return $user;
    }

    public function save(User $user): void 
    {
        $this->entityManager->persist($user);

        $events = $user->pullEvents();
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }

        $this->entityManager->flush();
    }

    public function findByActivationKey(string $activationKey): ?User
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.activationKey = :activation_key')
            ->setParameter(':activation_key', $activationKey)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function isActivationKeyValid(User $user): bool
    {
        $dateCreateRecord = $user->getCreatedAt();

        $expirationDate = $dateCreateRecord->modify("+{24} hours");

        return $expirationDate >= new \DateTime();
    }

    public function findUserByActive(bool $isActive=true): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.isActive = :is_active')
            ->setParameter(':is_active', $isActive)
            ->getQuery()
            ->getResult();
    }
}