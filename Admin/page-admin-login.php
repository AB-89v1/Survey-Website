<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */

session_start();

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
                <?php if($_SESSION['request'] == 'sent'){ ?>
                <div class = "form_wrapper" style = "margin-top: 200px; margin-bottom: 200px">
                    <p>Request to create admin account sent; Account verification e-mail will be sent if approved</p>
                </div>
                <?php }else{ if($_SESSION['login'] == 'failed'){ ?>
                <div class = "form_wrapper">
                    <p>Login failed; please try again</p>
                </div>
                <?php } ?>
                <div class = "form_wrapper">
                    <h5>Adminstrator login:</h5>
                    <form name = "admin_login" action = "/survey-website.com/admin-login-script/" method = "post">
                        <label for = "email">Administrator e-mail:</label>
                        <input type = "email"  name = "email" required/>
                        <label for = "password">Administrator password:</label>
                        <input type = "password" name = "password" required/>
                        <input type = "submit" class = "submit" name = "login_form" value = "Login"/>
                    </form>    
                </div>
                <div class = "form_wrapper">
                    <h5>Create admin account:</h5>
                    <form name = "admin_create" action = "/survey-website.com/admin-login-script/" method = "post">
                        <label for = "email">Account e-mail:</label>
                        <input type = "email"  name = "email" required/>
                        <label for "password"> Choose password (min. 8 characters):</label>
                        <input type = "password" name = "password" minlength = 8 required/>
                        <label for "password2"> Re-Enter Password:</label>
                        <input type = "password" name = "password2" minlength = 8 required/>
                        <input type = "submit" class = "submit" name = "register_form" value = "Request Admin Account"/>
                    </form>    
                </div>
                <?php } ?>
            </div>
        </div>
    </body>
    <div>
      <?
        get_footer();
    ?>  
    </div>
</html>
