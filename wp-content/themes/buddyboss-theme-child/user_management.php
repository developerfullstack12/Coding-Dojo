<?php

    /*
    template name:user-management
    
    */
    
    get_header(); 
 
?>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<div class="container">
  <div class="row py-5">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-3">

           <h4 class="text-heading">User Management</h4>

        </div><br/>
      <table id="example" class="table table-hover responsive nowrap" style="width:100%">
        <thead>
           <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Access</th>
        <th>Expire Date</th>
        <th>Submission</th>
        <th>Payment Log</th>
          </tr>
        </thead>
        <tbody>
                    <?php 
      $users = get_users( array( 'fields' => array( 'ID','user_login' ) ) );
      // echo"<pre>";print_r($level);  
      foreach($users as $user){ 
        $user_meta = get_userdata($user->ID);
         $status = get_user_meta( $user->ID, '_is_disabled', true);
         if($status == 1)
         {
          $checked = 'checked';
          $radval = '1';
         }
         else
         {
          $checked = '';
          $radval = '0';
         }

          $user_roles = $user_meta->roles;
        if ( !in_array( 'administrator', $user_roles, true ) ) {
          ?>

               <tr>
               <td><?php echo $user->ID;?></td>
               <td>
                <a href="#">
                  <div class="d-flex align-items-center">
                      <p class="font-weight-bold mb-0"><?php echo $user->user_login;?></p>
                  </div>
                </a>
              </td>
               <td>
              <label class="toggleSwitch nolabel">
              <input type="checkbox" name="disable_user_login" class="disable_user" data-id="<?php echo $user->ID;?>" id="disable_user_login" value="<?php echo $radval;?>" <?php echo $checked;?> />
              <a></a>
              <span>
                <span class="left-span">Y</span>
                <span class="right-span">N</span>
              </span>                     
            </label>
            </td>
              <td><?php
               $level = pmpro_getMembershipLevelForUser($user->ID);
               // echo"<pre>";print_r($level);  
               if(!empty($level) && !empty($level->enddate))
            echo $content = date(get_option('date_format'), $level->enddate);
          else
            echo $content = "---";
              ?></td>
               <td>
                <button type="button" class="btn_view" data-toggle="modal" data-target="#myModal_<?php echo $user->ID;?>">View</button>
              
               <div class="modal" id="myModal_<?php echo $user->ID;?>" role="dialog">
                  <div class="modal-dialog">
                  
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title">User:<?php echo $user->user_login;?></h6>
                        &nbsp;&nbsp;&nbsp;&nbsp;<h6 class="modal-title">Type:Submissions</h6>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                            
                            <table class="styled-table">
                              <thead>
                                <?php
                                  global $wpdb;
                                  $query = "SELECT * FROM `wp_project_link` WHERE user_id='".$user->ID."'";
                                  $chp = $wpdb->get_results($query);
                                  // print_r($chp);
                                      if(!empty($chp))
                                      {
                                        ?>
                                          <tr>
                                            <th>Timestamp:</th>
                                            <th>Project - Sub Task</th>
                                            <th>Link Submitted:</th>
                                          </tr>
                                        <?php
                                        foreach ($chp as $key => $value) {
                                        $lesson = get_the_title($value->lesson_id);
                                        $course = get_the_title($value->course_id);
                                        $project_task =  $course.'|'.$lesson;
                                        $submission_date = $value->submission_date;
                                        $project_link = $value->project_link;

                                        echo '<tr>';
                                          echo '<td>'.$submission_date.'</td>';
                                          echo '<td>'.$project_task.'</td>';
                                          echo '<td>'.$project_link.'</td>';
                                        echo '</tr>';
                                          // code...
                                        }
                                      }
                                      else
                                      {
                                        echo '<p>No Data Found</p>';
                                      }
                                ?>
                          </thead>
                        </table>
                      </div>
                    </div>
                    
                  </div>
                </div>
                </td>
                 <!-- Modal -->
               
               <td><button type="button" class="btn_view" data-toggle="modal" data-target="#paymod_<?php echo $user->ID;?>">View</button>
     
               <div class="modal" id="paymod_<?php echo $user->ID;?>" role="dialog">
                  <div class="modal-dialog">
                  
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title">User : <?php echo $user->user_login;?></h6>
                        &nbsp;&nbsp;&nbsp;&nbsp;<h6 class="modal-title">Type : Submissions</h6>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                            
                            <table class="styled-table">
                              <thead>
                                <?php
                               global $wpdb;

                              //Show all invoices for user
                              $invoices = $wpdb->get_results("SELECT mo.*, UNIX_TIMESTAMP(mo.timestamp) as timestamp, du.code_id as code_id FROM $wpdb->pmpro_membership_orders mo LEFT JOIN $wpdb->pmpro_discount_codes_uses du ON mo.id = du.order_id WHERE mo.user_id = '$user->ID' ORDER BY mo.timestamp DESC");  
                                     if(!empty($invoices ))
                                      {
                                        ?>
                                          <tr>
                                            <th><?php esc_html_e( 'Date', 'pmpro-member-history' ); ?></th>
                                            <th><?php esc_html_e( 'Invoice ID', 'pmpro-member-history' ); ?></th>
                                            <th><?php esc_html_e( 'Level', 'pmpro-member-history' ); ?></th>
                                            <th><?php esc_html_e( 'Total Billed', 'pmpro-member-history' ); ?></th>
                                            <th><?php esc_html_e( 'Status', 'pmpro-member-history' ); ?></th>
                                          </tr>
                                        <?php
                                      foreach ( $invoices as $invoice ) { 
                                           $level = pmpro_getLevel( $invoice->membership_id );

                                        echo '<tr>';
                                          echo '<td>'.date_i18n( get_option( 'date_format'), $invoice->timestamp ).'</td>';
                                          echo '<td>'.$invoice->code.'</td>';
                                          echo '<td>'.$level->name.'</td>';
                                          echo '<td>'.pmpro_formatPrice( $invoice->total ).'</td>';
                                          echo '<td>'.$invoice->status.'</td>';
                                        echo '</tr>';
                                          // code...
                                        }
                                      }
                                      else
                                      {
                                        echo '<p>No Data Found</p>';
                                      }
                                ?>
                          </thead>
                        </table>
                      </div>
                    </div>
                    
                  </div>
                </div>
  
               </td>
                </tr>
          <?php
          }
         }
              ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<style type="text/css">
