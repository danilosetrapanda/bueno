<?php
/*
Style 5
*/

$ultimate_popup .='#ultimate-popup-'.$post_id.' .ultimate-popup-shortcodes input[type="submit"] {background:'.$ppm_popup_theme_color.'}';

    $ultimate_popup .='
    #ultimate-popup-'.$post_id.' .cross-btn-ppm {color:#fff}
    #ultimate-popup-'.$post_id.' .popup-inner-col-thumb:after {background-color:'.$ppm_popup_theme_color.';}
    ';

$ultimate_popup .='
.ultimate-popup-theme-id-5 {
    background: transparent none repeat scroll 0 0;
    border: medium none;
    padding: 0;
}
.ultimate-popup-theme-id-5 .pop-inner-column { position: relative }
.ultimate-popup-theme-id-5 .popup-inner-col-thumb {
    position: relative;
    overflow: hidden;
    padding: 0 25px;
}
.ultimate-popup-theme-id-5 .popup-inner-col-thumb:after {
    background: #15b7e4 none repeat scroll 0 0;
    content: "";
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: -1;
}
.ultimate-popup-theme-id-5 .popup-inner-col-thumb img {
    display: none
}
.ultimate-popup-theme-id-5 .popup-inner-col-cont {
    position: relative;
    z-index: 9;
    overflow: hidden;
    background: #fff;
}
.ultimate-popup-theme-id-5 .ultimate-popup-inner-title {
    color: #fff;
    padding-top: 27px;
}
.ultimate-popup-theme-id-5 .ultimate-popup-inner-description {
    color: #fff;
    margin-bottom: 25px;
    text-transform: none;
}
.ultimate-popup-theme-id-5 .ultimate-popup-shortcodes {
    background: #fff none repeat scroll 0 0;
    padding: 30px;
    margin-top: 0;
}
.ultimate-popup-theme-id-5 .ultimate-popup-inner { }
.ultimate-popup-theme-id-5 .cross-btn-ppm {
    background: #333 none repeat scroll 0 0;
    border-radius: 50%;
    color: #69676a;
    font-weight: 400;
    height: 40px;
    left: 50%;
    line-height: 40px;
    margin-left: -20px;
    margin-top: -25px;
    width: 40px;
}


@media only screen and (max-width: 767px) { 
    .ultimate-popup-wrapper.ultimate-popup-theme-id-5 .pop-inner-column .popup-inner-col-thumb {
        margin: 0;
        padding: 0 25px;
    }
    .ultimate-popup-wrapper.ultimate-popup-theme-id-5 .ultimate-popup-shortcodes { padding: 0px }
    .ultimate-popup-theme-8-left {
        float: none;
        padding: 20px;
        text-transform: none;
        width: auto;
    }
}
';