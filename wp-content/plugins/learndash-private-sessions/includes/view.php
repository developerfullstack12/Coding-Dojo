<?php
add_action( 'wp_footer', 'ldms_messages_tab_placement' );
function ldms_messages_tab_placement() {

	$position 	= ldms_get_option('ldms_indicator_location');
	$skips 		= array(
		'shortcode',
		'widget'
	);

	if( !in_array( $position, $skips ) ) ldms_messages_tab();

}

function ldms_messages_tab() {

	wp_reset_postdata();

	global $post;

	// First big gate
	if( !current_user_can('manage_options') ) {

	    // Skip if the shortcode is displayed, the user is not logged in or the user can't view
	    if( has_shortcode( $post->post_content, 'private_sessions' ) || !is_user_logged_in() || !ldms_user_can_view() ) return;

	    // Normal users who don't have messages shouldn't see the tab
	    if( !ldms_is_group_leader() && !ldms_user_has_messages() && !current_user_can('start_session') ) return;

	}

	$location		= ldms_get_option('ldms_indicator_location');
	$class 			= 'ldms-located-' . $location;
    $label 			= '';
    $new_messages 	= ldms_get_user_unread_message_count();
	$placement		= ( $location == 'top' ? 'bottom' : 'top' );
	$tooltip		= ( $location == 'right' ? '' : 'js-ldms-tooltip' ); ?>

    <div class="ldms-message-tab <?php esc_attr_e($class); ?>">
        <?php if( ldms_is_group_leader() || current_user_can('manage_options') || current_user_can('start_session') ): ?>
            <a class="ldms-new-message-link <?php esc_attr_e($tooltip); ?>" data-toggle="tooltip" data-placement="<?php esc_attr_e($placement); ?>" title="<?php esc_html_e( 'Create a New Private Session', 'ldmessenger' ); ?>" href="<?php echo esc_url( apply_filters( 'ldms_session_page_link' , get_the_permalink( ldms_get_option('ldma_sessions_page') ) ) ); ?>#ldms-new-session"><img src="<?php echo esc_url( LDMA_URL . 'assets/svg/write.svg' ); ?>" alt="<?php esc_attr_e( 'New Message', 'ldmessenger' ); ?>"></a>
        <?php endif; ?>
        <a href="<?php echo esc_url( apply_filters( 'ldms_session_page_link' , apply_filters( 'ldms_session_page_link' , get_the_permalink( ldms_get_option('ldma_sessions_page') ) ) ) ); ?>">
            <span class="ldms-message-count <?php esc_attr_e($tooltip); ?>" data-toggle="tooltip" data-placement="<?php esc_attr_e($placement); ?>" title="<?php echo esc_html( $new_messages ) . _n( ' New Message', ' New Messages', $new_messages, 'ldmessenger' ); ?>"><?php echo esc_html($new_messages); ?></span>
            <?php esc_html_e( 'My Sessions', 'ldmessenger' ); ?>
        </a>
    </div>
    <?php
}

function ldms_messages_widget($user_can_start) {
	global $post;

     // Skip if the shortcode is displayed, the user is not logged in or the user can't view
    if( ( has_shortcode( $post->post_content, 'display_messages' ) || !is_user_logged_in() ) || !ldms_user_can_view() && $user_can_start === 'false' ) return;

	if( $user_can_start === 'false' ){
    	// Normal users who don't have messages shouldn't see the tab
    	if( !ldms_is_group_leader() && !ldms_user_has_messages() && !current_user_can('manage_options') ) return;
	}


    $label = '';
    $new_messages = ldms_get_user_unread_message_count(); ?>

    <div id="ldms-message-widget">

        <?php if( ldms_is_group_leader() || current_user_can('manage_options') || current_user_can('start_session') || $user_can_start != 'false' ): ?>
            <p>
				<a class="ldms-btn" href="<?php echo esc_url( apply_filters( 'ldms_session_page_link' , get_the_permalink( ldms_get_option('ldma_sessions_page') ) ) ); ?>#ldms-new-session">
					<span class="ldms-btn-icon">
						<img src="<?php echo esc_url( LDMA_URL . '/assets/svg/write.svg' ); ?>" alt="<?php esc_attr_e( 'New Session', 'ldmessenger' ); ?>">
					</span>
					<span class="ldms-btn-text">
						<?php esc_html_e( 'New Session', 'ldmessenger' ); ?>
					</span>
				</a>
			</p>
        <?php endif; ?>
        <p>
			<a class="ldms-btn" href="<?php echo esc_url( apply_filters( 'ldms_session_page_link' , get_the_permalink( ldms_get_option('ldma_sessions_page') ) ) ); ?>">
				<span class="ldms-btn-icon" title="<?php echo esc_attr( $new_messages ) . __( ' New Messages', 'ldmessenger' ); ?>">
					<span class="ldms-btn-msgs">
						<?php echo esc_html($new_messages); ?>
					</span>
				</span>
				<span class="ldms-btn-text">
	            	<?php esc_html_e( 'My Sessions', 'ldmessenger' ); ?>
				</span>
        	</a>
		</p>
    </div>

    <?php
}

