## 二维码扫码组件

#### 安装

```php
composer require quansitech/qrscanner
```

#### 使用
启动服务端程序
```php
// -d 启动守护进程  2346 为端口号（必填）  100为二维码的过期时间（必填）
./vendor/bin/qrscanner start -d 2346 100  
```

停止服务
```php
// 2346 为端口号  100为二维码的过期时间
./vendor/bin/qrscanner stop
```

重启服务程序
```php
// -d 启动守护进程 (选填)  2346 为端口号（必填）  100为二维码的过期时间（必填）
./vendor/bin/qrscanner restart -d 2346 100  
```