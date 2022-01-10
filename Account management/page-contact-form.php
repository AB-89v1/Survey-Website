<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysql_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB
$errormsg="0";

if(isset($_SESSION['name']))
{
    $name = $_SESSION['name'];
}
else
{
    $name = "";
    $_SESSION['name'] = $name;
}

if(isset($_SESSION['email']))
{
    $email = $_SESSION['email'];
}
else
{
    $email = "";
    $_SESSION['email'] = $email;
}

?>



<!DOCTYPE html>
<html>
        <style>
            div.successmsg {
                font-size: 22px;
            }
            div.exists {
                font-size: 22px;
            }
        </style>
        <!--
        <style>
            #colophon {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 100px;
            }
            div.successmsg {
                position: relative;
                min-height: 100%;
                font-size: 22px;
            }
            body {
                height: 100%;
                width: 100%;
                position: relative;
                padding-bottom: 100px;
            }
        </style>
        -->
        <!-- Start PHP Code for header (Go theme) -->
        <?php
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
        ?>
    <body>
            <?php
            if(isset($_POST['submit_button']))
            {
                
                   $email = $_POST['contact-email'];
                   $to = 'support@survey-website.com';
                   $subject = 'Support request';
                   $message = '
                   <html>
                       <head>
                           <h3>Support request from '.$email.'</h3>
                       </head>
                       <body>
                       '.$_POST['message'].'
                       </body>
                   </html>';
                   $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
                   $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
                   $headers [] = 'MIME-Version: 1.0';
                   mail($to, $subject, $message, implode("\r\n",$headers));
           
                   print 1;
        
                }
        ?>
        <!-- End PHP Code -->
        
            <div style="margin: auto; width: 35%; margin-bottom: 50px">
                <!-- break into PHP, if errormsg = "0", show Congrats message -->
                <!-- <? if(!isset(POST_['message'])){ ?> -->
                <h3>Contact Support</h3>
                <form action="" method="post">
                   <label for "name">Username:</label>
                   <input type = "text" name = "name" value = "<?=$name?> "/>
                   <label for "contact-email">*E-mail:</label>
                   <input type = "email" name = "contact-email" value = "<?=$email?>" required/>
                   <label for = "message">*Message:</label>
                   <input type = "text" name = "message" class = "message" value = "" required/>
                   
                   <input type = "submit" name = "submit_button" value = "Contact Us" />
                </form>
                <? } ?>
            </div>
            <div style="margin: auto; width: 35%;">
                <a href = "/survey-website.com/about/" target = "_blank"> Learn more about the Survey Buds community</a><br><br><br>
            </div>
    </body>
            <div>
                <?
                get_footer();
                ?>
            </div>
</html>
