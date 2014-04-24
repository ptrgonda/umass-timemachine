<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <title>MusicNet: The Music Database</title>
    </head>
    <body>
        <div align="right">
            <?php
            if (isset($_POST["deletecookie"])){
                setcookie("username", "", time()-3600);
                unset($_COOKIE["username"]);
            }
            else if ( isset($_POST["uid"]) && isset($_POST["password"]) ){
                $connection = mysql_connect("cs445sql", "ngonsalv", "EL083ngo");
                if (!$connection){
                    die ("Couldn't connect to mysql server! The error was: " . mysql_error());
                }
                if (!mysql_select_db("ggr")){
                    die ("Couldn't select a database! Error: " . mysql_error());
                }
                $query = "SELECT u.name, u.password FROM Users u WHERE u.login_id='" . $_POST["uid"] . "'";
                $result = mysql_query($query);
                if (!$result){
                    die("User not found!" . mysql_error());
                }else{
                    if ($row = mysql_fetch_array($result)){
                        $name = $row[0];
                        $pass = $row[1];
                        if ($pass == $_POST["password"]){
                            setcookie("username", $name, time()+3600);
                            $_COOKIE["username"] = $name;
                        }
                    }
                }
            }
            if (isset($_COOKIE["username"])){
            ?>
            <span>Welcome <?php echo $_COOKIE["username"] ?> !
                <form method="post" action="index.php" style="display:inline;">
                    <input type="hidden" name="deletecookie" />
                    <input type="submit" value="Logout" />
                </form>
            </span>
            <?php
            }else{
            ?>
            <span>You don't seem to be logged in!
                <form style='display:inline;' method="post" action="index.php">
                    <input type="text" name="uid" />
                    <input type="text" name="password" />
                    <input type="submit" value="Submit">
                </form>
            </span>
            <?php
            }
            ?>

        </div>

        <h1 align="center" style="font-size:600%">MusicNet</h1>

        <form action="" >
            <center>
                <input type="text" size="100"/><br/>
                <div style="width:25%" >
                <a id="advancedbutton" href="#" >Advanced Search</a>
                <table id="advanced" >
                    <tr>
                        <td>Title:</td> 
                        <td><input type="text" id="title" /></td>
                    </tr>
                    <tr>
                        <td>Artist:</td>
                        <td><input type="text" id="artist" /></td>
                    </tr>
                    <tr>
                        <td>Album:</td>
                        <td><input type="text" id="album" /></td>
                    </tr>
                    <tr>
                        <td>Year:</td> 
                        <td><input type="text" id="year" /></td>
                    </tr>
                    <tr>
                        <td>Average Rating:</td>
                        <td> 
                            <input type="checkbox" id="rating1"/>1
                            <input type="checkbox" id="rating2"/>2
                            <input type="checkbox" id="rating3"/>3
                            <input type="checkbox" id="rating4"/>4
                            <input type="checkbox" id="rating5"/>5
                        </td>
                    </tr>
                    <tr>
                        <td>Loudness: </td>
                        <td><input type="text" id="loudness"></td>
                    </tr>
                    <tr>
                        <td>Like Terms:</td>
                        <td>TO BE IMPLEMENTED MULTISELECT OF ALL TERMS</td>
                    </tr>
                </table>
                </div>
                <br/>
                <input type="submit" value="Search">
            </center>
                <script>
                $("#advanced").hide();
                var flip = true;
                $('#advancedbutton').click(function(){
                    if(flip){
                        $("#advanced").show("slow");
                        flip = false;
                    }else{
                        $("#advanced").hide();
                        flip = true;
                    }
                });
                </script>
        </form>

        <div id="advertisements" style="float:left;width:25%;">elo</div>
        <div id="searchresults" style="float:left;width:74%;">hello</div>
    </body>
</html>

