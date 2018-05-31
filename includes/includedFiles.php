<?php 
//Detect if a file was loaded from AJAX
//using HTTP_X_REQUESTED_WITH.
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
  include("includes/config.php");
  include("includes/classes/User.php");
  include("includes/classes/Artist.php");
  include("includes/classes/Album.php");
  include("includes/classes/Song.php");
  include("includes/classes/Playlist.php");

  //Get Username PHP variable
  if(isset($_GET['username'])){
    $userObj = new User($con, $_GET['username']);
  }else{
    echo "Username variable was not passed to page. Check the openPage JS function";
    exit();
  }

}else{
  //Include Header and footer If the page   wasn't loaded from
  //AJAX, then...
  $pageTitle = "Home page";
  include("includes/header.php");
  include("includes/footer.php");
  //... use AJAX to load the main content
  $url = $_SERVER['REQUEST_URI'];
  echo "<script>openPage('$url')</script>";
  //Prevent from reloading the rest of the page again
  exit();
}
?>