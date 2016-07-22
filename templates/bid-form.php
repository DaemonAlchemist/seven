<script id="bidForm" type="text/html" class="handlebars-template">
    <div class="col-xs-12">
        <h4><i class="fa fa-hand-peace-o"></i> Bid - Out of {{currentRound.cardCount}} Card(s)</h4>

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
        <button id="startRoundBtn" class="btn btn-primary form-control">
            <i class="fa fa-check"></i> Accept Bids
        </button>

    </div>
</script>
