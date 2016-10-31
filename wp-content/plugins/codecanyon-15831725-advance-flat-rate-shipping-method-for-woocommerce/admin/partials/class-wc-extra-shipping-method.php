<?php
/**
 * Flat Rate Shipping Method.
 *
 */
class WC_Extra_Shipping_Method extends WC_Shipping_Method {

	const PLUGIN_PREFIX = 'extra_';
	public $plugin_id = self::PLUGIN_PREFIX;
	public $name;
	public $profile_id;
	public $weight;
	public $subtotal;
	public $subtotalWithTax;
	public $weightRate;
	public $shippingClassRates;
	public $priceClamp;
	public $_extra_stub = false;
	protected $fee_cost = '';

	/**
	 * Constructor.
	 */
	public function __construct($profileId = null) {

		$manager = Extra_Shipping_Method::instance();

		// Force loading profiles when called from WooCommerce 2.3.9- save handler
		// to activate process_admin_option() with appropriate hook
		if (!isset($profileId)) {
			$manager->profiles();
		}

		$this->id = $manager->find_suitable_id($profileId);
		$this->profile_id = $profileId;
		$this->method_title       = __( 'Advance Flat Rate Shipping Method', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG );
		$this->method_description = __( 'Extra Flate Rate Shipping lets you charge a fixed rate for shipping.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG );

		$this->init();

	}

	/**
	 * Initialize flat rate shipping.
	 */
	public function init() {
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->title        = $this->get_option( 'title' );
		$this->availability = $this->get_option( 'availability' );
		$this->tax_status   = $this->get_option( 'tax_status' );
		$this->shipping_description   = $this->get_option( 'shipping_description' );
		$this->cost         = $this->get_option( 'cost' );
		$this->type         = $this->get_option( 'type', 'class' );
		$this->options      = $this->get_option( 'options', false ); // @deprecated in 2.4.0
		$this->extra_shipping_requires		= $this->get_option( 'extra_shipping_requires' );

		//Selection base shipping
		$this->countries		= $this->get_option( 'countries' );
		$this->product_base		= $this->get_option( 'product_base' );
		$this->category_base	= $this->get_option( 'category_base' );
		$this->tag_base			= $this->get_option( 'tag_base' );
		$this->sku_base			= $this->get_option( 'sku_base' );
		$this->user_base		= $this->get_option( 'user_base' );
		$this->user_role_base	= $this->get_option( 'user_role_base' );
		$this->coupon	= $this->get_option( 'coupon' );

		//Get different condition option
		$this->min_amount 		= $this->get_option( 'min_amount', 0 );
		$this->max_amount 		= $this->get_option( 'max_amount', 0 );

		$this->min_extra_quantity    	= $this->get_option( 'min_extra_quantity' );
		$this->max_quantity    	= $this->get_option( 'max_quantity' );

		$this->min_weight    	= $this->get_option( 'min_weight', 0 );
		$this->max_weight    	= $this->get_option( 'max_weight', 0 );

	}

	public function get_wp_option_name() {
		return Extra_Shipping_Method::getRuleSettingsOptionName($this->id, $this->plugin_id);
	}

	/**
	 * Initialize Settings Form Fields.
	 */
	public function init_form_fields() {
		$weight_unit = get_option('woocommerce_weight_unit');
		$cost_desc = __( 'This amount will be added to the sub total of the order when customer selects this shipping method. You can enter direct amount or make it dynamic using parameters. Below are the parameters you can use: [qty] = number of items, [cost] = cost of items,<br> [fee percent="10" min_fee="20"] = Percentage based fee.Below are some of the examples:"10.00"  - To charge flat 10.00 shipping charge"10.00 * [qty]" - To charge 10.000 per quantity in the cart. It will be 50.00 if the cart has 5 qty. "[fee percent="10" min_fee="20"]" - This means charge 10 percent o sub total of order, minimum 20 charge will be applicable. ', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG );


		$this->form_fields = array(
		'enabled' => array(
		'title' 		=> __( 'Enable/Disable', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'checkbox',
		'label' 		=> __( 'Enable this shipping method  (This method will be visible to customers only if it is enabled.)', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'default' 		=> 'yes',
		),
		'title' => array(
		'title' 		=> __( 'Enter Shipping Method Name ', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ).'<span class="requiredfields">*</span>',
		'type' 			=> 'text',
		'description' 	=> __( 'This name will be visible to the customer at the time of checkout. This should convey the purpose of the charges you are applying to the order. For example "Ground Shipping",  "Express Shipping Flat Rate", "Christmas Next Day Shipping" etc', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'default'       => sprintf($this->profile_id != 'extra_shipping' ? __('extra_shipping: %s') : '%s', $this->profile_id),
		'custom_attributes'	=> array(
		'required' => true
		)
		),
		'shipping_description' => array(
		'title' 		=> __( 'Enter Short Description about this Shipping method ', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'textarea',
		'description' 	=> __( 'Enter few words about this shipping method. This will help your customers to understand the shipping charges in more detailed way. ', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'default'       => '',
		'placeholder' => __('Enter method description', 'placeholder', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG),
		),
		'cost' => array(
		'title' 		=> __( 'Enter Amount to Charge ', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ).'<span class="requiredfields">*</span>',
		'type' 			=> 'text',
		'placeholder'	=> '',
		'description'	=> $cost_desc,
		'default'		=> '',
		),
		'availability' => array(
		'title' 		=> __( 'Select Countries Allowed', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'select',
		'default' 		=> 'all',
		'class'			=> 'availability wc-enhanced-select',
		'options'		=> array(
		'all' 		=> __( 'All allowed countries', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'specific' 	=> __( 'Specific Countries', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		),
		),
		'countries' => array(
		'title' 		=> __( 'Select Specific Countries', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'multiselect',
		'class'			=> 'wc-enhanced-select',
		'css'			=> 'width: 450px;',
		'default' 		=> '',
		'options'		=> WC()->countries->get_shipping_countries(),
		'custom_attributes' => array(
		'data-placeholder' => __( 'Select some countries', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG )
		)
		),
		'product_base' => array(
		'title' 		=> __( 'Select Specific Products', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'multiselect',
		'class'			=> 'wc-enhanced-select',
		'options'		=> $this->get_product_list_own(),
		'css'			=> 'width: 450px;',
		'default' 		=> '',
		'description' 	=> __( 'Use this feature when you want to make the shipping method visible only for specific products. For example, if you select "Basketball" as product, the current shipping method will only be visible if the customer has added "Basketball" product in his current cart.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'custom_attributes' => array(
		'data-placeholder' => __( 'Select some products', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG )
		)
		),
		'category_base' => array(
		'title' 		=> __( 'Select Specific Category', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'multiselect',
		'class'			=> 'wc-enhanced-select',
		'options'		=> $this->get_category_list_own(),
		'css'			=> 'width: 450px;',
		'default' 		=> '',
		'description' 	=> __( 'Using this feature you can restrict your shipping method to be vislble only for products of specific category.  For example, you can create a new method like "Expensive Shipping $50".  This method should be visible only when the cart has any product from "Glass Products" category.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'custom_attributes' => array(
		'data-placeholder' => __( 'Select some categories', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG )
		)
		),
		'tag_base' => array(
		'title' 		=> __( 'Select Specific Tag', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'multiselect',
		'class'			=> 'wc-enhanced-select',
		'options'		=> $this->get_tag_list(),
		'css'			=> 'width: 450px;',
		'default' 		=> '',
		'description' 	=> __( 'Using this feature you can restrict your shipping method to be vislble only for products from specific tag.  For example, you can create a new method like "Special shipping $100".  This method should be visible only when the cart has any product having "fragile" tag.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'custom_attributes' => array(
		'data-placeholder' => __( 'Select some tags', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG )
		)
		),
		'sku_base' => array(
		'title' 		=> __( 'Select Specific SKU', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'multiselect',
		'class'			=> 'wc-enhanced-select',
		'options'		=> $this->get_sku_list(),
		'css'			=> 'width: 450px;',
		'default' 		=> '',
		'description' 	=> __( 'Using this feature you can restrict your shipping method to be vislble only for products of specific SKU.  For example, you can create a new method like "Christmas Urgent $50".  This method should be visible only when the cart has a product of "CHRISTMAS007" SKU.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'custom_attributes' => array(
		'data-placeholder' => __( 'Select some sku', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG )
		)
		),
		'user_base' => array(
		'title' 		=> __( 'Select Specific User', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'multiselect',
		'class'			=> 'wc-enhanced-select',
		'options'		=> $this->get_user_list(),
		'css'			=> 'width: 450px;',
		'default' 		=> '',
		'description' 	=> __( 'When you want to make a shipping method visible only for selected user you can use this feature. For example, let us say you want to create a shipping method for your favorite buyer  called "Special Shipping for JamesSmith - $10" for all his bulk orders. In this case, you have to chose james username in this textbox.  Then this shipping method will only be visible to JamesSmith.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'custom_attributes' => array(
		'data-placeholder' => __( 'Select some user', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG )
		)
		),
		'user_role_base' => array(
		'title' 		=> __( 'Select Specific User Role', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'multiselect',
		'class'			=> 'wc-enhanced-select',
		'options'		=> $this->get_user_role_list(),
		'css'			=> 'width: 450px;',
		'default' 		=> '',
		'description' 	=> __( 'When you want to make a shipping method visible only for users from a specific group/role you can use this feature. For example, let us say you want to create a shipping method for all your wholesale customers called "Wholesale Shipping - $100" for all bulk orders.   Then you can select the "wholesale" in this textbox.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'custom_attributes' => array(
		'data-placeholder' => __( 'Select some user role', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG )
		)
		),
		'coupon' => array(
		'title' 		=> __( 'Select Coupon', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'multiselect',
		'class'			=> 'wc-enhanced-select',
		'options'		=> $this->get_coupon_list(),
		'css'			=> 'width: 450px;',
		'default' 		=> '',
		'description' 	=> __( 'Using this feature you can restrict the shipping method only to be visible if the specified coupon is used on the current order. For example, let us say you want to offer a discounted shipping rate for your email campaign. You can create this method like "CyberMonday 2016 Shipping $5". and this will be only visible if customer uses the coupon you shared on your email campaign "CyberMonday2016".  You can achieve this using this feature.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'custom_attributes' => array(
		'data-placeholder' => __( 'Select some Coupon', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG )
		)
		),

		'master_class' => array(
		'title' 		=> __( 'Select Class', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'select',
		'class'         => 'wc-enhanced-select',
		'default' 		=> '',
		'description'   => __( 'Select class if the product in cart available with selected class name then forcefully this shipping will be applicable.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'options'		=> $this->get_advance_flat_rate_class()
		),
		'min_amount' => array(
		'title' 		=> __( 'Order amount specific Shipping method - If less than', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'price',
		'placeholder'	=> wc_format_localized_price( 0 ),
		'description' 	=> __( 'Here you can enter maximum order amount to for which the shipping method  should be visible. For example, you can specify this like 500. In this case, the shipping method will only be visilbe to customer if the order amount is less than and equal to 500. For orders more than 500 it will not be visible.  You can use this feature, when you want to skip shipping charge when the order amount is higher than what you specified.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'default' 		=> '0',
		'class'         => 'min_amount',
		),
		'max_amount' => array(
		'title' 		=> __( 'Order amount specific Shipping method - If greater than', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'price',
		'placeholder'	=> wc_format_localized_price( 0 ),
		'description' 	=> __( 'Here you can enter minimum order amount after which the shipping method should be visible. For example, you can specify this like 500. In this case, the shipping method will only be visilbe to customer if the order amount is greater than and equal to 500. For orders smaller than 500 it will not be visible.  You can use this feature, when you want to skip shipping charge when the order amount is less than what you specified.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'default' 		=> '0',
		'class'         => 'max_amount',
		),
		'min_extra_quantity' => array(
		'title' 		=> __( 'Quantity specific Shipping method - If less than', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'number',
		'placeholder'	=> wc_format_localized_price( 0 ),
		'description' 	=> __( 'Here you can enter maximum cart quantity for which the shipping method should be visible. For example, you can specify this like 5. In this case, the shipping method will only be visilbe to customer if the cart has less than and equal to 5 qauntity in it. For cart having more than 5 quantity it will not be visible.  You can use this feature, when you want to skip shipping charge when the cart quantity is higher than what you specified.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'default' 		=> '0',
		'class'         => 'min_extra_quantity',
		),
		'max_quantity' => array(
		'title' 		=> __( 'Quantity specific Shipping method - If greater than', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'number',
		'placeholder'	=> wc_format_localized_price( 0 ),
		'description' 	=> __( 'Here you can enter minimum cart quantity after which the shipping method should be visible. For example, you can specify this like 5. In this case, the shipping method will only be visilbe to customer if the cart quantity is greater than and equal to 5. For cart having less than 5 quantity it will not be visible.  You can use this feature, when you want to skip shipping charge when the cart quantity is less than what you specified.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'default' 		=> '0',
		'class'         => 'max_quantity',
		),
		'min_weight' => array(
		'title' 		=> __( 'Weight specific Shipping method - If less than', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'number',
		'description' 	=> __('Here you can enter maximum cart/product weight for which the shipping method should be visible. For example, you can specify this like 500. In this case, the shipping method will only be visilbe to customer if the cart weight total is less than and equal to 500. For cart having greater than 500 weight it will not be visible.  You can use this feature, when you want to skip shipping charge when the cart total weight is higher than what you specified.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'class'         => 'min_weight',
		'placeholder' =>__($weight_unit,AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG)
		),
		'max_weight' => array(
		'title' 		=> __( 'Weight specific Shipping method - If greater than', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'number',
		'description' 	=> __( 'Here you can enter minimum cart weiht after which the shipping method should be visible. For example, you can specify this like 500. In this case, the shipping method will only be visilbe to customer if the cart total weight is greater than and equal to 500. For cart having less than 500 weight it will not be visible.  You can use this feature, when you want to skip shipping charge when the cart total weight is less than what you specified.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'default'       => '',
		'class'         => 'max_weight',
		'placeholder' =>__($weight_unit,AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG)
		),

		'tax_status' => array(
		'title' 		=> __( 'Should the shipping amount be taxable?', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'type' 			=> 'select',
		'class'         => 'wc-enhanced-select',
		'default' 		=> 'taxable',
		'description'   => __( 'If Tax Status is "Yes" then tax rate will apply on Shipping Cost as well. You can Mangage your Tax rate by <a href="' . admin_url( 'admin.php?page=wc-settings&tab=tax' ) . '">Click here</a>', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'options'		=> array(
		'taxable' 	=> __( 'YES', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
		'none' 		=> __( 'NO', 'Tax status', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG )
		)
		)

		);


		$shipping_classes = WC()->shipping->get_shipping_classes();

		if ( ! empty( $shipping_classes ) ) {
			$this->form_fields[ 'class_costs' ] = array(
			'title'			=> __( 'Shipping Class Costs', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
			'type'			=> 'title',
			'description'   => sprintf( __( 'These costs can optionally be added based on the %sproduct shipping class%s.', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ), '<a href="' . admin_url( 'edit-tags.php?taxonomy=product_shipping_class&post_type=product' ) . '">', '</a>' )
			);
			foreach ( $shipping_classes as $shipping_class ) {
				if ( ! isset( $shipping_class->term_id ) ) {
					continue;
				}
				$this->form_fields[ 'class_cost_' . $shipping_class->term_id ] = array(
				'title'       => sprintf( __( '"%s" Shipping Class Cost', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ), esc_html( $shipping_class->name ) ),
				'type'        => 'text',
				'placeholder' => __( 'N/A', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
				'description' => $cost_desc,
				'default'     => $this->get_option( 'class_cost_' . $shipping_class->slug ), // Before 2.5.0, we used slug here which caused issues with long setting names
				);
			}
			$this->form_fields[ 'no_class_cost' ] = array(
			'title'       => __( 'No Shipping Class Cost', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
			'type'        => 'text',
			'placeholder' => __( 'N/A', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
			'description' => $cost_desc,
			'default'     => '',
			);
			$this->form_fields[ 'type' ] = array(
			'title' 		=> __( 'Calculation Type', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
			'type' 			=> 'select',
			'class'         => 'wc-enhanced-select',
			'default' 		=> 'class',
			'options' 		=> array(
			'class' 	=> __( 'Per Class: Charge shipping for each shipping class individually', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
			'order' 	=> __( 'Per Order: Charge shipping for the most expensive shipping class', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ),
			),
			);
		}

	}

	/**
	 * Function for select product list
	 *
	 */
	public function get_product_list(){

		global $wpdb,$post;

		$product_array = array();

		$get_all_products = new WP_Query( array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1
		) );

		if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts ) ){

			foreach ( $get_all_products->posts  as $get_all_product ) {
				$product_array[$get_all_product->ID] = $get_all_product->post_title;
			}

		}

		return apply_filters( 'woocommerce_extra_shipping_product_list', $product_array );
	}

	/**
	 * Function for get Coupon list
	 *
	 */
	public function get_coupon_list(){

		global $wpdb,$post;

		$coupon_array = array();

		$get_all_coupon = new WP_Query( array(
		'post_type' => 'shop_coupon',
		'post_status' => 'publish',
		'posts_per_page' => -1
		) );

		if ( isset( $get_all_coupon->posts ) && !empty($get_all_coupon->posts ) ){

			foreach ( $get_all_coupon->posts  as $get_all_coupon ) {
				$coupon_array[$get_all_coupon->ID] = $get_all_coupon->post_title;
			}

		}

		return apply_filters( 'woocommerce_extra_shipping_coupon_list', $coupon_array );
	}

	/**
	 * get all shipping class name
	 *
	 */
	public function get_advance_flat_rate_class(){

		$shipping_classes = WC()->shipping->get_shipping_classes();
		$shipping_class_name = array();

		foreach ($shipping_classes as $shipping_classes_key) {
			$shipping_class_name[$shipping_classes_key->slug] = $shipping_classes_key->name;
		}

		array_unshift($shipping_class_name,"Select Class");

		return $shipping_class_name;
	}

	/**
	 * Function for select category list
	 *
	 */
	public function get_category_list(){

		global $wpdb,$post;

		$taxonomy     = 'product_cat';
		$post_status  = 'publish';
		$orderby      = 'name';
		$hierarchical = 1;      // 1 for yes, 0 for no
		$empty        = 0;

		$args = array(
		'post_type'    => 'product',
		'post_status'  => $post_status,
		'taxonomy'     => $taxonomy,
		'orderby'      => $orderby,
		'hierarchical' => $hierarchical,
		'hide_empty'   => $empty,
		'posts_per_page' => -1

		);

		$get_all_categories = get_categories( $args );

		$category_array = array();

		if ( isset( $get_all_categories ) && !empty( $get_all_categories ) ){

			foreach ( $get_all_categories  as $get_all_category ) {
				$category_array[$get_all_category->term_id] = $get_all_category->cat_name;
			}

		}

		return apply_filters( 'woocommerce_extra_shipping_category_list', $category_array );
	}

	/**
	 * Function for select cat list
	 *
	 */
	public function get_tag_list(){

		global $wpdb,$post,$product;

		$taxonomy     = 'product_tag';
		$orderby      = 'name';
		$hierarchical = 1;      // 1 for yes, 0 for no
		$empty        = 0;

		$args = array(
		'post_type'    => 'product',
		'post_status'  => 'publish',
		'taxonomy'     => $taxonomy,
		'orderby'      => $orderby,
		'hierarchical' => $hierarchical,
		'hide_empty'   => $empty,
		'posts_per_page' => -1
		);

		$get_all_tags = get_categories( $args );

		$tag_array = array();

		if ( isset( $get_all_tags ) && !empty( $get_all_tags ) ){

			foreach ( $get_all_tags  as $get_all_tag ) {
				$tag_array[$get_all_tag->term_id] = $get_all_tag->name;
			}

		}

		return apply_filters( 'woocommerce_extra_shipping_category_list', $tag_array );
	}


	public function get_product_list_own(){

		global $wpdb,$post;

		$product_array = array();

		$get_all_products = new WP_Query( array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1
		) );

		if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts ) ){

			foreach ( $get_all_products->posts  as $get_all_product ) {
				$product_array[$get_all_product->ID] = '#'.$get_all_product->ID.' - '.$get_all_product->post_title;
			}

		}

		return apply_filters( 'woocommerce_extra_shipping_product_list', $product_array );
	}

	/**
	 * Function for select category list
	 *
	 */
	public function get_category_list_own(){

		global $wpdb,$post;

		$taxonomy     = 'product_cat';
		$post_status  = 'publish';
		$orderby      = 'name';
		$hierarchical = 1;      // 1 for yes, 0 for no
		$empty        = 0;

		$args = array(
		'post_type'    => 'product',
		'post_status'  => $post_status,
		'taxonomy'     => $taxonomy,
		'orderby'      => $orderby,
		'hierarchical' => $hierarchical,
		'hide_empty'   => $empty,
		'posts_per_page' => -1

		);

		$get_all_categories = get_categories( $args );

		$category_array = array();

		if ( isset( $get_all_categories ) && !empty( $get_all_categories ) ){

			foreach ( $get_all_categories  as $get_all_category ) {

				$category = get_category($get_all_category->term_id);
				if ($category->parent > 0) {
					$category_array[$get_all_category->term_id] = '#'.get_cat_name($category->parent).'&nbsp;&#x2192;&nbsp;'.$get_all_category->cat_name;
				}else {
					$category_array[$get_all_category->term_id] = $get_all_category->cat_name;
				}
			}

		}

		return apply_filters( 'woocommerce_extra_shipping_category_list', $category_array );
	}


	/**
	 * Get all SKU list from all products
	 *
	 * @return unknown
	 */
	public function get_sku_list(){

		global $wpdb,$post,$product;

		$sku_array = array();

		$get_all_products = new WP_Query( array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1
		) );

		if ( isset( $get_all_products->posts ) && !empty( $get_all_products->posts ) ) {

			foreach ( $get_all_products->posts  as $get_all_product ) {

				$product_sku = get_post_meta($get_all_product->ID,'_sku',true);
				if ( isset( $product_sku ) && !empty( $product_sku ) ) {
					$sku_array[$product_sku] = $product_sku;
				}
			}
		}

		if ( isset( $sku_array ) && !empty( $sku_array ) && is_array($sku_array) ) {
			$sku_array = array_unique($sku_array);
		}

		return apply_filters( 'woocommerce_extra_shipping_category_list', $sku_array );

	}


	/**
	 * Function for select user list
	 *
	 */
	public function get_user_list(){

		global $wpdb,$post,$user;

		$get_all_users = get_users();

		$user_array = array();

		if ( isset( $get_all_users ) && !empty( $get_all_users ) ){

			foreach ( $get_all_users  as $get_all_user ) {
				$user_array[$get_all_user->data->ID] = $get_all_user->data->user_login;
			}

		}

		return apply_filters( 'woocommerce_extra_shipping_user_list', $user_array );
	}

	/**
	 * Get User role list
	 *
	 * @return unknown
	 */
	public function get_user_role_list(){

		global $wp_roles,$user;

		$user_roles_array = array();

		if ( isset( $wp_roles->roles ) && !empty( $wp_roles->roles ) ){

			foreach ( $wp_roles->roles  as $user_role_key => $get_all_role ) {
				$user_roles_array[$user_role_key] = $get_all_role['name'];
			}

		}

		return apply_filters( 'woocommerce_extra_shipping_user_role_list', $user_roles_array );
	}

	/**
	 * Save Extra Shipping settings
	 *
	 */
	public function save_extra_shipping_settings(){

		global $current_section;

		$wc_shipping = WC_Shipping::instance();

		if ( ! $current_section ) {
			WC_Admin_Settings::save_fields( $this->get_settings() );
			$wc_shipping->process_admin_options();

		} else {
			foreach ( $wc_shipping->load_shipping_methods() as $method_id => $method ) {
				if ( $current_section === sanitize_title( get_class( $method ) ) ) {
					do_action( 'woocommerce_update_options_' . $this->id . '_' . $method->id );
				}
			}
		}

		// Increments the transient version to invalidate cache
		WC_Cache_Helper::get_transient_version( 'shipping', true );
	}

	/**
	 * Evaluate a cost from a sum/string.
	 * @param  string $sum
	 * @param  array  $args
	 * @return string
	 */
	protected function evaluate_cost( $sum, $args = array() ) {
		include_once( 'class-wc-extra-flat-eval-math.php' );

		// Allow 3rd parties to process shipping cost arguments
		$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
		$locale         = localeconv();
		$decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'] );
		$this->fee_cost = $args['cost'];

		// Expand shortcodes
		add_shortcode( 'fee', array( $this, 'fee' ) );

		$sum = do_shortcode( str_replace(
		array(
		'[qty]',
		'[cost]'
		),
		array(
		$args['qty'],
		$args['cost']
		),
		$sum
		) );

		remove_shortcode( 'fee', array( $this, 'fee' ) );

		// Remove whitespace from string
		$sum = preg_replace( '/\s+/', '', $sum );

		// Remove locale from string
		$sum = str_replace( $decimals, '.', $sum );

		// Trim invalid start/end characters
		$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		// Do the math
		return $sum ? WC_Eval_Math_Extra::evaluate( $sum ) : 0;
	}

	/**
	 * Work out fee (shortcode).
	 * @param  array $atts
	 * @return string
	 */
	public function fee( $atts ) {
		$atts = shortcode_atts( array(
		'percent' => '',
		'min_fee' => ''
		), $atts );

		$calculated_fee = 0;

		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}

		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}

		return $calculated_fee;
	}

	/**
	 * Check if free shipping is available.
	 *
	 * @param array $package
	 * @return bool
	 */
	public function is_available( $package ) {
		if ( 'no' == $this->enabled ) {
			return false;
		}

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
		if ( 'specific' == $this->availability ) {
			$ship_to_countries = $this->countries;
		} else {
			$ship_to_countries = array_keys( WC()->countries->get_shipping_countries() );
		}


		if ( isset( $ship_to_countries ) && !empty( $ship_to_countries ) ) {
			if ( is_array( $ship_to_countries ) && ! in_array( $package['destination']['country'], $ship_to_countries ) ) {
				$is_passed['has_ship_based_on_countries'] = 'no';
				$has_ship_based_on_product = 'no';
			} else {
				$is_passed['has_ship_based_on_countries'] = 'yes';
				$has_ship_based_on_countries = 'yes';
			}
		}

		//Check if is product exist
		if ( is_array( $this->product_base ) && isset( $this->product_base ) && isset( $package['contents'] ) ) {
			$product_base_array = $this->product_base;

			$cart_product_id_array =  array();
			foreach ( $package['contents'] as $package_product ) {
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
		if ( is_array( $this->category_base ) && isset( $this->category_base ) && isset( $package['contents'] ) ) {
			$category_base_array = $this->category_base;

			$cart_category_id_array =  array();
			foreach ( $package['contents'] as $package_product ) {
				$wp_category_id_exist = wp_get_post_terms($package_product['product_id'],'product_cat',array('fields'=>'ids'));
				if ( isset( $wp_category_id_exist ) && !empty( $wp_category_id_exist ) && is_array( $wp_category_id_exist ) ) {
					$cart_category_id_array[] = $wp_category_id_exist;
				}
			}
			$get_cat_all = array();
			$get_cat_all = $this->array_flatten($cart_category_id_array);
			

			$cart_category_id_count = count($cart_category_id_array);
			if ( isset( $cart_category_id_array ) && !empty( $cart_category_id_array ) && is_array( $cart_category_id_array ) && $cart_category_id_count != 0 ){
				$cart_category_id_array = array_unique($get_cat_all);
			}

			$category_array_intersect = array();
			$category_array_intersect = array_intersect($category_base_array,$get_cat_all);
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
		if ( is_array( $this->tag_base ) && isset( $this->tag_base ) && isset( $package['contents'] ) ) {
			$tag_base_array = $this->tag_base;

			$cart_tag_id_array =  array();
			foreach ( $package['contents'] as $package_product ) {
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
		if ( is_array( $this->sku_base ) && isset( $this->sku_base ) && isset( $package['contents'] ) ) {
			$sku_base_array = $this->sku_base;

			$cart_sku_id_array =  array();
			foreach ( $package['contents'] as $package_product ) {
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
		if ( is_array( $this->user_base ) && isset( $this->user_base ) ) {

			if ( ! is_user_logged_in() ){
				return false;
			}

			$user_base_array = $this->user_base;
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
		if ( is_array( $this->user_role_base ) && isset( $this->user_role_base ) ) {

			if ( ! is_user_logged_in() ){
				return false;
			}

			$current_user_roles = array();
			$user_role_base_array = $this->user_role_base;
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



		if ( is_array( $this->coupon ) && isset( $this->coupon ) && !empty( $this->coupon )) {
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

		if ( !empty( $this->min_amount ) && isset( $this->min_amount ) && isset( WC()->cart->cart_contents_total ) ) {
			if ( WC()->cart->prices_include_tax ) {
				$total = WC()->cart->cart_contents_total + array_sum( WC()->cart->taxes );
			} else {
				$total = WC()->cart->cart_contents_total;
			}

			if ( $total <= $this->min_amount ) {
				$has_met_min_amount = true;
				$is_passed['has_met_min_amount'] = 'yes';
			} else {
				$has_met_min_amount = false;
				$is_passed['has_met_min_amount'] = 'no';
			}
		}

		//Check if min quantiy option choosen

		if ( !empty( $this->min_extra_quantity ) && isset( $this->min_extra_quantity ) ) {

			$woo_cart_array = array();
			$woo_cart_array = WC()->cart->get_cart();
			$cart_item_quantity = 0;

			foreach ( $woo_cart_array as $woo_cart_item_key => $woo_cart_item ) {
				$cart_item_quantity += $woo_cart_item['quantity'];
			}

			if ( $cart_item_quantity != 0 && $cart_item_quantity <= $this->min_extra_quantity ) {
				$min_extra_quantity = true;
				$is_passed['min_extra_quantity'] = 'yes';
			} else {
				$min_extra_quantity = false;
				$is_passed['min_extra_quantity'] = 'no';
			}
		}


		//Check if max amount option choosen
		if (  isset( $this->max_amount ) && !empty( $this->max_amount ) ) {

			if ( WC()->cart->prices_include_tax ) {
				$total = WC()->cart->cart_contents_total + array_sum( WC()->cart->taxes );
			} else {
				$total = WC()->cart->cart_contents_total;
			}

			if ( isset( $total ) && !empty( $total ) && $total >= $this->max_amount ) {
				$max_amount = true;
				$is_passed['max_amount'] = 'yes';
			} else {
				$max_amount = false;
				$is_passed['max_amount'] = 'no';
			}
		}

		//Check if max quantiy option choosen
		if ( !empty( $this->max_quantity ) && isset( $this->max_quantity ) ) {

			$woo_cart_array = array();
			$woo_cart_array = WC()->cart->get_cart();
			$cart_item_quantity = 0;

			foreach ( $woo_cart_array as $woo_cart_item_key => $woo_cart_item ) {
				$cart_item_quantity += $woo_cart_item['quantity'];
			}

			if ( $cart_item_quantity >= $this->max_quantity ) {
				$max_quantity = true;
				$is_passed['max_quantity'] = 'yes';
			} else {
				$max_quantity = false;
				$is_passed['max_quantity'] = 'no';
			}
		}


		//Check if min weight option choosen
		if ( !empty( $this->min_weight ) && isset( $this->min_weight ) ) {

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

			if ( $cart_item_weight != 0 && $cart_item_weight <= $this->min_weight ) {
				$min_weight = true;
				$is_passed['min_weight'] = 'yes';
			} else {
				$min_weight = false;
				$is_passed['min_weight'] = 'no';
			}
		}

		//Check if max weight option choosen
		if ( !empty( $this->max_weight ) && isset( $this->max_weight ) ) {

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

			if ( $cart_item_weight >= $this->max_weight ) {
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
					return false;
				}
			}
			return true;
		}

		return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', $is_available, $package );
	}





	/**
	 * calculate_shipping function.
	 *
	 * @param array $package (default: array())
	 */
	public function calculate_shipping( $package = array() ) {

		$_tax 	= new WC_Tax();
		$taxes 	= array();
		$shipping_cost 	= 0;

		$rate = array(
		'id'    => $this->id,
		'label' => $this->title,
		'cost'  => 0,
		);

		// Calculate the costs
		$has_costs = false; // True when a cost is set. False if all costs are blank strings.
		$cost      = $this->get_option( 'cost' );

		if ( $cost !== '' ) {
			$has_costs    = true;
			$rate['cost'] = $this->evaluate_cost( $cost, array(
			'qty'  => $this->get_package_item_qty( $package ),
			'cost' => $package['contents_cost']
			) );
		}


		// Add shipping class costs
		$found_shipping_classes = $this->find_shipping_classes( $package );
		$highest_class_cost     = 0;

		foreach ( $found_shipping_classes as $shipping_class => $products ) {
			// Also handles BW compatibility when slugs were used instead of ids
			$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
			$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );

			if ( $class_cost_string === '' ) {
				continue;
			}

			$has_costs  = true;
			$class_cost = $this->evaluate_cost( $class_cost_string, array(
			'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
			'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) )
			) );

			if ( $this->type === 'class' ) {
				$rate['cost'] += $class_cost;
			} else {
				$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
			}
		}

		if ( $this->type === 'order' && $highest_class_cost ) {
			$rate['cost'] += $highest_class_cost;
		}

		if ( isset($this->shipping_description) && !empty($this->shipping_description) ) {
			$rate['method_description'] = $this->shipping_description;
		}

		// Add the rate
		if ( $has_costs ) {
			$this->add_rate( $rate );
		}

		/**
		 * Developers can add additional flat rates based on this one via this action since @version 2.4.
		 *
		 * Previously there were (overly complex) options to add additional rates however this was not user.
		 * friendly and goes against what Flat Rate Shipping was originally intended for.
		 *
		 * This example shows how you can add an extra rate based on this flat rate via custom function:
		 *
		 * 		add_action( 'woocommerce_flat_rate_shipping_add_rate', 'add_another_custom_flat_rate', 10, 2 );
		 *
		 * 		function add_another_custom_flat_rate( $method, $rate ) {
		 * 			$new_rate          = $rate;
		 * 			$new_rate['id']    .= ':' . 'custom_rate_name'; // Append a custom ID.
		 * 			$new_rate['label'] = 'Rushed Shipping'; // Rename to 'Rushed Shipping'.
		 * 			$new_rate['cost']  += 2; // Add $2 to the cost.
		 *
		 * 			// Add it to WC.
		 * 			$method->add_rate( $new_rate );
		 * 		}.
		 */
		do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate, $package );
	}

	/**
	 * Get items in package.
	 * @param  array $package
	 * @return int
	 */
	public function get_package_item_qty( $package ) {
		$total_quantity = 0;
		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
				$total_quantity += $values['quantity'];
			}
		}
		return $total_quantity;
	}

	/**
	 * Finds and returns shipping classes and the products with said class.
	 * @param mixed $package
	 * @return array
	 */
	public function find_shipping_classes( $package ) {
		$found_shipping_classes = array();

		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();

				if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
					$found_shipping_classes[ $found_class ] = array();
				}

				$found_shipping_classes[ $found_class ][ $item_id ] = $values;
			}
		}

		return $found_shipping_classes;
	}

	private function refresh() {
		?>
		<div class="js"> 
    	<div id="preloader"></div>
    	<script type="text/javascript">
    	jQuery(document).ready(function($) {
    		$(window).load(function(){
    			$('#preloader').fadeOut('slow',function(){$(this).remove();});
    		});
    	});
		</script>
		</div>
		<?php 
		echo '<script>location.href = ', json_encode(ExtraShippingMethodUrl::build_url()), ';</script>';
		die();
	}

	public function admin_options() {

		$is_extra_method_screen = !empty( $_GET['extra_method'] ) ? $_GET['extra_method'] : 'no';

		$manager = Extra_Shipping_Method::instance(true);
		$profiles = $manager->profiles();
		$profile = $manager->profile();
		$selection_priority = get_option( 'woocommerce_shipping_method_selection_priority', array() );

		if (!isset($profile)) {
			$profile = new self();
			$profile->_extra_stub = true;
			$profiles[] = $profile;
		}

		if (!empty($_GET['delete'])) {

			if (isset($profile)) {
				delete_option($profile->get_wp_option_name());
			}

			$this->refresh();
		}

		/*if ($profile->_extra_stub && ($sourceProfileId = @$_GET['duplicate']) != null && ($sourceProfile = $manager->profile($sourceProfileId)) != null) {

		$duplicate = clone($sourceProfile);
		$duplicate->id = $profile->id;
		$duplicate->profile_id = $profile->profile_id;

		$profiles[array_search($profile, $profiles, true)] = $duplicate;
		$profile = $duplicate;
		}*/

		$create_profile_link_html =
		'<a class="add-new-extra-shipping button-primary"  id="add-new-shipping-class" href="'.esc_html(ExtraShippingMethodUrl::create()).'">'.
		__('Add New Flat Rate Shipping Method', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG).
		'</a>';
        ?>
		<table class="form-table" id="add_new_extra_method">
			<tr class="wbs-title">
			    <th colspan="2">
			        <h4><?php echo $create_profile_link_html; ?></h4>
			    </th>
			</tr>
		</table>
		<h3><?php echo __('Advance Flat Rate Shipping Method', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG); ?></h3>
		<?php
		if( isset( $_POST['save'] )){
			$extramethod = isset( $_GET['extra_method'] ) ? $_GET['extra_method'] :'';
			if( !empty($extramethod)) {
				$admin_redirct_url = site_url().'/wp-admin/admin.php?page=wc-settings&tab=shipping&section=wc_extra_shipping_method';
					?>
			    	<script type="text/javascript">
			    	var redirect_location = '<?php echo $admin_redirct_url; ?>';
			    	window.location.href = redirect_location;
					</script>
					<?php 
			}
		}
		?>
		<?php if ( $is_extra_method_screen == 'no' && $is_extra_method_screen === 'no' ) { ?>   
		
				
		        
				<table class="form-table wc_shipping widefat wp-list-table" id="extra_shipping_method_listing" cellspacing="0">
					<thead>
						<tr>
							<th class="name" width="40%"><?php _e( 'Shipping Method Name', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ); ?></th>
							<th class="cost" width="20%"><?php _e( 'Amount', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ); ?></th>
							<th class="status" width="20%"><?php _e( 'Status', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ); ?></th>
							<th class="remove" width="20%"><?php _e( 'Action', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $profiles as $key => $method ) : ?>
							<tr>
								<td class="name" width="40%">
									<?php if ( isset($method->id) && !empty($method->id) ) : ?><a href="<?php echo esc_url( admin_url( 'admin.php?extra_method='.esc_attr( $method->id ).'&page=wc-settings&tab=shipping&section=wc_extra_shipping_method') ); ?>"><?php endif; ?>
									<?php echo esc_html( $method->get_title() ); ?>
									<?php if ( isset($method->shipping_description)) : ?></a>
									<?php $method_description = empty( $method->shipping_description ) ? $method->title : $method->shipping_description; ?>
									<?php //echo '<img class="help_tip" data-tip="' . esc_attr__( $method_description ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />'; ?>
									<?php endif; ?>
								</td>
								<td class="cost" width="20%" >
									<?php echo esc_attr( $method->cost ); ?>
								</td>
								<td width="20%">
								<?php if ( 'yes' === $method->enabled ) : ?>
									<?php _e( 'Enable', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ); ?> 
								<?php else : ?>
									<?php _e( 'Disable', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ); ?> 
								<?php endif; ?>
								</td>
								<td class="remove_extra_shipping_method" width="20%">
									<a class="wc-email-settings-table-actions button" href="<?php echo esc_url( admin_url( 'admin.php?extra_method='.esc_attr( $method->id ).'&page=wc-settings&tab=shipping&section=wc_extra_shipping_method') ); ?>" > <?php _e( 'Edit', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG ); ?> </a>
									<a class="button" href="<?php echo esc_html(ExtraShippingMethodUrl::delete($method)) ?>"
									onclick="return confirm('<?php esc_html_e('Are you sure you want to delete this shipping method?', AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG) ?>');">
									<?php esc_html_e('Delete') ?>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				
			<?php
		}


		if ( $is_extra_method_screen != 'no' ) {
			?>
				<div id="extra-shipping-methods-details">
					<p><?php echo __('Using Advance Flat Rate Shipping Method plugin, you can create multiple flat rate shipping methods. Using this plugin you can configure different parameters on which a particular Flat Rate Shipping method becomes availalbe to the customers at the time of checking out.<br/><br/> For example, you can create different flatrate shipping methods for all below situations: <br/><br/> 1. You can create Flat Ground Shipping - $10  and  Express Next Day Flat Shipping - $30. Based on customers selection, charges will be applied.<br/> 2. You can create a conditional shipping method called  "Discounted Shipping for order of minimum $500"  -  $5 ,  This method will only be visible if the order amount is more then $500 <br/> 3. You can create multiple Flat Rate Methods, based on country. You can create different flat rates like "Flat Shipping $5" for USA, "Flat Shipping $10" for UK and "Flat Shipping $20" for France. etc..',AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG);?></p>
					<?php $profile->generate_settings_html(); ?>
					<p class="submit extra-flat-rate-save-change" id="extra-flat-rate-custom-save-button">
					<?php if ( ! isset( $GLOBALS['hide_save_button'] ) ) : ?>
					<input name="save" class="button-primary" type="submit" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" />
					<?php endif; ?>
					<input type="hidden" name="subtab" id="last_tab" />
					<?php wp_nonce_field( 'woocommerce-settings' ); ?>
					</p>
				</div>
				
        	<?php
		}
		exit;
	}

	public function generate_settings_html( $form_fields = array() ) {
		$html_group_one ='';
		$html_group_two ='';
		$html_group_three ='';
		$fields_group_one = array();
		$fields_group_two = array();
		$fields_group_three = array();

		if ( empty( $form_fields ) ) {
			$form_fields = $this->get_form_fields();
		}

		foreach ( $form_fields as $k => $v ) {

			if( $k =='enabled' || $k == 'title' || $k =='shipping_description' || $k =='cost' ) {
				$fields_group_one[] = $k;
			}

			$extra_method = $_GET['extra_method'] == 'extra_shipping' ? $_GET['extra_method'] : '';
			if (isset($extra_method) && !empty ($extra_method) && $extra_method == 'extra_shipping') {
				if( $k == 'availability' || $k == 'master_class' || $k =='countries' || $k =='product_base' || $k =='category_base' || $k =='tag_base' || $k =='sku_base' || $k == 'user_base' || $k =='user_role_base' || $k =='coupon' || $k =='min_amount' || $k =='max_amount' || $k =='min_extra_quantity' || $k =='max_quantity' || $k =='min_weight' || $k == 'max_weight' || $k =='tax_status' ) {
					$fields_group_two[] = $k;
				}
			} else {
				if( $k == 'availability' || $k =='countries' || $k =='product_base' || $k =='category_base' || $k =='tag_base' || $k =='sku_base' || $k == 'user_base' || $k =='user_role_base' || $k =='coupon' || $k =='min_amount' || $k =='max_amount' || $k =='min_extra_quantity' || $k =='max_quantity' || $k =='min_weight' || $k == 'max_weight' || $k =='tax_status' ) {
					$fields_group_two[] = $k;
				}
			}

			if(  strpos($k, 'class_cost_') !== false || $k=='type' || $k =='class_costs' ) {
				$fields_group_three[]	 = $k;
			}

			if ( ! isset( $v['type'] ) || ( $v['type'] == '' ) ) {
				$v['type'] = 'text'; // Default to "text" field type.
			}
			if( in_array($k,$fields_group_one ) ) {
				if ( method_exists( $this, 'generate_' . $v['type'] . '_html' ) ) {
					$html_group_one .= $this->{'generate_' . $v['type'] . '_html'}( $k, $v );
				} else {
					$html_group_one .= $this->{'generate_text_html'}( $k, $v );
				}
			}

			if( in_array($k,$fields_group_two ) ) {
				if ( method_exists( $this, 'generate_' . $v['type'] . '_html' ) ) {
					$html_group_two .= $this->{'generate_' . $v['type'] . '_html'}( $k, $v );
				} else {
					$html_group_two .= $this->{'generate_text_html'}( $k, $v );
				}
			}

			if( in_array($k,$fields_group_three ) ) {
				if ( method_exists( $this, 'generate_' . $v['type'] . '_html' ) ) {
					$html_group_three .= $this->{'generate_' . $v['type'] . '_html'}( $k, $v );
				} else {
					$html_group_three .= $this->{'generate_text_html'}( $k, $v );
				}
			}
		}

		$htmlall ='';
		$htmlall .='<fieldset class="custom-fieldset-flat-one set-flat-rate-fieldset">';
		$htmlall .='<legend>'.__('Advance Flat rate Shipping methods configuration',AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG).'</legend>';
		$htmlall .='<table class="form-table" id="extra_shipping_method_settings" cellspacing="0">';
		$htmlall .= $html_group_one;
		$htmlall .='</table>';
		$htmlall .=' </fieldset>';
		$htmlall .='<fieldset class="custom-fieldset-flat-two set-flat-rate-fieldset">';
		$htmlall .='<legend>'.__('Advance Flat rate Shipping method Rule',AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG).'</legend>';
		$htmlall .='<table class="form-table" id="extra_shipping_method_settings" cellspacing="0">';
		$htmlall .= $html_group_two;
		$htmlall .='</table>';
		$htmlall .=' </fieldset>';
		if( !empty($html_group_three) && $html_group_three !='' ) {
			$htmlall .='<fieldset class="custom-fieldset-flat-three set-flat-rate-fieldset">';
			$htmlall .='<legend>'.__('Advance Flat rate Shipping method Shipping Class Cost',AD_FLAT_RATE_SHIPPING_METHOD_PLUGIN_SLUG).'</legend>';
			$htmlall .='<table class="form-table" id="extra_shipping_method_settings" cellspacing="0">';
			$htmlall .= $html_group_three;
			$htmlall .='</table>';
			$htmlall .=' </fieldset>';
		}
		echo $htmlall;



	}


	/**
	 * Adds extra calculated flat rates.
	 *
	 * @deprecated 2.4.0
	 *
	 * Additonal rates defined like this:
	 * 	Option Name | Additional Cost [+- Percents%] | Per Cost Type (order, class, or item).
	 */
	public function calculate_extra_shipping( $method, $rate, $package ) {
		if ( $this->options ) {
			$options = array_filter( (array) explode( "\n", $this->options ) );

			foreach ( $options as $option ) {
				$this_option = array_map( 'trim', explode( WC_DELIMITER, $option ) );
				if ( sizeof( $this_option ) !== 3 ) {
					continue;
				}
				$extra_rate          = $rate;
				$extra_rate['id']    = $this->id . ':' . urldecode( sanitize_title( $this_option[0] ) );
				$extra_rate['label'] = $this_option[0];
				$extra_cost          = $this->get_extra_cost( $this_option[1], $this_option[2], $package );
				if ( is_array( $extra_rate['cost'] ) ) {
					$extra_rate['cost']['order'] = $extra_rate['cost']['order'] + $extra_cost;
				} else {
					$extra_rate['cost'] += $extra_cost;
				}
				$this->add_rate( $extra_rate );
			}
		}
	}

	/**
	 * Calculate the percentage adjustment for each shipping rate.
	 *
	 * @deprecated 2.4.0
	 * @param  float  $cost
	 * @param  float  $percent_adjustment
	 * @param  string $percent_operator
	 * @param  float  $base_price
	 * @return float
	 */
	public function calc_percentage_adjustment( $cost, $percent_adjustment, $percent_operator, $base_price ) {
		if ( '+' == $percent_operator ) {
			$cost += $percent_adjustment * $base_price;
		} else {
			$cost -= $percent_adjustment * $base_price;
		}
		return $cost;
	}

	/**
	 * Get extra cost.
	 *
	 * @deprecated 2.4.0
	 * @param  string $cost_string
	 * @param  string $type
	 * @param  array $package
	 * @return float
	 */
	public function get_extra_cost( $cost_string, $type, $package ) {
		$cost         = $cost_string;
		$cost_percent = false;
		$pattern      =
		'/' .           // start regex
		'(\d+\.?\d*)' . // capture digits, optionally capture a `.` and more digits
		'\s*' .         // match whitespace
		'(\+|-)' .      // capture the operand
		'\s*'.          // match whitespace
		'(\d+\.?\d*)'.  // capture digits, optionally capture a `.` and more digits
		'\%/';          // match the percent sign & end regex
		if ( preg_match( $pattern, $cost_string, $this_cost_matches ) ) {
			$cost_operator = $this_cost_matches[2];
			$cost_percent  = $this_cost_matches[3] / 100;
			$cost          = $this_cost_matches[1];
		}
		switch ( $type ) {
			case 'class' :
				$cost = $cost * sizeof( $this->find_shipping_classes( $package ) );
				break;
			case 'item' :
				$cost = $cost * $this->get_package_item_qty( $package );
				break;
		}
		if ( $cost_percent ) {
			switch ( $type ) {
				case 'class' :
					$shipping_classes = $this->find_shipping_classes( $package );
					foreach ( $shipping_classes as $shipping_class => $items ){
						foreach ( $items as $item_id => $values ) {
							$cost = $this->calc_percentage_adjustment( $cost, $cost_percent, $cost_operator, $values['line_total'] );
						}
					}
					break;
				case 'item' :
					foreach ( $package['contents'] as $item_id => $values ) {
						if ( $values['data']->needs_shipping() ) {
							$cost = $this->calc_percentage_adjustment( $cost, $cost_percent, $cost_operator, $values['line_total'] );
						}
					}
					break;
				case  'order' :
					$cost = $this->calc_percentage_adjustment( $cost, $cost_percent, $cost_operator, $package['contents_cost'] );
					break;
			}
		}
		return $cost;
	}


	public function process_admin_options() {
		$result = parent::process_admin_options();

		$this->init();

		$clone = Extra_Shipping_Method::instance()->profile($this->profile_id);
		if (isset($clone) && $clone !== $this) {
			$clone->init();
		}

		if ($result) {
			$this->purge_woocommerce_shipping_cache();
		}

		return $result;
	}

	private function purge_woocommerce_shipping_cache() {
		global $wpdb;

		$transients = $wpdb->get_col("
		    SELECT SUBSTR(option_name, LENGTH('_transient_') + 1)
		    FROM `{$wpdb->options}`
		    WHERE option_name LIKE '_transient_wc_ship_%'
		");

		foreach ($transients as $transient) {
			delete_transient($transient);
		}
	}

	public function __clone() {
		$manager = Extra_Shipping_Method::instance();

		$this->profile_id = $manager->new_profile_id();
		$this->id = $manager->find_suitable_id($this->profile_id);

		$this->name .= ' ('._x('copy', 'noun', 'extra_shipping').')';
		$this->settings['name'] = $this->name;

	}

	public function array_flatten($array) {
		if (!is_array($array)) {
			return FALSE;
		}
		$result = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = array_merge($result, $this->array_flatten($value));
			}
			else {
				$result[$key] = $value;
			}
		}
		return $result;
	}
}