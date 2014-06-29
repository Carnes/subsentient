<?php
class MoveCmdHandler extends CmdHandler {
    private $x;
    private $y;
    private $request;
    private $map;
    private $requestHandler;

    public function __construct($request) {
        $this->request = $request;
        $this->map = Map::getInstance();
        $this->requestHandler = RequestHandler::getInstance();

        if($this->requestHandler->isClientInQueue($request->client))
            return; //FIXME - need a way to signal to clients that we have rejected their move
        
        $this->calcMoveDirection();
        if($this->attemptMove()) //FIXME - need a way to signal to clients that their move failed
        {
            $this->notifyNearbyPlayers();
            $this->notifyPlayer();
        }
    }

    private function calcMoveDirection()
    {
        $data = $this->request->data;
        if($data->direction=="up") $this->y=-1; //FIXME - these directions should change to cardinals
        if($data->direction=="down") $this->y=1;
        if($data->direction=="left") $this->x=-1;
        if($data->direction=="right") $this->x=1;
    }

    private function attemptMove()
    {
        return $this->map->moveEntity($this->request->client,$this->x,$this->y);
    }

    private function notifyNearbyPlayers()
    {
        $clientsAffected = $this->map->getPlayerEntitiesInVicinityOf($this->request->client);
        foreach($clientsAffected as $clientAffected)
        {
            $mapData = array("cmd"=>"map update", "data"=>$this->map->getLocalMapForEntity($clientAffected));
            WebSocket::sendDataToOne($mapData, $clientAffected->connection);
        }
    }

    private function notifyPlayer()
    {
        $client = $this->request->client;
        $client->pose = $this->request->data->direction;
        $mapData = array("cmd"=>"map update", "data"=>$this->map->getLocalMapForEntity($client));
        WebSocket::sendDataToOne($mapData, $client->connection);
    }
}