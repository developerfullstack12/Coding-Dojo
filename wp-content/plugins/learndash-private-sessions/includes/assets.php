<?php
add_action( 'wp_enqueue_scripts', 'ldma_custom_assets' );
function ldma_custom_assets() {

    wp_register_style( 'ldms-shortcodes', LDMA_URL . 'assets/css/front.css', LDMA_VER );
    wp_register_style( 'ldms-global', LDMA_URL . 'assets/css/global.css', LDMA_VER );
    wp_register_script( 'ldms-front', LDMA_URL . 'assets/js/front.js', array('jquery'), LDMA_VER, false );
	wp_localize_script( 'ldms-front', 'ldmaajax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php'),
		'ldms_delete_txt' =>  __( 'Are you sure you want to delete this conversation?', 'ldmessenger' )
	) );
    // Enqueue if needed
	wp_enqueue_style( 'ldms-global');
    wp_enqueue_script( 'ldms-front' );

    $settings 	= get_option('ldms_private_sessions_settings');
 	$style		= '';

 	ob_start();

 	if( isset($settings['ldms_indicator_position']) && !empty($settings['ldms_indicator_position']) && $settings['ldms_indicator_location'] != 'right' ) { ?>
 		.ldms-message-tab {
 			right: <?php echo esc_attr($settings['ldms_indicator_position']); ?>px !important;
 		}
 	<?php }

    if( isset( $settings['ldms_accent_color'] ) && !empty( $settings['ldms_accent_color'] ) ) { ?>
        #ldms-response-box,
        #ldms-message-list table th,
        .ldms-btn,
        #ldms-comment-form h3,
        #ldms-new-session h2,
        #ldms-comment-form input[type='submit'],
        .ldms-next-posts a, input[type='submit'].ldms-btn,
        .ldms-btn,
        .ldms-message-tab,
        .ldms-next-posts a,
        #ldms-response-content {
            background: <?php echo esc_attr($settings['ldms_accent_color']); ?> !important;
        }
    <?php
    }

    if( isset( $settings['ldms_accent_txt_color'] ) && !empty( $settings['ldms_accent_txt_color'] ) ) { ?>
        #ldms-response-box,
        #ldms-message-list table th,
        .ldms-btn,
        #ldms-comment-form h3,
        #ldms-new-session h2,
        .ldms-message-tab a,
        .ldms-next-posts a,
        #ldms-response-content,
        #ldms-response-content h3,
        #ldms-comment-form input[type='submit'],
        .ldms-next-posts a,
        input[type='submit'].ldms-btn,
        .ldms-btn,
        #ldms-response-content p,
        #ldms-response-content a {
            color: <?php echo esc_attr($settings['ldms_accent_txt_color']); ?> !important;
        }
    <?php
    }

 	$style = ob_get_clean();

 	if( !empty($style) ) wp_add_inline_style( 'ldms-global', $style );

}

function ldms_change_upload_dir( $param ) {

    if( !is_user_logged_in() ) return $param;

    $folder             = '/ldms-uploads';

    $param['path']      = $param['basedir'] . $folder;
    $param['url']       = $param['baseurl'] . $folder;
    $param['subdir']    = '/';

    return $param;

}
