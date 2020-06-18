<?php 

function la_linea_ajax_load_more() {
    
    $page_num = isset($_POST["page_num"]) ? intval(esc_attr($_POST["page_num"])) : null;
    $page_size = isset($_POST["page_size"]) ? intval(esc_attr($_POST["page_size"])) : null;
    
    global $wpdb;
    $offset = $page_num*$page_size;
    $sql = "
      SELECT p.* FROM $wpdb->posts as p
      LEFT JOIN $wpdb->posts as pp ON p.post_parent = pp.ID
      WHERE 
        p.post_type = 'revision' AND pp.post_status = 'publish' AND pp.post_type IN ('post', 'books')
      ORDER BY p.post_date DESC
      LIMIT $offset,$page_size
    ";
    $loop = $wpdb->get_results($sql);

    $data = [];

    foreach($loop as $post) {
      $parent_id = wp_is_post_revision( $post->ID );
      if($post->post_type=="books" || ($parent_id!==false && get_post_type($parent_id)==='books')) {
        $post->idioma = get_metadata( 'post', $post->ID, 'idioma', true );
        $post->isbn = get_metadata( 'post', $post->ID, 'isbn', true );
        $post->book_publication_date = get_metadata( 'post', $post->ID, 'book_publication_date', true );
        $post->reading_start_date = get_metadata( 'post', $post->ID, 'reading_start_date', true );
        $post->reading_end_date = get_metadata( 'post', $post->ID, 'reading_end_date', true );
        $post->book_author_name = get_metadata( 'post', $post->ID, 'book_author_name', true );
        $post->purchase_link = get_metadata( 'post', $post->ID, 'purchase_link', true );
        
        $post->title = $post->book_publication_date ? 'Se ha publicado el artículo del libro' : ($post->reading_end_date ? 'Lectura finalizada' : ($post->reading_start_date ? 'Se ha iniciado una nueva lectura' : 'Estado desconocido'));
        $post->link_text = "Ver libro";
      }
      else {
        $post->title = "Artículo"; //$parent_id !== false ? 'Se ha actualizado un artículo' : 'Se ha creado un artículo nuevo';
        $post->link_text = "Leer artículo";
      }
      
      $post->custom_date_format = date("d/m/Y", strtotime($post->post_date));
      $post->url = get_permalink( $parent_id ? $parent_id : $post->ID );
  
      array_push($data, $loop->post);
    }
    
    wp_send_json_success($loop);
    wp_die();
}
add_action('wp_ajax_la_linea_ajax_load_more', 'la_linea_ajax_load_more');
add_action('wp_ajax_nopriv_la_linea_ajax_load_more', 'la_linea_ajax_load_more');