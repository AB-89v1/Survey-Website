<?php
    // start session for users coming from 'unsubscribe' link in profile
    session_start();

    //Check user successfuly logged in
    if($_SESSION['logged_in'] != TRUE){
        header('location: survey-website.com/test-login/');
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
        
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB
    $msg = "";
    
    //Check if user is coming from link in email
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
        
        //Prepare statement and bind params
        $stmt = mysqli_prepare($mysqli, "SELECT panelist_id FROM panelists WHERE email = ? AND hash = ? AND active='1'");
        mysqli_stmt_bind_param($stmt, "ss", $email, $hash);
        
        //Set parameters and execute
        $email = $_GET['email'];
        $hash = $_GET['hash'];
        mysqli_stmt_execute($stmt);
        
        //Store result and get row count
        mysqli_stmt_store_result($stmt);
        $result_rows = mysqli_stmt_num_rows($stmt);
        
        //Bind results
        $stmt->bind_result($panelist_id);
        mysqli_stmt_fetch($stmt);

        if($result_rows > 0){
        //User validated, check form
            if(isset($_POST['unsub'])){

                mysqli_query($mysqli, "DELETE FROM panelists WHERE panelist_id = '$panelist_id'");
                mysqli_query($mysqli, "DELETE FROM incentive WHERE panelist_id = '$panelist_id'");
                $msg = 'You have been unsubscribed from Survey Buds.';
                session_destroy();
            }
        }
    }elseif($_SESSION['logged_in'] == TRUE){
        
            //Prepare statement and bind params
            $stmt = mysqli_prepare($mysqli, "SELECT panelist_id FROM panelists WHERE email = ? AND hash = ? AND active='1'");
            mysqli_stmt_bind_param($stmt, "ss", $email, $hash);
            
            //Set parameters and execute
            $email = $_SESSION['email'];
            $hash = $_SESSION['hash'];
            mysqli_stmt_execute($stmt);
            
            //Store result and get row count
            mysqli_stmt_store_result($stmt);
            $result_rows = mysqli_stmt_num_rows($stmt);
            
            //Bind results
            $stmt->bind_result($panelist_id);
            mysqli_stmt_fetch($stmt);
            
            if($result_rows > 0){
            //User validated, check form
                if(isset($_POST['unsub'])){

                    mysqli_query($mysqli, "DELETE FROM panelists WHERE panelist_id = '$panelist_id'");
                    mysqli_query($mysqli, "DELETE FROM incentive WHERE panelist_id = '$panelist_id'");
                    $msg = 'You have been unsubscribed from Survey Buds.';
                    session_destroy();
                    
                }
            }
    }else{
        
        $msg = 'Invalid approach. Please <a href = "survey-website.com/test-login/">login</a> to your Survey Buds profile to perform this action.';
        session_destroy();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            
            div.container {
                width: 35%;
                margin: auto;
                margin-top: 20px;
                margin-bottom: 25px;
                min-height: 500px;
            }
            form.unsub_form {
                padding: 10px 0px 30px 0px;
            }
            div.error {
                margin: auto;
                text-align: center;
            }
            h3.title {
                text-align:center;
            }
            p.inst {
                text-align: center;
            }
            
        </style>
    </head>
        <body>
            <div class="container">
                <h3 class="title">User Unsubscribe</h3>
                    <? if ($msg == "") { ?>
                            <p> By clicking 'Unsubscribe' below, your Survey Buds account will be deactivated and you will no longer receive survey invitations or other communications from us.</br></br>
                            Any accumulated, unredeemed Survey Buds points will be forfeited upon unsubcribing.</p>
                    <form class = "unsub_form" action="" method="post">
                        <label for="unsub">Please unsubscribe me (<?=$_SESSION['email']?>) from the Survey Buds panel</label>
    
                        <input type="submit" class="unsub" name = "unsub" value="Unsubscribe" />
                    </form>
                    <? } else {
                        echo('<div class = "error">'.$msg.'</div>');
                    } ?>
            </div>
        </body>
</html>
<?
    get_footer();
?>