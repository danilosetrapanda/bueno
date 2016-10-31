<?php 


// Including plugin assets
function ultimate_popup_files(){
    
    wp_enqueue_script('jquery');
    
    wp_enqueue_style('ultimate-popup-main', plugin_dir_url( __FILE__ ) .'../assets/css/ultimate-popup.css');
    wp_enqueue_style('ultimate-popup-remodal', plugin_dir_url( __FILE__ ) .'../assets/css/remodal.css');
    
    wp_enqueue_script( 'ultimate-popup-main-js', plugin_dir_url( __FILE__ ) . '../assets/js/ultimate-popup.js', array(), '20120206', true );   
    wp_enqueue_script( 'ultimate-popup-cookie-js', plugin_dir_url( __FILE__ ) . '../assets/js/jquery.cookie.js', array(), '20120206', true );   
    wp_enqueue_script( 'ultimate-popup-modal-js', plugin_dir_url( __FILE__ ) . '../assets/js/remodal.min.js', array(), '20120206', true );   
}
add_action('wp_enqueue_scripts', 'ultimate_popup_files'); 