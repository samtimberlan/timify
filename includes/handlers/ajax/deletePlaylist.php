<?php
 include("../../config.php");

 if(isset($_POST['playlistId'])){
   $playlistId = $_POST['playlistId'];

   $playlistQuery = mysqli_query($con, "DELETE FROM playlists WHERE id='$playlistId' ");

   $playlistSongsQuery = mysqli_query($con, "DELETE FROM playlistSongs WHERE PlaylistId='$playlistId' ");
  }
 else{
   echo "Playlist ID was not passed into deletePlaylist JS function";
 }
?>

