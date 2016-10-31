<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('WooCommerce')) {
	exit;
}

//Define tabs class
$tabsClass = array(
	'2'	=> 'two-tabs',
	'3'	=> 'three-tabs',
	'4'	=> 'four-tabs',
	'5'	=> 'five-tabs',
	'6'	=> 'six-tabs'
);

//Get admin options
$options = get_option('arg-mc-options');

if (empty($options)) :
	$options = array();
endif;

//Show / hide form steps
$showLogin 		= !empty($options['show_login']) && !is_user_logged_in() ? true : false;
$showCoupon 	= !empty($options['show_coupon']) ? true : false;
$showOrder 		= !empty($options['show_order']) ? true : false;
$showShipping 	= false;

if (!empty($options['show_additional_information'])) :
	$showShipping = true;
else :
	add_filter('woocommerce_enable_order_notes_field', '__return_false');
endif;

if (true === WC()->cart->needs_shipping_address()) :
	$showShipping = true;
endif;


if ($showLogin === false) :
	unset($options['steps']['login']);
endif;

if ($showCoupon === false) :
	unset($options['steps']['coupon']);
endif;

if ($showOrder === false) :
	unset($options['steps']['order']);
endif;

if ($showShipping === false) :
	unset($options['steps']['shipping']);
endif;


//Merge Billing and Shipping
if (!empty($options['merge_billing_shipping'])) :
	unset($options['steps']['billing']);
	unset($options['steps']['shipping']);
else :
	unset($options['steps']['billing_shipping']);
endif;


//Merge Order and Payment
if (!empty($options['merge_order_payment'])) :
	unset($options['steps']['order']);
	unset($options['steps']['payment']);
else :
	unset($options['steps']['order_payment']);
endif;
?>

<div class="arg-mc-wrapper wrapper-no-bkg <?php echo $options['tabs_template']; ?>">
	<?php
	wc_print_notices();
	
	//If checkout registration is disabled and not logged in, the user cannot checkout
	if (!$checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in()) {
		echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce'));
		return;
	}

	remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
	remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
	
	do_action('woocommerce_before_checkout_form', $checkout);	

	if (!empty($options['steps'])) :
		?>
		<ul class="arg-mc-tabs-list <?php echo $options['tabs_width'] . ' ' . $tabsClass[count($options['steps'])]; ?> ">
			<?php
			$i = 1;
			//Display tabs
			foreach ($options['steps'] as $template) :
				?>		
				<li class="arg-mc-tab-item<?php echo $i == 1 ? ' current visited' : ''; ?>">
					
					<div class="arg-mc-tab-item-inner">
						<div class="arg-mc-tab-number-wrapper">
							<div class="arg-mc-tab-number">
								<span class="number-text"><?php echo $i.'.'; ?></span>
								<span class="tab-completed-icon"></span>
							</div>
						</div>
				
						<div class="arg-mc-tab-text"><?php echo $template['text']; ?></div>
					</div>
					
				</li>
				<?php
				$i++;
			endforeach;
			?>
		</ul><!--arg-mc-tabs-list-->
		
		<div class="arg-mc-form-steps-wrapper">
		
			<?php
			$i = 1;
			//Login step
			if (!empty($options['steps']['login'])) :
				unset($options['steps']['login']);
				?>
				<div class="arg-mc-form-steps arg-mc-form-step-<?php echo $i; ?> arg-mc-login-step<?php echo $i == 1 ? ' first current' : ''; ?>">
					<?php do_action('woocommerce_checkout_login_form', $checkout); ?>
				</div>
				<?php
				$i++;
			endif;
			
			//Coupon step
			if (!empty($options['steps']['coupon'])) :
				unset($options['steps']['coupon']);
				?>
				<div class="arg-mc-form-steps arg-mc-form-step-<?php echo $i; ?> arg-mc-coupon-step<?php echo $i == 1 ? ' first current' : ''; ?>">
					<?php do_action('woocommerce_checkout_coupon_form', $checkout); ?>
				</div>
				<?php
				$i++;
			endif;
			?>
		
			<form name="checkout" method="post" class="checkout arg-mc-form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">	
				<?php
				foreach ($options['steps'] as $template => $step) :
					?>
					<div class="arg-mc-form-steps arg-mc-form-step-<?php echo $i; ?><?php echo $i == 1 ? ' first current' : ''; ?>">
						<?php
						switch ($template) :
							//Billing step
							case 'billing':
								if (sizeof($checkout->checkout_fields['billing']) > 0) :
									do_action('woocommerce_checkout_before_customer_details'); 
									do_action('woocommerce_checkout_billing'); 
								endif;
								
								break;
							
							//Shipping step	
							case 'shipping':
								if (sizeof($checkout->checkout_fields['shipping']) > 0) :
									do_action('woocommerce_checkout_shipping');
									do_action('woocommerce_checkout_after_customer_details');
								endif;
								
								break;
							
							//Order step
							case 'order' :
								do_action('woocommerce_checkout_before_order_review');
								do_action('woocommerce_order_review');
								do_action('woocommerce_checkout_after_order_review');
								
								break;
							
							//Billing & shipping step
							case 'billing_shipping' :
								if (sizeof($checkout->checkout_fields['billing']) > 0) :
									do_action('woocommerce_checkout_before_customer_details'); 								
									do_action('woocommerce_checkout_billing'); 
								endif;
		
								if (sizeof( $checkout->checkout_fields['shipping']) > 0) :
									do_action('woocommerce_checkout_shipping');
									do_action('woocommerce_checkout_after_customer_details');
								endif;						
								
								break;
							
							//Order & payment step
							case 'order_payment' :
								if ($showOrder) :
									do_action('woocommerce_order_review');
								endif;
								
								do_action('woocommerce_checkout_payment');
								
								break;
							
							//Payment step	
							case 'payment' :
								do_action('woocommerce_checkout_payment');
								
								break;								
						endswitch;
						?>
					</div>
					<?php
					$i++;
				endforeach;
				?>
			</form>
		</div><!--arg-mc-form-steps-wrapper-->
		<?php
	endif;

	?>
	<div class="arg-mc-nav">
		<div class="arg-mc-nav-text"><?php echo $options['footer_text']; ?></div>
		<div class="arg-mc-nav-buttons">
			<button id="arg-mc-prev" class="button arg-mc-previous" type="button" style="display: none"><?php echo $options['btn_prev_text']; ?></button>
			<button id="arg-mc-next"<?php echo $showLogin ? ' style="display:none" ' : ''; ?> class="button arg-mc-next" type="button"><?php echo $options['btn_next_text']; ?></button>
			<?php
			if ($showLogin) :
				?>
				<button id="arg-mc-skip-login" class="button arg-mc-next" type="button"><?php echo $options['btn_skip_login_text']; ?></button>				
				<?php
			endif;
			?>
			<button id="arg-mc-submit" class="button arg-mc-submit" type="submit" style="display: none"><?php echo $options['btn_submit_text']; ?></button>
		</div>
	</div><!--arg-mc-nav-->
	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>	
</div><!--arg-mc-wrapper-->