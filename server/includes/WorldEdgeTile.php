<?php
class WorldEdgeTile extends Tile {
    public function __construct($x, $y) {
        parent::__construct(TileType::WorldEdge, $x, $y);
        $this->name = "World Edge";
        $this->variation = 1;
    }
}