<?php

class AreaGM {

	/**
	 * Plugin Instance
	 */
	protected static $_instance = null;

	/**
	 * Ensures only one instance is loaded or can be loaded.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	function __construct() {

		$this->load_dependencies();
		$this->set_locale();
		$this->set_objects();
	}

	function load_dependencies() {

		require_once AREAGM_PLUGIN_DIR . '/includes/functions.php';
		require_once AREAGM_PLUGIN_DIR . '/includes/class-areamaps-i18n.php';
		require_once AREAGM_PLUGIN_DIR . '/includes/class-areamaps-cpt.php';
		require_once AREAGM_PLUGIN_DIR . '/public/class-areamaps-public.php';

		if( is_admin() ) {
			require_once AREAGM_PLUGIN_DIR . '/admin/class-areamaps-settings.php';
			require_once AREAGM_PLUGIN_DIR . '/admin/class-areamaps-admin.php';
		}
	}


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the ShipArea_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 */
	private function set_locale() {

		$plugin_i18n = new AreaGM_i18n();
		$plugin_i18n->set_domain( 'letsgo' );

		add_action( 'plugins_loaded', [ $plugin_i18n, 'load_plugin_textdomain' ] );
	}

	/**
	 * Set all global objects
	 */
	private function set_objects() {
		if( is_admin() ) {
			$this->admin    = new AreaGM_Admin();
			$this->settings = new AreaGM_Settings();
		}
		
		$this->public = new AreaGM_Public();
	}
}

?>