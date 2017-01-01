<script id="bidForm" type="text/html" class="handlebars-template">
    <div class="col-xs-12">
        <h4>
            <i class="fa fa-hand-peace-o"></i>
            Bid - Out of {{currentRound.cardCount}} Card(s)
        </h4>

        {{#currentRound.bids}}
            <div class="row row-buffered">
                <div class="col-xs-12">
                    <div class="btn-group btn-group-justified">
                        <div class="bidFormName btn-group"><button class="btn btn-info" disabled>{{player.name}}</button></div>
                        {{#bidOptions}}
                            <div class="btn-group"><button class="bidBtn btn btn-default" data-bid-index="{{@../index}}" data-bid="{{.}}">{{.}}</button></div>
                        {{/bidOptions}}
                    </div>
                </div>
            </div>
        {{/currentRound.bids}}


        <div class="row row-buffered">
            <div class="col-xs-12">
                <div class="btn-group btn-group-justified">
                    <div class="btn-group"><button class="btn btn-success" disabled>Total Bids: </button></div>
                    <div class="btn-group"><button id="total-bids" class="btn btn-default" disabled> 0 </button></div>
                </div>
            </div>
        </div>
        <div class="row row-buffered">
            <div class="col-xs-12">
                <div class="btn-group btn-group-justified">
                    <div class="btn-group"><button class="btn btn-warning" disabled>Cannot Bid: </button></div>
                    <div class="btn-group"><button id="cannot-bid" class="btn btn-default" disabled> 5 </button></div>
                </div>
            </div>
        </div>
    </div>

    <div class="game-btn col-xs-12">
        <button id="startRoundBtn" class="btn btn-primary form-control">
            <i class="fa fa-check"></i> Accept Bids
        </button>
    </div>
</script>
