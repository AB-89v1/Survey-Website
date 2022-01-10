<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//Connect to database
$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error());

//Check connection
if(!$mysqli)
{
    echo 'Unable to connect to server';
    exit;
}

if(!mysqli_select_db($mysqli, 'DB_NAME'))
{
    echo 'Unable to select database';
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{

    if(isset($_POST['login_form']) && !empty($_POST['login_form']))
    {
        $email = mysqli_escape_string($mysqli, $_POST['email']);
        $password = mysqli_escape_string($mysqli, $_POST['password']);
        
        $stmt = mysqli_prepare($mysqli, "SELECT * FROM administrators WHERE email = ? AND active = 1");
        mysqli_stmt_bind_param($stmt, 's', $email);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_store_result($stmt);
        
        $result_rows = mysqli_stmt_num_rows($stmt);
        
        $stmt->bind_result($admin_id, $email, $hash, $active);
        
        mysqli_stmt_fetch($stmt);
        
        if($result_rows == 1)
        {
            if(password_verify($password, $hash))
            {
                $_SESSION['admin'] = TRUE;
                header('location: https://survey-website.com/admin-area/');
                die;
            }
            else
            {
                $_SESSION['login'] = "failed";
                header('location: https://survey-website.com/admin-login/');
                die;
            }
        }
        else
        {
            $_SESSION['login'] = "failed";
            header('location: https://survey-website.com/admin-login/');
            die;
        }
        
    }
    
    if(isset($_POST['register_form']) && !empty($_POST['register_form']))
    {
        //Escape e-mail
        $email = mysqli_escape_string($mysqli,$_POST['email']);
        
        //Escape pw
        if($_POST['password'] == $_POST['password'])
        {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $password_hash = mysqli_escape_string($mysqli, $hash);
        }
        else
        {
            echo "Password error; navigate back and try again";
            
            die;
        }
        
        //Enter info into administrator table, still inactive
        $register_query = "INSERT INTO administrators (email,hash) VALUES ('$email', '".$password_hash."')";
        mysqli_query($mysqli, $register_query) or die(mysqli_error($mysqli));
        
        //Prepare e-mail to webmaster
        $to = "support@survey-website.com";
        $subject = "Request for survey admin account creation";
        $message = '
                    <html>
                    <body>
                    User with e-mail "'.$email.'" is requesting survey administrator permissions
                    <br>
                    <a href = "https://survey-website.com/admin-login-script/?email='.$email.'&hash='.$password_hash.'&request=approved">Click here to approve user request</a>
                    <br>
                    <a href = "https://survey-website.com/test/admin-login-script/?email='.$email.'&hash='.$password_hash.'&request=denied">Click here to deny</a>
                    </body>
                    </html>';
        $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
        $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
        $headers [] = 'MIME-Version: 1.0';
        mail($to, $subject, $message, implode("\r\n",$headers));
        
        $_SESSION['request'] = "sent";
        
        header('location: https://survey-website.com/admin-login/');
        exit;    
                    
    }
}
elseif($_SERVER['REQUEST_METHOD'] === 'GET')
{
    if($_GET['request'] == 'approved')
    {
        //Set admin user to active in DB
        $email = mysqli_escape_string($mysqli, $_GET['email']);
        $hash = mysqli_escape_string($mysqli, $_GET['hash']);
        
        $query = "UPDATE administrators SET active = 1 WHERE email = '$email' AND hash = '$hash'";
        
        mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
        
        //Send e-mail to new admin
        $to = $email;
        $subject = "Survey Buds admin account approved";
        $message = '
                    <html>
                    <body>
                    Your request for Survey Buds survey administrator permissions has been approved.
                    <br>
                    You may now <a href = "https://survey-website.com/admin-login/">log in to the administrator portal </a>.
                    </body>
                    </html>
                ';
        $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
        $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
        $headers [] = 'MIME-Version: 1.0';
        mail($to, $subject, $message, implode("\r\n",$headers));
        
        echo "Admin approval notification sent to user";
        
        die;
    }
    elseif($_GET['request'] == 'denied')
    {
        //Set admin user to active in DB
        $email = mysqli_escape_string($mysqli, $_GET['email']);
        $hash = mysqli_escape_string($mysqli, $_GET['hash']);
        
        $query = "DELETE FROM administrators WHERE email = '.$email.' AND hash = '.$hash.'";
        
        mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
        
        //Send e-mail to new admin
        $to = $email;
        $subject = "Survey Buds admin account denied";
        $message = '
                    <html>
                    <body>
                    Your request for Survey Buds survey administrator permissions has been denied.
                    </body>
                    </html>
                ';
        $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
        $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
        $headers [] = 'MIME-Version: 1.0';
        mail($to, $subject, $message, implode("\r\n",$headers));
        
        echo "Admin denial notification sent to user";
        
        die;   
    }
}

