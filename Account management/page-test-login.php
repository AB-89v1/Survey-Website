<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$email = "";

// Establish connection to DB_NAME database
$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB

// Check form submit
if(isset($_POST['login-user'])) {
    
    //Prepare statement and bind parameters   
    $stmt = mysqli_prepare($mysqli,"SELECT name, panelist_id, hash FROM panelists WHERE email = ? AND active = '1'");
    mysqli_stmt_bind_param($stmt,"s",$email);
    
    //Set parameters and execute statement
    $email = $_POST['contact-email'];
    
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_store_result($stmt);
    
    $result_rows = mysqli_stmt_num_rows($stmt);
    
    $stmt->bind_result($name, $panelist_id, $hash);
    
    mysqli_stmt_fetch($stmt);
    
    if($result_rows == 1){
        
        $password = mysqli_escape_string($mysqli, $_POST['password']);
        
        if(password_verify($password,$hash)){
            
            $_SESSION['logged_in'] = TRUE;
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['hash'] = $hash;
            $_SESSION['panelist_id'] = $panelist_id;
            //Update row to record last login datetime
            mysqli_query($mysqli,"UPDATE panelists SET last_login = NOW() WHERE panelist_id = '{$string['panelist_id']}'") or die(mysqli_error($mysqli));
            // Session variables set, send to member page
            header ('Location: /survey-website.com/member-profile/');
            // Set cookie / Start Session / Start Download etc...
       }
       else{
                $msg = 'Login Failed! Please make sure that you enter the correct details and that you have activated your account.';
           }
    }else{
           $msg = 'Login Failed! Please make sure that you enter the correct details and that you have activated your account.';
        }
}
?>

<!DOCTYPE html>
<html>
    <style>
        div.password_reset{
            margin: auto;
            width: 35%;
            text-align: center;
            margin-bottom: 50px;
        }
        div.statusmsg{
            font-color: red;
            text-align: center;
            font-weight: bold;
            padding: 10px 0px 10px 0px;
        }
        div.container {
            margin: auto;
            width: 35%;
            margin-bottom: 50px;
        }
        @media screen and (max-width: 600px) {
            div.container,div.password_reset {
            width: 90%;
        
            }    
        }
        
    </style>
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
        <!-- Login form header text -->
        <div class = "container">
            <h3 style="text-align: center;">Survey Buds Login</h3>
                <? if(isset($msg)){
                    echo '<div class="statusmsg">'.$msg.'</div>';
                }
                else { ?>
                <p style="margin: 50px 0px 20px 0px; text-align: center;">
                    Please enter your e-mail address and password to login to your profile:
                </p>
               <? } ?>
                <form action="" method="post">
                    <label for="contact-email">E-mail:</label>
                    <input type="text" name="contact-email" value="<?=$email ?>" required />
                    <label for="password">Password:</label>
                    <input type="password" name="password" value="" required />
                      
                    <input type="submit" class="submit_button" name ="login-user" value="Login" />
                </form>
        </div>
        <div class = "password_reset">
            <p>
                Forgot password? <a href="/survey-website.com/pw-reset/">Click here</a> to reset.
            </p>
        </div>
    </body>
        <?php
            get_footer();
        ?>
</html>
