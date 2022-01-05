<?php
/**
 * Displays a lesson.
 *
 * Available Variables:
 *
 * $course_id       : (int) ID of the course
 * $course      : (object) Post object of the course
 * $course_settings : (array) Settings specific to current course
 * $course_status   : Course Status
 * $has_access  : User has access to course or is enrolled.
 *
 * $courses_options : Options/Settings as configured on Course Options page
 * $lessons_options : Options/Settings as configured on Lessons Options page
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 *
 * $user_id         : (object) Current User ID
 * $logged_in       : (true/false) User is logged in
 * $current_user    : (object) Currently logged in user object
 *
 * $quizzes         : (array) Quizzes Array
 * $post            : (object) The lesson post object
 * $topics      : (array) Array of Topics in the current lesson
 * $all_quizzes_completed : (true/false) User has completed all quizzes on the lesson Or, there are no quizzes.
 * $lesson_progression_enabled  : (true/false)
 * $show_content    : (true/false) true if lesson progression is disabled or if previous lesson is completed.
 * $previous_lesson_completed   : (true/false) true if previous lesson is completed
 * $lesson_settings : Settings specific to the current lesson.
 *
 * @since 3.0
 *
 * @package LearnDash\Lesson
 */
get_header();
global $post, $wpdb;
$lesson_data = $post;
if(isset( $_POST['action'] )) {
    if(!empty($_POST['projects_link'])){
         global $wpdb;     
       $table_name = $wpdb->prefix . 'project_link';     
       $wpdb->insert($table_name, array('lesson_id' => $lesson_data->ID, 'user_id' => $user_id, 'course_id' => learndash_get_course_id( $lesson_data->ID ),'project_link' => $_POST['projects_link'],'submission_date' => date("Y-m-d H:i:s"))); 
       // update_post_meta($lesson_data->ID,'project_link', $_POST['projects_link']);
       wp_redirect( get_permalink( $lesson_data->ID ) );
       ?>
       <script type="text/javascript">
           jQuery(document).ready(function($) {
              $('.ld-content-actions').append('<p>Your Link have been submitted Mark Complete to move to another lesson.</p>');
            });
       </script>
       <?php
   }
}

$parent_course_data  = learndash_get_setting( $post, 'course' );
if ( 0 === $parent_course_data ) {
    $parent_course_data = $course_id;
    if ( 0 === $parent_course_data ) {
        $course_id = buddyboss_theme()->learndash_helper()->ld_30_get_course_id( $post->ID );
    }
    $parent_course_data  = learndash_get_setting( $course_id, 'course' );
}
$parent_course       = get_post( $parent_course_data );
$parent_course_link  = $parent_course->guid;
$parent_course_title = $parent_course->post_title;

$in_focus_mode = LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Theme_LD30', 'focus_mode_enabled' );
add_filter( 'comments_array', 'learndash_remove_comments', 1, 2 );

// echo "<pre>";print_r($lesson_data);

if ( empty( $course_id ) ) {
    $course_id = learndash_get_course_id( $lesson_data->ID );
    if ( empty( $course_id ) ) {
        $course_id = (int) buddyboss_theme()->learndash_helper()->ld_30_get_course_id( $lesson_data->ID );
    }
}
$lession_list            = learndash_get_lesson_list( $course_id, array('num' => -1 ) );
//$content                 = $lesson_data->post_content;
// $lesson_topics_completed = learndash_lesson_topics_completed( $post->ID );
$content_urls            = buddyboss_theme()->learndash_helper()->buddyboss_theme_ld_custom_pagination( $course_id, $lession_list );
 $content_urls;
