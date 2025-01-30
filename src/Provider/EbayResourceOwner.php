<?php

namespace Gugiman\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class EbayResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     */
    protected array $response;

    /**
     * Creates new resource owner.
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     */
    public function getId(): ?string
    {
        return $this->getValueByKey($this->response, 'userId');
    }

    /**
     * Get resource owner username
     */
    public function getUserName(): ?string
    {
        return $this->getValueByKey($this->response, 'username');
    }

    /**
     * Get resource owner accountType
     */
    public function getAccountType(): ?string
    {
        return $this->getValueByKey($this->response, 'accountType');
    }

    /**
     * Get resource owner registrationMarketplaceId
     */
    public function getRegistrationMarketplaceId(): ?string
    {
        return $this->getValueByKey($this->response, 'registrationMarketplaceId');
    }

    /**
     * Return all of the owner details available as an array.
     */
    public function toArray(): array
    {
        return $this->response;
    }
}
