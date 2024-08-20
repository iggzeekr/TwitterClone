<?php

//prevent to follow without logged in
ob_start();
session_start();
if (!isset($_SESSION['user'])) {

    header("location:./homepage.php");
    exit;
}




include './connect.php';

//get active user
$active_user = $db->prepare("SELECT * FROM users where user_username=:user_name");
$active_user->execute(
    array(
        'user_name' => $_SESSION['user']
    )
);
$user = $active_user->fetch(PDO::FETCH_ASSOC);


$follow_user = $db->prepare("INSERT INTO follows SET
user_id =:user_id,
following_user_id=:following_user_id
");
$follow_user->execute(
    array(
        'user_id' => $user['user_id'],
        'following_user_id'=>$_POST['follow_user_id']
    )
);
if($follow_user){
    Header("Location:/homepage.php?r=takipedildi");

}else{
    Header("Location:/homepage.php?r=takiphata");

}


?>