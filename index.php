<?php get_header(); ?>

<?php 
    if(is_front_page()) {
        echo '<p class="intro">' . get_theme_mod('erdp_initial_page_content_headline_settings') . '</p>';
    }
?>

<?php

    if ( have_posts() ) : while ( have_posts() ) : the_post();
        get_template_part( 'content', get_post_format() );
    endwhile; ?>

    <?php get_template_part( 'template-parts/pager', get_post_format() ); ?>

    <?php
        endif;
    ?>

<?php get_footer(); ?>