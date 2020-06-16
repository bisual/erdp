<?php 
/* Template Name: La Línea Template */

get_header(); 

?>

<?php
    if ( have_posts() ) : while ( have_posts() ) : the_post();
    get_template_part( "template-parts/content", "page" );
    endwhile; endif;
?>

<div id="story">
    <?php
        $loop = new WP_Query( array( 'post_type' => ['post','books','revision'], 'paged' => 1, "orderby" => "post_date", "order" => "DESC", "posts_per_page" => 10 ) );
        if ( $loop->have_posts() ) :
            while ( $loop->have_posts() ) : $loop->the_post(); ?>
                <p><?php echo get_the_title() . ' - ' . get_the_date() . ' - ' . get_post_type(); ?></p>
            <?php endwhile;
        endif;
        wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>