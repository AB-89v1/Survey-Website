<?php

session_start();

//Check user successfuly logged in
if($_SESSION['logged_in'] != TRUE){
    header('location: /survey-website.com/test-login/');
    exit;
}

$cash = number_format($_SESSION['points']/100,2);

$_SESSION['cash'] = $cash;


/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */

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

?>

<!DOCTYPE html>
    <head>
        <style>
            div.container {
                margin: auto;
                text-align: center;
                margin-top: 100px;
                margin-bottom: 100px;
            }
        </style>
    </head>
    <body>
        <div class = "container">
            <p>You have <?=$_SESSION['points']?> GrowOpps Rewards Points available to redeem</p>
            <p>Would you like to redeem your Rewards Points for $<?=$cash?>?</p>
            <form action="/survey-website.com/redeem-script/" method="post">
                <input type = submit value = "Redeem"/>
            </form>
            <p>By clicking "Yes", your available rewards points will reset to 0 and you will receive a PayPal link via e-mail containing your payment. </p>
        </div>
    </body>
</html>
<?php
    get_footer();
?>

