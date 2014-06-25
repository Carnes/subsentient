(function(ns){
    ns.manualControl = function(){
        var self = this;

        this.move = function(direction){
            var data = {
                cmd: 'move',
                direction: direction
            };
            ns.Screwdriver().publish("webSocket send",data);
        };

        this.turn = function(direction){
            var data = {
                cmd: 'turn',
                direction: direction
            };
            ns.Screwdriver().publish("webSocket send",data);
        };

        this.initialize = function(){
            $('#moveLeft').click(function(){self.move("left");});
            $('#moveRight').click(function(){self.move("right");});
            $('#moveUp').click(function(){self.move("up");});
            $('#moveDown').click(function(){self.move("down");});
            $('#turnLeft').click(function(){self.turn("left");});
            $('#turnRight').click(function(){self.turn("right");});
            $('#turnUp').click(function(){self.turn("up");});
            $('#turnDown').click(function(){self.turn("down");});
        };

        this.initialize();
    };
})(window.main = window.main || {});
