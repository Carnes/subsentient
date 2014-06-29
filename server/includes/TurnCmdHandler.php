<?php
class TurnCmdHandler extends CmdHandler {
    public function __construct($request){
        $data = $request->data;
        if($data->direction=="up" || $data->direction=="down" || $data->direction=="left"|| $data->direction=="right")
        {
            $map = Map::getInstance();
            $request->client->pose = $data->direction;
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