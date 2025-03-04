<?php
// Establish connection to database
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'environment'); // your database name
function connect(){
	static $conn;
	if ($conn === NULL){
		$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if(mysqli_connect_errno()){
			echo "Failed to connect to MySQL: ", mysqli_connect_error();
		}
	}
	return $conn;
}
?>