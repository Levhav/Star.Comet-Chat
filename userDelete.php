<?php
 
if ($_SERVER['HTTP_X_REAL_IP'] != "159.8.8.107")
{
    die("Нет доступа с ip ".$_SERVER['HTTP_X_REAL_IP']); 
}

include './config.php';
include './common.php';
  
$user_id = (int)$_GET['id'];


$result = mysqli_query(app::conf()->getDB(), "SELECT * FROM `messages` where (from_user_id = ".$user_id." or to_user_id = ".$user_id.") and message like \"[[img%\""); 
if(mysqli_num_rows($result))
{
    while($row = mysqli_fetch_assoc($result))
    {
        $msg = preg_replace("/\[\[img=(.*)\]\]/", "usersFile/$1", $row['message']); 
        
        if(@unlink($msg))
        {
            echo "Успешно удалён:".$msg."\n";
        }
        else
        {
            echo "Не удалось удалить: ".$msg."\n";
        }
    }
} 


mysqli_query(app::conf()->getDB(), "delete FROM `messages` where from_user_id = ".$user_id." or to_user_id = ".$user_id.""); 
mysqli_query(app::conf()->getDB(), "delete FROM `users_relations` WHERE user_id = ".$user_id." or to_user_id = ".$user_id.""); 
 


