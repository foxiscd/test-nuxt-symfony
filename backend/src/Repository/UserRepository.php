<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use App\Entity\User;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneByUuidOrCreate(Uuid $uuid): User
    {
        $user = $this->findOneBy(['uuid' => $uuid]);

        if (!$user) {
            $user = new User();
            $user->setUuid($uuid);
            $entityManager = $this->getEntityManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $user;
    }
}