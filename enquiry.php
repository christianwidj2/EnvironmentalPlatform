<?php
require_once("config.php");
$conn = connect();

$errors = [];
$data = [];

$name = trim($_POST["contact-name"]);
$email = trim($_POST["contact-email"]);
$phone = trim($_POST["contact-phone"]);
$msg = trim($_POST["message-enquiry"]);

if ((!empty($name)) && (!empty($email)) && (!empty($phone)) && (!empty($msg))){
	$conn = connect();
	// checks if connection is successful
	if ($conn) {
		$sql_table="enquiry";
		
		// set up the SQL command
		$query = $conn->prepare("INSERT INTO $sql_table (name, email, phone, message) VALUES (?, ?, ?, ?)");
		$query->bind_param("ssss", $name, $email, $phone, $msg); // bind the name, email, phone, message to the query
		
		if ($query->execute()) {
			$data["message"] = "Thank you for your message, $name! We'll get in touch with you soon.";
		} else {
			$errors["query"] = "Error: " . $conn->error;
		}
		$query->close();
	} else {
		$errors["db"] = "Sorry, there is a problem with the database connection. Please try again later!";
	}
	$conn->close();
}

if (!empty($errors)) {
	$data['success'] = false;
	$data['errors'] = $errors;
} else {
	$data['success'] = true;
}

echo json_encode($data);
exit; // terminate the script execution after echoing JSON data

?>