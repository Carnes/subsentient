<?php
class RareTile extends Tile{
    public function __construct($x, $y){
        parent::__construct(TileType::Rare, $x, $y);
        $this->name = "Rare";
        $this->variation = rand(1,5);
    }
}