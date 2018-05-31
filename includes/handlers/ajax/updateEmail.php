<?php
  include("../../config.php");

  if(!isset($_POST['username'])){
    echo "ERROR: Could not set username";
    exit();
  }

  if(isset($_POST['email']) && $_POST['email'] != ""){
    $username = $_POST['username'];
    $email = strtolower($_POST['email']);

    //Checking to see if email is valid
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      echo "Email is invalid";
      exit();
    }

    //Check to see that the user is not a guest.
    $query = mysqli_query($con, "SELECT username FROM users WHERE username='guest' ");
    $row = mysqli_fetch_array($query);
    if($row['username'] == "guest"){
      echo 'Please create an account <a href=\'register.php\'>here</a>.';
      exit();
    }
    //Check if the email has been used by another user
    $emailCheck = mysqli_query($con, "SELECT email FROM users WHERE email = '$email' AND username != '$username' ");
    if(mysqli_num_rows($emailCheck) > 0){
      echo "Email is already in use";
      exit();
    }

    $updateQuery = mysqli_query($con, "UPDATE users SET email = '$email' WHERE username = '$username' ");
    echo "Your email has been changed successfully";

  }else{
    echo "Please provide an email";
    exit();
  }
?>