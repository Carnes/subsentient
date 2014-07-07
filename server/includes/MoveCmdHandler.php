<?php
class MoveCmdHandler extends CmdHandler {
    private $x;
    private $y;
    private $request;
    private $map;
    const Delay = 0.300000;

    public function handle($request){
        $this->request = $request;
        $requestHandler = RequestManager::getInstance();
        if($requestHandler->isClientInQueue($request->client))
            return array("status"=>"fail", "reason"=>"move command already in queue");
        $request->procTime = microtime(true) + $this::Delay;
        $requestHandler->addRequestToQueue($request);
        $this->turnInDirectionOfMove();
        return array("status"=>"queued","duration"=>$this::Delay);
    }

    public function proc($request) {
        $this->request = $request;
        $requestManager = RequestManager::getInstance();
        $requestManager->removeRequestFromQueue($request);

        $this->map = Map::getInstance();
        $this->calcMoveDirection();
        if($this->attemptMove())
        {
            $this->notifyNearbyPlayers();
            $this->notifyPlayer();
            return array("status"=>"success");
        }
        return array("status"=>"fail", "reason"=>"could not complete move");
    }

    private function turnInDirectionOfMove()
    {
        $turnHandler = new TurnCmdHandler();
        $turnHandler->handle($this->request);
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