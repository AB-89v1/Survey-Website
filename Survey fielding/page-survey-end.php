<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Start session and initialize session variables
session_start();

if(isset($_SESSION['valid_response']) && $_SESSION['valid_response'] == FALSE){
    header('location: https://survey-website.com/');
    exit;
}

//Connect to database
$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error());

//Check connection
if(!$mysqli)
{
    echo 'Unable to connect to server';
    exit;
}

if(!mysqli_select_db($mysqli, 'DB_NAME'))
{
    echo 'Unable to select database';
    exit;
}

get_header();

// Start the Loop.
while ( have_posts() ) :
    the_post();
    get_template_part( 'partials/content', 'page' );

    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }

endwhile;

?>
<!DOCTYPE html>
<html>
    <head>
        <script>
        </script>
        <style>
                body {
                    background-image: url("https://survey-website.com/wp-content/themes/go-child02/jar.png");
                    background-size: cover;
                }
                
                div.container {
                    margin-top: 150px;
                    margin-bottom: 150px;
                }

                div.form_wrapper {
                    width: 35%;
                    margin: auto;
                    padding: 20px 20px 20px 20px;
                    background-color: #f2f2f2;
                }
                
                input[type="radio"], input[type=""], input[type="checkbox"] {
                    height: 19px;
                    opacity: 100;
                    width: 20px;
                    display: inline !important;
                    -webkit-appearance:  !important;
                }
                
                input[type="submit"] {
                    margin-top: 50px;
                }
                
                @media screen and (max-width: 600px) {
                    div.form_wrapper {
                        width: 100%;
                    }
                }
      </style>
    </head>
    <body>
        <div class = "container">
            <div class = "form_wrapper">
                <p> You have reached the end of the survey. Thank you for your participation!</p>
                <p> Your responses have been recorded, and your account has been credited <span style="font-weight: bolder;"><?php echo $_SESSION['incentive'];?></span> Survey Buds Rewards Points.</p>
                <p><a href = "https://survey-website.com/test-login">Login to your account </a> to see your updated Rewards balance.</p>
            </div>
        </div>
    </body>
    <div>
        <?
            get_footer();
        ?>
    </div>
</html>
<?php

session_unset();

?>
