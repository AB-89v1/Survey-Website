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

if($intro_obj = mysqli_query($mysqli, "SELECT intro_text, title FROM surveys WHERE survey_id = '{$_SESSION['survey_id']}' AND open = 1"))
{
    $intro_text_array = mysqli_fetch_array($intro_obj) or die(mysqli_error($mysqli));
    $intro_text = $intro_text_array['intro_text'];
    $_SESSION['survey_title'] = $intro_text_array['title'];
}
else
{
    exit;
}

get_header();

// Start the Loop.
while ( have_posts() ) :
    the_post();
    get_template_part( 'partials/content', 'page' );

    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }

endwhile;

//TODO - Survey Introduction/Terms & conditions
?>
<!DOCTYPE html>
<html>
    <head>
        <script>
        </script>
        <title>
            <?php echo $_SESSION['title']; ?> Intro
        </title>
        <style>
                body {
                    background-image: url("https://survey-website.com/wp-content/themes/go-child02/jar.png");
                    background-size: cover;
                }
                
                div.container {
                    margin-top: 150px;
                    margin-bottom: 150px;
                }

                div.form_wrapper {
                    width: 35%;
                    margin: auto;
                    padding: 20px 20px 20px 20px;
                    background-color: #f2f2f2;
                }
                
                input[type="radio"], input[type=""] {
                    height: 19px;
                    opacity: 100;
                    width: 20px;
                    display: inline !important;
                    -webkit-appearance:  !important;
                }
                
                input[type="submit"] {
                    margin-top: 50px;
                }
                
                @media screen and (max-width: 600px) {
                    div.form_wrapper {
                        width: 100%;
                    }
                }
      </style>
    </head>
    <body>
        <div class = "container">
            <div class = "form_wrapper">
                <form name = "intro" onsubmit = "" action = "/survey-website.com/survey-script/" method = "post">
                    <input type = "hidden" name = "next_page" value = "1"/>
                    <p><?php print $intro_text; ?></p>
                    
                    
                    
                    <input type = "submit" class = "submit" name = "submit" value = "Next Page"/>
                </form>
            </div>
        </div>
    </body>
    <div>
        <?
            get_footer();
        ?>
    </div>
</html>
