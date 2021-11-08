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
    <title>BarForum Change username/password</title>
    <meta charset="utf-8">
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<!-- bav code -->
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
    <!-- form of changing info -->
<div class="container" style="margin-top:50px">
    <div class="row" id="page" hidden>
        <div class="col-md-3"></div>
        <div class="col-md-9" style="margin-top:50px">
            <form class="form-horizontal">
  <div class="form-group">
    <label class="control-label col-sm-2" for="new_username">New username:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="new_username" placeholder="Enter new user name">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="new_password">New password:</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="new_password" placeholder="New password">
    </div>
  </div>
    <div class="form-group">
    <label class="control-label col-sm-2" for="old_password">Old password to submit changes:</label>
      <div class="col-sm-10">
      <input type="password" class="form-control" id="old_password" placeholder="Old password">
      </div>
    </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button id="submitchange" type="submit" class="btn btn-default">change!</button>    
        </div>
    </div>
        <div>
        <?php
                    if (isset($_SESSION["msg"])) //מדפיס הודעות למשתמש
                    {
                        echo "<div style='text-align:right;' class='alert alert-default'><strong>הודעה:</strong>".$_SESSION["msg"]."</div>";
                        $_SESSION["msg"] = null;
                    }
                ?>
        </div>
    </form>
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
        //sending values to verify in api.php and getting msgs as well
    $("#submitchange").click(function(){
        $.post("api.php",{"action":"change","new_username":$("#new_username").val(),"new_password":$("#new_password").val(),"old_password":$("#old_password").val()},function(data){ 
            if (data.success == "true"){
                location.href = "#";
           }else{
               location.href = "#";
           }
        });
    });
    
    
</script>
</body>
</html>
