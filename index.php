<?php 
session_start();
// if user login sending him to main.php
if (isset ($_SESSION["user_name"])){
    header("location:main.php");
} 

//sql call
$MySQLdb = new PDO("mysql:host=127.0.0.1;dbname=barforum", "root", "");
$MySQLdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//checking about register
if (isset ($_POST['r_username']) && isset($_POST['r_password']) && isset($_POST['r_email']) && isset($_POST['r_fullname'])){
        $cursor = $MySQLdb->prepare("SELECT * FROM users WHERE user_name=:user_name");
        $cursor->execute( array(":user_name"=>$_POST["r_username"]) );
    
        if($cursor->rowCount()){
            $msg = "Username or password already exist";
        }else{
            $cursor = $MySQLdb->prepare("INSERT INTO users (user_name, full_name, password, email , create_date) value (:user_name,:full_name,:password,:email,now())");
            $cursor->execute(array(":user_name"=>$_POST["r_username"], ":password"=>$_POST["r_password"] ,":email"=>$_POST["r_email"] ,":full_name"=>$_POST["r_fullname"]));
            $msg = " User created succesfully";
        } 
}
// sending recovery email

if (isset ($_POST['f_email']) ){
    $msg = " Email Recovery Sent";
}

//checking about login
else if (isset ($_POST['l_username']) && isset($_POST['l_password'])){
            $cursor = $MySQLdb->prepare("SELECT * FROM users WHERE user_name=:username AND password=:password");
            $cursor->execute(array(":username"=>$_POST["l_username"], ":password"=>$_POST["l_password"]));
                if($cursor->rowCount()){
                    $return_value = $cursor-> fetch();
                    $_SESSION["user_name"] = $return_value["user_name"];
                    $_SESSION["user_id"] = $return_value["user_id"];
                    $_SESSION["user_fullname"] = $return_value["full_name"];
                    $_SESSION["user_email"] = $return_value["email"];
                    Header("location:main.php");
            }else{
            $msg = " Wrong username or password!";
}
        }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Welcome to BarForum!</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <h1>Forum Bar</h1>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                
                <div class="panel-body" id="login-panel">
                    <div class="panel-heading">Login</div>
                    <form action="#" method="POST">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="l_username" type="text" class="form-control" name="l_username" placeholder="username">
                        </div>  
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="l_password" type="password" class="form-control" name="l_password" placeholder="password">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                        <br>
                        <a href="#" id="register"><i class="glyphicon glyphicon-info-sign"></i>Register</a>
                        <br>
                        <a href="#" id="forget_password"><i class="glyphicon glyphicon-info-sign"></i>Forget password</a>
                        <div>
                <?php //מדפיס הודעות למשתמש
							if (isset($msg))
                            {
                                echo "<div class='alert alert-default'><strong>Msg:</strong>".$msg."</div>";
                                $msg = null;
                            }
						?>
                </div>
                    </form>
                </div>
                
                <div class="panel-body" id="register-panel" hidden>
                    <div class="panel-heading">Register</div>
                    <form action="#" method="POST">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="r_username" type="text" class="form-control" name="r_username" placeholder="Username">
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="r_fullname" type="text" class="form-control" name="r_fullname" placeholder="Full name">
                        </div>
                        
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="r_password" type="password" class="form-control" name="r_password" placeholder="Password">
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                            <input id="r_email" type="email" class="form-control" name="r_email" placeholder="Email">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                        <a href="#" id="login"><i class="glyphicon glyphicon-info-sign"></i>Login</a>
                        <div>
                <?php
							if (isset($msg)) //מדפיס הודעות למשתמש
                            {
                                echo "<div class='alert alert-default'><strong>Msg:</strong>".$msg."</div>";
                                $msg = null;
                            }
						?>
                </div>
                    </form>
                </div>
                
                <div class="panel-body" id="forget-panel" hidden>
                    <div class="panel-heading">Forget Password</div>
                    <form action="#" method="POST">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="f_email" type="text" class="form-control" name="f_email" placeholder="email">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary btn-block">Send recover Link</button>
                        <div>
                <?php
							if (isset($msg)) //מדפיס הודעות למשתמש
                            {
                                echo "<div class='alert alert-default'><strong>Msg:</strong>".$msg."</div>";
                                $msg = null;
                            }
						?>
                </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
<script>
$("#register").click(function () {
    $("#login-panel").fadeOut(1000);
    $("#forget-panel").fadeOut(1000);
    $("#register-panel").delay(1000).fadeIn(1000);
});
$("#login").click(function () {
    $("#register-panel").fadeOut(1000);
    $("#forget-panel").fadeOut(1000);
    $("#login-panel").delay(1000).fadeIn(1000);
});
$("#forget_password").click(function () {
    $("#login-panel").fadeOut(1000);
    $("#register_panel").fadeOut(1000);
    $("#forget-panel").delay(1000).fadeIn(1000);
});
</script>
    
</body>
    
</html>