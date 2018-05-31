<?php
  include("includes/includedFiles.php");

  if(isset($_GET["term"])){
    $term = urldecode($_GET["term"]);
  }
  else{
    $term = "";
  }

  $pageTitle = "Search Page";
?>

<script>
  $(".search-input").focus();

$(function() {
	
	$(".search-input").keyup(function() {
		clearTimeout(timer);

		timer = setTimeout(function() {
			var val = $(".search-input").val();
			openPage("search.php?term=" + val);
		}, 1500);

	})


})
</script>

<div class="search-container">
  <h4>Search for an artist, album or song</h4>
  <input type="text" class="search-input" onfocus="this.value = this.value" value="<?php echo $term?>" placeholder="Start Typing..." >
</div>

<?php if($term == "") exit();?>

<!-- Artist Search Result -->
<!-- Track List Container -->
<div class="track-list-container border-bottom">
  <h2>Songs</h2>
  <ul class="track-list">
    <?php 

      $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10");
      if(mysqli_num_rows($songsQuery) == 0){
        echo "<span class='no-results'>No songs found matching ". $term . "</span>";
      }

      $songIdArray = array();
      $count = 1;
      while ($row = mysqli_fetch_array($songsQuery)) {
        # code...
        if($count > 15){
          break;
        }

        array_push($songIdArray, $row['id']);

        $albumSong = new Song($con,  $row['id']);
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


<!-- ARTIST CONTAINER -->
<div class="artist-container border-bottom">
  <h2>Artists</h2>

  <?php
    $artistQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

    if(mysqli_num_rows($artistQuery) == 0){
      echo "<span class='no-results'>No artists found matching ". $term . "</span>";
    }

    while($row = mysqli_fetch_array($artistQuery)){
      $artistFound = new Artist($con, $row['id']);

      echo "<div class='search-result-row'>
        <div class='artist-name'>
        <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $artistFound->getId() ."\")'>
					"
					. $artistFound->getName() .
					"
					</span>
        </div>
      </div>
      ";
    }
  ?>
</div>

<!-- Album Grid View  -->
<div class="grid-view-container">
  <h2>Albums</h2>
  <?php
    $albumQuery = mysqli_query($con, "SELECT * FROM albums where title LIKE '$term%' LIMIT 10");

    if(mysqli_num_rows($albumQuery) == 0){
      echo "<span class='no-results'>No albums found matching ". $term . "</span>";
    }

    while($row = mysqli_fetch_array($albumQuery)){

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