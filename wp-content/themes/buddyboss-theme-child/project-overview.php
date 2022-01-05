<?php
    /*

    template name:project-overview

     */
     get_header(); 
if ( is_user_logged_in() ) {
      global $bp, $wpdb;
     $user_id = get_current_user_id();
    // $membership_level = pmpro_getMembershipLevelForUser($user_id);
    // if(!empty($membership_level)){
?>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <div class="container rounded bg-white mt-5 mb-5 course_progress_wrap">

        <div class="d-flex justify-content-between align-items-center mb-3">

           <h4 class="text-heading">Projects</h4>

        </div><br/>

<?php


    $courses = learndash_user_get_enrolled_courses( $user_id, array(), true );
    $args = array(  
        'post_type' => array('sfwd-courses'),
        'post_status' => 'publish',
        'posts_per_page' => -1, 
        'orderby' => 'date', 
        'order' => 'asc', 
        'post__in'      =>  $courses,
    );
    $enrolled = new WP_Query( $args );

     // echo"<pre>";print_r($enrolled);  
    if ( $enrolled->posts) {
            foreach ($enrolled->posts as $key => $courses_data) {
            
            echo '<div class="coures_wrap">';
            echo '<button class="accordion">'.$courses_data->post_title.'<span class="acc_icon">';

            // $lesson_progress = ;
            // $cp = learndash_get_course_progress($courses_data->ID);
                $context = 'course';
                $progress_args = apply_filters( 'learndash_progress_args', array(
                    'array'     =>  true,
                    'course_id' => $courses_data->ID,
                    'user_id'   =>  $user_id
                ), $courses_data->ID, $user_id, $context );

                $progress = apply_filters( 'learndash-' . $context . '-progress-stats', learndash_course_progress( $progress_args ) );

                if($progress['percentage'] == '100')
                {
                    echo '<i class="fas fa-check-circle"></i>';
                }
                else
                {
                    echo '<div class="blankcir"></div>';
                }
                echo '<i class="fas fa-caret-down"></i></span></button>';
            // echo"<pre>";print_r($progress);  

            echo '<div class="panel">';

            $lesson_data = learndash_get_course_lessons_list($courses_data->ID);
            $lesson_post = array();
            foreach ($lesson_data as $key => $value){
                $lesson_post1 = $value['post'];
                $lesson_post2['status'] = $value['status'];
                $lesson_post3['permalink'] = $value['permalink'];
                $lesson_post4['sno'] = $value['sno'];
                $lesson_post[] = (object)array_merge((array)$lesson_post1, (array)$lesson_post2, (array)$lesson_post3, (array)$lesson_post4);
            }

            // echo"<pre>";print_r($lesson_data); 
            if(!empty($lesson_post)){
            foreach ($lesson_post as $key => $value) {
                  $lesson_progress = learndash_lesson_progress($courses_data->ID);
                    if ( is_array( $lesson_progress ) ) {
                        $status = ( $lesson_progress['completed'] > 0 && 'completed' !== $lesson['status'] ? 'progress' : $lesson['status'] );
                    } else {
                        $status = $value->status;
                    }
                    echo '<div class="progress_lesson">';
                echo '<p class="accordion_con"><a href="'.$value->permalink.'">Part '.$value->sno.' : '.$value->post_title.'</a></p>';
                 learndash_status_icon( $status, get_post_type(), null, true ); 
                echo '</div>';
            }
           }
           else
           {
            echo '<p>No lesson Found Here</p>';
           }

            echo '</div>';
            echo '</div>';
        }
    } else {
        // no posts found
        echo 'No Project Found';
    }
    /* Restore original Post Data */
    // echo"<pre>";print_r($loop);           


?>


<script>

var acc = document.getElementsByClassName("accordion");

var i;



for (i = 0; i < acc.length; i++) {

  acc[i].addEventListener("click", function() {

    this.classList.toggle("active");

    var panel = this.nextElementSibling;

    if (panel.style.display === "block") {

      panel.style.display = "none";

    } else {

      panel.style.display = "block";

    }

  });

}

</script>

</div>

<?php
// }
// else{
//     echo '<div class="pmpro_content_message">This content is for 1 Month, 3 Months, and 12 Months members only.<br><a href="'.site_url().'/profile'.'">Join Now</a></div>';
// } 
}
else
{
     $url = site_url();
     echo "<script type='text/javascript'>window.location.href='". $url ."'</script>";  
}
get_footer(); ?>