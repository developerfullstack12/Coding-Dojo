<?php
function get_user_ids_leader_can_message( $user_id ) {

	$user_list = array();

	//checks if user is a group leder
	$group_leader = ldms_is_group_leader( $user_id );

	$cuser = wp_get_current_user();

	if( current_user_can('manage_options') ) {

		$users = get_users();
		$cuser = wp_get_current_user();

		foreach( $users as $user ) {
			if( $user->ID == $cuser->ID ) continue;
			$user_list[] = $user->ID;
		}

		return $user_list;

	} elseif( $group_leader ){
		// get ids of groups the user is a leader of
		$groups = learndash_get_administrators_group_ids( $user_id );
		$user_list = array();

		foreach($groups as $group_id){

			$user_list = array_merge($user_list, learndash_get_groups_user_ids( $group_id ) );

		}
		return $user_list;

	} elseif( in_array( 'subscriber', (array) $cuser->roles ) ) {
    	//The user has the "subscriber" role
		$groups = learndash_get_users_group_ids( $user_id );
		foreach($groups as $group_id){

			$user_list = array_merge($user_list, learndash_get_groups_administrator_ids( $group_id ) );

		}
	}
	return $user_list;
}


add_action( 'wp_ajax_nopriv_ldms_create_message', 'ldms_must_be_logged_in' );
function ldms_must_be_logged_in() {
   esc_html_e( "You must log in to create a message", "ldmessenger" );
   die();
}

add_action( 'template_redirect', 'ldms_check_for_new_message' );
function ldms_check_for_new_message() {

	if( isset( $_POST['ldms-send-to'] ) ) {

		$post_id = ldms_create_message();

		if( $post_id ) {
			wp_redirect( get_permalink($post_id) . '?success=true' );
			die();
		}

	}

}

add_action( 'wp_ajax_ldms_create_message', 'ldms_create_message' );
function ldms_create_message() {
	$result = __('Something went wrong on our end. Please try again and if the issue continues contact the system administor','ldmessenger');

	$message 		= isset( $_REQUEST['ldms-contents'] ) ? wp_kses_post( $_REQUEST['ldms-contents'] ) : '';
	$send_to_id 	= isset( $_REQUEST['ldms-send-to'] ) ? intval( $_REQUEST['ldms-send-to'] ) : '';
	$sender_id 		= isset( $_REQUEST['ldms-sender'] ) ? intval( $_REQUEST['ldms-sender'] ) : '';
	$session_title  = isset( $_REQUEST['ldms-session-title'] ) ? esc_attr( $_REQUEST['ldms-session-title'] ) : '';

	$send_to_user 	= get_user_by( 'id', $send_to_id );
	$sender_user 	= get_user_by( 'id', $sender_id );

	if ( !isset( $_REQUEST['nonce'] ) && !wp_verify_nonce( $_REQUEST['nonce'], "lmds_message_nonce" ) ) {
		exit("No naughty business please");
	}


	$attached_users = array( $send_to_id , $sender_id );

	$post_id = ldms_create_message_cpt($send_to_user, $session_title , $message, $attached_users, $sender_id);

	if( $post_id ){

		/**
		 * Send and e-mail
		 * @var string
		 */
		$result = '<h3>'.__('Session Started', 'ldmessenger').'</h3>';
		$result .= '<p><a href="'.get_permalink( $post_id ).'">'.__('View Session', 'ldmessenger' ).'</a></p>';

		$email_sent = ldms_send_email_notification($send_to_user, $message, get_permalink( $post_id), $sender_user);

		if( $email_sent ){
			$result .= '<p>'.__( 'Email notification sent', 'ldmessenger' ).'</p>';
		} else{
			$result .= '<p>'.__( 'There was an issue notifying the recipient', 'ldmessenger' ) . $email_sent . '</p>';
		}

		/**
		 * Add an unread message meta key
		 */
		 update_user_meta( $send_to_user, '_ldms_unread_message', $post_id );

		 return $post_id;

	}

	return false;

	/*
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$result = json_encode($result);
		echo $result;
	}
	else {
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}

	die(); */

}

add_action( 'wp_ajax_ldms_delete_message', 'ldms_delete_message' );
add_action( 'wp_ajax_nopriv_ldms_delete_message', 'ldms_delete_message' );
function ldms_delete_message() {

    $message_id    = $_POST[ 'message_id' ];
    $cuser      = wp_get_current_user();



	if ( !isset( $_POST['nonce'] ) && !wp_verify_nonce( $_POST['nonce'], "ldms-delete-nonce" ) ) {
		exit("No naughty business please");
	}

    if( ( $cuser->ID != get_post_field( 'post_author', $note_id ) ) && ( !current_user_can( 'delete_session' ) ) ) {
        return false;
        wp_die();
    }

    $deleted = wp_delete_post( $message_id );

    if($deleted){
    	wp_send_json_success( array( 'success' => true, 'data' => $message_id ) );
    }else{
	    wp_send_json_error( array( 'success' => false, 'data' => 'You do not have permission to delete Conversations' ) );
    }

    die();

}

