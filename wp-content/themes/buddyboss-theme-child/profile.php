<?php
/* template name:edit-Profile */
get_header(); 
if ( is_user_logged_in() ) {
    
  global $bp, $wpdb;

    $user_id = get_current_user_id();
    
    if(isset($_POST['usrup'])) {
     wp_update_user( array ('ID' => $user_id, 'display_name' => $_POST['display_name']));
     //wp_update_user( array ('ID' => $user_id, 'user_email' => $_POST['user_email']));
     wp_update_user( array ('ID' => $user_id, 'user_pass' => $_POST['user_pass']));
     //xprofile_set_field_data( '4', $user_id, $_POST['dob'], $is_required = false );
     
     xprofile_set_field_data( '4', $user_id, $_POST['dob'], $is_required = false );
     xprofile_set_field_data( '5', $user_id, $_POST['sec_name'], $is_required = false );
     xprofile_set_field_data( '6', $user_id, $_POST['sec_email'], $is_required = false );
     xprofile_set_field_data( '7', $user_id, $_POST['sec_phone'], $is_required = false );
     xprofile_set_field_data( '8', $user_id, $_POST['rel_stu'], $is_required = false );
   //  xprofile_set_field_data( '9', $user_id, $_POST['acc_email'], $is_required = false );
   //  xprofile_set_field_data( '10', $user_id, $_POST['acc_pass'], $is_required = false );

    if ( isset( $_FILES['userProfileImage'] ) ) {
      $file_name = $_FILES['userProfileImage']['name'];
      $image_full;
        $file = $_FILES['userProfileImage'];
        $file_path = $file['tmp_name'];
        $file_meta = getimagesize($file_path);
        // print_r($file_meta);

        if($file_meta !== false){
            // echo $upload_dir['basedir'];
            
            $uploads = wp_upload_dir();
            $upload_path = $uploads['basedir'];
            $folderpath = $upload_path."/avatars/".$user_id;
            if (!file_exists($folderpath)) {
                 mkdir($folderpath, 0777, true);
            } 
          // if($file_meta[0] == $file_meta[1]){
            $full_filename  = 'avatar-bpfull.'  . $FILE_EXTENSION;
            $thumb_filename = 'avatar-bpthumb.' . $FILE_EXTENSION;
            $target_dir = wp_get_upload_dir()['basedir'].'/avatars/'.$user_id.'/';

            $source = imagecreatefromstring(file_get_contents($file_path)); // La photo est la source

            $full = imagecreatetruecolor(150, 150);
            $thumb = imagecreatetruecolor(80, 80);

            imagecopyresampled($full, $source, 0, 0, 0, 0, imagesx($full), imagesy($full), imagesx($source), imagesy($source));
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, imagesx($thumb), imagesy($thumb), imagesx($source), imagesy($source));

            if(imagejpeg($thumb, $target_dir.$thumb_filename.'jpeg') && imagejpeg($full, $target_dir.$full_filename.'jpeg')){
              // echo  "The file has been uploaded.";
            } 
            else {
              echo "Sorry, there was an error uploading your file.";
            }
          // }else{
          //   echo 'not a squared image';
          // }
        }
        // else{
        //   echo 'not an image';
        // }
    }
      // die;
     $url = site_url('profile');
     wp_redirect( $url );
     // echo "<script type='text/javascript'>window.location.href='". $url ."'</script>";  
    }

 if ( have_posts() ) : while ( have_posts() ) : the_post();
the_content();
endwhile; else: ?>
<p></p>
<?php endif; 

  $dob =  xprofile_get_field_data( '4', $user_id);
  $sec_contact_name =  xprofile_get_field_data( '5', $user_id);
  $sec_contact_realtion =  xprofile_get_field_data( '8', $user_id);
  function xprofile_dataget($field_id, $user_id)
    { 
        global $wpdb;
        $query = "SELECT value FROM `wp_bp_xprofile_data` WHERE field_id='".$field_id."' AND user_id='".$user_id."'";
        $chp = $wpdb->get_results($query);
        return $chp[0]->value;
    }
  $sec_contact_email =  xprofile_dataget( '6', $user_id);
  $sec_contact_phone =  xprofile_dataget( '7', $user_id);
  //$account_email =  xprofile_dataget( '9', $user_id); 
 // $account_pass =  xprofile_dataget( '10', $user_id); 
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">




