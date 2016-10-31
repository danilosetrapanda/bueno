<?php

add_action( 'init', 'ultimate_popup_register_cpt' );
function ultimate_popup_register_cpt() {
    register_post_type( 'ultimate-popup',
        array(
            'labels' => array(
                'name' => __( 'Popups' ),
                'singular_name' => __( 'Popup' )
            ),
            'supports' => array('title', 'thumbnail', 'page-attributes'),
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-building'
        )
    );
}