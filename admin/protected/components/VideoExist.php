<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangjianzhou
 * Date: 13-5-7
 * Time: 上午10:32
 * To change this template use File | Settings | File Templates.
 */
require_once("RedisDAL.php");
class VideoExist
{
    private static $table="has_got_video_url_md5";
    public static function check_exist($url)
    {
        $redis=new RedisDAL();
        $md5=md5($url);
        return $redis->get_hash_value(self::$table,$md5);
    }
    public static function set_exist($url,$video_id)
    {
        $redis=new RedisDAL();
        $md5=md5($url);
        return $redis->set_hash_value(self::$table,$md5,$video_id);
    }
}