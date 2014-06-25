<?php
class Client extends MapEntity {
    public $connection;
    public $ip;
    public $alias;

    public function __construct($newConnection) {
        $this->connection = $newConnection;
        $this->alias = ClientHelper::getRandomAlias();
        socket_getpeername($newConnection, $this->ip);

        parent::__constructor(MapEntityType::Player);
    }
}