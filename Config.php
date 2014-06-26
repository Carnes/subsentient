<?php
class Config {
    const MapSizeX = 11;
    const MapSizeY = 9;
    const TileSize = 64;
    const EntityWidth = 64;
    const EntityHeight = 128;

    public static function getKeyMap()
    {
        return json_encode(array(
            "move east"=>"D",
            "move west"=>"A",
            "move north"=>"W",
            "move south"=>"S",
            "open chat"=>"T",
            "push"=>" ",
        ));
    }

} 