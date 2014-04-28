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
            echo json_encode($response);
            die();
        }
    }elseif(isset($_POST["search"])){

    }elseif(isset($_POST["register"])){

    }elseif(isset($_POST["rate"])){

    }elseif(isset($_POST["play"])){

    }else{
        die("ERROR");
    }

?>
