<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Advance_Flat_Rate_Shipping_Method_For_Woocommerce
 * @subpackage Advance_Flat_Rate_Shipping_Method_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advance_Flat_Rate_Shipping_Method_For_Woocommerce
 * @subpackage Advance_Flat_Rate_Shipping_Method_For_Woocommerce/public
 * @author     Multidots <inquiry@multidots.in>
 */
class Advance_Flat_Rate_Shipping_Method_For_Woocommerce_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advance-flat-rate-shipping-method-for-woocommerce-public.css', array(), $this->version, 'all' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jquery-ui.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome/css/font-awesome.css' ,array(), $this->version );
		wp_enqueue_style( 'font-awesome-min', plugin_dir_url( __FILE__ ) . 'css/font-awesome/css/font-awesome.min.css',array(), $this->version );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advance-flat-rate-shipping-method-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * BN code added 
	 */
	function paypal_bn_code_filter($paypal_args) {
		$paypal_args['bn'] = 'Multidots_SP';
		return $paypal_args;
	}

	function woocommerce_locate_template_extra_shipping_custom( $template, $template_name, $template_path ) {

		global $woocommerce;

		$_template = $template;

		if ( ! $template_path ) $template_path = $woocommerce->template_url;

		$plugin_path  = advance_flat_rate_shipping_method_for_woocommerce_plugin_path() . '/woocommerce/';

		$template = locate_template(
		array(
		$template_path . $template_name,
		$template_name
		)
		);

		// Modification: Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) )
		$template = $plugin_path . $template_name;

		// Use default template
		if ( ! $template )
		$template = $_template;
		// Return what we found
		return $template;
	}

	public function bulky_woocommerce_cart_shipping_packages($packages) {
		// Reset the packages
		$packages = array();
		$ship_via = array();

		$manager = Extra_Shipping_Method::instance(true);
		$profiles = $manager->profiles();

		foreach ( $profiles as $key => $method ) {
			$ship_via[] = $method->id;
		}

		// Bulky items
		$bulky_items   = array();
		$regular_items = array();

		// Sort bulky from regular
		foreach ( WC()->cart->get_cart() as $item ) {
			if ( $item['data']->needs_shipping() ) {
				if ( $item['data']->get_shipping_class() == 'free' ) {
					$bulky_items[] = $item;
				} else {
					$regular_items[] = $item;
				}
			}
		}

		// Put inside packages
		if ( $bulky_items ) {
			$packages[] = array(
			'ship_via'        => $ship_via,
			'contents'        => $bulky_items,
			'contents_cost'   => array_sum( wp_list_pluck( $bulky_items, 'line_total' ) ),
			'applied_coupons' => WC()->cart->applied_coupons,
			'destination'     => array(
			'country'   => WC()->customer->get_shipping_country(),
			'state'     => WC()->customer->get_shipping_state(),
			'postcode'  => WC()->customer->get_shipping_postcode(),
			'city'      => WC()->customer->get_shipping_city(),
			'address'   => WC()->customer->get_shipping_address(),
			'address_2' => WC()->customer->get_shipping_address_2()
			)
			);
		}
		if ( $regular_items ) {
			$packages[] = array(
			'contents'        => $regular_items,
			'contents_cost'   => array_sum( wp_list_pluck( $regular_items, 'line_total' ) ),
			'applied_coupons' => WC()->cart->applied_coupons,
			'destination'     => array(
			'country'   => WC()->customer->get_shipping_country(),
			'state'     => WC()->customer->get_shipping_state(),
			'postcode'  => WC()->customer->get_shipping_postcode(),
			'city'      => WC()->customer->get_shipping_city(),
			'address'   => WC()->customer->get_shipping_address(),
			'address_2' => WC()->customer->get_shipping_address_2()
			)
			);
		}

		return $packages;
	}

	/**
	 * This function loops over cart items, and moves any item with shipping class 'special-class' into a new package. 
	 * The new package in this example only takes flat rate shipping.
	 */
	public function split_special_shipping_class_items( $packages ) {

		//$ship_via = array('extra_shipping');
		$ship_via = array();
		$array = array();
		$manager = Extra_Shipping_Method::instance(true);
		$profiles = $manager->profiles();
		$get_master_class = get_option('extra_extra_shipping_settings',true);
		$get_serialize = maybe_unserialize($get_master_class);

		if (isset($get_serialize) && !empty($get_serialize['master_class']) && ($get_serialize['master_class'] != '0')){
			$selected_class_name = $get_serialize['master_class'];

			foreach ( $profiles as $key => $method ) {
				$ship_via[] = $method->id;
			}
			$a1= false;
			$afsm_is_shipping_enable = get_option('afsm_is_shipping_enable');
			// Enabled logic based on condition
			$is_available       = false;
			$has_coupon         = false;
			$has_met_min_amount = false;
			$max_amount = false;
			$max_quantity = false;
			$min_extra_quantity = false;
			$min_weight = false;
			$max_weight = false;
			$has_ship_based_on_countries = 'no';
			$has_ship_based_on_product = 'no';
			$has_ship_based_on_category = 'no';
			$has_ship_based_on_tag = 'no';
			$has_ship_based_on_sku = 'no';
			$has_ship_based_on_user_base = 'no';
			$has_ship_based_on_user_role_base = 'no';
			$has_met_min_amount = 'no';
			$min_extra_quantity = 'no';
			$max_amount = 'no';
			$max_quantity = 'no';
			$min_weight = 'no';
			$max_weight = 'no';
			$has_coupon = 'no';
			$is_passed = array();
			//Check if is Country exist
			if ( 'specific' == $get_serialize['availability'] ) {
				$ship_to_countries = $get_serialize['countries'];
			} else {
				$ship_to_countries = array_keys( WC()->countries->get_shipping_countries() );
			}


			if ( isset( $ship_to_countries ) && !empty( $ship_to_countries ) ) {
				if ( is_array( $ship_to_countries ) && !in_array( $packages[0]['destination']['country'], $ship_to_countries ) ) {
					$is_passed['has_ship_based_on_countries'] = 'no';
					$has_ship_based_on_countries = 'no';
				} else {
					$is_passed['has_ship_based_on_countries'] = 'yes';
					$has_ship_based_on_countries = 'yes';
				}
			}

			//Check if is product exist
			if ( is_array( $get_serialize['product_base'] ) && isset( $get_serialize['product_base'] ) && isset( $packages[0]['contents'] ) ) {
				$product_base_array = $get_serialize['product_base'];

				$cart_product_id_array =  array();
				foreach ( $packages[0]['contents'] as $package_product ) {
					$cart_product_id_array[] = $package_product['product_id'];
				}

				$product_array_intersect = array();
				$product_array_intersect = array_intersect($product_base_array,$cart_product_id_array);
				$product_array_intersect_count = count($product_array_intersect);

				if ( isset( $product_array_intersect ) && !empty( $product_array_intersect ) && $product_array_intersect_count >= 1 ) {
					$is_passed['has_ship_based_on_product'] = 'yes';
					$has_ship_based_on_product = 'yes';
				} else {
					$is_passed['has_ship_based_on_product'] = 'no';
					$has_ship_based_on_product = 'no';
				}

			}

			//Check if is category exist
			if ( is_array( $get_serialize['category_base'] ) && isset( $get_serialize['category_base'] ) && isset( $packages[0]['contents'] ) ) {
				$category_base_array = $get_serialize['category_base'];

				$cart_category_id_array =  array();
				foreach ( $packages[0]['contents'] as $package_product ) {
					$wp_category_id_exist = wp_get_post_terms($package_product['product_id'],'product_cat',array('fields'=>'ids'));
					if ( isset( $wp_category_id_exist ) && !empty( $wp_category_id_exist ) && is_array( $wp_category_id_exist ) ) {
						$cart_category_id_array[] = $wp_category_id_exist[0];
					}
				}

				$cart_category_id_count = count($cart_category_id_array);
				if ( isset( $cart_category_id_array ) && !empty( $cart_category_id_array ) && is_array( $cart_category_id_array ) && $cart_category_id_count != 0 ){
					$cart_category_id_array = array_unique($cart_category_id_array);
				}

				$category_array_intersect = array();
				$category_array_intersect = array_intersect($category_base_array,$cart_category_id_array);
				$category_array_intersect_count = count($category_array_intersect);

				if ( isset( $category_array_intersect ) && !empty( $category_array_intersect ) && $category_array_intersect_count >= 1 ) {
					$has_ship_based_on_category = 'yes';
					$is_passed['has_ship_based_on_category'] = 'yes';
				} else {
					$has_ship_based_on_category = 'no';
					$is_passed['has_ship_based_on_category'] = 'no';
				}
			}

			//Check if is tag exist
			if ( is_array( $get_serialize['tag_base'] ) && isset( $get_serialize['tag_base'] ) && isset( $packages[0]['contents'] ) ) {
				$tag_base_array = $get_serialize['tag_base'];

				$cart_tag_id_array =  array();
				foreach ( $packages[0]['contents'] as $package_product ) {
					$wp_tag_id_exist = wp_get_post_terms($package_product['product_id'],'product_tag',array('fields'=>'ids'));
					if ( isset( $wp_tag_id_exist ) && !empty( $wp_tag_id_exist ) && is_array( $wp_tag_id_exist ) ) {
						$cart_tag_id_array[] = $wp_tag_id_exist[0];
					}
				}

				$cart_tag_id_count = count($cart_tag_id_array);
				if ( isset( $cart_tag_id_array ) && !empty( $cart_tag_id_array ) && is_array( $cart_tag_id_array ) && $cart_tag_id_count != 0 ){
					$cart_tag_id_array = array_unique($cart_tag_id_array);
				} else {
					$cart_tag_id_array = array();
				}

				$tag_array_intersect = array();
				$tag_array_intersect = array_intersect($tag_base_array,$cart_tag_id_array);
				$tag_array_intersect_count = count($tag_array_intersect);

				if ( isset( $tag_array_intersect_count ) && !empty( $tag_array_intersect_count ) && $tag_array_intersect_count >= 1 ) {
					$has_ship_based_on_tag = 'yes';
					$is_passed['has_ship_based_on_tag'] = 'yes';
				} else {
					$has_ship_based_on_tag = 'no';
					$is_passed['has_ship_based_on_tag'] = 'no';
				}
			}

			//Check if is sku exist
			if ( is_array( $get_serialize['sku_base'] ) && isset( $get_serialize['sku_base'] ) && isset( $packages[0]['contents'] ) ) {
				$sku_base_array = $get_serialize['sku_base'];

				$cart_sku_id_array =  array();
				foreach ( $packages[0]['contents'] as $package_product ) {
					$wp_sku_id_exist = get_post_meta($package_product['product_id'],'_sku',true);
					if ( isset( $wp_sku_id_exist ) && !empty( $wp_sku_id_exist ) ) {
						$cart_sku_id_array[] = $wp_sku_id_exist;
					}
				}

				$cart_sku_id_count = count($cart_sku_id_array);
				if ( isset( $cart_sku_id_array ) && !empty( $cart_sku_id_array ) && is_array( $cart_sku_id_array ) && $cart_sku_id_count != 0 ){
					$cart_sku_id_array = array_unique($cart_sku_id_array);
				} else {
					$cart_sku_id_array = array();
				}

				$cart_sku_array_intersect = array();
				$cart_sku_array_intersect = array_intersect($sku_base_array,$cart_sku_id_array);
				$cart_sku_array_intersect_count = count($cart_sku_array_intersect);

				if ( isset( $cart_sku_array_intersect_count ) && !empty( $cart_sku_array_intersect_count ) && $cart_sku_array_intersect_count >= 1 ) {
					$has_ship_based_on_sku = 'yes';
					$is_passed['has_ship_based_on_sku'] = 'yes';
				} else {
					$has_ship_based_on_sku = 'no';
					$is_passed['has_ship_based_on_sku'] = 'no';
				}
			}


			//Check if is user exist then apply shipping
			if ( is_array( $get_serialize['user_base'] ) && isset( $get_serialize['user_base'] ) ) {

				if ( ! is_user_logged_in() ){
					return false;
				}

				$user_base_array = $get_serialize['user_base'];
				$current_user_id = get_current_user_id();

				if ( is_array( $user_base_array ) && in_array( $current_user_id, $user_base_array ) ) {
					$has_ship_based_on_user_base = 'yes';
					$is_passed['has_ship_based_on_user_base'] = 'yes';
				} else {
					$has_ship_based_on_user_base = 'no';
					$is_passed['has_ship_based_on_user_base'] = 'no';
				}
			}

			//Check if is User role exist
			if ( is_array( $get_serialize['user_role_base'] ) && isset( $get_serialize['user_role_base'] ) ) {

				if ( ! is_user_logged_in() ){
					return false;
				}

				$current_user_roles = array();
				$user_role_base_array = $get_serialize['user_role_base'];
				$current_user_id = get_current_user_id();
				$current_user_info = get_userdata($current_user_id);
				$current_user_roles = $current_user_info->roles;

				if ( isset( $current_user_roles ) && !empty( $current_user_roles ) && is_array( $current_user_roles ) ){
					foreach ($current_user_roles as $current_user_role ) {
						$current_user_roles[] = $current_user_role;
					}
				} else {
					$current_user_roles[] = $current_user_roles;
				}


				$current_user_role_count = count($current_user_roles);
				if ( isset( $current_user_role_count ) && !empty( $current_user_role_count ) && $current_user_role_count != 0 ){
					$current_user_roles = array_unique($current_user_roles);
				} else {
					$current_user_roles = array();
				}

				$current_user_role_intersect = array();
				$current_user_role_intersect = array_intersect($user_role_base_array,$current_user_roles);
				$current_user_role_intersect_count = count($current_user_role_intersect);

				if ( isset( $current_user_role_intersect_count ) && !empty( $current_user_role_intersect_count ) && $current_user_role_intersect_count >= 1 ) {
					$has_ship_based_on_user_role_base = 'yes';
					$is_passed['has_ship_based_on_user_role_base'] = 'yes';
				} else {
					$has_ship_based_on_user_role_base = 'no';
					$is_passed['has_ship_based_on_user_role_base'] = 'no';
				}
			}



			if ( is_array( $get_serialize['coupon'] ) && isset( $get_serialize['coupon'] ) && !empty( $get_serialize['coupon'] )) {
				$coupons = WC()->cart->get_coupons();
				if ( isset($coupons) && !empty($coupons) ) {
					foreach ( $coupons as $code => $coupon ) {
						if ( $coupon->is_valid() && isset($coupon) && !empty($coupon ) ) {
							$is_passed['coupon'] = 'yes';
						}
					}
				} else {
					$is_passed['coupon'] = 'no';
				}
			}

			if ( !empty( $get_serialize['min_amount'] ) && isset( $get_serialize['min_amount'] ) && isset( WC()->cart->cart_contents_total ) ) {
				if ( WC()->cart->prices_include_tax ) {
					$total = WC()->cart->cart_contents_total + array_sum( WC()->cart->taxes );
				} else {
					$total = WC()->cart->cart_contents_total;
				}

				if ( $total <= $get_serialize['min_amount'] ) {
					$has_met_min_amount = true;
					$is_passed['has_met_min_amount'] = 'yes';
				} else {
					$has_met_min_amount = false;
					$is_passed['has_met_min_amount'] = 'no';
				}
			}

			//Check if min quantiy option choosen

			if ( !empty( $get_serialize['min_extra_quantity'] ) && isset( $get_serialize['min_extra_quantity'] ) ) {

				$woo_cart_array = array();
				$woo_cart_array = WC()->cart->get_cart();
				$cart_item_quantity = 0;

				foreach ( $woo_cart_array as $woo_cart_item_key => $woo_cart_item ) {
					$cart_item_quantity += $woo_cart_item['quantity'];
				}

				if ( $cart_item_quantity != 0 && $cart_item_quantity <= $get_serialize['min_extra_quantity'] ) {
					$min_extra_quantity = true;
					$is_passed['min_extra_quantity'] = 'yes';
				} else {
					$min_extra_quantity = false;
					$is_passed['min_extra_quantity'] = 'no';
				}
			}

			//Check if max amount option choosen
			if (  isset( $get_serialize['max_amount'] ) && !empty( $get_serialize['max_amount'] ) ) {

				if ( WC()->cart->prices_include_tax ) {
					$total = WC()->cart->cart_contents_total + array_sum( WC()->cart->taxes );
				} else {
					$total = WC()->cart->cart_contents_total;
				}

				if ( isset( $total ) && !empty( $total ) && $total >= $get_serialize['max_amount'] ) {
					$max_amount = true;
					$is_passed['max_amount'] = 'yes';
				} else {
					$max_amount = false;
					$is_passed['max_amount'] = 'no';
				}
			}

			//Check if max quantiy option choosen
			if ( !empty( $get_serialize['max_quantity'] ) && isset( $get_serialize['max_quantity'] ) ) {

				$woo_cart_array = array();
				$woo_cart_array = WC()->cart->get_cart();
				$cart_item_quantity = 0;

				foreach ( $woo_cart_array as $woo_cart_item_key => $woo_cart_item ) {
					$cart_item_quantity += $woo_cart_item['quantity'];
				}

				if ( $cart_item_quantity >= $get_serialize['max_quantity']) {
					$max_quantity = true;
					$is_passed['max_quantity'] = 'yes';
				} else {
					$max_quantity = false;
					$is_passed['max_quantity'] = 'no';
				}
			}

			//Check if min weight option choosen
			if ( !empty( $get_serialize['min_weight'] ) && isset( $get_serialize['min_weight'] ) ) {

				$woo_cart_array = array();
				$woo_cart_array = WC()->cart->get_cart();
				$woo_cart_item_quantity = 0;
				$cart_item_weight = 0;

				foreach ( $woo_cart_array as $woo_cart_item_key => $woo_cart_item ) {


					if ( $woo_cart_item['data']->weight != 0 ) {
						$woo_cart_item_quantity = $woo_cart_item['quantity'];
						$cart_item_weight += $woo_cart_item['data']->weight * $woo_cart_item_quantity;
					}
				}

				if ( $cart_item_weight != 0 && $cart_item_weight <= $get_serialize['min_weight'] ) {
					$min_weight = true;
					$is_passed['min_weight'] = 'yes';
				} else {
					$min_weight = false;
					$is_passed['min_weight'] = 'no';
				}
			}

			//Check if max weight option choosen
			if ( !empty( $get_serialize['max_weight'] ) && isset( $get_serialize['max_weight'] ) ) {

				$woo_cart_array = array();
				$woo_cart_array = WC()->cart->get_cart();
				$cart_item_weight = 0;
				$woo_cart_item_quantity = 0;

				foreach ( $woo_cart_array as $woo_cart_item_key => $woo_cart_item ) {
					if ( $woo_cart_item['data']->weight != 0 ) {
						$woo_cart_item_quantity = $woo_cart_item['quantity'];
						$cart_item_weight += $woo_cart_item['data']->weight * $woo_cart_item_quantity;
					}
				}

				if ( $cart_item_weight >= $get_serialize['max_weight'] ) {
					$max_weight = true;
					$is_passed['max_weight'] = 'yes';
				} else {
					$max_weight = false;
					$is_passed['max_weight'] = 'no';
				}
			}

			if ( isset( $is_passed ) && !empty( $is_passed ) && is_array( $is_passed ) ) {
				foreach ( $is_passed as $passed_key => $yes_passed ) {

					if ( $yes_passed != 'yes' ) {
						$is_available = false;
						break;
					}else {
						$is_available = true;
						//break;

					}

				}

			}

			$found_item                     = false;
			$special_class                  = array($selected_class_name);  // edit this with the slug of your shippig class
			$new_package                    = current( $packages );
			$new_package['contents']        = array();
			$new_package['contents_cost']   = 0;
			$new_package['applied_coupons'] = array();
			$new_package['ship_via']        = array('extra_shipping'); // Only allow flat rate for items in special class

			foreach ( WC()->cart->get_cart() as $item_key => $item ) {
				// Is the product in the special class?
				if ( $item['data']->needs_shipping() && in_array($item['data']->get_shipping_class(),$special_class) ) {
					//if ( $item['data']->needs_shipping() && $special_class === $item['data']->get_shipping_class() ) {

					$obj = new WC_Extra_Shipping_Method();
					$flag = false;
					$flag = $obj->is_available($flag);
					$found_item   = $is_available;
					$new_package['contents'][ $item_key ]  = $item;
					$new_package['contents_cost']         += $item['line_total'];

					// Remove from original package
					$packages[0]['contents_cost'] = $packages[0]['contents_cost'] - $item['line_total'];
					unset( $packages[0]['contents'][ $item_key ] );

					// If there are no items left in the previous package, remove it completely.
					if ( empty( $packages[0]['contents'] ) ) {
						unset( $packages[0] );
					}
				}

			}

			if ( $found_item && $found_item == true) {
				$packages[] = $new_package;
			}
		}
		return $packages;
	}
}