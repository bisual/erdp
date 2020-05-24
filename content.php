<div class="blog-post">
	<a href="<?php the_permalink(); ?>" class="blog-post-title">
        <h2><?php the_title(); ?></h2>
    </a>
    <p class="blog-post-meta">
        <?php the_date(); ?>&nbsp;&nbsp;·&nbsp;&nbsp;<?php the_author(); ?>
    </p>

    <?php the_excerpt(); ?>

</div><!-- /.blog-post -->