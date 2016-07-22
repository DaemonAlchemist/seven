<script id="roundForm" type="text/html" class="handlebars-template">
    <div class="col-xs-12">
        <h4><i class="fa fa-hourglass-2"></i> Play -  {{currentRound.cardCount}} Card(s)</h4>

        {{#currentRound.bids}}
        <div class="row row-buffered">
            <div class="col-xs-12">
                <div class="btn-group btn-group-justified">
                    <div class="btn-group"><button class="btn btn-info" disabled>{{player.name}}</button></div>
                    <div class="btn-group">
                        <button class="resultBtn winBtn btn btn-default" data-bid-index="{{@index}}" data-screwed="false">
                            <i class="fa fa-check"></i> Got my <b>{{bid}}!</b>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="resultBtn screwedBtn btn btn-default" data-bid-index="{{@index}}" data-screwed="true">
                            <i class="fa fa-times"></i> Got screwed!
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{/currentRound.bids}}
        <button id="finishRoundBtn" class="btn btn-primary form-control">
            <i class="fa fa-check"></i> Finish Round
        </button>
    </div>
</script>
