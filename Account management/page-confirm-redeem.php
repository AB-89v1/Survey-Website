<?php

session_start();

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
                padding:100px 0px 100px 0px;
            }
        </style>
    </head>
    <body>
        <div class = "container">
            <p>Your request to redeem has been recorded. Please allow 1-2 days for receipt of your funds.</p>
            <p>If you do not receive an e-mail from Paypal containing your payment in 2 days,<br>please reach out to support@survey-website.com with the following reference ID:</p>
            <p style = "font-weight:bold"><?=$_SESSION['redemption_id']?></p>
            <p><a href = "https://survey-website.com/member-profile/">Click Here</a> to return to profile</p>
        </div>
    </body>
</html>
<?php
    get_footer();
?>