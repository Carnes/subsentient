<?php
class MoveCmdHandler extends CmdHandler {
    public function __construct($request) {
        $data = $request->data;
        $clientManager = ClientManager::getInstance();
        $client = $clientManager->getClientFromConnection($request->connection);
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
}