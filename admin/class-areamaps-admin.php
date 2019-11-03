<?php
class AreaGM_Admin {

	protected $options_default = array(
									'jsmap'			=> 1,
									'jsamap'		=> 1,
									'apikey'		=> '',
									'lat'			=> -12.043173,
									'lng'		 	=> -77.043125,
									'lcolor'	 	=> '#00FF00',
									'zoom'		 	=> 13,
								);

	function __construct() {

		$this->options_default['coords'] = array(
											array(+0.03501, +0.000001),
											array(+0.02, +0.045),
											array(-0.0201, +0.04501),
											array(-0.035, +0.0001),
											array(-0.02011, -0.0451),
											array(+0.02100001, -0.045111),
										);

		add_filter( 'plugin_row_meta', [ $this, 'setting_external_link' ], 10, 2 );
		add_filter( 'plugin_action_links_'.AREAGM_PLUGIN_BASE, [ $this, 'setting_internal_link' ] );

		/*	Embed JS	*/
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		
		/*	Ajax	*/
		add_action( 'wp_ajax_areamaps_js', [ $this, 'map_js' ] );
		add_action( 'wp_ajax_nopriv_areamaps_js', [ $this, 'map_js' ] );

		/*	MetaBoxes	*/
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post_areamaps', [ $this, 'save_post' ], 10, 3);

		/*	Admin Column	*/
		add_filter('manage_areamaps_posts_columns', [ $this,'add_name_column' ] );
		add_action('manage_areamaps_posts_custom_column', [ $this, 'add_value_column'], 10, 2);
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		global $pagenow, $post;

		if( get_post_type() !== 'areamaps' || !in_array( $pagenow, [ 'post-new.php', 'edit.php', 'post.php' ] ) )
			return;

		$settings = areagm_settings();

		if( isset( $settings['areamaps_apikey'] ) && $settings['areamaps_apikey'] != '' &&
			isset( $settings['areamaps_admin_js'] ) && $settings['areamaps_admin_js'] == true &&
			isset( $settings['areamaps_handle'] ) && !empty( $settings['areamaps_handle'] )
		) {
			wp_enqueue_script($settings['areamaps_handle'],'//maps.googleapis.com/maps/api/js?key='.$settings['areamaps_apikey'].'&libraries=geometry,places',array('jquery'), false, true );
		}
	}

	/*	Add custom settings url in plugins page	*/
	function setting_internal_link( $links ) {
		$settings = array(
						'settings' => sprintf('<a href="%s">%s</a>',	admin_url( 'edit.php?post_type=areamaps&page=area_settings' ), 'Setting')
					);
		
		return array_merge( $settings, $links );
	}


