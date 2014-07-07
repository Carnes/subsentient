<?php
class PlayerEntity extends Entity
{
    public function __construct(){
        $this->pose = "default";
        $this->typeID = EntityType::Player;
        $this->isPassable = false;
        $this->typeName = "player";
    }

    public function getData()
    {
        $data = parent::getData();
        $data["alias"]=$this->alias;
        $data["id"]=$this->id;
        return $data;
    }
}