function ldms_create_message_cpt($send_to_user, $session_title, $message, $attached_users, $sender_id) {

    $title = __( 'Message to ', 'ldmessenger' ) . $send_to_user->display_name;

    if( $session_title != '' ){
	   $title = $session_title;
    }


    $content =  $message;

    $post_id = wp_insert_post( array(
        'post_type'        => 'ldms_message',
        'post_title'        => $title,
        'post_content'      => $message,
        'post_status'       => 'publish',
        'post_author'       => $sender_id
    ) );

    if ( $post_id != 0 ){
	    update_post_meta( $post_id, 'session_title', $session_title );
	    update_post_meta( $post_id, 'attached_users', $attached_users );
	    update_post_meta( $post_id, 'sent_to', intval( $send_to_user->ID ) );
		update_user_meta( $send_to_user->ID, '_ldms_unread_message', $post_id );
        return $post_id;
    }else {
       return false;
    }
}

function ldms_display_user_dropdown($user_id){
	$users = get_user_ids_leader_can_message($user_id);

	foreach($users as $user_id){
		$user = get_user_by('id', $user_id);
		echo '<option value="' . esc_attr($user->ID) . '">' . esc_html($user->display_name) . '</option>';
	}
}

function ldms_user_has_messages( $user_id = NULL ) {

	$cuser 		= wp_get_current_user();
	$user_id 	= ( $user_id == NULL ? $cuser->ID : $user_id );

	$args = apply_filters( 'ldms_user_has_messages', array(
		'post_type'   		=> 'ldms_message',
		'post_status'   	=> 'publish',
		'posts_per_page'	=>	'1',
		'meta_query' => array(
			array(
				'key'     => 'attached_users',
				'value'   => serialize( $user_id ),
				'compare' => 'LIKE',
			),
		),
	) );

	$messages 		= new WP_Query($args);
	$has_messages 	= $messages->have_posts();

	wp_reset_postdata(); wp_reset_query();

	return $has_messages;

}

function ldms_get_user_messages( $user_id = NULL ) {

	$cuser 		= wp_get_current_user();
	$user_id 	= ( $user_id == NULL ? $cuser->ID : $user_id );
	$paged		= ( get_query_var('paged') ? get_query_var('paged') : 1 );

	$args = apply_filters( 'ldms_get_user_messages', array(
		'post_type'   	=> 'ldms_message',
		'post_status'   => 'publish',
		'paged'			=> $paged,
		'meta_query' => array(
			array(
				'key'     => 'attached_users',
				'value'   => serialize( $user_id ),
				'compare' => 'LIKE',
			),
		),
	) );

	return new WP_Query($args);

}

/**
 * Get the users unread message count.
 *
 * Stores a metakey of _ldms_unread_message in the users meta table with the post_id
 * @param  [int] $user_id User ID, optional
 * @return [int]          count
 */
function ldms_get_user_unread_message_count( $user_id = NULL ) {

	$cuser 		= wp_get_current_user();
	$user_id 	= ( $user_id == NULL ? $cuser->ID : $user_id );

	return intval( count(get_user_meta( $user_id, '_ldms_unread_message', false )) );

}


add_filter( 'ldms_get_user_messages', 'ldps_show_all_user_messages' );
function ldps_show_all_user_messages( $args ) {

	$settings 	= get_option('ldms_private_sessions_settings');
	$roles 		= array_keys($settings['ldms_user_moderators']);

    $user = wp_get_current_user();

    foreach( $roles as $role ) {

        if( in_array( $role, (array) $user->roles ) ) {
			unset($args['meta_query']);
        }

    }

    return $args;


}

add_filter( 'ldms_user_can_view_thread', 'ldps_show_individual_message_to_users', 10, 2 );
function ldps_show_individual_message_to_users( $value, $user_id ) {

    $user  = wp_get_current_user();

	$settings 	= get_option('ldms_private_sessions_settings');
	$roles 		= array_keys($settings['ldms_user_moderators']);

    foreach( $roles as $role ) {
        if( in_array( $role, (array) $user->roles ) ) {
            return true;
        }
    }

    return $value;

}

function ldms_is_moderator() {

	$user  = wp_get_current_user();

	$settings 	= get_option('ldms_private_sessions_settings');
	$roles 		= array_keys($settings['ldms_user_moderators']);

	foreach( $roles as $role ) {
		if( in_array( $role, (array) $user->roles ) ) {
			return true;
		}
	}


	return false;

}

function ldms_enable_title(){

	$enable_title = false;

	$settings = get_option( 'ldms_private_sessions_settings', array() );

	if( $settings['ldms_enable_title'] === 'yes'){
		$enable_title = true;
	}

	return apply_filters( 'ldms_enable_title', $enable_title );
}

add_action('learndash_delete_user_data','ldms_delete_user_data',10,1);

function ldms_delete_user_data($user_id){

	$sessions = get_posts( array( 'author' => $user_id, 'post_type' => 'ldms_message' ) );

	foreach($sessions as $session){
		wp_delete_post( $session->ID , true );
	}

	$messages = get_posts( array( 'user_id' => $user_id, 'post_type' => 'ldms_message' ) );

	foreach($messages as $message){
		wp_delete_post( $message->comment_ID , true );
	}


}
