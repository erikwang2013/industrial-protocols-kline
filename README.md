# K-Line 协议包 — OBD-II 诊断，ISO 9141/14230，5-baud 初始化

> [English](README.en.md)

K-Line OBD-II 汽车诊断协议（ISO 9141 / ISO 14230 / KWP2000）。5-baud 慢速初始化握手后切到 10400 bps 通信。

## 安装

```bash
composer require erikwang2013/industrial-protocols-kline
```

## 架构

KLineDriver（串口 UART）→ KLineFrame 帧编解码。5-baud 地址字节初始化握手，KWP2000 诊断帧格式。

## 功能

5-baud 慢速初始化（ECM 唤醒）、ISO 9141/14230/KWP2000 协议栈、OBD-II PID 读取（引擎转速/车速/冷却液温度）、校验和校验、KLineException 异常

## 使用说明

```php
$conn = $kernel->getConnectionManager()->connect('obd-ii');
$conn->read('010C');  // 引擎转速 RPM
$conn->read('010D');  // 车速 km/h
$conn->read('0105');  // 冷却液温度
```

## 配置示例

```php
'devices' => [
    'obd-ii' => [
        'protocol' => 'k-line',
        'device' => '/dev/ttyUSB4',
        'baud_rate' => 10400,
        'timeout' => 5000,
    ],
],
```

## 兼容框架

Laravel / Webman / Hyperf / ThinkPHP / Yii2 / Plain PHP

## 系统要求

- PHP >= 8.1
- K-Line OBD-II 适配器（USB/串口）
- erikwang2013/industrial-protocols-kernel

## License

MIT — Copyright (c) 2026 erik <erik@erik.xyz> — https://erik.xyz
