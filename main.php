<?php
session_start();
if (!isset($_SESSION["user_name"])){
    header("location: index.php");
}
$user_name = $_SESSION["user_name"];
$user_id = $_SESSION["user_id"];
$full_name = $_SESSION["user_fullname"];
$user_email = $_SESSION["user_email"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>BarForum Main Page</title>
    <meta charset="utf-8">
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div style="" class="navbar-header">
            <a class="navbar-brand" href="main.php"><p class = text-dark>BarForum</p></a>
        </div>
        <div style="" class="navbar-header">
            <a class="navbar-brand"><p class = text-dark>USERNAME: <? echo $user_name ?></p></a>
        </div>
        <div  style="width: 27%;"class="navbar-header">
            <a class="btn btn-danger navbar-btn" href="change.php">Change User/pass</a>
        </div>
        <div style="width: 10%;" class="navbar-header">
            <a class="btn btn-danger navbar-btn" href="main.php">עמוד ראשי</a>
        </div>
        <div  style="width: 10%;"class="navbar-header">
            <a class="btn btn-danger navbar-btn" href="create_post.php">צור פוסט</a>
        </div>
        <ul class="nav navbar-nav" style="float: right">
            <li><a href="#" id="logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
        </ul>
        
        <!-- חיפוש-->
        <form class="navbar-form navbar-left" action="search.php" method="get">
              <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search titles and posts">
                <div class="input-group-btn">
                  <button class="btn btn-default" type="submit">
                    <i class="glyphicon glyphicon-search"></i>
                  </button>
                </div>
              </div>
        </form>
    </div>
</nav>

<div class="container" style="margin-top:50px">
    <div class="row" id="page" hidden>
        <div class="col-md-3">
            <br>
            <br>
            <div class="panel panel-info">
                <div class="panel-heading">Account Info</div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <p class = text-dark>USERNAME : <?php echo $user_name;?></p>
                        </li>
                        <li class="list-group-item">
                            <p class = text-dark>EMAIL : <?php echo $user_email;?></p>
                        </li>
                        <br>
                        <form action="change.php">
                            <button id="change" type="submit" class="btn btn-default">Change username/password</button>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
      <div class="col-sm-6">
        <div class="btn-group" style="margin-top:50px;">
            <h2>ברוכים הבאים! לחצו על נושא להצגת הפוסטים</h2>
            <br>
           <button type="button" id="cars" class="btn btn-primary btn-lg">מכוניות</button>
            <button type="button" id="computers" class="btn btn-primary btn-lg">מחשבים</button>
            <button type="button" id="software" class="btn btn-primary btn-lg">תוכנות</button>
            <button type="button" id="talking" class="btn btn-primary btn-lg">דיבורים</button>
            <button type="button" id="sell_buy" class="btn btn-primary btn-lg">מכירות/קניות</button>
            <button type="button" id="help" class="btn btn-primary btn-lg ">עזרה</button>
        </div>
      </div>
    </div>
</div>       
<script>
	$("#page").slideDown("slow");
    
    $("#logout").click(function(){
        $.post("api.php",{"action":"logout"},function(data){    
            if (data.success == "true"){
               location.href = "index.php";
           }
        });
    });
    
    //sending get request to api and refernce to topics.php to see all post from this topic that chosen from btns
    $("#cars").click(function(){        
                    $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {topic_id_to_show:1},
                success: function(data) {
                    window.location = "topics.php";
                    console.log(data); // Inspect this in your console
                }
            });
        });
    $("#computers").click(function(){        
                    $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {topic_id_to_show:2},
                success: function(data) {
                    window.location = "topics.php";
                    console.log(data); // Inspect this in your console
                }
            });
        });
    $("#software").click(function(){        
                    $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {topic_id_to_show:3},
                success: function(data) {
                    window.location = "topics.php";
                    console.log(data); // Inspect this in your console
                }
            });
        });
    $("#talking").click(function(){        
                    $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {topic_id_to_show:4},
                success: function(data) {
                    window.location = "topics.php";
                    console.log(data); // Inspect this in your console
                }
            });
        });
    $("#sell_buy").click(function(){        
                    $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {topic_id_to_show:5},
                success: function(data) {
                    window.location = "topics.php";
                    console.log(data); // Inspect this in your console
                }
            });
        });
    $("#help").click(function(){        
                    $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {topic_id_to_show:6},
                success: function(data) {
                    window.location = "topics.php";
                    console.log(data); // Inspect this in your console
                }
            });
        });
    
</script>
</body>
</html>
