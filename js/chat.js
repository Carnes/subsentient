(function(ns){
    ns.chat = function(){
        var self = this;
        this.textElement = $('#chatMessage');
        this.sendBtnElement = $('#sendChatMessage');
        this.send = function(){
            var msg = self.textElement.val();
            self.textElement.val('');
            if (msg.length == 0)
                return;
            var data = {
                cmd: 'chat message',
                message: msg
            };
            ns.Screwdriver().publish("webSocket send",data);
        };

        this.onEvent = {
            'netEvent: system message': function(data){
                ns.log(data.message, 'systemText');
            },
            'netEvent: chat message': function(data){
                var msg = data.message;
                if(data.fromAlias != undefined)
                    msg = data.fromAlias+': '+msg;
                ns.log(msg, 'chatText');
            },
            'focus chat': function(){
                self.textElement.focus();
            }
        };

        this.initialize = function(){
            self.textElement.focusin(function(){
                ns.Screwdriver().publish('taking focus');
            });
            self.textElement.focusout(function(){
                ns.Screwdriver().publish('return focus');
            });
            self.sendBtnElement.click(this.send);
            self.textElement.keypress(function(e){
                if(e.which == 13)
                {
                    self.sendBtnElement.focus();
                    self.send();
                }
            });
            ns.Screwdriver().subscribe(self);
        };

        this.initialize();
    };
})(window.main = window.main || {});