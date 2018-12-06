<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

class ClientLocatorTest extends TestCase
{
    public function dataClientInterfaces(): array
    {
        return [
            ClientInterface::class => [ClientInterface::class],
        ];
    }

    /**
     * @dataProvider dataClientInterfaces
     */
    public function testCanRegisterAdditionalClientsForLocation(string $interface): void
    {
        $client = $this->getMockBuilder($interface)->getMock();
        $clientClass = get_class($client);

        ClientLocator::register($interface, $clientClass);

        $this->assertSame($clientClass, ClientLocator::locate($interface));

        ClientLocator::unregister($interface, $clientClass);

        $this->assertNotSame($clientClass, ClientLocator::locate($interface));
    }

    public function testCannotRegisterInvalidClient(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('foo');

        ClientLocator::register('foo', self::class);
    }

    public function testCannotRegisterInvalidClientImlementation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::class);

        ClientLocator::register(ClientInterface::class, self::class);
    }

    public function testCannotUnregisterInvalidClient(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('foo');

        ClientLocator::unregister('foo', self::class);
    }

    public function testCannotUnregisterInvalidClientImplementation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::class);

        ClientLocator::unregister(ClientInterface::class, self::class);
    }

    public function testCannotLocateInvalidClient(): void
    {
        $this->expectException(\RuntimeException::class);

        ClientLocator::locate('foo');
    }
}
