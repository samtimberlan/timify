<?php
class User {
  private $con;
  private $username;
  public $guest;

  public function __construct($con, $username){
    $this->con = $con;
    $this->username = $username;

    //Check for Guest User
    $query = mysqli_query($this->con, "SELECT username FROM users WHERE username='$this->username' ");
    $row = mysqli_fetch_array($query);

    //Create Guest flag
    if($row['username'] == "Guest"){
      return $this->guest = true;
    }else{
      return $this->guest = false;
    }

  }

  public function getUserName(){
    if($this->guest){
      return 'Guest';
    }else{
      return $this->username;
    }
  }

  public function getFirstAndLastName(){
    if($this->guest){
      return 'Guest Account';
    }else{
      $query = mysqli_query($this->con, "SELECT concat(firstName, ' ', lastName) as fullName FROM users WHERE username='$this->username' ");

      $row = mysqli_fetch_array($query);
      return $row['fullName'];
    }
  }

  public function getEmail(){
    $query = mysqli_query($this->con, "SELECT email FROM users WHERE username='$this->username' ");

    $row = mysqli_fetch_array($query);
    return $row['email'];
  }
}

?>