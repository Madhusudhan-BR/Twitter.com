<?php
include "functions.php";

    $error = "";

    if($_GET["action"] == "loginSignup") {
            //validate email and password

        if(!$_POST["email"]) {
            $error .= "Email field can't be empty<br>";
        }
         if (!$_POST["password"]) {
            $error .= "Password field can't be empty<br>";
        }
         if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) == false ) {
            $error .= "Invalid Email <br>";
        }

        if ($error != "") {
            echo $error;
            exit();
        } else {


            if($_POST["loginActive"] == "0"){
                //sign up user
                // check if there is no other user with this email ID

                $sql = "SELECT * FROM users WHERE email='".$_POST["email"]."'";
                $result = $link->query($sql);

                if(mysqli_num_rows($result) > 0 ) {
                    $error = "This email ID is already used. <br>";
                } else {
                    //insert into DB
                        $sql = "INSERT INTO users (email,password) VALUES ('" . $_POST["email"] . "','" . md5(md5("123456789").$_POST["password"]) . "')";
                        if(mysqli_query($link,$sql)){
                            echo 1;
                            $_SESSION["id"] = mysqli_insert_id($link);
                        }
                        else{
                            echo 0;
                        }
                }

                if($error!=""){
                    echo $error;
                    exit();
                } else {

                }

            } else {
                //sign up user

                $sql = "SELECT * FROM users WHERE email='".$_POST["email"]."'";
                $result = $link->query($sql);

                $row = mysqli_fetch_assoc($result);
                $userEnteredpassword = md5(md5("123456789").$_POST["password"]);
                if($row["password"] == $userEnteredpassword) {
                    $_SESSION["id"] = $row["id"];
                    echo 1;
                }
                else {

                    echo "Such email Id and password combinations doesn't exist";
                }
            }
        }

    }

    else if($_GET["action"] == "toggleFollow") {


        $sql = "SELECT * FROM isFollowing WHERE follower='".$_SESSION["id"]."' AND isFollowing='" . $_POST["userId"] ."'";


        $result = mysqli_query($link,$sql);

        if(mysqli_num_rows($result)>0) {
            //unfollow user

            $row = mysqli_fetch_assoc($result);

            mysqli_query($link,"DELETE FROM isFollowing WHERE id = '" . $row["id"]."'LIMIT 1");
            echo 1;
        } else {
            //follow user

            $sql = "INSERT INTO isFollowing (follower,isFollowing) VALUES ('" . $_SESSION["id"]."','".  $_POST["userId"]."')";

            mysqli_query($link,$sql);

            echo 2;

        }
    }

    else if($_GET["action"] == "postTweet"){

        if(!$_POST["tweetContent"]){
            echo "No Tweet Content";
        } else if (strlen($_POST["tweetContent"])>140) {
            echo "Your Tweet is greater than 140 characters";
        } else {
            //post tweet

            $sql = "INSERT INTO tweets (userId,tweet,date) VALUES ('" . $_SESSION["id"]."','".  $_POST["tweetContent"]."',now())";

            if(mysqli_query($link,$sql)){
                echo 1;
            }


        }
    }

?>