<div class="container rounded bg-white mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-right">Personal Details</h4>
    </div>
    <form method="post" id="edituser" action="<?php the_permalink(); ?>" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="">
                <div class="row mt-2">
                    <div class="col-md-4 cus_avtar">
                        <label class="labels" id="cus_img_heading">Display Picture</label>
                        
                        <?php echo get_avatar( get_current_user_id(), 100 ); ?>

                        
                        <!--<img  width="100" height="100" src="https://bootdey.com/img/Content/avatar/avatar7.png" class="avatar img-circle img-thumbnail" alt="avatar">-->
                    </div>
                    <div class="col-md-6 cus_img"><input type="file" id="upload" name="userProfileImage" hidden/><p id="c_validate_text9" class="c_req" style="color:red; text-align: right;"></p>
                        <label for="upload" id="c_upload">Upload Image</label>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Display Name</label><input type="text" id="display_name" name="display_name"  class="form-control" placeholder="" value="<?php echo $current_user->display_name; ?>" ></div>
                    <div class="col-md-12"><label class="labels">Your Birthday</label><input type="text" data-date-format='yy-mm-dd' id="datepicker" name="dob" class="form-control" placeholder="" value="<?php if(!empty($dob)){ echo $dob; }?>" ></div>
                     <div class="col-md-12"><label class="labels">Account Email</label><input type="text" id="c_validate3"  name="user_email" class="form-control" placeholder="" value="<?php echo $current_user->user_email; ?>" disabled></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="">
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Secondary Contact's Full Name</label><input type="text" id="c_validate4" name="sec_name" class="form-control" placeholder="" value="<?php if(!empty($sec_contact_name)){ echo $sec_contact_name; }?>" ></div>
                    <div class="col-md-12"><label class="labels">Secondary Contact Email</label><input type="email" id="sec_email" name="sec_email" class="form-control" value="<?php if(!empty($sec_contact_email)){ echo esc_html__($sec_contact_email); }?>" ></div>
                    <!--<div class="col-md-12"><label class="labels">Secondary Contact Number</label><input type="tel"  id="c_validate6" name="sec_phone" maxlength="8" class="form-control" placeholder="" value="<?php //if(!empty($sec_contact_phone)){ echo esc_html__($sec_contact_phone); }?>" required><p id="c_validate_text6" style="color:red"></p></div>-->
                    <!--<div class="col-md-12"><label class="labels">Relationship to Student</label><input type="text" id="c_validate7" name="rel_stu" class="form-control" placeholder="" value="<?php //if(!empty($sec_contact_realtion)){ echo $sec_contact_realtion; }?>" required><p id="c_validate_text7" style="color:red"></p></div>-->
                    
                    <div class="col-md-12"><label class="labels">Secondary Contact Number</label>
                      <div class="input-group prefix">                           
                        <span class="input-group-addon">(+65)</span>
                        <input type="tel" name="sec_phone" class="form-control c_phone" id="sec_phone"  maxlength="8" placeholder="" value="<?php if(!empty($sec_contact_phone)){ echo esc_html__($sec_contact_phone); }?>" >
                    </div>
                </div>
                    
                     <div class="col-md-12"><label class="labels">Relationship to Student</label><!--<p id="c_validate_text7" style="color:red; text-align: right;"></p>-->
                     
                     <!--<input type="text" id="c_validate7" name="rel_stu" class="form-control" placeholder="" value="<?php //if(!empty($sec_contact_realtion)){ echo $sec_contact_realtion; }?>" required>-->
                     
                     <!--<select name="rel_stu" id="c_validate7" class="form-control">
                      <?php //if(!empty($sec_contact_realtion)){ echo $sec_contact_realtion; }?>
                      <option value="<?php //if(!empty($sec_contact_realtion)){ echo $sec_contact_realtion; }?>" hidden><?php //if(!empty($sec_contact_realtion)){ echo $sec_contact_realtion; }?></option>
                      <option value="Father">Father</option>
                      <option value="Mother">Mother</option>
                      <option value="Guardian">Guardian</option>
                      <option value="Sibling (Above 18)">Sibling (Above 18)</option>
                      </select>-->
    
                      <select name="rel_stu" id="c_validate7" class="form-control">
                      <!-- <option value=""></option> -->
                      <option value="Father" <?php if(!empty($sec_contact_realtion) && $sec_contact_realtion == 'Father'){ echo 'selected'; }?>>Father</option>
                      <option value="Mother" <?php if(!empty($sec_contact_realtion) && $sec_contact_realtion == 'Mother'){ echo 'selected'; }?>>Mother</option>
                      <option value="Guardian" <?php if(!empty($sec_contact_realtion) && $sec_contact_realtion == 'Guardian'){ echo 'selected'; }?>>Guardian</option>
                      <option value="Sibling (Above 18)" <?php if(!empty($sec_contact_realtion) && $sec_contact_realtion == 'Sibling (Above 18)'){ echo 'selected'; }?>>Sibling (Above 18)</option>
                     </select>
                     </div>
                    
                     <div class="col-md-12"><label class="labels">Account Password</label><input type="password" id="c_pass" name="user_pass" class="form-control" placeholder=""  value="" > <i class="bb-icon-eye" aria-hidden="true" onclick="myFunction1()"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <!-- <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="button">Update</button></div> -->
             <input type="submit" id="sub" name="usrup" class="profile-button" value="Update »">
        </div>
    </div>
   </form>

    <br/><br/><br/>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-right">Purchase a Pass</h4>
    </div>

     <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-left">Number of Months left:</h5> &nbsp; &nbsp; &nbsp; &nbsp;
        <h6 class="text-center">
            <?php 
            $user = get_userdata($user_id);          
            $membership_level = pmpro_getMembershipLevelForUser($user_id);
            
            if(!empty($membership_level->expiration_number) )
            {            
             $expiration_date = $membership_level->enddate;
            
            //calculate days left
            $todays_date = current_time('timestamp');
            $time_left = $expiration_date - $todays_date;
            
            //time left?
            if($time_left > 0)
            {
                //convert to days and add to the expiration date (assumes expiration was 1 year)
                 $days_left = floor($time_left/(60*60*24));
                //figure out days based on period
                // if($membership_level->expiration_period == "Day")
                //     echo 'Day'.$total_days = $days_left + $membership_level->expiration_number;
                // elseif($membership_level->expiration_period == "Week")
                //     echo 'Week'.$total_days = $days_left + $membership_level->expiration_number * 7;
                // elseif($membership_level->expiration_period == "Month")
                //     echo 'Month'.$total_days = $days_left + $membership_level->expiration_number * 30;
                // elseif($membership_level->expiration_period == "Year")
                //     echo 'Year'.$total_days = $days_left + $membership_level->expiration_number * 365;

                // $years = floor(abs($time_left) / (365*60*60*24));
                $months = floor(($time_left - $years * 365*60*60*24) / (30*60*60*24));
                // $days = floor(($time_left - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                echo $months;
                //update the end date
                // echo date("Y-m-d", strtotime("+ $total_days Days", $todays_date));
              }
            }
            else
            {
                echo '0';
            }
            ?>
        </h6>
        &nbsp; &nbsp; &nbsp; &nbsp;<h6 class="text-right1">You will need purchase a pass to view lessons</h6>
     </div>
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <div class="row mt-2">
                    <div class="col-md-3 ">
                        <h5 class="text-left" style="padding-top: 30px;">Purchase new passes</h5>
                    </div>
                    <?php
                    global $wpdb, $pmpro_msg, $pmpro_msgt, $pmpro_levels, $current_user, $pmpro_currency_symbol;
                    // print_r($pmpro_levels);
                    foreach($pmpro_levels as $level)
                    {
                    ?>
                    <div class="col-md-3 c_btn">
                       <input type="radio" class="getlevel" id="<?php echo $level->id; ?>" name="getlevelurl" value="<?php echo site_url().'/membership-account/membership-checkout/?level='.$level->id; ?>" />
                       <label for="<?php echo $level->id; ?>" class="memmber_label"><?php echo $level->name; ?><br/>
                        <span class="c_price">SGD <?php echo pmpro_formatPrice($level->initial_payment); ?></span></label>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 Purchase_btn">
            <div class="">
                <div class="row mt-2">
                    <div class="col-md-12 c_btn1">
                        <a href="" class="c_purchse_btn" id="geturl">Purchase</a>
                    </div>
                </div>
            </div>
            <div class="">
                <h6 class="text-right2">You will be redirect to stripe to make payment</h6>
            </div>
        </div>
        <div class="col-md-12">
            <div class="">
                <h4 class="text-left">Email Notification</h4>
                <br/>
                <div class="row mt-2">
                    <div class="col-md-8">
                        <h6 class="text-left">I would like to recieve weekly summary emails:</h6>
                    </div>
                    <div class="col-md-4 c_btn radio_eamil">
                        
                        <?php 
                        $em = get_user_meta($user_id, 'Email_notification_weekly', true );
                        if(!empty($em))
                        {   
                            if($em == 'Yes')
                            {
                               $we = 'Yes';
                            }
                            else
                            {
                                $we = 'No';
                            }
                        }
                        else
                        {
                            $we = 'No';
                        }
                                                 
                        ?>

                             <label><input type="radio" name="weekly_email" class="btn_email_we" value="Yes" <?php if($we == 'Yes'){ echo 'checked';}?>> <span>Yes</span> </label>
                            <label><input type="radio" name="weekly_email" class="btn_email_we" value="No" <?php if($we == 'No'){ echo 'checked';}?>> <span>No</span> </label>

                    </div>
                </div>
            </div>
            <br/><br/>
            <h4 class="text-left">Payments Records</h4>
          
                <?php
                   $invoices = $wpdb->get_results("SELECT mo.*, UNIX_TIMESTAMP(mo.timestamp) as timestamp, du.code_id as code_id FROM $wpdb->pmpro_membership_orders mo LEFT JOIN $wpdb->pmpro_discount_codes_uses du ON mo.id = du.order_id WHERE mo.user_id = '$user_id' ORDER BY mo.timestamp DESC"); 

                  // $levelshistory = $wpdb->get_results("SELECT * FROM $wpdb->pmpro_memberships_users WHERE user_id = '$user_id' ORDER BY id DESC");
                ?>
                <?php if ( $invoices ) { ?>
                 <table>
                 <tr class="tab_heading_profile">
                    <th>Date</th>
                    <th>ID</th>
                    <th>No. of Pass</th>
                    <th>Amount</th>
                </tr>
                <?php
                    foreach ( $invoices as $invoice ) { 
                        $level = pmpro_getLevel( $invoice->membership_id );
                        ?>
                    <tr class="tab_con_profile">
                        <td><?php echo date_i18n( get_option( 'date_format'), $invoice->timestamp ); ?></td>
                        <td><?php echo $invoice->code; ?></td>
                        <td><?php if ( ! empty( $level ) ) { echo $level->name; } else { _e( 'N/A', 'pmpro-member-history'); } ?></td>
                        <td><?php echo pmpro_formatPrice( $invoice->total ); ?></td>
                    </tr>
               <?php } ?>
            </table>            
             <?php } else { 
                    esc_html_e( 'No membership history found.', 'pmpro-member-history');
                } ?>
               
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
<script  src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>

<!--  <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>-->
  
  <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
      <!--<script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>-->
      <script src = "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

  <script>
  $(function() {
      $('input#datepicker').datepicker({
		dateFormat: 'dd-mm-yy',
		showButtonPanel: true,
		changeMonth: true,
		changeYear: true,
		defaultDate: +0,
		showAnim: "fold"
	});
  });
  
  </script>


 <?php
}
else
{
     $urls = site_url();
     echo "<script type='text/javascript'>window.location.href='". $urls ."'</script>";  
}
?>

