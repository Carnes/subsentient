(function(ns){
    ns.chat = function(){
        var self = this;
        this.send = function(){
            var msg = $('#chatMessage').val();
            $('#chatMessage').val('');
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
            }
        };

        this.initialize = function(){
            $('#sendChatMessage').click(this.send);
            $('#chatMessage').keypress(function(e){
                if(e.which == 13)
                    self.send();
            });
            ns.Screwdriver().subscribe(self);
        };

        this.initialize();
    };
})(window.main = window.main || {});