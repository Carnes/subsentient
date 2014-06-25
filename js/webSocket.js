(function (ns) {
    ns.webSocketClient = function (uri) {
        var self = this;
        var isConnected = false;
        var socket;

        this.initialize = function(){
            socket = createNewWebSocket();
            new ns.Screwdriver().subscribe(self);
        };

        this.onEvent = {
            "webSocket send": function(obj){
                if(!self.isConnected)
                    socket = createNewWebSocket();
                socket.send(JSON.stringify(obj));
            }
        };

        var createNewWebSocket = function(){
            var newSocket = new WebSocket(uri);
            newSocket.onmessage = function (ev) {
                //console.log('webSocket data: '+ev.data);
                var data = JSON.parse(ev.data);
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
