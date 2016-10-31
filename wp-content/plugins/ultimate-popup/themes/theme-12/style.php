<?php
/*
Style 12
*/

$ultimate_popup .='
#ultimate-popup-'.$post_id.' {border:none;background-color:'.$ppm_popup_theme_color.';color:#fff;}
#ultimate-popup-'.$post_id.' .ultimate-popup-inner-title {
  font-size: 16px;
  font-weight: 400;
  text-transform: uppercase;
  margin-bottom: 0;
}


#ultimate-popup-'.$post_id.' .ultimate-popup-inner h1 {
    font-size: 100px;
    line-height: 115px;
    text-transform: uppercase;
    margin:0;color: #fff
}
#ultimate-popup-'.$post_id.' .ultimate-popup-inner h1 span {
    color: #000;
}

#ultimate-popup-'.$post_id.' .cross-btn-ppm {
    color: #333;
}
#ultimate-popup-'.$post_id.' .ultimate-popup-shortcodes input[type="submit"], #ultimate-popup-'.$post_id.' .ultimate-popup-shortcodes button {
  background-color: #333;
}

@media only screen and (max-width: 767px) { 
    #ultimate-popup-'.$post_id.' .ultimate-popup-inner h1 {
      font-size: 35px;
      line-height: 55px;
    }    
}

';