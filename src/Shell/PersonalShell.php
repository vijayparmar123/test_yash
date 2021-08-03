<?php
namespace App\Shell;
use Cake\Console\Shell;

use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

class PersonalShell extends Shell
{
    public function main()
    {
        $this->out('Hello World');
    }
	
	public function notification()
    {
		// $email_subject = "Test mail from cloud personal shell";
		// $email_message = "This is the test message from cloud personal shell so please ignore it.";
		// $email = new Email('default');
		// $email->from("das@gmail.com")
				// ->emailFormat('html')
				// ->to('vijay.parmar@dasinfomedia.com')
				// ->subject($email_subject)
				// ->send($email_message);
		// die;
        $erp_notification = TableRegistry::get('erp_personal_notification');
		$notification_data = $erp_notification->find("all")->where(['OR'=>[["event_type !="=>"single"],["event_date >="=>date("Y-m-d")]]])->hydrate(false)->toArray();
		
		
		if(!empty($notification_data))
		{
			foreach($notification_data as $notification)
			{
				//Get Email ID of all the receipent
				####################################################################
				$user_tbl = TableRegistry::get("erp_users");
				
				$user_data = $user_tbl->find()->where(["user_id"=>$notification['created_by'],"employee_no"=>"","status !="=>0]);
				if(!empty($user_data))
				{
					$emails = array();
					foreach($user_data as $user)
					{
						$emails[] = $user["email_id"];
						$emails[] = $user["second_email"];
					}
				}
					
				$unique_emails = array_unique($emails);
				$all_emails = array_filter($unique_emails, function($value) { return $value !== ''; });
				$send_to = implode(",",$all_emails);
				$to = rtrim($send_to,',');
				
				####################################################################
							
				/* Mail Content Variable Start */
				$message = $notification['message'];
				/* Mail Content Variable End */
				
				$event_recurence = $notification['event_type'];
				$today = date("Y-m-d");
				$event_date = $notification['event_date'];
				$day_before_event = $notification['time_before'];
				$last_mailed_date = date("Y-m-d",strtotime($notification['last_mailed_date']));
			
				######################################################################################
				 
				$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
				$email_subject = "YashNand: Personal Notification"; // The Subject of the email  
				$email_message = "Sir,<br>";
				$email_message .= "<p>Please find Personal Notification details mentioned below. Please check and take necessary actions.</p><br><br>";
				$email_message .= "<p><strong>Message :</strong> {$message}.</p><br><br>";
				$email_message .= "<p><strong>Event Date :</strong> ".date("d/m/Y",strtotime($event_date))."</p>";
				$email_message .= "<p>Thank You.</p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
				$email_message .= "<p><strong>Contact No: 079-23240202</strong></p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				
				
				//// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
				$email_to = $to; // Who the email is to
				$email_to = explode(",",$email_to);
				$headers = "From: ".$email_from;   
	 
				$semi_rand = md5(time());  
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
					
				$headers .= "\nMIME-Version: 1.0\n" .  
							"Content-Type: multipart/mixed;\n" .  
							" boundary=\"{$mime_boundary}\"";  
				// $email_message .= "This is a multi-part message in MIME format.\n\n" .  
								// "--{$mime_boundary}\n" .  
								// "Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
							   // "Content-Transfer-Encoding: 7bit\n\n" .  
				// $email_message .= "\n\n";  
				// $email_message .= "--{$mime_boundary}\n" .   
								  // "Content-Transfer-Encoding: base64\n\n" .  
								  // "--{$mime_boundary}--\n";  
				
				######################################################################################
			
				//For Single Event
			
				if($event_recurence == "single")
				{
					$newdate = strtotime ( '-'.$day_before_event.'day' , strtotime ( $event_date ) ) ;
					$mail_send_date = date ( 'Y-m-d' , $newdate );
					
					if($mail_send_date == $today && $last_mailed_date != $today)
					{
						$email = new Email('default');
						$email->from("das@gmail.com")
								->emailFormat('html')
								->to($email_to)
								->subject($email_subject)
								->send($email_message);
								
						// $ok = @mail('vijay.parmar@dasinfomedia.com,manan.patel@yashnandeng.com', $email_subject, $email_message, $headers);		
						// $ok = @mail($email_to, $email_subject, $email_message, $headers);
						
						/* Update current record last mailed date today's */
						$current_record = $erp_notification->get($notification['id']);
						$post['last_mailed_date'] = date("Y-m-d");
						$row = $erp_notification->patchEntity($current_record,$post);
						$erp_notification->save($row);
					}
				}
				elseif($event_recurence == "weekly")
				{
					$today_dow = date("l");
					
					$newdate = strtotime ( '-'.$day_before_event.'day' , strtotime ( $event_date ) ) ;
					$mail_send_date = date ( 'Y-m-d' , $newdate );
					
					$mail_send_dow = date("l",strtotime($mail_send_date));
					
					//Check that today day and mail send day both same and today mail sent already or not
					if($mail_send_dow == $today_dow && $last_mailed_date != $today)
					{
						$email = new Email('default');
						$email->from("das@gmail.com")
								->emailFormat('html')
								->to($email_to)
								->subject($email_subject)
								->send($email_message);
								
						// $ok = @mail('vijay.parmar@dasinfomedia.com,manan.patel@yashnandeng.com', $email_subject, $email_message, $headers);		
						//01-10-2018 $ok = @mail($email_to, $email_subject, $email_message, $headers);
						
						/* Update current record last mailed date today's */
						$current_record = $erp_notification->get($notification['id']);
						$post['last_mailed_date'] = date("Y-m-d");
						$row = $erp_notification->patchEntity($current_record,$post);
						$erp_notification->save($row);
					}	
				}
				elseif($event_recurence == "monthly")
				{					
					$newdate = strtotime ( '-'.$day_before_event.'day' , strtotime ( $event_date ) ) ;
					$mail_send_date = date ( 'Y-m-d' , $newdate );
					
					$mail_send_day_date = date("d",strtotime($mail_send_date));
					$month_year = date("Y-m");
					$current_month_send_date = $month_year.'-'.$mail_send_day_date;
					
					if($current_month_send_date == $today && $last_mailed_date != $today)
					{
						$email = new Email('default');
						$email->from("das@gmail.com")
								->emailFormat('html')
								->to($email_to)
								->subject($email_subject)
								->send($email_message);
								
						// $ok = @mail('vijay.parmar@dasinfomedia.com,manan.patel@yashnandeng.com', $email_subject, $email_message, $headers);		
						//01-10-2018 $ok = @mail($email_to, $email_subject, $email_message, $headers);
						
						/* Update current record last mailed date today's */
						$current_record = $erp_notification->get($notification['id']);
						$post['last_mailed_date'] = date("Y-m-d");
						$row = $erp_notification->patchEntity($current_record,$post);
						$erp_notification->save($row);
					}
				}
				elseif($event_recurence == "yearly")
				{
					$newdate = strtotime ( '-'.$day_before_event.'day' , strtotime ( $event_date ) ) ;
					$mail_send_date = date ( 'Y-m-d' , $newdate );
					
					$mail_send_day_date_month = date("m-d",strtotime($mail_send_date));
					$year = date("Y");
					$current_year_send_date = $year.'-'.$mail_send_day_date_month;
					
					if($current_year_send_date == $today && $last_mailed_date != $today)
					{
						$email = new Email('default');
						$email->from("das@gmail.com")
								->emailFormat('html')
								->to($email_to)
								->subject($email_subject)
								->send($email_message);
								
						// $ok = @mail('vijay.parmar@dasinfomedia.com,manan.patel@yashnandeng.com', $email_subject, $email_message, $headers);		
						//01-10-2018 $ok = @mail($email_to, $email_subject, $email_message, $headers);
						
						/* Update current record last mailed date today's */
						$current_record = $erp_notification->get($notification['id']);
						$post['last_mailed_date'] = date("Y-m-d");
						$row = $erp_notification->patchEntity($current_record,$post);
						$erp_notification->save($row);
					}
				}
			}
		
		}
    }
}
