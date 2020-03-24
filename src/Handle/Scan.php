<?php
namespace Qrscanner\Handle;

use Qrscanner\Business;
use Qrscanner\Handle;
use Qrscanner\Ws;

class Scan implements Handle{

    public static function handle($connection, $data){
        $token = $data['token'];

        if(isset(Ws::$map[$token])){

            $pipePath = "/tmp/" . uniqid() . ".pipe";
            if( !file_exists( $pipePath ) ){
                if( !posix_mkfifo( $pipePath, 0666 ) ){
                    exit('make pipe false!' . PHP_EOL);
                }
            }

            $pid = pcntl_fork();
            if($pid == -1){
                throw new \Exception('could not fork');
                exit(-1);
            }
            else if($pid){
                $file = fopen( $pipePath, 'r' );
                $result =  fread( $file, 8192 );
                fclose($file);
                unlink($pipePath);

                pcntl_wait($status);
            }
            else{
                $result = Business::$scanCallBack->run($data['param']);
                if($result !== true){
                    $result = [ 'status' => 0, 'error' => $result];
                }
                else{
                    $result = [ 'status' => 1];
                }
                $file = fopen( $pipePath, 'w' );
                fwrite( $file, json_encode($result));
                exit(0);
            }

            $to_connection = Ws::$map[$token];
            $result = json_decode($result, true);
            if(!$result['status']){
                $send_data['type'] = 'Scan';
                $send_data['status'] = 0;
                $send_data['error'] = $result['error'];
                $connection->send(json_encode($send_data));
            }
            else{
                unset(Ws::$map[$token]);
                $send_data['type'] = 'Scan';
                $send_data['status'] = 1;
                $send_data['error'] = '';
                $to_connection->send(json_encode($send_data));
                $connection->send(json_encode($send_data));
            }

        }
        else {
            $send_data['type'] = 'Scan';
            $send_data['status'] = 0;
            $send_data['error'] = '二维码已失效';
            $connection->send(json_encode($send_data));
        }

    }
}