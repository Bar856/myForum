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

//get by post the topic id to show the post from there
if (isset ($_SESSION["topic_id_to_show"]) ){
    $topic_id_to_show = $_SESSION["topic_id_to_show"];
        switch($topic_id_to_show){
            case "1":
                $topic_name_to_show = "מכוניות";
                break;
            case "2":
                $topic_name_to_show = "מחשבים"; 
                break;
            case "3":
                $topic_name_to_show = "תכונות"; 
                break;
            case "4":
                $topic_name_to_show = "דיבורים"; 
                break;
            case "5":
                $topic_name_to_show = "מכירה/קנייה"; 
                break;
            case "6":
                $topic_name_to_show = "עזרה"; 
                break;    
        }
}else{
    header("location: main.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>BarForum Topic: <? echo $topic_name_to_show; ?></title>
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
      <div class="col-sm-9">
        <table class="table" id = "posts_table">
          <!--ומכניס נתונים-->
            <caption><h2 style="text-align:center"><? echo $topic_name_to_show ; ?></h2></caption>
            <tr class="success">
                <th style="text-align:center"> מספר פוסט 
                <th style="text-align:center"> שם היוצר 
                <th style="text-align:center"> כותרת 
            </tr>
            <?php
                $cursor = $MySQLdb->prepare("SELECT * FROM posts WHERE topic_id=:topic_id");
                $cursor->execute( array(":topic_id"=>$topic_id_to_show) );
                foreach ($cursor->fetchAll() as $obj): ?>
            <tr>
                 <td class="post_id_c" style="text-align:center"><? echo $obj['post_id'] ?></td>
                 <td style="text-align:center"><? echo $obj['full_name']?></td>
                 <td style="text-align:center"><a class="click_title"><? echo $obj['post_title']?></a></td>
            </tr>
                <? endforeach; ?>    
          </table> 
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
    $("#submitchange").click(function(){
        $.post("api.php",{"action":"change","new_username":$("#new_username").val(),"new_password":$("#new_password").val(),"old_password":$("#old_password").val()},function(data){ 
            if (data.success == "true"){
                location.href = "main.php";
           }else{
               document.getElementById("old_password").value='wrong';
                $("#old_password").css('color', 'red');
           }
        });
    });
    $(".click_title").click(function(){
        var $item = $(this).closest("tr").find(".post_id_c").text();
                if($item){
                    $.ajax({
                url: 'post.php',
                type: 'POST',
                data: {post_id_to_show:$item},
                success: function(data) {
                    location.href = "post.php";
                    console.log(data); // Inspect this in your console
                }
            });
        };
    });
</script>
</body>
</html>