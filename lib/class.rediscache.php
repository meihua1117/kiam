<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
class RedisCache
{
    protected $default_host = "127.0.0.1";
    protected $default_port = 6379;
    protected $is_debug = false;

    protected $debug_info = array();
    protected $data_store;
    protected $default_key_prefix = "kiam_cache:";
    protected $time_elapsed_last;
    protected $use_redis = true;


    public function __construct($use_redis = true) {
        $this->use_redis = $use_redis;
        if($use_redis == true)
        {
            $this->time_elapsed_last = microtime(true);
            try {
                $redis = new Redis();
                $redis->connect($this->default_host, $this->default_port, 1000);
                $value = $redis->get(md5("connect_status"));
                $this->data_store = $redis;
            } catch (Exception $e) {
                $this->add_debug_info("[" . __METHOD__ . "()] ".$e->getMessage(). " redis 서버에 접속할수 없습니다.");
                $this->data_store = NULL;
                $this->use_redis = false;
            }
        }        
    }

    public function set_debug($is_debug){
        $this->is_debug = $is_debug;
    }

    public function get_query_to_array($sql, $timeout=300){
        return $this->get_query_cache($sql, true, $timeout);
    }

    public function get_query_to_data($sql, $timeout=300){
        return $this->get_query_cache($sql, false, $timeout);
    }

    
    public function get_debug_info() {
        return $this->debug_info;
    }

    public function get_debug_string($is_html=false){
        $log = "";
        $logs = $this->debug_info;
        for($i = 0; $i < count($logs); $i++)
        {
            if($is_html)
                $log .= $logs[$i] . "<BR>";
            else       
                $log .= $logs[$i] . "\n";   
        }
        return $log;
    }

    public function __destruct() {

    }

    public function clean_query_cache($key = NULL) {
        if($key == NULL) {
            //key 를 지정하지 않은 경우 query문을 이용한 md5 해쉬키를 생성한다.
            //동일한 쿼리라면, 동일한 hash key 가 생성됨.
            $key = $this->default_key_prefix;
        }
        $all_keys =  $this->data_store->keys("{$key}*");
        foreach ($all_keys as $key) {
            $this->add_debug_info("[".__METHOD__."()] $key delete..");
            $this->delete($key);
        }
    }

    protected function generate_cache_key($query) {
        $key = $this->default_key_prefix . hash("md5", $query);
        return $key;
    }

    protected function get_query_result($query, $is_array) {
        //$this->add_debug_info("[" . __METHOD__ . "()] 쿼리= ".$query);
        $result = mysqli_query($self_con, $query);
        if($is_array){
            while(($row = mysqli_fetch_array($result)) != null) {
                $list[] = $row;
            }
            return $list;           
        }
        else{
            return mysqli_fetch_array($result);
        }
    }

    protected function get($key, $default=NULL) {
        $value = $this->data_store->get($key);
        if ($value === FALSE) { return $default; }
        return $value;
    }

    protected function set($key, $value, $ttl=0) {

        try {
            if($ttl == 0) {
                return $this->data_store->set($key, $value);
            } else {
                return $this->data_store->setex($key, $ttl, $value);
            }
        } catch(Exception $e){
            $this->add_debug_info("[" . __METHOD__ . "()] ".$e->getMessage());
            return false;
        }
    }

    protected function delete($key) {
        return (bool) $this->data_store->delete($key);
    }

    protected function exists($key) {
        try {
            return $this->data_store->exists($key);
        }catch(Exception $e){
            $this->add_debug_info("[" . __METHOD__ . "()] ".$e->getMessage());
            return false;
        }
    }


    protected function add_debug_info($comment) {
        $this->debug_info[] = "DEBUG ".date("Y-m-d H:i:s")." ".$comment;
    }

    protected function ttl($key) {
        try {
            return $this->data_store->ttl($key);
        }catch(Exception $e){
            $this->add_debug_info("[" . __METHOD__ . "()] ".$e->getMessage());
            return false;
        }
    }

    protected function get_query_cache($query, $is_array, $ttl) {
        $list = array();

        if($this->is_debug) {
            $this->get_time_elapsed();
        }

        if($this->use_redis == true) {
            $key = $this->generate_cache_key($query);
            try{
                if($this->is_debug) {
                    $this->add_debug_info("[" . __METHOD__ . "()] cache_exist = " . $this->data_store->exists($key) . ", ttl=" . $this->data_store->ttl($key));
                }
                if ($this->exists($key) && $this->ttl($key) > 0) { //캐시가 만료되지 않았다면,
                    $result = $this->get($key);
                    $list = $this->unserialize($result);
                    $this->add_debug_info("[" . __METHOD__ . "()] 캐시 = $query ");
                } else {
                    //만료된 경우 캐시에 저장한다.
                    $list = $this->get_query_result($query, $is_array);
                    $value = $this->serialize($list);
                    $this->set($key, $value, $ttl);
                    $this->add_debug_info("[" . __METHOD__ . "()] 디비 = $query ");
                }
            }catch (Exception $e) {
                $this->add_debug_info("[" . __METHOD__ . "()] ".$e->getMessage());
                $this->use_redis = false;
            }

        }
        if($this->use_redis == false) {
            $this->add_debug_info("[" . __METHOD__ . "()] 레디스를 이용하지 않습니다. 디비 = $query");
            $list = $this->get_query_result($query, $is_array);
        } 

        if($this->is_debug) {
            $this->add_debug_info("[" . __METHOD__ . "()] 실행시간 =  " . $this->get_time_elapsed());
        }

        return $list;
    }

    protected function serialize($value) {
        return serialize($value);
    }

    protected function unserialize($value) {
        return unserialize($value);
    }

    protected function get_time_elapsed() {

        // $unit="s"; $scale=1000000; // output in seconds
        // $unit="ms"; $scale=1000; // output in milliseconds

        $now = microtime(true);

        $exec_time =   round(($now - $this->time_elapsed_last)*1000000)/1000;

        $this->time_elapsed_last = $now;

        return sprintf("%.3f", $exec_time / 1000)."s";
    }
}
