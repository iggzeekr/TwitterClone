<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php

    //check user is logged in
    
    ob_start();
    session_start();
    if (!isset($_SESSION['user'])) {

        header("location:./login.php?r=girisyap");
    }


    //db connection
    
    include "./connect.php";


    //get logged in user
    $active_user = $db->prepare("SELECT * FROM users where user_username=:user_name");
    $active_user->execute(
        array(
            'user_name' => $_SESSION['user']
        )
    );
    $user = $active_user->fetch(PDO::FETCH_ASSOC);

    //following users
    $following = $db->prepare("SELECT * FROM follows where user_id=:user_id");
    $following->execute(
        array(
            'user_id' => $user['user_id']
        )
    );
    $following_array = array();
    while ($following_fetch = $following->fetch(PDO::FETCH_ASSOC)) {
        array_push($following_array, $following_fetch['following_user_id']);
    }


    //get tweets
    if (!empty($following_array)) {
        $user_tweets = $db->prepare("SELECT * FROM tweets where user_id in (" . implode(',', $following_array) . ") order by tweet_created_at desc");
        $user_tweets->execute();
    }




    ?>
    <title>Homepage</title>
</head>

<body>
    <div class="container">
        <div class="card">
            <span>@
                <?php echo $user['user_name']; ?>
            </span>
            <form action="homepage.php" method="get" style="padding-top:20px">

                <input type="text" name="search" placeholder="Search users" required>
                <button type="submit">Search</button>
            </form>
            <?php
            if (isset($_GET['r']) && ($_GET['r'] == 'takipedildi')) {
                echo "<h4>User is followed successfully.</h4>";
            } elseif (isset($_GET['r']) && ($_GET['r'] == 'takiphata')) {
                echo "<h4>Something went wrong while following user!</h4>";

            }
            ?>
            <?php if (isset($_GET['search'])) { ?>


                <h3>Search results:</h3>
                <?php

                $clause = $_GET['search'];
                $get_users = $db->prepare("SELECT * FROM users WHERE user_name LIKE '%$clause%' ");
                $get_users->execute();

                while ($search_user = $get_users->fetch(PDO::FETCH_ASSOC)) {
                    if($search_user['user_id']!=$user['user_id']){

                    ?>
                    <div class="card" style="padding-bottom:10px">
                        <p><b>Username: </b> <span>
                                <?php echo $search_user['user_username']; ?>
                            </span> </p>
                        <p><b>Full Name: </b> <span>
                                <?php echo $search_user['user_name']; ?>
                            </span> </p>


                        <?php
                        //check if already following
                
                        $is_following = $db->prepare("SELECT * FROM follows where user_id=:user_id and following_user_id=:f_user_id");
                        $is_following->execute(
                            array(
                                'user_id' => $user['user_id'],
                                'f_user_id' => $search_user['user_id']
                            )
                        );
                        $count = $is_following->rowCount();
                        if ($count == 0) {
                            ?>
                            <form action="./follow.php" method="post">
                                <input type="hidden" name="follow_user_id" value="<?php echo $search_user['user_id']; ?>">

                                <input type="submit" value="Follow">
                            </form>

                        <?php } ?>
                    </div>
                    <?php
                }} ?>

                <br>
            <?php } else { ?>
                <h2>Tweets from the Users You Follow</h2>
                <?php if (!empty($following_array)) {
                    while ($user_tweet = $user_tweets->fetch(PDO::FETCH_ASSOC)) {
                        $get_tweet_owner = $db->prepare("SELECT * FROM users where user_id=:user_id");
                        $get_tweet_owner->execute(
                            array(
                                'user_id' => $user_tweet['user_id']
                            )
                        );
                        $tweet_owner = $get_tweet_owner->fetch(PDO::FETCH_ASSOC);

                        ?>


                        <div class="card">
                            <span>@
                                <?php echo $tweet_owner['user_name']; ?>
                            </span>

                            <br>

                            <p><b>Content</b>: <span>
                                    <?php echo $user_tweet['tweet_content']; ?>
                                </span></p>
                            <p><b>Created At: </b>: <span>
                                    <?php echo $user_tweet['tweet_created_at']; ?>
                                </span></p>
                            <br>
                        </div>
                    <?php }
                } ?>
            <?php } ?>

            <br>
            <br>
            <button><a href="profile.php" style="color:black;text-decoration:none">Profile</a></button>
            <form action="logout.php" method="post" style="padding-top:20px">

                <input type="submit" name="log_out" value="Logout">
            </form>
        </div>
    </div>
</body>

</html>