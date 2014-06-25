<?php

final class MapEntityType {
    const Rock = 1;
    const Tree = 2;
    const Player = 3;
    const Boulder = 4;
}

final class MapEntityHelper {
    public static function setup($entity, $typeID)
    {
        if(!($entity instanceof MapEntity))
        {echo "Warning: MapEntityBuilder->setup on non MapEntity.\n"; return;}

        if($typeID==MapEntityType::Boulder) {
            $entity->isPassable = false;
            $entity->typeName = "boulder";
        }
        if($typeID==MapEntityType::Tree) {
            $entity->isPassable = true;
            $entity->typeName = "tree";
        }
        if($typeID==MapEntityType::Rock) {
            $entity->isPassable = true;
            $entity->typeName = "rock";
        }
        if($typeID==MapEntityType::Player) {
            $entity->isPassable = false;
            $entity->typeName = "player";
        }
    }
}

class MapEntity {
    public $tile = null;
    public $typeID;
    public $isPassable;
    public $typeName;
    public $pose;

    public function __constructor($type)
    {
        $this->pose = "default";
        $this->typeID = $type;
        MapEntityHelper::setup($this,$type);
    }

    public function getData()
    {
        $data = array(
            "isPassable"=>$this->isPassable,
            "type"=>$this->typeName,
            "pose"=>$this->pose,
        );

        if($this->typeID==MapEntityType::Player)
            $data["alias"]=$this->alias;

        return $data;
    }
}