(function(ns){
    ns.characterCard = function(){
        var self = this;
        this.alias = 'n/a';

        this.onEvent = {
            'netEvent: client state change': function(data){
                var character = data.client;
                self.alias = character.alias;
                self.refreshDisplay();
            }
        };

        this.refreshDisplay = function(){
            var card = $('#characterCard');
            card.empty();

            var cardHeader = $('<span>Character Card</span></br>');
            var list = $('<ul>');
            var aliasElement = $('<li><label>Name</label>\n<span>'+self.alias+'</span></li>');

            aliasElement.appendTo(list);

            card.append(cardHeader).append(list);
        };

        this.initialize = function(){
            ns.Screwdriver().subscribe(self);
            self.refreshDisplay();
        };

        this.initialize();
    };
})(window.main = window.main || {});