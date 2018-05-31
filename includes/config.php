<?php
  ob_start();
  session_start();
  
  $timezone = date_default_timezone_set("Africa/Lagos");
  $con = mysqli_connect("localhost", "samtimberlan", "anijun3em", "timify");

  if(mysqli_connect_errno()){
    echo "Failed to connect to:" . mysqli_connect_errno();
  }
?>