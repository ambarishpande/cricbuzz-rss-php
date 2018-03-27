<?php
    require_once("CricBuzzApi.php");
    $api = new CricBuzzApi;
    echo "<pre>";
    foreach($api->get_matches() as $match){

            echo $match->get_batting_team()." - ".$match->get_batting_team_score() ;
            echo "<br>";
            echo $match->get_bowling_team()." - ".$match->get_bowling_team_score() ;
            echo "<br>";
            echo $match->get_status();
            echo "<br>";
    
    }
    echo "</pre>"
?>