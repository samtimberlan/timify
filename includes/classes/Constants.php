<?php
  class Constants{
    //Register Error Messages

    public static $usernameChars = "Your username must be between 2 and 50 characters";

    public static $usernameTaken = "This username already exists";

    public static $firstNamechars = "Your first name must be between 2 and 50 characters";

    public static $lastNamechars = "Your last name must be between 2 and 50 characters";

    public static $invalidEmail = "Email is invalid";

    public static $emailTaken = "This email already exists";

    public static $passwordsDoNotMatch = "Your passwords don't match";

    public static $passwordsNotAlphanumeric = "Your password can only contain numbers and letters";

    public static $passwordsChars = "Your password must be greater than 5 characters";

    //Login Error Messages
    public static $loginFailed = "Your username or password was incorrect";
  }
?>