function ldms_sessions_text_link( $atts = null ) {

	global $post;

	// Skip if the shortcode is displayed, the user is not logged in or the user can't view
	if( has_shortcode( $post->post_content, 'display_messages' ) || !is_user_logged_in() || !ldms_user_can_view() ) return;

	// Normal users who don't have messages shouldn't see the tab
	if( !ldms_is_group_leader() && !ldms_user_has_messages() &&  !current_user_can('manage_options') ) return;

	$label 			= '';
	$new_messages 	= ldms_get_user_unread_message_count();

	$markup = ( !isset($atts['link']) && $atts['link'] != 'off' ?
			array(
				'<a class="ldms-text-link" href="' . esc_url( apply_filters( 'ldms_session_page_link' , get_the_permalink( ldms_get_option('ldma_sessions_page') ) ) ) . '">',
				'</a>'
			) : array(
				'<span class="ldms-text-link">',
				'</span>'
			)
		); ?>

	<?php echo wp_kses_post($markup[0]); ?>
		<span class="ldms-btn-msgs">
			<?php echo esc_html($new_messages); ?>
		</span>
		<span class="ldms-btn-text">
			<?php esc_html_e( 'My Sessions', 'ldmessenger' ); ?>
		</span>
	<?php echo wp_kses_post($markup[1]);

}

