<?php get_header(); ?>

<?php
    if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
    <div class="blog-post single">
	<h1 class="post-title"><?php the_title(); ?></h1>

    <?php get_template_part("template-parts/content", "post"); ?>

</div><!-- /.blog-page -->

<?php        
    endwhile; endif;
?>

<?php get_footer(); ?>