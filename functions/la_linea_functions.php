<?php 

function la_linea_ajax_load_more() {
    
    $page_num = isset($_POST["page_num"]) ? esc_attr($_POST["page_num"]) : null;
    
    $loop = new WP_Query( array( "paged" => $page_num, 'post_type' => ['post','books','revision'], "orderby" => "post_date", "order" => "DESC", "posts_per_page" => 10, 'post_status' => array('publish','inherit')) );
    $data = [];
    if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post();
      $post = $loop->post;
      $parent_id = wp_is_post_revision( $post->ID );
      if($post->post_type=="books") {
        $post->idioma = get_metadata( 'post', $post->ID, 'idioma', true );
        $post->isbn = get_metadata( 'post', $post->ID, 'isbn', true );
        $post->book_publication_date = get_metadata( 'post', $post->ID, 'book_publication_date', true );
        $post->reading_start_date = get_metadata( 'post', $post->ID, 'reading_start_date', true );
        $post->reading_end_date = get_metadata( 'post', $post->ID, 'reading_end_date', true );
        $post->book_author_name = get_metadata( 'post', $post->ID, 'book_author_name', true );
        $post->purchase_link = get_metadata( 'post', $post->ID, 'purchase_link', true );
        
        $post->title = $post->book_publication_date ? 'Se ha publicado el artículo del libro' : $post->reading_end_date ? 'Preparando el artículo...' : $post->reading_start_date ? 'Se ha iniciado una nueva lectura' : 'Estado desconocido';
      }
      else {
        $post->title = $parent_id !== false ? 'Se ha actualizado un artículo' : 'Se ha creado un artículo nuevo';
      }

      $post->url = get_permalink( $parent_id ? $parent_id : $post->ID );

      array_push($data, $post);
    endwhile; endif; wp_reset_postdata();

    wp_send_json_success($data);
    wp_die();
}
add_action('wp_ajax_la_linea_ajax_load_more', 'la_linea_ajax_load_more');
add_action('wp_ajax_nopriv_la_linea_ajax_load_more', 'la_linea_ajax_load_more');