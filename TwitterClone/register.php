<html><head>
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    

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
        <h1>Registration</h1>
        <?php
            if(isset($_GET['r'])&&($_GET['r']=='kayitlikullanici')){
                echo "<h4>Username is already taken! Please register with different username.</h4>";
            }
            
            ?>
        <form method="post" action="functions.php">
          <div class="form-group">
            <label for="reg-name">Name:</label>
            <input type="text" id="reg-name" name="name" required="">
          </div>
          <div class="form-group">
            <label for="reg-username">Username:</label>
            <input type="text" id="reg-username" name="username" required="">
          </div>
          <div class="form-group">
            <label for="reg-password">Password:</label>
            <input type="password" id="reg-password" name="password" required="">
          </div>
          <!-- you can add here more form-group like above -->
          <div class="form-group">
            <input type="submit" name="user_register"value="Register">
          </div>
        </form>
      </div>
    </div>
  

</body></html>