<?php

declare(strict_types=1);

namespace Domain\OAuth\Entity\Client;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

final class ClientRepository implements ClientRepositoryInterface
{
    private array $clients;

    public function __construct(array $clients)
    {
        $this->clients = $clients;
    }

    public function getClientEntity(
        $clientIdentifier, $grantType = null, $clientSecret = null,
        $mustValidateSecret = true
    ): ?ClientEntityInterface
    {
        if (array_key_exists($clientIdentifier, $this->clients) === false) {
            return null;
        }

        if (
            $mustValidateSecret === true and
            $this->clients[$clientIdentifier]['is_confidential'] === true and
            password_verify($clientSecret, $this->clients[$clientIdentifier]['secret']) === false
        ) {
            return null;
        }

        $client = new Client($clientIdentifier);
        $client->setName($this->clients[$clientIdentifier]['name']);
        $client->setRedirectUri($this->clients[$clientIdentifier]['redirect_uri']);

        return $client;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {

    }
}
