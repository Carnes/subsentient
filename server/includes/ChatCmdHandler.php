<?php
class ChatCmdHandler extends CmdHandler {
    public function __construct($request){
        $data = $request->data;
        $cmd = $data->cmd;
        $message = $data->message;
        $clientManager = ClientManager::getInstance();
        $client = $clientManager->getClientFromConnection($request->connection);
        $outData = array('cmd'=>$cmd, 'message'=>$message, 'fromAlias'=>$client->alias);
        WebSocket::sendDataToAll($outData);
    }
}