<?php
// start a new session or resume an already created one
session_start();

// to display error feedback
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!--
    Name:  Augusto Araujo Peres Goncalez
    Project name: RockPaperScissorsPHP
    Date:  07/25/17

    App Description: This is a webpage that allows the user to play the game
        Rock-Paper-Scissors against the computer
    Page Description: the page that contains the content for the game
    Files: main.css - the page that contains the styles for the web site
    arrays.php - contains auxiliary arrays to be used in the index
-->

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Rock, Paper, Scissors v. 2.0: Augusto Goncalez</title>
        <link href="css/main.css" type="text/css" rel="stylesheet">
    </head>
    <body>

        <?php
        // Reference to external php file
        include "scripts/arrays.php";

        /* Checks to see if game was played before or not (if the there has 
          been a move yet */
        $game = isset($_POST["lstMoves"]) ? "on" : "";

        if (!empty($game)) {
            $_SESSION["game"] = $game;
        }

        // gets variable in the select field if game was played before
        $move = isset($_POST["lstMoves"]) ?
                htmlentities($_POST["lstMoves"]) : "none";

        /* checks to see if the "New Game" button was clicked. If it was, it 
         * resets all session variables and resets user move image
         */
        if (isset($_POST["newGame"])) {
            session_unset();
            $move = "none";
        }

        // if the user did not make any moves yet
        if (!isset($_SESSION["game"])) {
            $_SESSION["userScore"] = 0;
            $_SESSION["compScore"] = 0;
        }

        // if it is not the first game
        else {

            // check to see if an element need to be selected
            $_SESSION[$move] = "selected";

            // make computer move and determine winner/loser/tie
            $compMove = $values[rand(0, 2)];

            if ($move == $compMove) {
                $result = $results[0];
            }

            // to check every chance for the user to win
            else if (($move == "rock" && $compMove == "scissors") ||
                    ($move == "paper" && $compMove == "rock") ||
                    ($move == "scissors" && $compMove == "paper")) {
                $result = $results[1];

                // update user's score
                $_SESSION["userScore"] ++;
            }

            // if none of the above were met, computer won
            else {
                $result = $results[2];
                $_SESSION["compScore"] ++;
            }
        }
        ?>
        <h1>Play Rock, Paper, Scissors</h1>


        <!-- form reloads the index on submission with variable game = on -->
        <form action="index.php" method="POST">
            <div class="play-container">

                <!-- New Game button that resets all images and session 
                variables. Only shows when there has been a game played -->
                <?php
                if (isset($_SESSION["game"])) {
                    echo "<button name='newGame'>New Game</button>";
                }
                ?>

                <p class="game-header">Choose your move:</p>
                <select name="lstMoves">
                    <!-- Creates options for user -->
                    <?php
                    for ($i = 0; $i < count($moves); $i++) {

                        /* if it is the last user's move, the sel's value will
                         * be selected, leaving the option selected */
                        $selIndex = $values[$i];
                        echo "<option value=$values[$i] $_SESSION[$selIndex]>"
                        . "$moves[$i]</option>";

                        // remove the session after using it to "deselect"
                        unset($_SESSION[$values[$i]]);
                    }
                    ?>
                </select>
                <input type="submit">
                <p>
                    <!-- set player's image to the last move made -->
                    <?php
                    echo "<img src=images/$move.png alt=$move>";
                    ?>
                </p>
            </div>
            <div class="play-container">
                <p class="game-header">The computer chose:</p>
                <!-- display computer's move image -->
                <?php
                // if no game has been played yet
                if (!isset($_SESSION["game"])) {
                    echo "<img src='images/none.png' alt='none'>";
                }

                // if there was a move made already
                else {
                    echo "<img src='images/$compMove.png' alt='$compMove'>";
                }
                ?>
            </div>

        </form>

        <p class="game-header">

            <!-- display the result of previous move or asks for first move -->
            <?php
            if (!isset($_SESSION["game"])) {
                echo "Waiting for results...";
            } else {
                echo $result;
            }
            ?>
        </p>

        <div class="score">
            <div>Score:</div>
            <div>Computer:

                <!-- Output computer's score -->
                <?php
                if (isset($_SESSION["compScore"])) {
                    echo $_SESSION["compScore"];
                }
                ?>
            </div>

            <div>Player:

                <!-- Output user's score -->
                <?php
                if (isset($_SESSION["userScore"])) {
                    echo $_SESSION["userScore"];
                }
                ?> 
            </div>
    </body>
</html>
