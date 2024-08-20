<html>

<head>
    <title>Profile</title>

    <?php


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


    ?>
</head>

<body>
    <div class="container">
        <div class="card">

            <h1>Welcome,
                <?php echo $user['user_name']; ?>
            </h1>
            <?php
            if(isset($_GET['r'])&&($_GET['r']=='basarili')){
                echo "<h4>Tweet successfully posted!</h4>";
            }elseif(isset($_GET['r'])&&($_GET['r']=='hata')){
                echo "<h4>Something went wrong while posting your tweet!</h4>";

            }
            
            ?>
            <p><b>Username:</b> <span>
                    <?php echo $user['user_username']; ?>
                </span> </p>


            <p><b>Tweets:</b> <span>
                    <?php
                    $user_tweets = $db->prepare("SELECT * FROM tweets where user_id=:user_id");
                    $user_tweets->execute(
                        array(
                            'user_id' => $user['user_id']
                        )
                    );
                    $tweet_count = $user_tweets->rowCount();
                    echo $tweet_count;

                    ?>
                </span> </p>

            <p><b>Followers:</b> <span>
                    <?php
                    $user_followers = $db->prepare("SELECT * FROM follows where following_user_id=:user_id");
                    $user_followers->execute(
                        array(
                            'user_id' => $user['user_id']
                        )
                    );
                    $follower_count = $user_followers->rowCount();
                    echo $follower_count;

                    ?>
                </span> </p>
            <p><b>Following:</b> <span>
                    <?php
                    $user_following = $db->prepare("SELECT * FROM follows where user_id=:user_id");
                    $user_following->execute(
                        array(
                            'user_id' => $user['user_id']
                        )
                    );
                    $following_count = $user_following->rowCount();
                    echo $following_count;

                    ?>
                </span> </p>
            <p><b>Creation Date:</b> <span>
                    <?php
                    echo $user['user_register_date'];
                    ?>
                </span> </p>
            <form action="./functions.php" method="POST">

                <textarea name="tweet_content" id="" cols="30" rows="3" placeholder="Write a tweet"></textarea>
                <input type="submit" value="Tweet" name="tweet_post">


            </form>

            <h2>Your Tweets:</h2>
            <?php

            $user_tweets = $db->prepare('SELECT * FROM tweets where user_id=:user_id order by tweet_created_at desc');
            $user_tweets->execute(
                array(
                    'user_id' => $user['user_id']
                )
            );

            while ($user_tweet = $user_tweets->fetch(PDO::FETCH_ASSOC)) {?>
                <div class="card">
                    <p><b>Content</b>: <span>
                            <?php echo $user_tweet['tweet_content']; ?>
                        </span></p>
                    <p><b>Created At: </b>: <span>
                            <?php echo $user_tweet['tweet_created_at']; ?>
                        </span></p>
                    <br>
                </div>
            <?php } ?>
            <button><a href="./homepage.php" style="color:black;text-decoration:none">Homepage</a></button>
            <form action="logout.php" method="post" style="padding-top:20px">

                <input type="submit" name="log_out" value="Logout">
            </form>
        </div>


    </div>


</body>

</html>