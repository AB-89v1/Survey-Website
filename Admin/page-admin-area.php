<?php

session_start();

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */

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

if($_SESSION['login'] != "success"){
    header('location: https://survey-website.com/');
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
        <style>
            body {
                background-image: url("https://survey-website.com/wp-content/themes/go-child02/jar.png");
                background-size: cover;
            }
            
            div.container {
                margin-top: 100px;
                margin-bottom: 100px;
            }

            div.form_wrapper {
                width: 35%;
                margin: auto;
                padding: 20px 20px 20px 20px;
                background-color: #f2f2f2;
            }
           
            input.submit {
                margin: auto;
            }
            a {
                color: white;
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
                <p>Access the link below to distribute survey invite. Ensure that survey ID and panelist 
                array from phpMyAdmin have been entered into survey-distribute-script.php in cPanel</p>
                <button type = "button">
                    <a href = "/survey-website.com/survey-distribute/">Prepare Survey Link</a>
                </button>
            </div>
        </div>    
    </body>
</html>

<?php
get_footer();
?>