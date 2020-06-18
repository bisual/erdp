<?php

define("ERDP_JS", get_template_directory_uri() . "/js/");
define( 'WP_POST_REVISIONS', false );

function erdp_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-slider', "jquery", "1.0.0", false );

    //theme js
    wp_enqueue_script( 'erdp_main', ERDP_JS . "main.js", array("jquery", "jquery-ui-slider"), "1.0.0", true );

    //blog post
    if(is_single()) {
        wp_enqueue_script( 'erdp_post_slider', ERDP_JS . "post-slider.js", array("jquery", "jquery-ui-slider"), "1.0.0", true );
    }

    //la linea
    if(is_page('la-linea')) {
        wp_enqueue_script( 'erdp_la_linea_infinite_loading', ERDP_JS . "la-linea-infinite-loading.js", array('jquery'), "1.0.0", true );
        wp_localize_script( 'erdp_la_linea_infinite_loading', 'vars', [
            'url' => admin_url("admin-ajax.php")    
        ] );
    }
}
add_action('wp_enqueue_scripts', 'erdp_scripts');

function footer_widgets_init() {

	register_sidebar( array(
		'name'          => 'Footer',
		'id'            => 'footer',
		'before_widget' => '<div class="footer-section-col">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );

}
add_action( 'widgets_init', 'footer_widgets_init' );


function header_menu() {
    register_nav_menus([
        'header-menu-1' => __( 'Header Menu Left' ),
        'header-menu-2' => __( 'Header Menu Right' ),
    ]);
}
add_action( 'init', 'header_menu' );


function erdp_initial_page_content_callout($wp_customize) {
    $wp_customize->add_section('erdp_initial_page_content_callout_section', [
        'title' => "Front Page"
    ]);
    //headline
    $wp_customize->add_setting('erdp_initial_page_content_headline_settings');
    $wp_customize->add_control('erdp_initial_page_content_headline_settings', [
        'label' => 'Headline',
        'type' => 'textarea',
        'description' => 'Texto del inicio de la página',
        'section' => 'erdp_initial_page_content_callout_section',
    ]);
}
add_action('customize_register', 'erdp_initial_page_content_callout');

//when publishing draft post removes existing revisions
function erdp_on_publish_post($post) {
    global $wpdb;

    $wpdb->query( 
        $wpdb->prepare( 
            "DELETE FROM $wpdb->posts
             WHERE post_type = 'revision'
             AND post_parent = %d
             AND post_date != %s",
                $post->ID, $post->post_date
        )
    );
}
add_action('draft_to_publish', 'erdp_on_publish_post');

require_once("functions/books_post_type.php");
require_once("functions/proposals_post_type.php");
require_once("functions/la_linea_functions.php");

?>