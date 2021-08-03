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

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry; 
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ContractController extends AppController
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
		//$this->user_id=$this->request->session()->read('user_id');
		$this->rights = $this->Usermanage->contract_access_right();
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$action = $this->request->action;
		if(isset($this->rights[$action][$this->role]) && $action != "printworecord" && $action != "printwo")
		{
			$is_capable = $this->rights[$action][$this->role];	
		}
		else
			$is_capable = 0;	
		
		$this->set('is_capable',$is_capable);
	}
	
    public function index()
    {
		
    }
	
	public function billingmenu()
    {
		
    }
	
	public function planningmenu()
    {
		
    }

	public function inwardlist()
	{		
		$contract_table_register = TableRegistry::get('erp_contract_inward'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		$role = $this->Usermanage->get_user_role($this->user_id);
		
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids)){
				$contract_list = $contract_table_register->find()->where(["project_id IN"=>$projects_ids]);	
			}else{
				$contract_list=array();
			}
		}else{
			 $contract_list = $contract_table_register->find();
		}
		
		
		/* $contract_list = $contract_table_register->find(); */
		$this->set('inward_info',$contract_list);
		
		$projects = $this->Usermanage->access_project($this->user_id);
		/* $projects = $this->ERPfunction->get_projects($this->user_id); */
		$this->set('projects',$projects);
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$inward_tbl = TableRegistry::get("erp_contract_inward");
				$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
				
				$or = array();				
				// $or["project_id"] = ($post["project_id"]!="all")?"{$post["project_id"]}":NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0]!="all")?$post["project_id"]:NULL;
				$or["project_code LIKE"] = (!empty($post["project_code"]))?"%{$post["project_code"]}%":NULL;
				$or["out_inward_no LIKE"] = (!empty($post["out_inward_no"]))?"%{$post["out_inward_no"]}%":NULL;
				$or["agency_name LIKE"] = (!empty($post["agency_name"]))?"%{$post["agency_name"]}%":NULL;
				$or["reference_no LIKE"] = (!empty($post["refno"]))?"%{$post["refno"]}%":NULL;	
				
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($or);die;
				$search_data = $inward_tbl->find("all")->where($or);
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set("inward_info",$search_data);
			}
		}
	}
	
	public function viewinwardlist($projects_id=null,$from=null,$to=null)
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$contract_table_register = TableRegistry::get('erp_contract_inward'); 
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		
		if($projects_id!=null){
			$or1 = array();		
			$or1["inward_date >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["inward_date <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["project_id"] = ($projects_id!=null)?$projects_id:NULL;
			$or1["sub_project_id"] = ($projects_id!=null)?$projects_id:NULL;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
					
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_id)){
					$contract_list = $contract_table_register->find()->where([$or1]);	
				}else{
					$contract_list=array();
				}
			}else{
				 $contract_list = $contract_table_register->find()->where([$or1]);	
			}
		}
		else{
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_ids)){
					$contract_list = $contract_table_register->find()->where(["OR"=>["project_id IN"=>$projects_ids,"sub_project_id IN"=>$projects_ids]]);	
				}else{
					$contract_list=array();
				}
			}
			else{
				 $contract_list = $contract_table_register->find();
			}
				
		}
		
			
		
		
		/* $contract_list = $contract_table_register->find(); */
		$this->set('inward_info',$contract_list);
		
		if($role == "deputymanagerelectric")
		{
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				//$inward_tbl = TableRegistry::get("erp_contract_inward");
				//$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
							
				$or = array();				
				$inward_tbl = TableRegistry::get("erp_contract_inward");
				$or["inward_date >="] = ($post["inward_from_date"] != "")?date("Y-m-d",strtotime($post["inward_from_date"])):NULL;
				$or["inward_date <="] = ($post["inward_to_date"] != "")?date("Y-m-d",strtotime($post["inward_to_date"])):NULL;
				$or["date >="] = ($post["ref_from_date"] != "")?date("Y-m-d",strtotime($post["ref_from_date"])):NULL;
				$or["date <="] = ($post["ref_to_date"] != "")?date("Y-m-d",strtotime($post["ref_to_date"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["agency_client_name IN"] = (!empty($post["agency_client_name"]) && $post["agency_client_name"][0] != "All" )?$post["agency_client_name"]:NULL;
				$or["project_code"] = (!empty($post["project_code"]))?$post["project_code"]:NULL;
				$or["out_inward_no"] = (!empty($post["out_inward_no"]))?$post["out_inward_no"]:NULL;
				$or["reference_no"] = (!empty($post["refno"]))?$post["refno"]:NULL;
				$or["agency_name"] = (!empty($post["agency_name"]))?$post["agency_name"]:NULL;
				$or["subject"] = (!empty($post["Subject"]))?$post["Subject"]:NULL;	
				
				// if($role == 'projectdirector' || $role == 'contractadmin' || $role == 'projectcoordinator')
				// {
					// $or["project_id IN"] = $projects_ids;
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
				// debug($or);die;
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids)){
						$contract_list = $contract_table_register->find()->where([$or])->hydrate(false)->toArray();
					}else{
						$contract_list=array();
					}
				}
				else
				{
					$contract_list = $contract_table_register->find()->where([$or])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set('inward_info',$contract_list);
			}
			
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "inward_list.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("inwardlistpdf");
			}	
		}
	}
	
	public function printinward($id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_contract_inward = TableRegistry::get('erp_contract_inward'); 
		$print_data = $erp_contract_inward->get($id);
		$this->set('print_data',$print_data);
		//var_dump($print_data);die;
		// $this->set('variation',$data_pricevariation_update);
		// $this->set('form_header','Edit Price Variation');
		// $this->set('button_text','Edit Price Variation');	
	}
	
	public function outwardlist(){
    	$contract_table_register = TableRegistry::get('erp_contract_outward'); 
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($this->Usermanage->project_alloted($role)==1){  
			if(!empty($projects_ids)){
				$contract_list = $contract_table_register->find()->where(["project_id IN"=>$projects_ids]);	
			}else{
				$contract_list=array();
			}
		}else{
			 $contract_list = $contract_table_register->find();
		}	
		
		/* $contract_list = $contract_table_register->find(); */
		$this->set('outward_info',$contract_list);
		
		/* $projects = $this->ERPfunction->get_projects(); */
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$outward_tbl = TableRegistry::get("erp_contract_outward");
				$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
				
				$or = array();				
				/* $or["project_id"] = ($post["project_id"]!="all")?"{$post["project_id"]}":NULL; */
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0]!="all")?$post["project_id"]:NULL;
				$or["project_code LIKE"] = (!empty($post["project_code"]))?"%{$post["project_code"]}%":NULL;
				$or["our_outward_no LIKE"] = (!empty($post["our_outward_no"]))?"%{$post["our_outward_no"]}%":NULL;
				$or["agency_name LIKE"] = (!empty($post["agency_name"]))?"%{$post["agency_name"]}%":NULL;
				$or["reference_no LIKE"] = (!empty($post["refno"]))?"%{$post["refno"]}%":NULL;	
				
				
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($or);die;
				$search_data = $outward_tbl->find("all")->where($or);
				$this->set("outward_info",$search_data);
			}
		}
		
    }
	
    public function viewoutwardlist($projects_id=null,$from=null,$to=null){
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
    	$contract_table_register = TableRegistry::get('erp_contract_outward'); 
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["outward_date >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["outward_date <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["project_id"] = ($projects_id!=null)?$projects_id:NULL;
			$or1["sub_project_id"] = ($projects_id!=null)?$projects_id:NULL;
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
			
				 $contract_list = $contract_table_register->find()->where([$or1]);
				
		}
		else{
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_ids)){
					$contract_list = $contract_table_register->find()->where(["project_id IN"=>$projects_ids]);	
				}else{
					$contract_list=array();
				}
			}else{
				 $contract_list = $contract_table_register->find();
			}	
		}
		
		
		
		/* $contract_list = $contract_table_register->find(); */
		$this->set('outward_info',$contract_list);
		
		if($this->role == "deputymanagerelectric")
		{
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$or = array();				
				$erp_contract_outward = TableRegistry::get("erp_contract_outward");
				$or["outward_date >="] = ($post["outward_from_date"] != "")?date("Y-m-d",strtotime($post["outward_from_date"])):NULL;
				$or["outward_date <="] = ($post["outward_to_date"] != "")?date("Y-m-d",strtotime($post["outward_to_date"])):NULL;
				$or["date >="] = ($post["ref_from_date"] != "")?date("Y-m-d",strtotime($post["ref_from_date"])):NULL;
				$or["date <="] = ($post["ref_to_date"] != "")?date("Y-m-d",strtotime($post["ref_to_date"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["agency_client_name IN"] = (!empty($post["agency_client_name"]) && $post["agency_client_name"][0] != "All" )?$post["agency_client_name"]:NULL;
				$or["project_code"] = (!empty($post["project_code"]))?$post["project_code"]:NULL;
				$or["our_outward_no"] = (!empty($post["out_outward_no"]))?$post["out_outward_no"]:NULL;
				$or["reference_no"] = (!empty($post["refno"]))?$post["refno"]:NULL;
				$or["agency_name"] = (!empty($post["agency_name"]))?$post["agency_name"]:NULL;
				$or["subject"] = (!empty($post["Subject"]))?$post["Subject"]:NULL;	
				
				// if($role == 'projectdirector' || $role == 'contractadmin' || $role == 'projectcoordinator')
				// {
					// $or["project_id IN"] = $projects_ids;
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
				// debug($or);die;
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids)){
						$contract_list = $erp_contract_outward->find()->where([$or])->hydrate(false)->toArray();
					}else{
						$contract_list=array();
					}
				}
				else
				{
					$contract_list = $erp_contract_outward->find()->where([$or])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set("outward_info",$contract_list);
			}
		
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "outward_list.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("outwardlistpdf");
			}	
		
		
		}		
			
    }
	
	public function printoutward($id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_contract_outward = TableRegistry::get('erp_contract_outward'); 
		$print_data = $erp_contract_outward->get($id);
		$this->set('print_data',$print_data);
		//var_dump($print_data);die;
		// $this->set('variation',$data_pricevariation_update);
		// $this->set('form_header','Edit Price Variation');
		// $this->set('button_text','Edit Price Variation');	
	}

    public function editrabill(){
    	
		/* $projects = $this->ERPfunction->get_projects(); */
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$rabill_table_ragister = TableRegistry::get('erp_contract_rabill'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($role == 'projectdirector' || $role == 'contractadmin' || $role == 'billingengineer' || $role == 'projectcoordinator'){ 
			if(!empty($projects_ids)){
				$rabill_list = $rabill_table_ragister->find()->where(["project_id IN"=>$projects_ids]);	
			}else{
				$rabill_list=array();
			}
		}else{
			 $rabill_list = $rabill_table_ragister->find();
		}	
			
		$this->set('bill_info',$rabill_list);
		
			
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$rabill_tbl = TableRegistry::get("erp_contract_rabill");
				$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
				
				$or = array();				
				/* $or["project_id"] = ($post["project_id"]!="all")?"{$post["project_id"]}":NULL; */
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0]!="all")?$post["project_id"]:NULL;
				$or["project_code LIKE"] = (!empty($post["project_code"]))?"%{$post["project_code"]}%":NULL;
				$or["ra_bill_no LIKE"] = (!empty($post["rabillno"]))?"%{$post["rabillno"]}%":NULL;	
				
				if($role == 'projectdirector' || $role == 'contractadmin' || $role == 'billingengineer' || $role == 'projectcoordinator')
				{
					$or["project_id IN"] = $projects_ids;
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$search_data = $rabill_tbl->find("all")->where($or);
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set("bill_info",$search_data);
			}
		}
		
    }

	public function viewrabill($projects_id=null,$from=null,$to=null){
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$rabill_table_ragister = TableRegistry::get('erp_contract_rabill'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["qty_taken_uptodate >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["qty_taken_uptodate <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["project_id"] = ($projects_id!=null)?$projects_id:NULL;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
			
			
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_id)){
					$rabill_list = $rabill_table_ragister->find()->where([$or1]);	
				}else{
					$rabill_list=array();
				}
			}else{
				 $rabill_list = $rabill_table_ragister->find()->where([$or1]);
			}
			
		}
		else{
    	
			
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_ids)){
					$rabill_list = $rabill_table_ragister->find()->where(['project_id in'=>$projects_ids]);	
				}else{
					$rabill_list=array();
				}
			}else{
				 $rabill_list = $rabill_table_ragister->find();
			}	
		}
		$this->set('bill_info',$rabill_list);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set('projects',$projects);
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				
				$or = array();				
				$erp_contract_rabill = TableRegistry::get("erp_contract_rabill");
				$or["qty_taken_uptodate >="] = ($post["as_from_date"] != "")?date("Y-m-d",strtotime($post["as_from_date"])):NULL;
				$or["qty_taken_uptodate <="] = ($post["as_to_date"] != "")?date("Y-m-d",strtotime($post["as_to_date"])):NULL;
				$or["date_of_payment >="] = ($post["pay_from_date"] != "")?date("Y-m-d",strtotime($post["pay_from_date"])):NULL;
				$or["date_of_payment <="] = ($post["pay_to_date"] != "")?date("Y-m-d",strtotime($post["pay_to_date"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				//$or["agency_client_name IN"] = (!empty($post["agency_client_name"]) && $post["agency_client_name"][0] != "All" )?$post["agency_client_name"]:NULL;
				$or["project_code"] = (!empty($post["project_code"]))?$post["project_code"]:NULL;
				$or["ra_bill_no"] = (!empty($post["bill_no"]))?$post["bill_no"]:NULL;
				
				if($or["project_id IN"] == NULL)
				{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}
				}
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($or);die;
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids)){
						$contract_list = $erp_contract_rabill->find()->where([$or])->hydrate(false)->toArray();
					}else{
						$contract_list=array();
					}
				}
				else
				{
					$contract_list = $erp_contract_rabill->find()->where([$or])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set('bill_info',$contract_list);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "ra_bills.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("rabillspdf");
			}	
		}
		
    }
	
		public function printrabill($id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_contract_rabill = TableRegistry::get('erp_contract_rabill'); 
		$print_data = $erp_contract_rabill->get($id);
		$this->set('print_data',$print_data);
		//var_dump($print_data);die;
		// $this->set('variation',$data_pricevariation_update);
		// $this->set('form_header','Edit Price Variation');
		// $this->set('button_text','Edit Price Variation');	
	}
	
    public function editpricevariation(){
    	$pricevariation_register = TableRegistry::get('erp_contract_pricevariation'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($role == 'projectdirector' || $role == 'contractadmin' || $role == 'billingengineer' || $role == 'projectcoordinator'){ 
			if(!empty($projects_ids)){
				$price_variation = $pricevariation_register->find()->where(["project_id IN"=>$projects_ids]);	
			}else{
				$price_variation=array();
			}
		}else{
			 $price_variation = $pricevariation_register->find();
		}	
		
		$this->set('price_variation_info',$price_variation);
		
		/*$projects = $this->ERPfunction->get_projects();*/
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$pricev_tbl = TableRegistry::get("erp_contract_pricevariation");
				$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
				
				$or = array();				
				/* $or["project_id"] = ($post["project_id"]!="all")?"{$post["project_id"]}":NULL; */
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0]!="all")?$post["project_id"]:NULL;
				$or["project_code LIKE"] = (!empty($post["project_code"]))?"%{$post["project_code"]}%":NULL;
				$or["bill_no LIKE"] = (!empty($post["priceno"]))?"%{$post["priceno"]}%":NULL;	
				
				if($role == 'projectdirector' || $role == 'contractadmin' || $role == 'projectcoordinator')
				{
					$or["project_id IN"] = $projects_ids;
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($or);die;
				$search_data = $pricev_tbl->find("all")->where($or);
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set("price_variation_info",$search_data);
			}
		}	
    } 
	
	public function viewpricevariation($projects_id=null,$from=null,$to=null){
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
    	$pricevariation_register = TableRegistry::get('erp_contract_pricevariation'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["upto_date >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["upto_date <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["project_id"] = ($projects_id!=null)?$projects_id:NULL;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
					
			if($this->Usermanage->project_alloted($role)==1){  
				if(!empty($projects_id)){
					$price_variation = $pricevariation_register->find()->where([$or1]);	
				}else{
					$price_variation=array();
				}
			}else{
				 $price_variation = $pricevariation_register->find()->where([$or1]);	
			}	
		}
		else{
			
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_ids)){
					$price_variation = $pricevariation_register->find()->where(['project_id in'=>$projects_ids]);	
				}else{
					$price_variation=array();
				}
			}else{
				 $price_variation = $pricevariation_register->find();	
			}	
		}
		
		
		$this->set('price_variation_info',$price_variation);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set('projects',$projects);
		$search_data = array();
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				
				$or = array();				
				$erp_contract_pricevariation = TableRegistry::get("erp_contract_pricevariation");
				$or["upto_date >="] = ($post["as_from_date"] != "")?date("Y-m-d",strtotime($post["as_from_date"])):NULL;
				$or["upto_date <="] = ($post["as_to_date"] != "")?date("Y-m-d",strtotime($post["as_to_date"])):NULL;
				$or["payment_date >="] = ($post["pay_from_date"] != "")?date("Y-m-d",strtotime($post["pay_from_date"])):NULL;
				$or["payment_date <="] = ($post["pay_to_date"] != "")?date("Y-m-d",strtotime($post["pay_to_date"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				//$or["agency_client_name IN"] = (!empty($post["agency_client_name"]) && $post["agency_client_name"][0] != "All" )?$post["agency_client_name"]:NULL;
				$or["project_code"] = (!empty($post["project_code"]))?$post["project_code"]:NULL;
				$or["bill_no"] = (!empty($post["bill_no"]))?$post["bill_no"]:NULL;
				
				if($or["project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						$or["project_id IN"] = $projects_ids;
					}
				}
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($or);die;
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids)){
						$contract_list = $erp_contract_pricevariation->find()->where([$or])->hydrate(false)->toArray();
					}else{
						$contract_list=array();
					}
				}
				else
				{
					$contract_list = $erp_contract_pricevariation->find()->where([$or])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set('price_variation_info',$contract_list);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "price_variation.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("pricevariationpdf");
			}
		}
    }
	
	public function addinward($id=Null)
    {
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$table_category=TableRegistry::get('erp_category_master');
		$department_list=$table_category->find()->where(array('type'=>'department'));
		$this->set('department_list',$department_list);

		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$inward_agency = $erp_agency_name->find()->where(["type"=>"inward_agency"]);
		$this->set('result',$inward_agency);

		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$inward_written_by = $erp_agency_name->find()->where(["type"=>"inward_writtenby"]);
		$this->set('writtenby',$inward_written_by);

		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$inward_designation = $erp_agency_name->find()->where(["type"=>"inward_designation"]);
		$this->set('inward_designation',$inward_designation);

		$table_contract_inward = TableRegistry::get('erp_contract_inward'); 
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_list',$agency_list);
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		if($this->role == "deputymanagerelectric")
		{
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);

		if(isset($id))
		{
			
			$user_action = 'edit';
			
			$data_inward_update = $table_contract_inward->get($id);
			
			$this->set('update_inward',$data_inward_update);
			$this->set('back',"viewinwardlist");

			
			$this->set('form_header','Edit Inward Correspondence');
			$this->set('button_text','Update Inward Correspondence');
		}else {
			$user_action = 'insert';
			$this->set('user_identy_id',$user_identy_id);
			$this->set('form_header','Add Inward Correspondence');
			$this->set('button_text','Add Inward Correspondence');
			$this->set('back',"index");
		}
		
		
		$this->set('user_action',$user_action);
		
		
		if($this->request->is('post')) {
			if(isset($_FILES['attach_file'])){
			
			$file =$_FILES['image_url']["name"];
			$size = count($file);
			for($i=0;$i<$size;$i++) {
				$parts = pathinfo($_FILES['image_url']['name'][$i]);
			}
			$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
			if($ext != 0) {
				$this->set('user_data',$this->request->data);
				$this->request->data['date']=date('Y-m-d',strtotime($this->request->data['date']));
				$this->request->data['inward_date']=date('Y-m-d',strtotime($this->request->data['inward_date']));
				
				
				$this->request->data['status']=1;
				/* $image=$this->ERPfunction->upload_image('image_url',$this->request->data['old_image']); */
				/* $this->request->data['attachment']=$image; */
				
				if($user_action == 'edit')
				{
					
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
					$post_data['inward_from'] = ($this->request->data['project_id'] == 2)?$this->request->data['dep_pro']:'';
					$post_data['sub_project_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'project')?$this->request->data['sub_project']:'';
					$post_data['department_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'department')?$this->request->data['department']:'';
					
					$post_data['last_edit']=date('Y-m-d H:i:s');
					$post_data['last_edit_by']=$this->request->session()->read('user_id');
					
					$save_data_update = $table_contract_inward->patchEntity($data_inward_update,$post_data);
					if($table_contract_inward->save($save_data_update))
					{
						$this->Flash->success(__('Record Update Successfully', null), 
								'default', 
								array('class' => 'success'));
						$this->redirect(array("controller" => "Contract","action" => "viewinwardlist"));
						// echo "<script>window.close();</script>";
					}
					
				}
				else
				{	
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
					
					$this->request->data['inward_from'] = ($this->request->data['project_id'] == 2)?$this->request->data['dep_pro']:'';
					$this->request->data['sub_project_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'project')?$this->request->data['sub_project']:'';
					$this->request->data['department_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'department')?$this->request->data['department']:'';
									
					$this->request->data['created_date']=date('Y-m-d H:i:s');
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					
					$inward_entity = $table_contract_inward->newEntity();
					$create_patch_inward=$table_contract_inward->patchEntity($inward_entity,$this->request->data);
					if($table_contract_inward->save($create_patch_inward))
					{
						$this->Flash->success(__('Contract Inward Insert Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => "Contract","action" => "index"));
					}
				}
				
				//$this->redirect(array("controller" => "Contract","action" => "inwardlist"));		
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				// return $this->redirect(["action"=>"add-member"]);
			}
		}
		else{
			$this->set('user_data',$this->request->data);
				$this->request->data['date']=date('Y-m-d',strtotime($this->request->data['date']));
				$this->request->data['inward_date']=date('Y-m-d',strtotime($this->request->data['inward_date']));
				
				
				$this->request->data['status']=1;
				/* $image=$this->ERPfunction->upload_image('image_url',$this->request->data['old_image']); */
				/* $this->request->data['attachment']=$image; */
				
				if($user_action == 'edit')
				{
					
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
					
					$post_data['inward_from'] = ($this->request->data['project_id'] == 2)?$this->request->data['dep_pro']:'';
					$post_data['sub_project_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'project')?$this->request->data['sub_project']:'';
					$post_data['department_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'department')?$this->request->data['department']:'';
					
					$post_data['last_edit']=date('Y-m-d H:i:s');
					$post_data['last_edit_by']=$this->request->session()->read('user_id');
					
					$save_data_update = $table_contract_inward->patchEntity($data_inward_update,$post_data);
					if($table_contract_inward->save($save_data_update))
					{
						// $this->Flash->success(__('Record Update Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
						// $this->redirect(array("controller" => "Contract","action" => "inwardlist"));
						echo "<script>window.close();</script>";
					}
					
				}
				else
				{	
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
					
					$this->request->data['inward_from'] = ($this->request->data['project_id'] == 2)?$this->request->data['dep_pro']:'';
					$this->request->data['sub_project_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'project')?$this->request->data['sub_project']:'';
					$this->request->data['department_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'department')?$this->request->data['department']:'';
									
					$this->request->data['created_date']=date('Y-m-d H:i:s');
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					
					$inward_entity = $table_contract_inward->newEntity();
					$create_patch_inward=$table_contract_inward->patchEntity($inward_entity,$this->request->data);
					if($table_contract_inward->save($create_patch_inward))
					{
						$this->Flash->success(__('Contract Inward Insert Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => "Contract","action" => "index"));
					}
				}
				
		}
		}	
    }

	public function viewaddinward($id=Null)
    {
		$table_contract_inward = TableRegistry::get('erp_contract_inward'); 
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_list',$agency_list);
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		if(isset($id))
		{
			
			$user_action = 'edit';
			
			$data_inward_update = $table_contract_inward->get($id);
			
			$this->set('update_inward',$data_inward_update);

			
			$this->set('form_header','View Inward Correspondence');
			$this->set('button_text','Inward Correspondence');
			
		}
		
		$this->set('user_action',$user_action);
			
    }
	
	
	public function addoutward($id=Null)
    {
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$table_category=TableRegistry::get('erp_category_master');
		$department_list=$table_category->find()->where(array('type'=>'department'));
		$this->set('department_list',$department_list);
		
		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$outward_agency = $erp_agency_name->find()->where(["type"=>"outward_agency"]);
		$this->set('outward_agency',$outward_agency);

		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$outward_written_by = $erp_agency_name->find()->where(["type"=>"outward_writtenby"]);
		$this->set('outward_written_by',$outward_written_by);

		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$outward_designation = $erp_agency_name->find()->where(["type"=>"outward_designation"]);
		$this->set('outward_designation',$outward_designation);

		$table_contract_outward = TableRegistry::get('erp_contract_outward'); 
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		if($this->role == "deputymanagerelectric")
		{
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_list',$agency_list);
		
		if(isset($id))
		{
			
			$user_action = 'edit';
			
			$data_outward_update = $table_contract_outward->get($id);


			$this->set('back','viewoutwardlist');
			$this->set('update_outward',$data_outward_update);
			$this->set('form_header','Edit Outward Correspondence');
			$this->set('button_text','Update Outward Correspondence');
			
		}
		else
		{
			$user_action = 'insert';
			$this->set('user_identy_id',$user_identy_id);
			$this->set('form_header','Add Outward Correspondence');
			$this->set('button_text','Add Outward Correspondence');
			$this->set('back','index');
		}
		
		
		$this->set('user_action',$user_action);
		
		
		if($this->request->is('post')) {
			if(isset($_FILES['attach_file'])){
			
			$file =$_FILES['image_url']["name"];
			$size = count($file);
			for($i=0;$i<$size;$i++) {
				$parts = pathinfo($_FILES['image_url']['name'][$i]);
			}
			$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
			// debug($ext);die;
			if($ext != 0) {
				$this->set('user_data',$this->request->data);
				
				$this->request->data['date']=date('Y-m-d',strtotime($this->request->data['date']));
				$this->request->data['outward_date']=date('Y-m-d',strtotime($this->request->data['outward_date']));
				
				
				$this->request->data['status']=1;
				/* $image=$this->ERPfunction->upload_image('image_url',$this->request->data['old_image']);
				$this->request->data['attachment']=$image; */
				
				if($user_action == 'edit')
				{
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
					
					$post_data['outward_from'] = ($this->request->data['project_id'] == 2)?$this->request->data['dep_pro']:'';
					$post_data['sub_project_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'project')?$this->request->data['sub_project']:'';
					$post_data['department_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'department')?$this->request->data['department']:'';
					
					$post_data['last_edit']=date('Y-m-d H:i:s');
					$post_data['last_edit_by']=$this->request->session()->read('user_id');
					
					
					$save_data_update = $table_contract_outward->patchEntity($data_outward_update,$post_data);
					if($table_contract_outward->save($save_data_update))
					{
						// $this->Flash->success(__('Record Update Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
						// $this->redirect(array("controller" => "Contract","action" => "viewoutwardlist"));
						echo "<script>window.close();</script>";
					}
					
				}
				else
				{	
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
					
					$this->request->data['outward_from'] = ($this->request->data['project_id'] == 2)?$this->request->data['dep_pro']:'';
					$this->request->data['sub_project_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'project')?$this->request->data['sub_project']:'';
					$this->request->data['department_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'department')?$this->request->data['department']:'';
					
					$this->request->data['created_date']=date('Y-m-d H:i:s');
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					
					$outward_entity = $table_contract_outward->newEntity();
					$create_patch_outward=$table_contract_outward->patchEntity($outward_entity,$this->request->data);
					if($table_contract_outward->save($create_patch_outward))
					{
						$this->Flash->success(__('Contract Outward Insert Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => "Contract","action" => "index"));
					}
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				// return $this->redirect(["action"=>"add-member"]);
			}
		}
		else{
			
			$this->set('user_data',$this->request->data);
				
				$this->request->data['date']=date('Y-m-d',strtotime($this->request->data['date']));
				$this->request->data['outward_date']=date('Y-m-d',strtotime($this->request->data['outward_date']));
				
				
				$this->request->data['status']=1;
				/* $image=$this->ERPfunction->upload_image('image_url',$this->request->data['old_image']);
				$this->request->data['attachment']=$image; */
				
				if($user_action == 'edit')
				{
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
					
					$post_data['outward_from'] = ($this->request->data['project_id'] == 2)?$this->request->data['dep_pro']:'';
					$post_data['sub_project_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'project')?$this->request->data['sub_project']:'';
					$post_data['department_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'department')?$this->request->data['department']:'';
					
					$post_data['last_edit']=date('Y-m-d H:i:s');
					$post_data['last_edit_by']=$this->request->session()->read('user_id');
					
					
					$save_data_update = $table_contract_outward->patchEntity($data_outward_update,$post_data);
					if($table_contract_outward->save($save_data_update))
					{
						// $this->Flash->success(__('Record Update Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
						// $this->redirect(array("controller" => "Contract","action" => "viewoutwardlist"));
						echo "<script>window.close();</script>";
					}
					
				}
				else
				{	
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
					
					$this->request->data['outward_from'] = ($this->request->data['project_id'] == 2)?$this->request->data['dep_pro']:'';
					$this->request->data['sub_project_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'project')?$this->request->data['sub_project']:'';
					$this->request->data['department_id'] = ($this->request->data['project_id'] == 2 && $this->request->data['dep_pro'] == 'department')?$this->request->data['department']:'';
					
					$this->request->data['created_date']=date('Y-m-d H:i:s');
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					
					$outward_entity = $table_contract_outward->newEntity();
					$create_patch_outward=$table_contract_outward->patchEntity($outward_entity,$this->request->data);
					if($table_contract_outward->save($create_patch_outward))
					{
						$this->Flash->success(__('Contract Outward Insert Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => "Contract","action" => "index"));
					}
				}
			
		}
		}
    }

	public function viewaddoutward($id=Null)
    {
		$table_contract_outward = TableRegistry::get('erp_contract_outward'); 
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_list',$agency_list);
		
		if(isset($id))
		{
			
			$user_action = 'edit';
			
			$data_outward_update = $table_contract_outward->get($id);
		
			$this->set('update_outward',$data_outward_update);
			$this->set('form_header','View Outward Correspondence');
			$this->set('button_text','Update Outward Correspondence');			
		}	
		
		$this->set('user_action',$user_action);		
    }
	
	
    public function addpricevariation($id=NULL)
	{
    	$table_contract_pricevariation = TableRegistry::get('erp_contract_pricevariation'); 
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);

		if(isset($id))
		{
			
			$user_action = 'edit';
			
			$data_pricevariation_update = $table_contract_pricevariation->get($id);


			$this->set('update_variation',$data_pricevariation_update);
			$this->set('form_header','Edit Price Variation');
			$this->set('button_text','Edit Price Variation');
			$this->set('back','viewpricevariation');
		}
		else
		{
			$user_action = 'insert';
			$this->set('user_identy_id',$user_identy_id);
			$this->set('form_header','Add Price Variation');
			$this->set('button_text','Add price Variation');
			$this->set('back','billingmenu');
		}
		
		
		$this->set('user_action',$user_action);
		
		
		if($this->request->is('post')) {
			if(isset($_FILES['attach_file'])){
			$file =$_FILES['attach_file']["name"];
			$size = count($file);
			for($i=0;$i<$size;$i++) {
				$parts = pathinfo($_FILES['attach_file']['name'][$i]);
			}
			$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
			// debug($ext);die;
			if($ext != 0) {
				$this->set('user_data',$this->request->data);
					
				$this->request->data['upto_date']=date('Y-m-d',strtotime($this->request->data['upto_date']));
				$this->request->data['payment_date']=date('Y-m-d',strtotime($this->request->data['payment_date']));

				$this->request->data['status']=1;
				/* $image_doc=$this->ERPfunction->upload_image('attachment_doc',$this->request->data['old_doc']);
				$this->request->data['attachment_doc']=$image_doc;

				$image_ex=$this->ERPfunction->upload_image('attachment_excel',$this->request->data['old_ex']);
				$this->request->data['attachment_excel']=$image_ex; */
				
				if($user_action == 'edit')
				{
		
					$post_data = $this->request->data;
					
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
					$post_data['attach_file'] = json_encode($old_files);
					
					$post_data['last_edit']=date('Y-m-d H:i:s');				
					$post_data['last_edit_by']=$this->request->session()->read('user_id');
					
					$save_data_update =$table_contract_pricevariation->patchEntity($data_pricevariation_update,$post_data);
					if($table_contract_pricevariation->save($save_data_update))
					{
						// $this->Flash->success(__('Record Update Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
						// $this->redirect(array("controller" => "Contract","action" => "viewpricevariation"));
						echo "<script>window.close();</script>";
					}
					
				}
				else
				{	
				
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
					$this->request->data['attach_file'] = json_encode($all_files);
					
					$this->request->data['created_date']=date('Y-m-d H:i:s');				
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					
					$pricevariation_entity = $table_contract_pricevariation->newEntity();
					$create_patch_pricevariation=$table_contract_pricevariation->patchEntity($pricevariation_entity,$this->request->data);
					if($table_contract_pricevariation->save($create_patch_pricevariation))
					{
						$this->Flash->success(__('Contract Price Variation Insert Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => "Contract","action" => "index"));
					}
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				// return $this->redirect(["action"=>"add-member"]);
			}		
			}
			else{
				$this->set('user_data',$this->request->data);
					
				$this->request->data['upto_date']=date('Y-m-d',strtotime($this->request->data['upto_date']));
				$this->request->data['payment_date']=date('Y-m-d',strtotime($this->request->data['payment_date']));

				$this->request->data['status']=1;
				/* $image_doc=$this->ERPfunction->upload_image('attachment_doc',$this->request->data['old_doc']);
				$this->request->data['attachment_doc']=$image_doc;

				$image_ex=$this->ERPfunction->upload_image('attachment_excel',$this->request->data['old_ex']);
				$this->request->data['attachment_excel']=$image_ex; */
				
				if($user_action == 'edit')
				{
		
					$post_data = $this->request->data;
					
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
					$post_data['attach_file'] = json_encode($old_files);
					
					$post_data['last_edit']=date('Y-m-d H:i:s');				
					$post_data['last_edit_by']=$this->request->session()->read('user_id');
					
					$save_data_update =$table_contract_pricevariation->patchEntity($data_pricevariation_update,$post_data);
					if($table_contract_pricevariation->save($save_data_update))
					{
						// $this->Flash->success(__('Record Update Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
						// $this->redirect(array("controller" => "Contract","action" => "viewpricevariation"));
						echo "<script>window.close();</script>";
					}
					
				}
				else
				{	
				
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
					$this->request->data['attach_file'] = json_encode($all_files);
					
					$this->request->data['created_date']=date('Y-m-d H:i:s');				
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					
					$pricevariation_entity = $table_contract_pricevariation->newEntity();
					$create_patch_pricevariation=$table_contract_pricevariation->patchEntity($pricevariation_entity,$this->request->data);
					if($table_contract_pricevariation->save($create_patch_pricevariation))
					{
						$this->Flash->success(__('Contract Price Variation Insert Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => "Contract","action" => "index"));
					}
				}
			}
			//$this->redirect(array("controller" => "Contract","action" => "editpricevariation"));		
		}
    }

	public function viewaddpricevariation($id=NULL)
	{
    	$table_contract_pricevariation = TableRegistry::get('erp_contract_pricevariation'); 
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);

		if(isset($id))
		{
			
			$user_action = 'edit';
			
			$data_pricevariation_update = $table_contract_pricevariation->get($id);


			$this->set('update_variation',$data_pricevariation_update);
			$this->set('form_header','View Price Variation');
			$this->set('button_text','Edit Price Variation');
			
		}
			
		$this->set('user_action',$user_action);		
	}

	
	
    public function addrabill($id=NULL){
    	$table_contract_rabill = TableRegistry::get('erp_contract_rabill'); 
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);

		if(isset($id))
		{
			
			$user_action = 'edit';			
			$data_rabill_update = $table_contract_rabill->get($id);
			

			$this->set('update_rabill',$data_rabill_update);
			$this->set('form_header','Edit R.A Bill');
			$this->set('button_text','Edit R.A Bill');
			$this->set('back','viewrabill');
			
		}
		else
		{
			$user_action = 'insert';
			$this->set('user_identy_id',$user_identy_id);
			$this->set('form_header','Add R.A Bill');
			$this->set('button_text','Add R.A Bill');
			$this->set('back','billingmenu');
		}
		
		
		$this->set('user_action',$user_action);
		
		
		if($this->request->is('post')) {
			if(isset($_FILES['attach_file'])){
			$file =$_FILES['attach_file']["name"];
			// debug($file);die;
			$size = count($file);
			for($i=0;$i<$size;$i++) {
				$parts = pathinfo($_FILES['attach_file']['name'][$i]);
			}
			$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
			// debug($ext);die;
			if($ext != 0) {
				$this->set('user_data',$this->request->data);
		
				
				$this->request->data['qty_taken_uptodate']=date('Y-m-d',strtotime($this->request->data['qty_taken_uptodate']));
				$this->request->data['date_of_payment']=date('Y-m-d',strtotime($this->request->data['date_of_payment']));
							
				$this->request->data['status']=1;
				/* $image_doc=$this->ERPfunction->upload_image('attachment_doc',$this->request->data['old_doc']);
				$this->request->data['attachment_doc']=$image_doc;

				$image_ex=$this->ERPfunction->upload_image('attachment_excel',$this->request->data['old_ex']);
				$this->request->data['attachment_excel']=$image_ex; */
							
				if($user_action == 'edit')
				{			

					$post_data = $this->request->data;
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
					$post_data['attach_file'] = json_encode($old_files);
					
					$post_data['last_edit']=date('Y-m-d H:i:s');
					$post_data['last_edit_by']=$this->request->session()->read('user_id');
					
					$save_data_update = $table_contract_rabill->patchEntity($data_rabill_update,$post_data);
					if($table_contract_rabill->save($save_data_update))
					{
						// $this->Flash->success(__('Record Update Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
						// $this->redirect(array("controller" => "Contract","action" => "viewrabill"));	
						echo "<script>window.close();</script>";
					}
					
				}
				else
				{	
				
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
					$this->request->data['attach_file'] = json_encode($all_files);	
						
					$this->request->data['created_date']=date('Y-m-d H:i:s');
					$this->request->data['created_by']=$this->request->session()->read('user_id');
				
					$rabill_entity = $table_contract_rabill->newEntity();
					$create_patch_rabill=$table_contract_rabill->patchEntity($rabill_entity,$this->request->data);
					if($table_contract_rabill->save($create_patch_rabill))
					{
						$this->Flash->success(__('Contract R.A Bill Record Insert Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => "Contract","action" => "index"));	
					}
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				// return $this->redirect(["action"=>"add-member"]);
			}
				
			}
		
		else{
				$this->set('user_data',$this->request->data);
		
				
				$this->request->data['qty_taken_uptodate']=date('Y-m-d',strtotime($this->request->data['qty_taken_uptodate']));
				$this->request->data['date_of_payment']=date('Y-m-d',strtotime($this->request->data['date_of_payment']));
							
				$this->request->data['status']=1;
				/* $image_doc=$this->ERPfunction->upload_image('attachment_doc',$this->request->data['old_doc']);
				$this->request->data['attachment_doc']=$image_doc;

				$image_ex=$this->ERPfunction->upload_image('attachment_excel',$this->request->data['old_ex']);
				$this->request->data['attachment_excel']=$image_ex; */
							
				if($user_action == 'edit')
				{			

					$post_data = $this->request->data;
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
					$post_data['attach_file'] = json_encode($old_files);
					
					$post_data['last_edit']=date('Y-m-d H:i:s');
					$post_data['last_edit_by']=$this->request->session()->read('user_id');
					
					$save_data_update = $table_contract_rabill->patchEntity($data_rabill_update,$post_data);
					if($table_contract_rabill->save($save_data_update))
					{
						// $this->Flash->success(__('Record Update Successfully', null), 
								// 'default', 
								// array('class' => 'success'));
						// $this->redirect(array("controller" => "Contract","action" => "viewrabill"));	
						echo "<script>window.close();</script>";
					}
					
				}
				else
				{	
				
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
					$this->request->data['attach_file'] = json_encode($all_files);	
						
					$this->request->data['created_date']=date('Y-m-d H:i:s');
					$this->request->data['created_by']=$this->request->session()->read('user_id');
				
					$rabill_entity = $table_contract_rabill->newEntity();
					$create_patch_rabill=$table_contract_rabill->patchEntity($rabill_entity,$this->request->data);
					if($table_contract_rabill->save($create_patch_rabill))
					{
						$this->Flash->success(__('Contract R.A Bill Record Insert Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => "Contract","action" => "index"));	
					}
				}
		}
    }
	}

	 public function viewaddrabill($id=NULL)
	 {
    	$table_contract_rabill = TableRegistry::get('erp_contract_rabill'); 
		$user_identy_id = $this->ERPfunction->generate_autoid('CEO-');
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);

		if(isset($id))
		{			
			$user_action = 'edit';
			
			$data_rabill_update = $table_contract_rabill->get($id);			

			$this->set('update_rabill',$data_rabill_update);
			$this->set('form_header','View R.A Bill');
			$this->set('button_text','R.A Bill');
			
		}
		
		$this->set('user_action',$user_action);
		
    }


    public function deletepricevariation($id){
    	$price_variation_register = TableRegistry::get('erp_contract_pricevariation'); 
		$this->request->is(['post','delete']);
		
		$delete_price_variation =$price_variation_register->get($id);

		if($price_variation_register->delete($delete_price_variation))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 'default', array('class' => 'success'));	
		}
		return $this->redirect(array('controller'=>'contract','action'=>'viewpricevariation'));
    }



   public function deleteoutward($id){
		$contract_register = TableRegistry::get('erp_contract_outward'); 
		$this->request->is(['post','delete']);
		
		$delete_contract_outward =$contract_register->get($id);

		if($contract_register->delete($delete_contract_outward))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 'default', array('class' => 'success'));	
		}
		return $this->redirect(array('controller'=>'contract','action'=>'outwardlist'));
   }

   public function deleterabill($id){
   	$rabill_register = TableRegistry::get('erp_contract_rabill'); 
		$this->request->is(['post','delete']);
		
		$delete_contract_rabill =$rabill_register->get($id);

		if($rabill_register->delete($delete_contract_rabill))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 'default', array('class' => 'success'));	
		}
		return $this->redirect(array('controller'=>'contract','action'=>'viewrabill'));
   }

	public function delete($id)
	{
		$contract_register = TableRegistry::get('erp_contract_inward'); 
		$this->request->is(['post','delete']);
		
		$delete_contract_inward =$contract_register->get($id);

		if($contract_register->delete($delete_contract_inward))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 'default', array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'index']);
	}
	
	public function addagency()
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'contract') !== false) {
			$back_url = 'contract';
		}else{
			$back_url = 'purchase';
		}
		$this->set('back_url',$back_url);
		
		$this->set('form_header','Add Agency');
		$this->set('button_text','Save');
		$this->set('button_text2','Cancel');
		$this->set('back',"index");
		$this->set('edit',false);
		
		// $tbl = TableRegistry::get("erp_agency");
		// $conn = ConnectionManager::get('default');
		// $result = $conn->execute('select max(id) from  erp_agency');		
		// $count = 0;
		// foreach($result as $retrive_data)
		// { $count=$retrive_data[0]; }
		// $count = $count + 1;		
		// $count = sprintf("%07d", $count);
		// $auto_id = "YNEC/AG/{$count}";		
		// $this->set('agency_id',$auto_id);		
		if($this->request->is("post"))
		{
			$tbl = TableRegistry::get("erp_agency");
			$conn = ConnectionManager::get('default');
			$result = $conn->execute('select max(id) from  erp_agency');		
			$count = 0;
			foreach($result as $retrive_data)
			{ $count=$retrive_data[0]; }
			$count = $count + 1;		
			$count = sprintf("%07d", $count);
			$auto_id = "YNEC/AG/{$count}";
		
			$agency_tbl = TableRegistry::get("erp_agency");
			$this->request->data['agency_id'] = $auto_id;		
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
				$this->request->data['attach_file'] = json_encode($all_files);	
				$this->request->data['created_date']=date('Y-m-d H:i:s');
				$this->request->data['created_by']=$this->request->session()->read('user_id');
				
			
			
			$agency_entry = $agency_tbl->newEntity();
			/* $this->request->data['attach_account_detail']= $this->ERPfunction->upload_image('attach_account_detail',''); */
			$agency_entry = $agency_tbl->patchEntity($agency_entry,$this->request->data);
			if($agency_tbl->save($agency_entry))
			{
				$this->Flash->success(__('Record Inserted Successfully With Agency Id '.$auto_id, null), 'default', array('class' => 'success'));
			}
			return $this->redirect(['action'=>'agencylist']);
		}
	}
	
	public function agencylist()
    {
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}elseif (strpos($previous_url, 'accounts') !== false) {
			$back_url = 'accounts';
			$back_page = 'index';
		}elseif (strpos($previous_url, 'purchase') !== false) {
			$back_url = 'purchase';
			$back_page = 'index';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$tbl = TableRegistry::get("erp_agency");
		$data = $tbl->find("all")->hydrate(false)->toArray();
		$this->set("agency_list",$data);
		
		$role = $this->role;
		$this->set('role',$role);
		
		$agency_dropdown = $tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_dropdown',$agency_dropdown);
		
		$search_data = array();
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$agency_id = (!empty($post["agency_id"]))?["agency_id"=>$post["agency_id"]]:["agency_id LIKE"=>"%%"];
				$or = array();				
				$or["agency_id"] = ($post["agency_id"]!="")?"{$post["agency_id"]}":NULL;
				/* $or["id"] = (!empty($post["agency"]))?"{$post["agency"]}":NULL; */
				$or["id IN"] = (!empty($post["agency"]) && $post["agency"][0] != "")?$post["agency"]:NULL;
				$or["email_id LIKE"] = (!empty($post["email"]))?"%{$post["email"]}%":NULL;	
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($or);die;
				$search_data = $tbl->find("all")->where($or);
				/* $search_data = $search_data->hydrate(false)->toArray(); */
				$this->set("agency_list",$search_data);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "agency_list.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("agencylistpdf");
			}
		}
		
		
    }
	
	public function editagency($eid)
	{
		$this->set('form_header','Edit Agency');
		$this->set('button_text','Update');
		$this->set('button_text2','Cancel');
		$this->set('edit',true);
		$this->set('back','agencylist');
		
		$tbl = TableRegistry::get("erp_agency");	
		$data = $tbl->get($eid)->toArray();		
		$this->set("user_data",$data);
		$this->set('agency_id',$data['agency_id']);
		
		$this->render("addagency");
		if($this->request->is("post"))
		{
			$old_files = array();
				if(isset($this->request->data["old_attach_file"]))
				{
					$old_files = $this->request->data["old_attach_file"];				
				}
				@$this->request->data['attach_label'] = trim(json_encode($this->request->data["attach_label"]),'\"');
				if(isset($_FILES["attach_file"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("attach_file");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$this->request->data['attach_file'] = json_encode($old_files);
			
				
			
			
			
			$agency_tbl = TableRegistry::get("erp_agency");
			$agency_entry = $tbl->get($eid);
			/* if(!empty($this->request->data["new_attach_account_detail"]["name"]))
			{
				$this->request->data['attach_account_detail']= $this->ERPfunction->upload_image('new_attach_account_detail','');
			} */
			
			$this->request->data['last_edit']=date('Y-m-d H:i:s');
			$this->request->data['last_edit_by']=$this->request->session()->read('user_id');
				
			$agency_entry = $agency_tbl->patchEntity($agency_entry,$this->request->data);
			if($agency_tbl->save($agency_entry))
			{
				//$this->Flash->success(__('Record Updated Successfully'));
				echo "<script>window.close();</script>";
			}
			//return $this->redirect(['action'=>'agencylist']);
		}
	}
	
	public function viewagency($eid)
	{
		$this->set('form_header','View Agency');
		$this->set('button_text','Update');
		$this->set('button_text2','Cancel');
		$this->set('edit',true);
		
		$tbl = TableRegistry::get("erp_agency");	
		$data = $tbl->get($eid)->toArray();
		// var_dump($data);die;
		$this->set("user_data",$data);
		$this->set('agency_id',"YNEC/AG/000{$data['agency_id']}");		
	}
	
	public function printagency($aid)
	{
		$this->set('form_header','View Agency');
		$this->set('button_text','Update');
		$this->set('button_text2','Cancel');
		$this->set('edit',true);
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$tbl = TableRegistry::get("erp_agency");	
		$data = $tbl->get($aid)->toArray();
		// var_dump($data);die;
		$this->set("user_data",$data);
		$this->set('agency_id',$data['agency_id']);	
	}
	
	public function printpricevariation($id)
	{
		$table_contract_pricevariation = TableRegistry::get('erp_contract_pricevariation'); 
		$data_pricevariation_update = $table_contract_pricevariation->get($id);
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$this->set('variation',$data_pricevariation_update);
		// $this->set('form_header','Edit Price Variation');
		// $this->set('button_text','Edit Price Variation');	
	}
	
	public function workheadlist()
	{
		$work_head_tbl = TableRegistry::get('erp_work_head');
		$work_head_data = $work_head_tbl->find()->hydrate(false)->toArray();
		$this->set('head_list',$work_head_data);
		$this->set('role',$this->role);
	}
	
	public function viewworkhead($work_head_id)
	{
		$work_head_tbl = TableRegistry::get('erp_work_head');
		$work_head_data = $work_head_tbl->get($work_head_id);
		$this->set('head_data',$work_head_data);
		$this->set('role',$this->role);
	}
	
	public function editworkhead($work_head_id)
	{
		$work_head_tbl = TableRegistry::get('erp_work_head');
		$work_head_data = $work_head_tbl->get($work_head_id);
		$this->set('head_data',$work_head_data);
		$this->set('role',$this->role);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			$save['work_head_code'] = $post['head_code'];
			$save['type_of_contract'] = $post['type_of_contract'];
			$save['work_head_title'] = $post['head_title'];
			
			$row = $work_head_tbl->get($work_head_id);
			$save_data = $work_head_tbl->patchEntity($row,$save);
			if($work_head_tbl->save($save_data))
			{
				$this->Flash->success(__('Record Update Successfully', null), 
				'default', 
				array('class' => 'success'));
				$this->redirect(array("controller" => "Contract","action" => "workheadlist"));
			}
			
		}
	}
	
	public function preparewo()
	{
		$wo_table = TableRegistry::get('erp_work_order');
		
		$erp_work_head = TableRegistry::get('erp_work_head'); 
		$head_list = $erp_work_head->find();
		$this->set('work_head_list',$head_list);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		// $agency_tbl = TableRegistry::get("erp_agency");
		// $agency_list = $agency_tbl->find("All")->toArray();
		// $this->set('agency_list',$agency_list);
		
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == "deputymanagerelectric"){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			$code = $this->ERPfunction->get_projectcode($post['project_id']);
			$new_wono = $this->ERPfunction->generate_auto_id($post['project_id'],"erp_work_order","wo_id","wo_no");
			$new_wono = sprintf("%09d", $new_wono);
			$wo_no = $code.'/WO/'.$new_wono;
		
			$save['project_id'] = $post['project_id'];
			$save['bill_mode'] = $post['bill_mode'];
			$save['wo_no'] = $wo_no;
			$save['wo_date'] = $this->ERPfunction->set_date($post['wo_date']);
			$save['party_userid'] = $post['party_id'];
			$save['party_id'] = $post['party_identy'];
			$save['party_address'] = $post['party_address'];
			$save['party_no1'] = $post['party_no1'];
			$save['party_no2'] = $post['party_no2'];
			$save['party_email'] = $post['party_email'];
			$save['party_pan_no'] = $post['party_pan_no'];
			$save['party_gst_no'] = $post['party_gst_no'];
			$save['contract_type'] = $post['type_of_contract'];
			$save['payment_method'] = $post['payment_method'];
			// $save['target_date'] = date("Y-m-d",strtotime($post['target_date']));
			$save['remarks'] = $post['remarks'];
			$save['mail_check'] = $post['mail_check'];
			$save['created_date'] = date('Y-m-d H:i:s');
			$save['created_by'] = $this->request->session()->read('user_id');
			
			if($save['contract_type'] == 1 || $save['contract_type'] == 3 || $save['contract_type'] == 4)
			{
				$save['taxes_duties'] = isset($post['taxes_duties1'])?$post['taxes_duties1']:0;
				$save['guarantee'] = isset($post['guarantee_check1'])?$post['guarantee_check1']:0;
				$save['guarantee_time'] = $post['guarantee1'];
				$save['gstno'] = $post['gstno1'];
				$save['payment_days'] = $post['payment_days1'];
			}
			else
			{
				$save['taxes_duties'] = isset($post['taxes_duties2'])?$post['taxes_duties2']:0;
				$save['loading_transport'] = isset($post['loading_transport2'])?$post['loading_transport2']:0;
				$save['unloading'] = isset($post['unloading2'])?$post['unloading2']:0;
				$save['guarantee'] = isset($post['guarantee_check2'])?$post['unloading2']:0;
				$save['guarantee_time'] = $post['guarantee2'];
				$save['warrenty'] = isset($post['warranty_check2'])?$post['unloading2']:0;
				$save['warrenty_time'] = $post['warranty'];
				$save['gstno'] = $post['gstno2'];
				$save['payment_days'] = $post['payment_days2'];
			}
			$all_files = array();
			if(isset($_FILES["attach_file"]["name"]))
			{
				$file = $this->ERPfunction->upload_file_wo("attach_file");	
				if(!empty($file))
				foreach($file as $attachment_file)
				{
					$all_files[] = $attachment_file;
				}					
			}
			$save['attachment'] = json_encode($all_files);
			
			$entity_row = $wo_table->newEntity();
			$save_data = $wo_table->patchEntity($entity_row,$save);
			if($wo_table->save($save_data))
			{
				$wo_id = $save_data->wo_id;
				$this->ERPfunction->add_work_order_detail($post['material'],$wo_id);
				
				$this->Flash->success(__('WO Created Successfully With WO NO '.$wo_no, null), 
				'default', 
				array('class' => 'success'));
				$this->redirect(array("controller" => "Purchase","action" => "index"));
			}
			
		}
	}
	
	public function approvewo()
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'Purchase';
			$back_page = 'index';
		}
		
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		
		if($this->role == "deputymanagerelectric")
		{
			$result = $wo_table->find()->where(["approved_status"=>0,"project_id IN"=>$projects_ids])->hydrate(false)->toArray();
		}else{
			$result = $wo_table->find()->where(["approved_status"=>0])->hydrate(false)->toArray();
		}
		
		$this->set('role',$this->role);
		$this->set('wo_date',$result);
	}
	
	public function deletewo($wo_id)
	{
		$this->autoRander = false;
		$wod_tbl = TableRegistry::get('erp_work_order_detail');
		
		$ok = $wod_tbl->deleteAll(["wo_id"=>$wo_id]);
		if($ok)
		{		
			$wo_tbl = TableRegistry::get('erp_work_order');
			$row = $wo_tbl->get($wo_id);
			$wo_tbl->delete($row);
			
			$this->Flash->success(__('Record delete Successfully.'));
			return $this->redirect(['action'=>'approvewo']);
		}
		return $this->redirect(['action'=>'approvewo']);
	}
	
	public function deletewodetailrecord($wo_detail_id)
	{
		$this->autoRander = false;
		$wod_tbl = TableRegistry::get('erp_work_order_detail');
	
		$record = $wod_tbl->get($wo_detail_id);
		$wo_id = $record->wo_id;
		if($wod_tbl->delete($record))
		{
			$count = $wod_tbl->find()->where(["wo_id"=>$wo_id])->count();
			if($count == 0)
			{				
				$wo_tbl = TableRegistry::get('erp_work_order');
				$row = $wo_tbl->get($wo_id);
				$wo_tbl->delete($row);
			}
			$this->Flash->success(__('Record delete Successfully.'));
			return $this->redirect(['action'=>'worecords']);
		}
	}
	
	public function cancelwo($wo_id)
	{		
		$wod_tbl = TableRegistry::get('erp_work_order_detail');
		$wo_tbl = TableRegistry::get('erp_work_order');
		
		$get_deleted_wo = $wo_tbl->get($wo_id);
		$deleted_wo = $get_deleted_wo->toArray();
		$del_wo_project_id = $deleted_wo["project_id"];
		$party_user_id = $deleted_wo["party_userid"];
		
		if(is_numeric($party_user_id))
		{
			$party_email = $this->ERPfunction->get_vendor_email($party_user_id);
		}else{
			$party_email = $this->ERPfunction->get_agency_email($party_user_id);
		}
		$party_emails = array();
		$party_email = explode(",",$party_email);
		if(!empty($party_email))
		{
			foreach($party_email as $mail)
			{
				$party_emails[] = $mail;
			}
		}
		$del_wo_no = $deleted_wo["wo_no"];
		$del_wo_project_name = $this->ERPfunction->get_projectname($deleted_wo["project_id"]);
		if(is_numeric($deleted_wo['party_userid']))
		{
			$del_wo_party_name = $this->ERPfunction->get_vendor_name($deleted_wo["party_userid"]);
		}else{
			$del_wo_party_name = $this->ERPfunction->get_agency_name_by_code($deleted_wo['party_userid']);
		}
		
		
		// $pdpmcm_email = $this->ERPfunction->get_email_of_pd_pm_cm_by_project($del_wo_project_id,$wo_id);	
		// $mm_email = $this->ERPfunction->get_email_of_mm_by_project($del_wo_project_id);
		
		$query = $wod_tbl->query();
		$cancel = $query->update()
						->set(['approved'=>0,
						"approved_date"=>null,
						'approved_by'=>0])
						->where(['wo_id' => $wo_id])
						->execute();
						
		if($cancel)
		{		
			$wo_tbl = TableRegistry::get('erp_work_order');
			$row = $wo_tbl->get($wo_id);
			$row['approved_status'] = 0;
			$row['approved_date'] = null;
			$row['approved_by'] = 0;
			
			$code = $this->ERPfunction->get_projectcode($row['project_id']);
			$new_wono = $this->ERPfunction->generate_auto_id($row['project_id'],"erp_work_order","wo_id","wo_no");
			$new_wono = sprintf("%09d", $new_wono);
			$wo_no = $code.'/WO/'.$new_wono;
			$row['wo_no'] = $wo_no;
			if($wo_tbl->save($row))
			{
				$mail_enable = $this->ERPfunction->get_wo_mail_status($wo_id);
				if($mail_enable == 1)
				{
					/* Project Related Role */
					$project_related_role = ["projectdirector","billingengineer","constructionmanager"];
					$email1 = $this->ERPfunction->get_email_id_by_project_from_user($del_wo_project_id,$project_related_role);
					/* Project Related Role */
					
					/* Common Role */
					$common_role = ["erphead","md","contractadmin","erpmanager",'erpoperator','ceo','purchasemanager','purchasehead'];
					$email2 = $this->ERPfunction->get_email_id_by_role_from_user($common_role);
					/* Common Role */
					
					/* Project Role + Common Role (Merge) */
					$emails = array_merge($email1,$email2);
					/* Project Role + Common Role (Merge) */
					
					/* Project Role + Common Role + Party (Merge) */
					$emails = array_merge($emails,$party_emails);
					/* Project Role + Common Role + Party (Merge) */
					
					/* Remove Duplicate Email Id */
					$emails = array_unique($emails);
					/* Remove Duplicate Email Id */
					
					/* Remove Null and Blank Email Id */
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					/* Remove Null and Blank Email Id */
					
					$emails[] = "bipin.patel@yashnandeng.com";
					
				}elseif($mail_enable == 2){
					/* Project Related Role */
					$project_related_role = ["projectdirector","billingengineer","constructionmanager","deputymanagerelectric"];
					$email1 = $this->ERPfunction->get_email_id_by_project_from_user($del_wo_project_id,$project_related_role);
					/* Project Related Role */
					
					/* Common Role */
					$common_role = ["erphead","md","contractadmin","erpmanager",'erpoperator','ceo','purchasemanager','purchasehead'];
					$email2 = $this->ERPfunction->get_email_id_by_role_from_user($common_role);
					/* Common Role */
					
					/* Project Role + Common Role (Merge) */
					$emails = array_merge($email1,$email2);
					/* Project Role + Common Role (Merge) */
					
					/* Project Role + Common Role + Party (Merge) */
					$emails = array_merge($emails,$party_emails);
					/* Project Role + Common Role + Party (Merge) */
					
					/* Remove Duplicate Email Id */
					$emails = array_unique($emails);
					/* Remove Duplicate Email Id */
					
					/* Remove Null and Blank Email Id */
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					/* Remove Null and Blank Email Id */
					
					$emails[] = "bipin.patel@yashnandeng.com";
				}else{
					/* Project Related Role */
					$project_related_role = ["projectdirector","billingengineer","constructionmanager"];
					$email1 = $this->ERPfunction->get_email_id_by_project_from_user($del_wo_project_id,$project_related_role);
					/* Project Related Role */
					
					/* Common Role */
					$common_role = ["erphead","md","contractadmin","erpmanager",'erpoperator','ceo','purchasemanager','purchasehead'];
					$email2 = $this->ERPfunction->get_email_id_by_role_from_user($common_role);
					/* Common Role */
					
					/* Project Role + Common Role (Merge) */
					$emails = array_merge($email1,$email2);
					/* Project Role + Common Role (Merge) */
										
					/* Remove Duplicate Email Id */
					$emails = array_unique($emails);
					/* Remove Duplicate Email Id */
					
					/* Remove Null and Blank Email Id */
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					/* Remove Null and Blank Email Id */
					
					$emails[] = "bipin.patel@yashnandeng.com";
				}
				
				if(!empty($emails))
				{
					$all_users = implode(",",$emails);		
					$this->ERPfunction->cancel_wo_mail($all_users,$del_wo_no,$del_wo_project_name,$del_wo_party_name);
				}
					
				$this->Flash->success(__('W.O. Cancel Successfully.'));
				return $this->redirect(['action'=>'worecords']);
			}
		}
	}

	public function cancelplanningwo($wo_id)
	{		
		$wod_tbl = TableRegistry::get('erp_planning_work_order_detail');
		$wo_tbl = TableRegistry::get('erp_planning_work_order');
		
		$get_deleted_wo = $wo_tbl->get($wo_id);
		$deleted_wo = $get_deleted_wo->toArray();
		$del_wo_project_id = $deleted_wo["project_id"];
		$party_user_id = $deleted_wo["party_userid"];
		
		if(is_numeric($party_user_id))
		{
			$party_email = $this->ERPfunction->get_vendor_email($party_user_id);
		}else{
			$party_email = $this->ERPfunction->get_agency_email($party_user_id);
		}
		$party_emails = array();
		$party_email = explode(",",$party_email);
		if(!empty($party_email))
		{
			foreach($party_email as $mail)
			{
				$party_emails[] = $mail;
			}
		}
		$del_wo_no = $deleted_wo["wo_no"];
		$del_wo_project_name = $this->ERPfunction->get_projectname($deleted_wo["project_id"]);
		if(is_numeric($deleted_wo['party_userid']))
		{
			$del_wo_party_name = $this->ERPfunction->get_vendor_name($deleted_wo["party_userid"]);
		}else{
			$del_wo_party_name = $this->ERPfunction->get_agency_name_by_code($deleted_wo['party_userid']);
		}
		
		
		// $pdpmcm_email = $this->ERPfunction->get_email_of_pd_pm_cm_by_project($del_wo_project_id,$wo_id);	
		// $mm_email = $this->ERPfunction->get_email_of_mm_by_project($del_wo_project_id);
		
		$query = $wod_tbl->query();
		$cancel = $query->update()
						->set(['approved'=>0,
						"approved_date"=>null,
						'approved_by'=>0])
						->where(['wo_id' => $wo_id])
						->execute();
						
		if($cancel)
		{		
			$wo_tbl = TableRegistry::get('erp_planning_work_order');
			$row = $wo_tbl->get($wo_id);
			$row['approved_status'] = 0;
			$row['approved_date'] = null;
			$row['approved_by'] = 0;
			
			$code = $this->ERPfunction->get_projectcode($row['project_id']);
			$new_wono = $this->ERPfunction->generate_auto_id($row['project_id'],"erp_work_order","wo_id","wo_no");
			$new_wono = sprintf("%09d", $new_wono);
			$wo_no = $code.'/WO/'.$new_wono;
			$row['wo_no'] = $wo_no;
			if($wo_tbl->save($row))
			{
				$mail_enable = $this->ERPfunction->get_planningwo_mail_status($wo_id);
				if($mail_enable == 1)
				{
					/* Project Related Role */
					$project_related_role = ["projectdirector","billingengineer","constructionmanager"];
					$email1 = $this->ERPfunction->get_email_id_by_project_from_user($del_wo_project_id,$project_related_role);
					/* Project Related Role */
					
					/* Common Role */
					$common_role = ["erphead","md","contractadmin","erpmanager",'erpoperator','ceo','purchasemanager','purchasehead'];
					$email2 = $this->ERPfunction->get_email_id_by_role_from_user($common_role);
					/* Common Role */
					
					/* Project Role + Common Role (Merge) */
					$emails = array_merge($email1,$email2);
					/* Project Role + Common Role (Merge) */
					
					/* Project Role + Common Role + Party (Merge) */
					$emails = array_merge($emails,$party_emails);
					/* Project Role + Common Role + Party (Merge) */
					
					/* Remove Duplicate Email Id */
					$emails = array_unique($emails);
					/* Remove Duplicate Email Id */
					
					/* Remove Null and Blank Email Id */
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					/* Remove Null and Blank Email Id */
					
					$emails[] = "bipin.patel@yashnandeng.com";
					
				}elseif($mail_enable == 2){
					/* Project Related Role */
					$project_related_role = ["projectdirector","billingengineer","constructionmanager","deputymanagerelectric"];
					$email1 = $this->ERPfunction->get_email_id_by_project_from_user($del_wo_project_id,$project_related_role);
					/* Project Related Role */
					
					/* Common Role */
					$common_role = ["erphead","md","contractadmin","erpmanager",'erpoperator','ceo','purchasemanager','purchasehead'];
					$email2 = $this->ERPfunction->get_email_id_by_role_from_user($common_role);
					/* Common Role */
					
					/* Project Role + Common Role (Merge) */
					$emails = array_merge($email1,$email2);
					/* Project Role + Common Role (Merge) */
					
					/* Project Role + Common Role + Party (Merge) */
					$emails = array_merge($emails,$party_emails);
					/* Project Role + Common Role + Party (Merge) */
					
					/* Remove Duplicate Email Id */
					$emails = array_unique($emails);
					/* Remove Duplicate Email Id */
					
					/* Remove Null and Blank Email Id */
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					/* Remove Null and Blank Email Id */
					
					$emails[] = "bipin.patel@yashnandeng.com";
				}else{
					/* Project Related Role */
					$project_related_role = ["projectdirector","billingengineer","constructionmanager"];
					$email1 = $this->ERPfunction->get_email_id_by_project_from_user($del_wo_project_id,$project_related_role);
					/* Project Related Role */
					
					/* Common Role */
					$common_role = ["erphead","md","contractadmin","erpmanager",'erpoperator','ceo','purchasemanager','purchasehead'];
					$email2 = $this->ERPfunction->get_email_id_by_role_from_user($common_role);
					/* Common Role */
					
					/* Project Role + Common Role (Merge) */
					$emails = array_merge($email1,$email2);
					/* Project Role + Common Role (Merge) */
										
					/* Remove Duplicate Email Id */
					$emails = array_unique($emails);
					/* Remove Duplicate Email Id */
					
					/* Remove Null and Blank Email Id */
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					/* Remove Null and Blank Email Id */
					
					$emails[] = "bipin.patel@yashnandeng.com";
				}
				
				if(!empty($emails))
				{
					$all_users = implode(",",$emails);		
					// $this->ERPfunction->cancel_wo_mail($all_users,$del_wo_no,$del_wo_project_name,$del_wo_party_name);
				}
					
				$this->Flash->success(__('W.O. Cancel Successfully.'));
				return $this->redirect(['action'=>'planningworecords']);
			}
		}
	}
	
	public function editpreparewo($wo_id)
	{
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>0]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
		
		$erp_work_head = TableRegistry::get('erp_work_head'); 
		$head_list = $erp_work_head->find();
		$this->set('work_head_list',$head_list);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$this->set('agency_list',$agency_list);
		
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == "deputymanagerelectric"){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			$save['project_id'] = $post['project_id'];
			$save['bill_mode'] = $post['bill_mode'];
			$save['wo_no'] = $post['wo_no'];
			$save['wo_date'] = $this->ERPfunction->set_date($post['wo_date']);
			$save['party_userid'] = $post['party_id'];
			$save['party_id'] = $post['party_identy'];
			$save['party_address'] = $post['party_address'];
			$save['party_no1'] = $post['party_no1'];
			$save['party_no2'] = $post['party_no2'];
			$save['party_email'] = $post['party_email'];
			$save['party_pan_no'] = $post['party_pan_no'];
			$save['party_gst_no'] = $post['party_gst_no'];
			$save['contract_type'] = $post['type_of_contract'];
			$save['payment_method'] = $post['payment_method'];
			// $save['target_date'] = date("Y-m-d",strtotime($post['target_date']));
			$save['remarks'] = $post['remarks'];
			$save['mail_check'] = $post['mail_check'];
			
			if($save['contract_type'] == 1 || $save['contract_type'] == 3 || $save['contract_type'] == 4)
			{
				$save['taxes_duties'] = isset($post['taxes_duties1'])?$post['taxes_duties1']:0;
				$save['loading_transport'] = 0;
				$save['unloading'] = 0;
				$save['guarantee'] = isset($post['guarantee_check1'])?$post['guarantee_check1']:0;
				$save['guarantee_time'] = isset($post['guarantee1'])?$post['guarantee1']:'';
				$save['warrenty'] = 0;
				$save['warrenty_time'] = '';
				$save['gstno'] = $post['gstno1'];
				$save['payment_days'] = $post['payment_days1'];
			}
			else
			{
				$save['taxes_duties'] = isset($post['taxes_duties2'])?$post['taxes_duties2']:0;
				$save['loading_transport'] = isset($post['loading_transport2'])?$post['loading_transport2']:0;
				$save['unloading'] = isset($post['unloading2'])?$post['unloading2']:0;
				$save['guarantee'] = isset($post['guarantee_check2'])?$post['guarantee_check2']:0;
				$save['guarantee_time'] = isset($post['guarantee2'])?$post['guarantee2']:'';
				$save['warrenty'] = isset($post['warranty_check2'])?$post['warranty_check2']:0;
				$save['warrenty_time'] = isset($post['warranty'])?$post['warranty']:'';
				$save['gstno'] = $post['gstno2'];
				$save['payment_days'] = $post['payment_days2'];
			}
			
			$old_files = array();
			if(isset($post["old_attach_file"]))
			{
				$old_files = $post["old_attach_file"];				
			}
			if(isset($_FILES["attach_file"]["name"]))
			{
				$file = $this->ERPfunction->upload_file_wo("attach_file");	
				if(!empty($file))
				foreach($file as $attachment_file)
				{
					$old_files[] = $attachment_file;
				}					
			}
			$save['attachment'] = json_encode($old_files);
			
			$edit_row = $wo_table->get($wo_id);
			$save_data = $wo_table->patchEntity($edit_row,$save);
			if($wo_table->save($save_data))
			{
				$wo_id = $save_data->wo_id;
				$this->ERPfunction->edit_work_order_detail($post['material'],$wo_id);
				
				$this->Flash->success(__('Data Update Successfully', null), 
				'default', 
				array('class' => 'success'));
				echo "<script>window.close();</script>";
				//$this->redirect(array("controller" => "Contract","action" => "approvewo"));
			}
			
		}
	}
	
	public function previewwo($wo_id)
	{
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id])->order(["contract_no"=>"asc"]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}
	
	public function printworecord($wo_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id])->order(["contract_no"=>"asc"]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}
	
	public function worecords($projects_id=null,$from=null,$to=null)
	{
		ini_set('memory_limit', '-1');
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}elseif (strpos($previous_url, 'contract') !== false) {
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}else{
			$back_url = 'Purchase';
			$back_page = 'index';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$role = $this->role;
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == "deputymanagerelectric"){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		$erp_work_head = TableRegistry::get('erp_work_head'); 
		$head_list = $erp_work_head->find();
		$this->set('work_head_list',$head_list);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$this->set('agency_list',$agency_list);
		
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		
		if($projects_id!=null){
			
			$or = array();				
					
					$or["erp_work_order_detail.approved_date >="] = date('Y-m-d',strtotime($from));
					$or["erp_work_order_detail.approved_date <="] = date('Y-m-d',strtotime($to));
					$or["project_id"] = $projects_id;
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
					
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
				$result = $result->innerjoin(
				["erp_work_order"=>"erp_work_order"],
				["erp_work_order.wo_id = erp_work_order_detail.wo_id"])->where($or)
				->select($wo_table)->order(['erp_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
		}
		
		else{
			
			if($this->role == "deputymanagerelectric")
			{
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1,"project_id IN"=>$projects_ids]);
				$result = $result->innerjoin(
				["erp_work_order"=>"erp_work_order"],
				["erp_work_order.wo_id = erp_work_order_detail.wo_id"])
				->select($wo_table)->order(['erp_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
			}else{
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
				$result = $result->innerjoin(
				["erp_work_order"=>"erp_work_order"],
				["erp_work_order.wo_id = erp_work_order_detail.wo_id"])
				->select($wo_table)->order(['erp_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
			}
		
		}	
		$this->set('role',$this->role);
		$this->set('wo_date',$result);
		
		if($this->request->is('post'))
		{	
			if(isset($this->request->data["go1"]))
			{
				$erp_work_order = TableRegistry::get("erp_work_order");
				$erp_work_order_detail = TableRegistry::get("erp_work_order_detail");
				$post = $this->request->data;	
				$or = array();				
				
				$or["wo_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["wo_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["party_userid IN"] = (!empty($post["party_userid"]) && $post["party_userid"][0] != "All")?$post["party_userid"]:NULL;
				$or["contract_type IN"] = (!empty($post["type_of_contract"]) && $post["type_of_contract"][0] != "All")?$post["type_of_contract"]:NULL;
				$or["payment_method"] = (!empty($post["payment_method"]) && $post["payment_method"][0] != "All")?$post["payment_method"]:NULL;
				$or["wo_no"] = (!empty($post["wo_no"]))?$post["wo_no"]:NULL;
				
				if($role == "deputymanagerelectric")
				{
					if($or["project_id IN"] == NULL)
					{
						$or["project_id IN"] = $projects_ids;
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				 
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
				$result = $result->innerjoin(
					["erp_work_order"=>"erp_work_order"],
					["erp_work_order.wo_id = erp_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
				
				$this->set('wo_date',$result);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				// debug($this->request->data);die;
				$erp_work_order = TableRegistry::get("erp_work_order");
				$erp_work_order_detail = TableRegistry::get("erp_work_order_detail");
				
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_party_userid"] = (!empty($post["e_party_userid"]))?explode(",",$post["e_party_userid"]):NULL;
				$post["e_contract_type"] = (!empty($post["e_contract_type"]))?explode(",",$post["e_contract_type"]):NULL;
				
				$or["erp_work_order.wo_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_work_order.wo_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_work_order.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_work_order.party_userid IN"] = (!empty($post["e_party_userid"]) && $post["e_party_userid"] != "All")?$post["e_party_userid"]:NULL;
				$or["erp_work_order.contract_type IN"] = (!empty($post["e_contract_type"]) && $post["e_contract_type"] != "All")?$post["e_contract_type"]:NULL;
				
				$or["erp_work_order.wo_no ="] = (!empty($post["e_wo_no"]))?$post["e_wo_no"]:NULL;
				$or["erp_work_order.payment_method ="] = (!empty($post["e_payment_method"]) && $post["e_payment_method"] != "All")?$post["e_payment_method"]:NULL;
				
				if($or["erp_work_order.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_work_order.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				
				$or["erp_work_order_detail.approved !="] = 0;
				
				// $result = $erp_work_order->find()->select($erp_work_order);
				// $result = $result->innerjoin(
					// ["erp_work_order_detail"=>"erp_work_order_detail"],
					// ["erp_work_order.wo_id = erp_work_order_detail.wo_id"])
					// ->where($or)->select("sum(erp_work_order_detail.amount)")->order(['erp_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
				
				$query = $wod_table->find();
				$result = $query->select(['amount' => $query->func()->sum('erp_work_order_detail.amount')])->GROUP(["erp_work_order_detail.wo_id"]);
				$result = $result->innerjoin(
					["erp_work_order"=>"erp_work_order"],
					["erp_work_order.wo_id = erp_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
					
				$rows = array();
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract","Amount");
				
				foreach($result as $retrive_data)
				{		
					
					if(isset($retrive_data["erp_work_order"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_work_order"]);
					}
					
					$is_agency = strpos($retrive_data['party_userid'],"NEC");
					if($is_agency == 1)
					{
						$partyname = $this->ERPfunction->get_agency_name_by_code($retrive_data['party_userid']);
					}else{
						$partyname = $this->ERPfunction->get_vendor_name($retrive_data['party_userid']);
					}
					
					$csv = array();
					$csv[] = date("d-m-Y",strtotime($retrive_data['wo_date']));
					$csv[] = $retrive_data['wo_no'];
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $partyname;
					$csv[] = $this->ERPfunction->get_contract_title($retrive_data['contract_type']);
					$csv[] = $retrive_data['amount'];
					$rows[] = $csv;
				}
				
				$filename = "wo_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				// debug($this->request->data);die;
				$erp_work_order = TableRegistry::get("erp_work_order");
				$erp_work_order_detail = TableRegistry::get("erp_work_order_detail");
				
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_party_userid"] = (!empty($post["e_party_userid"]))?explode(",",$post["e_party_userid"]):NULL;
				$post["e_contract_type"] = (!empty($post["e_contract_type"]))?explode(",",$post["e_contract_type"]):NULL;
				
				$or["erp_work_order.wo_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_work_order.wo_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_work_order.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_work_order.party_userid IN"] = (!empty($post["e_party_userid"]) && $post["e_party_userid"] != "All")?$post["e_party_userid"]:NULL;
				$or["erp_work_order.contract_type IN"] = (!empty($post["e_contract_type"]) && $post["e_contract_type"] != "All")?$post["e_contract_type"]:NULL;
				
				$or["erp_work_order.wo_no ="] = (!empty($post["e_wo_no"]))?$post["e_wo_no"]:NULL;
				$or["erp_work_order.payment_method ="] = (!empty($post["e_payment_method"]) && $post["e_payment_method"] != "All")?$post["e_payment_method"]:NULL;
				
				if($or["erp_work_order.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_work_order.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				
				$or["erp_work_order_detail.approved !="] = 0;
				
				$query = $wod_table->find();
				$result = $query->select(['amount' => $query->func()->sum('erp_work_order_detail.amount')])->GROUP(["erp_work_order_detail.wo_id"]);
				$result = $result->innerjoin(
					["erp_work_order"=>"erp_work_order"],
					["erp_work_order.wo_id = erp_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
					
				$rows = array();
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract","Amount");
				
				foreach($result as $retrive_data)
				{		
					
					if(isset($retrive_data["erp_work_order"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_work_order"]);
					}
					
					$is_agency = strpos($retrive_data['party_userid'],"NEC");
					if($is_agency == 1)
					{
						$partyname = $this->ERPfunction->get_agency_name_by_code($retrive_data['party_userid']);
					}else{
						$partyname = $this->ERPfunction->get_vendor_name($retrive_data['party_userid']);
					}
					
					$csv = array();
					$csv[] = date("d-m-Y",strtotime($retrive_data['wo_date']));
					$csv[] = $retrive_data['wo_no'];
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $partyname;
					$csv[] = $this->ERPfunction->get_contract_title($retrive_data['contract_type']);
					$csv[] = $retrive_data['amount'];
					$rows[] = $csv;
				}
				
				$this->set("rows",$rows);
				$this->render("worecordspdf");
			}
		}
	}
	
	public function previewapprovedwo($wo_id)
	{
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>1]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}
	
	public function printapprovedworecord($wo_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>1]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}

	public function previewapprovedplanningwo($wo_id)
	{
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>1])->order(['contract_no']);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}
	
	public function printapprovedplanningworecord($wo_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>1])->order(['contract_no']);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}
	
	public function addreference()
	{
		$this->autoRander = false;
		$erp_reference = TableRegistry::get('erp_reference'); 
		$post = $this->request->data;
		
		$save['title'] = $post['reference_name'];
		$save['project_id'] = $post['project_id'];
		$save['created_by'] = $this->request->session()->read('user_id');
		$save['created_date'] = date('Y-m-d');
		
		$row = $erp_reference->newEntity();
		$save_data = $erp_reference->patchEntity($row,$save);
		$erp_reference->save($save_data);
		$this->redirect(array("controller" => "Contract","action" => "adddrawing"));
	}
	
	public function adddrawing()
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'billingmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set("projects",$projects);
		
		$drawing_type = $this->ERPfunction->drawing_type_list();
		$this->set("drawing_type",$drawing_type);
		
		if($this->request->is('post'))
		{
			$post = $this->request->data();
			
			if(isset($post['drawing']['attach_file']))
			{
				$file =$post['drawing']['attach_file'];
				$size = count($file);
				for($i=0;$i<$size;$i++) {
					$parts = pathinfo($post['drawing']['attach_file'][$i]['name']);
					
				}
				$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
				
				if($ext != 0) {
					$erp_drawing = TableRegistry::get('erp_drawing');
					$this->request->data['created_by'] = $this->request->session()->read('user_id');
					$this->request->data['created_date'] = date('Y-m-d');
					$row = $erp_drawing->newEntity();
					$save_data = $erp_drawing->patchEntity($row,$this->request->data);
					if($erp_drawing->save($save_data))
					{
						$drowing_id = $save_data->drawing_id;
						$this->ERPfunction->add_drawing_detail($this->request->data['drawing'],$drowing_id);
					}
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}	
			}
			else{
				
				$erp_drawing = TableRegistry::get('erp_drawing');
				$this->request->data['created_by'] = $this->request->session()->read('user_id');
				$this->request->data['created_date'] = date('Y-m-d');
				$row = $erp_drawing->newEntity();
				$save_data = $erp_drawing->patchEntity($row,$this->request->data);
				if($erp_drawing->save($save_data))
				{
					$drowing_id = $save_data->drawing_id;
					$this->ERPfunction->add_drawing_detail($this->request->data['drawing'],$drowing_id);
				}
				
			}
			
			
			
		}
	}
	
	public function drawingrecords($projects_id=null)
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'billingmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set("projects",$projects);
		
		$drawing_type = $this->ERPfunction->drawing_type_list();
		$this->set("drawing_type",$drawing_type);
		
		$erp_drawing = TableRegistry::get('erp_drawing');
		$dtl_tbl = TableRegistry::get('erp_drawing_detail');
		
		$user_id = $this->request->session()->read('user_id');
		$user_projects = $this->Usermanage->users_project($user_id);
		
		$role = $this->Usermanage->get_user_role($user_id);
		$this->set("role",$role);
		
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["project_id"] = ($projects_id!=null)?$projects_id:NULL;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
			
			
				$result = $erp_drawing->find()->select($erp_drawing)->group('erp_drawing.drawing_id');
					$result = $result->innerjoin(
						["erp_drawing_detail"=>"erp_drawing_detail"],
						["erp_drawing.drawing_id = erp_drawing_detail.drawing_id"])
						->select($dtl_tbl)->hydrate(false)->toArray();
			
		}
		else{
			
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($user_projects))
				{
					$result = $erp_drawing->find()->select($erp_drawing)->group('erp_drawing.drawing_id');
					$result = $result->innerjoin(
						["erp_drawing_detail"=>"erp_drawing_detail"],
						["erp_drawing.drawing_id = erp_drawing_detail.drawing_id"])
						->where(['erp_drawing.project_id IN'=>$user_projects])->select($dtl_tbl)->hydrate(false)->toArray();
				}
				else
				{
					$result=array();
				}
			}
			else
			{
				$result = $erp_drawing->find()->select($erp_drawing)->group('erp_drawing.drawing_id');
					$result = $result->innerjoin(
						["erp_drawing_detail"=>"erp_drawing_detail"],
						["erp_drawing.drawing_id = erp_drawing_detail.drawing_id"])
						->select($dtl_tbl)->hydrate(false)->toArray();
			}
			
		}
		
		
		$this->set('drawing_list',$result);
		
		if($this->request->is('post'))
		{
			if(isset($this->request->data["go"]))
			{
				$post = $this->request->data;
				$or = array();				
				
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["drawing_type IN"] = (!empty($post["drawing_type"]) && $post["drawing_type"][0] != "All")?$post["drawing_type"]:NULL;
				
				if($or["project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						$or["project_id IN"] = $user_projects;
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$result = $erp_drawing->find()->select($erp_drawing)->group('erp_drawing.drawing_id');
					$result = $result->innerjoin(
						["erp_drawing_detail"=>"erp_drawing_detail"],
						["erp_drawing.drawing_id = erp_drawing_detail.drawing_id"])
						->select($dtl_tbl)->where($or)->hydrate(false)->toArray();
						
				$this->set('drawing_list',$result);
			}
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "drawing_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$this->set("rows",$rows);
				$this->render("drawingrecordspdf");
			}
		}
	}
	
	public function editdrawing($drawing_id)
	{
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set("projects",$projects);
		
		$drawing_type = $this->ERPfunction->drawing_type_list();
		$this->set("drawing_type",$drawing_type);
		
		$erp_drawing = TableRegistry::get('erp_drawing');
		$dtl_tbl = TableRegistry::get('erp_drawing_detail');
		
		$data = $erp_drawing->get($drawing_id);
		$detail_data = $dtl_tbl->find()->where(["drawing_id"=>$drawing_id])->hydrate(false)->toArray();
		
		$this->set("data",$data);
		$this->set("detail_data",$detail_data);
		
		if($this->request->is('post'))
		{
			$post = $this->request->data();
			if(isset($post['drawing']['attach_file']))
			{
				$file =$post['drawing']['attach_file'];
				$size = count($file);
				for($i=0;$i<$size;$i++) {
					$parts = pathinfo($post['drawing']['attach_file'][$i]['name']);
					
				}
				$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
				
				if($ext != 0) {
				
					$erp_drawing = TableRegistry::get('erp_drawing');
					$row = $erp_drawing->get($drawing_id);
					$this->request->data['last_edited_by'] = $this->user_id;
					$this->request->data['last_edit_date'] = date('Y-m-d');
					$save_data = $erp_drawing->patchEntity($row,$this->request->data);
					if($erp_drawing->save($save_data))
					{
						$drowing_id = $save_data->drawing_id;
						$this->ERPfunction->edit_drawing_detail($this->request->data['drawing'],$drowing_id);
						echo "<script>window.close();</script>";
					}
				
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				$erp_drawing = TableRegistry::get('erp_drawing');
				$row = $erp_drawing->get($drawing_id);
				$this->request->data['last_edited_by'] = $this->user_id;
				$this->request->data['last_edit_date'] = date('Y-m-d');
				$save_data = $erp_drawing->patchEntity($row,$this->request->data);
				if($erp_drawing->save($save_data))
				{
					$drowing_id = $save_data->drawing_id;
					$this->ERPfunction->edit_drawing_detail($this->request->data['drawing'],$drowing_id);
					echo "<script>window.close();</script>";
				}
			}
			
		}
	}
	
	public function viewdrawing($drawing_id)
	{
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set("projects",$projects);
		
		$drawing_type = $this->ERPfunction->drawing_type_list();
		$this->set("drawing_type",$drawing_type);
		
		$erp_drawing = TableRegistry::get('erp_drawing');
		$dtl_tbl = TableRegistry::get('erp_drawing_detail');
		
		$data = $erp_drawing->get($drawing_id);
		$detail_data = $dtl_tbl->find()->where(["drawing_id"=>$drawing_id])->hydrate(false)->toArray();
		
		$this->set("data",$data);
		$this->set("detail_data",$detail_data);
	}
	
	public function deletedrawing($drawing_id)
	{
		$dtl_tbl = TableRegistry::get('erp_drawing_detail');
		
	$delete = $dtl_tbl->deleteAll(['drawing_id'=>$drawing_id]);
		if($delete)
		{
			$erp_drawing = TableRegistry::get('erp_drawing');
			$row = $erp_drawing->get($drawing_id);
			if($erp_drawing->delete($row))
			{
				$this->Flash->success(__('Record Deleted Successfully', null), 
				'default', 
				array('class' => 'success'));
				$this->redirect(array("controller" => "Contract","action" => "drawingrecords"));
			}
		}
	}
	
	public function printwo($wo_id,$mail='null')
	{
		$this->viewBuilder()->layout("wo");
		//require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
		$this->set('mail',$mail);
	}
	
	public function printwoapproved($wo_id)
	{
		$this->viewBuilder()->layout("wo");
		//require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>1]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}
	
	public function addsubcontractbill()
	{		
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
    	$this->set('back_url',$back_url);
    	$this->set('back_page',$back_page);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		// $agency_tbl = TableRegistry::get("erp_agency");
		// $agency_list = $agency_tbl->find("All")->toArray();
		// $this->set('agency_list',$agency_list);
		
		$users_table = TableRegistry::get('erp_users'); 
		$temp_employee = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0,"pay_type"=>"temporary"])->select(['user_id'])->hydrate(false)->toArray();
		$this->set('temp_employee',$temp_employee);
		
		$table_category=TableRegistry::get('erp_category_master');
		$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option'));
		$this->set('description_options',$description_options);
		
		//Role
		$role = $this->role;
		$this->set('role',$role);
		
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('projects',$project_list);
		
		$erp_sub_contract = TableRegistry::get('erp_sub_contract'); 
		if($this->request->is('post'))
		{
			if(isset($_FILES['attach_file'])){
				$file =$_FILES['attach_file']["name"];
				$size = count($file);
				for($i=0;$i<$size;$i++) {
					$parts = pathinfo($_FILES['attach_file']['name'][$i]);
				}
				$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
				if($ext != 0) {
				
					$post = $this->request->data;
			// debug($post);die;
			$erp_sub_contract = TableRegistry::get('erp_sub_contract');
			
			$count = $erp_sub_contract->find()->where(["project_id"=>$post['project_id'],"party_id"=>$post['party_id'],"approval"=>0])->count();
			if($count)
			{
				$this->Flash->error('Please approve record from alert first');
				return $this->redirect($this->referer());
			}else{
				$save['project_code'] = $post['project_code'];
				$save['project_id'] = $post['project_id'];
				$save['type_of_bill'] = $post['type_of_bill'];
				$save['yashnand_gst_no'] = $post['yashnand_gstno'];
				$save['our_abstract_no'] = $post['abstrack_number'];
				$save['wo_no'] = $post['wo_no_list'];
				$save['bill_mode'] = $post['bill_mode'];
				$save['bill_no'] = $post['bill_no'];
				$save['bill_date'] = date("Y-m-d",strtotime($post['bill_date']));
				$save['party_id'] = $post['party_id'];
				$save['party_type'] = $post['party_type_radio'];
				$save['party_identy'] = $post['party_identy'];
				$save['party_address'] = $post['party_address'];
				$save['party_no1'] = $post['party_no1'];
				$save['party_no2'] = $post['party_no2'];
				$save['party_pan_no'] = $post['party_pan_no'];
				$save['party_gst_no'] = $post['party_gst_no'];
				$save['bill_from_date'] = date("Y-m-d",strtotime($post['bill_from_date']));
				$save['bill_to_date'] = date("Y-m-d",strtotime($post['bill_to_date']));
				$save['type_of_work'] = $post['type_of_work'];
				$save['created_date'] = date('Y-m-d H:i:s');
				$save['created_by'] = $this->request->session()->read('user_id');
				
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
				$save['attachment'] = json_encode($all_files);
				$save['debit_this_bill'] = $post['debit_this_bill'];
				$save['debit_previous_bill'] = $post['debit_previous_bill'];
				if($post['type_of_bill'] == "Labour with Material")
				{
					$save['debit_till_date'] = $post['debit_till_date_labour'];
				}else{
					$save['debit_till_date'] = $post['debit_till_date'];
				}
				
				$save['reconciliation_this_bill'] = $post['reconciliation_this_bill'];
				$save['reconciliation_previous_bill'] = $post['reconciliation_previous_bill'];
				if($post['type_of_bill'] == "Labour with Material")
				{
					$save['reconciliation_till_date'] = $post['reconciliation_till_date_labour'];
				}else{
					$save['reconciliation_till_date'] = $post['reconciliation_till_date'];
				}
				
				$save['sum_a'] = $post['sum_a'];
				$save['sum_b'] = $post['sum_b'];
				$save['sum_c'] = $post['sum_c'];
				if($post['type_of_bill'] == "Labour with Material")
				{
					$save['material_advance'] = $post['material_advance'];
					$save['amount_till_date_labour'] = $post['amount_till_date_labour'];
					$save['amount_upto_previous_labour'] = $post['amount_upto_previous_labour'];
				}
				$save['this_bill_amount'] = $post['this_bill_amount'];
				$save['cgst_percentage'] = $post['cgst_percentage'];
				$save['cgst'] = $post['cgst'];
				$save['sgst_percentage'] = $post['sgst_percentage'];
				$save['sgst'] = $post['sgst'];
				$save['igst_percentage'] = $post['igst_percentage'];
				$save['igst'] = $post['igst'];
				$save['gross_amount'] = $post['gross_amount'];
				$save['retention_percentage'] = $post['retention_percentage'];
				$save['retention_money'] = $post['retention_money'];
				$save['net_amount'] = $post['net_amount'];
				$entity_row = $erp_sub_contract->newEntity();
				$save_data = $erp_sub_contract->patchEntity($entity_row,$save);
				if($erp_sub_contract->save($save_data))
				{
					$sub_contract_id = $save_data->id;
					$this->ERPfunction->add_sub_contract_detail($post['bill'],$sub_contract_id);
					
					$this->Flash->success(__('Record Created Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => $back_url,"action" => $back_page));
				}
			}
					
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				$post = $this->request->data;
			// debug($post);die;
			$erp_sub_contract = TableRegistry::get('erp_sub_contract');
			
			$count = $erp_sub_contract->find()->where(["project_id"=>$post['project_id'],"party_id"=>$post['party_id'],"approval"=>0])->count();
			if($count)
			{
				$this->Flash->error('Please approve record from alert first');
				return $this->redirect($this->referer());
			}else{
				$save['project_code'] = $post['project_code'];
				$save['project_id'] = $post['project_id'];
				$save['type_of_bill'] = $post['type_of_bill'];
				$save['yashnand_gst_no'] = $post['yashnand_gstno'];
				$save['our_abstract_no'] = $post['abstrack_number'];
				$save['wo_no'] = $post['wo_no_list'];
				$save['bill_mode'] = $post['bill_mode'];
				$save['bill_no'] = $post['bill_no'];
				$save['bill_date'] = date("Y-m-d",strtotime($post['bill_date']));
				$save['party_id'] = $post['party_id'];
				$save['party_type'] = $post['party_type_radio'];
				$save['party_identy'] = $post['party_identy'];
				$save['party_address'] = $post['party_address'];
				$save['party_no1'] = $post['party_no1'];
				$save['party_no2'] = $post['party_no2'];
				$save['party_pan_no'] = $post['party_pan_no'];
				$save['party_gst_no'] = $post['party_gst_no'];
				$save['bill_from_date'] = date("Y-m-d",strtotime($post['bill_from_date']));
				$save['bill_to_date'] = date("Y-m-d",strtotime($post['bill_to_date']));
				$save['type_of_work'] = $post['type_of_work'];
				$save['created_date'] = date('Y-m-d H:i:s');
				$save['created_by'] = $this->request->session()->read('user_id');
				
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
				$save['attachment'] = json_encode($all_files);
				$save['debit_this_bill'] = $post['debit_this_bill'];
				$save['debit_previous_bill'] = $post['debit_previous_bill'];
				if($post['type_of_bill'] == "Labour with Material")
				{
					$save['debit_till_date'] = $post['debit_till_date_labour'];
				}else{
					$save['debit_till_date'] = $post['debit_till_date'];
				}
				
				$save['reconciliation_this_bill'] = $post['reconciliation_this_bill'];
				$save['reconciliation_previous_bill'] = $post['reconciliation_previous_bill'];
				if($post['type_of_bill'] == "Labour with Material")
				{
					$save['reconciliation_till_date'] = $post['reconciliation_till_date_labour'];
				}else{
					$save['reconciliation_till_date'] = $post['reconciliation_till_date'];
				}
				
				$save['sum_a'] = $post['sum_a'];
				$save['sum_b'] = $post['sum_b'];
				$save['sum_c'] = $post['sum_c'];
				if($post['type_of_bill'] == "Labour with Material")
				{
					$save['material_advance'] = $post['material_advance'];
					$save['amount_till_date_labour'] = $post['amount_till_date_labour'];
					$save['amount_upto_previous_labour'] = $post['amount_upto_previous_labour'];
				}
				$save['this_bill_amount'] = $post['this_bill_amount'];
				$save['cgst_percentage'] = $post['cgst_percentage'];
				$save['cgst'] = $post['cgst'];
				$save['sgst_percentage'] = $post['sgst_percentage'];
				$save['sgst'] = $post['sgst'];
				$save['igst_percentage'] = $post['igst_percentage'];
				$save['igst'] = $post['igst'];
				$save['gross_amount'] = $post['gross_amount'];
				$save['retention_percentage'] = $post['retention_percentage'];
				$save['retention_money'] = $post['retention_money'];
				$save['net_amount'] = $post['net_amount'];
				$entity_row = $erp_sub_contract->newEntity();
				$save_data = $erp_sub_contract->patchEntity($entity_row,$save);
				if($erp_sub_contract->save($save_data))
				{
					$sub_contract_id = $save_data->id;
					$this->ERPfunction->add_sub_contract_detail($post['bill'],$sub_contract_id);
					
					$this->Flash->success(__('Record Created Successfully', null), 
					'default', 
					array('class' => 'success'));
					$this->redirect(array("controller" => $back_url,"action" => $back_page));
				}
			}
			}
			
		}
	}
	
	public function subcontractbillalert()
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		//Role
		$role = $this->role;
		$this->set('role',$role);
		
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
    	$sub_contract_data = $erp_sub_contract->find()->where(["approval"=>0])->hydrate(false)->toArray();
    	$this->set('sub_contract_data',$sub_contract_data);
		
	}
	
	public function editsubcontractbill($id)
	{
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$this->set('agency_list',$agency_list);
		
		$users_table = TableRegistry::get('erp_users'); 
		$temp_employee = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0,"pay_type"=>"temporary"])->select(['user_id'])->hydrate(false)->toArray();
		$this->set('temp_employee',$temp_employee);
		
		//Role
		$role = $this->role;
		$this->set('role',$role);
		
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('projects',$project_list);
		
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		$data = $erp_sub_contract->get($id);
		$detail_data = $erp_sub_contract_detail->find()->where(["sub_contract_id"=>$id])->order(["item_no"=>"asc"])->hydrate(false)->toArray();
		
		$this->set('data',$data);
		$this->set('detail_data',$detail_data);
		
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('projects',$project_list);
		
		$erp_work_order = TableRegistry::get('erp_work_order'); 
		$wo_no_list = $erp_work_order->find("list",["keyField"=>"wo_no","valueField"=>"wo_no"])->where(["project_id"=>$data->project_id,"party_userid"=>$data->party_id]);
		$this->set('wo_no_list',$wo_no_list);
		
		$erp_category_master = TableRegistry::get('erp_category_master'); 
		$description_options = $erp_category_master->find("list",["keyField"=>"cat_id","valueField"=>"category_title"])->where(["type"=>"subcontractbill_option","project_id"=>$data->project_id]);
		$this->set('description_options',$description_options);
		
		if($this->request->is('post'))
		{
			if(isset($_FILES['attach_file'])){
				$file =$_FILES['attach_file']["name"];
				$size = count($file);
				for($i=0;$i<$size;$i++) {
					$parts = pathinfo($_FILES['attach_file']['name'][$i]);
				}
				$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
				if($ext != 0) {
					$post = $this->request->data();
			
			$save['project_code'] = $post['project_code'];
			$save['project_id'] = $post['project_id'];
			$save['type_of_bill'] = $post['type_of_bill'];
			$save['yashnand_gst_no'] = $post['yashnand_gstno'];
			$save['our_abstract_no'] = $post['abstrack_number'];
			$save['wo_no'] = $post['wo_no_list'];
			$save['bill_mode'] = $post['bill_mode'];
			$save['bill_no'] = $post['bill_no'];
			$save['bill_date'] = date("Y-m-d",strtotime($post['bill_date']));
			$save['party_id'] = $post['party_id'];
			$save['party_type'] = $post['party_type_radio'];
			$save['party_identy'] = $post['party_identy'];
			$save['party_address'] = $post['party_address'];
			$save['party_no1'] = $post['party_no1'];
			$save['party_no2'] = $post['party_no2'];
			$save['party_pan_no'] = $post['party_pan_no'];
			$save['party_gst_no'] = $post['party_gst_no'];
			$save['bill_from_date'] = date("Y-m-d",strtotime($post['bill_from_date']));
			$save['bill_to_date'] = date("Y-m-d",strtotime($post['bill_to_date']));
			$save['type_of_work'] = $post['type_of_work'];
			$save['updated_date'] = date('Y-m-d H:i:s');
			$save['updated_by'] = $this->request->session()->read('user_id');
			
			$old_files = array();
			if(isset($post["old_attach_file"]))
			{
				$old_files = $post["old_attach_file"];				
			}
			if(isset($_FILES["attach_file"]["name"]))
			{
				$file = $this->ERPfunction->upload_file("attach_file");	
				if(!empty($file))
				foreach($file as $attachment_file)
				{
					$old_files[] = $attachment_file;
				}					
			}
			$save['attachment'] = json_encode($old_files);
			
			$save['debit_this_bill'] = $post['debit_this_bill'];
			$save['debit_previous_bill'] = $post['debit_previous_bill'];
			if($post['type_of_bill'] == "Labour with Material")
			{
				$save['debit_till_date'] = $post['debit_till_date_labour'];
			}else{
				$save['debit_till_date'] = $post['debit_till_date'];
			}
			$save['reconciliation_this_bill'] = $post['reconciliation_this_bill'];
			$save['reconciliation_previous_bill'] = $post['reconciliation_previous_bill'];
			if($post['type_of_bill'] == "Labour with Material")
			{
				$save['reconciliation_till_date'] = $post['reconciliation_till_date_labour'];
			}else{
				$save['reconciliation_till_date'] = $post['reconciliation_till_date'];
			}
			$save['sum_a'] = $post['sum_a'];
			$save['sum_b'] = $post['sum_b'];
			$save['sum_c'] = $post['sum_c'];

			if($post['type_of_bill'] == "Labour with Material")
			{
				$save['material_advance'] = $post['material_advance'];
				$save['amount_till_date_labour'] = $post['amount_till_date_labour'];
				$save['amount_upto_previous_labour'] = $post['amount_upto_previous_labour'];
			}

			$save['this_bill_amount'] = $post['this_bill_amount'];
			$save['cgst_percentage'] = $post['cgst_percentage'];
			$save['cgst'] = $post['cgst'];
			$save['sgst_percentage'] = $post['sgst_percentage'];
			$save['sgst'] = $post['sgst'];
			$save['igst_percentage'] = $post['igst_percentage'];
			$save['igst'] = $post['igst'];
			$save['gross_amount'] = $post['gross_amount'];
			$save['retention_percentage'] = $post['retention_percentage'];
			$save['retention_money'] = $post['retention_money'];
			$save['net_amount'] = $post['net_amount'];
			
			$entity_row = $erp_sub_contract->get($id);
			$save_data = $erp_sub_contract->patchEntity($entity_row,$save);
			if($erp_sub_contract->save($save_data))
			{
				$sub_contract_id = $save_data->id;
				$this->ERPfunction->edit_sub_contract_detail($post['bill'],$sub_contract_id);
				
				$this->Flash->success(__('Record Updated Successfully', null), 
				'default', 
				array('class' => 'success'));
				echo "<script>window.close();</script>";
			}
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				$post = $this->request->data();
			
			$save['project_code'] = $post['project_code'];
			$save['project_id'] = $post['project_id'];
			$save['type_of_bill'] = $post['type_of_bill'];
			$save['yashnand_gst_no'] = $post['yashnand_gstno'];
			$save['our_abstract_no'] = $post['abstrack_number'];
			$save['wo_no'] = $post['wo_no_list'];
			$save['bill_mode'] = $post['bill_mode'];
			$save['bill_no'] = $post['bill_no'];
			$save['bill_date'] = date("Y-m-d",strtotime($post['bill_date']));
			$save['party_id'] = $post['party_id'];
			$save['party_type'] = $post['party_type_radio'];
			$save['party_identy'] = $post['party_identy'];
			$save['party_address'] = $post['party_address'];
			$save['party_no1'] = $post['party_no1'];
			$save['party_no2'] = $post['party_no2'];
			$save['party_pan_no'] = $post['party_pan_no'];
			$save['party_gst_no'] = $post['party_gst_no'];
			$save['bill_from_date'] = date("Y-m-d",strtotime($post['bill_from_date']));
			$save['bill_to_date'] = date("Y-m-d",strtotime($post['bill_to_date']));
			$save['type_of_work'] = $post['type_of_work'];
			$save['updated_date'] = date('Y-m-d H:i:s');
			$save['updated_by'] = $this->request->session()->read('user_id');
			
			$old_files = array();
			if(isset($post["old_attach_file"]))
			{
				$old_files = $post["old_attach_file"];				
			}
			if(isset($_FILES["attach_file"]["name"]))
			{
				$file = $this->ERPfunction->upload_file("attach_file");	
				if(!empty($file))
				foreach($file as $attachment_file)
				{
					$old_files[] = $attachment_file;
				}					
			}
			$save['attachment'] = json_encode($old_files);
			
			$save['debit_this_bill'] = $post['debit_this_bill'];
			$save['debit_previous_bill'] = $post['debit_previous_bill'];
			if($post['type_of_bill'] == "Labour with Material")
			{
				$save['debit_till_date'] = $post['debit_till_date_labour'];
			}else{
				$save['debit_till_date'] = $post['debit_till_date'];
			}
			$save['reconciliation_this_bill'] = $post['reconciliation_this_bill'];
			$save['reconciliation_previous_bill'] = $post['reconciliation_previous_bill'];
			if($post['type_of_bill'] == "Labour with Material")
			{
				$save['reconciliation_till_date'] = $post['reconciliation_till_date_labour'];
			}else{
				$save['reconciliation_till_date'] = $post['reconciliation_till_date'];
			}
			$save['sum_a'] = $post['sum_a'];
			$save['sum_b'] = $post['sum_b'];
			$save['sum_c'] = $post['sum_c'];

			if($post['type_of_bill'] == "Labour with Material")
			{
				$save['material_advance'] = $post['material_advance'];
				$save['amount_till_date_labour'] = $post['amount_till_date_labour'];
				$save['amount_upto_previous_labour'] = $post['amount_upto_previous_labour'];
			}

			$save['this_bill_amount'] = $post['this_bill_amount'];
			$save['cgst_percentage'] = $post['cgst_percentage'];
			$save['cgst'] = $post['cgst'];
			$save['sgst_percentage'] = $post['sgst_percentage'];
			$save['sgst'] = $post['sgst'];
			$save['igst_percentage'] = $post['igst_percentage'];
			$save['igst'] = $post['igst'];
			$save['gross_amount'] = $post['gross_amount'];
			$save['retention_percentage'] = $post['retention_percentage'];
			$save['retention_money'] = $post['retention_money'];
			$save['net_amount'] = $post['net_amount'];
			
			$entity_row = $erp_sub_contract->get($id);
			$save_data = $erp_sub_contract->patchEntity($entity_row,$save);
			if($erp_sub_contract->save($save_data))
			{
				$sub_contract_id = $save_data->id;
				$this->ERPfunction->edit_sub_contract_detail($post['bill'],$sub_contract_id);
				
				$this->Flash->success(__('Record Updated Successfully', null), 
				'default', 
				array('class' => 'success'));
				echo "<script>window.close();</script>";
			}
			}
		
			
		}
	}
	
	public function deletesubcontractbill($id)
	{
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		
		$delete_ok = $erp_sub_contract_detail->deleteAll(["sub_contract_id"=>$id]);
		if($delete_ok)
		{
			$row = $erp_sub_contract->get($id);
			if($erp_sub_contract->delete($row))
			{
				$this->Flash->success(__('Record Deleted Successfully', null), 
				'default', 
				array('class' => 'success'));
				$this->redirect(array("controller" => "Contract","action" => "subcontractbillalert"));
			}
		}
	}
	
	public function previewsubcontract($id)
	{
		//Role
		$role = $this->role;
		$this->set('role',$role);
		
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		$data = $erp_sub_contract->get($id);
		$detail_data = $erp_sub_contract_detail->find()->where(['sub_contract_id'=>$id])->order("item_no","ASC")->hydrate(false)->toArray();
		$this->set('data',$data);
		$this->set('detail_data',$detail_data);
	}
	
	public function printsubcontract($id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		$data = $erp_sub_contract->get($id);
		$detail_data = $erp_sub_contract_detail->find()->where(['sub_contract_id'=>$id])->order("item_no","ASC")->hydrate(false)->toArray();
		$this->set('data',$data);
		$this->set('detail_data',$detail_data);
	}
	
	public function subcontractletterhead($id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		$data = $erp_sub_contract->get($id);
		$detail_data = $erp_sub_contract_detail->find()->where(['sub_contract_id'=>$id])->hydrate(false)->toArray();
		$this->set('data',$data);
		$this->set('detail_data',$detail_data);
	}
	
	public function subcontractrecords($projects_id=null,$from=null,$to=null)
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$this->set('agency_list',$agency_list);
		
		//Role
		$role = $this->role;
		$this->set('role',$role);
		
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('projects',$project_list);
		
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["bill_date >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["bill_date <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["project_id"] = ($projects_id!=null)?$projects_id:NULL;
			$or1["approval"] = 1;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
			$sub_contract_data = $erp_sub_contract->find()->where([$or1])->hydrate(false)->toArray();
		}
		else{
			$sub_contract_data = $erp_sub_contract->find()->where(["approval"=>1])->hydrate(false)->toArray();
			
		}
		
    	
    	$this->set('data',$sub_contract_data);
		
		if($this->request->is('post'))
		{	
			if(isset($this->request->data["go"]))
			{
				$erp_sub_contract = TableRegistry::get("erp_sub_contract");
				$erp_sub_contract_detail = TableRegistry::get("erp_sub_contract_detail");
				$post = $this->request->data;	
				$or = array();				
				
				$or["bill_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["bill_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "" )?$post["project_id"]:NULL;
				$or["party_id IN"] = (!empty($post["party_id"]) && $post["party_id"] != "")?$post["party_id"]:NULL;
				$or["bill_no"] = (!empty($post["bill_no"]))?$post["bill_no"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				$or['approval'] = 1;
								
				$result = $erp_sub_contract->find()->select($erp_sub_contract)->where($or)->hydrate(false)->toArray();
				
				$this->set('data',$result);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "sub_contract_list.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$this->set("rows",$rows);
				$this->render("subcontractpdf");
			}
		}
	}
	
	public function reversesubcontract($id)
	{		
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		
		$row = $erp_sub_contract->get($id);
		$row->approval = 0;
		$row->approval_by = NULL;
		$row->approval_date = NULL;
		if($erp_sub_contract->save($row))
		{
			$date=date('Y-m-d');
			$query = $erp_sub_contract_detail->query();
			$approve = $query->update()
						->set(['approval'=>0,
						"approval_date"=>NULL,
						'approval_by'=>NULL])
						->where(['sub_contract_id' => $id])
						->execute();
			$this->Flash->success(__('Record Deleted Successfully', null), 
				'default', 
				array('class' => 'success'));
			$this->redirect(array("controller" => "Contract","action" => "subcontractrecords"));
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
	
	public function workdescription()
	{
		$category_master_Table = TableRegistry::get('erp_category_master');
		$descriptions = $category_master_Table->find()->where(['type'=>'subcontractbill_option']);
		$this->set('descriptions',$descriptions);
		$erp_work_sub_group = TableRegistry::get("erp_work_sub_group");
		$erpWorkSubGroupData = $erp_work_sub_group->find()->hydrate(false)->toArray();
		$this->set('erpWorkSubGroupData',$erpWorkSubGroupData);
		$this->set('role',$this->role);
	}

	public function joinworkdescription()
    {
        $this->autoRender = false;
        $data = $this->request->data;
        $erp_category_master = TableRegistry::get("erp_category_master");
        $join_hstr = TableRegistry::get("erp_join_work_description_history");
        $master_workgroup = $data['material_id'];
        $base_workgroup = $data['base_material'];

        $master_workgroup_data = $erp_category_master->get($master_workgroup);
        $base_workgroup_data = $erp_category_master->get($base_workgroup);
	
		// Enable Project merge from child to master
		$old_project = json_decode($base_workgroup_data['project_id']);
		$new_project = json_decode($master_workgroup_data['project_id']);
		$merged_project = array_merge($old_project, $new_project);
		$merged_project = array_unique($merged_project);
		$formated_project = json_encode($merged_project);

		$categoryId = $master_workgroup_data['cat_id'];
		$join_material = $base_workgroup_data->toArray();

        $row = $join_hstr->newEntity($join_material);
        $row['category_title'] = $join_material['category_title'];
		$row['join_category_title'] = $master_workgroup;
        $row['join_by'] = $this->request->session()->read('user_id');
        $row['join_date'] = date("Y-m-d");
        $save_history = $join_hstr->save($row);

        if ($save_history) {
            ######### Start code for update master material id on base material id ###########
            
			// Project Update from child to master
			$updateQuery = $erp_category_master->query();
			$updateQuery->update()
				->set(["project_id" => $formated_project])
				->where(['cat_id' => $categoryId])
				->execute();

			//Update work_Description id in erp_planning_work_order_detail
            $erp_planning_work_order_detail = TableRegistry::get('erp_planning_work_order_detail');
            $query = $erp_planning_work_order_detail->query();
            $query->update()
                ->set(['material_name' => $master_workgroup])
                ->where(['material_name' => $base_workgroup])
                ->execute();

            ######### End code for update master material id on base material id ###########
            // Base Description delete
            $erp_category_master->delete($base_workgroup_data);

            $this->Flash->success(__('Work Description Join Successfully.', null),
                'default',
                array('class' => 'success'));
        }
        $this->redirect(["action" => "workdescription"]);
    }

	public function joinworksubgroup() {
		$this->autoRender = false;
        $data = $this->request->data;
        $erp_planning_work_head = TableRegistry::get("erp_planning_work_head");
        $join_history = TableRegistry::get("erp_join_work_sub_group_history");
        $master_workgroup = $data['material_id'];
        $base_workgroup = $data['base_material'];
        $master_workgroup_data = $erp_planning_work_head->get($master_workgroup);
        $base_workgroup_data = $erp_planning_work_head->get($base_workgroup);

        $join_workgroup = $base_workgroup_data->toArray();
        $row = $join_history->newEntity($join_workgroup);
        $row['work_head_title'] = $join_workgroup['work_head_title'];
		$row['work_head_code'] = $join_workgroup['work_head_code'];
		$row['type_of_contract'] = $join_workgroup['type_of_contract'];
		$row['join_work_head_title'] = $master_workgroup;
        $row['join_by'] = $this->request->session()->read('user_id');
        $row['join_date'] = date("Y-m-d");
        $save_history = $join_history->save($row);

        if ($save_history) {
            ######### Start code for update master material id on base material id ###########
            
			//Update work_Description id in erp_planning_work_order_detail
            $erp_planning_work_order_detail = TableRegistry::get('erp_planning_work_order');
            $query = $erp_planning_work_order_detail->query();
            $query->update()
                ->set(['work_type' => $master_workgroup])
                ->where(['work_type' => $base_workgroup])
                ->execute();

            ######### End code for update master material id on base material id ###########
            // Base material delete
            $erp_planning_work_head->delete($base_workgroup_data);

            $this->Flash->success(__('Work Sub-group Join Successfully.', null),
                'default',
                array('class' => 'success'));
        }
        $this->redirect(["action" => "planningworkheadlist"]);
	}

	public function planningworkheadlist()
	{
		$work_head_tbl = TableRegistry::get('erp_planning_work_head');
		$work_head_data = $work_head_tbl->find()->hydrate(false)->toArray();
		$this->set('head_list',$work_head_data);
		$this->set('role',$this->role);
	}
	
	public function planningpreparewo()
	{
		// ini_set('memory_limit', '-1');
		$wo_table = TableRegistry::get('erp_planning_work_order');
		
		$erp_work_head = TableRegistry::get('erp_planning_work_head'); 
		$head_list = $erp_work_head->find();
		$this->set('work_head_list',$head_list);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$table_category=TableRegistry::get('erp_category_master');
		$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option'))->hydrate(false)->toArray();
		$this->set('description_options',$description_options);
		
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == "deputymanagerelectric"){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		if($this->request->is("post")) {
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
					$post = $this->request->data;
					// debug($post);die;
					// Check Work Order exist with same project and party_address
					$count = $wo_table->find()->where(['project_id'=>$post['project_id'],'party_userid'=>$post['party_id']])->count();
					if($count == 0)
					{
						$code = $this->ERPfunction->get_projectcode($post['project_id']);
						$new_wono = $this->ERPfunction->generate_auto_id($post['project_id'],"erp_planning_work_order","wo_id","wo_no");
						$new_wono = sprintf("%09d", $new_wono);
						$wo_no = $code.'/WO/'.$new_wono;
					
						$save['project_id'] = $post['project_id'];
						$save['bill_mode'] = $post['bill_mode'];
						$save['wo_no'] = $wo_no;
						$save['yashnand_gst_no'] = $post['yashnand_gstno'];
						$save['wo_date'] = $this->ERPfunction->set_date($post['wo_date']);
						$save['party_userid'] = $post['party_id'];
						$save['party_id'] = $post['party_identy'];
						$save['party_address'] = $post['party_address'];
						$save['party_no1'] = $post['party_no1'];
						$save['party_no2'] = $post['party_no2'];
						$save['party_email'] = $post['party_email'];
						$save['party_pan_no'] = $post['party_pan_no'];
						$save['party_gst_no'] = $post['party_gst_no'];
						$save['contract_type'] = $post['type_of_contract'];
						$save['work_type'] = $post['work_type'];
						$save['payment_method'] = $post['payment_method'];

						$save['cgst_percentage'] = $post['cgst_percentage'];
						$save['cgst'] = $post['cgst'];
						$save['sgst_percentage'] = $post['sgst_percentage'];
						$save['sgst'] = $post['sgst'];
						$save['igst_percentage'] = $post['igst_percentage'];
						$save['igst'] = $post['igst'];

						$save['till_date_cgst'] = $post['cgst_till_date'];
						$save['till_date_igst'] = $post['igst_till_date'];
						$save['till_date_sgst'] = $post['sgst_till_date'];


						$save['sub_total'] = $post['sub_total'];
						$save['till_date_sub_total'] = $post['sub_total_till_date'];
						$save['net_amount'] = $post['net_amount'];
						$save['till_date_net_amount'] = $post['till_date_net_amount'];

						// $save['target_date'] = date("Y-m-d",strtotime($post['target_date']));
						$save['last_wo'] = 1;
						$save['remarks'] = $post['remarks'];
						$save['mail_check'] = $post['mail_check'];
						$save['created_date'] = date('Y-m-d H:i:s');
						$save['created_by'] = $this->request->session()->read('user_id');
						
						if($save['contract_type'] == 1 || $save['contract_type'] == 3 || $save['contract_type'] == 4)
						{
							$save['taxes_duties'] = isset($post['taxes_duties1'])?$post['taxes_duties1']:0;
							$save['guarantee'] = isset($post['guarantee_check1'])?$post['guarantee_check1']:0;
							$save['guarantee_time'] = $post['guarantee1'];
							$save['gstno'] = $post['gstno1'];
							$save['payment_days'] = $post['payment_days1'];
						}
						else
						{
							$save['taxes_duties'] = isset($post['taxes_duties2'])?$post['taxes_duties2']:0;
							$save['loading_transport'] = isset($post['loading_transport2'])?$post['loading_transport2']:0;
							$save['unloading'] = isset($post['unloading2'])?$post['unloading2']:0;
							$save['guarantee'] = isset($post['guarantee_check2'])?$post['unloading2']:0;
							$save['guarantee_time'] = $post['guarantee2'];
							$save['warrenty'] = isset($post['warranty_check2'])?$post['unloading2']:0;
							$save['warrenty_time'] = $post['warranty'];
							$save['gstno'] = $post['gstno2'];
							$save['payment_days'] = $post['payment_days2'];
						}
						$all_files = array();
						if(isset($_FILES["attach_file"]["name"]))
						{
							$file = $this->ERPfunction->upload_file_wo("attach_file");	
							if(!empty($file))
							foreach($file as $attachment_file)
							{
								$all_files[] = $attachment_file;
							}					
						}
						$save['attachment'] = json_encode($all_files);
						
						$entity_row = $wo_table->newEntity();
						$save_data = $wo_table->patchEntity($entity_row,$save);
						if($wo_table->save($save_data))
						{
							$wo_id = $save_data->wo_id;
							$this->ERPfunction->add_planning_work_order_detail($post['material'],$wo_id);
							
							$this->Flash->success(__('WO Created Successfully With WO NO '.$wo_no, null), 
							'default', 
							array('class' => 'success'));
							$this->redirect(array("controller" => "Contract","action" => "planningmenu"));
						}
					}else{
						$this->Flash->error(__("ERROR : Work Order exist already with project and party.", null), 'default',array('class' => 'success'));
						return $this->redirect($this->referer());
					}
				}else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
					// return $this->redirect(["action"=>"add-member"]);
				}
			}	
			else{
				$post = $this->request->data;
					// debug($post);die;
					// Check Work Order exist with same project and party_address
					$count = $wo_table->find()->where(['project_id'=>$post['project_id'],'party_userid'=>$post['party_id']])->count();
					if($count == 0)
					{
						$code = $this->ERPfunction->get_projectcode($post['project_id']);
						$new_wono = $this->ERPfunction->generate_auto_id($post['project_id'],"erp_planning_work_order","wo_id","wo_no");
						$new_wono = sprintf("%09d", $new_wono);
						$wo_no = $code.'/WO/'.$new_wono;
					
						$save['project_id'] = $post['project_id'];
						$save['bill_mode'] = $post['bill_mode'];
						$save['wo_no'] = $wo_no;
						$save['yashnand_gst_no'] = $post['yashnand_gstno'];
						$save['wo_date'] = $this->ERPfunction->set_date($post['wo_date']);
						$save['party_userid'] = $post['party_id'];
						$save['party_id'] = $post['party_identy'];
						$save['party_address'] = $post['party_address'];
						$save['party_no1'] = $post['party_no1'];
						$save['party_no2'] = $post['party_no2'];
						$save['party_email'] = $post['party_email'];
						$save['party_pan_no'] = $post['party_pan_no'];
						$save['party_gst_no'] = $post['party_gst_no'];
						$save['contract_type'] = $post['type_of_contract'];
						$save['work_type'] = $post['work_type'];
						$save['payment_method'] = $post['payment_method'];

						$save['cgst_percentage'] = $post['cgst_percentage'];
						$save['cgst'] = $post['cgst'];
						$save['sgst_percentage'] = $post['sgst_percentage'];
						$save['sgst'] = $post['sgst'];
						$save['igst_percentage'] = $post['igst_percentage'];
						$save['igst'] = $post['igst'];

						$save['till_date_cgst'] = $post['cgst_till_date'];
						$save['till_date_igst'] = $post['igst_till_date'];
						$save['till_date_sgst'] = $post['sgst_till_date'];


						$save['sub_total'] = $post['sub_total'];
						$save['till_date_sub_total'] = $post['sub_total_till_date'];
						$save['net_amount'] = $post['net_amount'];
						$save['till_date_net_amount'] = $post['till_date_net_amount'];

						// $save['target_date'] = date("Y-m-d",strtotime($post['target_date']));
						$save['last_wo'] = 1;
						$save['remarks'] = $post['remarks'];
						$save['mail_check'] = $post['mail_check'];
						$save['created_date'] = date('Y-m-d H:i:s');
						$save['created_by'] = $this->request->session()->read('user_id');
						
						if($save['contract_type'] == 1 || $save['contract_type'] == 3 || $save['contract_type'] == 4)
						{
							$save['taxes_duties'] = isset($post['taxes_duties1'])?$post['taxes_duties1']:0;
							$save['guarantee'] = isset($post['guarantee_check1'])?$post['guarantee_check1']:0;
							$save['guarantee_time'] = $post['guarantee1'];
							$save['gstno'] = $post['gstno1'];
							$save['payment_days'] = $post['payment_days1'];
						}
						else
						{
							$save['taxes_duties'] = isset($post['taxes_duties2'])?$post['taxes_duties2']:0;
							$save['loading_transport'] = isset($post['loading_transport2'])?$post['loading_transport2']:0;
							$save['unloading'] = isset($post['unloading2'])?$post['unloading2']:0;
							$save['guarantee'] = isset($post['guarantee_check2'])?$post['unloading2']:0;
							$save['guarantee_time'] = $post['guarantee2'];
							$save['warrenty'] = isset($post['warranty_check2'])?$post['unloading2']:0;
							$save['warrenty_time'] = $post['warranty'];
							$save['gstno'] = $post['gstno2'];
							$save['payment_days'] = $post['payment_days2'];
						}
						$all_files = array();
						if(isset($_FILES["attach_file"]["name"]))
						{
							$file = $this->ERPfunction->upload_file_wo("attach_file");	
							if(!empty($file))
							foreach($file as $attachment_file)
							{
								$all_files[] = $attachment_file;
							}					
						}
						$save['attachment'] = json_encode($all_files);
						
						$entity_row = $wo_table->newEntity();
						$save_data = $wo_table->patchEntity($entity_row,$save);
						if($wo_table->save($save_data))
						{
							$wo_id = $save_data->wo_id;
							$this->ERPfunction->add_planning_work_order_detail($post['material'],$wo_id);
							
							$this->Flash->success(__('WO Created Successfully With WO NO '.$wo_no, null), 
							'default', 
							array('class' => 'success'));
							$this->redirect(array("controller" => "Contract","action" => "planningmenu"));
						}
					}else{
						$this->Flash->error(__("ERROR : Work Order exist already with project and party.", null), 'default',array('class' => 'success'));
						return $this->redirect($this->referer());
					}
			}
		}
	}
	
	public function viewplanningworkhead($work_head_id)
	{
		$work_head_tbl = TableRegistry::get('erp_planning_work_head');
		$work_head_data = $work_head_tbl->get($work_head_id);
		$this->set('head_data',$work_head_data);
		$this->set('role',$this->role);
	}

	public function viewworkdescription($description_id)
	{
		$erp_category_master = TableRegistry::get('erp_category_master');
		$description_data = $erp_category_master->get($description_id);
		$this->set('description_data',$description_data);

		$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $erp_projects->find()->order(array(
			'project_id Desc'
		));
		$this->set('projects',$projects);

		$this->set('role',$this->role);
	}
	
	public function editplanningworkhead($work_head_id)
	{
		$work_head_tbl = TableRegistry::get('erp_planning_work_head');
		$work_head_data = $work_head_tbl->get($work_head_id);
		$this->set('head_data',$work_head_data);
		$this->set('role',$this->role);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			$save['work_head_code'] = $post['head_code'];
			$save['type_of_contract'] = $post['type_of_contract'];
			$save['work_head_title'] = $post['head_title'];
			
			$row = $work_head_tbl->get($work_head_id);
			$save_data = $work_head_tbl->patchEntity($row,$save);
			if($work_head_tbl->save($save_data))
			{
				$this->Flash->success(__('Record Update Successfully', null), 
				'default', 
				array('class' => 'success'));
				$this->redirect(array("controller" => "Contract","action" => "planningworkheadlist"));
			}
			
		}
	}

	public function editworkdescription($description_id) {
		$erp_category_master = TableRegistry::get('erp_category_master');
		$description_data = $erp_category_master->get($description_id);
		$this->set('description_data',$description_data);

		$erp_work_group = TableRegistry::get('erp_work_group'); 
		$work_group = $erp_work_group->find();
		$this->set("work_group",$work_group);

		$erp_work_sub_group = TableRegistry::get('erp_work_sub_group'); 
		$work_sub_group = $erp_work_sub_group->find();
		$this->set("work_sub_group",$work_sub_group);
		
		
		$erp_work_group = TableRegistry::get('erp_work_group'); 
		$work_group = $erp_work_group->find();
		$this->set("work_group",$work_group);

		$erp_work_sub_group = TableRegistry::get('erp_work_sub_group'); 
		$work_sub_group = $erp_work_sub_group->find();
		$this->set("work_sub_group",$work_sub_group);

		$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $erp_projects->find()->order(array(
			'project_id Desc'
		));
		$this->set('projects',$projects);

		if($this->request->is("post")) {
			$table_field = $erp_category_master->get($description_id);
			$enabled_project_data = $this->request->data["enabled_project_id"];
			$keys = array_keys($enabled_project_data,"disabled");				
			foreach ($keys as $k) {
				unset($enabled_project_data[$k]);
			}
			
			$selected_project_ids = json_encode(array_values($enabled_project_data));
			$this->request->data['project_id'] = $selected_project_ids;
			$update_data = $erp_category_master->patchEntity($table_field,$this->request->data);
			if($erp_category_master->save($update_data)) {
				$this->Flash->success(__('Record Update Successfully', null), 
				'default', 
				array('class' => 'success'));
				$this->redirect(array("controller" => "Contract","action" => "workdescription"));
			}
			
		}
	}

	public function deleteworkdescription($description_id)
	{
		$erp_category_master = TableRegistry::get('erp_category_master');
		$row = $erp_category_master->get($description_id);
		if($erp_category_master->delete($row))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 
				'default', 
			array('class' => 'success'));
			$this->redirect(array("controller" => "Contract","action" => "workdescription"));
			
		}
	}
	
	public function planningapprovewo()
	{
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'Purchase';
			$back_page = 'index';
		}
		
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$projects_ids = $this->Usermanage->users_project($this->user_id);

		$or = array();
		
		$or["erp_planning_work_order.project_id"] = (!empty($project_id) && $project_id != "All")?$project_id:NULL;
		// $or["erp_planning_work_order_detail.po_type"] = (!empty($po_type) && $po_type != "All")?$po_type:NULL;
		if($this->role =='deputymanagerelectric')
		{
			$material_ids = $this->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$or["erp_planning_work_order_detail.material_name IN"] = $material_ids;
		}
		if($or["erp_planning_work_order.project_id"] == NULL)
		{
			if($this->project_alloted($this->role)==1)
			{
				$or["erp_planning_work_order.project_id IN"] = $projects_ids;
			}
		}
		$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
		$or["erp_planning_work_order_detail.approved ="] = 0;
		// debug($or);die;
		$result = $wo_table->find()->select($wo_table);
		$result = $result->innerjoin(
			["erp_planning_work_order_detail"=>"erp_planning_work_order_detail"],
			["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
			->where($or)->select($wod_table)->hydrate(false)->toArray();
		// debug($result);die;
		
		$new_array = array();
		foreach($result as $retrive)
		{
			if(isset($new_array[$retrive['wo_no']]))
			{
				$new_array[$retrive['wo_no']]['erp_planning_work_order_detail'][] = $retrive['erp_planning_work_order_detail'];
			}else{
				$a = $retrive["erp_planning_work_order_detail"];
				unset($retrive["erp_planning_work_order_detail"]);
				$new_array[$retrive["wo_no"]] = $retrive;
				$new_array[$retrive["wo_no"]]['erp_planning_work_order_detail'][] = $a;
			}
			
		}
		// debug($new_array);die;
		$data = $new_array;
		$this->set("wo_date",$data);
		$this->set('role',$this->role);
		// $this->set('wo_date',$result);
	}
	
	public function previewplanningwo($wo_id)
	{
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id])->order(["contract_no"=>"asc"]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}
	
	public function printplanningworecord($wo_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id])->order(["contract_no"=>"asc"]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}

	public function mailplanningworecord($wo_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id])->order(["contract_no"=>"asc"]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}
	
	public function deleteplanningwo($wo_id)
	{
		$this->autoRander = false;
		$wod_tbl = TableRegistry::get('erp_planning_work_order_detail');
		
		$ok = $wod_tbl->deleteAll(["wo_id"=>$wo_id]);
		if($ok)
		{		
			$wo_tbl = TableRegistry::get('erp_planning_work_order');
			$row = $wo_tbl->get($wo_id);
			$wo_tbl->delete($row);
			
			$this->Flash->success(__('Record delete Successfully.'));
			return $this->redirect(['action'=>'planningapprovewo']);
		}
		return $this->redirect(['action'=>'approvewo']);
	}

	public function editplanningwo($wo_id)
	{
		ini_set('memory_limit', '-1');
		$this->set('woId',$wo_id);
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>0])->order(["contract_no"=>"asc"]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
		
		$erp_work_head = TableRegistry::get('erp_planning_work_head'); 
		$head_list = $erp_work_head->find();
		$this->set('work_type',$head_list);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);

		// Project Based Work Description Selection Code
		$woTableData = $wo_table->find()->select(['project_id'])->where(['wo_id' => $wo_id]);
		$description_options = '';
		foreach($woTableData as $retrived_data){
			$projectId = (String)$retrived_data['project_id'];
			$table_category=TableRegistry::get('erp_category_master');
			$descriptionValue = $table_category->find()->where(['type' => "subcontractbill_option"])->select(['cat_id',"category_title","project_id"])->hydrate(false)->toArray();
			foreach($descriptionValue as $data){
				$formattedProject = json_decode($data['project_id']);
				if($projectId != '' && $formattedProject != ''){
					if(in_array($projectId,$formattedProject)){
						$conn = ConnectionManager::get('default');
						$description_options = $conn->execute("SELECT cat_id,category_title FROM `erp_category_master` WHERE JSON_CONTAINS(`project_id`, '\"$projectId\"')")->fetchAll("assoc");
					}
				}	
			}
		}
		$this->set("descriptionOptions",$description_options);

		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == "deputymanagerelectric"){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		if($this->request->is("post")) {
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
				$post = $this->request->data;
				// debug($post);die;
				$save['project_id'] = $post['project_id'];
				$save['bill_mode'] = $post['bill_mode'];
				$save['yashnand_gst_no'] = $post['yashnand_gstno'];
				$save['wo_no'] = $post['wo_no'];
				$save['wo_date'] = $this->ERPfunction->set_date($post['wo_date']);
				$save['party_userid'] = $post['party_id'];
				$save['party_id'] = $post['party_identy'];
				$save['party_address'] = $post['party_address'];
				$save['party_no1'] = $post['party_no1'];
				$save['party_no2'] = $post['party_no2'];
				$save['party_email'] = $post['party_email'];
				$save['party_pan_no'] = $post['party_pan_no'];
				$save['party_gst_no'] = $post['party_gst_no'];
				$save['contract_type'] = $post['type_of_contract'];
				$save['payment_method'] = $post['payment_method'];
				$save['work_type'] = $post['work_type'];

				$save['cgst_percentage'] = $post['cgst_percentage'];
				$save['cgst'] = $post['cgst'];
				$save['sgst_percentage'] = $post['sgst_percentage'];
				$save['sgst'] = $post['sgst'];
				$save['igst_percentage'] = $post['igst_percentage'];
				$save['igst'] = $post['igst'];

				$save['till_date_cgst'] = $post['cgst_till_date'];
				$save['till_date_igst'] = $post['igst_till_date'];
				$save['till_date_sgst'] = $post['sgst_till_date'];

				$save['sub_total'] = $post['sub_total'];
				$save['till_date_sub_total'] = $post['sub_total_till_date'];
				$save['net_amount'] = $post['net_amount'];
				$save['till_date_net_amount'] = $post['till_date_net_amount'];

				// $save['target_date'] = date("Y-m-d",strtotime($post['target_date']));
				$save['remarks'] = $post['remarks'];
				$save['mail_check'] = $post['mail_check'];
				// $save['updated'] = 1;

				if($save['contract_type'] == 1 || $save['contract_type'] == 3 || $save['contract_type'] == 4)
				{
					$save['taxes_duties'] = isset($post['taxes_duties1'])?$post['taxes_duties1']:0;
					$save['loading_transport'] = 0;
					$save['unloading'] = 0;
					$save['guarantee'] = isset($post['guarantee_check1'])?$post['guarantee_check1']:0;
					$save['guarantee_time'] = isset($post['guarantee1'])?$post['guarantee1']:'';
					$save['warrenty'] = 0;
					$save['warrenty_time'] = '';
					$save['gstno'] = $post['gstno1'];
					$save['payment_days'] = $post['payment_days1'];
				}
				else
				{
					$save['taxes_duties'] = isset($post['taxes_duties2'])?$post['taxes_duties2']:0;
					$save['loading_transport'] = isset($post['loading_transport2'])?$post['loading_transport2']:0;
					$save['unloading'] = isset($post['unloading2'])?$post['unloading2']:0;
					$save['guarantee'] = isset($post['guarantee_check2'])?$post['guarantee_check2']:0;
					$save['guarantee_time'] = isset($post['guarantee2'])?$post['guarantee2']:'';
					$save['warrenty'] = isset($post['warranty_check2'])?$post['warranty_check2']:0;
					$save['warrenty_time'] = isset($post['warranty'])?$post['warranty']:'';
					$save['gstno'] = $post['gstno2'];
					$save['payment_days'] = $post['payment_days2'];
				}
				
				$old_files = array();
				if(isset($post["old_attach_file"]))
				{
					$old_files = $post["old_attach_file"];				
				}
				if(isset($_FILES["attach_file"]["name"]))
				{
					$file = $this->ERPfunction->upload_file_wo("attach_file");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$save['attachment'] = json_encode($old_files);
				
				$edit_row = $wo_table->get($wo_id);
				$save_data = $wo_table->patchEntity($edit_row,$save);
				if($wo_table->save($save_data))
				{
					$wo_id = $save_data->wo_id;
					$this->ERPfunction->edit_planning_work_order_detail($post['material'],$wo_id);
					
					$this->Flash->success(__('Data Update Successfully', null), 
					'default', 
					array('class' => 'success'));
					// echo "<script>window.close();</script>";
					$this->redirect(array("controller" => "Contract","action" => "planningapprovewo"));
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
			}
			}
			else{
				$post = $this->request->data;
				// debug($post);die;
				$save['project_id'] = $post['project_id'];
				$save['bill_mode'] = $post['bill_mode'];
				$save['yashnand_gst_no'] = $post['yashnand_gstno'];
				$save['wo_no'] = $post['wo_no'];
				$save['wo_date'] = $this->ERPfunction->set_date($post['wo_date']);
				$save['party_userid'] = $post['party_id'];
				$save['party_id'] = $post['party_identy'];
				$save['party_address'] = $post['party_address'];
				$save['party_no1'] = $post['party_no1'];
				$save['party_no2'] = $post['party_no2'];
				$save['party_email'] = $post['party_email'];
				$save['party_pan_no'] = $post['party_pan_no'];
				$save['party_gst_no'] = $post['party_gst_no'];
				$save['contract_type'] = $post['type_of_contract'];
				$save['payment_method'] = $post['payment_method'];
				$save['work_type'] = $post['work_type'];

				$save['cgst_percentage'] = $post['cgst_percentage'];
				$save['cgst'] = $post['cgst'];
				$save['sgst_percentage'] = $post['sgst_percentage'];
				$save['sgst'] = $post['sgst'];
				$save['igst_percentage'] = $post['igst_percentage'];
				$save['igst'] = $post['igst'];

				$save['till_date_cgst'] = $post['cgst_till_date'];
				$save['till_date_igst'] = $post['igst_till_date'];
				$save['till_date_sgst'] = $post['sgst_till_date'];

				$save['sub_total'] = $post['sub_total'];
				$save['till_date_sub_total'] = $post['sub_total_till_date'];
				$save['net_amount'] = $post['net_amount'];
				$save['till_date_net_amount'] = $post['till_date_net_amount'];

				// $save['target_date'] = date("Y-m-d",strtotime($post['target_date']));
				$save['remarks'] = $post['remarks'];
				$save['mail_check'] = $post['mail_check'];
				// $save['updated'] = 1;

				if($save['contract_type'] == 1 || $save['contract_type'] == 3 || $save['contract_type'] == 4)
				{
					$save['taxes_duties'] = isset($post['taxes_duties1'])?$post['taxes_duties1']:0;
					$save['loading_transport'] = 0;
					$save['unloading'] = 0;
					$save['guarantee'] = isset($post['guarantee_check1'])?$post['guarantee_check1']:0;
					$save['guarantee_time'] = isset($post['guarantee1'])?$post['guarantee1']:'';
					$save['warrenty'] = 0;
					$save['warrenty_time'] = '';
					$save['gstno'] = $post['gstno1'];
					$save['payment_days'] = $post['payment_days1'];
				}
				else
				{
					$save['taxes_duties'] = isset($post['taxes_duties2'])?$post['taxes_duties2']:0;
					$save['loading_transport'] = isset($post['loading_transport2'])?$post['loading_transport2']:0;
					$save['unloading'] = isset($post['unloading2'])?$post['unloading2']:0;
					$save['guarantee'] = isset($post['guarantee_check2'])?$post['guarantee_check2']:0;
					$save['guarantee_time'] = isset($post['guarantee2'])?$post['guarantee2']:'';
					$save['warrenty'] = isset($post['warranty_check2'])?$post['warranty_check2']:0;
					$save['warrenty_time'] = isset($post['warranty'])?$post['warranty']:'';
					$save['gstno'] = $post['gstno2'];
					$save['payment_days'] = $post['payment_days2'];
				}
				
				$old_files = array();
				if(isset($post["old_attach_file"]))
				{
					$old_files = $post["old_attach_file"];				
				}
				if(isset($_FILES["attach_file"]["name"]))
				{
					$file = $this->ERPfunction->upload_file_wo("attach_file");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$save['attachment'] = json_encode($old_files);
				
				$edit_row = $wo_table->get($wo_id);
				$save_data = $wo_table->patchEntity($edit_row,$save);
				if($wo_table->save($save_data))
				{
					$wo_id = $save_data->wo_id;
					$this->ERPfunction->edit_planning_work_order_detail($post['material'],$wo_id);
					
					$this->Flash->success(__('Data Update Successfully', null), 
					'default', 
					array('class' => 'success'));
					// echo "<script>window.close();</script>";
					$this->redirect(array("controller" => "Contract","action" => "planningapprovewo"));
				}
			}
			
		}
	}

	public function editplanningwonew($wo_id)
	{
		$this->set('wo_id',$wo_id);
		ini_set('memory_limit', '-1');
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>0])->order(["contract_no"=>"asc"]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
		
		$erp_work_head = TableRegistry::get('erp_planning_work_head'); 
		$head_list = $erp_work_head->find()->where(["project_id"=>$wo_data->project_id]);
		$this->set('work_type',$head_list);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$this->set('agency_list',$agency_list);
		
		$table_category=TableRegistry::get('erp_category_master');
		$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option','project_id'=>$wo_data->project_id))->select(["cat_id","category_title"]);
		$this->set('description_options',$description_options);

		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == "deputymanagerelectric"){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			// debug($post);die;
			$save['project_id'] = $post['project_id'];
			$save['bill_mode'] = $post['bill_mode'];
			$save['yashnand_gst_no'] = $post['yashnand_gstno'];
			$save['wo_no'] = $post['wo_no'];
			$save['wo_date'] = $this->ERPfunction->set_date($post['wo_date']);
			$save['party_userid'] = $post['party_id'];
			$save['party_id'] = $post['party_identy'];
			$save['party_address'] = $post['party_address'];
			$save['party_no1'] = $post['party_no1'];
			$save['party_no2'] = $post['party_no2'];
			$save['party_email'] = $post['party_email'];
			$save['party_pan_no'] = $post['party_pan_no'];
			$save['party_gst_no'] = $post['party_gst_no'];
			$save['contract_type'] = $post['type_of_contract'];
			$save['payment_method'] = $post['payment_method'];
			$save['work_type'] = $post['work_type'];

			$save['cgst_percentage'] = $post['cgst_percentage'];
			$save['cgst'] = $post['cgst'];
			$save['sgst_percentage'] = $post['sgst_percentage'];
			$save['sgst'] = $post['sgst'];
			$save['igst_percentage'] = $post['igst_percentage'];
			$save['igst'] = $post['igst'];

			$save['till_date_cgst'] = $post['cgst_till_date'];
			$save['till_date_igst'] = $post['igst_till_date'];
			$save['till_date_sgst'] = $post['sgst_till_date'];

			$save['sub_total'] = $post['sub_total'];
			$save['till_date_sub_total'] = $post['sub_total_till_date'];
			$save['net_amount'] = $post['net_amount'];
			$save['till_date_net_amount'] = $post['till_date_net_amount'];

			// $save['target_date'] = date("Y-m-d",strtotime($post['target_date']));
			$save['remarks'] = $post['remarks'];
			$save['mail_check'] = $post['mail_check'];
			
			if($save['contract_type'] == 1 || $save['contract_type'] == 3 || $save['contract_type'] == 4)
			{
				$save['taxes_duties'] = isset($post['taxes_duties1'])?$post['taxes_duties1']:0;
				$save['loading_transport'] = 0;
				$save['unloading'] = 0;
				$save['guarantee'] = isset($post['guarantee_check1'])?$post['guarantee_check1']:0;
				$save['guarantee_time'] = isset($post['guarantee1'])?$post['guarantee1']:'';
				$save['warrenty'] = 0;
				$save['warrenty_time'] = '';
				$save['gstno'] = $post['gstno1'];
				$save['payment_days'] = $post['payment_days1'];
			}
			else
			{
				$save['taxes_duties'] = isset($post['taxes_duties2'])?$post['taxes_duties2']:0;
				$save['loading_transport'] = isset($post['loading_transport2'])?$post['loading_transport2']:0;
				$save['unloading'] = isset($post['unloading2'])?$post['unloading2']:0;
				$save['guarantee'] = isset($post['guarantee_check2'])?$post['guarantee_check2']:0;
				$save['guarantee_time'] = isset($post['guarantee2'])?$post['guarantee2']:'';
				$save['warrenty'] = isset($post['warranty_check2'])?$post['warranty_check2']:0;
				$save['warrenty_time'] = isset($post['warranty'])?$post['warranty']:'';
				$save['gstno'] = $post['gstno2'];
				$save['payment_days'] = $post['payment_days2'];
			}
			
			$old_files = array();
			if(isset($post["old_attach_file"]))
			{
				$old_files = $post["old_attach_file"];				
			}
			if(isset($_FILES["attach_file"]["name"]))
			{
				$file = $this->ERPfunction->upload_file_wo("attach_file");	
				if(!empty($file))
				foreach($file as $attachment_file)
				{
					$old_files[] = $attachment_file;
				}					
			}
			$save['attachment'] = json_encode($old_files);
			
			$edit_row = $wo_table->get($wo_id);
			$save_data = $wo_table->patchEntity($edit_row,$save);
			if($wo_table->save($save_data))
			{
				$wo_id = $save_data->wo_id;
				$this->ERPfunction->edit_planning_work_order_detail($post['material'],$wo_id);
				
				$this->Flash->success(__('Data Update Successfully'));
				// 'default', 
				// array('class' => 'success'));
				// echo "<script>window.close();</script>";
				$this->redirect(array("controller" => "Contract","action" => "planningapprovewo"));
			}
			
		}
	}

	public function ammendworkorder($wo_id)
	{
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>1])->order(['contract_no'=>'asc']);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
		
		$erp_work_head = TableRegistry::get('erp_planning_work_head'); 
		// $head_list = $erp_work_head->find()->where(["project_id"=>$wo_data->project_id]);
		$head_list = $erp_work_head->find();
		$this->set('work_type',$head_list);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		// Project Based Work Description Selection Code
		$woTableData = $wo_table->find()->select(['project_id'])->where(['wo_id' => $wo_id]);
		$description_options = '';
		foreach($woTableData as $retrived_data){
			$projectId = (String)$retrived_data['project_id'];
			$table_category=TableRegistry::get('erp_category_master');
			$descriptionValue = $table_category->find()->where(['type' => "subcontractbill_option"])->select(['cat_id',"category_title","project_id"])->hydrate(false)->toArray();
			foreach($descriptionValue as $data){
				$formattedProject = json_decode($data['project_id']);
				if($projectId != '' && $formattedProject != ''){
					if(in_array($projectId,$formattedProject)){
						$conn = ConnectionManager::get('default');
						$description_options = $conn->execute("SELECT cat_id,category_title FROM `erp_category_master` WHERE JSON_CONTAINS(`project_id`, '\"$projectId\"')")->fetchAll("assoc");
					}
				}	
			}
		}
		$this->set("descriptionOptions",$description_options);

		$can_update = 0;
		if($wo_data->updated == 1 && $wo_data->ammend_approve == 0)
		{
			$can_update = 1;
		}else{
			$can_update = 0;
		}
		$this->set('can_update',$can_update);
		if($can_update)
		{
			$wo_no = $wo_data->wo_no;
		}else{
			$old_wo = $wo_data->wo_no;
			$split = explode('/',$old_wo);
			$wo_no = $split[0].'/'.$split[1].'/'.$split[2].'/'.$split[3].'/'.$split[4].'/'.'Rev1'.'/'.date("d-m-Y");
		}
		$this->set('wo_no',$wo_no);
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == "deputymanagerelectric"){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		$this->set('projects',$projects);
		
		if($this->request->is("post")) {
			$file =$_FILES['attach_file']["name"];
			$size = count($file);
			for($i=0;$i<$size;$i++) {
				$parts = pathinfo($_FILES['attach_file']['name'][$i]);
			}
			$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
			// debug($ext);die;
			if($ext != 0) {

				$post = $this->request->data;
				if($wo_data->updated == 0)
				{
					$related_wo_id[] = $wo_id;
					if($wo_data->related_wo_id != '' && $wo_data->related_wo_id != null)
					{
						$old_related = explode(",",$wo_data->related_wo_id);
						$related_wo_id = array_merge($old_related,$related_wo_id);
					}
					$save['child_wo_id'] = $wo_id;
					$save['related_wo_id'] = implode(",",$related_wo_id);
				}
				$save['project_id'] = $post['project_id'];
				$save['bill_mode'] = $post['bill_mode'];
				$save['yashnand_gst_no'] = $post['yashnand_gstno'];
				$save['wo_no'] = $post['wo_no'];
				$save['wo_date'] = $this->ERPfunction->set_date($post['wo_date']);
				$save['party_userid'] = $post['party_id'];
				$save['party_id'] = $post['party_identy'];
				$save['party_address'] = $post['party_address'];
				$save['party_no1'] = $post['party_no1'];
				$save['party_no2'] = $post['party_no2'];
				$save['party_email'] = $post['party_email'];
				$save['party_pan_no'] = $post['party_pan_no'];
				$save['party_gst_no'] = $post['party_gst_no'];
				$save['contract_type'] = $post['type_of_contract'];
				$save['payment_method'] = $post['payment_method'];
				$save['work_type'] = $post['work_type'];

				$save['cgst_percentage'] = $post['cgst_percentage'];
				$save['cgst'] = $post['cgst'];
				$save['sgst_percentage'] = $post['sgst_percentage'];
				$save['sgst'] = $post['sgst'];
				$save['igst_percentage'] = $post['igst_percentage'];
				$save['igst'] = $post['igst'];

				$save['till_date_cgst'] = $post['cgst_till_date'];
				$save['till_date_igst'] = $post['igst_till_date'];
				$save['till_date_sgst'] = $post['sgst_till_date'];

				$save['sub_total'] = $post['sub_total'];
				$save['till_date_sub_total'] = $post['sub_total_till_date'];
				$save['net_amount'] = $post['net_amount'];
				$save['till_date_net_amount'] = $post['till_date_net_amount'];
				$save['last_wo'] = 1;
				$save['updated'] = 1;
				// $save['target_date'] = date("Y-m-d",strtotime($post['target_date']));
				
				$save['created_date'] = date('Y-m-d H:i:s');
				$save['created_by'] = $this->request->session()->read('user_id');
				$save['remarks'] = $post['remarks'];
				$save['mail_check'] = $post['mail_check'];
				
				if($save['contract_type'] == 1 || $save['contract_type'] == 3 || $save['contract_type'] == 4)
				{
					$save['taxes_duties'] = isset($post['taxes_duties1'])?$post['taxes_duties1']:0;
					$save['loading_transport'] = 0;
					$save['unloading'] = 0;
					$save['guarantee'] = isset($post['guarantee_check1'])?$post['guarantee_check1']:0;
					$save['guarantee_time'] = isset($post['guarantee1'])?$post['guarantee1']:'';
					$save['warrenty'] = 0;
					$save['warrenty_time'] = '';
					$save['gstno'] = $post['gstno1'];
					$save['payment_days'] = $post['payment_days1'];
				}
				else
				{
					$save['taxes_duties'] = isset($post['taxes_duties2'])?$post['taxes_duties2']:0;
					$save['loading_transport'] = isset($post['loading_transport2'])?$post['loading_transport2']:0;
					$save['unloading'] = isset($post['unloading2'])?$post['unloading2']:0;
					$save['guarantee'] = isset($post['guarantee_check2'])?$post['guarantee_check2']:0;
					$save['guarantee_time'] = isset($post['guarantee2'])?$post['guarantee2']:'';
					$save['warrenty'] = isset($post['warranty_check2'])?$post['warranty_check2']:0;
					$save['warrenty_time'] = isset($post['warranty'])?$post['warranty']:'';
					$save['gstno'] = $post['gstno2'];
					$save['payment_days'] = $post['payment_days2'];
				}
				
				$old_files = array();
				if(isset($post["old_attach_file"]))
				{
					$old_files = $post["old_attach_file"];				
				}
				if(isset($_FILES["attach_file"]["name"]))
				{
					$file = $this->ERPfunction->upload_file_wo("attach_file");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$save['attachment'] = json_encode($old_files);
				
				if($wo_data->updated == 1 && $wo_data->ammend_approve == 0)
				{
					$ammend_row = $wo_table->get($wo_id);
				}else{
					$ammend_row = $wo_table->newEntity();
				}
				
				$save_data = $wo_table->patchEntity($ammend_row,$save);
				if($wo_table->save($save_data))
				{
					$wo_id = $save_data->wo_id;
					$this->ERPfunction->add_ammend_work_order_details($post['material'],$wo_id,$wo_data);
					
					// Update old record
					if(!$can_update)
					{
						$wo_data->last_wo = 0;
						$wo_table->save($wo_data);
					}
					$this->Flash->success(__('Data Update Successfully', null), 
					'default', 
					array('class' => 'success'));
					// echo "<script>window.close();</script>";
					$this->redirect(array("controller" => "Contract","action" => "planningworecords"));
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
			}	
			
		}
	}

	public function planningammendapprovewo($projects_id=null,$from=null,$to=null) {
		$back_url = 'contract';
		$back_page = 'planningmenu';
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$role = $this->role;
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		
		$erp_work_head = TableRegistry::get('erp_work_head'); 
		$head_list = $erp_work_head->find();
		$this->set('work_head_list',$head_list);
		
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		
		if($projects_id!=null) {
			$or = array();
			$or["erp_planning_work_order_detail.approved_date >="] = date('Y-m-d',strtotime($from));
			$or["erp_planning_work_order_detail.approved_date <="] = date('Y-m-d',strtotime($to));
			$or["project_id"] = $projects_id;
			$keys = array_keys($or,"");				
			foreach ($keys as $k) {
				unset($or[$k]);
			}
					
			$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
			$result = $result->innerjoin(
				["erp_planning_work_order"=>"erp_planning_work_order"],
				["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])->where($or)
				->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
		}else {
			if($this->role == "deputymanagerelectric") {
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1,"project_id IN"=>$projects_ids]);
				$result = $result->innerjoin(
				["erp_planning_work_order"=>"erp_planning_work_order"],
				["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
				->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
			}else {
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
				$result = $result->innerjoin(
				["erp_planning_work_order"=>"erp_planning_work_order"],
				["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
				->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
			}
		}	
		$this->set('role',$this->role);
		$this->set('wo_date',$result);
		if($this->request->is('post')) {	
			if(isset($this->request->data["go1"])) {
				$erp_planning_work_order = TableRegistry::get("erp_planning_work_order");
				$erp_planning_work_order_detail = TableRegistry::get("erp_planning_work_order_detail");
				$post = $this->request->data;	
				$or = array();
				$or["wo_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["wo_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["party_userid IN"] = (!empty($post["party_userid"]) && $post["party_userid"][0] != "All")?$post["party_userid"]:NULL;
				$or["contract_type IN"] = (!empty($post["type_of_contract"]) && $post["type_of_contract"][0] != "All")?$post["type_of_contract"]:NULL;
				$or["payment_method"] = (!empty($post["payment_method"]) && $post["payment_method"][0] != "All")?$post["payment_method"]:NULL;
				$or["wo_no"] = (!empty($post["wo_no"]))?$post["wo_no"]:NULL;
				
				if($role == "deputymanagerelectric") {
					if($or["project_id IN"] == NULL) {
						$or["project_id IN"] = $projects_ids;
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k) {
					unset($or[$k]);
				} 
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
				$result = $result->innerjoin(
					["erp_planning_work_order"=>"erp_planning_work_order"],
					["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
				
				$this->set('wo_date',$result);
			}
			
			if(isset($this->request->data["export_csv"])) {
				$erp_planning_work_order = TableRegistry::get("erp_planning_work_order");
				$erp_planning_work_order_detail = TableRegistry::get("erp_planning_work_order_detail");
				
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_party_userid"] = (!empty($post["e_party_userid"]))?explode(",",$post["e_party_userid"]):NULL;
				$post["e_contract_type"] = (!empty($post["e_contract_type"]))?explode(",",$post["e_contract_type"]):NULL;
				
				$or["erp_planning_work_order.wo_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_planning_work_order.wo_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_planning_work_order.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_planning_work_order.party_userid IN"] = (!empty($post["e_party_userid"]) && $post["e_party_userid"] != "All")?$post["e_party_userid"]:NULL;
				$or["erp_planning_work_order.contract_type IN"] = (!empty($post["e_contract_type"]) && $post["e_contract_type"] != "All")?$post["e_contract_type"]:NULL;
				
				$or["erp_planning_work_order.wo_no ="] = (!empty($post["e_wo_no"]))?$post["e_wo_no"]:NULL;
				$or["erp_planning_work_order.payment_method ="] = (!empty($post["e_payment_method"]) && $post["e_payment_method"] != "All")?$post["e_payment_method"]:NULL;
				
				if($or["erp_planning_work_order.project_id IN"] == NULL) {
					if($this->Usermanage->project_alloted($role)==1) {
						$or["erp_planning_work_order.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k) {
					unset($or[$k]);
				}
				
				
				$or["erp_planning_work_order_detail.approved !="] = 0;
				$query = $wod_table->find();
				$result = $query->select(['amount' => $query->func()->sum('erp_planning_work_order_detail.amount')])->GROUP(["erp_planning_work_order_detail.wo_id"]);
				$result = $result->innerjoin(
					["erp_planning_work_order"=>"erp_planning_work_order"],
					["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
					
				$rows = array();
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract","Amount");
				
				foreach($result as $retrive_data) {
					if(isset($retrive_data["erp_planning_work_order"])) {
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_planning_work_order"]);
					}
					$is_agency = strpos($retrive_data['party_userid'],"NEC");
					if($is_agency == 1) {
						$partyname = $this->ERPfunction->get_agency_name_by_code($retrive_data['party_userid']);
					}else {
						$partyname = $this->ERPfunction->get_vendor_name($retrive_data['party_userid']);
					}
					
					$csv = array();
					$csv[] = date("d-m-Y",strtotime($retrive_data['wo_date']));
					$csv[] = $retrive_data['wo_no'];
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $partyname;
					$csv[] = $this->ERPfunction->get_contract_title($retrive_data['contract_type']);
					$csv[] = $retrive_data['amount'];
					$rows[] = $csv;
				}
				
				$filename = "wo_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"])) {			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$erp_planning_work_order = TableRegistry::get("erp_planning_work_order");
				$erp_planning_work_order_detail = TableRegistry::get("erp_planning_work_order_detail");
				
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_party_userid"] = (!empty($post["e_party_userid"]))?explode(",",$post["e_party_userid"]):NULL;
				$post["e_contract_type"] = (!empty($post["e_contract_type"]))?explode(",",$post["e_contract_type"]):NULL;
				
				$or["erp_planning_work_order.wo_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_planning_work_order.wo_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_planning_work_order.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_planning_work_order.party_userid IN"] = (!empty($post["e_party_userid"]) && $post["e_party_userid"] != "All")?$post["e_party_userid"]:NULL;
				$or["erp_planning_work_order.contract_type IN"] = (!empty($post["e_contract_type"]) && $post["e_contract_type"] != "All")?$post["e_contract_type"]:NULL;
				
				$or["erp_planning_work_order.wo_no ="] = (!empty($post["e_wo_no"]))?$post["e_wo_no"]:NULL;
				$or["erp_planning_work_order.payment_method ="] = (!empty($post["e_payment_method"]) && $post["e_payment_method"] != "All")?$post["e_payment_method"]:NULL;
				
				if($or["erp_planning_work_order.project_id IN"] == NULL) {
					if($this->Usermanage->project_alloted($role)==1) {
						$or["erp_planning_work_order.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k) {
					unset($or[$k]);
				}

				$or["erp_planning_work_order_detail.approved !="] = 0;
				
				$query = $wod_table->find();
				$result = $query->select(['amount' => $query->func()->sum('erp_planning_work_order_detail.amount')])->GROUP(["erp_planning_work_order_detail.wo_id"]);
				$result = $result->innerjoin(
					["erp_planning_work_order"=>"erp_planning_work_order"],
					["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
					
				$rows = array();
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract","Amount");
				
				foreach($result as $retrive_data) {
					if(isset($retrive_data["erp_planning_work_order"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_planning_work_order"]);
					}
					
					$is_agency = strpos($retrive_data['party_userid'],"NEC");
					if($is_agency == 1)
					{
						$partyname = $this->ERPfunction->get_agency_name_by_code($retrive_data['party_userid']);
					}else{
						$partyname = $this->ERPfunction->get_vendor_name($retrive_data['party_userid']);
					}
					
					$csv = array();
					$csv[] = date("d-m-Y",strtotime($retrive_data['wo_date']));
					$csv[] = $retrive_data['wo_no'];
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $partyname;
					$csv[] = $this->ERPfunction->get_contract_title($retrive_data['contract_type']);
					$csv[] = $retrive_data['amount'];
					$rows[] = $csv;
				}
				
				$this->set("rows",$rows);
				$this->render("worecordspdf");
			}
		}
	}

	public function planningworecords($projects_id=null,$from=null,$to=null)
	{
		// $previous_url= $this->referer();
		// if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		// }elseif (strpos($previous_url, 'contract') !== false) {
		// 	$back_url = 'contract';
		// 	$back_page = 'billingmenu';
		// }else{
		// 	$back_url = 'Purchase';
		// 	$back_page = 'index';
		// }
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$role = $this->role;
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		// if($role == "erpoperator")
		// {
		// 	$projects = $this->Usermanage->all_access_project($this->user_id);
		// }elseif($role == "deputymanagerelectric"){
		// 	$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		// }else{
		// 	$projects = $this->Usermanage->access_project($this->user_id);
		// }
		// $this->set('projects',$projects);
		
		$erp_work_head = TableRegistry::get('erp_work_head'); 
		$head_list = $erp_work_head->find();
		$this->set('work_head_list',$head_list);
		
		// $agency_tbl = TableRegistry::get("erp_agency");
		// $agency_list = $agency_tbl->find("All")->toArray();
		// $this->set('agency_list',$agency_list);
		
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		
		if($projects_id!=null){
			
			$or = array();				
					
					$or["erp_planning_work_order_detail.approved_date >="] = date('Y-m-d',strtotime($from));
					$or["erp_planning_work_order_detail.approved_date <="] = date('Y-m-d',strtotime($to));
					$or["project_id"] = $projects_id;
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
					
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
				$result = $result->innerjoin(
				["erp_planning_work_order"=>"erp_planning_work_order"],
				["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])->where($or)
				->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
		}
		
		else{
			
			if($this->role == "deputymanagerelectric")
			{
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1,"project_id IN"=>$projects_ids]);
				$result = $result->innerjoin(
				["erp_planning_work_order"=>"erp_planning_work_order"],
				["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
				->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
			}else{
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
				$result = $result->innerjoin(
				["erp_planning_work_order"=>"erp_planning_work_order"],
				["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
				->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
			}
		
		}	
		$this->set('role',$this->role);
		$this->set('wo_date',$result);
		// var_dump('hello');die;
		if($this->request->is('post'))
		{	
			if(isset($this->request->data["go1"]))
			{
				$erp_planning_work_order = TableRegistry::get("erp_planning_work_order");
				$erp_planning_work_order_detail = TableRegistry::get("erp_planning_work_order_detail");
				$post = $this->request->data;	
				$or = array();				
				
				$or["wo_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["wo_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["party_userid IN"] = (!empty($post["party_userid"]) && $post["party_userid"][0] != "All")?$post["party_userid"]:NULL;
				$or["contract_type IN"] = (!empty($post["type_of_contract"]) && $post["type_of_contract"][0] != "All")?$post["type_of_contract"]:NULL;
				$or["payment_method"] = (!empty($post["payment_method"]) && $post["payment_method"][0] != "All")?$post["payment_method"]:NULL;
				$or["wo_no"] = (!empty($post["wo_no"]))?$post["wo_no"]:NULL;
				
				if($role == "deputymanagerelectric")
				{
					if($or["project_id IN"] == NULL)
					{
						$or["project_id IN"] = $projects_ids;
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				 
				$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
				$result = $result->innerjoin(
					["erp_planning_work_order"=>"erp_planning_work_order"],
					["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
				
				$this->set('wo_date',$result);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				// debug($this->request->data);die;
				$erp_planning_work_order = TableRegistry::get("erp_planning_work_order");
				$erp_planning_work_order_detail = TableRegistry::get("erp_planning_work_order_detail");
				
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_party_userid"] = (!empty($post["e_party_userid"]))?explode(",",$post["e_party_userid"]):NULL;
				$post["e_contract_type"] = (!empty($post["e_contract_type"]))?explode(",",$post["e_contract_type"]):NULL;
				
				$or["erp_planning_work_order.wo_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_planning_work_order.wo_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_planning_work_order.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_planning_work_order.party_userid IN"] = (!empty($post["e_party_userid"]) && $post["e_party_userid"] != "All")?$post["e_party_userid"]:NULL;
				$or["erp_planning_work_order.contract_type IN"] = (!empty($post["e_contract_type"]) && $post["e_contract_type"] != "All")?$post["e_contract_type"]:NULL;
				
				$or["erp_planning_work_order.wo_no ="] = (!empty($post["e_wo_no"]))?$post["e_wo_no"]:NULL;
				$or["erp_planning_work_order.payment_method ="] = (!empty($post["e_payment_method"]) && $post["e_payment_method"] != "All")?$post["e_payment_method"]:NULL;
				
				if($or["erp_planning_work_order.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_planning_work_order.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				
				$or["erp_planning_work_order_detail.approved !="] = 0;
				
				// $result = $erp_planning_work_order->find()->select($erp_planning_work_order);
				// $result = $result->innerjoin(
					// ["erp_planning_work_order_detail"=>"erp_planning_work_order_detail"],
					// ["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
					// ->where($or)->select("sum(erp_planning_work_order_detail.amount)")->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
				
				$query = $wod_table->find();
				$result = $query->select(['amount' => $query->func()->sum('erp_planning_work_order_detail.amount')])->GROUP(["erp_planning_work_order_detail.wo_id"]);
				$result = $result->innerjoin(
					["erp_planning_work_order"=>"erp_planning_work_order"],
					["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
					
				$rows = array();
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract","Amount");
				
				foreach($result as $retrive_data)
				{		
					
					if(isset($retrive_data["erp_planning_work_order"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_planning_work_order"]);
					}
					
					$is_agency = strpos($retrive_data['party_userid'],"NEC");
					if($is_agency == 1)
					{
						$partyname = $this->ERPfunction->get_agency_name_by_code($retrive_data['party_userid']);
					}else{
						$partyname = $this->ERPfunction->get_vendor_name($retrive_data['party_userid']);
					}
					
					$csv = array();
					$csv[] = date("d-m-Y",strtotime($retrive_data['wo_date']));
					$csv[] = $retrive_data['wo_no'];
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $partyname;
					$csv[] = $this->ERPfunction->get_contract_title($retrive_data['contract_type']);
					$csv[] = $retrive_data['amount'];
					$rows[] = $csv;
				}
				
				$filename = "wo_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				// debug($this->request->data);die;
				$erp_planning_work_order = TableRegistry::get("erp_planning_work_order");
				$erp_planning_work_order_detail = TableRegistry::get("erp_planning_work_order_detail");
				
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_party_userid"] = (!empty($post["e_party_userid"]))?explode(",",$post["e_party_userid"]):NULL;
				$post["e_contract_type"] = (!empty($post["e_contract_type"]))?explode(",",$post["e_contract_type"]):NULL;
				
				$or["erp_planning_work_order.wo_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_planning_work_order.wo_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_planning_work_order.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_planning_work_order.party_userid IN"] = (!empty($post["e_party_userid"]) && $post["e_party_userid"] != "All")?$post["e_party_userid"]:NULL;
				$or["erp_planning_work_order.contract_type IN"] = (!empty($post["e_contract_type"]) && $post["e_contract_type"] != "All")?$post["e_contract_type"]:NULL;
				
				$or["erp_planning_work_order.wo_no ="] = (!empty($post["e_wo_no"]))?$post["e_wo_no"]:NULL;
				$or["erp_planning_work_order.payment_method ="] = (!empty($post["e_payment_method"]) && $post["e_payment_method"] != "All")?$post["e_payment_method"]:NULL;
				
				if($or["erp_planning_work_order.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_planning_work_order.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				
				$or["erp_planning_work_order_detail.approved !="] = 0;
				
				$query = $wod_table->find();
				$result = $query->select(['amount' => $query->func()->sum('erp_planning_work_order_detail.amount')])->GROUP(["erp_planning_work_order_detail.wo_id"]);
				$result = $result->innerjoin(
					["erp_planning_work_order"=>"erp_planning_work_order"],
					["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
					
				$rows = array();
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract","Amount");
				
				foreach($result as $retrive_data)
				{		
					
					if(isset($retrive_data["erp_planning_work_order"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_planning_work_order"]);
					}
					
					$is_agency = strpos($retrive_data['party_userid'],"NEC");
					if($is_agency == 1)
					{
						$partyname = $this->ERPfunction->get_agency_name_by_code($retrive_data['party_userid']);
					}else{
						$partyname = $this->ERPfunction->get_vendor_name($retrive_data['party_userid']);
					}
					
					$csv = array();
					$csv[] = date("d-m-Y",strtotime($retrive_data['wo_date']));
					$csv[] = $retrive_data['wo_no'];
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $partyname;
					$csv[] = $this->ERPfunction->get_contract_title($retrive_data['contract_type']);
					$csv[] = $retrive_data['amount'];
					$rows[] = $csv;
				}
				
				$this->set("rows",$rows);
				$this->render("worecordspdf");
			}
		}
	}

	public function project_alloted($role){
		$alloted=1;		
		$findvalue=array();		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		
		$find=$erp_accessrights_tbl->find()->where(['role'=>$role])->first();
		if(!empty($find))
		{
			$alloted=$find->Alloted;
		}
		return $alloted;
	}

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
	
}
