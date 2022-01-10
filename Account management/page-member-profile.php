<?php
    session_start();
    
    //Check user successfuly logged in
    if($_SESSION['logged_in'] != TRUE){
        header('location: https://survey-website.com/test-login/');
        exit;
    }
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Establish connection to DB_NAME database
    $mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB
    
    //Set variable for incentive points
    $stmt = mysqli_prepare($mysqli, "SELECT points FROM incentive WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['email']);
    
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_store_result($stmt);
    $stmt->bind_result($points);
    mysqli_stmt_fetch($stmt);
    
    if($points = 0){
        $points = 0;
    }
    //Check for available notifications
    
    //Open survey invites - Get the ID of any survey this panelist has been invited to and which is still open
    $survey_notifications = array();
    $stmt = mysqli_prepare($mysqli, "SELECT survey_id FROM survey_invites WHERE panelist_id = ? AND survey_id IN (SELECT survey_id FROM surveys WHERE open = 1)");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['panelist_id']);
    
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_store_result($stmt);
    
    $stmt->bind_result($survey_id);
    
    while(mysqli_stmt_fetch($stmt))
    {
        //Get the row from survey_completes for this panelist and that open survey
        $stmt = mysqli_prepare($mysqli, "SELECT * FROM survey_completes WHERE survey_id = ? AND panelist_id = ?");
        
        mysqli_stmt_bind_param($stmt,"ii", $survey_id, $_SESSION['panelist_id']);
        
        mysqli_stmt_store_result($stmt);
        
        $num_rows = mysqli_stmt_num_rows($stmt);
        
        if($num_rows == 0)
        {
            //There existing an open survey the panelist was invited to and hasn't taken - get its title and reward amt
            $stmt = mysqli_prepare($mysqli, "SELECT title, incentive FROM surveys WHERE survey_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $survey_id);
            
            mysqli_stmt_execute($stmt);
            
            mysqli_stmt_store_result($stmt);
            
            $stmt->bind_result($title, $incentive);
            
            mysqli_stmt_fetch($stmt);
            
            $survey_notifications[] = array($title, $incentive, $survey_id);
        }
    }
    
    //Check for recent completes
    $survey_completes = array();
    $stmt = mysqli_prepare($mysqli, "SELECT survey_id FROM survey_completes WHERE panelist_id = ? AND datetime >= ?");
    mysqli_stmt_bind_param($stmt, "is", $_SESSION['panelist_id'],$recent);
    
    $today = strtotime(date("Y-m-d"));
    $recent = strtotime("-3 days", $today);
    
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_store_result($stmt);
    
    $rows = mysqli_stmt_num_rows($stmt);
    
    $stmt->bind_result($survey_id);
    
    while(mysqli_stmt_fetch($stmt))
    {
        $stmt = mysqli_prepare($mysqli, "SELECT title, incentive FROM surveys WHERE survey_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $survey_id);
            
            mysqli_stmt_execute($stmt);
            
            mysqli_stmt_store_result($stmt);
            
            $stmt->bind_result($title, $incentive);
            
            mysqli_stmt_fetch($stmt);
            
            $survey_completes[] = array($title, $incentive);
    }
    
    //Check for recent redemption requests
    $redemption = array();
    $stmt = mysqli_prepare($mysqli, "SELECT amount FROM redeem WHERE panelist_id = ? AND request_date >= ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "id", $_SESSION['panelist_id'], $recent);
    
    $today = strtotime(date("Y-m-d"));
    $recent = strtotime("-7 days", $today);
    
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_store_result($stmt);
    
    $stmt->bind_result($amount);
    
    while(mysqli_stmt_fetch($stmt))
    {
        $redemption[] = $amount;
    }
    
    // Log out user and destroy session if Log Out button is clicked
    if(isset($_POST['logout'])){
        session_destroy();
        unset($_SESSION['email']);
        header('location: /survey-website.com/test-login/');
    }
    //Send user to info update form if Update Info buttion is clicked
    if(isset($_POST['update'])){
        header('location: /survey-website.com/info-update/');
    }
    
get_header();

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
            div.subheader {
                display:flex;
                width: 90%;
                margin: auto;
                justify-content: center;
                align-items: center;
                margin-bottom:50px;
            }
            div.subheader-left {
                flex: 1;
            }
            div.subheader-center {
                flex: 2;
            }
            div.subheader-right {
                flex: 1;
            }
            input.submit_button {
                float: right;
            }
            h5{
                margin-bottom:50px;
            }
            div.container {
                display: flex;
                /*flex-direction: row-reverse;*/
                margin: auto;
                justify-content: center;
                padding-bottom:30px;
            }
            div.column-right{
                flex:1;
            }
            div.column-left{
                flex:3;
                display: flex;
                flex-direction: column;
                margin-right: 75px;
            }
            div.rewards {
                text-align: center;
                border: 2px solid;
                padding: 20px 0px 20px 0px;
                margin-bottom:20px;
            }
            div.notifications {
                border: 2px solid;
                padding: 20px 20px 20px 20px;
                margin-bottom:20px;
            }
            div.notification_row {
                display: flex;
                margin-bottom: 20px;
            }
            div.notification_img {
                display: inline-block;
                flex: 2;
                padding-right: 20px;
                justify-content: right;
            }
            div.notification_img img {
            }
            div.notification_msg {
                display: inline-block;
                flex: 8;
                justify-content: left;
                text-align: left;
            }
            div.notification_msg p {
                padding: 20px 0px;
            }
            h6 {
                margin-top: 10px;
                position: relative;
                text-align: center;
                color: white;
            }
            div.section-header {
                display: flex;
                height: 50px;
            }
            div.section-header-left {
                flex: 1;
                border: 2px solid;
                border-bottom: 0;
                border-radius: 5px 5px 0px 0px;
                padding-left: 2px;
                background-color: #e6ccff;
            }
            div.section-header-right {
                flex: 7;
            }
            div.profile {
                border: 2px solid;
                padding: 30px 0px 30px 0px;
                margin-bottom: 50px;
            }
            table.info {
                width: 75%;
                margin-left: 1%;
                border: 0px;
                border-collapse: collapse;
                border-style: none;
            }
            td.label {
                width: 25%;
            }
            input.update_button {
                margin-left: 2%;
            }
            div.quicklinks {
                margin: auto;
            }
            #links {
                text-align: center;
                font-size:20px;
                font-style: bold;
                border-bottom:1px solid;
                margin-bottom: 10px;
                height: 50px;
            }
            ul.quicklinks {
                width: 90%;
                margin: auto;
                padding-bottom: 10px;
            }
            img {
                height: 50px;
                float: right;
            }
            @media screen and (max-width: 600px) {
                div.container {
                    flex-direction: column;
                }
                div.column-left {
                    width:100%;    
                }
                div.section-header-right {
                    flex: 3;
                }
                table.info {
                    width: 100%;
                }
                td.label {
                    width: 35%;
                }
                div.subheader-left {
                    flex: 0;
                }
                div.subheader-center {
                    flex: 1;
                }
                div.subheader-right {
                    flex: 1;
                }
            }
        </style>
    </head>
    <body>
        <div class="subheader">
            <div class= "subheader-left">
            </div>
            <div class="subheader-center">
                <h5 style="text-align: center;"><?=$_SESSION['name']?>'s Survey Buds Profile</h5>
            </div>
            <div class="subheader-right">
                <form action="" method="post">
                    <input type = "submit" class="submit_button" name="logout" value="Log Out"/>
                </form>
            </div>
        </div>
        <div class="container">
            <div class="column-left">
                
                <?php if(count($survey_notifications,0) > 0 || count($survey_completes,0) > 0 || count($redemption,0) > 0) { ?>
                
                <div class = "section-header">
                    <div class = "section-header-left">
                        <h6>Notifications</h6>
                    </div>
                    <div class = "section-header-right">
                    </div>
                </div>
                <div class = "notifications">
                
                <?php for($i = 0; $i < count($survey_notifications,0); $i++){
                    
                $survey_id = sprintf("000%u",$survey_notifications[$i][2]);
                
                ?>
                    
                    <div class = "notification_row">
                        <div class = "notification_img">
                            <img src = "https://survey-website.com/wp-content/themes/go-child02/survey_notification.svg">    
                        </div>
                        <div class = "notification_msg">
                            <p>A new survey titled <a href = "/survey-website.com/survey-load/?email=<?php print $_SESSION['email'];?>&survey_id=<?php print $survey_id;?>">"<?php echo $survey_notifications[$i][0];?>"</a> is available to take for <?php echo $survey_notifications[$i][1];?> GrowOpps Rewards points</p>
                        </div>
                    </div>
                <?php } 
                for($i = 0; $i < count($survey_completes,0); $i++) { ?>
                
                    <div class = "notification_row">
                        <div class = "notification_img">
                            <img src = "https://survey-website.com/wp-content/themes/go-child02/survey_complete.svg">    
                        </div>
                        <div class = "notification_msg">
                            <p><?php echo $survey_completes[$i][1];?> GrowOpps Reward points were credited to your account for completing the survey titled <?php echo $survey_completes[$i][0]?></p>
                        </div>
                    </div>
                    
                <?php }  
                for($i = 0; $i < count($redemption,0); $i++) { ?>
                
                    <div class = "notification_row">
                        <div class = "notification_img">
                            <img src = "https://survey-website.com/wp-content/themes/go-child02/survey_redeem.svg">    
                        </div>
                        <div class = "notification_msg">
                            <p>Your request to redeem $<?php echo $redemption[$i];?> worth of rewards points has been received and is being processed</p>
                        </div>
                    </div>
                
                <?php } } ?>
                </div>
                <div class="section-header">
                    <div class="section-header-left">
                        <h6>Basic Info</h6>
                    </div>
                    <div class="section-header-right">
                    </div>
                </div>
                <div class="profile">
                    <table class="info">
                        <tr>
                            <td class="label" style="border: none; font-weight: bold;">Username:</td>
                            <td style="border: none;"><?=$_SESSION['name']?></td>
                        </tr>
                        <tr>
                            <td class="label" style="border: none; font-weight: bold;">E-mail:</td>
                            <td style="border: none;"><?=$_SESSION['email']?></td>
                        </tr>
                    </table>
                    <form action="" method="post">
                        <input type = "submit" class = "update_button" name= "update" value= "Update Info"/>
                    </form>
                </div>
                <div class="section-header">
                    <div class="section-header-left">
                        <h6>Rewards</h6>
                    </div>
                    <div class="section-header-right">
                    </div>
                </div>
                <div class="rewards">
                   <div><?=$points?> Survey Buds Rewards Points</div>
                   <br>
                   <!-- <? if($points >= 1000) { ?> -->
                   <p><a href="/survey-website.com/redeem/">Click Here to redeem</a></p>
                   <!-- <? }else{ ?> -->
                   <p>You only need <span style="font-weight: bold;display: inline;"><?=(1000 - $points)?></span> more points until you can redeem!</p>
                   <!-- <? } ?> -->
                </div>
            </div>
            <div class="column-right">
                <div class="quicklinks">
                        <div id="links">Quick Links:</div>
                        <ul class="quicklinks">
                            <li><a href="/survey-website.com/pw-reset-form/">Change password</a></li>
                            <li><a href="">Refer a friend</a></li>
                            <li><a href="/survey-website.com/about-3">Learn more about GrowOpps</a></li>
                            <li><a href="/survey-website.com/contact-2/">Contact support</a></li>
                            <li><a href="/survey-website.com/unsubscribe/">Unsubscribe</a></li>
                        </ul>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
   get_footer();
?>
