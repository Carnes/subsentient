<?php
class Logger {
    public static function Log($msg){
        $logFile = fopen(Config::LogFile, "a");
        $timestamp = date("dMY H:i");
        $logMessage = $timestamp." ".$msg."\n";
        fwrite($logFile,$logMessage);
        fclose($logFile);
        echo $logMessage; //FIXME - for debug?
    }
} 