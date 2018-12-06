<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use InvalidArgumentException;
use RuntimeException;

abstract class DiscoveryLocator
{
    /** @var array  */
    protected static $candidates = [];

    /**
     * @throws RuntimeException If no implementation is available
     */
    public static function locate(string $interface): string
    {
        foreach (static::$candidates[$interface] ?? [] as $candidate) {
            if (class_exists($candidate) && is_subclass_of($candidate, $interface, true)) {
                return $candidate;
            }
        }

        throw new RuntimeException("$interface has no available implementations");
    }

    public static function register(string $interface, string $implementation): void
    {
        self::assertValidImplementation($interface, $implementation);

        array_unshift(static::$candidates[$interface], $implementation);
    }

    public static function unregister(string $interface, string $implementation): void
    {
        self::assertValidImplementation($interface, $implementation);

        static::$candidates[$interface] = array_diff(
            static::$candidates[$interface],
            [$implementation]
        );

        static::clearCache($interface);
    }

    /**
     * @throws InvalidArgumentException If the factory is not supported or the implementation is invalid
     */
    private static function assertValidImplementation(string $interface, string $implementation): void
    {
        if (! isset(static::$candidates[$interface])) {
            throw new InvalidArgumentException("$interface is not a supported factory");
        }

        if (! is_subclass_of($implementation, $interface, true)) {
            throw new InvalidArgumentException("$implementation does not implement $interface");
        }
    }

    abstract protected static function clearCache(?string $interface = null): void;
}
