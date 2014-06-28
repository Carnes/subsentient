<?php
function __autoload($class_name) {
    set_include_path("includes");
    include $class_name . '.php';
};
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/core.css">
    <meta charset='UTF-8'/>
</head>
<body>
<script src="http://tageverything.org/js/jquery.js"></script>
<script src="js/screwdriver.js"></script>
<script src="js/log.js"></script>
<script src="js/webSocket.js"></script>
<script src="js/chat.js"></script>
<script src="js/characterCard.js"></script>
<script src="js/map.js"></script>
<script src="js/manualControl.js"></script>

<div id="centeredMainDiv">
    <div id="header">
        <h2>Sub-Sentient</h2>
        <h4>A javascript development game.</h4>
            Latest features: New rock entity.  Keyboard movement.  You can move around using WASD and use T to chat.
    </div>

    <canvas id="gameBoard"></canvas>

    <div id="characterCard"></div>

    <div id="manualControls" tabindex="0">
        Controls<br>
        <span style="width:35px; display: inline-block;">Move</span>
        <input id="moveLeft" type="button" value="left">
        <input id="moveRight" type="button" value="right">
        <input id="moveUp" type="button" value="up">
        <input id="moveDown" type="button" value="down">
        <br>
        <span style="width:35px; display: inline-block;">Turn</span>
        <input id="turnLeft" type="button" value="left">
        <input id="turnRight" type="button" value="right">
        <input id="turnUp" type="button" value="up">
        <input id="turnDown" type="button" value="down">
        <br>
    </div>

    <div id="logContainer">
        <div id="systemLog"></div>
        <input id="chatMessage" type="text" size="80" tabindex="1"/>
        <input id="sendChatMessage" type="button" value="Send">
    </div>
</div>

<div id="artwork" style="display: none;">
    <img id="Tile: Grass 1" src="img/tiles/grass64v1.jpg">
    <img id="Tile: Grass 2" src="img/tiles/grass64v2.jpg">
    <img id="Tile: Grass 3" src="img/tiles/grass64v3.jpg">
    <img id="Tile: Grass 4" src="img/tiles/grass64v4.jpg">
    <img id="Tile: Grass 5" src="img/tiles/grass64v5.jpg">
    <img id="Tile: Grass 6" src="img/tiles/grass64v6.png">
    <img id="Tile: Grass 7" src="img/tiles/grass64v7.png">
    <img id="Tile: Grass 8" src="img/tiles/grass64v8.png">
    <img id="Tile: Grass 9" src="img/tiles/grass64v9.png">
    <img id="Tile: Grass 10" src="img/tiles/grass64v10.png">
    <img id="Tile: Grass 11" src="img/tiles/grass64v11.png">
    <img id="Tile: Grass 12" src="img/tiles/grass64v12.png">
    <img id="Tile: World Edge 1" src="img/tiles/lava64.jpg">
    <img id="Entity: player default" src="img/entities/player_default.png">
    <img id="Entity: player left" src="img/entities/player_left.png">
    <img id="Entity: player right" src="img/entities/player_right.png">
    <img id="Entity: player up" src="img/entities/player_up.png">
    <img id="Entity: player down" src="img/entities/player_down.png">
    <img id="Entity: player sitcry" src="img/entities/player_sitcry.png">
    <img id="Entity: rock default" src="img/entities/rock_default.png">
</div>
<script>
    $(function(){
        window.main = window.main || {};
        var webSocketServerUri = 'ws://tageverything.org:9000';
        var mapSizeX = <?php echo Config::MapSizeX ?>;
        var mapSizeY = <?php echo Config::MapSizeY ?>;
        var entitySizeX = <?php echo Config::EntityWidth ?>;
        var entitySizeY = <?php echo Config::EntityHeight ?>;
        var tileSize = <?php echo Config::TileSize ?>;
        var keyMap = JSON.parse('<?php echo Config::getKeyMap() ?>');

        new main.characterCard();
        new main.webSocketClient(webSocketServerUri);
        new main.chat();
        new main.map(tileSize, mapSizeX, mapSizeY, entitySizeX, entitySizeY);
        new main.manualControl(keyMap);
    });
</script>

</body>
</html>
