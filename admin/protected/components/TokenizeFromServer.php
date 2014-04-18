<?php
class TokenizeFromServer
{
    private static $port=11080;
    private static $address="127.0.0.1";
    public static function tokenize($text)
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false)
        {
            throw new Exception("socket_create() failed: reason: " . socket_strerror(socket_last_error()) );
        }
        $result = socket_connect($socket, self::$address, self::$port);
        if($result === false)
        {
           throw new Exception( "socket_connect() failed.Reason: ($result) " . socket_strerror(socket_last_error($socket)));
        }
        $result="";
        socket_write($socket, $text, strlen($text));
        while((false !== ($buf = socket_read($socket, 2048))))
        {
            if ($buf == "") break;
            $result .= $buf;
        }
        socket_close($socket);
        return json_decode($result);
    }
}
