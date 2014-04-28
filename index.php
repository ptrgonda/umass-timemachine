<!DOCTYPE html>
<html lang="en">
    <head>
        <title>MusicNet: A Social Network for Music Enthusiasts</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <style>
.play_border {
    margin:5px;
    border: 2px solid rgba(0,0,0,0.7);
    -webkit-border-radius: 100%;
    -moz-border-radius: 100%;
    border-radius: 100%;
    width: 40px;
    height: 40px;
    -webkit-box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.5);
    -moz-box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.5);
    box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.5);
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    -ms-transition: all 0.5s ease;
    transition: all 0.5s ease;
    cursor: pointer;
}
.play_border:hover{
    border-color: transparent;
    -webkit-box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.2);
    box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.2);
}
.play_border:hover .play_button{
    border-left: 10px solid rgba(0,0,0,0.5);
}
.play_border:active,.play_border:focus{
    -webkit-box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 0px 0px 5.play_button:hoverpx 2px rgba(0, 0, 0, 0.2);
    box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.2);
}
.play_button {
    position:relative;
    top: 10px;
    left: 40%;
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-left: 10px solid rgba(0,0,0,0.8);
}
        </style>
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
                <input id="searchbar" type="text" size="100"/><br/>
                <div >
                <a id="advancedbutton" href="#" >Advanced Search</a>
                <table id="advanced" >
                    <tr>
                        <td></td>
                        <td></td>
                        <td align="right">Reverse! Sort Desc <input type="checkbox" id="sortdesc" /> </td>
                    </tr>
                    <tr>
                        <td>Title:</td>
                        <td><input type="text" id="title"  size=25 /></td>
                        <td align="right">Sort by Title <input type="checkbox" id="sortbytitle" /></td>
                        <td align="right">Group by Title <input type="checkbox" id="groupbytitle" /></td>
                    </tr>
                    <tr>
                        <td>Artist:</td>
                        <td><input type="text" id="artist" size=25  /></td>
                        <td align="right">Sort by Artist <input type="checkbox" id="sortbyartist" /></td>
                        <td align="right">Group by Artist <input type="checkbox" id="groupbyartist" /></td>
                    </tr>
                    <tr>
                        <td>Album:</td>
                        <td><input type="text" id="album" size=25 /></td>
                        <td align="right">Sort by Album<input type="checkbox" id="sortbyalbum" /></td>
                        <td align="right">Group by Album <input type="checkbox" id="groupbyalbum" /></td>
                    </tr>
                    <tr>
                        <td>Year:</td>
                        <td> between <input type="text" id="year1" size="4" />
                        and <input type="text" id="year2" size="4" /></td>
                        <td align="right">Sort by Year <input type="checkbox" id="sortbyyear" /></td>
                        <td align="right">Group by Year <input type="checkbox" id="groupbyyear" /></td>
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
                        <td align="right">Sort by Rating<input type="checkbox" id="sortbyrating" /></td>
                        <td> <input type="text" id="userrates" size=6 />+ User Ratings</td>
                    </tr>
                    <tr>
                        <td>Loudness: </td>
                        <td> between <input type="text" id="loud1" size=4 /> and
                        <input type="text" id="loud2" size=4 /></td>
                        <td align="right">Sort by Loudness <input type="checkbox" id="sortbyloud" /></td>
                        <td align="right"><input type="text" id="songcount" size=4 />+ Grouped Songs</td>
                    </tr>
                    <tr>
                        <td>Duration: </td>
                        <td> between <input type="text" id="dur1" size=4 /> and
                        <input type="text" id="dur2" size=4 /></td>
                        <td align="right">Sort by Duration <input type="checkbox" id="sortbydur" /></td>
                    </tr>
                </table>
                </div>
                <br/>
                <input type="submit" value="Search" id="submit" />
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
                    $("<div>", {style:"float:right;width:30%;padding:17px;", id:s_id+"-rating-container"});

                var title = $("<div>", {id:s_id+"-rating-title"});
                if ( has_rated ){
                    title.text("You Rated The Song: "+rating+" stars");
                }else{
                    if(isNaN(rating)){
                        title.text("Average Rating: Not Available");
                    }else{
                        title.text("Average Rating: "+rating.toFixed(2)+" stars");
                    }
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
                var ret = $("<div>", {style: "overflow:hidden;padding-bottom:10px;border:2px solid;"});
                var rank = $("<div>", {style: "float:left;font-size:400%;padding-right:2%;"});
                var info = $("<div>", {style: "float:left;"});
                var song_name = $("<span>", {style:"font-size:250%;"});
                var art_name = $("<span>", {style:"color:grey;font-size:150%;"});
                var alb_name = $("<span>", {style:"font-size:150%;"});
                var rest = $("<span>");
                var play = $('<div>', {style:"float:right;padding:5px;",class:"play_border"});
                play.append($('<div>', {class:"play_button"}));
                var stars = '';
                if( u_rate == 0 ){
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
                ret.append(play);
                ret.append(stars);

                return ret;
            };
            $("#submit").click(function(){
                var data = {};
                if($("#searchbar").val() !== ''){
                    data["simple"] = $("#searchbar").val();
                }else{
                    $("#advanced input").each(function(){
                        if($(this).val() != null && $(this).val() != ""){
                            if($(this).val() === "on" && $(this).is(':checked') && this.type==='checkbox'){
                                data[$(this).attr('id')] = $(this).val();
                            }else if( this.type !== 'checkbox' && this.type !== 'submit'){
                                data[$(this).attr('id')] = $(this).val();
                            }
                        }
                    });
                }
                data['search'] = 1;
                $.post('/php-wrapper/ggr/server.php', data, function(suc){
                    var i = 1;
                    $("#searchresults").html("");
                    for (var key in suc){
                        $('#searchresults').append(createSongDiv(i++, suc[key][0], suc[key][1]
                            , suc[key][2], suc[key][3], suc[key][4]
                            , suc[key][5], suc[key][6], suc[key][7]
                            , parseFloat(suc[key][8]/suc[key][9])));
                    }
                });
            });

        });
        </script>
    </body>
</html>

