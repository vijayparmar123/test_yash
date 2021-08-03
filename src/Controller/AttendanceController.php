<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\Mailer\Email;
use mPDF;
use Cake\Datasource\ConnectionManager;

class AttendanceController extends AppController
{
	public function initialize()
	{
		  parent::initialize();
		
		
		$this->loadComponent("Flash");
		$this->loadComponent("ERPfunction");
		$this->loadComponent("Usermanage");
		$this->user_id=$this->request->session()->read('user_id');
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$this->rights = $this->Usermanage->attendance_access_right();
		$action = $this->request->action;
		
		if(isset($this->rights[$action][$this->role]))
		{
			$is_capable = $this->rights[$action][$this->role];	
		}
		else
		{ $is_capable = 0; }		
	
		$this->set('is_capable',$is_capable);
		$role = $this->role;
		$this->set('role',$role);
	}
	
	public function timelog()
    {
		/* $projects = $this->ERPfunction->get_projects(); */
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set("projects",$projects);
		if(isset($this->request->data["go"]))
		{			
			$date = $this->request->data["date"];
			$month = date("n",strtotime($date));
			$year = date("Y",strtotime($date));
			$emp_at = (isset($this->request->data["project_id"])) ? $this->request->data["project_id"] : array();
			
			if(!empty($emp_at))
			{	
				$users = $this->Attendance->find("all")->contain(["erp_users"])
					->where(["erp_users.is_resign"=>0,"MONTH(attendance_date)"=>$month,"YEAR(attendance_date)"=>$year,"employee_at IN"=>$emp_at])->group(["Attendance.user_id"])->hydrate(false)->toArray();
			}
			else{	
				$users = $this->Attendance->find("all")->contain(["erp_users"])
					->where(["erp_users.is_resign"=>0,"MONTH(attendance_date)"=>$month,"YEAR(attendance_date)"=>$year])->group(["Attendance.user_id"])->hydrate(false)->toArray();
			}
			// debug($users);die;
			$this->set("users",$users);
			$this->set("month",$month);
			$this->set("year",$year);
		}
    }
	
	public function viewlog()
    {
		if(empty($this->request->pass))
		{
			return false;
		}
		$user_id = $this->request->pass[0];
		$month = $this->request->pass[1];
		$year = $this->request->pass[2];
		
		$record = $this->Attendance->find()->where(["user_id"=>$user_id,"MONTH(attendance_date)"=>$month,"YEAR(attendance_date)"=>$year])->hydrate(false)->toArray();
		$this->set("record",$record);
		$this->set("user_id",$user_id);
		$this->set("month",$month);
		$this->set("year",$year);
    }
	
