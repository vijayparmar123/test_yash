<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Datasource\ConnectionManager;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry; 
use Cake\Routing\Router;

use DateTime;
use DatePeriod;
use DateInterval;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class HumanresourceController extends AppController
{

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
	public $user_id;
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent('Flash');
		$this->loadComponent('ERPfunction');
		$this->user_id=$this->request->session()->read('user_id');
		$this->rights = $this->Usermanage->hr_access_right();
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$action = $this->request->action;
		if(isset($this->rights[$action][$this->role]) && $action != "mailsalaryslip")
		{
			$is_capable = $this->rights[$action][$this->role];	
		}
		else
			$is_capable = 0;
		
		$this->set('is_capable',$is_capable);
		$this->set('role',$this->role);
		
	}
	/*View Bonus Record Start*/

	public function viewbonusrecord()
	{
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set("projects",$projects);

		 if($this->request->is("post"))
		{
			if(isset($this->request->data["go"]))
			{
				$request = $this->request->data;
				
				$start_month = $request['current_year'];
				$end_month = $request['previous_year'];

				

				$current_year = date("Y",strtotime($request['current_year']));
				$previous_year = date("Y",strtotime($request['previous_year']));
				

				$bonus_tbl = TableRegistry::get("bonus_records");

				$bonus_records = $bonus_tbl->find()->where(["current_year"=>$current_year,"previous_year"=>$previous_year])->select('user_id')->hydrate(false)->toArray();


				if(!empty($bonus_records))
				{
					$user_ids = array();
					foreach($bonus_records as $bonus)
					{
						$ids[] = $bonus['user_id'];
					}
				}
				$exgrica_tbl = TableRegistry::get('exgrica_record');
				$exgrica_record = $exgrica_tbl->find()->where(["bonus_date <="=>$end_month,'bonus_date >='=>$start_month])->select(['user_id','bonus_date'])->hydrate(false)->toArray();


				if(!empty($exgrica_record))
				{
					foreach ($exgrica_record as $exgrica) 
					{
						$exgrica_id[] = $exgrica['user_id'];
	 				}
	 			}


	 			if(!empty($ids) && !empty($exgrica_id))
	 			{ 
					$user_id1 = array_merge($ids,$exgrica_id);
					$user_ids = array_unique($user_id1);
				}
				if(!empty($ids) && empty($exgrica_id))
				{ 
					$user_ids = $ids;
				}
				if(empty($ids) && !empty($exgrica_id))
				{
					$user_ids = $exgrica_id;
				}

				$user_tbl =TableRegistry::get("erp_users");
				$user_data = null;	
				if(!empty($user_ids))
				{
					$user_data = $user_tbl->find()->where(["user_id  IN"=>$user_ids,"employee_no !=" => "","is_resign"=>0,"employee_at"=>$request['project_id']])->select(["user_id","employee_at","user_identy_number","first_name","middle_name","last_name","designation"])->hydrate(false)->toArray();
				}
	
				
				$this->set("start_month",$start_month);
				$this->set("end_month",$end_month);
				$this->set("users",$user_data);
				$this->set("current_year",$current_year);
				$this->set("previous_year",$previous_year);
				$this->set("previous_month_year",$request['previous_year']);
				$this->set("current_month_year",$request['current_year']);
			  }
				
			}
		}		
	
	/*View Bonus Record End*/

	

	/*View Bonus Start*/
	public function viewbonus($user_id,$current_year,$previous_year)
	{
		
		$emp_tbl = TableRegistry::get("erp_users");
		$find =  $emp_tbl->get($user_id);
		
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => "",'user_id'=>$user_id]);
		$data = $data->select(["user_id","name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();

		$this->set('employees',$data);
		$this->set('records',$find);

		$start = new DateTime($previous_year.'-4-01');
		$interval = new DateInterval('P1M');
		$end = new DateTime($current_year.'-3-31');

		$period = new DatePeriod($start, $interval, $end);
		$financial_data = array();
		foreach ($period as $dt) {
			// echo $dt->format('m Y') . PHP_EOL;
			// $financial_data[$dt->format('Y')][$dt->format('m')];
			$financial_data['month'][] = intval($dt->format('m'));
			$financial_data['year'][] = $dt->format('Y');
		}
		$this->set('financial_data',$financial_data);
		// debug($financial_data);die;
		$this->set('current_year',$current_year);
		$this->set('previous_year',$previous_year);


		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_records = $salary_tbl->find()->where(["AND"=>[["user_id"=>$user_id],["OR"=>[["substr(month, 1,1)  >"=>3,"year"=>$previous_year],["substr(month, 1,1)  <"=>4,"year"=>$current_year]]]]])->hydrate(false)->toArray();

		$bonus_tbl = TableRegistry::get('bonus_records');
		$bonus_records = $bonus_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		//debug($bonus_records);die;
		$bonus = 0;
		$total_bonus = 0; 
		foreach ($bonus_records as $row) {
			$bonus = $row['extra_bonus'];	
			$total_bonus = $row['total_bonus'];
		}
		$this->set('bonus',$bonus);
		$this->set('total_bonus',$total_bonus);

	
		$salary_data = array();
		$total_earning = 0;

		foreach($salary_records as $record)
		{	
			$salary_data[$record['year']][$record['month']] = $record['total_earning'];
			
			$total_earning += $record['total_earning'];
		}
		$tax = $total_earning * 8.33 /100;
	//	debug($salary_data);die;
		$this->set('salary_data',$salary_data);
		$this->set('tax',$tax);
		$this->set('total_earning',$total_earning);
		if($this->request->is("post"))
		{
			$created_by = $this->request->session()->read('user_id');
			$data = $this->request->data();
			$data['current_year'] = $current_year;
			$data['previous_year'] = $previous_year;
			$data['created_by'] = $created_by;
			$data['created_date'] = date('Y-m-d');
		
			$bonus_records = TableRegistry::get('bonus_records');
			$row = $bonus_records->newEntity();	

			$row = $bonus_records->patchEntity($row,$data);

			if($bonus_records->save($row))
			{
				$this->Flash->success(__('Bonus Added Successfully', null), 
								'default', 
								array('class' => 'success'));
					return $this->redirect(['action'=>'bonusalert']);
			}
		}
	}
	/*View Bonus End*/
	

	/*Delete Data*/
	public function deletepaystructure($user_id=null)
	{
		 $this->autoRender=false;
		  $user_id = (int)$user_id;
		  $user_tbl = TableRegistry::get("erp_users_history");
		  $query = $user_tbl->find()->where(['user_id'=>$user_id])->first();
		  $id=$query->id;
		  $user_data = $user_tbl->get($id);
		
     	  $user_tbl->delete($user_data);
		  return $this->redirect(['controller'=>'Humanresource','action' => 'viewrecords']);
	}
	public function deletetransfer($user_id = null)
	{
			$this->autoRender=false;
			$history_id = (int)$user_id;
			$user_tbl = TableRegistry::get("erp_employee_transfer_history");
			$user_data = $user_tbl->get($history_id);
			$user_tbl->delete($user_data);
			return $this->redirect(['controller'=>'Humanresource','action' => 'viewrecords']);
	}	
	public function deletedesignationhistory($user_id = null)
	{
			$this->autoRender=false;
			$id = (int)$user_id;
			$user_tbl = TableRegistry::get("erp_designation_history");
			$query = $user_tbl->find()->where(['user_id'=>$user_id])->first();
			$id = $query->id;
			
			$user_data = $user_tbl->get($id);
			$user_tbl->delete($user_data);
			return $this->redirect(['controller'=>'Humanresource','action' => 'viewrecords']);
	}
	/*Delete Data End*/
	
	public function loanpending($projects_id=null,$from=null,$to=null)
	{
		$table_category=TableRegistry::get('erp_category_master');
		
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set("projects",$projects);
		
		$users_table = TableRegistry::get('erp_users'); 
		$name_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0]);
		$this->set('name_list',$name_list);
		$erp_loan = TableRegistry::get("erp_loan");
		$erp_users = TableRegistry::get("erp_users");
	
		$this->set('role',$this->role);
		
		
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["erp_loan.given_date >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["erp_loan.given_date <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["erp_users.employee_at"] = ($projects_id!=null)?$projects_id:NULL;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
					
			$result = $erp_loan->find()->select($erp_loan);
						$result = $result->innerjoin(
							["erp_users"=>"erp_users"],
							["erp_loan.user_id = erp_users.user_id"])
							->where($or1)->select($erp_users)->hydrate(false)->toArray();
				$this->set("loan_data",$result);
			
		}
		else
		{
			$tbl = TableRegistry::get("erp_loan");
			$loan_data = $tbl->find("all")->where(['loan_status'=>1])->hydrate(false)->toArray();
			$this->set("loan_data",$loan_data);
		}
		
		if($this->request->is("post"))
		{
				
				$post = $this->request->data;	
				$or = array();				
				
				$or["erp_loan.user_id IN"] = (!empty($post["user_id"]) && $post["user_id"][0] != "All" )?$post["user_id"]:NULL;
				$or["erp_users.designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
				$or["erp_users.employee_at IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["erp_users.employee_no"] = (!empty($post["employee_no"]))?$post["employee_no"]:NULL;
				$or["erp_loan.loan_status ="] = 1;

				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				$result = $erp_loan->find()->select($erp_loan);
						$result = $result->innerjoin(
							["erp_users"=>"erp_users"],
							["erp_loan.user_id = erp_users.user_id"])
							->where($or)->select($erp_users)->hydrate(false)->toArray();
				$this->set("loan_data",$result);
		}
		
	}
	
    public function index()
    {
    }
    /*Candidate Insert Update And Delete*/

    public function addcandidate ($candidate_id=Null)
	{
		$erp_candidate = TableRegistry::get('erp_candidate');

		if(isset($candidate_id))
		{
			$user_action = 'edit';
			$candidate_data = $erp_candidate->get($candidate_id);
			 //$employee_data['employee_no'] = $employee_no;
			$this->set('id',$candidate_id);
			$this->set('employee_data',$candidate_data);
			$this->set('form_header','Edit Candidate');
			$this->set('button_text','Update Candidate');	
		}
		else
		{
			$user_action = 'insert';
		
			$this->set('form_header','Add Candidate');
			$this->set('button_text','Add Candidate');
		}
		
		$this->set('user_action',$user_action);
		
		if($this->request->is('post'))
		{
		//var_dump($_FILES);die;
			if(isset($_FILES['user_image_url']) || isset($_FILES['image_url']) || isset($_FILES["aadhar_card_att"]) || isset($_FILES["pan_card_att"]) || isset($_FILES["driving_licence_att"]) || isset($_FILES["cancel_cheque_att"]) || isset($_FILES["resume_att"]) || isset($_FILES["qualification_doc"]) || isset($_FILES["other_doc"]))
			{	
				$ext1=1;
				$ext2=1;
				$ext3=1;
				$ext4=1;
				$ext5=1;
				$ext6=1;
				$ext7=1;
				$ext8=1;
				$ext9=1;
				
				if(isset($_FILES['image_url'])){
					$file =$_FILES['image_url']["name"];
					$size = count($file);
					for($i=0;$i<$size;$i++) {
						$parts = pathinfo($_FILES['image_url']['name'][$i]);
					}
					$ext1 = $this->ERPfunction->check_valid_extension($parts['basename']);
				}
				if(isset($_FILES['user_image_url'])){
					$user_image_url =$_FILES['user_image_url']['name'];
					$ext2 = $this->ERPfunction->check_valid_extension($user_image_url);	
				}
				if(isset($_FILES['aadhar_card_att'])){
					$aadhar_card_att =$_FILES['aadhar_card_att']['name'];
					$ext3 = $this->ERPfunction->check_valid_extension($aadhar_card_att);	
				}
				if(isset($_FILES['pan_card_att'])){
					$pan_card_att =$_FILES['pan_card_att']['name'];
					$ext4 = $this->ERPfunction->check_valid_extension($pan_card_att);	
				}
				if(isset($_FILES['driving_licence_att'])){
					$driving_licence_att =$_FILES['driving_licence_att']['name'];
					$ext5 = $this->ERPfunction->check_valid_extension($driving_licence_att);	
				}
				if(isset($_FILES['cancel_cheque_att'])){
					$cancel_cheque_att =$_FILES['cancel_cheque_att']['name'];
					$ext6 = $this->ERPfunction->check_valid_extension($cancel_cheque_att);	
				}
				if(isset($_FILES['resume_att'])){
					$resume_att =$_FILES['resume_att']['name'];
					$ext7 = $this->ERPfunction->check_valid_extension($resume_att);	
				}
				if(isset($_FILES['qualification_doc'])){
					$qualification_doc =$_FILES['qualification_doc']['name'];
					$ext8 = $this->ERPfunction->check_valid_extension($qualification_doc);	
				}
				if(isset($_FILES['other_doc'])){
					$other_doc =$_FILES['other_doc']['name'];
					$ext9 = $this->ERPfunction->check_valid_extension($other_doc);	
				}
				
				if($ext1 != 0 && $ext2 != 0 && $ext3 != 0 && $ext4 != 0 && $ext5 != 0 && $ext6 != 0 && $ext7 != 0 && $ext8!= 0 && $ext9!= 0 ) {
					
					$data = $this->request->data;
			
			$candidate_id = $this->ERPfunction->candidate_asset_auto_id("erp_candidate","user_id","user_identy_id");
			$new_assetno = sprintf("%09d", $candidate_id);
			$user_identy_id = 'CH-'.$new_assetno;

			if($user_action == 'insert')
			{
				$this->request->data['user_identy_id'] = $user_identy_id;
			}

			@$this->request->data['attach_label'] = trim(json_encode($this->request->data["attach_label"]),'\"');
				$all_files = array();
				if(isset($_FILES["image_url"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("image_url");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$all_files[] = $attachment_file;
					}					
				}
				$this->request->data['attachment'] = json_encode($all_files);

				
					if(isset($_FILES["image_url"]["name"]))
					{
						$file = $this->ERPfunction->upload_file("image_url");	
						if(!empty($file))
						foreach($file as $attachment_file)
						{
							$all_files[] = $attachment_file;
						}					
					}

					$old_files = array();
					if(isset($data["old_image_url"]))
					{
						$old_files = $data["old_image_url"];				
					}
					
					@$data['attach_label'] = trim(json_encode($data["attach_label"]),'\"');
					if(isset($_FILES["image_url"]["name"]))
					{
						$file = $this->ERPfunction->upload_file("image_url");	
						if(!empty($file))
						foreach($file as $attachment_file)
						{
							$old_files[] = $attachment_file;
						}					
					}
				
					$this->request->data['attachment'] = json_encode($old_files);
			
			if($_FILES["aadhar_card_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("aadhar_card_att");	
				if(!empty($file))
				{
					$this->request->data['aadhar_card_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['aadhar_card_att'] = $this->request->data['old_aadhar_card_att'];
			}
				
			if($_FILES["pan_card_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("pan_card_att");			
				if(!empty($file))
				{
					$this->request->data['pan_card_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['pan_card_att'] = $this->request->data['old_pan_card_att'];
			}
				
			if($_FILES["driving_licence_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("driving_licence_att");
				if(!empty($file))
				{
					$this->request->data['driving_licence_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['driving_licence_att'] = $this->request->data['old_driving_licence_att'];
			}
				
			if($_FILES["cancel_cheque_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("cancel_cheque_att");
				if(!empty($file))
				{
						$this->request->data['cancel_cheque_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['cancel_cheque_att'] = $this->request->data['old_cancel_cheque_att'];
			}
				
			if($_FILES["resume_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("resume_att");	
				if(!empty($file))
				{
					$this->request->data['resume_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['resume_att'] = $this->request->data['old_resume_att'];
			}
				
			if($_FILES["qualification_doc"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("qualification_doc");	
				if(!empty($file))
				{
					$this->request->data['qualification_doc'] = $file;
				}					
			}
			else
			{
				$this->request->data['qualification_doc'] = $this->request->data['old_qualification_doc'];
			}
				
			if($_FILES["other_doc"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("other_doc");
				if(!empty($file))
				{
					$this->request->data['other_doc'] = $file;
				}					
			}
			else
			{
				$this->request->data['other_doc'] = $this->request->data['old_other_doc'];
			}
			
			$erp_candidate = TableRegistry::get('erp_candidate');
			if($user_action == 'insert')
			{
				
				$users = $erp_candidate->newEntity($this->request->data);
			}
			else
			{
				$users = $erp_candidate->patchEntity($candidate_data,$this->request->data);
			}
			
		
			if($erp_candidate->save($users))
			{ 
				if($user_action == 'insert')
				{
				$this->Flash->success(__('Record Insert Successfully '.$user_identy_id, null), 
							'default', 
				array('class' => 'success'));
				}
				else
				{
				 $this->Flash->success(__('Record Update Successfully ', null), 
							'default', 
				 array('class' => 'success'));
				}
				$this->redirect(array("controller" => "Humanresource","action" => "candidatelist"));
			}
					
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				
				$data = $this->request->data;
			
			$candidate_id = $this->ERPfunction->candidate_asset_auto_id("erp_candidate","user_id","user_identy_id");
			$new_assetno = sprintf("%09d", $candidate_id);
			$user_identy_id = 'CH-'.$new_assetno;

			if($user_action == 'insert')
			{
				$this->request->data['user_identy_id'] = $user_identy_id;
			}

			@$this->request->data['attach_label'] = trim(json_encode($this->request->data["attach_label"]),'\"');
				$all_files = array();
				if(isset($_FILES["image_url"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("image_url");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$all_files[] = $attachment_file;
					}					
				}
				$this->request->data['attachment'] = json_encode($all_files);

				
					if(isset($_FILES["image_url"]["name"]))
					{
						$file = $this->ERPfunction->upload_file("image_url");	
						if(!empty($file))
						foreach($file as $attachment_file)
						{
							$all_files[] = $attachment_file;
						}					
					}

					$old_files = array();
					if(isset($data["old_image_url"]))
					{
						$old_files = $data["old_image_url"];				
					}
					
					@$data['attach_label'] = trim(json_encode($data["attach_label"]),'\"');
					if(isset($_FILES["image_url"]["name"]))
					{
						$file = $this->ERPfunction->upload_file("image_url");	
						if(!empty($file))
						foreach($file as $attachment_file)
						{
							$old_files[] = $attachment_file;
						}					
					}
				
					$this->request->data['attachment'] = json_encode($old_files);
			
			if($_FILES["aadhar_card_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("aadhar_card_att");	
				if(!empty($file))
				{
					$this->request->data['aadhar_card_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['aadhar_card_att'] = $this->request->data['old_aadhar_card_att'];
			}
				
			if($_FILES["pan_card_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("pan_card_att");			
				if(!empty($file))
				{
					$this->request->data['pan_card_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['pan_card_att'] = $this->request->data['old_pan_card_att'];
			}
				
			if($_FILES["driving_licence_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("driving_licence_att");
				if(!empty($file))
				{
					$this->request->data['driving_licence_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['driving_licence_att'] = $this->request->data['old_driving_licence_att'];
			}
				
			if($_FILES["cancel_cheque_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("cancel_cheque_att");
				if(!empty($file))
				{
						$this->request->data['cancel_cheque_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['cancel_cheque_att'] = $this->request->data['old_cancel_cheque_att'];
			}
				
			if($_FILES["resume_att"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("resume_att");	
				if(!empty($file))
				{
					$this->request->data['resume_att'] = $file;
				}					
			}
			else
			{
				$this->request->data['resume_att'] = $this->request->data['old_resume_att'];
			}
				
			if($_FILES["qualification_doc"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("qualification_doc");	
				if(!empty($file))
				{
					$this->request->data['qualification_doc'] = $file;
				}					
			}
			else
			{
				$this->request->data['qualification_doc'] = $this->request->data['old_qualification_doc'];
			}
				
			if($_FILES["other_doc"]["name"] != "")
			{
				$file = $this->ERPfunction->upload_image("other_doc");
				if(!empty($file))
				{
					$this->request->data['other_doc'] = $file;
				}					
			}
			else
			{
				$this->request->data['other_doc'] = $this->request->data['old_other_doc'];
			}
			
			$erp_candidate = TableRegistry::get('erp_candidate');
			if($user_action == 'insert')
			{
				
				$users = $erp_candidate->newEntity($this->request->data);
			}
			else
			{
				$users = $erp_candidate->patchEntity($candidate_data,$this->request->data);
			}
			
		
			if($erp_candidate->save($users))
			{ 
				if($user_action == 'insert')
				{
				$this->Flash->success(__('Record Insert Successfully '.$user_identy_id, null), 
							'default', 
				array('class' => 'success'));
				}
				else
				{
				 $this->Flash->success(__('Record Update Successfully ', null), 
							'default', 
				 array('class' => 'success'));
				}
				$this->redirect(array("controller" => "Humanresource","action" => "candidatelist"));
			}
				
			}
			
			
		}	
	}

	public function viewcandidate($candidate_id=Null)
	{
		$erp_candidate = TableRegistry::get('erp_candidate');
		if(isset($candidate_id))
		{  	
			$user_action = 'edit';
			$candidate_data = $erp_candidate->get($candidate_id);
			 //$employee_data['employee_no'] = $employee_no;
			$this->set('id',$candidate_id);
			$this->set('employee_data',$candidate_data);
			$this->set('form_header','View Candidate');			
			
		}
		$this->set('id',$candidate_id);
		$this->set('user_action',$user_action);
	}

	public function candidatelist($projects_id = null)
	{
		$candidate_tabel = TableRegistry::get('erp_candidate');
		$candidate_data = $candidate_tabel->find()->hydrate(false)->toArray();
		$this->set('user_list',$candidate_data);

		$users_table = TableRegistry::get('erp_candidate'); 

		$name_list = $users_table->find()->where(["user_identy_id !=" => ""])->hydrate(false)->toArray();

		$this->set('name_list',$name_list);	

		if($this->request->is("post"))
		{	
				
				$post = $this->request->data;

				$or = array();				
				
				$or["user_id IN"] = (!empty($post["user_id"]) && $post["user_id"][0] != "All" )?$post["user_id"]:NULL;
				
				$or["user_identy_id"] = (!empty($post["user_identy_id"]))?$post["user_identy_id"]:NULL;
				$or["mobile_no"] = (!empty($post["mobile_no"]))?$post["mobile_no"]:NULL;
				
				
				$keys = array_keys($or,"");

				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				$user_list = $users_table->find()->where(["user_identy_id !=" => "",$or]);
				$this->set('user_list',$user_list);
     		}
	}
	public function deletecandidate($user_id)
	{

		$users_table = TableRegistry::get('erp_candidate'); 

		$this->request->is(['post','delete']);

		$user_data =$users_table->get($user_id);
	
		if($users_table->delete($user_data))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		$this->redirect(array("controller" => "Humanresource","action" => "candidatelist"));
	}
    /*Candidate Insert Update And Delete*/

   public function printcandidate($eid)
	{
		require_once(ROOT .DS .'vendor' . DS . 'mpdf' . DS . 'mpdf.php');
		$rmc_tbl = TableRegistry::get("erp_candidate");
		$data = $rmc_tbl->get($eid);
		
		$this->set("data",$data->toArray());
		$this->set("id",$eid);
	}
	
	public function emplyeelist($projects_id=null)
	{
		/* $users_table = TableRegistry::get('erp_employee');  */
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set("projects",$projects);
		
		$users_table = TableRegistry::get('erp_users'); 
		$name_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0,"pay_type"=>"employee"]);
		$this->set('name_list',$name_list);
		
		$users_table = TableRegistry::get("erp_users");
	
		$this->set('role',$this->role);
		
		if($projects_id!=null)
		{
			
			$or1 = array();		
			//$or1["date_of_joining >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			//$or1["date_of_joining <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["employee_at"] = ($projects_id!=null)?$projects_id:NULL;
			$or1["employee_no !="] = "";
			$or1["is_resign !="] = 0;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
			
			$user_list = $users_table->find()->where([$or1]);
			
			$this->set('user_list',$user_list);			
			
		}
		else
		{
				$users_table = TableRegistry::get('erp_users'); 
				// $user_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0,"pay_type"=>"employee"]);
				$user_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0]);
				$this->set('user_list',$user_list);
		}
		if($this->request->is("post"))
		{	
				$post = $this->request->data;	
				// debug($post);die;
				$or = array();				
				
				$or["user_id IN"] = (!empty($post["user_id"]) && $post["user_id"][0] != "All" )?$post["user_id"]:NULL;
				$or["designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
				$or["pay_type IN"] = (!empty($post["pay_type"]) && $post["pay_type"][0] != "All" )?$post["pay_type"]:NULL;
				$or["employee_at IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["employee_no"] = (!empty($post["employee_no"]))?$post["employee_no"]:NULL;
				$or["mobile_no"] = (!empty($post["mobile_no"]))?$post["mobile_no"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				$user_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0,$or]);
				$this->set('user_list',$user_list);
		}
		
	}
	
	public function notworkingemplyeelist()
	{
		/* $users_table = TableRegistry::get('erp_employee');  */
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set("projects",$projects);
		
		$users_table = TableRegistry::get('erp_users'); 
		$name_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>1]);
		$this->set('name_list',$name_list);
		
	
		$this->set('role',$this->role);
		
		if($this->request->is("post"))
		{	
					
				$users_table = TableRegistry::get("erp_users");
				$post = $this->request->data;	
				$or = array();				
				
				$or["user_id IN"] = (!empty($post["user_id"]) && $post["user_id"][0] != "All" )?$post["user_id"]:NULL;
				$or["designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
				$or["pay_type"] = (!empty($post["pay_type"]) && $post["pay_type"] != "All" )?$post["pay_type"]:NULL;
				$or["employee_at IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["employee_no"] = (!empty($post["employee_no"]))?$post["employee_no"]:NULL;
				$or["mobile_no"] = (!empty($post["mobile_no"]))?$post["mobile_no"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				$user_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>1,$or]);
				$this->set('user_list',$user_list);
		}
		else
		{
				$users_table = TableRegistry::get('erp_users'); 
				$user_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>1]);
				$this->set('user_list',$user_list);
		}
	}
	public function rejoin($id)
	{
			$rejoin_id = (int)$id;
		
		$users_table = TableRegistry::get('erp_users');
		/* Create new Record */
		$user_list = $users_table->get($rejoin_id);
		$user_list = $user_list->toArray();
		unset($user_list['user_id']);
		unset($user_list['user_identy_number']);
		$user_list['user_identy_number'] = $this->ERPfunction->get_user_identity_number();
		$user_list['non_working_id'] = $rejoin_id;
		$user_list['rejoin_date'] = date('Y-m-d');
		$user_list['is_resign']=0;	
		$new_user = $users_table->newEntity();
		$new_record = $users_table->patchEntity($new_user,$user_list);
		/* Create New Record */
		if($users_table->save($new_record))
		{
			$old_record = $users_table->get($rejoin_id);
			$old_record->already_rejoined = 1;
			$users_table->save($old_record);
			$this->Flash->success(__('Personnel Re-Join Successfully', null), 
			'default', 
			array('class' => 'success'));
			$this->redirect(array("controller" => "Humanresource","action" => "notworkingemplyeelist"));
		}
	}
	public function addemployee($employee_id=Null)
    {
		$new_emp = $this->ERPfunction->get_last_emp_no();
		$new_no = sprintf("%09d",  + $new_emp);
		$employee_no = 'YNEC/EMP/'.$new_no;
		
		$erp_employee = TableRegistry::get('erp_users'); 

		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);

		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);

		/*Candidate*/
			$candidate_tabel  = TableRegistry::get('erp_candidate');
			$candidate = $candidate_tabel ->find()->where(['status'=>'0'])->hydrate(false)->toArray();
			$this->set('candidate',$candidate);
		/*Candidate*/

		$designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations);

		if(isset($employee_id))
		{
		   	
			$user_action = 'edit';
			
			$employee_data = $erp_employee->get($employee_id);			
			
			 //$employee_data['employee_no'] = $employee_no;
			
			$this->set('id',$employee_id);
			$this->set('employee_data',$employee_data);
			$this->set('form_header','Edit Personnel');
			$this->set('button_text','Update Personnel');			
			
		}
		else
		{
			$user_action = 'insert';
			
			$this->set('employee_no',$employee_no);
			$this->set('form_header','Add Personnel');
			$this->set('button_text','Add Personnel');
			
		}		
	
		$this->set('user_action',$user_action);
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		if($this->request->is('post'))
		{	
			
			$this->set('employee_data',$this->request->data);			
			$this->request->data['date_of_joining']=$this->ERPfunction->set_date($this->request->data['date_of_joining']);
			$this->request->data['date_of_birth']=date('Y-m-d',strtotime($this->request->data['date_of_birth']));
			$this->request->data['pf_ref_no']=$this->request->data['pf_ref_no'];
			// $this->request->data['as_on_date']=$this->ERPfunction->set_date($this->request->data['as_on_date']);
			// $this->request->data['extra_payment']= implode(',',$this->request->data['extra_payment']);
			// $this->request->data['incentive_includes']= implode(',',$this->request->data['incentive_includes']);
			//debug($this->request->data['date_of_birth']);die;
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;
			$image=$this->ERPfunction->upload_image('user_image_url',$this->request->data['old_user_image']);
			$this->request->data['image_url']=$image;
			if($this->request->data['food_allowance'] == "fixed")
			{
				$this->request->data['food_allowance'] = $this->request->data['food_fixed'] ;
			}
			
			if($this->request->data['acco_allowance'] == "fixed")
			{
				$this->request->data['acco_allowance'] = $this->request->data['acc_fixed'] ;
			}
			if($this->request->data['trans_allowance'] == "fixed")
			{
				$this->request->data['trans_allowance'] = $this->request->data['trans_fixed'] ;
			}
			if($this->request->data['mobile_allowance'] == "fixed")
			{
				$this->request->data['mobile_allowance'] = $this->request->data['mobile_fixed'] ;
			}
			
			
			if(isset($_FILES['user_image_url']) || isset($_FILES['image_url']) || isset($_FILES["aadhar_card_att"]) || isset($_FILES["pan_card_att"]) || isset($_FILES["driving_licence_att"]) || isset($_FILES["cancel_cheque_att"]) || isset($_FILES["resume_att"]) || isset($_FILES["qualification_doc"]) || isset($_FILES["other_doc"]))
			{	
				$ext1=1;
				$ext2=1;
				$ext3=1;
				$ext4=1;
				$ext5=1;
				$ext6=1;
				$ext7=1;
				$ext8=1;
				$ext9=1;
				
				if(isset($_FILES['image_url'])){
					$file =$_FILES['image_url']["name"];
					$size = count($file);
					for($i=0;$i<$size;$i++) {
						$parts = pathinfo($_FILES['image_url']['name'][$i]);
					}
					$ext1 = $this->ERPfunction->check_valid_extension($parts['basename']);
				}
				if(isset($_FILES['user_image_url'])){
					$user_image_url =$_FILES['user_image_url']['name'];
					$ext2 = $this->ERPfunction->check_valid_extension($user_image_url);	
				}
				if(isset($_FILES['aadhar_card_att'])){
					$aadhar_card_att =$_FILES['aadhar_card_att']['name'];
					$ext3 = $this->ERPfunction->check_valid_extension($aadhar_card_att);	
				}
				if(isset($_FILES['pan_card_att'])){
					$pan_card_att =$_FILES['pan_card_att']['name'];
					$ext4 = $this->ERPfunction->check_valid_extension($pan_card_att);	
				}
				if(isset($_FILES['driving_licence_att'])){
					$driving_licence_att =$_FILES['driving_licence_att']['name'];
					$ext5 = $this->ERPfunction->check_valid_extension($driving_licence_att);	
				}
				if(isset($_FILES['cancel_cheque_att'])){
					$cancel_cheque_att =$_FILES['cancel_cheque_att']['name'];
					$ext6 = $this->ERPfunction->check_valid_extension($cancel_cheque_att);	
				}
				if(isset($_FILES['resume_att'])){
					$resume_att =$_FILES['resume_att']['name'];
					$ext7 = $this->ERPfunction->check_valid_extension($resume_att);	
				}
				if(isset($_FILES['qualification_doc'])){
					$qualification_doc =$_FILES['qualification_doc']['name'];
					$ext8 = $this->ERPfunction->check_valid_extension($qualification_doc);	
				}
				if(isset($_FILES['other_doc'])){
					$other_doc =$_FILES['other_doc']['name'];
					$ext9 = $this->ERPfunction->check_valid_extension($other_doc);	
				}
				
				if($ext1 != 0 && $ext2 != 0 && $ext3 != 0 && $ext4 != 0 && $ext5 != 0 && $ext6 != 0 && $ext7 != 0 && $ext8!= 0 && $ext9!= 0 ) {
					
					if($user_action == 'edit')
			{
				$employee_data = $erp_employee->get($employee_id);			
			
			// $employee_data['employee_no'] = $employee_no;
			
			$this->set('employee_data',$employee_data);
				//debug($this->request->data);die;
				$emp_no = $this->request->data['employee_no'];
				$count = $this->ERPfunction->check_emp_no_exists($emp_no);
				if($count > 1) /* or >= 2 */
				{
					$new_emp = $this->ERPfunction->get_last_emp_no();
					$new_no = sprintf("%09d",  + $new_emp);
					$emp_no = 'YNEC/EMP/'.$new_no;
					$i = 1;
					$pass = 0;
					do{
						$count = $this->ERPfunction->check_emp_no_exists($emp_no);						
						if($count == 0)
						{
							$pass = 1;
						}
						else{ // emp_no + 1;							
							$auto_fld = $emp_no;
							$split = explode("/",$auto_fld);	
							$find = sizeof($split) - 1;
							$last_id = $split[$find];
							$last_number = (int) $last_id;
							$new_emp = $last_number + 1;
							$new_no = sprintf("%09d",  + $new_emp);
							$emp_no = 'YNEC/EMP/'.$new_no;
						}
						
					}while($pass != 1);		
					$this->request->data['employee_no'] = $emp_no;	
				}
				
				
				if(isset($_POST['password']) && $_POST['password']!="")
				$this->request->data['password']= md5($_POST['password']);
				$post_data = $this->request->data;

				$old_files = array();
				if(isset($post_data["old_image_url"]))
				{
					$old_files = $post_data["old_image_url"];				
				}
				
				@$post_data['attach_label'] = trim(json_encode($post_data["attach_label"]),'\"');
				if(isset($_FILES["image_url"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("image_url");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$post_data['attachment'] = json_encode($old_files);

				if($_FILES["aadhar_card_att"]["name"] != "")
				{
					
					$file = $this->ERPfunction->upload_image("aadhar_card_att");	
					if(!empty($file))
					{
						$post_data['aadhar_card_att'] = $file;
					}					
				}else{
					$post_data['aadhar_card_att'] = $this->request->data['old_aadhar_card_att'];
				}
				
				if($_FILES["pan_card_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("pan_card_att");				
					if(!empty($file))
					{
						$post_data['pan_card_att'] = $file;
					}					
				}else{
					$post_data['pan_card_att'] = $this->request->data['old_pan_card_att'];
				}
				
				if($_FILES["driving_licence_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("driving_licence_att");
					if(!empty($file))
					{
						$post_data['driving_licence_att'] = $file;
					}					
				}else{
					$post_data['driving_licence_att'] = $this->request->data['old_driving_licence_att'];
				}
				
				if($_FILES["cancel_cheque_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("cancel_cheque_att");
					if(!empty($file))
					{
						$post_data['cancel_cheque_att'] = $file;
					}					
				}else{
					$post_data['cancel_cheque_att'] = $this->request->data['old_cancel_cheque_att'];
				}
				
				if($_FILES["resume_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("resume_att");	
					if(!empty($file))
					{
						$post_data['resume_att'] = $file;
					}					
				}else{
					$post_data['resume_att'] = $this->request->data['old_resume_att'];
				}
				
				if($_FILES["qualification_doc"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("qualification_doc");	
					if(!empty($file))
					{
						$post_data['qualification_doc'] = $file;
					}					
				}else{
					$post_data['qualification_doc'] = $this->request->data['old_qualification_doc'];
				}
				
				if($_FILES["other_doc"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("other_doc");
					if(!empty($file))
					{
						$post_data['other_doc'] = $file;
					}					
				}else{
					$post_data['other_doc'] = $this->request->data['old_other_doc'];
				}
				
				$post_data['last_edit']=date('Y-m-d H:i:s');
				$post_data['last_edit_by']=$this->request->session()->read('user_id');
				$employee_data = $erp_employee->patchEntity($employee_data,$post_data);
				//debug($employee_data);die;
				if($erp_employee->save($employee_data))
				{
					//$this->Flash->success(__('Record Update Successfully', null), 
							//'default', 
							//array('class' => 'success'));
					//$this->redirect(array("controller" => "Humanresource","action" => "emplyeelist"));
					echo "<script>window.close();</script>";
				}				
			}			
			else
			{
				
				$data = $this->request->data;
				//debug($data);die;
			//	$this->request->data['designation'] = $this->request->data['role'];
				$select =  $this->request->data['select'];
				
				$candidate_tabel = TableRegistry::get('erp_candidate');
				
				$candidate_status = $data['select'];
			
				/*Update Candidate Start */
				if($candidate_status == "SelectCandidate" )
				{
					if($this->request->data['candidate_id'] != null)
					{
						$user_id =$this->request->data['candidate_id'];
					}
				}
				/*Update Candidate Over */

				/* $check_email = $erp_employee->find()->where(['email_id'=>$this->request->data['email_id']]);		
				if(!$check_email->isEmpty()) */
				if(1 ==2)
				{
					$this->Flash->success(__('Dublicate Email id', null), 
							'default', 
							array('class' => 'success'));
						
				}
				else{
					
			$user_field = $erp_employee->newEntity();
			/* Assign next user identity number to new user */
			$new_number = $this->ERPfunction->get_user_identity_number();
			$this->request->data['user_identy_number'] = $new_number;
			
			/* Assign next user identity number to new user */
			@$this->request->data['attach_label'] = trim(json_encode($this->request->data["attach_label"]),'\"');
				$all_files = array();
				if(isset($_FILES["image_url"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("image_url");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$all_files[] = $attachment_file;
					}					
				}
				$this->request->data['attachment'] = json_encode($all_files);		
							

				$old_image_url = $this->request->data['old_image_url'];


				if($select == 'SelectCandidate')
				{
					
						$this->request->data['attachment'] = $old_image_url;
				}

			
			if($_FILES["aadhar_card_att"]["name"] != "")
				{
					
					$file = $this->ERPfunction->upload_image("aadhar_card_att");	
					if(!empty($file))
					{
						$this->request->data['aadhar_card_att'] = $file;
					}					
				}else{
					$this->request->data['aadhar_card_att'] = $this->request->data['old_aadhar_card_att'];
				}
				
				if($_FILES["pan_card_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("pan_card_att");				
					if(!empty($file))
					{
						$this->request->data['pan_card_att'] = $file;
					}					
				}else{
					$this->request->data['pan_card_att'] = $this->request->data['old_pan_card_att'];
				}
				
				if($_FILES["driving_licence_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("driving_licence_att");
					if(!empty($file))
					{
						$this->request->data['driving_licence_att'] = $file;
					}					
				}else{
					$this->request->data['driving_licence_att'] = $this->request->data['old_driving_licence_att'];
				}
				
				if($_FILES["cancel_cheque_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("cancel_cheque_att");
					if(!empty($file))
					{
						$this->request->data['cancel_cheque_att'] = $file;
					}					
				}else{
					$this->request->data['cancel_cheque_att'] = $this->request->data['old_cancel_cheque_att'];
				}
				
				if($_FILES["resume_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("resume_att");	
					if(!empty($file))
					{
						$this->request->data['resume_att'] = $file;
					}					
				}else{
					$this->request->data['resume_att'] = $this->request->data['old_resume_att'];
				}
				
				if($_FILES["qualification_doc"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("qualification_doc");	
					if(!empty($file))
					{
						$this->request->data['qualification_doc'] = $file;
					}					
				}else{
					$this->request->data['qualification_doc'] = $this->request->data['old_qualification_doc'];
				}
				
				if($_FILES["other_doc"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("other_doc");
					if(!empty($file))
					{
						$this->request->data['other_doc'] = $file;
					}					
				}else{
					$this->request->data['other_doc'] = $this->request->data['old_other_doc'];
				}
			
			$user_field=$erp_employee->patchEntity($user_field,$this->request->data);
		
			if($erp_employee->save($user_field))
			{
				if($candidate_status == 'SelectCandidate')
				{
					$row = $candidate_tabel -> find()->select(['user_id','status'])->where(['user_id '=>$user_id])->first()->toArray();
				
					$data = $candidate_tabel->get($row['user_id']);

					if($data['status'] == '0')
					{
						$data['status'] = '1';
					}

					 $candidate_tabel->save($data);
				}
				
				$this->Flash->success(__('Record Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
				$this->redirect(array("controller" => "Humanresource","action" => "emplyeelist"));
				}
			}
			}
					
					
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				
				if($user_action == 'edit')
			{
				$employee_data = $erp_employee->get($employee_id);			
			
			// $employee_data['employee_no'] = $employee_no;
			
			$this->set('employee_data',$employee_data);
				//debug($this->request->data);die;
				$emp_no = $this->request->data['employee_no'];
				$count = $this->ERPfunction->check_emp_no_exists($emp_no);
				if($count > 1) /* or >= 2 */
				{
					$new_emp = $this->ERPfunction->get_last_emp_no();
					$new_no = sprintf("%09d",  + $new_emp);
					$emp_no = 'YNEC/EMP/'.$new_no;
					$i = 1;
					$pass = 0;
					do{
						$count = $this->ERPfunction->check_emp_no_exists($emp_no);						
						if($count == 0)
						{
							$pass = 1;
						}
						else{ // emp_no + 1;							
							$auto_fld = $emp_no;
							$split = explode("/",$auto_fld);	
							$find = sizeof($split) - 1;
							$last_id = $split[$find];
							$last_number = (int) $last_id;
							$new_emp = $last_number + 1;
							$new_no = sprintf("%09d",  + $new_emp);
							$emp_no = 'YNEC/EMP/'.$new_no;
						}
						
					}while($pass != 1);		
					$this->request->data['employee_no'] = $emp_no;	
				}
				
				
				if(isset($_POST['password']) && $_POST['password']!="")
				$this->request->data['password']= md5($_POST['password']);
				$post_data = $this->request->data;

				$old_files = array();
				if(isset($post_data["old_image_url"]))
				{
					$old_files = $post_data["old_image_url"];				
				}
				
				@$post_data['attach_label'] = trim(json_encode($post_data["attach_label"]),'\"');
				if(isset($_FILES["image_url"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("image_url");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$post_data['attachment'] = json_encode($old_files);

				if($_FILES["aadhar_card_att"]["name"] != "")
				{
					
					$file = $this->ERPfunction->upload_image("aadhar_card_att");	
					if(!empty($file))
					{
						$post_data['aadhar_card_att'] = $file;
					}					
				}else{
					$post_data['aadhar_card_att'] = $this->request->data['old_aadhar_card_att'];
				}
				
				if($_FILES["pan_card_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("pan_card_att");				
					if(!empty($file))
					{
						$post_data['pan_card_att'] = $file;
					}					
				}else{
					$post_data['pan_card_att'] = $this->request->data['old_pan_card_att'];
				}
				
				if($_FILES["driving_licence_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("driving_licence_att");
					if(!empty($file))
					{
						$post_data['driving_licence_att'] = $file;
					}					
				}else{
					$post_data['driving_licence_att'] = $this->request->data['old_driving_licence_att'];
				}
				
				if($_FILES["cancel_cheque_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("cancel_cheque_att");
					if(!empty($file))
					{
						$post_data['cancel_cheque_att'] = $file;
					}					
				}else{
					$post_data['cancel_cheque_att'] = $this->request->data['old_cancel_cheque_att'];
				}
				
				if($_FILES["resume_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("resume_att");	
					if(!empty($file))
					{
						$post_data['resume_att'] = $file;
					}					
				}else{
					$post_data['resume_att'] = $this->request->data['old_resume_att'];
				}
				
				if($_FILES["qualification_doc"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("qualification_doc");	
					if(!empty($file))
					{
						$post_data['qualification_doc'] = $file;
					}					
				}else{
					$post_data['qualification_doc'] = $this->request->data['old_qualification_doc'];
				}
				
				if($_FILES["other_doc"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("other_doc");
					if(!empty($file))
					{
						$post_data['other_doc'] = $file;
					}					
				}else{
					$post_data['other_doc'] = $this->request->data['old_other_doc'];
				}
				
				$post_data['last_edit']=date('Y-m-d H:i:s');
				$post_data['last_edit_by']=$this->request->session()->read('user_id');
				$employee_data = $erp_employee->patchEntity($employee_data,$post_data);
				//debug($employee_data);die;
				if($erp_employee->save($employee_data))
				{
					//$this->Flash->success(__('Record Update Successfully', null), 
							//'default', 
							//array('class' => 'success'));
					//$this->redirect(array("controller" => "Humanresource","action" => "emplyeelist"));
					echo "<script>window.close();</script>";
				}				
			}			
			else
			{
				
				$data = $this->request->data;
				//debug($data);die;
			//	$this->request->data['designation'] = $this->request->data['role'];
				$select =  $this->request->data['select'];
				
				$candidate_tabel = TableRegistry::get('erp_candidate');
				
				$candidate_status = $data['select'];
			
				/*Update Candidate Start */
				if($candidate_status == "SelectCandidate" )
				{
					if($this->request->data['candidate_id'] != null)
					{
						$user_id =$this->request->data['candidate_id'];
					}
				}
				/*Update Candidate Over */

				/* $check_email = $erp_employee->find()->where(['email_id'=>$this->request->data['email_id']]);		
				if(!$check_email->isEmpty()) */
				if(1 ==2)
				{
					$this->Flash->success(__('Dublicate Email id', null), 
							'default', 
							array('class' => 'success'));
						
				}
				else{
					
			$user_field = $erp_employee->newEntity();
			/* Assign next user identity number to new user */
			$new_number = $this->ERPfunction->get_user_identity_number();
			$this->request->data['user_identy_number'] = $new_number;
			
			/* Assign next user identity number to new user */
			@$this->request->data['attach_label'] = trim(json_encode($this->request->data["attach_label"]),'\"');
				$all_files = array();
				if(isset($_FILES["image_url"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("image_url");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$all_files[] = $attachment_file;
					}					
				}
				$this->request->data['attachment'] = json_encode($all_files);		
							

				$old_image_url = $this->request->data['old_image_url'];


				if($select == 'SelectCandidate')
				{
					
						$this->request->data['attachment'] = $old_image_url;
				}

			
			if($_FILES["aadhar_card_att"]["name"] != "")
				{
					
					$file = $this->ERPfunction->upload_image("aadhar_card_att");	
					if(!empty($file))
					{
						$this->request->data['aadhar_card_att'] = $file;
					}					
				}else{
					$this->request->data['aadhar_card_att'] = $this->request->data['old_aadhar_card_att'];
				}
				
				if($_FILES["pan_card_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("pan_card_att");				
					if(!empty($file))
					{
						$this->request->data['pan_card_att'] = $file;
					}					
				}else{
					$this->request->data['pan_card_att'] = $this->request->data['old_pan_card_att'];
				}
				
				if($_FILES["driving_licence_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("driving_licence_att");
					if(!empty($file))
					{
						$this->request->data['driving_licence_att'] = $file;
					}					
				}else{
					$this->request->data['driving_licence_att'] = $this->request->data['old_driving_licence_att'];
				}
				
				if($_FILES["cancel_cheque_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("cancel_cheque_att");
					if(!empty($file))
					{
						$this->request->data['cancel_cheque_att'] = $file;
					}					
				}else{
					$this->request->data['cancel_cheque_att'] = $this->request->data['old_cancel_cheque_att'];
				}
				
				if($_FILES["resume_att"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("resume_att");	
					if(!empty($file))
					{
						$this->request->data['resume_att'] = $file;
					}					
				}else{
					$this->request->data['resume_att'] = $this->request->data['old_resume_att'];
				}
				
				if($_FILES["qualification_doc"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("qualification_doc");	
					if(!empty($file))
					{
						$this->request->data['qualification_doc'] = $file;
					}					
				}else{
					$this->request->data['qualification_doc'] = $this->request->data['old_qualification_doc'];
				}
				
				if($_FILES["other_doc"]["name"] != "")
				{
					$file = $this->ERPfunction->upload_image("other_doc");
					if(!empty($file))
					{
						$this->request->data['other_doc'] = $file;
					}					
				}else{
					$this->request->data['other_doc'] = $this->request->data['old_other_doc'];
				}
			
			$user_field=$erp_employee->patchEntity($user_field,$this->request->data);
		
			if($erp_employee->save($user_field))
			{
				if($candidate_status == 'SelectCandidate')
				{
					$row = $candidate_tabel -> find()->select(['user_id','status'])->where(['user_id '=>$user_id])->first()->toArray();
				
					$data = $candidate_tabel->get($row['user_id']);

					if($data['status'] == '0')
					{
						$data['status'] = '1';
					}

					 $candidate_tabel->save($data);
				}
				
				$this->Flash->success(__('Record Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
				$this->redirect(array("controller" => "Humanresource","action" => "emplyeelist"));
				}
			}
			}
				
			}
					
		}		
    }

	public function personaleditemployee($employee_id=Null) {
		$new_emp = $this->ERPfunction->get_last_emp_no();
		// $new_no = sprintf("%09d",  + $new_emp);
		// $employee_no = 'YNEC/EMP/'.$new_no;
		
		$erp_employee = TableRegistry::get('erp_users'); 

		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);

		$role = $this->role;
		if($role == "erpoperator") {
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else {
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);

		/*Candidate*/
			$candidate_tabel  = TableRegistry::get('erp_candidate');
			$candidate = $candidate_tabel ->find()->where(['status'=>'0'])->hydrate(false)->toArray();
			$this->set('candidate',$candidate);
		/*Candidate*/

		$designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations);

		if(isset($employee_id)) {
		   	
			$user_action = 'edit';
			
			$employee_data = $erp_employee->get($employee_id);			
			
			 //$employee_data['employee_no'] = $employee_no;
			
			$this->set('id',$employee_id);
			$this->set('employee_data',$employee_data);
			$this->set('form_header','Edit Personal');
			$this->set('button_text','Update Personal');			
			
		}		
	
		$this->set('user_action',$user_action);
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		if($this->request->is('post')) {	
			
			$this->set('employee_data',$this->request->data);			
			$this->request->data['date_of_joining']=$this->ERPfunction->set_date($this->request->data['date_of_joining']);
			$this->request->data['date_of_birth']=date('Y-m-d',strtotime($this->request->data['date_of_birth']));
			$this->request->data['pf_ref_no']=$this->request->data['pf_ref_no'];
			// $this->request->data['as_on_date']=$this->ERPfunction->set_date($this->request->data['as_on_date']);
			// $this->request->data['extra_payment']= implode(',',$this->request->data['extra_payment']);
			// $this->request->data['incentive_includes']= implode(',',$this->request->data['incentive_includes']);
			//debug($this->request->data['date_of_birth']);die;
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;
			$image=$this->ERPfunction->upload_image('user_image_url',$this->request->data['old_user_image']);
			$this->request->data['image_url']=$image;
			if($this->request->data['food_allowance'] == "fixed") {
				$this->request->data['food_allowance'] = $this->request->data['food_fixed'] ;
			}
			
			if($this->request->data['acco_allowance'] == "fixed") {
				$this->request->data['acco_allowance'] = $this->request->data['acc_fixed'] ;
			}
			if($this->request->data['trans_allowance'] == "fixed") {
				$this->request->data['trans_allowance'] = $this->request->data['trans_fixed'] ;
			}
			if($this->request->data['mobile_allowance'] == "fixed") {
				$this->request->data['mobile_allowance'] = $this->request->data['mobile_fixed'] ;
			}
			
			if(isset($_FILES['user_image_url']) || isset($_FILES['image_url']) || isset($_FILES["aadhar_card_att"]) || isset($_FILES["pan_card_att"]) || isset($_FILES["driving_licence_att"]) || isset($_FILES["cancel_cheque_att"]) || isset($_FILES["resume_att"]) || isset($_FILES["qualification_doc"]) || isset($_FILES["other_doc"]))
			{	
				$ext1=1;
				$ext2=1;
				$ext3=1;
				$ext4=1;
				$ext5=1;
				$ext6=1;
				$ext7=1;
				$ext8=1;
				$ext9=1;
				
				if(isset($_FILES['image_url'])){
					$file =$_FILES['image_url']["name"];
					$size = count($file);
					for($i=0;$i<$size;$i++) {
						$parts = pathinfo($_FILES['image_url']['name'][$i]);
					}
					$ext1 = $this->ERPfunction->check_valid_extension($parts['basename']);
				}
				if(isset($_FILES['user_image_url'])){
					$user_image_url =$_FILES['user_image_url']['name'];
					$ext2 = $this->ERPfunction->check_valid_extension($user_image_url);	
				}
				if(isset($_FILES['aadhar_card_att'])){
					$aadhar_card_att =$_FILES['aadhar_card_att']['name'];
					$ext3 = $this->ERPfunction->check_valid_extension($aadhar_card_att);	
				}
				if(isset($_FILES['pan_card_att'])){
					$pan_card_att =$_FILES['pan_card_att']['name'];
					$ext4 = $this->ERPfunction->check_valid_extension($pan_card_att);	
				}
				if(isset($_FILES['driving_licence_att'])){
					$driving_licence_att =$_FILES['driving_licence_att']['name'];
					$ext5 = $this->ERPfunction->check_valid_extension($driving_licence_att);	
				}
				if(isset($_FILES['cancel_cheque_att'])){
					$cancel_cheque_att =$_FILES['cancel_cheque_att']['name'];
					$ext6 = $this->ERPfunction->check_valid_extension($cancel_cheque_att);	
				}
				if(isset($_FILES['resume_att'])){
					$resume_att =$_FILES['resume_att']['name'];
					$ext7 = $this->ERPfunction->check_valid_extension($resume_att);	
				}
				if(isset($_FILES['qualification_doc'])){
					$qualification_doc =$_FILES['qualification_doc']['name'];
					$ext8 = $this->ERPfunction->check_valid_extension($qualification_doc);	
				}
				if(isset($_FILES['other_doc'])){
					$other_doc =$_FILES['other_doc']['name'];
					$ext9 = $this->ERPfunction->check_valid_extension($other_doc);	
				}
				
				if($ext1 != 0 && $ext2 != 0 && $ext3 != 0 && $ext4 != 0 && $ext5 != 0 && $ext6 != 0 && $ext7 != 0 && $ext8!= 0 && $ext9!= 0 ) {
					if($user_action == 'edit') {
				$employee_data = $erp_employee->get($employee_id);			
			
				// $employee_data['employee_no'] = $employee_no;
				
				$this->set('employee_data',$employee_data);
				//debug($this->request->data);die;
				$emp_no = $this->request->data['employee_no'];
				$count = $this->ERPfunction->check_emp_no_exists($emp_no);
				if($count > 1) /* or >= 2 */ {
					$new_emp = $this->ERPfunction->get_last_emp_no();
					$new_no = sprintf("%09d",  + $new_emp);
					$emp_no = 'YNEC/EMP/'.$new_no;
					$i = 1;
					$pass = 0;
					do {
						$count = $this->ERPfunction->check_emp_no_exists($emp_no);						
						if($count == 0) {
							$pass = 1;
						}
						else { // emp_no + 1;							
							$auto_fld = $emp_no;
							$split = explode("/",$auto_fld);	
							$find = sizeof($split) - 1;
							$last_id = $split[$find];
							$last_number = (int) $last_id;
							$new_emp = $last_number + 1;
							$new_no = sprintf("%09d",  + $new_emp);
							$emp_no = 'YNEC/EMP/'.$new_no;
						}
						
					}while($pass != 1);		
					$this->request->data['employee_no'] = $emp_no;	
				}
				
				
				if(isset($_POST['password']) && $_POST['password']!="")
				$this->request->data['password']= md5($_POST['password']);
				$post_data = $this->request->data;

				$old_files = array();
				if(isset($post_data["old_image_url"])) {
					$old_files = $post_data["old_image_url"];				
				}
				
				@$post_data['attach_label'] = trim(json_encode($post_data["attach_label"]),'\"');
				if(isset($_FILES["image_url"]["name"])) {
					$file = $this->ERPfunction->upload_file("image_url");	
					if(!empty($file))
					foreach($file as $attachment_file) {
						$old_files[] = $attachment_file;
					}					
				}
				$post_data['attachment'] = json_encode($old_files);

				if($_FILES["aadhar_card_att"]["name"] != "") {
					
					$file = $this->ERPfunction->upload_image("aadhar_card_att");	
					if(!empty($file)) {
						$post_data['aadhar_card_att'] = $file;
					}					
				}else {
					$post_data['aadhar_card_att'] = $this->request->data['old_aadhar_card_att'];
				}
				
				if($_FILES["pan_card_att"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("pan_card_att");				
					if(!empty($file)) {
						$post_data['pan_card_att'] = $file;
					}					
				}else {
					$post_data['pan_card_att'] = $this->request->data['old_pan_card_att'];
				}
				
				if($_FILES["driving_licence_att"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("driving_licence_att");
					if(!empty($file)) {
						$post_data['driving_licence_att'] = $file;
					}					
				}else {
					$post_data['driving_licence_att'] = $this->request->data['old_driving_licence_att'];
				}
				
				if($_FILES["cancel_cheque_att"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("cancel_cheque_att");
					if(!empty($file)) {
						$post_data['cancel_cheque_att'] = $file;
					}					
				}else {
					$post_data['cancel_cheque_att'] = $this->request->data['old_cancel_cheque_att'];
				}
				
				if($_FILES["resume_att"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("resume_att");	
					if(!empty($file)) {
						$post_data['resume_att'] = $file;
					}					
				}else {
					$post_data['resume_att'] = $this->request->data['old_resume_att'];
				}
				
				if($_FILES["qualification_doc"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("qualification_doc");	
					if(!empty($file)) {
						$post_data['qualification_doc'] = $file;
					}					
				}else {
					$post_data['qualification_doc'] = $this->request->data['old_qualification_doc'];
				}
				
				if($_FILES["other_doc"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("other_doc");
					if(!empty($file)) {
						$post_data['other_doc'] = $file;
					}					
				}else {
					$post_data['other_doc'] = $this->request->data['old_other_doc'];
				}
				
				$post_data['last_edit']=date('Y-m-d H:i:s');
				$post_data['last_edit_by']=$this->request->session()->read('user_id');
				$employee_data = $erp_employee->patchEntity($employee_data,$post_data);
				//debug($employee_data);die;
				if($erp_employee->save($employee_data)) {
					$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
					$this->redirect(array("controller" => "Humanresource","action" => "personnel"));
				}				
			}		
		
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				if($user_action == 'edit') {
				$employee_data = $erp_employee->get($employee_id);			
			
				// $employee_data['employee_no'] = $employee_no;
				
				$this->set('employee_data',$employee_data);
				//debug($this->request->data);die;
				$emp_no = $this->request->data['employee_no'];
				$count = $this->ERPfunction->check_emp_no_exists($emp_no);
				if($count > 1) /* or >= 2 */ {
					$new_emp = $this->ERPfunction->get_last_emp_no();
					$new_no = sprintf("%09d",  + $new_emp);
					$emp_no = 'YNEC/EMP/'.$new_no;
					$i = 1;
					$pass = 0;
					do {
						$count = $this->ERPfunction->check_emp_no_exists($emp_no);						
						if($count == 0) {
							$pass = 1;
						}
						else { // emp_no + 1;							
							$auto_fld = $emp_no;
							$split = explode("/",$auto_fld);	
							$find = sizeof($split) - 1;
							$last_id = $split[$find];
							$last_number = (int) $last_id;
							$new_emp = $last_number + 1;
							$new_no = sprintf("%09d",  + $new_emp);
							$emp_no = 'YNEC/EMP/'.$new_no;
						}
						
					}while($pass != 1);		
					$this->request->data['employee_no'] = $emp_no;	
				}
				
				
				if(isset($_POST['password']) && $_POST['password']!="")
				$this->request->data['password']= md5($_POST['password']);
				$post_data = $this->request->data;

				$old_files = array();
				if(isset($post_data["old_image_url"])) {
					$old_files = $post_data["old_image_url"];				
				}
				
				@$post_data['attach_label'] = trim(json_encode($post_data["attach_label"]),'\"');
				if(isset($_FILES["image_url"]["name"])) {
					$file = $this->ERPfunction->upload_file("image_url");	
					if(!empty($file))
					foreach($file as $attachment_file) {
						$old_files[] = $attachment_file;
					}					
				}
				$post_data['attachment'] = json_encode($old_files);

				if($_FILES["aadhar_card_att"]["name"] != "") {
					
					$file = $this->ERPfunction->upload_image("aadhar_card_att");	
					if(!empty($file)) {
						$post_data['aadhar_card_att'] = $file;
					}					
				}else {
					$post_data['aadhar_card_att'] = $this->request->data['old_aadhar_card_att'];
				}
				
				if($_FILES["pan_card_att"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("pan_card_att");				
					if(!empty($file)) {
						$post_data['pan_card_att'] = $file;
					}					
				}else {
					$post_data['pan_card_att'] = $this->request->data['old_pan_card_att'];
				}
				
				if($_FILES["driving_licence_att"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("driving_licence_att");
					if(!empty($file)) {
						$post_data['driving_licence_att'] = $file;
					}					
				}else {
					$post_data['driving_licence_att'] = $this->request->data['old_driving_licence_att'];
				}
				
				if($_FILES["cancel_cheque_att"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("cancel_cheque_att");
					if(!empty($file)) {
						$post_data['cancel_cheque_att'] = $file;
					}					
				}else {
					$post_data['cancel_cheque_att'] = $this->request->data['old_cancel_cheque_att'];
				}
				
				if($_FILES["resume_att"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("resume_att");	
					if(!empty($file)) {
						$post_data['resume_att'] = $file;
					}					
				}else {
					$post_data['resume_att'] = $this->request->data['old_resume_att'];
				}
				
				if($_FILES["qualification_doc"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("qualification_doc");	
					if(!empty($file)) {
						$post_data['qualification_doc'] = $file;
					}					
				}else {
					$post_data['qualification_doc'] = $this->request->data['old_qualification_doc'];
				}
				
				if($_FILES["other_doc"]["name"] != "") {
					$file = $this->ERPfunction->upload_image("other_doc");
					if(!empty($file)) {
						$post_data['other_doc'] = $file;
					}					
				}else {
					$post_data['other_doc'] = $this->request->data['old_other_doc'];
				}
				
				$post_data['last_edit']=date('Y-m-d H:i:s');
				$post_data['last_edit_by']=$this->request->session()->read('user_id');
				$employee_data = $erp_employee->patchEntity($employee_data,$post_data);
				//debug($employee_data);die;
				if($erp_employee->save($employee_data)) {
					$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
					$this->redirect(array("controller" => "Humanresource","action" => "personnel"));
				}				
			}		
		
			}
			
			}		
   	}
	
	public function viewemployee($employee_id)
	{

		$designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations);

		$last_emp = $this->ERPfunction->getlast_employeeid();
		$new_no = sprintf("%09d",  + $last_emp + 1);
		$employee_no = 'YNEC/EMP/'.$new_no;
		// $erp_employee = TableRegistry::get('erp_employee'); 
		$erp_employee = TableRegistry::get('erp_users'); 
		 $table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		//$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);


		
		if(isset($employee_id))
		{
		   	
			$user_action = 'edit';
			$employee_data = $erp_employee->get($employee_id);	
			
			//$employee_rejoin = $erp_employee->find()->select(['working_status','rejoin_date'])->where(['working_status'=>1,'non_working_id'=>$employee_id])->hydrate(false)->toArray();
			
			//debug($employee_rejoin);die;		
			
			//$employee_data['employee_no'] = $employee_no;
			//$this->set('employee_rejoin',$employee_rejoin);
			$this->set('employee_data',$employee_data);
			$this->set('form_header','View Employee');
			$this->set('button_text','Update Employee');			
			
		}
		$this->set('id',$employee_id);
		$this->set('user_action',$user_action);
		
	}
	
	public function delete($user_id)
	{
		$users_table = TableRegistry::get('erp_users'); 
		$this->request->is(['post','delete']);

		$user_data =$users_table->get($user_id);

		if($users_table->delete($user_data))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'index']);
	}
	
	public function transferemployee()
    {
		$erp_employee_transfer_history = TableRegistry::get('erp_employee_transfer_history'); 
		$history_data['employee_id']=$this->request->data['user_id'];
		$history_data['old_project']=$this->ERPfunction->get_employee_project($this->request->data['user_id']);
		$history_data['new_project']=$this->request->data['transfer_to'];
		$history_data['transfer_date']=date('Y-m-d');; 
		$history_data['accepted']=1;
		$history_data['created_date']=date('Y-m-d H:i:s');
		$history_data['created_by']=$this->request->session()->read('user_id');
		$user_field = $erp_employee_transfer_history->newEntity();			
		$user_field=$erp_employee_transfer_history->patchEntity($user_field,$history_data);
		if($erp_employee_transfer_history->save($user_field))
		{
			$this->ERPfunction->update_employeeproject($this->request->data['user_id'],$this->request->data['transfer_to']);
			$this->Flash->success(__('Employee Transfer Successfully', null), 
                            'default', 
                             array('class' => 'success'));
		}
		return $this->redirect(['action'=>'index']);
    }
	
	public function resignemployee()
    {
		// $erp_employee = TableRegistry::get('erp_employee'); 
		$erp_employee = TableRegistry::get('erp_users'); 
		$employee_data = $erp_employee->get($this->request->data('user_id'));
		$post_data['is_resign']=1;
		$post_data['working_status'] = 0;	
		//$post_data['non_working_id'] = $this->request->data('user_id');
		$post_data['resign_date']= $this->ERPfunction->set_date($this->request->data('resign_date'));

		$post_data['resign_reason']=$this->request->data('resign_reason');
		$employee_data = $erp_employee->patchEntity($employee_data,$post_data);
		$erp_employee->save($employee_data);
		
		if($erp_employee->save($employee_data))
		{
			
			$this->Flash->success(__('Employee Resign Successfully', null), 
                            'default', 
                             array('class' => 'success'));
		}
		return $this->redirect(['action'=>'emplyeelist']);
    }
	public function leavesheet($leave_id=Null)
    {
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		$erp_employee = TableRegistry::get('erp_users'); 
		$employees = $erp_employee->find();
		$this->set('eployees',$employees);
		$erp_leavesheet = TableRegistry::get('erp_leavesheet'); 
		
		/* $designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations); */
		 $table_category=TableRegistry::get('erp_category_master');
		$designations=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designations',$designations);
		
		$this->set('months',$this->ERPfunction->month_names());
		if(isset($leave_id))
		{
			$user_action = 'edit';
			$employee_data = $erp_leavesheet->get($leave_id);
			$this->set('employee_data',$employee_data);
			$this->set('form_header','Edit Leave Sheet');
			$this->set('button_text','Update Leave Sheet');
			
		}
		else
		{
			$user_action = 'insert';
			$this->set('form_header','Add Leave Sheet');
			$this->set('button_text','Add Leave Sheet');
		}
		$this->set('user_action',$user_action);
		if($this->request->is('post'))
		{			
			
			if($user_action == 'edit')
			{				
				$post_data = $this->request->data;
				$post_data['last_edit_by'] = $this->request->session()->read('user_id');
				$post_data['last_edited'] = date('Y-m-d H:i:s');
				$employee_data = $erp_leavesheet->patchEntity($employee_data,$post_data);
				if($erp_leavesheet->save($employee_data))
				{
					$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				}
			}
			else
			{
				$user_field = $erp_leavesheet->newEntity();
				$user_field['created_by']=$this->request->session()->read('user_id');
				$user_field['last_edit_by'] = $this->request->session()->read('user_id');
				$user_field['last_edited'] = date('Y-m-d H:i:s');
			
				$user_field=$erp_leavesheet->patchEntity($user_field,$this->request->data);
			
				if($erp_leavesheet->save($user_field))
				{
					$this->Flash->success(__('Record Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
				}
				
			}
			$this->redirect(array("controller" => "Humanresource","action" => "leavesummary"));		
		}		
    }
	public function viewexgraciarecord($user_id,$start_month,$end_month)
	{
		
		$emp_tbl = TableRegistry::get("erp_users");
		$find =  $emp_tbl->get($user_id);
		$this->set('records',$find);

	   $data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => "",'user_id'=>$user_id]);
	   //debug($data);die;
	   $data = $data->select(["name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();

	   

		$this->set('employees1',$data);
		
		$start_year = date("Y",strtotime($start_month));
		$end_year = date("Y",strtotime($end_month));
		
		$this->set('current_year',$start_year);
		$this->set('previous_year',$end_year);
	/*	$start_month = date("m",strtotime($start_month));
		$end_month = date("m",strtotime($end_month));*/
		
			$end= new DateTime($start_year.'-03-31');
			 $start = new DateTime($end_year.'-04-01');
		
			

	/*	if($start_month > '03' || $end_month > '03' )
		{
			
				$previous_year = ($year); //current year
				$current_year = ($year + 1); //next year
			
				$start = new DateTime($previous_year.'-04-01');
				$end = new DateTime($current_year.'-03-31');
			

		}else{
			$previous_year = ($year - 1); //current year
			$current_year = ($year); //next year
			
			$start = new DateTime($previous_year.'-04-01');
			$end = new DateTime($current_year.'-03-31');
		}*/

		$interval = new DateInterval('P1M');
		$period = new DatePeriod($start, $interval, $end);
		// debug($period);die;
		$financial_data = array();
		foreach ($period as $dt) {
			// echo $dt->format('m Y') . PHP_EOL;
			// $financial_data[$dt->format('Y')][$dt->format('m')];
			$financial_data['month'][] = $dt->format('m');
			$financial_data['year'][] = $dt->format('Y');
		}
		// debug($financial_data);die;
		

		$this->set('financial_data',$financial_data);

		$exgrica_tbl = TableRegistry::get('exgrica_record');

		$previous_month = date('m',strtotime($end_month));
		$previous_year = date('Y',strtotime($end_month));
	
		$current_month = date('m',strtotime($start_month));
		$current_year = date('Y',strtotime($start_month));
	

		$exgrica_record = $exgrica_tbl->find()->where(["AND"=>[["user_id"=>$user_id],["OR"=>[["month >"=>$previous_month,"Year"=>$previous_year],["month <"=>$current_month,"Year"=>$current_year]]]]])->hydrate(false)->toArray();
		//debug($exgrica_record);die;

		//$exgrica_record = $exgrica_tbl->find()->where(["user_id"=>$user_id,"bonus_date <="=>$end_month,'bonus_date >='=>$start_month])->hydrate(false)->toArray();
		
		//debug($exgrica_record);die;
		$salary_data = array();
		$total_earning = 0;
		foreach($exgrica_record as $record)
		{
			$salary_data[$record['Year']][$record['month']] = $record['bonus'];
			$total_earning += $record['bonus'];
		}
		//debug($salary_data);die;
		$this->set('salary_data',$salary_data);
		$this->set('total_earning',$total_earning);

	}

	/*Personnel Information*/
	public function personnel($projects_id=null)
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'Humanresource';
			$back_page = 'index';
		}
		
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		/* $users_table = TableRegistry::get('erp_employee');  */
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set("projects",$projects);
		
		$users_table = TableRegistry::get('erp_users'); 
		$name_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0,"pay_type"=>"employee"]);
		$this->set('name_list',$name_list);
		$users_table = TableRegistry::get("erp_users");
	
		$this->set('role',$this->role);
		
		if($projects_id!=null){
			
			$or1 = array();		
			
			$or1["employee_at"] = ($projects_id!=null)?$projects_id:NULL;
			$or1["employee_no !="] = "";
			$or1["is_resign !="] = 0;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
			
			$user_list = $users_table->find()->where([$or1]);
			
			$this->set('user_list',$user_list);			
			
		}
		else
		{
				$users_table = TableRegistry::get('erp_users'); 
				$user_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0,"pay_type"=>"employee"]);
				$this->set('user_list',$user_list);
		}
		if($this->request->is("post"))
		{	
				$post = $this->request->data;	
				// debug($post);die;
				$or = array();				
				
				$or["user_id IN"] = (!empty($post["user_id"]) && $post["user_id"][0] != "All" )?$post["user_id"]:NULL;
				$or["designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
				$or["pay_type IN"] = (!empty($post["pay_type"]) && $post["pay_type"][0] != "All" )?$post["pay_type"]:NULL;
				$or["employee_at IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["employee_no"] = (!empty($post["employee_no"]))?$post["employee_no"]:NULL;
				$or["mobile_no"] = (!empty($post["mobile_no"]))?$post["mobile_no"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				$user_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0,$or]);
				$this->set('user_list',$user_list);
		}

	}
	public function viewPersonnel($employee_id)
	{
		$last_emp = $this->ERPfunction->getlast_employeeid();
		$new_no = sprintf("%09d",  + $last_emp + 1);
		$employee_no = 'YNEC/EMP/'.$new_no;
		// $erp_employee = TableRegistry::get('erp_employee'); 
		$erp_employee = TableRegistry::get('erp_users'); 
		 $table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		//$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		if(isset($employee_id))
		{
		   	
			$user_action = 'edit';
			$employee_data = $erp_employee->get($employee_id);			
			
			//$employee_data['employee_no'] = $employee_no;
			
			$this->set('employee_data',$employee_data);
			$this->set('form_header','View Personnel Information');
			$this->set('button_text','Update Employee');			
			
		}
		$this->set('id',$employee_id);
		$this->set('user_action',$user_action);
			
	}


	/*End Personal Information*/
	public function viewleavesheet($leave_id=Null)
    {
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		$erp_employee = TableRegistry::get('erp_users'); 
		$employees = $erp_employee->find();
		$this->set('eployees',$employees);
		$erp_leavesheet = TableRegistry::get('erp_leavesheet'); 
		
		$designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations);
		
		$this->set('months',$this->ERPfunction->month_names());
		
		if(isset($leave_id))
		{
			$user_action = 'edit';
			$employee_data = $erp_leavesheet->get($leave_id);
			$this->set('employee_data',$employee_data);
			$this->set('form_header','View Leave Sheet');
			$this->set('button_text','Update Leave Sheet');
		}		
				
    }
	
	public function leavesummary()
    {
			
		$designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations);
		$role = $this->role;
		$this->set('months',$this->ERPfunction->month_names());
		
		$erp_leavesheet = TableRegistry::get('erp_leavesheet');		
		$projects_ids = $this->Usermanage->users_project($this->user_id);
	
		if(!empty($projects_ids)){
			foreach($projects_ids as $pid)
			{
				$project_names[] = $this->ERPfunction->get_projectname($pid);
			}
		}
		
		if($role == 'projectdirector' ){ 
			if(!empty($projects_ids)){
				$employee_leaves = $erp_leavesheet->find()->where(["employee_at IN"=>$project_names]);	
			}else{
				$employee_leaves=array();
			}
		}else{
			 $employee_leaves = $erp_leavesheet->find();
		}
		
		$this->set('employee_leaves',$employee_leaves);

		if($this->request->is("post"))
		{
			$post = $this->request->data;
			// debug($post);die;
			
			$or = array();				
			
			$or["designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
			$or["full_name LIKE"] = (!empty($post["full_name"]))?"%{$post["full_name"]}%":NULL;
			$or["month IN"] = (!empty($post["month"]) && $post["month"][0] != "All")?$post["month"]:NULL;
			$or["year in"] = (!empty($post["year"]) && $post["year"][0] != "All" )?$post["year"]:NULL;
			
			if($role == 'projectdirector' )
			{ 
				$or["employee_at IN"]=$project_names;
			}
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			
			$employee_leaves = $erp_leavesheet->find()->where([$or])->hydrate(false)->toArray();
			
			$this->set("employee_leaves",$employee_leaves);
		}
		
    }
	public function deleteleave($leave_id)
    {
		$erp_leavesheet = TableRegistry::get('erp_leavesheet'); 
		
		$user_data =$erp_leavesheet->get($leave_id);

		if($erp_leavesheet->delete($user_data))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'leavesummary']);
		
    }

	public function salaryslip()
	{
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set("projects",$projects);
		$role = $this->role;
		$this->set('role',$role);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data["go"]))
			{
				$request = $this->request->data;
				$m = date("m",strtotime($request['date']));
				$y = date("Y",strtotime($request['date']));
				$custom_holiday = $this->ERPfunction->total_sundays($m,$y);
				$this->set('custom_holiday',$custom_holiday);
				$or = array();
		
				$or["erp_users.employee_at"] = (!empty($request["project_id"]) && $request["project_id"] != "All")?$request["project_id"]:NULL;
				$or["erp_users.pay_type IN"] = (!empty($request["pay_type"]) && $request["pay_type"][0] != "All")?$request["pay_type"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				//debug($or);die;
				$date = $this->request->data["date"];
				
				$month = date("n",strtotime($date));
				$year =  date("Y",strtotime($date));
				$att_detail_tbl = TableRegistry::get("erp_attendance_detail");
				$user_tbl = TableRegistry::get("erp_users");
				$users = $att_detail_tbl->find()->where(["month"=>$month,"year"=>$year,"approved"=>1])->select($att_detail_tbl);
				$users = $users->innerjoin(
						["erp_users"=>"erp_users"],
						["erp_users.user_id = erp_attendance_detail.user_id"]					
							)->where([$or])->hydrate(false)->toArray();			
				$user_data = array();
				
				$salary_tbl = TableRegistry::get("erp_salary_slip");			
				
				if(!empty($users))
				{
					foreach($users as $user)
					{
						$check = $salary_tbl->find()->where(["user_id"=>$user["user_id"],"month"=>$month,"year"=>$year])->count();
						if($check == 0)
						{
							$ids[] = $user["user_id"];
						}
					}		
					if(!empty($ids))
					{
						$user_tbl = TableRegistry::get("erp_users");
						$user_data = $user_tbl->find()->where(["user_id IN"=>$ids])->hydrate(false)->toArray();
					}				
				}
				$this->set("users",$user_data);
				$this->set("month",$month);
				$this->set("year",$year);
				
			}
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));

				$filename = "payslip.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				
				$this->set("rows",$rows);
				$this->render("payslippdf");
			}
		}		
	}
	
	public function generatesalaryslip($user_id,$month,$year,$custom_holiday)
	{
		$att_detail_tbl = TableRegistry::get("erp_attendance_detail");
		$att_detail = $att_detail_tbl->find()->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year,"approved"=>1])->hydrate(false)->toArray();			
		
		$user_tbl = TableRegistry::get("erp_users");
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$date = "01-".$month."-".$year;
		$total_days = date("t",strtotime($date));	
		
		$pay_change = ($user_data[0]["is_pay_structure_change"] == 1) ? true : false ; 
		$change_date = $user_data[0]["change_date"];
		if($pay_change)
		{
			$change_month = date("n",strtotime($change_date));
			$change_date = $change_date->format("Y-m-d");
			$curr_date_stamp = strtotime($date);
			$change_date_stamp =  strtotime($change_date);
			// if($month != $change_month) //NO NEED TO CHECK YEAR. LOAD OLD DATA FROM HISTORY TABLE
			if($curr_date_stamp < $change_date_stamp) //NO NEED TO CHECK YEAR. LOAD OLD DATA FROM HISTORY TABLE
			{
				$check_ch_date = $change_date;//->format("Y-m-d");
				$h_tbl = TableRegistry::get("erp_users_history");
				$user_data = $h_tbl->find()->where(["user_id"=>$user_id,"change_date"=>$check_ch_date])->hydrate(false)->toArray();
			}
		}
		
		$this->set("pay_change",$pay_change);
		$this->set("user_id",$user_id);
		$this->set("change_date",$change_date);
		$this->set("user_data",$user_data[0]);
		$this->set("att_detail",$att_detail[0]);
		$this->set("month",$month);
		$this->set("year",$year);	
		$this->set("custom_holiday",$custom_holiday);	
		$this->set("user_action","insert");	
		$this->set("button_text","Generate Salary Slip");	
		$this->set("total_days",$total_days);
		
		$month_all_day = $this->ERPfunction->total_day_of_month($month,$year);
		$working_days = $month_all_day - $custom_holiday;
		$paid_days = $att_detail[0]["payable_days"] * $working_days / $month_all_day;
		$paid_days = $x = floor($paid_days * 2) / 2;
		$paid_days = number_format((float)$paid_days,2, '.', '');
		
		
		// $basic_pay_amount = ($user_data[0]["basic_salary"]/$total_days) * $att_detail[0]["payable_days"];
		// $basic_pay_amount = round($basic_pay_amount);
		
		$basic_pay_amount = ($user_data[0]["basic_salary"]/$working_days) * $paid_days;
		$basic_pay_amount = round($basic_pay_amount);
		
		// $da_amount = ($user_data[0]["da"]/$total_days) * $att_detail[0]["payable_days"];
		// $da_amount = round($da_amount);
		
		$da_amount = ($user_data[0]["da"]/$working_days) * $paid_days;
		$da_amount = round($da_amount);
		
		// $hra_amount = ($user_data[0]["hra"]/$total_days) * $att_detail[0]["payable_days"];
		// $hra_amount = round($hra_amount);
		
		$hra_amount = ($user_data[0]["hra"]/$working_days) * $paid_days;
		$hra_amount = round($hra_amount);
		
		//$medical_amount = ($user_data[0]["medical_allowance"]/$total_days) * $att_detail[0]["payable_days"];
		$medical_amount = ($user_data[0]["medical_allowance"]/$working_days) * $paid_days;
		$medical_amount = round($medical_amount);
		// $medical_amount = $user_data[0]["medical_allowance"];
		// $medical_amount = round($medical_amount);
		
		// debug($user_data[0]);die;
		//$other_allowance = ($user_data[0]["other_allowance"]/$total_days) * $att_detail[0]["payable_days"];
		$other_allowance = ($user_data[0]["other_allowance"]/$working_days) * $paid_days;
		$other_allowance = round($other_allowance);
		
		####################################################
		$food_text = 0;
		if($user_data[0]["food_allowance"] == "company_provided")
		{
			$food_allowance = 0;
			//$food_allowance_amount = ($food_allowance/$total_days) * $att_detail[0]["payable_days"];
			$food_allowance_amount = ($food_allowance/$working_days) * $paid_days;
		}
		else if($user_data[0]["food_allowance"] == "bill_paid")
		{
			$food_text = 1;
			$food_allowance=0;
			// $food_allowance_amount = 0;
			$food_allowance = $user_data[0]["food_bill_paid"];
			//$food_allowance_amount =  ($food_allowance/$total_days) * $att_detail[0]["payable_days"];
			$food_allowance_amount =  ($food_allowance/$working_days) * $paid_days;
		}
		else
		{
			$food_text = 0;
			$food_allowance = $user_data[0]["food_allowance"];
			//$food_allowance_amount = ($food_allowance/$total_days) * $att_detail[0]["payable_days"];
			$food_allowance_amount = ($food_allowance/$working_days) * $paid_days;
		}
		$food_allowance_amount = round($food_allowance_amount);
		
		#########################################################
		
		$trans_text = 0;
		if($user_data[0]["trans_allowance"] == "company_provided")
		{
			$trans_allowance = 0;
			//$trans_allowance_amount = ($trans_allowance/$total_days) * $att_detail[0]["payable_days"];
			$trans_allowance_amount = ($trans_allowance/$working_days) * $paid_days;
		}
		else if($user_data[0]["trans_allowance"] == "bill_paid")
		{
			$trans_text = 1;
			$trans_allowance=0;
			// $trans_allowance_amount = 0;
			$trans_allowance = $user_data[0]["trans_bill_paid"];
			//$trans_allowance_amount = ($trans_allowance/$total_days) * $att_detail[0]["payable_days"];
			$trans_allowance_amount = ($trans_allowance/$working_days) * $paid_days;
		}
		else
		{
			$trans_text = 0;
			$trans_allowance = $user_data[0]["trans_allowance"];
			//$trans_allowance_amount = ($trans_allowance/$total_days) * $att_detail[0]["payable_days"];
			$trans_allowance_amount = ($trans_allowance/$working_days) * $paid_days;
		}
		$trans_allowance_amount = round($trans_allowance_amount);
		
		#########################################################
		
		$acco_text = 0;
		if($user_data[0]["acco_allowance"] == "company_provided")
		{
			$acco_allowance = 0;
		}
		else if($user_data[0]["acco_allowance"] == "bill_paid")
		{
			$acco_text = 1;
			// $acco_allowance=0;
			$acco_allowance = $user_data[0]["acc_bill_paid"];			
			//$acco_allowance = ($acco_allowance/$total_days) * $att_detail[0]["payable_days"];	
			$acco_allowance = ($acco_allowance/$working_days) * $paid_days;	
			$acco_allowance = round($acco_allowance);
		}
		else
		{
			$acco_text = 0;
			$acco_allowance = $user_data[0]["acco_allowance"];
			//$acco_allowance = ($acco_allowance/$total_days) * $att_detail[0]["payable_days"];	
			$acco_allowance = ($acco_allowance/$working_days) * $paid_days;	
			$acco_allowance = round($acco_allowance);
		}
		
		#########################################################
		
		$mobile_text = 0;
		if($user_data[0]["mobile_allowance"] == "company_provided" || $user_data[0]["mobile_allowance"] == "fixed_cug")
		{
			$mobile_allowance = 0;
		}
		else if($user_data[0]["mobile_allowance"] == "bill_paid")
		{
			$mobile_text = 1;
			// $mobile_allowance=0;
			$mobile_allowance = $user_data[0]["mobile_bill_paid"];
			//$mobile_allowance = ($mobile_allowance/$total_days) * $att_detail[0]["payable_days"];	
			$mobile_allowance = ($mobile_allowance/$working_days) * $paid_days;	
			$mobile_allowance = round($mobile_allowance);
		}
		else
		{
			$mobile_text = 0;
			//$mobile_allowance = $user_data[0]["mobile_allowance"];
			$mobile_allowance = ($user_data[0]["mobile_allowance"]/$working_days) * $paid_days;	
			$mobile_allowance = round($mobile_allowance);
		}
		
		#########################################################
		
		$this->set("basic_pay_amount",$basic_pay_amount);
		$this->set("da_amount",$da_amount);
		$this->set("hra_amount",$hra_amount);
		$this->set("medical_amount",$medical_amount);
		$this->set("food_allowance",$food_allowance);
		$this->set("food_allowance_amount",$food_allowance_amount);
		$this->set("food_text",$food_text);
		$this->set("trans_allowance",$trans_allowance);
		$this->set("trans_allowance_amount",$trans_allowance_amount);
		$this->set("trans_text",$trans_text);
		$this->set("acco_allowance",$acco_allowance);
		$this->set("acco_text",$acco_text);
		$this->set("mobile_allowance",$mobile_allowance);
		$this->set("mobile_text",$mobile_text);
		$this->set("other_allowance",$other_allowance);
		
		#############################################################
		
		if($this->request->is("post"))
		{
			$tbl = TableRegistry::get("erp_salary_slip");
			$post = $this->request->data;
			// debug($post);die;
			$cnt = $tbl->find()->where(["user_id"=>$post["user_id"],"month"=>$post["month"],"year"=>$post["year"]])->count();
			if($cnt >= 1)
			{
				$this->Flash->success(__('Salary Slip aleardy genderated for this month', null), 
								'default', 
								array('class' => 'success'));
			}
			else
			{
				$post = $this->request->data();
				$date = $post['year']."-".$post['month']."-06";
				$issue_date = date('Y-m-d', strtotime('+1 month', strtotime($date)));

				$day_name = date('l', strtotime($issue_date));

				if($day_name == "Sunday"){
					$issue_date = date('Y-m-d', strtotime('-1 day', strtotime($issue_date)));
				}
				
				$post["salaryslip_type"] = 'salary_slip';
				$post["created_by"] = $this->user_id;
				$post["created_date"] = $issue_date;
				$post["approved"] = 0;
				$row = $tbl->newEntity();
				$row = $tbl->patchEntity($row,$post);
				if($tbl->save($row))
				{	
					$this->ERPfunction->set_loan_outstanding($post["loan_payment"],$post["user_id"],$post['month'],$post['year']);
					$this->Flash->success(__('Salary Slip Generated Successfully', null), 
									'default',
									array('class' => 'success'));
					// return $this->redirect(['action'=>'salaryslip']);
					echo "<script>window.close();</script>";
				}
			}
		}
	}
	
	public function editsalaryslip($slip_id)
	{
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray();/*->select($salary_tbl);
		$salary_data = $salary_data->leftjoin(
						["erp_users"=>"erp_users"],
						["erp_salary_slip.user_id = erp_users.user_id"]
						)->select($user_tbl)->hydrate(false)->toArray();*/
		$data = $salary_data[0];
		$this->set("data",$data);	
		// debug($data);
		// $user_data = $salary_data[0]["erp_users"];
		
		/*
		$pay_change = ($user_data["is_pay_structure_change"] == 1) ? true : false ; 
		$change_date = $user_data["change_date"];
		
		if($pay_change)
		{			
					
			$curr_date_stamp = strtotime($user_data["created_date"]);
			$change_date_stamp =  strtotime($change_date);			
			
			if($curr_date_stamp < $change_date_stamp) //NO NEED TO CHECK YEAR. LOAD OLD DATA FROM HISTORY TABLE
			{
				$h_tbl = TableRegistry::get("erp_users_history");
				$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->select($salary_tbl);
				$salary_data = $salary_data->leftjoin(
					["erp_users_history"=>"erp_users_history"],
					["erp_salary_slip.user_id = erp_users_history.user_id"]
					)->select($h_tbl)->hydrate(false)->toArray();
					
				$data = $salary_data[0];
				$this->set("data",$data);					
				$user_data = $salary_data[0]["erp_users_history"];				
			}
		} */
		
		// debug($salary_data);die;
		// $this->set("user_data",$salary_data[0]["erp_users_history"]);	
		// $user_data[0] = $user_data;
		/* $user_id = $user_data[0]["user_id"]; */
		$user_id = $data["user_id"];
		// debug($salary_data[0]["erp_users"]);die;
		// debug($data);die;
		
		$month = $data["month"];
		$year = $data["year"];
		$total_days = $data["total_days"];
		
		##########################################
		$food_text = 0;
		if($data["food_allowance"] == "bill_paid")
		{
			$food_text = 1;
		}		
		#########################################################
		
		$trans_text = 0;
		if($data["trans_allowance"] == "bill_paid")
		{
			$trans_text = 1;
		}		
		#########################################################
		
		$acco_text = 0;
		if($data["acco_allowance"] == "bill_paid")
		{
			$acco_text = 1;
		}		
		#########################################################
		
		$mobile_text = 0;		
		if($data["mobile_allowance"] == "bill_paid")
		{
			$mobile_text = 1;
		}		
		#########################################################	
	
		$this->set("food_text",$food_text);
		$this->set("trans_text",$trans_text);
		$this->set("acco_text",$acco_text);
		$this->set("mobile_text",$mobile_text);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			$post["updated_by"] = $this->user_id;
			$post["updated_date"] = date("Y-m-d");
			$salary_tbl = TableRegistry::get("erp_salary_slip");
			$row = $salary_tbl->get($slip_id);
			$row = $salary_tbl->patchEntity($row,$post);
			if($salary_tbl->save($row))
			{								
				$this->ERPfunction->set_loan_outstanding($post["loan_payment"],$post["user_id"],$post['month'],$post['year']);
				// $this->Flash->success(__('Salary Slip Updated Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
				// return $this->redirect(['action'=>'salarystatement']);
				echo "<script>window.close();</script>";
			}
			
		}
		
	}
	
	public function deletesalaryslip($slip_id,$month,$year)
	{
		
		$tbl = TableRegistry::get("erp_salary_slip");

		$row = $tbl->get($slip_id);

		$user_id = $row->user_id;
		$loan_paid_amt = $row->loan_payment;
		if($tbl->delete($row))
		{
			$this->ERPfunction->reverse_loan_payment($loan_paid_amt,$user_id,$month,$year);
			/*$this->Flash->success(__('Salary Slip Deleted Successfully', null), 
							'default', 
							array('class' => 'success'));*/
			return $this->redirect(['action'=>'salarystatement']);
		}
	}
	
	public function viewsalaryslip_old($salary_slip_id=NULL)
    {
		$erp_salary_slip = TableRegistry::get('erp_salary_slip'); 
		/* $users_table = TableRegistry::get('erp_employee');  */
		$this->set('months',$this->ERPfunction->month_names());
		$users_table = TableRegistry::get('erp_users'); 
		$user_list = $users_table->find();
		$this->set('user_list',$user_list);
		if(isset($salary_slip_id))
		{			
			$user_action = 'edit';
			
			$employee_data = $erp_salary_slip->get($salary_slip_id);
			$employee_data["pay_wa"]= unserialize($employee_data["pay_wa"]);
			$employee_data["da_sp"]= unserialize($employee_data["da_sp"]);
			$employee_data["hra_all"]= unserialize($employee_data["hra_all"]);
			$employee_data["conva_ta"]= unserialize($employee_data["conva_ta"]);
			$employee_data["total"]= unserialize($employee_data["total"]);
			
			$employee_data["pf_advance"] = unserialize($employee_data["pf_advance"]);
			$employee_data["esi_glwf"] = unserialize($employee_data["esi_glwf"]);
			$employee_data["pt"] = unserialize($employee_data["pt"]);
			$employee_data["ln"] = unserialize($employee_data["ln"]);
			$employee_data["total_deduction_row"] = unserialize($employee_data["total_deduction_row"]);
			
			$this->set('employee_data',$employee_data);
			$this->set('form_header','View Salary Slip');
			
		}
		$this->set('user_action',$user_action);		
    }
	
	public function viewsalaryslip($slip_id)
	{
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray(); /*->select($salary_tbl);
		$salary_data = $salary_data->leftjoin(
						["erp_users"=>"erp_users"],
						["erp_salary_slip.user_id = erp_users.user_id"]
						)->select($user_tbl)->hydrate(false)->toArray();
		// debug($salary_data);die;*/
		$this->set("data",$salary_data[0]);
		
		$data = $salary_data[0];
		##########################################
		$food_text = 0;
		if($data["food_allowance"] == "bill_paid")
		{
			$food_text = 1;
		}		
		#########################################################
		
		$trans_text = 0;
		if($data["trans_allowance"] == "bill_paid")
		{
			$trans_text = 1;
		}		
		#########################################################
		
		$acco_text = 0;
		if($data["acco_allowance"] == "bill_paid")
		{
			$acco_text = 1;
		}		
		#########################################################
		
		$mobile_text = 0;		
		if($data["mobile_allowance"] == "bill_paid")
		{
			$mobile_text = 1;
		}		
		#########################################################	
	
		$this->set("food_text",$food_text);
		$this->set("trans_text",$trans_text);
		$this->set("acco_text",$acco_text);
		$this->set("mobile_text",$mobile_text);
		
	}
	
	public function salarystatement_old()
    {
		$designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations);
		$role = $this->role;
		$this->set('months',$this->ERPfunction->month_names());
		
		$erp_salary_slip = TableRegistry::get('erp_salary_slip'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);
	
		if(!empty($projects_ids)){
			foreach($projects_ids as $pid)
			{
				$project_names[] = $this->ERPfunction->get_projectname($pid);
			}
		}
		
		if($role == 'projectdirector' ){ 
			if(!empty($projects_ids)){
				$employee_slip = $erp_salary_slip->find()->where(["employee_at IN"=>$project_names]);	
			}else{
				$employee_slip=array();
			}
		}else{
			 $employee_slip = $erp_salary_slip->find();
		}		
		
		$this->set('employee_slip',$employee_slip);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			// debug($post);die;
			
			$or = array();				
			
			$or["designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
			$or["full_name LIKE"] = (!empty($post["full_name"]))?"%{$post["full_name"]}%":NULL;
			$or["month IN"] = (!empty($post["month"]) && $post["month"][0] != "All")?$post["month"]:NULL;
			$or["year in"] = (!empty($post["year"]) && $post["year"][0] != "All" )?$post["year"]:NULL;
			
			if($role == 'projectdirector' )
			{ 
				$or["employee_at IN"]=$project_names;
			}
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			
			$employee_slip = $erp_salary_slip->find()->where([$or])->hydrate(false)->toArray();
			
			$this->set("employee_slip",$employee_slip);
			
		}
    }
	
	public function salarystatement($projects_id=null)
    { 
		$role = $this->role;
		$this->set("role",$role);
		// $this->set('months',$this->ERPfunction->month_names());
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set("projects",$projects);
		$salary_tbl = TableRegistry::get("erp_salary_slip");			
			$usr_tbl = TableRegistry::get("erp_users");		
		
		if($projects_id!=null){
			
			$or1 = array();
			/* $or1["erp_salary_slip.month >="] = ($from != null)? date("n",strtotime($from)):NULL;
			$or1["erp_salary_slip.year >="] = ($from != null)? date("Y",strtotime($from)):NULL;
			$or1["erp_salary_slip.month <="] = ($to != null)? date("n",strtotime($to)):NULL;
			$or1["erp_salary_slip.year <="] = ($to != null)? date("Y",strtotime($to)):NULL; */
			$or1["erp_salary_slip.employee_at"] = ($projects_id!=null)?$projects_id:NULL;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
					
			$salary_data = $salary_tbl->find()->where([$or1])->select($salary_tbl);
			$salary_data = $salary_data->rightjoin(
						["erp_users"=>"erp_users"],
						["erp_users.user_id = erp_salary_slip.user_id"]
						)->select($usr_tbl)->hydrate(false)->toArray();
			$this->set("salary_data",$salary_data);
		}
		
		if($this->request->is("post"))
		{	
			if(isset($this->request->data['go']))
			{
			$request = $this->request->data;
			
			
			$or = array();				
			
			$or["erp_salary_slip.employee_at"] = (!empty($request["project_id"]) && $request["project_id"] != "All")?$request["project_id"]:NULL;
			$or["erp_salary_slip.month"] = (!empty($request["date"]))? date("n",strtotime($request["date"])):NULL;
			$or["erp_salary_slip.year"] = (!empty($request["date"]))? date("Y",strtotime($request["date"])):NULL;
			$or["erp_users.pay_type IN"] = (!empty($request["pay_type"]) && $request["pay_type"][0] != "All")?$request["pay_type"]:NULL;
			

			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			
			$or["erp_salary_slip.approved"] = 0;
			//debug($or);die;
			
			$date = $this->request->data["date"];
			$month = date("n",strtotime($date));

			$year =  date("Y",strtotime($date));
			
				
			$salary_data = $salary_tbl->find()->where([$or])->select($salary_tbl);
			$salary_data = $salary_data->rightjoin(
						["erp_users"=>"erp_users"],
						["erp_users.user_id = erp_salary_slip.user_id"]
						)->select($usr_tbl)->hydrate(false)->toArray();
			// debug($salary_data);die;
			$this->set("salary_data",$salary_data);
			$this->set("month",$month);
			$this->set("year",$year);		
			}
			// $this->set("salary_data",$salary_data);
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				// debug($rows);die;
				$filename = "payslip_approval.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize(base64_decode($this->request->data["rows"]));

				$this->set("rows",$rows);
				$this->render("salarystatementpdf");
			}
		}
		
    }
	
	public function printslaryslip($salary_slip_id = null)
    {
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');			
		$erp_salary_slip = TableRegistry::get('erp_salary_slip'); 
		/* $users_table = TableRegistry::get('erp_employee');  */
		$users_table = TableRegistry::get('erp_users'); 
		$user_list = $users_table->find();
		$this->set('user_list',$user_list);
		
		if(isset($salary_slip_id))
		{
			
			$user_action = 'edit';
			
			$employee_data = $erp_salary_slip->get($salary_slip_id);
			$employee_data["pay_wa"]= unserialize($employee_data["pay_wa"]);
			$employee_data["da_sp"]= unserialize($employee_data["da_sp"]);
			$employee_data["hra_all"]= unserialize($employee_data["hra_all"]);
			$employee_data["conva_ta"]= unserialize($employee_data["conva_ta"]);
			$employee_data["total"]= unserialize($employee_data["total"]);
			
			$employee_data["pf_advance"] = unserialize($employee_data["pf_advance"]);
			$employee_data["esi_glwf"] = unserialize($employee_data["esi_glwf"]);
			$employee_data["pt"] = unserialize($employee_data["pt"]);
			$employee_data["ln"] = unserialize($employee_data["ln"]);
			$employee_data["total_deduction_row"] = unserialize($employee_data["total_deduction_row"]);
			
			$this->set('employee_data',$employee_data);
			$this->set('form_header','View Salary Slip');
			
		}
		$this->set('user_action',$user_action);				
    }
	
	public function printsalaryslip($slip_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');			
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray();/*->select($salary_tbl);
		$salary_data = $salary_data->leftjoin(
						["erp_users"=>"erp_users"],
						["erp_salary_slip.user_id = erp_users.user_id"]
						)->select($user_tbl)->hydrate(false)->toArray();
		// debug($salary_data);die;*/
		$this->set("data",$salary_data[0]);
		
		$data = $salary_data[0];
		##########################################
		$food_text = 0;
		if($data["food_allowance"] == "bill_paid")
		{
			$food_text = 1;
		}		
		#########################################################
		
		$trans_text = 0;
		if($data["trans_allowance"] == "bill_paid")
		{
			$trans_text = 1;
		}		
		#########################################################
		
		$acco_text = 0;
		if($data["acco_allowance"] == "bill_paid")
		{
			$acco_text = 1;
		}		
		#########################################################
		
		$mobile_text = 0;		
		if($data["mobile_allowance"] == "bill_paid")
		{
			$mobile_text = 1;
		}		
		#########################################################	
	
		$this->set("food_text",$food_text);
		$this->set("trans_text",$trans_text);
		$this->set("acco_text",$acco_text);
		$this->set("mobile_text",$mobile_text);
	}
	
	
	public function mailsalaryslip($slip_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');			
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray();/*->select($salary_tbl);
		$salary_data = $salary_data->leftjoin(
						["erp_users"=>"erp_users"],
						["erp_salary_slip.user_id = erp_users.user_id"]
						)->select($user_tbl)->hydrate(false)->toArray();
		// debug($salary_data);die;*/
		$this->set("data",$salary_data[0]);
		
		$data = $salary_data[0];
		##########################################
		$food_text = 0;
		if($data["food_allowance"] == "bill_paid")
		{
			$food_text = 1;
		}		
		#########################################################
		
		$trans_text = 0;
		if($data["trans_allowance"] == "bill_paid")
		{
			$trans_text = 1;
		}		
		#########################################################
		
		$acco_text = 0;
		if($data["acco_allowance"] == "bill_paid")
		{
			$acco_text = 1;
		}		
		#########################################################
		
		$mobile_text = 0;		
		if($data["mobile_allowance"] == "bill_paid")
		{
			$mobile_text = 1;
		}		
		#########################################################	
	
		$this->set("food_text",$food_text);
		$this->set("trans_text",$trans_text);
		$this->set("acco_text",$acco_text);
		$this->set("mobile_text",$mobile_text);
	}
	
	public function viewrecords()
	{
		$user_tbl = TableRegistry::get("erp_users");		
				
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set("projects",$projects);
		
		$users_table = TableRegistry::get('erp_users'); 
		$name_list = $users_table->find()->where(["employee_no !=" => ""]);
		$this->set('name_list',$name_list);
		
		$role = $this->role;
		$projects_ids = $this->Usermanage->users_project($this->user_id);
			
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids)){
				$employees = $user_tbl->find()->where(["employee_no !="=>"","employee_at IN"=>$projects_ids])->hydrate(false)->toArray();
			}else{
				$employees=array();
			}
		}else{
			 $employees = $user_tbl->find("all")->where(["employee_no !="=>""])->hydrate(false)->toArray();
		}		
		
		$this->set('employees',$employees);
		
		if($this->request->is('post'))
		{ 
			$post = $this->request->data;
			if(isset($post["go"]))
			{				
				$or = array();
				
				$or["user_id IN"] = (!empty($post["user_id"]) && $post["user_id"][0] != "All" )?$post["user_id"]:NULL;
				$or["designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
				$or["pay_type"] = (!empty($post["pay_type"]) && $post["pay_type"] != "All" )?$post["pay_type"]:NULL;
				$or["employee_at IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["employee_no"] = (!empty($post["employee_no"]))?$post["employee_no"]:NULL;
				$or["mobile_no"] = (!empty($post["mobile_no"]))?$post["mobile_no"]:NULL;			
				
				if($or["employee_at IN"] == NULL)
				{
					if($role == 'projectdirector' || $role == 'siteaccountant' || $role == 'constructionmanager')
					{ 
						$or["employee_at IN"] = $projects_ids;
					}
				}
			
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if($post["status"] == "working")
				{
					$or["is_resign"] = 0;
				}
				else if($post["status"] == "resigned")
				{
					$or["is_resign"] = 1;
					
				}
				
				// debug($or);die; 
				$search_data = $user_tbl->find("all")->where(["employee_no !="=>"",$or]);
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set("employees",$search_data);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "view_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$this->set("rows",$rows);
				$this->render("viewrecordspdf");
			}
		}		
	}
	
	public function printemployee($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_users");
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
		$this->set("id",$data->user_identy_number);			
	}

	public function printepersonnel($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_users");
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
		$this->set("id",$eid);			
	}
	
	public function printleavesheet($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_leavesheet");
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
	}
	
	public function attendance()
	{
		$this->set("form_header","Manual Thumb");
		$this->set("button_text","Take Manual Thumb");
		$emp_tbl = TableRegistry::get("erp_users");
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => ""]);
		$data = $data->select(["user_id","name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();
		$this->set("employees",$data);
		
		$this->user_id=$this->request->session()->read('user_id');
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set("role",$role);
		
		if($this->request->is("post"))
		{ 
			$att_tbl = TableRegistry::get("erp_attendance");
			if(isset($this->request->data["load_attendance"]))
			{				
				$user_id = $this->request->data["user_id"];
				$attendance_date = date("Y-m-d",strtotime($this->request->data["attendance_date"]));
				$check = $att_tbl->find()->where(["user_id"=>$user_id,"attendance_date"=>$attendance_date])->hydrate(false)->toArray();
				$this->set("day_started",false);
				$this->set("day_in_time","");
				$this->set("day_out_time","");
				$this->set("working_hours","");
				$this->set("attendance_date",date("d-m-Y",strtotime($attendance_date)));
					
				if(!empty($check))
				{
					$this->set("day_started",true);
					$this->set("day_in_time",$check[0]["day_in_time"]);
					$this->set("day_out_time",$check[0]["day_out_time"]);
					$this->set("working_hours",$check[0]["working_hours"]);
				}
			}
			
			if(isset($this->request->data["day_in"]))
			{
				$attendance_date = date("Y-m-d",strtotime($this->request->data["attendance_date"]));
				$user_id =  $this->request->data["user_id"];
				$ety = $att_tbl->newEntity();
				$data["user_id"] = $user_id;
				$data["attendance_date"] = $attendance_date;
				$data["day_in_time"] = date("h:i A");
				$ety = $att_tbl->patchEntity($ety,$data);
				$att_tbl->save($ety);
				
				$this->ERPfunction->save_attendance_detail($user_id,$attendance_date,"day_in");
				
				$this->Flash->success(__('Day Started Successfully', null), 
							'default', 
							array('class' => 'success'));
			}
			
			if(isset($this->request->data["day_out"]))
			{
				$attendance_date = date("Y-m-d",strtotime($this->request->data["attendance_date"]));
				$user_id =  $this->request->data["user_id"];
				$query = $att_tbl->find();
				$day_out =  date("h:i A");
				$working_hours = $this->counthours($user_id,$attendance_date,$day_out);
				
				$query->update()
					->set(['day_out_time' => $day_out,"working_hours"=>$working_hours])
					->where(["user_id"=>$user_id,"attendance_date"=>$attendance_date])
					->execute();
				
				$this->ERPfunction->save_attendance_detail($user_id,$attendance_date,"day_out",$working_hours);
				
					$this->Flash->success(__('Day Ended Successfully', null), 
							'default', 
							array('class' => 'success'));
			}
		}
		
		
	}
	
	public function counthours($user_id,$date,$day_out_time)
	{	
		$att_tbl = TableRegistry::get("erp_attendance");
		$row = $att_tbl->find()->where(["user_id"=>$user_id,"attendance_date"=>$date])->hydrate(false)->toArray();		
		
		$datetime1 = strtotime($row[0]["day_in_time"]);
		$datetime2 = strtotime($day_out_time);
		$dateDiff = intval(($datetime2-$datetime1)/60);
		$hours = intval($dateDiff/60);
		$minutes = $dateDiff%60;
		$hours_diff = $hours.":".$minutes;
		return $hours_diff;
		
		/* $from_time = strtotime($row[0]["day_in_time"]);
		$to_time = strtotime($day_out_time);
		$hours_diff = round(abs($to_time - $from_time) / 3600,2);
		$hours_diff=str_replace('.',':',$hours_diff);		
		return $hours_diff; */
	}
	
	public function viewattendance()
	{
		
	}
	
	
	public function addloan()
    {
		$this->set("form_header","Add Loan");
		$emp_tbl = TableRegistry::get("erp_users");
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => ""]);
		$data = $data->select(["user_id","name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();
		$this->set("employees",$data);
		$this->set("edit",false);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			
			$tbl = TableRegistry::get("erp_loan");
			/*$count = $tbl->find()->where(["user_id"=>$post["user_id"],"loan_status"=>0])->count();
			
			if($count >= 1)
			{
				$this->Flash->success(__('Loan record aleardy exist for this user.', null), 
							'default', 
							array('class' => 'success'));
			}
			else
			{	*/		
				$row = $tbl->newEntity();				
				$post["outstanding"] = $post["amount"];
				$post["remarks"] = $post["remarks"];
				$post["approved_by"] = $post["approved_by"];
				$post["created_by"] = $this->user_id;
				$post["created_date"] = date("Y-m-d");
				$post["given_date"] = ($post["given_date"] != "") ? date("Y-m-d",strtotime($post["given_date"])) : "";

				$row = $tbl->patchEntity($row,$post);
				if($tbl->save($row))
				{
					$this->Flash->success(__('Loan Added Successfully', null), 
								'default', 
								array('class' => 'success'));
					return $this->redirect(['action'=>'loanlist']);
				}
			/* } */
		}
    }
	
	public function loanlist($projects_id=null,$from=null,$to=null)
	{
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set("projects",$projects);
		
		$users_table = TableRegistry::get('erp_users'); 
		$name_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0]);
		$this->set('name_list',$name_list);
		$erp_loan = TableRegistry::get("erp_loan");
		$erp_users = TableRegistry::get("erp_users");
	
		$this->set('role',$this->role);
		
		
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["erp_loan.given_date >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["erp_loan.given_date <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["erp_users.employee_at"] = ($projects_id!=null)?$projects_id:NULL;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
					
			$result = $erp_loan->find()->select($erp_loan);
						$result = $result->innerjoin(
							["erp_users"=>"erp_users"],
							["erp_loan.user_id = erp_users.user_id"])
							->where($or1)->select($erp_users)->hydrate(false)->toArray();
				$this->set("loan_data",$result);
			
		}
		else
		{
			$tbl = TableRegistry::get("erp_loan");

			//$loan_data = $tbl->find("all")->where(['loan_status'=>0])->hydrate(false)->toArray();
			$loan_data = $tbl->find("all")->where(['loan_status'=>0])->hydrate(false)->toArray();
			$this->set("loan_data",$loan_data);
		}
		
		if($this->request->is("post"))
		{
				
				$post = $this->request->data;	
			
				$or = array();				
				
				$or["erp_loan.user_id IN"] = (!empty($post["user_id"]) && $post["user_id"][0] != "All" )?$post["user_id"]:NULL;
				
				$or["erp_users.designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
				$or["erp_users.employee_at IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["erp_users.employee_no"] = (!empty($post["employee_no"]))?$post["employee_no"]:NULL;
				$or["erp_loan.loan_status ="] = '0';
					
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				$result = $erp_loan->find()->select($erp_loan);
						$result = $result->innerjoin(
							["erp_users"=>"erp_users"],
							["erp_loan.user_id = erp_users.user_id"])
							->where($or)->select($erp_users)->hydrate(false)->toArray();
							
				
				$this->set("loan_data",$result);
		}
		
	}
	public function viewloan($loan_id)
	{
		$this->set("form_header","Loan Detail");
		$emp_tbl = TableRegistry::get("erp_users");
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => ""]);
		$data = $data->select(["user_id","name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();
		$this->set("employees",$data);
		$this->set("edit",true);
		
		$tbl = TableRegistry::get("erp_loan");
		$data = $tbl->get($loan_id);
		$row = $data;		
		$data = $data->toArray();		
		$this->set("data",$data);
	}
	
    public function editloan($loan_id)
    {
		$this->set("form_header","Update Loan");
		$emp_tbl = TableRegistry::get("erp_users");
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => ""]);
		$data = $data->select(["user_id","name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();
		$this->set("employees",$data);
		$this->set("edit",true);
		
		$tbl = TableRegistry::get("erp_loan");
		$data = $tbl->get($loan_id);
		$row = $data;		
		$data = $data->toArray();		
		$this->set("data",$data);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
		
			$row->amount = $post["amount"];	
			$row->outstanding = $post["amount"];		
			$row->remarks = $post["remarks"];			
			$row->approved_by = $post["approved_by"];						
			$row->given_date = ($post["given_date"] != "") ? date("Y-m-d",strtotime($post["given_date"])) : "";
			$row->installment = $post['installment'];
			$row->last_edited_by = $this->request->session()->read('user_id');			
			$row->last_edit_date = date('Y-m-d');
			
			if($tbl->save($row))
			{
				$this->Flash->success(__('Loan updated Successfully', null), 
								'default', 
								array('class' => 'success'));
					return $this->redirect(['action'=>'loanlist']);
			}
		}
		
		$this->render("addloan");
    }
	
	public function deleteloan($id)
    {
		$tbl = TableRegistry::get("erp_loan");
		$row = $tbl->get($id);
		if($tbl->delete($row))
		{
			$this->Flash->success(__('Loan Delete Successfully', null), 
							'default', 
							array('class' => 'success'));
			return $this->redirect(['action'=>'loanlist']);
		}
	}
	
	public function salaryrecords()
    {
		$role = $this->role;
		$this->set("role",$role);
		// $this->set('months',$this->ERPfunction->month_names());
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set("projects",$projects);
		
		$users_table = TableRegistry::get('erp_users');
		$name_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0]);
		$this->set('name_list',$name_list);
		
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		if($this->request->is("post"))
		{	
			if(isset($this->request->data['go']))
			{
			$salary_tbl = TableRegistry::get("erp_salary_slip");			
			$usr_tbl = TableRegistry::get("erp_users");	
		
			$post = $this->request->data;
			$from_month = (!empty($post["from_date"]))?date("n",strtotime($post["from_date"])):"";
			$from_year = (!empty($post["from_date"]))?date("Y",strtotime($post["from_date"])):"";
			$to_month = (!empty($post["to_date"]))?date("n",strtotime($post["to_date"])):"";
			$to_year = (!empty($post["to_date"]))?date("Y",strtotime($post["to_date"])):"";
				
			$or = array();				
				
			$or["month >="] = ($from_month != "")?$from_month:NULL;
			$or["year >="] = ($from_year != "")?$from_year:NULL;
			$or["month <="] = ($to_month != "")?$to_month:NULL;
			$or["year <="] = ($to_year != "")?$to_year:NULL;
			$or["erp_users.user_id IN"] = (!empty($post["user_id"]) && $post["user_id"][0] != "All" )?$post["user_id"]:NULL;
			$or["erp_users.designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
			$or["erp_users.employee_at IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			$or["erp_users.employee_no"] = (!empty($post["employee_no"]))?$post["employee_no"]:NULL;
			$or["erp_users.pay_type IN"] = (!empty($post["pay_type"]) && $post["pay_type"][0] != "All")?$post["pay_type"]:NULL;
			$or["erp_salary_slip.approved"] = 1;			

			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			if($post["status"] == "working")
			{
				$or["is_resign"] = 0;
			}
			else if($post["status"] == "resigned")
			{
				$or["is_resign"] = 1;
				
			}
			
			/* $salary_data = $salary_tbl->find()->where(["erp_salary_slip.employee_at"=>$post["project_id"],"erp_salary_slip.month"=>$month,"erp_salary_slip.year"=>$year,"erp_salary_slip.approved"=>1])->select($salary_tbl);
			$salary_data = $salary_data->rightjoin(
						["erp_users"=>"erp_users"],
						["erp_users.user_id = erp_salary_slip.user_id"]
						)->select($usr_tbl)->hydrate(false)->toArray(); */
						
						
			$salary_data = $salary_tbl->find()->select($salary_tbl);
			$salary_data = $salary_data->rightjoin(
						["erp_users"=>"erp_users"],
						["erp_users.user_id = erp_salary_slip.user_id"]
						)->where($or)->select($usr_tbl)->hydrate(false)->toArray();
						
						
						
			// debug($salary_data);die;
			$this->set("salary_data",$salary_data);
			// $this->set("month",$from_month);
			// $this->set("year",$from_year);
			}	

			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "salary_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$this->set("rows",$rows);
				$this->render("salaryrecordspdf");
			}
		}
    }
	
	public function unapprovesalaryslip($slip_id)
	{
		$tbl = TableRegistry::get("erp_salary_slip");
		$row = $tbl->get($slip_id);
		$row->approved = 0;
		$row->approved_by = 0;
		$row->approved_date = 0;
		if($tbl->save($row))
		{
			$this->Flash->success(__('Loan updated Successfully', null), 
								'default', 
								array('class' => 'success'));
			return $this->redirect(['action'=>'salaryrecords']);
		}
	}
	
	public function paystructure($employee_id)
	{		
		$user_action = 'edit';
		$user_tbl = TableRegistry::get("erp_users");
		$employee_data = $user_tbl->get($employee_id);
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		$history = $employee_data;
		$role = $this->role;
		$this->set("role",$role);
		$this->set('employee_data',$employee_data);
		$this->set('form_header','Change Pay Structure');
		$this->set('button_text','Change Pay Structure');
		$this->set('user_action',$user_action);
		$this->set('id',$employee_id);
		
		if($this->request->is('post'))
		{				
			$post = $this->request->data;
			// debug($post);die; 
			$modified_date = date("Y-m",strtotime($post['change_date']));
			$modified_date = $modified_date."-01";
			
			$data = $user_tbl->get($employee_id);
			if($data->is_pay_structure_change == 1)
			{
				$old_date = $data->change_date;
			}else{
				$old_date = $data->date_of_joining;
			}
			if(isset($post['food_fixed']))
			{
				// if($post['food_allowance'] == "fixed")
				// {
					$data->food_allowance = $post['food_fixed'];
				// }else{$data->food_allowance = $post['food_allowance'];}
			}
			
			if(isset($post['acc_fixed']))
			{
				// if($post['acco_allowance'] == "fixed")
				// {
					$data->acco_allowance = $post['acc_fixed'] ;
				// }else{$data->acco_allowance = $post['acco_allowance'];}
			}
			
			if(isset($post['trans_fixed']))
			{
				// if($post['trans_allowance'] == "fixed")
				// {
					$data->trans_allowance = $post['trans_fixed'];
				// }else{$data->trans_allowance = $post['trans_allowance'];}
			}
			
			if(isset($post['mobile_fixed']))
			{
				// if($post['mobile_allowance'] == "fixed")
				// {
					$data->mobile_allowance = $post['mobile_fixed'];
				// }else{$data->mobile_allowance = $post['mobile_allowance'];}
			}
			
			if(isset($post['change_date']))
			{
				$data->change_date = date("Y-m-d",strtotime($post["change_date"]));
			}
			if(isset($post['payment']))
			{
				$data->payment = $post["payment"];
			}
			// if(isset($post['designation']))
			// {
				// $data->designation = $post["designation"];
			// }
			if(isset($post['is_epf']))
			{
				$data->is_epf = $post["is_epf"];
			}
			if(isset($post['is_esi']))
			{
				$data->is_esi = $post["is_esi"];
			}
			if(isset($post['esi_no']))
			{
				$data->esi_no = $post["esi_no"];
			}
			if(isset($post['epf_no']))
			{
				$data->epf_no = $post["epf_no"];
			}
			if(isset($post['uan_no']))
			{
				$data->uan_no = $post["uan_no"];
			}
			if(isset($post['eligible_bonus']))
			{
				$data->eligible_bonus = $post["eligible_bonus"];
			}
			if(isset($post['bonus']))
			{
				$data->bonus = $post["bonus"];
			}
			if(isset($post['pay_type']))
			{
				$data->pay_type = $post["pay_type"];
			}
			// if(isset($post['category']))
			// {
				// $data->category = $post["category"];
			// }
			if(isset($post['monthly_pay']))
			{
				$data->monthly_pay = $post["monthly_pay"];
			}
			if(isset($post['basic_salary']))
			{
				$data->basic_salary = $post["basic_salary"];
			}
			if(isset($post['da']))
			{
				$data->da = $post["da"];
			}
			if(isset($post['medical_allowance']))
			{
				$data->medical_allowance = $post["medical_allowance"];
			}
			if(isset($post['other_allowance']))
			{
				$data->other_allowance = $post["other_allowance"];
			}
			if(isset($post['hra']))
			{
				$data->hra = $post["hra"];
			}
			if(isset($post['total_salary']))
			{
				$data->total_salary = $post["total_salary"];
			}
			if(isset($post['ctc']))
			{
				$data->ctc = $post["ctc"];
			}
			if(isset($post['cheque_name']))
			{
				$data->cheque_name = $post["cheque_name"];
			}
			if(isset($post['ac_no']))
			{
				$data->ac_no = $post["ac_no"];
			}
			if(isset($post['bank']))
			{
				$data->bank = $post["bank"];
			}
			if(isset($post['payment_mode']))
			{
				$data->payment_mode = $post["payment_mode"];
			}
			if(isset($post['branch']))
			{
				$data->branch = $post["branch"];
			}
			if(isset($post['ifsc_code']))
			{
				$data->ifsc_code = $post["ifsc_code"];
			}
						
			$data->paystructure_approved_by = $post["paystructure_approved_by"];
			$data->paystructure_approved_on = ($post["paystructure_approved_on"] != '')?date("Y-m-d",strtotime($post["paystructure_approved_on"])):"";
			$data->ref_document_no = $post["ref_document_no"];
			
			$data->is_pay_structure_change = 1;
			$data->paystructure_change_date = date("Y-m-d");
			$data->paystructure_change_by = $this->user_id;
			$user_tbl->save($data);
			
			##################################
			
			$history->user_id = $employee_id;
			$history->change_date = date("Y-m-d",strtotime($post["change_date"]));
			$history->old_date = $old_date;

			$history_array = $history->toArray();
			$history_tbl = TableRegistry::get("erp_users_history");
			$history_row = $history_tbl->find()->where(["change_date"=>$modified_date])->last();
			if(!empty($history_row)){
				$h_row = $history_tbl->get($history_row->id);
			}else{
				$h_row = $history_tbl->newEntity();
			}
			
			$h_row = $history_tbl->patchEntity($h_row,$history_array);
			$history_tbl->save($h_row);		
			
			// $this->Flash->success(__('Pay structure changed successfully', null), 
								// 'default', 
								// array('class' => 'success'));
			// return $this->redirect(['action'=>'emplyeelist']);
			echo "<script>window.close();</script>";
		}
	}
	
	public function changedesignation($employee_id)
	{
		$user_action = 'edit';
		$user_tbl = TableRegistry::get("erp_users");
		$employee_data = $user_tbl->get($employee_id);
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		$history = $employee_data;
		$role = $this->role;
		$this->set("role",$role);
		$this->set('employee_data',$employee_data);
		$this->set('form_header','Change Designation');
		$this->set('button_text','Change Designation');
		$this->set('user_action',$user_action);
		$this->set('id',$employee_id);
		
		if($this->request->is('post'))
		{				
			$post = $this->request->data;
			$modified_date = date("Y-m",strtotime($post['change_date']));
			$modified_date = $modified_date."-01";
			
			$data = $user_tbl->get($employee_id);
			if($data->is_change_designation == 1)
			{
				$old_date = $data->designation_change_date;
			}else{
				$old_date = $data->date_of_joining;
			}			
			if(isset($post['designation']))
			{
				$data->designation = $post["designation"];
			}
			if(isset($post['category']))
			{
				$data->category = $post["category"];
			}
			if(isset($post['change_date']))
			{
				$data->designation_change_date = date("Y-m-d",strtotime($post["change_date"]));
			}
				
			$data->is_change_designation = 1;
			$data->actual_designation_change_date = date("Y-m-d");
			$data->designation_change_by = $this->user_id;
			$user_tbl->save($data);
			
			##################################
			
			$history->user_id = $employee_id;
			$history->change_date = date("Y-m-d",strtotime($post["change_date"]));
			$history->old_date = $old_date;

			$history_array = $history->toArray();
			$history_tbl = TableRegistry::get("erp_designation_history");
			$history_row = $history_tbl->find()->where(["change_date"=>$modified_date])->last();
			if(!empty($history_row)){
				$h_row = $history_tbl->get($history_row->id);
			}else{
				$h_row = $history_tbl->newEntity();
			}
			
			$h_row = $history_tbl->patchEntity($h_row,$history_array);
			$history_tbl->save($h_row);		
			
			// $this->Flash->success(__('Pay structure changed successfully', null), 
								// 'default', 
								// array('class' => 'success'));
			// return $this->redirect(['action'=>'emplyeelist']);
			echo "<script>window.close();</script>";
		}
	}
	public function printdeploymenthistory($user_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$history_tbl = TableRegistry::get("erp_employee_transfer_history");
		$user_tbl = TableRegistry::get("erp_users");
		
		
		$this->set("user_id",$user_id);
		
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$data = $history_tbl->find()->where(["employee_id"=>$user_id])->hydrate(false)->toArray();
		
		if(!empty($data))
		{
			$first_project = $data[0]['old_project'];
		}
		else
		{
			$first_project = $user_data[0]['employee_at'];
		}
		
		$this->set("first_project",$first_project);
		$this->set("user_data",$user_data);
		$this->set("data",$data);
	}
	
	public function exceldeploymenthistory()
	{

		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$filename = "deployment.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
	}
	
	public function printpayhistory($user_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$user_tbl = TableRegistry::get("erp_users");
		$tbl = TableRegistry::get("erp_users_history");
		
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		$history = $tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$this->set("user_data",$user_data[0]);
		$this->set("history",$history);
		$this->set("user_id",$user_id);
	}
	
	public function excelpayhistory()
	{	
		if(isset($this->request->data["export_csv1"]))
		{
			debug(unserialize(base64_decode($this->request->data["payrows"])));
			$payrows = unserialize(base64_decode($this->request->data["payrows"]));
			$filename = "payhistory.csv";
			$this->ERPfunction->export_to_csv($filename,$payrows);
		}
	}
	
	public function printpayrecordshistory($user_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$salary_tbl = TableRegistry::get("erp_salary_slip");			
		$usr_tbl = TableRegistry::get("erp_users");
		
		$data = $salary_tbl->find()->where(["erp_salary_slip.user_id"=>$user_id])->select($salary_tbl);
		$data = $data->leftjoin(["erp_users" => "erp_users"],
		["erp_salary_slip.user_id = erp_users.user_id"])->select($usr_tbl)->hydrate(false)->toArray();
		
		$this->set("user_id",$user_id);
		$this->set("salary_data",$data);
	}
	
	public function excelpayrecordshistory() {
		 if($this->request->is('post')) {
			if(isset($this->request->data["export_csv"])) {
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "payrecords.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
		}
	}
	
	public function unapproveattendance($attendance_id)
	{
		$tbl = TableRegistry::get("erp_attendance_detail");
		$row = $tbl->get($attendance_id);
		$row->approved = 0;
		$row->approved_by = 0;
		$row->approved_date = NULL;
		if($tbl->save($row))
		{
			$this->Flash->success(__('Record Unapprove successfully', null), 
								'default', 
								array('class' => 'success'));
			return $this->redirect(['action'=>'salaryslip']);
		}
	}
	
	public function exceldesignationhistory()
	{	
		if(isset($this->request->data["export_csv1"]))
		{
			debug(unserialize(base64_decode($this->request->data["designationrows"])));
			$payrows = unserialize(base64_decode($this->request->data["designationrows"]));
			$filename = "designationhistory.csv";
			$this->ERPfunction->export_to_csv($filename,$payrows);
		}
	}
	
	public function printdesignationhistory($user_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$user_tbl = TableRegistry::get("erp_users");
		$tbl = TableRegistry::get("erp_designation_history");
		
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		$history = $tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$this->set("user_data",$user_data[0]);
		$this->set("history",$history);
		$this->set("user_id",$user_id);
	}
	
	public function salarystament()
	{
		$post = $this->request->data();
		$user_id = $post["user_id"];
		
		$salary_tbl = TableRegistry::get("erp_salary_slip");			
		$usr_tbl = TableRegistry::get("erp_users");
		
		$from_date = $post["from_date"];
		$to_date = $post["to_date"];
		
		$from_month = (!empty($post["from_date"]))?date("n",strtotime($post["from_date"])):"";
		$from_year = (!empty($post["from_date"]))?date("Y",strtotime($post["from_date"])):"";
		$to_month = (!empty($post["to_date"]))?date("n",strtotime($post["to_date"])):"";
		$to_year = (!empty($post["to_date"]))?date("Y",strtotime($post["to_date"])):"";
			
		$and = array();				
		$or = array();				
			
		$and["month >="] = ($from_month != "")?$from_month:NULL;
		$and["year >="] = ($from_year != "")?$from_year:NULL;
		$or["month <="] = ($to_month != "")?$to_month:NULL;
		$or["year <="] = ($to_year != "")?$to_year:NULL;
			
		// $data = $salary_tbl->find()->where(["erp_salary_slip.user_id"=>$user_id])->where($or)->hydrate(false)->toArray();
		
		$options = [
			'conditions' =>
			[ 
					['AND' =>
						[
							"erp_salary_slip.user_id"=>$user_id
						]
					],
					['OR' => 
						[
							['AND' => 
								[
									$and
								]
							],
							['AND' => 
								[
									$or
								]
							]
						]
					]
			]
		];

		$data = $salary_tbl->find('all', $options)->hydrate(false)->toArray();
		
		$month_range = array();
		$montharr = $this->ERPfunction->get_date_month_range($post["from_date"],$post["to_date"]);
		foreach(array_keys($montharr) as $year)
		{
			foreach($montharr[$year] as $month)
			{
				$month = ltrim($month,"0");
				$month_range[] = "{$year}-{$month}";
			}
		}
		
		// foreach ($month_range as $m) {
			// $salary_statement[$m] = array();
		// }
		$salary_statement = array();
		foreach($data as $retrive_data)
		{
			$key = $retrive_data['year'].'-'.$retrive_data['month'];
			
			$salary_statement['Basic Pay'][$key] = $retrive_data['basic_pay_amount'];
			$salary_statement['Dearness Allowance (D.A.)'][$key] = $retrive_data['da_amount'];
			$salary_statement['House Rent Allowance (H.R.A.)'][$key] = $retrive_data['acco_amount'];
			$salary_statement['Conveyance Allowance'][$key] = $retrive_data['hra_amount'];
			$salary_statement['Travel Allowance (T.A.)'][$key] = $retrive_data['transport_amount'];
			$salary_statement['Medical Allowance'][$key] = $retrive_data['medical_amount'];
			$salary_statement['Food Allowance'][$key] = $retrive_data['food_amount'];
			$salary_statement['Mobile Allowance'][$key] = $retrive_data['mobile_amount'];
			$salary_statement['Other Allowance'][$key] = $retrive_data['other_allowance_amount'];
			$salary_statement['Performance Incentives'][$key] = $retrive_data['incentive_amount'];
			$salary_statement['Salary Difference'][$key] = $retrive_data['salary_diff_amount'];
			$salary_statement['Total Earnings'][$key] = $retrive_data['total_earning'];
			$salary_statement['Professional Tax'][$key] = $retrive_data['pro_tax'];
			$salary_statement['Employee State Insurance (E.S.I.)'][$key] = $retrive_data['esi'];
			$salary_statement['Loan Repayment / Advance'][$key] = $retrive_data['loan_payment'];
			$salary_statement['Mobile Bill Recovery'][$key] = $retrive_data['mobile_bill_recovery'];
			$salary_statement['Tax Deducted at Source (T.D.S.)'][$key] = $retrive_data['tax_deducted_source'];
			$salary_statement['Other Deduction'][$key] = $retrive_data['others'];
			$salary_statement['Total Deductions'][$key] = $retrive_data['total_deduction'];
			$salary_statement['Net Pay'][$key] = $retrive_data['net_pay'];
		}
		// debug($salary_statement);die;
		$this->set("user_id",$user_id);
		$this->set("from_date",$from_date);
		$this->set("to_date",$to_date);
		$this->set("from_month",$from_month);
		$this->set("from_year",$from_year);
		$this->set("to_month",$to_month);
		$this->set("to_year",$to_year);
		$this->set("month_range",$month_range);
		$this->set("salary_statement",$salary_statement);
	}
	
	public function exportSalaryStatement()
	{
		$this->autoRender = false;
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$filename = "salarystatement.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
	}
	
	public function printSalaryStatement()
	{
		if(isset($this->request->data["export_pdf"]))
		{			
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$user_id = $this->request->data["user_id"];
			$from_date = $this->request->data["from_date"];
			$to_date = $this->request->data["to_date"];
			$this->set("rows",$rows);
			$this->set("user_id",$user_id);
			$this->set("from_date",$from_date);
			$this->set("to_date",$to_date);
			$this->render("printsalarystatement");
		}
	}
	
	public function generatesalaryvoucher($user_id,$month,$year,$custom_holiday)
	{
		$att_detail_tbl = TableRegistry::get("erp_attendance_detail");
		$att_detail = $att_detail_tbl->find()->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year,"approved"=>1])->hydrate(false)->toArray();			
		
		$user_tbl = TableRegistry::get("erp_users");
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$date = "01-".$month."-".$year;
		$total_days = date("t",strtotime($date));	
		
		$pay_change = ($user_data[0]["is_pay_structure_change"] == 1) ? true : false ; 
		$change_date = $user_data[0]["change_date"];
		if($pay_change)
		{
			$change_month = date("n",strtotime($change_date));
			$change_date = $change_date->format("Y-m-d");
			$curr_date_stamp = strtotime($date);
			$change_date_stamp =  strtotime($change_date);
			// if($month != $change_month) //NO NEED TO CHECK YEAR. LOAD OLD DATA FROM HISTORY TABLE
			if($curr_date_stamp < $change_date_stamp) //NO NEED TO CHECK YEAR. LOAD OLD DATA FROM HISTORY TABLE
			{
				$check_ch_date = $change_date;//->format("Y-m-d");
				$h_tbl = TableRegistry::get("erp_users_history");
				$user_data = $h_tbl->find()->where(["user_id"=>$user_id,"change_date"=>$check_ch_date])->hydrate(false)->toArray();
			}
		}
		
		$this->set("pay_change",$pay_change);
		$this->set("user_id",$user_id);
		$this->set("change_date",$change_date);
		$this->set("user_data",$user_data[0]);
		$this->set("att_detail",$att_detail[0]);
		$this->set("month",$month);
		$this->set("year",$year);	
		$this->set("custom_holiday",$custom_holiday);	
		$this->set("user_action","insert");	
		$this->set("button_text","Generate Voucher");	
		$this->set("total_days",$total_days);
		
		$month_all_day = $this->ERPfunction->total_day_of_month($month,$year);
		$working_days = $month_all_day - $custom_holiday;
		$paid_days = $att_detail[0]["payable_days"] * $working_days / $month_all_day;
		$paid_days = $x = floor($paid_days * 2) / 2;
		$paid_days = number_format((float)$paid_days,2, '.', '');
		
		
		
		$basic_pay_amount = ($user_data[0]["basic_salary"]/$working_days) * $paid_days;
		$basic_pay_amount = round($basic_pay_amount);
		
		
		$this->set("basic_pay_amount",$basic_pay_amount);
		
		
		#############################################################
		
		if($this->request->is("post"))
		{
			$tbl = TableRegistry::get("erp_salary_slip");
			$post = $this->request->data;
			// debug($post);die;
			$cnt = $tbl->find()->where(["user_id"=>$post["user_id"],"month"=>$post["month"],"year"=>$post["year"]])->count();
			if($cnt >= 1)
			{
				$this->Flash->success(__('Salary Slip aleardy genderated for this month', null), 
								'default', 
								array('class' => 'success'));
			}
			else
			{
				$post = $this->request->data();
				
				// $date = $post['year']."-".$post['month']."-06";
				// $issue_date = date('Y-m-d', strtotime('+1 month', strtotime($date)));
				// $day_name = date('l', strtotime($issue_date));
				// if($day_name == "Sunday"){
					// $issue_date = date('Y-m-d', strtotime('-1 day', strtotime($issue_date)));
				// }
				
				$post["user_id"] = $post['user_id'];
				$post["employee_at"] = $post['employee_at'];
				$post["month"] = $post['month'];
				$post["year"] = $post['year'];
				$post["salaryslip_type"] = 'voucher';
				$post["total_days"] = $post['total_days'];
				$post["payable_days"] = $post['payable_days'];
				$post["basic_salary"] = $post['basic_pay'];
				$post["loan_payment"] = $post['advance'];
				$post["others"] = $post['other_deductions'];
				$post["net_pay"] = $post['net_pay'];
				
				$post["created_by"] = $this->user_id;
				$post["created_date"] = date("Y-m-d",strtotime($post['date']));
				$post["approved"] = 0;
				$row = $tbl->newEntity();
				$row = $tbl->patchEntity($row,$post);
				if($tbl->save($row))
				{					
					$this->ERPfunction->set_loan_outstanding($post['advance'],$post["user_id"],$post['month'],$post['year']);
					$this->Flash->success(__('Salary Slip Generated Successfully', null), 
									'default',
									array('class' => 'success'));
					//return $this->redirect(['action'=>'salaryslip']);
					echo "<script>window.close();</script>";
				}
			}
		}
	}
	
	public function editsalaryvoucher($slip_id)
	{
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray();
		$data = $salary_data[0];
		$this->set("data",$data);	
		
		$user_id = $data["user_id"];
		// debug($salary_data[0]["erp_users"]);die;
		// debug($data);die;
		
		$month = $data["month"];
		$year = $data["year"];
		$total_days = $data["total_days"];
		
		$this->set("user_id",$user_id);
		$this->set("month",$month);
		$this->set("year",$year);
		$this->set("total_days",$total_days);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			
			$post["loan_payment"] = $post['advance'];
			$post["others"] = $post['other_deductions'];
			$post["net_pay"] = $post['net_pay'];
			$post["created_date"] = date("Y-m-d",strtotime($post['date']));
			$post["updated_by"] = $this->user_id;
			$post["updated_date"] = date("Y-m-d");
			$salary_tbl = TableRegistry::get("erp_salary_slip");
			$row = $salary_tbl->get($slip_id);
			$row = $salary_tbl->patchEntity($row,$post);
									
			if($salary_tbl->save($row))
			{								
				// $this->ERPfunction->set_loan_outstanding($post["loan_payment"],$post["user_id"],$post['month'],$post['year']);
				// $this->Flash->success(__('Salary Slip Updated Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
				// return $this->redirect(['action'=>'salarystatement']);
				echo "<script>window.close();</script>";
			}
			
		}
		
	}
	
	public function viewsalaryvoucher($slip_id)
	{
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray();
		$data = $salary_data[0];
		$this->set("data",$data);	
		
		$user_id = $data["user_id"];
		// debug($salary_data[0]["erp_users"]);die;
		// debug($data);die;
		
		$month = $data["month"];
		$year = $data["year"];
		$total_days = $data["total_days"];
		
		$this->set("user_id",$user_id);
		$this->set("month",$month);
		$this->set("year",$year);
		$this->set("total_days",$total_days);
			
	}
	
	public function printsalaryvoucher($slip_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray();
		$data = $salary_data[0];
		$this->set("data",$data);	
		
		$user_id = $data["user_id"];
		// debug($salary_data[0]["erp_users"]);die;
		// debug($data);die;
		
		$month = $data["month"];
		$year = $data["year"];
		$total_days = $data["total_days"];
		
		$this->set("user_id",$user_id);
		$this->set("month",$month);
		$this->set("year",$year);
		$this->set("total_days",$total_days);
			
	}
	
	public function generatesalarybill($user_id,$month,$year,$custom_holiday)
	{
		$att_detail_tbl = TableRegistry::get("erp_attendance_detail");
		$att_detail = $att_detail_tbl->find()->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year,"approved"=>1])->hydrate(false)->toArray();			
		
		$user_tbl = TableRegistry::get("erp_users");
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$date = "01-".$month."-".$year;
		$total_days = date("t",strtotime($date));	
		
		$pay_change = ($user_data[0]["is_pay_structure_change"] == 1) ? true : false ; 
		$change_date = $user_data[0]["change_date"];
		if($pay_change)
		{
			$change_month = date("n",strtotime($change_date));
			$change_date = $change_date->format("Y-m-d");
			$curr_date_stamp = strtotime($date);
			$change_date_stamp =  strtotime($change_date);
			// if($month != $change_month) //NO NEED TO CHECK YEAR. LOAD OLD DATA FROM HISTORY TABLE
			if($curr_date_stamp < $change_date_stamp) //NO NEED TO CHECK YEAR. LOAD OLD DATA FROM HISTORY TABLE
			{
				$check_ch_date = $change_date;//->format("Y-m-d");
				$h_tbl = TableRegistry::get("erp_users_history");
				$user_data = $h_tbl->find()->where(["user_id"=>$user_id,"change_date"=>$check_ch_date])->hydrate(false)->toArray();
			}
		}
		
		$this->set("pay_change",$pay_change);
		$this->set("user_id",$user_id);
		$this->set("change_date",$change_date);
		$this->set("user_data",$user_data[0]);
		$this->set("att_detail",$att_detail[0]);
		$this->set("month",$month);
		$this->set("year",$year);	
		$this->set("custom_holiday",$custom_holiday);	
		$this->set("user_action","insert");	
		$this->set("button_text","Generate Labour Bill");	
		$this->set("total_days",$total_days);
		
		$month_all_day = $this->ERPfunction->total_day_of_month($month,$year);
		$working_days = $month_all_day - $custom_holiday;
		$paid_days = $att_detail[0]["payable_days"] * $working_days / $month_all_day;
		$paid_days = $x = floor($paid_days * 2) / 2;
		$paid_days = number_format((float)$paid_days,2, '.', '');
		
		
		
		$basic_pay_amount = ($user_data[0]["basic_salary"]/$working_days) * $paid_days;
		$basic_pay_amount = round($basic_pay_amount);
		
		
		$this->set("basic_pay_amount",$basic_pay_amount);
		
		
		#############################################################
		
		if($this->request->is("post"))
		{
			$tbl = TableRegistry::get("erp_salary_slip");
			$post = $this->request->data;
			// debug($post);die;
			$cnt = $tbl->find()->where(["user_id"=>$post["user_id"],"month"=>$post["month"],"year"=>$post["year"]])->count();
			if($cnt >= 1)
			{
				$this->Flash->success(__('Salary Slip aleardy genderated for this month', null), 
								'default', 
								array('class' => 'success'));
			}
			else
			{
				$post = $this->request->data();
				// debug($post);die;
				// $date = $post['year']."-".$post['month']."-06";
				// $issue_date = date('Y-m-d', strtotime('+1 month', strtotime($date)));
				// $day_name = date('l', strtotime($issue_date));
				// if($day_name == "Sunday"){
					// $issue_date = date('Y-m-d', strtotime('-1 day', strtotime($issue_date)));
				// }
				$post["user_id"] = $post['user_id'];
				$post["employee_at"] = $post['employee_at'];
				$post["month"] = $post['month'];
				$post["year"] = $post['year'];
				$post["salaryslip_type"] = 'labourbill';
				$post["total_days"] = $post['total_days'];
				$post["payable_days"] = $post['payable_days'];
				$post["basic_salary"] = $post['basic_salary'];
				$post["loan_payment"] = $post['upad'];
				$post["others"] = $post['debit_this_bill'];
				$post["net_pay"] = $post['paid_amonut'];
				
				$post["created_by"] = $this->user_id;
				$post["created_date"] = date("Y-m-d",strtotime($post['date']));
				$post["approved"] = 0;
				$row = $tbl->newEntity();
				$row = $tbl->patchEntity($row,$post);
				// debug($row);die;
				if($tbl->save($row))
				{					
					$this->ERPfunction->set_loan_outstanding($post['upad'],$post["user_id"],$post['month'],$post['year']);
					$this->Flash->success(__('Salary Slip Generated Successfully', null), 
									'default',
									array('class' => 'success'));
					//return $this->redirect(['action'=>'salaryslip']);
					echo "<script>window.close();</script>";
				}
			}
		}
	}
	
	public function editsalarybill($slip_id)
	{
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray();
		$data = $salary_data[0];
		$this->set("data",$data);	
		
		$user_id = $data["user_id"];
		// debug($salary_data[0]["erp_users"]);die;
		// debug($data);die;
		
		$month = $data["month"];
		$year = $data["year"];
		$total_days = $data["total_days"];
		
		$this->set("user_id",$user_id);
		$this->set("month",$month);
		$this->set("year",$year);
		$this->set("total_days",$total_days);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			
			$post["loan_payment"] = $post['upad'];
			$post["others"] = $post['debit_this_bill'];
			$post["net_pay"] = $post['paid_amonut'];
			$post["updated_by"] = $this->user_id;
			$post["updated_date"] = date("Y-m-d");
			$salary_tbl = TableRegistry::get("erp_salary_slip");
			$row = $salary_tbl->get($slip_id);
			$row = $salary_tbl->patchEntity($row,$post);
									
			if($salary_tbl->save($row))
			{								
				// $this->ERPfunction->set_loan_outstanding($post["loan_payment"],$post["user_id"]);
				// $this->Flash->success(__('Salary Slip Updated Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
				// return $this->redirect(['action'=>'salarystatement']);
				echo "<script>window.close();</script>";
			}
			
		}
		
	}
	
	public function viewsalarybill($slip_id)
	{
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray();
		$data = $salary_data[0];
		$this->set("data",$data);	
		
		$user_id = $data["user_id"];
		// debug($salary_data[0]["erp_users"]);die;
		// debug($data);die;
		
		$month = $data["month"];
		$year = $data["year"];
		$total_days = $data["total_days"];
		
		$this->set("user_id",$user_id);
		$this->set("month",$month);
		$this->set("year",$year);
		$this->set("total_days",$total_days);
			
	}
	
	public function printsalarybill($slip_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$user_tbl = TableRegistry::get("erp_users");
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$salary_data = $salary_tbl->find()->where(["slip_id"=>$slip_id])->hydrate(false)->toArray();
		$data = $salary_data[0];
		$this->set("data",$data);	
		
		$user_id = $data["user_id"];
		// debug($salary_data[0]["erp_users"]);die;
		// debug($data);die;
		
		$month = $data["month"];
		$year = $data["year"];
		$total_days = $data["total_days"];
		
		$this->set("user_id",$user_id);
		$this->set("month",$month);
		$this->set("year",$year);
		$this->set("total_days",$total_days);
			
	}
	
	public function bonusalert()
	{
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set("projects",$projects);
		// $role = $this->role;
		// $this->set('role',$role);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data["go"]))
			{
				$request = $this->request->data;
				// debug($request);die;
				$current_year = date("Y",strtotime($request['current_year']));
				$previous_year = date("Y",strtotime($request['previous_year']));
				
				$bonus_tbl = TableRegistry::get("bonus_records");
				$bonus_records = $bonus_tbl->find()->where(["current_year"=>$current_year,"previous_year"=>$previous_year])->select('user_id')->hydrate(false)->toArray();
				$user_ids = array();
				foreach($bonus_records as $bonus)
				{
					$user_ids[] = $bonus['user_id'];
				}
				
				$user_tbl = TableRegistry::get("erp_users");
				if(!empty($user_ids))
				{
					$user_data = $user_tbl->find()->where(["user_id NOT IN"=>$user_ids,"employee_no !=" => "","is_resign"=>0,"employee_at"=>$request['project_id']])->select(["user_id","employee_at","user_identy_number","first_name","middle_name","last_name","designation"])->hydrate(false)->toArray();
				}else{
					$user_data = $user_tbl->find()->where(["employee_no !=" => "","is_resign"=>0,"employee_at"=>$request['project_id']])->select(["user_id","employee_at","user_identy_number","first_name","middle_name","last_name","designation"])->hydrate(false)->toArray();
				}
				
				// debug($user_data);die;
				$this->set("users",$user_data);
				$this->set("current_year",$current_year);
				$this->set("previous_year",$previous_year);
				$this->set("previous_month_year",$request['previous_year']);
				$this->set("current_month_year",$request['current_year']);
				
			}
		}		
	}
	/*Monthly Bonus Start*/

	public function createexgracia()
	{
		$year = date('Y');
		if(date('m') > '03')
		{
			
			$previous_year = ($year); //current year
			$current_year = ($year + 1); //next year
			
			$start = new DateTime($previous_year.'-04-01');
			$end = new DateTime($current_year.'-03-31');
		}else{
			$previous_year = ($year - 1); //current year
			$current_year = ($year); //next year
			
			$start = new DateTime($previous_year.'-04-01');
			$end = new DateTime($current_year.'-03-31');
		}
		$interval = new DateInterval('P1M');
		$period = new DatePeriod($start, $interval, $end);
		$financial_data = array();
		foreach ($period as $dt) {
			// echo $dt->format('m Y') . PHP_EOL;
			// $financial_data[$dt->format('Y')][$dt->format('m')];
			$financial_data['month'][] = $dt->format('m');
			$financial_data['year'][] = $dt->format('Y');
		}
		

		$emp_tbl = TableRegistry::get("erp_users");
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => ""]);
		$data = $data->select(["user_id","name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();
		$this->set('employees',$data);
		$this->set('financial_data',$financial_data);

		if(isset($this->request->data["submit"]))
		{

			$data = $this->request->data();
			
			$year =  date("Y",strtotime($data['bonus_date']));
			$month =  date("m",strtotime($data['bonus_date']));

			$exgrica_tbl = TableRegistry::get('exgrica_record');
			$row = $exgrica_tbl->newEntity();
				
				$data['created_date'] = date("Y-m-d");
				$data['month'] = $month;
				$data['Year'] =  $year;
						
				
			$row = $exgrica_tbl->patchEntity($row,$data);
			if($exgrica_tbl->save($row))
			{
				$this->Flash->success(__('Bonus Added Successfully', null), 
								'default', 
								array('class' => 'success'));
					return $this->redirect(['action'=>'createexgracia']);
			}
		}
	}	
	/*End*/
	
	public function generatebonus($user_id,$current_year,$previous_year)
	{
		
		$emp_tbl = TableRegistry::get("erp_users");
		$find =  $emp_tbl->get($user_id);
		
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => "",'user_id'=>$user_id]);
		$data = $data->select(["user_id","name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();

		$this->set('employees',$data);
		$this->set('records',$find);

		$start = new DateTime($previous_year.'-4-01');
		$interval = new DateInterval('P1M');
		$end = new DateTime($current_year.'-3-31');

		$period = new DatePeriod($start, $interval, $end);
		$financial_data = array();
		foreach ($period as $dt) {
			// echo $dt->format('m Y') . PHP_EOL;
			// $financial_data[$dt->format('Y')][$dt->format('m')];
			$financial_data['month'][] = intval($dt->format('m'));
			$financial_data['year'][] = $dt->format('Y');
		}
		$this->set('financial_data',$financial_data);
		$this->set('current_year',$current_year);
		$this->set('previous_year',$previous_year);


		$salary_tbl = TableRegistry::get("erp_salary_slip");
		// $salary_records = $salary_tbl->find()->where(["AND"=>[["user_id"=>$user_id],["OR"=>[["substr(month, 1,1)  >"=>3,"year"=>$previous_year],["substr(month, 1,1)  <"=>4,"year"=>$current_year]]]]])->hydrate(false)->toArray();
		
		$salary_records = $salary_tbl->find()->where(["AND"=>[["user_id"=>$user_id],["OR"=>[["month  >"=>03,"year"=>$previous_year],["month <"=>04,"year"=>$current_year]]]]])->hydrate(false)->toArray();
		// debug($salary_records);die;
		$salary_data = array();
		$total_earning = 0;
		foreach($salary_records as $record)
		{
			$salary_data[$record['year']][$record['month']] = $record['total_earning'];
			$total_earning += $record['total_earning'];
		}
		// debug($salary_data);die;
		$tax = $total_earning * 8.33 /100;
		$this->set('salary_data',$salary_data);
		$this->set('tax',$tax);
		$this->set('total_earning',$total_earning);
		if($this->request->is("post"))
		{
			$created_by = $this->request->session()->read('user_id');
			$data = $this->request->data();
			$data['total_bonus'] = $data['bonus'] + $data['extra_bonus'];
			$data['current_year'] = $current_year;
			$data['previous_year'] = $previous_year;
			$data['created_by'] = $created_by;
			$data['created_date'] = date('Y-m-d');
		
			$bonus_records = TableRegistry::get('bonus_records');
			$row = $bonus_records->newEntity();	
			$row = $bonus_records->patchEntity($row,$data);

			if($bonus_records->save($row))
			{
				$this->Flash->success(__('Bonus Added Successfully', null), 
								'default', 
								array('class' => 'success'));
					return $this->redirect(['action'=>'bonusalert']);
			}
		}
	}

	/*Add Expenditure*/
	public function expenditure()
	{
		$emp_tbl = TableRegistry::get("erp_users");
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => ""]);
		$data = $data->select(["user_id","name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();
		$this->set('employees',$data);

		if($this->request->is('post'))
		{
			$created_by = $this->request->session()->read('user_id');

			$post = $this->request->data();
			
			$clam_period = TableRegistry::get('expenditure_clam');

			$row = $clam_period->newEntity();
			$row['user_id'] = $post['user_id'];
			
			$row['clam_period'] = $post['clam_period'];
			$row['travel_charge'] = $post['travel_charge'];
			$row['house_charge'] = $post['house_charge'];
			$row['mobile_charge'] = $post['mobile_charge'];
			$row['food_charge'] = $post['food_charge'];
			$row['other_charge'] = $post['other_charge'];
			$row['total_amount'] = $post['total_amount'];
			$row['remark'] = $post['remark'];
			$row['created_date'] = date('Y-m-d');
			$row['created_by'] = $created_by;
		
			// $row = $clam_period->patchEntity($row,$datas);

			// debug($row);die;

			
			if($clam_period->save($row))
			{
				$this->Flash->success(__('Expenditure  Added Successfully', null), 
								'default', 
								array('class' => 'success'));
					return $this->redirect(['action'=>'expenditure']);
			}
			
		}
	}
	/*Expenditure End*/

	/*View Expenditure*/
	public function viewexpenditure($projects_id = null)
	{
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set("projects",$projects);
		
		$users_table = TableRegistry::get('erp_users'); 
		$name_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0,"pay_type"=>"employee"]);
		$this->set('name_list',$name_list);
		
		$users_table = TableRegistry::get("erp_users");
		
		$this->set('role',$this->role);
		
		if($this->request->is('post'))
		{

			if(isset($this->request->data["go"]))
			{
				$erp_users = TableRegistry::get("erp_users");
				$expenditure_tbl = TableRegistry::get("expenditure_clam");

				$post = $this->request->data();
			
				$or = array();				
				
				$or["expenditure_clam.user_id In"] = (!empty($post["user_id"]) && $post["user_id"][0] != "All" )?$post["user_id"]:NULL;
				$or["erp_users.designation IN"] = (!empty($post["designation"]) && $post["designation"][0] != "All" )?$post["designation"]:NULL;
				$or["erp_users.employee_at IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["erp_users.employee_no"] = (!empty($post["employee_no"]))?$post["employee_no"]:NULL;
				$or["expenditure_clam.clam_period"] = (!empty($post["clam_period"]))?$post["clam_period"]:NULL;
				
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
			

				$result = $expenditure_tbl->find()->select($expenditure_tbl);

				
				$result = $result->innerjoin(
						["erp_users"=>"erp_users"],
						["expenditure_clam.user_id = erp_users.user_id"])
						->where($or)->select($erp_users)->hydrate(false)->toArray();

				
				
				$this->set("user_list",$result);
				
			}
		}

		if(isset($this->request->data["export_csv"]))
		{
			
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "viewexpenditure.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
		}
			
		if(isset($this->request->data["export_pdf"]))
		{

				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$this->set("rows",$rows);
				$this->render("viewexpenditurepdf");
		}


		

		$emp_tbl = TableRegistry::get("erp_users");
		
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => ""]);
		$data = $data->select(["user_id","name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();

		$this->set('employees',$data);
	
		$claim_tbl = TableRegistry::get('expenditure_clam');
		

	}
	public function deleteexpenditure($user_id,$date)
	{
		$this->autoRender=false;
		$expenditure_tbl = TableRegistry::get('expenditure_clam');
		
			$user_data = $expenditure_tbl->find()->select('id')->where(['user_id'=>$user_id,'clam_period'=>$date])->hydrate(false)->toarray();
			$id = $expenditure_tbl->get($user_data[0]);


			$expenditure_tbl->delete($id);

			$this->Flash->success(__('Delete Expenditure Successfully', null), 
								'default', 
								array('class' => 'success'));
			return $this->redirect(['action' => 'viewexpenditure']);
	}

	public function expenditurehistory()
	{
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$filename = "expenditure.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
	}

	public function printexpenditure($user_id)
	{

		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$user_tbl = TableRegistry::get("erp_users");
		
		$user_data = $user_tbl->get($user_id);

		$this->set('user_data',$user_data);

		$expenditure_tbl = TableRegistry::get('expenditure_clam');

		$data = $expenditure_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$this->set('user_id',$user_id);
		$this->set('data',$data);
		
		
	}


	public function expenditurelist($user_id ,$date)
	{

		$employee_at = $this->ERPfunction->get_user_employee_at($user_id);
		$this->set('date',$date);
		$emp_tbl = TableRegistry::get("erp_users");
		$data = $emp_tbl->find("list",["keyField"=>"user_id","valueField"=>"name"])->where(["employee_no !=" => "",'user_id'=>$user_id]);
		$data = $data->select(["name"=>$data->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->toArray();

		$user_data = $emp_tbl->get($user_id);

		$this->set('employees',$data);
		$this->set('employee_at',$employee_at);
		$this->set('user_data',$user_data);

		$expenditure_tbl = TableRegistry::get('expenditure_clam');
		$expenditure_record = $expenditure_tbl->find()->where(['user_id'=>$user_id,'clam_period'=>$date])->hydrate(false)->toarray();
		$this->set('expenditure_record',$expenditure_record);

	}
	
	public function filemanager()
	{
		$baseurl = Router::url( $this->here, true );
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		$location = "";
		$this->set('location',$location);
		$this->set('role',$this->role);
		$this->set('baseurl',$baseurl);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data["searchbyproject"]))
			{
				$project_name = ($this->request->data["project_id"] != '')?$this->ERPfunction->get_projectname($this->request->data["project_id"]):'';
				$this->set('location',$project_name);
			}
		}
	}
			
	/*View Expenditure End*/
	
	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
	
}
