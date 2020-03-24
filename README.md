## 二维码扫码组件

### 安装

```php
composer require quansitech/qrscanner
```

### 使用
配置文件
```php
host=0.0.0.0
port=2346
expire=300 //二维码过期时间，单位秒，不填则默认300秒
ssl=false //是否开启ssl
ssl_cert=/var/www/fullchain1.pem //指定ssl 证书
ssl_pk=/var/www/privkey1.pem //指定ssl key
```

设置扫码业务逻辑
```php
//这里的BindDeveloper必须实现Qrscanner\BusinessContract接口
class BindDeveloper implements BusinessContract{

    public function run($param){
        //$param为用户设定的参数，后面关于前端配置的部分会说明
        
        //处理客制化的业务逻辑开始

        //处理客制化的业务逻辑结束

        if($someThingError){
            //该错误信息会传递给前端页面，可根据业务需要决定是否展示
            return '错误信息';
        }
        else{
            //业务正常结束，返回true，完成正常的扫码流程
            return true;
        }
    }
}
```

封装服务端程序
```php
<?php
global $argv;
//注册成功扫码后执行的业务逻辑，这里的BindDeveloper就是前面定义的类实例
Business::scanCallBackRegister(new BindDeveloper());

//启动websocket服务端程序
//$argv是命令行传递的参数， 示例中的$argv[1] 接收 start | stop | restart 三个命令
Ws::open($argv[1]);
?>
```

命令行启动示例
```shell
//这里的console.php为前面封装好的脚本
php console.php start --d --config=/var/www/.env
```

停止服务
```php
php console.php stop
```

重启服务程序
```php
php console.php restart --d --config=/var/www/.env
```


### 安装配置二维码react组件
组件安装和使用方法请移步 [传送门](https://github.com/quansitech/react-qrscanner)

