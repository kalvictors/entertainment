<?php
session_start();

// initializing variables
$firstname = "";
$lastname = "";
$nationality = "";
$phone = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'zalpoint_root', '', 'zalpoint_dbitb');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
} 

// REGISTER USER
if (isset($_POST['btn-signup'])) {
  // receive all input values from the form
  $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
  $lastname = mysqli_real_escape_string($db, $_POST['lastname']);
  $nationality = mysqli_real_escape_string($db, $_POST['nationality']);
  $phone = mysqli_real_escape_string($db, $_POST['phone']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($firstname)) { array_push($errors, "name required"); }
  if (empty($lastname)) { array_push($errors, "name required"); }
  if (empty($nationality)) { array_push($errors, "nationality required"); }
  if (empty($phone)) { array_push($errors, "phone required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE firstname='$firstname' AND lastname=$lastname OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['firstname'] === $firstname , ['lastname'] === $lastname) {
      array_push($errors, "name already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (firstname, lastname, nationality, phone, email, password) 
  			  VALUES ('$firstname','$lastname','$nationality','$phone','$email','$password')";
  	mysqli_query($db, $query);
  	$_SESSION['firstname'] = $firstname;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: userp.html');
  }
}

// ...

// ... 

// LOGIN USER
if (isset($_POST['login_user'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($email)) {
  	array_push($errors, "email required");
  }
  if (empty($password)) {
  	array_push($errors, "Password required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['firstname'] = $firstname;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: userp.html');
  	}else {
  		array_push($errors, "Wrong email/password combination");
  	}
  }
}

?>