<style>
.c_req {
    color: red;
    text-align: right;
    margin: 0px !important;
    position: relative;
    top: 21px;
}
input[type="radio"] {
  display:none;
}
input[type="radio"]:checked + label {
  background: #6e6e6eeb;
  color: #fff;
}
i.bb-icon-eye {
    display: inline-block;
    margin: auto;
    position: absolute;
    right: 30px;
    bottom: 5px;
    font-size: 28px;
}
h1, h2, h3, h4, h5, h6, .entry-title, .widget-title, .elementor-widget .elementor-widget-container > h5, .show-support h6, label, h4 .bp-reported-type {
    font-weight: 400;
    color: #000000;
}
.form-control {
    display: block;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #000000;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #000000;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.form-control:disabled, .form-control[readonly] {
    background-color: #ffffff;
    opacity: 1;
    color: grey;
}







/*.row {
    margin: 10px;
}*/
.input-group-addon {
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
    color: #000;
    text-align: center;
    background-color: #fff;
    border: 1px solid #000;
    width: 1%;
    white-space: nowrap;
    vertical-align: middle;
    display: table-cell;
}

.c_phone input {
    position: relative;
}


.prefix input {
    border-radius: 0px 4px 4px 0px;
}
.prefix .input-group-addon {
    border-right: 0;
    border-radius: 4px 0px 0px 4px;
}
.presuffix input {
    border-radius: 0px;
}
.input-group-addon.prefix {
    border-radius: 4px 0px 0px 4px;
    border-right: 0;
}

