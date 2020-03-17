<?php
namespace Qrscanner\Handle;

use Qrscanner\Handle;
use Qrscanner\Ws;
use Workerman\Lib\Timer;

class BindToken implements Handle{

    public static function handle($connection, $data){

        $token = $data['token'];
        Ws::$map[$token] = $connection;

        Timer::add(Ws::$expire, function($connection, $token){
            if(isset(Ws::$map[$token])){
                unset(Ws::$map[$token]);
                $send_data['type'] = 'TokenInvalid';
                $connection->send(json_encode($send_data));
            }
        }, array($connection, $token), false);
    }
}