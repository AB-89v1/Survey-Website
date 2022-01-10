<?
session_start();

$education;
$employment;

if($_SESSION['signup_id'] == null)
{
    exit();
}
else
{
    $signup_id = $_SESSION['signup_id'];
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

$education = $_POST['education'];
$employment = $_POST['employment'];

$query = "UPDATE signup SET employment = '$employment', education = '$education' WHERE signup_id = '$signup_id'";

if(!mysqli_query($mysqli, $query))
{
   print mysqli_error($mysqli);
   exit;
   
}
else
{
    header('location: /sign-up-4/');
}

?>
