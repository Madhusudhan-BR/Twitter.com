<?php

 include "functions.php";
 include "views/header.php";

 if($_SESSION["id"]>0) {
     if ($_GET["page"] == "timeline") {
         include "views/timeline.php";
     } else if ($_GET["page"] == "yourtweets") {
         include "views/yourtweets.php";
     } else if ($_GET["page"] == "search") {
         include "views/search.php";
     } else if ($_GET["page"] == "publicprofiles") {
         include "views/publicprofiles.php";
     } else {
         include "views/home.php";
     }
 } else {
     echo "<h2>   Please sign in / sign up   </h2>";
 }

 include "views/footer.php";

?>