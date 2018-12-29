<?php
class AreaGM_Public {

	function __construct() {
		//add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] ,20 );
		add_shortcode('areamaps', [ $this, 'add_shortcode' ] );

		add_action('wp_ajax_frontareamaps', [ $this, 'show_areamaps' ] );
		add_action('wp_ajax_nopriv_frontareamaps', [ $this, 'show_areamaps' ] );
	}

	function add_shortcode( $atts ) {

		ob_start();

		$param = shortcode_atts( array(
			'id' => 0,
			'w' => '100%',
			'h' => '300px'
		), $atts );

		if( isset($param['id']) && is_numeric($param['id']) ) {

			$stylemaps = 'width:'.$param['w'].';height:'.$param['h'].';';

			$settings = areagm_settings();
		
			if( $settings['areamaps_front_js'] == true &&
				isset($settings['areamaps_handle']) && !empty($settings['areamaps_handle'])
			) {
				wp_enqueue_script($settings['areamaps_handle'], '//maps.googleapis.com/maps/api/js?key='.$settings['areamaps_apikey'].'&v=3.exp&libraries=geometry', array('jquery'), false, true);
			}

			wp_enqueue_script('areamaps-child-'.$param['id'], admin_url('admin-ajax.php?action=frontareamaps&id='.$param['id']), array($settings['areamaps_handle'],'jquery'), false, true);

			echo '<div id="areamaps-'.$param['id'].'" style="'.$stylemaps.'"></div>';
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}

	function show_areamaps() {
		include_once AREAMAPS_PLUGIN_DIR. '/public/assets/js/frontmap_js.php';
		die();
	}
}
?>