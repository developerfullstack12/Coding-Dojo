<?php
 global $bp, $wpdb;

$d = BP_Messages_Thread::get_messages( '6',  $before = null,  $perpage = 10 );
print_r($d);

    $subject = 'Weekly Updates from Coding Dojo';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $users = get_users( array( 'fields' => array( 'ID','user_email','user_login' ) ) );

    foreach($users as $user){
        $user_id = $user->ID;
        $users_emails = $user->user_email;
        $user_logins = $user->user_login;

        $em = get_user_meta($user_id, 'Email_notification_weekly', true );
        if(!empty($em) && $em == 'Yes')
        { 
           $query = "SELECT course_id FROM `wp_learndash_user_activity` WHERE user_id='".$user_id."' ORDER BY activity_updated DESC limit 1";
           $chp = $wpdb->get_results($query);

               if(!empty($chp))
               {
              ob_start();
                 $message = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1"><meta http-equiv="X-UA-Compatible" content="IE=edge" /><head><style type="text/css">@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap");*{font-family: "Poppins", sans-serif;}img.avatar.avatar-100.photo {width: 50px;height: 50px;border-radius: 50px;margin-bottom: 30px;}.date_sh {padding: 20px 30px 40px 30px;color: #333;font-family: "Poppins", Helvetica, Arial, sans-serif;font-size: 18px;font-weight: 400;line-height: 25px;text-decoration: underline;}p{font-size: 18px;margin: 0px 0px 20px  0px;}h1 {margin-bottom: 0px !important;}   img {-ms-interpolation-mode: bicubic;}.sed{padding:30px 30px 30px 30px; border-radius:4px 4px 4px 4px; color:#B2A6A3; font-family:"Poppins"; font-size:15px; font-weight:400; line-height:25px;}</style></head><body style="background-color:#f4f4f4; margin:0 !important; padding:0 !important;"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>';
                $message .= '<tr><td bgcolor="#FFF" align="center" style="padding:20px 10px 0px 10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:900px;"><tbody><tr><td bgcolor="#ffffff" align="left" style="padding:0px 30px 20px 30px;color:#666666;font-size: 16px;font-weight:normal;line-height:25px;"><p>Hi '.$user_logins.',</p><p>Hope you are having a great time learning with Coding Dojo. Here is what you miss out from this weeks discussion</p></td></tr></tbody></table></td></tr><tr><td bgcolor="#FFF" align="center" style="padding: 0px 10px 0px 10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FFFCE0; max-width: 600px;"><tbody><tr><td bgcolor="#FFFCE0" align="center" style="padding:20px 20px 20px 20px;border-radius:4px 4px 0px 0px;color:#43433B;font-family:"Poppins";font-size: 36px;margin-top:10px;font-weight: 600;"> <h1 style="margin: 4px;font-family:"Poppins";">Coding Dojo</h1><p style="font-size: 18px;margin: 0px;font-family:"Poppins";"> Latest Community Discussion </p></td></tr></tbody></table></td></tr>';
                           $cid = $chp[0]->course_id;
                           $gdata = learndash_get_course_groups($cid);
                           $gid = $gdata[0];
                           $querys = "SELECT group_id FROM `wp_bp_groups_groupmeta` WHERE meta_key='_sync_group_id' and meta_value='".$gid."'";

                           $bgid = $wpdb->get_results($querys);
                           $buddyid = $bgid[0]->group_id;
                           if(!empty($buddyid))
                           {
                             $que = "SELECT bpbm_threads_id FROM `wp_bpbm_threadsmeta` WHERE meta_key='group_id' AND meta_value='".$buddyid."'";
                             $threadid = $wpdb->get_results($que);
                             $tid = $threadid[0]->bpbm_threads_id;
                           }
                    
                           $ques = "SELECT * FROM `wp_bp_messages_messages` WHERE thread_id='".$tid."' and sender_id != 0 ORDER BY date_sent DESC LIMIT 10";
                           $threadid = $wpdb->get_results($ques);
                           $msdarr = array();
                               foreach ($threadid as $key => $value) {
                                    $datetime = $value->date_sent;
                                    $dt = new DateTime($datetime);
                                    $date = $dt->format('d F, Y');
                                    $msdarr[$date][] = $value;
                               }
                               foreach ($msdarr as $key => $value) {
                                    $message .= '<tr><td bgcolor="#FFF" align="center" style="padding:0px 10px 0px 10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;"><tbody><tr><td bgcolor="#ffffff" align="center" style="padding:20px 30px 40px 30px; color:#666666; font-family:"Poppins", Helvetica, Arial, sans-serif; font-size:18px; font-weight:400; line-height:25px;text-decoration:underline;"><div class="date_sh">';
                                    $message .= $key;
                                    $message .= '</div></td></tr>';
                                  foreach ($value as $key => $value) {
                                     $datetime = $value->date_sent;
                                     $dt = new DateTime($datetime);
                                     $sender_id = $value->sender_id;
                                     $messagess = $value->message;
                                     $time = $dt->format('H:i A');
                                     $the_user = get_user_by( 'id', $value->sender_id );
                                     $user_email = $the_user->user_email;
                                     $user_name = $the_user->user_login;
                                     $message .= '<tr><td class="p-2 whitespace-nowrap"><div style="display: flex;"><div style="margin-right: 20px;align-items: center;display: flex;">';
                                     $message .= get_avatar( $sender_id, 100 ); 
                                     $message .= '</div><div class="font-medium text-gray-800">';
                                     $message .= $user_name.' '.$time;
                                     $message .= '<p style="margin: 0px;">'.$messagess.'</p></div></div></td></tr>';

                                  }
                                  $message .= '</tbody></table></td></tr>';
                               }

                          $message .= '<tr>
                        <td bgcolor="#FFF" align="center" style="padding:0px 10px 60px 10px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 700px;">
                                <tbody>
                                  <tr>
                                    <td bgcolor="#FFF" align="center" style="padding:40px 30px 40px 30px; border-radius:0px 0px 4px 4px; color:#666666; font-family:"Poppins"; font-size:18px; font-weight:400; line-height:25px;">
                                        <a href="'.site_url('message').'" style="background-color:#FFE602; padding:8px 20px; border-radius:10px; text-decoration:none; font-weight:600; color:#333;">Read More</a>
                                    </td>
                                </tr>
                                    <tr>
                                    <td bgcolor="#FFF" align="center"><div class="sed">
                                        <p style="margin:0;">This email was sent by Coding Dojo</br>
                                        If you do not want to receive this type of email in the future, please <a href="'.site_url('profile').'" target="_blank" style="color:#0062cc;">unsubscribe</a>.</br>
                                        https://codingdojo.com.sg.
                                        </p></div>
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
            </body>
            </html>';
               $message .= ob_get_contents();
   
                 ob_end_clean();
                 wp_mail( $users_emails, $subject, $message, $headers );
               }
         // wp_mail( $users_emails, $subject, $body, $headers );
        }

    } 
?>