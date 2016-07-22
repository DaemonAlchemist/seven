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
        
        <?php include('templates/new-game-form.php')?>
        <?php include('templates/game-board.php')?>
        <?php include('templates/bid-form.php')?>
        <?php include('templates/round-form.php')?>

        <script src="http://underscorejs.org/underscore-min.js"></script>
        <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
        <script src="https://use.fontawesome.com/437a093948.js"></script>
        <script src="seven.js"></script>
    </body>
</html>
