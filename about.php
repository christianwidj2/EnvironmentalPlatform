<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
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
	?>

    <section class="sec1d"></section>

        <div class="headline">
            <h1>About Us</h1>
            <div class="border"></div>
        </div>

    <section class="contentd">	
        
        <p>The Environmental Education is the leading global environmental authority that sets the global environmental agenda, promotes the coherent implementation of the environmental dimension of sustainable development within the Environemtal education system, and serves as an authoritative advocate for the global environment.</p><br><br>

        <img src="images/image13.jpg" alt="">
    </section>
    

    <footer class="footer">	
        Copyright &copy; 2020 Environmental Education | EECW Terms of Use. All right reserved
     </footer>

</body>
</html>
