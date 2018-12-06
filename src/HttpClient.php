<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use Psr\Http\Client\ClientInterface;

final class HttpClient extends DiscoveryCache
{
    public static function client(): ClientInterface
    {
        return self::makeInstance(ClientInterface::class);
    }

    protected static function locate(string $interface): string
    {
        return ClientLocator::locate($interface);
    }
}
