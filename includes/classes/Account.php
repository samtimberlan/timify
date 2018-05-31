<?php
  class Account{

    private $errorArray;
    private $con;

    public function __construct($con){
      $this->con = $con;
      $this->errorArray = array();
    }

    public function login($un, $pw){
      $pw = md5($pw);
      $passwordQuery = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");
      if(mysqli_num_rows($passwordQuery) == 1){
        return true;
      }
      else {
        array_push($this->errorArray, Constants::$loginFailed);
        return false;
      }
    }

    public function register($un, $fn, $ln, $em, $pw, $pw2){
      //Validation
      $this-> validateUsername($un);
      $this-> validateFirstName($fn);
      $this-> validateLastName($ln);
      $this-> validateEmail($em);
      $this-> validatePasswords($pw, $pw2);

      if(empty($this-> errorArray)){
        //Insert into database
        return $this-> insertUserDetails($un, $fn, $ln, $em, $pw);
      }
      else{
        return false;
      }
    }

    public function getError($error){
      if(!in_array($error, $this-> errorArray)){
        $error = "";
      }
      else{
        return "<span class='errorMessage'>$error</span>";
      }
    }

    private function insertUserDetails($un, $fn, $ln, $em, $pw){
      $encryptedPw = md5($pw);
      $profilePic = "assets\images\profile-pic";
      $date = date("d-m-Y");

      $result = mysqli_query($this->con, "INSERT INTO users VALUES('', '$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");
      return $result;
    }

     //Validation functions
    private function validateUsername($un){
      if(strlen($un) > 50 || strlen($un) < 2){
        array_push($this-> errorArray, Constants::$usernameChars);
        return;
      }

      //check if username exists
      $checkUsernameQuery = mysqli_query($this->con,"SELECT username FROM users WHERE username = '$un'");
      if(mysqli_num_rows($checkUsernameQuery) != 0){
        array_push($this->errorArray, Constants::$usernameTaken);
      }
    }

    private function validateFirstName($fn){
      if(strlen($fn) > 50 || strlen($fn) < 2){
        array_push($this-> errorArray, Constants::$firstNamechars);
        return;
      }
    }
    private function validateLastName($ln){
      if(strlen($ln) > 50 || strlen($ln) < 2){
        array_push($this-> errorArray, Constants::$lastNamechars);
        return;
      }
    }

    private function validateEmail($em){
      //Check that email is in the correct format
      if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
        array_push($this-> errorArray, Constants::$invalidEmail);
      } 

      //Check that username hasn't already been used
      $checkEmailQuery = mysqli_query($this->con,"SELECT email FROM users WHERE email = '$em'");
      if(mysqli_num_rows($checkEmailQuery) != 0){
        array_push($this->errorArray, Constants::$emailTaken);
      }
    }

    private function validatePasswords($pw1, $pw2){
      //Check that passwords match
      if($pw1 != $pw2){
        array_push($this-> errorArray, Constants::$passwordsDoNotMatch);
        return;
      }

      //Filer non alphanumerics
      if(preg_match('/[^A-Za-z0-9]/', $pw1)){
        array_push($this-> errorArray, Constants::$passwordsNotAlphanumeric);
        return;
      }

      //Check password length
      if(strlen($pw1) < 6){
        array_push($this-> errorArray, Constants::$passwordsChars);
        return;
      }
    }
  }
?> 