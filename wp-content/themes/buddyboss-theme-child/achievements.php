<?php
    /*
    template name:achievements
    
    */
    
    get_header(); 
      global $bp, $wpdb;
     $user_id = get_current_user_id();
    $details = learndash_user_get_enrolled_courses( $user_id, array(), true );
      
     if ( $details) {
     
      $course_data = array();

        foreach ($details as $key => $courses_data) { 
          $status = learndash_course_status($courses_data);
            if($status == 'Completed')
            {
               $count++;
            }
            $course = get_post($courses_data);
            $course_data[$courses_data]['course_title'] = $course->post_title;

            $course_data[$courses_data]['completed_date'] =learndash_user_get_course_completed_date($user_id,$courses_data);
            $course_count =learndash_get_course_steps_count($courses_data);
            $course_complete_count = learndash_course_get_completed_steps($user_id,$courses_data);
            $course_data[$courses_data]['status'] = $course_complete_count.'/'.$course_count;

            if($course_count == $course_complete_count)
            {
              $course_data[$courses_data]['com_design_font'] = 'com_design_font';
            }

        }
      }
      // echo"<pre>";print_r($course_data); 
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<div class="container rounded bg-white mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-heading">Achievements</h4>
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <h6 class="sub-heading">No of Project completed : <?php echo $count; ?></h6>
    </div>
<table>
  <tr class="tab_heading_achivements">
    <th>Project</th>
    <th>Status</th>
    <th>Completetion Date</th>
  </tr>

  <?php
    foreach ($course_data as $key => $value) {
      if(!empty($value['completed_date']))
      {
        $date_format = get_option('date_format');
        $completed_date = date_i18n( $date_format, $value['completed_date'] );
      }
      else{
        $completed_date = '-';
      }

       echo ' <tr class="tab_con_achivements">';
       echo '<td>'.$value['course_title'].'</td>';
       echo '<td class="'.$value['com_design_font'].'" style="font-weight:600;">'.$value['status'].'</td>';
       echo '<td>'.$completed_date.'</td>';
       echo '</tr>';
    }
  ?>

</table>
    
</div>

</div>
</div>
</div>

<?php get_footer(); ?>