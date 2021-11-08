<?php
session_start();
if (!isset($_SESSION["user_name"])){
    header("location: index.php");
}
$user_name = $_SESSION["user_name"];
$user_id = $_SESSION["user_id"];
$full_name = $_SESSION["user_fullname"];
$user_email = $_SESSION["user_email"];


//sql call
$MySQLdb = new PDO("mysql:host=127.0.0.1;dbname=barforum", "root", "");
$MySQLdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
<!-- designing the subject selecbox -->
<style>
    #subject{
        height:30px;
        width: 300;
        font-size:13pt;
        text-indent: 120px; 
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>BarForum Create Post</title>
    <meta charset="utf-8">
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<!-- navbar -->
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
<!-- create post form -->
<div class="container" style="margin-top:140px" >
    <div class="row" id="page" hidden>
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
        <h2 style="text-align:right;">צור פוסט</h2>
        <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <select name="subject" id="subject">
                              <option value="1">מכוניות</option>
                              <option value="2">מחשבים</option>
                              <option value="3">תוכנות</option>
                              <option value="4">דיבורים</option>
                              <option value="5">מכירות/קניות</option>
                              <option value="6">עזרה</option>
                        </select>
                    </div>
                    <div class="col-sm-3" ><h4 style="margin-top:2px;text-align:right;">נושא</h4></div>
                </div>
            </div>
        <div class="form-group">
              <h4 style="text-align:right;">כותרת</h4>
              <input style="text-align:right;" type="text" class="form-control" id="title">
        </div>
        <div class="form-group">
              <h4 style="text-align:right;">פוסט</h4>
              <textarea style="text-align:right;" class="form-control" rows="10" id="post"></textarea>
        </div> 
                <br>
              <button id="create_post_btn" type="submit" class="btn btn-primary btn-block">צור פוסט</button>
    </div></div>
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
    // submit new post - if one of the fields is empty is getting error by url
    $("#create_post_btn").click(function(){
        $.post("api.php",{"action":"create_post","subject":$('#subject').val(),"title":$("#title").val(),"post_data":$("#post").val()},function(data){
            if (data.success == "true"){
                location.href = "topics.php";
           }else{
               location.href = "#error";
           }
        });
    });
           
</script>
</body>
</html>
            