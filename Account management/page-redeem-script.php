<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//Check user successfuly logged in
if($_SESSION['logged_in'] != TRUE){
    header('location: /survey-website.com/test-login/');
    exit;
}

// Establish connection to DB_NAME database
$mysqli = mysqli_connect("localhost", "root", "pw", "DB_NAME") or die(mysqli_error()); // Connect to database server(localhost) with username and password, and select DB_NAME DB

// Define query strings
$incentive_update = "UPDATE incentive SET points = 0 WHERE email = '{$_SESSION['email']}'";

$redeem_update = "INSERT INTO redeem (email,amount,panelist_id) VALUES ('{$_SESSION['email']}', '{$_SESSION['cash']}','{$_SESSION['panelist_id']}')";

//Run SQL queries
mysqli_query($mysqli, $incentive_update) or die(mysqli_error($mysqli));

mysqli_query($mysqli, $redeem_update); //or die(mysqli_error($mysqli));

//Get redemption request ID
$_SESSION['redemption_id'] = mysqli_insert_id($mysqli);

//Define variables for confirmation email

$to = $_SESSION['email'];
$name = $_SESSION['name'];
$subject = "Survey Buds Rewards Points - Confirmation of Redemption Request";
$msg = '
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
                        <h3><center>Survey Buds Rewards Points Redemption</center></h3>
                        <br>
                        Hi '.$name.',
                        <br><br>
                        This message is to confirm that we have received and are processing your request to redeem
                        <span style="font-weight:bolder">'.$_SESSION['points'].'</span> Survey Buds Reward Points for <span style="font-weight:bolder">$'.$_SESSION['cash'].'</span>.
                        <br><br>
                        These funds will be delivered to your e-mail via a Paypal payment link in 1-2 business days.
                        <br><br>
                        To follow up on the status of your payment, please contact Survey Buds support and reference the
                        transaction ID <span style="font-weight: bolder">'.$_SESSION['redemption_id'].'</span>.
                        <br><br>
                        Thank you for your participation in Survey Buds!
                        <br>
                        Have a great day,
                        <br>
                        <p class="signature">Survey Buds Team<p>
                    </div>
                </div>
            </body>
        </html>';
$headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
$headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
$headers [] = 'MIME-Version: 1.0';

mail($to, $subject, $msg, implode("\r\n",$headers));

header('location: /survey-website.com/confirm-redeem/');

?>