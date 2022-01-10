<?php
session_start();

//Check user successfuly logged in
if($_SESSION['logged_in'] != TRUE){
header('location: /survey-website.com/test-login/');
exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysql_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB

$errormsg = "0";
$name = $_SESSION['name'];
$email = "";
$name_msg = "";

// Start PHP Code for header (Go theme)
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
<!DOCTYPE html>
<html>
    <head>
        <style>

            div.container {
                min-height: 500px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            div.terminal_container {
                width: 35%;
                margin: auto;
                padding: 10px;
            }

            div.success_row {
                margin: auto;
                margin: 25px 0px 25px 0px;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 5px;
            }

            div.success_img {
                flex: 2;
                display: flex;
                justify-items: center;
            }

            div.success_msg {
                flex: 8;
                color: #3c896d;
                font-weight: bold;
            }

            div.error_row {
                margin: auto;
                margin: 25px 0px 25px 0px;
                display:flex;
                justify-content: center;
                align-items: center;
                padding: 5px;
            }

            div.error_img {
                flex: 2;
                display: flex;
                justify-items: center;
            }

            div.error_msg {
                flex: 8;
                color: #ff000;
                font-weight: bold;
            }

            img {
                height:50px;
            }

            div.return_row {
                display: flex;
                margin: auto;
                justify-content: center;
                align-items: center;
                margin: 50px 0px 15px 0px;
            }

            div.return {
                flex: 8;
                margin: auto;
                margin: 30px 0px 25px 0px;
            }

            div.return_img {
                flex: 2;
            }
            
            div.form_wrapper {
                margin: auto; 
                width: 35%; 
                margin-bottom: 50px
            }
            
            div.learn_more {
                margin: auto; 
                width: 35%;
            }
            
            @media screen and (max-width: 600px) {
                div.form_wrapper {
                    width: 95%;
                }
                div.learn_more {
                    width: 95%;
                }
                div.terminal_container {
                    width: 95%;
                }
            }
        </style>
    </head>
<body>
<?php
//Start PHP code for form

    //Update database and set success message for changed username if username is changed
    if(isset($_POST['username']) && !empty($_POST['username']))
    {
        if($_POST['username'] != $_SESSION['name'])
        {
            //Update session variable for name
            $_SESSION['name'] = $_POST['username'];
            
            //Prepare statement
            $stmt = mysqli_prepare($mysqli,"UPDATE panelists SET name = ? WHERE email = ?");
            mysqli_stmt_bind_param($stmt,"ss",$name, $email);
            
            //Set parameters
            $name = $_POST['username'];
            $email = $_SESSION['email'];
            
            //Execute statement
            mysqli_stmt_execute($stmt);
            
            $name_msg = 'Username has been updated';
            
        }
    }
    // Update database if email is changed
    if(isset($_POST['email']) && !empty($_POST['email']))
    {
        //Check if both email fields match
        if($_POST['email'] == $_POST['confirm-email'])
        {
            //Check if e-mail was actually changed from current session user
            if($_POST['email'] != $_SESSION['email'])
            {
                
                //Prepare statement and bind parameters
                $stmt = mysqli_prepare($mysqli,"UPDATE panelists SET email = ?, active = ? WHERE email = ?");
                mysqli_stmt_bind_param($stmt,"sis",$newemail,$active,$oldemail);
                
                //Set parameters
                $newemail = $_POST['email'];
                $active = 0;
                $oldemail = $_SESSION['email'];
                
                //Execute statement
                mysqli_stmt_execute($stmt);
                
                //Return error message if e-mail exists in database
                if(mysqli_errno($mysqli) == 1062)
                {
                    //Include the success message for username change if applicable
                    if($name_msg !== "")
                    {
                            print('<div class = "container">
                                    <div class = "terminal_container">
                                          <div class = "success_row">
                                                  <div class = "success_img">
                                                      <img src="https://survey-website.com/wp-content/themes/go-child02/check.svg">
                                                  </div>
                                                  <div class = "success_msg">
                                                      '.$name_msg.'
                                                  </div>
                                          </div>
                                          <div class = "error_row">
                                                  <div class = "error_img">
                                                      <img src = "https://survey-website.com/wp-content/themes/go-child02/warning.svg">
                                                  </div>
                                                  <div class = "error_msg">
                                                    An account for this e-mail already exists.</br> If you are the owner, please visit <a href="/survey-website.com/test-login/">login page</a> to access this account.<br>
                                                    To resend e-mail verification, <a href="/survey-website.com/resend-verify">Click here</a>.
                                                 </div>
                                          </div>
                                          <div class = "return_row">
                                              <div class = "return_img">
                                              </div>
                                              <div class = "return">
                                                        <a href = "/survey-website.com/member-profile/">Click Here</a> to return to your profile page
                                              </div>
                                          </div>
                                      </div>
                                    </div>');
                      }
                      //If username unchanged, just return existing e-mail error
                      else
                      {
                          print('<div class = "container">
                                  <div class = "terminal_container">
                                      <div class = "error_row">
                                          <div class = "error_img">
                                              <img src = "https://survey-website.com/wp-content/themes/go-child02/warning.svg">
                                          </div>
                                          <div class = "error_msg">
                                              An account for this e-mail already exists.</br> If you are the owner, please visit <a href="/survey-website.com/test-login/">login page</a> to access this account.<br>
                                              To resend e-mail verification, <a href="/survey-website.com/resend-verify">Click here</a>.
                                          </div>
                                            <div class = "return_row">
                                                <div class = "return_img">
                                                </div>
                                                <div class = "return">
                                                          <a href = "/survey-website.com/member-profile/">Click Here</a> to return to your profile page
                                                </div>
                                            </div>
                                      </div>
                                  </div>');
                      }
                        //Existing e-mail is a 'terminal error', so we include the footer, end session, and exit to prevent form reloading
                        get_footer();
                        session_destroy();
                        exit();
                }
                //Return e-mail changed AND username changed success message if applicable
                if($name_msg !== ""){
                    print(
                      '<div class = "container">
                            <div class = "terminal_container">
                                <div class = "success_row">
                                    <div class = "success_img">
                                        <img src = "https://survey-website.com/wp-content/themes/go-child02/check.svg">
                                    </div>
                                    <div class = "success_msg">
                                        Your email has been updated. Please check your inbox for a verification link to confirm your new email address.
                                    </div>
                                </div>
                                <div class = "success_row">
                                    <div class = "success_img">
                                        <img src = "https://survey-website.com/wp-content/themes/go-child02/check.svg">
                                    </div>
                                    <div class = "success_msg">
                                        '.$name_msg.'
                                    </div>
                                </div>
                                  <div class = "return_row">
                                      <div class = "return_img">
                                      </div>
                                      <div class = "return">
                                                <a href = "/survey-website.com/test-login/">Click Here</a> to return to login
                                      </div>
                                  </div>
                          </div>
                      </div>');
                      get_footer();
                }
                //If username unchanged, just return e-mail changed success message
                else{
                    print('<div class = "container">
                                <div class = "terminal_container">
                                    <div class = "success_row">
                                        <div class = "success_img">
                                            <img src = "https://survey-website.com/wp-content/themes/go-child02/check.svg">
                                        </div>
                                        <div class = "success_msg">
                                            Your email has been updated. Please check your inbox for a verification link to confirm your new email address.
                                        </div>
                                    </div>
                                    <div class = "return_row">
                                        <div class = "return_img">
                                        </div>
                                        <div class = "return">
                                            <a href = "/survey-website.com/test-login/">Click Here</a> to return to login
                                        </div>
                                    </div>
                                </div>
                           </div>');
                    get_footer();
                }
                // Send verification email
                              
                  $hash = $_SESSION['hash'];
                  $to = $newemail; // Send email to our user
                  $subject = 'Survey Buds Verification: Updated E-mail'; // Give the email a subject
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
                                <h3><center>Verification of updated email for Survey Buds survey panel</center></h3>
                                <br>
                                  Hi '.$name.',
                                  <br><br>
                                  You are receiving this message because you recently requested to update the email address for your Survey Buds panelist account.
                                  <br><br>
                                  Please click the link below to verify your e-mail and re-activate your account:
                                  <br><br>
                                  <button type="button"><a href="https://survey-website.com/verify/?email='.$_SESSION['email'].'&hash='.$hash.'">Verify E-mail</a></button>
                                  <br><br>
                              
                                  Once verified, you will be able to log back in using this email address.
                                  <br><br>
                                  Have a great day,
                                  <br><br>
                                  <p class="signature">Survey Buds Team<p>
                            </div>
                        </div>
                    </body>
                  </html>
                  '; // Our message above including the link
                                        
                  $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
                  $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
                  $headers [] = 'MIME-Version: 1.0';
                  
                  mail($to, $subject, $message, implode("\r\n",$headers));
                                        
                //Include footer, end session and exit to prevent form loading after success message printing above
                get_footer();
                session_unset();
                exit();
             }
            //E-mail unchanged
            else
            {
                //'No changes made' error if username also unchanged
                if($name_msg==""){
                    $errormsg = 'No changes made; edit fields and click Update to update account records';
                      print('<div class = "terminal_container">
                                  <div class = "error_row">
                                          <div class = "error_img">
                                              <img src = "https://survey-website.com/wp-content/themes/go-child02/warning.svg">
                                          </div>
                                          <div class = "error_msg">
                                              '. $errormsg .'
                                          </div>
                                  </div>
                            </div>');

                }
                // Just username changed; return success message
                else
                {
                      print('<div class = "container">
                              <div class = "terminal_container">
                                  <div class = "success_row">
                                      <div class = "success_img">
                                          <img src = "https://survey-website.com/wp-content/themes/go-child02/check.svg">
                                      </div>
                                      <div class = "success_msg">
                                            '. $name_msg .'
                                      </div>
                                  </div>
                                    <div class = "return_row">
                                        <div class = "return_img">
                                        </div>
                                        <div class = "return">
                                                  <a href = "/survey-website.com/member-profile/">Click Here</a> to return to your profile page
                                        </div>
                                    </div>
                                </div>
                             </div>');
                    get_footer();
                    exit();
                }
            }
        //Unmatching e-mail field errors
        }
        else
        {
            $errormsg = 'Both email fields must match to update email address record';
            //Also include username change success if applicable
            if($name_msg !== "")
            {
                print  '<div class = "terminal_container">
                            <div class = "success_row">
                                <div class = "success_img">
                                    <img src = "https://survey-website.com/wp-content/themes/go-child02/check.svg">
                                </div>
                                <div class = "success_msg">
                                      '. $name_msg .'
                                </div>
                            </div>
                            <div class = "error_row">
                                <div class = "error_img">
                                    <img src = "https://survey-website.com/wp-content/themes/go-child02/warning.svg">
                                </div>
                                <div class = "error_msg">
                                      '. $errormsg .'
                                </div>
                            </div>
                        </div>';
            }
            //If username unchanged, just give 'emails don't match' error
            else {
                    print  '<div class = "terminal_container">
                                <div class = "error_row">
                                    <div class = "error_img">
                                        <img src = "https://survey-website.com/wp-content/themes/go-child02/warning.svg">
                                    </div>
                                    <div class = "error_msg">
                                          '. $errormsg .'
                                    </div>
                                </div>
                            </div>';
            }
        }
    }
?>
<!-- End PHP Code -->
<!-- Start HTML form, picking up in body -->
                            
        <div class = "form_wrapper">
            <!-- break into PHP, if errormsg = "0", show instruction message -->
            <!-- <? if($errormsg=='0'){ ?> -->
            <p style="margin: 50px 0px 20px 0px;">
                Please edit the fields you would like to change below. When you are done, click the 'Update' button.
            </p>
            <!-- <? } ?> -->
            <form action="" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?=$_SESSION['name']?>" required />
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?=$_SESSION['email']?>" required />
                <label for="confirm-email">Confirm Email:</label>
                <input type="email" name="confirm-email" value="<?=$_SESSION['email']?>" required />
                
                <input type="submit" class="update_button" value="Update" />
            </form>
        </div>
        <div class = "learn_more">
            <a href = "/survey-website.com/about/" target = "_blank"> Learn more about the Survey Buds panel</a><br><br><br>
        </div>
</body>
        <div>
            <?
            get_footer();
            ?>
        </div>
</html>
