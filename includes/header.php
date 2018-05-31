<?php
  include("includes/config.php");
  include("includes/classes/User.php");
  include("includes/classes/Artist.php");
  include("includes/classes/Album.php");
  include("includes/classes/Song.php");
  include("includes/classes/Playlist.php");

  if(isset($_SESSION['userLoggedIn'])){
    $userObj = new User($con, $_SESSION['userLoggedIn']); 
    $username = $userObj->getUserName();
    echo "<script>username = '$username';</script>";
  }
  else {
    $userObj = new User($con, "Guest");
    $username = $userObj->getUsername();
    echo "<script>username = '$username';</script>";
    //header("Location: register.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="assets/js/jquery 3.2.1.min.js"></script>
  <script src="assets/js/script.js"></script>
  <?php echo "<title>" . $pageTitle . "</title>"; ?>
</head>
<body>
  <div id="mainContainer">
    <div id="topContainer">
      <?php include('includes/navBarContainer.php');?>

      <div id="mainViewContainer">
        <div id="mainContent">