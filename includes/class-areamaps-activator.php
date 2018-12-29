<?php
class AreaGM_Activator {

	static public function activate() {
		$administrator = get_role( 'administrator' );
	
		$admin_caps = array(
				'delete_areamaps',
				'delete_others_areamaps',
				'delete_private_areamaps',
				'delete_published_areamaps',
				'edit_areamaps',
				'edit_others_areamaps',
				'edit_private_areamaps',
				'edit_published_areamaps',
				'publish_areamaps',
				'read_private_areamaps'
			);
	
		foreach( $admin_caps as $cap ) {
			$administrator->add_cap( $cap );
		}
	}
}