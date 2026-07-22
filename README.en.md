# K-Line 协议包 — OBD-II 诊断，ISO 9141/14230，5-baud 初始化

> [中文](README.md)

K-Line 协议包 — OBD-II 诊断，ISO 9141/14230，5-baud 初始化。Pure PHP implementation, compatible with 6 PHP runtimes via kernel framework adapters.

## Installation

```bash
composer require erikwang2013/industrial-protocols-kernel erikwang2013/industrial-protocols-kline
```

> Depends on [erikwang2013/industrial-protocols-kernel](https://github.com/erikwang2013/industrial-protocols-kernel) for connection management, protocol registry, coroutine adaptation, event system and more.

## Architecture

Built on kernel SDK interfaces (ProtocolInterface/ConnectorInterface/DriverInterface/FrameInterface), with KlineDriver for transport and KlineConnector for unified ConnectorInterface.

## Features

Complete kline protocol frame encode/decode, driver transport, Connector wrapper, health check, connection strategies (Lazy/Eager/Pooled)

## Supported Frameworks

Compatible with 6 PHP runtimes via kernel framework adapters: Laravel (ServiceProvider+Facade+artisan), Webman (config/plugin auto-discovery+ProtocolProcess), Hyperf (ConfigProvider+DI+KernelFactory), ThinkPHP (services.php+IndustrialProtocolsService), Yii2 (Bootstrap+component), Plain PHP (direct Kernel instantiation)

### Laravel

```php
// AppServiceProvider::boot()
$kernel = app(Kernel::class);
$kernel->getProtocolRegistry()->register(new ModbusProtocol());
$kernel->boot();
$conn = $kernel->getConnectionManager()->connect('device-id');
```

### Webman

Auto-boot via ProtocolProcess on worker start. Configure at `config/plugin/erikwang2013/industrial-protocols-kernel/config/industrial-protocols.php`.

### Hyperf

```php
$kernel = \Hyperf\Context\ApplicationContext::getContainer()->get(Kernel::class);
```

## Usage

```php
$conn = $kernel->getConnectionManager()->connect('obd-ii');
$rpm    = $conn->read('010C');               // PID 0x0C: engine RPM
$speed  = $conn->read('010D');               // PID 0x0D: vehicle speed
```

## Configuration

```php
'devices' => [
    'device-id' => [
        'protocol' => 'kline',
        'host'     => '192.168.1.10',
        'port'     => 0,
        'timeout'  => 3000,
    ],
],
```

## Adapter Vendors

Bosch (K-Line Interface), Vector (OBD-II Interface)

## Requirements

- PHP >= 8.1
- Composer
- erikwang2013/industrial-protocols-kernel

## Related Links

- [Industrial Protocols Main Project](https://github.com/erikwang2013/industrial-protocols)
- [Kernel](https://github.com/erikwang2013/industrial-protocols-kernel)
- [All 42 Protocol Packages](https://github.com/erikwang2013/industrial-protocols#supported-protocols)

## License

MIT — Copyright (c) 2026 erik <erik@erik.xyz> — https://erik.xyz
