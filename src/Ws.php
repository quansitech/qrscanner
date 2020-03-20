<?php
namespace Qrscanner;

use Dotenv\Dotenv;
use Workerman\Worker;

class Ws{

    static $map = [];
    static $expire = 300;

    static $default = [
        'port' => 2346,
        'host' => '0.0.0.0',
        'ssl' => false
    ];

    static protected function parseConfig(){
        global $argv;

        $config['method'] = $argv[1];
        unset($argv[1]);
        foreach($argv as $v){
            if(strpos($v, '--') === 0){
                $v = ltrim($v, "--");
                if(strpos($v, '=') !== false){
                    list($key, $value) = explode("=", $v);
                }
                else{
                    $key = $v;
                    $value = null;
                }
                $config[$key] = is_null($value) ? true : $value;
            }
        }

        return $config;
    }

    static function open(){
        global $argv;

        $config = self::parseConfig();
        $argv[1] = $config['method'];

        if(isset($config['d']) && $config['d'] === true){
            $argv[2] = '-d';
        }

        if($config['method'] == 'stop'){
            Worker::runAll();
            return;
        }

        if(isset($config['config'])){
            $path = dirname($config['config']);
            $file = str_replace($path . '/', '', $config['config']);

            $dotenv = Dotenv::create($path, $file);
            $dotenv->load();

            $host = env('host', self::$default['host']);
            $port = env('port', self::$default['port']);
            $ssl = env('ssl', self::$default['ssl']);
            self::$expire = env('expire', self::$expire);
        }
        else{
            $host = self::$default['host'];
            $port = self::$default['port'];
            $ssl = self::$default['ssl'];
        }
        $ws_worker = null;
        if($ssl){
            $context = array(
                'ssl' => array(
                    'local_cert'                 => env('ssl_cert'),
                    'local_pk'                   => env('ssl_pk'),
                    'verify_peer'                => false,
                    'allow_self_signed' => true
                )
            );

            $ws_worker = new Worker("websocket://{$host}:{$port}", $context);
            $ws_worker->transport = 'ssl';
        }
        else{
            $ws_worker = new Worker("websocket://{$host}:{$port}");
        }

        $ws_worker->onMessage = function($connection, $data)
        {
            $data = json_decode($data, true);
            $type = $data['type'];
            $class = "Qrscanner\\Handle\\$type";
            if(!class_exists($class)){
                throw new \Exception("不存在的操作");
            }

            $class::handle($connection, $data['data']);

        };

        $ws_worker->onClose = function($connection)
        {

        };

        Worker::runAll();
    }
}