<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Advance_Flat_Rate_Shipping_Method_For_Woocommerce
 * @subpackage Advance_Flat_Rate_Shipping_Method_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advance_Flat_Rate_Shipping_Method_For_Woocommerce
 * @subpackage Advance_Flat_Rate_Shipping_Method_For_Woocommerce/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advance-flat-rate-shipping-method-for-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advance-flat-rate-shipping-method-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function woocommerce_inactive_notice_extra_flat_rate(){

		if ( current_user_can( 'activate_plugins' ) ) :
			if ( ! Advance_Flat_Rate_Shipping_Method_For_Woocommerce::is_woocommerce_active() ) : ?>
				<div id="message" class="error">
				<p><?php
				printf( esc_html__( '%1$sAdvance Flat Rate Shipping Method For WooCommerce is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for WooCommerce Extra Flat Rate to work. Please %5$sinstall & activate WooCommerce &raquo;%6$s',  'advance-flat-rate-shipping-method-for-woocommerce' ), '<strong>', '</strong>', '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">', '</a>' ); ?>
				</p>
				</div>
			<?php endif; ?>
		<?php endif;
	}

	public function extra_flat_rate_admin_init_own() {
		require_once 'partials/advance-flat-rate-shipping-method-for-woocommerce-admin-display.php';
		//	$admin = new WC_Settings_Extra_Shipping_Methods();
	}

	/**
     * Register Extra shipping method tab in shipping section 
     *
     */
	public function extra_shipping_method_load () {

		require_once 'partials/woo-extra-shipping-method-url.php';
		require_once 'partials/class-wc-extra-shipping-method.php';
		require_once 'partials/woo-extra-flat-rate-shipping-method.php';

		Extra_Shipping_Method::setup();
	}

	public function extra_flat_rate_add_body_class( $classes ) {
		$is_extra_method_section = !empty( $_GET['section'] ) ? $_GET['section'] : '';
		if( $is_extra_method_section == 'wc_extra_shipping_method') {
			$classes = $is_extra_method_section;
			return $classes;
		}
	}

	public function welcome_flate_rate_screen_do_activation_redirect() {
		// if no activation redirect
		if ( ! get_transient( '_welcome_screen_activation_redirect_data' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_welcome_screen_activation_redirect_data' );

		// if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		// Redirect to extra cost welcome  page
		wp_safe_redirect( add_query_arg( array( 'page' => 'woo-extra-flat-rate-about&tab=about' ), admin_url( 'index.php' ) ) );

	}

	public  function welcome_pages_screen() {
		add_dashboard_page(
		'Advance Flat Rate Shipping Method Dashboard',
		'Advance Flat Rate Shipping Method Dashboard',
		'read', 'woo-extra-flat-rate-about',
		array( &$this,'welcome_screen_content')
		);
	}

	public function admin_css() {
		wp_enqueue_style($this->plugin_name . 'welcome-page', plugin_dir_url(__FILE__) . 'css/woo-extra-flate-rate-admin.css', array(), $this->version, 'all');
	}

	public  function welcome_screen_content() {?>
		<div class="wrap about-wrap">
			<h1 style="font-size: 2.1em;"><?php printf( __( 'Welcome to Advance Flat Rate Shipping Method For WooCommerce 1.1', 'advance-flat-rate-shipping-method-for-woocommerce' ) ); ?></h1>
				<div class="about-text woocommerce-about-text">
					<?php
					$message = '';
					printf( __( '%s Explore your WooCommerce Shop and products with wide variety of Advance Flat Rate Shipping Method options.', 'advance-flat-rate-shipping-method-for-woocommerce' ), $message,  $this->version );
					?>
				</div>
			
			<?php
			$setting_tabs_wc = apply_filters('extra_flate_rate_setting_tab', array("about" => "Overview", "other_plugins" => "Checkout our other plugins" ));
			$current_tab_wc = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
			$aboutpage = isset($_GET['page'])
			?>
			<h2 id="woo-extra-cost-tab-wrapper" class="nav-tab-wrapper">
				<?php
				foreach ($setting_tabs_wc as $name => $label)
				echo '<a  href="' . home_url('wp-admin/index.php?page=woo-extra-flat-rate-about&tab=' . $name) . '" class="nav-tab ' . ( $current_tab_wc == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
				?>
			</h2>
			
			 <?php
			 foreach ($setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue) {
			 	switch ($setting_tabkey_wc) {
			 		case $current_tab_wc:
			 			do_action('woo_extra_flate_'.$current_tab_wc);
			 			break;
			 	}
       		 }?>
			<hr />
			<div class="return-to-dashboard">
				<a href="<?php echo home_url('/wp-admin/admin.php?page=wc-settings&tab=shipping&section=wc_extra_shipping_method'); ?>"><?php _e( 'Go to Advance Flat Rate Shipping Method Settings', 'advance-flat-rate-shipping-method-for-woocommerce' ); ?></a>
			</div>
		</div>
	<?php
	}
	/**
	 * Extra flate rate overview welcome page content function
	 *
	 */
	public function woo_extra_flate_about() { ?>
		<div class="changelog">
			</br>
			<div class="changelog about-integrations">
				<div class="wc-feature feature-section col three-col">
					<div>
						<p><?php _e( 'Advance Flat Rate Shipping Method For WooCommerce plugin provides you an interface in Woo commerce setting section from admin side. So admin can add Multiple Shipping option (extra_shipping). Advance Flat Rate Shipping Method For WooCommerce Plugin extend the shipping concept of Woo commerce and provide a facility to add Multiple Shipping option based on multiple aspects like "Specific product" , "Specific category" , "Specific Tag" ,"Specific classes" , and much more options. Admin also remove any existing shipping from the back end. Admin set options will be displayed from the front side. So the user can choose shipping method based on that.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ); ?></p>
					</div>
					<div>
						<p><?php _e( 'This plugin is for those users who want to use multiple shipping with different aspects on the website. By using this plugin, you can add multiple shipping with different aspects in your woo Commerce website as well as you can add/remove it as per your requirement. ', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php 
	}
	/**
	 * Extra flate rate other plugin  welcome page content function
	 *
	 */
	public function woo_extra_flate_other_plugins() { ?>
	
	<div class="plug-containter">
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/analytic_buddy_press.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/bpcustomerio/">Analytics for BuddyPress by Customer.io</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/analttics_woocommerce.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/analytics-for-woocommerce-by-customerio/">Analytics for WooCommerce by Customer.io</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/custom_post_term_shotcode.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/get-post-custom-taxonomy-term-shortcode/">Get Post Custom Taxonomy Term Shortcode</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/mass_page_post_creator.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/mass-pagesposts-creator/">Mass Pages/Posts Creator</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/page_visit_counter.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/page-visit-counter/">Page Visit Counter</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/woo_braintree_pay_gateway.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/woo-braintree-payment-gateway/">WooCommerce Braintree Payment Gateway</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/woo_checkout_digitak_good.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/woo-checkout-for-digital-goods/">WooCommerce Checkout for Digital Goods</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/woo_ecommerce_track_google_fb.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/woo-ecommerce-tracking-for-google-and-facebook/">WooCommerce Ecommerce Tracking for Google and Facebook</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/woo_extra_cost.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/woo-extra-cost/">WooCommerce Extra Cost</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/woo_extra_flat_rate.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/woo-extra-flat-rate/">WooCommerce Extra Flat Rate Shipping</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/woo_instragram.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/woo-instagram/">WooCommerce Instagram</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/woo_no_order_alert.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/no-order-alert/">WooCommerce No Order Alert</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/woo-shipping_display_mode.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/woo-shipping-display-mode/">WooCommerce Shipping Display Mode</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/woo_social_share_disc_coupon.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/woo-social-share-discount-coupon/">WooCommerce Social Share Discount Coupon</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/wp_cate_tag_rating.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/wp-category-tag-ratings/">Wp Category Tag Ratings</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/wp_ecomm_extra_flat_rate.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://wordpress.org/plugins/wpsc-extra-flat-rate/">WP eCommerce Extra Flat Rate Shipping</a></div>
		</div>
		<div class="contain-section">
			<div class="contain-img"><img src="<?php echo  EXTRA_FLAT_PLUGIN_URL.'admin/images/advance_menu_manager.png'; ?>"></div>
			<div class="contain-title"><a target="_blank" href="http://codecanyon.net/item/advance-menu-manager/15275037?s_rank=1">Advance Menu Manager</a></div>
		</div>
	</div>
	
<?php }

/**
	 * Remove the Extra flate rate menu in dashboard
	 *
	 */
public function welcome_screen_remove_menus() {
	remove_submenu_page( 'index.php', 'woo-extra-flat-rate-about' );
}

public function woocommerce_get_sections_shipping_custom( $sections ){
	global $woocommerce;
	if ( defined( 'WOOCOMMERCE_VERSION' ) && version_compare( WOOCOMMERCE_VERSION, '2.6', '>=' ) ) {

		$sections = array(
		''        => __( 'Shipping Zones', 'woocommerce' ),
		'options' => __( 'Shipping Options', 'woocommerce' ),
		'classes' => __( 'Shipping Classes', 'woocommerce' ),
		'wc_extra_shipping_method' => __( 'Advance Flat Rate Shipping', 'woocommerce' )
		);

		if ( ! defined( 'WC_INSTALLING' ) ) {
			// Load shipping methods so we can show any global options they may have
			$shipping_methods = WC()->shipping->load_shipping_methods();

			foreach ( $shipping_methods as $method ) {
				if ( ! $method->has_settings() ) {
					continue;
				}
				$title = empty( $method->method_title ) ? ucfirst( $method->id ) : $method->method_title;

				if(  $method->plugin_id != 'extra_') {
					$sections[ strtolower( $method->id ) ] = esc_html( $title );
				}
			}
		}

		return $sections;
	}else {
		return $sections;
	}

}

}