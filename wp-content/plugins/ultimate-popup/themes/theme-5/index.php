<?php
/*
Theme 5
*/


$ultimate_popup .='<div class="ultimate-popup-inner">';

if( ! has_post_thumbnail() ) :
$ultimate_popup .='<div class="pop-inner-column no-thumb-ppm-attached">';
else :
$ultimate_popup .='<div class="pop-inner-column">';
endif;

$ultimate_popup .='<div class="popup-inner-col-thumb"> '.get_the_post_thumbnail($post_id, 'full').'';

            if($ppm_popup_title) :
            $ultimate_popup .='<h2 class="ultimate-popup-inner-title">'.do_shortcode( $ppm_popup_title ).'</h2>';
            endif;

            if($ppm_popup_desc) :
            $ultimate_popup .='<div class="ultimate-popup-inner-description">'.do_shortcode( $ppm_popup_desc ).'</div>';
            endif;
$ultimate_popup .='</div>';

            $ultimate_popup .='
            <div class="popup-inner-col-cont">
                <div class="ultimate-popup-shortcodes">
                    '.do_shortcode( $ppm_form_code ).'
                </div>                    
            </div>                        
            ';   
$ultimate_popup .='
    </div>
</div>                     
'; 