<?php

    $revisions = wp_get_post_revisions(get_the_ID());

    $date_format_option = get_option( 'date_format' );
    foreach($revisions as $revision) {
        $revision->post_formatted_date = date($date_format_option, strtotime($revision->post_date));
    }

    wp_localize_script("erdp_post_slider", "vars", [
        "max" => sizeof($revisions)-1,
        "revisions" => array_reverse(array_values($revisions))
    ]);

?>
<div class="blog-post single">
	<h1 class="post-title"><?php the_title(); ?></h1>

    <div id="post-slider"></div>

    <p class="blog-post-meta">
        <span class="post-date">
            <?php the_date(); ?>
            <span class="revision-date"></span>
        </span>
        <!--&nbsp;&nbsp;·&nbsp;&nbsp;
        <a href="#" class="post-author"><?php the_author(); ?></a>-->
    </p>

    <?php get_template_part("template-parts/content", "post"); ?>

</div><!-- /.blog-post -->
