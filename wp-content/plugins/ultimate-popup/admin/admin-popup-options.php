<?php

function up_get_post_as_list( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'post',
        'numberposts' => 10,
    ) );

    $posts = get_posts( $args );

    $post_options = array();
    if ( $posts ) {
        foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
        }
    } else {
        $post_options[0] = 'Nothing available!';
    }

    return $post_options;
}

function up_get_popups_as_list_array() {
    return up_get_post_as_list( array( 'post_type' => 'ultimate-popup', 'numberposts' => -1 ) );
}

class Ultimate_Popup_Admin {
    
    //global $up_option_page_array; 

	private $key = 'ultimate_popup_options';
    
	private $metabox_id = 'ultimate_popup_option_metabox';
    
	protected $title = '';
    
	protected $options_page = '';
    
	private static $instance = null;
    
	private function __construct() {
		$this->title = __( 'Popup Options', 'ultimate-popup' );
	}
    
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->hooks();
		}
		return self::$instance;
	}
    
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}
    
	public function init() {
		register_setting( $this->key, $this->key );
	}
    
	public function add_options_page() {
        global $up_option_page_array;
		$this->options_page = $up_option_page_array = add_menu_page( 
            __('Ultimate Popup Settings', 'ultimate-popup'), 
            $this->title, 
            'manage_options', 
            $this->key, 
            array( $this, 'admin_page_display' ) 
        );
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}
    
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page up-option-page-area <?php echo $this->key; ?>">
			<h2>Ultimate Popup Settings</h2>
			
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		
		<script>
            jQuery(document).ready(function($){
                $('select#popup_display_settings option[selected=selected]').each(function(){
                    if($(this).val() == '1')
                    { // or this.value == 'volvo'
                        $(".cmb2-id-global-popup-id").hide();
                    }
                    else
                    {
                        $(".cmb2-id-global-popup-id").show();
                    }    
                });   

                $("select#popup_display_settings").change(function(){

                    if($(this).val() == "1")
                    {
                        $(".cmb2-id-global-popup-id").hide();
                    }
                    else
                    {
                        $(".cmb2-id-global-popup-id").show();
                    }
                });             
            });
        </script>
		<?php
	}
    
	function add_options_page_metabox() {
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );
        
		$cmb->add_field( array(
			'name'        => __( 'Popup Display Settings', 'ultimate-popup' ),
            'desc'        => __( 'Select where popup will appear.', 'ultimate-popup' ),
            'id'          => 'popup_display_settings',
            'type'        => 'select',
            'default'        => '1',
            'options' => array(
                '1' => __( 'Display different popup on different page', 'ultimate-popup' ),
                '2' => __( 'Display popup only on blog index page', 'ultimate-popup' ),
                '3' => __( 'Display same popup on all pages', 'ultimate-popup' ),
            ),
		) );
        
		$cmb->add_field( array(
			'name' => __( 'Select Global Popup', 'ultimate-popup' ),
			'desc' => __( 'Select global popup which you want on all pages.', 'ultimate-popup' ),
			'id'   => 'global_popup_id',
			'type'        => 'select',
            'options_cb' => 'up_get_popups_as_list_array',
		) );

	}
    
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'ultimate-popup' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}
    
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

function ultimate_popup_admin() {
	return Ultimate_Popup_Admin::get_instance();
}

function ultimate_popup_get_option( $key = '' ) {
	return cmb2_get_option( ultimate_popup_admin()->key, $key );
}

ultimate_popup_admin();


// Hiding menu from wp menu
function up_ppm_remove_menus(){
    remove_menu_page( 'ultimate_popup_options' );
}
add_action( 'admin_menu', 'up_ppm_remove_menus' );

// Adding redirecting menu on gallery CPT
add_action('admin_menu', 'ultimate_popup_custom_submenu_page');
function ultimate_popup_custom_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=ultimate-popup',
        __('Ultimate Popup Settings', 'ultimate-popup'),
        __('Popup Settings', 'ultimate-popup'),
        'manage_options',
        'ultimate-popup-settings',
        'up_ppm_fake_page' );
}

// Set redirect function
function up_ppm_fake_page() {
   ?>
   <p><?php echo __('Please wait ...', 'ultimate-popup'); ?></p>
   <script>
       window.location.replace("admin.php?page=ultimate_popup_options");
    </script>
   <?php
}