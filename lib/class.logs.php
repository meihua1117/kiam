<?php
class Logs
{
    protected $log_info = array();
    protected $time_elapsed_last;
    protected $log_file = "iam_log.txt";
    protected $is_by_step = false;
    protected $is_log = true;

    public function __construct($log_file, $is_log=true) {
        $this->is_log = $is_log;
        if($this->is_log)
        {
            $this->log_file = $log_file;
            $this->time_elapsed_last = microtime(true);
            $this->add_log_record("**************  $_SERVER[REQUEST_URI]  **************");
        }
    }

    public function __destruct() {

    }    

    public function add_log($log, $is_add_time = true) {        
        if($this->is_log)
        {
            if($is_add_time){
                $this->add_log_record("[$log] exec_time =  " . $this->get_time_elapsed());
            }
            else{
                $this->log_info[] = $log;
            }
        }

    }
  
    public function get_log_info() {
        return $this->log_info;
    }

    public function step_by_step($is_step)
    {
        $this->is_by_step = $is_step;
    }

    public function get_log_string($is_html=false){
        $log = "";
        $logs = $this->log_info;
        for($i = 0; $i < count($logs); $i++)
        {
            if($is_html)
                $log .= $logs[$i] . "<BR>";
            else       
                $log .= $logs[$i] . "\n";
        }
        return $log;
    }

    public function write_to_file()
    {
        if($this->is_log)
        {
            $log = "";
            $logs = $this->log_info;
            for($i = 0; $i < count($logs); $i++){
                $log .= $logs[$i] . "\n";
            }
            file_put_contents($this->log_file, $log, FILE_APPEND);            
        }

    }    

    protected function add_log_record($comment) {
        $this->log_info[] = date("Y-m-d H:i:s")." ".$comment;
    }

    protected function get_time_elapsed() {

        $now = microtime(true);

        $exec_time =   round(($now - $this->time_elapsed_last)*1000000)/1000;

        if($this->is_by_step)
            $this->time_elapsed_last = $now;

        return sprintf("%.3f", $exec_time / 1000)."s";
    }
}
