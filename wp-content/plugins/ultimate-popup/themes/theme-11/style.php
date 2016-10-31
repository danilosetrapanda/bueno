<?php
/*
Style 11
*/

$ultimate_popup .='
#ultimate-popup-'.$post_id.' {height:'.$ppm_popup_width.';border-radius:50%;border:none;background-color:'.$ppm_popup_theme_color.';color:#fff}
#ultimate-popup-'.$post_id.' span.cross-btn-ppm{display:none}
#ultimate-popup-'.$post_id.' .ultimate-popup-inner-title{text-transform:uppercase}


#ultimate-popup-'.$post_id.' .ultimate-popup-inner {
    height: 100%;
}
#ultimate-popup-'.$post_id.' .ultimate-popup-inner-table {
    display: table;
    height: 100%;
    width: 100%;
}
#ultimate-popup-'.$post_id.' .ultimate-popup-inner-tablecell {
    display: table-cell;
    padding: 50px;
    vertical-align: middle;
}
#ultimate-popup-'.$post_id.' p.cross-btn-ppm {
    bottom: 35px;
    font-size: 13px;
    font-weight: 400;
    left: 0;
    text-align: center;
    top: auto;
    width: 100%;text-decoration:underline
}

@media only screen and (max-width: 767px) { 
    #ultimate-popup-'.$post_id.' {border-radious:0;padding:0}
    #ultimate-popup-'.$post_id.' .ultimate-popup-inner-tablecell {
      padding: 0;
    }   
    #ultimate-popup-'.$post_id.' p.cross-btn-ppm {
      bottom: auto;
      left: auto;
      margin-top: 20px;
      position: relative;
      top: auto;
    }    
}


';