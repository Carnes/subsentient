(function(ns){
    ns.map = function(tileSize, mapSizeX, mapSizeY, entitySizeX, entitySizeY){
        var self=this;
        this.tileSize = tileSize;
        this.mapSizeX = mapSizeX;
        this.mapSizeY = mapSizeY;
        this.entitySizeY = entitySizeY;
        this.entitySizeX = entitySizeX;
        this.canvasWidth = mapSizeX * tileSize;
        this.canvasHeight = mapSizeY * tileSize;
        this.map = [];
        this.canvas = document.getElementById("gameBoard");
        this.ctx = this.canvas.getContext("2d");

        this.initialize = function(){
            self.resizeCanvas();
            self.ctx.font = "30px Arial";
            self.ctx.textAlign="center";
            self.ctx.strokeText("no map data",self.canvasWidth/2, self.canvasHeight/2);
            ns.Screwdriver().subscribe(this);
        };

        this.resizeCanvas = function(){
            $('#gameBoard').attr('width',self.canvasWidth).attr('height',self.canvasHeight);
        };

        this.onEvent = {
            "netEvent: map update": function(data) {
                self.map = data.data;
                self.refreshDisplay();
            }
        };

        this.refreshDisplay = function(){
            self.clearDisplay();
            self.renderTiles();
            self.renderEntities();
        };

        this.renderEntities = function(){
            for(var y=0; y<self.mapSizeY; y++)
                for(var x=0; x<self.mapSizeX; x++)
                    if(self.map[x][y].entities instanceof Array && self.map[x][y].entities.length > 0)
                        for(var e=0; e<self.map[x][y].entities.length; e++)
                            self.renderEntity(self.map[x][y].entities[e], x, y);
        };

        this.renderEntity = function(entity, x, y){
            var imgName = "Entity: "+entity.type+" "+entity.pose;
            var img=document.getElementById(imgName);
            var xPos = (x*self.tileSize);
            var yPos = (y*self.tileSize) -(self.entitySizeY-self.tileSize);
            self.ctx.drawImage(img, xPos, yPos);
            if(entity.type=="player")
            {
                var textYPos = yPos-5;
                var textXPos = xPos+(self.entitySizeX/2);
                self.ctx.font = "19pt Arial";
                self.ctx.lineWidth = 1;
                self.ctx.textAlign="center";
                self.ctx.fillStyle='white';
                self.ctx.fillText(entity.alias,textXPos, textYPos);
                self.ctx.strokeStyle='black';
                self.ctx.strokeText(entity.alias,textXPos, textYPos);
            }
        };

        this.renderTiles = function(){
            for(var x=0; x<self.mapSizeX; x++)
                for(var y=0; y<self.mapSizeY; y++)
                    self.renderTile(self.map[x][y], x, y);
        };

        this.renderTile = function(tile,x,y){
            if(tile==0)
                return;
            var img=document.getElementById("Tile: "+tile.type+" "+tile.variation);
            var xPos = x*self.tileSize;
            var yPos = y*self.tileSize;
            self.ctx.drawImage(img, xPos, yPos);
        };

        this.clearDisplay = function(){
            self.ctx.save();
            self.ctx.setTransform(1, 0, 0, 1, 0, 0);
            self.ctx.fillStyle="#2F4F4F";
            self.ctx.fillRect(0, 0, self.canvasWidth, self.canvasHeight);
            self.ctx.restore();
        };

        this.initialize();
    };
})(window.main = window.main || {});