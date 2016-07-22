/**
 * Created by Andy on 7/22/2016.
 */

function Player(name) {
    this.name = typeof name != 'undefined' ? name : null;
    this.bids = [];
    this.score = function() {
        return this.bids.reduce(function(total, bid) {
            return total + bid.score();
        }, 0);
    };
    this.id = function() {
        return "player_" + this.name;
    };
    this.simpleFields = function() {
        return ['name'];
    };
    this.objectFields = function() {
        return [];
    };
    this.objectLists = function() {
        return ['bids'];
    } ;
};
