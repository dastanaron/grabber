<?php
namespace classes\debug;

class Logger {
    
    public $logname;
    public $log_dir;
    protected $pathlog = __DIR__ . '/../../';
    public $string;
    
    function __construct($logname='log.log', $log_dir='/logs/') {
        
        $this->logname = $logname;
        $this->log_dir = $log_dir;
        
    }
    
    public function Log ($string ,$print=false) {
        
        $this->string = $string;
        
        if (!file_exists ($this->getLogDir())) {
            mkdir ($this->getLogDir(), 0755);
        }
        
        if (!$this->ValidateString()) {
            
            $this->ErrorLogWrite('Ошибка, валидация строки не пройдена');
            return false;
            
        }
        
        $log = $this->Logname();
        
        
        
        $this->string  = date("d.m.Y|H:i:s") . '| ' . $this->string . "|" . PHP_EOL;
            
        if(file_put_contents($log, $this->string, LOCK_EX | FILE_APPEND)) {
            return true;
        }
        
        if ($print === true) {
            
            $this->string;
            
        }
        
    }
    
    private function Logname() {
        
        $log = $this->getLogDir() . $this->logname;
        
        if (file_exists($log) && filesize($log) > 1048576) {
            
            $countlog = 1;
            
            $old_log = $log . '.' . $countlog;
            
            if (file_exists($old_log)) {
                $countlog += 1;
                $old_log = $log . '.' . $countlog;
            }
            
            rename($log, $old_log);
        }
        
        return $log;
        
    }
    
    private function ValidateString() {
        
        if (empty($this->string)) {
            $this->ErrorLogWrite('Ошибка, обязательный параметр пустой.');
            return false;
            
        }
        elseif (is_array($this->string) || is_object($this->string)) {
            $this->ErrorLogWrite('Ошибка, в параметр string передан массив или объект.');
            return false;
            
        }
        else {
            return true;
        }
        
    }
    
    private function ErrorLogWrite($errorstring) {
        
        $errorlog = 'error.log';
        
        if(file_put_contents($errorlog, $errorstring, LOCK_EX | FILE_APPEND)) {
            return true;
        }else {
            return false;
        }
        
        
    }
    public function getLogDir() {
        
        return $this->pathlog . $this->log_dir;
        
    }
    
    
}
