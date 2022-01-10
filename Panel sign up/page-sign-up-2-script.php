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

$thc;
$cbd;

if(isset($_POST['thc']))
{
    $thc = 1;
}
else
{
    $thc = 0;
}

if(isset($_POST['cbd']))
{
    $cbd = 1;
}
else
{
    $cbd = 0;
}

$query = "UPDATE signup SET thc=$thc, cbd=$cbd WHERE signup_id=$signup_id";

if(!mysqli_query($mysqli, $query))
{
   echo 'Unable to record responses. Please try again later';
   exit;
   
}
else
{
    echo 'success';
}

if(isset($_POST['thc']) || isset($_POST['cbd']))
{
   header('location: /survey-website.com/sign-up-3/'); 
}
else
{
   disqualify($_SESSION['ip'], $_SESSION['ip2'],$_SESSION['signup_id'],"","",$mysqli, 3);
   header('location: /survey-website.com/ineligible/'); 
}

?>