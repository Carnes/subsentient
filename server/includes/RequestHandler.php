<?php
class RequestHandler {
    private static $_instance;

    public static function getInstance() {
        if (!self::$_instance)
            self::$_instance = new RequestHandler();
        return self::$_instance;
    }

    public function dispatchRequest($request)
    {
        $data = $request->data;
        if(!isset($data->cmd))
            return;
        $clientManager = ClientManager::getInstance();
        $client = $clientManager->getClientFromConnection($request->connection);
        $cmd = $data->cmd;
        if($cmd=="chat message") {
            $message = $data->message;
            $outData = array('cmd'=>$cmd, 'message'=>$message, 'fromAlias'=>$client->alias);
            WebSocket::sendDataToAll($outData);
        }
        else if($cmd=="connect") {
            $client = $clientManager->createNewClient($request->connection);
            Logger::Log($client->ip." joined as ".$client->alias);
        }
        else if($cmd=="turn"){
            if($data->direction=="up" || $data->direction=="down" || $data->direction=="left"|| $data->direction=="right")
            {
                $map = Map::getInstance();
                $client->pose = $data->direction;
                $clientsAffected = $map->getPlayerEntitiesInVicinityOf($client);
                foreach($clientsAffected as $clientAffected)
                {
                    $mapData = array("cmd"=>"map update", "data"=>$map->getLocalMapForEntity($clientAffected));
                    WebSocket::sendDataToOne($mapData, $clientAffected->connection);
                }
                $mapData = array("cmd"=>"map update", "data"=>$map->getLocalMapForEntity($client));
                WebSocket::sendDataToOne($mapData, $client->connection);
            }
        }
        else if($cmd=="move"){
            $map = Map::getInstance();
            $x=0;$y=0;
            if($data->direction=="up") $y=-1;
            if($data->direction=="down") $y=1;
            if($data->direction=="left") $x=-1;
            if($data->direction=="right") $x=1;
            $moved = $map->moveEntity($client,$x,$y);
            if($moved) {
                $client->pose = $data->direction;
                $clientsAffected = $map->getPlayerEntitiesInVicinityOf($client);
                foreach($clientsAffected as $clientAffected)
                {
                    $mapData = array("cmd"=>"map update", "data"=>$map->getLocalMapForEntity($clientAffected));
                    WebSocket::sendDataToOne($mapData, $clientAffected->connection);
                }
                $mapData = array("cmd"=>"map update", "data"=>$map->getLocalMapForEntity($client));
                WebSocket::sendDataToOne($mapData, $client->connection);
            }
        }
        else if($cmd=="disconnect") {
            Logger::Log($client->ip." (".$client->alias.") disconnected.");
            $clientManager->disconnect($client);
            WebSocket::sendDataToAll(array("cmd"=>"system message", "message"=>$client->alias." quit."));
        }
        else {
            Logger::Log("unknown command: ".$cmd." from ".$client->ip."(".$client->alias.")");
        }
    }
} 