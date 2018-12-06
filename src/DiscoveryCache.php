<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use RuntimeException;

abstract class DiscoveryCache
{
    /** @var array */
    private static $cache = [];

    protected static function instance(string $interface)
    {
        if (! isset(self::$cache[$interface])) {
            $implementation = static::locate($interface);
            self::$cache[$interface] = new $implementation();
        }

        return self::$cache[$interface];
    }

    public static function clearCache(?string $interface = null): void
    {
        if ($interface === null) {
            self::$cache = [];
        } else {
            unset(self::$cache[$interface]);
        }
    }

    /**
     * @throws RuntimeException If no implementation is available
     */
    abstract protected static function locate(string $interface): string;
}
