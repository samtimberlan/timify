<?php include("includes/includedFiles.php");

if(isset($_GET['id'])){
  $playlistId = $_GET['id'];
}
else{
  header("Location: index.php");
}
$playlist = new Playlist($con, $playlistId);
$owner = $playlist->getOwner();
$pageTitle = "Playlist Page";
?>

<div class="entity-info">
  <div class="left-section">
    <div class='playlist-image'>
    <img src="assets/images/icons/playlist.png" alt="Playlist Cover">
    </div>
  </div>
  <div class="right-section">
    <h2><?php echo $playlist -> getName(); ?></h2>
    <p>By <?php echo $playlist->getOwner();?></p>
    <p><?php echo $playlist->getNumberOfSongs();?> Songs</p>
    <button class="button" onclick="deletePlaylist(<?php echo $playlistId; ?>)">Delete Playlist</button>
  </div>
</div>

<div class="track-list-container">
  <ul class="track-list">
    <?php 
      $songIdArray = $playlist->getSongIds();
      $count = 1;
      foreach ($songIdArray as $songId) {
        # code...
        $playlistSong = new Song($con, $songId);
        $songArtist = $playlistSong->getArtist();
        echo "<li class='track-list-row' >
          <div class='track-count'>
            <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"".$playlistSong->getID() . "\",tempPlaylist, true)'>
            <span class='track-number'>$count</span>
          </div>

          <div class='track-info'>
            <span class='track-name'>".$playlistSong->getTitle()."</span>
            <span class='artist-name'>".$songArtist->getName()."</span>
          </div>

          <div class='track-options'>
            <input type='hidden' class='songId' value='".$playlistSong->getID(). "'>
            <img class='options-button' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
          </div>

          <div class='track-duration'>
            <span class='duration'>". $playlistSong->getDuration() ."</span>
          </div>
        </li>";

        $count++;
      }
    ?>

    <script>
      var tempSongIDs = '<?php echo json_encode($songIdArray); ?>'
      tempPlaylist = JSON.parse(tempSongIDs);
    </script>
  </ul>
</div>


<nav class='options-menu'>
  <input type="hidden" class ='songId'>
  <?php echo Playlist::getPlaylistsDropdown($con, $userObj->getUserName());?>

  <div class='item' onclick='removeFromPlaylist(this, <?php echo $playlistId; ?>)'>Remove from playlist</div>
</nav>