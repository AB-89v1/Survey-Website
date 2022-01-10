<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Start session and initialize session variables
session_start();

$_SESSION['signup_id'];

$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['ip2'] = $_SERVER['HTTP_X_FORWARDED_FOR'];

//Define function to check IP
function ip_check($ip,$ip2,$table,$db) {
    if($ip2 == null){
        $ip_query = "SELECT * FROM $table WHERE ip = '$ip' LIMIT 1";
        
        $ip_result = mysqli_query($db,$ip_query) or die(mysqli_error($db));
        
        $result_array = mysqli_fetch_array($ip_result);
        
        $match = mysqli_num_rows($ip_result);
        
        if($match == 1){
            
            if($result_array['admin'] == 0){
                
                return $result_array;
            }
        }
            
    }
    else{
        
        $ip_query = "SELECT * FROM $table WHERE ip2 = '$ip2' LIMIT 1";
        
        $ip_result = mysqli_query($db,$ip_query) or die(mysqli_error($db));
        
        $result_array = mysqli_fetch_array($ip_result);
        
        $match = mysqli_num_rows($ip_result);
        
        if($match == 1){
            
            if($result_array['admin'] == 0){
                
                return $result_array;
            }
        }
        else{
            
            $ip_query = "SELECT * FROM $table WHERE ip = '$ip' LIMIT 1";
            
            $ip_result = mysqli_query($db,$ip_query) or die(mysqli_error($db));
            
            $result_array = mysqli_fetch_array($ip_result);
            
            $match = mysqli_num_rows($ip_result);
            
            if($match == 1){
                
                if($result_array['admin'] == 0){
                    
                    return $result_array;
                }
            }
        }
    }
}


//Define function to add user to disqualified list
function disqualify($ip,$ip2,$signup_id,$panelist_id,$email,$db,$code){
    
    $insert_disqualified = "INSERT INTO disqualified (ip,ip2,signup_id,panelist_id,email,disqual_code) VALUES ('$ip', '$ip2','$signup_id','$panelist_id','$email','$code')";
    
    mysqli_query($db,$insert_disqualified) or die(mysqli_error($db));
    
    return true;
}

// Define function to remove user from panelist and incentive table
function remove_panelist($panelist_id,$db){
    
    $remove_panelists = "DELETE FROM panelists WHERE panelist_id = '$panelist_id'";
    
    mysqli_query($db,$remove_panelists) or die(mysqli_error($db));
    
    $remove_incentive = "DELETE FROM incentive WHERE panelist_id = '$panelist_id'";
    
    mysqli_query($db,$remove_incentive) or die(mysqli_error($db));
    
    return true;
}


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

//Localize post variables
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$zip = $_POST['zip'];

//Create row for user in signup table
$query = "INSERT INTO signup (dob, gender, zip) VALUES ('$dob', '$gender', '$zip')";

if(!mysqli_query($mysqli, $query))
{
   echo 'Unable to record responses. Please try again later';
   exit;
   
}
else
{
   $_SESSION['signup_id'] = mysqli_insert_id($mysqli);
}

//Check against IP that this is a new unique visitor
if($row = ip_check($_SESSION['ip'], $_SESSION['ip2'], "panelists", $mysqli)){
    
    //Attempted duplicate signup by existing panelist IP - disqualify from participation
    disqualify($row['ip'], $row['ip2'], $_SESSION['signup_id'],$row['panelist_id'],$row['email'], $mysqli, 1);
    
    remove_panelist($row['panelist_id'],$mysqli);
    
    header('location: /survey-website.com/ineligible');
    
    session_unset();
    
    exit;
}
else if($row = ip_check($_SESSION['ip'], $_SESSION['ip2'], "disqualified", $mysqli)){
    
    //Previously ineligible panelist attempting second signup - disqualify
    mysqli_query($mysqli,"UPDATE disqualified SET last_attempt = NOW() WHERE disqualified_id = '{$row['disqualified_id']}'");
    
    header('location: /survey-website.com/ineligible');
    
    session_unset();
    
    exit;
}

//Generate date strings for age validation
$today = strtotime(date("Y-m-d"));
$eighteen = strtotime("-18 years", $today);
$eighteen = date("Y-m-d", $eighteen);

//Check that user is at least 18
if ( $eighteen < $dob)
{
    disqualify($_SESSION['ip'],$_SESSION['ip2'], $_SESSION['signup_id'],'','', $mysqli, 2);
    
    header('location: /survey-website.com/ineligible');
    
    session_unset();
    
    exit;
}


//Fetch state capital of user zip code
$c_query = "SELECT capital FROM `zip code validation` WHERE zip = '$zip' ";
$c_result = mysqli_query($mysqli, $c_query);


if (mysqli_num_rows($c_result) < 1)
{
    //Zip is invalid - user will be disqualified when entering state capital on signup page 4
    $_SESSION['capital'] = "disqualified";

}
else
{
    //Valid zip = store state capital as session variable
    $string = mysqli_fetch_array($c_result);
    $_SESSION['capital'] = $string['capital'];
    
}

$_SESSION['zip'] = $zip;

header('location: /sign-up-2/');

?>