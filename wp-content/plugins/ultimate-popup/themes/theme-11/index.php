<?php
/*
Theme 11
*/

$ultimate_popup .='<div class="ultimate-popup-inner"><div class="ultimate-popup-inner-table"><div class="ultimate-popup-inner-tablecell">';
    if($ppm_popup_title) :
    $ultimate_popup .='<h2 class="ultimate-popup-inner-title">'.do_shortcode( $ppm_popup_title ).'</h2>';
    endif;

    if($ppm_popup_desc) :
    $ultimate_popup .='<div class="ultimate-popup-inner-description">'.do_shortcode( $ppm_popup_desc ).'</div>';
    endif;

    $ultimate_popup .='
    <div class="ultimate-popup-shortcodes">
        '.do_shortcode( $ppm_form_code ).'
        
        <p data-remodal-action="close" class="cross-btn-ppm">NO THANKS, Let me visit the site</p>
    </div>                     
    ';

$ultimate_popup .='</div></div></div>'; 

