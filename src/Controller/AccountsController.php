<?php


namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry; 
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class AccountsController extends AppController
{

	public function initialize(){
		parent::initialize();		
		$this->loadComponent('Flash');
		$this->loadComponent('ERPfunction');
		$this->user_id=$this->request->session()->read('user_id');
		$this->rights = $this->Usermanage->account_access_right();
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$action = $this->request->action;
		if(isset($this->rights[$action][$this->role]))
		{
			$is_capable = $this->rights[$action][$this->role];	
		}
		else
			$is_capable = 0;
		$this->set('is_capable',$is_capable);
		$role = $this->role;
		$this->set('role',$role);
	}

    public function index(){
		$role = $this->role;
		$this->set('role',$role);
    }

	public function accountlist()
	{	
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}elseif (strpos($previous_url, 'inventory') !== false) {
			$back_url = 'inventory';
			$back_page = 'index';
		}elseif(strpos($previous_url, 'contract') !== false) {
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}elseif(strpos($previous_url, 'purchase') !== false) {
			$back_url = 'purchase';
			$back_page = 'index';
		}else{
			$back_url = 'accounts';
			$back_page = 'index';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->role;
		
		/* $projects = $this->ERPfunction->get_projects(); */
		if($role == "deputymanagerelectric")
		{
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{	
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}
		$this->set('projects',$projects);
		$this->set('role',$role);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$erp_inward_bill_register = TableRegistry::get('erp_inward_bill'); 
		/* $inward_bill_list = $erp_inward_bill_register->find()->where(array('status_inward'=>'completed')); */
		
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids)){
				$inward_bill_list = $erp_inward_bill_register->find()->where(["project_id IN"=>$projects_ids]);	
			}else{
				$inward_bill_list=array();
			}
		}else{
			$inward_bill_list = $erp_inward_bill_register->find("all");
		}	
		
		//$this->set('inward_bill_info',$inward_bill_list);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data['go']))
			{
			$post = $this->request->data;
			// debug($post);die;
			
			$or = array();				
			
			//$or["date >="] = (!empty($post["date_from"]))?"%{$post["date_from"]}%":NULL;
			$or["date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
			$or["date <="] = ($post["date_to"] != "")?date("Y-m-d",strtotime($post["date_to"])):NULL;
			$or["bill_date >="] = ($post["bill_date_from"] != "")?date("Y-m-d",strtotime($post["bill_date_from"])):NULL;
			$or["bill_date <="] = ($post["bill_date_to"] != "")?date("Y-m-d",strtotime($post["bill_date_to"])):NULL;
			$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All")?$post["project_id"]:NULL;
			$or["party_name IN"] = (!empty($post["party_id"]) && $post["party_id"][0] != "All")?$post["party_id"]:NULL;
			$or["bill_type IN"] = (!empty($post["bill_type"]) && $post["bill_type"][0] != "All" )?$post["bill_type"]:NULL;
			$or["payment_method IN"] = (!empty($post["payment_mod"]) && $post["payment_mod"][0] != "All")?$post["payment_mod"]:NULL;
			$or["inward_bill_no LIKE"] = (!empty($post["bill_no"]))?"%{$post["bill_no"]}%":NULL;
			$or["invoice_no LIKE"] = (!empty($post["invoice_no"]))?"%{$post["invoice_no"]}%":NULL;
			$or["po_no LIKE"] = (!empty($post["powono"]))?"%{$post["powono"]}%":NULL;
			$or["status_inward IN"] = (!empty($post["pay_status"]) && $post["pay_status"][0] != "All")?$post["pay_status"]:NULL;
			//var_dump($post["bill_type"]);die;
			if($or["project_id IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}
			}
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			//var_dump($or);die;
			$inward_bill_list = $erp_inward_bill_register->find()->where([$or])->hydrate(false)->toArray();
			
			$this->set("inward_bill_info",$inward_bill_list);
		}
		}
		if(isset($this->request->data["export_csv"]))
		{
			$projects_ids = $this->Usermanage->users_project($this->user_id);		
			$role = $this->role;
			$request = $this->request->data;
			$or = array();				
			
			$or["date >="] = ($request["date_from"] != "")?date("Y-m-d",strtotime($request["date_from"])):NULL;
			$or["date <="] = ($request["date_to"] != "")?date("Y-m-d",strtotime($request["date_to"])):NULL;
			$or["bill_date >="] = ($request["bill_date_from"] != "")?date("Y-m-d",strtotime($request["bill_date_from"])):NULL;
			$or["bill_date <="] = ($request["bill_date_to"] != "")?date("Y-m-d",strtotime($request["bill_date_to"])):NULL;
			$or["project_id IN"] = (!empty($request["project_id"]) && $request["project_id"] != "all")?$request["project_id"]:NULL;
			$or["party_name ="] = (!empty($request["party_id"]) && $request["party_id"] != "All")?$request["party_id"]:NULL;
			$or["bill_type ="] = (!empty($request["bill_type"]) && $request["bill_type"] != "All" )?$request["bill_type"]:NULL;
			$or["payment_method ="] = (!empty($request["payment_mod"]) && $request["payment_mod"] != "All")?$request["payment_mod"]:NULL;
			$or["inward_bill_no ="] = (!empty($request["bill_no"]))?$request["bill_no"]:NULL;
			$or["invoice_no ="] = (!empty($request["invoice_no"]))?$request["invoice_no"]:NULL;
			$or["po_no ="] = (!empty($request["powono"]))?$request["powono"]:NULL;
			if(!empty($request["pay_status"]) && $request["pay_status"] != "All")
			{
				if($request["pay_status"] == 'pending')
				{
					$or["status_inward ="] = $request["pay_status"];
				}
				else
				{
					$or["status_inward !="] = 'pending';
				}
			}
			else
			{
				$or["status_inward ="] = NULL;
			}
			
			if($or["project_id IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}
			}
			else
			{
				$or["project_id IN"] = explode(",",$or["project_id IN"]);
			}
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			// debug($or);die;
			if(!empty($or)){
				$inward_bill_list = $erp_inward_bill_register->find()->where([$or])->hydrate(false)->toArray();
			}else{
				$inward_bill_list = $erp_inward_bill_register->find()->hydrate(false)->toArray();
			}
			
		
			$rows = array();
			$rows[] = array("Project Name","Inward Bill No","Inward Date","Party's Name","Invoice No","Bill Date","Bill Amount","Credit Period","Diff(+/-)","Pay Status");
			
			foreach($inward_bill_list as $inward_bill_row)
			{
				$date =  date('Y-m-d');							
				$date1 = new \DateTime($inward_bill_row["bill_date"]->format("Y-m-d"));
				$date2 = new \DateTime($date);
				$diff = $date2->diff($date1)->format("%r%a");
				$check = $diff + intval($inward_bill_row["credit_period"]);
				
				$csv = array();
				$pay_status = "";
				if($inward_bill_row["status_inward"] == "pending")
				{
					$pay_status = "Only Inward";
				}
				else if($inward_bill_row["status_inward"] == "accept")
				{
					$pay_status = "Accepted";
				}else if($inward_bill_row["status_inward"] == "completed" ){
					$pay_status = "Accepted";
				}
				
				$csv[] = $this->ERPfunction->get_projectname($inward_bill_row['project_id']);
				$csv[] = $inward_bill_row['inward_bill_no'];
				$csv[] = date('d-m-Y',strtotime($inward_bill_row['date']));
				$is_agencry = strpos($inward_bill_row["party_name"],"NEC");
									
				if(($inward_bill_row["party_name"] == "0" || $is_agencry == 1 ) && $inward_bill_row["party_type"] == "old" )
				{
					$csv[] =$this->ERPfunction->get_agency_name_by_code($inward_bill_row["party_name"]);
				}
				else if($inward_bill_row["party_type"] == "new")
				{
					$csv[] = $inward_bill_row["new_party_name"];
				}
				else
				{
					$csv[] = $this->ERPfunction->get_vendor_name($inward_bill_row["party_name"]);			
				}
					$csv[] = $inward_bill_row['invoice_no'];
					$csv[] = $inward_bill_row["bill_date"]->format("d-m-Y");
					$csv[] = $inward_bill_row["total_amt"];
					$csv[] = $inward_bill_row["credit_period"];
					$csv[] = $inward_bill_row["bill_type"];
					$csv[] = $pay_status;
					$rows[] = $csv;
			}
			
			// $rows = unserialize(base64_decode($this->request->data["rows"]));
			$filename = "Accounts.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{			
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			// $rows = unserialize(base64_decode($this->request->data["rows"]));
			
			$projects_ids = $this->Usermanage->users_project($this->user_id);		
			$role = $this->role;
			$request = $this->request->data;
			$or = array();				
			
			$or["date >="] = ($request["date_from"] != "")?date("Y-m-d",strtotime($request["date_from"])):NULL;
			$or["date <="] = ($request["date_to"] != "")?date("Y-m-d",strtotime($request["date_to"])):NULL;
			$or["bill_date >="] = ($request["bill_date_from"] != "")?date("Y-m-d",strtotime($request["bill_date_from"])):NULL;
			$or["bill_date <="] = ($request["bill_date_to"] != "")?date("Y-m-d",strtotime($request["bill_date_to"])):NULL;
			$or["project_id IN"] = (!empty($request["project_id"]) && $request["project_id"] != "all")?$request["project_id"]:NULL;
			$or["party_name ="] = (!empty($request["party_id"]) && $request["party_id"] != "All")?$request["party_id"]:NULL;
			$or["bill_type ="] = (!empty($request["bill_type"]) && $request["bill_type"] != "All" )?$request["bill_type"]:NULL;
			$or["payment_method ="] = (!empty($request["payment_mod"]) && $request["payment_mod"] != "All")?$request["payment_mod"]:NULL;
			$or["inward_bill_no ="] = (!empty($request["bill_no"]))?$request["bill_no"]:NULL;
			$or["invoice_no ="] = (!empty($request["invoice_no"]))?$request["invoice_no"]:NULL;
			$or["po_no ="] = (!empty($request["powono"]))?$request["powono"]:NULL;
			if(!empty($request["pay_status"]) && $request["pay_status"] != "All")
			{
				if($request["pay_status"] == 'pending')
				{
					$or["status_inward ="] = $request["pay_status"];
				}
				else
				{
					$or["status_inward !="] = 'pending';
				}
			}
			else
			{
				$or["status_inward ="] = NULL;
			}
			
			if($or["project_id IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}
			}
			else
			{
				$or["project_id IN"] = explode(",",$or["project_id IN"]);
			}
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			//debug($or);die;
			if(!empty($or)){
				$inward_bill_list = $erp_inward_bill_register->find()->where([$or])->hydrate(false)->toArray();
			}else{
				$inward_bill_list = $erp_inward_bill_register->find()->hydrate(false)->toArray();
			}
		
			$rows = array();
			$rows[] = array("Project Name","Inward Bill No","Inward Date","Party's Name","Invoice No","Bill Date","Bill Amount","Credit Period","Type of Bill","Pay Status");
			
			foreach($inward_bill_list as $inward_bill_row)
			{
				$date =  date('Y-m-d');							
				$date1 = new \DateTime($inward_bill_row["bill_date"]->format("Y-m-d"));
				$date2 = new \DateTime($date);
				$diff = $date2->diff($date1)->format("%r%a");
				// $check = $diff + $inward_bill_row["credit_period"];
				
				$csv = array();
				$pay_status = "";
				if($inward_bill_row["status_inward"] == "pending")
				{
					$pay_status = "Only Inward";
				}
				else if($inward_bill_row["status_inward"] == "accept")
				{
					$pay_status = "Accepted";
				}else if($inward_bill_row["status_inward"] == "completed" ){
					$pay_status = "Accepted";
				}
				
				$csv[] = $this->ERPfunction->get_projectname($inward_bill_row['project_id']);
				$csv[] = $inward_bill_row['inward_bill_no'];
				$csv[] = date('d-m-Y',strtotime($inward_bill_row['date']));
				$is_agencry = strpos($inward_bill_row["party_name"],"NEC");
									
				if(($inward_bill_row["party_name"] == "0" || $is_agencry == 1 ) && $inward_bill_row["party_type"] == "old" )
				{
					$csv[] =$this->ERPfunction->get_agency_name_by_code($inward_bill_row["party_name"]);
				}
				else if($inward_bill_row["party_type"] == "new")
				{
					$csv[] = $inward_bill_row["new_party_name"];
				}
				else
				{
					$csv[] = $this->ERPfunction->get_vendor_name($inward_bill_row["party_name"]);			
				}
					$csv[] = $inward_bill_row['invoice_no'];
					$csv[] = $inward_bill_row["bill_date"]->format("d-m-Y");
					$csv[] = $inward_bill_row["total_amt"];
					$csv[] = $inward_bill_row["credit_period"];
					$csv[] = $inward_bill_row["bill_type"];
					$csv[] = $pay_status;
					$rows[] = $csv;
			}
			
			$this->set("rows",$rows);
			$this->render("accountlistspdf");
		}
	}
	
	public function accountpreviewgrn($grn_id)
    {
		$erp_inve_grn = TableRegistry::get('erp_inventory_grn'); 
		$erp_inve_grn_details = TableRegistry::get('erp_inventory_grn_detail'); 
		$erp_grn_details = $erp_inve_grn->get($grn_id);
		$this->set('erp_grn_details',$erp_grn_details);  
		$previw_list = $erp_inve_grn_details->find()->where(array('grn_id'=>$grn_id,"approved"=>1));
		$this->set('previw_list',$previw_list);   
    }
	
	public function printaccountgrn($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_grn");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_grn_detail');
		$previw_list = $erp_inve_po_details->find()->where(array('grn_id'=>$eid));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("erp_grn_details",$data->toArray());			
	}
	
    public function pendingbills(){
		ini_set('memory_limit', -1);

    	$inward_bill_register = TableRegistry::get('erp_inward_bill'); 
    	/* $get_pending_data=$inward_bill_register->find()->where(array('status_inward'=>'pending')); */
    	$get_pending_data=$inward_bill_register->find()->where(array('status_inward'=>'accept'));
    	$this->set('data_inward_pending',$get_pending_data);
		
			$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set('projects',$projects);
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->role;
		$this->set('role',$role);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;		
			
			$or = array();				
			
			$or["date LIKE"] = (!empty($post["date_from"]))?"%{$post["date_from"]}%":NULL;
			$or["date LIKE"] = (!empty($post["date_to"]))?"%{$post["date_to"]}%":NULL;
			$or["bill_date LIKE"] = (!empty($post["bill_date_from"]))?"%{$post["bill_date_from"]}%":NULL;
			$or["bill_date LIKE"] = (!empty($post["bill_date_to"]))?"%{$post["bill_date_to"]}%":NULL;
			$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All")?$post["project_id"]:NULL;
			$or["party_id IN"] = (!empty($post["party_id"]) && $post["party_id"][0] != "All")?$post["party_id"]:NULL;
			$or["bill_type IN"] = (!empty($post["bill_type"]) && $post["bill_type"][0] != "All" )?$post["bill_type"]:NULL;
			$or["payment_method IN"] = (!empty($post["payment_mod"]) && $post["payment_mod"][0] != "All")?$post["payment_mod"]:NULL;
			$or["inward_bill_no LIKE"] = (!empty($post["bill_no"]))?"%{$post["bill_no"]}%":NULL;
			$or["invoice_no LIKE"] = (!empty($post["invoice_no"]))?"%{$post["invoice_no"]}%":NULL;
			$or["po_no LIKE"] = (!empty($post["powono"]))?"%{$post["powono"]}%":NULL;
			//var_dump($post["bill_type"]);die;
			if($or["project_id IN"] == NULL)
			{
				if($role =='projectdirector' || $role =='projectcoordinator' || $role =='planningmanager' || $role =='siteaccountant' || $role =='constructionmanager' || $role =='billingengineer' || $role =='planningengineer' || $role =='materialmanager')
				{ 
					$or["project_id IN"] = $projects_ids;
				}
			}
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			//var_dump($or);die;
			$get_pending_data = $inward_bill_register->find()->where([$or])->hydrate(false)->toArray();
			
			$this->set("data_inward_pending",$get_pending_data);
			
		}
    }
	
	 public function disapprove($id)
	 {
		$inward_bill_register = TableRegistry::get('erp_inward_bill');
			$row = $inward_bill_register->get($id);
			$row['status_inward'] = 'pending';
			$row['pending_approve_by'] = NULL;
			$row['pending_approve_date'] = NULL;
			
			if($inward_bill_register->save($row))
			{
					$this->Flash->success(__('Record disapprove Successfully', null), 
							'default', 
							array('class' => 'success'));
					$this->redirect(array("controller" => "Accounts","action" => "pendingbills"));	
			}
	 }
	 
	 public function billdisapprove($id)
	 {
		$inward_bill_register = TableRegistry::get('erp_inward_bill');
			$row = $inward_bill_register->get($id);
			$row['status_inward'] = 'pending';
			$row['checked_by'] = NULL;
			$row['checked_date'] = NULL;

			$row['pending_approve_by'] = NULL;
			$row['pending_approve_date'] = NULL;

			$row['accept_by'] = NULL;
			$row['accept_date'] = NULL;
			if($inward_bill_register->save($row))
			{
					$this->Flash->success(__('Record disapprove Successfully', null), 
							'default', 
							array('class' => 'success'));
					$this->redirect(array("controller" => "accounts","action" => "accountlist"));	
			}
	 }
	
    public function delete($id){
		$inward_bill_register = TableRegistry::get('erp_inward_bill'); 
		$this->request->is(['post','delete']);
		
		$delete_contract_bill =$inward_bill_register->get($id);

		if($inward_bill_register->delete($delete_contract_bill))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 'default', array('class' => 'success'));	
		}
		return $this->redirect(array('controller'=>'Accounts','action'=>'index'));
    }
	
    public function acceptbills(){
		$previous_url= $this->referer();
		if (strpos($previous_url, 'inventory') !== false) {
			$back_url = 'inventory';
		}elseif(strpos($previous_url, 'accounts') !== false){
			$back_url = 'accounts';
		}else{
			$back_url = 'purchase';
		}
		
		$this->set('back_url',$back_url);
		
    	$inward_bill_register = TableRegistry::get('erp_inward_bill'); 
    	/* $get_pending_data=$inward_bill_register->find()->where(array('status_inward'=>'accept')); */
    	$get_pending_data=$inward_bill_register->find()->where(array('status_inward'=>'pending'));
    	$this->set('data_inward_pending',$get_pending_data);
		
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		$this->set('role',$this->role);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->role;
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data['go']))
			{
			$post = $this->request->data;		
			
			$or = array();				
			
			$or["date LIKE"] = (!empty($post["date_from"]))?"%{$post["date_from"]}%":NULL;
			$or["date LIKE"] = (!empty($post["date_to"]))?"%{$post["date_to"]}%":NULL;
			$or["bill_date LIKE"] = (!empty($post["bill_date_from"]))?"%{$post["bill_date_from"]}%":NULL;
			$or["bill_date LIKE"] = (!empty($post["bill_date_to"]))?"%{$post["bill_date_to"]}%":NULL;
			$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All")?$post["project_id"]:NULL;
			$or["party_id IN"] = (!empty($post["party_id"]) && $post["party_id"][0] != "All")?$post["party_id"]:NULL;
			$or["bill_type IN"] = (!empty($post["bill_type"]) && $post["bill_type"][0] != "All" )?$post["bill_type"]:NULL;
			$or["payment_method IN"] = (!empty($post["payment_mod"]) && $post["payment_mod"][0] != "All")?$post["payment_mod"]:NULL;
			$or["inward_bill_no LIKE"] = (!empty($post["bill_no"]))?"%{$post["bill_no"]}%":NULL;
			$or["invoice_no LIKE"] = (!empty($post["invoice_no"]))?"%{$post["invoice_no"]}%":NULL;
			$or["po_no LIKE"] = (!empty($post["powono"]))?"%{$post["powono"]}%":NULL;
			
			//$or["party_name LIKE"] = (!empty($post["party_name"]))?"%{$post["party_name"]}%":NULL;
			
			
			//var_dump($post["bill_type"]);die;
			if($or["project_id IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}
			}
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			//var_dump($or);die;
			$get_pending_data = $inward_bill_register->find()->where([$or])->hydrate(false)->toArray();
			
			$this->set("data_inward_pending",$get_pending_data);
		}
		
			if(isset($this->request->data["export_csv"]) || isset($this->request->data["export_pdf"]))
			{
				// debug($this->request->data);die;
				$erp_work_order = TableRegistry::get("erp_work_order");
				$erp_work_order_detail = TableRegistry::get("erp_work_order_detail");
				
				$request = $this->request->data;	
				$or = array();
				$or["date >="] = ($request["e_date_from"] != "")?date("Y-m-d",strtotime($request["e_date_from"])):NULL;
				$or["date <="] = ($request["e_date_to"] != "")?date("Y-m-d",strtotime($request["e_date_to"])):NULL;
				$or["bill_date >="] = ($request["eb_date_from"] != "")?date("Y-m-d",strtotime($request["eb_date_from"])):NULL;
				$or["bill_date <="] = ($request["eb_date_to"] != "")?date("Y-m-d",strtotime($request["eb_date_to"])):NULL;
				$or["project_id IN"] = (!empty($request["e_pro_id"]) && $request["e_pro_id"] != "all")?$request["e_pro_id"]:NULL;
				$or["party_name ="] = (!empty($request["e_party"]) && $request["e_party"] != "All")?$request["e_party"]:NULL;
				$or["bill_type ="] = (!empty($request["e_bill_type"]) && $request["e_bill_type"] != "All" )?$request["e_bill_type"]:NULL;
				$or["payment_method ="] = (!empty($request["e_payment"]) && $request["e_payment"] != "All")?$request["e_payment"]:NULL;
				$or["inward_bill_no ="] = (!empty($request["e_bill_no"]))?$request["e_bill_no"]:NULL;
				$or["invoice_no ="] = (!empty($request["e_invoice_no"]))?$request["e_invoice_no"]:NULL;
				$or["po_no ="] = (!empty($request["e_po_wo"]))?$request["e_po_wo"]:NULL;
				
				if($or["project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{ 
						$or["project_id IN"] = $projects_ids;
					}
				}
				else
				{
					$or["project_id IN"] = explode(",",$or["project_id IN"]);
				}
				
				$status = ['checked','pending'];
				// $status = "'pending','checked'";
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				$or["status_inward IN"] = $status;
				// debug($or);die;
								
				$result = $inward_bill_register->find()->where([$or])->order(['date'=>'DESC'])->hydrate(false)->toArray();
				// debug($result);die;	
				$rows = array();
				$rows[] = array("Inward Date","Project Name","Bill Inward No","Party Name","Type of Bill","Invoice No","Total Amount","Bill Date","Credit Period","Diff(+/-)","Qty. Checked By","Rate Checked By");
				
				foreach($result as $retrive_data)
				{	
					$new_party_name=$retrive_data['new_party_name'];
					$party_type = $retrive_data['party_type'];
					$party_name = $retrive_data['party_name'];
					
					$is_agency = strpos($party_name,"NEC");
					if(($party_name == "0" || $is_agency == 1 ) && $party_type == "old" )
					{
					    $ag_name = $this->ERPfunction->get_agency_name_by_code($party_name);
					}
					else if($party_type == "new")
					{
						$ag_name = $new_party_name;
					}
					else if($party_type == "inwardbillparty")
					{
						$ag_name = $party_name;
					}
					else
					{
						$ag_name = $this->ERPfunction->get_vendor_name($party_name);
					}
					
					$credit_period = $retrive_data['credit_period'];
					$date =  date('Y-m-d');
					$bill_date = $retrive_data['bill_date'];
					$pending_days = date('Y-m-d', strtotime( $date. " + {$credit_period} days"));
					// $datediff = $bill_date - $date;
					// $days_diff = floor($datediff/(60*60*24));
							
					$date1 = new \DateTime($bill_date);
					$date2 = new \DateTime($date);
					$diff = $date2->diff($date1)->format("%r%a");
					$rem = $diff + intval($credit_period);
					
					$csv = array();
					$csv[] = date("d-m-Y",strtotime($retrive_data['date']));
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $retrive_data['inward_bill_no'];
					$csv[] = $ag_name;
					$csv[] = $retrive_data['bill_type'];
					$csv[] = $retrive_data['invoice_no'];
					$csv[] = $retrive_data['total_amt'];
					$csv[] = date("d-m-Y",strtotime($retrive_data['bill_date']));
					$csv[] = $retrive_data['credit_period'];;
					$csv[] = $rem;
					$csv[] = $this->ERPfunction->get_category_title($retrive_data['qty_checked_by']);
					$csv[] = $this->ERPfunction->get_category_title($retrive_data['rate_checked_by']);
					// $csv[] = $retrive_data['amount'];
					$rows[] = $csv;
				}
				if(isset($this->request->data["export_csv"])){
					$filename = "acceptbills.csv";
					$this->ERPfunction->export_to_csv($filename,$rows);
				}
				if(isset($this->request->data["export_pdf"])){
					require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
					$this->set("rows",$rows);
					$this->render("acceptbillspdf");
				}
			}
		}
		
		
    }
	
    public function grnalert(){
		//$grn_list = $this->Usermanage->fetch_approve_grn_account();
		// debug($grn_list->fetchAll());die;
		//$this->set('grn_list',$grn_list);
		
		$previous_url= $this->referer();
		if (strpos($previous_url, 'accounts') !== false) {
			$back_url = 'accounts';
		}else{
			$back_url = 'purchase';
		}
		
		$this->set('back_url',$back_url);
		$role = $this->role;
		$this->set('role',$role);
		if($role == 'erpoperator')
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else
		{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$erp_material=TableRegistry::get('erp_material');
    	$meterial_list=$erp_material->find();
    	$this->set('meterial_list',$meterial_list);
		
		$erp_inventory_grn=TableRegistry::get('erp_inventory_grn');
		
		if($this->request->is("post"))
		{
		    // $erp_inventory_grn = TableRegistry::get("erp_inventory_grn");
			// $erp_inventory_grn_detail = TableRegistry::get("erp_inventory_grn_detail");
			
			// $post = $this->request->data;		
			// $or = array();				
			
			// $or["grn_date LIKE"] = (!empty($post["date_from"]))?"%{$post["date_from"]}%":NULL;
			// $or["grn_date LIKE"] = (!empty($post["date_to"]))?"%{$post["date_to"]}%":NULL;
			// $or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All")?$post["project_id"]:NULL;
			// $or["vendor_userid IN"] = (!empty($post["party_id"]) && $post["party_id"][0] != "All")?$post["party_id"]:NULL;
			// $or["erp_inventory_grn_detail.material_id IN"] = (!empty($post["material"]) && $post["material"][0] != "All" )?$post["material"]:NULL;
			// $or["payment_method IN"] = (!empty($post["payment_mod"]) && $post["payment_mod"][0] != "All")?$post["payment_mod"]:NULL;
			// $or["grn_no LIKE"] = (!empty($post["grn_no"]))?"%{$post["grn_no"]}%":NULL;
			// $or["challan_no LIKE"] = (!empty($post["challan_no"]))?"%{$post["challan_no"]}%":NULL;
			//$or["po_no LIKE"] = (!empty($post["powono"]))?"%{$post["powono"]}%":NULL;
			
			//$or["party_name LIKE"] = (!empty($post["party_name"]))?"%{$post["party_name"]}%":NULL;
			
			
			//var_dump($post["bill_type"]);die;
			// if($or["project_id IN"] == NULL)
			// {
				// if($this->Usermanage->project_alloted($role)==1){ 
					// $or["project_id IN"] = $projects_ids;
				// }
			// }
			
			// $keys = array_keys($or,"");				
			// foreach ($keys as $k)
			// {unset($or[$k]);}
			//var_dump($or);die;
			// $result = $erp_inventory_grn->find()->select($erp_inventory_grn)->where(["approved_status"=>1,"show_in_account"=>0]);
				// $result = $result->innerjoin(
							// ["erp_inventory_grn_detail"=>"erp_inventory_grn_detail"],
							// ["erp_inventory_grn.grn_id = erp_inventory_grn_detail.grn_id"])
							// ->where($or)->select($erp_inventory_grn_detail)->hydrate(false)->toArray();
			//var_dump($result);die;
			// $this->set("grn_list",$result);
			
		}
		if(isset($this->request->data["export_csv"]))
		{
			// $rows = unserialize(base64_decode($this->request->data["rows"]));
			$post = $this->request->data;
			
			$erp_inventory_grn = TableRegistry::get("erp_inventory_grn");
			$erp_inventory_grn_detail = TableRegistry::get("erp_inventory_grn_detail");
			
			$or["erp_inventory_grn.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"][0] != "All" )?$post["e_pro_id"]:NULL;
			$or["erp_inventory_grn.payment_method ="] = (!empty($post["e_payment_mod"]) && $post["e_payment_mod"] != "All")?$post["e_payment_mod"]:NULL;
			$or["erp_inventory_grn_detail.material_id ="] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
			$or["erp_inventory_grn.vendor_userid ="] = (!empty($post["e_party_id"]) && $post["e_party_id"] != "All")?$post["e_party_id"]:NULL;
			$or["erp_inventory_grn.grn_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
			$or["erp_inventory_grn.grn_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
			$or["erp_inventory_grn.grn_no ="] = (!empty($post["e_grn_no"]))?$post["e_grn_no"]:NULL;
			$or["erp_inventory_grn.challan_no ="] = (!empty($post["e_challan_no"]))?$post["e_challan_no"]:NULL;
			
			if($or["erp_inventory_grn.project_id IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1)
				{ 
					$or["erp_inventory_grn.project_id IN"] = $projects_ids;
				}
			}
					
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
					
			$or["erp_inventory_grn.approved_status ="] = 1;
			$or["erp_inventory_grn.show_in_account ="] = 0;
			
			$result = $erp_inventory_grn->find()->select($erp_inventory_grn);
			$result = $result->innerjoin(
						["erp_inventory_grn_detail"=>"erp_inventory_grn_detail"],
						["erp_inventory_grn.grn_id = erp_inventory_grn_detail.grn_id"])
						->where($or)->select($erp_inventory_grn_detail)->hydrate(false)->toArray();
			
			$rows = array();
			$rows[] = array("G.R.N No","Date","Vendor Name","Challan No.","Material Name","Make/ Source","Actual Qty.","Unit");
		
			foreach($result as $retrive_data)
			{				
				if(isset($retrive_data["erp_inventory_grn_detail"]))
				{
					$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_grn_detail"]);
				}
				if($retrive_data['material_id'] != 0)
				{
					$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
					$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
					$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
				}
				else
				{
					$mt = $retrive_data['material_name'];
					$brnd = $retrive_data['brand_name'];
					$static_unit = $retrive_data['static_unit'];
				}
				
				$csv = array();		
				$csv[] = $retrive_data['grn_no'];
				$csv[] = date('d-m-Y',strtotime($retrive_data['grn_date']));
				$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
				$csv[] = $retrive_data['challan_no'];
				$csv[] = $mt;
				$csv[] = $brnd;
				$csv[] = $retrive_data['actual_qty'];
				$csv[] = $static_unit;
				$rows[] = $csv;
			}
			$filename = "grnalert.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{			
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			// $rows = unserialize(base64_decode($this->request->data["rows"]));
			$post = $this->request->data;
			
			$erp_inventory_grn = TableRegistry::get("erp_inventory_grn");
			$erp_inventory_grn_detail = TableRegistry::get("erp_inventory_grn_detail");
			
			$or["erp_inventory_grn.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"][0] != "All" )?$post["e_pro_id"]:NULL;
			$or["erp_inventory_grn.payment_method ="] = (!empty($post["e_payment_mod"]) && $post["e_payment_mod"] != "All")?$post["e_payment_mod"]:NULL;
			$or["erp_inventory_grn_detail.material_id ="] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
			$or["erp_inventory_grn.vendor_userid ="] = (!empty($post["e_party_id"]) && $post["e_party_id"] != "All")?$post["e_party_id"]:NULL;
			$or["erp_inventory_grn.grn_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
			$or["erp_inventory_grn.grn_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
			$or["erp_inventory_grn.grn_no ="] = (!empty($post["e_grn_no"]))?$post["e_grn_no"]:NULL;
			$or["erp_inventory_grn.challan_no ="] = (!empty($post["e_challan_no"]))?$post["e_challan_no"]:NULL;
			
			if($or["erp_inventory_grn.project_id IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1)
				{ 
					$or["erp_inventory_grn.project_id IN"] = $projects_ids;
				}
			}
					
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
					
			$or["erp_inventory_grn.approved_status ="] = 1;
			$or["erp_inventory_grn.show_in_account ="] = 0;
			
			$result = $erp_inventory_grn->find()->select($erp_inventory_grn);
			$result = $result->innerjoin(
						["erp_inventory_grn_detail"=>"erp_inventory_grn_detail"],
						["erp_inventory_grn.grn_id = erp_inventory_grn_detail.grn_id"])
						->where($or)->select($erp_inventory_grn_detail)->hydrate(false)->toArray();
			
			$rows = array();
			$rows[] = array("G.R.N No","Date","Vendor Name","Challan No.","Material Name","Make/ Source","Actual Qty.","Unit");
		
			foreach($result as $retrive_data)
			{				
				if(isset($retrive_data["erp_inventory_grn_detail"]))
				{
					$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_grn_detail"]);
				}
				if($retrive_data['material_id'] != 0)
				{
					$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
					$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
					$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
				}
				else
				{
					$mt = $retrive_data['material_name'];
					$brnd = $retrive_data['brand_name'];
					$static_unit = $retrive_data['static_unit'];
				}
				
				$csv = array();		
				$csv[] = $retrive_data['grn_no'];
				$csv[] = date('d-m-Y',strtotime($retrive_data['grn_date']));
				$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
				$csv[] = $retrive_data['challan_no'];
				$csv[] = $mt;
				$csv[] = $brnd;
				$csv[] = $retrive_data['actual_qty'];
				$csv[] = $static_unit;
				$rows[] = $csv;
			}
			$this->set("rows",$rows);
			$this->render("grnalertpdf");
		}
		
    }
	
    public function mrnalert(){
		// $mrn_list = $this->Usermanage->account_fetch_approve_mrn();
		// $this->set('mrn_list',$mrn_list);
		$previous_url= $this->referer();
		if (strpos($previous_url, 'account') !== false) {
			$back_url = 'accounts';
		}else{
			$back_url = 'purchase';
		}
		$this->set('back_url',$back_url);
		
		$role = $this->role;
		$this->set('role',$role);
		
		if($role == 'erpoperator')
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else
		{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$erp_material=TableRegistry::get('erp_material');
    	$meterial_list=$erp_material->find();
    	$this->set('meterial_list',$meterial_list);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			
				$erp_inventory_mrn = TableRegistry::get("erp_inventory_mrn");
				$erp_inventory_mrn_detail = TableRegistry::get("erp_inventory_mrn_detail");
				$post = $this->request->data;
				$or = array();				
				
				$or["mrn_date LIKE"] = (!empty($post["date_from"]))?"%{$post["date_from"]}%":NULL;
				$or["mrn_date LIKE"] = (!empty($post["date_to"]))?"%{$post["date_to"]}%":NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All")?$post["project_id"]:NULL;
				$or["vendor_user IN"] = (!empty($post["party_id"]) && $post["party_id"][0] != "All")?$post["party_id"]:NULL;
				//$or["bill_type IN"] = (!empty($post["bill_type"]) && $post["bill_type"][0] != "All" )?$post["bill_type"]:NULL;
				$or["erp_inventory_mrn_detail.material_id IN"] = (!empty($post["material"]) && $post["material"][0] != "All")?$post["material"]:NULL;
				$or["mrn_no LIKE"] = (!empty($post["mrn_no"]))?"%{$post["mrn_no"]}%":NULL;
				//$or["challan_no LIKE"] = (!empty($post["challan_no"]))?"%{$post["challan_no"]}%":NULL;
				//$or["po_no LIKE"] = (!empty($post["powono"]))?"%{$post["powono"]}%":NULL;
				
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
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				$result = $erp_inventory_mrn->find()->select($erp_inventory_mrn)->where(["approve_accountant"=>0,"approve_executives"=>0]);
				$result = $result->innerjoin(
							["erp_inventory_mrn_detail"=>"erp_inventory_mrn_detail"],
							["erp_inventory_mrn.mrn_id = erp_inventory_mrn_detail.mrn_id","erp_inventory_mrn_detail.approved"=>0])
							->where($or)->select($erp_inventory_mrn_detail)->hydrate(false)->toArray();
				//var_dump($result);die;
				$this->set('mrn_list',$result);
		}
		
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$filename = "mrnalert.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{			
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$this->set("rows",$rows);
			$this->render("mrnalertpdf");
		}
    }
   

    public function addinwardbill($id=NULL)
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'inventory') !== false) {
			$back_url = 'inventory';
		}elseif(strpos($previous_url, 'purchase') !== false){
			$back_url = 'purchase';
		}else{
			$back_url = 'accounts';
		}
		
		$this->set('back_url',$back_url);
		$current_dt = Time::now();
		$current_time = date("H:i");
		$current_date=date("d-m-Y", strtotime($current_dt));
		$this->set('current_time',$current_time);
		$this->set('current_date',$current_date);
		
		$erp_inward_bill_register = TableRegistry::get('erp_inward_bill');
		
		$erp_category_master = TableRegistry::get('erp_category_master'); 
		$checkedby_data = $erp_category_master->find()->where(['type'=>'qty_checkdeby'])->hydrate(false)->toArray();
		$this->set('checkedby_data',$checkedby_data);
		
		$ratecheckedby_data = $erp_category_master->find()->where(['type'=>'rate_checkdeby'])->hydrate(false)->toArray();
		$this->set('ratecheckedby_data',$ratecheckedby_data);
		
    	$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);

		$inward_party_info=$erp_inward_bill_register->find()->where(['party_type'=>'new'])->select('new_party_name')->hydrate(false)->toArray();
		$inward_party = array();
		foreach($inward_party_info as $party)
		{
			$inward_party[] = $party['new_party_name'];
		}
		$this->set('inward_party',$inward_party);
		$role = $this->role;
		$this->set('role',$role);
		 
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);

		if(isset($id)){			
			$user_action = 'edit';
			$data_inward_update = $erp_inward_bill_register->get($id);
			$this->set('update_inward',$data_inward_update);
			$this->set('form_header','Edit Inward Bill');
			$this->set('button_text','Edit Inward Bill');	
		}
		else{
			$user_action = 'insert';
			$this->set('user_identy_id',$user_identy_id);
			$this->set('form_header','Inward Bill');
			$this->set('button_text','Add Inward Bill');
		}
		
		
		$this->set('user_action',$user_action);
		
		
		if($this->request->is('post')){
				
				
				$this->set('user_data',$this->request->data);


				$this->request->data['date']=date('Y-m-d',strtotime($this->request->data['date']));
				$this->request->data['bill_date']=date('Y-m-d',strtotime($this->request->data['bill_date']));
				
				
				$this->request->data['status']=1;
				
				/*
				$image_bill=$this->ERPfunction->upload_image('attachment_bill',$this->request->data['old_bill']);
				$this->request->data['attachment_bill']=$image_bill;

				$image_pass=$this->ERPfunction->upload_image('attachment_pass',$this->request->data['old_pass']);
				$this->request->data['attachment_pass']=$image_pass;

				$image_sheet=$this->ERPfunction->upload_image('attachment_mmt_sheet',$this->request->data['old_mmt_sheet']);
				$this->request->data['attachment_mmt_sheet']=$image_sheet;
				*/
				
				if($user_action == 'edit')
				{
					if(isset($_FILES['attach_file']))
					{
						$file =$_FILES['attach_file']["name"];
						$size = count($file);
						for($i=0;$i<$size;$i++) {
							$parts = pathinfo($_FILES['attach_file']['name'][$i]);
						}
						$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
						// debug($ext);die;
						if($ext != 0) {
							$post_data = $this->request->data;
							// var_dump($post_data);die;
							$old_files = array();
							if(isset($post_data["old_attach_file"]))
							{
								$old_files = $post_data["old_attach_file"];				
							}
							@$post_data['attach_label'] = trim(json_encode($post_data["attach_label"]),'\"');
							if(isset($_FILES["attach_file"]["name"]))
							{
								$file = $this->ERPfunction->upload_file("attach_file");	
								if(!empty($file))
								foreach($file as $attachment_file)
								{
									$old_files[] = $attachment_file;
								}					
							}
							
							if($post_data["party_type"] == "old")
							{
								$post_data['party_name'] = $post_data['old_party'];
							}
							else if($post_data["party_type"] == "inwardbillparty")
							{
								$post_data['party_name'] = $post_data['inward_party'];
							}
							$post_data['attach_file'] = json_encode($old_files);
							
							// $post_data['qty_checked_by']=$post_data['qty_checkedby'];
							// $post_data['rate_checked_by']=$post_data['rate_checkedby'];
							$post_data['last_edit']=date('Y-m-d H:i:s');
							$post_data['last_edit_by']=$this->request->session()->read('user_id');
							$save_data_update = $erp_inward_bill_register->patchEntity($data_inward_update,$post_data);
							// debug($save_data_update);die;
							if($erp_inward_bill_register->save($save_data_update))
							{
								$this->Flash->success(__('Record Update Successfully', null), 
										'default', 
										array('class' => 'success'));
								
								if($back_url == "accounts") {
									$this->redirect(array("controller" => $back_url,"action" => "acceptbills"));
								}
								
							}
						}else{
							$this->Flash->error(__("Invalid File Extension, Please Retry."));
						}	
					}
					else{
						$post_data = $this->request->data;
						// var_dump($post_data);die;
						$old_files = array();
						if(isset($post_data["old_attach_file"]))
						{
							$old_files = $post_data["old_attach_file"];				
						}
						@$post_data['attach_label'] = trim(json_encode($post_data["attach_label"]),'\"');
						if(isset($_FILES["attach_file"]["name"]))
						{
							$file = $this->ERPfunction->upload_file("attach_file");	
							if(!empty($file))
							foreach($file as $attachment_file)
							{
								$old_files[] = $attachment_file;
							}					
						}
						
						if($post_data["party_type"] == "old")
						{
							$post_data['party_name'] = $post_data['old_party'];
						}
						else if($post_data["party_type"] == "inwardbillparty")
						{
							$post_data['party_name'] = $post_data['inward_party'];
						}
						$post_data['attach_file'] = json_encode($old_files);
						
						// $post_data['qty_checked_by']=$post_data['qty_checkedby'];
						// $post_data['rate_checked_by']=$post_data['rate_checkedby'];
						$post_data['last_edit']=date('Y-m-d H:i:s');
						$post_data['last_edit_by']=$this->request->session()->read('user_id');
						$save_data_update = $erp_inward_bill_register->patchEntity($data_inward_update,$post_data);
						// debug($save_data_update);die;
						if($erp_inward_bill_register->save($save_data_update))
						{
							$this->Flash->success(__('Record Update Successfully', null), 
									'default', 
									array('class' => 'success'));
							
							if($back_url == "accounts") {
								$this->redirect(array("controller" => $back_url,"action" => "acceptbills"));
							}
							
						}
					}
				}
				else
				{	
					if(isset($_FILES['attach_file']))
					{
						$file =$_FILES['attach_file']["name"];
						$size = count($file);
						for($i=0;$i<$size;$i++) {
							$parts = pathinfo($_FILES['attach_file']['name'][$i]);
						}
						$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
						// debug($ext);die;
						if($ext != 0) {
							$request_data = $this->request->data;
					
							$project_code = $this->ERPfunction->get_projectcode($request_data['project_id']);
							$number = $this->ERPfunction->generate_auto_id($request_data['project_id'],"erp_inward_bill","inward_bill_id","inward_bill_no");
							$new_inwardno = sprintf("%09d", $number);
							$inward_no = $project_code.'/BIN/'.$new_inwardno;
							
							
							$cnt = 0 ;
							if($this->request->data["party_type"] == "old")
							{
								$row=$erp_inward_bill_register->find("all")->where(["party_name"=>$this->request->data["old_party"],"invoice_no"=>$this->request->data["invoice_no"]]);
								$cnt = $row->count();
							}
							else if($this->request->data["party_type"] == "new")
							{
								$row=$erp_inward_bill_register->find("all")->where(["new_party_name"=>$this->request->data["new_party_name"],"invoice_no"=>$this->request->data["invoice_no"]]);
								$cnt = $row->count();
							}
							
							if($cnt >= 1)
							{
								$this->Flash->success(__('Error : Party name and invoice number already exist.', null), 
								'default', 
								array('class' => 'success'));
								$this->redirect(array("controller" => "Accounts","action" => "addinwardbill"));
							}
							else
							{
								if($this->request->data["party_type"] == "old")
								{
									$this->request->data['party_name'] = $request_data['old_party'];
								}
								else if($this->request->data["party_type"] == "inwardbillparty")
								{
									$this->request->data['party_name'] = $request_data['inward_party'];
								}
								
								$inward_bill_entity = $erp_inward_bill_register->newEntity();
								
								@$this->request->data['attach_label'] = trim(json_encode($this->request->data["attach_label"]),'\"');
								$all_files = array();
								if(isset($_FILES["attach_file"]["name"]))
								{
									$file = $this->ERPfunction->upload_file("attach_file");	
									if(!empty($file))
									foreach($file as $attachment_file)
									{
										$all_files[] = $attachment_file;
									}					
								}
								$this->request->data['inward_bill_no'] = $inward_no;
								$this->request->data['attach_file'] = json_encode($all_files);
								// $this->request->data['qty_checked_by']=$request_data['qty_checkedby'];
								// $this->request->data['rate_checked_by']=$request_data['rate_checkedby'];
								$this->request->data['created_date']=date('Y-m-d H:i:s');
								$this->request->data['created_by']=$this->request->session()->read('user_id');
								
								$create_patch_inward_bill=$erp_inward_bill_register->patchEntity($inward_bill_entity,$this->request->data);
								if($erp_inward_bill_register->save($create_patch_inward_bill))
								{
									$this->Flash->success(__('Inward Bill Insert Successfully with Inward NO '.$inward_no, null), 
								'default', 
								array('class' => 'success'));
								
								$this->redirect(array("controller" => "Accounts","action" => "index"));	
								}
							}
						}
						else{
							$this->Flash->error(__("Invalid File Extension, Please Retry."));
						}
					}
					else{
						$request_data = $this->request->data;
					
						$project_code = $this->ERPfunction->get_projectcode($request_data['project_id']);
						$number = $this->ERPfunction->generate_auto_id($request_data['project_id'],"erp_inward_bill","inward_bill_id","inward_bill_no");
						$new_inwardno = sprintf("%09d", $number);
						$inward_no = $project_code.'/BIN/'.$new_inwardno;
						
						
						$cnt = 0 ;
						if($this->request->data["party_type"] == "old")
						{
							$row=$erp_inward_bill_register->find("all")->where(["party_name"=>$this->request->data["old_party"],"invoice_no"=>$this->request->data["invoice_no"]]);
							$cnt = $row->count();
						}
						else if($this->request->data["party_type"] == "new")
						{
							$row=$erp_inward_bill_register->find("all")->where(["new_party_name"=>$this->request->data["new_party_name"],"invoice_no"=>$this->request->data["invoice_no"]]);
							$cnt = $row->count();
						}
						
						if($cnt >= 1)
						{
							$this->Flash->success(__('Error : Party name and invoice number already exist.', null), 
							'default', 
							array('class' => 'success'));
							$this->redirect(array("controller" => "Accounts","action" => "addinwardbill"));
						}
						else
						{
							if($this->request->data["party_type"] == "old")
							{
								$this->request->data['party_name'] = $request_data['old_party'];
							}
							else if($this->request->data["party_type"] == "inwardbillparty")
							{
								$this->request->data['party_name'] = $request_data['inward_party'];
							}
							
							$inward_bill_entity = $erp_inward_bill_register->newEntity();
							
							@$this->request->data['attach_label'] = trim(json_encode($this->request->data["attach_label"]),'\"');
							$all_files = array();
							if(isset($_FILES["attach_file"]["name"]))
							{
								$file = $this->ERPfunction->upload_file("attach_file");	
								if(!empty($file))
								foreach($file as $attachment_file)
								{
									$all_files[] = $attachment_file;
								}					
							}
							$this->request->data['inward_bill_no'] = $inward_no;
							$this->request->data['attach_file'] = json_encode($all_files);
							// $this->request->data['qty_checked_by']=$request_data['qty_checkedby'];
							// $this->request->data['rate_checked_by']=$request_data['rate_checkedby'];
							$this->request->data['created_date']=date('Y-m-d H:i:s');
							$this->request->data['created_by']=$this->request->session()->read('user_id');
							
							$create_patch_inward_bill=$erp_inward_bill_register->patchEntity($inward_bill_entity,$this->request->data);
							if($erp_inward_bill_register->save($create_patch_inward_bill))
							{
								$this->Flash->success(__('Inward Bill Insert Successfully with Inward NO '.$inward_no, null), 
							'default', 
							array('class' => 'success'));
							
							$this->redirect(array("controller" => "Accounts","action" => "index"));	
							}
						}
					}
				}
				
		}	



    }
	
	 public function viewbill($id=NULL)
	 {
		 
    	$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$erp_agency = TableRegistry::get('erp_agency'); 
		$agency_list = $erp_agency->find();
		$this->set('agency_list',$agency_list);
		
		$erp_category_master = TableRegistry::get('erp_category_master'); 
		$checkedby_data = $erp_category_master->find()->where(['type'=>'qty_checkdeby'])->hydrate(false)->toArray();
		$this->set('checkedby_data',$checkedby_data);
		
		$ratecheckedby_data = $erp_category_master->find()->where(['type'=>'rate_checkdeby'])->hydrate(false)->toArray();
		$this->set('ratecheckedby_data',$ratecheckedby_data);
		
		$erp_inward_bill_register = TableRegistry::get('erp_inward_bill');
		$inward_party_info=$erp_inward_bill_register->find()->where(['party_type'=>'new'])->select('new_party_name')->hydrate(false)->toArray();
		$inward_party = array();
		foreach($inward_party_info as $party)
		{
			$inward_party[] = $party['new_party_name'];
		}
		$this->set('inward_party',$inward_party);
		
		$erp_inward_bill_register = TableRegistry::get('erp_inward_bill'); 
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set('projects',$projects);

		if(isset($id)){
			
			//$user_action = 'edit';
			$data_inward_update = $erp_inward_bill_register->get($id);
			$this->set('update_inward',$data_inward_update);
			$this->set('form_header','View Inward Bill');
			
		}
		
		//$this->set('user_action',$user_action);
		
		
		if($this->request->is('post')){	
			$this->set('user_data',$this->request->data);
	
			
			// $this->request->data['date']=date('Y-m-d',strtotime($this->request->data['date']));
			// $this->request->data['bill_date']=date('Y-m-d',strtotime($this->request->data['bill_date']));
		//	$this->request->data['created_date']=date('Y-m-d H:i:s');
			// $this->request->data['created_by']=$this->request->session()->read('user_id');
			
			// $this->request->data['status']=1;

			// $image_bill=$this->ERPfunction->upload_image('attachment_bill',$this->request->data['old_bill']);
			// $this->request->data['attachment_bill']=$image_bill;

			// $image_pass=$this->ERPfunction->upload_image('attachment_pass',$this->request->data['old_pass']);
			// $this->request->data['attachment_pass']=$image_pass;

			// $image_sheet=$this->ERPfunction->upload_image('attachment_mmt_sheet',$this->request->data['old_mmt_sheet']);
			// $this->request->data['attachment_mmt_sheet']=$image_sheet;

			
				
				// $post_data = $this->request->data;
				// $save_data_update = $erp_inward_bill_register->patchEntity($data_inward_update,$post_data);
				// if($erp_inward_bill_register->save($save_data_update))
				// {
					// $this->Flash->success(__('Successfully', null), 
							// 'default', 
							// array('class' => 'success'));
				// }
			
				}
	 }
	 
	public function printbill($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inward_bill");
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());
	}
	
	public function advancerequest()
	{
		$projects = $this->Usermanage->access_project($this->user_id);	

		$this->set('projects',$projects);
		
		// $erp_agency = TableRegistry::get('erp_agency'); 
		// $agency_list = $erp_agency->find();
		// $this->set('agency_list',$agency_list);

		$erp_vendor = TableRegistry::get('erp_vendor'); 
		$vendor_list = $erp_vendor->find('all');
		$this->set('vendor_list',$vendor_list);
		
		if($this->request->is('post'))
		{
			$data = $this->request->data;
			$erp_advance_request = TableRegistry::get('erp_advance_request');
			$cnt = $erp_advance_request->find()->where(['project_id'=>$data['project_id'],'DATE(erp_advance_request.created_date)'=>date('Y-m-d')])->count();
			if($cnt != 1)
			{
				$entity_data = $erp_advance_request->newEntity();
				$entity_data['created_date']=date('Y-m-d H:i:s');
				$entity_data['created_by']=$this->request->session()->read('user_id');
				$entity_data['project_id'] = $data['project_id'];
				$entity_data['advance_req_no'] = $data['prno'];
				$entity_data['date'] = $this->ERPfunction->set_date($data['pr_date']);
				$entity_data['time'] = $data['pr_time'];
				
				if($erp_advance_request->save($entity_data))
				{
					$this->Flash->success(__('Insert Successfully.'));
					 $request_id = $entity_data->request_id;
					 $this->ERPfunction->add_advance_req_detail($this->request->data['agency'],$request_id,$data['project_id']);	
					 //$this->set('save','alert-success');
					 return $this->redirect(['action' => 'index']);
				 }	
			}else{
				$this->Flash->success(__('Complete single entry today with this Project.', null), 
							'default', 
							array('class' => 'success'));
			}
			// if(!empty($data['agency']))
			// {
				// $i = 0;
				// foreach($data['agency'] as $key=>$value)
				// {
					
					// $erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail');
					// $detail_data = $erp_advance_request_detail->newEntity();
					// $detail_data['request_id'] = $request_id;
					// @$detail_data['agency_id'] = $data["agency"]['agency_id'][$i];
					// @$detail_data['labur'] = $data["agency"]["laburs"][$i];
					// @$detail_data['advance_rs'] = $data["agency"]["advance_rs"][$i];
					// $detail_data['approve'] = 0;
					// $i++;
					// if($erp_advance_request_detail->save($detail_data))
					// {
						
					// }
				// }
			// }
			
			
		}
		
		
	}
	public function viewrequest()
	{
		$request_list = $this->Usermanage->fetch_view_advance_request($this->user_id);
		$this->set('request_list',$request_list);
		$role = $this->role;
		$this->set('role',$role);
		
	}
	
	public function editrequest($request_id)
	{
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		$erp_advance_request = TableRegistry::get("erp_advance_request");
		$erp_advance_request_detail = TableRegistry::get("erp_advance_request_detail");
		
		$request_list = $erp_advance_request->get($request_id)->toArray();
		$this->set('request_list',$request_list);
		
		// $erp_agency = TableRegistry::get('erp_agency'); 
		// $agency_list = $erp_agency->find();
		// $this->set('agency_list',$agency_list);

		$erp_vendor= TableRegistry::get('erp_vendor');
		$vendor_list = $erp_vendor->find();
		$this->set('vendor_list',$vendor_list);
		
		$detail_data = $erp_advance_request_detail->find("all")->where(["request_id"=>$request_id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);	

			if($this->request->is("post"))
			{
				$post = $this->request->data;		
				
				$row = $erp_advance_request->get($request_id);
				$row['project_id'] = $post["project_id"];
				$row['advance_req_no'] = $post["prno"];
				$row['date'] = $this->ERPfunction->set_date($post['pr_date']);
				$row['time'] = $post["pr_time"];
				$row['created_date'] = $row['created_date'];
				$row['created_by'] = $this->request->session()->read('user_id');
				
				if($erp_advance_request->save($row))
				{
					 $this->ERPfunction->edit_advance_req_detail($this->request->data['agency'],$request_id,$post["project_id"]);
					$this->Flash->success(__('Record Update Successfully')); 
					return $this->redirect(['action' => 'viewrequest']);
				}
			}
	}
	
	public function requestview($request_id,$from_where = NULL)
	{
		ini_set('memory_limit', '-1');
		if($from_where == 'viewadvance')
		{
			$back = 'viewadvance';
		}
		else if($from_where == 'viewrequest')
		{
			$back = 'viewrequest';
		}
		$this->set('back',$back);
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		$erp_advance_request = TableRegistry::get("erp_advance_request");
		$erp_advance_request_detail = TableRegistry::get("erp_advance_request_detail");
		
		$request_list = $erp_advance_request->get($request_id)->toArray();
		$this->set('request_list',$request_list);
		
		$erp_vendor = TableRegistry::get('erp_vendor'); 
		$vendor_list = $erp_vendor->find();
		$this->set('vendor_list',$vendor_list);
		
		$detail_data = $erp_advance_request_detail->find("all")->where(["request_id"=>$request_id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);	
		$this->set("request_id",$request_id);	

			if($this->request->is("post"))
			{
				$post = $this->request->data;		
				
				$row = $erp_advance_request->get($request_id);
				$row['project_id'] = $post["project_id"];
				$row['advance_req_no'] = $post["prno"];
				$row['date'] = $this->ERPfunction->set_date($post['pr_date']);
				$row['time'] = $post["pr_time"];
				$row['created_date'] = $row['created_date'];
				$row['created_by'] = $this->request->session()->read('user_id');
				
				if($erp_advance_request->save($row))
				{
					 $this->ERPfunction->edit_advance_req_detail($this->request->data['agency'],$request_id,$post["project_id"]);
					$this->Flash->success(__('Record Update Successfully')); 
					return $this->redirect(['action' => 'viewrequest']);
				}
			}
	}
	
	public function printadvance($request_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		$erp_advance_request = TableRegistry::get("erp_advance_request");
		$erp_advance_request_detail = TableRegistry::get("erp_advance_request_detail");
		
		$request_list = $erp_advance_request->get($request_id)->toArray();
		$this->set('request_list',$request_list);
		
		$erp_agency = TableRegistry::get('erp_agency'); 
		$agency_list = $erp_agency->find();
		$this->set('agency_list',$agency_list);
		
		$detail_data = $erp_advance_request_detail->find("all")->where(["request_id"=>$request_id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);	
		$this->set("request_id",$request_id);	

	}
	
	public function deleterequest($id)
	{
		$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail');
		$record = $erp_advance_request_detail->get($id);
		$req_id = $record->request_id;
		if($erp_advance_request_detail->delete($record))
		{
			$count = $erp_advance_request_detail->find()->where(["request_id"=>$req_id])->count();
			if($count == 0)
			{				
				$tbl = TableRegistry::get("erp_advance_request");
				$row = $tbl->get($req_id);
				$project_id = $row->project_id;
				$tbl->delete($row);
			}
			$this->Flash->success(__('Record delete Successfully.'));
			return $this->redirect(['action'=>'viewrequest']);
		}
	}
	
	public function deleteadvance($id)
	{
		$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail');
		$row = $erp_advance_request_detail->get($id);
		$id_group = json_decode($row["approval_group"]);
		//var_dump($id_group);
		$re_group = array();
		foreach($id_group as $ids)
		{
			if($ids != $id)
			{
				$re_group[] = $ids;
			}
		}
		
		foreach($re_group as $re_id)
		{
			$row1 = $erp_advance_request_detail->get($re_id);
			$row1["approval_group"] = json_encode($re_group);
			$erp_advance_request_detail->save($row1);
			
		}
		
		//var_dump($re_group);die;
		$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail');
		$record = $erp_advance_request_detail->get($id);
		//$req_id = $record->request_id;
		$record['approval_export'] = 0;
		$record['approval_export_by'] = NULL;
		$record['transfer_type'] = NULL;
		$record['cheque_amount'] = NULL;
		$record['bank'] = NULL;
		$record['cheque_no'] = NULL;
		$record['transfer_date'] = NULL;
		$record['approval_group'] = NULL;
		
		if($erp_advance_request_detail->save($record))
		{
			$this->Flash->success(__('Record Unapprove Successfully.'));
			return $this->redirect(['action'=>'viewadvance']);
		}
	}
	
	public function advancetransfer()
	{
	$this->autoRender = false ;
		if($this->request->is("post"))
		{
			$data = $this->request->data;
			//$dt = str_replace('-','/',$data['transfer_date']);
			$dt = $data['transfer_date'];
			$request_id = json_decode($data['request_id']);
		// var_dump($request_id);die;
			$group = array();
			
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$group[] = $req_id;
				}
			}
			$id = $group;
			//var_dump($id);die;
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail');
					$row = $erp_advance_request_detail->get($req_id);
					$row['approval_export'] = 1;
					$row['approval_export_by'] = $this->request->session()->read('user_id');
					$row['transfer_type'] = $data['transfer_type'];
					$row['cheque_amount'] = $data['amount'];
					//$row['bank'] = $data['bank_name'];
					//$row['cheque_no'] = $data['cheque_no'];
					$row['transfer_date'] = $this->ERPfunction->set_date($dt);
					$row['approval_group'] = json_encode($id);
					$check=$erp_advance_request_detail->save($row);
					
					$erp_advance_amount_detail = TableRegistry::get('erp_advance_amount_detail');
					$amount_entity = $erp_advance_amount_detail->newEntity();
					$amount_entity['project_id'] = $row['project_id'];
					$amount_entity['agency_id'] = $row['agency_id'];
					//$amount_entity['bank'] = $data['bank_name'];
					//$amount_entity['cheque_no'] = $data['cheque_no'];
					$amount_entity['transfer_date'] = $this->ERPfunction->set_date($dt);
					$amount_entity['cheque_amount'] = $data['amount'];
					$amount_entity['transfer_type'] = $data['transfer_type'];
					$add_detail=$erp_advance_amount_detail->save($amount_entity);
				}
			}
		}
		if($check)
		{
			$this->Flash->success(__('Successfully Transfer.'));
			return $this->redirect(['action'=>'viewrequest']);
		}
	}
	
	public function viewadvance($projects_id=null,$from=null,$to=null)
	{
		ini_set('memory_limit', '-1');
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_vendor = TableRegistry::get('erp_vendor'); 
		$vendor_list = $erp_vendor->find();
		$this->set('vendor_list',$vendor_list);
		
		$role = $this->role;
		$this->set('role',$role);
		$erp_advance_request = TableRegistry::get("erp_advance_request");
				$erp_advance_request_detail = TableRegistry::get("erp_advance_request_detail");
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["transfer_date >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["transfer_date <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			//$or1["project_id"] = ($projects_id!=null)?$projects_id:NULL;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
			$result = $erp_advance_request->find()->select($erp_advance_request)->where(['erp_advance_request.project_id'=>$projects_id]);
						$result = $result->innerjoin(
							["erp_advance_request_detail"=>"erp_advance_request_detail"],
							["erp_advance_request.request_id = erp_advance_request_detail.request_id","erp_advance_request_detail.approval_export"=>1])
							->where($or1)->select($erp_advance_request_detail)->hydrate(false)->toArray();
							$this->set('advance_viewlist',$result);
		}
		
			$erp_advance_request = TableRegistry::get('erp_advance_request'); 
			$advance_r_no = $erp_advance_request->find();
		
		
		$this->set('advance_r_no',$advance_r_no);
		
		
		$user = $this->request->session()->read('user_id');
	
		//$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		//var_dump($projects_ids);die;
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["go"]))
			{
			
				$post = $this->request->data;
				$or = array();				
				
				$or["erp_advance_request.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
				$or["erp_advance_request_detail.agency_id IN"] = (!empty($post["agency_id"]) && $post["agency_id"][0] != "All")?$post["agency_id"]:NULL;
				$or["erp_advance_request.advance_req_no IN"] = (!empty($post["adv_r_no"]) && $post["adv_r_no"][0] != "All")?$post["adv_r_no"]:NULL;
				$or["transfer_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["transfer_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				
				
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids))
					{
						$result = $erp_advance_request->find()->select($erp_advance_request)->where(['erp_advance_request.project_id in'=>$projects_ids]);
						$result = $result->innerjoin(
							["erp_advance_request_detail"=>"erp_advance_request_detail"],
							["erp_advance_request.request_id = erp_advance_request_detail.request_id","erp_advance_request_detail.approval_export"=>1])
							->where($or)->select($erp_advance_request_detail)->hydrate(false)->toArray();
					}
					else
					{
						$result=array();
					}
				}
				else
				{
					$result = $erp_advance_request->find()->select($erp_advance_request);
						$result = $result->innerjoin(
							["erp_advance_request_detail"=>"erp_advance_request_detail"],
							["erp_advance_request.request_id = erp_advance_request_detail.request_id","erp_advance_request_detail.approval_export"=>1])
							->select($erp_advance_request_detail)->hydrate(false)->toArray();
				}
				
				//var_dump($result);die;
				$this->set('advance_viewlist',$result);
			}
			if(isset($this->request->data["export_csv"]))
			{
			    
				
				$filename="advance_list.csv";
			    //$rows[] = array("Name of Sub-contractor","Amt. Rs ","TDS","Net Amt.","Name of Bank","A/C No.");
				$data = $this->request->data;
				//var_dump($data);die;
				$erp_advance_request_detail = TableRegistry::get("erp_advance_request_detail");
				$row = $erp_advance_request_detail->get($data['rows']);
				$date = $row['transfer_date'];
				//var_dump($date);die;
				 //$qry = $erp_advance_request_detail->find()->where(['transfer_date'=>$date,'transfer_type'=>'NEFT'])->group('agency_id')->hydrate(false)->toArray();
				
				 $qry = $erp_advance_request_detail->find()->where(['transfer_date'=>$date,'transfer_type'=>'NEFT'])->group('agency_id')->hydrate(false); 
				 $qry = $qry
				 ->select(['sum' => $qry->func()->sum('erp_advance_request_detail.advance_rs')])
					 ->select($erp_advance_request_detail)
				 ->toArray();
				//var_dump($qry);die;
				
				
				//$qry_transefer = $erp_advance_request_detail->find()->where(['transfer_date'=>$date,'transfer_type'=>'Transfer'])->group('agency_id')->hydrate(false)->toArray();
				
				 $qry_transefer = $erp_advance_request_detail->find()->where(['transfer_date'=>$date,'transfer_type'=>'Transfer'])->group('agency_id')->hydrate(false); 
				 $qry_transefer = $qry_transefer
				 ->select(['sum' => $qry_transefer->func()->sum('erp_advance_request_detail.advance_rs')])
					 ->select($erp_advance_request_detail)
				 ->toArray();
				//var_dump($qry_transefer);die;
				
				//$qry_cheque = $erp_advance_request_detail->find()->where(['transfer_date'=>$date,'transfer_type'=>'Single-Cheque'])->group('agency_id')->hydrate(false)->toArray();
				
				 $qry_cheque = $erp_advance_request_detail->find()->where(['transfer_date'=>$date,'transfer_type'=>'Single-Cheque'])->group('agency_id')->hydrate(false); 
				 $qry_cheque = $qry_cheque
				 ->select(['sum' => $qry_cheque->func()->sum('erp_advance_request_detail.advance_rs')])
					 ->select($erp_advance_request_detail)
				 ->toArray();
				//var_dump($qry_cheque);
				$rows = array();
				$rows[] = array("Sr No.","Name of Sub-contractor","Amt. Rs ","TDS","Net Amt.","Transfer Mode","Branch","A/C No.","IFS Code","BENEFICIARY BANK NAME");
				$rows_transfer = array();
				$rows_cheque = array();
				if($this->Usermanage->project_alloted($role)==1){ 
					$user = $this->request->session()->read('user_id');
					$projects = $this->Usermanage->access_project($user);
					$projects = $projects->fetchAll("assoc");
					foreach($projects as $pid)
					{
						$project_ids[] =  $pid['project_id'];
					}
					$i = 0;
					foreach($qry as $retrive)
					{
						$agency_id = $retrive['agency_id'];
						$abc = $erp_advance_request_detail->find()->where(['transfer_date'=>$date,'agency_id'=>$agency_id,'transfer_type'=>'NEFT'])->hydrate(false)->toArray();
						// var_dump($abc);die;
						$pro = array();
						$approval_grp_id = array();
						foreach($abc as $aa)
						{
							$approval_group = json_decode($aa['approval_group']);
							
						
						foreach($approval_group as $idd)
						{
							if(!in_array($idd,$approval_grp_id))
							{
								$approval_grp_id[] = $idd;
							}
							
							$query = $erp_advance_request_detail->find()->where(['id'=>$idd])->select('project_id')->hydrate(false)->toArray();
							if(!empty($query))
							{
								if(!in_array($query[0]['project_id'],$pro))
								{
								$pro[] = $query[0]['project_id'];
								}
								
							}
							
						}
						
						}
						$approval_grp = $approval_grp_id;
						//var_dump($approval_grp);die;
						$project_id = $pro;
						$diff_id = array_diff($project_id,$project_ids);
						
						//var_dump($project_ids);
						//var_dump($project_id);
						//var_dump($diff_id);
						
						$sum = 0;
						foreach($diff_id as $diff)
						{
							$qry = $erp_advance_request_detail->find()->where(['id IN'=>$approval_grp,'project_id'=>$diff])->select('advance_rs')->hydrate(false)->toArray();
							//var_dump($qry);die;
							$rs = $qry[0]['advance_rs'];
							$sum = $rs + $sum;
						}
						$cut_amount = $sum;
						//var_dump($cut_amount);die;
						$find = $erp_advance_request_detail->get($retrive['id']);
						$cheque_amount = $retrive['sum'];
						$final_amount = $cheque_amount - $cut_amount;
						
						$export = array();
						$i++;
						$export[] = $i;
						$export[] = $this->ERPfunction->get_agency_name($retrive['agency_id']);
						$export[] = $final_amount;
						$export[] = $tds = $final_amount * 1 /100;
						$export[] = $final_amount - $tds;
						$export[] = $retrive['transfer_type'];
						//$export[] = $retrive['bank'];
						$export[] = $this->ERPfunction->get_agency_branch($retrive['agency_id']);
						$export[] = $this->ERPfunction->get_agency_accountno($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_ifs_code($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_bank($retrive['agency_id']);
						//var_dump($rows);die;
						$rows[] = $export;
					}	
					
					foreach($qry_transefer as $retrive)
					{
						$agency_id = $retrive['agency_id'];
						$abc = $erp_advance_request_detail->find()->where(['transfer_date'=>$date,'agency_id'=>$agency_id,'transfer_type'=>'Transfer'])->hydrate(false)->toArray();
						// var_dump($abc);die;
						$pro = array();
						foreach($abc as $aa)
						{
							$approval_group = json_decode($aa['approval_group']);
							//var_dump($approval_group);die;
						
						foreach($approval_group as $idd)
						{
							$query = $erp_advance_request_detail->find()->where(['id'=>$idd])->select('project_id')->hydrate(false)->toArray();
							if(!empty($query))
							{
							$pro[] = $query[0]['project_id'];
							}
							
						}
						
						}
						$project_id = $pro;
						$diff_id = array_diff($project_id,$project_ids);
						
						$sum = 0;
						foreach($diff_id as $diff)
						{
							$qry = $erp_advance_request_detail->find()->where(['id IN'=>$approval_group,'project_id'=>$diff])->select('advance_rs')->hydrate(false)->toArray();
							$rs = $qry[0]['advance_rs'];
							$sum = $rs + $sum;
						}
						$cut_amount = $sum;
						
						$find = $erp_advance_request_detail->get($retrive['id']);
						$cheque_amount = $retrive['sum'];
						$final_amount1 = $cheque_amount - $cut_amount;
						//var_dump($final_amount);die;
						//var_dump($project_ids);
						//var_dump($project_id);
						//var_dump($diff_id);die;
						
						$export = array();
						$i++;
						$export[] = $i;
						$export[] = $this->ERPfunction->get_agency_name($retrive['agency_id']);
						$export[] = $final_amount1;
						$export[] = $tds = $final_amount1 * 1 /100;
						$export[] = $final_amount1 - $tds;
						$export[] = $retrive['transfer_type'];
						//$export[] = $retrive['bank'];
						$export[] = $this->ERPfunction->get_agency_branch($retrive['agency_id']);
						$export[] = $this->ERPfunction->get_agency_accountno($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_ifs_code($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_bank($retrive['agency_id']);
						//var_dump($rows);die;
						$rows_transfer[] = $export;
					}
					
					foreach($qry_cheque as $retrive)
					{	
						$agency_id = $retrive['agency_id'];
						$abc = $erp_advance_request_detail->find()->where(['transfer_date'=>$date,'agency_id'=>$agency_id,'transfer_type'=>'Single-Cheque'])->hydrate(false)->toArray();
						// var_dump($abc);die;
						$pro = array();
						foreach($abc as $aa)
						{
							$approval_group = json_decode($aa['approval_group']);
							//var_dump($approval_group);die;
						
						foreach($approval_group as $idd)
						{
							$query = $erp_advance_request_detail->find()->where(['id'=>$idd])->select('project_id')->hydrate(false)->toArray();
							if(!empty($query))
							{
							$pro[] = $query[0]['project_id'];
							}
							
						}
						
						}
						$project_id = $pro;
						//var_dump($project_id);die;
						$diff_id = array_diff($project_id,$project_ids);
						
						$sum = 0;
						foreach($diff_id as $diff)
						{
							$qry = $erp_advance_request_detail->find()->where(['id IN'=>$approval_group,'project_id'=>$diff])->select('advance_rs')->hydrate(false)->toArray();
							$rs = $qry[0]['advance_rs'];
							$sum = $rs + $sum;
						}
						$cut_amount = $sum;
						//var_dump($cut_amount);die;
						$find = $erp_advance_request_detail->get($retrive['id']);
						$cheque_amount = $retrive['sum'];
						//var_dump($cheque_amount);die;
						$final_amount2 = $cheque_amount - $cut_amount;
						//var_dump($final_amount2);
						//var_dump($project_ids);
						//var_dump($project_id);
						//var_dump($diff_id);die;
						
						$export = array();
						$i++;
						$export[] = $i;
						$export[] = $this->ERPfunction->get_agency_name($retrive['agency_id']);
						$export[] = $final_amount2;
						$export[] = $tds = $final_amount2 * 1 /100;
						$export[] = $final_amount2 - $tds;
						$export[] = $retrive['transfer_type'];
						//$export[] = $retrive['bank'];
						$export[] = $this->ERPfunction->get_agency_branch($retrive['agency_id']);
						$export[] = $this->ERPfunction->get_agency_accountno($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_ifs_code($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_bank($retrive['agency_id']);
						//var_dump($rows);die;
						$rows_cheque[] = $export;
					}
					
					
				}
				else
				{
					$i = 0;
					foreach($qry as $retrive)
					{
						$export = array();
						$i++;
						$export[] = $i;
						$export[] = $this->ERPfunction->get_agency_name($retrive['agency_id']);
						$export[] = $retrive['sum'];
						$export[] = $tds = $retrive['sum'] * 1 /100;
						$export[] = $retrive['sum'] - $tds;
						$export[] = $retrive['transfer_type'];
						//$export[] = $retrive['bank'];
						$export[] = $this->ERPfunction->get_agency_branch($retrive['agency_id']);
						$export[] = $this->ERPfunction->get_agency_accountno($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_ifs_code($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_bank($retrive['agency_id']);
						//var_dump($rows);die;
						$rows[] = $export;
					}	

					foreach($qry_transefer as $retrive)
					{
						$export = array();
						$i++;
						$export[] = $i;
						$export[] = $this->ERPfunction->get_agency_name($retrive['agency_id']);
						$export[] = $retrive['sum'];
						$export[] = $tds = $retrive['sum'] * 1 /100;
						$export[] = $retrive['sum'] - $tds;
						$export[] = $retrive['transfer_type'];
						//$export[] = $retrive['bank'];
						$export[] = $this->ERPfunction->get_agency_branch($retrive['agency_id']);
						$export[] = $this->ERPfunction->get_agency_accountno($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_ifs_code($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_bank($retrive['agency_id']);
						//var_dump($rows);die;
						$rows_transfer[] = $export;
					}	
					
					foreach($qry_cheque as $retrive)
					{
						$export = array();
						$i++;
						$export[] = $i;
						$export[] = $this->ERPfunction->get_agency_name($retrive['agency_id']);
						$export[] = $retrive['sum'];
						$export[] = $tds = $retrive['sum'] * 1 /100;
						$export[] = $retrive['sum'] - $tds;
						$export[] = $retrive['transfer_type'];
						//$export[] = $retrive['bank'];
						$export[] = $this->ERPfunction->get_agency_branch($retrive['agency_id']);
						$export[] = $this->ERPfunction->get_agency_accountno($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_ifs_code($retrive['agency_id'])."�";
						$export[] = $this->ERPfunction->get_agency_bank($retrive['agency_id']);
						//var_dump($rows);die;
						$rows_cheque[] = $export;
					}	
				}
				
				$blank = array();
				$blank[] = array("","","","","","","","","","");
						
								
								$fp = fopen(TMP .$filename, 'w');
		//var_dump($rows);die;
		  //fputcsv($fp, $export);
		  foreach ($rows as $fields) {
		   fputcsv($fp, $fields);
		}
		
		if(!empty($qry))
		{
			foreach ($blank as $fields) {
			   fputcsv($fp, $fields);
			}
		
		}
		
		foreach ($rows_transfer as $fields) {
		   fputcsv($fp, $fields);
		}
		
		if(!empty($qry_transefer))
		{
			foreach ($blank as $fields) {
			   fputcsv($fp, $fields);
			}
		}
		
		foreach ($rows_cheque as $fields) {
		   fputcsv($fp, $fields);
		}
		
		fclose($fp);
		ob_clean();
		$file= TMP .$filename;//file location
		$mime = 'text/plain';
		header('Content-Type:application/force-download');
		header('Pragma: public');       // required
		header('Expires: 0');           // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		readfile($file);		
		exit;
			}
			
		if(isset($this->request->data["export_csv_all"]))
		{
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$filename = "advance.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		
		if(isset($this->request->data["export_pdf_all"]))
		{			
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$this->set("rows",$rows);
			$this->render("advance_pdf");
		}
				
		}
		
		
	}
	
	public function createaccount()
	{
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		if($this->request->is('post'))
		{
			$data = $this->request->data;
			$erp_account = TableRegistry::get('erp_account');
			$entity_data = $erp_account->newEntity();
			
			$entity_data['project_id']= $data['project_id'];
			$entity_data['account_name']= $data['account_name'];
			$entity_data['account_no'] = $data['account_no'];
			$entity_data['bank'] = $data['bank'];
			$entity_data['branch'] = $data['branch'];
			$entity_data['ifsc_code'] = $data['ifsc_code'];
			
			if($erp_account->save($entity_data))
			{
                $this->Flash->success(__('Record Insert Successfully.'));	
                 return $this->redirect(['action' => 'createaccount']);
			}
		}
	}
	
	public function expensehead()
	{
		if($this->request->is('post'))
		{
			$data = $this->request->data;
			$erp_expense = TableRegistry::get('erp_expense');
			$entity_data = $erp_expense->newEntity();
			$entity_data['expence_head_name']= $data['expense_head_name'];
			$entity_data['expence_type']= $data['expense_type'];
			if($erp_expense->save($entity_data))
			{
                $this->Flash->success(__('Record Insert Successfully.'));	
                 return $this->redirect(['action' => 'expensehead']);
			}
		}
	}
	
	public function editexpensehead($id)
	{
		$erp_expense = TableRegistry::get('erp_expense');
		$head_detail = $erp_expense->get($id);
		$this->set("head_detail",$head_detail);
		if($this->request->is('post'))
		{
			$post = $this->request->data;		
				
				$row = $erp_expense->get($id);
				$row['expence_head_name'] = $post["expense_head_name"];
				$row['expence_type'] = $post["expense_type"];
				
				if($erp_expense->save($row))
				{
					$this->Flash->success(__('Record Update Successfully')); 
					//return $this->redirect(['action' => 'viewexpensehead']);
					echo "<script>window.close();</script>";
				}
		}
	}
	
	public function amountissued()
	{
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_account = TableRegistry::get('erp_account'); 
		$account_list = $erp_account->find();
		$this->set('account_list',$account_list);
		
		if($this->request->is('post'))
		{
			$data = $this->request->data;
			$erp_amount_issue = TableRegistry::get('erp_amount_issue');
			$entity_data = $erp_amount_issue->newEntity();
			$entity_data['project_id']=$data['project_id'];
			$entity_data['voucher_no']=$data['voucher_no'];
			$entity_data['date'] = $this->ERPfunction->set_date($data['pr_date']);
			$entity_data['time'] = $data['pr_time'];
			$entity_data['account_id'] = $data['account_id'];
			$entity_data['account_no'] = $data['account_no'];
			$entity_data['bank'] = $data['bank'];
			$entity_data['branch'] = $data['branch'];
			$entity_data['ifsc_code'] = $data['ifsc_code'];
			$entity_data['amount_issue'] = $data['amount_issued'];
			$entity_data['payment_type'] = $data['payment_type'];
			if($data['payment_type'] == 'cheque')
			{
				$entity_data['second_bank'] = $data['bnk'];
				$entity_data['cheque_no'] = $data['cheque_no'];
				$entity_data['cheque_date'] = $this->ERPfunction->set_date($data['cheque_date']);
			}
			else
			{
				$entity_data['receiver_name'] = $data['receiver'];
			}
			
			$entity_data['remark'] = $data['remark'];
			
			if($erp_amount_issue->save($entity_data))
			{
                $this->Flash->success(__('Record Insert Successfully.'));
                 return $this->redirect(['action' => 'index']);
			 }
		}
	}
	
	public function addexpence()
	{
		$erp_expence_add = TableRegistry::get('erp_expence_add');
		
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_account = TableRegistry::get('erp_account'); 
		$account_list = $erp_account->find();
		$this->set('account_list',$account_list);
		
		$erp_expense = TableRegistry::get('erp_expense'); 
		$expense_list = $erp_expense->find();
		$this->set('expence_head_list',$expense_list);
		
		$user = $this->request->session()->read('user_id');
		
		if($this->request->is('post'))
		{
			$data = $this->request->data;
			$record = $erp_expence_add->find()->where(['project_id'=>$data['project_id'],'voucher_no'=>$data['voucher_no']]);
			$cnt = $record->count();
			
			if($cnt == 0)
			{
				$entity_data = $erp_expence_add->newEntity();
				$entity_data['project_id']=$data['project_id'];
				$entity_data['account_id']=$data['account_id'];
				$entity_data['account_no'] = $data['account_no'];
				$entity_data['bank'] = $data['bank'];
				$entity_data['voucher_no'] = $data['voucher_no'];
				$entity_data['date'] = $this->ERPfunction->set_date($data['pr_date']);
				$entity_data['expence_head'] = $data['expence_head'];
				$entity_data['given_to'] = $data['given_to'];
				$entity_data['payment_type'] = $data['payment_type'];
				
				if($erp_expence_add->save($entity_data))
				{
					$this->Flash->success(__('Record Insert Successfully.'));
					 $id = $entity_data->id;
					 $this->ERPfunction->add_expence_detail($this->request->data['expense'],$id,$this->request->data['total_amount'],$this->request->data['total_words'],$user);
					 return $this->redirect(['action' => 'addexpence']);
				 }
			}else{
				$this->Flash->success(__('Duplicate Voucher No,Please try again.'));
				return $this->redirect(['action' => 'addexpence']);
			}
			
			
		}
	}
	
	public function expencealert()
	{
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_account = TableRegistry::get('erp_account'); 
		$account_list = $erp_account->find();
		$this->set('account_list',$account_list);
		
		$user = $this->request->session()->read('user_id');
		//var_dump($user);die;
		//$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		
		//$expence_list = $this->Usermanage->fetch_view_expence_detail($this->user_id);
		//$this->set('expence_list',$expence_list);
		$role = $this->role;
		$this->set('role',$role);
		
		if($this->request->is("post"))
		{
			$erp_expence_add = TableRegistry::get("erp_expence_add");
				$erp_expence_detail = TableRegistry::get("erp_expence_detail");
				$post = $this->request->data;
				$or = array();				
				
				$or["erp_expence_add.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
				$or["erp_expence_add.account_id IN"] = (!empty($post["account_id"]) && $post["account_id"][0] != "All")?$post["account_id"]:NULL;
				//$or["erp_advance_request.advance_req_no IN"] = (!empty($post["adv_r_no"]) && $post["adv_r_no"][0] != "All")?$post["adv_r_no"]:NULL;
				//$or["transfer_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				//$or["transfer_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids))
					{
						$result = $erp_expence_add->find()->where(['erp_expence_add.project_id in'=>$projects_ids,$or])->select($erp_expence_add)->hydrate(false)->toArray();
						// $result = $result->innerjoin(
							// ["erp_expence_detail"=>"erp_expence_detail"],
							// ["erp_expence_add.id = erp_expence_detail.exp_id","erp_expence_detail.approval_accountant"=>0])
							// ->where($or)->select($erp_expence_detail)->hydrate(false)->toArray();
					}
					else
					{
						$result=array();
					}
				}
				else
				{
					$result = $erp_expence_add->find()->where($or)->select($erp_expence_add)->hydrate(false)->toArray();
					// $result = $result->innerjoin(
							// ["erp_expence_detail"=>"erp_expence_detail"],
							// ["erp_expence_add.id = erp_expence_detail.exp_id","erp_expence_detail.approval_accountant"=>0])
							// ->where($or)->select($erp_expence_detail)->hydrate(false)->toArray();
				//var_dump($result);die;
				}
				//ebug($result);die;
				$this->set('expence_list',$result);
				
		}
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$filename = "expence_alert.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{			
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$this->set("rows",$rows);
			$this->render("expence_alertpdf");
		}
		
	}
	
	public function editexpence($id)
	{
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_account = TableRegistry::get('erp_account'); 
		$account_list = $erp_account->find();
		$this->set('account_list',$account_list);
		
		$erp_expense = TableRegistry::get('erp_expense'); 
		$expense_list = $erp_expense->find();
		$this->set('expence_head_list',$expense_list);
		
		
		$erp_expence_add = TableRegistry::get("erp_expence_add");
		$erp_expence_detail = TableRegistry::get("erp_expence_detail");
		
		$expence_list = $erp_expence_add->get($id)->toArray();
		$this->set('expence_list',$expence_list);
		
		
		$detail_data = $erp_expence_detail->find("all")->where(["exp_id"=>$id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);	

			if($this->request->is("post"))
			{
				$data = $this->request->data;		
				
				$row = $erp_expence_add->get($id);
				$row['project_id']=$data['project_id'];
				$row['account_id']=$data['account_id'];
				$row['account_no'] = $data['account_no'];
				$row['bank'] = $data['bank'];
				$row['voucher_no'] = $data['voucher_no'];
				$row['date'] = $this->ERPfunction->set_date($data['pr_date']);
				$row['expence_head'] = $data['expence_head'];
				$row['given_to'] = $data['given_to'];
				$row['payment_type'] = $data['payment_type'];
				
				if($erp_expence_add->save($row))
				{
					 $this->ERPfunction->edit_expence_detail($this->request->data['expense'],$data['total_amount'],$data['total_words']);
					$this->Flash->success(__('Record Update Successfully')); 
					//return $this->redirect(['action' => 'expencealert']);
					echo "<script>window.close();</script>";
				}
			}
	}
	
	public function deleteexpense($id)
	{
		$erp_expence_detail = TableRegistry::get('erp_expence_detail');
		$delete_ok = $erp_expence_detail->deleteAll(["exp_id"=>$id]);
		if($delete_ok)
		{				
			$tbl = TableRegistry::get("erp_expence_add");
			$row = $tbl->get($id);
			if($tbl->delete($row))
			{
				$this->Flash->success(__('Record delete Successfully.'));
				return $this->redirect(['action'=>'expencealert']);
			}
		}
	}
	
	public function printaccountexpence($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$erp_expence_add = TableRegistry::get("erp_expence_add");
		$erp_expence_detail = TableRegistry::get('erp_expence_detail');
		$detail_list = $erp_expence_detail->find()->where(array('exp_id'=>$eid));
		$this->set('detail_list',$detail_list);
		$data = $erp_expence_add->get($eid);
		$this->set("erp_expence_list",$data->toArray());			
	}
	
	public function sitetransactions()
	{
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_account = TableRegistry::get('erp_account'); 
		$account_list = $erp_account->find();
		$this->set('account_list',$account_list);
		
		$erp_expense = TableRegistry::get('erp_expense'); 
		$expence_head = $erp_expense->find();
		$this->set('expence_head',$expence_head);
		
		$role = $this->role;
		$this->set('role',$role);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["go"]))
			{
				//var_dump($post);die;
				$or = array();				
				$erp_amount_issue = TableRegistry::get("erp_amount_issue");
				$or["erp_amount_issue.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
				$or["erp_amount_issue.account_id IN"] = (!empty($post["account_id"]) && $post["account_id"][0] != "All")?$post["account_id"]:NULL;
				$or["voucher_no"] = ($post["voucher"] != "")?$post["voucher"]:NULL;
				$or["erp_amount_issue.payment_type IN"] = (!empty($post["payment_type"]) && $post["payment_type"][0] != "All")?$post["payment_type"]:NULL;
				$or["date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				//debug($or);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				
							$result = $erp_amount_issue->find()->where($or)->hydrate(false)->toArray();
				
				//var_dump($result);die;
				//$this->set('amount_list',$result);
				
				//second result
				$erp_expence_add = TableRegistry::get("erp_expence_add");
				$erp_expence_detail = TableRegistry::get("erp_expence_detail");
				$data = $this->request->data;
				$or1 = array();				
				
				$or1["erp_expence_add.project_id IN"] = (!empty($data["project_id"]) && $data["project_id"][0] != "All" )?$data["project_id"]:NULL;
				//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
				$or1["erp_expence_add.account_id IN"] = (!empty($data["account_id"]) && $data["account_id"][0] != "All")?$data["account_id"]:NULL;
				$or1["erp_expence_add.expence_head IN"] = (!empty($data["expence_head"]) && $data["expence_head"][0] != "All")?$data["expence_head"]:NULL;
				$or1["voucher_no"] = ($data["voucher"] != "")?$data["voucher"]:NULL;
				$or1["erp_expence_add.payment_type IN"] = (!empty($post["payment_type"]) && $post["payment_type"][0] != "All")?$post["payment_type"]:NULL;
				$or1["date >="] = ($data["from_date"] != "")?date("Y-m-d",strtotime($data["from_date"])):NULL;
				$or1["date <="] = ($data["to_date"] != "")?date("Y-m-d",strtotime($data["to_date"])):NULL;
				
				$keys1 = array_keys($or1,"");				
				foreach ($keys1 as $k)
				{unset($or1[$k]);}
				// debug($data);
				// debug($or1);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				$result1 = $erp_expence_add->find()->select($erp_expence_add);
				$result1 = $result1->innerjoin(
							["erp_expence_detail"=>"erp_expence_detail"],
							["erp_expence_add.id = erp_expence_detail.exp_id"])
							->where($or1)->select($erp_expence_detail)->group('erp_expence_detail.exp_id')->hydrate(false)->toArray();
							//$result1 = array_merge($result1,$result1['erp_expence_detail']);
				//var_dump($result);			
				// debug($result1);die;
				//$this->set('expence_list',$result1);
				$result_date = array();
				$result1_date = array();
				$merge_date = array();
				foreach($result as $key=>$row)
				{
					$result_date[] = $result[$key]["date"];
				}
				//var_dump($result_date);
				
				foreach($result1 as $key=>$row)
				{
					$result1_date[] = $result1[$key]["date"];
				}
				//var_dump($result1_date);
				
				
				
				foreach($result1_date as $date)
				{
					// if(!in_array($date,$merge_date))
					// {
						$merge_date[] = $date;
					//}
				}
				
				foreach($result_date as $date)
				{
					$merge_date[] = $date;
				}
				
				//debug($merge_date);die;
				
				$merge = array();
				// foreach($result as $key=>$row)
				// {
					
					// $merge[$key] = $row;
					// $merge[$key]["expense_details"] = array();
					// $merge[$key]["erp_expense_details"] = array();
					// $d1 = $result[$key]["date"];		
					
					// foreach($result1 as $id=>$val)
					// {
						// $d2 = $val["date"];
						// if($d1 == $d2)
						// {						
							// $merge[$key]["expense_details"] = $val;
							// $merge[$key]["erp_expense_details"] = $val['erp_expence_detail'];
						// }						
					// }
				
				// }
				
				for($j = 0; $j < count($merge_date); $j ++) {
					for($i = 0; $i < count($merge_date)-1; $i ++){

						if($merge_date[$i] > $merge_date[$i+1]) {
							$temp = $merge_date[$i+1];
							$merge_date[$i+1]=$merge_date[$i];
							$merge_date[$i]=$temp;
						}       
					}
				}
				
				$flag = false;
				foreach($merge_date as $d_key=>$m_date)
				{			
					foreach($result as $key=>$row)
					{
						//debug($result);
						//debug($result[$key]);
						if($m_date == $result[$key]["date"])
						{							
							$merge[$d_key] = $row;
							$merge[$d_key]["expense_details"] = array();
							$merge[$d_key]["erp_expense_details"] = array();
							$flag = true;
							unset($result[$key]);
							//debug($result);
						}	
						else
						{
							if($flag == false)
							{$merge[$d_key] = array();}
							$merge[$d_key]["expense_details"] = array();
							$merge[$d_key]["erp_expense_details"] = array();
							$d1 = $result[$key]["date"];
						}
					}
					
					foreach($result1 as $key=>$val)
					{
						//debug($result1[$key]);
					//debug($result1[$key]);
						$d2 = $val["date"];
						//debug($m_date);
						
						if($m_date == $d2)
						{				
							//debug($m_date ." ==". $d2);
							$merge[$d_key]["expense_details"] = $val;
							$merge[$d_key]["erp_expense_details"] = $val['erp_expence_detail'];
							 unset($result1[$key]);
							 break;
							 //debug($result1);
							
						}
						
					}
				}
				
				//die;
				// debug($merge);die;
				$this->set('transaction',$merge);
				
			}
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				//var_dump($rows);die;
				$filename = "sitetransaction.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("sitetransactionpdf");
			}
		}
	}
	
	public function viewexpensehead()
	{
		$erp_expense = TableRegistry::get('erp_expense'); 
		$expense_list = $erp_expense->find();
		$this->set('expense_list',$expense_list);
	}
	
	public function viewexpence($id)
	{
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_account = TableRegistry::get('erp_account'); 
		$account_list = $erp_account->find();
		$this->set('account_list',$account_list);
		
		$erp_expense = TableRegistry::get('erp_expense'); 
		$expense_list = $erp_expense->find();
		$this->set('expence_head_list',$expense_list);
		
		
		$erp_expence_add = TableRegistry::get("erp_expence_add");
		$erp_expence_detail = TableRegistry::get("erp_expence_detail");
		
		$expence_list = $erp_expence_add->get($id)->toArray();
		$this->set('expence_list',$expence_list);
		
		
		$detail_data = $erp_expence_detail->find("all")->where(["exp_id"=>$id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);
	}
	
	public function incomedelete($id){
		$erp_amount_issue = TableRegistry::get('erp_amount_issue'); 
		//$this->request->is(['post','delete']);
		
		$delete_income =$erp_amount_issue->get($id);

		if($erp_amount_issue->delete($delete_income))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 'default', array('class' => 'success'));	
		}
		return $this->redirect(array('controller'=>'Accounts','action'=>'sitetransactions'));
    }
	
	public function expensedelete($id)
	{
		$erp_expence_detail = TableRegistry::get('erp_expence_detail');
		$delete_ok = $erp_expence_detail->deleteAll(["exp_id"=>$id]);
		if($delete_ok)
		{				
			$tbl = TableRegistry::get("erp_expence_add");
			$row = $tbl->get($id);
			if($tbl->delete($row))
			{
				$this->Flash->success(__('Record delete Successfully.'));
				return $this->redirect(array('controller'=>'Accounts','action'=>'sitetransactions'));
			}
		}
    }
	
	public function viewamountissued($id)
	{
		$erp_amount_issue = TableRegistry::get('erp_amount_issue'); 
		$view_list =$erp_amount_issue->get($id);
		$this->set('view_list',$view_list);
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_account = TableRegistry::get('erp_account'); 
		$account_list = $erp_account->find();
		$this->set('account_list',$account_list);
	}
	
	public function inwardpayment()
	{
		ini_set('memory_limit', '-1');
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		// $agency_tbl = TableRegistry::get("erp_agency");
		// $agency_list = $agency_tbl->find("All")->toArray();
		// $this->set('agency_list',$agency_list);	
		
		$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $erp_projects->find();
		$this->set('projects',$projects);
		
		$erp_inward_bill=TableRegistry::get('erp_inward_bill');
    	$new_party_info=$erp_inward_bill->find()->where(['party_type'=>'new'])->select('new_party_name')
		->group('new_party_name')->hydrate(false)->toArray();
		$new_party = array();
		foreach($new_party_info as $party)
		{
			$a = str_replace(' ','',$party['new_party_name']);
			$new_party[] = $a;
		}
		$new_party_name = array_unique($new_party);
		$this->set('new_party',$new_party_name);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			//debug($post);die;
			$cheque_date = $post['cheque_date'];
			$file = $post['inward_doc'];
			if($_FILES['inward_doc']['tmp_name'] != '')
			{
				$pdf_name = $this->ERPfunction->uploadinwardpdf($file);
			}
			else
			{
				$pdf_name = 'empty.pdf';
			}
						
			if($post['party_type'] == 'oldparty')
			{
				if($post['payment_type'] == 'advance')
				{
					$erp_inward_advance=TableRegistry::get('erp_inward_advance');
					$row = $erp_inward_advance->newEntity();
					$row['old_party_id'] = $post['party_id'];
					$row['party_email'] = $post['party_email'];
					$row['assign_project'] = implode(",",$post['assign_projects']);
					$row['payment_type'] = $post['payment_type'];
					$row['bank_name'] = $post['bank_name'];
					$row['cheque_no'] = $post['cheque_no'];
					$row['cheque_amount'] = $post['cheque_amount'];
					$row['transfer_type'] = $post['transfer_type'];
					$row['created_date'] = date('Y-m-d H:i:s');
					
					if($erp_inward_advance->save($row))
					{
						
						/* MAIL TO PARTY */
						if($post['party_name'] == 'agency')
						{
							$party_name =  $this->ERPfunction->get_vendor_name_by_code($post['party_id']);
						}
						else
						{
							$party_name = $this->ERPfunction->get_vendor_name($post['party_id']);										
						}
						if($post['transfer_type'] != 'office')
						{
							$transfer_type = $post['transfer_type'];
						}
						else
						{
							$transfer_type = 'Please Collect Cheque from Corporate Office';
						}
						$bank = $post['bank_name'];
						$cheque_no = $post['cheque_no'];
						// $cheque_date = date("d-m-Y");
						$cheque_amount = $post['cheque_amount'];
						
						$party_emails = array();
						$party_email = explode(",",$post['party_email']);
						
						if(!empty($party_email))
						{
							foreach($party_email as $mail)
							{
								$party_emails[] = $mail;
							}
						}
						//$role = ["projectdirector","siteaccountant","constructionmanager"];
						$role =$this->ERPfunction->get_mail_list_by_payment('"payment_notification"','Alloted');
						
						//$cat_ids = $this->ERPfunction->get_cat_id_by_title($designation);
									
						$all_email = array();
						foreach($post['assign_projects'] as $project_id)
						{
							$temp = $this->ERPfunction->get_email_id_by_project_from_user($project_id,$role);
							$all_email = array_merge($temp,$all_email);
						}
						
						//$role1 = ['erphead','erpmanager','md','purchasehead','purchasemanager','erpoperator','ceo'];
						$role1 =$this->ERPfunction->get_mail_list_by_payment('"payment_notification"','default');
						
						$erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role1);
						$all_email = array_merge($erp_email,$all_email);
						$all_email = array_merge($all_email,$party_emails);
						$all_email = array_unique($all_email);
						
						// $emails[] = "vipul.desai@yashnandeng.com";
						// $emails[] = $party_email;
						// $emails = array_merge($emails,$all_email);
							
						$email=array_values(array_diff($all_email,array("null","")));
						
						$to = implode(",",$email);
						
						$url = $this->ERPfunction->get_signed_url($pdf_name);
						 // $url = "http://192.168.1.29/svn/cakephp/cake_yashnanderp_2/nghome/{$pdf_name}";
						$fileatt = "test.pdf"; // Path to the file                  
						// $fileatt = $url; // Path to the file                  
						$fileatt_type = "application/pdf"; // File Type  
						$fileatt_name = "inward.pdf"; // Filename that will be used for the file as the attachment  
						$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
						$email_subject = "YashNand: Bill Payment Notification"; // The Subject of the email  
						$message = "Sir / Madam,<br />";
						$message .= "Your bills have been paid.Details for the same are below [After deduction of Advances, Credits, Debits, Retention etc.]";
						$message .= "<br /><br /><p><strong>Party's Name:</strong> {$party_name}</p>";
						$message .= "<p><strong>Type of Payment:</strong> Advance</p>";
						// $message .= "<p><strong>Invoice Dates:</strong> {$invoice_dates}</p>";
						// $message .= "<p><strong>Total Amount:</strong> {$total_amount}</p>";
						
						$message .= "<br />";
						/* $message .= "<p><strong>Bill Inward No.: {$bills}</p>";*/
						$message .= "<p><strong>Bank:</strong> {$bank}</p>";
						$message .= "<p><strong>Cheque No:</strong> {$cheque_no}</p>";
						$message .= "<p><strong>Cheque Date:</strong> {$cheque_date}</p>";
						$message .= "<p><strong>Cheque Amount(Rs.):</strong> {$cheque_amount}</p>";
						$message .= "<p><strong>Transfer Type:</strong> {$transfer_type}</p>";
						$message .= "<br /><br />";
						$message .= "<p><strong>Please collect your cheque from Corporate Office during working hours.</strong></p>";
						$message .= "<br /><br />";			
						$message .= "Thank You.";
						
						$message .= "<br /><br />";			
						$message .= "-------------------------------------------------------------------------------------------------------------";
						$message .= "<br /><br />";
						
						$message .= "Please Do Not Reply to this E-mail ID. This E-mail is system generated and may have some problems. For conformation and/or queries,<br />please contact:";
						$message .= "<p><strong>Contact No: 079-23240202</strong></p>";
						$message .= "<p><strong>E-mail ID:</strong> <a href='mailto:mahesh.chaudhary@yashnandeng.com'>mahesh.chaudhary@yashnandeng.com</a></p>";
						
						$message .= "-------------------------------------------------------------------------------------------------------------";
						
						$email_to = $to; // Who the email is to
						$email_to = explode(",",$email_to);
						$email_to = array_filter($email_to, function($value) { return $value !== ''; });
						$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
						
						$headers = "From: ".$email_from;  
						$file = fopen($url,'rb');  


						$contents = file_get_contents($url); // read the remote file
						touch('temp.pdf'); // create a local EMPTY copy
						file_put_contents('temp.pdf', $contents);


						$data = fread($file,filesize("temp.pdf"));  
						// $data = fread($file,19189);  
						fclose($file);  
						$semi_rand = md5(time());  
						$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
							
						$headers .= "\nMIME-Version: 1.0\n" .  
									"Content-Type: multipart/mixed;\n" .  
									" boundary=\"{$mime_boundary}\"";
						$email_message_old = "";
						$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
										"--{$mime_boundary}\n" .  
										"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
									   "Content-Transfer-Encoding: 7bit\n\n" .  
						$email_message_old .= "\n\n";  
						// $data = chunk_split(base64_encode($data));   
						$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
						$dir_to_save = WWW_ROOT;
						// echo $dir_to_save;die;
						$pdf_name=$this->ERPfunction->generate_autoid('payment-').time().'.pdf';
						file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
						$email_message_old .= "--{$mime_boundary}\n" .  
										  "Content-Type: {$fileatt_type};\n" .  
										  " name=\"{$fileatt_name}\"\n" .  
										  //"Content-Disposition: attachment;\n" .  
										  //" filename=\"{$fileatt_name}\"\n" .  
										  "Content-Transfer-Encoding: base64\n\n" .  
										 $data .= "\n\n" .  
										  "--{$mime_boundary}--\n";
						$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->attachments(['Attachment' => $dir_to_save.$pdf_name])
						  ->send($message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
										  
						// $ok = @mail($to, $email_subject, $message, $headers);
						
					}
				}
				else
				{
					//invoice_type
					if($post['transfer_type'] != 'office')
					{
						$transfer_type = $post['transfer_type'];
					}
					else
					{
						$transfer_type = 'Please Collect Cheque from Corporate Office';
					}
					$cheque_amount = $post['cheque_amount'];
					$cheque_no = $post['cheque_no'];
					$bank = $post['bank_name'];
					// $cheque_date = date("d-m-Y");
					
					// $inward = $post['inward'];
					// $project_ids = array();
					// foreach($inward['inward_bill_id'] as $key => $data)
					// {
						// $inward_id = $inward['inward_bill_id'][$key];
						// $row = $erp_inward_bill->find()->where(['inward_bill_id' => $inward_id])->hydrate(false)->toArray();
						// $project_ids[] = $row[0]['project_id'];
						
						// $user_create=$this->request->session()->read('user_id');
						// date_default_timezone_set('asia/kolkata');
						// $date=date('Y-m-d H:i:s');
						// $query = $erp_inward_bill->query();
						// $query->update()
						// ->set(['status_inward'=>'completed',
						// "paid_amount"=>$cheque_amount,
						// "payment_date"=>$date,
						// "cheque_no"=>$cheque_no,
						// "bank"=>$bank,
						// "is_notification"=>1,
						// 'accept_by'=>$user_create,'accept_date'=>$date])
						// ->where(['inward_bill_id' => $inward_id])
						// ->execute();
					// }
					//$project_ids = array_unique($project_ids);
					// if($query)
					// {
						if($post['party_name'] == 'agency')
						{
							$party_name =  $this->ERPfunction->get_agency_name_by_code($post['party_id']);
						}
						else
						{
							$party_name = $this->ERPfunction->get_vendor_name($post['party_id']);										
						}
						
						// $invoice_no = array();
						// $invoice_date = array();
		
						// foreach($inward['inward_bill_id'] as $key => $data)
						// {
							// $invoice_no[] = $inward['invoice_no'][$key];
							// $invoice_date[] = date("d-m-Y",strtotime($inward['bill_date'][$key]));
						// }
						//$invoices = implode(",",$invoice_no);
						//$invoices_date = implode(",",$invoice_date);
						$party_emails = array();
						$party_email = explode(",",$post['party_email']);
						if(!empty($party_email))
						{
							foreach($party_email as $mail)
							{
								$party_emails[] = $mail;
							}
						}
						
						$role =$this->ERPfunction->get_mail_list_by_payment('"payment_notification"','Alloted');
						//$cat_ids = $this->ERPfunction->get_cat_id_by_title($designation);
									
						$all_email = array();
						foreach($post['assign_projects'] as $project_id)
						{
							$temp = $this->ERPfunction->get_email_id_by_project_from_user($project_id,$role);
							$all_email = array_merge($temp,$all_email);
						}
						$role1 =$this->ERPfunction->get_mail_list_by_payment('"payment_notification"','default');
						$erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role1);
						$all_email = array_merge($erp_email,$all_email);
						$all_email = array_merge($all_email,$party_emails);
						$all_email = array_unique($all_email);
						// $emails[] = "vipul.desai@yashnandeng.com";
						// $emails[] = $party_email;
						// $emails = array_merge($emails,$all_email);
							
						$email=array_values(array_diff($all_email,array("null","")));
							
						$to = implode(",",$email);
						
		
						$url = Router::url('/', true)."nghome/{$pdf_name}";
						 // $url = "http://192.168.1.29/svn/cakephp/cake_yashnanderp_2/nghome/{$pdf_name}";
						$fileatt = "test.pdf"; // Path to the file                  
						// $fileatt = $url; // Path to the file                  
						$fileatt_type = "application/pdf"; // File Type  
						$fileatt_name = "inward.pdf"; // Filename that will be used for the file as the attachment  
						$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
						$email_subject = "YashNand: Bill Payment Notification"; // The Subject of the email  
						$message = "Sir / Madam,<br />";
						$message .= "Your bills have been paid.Details for the same are below [After deduction of Advances, Credits, Debits, Retention etc.]";
						$message .= "<br /><br /><p><strong>Party's Name:</strong> {$party_name}</p>";
						$message .= "<p><strong>Type of Payment:</strong> Invoice</p>";
						$message .= "<p><strong>Invoice No:</strong>�Check Attachment</p>";
						$message .= "<p><strong>Invoice Dates:</strong>�Check Attachment</p>";
						
						$message .= "<br />";
						/* $message .= "<p><strong>Bill Inward No.: {$bills}</p>";*/
						$message .= "<p><strong>Bank Name:</strong> {$bank}</p>";
						$message .= "<p><strong>Cheque No:</strong> {$cheque_no}</p>";
						$message .= "<p><strong>Cheque Date:</strong> {$cheque_date}</p>";
						$message .= "<p><strong>Cheque Amount(Rs.):</strong> {$cheque_amount}</p>";
						$message .= "<p><strong>Transfer Type:</strong> {$transfer_type}</p>";
						$message .= "<br /><br />";
						$message .= "<p><strong>Please collect your cheque from Corporate Office during working hours.</strong></p>";
						$message .= "<br /><br />";			
						$message .= "Thank You.";
						
						$message .= "<br /><br />";			
						$message .= "-------------------------------------------------------------------------------------------------------------";
						$message .= "<br /><br />";
						
						$message .= "Please Do Not Reply to this E-mail ID. This E-mail is system generated and may have some problems. For conformation and/or queries,<br />please contact:";
						$message .= "<p><strong>Contact No: 079-23240202</strong></p>";
						$message .= "<p><strong>E-mail ID:</strong> <a href='mailto:mahesh.chaudhary@yashnandeng.com'>mahesh.chaudhary@yashnandeng.com</a></p>";
						
						$message .= "-------------------------------------------------------------------------------------------------------------";
						
						$email_to = $to; // Who the email is to
						$email_to = explode(",",$email_to);
						$email_to = array_filter($email_to, function($value) { return $value !== ''; });
						$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
						
						$headers = "From: ".$email_from;  
						$file = fopen($url,'rb');  


						$contents = file_get_contents($url); // read the remote file
						touch('temp.pdf'); // create a local EMPTY copy
						file_put_contents('temp.pdf', $contents);


						$data = fread($file,filesize("temp.pdf"));  
						// $data = fread($file,19189);  
						fclose($file);  
						$semi_rand = md5(time());  
						$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
							
						$headers .= "\nMIME-Version: 1.0\n" .  
									"Content-Type: multipart/mixed;\n" .  
									" boundary=\"{$mime_boundary}\"";
						$email_message_old = "";
						$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
										"--{$mime_boundary}\n" .  
										"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
									   "Content-Transfer-Encoding: 7bit\n\n" .  
						$email_message_old .= "\n\n";  
						// $data = chunk_split(base64_encode($data));   
						$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
						$dir_to_save = WWW_ROOT;
						// echo $dir_to_save;die;
						$pdf_name=$this->ERPfunction->generate_autoid('payment-').time().'.pdf';
						file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
						$email_message_old .= "--{$mime_boundary}\n" .  
										  "Content-Type: {$fileatt_type};\n" .  
										  " name=\"{$fileatt_name}\"\n" .  
										  //"Content-Disposition: attachment;\n" .  
										  //" filename=\"{$fileatt_name}\"\n" .  
										  "Content-Transfer-Encoding: base64\n\n" .  
										 $data .= "\n\n" .  
										  "--{$mime_boundary}--\n";
						$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->attachments(['Attachment' => $dir_to_save.$pdf_name])
						  ->send($message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
										  
						// $ok = @mail($to, $email_subject, $message, $headers);
					//}
					
				// else{
					// $this->Flash->success(__('Not Any Record', null), 
							// 'default', 
							// array('class' => 'success'));
				// $this->redirect(["controller"=>"Accounts","action"=>"inwardpayment"]);
				// }
				}
			}
			else
			{
				if($post['payment_type'] == 'advance')
				{
					$erp_inward_advance=TableRegistry::get('erp_inward_advance');
					$row = $erp_inward_advance->newEntity();
					$row['new_party_name'] = $post['party_id'];
					$row['party_email'] = $post['party_email'];
					$row['assign_project'] = implode(",",$post['assign_projects']);
					$row['payment_type'] = $post['payment_type'];
					$row['bank_name'] = $post['bank_name'];
					$row['cheque_no'] = $post['cheque_no'];
					$row['cheque_amount'] = $post['cheque_amount'];
					$row['transfer_type'] = $post['transfer_type'];
					$row['created_date'] = date('Y-m-d H:i:s');
					
					if($erp_inward_advance->save($row))
					{
							/* MAIL TO PARTY */
						
						if($post['transfer_type'] != 'office')
						{
							$transfer_type = $post['transfer_type'];
						}
						else
						{
							$transfer_type = 'Please Collect Cheque from Corporate Office';
						}
						$party_name =  $post['party_id'];
						$bank = $post['bank_name'];
						$cheque_no = $post['cheque_no'];
						// $cheque_date = date("d-m-Y");
						$cheque_amount = $post['cheque_amount'];
						
						$party_emails = array();
						$party_email = explode(",",$post['party_email']);
						if(!empty($party_email))
						{
							foreach($party_email as $mail)
							{
								$party_emails[] = $mail;
							}
						}
		
						$role =$this->ERPfunction->get_mail_list_by_payment('"payment_notification"','Alloted');
						//$cat_ids = $this->ERPfunction->get_cat_id_by_title($designation);
									
						$all_email = array();
						foreach($post['assign_projects'] as $project_id)
						{
							$temp = $this->ERPfunction->get_email_id_by_project_from_user($project_id,$role);
							$all_email = array_merge($temp,$all_email);
						}
						$role1 =$this->ERPfunction->get_mail_list_by_payment('"payment_notification"','defalut');
						$erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role1);
						$all_email = array_merge($erp_email,$all_email);
						$all_email = array_merge($all_email,$party_emails);
						$all_email = array_unique($all_email);
						// $emails[] = "vipul.desai@yashnandeng.com";
						// $emails[] = $party_email;
						// $emails = array_merge($emails,$all_email);
							
						$email=array_values(array_diff($all_email,array("null","")));
								
						$to = implode(",",$email);
								
						$url = Router::url('/', true)."nghome/{$pdf_name}";
						 // $url = "http://192.168.1.29/svn/cakephp/cake_yashnanderp_2/nghome/{$pdf_name}";
						$fileatt = "test.pdf"; // Path to the file                  
						// $fileatt = $url; // Path to the file                  
						$fileatt_type = "application/pdf"; // File Type  
						$fileatt_name = "inward.pdf"; // Filename that will be used for the file as the attachment  
						$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
						$email_subject = "YashNand: Bill Payment Notification"; // The Subject of the email  
						$message = "Sir / Madam,<br />";
						$message .= "Your bills have been paid.Details for the same are below [After deduction of Advances, Credits, Debits, Retention etc.]";
						$message .= "<br /><br /><p><strong>Party's Name:</strong> {$party_name}</p>";
						$message .= "<p><strong>Type of Payment:</strong> Advance</p>";
						// $message .= "<p><strong>Invoice Dates:</strong> {$invoice_dates}</p>";
						// $message .= "<p><strong>Total Amount:</strong> {$total_amount}</p>";
						
						$message .= "<br />";
						/* $message .= "<p><strong>Bill Inward No.: {$bills}</p>";*/
						$message .= "<p><strong>Bank:</strong> {$bank}</p>";
						$message .= "<p><strong>Cheque No:</strong> {$cheque_no}</p>";
						$message .= "<p><strong>Cheque Date:</strong> {$cheque_date}</p>";
						$message .= "<p><strong>Cheque Amount(Rs.):</strong> {$cheque_amount}</p>";
						$message .= "<p><strong>Transfer Type:</strong> {$transfer_type}</p>";
						$message .= "<br /><br />";
						$message .= "<p><strong>Please collect your cheque from Corporate Office during working hours.</strong></p>";
						$message .= "<br /><br />";			
						$message .= "Thank You.";
						
						$message .= "<br /><br />";			
						$message .= "-------------------------------------------------------------------------------------------------------------";
						$message .= "<br /><br />";
						
						$message .= "Please Do Not Reply to this E-mail ID. This E-mail is system generated and may have some problems. For conformation and/or queries,<br />please contact:";
						$message .= "<p><strong>Contact No: 079-23240202</strong></p>";
						$message .= "<p><strong>E-mail ID:</strong> <a href='mailto:mahesh.chaudhary@yashnandeng.com'>mahesh.chaudhary@yashnandeng.com</a></p>";
						
						$message .= "-------------------------------------------------------------------------------------------------------------";
						
						$email_to = $to; // Who the email is to
						$email_to = explode(",",$email_to);
						$email_to = array_filter($email_to, function($value) { return $value !== ''; });
						$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
						
						$headers = "From: ".$email_from;  
						$file = fopen($url,'rb');  


						$contents = file_get_contents($url); // read the remote file
						touch('temp.pdf'); // create a local EMPTY copy
						file_put_contents('temp.pdf', $contents);


						$data = fread($file,filesize("temp.pdf"));  
						// $data = fread($file,19189);  
						fclose($file);  
						$semi_rand = md5(time());  
						$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
							
						$headers .= "\nMIME-Version: 1.0\n" .  
									"Content-Type: multipart/mixed;\n" .  
									" boundary=\"{$mime_boundary}\"";
						$email_message_old = "";
						$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
										"--{$mime_boundary}\n" .  
										"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
									   "Content-Transfer-Encoding: 7bit\n\n" .  
						$email_message_old .= "\n\n";  
						// $data = chunk_split(base64_encode($data));   
						$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
						$dir_to_save = WWW_ROOT;
						// echo $dir_to_save;die;
						$pdf_name=$this->ERPfunction->generate_autoid('payment-').time().'.pdf';
						file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
						$email_message_old .= "--{$mime_boundary}\n" .  
										  "Content-Type: {$fileatt_type};\n" .  
										  " name=\"{$fileatt_name}\"\n" .  
										  //"Content-Disposition: attachment;\n" .  
										  //" filename=\"{$fileatt_name}\"\n" .  
										  "Content-Transfer-Encoding: base64\n\n" .  
										 $data .= "\n\n" .  
										  "--{$mime_boundary}--\n";
						 $email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->attachments(['Attachment' => $dir_to_save.$pdf_name])
						  ->send($message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
										  
						// $ok = @mail($to, $email_subject, $message, $headers);
						// /* MAIL TO PARTY */
					}
				}
				else
				{
					// if(!empty($post['inward']))
					// {
					//invoice_type
					if($post['transfer_type'] != 'office')
					{
						$transfer_type = $post['transfer_type'];
					}
					else
					{
						$transfer_type = 'Please Collect Cheque from Corporate Office';
					}
					$cheque_amount = $post['cheque_amount'];
					$cheque_no = $post['cheque_no'];
					$bank = $post['bank_name'];
					// $cheque_date = date("d-m-Y");
					
					// $inward = $post['inward'];
					// $project_ids = array();
					// foreach($inward['inward_bill_id'] as $key => $data)
					// {
						// $inward_id = $inward['inward_bill_id'][$key];
						// $row = $erp_inward_bill->find()->where(['inward_bill_id' => $inward_id])->hydrate(false)->toArray();
						// $project_ids[] = $row[0]['project_id'];
						
						// $user_create=$this->request->session()->read('user_id');
						// date_default_timezone_set('asia/kolkata');
						// $date=date('Y-m-d H:i:s');
						// $query = $erp_inward_bill->query();
						// $query->update()
						// ->set(['status_inward'=>'completed',
						// "paid_amount"=>$cheque_amount,
						// "payment_date"=>$date,
						// "cheque_no"=>$cheque_no,
						// "bank"=>$bank,
						// "is_notification"=>1,
						// 'accept_by'=>$user_create,'accept_date'=>$date])
						// ->where(['inward_bill_id' => $inward_id])
						// ->execute();
					// }
					// $project_ids = array_unique($project_ids);
					// if($query)
					// {
						$party_name =  $post['party_id'];
						
						
						// $invoice_no = array();
						// $invoice_date = array();
		
						// foreach($inward['inward_bill_id'] as $key => $data)
						// {
							// $invoice_no[] = $inward['invoice_no'][$key];
							// $invoice_date[] = date("d-m-Y",strtotime($inward['bill_date'][$key]));
						// }
						// $invoices = implode(",",$invoice_no);
						// $invoices_date = implode(",",$invoice_date);
						$party_emails = array();
						$party_email = explode(",",$post['party_email']);
						if(!empty($party_email))
						{
							foreach($party_email as $mail)
							{
								$party_emails[] = $mail;
							}
						}
						
						$role =$this->ERPfunction->get_mail_list_by_payment('"payment_notification"','Alloted');
						//$cat_ids = $this->ERPfunction->get_cat_id_by_title($designation);
									
						$all_email = array();
						foreach($post['assign_projects'] as $project_id)
						{
							$temp = $this->ERPfunction->get_email_id_by_project_from_user($project_id,$role);
							$all_email = array_merge($temp,$all_email);
						}
						$role2 =$this->ERPfunction->get_mail_list_by_payment('"payment_notification"','dafalut');
						$erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role2);
						$all_email = array_merge($erp_email,$all_email);
						$all_email = array_merge($all_email,$party_emails);
						$all_email = array_unique($all_email);
						// $emails[] = "vipul.desai@yashnandeng.com";
						// $emails[] = $party_email;
						// $emails = array_merge($emails,$all_email);
							
						$email=array_values(array_diff($all_email,array("null","")));
								
						$to = implode(",",$email);
						
						$url = Router::url('/', true)."nghome/{$pdf_name}";
						 // $url = "http://192.168.1.29/svn/cakephp/cake_yashnanderp_2/nghome/{$pdf_name}";
						$fileatt = "test.pdf"; // Path to the file                  
						// $fileatt = $url; // Path to the file                  
						$fileatt_type = "application/pdf"; // File Type  
						$fileatt_name = "inward.pdf"; // Filename that will be used for the file as the attachment  
						$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
						$email_subject = "YashNand: Bill Payment Notification"; // The Subject of the email  
						$message = "Sir / Madam,<br />";
						$message .= "Your bills have been paid.Details for the same are below [After deduction of Advances, Credits, Debits, Retention etc.]";
						$message .= "<br /><br /><p><strong>Party's Name:</strong> {$party_name}</p>";
						$message .= "<p><strong>Type of Payment:</strong> Invoice</p>";
						$message .= "<p><strong>Invoice No:</strong>�Check Attachment</p>";
						$message .= "<p><strong>Invoice Dates:</strong>�Check Attachment</p>";
						
						$message .= "<br />";
						/* $message .= "<p><strong>Bill Inward No.: {$bills}</p>";*/
						$message .= "<p><strong>Bank Name:</strong> {$bank}</p>";
						$message .= "<p><strong>Cheque No:</strong> {$cheque_no}</p>";
						$message .= "<p><strong>Cheque Date:</strong> {$cheque_date}</p>";
						$message .= "<p><strong>Cheque Amount(Rs.):</strong> {$cheque_amount}</p>";
						$message .= "<p><strong>Transfer Type:</strong> {$transfer_type}</p>";
						$message .= "<br /><br />";
						$message .= "<p><strong>Please collect your cheque from Corporate Office during working hours.</strong></p>";
						$message .= "<br /><br />";			
						$message .= "Thank You.";
						
						$message .= "<br /><br />";			
						$message .= "-------------------------------------------------------------------------------------------------------------";
						$message .= "<br /><br />";
						
						$message .= "Please Do Not Reply to this E-mail ID. This E-mail is system generated and may have some problems. For conformation and/or queries,<br />please contact:";
						$message .= "<p><strong>Contact No: 079-23240202</strong></p>";
						$message .= "<p><strong>E-mail ID:</strong> <a href='mailto:mahesh.chaudhary@yashnandeng.com'>mahesh.chaudhary@yashnandeng.com</a></p>";
						
						$message .= "-------------------------------------------------------------------------------------------------------------";
						
						$email_to = $to; // Who the email is to
						$email_to = explode(",",$email_to);
						$email_to = array_filter($email_to, function($value) { return $value !== ''; });
						$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
						
						$headers = "From: ".$email_from;  
						$file = fopen($url,'rb');  


						$contents = file_get_contents($url); // read the remote file
						touch('temp.pdf'); // create a local EMPTY copy
						file_put_contents('temp.pdf', $contents);


						$data = fread($file,filesize("temp.pdf"));  
						// $data = fread($file,19189);  
						fclose($file);  
						$semi_rand = md5(time());  
						$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
							
						$headers .= "\nMIME-Version: 1.0\n" .  
									"Content-Type: multipart/mixed;\n" .  
									" boundary=\"{$mime_boundary}\"";
						$email_message_old = "";									
						$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
										"--{$mime_boundary}\n" .  
										"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
									   "Content-Transfer-Encoding: 7bit\n\n" .  
						$email_message_old .= "\n\n";  
						// $data = chunk_split(base64_encode($data));   
						$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
						$dir_to_save = WWW_ROOT;
						// echo $dir_to_save;die;
						$pdf_name=$this->ERPfunction->generate_autoid('payment-').time().'.pdf';
						file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
						$email_message_old .= "--{$mime_boundary}\n" .  
										  "Content-Type: {$fileatt_type};\n" .  
										  " name=\"{$fileatt_name}\"\n" .  
										  //"Content-Disposition: attachment;\n" .  
										  //" filename=\"{$fileatt_name}\"\n" .  
										  "Content-Transfer-Encoding: base64\n\n" .  
										 $data .= "\n\n" .  
										  "--{$mime_boundary}--\n";
						$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->attachments(['Attachment' => $dir_to_save.$pdf_name])
						  ->send($message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
										  
						// $ok = @mail($to, $email_subject, $message, $headers);
					//}
					
					//}
				// else{
					// $this->Flash->success(__('Not Any Record', null), 
							// 'default', 
							// array('class' => 'success'));
				// $this->redirect(["controller"=>"Accounts","action"=>"inwardpayment"]);
				// }
					
				}
			}
		}
	}
	
	public function adddebitnote()
	{
		$erp_debit_note = TableRegistry::get('erp_debit_note');
		
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		// $agency_tbl = TableRegistry::get("erp_agency");
		// $agency_list = $agency_tbl->find("All")->toArray();
		// $this->set('agency_list',$agency_list);
		
		$user = $this->request->session()->read('user_id');
		
		if($this->request->is('post'))
		{
			$data = $this->request->data;
			
			$entity_data = $erp_debit_note->newEntity();
			if(isset($data["debit_doc"]) && $data["debit_doc"]['tmp_name'] != '')
			{
				$file = $this->ERPfunction->upload_debit_file($data["debit_doc"]);	
				if(!empty($file))
				{
					$entity_data['attachment'] =  $file;
				}					
			}
			$entity_data['project_id']=$data['project_id'];
			$entity_data['debit_note_no']=$data['debit_no'];
			$entity_data['date'] = date("Y-m-d",strtotime($data['debit_date']));
			$entity_data['debit_to'] = $data['party_id'];
			$entity_data['receiver_name'] = $data['receiver_name'];
			$entity_data['created_by'] = $user;
			$entity_data['created_date'] = date('Y-m-d');
			
			if($erp_debit_note->save($entity_data))
			{
				 $debit_id = $entity_data->debit_id;
				 $this->ERPfunction->add_debit_detail($data['debit'],$debit_id,$data['total_amount'],$data['total_words']);
				 $this->Flash->success(__('Record Insert Successfully.'));
				 return $this->redirect(['action' => 'adddebitnote']);
			 }
		}
	}
	
	public function debitnotealert()
	{
		$erp_debit_note = TableRegistry::get("erp_debit_note");
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$this->set('agency_list',$agency_list);
		
		$user = $this->request->session()->read('user_id');
		$projects_ids = $this->Usermanage->users_project($user);
		
		$role = $this->role;
		$this->set('role',$role);
		
		if($this->Usermanage->project_alloted($role)==1) { 
			if(!empty($projects_ids)) {
				$result = $erp_debit_note->find()->where(['erp_debit_note.project_id in'=>$projects_ids])->hydrate(false)->toArray();
			}else {
				$result=array();
			}
		}else {
			$result = $erp_debit_note->find()->hydrate(false)->toArray();
		}
		
		$this->set('debit_list',$result);
		
		if($this->request->is("post"))
		{
			$erp_debit_note_detail = TableRegistry::get("erp_debit_note_detail");
			$post = $this->request->data;
			$or = array();				
			
			$or["erp_debit_note.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			
			$or["erp_debit_note.debit_to"] = (!empty($post["party_id"]) && $post["party_id"] != "All")?$post["party_id"]:NULL;
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			 // debug($post);
			 // debug($or);die;

			if($this->Usermanage->project_alloted($role)==1) { 
				if(!empty($projects_ids)) {
					$result = $erp_debit_note->find()->where(['erp_debit_note.project_id in'=>$projects_ids,$or])->select($erp_debit_note)->hydrate(false)->toArray();	
				}else {
					$result=array();
				}
			}else {
				if(!empty($or)){
				$result = $erp_debit_note->find()->where($or)->select($erp_debit_note)->hydrate(false)->toArray();
				}else {
					$result = $erp_debit_note->find()->select($erp_debit_note)->hydrate(false)->toArray();
				}
			}
			$this->set('debit_list',$result);
				
		}
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$filename = "debit_alert.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{			
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize(base64_decode($this->request->data["rows"]));
			$this->set("rows",$rows);
			$this->render("debit_alertpdf");
		}
		
	}
	
	public function editdebit($debit_id)
	{		
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		// $agency_tbl = TableRegistry::get("erp_agency");
		// $agency_list = $agency_tbl->find("All")->toArray();
		// $this->set('agency_list',$agency_list);
		
		$erp_debit_note = TableRegistry::get("erp_debit_note");
		$erp_debit_note_detail = TableRegistry::get("erp_debit_note_detail");
		
		$debit_list = $erp_debit_note->get($debit_id)->toArray();
		$this->set('debit_list',$debit_list);
		
		
		$detail_data = $erp_debit_note_detail->find("all")->where(["debit_id"=>$debit_id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);	
		
		$user = $this->request->session()->read('user_id');
		
		if($this->request->is('post'))
		{
			$data = $this->request->data;
			
			$entity_data = $erp_debit_note->get($debit_id);
			if(isset($data["debit_doc"]) && $data["debit_doc"]['tmp_name'] != '')
			{
				$file = $this->ERPfunction->upload_debit_file($data["debit_doc"]);	
				if(!empty($file))
				{
					$entity_data['attachment'] =  $file;
				}					
			}
			$entity_data['project_id']=$data['project_id'];
			$entity_data['debit_note_no']=$data['debit_no'];
			$entity_data['date'] = date("Y-m-d",strtotime($data['debit_date']));
			$entity_data['debit_to'] = $data['party_id'];
			$entity_data['receiver_name'] = $data['receiver_name'];
			
			if($erp_debit_note->save($entity_data))
			{
				 $this->ERPfunction->edit_debit_detail($data['debit'],$debit_id,$data['total_amount'],$data['total_words']);
				 $this->Flash->success(__('Record Update Successfully.'));
				 echo "<script>window.close();</script>";
			 }
		}
	}
	
	public function deletedebit($debit_id)
	{
		$erp_debit_note_detail = TableRegistry::get('erp_debit_note_detail');
		$delete_ok = $erp_debit_note_detail->deleteAll(["debit_id"=>$debit_id]);
		if($delete_ok)
		{				
			$tbl = TableRegistry::get("erp_debit_note");
			$row = $tbl->get($debit_id);
			if($tbl->delete($row))
			{
				$this->Flash->success(__('Record delete Successfully.'));
				return $this->redirect(['action'=>'debitnotealert']);
			}
		}
	}
	
	public function viewdebit($debit_id)
	{
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$this->set('agency_list',$agency_list);
		
		$erp_debit_note = TableRegistry::get("erp_debit_note");
		$erp_debit_note_detail = TableRegistry::get("erp_debit_note_detail");
		
		$debit_list = $erp_debit_note->get($debit_id)->toArray();
		$this->set('debit_list',$debit_list);
		
		
		$detail_data = $erp_debit_note_detail->find("all")->where(["debit_id"=>$debit_id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);	
		
		$user = $this->request->session()->read('user_id');
	}
	
	public function printdebit($debit_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$erp_debit_note = TableRegistry::get("erp_debit_note");
		$erp_debit_note_detail = TableRegistry::get("erp_debit_note_detail");
		$detail_list = $erp_debit_note_detail->find()->where(array('debit_id'=>$debit_id));
		$this->set('detail_list',$detail_list);
		$data = $erp_debit_note->get($debit_id);
		$this->set("debit_list",$data->toArray());			
	}
	
	public function debitnoterecord($projects_id=null,$from=null,$to=null)
	{
		$erp_debit_note = TableRegistry::get("erp_debit_note");
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		// $agency_tbl = TableRegistry::get("erp_agency");
		// $agency_list = $agency_tbl->find("All")->toArray();
		// $this->set('agency_list',$agency_list);
		
		$user = $this->request->session()->read('user_id');
		$projects_ids = $this->Usermanage->users_project($user);
		
		$role = $this->role;
		$this->set('role',$role);
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["date >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["date <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["erp_debit_note.project_id"] = ($projects_id!=null)?$projects_id:NULL;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
					
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_ids))
				{
					$result = $erp_debit_note->find()->where($or1)->hydrate(false)->toArray();
				}
				else
				{
					$result=array();
				}
			}
			else
			{
				$result = $erp_debit_note->find()->where($or1)->hydrate(false)->toArray();
			}
		}
		else{
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_ids))
				{
					$result = $erp_debit_note->find()->where(['erp_debit_note.project_id in'=>$projects_ids])->hydrate(false)->toArray();
				}
				else
				{
					$result=array();
				}
			}
			else
			{
				$result = $erp_debit_note->find()->hydrate(false)->toArray();
			}
		}
		$this->set('debit_list',$result);
		
		if($this->request->is("post"))
		{
			$erp_debit_note_detail = TableRegistry::get("erp_debit_note_detail");
			$post = $this->request->data;
			$or = array();				
			
			$or["erp_debit_note.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			
			$or["erp_debit_note.debit_to"] = (!empty($post["party_id"]) && $post["party_id"] != "All")?$post["party_id"]:NULL;
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			 // debug($post);
			 // debug($or);die;
			
		
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_ids))
				{
					$result = $erp_debit_note->find()->where(['erp_debit_note.project_id in'=>$projects_ids,$or])->select($erp_debit_note)->hydrate(false)->toArray();
				}
				else
				{
					$result=array();
				}
			}
			else
			{
				$result = $erp_debit_note->find()->where($or)->select($erp_debit_note)->hydrate(false)->toArray();
			}
			
			$this->set('debit_list',$result);
				
		}
	}
	
	public function filemanager()
	{
		$baseurl = Router::url( $this->here, true );
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		$location = "";
		$this->set('role',$this->role);
		// debug($baseurl);die;
		$this->set('location',$location);
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

	public function updateremarks() {
		$this->autoRender = false;
		$uid = $this->request->data['uid'];
		$remarks = $this->request->data['remark'];

		$erpInwardBill = TableRegistry::get("erp_inward_bill");
		$update = $erpInwardBill->get($uid);
		$update->accept_bill_remarks = $remarks;
		$erpInwardBill->save($update);
		$this->Flash->success(__('Remarks Added Successfully', null),
            'default',
            array('class' => 'success'));
        $this->redirect(["controller" => "Accounts", "action" => "acceptbills"]);
	}

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}
