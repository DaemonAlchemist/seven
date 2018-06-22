<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>        
        <nav class="navbar navbar-default navbar-fixed-top">
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
            <nav class="navbar navbar-default navbar-fixed-bottom">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="https://github.com/DaemonAlchemist/seven" target="blank">
                            <i class="fa fa-github" aria-hidden="true"></i>
                            <span class="hidden-xs">Contribute</span>
                        </a>
                        <a class="navbar-brand" href="https://github.com/DaemonAlchemist/seven/issues" target="blank">
                            <i class="fa fa-bug" aria-hidden="true"></i>
                            <span class="hidden-xs">Report a Bug</span>
                        </a>
                        <a class="navbar-brand" href="License.txt" target="blank">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                            <span class="hidden-xs">License</span>
                        </a>
                    </div>
                </div>
            </nav>
        </footer>
        
        <?php include('templates/new-game-form.php')?>
        <?php include('templates/game-board.php')?>
        <?php include('templates/bid-form.php')?>
        <?php include('templates/round-form.php')?>

        <script src="//underscorejs.org/underscore-min.js"></script>
        <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
        <script src="//use.fontawesome.com/437a093948.js"></script>
        <script src="js/helpers.js"></script>
        <script src="js/serializer.js"></script>
        <script src="js/game.js"></script>
        <script src="js/player.js"></script>
        <script src="js/bid.js"></script>
        <script src="js/round.js"></script>
        <script src="js/seven.js"></script>
    </body>
</html>