	public function attendancealert()
	{
		/* $projects = $this->ERPfunction->get_projects(); */
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set("projects",$projects);
		$this->set("role",$this->role);
		
		if(isset($this->request->data["go"]))
		{			
			$date = $this->request->data["date"];
			$month = date("n",strtotime($date));
			$year = date("Y",strtotime($date));
			$emp_at = (isset($this->request->data["project_id"]) && $this->request->data["project_id"][0] != "All" ) ? $this->request->data["project_id"] : array();
			
			if(!empty($emp_at))
			{	
				$users = $this->Attendance->AttendanceDetail->find("all")->contain(["erp_users"])
					->where(["erp_users.is_resign"=>0,"att_generated"=>1,"approved"=>0,"month"=>$month,"year"=>$year,"erp_users.employee_at IN"=>$emp_at])->group(["AttendanceDetail.user_id"])->hydrate(false)->toArray();
			}
			else{	
				$users = $this->Attendance->AttendanceDetail->find("all")->contain(["erp_users"])
					->where(["erp_users.is_resign"=>0,"att_generated"=>1,"approved"=>0,"month"=>$month,"year"=>$year])->group(["AttendanceDetail.user_id"])->hydrate(false)->toArray();
			}
			$this->set("users",$users);
			$this->set("month",$month);
			$this->set("year",$year);
		}
		
		else if(isset($this->request->data["edit_all"]))
		{
			if(isset($this->request->data["emp_at"]))
			{
				$this->changeattendancestatusall($this->request->data);
				$this->request->data["project_id"] = json_decode($this->request->data["emp_at"]);
			}
			
			$date = $this->request->data["date"];
			$month = date("n",strtotime($date));
			$year = date("Y",strtotime($date));
			$emp_at = (isset($this->request->data["project_id"])) ? $this->request->data["project_id"] : array();
			
			if(!empty($emp_at))
			{	
				$users = $this->Attendance->AttendanceDetail->find("all")->contain(["erp_users"])
					->where(["erp_users.is_resign"=>0,"approved"=>0,"month"=>$month,"year"=>$year,"erp_users.employee_at IN"=>$emp_at])->group(["AttendanceDetail.user_id"])->hydrate(false)->toArray();
			}
			
			$this->set("records",$users);
			$this->set("month",$month);
			$this->set("year",$year);
			$this->set("emp_at",$emp_at);
			
			// debug($users);die;
			$this->render("editattendanceall");
		}
		else if(isset($this->request->data["view_all"]))
		{
			if(isset($this->request->data["emp_at"]))
			{
				$this->changeattendancestatusall($this->request->data);
				$this->request->data["project_id"] = json_decode($this->request->data["emp_at"]);
			}
			
			$date = $this->request->data["date"];
			$month = date("n",strtotime($date));
			$year = date("Y",strtotime($date));
			$emp_at = (isset($this->request->data["project_id"])) ? $this->request->data["project_id"] : array();
			
			if(!empty($emp_at))
			{	
				$users = $this->Attendance->AttendanceDetail->find("all")->contain(["erp_users"])
					->where(["erp_users.is_resign"=>0,"approved"=>0,"month"=>$month,"year"=>$year,"erp_users.employee_at IN"=>$emp_at])->group(["AttendanceDetail.user_id"])->hydrate(false)->toArray();
			}
			
			$this->set("records",$users);
			$this->set("month",$month);
			$this->set("year",$year);
			$this->set("emp_at",$emp_at);
			
			// debug($users);die;
			$this->render("viewrecordall");
		}
		
	}
	
