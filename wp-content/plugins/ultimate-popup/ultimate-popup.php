<?php
/*
Plugin Name: Ultimate Popup
Plugin URI: http://ultimatepopup.com
Description: This plugin will enable an awesome side popup in your wordpress site. 
Author: Perfect Point Marketing
Author URI: http://perfectpointmarketing.com
Version: 2.5
*/

// Including plugin assets
include_once('admin/up-plugin-assets.php');

// Including CMB2
if ( file_exists( dirname( __FILE__ ) . '/libs/cmb2/init.php' ) ) {
	require_once 'libs/cmb2/init.php';
} else {
	add_action( 'admin_notices', 'ultimate_popup_cmb2_missing' );
}

// Popup fallback message
function ultimate_popup_cmb2_missing() { ?>
<div class="error">
	<p><?php _e( 'CMB2 Example Plugin is missing CMB2!', 'ultimate-popup' ); ?></p>
</div>
<?php }


// Popup metaboxe
require_once("admin/admin-popup-metabox.php");

// Popup Options
require_once("admin/admin-popup-options.php");

// Registering Popup custom post
require_once("admin/admin-custom-post.php");

// Popup custom messages
require_once("admin/admin-custom-messages.php");

// Popup admin shortcode generator
require_once("admin/admin-shortcode-generator.php");

// Registering popup shortcodes
require_once("admin/ultimate-popup-shortcodes.php");