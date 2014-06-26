<?php
class ClientManager {
    private static $_instance;
    private $clients;

    public static function getInstance() {
        if (!self::$_instance)
            self::$_instance = new ClientManager();
        return self::$_instance;
    }

    private function __construct() {
        $this->clients = array();
    }

    public function getClientFromConnection($connection)
    {
        foreach($this->clients as $client)
            if($client->connection == $connection)
                return $client;
        return null;
    }

    public function disconnect($client)
    {
        $client->connection = null;
        $client->pose = "sitcry";
        $map = Map::getInstance();
        $clientsAffected = $map->getPlayerEntitiesInVicinityOf($client);
        foreach($clientsAffected as $clientAffected)
        {
            $mapData = array("cmd"=>"map update", "data"=>$map->getLocalMapForEntity($clientAffected));
            WebSocket::sendDataToOne($mapData, $clientAffected->connection);
        }

    }

    public function createNewClient($connection)
    {
        $newClient = new Client($connection);
        $this->clients[] = $newClient;
        Map::getInstance()->addEntityToMapRandomly($newClient);

        $clientStateChangeCmd = array("cmd"=>"client state change", "client"=>array("alias"=>$newClient->alias));
        WebSocket::sendDataToOne($clientStateChangeCmd, $connection);

        $map = Map::getInstance()->getLocalMapForEntity($newClient);
        $mapData = array("cmd"=>"map update", "data"=>$map);
        WebSocket::sendDataToOne($mapData, $connection);

        $greetingCmd = array("cmd"=>"system message", "message"=>$newClient->alias." has joined.");
        WebSocket::sendDataToAll($greetingCmd);
    }
} 