<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangjianzhou
 * Date: 12-12-13
 * Time: 下午12:33
 * To change this template use File | Settings | File Templates.
 */
require_once("RedisConnect.php");
class RedisDAL
{
    private $redis;
    public function __construct($server_no=0)
    {
        $this->redis=RedisConnect::get_con($server_no);
    }
    public function get_hash_value($dataid,$key)
    {
        return $this->redis->hget($dataid,$key);
    }
    public function set_hash_value($dataid,$key,$value,$time=NULL)
    {
        $data=array();
        $data[$key]=$value;
        $temp=$this->redis->hmset($dataid,$data) ;
        if($time&&$temp)
        {
            $this->redis->expireAt($dataid, time()+$time);
        }
        return $temp;
    }
    public function increase_hash_value($dataid,$key,$skip=1)
    {
        if(is_int($skip))
        {
            return $this->redis->hIncrBy($dataid,$key,$skip);
        }
    }
    public function update_hash_value($dataid,$key,$value,$time=NULL)
    {
        if($this->is_in_hash($dataid,$key))
        {
            $this->clear_hash_value($dataid,$key);
            return $this->set_hash_value($dataid,$key,$value,$time);
        }
        else
        {
            return $this->set_hash_value($dataid,$key,$value,$time);
        }
    }
    public function get_hash_count($dataid)
    {
        return $this->redis->hLen($dataid);
    }
    public function clear_hash_value($dataid,$key)
    {
        return $this->redis->hdel($dataid,$key);
    }
    public function is_in_hash($dataid,$key)
    {
        return $this->redis->hexists ($dataid,$key);
    }
    public function drop_table($table_name)
    {
        return $this->redis->del($table_name);
    }
    #获取一个哈希表的所有key
    public function get_hashtable_keys($table_name)
    {
        return $this->redis->hKeys($table_name);
    }
    #获取一个哈希表中的所有key以及对应的value
    public function  get_hashtable_values($table_name)
    {
        return $this->redis->hVals($table_name);
    }
    #向哈希表中批量插入元素
    public function set_many_in_hash_table($table_name,$mapping=array())
    {
        return $this->redis->hMset($table_name,$mapping);
    }
    #从哈希表中批量获取元素
    public function get_many_from_hash_table($table_name,$keys_collection=array())
    {
        return $this->redis->hmGet($table_name,$keys_collection);
    }
    public function set_expire_time($key,$time_out)
    {
        return $this->redis->expireAt($key, time()+$time_out);
    }
    #向为key的队列中添加元素
    public function add_value_to_queue($key,$value,$pos="left",$only_once=false)
    {
        if($pos=="left")
        {
            if($only_once)
            {
                return $this->redis->lPushx($key, $value);
            }
            return $this->redis->lPush($key,$value) ;
        }
        else
        {
            if($only_once)
            {
                return $this->redis->rPushx($key, $value);
            }
            return $this->redis->rPush($key,$value) ;
        }
    }
    #弹出为key的队列中的一个元素
    public function get_one_value_from_queue($key,$pos="left")
    {
        if($pos=="left")
        {
            return $this->redis->lPop($key)  ;
        }
        else
        {
            return $this->redis->rPop($key) ;
        }
    }
    #获取为$key的队列中的元素个数
    public function get_count_of_queque($key)
    {
        return $this->redis->lSize($key) ;
    }
    #截取queue中的队列长度
    public function trim_queue($key,$start,$end)
    {
        return $this->redis->lTrim($key, $start, $end);
    }
    public function remove_one_from_queue($key,$value,$count=0)
    {
        return $this->redis->lRem($key,$value,$count);
    }
    public function get_some_from_queue($key,$sta,$count=-1)
    {
        return $this->redis->lRange($key,$sta,$count);
    }
    public function add_one_to_sort_set($key,$value,$score)
    {
        return $this->redis->zAdd($key,$score,$value);
    }
    public function get_some_from_sort_set($key,$start,$end,$reverse,$with_score=true)
    {
        if($reverse)
        {
            return $this->redis->zRevRange($key,$start,$end,$with_score);
        }
        else
        {
            return $this->redis->zRange($key,$start,$end,$with_score);
        }
    }
    public function get_count_of_sort_set($key)
    {
        return (int)$this->redis->zSize($key);
    }
    public function delete_one_from_sort_set($key,$value)
    {
        return $this->redis->zDelete($key, $value);
    }
    public function delete_some_from_sort_set($key,$value_sta,$value_end)
    {
        return $this->redis->zRemRangeByScore($key,$value_sta,$value_end);
    }


}