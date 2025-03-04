<nav>
<div class="logo">
    <img src="images/logo3.png" alt="">
</div>
<ul>
    <li><a href="index.php" class="active">Home</a></li>
    <li><a href="environment.php">ENVIRONMENT</a></li>
    <li><a href="what.php">WHAT CAN WE DO</a></li>
    <li><a href="about.php">ABOUT</a></li>
    <?php if(!isset($_SESSION["username"])) { ?> <li><a href="login.php">LOGIN/REGISTER</a></li> <?php } ?>
<?php 
if(isset($_SESSION["username"])) { 
	echo "<li class='welcome'>";
	echo "<p>Welcome ".$_SESSION['username']."</p>";
	echo "<form method='post' style='display:inline;'><button type='submit' name='logout' class='logout-btn'>Logout</button></form>";
	echo "</li>"; 
} 
if (isset($_POST["logout"])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: index.php");
}
?>
</ul>
</nav>
