<?php
include("includes/includedFiles.php");
$pageTitle = "Profile Page";
?>

<div class="entity-info">
  <div class="center-section">
    <div class="user-info">
      <h1><?php echo $userObj->getFirstAndLastName(); ?></h1>
    </div>
  </div>
  <div class="button-items">
    <button class="button" onclick='openPage("updateDetails.php")'>
      User Details
    </button>
    <button class="button" onclick='logout()' >Logout</button>
  </div>
</div>