HTTP Factory Discovery
======================

[![Latest Stable Version](https://img.shields.io/packagist/v/http-interop/http-factory-discovery.svg)](https://packagist.org/packages/http-interop/http-factory-discovery)
[![License](https://img.shields.io/packagist/l/http-interop/http-factory-discovery.svg)](https://github.com/http-interop/http-factory-discovery/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/http-interop/http-factory-discovery.svg)](https://travis-ci.org/http-interop/http-factory-discovery)
[![Code Coverage](https://scrutinizer-ci.com/g/http-interop/http-factory-discovery/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/http-interop/http-factory-discovery/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/http-interop/http-factory-discovery/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/http-interop/http-factory-discovery/?branch=master)

HTTP Factory service location for available [HTTP factories][http-factory-implementations].
Allows for fast switching between different HTTP message implementations with
minimal effort.

By default, the following factory implementations can be discovered:

- [Guzzle](https://github.com/http-interop/http-factory-guzzle)
- [Zend Diactoros](https://github.com/http-interop/http-factory-diactoros)
- [Slim](https://github.com/http-interop/http-factory-slim)
- [Nyholm](https://github.com/Nyholm/psr7)

Additional implementations [can be registered](#Registering-additional-factories).

[http-factory-implementations]: https://packagist.org/providers/psr/http-factory-implementation

## Install

```
composer require http-interop/http-factory-discovery
```

## Usage

```php
use Http\Factory\Discovery\Discovery;

/** @var \Psr\Http\Message\RequestFactoryInterface */
$requestFactory = HttpFactory::requestFactory();

/** @var \Psr\Http\Message\ResponseFactoryInterface */
$responseFactory = HttpFactory::responseFactory();

/** @var \Psr\Http\Message\ServerRequestFactoryInterface */
$requestFactory = HttpFactory::requestFactory();

/** @var \Psr\Http\Message\StreamFactoryInterface */
$streamFactory = HttpFactory::streamFactory();

/** @var \Psr\Http\Message\UriFactoryInterface */
$uriFactory = HttpFactory::uriFactory();

/** @var \Psr\Http\Message\UploadedFileFactoryInterface */
$uploadedFileFactory = HttpFactory::uploadedFileFactory();
```

### Best Practices

Because this package acts as a [service locator][service-locator] it should be
used to supplement [dependency injection][dependency-injection].

A prime example would be when writing [PSR-15 middleware][psr15]:

```php
namespace Acme\Middleware;

use Http\Factory\Discovery\Factory;
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

### Registering Additional Factories

Additional factories can be registered:

```php
use Acme\RequestFactory;
use Http\Factory\Discovery\FactoryLocator;
use Psr\Http\Message\RequestFactoryInterface;

FactoryLocator::register(RequestFactoryInterface::class, RequestFactory::class);
```

Factories can also be unregistered, if you prefer not to use them:

```php
use Http\Factory\Discovery\FactoryLocator;
use Http\Factory\Guzzle\UriFactory;
use Psr\Http\Message\UriFactoryInterface;

FactoryLocator::unregister(UriFactoryInterface::class, UriFactory::class);
```
