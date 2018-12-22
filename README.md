# HTTP Discovery

[![Latest Stable Version](https://img.shields.io/packagist/v/http-interop/http-factory-discovery.svg)](https://packagist.org/packages/http-interop/http-factory-discovery)
[![License](https://img.shields.io/packagist/l/http-interop/http-factory-discovery.svg)](https://github.com/http-interop/http-factory-discovery/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/http-interop/http-factory-discovery.svg)](https://travis-ci.org/http-interop/http-factory-discovery)
[![Code Coverage](https://scrutinizer-ci.com/g/http-interop/http-factory-discovery/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/http-interop/http-factory-discovery/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/http-interop/http-factory-discovery/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/http-interop/http-factory-discovery/?branch=master)

Package for automatic discovery of available implementations providing HTTP
functionality. Allows for fast switching between different implementations with
minimal effort.

Automatic discovery of [HTTP Factories][psr17] and [HTTP Clients][psr18] is
supported.

By default, the following implementations can be discovered:

## HTTP Factory

- [Guzzle](https://github.com/http-interop/http-factory-guzzle)
- [Zend Diactoros](https://github.com/http-interop/http-factory-diactoros)
- [Slim](https://github.com/http-interop/http-factory-slim)
- [Nyholm](https://github.com/Nyholm/psr7)
- [Sunrise](https://github.com/sunrise-php/http-factory)

## HTTP Client

- [Guzzle](https://github.com/php-http/guzzle6-adapter)

Additional implementations [can be registered][add-implementations].

[add-implementations]: #registering-additional-implementations
[psr17]: https://www.php-fig.org/psr/psr-17/
[psr18]: https://www.php-fig.org/psr/psr-18/

## Install

```
composer require http-interop/http-factory-discovery
```

## Usage

### HTTP Factory

```php
use Http\Factory\Discovery\HttpFactory;

/** @var \Psr\Http\Message\RequestFactoryInterface */
$requestFactory = HttpFactory::requestFactory();

/** @var \Psr\Http\Message\ResponseFactoryInterface */
$responseFactory = HttpFactory::responseFactory();

/** @var \Psr\Http\Message\ServerRequestFactoryInterface */
$serverRequestFactory = HttpFactory::serverRequestFactory();

/** @var \Psr\Http\Message\StreamFactoryInterface */
$streamFactory = HttpFactory::streamFactory();

/** @var \Psr\Http\Message\UriFactoryInterface */
$uriFactory = HttpFactory::uriFactory();

/** @var \Psr\Http\Message\UploadedFileFactoryInterface */
$uploadedFileFactory = HttpFactory::uploadedFileFactory();
```

### HTTP Client

```php
use Http\Factory\Discovery\HttpClient;

/** @var \Psr\Http\Client\ClientInterface */
$client = HttpClient::client();
```

### Best Practices

Because this package acts as a [service locator][service-locator] it should be
used to supplement [dependency injection][dependency-injection].

#### HTTP Factory

A prime example for using HTTP Factories would be when writing
[PSR-15 middleware][psr15]:

```php
namespace Acme\Middleware;

use Http\Factory\Discovery\HttpFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class CatchErrors extends MiddlewareInterface
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    public function __construct(
        ResponseFactoryInterface $responseFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->responseFactory = $responseFactory ?? HttpFactory::responseFactory();
        $this->streamFactory = $streamFactory ?? HttpFactory::streamFactory();
    }

    public function process(Request $request, Handler $handler): Response
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $error) {
            $stream = $this->streamFactory->createStream($e->getMessage());

            $response = $this->responseFactory->createResponse(500);
            $response = $response->withHeader('content-type', 'text/plain');
            $response = $response->withBody($stream);

            return $response;
        }
    }
}

```
[service-locator]: https://en.wikipedia.org/wiki/Service_locator_pattern
[dependency-injection]: https://en.wikipedia.org/wiki/Dependency_injection
[psr15]: https://www.php-fig.org/psr/psr-15/

#### HTTP Client

An example for using both HTTP Client and HTTP Factories would be when writing
functionality sending HTTP requests:

```php
namespace Acme;

use Http\Factory\Discovery\HttpClient;
use Http\Factory\Discovery\HttpFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class Api
{
    /** @var ClientInterface */
    private $client;

    /** @var RequestFactoryInterface */
    private $requestFactory;

    public function __construct(
        ClientInterface $client = null,
        RequestFactoryInterface $requestFactory = null
    ) {
        $this->client = $client ?? HttpClient::client();
        $this->requestFactory = $requestFactory ?? HttpFactory::requestFactory();
    }

    public function query(): string
    {
        $request = $this->requestFactory->createRequest('GET', 'http://acme.com/api');

        return $this->client->sendRequest($request)->getBody()->getContents();
    }
}

```

### Registering Additional Implementations

Additional implementations can be registered:

#### HTTP Factory

```php
use Acme\RequestFactory;
use Http\Factory\Discovery\FactoryLocator;
use Psr\Http\Message\RequestFactoryInterface;

FactoryLocator::register(RequestFactoryInterface::class, RequestFactory::class);
```

#### HTTP Client

```php
use Acme\Client;
use Http\Factory\Discovery\ClientLocator;
use Psr\Http\Client\ClientInterface;

ClientLocator::register(ClientInterface::class, Client::class);
```

Implementations can also be unregistered, if you prefer not to use them:

#### HTTP Factory

```php
use Http\Factory\Discovery\FactoryLocator;
use Http\Factory\Guzzle\UriFactory;
use Psr\Http\Message\UriFactoryInterface;

FactoryLocator::unregister(UriFactoryInterface::class, UriFactory::class);
```

#### HTTP Client

```php
use Http\Factory\Discovery\ClientLocator;
use Http\Adapter\Guzzle6\Client;
use Psr\Http\Client\ClientInterface;

ClientLocator::unregister(ClientInterface::class, Client::class);
```

### Clearing Cache

The cache of discovered implementations can be cleared:

#### HTTP Factory

```php
use Http\Factory\Discovery\HttpFactory;
use Psr\Http\Message\UriFactoryInterface;

// Clear a single interface
HttpFactory::clearCache(UriFactoryInterface::class);

// Clear all interfaces
HttpFactory::clearCache();
```

#### HTTP Client

```php
use Http\Factory\Discovery\HttpClient;
use Psr\Http\Client\ClientInterface;

// Clear a single interface
HttpClient::clearCache(ClientInterface::class);

// Clear all interfaces
HttpClient::clearCache();
```

_Note: Cache is automatically cleared when `FactoryLocator::unregister()` or
`ClientLocator::unregister()` is called._
