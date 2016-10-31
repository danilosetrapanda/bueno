<?php

function ppm_popup_generate_shortcode($atts){
	extract( shortcode_atts( array(
		'id' => '',
	), $atts) );
	
    $q = new WP_Query(
        array('posts_per_page' => 1, 'p' =>$id, 'post_type' => 'ultimate-popup')
        );		
		
	$ultimate_popup = '<div id="ppm-popup-post-shortcode-wrap">';	
	while($q->have_posts()) : $q->the_post();
    

    $prefix = '_ultimate_popup_';
    $post_id = get_the_ID();
    
    $ppm_popup_title= get_post_meta($post_id, $prefix . 'title', true); 
    $ppm_popup_desc= get_post_meta($post_id, $prefix . 'description', true); 
    $ppm_form_code= get_post_meta($post_id, $prefix . 'code', true); 
    $ppm_popup_width= get_post_meta($post_id, $prefix . 'width', true); 
    $ppm_popup_border_width= get_post_meta($post_id, $prefix . 'border_width', true); 
    $ppm_popup_theme= get_post_meta($post_id, $prefix . 'theme', true); 
    $ppm_popup_left_content= get_post_meta($post_id, $prefix . 'left_content', true); 
    $ppm_popup_theme_color= get_post_meta($post_id, $prefix . 'theme_color', true); 
    $enable_cross_button= get_post_meta($post_id, $prefix . 'cross_btn', true); 
    $popup_show_on= get_post_meta($post_id, $prefix . 'show_on', true); 
    $when_popup_load= get_post_meta($post_id, $prefix . 'when', true); 
    $popup_enable_cookie= get_post_meta($post_id, $prefix . 'cookie', true); 
    $ppm_popup_timeunit= get_post_meta($post_id, $prefix . 'time_unit', true); 
    $ppm_popup_timevalue= get_post_meta($post_id, $prefix . 'time_value', true); 
    $ppm_popup_position= get_post_meta($post_id, $prefix . 'position', true);  
    $ppm_popup_overlay= get_post_meta($post_id, $prefix . 'overlay', true);  

    $popup_inner_bg = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
    
    if($popup_show_on == '1') {
    $ultimate_popup .='<div id="ultimate-popup-'.$post_id.'" class="ultimate-popup-wrapper ultimate-popup-theme-id-'.$ppm_popup_theme.' ultimate-popup-halfway-scroll-activate">';
    } elseif($popup_show_on == '2') {
    $ultimate_popup .='<div id="ultimate-popup-'.$post_id.'" class="ultimate-popup-wrapper ultimate-popup-theme-id-'.$ppm_popup_theme.' ultimate-popup-automatic-activate">';
    }else {
    $ultimate_popup .='<div id="ultimate-popup-'.$post_id.'" class="ultimate-popup-wrapper ultimate-popup-theme-id-'.$ppm_popup_theme.'">';
    }
    
    if($enable_cross_button == '1') {
        if($ppm_popup_theme == '6') {
        $ultimate_popup .='<button data-remodal-action="close" class="remodal-close cross-btn-ppm" aria-label="Close">Close</button>';
        } else {
        $ultimate_popup .='<button data-remodal-action="close" class="remodal-close cross-btn-ppm" aria-label="Close"></button>';
        }
    }
    
    require_once realpath(dirname(__FILE__).'/..').'/themes/theme-'.$ppm_popup_theme.'/index.php';


    $ultimate_popup .='</div>';
    
    
    $ultimate_popup .='
<style>
    .remodal-overlay {background-color:'.$ppm_popup_theme_color.';opacity:.5}
    #ultimate-popup-'.$post_id.' {width: '.$ppm_popup_width.'; border-width:'.$ppm_popup_border_width.';border-color:'.$ppm_popup_theme_color.'}
    ';
    
    
    require_once realpath(dirname(__FILE__).'/..').'/themes/theme-'.$ppm_popup_theme.'/style.php';

    if($ppm_popup_overlay == '2') {
        $ultimate_popup .= '.remodal-overlay {display:none;visibility:hidden;z-index:-1;opacity:0}';
    }
    
    
    if($popup_show_on == '1') :
        if($ppm_popup_position == '2') :
            $ultimate_popup .='
            #ultimate-popup-'.$post_id.' {left: 0;bottom: -100%;}
            #ultimate-popup-'.$post_id.'.ultimate-popup-wrapper-activate {bottom: 0;}             
            ';
        else :   
            $ultimate_popup .='
            #ultimate-popup-'.$post_id.' {right: 0;bottom: -100%;}
            #ultimate-popup-'.$post_id.'.ultimate-popup-wrapper-activate {bottom: 0;}            
            ';
        endif;  
    
    endif;

$ultimate_popup .='
</style>


<script type="text/javascript">
//<![CDATA[  
';
    
    
if($when_popup_load == '2') :
    $ultimate_popup .='jQuery(document).ready(function(){';
else :
    $ultimate_popup .='jQuery(window).load(function(){';
endif;
        
      

    if($popup_enable_cookie == '2') :
    
        if($popup_show_on == '2') :
            $ultimate_popup .='
            
            var inst = jQuery("#ultimate-popup-'.$post_id.'").remodal();
            inst.open();

            jQuery("#ultimate-popup-'.$post_id.'").addClass("ultimate-popup-wrapper-activate");
            ';
        else :
            $ultimate_popup .='
            // Popup is showing first time!
            jQuery(window).scroll(function () { 
              if (jQuery(window).scrollTop() > jQuery("body").height() / 2) {
                jQuery("#ultimate-popup-'.$post_id.'").addClass("ultimate-popup-wrapper-activate");
              } 
            });  
            ';
        endif;     
    
    else :
    
    if($popup_show_on == '4') :
    $ultimate_popup .='
    jQuery("body").mouseleave(function() { 
    ';
    endif;
    
    $ultimate_popup .='
    if (jQuery.cookie("popupTemporaryCookie'.$post_id.'")) {

        // Popup is hiding after showing first time!
        jQuery("#ultimate-popup-'.$post_id.'").hide();

    } else if (jQuery.cookie("popupLongerCookie'.$post_id.'")) {
    ';
    
    if($popup_show_on == '2') :
        $ultimate_popup .='
        var inst = jQuery("#ultimate-popup-'.$post_id.'").remodal();
        inst.open();
    
        jQuery("#ultimate-popup-'.$post_id.'").addClass("ultimate-popup-wrapper-activate");
        ';
    elseif($popup_show_on == '3') :
        $ultimate_popup .='
        jQuery("body").mouseleave(function() { 
        
            var inst = jQuery("#ultimate-popup-'.$post_id.'").remodal();
            inst.open();
            
            jQuery(".cross-btn-ppm, .lean-overlay").click(function(){
                jQuery("body").addClass("ultimate-popup-wrapper-hide");
            });

            jQuery("#ultimate-popup-'.$post_id.'").addClass("ultimate-popup-wrapper-activate");
        });        
        ';
    else :
        $ultimate_popup .='
        // Popup is showing again!
        jQuery(window).scroll(function () { 
          if (jQuery(window).scrollTop() > jQuery("body").height() / 2) {
            jQuery("#ultimate-popup-'.$post_id.'").addClass("ultimate-popup-wrapper-activate");
          } 
        });
        ';
    endif;      


    $ultimate_popup .='
    } else {
    ';
    
    if($popup_show_on == '2') :
        $ultimate_popup .='
        var inst = jQuery("#ultimate-popup-'.$post_id.'").remodal();
        inst.open();

    
        jQuery("#ultimate-popup-'.$post_id.'").addClass("ultimate-popup-wrapper-activate");
        ';
    elseif($popup_show_on == '3') :
        $ultimate_popup .='
        jQuery("body").mouseleave(function() { 
            var inst = jQuery("#ultimate-popup-'.$post_id.'").remodal();
            inst.open();
            
            jQuery(".cross-btn-ppm, .lean-overlay").click(function(){
                jQuery("body").addClass("ultimate-popup-wrapper-hide");
            });            

            jQuery("#ultimate-popup-'.$post_id.'").addClass("ultimate-popup-wrapper-activate");
       });    
       ';
    else :
        $ultimate_popup .='
        // Popup is showing first time!
        jQuery(window).scroll(function () { 
          if (jQuery(window).scrollTop() > jQuery("body").height() / 2) {
            jQuery("#ultimate-popup-'.$post_id.'").addClass("ultimate-popup-wrapper-activate");
          } 
        });  
        ';
    endif;    
    
    $ultimate_popup .='
    }
        
      

    var expiresAt = new Date();
    ';
    
    if($ppm_popup_timeunit == '1') :
        $ultimate_popup .='expiresAt.setTime(expiresAt.getTime() + '.$ppm_popup_timevalue.'*24*60*60*1000); // Days';               
    elseif($ppm_popup_timeunit == '2') :
        $ultimate_popup .='expiresAt.setTime(expiresAt.getTime() + '.$ppm_popup_timevalue.'*60*60*1000); // Hours';  
    else :
        $ultimate_popup .='expiresAt.setTime(expiresAt.getTime() + '.$ppm_popup_timevalue.'*60*1000); // Minutes';  
    endif;

    $ultimate_popup .='
    jQuery.cookie("popupLongerCookie'.$post_id.'", new Date());
    jQuery.cookie("popupTemporaryCookie'.$post_id.'", true, { expires: expiresAt });  
    ';
    endif;
        
    if($popup_show_on == '4') :
    $ultimate_popup .='});';
    endif;  
    
$ultimate_popup .='
});

//]]>
</script>
';
    
    endwhile;
    $ultimate_popup.= '</div>';
	wp_reset_query();
    
	return $ultimate_popup;
}
add_shortcode('ultimate_popup', 'ppm_popup_generate_shortcode');

function ultimate_popup_mass_display(){
    global $options;
    $popup_display_settings = ultimate_popup_get_option('popup_display_settings');
    $global_popup_id = ultimate_popup_get_option('global_popup_id');
    
    if($popup_display_settings == '2') {
        if( is_home() ) :
            echo do_shortcode('[ultimate_popup id="'.$global_popup_id.'"]');
        endif;
    } elseif($popup_display_settings == '3') {
        echo do_shortcode('[ultimate_popup id="'.$global_popup_id.'"]');
    }
}
add_action('wp_footer', 'ultimate_popup_mass_display');


function up_big_text_shortcode( $atts, $content = null  ) {
 
    extract( shortcode_atts( array(
        'colored' => '',
        'regular' => ''
    ), $atts ) );
 
    return '<h1><span>'.$colored.'</span> '.$regular.'</h1>';
}   
add_shortcode('up_big_text', 'up_big_text_shortcode');