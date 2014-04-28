<!DOCTYPE html>
<html lang="en">
    <head>
        <title>MusicNet: A Social Network for Music Enthusiasts</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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
                        <td>Duration: </td>
                        <td><input type="text" id="duration"></td>
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
        <br/>

        <div id="advertisements" style="padding-right:2%;float:left;width:20%;">
            <div id="advertexample" style="border:2px dashed;">
                <div align="center" style="font-size:150%;">Ad Name</div>
                <div style="margin:7px;"> Ad Description lorem ipsum lorem ipsum lorem ipsum </div>
            </div>
        </div>
        <div id="searchresults" style="float:left;width:74%;">
            <div id="resultexample" style="overflow:hidden;border:2px solid;">
                <div style="float:left;font-size:400%;padding-right:2%;">1</div>
                <div style="float:left;">
                    <span style="font-size:250%;"> Song Name </span>
                    <span style="color:grey;font-size:150%;">- Artist Name </span>
                    <br/>
                    <span style="font-size:150%">Album Name</span> - (Year) - Duration - Loudness
                 </div>
                 <img style="float:right;" height="84" width="84" src="./play.png" alt="Play" />
                 <div class="rating-container">
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                    <div class="star"></div>
                </div>

          </div>
        </div>
        <script>
            $(function(){
            <?php
            if(isset($_COOKIE["username"])){
                echo "var isLoggedIn = true;\n";
            }else{
                echo "var isLoggedIn = false;\n";
            }
            ?>
            // rating is a number, has_rated a boolean, and s_id as integer
            var createStars = function(rating, has_rated, s_id){
                var ratingcontainer =
                    $("<div>", {style:"float:right;width:30%;", id:s_id+"-rating-container"});

                var title = $("<div>", {id:s_id+"-rating-title"});
                if ( has_rated ){
                    title.text("You Rated The Song: "+rating+" stars");
                }else{
                    title.text("Average Rating: "+rating+" stars");
                }
                title.append("</br>");
                var ul = $("<div>");
                for( var i = 1; i <= 5; i++ ){
                    var star = $('<div>', {class:"star"
                        , style:"display:inline-block;"
                            +"width:10px;"
                            +"height:10px;"
                            +"background-color:#CC0;"
                            +"margin:3px;"
                        , href:"#"});
                    ul.append(star);
                    if( i === rating ){
                        star.prevAll('.star').addBack().css('background-color', '#C00');
                    }
                    star.click(function(){
                        $(this).siblings().css("background-color", '#CC0');
                        $(this).prevAll('.star').addBack().css('background-color', '#C00');
                        $("#"+s_id+"-rating-title").text("You Rated the Song: "+($(this).index()+1)+" stars");
                        rating = ($(this).index()+1);
                    });
                    star.hover(function(){
                        $(this).siblings().addBack().css("background-color", "#CC0");
                        $(this).prevAll('.star').addBack().css('background-color', '#C80');
                    }, function(){
                        $(this).siblings().addBack().css("background-color", "#CC0");
                        $("#"+s_id+"-rating-container div div:eq("+(rating-1)+")")
                            .prevAll('.star').addBack().css('background-color', '#C00');
                    })
                }
                ratingcontainer.append(title);
                ratingcontainer.append(ul);
                return ratingcontainer;
            };
            //Strings describing what to display in the jQuery tag returned
            var createSongDiv = function(rank_txt, s_id, s_name, art, alb, year, dur, loud, u_rate, avg_rate){
                var ret = $("<div>", {style: "overflow:hidden;border:2px solid;"});
                var rank = $("<div>", {style: "float:left;font-size:400%;padding-right:2%;"});
                var info = $("<div>", {style: "float:left;"});
                var song_name = $("<span>", {style:"font-size:250%;"});
                var art_name = $("<span>", {style:"color:grey;gont-size:150%;"});
                var alb_name = $("<span>", {style:"font-size:150%;"});
                var rest = $("<span>");
                var img = $("<img>", {style:"float:right;", height:84, width:84, src:"./play.png", alr:"Play"});
                var stars = '';
                if( u_rate === 0 ){
                    stars = createStars(avg_rate, false, s_id);
                }else{
                    stars = createStars(u_rate, true, s_id);
                }
                rank.text(rank_txt);
                ret.append(rank);
                song_name.text(s_name);
                info.append(song_name)
                art_name.text(" - "+art);
                info.append(art_name);
                info.append("<br/>");
                alb_name.text(alb);
                info.append(alb_name);
                rest.text(" - ("+year+") - "+dur+" - "+loud);
                info.append(rest);
                ret.append(info);
                ret.append(img);
                ret.append(stars);

                return ret;
            };
        });
        </script>
    </body>
</html>

