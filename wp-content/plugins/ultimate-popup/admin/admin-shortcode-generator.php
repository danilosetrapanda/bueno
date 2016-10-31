<?php


// Shortcode Generator
add_filter('manage_ultimate-popup_posts_columns', 'ultimate_popup_wp_shortcode_column_head', 10);
add_action('manage_ultimate-popup_posts_custom_column', 'ultimate_popup_wp_shortcode_column_content', 10, 2);
function ultimate_popup_wp_shortcode_column_head($defaults) {
    $upshortcode_column_lang_text = __('Shortcode', 'ultimate-popup');
    $defaults['up_shortcode_generate'] = ''.$upshortcode_column_lang_text.'';
    return $defaults;
}
function ultimate_popup_wp_shortcode_column_content($column_name, $post_ID) {
    
    if ($column_name == 'up_shortcode_generate') {
        $shortcode_render = '[ultimate_popup id="'.$post_ID.'"]';
        
        echo '<input style="min-width:210px" type=\'text\' onClick=\'this.setSelectionRange(0, this.value.length)\' value=\''.$shortcode_render.'\' />';
    }
}