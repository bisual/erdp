<?php 
/* Template Name: La Línea Template */

get_header(); 

require_once("functions/la_linea_functions.php");

?>


<?php
    if ( have_posts() ) : while ( have_posts() ) : the_post();
?>

<div class="blog-post single la-linea">
    
	<h1 class="post-title"><?php the_title(); ?></h1>

    <?php get_template_part("template-parts/content", "post"); ?>

    <div id="story"></div>

</div><!-- /.blog-post -->

<?php        
    endwhile; endif;

    get_footer();
?>
