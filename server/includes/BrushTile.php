<?php
class BrushTile extends Tile{
    public function __construct($x, $y){
        parent::__construct(TileType::Brush, $x, $y);
        $this->name = "Brush";
        $this->variation = rand(1,7);
    }
}