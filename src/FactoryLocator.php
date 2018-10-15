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

final class FactoryLocator
{
    /** @var array  */
    private static $candidates = [
        RequestFactoryInterface::class => [
            'Http\Factory\Diactoros\RequestFactory',
            'Http\Factory\Guzzle\RequestFactory',
            'Http\Factory\Slim\RequestFactory',
            'Nyholm\Psr7\Factory\Psr17Factory',
            'Zend\Diactoros\RequestFactory',
        ],
        ResponseFactoryInterface::class => [
            'Http\Factory\Diactoros\ResponseFactory',
            'Http\Factory\Guzzle\ResponseFactory',
            'Http\Factory\Slim\ResponseFactory',
            'Nyholm\Psr7\Factory\Psr17Factory',
            'Zend\Diactoros\ResponseFactory',
        ],
        ServerRequestFactoryInterface::class => [
            'Http\Factory\Diactoros\ServerRequestFactory',
            'Http\Factory\Guzzle\ServerRequestFactory',
            'Http\Factory\Slim\ServerRequestFactory',
            'Nyholm\Psr7\Factory\Psr17Factory',
            'Zend\Diactoros\ServerRequestFactory',
        ],
        StreamFactoryInterface::class => [
            'Http\Factory\Diactoros\StreamFactory',
            'Http\Factory\Guzzle\StreamFactory',
            'Http\Factory\Slim\StreamFactory',
            'Nyholm\Psr7\Factory\Psr17Factory',
            'Zend\Diactoros\StreamFactory',
        ],
        UploadedFileFactoryInterface::class => [
            'Http\Factory\Diactoros\UploadedFileFactory',
            'Http\Factory\Guzzle\UploadedFileFactory',
            'Http\Factory\Slim\UploadedFileFactory',
            'Nyholm\Psr7\Factory\Psr17Factory',
            'Zend\Diactoros\UploadedFileFactory',
        ],
        UriFactoryInterface::class => [
            'Http\Factory\Diactoros\UriFactory',
            'Http\Factory\Guzzle\UriFactory',
            'Http\Factory\Slim\UriFactory',
            'Nyholm\Psr7\Factory\Psr17Factory',
            'Zend\Diactoros\UriFactory',
        ],
    ];

    /**
     * @throws RuntimeException If no implementation is available
     */
    public static function locate(string $factoryInterface): string
    {
        foreach (self::$candidates[$factoryInterface] ?? [] as $candidate) {
            if (class_exists($candidate) && is_subclass_of($candidate, $factoryInterface, true)) {
                return $candidate;
            }
        }

        throw new RuntimeException("$factoryInterface has no available implementations");
    }

    public static function register(string $factoryInterface, string $factoryImplementation): void
    {
        self::assertValidFactory($factoryInterface, $factoryImplementation);

        array_unshift(self::$candidates[$factoryInterface], $factoryImplementation);
    }

    public static function unregister(string $factoryInterface, string $factoryImplementation): void
    {
        self::assertValidFactory($factoryInterface, $factoryImplementation);

        self::$candidates[$factoryInterface] = array_diff(
            self::$candidates[$factoryInterface],
            [$factoryImplementation]
        );
    }

    /**
     * @throws InvalidArgumentException If the factory is not supported or the implementation is invalid
     */
    private static function assertValidFactory(string $factoryInterface, string $factoryImplementation): void
    {
        if (! isset(self::$candidates[$factoryInterface])) {
            throw new InvalidArgumentException("$factoryInterface is not a supported factory");
        }

        if (! is_subclass_of($factoryImplementation, $factoryInterface, true)) {
            throw new InvalidArgumentException("$factoryImplementation does not implement $factoryInterface");
        }
    }
}
