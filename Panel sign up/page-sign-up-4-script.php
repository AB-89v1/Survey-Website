<?
session_start();

if($_SESSION['signup_id'] == null)
{
    exit();
}
else
{
    $signup_id = $_SESSION['signup_id'];
}

//Define function to add user to disqualified list
function disqualify($ip,$ip2,$signup_id,$panelist_id,$email,$db,$code){
    
    $insert_disqualified = "INSERT INTO disqualified (ip,ip2,signup_id,panelist_id,email,disqual_code) VALUES ('$ip', '$ip2','$signup_id','$panelist_id','$email','$code')";
    
    mysqli_query($db,$insert_disqualified) or die(mysqli_error($db));
    
    return true;
}

$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error());

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

if (strcasecmp($_SESSION['capital'], $_POST['capital']) != 0)
{
    disqualify($_SESSION['ip'], $_SESSION['ip2'], $_SESSION['signup_id'],"","",$mysqli,5);
    
    header('location: /survey-website.com/ineligible/');
    
    session_unset();
    
    exit;
}
else if ($_POST['industry'] == "Yes")
{
    disqualify($_SESSION['ip'], $_SESSION['ip2'], $_SESSION['signup_id'],"","",$mysqli,4);
    
    header('location: /survey-website.com/ineligible/');
    
    session_unset();
    
    exit;
}

mysqli_query($mysqli, "UPDATE signup SET qualified = '1' WHERE signup_id = '{$_SESSION['signup_id']}'") or die(mysqli_error($mysqli));

header('location: /test-sign-up-2/'); 


?>
