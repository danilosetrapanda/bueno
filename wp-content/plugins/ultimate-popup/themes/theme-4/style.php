<?php
/*
Style 4
*/

$ultimate_popup .='#ultimate-popup-'.$post_id.' .ultimate-popup-shortcodes input[type="submit"] {background:'.$ppm_popup_theme_color.'}';
$ultimate_popup .='
#ultimate-popup-'.$post_id.' .cross-btn-ppm:hover, #ultimate-popup-'.$post_id.' .popup-inner-col-thumb {background-color:'.$ppm_popup_theme_color.';color:#fff}
#ultimate-popup-'.$post_id.' .ultimate-popup-inner {border:none}    
';

$ultimate_popup .='
.ultimate-popup-theme-id-4 {
    border: medium none;
    padding: 0;
}
.ultimate-popup-theme-id-4 .pop-inner-column { position: relative }
.ultimate-popup-theme-id-4 .popup-inner-col-thumb {
    height: 100%;
    left: 0;
    padding: 35px;
    position: absolute;
    top: 0;
    width: 30%;
    padding-top: 13%;
}
.ultimate-popup-theme-id-4 .popup-inner-col-cont { padding: 30px 30px 30px 35% }
.ultimate-popup-theme-id-4 .cross-btn-ppm {
    border-radius: 50%;
    font-size: 20px;
    height: 50px;
    line-height: 50px;
    right: -25px;
    top: -25px;
    width: 50px;
    background: #666;
    color: #fff;
}
.ultimate-popup-theme-id-4 .ultimate-popup-shortcodes { margin-top: 30px }

@media only screen and (max-width: 767px) { 
    .ultimate-popup-wrapper.ultimate-popup-theme-id-4 .pop-inner-column .popup-inner-col-thumb {
        padding: 20px;
        position: relative;
        height: auto;
        margin: 0;
    }
}

';