$pagination_urls         = buddyboss_theme()->learndash_helper()->buddyboss_theme_custom_next_prev_url( $content_urls );
if ( empty( $course ) ) {
    if ( empty( $course_id ) ) {
        $course = learndash_get_course_id( $lesson_data->ID );
    } else {
        $course = get_post( $course_id );
    }
}
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="text-heading"><?php echo $parent_course_title; ?></h4>
            </div>
        </div>
        <div class="col-md-4">
             <div class="btn_pn">
                <?php
                if ( isset( $pagination_urls['prev'] ) && $pagination_urls['prev'] != '' ) {
                    echo $pagination_urls['prev'];
                } else {
                    echo '<span class="prev-link empty-post"></span>';
                }
                ?>
                <?php if ( (isset( $pagination_urls['next'] ) && apply_filters( 'learndash_show_next_link', learndash_is_lesson_complete( $user_id, $post->ID ),  $user_id, $post->ID ) && $pagination_urls['next'] != '') || (isset( $pagination_urls['next'] ) && $course_settings['course_disable_lesson_progression'] === 'on' && $pagination_urls['next'] != '') ) {
                    echo $pagination_urls['next'];
                } else {
                    echo '<span class="next-link empty-post"></span>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="step-box">
        <ul class="step-list">

            <?php
            $i = 1;
                foreach ($lession_list as $key => $value) {
                    // echo"<pre>";print_r($value);
                    $lesson_progress = buddyboss_theme()->learndash_helper()->learndash_get_lesson_progress($value->ID,$course_id);
                    ?>
                    <li class="active">
                        <div class="step_height">
                            
                            <?php if ($lesson_progress['percentage'] == '100') { ?>
                               <a href="<?php echo get_permalink($value->ID);?>"><span><i class="fas fa-check-circle"></i></span></a>
                            <?php } else { ?>
                                <span></span>
                                <?php } ?>
                        </div>
                        <p class="step_p">Part <?php echo $i; ?></p>
                   </li> 

                    <?php
                    $i++;
                }

            ?>
            
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12 c_input">
            <div class="justify-content-between align-items-center">
                <h6 class="sub-heading">Part <?php $lesson_data->menu_order; ?> : <?php the_title(); ?></h6>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-12">
                    <?php 
                    if ( !empty( $video_content ) ) {
                    if ( strpos( $content, '[ld_video]' ) !== false ) {
                        $content = str_replace( '[ld_video]', $video_content, $content );
                    } else {
                        $content = $video_content . $content;
                    }
                } else {
                    if ( strpos( $content, '[ld_video]' ) !== false ) {
                        $content = str_replace( '[ld_video]', '', $content );
                    }
                }
                echo $content;
                    ?>
                </div>
            </div>
        </div>
        <?php
       global $wpdb;
        $query = "SELECT * FROM `wp_project_link` WHERE lesson_id='".$lesson_data->ID."' AND user_id='".$user_id."'";
        $chp = $wpdb->get_results($query);
            if(!empty($chp))
            {
                $can_complete = false;
                if( $all_quizzes_completed && $logged_in && !empty($course_id) ):
                    $can_complete = apply_filters( 'learndash-lesson-can-complete', true, get_the_ID(), $course_id, $user_id );
                endif;
                learndash_get_template_part(
                        'modules/course-steps.php',
                        array(
                            'course_id'          => $course_id,
                            'course_step_post'   => $post,
                            'user_id'            => $user_id,
                            'course_settings'    => isset( $course_settings ) ? $course_settings : array(),
                            'can_complete'       => $can_complete,
                            'context'            => 'lesson'
                        ),
                        true
                    );
                /**
                 * Action to add custom content after the lesson
                 *
                 * @since 3.0
                 */
                do_action( 'learndash-lesson-after', get_the_ID(), $course_id, $user_id ); 
            }
            else
            {

                ?>
                    
                <form id="prowrap" name="new_post" method="post" action="">
                   <div class="col-md-3"><label class="labels_title">Submit Project Link:</label></div>
                   <div class="col-md-6"><input type="text" name="projects_link" required="" class="form-control" placeholder="" value="" required></div>
                   <div class="col-md-3"><input type="submit" class="btn_submit" name="action" value="Submit"></div>
                </form>

                <?php

            }
        ?>
     

    </div>
</div>
<?php 
get_footer();
?>