	public function editattendance()
	{
		if(empty($this->request->pass))
		{
			return false;
		}
		$user_id = $this->request->pass[0];
		$month = $this->request->pass[1];
		$year = $this->request->pass[2];
		
		$record = $this->Attendance->AttendanceDetail->find("all")
					->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year])->group(["AttendanceDetail.user_id"])->hydrate(false)->toArray();
		
		$this->set("record",$record[0]);
		$this->set("user_id",$user_id);
		$this->set("month",$month);
		$this->set("year",$year);
		$this->set("role",$this->role);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			$row = $this->Attendance->AttendanceDetail->get($post["att_id"]);
			$row->total_present = $post["present"];
			$row->total_absent = $post["absent"];
			$row->opening_pl = $post["opening"];
			$row->new = $post["new"];
			$row->total_holidays = $post["holiday"];
			$row->man_pl = $post["manual"];
			$row->used_pl = $post["used"];
			$row->remaining_pl = $post["remaining"];
			$row->payable_days = $post["payable_days"];
			$this->Attendance->AttendanceDetail->save($row);
			
			// $this->Flash->success(__('Time Log Approved Successfully', null), 
							// 'default', 
							// array('class' => 'success'));
			// $this->redirect(["action"=>"attendancealert"]);
			echo "<script>window.close();</script>";
		}
	}
	public function vieweditattendance()
	{
		if(empty($this->request->pass))
		{
			return false;
		}
		$user_id = $this->request->pass[0];
		$month = $this->request->pass[1];
		$year = $this->request->pass[2];
		
		$record = $this->Attendance->AttendanceDetail->find("all")
					->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year])->group(["AttendanceDetail.user_id"])->hydrate(false)->toArray();
			
		$this->set("record",$record[0]);
		$this->set("user_id",$user_id);
		$this->set("month",$month);
		$this->set("year",$year);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			$row = $this->Attendance->AttendanceDetail->get($post["detail_id"]);
			$row->approved = 1;
			$row->approved_by = $this->user_id;
			$row->approved_date = date("Y-m-d");
			$this->Attendance->AttendanceDetail->save($row);
			
			$this->Flash->success(__('Time Log Approved Successfully', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(["action"=>"attendancealert"]);
		}
	}
	
	public function changeattendancestatus()
	{
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			$user_id = $post["user_id"];
			$day = $post["day"];
			$month = $post["month"];
			$year = $post["year"];
			$detail_id = $post["detail_id"];
			$new_status = $post["new_status"];
			
			$row = $this->Attendance->AttendanceDetail->get($detail_id);
			$row->{'day_'.$day} = $new_status;
			$this->Attendance->AttendanceDetail->save($row);
			##################
			
			$last_month_balance = $this->ERPfunction->get_leave_balance($user_id,$month,$year);
			//debug($last_month_balance);die;
			$monthly_leave = $this->ERPfunction->get_monthly_paid_leave($user_id);
			// debug($last_month_balance);die;
				
			// $pl_balance = $last_month_balance + $monthly_leave;
			
			$total_present = $this->ERPfunction->get_monthly_total_attendace($user_id,$month,$year);			
			$presents = (isset($total_present["P"])) ? $total_present["P"] : 0; /* Default 0 Present for month, same for below*/
			$manual_present = (isset($total_present["manual_P"])) ? $total_present["manual_P"] : 0; /* Default 0 Present for month, same for below*/
			$presents = $presents + $manual_present;
			
			$half_days = (isset($total_present["HL"])) ? $total_present["HL"] : 0;
			$manual_half_days = (isset($total_present["manual_HL"])) ? $total_present["manual_HL"] : 0;
			$half_days = $half_days + $manual_half_days;
			
			$aa = (isset($total_present["AA"])) ? $total_present["AA"] : 0;
			$manual_aa = (isset($total_present["manual_AA"])) ? $total_present["manual_AA"] : 0;
			$aa = $aa + $manual_aa;
			
			$absents = $total_present["A"];
			$manual_absents = (isset($total_present["manual_A"])) ? $total_present["manual_A"] : 0;
			$absents = $absents + $manual_absents + ($half_days * 0.5);			
		
			$holidays = isset($total_present["H"]) ? $total_present["H"] : 0;
			$manual_holidays = (isset($total_present["manual_H"])) ? $total_present["manual_H"] : 0;
			$holidays = $holidays + $manual_holidays;
			
			if($monthly_leave == 4)/* $monthly_leave == 4 means it b category and leave edit with 1 for each 7 present */
			{
				if($presents + $holidays >= 28)
				{
					$monthly_leave = 4;
				}else if($presents + $holidays >= 21){
					$monthly_leave = 3;
				}else if($presents + $holidays >= 14){
					$monthly_leave = 2;
				}
				else if($presents + $holidays >= 7){
					$monthly_leave = 1;
				}
				else{
					$monthly_leave = 0;
				}
			}
			
			if($monthly_leave == 2)/* $monthly_leave == 2 means it c category and leave edit with 1 for each 14 present */
			{
				if($presents + $holidays >= 28)
				{
					$monthly_leave = 2;
				}else if($presents + $holidays >= 14){
					$monthly_leave = 1;
				}else{
					$monthly_leave = 0;
				}
			}
			
			if($monthly_leave === "NA")
			{	
				$last_month_balance = 0;
				$monthly_leave = $absents;
				$used_pl = $absents;
				$remaining_pl = 0;
				$date = "{$year}-{$month}-{$day}";
				$date = date("Y-m-d",strtotime($date));
				$payable_days = date("t",strtotime($date));			
			}
			else
			{		
				$pl_balance = $last_month_balance + $monthly_leave;
				//debug($pl_balance);die;
				$total_present = ($presents != 0  ) ? $presents + $holidays : 0; /* not including holiday as present if absent whole month*/
				$total_present = $total_present + ($half_days * 0.5);
				/* $total_absents = $absents + ($aa * 1.5); */
				$total_absents = $absents + $aa;
				
				//$remaining_pl = $pl_balance - $total_absents;
				//$remaining_pl = $remaining_pl + $post['man_pl'];
				
				// if($remaining_pl >= 0 )
				// {
					// $used_pl = $total_absents;
				// }
				// else{
					// $remaining_pl = 0;
					// $used_pl = $pl_balance;
				// }
				if($last_month_balance + $monthly_leave + $post['man_pl'] < $total_absents - $aa)
				{
					$used_pl = $last_month_balance + $monthly_leave + $post['man_pl'];
				}
				else{
					
					$used_pl = $total_absents - $aa;
				}
				$remaining_pl = $last_month_balance + $monthly_leave + $post['man_pl'] - $used_pl;
				$payable_days = $presents + $used_pl + $holidays + ($half_days/2) ;
				
			}
			######################################
			
			$row = $this->Attendance->AttendanceDetail->get($detail_id);
			$row->{'day_'.$day} = $new_status;
			$row->total_present = $presents + ($half_days * 0.5);
			$row->total_absent = $absents;
			$row->total_holidays = $holidays;
			$row->total_aa = $aa;
			$row->opening_pl = $last_month_balance;
			$row->new = $monthly_leave;
			$row->used_pl = $used_pl;
			$row->man_pl = $post['man_pl'];
			$row->remaining_pl = $remaining_pl;
			$row->payable_days = $payable_days;
			
			$this->Attendance->AttendanceDetail->save($row);
			// $this->ERPfunction->saveremainingpl($user_id,$remaining_pl);
			
			$this->redirect(array("action"=>"editattendance",$user_id,$month,$year));
		}		
	}
	
	public function changeattendancestatusall($post)
	{
	
		// $post = $this->request->data;
		$user_id = $post["user_id"];
			$day = $post["day"];
			$month = $post["month"];
			$year = $post["year"];
			$detail_id = $post["detail_id"];
			$new_status = $post["new_status"];
			
			$row = $this->Attendance->AttendanceDetail->get($detail_id);
			$row->{'day_'.$day} = $new_status;
			$this->Attendance->AttendanceDetail->save($row);
			##################
			
			$last_month_balance = $this->ERPfunction->get_leave_balance($user_id,$month,$year);
			//debug($last_month_balance);die;
			$monthly_leave = $this->ERPfunction->get_monthly_paid_leave($user_id);
			// debug($last_month_balance);die;
				
			// $pl_balance = $last_month_balance + $monthly_leave;
			
			$total_present = $this->ERPfunction->get_monthly_total_attendace($user_id,$month,$year);			
			$presents = (isset($total_present["P"])) ? $total_present["P"] : 0; /* Default 0 Present for month, same for below*/
			$manual_present = (isset($total_present["manual_P"])) ? $total_present["manual_P"] : 0; /* Default 0 Present for month, same for below*/
			$presents = $presents + $manual_present;
			
			$half_days = (isset($total_present["HL"])) ? $total_present["HL"] : 0;
			$manual_half_days = (isset($total_present["manual_HL"])) ? $total_present["manual_HL"] : 0;
			$half_days = $half_days + $manual_half_days;
			
			$aa = (isset($total_present["AA"])) ? $total_present["AA"] : 0;
			$manual_aa = (isset($total_present["manual_AA"])) ? $total_present["manual_AA"] : 0;
			$aa = $aa + $manual_aa;
			
			$absents = $total_present["A"];
			$manual_absents = (isset($total_present["manual_A"])) ? $total_present["manual_A"] : 0;
			$absents = $absents + $manual_absents + ($half_days * 0.5);			
		
			$holidays = isset($total_present["H"]) ? $total_present["H"] : 0;
			$manual_holidays = (isset($total_present["manual_H"])) ? $total_present["manual_H"] : 0;
			$holidays = $holidays + $manual_holidays;
			
			if($monthly_leave == 4)/* $monthly_leave == 4 means it b category and leave edit with 1 for each 7 present */
			{
				if($presents + $holidays >= 28)
				{
					$monthly_leave = 4;
				}else if($presents + $holidays >= 21){
					$monthly_leave = 3;
				}else if($presents + $holidays >= 14){
					$monthly_leave = 2;
				}
				else if($presents + $holidays >= 7){
					$monthly_leave = 1;
				}
				else{
					$monthly_leave = 0;
				}
			}
			
			if($monthly_leave == 2)/* $monthly_leave == 2 means it c category and leave edit with 1 for each 14 present */
			{
				if($presents + $holidays >= 28)
				{
					$monthly_leave = 2;
				}else if($presents + $holidays >= 14){
					$monthly_leave = 1;
				}else{
					$monthly_leave = 0;
				}
			}
			
			if($monthly_leave === "NA")
			{	
				$last_month_balance = 0;
				$monthly_leave = $absents;
				$used_pl = $absents;
				$remaining_pl = 0;
				$date = "{$year}-{$month}-{$day}";
				$date = date("Y-m-d",strtotime($date));
				$payable_days = date("t",strtotime($date));			
			}
			else
			{		
				$pl_balance = $last_month_balance + $monthly_leave;
				//debug($pl_balance);die;
				$total_present = ($presents != 0  ) ? $presents + $holidays : 0; /* not including holiday as present if absent whole month*/
				$total_present = $total_present + ($half_days * 0.5);
				/* $total_absents = $absents + ($aa * 1.5); */
				$total_absents = $absents + $aa;
				
				//$remaining_pl = $pl_balance - $total_absents;
				//$remaining_pl = $remaining_pl + $post['man_pl'];
				
				// if($remaining_pl >= 0 )
				// {
					// $used_pl = $total_absents;
				// }
				// else{
					// $remaining_pl = 0;
					// $used_pl = $pl_balance;
				// }
				if($last_month_balance + $monthly_leave + $post['man_pl'] < $total_absents - $aa)
				{
					$used_pl = $last_month_balance + $monthly_leave + $post['man_pl'];
				}
				else{
					
					$used_pl = $total_absents - $aa;
				}
				$remaining_pl = $last_month_balance + $monthly_leave + $post['man_pl'] - $used_pl;
				$payable_days = $presents + $used_pl + $holidays + ($half_days/2) ;
				
			}
			######################################
			
			$row = $this->Attendance->AttendanceDetail->get($detail_id);
			$row->{'day_'.$day} = $new_status;
			$row->total_present = $presents + ($half_days * 0.5);
			$row->total_absent = $absents;
			$row->total_holidays = $holidays;
			$row->total_aa = $aa;
			$row->opening_pl = $last_month_balance;
			$row->new = $monthly_leave;
			$row->used_pl = $used_pl;
			$row->man_pl = $post['man_pl'];
			$row->remaining_pl = $remaining_pl;
			$row->payable_days = $payable_days;
			
			$this->Attendance->AttendanceDetail->save($row);		
	}
	
	public function addleavebalance()
	{
		if($this->request->is("post"))
		{
			$post = $this->request->data;			
			$tbl = TableRegistry::get("erp_users");
			$row = $tbl->get($post["user_id"]);
			$row->leave_balance = $post["balance"];
			$tbl->save($row);
			$this->Flash->success(__('Leave Balance added successfully Successfully', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(array("controller"=>"humanresource","action"=>"emplyeelist"));
		}	
	}
	
	public function attendancerecord($projects_id=null,$from=null,$to=null)
	{		
		// $projects = $this->ERPfunction->get_projects();
		// $this->set("projects",$projects);
		
		$table_category=TableRegistry::get('erp_category_master');
		$designationlist=$table_category->find()->where(array('type'=>'designation'));
		$this->set('designationlist',$designationlist);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set("projects",$projects);
		$users_table = TableRegistry::get('erp_users'); 
		
		if($projects_id!=null){
			
			$or1 = array();	

			$from_month = (!empty($from))?date("n",strtotime($from)):"";
			$from_year = (!empty($from))?date("Y",strtotime($from)):"";
			$to_month = (!empty($to))?date("n",strtotime($to)):"";
			$to_year = (!empty($to))?date("Y",strtotime($to)):"";			
			
			$or1["month >="] = ($from_month != "")?$from_month:NULL;
			$or1["year >="] = ($from_year != "")?$from_year:NULL;
			$or1["month <="] = ($to_month != "")?$to_month:NULL;
			$or1["year <="] = ($to_year != "")?$to_year:NULL;
			$or1["employee_at"] = ($projects_id!=null)?$projects_id:NULL;
			//$or1["employee_no !="] = "";
			$or1["is_resign !="] = 0;
			$or1["approved"] = 1;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
			
			$users = $this->Attendance->AttendanceDetail->find()->contain(["erp_users"])
					->where([$or1])->group(["AttendanceDetail.user_id"])->hydrate(false)->toArray();
			
			$this->set('users',$users);			
			$this->set("from_month",$from_month);
			$this->set("from_year",$from_year);
			$this->set("to_month",$to_month);
			$this->set("to_year",$to_year);
		}
		else{
			$name_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0]);
			$this->set('name_list',$name_list);
		}
		
		
		
		if(isset($this->request->data["go"]))
		{		

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
			
				
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			// debug($post);
			// debug($or);die;
		
			//$date = $this->request->data["date"];
			//$month = date("n",strtotime($date));
			//$year = date("Y",strtotime($date));
			//$emp_at = (isset($this->request->data["project_id"])) ? $this->request->data["project_id"] : array();
			
			// if(!empty($emp_at))
			// {	
				// $users = $this->Attendance->AttendanceDetail->find("all")->contain(["erp_users"])
					// ->where(["erp_users.is_resign"=>0,"approved"=>1,"month"=>$month,"year"=>$year,"erp_users.employee_at IN"=>$emp_at])->group(["AttendanceDetail.user_id"])->hydrate(false)->toArray();
			// }
			// else{	
				$users = $this->Attendance->AttendanceDetail->find("all")->contain(["erp_users"])
					->where(["erp_users.is_resign"=>0,"approved"=>1,$or])->group(["AttendanceDetail.user_id"])->hydrate(false)->toArray();
			//}\
			
			$this->set("users",$users);
			$this->set("from_month",$from_month);
			$this->set("from_year",$from_year);
			$this->set("to_month",$to_month);
			$this->set("to_year",$to_year);
		}
		
	}
	
	public function viewrecord()
	{
		if(empty($this->request->pass))
		{
			return false;
		}
		$user_id = $this->request->pass[0];
		$from_month = $this->request->pass[1];
		$from_year = $this->request->pass[2];
		$to_month = $this->request->pass[3];
		$to_year = $this->request->pass[4];
		
		$record = $this->Attendance->AttendanceDetail->find("all")
					->where(["user_id"=>$user_id,"approved"=>1,"month >="=>$from_month,"year >="=>$from_year,"month <="=>$to_month,"year <="=>$to_year])->order(['month'=>'ASC'])->hydrate(false)->toArray();
		//var_dump($record);die;
		$this->set("record",$record);
		$this->set("user_id",$user_id);
		$this->set("month",$from_month);
		$this->set("year",$from_year);
	}
	
	public function generaterecords()
	{
		/* if($this->role != "erphead" &&  $role != "hrmanager")
		{
			return $this->redirect(["controller"=>"Humanresource","action"=>"index"]);
			exit;
		} */
		
		// Employee List
		$user_tbl = TableRegistry::get("erp_users");
		$employee = $user_tbl->find()->where(["employee_no !="=>"","is_resign"=>0])->select("user_id")->hydrate(false)->toArray();
		$this->set("employee",$employee);
		
		if($this->request->is("post"))
		{
			// debug($this->request->data);die;
			$post = $this->request->data;
			/* if($this->role != "erphead" &&  $role != "hrmanager")
			{
				return $this->redirect(["controller"=>"Humanresource","action"=>"index"]);
				exit;
			} */
			
			$att_detail = TableRegistry::get("erp_attendance_detail");
			if($post['type'] != "all"  && isset($post['employee_id']) && !empty($post['employee_id']))
			{
				$users = $user_tbl->find()->where(["employee_no !="=>"","is_resign"=>0,"user_id IN"=>$post['employee_id']])->select("user_id")->hydrate(false)->toArray();
			}else{
				$users = $user_tbl->find()->where(["employee_no !="=>"","is_resign"=>0])->select("user_id")->hydrate(false)->toArray();
			}
			
			foreach($users as $user)
			{
				$user_ids[] = $user["user_id"];
			}
			
			$day = date("j",strtotime($post['date']));
			$month = date("n",strtotime($post['date']));		
			$year = date("Y",strtotime($post['date']));
			
			if(!empty($user_ids))
			{
				foreach($user_ids as $user_id)
				{
					$count = $att_detail->find()->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year])->count();
					if($count == 0)
					{
						$start_date = "01-".$month."-".$year;
						$start_time = strtotime($start_date);
						$end_time = strtotime("+1 month", $start_time);
						
						for($i=$start_time; $i<$end_time; $i+=86400)
						{
						   // $dates[] = date('Y-m-d D', $i);
						   $day = date('j', $i);
						   $day_name = date('l', $i);
						   if($day_name == "Sunday")
						   {
							 $corpo_emp = $this->ERPfunction->is_corporate_emp($user_id);
							 // $data["day_{$day}"] = ($corpo_emp) ? "H" : "A"; // On sunday for co.emp it absent
							 $data["day_{$day}"] = ($corpo_emp) ? "A" : "A";
						   }else{
							  $data["day_{$day}"]= "A";
						   }
						}
						$data["user_id"] = $user_id;
						$data["month"] = $month;
						$data["year"] = $year;
						$new = $att_detail->newEntity();
						$new = $att_detail->patchEntity($new,$data);
						$att_detail->save($new);
					}
				}
			}
			$this->Flash->success(__('Personnel added to attendance alert successfully.', null), 
								'default', 
								array('class' => 'success'));
			$this->redirect(array("controller"=>"humanresource","action"=>"index"));
		}
	}
	
	public function addattendance()
	{
		$user_tbl = TableRegistry::get("erp_users");
		$employee = $user_tbl->find()->where(["employee_no !="=>"","is_resign"=>0])->select("user_id")->hydrate(false)->toArray();
		$this->set("employee",$employee);
		
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data['generate']))
			{
				$post = $this->request->data;
				// debug($post);die;
				$month = date("n",strtotime($post['date']));
				$year =  date("Y",strtotime($post['date']));
				$date = "01-".$month."-".$year;
				$total_days = date("t",strtotime($date));
				
				$erp_users = TableRegistry::get("erp_users");
				$erp_attendance_detail = TableRegistry::get("erp_attendance_detail");
				
				$or = array();
				$or["erp_users.employee_at"] = ($post["project_id"] != "")?$post["project_id"]:NULL;
				$or["erp_users.pay_type IN"] = (!empty($post["pay_type"]) && $post["pay_type"][0] != "All" )?$post["pay_type"]:NULL;
				$or["erp_attendance_detail.month"] = ($month != "")?$month:NULL;
				$or["erp_attendance_detail.year"] = ($year != "")?$year:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				$or["erp_attendance_detail.approved"] = 0;
				$or["erp_attendance_detail.att_generated"] = 0;
				$or["erp_users.is_resign"] = 0;
				
				$result = $erp_attendance_detail->find()->select($erp_attendance_detail);
				$attendance_data = $result->innerjoin(
					["erp_users"=>"erp_users"],
					["erp_users.user_id = erp_attendance_detail.user_id"])
					->where($or)->select($erp_users)->hydrate(false)->toArray();
				$this->set("attendance_data",$attendance_data);
				$this->set("month",$month);
				$this->set("year",$year);
				$this->set("total_days",$total_days);
			}
			if(isset($this->request->data['save_attendance']))
			{
				$post = $this->request->data;
				$erp_attendance_detail = TableRegistry::get("erp_attendance_detail");
				
				$attendance = $post['attendance'];
				foreach($attendance["att_id"] as $key=>$value)
				{
					$row = $erp_attendance_detail->get($attendance["att_id"][$key]);
					$row->total_present = $attendance["present"][$key];
					$row->total_absent = $attendance["absent"][$key];
					$row->opening_pl = $attendance["opening"][$key];
					$row->new = $attendance["new"][$key];
					$row->total_holidays = $attendance["holiday"][$key];
					$row->man_pl = $attendance["manual"][$key];
					$row->used_pl = $attendance["used"][$key];
					$row->remaining_pl = $attendance["remaining"][$key];
					$row->payable_days = $attendance["payable_days"][$key];
					$row->att_generated = 1;
					$erp_attendance_detail->save($row);
				}
				$this->Flash->success(__('Attendance Added Successfully', null), 
								'default', 
								array('class' => 'success'));
				$this->redirect(array("controller"=>"attendance","action"=>"addattendance"));
			}
		}
	}
	
	/*
	public function attupdate()
	{
			$user_id = 109;
			$day = 01;
			$month = 4;
			$year = 2017;
			$detail_id = 
		
			$detail_id = $this->Attendance->AttendanceDetail->find("detail_id")->where(["user_id"=>109,"month"=>4,"year"=>2017])->hydrate(false)->toArray();
			echo $detail_id = $detail_id[0]["detail_id"];die;
		
			##################
			
			$last_month_balance = $this->ERPfunction->get_leave_balance($user_id,$month,$year);
			
			$monthly_leave = $this->ERPfunction->get_monthly_paid_leave($user_id);
			
			$total_present = $this->ERPfunction->get_monthly_total_attendace($user_id,$month,$year);			
			$presents = (isset($total_present["P"])) ? $total_present["P"] : 0; 
			$manual_present = (isset($total_present["manual_P"])) ? $total_present["manual_P"] : 0; 
			$presents = $presents + $manual_present;
			
			$half_days = (isset($total_present["HL"])) ? $total_present["HL"] : 0;
			$manual_half_days = (isset($total_present["manual_HL"])) ? $total_present["manual_HL"] : 0;
			$half_days = $half_days + $manual_half_days;
			
			$aa = (isset($total_present["AA"])) ? $total_present["AA"] : 0;
			$manual_aa = (isset($total_present["manual_AA"])) ? $total_present["manual_AA"] : 0;
			$aa = $aa + $manual_aa;
			
			$absents = $total_present["A"];
			$manual_absents = (isset($total_present["manual_A"])) ? $total_present["manual_A"] : 0;
			$absents = $absents + $manual_absents + ($half_days * 0.5);			
		
			$holidays = isset($total_present["H"]) ? $total_present["H"] : 0;
			$manual_holidays = (isset($total_present["manual_H"])) ? $total_present["manual_H"] : 0;
			$holidays = $holidays + $manual_holidays;
			
			if($monthly_leave == "NA")
			{
				$last_month_balance = 0;
				$monthly_leave = 0;
				$used_pl = 0;
				$remaining_pl = 0;
				$date = "{$year}-{$month}-{$day}";
				$date = date("Y-m-d",strtotime($date));
				$payable_days = date("t",strtotime($date));			
			}
			else
			{			
				$pl_balance = $last_month_balance + $monthly_leave;
				
				$total_present = ($presents != 0  ) ? $presents + $holidays : 0;
				$total_present = $total_present + ($half_days * 0.5);
				
				$total_absents = $absents + $aa;
				
				$remaining_pl = $pl_balance - $total_absents;
				
				if($remaining_pl >= 0 )
				{
					$used_pl = $total_absents;
				}
				else{
					$remaining_pl = 0;
					$used_pl = $pl_balance;
				}
				$payable_days = $total_present + $used_pl;
			}
			######################################
			
			$row = $this->Attendance->AttendanceDetail->get($detail_id);
			$row->total_present = $presents + ($half_days * 0.5);
			$row->total_absent = $absents;
			$row->total_holidays = $holidays;
			$row->total_aa = $aa;
			$row->opening_pl = $last_month_balance;
			$row->new = $monthly_leave;
			$row->used_pl = $used_pl;
			$row->remaining_pl = $remaining_pl;
			$row->payable_days = $payable_days;
			
			$this->Attendance->AttendanceDetail->save($row);
			
			$this->autoRender = false;
				
	}
	*/

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
	
	
	
}