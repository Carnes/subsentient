<?php
class Logger {
    public static function Log($msg){
        $logFile = fopen(Config::LogFile, "a");
        $timestamp = date("dMY H:i");
        fwrite($logFile,$timestamp." ".$msg."\n");
        fclose($logFile);
    }
} 