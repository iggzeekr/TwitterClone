<?php


ob_start();
session_start();

include "./connect.php";


if(isset($_POST['user_register'])){


    $check_user_name = $db->prepare("SELECT * FROM users where user_username=:user_name");
    $check_user_name->execute(array(
        'user_name'=>$_POST['username']
    ));


    $is_registered = $check_user_name->rowCount();

   if($is_registered){

    Header("Location:/register.php?r=kayitlikullanici");
   }else{

    $new_user = $db->prepare("INSERT INTO users SET
    user_name=:user_name,
    user_username=:user_username,
    user_password=:user_password,
    user_register_date=:register_date
    ");

    $new_user->execute(array(
        'user_name'=>$_POST['name'],
        'user_username'=>$_POST['username'],
        'user_password'=>md5($_POST['password']),
        'register_date'=>date_create($datetime = "now", timezone_open("Europe/Istanbul"))->format('Y-m-d H:i:s')
    ));


    if($new_user){
        Header("Location:/login.php?r=kayitbasarili");

    }else{
        Header("Location:/register.php?r=hata");

    }
   }


}

if(isset($_POST['user_login'])){

    $user_name = htmlspecialchars($_POST['username']);
    $pass = md5($_POST['password']);


    $fetch_user = $db->prepare("SELECT * FROM users WHERE user_username=:user_username and user_password=:user_password ");
    $fetch_user->execute(
        array(
            'user_username' => $user_name,
            'user_password' => $pass
        )
    );


    $count = $fetch_user->rowCount();



    if ($count == 1) {

        $_SESSION['user'] = $user_name;

        header("Location:./homepage.php");
        exit;
    } else {


        header("Location:./login.php?r=basarisiz");
    }
}

if(isset($_POST['tweet_post'])){

    //get active user
    $active_user = $db->prepare("SELECT * FROM users where user_username=:user_name");
    $active_user->execute(
        array(
            'user_name' => $_SESSION['user']
        )
    );
    $user = $active_user->fetch(PDO::FETCH_ASSOC);

    //post tweet

    $post_tweet = $db->prepare("INSERT INTO tweets SET
    user_id=:user_id,
    tweet_content=:tweet_content,
    tweet_created_at=:tweet_created_at
    ");

    $post_tweet->execute(array(
        'user_id'=>$user['user_id'],
        'tweet_content'=>$_POST['tweet_content'],
        'tweet_created_at'=>date_create($datetime = "now", timezone_open("Europe/Istanbul"))->format('Y-m-d H:i:s')

    ));
    if($post_tweet){
        Header("Location:/profile.php?r=basarili");

    }else{
        Header("Location:/profile.php?r=hata");

    }
}
?>