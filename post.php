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
// cheking if post is exist
if (isset ($_POST["post_id_to_show"]) ){
    $post_id_to_show = $_POST["post_id_to_show"];
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>BarForum Post</title>
    <meta charset="utf-8">
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
    <style>
        #longpost {
        max-width: 900px;
        margin: 0 auto;
        overflow: scroll;
   }
        #longcommant{
        max-width: 900px;
        margin: 0 auto;
        overflow: scroll;
   }
        .panel-heading{
            height:30px;
        }
`   </style>
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
<? // getting post info
            $cursor = $MySQLdb->prepare("SELECT * FROM posts WHERE  post_id=:post_id");
            $cursor->execute(array(":post_id"=>$post_id_to_show));
                if($cursor->rowCount()){
                    $return_value = $cursor-> fetch();
                    $_SESSION["post_title_to_show"] = $return_value["post_title"];
                    $_SESSION["full_name_to_show"] = $return_value["full_name"];
                    $_SESSION["post_data_to_show"] = $return_value["post_data"];
                    $_SESSION["post_id_to_show"] = $return_value["post_id"];
                    $_SESSION["post_date_time"] = $return_value["date_time"];
        }
?>
<div class="container" style="margin-top:50px; margin-right:30px;">
    <div class="row" id="page" hidden>
        <!-- send commant form -->
        <div class="col-sm-3">
            <form class="form-group">
                  <h4 style="margin-top:30px;text-align:right;">שלח תגובה</h4>
                  <input style="text-align:right;" type="text" class="form-control" id="commant">
                    <br>
                  <button type="sumbit" id ="send_commant" class="btn btn-primary btn-lg">שלח</button>    
            </form>
        </div>
        <!-- showing post -->
        <div class="col-sm-3">
            <div class="panel-group">
                <div class="panel panel-success">
                      <div style="text-align:right;" class="panel-heading"><? echo $_SESSION["post_date_time"];?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel-group">
                <div class="panel panel-success">
                      <div style="text-align:right;" class="panel-heading"><? echo $_SESSION["full_name_to_show"];?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel-group">
                <div class="panel panel-success">
                      <div style="text-align:right;" class="panel-heading"><? echo $_SESSION["post_title_to_show"];?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div id="longpost" style="text-align:right;height:150px;" class="panel-body"> <? echo $_SESSION["post_data_to_show"];?></div>
                </div>
            </div>
        </div>
  </div>
</div>

    
<div style="" class="container" name="leave_a_commant" >
    <div class="row" id="page">
      <!--מראה את התגובות של אותו הפוסט-->
        <caption><h2 style="text-align:right;margin-right:20px;">תגובות</h2></caption>
    <?php
        $cursor = $MySQLdb->prepare("SELECT * FROM comments WHERE post_id=:post_id");
        $cursor->execute( array(":post_id"=>$_SESSION["post_id_to_show"]) );
        foreach ($cursor->fetchAll() as $obj): ?>
            <div class="col-sm-6"></div>
            <div class="col-sm-3">
                <div class="panel-group">
                    <div class="panel panel-success">
                          <div style="text-align:right;" class="panel-heading"><? echo $obj['full_name']?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="panel-group">
                    <div class="panel panel-success">
                          <div style="text-align:right;" class="panel-heading"><? echo $obj['date_time']?></div>
                    </div>
                </div>
            </div>
             <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <div class="panel-group">
                    <div class="panel panel-success">
                          <div id="longcommant" style="text-align:right;height:65px;" class="panel-body"><? echo $obj['comment_data']?></div>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
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
    //sending commant data to api.php
    $("#send_commant").click(function(){
        $.post("api.php",{"action":"send_comment","post_id":<? echo $_SESSION["post_id_to_show"]?>,"comment_data":$("#commant").val()},function(data){ 
            if (data.success == "true"){
                location.href = "post.php";
           }
        });
    });
</script>
</body>
</html>
            