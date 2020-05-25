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


/**
 * BOOKS ENTITY
 */
function create_book_posttype() {
    //post type
    register_post_type('books', [
        'labels' => [
            'name'                  => __('Libros'),
            'singular_name'         => __('Libro'),
            'menu_name'             => __( 'Libros' ),
            'all_items'             => __( 'Todos los libros' ),
            'view_item'             => __( 'Ver libro' ),
            'add_new_item'          => __( 'Añadir libro' ),
            'add_new'               => __( 'Añadir nuevo' ),
            'edit_item'             => __( 'Editar libro' ),
            'update_item'           => __( 'Actualizar libro' ),
            'search_items'          => __( 'Buscar libro' ),
            'not_found'             => __( 'Libro no encontrado' ),
            'not_found_in_trash'    => __( 'Libro no encontrado en la papelera' ),
        ],
        'public' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'menu_icon' => 'dashicons-book',
        'rewrite' => [
            'slug' => 'libros'
        ],
        'supports' => [ 'title', 'thumbnail', 'revisions' ],
        'taxonomies' => [ 'book_genres' ],
    ]);

    //taxonomy
    register_taxonomy('book_genres', ['books'], [
        'hierarchical' => false,
        'labels' => [
            'name' => __('Géneros'),
            'singular_name' => __( 'Género' ),
            'search_items' =>  __( 'Buscar género' ),
            'all_items' => __( 'Todos los géneros' ),
            'edit_item' => __( 'Editar género' ), 
            'update_item' => __( 'Actualizar género' ),
            'add_new_item' => __( 'Añadir género' ),
            'new_item_name' => __( 'Añadir género' ),
            'menu_name' => __( 'Géneros' ),
        ],
        'query_var' => true,
        'rewrite' => [ 'slug' => 'genre' ]
    ]);
}
add_action('init', 'create_book_posttype');

function erdp_register_books_meta_boxes() {
	add_meta_box( 'books-meta-box', __( 'Atributos del libro' ), function($book) {
        $idioma = get_post_meta($book->ID, 'idioma', true);
        $isbn = get_post_meta($book->ID, 'isbn', true);
        $book_publication_date = get_post_meta($book->ID, 'book_publication_date', true);
        $reading_start_date = get_post_meta($book->ID, 'reading_start_date', true);
        $reading_end_date = get_post_meta($book->ID, 'reading_end_date', true);
        $book_author_name = get_post_meta($book->ID, 'book_author_name', true);
        $purchase_link = get_post_meta($book->ID, 'purchase_link', true);

        wp_nonce_field( 'books_meta_box_nonce', 'meta_box_nonce' );
        
        echo '<p><label for="idioma">Idioma</label> <input type="text" name="idioma" id="idioma" value="'. $idioma .'" /></p>';
	    echo '<p><label for="isbn">ISBN</label> <input type="text" name="isbn" id="isbn" value="'. $isbn .'" /></p>';
	    echo '<p><label for="book_publication_date">Fecha de publicación</label> <input type="date" name="book_publication_date" id="book_publication_date" value="'. $book_publication_date .'" /></p>';
	    echo '<p><label for="reading_start_date">Fecha de inicio de lectura</label> <input type="date" name="reading_start_date" id="reading_start_date" value="'. $reading_start_date .'" /></p>';
	    echo '<p><label for="reading_end_date">Fecha de fin de lectura</label> <input type="date" name="reading_end_date" id="reading_end_date" value="'. $reading_end_date .'" /></p>';
	    echo '<p><label for="book_author_name">Autor del libro</label> <input type="text" name="book_author_name" id="book_author_name" value="'. $book_author_name .'" /></p>';
	    echo '<p><label for="purchase_link">Link de compra</label> <input type="text" name="purchase_link" id="purchase_link" value="'. $purchase_link .'" /></p>';
    }, 'books' );
}
add_action( 'add_meta_boxes', 'erdp_register_books_meta_boxes' );

function erdp_save_book_metadata( $post_id ) {
	// Comprobamos si es auto guardado
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	// Comprobamos el valor nonce creado en twp_mi_display_callback()
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'books_meta_box_nonce' ) ) return;
	// Comprobamos si el usuario actual no puede editar el post
	if( !current_user_can( 'edit_post' ) ) return;
    
	if(get_post_type($post_id)==='books') {
        // Guardamos... 
        if( isset( $_POST['idioma'] ) ) update_post_meta( $post_id, 'idioma', $_POST['idioma'] );
        if( isset( $_POST['isbn'] ) ) update_post_meta( $post_id, 'isbn', $_POST['isbn'] );
        if( isset( $_POST['book_publication_date'] ) ) update_post_meta( $post_id, 'book_publication_date', $_POST['book_publication_date'] );
        if( isset( $_POST['reading_start_date'] ) ) update_post_meta( $post_id, 'reading_start_date', $_POST['reading_start_date'] );
        if( isset( $_POST['reading_end_date'] ) ) update_post_meta( $post_id, 'reading_end_date', $_POST['reading_end_date'] );
        if( isset( $_POST['book_author_name'] ) ) update_post_meta( $post_id, 'book_author_name', $_POST['book_author_name'] );
        if( isset( $_POST['purchase_link'] ) ) update_post_meta( $post_id, 'purchase_link', $_POST['purchase_link'] );
    }
}
add_action( 'save_post', 'erdp_save_book_metadata' );

