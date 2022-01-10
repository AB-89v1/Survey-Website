<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */
 
 session_start();
 
 if(!isset($_SESSION['signup_id']))
 {
     exit;
 }
 
 if($_SESSION['ineligible'] == true)
 {
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
            
            input[type="radio"], input[type="checkbox"] {
                height: 19px;
                opacity: 100;
                width: 20px;
                display: inline !important;
                -webkit-appearance: checkbox !important;
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
                <form action = "/sign-up-4-script/" method = "post">
                    <p>Do you or does anyone in your household currently work in the legal cannabis industry?</p>
                    <label for = "industry">
                        <input type = "radio" name = "industry" value = "Yes" required> Yes</label>
                    <label for = "industry">
                        <input type = "radio" name = "industry" value = "No"> No</label>
                    <br>   
                    <p>What is the capital city of the state where you live?<p>
                    
                    <input type = "text" name = "capital" value = "" required>
                        
                    <input type = "submit" class = "submit" value = "Next Page"/>
                </form>
            </div>
        </div>
    </body>
    <div>
        <?
            get_footer();
        ?>
    </div>
</html>
