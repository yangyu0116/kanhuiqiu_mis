<?php

class RedisConnect
{
    private static $redis;
    private function __construct(){}
    public static function get_con($shard_data=null)
    {
        $address="127.0.0.1";
        $port=6379;
        if(isset(self::$redis[$address][$port]))
        {
            return self::$redis[$address][$port];
        }
        else
        {
            try
            {
                $redis=new Redis();
                $redis->connect($address, $port);
                self::$redis[$address][$port]=$redis;
                return $redis;
            }
            catch(Exception $e)
            {
                debug($e);
            }

        }
    }
}