<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article</title>
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
	session_start(); // starting a session
	include("header.php");
	require_once("config.php"); // initialize database configuration
	
	// get the article ID from the URL
	if (isset($_GET['id'])) {
		$article_id = intval($_GET['id']);
	} else {
		die("Invalid article ID.");
	}
	
	$conn = connect();
	
	if ($conn) {
		$sql_table = "articles";

		// prepare the SQL command to fetch the article by ID
		$query_string = "SELECT title, content, category, video FROM $sql_table WHERE id = ?";
		$query = $conn->prepare($query_string);

		if ($query === false) {
			die("Error preparing the query: " . $conn->error);
		}

		$query->bind_param("i", $article_id); // bind the article ID to the query
		$query->execute(); // execute the query

		// get the result
		$result = $query->get_result();

		if ($result && $result->num_rows > 0) {
			$article_details = $result->fetch_assoc();
			
			echo "<div class='article-headline'>";
            echo "<h1>".$article_details["title"]."</h1>";
            echo "<div class='border'></div>";
			echo "</div>";
			
			echo "<section class='content1b'>";
			echo "<div class='content'>".$article_details["content"]."</div>";
			echo "</section>";
		}
	}
	?>

    <footer class="footer">	
        Copyright &copy; 2020 Environmental Education | EECW Terms of Use. All right reserved
     </footer>

</body>
</html>
