<?php
class ClientHelper {
    public static function getRandomAlias()
    {
      return self::getPrefix()." ".self::getName();
    }

    private static function getPrefix()
    {
        $parts = ["Mr","Mrs","Miss","Dr.","Prof","The","Nimble","Big","Fast","Dark","Captain","Chief","Master","Heir","Elder","Frau","Agile","Quick","Senior","Sharp","Ghastly"];
        return $parts[rand(0,count($parts)-1)];
    }

    private static function getName()
    {
        $parts = ["bo","ok","ha","nd","as",
            "te","ta","ti","to","se",
            "sa","si","st","sr","str",
            "ip","op","on","og","oy",
            "ce","er","et","ek","el",
            "at","su","du","fu","ju",
            "ki","li","fi","li","ro",
            "th","th","tr","qu","xu",
            " O'","ni","na","vi","vu",
        ];

        $name = "";
        $nameParts = rand(2,5);
        for($i=0; $i<$nameParts; $i++)
            $name .= $parts[rand(0,count($parts)-1)];

        $name[0] = strtoupper($name[0]);

        return $name;
    }
}