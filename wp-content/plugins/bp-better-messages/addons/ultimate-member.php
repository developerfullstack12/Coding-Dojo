<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'BP_Better_Messages_Ultimate_Member' ) ){

    class BP_Better_Messages_Ultimate_Member
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new BP_Better_Messages_Ultimate_Member();
            }

            return $instance;
        }

        public function __construct(){
            add_filter( 'um_user_profile_tabs', array( $this, 'um_add_profile_tab' ), 200 );

            add_action( 'um_profile_content_messages_default', array( $this, 'um_content_messages' ), 1 );

            if( BP_Better_Messages()->settings['chatPage'] === '0' ) {
                add_filter('bp_better_messages_page', array($this, 'um_message_page_url'), 10, 2);
            }

            if( BP_Better_Messages()->settings['umProfilePMButton'] === '1' ) {
                add_action('um_profile_navbar', array($this, 'um_profile_message_button'), 5);
            }

            if( BP_Better_Messages()->settings['userListButton'] == '1' ) {
                add_action('um_members_just_after_name_tmpl', array($this, 'um_pm_link'), 10);
                add_action('um_members_list_just_after_actions_tmpl', array( $this, 'um_pm_link' ), 10);
            }

            add_filter( 'bp_core_get_userlink', array( $this, 'um_member_link' ), 10, 2 );

            if( BP_Better_Messages()->settings['umOnlyFriendsMode'] === '1' && class_exists('UM_Friends_API') ) {
                add_filter('bp_better_messages_can_send_message',  array($this, 'disable_non_friends_reply'), 10, 3);
                add_action('bp_better_messages_before_new_thread', array($this, 'disable_start_thread_for_non_friends'), 10, 2);
            }

            if( BP_Better_Messages()->settings['umOnlyFollowersMode'] === '1' && class_exists('UM_Followers_API') ) {
                add_filter('bp_better_messages_can_send_message',  array($this, 'disable_non_followers_reply'), 10, 3);
                add_action('bp_better_messages_before_new_thread', array($this, 'disable_start_thread_for_non_followers'), 10, 2);
            }
        }

        public function disable_start_thread_for_non_followers(&$args, &$errors){
            if( ! class_exists('UM_Followers_API') ) {
                return null;
            }

            if( current_user_can('manage_options' ) ) {
                return null;
            }

            $recipients = $args['recipients'];

            if( ! is_array( $recipients ) ) $recipients = [ $recipients ];

            $notFollowed = array();

            foreach($recipients as $recipient){
                $user = get_user_by('slug', $recipient);

                $allowed = UM()->Followers_API()->api()->followed( get_current_user_id(), $user->ID );

                if( ! $allowed ) {
                    $allowed = UM()->Followers_API()->api()->followed($user->ID, get_current_user_id() );
                }

                if( ! $allowed ) {
                    $notFollowed[] = BP_Better_Messages()->functions->get_name($user->ID);
                }
            }

            if(count($notFollowed) > 0){
                $message = sprintf(_x('%s need to be followed to start new conversation', 'Ultimate member - follower restriction', 'bp-better-messages'), implode(', ', $notFollowed));
                $errors[] = $message;
            }

        }

        public function disable_non_followers_reply( $allowed, $user_id, $thread_id ){
            if( ! class_exists('UM_Followers_API') ) {
                return $allowed;
            }

            $participants = BP_Better_Messages()->functions->get_participants($thread_id);
            if( count($participants['users']) !== 2) return $allowed;
            unset($participants['users'][$user_id]);
            reset($participants['users']);

            $user_id_2 = key($participants['users']);
            /**
             * Allow users reply to admins even if not friends
             */
            if( current_user_can('manage_options') || user_can( $user_id_2, 'manage_options' ) ) {
                return $allowed;
            }

            $allowed = UM()->Followers_API()->api()->followed( $user_id, $user_id_2 );

            if( ! $allowed ) {
                $allowed = UM()->Followers_API()->api()->followed($user_id_2, $user_id);
            }

            if( ! $allowed ){
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['follow_needed'] = _x('You must follower this user to send messages', 'Ultimate member - follower restriction', 'bp-better-messages');
            }

            return $allowed;
        }

        public function disable_start_thread_for_non_friends(&$args, &$errors){
            if( ! class_exists('UM_Friends_API') ) {
                return null;
            }

            if( current_user_can('manage_options' ) ) {
                return null;
            }

            $recipients = $args['recipients'];

            if( ! is_array( $recipients ) ) $recipients = [ $recipients ];

            $notFriends = array();

            foreach($recipients as $recipient){
                $user = get_user_by('slug', $recipient);

                if( ! UM()->Friends_API()->api()->is_friend( get_current_user_id(), $user->ID ) ) {
                    $notFriends[] = BP_Better_Messages()->functions->get_name($user->ID);
                }
            }

            if(count($notFriends) > 0){
                $message = sprintf(__('%s not on your friends list', 'bp-better-messages'), implode(', ', $notFriends));
                $errors[] = $message;
            }

        }

        public function disable_non_friends_reply( $allowed, $user_id, $thread_id ){
            if( ! class_exists('UM_Friends_API') ) {
                return $allowed;
            }

            $participants = BP_Better_Messages()->functions->get_participants($thread_id);
            if( count($participants['users']) !== 2) return $allowed;
            unset($participants['users'][$user_id]);
            reset($participants['users']);

            $friend_id = key($participants['users']);
            /**
             * Allow users reply to admins even if not friends
             */
            if( current_user_can('manage_options') || user_can( $friend_id, 'manage_options' ) ) {
                return $allowed;
            }

            $allowed = UM()->Friends_API()->api()->is_friend( $user_id, $friend_id );

            if( ! $allowed ){
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['friendship_needed'] = __('You must become friends to send messages', 'bp-better-messages');
            }

            return $allowed;
        }

        public function um_message_page_url( $url, $user_id ){
            $um_profile_url = um_user_profile_url( $user_id );
            return add_query_arg( ['profiletab' => 'messages'], $um_profile_url );
        }

        public function um_add_profile_tab( $tabs ) {
            $user_id  = (int) um_profile_id();
            $can_view = is_user_logged_in() && get_current_user_id() === $user_id;

            if( $can_view ) {
                $tabs['messages'] = array(
                    'name' => __('Messages', 'bp-better-messages'),
                    'icon' => 'um-faicon-envelope-o',
                    'default_privacy' => 3,
                );
            }

            return $tabs;
        }

        public function um_content_messages( $args ) {
            echo BP_Better_Messages()->functions->get_page( true );
        }


        public function um_pm_link( $args ){
            if ( ! is_user_logged_in() ) return;

            $base_url = BP_Better_Messages()->functions->get_link(get_current_user_id());

            $args = [
                'new-message' => '',
                'to' => '{{{user.id}}}'
            ];

            if( BP_Better_Messages()->settings['fastStart'] == '1'){
                $args['fast'] = '1';
            }

            $url = add_query_arg( $args, $base_url );

            $class = 'um-members-bpbm-btn';

            if( doing_action('um_members_list_just_after_actions_tmpl') ){
                $class .= ' um-members-list-footer-button-wrapper';
            }
            echo '<div class="' . $class . '">';
            echo '<a href="' . $url . '" class="um-button um-alt" target="_self">' . __('Private Message', 'bp-better-messages') . '</a>';
            echo '</div>';
        }

        public function um_member_link($link, $user_id){
            $um_profile_url = um_user_profile_url( $user_id );
            return $um_profile_url;
        }

        public function um_profile_message_button( $args ){
            if( ! function_exists('um_profile_id') ) return false;
            $user_id = um_profile_id();

            if ( is_user_logged_in() ) {
                if ( get_current_user_id() == $user_id ) {
                    return;
                }
            }
            ?>
            <div class="um-messaging-btn">
                <?php echo do_shortcode( '[bp_better_messages_pm_button text="' . __('Private Message', 'bp-better-messages') . '" target="_self" fast_start="1" user_id="' . $user_id . '"]' ) ?>
            </div>
            <?php
        }


    }
}

