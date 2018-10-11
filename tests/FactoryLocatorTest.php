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

class FactoryLocatorTest extends TestCase
{
    public function dataFactoryInterfaces(): array
    {
        return [
            RequestFactoryInterface::class => [RequestFactoryInterface::class],
            ResponseFactoryInterface::class => [ResponseFactoryInterface::class],
            ServerRequestFactoryInterface::class => [ServerRequestFactoryInterface::class],
            StreamFactoryInterface::class => [StreamFactoryInterface::class],
            UriFactoryInterface::class => [UriFactoryInterface::class],
            UploadedFileFactoryInterface::class => [UploadedFileFactoryInterface::class],
        ];
    }

    /**
     * @dataProvider dataFactoryInterfaces
     */
    public function testCanRegisterAdditionalFactoriesForLocation(string $interface): void
    {
        $factory = $this->getMockBuilder($interface)->getMock();
        $factoryClass = get_class($factory);

        FactoryLocator::register($interface, $factoryClass);

        $this->assertSame($factoryClass, FactoryLocator::locate($interface));

        FactoryLocator::unregister($interface, $factoryClass);

        $this->assertNotSame($factoryClass, FactoryLocator::locate($interface));
    }

    public function testCannotRegisterInvalidFactory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('foo');

        FactoryLocator::register('foo', self::class);
    }

    public function testCannotRegisterInvalidFactoryImlementation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::class);

        FactoryLocator::register(RequestFactoryInterface::class, self::class);
    }

    public function testCannotUnregisterInvalidFactory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('foo');

        FactoryLocator::unregister('foo', self::class);
    }

    public function testCannotUnregisterInvalidFactoryImplementation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::class);

        FactoryLocator::unregister(RequestFactoryInterface::class, self::class);
    }

    public function testCannotLocateInvalidFactory(): void
    {
        $this->expectException(\RuntimeException::class);

        FactoryLocator::locate('foo');
    }
}
