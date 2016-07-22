/**
 * Created by Andy on 7/22/2016.
 */

//Initialize game
var game = null;
(function($){
    //Create the serializer
    var serializer = new Serializer();
    serializer.load('game');

    if(serializer.objects != null) {
        game = serializer.unserialize('game');
        $("#page").html(_T("gameBoard", game));
    } else {
        //Create game Object
        game = new Game();

        //Display the new game form
        $("#page").html(_T("newGameForm"));
    }

    //Add the players to the game upon new game form submission
    $(document).on('click', '#startGameBtn', function(){
        $("input.playerName").filter(function(){
            return this.value.length > 0;
        }).map(function() {
            game.addPlayer(this.value);
        });

        $("#page").html(_T("gameBoard", game));
    });

    //Start the next round upon next round button click
    $(document).on('click', '#nextRoundBtn', function() {
        game.nextRound();
        $("#page").html(_T("bidForm", game));
    });

    //Set bids
    $(document).on('click', '.bidBtn', function() {
        //Change the button color
        $(this).parent().parent().find('button').removeClass('btn-primary');
        $(this).addClass('btn-primary');

        //Set the bid
        var data = $(this).data();
        game.currentRound.bids[data.bidIndex].bid = data.bid;
    });

    //Show round form upon clicking accept bid button
    $(document).on('click', '#startRoundBtn', function(){
        $("#page").html(_T("roundForm", game));
    });

    $(document).on('click', '.resultBtn', function() {
        var data = $(this).data();

        //Reset the button colors
        var buttons = $(this).parent().parent().find('button');
        buttons.removeClass('btn-success');
        buttons.removeClass('btn-danger');
        buttons.addClass('btn-default');

        //Set the clicked button's color
        $(this).removeClass('btn-default');
        var style = data.screwed ? 'danger' : 'success';
        $(this).addClass('btn-' + style);

        //Set bid result
        game.currentRound.bids[data.bidIndex].screwed = data.screwed;
    });

    //Show the scoreboard when clicking the finish round button
    $(document).on('click', '#finishRoundBtn', function() {
        //Save the game state after every round
        serializer = new Serializer();
        serializer.serialize(game);
        serializer.save("game");
        console.debug(JSON.stringify(serializer.objects));

        $("#page").html(_T("gameBoard", game));
    });

    //Start a new game when clicking the new game button
    $(document).on('click', '#newGameBtn', function(){
        game = new Game();
        serializer = new Serializer();
        serializer.clear('game');
        $("#page").html(_T("newGameForm"));
    });
}(jQuery));
