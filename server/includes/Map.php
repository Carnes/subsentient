<?php
class Map
{
    private static $_instance;
    private $_map;
    private $_entities;

    public static function getInstance() {
        if (!self::$_instance)
            self::$_instance = new Map();
        return self::$_instance;
    }

    private function __construct() {
        $this->_map = array();
        $this->_entities = array();
        $this->buildEmptyMap();
        $this->populateMapRandomlyWithEntities();
    }

    private function populateMapRandomlyWithEntities()
    {
        $worldSize = Config::WorldSizeX*Config::WorldSizeY;
        $numRandRocks = rand($worldSize*0.01,$worldSize*0.05);
        for($i=0;$i<$numRandRocks; $i++)
        {
            $newRock = new RockEntity();
            $this->addEntityToMapRandomly($newRock);
        }
    }

    private function buildEmptyMap()
    {
        $grassChance = array(1,800);
        $brushChance = array(801, 950);
        $bushChance = array(951,996);
        $rareChance = array(997,1000);

        for($x=0; $x<=Config::WorldSizeX; $x++)
            for($y=0; $y<=Config::WorldSizeY; $y++)
                if($x==0 || $x==Config::WorldSizeX || $y==0 || $y==Config::WorldSizeY)
                    $this->_map[$x][$y] = new WorldEdgeTile($x, $y);
                else
                {
                    $choice = rand(1,1000);
                    if($choice >= $grassChance[0] && $choice <=$grassChance[1])
                        $this->_map[$x][$y] = new GrassTile($x, $y);
                    else if($choice >= $brushChance[0] && $choice <=$brushChance[1])
                        $this->_map[$x][$y] = new BrushTile($x, $y);
                    else if($choice >= $bushChance[0] && $choice <=$bushChance[1])
                        $this->_map[$x][$y] = new BushTile($x, $y);
                    else if($choice >= $rareChance[0] && $choice <=$rareChance[1])
                        $this->_map[$x][$y] = new RareTile($x, $y);
                }
    }

    public function addEntityToMapRandomly($entity)
    {
        if(!($entity instanceof Entity))
        {Logger::Log("Warning: Map->addEntityToMapRandomly on non Entity."); return;}
        if($entity->typeID==EntityType::Player)
            $minDist = Config::MinimumSpawningDistanceFromBorder;
        else
            $minDist = 1;
        $x = rand($minDist, Config::WorldSizeX-$minDist);
        $y = rand($minDist, Config::WorldSizeY-$minDist);
        $this->_entities[] = $entity;
        $this->_map[$x][$y]->addEntity($entity);
    }

    private function hasEntity($entity)
    {
        foreach($this->_entities as $key => $value)
            if($value === $entity)
                return true;
        return false;
    }

    public function moveEntity($entity,$moveX,$moveY)
    {
        if(! $this->hasEntity($entity))
        { Logger::Log("Warning: Map->moveEntity on non-mapped entity."); return false;}

        if(abs($moveX) + abs($moveY)!=1)
        { Logger::Log("Warning: Map->moveEntity tried to move more than one tile distance. x:".$moveX." y:".$moveY); return false;}

        $x = $moveX+$entity->tile->x;
        $y = $moveY+$entity->tile->y;

        if(!($this->_map[$x][$y]->isEnterable()))
            return false;

        if($entity->tile!=null)
            $entity->tile->removeEntity($entity);

        $this->_map[$x][$y]->addEntity($entity);
        return true;
    }

    public function getPlayerEntitiesInVicinityOf($entity)
    {
        $nearEntities = array();
        $offsetX = ((Config::LocalMapX -1) /2)+1;
        $offsetY = ((Config::LocalMapY -1) /2)+1;

        $xStart = $entity->tile->x - $offsetX;
        if($xStart <= 0) $xStart = 1;
        $yStart = $entity->tile->y - $offsetY;
        if($yStart <= 0) $yStart = 1;
        $xEnd = $entity->tile->x+$offsetX;
        if($xEnd >= Config::WorldSizeX) $xEnd = Config::WorldSizeX-1;
        $yEnd = $entity->tile->y+$offsetY;
        if($yEnd >= Config::WorldSizeY) $yEnd = Config::WorldSizeY-1;

        for($x=$xStart; $x <= $xEnd; $x++)
            for($y=$yStart; $y <= $yEnd; $y++)
                foreach($this->_map[$x][$y]->entities as $entityInTile)
                    if($entityInTile->typeID == EntityType::Player)
                        $nearEntities[] = $entityInTile;
        return $nearEntities;
    }
    public function getLocalMapForEntity($entity)
    {
        $localMap = array();
        $offsetX = (Config::LocalMapX -1) /2;
        $offsetY = (Config::LocalMapY -1) /2;
        $eX = $entity->tile->x;
        $eY = $entity->tile->y;
        $localX=0;
        $localY=0;
        for($x=$eX - $offsetX; $x <= $eX+$offsetX; $x++)
        {
            for($y=$eY - $offsetY; $y <= $eY+$offsetY; $y++)
            {
                if($x<0 || $x>Config::WorldSizeX || $y<0 || $y>Config::WorldSizeY)
                    $localMap[$localX][$localY] = 0;
                else
                    $localMap[$localX][$localY] = $this->_map[$x][$y]->getData();
                $localY++;
            }
            $localY=0;
            $localX++;
        }
        return $localMap;
    }
}

