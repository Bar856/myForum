<?php

session_start();
// if user not login sending him to login page
if (!isset($_SESSION["user_name"]))
{
    header("location: index.php");
}
// creats the session user info
$user_name = $_SESSION["user_name"];
$user_id = $_SESSION["user_id"];
$full_name = $_SESSION["user_fullname"];
$user_email = $_SESSION["user_email"];

//data base call
$MySQLdb = new PDO("mysql:host=127.0.0.1;dbname=barforum", "root", "");
$MySQLdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// get action for switch function by post and work with it
$action = $_POST["action"];
//reciving topic_id_to_show for topics page
if (isset ($_GET["topic_id_to_show"])){
    header("Location: topics.php");
    $_SESSION["topic_id_to_show"] = $_GET["topic_id_to_show"];
}

//reciving data for add a commant
if (isset ($_POST["post_id"]) && isset ($_POST["comment_data"])){
    $comment_data=$_POST["comment_data"];
    $post_id_to_commant = $_POST["post_id"];
}
//reciving post_id_to_show to post page
if (isset ($_POST["post_id_to_show"])){
    $post_id_to_show = $_POST["post_id_to_show"];
}

if (isset ($_POST["data"])){
    $data = $_POST["data"];
}

// reciving by POST the veriables to add new post
if (isset ($_POST["subject"]) && isset ($_POST["title"]) && isset ($_POST["post_data"])){
    $subject = $_POST["subject"];
    $title = $_POST["title"];
    $post_data= $_POST["post_data"];
}
// reciving by POST the veriables to change username and password
if (isset($_POST["new_username"]) && isset($_POST["new_password"]) && isset($_POST["old_password"]) ){
    $new_username = $_POST["new_username"];
    $new_password = $_POST["new_password"];
    $old_password = $_POST["old_password"];
}
header("content-type: application/json");

switch($action){
    case "send_comment":
        if ($comment_data != null){
            //inserting commant to the post by post id
            $retval = "";
            $cursor = $MySQLdb->prepare("INSERT INTO comments (post_id, user_id,full_name,comment_data,date_time) value (:post_id,:user_id,:full_name,:comment_data,now())");
            $cursor->execute(array(":post_id"=>$post_id_to_commant,":user_id"=>$user_id,":full_name"=>$full_name,":comment_data"=>$comment_data));
            die('{"success":true,"data":"'.$retval.'"}');
        }else{
            die('{"success":false,"data":"'.$retval.'"}');
        }
        break;
    case "show_post":
        //checking id the post are exist if not sending the user to main page   
        $cursor = $MySQLdb->prepare("SELECT * FROM posts WHERE post_id=:post_id");
        $cursor->execute( array(":post_id"=>$post_id_to_show) );
        if($cursor->rowCount()){
            echo '{"success":"true"}';
        }else{
            echo '{"success":"false"}';
            header("location: main.php");
        }
        break;
        
    case "create_post":
        //inserting new post to the db
        $retval = "";
        if ($subject==null or $title==null or $post_data ==null){
            die('{"success":"false","data":"'.$retval.'"}');
        }else{
        $cursor = $MySQLdb->prepare("INSERT INTO posts (user_id, topic_id,full_name,post_title,post_data,date_time) value (:user_id,:topic_id,:full_name,:post_title,:post_data,now())");
        $cursor->execute(array(":user_id"=>$user_id,":topic_id"=>$subject,":full_name"=>$full_name,":post_title"=>$title,":post_data"=>$post_data));
        $_SESSION["topic_id_to_show"] = $subject;
        die('{"success":"true","data":"'.$retval.'"}');
        }
        break;
        // changing the pass or user_name
    case "change":
        if ($new_username == null or $new_password==null or $old_password==null){
            $_SESSION["msg"] = "שגיאה: אחד או יותר מהשדות ריקים";
            echo '{"success":"false"}';
            
        }else{
        $cursor = $MySQLdb->prepare("SELECT * FROM users WHERE user_name=:user_name");
        $cursor->execute( array(":user_name"=>$new_username) );
        if($cursor->rowCount()){
            $_SESSION["msg"] = "שגיאה: שם משתמש תפוס";
            echo '{"success":"false"}';
            
        }else{
        $cursor = $MySQLdb->prepare("SELECT * FROM users WHERE password=:password");
        $cursor->execute( array(":password"=>$old_password) );
        //מעדכן את השם משתמש והססמא של המשתמש ובודק האם הססמא הישנה שלו זהה לססמא במאגר
        if($cursor->rowCount()){
            $cursor = $MySQLdb->prepare("UPDATE `users` SET `user_name` =:user_name ,`password` =:password  WHERE `users`.`user_id` = :user_id;");
            $cursor->execute(array(":user_id"=>$user_id,":user_name"=>$new_username,":password"=>$new_password));
            $_SESSION["user_name"] = $new_username;
            $_SESSION["msg"] = "עודכן בהצלחה";
            echo '{"success":"true"}';
        }else{
            $_SESSION["msg"] = "שגיאה: ססמא לא נכונה";
            echo '{"success":"false"}';
        }
      }}
        break;
        //logout button
    case "logout":
        session_destroy();
        echo '{"success":"true"}';
        break;
        
    default:
        echo '{"success":"false"}';
        die();
}
?>