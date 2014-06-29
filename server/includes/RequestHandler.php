<?php
class RequestHandler {
    private static $_instance;

    private $_queue;

    private function __construct(){
        $this->_queue = array();
    }

    public function isClientInQueue($client)
    {
        foreach($this->_queue as $request)
            if($request->client == $client)
                return true;
        return false;
    }

    public function addRequestToQueue($request)
    {
        if(!isset($request->client) || !isset($request->procTime))
        {
            Logger::Log("Attempted to queue a request(".$request->data->cmd.") without a client or procTime.");
            return;
        }
        $this->_queue[] = $request;
    }

    public function removeRequestFromQueue($request)
    {
        $key = array_search($request, $this->_queue);
        if ($key !== false)
            unset($this->_queue[$key]);
    }

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
        $request->client = $client;

        if(!isset(Config::$requestCommandsAllowed[$data->cmd]))
        {
            Logger::Log("unknown command: \"".$data->cmd."\" from ".$client->ip."(".$client->alias.")");
            return;
        }
        $handlerClassName = Config::$requestCommandsAllowed[$data->cmd];
        new $handlerClassName($request);
        return;
    }
} 