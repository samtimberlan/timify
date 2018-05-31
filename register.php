<?php
  include("includes/config.php");
  include("./includes/classes/Account.php");
  include("./includes/classes/Constants.php");
  $account = new Account($con);


  include("includes/handlers/register-handler.php");
  include("includes/handlers/login-handler.php");

  function getInputValue($name){
    if(isset($_POST[$name])){
      echo $_POST[$name];
    }
  }
  $pageTitle = "Account Page";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/register.css">
  <title>Welcome to Timify</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
  <?php
    if(isset($_POST['registerButton'])){
      echo '<script>
      $(document).ready(()=>{
      $("#loginForm").hide();
        $("#registerForm").show();
    });
      </script>';
    }
    else{
      echo '<script>
      $(document).ready(()=>{
      $("#loginForm").show();
        $("#registerForm").hide();
    });
      </script>';
    }
  ?>
  
  <script>
      $(document).ready(()=>{
      $("#loginForm").show();
        $("#registerForm").hide();
    });
      </script>

  <div class="background">
  <div class="loginContainer">
  <h1 style="text-align: center; font-size:3.5em;">Timify</h1>
  <div id="inputContainer">
    <form action="register.php" id="loginForm" method="POST">
      <h2>Login to your account</h2>
      <p>
      <?php
      echo $account-> getError(Constants::$loginFailed);
      ?>
      <label for="loginUsername">Username</label>
      <input type="text" id="loginUsername" name="loginUsername" placeholder="e.g Barney" value="<?php getInputValue('loginUsername') ?>" required>
      </p>
      <p>
      <label for="loginPassword">Password</label>
      <input type="password" id="loginPassword" name="loginPassword" placeholder="Password" required>
      </p>
      <button type="submit" name="loginButton">Login</button>
      <p class="has-account-text">
          <span id="hideLogin">Don't have an account? <b>Sign up here</b></span>
          <br>
          <span>Or</span>
          <br>
          <a href='index.php'>Login as guest</a>
      </p>
    </form>

    <form action="register.php" id="registerForm" method="POST">
      <h2>Create your free Timify account</h2>
      <p>
      <?php
      echo $account-> getError(Constants::$usernameChars);
      ?>
      <?php
      echo $account-> getError(Constants::$usernameTaken);
      ?>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Username" required value="<?php getInputValue('username')?>">
      </p>
      <p>
      <?php
      echo $account-> getError(Constants::$firstNamechars);
      ?>
      <label for="firstName">First name</label>
      <input type="text" id="firstName" name="firstName" placeholder="e.g Clark" required value="<?php getInputValue('firstName')?>">
      </p>
      <p>
      <?php
      echo $account-> getError(Constants::$lastNamechars);
      ?>
      <label for="lastName">Last name</label>
      <input type="text" id="lastName" name="lastName" placeholder="e.g Kent" required value="<?php getInputValue('lastName')?>">
      </p>
      <p>
      <?php
      echo $account-> getError(Constants::$invalidEmail);
      ?>

      <?php
      echo $account-> getError(Constants::$emailTaken);
      ?>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Email" required value="<?php getInputValue('email')?>">
      </p>
      <p>
      <?php
      echo $account-> getError(Constants::$passwordsDoNotMatch);
      ?>
      <?php
      echo $account-> getError(Constants::$passwordsNotAlphanumeric);
      ?>
      <?php
      echo $account-> getError(Constants::$passwordsChars);
      ?>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Password" required>
      </p>
      <p>
      <label for="confirmPassword">Confirm password</label>
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
      </p>
      <button type="submit" name="registerButton">Sign Up</button>
      <p class="has-account-text">
          <span id="hideRegister">Already have an account? <b>Log in here</b></span>
      </p>
    </form>
  </div>
  <div id="loginText">
    <h1>Music Is Life!</h1>
    <h2>That is why we provide songs for free</h2>
    <ul>
      <li>Stream Online</li>
      <li>Create your own playlists</li>
      <li>Follow your favorite artists</li>
    </ul>
  </div>
  </div>
  </div>

  <!-- <script src="assets/js/jquery 3.2.1.min.js"></script> -->
 
  <script src="assets/js/register.js"></script>
</body>
</html>