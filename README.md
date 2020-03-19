## 二维码扫码组件

#### 安装

```php
composer require quansitech/qrscanner
```

#### 使用
启动服务端程序
```php
// --d 启动守护进程(选填)  --config=配置文件绝对路径(选填) 如果不填 则host默认0.0.0.0 port默认2346 ssl默认不启动
./vendor/bin/qrscanner start --d --config=/var/www/.env
```

停止服务
```php
// 2346 为端口号  100为二维码的过期时间
./vendor/bin/qrscanner stop
```

重启服务程序
```php
// --d 启动守护进程 (选填)  --config=配置文件绝对路径(选填)
./vendor/bin/qrscanner restart --d --config=/var/www/.env
```

配置文件
```php
host=0.0.0.0
port=2346
expire=300 //二维码过期时间，单位秒，不填则默认300秒
ssl=false //是否开启ssl
ssl_cert=/var/www/fullchain1.pem //指定ssl 证书
ssl_pk=/var/www/privkey1.pem //指定ssl key
```