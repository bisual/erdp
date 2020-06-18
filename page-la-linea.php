<?php 
/* Template Name: La Línea Template */

get_header(); 

?>


<?php
    if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
    <div class="blog-post single">
	<h1 class="post-title"><?php the_title(); ?></h1>

    <?php get_template_part("template-parts/content", "post"); ?>

    <div id="story">
        <?php
            $loop = new WP_Query( array( 'post_type' => ['post','books','revision'], 'paged' => 1, "orderby" => "post_date", "order" => "DESC", "posts_per_page" => 10, 'post_status' => array('publish','inherit')) );
            if ( $loop->have_posts() ) :
                while ( $loop->have_posts() ) : $loop->the_post(); ?>
                    <p><?php echo get_the_title() . ' - ' . get_the_date() . ' - ' . get_post_type(); ?></p>
                    <?php 
                        $parent_id = wp_is_post_revision(get_the_ID());
                        if($loop->post->post_type==='books' || ($parent_id!==false && get_post_type($parent_id)==='books')) {
                            var_dump(get_post_meta( get_the_ID(), 'idioma', 'single' ));
                        }
                    ?>  
                <?php endwhile;
            endif;
            wp_reset_postdata();
        ?>
    </div>
</div><!-- /.blog-page -->

<?php        
    endwhile; endif;
?>
