<?php
class RockEntity extends Entity
{
    public function __construct(){
        $this->pose = "default";
        $this->typeID = EntityType::Rock;
        $this->isPassable = false;
        $this->typeName = "rock";
        $this->isMovable = true;
    }

    public function getData(){
        $data = parent::getData();
        return $data;
    }
}