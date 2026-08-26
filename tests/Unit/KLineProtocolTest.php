<?php

/*
 * Copyright (c) 2026 erik <erik@erik.xyz> — https://erik.xyz
 */

namespace Erikwang2013\IndustrialProtocols\KLine\Tests\Unit;

use Erikwang2013\IndustrialProtocols\KLine\KLineConnector;
use Erikwang2013\IndustrialProtocols\KLine\KLineProtocol;
use PHPUnit\Framework\TestCase;

class KLineProtocolTest extends TestCase
{
    public function testProtocolMetadata(): void
    {
        $protocol = new KLineProtocol();
        $this->assertSame('k-line', $protocol->getName());
        $this->assertSame('1.1.1', $protocol->getVersion());
        $this->assertSame(0, $protocol->getDefaultPort());
        $this->assertSame(['iso9141', 'iso14230', 'kwp2000'], $protocol->getSupportedVariants());
    }

    public function testCreateConnectorReturnsKLineConnector(): void
    {
        $connector = (new KLineProtocol())->createConnector([
            'device' => '/dev/ttyUSB0',
            'baud_rate' => 10400,
            'timeout' => 5000,
        ]);
        $this->assertInstanceOf(KLineConnector::class, $connector);
        $this->assertFalse($connector->isConnected());
    }

    public function testCreateConnectorWithEmptyConfig(): void
    {
        $connector = (new KLineProtocol())->createConnector([]);
        $this->assertInstanceOf(KLineConnector::class, $connector);
    }

    public function testConnectorHealthBeforeConnect(): void
    {
        $connector = (new KLineProtocol())->createConnector([]);
        $this->assertSame(\Erikwang2013\IndustrialProtocols\Connection\ConnectionState::CLOSED, $connector->getHealth()->state);
    }

    public function testConnectorConnectFailsOnMissingDevice(): void
    {
        $connector = (new KLineProtocol())->createConnector([
            'device' => '/nonexistent/ttyUSB99',
            'timeout' => 100,
        ]);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot open K-Line serial device');
        $connector->connect();
    }
}
