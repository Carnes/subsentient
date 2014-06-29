<?php
class TurnCmdHandler extends CmdHandler {

    public function proc($request){}

    public function handle($request){
        $data = $request->data;
        if($data->direction=="up" || $data->direction=="down" || $data->direction=="left"|| $data->direction=="right")
        {
            $map = Map::getInstance();
            $request->client->pose = $data->direction;
            $clientsAffected = $map->getPlayerEntitiesInVicinityOf($request->client);
            foreach($clientsAffected as $clientAffected)
            {
                $mapData = array("cmd"=>"map update", "data"=>$map->getLocalMapForEntity($clientAffected));
                WebSocket::sendDataToOne($mapData, $clientAffected->connection);
            }
            $mapData = array("cmd"=>"map update", "data"=>$map->getLocalMapForEntity($request->client));
            WebSocket::sendDataToOne($mapData, $request->client->connection);
        }
    }
}