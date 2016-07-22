<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>        
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Seven</a>
                    <div id="newGameDiv">
                        <button id="newGameBtn" class="btn btn-primary">
                            <i class="fa fa-refresh"></i>
                            New Game
                        </button>
                    </div>
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

        <?php include('templates/new-game-form.php')?>
        <?php include('templates/game-board.php')?>
        <?php include('templates/bid-form.php')?>
        <?php include('templates/round-form.php')?>

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
                this.unserializedObjects = {};

                var serializer = this;

                this.reset = function(){
                    this.objects = {};
                    this.unserializedObjects = {};
                    this.clear();
                };

                this.save = function(name) {
                    localStorage.setItem(name, JSON.stringify(this.objects));
                };

                this.load = function(name) {
                    serializer.objects = $.parseJSON(localStorage.getItem(name));
                };

                this.clear = function(name) {
                    localStorage.removeItem(name);
                }

                this.serialize = function(object) {
                    if(object == null) {
                        console.log("No object to serialize.  Returning null");
                        return null;
                    }
                    console.debug("Serializing " + object.id());
                    var objectId = object.id();
                    if(typeof this.objects[objectId] == 'undefined') {
                        var objSerialized = {};
                        var type = object.constructor.name;
                        console.debug("[" + objectId + "]Object is a " + type);
                        serializer.objects[objectId] = {
                            type: type,
                            data: objSerialized
                        };
                        object.simpleFields().forEach(function(name) {
                            console.debug("[" + objectId + "]Serializing simple field " + name);
                            objSerialized[name] = object[name];
                        });
                        object.objectFields().forEach(function(name) {
                            console.debug("[" + objectId + "]Serializing object field " + name);
                            objSerialized[name] = serializer.serialize(object[name]);
                        });
                        object.objectLists().forEach(function(name) {
                            console.debug("[" + objectId + "]Serializing object list " + name);
                            objSerialized[name] = object[name].map(function(listObject) {
                                return serializer.serialize(listObject);
                            });
                        });
                    } else {
                        console.debug("[" + objectId + "]Object already exists.");
                    }

                    console.debug("[" + objectId + "]Returning objectId");
                    return objectId;
                };

                this.unserialize = function(name) {
                    console.debug("Unserializing " + name);
                    var dispName = name;
                    if(typeof serializer.unserializedObjects[name] == 'undefined') {
                        if(!serializer.objects[name]) {
                            console.debug("[" + dispName + "]Object does not exist in serializer. Returning null")
                            return null;
                        }
                        var type = serializer.objects[name].type;
                        var data = serializer.objects[name].data;
                        console.debug("[" + dispName + "]Object is a " + type);
                        var obj = new window[type]();
                        serializer.unserializedObjects[name] = obj;

                        obj.simpleFields().forEach(function(name){
                            console.debug("[" + dispName + "]Unserializing simple field " + name + " with value " + data[name]);
                            obj[name] = data[name];
                        });
                        obj.objectFields().forEach(function(name) {
                            console.debug("[" + dispName + "]Unserializing object field " + name);
                            obj[name] = serializer.unserialize(data[name]);
                        });
                        obj.objectLists().forEach(function(listName){
                            console.debug("[" + dispName + "]Unserializing object list " + listName);
                            obj[listName] = data[listName].map(function(objName){
                                return serializer.unserialize(objName);
                            })
                        });
                    } else {
                        console.log("[" + dispName + "]Already exists");
                    }

                    console.debug("[" + dispName + "]Returning object " + name);
                    return serializer.unserializedObjects[name];
                };
            };

            function Game(){
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
        </script>
    </body>
</html>
