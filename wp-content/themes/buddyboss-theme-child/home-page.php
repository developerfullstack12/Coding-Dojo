<?php
   /*
   template name:home-page
   */
   get_header(); 
   ?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>



<?php

   if ( is_user_logged_in() ) {

         global $bp, $wpdb;

        $user_id = get_current_user_id();

       // $membership_level = pmpro_getMemb

        $query = "SELECT course_id FROM `wp_learndash_user_activity` WHERE user_id='".$user_id."' ORDER BY activity_updated DESC limit 1";

           $chp = $wpdb->get_results($query);

               if(!empty($chp))

               {

   ?>

<div class="container rounded bg-white mt-5 mb-5">

   <div class="d-flex justify-content-between align-items-center">

      <h4 class="text-heading">Project Progress</h4>

   </div>

   <div class="d-flex justify-content-between align-items-center">

      <h6 class="sub-heading">Project Title: <?php echo get_the_title($chp[0]->course_id)?></h6>

   </div>

   <br/>

   <div class="step-box">

      <ul class="step-list">

         <?php

            $lessons = learndash_get_lesson_list($chp[0]->course_id);

            

            $i = 1;

            foreach ($lessons as $key => $value) {

                  $lesson_progress = buddyboss_theme()->learndash_helper()->learndash_get_lesson_progress($value->ID,$chp[0]->course_id);

                  // echo"<pre>";print_r($lesson_progress); 

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

   <?php 

      }

      ?>

      <?php } 

   else

   {

        $url = site_url();

        echo "<script type='text/javascript'>window.location.href='". $url ."'</script>";  

   }

   ?>

<?php

$args = array(  

        'post_type' => 'announcements',

        'post_status' => 'publish',

        'posts_per_page' => 8,

    );

    $loop = new WP_Query( $args );  ?>

  <div class="row">

           <div class="col-md-12">

                <div class="d-flex justify-content-between align-items-center mb-3">

                     <h4 class="text-heading">Announcement</h4>

                </div>

            </div>

            <?php  while ( $loop->have_posts() ) : $loop->the_post(); 


       ?>

             <div class="col-md-12">

                <div class="text_bg">

                  <span class="entry-header"><?php the_title(); ?></span><span class="entry-title"><?php echo get_the_date(); ?></span>

                  <p class="entry-des"><?php the_content(); ?> </p>

              </div>

            </div>

            

    <?php endwhile; ?>

    </div>
</div>
<?php

    wp_reset_postdata(); ?>

<?php get_footer(); ?>