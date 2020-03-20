<?php
namespace Qrscanner\Handle;

use Qrscanner\Handle;
use Qrscanner\Ws;

class Scan implements Handle{

    public static function handle($connection, $data){
        $token = $data['token'];

        if(isset(Ws::$map[$token])){

            $to_connection = Ws::$map[$token];

            unset(Ws::$map[$token]);
            $send_data['type'] = 'Scan';
            $send_data['status'] = 1;
            $send_data['error'] = '';
            $to_connection->send(json_encode($send_data));
            $connection->send(json_encode($send_data));

        }
        else {
            $send_data['type'] = 'Scan';
            $send_data['status'] = 0;
            $send_data['error'] = '二维码已失效';
            $connection->send(json_encode($send_data));
        }

    }

//    private static function getConnection($worker, $id){
//        foreach($worker->connections as $connection){
//            if($connection->id == $id){
//                return $connection;
//            }
//        }
//        return null;
//    }
}