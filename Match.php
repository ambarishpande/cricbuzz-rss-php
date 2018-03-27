<?php     
    /**
     * Match Object Representation
     */
    class Match {
        public $desc;
        public $type;
        public $series;
        public $match_number;
        public $venue;
        public $inning_count;
        public $state; 
        public $team1;
        public $team2;
        public $score_card;

        public function __construct()
        {
            $this->desc = "" ;
            $this->type = "" ;
            $this->series = "";
            $this->match_number = " ";
            $this->venue = new Venue;
            $this->inning_count = "";
            $this->state = new State;
            $this->team1 = "";
            $this->team2 = "";
            $this->score_card = new ScoreCard;
        }

        public function get_batting_team(){
            $name = $this->score_card->batting_team->name;
            return $name!="" ? $name : $this->team1;
        }
        
        public function get_batting_team_score(){
            $scores = array();
            foreach($this->score_card->batting_team->innings as $inning){
                $score = $inning->runs."/".$inning->wickets;
                array_push($scores, $score); 
            }
            return sizeof($scores) !=0 ? implode(" & ", $scores) : "N/A" ;
        }

        public function get_bowling_team(){
            $name = $this->score_card->bowling_team->name;
            return $name!="" ? $name : $this->team2;
        }

        public function get_bowling_team_score(){
            $scores = array();
            foreach($this->score_card->bowling_team->innings as $inning){
                $score = $inning->runs."/".$inning->wickets;
                array_push($scores, $score); 
            }
            return sizeof($scores) !=0 ? implode(" & ", $scores) : "N/A" ;
        }

        public function get_venue(){
            return $this->venue->ground.", ".$this->venue->city.", ".$this->venue->country;
        }


        
        
        public function get_status(){
            return $this->state->status;
        }
    }  
    
    
    class Venue {
        public $city;
        public $country;
        public $ground;

        public function __construct(){
            $this->city = "";
            $this->country = "";
            $this->ground = "";
        }
    }

    class State {
        public $match_state;
        public $status;
        public $toss_won_by;
        public $decision;
        public $additional_status;
        public $special_status;

        public function __construct(){
            $this->match_state = "";
            $this->status = "";
            $this->toss_won_by = "";
            $this->decision = "";
            $this->additional_status = "";
            $this->special_status = "";
        }

    }

    class ScoreCard {
        public $inning_detail;
        public $batting_team;
        public $bowling_team;

        public function __construct(){
            $this->inning_detail = new InningDetail;
            $this->batting_team = new BattingTeam;
            $this->bowling_team = new BowlingTeam;
                
        }
    }

    class InningDetail{
        public $num_of_overs;
        public $required_rr;
        public $current_rr;
        public $partnership;

        public function __construct(){
            $this->num_of_overs = "";
            $this->required_rr = "";
            $this->current_rr = "";
            $this->partnership = "";
        }
    }

    class Inning{
        public $desc;
        public $runs;
        public $follow_on;
        public $overs;
        public $wickets;
        public $decl;

        public function __construct(){
            $this->desc = "";
            $this->runs = "";
            $this->follow_on = "";
            $this->overs = "";
            $this->wickets = "";
            $this->decl = "";
        }


    }

    class BattingTeam{
        public $name;
        public $innings; // Array of Inning Objects

        public function __construct(){
            $this->name = "" ;
            $this->innings = array();
        }
    }

    class BowlingTeam{
        public $name;
        public $innings; // Array of Inning Objects

        public function __construct(){
            $this->name = "" ;
            $this->innings = array();
        }
    }
?>