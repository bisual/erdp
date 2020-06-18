<?php

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