function ldms_list_messages( $user_id = NULL ){

	$cuser 		= wp_get_current_user();
	$user_id 	= ( $user_id == NULL ? $cuser->ID : $user_id );

	$messages 			= ldms_get_user_messages($user_id);
	$unread_messages 	= get_user_meta( $user_id, '_ldms_unread_message', false );

	if($messages->have_posts()){ ?>
		<div id="ldms-message-list">
			<table>
				<thead>
					<tr>
						<th><?php esc_html_e( 'Title', 'ldmessenger' ); ?></th>
						<th class="ldms-text-center"><?php esc_html_e( 'Messages', 'ldmessenger' );?></th>
						<th><?php esc_html_e( 'Started', 'ldmessenger' );?></th>
						<th><?php esc_html_e( 'Last Response', 'ldmessenger' ); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php

				while($messages->have_posts()){ $messages->the_post();

					$user 			= wp_get_current_user();
					$sent_to_id 	= get_post_meta( get_the_id(), 'sent_to', true);
					$session_title 	= get_post_meta( get_the_id(), 'session_title', true);

					$sent_to 		= get_user_by( 'id', $sent_to_id );
					$username		= ( $user->ID == $sent_to_id ? get_the_author() : $sent_to->display_name );
					$result 		= '';
					$message_count 	= intval(get_comments_number( '0', '1', '%' ));
					$message_count++;
					$class 			= ( in_array( get_the_ID(), $unread_messages ) ? 'unread-messages' : '' );

					if( empty($session_title) ) {
						$session_title = __( 'Session with ' . $username );
					}

				    $args = array(
				    	'number' => '1',
				        'post_id' => get_the_id()
				    );
				    $comments 	= get_comments($args);

				    if( !empty($comments) ){
						$last_comment 	= end($comments);
						$link 			= get_comment_link($last_comment);
						$last_responded = get_comment_date( get_option('date_format') . ' ' . get_option('time_format'), $last_comment );
					}else{
						$link = get_permalink();
						$last_responded = get_the_time( get_option('date_format') . ' - ' . get_option('time_format') );
					}?>

					<tr class="<?php echo esc_attr($class); ?>" data-js-url="<?php echo esc_url($link); ?>">
						<td data-label="<?_e('Title', 'ldmessenger');?>">
							<strong><?php echo esc_html($session_title); ?></strong> <span class="ld-username"><?php echo esc_html($username); ?></span>
						</td>
						<td data-label="<?_e('Messages', 'ldmessenger');?>" class="ldms-text-center"><?php echo esc_html($message_count); ?> <img class="ldms-comment-icon" src="<?php echo esc_url( LDMA_URL . '/assets/svg/comments.svg' ); ?>" alt="<?php esc_attr_e( 'Comments', 'ldmessenger' ); ?>"></td>
						<td data-label="<?_e('Started', 'ldmessenger');?>" ><?php esc_html_e(get_the_date( get_option('date_format') ) ); ?></td>
						<td data-label="<?_e('Last Response', 'ldmessenger');?>" ><?php esc_html_e($last_responded); ?></td>
						<td>
							<a class="link ldms-view-message" href="<?php echo esc_url($link); ?>"><?php esc_html_e('View','ldmessenger');?></a>

						<?php if( current_user_can( 'delete_session' ) ){?>

							<a class="link ldms-delete-message" data-nonce="<?php echo wp_create_nonce( 'ldms-delete-nonce' );?>" data-message="<?php echo get_the_ID(); ?>" href="#"><img class="ldms-delete-icon" src="<?php echo esc_url( LDMA_URL . '/assets/svg/trash.svg' ); ?>" alt="<?php esc_attr_e( 'Comments', 'ldmessenger' ); ?>"></a>

						<?php } ?>
						</td>
					</tr>
				<?php }?>
				</tbody>
			</table>
			<?php if ($messages->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
			  <nav class="ldms-next-posts">
			    <div class="ldms-prev-posts-link">
			      <?php echo get_next_posts_link( __( 'Older Sessions', 'ldmessenger' ) , $messages->max_num_pages ); // display older posts link ?>
			    </div>
			    <div class="ldms-next-posts-link">
			      <?php echo get_previous_posts_link( __( 'Newer Sessions', 'ldmessenger' ) ); // display newer posts link ?>
			    </div>
			  </nav>
			<?php } ?>
		</div>
	<?php }else{?>
			<div class="messages"><?php esc_html_e('No Messages','ldmessenger');?></div>
	<?php }

	 wp_reset_postdata(); wp_reset_query(); //endwhile

}


// Check if user can view the messages
function ldms_content_filter($content) {

    if( !is_singular( 'ldms_message') ) return $content;

    if( !is_user_logged_in() ) {
        ldms_kill_comments();
        return wp_login_form( array( 'redirect' => get_the_permalink() ) );
    }

    if( !ldms_user_can_view_thread() ) {
        ldms_kill_comments();
        return '<h3>'.__('You do not have access to view this message','ldmessenger').'</h3>';
    }

    /**
     * No longer mark this message as being unviewed
     *
     */
    $cuser = wp_get_current_user();
    delete_user_meta( $cuser->ID, '_ldms_unread_message', get_the_ID() );

    // Include the template;
	// include(LDMA_PATH . '/templates/ldms_comments.php');

	ob_start(); ?>

	<div id="ldms-return-to-message">
		<p><a href="<?php echo esc_url( apply_filters( 'ldms_session_page_link' , get_the_permalink( ldms_get_option('ldma_sessions_page') ) ) ); ?>">&laquo; <?php esc_html_e( 'Back to Private Sessions', 'ldmessenger' ); ?></a></p>
	</div>

	<?php

	if( isset($_GET['success']) ): ?>
		<div class="ldms-success">
			<p><?php esc_html_e( 'Private Session Started', 'ldmessenger' ); ?></p>
		</div>
	<?php endif;

	include( LDMA_PATH . '/templates/partials/original-comment.php' );

	return ob_get_clean() . ldms_comments();

}

add_action( 'wp_head', 'ldms_noindex_nofollow');
function ldms_noindex_nofollow() {

	if( get_post_type() != 'ldms_message' ) return;

	echo '<meta name="robots" content="noindex,nofollow">';

}
