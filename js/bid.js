/**
 * Created by Andy on 7/22/2016.
 */

function Bid() {
    this.bidId = _.uniqueId();
    this.player = null;
    this.bid = 0;
    this.screwed = null;
    this.bidOptions = [];
    this.score = function() {
        return this.screwed == null || this.screwed
            ? 0
            : this.bid + 10;
    }
    this.scoreDisplay = function() {
        var disp =
            (this.screwed == null ? "&nbsp;" :
                (this.screwed         ? _T('scoreScrewed', this) :
                    _T('scoreSuccess', this)));
        return disp;
    }

    this.id = function() {
        return "bid_" + this.bidId;
    };
    this.simpleFields = function() {
        return ['bidId', 'bid', 'screwed', 'bidOptions'];
    };
    this.objectFields = function() {
        return ['player'];
    };
    this.objectLists = function() {
        return [];
    } ;
};
