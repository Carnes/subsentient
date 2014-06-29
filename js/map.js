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
        this.xOffset = 0;
        this.yOffset = 0;
        this.map = [];
        this.canvas = document.getElementById("gameBoard");
        this.ctx = this.canvas.getContext("2d");
        this.animating = false;

        this.initialize = function(){
            self.resizeCanvas();
            self.ctx.font = "30px Arial";
            self.ctx.textAlign="center";
            self.ctx.strokeText("no map data",self.canvasWidth/2, self.canvasHeight/2);
            ns.Screwdriver().subscribe(this);
            window.requestAnimationFrame =
                window.requestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.msRequestAnimationFrame;

            $('#characterCard').click(self.animateStart);
        };

        this.resizeCanvas = function(){
            $('#gameBoard').attr('width',self.canvasWidth).attr('height',self.canvasHeight);
        };

        this.onEvent = {
            "netEvent: map update": function(data) {
                self.map = data.data;
                if(self.animating === true)
                    self.cancelAnimation();
                self.refreshDisplay();
            },
            "netEvent: tile update": function(data) {
                self.map = data.data;
                if(self.animating === false)
                    self.refreshDisplay();
            },
            "animate: move": function(data){
                self.animateStart(data);
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
            else
            {
                xPos += self.xOffset;
                yPos += +self.yOffset;
            }
            self.ctx.drawImage(img, xPos, yPos);
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
            if(img==null)
                console.log('Critical error: could not find image: "'+"Tile: "+tile.type+" "+tile.variation+'"');
            else
            {
                var xPos = (x*self.tileSize)+self.xOffset;
                var yPos = (y*self.tileSize)+self.yOffset;
                self.ctx.drawImage(img, xPos, yPos);
            }
        };

        this.clearDisplay = function(){
            self.ctx.save();
            self.ctx.setTransform(1, 0, 0, 1, 0, 0);
            self.ctx.fillStyle="#2F4F4F";
            self.ctx.fillRect(0, 0, self.canvasWidth, self.canvasHeight);
            self.ctx.restore();
        };

        this.animateStart = function(animData){
            if(self.animating==true)
                return;
            self.xOffset = 0;
            self.yOffset = 0;
            self.animating=true;
            self.animationDirection = animData;
            self.animationStart = null;
            requestAnimationFrame(self.animateFrame);
        };

        this.cancelAnimation = function(){
            self.xOffset = 0;
            self.yOffset = 0;
            self.animationStart = null;
            self.animating = false;
        };

        this.animateFrame = function(timeStamp){
            if(self.animating===false)
                return;
            var progressMax = 250;
            var progress;
            if (self.animationStart === null) self.animationStart = timeStamp;
            progress = timeStamp - self.animationStart;
            if(self.animationDirection.x<0)
                self.xOffset = Math.floor(self.tileSize * (progress/progressMax));
            if(self.animationDirection.x>0)
                self.xOffset = Math.floor(self.tileSize * (progress/progressMax))*-1;
            if(self.animationDirection.y<0)
                self.yOffset = Math.floor(self.tileSize * (progress/progressMax));
            if(self.animationDirection.y>0)
                self.yOffset = Math.floor(self.tileSize * (progress/progressMax))*-1;
            self.refreshDisplay();

            if (progress < progressMax)
                requestAnimationFrame(self.animateFrame);
            else
            {
                self.cancelAnimation();
                //self.refreshDisplay();
            }
        }

        this.initialize();
    };
})(window.main = window.main || {});