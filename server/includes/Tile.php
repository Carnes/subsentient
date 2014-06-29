<?php
class Tile
{
    public $typeID;
    public $variation;
    public $entities;
    public $x;
    public $y;
    public $name;
    public function __construct($typeID, $x, $y){
        $this->entities = array();
        $this->typeID = $typeID;
        $this->x = $x;
        $this->y = $y;
    }

    public function addEntity($entity)
    {
        $this->entities[] = $entity;
        $entity->tile = $this;
    }

    public function removeEntity($entity)
    {
        $entity->tile = null;
        for($i=0; $i<count($this->entities); $i++)
            if($this->entities[$i] == $entity)
            {
                unset($this->entities[$i]);
                break;
            }
        $this->entities = array_values($this->entities);
    }

    public function isEnterable()
    {
        if($this->typeID == TileType::WorldEdge)
            return false;
        foreach($this->entities as $entity)
            if($entity->isPassable === false)
                return false;
        return true;
    }

    public function getData()
    {
        $entityData = array();
        foreach($this->entities as $entity)
            $entityData[] = $entity->getData();

        $tileData = array(
            "isEnterable"=>$this->isEnterable(),
            "type"=>$this->name,
            "entities"=>$entityData,
            "variation"=>$this->variation,
        );

        return $tileData;
    }
}