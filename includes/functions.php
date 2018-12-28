<?php

if( !function_exists('areamaps_settings') ) {
	function areamaps_settings() {

		$default = [
					'areamaps_apikey' => '',
					'areamaps_front_js' => true,
					'areamaps_admin_js' => true,
					'areamaps_handle' => 'areamaps-js',
					'areamaps_lat'	=> '',
					'areamaps_lng' => '',
				];

		$settings = get_option('areamaps_options', apply_filters('areamaps/settings/default', $default));

		return apply_filters('areamaps/settings/values', $settings);
	}
}