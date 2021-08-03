<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\Mailer\Email;
use mPDF;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;

class ProjectsController extends AppController
{
	public function initialize()
   {
       parent::initialize();
		
		
		$this->loadComponent('Flash');
		$this->loadComponent('ERPfunction');
		$this->user_id=$this->request->session()->read('user_id');
		$this->rights = $this->Usermanage->projects_access_right();
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$action = $this->request->action;
		if(isset($this->rights[$action][$this->role]))
		{
			$is_capable = $this->rights[$action][$this->role];	
		}
		else
		{	$is_capable = 0;	}
		
		$this->set('is_capable',$is_capable);
   }
	
	public function index() {
		$conn = ConnectionManager::get('default');	
		$role = $this->Usermanage->get_user_role($this->user_id);
		 $this->set('role',$role);	
	
    }
	
	public function editprojectlist()
    {
		$conn = ConnectionManager::get('default');
		$erp_projects = TableRegistry::get('erp_projects'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		$role = $this->Usermanage->get_user_role($this->user_id);
		// if($role == 'projectdirector' || $role == 'contractadmin' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager'){ 
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids)){ 
				$result = $conn->execute('select * from  erp_projects where project_id in ('.implode(',',$projects_ids ).')');			
			}else{
				$result=array();
			}
		}else{
			$result = $conn->execute('select * from  erp_projects');	
		}
		
		
		/* $result = $erp_projects->find(); */
		$this->set('projects',$result);		
		
		$projects_list = $this->Usermanage->access_project($this->user_id);
		/* $projects_list = $this->ERPfunction->get_projects(); */
		$this->set('projects_list',$projects_list);
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
				
				$or = array();				
				/* $or["project_id"] = ($post["project_id"]!="all")?"{$post["project_id"]}":NULL; */
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0]!="all")?$post["project_id"]:NULL;
				$or["project_code LIKE"] = (!empty($post["project_code"]))?"%{$post["project_code"]}%":NULL;
				$or["project_status LIKE"] = (!empty($post["project_status"]))?"%{$post["project_status"]}%":NULL;
				$or["state LIKE"] = (!empty($post["state"]))?"%{$post["state"]}%":NULL;
				$or["client_name LIKE"] = (!empty($post["refno"]))?"%{$post["client_name"]}%":NULL;	
				
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($or);die;
				$search_data = $erp_projects->find("all")->where($or);
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set("projects",$search_data);
			}
		}
    }
	
	public function viewprojectlist()
    {
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}elseif (strpos($previous_url, 'contract') !== false) {
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}else{
			$back_url = 'Projects';
			$back_page = 'index';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		
		 $erp_projects = TableRegistry::get('erp_projects'); 
		/*$result = $erp_projects->find();
		$this->set('projects',$result); */
		
		$projects_list = $this->Usermanage->all_access_project($this->user_id);
		$this->set('projects',$projects_list);		
		$this->set('projects_list',$projects_list);		
		$this->set('role',$this->role);		
		$role = $this->role;
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
				$user_id = $this->user_id;
				$projects_ids = $this->Usermanage->users_project($user_id);
				
				$or = array();		
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["project_code LIKE"] = (!empty($post["project_code"]))?"%{$post["project_code"]}%":NULL;
				$or["project_status IN"] = (!empty($post["project_status"]) && $post["project_status"]!="all")?$post["project_status"]:NULL;
				$or["state LIKE"] = (!empty($post["state"]))?"%{$post["state"]}%":NULL;
				$or["client_name LIKE"] = (!empty($post["client_name"]))?"%{$post["client_name"]}%":NULL;	
				$or["contract_start_date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
				//$or["contract_end_date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["date_of_information >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["exten_cmp_date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["ref_date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["actual_cmp_date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["contract_end_date <="] = ($post["date_to"] != "")?date("Y-m-d",strtotime($post["date_to"])):NULL;
				
				
				// if($post["date_from"] != "")
				// {
					// $from_date = date("Y-m-d",strtotime($post["date_from"]));
					// $or_wh["contract_start_date >="] = $from_date;
					// $or_wh["exten_cmp_date >="] = $from_date;
					// $or_wh["date_of_information >="] = $from_date;
					// $or_wh["ref_date >="] = $from_date;
					// $or_wh["actual_cmp_date >="] = $from_date;
				// }
				// if($post["date_to"] != "")
				// {
					// $to_date = date("Y-m-d",strtotime($post["date_from"]));
					// $or_wh["contract_start_date <="] = $to_date;
					// $or_wh["exten_cmp_date <="] = $to_date;
					// $or_wh["date_of_information <="] = $to_date;
					// $or_wh["ref_date <="] = $to_date;
					// $or_wh["actual_cmp_date <="] = $to_date;
				// }
				
				
				if($or["project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						$or["project_id IN"] = $projects_ids;
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				// $keys = array_keys($or_wh,"");				
				// foreach ($keys as $k)
				// {unset($or_wh[$k]);}
				
				
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids)){
						$project_list = $erp_projects->find()->where([$or,"actual_amount"=>0])->hydrate(false)->toArray();
					}else{
						$project_list=array();
					}
				}
				else
				{
					$project_list = $erp_projects->find()->where([$or,"actual_amount"=>0])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				
				$this->set("projects",$project_list);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "projects.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("projectlistpdf");
			}			
		}		
    }
	
	public function add($project_id = Null)
    {
		$erp_projects = TableRegistry::get('erp_projects'); 
		$erp_users = TableRegistry::get('erp_users'); 
		$project_manager = $erp_users->find()->where(array('designation'=>'42')); /*23 for local*/
		$this->set('project_manager',$project_manager);
		$constructionmanager = $erp_users->find()->where(array('designation'=>'71')); /*24 for local*/
		$this->set('constructionmanager',$constructionmanager);
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set("role",$role);
		
		if(isset($project_id))
		{	
			$user_action = 'edit';			
			$project_data = $erp_projects->get($project_id);			
			$this->set('project_data',$project_data);
			$this->set('form_header','Edit Project');
			$this->set('button_text','Update Project');
		}
		else
		{
			$user_action = 'insert';
			$this->set('form_header','Add Project');
			$this->set('button_text','Add Project');
		}
		$this->set('user_action',$user_action);	
		if($this->request->is('post'))
		{	
		
			if(isset($_FILES['attach_file'])){
				$file =$_FILES['attach_file']["name"];
				$size = count($file);
				for($i=0;$i<$size;$i++) {
					$parts = pathinfo($_FILES['attach_file']['name'][$i]);
				}
				$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
				// debug($ext);die;
				if($ext != 0) {
					
					// debug($this->request->data);
			$this->set('project_data',$this->request->data);
				
			// if(!empty($this->request->data['attach_file']))
			// {
				@$this->request->data['attach_label'] = json_encode($this->request->data['attach_label']);
				$file = $this->ERPfunction->upload_file("attach_file");					
				$this->request->data['attach_file'] = json_encode($file);
				// debug($file);
				// debug(json_encode($file));				
			// }
			
			// $this->request->data['attach_price_bid']= $this->ERPfunction->upload_image('attach_price_bid',$this->request->data['old_attach_price_bid']);
			// $this->request->data['attach_specification']= $this->ERPfunction->upload_image('attach_specification',$this->request->data['old_attach_specification']);
			// $this->request->data['attach_makelist']= $this->ERPfunction->upload_image('attach_makelist',$this->request->data['old_attach_makelist']);
			// $this->request->data['attach_contract_document']= $this->ERPfunction->upload_image('attach_contract_document',$this->request->data['old_attach_contract_document']);
		
			$this->request->data['contract_start_date']= $this->ERPfunction->set_date($this->request->data['contract_start_date']);
			$this->request->data['contract_end_date']= $this->ERPfunction->set_date($this->request->data['contract_end_date']);
			$this->request->data['exten_cmp_date']= $this->ERPfunction->set_date($this->request->data['exten_cmp_date']);
			$this->request->data['date_of_information']= $this->ERPfunction->set_date($this->request->data['date_of_information']);
			$this->request->data['actual_cmp_date']= ($this->request->data['actual_cmp_date'] != 0) ? $this->ERPfunction->set_date($this->request->data['actual_cmp_date']) : "";
			$this->request->data['ref_date']= $this->ERPfunction->set_date($this->request->data['ref_date']);
			// $this->request->data['created_date']=date('Y-m-d H:i:s');
			// $this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;		
			
			if($user_action == 'edit')
			{
				$updated_data = $this->request->data;
				// debug($updated_data);die;
				$old_files = array();
				if(isset($updated_data["old_attach_file"]))
				{
					$old_files = $updated_data["old_attach_file"];				
				}
				@$updated_data['attach_label'] = trim(json_encode($updated_data["attach_label"]),'\"');
				if(isset($_FILES["attach_file"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("attach_file");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$updated_data['attach_file'] = json_encode($old_files);
				// debug(stripslashes($updated_data["attach_label"]));
				// debug($updated_data['attach_file']);die;
				$updated_data["last_edit"] = date("Y-m-d H:i:s");
				$updated_data["last_edit_by"] = $this->request->session()->read('user_id');
				$actual_cmp_date = $updated_data['actual_cmp_date'];
				$actual_amount = $updated_data['actual_amount'];
				/* $updated_data["project_status"] = ($actual_cmp_date != "" && $actual_amount != 0 ) ? "Completed":"On Going"; */
				
				if($actual_cmp_date == "")
				{
					unset($updated_data["actual_cmp_date"]);
				}
				
				// debug($updated_data);die;
				$project_data = $erp_projects->patchEntity($project_data,$updated_data);
				
				if($erp_projects->save($project_data))
				{
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				}
				$this->redirect(array("controller" => "Projects","action" => "index"));	
			}
			else{
				if($this->ERPfunction->is_duplicate_project_code($this->request->data('project_code')))
				{
					$this->Flash->success(__('Please enter unique project code', null), 
								'default', 
								array('class' => 'success'));
				}
				else
				{	
					$table_field = $erp_projects->newEntity();
					$this->request->data['created_date']=date('Y-m-d H:i:s');
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					$actual_cmp_date = $this->request->data['actual_cmp_date'];
					$actual_amount = $this->request->data['actual_amount'];
					/* $this->request->data["project_status"] = ($actual_cmp_date != "" && $actual_amount != 0 ) ? "Completed":"On Going"; */
					
					$new_data=$erp_projects->patchEntity($table_field,$this->request->data);
					if($erp_projects->save($new_data))
					{
						$this->Flash->success(__('Record Insert Successfully', null), 
									'default', 
									array('class' => 'success'));
					}
					$this->redirect(array("controller" => "Projects","action" => "viewprojectlist"));	
				}
			}
				
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				// debug($this->request->data);
			$this->set('project_data',$this->request->data);
				
			// if(!empty($this->request->data['attach_file']))
			// {
				@$this->request->data['attach_label'] = json_encode($this->request->data['attach_label']);
				$file = $this->ERPfunction->upload_file("attach_file");					
				$this->request->data['attach_file'] = json_encode($file);
				// debug($file);
				// debug(json_encode($file));				
			// }
			
			// $this->request->data['attach_price_bid']= $this->ERPfunction->upload_image('attach_price_bid',$this->request->data['old_attach_price_bid']);
			// $this->request->data['attach_specification']= $this->ERPfunction->upload_image('attach_specification',$this->request->data['old_attach_specification']);
			// $this->request->data['attach_makelist']= $this->ERPfunction->upload_image('attach_makelist',$this->request->data['old_attach_makelist']);
			// $this->request->data['attach_contract_document']= $this->ERPfunction->upload_image('attach_contract_document',$this->request->data['old_attach_contract_document']);
		
			$this->request->data['contract_start_date']= $this->ERPfunction->set_date($this->request->data['contract_start_date']);
			$this->request->data['contract_end_date']= $this->ERPfunction->set_date($this->request->data['contract_end_date']);
			$this->request->data['exten_cmp_date']= $this->ERPfunction->set_date($this->request->data['exten_cmp_date']);
			$this->request->data['date_of_information']= $this->ERPfunction->set_date($this->request->data['date_of_information']);
			$this->request->data['actual_cmp_date']= ($this->request->data['actual_cmp_date'] != 0) ? $this->ERPfunction->set_date($this->request->data['actual_cmp_date']) : "";
			$this->request->data['ref_date']= $this->ERPfunction->set_date($this->request->data['ref_date']);
			// $this->request->data['created_date']=date('Y-m-d H:i:s');
			// $this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;		
			
			if($user_action == 'edit')
			{
				$updated_data = $this->request->data;
				// debug($updated_data);die;
				$old_files = array();
				if(isset($updated_data["old_attach_file"]))
				{
					$old_files = $updated_data["old_attach_file"];				
				}
				@$updated_data['attach_label'] = trim(json_encode($updated_data["attach_label"]),'\"');
				if(isset($_FILES["attach_file"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("attach_file");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$updated_data['attach_file'] = json_encode($old_files);
				// debug(stripslashes($updated_data["attach_label"]));
				// debug($updated_data['attach_file']);die;
				$updated_data["last_edit"] = date("Y-m-d H:i:s");
				$updated_data["last_edit_by"] = $this->request->session()->read('user_id');
				$actual_cmp_date = $updated_data['actual_cmp_date'];
				$actual_amount = $updated_data['actual_amount'];
				/* $updated_data["project_status"] = ($actual_cmp_date != "" && $actual_amount != 0 ) ? "Completed":"On Going"; */
				
				if($actual_cmp_date == "")
				{
					unset($updated_data["actual_cmp_date"]);
				}
				
				// debug($updated_data);die;
				$project_data = $erp_projects->patchEntity($project_data,$updated_data);
				
				if($erp_projects->save($project_data))
				{
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				}
				$this->redirect(array("controller" => "Projects","action" => "index"));	
			}
			else{
				if($this->ERPfunction->is_duplicate_project_code($this->request->data('project_code')))
				{
					$this->Flash->success(__('Please enter unique project code', null), 
								'default', 
								array('class' => 'success'));
				}
				else
				{	
					$table_field = $erp_projects->newEntity();
					$this->request->data['created_date']=date('Y-m-d H:i:s');
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					$actual_cmp_date = $this->request->data['actual_cmp_date'];
					$actual_amount = $this->request->data['actual_amount'];
					/* $this->request->data["project_status"] = ($actual_cmp_date != "" && $actual_amount != 0 ) ? "Completed":"On Going"; */
					
					$new_data=$erp_projects->patchEntity($table_field,$this->request->data);
					if($erp_projects->save($new_data))
					{
						$this->Flash->success(__('Record Insert Successfully', null), 
									'default', 
									array('class' => 'success'));
					}
					$this->redirect(array("controller" => "Projects","action" => "viewprojectlist"));	
				}
			}
			}
		
			
		}	
    }
	
	public function edit($project_id = Null) {
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		// debug($projects_ids);die;
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($role == 'ceo' || $role == 'projectdirector' || $role == 'md') {
			if(!in_array($project_id,$projects_ids)) {
				$this->set("is_capable",false);
			}		
		}
		
		
		$erp_projects = TableRegistry::get('erp_projects'); 
		$erp_users = TableRegistry::get('erp_users'); 
		$project_manager = $erp_users->find()->where(array('designation'=>'23'));
		$this->set('project_manager',$project_manager);
		$constructionmanager = $erp_users->find()->where(array('designation'=>'24'));
		$this->set('constructionmanager',$constructionmanager);
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set("role",$role);
		
		if(isset($project_id))
		{	
			$user_action = 'edit';			
			$project_data = $erp_projects->get($project_id);
			$this->set("role",$role);			
			$this->set('project_data',$project_data);
			$this->set('form_header','Edit Project');
			$this->set('button_text','Update Project');
		}
		$this->set('user_action',$user_action);	
			
		if($this->request->is('post'))
		{	
			if(isset($_FILES['attach_file'])){
				$file =$_FILES['attach_file']["name"];
				$size = count($file);
				for($i=0;$i<$size;$i++) {
					$parts = pathinfo($_FILES['attach_file']['name'][$i]);
				}
				$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
				// debug($ext);die;
				if($ext != 0) {
					// debug($this->request->data);
			$this->set('project_data',$this->request->data);
			if(isset($attach_file)) {
				@$this->request->data['attach_label'] = json_encode($this->request->data['attach_label']);
				$file = $this->ERPfunction->upload_file("attach_file");					
				$this->request->data['attach_file'] = json_encode($file);
			}
			$this->request->data['contract_start_date']= $this->ERPfunction->set_date($this->request->data['contract_start_date']);
			$this->request->data['contract_end_date']= $this->ERPfunction->set_date($this->request->data['contract_end_date']);
			$this->request->data['exten_cmp_date']= $this->ERPfunction->set_date($this->request->data['exten_cmp_date']);
			$this->request->data['date_of_information']= $this->ERPfunction->set_date($this->request->data['date_of_information']);
			$this->request->data['actual_cmp_date']= ($this->request->data['actual_cmp_date'] != 0) ? $this->ERPfunction->set_date($this->request->data['actual_cmp_date']) : "";
			$this->request->data['ref_date']= $this->ERPfunction->set_date($this->request->data['ref_date']);
			
			$this->request->data['status']=1;	
				
			if($user_action == 'edit')
			{
				$updated_data = $this->request->data;
				// debug($updated_data);die;
				$old_files = array();
				if(isset($updated_data["old_attach_file"]))
				{
					$old_files = $updated_data["old_attach_file"];				
				}
				@$updated_data['attach_label'] = trim(json_encode($updated_data["attach_label"]),'\"');
				if(isset($_FILES["attach_file"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("attach_file");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$updated_data['attach_file'] = json_encode($old_files);
				// debug(stripslashes($updated_data["attach_label"]));
				// debug($updated_data['attach_file']);die;
				$updated_data["last_edit"] = date("Y-m-d H:i:s");
				$updated_data["last_edit_by"] = $this->request->session()->read('user_id');
				$actual_cmp_date = $updated_data['actual_cmp_date'];
				$actual_amount = $updated_data['actual_amount'];
				/* $updated_data["project_status"] = ($actual_cmp_date != "" && $actual_amount != 0 ) ? "Completed":"On Going"; */
				
				if($actual_cmp_date == "")
				{
					unset($updated_data["actual_cmp_date"]);
				}
				
				// debug($updated_data);die;
				$project_data = $erp_projects->patchEntity($project_data,$updated_data);
				
				if($erp_projects->save($project_data))
				{
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				}
				$this->redirect(array("controller" => "Projects","action" => "index"));	
			}
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				// debug($this->request->data);
			$this->set('project_data',$this->request->data);
			if(isset($attach_file)) {
				@$this->request->data['attach_label'] = json_encode($this->request->data['attach_label']);
				$file = $this->ERPfunction->upload_file("attach_file");					
				$this->request->data['attach_file'] = json_encode($file);
			}
			$this->request->data['contract_start_date']= $this->ERPfunction->set_date($this->request->data['contract_start_date']);
			$this->request->data['contract_end_date']= $this->ERPfunction->set_date($this->request->data['contract_end_date']);
			$this->request->data['exten_cmp_date']= $this->ERPfunction->set_date($this->request->data['exten_cmp_date']);
			$this->request->data['date_of_information']= $this->ERPfunction->set_date($this->request->data['date_of_information']);
			$this->request->data['actual_cmp_date']= ($this->request->data['actual_cmp_date'] != 0) ? $this->ERPfunction->set_date($this->request->data['actual_cmp_date']) : "";
			$this->request->data['ref_date']= $this->ERPfunction->set_date($this->request->data['ref_date']);
			
			$this->request->data['status']=1;	
				
			if($user_action == 'edit')
			{
				$updated_data = $this->request->data;
				// debug($updated_data);die;
				$old_files = array();
				if(isset($updated_data["old_attach_file"]))
				{
					$old_files = $updated_data["old_attach_file"];				
				}
				@$updated_data['attach_label'] = trim(json_encode($updated_data["attach_label"]),'\"');
				if(isset($_FILES["attach_file"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("attach_file");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$updated_data['attach_file'] = json_encode($old_files);
				// debug(stripslashes($updated_data["attach_label"]));
				// debug($updated_data['attach_file']);die;
				$updated_data["last_edit"] = date("Y-m-d H:i:s");
				$updated_data["last_edit_by"] = $this->request->session()->read('user_id');
				$actual_cmp_date = $updated_data['actual_cmp_date'];
				$actual_amount = $updated_data['actual_amount'];
				/* $updated_data["project_status"] = ($actual_cmp_date != "" && $actual_amount != 0 ) ? "Completed":"On Going"; */
				
				if($actual_cmp_date == "")
				{
					unset($updated_data["actual_cmp_date"]);
				}
				
				// debug($updated_data);die;
				$project_data = $erp_projects->patchEntity($project_data,$updated_data);
				
				if($erp_projects->save($project_data))
				{
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				}
				$this->redirect(array("controller" => "Projects","action" => "index"));	
			}
			}
			
		}
	}
	
	public function viewproject($project_id = Null)
    {		
		$erp_projects = TableRegistry::get('erp_projects'); 
		$erp_users = TableRegistry::get('erp_users'); 
		// $project_manager = $erp_users->find()->where(array('role'=>'projectdirector'));
		// $this->set('project_manager',$project_manager);
		// $constructionmanager = $erp_users->find()->where(array('role'=>'constructionmanager'));
		// $this->set('constructionmanager',$constructionmanager);
		$project_manager = $erp_users->find()->where(array('designation'=>'23'));
		$this->set('project_manager',$project_manager);
		$constructionmanager = $erp_users->find()->where(array('designation'=>'24'));
		$this->set('constructionmanager',$constructionmanager);
		
		if(isset($project_id))
		{	
			$user_action = 'edit';			
			$project_data = $erp_projects->get($project_id);			
			$this->set('project_data',$project_data);
			$this->set('form_header','View Project');
		}
		else
		{
			$user_action = 'insert';
			$this->set('form_header','Add Project');
			$this->set('button_text','Add Project');
		}
		$this->set('user_action',$user_action);	
			
    }	
	
	public function delete($id){
			$this->request->is(['post','delete']);
			$erp_projects = TableRegistry::get('erp_projects'); 
			$row_delte=$erp_projects->get($id);
			if($erp_projects->delete($row_delte)){
			$this->Flash->success(__('Record Successfully Deleted'));
			return $this->redirect(['controller'=>'projects','action'=>'index']);
		}
    }
	
	public function openingstock($project_id = Null)
    {
		$erp_projects = TableRegistry::get('erp_projects'); 
		$result = $erp_projects->find();
		$this->set('projects',$result);
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);	
		if(isset($project_id))
		{			
			$user_action = 'edit';			
			$project_data = $erp_projects->get($project_id);			
			$this->set('project_data',$project_data);
			$this->set('form_header','Edit Project');
			$this->set('button_text','Update Project');
		}
		else
		{
			$user_action = 'insert';
			$this->set('form_header','Add Project Opening Stock');
			$this->set('button_text','Add Project Opening Stock');
		}
		$this->set('user_action',$user_action);
		
		if($this->request->is('post'))
		{	
			$erp_project_opening_stock = TableRegistry::get('erp_project_opening_stock'); 
			$material_items = $this->request->data('material');
			foreach($material_items['material_id'] as $key => $data)
			{
				$save_data['created_date']=date('Y-m-d H:i:s');			
				$save_data['created_by']=$this->request->session()->read('user_id');
				$save_data['project_id'] =  $this->request->data('project_id');			
				$save_data['material_id'] =  $material_items['material_id'][$key];
				$save_data['quantity'] =  $material_items['quantity'][$key];
				$save_data['note'] =  $material_items['note'][$key];						
				$this->ERPfunction->stock_add($this->request->data('project_id'),$save_data['material_id'],$save_data['quantity']);		
				$entity_data = $erp_project_opening_stock->newEntity();			
				$material_data=$erp_project_opening_stock->patchEntity($entity_data,$save_data);
				$erp_project_opening_stock->save($material_data);						
			}	
			$this->Flash->success(__('Record Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(array("controller" => "Projects","action" => "index"));		
		}	
    }
	
	public function viewstock($project_id)
    {
		$erp_projects = TableRegistry::get('erp_projects'); 
		$project_data = $erp_projects->get($project_id);
		$this->set('projct_title',$project_data['project_name']);
		$erp_stock = TableRegistry::get('erp_stock'); 
		$result = $erp_stock->find()->where(['project_id'=>$project_id]);
		$this->set('material_list',$result);
    }
	
	public function printproject($project_id)
	{
		$erp_projects = TableRegistry::get('erp_projects'); 
		$erp_users = TableRegistry::get('erp_users'); 
		$project_manager = $erp_users->find()->where(array('role'=>'projectdirector'));
		$this->set('project_manager',$project_manager);
		$constructionmanager = $erp_users->find()->where(array('role'=>'constructionmanager'));
		$this->set('constructionmanager',$constructionmanager);
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		if(isset($project_id))
		{	
			$user_action = 'edit';			
			$project_data = $erp_projects->get($project_id);			
			$this->set('project_data',$project_data);
			$this->set('form_header','Edit Project');
			$this->set('button_text','Update Project');
		}
		else
		{
			$user_action = 'insert';
			$this->set('form_header','Add Project');
			$this->set('button_text','Add Project');
		}
		$this->set('user_action',$user_action);	
		
	}
	
	public function addcontractnotification()
    {
		$this->set("back","index");
		$this->set("form_header","Add Tender/Contract Notification");
				
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('project_data',$project_list);
		
		if($this->request->is("post"))
		{
			$data = $this->request->data;
			
			$erp_contract_notification = TableRegistry::get("erp_contract_notification");
			$row = $erp_contract_notification->newEntity();
			$post['project_id'] = $data['project_id'];
			$post["project_code"] = $data['project_code'];
			$post["message"] = $data['messages'];
			$post["event_date"] = date("Y-m-d",strtotime($data['event_date']));
			$post["time_before"] = $data['notification_time'];
			$post["event_type"] = $data['event_type'];
			$post["created_by"] = $this->request->session()->read('user_id');
			$post["created_date"] = date("Y-m-d");
			$row = $erp_contract_notification->patchEntity($row,$post);
			if($erp_contract_notification->save($row))
			{
				$this->Flash->success(__('Record Saved Successfully', null), 
                            'default', 
                             array('class' => 'success'));
				return $this->redirect(['action'=>'index']);
			}
		}
	}
	
	public function contractnotificationlist()
	{
		$erp_contract_notification = TableRegistry::get('erp_contract_notification');
		$this->set("form_header","Tender/Contract Notification Records");
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set('projects',$projects);
				
		$search_data = $erp_contract_notification->find("all")->where(['OR'=>[["event_type !="=>"single"],["event_date >="=>date("Y-m-d")]]])->hydrate(false)->toArray();	
		$this->set("search_data",$search_data);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$or = array();				
				
				$or["erp_contract_notification.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
				
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				$search_data = $erp_contract_notification->find("all")->where([$or])->hydrate(false)->toArray();	
				$this->set("search_data",$search_data);
				$this->set("data",$post);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "Contract Notification.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("contractnotificationpdf");
			}
		}
	}
	
	public function editcontractnotification($id)
	{
		$this->set("form_header","Edit Tender/Contract Notification");
		$erp_contract_notification = TableRegistry::get('erp_contract_notification');
		
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('project_data',$project_list);
		
		$data = $erp_contract_notification->get($id);		
		$this->set("data",$data);
		
		if($this->request->is("post"))
		{
			$data = $this->request->data;
			
			$erp_contract_notification = TableRegistry::get("erp_contract_notification");
			$row = $erp_contract_notification->get($id);
			$post['project_id'] = $data['project_id'];
			$post["project_code"] = $data['project_code'];
			$post["message"] = $data['messages'];
			$post["event_date"] = date("Y-m-d",strtotime($data['event_date']));
			if(date("Y-m-d",strtotime($row["event_date"])) != date("Y-m-d",strtotime($data['event_date'])))
			{
				$post["last_mailed_date"] = "";
			}
			$post["time_before"] = $data['notification_time'];
			$post["event_type"] = $data['event_type'];
			$post["updated_by"] = $this->request->session()->read('user_id');
			$post["updated_date"] = date("Y-m-d");
			$row = $erp_contract_notification->patchEntity($row,$post);
			if($erp_contract_notification->save($row))
			{
				$this->Flash->success(__('Record Saved Successfully', null), 
                            'default', 
                             array('class' => 'success'));
				echo "<script>window.close();</script>";
			}
		}
	}
	
	public function viewcontractnotification($id)
	{
		$this->set("form_header","Edit Tender/Contract Notification");
		$erp_contract_notification = TableRegistry::get('erp_contract_notification');
		
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('project_data',$project_list);
		
		$data = $erp_contract_notification->get($id);		
		$this->set("data",$data);
	}
	
	public function deletecontractnotification($id)
	{
		$erp_contract_notification = TableRegistry::get('erp_contract_notification');
		$row = $erp_contract_notification->get($id);
		if($erp_contract_notification->delete($row))
		{
			$this->Flash->success(__('Record Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			return $this->redirect(['action'=>'contractnotificationlist']);
		}
		
	}
	
	public function addpersonalnotification()
    {
		$this->set("back","index");
		$this->set("form_header","Add Personal Notification");
			
		if($this->request->is("post"))
		{
			$data = $this->request->data;
			
			$erp_personal_notification = TableRegistry::get("erp_personal_notification");
			$row = $erp_personal_notification->newEntity();
			$post["message"] = $data['messages'];
			$post["event_date"] = date("Y-m-d",strtotime($data['event_date']));
			$post["time_before"] = $data['notification_time'];
			$post["event_type"] = $data['event_type'];
			$post["created_by"] = $this->request->session()->read('user_id');
			$post["created_date"] = date("Y-m-d");
			$row = $erp_personal_notification->patchEntity($row,$post);
			if($erp_personal_notification->save($row))
			{
				$this->Flash->success(__('Record Saved Successfully', null), 
                            'default', 
                             array('class' => 'success'));
				return $this->redirect(['action'=>'index']);
			}
		}
	}
	
	public function personalnotificationlist()
	{
		$erp_personal_notification = TableRegistry::get('erp_personal_notification');
		$this->set("form_header","Personal Notification Records");
		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		
		$search_data = $erp_personal_notification->find("all")->where(['OR'=>[["event_type !="=>"single"],["event_date >="=>date("Y-m-d")]]])->hydrate(false)->toArray();	
		$this->set("search_data",$search_data);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "Personal Notification.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("personalnotificationpdf");
			}
		}
	}
	
	public function editpersonalnotification($id)
	{
		$this->set("form_header","Edit Personal Notification");
		$erp_personal_notification = TableRegistry::get('erp_personal_notification');
				
		$data = $erp_personal_notification->get($id);		
		$this->set("data",$data);
		
		if($this->request->is("post"))
		{
			$data = $this->request->data;
			
			$erp_personal_notification = TableRegistry::get("erp_personal_notification");
			$row = $erp_personal_notification->get($id);
			$post["message"] = $data['messages'];
			$post["event_date"] = date("Y-m-d",strtotime($data['event_date']));
			if(date("Y-m-d",strtotime($row["event_date"])) != date("Y-m-d",strtotime($data['event_date'])))
			{
				$post["last_mailed_date"] = "";
			}
			$post["time_before"] = $data['notification_time'];
			$post["event_type"] = $data['event_type'];
			$post["updated_by"] = $this->request->session()->read('user_id');
			$post["updated_date"] = date("Y-m-d");
			$row = $erp_personal_notification->patchEntity($row,$post);
			if($erp_personal_notification->save($row))
			{
				$this->Flash->success(__('Record Saved Successfully', null), 
                            'default', 
                             array('class' => 'success'));
				echo "<script>window.close();</script>";
			}
		}
	}
	
	public function viewpersonalnotification($id)
	{
		$this->set("form_header","View Personal Notification");
		$erp_personal_notification = TableRegistry::get('erp_personal_notification');
				
		$data = $erp_personal_notification->get($id);		
		$this->set("data",$data);
	}
	
	public function deletepersonalnotification($id)
	{
		$erp_personal_notification = TableRegistry::get('erp_personal_notification');
		$row = $erp_personal_notification->get($id);
		if($erp_personal_notification->delete($row))
		{
			$this->Flash->success(__('Record Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			return $this->redirect(['action'=>'personalnotificationlist']);
		}
		
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

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}

?>