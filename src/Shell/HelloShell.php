<?php
namespace App\Shell;
use Cake\Console\Shell;

use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class HelloShell extends Shell
{
    public function main()
    {
        $this->out('Hello World');
    }
	
	public function notification()
    {
		// $email_subject = "Test mail from cloud";
		// $email_message = "This is the test message from cloud so please ignore it.";
		// $email = new Email('default');
		// $email->from("das@gmail.com")
				// ->emailFormat('html')
				// ->to('vijay.parmar@dasinfomedia.com')
				// ->subject($email_subject)
				// ->send($email_message);
		// die;
							
		$erp_notification = TableRegistry::get('erp_maintainance_notification');
		$notification_data = $erp_notification->find("all")->where(['OR'=>[["event_type !="=>"single"],["event_date >="=>date("Y-m-d")]]])->hydrate(false)->toArray();
		
		$erp_projects = TableRegistry::get('erp_projects');
		$erp_assets = TableRegistry::get('erp_assets');
		
		//Get Email ID of all the receipent
		####################################################################
		$user_tbl = TableRegistry::get("erp_users");
		//$role = ['constructionmanager','assistantpmm','projectcoordinator','asset-inventoryhead','erphead','erpmanager','pmm'];
		$role= $this->get_mail_list_by_pmnotification('"p&m_notification"');
		
		$emails = array();
		foreach($role as $desg)
		{
			
			$user_data = $user_tbl->find()->where(["role"=>$desg,"employee_no"=>"","status !="=>0]);
			if(!empty($user_data))
			{
				foreach($user_data as $user)
				{
					$emails[] = $user["email_id"];
					$emails[] = $user["second_email"];
				}
			}
		}
		$unique_emails = array_unique($emails);
		$all_emails = array_filter($unique_emails, function($value) { return $value !== ''; });
		$to = implode(",",$all_emails);
		####################################################################
		
	if(!empty($notification_data)){
		foreach($notification_data as $notification)
		{
			// Deploy Project Name
			if($notification['deploy_to'])
			{
				$project_data = $erp_projects->get($notification['deploy_to']);
				$deploy_to = !empty($project_data)?$project_data['project_name']:"";
			}else{
				$deploy_to = "";
			}
			
			// Asset Name
			if($notification['asset_id'])
			{
				$asset_data = $erp_assets->get($notification['asset_id']);
				$asset_name = !empty($asset_data)?$asset_data['asset_name']:"";
			}else{
				$asset_name = "";
			}
			/* Mail Content Variable Start */
			$asset_code = $notification['asset_code'];
			$make = $notification['asset_make'];
			$capacity = $notification['asset_capacity'];
			$model_no = $notification['model_no'];
			$identity = $notification['identity'];
			$message = $notification['message'];
			/* Mail Content Variable End */
			
			$event_recurence = $notification['event_type'];
			$today = date("Y-m-d");
			$event_date = $notification['event_date'];
			$day_before_event = $notification['time_before'];
			$last_mailed_date = date("Y-m-d",strtotime($notification['last_mailed_date']));
			
			######################################################################################
			 
			$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
			$email_subject = "YashNand: P&M Notification"; // The Subject of the email  
			$email_message = "Sir,<br>";
			$email_message .= "<p>Please find P&M Notification details mentioned below. Please check and take necessary actions.</p>";
			$email_message .= "<p><strong>Asset ID :</strong> {$asset_code}.</p>";
			$email_message .= "<p><strong>Asset Name :</strong> {$asset_name}. </p>";
			$email_message .= "<p><strong>Make :</strong> {$make} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Capacity :</strong> {$capacity}</p>";
			$email_message .= "<p><strong>Model No :</strong> {$model_no} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Identity / Veh. No :</strong> {$identity}</p>";
			$email_message .= "<p><strong>Deploy To :</strong> {$deploy_to}.</p><br><br>";
			$email_message .= "<p><strong>Message :</strong> {$message}.</p><br><br>";
			$email_message .= "<p><strong>Event Date :</strong> ".date("d/m/Y",strtotime($event_date))."</p>";
			$email_message .= "<p>Thank You.</p>";
			$email_message .= "---------------------------------------------------------------------------------------------------------------";
			$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
			$email_message .= "<p><strong>Contact No: 079-23240202</strong></p>";
			$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:asset@yashnandeng.com'>asset@yashnandeng.com</a></p>";
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
			
			if($event_recurence == "single"){
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
					// 01-11-2018 $ok = @mail($email_to, $email_subject, $email_message, $headers);
					
					/* Update current record last mailed date today's */
					$current_record = $erp_notification->get($notification['id']);
					$post['last_mailed_date'] = date("Y-m-d");
					$row = $erp_notification->patchEntity($current_record,$post);
					$erp_notification->save($row);
				}
				}elseif($event_recurence == "weekly"){
					
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
						//01-11-2018 $ok = @mail($email_to, $email_subject, $email_message, $headers);
						
						/* Update current record last mailed date today's */
						$current_record = $erp_notification->get($notification['id']);
						$post['last_mailed_date'] = date("Y-m-d");
						$row = $erp_notification->patchEntity($current_record,$post);
						$erp_notification->save($row);
					}
					
					
				}elseif($event_recurence == "monthly"){
					
										
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
						// 01-11-2018 $ok = @mail($email_to, $email_subject, $email_message, $headers);
						
						/* Update current record last mailed date today's */
						$current_record = $erp_notification->get($notification['id']);
						$post['last_mailed_date'] = date("Y-m-d");
						$row = $erp_notification->patchEntity($current_record,$post);
						$erp_notification->save($row);
					}
					
					
				}elseif($event_recurence == "yearly"){
					
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
						//01-11-2018 $ok = @mail($email_to, $email_subject, $email_message, $headers);
						
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
	public function get_mail_list_by_pmnotification($type)
	{
		$role = array();
		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		$type="'[".$type."]'";
		$conn = ConnectionManager::get('default');
		
		$result = $conn->execute('SELECT role,Alloted FROM `erp_accessrights` WHERE JSON_CONTAINS(notificationlist, '.$type.')')->fetchAll("assoc");
		foreach($result as $data){
				$role[]=$data['role'];	
		}
		return $role;
	}
}