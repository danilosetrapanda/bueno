<?php
/*
Plugin Name: ARG Multistep Checkout for WooCommerce
Plugin URI:  http://argthemes.com/woocommerce-multistep-checkout/
Description: Extension for WooCommerce which helps you change the Checkout page into an easy and modern step-by-step page.
Version:     1.0
Author:      ARG Themes
Author URI:  http://argthemes.com
Domain Path: /languages
Text Domain: argMC
*/



/**
 * Table of Contents
 *
 *Wizzard Styles
	*Wizzard Color
	*Wizzard Accent Color
	*Wizzard Border Color
	*Wizzard Max Widht
	*Wizzard Secondary Font Family
	*Wizzard Secondary Font Weight
 *Tab Styles
	*Tab Text Color
	*Tab Background Color
	*Hovered/Completed Tab Text Color
	*Hovered/Completed Tab Background Color
 *Overwrite Woocommerce Styles
	*Woo Text Color
	*Woo Headings/Label Color
	*Woo Input Border/Background Color
	*Woo Button Background Color
	*Woo Button Background Color on Login And Coupon Forms
	*Woo Inherit Accent Color from Wizzard  
 *Accent Color > 767px
 *Tab Number Text Color  > 767px
 *Tab Number Background Color > 767px
 *Tab Text Color > 767px
 *Tab Completed - Number Background Color  > 767px
 *
 */

 

