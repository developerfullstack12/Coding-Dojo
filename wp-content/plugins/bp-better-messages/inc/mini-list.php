<?php
defined( 'ABSPATH' ) || exit;

class BP_Better_Messages_Mini_List
{

    public static function instance()
    {

        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been run previously
        if ( null === $instance ) {
            $instance = new BP_Better_Messages_Mini_List;
            $instance->setup_actions();
        }

        // Always return the instance
        return $instance;

        // The last metroid is in captivity. The galaxy is at peace.
    }

    public function setup_actions()
    {
        add_action('wp_footer', array( $this, 'html' ), 199);
    }


    public function html(){
        if( ! is_user_logged_in() ) return false;

        if (defined('WP_DEBUG') && true === WP_DEBUG) {
            // some debug to add later
        } else {
            error_reporting(0);
        }

        $user_id = get_current_user_id();
        $user    = get_userdata( $user_id );
        $roles   = $user->roles;

        $tabs = array();
        if(BP_Better_Messages()->settings['miniThreadsEnable'] === '1') {
            $restricted_roles = BP_Better_Messages()->settings['restrictViewMiniThreads'];
            $is_restricted = false;

            if( count( $restricted_roles ) > 0 ) {
                foreach( $restricted_roles as $restricted_role ){
                    if( in_array( $restricted_role, $roles ) ){
                        $is_restricted = true;
                    }
                }
            }

            if( ! $is_restricted ) {
                $tabs['messages'] = 'messages';
            }
        }

        if(BP_Better_Messages()->settings['miniFriendsEnable'] === '1'  && function_exists('friends_get_friend_user_ids')) {

            $restricted_roles = BP_Better_Messages()->settings['restrictViewMiniFriends'];
            $is_restricted = false;

            if( count( $restricted_roles ) > 0 ) {
                foreach( $restricted_roles as $restricted_role ){
                    if( in_array( $restricted_role, $roles ) ){
                        $is_restricted = true;
                    }
                }
            }

            if( ! $is_restricted ) {
                $friends = friends_get_friend_user_ids(get_current_user_id());
                if (count($friends) > 0) {
                    $tabs['friends'] = 'friends';
                }
            }
        }

        if( BP_Better_Messages()->settings['enableMiniGroups'] === '1' && function_exists('groups_get_user_groups') ) {

            $restricted_roles = BP_Better_Messages()->settings['restrictViewMiniGroups'];
            $is_restricted = false;

            if( count( $restricted_roles ) > 0 ) {
                foreach( $restricted_roles as $restricted_role ){
                    if( in_array( $restricted_role, $roles ) ){
                        $is_restricted = true;
                    }
                }
            }

            if( ! $is_restricted ) {
                $groups = groups_get_user_groups(get_current_user_id());

                if ($groups['total'] > 0) {
                    $tabs['groups'] = 'groups';
                }
            }
        }

        $tabs = apply_filters( 'bp_better_messages_bottom_widgets', $tabs );

        if( count($tabs) == 0 ) return false;

        $has_chat_footer = false;
        $has_new_button = false;

        if( BP_Better_Messages()->settings['disableNewThread'] === '0' || current_user_can('manage_options') ){
            $has_chat_footer = true;
            $has_new_button  = true;
        }

        $me = BP_Better_Messages()->functions->render_me();
        if( !! $me ){
            $has_chat_footer = true;
        }

        $chat_footer = '';

        if( $has_chat_footer ) { ob_start(); ?>
            <div class="chat-footer">
                <?php if($has_new_button) { ?>
                    <a href="<?php echo add_query_arg( 'new-message', '', BP_Better_Messages()->functions->get_link() ); ?>&scrollToContainer" class="new-message ajax" title="<?php _e( 'New Thread', 'bp-better-messages' ); ?>"><i class="far fa-edit" aria-hidden="true"></i></a>
                <?php } ?>
                <?php echo $me; ?>
            </div>
            <?php $chat_footer = ob_get_clean();
        }
        ?>
        <div class="bp-messages-wrap bp-better-messages-list <?php BP_Better_Messages()->functions->messages_classes(); ?>">
            <div class="tabs">
                <?php if(in_array('messages', $tabs)){ ?>
                    <div data-tab="messages"><span class="unread-count" style="display:none"></span><i class="fas fa-comments"></i> <?php _e('Messages', 'bp-better-messages'); ?></div>
                <?php } ?>
                <?php if(in_array('friends', $tabs)){ ?>
                    <div data-tab="friends"><i class="fas fa-user-friends"></i> <?php _e('Friends', 'bp-better-messages'); ?></div>
                <?php } ?>
                <?php if(in_array('groups', $tabs)){ ?>
                    <div data-tab="bpbm-groups"><i class="fas fa-users"></i> <?php _e('Groups', 'bp-better-messages'); ?></div>
                <?php } ?>
                <?php if( BP_Better_Messages()->settings['enableMiniCloseButton'] === '1' ){ ?>
                <div data-tab="bpbm-close" title="<?php _e('Close', 'bp-better-messages'); ?>"><i class="fas fa-times"></i></div>
                <?php } ?>
            </div>
            <div class="tabs-content">
            <?php if(in_array('messages', $tabs)){
                $threads = BP_Better_Messages()->functions->get_threads( $user_id );
                ?>
                <div class="messages<?php if( ! $has_chat_footer ) echo ' no-chat-footer'; ?>">
                    <?php if ( !empty( $threads ) ) { ?>
                        <div class="scroller scrollbar-inner threads-list-wrapper">
                            <div class="threads-list">
                                <?php foreach ( $threads as $thread ) {
                                    echo BP_Better_Messages()->functions->render_thread( $thread, get_current_user_id() );
                                } ?>
                                <div class="loading-messages">
                                    <div class="bounce1"></div>
                                    <div class="bounce2"></div>
                                    <div class="bounce3"></div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="threads-list">
                            <p class="empty">
                                <?php _e( 'Nothing found', 'bp-better-messages' ); ?>
                            </p>
                        </div>
                    <?php } ?>
                    <?php echo $chat_footer; ?>
                </div>
            <?php } ?>
            <?php if(in_array('friends', $tabs)){ ?>
                <div class="friends">
                    <?php $friends = BP_Better_Messages()->functions->get_friends_sorted(get_current_user_id()); ?>
                    <div class="scroller scrollbar-inner">
                        <div class="bpbm-search-in-list">
                            <input title="<?php _e('Search...', 'bp-better-messages'); ?>" type="text" name="bpbm-search" value="" placeholder="<?php _e('Search...', 'bp-better-messages'); ?>">
                        </div>
                        <div class="bp-messages-user-list">
                            <?php foreach($friends as $user_id => $last_activity){
                                $user = get_userdata($user_id);
                                if( ! $user ) continue;
                                echo BP_Better_Messages()->functions->render_user( $user );
                            } ?>
                        </div>
                    </div>
                    <?php echo $chat_footer; ?>
                </div>
            <?php } ?>
            <?php if(in_array('groups', $tabs)){ ?>
                <div class="bpbm-groups">
                    <div class="scroller scrollbar-inner">
                        <div class="bpbm-search-in-list">
                            <input title="<?php _e('Search...', 'bp-better-messages'); ?>" type="text" name="bpbm-search" value="" placeholder="<?php _e('Search...', 'bp-better-messages'); ?>">
                        </div>
                        <div class="bp-messages-group-list">
                            <?php foreach($groups['groups'] as $group_id){
                                $group = new BP_Groups_Group( (int) $group_id );
                                if( $group->id === 0 ) continue;

                                echo BP_Better_Messages()->functions->render_group( $group );
                            } ?>
                        </div>
                    </div>

                    <?php echo $chat_footer; ?>
                </div>
            <?php } ?>
            </div>
        </div>
        <?php
    }
}

function BP_Better_Messages_Mini_List()
{
    return BP_Better_Messages_Mini_List::instance();
}