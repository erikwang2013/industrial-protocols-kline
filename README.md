# K-Line 协议包 — OBD-II 诊断，ISO 9141/14230，5-baud 初始化

> [English](README.en.md)

K-Line 协议包 — OBD-II 诊断，ISO 9141/14230，5-baud 初始化。纯 PHP 实现，通过内核框架适配器兼容 6 种 PHP 运行时环境。

## 安装

```bash
composer require erikwang2013/industrial-protocols-kernel erikwang2013/industrial-protocols-kline
```

> 本包依赖 [erikwang2013/industrial-protocols-kernel](https://github.com/erikwang2013/industrial-protocols-kernel)，内核提供连接管理、协议注册、协程适配、事件系统等基础设施。

## 架构

基于内核 SDK 接口（ProtocolInterface/ConnectorInterface/DriverInterface/FrameInterface）构建，通过 KlineDriver 实现底层通信，KlineConnector 封装为统一 ConnectorInterface。

## 功能

完整的 kline 协议帧编解码、驱动层通信、Connector 封装、健康检查、连接策略支持（Lazy/Eager/Pooled）

## 支持的框架

本包通过内核的框架适配器兼容以下 6 种 PHP 运行时环境：Laravel (ServiceProvider+Facade+artisan)、Webman (config/plugin 自动发现+ProtocolProcess)、Hyperf (ConfigProvider+DI+KernelFactory)、ThinkPHP (services.php+IndustrialProtocolsService)、Yii2 (Bootstrap+组件注册)、Plain PHP (直接实例化 Kernel)

### Laravel 示例

```php
use Erikwang2013\IndustrialProtocols\Kernel;
use Erikwang2013\IndustrialProtocols\Modbus\ModbusProtocol;

// AppServiceProvider::boot()
$kernel = app(Kernel::class);
$kernel->getProtocolRegistry()->register(new ModbusProtocol());
$kernel->boot();

$conn = $kernel->getConnectionManager()->connect('device-id');
$result = $conn->read('address');

// 或使用 Facade
\Erikwang2013\IndustrialProtocols\Framework\Laravel\IndustrialProtocolsFacade::connect('device-id')->read('address');
```

### Webman 示例

Worker 启动时 ProtocolProcess 自动初始化。配置 `config/plugin/erikwang2013/industrial-protocols-kernel/config/industrial-protocols.php`。

### Hyperf 示例

```php
$kernel = \Hyperf\Context\ApplicationContext::getContainer()->get(Kernel::class);
$conn = $kernel->getConnectionManager()->connect('device-id');
```

## 使用说明

```php
$conn = $kernel->getConnectionManager()->connect('obd-ii');
$rpm    = $conn->read('010C');               // PID 0x0C: 引擎转速
$speed  = $conn->read('010D');               // PID 0x0D: 车速
$coolant = $conn->read('0105');              // PID 0x05: 冷却液温度
```

## 配置示例

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

## 适配厂商

Bosch (K-Line Interface)、Vector (OBD-II Interface)

## 系统要求

- PHP >= 8.1
- Composer
- erikwang2013/industrial-protocols-kernel

## 相关链接

- [Industrial Protocols 主项目](https://github.com/erikwang2013/industrial-protocols)
- [Kernel 内核](https://github.com/erikwang2013/industrial-protocols-kernel)
- [全部 42 个协议包](https://github.com/erikwang2013/industrial-protocols#支持的协议)

## License

MIT — Copyright (c) 2026 erik <erik@erik.xyz> — https://erik.xyz
