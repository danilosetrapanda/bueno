<?php
/*
Style 9
*/

    $ultimate_popup .='
    #ultimate-popup-'.$post_id.' .ultimate-popup-theme-9-right {background-color:'.$ppm_popup_theme_color.'}
    #ultimate-popup-'.$post_id.' .ultimate-popup-theme-9-left h3 {color:'.$ppm_popup_theme_color.'}
    #ultimate-popup-'.$post_id.' .cross-btn-ppm {color:#fff}    
    ';

$ultimate_popup .='
.ultimate-popup-theme-id-9 { color: #fff }
.ultimate-popup-theme-id-9 .ultimate-popup-shortcodes input[type="text"],
.ultimate-popup-theme-id-9 .ultimate-popup-shortcodes input[type="email"] { color: #333 }
.ultimate-popup-theme-id-9 .ultimate-popup-shortcodes input[type="submit"] { background: #000 none repeat scroll 0 0 }
.ultimate-popup-theme-id-9 .cross-btn-ppm {
    background: #000 none repeat scroll 0 0;
    height: 40px;
    line-height: 40px;
    right: -40px;
    top: 0;
    width: 40px;
}
.ultimate-popup-theme-id-9 { border: medium none }
.ultimate-popup-theme-id-9 h2.ultimate-popup-inner-title { color: #fff }

.ultimate-popup-theme-9-column { position: relative }
.ultimate-popup-theme-9-left {
    float: left;
    padding: 5% 20px;
    text-align: left;
    text-transform: none;
    width: 50%;color:#333
}
.ultimate-popup-theme-9-right {
    color: #fff;
    float: right;
    padding: 25px;
    width: 50%;
}
.ultimate-popup-theme-9-left h2,
.ultimate-popup-theme-9-left h3,
.ultimate-popup-theme-9-left h4,
.ultimate-popup-theme-9-left h5,
.ultimate-popup-theme-9-left h6 {
    font-size: 20px;
    text-transform: uppercase;
    margin: 0 0 15px !important;
}
.ultimate-popup-theme-9-left ul {
    line-height: 35px;
    list-style: outside none none;
    margin: 0;
    padding: 0;
}
.ultimate-popup-theme-9-left ul li {
    background: url('.plugin_dir_url( __FILE__ ) .''.'img/check_icon.png) no-repeat scroll 0 11px;
    padding-left: 30px;
}

@media only screen and (max-width: 767px) { 
    .ultimate-popup-theme-9-left {
        float: none;
        padding: 20px;
        text-transform: none;
        width: auto;
    }
    .ultimate-popup-theme-9-right {
        float: none;
        padding: 40px;
        width: auto;
    }
}
';