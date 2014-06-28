<?php

class Entity {
    public $tile = null;
    public $typeID;
    public $isPassable;
    public $isMovable;
    public $typeName;
    public $pose;

    public function getData()
    {
        $data = array(
            "isPassable"=>$this->isPassable,
            "isMovable"=>$this->isMovable,
            "type"=>$this->typeName,
            "pose"=>$this->pose,
        );
        return $data;
    }
}