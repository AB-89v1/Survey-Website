<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysql_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB
$errormsg="0";
$name = "";
$email = "";

?>

<!DOCTYPE html>
<html>
    <head>    
        <style>
            div.successmsg {
                font-size: 22px;
            }
            div.exists {
                font-size: 22px;
            }
            
            div.form_wrapper {
                width: 35%;
                margin: auto;
                margin-bottom: 50px;
            }
            div.learn_more {
                width: 35%;
                margin: auto;
            }
            @media screen and (max-width: 600px) {
                div.form_wrapper, div.learn_more {
                    width: 90%;
                    margin-left:5%;
                }
            }
        </style>
    </head>
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
            //Check all post variables are set and contain values
            if(isset($_POST['contact-name']) && !empty($_POST['contact-name']) 
                    AND isset($_POST['contact-email']) && !empty($_POST['contact-email']) 
                    AND isset($_POST['password2']) && !empty($_POST['password2']))
            {
                
                //Check passwords entered match
                if($_POST['password'] !== $_POST['password2']){
                    $errormsg = 'Passwords must match, please try again';
                    $name = $_POST['contact-name'];
                    $email = $_POST['contact-email'];
                }
            
                if($errormsg=="0"){
                    // Input validated, set success message
                    $msg = 'Your account has been made, <br/> please verify it by clicking the activation link that has been send to your email.';
                    
                    //Prepare INSERT statement and bind parameters
                    $stmt = mysqli_prepare($mysqli, "INSERT INTO panelists (`signup_id`, `name`, `email`, `hash`, `ip`, `ip2`) VALUES(?,?,?,?,?,?)");
                    mysqli_stmt_bind_param($stmt,"isssss",$signup_id,$name,$email,$hash,$ip,$ip2);
                    
                    // Set parameters and execute statement
                    $signup_id = $_SESSION['signup_id'];
                    $name = $_POST['contact-name'];
                    $email = $_POST['contact-email'];
                    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $ip = $_SESSION['ip'];
                    $ips = $_SESSION['ip2'];
                    mysqli_stmt_execute($stmt);
                    
                    if(mysqli_errno($mysqli) == 1062){
                        
                        print('<div class = "exists">
                                <center>
                                <br><br><br><br><br>
                                An account for this e-mail already exists, please visit <a href="survey-website.com/test-login/">login page</a>.<br><br>
                                <a href="survey-website.com/resend-verify">Click here</a> to resend e-mail verification.
                                <br><br><br><br><br><br><br>
                                </center>
                        </div>');
                        get_footer();
                        exit();
                    }
                    // Start Verification email    
                    $to      = $email; // Send email to our user
                    $subject = 'Survey Buds Account Verification'; // Give the email a subject 
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
                                    Once verified, you will receive a link to complete your profile survey for 200 Survey Buds points!
                                    <br><br>
                                    Have a great day,
                                    <br><br>
                                    <p class="signature">Survey Buds Teams<p>
                                </div>
                            </div>    
                      </body>
                    </html>';
                    
                    // Our message above including the link
                                          
                    $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
                    $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
                    $headers [] = 'MIME-Version: 1.0';
                    mail($to, $subject, $message, implode("\r\n",$headers)); // Send our email
                    echo('<div class = "successmsg"><center><br><br><br>
                            '.$msg.'
                            <br><br><br><br><br><br><br><br><br><br>
                            </center></div>');
                    get_footer();
                    exit();
                }else{
                    echo("<center><br><br><font color=red size=4>".$errormsg."</font><br></center>");
                    echo("<br>");
                }
            }
        ?>
        <!-- End PHP Code -->
        
            <div class = form_wrapper>
                <!-- break into PHP, if errormsg = "0", show Congrats message -->
                <!-- <? if($errormsg=='0'){ ?> -->
                <p style="margin-top:50px; margin-bottom:20px;">
                    Congratulations! You qualify for participation in the Survey Buds panel. Enter your name and e-mail address below and watch your inbox for a verification link to complete your registration.
                </p>
                <!-- <? } ?> -->
                <form action="" method="post">
                    <label for = "contact-name">First Name:</label>
                    <input type = "text" name="contact-name" value="<?=$name?>" required />
                    <label for = "contact-email">Email:</label>
                    <input type = "email" name="contact-email" value="<?=$email?>" required />
                    <label for = "password">Password (minimum 8 characters):</label>
                    <input type = "password" name="password" value = "" minlength = 8 required />
                    <label for = "password2">Confirm Password:</label>
                    <input type = "password" name = "password2" value = "" minlength = 8 required />
                      
                    <input type = "submit" name="submit_button" value="Sign up" />
                </form>
            </div>
            <div class = "learn_more">
                <a href = "survey-website.com/about/" target = "_blank"> Learn more about the Survey Buds panel</a><br><br><br>
            </div>
    </body>
            <div>
                <?
                get_footer();
                ?>
            </div>
</html>
