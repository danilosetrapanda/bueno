<?php 

// popup custom messages
add_filter( 'post_updated_messages', 'ultimate_popup_updated_messages' );
function ultimate_popup_updated_messages( $messages ){
    
    global $post;
    
    $post_ID = $post->ID;    
        
    $messages['ultimate-popup'] = array(
        0 => '', 
        1 => sprintf( __('Popup updated. Shortcode is: %s', 'ultimate-popup'), '[ultimate_popup id="'.$post_ID.'"]' ),
        2 => __('Popup field updated.', 'ultimate-popup'),
        3 => __('Popup field deleted.', 'ultimate-popup'),
        4 => __('Popup updated.', 'ultimate-popup'),
        5 => isset($_GET['revision']) ? sprintf( __('Popup restored to revision from %s', 'ultimate-popup'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Popup published. Shortcode is: %s', 'ultimate-popup'), '[ultimate_popup id="'.$post_ID.'"]' ),
        7 => __('Popup saved.'),
        8 => sprintf( __('Popup submitted.', 'ultimate-popup'), esc_url( add_query_arg( 'preview', 'true',get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Popup scheduled for: <strong>%1$s</strong>.', 'ultimate-popup'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( __('Popup draft updated.', 'ultimate-popup'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
    
    return $messages;
        
}