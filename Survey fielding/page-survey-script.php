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

//Start session and initialize session variables
session_start();

if(isset($_SESSION['valid_response']) && $_SESSION['valid_response'] == FALSE){
    header('location: https://survey-website.com/');
    exit;
}

//Check page's form was submitted
if(isset($_POST['submit']) && !empty($_POST['submit']))
{
    $_SESSION['next_page'] = $_POST['next_page'] + 1;
    
    //Unless coming from the intro page, start by recording answers into DB
    if($_POST['next_page'] != "1")
    {
        //Loop through the ids of questions on the relevant page and insert into DB
        foreach($_POST as $name => $value)
        {
            if($name != "submit" && $name != "next_page")
            {
                if(!is_array($value))
                {
                    $insert_query = "INSERT INTO answers (survey_id, panelist_id, question_id, answer_option_id) VALUES ('{$_SESSION['survey_id']}', '{$_SESSION['panelist_id']}', '$name', '$value')";
                    mysqli_query($mysqli, $insert_query) or die(mysqli_error($mysqli));
                }
                else
                {
                    foreach($value as $value_a)
                    {
                        $insert_query = "INSERT INTO answers (survey_id, panelist_id, question_id, answer_option_id) VALUES ('{$_SESSION['survey_id']}', '{$_SESSION['panelist_id']}', '$name', '$value_a')";
                        
                        mysqli_query($mysqli, $insert_query) or die(mysqli_error($mysqli));
                    }
                }
            }
        }
        
        //Query database for next page's questions and answers
        $q_query = "SELECT * FROM questions WHERE survey_id = '{$_SESSION['survey_id']}' AND survey_page = '{$_POST['next_page']}' ORDER BY sequence ASC";
        $q_result = mysqli_query($mysqli, $q_query) or die(mysqli_error($mysqli));
        $q_rows = mysqli_num_rows($q_result);
        
        if($q_rows > 0)
        {
            unset($_SESSION['questions']);
            
            //Loop through rows of question data
            for($i = 0; $i < $q_rows; $i++)
            {
                
                //Store question row data in session variable
                $_SESSION['questions'][$i] = mysqli_fetch_array($q_result);
                
                //Query database for answer option data related to that ONE question
                $a_query = "SELECT answer_option_id, answer_option_text FROM answer_options WHERE survey_id = '{$_SESSION['survey_id']}' AND question_id = '{$_SESSION['questions'][$i]['question_id']}' ORDER BY sequence ASC";
                $a_result = mysqli_query($mysqli, $a_query) or die(mysqli_error($mysqli));
                $a_rows = mysqli_num_rows($a_result);
                
                //Check there are answer options
                if($a_rows > 0)
                {
                    //Loop through each option represented by $j
                    for($j = 0; $j < $a_rows; $j++)
                    {
                        //Additional key 'answer options' for each question row will contain an array of arrays of answer_option_id and answer_option_text
                        $_SESSION['questions'][$i]['answer_options'][$j] = mysqli_fetch_array($a_result);
                        
                    }
                }
            }
        }
        else
        {
            //Query empty - survey complete - Increment response count
            $response_count_update = "UPDATE surveys SET response_count = response_count +1 WHERE survey_id = '{$_SESSION['survey_id']}'";
            
            mysqli_query($mysqli, $response_count_update) or die(mysqli_error($mysqli));
            
            //Record completion of survey for panelist
            $record_completion = "INSERT INTO survey_completes (survey_id, panelist_id) VALUES ('{$_SESSION['survey_id']}', '{$_SESSION['panelist_id']}')";
            
            mysqli_query($mysqli, $record_completion) or die(mysqli_error($mysqli));
            
            //Allocate incentive
            $incentive_query = "SELECT incentive FROM surveys WHERE survey_id = '{$_SESSION['survey_id']}'";
            
            $incentive_result = mysqli_query($mysqli, $incentive_query) or die(mysqli_error($mysqli));
            
            $incentive = mysqli_fetch_array($incentive_result);
            
            $incentive_update = "UPDATE incentive SET points = points + '$incentive[0]' WHERE panelist_id = '{$_SESSION['panelist_id']}'";
            
            mysqli_query($mysqli, $incentive_update) or die(mysqli_error($mysqli));
            
            $_SESSION['incentive'] = $incentive[0];
            
            //Mail confirmation
            $stmt = "SELECT email, name FROM panelists WHERE panelist_id = ?";
            mysqli_stmt_bind_param($stmt, "i",$panelist_id);
            
            $panelist_id = $_SESSION['panelist_id'];
            mysqli_stmt_execute($stmt);
            
            mysqli_stmt_store_result($stmt);
            $stmt->bind_result($email, $name);
            mysqli_stmt_fetch($stmt);
            
            $to = $email;
            $subject = 'Survey Buds Rewards Points Received';
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
                                <h3><center>Rewards Points Payment Received</center></h3>
                                <br>
                                Hi '.$name.',
                                <br><br>
                                Great news! Your response to a Survey Buds survey was recently recorded. Thank you! 
                                <br><br>
                                Your account has been credited '.$incentive.' Survey Buds Rewards Points.
                                <br><br>
                                Login to your <a href="https://survey-website.com/test-login/">Survey Buds profile</a> to view your points balance and redeem for cash.
                                <br><br>
                                Have a great day!
                                <br><br>
                                <p class="signature">Survey Buds Team<p>
                            </div>
                        </div>
                    </body>
                </html>';
            
            $headers [] = 'From:support@survey-website.com';// . "\r\n"; // Set from headers
            $headers [] = 'Content-type: text/html; charset=iso-8859-1'; // Set Content Type header
            $headers [] = 'MIME-Version: 1.0';
            
            //Send user to final page
            header('location: /survey-website.com/survey-end/');
            exit;
        }
        
        header('location: /survey-website.com/survey-form/');
        exit;
    }
    else
    //This branch if coming from the intro page
    {
       //Load first page of question data
       $q_query = "SELECT * FROM questions WHERE survey_id = '{$_SESSION['survey_id']}' AND survey_page = '{$_POST['next_page']}' ORDER BY sequence ASC";
       $q_result = mysqli_query($mysqli, $q_query) or die(mysqli_error($mysqli));
       $q_rows = mysqli_num_rows($q_result);
        
        //Loop through rows of question data
        for($i = 0; $i < $q_rows; $i++)
        {
            
            //Store question row data in session variable
            $_SESSION['questions'][$i] = mysqli_fetch_array($q_result);
            
            //Query database for answer option data related to that ONE question
            $a_query = "SELECT answer_option_id, answer_option_text FROM answer_options WHERE survey_id = '{$_SESSION['survey_id']}' AND question_id = '{$_SESSION['questions'][$i]['question_id']}' ORDER BY sequence ASC";
            $a_result = mysqli_query($mysqli, $a_query) or die(mysqli_error($mysqli));
            $a_rows = mysqli_num_rows($a_result);
            
            //Check there are answer options
            if($a_rows > 0)
            {
                //Loop through each option represented by $j
                for($j = 0; $j < $a_rows; $j++)
                {
                    //Additional key 'answer options' for each question row will contain an array of arrays of answer_option_id and answer_option_text
                    $_SESSION['questions'][$i]['answer_options'][$j] = mysqli_fetch_array($a_result);
                    
                }
            }
 
        }
       
       //Send to first page
       header('location: /survey-website.com/survey-form/');
       exit;
    }
}
exit;

?>
