<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */
 
 session_start();
 
 if (!isset($_SESSION['signup_id']))
 {
     exit;
 }
 
 if ($_SESSION['ineligible'] == true)
 {
     exit;
 }
 
$blah = "signup_id";

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
<html>
    <head>
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
            
            input[type="radio"], input[type="checkbox"] {
                height: 19px;
                opacity: 100;
                width: 20px;
                display: inline !important;
                -webkit-appearance: checkbox !important;
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
        <?php print($_SESSION['test'][0][$blah]); ?>
        <div class = "container">
            <div class = "form_wrapper">
                <p>Among the following items, which have you purchased in the past 3 months? Select all that apply:</p>
                <form action = "/sign-up-2-script/" method = "post">
                    <label for = "otc">
                        <input type = "checkbox" id = "otc" name = "otc" value = "OTC Pain Relief"/> OTC (over-the-counter) pain relief (i.e. Aspirin, Ibuprofen)</label>
                    <label for = "vitamins">
                        <input type = "checkbox" id = "vitamins" name = "vitamins" value = "Vitamins"/> Vitamins/Multivitamins</label>
                    <label for = "antacids">    
                        <input type = "checkbox" id = "antacids" name = "antacids" value = "Antacids"/> Antacids/indigestion relief</label>
                    <label for = "thc">    
                        <input type = "checkbox" id = "thc" name = "thc" value = "THC"/> THC-containing cannabis products</label>
                    <label for = "protein">    
                        <input type = "checkbox" id = "protein" name = "protein" value = "protein"/> Protein supplements (i.e. whey protein powder)</label>
                    <label for = "topical_pain">    
                        <input type = "checkbox" id = "topical_pain " name = "topical_pain" value = "Topical pain relief"/> Topical pain relief (i.e. Icy Hot, lidocain)</label>
                    <label for = "collagen">
                        <input type = "checkbox" id = "collagen" name = "collagen" value = "Collagen"/> Collagen supplements</label>
                    <label for = "cbd">
                        <input type = "checkbox" id = "cbd" name = "cbd" value = "CBD"/> CBD-containing, THC-free cannabis products</label>

                    <input type = "submit" class = "submit" value = "Next Page"/>
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

