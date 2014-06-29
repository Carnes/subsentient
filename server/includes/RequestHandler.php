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

        if(!isset(Config::$requestCommandsAllowed[$data->cmd]))
        {
            $clientManager = ClientManager::getInstance();
            $client = $clientManager->getClientFromConnection($request->connection);
            Logger::Log("unknown command: \"".$data->cmd."\" from ".$client->ip."(".$client->alias.")");
            return;
        }
        $handlerClassName = Config::$requestCommandsAllowed[$data->cmd];
        new $handlerClassName($request);
        return;
    }
} 