(function(ns){
    ns.manualControl = function(keyMap){
        var self = this;
        this.hasFocus = true;
        this.keyMap = keyMap;
        this.isMoving = false;

        this.move = function(direction){
            if(self.isMoving)
                return;
            var data = {
                cmd: 'move',
                direction: direction,
                rid: UUID.generate()
            };
            var animData = {x: 0, y: 0};
            if(direction=="left")
                animData.x=-1;
            if(direction=="right")
                animData.x=1;
            if(direction=="up")
                animData.y=-1;
            if(direction=="down")
                animData.y=1;

            var moveCallback = function(cbData){
                if(cbData.status == 'queued') {
                    self.isMoving = true;
                    ns.Screwdriver().publish("animate: move", animData); //FIXME add data.duration to animData
                    return false;
                }
                else if(cbData.status == 'fail') {
                    if(self.isMoving) {
                        ns.Screwdriver().publish("animate: cancel");
                        self.isMoving = false;
                    }
                    return true;
                }
                else if(cbData.status == 'success') {
                    self.isMoving = false;
                    return true;
                }
                throw "Must return true or false";
            };

            ns.Screwdriver().publish("webSocket send",data, moveCallback);
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
