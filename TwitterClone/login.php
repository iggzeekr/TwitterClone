<html>

<head>
    <title>Login</title>
    <link rel="stylesheet">

    <?php
    
    
    ob_start();
    session_start();
    if (isset($_SESSION['user'])) {

        header("location:./homepage.php");
    }

    ?>
</head>

<body>
    <div class="container">
        <div class="card">
          
            <h1>Login</h1>
            <?php
            if(isset($_GET['r'])&&($_GET['r']=='kayitbasarili')){
                echo "<h4>Successfully registered, please log in.</h4>";
            }elseif(isset($_GET['r'])&&($_GET['r']=='basarisiz')){
                echo "<h4>Username or password is wrong!</h4>";

            }elseif(isset($_GET['r'])&&($_GET['r']=='girisyap')){
                echo "<h4>Please login first!</h4>";

            }
            
            ?>
            <form method="post" action="functions.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required="">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required="">
                </div>
                <div class="form-group">
                    <input type="submit" name="user_login" value="Login">
                </div>
            </form>
        </div>

    </div>


</body>

</html>