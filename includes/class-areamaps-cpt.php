<?php
class AreaGM_Cpt {

	public function __construct() {
		add_action('init', [ $this, 'register_cpt' ]);
	}

	public function register_cpt() {
		$labels = array(
			'name'				=> __('Delivery Area', 'letsgo'),
			'menu_name'			=> __('Delivery Area', 'letsgo'),
			'name_admin_bar'	=> __('Areas List', 'letsgo'),
			'all_items'			=> __('Areas List', 'letsgo'),
			'singular_name'		=> __('Areas List', 'letsgo'),
			'add_new'			=> __('Add New Area', 'letsgo'),
			'add_new_item'		=> __('Add New Area','letsgo'),
			'edit_item'			=> __('Edit Area','letsgo'),
			'new_item'			=> __('New Area','letsgo'),
			'view_item'			=> __('View Area','letsgo'),
			'search_items'		=> __('Search Area','letsgo'),
			'not_found'			=>  __('Nothing found','letsgo'),
			'not_found_in_trash'	=> __('Nothing found in Trash','letsgo'),
			'parent_item_colon'		=> ''	
		);
		 
		$args = array(
			'labels'				=> $labels,
			'public'				=> true,
			'publicly_queryable'	=> false,
			'show_ui'				=> true,
			'query_var'				=> true,
			//'menu_icon' => plugins_url( 'images/icon_star.png' , __FILE__ ),
			'rewrite'				=> false,
			'hierarchical'			=> false,
			//'menu_position' => 25,
			'supports' => array('title', 'author'),
			'exclude_from_search'	=> true,
			'show_in_nav_menus'		=> false,
			'can_export'			=> true,
			'map_meta_cap'			=> true,
			'capability_type'		=> 'areamap',
			'capabilities'			=> array (
										'edit_post' => 'edit_areamap',
										'read_post' => 'read_areamap',
										'delete_post' => 'delete_areamap',
										'edit_posts' => 'edit_areamaps',
										'edit_others_posts' => 'edit_others_areamaps',
										'publish_posts' => 'publish_areamaps',
										'read_private_posts' => 'read_private_areamaps',	
										'delete_posts' => 'delete_areamaps',
										'delete_private_posts'   => 'delete_private_areamaps',
										'delete_published_posts' => 'delete_published_areamaps',
										'delete_others_posts' => 'delete_others_areamaps',
										'edit_private_posts' => 'edit_private_areamaps',
										'edit_published_posts' => 'edit_published_areamaps',
										'create_posts' => 'edit_areamaps'
								)
		);
		
		register_post_type( 'areamaps' , $args );
	}
}

new AreaGM_Cpt();
?>