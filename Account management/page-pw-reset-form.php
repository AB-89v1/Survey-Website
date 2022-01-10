<?php
    // start session for users coming from 'change pw link' in profile
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
        
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB
    $errormsg = "";
    $msg = "";
    
    //Check if user is coming from email verification link
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
        
        //Prepare Select statement
        $stmt = mysqli_prepare($mysqli,"SELECT * FROM panelists WHERE email = ? AND hash = ? AND active ='1'");
        mysqli_stmt_bind_param($stmt,"ss",$email,$hash);
        
        //Set parameters and execute
        $email = $_GET['email'];
        $hash = $_GET['hash'];
        mysqli_stmt_execute($stmt);
        
        //Get row count from result
        mysqli_stmt_store_result($stmt);
        $result_rows = mysqli_stmt_num_rows($stmt);

        if($result_rows == 1){
        
            //User validated, check form
            if(isset($_POST['password']) AND isset($_POST['password1'])){
                
                if($_POST['password'] == $_POST['password1']){
                    
                    //Passwords match, prepare statement
                    $stmt = mysqli_prepare($mysqli,"UPDATE panelists SET hash = ? WHERE email = ? AND hash = ?");
                    mysqli_stmt_bind_param($stmt,"sss",$oldhash, $email, $newhash);
                    
                    //Set parameters & execute
                    $oldhash = $_GET['hash'];
                    $emai = $_GET['email'];
                    $newhash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    mysqli_stmt_execute($stmt);
                    
                    $msg = 'Password has been updated.<br>Please return to <a href="/survey-website.com/test-login/">login page</a>';

                    echo ('<div>
                            <br><br><br><br><center>
                            '.$msg.'
                            </center?<br><br><br><br><br>
                        </div>');
                    
                    get_footer();
                    exit();
                
                }else{
                    
                    $errormsg = 'Passwords must match, please try again';
                    
                }
            }
        }else{
            
            //GET error - display error message and kill session
            $errormsg = 'Invalid approach. Please use the password reset link that has been sent to your e-mail';
            echo('<div class = "error">'.$errormsg.'</div>');
            session_destroy();
            get_footer;
            exit();
            
        }
    }else{
        
          //User not from e-mail link, check if session variable is set
          if($_SESSION['logged_in'] == TRUE){
              
              //User validated, check form
              if(isset($_POST['password']) AND isset($_POST['password1'])){
                  
                  if($_POST['password'] == $_POST['password1']){
                      
                      //Passwords match, prepare Update statement
                      $stmt = mysqli_prepare($mysqli,"UPDATE panelists SET hash = ? WHERE email = ?");
                      mysqli_stmt_bind_param($stmt,"ss", $hash, $email);
                      
                      //Set parameters and execute
                      $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                      $email = $_SESSION['email'];
                      mysqli_stmt_execute($stmt);
                      
                      $msg = 'Password has been updated.<br>Please return to <a href="/survey-website.com/test-login/">login page</a>';
    
                      echo('<div>
                                <br><br><br><br><center>
                                '.$msg.'
                                </center><br><br><br><br><br>
                            </div>');
                      session_destroy();
                      get_footer();
                      exit();
              }else{
                  
              $errormsg = 'Passwords must match, please try again';
              
              }
           }
    }else{
        
        //User not logged in, kill session and redirect
        session_destroy();
        header('location: /survey-website.com/test-login/');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            
            div.container {
                width: 35%;
                margin: auto;
            }
            form.pw_form {
                padding: 10px 0px 30px 0px;
            }
            div.error {
                margin: auto;
                text-align: center;
                font-color: red;
            }
            h3.title {
                text-align:center;
            }
            p.inst {
                text-align: center;
            }
            @media screen and (max-width: 600px) {
                width: 90%;
            }
        </style>
    </head>
        <body>
            <div class="container">
                <h3 class="title">Password Reset</h3>
                        <? if($errormsg==""){ ?>
                            <p> Please enter and confirm a new password:</p>
                        <? }
                            else
                            {
                            echo('<div class = "error">'.$errormsg.'</div>');
                            }
                        ?>
                    <form class = "pw_form" action="" method="post">
                        <label for="password">Password (min. 8 characters):</label>
                        <input type="password" name="password" value="" minlength=8 required />
                        <label for="password2">Confirm Password:</label>
                        <input type="password" name="password1" value="" minlength=8 required />
    
                        <input type="submit" class="submit_button" value="Update Password" />
                    </form>
            </div>
        </body>
<?php
    get_footer();
?>
</html>
