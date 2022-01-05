<?php
/*
Plugin Name: Weekly email
Author URI: Aishwarya patidar
*/
add_filter ("wp_mail_content_type", "my_awesome_mail_content_type");
function my_awesome_mail_content_type() {
    return "text/html";
}

add_filter( 'cron_schedules', 'wpshout_add_cron_interval' );
function wpshout_add_cron_interval( $schedules ) {
    // $schedules['everyminutes'] = array(
    //         'interval'  => 60, // time in seconds
    //         'display'   => 'Every Minute'
    // );
    $schedules['weekly'] = array(
        'interval' => WEEK_IN_SECONDS,
        'display' => __('Once Weekly')
    );
    return $schedules;
}
/**
 * this method will register the cron event
 */
add_action( 'init', 'register_daily_notify_user_send_email_event');
function register_daily_notify_user_send_email_event() {
    // make sure this event is not scheduled
    if( !wp_next_scheduled( 'notify_user_send_email' ) ) {
        // schedule an event
        wp_schedule_event( time(), 'weekly', 'notify_user_send_email' );
    }
}

/**
 * notify_user_send_email method will be call when the cron is executed
 */
add_action( 'notify_user_send_email', 'notify_all_user_send_email' );
 
/**
 * this method will call when cron executes
 */
function notify_all_user_send_email() {

    include("email_template.php");
}
?>