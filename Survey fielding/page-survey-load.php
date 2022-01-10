<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

session_start();

if(isset($_SESSION['valid_response']) && $_SESSION['valid_response'] == FALSE){

    header('location: https://survey-website.com/');
    exit;
}

// Check GET variables
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['survey_id']) && !empty($_GET['survey_id']))
{
    $email = mysqli_escape_string($mysqli,$_GET['email']);
    $_SESSION['email'] = $email;
    $survey = mysqli_escape_string($mysqli,$_GET['survey_id']);
}
else
{
    echo 'Invalid approach';
    $_SESSION['valid_response'] = FALSE;
    die;
    
}

//Verify GET variables with database to retrieve panelist ID
if($panelist_id = mysqli_query($mysqli,"SELECT panelist_id FROM panelists WHERE email = '$email' AND active = 1"))
{
    $panelist_obj = mysqli_fetch_object($panelist_id);
    
    $_SESSION['panelist_id'] = $panelist_obj->panelist_id;
    
}
else
{
    echo "panelist ID error\n";
    $_SESSION['valid_response'] = FALSE;
    die;
}

//Retrieve survey ID from DB if open
if($survey_id = mysqli_query($mysqli,"SELECT survey_id FROM surveys WHERE survey_id = '$survey' AND open = 1"))
{
    $survey_obj = mysqli_fetch_object($survey_id);
    
    $_SESSION['survey_id'] = $survey_obj->survey_id;
}
else
{
    echo "survey_id error \n";
    $_SESSION['valid_response'] = FALSE;
    die;
}

//Check user has not taken survey yet
$stmt = mysqli_prepare($mysqli, "SELECT * FROM survey_completes WHERE panelist_id = ? AND survey_id = ?");
mysqli_stmt_bind_param($stmt,"ii",$panelist_id, $survey_id);

$panelist_id = $_SESSION['panelist_id'];
$survey_id = $_SESSION['survey_id'];
mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);
$result_rows = mysqli_stmt_num_rows($stmt);

if($result_rows > 0){
    
    echo "Your responses to this survey were previously recorded";
    $_SESSION['valid_response'] = FALSE;
    die;
}

header('location: /survey-website.com/survey-intro/');

?>
