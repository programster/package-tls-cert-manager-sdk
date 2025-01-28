TLS Certificate Manage SDK
==========================
A package to facilitate interfacing with Programster's TLS certificate manager service.


## Example Usage
The example below uses Guzzle for the HTTP message library and factory, because it is the most
commonly used, but you are welcome to use anything that is PSR-7 and PSR-17 compliant.

```php
// Create the client for interfacing with the API:
$messagingClient = new \GuzzleHttp\Client();
$requestFactory = new \GuzzleHttp\Psr7\HttpFactory();

$client = CertManagerClient(
    $messagingClient,
    $requestFactory,
    $myAuthTokenId,
    $myAuthTokenSecret,
    "https://certs.mydomain.com"
);

// Use the client to upload a set of certificates you created:
$response = $client->createCertificateBundle(
    id: "9e138e97-1df3-4f44-9aeb-f54636dae710",
    name: "certificate for my.domain.com",
    cert: "....",
    chain: "...",
    fullchain: "....",
    privateKey: "....",
);

// ... and to retrieve the certificate and write it out somewhere:
$certificateBundle = $client->getCertificateBundle(
    id: "9e138e97-1df3-4f44-9aeb-f54636dae710",
);

file_put_contents('/path/to/fullchain.pem', $certificateBundle->fullchain);
file_put_contents('/path/to/private.pem', $certificateBundle->privateKey);
```


## Testing
To run the tests:
1) Fill in the `testing/TestSettings.php.tmpl` and rename it to `testing/TestSettings.php`
2) Run the tests with `php testing/main.php`
