<?php

    session_start();

    $link = mysqli_connect("shareddb1c.hosting.stackcp.net","twitter-3230ad37","7vsAzylwd+xj","twitter-3230ad37");
    if(mysqli_connect_error()) {
        die(mysqli_connect_error());
    }

    if($_GET["function"] == "logout") {
        session_unset();
    }

    function time_since($since) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'minute'),
            array(1 , 'second')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;
    }

    function displayTweets($type){
        $whereClause = "";
        global $link;

        if($type == "public") {
            $whereClause = "";
        } elseif ($type == "isFollowing") {
            $sql = "SELECT * FROM isFollowing WHERE follower='".$_SESSION["id"]."' ";
           $result =  mysqli_query($link,$sql);
            $whereClause = "";


            while($row = mysqli_fetch_assoc($result)){
                if ($whereClause == "") $whereClause = "WHERE";
                else {
                    $whereClause.= " OR";
                }
                $whereClause .= " userId = " . $row["isFollowing"];

            }
        }else if($type == "yourtweets"){
            $whereClause = " WHERE userId = " . $_SESSION["id"];
        } else if($type == "search") {
            $whereClause = " WHERE tweet LIKE '%" . $_GET["q"]."%'";
        } else if(is_numeric($type)){
            $usersql = "SELECT * FROM users WHERE id = '".$type."' ";
            $userresult = mysqli_query($link,$usersql);
            $user = mysqli_fetch_assoc($userresult);
            echo "<h2>" . $user["email"]. "'s tweets" ."</h2>". "<br>";
            $whereClause = " WHERE userId = " . $type . " ";
        }

        $sql = "SELECT * FROM tweets " . $whereClause . "ORDER BY date DESC LIMIT 10";
        //echo $sql;
        $result = mysqli_query($link,$sql);

        if(mysqli_num_rows($result) == 0) {
            echo "There are no tweets to display";
        } else {
            while($row = mysqli_fetch_assoc($result)){
                $usersql = "SELECT * FROM users WHERE id = '".$row["userId"]."'";
                $userresult = mysqli_query($link,$usersql);
                $user = mysqli_fetch_assoc($userresult);

                echo "<div class='tweet'><p>". $user["email"]. " <span class='time'>". time_since(time() - strtotime($row["date"])) . " ago </span></p>";

                echo "<p>". $row["tweet"] . "</p>";
                echo "<a class ='toggleFollow' href='#' data-userId='".$row["userId"] ."'><p>";

                $followingsql = "SELECT * FROM isFollowing WHERE follower='".$_SESSION["id"]."' AND isFollowing='" . $row["userId"] ."'";


                $followresult = mysqli_query($link,$followingsql);

                if(mysqli_num_rows($followresult)>0) {
                    echo "Unfollow";
                } else {
                    echo "Follow";
                }


                echo "</p></a> </div>";
            }
        }
    }

    function displaySearch(){
        echo '<form class="form-inline"> 
            <div class="form-group mx-sm-3">
                <label for="search" class="sr-only">Search</label>
                <input name="page" value="search" type="hidden" >
                <input type="text" class="form-control" id="search" name="q" placeholder="search">
            </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>';
    }

    function displayTweetBox() {

        if($_SESSION["id"] > 0){
            echo '<div class="form"> 
                <div id="tweetSuccess" class="alert alert-success">Tweet posted Successfully</div>
                <div id="tweetFailure" class="alert alert-danger"></div>
                <div class="form-group mx-sm-3">
                <textarea type="text" class="form-control" id="tweetContent" rows="3"></textarea>
            </div>
                <button type="submit" class="btn btn-primary" id="tweetButton">Post Tweet</button>
            </div>';
        }


    }

    function displayUsers(){
        global $link;
        $sql = "SELECT * FROM users";

        $result = mysqli_query($link,$sql);

        while($row = mysqli_fetch_assoc($result)){
            echo  "<p> <a href='?page=publicprofiles&userId= ". $row["id"]."'>" . $row["email"] . "</a></p>";
        }

    }
?>