<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

final class FactoryLocator extends DiscoveryLocator
{
    /** @var array  */
    protected static $candidates = [
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
    
    protected static function clearCache(?string $interface = null): void
    {
        HttpFactory::clearCache($interface);
    }
}
