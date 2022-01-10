<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */
session_start();

if($_SESSION['ineligible'] == true)
{
    session_unset();
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
            
            @media screen and (max-width: 600px) {
                div.form_wrapper {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <div>
            <div class = "container">
                <div class = "form_wrapper">
                    <p>Thank you for your interest in joining Survey Buds!
                    </br></br>
                    We are cannabis research community, built to give regular consumers 
                    a voice in the development of the legal marijuana industry. 
                    Our members receive invitations to complete online surveys written by 
                    cannabis companies and other stakeholders. In exchange for their participation, 
                    members earn points that can be exchanged for cash payments issued through PayPal.
                    </p> 
                    </br>
                    <p style = "margin: auto;">
                    <a href = "/about/" target = "_blank" style = "text-align: center;">Learn more about being a Survey Buds member</a>
                    </p>
                    </br>
                    <p></p>
                    In order to sign up, please provide a few details about yourself below:
                    </p>
                    <form action = "/sign-up-script/" method = "post">
                        <label for = "dob">Please enter your date of birth:</label>
                        <input type = "date" id = "dob" name = "dob" required/>
                        
                        <label for = "gender" id = "gender">Gender:</label>
                        <select name = "gender" id = "gender" required>
                            <option value = "" selected disabled hidden>Select one:</option>
                            <option value = "female">Female</option>
                            <option value = "male">Male</option>
                            <option value = "non-binary">Non-Binary</option>
                            <option value = "undisclosed">Undisclosed</option>
                        </select>
        
                        <label for = "zip">Zip code (5 digit):</label>
                        <input type = "text" id = "zip" name = "zip" pattern = "\d{5}" required/>
                        
                        <input type = "submit" class = "submit" value = "Next Page"/>
                    </form>    
                </div>
            </div>
        </div>
    </body>
    <div>
      <?
        get_footer();
    ?>  
    </div>
</html>
