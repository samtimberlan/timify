<?php 
  include("includes/includedFiles.php");

  //Check if id has been set from he URL using GET
  if(isset($_GET['id'])){
    $artistId = $_GET['id'];
  }
  else{
    header("Location: index.php");
  }

  $artist = new Artist($con, $artistId);
  $pageTitle = "Artist Page";
?>

<div class="entity-info border-bottom">
  <div class="center-section">
    <div class="artist-info">
      <h1 class="artist-name"><?php echo $artist->getName(); ?></h1>
      <div class="header-buttons">
        <button class="button" onclick="playFirstSong()">Play</button>
      </div>
    </div>
  </div>
</div>

<!-- Track List Container -->
<div class="track-list-container border-bottom">
  <h2>Songs</h2>
  <ul class="track-list">
    <?php 
      $songIdArray = $artist->getSongIds();
      $count = 1;
      foreach ($songIdArray as $songId) {
        # code...
        if($count > 5){
          break;
        }
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

<!-- Album Grid View  -->
<div class="grid-view-container">
  <h2>Albums</h2>
  <?php
    $albumQuery = mysqli_query($con, "SELECT * FROM albums where artist = '$artistId'");

    while($row = mysqli_fetch_array($albumQuery)){
      //echo $row['title'] . "<br/>";

      ECHO "<div class='grid-view-item'>      
      <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] ."\")'href='album.php?id=" . $row['id'] ."'>
          <img src='" . $row['artworkPath'] . "' alt='Album Cover'/>
          <div class='grid-view-info'>"
            . $row['title'] .
          "</div>
        </span>
      </div>";
    }
  ?>
</div>

<nav class='options-menu'>
  <input type="hidden" class ='songId'>
  <?php echo Playlist::getPlaylistsDropdown($con, $userObj->getUserName());?>
</nav>