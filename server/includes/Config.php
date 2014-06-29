<?php
class Config {
    public static $host = 'tageverything.org';
    public static $port = '9000';
    public static $microSecondsPerTick = 150000;
    public static $rootFolder = '/subsentient';
    const WorldSizeX = 100;
    const WorldSizeY = 100;
    const LocalMapX = 11; //Must be an odd number, to display character in center
    const LocalMapY = 9; //Must be an odd number, to display character in center
    const LogFile = 'subsentient.log';
    const MinimumSpawningDistanceFromBorder = 15;
}