var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var username;
var timer;

$(document).click(function(click) {
  var target = $(click.target);

  if (!target.hasClass("item") && !target.hasClass("options-button")) {
    hideOptionsMenu();
  }
});

$(document).on("change", "select.playlist", function() {
  var select = $(this);
  var playlistId = $(select).val();
  var songId = $(select)
    .prev(".songId")
    .val();

  $.post("includes/handlers/ajax/addToPlaylist.php", {
    playlistId: playlistId,
    songId: songId
  }).done(function() {
    hideOptionsMenu();
    select.val("");
  });
});

$(window).scroll(function() {
  hideOptionsMenu();
});

$(window).on("popstate", function() {
  var currentUrl = location.href;
  openPage(currentUrl);
});

function openPage(url) {
  //Reset the search timer when a user leaves the search page
  if (timer != null) {
    clearTimeout(timer);
  }

  //Append ? to querystring if missing
  if (url.indexOf("?") == -1) {
    url += "?";
  }
  var encodedUrl = encodeURI(url + "&username=" + username);
  $("#mainContent").load(encodedUrl);
  $("body").scrollTop(0);
  history.pushState(
    {
      state: "dummyState"
    },
    "info",
    url
  );
}

function updateEmail(emailClass) {
  var emailValue = $("." + emailClass).val();

  $.post("includes/handlers/ajax/updateEmail.php", {
    email: emailValue,
    username: username
  }).done(function(response) {
    $("." + emailClass)
      .nextAll(".message")
      .text(response);
  });
}

function updatePassword(
  oldPasswordClass,
  newPasswordClass1,
  newPasswordClass2
) {
  var oldPasswordValue = $("." + oldPasswordClass).val();
  var newPasswordValue1 = $("." + newPasswordClass1).val();
  var newPasswordValue2 = $("." + newPasswordClass2).val();

  $.post("includes/handlers/ajax/updatePassword.php", {
    oldPassword: oldPasswordValue,
    newPassword1: newPasswordValue1,
    newPassword2: newPasswordValue2,
    username: username
  }).done(function(response) {
    $("." + oldPasswordClass)
      .nextAll(".message")
      .text(response);
  });
}

function logout() {
  $.post("includes/handlers/ajax/logout.php", function() {
    location.assign("register.php");
  });
}

function removeFromPlaylist(hiddenSongId, playlistId) {
  //Geting the song Id from the "track-options" div in album.php
  var songId = $(hiddenSongId)
    .prevAll(".songId")
    .val();

  $.post("includes/handlers/ajax/removeFromPlaylist.php", {
    songId: songId,
    playlistId: playlistId
  }).done(openPage("playlist.php?id=" + playlistId));
}

function createPlaylist() {
  var popBox = prompt("Please enter the name of your playlist");

  //Create a new playlist in the database query using AJAX
  if (popBox != null) {
    $.post("includes/handlers/ajax/createPlaylist.php", {
      name: popBox,
      username: username
    })
      //Done is the preferred way of handling AJAX responses because of DEFERREDS
      .done(function(error) {
        if (error != "") {
          alert(error);
          return;
        }

        openPage("yourMusic.php");
      });
  }
}

function deletePlaylist(playlistId) {
  var prompt = confirm("Are you sure you want to delete this playlist?");

  if (prompt) {
    $.post("includes/handlers/ajax/deletePlaylist.php", {
      playlistId: playlistId
    })
      //Done is the preferred way of handling AJAX responses because of DEFERREDS
      .done(function(error) {
        // if (error != "") {
        //   console.log(error);
        //   return;
        // }
        openPage("yourMusic.php");
      });
  }
}

function hideOptionsMenu() {
  var menu = $(".options-menu");
  if (menu.css("display") != "none") {
    menu.css("display", "none");
  }
}

function showOptionsMenu(button) {
  //Geting the song Id from the "track-options" div in album.php
  var songId = $(button)
    .prevAll(".songId")
    .val();

  var menu = $(".options-menu");
  var menuWidth = menu.width();
  //Set the value(songId) of the hidden input field in the select element on album.php
  menu.find(".songId").val(songId);

  var scrollTop = $(window).scrollTop(); //Distance from top of window to top of document

  var elementOffset = $(button).offset().top; //Distance from top of decument

  var top = elementOffset - scrollTop;
  var left = $(button).position().left;

  menu.css({
    top: top + "px",
    left: left - menuWidth + "px",
    display: "inline"
  });
}

function formatTime(seconds) {
  var time = Math.round(seconds);
  var minutes = Math.floor(time / 60);
  var seconds = time - minutes * 60;

  //Add an extra zero
  var extraZero;
  if (seconds < 10) {
    extraZero = "0";
  } else {
    extraZero = "";
  }

  return minutes + ":" + extraZero + seconds;
}

function playFirstSong() {
  setTrack(tempPlaylist[0], tempPlaylist, true);
}

function updateTimeProgressBar(audio) {
  $(".progress-time.current").text(formatTime(audio.currentTime));
  $(".progress-time.remaining").text(
    formatTime(audio.duration - audio.currentTime)
  );

  var progress = audio.currentTime / audio.duration * 100;
  $(".playback-bar .progress").css("width", progress + "%");
}

function updateVolumeProgressBar(audio) {
  var volumePercentage = audio.volume * 100;
  $(".volume-bar .progress").css("width", volumePercentage + "%");
}

function Audio() {
  this.currentlyPlaying;
  this.audio = document.createElement("audio");

  this.audio.addEventListener("ended", function() {
    nextSong();
  });

  this.audio.addEventListener("canplay", function() {
    //Set track duration
    $(".progress-time.remaining").text(formatTime(this.duration));
  });

  this.audio.addEventListener("timeupdate", function() {
    if (this.duration) {
      updateTimeProgressBar(this);
    }
  });

  this.audio.addEventListener("volumechange", function() {
    updateVolumeProgressBar(this);
  });

  this.setTrackPath = function(track) {
    this.currentlyPlaying = track;
    this.audio.src = track.path;
  };

  this.play = function() {
    this.audio.play();
  };

  this.pause = function() {
    this.audio.pause();
  };

  this.setTime = function(seconds) {
    this.audio.currentTime = seconds;
  };
}
