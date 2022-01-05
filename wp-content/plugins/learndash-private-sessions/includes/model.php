<?php
// Register Custom Post Type
function ldma_create_cpt() {

	$labels = array(
		'name'               => _x( 'Messages', 'Message general name', 'ldmessenger' ),
		'singular_name'      => _x( 'Message', 'Message singular name', 'ldmessenger' ),
		'menu_name'          => _x( 'Messages', 'admin menu', 'ldmessenger' ),
		'name_admin_bar'     => _x( 'Message', 'add new message on admin bar', 'ldmessenger' ),
		'add_new'            => _x( 'Add New', 'message', 'ldmessenger' ),
		'add_new_item'       => __( 'Add New Message', 'ldmessenger' ),
		'new_item'           => __( 'New Message', 'ldmessenger' ),
		'edit_item'          => __( 'Edit Message', 'ldmessenger' ),
		'view_item'          => __( 'View Message', 'ldmessenger' ),
		'all_items'          => __( 'All Messages', 'ldmessenger' ),
		'search_items'       => __( 'Search Messages', 'ldmessenger' ),
		'parent_item_colon'  => __( 'Parent Messages:', 'ldmessenger' ),
		'not_found'          => __( 'No messages found.', 'ldmessenger' ),
		'not_found_in_trash' => __( 'No messages found in Trash.', 'ldmessenger' )
	);


	$args = array(
		'label'                 => __( 'Message', 'ldmessenger' ),
		'description'           => __( 'Message Description', 'ldmessenger' ),
		'labels'                => $labels,
		'supports'              => array( ),
		// TODO consider adding a message status
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		// TODO set 'show_ui' to false when launching to hide from backend.
		'show_ui'               => true,

		//TODO consider adding menu to LearnDash Menu
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => false,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'supports'           => array( 'editor','comments')
	);
	register_post_type( 'ldms_message', $args );

	if( get_option('ldma_version') != LDMA_VER ) {
		flush_rewrite_rules();
		update_option( 'ldma_version', LDMA_VER );
	}

}
add_action( 'init', 'ldma_create_cpt', 0 );




// Disable Feed Links
/*
add_action( 'after_setup_theme', 'head_cleanup' );

function head_cleanup(){

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // disable comments feed
    add_filter( 'feed_links_show_comments_feed', '__return_false' );

}
*/



//create sub menu
function ldma_add_learndash_submenu($add_submenu){
	if ( current_user_can('edit_courses') ) {
		$add_submenu['messages'] = array(
			'name' 	=> _x( 'Messages', 'Settings Menu Label', 'ldmessenger' ),
			'cap'	=> 'edit_courses',
			'link'	=> 'edit.php?post_type=ldms_message',
		);

	}
	return $add_submenu;
}
// add_filter( 'learndash_submenu', 'ldma_add_learndash_submenu' );

//create tabs
function ldma_add_learndash_submenu_tabs($admin_tabs){

	$admin_tabs['create-message'] = array(
			'link'	=> 'post-new.php?post_type=message',
			'name'	=> _x( 'Create Message', 'Create Message Label', 'ldmessenger' ),
			'id'	=> 'message',
			'menu_link'	=> 'edit.php?post_type=ldms_message',
		);

	$admin_tabs['view-messages'] = array(
			'link'	=> 'edit.php?post_type=message',
			'name'	=> _x( 'View Message', 'View Message Label', 'ldmessenger' ),
			'id'	=> 'edit-message',
			'menu_link'	=> 'edit.php?post_type=ldms_message',
		);


	return $admin_tabs;
}

//add_filter( 'learndash_admin_tabs', 'ldma_add_learndash_submenu_tabs' );

//display tabs
function ldma_add_learndash_tabs_on_page( $admin_tabs_on_page, $admin_tabs, $current_page_id ){


		$admin_tabs_on_page['message'] = array( 'create-message','view-messages' );
		$admin_tabs_on_page['edit-message'] = array('create-message','view-messages' );

	return $admin_tabs_on_page;

}
//add_filter( 'learndash_admin_tabs_on_page', 'ldma_add_learndash_tabs_on_page' );

