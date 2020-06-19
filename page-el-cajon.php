<?php 

get_header(); 

?>

<?php
    if ( have_posts() ) : while ( have_posts() ) : the_post();
?>

<div class="blog-post single el-cajon">
    
	<h1 class="post-title"><?php the_title(); ?></h1>

    <?php get_template_part("template-parts/content", "post"); ?>

    <?php get_template_part("template-parts/proposals-loop"); ?>

    <?php get_template_part( 'template-parts/pager', get_post_format() ); ?>

</div><!-- /.blog-post -->

<?php
        
    endwhile; endif;
    
    get_footer(); 
    
?>