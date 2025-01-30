<?php

namespace Gugiman\OAuth2\Client\Provider;

use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use League\OAuth2\Client\Provider\AbstractProvider;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

class Ebay extends AbstractProvider
{
    use BearerAuthorizationTrait;

    const ENVIRONMENTURLS = [
        "production" => [
            "baseAuthorizationUrl" => "https://auth.ebay.com/oauth2/authorize",
            "baseAccessTokenUrl" => "https://api.ebay.com/identity/v1/oauth2/token",
            "resourceOwnerDetailsUrl" => "https://apiz.ebay.com/commerce/identity/v1/user",
        ],
        "stage" => [
            "baseAuthorizationUrl" => "https://auth.sandbox.ebay.com/oauth2/authorize",
            "baseAccessTokenUrl" => "https://api.sandbox.ebay.com/identity/v1/oauth2/token",
            "resourceOwnerDetailsUrl" => "https://apiz.sandbox.ebay.com/commerce/identity/v1/user",
        ]
    ];

    protected string $environment = 'production';
    protected array $defaultScopes = [
        'https://api.ebay.com/oauth/api_scope',
        'https://api.ebay.com/oauth/api_scope/sell.marketing.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.marketing',
        'https://api.ebay.com/oauth/api_scope/sell.inventory.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.inventory',
        'https://api.ebay.com/oauth/api_scope/sell.account.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.account',
        'https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.fulfillment',
        'https://api.ebay.com/oauth/api_scope/sell.analytics.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.finances',
        'https://api.ebay.com/oauth/api_scope/sell.payment.dispute',
        'https://api.ebay.com/oauth/api_scope/commerce.identity.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.reputation',
        'https://api.ebay.com/oauth/api_scope/sell.reputation.readonly',
        'https://api.ebay.com/oauth/api_scope/commerce.notification.subscription',
        'https://api.ebay.com/oauth/api_scope/commerce.notification.subscription.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.stores',
        'https://api.ebay.com/oauth/api_scope/sell.stores.readonly',
        'https://api.ebay.com/oauth/scope/sell.edelivery'
    ];

    public function __construct(array $options = [], array $collaborators = ['optionProvider' => new HttpBasicAuthOptionProvider()])
    {
        parent::__construct($options, $collaborators);
    }

    /**
     * Get authorization url to begin OAuth flow
     */
    public function getBaseAuthorizationUrl(): string
    {
        return self::ENVIRONMENTURLS[$this->environment]['baseAuthorizationUrl'];
    }

    /**
     * Get access token url to retrieve token
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return self::ENVIRONMENTURLS[$this->environment]['baseAccessTokenUrl'];
    }

    /**
     * Get provider url to fetch user details
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return self::ENVIRONMENTURLS[$this->environment]['resourceOwnerDetailsUrl'];
    }

    /**
     * Get the default scopes used by this provider.
     */
    protected function getDefaultScopes(): array
    {
        return $this->defaultScopes;
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     */
    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (isset($data['error_id'])) {
            throw  new IdentityProviderException(
                $data['error_description'] ?: $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Creates a Resource Owner.
     */
    protected function createResourceOwner(array $response, AccessToken $token): EbayResourceOwner
    {
        return new EbayResourceOwner($response);
    }
}
