# Ebay Provider for OAuth 2.0 Client

This package provides Ebay OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

```
composer require gugiman/oauth2-ebay
```

## Usage

```php
$ebayProvider = new \Gugiman\OAuth2\Client\Provider\Ebay([
    'clientId'                => 'yourId',          // The client ID assigned to you by Ebay
    'clientSecret'            => 'yourSecret',      // The client password assigned to you by Ebay
    'redirectUri'             => 'yourRedirectUri'  // The return URL you specified for your app on Ebay
]);

// Get authorization code
if (!isset($_GET['code'])) {
    // Get authorization URL
    $authorizationUrl = $ebayProvider->getAuthorizationUrl();

    // Get state and store it to the session
    $_SESSION['oauth2state'] = $ebayProvider->getState();

    // Redirect user to authorization URL
    header('Location: ' . $authorizationUrl);
    exit;
// Check for errors
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    exit('Invalid state');
} else {
    // Get access token
    try {
        $accessToken = $ebayProvider->getAccessToken(
            'authorization_code',
            [
                'code' => $_GET['code']
            ]
        );

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo 'Access Token: ' . $tokens->getToken() . "<br>";
        echo 'Refresh Token: ' . $tokens->getRefreshToken() . "<br>";
        echo 'Expired in: ' . $tokens->getExpires() . "<br>";
        echo 'Already expired? ' . ($tokens->hasExpired() ? 'expired' : 'not expired') . "<br>";

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit($e->getMessage());
    }

    // Get resource owner
    try {
        $resourceOwner = $ebayProvider->getResourceOwner($accessToken);
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit($e->getMessage());
    }

    // Now you can store the results to session etc.
    $_SESSION['accessToken'] = $accessToken;
    $_SESSION['resourceOwner'] = $resourceOwner;

    var_dump(
        $resourceOwner->getId(),
        $resourceOwner->getUserName(),
        $resourceOwner->getAccountType(),
        $resourceOwner->getRegistrationMarketplaceId(),
        $resourceOwner->toArray()
    );
}
```

For more information see the PHP League's general usage examples.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](https://github.com/Gugiman/oauth2-ebay/blob/master/LICENSE) for more information.
