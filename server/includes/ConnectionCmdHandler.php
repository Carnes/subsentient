<?php
class ConnectionCmdHandler extends CmdHandler{
    public function __construct($request) {
        $clientManager = ClientManager::getInstance();

        $cmd = $request->data->cmd;
        if($cmd=="connect") {
            $client = $clientManager->createNewClient($request->connection);
            Logger::Log($client->ip." joined as ".$client->alias);
        }
        else if($cmd=="disconnect") {
            $client = $request->client;
            Logger::Log($client->ip." (".$client->alias.") disconnected.");
            $clientManager->disconnect($client);
            WebSocket::sendDataToAll(array("cmd"=>"system message", "message"=>$client->alias." quit."));
        }
    }
}