<?php

/**

 * @package BuddyBoss Child

 * The parent theme functions are located at /buddyboss-theme/inc/theme/functions.php

 * Add your own functions at the bottom of this file.

 */

/****************************** THEME SETUP ******************************/

/**

 * Sets up theme for translation

 *

 * @since BuddyBoss Child 1.0.0

 */

function buddyboss_theme_child_languages()

{

  /**

   * Makes child theme available for translation.

   * Translations can be added into the /languages/ directory.

   */



  // Translate text from the PARENT theme.

  load_theme_textdomain( 'buddyboss-theme', get_stylesheet_directory() . '/languages' );



  // Translate text from the CHILD theme only.

  // Change 'buddyboss-theme' instances in all child theme files to 'buddyboss-theme-child'.

  // load_theme_textdomain( 'buddyboss-theme-child', get_stylesheet_directory() . '/languages' );



}

add_action( 'after_setup_theme', 'buddyboss_theme_child_languages' );



/**

 * Enqueues scripts and styles for child theme front-end.

 *

 * @since Boss Child Theme  1.0.0

 */

function buddyboss_theme_child_scripts_styles()

{

  /**

   * Scripts and Styles loaded by the parent theme can be unloaded if needed

   * using wp_deregister_script or wp_deregister_style.

   *

   * See the WordPress Codex for more information about those functions:

   * http://codex.wordpress.org/Function_Reference/wp_deregister_script

   * http://codex.wordpress.org/Function_Reference/wp_deregister_style

   **/



  // Styles

  wp_enqueue_style( 'buddyboss-child-css', get_stylesheet_directory_uri().'/assets/css/custom.css', '', time() );
  // Javascript
  wp_enqueue_script( 'buddyboss-child-js', get_stylesheet_directory_uri().'/assets/js/custom.js', '', '1.0.0' );

}

add_action( 'wp_enqueue_scripts', 'buddyboss_theme_child_scripts_styles', 9999 );


/****************************** CUSTOM FUNCTIONS ******************************/



// Add your own custom functions here



function wpa_filter_nav_menu_objects( $items ){

    foreach( $items as $key => $item ){

        if( 'User Management' == $item->title && !current_user_can( 'administrator' ) ){

            unset( $items[$key] );

        }
        if( 'About Us' == $item->title && is_user_logged_in() ){

            unset( $items[$key] );

        }

    }

    return $items;

}

add_filter( 'wp_nav_menu_objects', 'wpa_filter_nav_menu_objects' );


function custom_course_completed_redirect($link, $course_id) {

  $link = get_post_field( 'post_name', $course_id );

  //You can change the link here

  return site_url().'/project-overview/';

}



add_filter("learndash_course_completion_url", custom_course_completed_redirect, 5, 2);





/*============== Announcement Post Type===========*/



function create_posttype() {

 

    register_post_type( 'announcements',

    // CPT Options

        array(

            'labels' => array(

                'name' => __( 'Announcements' ),

                'singular_name' => __( 'Announcement' )

            ), 

            'public' => true,

            'has_archive' => true,

            'rewrite' => array('slug' => 'announcements'),

            'show_in_rest' => true,

 

        )

    );

}

// Hooking up our function to theme setup

add_action( 'init', 'create_posttype' );

// user access custom code

add_action('wp_ajax_nopriv_my_action', 'my_action');
add_action('wp_ajax_my_action','my_action');
function my_action(){
  global $wpdb;
    $user_id = esc_attr($_POST['user_id']);
    $status = esc_attr($_POST['status']);
    update_user_meta( $user_id, '_is_disabled', $status );
      die();
}

add_action('wp_ajax_nopriv_my_action_email', 'my_action_email');
add_action('wp_ajax_my_action_email','my_action_email');
function my_action_email(){
    global $wpdb;
    $user_id = get_current_user_id();
    $status = esc_attr($_POST['status']);
    update_user_meta( $user_id, 'Email_notification_weekly', $status );
}

