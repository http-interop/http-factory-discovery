<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;

class HttpFactoryTest extends TestCase
{
    public function testCanDiscoverRequestFactory(): void
    {
        $this->assertSame(HttpFactory::requestFactory(), HttpFactory::requestFactory());
    }

    public function testCanDiscoverResponseFactory(): void
    {
        $this->assertSame(HttpFactory::responseFactory(), HttpFactory::responseFactory());
    }

    public function testCanDiscoverServerRequestFactory(): void
    {
        $this->assertSame(HttpFactory::serverRequestFactory(), HttpFactory::serverRequestFactory());
    }

    public function testCanDiscoverStreamFactory(): void
    {
        $this->assertSame(HttpFactory::streamFactory(), HttpFactory::streamFactory());
    }

    public function testCanDiscoverUploadedFileFactory(): void
    {
        $this->assertSame(HttpFactory::uploadedFileFactory(), HttpFactory::uploadedFileFactory());
    }

    public function testCanDiscoverUriFactory(): void
    {
        $this->assertSame(HttpFactory::uriFactory(), HttpFactory::uriFactory());
    }
}
