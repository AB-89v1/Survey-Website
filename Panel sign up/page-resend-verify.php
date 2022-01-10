<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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
    
$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB
 
$msg = "";
        
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            div.container {
                margin: auto;
                width: 35%;
                margin-bottom: 50px;
            }
            @media screen and (max-width: 600px) {
                div.container {
                    width: 100%;
                    padding-left:10px;
                }
            }
        </style>
    </head>
    <body>
        <?php
        if(isset($_POST['contact-email']) && !empty($_POST['contact-email'])){
            
            //Prepare select statement
            $stmt = mysqli_prepare($mysqli, "SELECT name, hash, active FROM panelists WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            //Set parameters and execute
            $email = $_POST['contact-email'];
            mysqli_stmt_execute($stmt);
            
            //Store and bind results
            mysqli_stmt_store_result($stmt);
            $result_rows = mysqli_stmt_num_rows($stmt);
            $stmt->bind_result($name, $hash, $active);
            mysqli_stmt_fetch($stmt);
            

            if($result_rows > 0){
                
                if($active == "0"){
                    
                    $msg = 'An e-mail verification has been resent. Please check your inbox';

                    $to      = $email; 
                    $subject = 'Resend - Survey Buds Account Verification'; // Give the email a subject 
                    $message = '
                        <html>
                            <head>
                                <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
                                <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
                                <style>
                                    body {
                                          font-family: "Quicksand";
                                          font-size: 20px;
                                          }
                                    div.center_column {
                                        background-color: #bbe1d4;
                                        margin: auto;
                                        width: 70%;
                                    }
                                    div.text_container {
                                        padding:5% 10% 5% 10%;
                                    }
                                    h3 {
                                    font-family: "Poppins";
                                    color: #323648;
                                    font-weight:600;
                                    }
                                    button{
                                          background-color: #3c896d;
                                          text-align: center;
                                          padding: 14px 40px;
                                          border: none;
                                          font-size: 20px;
                                          margin: 4px 2px;
                                          border-radius: 10px;
                                          }
                                    button a{
                                        color: white;
                                    }
                                    a{
                                          text-decoration: none;
                                          color: purple;
                                          }
                                    p.signature{
                                        font-weight: bolder;
                                        }
                                    @media screen and (max-width: 600px) {
                                        div.center_column {
                                            width: 100%;
                                        }
                                    }
                                </style>
                            </head>
                            <body>
                                <div class = center_column>
                                    <div class = text_container>
                                        <h3><center>Account Verification for Survey Buds survey panel</center></h3>
                                        <br>
                                        Hi '.$name.',
                                        <br><br>
                                        Thanks for signing up to the Survey Buds panel!
                                        <br><br>
                                        Please click the link below to verify your e-mail and activate your account:
                                        <br><br>
                                        <button type="button"><a href="https://survey-website.com/verify/?email='.$email.'&hash='.$hash.'">Verify E-mail</a></button>
                                        <br><br>
                                    
                                        Once verified, you will receive a link to complete your profile, and then you will be ready<br>
                                        to start earning rewards for answering surveys!
                                        <br><br>
                                        Have a great day,
                                        <br><br>
                                        <p class="signature">Survey Buds Team<p>
                                    </div>
                                </div>
                          </body>
                        </html>'; 
                                      
                $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
                $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
                $headers [] = 'MIME-Version: 1.0';
                mail($to, $subject, $message, implode("\r\n",$headers)); // Send our email
                echo('<div class = "successmsg"><center><br><br><br><br><br>
                        '.$msg.'
                        <br><br><br><br><br><br><br><br><br><br><br>
                        </center></div>');
                get_footer();
                exit();
                }  
              else
              {
                echo (
                        '<div><center><br><br><br><br><br><br>
                        E-mail has already been activated. Please <a href="/survey-website.com/test-login/">login</a> with your e-mail address and password
                        <br><br><br><br><br><br><br><br><br><br></center></div>'
                    );
                get_footer();
                exit();
              }  

            }
            else
            {
                $msg = 'No account for this e-mail exists. Please try entering again or return to sign up';
                echo (
                        '<div><center><br><br><br>
                        '.$msg.'
                        </center></div>'
                    );
            } 
        }        
        ?>
        <!-- End PHP code, start form html -->
        <div class="container" style="margin: auto; width: 35%; margin-bottom: 50px">
            <br><br><br>    
            <?php if($msg==""){ ?>
                    <p>Please enter the e-mail address you previously used to sign up </p>
            <? }//else{echo($msg);} ?>
                <form action="" method="post">
                    <label for="contact-email"> Account E-mail:</label>
                    <input type="email" name="contact-email" value="" required />
    
                    <input type="submit" class="submit_button" value="Resend Verification" />
                </form>
            <br><br><br><br><br>
        </div>
    </body>
        <div>
            <?
            get_footer();
            ?>
        </div>
</html>