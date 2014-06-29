<?php
class Config {
    const Host = 'tageverything.org';
    const Port = '9000';
    const MicroSecondsPerTick = 150000;
    const RootFolder = '/subsentient';
    const WorldSizeX = 100;
    const WorldSizeY = 100;
    const LocalMapX = 11; //Must be an odd number, to display character in center
    const LocalMapY = 9; //Must be an odd number, to display character in center
    const LogFile = 'subsentient.log';
    const MinimumSpawningDistanceFromBorder = 15;
    public static $requestCommandsAllowed = [
        "move"=>"MoveCmdHandler",
        "turn"=>"TurnCmdHandler",
        "chat message"=>"ChatCmdHandler",
        "connect"=>"ConnectionCmdHandler",
        "disconnect"=>"ConnectionCmdHandler",
    ];
}