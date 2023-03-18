<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', 'toor404', 'login');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $fullname = mysqli_real_escape_string($db, $_POST['Full name']);
  $username = mysqli_real_escape_string($db, $_POST['Username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  $mobile = mysqli_real_escape_string($db, $_POST['Mobile no']);
  $email = mysqli_real_escape_string($db, $_POST['Email']);
  $gender = mysqli_real_escape_string($db, $_POST['Gender']);
  
  // $file = mysqli_real_escape_string($db, $_FILES['f1'])
  
  // code for image uploading
	// if($_FILES['f1']['name']){
	// 	move_uploaded_file($_FILES['f1']['tmp_name'], "uploads/".$_FILES['f1']['name']);
	// 	$img="uploads/".$_FILES['f1']['name'];
	// }

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($fullname)) { array_push($errors, "fullname is required"); }
  if (empty($username)) { array_push($errors, "username is required"); }
  if (empty($password)) { array_push($errors, "Password is required"); }
  if (empty($mobile)) { array_push($errors, "Mobile no. is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($gender)){ array_push($errors, "Gender is required");}
  
  
 
  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM students WHERE user='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO students (fullname, username, password, mobileno, email, gender) 
  			  VALUES('$fullname', '$username', '$password', '$mobile', '$email', '$gender')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location:');
  }
}

// ... 

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM students WHERE user='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location:');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>