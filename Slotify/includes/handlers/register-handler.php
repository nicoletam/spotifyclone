<?php

function sanitizeFormUsername($inputText){
  $inputText = strip_tags($inputText);
  $inputText = str_replace(" ", "", $inputText);
  return $inputText;
}

function sanitizeFormPassword($inputText){
  $inputText = strip_tags($inputText);
  return $inputText;
}

function sanitizeFormString($inputText){
  $inputText = strip_tags($inputText);
  $inputText = str_replace(" ", "", $inputText);
  $inputText = ucfirst(strtolower($inputText));
  return $inputText;
}

if(isset($_POST['registerButton'])){
  //presses register button
  $firstName = sanitizeFormString($_POST['firstName']);
  $lastName = sanitizeFormString($_POST['lastName']);
  $email = sanitizeFormString($_POST['email']);
  $username = sanitizeFormUsername($_POST['username']);
  $password = sanitizeFormPassword($_POST['password']);
  $confirmPassword = sanitizeFormPassword($_POST['confirmPassword']);

  $wasSuccessful = $account->register($firstName, $lastName, $email, $username, $password, $confirmPassword);

  if ($wasSuccessful){
    $_SESSION['userLoggedIn'] = $username;
    header("Location: index.php");
  }
}

?>
