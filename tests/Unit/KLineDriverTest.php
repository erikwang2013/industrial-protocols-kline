<?php

/*
 * Copyright (c) 2026 erik <erik@erik.xyz> — https://erik.xyz
 */

namespace Erikwang2013\IndustrialProtocols\KLine\Tests\Unit;

use Erikwang2013\IndustrialProtocols\KLine\Driver\KLineDriver;
use Erikwang2013\IndustrialProtocols\KLine\Frame\KLineFrame;
use PHPUnit\Framework\TestCase;

/**
 * K-Line driver is a serial-UART driver (no TCP). A php://memory pseudo-device
 * opens like a real device; connect() fails at the 5-baud init handshake
 * because no ECU sync byte is present. Validation paths need no hardware.
 */
class KLineDriverTest extends TestCase
{
    public function testSendRejectsNonKLineFrame(): void
    {
        $driver = new KLineDriver('php://memory', 10400, 0.1);
        $this->expectException(\InvalidArgumentException::class);
        $driver->send($this->createStub(\Erikwang2013\IndustrialProtocols\Protocol\FrameInterface::class));
    }

    public function testSendAsyncThrows(): void
    {
        $driver = new KLineDriver('php://memory', 10400, 0.1);
        $this->expectException(\RuntimeException::class);
        $driver->sendAsync(new KLineFrame());
    }

    public function testSupportsAsyncIsFalse(): void
    {
        $this->assertFalse((new KLineDriver())->supportsAsync());
    }

    public function testConnectFailsWithoutEcuSyncByte(): void
    {
        // php://memory opens, but the 5-baud init handshake finds no sync
        // byte 0x55 from an ECU, so connect() must throw
        $driver = new KLineDriver('php://memory', 10400, 0.5);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('timeout reading byte');
        $driver->connect();
    }

    public function testConnectFailsOnMissingDevice(): void
    {
        $driver = new KLineDriver('/nonexistent/ttyUSB99', 10400, 0.1);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot open K-Line serial device');
        $driver->connect();
    }
}
