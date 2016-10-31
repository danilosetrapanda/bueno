<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Advance_Flat_Rate_Shipping_Method_For_Woocommerce
 * @subpackage Advance_Flat_Rate_Shipping_Method_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Advance_Flat_Rate_Shipping_Method_For_Woocommerce
 * @subpackage Advance_Flat_Rate_Shipping_Method_For_Woocommerce/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb,$woocommerce;
		if( !in_array( 'woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins'))) && !is_plugin_active_for_network( 'woocommerce/woocommerce.php' )   ) { 
			wp_die( "<strong> Advance Flat Rate Shipping Method For WooCommerce</strong> Plugin requires <strong>WooCommerce</strong> <a href='".get_admin_url(null, 'plugins.php')."'>Plugins page</a>." );
		} else {
			set_transient( '_welcome_screen_activation_redirect_data', true, 30 );
		}
	}
}
