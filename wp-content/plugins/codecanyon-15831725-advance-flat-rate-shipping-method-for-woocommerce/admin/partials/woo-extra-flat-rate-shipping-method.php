<?php
    
class Extra_Shipping_Method {
        
	public static function setup(){
        self::instance();
    }

    public static function instance( $resetCache = false ) {
        
    	if ( !isset(self::$instance) ) {
    		self::$instance = new self();
            add_filter('woocommerce_shipping_methods', array(self::$instance, '_registerExtraShippingMethod'));
        }

        if ($resetCache) {
            unset(self::$instance->orderedProfiles);
            unset(self::$instance->profileInstances);
        }

        return self::$instance;
    }

    /** @return WC_Extra_Shipping_Method[] */
    public function profiles() {
        if (!isset($this->orderedProfiles)) {

            $this->orderedProfiles = array();

            /** @var WC_Shipping $shipping */
            $shipping = WC()->shipping;
            foreach ( $shipping->load_shipping_methods() as $method ) {
                
            	if ( $method instanceof WC_Extra_Shipping_Method ) {
                    $this->orderedProfiles[] = $method;
                }
            }
        }

        return $this->orderedProfiles;
    }
	
    public function profile( $name = null ) {
        $this->find_suitable_id($name);
        $profiles = $this->instantiateProfiles();
        $profiles_name = !empty( $profiles[$name] ) ? $profiles[$name] : '';
        
        if (isset($profiles_name) && !empty($profiles_name)) {
        	return $profiles[$name];
        }else {
        	return '';
        }
        
    }

    public function profile_exists( $name ) {
        $profiles = $this->instantiateProfiles();
        return isset($profiles[$name]);
    }

    public function find_suitable_id( &$profileId ) {
        if ( !$profileId && !( $profileId = $this->current_profile_id() ) ) {
            return $profileId = null;
        }

        return $profileId;
    }

    public function current_profile_id() {
        $profile_id = null;

        if ( is_admin() ) {
        	
        	$is_extra_shipping_method = !empty($_GET['extra_method']) ? $_GET['extra_method'] : '';
            if (isset( $is_extra_shipping_method ) && !empty( $is_extra_shipping_method )) {
	            if ( empty( $profile_id ) ) {
	                $profile_id = $_GET['extra_method'];
	            }
            }

            if ( empty( $profile_id ) ) {
                $profile_id = 'extra_shipping';
            }
        }

        return $profile_id;
    }

    public function new_profile_id() {
        
    	if ( !$this->profile_exists('extra_shipping') ) {
            return 'extra_shipping';
        }

        $timestamp = time();

        $i = null;
        
        do {
            $new_profile_id = trim($timestamp.'-'.$i++, '-');
        } while ($this->profile_exists($new_profile_id));

        return $new_profile_id;
    }



    public function _registerExtraShippingMethod( $methods ) {
        return array_merge($methods, $this->instantiateProfiles());
    }
	
	public static function listAvailableProfileIds( $pluginPrefix = WC_Extra_Shipping_Method::PLUGIN_PREFIX, $idPrefix = null ) {
	
		$ids = array();
	
		$settingsOptionNamePattern = sprintf('/^%s%s(\\w+)_settings$/',
			preg_quote($pluginPrefix, '/'), preg_quote($idPrefix, '/')
		);
	
		foreach (array_keys(wp_load_alloptions()) as $option) {
		
			$matches = array();
			
			if (preg_match($settingsOptionNamePattern, $option, $matches)) {
		    	$ids[$matches[0]] = $matches[1];
			}
		}

		return $ids;
	}	

	public static function getRuleSettingsOptionName($ruleId, $pluginPrefix = WC_Extra_Shipping_Method::PLUGIN_PREFIX) {
		return sprintf('%s%s_settings', $pluginPrefix, $ruleId);
	}

    private static $instance;

    private $orderedProfiles;

    /** @var WC_Extra_Shipping_Method[] */
    private $profileInstances;

    private function instantiateProfiles() {
        
    	if ( !isset( $this->profileInstances ) ) {

            $this->profileInstances = array();

            $profileIds = self::listAvailableProfileIds();
            
            if ( empty( $profileIds ) ) {
                $profileIds[] = $this->new_profile_id();
            }

            foreach ( $profileIds as $profileId ) {
                $this->profileInstances[$profileId] = new WC_Extra_Shipping_Method($profileId);
            }
            
            $is_extra_shipping_method = !empty($_GET['extra_method']) ? $_GET['extra_method'] : '';
            if (isset( $is_extra_shipping_method ) && !empty( $is_extra_shipping_method )) {
            	$is_extra_shipping_method_get = $_GET['extra_method'];
            }else {
            	$is_extra_shipping_method_get = '';
            }

            if (is_admin() && ($editingProfileId = $is_extra_shipping_method_get) &&
                !isset($this->profileInstances[$editingProfileId])) {

                $editingProfile = new WC_Extra_Shipping_Method($editingProfileId);
                $editingProfile->_extra_stub = true;
                $this->profileInstances[$editingProfileId] = $editingProfile;
            }

            if ($currentProfile = $this->profile()) {
                add_action(
                    'woocommerce_update_options_shipping_' . $currentProfile->id,
                    array($currentProfile, 'process_admin_options')
                );
            }
        }

        return $this->profileInstances;
    }
}