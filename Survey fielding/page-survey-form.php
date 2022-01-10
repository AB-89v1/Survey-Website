<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Go
 */
 
session_start();

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
        <script>
        </script>
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
                
                input[type="radio"], input[type=""], input[type="checkbox"] {
                    height: 19px;
                    opacity: 100;
                    width: 20px;
                    display: inline !important;
                    -webkit-appearance:  !important;
                }
                
                input[type="submit"] {
                    margin-top: 50px;
                }
                
                p.question_subtext {
                    font-style: italic;
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
                <form name = "" onsubmit = "" action = "/survey-website.com/survey-script/" method = "post">
                    <input type = "hidden" name = "next_page" value = "<?php echo $_SESSION['next_page'];?>"/>
                    <!-- Loop through each value in the question_index array, i.e. each question to be shown on page -->
                    <?php foreach($_SESSION['questions'] as $question_row) { ?>
                        
                        <p class = "question_text"><?php print($question_row['sequence']); print ". "; print($question_row['question_text']);?></p>
                        <p class = "question_subtext"><?php print($question_row['question_subtext']);?></p>
                        <?php if($question_row['question_type'] == "radio"){
                            foreach($question_row['answer_options'] as $answer_row){ ?>
                            
                            <label for = "<?php echo $question_row['question_id'];?>" >
                                <input type = "radio" id = "" name = "<?php echo $question_row['question_id'];?>"
                                value = "<?php echo $answer_row['answer_option_id'];?>"/>
                                    <?php  echo $answer_row['answer_option_text']?>
                            </label>
                        <?php }
                        
                         }elseif($question_row['question_type'] == "checkbox"){
                             foreach($question_row['answer_options'] as $answer_row){ ?>
                             
                                 <label for = "<?php echo $question_row['question_id'];?>" >
                                     <input type = "checkbox" id = "" name = '<?php echo "{$question_row['question_id']}"; echo '[]';?>'
                                     value = "<?php echo $answer_row['answer_option_id'];?>"/>
                                         <?php  echo $answer_row['answer_option_text']?>
                                 </label>
                            <?php }
                         
                         }elseif($question_row['question_type'] == "select"){ ?>
                            
                            <select name = "<?php echo $question_row['question_id'];?>" id = "" required>
                                <option value = "" selected disabled >Select one:</option>
                            
                        <?php foreach($question_row['answer_options'] as $answer_row){ ?>
                                
                                <option value = "<?php echo $answer_row['answer_option_id'];?>"><?php echo $answer_row['answer_option_text']?></option>
                                    
                        <?php } ?>
                            </select>
                        <?php }elseif($question_row['question_type'] == "open"){ ?>
                                    
                            <input type = "textarea" id = "" name = "<?php echo $question_row['question_id'];?>" value=""/>
                        <?php }
                        }?>
                    
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