	function setting_external_link($links, $file) {
		if ( $file == AREAGM_PLUGIN_BASE ) {
			
			$row_meta = array(
						'docs' => sprintf('<a href="%s" target="_blank" title="%s">%s</a>',esc_url('https://github.com/gonzalesc/Wordpress-Delivery-Area-with-Google-Maps'), 'Delivery Area with Google Maps' ,'Documentation'),
						'support' => sprintf('<a href="%s" target="_blank" title="%s">%s</a>',esc_url('https://github.com/gonzalesc/Wordpress-Delivery-Area-with-Google-Maps'), 'Delivery Area with Google Maps' , __('Support','letsgo')),
						'buy' => sprintf('<a href="%s" target="_blank" title="%s">%s</a>',esc_url('https://www.letsgodev.com/'), 'Delivery Area with Google Maps' ,'More Premiun Plugins'),
				);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}


	public function add_meta_boxes() {
		add_meta_box('areamaps_mapmeta', 'Map', [ $this, 'input_map' ], 'areamaps', 'normal', 'high');
		add_meta_box('areamaps_shortmeta', 'Shortcodes', [ $this, 'input_shortcode' ], "areamaps", "side", "low");

		do_action('areamaps/admin/meta_boxes', $this);
	}

	public function input_map() {
		global $post;

		$settings = areagm_settings();
		
		if(isset($settings['areamaps_apikey']) && $settings['areamaps_apikey'] != '') {
			wp_enqueue_script('areamaps-child', admin_url('admin-ajax.php?action=areamaps_js&id='.$post->ID), array( $settings['areamaps_handle'], 'jquery'), false, true );

			include_once AREAGM_PLUGIN_DIR . '/admin/layouts/view-admin-maps.php';
		}
		else
			echo sprintf(__('Please enter your ApiKey Google Maps in the <a href="%s" title="setting section">setting section</a>','letsgo'), admin_url('edit.php?post_type=areamaps&page=area_settings') );
	}

	public function input_shortcode() {
		global $post;

		$opts = get_post_meta($post->ID, 'areamaps_metakey',true);
		$meta_shortcode = isset($opts) ? '[areamaps id='.$post->ID.']' : '';

		echo __('Please insert this shortcode in you page','letsgo');
		echo '<input type="text" size="25" name="areamaps_shortcode" id="areamaps_shortcode" value="'.$meta_shortcode.'" readonly />';
	}

	public function map_js(){

    	$settings = areagm_settings();

    	if( isset($settings['areamaps_lat']) && !empty($settings['areamaps_lat']) )
    		$this->options_default['lat'] = $settings['areamaps_lat'];

    	if( isset($settings['areamaps_lng']) && !empty($settings['areamaps_lng']) )
    		$this->options_default['lng'] = $settings['areamaps_lng'];

		include_once AREAGM_PLUGIN_DIR. '/admin/assets/js/areamap_js.php';
		die();
	}


	public function save_post($post_id, $post, $update) {

		if ( $post->post_type != 'areamaps' )
        	return;
    	
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return;

		if( wp_is_post_revision( $post_id ) )
			return;
	 
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;

		if( isset($_POST['areamaps_coords']) && !empty($_POST['areamaps_coords']) ) {

			$array_coords = explode('),(',$_POST['areamaps_coords']);

			if( is_array($array_coords) && count($array_coords) > 0 ) {

				foreach($array_coords as $value_coords) {
					$latlng = str_replace(array("(", ")"), array("",""), $value_coords);
					$array_latlng[] = array_map('sanitize_text_field', explode(',',$latlng));
				}
			} else
				$array_latlng = $this->options_default['coords'];


			$array_save_post = array(
				'lcolor' => !empty($_POST['areamaps_lcolor']) ? sanitize_text_field($_POST['areamaps_lcolor']) : $this->options_default['lcolor'],
				'lat'	 => !empty($_POST['areamaps_lat']) ? sanitize_text_field($_POST['areamaps_lat']) : $this->options_default['lat'],
				'lng'	 => !empty($_POST['areamaps_lng']) ? sanitize_text_field($_POST['areamaps_lng']) : $this->options_default['lng'],
				'coords' => $array_latlng,
				'zoom'	 => !empty($_POST['areamaps_zoom']) ? sanitize_text_field($_POST['areamaps_zoom']) : $this->options_default['zoom']
			);

			$array_save_post = apply_filters('areamaps/admin/save', $array_save_post, $_POST);

			update_post_meta($post_id, 'areamaps_metakey', $array_save_post);
		}
		
		return $post_id;
	}


	function add_name_column($columns) {

		foreach($columns as $key_column => $value_column) {
			
			$ok_columns[$key_column] = $value_column;

			if( $key_column == 'title' )
				$ok_columns['shortcode'] = __( 'Shortcode', 'gowoo' );
		}
		
		return apply_filters('areamaps/admin/column_name', $ok_columns );
	}

	function add_value_column($column, $post_id) {

		if($column == 'shortcode') {
			$meta_shortcode = '[areamaps id='.$post_id.']';
			echo '<input type="text" size="20" value="'.$meta_shortcode.'" readonly />';
		}

		do_action('areamaps/admin/column_value', $column, $post_id);
	}
}
?>