.input-group {
    position: relative;
    display: table;
    border-collapse: separate;
}

label#sec_phone-error {
    position: absolute;
    top: -32px;
    right: 0;
    font-size: 15px;

}
label#display_name-error {
    position: absolute;
    top: 21px;
    right: 15px;
    font-size: 15px;
}

label#sec_email-error {
    position: absolute;
    top: 21px;
    right: 15px;
    font-size: 15px;
}
label#c_validate4-error {
    position: absolute;
    top: 21px;
    right: 15px;
    font-size: 15px;
}

label#c_validate1-error {
    position: absolute;
    top: 21px;
    right: 15px;
    font-size: 15px;
}
label#datepicker-error {
    position: absolute;
    top: 21px;
    right: 15px;
    font-size: 15px;
}
label#c_pass-error {
    position: absolute;
    top: 21px;
    right: 15px;
    font-size: 15px;
}
label#c_validate7-error {
    position: absolute;
    top: 21px;
    right: 15px;
    font-size: 15px;
}
input.error, textarea.error, select.error {
    border: 1px solid #000000;
    background: #fff !important;
}


@media only screen and (max-width: 1275px) {
form#edituser .row.mt-3 label.labels {
    margin-bottom: 6px;
    margin-top: 10px;
    float: left;
}


.input-group-addon {
    padding: 12px 45px 10px 10px;
    
}
.input-group {
    display: inline-flex;
}
label#sec_phone-error {
    position: unset;
    margin: 0px;
    width: 100%;

}
label#display_name-error {
    position: unset;
    margin: 0px;
}

