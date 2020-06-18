<?php
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

function erdp_save_book_metadata_revisions( $post_id ) {
	$parent_id = wp_is_post_revision( $post_id );

	if ( $parent_id && get_post_type($parent_id)==='books' ) {
        $parent  = get_post( $parent_id );
        
		if( isset( $_POST['idioma'] ) ) add_metadata( 'post', $post_id, 'idioma', $_POST['idioma'] );
        if( isset( $_POST['isbn'] ) ) add_metadata( 'post', $post_id, 'isbn', $_POST['isbn'] );
        if( isset( $_POST['book_publication_date'] ) ) add_metadata( 'post', $post_id, 'book_publication_date', $_POST['book_publication_date'] );
        if( isset( $_POST['reading_start_date'] ) ) add_metadata( 'post', $post_id, 'reading_start_date', $_POST['reading_start_date'] );
        if( isset( $_POST['reading_end_date'] ) ) add_metadata( 'post', $post_id, 'reading_end_date', $_POST['reading_end_date'] );
        if( isset( $_POST['book_author_name'] ) ) add_metadata( 'post', $post_id, 'book_author_name', $_POST['book_author_name'] );
        if( isset( $_POST['purchase_link'] ) ) add_metadata( 'post', $post_id, 'purchase_link', $_POST['purchase_link'] );
	}
}
add_action( 'save_post', 'erdp_save_book_metadata_revisions' );

function erdp_books_restore_revision( $post_id, $revision_id ) {

    if(get_post_type($post_id)==='books') {
        $post     = get_post( $post_id );
        $revision = get_post( $revision_id );

        $idioma  = get_metadata( 'post', $revision->ID, 'idioma', true );
        if ( false !== $idioma ) update_post_meta( $post_id, 'idioma', $idioma );
        else delete_post_meta( $post_id, 'idioma' );

        $isbn  = get_metadata( 'post', $revision->ID, 'isbn', true );
        if ( false !== $isbn ) update_post_meta( $post_id, 'isbn', $isbn );
        else delete_post_meta( $post_id, 'isbn' );
        
        $book_publication_date  = get_metadata( 'post', $revision->ID, 'book_publication_date', true );
        if ( false !== $book_publication_date ) update_post_meta( $post_id, 'book_publication_date', $book_publication_date );
        else delete_post_meta( $post_id, 'book_publication_date' );

        $reading_start_date  = get_metadata( 'post', $revision->ID, 'reading_start_date', true );
        if ( false !== $reading_start_date ) update_post_meta( $post_id, 'reading_start_date', $reading_start_date );
        else delete_post_meta( $post_id, 'reading_start_date' );

        $reading_end_date  = get_metadata( 'post', $revision->ID, 'reading_end_date', true );
        if ( false !== $reading_end_date ) update_post_meta( $post_id, 'reading_end_date', $reading_end_date );
        else delete_post_meta( $post_id, 'reading_end_date' );

        $book_author_name  = get_metadata( 'post', $revision->ID, 'book_author_name', true );
        if ( false !== $book_author_name ) update_post_meta( $post_id, 'book_author_name', $book_author_name );
        else delete_post_meta( $post_id, 'book_author_name' );

        $purchase_link  = get_metadata( 'post', $revision->ID, 'purchase_link', true );
        if ( false !== $purchase_link ) update_post_meta( $post_id, 'purchase_link', $purchase_link );
        else delete_post_meta( $post_id, 'purchase_link' );
    }
}
add_action( 'wp_restore_post_revision', 'erdp_books_restore_revision', 10, 2 );

function erdp_books_revision_fields( $fields ) {
	$fields['idioma'] = 'My Meta';
	$fields['isbn'] = 'My Meta';
	$fields['book_publication_date'] = 'My Meta';
	$fields['reading_start_date'] = 'My Meta';
	$fields['reading_end_date'] = 'My Meta';
	$fields['book_author_name'] = 'My Meta';
	$fields['purchase_link'] = 'My Meta';
	return $fields;
}
add_filter( '_wp_post_revision_fields', 'erdp_books_revision_fields' );

function erdp_revision_field_idioma( $value, $field ) {
	global $revision;
	return get_metadata( 'post', $revision->ID, $field, true );
}
add_filter( '_wp_post_revision_field_idioma', 'erdp_revision_field_idioma', 10, 2 );

function erdp_revision_field_isbn( $value, $field ) {
	global $revision;
	return get_metadata( 'post', $revision->ID, $field, true );
}
add_filter( '_wp_post_revision_field_isbn', 'erdp_revision_field_isbn', 10, 2 );

function erdp_revision_field_book_publication_date( $value, $field ) {
	global $revision;
	return get_metadata( 'post', $revision->ID, $field, true );
}
add_filter( '_wp_post_revision_field_book_publication_date', 'erdp_revision_field_book_publication_date', 10, 2 );

function erdp_revision_field_reading_start_date( $value, $field ) {
	global $revision;
	return get_metadata( 'post', $revision->ID, $field, true );
}
add_filter( '_wp_post_revision_field_reading_start_date', 'erdp_revision_field_reading_start_date', 10, 2 );

function erdp_revision_field_reading_end_date( $value, $field ) {
	global $revision;
	return get_metadata( 'post', $revision->ID, $field, true );
}
add_filter( '_wp_post_revision_field_reading_end_date', 'erdp_revision_field_reading_end_date', 10, 2 );

function erdp_revision_field_book_author_name( $value, $field ) {
	global $revision;
	return get_metadata( 'post', $revision->ID, $field, true );
}
add_filter( '_wp_post_revision_field_book_author_name', 'erdp_revision_field_book_author_name', 10, 2 );

function erdp_revision_field_purchase_link( $value, $field ) {
	global $revision;
	return get_metadata( 'post', $revision->ID, $field, true );
}
add_filter( '_wp_post_revision_field_purchase_link', 'erdp_revision_field_purchase_link', 10, 2 );
