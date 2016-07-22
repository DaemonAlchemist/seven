/**
 * Created by Andy on 7/22/2016.
 */

function Game(){
    this.players = [];
    this.rounds = [];

    this.roundIndex = -1;
    this.currentRound = null;
    this.gameOver = false;

    this.dealerIndex = function() {
        return this.currentRound % this.players.length;
    };

    this.addPlayer = function(name) {
        var player = new Player(name);
        this.players.push(player);

        //Add all bids
        this.rounds.forEach(function(round){
            var bid = new Bid();
            bid.player = player;
            player.bids.push(bid);
            round.addBid(bid);
        });
    };

    this.nextRound = function() {
        this.roundIndex++;
        this.gameOver = this.roundIndex > 11;
        this.currentRound = this.rounds[this.roundIndex];
    }

    this.init = function() {
        //Add all rounds
        for(var i=1; i<=7; i++) {
            this.rounds.push(new Round(i - 1, i, this));
        }
        for(var i=6; i>=1; i--) {
            this.rounds.push(new Round(13 - i, i, this));
        }
    };

    this.columnWidth = function(){
        return 100.0 / (this.players.length + 1);
    };

    this.id = function() {
        return "game";
    };
    this.simpleFields = function() {
        return ['roundIndex', 'gameOver'];
    };
    this.objectFields = function() {
        return ['currentRound'];
    };
    this.objectLists = function() {
        return ['players', 'rounds'];
    } ;

    this.init();
};
