<?php get_header(); ?>

<p class="intro"><?php echo get_theme_mod('erdp_initial_page_content_headline_settings'); ?></p>

<?php

    if ( have_posts() ) : while ( have_posts() ) : the_post();
        get_template_part( 'content', get_post_format() );
    endwhile; ?>

    <ul class="pager">
        <?php 
            $next_link = get_next_posts_link( 'Siguiente' );
            $previous_link = get_previous_posts_link( 'Anterior' );
            
            if(!empty($previous_link)) {
                echo "<li class='paginate'>$previous_link</li>";
            }
            
            if(!empty($next_link)) {
                echo "<li class='paginate'>$next_link</li>";
            }
        ?>
    </ul>

    <?php
        endif;
    ?>

<?php get_footer(); ?>