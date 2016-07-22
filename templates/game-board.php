<script id="gameBoard" type="text/html" class="handlebars-template">
    <div class="col-xs-12">
        <h4><i class="fa fa-star"></i> Current Scores</h4>

        <style>
            #score-table th, #score-table td {
                width: {{columnWidth}}% !important;
            }
        </style>

        <table id="score-table" class="table table-condensed row-buffered">
            <thead>
                <tr>
                    <th></th>
                    {{#players}}
                        <th>{{name}}</th>
                    {{/players}}
                </tr>
            </thead>
            <tbody>
            {{#rounds}}
                <tr>
                    <th>
                        <i class="fa fa-arrow-right round-indicator{{#if isCurrentRound}} current-round{{/if}}"></i>
                        {{cardCount}}
                    </th>
                    {{#bids}}
                        <th>{{{scoreDisplay}}}</th>
                    {{/bids}}
                </tr>
            {{/rounds}}
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    {{#players}}
                        <th>{{score}}</th>
                    {{/players}}
                </tr>
            </tfoot>
        </table>
    </div>

    {{#unless gameOver}}
        <div class="game-btn col-xs-12">
            <button id="nextRoundBtn" class="btn btn-primary form-control">
                <i class="fa fa-arrow-right"></i> Next Round
            </button>
        </div>
    {{/unless}}
</script>

<script id="scoreSuccess" type="text/html" class="handlebars-template">
    <span class="text-success">{{score}}</span>
</script>

<script id="scoreScrewed" type="text/html" class="handlebars-template">
    <span class="text-danger"><strike>{{bid}}</strike></span>
</script>
