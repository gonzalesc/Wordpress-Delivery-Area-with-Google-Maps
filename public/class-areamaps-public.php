<?php
class AreaGM_Public {

	function __construct() {
		//add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] ,20 );
		add_shortcode('areamaps', [ $this, 'add_shortcode' ] );

		add_action('wp_ajax_frontmap_js', [ $this, 'show_areamaps' ] );
		add_action('wp_ajax_nopriv_frontmap_js', [ $this, 'show_areamaps' ] );
	}

	function add_shortcode( $atts ) {

		ob_start();
		$settings = areagm_settings();

		$param = shortcode_atts( array(
			'id'		=> 0,
			'w'			=> '100%',
			'h'			=> '300px',
			'lib'		=> 'yes',
			'handle'	=> $settings['areamaps_handle']
		), $atts );

		if( isset($param['id']) && is_numeric($param['id']) && $param['id'] > 0 ) {

			$id = intval($param['id']);
			$stylemaps = 'width:'.$param['w'].';height:'.$param['h'].';';

		
			if( !empty($param['handle']) ) {

				if( $param['lib'] == 'yes' ) {
					wp_enqueue_script($param['handle'], '//maps.googleapis.com/maps/api/js?key='.$settings['areamaps_apikey'].'&libraries=geometry', array('jquery'), false, true);
				}

				wp_enqueue_script('areamaps-child-'.$id, admin_url('admin-ajax.php?action=frontmap_js&id='.$id), array($param['handle'],'jquery'), false, true);

				echo '<div id="areamaps-'.$id.'" style="'.$stylemaps.'"></div>';
			}
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}

	function show_areamaps() {
		include_once AREAGM_PLUGIN_DIR. '/public/assets/js/frontmap_js.php';
		die();
	}
}
?>