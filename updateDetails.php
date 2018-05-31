<?php
include('includes/includedFiles.php');
$pageTitle = "Update Details Page";
?>

<div class="user-details">
  <div class="container border-bottom">
    <h2>Email</h2>
    <input type="email" class='email' name="email" value="<?php echo $userObj->getEmail()?>" placeholder='Email'>
    <span class='message'></span>
    <button class="button" onclick='updateEmail("email")'>Save</button>
  </div>
  <div class="container">
    <h2>Password</h2>
    <input type="password" class='old-password' name="old-password"  placeholder='Current Password'>
    <input type="password" class='new-password1' name="new-password1"  placeholder='Current Password'>
    <input type="password" class='new-password2' name="new-password2"  placeholder='Confirm Password'>
    <span class='message'></span>
    <button class="button" onclick='updatePassword("old-password", "new-password1", "new-password2")'>Save</button>
  </div>
</div>