<?php
namespace App\Shell;
use Cake\Console\Shell;

use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

class AssetShell extends Shell
{
    public function main()
    {
        $this->out('Hello World');
    }
	
	public function notification()
    {
		// $email_subject = "Test mail from cloud Asset shell";
		// $email_message = "Asset cronjob fired";
		// $email = new Email('default');
		// $email->from("das@gmail.com")
				// ->emailFormat('html')
				// ->to('vijay.parmar@dasinfomedia.com')
				// ->subject($email_subject)
				// ->send($email_message);
		// die;
		
		$all_due_dates = array();
		$all_due_dates[] = date("Y-m-d", strtotime("+30 days")); // After 30 days date
		$all_due_dates[] = date("Y-m-d", strtotime("+15 days")); // After 15 days date
		$all_due_dates[] = date("Y-m-d", strtotime("+7 days")); // After 7 days date
		$all_due_dates[] = date("Y-m-d"); // Same date
		// debug($all_due_dates);die;
		
		/* Get dynamic email from accessright of asset notification */
		$alloted_role =$this->get_asset_notification_role('"asset_notification"',true);
		// debug($alloted_role);
		$not_alloted_role =$this->get_asset_notification_role('"asset_notification"',false);
		// debug($not_alloted_role);die;
		$common_email = $this->get_email_id_by_role_from_user($not_alloted_role);
		$common_email = array_unique($common_email);
		$common_email = array_filter($common_email, function($value) { return $value !== ''; });		
		$common_email = array_filter($common_email, function($value) { return $value !== NULL; });
		$to = $common_email;
		// debug($to);
		/* Get dynamic email from accessright of asset notification */
#################################################################################################
							// Due Date Road Tax code
#################################################################################################
        $erp_assets = TableRegistry::get('erp_assets');
		// debug($all_due_dates);die;
		$road_tax_data = $erp_assets->find("all")->where(['due_date_road_tax IN'=>$all_due_dates])->hydrate(false)->toArray();
		// debug('Due Date Road Tax');
		// debug($road_tax_data);
		if(!empty($road_tax_data))
		{
			foreach($road_tax_data as $retrive_data)
			{
				$now = time(); // or your date as well
				$your_date = strtotime($retrive_data["due_date_road_tax"]);
				$datediff = $your_date - $now;

				$difference_days = round($datediff / (60 * 60 * 24));

				$due_date = date("d-m-Y",strtotime($retrive_data["due_date_road_tax"]));
				
				/* Get Project alloted roles email */
				$project_email = '';
				$project_email = $this->get_email_id_by_project_from_user($retrive_data["deployed_to"],$alloted_role);
				if(!empty($project_email))
				{
					$project_email = array_unique($project_email);
					$project_email = array_filter($project_email, function($value) { return $value !== ''; });		
					$project_email = array_filter($project_email, function($value) { return $value !== NULL; });
					// debug($project_email);
					if(!empty($project_email))
					{
						$to = array_merge($project_email,$to);
					}
				}
				/* Get Project alloted roles email */
				// $message = "above mentioned asset's Road Tax will expire on ".$due_date;
				$message = "Road Tax Certificate will be due after $difference_days Days.";
				$event_date = $due_date;
				/* Mail Content Variable End */	
								 
				$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
				$email_subject = "YashNand: Asset Road Tax Due Date Notification"; // The Subject of the email  
				$email_message = "Sir,<br>";
				$email_message .= "<p>Please find Asset Due Notification details mentioned below. Please check and take necessary actions.</p><br>";
				$email_message .= "<b>Asset ID :</b>{$retrive_data["asset_code"]}<br><br>";
				$email_message .= "<b>Asset Name :</b>{$retrive_data["asset_name"]}<br><br>";
				$email_message .= "<b>Make :</b>{$this->get_category_title($retrive_data["asset_make"])}<br><br>";
				$email_message .= "<b>Capacity :</b>{$retrive_data["capacity"]}<br><br>";
				$email_message .= "<b>Model No :</b>{$retrive_data["model_no"]}<br><br>";
				$email_message .= "<b>Identity / Veh. No :</b>{$retrive_data["vehicle_no"]}<br><br>";
				$email_message .= "<b>Deployed To :</b>{$this->get_projectname($retrive_data["deployed_to"])}<br><br>";
				$email_message .= "<p><strong>Message :</strong> {$message}.</p>";
				$email_message .= "<p><strong>Due Date :</strong> ".$event_date."</p>";
				$email_message .= "<p>Thank You.</p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
				$email_message .= "<p><strong>Contact No: 079-23240202</strong></p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				
				
				// $email_to = "vijay.parmar@dasinfomedia.com"; // Who the email is to  
				$email_to = $to; // Who the email is to
				// $email_to = explode(",",$email_to);
				$headers = "From: ".$email_from;   
	 
				$semi_rand = md5(time());  
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
				// debug($email_to);
				$headers .= "\nMIME-Version: 1.0\n" .  
							"Content-Type: multipart/mixed;\n" .  
							" boundary=\"{$mime_boundary}\"";
				$email = new Email('default');
						$email->from("das@gmail.com")
								->emailFormat('html')
								->to($email_to)
								->subject($email_subject)
								->send($email_message);
			}
		
		}
		// die;
		$to = $common_email;
#################################################################################################
							// Due Date Registration/Passing code
#################################################################################################
        $erp_assets = TableRegistry::get('erp_assets');
		$registration_passing_data = $erp_assets->find("all")->where(['due_date_reg IN'=>$all_due_dates])->hydrate(false)->toArray();
		// debug('Registration/Passing');
		// debug($registration_passing_data);
		if(!empty($registration_passing_data))
		{
			foreach($registration_passing_data as $retrive_data)
			{
				$now = time(); // or your date as well
				$your_date = strtotime($retrive_data["due_date_reg"]);
				$datediff = $your_date - $now;

				$difference_days = round($datediff / (60 * 60 * 24));
				
				$due_date1 = date("d-m-Y",strtotime($retrive_data["due_date_reg"]));
				/* Get Project alloted roles email */
				$project_email = '';
				$project_email = $this->get_email_id_by_project_from_user($retrive_data["deployed_to"],$alloted_role);
				
				if(!empty($project_email))
				{
					$project_email = array_unique($project_email);
					$project_email = array_filter($project_email, function($value) { return $value !== ''; });		
					$project_email = array_filter($project_email, function($value) { return $value !== NULL; });
					// debug($project_email);
					if(!empty($project_email))
					{
						$to = array_merge($project_email,$to);
					}
				}
				/* Get Project alloted roles email */
							
				/* Mail Content Variable Start */
				$message = "Passing Certificate will be due after $difference_days Days.";
				$event_date1 = $due_date1;
				/* Mail Content Variable End */	
								 
				$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
				$email_subject = "YashNand: Asset Passing Due Date Notification"; // The Subject of the email  
				$email_message = "Sir,<br>";
				$email_message .= "<p>Please find Asset Due Notification details mentioned below. Please check and take necessary actions.</p><br>";
				$email_message .= "<b>Asset ID :</b>{$retrive_data["asset_code"]}<br><br>";
				$email_message .= "<b>Asset Name :</b>{$retrive_data["asset_name"]}<br><br>";
				$email_message .= "<b>Make :</b>{$this->get_category_title($retrive_data["asset_make"])}<br><br>";
				$email_message .= "<b>Capacity :</b>{$retrive_data["capacity"]}<br><br>";
				$email_message .= "<b>Model No :</b>{$retrive_data["model_no"]}<br><br>";
				$email_message .= "<b>Identity / Veh. No :</b>{$retrive_data["vehicle_no"]}<br><br>";
				$email_message .= "<b>Deployed To :</b>{$this->get_projectname($retrive_data["deployed_to"])}<br><br>";
				$email_message .= "<p><strong>Message :</strong> {$message}.</p>";
				$email_message .= "<p><strong>Due Date :</strong> ".$event_date1."</p>";
				$email_message .= "<p>Thank You.</p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
				$email_message .= "<p><strong>Contact No: 079-23240202</strong></p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				
				
				// $email_to = "vijay.parmar@dasinfomedia.com"; // Who the email is to  
				$email_to = $to; // Who the email is to
				// $email_to = explode(",",$email_to);
				$headers = "From: ".$email_from;   
	 
				$semi_rand = md5(time());  
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
				
				// debug($email_to);
				$headers .= "\nMIME-Version: 1.0\n" .  
							"Content-Type: multipart/mixed;\n" .  
							" boundary=\"{$mime_boundary}\"";
				$email = new Email('default');
						$email->from("das@gmail.com")
								->emailFormat('html')
								->to($email_to)
								->subject($email_subject)
								->send($email_message);
			}
		
		}
		// die;
		$to = $common_email;
#################################################################################################
							// Due Date Fitness code
#################################################################################################
        $erp_assets = TableRegistry::get('erp_assets');
		$fitness_data = $erp_assets->find("all")->where(['due_date_fitness IN'=>$all_due_dates])->hydrate(false)->toArray();
		// debug('Fitness');
		// debug($fitness_data);
		if(!empty($fitness_data))
		{
			foreach($fitness_data as $retrive_data)
			{
				$now = time(); // or your date as well
				$your_date = strtotime($retrive_data["due_date_fitness"]);
				$datediff = $your_date - $now;

				$difference_days = round($datediff / (60 * 60 * 24));
				
				$due_date2 = date("d-m-Y",strtotime($retrive_data["due_date_fitness"]));
				/* Get Project alloted roles email */
				$project_email = '';
				$project_email = $this->get_email_id_by_project_from_user($retrive_data["deployed_to"],$alloted_role);
				if(!empty($project_email))
				{
					$project_email = array_unique($project_email);
					$project_email = array_filter($project_email, function($value) { return $value !== ''; });		
					$project_email = array_filter($project_email, function($value) { return $value !== NULL; });
					// debug($project_email);
					if(!empty($project_email))
					{
						$to = array_merge($project_email,$to);
					}
				}
				/* Get Project alloted roles email */
							
				/* Mail Content Variable Start */
				$message = "Fitness Certificate will be due after $difference_days Days.";
				$event_date2 = $due_date2;
				/* Mail Content Variable End */	
								 
				$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
				$email_subject = "YashNand: Asset Fitness Due Date Notification"; // The Subject of the email  
				$email_message = "Sir,<br>";
				$email_message .= "<p>Please find Asset Due Notification details mentioned below. Please check and take necessary actions.</p><br>";
				$email_message .= "<b>Asset ID :</b>{$retrive_data["asset_code"]}<br><br>";
				$email_message .= "<b>Asset Name :</b>{$retrive_data["asset_name"]}<br><br>";
				$email_message .= "<b>Make :</b>{$this->get_category_title($retrive_data["asset_make"])}<br><br>";
				$email_message .= "<b>Capacity :</b>{$retrive_data["capacity"]}<br><br>";
				$email_message .= "<b>Model No :</b>{$retrive_data["model_no"]}<br><br>";
				$email_message .= "<b>Identity / Veh. No :</b>{$retrive_data["vehicle_no"]}<br><br>";
				$email_message .= "<b>Deployed To :</b>{$this->get_projectname($retrive_data["deployed_to"])}<br><br>";
				$email_message .= "<p><strong>Message :</strong> {$message}.</p>";
				$email_message .= "<p><strong>Due Date :</strong> ".$event_date2."</p>";
				$email_message .= "<p>Thank You.</p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
				$email_message .= "<p><strong>Contact No: 079-23240202</strong></p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				
				
				// $email_to = "vijay.parmar@dasinfomedia.com"; // Who the email is to  
				$email_to = $to; // Who the email is to
				// $email_to = explode(",",$email_to);
				$headers = "From: ".$email_from;   
	 
				$semi_rand = md5(time());  
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
				
				// debug($email_to);
				$headers .= "\nMIME-Version: 1.0\n" .  
							"Content-Type: multipart/mixed;\n" .  
							" boundary=\"{$mime_boundary}\"";
				$email = new Email('default');
						$email->from("das@gmail.com")
								->emailFormat('html')
								->to($email_to)
								->subject($email_subject)
								->send($email_message);
			}
		
		}
		// die;
		$to = $common_email;
#################################################################################################
							// Due Date Insurance code
#################################################################################################
        $erp_assets = TableRegistry::get('erp_assets');
		$insurance_data = $erp_assets->find("all")->where(['due_date_insurance IN'=>$all_due_dates])->hydrate(false)->toArray();
		// debug('insurance');
		// debug($insurance_data);
		if(!empty($insurance_data))
		{
			foreach($insurance_data as $retrive_data)
			{
				$now = time(); // or your date as well
				$your_date = strtotime($retrive_data["due_date_insurance"]);
				$datediff = $your_date - $now;

				$difference_days = round($datediff / (60 * 60 * 24));
				
				$due_date3 = date("d-m-Y",strtotime($retrive_data["due_date_insurance"]));
				/* Get Project alloted roles email */
				$project_email = '';
				$project_email = $this->get_email_id_by_project_from_user($retrive_data["deployed_to"],$alloted_role);
				if(!empty($project_email))
				{
					$project_email = array_unique($project_email);
					$project_email = array_filter($project_email, function($value) { return $value !== ''; });		
					$project_email = array_filter($project_email, function($value) { return $value !== NULL; });
					$project_email[] = 'shailendra.kansara@yashnandeng.com';
					// debug($project_email);
					if(!empty($project_email))
					{
						$to = array_merge($project_email,$to);
					}
				}
				/* Get Project alloted roles email */
							
				/* Mail Content Variable Start */
				$message = "Insurance Certificate will be due after $difference_days Days.";
				$event_date3 = $due_date3;
				/* Mail Content Variable End */	
								 
				$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
				$email_subject = "YashNand: Asset Insurance Due Date Notification"; // The Subject of the email  
				$email_message = "Sir,<br>";
				$email_message .= "<p>Please find Asset Due Notification details mentioned below. Please check and take necessary actions.</p><br>";
				$email_message .= "<b>Asset ID :</b>{$retrive_data["asset_code"]}<br><br>";
				$email_message .= "<b>Asset Name :</b>{$retrive_data["asset_name"]}<br><br>";
				$email_message .= "<b>Make :</b>{$this->get_category_title($retrive_data["asset_make"])}<br><br>";
				$email_message .= "<b>Capacity :</b>{$retrive_data["capacity"]}<br><br>";
				$email_message .= "<b>Model No :</b>{$retrive_data["model_no"]}<br><br>";
				$email_message .= "<b>Identity / Veh. No :</b>{$retrive_data["vehicle_no"]}<br><br>";
				$email_message .= "<b>Deployed To :</b>{$this->get_projectname($retrive_data["deployed_to"])}<br><br>";
				$email_message .= "<p><strong>Message :</strong> {$message}.</p>";
				$email_message .= "<p><strong>Due Date :</strong> ".$event_date3."</p>";
				$email_message .= "<p>Thank You.</p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
				$email_message .= "<p><strong>Contact No: 079-23240202</strong></p>";
				$email_message .= "---------------------------------------------------------------------------------------------------------------";
				
				
				// $email_to = "vijay.parmar@dasinfomedia.com"; // Who the email is to  
				$email_to = $to; // Who the email is to
				// $email_to = explode(",",$email_to);
				$headers = "From: ".$email_from;   
	 
				$semi_rand = md5(time());  
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
				// debug($email_to);
				$headers .= "\nMIME-Version: 1.0\n" .  
							"Content-Type: multipart/mixed;\n" .  
							" boundary=\"{$mime_boundary}\"";
				$email = new Email('default');
						$email->from("das@gmail.com")
								->emailFormat('html')
								->to($email_to)
								->subject($email_subject)
								->send($email_message);
			}
		
		}		
    }
	
