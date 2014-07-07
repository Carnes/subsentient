(function (ns) {
    'use strict';
    var p = {};

    p.Screwdriver = function () {
        var self = this;

        self.subscribers = [];

        self.subscribe = function (sub) {
            if (sub.onEvent) self.subscribers.push(sub);
        };

        self.publish = function (event, value1, value2, value3) {
            self.subscribers.forEach(function (item) {
                if (item.onEvent[event])
                    item.onEvent[event](value1, value2, value3);
            });
        };
    };

    ns.Screwdriver = function () {
        return p.instance = p.instance || new p.Screwdriver();
    };
})(window.main = window.main || {});