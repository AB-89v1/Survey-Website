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
                <form action = "/sign-up-3-script/" method = "post">
                    <p>What is the highest degree or level of education you have completed?</p>
                    <label for = "education">
                        <input type = "radio" name = "education" value = "< HS degree" required>Less than high school degree</label>
                    <label for = "education">
                        <input type = "radio" name = "education" value = "HS degree">High school degree or equivalent</label>    
                    <label for = "education">
                        <input type = "radio" name = "education" value = "Some college">Some college, no degree</label>
                    <label for = "education">
                        <input type = "radio" name = "education" value = "Associates">Associate's degree</label>
                    <label for = "education">
                        <input type = "radio" name = "education" value = "Bachelors">Bachelor's degree</label>
                    <label for = "education">
                        <input type = "radio" name = "education" value = "Graduate">Graduate degree</label>
                    <br>   
                    <p>Which of the following best describes your current employment status?<p>
                    <label>
                        <input type = "radio" name = "employment" value = "40+">Employed, working 40+ hours per week</label>
                    <label>
                        <input type = "radio" name = "employment" value = "<40">Employed, working less than 40 hours per week</label>
                    <label>
                        <input type = "radio" name = "employment" value = "looking">Unemployed, looking for work</label>
                    <label>
                        <input type = "radio" name = "employment" value = "not looking">Unemployed, NOT looking for work</label>
                    <label>
                        <input type = "radio" name = "employment" value = "self">Self-employed</label>
                    <label>
                        <input type = "radio" name = "employment" value = "retired">Retired</label>
                    <label>
                        <input type = "radio" name = "employment" value = "disabled">Disabled/unable to work</label>
                        
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