label#sec_email-error {
    position: unset;
    margin: 0px;
}
label#c_validate4-error {
    position: unset;
    margin: 0px;
}

label#c_validate1-error {
    position: unset;
    margin: 0px;
}
label#datepicker-error {
    position: unset;
    margin: 0px;
}
label#c_pass-error {
    position: unset;
    margin: 0px;
}
label#c_validate7-error {
    position: unset;
    margin: 0px;
}

}
.radio_eamil span {
    background: #f4f4f4;
    border: 1px solid #f4f4f4;
    padding: 4px 30px;
    font-size: 18px;
    font-weight: 900;
    color: #000;
    border-radius: 8px;
  cursor:pointer;
}

.radio_eamil input {
  display:none;  
}

.radio_eamil input:checked + span {
    background: #ffe700;
    border: 1px solid #ffe700;
    padding: 4px 30px;
    font-size: 18px;
    font-weight: 700;
    color: #000;
    border-radius: 8px;
}
</style>

<script>
/*function myFunction() {

  const inpObj1 = document.getElementById("c_validate1");
  if (!inpObj1.checkValidity()) {
    document.getElementById("c_validate_text1").innerHTML = inpObj1.validationMessage;
  } else {
  }
  const inpObj2 = document.getElementById("datepicker");
  if (!inpObj2.checkValidity()) {
    document.getElementById("c_validate_text2").innerHTML = inpObj2.validationMessage;
  } else {
  }
  const inpObj3 = document.getElementById("c_validate3");
  if (!inpObj3.checkValidity()) {
    document.getElementById("c_validate_text3").innerHTML = inpObj3.validationMessage;
  } else {
  }
  const inpObj4 = document.getElementById("c_validate4");
  if (!inpObj4.checkValidity()) {
    document.getElementById("c_validate_text4").innerHTML = inpObj4.validationMessage;
  } else {
  }
  const inpObj5 = document.getElementById("c_validate5");
  if (!inpObj5.checkValidity()) {
    document.getElementById("c_validate_text5").innerHTML = inpObj5.validationMessage;
  } else {
  }
  const inpObj6 = document.getElementById("c_validate6");
  if (!inpObj6.checkValidity()) {
    document.getElementById("c_validate_text6").innerHTML = inpObj6.validationMessage;
  } else {
  }
  const inpObj8 = document.getElementById("c_validate8");
  if (!inpObj8.checkValidity()) {
    document.getElementById("c_validate_text8").innerHTML = inpObj8.validationMessage;
  } else {
  }
  const inpObj7 = document.getElementById("c_validate7");
  if (!inpObj7.checkValidity()) {
    document.getElementById("c_validate_text7").innerHTML = inpObj7.validationMessage;
  } else {
  }
  
  const inpObj9 = document.getElementById("c_upload");
  if (!inpObj9.checkValidity()) {
    document.getElementById("c_validate_text9").innerHTML = inpObj9.validationMessage;
  } else {
  }
} */


     if ($("#edituser").length > 0) {
            $("#edituser").validate({
               rules: {
                    display_name: {
                        required : true,
                        minlength: 2,
                        maxlength:100
                    },
                    dob: {
                        required : true,
                        minlength: 2,
                        maxlength:100
                    },
                    sec_email: {
                        required : true,
                        email: true
                    },
                    sec_name: {
                        required : true,
                        minlength: 2,
                        maxlength:100
                    },
                    rel_stu: {
                        required : true,
                    },
 
                    /*user_pass: {
                        required : true,
                        minlength: 8
                    },*/
                    /*cpassword: {
                        required : true,
                        equalTo: "#password"
                    },*/
                    sec_phone: {
                        required : true,
                        minlength: 8,
                        maxlength: 8
                    },
                    /*userProfileImage: {
                        required: true,
                        extension: "png|jpeg|jpg"
                    },*/
                    /*country: {
                        required : true
                    },
                    Degree: {
                        required : true
                    },
                    University: {
                        required : true
                    }*/
                },
            // set validation messages for the rules are set previously
                messages: {
                    display_name: {
                        required : "*Required",
                        //minlength: "Display Name must contain at least 5 characters",
                       // maxlength: "Display Name maxlength  100 characters"
                    },
                    dob: {
                        required : "*Required",
                        /*minlength: "Display Name must contain at least 5 characters",
                        maxlength: "Display Name maxlength  100 characters"*/
                    },
                    sec_email: {
                        required : "*Required",
                        email: "Enter a valid email. Ex: demo@gmail.com"
                    },
                    sec_name: {
                        required : "*Required",
                       // minlength: "Secondary Contact's Full Name must contain at least 5 characters",
                       // maxlength: "Secondary Contact's Full Name maxlength  100 characters"
                    },
                    rel_stu: {
                        required : "*Required",
                       // minlength: "Secondary Contact's Full Name must contain at least 5 characters",
                       // maxlength: "Secondary Contact's Full Name maxlength  100 characters"
                    },
                    
                    /*user_pass: {
                        required : "Required*",
                        minlength: "Password must contain at least 8 characters"
                    },*/
                    /*cpassword: {
                        required : "Confirm Password is required",
                        equalTo: "Confirm Password must be matched with Password"
                    },*/

                    sec_phone: {
                        required : "*Required"
                    },
                    /*userProfileImage: {
                        required : "Please enter a value with a valid extension."
                    },*/
                    /*country: {
                        required : "Country is required"
                    },
                    Degree: {
                        required : "Degree is required"
                    },
                    University: {
                        required : "University is required"
                    }*/

                }
            })
        }




/*function myFunction() {
  const inpObj1 = document.getElementById("c_validate3");
  if (!inpObj1.checkValidity()) {
    document.getElementById("c_validate_text3").innerHTML = inpObj1.validationMessage;
  } else {
  }
} */
</script>


<script type="text/javascript">
    jQuery(document).ready(function() {
    jQuery('.getlevel').click(function() {
        var value = jQuery("input[type=radio][name=getlevelurl]:checked").val();
        if (value) {
            jQuery('#geturl').attr("href", value)
        }
        else {
            alert('Nothing is selected');
        }
    })
});
</script>

<script>
function myFunction1() {
  var x = document.getElementById("c_pass");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

jQuery(document).ready(function(){
 $("body").on('change', '.btn_email_we', function(){ 
  ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
   var ustatus = jQuery(this).val();
        jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        dataType: 'json',
        data:{
            'action': 'my_action_email',
            'status': ustatus
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