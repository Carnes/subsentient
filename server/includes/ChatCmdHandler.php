<?php
class ChatCmdHandler extends CmdHandler {

    public function proc($request){}

    public function handle($request){
        $data = $request->data;
        $cmd = $data->cmd;
        $message = $data->message;
        $outData = array('cmd'=>$cmd, 'message'=>$message, 'fromAlias'=>$request->client->alias);
        WebSocket::sendDataToAll($outData);
    }
}