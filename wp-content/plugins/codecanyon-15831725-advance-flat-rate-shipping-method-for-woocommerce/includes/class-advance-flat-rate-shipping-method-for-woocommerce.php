<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Advance_Flat_Rate_Shipping_Method_For_Woocommerce
 * @subpackage Advance_Flat_Rate_Shipping_Method_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Advance_Flat_Rate_Shipping_Method_For_Woocommerce
 * @subpackage Advance_Flat_Rate_Shipping_Method_For_Woocommerce/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Advance_Flat_Rate_Shipping_Method_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	/**
	 * Check if plugin is active
	 *
	 * @var unknown_type
	 */
	private static $active_plugins;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'advance-flat-rate-shipping-method-for-woocommerce';
		$this->version = '1.0.3';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		/**
         * Add action links
         * http://stackoverflow.com/questions/22577727/problems-adding-action-links-to-wordpress-plugin
         */
        $prefix = is_network_admin() ? 'network_admin_' : '';
        add_filter("{$prefix}plugin_action_links_" . EXTRA_FLAT_PLUGIN_BASENAME, array($this, 'plugin_action_links'), 10, 4);

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Advance_Flat_Rate_Shipping_Method_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advance-flat-rate-shipping-method-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advance-flat-rate-shipping-method-for-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-advance-flat-rate-shipping-method-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-advance-flat-rate-shipping-method-for-woocommerce-public.php';

		$this->loader = new Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Advance_Flat_Rate_Shipping_Method_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Advance_Flat_Rate_Shipping_Method_For_Woocommerce_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );
				
		if ( ! Advance_Flat_Rate_Shipping_Method_For_Woocommerce::is_woocommerce_active() ) {
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'woocommerce_inactive_notice_extra_flat_rate' );
			return;
		}
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action('admin_init', $plugin_admin, 'extra_flat_rate_admin_init_own');
		$this->loader->add_action('plugins_loaded', $plugin_admin, 'extra_shipping_method_load',0);
		$this->loader->add_action('admin_body_class', $plugin_admin,'extra_flat_rate_add_body_class',10,1 );
		$this->loader->add_action( 'admin_init', $plugin_admin,'welcome_flate_rate_screen_do_activation_redirect' );
		//admin menu intilization hooks
		$this->loader->add_action('admin_menu',$plugin_admin, 'welcome_pages_screen');
		//add hooks for admin menu
		$this->loader->add_action( 'admin_head', $plugin_admin,'welcome_screen_remove_menus' );
		$this->loader->add_action('woo_extra_flate_about', $plugin_admin, 'woo_extra_flate_about');
		$this->loader->add_action('woo_extra_flate_functionality', $plugin_admin, 'woo_extra_flat_rate_functionality');
		$this->loader->add_action('woo_extra_flate_other_plugins', $plugin_admin, 'woo_extra_flate_other_plugins');
		$this->loader->add_action('woocommerce_get_sections_shipping', $plugin_admin, 'woocommerce_get_sections_shipping_custom',10,1);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter('woocommerce_paypal_args',  $plugin_public,'paypal_bn_code_filter', 99, 1);
		$this->loader->add_filter( 'woocommerce_locate_template' ,$plugin_public,'woocommerce_locate_template_extra_shipping_custom' ,10,3);
		
		$get_set = get_option('extra_extra_shipping_settings');
		$get_set = maybe_unserialize($get_set);
		$get_set_en = $get_set['enabled'];
		if (isset($get_set_en) && $get_set_en == 'yes') {
			$this->loader->add_filter( 'woocommerce_cart_shipping_packages' ,$plugin_public,'split_special_shipping_class_items');
		}
			//$this->loader->add_filter( 'woocommerce_cart_shipping_packages' ,$plugin_public,'remove_free_shipping_items');
		//$this->loader->add_filter( 'woocommerce_package_rates' ,$plugin_public,'hid_shipping_method',10,2);
	}
	
	
	/**
     * Return the plugin action links.  This will only be called if the plugin
     * is active.
     *
     * @since 1.0.0
     * @param array $actions associative array of action names to anchor tags
     * @return array associative array of plugin action links
     */
    public function plugin_action_links($actions, $plugin_file, $plugin_data, $context) {
        $custom_actions = array(
            'configure' => sprintf('<a href="%s">%s</a>', admin_url('/admin.php?page=wc-settings&tab=shipping&section=wc_extra_shipping_method'), __('Settings', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG)),
            'docs' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://codecanyon.net/item/advance-flat-rate-shipping-method-for-woocommerce/15831725', __('Docs', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG)),
            'support' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://codecanyon.net/item/advance-flat-rate-shipping-method-for-woocommerce/15831725/comments', __('Support', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG))
         );

        // add the links to the front of the actions list
        return array_merge($custom_actions, $actions);
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	
	
	public static function init() {

		Advance_Flat_Rate_Shipping_Method_For_Woocommerce::$active_plugins = (array) get_option( 'active_plugins', array() );
		
		if ( is_multisite() )
		Advance_Flat_Rate_Shipping_Method_For_Woocommerce::$active_plugins = array_merge( Advance_Flat_Rate_Shipping_Method_For_Woocommerce::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}

	public static function woocommerce_active_check_extra_flat_rate() {

		if ( ! Advance_Flat_Rate_Shipping_Method_For_Woocommerce::$active_plugins ) Advance_Flat_Rate_Shipping_Method_For_Woocommerce::init();

		return in_array( 'woocommerce/woocommerce.php', Advance_Flat_Rate_Shipping_Method_For_Woocommerce::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', Advance_Flat_Rate_Shipping_Method_For_Woocommerce::$active_plugins );
	}
	
	public static function is_woocommerce_active() {
		return Advance_Flat_Rate_Shipping_Method_For_Woocommerce::woocommerce_active_check_extra_flat_rate();
	}

}