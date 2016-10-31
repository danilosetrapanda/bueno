<?php
/*
Style 10
*/

$ultimate_popup .='
#ultimate-popup-'.$post_id.' {background-color:#F8F8F8;border:none;}
#ultimate-popup-'.$post_id.' input[type="submit"] {background-color:'.$ppm_popup_theme_color.'}
#ultimate-popup-'.$post_id.' input[type="submit"]:hover {background-color:#666}
#ultimate-popup-'.$post_id.' .cross-btn-ppm {color: #9a9a9a; top: 10px;}
#ultimate-popup-'.$post_id.':before,
#ultimate-popup-'.$post_id.':after {background-image:url('.plugin_dir_url( __FILE__ ).'img/mail-border.png);position:absolute;left:0;width:100%;height:10px;content:""}
#ultimate-popup-'.$post_id.':before {top:0}
#ultimate-popup-'.$post_id.':after {bottom:-2px}

';