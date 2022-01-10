<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */

//Assign recipient list from phpMyAdmin to variable below//
$recipient_list = array(
                        array('name' => 'Andy','email' => 'myemail@email.com')
                        );

//Enter Survey ID below
$survey_id = '0001';

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

$query = "SELECT title, end_date, incentive FROM surveys WHERE survey_id = '$survey_id'";
$result = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));
$result_array = mysqli_fetch_assoc($result) or die(mysqli_error($mysqli));

$title = $result_array['title'];
$enddate = $result_array['end_date'];
$incentive = $result_array['incentive'];

function sendmail($address,$name,$survey_id,$title,$enddate,$incentive){
    $to = $address;
    $subject = 'New Survey Buds Survey Available';
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
                        <h3><center>New Survey Buds Survey Available</center></h3>
                        <br>
                        Hi '.$name.',
                        <br><br>
                        A new Survey Buds survey titled <span style = "font-style: italic; font-weight: bold;">'.$title.'</span> has just been posted and
                        will be available until <span style="font-weight: bold;">'.$enddate.'</span>.
                        <br><br>
                        Please click the link below to take the survey. Alternatively, the survey link has also been posted to your
                        <a href="https://survey-website.com/test-login/">Survey Buds profile</a> under the
                        <span style="font-weight:bold">Notifications</span> area.
                        <br><br>
                        <button class="button" type="button"><a href="https://survey-website.com/survey-load/?email='.$address.'&survey_id='.$survey_id.'">Take Survey</a></button>
                        <br><br>
                        We are offering <span style="font-weight: bold;">'.$incentive.'</span> Survey Buds Rewards Points in exchange for completing this survey.
                        <br><br>
                        Have a great day,
                        <br><br>
                        <p class="signature">Survey Buds Team<p>
                    </div>
                </div>
            </body>
        </html>';
    
    $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
    $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
    $headers [] = 'MIME-Version: 1.0';
    
    if(mail($to, $subject, $message, implode("\r\n",$headers))){ // Send our email
        return true;
        echo 'Test message sent';
        die;
    }else{
        echo 'Test message failed';
        die;
    }
}

function sendlist($recipient_list,$survey_id,$title,$enddate,$incentive) {
    for($i=0; $i < count($recipient_list);$i++){
        if(!sendmail($recipient_list[$i]['email'],$recipient_list[$i]['name'],$survey_id,$title,$enddate,$incentive)){
            echo 'Survey invite list failed';
            die;
        }else{
            return true;
        }
    }
}

if(isset($_POST['test'])){
    sendmail('support@survey-website.com','John Doe',$survey_id,$title,$enddate,$incentive);
    echo "test message sent";
}
elseif(isset($_POST['sendlist'])){
    sendlist($recipient_list, $survey_id,$title,$enddate,$incentive);
    echo "survey invite sent to panelists";
}

die;

?>
