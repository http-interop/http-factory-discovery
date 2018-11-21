<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use InvalidArgumentException;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use RuntimeException;

final class HttpFactory
{
    /** @var array */
    private static $factories = [];

    public static function requestFactory(): RequestFactoryInterface
    {
        return self::factoryInstance(RequestFactoryInterface::class);
    }

    public static function responseFactory(): ResponseFactoryInterface
    {
        return self::factoryInstance(ResponseFactoryInterface::class);
    }

    public static function serverRequestFactory(): ServerRequestFactoryInterface
    {
        return self::factoryInstance(ServerRequestFactoryInterface::class);
    }

    public static function streamFactory(): StreamFactoryInterface
    {
        return self::factoryInstance(StreamFactoryInterface::class);
    }

    public static function uploadedFileFactory(): UploadedFileFactoryInterface
    {
        return self::factoryInstance(UploadedFileFactoryInterface::class);
    }

    public static function uriFactory(): UriFactoryInterface
    {
        return self::factoryInstance(UriFactoryInterface::class);
    }

    private static function factoryInstance(string $factoryInterface)
    {
        if (! isset(self::$factories[$factoryInterface])) {
            $factory = FactoryLocator::locate($factoryInterface);
            self::$factories[$factoryInterface] = new $factory();
        }

        return self::$factories[$factoryInterface];
    }

    public static function clearCache(?string $factoryInterface = null): void
    {
        if ($factoryInterface === null) {
            self::$factories = [];
        } else {
            unset(self::$factories[$factoryInterface]);
        }
    }
}
