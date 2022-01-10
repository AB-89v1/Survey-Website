<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB

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
     <style>
         .footer {
             position: fixed;
             bottom: 0;
         }
         div.successmsg {
             font-size: 22px;
             margin: auto;
             text-align: center;
         }
         div.container {
             margin: auto;
             width: 50%;
         }
         @media screen and (max-width: 600px) {
             div.container {
                 width: 100%;
                 margin-left: 10px;
             }
         }
     </style>
     <body>
        <div class="container">
            <p style = "margin-top: 200px; text-align: center">    
                <?php
                if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
                    
                    // Prepare select statement for validation
                    $stmt = mysqli_prepare($mysqli, "SELECT panelist_id, name FROM panelists WHERE email = ? AND hash = ? AND active = '0'");
                    mysqli_stmt_bind_param($stmt,"ss",$email,$hash);
                    
                    //Set parameters and execute
                    $email = $_GET['email'];
                    $hash = $_GET['hash'];
                    mysqli_stmt_execute($stmt);
                    
                    //Store and bind result and get row count
                    mysqli_stmt_store_result($stmt);
                    $result_rows = mysqli_stmt_num_rows($stmt);
                    $stmt->bind_result($panelist_id, $panelist_name);
                    mysqli_stmt_fetch($stmt);
                    
                    if($result_rows > 0){
                    
                        // Valid Account, prepare statement to Update panelists table
                        $stmt = mysqli_prepare($mysqli, "UPDATE panelists SET active = '1' WHERE email = ? AND hash = ? AND active = '0'");
                        mysqli_stmt_bind_param($stmt,"ss", $email, $hash);
                        
                        //Set parameters and execute
                        $email = $_GET['email'];
                        $hash = $_GET['hash'];
                        mysqli_stmt_execute($stmt);
                        
                        //Prepare select statement to check if panelist already exists in incentive table (in the case of an email update on existing account)
                        $stmt = mysqli_prepare($mysqli, "SELECT * FROM incentive WHERE panelist_id = ?");
                        mysqli_stmt_bind_param($stmt, "s", $panelist_id);
                        
                        //Set parameter and execute
                        $panelist_id = $panelist_id;
                        mysqli_stmt_execute($stmt);
                        
                        //Store and bind result and get row count
                        mysqli_stmt_store_result($stmt);
                        $result_rows = mysqli_stmt_num_rows($stmt);
                        
                        if($result_rows > 0){
                            
                            //Panelist already exists - update email
                            $stmt = mysqli_prepare($mysqli, "UPDATE incentive SET email = ? WHERE panelist_ID = ?");
                            mysqli_stmt_bind_param($stmt, "si", $email, $panelist_id);
                            
                            //Set parameters and execute
                            $email = $_GET['email'];
                            $panelist_id = $panelist_id;
                            mysqli_stmt_execute($stmt);
                            
                            echo '<div class = "successmsg">Your new email has been confirmed, you can now login </br><a href="/survey-website.com/test-login/">Click here to login to your profile</a></div>';
                            
                        }else{
                        
                            //New panelist - prepare Insert statement to update incentive table
                            $stmt = mysqli_prepare($mysqli, "INSERT INTO incentive (email, panelist_id) VALUES (?,?)");
                            mysqli_stmt_bind_param($stmt, "si", $email, $panelist_id);
                            
                            //Set parameters and execute
                            $email = $_GET['email'];
                            $panelist_id = $panelist_id;
                            mysqli_stmt_execute($stmt);
                            
                            //Get data for profile survey
                            $stmt = mysqli_prepare($mysqli, "SELECT incentive, survey_id FROM surveys WHERE title = 'Profile Survey'");
                            mysqli_stmt_execute($stmt);
                            
                            mysqli_stmt_store_result($stmt);
                            $stmt->bind_result($incentive, $survey_id);
                            mysqli_stmt_fetch($stmt);
                            
                            //Add record to survey invites
                            $stmt = mysqli_prepare($mysqli, "INSERT INTO survey_invites (survey_id, panelist_id, incentive) VALUES (?,?,?)");
                            mysqli_stmt_bind_param($stmt, "iii", $survey_id, $panelist_id, $incentive);
                            
                            mysqli_stmt_execute($stmt)or die(mysqli_error($mysqli));
                            
                            //Send link for profile survey
                            $to = $email;
                            $subject = 'Survey Buds Profile Survey Invitation';
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
                                                <h3><center>Survey Buds Profile Survey Available</center></h3>
                                                <br>
                                                Hi '.$panelist_name.',
                                                <br><br>
                                                Thank you for signing up to the Survey Buds panel! 
                                                <br><br>
                                                The final step in your onboarding into the panel is to complete the <span style = "font-style: bold;">profile survey</span>. This
                                                will give us a better idea of your cannabis preferences and habits, allowing us to send you more targeted, relevant surveys that
                                                you can complete and be compensated for.
                                                <br><br>
                                                Please click the link below to take the 3-minute profile survey. This link has also been posted to your
                                                <a href="https://survey-website.com/test-login/">Survey Buds profile</a> under the
                                                <span style="font-weight:bold">Notifications</span> area.
                                                <br><br>
                                                <button class="button" type="button"><a href="https://survey-website.com/survey-load/?email='.$email.'&survey_id='.$survey_id.'">Complete Profile Survey</a></button>
                                                <br><br>
                                                We are offering <span style="font-weight: bold;">'.$incentive.'</span> Rewards Points in exchange for responding to this survey and completing your Survey Buds profile.
                                                <br><br>
                                                Have a great day!
                                                <br><br>
                                                <p class="signature">Survey Buds Team<p>
                                            </div>
                                        </div>
                                    </body>
                                </html>';
                            
                            $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
                            $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
                            $headers [] = 'MIME-Version: 1.0';
                            
                            if(!mail($to, $subject, $message, implode("\r\n",$headers))){
                                
                                //Catch error if message does not send
                                echo '<div class="statusmsg">An error has occurred and your profile survey link was not sent. The link can still be found by logging in to your<a href="/survey-website.com/test-login/">profile</a></div>';
                                
                            }else{ 
                            
                                echo '<div class = "successmsg">Your account has been activated, you can now login </br><a href="/survey-website.com/test-login/">Click here to login to your profile</a></div>
                                    <br>
                                    <div class = "successmsg">An email has been sent containing a link to complete your profile survey for 200 rewards points.</div>';
                            }
                        }
                    }else{
                        //Invalid Account
                        echo '<div class="statusmsg">The url is either invalid or you already have activated your account.</div>';
                    }
                }else{
                // Invalid approach
                echo '<div class="statusmsg">Invalid approach, please use the link that has been send to your email.</div>';
                }
                ?>
            </p>
        </div>
        <div style = "margin-top: 305px">
            <?php
                get_footer();
            ?>
        </div>
    </body>
</html>