if (!function_exists ('argMCStyles')) {
	function argMCStyles() { 	
		$options = get_option('arg-mc-options');

		?>			
		<style>
		
			/**********************************************************************************/
			/* Wizzard Styles  ****************************************************************/
			/**********************************************************************************/
			
			/*Wizzard Color*/
			
			.arg-mc-nav-text {
				color: <?php echo $options['wizzard_color']; ?>;	
			}
			
			
			
			/*Wizzard Accent Color*/
			
			.arg-mc-nav-text a 	{
				color: <?php echo $options['accent_color']; ?>;
			}
			
			.arg-mc-tab-item.current::before,
			.arg-mc-tab-item.completed::before,
			.arg-mc-tab-item.visited:hover::before {
				border-bottom: 3px solid <?php echo $options['accent_color']; ?>;
			}
			
			.arg-mc-tab-item.current::after {
				border-color: <?php echo $options['accent_color']; ?> transparent transparent;
			}
				
				
			
			 /*Wizzard Border Color*/
			
			.arg-mc-nav-text,
			.arg-mc-nav-buttons {
				border-color: <?php echo $options['border_color']; ?>;
			}
			
			
			/*Wizzard Max Widht*/
			.arg-mc-wrapper {
				max-width: <?php echo $options['wizzard_max_width']; ?>;
			}
			
			
			
			/*Wizzard Secondary Font Family*/
			<?php
			if (!empty($options['secondary_font'])) :
				?>
				.arg-mc-tabs-list {
					font-family: <?php echo $options['secondary_font']; ?>;
				}
				<?php
			endif;
			?>
			
			
			
			/*Wizzard Secondary Font Weight*/
			.arg-mc-tabs-list {
				font-weight: <?php echo $options['secondary_font_weight']; ?>;
			}
				
			
			
			/**********************************************************************************/
			/* Tab Styles  ********************************************************************/
			/**********************************************************************************/
			
			/*Tab Text Color*/
			
			.arg-mc-tab-item {
				color: <?php echo $options['tab_text_color']; ?>;
			}
			
		
		
			/*Tab Background Color*/
			
			.arg-mc-tab-item {
				background: <?php echo $options['tab_bkg_color']; ?>;
			}
			
			
			
			/*Tab Border Color*/
			
			.arg-mc-tab-item {
				border-bottom-color: <?php echo $options['tab_border_bottom_color']; ?>;
				border-left-color: <?php echo $options['tab_border_left_color']; ?>;
			}
			
			
			
			/*Tab Icon Color*/
			
			.arg-mc-tab-item.completed .tab-completed-icon {
				color: <?php echo $options['accent_color']; ?>;
			}
			
			
			
			/*Hovered/Completed Tab Text Color*/
			
			.arg-mc-tab-item.current,
			.arg-mc-tab-item.selected,
			.arg-mc-tab-item.completed,
			.arg-mc-tab-item.current:hover,
			.arg-mc-tab-item.completed:hover,
			.arg-mc-tab-item.visited:hover {
				color: <?php echo $options['tab_text_color_hover']; ?>;
			}
			
			
			
			/*Hovered/Completed Tab Background Color*/

			.arg-mc-tab-item.current,
			.arg-mc-tab-item.completed,
			.arg-mc-tab-item.current:hover,
			.arg-mc-tab-item.completed:hover,
			.arg-mc-tab-item.visited:hover {
				background: <?php echo $options['tab_bkg_color_hover']; ?>;
			}

		
			<?php
			if (!empty($options['overwrite_woo_styles'])) :
				?>
			
				/**********************************************************************************/
				/* Overwrite Woocommerce Styles  **************************************************/
				/**********************************************************************************/
				
				/*Woo Text Color*/
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper .arg-mc-form-steps,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper #payment div.payment_box,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper table.shop_table th,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper table.shop_table td {
					color: <?php echo $options['woo_text_color']; ?>;
				}
				
				
				/*Woo Headings/Label Color*/
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper h2,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper h3,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper label,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .form-row.woocommerce-invalid label,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper table.shop_table thead th,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input.input-radio[type="radio"] + label {
					color: <?php echo $options['woo_label_color']; ?>;
				}
				
				
				/*Woo Input Border/Background Color*/
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="text"],
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="password"],
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="search"],
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="email"],
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="url"],
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="tel"],
				.woocommerce-checkout .woocommerce .arg-mc-wrapper  select,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper textarea,
				.woocommerce-checkout .select2-container .select2-choice {
					border-color: <?php echo $options['woo_input_border_color']; ?>;
					background: <?php echo $options['woo_input_bkg_color']; ?>;
					border-radius: <?php echo $options['woo_field_border_radius']; ?>;
				}
				
				/*.woocommerce-checkout .woocommerce .arg-mc-wrapper input:-webkit-autofill {
					-webkit-box-shadow: 0 0 0px 1000px <?php //echo $options['woo_input_bkg_color']; ?> inset;
				}*/
		
				
				
				/*Woo Input Background Color on Hover*/
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="text"]:focus,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="password"]:focus,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="search"]:focus,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="email"]:focus,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="url"]:focus,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="tel"]:focus,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper select:focus,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper textarea:focus,
				.woocommerce-checkout .select2-container .select2-choice:focus {
					background: <?php echo $options['woo_input_bkg_color_hover']; ?>;
				}
				
				
				/*Woo Button Background Color*/
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper #respond input#submit,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper a.button,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper button.button,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input.button,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper #respond input#submit:hover,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper a.button:hover,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper button.button:hover,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input.button:hover {
					background: <?php echo $options['woo_button_bkg_color']; ?>;
					border-radius: <?php echo $options['woo_field_border_radius']; ?>;
				}
				
				
				/*Woo Button Background Color on Login And Coupon Forms*/
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper .login input[type=submit],
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form.checkout_coupon input[type=submit] {
					background: <?php echo $options['woo_button_bkg_color_login']; ?> !important;
				}
				
				
				/*Woo Inherit Accent Color from Wizzard*/
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper #payment .payment_method_paypal .about_paypal,
				.woocommerce-checkout .woocommerce .terms.wc-terms-and-conditions a,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper .login .lost_password a,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .form-row .required,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form.login label[for="rememberme"]:after,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="checkbox"] + label:after {
					color:  <?php echo $options['accent_color']; ?>;
				}
				
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper input[type="radio"].input-radio + label:after,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper ul#shipping_method li input[type="radio"].shipping_method + label:after,
				.woocommerce-checkout .select2-results .select2-highlighted {
					background: <?php echo $options['accent_color']; ?>;
				}
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .form-row.woocommerce-invalid .select2-container,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .form-row.woocommerce-invalid select,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .form-row.woocommerce-invalid input,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .has-error input,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .has-error .select2-choice {
					border-color:  <?php echo $options['woo_invalid_required_field_border']; ?> !important;
					background: <?php echo $options['woo_invalid_required_field_bkg']; ?>;
				}
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .form-row.woocommerce-validated .select2-container,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .form-row.woocommerce-validated input.input-text,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .form-row.woocommerce-validated select {
					border-color:  <?php echo $options['woo_validated_field_border']; ?>;
				}
				
				<?php
				if (!empty($options['secondary_font'])) :
					?>
					.woocommerce-checkout .woocommerce .arg-mc-wrapper label,
					.woocommerce-checkout .woocommerce .arg-mc-wrapper .login input[type="submit"],
					.woocommerce-checkout .woocommerce .arg-mc-wrapper form.checkout_coupon input[type="submit"],
					.woocommerce-checkout .woocommerce .arg-mc-wrapper .arg-mc-nav-buttons .button,
					.woocommerce-checkout .woocommerce .arg-mc-wrapper .woocommerce-billing-fields h3,
					.woocommerce-checkout .woocommerce .arg-mc-wrapper .woocommerce-shipping-fields h3,
					.woocommerce-checkout .woocommerce .arg-mc-wrapper table.shop_table thead th {
						font-family: <?php echo $options['secondary_font']; ?>;
					}
					<?php
				endif;
				?>
				
				.woocommerce-checkout .woocommerce .arg-mc-wrapper .wc_payment_method input.input-radio[type="radio"] + label,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper .login input[type="submit"],
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form.checkout_coupon input[type="submit"],
				.woocommerce-checkout .woocommerce .arg-mc-wrapper .arg-mc-nav-buttons .button,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper .woocommerce-billing-fields h3,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper .woocommerce-shipping-fields h3,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper table.shop_table thead th,
				.woocommerce-checkout .woocommerce .arg-mc-wrapper #ship-to-different-address label {
					font-weight: <?php echo $options['secondary_font_weight']; ?>;
				}				

				<?php
			endif;
			?>
			
				.woocommerce-checkout .woocommerce .arg-mc-wrapper form .form-row label.error {
					color:  <?php echo $options['accent_color']; ?>;
				}
				
			
			@media screen and (min-width: 767px) {
				
				
				/*Accent Color > 767px*/
				
				.arg-mc-tab-item.current .arg-mc-tab-number,
				.arg-mc-tab-item.visited:hover .arg-mc-tab-number {
					color: <?php echo $options['accent_color']; ?>;
				}
				
				
				/*Tab Number Text Color  > 767px*/
			
				.arg-mc-tab-number {
					color: <?php echo $options['number_text_color']; ?>;
				}
				

			}
              
		</style>
		<?php						
	}
}

add_action('wp_head', 'argMCStyles', 100);    