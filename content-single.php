<div class="blog-post single">
	<h1 class="post-title"><?php the_title(); ?></h1>
    <p class="blog-post-meta">
        <?php the_date(); ?>&nbsp;&nbsp;·&nbsp;&nbsp;<a href="#"><?php the_author(); ?></a>
    </p>

    <?php the_content(); ?>

</div><!-- /.blog-post -->