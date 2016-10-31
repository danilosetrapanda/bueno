<?php
/*
Style 7
*/

$ultimate_popup .='#ultimate-popup-'.$post_id.' .ultimate-popup-shortcodes input[type="submit"] {background:'.$ppm_popup_theme_color.'}';

    $ultimate_popup .='
    #ultimate-popup-'.$post_id.' .cross-btn-ppm {background-color:'.$ppm_popup_theme_color.';color:#fff}
    #ultimate-popup-'.$post_id.':before, #ultimate-popup-'.$post_id.':after {background-color:'.$ppm_popup_theme_color.'}
    #ultimate-popup-'.$post_id.' .cross-btn-ppm:hover {background-color: #666;color: #fff}    
    ';

$ultimate_popup .='
.ultimate-popup-theme-id-7 {
    border: medium none;
    padding: 40px 30px;
}
.ultimate-popup-theme-id-7:before,
.ultimate-popup-theme-id-7:after {
    content: "";
    height: 180px;
    left: 0;
    margin-top: -90px;
    position: absolute;
    top: 50%;
    width: 8px;
}
.ultimate-popup-theme-id-7:after {
    left: auto;
    right: 0;
}
.ultimate-popup-theme-id-7 .cross-btn-ppm {
    border-radius: 50%;
    font-size: 18px;
    height: 40px;
    left: 50%;
    line-height: 40px;
    margin-left: -20px;
    margin-top: -25px;
    width: 40px;
}
.ultimate-popup-theme-id-7 .ultimate-popup-shortcodes { margin-top: 30px }
';