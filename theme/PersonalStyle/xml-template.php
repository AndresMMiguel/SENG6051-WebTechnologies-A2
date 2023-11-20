<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); } 
    // Load the XML file
    $filename = GSDATAPAGESPATH."/games/".$_GET["category"].".xml";
    $file = simplexml_load_file($filename);

    echo '<!DOCTYPE html>';
    echo '<html>';

    // HEAD
	include("head.php");

    // NAVIGATION BAR
    include("navbar.php");

	// PAGE CONTENT
    echo '<body id="'.get_page_slug().'">';
    echo '<div class="content-header">';
    echo '<h1>'. $file->getName().'</h1>';
    echo '<p>'.$file->name.'</p>';
    echo '</div>';

    echo '<div class="content-body">';
    // Iterate over each child (game) of the XML file:
    foreach($file->children() as $game) {
        $gameHash = str_replace(' ', '', $game->name);
        echo '<div class="game" id='.$gameHash.'>';

        echo '<div class="game-image">';
        // Iterate over each game image as there can be multiple
        foreach ($game->xpath("picture") as $picture) {
            $src = '"../..'.$picture.'"';
            echo '<img alt="Error, the image could not be loaded" src='.$src.'/>'; // Game picture(s)
        }
        echo '</div>';
        
        echo '<div class="game-data">';
        echo '<div class="game-header">';
        echo '<div class="game-header-left">';
        // NAME & URL:
        echo '<h2><a href="'.$game->url.'" style="color: inherit; text-decoration: none;">'.$game->name.'</a></h2>';
        echo '<div class="game-inline">';
        // Game grade:
        echo '<div>GRADE: '.$game->grade.'</div>'; 
        // DURATION: Check if there is any:
        if (!empty($game->duration)){
            echo '<div> DURATION: '.$game->duration.'</div>'; // Game duration
        }
        // DIFFICULTY: Check if there is any:
        if (!empty($game->difficulty)){
            echo '<div> DIFFICULTY: '.$game->difficulty.'</div>'; // Game difficulty
        }
        // NUMPLAYERS: Check if there is any:
        if (!empty($game->numPlayers)){
            // Iterate over each game number of players as there can be multiple
            foreach ($game->xpath("numPlayers") as $numplayers){
                echo '<div>Nº PLAYERS: '.$numplayers.'</div>'; // Game number(s) of players
            }
        }
        echo '</div>';
        echo '</div>';
        echo '<div class="game-header-right">';
        // Game subject:
        echo '<div class="game-subject">'.$game->subject.'</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="game-body">';
        echo '<p>'.$game->description.'</p>'; // Game description

        // EQUIPMENT: Iterate over each game equipment as there can be multiple
        echo '<p style="padding: 0;"> For this game you will need:</p>';
        echo '<ul">';
        foreach ($game->xpath("equipment") as $equipment){ 
            $eqName = "";
            $eqCost = ""; 
            $eqCategory = "";
            foreach ($equipment->xpath("eqName") as $childname){
                $eqName = $childname;
            }
            foreach ($equipment->xpath("addCost") as $childcost){
                $eqCost = $childcost;
            }
            foreach ($equipment->xpath("addCost") as $costattributes){
                $eqCategory = $costattributes["costAttr"];
            }
            echo '<li>'.$eqName.': '.$eqCost.' ('.$eqCategory.')</li>';
        }
        echo '</ul>';

        // COST: Check if there is any
        if (!empty($game->cost)){
            echo '<div class="game-cost">'.$game->cost.'</div>'; // Game cost
        }

        echo '<div class="game-reviews"> REVIEWS:';
        echo '<ul>';
        // REVIEWS: Check if there is any
        if (!empty($game->review)){
            // Iterate over each game review as there can be multiple
            foreach ($game->xpath("review") as $review){
                $reviewscore = $review["score"];
                $reviewtext = $review;$reviewscore;
                echo '<li> SCORE '.$reviewscore.'. "'.$reviewtext.'"</li>';
            }
        }
        echo '</ul>';
        echo '</div>';
        
        
        

        echo '</div>';
        
        echo '<div class="game-footer">';
        // TAGS: Check if there is any
        if (!empty($game->tag)){
            echo '<div class="game-tags">';
            echo '<ul>';
            // Iterate over each game tag as there can be multiple
            foreach ($game->xpath("tag") as $tag){
                echo '<li>'.$tag.'</li>'; // Game tag(s)
            }
            echo '</ul>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';

        echo '</div>';
    }
    echo '</body>';

    // Footer
    include("footer.php");
    echo '</html>';
?>