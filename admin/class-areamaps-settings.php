<?php
class AreaGM_Settings {

	public $url_apikey = 'https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend,places_backend&keyType=CLIENT_SIDE&reusekey=true&hl=es';

	function __construct() {

		add_action('admin_menu', [ $this, 'add_plugin_page' ] );
		add_action('admin_init', [ $this, 'register_settings' ] );
		add_action('plugins_loaded', [ $this, 'verify_apikey' ] );
	}

	public function verify_apikey() {
		$settings = areagm_settings();

		if(!isset($settings['areamaps_apikey']) || $settings['areamaps_apikey'] == '')
			add_action( 'admin_notices', [ $this, 'warning_notice' ] );
	}

	public function warning_notice() {
		echo '<div class="update-nag"><p>'.sprintf(__('Please enter your ApiKey Google Maps in the <a href="%s" title="setting section">setting section</a>','letsgo'), admin_url('edit.php?post_type=areamaps&page=area_settings') ).'</p></div>'; 
	}


	public function add_plugin_page() {
		add_submenu_page('edit.php?post_type=areamaps', __('Settings','letsgo'), __('Settings','letsgo'), 'manage_options', 'area_settings', [ $this, 'add_submenu' ] );
	}

	public function add_submenu() {
		include_once AREAGM_PLUGIN_DIR.'/admin/layouts/template_options.php';
	}


	public function register_settings() {

		//unregister_setting('groupareamaps','areamaps_options');

		register_setting(
			'groupareamaps', // Option group
			'areamaps_options', // Option name
			[ $this, 'settings_sanitize' ] // Sanitize
		);

		add_settings_section(
			'gowoo-seccion', // ID
			__('Settings Areamaps','letsgo'), // Title
			[ $this, 'print_section_info' ], // Callback
			'settingareamaps' // Page
		);

		add_settings_field(
			'areamaps_apikey', // ID
			__('API GoogleMaps','letsgo'), // ApiKey
			[ $this, 'input_apikey' ], // Callback
			'settingareamaps', // Page
			'gowoo-seccion' // Section
		);

		add_settings_field(
			'areamaps_admin_js', // ID
			__('Include Google Maps Library in the Admin Panel','letsgo'), // Include Library
			[ $this, 'input_admin_jsmap' ], // Callback
			'settingareamaps', // Page
			'gowoo-seccion' // Section
		);

		add_settings_field(
			'areamaps_handle', // ID
			__('Handle Js','letsgo'), // Latitude
			[ $this, 'input_handle' ], // Callback
			'settingareamaps', // Page
			'gowoo-seccion' // Section
		);

		add_settings_field(
			'areamaps_lat', // ID
			__('Latitude Default','letsgo'), // Latitude
			[ $this, 'input_lat' ], // Callback
			'settingareamaps', // Page
			'gowoo-seccion' // Section
		);

		add_settings_field(
			'areamaps_lng', // ID
			__('Longitude Default','letsgo'), // Longitude
			[ $this, 'input_lng' ], // Callback
			'settingareamaps', // Page
			'gowoo-seccion' // Section
		);
	}

	public function settings_sanitize($array_input) {

		return $array_input;
	}

	public function input_apikey() {

		$options_current = get_option('areamaps_options', array('areamaps_apikey' => ''));
		$input_key = isset($options_current['areamaps_apikey'])?$options_current['areamaps_apikey']:'';

		echo '<label for="areamaps_apikey">
			<input type="text" id="areamaps_apikey" name="areamaps_options[areamaps_apikey]" value="'.$input_key.'" /></label>
			<p>'.sprintf(__('Enter your ApiKey Google Maps. You can generate a key <a href="%s" target="_blank">here</a>','letsgo'),$this->url_apikey).'.</p><br />';
	}

	public function input_admin_jsmap() {
		$options_current = get_option('areamaps_options', array('areamaps_admin_js' => true ));

		$checked = isset($options_current['areamaps_admin_js'])&&$options_current['areamaps_admin_js'] ? 'CHECKED' : '';

		echo '<label for="areamaps_admin_js">
			<input type="checkbox" id="areamaps_admin_js" name="areamaps_options[areamaps_admin_js]" value="1" '.$checked.' /></label>
			<p>'.__('If you uncheck this option, you should add manually the Google Maps Library in the Admin Panel','letsgo').'</p>';
	}

	public function input_handle() {
		$options_current = get_option('areamaps_options', array('areamaps_handle' => 'areamaps-js'));
		$input_key = isset($options_current['areamaps_handle'])?$options_current['areamaps_handle']:'';

		echo '<label for="areamaps_handle">
			<input type="text" id="areamaps_handle" name="areamaps_options[areamaps_handle]" value="'.$input_key.'" /></label><br />';
	}

	public function input_lat() {
		$options_current = get_option('areamaps_options');
		$input_key = isset($options_current['areamaps_lat'])?$options_current['areamaps_lat']:'';

		echo '<label for="areamaps_lat">
			<input type="text" id="areamaps_lat" name="areamaps_options[areamaps_lat]" value="'.$input_key.'" /></label><br />';
	}

	public function input_lng() {
		$options_current = get_option('areamaps_options');
		$input_key = isset($options_current['areamaps_lng'])?$options_current['areamaps_lng']:'';

		echo '<label for="areamaps_lng">
			<input type="text" id="areamaps_lng" name="areamaps_options[areamaps_lng]" value="'.$input_key.'" /></label><br />';
	}

	function print_section_info() {
		echo '<div>'.__('Options','letsgo').'</div>';
	}
}