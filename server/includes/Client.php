<?php
class Client extends PlayerEntity {
    public $connection;
    public $ip;
    public $alias;
    public $id;

    public function __construct($newConnection) {
        $this->connection = $newConnection;
        $this->alias = ClientHelper::getRandomAlias();
        $this->id = UUID::generate();
        socket_getpeername($newConnection, $this->ip);

        parent::__construct();
    }
}