	public function get_projectname($project_id)
	{	
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);	
		$result_arr = array();
		$result_arr['project_name'] = '-';
		if(!empty($project_data)){
			foreach($project_data as $retrive_data)
			{
				$result_arr['project_name'] = $retrive_data['project_name'];			
			}
		}
		return $result_arr['project_name'];
	}
	
	public function get_category_title($cat_id)
	{
		$erp_category_master = TableRegistry::get('erp_category_master'); 
		$category_data = $erp_category_master->find()->where(['cat_id'=>$cat_id]);
		$res_array = array();
		foreach($category_data as $retrive_data)
		{
			$res_array['category_title'] = $retrive_data['category_title'];
			$res_array['cat_id'] = $retrive_data['cat_id'];
		}
		if(isset($res_array['category_title']))
			return $res_array['category_title'];
		else
			return '';
	}
	
	public function get_asset_notification_role($type,$alloted=false)
	{
		$role = array();
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		$type="'[".$type."]'";
		$conn = ConnectionManager::get('default');
		if($alloted)
		{
			$result = $conn->execute('SELECT role,Alloted FROM `erp_accessrights` WHERE Alloted = "1" AND JSON_CONTAINS((JSON_UNQUOTE(`notificationlist`)), '.$type.')')->fetchAll("assoc");
		}else{
			$result = $conn->execute('SELECT role,Alloted FROM `erp_accessrights` WHERE Alloted = "0" AND JSON_CONTAINS((JSON_UNQUOTE(`notificationlist`)), '.$type.')')->fetchAll("assoc");
		}
		foreach($result as $data){
			$role[]=$data['role'];
		}
		return $role;
	}
	
	public function get_email_id_by_role_from_user($role = array())
	{
		$user_tbl = TableRegistry::get("erp_users");
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
		return $emails;
	}
	
	public function get_email_id_by_project_from_user($project_id,$role = array())
	{
		$user_tbl = TableRegistry::get("erp_users");
		$emails = array();
		$ids = array();
		foreach($role as $desg)
		{
			//$user_data = $user_tbl->find('all')->where(["employee_at"=>$project_id,"role"=>$desg,"employee_no ="=>""])->hydrate(false)->toArray();
			$user_data = $user_tbl->find('all');
			// $user_data = $user_data->leftjoin(
							// ["erp_projects_assign"=>"erp_projects_assign"],
							// ["erp_users.user_id = erp_projects_assign.user_id","erp_projects_assign.user_id"=>$project_id])
							// ->where(["erp_users.role"=>$desg,"erp_users.status !="=>0,"erp_users.employee_no"=>""])->hydrate(false)->toArray();
			$user_data = $user_data->leftjoin(
							["erp_projects_assign"=>"erp_projects_assign"],
							["erp_users.user_id = erp_projects_assign.user_id"])
							->where(["erp_users.role"=>$desg,"erp_users.status !="=>0,"erp_users.employee_no"=>"","erp_projects_assign.project_id" => $project_id])->hydrate(false)->toArray();
			
			if(!empty($user_data))
			{
				foreach($user_data as $user)
				{
					$emails[] = $user["email_id"];
					$emails[] = $user["second_email"];
				}
			}
		}
		return $emails;
	}
}
