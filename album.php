<?php include("includes/includedFiles.php");

if(isset($_GET['id'])){
  $albumId = $_GET['id'];
}
else{
  header("Location: index.php");
}
$album = new Album($con, $albumId);
$artist = $album->getArtist();
$pageTitle = "Album Page";
?>

<div class="entity-info">
  <div class="left-section">
    <img src="<?php echo $album-> getArtworkPath();?>" alt="Album Cover">
  </div>
  <div class="right-section">
    <h2><?php echo $album -> getTitle(); ?></h2>
    <p>By <?php echo $artist->getName();?></p>
    <p><?php echo $album->getNumberOfSongs();?> Songs</p>
  </div>
</div>

<div class="track-list-container">
  <ul class="track-list">
    <?php 
      $songIdArray = $album->getSongIds();
      $count = 1;
      foreach ($songIdArray as $songId) {
        # code...
        $albumSong = new Song($con, $songId);
        $albumArtist = $albumSong->getArtist();
        echo "<li class='track-list-row' >
          <div class='track-count'>
            <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"".$albumSong->getID() . "\",tempPlaylist, true)'>
            <span class='track-number'>$count</span>
          </div>

          <div class='track-info'>
            <span class='track-name'>".$albumSong->getTitle()."</span>
            <span class='artist-name'>".$albumArtist->getName()."</span>
          </div>

          <div class='track-options'>
            <input type='hidden' class='songId' value='".$albumSong->getID(). "'>
            <img class='options-button' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
          </div>

          <div class='track-duration'>
            <span class='duration'>". $albumSong->getDuration() ."</span>
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
</nav>