/**
 * Created by Andy on 7/22/2016.
 */

function Round(round, cardCount, game) {
    this.round = typeof round != 'undefined' ? round : null;
    this.cardCount = cardCount || null;
    this.bids = [];
    this.game = game || null;
    this.bidOptions = _.range(0, (cardCount || 0) + 1);

    this.isCurrentRound = function() {
        return this.round == this.game.roundIndex + 1;
    }

    this.addBid = function(bid) {
        bid.bidOptions = this.bidOptions;
        this.bids.push(bid);
    }

    this.totalBids = function(){
        //return this.bids.reduce((total, bid) => total + bid, 0);
        return this.bids.reduce(function(total, bid) {
            return total + bid.bid;
        }, 0);
    }
    
    this.cannotBid = function(){
        var bidCount = this.cardCount - this.totalBids();
        if(bidCount < 0){
            bidCount = "Anything";
        }
        return bidCount        
    }

    this.id = function() {
        return "round_" + this.round;
    };
    this.simpleFields = function() {
        return ['round', 'cardCount', 'bidOptions'];
    };
    this.objectFields = function() {
        return ['game'];
    };
    this.objectLists = function() {
        return ['bids'];
    } ;
};
