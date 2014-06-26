(function(ns){
    ns.manualControl = function(keyMap){
        var self = this;
        this.hasFocus = true;
        this.keyMap = keyMap;

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

        this.takeFocus = function(){
            self.hasFocus = true;
        };

        this.lostFocus = function(){
            self.hasFocus = false;
        };

        this.onEvent = {
            'taking focus': self.lostFocus,
            'return focus': self.takeFocus
        };

        this.initialize = function(){
            $(document).keydown(function(event) {
                if(!self.hasFocus)
                    return;
                if(String.fromCharCode(event.keyCode) == self.keyMap['move west'])
                    self.move("left");
                if(String.fromCharCode(event.keyCode) == self.keyMap['move east'])
                    self.move("right");
                if(String.fromCharCode(event.keyCode) == self.keyMap['move north'])
                    self.move("up");
                if(String.fromCharCode(event.keyCode) == self.keyMap['move south'])
                    self.move("down");
                if(String.fromCharCode(event.keyCode) == self.keyMap['open chat'])
                {
                    self.lostFocus();
                    ns.Screwdriver().publish('focus chat');
                    return false;
                }
            });

            $('#moveLeft').click(function(){self.move("left");});
            $('#moveRight').click(function(){self.move("right");});
            $('#moveUp').click(function(){self.move("up");});
            $('#moveDown').click(function(){self.move("down");});
            $('#turnLeft').click(function(){self.turn("left");});
            $('#turnRight').click(function(){self.turn("right");});
            $('#turnUp').click(function(){self.turn("up");});
            $('#turnDown').click(function(){self.turn("down");});
            ns.Screwdriver().subscribe(self);
        };

        this.initialize();
    };
})(window.main = window.main || {});
