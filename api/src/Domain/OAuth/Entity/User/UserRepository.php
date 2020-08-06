<?php

declare(strict_types=1);

namespace Domain\OAuth\Entity\User;

use Doctrine\Persistence\ObjectRepository;
use Domain\User\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Domain\User\Service\User\PasswordValidator;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Domain\OAuth\Entity\User\User as OAuthUser;

final class UserRepository implements UserRepositoryInterface
{
    private ObjectRepository $repo;
    private PasswordValidator $validator;

    public function __construct(EntityManagerInterface $em, PasswordValidator $validator)
    {
        $this->repo = $em->getRepository(User::class);
        $this->validator = $validator;
    }

    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity): ?UserEntityInterface
    {
        /** @var User $user */
        if ($user = $this->repo->findOneBy(['email' => $username])) {
            if (!$this->validator->validate($password, $user->getPassword()->getValue())) {
                return null;
            }
            return new OAuthUser($user->getId()->getValue());
        }
        return  null;
    }
}
