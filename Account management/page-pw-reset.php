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
            div.form_wrapper {
                margin: auto; 
                width: 37%; 
                margin-bottom: 50px;
            }
            
            @media screen and (max-width: 600px) {
                div.form_wrapper {
                    width: 90%;
                }
            }
        </style>
    </head>
    <body>
        <?php
        if(isset($_POST['contact-email']) && !empty($_POST['contact-email']))
        {
            //Prepare statement and bind parameters
            $stmt = mysqli_prepare($mysqli,"SELECT name, hash, active FROM panelists WHERE email = ? LIMIT 1");
            $mysqli_stmt_bind_param($stmt,"s",$email);
            
            //Set parameters and execute select statement
            $email = $_POST['contact-email'];
            mysqli_stmt_execute($stmt);
            
            mysqli_stmt_store_result($stmt);
        
            $result_rows = mysqli_stmt_num_rows($stmt);
        
            $stmt->bind_result($name,$hash,$active);
        
            mysqli_stmt_fetch($stmt);
            

            if($result_rows > 0)
            {
              if($active == 1)
              {
                
                $msg = 'A password reset link has been sent. Please check your inbox';

                $to      = $email; 
                $subject = 'Password Reset for Survey Buds'; 
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
                                <h3><center>Password Reset for Survey Buds panel</center></h3>
                                <br>
                                Hi '.$name.',
                                <br><br>
                                You recently requested to reset your password for your Survey Buds account. 
                                <br><br>
                                To continue, please click the link below:
                                <br><br>
                                <button type="button"><a href="https://survey-website.com/pw-reset-form/?email='.$email.'&hash='.$hash.'">Reset Password</a></button>
                                <br><br>
                                If you did not request a password reset, please contact us at support@survey-website.com so we can secure your account.
                                <br><br>
                                Have a great day,
                                <br><br>
                                <p class="signature">Survey Buds<p>
                            </div>
                        </div>
                  </body>
                </html>
                '; // Our message above including the link
                                      
                $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
                $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
                $headers [] = 'MIME-Version: 1.0';
                mail($to, $subject, $message, implode("\r\n",$headers)); // Send our email
                echo('<div class = "successmsg"><center><br><br><br><br><br>
                        '.$msg.'
                        <br><br><br><br><br><br><br><br><br>
                        </center></div>');
                get_footer();
                exit();
                }  
              else
              {
                echo (
                        '<div><center><br><br><br><br><br><br>
                        This account has not been activated. Please check your inbox for the e-mail verification link or <a href="/survey-website.com/resend-verify">click here </a>to resend it.
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
        <div class = "form_wrapper">
            <h3 style="text-align: center;">Password Reset</h3>
        <br>   
        <?php if($msg==""){ ?>
                <p>Please enter the e-mail address you previously used to sign up </p>
        <? }//else{echo($msg);} ?>
            <form action="" method="post">
                <label for="contact-email"> Account E-mail:</label>
                <input type="email" name="contact-email" value="" required />

                <input type="submit" class="submit_button" value="Send Password Reset Link" />
            </form>
        <br>
        </div>
    </body>
        <div>
            <?
            get_footer();
            ?>
        </div>
</html>