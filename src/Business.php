<?php
namespace Qrscanner;

class Business{

    static $scanCallBack;

    static function scanCallBackRegister($business){
        if(!($business instanceof BusinessContract)){
            throw new \Exception('必须实现' . BusinessContract::class);
        }

        self::$scanCallBack = $business;
    }
}