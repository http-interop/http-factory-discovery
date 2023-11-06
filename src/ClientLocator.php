<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use Psr\Http\Client\ClientInterface;

final class ClientLocator extends DiscoveryLocator
{
    /** @var array  */
    protected static $candidates = [
        ClientInterface::class => [
            'Http\Adapter\Guzzle7\Client',
            'Http\Adapter\Guzzle6\Client',
            'Http\Adapter\Guzzle5\Client',
            'Http\Client\Curl\Client',
            'Http\Client\Socket\Client',
            'Http\Adapter\React\Client',
            'Http\Adapter\Buzz\Client',
            'Http\Adapter\Cake\Client',
            'Http\Adapter\Zend\Client',
            'Http\Adapter\Artax\Client',
        ],
    ];
    
    protected static function clearCache(?string $interface = null): void
    {
        HttpClient::clearCache($interface);
    }
}
