<?php
// Registering accordion metabox
add_action( 'cmb2_admin_init', 'ultimate_popup_register_metabox' );
function ultimate_popup_register_metabox() {
    
    $prefix = '_ultimate_popup_';
    
    
    
	$popup_metabox = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => 'Popup Settings',
		'object_types'  => array( 'ultimate-popup', )
	) );
    
	$popup_metabox->add_field( array(
		'name'        => __( 'Popup theme', 'cmb2' ),
		'id'          => $prefix . 'theme',
		'type'        => 'radio',
        'default'    => '1',
        'options' => array(
            '1' => __( 'Theme 1', 'cmb' ),
            '2' => __( 'Theme 2', 'cmb' ),
            '3' => __( 'Theme 3', 'cmb' ),
            '4' => __( 'Theme 4', 'cmb' ),
            '5' => __( 'Theme 5', 'cmb' ),
            '6' => __( 'Theme 6', 'cmb' ),
            '7' => __( 'Theme 7', 'cmb' ),
            '8' => __( 'Theme 8', 'cmb' ),
            '9' => __( 'Theme 9', 'cmb' ),
            '10' => __( 'Theme 10', 'cmb' ),
            '11' => __( 'Theme 11', 'cmb' ),
            '12' => __( 'Theme 12', 'cmb' ),
        ), 
	) );
        

	$popup_metabox->add_field( array(
		'name'        => __( 'Popup title', 'cmb2' ),
		'desc'        => __( 'Add popup title here.', 'cmb2' ),
		'id'          => $prefix . 'title',
		'type'        => 'text',
	) );

	$popup_metabox->add_field( array(
		'name'        => __( 'Popup description', 'cmb2' ),
		'desc'        => __( 'Popup description will show after the popup title. HTML Allowed', 'cmb2' ),
		'id'          => $prefix . 'description',
		'type'        => 'textarea_small',
	) );

	$popup_metabox->add_field( array(
		'name'        => __( 'Popup form code', 'cmb2' ),
		'desc'        => __( 'Paste popup form code here. You can use HTML, JS or WordPress shortcode here.', 'cmb2' ),
		'id'          => $prefix . 'code',
		'type'        => 'textarea_code',
	) );

	$popup_metabox->add_field( array(
		'name'        => __( 'Popup width', 'cmb2' ),
		'desc'        => __( 'Set popup width here. You can use px or % value here. Don\'t forget to add px or % with values', 'cmb2' ),
		'id'          => $prefix . 'width',
		'type'        => 'text',
		'default'     => '650px',
	) );

	$popup_metabox->add_field( array(
		'name'        => __( 'Popup border width', 'cmb2' ),
		'desc'        => __( 'Set border width here. 0px = no border', 'cmb2' ),
		'id'          => $prefix . 'border_width',
		'type'        => 'text',
		'default'     => '8px',
	) );

    
	$popup_metabox->add_field( array(
		'name'        => __( 'Popup left content', 'cmb2' ),
		'desc'        => __( 'Style 9 supports list item and title. You can add that here. Use headding two & bullet list.', 'cmb2' ),
		'id'          => $prefix . 'left_content',
		'type'        => 'textarea',
		'attributes' => array(
			'data-conditional-id' => $prefix . 'theme',
			'data-conditional-value' => '9',
		)         
	) );  
    
	$popup_metabox->add_field( array(
		'name'        => __( 'Popup theme color', 'cmb2' ),
		'desc'        => __( 'Set popup theme color here. You can select color or use add HEX code here.', 'cmb2' ),
		'id'          => $prefix . 'theme_color',
		'type'        => 'colorpicker',
		'default'        => '#00619e',
	) );  
    
	$popup_metabox->add_field( array(
		'name'        => __( 'Enable overlay?', 'cmb2' ),
		'desc'        => __( 'If you want to enable popup overlay, select yes. Otherwise select no.', 'cmb2' ),
		'id'          => $prefix . 'overlay',
		'type'        => 'select',
		'default'        => '1',
        'options' => array(
            '1' => __( 'Yes', 'cmb' ),
            '2' => __( 'No', 'cmb' ),
        ),         
	) );   
    
    
	$popup_metabox->add_field( array(
		'name'        => __( 'Show cross Button?', 'cmb2' ),
		'desc'        => __( 'If you want to show cross button, select yes. Otherwise select no.', 'cmb2' ),
		'id'          => $prefix . 'cross_btn',
		'type'        => 'select',
		'default'        => '1',
        'options' => array(
            '1' => __( 'Yes', 'cmb' ),
            '2' => __( 'No', 'cmb' ),
        ),         
	) );   
    
	$popup_metabox->add_field( array(
		'name'        => __( 'Popup show on', 'cmb2' ),
		'desc'        => __( '<strong>Warning:</strong> Before close tab will not work on mobile!', 'cmb2' ),
		'id'          => $prefix . 'show_on',
		'type'        => 'select',
		'default'        => '2',
        'options' => array(
            '1' => __( 'On halfway Scroll', 'cmb' ),
            '2' => __( 'Automatically', 'cmb' ),
            '3' => __( 'Before close tab', 'cmb' ),
        ),         
	) ); 
    
	$popup_metabox->add_field( array(
		'name'        => __( 'When popup will load?', 'cmb2' ),
		'desc'        => __( 'Select when popup will load.', 'cmb2' ),
		'id'          => $prefix . 'when',
		'type'        => 'select',
		'default'        => '1',
        'options' => array(
            '1' => __( 'Load after page load', 'cmb' ),
            '2' => __( 'Load before page load', 'cmb' ),
        ),         
	) );  
    
	$popup_metabox->add_field( array(
		'name'        => __( 'Enable cookie?', 'cmb2' ),
		'desc'        => __( 'If you want to use popup with cookie, select yes. After that, configure cookie expiry below.', 'cmb2' ),
		'id'          => $prefix . 'cookie',
		'type'        => 'select',
		'default'        => '1',
        'options' => array(
            '1' => __( 'Yes - Display popup on first visit only', 'cmb' ),
            '2' => __( 'No - Display popup everytime', 'cmb' ),
        ),         
	) );  
    
	$popup_metabox->add_field( array(
		'name'        => __( 'Popup time unit', 'cmb2' ),
		'desc'        => __( 'Select popup time unit here.', 'cmb2' ),
		'id'          => $prefix . 'time_unit',
		'type'        => 'select',
		'default'        => '1',
        'options' => array(
            '1' => __( 'Day', 'cmb' ),
            '2' => __( 'Hour', 'cmb' ),
            '3' => __( 'Minute', 'cmb' ),
        ), 
		'attributes' => array(
			'required' => true, 
			'data-conditional-id' => $prefix . 'cookie',
			'data-conditional-value' => '1',
		)        
	) );   
    
	$popup_metabox->add_field( array(
		'name'        => __( 'Popup time value', 'cmb2' ),
		'desc'        => __( 'Select popup time value here.', 'cmb2' ),
		'id'          => $prefix . 'time_value',
		'type'        => 'text',
		'default'        => '1',
		'attributes' => array(
			'required' => true, 
			'data-conditional-id' => $prefix . 'cookie',
			'data-conditional-value' => '1',
		)        
	) ); 
    
	$popup_metabox->add_field( array(
		'name'        => __( 'Popup position', 'cmb2' ),
		'desc'        => __( 'On scroll halfway, where from popup will show? Select that here.', 'cmb2' ),
		'id'          => $prefix . 'position',
		'type'        => 'select',
		'default'        => '1',
        'options' => array(
            '1' => __( 'Bottom right', 'cmb' ),
            '2' => __( 'Bottom left', 'cmb' ),
        ), 
		'attributes' => array(
			'required' => true, 
			'data-conditional-id' => $prefix . 'show_on',
			'data-conditional-value' => '1',
		)        
	) );     
    
    
};


