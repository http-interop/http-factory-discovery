<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

final class HttpFactory extends DiscoveryCache
{
    public static function requestFactory(): RequestFactoryInterface
    {
        return self::makeInstance(RequestFactoryInterface::class);
    }

    public static function responseFactory(): ResponseFactoryInterface
    {
        return self::makeInstance(ResponseFactoryInterface::class);
    }

    public static function serverRequestFactory(): ServerRequestFactoryInterface
    {
        return self::makeInstance(ServerRequestFactoryInterface::class);
    }

    public static function streamFactory(): StreamFactoryInterface
    {
        return self::makeInstance(StreamFactoryInterface::class);
    }

    public static function uploadedFileFactory(): UploadedFileFactoryInterface
    {
        return self::makeInstance(UploadedFileFactoryInterface::class);
    }

    public static function uriFactory(): UriFactoryInterface
    {
        return self::makeInstance(UriFactoryInterface::class);
    }

    protected static function locate(string $interface): string
    {
        return FactoryLocator::locate($interface);
    }
}
