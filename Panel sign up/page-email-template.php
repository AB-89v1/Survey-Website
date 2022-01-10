<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */

function sendmail(){
    $to = 'myemail@email.com';
    $subject = 'E-mail template test';
    $message = '
        <html>
            <head>
                <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
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
                button{
                      background-color: #3c896d;
                      text-align: center;
                      padding: 14px 40px;
                      border: none;
                      font-size: 20px;
                      margin: 4px 2px;
                      border-radius: 10px
                      }
                a{
                      text-decoration: none;
                      color: white;
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
                        <h3><center>Account Verification for Survey Buds survey panel</center></h3>
                        <br>
                        Hi '.$name.',
                        <br><br>
                        Thanks for signing up to the Survey Buds panel!
                        <br><br>
                        Please click the link below to verify your e-mail and activate your account:
                        <br><br>
                        <button type="button"><a href="https://survey-website.com/verify/?email='.$email.'&hash='.$hash.'">Verify E-mail</a></button>
                        <br><br>
                    
                        Once verified, you will receive a link to complete your profile, and then you will be ready
                        to start earning rewards for answering surveys!
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
    mail($to, $subject, $message, implode("\r\n",$headers)); // Send our email
    
    return true;
}

?>
<html>
    <head>
        <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
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
        button{
              background-color: #3c896d;
              text-align: center;
              padding: 14px 40px;
              border: none;
              font-size: 20px;
              margin: 4px 2px;
              border-radius: 10px
              }
        a{
              text-decoration: none;
              color: white;
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
                <h3><center>Account Verification for Survey Buds survey panel</center></h3>
                <br>
                Hi '.$name.',
                <br><br>
                Thanks for signing up to the Survey Buds panel!
                <br><br>
                Please click the link below to verify your e-mail and activate your account:
                <br><br>
                <button type="button"><a href="https://survey-website.com/verify/?email='.$email.'&hash='.$hash.'">Verify E-mail</a></button>
                <br><br>
            
                Once verified, you will receive a link to complete your profile, and then you will be ready
                to start earning rewards for answering surveys!
                <br><br>
                Have a great day,
                <br><br>
                <p class="signature">Survey Buds<p>
            </div>
        </div>
    </body>
    <form onsubmit = "return sendmail()">
        <input type = "submit" value = "send message"/>
    </form>
</html>
<?php
    print(sendmail());
?>





