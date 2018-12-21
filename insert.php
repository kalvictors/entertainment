<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "dbitb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if(isset($_POST['btn-signup']))
{
 $firstname = ($_POST['firstname']);
 $lastname = ($_POST['lastname']);
 $nationality = ($_POST['nationality']);
 $phone = ($_POST['phone']);
 $email = ($_POST['email']);
 $password = ($_POST['password']);

$sql = "INSERT INTO users (firstname, lastname, nationality, phone, email, password)
VALUES ('$firstname','$lastname','$nationality','$phone','$email','$password')";

if ($conn->query($sql) === TRUE) 
{
    echo "New record created successfully";
} 
else 
{
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
}
?>