<?php
    header('Content-Type: application/json');
    $connection = mysql_connect("cs445sql", "ngonsalv", "EL083ngo");

    if (!$connection){
        die ("Couldn't connect to mysql server! The error was: " . mysql_error());
    }
    if (!mysql_select_db("ggr")){
        die ("Couldn't select a database! Error: " . mysql_error());
    }

    if(isset($_POST["executesql"])){
        $query = $_POST["executesql"];
        $result = mysql_query($query);
        if (!$result){
            die("Query Failed!" . mysql_error());
        }else{
            $return = array();
            while($row = mysql_fetch_array($result, MYSQL_NUM)){
                array_push($return, $row);
            }
            echo json_encode($return);
            die();
        }
    }elseif(isset($_POST["search"])){
        if(isset($_POST["simple"])){
            $query = 'SELECT  s.s_id, s.title, art.art_name, alb.alb_name
                , s.year, s.duration, s.loudness, IFNULL(
                    (select r.rating
                    from Rates r
                    where r.s_id=s.s_id and r.login_id = ';

            $query = $query . $_POST["user_id"];
            echo $query;

            $part = "), 0), s.raw_rating, s.numb_user_rating";
            $part = $part . " from Songs s";
            $part = $part . " join Artists art on (s.art_id = art.art_id)";
            $part = $part . " join Labums alb on (s.alb_id = alb.alb_id) limit 1";

            $query = $query . $part;

    _       $result = mysql_query($query);

            if (!$result){
                die("Query Failed!" . mysql_error());
            }else{
                $return = array();
                while($row = mysql_fetch_array($result, MYSQL_NUM)){
                    array_push($return, $row);
                }
                echo json_encode($return);
                die();
            }


        }else{
            exit();
        }

    }elseif(isset($_POST["register"])){

    }elseif(isset($_POST["rate"])){

    }elseif(isset($_POST["play"])){

    }else{
        die("ERROR");
    }

?>
