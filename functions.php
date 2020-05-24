<?php


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


?>