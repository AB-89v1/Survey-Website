<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */
session_start();
$_SESSION['ineligible'] = true;

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

print $_SESSION['rslt'];

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
                margin-top: 250px;
                margin-bottom: 250px;
            }

            div.form_wrapper {
                width: 35%;
                margin: auto;
                padding: 20px 20px 20px 20px;
                background-color: #f2f2f2;
            }
            @media screen and (max-width: 600px){
                div.form_wrapper {
                    width:90%;
                }
            }
            
  </style>
    </head>
    <body>
        <div class = "container">
            <div class = "form_wrapper">
                <p>We're sorry, unfortunately you do not qualify for participation in Survey Buds. Thank you for your time.</p>
            </div>
        </div>
    </body>
    <div>
        <?
            get_footer();
        ?>
    </div>
</html>