function ldma_user_has_new_messages( $user_id = NULL ) {

	$cuser 		= wp_get_current_user();
	$user_id 	= ( $user_id == NULL ? $cuser->ID : $user_id );

	return ( ldms_get_user_unread_message_count() == 0 ? false : true );

}

add_action( 'wp_insert_comment', 'ldma_new_comment_actions', 10, 2 );
function ldma_new_comment_actions( $comment_id, $comment ) {

	$post_id = $comment->comment_post_ID;

	// Not a message, return
	if( get_post_type($post_id) != 'ldms_message' ) return;

	$participants = get_post_meta( $post_id, 'attached_users', true );

	/**
	 * Set an unread message indication for the non-commenting participant
	 * @var [type]
	 */
	foreach( $participants as $participant ) {

		if( $participant != $comment->user_id ) {
			update_user_meta( $participant, '_ldms_unread_message', $post_id );
			//send email
			$sender_user = get_user_by('id', $comment->user_id );
			$send_to_user = get_user_by('id', $participant );
			ldms_send_email_notification($send_to_user, $comment->comment_content , get_comment_link( $comment->comment_ID ), $sender_user, true);
		}

	}

}

add_filter( 'comment_form_field_comment', 'ldms_wysiwyg_comment_field' );
function ldms_wysiwyg_comment_field( $field ) {

	global $post;

	if( get_post_type($post) !== 'ldms_message' ) return $field;

	ob_start();

	$settings = apply_filters( 'ldms_comment_form_field_wysiwyg', array(
		'media_buttons'	=>	false
	) );

	wp_editor( '', 'comment', $settings );

	$editor = ob_get_contents();

	ob_end_clean();

	$editor = str_replace( 'post_id=0', 'post_id='.get_the_ID(), $editor );

	return $editor;

}

function ldms_get_option( $option = null ) {

	if( $option == null ) return false;

	$settings = get_option('ldms_private_sessions_settings');

	if( !isset( $settings[$option]) ) return false;

	return $settings[$option];

}

function ldms_delete_option( $option = null ) {

	if( $option == null ) return false;

	$settings 		= get_option( 'ldms_private_sessions_settings' );
	$new_settings 	= array();

	if( !isset( $settings[$option] ) ) return false;

	foreach( $settings as $setting ) {
		if( key($setting) != $option ) $new_settings[] = $setting;
	}

	update_option( 'ldms_private_sessions_settings', $new_settings );

}

function ldms_update_option( $option = null , $value = null ) {

	if( $option == null || $value == null ) return false;

	$settings			= get_option('ldms_private_sessions_settings');
	$settings[$option] 	= $value;

	update_option( 'ldms_private_sessions_settings', $settings );

}

add_action( 'init', 'ldms_create_user_permissions');
function ldms_create_user_permissions() {

    $permissions = apply_filters( 'ldms_create_user_permissions', array(
        'start_session',
        'delete_session',
    ) );

    $admins = apply_filters( 'ldms_admin_roles', array(
        get_role('editor'),
        get_role('administrator'),
        get_role('group_leader')
    ) );

    foreach( $admins as $admin ) {
        foreach( $permissions as $permission ) $admin->add_cap($permission);
    }

	$added_users = ldms_get_option('ldms_users_can_start');

	// get all users roles
	global $wp_roles;

	if ( ! isset( $wp_roles ) ){
		$wp_roles = new WP_Roles();
	}

	// create array of users
	$roles = $wp_roles->get_names();

	//remove defaults
	unset($roles['administrator']);
	unset($roles['editor']);
	unset($roles['group_leader']);


	// cycle each user role and if that user role is selected add the cap if not remove it.
	foreach( $roles as $key => $user ) {
        if(array_key_exists($key, $added_users)){
	        $user = get_role($key);
	        $user->add_cap('start_session');
        }else{
	        $user = get_role($key);
	        $user->remove_cap('start_session');
        }
    }
}
