<?php
include("includes/includedFiles.php");
$pageTitle = "Home";
?>
<h1 class="page-heading-big">You Might Also Like</h1>
<div class="grid-view-container">
  <?php
    $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");

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