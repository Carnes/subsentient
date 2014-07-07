(function (ns) {
    ns.webSocketClient = function (uri) {
        var self = this;
        var isConnected = false;
        var socket;
        this.callbacks = [];

        this.initialize = function(){
            socket = createNewWebSocket();
            new ns.Screwdriver().subscribe(self);
        };

        this.onEvent = {
            "webSocket send": function(obj, callback){
                if(!self.isConnected) {
                    socket = createNewWebSocket();
                    return;
                }
                if(callback && obj.rid)
                    self.callbacks[obj.rid] = callback;

                socket.send(JSON.stringify(obj));
            },

            "webSocket close request": function(rid){
                self.callbacks.splice(rid, 1);
            }
        };

        var createNewWebSocket = function(){
            var newSocket = new WebSocket(uri);
            newSocket.onmessage = function (ev) {
                var data = JSON.parse(ev.data);
                if(data.rid && self.callbacks[data.rid])
                {
                    var removeCallback = self.callbacks[data.rid](data.response);
                    if(removeCallback)
                        self.callbacks.splice(data.rid, 1);
                }
                else
                    new ns.Screwdriver().publish('netEvent: '+data.cmd, data);
            };

            newSocket.onopen = function (ev) {
                ns.log('Connecting..', 'systemText');
                self.isConnected = true;
            };

            newSocket.onerror = function (ev) {
                ns.log('Error Occurred - ' + ev.data, 'systemText');
            };
            newSocket.onclose = function (ev) {
                ns.log('Connection Closed', 'systemText');
                self.isConnected = false;
            };
            return newSocket;
        };

        this.initialize();
    };
})(window.main = window.main || {});
