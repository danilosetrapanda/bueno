<?php
if( ! class_exists( 'GetBowtiedUpdater' ) ) {
	
	class GetBowtiedUpdater {
		
		var $api_url = "http://my.getbowtied.com/api/update_theme.php";
	
		function __construct( $license_key ) {
	
			$this->license_key = $license_key;

			add_filter( 'pre_set_site_transient_update_themes', array( &$this, 'check_for_update' ) );

			//set_site_transient('update_themes', null);
			
		}
		
		function check_for_update( $transient ) {
			
			global $wp_filesystem;

			if( empty( $transient->checked ) )  {
				return $transient;
			}

			$curr_theme = wp_get_theme();

			$curr_ver =  getbowtied_theme_version();
			// die();

			$url = $this->api_url;

			// $args  = array("k" => $this->license_key,
			// 			   "t" => $curr_theme->name,
			// 			   "d" => get_site_url() 
			// 		);

			$args = array(
								'method' => 'POST',
								'timeout' => 30,
								'body' => array( "k" => $this->license_key,  "t" => THEME_NAME, "d" => get_site_url()  )
					);

			// echo $url;
			// die();

			$request = wp_remote_post( $url, $args);

			// echo $url;

			if ( is_wp_error( $request ) ) 
			{
		    	return $transient;
		    }	    

		    if ( $request['response']['code'] == 200 ) 
		    {
		    	$data = json_decode( $request['body'] );

		    	if (!empty($data->error) && $data->error == 1)
		    	{
		    		update_option("getbowtied_".THEME_SLUG."_license_expired", 1);
		    		add_action( 'admin_notices', array(&$this, 'expired_notice') );
   		
		    	}
				
				if(version_compare($curr_ver, $data->version, '<'))
				{

					$transient->response[$curr_theme->get_template()] = array(
						"new_version"	=> 		$data->version,
						"package"		=>	    $data->download_url,
						"url"			=>		'http://www.getbowtied.com'		
					);

					// add_action( 'admin_notices', array(&$this, 'update_notice') );
					update_option("getbowtied_".THEME_SLUG."_remote_ver", $data->version);

				}
				else 
				{
					// update_option("getbowtied_".THEME_SLUG."_update_available", 0);
				}

			}

			// $curr_theme = wp_get_theme();

			// foreach ($purchased_themes AS $theme)
			// {
			// 	if ($curr_theme->name == $theme->theme_name)
			// 	{
			// 		if(version_compare($curr_theme->version, $theme->version, '<'));
			// 		{

			// 			$new_ver = $theme->version; 
			// 			$url = $this->api_url.$this->api_user.'/'.$this->api_key.'/wp-download:'.$theme->item_id.'.json';
			// 			$request = wp_remote_get( $url );

			// 			if ( is_wp_error( $request ) ) 
			// 			{
			// 		    	return $transient;
			// 		    }

			// 	        if ( $request['response']['code'] == 200 ) 
			// 		    {

			// 				$data = json_decode($request['body']);
	
			// 				$download_url = $data->{'wp-download'}->url;
			// 				$transient->response[$curr_theme->slug] = array(
			// 					"new_version"	=> 		$new_ver,
			// 					"package"		=>	    $download_url,
			// 					"url"			=>		'http://www.getbowtied.com'		
			// 				);

			// 				add_action( 'admin_notices', array(&$this, 'update_notice') );
			// 			}
			// 		}
			// 	}
			// }

			// echo '<script type="text/javascript">console.log('.json_encode($transient).')</script>';
			
			return $transient;

		}

		function update_notice() {
			update_option("getbowtied_".THEME_SLUG."_update_available", 1);
// 			//global $pagenow;

// 			$theme_settings_link = admin_url( 'themes.php' );

// 			//if ( $pagenow == 'themes.php' || $pagenow == 'update-core.php' ) {
// 				if( function_exists('wp_get_theme') ) {
// 					$theme_name = '<strong>'. wp_get_theme() .'</strong>';
// 				}
// 				echo '<div class="error getbowtied_admin_notices">
// 				<p>There is an update available for the ' . $theme_name . ' theme.</p>
// 				</div>';
// 			//}
		}

		function expired_notice() {
			
			echo '<div class="error getbowtied_admin_notices">
			<p>This site will no longer receive automatic theme updates. Theme\'s <a href="' . admin_url( 'admin.php?page=getbowtied_theme_registration' ) . '">Product Key</a> is no longer active on this domain.</p>
			</div>';
		}

	}

}