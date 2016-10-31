<?php
/*
Theme 3
*/
$ultimate_popup .='
<div class="ultimate-popup-inner">
    <div class="pop-inner-column">      
';

    $ultimate_popup .='<div class="popup-inner-col-thumb">';

        if( has_post_thumbnail() ) :
            $ultimate_popup .=''.get_the_post_thumbnail($post_id, 'full').'';
        else :

            $ultimate_popup .='<img src="'.plugin_dir_url( __FILE__ ).'img/message_icon_2.png" alt="">';

        endif;

    $ultimate_popup .='</div> <div class="popup-inner-col-cont">';


            if($ppm_popup_title) :
            $ultimate_popup .='<h2 class="ultimate-popup-inner-title">'.do_shortcode( $ppm_popup_title ).'</h2>';
            endif;

            if($ppm_popup_desc) :
            $ultimate_popup .='<div class="ultimate-popup-inner-description">'.do_shortcode( $ppm_popup_desc ).'</div>';
            endif;

            $ultimate_popup .='
            <div class="ultimate-popup-shortcodes">
                '.do_shortcode( $ppm_form_code ).'
            </div>                     
            ';


$ultimate_popup .='</div>
    </div>
</div>         
';    