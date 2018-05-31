<?php

  include("../../config.php");
 
  if(isset($_POST['playlistId'])  && isset($_POST['songId']) ){
    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];
 
    $orderIdQuery = mysqli_query($con, "SELECT MAX( playlistOrder ) + 1 as playlistOrder FROM playlistSongs WHERE playlistId='$playlistId' ");
    $row = mysqli_fetch_array($orderIdQuery);
    $orderId = $row['playlistOrder'];
    echo "<script>console.log($orderId)<script>";

    //Check if song exists in playlist
    $songQuery = mysqli_query($con, "SELECT * FROM playlistSongs WHERE songId='$songId' AND playlistId = '$playlistId' ");
 
    if(mysqli_num_rows($songQuery) == 0){
      $playlistInsertQuery = mysqli_query($con, "INSERT INTO playlistSongs VALUES('', '$songId', '$playlistId', '$orderId'  )  ");
    }
   }
  else{
    echo "Playlist ID or Song ID was not passed into addToPlaylist.php function";
  }
 
 
?>