<?php 

function la_linea_ajax_load_more() {
    
    $page_num = isset($_POST["page_num"]) ? esc_attr($_POST["page_num"]) : null;
    
    $loop = new WP_Query( array( "paged" => $page_num, 'post_type' => ['post','books','revision'], "orderby" => "post_date", "order" => "DESC", "posts_per_page" => 10, 'post_status' => array('publish','inherit')) );
    $data = [];
    if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post();
		array_push($data, $loop->post);
    endwhile; endif; wp_reset_postdata();

    wp_send_json_success($data);
    wp_die();
}
add_action('wp_ajax_la_linea_ajax_load_more', 'la_linea_ajax_load_more');
add_action('wp_ajax_nopriv_la_linea_ajax_load_more', 'la_linea_ajax_load_more');