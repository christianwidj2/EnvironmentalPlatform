<?php
	session_start(); // starting a session
	require_once('config.php'); // initialize database configuration
	
	// LOGIN 
	if (isset($_POST["login"])){
		if (isset($_POST["username"]) && isset($_POST["password"])){
			$username = trim($_POST["username"]);
			$password = trim($_POST["password"]);
			if ((!empty($username)) && (!empty($password))){
				$conn = connect();
				// checks if connection is successful
				if ($conn) {
					// upon successful connection
					if(preg_match('/^[a-zA-Z0-9_.]+$/', $username)) // check if the username is using allowed characters
					{
						$sql_table="user";
					
						// set up the SQL command
						$query = $conn->prepare("SELECT username, password FROM $sql_table WHERE username = ?");
						$query->bind_param("s", $username); // bind the username to the query
						$query->execute();
						$result = $query->get_result();
						if($result && $result->num_rows > 0) {
							$user = $result->fetch_assoc();
							$hashed_password = md5($password);
							// check the password
							if ($hashed_password === $user["password"]) {
								$_SESSION["username"] = $user["username"];
								if(isset($_POST["remember"])){
									if(!isset($_COOKIE["username"])){
										setcookie ("username",$user["username"], [
											'expires' => time() + (60 * 60), // 1 hour
											'path' => '/',
											'secure' => true, // ensure the cookie is sent over HTTPS
											'httponly' => true, // make the cookie inaccessible to JavaScript
										]);
										setcookie ("pass",$password,[
											'expires' => time() + (60 * 60), // 1 hour
											'path' => '/',
											'secure' => true, // ensure the cookie is sent over HTTPS
											'httponly' => true, // make the cookie inaccessible to JavaScript
										]);
									}
								}
							}
							// frees up the memory, after using the query
							$query->close();
							// close the database connection
							$conn->close();
							header("Location: index.php");
						} // if successful query operation
						else {
							echo "<script>alert('Invalid login!');</script>";
						}
					}
				} // if successful database connection
				else {
					echo "<script>alert('Sorry, there is a problem with the database connection. Please try again later!');</script>";
				}
			} // if null string
		} // if data posted
	}
	
	// REGISTER
	if (isset($_POST["username-reg"]) && isset($_POST["email-reg"])){
		$usernameReg = trim($_POST["username-reg"]);
		$emailReg = trim($_POST["email-reg"]);
		$passReg = trim($_POST["pass-reg"]);
		$conn = connect();
		if($conn){
			$sql_table="user";
			
			// username check
			// query to check if the username exists
			$query = $conn->prepare("SELECT username FROM $sql_table WHERE username = ?");
			$query->bind_param("s", $usernameReg);
			$query->execute();
			$result = $query->get_result();
			
			if($result && $result->num_rows > 0) {
				echo "<script> var errorMessage = document.getElementById('username-error-message');
					errorMessage.style.display = 'block'; </script>";
			} else {
				echo "<script> var errorMessage = document.getElementById('username-error-message');
					errorMessage.style.display = 'none'; </script>";
					
				// email check
				// query to check if the email exists
				$query = $conn->prepare("SELECT email FROM $sql_table WHERE email = ?");
				$query->bind_param("s", $emailReg); // bind the email to the query
				$query->execute();
				$result = $query->get_result();
				
				if($result && $result->num_rows > 0) {
					echo "<script> var errorMessage = document.getElementById('email-error-message');
						errorMessage.style.display = 'block'; </script>";
				} else {
					echo "<script> var errorMessage = document.getElementById('email-error-message');
						errorMessage.style.display = 'none'; </script>";
					$passReg = md5($passReg);
					// Prepare the SQL statement to insert the new user
					$query = $conn->prepare("INSERT INTO $sql_table (username, email, password) VALUES (?, ?, ?)");
					$query->bind_param("sss", $usernameReg, $emailReg, $passReg); // bind the username, email, and pwd to the query
                
					if ($query->execute()) {
						echo "<script>alert('Registration successful.');</script>";
					} else {
						echo "<script>alert('Error: ".$query->error."');</script>";
					}
					
				}
			}
			$query->close();
		} else {
			echo "<script>alert('Sorry, there is a problem with the database connection. Please try again later!');</script>";
		}
		$conn->close();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript">
        $(window).on('scroll', function(){
            if ($(window).scrollTop()){
                $('nav').addClass('black');
            }
            else
            {
                $('nav').removeClass('black')
            }
        })
    </script>
</head>

<body>
    <?php
	include("header.php");
	?>
	
    <section class="sec1e"></section>
    
    <section class="content1e">
        <h1>Sign in or Register here</h1>
        <div class="border"></div>
    <div class="container">
    <div class="form-box">
        <div class="button-box">
            <div id="btn"></div>
            <button type="button" class="toggle-btn" onclick="login()">Login</button>
            <button type="button" class="toggle-btn" onclick="register()">Register</button>
        </div>
    <form method="post" id="login" class="input-group" action="login.php">
        <input type="username" class="input-field" placeholder="Username" name="username" id="username" value="<?php if(isset($_COOKIE["username"])) { echo $_COOKIE["username"]; } ?>" required>
        <input type="password" class="input-field" placeholder="Password" name="password" id="password" value="<?php if(isset($_COOKIE["pass"])) { echo $_COOKIE["pass"]; } ?>" required>
        <input type="checkbox" class="check-box" name="remember" id="remember" value="<?php if(isset($_COOKIE["username"])) { ?>" checked "<?php } ?>"><span>Remember Password</span>
        <button type="submit" class="submit-btn" name="login">Login</button>
    </form>
    <form method="post" id="register" class="input-group" action="login.php">
        <input type="username" class="input-field" name="username-reg" id="username-reg" placeholder="Username" required>
		<span class="error-message" id="username-error-message" style="display:none">The username already exists</span>
        <input type="email" class="input-field" name="email-reg" id="email-reg" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="E-mail" required>
		<span class="error-message" id="email-error-message" style="display:none">The email already exists</span>
        <input type="password" class="input-field" name="pass-reg" id="pass-reg" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}" placeholder="Password" required>
		<span class="error-message" id="pass-error-message" style="display:none; color:red;">Password must be at least 8 characters long, and include at least one uppercase letter, one lowercase letter, one number, and one special character</span>
        <input type="checkbox" class="check-box" required><span>I agree to the terms & condition</span>
        <button type="submit" class="submit-btn">Register</button>
    </form>
    </div>	
    </div>
    </section>
    <footer class="footer">	
        Copyright &copy; 2020 Environmental Education | EECW Terms of Use. All right reserved
    </footer>
<script>

var x = document.getElementById("login");
var y = document.getElementById("register");
var z = document.getElementById("btn");

function register(){
    x.style.left = "-400px";
    y.style.left = "50px";
    z.style.left = "110px";	
}
function login(){
    x.style.left = "50px";
    y.style.left = "450px";
    z.style.left = "0";	
}

// check password strength
document.getElementById('pass-reg').addEventListener('input', function() {
    var passwordInput = this;
    var errorMessage = document.getElementById('pass-error-message');

    if (passwordInput.validity.patternMismatch) {
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
    }
});

</script>
</body>
</html>
