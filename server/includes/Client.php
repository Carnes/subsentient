<?php
class Client extends PlayerEntity {
    public $connection;
    public $ip;
    public $alias;

    public function __construct($newConnection) {
        $this->connection = $newConnection;
        $this->alias = ClientHelper::getRandomAlias();
        socket_getpeername($newConnection, $this->ip);

        parent::__construct();
    }
}