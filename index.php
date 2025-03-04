<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
	
	echo "<section class='sec1a'></section>";

	echo "<div class='headline'>";  
	echo "<h1>Home</h1>";
	echo "<div class='border'></div>";
	echo "</div>";
	
	echo "<section class='content1a'>";
		
	$conn = connect();
	if($conn) {
		$sql_table="articles";
				
		// set up the SQL command
		$query = $conn->prepare("SELECT id, title, content, category, image FROM $sql_table WHERE image IS NOT NULL ORDER BY updated_at DESC");
		
		if ($query === false) {
			die("Error preparing the query: " . $conn->error);
		}
		
		$query->execute();
		$result = $query->get_result();
		
		if($result && $result->num_rows > 0) {
			while ($article = $result->fetch_assoc()) {
				echo "<div class='card'>";
				echo "<div class='imgcard'>";
				echo "<img src=".$article["image"].">"; 
				echo "</div>";
				echo "<div class='title'>";
				echo "<h1>".$article["category"]."</h1>";
				echo "</div>";
				echo "<div class='des'>";  
				echo "<p>".$article["title"]."</p>";
				echo "<div class='artbtn'>";
				echo "<a href='article.php?id=" . $article["id"] . "'><button>Read More</button></a>";  
				echo "</div>";  
				echo "</div>";
				echo "</div>";
			}
		}
	} else {
		echo "<script>alert('Sorry, there is a problem with the database connection. Please try again later!');</script>";
	}
	
	echo "</section>";

	echo "<section class='content2a'>";

	if($conn) {
		// set up the SQL command
		$query = $conn->prepare("SELECT id, title, content, category, video FROM $sql_table WHERE video IS NOT NULL ORDER BY updated_at DESC");
		
		if ($query === false) {
			die("Error preparing the query: " . $conn->error);
		}

		$query->execute();
		$result = $query->get_result();
		
		if($result && $result->num_rows > 0) {
			while ($article = $result->fetch_assoc()) {
				echo "<div class='vcard'>";
				echo "<div class='vidcard'>";
				echo "<iframe src=".$article["video"]." frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>"; 
				echo "</div>";
				echo "<div class='title'>";
				echo "<h1>".$article["category"]."</h1>";
				echo "</div>";
				echo "<div class='des'>";  
				echo "<p>".$article["title"]."</p>";
				echo "<div class='artbtn'>";
				echo "<a href='article.php?id=" . $article["id"] . "'><button>Read More</button></a>";  
				echo "</div>";  
				echo "</div>";
				echo "</div>";
			}
		}
	} else {
		echo "<script>alert('Sorry, there is a problem with the database connection. Please try again later!');</script>";
	}
	
	$query->close();
	$conn->close();
	echo "</section>";
	?>

    <section class="content3a">
        <div class="contact-section">

            <h1>Contact Us</h1>
            <div class="border"></div>
            <form class="contact-form" action="enquiry.php" method="post">
              <input type="text" class="contact-form-text" name="contact-name" id="contact-name" placeholder="Your name" required>
              <input type="email" class="contact-form-text" name="contact-email" id="contact-email" placeholder="Your email" required>
              <input type="text" class="contact-form-text" name="contact-phone" id="contact-phone" placeholder="Your phone" required>
              <textarea class="contact-form-text" name="message-enquiry" id="message-enquiry" placeholder="Your message" required></textarea>
              <input type="submit" class="contact-form-btn" name="enquiry" value="Send">
            </form>
          </div>
    </section>

    <footer class="footer">	
       Copyright &copy; 2020 Environmental Education | EECW Terms of Use. All right reserved
    </footer>
	
	 <script>
        $(document).ready(function() {
            $('.contact-form').submit(function(event) {
                event.preventDefault(); // prevent the default form submission

                var formData = $(this).serialize();
                // send an AJAX request
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    success: function(data) {
						data = JSON.parse(data); // parse the JSON response
						if(data.success){
							alert(data.message); // display the success message
						} else {
							alert("Error: " + JSON.stringify(data.errors)); // display the error messages
						}
                    },
                    error: function() {
                        alert('An error occurred while submitting the form.'); // display an error message if the request fails
                    }
                });
            });
        });
    </script>
	
</body>
</html>