function save_books_metadata_in_revisions( $post_id ) {

	$parent_id = wp_is_post_revision( $post_id );

	if ( $parent_id && get_post_type($post_id)==='books' ) {
		$parent  = get_post( $parent_id );
		$idioma = get_post_meta( $parent->ID, 'idioma', true );
		$isbn = get_post_meta( $parent->ID, 'isbn', true );
		$book_publication_date = get_post_meta( $parent->ID, 'book_publication_date', true );
		$reading_start_date = get_post_meta( $parent->ID, 'reading_start_date', true );
		$reading_end_date = get_post_meta( $parent->ID, 'reading_end_date', true );
		$book_author_name = get_post_meta( $parent->ID, 'book_author_name', true );
		$purchase_link = get_post_meta( $parent->ID, 'purchase_link', true );
		if ( false !== $idioma ) update_post_meta($post_id, 'idioma', $my_meta );
		if ( false !== $isbn ) update_post_meta($post_id, 'isbn', $my_meta );
		if ( false !== $book_publication_date ) update_post_meta($post_id, 'book_publication_date', $my_meta );
		if ( false !== $reading_start_date ) update_post_meta($post_id, 'reading_start_date', $my_meta );
		if ( false !== $reading_end_date ) update_post_meta($post_id, 'reading_end_date', $my_meta );
		if ( false !== $book_author_name ) update_post_meta($post_id, 'book_author_name', $my_meta );
		if ( false !== $purchase_link ) update_post_meta($post_id, 'purchase_link', $my_meta );
	}

}
add_action( 'save_post', 'save_books_metadata_in_revisions' );

function erdp_books_revision_fields_meta( $fields ) {

	$fields['idioma'] = 'My Meta';
	$fields['isbn'] = 'My Meta';
	$fields['book_publication_date'] = 'My Meta';
	$fields['reading_start_date'] = 'My Meta';
	$fields['reading_end_date'] = 'My Meta';
	$fields['book_author_name'] = 'My Meta';
	$fields['purchase_link'] = 'My Meta';
	return $fields;

}
add_filter( '_wp_post_revision_fields', 'erdp_books_revision_fields_meta' );

function my_plugin_revision_field( $value, $field ) {

	global $revision;
	return get_metadata( $revision->ID, $field, true );

}
add_filter( '_wp_post_revision_field_idioma', 'my_plugin_revision_field', 10, 2 );


/**
 * PROPOSALS ENTITY
 */
function create_proposal_posttype() {
    //post type
    register_post_type('proposals', [
        'labels' => [
            'name'                  => __('Propuestas'),
            'singular_name'         => __('Propuesta'),
            'menu_name'             => __( 'Propuestas' ),
            'all_items'             => __( 'Todas las propuestas' ),
            'view_item'             => __( 'Ver propuesta' ),
            'add_new_item'          => __( 'Añadir propuesta' ),
            'add_new'               => __( 'Añadir nueva' ),
            'edit_item'             => __( 'Editar propuesta' ),
            'update_item'           => __( 'Actualizar propuesta' ),
            'search_items'          => __( 'Buscar propuesta' ),
            'not_found'             => __( 'Propuesta no encontrado' ),
            'not_found_in_trash'    => __( 'Propuesta no encontrada en la papelera' ),
        ],
        'public' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'menu_icon' => 'dashicons-format-status',
        'rewrite' => [
            'slug' => 'propuestas'
        ],
        'supports' => [ 'title', 'excerpt', 'revisions' ],
    ]);
}
add_action('init', 'create_proposal_posttype');

function erdp_register_proposals_meta_boxes() {
	add_meta_box( 'proposals-meta-box', __( 'Atributos de la propuesta' ), function($proposal) {
        $done = get_post_meta($proposal->ID, 'done', true);
        $votes = get_post_meta($proposal->ID, 'votes', true);

        wp_nonce_field( 'proposals_meta_box_nonce', 'meta_box_nonce' );
        
        echo '<p><label for="done">Fet</label> <input type="checkbox" name="done" id="done" value="done" ' . ($done=='done' ? 'checked' : '') . ' /></p>';
	    echo '<p><label for="votes">Votos</label> <input type="number" name="votes" id="votes" value="'. $votes .'" /></p>';
    }, 'proposals' );
}
add_action( 'add_meta_boxes', 'erdp_register_proposals_meta_boxes' );

function erdp_save_proposals_metadata( $post_id ) {
	// Comprobamos si es auto guardado
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	// Comprobamos el valor nonce creado en twp_mi_display_callback()
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'proposals_meta_box_nonce' ) ) return;
	// Comprobamos si el usuario actual no puede editar el post
	if( !current_user_can( 'edit_post' ) ) return;
    
	if(get_post_type($post_id)==='proposals') {
        // Guardamos... 
        if( isset( $_POST['done'] ) ) update_post_meta( $post_id, 'done', $_POST['done']=="done" ? "done" : "null" );
        else update_post_meta( $post_id, 'done', "null");

        if( isset( $_POST['votes'] ) ) update_post_meta( $post_id, 'votes', $_POST['votes'] );
    }
}
add_action( 'save_post', 'erdp_save_proposals_metadata' );

?>