<?php
class GrassTile extends Tile{
    public function __construct($x, $y){
        parent::__construct(TileType::Grass, $x, $y);
        $this->name = "Grass";
        $this->variation = rand(1,6);
    }
}