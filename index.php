<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

        <style>
            .row-buffered {
                margin-bottom: 15px;
            }
            
            #score-table {
                margin: auto;
            }
            
            #score-table tbody {
                padding-top: 1em;
                display: block;
            }
            
            #score-table tr {
                display: block;
                width: 100%;
                clear: both;
            }
            
            #score-table thead th {
                transform: rotate(-45deg);
                border: none;
            }
            
            #score-table th, #score-table td {
                display: block;
                float: left;
                text-align: center;
            }
            
            .round-indicator {
                visibility: hidden;
            }
            
            .round-indicator.current-round {
                visibility: visible;
            }
            
            .bidFormName {
                min-width: 0.5em !important;
                width: 0.5em !important;
            }
            
            footer {
                clear: both;
                padding-top: 15px;
            }
            
            footer .navbar-header {
                text-align: center !important;
            }
            
            footer a.navbar-brand {
                float: none;
                display: inline-block;
            }
        </style>
    </head>
    <body>        
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Seven</a>
                </div>
            </div>
        </nav>
    
        <div id="page"></div>
        
        <footer>
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="https://github.com/DaemonAlchemist/seven" target="blank">Contribute</a>
                        <a class="navbar-brand" href="https://github.com/DaemonAlchemist/seven/issues" target="blank">Bug Reports</a>
                        <a class="navbar-brand" href="License.txt" target="blank">License</a>
                    </div>
                </div>
            </nav>
        </footer>
        
        <script src="http://underscorejs.org/underscore-min.js"></script>
        <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
        <script src="https://use.fontawesome.com/437a093948.js"></script>

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
                <div class="col-xs-12">
                    <button id="startGameBtn" class="form-control btn btn-primary">
                        <i class="fa fa-play"></i> Start Game
                    </button>
                </div>
            </div>
        </script>

        <script id="gameBoard" type="text/html" class="handlebars-template">
            <div class="col-xs-12">
                <h4><i class="fa fa-star"></i> Current Scores</h4>

                <style>
                    #score-table th, #score-table td {
                        width: {{columnWidth}}% !important;
                    }
                </style>

                <table id="score-table" class="table row-buffered">
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
                        <th>Total</th>
                        {{#players}}
                            <th>{{score}}</th>
                        {{/players}}
                    </tfoot>
                </table>

                {{#unless gameOver}}
                    <button id="nextRoundBtn" class="btn btn-primary form-control">
                        <i class="fa fa-arrow-right"></i> Next Round
                    </button>
                {{/unless}}
            </div>
        </script>

        <script id="scoreSuccess" type="text/html" class="handlebars-template">
            <span class="text-success">{{score}}</span>
        </script>

        <script id="scoreScrewed" type="text/html" class="handlebars-template">
            <span class="text-danger"><strike>{{bid}}</strike></span>
        </script>

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

        <script>
            //====Start Helper functions
            function _H(template, context) {
                return Handlebars.compile(template)(context);
            }

            function _T(template, context) {
                return _H($("script#" + template).html(), context);
            }
            //====End Helper Functions

            //====Start Data Model
            var Serializer = function() {
                this.objects = {};

                var serializer = this;

                this.save = function(name) {
                    localStorage.setItem(name, serializer.objects);
                };

                this.load = function(name) {
                    serializer.objects = $.parseJSON(localStorage.getItem(name));
                };

                this.serialize = function(object) {
                    if(object == null) return null;
                    if(typeof this.objects[object.id()] == 'undefined') {
                        var objSerialized = {};
                        serializer.objects[object.id()] = objSerialized;
                        object.simpleFields().forEach(function(name) {
                            objSerialized[name] = object[name];
                        });
                        object.objectFields().forEach(function(name) {
                            objSerialized[name] = serializer.serialize(object[name]);
                        });
                        object.objectLists().forEach(function(name) {
                            objSerialized[name] = object[name].map(function(object) {
                                serializer.serialize(object);
                                return object.id();
                            });
                        });
                    }

                    return object.id();
                };

                this.unserialize = function(object) {
                    //TODO:  implement
                };
            };

            var Game = function(){
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

            var Player = function(name) {
                this.name = name;
                this.bids = [];
                this.score = function() {
                    return this.bids.reduce(function(total, bid) {
                        return total + bid.score();
                    }, 0);
                };
                this.id = function() {
                    return "player_" + name;
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

            var Bid = function() {
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
                    return ['id', 'bid', 'screwed', 'bidOptions'];
                };
                this.objectFields = function() {
                    return ['player'];
                };
                this.objectLists = function() {
                    return [];
                } ;
            };

            var Round = function(round, cardCount, game) {
                this.round = round;
                this.cardCount = cardCount;
                this.bids = [];
                this.game = game;
                this.bidOptions = _.range(0, cardCount + 1);

                this.isCurrentRound = function() {
                    return this.round == this.game.roundIndex + 1;
                }

                this.addBid = function(bid) {
                    bid.bidOptions = this.bidOptions;
                    this.bids.push(bid);
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
            //====End Data Model

            //Create game Object
            var game = new Game();

            //Initialize game
            (function($){
                //Display the new game form
                $("#page").html(_T("newGameForm"));

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
                    var serializer = new Serializer();
                    serializer.serialize(game);
                    console.debug(JSON.stringify(serializer.objects));
                    serializer.save("game");

                    $("#page").html(_T("gameBoard", game));
                });
            }(jQuery));
        </script>
    </body>
</html>
