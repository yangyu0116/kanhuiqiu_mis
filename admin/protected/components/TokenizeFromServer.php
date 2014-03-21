<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangjianzhou
 * Date: 13-4-27
 * Time: 下午6:09
 * To change this template use File | Settings | File Templates.
 */
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
var_dump(TokenizeFromServer::tokenize("天生好胜的麦一敏被公司裁员后，无意中发现大妗姐（意为：结婚时专门侍候新娘的人）这个行业工作简单兼收入可观，便一心向金牌大妗姐庄思甜拜师学艺"));
