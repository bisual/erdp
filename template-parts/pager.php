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