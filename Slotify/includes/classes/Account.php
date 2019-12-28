<?php
  class Account{

    private $con;
    private $errorArray;

    public function __construct($con){
      $this->con = $con;
      $this->errorArray = array();
    }

    public function login($us, $pw){
      $pw = md5($pw);
      $query = mysqli_query($this->con, "SELECT * FROM users WHERE username = '$us' AND password = '$pw'");
      if (mysqli_num_rows($query) == 1){
        return true;
      }
      else {
        array_push($this->errorArray, Constants::$loginFailed);
        return false;
      }
    }

    public function register($fn, $ln, $em, $us,$pw, $confpw){
      $this->validateFirstname($fn);
      $this->validateLastname($ln);
      $this->validateEmail($em);
      $this->validateUsername($us);
      $this->validatePasswords($pw, $confpw);

      if(empty($this->errorArray)){
        // insert into database
        return $this->insertUserDetails($us,$fn,$ln,$em,$pw);
      }
      else{
        return false;
      }
    }

    public function getError($error){
      if(!in_array($error, $this->errorArray)){
        $error = "";
      }
      return "<span class='errorMessage'>$error</span> ";
    }

    private function insertUserDetails($us,$fn,$ln,$em,$pw){
      $encryptedPW = md5($pw); //encrypts password
      $profilePic = "assets/images/profile-pics/head_emerald.png";
      $date = date("Y-m-d");

      $results = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$us', '$fn', '$ln', '$em', '$encryptedPW','$date', '$profilePic')");
      return $results;
    }

    private function validateFirstname($fn){
      if (strlen($fn) > 25 || strlen($fn) < 2){
        array_push($this->errorArray, Constants::$firstNameLength);
        return;
      }
    }

    private function validateLastname($ln){
      if (strlen($ln) > 25 || strlen($ln) < 2){
        array_push($this->errorArray, Constants::$lastNameLength);
        return;
      }
    }

    private function validateEmail($em){
      if (!filter_var($em, FILTER_VALIDATE_EMAIL)){
        array_push($this->errorArray, Constants::$emailInvalid);
        return;
      }
      $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email = '$em'");
      if (mysqli_num_rows($checkEmailQuery) != 0){
        array_push($this->errorArray, Constants::$emailTaken);
        return;
      }
    }

    private function validateUsername($us){
      if (strlen($us) > 25 || strlen($us) < 5){
        array_push($this->errorArray, Constants::$usernameLength);
        return;
      }
      $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username = '$us'");
      if (mysqli_num_rows($checkUsernameQuery) != 0){
        array_push($this->errorArray, Constants::$usernameTaken);
        return;
      }
    }

    private function validatePasswords($pw, $confpw){
      if($pw != $confpw){
        array_push($this->errorArray, Constants::$passwordsDoNotMatch);
        return;
      }
      if(preg_match('/[^A-Za-z0-9]/', $pw)){
        array_push($this->errorArray, Constants::$passwordsAlphanumeric);
        return;
      }
      if (strlen($pw) > 30 || strlen($pw) < 5){
        array_push($this->errorArray, Constants::$passwordsLength);
        return;
      }
    }
  }
?>