table#example thead tr {
    background: #6e6e6e;
    color: #fff;
    font-size: 20px;
}
table.dataTable thead .sorting,table.dataTable thead .sorting_desc,table.dataTable thead .sorting_asc {
    background: transparent !important;
}

table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after {
    font-size: 14px !important;
    color: #FFF !important;
}
table.styled-table {
    width: 100%;
    text-align: center;
}

.modal-dialog {
    max-width: 1000px;
    width: auto;
}
table.styled-table tr, table.styled-table td, table.styled-table th {
    background: transparent !important;
    color: #000!important;
    border: none !important;
}

.modal-body {
    padding: 0px;
    margin: 0px 0;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
  $("#example").DataTable({
    aaSorting: [],
    responsive: true,

    columnDefs: [
      {
        responsivePriority: 1,
        targets: 0
      },
      {
        responsivePriority: 2,
        targets: -1
      }
    ]
  });

  $(".dataTables_filter input")
    .attr("placeholder", "Search here...")
    .css({
      width: "300px",
      display: "inline-block"
    });

  $('[data-toggle="tooltip"]').tooltip();
});

jQuery(document).ready(function(){
 $("body").on('change', '.disable_user', function(){ 
  ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
    var status = $(this).val(); 
            var user_id = $(this).data('id'); 

             if(status == '0'){
                  jQuery(this).val('1');
                }
                else{
                   jQuery(this).val('0');
                }
            var ustatus = jQuery(this).val();
        jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        dataType: 'json',
        data:{
            'action': 'my_action',
            'status': ustatus,
            'user_id': user_id
        },
        success: function( response ){
            console.log("This is response...");
            console.log(response);
        },
        error: function( error ){
            console.log('AJAX error callback....');
            console.log(error);
        }
       });
    });
});
</script>

<?php get_footer(); ?>