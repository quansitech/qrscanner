<?php
namespace Qrscanner;

use Workerman\Worker;

class Ws{

    static $map = [];
    static $expire = 300;

    static function open(){
        global $argv;

        if($argv[1] == 'stop'){
            Worker::runAll();
            return;
        }

        if(!is_integer($argv[2])){
            $port = $argv[3];
            self::$expire = $argv[4];
            unset($argv[3]);
            unset($argv[4]);
        }
        else{
            $port = $argv[2];
            self::$expire = $argv[3];
            unset($argv[2]);
            unset($argv[3]);
        }




        // Create a Websocket server
        $ws_worker = new Worker("websocket://0.0.0.0:{$port}");

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