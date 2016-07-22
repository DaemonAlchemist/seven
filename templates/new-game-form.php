<script id="newGameForm" type="text/html" class="handlebars-template">
    <div id="newGameForm" class="col-xs-12 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="col-xs-12">
            <h4><i class="fa fa-play"></i> Start a New Game of Seven</h4>
        </div>
        <?php for($i=0; $i<7; $i++) { ?>
            <div class="col-xs-12 row-buffered">
                <input class="form-control playerName" id="player-<?=$i?>" placeholder="Player <?=$i+1?>"/>
            </div>
        <?php } ?>
    </div>

    <div class="game-btn col-xs-12">
        <button id="startGameBtn" class="form-control btn btn-primary">
            <i class="fa fa-play"></i> Start Game
        </button>
    </div>
</script>
