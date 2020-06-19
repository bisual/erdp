<?php 

function el_cajon_ajax() {

    $active = isset($_POST["active"]) ? boolval(esc_attr($_POST["active"])) : null;
    $sort_by_date = isset($_POST["sort_by_date"]) ? boolval(esc_attr($_POST["sort_by_date"])) : null;

    $args = [
        'post_type' => "proposals",
        'posts_per_page' => -1,
        'orderby' => 'DESC'
    ];

    if($sort_by_date) {
        $args["orderby"] = "date";
    }
    else {
        $args["meta_key"] = "votes";
        $args["orderby"] = "meta_value_num";
    }

    if($active) {
        $args["meta_query"] = [
            [
                'key' => 'done',
                'value' => 'done',
                'compare' => '!='
            ]
        ];
    }
    else {
        $args["meta_query"] = [
            [
                'key' => 'done',
                'value' => 'done',
            ]
        ];
    }

    $loop = new WP_Query($args);

    $data = [];
    if($loop->have_posts()) {
        while($loop->have_posts()) {
            $loop->the_post();

            $post = $loop->post;
            $post->votes = get_post_meta($post->ID, 'votes', true);
            $post->done = get_post_meta($post->ID, 'done', true);
            $post->custom_date_format = date("d/m/Y", strtotime($post->post_date));
            array_push($data, $post);
        }
    }

    wp_send_json_success($data);
    wp_die();
}
add_action('wp_ajax_el_cajon_ajax', 'el_cajon_ajax');
add_action('wp_ajax_nopriv_el_cajon_ajax', 'el_cajon_ajax');


function el_cajon_vote_ajax() {
    $post_id = isset($_POST["post_id"]) ? intval(esc_attr($_POST["post_id"])) : null;

    $votes = intval(get_post_meta($post_id, 'votes', true) ?? 0);
    $votes++;
    update_post_meta($post_id, 'votes', $votes);

    wp_send_json_success(['votes' => $votes]);
    wp_die();
}
add_action('wp_ajax_el_cajon_vote_ajax', 'el_cajon_vote_ajax');
add_action('wp_ajax_nopriv_el_cajon_vote_ajax', 'el_cajon_vote_ajax');