<?php
class SocketManager {
    private static $_instance;
    public $serviceConnection;
    public $clientConnections;

    public static function getInstance() {
        if (!self::$_instance)
            self::$_instance = new SocketManager();
        return self::$_instance;
    }

    private function __construct() {
        $this->serviceConnection = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        socket_set_option($this->serviceConnection, SOL_SOCKET, SO_REUSEADDR, 1);

        socket_bind($this->serviceConnection, 0, Config::Port);

        socket_listen($this->serviceConnection);

        $this->clientConnections = array();
    }

    public function addConnection($newConnection){
        $this->clientConnections[] = $newConnection;
    }

    public function close() {
        WebSocket::sendDataToAll(array("cmd"=>"system message","message"=>"Server shutting down."));
        socket_close($this->serviceConnection);
        foreach($this->clientConnections as $connection)
            socket_close($connection);
    }

    private function getNewConnection(){
        $connectionToWatch[] = $this->serviceConnection;
        socket_select($connectionToWatch, $null, $null, 0, 10);
        if(count($connectionToWatch)==0)
            return null;
        $newConnection = socket_accept($this->serviceConnection);
        $this->addConnection($newConnection);
        $header = socket_read($newConnection, 1024);
        WebSocket::handShake($header, $newConnection);
        $data = (object) array('cmd'=>'connect');
        return (object) array('data'=>$data, 'connection'=>$newConnection);
    }

    public function getClientRequests() {
        $cmdQueue = array();
        $newConnectionCmd = $this->getNewConnection();
        if($newConnectionCmd!=null)
            $cmdQueue[] = $newConnectionCmd;
        $changedConnections = $this->getChangedConnections();
        foreach ($changedConnections as $changedConnection)
            $cmdQueue[] = $this->getCmdFromConnection($changedConnection);
        return $cmdQueue;
    }

    private function getCmdFromConnection($connection){
        $status = socket_recv($connection, $buf, 1024, 0);

        if($this->isDisconnected($status))
            return $this->disconnectClient($connection);

        $jsonData = WebSocket::decodeMessage($buf);
        $data = json_decode($jsonData);
        return (object) array('data'=>$data, 'connection'=>$connection);
    }

    private function getChangedConnections(){
        $changedConnections = $this->clientConnections;
        if(count($changedConnections)>0)
            socket_select($changedConnections, $null, $null, 0, 10);
        return $changedConnections;
    }

    private function isDisconnected($socketReceivedStatus) {
        if($socketReceivedStatus===false || $socketReceivedStatus===0) return true;
        return false;
    }

    private function disconnectClient($connection) {
        $foundSocket = array_search($connection, $this->clientConnections);
        socket_getpeername($connection, $ip);
        unset($this->clientConnections[$foundSocket]);
        $data = (object) array('cmd'=>'disconnect');
        return (object) array('data'=>$data, 'connection'=>$connection);
    }
}