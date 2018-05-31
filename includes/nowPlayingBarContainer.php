<?php
  $songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY rand() LIMIT 10");

  $resultArray = array();
  while ($row  = mysqli_fetch_array($songQuery) ) {
    # code...
    array_push($resultArray, $row['id']);
    $jsonArray = json_encode( $resultArray );
  }
?>

<script>
  $(document).ready(function(){
    $(".control-button.play").show();
    $(".control-button.pause").hide();
    currentPlaylist = <?php echo $jsonArray; ?>;
    audioElement = new Audio();
    setTrack(currentPlaylist[0], currentPlaylist, false);
    updateVolumeProgressBar(audioElement.audio);

    $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e){
      e.preventDefault();
    });

    $(".playback-bar .progress-bar").mousedown(function (){
      mouseDown = true;
    });

    $(".playback-bar .progress-bar").mousemove(function (e){
      if(mouseDown){
        //Set time of song, depending on position of mouse
        timeFromOffset(e, this);
      }
    });

    $(".playback-bar .progress-bar").mouseup(function (e){
      timeFromOffset(e, this);
    });

    $(".volume-bar .progress-bar").mousedown(function (){
      mouseDown = true;
    });

    $(".volume-bar .progress-bar").mousemove(function (e){
      if(mouseDown){
        //Set volume of song, depending on position of mouse
        var percentage = e.offsetX / $(this).width();
        if(percentage >= 0 && percentage <=1){
        audioElement.audio.volume = percentage;
        }
      }
    });

    $(".volume-bar .progress-bar").mouseup(function (e){
      //Set volume of song, depending on position of mouse
      var percentage = e.offsetX / $(this).width();
        if(percentage >= 0 && percentage <=1){
        audioElement.audio.volume = percentage;
        }
    });

    $(document).mouseup(function(){
      mouseDown = false;
    });
  });

  function timeFromOffset(mouse, progressBar) { 
    var percentage = mouse.offsetX / $(progressBar).width() * 100;
    var seconds = audioElement.audio.duration * (percentage /100);
    audioElement.setTime(seconds);
   }

  function nextSong () { 

    if(repeat){
      audioElement.setTime(0);
      playSong();
      return;
    }
    if(currentIndex == currentPlaylist.length - 1){
      currentIndex = 0;
    }else{
      currentIndex ++; 
    }
    var trackToPlay = currentPlaylist[currentIndex];
    setTrack(trackToPlay, currentPlaylist, true);
   }

   function prevSong(){
     if(audioElement.audio.currentTime >= 10 || currentIndex == 0){ 
      audioElement.setTime(0);
     }
     else{
       currentIndex--;
       setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
     }
   }

   function setRepeat(){
     repeat = !repeat;
     //Toggle image
     var imageName = repeat ? "repeat-active.png" : "repeat.png";
     $(".control-button.repeat img").attr("src", "assets/images/icons/" + imageName);
   }

   function setMute(){
     audioElement.audio.muted = !audioElement.audio.muted;
     var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
     $(".control-button.volume img").attr("src", "assets/images/icons/" + imageName);
   }

   function setShuffle(){
     shuffle = !shuffle;
     var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
     $(".control-button.shuffle img").attr("src", "assets/images/icons/" + imageName);


     if(shuffle){
       //Randomize playlist
       shuffleArray(shuffleArray);
     }
     else{
       //Shuffle deactivated. Use regular playlist

     }
   }

   function shuffleArray(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
    return a;
}

  function setTrack(trackID, newPlaylist, play){

    if(newPlaylist != currentPlaylist){
      currentPlaylist = newPlaylist;
      shufflePlaylist = currentPlaylist.slice();
      shuffleArray(shufflePlaylist);
    }

    //Set curent Index of track in playlist
    currentIndex = newPlaylist.indexOf(trackID);
    pauseSong();

    // Use AJAX to get song playlist
    $.post("includes/handlers/ajax/getSongJson.php", {songID: trackID}, function(data){
      var track = JSON.parse(data);

      //Set the Song Title
      $("#nowPlayingBar .track-name").text(track.title);

      //Set the track title using AJAX
      $.post("includes/handlers/ajax/getArtistJson.php", {artistID: track.artist}, function(data){
        var artist = JSON.parse(data);

        $("#nowPlayingBar .artist-name").text(artist.name);
        $(".track-info .artist-name").attr("onclick", "openPage('artist.php?id=" + artist.id + "')");
      }); 
      
      //Set the track album art
      $.post("includes/handlers/ajax/getAlbumJson.php", {albumID: track.album}, function(data){
        var album = JSON.parse(data);

        $("#nowPlayingBar .album-link img").attr("src", album.artworkPath);
        
        $("#nowPlayingBar .album-link img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
        $("#nowPlayingBar .track-name").attr("onclick", "openPage('album.php?id=" + album.id + "')");
      }); 

      audioElement.setTrackPath(track);
      if(play){
      $(".control-button.play").hide();
      $(".control-button.pause").show();
      audioElement.play();
    }
    });

    if(play){
     playSong();
    }
  }

  function playSong(){
    //Increment song count in the DB using AJAX
    if(audioElement.audio.currentTime == 0){
      $.post("includes/handlers/ajax/updatePlays.php", {songID: audioElement.currentlyPlaying.id});
    }

    $(".control-button.play").hide();
    $(".control-button.pause").show();
    audioElement.play();
  }

  function pauseSong(){
    $(".control-button.play").show();
    $(".control-button.pause").hide();
    audioElement.pause();
  }
</script>

<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">
      <div id="nowPlayingLeft">
        <div class="content">
          <span class="album-link">
            <img src="" alt="Album Art" class="album-art" role="link" tabindex="0">
          </span>
          <div class="track-info">
            <span class="track-name" role="link" tabindex="0">
              
            </span>
            <span class="artist-name" role="link" tabindex="0">
              
            </span>
          </div>
        </div>
      </div>
      <div id="nowPlayingCenter">
      <div class="content player-controls">
          <div class="buttons">
            <button class="control-button shuffle" title="Shuffle button" onclick="setShuffle()">
              <img src="assets/images/icons/shuffle.png" alt="Shuffle">
            </button>
            <button class="control-button previous" title="Previous button" onclick="prevSong()">
              <img src="assets/images/icons/previous.png" alt="Previous">
            </button>
            <button class="control-button play" title="Play button" onclick="playSong()">
              <img src="assets/images/icons/play.png" alt="Play">
            </button>
            <button class="control-button pause" title="Pause button" onclick="pauseSong()">
              <img src="assets/images/icons/pause.png" alt="Pause">
            </button>
            <button class="control-button next" title="Next button" onclick="nextSong()">
              <img src="assets/images/icons/next.png" alt="Next">
            </button>
            <button class="control-button repeat" title="Repeat button" onclick="setRepeat()">
              <img src="assets/images/icons/repeat.png" alt="Repeat">
            </button>
          </div>
          <div class="playback-bar">
            <span class="progress-time current">0.00</span>

            <div class="progress-bar">
              <div class="progress-bar-bg">
                <div class="progress"></div>
              </div>
            </div>

            <span class="progress-time remaining">0.00</span>
          </div>
      </div>
      </div>
      <div id="nowPlayingRight">
      <div class="volume-bar">
        <button class="control-button volume" title="Volume" onclick="setMute()">
          <img src="assets/images/icons/volume.png" alt="Volume">
        </button>
        <div class="progress-bar">
              <div class="progress-bar-bg">
                <div class="progress"></div>
              </div>
            </div>
      </div> 
      </div>
    </div>
  </div>



  