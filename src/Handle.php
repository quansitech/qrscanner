<?php
namespace Qrscanner;

interface Handle{

    public static function handle($connection, array $data);
}