add_action( 'admin_head', 'ultimate_popup_metabox_style' );
function ultimate_popup_metabox_style() {

	
	global $post_type;

    
	if( 'ultimate-popup' != $post_type )
		return;

    
	?>
   
    <script>
        jQuery(document).ready(function($){
            
            
$('.cmb2-id--ultimate-popup-show-on select option[selected=selected]').each(function(){
    if($(this).val() == '3')
    { // or this.value == 'volvo'
        $(".cmb2-id--ultimate-popup-cookie").addClass("popup-selected-halfway-scroll");
    }
    else
    {
        $(".cmb2-id--ultimate-popup-cookie").removeClass("popup-selected-halfway-scroll");
    }    
});   
            
$(".cmb2-id--ultimate-popup-show-on select").change(function(){

    if($(this).val() == "3")
    {
        $(".cmb2-id--ultimate-popup-cookie").addClass("popup-selected-halfway-scroll");
    }
    else
    {
        $(".cmb2-id--ultimate-popup-cookie").removeClass("popup-selected-halfway-scroll");
    }
});  
        
$(".postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label").click(function(){
    $(".cmb2-id--ultimate-popup-description").removeClass("ultimate-popup-style-12-selected");
    
}); 
$(".postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme12").click(function(){
    $(".cmb2-id--ultimate-popup-description").addClass("ultimate-popup-style-12-selected");
});             
            

<?php 

$up_metabox_logic = '
    if($(this).val() == \'9\') {
        $(".cmb2-id--ultimate-popup-left-content").addClass("show-cmb2-id--ultimate-popup-left-content");
        
    } else {
        $(".cmb2-id--ultimate-popup-left-content").removeClass("show-cmb2-id--ultimate-popup-left-content");
    }  
'; 
    
$up_show_on = '
    if($(this).val() == 1) {
        $(".cmb2-id--ultimate-popup-position").addClass("show-cmb2-id--ultimate-popup-position");
    } else {
        $(".cmb2-id--ultimate-popup-position").removeClass("show-cmb2-id--ultimate-popup-position");
    }
';
    
$up_cookie_logic = '
    if($(this).val() == 1) {
        $(".cmb2-id--ultimate-popup-time-unit").addClass("show-cmb2-id--ultimate-popup-time-unit");
        $(".cmb2-id--ultimate-popup-time-value").addClass("show-cmb2-id--ultimate-popup-time-value");
    } else {
        $(".cmb2-id--ultimate-popup-time-unit").removeClass("show-cmb2-id--ultimate-popup-time-unit");
        $(".cmb2-id--ultimate-popup-time-value").removeClass("show-cmb2-id--ultimate-popup-time-value");
    }
';    
    
?>
            
$('.cmb2-id--ultimate-popup-theme input[type=radio][name=_ultimate_popup_theme]:checked').each(function(){
    <?php echo $up_metabox_logic; ?>
});   
            
$(".cmb2-id--ultimate-popup-theme input[type=radio][name=_ultimate_popup_theme]").change(function(){
    <?php echo $up_metabox_logic; ?>
});
            
$('.cmb2-id--ultimate-popup-show-on select option[selected=selected]').each(function(){
    <?php echo $up_show_on; ?>
});   
            
$(".cmb2-id--ultimate-popup-show-on select").change(function(){
    <?php echo $up_show_on; ?>
}); 
            
$('.cmb2-id--ultimate-popup-cookie select option[selected=selected]').each(function(){
    <?php echo $up_cookie_logic; ?>
});   
            
$(".cmb2-id--ultimate-popup-cookie select").change(function(){
    <?php echo $up_cookie_logic; ?>
}); 


            
        });
    </script> 
   
    
    <style>
        
        .cmb2-id--ultimate-popup-left-content, .cmb2-id--ultimate-popup-position, .cmb2-id--ultimate-popup-time-unit, .cmb2-id--ultimate-popup-time-value {display:none}
        .show-cmb2-id--ultimate-popup-left-content, .show-cmb2-id--ultimate-popup-position, .show-cmb2-id--ultimate-popup-time-unit, .show-cmb2-id--ultimate-popup-time-value {display:block}
        
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-th,
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td {
            float: none;
            width:auto
        }
        
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list {overflow: hidden;}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li{background: #f1f1f1;
float: left;
margin-bottom: 20px;
margin-right: 20px;
padding: 10px;
text-align: center;
width: 230px;}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li:hover,
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li.active{}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label{background-size:100% auto;
display: block;
height: 160px;
margin-top: 10px;
text-indent: -9999px;background-repeat:no-repeat;background-position:center center}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme1]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_1.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme2]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_2.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme3]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_3.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme4]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_4.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme5]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_5.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme6]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_6.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme7]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_7.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme8]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_8.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme9]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_9.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme10]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_10.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme11]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_11.png)}
        .postbox-container .cmb2-id--ultimate-popup-theme .cmb-td ul.cmb2-radio-list li label[for=_ultimate_popup_theme12]{background-image:url(../wp-content/plugins/ultimate-popup/img/theme_12.png)}
        
        
        .cmb2-id--ultimate-popup-cookie.popup-selected-halfway-scroll::before {
            background-color: #fcf8e3;
            color: #8a6d3b;
            content: "You are using popup by Before close tab. That need to enalbe cookie. Make sure you selected Yes here.";
            display: block;
            font-size: 12px;
            margin-bottom: 10px;
            margin-top: -15px;
            padding: 5px;
        }  
        .cmb2-id--ultimate-popup-description.ultimate-popup-style-12-selected::before {
            background-color: #fcf8e3;
            color: #8a6d3b;
            content: "Style 12 need shortcode for display bigger title. Shortcode is: [up_big_text colored=\"40%\" regular=\"off\"]. You can change content with yours. See documentation for more details.";
            display: block;
            font-size: 12px;
            margin-bottom: 10px;
            margin-top: -15px;
            padding: 5px;
        }        
        
    </style>
	<?php
}