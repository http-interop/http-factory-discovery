<?php
declare(strict_types=1);

namespace Http\Factory\Discovery;

use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    public function testCanDiscoverClient(): void
    {
        $this->assertSame(HttpClient::client(), HttpClient::client());
    }
}
