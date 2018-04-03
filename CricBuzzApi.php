<?php
    require_once("Match.php");

    /**
     * A PHP REST API for live cricket scores from CricBuzz rss feed.
     */
    class CricBuzzApi { 
        
       public function get_feed()
       {
           $feed = file_get_contents("http://synd.cricbuzz.com/j2me/1.0/livematches.xml" );
           $feed_object = simplexml_load_string($feed);
           return $feed_object; 
       } 

       public function get_matches(){
            $matches = array();
            $rss = $this->get_feed();
            $array = json_decode(json_encode((array) $rss), TRUE);
            foreach($array['match'] as $match){
                $m = new Match;
                $m->desc = isset($match["@attributes"]["mchDesc"]) ? $match["@attributes"]["mchDesc"] : "";
                $m->series = isset($match["@attributes"]["srs"]) ? $match["@attributes"]["srs"] : "";
                $m->type = isset($match["@attributes"]["type"]) ? $match["@attributes"]["type"] : ""; 
                $m->match_number = isset($match["@attributes"]["mnum"]) ? $match["@attributes"]["mnum"] : "";
                $m->inning_count = isset($match["@attributes"]["inngCnt"]) ? $match["@attributes"]["inngCnt"] : "";
               
                $v = new Venue;
                $v->city = isset($match["@attributes"]["vcity"]) ? $match["@attributes"]["vcity"] : "";
                $v->country = isset($match["@attributes"]["vcountry"]) ? $match["@attributes"]["vcountry"] : "";
                $v->ground = isset($match["@attributes"]["grnd"]) ? $match["@attributes"]["grnd"] : "";
                $m->venue = $v;

                $s = new State;
                $s->match_state = isset($match["state"]["@attributes"]["mchState"]) ? $match["state"]["@attributes"]["mchState"] : "";
                $s->status = isset($match["state"]["@attributes"]["status"]) ? $match["state"]["@attributes"]["status"] : "";
                $s->toss_won_by = isset($match["state"]["@attributes"]["TW"]) ? $match["state"]["@attributes"]["TW"] : "";
                $s->decision = isset($match["state"]["@attributes"]["decisn"]) ? $match["state"]["@attributes"]["decisn"] : "";
                $s->additional_status = isset($match["state"]["@attributes"]["addnStatus"]) ? $match["state"]["@attributes"]["addnStatus"] : "";
                $s->special_status = isset($match["state"]["@attributes"]["splStatus"]) ? $match["state"]["@attributes"]["splStatus"] : "";
                $m->state = $s;


                $m->team1 = isset($match["Tm"][0]["@attributes"]["sName"]) ? $match["Tm"][0]["@attributes"]["sName"] : ""; 
                $m->team2 = isset($match["Tm"][1]["@attributes"]["sName"]) ? $match["Tm"][1]["@attributes"]["sName"] : ""; 
                
                $sc = new ScoreCard;
                
                $id = new InningDetail;
                $id->num_of_overs = isset($match["mscr"]["inngsdetail"]["@attributes"]["noofovers"]) ? $match["mscr"]["inngsdetail"]["@attributes"]["noofovers"] : "";
                $id->required_rr = isset($match["mscr"]["inngsdetail"]["@attributes"]["rrr"]) ? $match["mscr"]["inngsdetail"]["@attributes"]["rrr"] : "";
                $id->current_rr = isset($match["mscr"]["inngsdetail"]["@attributes"]["crr"]) ? $match["mscr"]["inngsdetail"]["@attributes"]["crr"] : "";
                $id->partnership = isset($match["mscr"]["inngsdetail"]["@attributes"]["cprtshp"]) ? $match["mscr"]["inngsdetail"]["@attributes"]["cprtshp"] : "";

                $sc->inning_detail = $id;

                $bat = new BattingTeam;
                $bat->name = isset($match["mscr"]["btTm"]["@attributes"]["sName"]) ? $match["mscr"]["btTm"]["@attributes"]["sName"] : "";
                $inngs = array();
                if (isset($match["mscr"]["btTm"]["Inngs"])){
                    foreach($match["mscr"]["btTm"]["Inngs"] as $inn){
                        $in = new Inning;
                        $in->desc = $inn["desc"];
                        $in->runs = $inn["r"];
                        $in->overs = $inn["ovrs"];
                        $in->wickets = $inn["wkts"];
                        $in->decl = $inn["Decl"];
                        array_push($inngs, $in);
                    }
                }
                
                $bat->innings = $inngs;

                $sc->batting_team = $bat;

                $ball = new BattingTeam;
                $ball->name = isset($match["mscr"]["blgTm"]["@attributes"]["sName"]) ? $match["mscr"]["blgTm"]["@attributes"]["sName"] : "";
                $inngs1 = array();
                if (isset($match["mscr"]["blgTm"]["Inngs"])){
                    foreach($match["mscr"]["blgTm"]["Inngs"] as $inn){
                        $in = new Inning;
                        $in->desc = $inn["desc"];
                        $in->runs = $inn["r"];
                        $in->overs = $inn["ovrs"];
                        $in->wickets = $inn["wkts"];
                        $in->decl = $inn["Decl"];
                        array_push($inngs1, $in);
                    }
                }
                $ball->innings = $inngs1;
            
                $sc->bowling_team = $ball;

                $m->score_card = $sc;

                array_push($matches, $m);
            }
            return $matches;
    
       }
      
    }         
?>
