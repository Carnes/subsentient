<?php
class BushTile extends Tile{
    public function __construct($x, $y){
        parent::__construct(TileType::Bush, $x, $y);
        $this->name = "Bush";
        $this->variation = rand(1,1);
    }
}