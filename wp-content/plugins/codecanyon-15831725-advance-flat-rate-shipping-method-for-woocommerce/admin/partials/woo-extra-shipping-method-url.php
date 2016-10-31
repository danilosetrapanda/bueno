<?php
/**
 * ExtraShippingMethodUrl
 *
 * @class 		ExtraShippingMethodUrl
 * @author 		Multidots
 */

class ExtraShippingMethodUrl {
    
	public static function build_url( $parameters = array() ) {
        
    	$query = build_query(self::arrayFilterNull($parameters + array(
            "page" => (version_compare(WC()->version, '2.1', '>=') ? "wc-settings" : "woocommerce_settings"),
            "tab" => "shipping",
            "section" => "wc_extra_shipping_method",
        )));

        $url = admin_url("admin.php?{$query}");

        return $url;
    }

    public static function create( array $additionals = array() ) {
        return self::genericWithProfile(Extra_Shipping_Method::instance()->new_profile_id(), $additionals);
    }

    public static function edit( WC_Extra_Shipping_Method $rule, array $parameters = array() ) {
        return self::genericWithProfile($rule->profile_id, $parameters);
    }

    /*public static function duplicate( WC_Extra_Shipping_Method $rule ) {
        return self::create(array('duplicate' => $rule->profile_id));
    }*/

    public static function delete( WC_Extra_Shipping_Method $rule ) {
        return self::edit($rule, array('delete' => 'yes'));
    }


    private static function genericWithProfile( $profileId, array $parameters = array() ) {
        $parameters['extra_method'] = $profileId;
        $url = self::build_url($parameters);
        return $url;
    }

    public static function arrayFilterNull( $array ) {
        
    	foreach ( $array as $key => $value) {
            if ($value === null) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}