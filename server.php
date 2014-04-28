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
                , s.year, s.duration, s.loudness, 0
                , s.raw_ratings, s.numb_users_rated
                from Songz2 s
                join Artists art on(s.art_id = art.art_id)
                join Albums alb on (s.alb_id = alb.alb_id)';
            if( isset($_POST['user_id']) ){
                $query = substr_replace($query, ' IFNULL(r.rating, 0) ', strpos('0'), 1);
                $query = $query . ' left join Rates r on (r.s_id = s.s_id and r.login_id = \'' . $_POST['user_id'] . '\')';
            }
            $query = $query . ' where s.title like \'%' . join('%', split('/\s/', $_POST["simple"])) . '%\'
                or art.art_name like \'%' . join('%', split('/\s/', $_POST["simple"])) . '%\'
                or alb.alb_name like \'%' . join('%', split('/\s/', $_POST["simple"])) . '%\';';

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
        }else{
            $group = array();
            if( isset($_POST["groupbytitle"] ) ){
                array_push($group, "s.title");
            }
            if( isset($_POST["groupbyartist"] ) ){
                array_push($group, "art.art_name");
            }
            if( isset($_POST["groupbyalbum"] ) ){
                array_push($group, "alb.alb_name");
            }
            if( isset($_POST["groupbyyear"] ) ) {
                array_push($group, "s.year");
            }
            if(count($group) > 0){
                $query = 'SELECT count(s.s_id), s.title, art.art_name, alb.alb_name
                    , s.year, null, null, 0, sum(s.raw_ratings), sum(s.numb_users_rated)
                    from Songz2 s
                    join Artists art on(s.art_id = art.art_id)
                    join Albums alb on (s.alb_id = alb.alb_id)';
            }else{
                $query = 'SELECT  s.s_id, s.title, art.art_name, alb.alb_name
                    , s.year, s.duration, s.loudness, 0
                    , s.raw_ratings, s.numb_users_rated
                    from Songz2 s
                    join Artists art on(s.art_id = art.art_id)
                    join Albums alb on (s.alb_id = alb.alb_id)';
            }

            if( isset($_POST['user_id']) ){
                $query = substr_replace($query, ' IFNULL(r.rating, 0) ', strpos('0'), 1);
                $query = $query . ' left join Rates r on (r.s_id = s.s_id and r.login_id = \'' . $_POST['user_id'] . '\')';
            }

            $part = array();
            if( isset($_POST["title"] ) ) {
                if(strpos($_POST["title"], '"') === 0 and substr($_POST["title"], -strlen('"')) === '"'){
                    $_POST["title"] = ltrim($_POST["title"], '"');
                    $_POST["title"] = rtrim($_POST["title"], '"');
                    array_push($part, "s.title = '" .  $_POST["title"] . "'");
                }else{
                    array_push($part, "s.title like '%" . join('%', split('/\s/', $_POST["title"])) . "%'");
                }
            }
            if( isset($_POST["artist"] ) ) {
                if(strpos($_POST["artist"], '"') === 0 and substr($_POST["artist"], -strlen('"')) === '"'){
                    $_POST["artist"] = ltrim($_POST["artist"], '"');
                    $_POST["artist"] = rtrim($_POST["artist"], '"');
                    array_push($part, "art.art_name = '" . $_POST["artist"] . "'");
                }else{
                    array_push($part, "art.art_name like '%" . join('%', split('/\s/', $_POST["artist"])) . "%'");
                }
            }
            if( isset($_POST["album"] ) ) {
                if(strpos($_POST["album"], '"') === 0 and substr($_POST["album"], -strlen('"')) === '"'){
                    $_POST["album"] = ltrim($_POST["album"], '"');
                    $_POST["album"] = rtrim($_POST["album"], '"');
                    array_push($part, "alb.alb_name = '" .  $_POST["album"] . "'");
                }else{
                    array_push($part, "alb.alb_name like '%" . join('%', split('/\s/', $_POST["album"])) . "%'");
                }
            }
            if ( isset($_POST["year1"]) and isset($_POST["year2"]) ) {
                array_push($part, 's.year between ' . $_POST["year1"] . ' and ' . $_POST['year2'] . '');
            }
            if ( isset($_POST["loud1"])  and isset($_POST["loud2" ]) ) {
                array_push($part, 's.loudness between ' . $_POST["loud1"] . ' and ' . $_POST["loud2"] . '');
            }
            if ( isset($_POST["dur1"]) and isset($_POST["dur2"] ) ){
                array_push($part, 's.duration between ' . $_POST["dur1"] . ' and ' . $_POST["dur2"]);
            }
            $like = array();
            for($i = 1; $i <= 5; $i++){
                if(isset($_POST['rating' . $i])){
                    array_push($like, $i);
                }
            }
            $inclause  = '(' . join(',', $like) . ')';
            if( count($inclause) > 0) {
                array_push($part, 'ceil(ifnull(s.raw_ratings/s.numb_users_rated, 0))-1 in ' . $inclause);
            }
            if( isset($_POST["userrates"] )){
                array_push($part, 's.numb_users_rated >= ' . $_POST['userrates']);
            }

            if( count($part) > 0 ){
                $query = $query . ' where ' . join(' and ', $part);
            }

            if( count($group) > 0 ){
                $query = $query . ' group by ' . join(', ', $group);
            }
            if( isset($_POST["songcount"] ) ){
                $query = $query . ' having count(s_id) > ' . $_POST["songcount"];
            }

            $order = array();
            $end = '';
            if( isset($_POST["sortdesc"] )) {
                $end = " DESC";
            }else{
                $end = " ASC";
            }
            if( isset($_POST["sortbytitle"] )){
                array_push($order, 's.title' . $end);
            }
            if( isset($_POST["sortbyartist"] )){
                array_push($order, 'art.art_name' . $end);
            }
            if( isset($_POST["sortbyalbum"] )){
                array_push($order, 'alb.alb_name'. $end);
            }
            if( isset($_POST["sortbyyear"] )){
                array_push($order, 's.year' . $end);
            }
            if( isset($_POST["sortbyrating"]) ){
                array_push($order, 'ifnull(s.raw_ratings/s.numb_users_rated, 0)' . $end);
            }
            if( isset($_POST["sortbyloud"] )){
                array_push($order, 's.loudness' . $end);
            }
            if( isset($_POST["sortbydur"] )){
                array_push($order, 's.duration' . $end);
            }
            if( count($order) > 0 ){
                $query = $query . ' order by ' . join(', ', $order);
            }



            #echo $query;
            $result = mysql_query($query);
            if (!$result){
                echo $query;
                die("Query Failed!" . mysql_error());
            }else{
                $return = array();
                while($row = mysql_fetch_array($result, MYSQL_NUM)){
                    array_push($return, $row);
                }
                echo json_encode($return);
                die();
            }
        }

    }elseif(isset($_POST["register"])){

    }elseif(isset($_POST["rate"])){

    }elseif(isset($_POST["play"])){

    }else{
        die("ERROR");
    }

?>
