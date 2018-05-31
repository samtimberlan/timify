<?php
include("includes/includedFiles.php");
$pageTitle = "Your Music Page";

?>

<div class="playlist-container">
  <div class="grid-view-container">
    <h2>Playlists</h2>
    <div class="button-items">
      <button class="button" onclick="createPlaylist()">New Playlist</button>
    </div>
  </div>
</div>


<!-- // Displaying the playlist Albums -->

  <?php
			$username = $userObj->getUsername();

			$playlistsQuery = mysqli_query($con, "SELECT * FROM playlists WHERE owner='$username'");

			if(mysqli_num_rows($playlistsQuery) == 0) {
				echo "<span class='no-results'>You don't have any playlists yet.</span>";
			}

			while($row = mysqli_fetch_array($playlistsQuery)) {

				$playlist = new Playlist($con, $row);

				echo "<div class='grid-view-item' role='link' tabindex='0' onclick='openPage(\"playlist.php?id=" .$playlist->getId() . "\")'>      
        <div class='playlist-image'>
          <img src='assets/images/icons/playlist.png'>
        </div>
  
        <div class='grid-view-info'>"
              .  $playlist->getName() .
              "</div>
              </div>";



			}
		?>