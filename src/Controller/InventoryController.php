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
use Cake\I18n\Time;
use Cake\Routing\Router;
use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Dompdf\Exception;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class InventoryController extends AppController
{

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
	public $user_id;
	public $rights;
	public $role;
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');
		$this->loadComponent('ERPfunction');
		$this->user_id=$this->request->session()->read('user_id');
		$this->rights = $this->Usermanage->inventory_access_right();
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$action = $this->request->action;
		if(isset($this->rights[$action][$this->role]) && $action != "printporecord" && $action != "printapprovedporecord" && $action != "printporecordnorate" && $action != "printporecord2" && $action != "mailporecord2" && $action != "printporecordnorate2")
		{			
			$is_capable = $this->rights[$action][$this->role];				
		}
		else
		{	$is_capable = 0;	}
		
		$this->set('is_capable',$is_capable);
		$this->set('role',$this->role);
		
	}
    public function index()
    {
		
    }
	
	public function ceolist()
	{
		$users_table = TableRegistry::get('erp_users'); 
		$user_list = $users_table->find()->where(array('role'=>'inventorystaff'));
		$this->set('user_list',$user_list);
	}
	
	public function preparepr()
    {
		$previous_url= $this->referer();
		if (strpos($previous_url, 'contract') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'Inventory';
			$back_page = 'index';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		$users_table = TableRegistry::get('erp_users');
		$purchase_department = $users_table->find()->where(array('role'=>'purchasehead'));
		$this->set('purchase_department',$purchase_department);
		$raise_from = $this->ERPfunction->get_mm_constructionmanager();		
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);	

		$this->set('projects',$projects);
		

		$this->set('raise_from',$raise_from);
		
		$erp_material = TableRegistry::get('erp_material');
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_code !="=>17,"material_id IN"=>$material_ids,"project_id"=>0]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id"=>0]);
		}
		// debug($material_list->count());die;
		$this->set('material_list',$material_list);
		if(isset($user_id))
		{			
			$user_action = 'edit';			
			$user_data = $users_table->get($user_id);			
			$this->set('user_data',$user_data);
			$this->set('form_header','Edit Purchase Requisition (PR)');
			$this->set('button_text','Update Purchase Requisition (PR)');			
		}
		else
		{
			$user_action = 'insert';			
			$this->set('form_header','Add Purchase Requisition (PR)');
			$this->set('button_text','Add Purchase Requisition');
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
				if($ext != 0) {
					$post = $this->request->data;
					$projectdetail = TableRegistry::get('erp_projects'); 
					$project_data = $projectdetail->get($post['project_id']);
					$code = $project_data->project_code;
					
					$number1 = $this->ERPfunction->generate_auto_id($post['project_id'],"erp_inventory_purhcase_request","pr_id","prno");

					$new_prno = sprintf("%09d", $number1);
					$pr_no = $code.'/PR/'.$new_prno;
					
					$custom = isset($this->request->data['is_custom'])?$this->request->data['is_custom']:0;
					$this->request->data['prno'] = $pr_no;
					$this->request->data['is_manual'] = $custom;
					$this->request->data['created_date']=date('Y-m-d H:i:s');
					$this->request->data['pr_date']=$this->ERPfunction->set_date($this->request->data['pr_date']);
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					$this->request->data['status']=1;
					
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
					
					$entity_data = $erp_inventory_purhcase_request->newEntity();			
					$post_data=$erp_inventory_purhcase_request->patchEntity($entity_data,$this->request->data);
					if($erp_inventory_purhcase_request->save($post_data))
					{
						$this->Flash->success(__('Record created with PR No. '.$pr_no, null), 
									'default', 
									array('class' => 'success'));
						$pr_id = $post_data->pr_id;
						
						$this->ERPfunction->add_inventory_pr_material($this->request->data['material'],$pr_id,$custom);			
					}
				}
				else{
						$this->Flash->error(__("Invalid File Extension, Please Retry."));
					}
			}
			else{
				$post = $this->request->data;
				$projectdetail = TableRegistry::get('erp_projects'); 
				$project_data = $projectdetail->get($post['project_id']);
				$code = $project_data->project_code;
				
				$number1 = $this->ERPfunction->generate_auto_id($post['project_id'],"erp_inventory_purhcase_request","pr_id","prno");

				$new_prno = sprintf("%09d", $number1);
				$pr_no = $code.'/PR/'.$new_prno;
				
				$custom = isset($this->request->data['is_custom'])?$this->request->data['is_custom']:0;
				$this->request->data['prno'] = $pr_no;
				$this->request->data['is_manual'] = $custom;
				$this->request->data['created_date']=date('Y-m-d H:i:s');
				$this->request->data['pr_date']=$this->ERPfunction->set_date($this->request->data['pr_date']);
				$this->request->data['created_by']=$this->request->session()->read('user_id');
				$this->request->data['status']=1;
				
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
				
				$entity_data = $erp_inventory_purhcase_request->newEntity();			
				$post_data=$erp_inventory_purhcase_request->patchEntity($entity_data,$this->request->data);
				if($erp_inventory_purhcase_request->save($post_data))
				{
					$this->Flash->success(__('Record created with PR No. '.$pr_no, null), 
								'default', 
								array('class' => 'success'));
					$pr_id = $post_data->pr_id;
					
					$this->ERPfunction->add_inventory_pr_material($this->request->data['material'],$pr_id,$custom);			
				}
			}
			
		}	
    }
	
	
	public function editpreparepr($pr_id)
    {
		$this->set('form_header','Edit Purchase Requisition (PR)');
		$this->set('button_text','Update Purchase Requisition');
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);
		
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$pr_mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		
		$data = $pr_tbl->get($pr_id)->toArray();
		$this->set("data",$data	);
		
		$mdata = $pr_mt_tbl->find("all")->where(["pr_id"=>$pr_id,"show_in_purchase" => 0])->hydrate(false)->toArray();
		$this->set("mdata",$mdata);		
		if($this->request->is("post")) {
			// if(!empty($this->request->data['attach_file']) && !empty($this->request->data['attach_file']['name'])
			// && !empty($this->request->data['old_attach_file']) && !empty($this->request->data['old_attach_file']['name'])){
				if(isset($_FILES['attach_file'])){
				$file =$_FILES['attach_file']["name"];
				$size = count($file);
				for($i=0;$i<$size;$i++) {
					$parts = pathinfo($_FILES['attach_file']['name'][$i]);
				}
				$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
				// debug($ext);die;
				if($ext != 0) {
					// if(isset($_FILES['attach_file'])){
					// 	$file =$_FILES['attach_file']["name"];
					// 	$size = count($file);
					// 	for($i=0;$i<$size;$i++) {
					// 		$parts = pathinfo($_FILES['attach_file']['name'][$i]);
					// 	}
					// 	$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
					// 	if($ext == 0) {
					// 		$this->Flash->error(__("Invalid File Extension, Please Retry."));	
					// 		$this->redirect(array("controller" => "Inventory","action" => "approvedpr"));
					// 	}
					// }
					$saved = false;
					$post = $this->request->data;			
					$row = $pr_tbl->get($pr_id);
					$row->project_id = $post["project_id"];
					$row->pr_date = date("Y-m-d",strtotime($post["pr_date"]));
					$row->pr_time = $post["pr_time"];
					$row->contact_no1 = $post["contact_no1"];
					$row->contact_no2 = $post["contact_no2"];
					
					$old_files = array();
					if(isset($this->request->data["old_attach_file"]))
					{
						$old_files = $this->request->data["old_attach_file"];				
					}
					@$row->attach_label = trim(json_encode($this->request->data["attach_label"]),'\"');
					if(isset($_FILES["attach_file"]["name"]))
					{
						$file = $this->ERPfunction->upload_file("attach_file");	
						if(!empty($file))
						foreach($file as $attachment_file)
						{
							$old_files[] = $attachment_file;
						}					
					}
					$row->attach_file = json_encode($old_files);
					
					if($pr_tbl->save($row))
					{
						if(!empty($post["pr_material_id"]))
						{					
							foreach($post["pr_material_id"] as $key=>$pr_material_id)
							{
								$mrow = $pr_mt_tbl->get($pr_material_id);
								
								if(is_numeric($post["material"]["material_id"][$key]) && $post["material"]["material_id"][$key] != 0)
								{
									$mrow->material_id =  $post["material"]["material_id"][$key];
									$mrow->brand_id =  $post["material"]["brand_id"][$key];
								}
								else
								{
									$mrow->material_name =  $post["material"]["material_id"][$key];
									$mrow->brand_name =  $post["material"]["brand_id"][$key];
								}
								
								$mrow->static_unit = $post["material"]["static_unit"][$key];
								$mrow->quantity = $post["material"]["quantity"][$key];
								$mrow->po_pending_quantity = $post["material"]["quantity"][$key];
								$mrow->po_approved_quantity = 0;
								$mrow->delivery_date = date("Y-m-d",strtotime($post["material"]["delivery_date"][$key]));
								$mrow->name_of_subcontractor = $post["material"]["name_of_subcontractor"][$key];
								$mrow->usages = $post["material"]["usage"][$key];
								if($pr_mt_tbl->save($mrow))
								{
									$saved = true;
								}
							}
						}
					}
					if($saved) {
						$this->Flash->success(__('Record Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
					}
					$this->redirect(array("controller" => "Inventory","action" => "approvedpr"));
				}else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}	
			}
			else{
				$saved = false;
				$post = $this->request->data;			
				$row = $pr_tbl->get($pr_id);
				$row->project_id = $post["project_id"];
				$row->pr_date = date("Y-m-d",strtotime($post["pr_date"]));
				$row->pr_time = $post["pr_time"];
				$row->contact_no1 = $post["contact_no1"];
				$row->contact_no2 = $post["contact_no2"];
				
				$old_files = array();
				if(isset($this->request->data["old_attach_file"]))
				{
					$old_files = $this->request->data["old_attach_file"];				
				}
				@$row->attach_label = trim(json_encode($this->request->data["attach_label"]),'\"');
				if(isset($_FILES["attach_file"]["name"]))
				{
					$file = $this->ERPfunction->upload_file("attach_file");	
					if(!empty($file))
					foreach($file as $attachment_file)
					{
						$old_files[] = $attachment_file;
					}					
				}
				$row->attach_file = json_encode($old_files);
				
				if($pr_tbl->save($row))
				{
					if(!empty($post["pr_material_id"]))
					{					
						foreach($post["pr_material_id"] as $key=>$pr_material_id)
						{
							$mrow = $pr_mt_tbl->get($pr_material_id);
							
							if(is_numeric($post["material"]["material_id"][$key]) && $post["material"]["material_id"][$key] != 0)
							{
								$mrow->material_id =  $post["material"]["material_id"][$key];
								$mrow->brand_id =  $post["material"]["brand_id"][$key];
							}
							else
							{
								$mrow->material_name =  $post["material"]["material_id"][$key];
								$mrow->brand_name =  $post["material"]["brand_id"][$key];
							}
							
							$mrow->static_unit = $post["material"]["static_unit"][$key];
							$mrow->quantity = $post["material"]["quantity"][$key];
							$mrow->po_pending_quantity = $post["material"]["quantity"][$key];
							$mrow->po_approved_quantity = 0;
							$mrow->delivery_date = date("Y-m-d",strtotime($post["material"]["delivery_date"][$key]));
							$mrow->name_of_subcontractor = $post["material"]["name_of_subcontractor"][$key];
							$mrow->usages = $post["material"]["usage"][$key];
							if($pr_mt_tbl->save($mrow))
							{
								$saved = true;
							}
						}
					}
				}
				if($saved) {
					$this->Flash->success(__('Record Insert Successfully', null), 
						'default', 
						array('class' => 'success'));
				}
				$this->redirect(array("controller" => "Inventory","action" => "approvedpr"));
			}
		}
		// }else {
		// 	debug("File not found");
		// }
    }
	
	
	public function approvedpr($search_project_id= null)
    {
		$previous_url= $this->referer();
		if (strpos($previous_url, 'contract') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'Inventory';
			$back_page = 'index';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		
		$this->set('projects',$projects);
		$this->set('role',$this->role);
		if($this->request->is('post') || $search_project_id != null)
		{
			$this->set('selected_pl',true);
			$request_data = $this->request->data;	
			$this->set('request_data',$request_data);		
			if($search_project_id != null)
			{
				$this->request->data["project_id"] = $search_project_id;
				$this->request->data["from_date"] = "";
				$this->request->data["to_date"] = "";
			}
			
			if($this->request->data['from_date'] != '')
			{ $this->request->data['from_date'] =  date('Y-m-d',strtotime($this->request->data['from_date'])); }
			if($this->request->data['to_date'] != '')
			{ $this->request->data['to_date'] = date('Y-m-d',strtotime($this->request->data['to_date'])); }
			
			$pr_list = $this->Usermanage->fetch_approve_pr($this->user_id,$this->request->data);
			$this->set('pr_list',$pr_list);
		}
		else{
			
			// $pr_list = $this->Usermanage->fetch_approve_pr($this->user_id);
		}
		// debug($pr_list->fetch("assoc"));die;
		// $this->set('pr_list',$pr_list);		
    }
	
	public function viewpr()
    {
		$previous_url= $this->referer();
		if (strpos($previous_url, 'contract') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'Inventory';
			$back_page = 'index';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		// ini_set('memory_limit', '-1');
		// $pr_list = $this->Usermanage->fetch_view_pr($this->user_id);
		//$pr_list = $this->Usermanage->fetch_view_pr_material($this->user_id);
		// debug($pr_list->fetchAll("assoc"));die;
		//$this->set('pr_list',$pr_list);
		$this->set('role',$this->role);
		
		/* Loaded data by ajax according to load issue */
		// if($this->role == "deputymanagerelectric")
		// {
			// $projects = $this->Usermanage->access_project_ongoing($this->user_id);
		// }else{
			// $projects = $this->Usermanage->access_project($this->user_id);
		// }
		
		// $this->set('projects',$projects);

		$this->set('role',$this->role);
		
		$user = $this->request->session()->read('user_id');
		//var_dump($user);die;
		$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->access_project($user);
		//var_dump($projects_ids);die;
		
		/* Loaded data by ajax according to load issue */
		// $erp_material = TableRegistry::get('erp_material');
		// if($this->role == "deputymanagerelectric")
		// {
			// $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			// $material_ids = json_decode($material_ids);
			// $material_list = $erp_material->find()->where(["material_id IN"=>$material_ids]);
		// }else{
			// $material_list = $erp_material->find();
		// }
		
		// $this->set('material_list',$material_list);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		if($this->request->is("post"))
		{	
		
			if(isset($this->request->data['go1']))
			{			
				$erp_inventory_pr_material = TableRegistry::get("erp_inventory_pr_material");
				$erp_inventory_purhcase_request = TableRegistry::get("erp_inventory_purhcase_request");
				$post = $this->request->data;	
				$or = array();				
				
				$or["erp_inventory_purhcase_request.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				//$or["erp_inventory_grn.payment_method IN"] = (!empty($post["payment_mod"]) && $post["payment_mod"][0] != "All" )?$post["payment_mod"]:NULL;
				//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
				$or["erp_inventory_pr_material.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All")?$post["material_id"]:NULL;
				//$or["erp_inventory_grn.vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All")?$post["vendor_userid"]:NULL;
				$or["erp_inventory_purhcase_request.pr_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["erp_inventory_purhcase_request.pr_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["erp_inventory_purhcase_request.prno"] = (!empty($post["pr_no"]))?$post["pr_no"]:NULL;
				//$or["erp_inventory_grn.challan_no"] = (!empty($post["challan_no"]))?$post["challan_no"]:NULL;
				
				if($or["erp_inventory_pr_material.material_id IN"] == NULL)
				{
					if($role == "deputymanagerelectric")
					{
						$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
						$material_ids = json_decode($material_ids);
						$or["erp_inventory_pr_material.material_id IN"] = $material_ids;
					}
					
				}
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				if($post["purchase_mod"] == "central")
				{
					$or["erp_inventory_pr_material.show_in_purchase"] = 1;
				}
				else if($post["purchase_mod"] == "local")
				{
					$or["erp_inventory_pr_material.show_in_purchase"] = 0;
					
				}
				// debug($post);
				// debug($or);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids))
					{
						$result = $erp_inventory_pr_material->find()->select($erp_inventory_pr_material)->where(["OR"=>['approved_for_grnwithoutpo'=>1,'show_in_purchase'=>1]]);
						$result = $result->innerjoin(
							["erp_inventory_purhcase_request"=>"erp_inventory_purhcase_request"],
							["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id","erp_inventory_purhcase_request.project_id in"=>$projects_ids])
							->where($or)->select($erp_inventory_purhcase_request)->group('erp_inventory_purhcase_request.prno')->hydrate(false)->toArray();
							//var_dump($result);die;
						//$this->set('grn_list',$result);
					}
					else
					{
						$result=array();
					}
				}
				else
				{
					$result = $erp_inventory_pr_material->find()->select($erp_inventory_pr_material)->where(["OR"=>['approved_for_grnwithoutpo'=>1,'show_in_purchase'=>1]]);
						$result = $result->innerjoin(
							["erp_inventory_purhcase_request"=>"erp_inventory_purhcase_request"],
							["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id"])
							->where($or)->select($erp_inventory_purhcase_request)->group('erp_inventory_purhcase_request.prno')->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				//var_dump($result);die;
				$this->set('pr_list',$result);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "approveprlist.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$this->set("rows",$rows);
				$this->render("viewprpdf");
			}
		}
		/*else{
			$erp_inventory_pr_material = TableRegistry::get("erp_inventory_pr_material");
			$erp_inventory_purhcase_request = TableRegistry::get("erp_inventory_purhcase_request");
			$post = $this->request->data;	
			$or = array();				
			
			$or["erp_inventory_purhcase_request.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			//$or["erp_inventory_grn.payment_method IN"] = (!empty($post["payment_mod"]) && $post["payment_mod"][0] != "All" )?$post["payment_mod"]:NULL;
			//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
			$or["erp_inventory_pr_material.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All")?$post["material_id"]:NULL;
			//$or["erp_inventory_grn.vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All")?$post["vendor_userid"]:NULL;
			// $or["erp_inventory_purhcase_request.pr_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
			// $or["erp_inventory_purhcase_request.pr_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
			$or["erp_inventory_purhcase_request.prno"] = (!empty($post["pr_no"]))?$post["pr_no"]:NULL;
			//$or["erp_inventory_grn.challan_no"] = (!empty($post["challan_no"]))?$post["challan_no"]:NULL;
			
			if($or["erp_inventory_pr_material.material_id IN"] == NULL)
			{
				if($role == "deputymanagerelectric")
				{
					$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
					$material_ids = json_decode($material_ids);
					$or["erp_inventory_pr_material.material_id IN"] = $material_ids;
				}
				
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			// if($post["purchase_mod"] == "central")
			// {
				// $or["erp_inventory_pr_material.show_in_purchase"] = 1;
			// }
			// else if($post["purchase_mod"] == "local")
			// {
				// $or["erp_inventory_pr_material.show_in_purchase"] = 0;
				
			// }
			// debug($post);
			// debug($or);die;
			
			//array('fields'=>array('sum(stock_in) AS total_stock_in')) 
			if($role =='erpoperator' || $role =='projectdirector' || $role =='assistantpmm' || $role =='deputymanagerelectric' || $role =='constructionmanager' || $role =='billingengineer' || $role == 'siteaccountant' || $role == 'materialmanager')
			{
				if(!empty($projects_ids))
				{
					$result = $erp_inventory_pr_material->find()->select($erp_inventory_pr_material)->where(["OR"=>['approved_for_grnwithoutpo'=>1,'show_in_purchase'=>1]]);
					$result = $result->innerjoin(
						["erp_inventory_purhcase_request"=>"erp_inventory_purhcase_request"],
						["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id","erp_inventory_purhcase_request.project_id in"=>$projects_ids])
						->where($or)->select($erp_inventory_purhcase_request)->hydrate(false)->toArray();
						//var_dump($result);die;
					//$this->set('grn_list',$result);
				}
				else
				{
					$result=array();
				}
			}
			else
			{
				$result = $erp_inventory_pr_material->find()->select($erp_inventory_pr_material)->where(["OR"=>['approved_for_grnwithoutpo'=>1,'show_in_purchase'=>1]]);
					$result = $result->innerjoin(
						["erp_inventory_purhcase_request"=>"erp_inventory_purhcase_request"],
						["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id"])
						->where($or)->select($erp_inventory_purhcase_request)->hydrate(false)->toArray();
						//var_dump($result);die;
					
			}
			//var_dump($result);die;
			$this->set('pr_list',$result);
		}*/
    }
	public function preparepo()
    {			
		$erp_inventory_po = TableRegistry::get('erp_inventory_po');
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		$prno_list = $erp_inventory_purhcase_request->find();
		$this->set('prno_list',$prno_list);
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		$erp_agency = TableRegistry::get('erp_agency'); 
		$agency_list = $erp_agency->find();
		$this->set('agency_list',$agency_list);
		$raise_from = $this->ERPfunction->get_mm_constructionmanager();		
		/* $projects = $this->Usermanage->access_project_ongoing($this->user_id); */
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		$this->set('raise_from',$raise_from);
		$this->set("back","index");
		$this->set("controller","purchase");
		
		
		if(isset($user_id))
		{			
			$user_action = 'edit';			
			$user_data = $users_table->get($user_id);			
			$this->set('user_data',$user_data);
			$this->set('form_header','Edit Purchase Requisition (PR)');
			$this->set('button_text','Update Purchase Requisition (PR)');			
		}
		else
		{
			$user_action = 'insert';			
			$this->set('form_header','PREPARE PO (PO)');
			$this->set('button_text','Add Purchase Order (PO)');
		}	
		$this->set('user_action',$user_action);		
		if($this->request->is('post'))
		{	
			//debug($this->request->data);die;		
			$this->request->data['po_date']=$this->ERPfunction->set_date($this->request->data['po_date']);
			$this->request->data['taxes_duties']=isset($this->request->data['taxes_duties'])?$this->request->data['taxes_duties']:'0';
			$this->request->data['loading_transport']=isset($this->request->data['loading_transport'])?$this->request->data['loading_transport']:'0';
			$this->request->data['unloading']=isset($this->request->data['unloading'])?$this->request->data['unloading']:'0';
			$this->request->data['created_date']=date('Y-m-d H:i:s');			
			$this->request->data['delivery_date']=date("Y-m-d",strtotime($this->request->data['delivery_date']));			
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;
			
			if(!isset($this->request->data["warranty_check"]))
			{
				$this->request->data["warranty_check"] = "";
			}			
			
			$entity_data = $erp_inventory_po->newEntity();			
			$post_data=$erp_inventory_po->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_po->save($post_data))
			{
				$this->Flash->success(__('Record Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
				$po_id = $post_data->po_id;				
				$m_tbl = TableRegistry::get("erp_inventory_pr_material");
				// foreach($this->request->data["pr_mid"] as $pr_mid)
				// {
					// $mdata = $m_tbl->get($pr_mid);
					// $mdata->approved = 1;
					// $mdata->approved_by = $this->user_id;
					// $mdata->approved_date = date("Y-m-d");
					// $m_tbl->save($mdata);
				// }
				$this->ERPfunction->add_inventory_po_detail($this->request->data['material'],$po_id);			
			}
			$this->redirect(array("controller" => "Inventory","action" => "approvepo"));		
		}		
    }
	
	public function preparepo2()
	{		
		// ini_set('memory_limit', '-1');
		ini_set('memory_limit', '256M');
		$erp_inventory_po = TableRegistry::get('erp_inventory_po');
		$user_action = 'insert';			
		$this->set('form_header','Purchase Order (PO)');
		$this->set('button_text','Prepare PO');
		$user_action = "insert";
		$this->set('user_action',$user_action);
		$erp_material = TableRegistry::get('erp_material'); 
		if($this->role == "deputymanagerelectric")
		{
			// $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			// $material_ids = json_decode($material_ids);
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
			// $material_list = $erp_material->find()->where(['material_id IN'=>$material_ids]);
		}else{
			$projects = $this->Usermanage->all_access_project($this->user_id);
			// $material_list = $erp_material->find();
		}
		$this->set('projects',$projects);
		// $this->set('material_list',$material_list);
		$erp_agency = TableRegistry::get('erp_agency'); 
		$agency_list = $erp_agency->find();
		$this->set('agency_list',$agency_list);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		// $erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		// $prno_list = $erp_inventory_purhcase_request->find("list",["keyField"=>"pr_id","valueField"=>"prno"])->hydrate(false)->toArray();
		// $this->set('prno_list',$prno_list);		
		
		if($this->request->is('post'))
		{		
			if(isset($this->request->data["approve_list"]))
			{
				$post = $this->request->data;
				
				$project_id = $post['project_id'];
				$erp_material = TableRegistry::get('erp_material');
				
				if($this->role == "deputymanagerelectric")
				{
					$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
					$material_ids = json_decode($material_ids);
					$material_list = $erp_material->find()->where(["project_id IN"=>[0,$project_id],'material_id IN'=>$material_ids]);
				}else{
					$material_list = $erp_material->find()->where(["project_id IN"=>[0,$project_id]]);
				}
				$this->set('material_list',$material_list);
				$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
				$pr_data = $pr_tbl->find()->where(["prno"=>$post["prno"]])->hydrate(false)->toArray();
				$this->set("data",$post);
				$this->set("pr_data",$pr_data[0]);
				// debug($post);die;
			}
			else{
			$post = $this->request->data;
			// debug($post);die;
			$code = $this->ERPfunction->get_projectcode($post['project_id']);
			$new_pono = $this->ERPfunction->generate_auto_id($post['project_id'],"erp_inventory_po","po_id","po_no");
			$new_pono = sprintf("%09d", $new_pono);
			$po_no = $code.'/PO/'.$new_pono;
			$this->request->data['po_no']=$po_no;
			$this->request->data['po_date']=$this->ERPfunction->set_date($this->request->data['po_date']);
			$this->request->data['taxes_duties']=isset($this->request->data['taxes_duties'])?$this->request->data['taxes_duties']:'0';
			$this->request->data['loading_transport']=isset($this->request->data['loading_transport'])?$this->request->data['loading_transport']:'0';
			$this->request->data['unloading']=isset($this->request->data['unloading'])?$this->request->data['unloading']:'0';
			// $this->request->data['delivery_date']=date("Y-m-d",strtotime($this->request->data['delivery_date']));
			$this->request->data['created_date']=date('Y-m-d H:i:s');			
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;
			$this->request->data['contact_no1']=$this->request->data['contact1'];
			$this->request->data['contact_no2']=$this->request->data['contact2'];
			
			$entity_data = $erp_inventory_po->newEntity();			
			$post_data=$erp_inventory_po->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_po->save($post_data))
			{
				$m_tbl = TableRegistry::get("erp_inventory_pr_material");
				$po_id = $post_data->po_id;
				$po_complete = $this->ERPfunction->add_inventory_po_detail($this->request->data['material'],$po_id,$post["pr_mid"]);			
				foreach($post["pr_mid"] as $pr_mid)
				{
					if($pr_mid)
					{
						$mdata = $m_tbl->get($pr_mid);
						
						if($po_complete == 0)
						{
							$mdata->po_completed = 3;//3 means half created po and
						}else{
							$mdata->approved = 1;
							$mdata->po_completed = 0;//0 means half created po and 
						}
						// $mdata->approved_by = $this->user_id;
						$mdata->approved_date = date("Y-m-d H:i:s");
						$m_tbl->save($mdata);
					}
				}
				
				$this->Flash->success(__('PO Created Successfully With PO NO '.$po_no, null), 
							'default', 
							array('class' => 'success'));
			}
				$this->redirect(array("controller" => "Inventory","action" => "approvepo"));		
			}		
		}	
	}
	
	
	public function approvepo()
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
		// $po_list = $this->Usermanage->fetch_approve_po($this->user_id);
		// $this->set('po_list',$po_list);
		$selected_project = isset($_REQUEST['selected_project'])?$_REQUEST['selected_project']:'';
		$po_type = "";
		$show_data = isset($_REQUEST['selected_project'])?1:0;
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == 'deputymanagerelectric'){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
			
		$this->set('projects',$projects);
		$this->set('selected_project',$selected_project);
		$this->set('po_type',$po_type);
		$this->set('role',$this->role);
		$this->set('show_data',$show_data);
		if($this->request->is('post'))
		{
			$request_data = $this->request->data;	
			$this->set('request_data',$request_data);			
			if($this->request->data['from_date'] != '')
			$this->request->data['from_date'] =  date('Y-m-d',strtotime($this->request->data['from_date']));
			if($this->request->data['to_date'] != '')
			$this->request->data['to_date'] = date('Y-m-d',strtotime($this->request->data['to_date']));
		
			// $pr_list = $this->Usermanage->fetch_approve_pr($this->user_id,$this->request->data); /* fetch_approve_po */
			// $this->set('pr_list',$pr_list);
			$this->set('selected_project',$request_data['project_id']);
			$this->set('po_type',$request_data['po_type']);
			$this->set('show_data',1);
		}
		
    }
	
	
	
	public function preparegrn()
    {
		$user_action = 'insert';
		$this->set('form_header','Goods Receipt Note GRN');
		$this->set('button_text','Prepare G.R.N');
		$this->set('user_action',$user_action);		
		$this->set('role',$this->role);
		
		$erp_inventory_grn = TableRegistry::get('erp_inventory_grn'); 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		$prno_list = $erp_inventory_purhcase_request->find();
		$this->set('prno_list',$prno_list);
		
		if($this->request->is('post'))
		{		
			if(isset($this->request->data["approve_po"]))
			{
				$post = $this->request->data;				
				// $po_id = $post[""]
				// debug($post);				
				// die;
				$mat_tbl = TableRegistry::get("erp_inventory_pr_material");
				$po_tbl = TableRegistry::get("erp_inventory_po");
				$pod_tbl = TableRegistry::get("erp_inventory_po_detail");
				
				$data = $pod_tbl->find()->where(["id IN"=>$post["approved_list"],"approved"=>0])->hydrate(false)->toArray();	
				
				$i = 0;
				$row='';
				if(!empty($data))
				{
					foreach($data as $material)
					{
						$po_id = $post["selected_po_id_{$material['id']}"];
						$pr_id = $po_tbl->find()->where(["po_id"=>$post["selected_po_id_{$material['id']}"]])->select(["pr_id"])->hydrate(false)->toArray();
						$row .= '<tr class="cpy_row">
						<td>'.$this->ERPfunction->get_projectcode($material["material_id"]).'</td>
							<td>'.$this->ERPfunction->get_material_title($material["material_id"]).'	<input type="hidden" name="material[material_id][]" readonly = "true" value="'.$material["material_id"].'" id="material_id_'.$i.'"/></td>
							<td><input type="hidden" name="material[brand_id][]" value="'.$material["brand_id"].'" id="brand_id_'.$i.'"/>'.$this->ERPfunction->get_brand_name($material["brand_id"]).'</td>
							<td> <input type="text" name="material[quantity][]" readonly = "true" value="'.$material["quantity"].'" id="quantity_'.$i.'"/></td>
							<td><input type="text" name="material[actual_qty][]" class="actualy_qty" value="" data-id="'.$i.'" id="actual_qty_'.$i.'"/></td>
							<td><input type="text" name="material[difference_qty][]" readonly="true" value="" id="difference_qty_'.$i.'"/></td>
							<td>'.$this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material["material_id"])).'										
							 <input type="hidden" name="po_mid[]" value="'.$material["id"].'">
							</td>
						</tr>';
						/*  <td>'.$this->ERPfunction->get_brandname_by_po_material($pr_id[0]["pr_id"],$material['material_id']).'</td>
						<input type="hidden" name="pr_mid[]" value="'.$material["pr_material_id"].'"> */
						/* <td><input type="text" name="material[remarks][]" value="" id="remarks_'.$i.'"/></td>	 */
						$i++;
					}
				}else{
					$row = "<tr><td td colspan='7' align='center'>No Record Found.</td></tr>";
				}
							
				// debug($row);
				// die;
				$po_data = $po_tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
				
				$prepare_count = $this->ERPfunction->getlast_prepare_grn();
				$new_grnno = sprintf("%09d", $prepare_count + 1);
				$grn_no = '/GRN/'.$new_grnno;
				// debug($grn_no);
						
				$this->set("auto_grn_no",$grn_no);
				$this->set("po_data",$po_data[0]);
				$this->set("row",$row);
				$this->set("selected_pl",true);
			}
			else{
			
			$data = $this->request->data();
			$code = $this->ERPfunction->get_projectcode($data['project_id']);
			
			$new_grn_no = $this->ERPfunction->generate_auto_id_grn_not_grnlp($data['project_id'],"erp_inventory_grn","grn_id","grn_no","GRNLP");
			
			// $new_grn_no = $this->ERPfunction->generate_auto_id($data['project_id'],"erp_inventory_grn","grn_id","grn_no");
			$new_grn_no = sprintf("%09d", $new_grn_no);
			$grn_no = $code.'/GRN/'.$new_grn_no;
			
			
			$this->request->data['grn_no']= $grn_no;
			$this->request->data['grn_date']= $this->ERPfunction->set_date($this->request->data('grn_date'));
			$this->request->data['created_date']=date('Y-m-d H:i:s');			
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;	
			// $challan_file = $this->ERPfunction->upload_image("challan_bill");					
			// $gate_pass = $this->ERPfunction->upload_image("gate_pass");					
			// $this->request->data['challan_bill'] = $challan_file;
			// $this->request->data['gate_pass'] = $gate_pass;
			
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
			
			$entity_data = $erp_inventory_grn->newEntity();			
			$post_data=$erp_inventory_grn->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_grn->save($post_data))
			{
				$this->Flash->success(__('GRN Created Successfully with GRN No '.$grn_no, null), 
							'default', 
							array('class' => 'success'));
				$grn_id = $post_data->grn_id;
				$m_tbl = TableRegistry::get("erp_inventory_po_detail");				
				$i = 0;
				foreach($this->request->data["po_mid"] as $po_mid)
				{
					$mdata = $m_tbl->get($po_mid);					
					
					// $used_qty = $mdata->used_qty + $this->request->data["material"]["quantity"][$i];
					$used_qty = $mdata->used_qty + $this->request->data["material"]["actual_qty"][$i];
					$mdata->used_qty = $used_qty;
					if($mdata->quantity == $used_qty)
					{
						/* $mdata->approved = 1; */
						$mdata->approved = 2;
						$mdata->approved_by = $this->user_id;
						$mdata->approved_date = date('Y-m-d');
					}
					
					$m_tbl->save($mdata);
					$i++;
					
					// debug($this->request->data);
					// die; 
				}
				$this->ERPfunction->add_inventory_grn_detail($this->request->data['material'],$grn_id);			
			}
			// $this->redirect(array("controller" => "Inventory","action" => "approvegrn"));		
		 }
		}		
		
    }
	public function updategrn($grn_id="",$approved=null)
    {
		$this->set("selected_pl",true);
		$user_action = 'update';
		$this->set('form_header','Goods Receipt Note GRN');
		$this->set('button_text','Prepare G.R.N');
		$this->set('user_action',$user_action);	
		
		$erp_material = TableRegistry::get('erp_material'); 
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
			$material_list = $erp_material->find()->where(['material_id IN'=>$material_ids]);
		}else{
			$projects = $this->Usermanage->all_access_project($this->user_id);
			$material_list = $erp_material->find();
		}
		
		$erp_inventory_grn = TableRegistry::get('erp_inventory_grn'); 
		$erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail'); 
		
		$grn_row_data = $erp_inventory_grn->find()
		->select($erp_inventory_grn)
		->where(['erp_inventory_grn.grn_id'=>$grn_id]);
		$grn_record = $erp_inventory_grn->get($grn_id);
		
		$data = $grn_row_data->leftjoin(["erp_inventory_grn_detail"=>"erp_inventory_grn_detail"],
		["erp_inventory_grn_detail.grn_id = erp_inventory_grn.grn_id"])
		->select($erp_inventory_grn_detail)
		->hydrate(false)->toArray();
		$po_id = $data[0]["po_id"];
		$pr_id = $data[0]["pr_id"];
		// debug($data[0]);die;
		$this->set('update_grn',$data[0]);
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		$prno_list = $erp_inventory_purhcase_request->find();
		$this->set('prno_list',$prno_list);
		// debug($grn_id);
		if($approved == "approved")
		{
			$data = $erp_inventory_grn_detail->find()->where(["grn_id"=>$grn_id,"approved"=>1])->hydrate(false)->toArray();	
		}else{
			$data = $erp_inventory_grn_detail->find()->where(["grn_id"=>$grn_id,"approved"=>0])->hydrate(false)->toArray();
		}
				
		$i = 0;
		$row='';
		// debug($data);die;

		if(!empty($data))
		{
			foreach($data as $material)
			{
				// debug($material);die;
				//$po_id = $post["selected_po_id_{$material['id']}"];
				//$pr_id = $po_tbl->find()->where(["po_id"=>$post["selected_po_id_{$material['id']}"]])->select(["pr_id"])->hydrate(false)->toArray();
				$m_code = ($material['is_static'])?$material['m_code']:$this->ERPfunction->get_material_item_code_bymaterialid($material['material_id']);
				
				$mt = ($material['is_static'])?$material['material_name']:$this->ERPfunction->get_material_title
				($material['material_id']);
				
				$brnd = ($material['is_static'])?$material["brand_name"]:$this->ERPfunction->get_brand_name($material["brand_id"]);
				
				$unit = ($material['is_static'])?$material['static_unit']:$this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material['material_id']));
				
				$row .= '<tr class="cpy_row">
							<td>'.$m_code.'<input type="hidden" class="row_number" value="'.$i.'"></td><td>';
							if($po_id == '' && $pr_id == 0)
							{
								$row .= '
								<input type="hidden" name="material[old_material_id][]" readonly = "true" value="'.$material["material_id"].'"/>
								<select class="select2 material_id" style="width: 100%;" class="material_id" name="material[material_id][]" id="material_id_0" data-id="0">
								<option value="">--Select Material--</Option>';
								foreach($material_list as $retrive_data)
								{
									$selected = ($retrive_data['material_id'] == $material['material_id'])?"selected":"";
									$row .= '<option value="'.$retrive_data['material_id'].'"'.$selected.'>'.
									$retrive_data['material_title'].'</option>';
								}
								$row .= '</select></td>';
								
								$row .= '<td><select class="select2 brand_id"  required="true"   name="material[brand_id][]" style="width:130px;" id="brand_id_'.$i.'" data-id='.$i.'>';
								$brands = $this->ERPfunction->get_brands_by_material_id($material["material_id"]);
								if($brands != "")
								{
									foreach($brands as $brand)
									{
										$row .= '<option value="'.$brand['brand_id'].'"'.$this->ERPfunction->selected($brand['brand_id'],$material['brand_id']).'>'.$brand['brand_name'].'</option>';
									}
								}
								$row .= '</select>';
					
								
							}else{
								$row .= $mt.'<input type="hidden" name="material[material_id][]" readonly = "true" value="'.$material["material_id"].'" id="material_id_'.$i.'"/><input type="hidden" name="material[old_material_id][]" readonly = "true" value="'.$material["material_id"].'"/></td>';
								$row .= '<td><input type="hidden" name="material[brand_id][]" value="'.$material["brand_id"].'" id="brand_id_'.$i.'"/>'.$brnd.'</td>';
							}
							if($po_id != NULL) {
								$row .= "<td></td>";
							}
							$row .= '
							<td> <input type="text" class="vendor_quentity validate[required]" name="material[quantity][]" value="'.$material["quantity"].'" data-id="'.$i.'" id="quantity_'.$i.'"/>
							<input type="hidden" name="material[old_quantity][]" value="'.$material["quantity"].'">
							<input type="hidden" name="material[detail_id][]" value="'.$material["grndetail_id"].'"></td>
							<td><input type="text" name="material[actual_qty][]" class="actualy_qty validate[required]" value="'.$material["actual_qty"].'" data-id="'.$i.'" id="actual_qty_'.$i.'"/></td>
							<td><input type="text" name="material[difference_qty][]" readonly="true" value="'.$material["difference_qty"].'" id="difference_qty_'.$i.'"/></td>
							<td>'.$unit.'										
								 <input type="hidden" name="po_mid[]" value="'.$material["grndetail_id"].'">
							</td>';
							if($approved == 'approved'){
								$row .='<td> <input style="padding-left:0;padding-right:0" type="text" name="material[unit_price][]" data-id="'.$i.'" value="'.$material["unit_price"].'" id="unit_price_'.$i.'" class="unit_rate validate[required]" /></td>
								<td> <input style="padding-left:0;padding-right:0" type="text" name="material[discount][]" data-id="'.$i.'" value="'.$material["discount"].'" id="dis_'.$i.'" class="tx_count validate[required]" /></td>
								<td> <input style="padding-left:0;padding-right:0" type="text" name="material[gst][]" data-id="'.$i.'" value="'.$material["gst"].'" id="gst_'.$i.'" class="gst validate[required]" /></td>';
							}else {
								$row .='<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[unit_price][]" data-id="'.$i.'" value="'.$material["unit_price"].'" id="unit_price_'.$i.'" class="unit_rate validate[required]" /></td>
								<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[discount][]" data-id="'.$i.'" value="'.$material["discount"].'" id="dis_'.$i.'" class="tx_count validate[required]" /></td>
								<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[gst][]" data-id="'.$i.'" value="'.$material["gst"].'" id="gst_'.$i.'" class="gst validate[required]" /></td>';
							}
							$row .= '
							<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[amount][]" data-id="'.$i.'" value="'.$material["amount"].'" id="amount_'.$i.'" class="amount validate[required]" /></td>
							<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[single_amount][]" data-id="'.$i.'" id="single_amount_'.$i.'" value="'.$material["single_amount"].'" class="single_amount validate[required]" /></td>
							<td><input type="text" name="material[remark][]" value="'.$material["remarks"].'" id="remark_'.$i.'"/></td>
							
						 </tr>';
						
					$i++;
			}
		}
		
		$this->set("row",$row);
		
		if($this->request->is('post'))
		{
			/* Stock validation only when update approved GRN */
			
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
			if($approved == "approved")
			{
				
				/* Redirect back and do not update if stock going nagative after edit*/
				$material_items = $this->request->data["material"];
				foreach($material_items['material_id'] as $key => $data)
				{
					if(isset($material_items['old_quantity'][$key]))
					{
						$available_stock = $this->ERPfunction->get_current_stock($post['project_id'],$material_items['material_id'][$key]);
						$old_qty = $material_items['old_quantity'][$key];
						$difference = $old_qty - $material_items['quantity'][$key];
						$stock_after = $available_stock - $difference;
						if($stock_after < 0)
						{
							$m = $this->ERPfunction->get_material_title($material_items['material_id'][$key]);
							$this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
							return $this->redirect($this->referer());
						}
					}
				}
				/* Redirect back and do not update if stock going nagative after edit*/
			}
			/* Stock validation only when update approved GRN */
			$grn_type = isset($this->request->data['grn_type'])?$this->request->data['grn_type']:'';
			// if(isset($this->request->data['old_grn_type'])) {
			// 	$old_grn_type = $this->request->data['old_grn_type'];
			// }
			if(isset($this->request->data["old_grn_type"]))
			{
				$old_grn_type = $this->request->data["old_grn_type"];				
			}
			$this->request->data['last_edit']=date('Y-m-d H:i:s');			
			$this->request->data['grn_date']=date('Y-m-d',strtotime($this->request->data['grn_date']));			
			$this->request->data['gate_pass_date']=date('Y-m-d',strtotime($this->request->data['gate_pass_date']));
			$this->request->data['challan_date']=date('Y-m-d',strtotime($this->request->data['challan_date']));
			$this->request->data['last_edit_by']=$this->request->session()->read('user_id');
			
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
			
			$entity_data = $erp_inventory_grn->get($grn_id);			
			$post_data=$erp_inventory_grn->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_grn->save($post_data))
			{
				
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				/*
				$po_mid = $this->request->data['po_mid'];
				$k = 0;
				foreach($po_mid as $po_mt_id)
				{
					//var_dump($po_mt_id);die;
					$this->ERPfunction->edit_inventory_grn_detail($po_mt_id,$this->request->data,$k);			
					$k++;
				}
				*/
				$this->ERPfunction->edit_inventory_grn_detail($this->request->data['material'],$grn_id,$approved,$post,$grn_type);			
			}
			//$this->redirect(array("controller" => "Inventory","action" => "approvegrn"));
			// echo "<script>window.close();</script>";
			if($approved == "approved") {
				$this->redirect(array("controller" => "Inventory","action" => "viewgrn"));
			}else {
				$this->redirect(array("controller" => "Inventory","action" => "approvegrn"));
			}
					
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				
				$post = $this->request->data;
			if($approved == "approved")
			{
				
				/* Redirect back and do not update if stock going nagative after edit*/
				$material_items = $this->request->data["material"];
				foreach($material_items['material_id'] as $key => $data)
				{
					if(isset($material_items['old_quantity'][$key]))
					{
						$available_stock = $this->ERPfunction->get_current_stock($post['project_id'],$material_items['material_id'][$key]);
						$old_qty = $material_items['old_quantity'][$key];
						$difference = $old_qty - $material_items['quantity'][$key];
						$stock_after = $available_stock - $difference;
						if($stock_after < 0)
						{
							$m = $this->ERPfunction->get_material_title($material_items['material_id'][$key]);
							$this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
							return $this->redirect($this->referer());
						}
					}
				}
				/* Redirect back and do not update if stock going nagative after edit*/
			}
			/* Stock validation only when update approved GRN */
			$grn_type = isset($this->request->data['grn_type'])?$this->request->data['grn_type']:'';
			// if(isset($this->request->data['old_grn_type'])) {
			// 	$old_grn_type = $this->request->data['old_grn_type'];
			// }
			if(isset($this->request->data["old_grn_type"]))
			{
				$old_grn_type = $this->request->data["old_grn_type"];				
			}
			$this->request->data['last_edit']=date('Y-m-d H:i:s');			
			$this->request->data['grn_date']=date('Y-m-d',strtotime($this->request->data['grn_date']));			
			$this->request->data['gate_pass_date']=date('Y-m-d',strtotime($this->request->data['gate_pass_date']));
			$this->request->data['challan_date']=date('Y-m-d',strtotime($this->request->data['challan_date']));
			$this->request->data['last_edit_by']=$this->request->session()->read('user_id');
			
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
			
			$entity_data = $erp_inventory_grn->get($grn_id);			
			$post_data=$erp_inventory_grn->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_grn->save($post_data))
			{
				
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				/*
				$po_mid = $this->request->data['po_mid'];
				$k = 0;
				foreach($po_mid as $po_mt_id)
				{
					//var_dump($po_mt_id);die;
					$this->ERPfunction->edit_inventory_grn_detail($po_mt_id,$this->request->data,$k);			
					$k++;
				}
				*/
				$this->ERPfunction->edit_inventory_grn_detail($this->request->data['material'],$grn_id,$approved,$post,$grn_type);			
			}
			//$this->redirect(array("controller" => "Inventory","action" => "approvegrn"));
			// echo "<script>window.close();</script>";
			if($approved == "approved") {
				$this->redirect(array("controller" => "Inventory","action" => "viewgrn"));
			}else {
				$this->redirect(array("controller" => "Inventory","action" => "approvegrn"));
			}
				
			}
			
			
		}
	}
	
	public function updateauditgrn($audit_id="")
    {
		ini_set('memory_limit', '500M');

		$this->set("selected_pl",true);
		$user_action = 'update';
		$this->set('form_header','Goods Receipt Note GRN');
		$this->set('button_text','Prepare G.R.N');
		$this->set('user_action',$user_action);	
		
		$erp_material = TableRegistry::get('erp_material'); 
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
			$material_list = $erp_material->find()->where(['material_id IN'=>$material_ids]);
		}else{
			$projects = $this->Usermanage->all_access_project($this->user_id);
			$material_list = $erp_material->find();
		}
		
		$erp_audit_grn = TableRegistry::get('erp_audit_grn'); 
		$erp_audit_grn_detail = TableRegistry::get('erp_audit_grn_detail'); 
		
		$grn_row_data = $erp_audit_grn->find()
		->select($erp_audit_grn)
		->where(['erp_audit_grn.audit_id'=>$audit_id]);
		$grn_record = $erp_audit_grn->get($audit_id);
		
		$data = $grn_row_data->leftjoin(["erp_audit_grn_detail"=>"erp_audit_grn_detail"],
		["erp_audit_grn_detail.audit_id = erp_audit_grn.audit_id"])
		->select($erp_audit_grn_detail)
		->hydrate(false)->toArray();
		
		$po_id = $data[0]["po_id"];
		$pr_id = $data[0]["pr_id"];
		//var_dump($data);
		$this->set('update_grn',$data[0]);
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		$prno_list = $erp_inventory_purhcase_request->find();
		$this->set('prno_list',$prno_list);
		
		$data = $erp_audit_grn_detail->find()->where(["audit_id"=>$audit_id])->hydrate(false)->toArray();	
		
				
		$i = 0;
		$row='';
		if(!empty($data))
		{
			foreach($data as $material)
			{
				//$po_id = $post["selected_po_id_{$material['id']}"];
				//$pr_id = $po_tbl->find()->where(["po_id"=>$post["selected_po_id_{$material['id']}"]])->select(["pr_id"])->hydrate(false)->toArray();
				$m_code = ($material['is_static'])?$material['m_code']:$this->ERPfunction->get_material_item_code_bymaterialid($material['material_id']);
				
				$mt = ($material['is_static'])?$material['material_name']:$this->ERPfunction->get_material_title
				($material['material_id']);
				
				$brnd = ($material['is_static'])?$material["brand_name"]:$this->ERPfunction->get_brand_name($material["brand_id"]);
				
				$unit = ($material['is_static'])?$material['static_unit']:$this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material['material_id']));
				
				$row .= '<tr class="cpy_row">
							<td>'.$m_code.'</td><td>';
							if($po_id == '' && $pr_id == 0)
							{
								$row .= '<select class="select2 material_id" style="width: 100%;" name="material[material_id][]" id="material_id_0" data-id="0">
								<option value="">--Select Material--</Option>';
								foreach($material_list as $retrive_data)
								{
									$selected = ($retrive_data['material_id'] == $material['material_id'])?"selected":"";
									$row .= '<option value="'.$retrive_data['material_id'].'"'.$selected.'>'.
									$retrive_data['material_title'].'</option>';
								}
								$row .= '</select></td>';
								
								$row .= '<td><select class="select2 brand_id"  required="true"   name="material[brand_id][]" style="width:130px;" id="brand_id_'.$i.'" data-id='.$i.'>';
								$brands = $this->ERPfunction->get_brands_by_material_id($material["material_id"]);
								if($brands != "")
								{
									foreach($brands as $brand)
									{
										$row .= '<option value="'.$brand['brand_id'].'"'.$this->ERPfunction->selected($brand['brand_id'],$material['brand_id']).'>'.$brand['brand_name'].'</option>';
									}
								}
								$row .= '</select>';
					
								
							}else{
								$row .= $mt.'<input type="hidden" name="material[material_id][]" readonly = "true" value="'.$material["material_id"].'" id="material_id_'.$i.'"/></td>';
								$row .= '<td><input type="hidden" name="material[brand_id][]" value="'.$material["brand_id"].'" id="brand_id_'.$i.'"/>'.$brnd.'</td>';
							}
							
							$row .= '<td> <input type="text" class="vendor_quentity validate[required]" name="material[quantity][]" value="'.$material["quantity"].'" data-id="'.$i.'" id="quantity_'.$i.'"/>
							<input type="hidden" name="material[old_quantity][]" value="'.$material["quantity"].'">
							<input type="hidden" name="material[detail_id][]" value="'.$material["auditdetail_id"].'"></td>
							<td><input type="text" name="material[actual_qty][]" class="actualy_qty validate[required]" value="'.$material["actual_qty"].'" data-id="'.$i.'" id="actual_qty_'.$i.'"/></td>
							<td><input type="text" name="material[difference_qty][]" readonly="true" value="'.$material["difference_qty"].'" id="difference_qty_'.$i.'"/></td>
							<td>'.$unit.'										
								 <input type="hidden" name="po_mid[]" value="'.$material["grndetail_id"].'">
							</td>
							<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[unit_price][]" data-id="'.$i.'" value="'.$material["unit_price"].'" id="unit_price_'.$i.'" class="unit_rate validate[required]" /></td>
							<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[discount][]" data-id="'.$i.'" value="'.$material["discount"].'" id="dis_'.$i.'" class="tx_count validate[required]" /></td>
							<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[gst][]" data-id="'.$i.'" value="'.$material["gst"].'" id="gst_'.$i.'" class="gst validate[required]" /></td>
							<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[amount][]" data-id="'.$i.'" value="'.$material["amount"].'" id="amount_'.$i.'" class="amount validate[required]" /></td>
							<td> <input style="padding-left:0;padding-right:0" readonly="true" type="text" name="material[single_amount][]" data-id="'.$i.'" id="single_amount_'.$i.'" value="'.$material["single_amount"].'" class="single_amount validate[required]" /></td>
						 </tr>';
						
					$i++;
			}
		}
		$this->set("row",$row);
		
		if($this->request->is('post'))
		{
			// debug($this->request->data);die;
						
			// $this->request->data['last_edit']=date('Y-m-d H:i:s');			
			$this->request->data['grn_date']=date('Y-m-d',strtotime($this->request->data['grn_date']));			
			$this->request->data['gate_pass_date']=date('Y-m-d',strtotime($this->request->data['gate_pass_date']));
			$this->request->data['challan_date']=date('Y-m-d',strtotime($this->request->data['challan_date']));
			// $this->request->data['last_edit_by']=$this->request->session()->read('user_id');
			
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
			
			$entity_data = $erp_audit_grn->get($audit_id);			
			$post_data=$erp_audit_grn->patchEntity($entity_data,$this->request->data);
			$diff = $post_data->extract($post_data->visibleProperties(), true);
			// echo $post_data->dirty();die;
			
			unset($diff['project_code']);
			unset($diff['material']);
			unset($diff['po_mid']);
			unset($diff['old_attach_file']);
			
			if(empty($post_data["changes"]))
			{
				/* Add user detail who make changes */
				if(!empty($diff))
				{
					$diff["edit_by"] = $this->request->session()->read('user_id');				
					$diff = json_encode($diff);
					$changes[date("Y-m-d H:i:s")] = $diff;
					$post_data["changes"] = json_encode($changes);
					
					$post_data["changes_status"] = 1;
				}
				/* Add user detail who make changes */
			}else{
				if(!empty($diff))
				{
					$diff["edit_by"] = $this->request->session()->read('user_id');				
					$diff = json_encode($diff);
					$changes[date("Y-m-d H:i:s")] = $diff;
					$changes = json_encode($changes);
					// debug($changes);die;
					$post_data["changes"] = json_encode(array_merge(json_decode($changes, true),json_decode($post_data["changes"], true)));
					$post_data["changes_status"] = 1;
				}
			}
			// debug($post_data);die;
			if($erp_audit_grn->save($post_data))
			{
				
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				/*
				$po_mid = $this->request->data['po_mid'];
				$k = 0;
				foreach($po_mid as $po_mt_id)
				{
					//var_dump($po_mt_id);die;
					$this->ERPfunction->edit_inventory_grn_detail($po_mt_id,$this->request->data,$k);			
					$k++;
				}
				*/
				$update_status = $this->ERPfunction->edit_inventory_auditgrn_detail($this->request->data['material'],$audit_id);
				// debug($update_status);
				if($update_status > 0)
				{
					$record = $erp_audit_grn->get($audit_id);
					$record->changes_status = 1;
					$erp_audit_grn->save($record);
				}
			}
			//$this->redirect(array("controller" => "Inventory","action" => "approvegrn"));
			echo "<script>opener.location.reload();</script>";
			echo "<script>window.close();</script>";
		}
	}
	
	public function updateapprovedgrn($grn_id="")
	{
		$this->set("selected_pl",true);
		$user_action = 'update';
		$this->set('form_header','Goods Receipt Note GRN');
		$this->set('button_text','Prepare G.R.N');
		$this->set('user_action',$user_action);	
		
		$erp_inventory_grn = TableRegistry::get('erp_inventory_grn'); 
		$erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail'); 
		
		$grn_row_data = $erp_inventory_grn->find()
		->select($erp_inventory_grn)
		->where(['erp_inventory_grn.grn_id'=>$grn_id]);
		
		$data = $grn_row_data->leftjoin(["erp_inventory_grn_detail"=>"erp_inventory_grn_detail"],
		["erp_inventory_grn_detail.grn_id = erp_inventory_grn.grn_id"])
		->select($erp_inventory_grn_detail)
		->hydrate(false)->toArray();
		
		//var_dump($data);
		$this->set('update_grn',$data[0]);
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		$prno_list = $erp_inventory_purhcase_request->find();
		$this->set('prno_list',$prno_list);
		
		$data = $erp_inventory_grn_detail->find()->where(["grn_id"=>$grn_id,"approved"=>1])->hydrate(false)->toArray();	
				
		$i = 0;
		$row='';
		if(!empty($data))
		{
			foreach($data as $material)
			{
				//$po_id = $post["selected_po_id_{$material['id']}"];
				//$pr_id = $po_tbl->find()->where(["po_id"=>$post["selected_po_id_{$material['id']}"]])->select(["pr_id"])->hydrate(false)->toArray();
				$row .= '<tr class="cpy_row">
							<td>'.$this->ERPfunction->get_material_item_code_bymaterialid($material["material_id"]).'</td>
							<td>'.$this->ERPfunction->get_material_title($material["material_id"]).'	<input type="hidden" name="material[material_id][]" readonly = "true" value="'.$material["material_id"].'" id="material_id_'.$i.'"/></td>
							<td><input type="hidden" name="material[brand_id][]" value="'.$material["brand_id"].'" id="brand_id_'.$i.'"/>'.$this->ERPfunction->get_brand_name($material["brand_id"]).'</td>
							<td> <input type="text" name="material[quantity][]" value="'.$material["quantity"].'" id="quantity_'.$i.'"/></td>
							<td><input type="text" name="material[actual_qty][]" class="actualy_qty" value="'.$material["actual_qty"].'" data-id="'.$i.'" id="actual_qty_'.$i.'"/></td>
							<td><input type="text" name="material[difference_qty][]" readonly="true" value="'.$material["difference_qty"].'" id="difference_qty_'.$i.'"/></td>
							<td>'.$this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material["material_id"])).'										
								 <input type="hidden" name="po_mid[]" value="'.$material["grndetail_id"].'">
							</td>
						 </tr>';
						
					$i++;
			}
		}
		$this->set("row",$row);
		
		if($this->request->is('post'))
		{
			$this->request->data['last_edit']=date('Y-m-d H:i:s');			
			$this->request->data['grn_date']=date('Y-m-d',strtotime($this->request->data['grn_date']));			
			$this->request->data['last_edit_by']=$this->request->session()->read('user_id');
			
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
			
			$entity_data = $erp_inventory_grn->get($grn_id);			
			$post_data=$erp_inventory_grn->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_grn->save($post_data))
			{
				
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				/*
				$po_mid = $this->request->data['po_mid'];
				$k = 0;
				foreach($po_mid as $po_mt_id)
				{
					//var_dump($po_mt_id);die;
					$this->ERPfunction->edit_inventory_grn_detail($po_mt_id,$this->request->data,$k);			
					$k++;
				}
				*/
				$this->ERPfunction->edit_inventory_grn_detail($this->request->data['material'],$grn_id);			
			}
			$this->redirect(array("controller" => "Inventory","action" => "approvegrn"));
		}
	}
	
    public function preparegrnwithoutpo()
    {
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id"=>0]);
		$this->set('material_list',$material_list);
		
		$erp_agency = TableRegistry::get('erp_agency'); 
		$agency_list = $erp_agency->find();
		$this->set('agency_list',$agency_list);
		
		/* Check that GRN request come from SST code start */
		$from_sst = 0;
		$sst_id = isset($_REQUEST['sst_id'])?$_REQUEST['sst_id']:0;
		$sst_detail_id = isset($_REQUEST['sst_detail_id'])?$_REQUEST['sst_detail_id']:0;
		
		if($sst_id)
		{
			$from_sst = 1;
			$erp_inventory_sst = TableRegistry::get('erp_inventory_sst');
			$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail');
			$sst_data = $erp_inventory_sst->get($sst_id);
			$sst_transfer_to_project = $sst_data->transfer_to;
			$central_material = $erp_material->find()->where(['project_id'=>0])->toArray();
			$project_material = $erp_material->find()->where(['project_id'=>$sst_transfer_to_project])->toArray();
			$project_material = array_merge($central_material,$project_material);
			$sst_detail_data = $erp_inventory_sst_detail->find()->where(['sst_id'=>$sst_id])->hydrate(false)->toArray();
			$i = 0;
			$sst_material_row = '';
			
			foreach($sst_detail_data as $material)
			{
				$m_row = '';
				$b_row = '';
				$m_code = $this->ERPfunction->get_material_item_code_bymaterialid($material['material_id']);
				
				$mt = $this->ERPfunction->get_material_title
				($material['material_id']);
				
				$brnd = $this->ERPfunction->get_brand_name($material["brand_id"]);
				
				$unit = $this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material['material_id']));
				
				$m_row .= '<select class="select2 material_id" required="true" style="width:100%;" name="material[material_id][]" id="material_id_'.$i.'" data-id='.$i.'>
					<option value="">Select Material</Option>';
					   foreach($project_material as $retrive_data)
					   {
						   // $selected = ($retrive_data['material_id'] == $material['material_id']) ? "selected" : "";
							$m_row .=  '<option value="'.$retrive_data['material_id'].'">'.
							 $retrive_data['material_title'].'</option>';
					   }
				$m_row .= '</select>';
				
				$b_row .= '<select class="select2 brand_id"  required="true"   name="material[brand_id][]" style="width:130px;" id="brand_id_'.$i.'" data-id='.$i.'>';
				$brands = $this->ERPfunction->get_brands_by_material_id($material["material_id"]);
												if($brands != "")
												{
													foreach($brands as $brand)
													{
														$b_row .= '<option value="'.$brand['brand_id'].'">'.$brand['brand_name'].'</option>';
													}
												}
				
				$b_row .= '</select>';
				
				$sst_material_row .= '<tr class="cpy_row">
							<td><span id="material_code_'.$i.'">'.$m_code.'</span></td>
							<td>'.$m_row.'</td>
							<td>'.$b_row.'</td>
							<td> <input type="text" name="material[quantity][]" value="'.$material["quantity"].'" readonly="true" id="quantity_'.$i.'"/></td>
							<td><input type="text" name="material[actual_qty][]" class="actualy_qty" readonly="true" value="'.$material["quantity"].'" data-id="'.$i.'" id="actual_qty_'.$i.'"/></td>
							<td><input type="text" name="material[difference_qty][]" readonly="true" value="0 : Less" id="difference_qty_'.$i.'"/></td>
							<td>'.$unit.'</td>
							<td><input type="text" name="material[remark][]" value="" id="remark_'.$i.'"/></td>
						 </tr>';
						
					$i++;
			}
			$this->set('sst_data',$sst_data);
			$this->set('sst_detail_data',$sst_detail_data);
			$this->set('sst_material_row',$sst_material_row);
			
		}else{
			$from_sst = 0;
			$sst_id = 0;
			$sst_detail_id = 0;
		}
		/* Check that GRN request come from SST code end */
		$this->set('sst_id',$sst_id);
		$this->set('sst_detail_id',$sst_detail_id);
		$this->set('from_sst',$from_sst);
		$user_action = 'insert';
		$this->set('form_header','Goods Receipt Note (GRN) without P.O.');
		$this->set('button_text','Prepare GRN');
		$this->set('user_action',$user_action);
		$this->set('role',$this->role);
		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id"=>0]);
		$this->set('material_list',$material_list);
		
		$erp_inventory_grn = TableRegistry::get('erp_inventory_grn'); 
		
		$this->set('user_action',$user_action);	
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		$prno_list = $erp_inventory_purhcase_request->find("list",["keyField"=>"prno","valueField"=>"prno"])->hydrate(false)->toArray();
		$this->set('prno_list',$prno_list);	
		
		if($this->request->is('post'))
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
					
					// debug($this->request->data);die;
					if(isset($this->request->data["approve_list"]))
					{
						$post = $this->request->data;				
						$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
						$pr_data = $pr_tbl->find()->where(["prno"=>$post["prno"]])->hydrate(false)->toArray();
						$this->set("data",$post);
						$this->set("pr_data",$pr_data[0]);
						
					}else{
					$connection = ConnectionManager::get('default');
					try{
					$connection->begin();
					// debug($this->request->data);die;
					$data = $this->request->data();
					//Check for duplicate record
					
					$count = $erp_inventory_grn->find()->where(["project_id"=>$data["project_id"],"grn_date"=>date("Y-m-d",strtotime($data["grn_date"])),"vendor_userid"=>$data["vendor_userid"],"challan_no"=>$data["challan_no"],"challan_date"=>date("Y-m-d",strtotime($data["challan_date"]))])->count();
					
					if($count == 0)
					{
					
					$code = $this->ERPfunction->get_projectcode($data['project_id']);
					
				
					$new_grn_no = $this->ERPfunction->generate_auto_id_grn($data['project_id'],"erp_inventory_grn","grn_id","grn_no","GRNLP");
					$new_grn_no = sprintf("%09d", $new_grn_no);
					if($data['grn_type'] == "without_po")
					{
						// $grn_no = $code.'/GRNLP/'.$new_grn_no;
						$grn_no = $code.'/GRN/'.$new_grn_no;
					}else{
						$grn_no = $code.'/GRN/'.$new_grn_no;
					}
					
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
					$this->request->data['manualpo_no'] = $data['manual_po_no'];
					$this->request->data['attach_file'] = json_encode($all_files);
					
					$entity_data = $erp_inventory_grn->newEntity();			
				
					// $challan_file = $this->ERPfunction->upload_image("challan_bill");					
					// $gate_pass = $this->ERPfunction->upload_image("gate_pass");					
					// $this->request->data['challan_bill'] = $challan_file;
					// $this->request->data['gate_pass'] = $gate_pass;
					$this->request->data['pr_id'] = (isset($this->request->data['pr_id']) && $this->request->data['pr_id'] > 0)?$this->request->data['pr_id']:0;
					$this->request->data['grn_no']= $grn_no;			
					$this->request->data['grn_date']= $this->ERPfunction->set_date($this->request->data('grn_date'));
					$this->request->data['gate_pass_date']= $this->ERPfunction->set_date($this->request->data('gate_pass_date'));
					$this->request->data['challan_date']= $this->ERPfunction->set_date($this->request->data('challan_date'));					
					$this->request->data['po_date']= $this->ERPfunction->set_date($this->request->data('po_date'));					
					$this->request->data['created_date']=date('Y-m-d H:i:s');			
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					$this->request->data['status']=1;
					$post_data=$erp_inventory_grn->patchEntity($entity_data,$this->request->data);
					if($erp_inventory_grn->save($post_data))
					{			
						$grn_id = $post_data->grn_id;						
						$grndetail_id = $this->ERPfunction->add_inventory_grn_detail($this->request->data['material'],$grn_id);	
						$m_tbl = TableRegistry::get("erp_inventory_pr_material");
						$po_id = $post_data->po_id;	
						$i = 0;
						if(isset($this->request->data["pr_mid"]))
						{
							foreach($this->request->data["pr_mid"] as $pr_mid)
							{	
								$mdata = $m_tbl->get($pr_mid);
								$used_qty = $mdata->used_qty + $this->request->data["material"]["actual_qty"][$i];
								$mdata->used_qty = $used_qty;						
								$mdata->last_grndetail_id = serialize($grndetail_id);
								if($mdata->quantity == $used_qty)
								{							
									$mdata->approved = 1; /* Remove from Inventory PR Alert*/
									$mdata->show_in_purchase = 0; /* Remove from purchase PR Alert*/
									$mdata->approved_for_grnwithoutpo = 2; /* Remove preparegrnwithoutpo form*/								
								}
								$m_tbl->save($mdata);
								$i++;	
							}
						}
						if($data['from_sst'])
						{
							$this->ERPfunction->remove_stock_from_project_bysst($sst_id,$sst_detail_id);
						}
						
						//Creatae Manual PO for local purchase from grn without PO
						// if($data['grn_type'] == "with_localpo")
						// {
						// 	$code = $this->ERPfunction->get_projectcode($data['project_id']);
						// 	$new_pono = $this->ERPfunction->generate_auto_id($data['project_id'],"erp_inventory_po","po_id","po_no");
						// 	$new_pono = sprintf("%09d", $new_pono);
						// 	$new_pono = $code.'/PO/'.$new_pono;
							
						// 	$po_data['po_purchase_type']="local_po";
						// 	$po_data['project_id']=$data['project_id'];
						// 	$po_data['bill_mode']=$data['bill_mode'];
						// 	$po_data['usage_name']=$data['usage_name'];
						// 	if(isset($data['agency_id']))
						// 	{
						// 		$po_data['agency_id']=$data['agency_id'];
						// 	}
						// 	$po_data['po_no']=$new_pono;
						// 	$po_data['po_date']=$this->ERPfunction->set_date($data['grn_date']);
						// 	$po_data['po_time']=$data['grn_time'];
						// 	$po_data['vendor_userid']=$data['vendor_userid'];
						// 	$po_data['vendor_id']=$data['vendor_id'];
							
						// 	$vendor_detail = json_decode($this->ERPfunction->vendordetail($data['vendor_userid']));
						// 	$po_data['vendor_address'] = $vendor_detail->address_1;
						// 	$po_data['custom_pan'] = $vendor_detail->pancard_no;
						// 	$po_data['custom_gst'] = $vendor_detail->gst_no;
						// 	$po_data['vendor_delivery_address'] = $vendor_detail->delivery_place;
						// 	$po_data['vendor_email'] = $vendor_detail->email_id;
						// 	$po_data['delivery_type'] = 'direct';
						// 	$po_data['delivery_project'] = 0;
						// 	$po_data['payment_method'] = strtolower($data['payment_method']);
						// 	$po_data['delivery_date']=$this->ERPfunction->set_date($this->request->data['grn_date']);
						// 	$po_data['taxes_duties']=isset($data['taxes_duties'])?$data['taxes_duties']:'0';
						// 	$po_data['loading_transport']=isset($data['loading_transport'])?$data['loading_transport']:'0';
						// 	$po_data['unloading']=isset($data['unloading'])?$data['unloading']:'0';
						// 	$po_data['warranty_check']=isset($data['warranty_check'])?$data['warranty_check']:'0';
						// 	$po_data['payment_days']=$data['payment_days'];
						// 	$po_data['warranty']=$data['warranty'];
						// 	$po_data['po_mode']=$data['po_mode'];
						// 	$po_data['mail_check']=0;
						// 	$po_data['is_grn_base']=1;
						// 	$po_data['related_grn_id']=$grn_id;
						// 	$po_data['remarks']=$data['po_remarks'];
						// 	$po_data['gstno']=$this->ERPfunction->getstategstno($data['bill_mode']);
						// 	$po_data['created_date']=date('Y-m-d H:i:s');			
						// 	$po_data['created_by']=$this->request->session()->read('user_id');
						// 	$po_data['status']=1;
							
						// 	$erp_inventory_po = TableRegistry::get('erp_inventory_po');
						// 	$entity_data_manualpo = $erp_inventory_po->newEntity();			
						// 	$save_manualpo=$erp_inventory_po->patchEntity($entity_data_manualpo,$po_data);
						// 	if($erp_inventory_po->save($save_manualpo))
						// 	{
						// 		$last_po_id = $save_manualpo->po_id;
						// 		$erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail');
						// 		$material_items = $data['material'];
						// 		foreach($material_items['material_id'] as $key => $data)
						// 		{
						// 			$save_data['po_id'] =  $last_po_id;		
						// 			$save_data['po_type'] =  "local_po";		
						// 			$save_data['material_id'] =  $material_items['material_id'][$key];
						// 			if(isset($material_items['static_unit'][$key]))
						// 			{
						// 				$save_data['static_unit'] =  $material_items['static_unit'][$key];
						// 			}
						// 			if(isset($material_items['is_custom'][$key]))
						// 			{
						// 				$save_data['is_custom'] =  $material_items['is_custom'][$key];
						// 			}
						// 			$save_data['hsn_code'] =  '';
						// 			$save_data['quantity'] =  $material_items['actual_qty'][$key];
						// 			$save_data['brand_id'] =  $material_items['brand_id'][$key];
						// 			$save_data['unit_price'] =  $material_items['unit_rate'][$key];
						// 			$save_data['discount'] =  $material_items['discount'][$key];
						// 			// $save_data['transportation'] =  $material_items['loading_transport'][$key];
						// 			// $save_data['exice'] =  $material_items['exice'][$key];
						// 			// $save_data['other_tax'] =  $material_items['other_tax'][$key];
						// 			$save_data['gst'] = $material_items['gst'][$key];
						// 			$save_data['amount'] =  $material_items['amount'][$key];
						// 			$save_data['single_amount'] =  $material_items['single_amount'][$key];
						// 			$pr_material_data = $erp_inventory_po_detail->newEntity();			
						// 			$pr_material_data=$erp_inventory_po_detail->patchEntity($pr_material_data,$save_data);
						// 			// debug($pr_material_data);die;
						// 			$erp_inventory_po_detail->save($pr_material_data);
									
						// 			//Add last added local po id in grn last added record for linkup
						// 			$latest_grn = $erp_inventory_grn->get($grn_id);
						// 			$update_latest_grn['local_po_id'] = $last_po_id;
						// 			$update_latest_grn['local_po_id'] = $last_po_id;
									
						// 			$update_current_grn = $erp_inventory_grn->patchEntity($latest_grn,$update_latest_grn);
						// 			$erp_inventory_grn->save($update_current_grn);
									
						// 		}
						// 	}
							
						// }
						$connection->commit();
						$this->Flash->success(__('GRN Created Successfully with GRN No '.$grn_no, null), 'default', array('class' => 'success'));	
		
						return $this->redirect(array('controller'=>'Inventory','action'=>'preparegrnwithoutpo'));
									
					}	
					}else{
						
						echo "duplicate";
						$data = $erp_inventory_grn->find()->where(["project_id"=>$data["project_id"],"grn_date"=>date("Y-m-d",strtotime($data["grn_date"])),"vendor_userid"=>$data["vendor_userid"],"challan_no"=>$data["challan_no"],"challan_date"=>date("Y-m-d",strtotime($data["challan_date"]))])->hydrate(false)->toArray();
						debug($data);die;
					}
					
					}catch(Exception $e){
						$connection->rollback();
					}
				}
					
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				// debug($this->request->data);die;
			if(isset($this->request->data["approve_list"]))
			{
				$post = $this->request->data;				
				$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
				$pr_data = $pr_tbl->find()->where(["prno"=>$post["prno"]])->hydrate(false)->toArray();
				$this->set("data",$post);
				$this->set("pr_data",$pr_data[0]);
				
			}else{
					$connection = ConnectionManager::get('default');
					try{
					$connection->begin();
					// debug($this->request->data);die;
					$data = $this->request->data();
					//Check for duplicate record
					
					$count = $erp_inventory_grn->find()->where(["project_id"=>$data["project_id"],"grn_date"=>date("Y-m-d",strtotime($data["grn_date"])),"vendor_userid"=>$data["vendor_userid"],"challan_no"=>$data["challan_no"],"challan_date"=>date("Y-m-d",strtotime($data["challan_date"]))])->count();
					
					if($count == 0)
					{
					
					$code = $this->ERPfunction->get_projectcode($data['project_id']);
					
				
					$new_grn_no = $this->ERPfunction->generate_auto_id_grn($data['project_id'],"erp_inventory_grn","grn_id","grn_no","GRNLP");
					$new_grn_no = sprintf("%09d", $new_grn_no);
					if($data['grn_type'] == "without_po")
					{
						// $grn_no = $code.'/GRNLP/'.$new_grn_no;
						$grn_no = $code.'/GRN/'.$new_grn_no;
					}else{
						$grn_no = $code.'/GRN/'.$new_grn_no;
					}
					
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
					$this->request->data['manualpo_no'] = $data['manual_po_no'];
					$this->request->data['attach_file'] = json_encode($all_files);
					
					$entity_data = $erp_inventory_grn->newEntity();			
				
					// $challan_file = $this->ERPfunction->upload_image("challan_bill");					
					// $gate_pass = $this->ERPfunction->upload_image("gate_pass");					
					// $this->request->data['challan_bill'] = $challan_file;
					// $this->request->data['gate_pass'] = $gate_pass;
					$this->request->data['pr_id'] = (isset($this->request->data['pr_id']) && $this->request->data['pr_id'] > 0)?$this->request->data['pr_id']:0;
					$this->request->data['grn_no']= $grn_no;			
					$this->request->data['grn_date']= $this->ERPfunction->set_date($this->request->data('grn_date'));
					$this->request->data['gate_pass_date']= $this->ERPfunction->set_date($this->request->data('gate_pass_date'));
					$this->request->data['challan_date']= $this->ERPfunction->set_date($this->request->data('challan_date'));					
					$this->request->data['po_date']= $this->ERPfunction->set_date($this->request->data('po_date'));					
					$this->request->data['created_date']=date('Y-m-d H:i:s');			
					$this->request->data['created_by']=$this->request->session()->read('user_id');
					$this->request->data['status']=1;
					$post_data=$erp_inventory_grn->patchEntity($entity_data,$this->request->data);
					if($erp_inventory_grn->save($post_data))
					{			
						$grn_id = $post_data->grn_id;						
						$grndetail_id = $this->ERPfunction->add_inventory_grn_detail($this->request->data['material'],$grn_id);	
						$m_tbl = TableRegistry::get("erp_inventory_pr_material");
						$po_id = $post_data->po_id;	
						$i = 0;
						if(isset($this->request->data["pr_mid"]))
						{
							foreach($this->request->data["pr_mid"] as $pr_mid)
							{	
								$mdata = $m_tbl->get($pr_mid);
								$used_qty = $mdata->used_qty + $this->request->data["material"]["actual_qty"][$i];
								$mdata->used_qty = $used_qty;						
								$mdata->last_grndetail_id = serialize($grndetail_id);
								if($mdata->quantity == $used_qty)
								{							
									$mdata->approved = 1; /* Remove from Inventory PR Alert*/
									$mdata->show_in_purchase = 0; /* Remove from purchase PR Alert*/
									$mdata->approved_for_grnwithoutpo = 2; /* Remove preparegrnwithoutpo form*/								
								}
								$m_tbl->save($mdata);
								$i++;	
							}
						}
						if($data['from_sst'])
						{
							$this->ERPfunction->remove_stock_from_project_bysst($sst_id,$sst_detail_id);
						}
						
						//Creatae Manual PO for local purchase from grn without PO
						// if($data['grn_type'] == "with_localpo")
						// {
						// 	$code = $this->ERPfunction->get_projectcode($data['project_id']);
						// 	$new_pono = $this->ERPfunction->generate_auto_id($data['project_id'],"erp_inventory_po","po_id","po_no");
						// 	$new_pono = sprintf("%09d", $new_pono);
						// 	$new_pono = $code.'/PO/'.$new_pono;
							
						// 	$po_data['po_purchase_type']="local_po";
						// 	$po_data['project_id']=$data['project_id'];
						// 	$po_data['bill_mode']=$data['bill_mode'];
						// 	$po_data['usage_name']=$data['usage_name'];
						// 	if(isset($data['agency_id']))
						// 	{
						// 		$po_data['agency_id']=$data['agency_id'];
						// 	}
						// 	$po_data['po_no']=$new_pono;
						// 	$po_data['po_date']=$this->ERPfunction->set_date($data['grn_date']);
						// 	$po_data['po_time']=$data['grn_time'];
						// 	$po_data['vendor_userid']=$data['vendor_userid'];
						// 	$po_data['vendor_id']=$data['vendor_id'];
							
						// 	$vendor_detail = json_decode($this->ERPfunction->vendordetail($data['vendor_userid']));
						// 	$po_data['vendor_address'] = $vendor_detail->address_1;
						// 	$po_data['custom_pan'] = $vendor_detail->pancard_no;
						// 	$po_data['custom_gst'] = $vendor_detail->gst_no;
						// 	$po_data['vendor_delivery_address'] = $vendor_detail->delivery_place;
						// 	$po_data['vendor_email'] = $vendor_detail->email_id;
						// 	$po_data['delivery_type'] = 'direct';
						// 	$po_data['delivery_project'] = 0;
						// 	$po_data['payment_method'] = strtolower($data['payment_method']);
						// 	$po_data['delivery_date']=$this->ERPfunction->set_date($this->request->data['grn_date']);
						// 	$po_data['taxes_duties']=isset($data['taxes_duties'])?$data['taxes_duties']:'0';
						// 	$po_data['loading_transport']=isset($data['loading_transport'])?$data['loading_transport']:'0';
						// 	$po_data['unloading']=isset($data['unloading'])?$data['unloading']:'0';
						// 	$po_data['warranty_check']=isset($data['warranty_check'])?$data['warranty_check']:'0';
						// 	$po_data['payment_days']=$data['payment_days'];
						// 	$po_data['warranty']=$data['warranty'];
						// 	$po_data['po_mode']=$data['po_mode'];
						// 	$po_data['mail_check']=0;
						// 	$po_data['is_grn_base']=1;
						// 	$po_data['related_grn_id']=$grn_id;
						// 	$po_data['remarks']=$data['po_remarks'];
						// 	$po_data['gstno']=$this->ERPfunction->getstategstno($data['bill_mode']);
						// 	$po_data['created_date']=date('Y-m-d H:i:s');			
						// 	$po_data['created_by']=$this->request->session()->read('user_id');
						// 	$po_data['status']=1;
							
						// 	$erp_inventory_po = TableRegistry::get('erp_inventory_po');
						// 	$entity_data_manualpo = $erp_inventory_po->newEntity();			
						// 	$save_manualpo=$erp_inventory_po->patchEntity($entity_data_manualpo,$po_data);
						// 	if($erp_inventory_po->save($save_manualpo))
						// 	{
						// 		$last_po_id = $save_manualpo->po_id;
						// 		$erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail');
						// 		$material_items = $data['material'];
						// 		foreach($material_items['material_id'] as $key => $data)
						// 		{
						// 			$save_data['po_id'] =  $last_po_id;		
						// 			$save_data['po_type'] =  "local_po";		
						// 			$save_data['material_id'] =  $material_items['material_id'][$key];
						// 			if(isset($material_items['static_unit'][$key]))
						// 			{
						// 				$save_data['static_unit'] =  $material_items['static_unit'][$key];
						// 			}
						// 			if(isset($material_items['is_custom'][$key]))
						// 			{
						// 				$save_data['is_custom'] =  $material_items['is_custom'][$key];
						// 			}
						// 			$save_data['hsn_code'] =  '';
						// 			$save_data['quantity'] =  $material_items['actual_qty'][$key];
						// 			$save_data['brand_id'] =  $material_items['brand_id'][$key];
						// 			$save_data['unit_price'] =  $material_items['unit_rate'][$key];
						// 			$save_data['discount'] =  $material_items['discount'][$key];
						// 			// $save_data['transportation'] =  $material_items['loading_transport'][$key];
						// 			// $save_data['exice'] =  $material_items['exice'][$key];
						// 			// $save_data['other_tax'] =  $material_items['other_tax'][$key];
						// 			$save_data['gst'] = $material_items['gst'][$key];
						// 			$save_data['amount'] =  $material_items['amount'][$key];
						// 			$save_data['single_amount'] =  $material_items['single_amount'][$key];
						// 			$pr_material_data = $erp_inventory_po_detail->newEntity();			
						// 			$pr_material_data=$erp_inventory_po_detail->patchEntity($pr_material_data,$save_data);
						// 			// debug($pr_material_data);die;
						// 			$erp_inventory_po_detail->save($pr_material_data);
									
						// 			//Add last added local po id in grn last added record for linkup
						// 			$latest_grn = $erp_inventory_grn->get($grn_id);
						// 			$update_latest_grn['local_po_id'] = $last_po_id;
						// 			$update_latest_grn['local_po_id'] = $last_po_id;
									
						// 			$update_current_grn = $erp_inventory_grn->patchEntity($latest_grn,$update_latest_grn);
						// 			$erp_inventory_grn->save($update_current_grn);
									
						// 		}
						// 	}
							
						// }
						$connection->commit();
						$this->Flash->success(__('GRN Created Successfully with GRN No '.$grn_no, null), 'default', array('class' => 'success'));	
		
						return $this->redirect(array('controller'=>'Inventory','action'=>'preparegrnwithoutpo'));
									
					}	
					}else{
						
						echo "duplicate";
						$data = $erp_inventory_grn->find()->where(["project_id"=>$data["project_id"],"grn_date"=>date("Y-m-d",strtotime($data["grn_date"])),"vendor_userid"=>$data["vendor_userid"],"challan_no"=>$data["challan_no"],"challan_date"=>date("Y-m-d",strtotime($data["challan_date"]))])->hydrate(false)->toArray();
						debug($data);die;
					}
					
					}catch(Exception $e){
						$connection->rollback();
					}
				}
			}
			
			
		}
    }
	
	
	public function approvegrn()
    {
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		 
		// $grno_list = $this->Usermanage->fetch_approve_grn_no($this->user_id);
		// $this->set('grno_list',$grno_list);
		
		// $grn_list = $this->Usermanage->fetch_approve_grn($this->user_id);
		// $this->set('grn_list',$grn_list);
		
		if($this->request->is("post"))
		{
			$erp_inventory_grn = TableRegistry::get('erp_inventory_grn');
			$erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail');
			$post = $this->request->data;
			
			$user = $this->request->session()->read('user_id');
			$projects_ids = $this->Usermanage->users_project($user);
			$role = $this->role;
			
			$or = array();				
			$or["erp_inventory_grn.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			
			if($or["erp_inventory_grn.project_id IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["erp_inventory_grn.project_id IN"] = $projects_ids;
				}
			}

			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			if($post['grn_type'] != "All")
			{
				if($post['grn_type'] == "central")
				{
					$or["erp_inventory_grn.po_id !="] = "";
				}elseif($post['grn_type'] == "local"){
					$or["erp_inventory_grn.local_po_id !="] = 0;
				}else{
					$or["erp_inventory_grn.po_id"] = "";
					$or["erp_inventory_grn.local_po_id"] = 0;
				}
			}
			$or["erp_inventory_grn_detail.approved"] = 0;
			
			############################# Inner Join Query #############################################
			$result = $erp_inventory_grn->find()->select($erp_inventory_grn)->order(['erp_inventory_grn.grn_date'=>'DESC']);
			$result = $result->innerjoin(
				["erp_inventory_grn_detail"=>"erp_inventory_grn_detail"],
				["erp_inventory_grn.grn_id = erp_inventory_grn_detail.grn_id"])
				->where($or)->select($erp_inventory_grn_detail)->hydrate(false)->toArray();
				
			$new_array = array();
			foreach($result as $retrive)
			{
				if(isset($new_array[$retrive['grn_no']]))
				{
					$new_array[$retrive['grn_no']]['erp_inventory_grn_detail'][] = $retrive['erp_inventory_grn_detail'];
				}else{
					$a = $retrive["erp_inventory_grn_detail"];
					unset($retrive["erp_inventory_grn_detail"]);
					$new_array[$retrive["grn_no"]] = $retrive;
					$new_array[$retrive["grn_no"]]['erp_inventory_grn_detail'][] = $a;
				}
				
			}
			############################# Inner Join Query #############################################
			$grn_list = $new_array;
			// debug($or);die;
			// if(!empty($or)){
				// $grn_list = $erp_inventory_grn->find()->where([$or])->order(['grn_date'=>'DESC'])->hydrate(false)->toArray();
			// }else{
				// $grn_list = $erp_inventory_grn->find()->order(['grn_date'=>'DESC'])->hydrate(false)->toArray();
			// }
			$this->set("grn_list",$grn_list);
			
		}
    }

	public function grnaudit()
    {
		$project = isset($_REQUEST['project'])?$_REQUEST['project']:'';
		$grn_type = isset($_REQUEST['grn_type'])?$_REQUEST['grn_type']:'';
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		$erp_audit_grn = TableRegistry::get('erp_audit_grn');
		$erp_audit_grn_detail = TableRegistry::get("erp_audit_grn_detail");
		
		if($project != '' && $grn_type != '')
		{
			$user = $this->request->session()->read('user_id');
			$projects_ids = $this->Usermanage->users_project($user);
			$role = $this->role;
			// debug($post);
			$or = array();				
			
			$or["erp_audit_grn.project_id"] = (!empty($project) && $project != "All" )?$project:NULL;
			
			if($or["erp_audit_grn.project_id"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["erp_audit_grn.project_id IN"] = $projects_ids;
				}
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			// debug($or);die;
			if($grn_type != "All")
			{
				if($grn_type == "central")
				{
					$or["erp_audit_grn.po_id !="] = "";
				}elseif($grn_type == "local"){
					$or["erp_audit_grn.local_po_id !="] = 0;
				}else{
					$or["erp_audit_grn.po_id"] = "";
					$or["erp_audit_grn.local_po_id"] = 0;
				}
			}
			$or["erp_audit_grn_detail.audit_status"] = 0;
			// debug($or);die;
			############################# Inner Join Query #############################################
			$result = $erp_audit_grn->find()->select($erp_audit_grn)->order(['erp_audit_grn.grn_date'=>'DESC']);
			$result = $result->innerjoin(
				["erp_audit_grn_detail"=>"erp_audit_grn_detail"],
				["erp_audit_grn.grn_id = erp_audit_grn_detail.grn_id"])
				->where($or)->select($erp_audit_grn_detail)->hydrate(false)->toArray();
				
			$new_array = array();
			foreach($result as $retrive)
			{
				if(isset($new_array[$retrive['grn_no']]))
				{
					$new_array[$retrive['grn_no']]['erp_audit_grn_detail'][] = $retrive['erp_audit_grn_detail'];
				}else{
					$a = $retrive["erp_audit_grn_detail"];
					unset($retrive["erp_audit_grn_detail"]);
					$new_array[$retrive["grn_no"]] = $retrive;
					$new_array[$retrive["grn_no"]]['erp_audit_grn_detail'][] = $a;
				}
				
			}
			############################# Inner Join Query #############################################
			// debug($new_array);die;
			$grn_list = $new_array;
			// if(!empty($or)){
				// $grn_list = $erp_audit_grn->find()->where([$or])->order(['grn_date'=>'DESC'])->hydrate(false)->toArray();
			// }else{
				// $grn_list = $erp_audit_grn->find()->order(['grn_date'=>'DESC'])->hydrate(false)->toArray();
			// }
			// debug($grn_list);die;
			$this->set("grn_list",$grn_list);
			$this->set("grn_type",$grn_type);
			$this->set("project",$project);
		}
				
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			
			$user = $this->request->session()->read('user_id');
			$projects_ids = $this->Usermanage->users_project($user);
			$role = $this->role;
			// debug($post);
			$or = array();				
			
			$or["erp_audit_grn.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
			
			if($or["erp_audit_grn.project_id"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["erp_audit_grn.project_id IN"] = $projects_ids;
				}
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			// debug($or);die;
			if($post['grn_type'] != "All")
			{
				if($post['grn_type'] == "central")
				{
					$or["erp_audit_grn.po_id !="] = "";
				}elseif($post['grn_type'] == "local"){
					$or["erp_audit_grn.local_po_id !="] = 0;
				}else{
					$or["erp_audit_grn.po_id"] = "";
					$or["erp_audit_grn.local_po_id"] = 0;
				}
			}
			$or["erp_audit_grn_detail.audit_status"] = 0;
			// debug($or);die;
			############################# Inner Join Query #############################################
			$result = $erp_audit_grn->find()->select($erp_audit_grn)->order(['erp_audit_grn.grn_date'=>'DESC']);
			$result = $result->innerjoin(
				["erp_audit_grn_detail"=>"erp_audit_grn_detail"],
				["erp_audit_grn.grn_id = erp_audit_grn_detail.grn_id"])
				->where($or)->select($erp_audit_grn_detail)->hydrate(false)->toArray();
				
			$new_array = array();
			foreach($result as $retrive)
			{
				if(isset($new_array[$retrive['grn_no']]))
				{
					$new_array[$retrive['grn_no']]['erp_audit_grn_detail'][] = $retrive['erp_audit_grn_detail'];
				}else{
					$a = $retrive["erp_audit_grn_detail"];
					unset($retrive["erp_audit_grn_detail"]);
					$new_array[$retrive["grn_no"]] = $retrive;
					$new_array[$retrive["grn_no"]]['erp_audit_grn_detail'][] = $a;
				}
				
			}
			############################# Inner Join Query #############################################
			// debug($new_array);die;
			$grn_list = $new_array;
			// debug($grn_list);die;
			// if(!empty($or)){
				// $grn_list = $erp_audit_grn->find()->where([$or])->order(['grn_date'=>'DESC'])->hydrate(false)->toArray();
			// }else{
				// $grn_list = $erp_audit_grn->find()->order(['grn_date'=>'DESC'])->hydrate(false)->toArray();
			// }
			// debug($grn_list);die;
			$this->set("grn_list",$grn_list);
			$this->set("grn_type",$post['grn_type']);
			$this->set("project",$post["project_id"]);
			
		}
    }

	public function viewgrn()
    {
		
		// $projects = $this->Usermanage->access_project($this->user_id);
		// $this->set('projects',$projects);
			$role = $this->role;
		// $this->set('role',$this->role);
		
		// $erp_material = TableRegistry::get('erp_material'); 
		// $material_list = $erp_material->find();
		// $this->set('material_list',$material_list);
		
		// $users_table = TableRegistry::get('erp_vendor');
		// $vendor_department = $users_table->find();
		// $this->set('vendor_department',$vendor_department);
				
		if($this->request->is("post"))
		{
			if(isset($this->request->data["export_csv"]))
			{
				ini_set('memory_limit', '1024M');
				// phpinfo();die;

				$post = $this->request->data();
				// debug($post);die;
				$or = array();				
				$projects_ids = $this->Usermanage->users_project($this->user_id);
				$or["erp_inventory_grn.project_id IN"] = (!empty($post["pro_id"]) && $post["pro_id"][0] != "All" )?$post["pro_id"]:NULL;
				$or["erp_inventory_grn.payment_method ="] = (!empty($post["payment_mod"]))?$post["payment_mod"]:NULL;
				$or["erp_inventory_grn_detail.material_id IN"] = (!empty($post["materials"]) && $post["materials"][0] != "All")?$post["materials"]:NULL;
				$or["erp_inventory_grn.vendor_userid IN"] = (!empty($post["vendors"]) && $post["vendors"][0] != "All")?$post["vendors"]:NULL;
				$or["erp_inventory_grn.grn_date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["erp_inventory_grn.grn_date <="] = ($post["date_to"] != "")?date("Y-m-d",strtotime($post["date_to"])):NULL;
				$or["erp_inventory_grn.grn_no ="] = (!empty($post["grn_no"]))?$post["grn_no"]:NULL;
				$or["erp_inventory_grn.challan_no ="] = (!empty($post["challan_no"]))?$post["challan_no"]:NULL;
				if($or["erp_inventory_grn.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						
						$or["erp_inventory_grn.project_id IN"] = $projects_ids;
						
					}
				}
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if($post['purchase_mod'] == "central")
				{
					// $or["erp_inventory_grn.manualpo_no"] = 0; 
					// $or["erp_inventory_grn.local_po_id"] = 0;
					$or["erp_inventory_grn.po_id !="] = "";
				}elseif($post['purchase_mod'] == "local"){
					$or["erp_inventory_grn.local_po_id !="] = 0;
				}elseif($post['purchase_mod'] == "withoutpo"){
					$or["erp_inventory_grn.po_id ="] = "";
					$or["erp_inventory_grn.local_po_id ="] = 0;
				}
				// if($post["purchase_mod"] == "central")
				// {
					// $or["erp_inventory_grn.po_id !="] = "";
				// }
				// else if($post["purchase_mod"] == "local")
				// {
					// $or["erp_inventory_grn.pr_id !="] = 0;
					// $or["erp_inventory_grn.po_id ="] = "";
				// }
				$or["erp_inventory_grn_detail.approved ="] = 1;
				$grn_tbl = TableRegistry::get("erp_inventory_grn");
				$grnd_tbl = TableRegistry::get("erp_inventory_grn_detail");
				
				$result = $grn_tbl->find()->select($grn_tbl)->order(['grn_date'=>'DESC']);
				$result = $result->innerjoin(
							["erp_inventory_grn_detail"=>"erp_inventory_grn_detail"],
							["erp_inventory_grn.grn_id = erp_inventory_grn_detail.grn_id"])
							->where($or)->select($grnd_tbl)->hydrate(false)->toArray();
							
				$rows = array();
				$rows[] = array("Project Name","G.R.N No","Date","Time","Vendor Name","Challan No","Material Name","Make/Source","Material Group","Vendor/Royalty\"\s Qty./Weight","Actual Qty./Weight","Diff.(+/-)","Unit","Mode of Purchase","Mode of Payment");
			
				foreach($result as $retrive_data)
				{		
					// debug($retrive_data);die;		
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
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $retrive_data['grn_no'];
					$csv[] = date('d-m-Y',strtotime($retrive_data['grn_date']));
					$csv[] = $retrive_data['grn_time'];
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
					$csv[] = $retrive_data['challan_no'];
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $this->ERPfunction->get_material_group_name_by_material($retrive_data['material_id']);
					$csv[] = $retrive_data['quantity'];
					$csv[] = $retrive_data['actual_qty'];
					$csv[] = $retrive_data['difference_qty'];
					$csv[] = $static_unit;
					$csv[] = ($retrive_data["po_id"] != "")?'Central':'Local';
					$csv[] = $retrive_data['payment_method'];
					$rows[] = $csv;
				}
				$filename = "approvegrnlist.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{	
				ini_set('memory_limit', '1024M');
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				
				$post = $this->request->data();	
				$or = array();				
				$projects_ids = $this->Usermanage->users_project($this->user_id);
				$or["erp_inventory_grn.project_id IN"] = (!empty($post["pro_id"]) && $post["pro_id"][0] != "All" )?$post["pro_id"]:NULL;
				$or["erp_inventory_grn.payment_method ="] = (!empty($post["payment_mod"]))?$post["payment_mod"]:NULL;
				$or["erp_inventory_grn_detail.material_id IN"] = (!empty($post["materials"]) && $post["materials"][0] != "All")?$post["materials"]:NULL;
				$or["erp_inventory_grn.vendor_userid IN"] = (!empty($post["vendors"]) && $post["vendors"][0] != "All")?$post["vendors"]:NULL;
				$or["erp_inventory_grn.grn_date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["erp_inventory_grn.grn_date <="] = ($post["date_to"] != "")?date("Y-m-d",strtotime($post["date_to"])):NULL;
				$or["erp_inventory_grn.grn_no ="] = (!empty($post["grn_no"]))?$post["grn_no"]:NULL;
				$or["erp_inventory_grn.challan_no ="] = (!empty($post["challan_no"]))?$post["challan_no"]:NULL;
				
				if($or["erp_inventory_grn.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){  
						
						$or["erp_inventory_grn.project_id IN"] = $projects_ids;
						
					}
				}			
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if($post['purchase_mod'] == "central")
				{
					$or["erp_inventory_grn.po_id !="] = "";
				}elseif($post['purchase_mod'] == "local"){
					$or["erp_inventory_grn.local_po_id !="] = 0;
				}elseif($post['purchase_mod'] == "withoutpo"){
					$or["erp_inventory_grn.po_id ="] = "";
					$or["erp_inventory_grn.local_po_id ="] = 0;
				}
				$or["erp_inventory_grn_detail.approved ="] = 1;
				$grn_tbl = TableRegistry::get("erp_inventory_grn");
				$grnd_tbl = TableRegistry::get("erp_inventory_grn_detail");
				
				$result = $grn_tbl->find()->select($grn_tbl)->order(['grn_date'=>'DESC']);
				$result = $result->innerjoin(
							["erp_inventory_grn_detail"=>"erp_inventory_grn_detail"],
							["erp_inventory_grn.grn_id = erp_inventory_grn_detail.grn_id"])
							->where($or)->select($grnd_tbl)->hydrate(false)->toArray();
							
				$rows = array();
				$rows[] = array("Project Name","G.R.N No","Date","Time","Vendor Name","Challan No","Material Name","Make/Source","Material Group","Vendor/Royalty\"\s Qty./Weight","Actual Qty./Weight","Diff.(+/-)","Unit","Mode of Purchase","Mode of Payment");
			
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
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $retrive_data['grn_no'];
					$csv[] = date('d-m-Y',strtotime($retrive_data['grn_date']));
					$csv[] = $retrive_data['grn_time'];
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
					$csv[] = $retrive_data['challan_no'];
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $this->ERPfunction->get_material_group_name_by_material($retrive_data['material_id']);
					$csv[] = $retrive_data['quantity'];
					$csv[] = $retrive_data['actual_qty'];
					$csv[] = $retrive_data['difference_qty'];
					$csv[] = $static_unit;
					$csv[] = ($retrive_data["po_id"] != "")?'Central':'Local';
					$csv[] = $retrive_data['payment_method'];
					$rows[] = $csv;
				}
				
				// $rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("viewgrnpdf");
			}
			/*
			if(isset($this->request->data['go']))
			{			
				$grn_tbl = TableRegistry::get("erp_inventory_grn");
				$grnd_tbl = TableRegistry::get("erp_inventory_grn_detail");
				$post = $this->request->data;	
				$or = array();				
				
				$or["erp_inventory_grn.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["erp_inventory_grn.payment_method IN"] = (!empty($post["payment_mod"]) && $post["payment_mod"][0] != "All" )?$post["payment_mod"]:NULL;
				//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
				$or["erp_inventory_grn_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All")?$post["material_id"]:NULL;
				$or["erp_inventory_grn.vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All")?$post["vendor_userid"]:NULL;
				$or["grn_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["grn_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["erp_inventory_grn.grn_no"] = (!empty($post["grn_no"]))?$post["grn_no"]:NULL;
				$or["erp_inventory_grn.challan_no "] = (!empty($post["challan_no"]))?$post["challan_no"]:NULL;
				
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if($post["purchase_mod"] == "central")
				{
					$or["erp_inventory_grn.po_id !="] = "";
				}
				else if($post["purchase_mod"] == "local")
				{
					$or["erp_inventory_grn.pr_id !="] = 0;
					$or["erp_inventory_grn.po_id"] = "";
				}
				// debug($post);
				 //debug($or);die;
				
				// ,array('fields'=>array('sum(stock_in) AS total_stock_in')) 
				$result = $grn_tbl->find()->select($grn_tbl)->order(['grn_date'=>'DESC']);
				$result = $result->innerjoin(
							["erp_inventory_grn_detail"=>"erp_inventory_grn_detail"],
							["erp_inventory_grn.grn_id = erp_inventory_grn_detail.grn_id","erp_inventory_grn_detail.approved"=>1])
							->where($or)->select($grnd_tbl)->hydrate(false)->toArray();
				$this->set('grn_list',$result);
			}	*/		
		}else{
			// $grn_list = $this->Usermanage->fetch_view_grn($this->user_id);
			// $this->set('grn_list',$grn_list);
		}
    }
	
	
	public function prepareis()
    {
		$users_table = TableRegistry::get('erp_users');
		$ceo_department = $users_table->find()->where(array('role'=>'ceo'));
		$this->set('ceo_department',$ceo_department);
		$user_action = 'insert';
		$this->set('form_header','PREPARE ISSUE SLIP (I.S.)');
		$this->set('button_text','Prepare I.S');
		$this->set('user_action',$user_action);	
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id"=>0]);
		$this->set('material_list',$material_list);
		$erp_inventory_is = TableRegistry::get('erp_inventory_is'); 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);	
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		/* $this->set('agency_list',$agency_list); */
		
		/*$asset_list = $this->ERPfunction->get_asset_by_fix_group();
		if(!empty($asset_list))
		{
			/* $asset_list = array_map(function($val){return "asst_{$val}";},$asset_list); */
			/*foreach ($asset_list as $key => $val) {
				$assets['asst_'.$key] = $val;
				unset($asset_list[$key]);
			}
		
		
		$agency_assets = array_merge($agency_list,$assets);	
		$this->set('agency_assets',$agency_assets);
		}else{
			$this->set('agency_assets',$agency_list);
		}*/
		$this->set('vendor_assets',$vendor_list);

		if($this->request->is('post'))
		{
			$post = $this->request->data();
			$project_id = $post["project_id"];
			$materials = $post["material"]["material_id"];
			$quantity = $post["material"]["quantity"];
			$error = false;
			foreach($materials as $key=>$mid)
			{
				$balance = $this->ERPfunction->get_current_stock($project_id,$mid);
				if($quantity[$key] > $balance)
				{
					$m = $this->ERPfunction->get_material_title($mid);
					$this->Flash->error("ERROR : Quantity is more than its balance({$balance}) for material {$m},Please Try again");
					$error = true;
				}				
			}
			
			if($error)
			{
				return $this->redirect(["controller"=>"Inventory","action"=>"prepareis"]);							
			}
			
			// Create IS No. code start
			$project_id = $this->request->data['project_id'];
			$project_code = $this->ERPfunction->get_projectcode($project_id);
			
			$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_is","is_id","is_no");
		
			$new_isno = sprintf("%09d", $number1);
			$is_no = $project_code.'/IS/'.$new_isno;
			// Create IS No. code end
		
			$this->request->data['is_no'] = $is_no;			
			$this->request->data['is_date'] = $this->ERPfunction->set_date($this->request->data['is_date']);			
			$this->request->data['created_date']=date('Y-m-d H:i:s');			
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			//$this->request->data['last_edit']=date('Y-m-d H:i:s');			
			//$this->request->data['last_edit_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;
			
			$entity_data = $erp_inventory_is->newEntity();			
			$post_data=$erp_inventory_is->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_is->save($post_data))
			{
				$this->Flash->success(__('Record Insert Successfully with IS No. '.$is_no, null), 
							'default', 
							array('class' => 'success'));
				$is_id = $post_data->is_id;
				
				$this->ERPfunction->add_inventory_is_detail($this->request->data['material'],$is_id,$this->request->data['project_id'],$this->request->data['is_date']);			
			}
			$this->redirect(array("controller" => "Inventory","action" => "prepareis"));		
		}		
    }
	
	public function updateis($is_id,$searched_project = null)
	{
		$is_tbl = TableRegistry::get("erp_inventory_is");
		$is_detail_tbl = TableRegistry::get("erp_inventory_is_detail");
		$data = $is_tbl->find()->where(["is_id"=>$is_id])->hydrate(false)->toArray();
		$data = $data[0];
		$this->set('data',$data);
		
		$this->set('searched_project',$searched_project);
		
		$materials = $is_detail_tbl->find()->where(["is_id"=>$is_id])->hydrate(false)->toArray();
		$this->set('materials',$materials);
				
		$users_table = TableRegistry::get('erp_users');
		$ceo_department = $users_table->find()->where(array('role'=>'ceo'));
		$this->set('ceo_department',$ceo_department);
		$user_action = 'insert';
		$this->set('form_header','Update ISSUE SLIP (I.S.)');
		$this->set('button_text','Update I.S');
		$this->set('user_action',$user_action);	
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);		
		$erp_inventory_is = TableRegistry::get('erp_inventory_is'); 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);	
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find()->toArray();
		// debug($vendor_list);die;
		$this->set('vendor_list',$vendor_list);

		$ast_tbl = TableRegistry::get("erp_assets");
		$assets = $ast_tbl->find()->toArray();
		$this->set('assets',$assets);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			// debug($post);die;
			$material_items = $post["material"];
			
			$is_stock_nagative = 0;
			foreach($material_items['material_id'] as $key => $data)
			{
				$available_stock = $this->ERPfunction->get_current_stock($post['project_id'],$material_items['material_id'][$key]);
				$old_qty = $material_items['old_quantity'][$key];
				$difference = $old_qty - $material_items['quantity'][$key];
				
				$stock_after = $available_stock + $difference;
				if($stock_after < 0)
				{
					$m = $this->ERPfunction->get_material_title($material_items['material_id'][$key]);
					$this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
					return $this->redirect($this->referer());
				}
			}
			$post["is_date"] = date("Y-m-d",strtotime($post["is_date"]));
			$row = $is_tbl->get($is_id);
			$post["last_edit_by"] = $this->user_id;
			$post["last_edit"] = date("Y-m-d H:i:s");
		
			$is_tbl->patchEntity($row,$post);
			
			$is_tbl->save($row);
			
			// if(!empty($post["old_detail_id"]))
			// {
				// $query = $is_detail_tbl->query();
				// $query->delete()->where(["is_detail_id IN"=>$post["old_detail_id"]])->execute();
				
			// }
			//$this->ERPfunction->add_inventory_is_detail($post['material'],$is_id,$post['project_id']);
			
			foreach($material_items['material_id'] as $key => $data)
			{			
				$save_data['is_id'] =  $is_id;
				$save_data['material_id'] =  $material_items['material_id'][$key];
				$save_data['quantity'] =  $material_items['quantity'][$key];
				$save_data['balance'] =  $material_items['balance'][$key];
				/* $save_data['brand_id'] = $material_items['brand_id'][$key]; */
				// $save_data['name_of_receiver'] =  $material_items['name_of_receiver'][$key];
				$save_data['name_of_foreman'] =  $material_items['name_of_foreman'][$key];
				$save_data['time_issue'] =  $material_items['time_issue'][$key];
				// $save_data['site_reference'] =  $material_items['site_reference'][$key];
				
				if(isset($material_items['detail_id'][$key]))
				{
					$entity_data = $is_detail_tbl->get($material_items['detail_id'][$key]);
				}
				else{
					$save_data['approved'] =  1;
					$save_data['approved_date'] =  date('Y-m-d');
					$save_data['approved_by'] =  $this->request->session()->read('user_id');
					$entity_data = $is_detail_tbl->newEntity();
				}
				$material_data=$is_detail_tbl->patchEntity($entity_data,$save_data);
				$is_detail_tbl->save($material_data);
				$is_detail_id = $material_data->is_detail_id;
				//For history table update
				$history_tbl = TableRegistry::get("erp_stock_history");
				if(isset($material_items['detail_id'][$key]))
				{
					$hstry_query = $history_tbl->query();
					$hstry_query->update()
						->set(["project_id"=>$post['project_id'],"date"=>$post["is_date"],"material_id"=>$material_items['material_id'][$key],"quantity"=>$material_items["quantity"][$key],"stock_out"=>$material_items["quantity"][$key]])
						->where(["detail_id"=>$material_items['detail_id'][$key],"type"=>'is'])
						->execute();
				}
				else{
					$history_row = $history_tbl->newEntity();
					$insert["date"] = $post["is_date"];
					$insert["project_id"] = $post['project_id'];
					$insert["material_id"] = $material_items['material_id'][$key];
					$insert["quantity"] = $material_items['quantity'][$key];
					$insert["stock_out"] = $material_items['quantity'][$key];		
					$insert["type"] = "is";
					$insert["type_id"] = $is_id;
					$insert["detail_id"] = $is_detail_id;
					$history_row = $history_tbl->patchEntity($history_row,$insert);
					$history_tbl->save($history_row);
				}
				
				//For stock table update
				
				$stock_tbl = TableRegistry::get("erp_stock");
				$check_stock = $stock_tbl->find("all")->where(["project_id"=>$post['project_id'],"material_id"=>$material_items['material_id'][$key]])->hydrate(false)->toArray();
					
				if(isset($material_items['detail_id'][$key]))
				{
					$new_quentity = $material_items["quantity"][$key];
					$old_quentity = $material_items["old_quantity"][$key];
					if($new_quentity > $old_quentity)
					{
						$difference = $new_quentity - $old_quentity; // For add difference in stock table		
						if(!empty($check_stock))
						{			
							$query = $stock_tbl->query();
							$query->update()
								->set(['quantity' => $check_stock[0]["quantity"] + intval($difference)])
								->where(['project_id' => $post['project_id'],'material_id'=>$material_items["material_id"][$key]])
								->execute();
						}
					}
					else if($new_quentity < $old_quentity)
					{
						$difference = $old_quentity - $new_quentity; // For add difference in stock table		
						if(!empty($check_stock))
						{			
							$query = $stock_tbl->query();
							$query->update()
								->set(['quantity' => $check_stock[0]["quantity"] - intval($difference)])
								->where(['project_id' => $post['project_id'],'material_id'=>$material_items["material_id"][$key]])
								->execute();
						}
					}
				}
				else{
					$stock_row = $stock_tbl->newEntity();
					$stock_data["project_id"] = $post['project_id'];
					$stock_data["material_id"] = $material_items['material_id'][$key];
					$stock_data["quantity"] = $material_items['quantity'][$key];			
					$stock_row = $stock_tbl->patchEntity($stock_row,$stock_data);
					$stock_tbl->save($stock_row);
				}
				
			}
			$this->Flash->success(__('Record Updated Successfully', null), 
							'default', 
							array('class' => 'success'));			
			// die;
			//$this->redirect(array("controller" => "Inventory","action" => "approveis"));
			echo "<script>window.close();</script>";
		}
	}
	
	public function approveis($searched_project = null)
    {
		$is_list = $this->Usermanage->fetch_approve_is_details($this->user_id);
		/* $this->set('is_list',$is_list);	 */
		$this->set('role',$this->role);	
	
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$conn = ConnectionManager::get('default');
		
		$erp_inventory_is = TableRegistry::get('erp_inventory_is'); 
		
		if($this->request->is("post") || $searched_project != null)
		{ 
			$post = $this->request->data;
			$searched_project = isset($post["project_id"]) ? $post["project_id"] : $searched_project;
			if($searched_project == "All" || $searched_project == "all")
			{
				$is_list = $conn->execute('select * from  erp_inventory_is right join 
				erp_inventory_is_detail ON erp_inventory_is.is_id = erp_inventory_is_detail.is_id
				where erp_inventory_is_detail.approved = 0');
			
			}else{			
				$is_list = $conn->execute('select * from  erp_inventory_is right join 
				erp_inventory_is_detail ON erp_inventory_is.is_id = erp_inventory_is_detail.is_id
				where erp_inventory_is.project_id = '.$searched_project.' and erp_inventory_is_detail.approved = 0');
			}

			$this->set('searched_project',$searched_project); 			
			$this->set('is_list',$is_list); 			
		}		
			
    }
	
	public function isaudit()
    {
		$project = isset($_REQUEST['project'])?$_REQUEST['project']:'';
		$erp_is_audit = TableRegistry::get('erp_is_audit');
		$erp_audit_is_detail = TableRegistry::get('erp_audit_is_detail');
		if($project != '')
		{
			$user = $this->request->session()->read('user_id');
			$projects_ids = $this->Usermanage->users_project($user);
			$role = $this->role;
			// debug($post);die;
			$or = array();				
			
			$or["erp_is_audit.project_id"] = (!empty($project) && $project != "All" )?$project:NULL;
			
			if($or["erp_is_audit.project_id"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["erp_is_audit.project_id IN"] = $projects_ids;
				}
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			############################# Inner Join Query #############################################
			if(!empty($or))
			{
				$result = $erp_is_audit->find()->select($erp_is_audit)->order(['erp_is_audit.is_date'=>'DESC']);
				$result = $result->innerjoin(
					["erp_audit_is_detail"=>"erp_audit_is_detail"],
					["erp_is_audit.audit_is_id = erp_audit_is_detail.is_audit_id"])
					->where([$or])->select($erp_audit_is_detail)->hydrate(false)->toArray();
			}else{
				$result = $erp_is_audit->find()->select($erp_is_audit)->order(['erp_is_audit.is_date'=>'DESC']);
				$result = $result->innerjoin(
					["erp_audit_is_detail"=>"erp_audit_is_detail"],
					["erp_is_audit.audit_is_id = erp_audit_is_detail.is_audit_id"])
					->select($erp_audit_is_detail)->hydrate(false)->toArray();
			}
			
				
			$new_array = array();
			foreach($result as $retrive)
			{
				if(isset($new_array[$retrive['is_no']]))
				{
					$new_array[$retrive['is_no']]['erp_audit_is_detail'][] = $retrive['erp_audit_is_detail'];
				}else{
					$a = $retrive["erp_audit_is_detail"];
					unset($retrive["erp_audit_is_detail"]);
					$new_array[$retrive["is_no"]] = $retrive;
					$new_array[$retrive["is_no"]]['erp_audit_is_detail'][] = $a;
				}
				
			}
			$is_list = $new_array;
			############################# Inner Join Query #############################################
			
			
			// if(!empty($or)){
				// $is_list = $erp_is_audit->find()->where([$or])->order(['is_date'=>'DESC'])->hydrate(false)->toArray();
			// }else{
				// $is_list = $erp_is_audit->find()->order(['is_date'=>'DESC'])->hydrate(false)->toArray();
			// }
			$this->set("is_list",$is_list);
			$this->set("project",$project);
		}
		$this->set('role',$this->role);	
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$conn = ConnectionManager::get('default');
		
		if($this->request->is("post"))
		{ 
			$post = $this->request->data;
			
			$user = $this->request->session()->read('user_id');
			$projects_ids = $this->Usermanage->users_project($user);
			$role = $this->role;
			// debug($post);die;
			$or = array();				
			
			$or["erp_is_audit.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
			
			if($or["erp_is_audit.project_id"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["erp_is_audit.project_id IN"] = $projects_ids;
				}
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			############################# Inner Join Query #############################################
			if(!empty($or))
			{
				$result = $erp_is_audit->find()->select($erp_is_audit)->order(['erp_is_audit.is_date'=>'DESC']);
				$result = $result->innerjoin(
					["erp_audit_is_detail"=>"erp_audit_is_detail"],
					["erp_is_audit.audit_is_id = erp_audit_is_detail.is_audit_id"])
					->where([$or])->select($erp_audit_is_detail)->hydrate(false)->toArray();
			}else{
				$result = $erp_is_audit->find()->select($erp_is_audit)->order(['erp_is_audit.is_date'=>'DESC']);
				$result = $result->innerjoin(
					["erp_audit_is_detail"=>"erp_audit_is_detail"],
					["erp_is_audit.audit_is_id = erp_audit_is_detail.is_audit_id"])
					->select($erp_audit_is_detail)->hydrate(false)->toArray();
			}
			
				
			$new_array = array();
			foreach($result as $retrive)
			{
				if(isset($new_array[$retrive['is_no']]))
				{
					$new_array[$retrive['is_no']]['erp_audit_is_detail'][] = $retrive['erp_audit_is_detail'];
				}else{
					$a = $retrive["erp_audit_is_detail"];
					unset($retrive["erp_audit_is_detail"]);
					$new_array[$retrive["is_no"]] = $retrive;
					$new_array[$retrive["is_no"]]['erp_audit_is_detail'][] = $a;
				}
				
			}
			$is_list = $new_array;
			############################# Inner Join Query #############################################
			
			// if(!empty($or)){
				// $is_list = $erp_is_audit->find()->where([$or])->order(['is_date'=>'DESC'])->hydrate(false)->toArray();
			// }else{
				// $is_list = $erp_is_audit->find()->order(['is_date'=>'DESC'])->hydrate(false)->toArray();
			// }
			// debug($is_list);die;
			$this->set("is_list",$is_list);
			$this->set("project",$post["project_id"]);
			
			 			
		}		
			
    }
	
	public function viewis()
    {
		$erp_inventory_is = TableRegistry::get("erp_inventory_is");
		$erp_inventory_is_detail = TableRegistry::get("erp_inventory_is_detail");
		$this->set('role',$this->role);
		
		// $vendor_tbl = TableRegistry::get("erp_vendor");
		// $vendor_list = $vendor_tbl->find()->toArray();
		
		$ast_tbl = TableRegistry::get("erp_assets");
		$assets = $ast_tbl->find()->toArray();
		// $this->set('vendor_list',$vendor_list);
		$this->set('assets',$assets);
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$user = $this->request->session()->read('user_id');
	
		$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		
		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);		
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data["export_csv"]))
			{
				ini_set("memory_limit","2000M");
				$or = array();				
				$post = $this->request->data();
				$or["erp_inventory_is.project_id IN"] = (!empty($post["f_pro_id"]) && $post["f_pro_id"] != "All" )?$post["f_pro_id"]:NULL;
				$or["erp_inventory_is_detail.material_id IN"] = (!empty($post["f_material_id"]) && $post["f_material_id"] != "All")?$post["f_material_id"]:NULL;
				$or["erp_inventory_is.agency_name IN"] = (!empty($post["f_agency_id"]) && $post["f_agency_id"] != "All")?$post["f_agency_id"]:NULL;
				$or["erp_inventory_is.is_date >="] = ($post["f_date_from"] != "")?date("Y-m-d",strtotime($post["f_date_from"])):NULL;
				$or["erp_inventory_is.is_date <="] = ($post["f_date_to"] != "")?date("Y-m-d",strtotime($post["f_date_to"])):NULL;
				$or["erp_inventory_is.is_no ="] = (!empty($post["f_is_no"]))?$post["f_is_no"]:NULL;
								
				$or["erp_inventory_is_detail.approved ="] = 1;
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids))
					{
						$result = $erp_inventory_is->find()->select($erp_inventory_is)->where(['project_id in'=>$projects_ids]);
						$result = $result->innerjoin(
							["erp_inventory_is_detail"=>"erp_inventory_is_detail"],
							["erp_inventory_is.is_id = erp_inventory_is_detail.is_id","erp_inventory_is_detail.approved"=>1])
							->where($or)->select($erp_inventory_is_detail)->hydrate(false)->toArray();
					}
					else
					{
						$result=array();
					}
				}
				else
				{
					$result = $erp_inventory_is->find()->select($erp_inventory_is);
						$result = $result->innerjoin(
							["erp_inventory_is_detail"=>"erp_inventory_is_detail"],
							["erp_inventory_is.is_id = erp_inventory_is_detail.is_id"])
							->where($or)->select($erp_inventory_is_detail)->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Project Name","I.S. No","Vendor or Asset Name","Date","Material Name","Quantity","Unit","Name of Foreman");
			
				foreach($result as $retrive_data)
				{
					if(isset($retrive_data["erp_inventory_is_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_is_detail"]);
					}
										
					$csv = array();		
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $retrive_data['is_no'];
					$is_asset = explode("_",$retrive_data['agency_name']);
					if(isset($is_asset[1]))
					{
						$csv[] = $this->ERPfunction->get_asset_name($is_asset[1]);
					}else{
						$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['agency_name']);
					}
					$csv[] = date('d-m-Y',strtotime($retrive_data['is_date']));
					
					$details = $this->ERPfunction->get_approveis_details($retrive_data['is_id']);
					
					$csv[] = $this->ERPfunction->get_material_title($retrive_data["material_id"]);
					$csv[] = $retrive_data["quantity"];
					$csv[] = $this->ERPfunction->get_items_units($details["material_id"]);
					$csv[] = $retrive_data["name_of_foreman"];
					$rows[] = $csv;
				}
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				
				$filename = "issue_slip_list.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				ini_set('memory_limit', '2000M');
				$erp_inventory_is = TableRegistry::get("erp_inventory_is");
				$erp_inventory_is_detail = TableRegistry::get("erp_inventory_is_detail");
				$or = array();				
				$post = $this->request->data();
				$or["erp_inventory_is.project_id IN"] = (!empty($post["f_pro_id"]) && $post["f_pro_id"] != "All" )?$post["f_pro_id"]:NULL;
				$or["erp_inventory_is_detail.material_id IN"] = (!empty($post["f_material_id"]) && $post["f_material_id"] != "All")?$post["f_material_id"]:NULL;
				$or["erp_inventory_is.agency_name IN"] = (!empty($post["f_agency_id"]) && $post["f_agency_id"] != "All")?$post["f_agency_id"]:NULL;
				$or["erp_inventory_is.is_date >="] = ($post["f_date_from"] != "")?date("Y-m-d",strtotime($post["f_date_from"])):NULL;
				$or["erp_inventory_is.is_date <="] = ($post["f_date_to"] != "")?date("Y-m-d",strtotime($post["f_date_to"])):NULL;
				$or["erp_inventory_is.is_no ="] = (!empty($post["f_is_no"]))?$post["f_is_no"]:NULL;
								
				$or["erp_inventory_is_detail.approved ="] = 1;
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids))
					{
						$result = $erp_inventory_is->find()->select($erp_inventory_is)->where(['project_id in'=>$projects_ids]);
						$result = $result->innerjoin(
							["erp_inventory_is_detail"=>"erp_inventory_is_detail"],
							["erp_inventory_is.is_id = erp_inventory_is_detail.is_id","erp_inventory_is_detail.approved"=>1])
							->where($or)->select($erp_inventory_is_detail)->hydrate(false)->toArray();
					}
					else
					{
						$result=array();
					}
				}
				else
				{
					$result = $erp_inventory_is->find()->select($erp_inventory_is);
						$result = $result->innerjoin(
							["erp_inventory_is_detail"=>"erp_inventory_is_detail"],
							["erp_inventory_is.is_id = erp_inventory_is_detail.is_id"])
							->where($or)->select($erp_inventory_is_detail)->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Project Name","I.S. No","Vendor or Asset Name","Date","Material Name","Quantity","Unit","Name of Foreman");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_inventory_is_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_is_detail"]);
					}
										
					$pdf = array();		
					$pdf[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$pdf[] = $retrive_data['is_no'];
					$is_asset = explode("_",$retrive_data['agency_name']);
					if(isset($is_asset[1]))
					{
						$pdf[] = $this->ERPfunction->get_asset_name($is_asset[1]);
					}else{
						$pdf[] = $this->ERPfunction->get_vendor_name($retrive_data['agency_name']);
					}
					$pdf[] = date('d-m-Y',strtotime($retrive_data['is_date']));
					
					$details = $this->ERPfunction->get_approveis_details($retrive_data['is_id']);
					
					$pdf[] = $this->ERPfunction->get_material_title($retrive_data["material_id"]);
					$pdf[] = $retrive_data["quantity"];
					$pdf[] = $this->ERPfunction->get_items_units($details["material_id"]);
					$pdf[] = $retrive_data["name_of_foreman"];
					$rows[] = $pdf;
				}
				
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				$this->set("rows",$rows);
				$this->render("viewispdf");
			}
		}
    }
	
	public function preparemrn()
    {
		$users_table = TableRegistry::get('erp_users');
		$ceo_department = $users_table->find()->where(array('role'=>'ceo'));
		$this->set('ceo_department',$ceo_department);
		$user_action = 'insert';
		$this->set('form_header','Material Return Note (MRN)');
		$this->set('button_text','Prepare M.R.N.');
		$this->set('user_action',$user_action);	
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id"=>0]);
		$this->set('material_list',$material_list);		
		$erp_inventory_mrn = TableRegistry::get('erp_inventory_mrn'); 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);		
		
		if($this->request->is('post'))
		{		
			$project_id = $this->request->data["project_id"];
			$materials = $this->request->data["material"]["material_id"];
			$quantity = $this->request->data["material"]["quantity"];
			$error = false;
			foreach($materials as $key=>$mid)
			{
				$balance = $this->ERPfunction->get_current_stock($project_id,$mid);
				if($quantity[$key] > $balance)
				{
					$m = $this->ERPfunction->get_material_title($mid);
					$this->Flash->error("ERROR : Quantity is more than its balance({$balance}) for material {$m},Please Try again");
					$error = true;
				}				
			}
			
			if($error)
			{
				return $this->redirect(["controller"=>"Inventory","action"=>"approvemrn"]);							
			}
			
			//MRN No
			$code = $this->ERPfunction->get_projectcode($project_id);
			$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_mrn","mrn_id","mrn_no");
			/* $new_grnno = sprintf("%09d", $prepare_count + 1); */
			$new_mrnno = sprintf("%09d", $number1);
			$mrn_no = $code.'/MRN/'.$new_mrnno;
			
			$this->request->data['mrn_no'] = $mrn_no;
			$this->request->data['mrn_date'] = $this->ERPfunction->set_date($this->request->data['mrn_date']);			
			$this->request->data['created_date']=date('Y-m-d H:i:s');			
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;	
			
			$entity_data = $erp_inventory_mrn->newEntity();			
			$post_data=$erp_inventory_mrn->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_mrn->save($post_data))
			{
				$this->Flash->success(__('Record Insert Successfully with MRN No. '.$mrn_no, null), 
							'default', 
							array('class' => 'success'));
				$mrn_id = $post_data->mrn_id;
				
				$this->ERPfunction->add_inventory_mrn_detail($this->request->data['material'],$mrn_id);			
			}
			$this->redirect(array("controller" => "Inventory","action" => "approvemrn"));		
		}	
    }
	 public function approvemrn()
    {
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		//$mrn_list = $this->Usermanage->fetch_approve_mrn($this->user_id);
		//$this->set('mrn_list',$mrn_list);
		$this->set('role',$this->role);
		
		$user = $this->request->session()->read('user_id');
	
		$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		// debug($mrn_list->fetchAll("assoc"));die;
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data['go']))
			{
				$data = $this->request->data;
				$erp_inventory_mrn = TableRegistry::get('erp_inventory_mrn');
				$erp_inventory_mrn_detail = TableRegistry::get('erp_inventory_mrn_detail');
				$conn = ConnectionManager::get('default');
				if($this->Usermanage->project_alloted($role)==1){ 
					$result = $conn->execute('select * from  erp_inventory_mrn as a left join erp_inventory_mrn_detail as b ON a.mrn_id = b.mrn_id where b.approved = 0 AND approve_executives = 0 AND project_id = '.$data['project_id'].' AND	project_id in ('.implode(',',$projects_ids ).')');	
				}
				else
				{
					$result = $conn->execute('select * from  erp_inventory_mrn as a left join erp_inventory_mrn_detail as b ON a.mrn_id = b.mrn_id where b.approved = 0 AND approve_executives = 0 AND a.project_id = '.$data['project_id']);
				}
				$this->set('mrn_list',$result);
			}
		}
    }
	public function viewmrn()
    {		
		$this->set('role',$this->role);
	
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$user = $this->request->session()->read('user_id');
	
		$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data['go1']))
			{			
				$erp_inventory_mrn = TableRegistry::get("erp_inventory_mrn");
				$erp_inventory_mrn_detail = TableRegistry::get("erp_inventory_mrn_detail");
				$post = $this->request->data;	
				$or = array();				
				
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["vendor_user IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All" )?$post["vendor_userid"]:NULL;
				//$or["erp_inventory_grn.payment_method IN"] = (!empty($post["payment_mod"]) && $post["payment_mod"][0] != "All" )?$post["payment_mod"]:NULL;
				//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
				$or["erp_inventory_mrn_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All")?$post["material_id"]:NULL;
				//$or["erp_inventory_grn.vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All")?$post["vendor_userid"]:NULL;
				$or["mrn_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["mrn_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["mrn_no"] = (!empty($post["mrn_no"]))?$post["mrn_no"]:NULL;
				//$or["erp_inventory_grn.challan_no"] = (!empty($post["challan_no"]))?$post["challan_no"]:NULL;
				$or["erp_inventory_mrn_detail.approved"] = 1;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids))
					{
						$result = $erp_inventory_mrn->find()->select($erp_inventory_mrn)->where(['project_id in'=>$projects_ids]);
						$result = $result->innerjoin(
							["erp_inventory_mrn_detail"=>"erp_inventory_mrn_detail"],
							["erp_inventory_mrn.mrn_id = erp_inventory_mrn_detail.mrn_id"])
							->where($or)->select($erp_inventory_mrn_detail)->hydrate(false)->toArray();
							//var_dump($result);die;
						//$this->set('grn_list',$result);
					}
					else
					{
						$result=array();
					}
				}
				else
				{
					$result = $erp_inventory_mrn->find()->select($erp_inventory_mrn);
						$result = $result->innerjoin(
							["erp_inventory_mrn_detail"=>"erp_inventory_mrn_detail"],
							["erp_inventory_mrn.mrn_id = erp_inventory_mrn_detail.mrn_id"])
							->where($or)->select($erp_inventory_mrn_detail)->hydrate(false)->toArray();
							//var_dump($result);die;
						//$this->set('grn_list',$result);
				}
				$this->set('mrn_list',$result);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				// $rows = unserialize($this->request->data["rows"]);
				// debug($this->request->data["rows"]);die;
				$post = $this->request->data();	
				$or = array();				
				$or["erp_inventory_mrn.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_inventory_mrn_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_inventory_mrn.vendor_user IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_inventory_mrn.mrn_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_inventory_mrn.mrn_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_inventory_mrn.mrn_no ="] = (!empty($post["e_mrn_no"]))?$post["e_mrn_no"]:NULL;
				
				if($or["erp_inventory_mrn.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						
						$or["erp_inventory_mrn.project_id IN"] = $projects_ids;
						
					}
				}
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_inventory_mrn_detail.approved ="] = 1;
				
				$erp_inventory_mrn = TableRegistry::get("erp_inventory_mrn");
				$erp_inventory_mrn_detail = TableRegistry::get("erp_inventory_mrn_detail");
				
				$result = $erp_inventory_mrn->find()->select($erp_inventory_mrn);
				$result = $result->innerjoin(
					["erp_inventory_mrn_detail"=>"erp_inventory_mrn_detail"],
					["erp_inventory_mrn.mrn_id = erp_inventory_mrn_detail.mrn_id"])
					->where($or)->select($erp_inventory_mrn_detail)->hydrate(false)->toArray();
							
				$rows = array();
				$rows[] = array("Project Name","M.R.N No","Date","Time","Vendor Name","Material Name","Make/Source","Returned Quantity","Unit");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_inventory_mrn_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_mrn_detail"]);
					}
					$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
					$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
					$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					
					$csv = array();		
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $retrive_data['mrn_no'];
					$csv[] = date('d-m-Y',strtotime($retrive_data['mrn_date']));
					$csv[] = $retrive_data['mrn_time'];
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_user']);
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $retrive_data['quantity'];
					$csv[] = $static_unit;
					$rows[] = $csv;
				}
				
				$filename = "approvemrnlist.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				// $rows = unserialize($this->request->data["rows"]);
				$post = $this->request->data();	
				$or = array();				
				$or["erp_inventory_mrn.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_inventory_mrn_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_inventory_mrn.vendor_user IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_inventory_mrn.mrn_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_inventory_mrn.mrn_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_inventory_mrn.mrn_no ="] = (!empty($post["e_mrn_no"]))?$post["e_mrn_no"]:NULL;
				
				if($or["erp_inventory_mrn.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						
						$or["erp_inventory_mrn.project_id IN"] = $projects_ids;
						
					}
				}
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_inventory_mrn_detail.approved ="] = 1;
				
				$erp_inventory_mrn = TableRegistry::get("erp_inventory_mrn");
				$erp_inventory_mrn_detail = TableRegistry::get("erp_inventory_mrn_detail");
				
				$result = $erp_inventory_mrn->find()->select($erp_inventory_mrn);
				$result = $result->innerjoin(
					["erp_inventory_mrn_detail"=>"erp_inventory_mrn_detail"],
					["erp_inventory_mrn.mrn_id = erp_inventory_mrn_detail.mrn_id"])
					->where($or)->select($erp_inventory_mrn_detail)->hydrate(false)->toArray();
							
				$rows = array();
				$rows[] = array("Project Name","M.R.N No","Date","Time","Vendor Name","Material Name","Make/Source","Returned Quantity","Unit");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_inventory_mrn_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_mrn_detail"]);
					}
					$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
					$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
					$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					
					$csv = array();		
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $retrive_data['mrn_no'];
					$csv[] = date('d-m-Y',strtotime($retrive_data['mrn_date']));
					$csv[] = $retrive_data['mrn_time'];
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_user']);
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $retrive_data['quantity'];
					$csv[] = $static_unit;
					$rows[] = $csv;
				}
				$this->set("rows",$rows);
				$this->render("viewmrnpdf");
			}
		}
		
    }
	
	public function unapprovemrn($detail_id)
	{
		$erp_inventory_mrn = TableRegistry::get('erp_inventory_mrn');
		$erp_inventory_mrn_detail = TableRegistry::get('erp_inventory_mrn_detail');
		
		$row = $erp_inventory_mrn_detail->get($detail_id);
		$material_id = $row->material_id;
		$mrn_id = $row->mrn_id;
		$row->approved = 0;
		$erp_inventory_mrn_detail->save($row);
		
		$row1 = $erp_inventory_mrn->get($mrn_id);
		$project_id = $row1->project_id;
		$row1['approve_executives'] = 0;
		
		if($erp_inventory_mrn->save($row1))
		{
			$this->ERPfunction->delete_stock_entry("mrn",$mrn_id,$project_id,$material_id);
			$this->Flash->success(__('Record Unapprove Successfully', null), 
						'default', 
						array('class' => 'success'));
			$this->redirect(array("controller" => "Inventory","action" => "viewmrn"));	
		}
		
	}
	
	public function unapprovesst($detail_id)
	{
		$erp_inventory_sst = TableRegistry::get('erp_inventory_sst');
		$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail');
		$row = $erp_inventory_sst_detail->find()->where(['sst_detail_id'=>$detail_id])->hydrate(false)->toArray();
		$material_id = $row[0]['material_id'];
		$sst_id = $row[0]['sst_id'];
		
		$row1 = $erp_inventory_sst->get($sst_id);
		$project_id = $row1->project_id;
		$row1['approved_status'] = 0;
		
		if($erp_inventory_sst->save($row1))
		{
			$this->ERPfunction->delete_stock_entry("sst",$sst_id,$project_id,$material_id);
			$this->Flash->success(__('Record Unapprove Successfully', null), 
						'default', 
						array('class' => 'success'));
			$this->redirect(array("controller" => "Inventory","action" => "viewsst"));	
		}
		
	}
	
	public function viewrecords()
    {
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}elseif (strpos($previous_url, 'contract') !== false) {
			$back_url = 'contract';
			$back_page = 'billingmenu';
		}else{
			$back_url = 'Inventory';
			$back_page = 'index';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		if($this->role == "deputymanagerelectric")
		{
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}
		
		$role = $this->role;
		$this->set('projects',$projects);
		$erp_stock_tab = TableRegistry::get('erp_stock');
		if($this->role == "deputymanagerelectric")
		{
			
			$meterial_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$meterial_ids = json_decode($meterial_ids);
			$result_stockdata = $erp_stock_tab->find()->where(["material_id IN"=>$meterial_ids])->select(["material_id","material_name"])->hydrate(false)->toArray();
		}elseif($role =='projectdirector' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager' || $role =='projectcoordinator' || $role =='erpoperator'){
			$meterial_ids = $this->ERPfunction->get_user_material_id($this->user_id);
			$meterial_ids = json_decode($meterial_ids);
			$result_stockdata = $erp_stock_tab->find()->where(["material_id IN"=>$meterial_ids])->select(["material_id","material_name"])->hydrate(false)->toArray();
			
		}else{
			$result_stockdata = $erp_stock_tab->find()->select(["material_id","material_name"])->hydrate(false)->toArray();
		}
		// debug($result_stockdata);die;
		$erp_material_sub_group = TableRegistry::get('erp_material_sub_group');
		$material_sub_group = $erp_material_sub_group->find()->hydrate(false)->toArray();
		
		$this->set('material_sub_group',$material_sub_group);
		$this->set('sl_data',$result_stockdata);
		$this->set('role',$this->role);
		$conn = ConnectionManager::get('default');
		$erp_stock_history_tbl = TableRegistry::get("erp_stock_history");
		// $request_list = $conn->execute('select *,SUM(stock_in) as total_stock_in,
		// SUM(stock_out) as total_stock_out
		// from erp_stock_history 
		// group by project_id,material_id,material_name');	
		
		// $this->set('result',$request_list);
		if($this->request->is("post"))
		{
			$user = $this->request->session()->read('user_id');
			$projects_ids = $this->Usermanage->users_project($user);
			/*
			if(isset($this->request->data['go']))
			{
			$post = $this->request->data;
			// debug($post);//die;
			$erp_stock_history_tbl = TableRegistry::get("erp_stock_history");
			
			$or = array();
			$orwhere_name = array();
			$orwhere_id = array();
			$material_name = array();
			$material_id = array();
			
			$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
			if(!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"][0] != "All")
			{
			foreach($post["sl_mrn_name"] as $retrive)
			{
				if(is_numeric($retrive))
				{
				 $material_id[] = $retrive;
				}
				else
				{
				 $material_name[] = $retrive;	
				}
			}
			$orwhere_id["material_id IN"] = (!empty($material_id)) ? $material_id : NULL ;
			$orwhere_name["material_name IN"] = (!empty($material_name)) ? $material_name : NULL;
			}
			//$or["asset_id LIKE"] = (!empty($post["asset_id"]))?"%{$post["asset_id"]}%":NULL;
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			
			$keys1 = array_keys($orwhere_id,"");				
			foreach ($keys1 as $k1)
			{unset($orwhere_id[$k1]);}
			
			$keys2 = array_keys($orwhere_name,"");				
			foreach ($keys2 as $k2)
			{unset($orwhere_name[$k2]);}
			 //debug($or);
			 //debug($orwhere_id);
			 //debug($orwhere_name);die;
			//array('fields'=>array('sum(stock_in) AS total_stock_in')) 
			$result = $erp_stock_history_tbl->find('all');
			if(!empty($or) || !empty($orwhere_id) || !empty($orwhere_name))
			{
				if(!empty($orwhere_id))
				{
					$result= $result->where([$or])
					->andWhere([$orwhere_id])
					->orWhere([$orwhere_name])
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
				}
				elseif(!empty($orwhere_name))
				{
					$result= $result->where([$or])
					->andWhere([$orwhere_name])
					->orWhere([$orwhere_id])
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
				}
				else
				{
					$result= $result->where([$or])
					->andWhere([$orwhere_id])
					->orWhere([$orwhere_name])
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
				}
				
			}
			else
			{
				$result= $result
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
			}
			
					//debug($result);die;
			$this->set("result",$result);
			}*/
			if(isset($this->request->data["export_csv"]))
			{
				// $rows = unserialize($this->request->data["rows"]);
				$erp_material = TableRegistry::get("erp_material");
				$or = array();				
				$post = $this->request->data();
				
				$pro_id = ($post["f_pro_id"] != '')?explode(",",$post["f_pro_id"]):array();
				$mat_id = ($post["f_material_id"] != '')?explode(",",$post["f_material_id"]):array();
				$consume = ($post["f_consume"] != '')?explode(",",$post["f_consume"]):array();
				$material_sub_group = ($post["f_material_sub_group"] != '')?explode(",",$post["f_material_sub_group"]):array();
				$cost_group = ($post["f_cost_group"] != '')?explode(",",$post["f_cost_group"]):array();
				
				$or["erp_stock_history.project_id IN"] = (!empty($pro_id) && $pro_id[0] != "All" )?$pro_id:NULL;
				$or["erp_stock_history.material_id IN"] = (!empty($mat_id) && $mat_id[0] != "All")?$mat_id:NULL;
				$or["erp_stock_history.min_quantity ="] = (!empty($post["f_minimum_stock"]))?$post["f_minimum_stock"]:NULL;
				$or["erp_stock_history.max_quantity ="] = (!empty($post["f_maximum_purchase"]))?$post["f_maximum_purchase"]:NULL;
				
				$or["erp_material.consume IN"] = (!empty($consume) && $consume[0] != "All" )?$consume:NULL;
				$or["erp_material.material_sub_group IN"] = (!empty($material_sub_group) && $material_sub_group[0] != "All" )?$material_sub_group:NULL;
				$or["erp_material.cost_group IN"] = (!empty($cost_group) && $cost_group[0] != "All" )?$cost_group:NULL;
				
				if($or["erp_stock_history.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						$or["erp_stock_history.project_id IN"] = $projects_ids;
					}
				}
				// if($or["erp_stock_history.material_id IN"] == NULL)
				// {
					// if($role == "deputymanagerelectric")
					// {
						// $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
						// $material_ids = json_decode($material_ids);
						// $or["erp_stock_history.material_id IN"] = $material_ids;
					// }
					
					// if($role =='projectdirector' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager' || $role =='projectcoordinator' || $role =='erpoperator')
					// {
						// $material_ids = $this->ERPfunction->get_user_material_id($user);
						// $or["erp_stock_history.material_id IN"] = $material_ids;
					// }
				// }
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($or);die;
				if(!empty($or))
				{
					
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_stock_history_tbl)
					->select($erp_material)
					->where($or)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}else{
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_material)
					->select($erp_stock_history_tbl)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}
				// debug($result);die;
				$rows = array();
				$rows[] = array("Project Name","Material Code","Material Name",'Consume Type','Cost Group',"Max Purchase Level","Total Stock In","Total Stock Out","Symbolic Balance","Current Balance","Min Stock Level","Unit");
			
				foreach($result as $retrive_data)
				{				
					$csv = array();
					if($retrive_data['material_id'] != 0)
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$m_id = $retrive_data['material_id'];
					}
					else
					{
						$mt = $retrive_data['material_name'];
						$m_id = $retrive_data['material_name'];
					}
					$consume_value = $retrive_data['erp_material']['consume'];
					if($consume_value == 1)
					{
						$consume_type = "Consumable";
					}elseif($consume_value == 0){
						$consume_type = "Retunable / Non-consumable";
					}elseif($consume_value == 3){
						$consume_type = "Asset";
					}else{
						$consume_type = "";
					}
										
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_materialitemcode($retrive_data['material_id']);
					$csv[] = $mt;
					$csv[] = $consume_type;
					$csv[] = ucfirst($retrive_data['erp_material']['cost_group']);
					$csv[] = $retrive_data['max_quantity'];
					// $csv[] = bcdiv($retrive_data['total_stock_in'],1,3);
					$csv[] = $this->ERPfunction->get_total_stockin($retrive_data['project_id'],$retrive_data['material_id']);
					// $csv[] = ($retrive_data['max_quantity'] != 0) ? bcdiv($retrive_data['total_stock_in']/$retrive_data['max_quantity'],1,3) : "NA";
					$csv[] = bcdiv($retrive_data['total_stock_out'],1,3);
					if($consume_value == 1)
					{
						$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					}else{
						$csv[] = bcdiv($this->ERPfunction->get_symbolic_stock($retrive_data['project_id'],$m_id),1,3);
					}
					
					$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					$csv[] = $retrive_data['min_quantity'];
					$csv[] = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					/* Check mismatch material with project */
					$flag = 1;
					$material_id=$retrive_data['material_id'];
					if($material_id)
					{
						$project_specific = $this->ERPfunction->is_material_projectspecific($material_id);
						
						if($project_specific)
						{
							$material_code = $this->ERPfunction->get_materialitemcode($material_id);
							$m_c = explode("/",$material_code);
							$m_c = ($m_c[3])?$m_c[3]:'';
							
							$project_id=$retrive_data['project_id'];
							$project_code = $this->ERPfunction->get_projectcode($project_id);
							$p_c = explode("/",$project_code);
							$p_c = ($p_c[2])?$p_c[2]:'';
							if($m_c != $p_c)
							{
								$flag = 0;
							}
						}
					}
					
					/* Check mismatch material with project */
					if($flag)
					{
						$rows[] = $csv;
					}
				}
				$filename = "viewrecords.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				// $rows = unserialize($this->request->data["rows"]);
				$erp_material = TableRegistry::get("erp_material");
				$or = array();				
				$post = $this->request->data();
				
				$pro_id = ($post["f_pro_id"] != '')?explode(",",$post["f_pro_id"]):array();
				$mat_id = ($post["f_material_id"] != '')?explode(",",$post["f_material_id"]):array();
				$consume = ($post["f_consume"] != '')?explode(",",$post["f_consume"]):array();
				$material_sub_group = ($post["f_material_sub_group"] != '')?explode(",",$post["f_material_sub_group"]):array();
				$cost_group = ($post["f_cost_group"] != '')?explode(",",$post["f_cost_group"]):array();
				
				$or["erp_stock_history.project_id IN"] = (!empty($pro_id) && $pro_id[0] != "All" )?$pro_id:NULL;
				$or["material_id IN"] = (!empty($mat_id) && $mat_id[0] != "All")?$mat_id:NULL;
				$or["min_quantity ="] = (!empty($post["f_minimum_stock"]))?$post["f_minimum_stock"]:NULL;
				$or["max_quantity ="] = (!empty($post["f_maximum_purchase"]))?$post["f_maximum_purchase"]:NULL;
				
				$or["erp_material.consume IN"] = (!empty($consume) && $consume[0] != "All" )?$consume:NULL;
				$or["erp_material.material_sub_group IN"] = (!empty($material_sub_group) && $material_sub_group[0] != "All" )?$material_sub_group:NULL;
				$or["erp_material.cost_group IN"] = (!empty($cost_group) && $cost_group[0] != "All" )?$cost_group:NULL;
				
				if($or["erp_stock_history.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						$or["erp_stock_history.project_id IN"] = $projects_ids;
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if(!empty($or))
				{
					
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_stock_history_tbl)
					->select($erp_material)
					->where($or)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}else{
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_material)
					->select($erp_stock_history_tbl)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Project Name","Material Code","Material Name",'Consume Type','Cost Group',"Max Purchase Level","Total Stock In","Total Stock Out","Symbolic Balance","Current Balance","Min Stock Level","Unit");
			
				foreach($result as $retrive_data)
				{				
					$csv = array();
					if($retrive_data['material_id'] != 0)
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$m_id = $retrive_data['material_id'];
					}
					else
					{
						$mt = $retrive_data['material_name'];
						$m_id = $retrive_data['material_name'];
					}
					$consume_value = $retrive_data['erp_material']['consume'];
					if($consume_value == 1)
					{
						$consume_type = "Consumable";
					}elseif($consume_value == 0){
						$consume_type = "Retunable / Non-consumable";
					}elseif($consume_value == 3){
						$consume_type = "Asset";
					}else{
						$consume_type = "";
					}
										
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_materialitemcode($retrive_data['material_id']);
					$csv[] = $mt;
					$csv[] = $consume_type;
					$csv[] = ucfirst($retrive_data['erp_material']['cost_group']);
					$csv[] = $retrive_data['max_quantity'];
					$csv[] = bcdiv($retrive_data['total_stock_in'],1,3);
					// $csv[] = ($retrive_data['max_quantity'] != 0) ? bcdiv($retrive_data['total_stock_in']/$retrive_data['max_quantity'],1,3) : "NA";
					$csv[] = bcdiv($retrive_data['total_stock_out'],1,3);
					if($consume_value == 1)
					{
						$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					}else{
						$csv[] = bcdiv($this->ERPfunction->get_symbolic_stock($retrive_data['project_id'],$m_id),1,3);
					}
					
					$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					$csv[] = $retrive_data['min_quantity'];
					$csv[] = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					
					/* Check mismatch material with project */
					$flag = 1;
					$material_id=$retrive_data['material_id'];
					if($material_id)
					{
						$project_specific = $this->ERPfunction->is_material_projectspecific($material_id);
						
						if($project_specific)
						{
							$material_code = $this->ERPfunction->get_materialitemcode($material_id);
							$m_c = explode("/",$material_code);
							$m_c = ($m_c[3])?$m_c[3]:'';
							
							$project_id=$retrive_data['project_id'];
							$project_code = $this->ERPfunction->get_projectcode($project_id);
							$p_c = explode("/",$project_code);
							$p_c = ($p_c[2])?$p_c[2]:'';
							if($m_c != $p_c)
							{
								$flag = 0;
							}
						}
					}
					
					/* Check mismatch material with project */
					if($flag)
					{
						$rows[] = $csv;
					}
				}
				
				$this->set("rows",$rows);
				$this->render("viewrecordspdf");
			}
		}
			
		//return $request_list;
		/*
		$view_table = TableRegistry::get('erp_stock_history');
		$view_table_data = $view_table->find();
		$this->set('result',$view_table_data);
		*/
    }
	
	public function urgentstockrequirment()
    {
		if($this->role == "deputymanagerelectric")
		{
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}
		$role = $this->role;
		$this->set('projects',$projects);
		$erp_stock_tab = TableRegistry::get('erp_stock');
		if($this->role == "deputymanagerelectric")
		{
			
			$meterial_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$meterial_ids = json_decode($meterial_ids);
			$result_stockdata = $erp_stock_tab->find()->where(["material_id IN"=>$meterial_ids]);
		}elseif($role =='projectdirector' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager' || $role =='projectcoordinator' || $role =='erpoperator'){
			$meterial_ids = $this->ERPfunction->get_user_material_id($this->user_id);
			$meterial_ids = json_decode($meterial_ids);
			$result_stockdata = $erp_stock_tab->find()->where(["material_id IN"=>$meterial_ids]);
			
		}else{
			$result_stockdata = $erp_stock_tab->find();
		}
		
		$this->set('sl_data',$result_stockdata);
		$this->set('role',$this->role);
		$conn = ConnectionManager::get('default');
		$erp_stock_history_tbl = TableRegistry::get("erp_stock_history");
		// $request_list = $conn->execute('select *,SUM(stock_in) as total_stock_in,
		// SUM(stock_out) as total_stock_out
		// from erp_stock_history 
		// group by project_id,material_id,material_name');	
		
		// $this->set('result',$request_list);
		
		if($this->request->is("post"))
		{
			/*
			if(isset($this->request->data['go']))
			{
			$post = $this->request->data;
			// debug($post);//die;
			$erp_stock_history_tbl = TableRegistry::get("erp_stock_history");
			
			$or = array();
			$orwhere_name = array();
			$orwhere_id = array();
			$material_name = array();
			$material_id = array();
			
			$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
			if(!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"][0] != "All")
			{
			foreach($post["sl_mrn_name"] as $retrive)
			{
				if(is_numeric($retrive))
				{
				 $material_id[] = $retrive;
				}
				else
				{
				 $material_name[] = $retrive;	
				}
			}
			$orwhere_id["material_id IN"] = (!empty($material_id)) ? $material_id : NULL ;
			$orwhere_name["material_name IN"] = (!empty($material_name)) ? $material_name : NULL;
			}
			//$or["asset_id LIKE"] = (!empty($post["asset_id"]))?"%{$post["asset_id"]}%":NULL;
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			
			$keys1 = array_keys($orwhere_id,"");				
			foreach ($keys1 as $k1)
			{unset($orwhere_id[$k1]);}
			
			$keys2 = array_keys($orwhere_name,"");				
			foreach ($keys2 as $k2)
			{unset($orwhere_name[$k2]);}
			 //debug($or);
			 //debug($orwhere_id);
			 //debug($orwhere_name);die;
			//array('fields'=>array('sum(stock_in) AS total_stock_in')) 
			$result = $erp_stock_history_tbl->find('all');
			if(!empty($or) || !empty($orwhere_id) || !empty($orwhere_name))
			{
				if(!empty($orwhere_id))
				{
					$result= $result->where([$or])
					->andWhere([$orwhere_id])
					->orWhere([$orwhere_name])
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
				}
				elseif(!empty($orwhere_name))
				{
					$result= $result->where([$or])
					->andWhere([$orwhere_name])
					->orWhere([$orwhere_id])
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
				}
				else
				{
					$result= $result->where([$or])
					->andWhere([$orwhere_id])
					->orWhere([$orwhere_name])
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
				}
				
			}
			else
			{
				$result= $result
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
			}
			
					//debug($result);die;
			$this->set("result",$result);
			}*/
			if(isset($this->request->data["export_csv"]))
			{
				// $rows = unserialize($this->request->data["rows"]);
				$erp_material = TableRegistry::get("erp_material");
				$or = array();				
				$post = $this->request->data();
				
				$pro_id = ($post["f_pro_id"] != '')?explode(",",$post["f_pro_id"]):array();
				$mat_id = ($post["f_material_id"] != '')?explode(",",$post["f_material_id"]):array();
				$consume = ($post["f_consume"] != '')?explode(",",$post["f_consume"]):array();
				$cost_group = ($post["f_cost_group"] != '')?explode(",",$post["f_cost_group"]):array();
				
				$or["erp_stock_history.project_id IN"] = (!empty($pro_id) && $pro_id[0] != "All" )?$pro_id:NULL;
				$or["erp_stock_history.material_id IN"] = (!empty($mat_id) && $mat_id[0] != "All")?$mat_id:NULL;
				$or["erp_stock_history.min_quantity ="] = (!empty($post["f_minimum_stock"]))?$post["f_minimum_stock"]:NULL;
				$or["erp_stock_history.max_quantity ="] = (!empty($post["f_maximum_purchase"]))?$post["f_maximum_purchase"]:NULL;
				
				$or["erp_material.consume IN"] = (!empty($consume) && $consume[0] != "All" )?$consume:NULL;
				$or["erp_material.cost_group IN"] = (!empty($cost_group) && $cost_group[0] != "All" )?$cost_group:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if(!empty($or))
				{
					
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_stock_history_tbl)
					->select($erp_material)
					->where($or)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}else{
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_material)
					->select($erp_stock_history_tbl)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Project Name","Material Code","Material Name",'Consume Type','Cost Group',"Max Purchase Level","Total Stock In","Total Stock Out","Symbolic Balance","Current Balance","Min Stock Level","Unit");
			
				foreach($result as $retrive_data)
				{				
					$csv = array();
					if($retrive_data['material_id'] != 0)
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$m_id = $retrive_data['material_id'];
					}
					else
					{
						$mt = $retrive_data['material_name'];
						$m_id = $retrive_data['material_name'];
					}
					$consume_value = $retrive_data['erp_material']['consume'];
					if($consume_value == 1)
					{
						$consume_type = "Consumable";
					}elseif($consume_value == 0){
						$consume_type = "Retunable / Non-consumable";
					}elseif($consume_value == 3){
						$consume_type = "Asset";
					}else{
						$consume_type = "";
					}
										
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_materialitemcode($retrive_data['material_id']);
					$csv[] = $mt;
					$csv[] = $consume_type;
					$csv[] = ucfirst($retrive_data['erp_material']['cost_group']);
					$csv[] = $retrive_data['max_quantity'];
					$csv[] = bcdiv($retrive_data['total_stock_in'],1,3);
					// $csv[] = ($retrive_data['max_quantity'] != 0) ? bcdiv($retrive_data['total_stock_in']/$retrive_data['max_quantity'],1,3) : "NA";
					$csv[] = bcdiv($retrive_data['total_stock_out'],1,3);
					if($consume_value == 1)
					{
						$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					}else{
						$csv[] = bcdiv($this->ERPfunction->get_symbolic_stock($retrive_data['project_id'],$m_id),1,3);
					}
					
					$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					$csv[] = $retrive_data['min_quantity'];
					$csv[] = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					$rows[] = $csv;
				}
				
				$filename = "viewrecords.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				// $rows = unserialize($this->request->data["rows"]);
				$erp_material = TableRegistry::get("erp_material");
				$or = array();				
				$post = $this->request->data();
				
				$pro_id = ($post["f_pro_id"] != '')?explode(",",$post["f_pro_id"]):array();
				$mat_id = ($post["f_material_id"] != '')?explode(",",$post["f_material_id"]):array();
				$consume = ($post["f_consume"] != '')?explode(",",$post["f_consume"]):array();
				$cost_group = ($post["f_cost_group"] != '')?explode(",",$post["f_cost_group"]):array();
				
				$or["project_id IN"] = (!empty($pro_id) && $pro_id[0] != "All" )?$pro_id:NULL;
				$or["material_id IN"] = (!empty($mat_id) && $mat_id[0] != "All")?$mat_id:NULL;
				$or["min_quantity ="] = (!empty($post["f_minimum_stock"]))?$post["f_minimum_stock"]:NULL;
				$or["max_quantity ="] = (!empty($post["f_maximum_purchase"]))?$post["f_maximum_purchase"]:NULL;
				
				$or["erp_material.consume IN"] = (!empty($consume) && $consume[0] != "All" )?$consume:NULL;
				$or["erp_material.cost_group IN"] = (!empty($cost_group) && $cost_group[0] != "All" )?$cost_group:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if(!empty($or))
				{
					
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_stock_history_tbl)
					->select($erp_material)
					->where($or)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}else{
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_material)
					->select($erp_stock_history_tbl)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Project Name","Material Code","Material Name",'Consume Type','Cost Group',"Max Purchase Level","Total Stock In","Total Stock Out","Symbolic Balance","Current Balance","Min Stock Level","Unit");
			
				foreach($result as $retrive_data)
				{				
					$csv = array();
					if($retrive_data['material_id'] != 0)
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$m_id = $retrive_data['material_id'];
					}
					else
					{
						$mt = $retrive_data['material_name'];
						$m_id = $retrive_data['material_name'];
					}
					$consume_value = $retrive_data['erp_material']['consume'];
					if($consume_value == 1)
					{
						$consume_type = "Consumable";
					}elseif($consume_value == 0){
						$consume_type = "Retunable / Non-consumable";
					}elseif($consume_value == 3){
						$consume_type = "Asset";
					}else{
						$consume_type = "";
					}
										
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_materialitemcode($retrive_data['material_id']);
					$csv[] = $mt;
					$csv[] = $consume_type;
					$csv[] = ucfirst($retrive_data['erp_material']['cost_group']);
					$csv[] = $retrive_data['max_quantity'];
					$csv[] = bcdiv($retrive_data['total_stock_in'],1,3);
					// $csv[] = ($retrive_data['max_quantity'] != 0) ? bcdiv($retrive_data['total_stock_in']/$retrive_data['max_quantity'],1,3) : "NA";
					$csv[] = bcdiv($retrive_data['total_stock_out'],1,3);
					if($consume_value == 1)
					{
						$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					}else{
						$csv[] = bcdiv($this->ERPfunction->get_symbolic_stock($retrive_data['project_id'],$m_id),1,3);
					}
					
					$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					$csv[] = $retrive_data['min_quantity'];
					$csv[] = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					$rows[] = $csv;
				}
				
				$this->set("rows",$rows);
				$this->render("viewrecordspdf");
			}
		}
			
		//return $request_list;
		/*
		$view_table = TableRegistry::get('erp_stock_history');
		$view_table_data = $view_table->find();
		$this->set('result',$view_table_data);
		*/
    }
	
	public function overpurchasedstock()
    {
		if($this->role == "deputymanagerelectric")
		{
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}
		$role = $this->role;
		$this->set('projects',$projects);
		$erp_stock_tab = TableRegistry::get('erp_stock');
		if($this->role == "deputymanagerelectric")
		{
			
			$meterial_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$meterial_ids = json_decode($meterial_ids);
			$result_stockdata = $erp_stock_tab->find()->where(["material_id IN"=>$meterial_ids]);
		}elseif($role =='projectdirector' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager' || $role =='projectcoordinator' || $role =='erpoperator'){
			$meterial_ids = $this->ERPfunction->get_user_material_id($this->user_id);
			$meterial_ids = json_decode($meterial_ids);
			$result_stockdata = $erp_stock_tab->find()->where(["material_id IN"=>$meterial_ids]);
			
		}else{
			$result_stockdata = $erp_stock_tab->find();
		}
		
		$this->set('sl_data',$result_stockdata);
		$this->set('role',$this->role);
		$conn = ConnectionManager::get('default');
		$erp_stock_history_tbl = TableRegistry::get("erp_stock_history");
		// $request_list = $conn->execute('select *,SUM(stock_in) as total_stock_in,
		// SUM(stock_out) as total_stock_out
		// from erp_stock_history 
		// group by project_id,material_id,material_name');	
		
		// $this->set('result',$request_list);
		
		if($this->request->is("post"))
		{
			/*
			if(isset($this->request->data['go']))
			{
			$post = $this->request->data;
			// debug($post);//die;
			$erp_stock_history_tbl = TableRegistry::get("erp_stock_history");
			
			$or = array();
			$orwhere_name = array();
			$orwhere_id = array();
			$material_name = array();
			$material_id = array();
			
			$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
			if(!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"][0] != "All")
			{
			foreach($post["sl_mrn_name"] as $retrive)
			{
				if(is_numeric($retrive))
				{
				 $material_id[] = $retrive;
				}
				else
				{
				 $material_name[] = $retrive;	
				}
			}
			$orwhere_id["material_id IN"] = (!empty($material_id)) ? $material_id : NULL ;
			$orwhere_name["material_name IN"] = (!empty($material_name)) ? $material_name : NULL;
			}
			//$or["asset_id LIKE"] = (!empty($post["asset_id"]))?"%{$post["asset_id"]}%":NULL;
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			
			$keys1 = array_keys($orwhere_id,"");				
			foreach ($keys1 as $k1)
			{unset($orwhere_id[$k1]);}
			
			$keys2 = array_keys($orwhere_name,"");				
			foreach ($keys2 as $k2)
			{unset($orwhere_name[$k2]);}
			 //debug($or);
			 //debug($orwhere_id);
			 //debug($orwhere_name);die;
			//array('fields'=>array('sum(stock_in) AS total_stock_in')) 
			$result = $erp_stock_history_tbl->find('all');
			if(!empty($or) || !empty($orwhere_id) || !empty($orwhere_name))
			{
				if(!empty($orwhere_id))
				{
					$result= $result->where([$or])
					->andWhere([$orwhere_id])
					->orWhere([$orwhere_name])
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
				}
				elseif(!empty($orwhere_name))
				{
					$result= $result->where([$or])
					->andWhere([$orwhere_name])
					->orWhere([$orwhere_id])
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
				}
				else
				{
					$result= $result->where([$or])
					->andWhere([$orwhere_id])
					->orWhere([$orwhere_name])
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
				}
				
			}
			else
			{
				$result= $result
					->select(["total_stock_in"=>$result->func()->sum("stock_in")])
					->select(["total_stock_out"=>$result->func()->sum("stock_out")])
					->select($erp_stock_history_tbl)
					->group('project_id,material_id,material_name')
					->hydrate(false)->toArray();
			}
			
					//debug($result);die;
			$this->set("result",$result);
			}*/
			if(isset($this->request->data["export_csv"]))
			{
				// $rows = unserialize($this->request->data["rows"]);
				$erp_material = TableRegistry::get("erp_material");
				$or = array();				
				$post = $this->request->data();
				
				$pro_id = ($post["f_pro_id"] != '')?explode(",",$post["f_pro_id"]):array();
				$mat_id = ($post["f_material_id"] != '')?explode(",",$post["f_material_id"]):array();
				$consume = ($post["f_consume"] != '')?explode(",",$post["f_consume"]):array();
				$cost_group = ($post["f_cost_group"] != '')?explode(",",$post["f_cost_group"]):array();
				
				$or["erp_stock_history.project_id IN"] = (!empty($pro_id) && $pro_id[0] != "All" )?$pro_id:NULL;
				$or["erp_stock_history.material_id IN"] = (!empty($mat_id) && $mat_id[0] != "All")?$mat_id:NULL;
				$or["erp_stock_history.min_quantity ="] = (!empty($post["f_minimum_stock"]))?$post["f_minimum_stock"]:NULL;
				$or["erp_stock_history.max_quantity ="] = (!empty($post["f_maximum_purchase"]))?$post["f_maximum_purchase"]:NULL;
				
				$or["erp_material.consume IN"] = (!empty($consume) && $consume[0] != "All" )?$consume:NULL;
				$or["erp_material.cost_group IN"] = (!empty($cost_group) && $cost_group[0] != "All" )?$cost_group:NULL;
		
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if(!empty($or))
				{
					
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_stock_history_tbl)
					->select($erp_material)
					->where($or)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}else{
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_material)
					->select($erp_stock_history_tbl)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Project Name","Material Code","Material Name",'Consume Type','Cost Group',"Max Purchase Level","Total Stock In","Total Stock Out","Symbolic Balance","Current Balance","Min Stock Level","Unit");
			
				foreach($result as $retrive_data)
				{				
					$csv = array();
					if($retrive_data['material_id'] != 0)
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$m_id = $retrive_data['material_id'];
					}
					else
					{
						$mt = $retrive_data['material_name'];
						$m_id = $retrive_data['material_name'];
					}
					$consume_value = $retrive_data['erp_material']['consume'];
					if($consume_value == 1)
					{
						$consume_type = "Consumable";
					}elseif($consume_value == 0){
						$consume_type = "Retunable / Non-consumable";
					}elseif($consume_value == 3){
						$consume_type = "Asset";
					}else{
						$consume_type = "";
					}
										
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_materialitemcode($retrive_data['material_id']);
					$csv[] = $mt;
					$csv[] = $consume_type;
					$csv[] = ucfirst($retrive_data['erp_material']['cost_group']);
					$csv[] = $retrive_data['max_quantity'];
					$csv[] = bcdiv($retrive_data['total_stock_in'],1,3);
					// $csv[] = ($retrive_data['max_quantity'] != 0) ? bcdiv($retrive_data['total_stock_in']/$retrive_data['max_quantity'],1,3) : "NA";
					$csv[] = bcdiv($retrive_data['total_stock_out'],1,3);
					if($consume_value == 1)
					{
						$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					}else{
						$csv[] = bcdiv($this->ERPfunction->get_symbolic_stock($retrive_data['project_id'],$m_id),1,3);
					}
					
					$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					$csv[] = $retrive_data['min_quantity'];
					$csv[] = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					$rows[] = $csv;
				}
				
				$filename = "viewrecords.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				// $rows = unserialize($this->request->data["rows"]);
				$erp_material = TableRegistry::get("erp_material");
				$or = array();				
				$post = $this->request->data();
				
				$pro_id = ($post["f_pro_id"] != '')?explode(",",$post["f_pro_id"]):array();
				$mat_id = ($post["f_material_id"] != '')?explode(",",$post["f_material_id"]):array();
				$consume = ($post["f_consume"] != '')?explode(",",$post["f_consume"]):array();
				$cost_group = ($post["f_cost_group"] != '')?explode(",",$post["f_cost_group"]):array();
				
				$or["project_id IN"] = (!empty($pro_id) && $pro_id[0] != "All" )?$pro_id:NULL;
				$or["material_id IN"] = (!empty($mat_id) && $mat_id[0] != "All")?$mat_id:NULL;
				$or["min_quantity ="] = (!empty($post["f_minimum_stock"]))?$post["f_minimum_stock"]:NULL;
				$or["max_quantity ="] = (!empty($post["f_maximum_purchase"]))?$post["f_maximum_purchase"]:NULL;
				
				$or["erp_material.consume IN"] = (!empty($consume) && $consume[0] != "All" )?$consume:NULL;
				$or["erp_material.cost_group IN"] = (!empty($cost_group) && $cost_group[0] != "All" )?$cost_group:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if(!empty($or))
				{
					
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_stock_history_tbl)
					->select($erp_material)
					->where($or)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}else{
					$query = $erp_stock_history_tbl->find('all');
			
					$result= $query
					->innerjoin(["erp_material"=>"erp_material"],["erp_material.material_id = erp_stock_history.material_id"])
					->select(["total_stock_in"=>$query->func()->sum("erp_stock_history.stock_in")])
					->select(["total_stock_out"=>$query->func()->sum("erp_stock_history.stock_out")])
					->select($erp_material)
					->select($erp_stock_history_tbl)
					->group(['erp_stock_history.project_id,erp_stock_history.material_id,erp_stock_history.material_name'])
					->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Project Name","Material Code","Material Name",'Consume Type','Cost Group',"Max Purchase Level","Total Stock In","Total Stock Out","Symbolic Balance","Current Balance","Min Stock Level","Unit");
			
				foreach($result as $retrive_data)
				{				
					$csv = array();
					if($retrive_data['material_id'] != 0)
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$m_id = $retrive_data['material_id'];
					}
					else
					{
						$mt = $retrive_data['material_name'];
						$m_id = $retrive_data['material_name'];
					}
					$consume_value = $retrive_data['erp_material']['consume'];
					if($consume_value == 1)
					{
						$consume_type = "Consumable";
					}elseif($consume_value == 0){
						$consume_type = "Retunable / Non-consumable";
					}elseif($consume_value == 3){
						$consume_type = "Asset";
					}else{
						$consume_type = "";
					}
										
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_materialitemcode($retrive_data['material_id']);
					$csv[] = $mt;
					$csv[] = $consume_type;
					$csv[] = ucfirst($retrive_data['erp_material']['cost_group']);
					$csv[] = $retrive_data['max_quantity'];
					$csv[] = bcdiv($retrive_data['total_stock_in'],1,3);
					// $csv[] = ($retrive_data['max_quantity'] != 0) ? bcdiv($retrive_data['total_stock_in']/$retrive_data['max_quantity'],1,3) : "NA";
					$csv[] = bcdiv($retrive_data['total_stock_out'],1,3);
					if($consume_value == 1)
					{
						$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					}else{
						$csv[] = bcdiv($this->ERPfunction->get_symbolic_stock($retrive_data['project_id'],$m_id),1,3);
					}
					
					$csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3);
					$csv[] = $retrive_data['min_quantity'];
					$csv[] = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					$rows[] = $csv;
				}
				
				$this->set("rows",$rows);
				$this->render("viewrecordspdf");
			}
		}
			
		//return $request_list;
		/*
		$view_table = TableRegistry::get('erp_stock_history');
		$view_table_data = $view_table->find();
		$this->set('result',$view_table_data);
		*/
    }
	
	public function preparerbn()
    {
		$users_table = TableRegistry::get('erp_users');
		$ceo_department = $users_table->find()->where(array('role'=>'ceo'));
		$this->set('ceo_department',$ceo_department);
		$user_action = 'insert';
		$this->set('form_header','Returned Back Note (RBN)');
		$this->set('button_text','Prepare R.B.N.');
		$this->set('user_action',$user_action);	
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id"=>0]);
		$this->set('material_list',$material_list);		
		$erp_inventory_rbn = TableRegistry::get('erp_inventory_rbn'); 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		/* $projects = $this->ERPfunction->get_projects(); */
		$this->set('projects',$projects);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);		
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		$this->set('vendor_list',$vendor_list);
		
		if($this->request->is('post'))
		{	
			$post = $this->request->data;
			// debug($post);die;
			// RBN Number
			$code = $this->ERPfunction->get_projectcode($post['project_id']);
			$number1 = $this->ERPfunction->generate_auto_id($post['project_id'],"erp_inventory_rbn","rbn_id","rbn_no");
			$new_grnno = sprintf("%09d", $number1);
			$rbn_no = $code.'/RBN/'.$new_grnno;
			$this->request->data['rbn_no'] = $rbn_no;
			
			$this->request->data['rbn_date'] = $this->ERPfunction->set_date($this->request->data['rbn_date']);			
			$this->request->data['created_date']=date('Y-m-d H:i:s');			
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;	
			
			$entity_data = $erp_inventory_rbn->newEntity();			
			$post_data=$erp_inventory_rbn->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_rbn->save($post_data))
			{
				$this->Flash->success(__('Record Insert Successfully with RBN No. '.$rbn_no, null), 
							'default', 
							array('class' => 'success'));
				$rbn_id = $post_data->rbn_id;
				
				$this->ERPfunction->add_inventory_rbn_detail($this->request->data['material'],$rbn_id,$this->request->data['project_id'],$this->request->data['rbn_date']);			
			}
			$this->redirect(array("controller" => "Inventory","action" => "viewrbn"));		
		}
    }
	
	public function editmrn($mrn_id)
	{
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);
		
		$erp_inventory_mrn = TableRegistry::get('erp_inventory_mrn');
		$mrn_list = $erp_inventory_mrn->get($mrn_id)->toArray();
		$this->set('mrn_list',$mrn_list);
		
		$erp_inventory_mrn_detail = TableRegistry::get('erp_inventory_mrn_detail');
		$detail_data = $erp_inventory_mrn_detail->find("all")->where(["mrn_id"=>$mrn_id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);
		
		if($this->request->is("post"))
		{
			$rbn_tbl = TableRegistry::get("erp_inventory_mrn");
			$rbnd_tbl = TableRegistry::get("erp_inventory_mrn_detail");
			
			
			$project_id = $this->request->data["project_id"];
			$materials = $this->request->data["material"]["material_id"];
			$quantity = $this->request->data["material"]["quantity"];
			$error = false;
			foreach($materials as $key=>$mid)
			{
				$balance = $this->ERPfunction->get_current_stock($project_id,$mid);
				if($quantity[$key] > $balance)
				{
					$m = $this->ERPfunction->get_material_title($mid);
					$this->Flash->error("ERROR : Quantity is more than its balance({$balance}) for material {$m},Please Try again");
					$error = true;
				}				
			}
			
			if($error)
			{
				return $this->redirect(["controller"=>"Inventory","action"=>"approvemrn"]);							
			}
			$row = $erp_inventory_mrn->get($mrn_id);
			$data = $this->request->data;
			
				$row['project_id'] = $data["project_id"];
				$row['mrn_no'] = $data["mrn_no"];
				$row['mrn_date'] = $this->ERPfunction->set_date($data['mrn_date']);
				$row['time'] = $data["mrn_time"];
				$row['vendor_user'] = $data["vendor_user"];
				$row['vendor_id'] = $data["vendor_id"];
				$row['driver_name'] = $data["driver_name"];
				$row['vehicle_no'] = $data["vehicle_no"];
			if($erp_inventory_mrn->save($row))
			{
				$this->ERPfunction->edit_inventory_mrn_detail($this->request->data['material'],$mrn_id);
				// $this->Flash->success(__('Record Update Successfully')); 
				// return $this->redirect(['action' => 'approvemrn']);
				echo "<script>window.close();</script>";
			}
		}
	}
	
	public function editrbn($rbn_id)
	{
		$user_action = 'edit';
		$this->set('form_header','Edit Returned Back Note (RBN)');
		$this->set('button_text','Update Prepare R.B.N.');
		$this->set('user_action',$user_action);	
		
		$users_table = TableRegistry::get('erp_users');
		$ceo_department = $users_table->find()->where(array('role'=>'ceo'));
		$this->set('ceo_department',$ceo_department);
		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);		
		$erp_inventory_rbn = TableRegistry::get('erp_inventory_rbn'); 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		/* $projects = $this->ERPfunction->get_projects(); */
		$this->set('projects',$projects);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);		
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		$this->set('vendor_list',$vendor_list);
		
		$rbn_tbl = TableRegistry::get("erp_inventory_rbn");
		$rbnd_tbl = TableRegistry::get("erp_inventory_rbn_detail");
		
		$materials = $rbn_tbl->find("all")->where(["erp_inventory_rbn.rbn_id"=>$rbn_id])->select($rbn_tbl);
		$materials = $materials->rightjoin(
						["erp_inventory_rbn_detail"=>"erp_inventory_rbn_detail"],
						["erp_inventory_rbn.rbn_id = erp_inventory_rbn_detail.rbn_id"])->select($rbnd_tbl)->hydrate(false)->toArray();
		
		foreach($materials as $mat)
		{
			$items["erp_inventory_rbn_detail"][] = $mat["erp_inventory_rbn_detail"];			
		}
		
		$this->set("items",$items);
		$this->set("rbndata",$materials[0]);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			// debug($post);die;
			
			/* Redirect back and do not update if stock going nagative after edit*/
			$material_items = $this->request->data["material"];
			foreach($material_items['material_id'] as $key => $data)
			{
				$available_stock = $this->ERPfunction->get_current_stock($post['project_id'],$material_items['material_id'][$key]);
				$old_qty = $material_items['old_quantity_reurn'][$key];
				$difference = $old_qty - $material_items['quantity_reurn'][$key];
				$stock_after = $available_stock - $difference;
				if($stock_after < 0)
				{
					$m = $this->ERPfunction->get_material_title($material_items['material_id'][$key]);
					$this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
					return $this->redirect($this->referer());
				}
			}
			/* Redirect back and do not update if stock going nagative after edit*/
			
			
			$materials = $post["material"];
			// debug($materials["material_id"][0]);die;
			$query = $rbn_tbl->query();
			$query->update()
					->set(["rbn_date"=>date("Y-m-d",strtotime($post["rbn_date"])),"agency_name"=>$post["agency_name"],"last_edit"=>date("Y-m-d"),"last_edit_by"=>$this->user_id])
					->where(["rbn_id"=>$rbn_id])
					->execute();
			foreach($post["detail_id"] as $key=>$detail_id)
			{
				$query = $rbnd_tbl->query();
				$query->update()
					->set(["material_id"=>$materials["material_id"][$key],"brand_id"=>$materials["brand_id"][$key],"quantity_reurn"=>$materials["quantity_reurn"][$key],
							"name_of_foreman"=>$materials["name_of_foreman"][$key],"time_of_return"=>$materials["time_of_return"][$key]])
					->where(["rbn_detail_id"=>$detail_id])
					->execute();
				//For history table update	
				$history_tbl = TableRegistry::get("erp_stock_history");
				$hstry_query = $history_tbl->query();
				$hstry_query->update()
					->set(["quantity"=>$materials["quantity_reurn"][$key],"return_back"=>$materials["quantity_reurn"][$key]])
					->where(["detail_id"=>$detail_id,"type"=>'rbn'])
					->execute();

				//For stock table update
				$new_quentity = $materials["quantity_reurn"][$key];
				$old_quentity = $materials["old_quantity_reurn"][$key];
				
				$stock_tbl = TableRegistry::get("erp_stock");
				$check_stock = $stock_tbl->find("all")->where(["project_id"=>$post['project_id'],"material_id"=>$materials["material_id"][$key]])->hydrate(false)->toArray();
					
				if($new_quentity > $old_quentity)
				{
					$difference = $new_quentity - $old_quentity; // For add difference in stock table		
					if(!empty($check_stock))
					{			
						$query = $stock_tbl->query();
						$query->update()
							->set(['quantity' => $check_stock[0]["quantity"] + intval($difference)])
							->where(['project_id' => $post['project_id'],'material_id'=>$materials["material_id"][$key]])
							->execute();
					}
				}
				else if($new_quentity < $old_quentity)
				{
					$difference = $old_quentity - $new_quentity; // For add difference in stock table		
					if(!empty($check_stock))
					{			
						$query = $stock_tbl->query();
						$query->update()
							->set(['quantity' => $check_stock[0]["quantity"] - intval($difference)])
							->where(['project_id' => $post['project_id'],'material_id'=>$materials["material_id"][$key]])
							->execute();
					}
				}
			}
			
			// $this->Flash->success(__('Record Updated Successfully', null), 
						// 'default', 
						// array('class' => 'success'));
			// $this->redirect(array("controller" => "Inventory","action" => "index"));
			echo "<script>window.close();</script>";
		}
								
	}
	
	public function approverbn($search_project_id = null)
    {
		/* $rbn_list = $this->Usermanage->fetch_approve_rbn($this->user_id);
		$this->set('rbn_list',$rbn_list); */		
		$this->set('role',$this->role);		
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);		
		$this->set('projects',$projects);

		if($this->request->is('post') || $search_project_id != null)
		{
			$this->set('selected_pl',true);
			$request_data = $this->request->data;	
			$this->set('request_data',$request_data);		
			if($search_project_id != null)
			{
				$this->request->data["project_id"] = $search_project_id;
			}
		
			$post = $this->request->data;
			$rbn_list = $this->Usermanage->fetch_approve_rbn_by_project($post["project_id"]);
			$this->set('rbn_list',$rbn_list);	
		}
		
	
    }
	
	
	public function viewrbn()
    {
		$this->set('role',$this->role);
		$user = $this->request->session()->read('user_id');
		$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		
		if($this->request->is("post")) {
			if(isset($this->request->data["export_csv"])) {
				$post = $this->request->data();
				$or = array();				
				$projects_ids = $this->Usermanage->users_project($this->user_id);
				$or["erp_inventory_rbn.project_id IN"] = (!empty($post["pro_id"]) && $post["pro_id"] != "All" )?$post["pro_id"]:NULL;
				$or["erp_inventory_rbn_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"] != "All")?$post["material_id"]:NULL;
				$or["erp_inventory_rbn.agency_name IN"] = (!empty($post["agency_id"]) && $post["agency_id"] != "All")?$post["agency_id"]:NULL;
				$or["erp_inventory_rbn.rbn_date >="] = ($post["f_date_from"] != "")?date("Y-m-d",strtotime($post["f_date_from"])):NULL;
				$or["erp_inventory_rbn.rbn_date <="] = ($post["f_date_to"] != "")?date("Y-m-d",strtotime($post["f_date_to"])):NULL;
				$or["erp_inventory_rbn.rbn_no ="] = (!empty($post["rbn_no"]))?$post["rbn_no"]:NULL;
				if($or["erp_inventory_rbn.project_id IN"] == NULL) {
					if($this->Usermanage->project_alloted($role)==1) { 
						$or["erp_inventory_rbn.project_id IN"] = implode(",",$projects_ids);
					}
				}
				$keys = array_keys($or,"");				
				foreach ($keys as $k) {
					unset($or[$k]);
				}
				$or["erp_inventory_rbn_detail.approved ="] = 1;
				$erp_inventory_rbn = TableRegistry::get("erp_inventory_rbn");
				$erp_inventory_rbn_detail = TableRegistry::get("erp_inventory_rbn_detail");
				$result = $erp_inventory_rbn->find()->select($erp_inventory_rbn)->order(['rbn_date'=>'DESC']);
				$result = $result->innerjoin(
						["erp_inventory_rbn_detail"=>"erp_inventory_rbn_detail"],
						["erp_inventory_rbn.rbn_id = erp_inventory_rbn_detail.rbn_id"])
						->where($or)->select($erp_inventory_rbn_detail)->hydrate(false)->toArray();
				$rows = array();
				$rows[] = array("Project Name","R.B.N No","Date","Vendor Name","Material Name","Make/Source","Returned Quantity","Unit","Name Of Foreman");
				foreach($result as $retrive_data) {
					if(isset($retrive_data["erp_inventory_rbn_detail"])) {
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_rbn_detail"]);
					}
					if($retrive_data['material_id'] != 0) {
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
					}else {
						$mt = $retrive_data['material_name'];
					}
					$csv = array();		
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $retrive_data['rbn_no'];
					$csv[] = date('d-m-Y',strtotime($retrive_data['rbn_date']));
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['agency_name']);
					$csv[] = $mt;
					$csv[] = $this->ERPfunction->get_material_group_name_by_material($retrive_data['material_id']);
					$csv[] = $retrive_data['quantity_reurn'];
					$csv[] = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					$csv[] = $retrive_data['name_of_foreman'];
					$rows[] = $csv;
				}	
				$filename = "Rbn_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"])) {			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$post = $this->request->data();
				$or = array();				
				$projects_ids = $this->Usermanage->users_project($this->user_id);
				$or["erp_inventory_rbn.project_id IN"] = (!empty($post["pro_id"]) && $post["pro_id"] != "All" )?$post["pro_id"]:NULL;
				$or["erp_inventory_rbn_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"] != "All")?$post["material_id"]:NULL;
				$or["erp_inventory_rbn.agency_name IN"] = (!empty($post["agency_id"]) && $post["agency_id"] != "All")?$post["agency_id"]:NULL;
				$or["erp_inventory_rbn.rbn_date >="] = ($post["f_date_from"] != "")?date("Y-m-d",strtotime($post["f_date_from"])):NULL;
				$or["erp_inventory_rbn.rbn_date <="] = ($post["f_date_to"] != "")?date("Y-m-d",strtotime($post["f_date_to"])):NULL;
				$or["erp_inventory_rbn.rbn_no ="] = (!empty($post["rbn_no"]))?$post["rbn_no"]:NULL;
				if($or["erp_inventory_rbn.project_id IN"] == NULL) {
					if($this->Usermanage->project_alloted($role)==1) { 
						$or["erp_inventory_rbn.project_id IN"] = implode(",",$projects_ids);
					}
				}
				$keys = array_keys($or,"");				
				foreach ($keys as $k) {
					unset($or[$k]);
				}
				$or["erp_inventory_rbn_detail.approved ="] = 1;
				$erp_inventory_rbn = TableRegistry::get("erp_inventory_rbn");
				$erp_inventory_rbn_detail = TableRegistry::get("erp_inventory_rbn_detail");
				$result = $erp_inventory_rbn->find()->select($erp_inventory_rbn)->order(['rbn_date'=>'DESC']);
				$result = $result->innerjoin(
						["erp_inventory_rbn_detail"=>"erp_inventory_rbn_detail"],
						["erp_inventory_rbn.rbn_id = erp_inventory_rbn_detail.rbn_id"])
						->where($or)->select($erp_inventory_rbn_detail)->hydrate(false)->toArray();
				$rows = array();
				$rows[] = array("Project Name","R.B.N No","Date","Vendor Name","Material Name","Make/Source","Returned Quantity","Unit","Name Of Foreman");
				foreach($result as $retrive_data) {
					if(isset($retrive_data["erp_inventory_rbn_detail"])) {
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_rbn_detail"]);
					}
					if($retrive_data['material_id'] != 0) {
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
					}else {
						$mt = $retrive_data['material_name'];
					}
					$pdf = array();		
					$pdf[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$pdf[] = $retrive_data['rbn_no'];
					$pdf[] = date('d-m-Y',strtotime($retrive_data['rbn_date']));
					$pdf[] = $this->ERPfunction->get_vendor_name($retrive_data['agency_name']);
					$pdf[] = $mt;
					$pdf[] = $this->ERPfunction->get_material_group_name_by_material($retrive_data['material_id']);
					$pdf[] = $retrive_data['quantity_reurn'];
					$pdf[] = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					$pdf[] = $retrive_data['name_of_foreman'];
					$rows[] = $pdf;
				}	
				$this->set("rows",$rows);
				$this->render("viewrbnpdf");
			}
		}		
    }
	
	public function unapproverbn($rbl_detail_id)
	{
		$tbl = TableRegistry::get("erp_inventory_rbn_detail");
		$row =  $tbl->get($rbl_detail_id);
		$material_id = $row->material_id;
		$rbn_id = $row->rbn_id;
		$unapprovable_qty = $row->quantity_reurn;
		
		$rbn_tbl = TableRegistry::get("erp_inventory_rbn");
		$data = $rbn_tbl->find()->where(["rbn_id"=>$rbn_id])->hydrate(false)->toArray();
		if(!empty($data))
		{
			$project_id = $data[0]["project_id"];
		}
		/* Redirect back and do not unapprove if stock going nagative after unapprove*/
		$available_stock = $this->ERPfunction->get_current_stock($project_id,$material_id);
		$stock_after = $available_stock - $unapprovable_qty;
		if($stock_after < 0)
		{
			$m = $this->ERPfunction->get_material_title($material_id);
			$this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
			return $this->redirect($this->referer());
		}
		/* Redirect back and do not unapprove if stock going nagative after unapprove*/
		
		$row->approved = 0; 
		$row->approved_by = null;
		$row->approved_date = null;
		
		if($tbl->save($row))
		{			
			// cut stock from ledger.
			
			$rbn_tbl = TableRegistry::get("erp_inventory_rbn");
			$data = $rbn_tbl->find()->where(["rbn_id"=>$rbn_id])->hydrate(false)->toArray();
			if(!empty($data))
			{
				$project_id = $data[0]["project_id"];
				$this->ERPfunction->delete_stock_entry("rbn",$rbn_id,$project_id,$material_id);
			}
			
			/* Remove record from RBN Audit detailed table */
			$erp_audit_rbn = TableRegistry::get("erp_audit_rbn");
			$erp_audit_rbn_detail = TableRegistry::get("erp_audit_rbn_detail");
		
			$audit_data = $erp_audit_rbn_detail->find()->where(['rbn_detail_id'=>$rbl_detail_id])->first();
			if(!empty($audit_data))
			{
				$rbn_id = $audit_data->rbn_id;
				$rbn_audit_id = $audit_data->audit_id;
				$audit_detail_id = $audit_data->audit_detail_id;
				
				$is_audit_detail_row = $erp_audit_rbn_detail->get($audit_detail_id);
				$delete_ok = $erp_audit_rbn_detail->delete($is_audit_detail_row);
				if($delete_ok)
				{
					$total_records = $erp_audit_rbn_detail->find()->where(['rbn_id'=>$rbn_id])->count();
					if($total_records == 0)
					{
						$audit_row = $erp_audit_rbn->get($rbn_audit_id);
						$erp_audit_rbn->delete($audit_row);
					}
				
					
				}
			}
			/* Remove record from RBN Audit detailed table */
			$this->Flash->success(__('Record Unapproved Successfully', null), 
						'default', 
						array('class' => 'success'));
			$this->redirect(array("controller" => "Inventory","action" => "viewrbn"));
		}
	}
	
	public function preparesst()
    {
		ini_set('memory_limit', '-1');
		$users_table = TableRegistry::get('erp_users');
		$ceo_department = $users_table->find()->where(array('role'=>'ceo'));
		$this->set('ceo_department',$ceo_department);
		$user_action = 'insert';
		$this->set('form_header','Site to Site Transfer (SST)');
		$this->set('button_text','Prepare Site to Site Transfer (SST)');
		$this->set('user_action',$user_action);	
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id"=>0]);
		$this->set('material_list',$material_list);		
		$erp_inventory_sst = TableRegistry::get('erp_inventory_sst'); 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id); 
		$transfer_projects = $this->ERPfunction->get_projects();
		$this->set('projects',$projects);
		$this->set('transfer_projects',$transfer_projects);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);		
		
		if($this->request->is('post'))
		{	
			$project_id = $this->request->data["project_id"];
			$materials = $this->request->data["material"]["material_id"];
			$quantity = $this->request->data["material"]["quantity"];
			$error = false;
			foreach($materials as $key=>$mid)
			{
				$balance = $this->ERPfunction->get_current_stock($project_id,$mid);
				if($quantity[$key] > $balance)
				{
					$m = $this->ERPfunction->get_material_title($mid);
					$this->Flash->error("ERROR : Quantity is more than its balance({$balance}) for material {$m},Please Try again");
					$error = true;
				}				
			}
			
			if($error)
			{
				return $this->redirect(["controller"=>"Inventory","action"=>"approvesst"]);							
			}
			// SST No
			$code = $this->ERPfunction->get_projectcode($project_id);
			$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_sst","sst_id","sst_no");
			$new_sstno = sprintf("%09d", $number1);
			$sst_no = $code.'/SST/'.$new_sstno;
			
			$this->request->data['sst_no'] = $sst_no;
			$this->request->data['sst_date'] = $this->ERPfunction->set_date($this->request->data['sst_date']);			
			$this->request->data['created_date']=date('Y-m-d H:i:s');			
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;	
			
			$entity_data = $erp_inventory_sst->newEntity();			
			$post_data=$erp_inventory_sst->patchEntity($entity_data,$this->request->data);
			if($erp_inventory_sst->save($post_data))
			{
				$this->Flash->success(__('Record Insert Successfully with SST No. '.$sst_no, null), 
							'default', 
							array('class' => 'success'));
				$sst_id = $post_data->sst_id;
				
				$this->ERPfunction->add_inventory_sst_detail($this->request->data['material'],$sst_id);			
			}
			$this->redirect(array("controller" => "Inventory","action" => "approvesst"));		
		}	
    }
	public function approvesst()
    {
		//$sst_list = $this->Usermanage->fetch_approve_sst($this->user_id);
		$this->set('role',$this->role);
		$user_id = $this->user_id;
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		//$this->set('sst_list',$sst_list);
		$this->set('user_id',$user_id);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data['go']))
			{
				$data = $this->request->data;
				$project_id = $data['project_id'];
				$sst_list = $this->Usermanage->fetch_approve_sst_byproject($project_id);
				$this->set('selected_project',$project_id);
				$this->set('sst_list',$sst_list);
			}
		}
    }
	
	public function editsst($sst_id)
    {
		$users_table = TableRegistry::get('erp_users');
		$ceo_department = $users_table->find()->where(array('role'=>'ceo'));
		$this->set('ceo_department',$ceo_department);
		$user_action = 'edit';
		$this->set('form_header','Site to Site Transfer (SST)');
		$this->set('button_text','Edit Site to Site Transfer (SST)');
		$this->set('user_action',$user_action);	
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);		
		$erp_inventory_sst = TableRegistry::get('erp_inventory_sst'); 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id); 
		$transfer_projects = $this->ERPfunction->get_projects();
		$this->set('projects',$projects);
		$this->set('transfer_projects',$transfer_projects);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);	

		$erp_inventory_sst = TableRegistry::get('erp_inventory_sst');
		$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail');
		
		$sst_data = $erp_inventory_sst->get($sst_id);
		$this->set('sst_data',$sst_data);
		
		$detail_data = $erp_inventory_sst_detail->find()->where(['sst_id'=>$sst_id]);
		$this->set('detail_data',$detail_data);
		
		if($this->request->is('post'))
		{	
			$project_id = $this->request->data["project_id"];
			$materials = $this->request->data["material"]["material_id"];
			$quantity = $this->request->data["material"]["quantity"];
			$error = false;
			foreach($materials as $key=>$mid)
			{
				$balance = $this->ERPfunction->get_current_stock($project_id,$mid);
				if($quantity[$key] > $balance)
				{
					$m = $this->ERPfunction->get_material_title($mid);
					$this->Flash->error("ERROR : Quantity is more than its balance({$balance}) for material {$m},Please Try again");
					$error = true;
				}				
			}
			
			if($error)
			{
				return $this->redirect($this->referer());							
			}
			
			$data = $this->request->data;
			//var_dump($data);die;
			$row = $erp_inventory_sst->get($sst_id);
			$row['project_id'] = $data['project_id'];
			$row['sst_no'] = $data['sst_no'];
			$row['sst_date'] = $this->ERPfunction->set_date($data['sst_date']);
			$row['sst_time'] = $data['sst_time'];
			$row['transfer_to'] = $data['transfer_to'];
			$row['driver_name'] = $data['driver_name'];
			$row['vehicle_no'] = $data['vehicle_no'];
			
			if($erp_inventory_sst->save($row))
			{
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				//$sst_id = $post_data->sst_id;
				
				$this->ERPfunction->edit_inventory_sst_detail($this->request->data['material'],$sst_id);			
			}
			//$this->redirect(array("controller" => "Inventory","action" => "approvesst"));
			echo "<script>window.close();</script>";
		}	
    }
	
	public function viewsst()
    {
		//$sst_list = $this->Usermanage->fetch_view_sst($this->user_id);
		//$this->set('sst_list',$sst_list);
		
		// $erp_material = TableRegistry::get('erp_material'); 
		// $material_list = $erp_material->find();
		// $this->set('material_list',$material_list);
		
		// $users_table = TableRegistry::get('erp_vendor');
		// $vendor_department = $users_table->find();
		// $this->set('vendor_department',$vendor_department);
		
		// $projects = $this->Usermanage->all_access_project($this->user_id);
		// $this->set('projects',$projects);
		
		$user = $this->request->session()->read('user_id');
	
		$role = $this->Usermanage->get_user_role($user);
		$this->set('role',$role);
		$projects_ids = $this->Usermanage->users_project($user);
		
		$erp_inventory_sst = TableRegistry::get("erp_inventory_sst");
		$erp_inventory_sst_detail = TableRegistry::get("erp_inventory_sst_detail");
		$post = $this->request->data;	
		$or = array();				
		
		$or["erp_inventory_sst_detail.approved_site2"] = 1;
		
		/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids))
			{
				$result = $erp_inventory_sst->find()->select($erp_inventory_sst)->where(['project_id in'=>$projects_ids,'transfer_to in'=>$projects_ids]);
				$result = $result->innerjoin(
					["erp_inventory_sst_detail"=>"erp_inventory_sst_detail"],
					["erp_inventory_sst.sst_id = erp_inventory_sst_detail.sst_id"])
					->where($or)->select($erp_inventory_sst_detail)->hydrate(false)->toArray();
					//var_dump($result);die;
				//$this->set('grn_list',$result);
			}
			else
			{
				$result=array();
			}
		}
		else
		{
			$result = $erp_inventory_sst->find()->select($erp_inventory_sst);
				$result = $result->innerjoin(
					["erp_inventory_sst_detail"=>"erp_inventory_sst_detail"],
					["erp_inventory_sst.sst_id = erp_inventory_sst_detail.sst_id"])
					->where($or)->select($erp_inventory_sst_detail)->hydrate(false)->toArray();
					//var_dump($result);die;
				//$this->set('grn_list',$result);
		}
		$this->set('sst_list',$result);
				
		if($this->request->is("post"))
		{
			if(isset($this->request->data['go']))
			{			
				$erp_inventory_sst = TableRegistry::get("erp_inventory_sst");
				$erp_inventory_sst_detail = TableRegistry::get("erp_inventory_sst_detail");
				$post = $this->request->data;	
				$or = array();				
				
				$or["project_id IN"] = (!empty($post["from_project_id"]) && $post["from_project_id"][0] != "All" )?$post["from_project_id"]:NULL;
				$or["transfer_to IN"] = (!empty($post["to_project_id"]) && $post["to_project_id"][0] != "All" )?$post["to_project_id"]:NULL;
				//$or["erp_inventory_grn.payment_method IN"] = (!empty($post["payment_mod"]) && $post["payment_mod"][0] != "All" )?$post["payment_mod"]:NULL;
				//$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
				$or["erp_inventory_sst_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All")?$post["material_id"]:NULL;
				//$or["erp_inventory_grn.vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All")?$post["vendor_userid"]:NULL;
				$or["sst_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["sst_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["sst_no"] = (!empty($post["sst_no"]))?$post["sst_no"]:NULL;
				//$or["erp_inventory_grn.challan_no"] = (!empty($post["challan_no"]))?$post["challan_no"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($post);
				// debug($or);die;
				
				$or["erp_inventory_sst_detail.approved_site2"] = 1;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids))
					{
						$result = $erp_inventory_sst->find()->select($erp_inventory_sst)->where(['project_id in'=>$projects_ids,'transfer_to in'=>$projects_ids]);
						$result = $result->innerjoin(
							["erp_inventory_sst_detail"=>"erp_inventory_sst_detail"],
							["erp_inventory_sst.sst_id = erp_inventory_sst_detail.sst_id"])
							->where($or)->select($erp_inventory_sst_detail)->hydrate(false)->toArray();
							//var_dump($result);die;
						//$this->set('grn_list',$result);
					}
					else
					{
						$result=array();
					}
				}
				else
				{
					$result = $erp_inventory_sst->find()->select($erp_inventory_sst);
						$result = $result->innerjoin(
							["erp_inventory_sst_detail"=>"erp_inventory_sst_detail"],
							["erp_inventory_sst.sst_id = erp_inventory_sst_detail.sst_id"])
							->where($or)->select($erp_inventory_sst_detail)->hydrate(false)->toArray();
							//var_dump($result);die;
						//$this->set('grn_list',$result);
				}
				$this->set('sst_list',$result);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				// debug($this->request->data["rows"]);die;
				$filename = "approvesstlist.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("viewsstpdf");
			}
		}
    }
	
	public function previewpr($pr_id)
    {		
		$erp_pr_material = TableRegistry::get('erp_inventory_pr_material'); 
		$erp_inventory_pr = TableRegistry::get('erp_inventory_purhcase_request'); 
		$pr_material = $erp_inventory_pr->get($pr_id);
		$this->set('pr_material',$pr_material);  
		$previw_list = $erp_pr_material->find()->where(array('pr_id'=>$pr_id));
		$this->set('previw_list',$previw_list); 
    }
	
	public function previewprapprove($pr_id)
    {		
		$erp_pr_material = TableRegistry::get('erp_inventory_pr_material'); 
		$erp_inventory_pr = TableRegistry::get('erp_inventory_purhcase_request'); 
		$pr_material = $erp_inventory_pr->get($pr_id);
		$this->set('pr_material',$pr_material);  
		$previw_list = $erp_pr_material->find()->where(['pr_id'=>$pr_id,"OR"=>[["approved"=>1],["show_in_purchase IN"=>[1,3]]]])->hydrate(false)->toArray();
		$this->set('previw_list',$previw_list); 
		// debug($previw_list->hydrate(false)->toArray); die;
    }
	
	public function stockledger($project_id="",$material_id="")
    {
		// ini_set('memory_limit', '-1');
		// $projects = $this->Usermanage->access_project($this->user_id);
		// $this->set('projects',$projects);
		
		// $agency_tbl = TableRegistry::get("erp_agency");
		// $agency_list = $agency_tbl->find("All")->toArray();
		// $this->set('agency_list',$agency_list);
		
		// $users_table = TableRegistry::get('erp_vendor');
		// $vendor_department = $users_table->find();
		// $this->set('vendor_department',$vendor_department);
		
		// $erp_stock_tab = TableRegistry::get('erp_stock');
		// $result_stockdata = $erp_stock_tab->find();
		// $this->set('sl_data',$result_stockdata);
		$this->set("show",false);
		$this->set("showpost",false);
		$this->set('role',$this->role);
		
		if($project_id != "" && $material_id != "")
		{
			$this->set("show",true);
			$history_tbl = TableRegistry::get("erp_stock_history");
			if(is_numeric($material_id))
			{
				// $data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type !="=>"sst_to"])->order(["date"=>"ASC",'FIELD(type, "grn","is","rbn","mrn","sst_from")'])->hydrate(false)->toArray();
				$data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type !="=>"sst_to"])->order(["date"=>"ASC"])->hydrate(false)->toArray();
			}
			else
			{
				// $data = $history_tbl->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type !="=>"sst_to"])->order(["date"=>"ASC",'FIELD(type, "grn","is","rbn","mrn","sst_from")'])->hydrate(false)->toArray();
				$data = $history_tbl->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type !="=>"sst_to"])->order(["date"=>"ASC"])->hydrate(false)->toArray();
			}
			$this->set("stockledger",$data);
			
		}
			
		if($this->request->is('post'))
		{	
			if(isset($this->request->data['go']))
			{
				$this->set("showpost",true);
				$project_id = $this->request->data["project_id"];
				$material_id = $this->request->data["sl_mrn_name"];
				//$project_code = $this->request->data["project_code"];
				//$material_code = $this->request->data["sl_mrn_code"];
				$history_tbl = TableRegistry::get("erp_stock_history");
				$this->set("project_id",$project_id);
				$this->set("material_id",$material_id);
				//$this->set("project_code",$project_code);
				//$this->set("material_code",$material_code);
				$format = $this->request->data["format"];
				
				
				$opening_stock = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
				if(!empty($opening_stock))
				{
					$this->set("opening_stock",$opening_stock[0]);
				}			
				
				
				//$data = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type !="=>"os"])->order(["date"=>"ASC"])->hydrate(false)->toArray();
				if($format == 'grn')
				{
					
					$grn_tbl = TableRegistry::get("erp_inventory_grn");
					$grnd_tbl = TableRegistry::get("erp_inventory_grn_detail");
					$erp_stock_history = TableRegistry::get("erp_stock_history");
					$post = $this->request->data;	
					$or = array();				
					
					$or["erp_stock_history.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
					if(is_numeric($post["sl_mrn_name"]))
					{
						$or["erp_stock_history.material_id"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					}
					else
					{
						$or["erp_stock_history.material_name"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					}
					$or["erp_inventory_grn.vendor_userid"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"] != "All")?$post["vendor_userid"]:NULL;
					$or["erp_inventory_grn.grn_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
					$or["erp_inventory_grn.grn_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
									
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
					
					$result = $erp_stock_history->find()->select($erp_stock_history)->where(["type"=>"grn"])->order(["date"=>"ASC"]);
					$result = $result->innerjoin(
								["erp_inventory_grn"=>"erp_inventory_grn"],
								["erp_inventory_grn.grn_id = erp_stock_history.type_id"])
								->where($or)->hydrate(false)->toArray();
				}
				elseif($format == 'is')
				{
					
					$erp_inventory_is = TableRegistry::get("erp_inventory_is");
					$erp_inventory_is_detail = TableRegistry::get("erp_inventory_is_detail");
					$erp_stock_history = TableRegistry::get("erp_stock_history");
					$post = $this->request->data;	
					$or = array();				
					
					$or["erp_stock_history.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
					$or["erp_stock_history.material_id"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					$or["erp_inventory_is.agency_name"] = (!empty($post["agency_id"]) && $post["agency_id"] != "All")?$post["agency_id"]:NULL;
					$or["erp_inventory_is.is_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
					$or["erp_inventory_is.is_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
					
					
									
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
				
					$result = $erp_stock_history->find()->select($erp_stock_history)->where(["type"=>"is"])->order(["date"=>"ASC"]);
					$result = $result->innerjoin(
								["erp_inventory_is"=>"erp_inventory_is"],
								["erp_inventory_is.is_id = erp_stock_history.type_id"])
								->where($or)->hydrate(false)->toArray();
				}
				elseif($format == 'rbn')
				{
					
					$erp_inventory_rbn = TableRegistry::get("erp_inventory_rbn");
					$erp_inventory_rbn_detail = TableRegistry::get("erp_inventory_rbn_detail");
					$erp_stock_history = TableRegistry::get("erp_stock_history");
					$post = $this->request->data;	
					$or = array();				
					
					$or["erp_stock_history.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
					$or["erp_stock_history.material_id"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					$or["erp_inventory_rbn.agency_name"] = (!empty($post["agency_id"]) && $post["agency_id"] != "All")?$post["agency_id"]:NULL;
					$or["erp_inventory_rbn.rbn_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
					$or["erp_inventory_rbn.rbn_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
					
					
									
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
					
					$result = $erp_stock_history->find()->select($erp_stock_history)->where(["type"=>"rbn"])->order(["date"=>"ASC"]);
					$result = $result->innerjoin(
								["erp_inventory_rbn"=>"erp_inventory_rbn"],
								["erp_inventory_rbn.rbn_id = erp_stock_history.type_id"])
								->where($or)->hydrate(false)->toArray();
				}
				elseif($format == 'mrn')
				{
					
					$erp_inventory_mrn = TableRegistry::get("erp_inventory_mrn");
					$erp_inventory_mrn_detail = TableRegistry::get("erp_inventory_mrn_detail");
					$erp_stock_history = TableRegistry::get("erp_stock_history");
					$post = $this->request->data;	
					$or = array();				
					
					$or["erp_stock_history.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
					$or["erp_stock_history.material_id"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					$or["erp_inventory_mrn.vendor_user"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"] != "All")?$post["vendor_userid"]:NULL;
					$or["erp_inventory_mrn.mrn_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
					$or["erp_inventory_mrn.mrn_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
					
					
									
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
				
					$result = $erp_stock_history->find()->select($erp_stock_history)->where(["type"=>"mrn"])->order(["date"=>"ASC"]);
					$result = $result->innerjoin(
								["erp_inventory_mrn"=>"erp_inventory_mrn"],
								["erp_inventory_mrn.mrn_id = erp_stock_history.type_id"])
								->where($or)->hydrate(false)->toArray();
				}
				elseif($format == 'sst_from')
				{
					
					$erp_inventory_sst = TableRegistry::get("erp_inventory_sst");
					$erp_inventory_sst_detail = TableRegistry::get("erp_inventory_sst_detail");
					$erp_stock_history = TableRegistry::get("erp_stock_history");
					$post = $this->request->data;	
					$or = array();				
					
					$or["erp_stock_history.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
					$or["erp_stock_history.material_id"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					//$or["erp_inventory_mrn.vendor_user"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"] != "All")?$post["vendor_userid"]:NULL;
					$or["erp_inventory_sst.sst_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
					$or["erp_inventory_sst.sst_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
					
					
									
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
				
					$result = $erp_stock_history->find()->select($erp_stock_history)->where(["type"=>"sst_from"])->order(["date"=>"ASC"]);
					$result = $result->innerjoin(
								["erp_inventory_sst"=>"erp_inventory_sst"],
								["erp_inventory_sst.sst_id = erp_stock_history.type_id"])
								->where($or)->hydrate(false)->toArray();
				}
				elseif($format == 'sst_to')
				{
					
					$erp_inventory_sst = TableRegistry::get("erp_inventory_sst");
					$erp_inventory_sst_detail = TableRegistry::get("erp_inventory_sst_detail");
					$erp_stock_history = TableRegistry::get("erp_stock_history");
					$post = $this->request->data;	
					$or = array();				
					
					$or["erp_stock_history.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
					$or["erp_stock_history.material_id"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					//$or["erp_inventory_mrn.vendor_user"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"] != "All")?$post["vendor_userid"]:NULL;
					$or["erp_inventory_sst.sst_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
					$or["erp_inventory_sst.sst_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
					
					
									
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
		
					// debug($post);
					// debug($or);die;
				
					$result = $erp_stock_history->find()->select($erp_stock_history)->where(["type"=>"sst_to"])->order(["date"=>"ASC"]);
					$result = $result->innerjoin(
								["erp_inventory_sst"=>"erp_inventory_sst"],
								["erp_inventory_sst.sst_id = erp_stock_history.type_id"])
								->where($or)->hydrate(false)->toArray();
								//var_dump($result);die;
				}
				elseif($format == 'debit_note')
				{
					
					$erp_inventory_debit_note = TableRegistry::get("erp_inventory_debit_note");
					$erp_stock_history = TableRegistry::get("erp_stock_history");
					$post = $this->request->data;	
					$or = array();				
					
					$or["erp_stock_history.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
					$or["erp_stock_history.material_id"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					$or["erp_inventory_debit_note.debit_to"] = (!empty($post["agency_id"]) && $post["agency_id"] != "All")?$post["agency_id"]:NULL;
					$or["erp_inventory_debit_note.date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
					$or["erp_inventory_debit_note.date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
					
					
									
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
					
					$result = $erp_stock_history->find()->select($erp_stock_history)->where(["type"=>"debit"])->order(["erp_stock_history.date"=>"ASC"]);
					$result = $result->innerjoin(
								["erp_inventory_debit_note"=>"erp_inventory_debit_note"],
								["erp_inventory_debit_note.debit_id = erp_stock_history.type_id"])
								->where($or)->hydrate(false)->toArray();
				}
				else{
					
					$erp_stock_history = TableRegistry::get("erp_stock_history");
					$post = $this->request->data;	
					$or = array();				
					
					$or["erp_stock_history.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
					if(is_numeric($post["sl_mrn_name"]))
					{
						$or["erp_stock_history.material_id"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					}else{
						$or["erp_stock_history.material_name"] = (!empty($post["sl_mrn_name"]) && $post["sl_mrn_name"] != "All")?$post["sl_mrn_name"]:NULL;
					}
					$or["erp_stock_history.date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
					$or["erp_stock_history.date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
					
					$keys = array_keys($or,"");				
					foreach ($keys as $k)
					{unset($or[$k]);}
		
					// debug($post);
					// debug($or);die;
					
					if(is_numeric($material_id))
					{
						// $result = $history_tbl->find("all")->where([$or,"type !="=>"os","type !="=>"sst_to"])->order(["date"=>"ASC"])->hydrate(false)->toArray();
												
						// $result = $history_tbl->find("all")->where([$or,"type !="=>"os","type !="=>"sst_to"])->order(["date"=>"ASC",'FIELD(type, "grn","is","rbn","mrn","sst_from")'])->hydrate(false)->toArray();
						$result = $history_tbl->find("all")->where([$or,"type NOT IN"=>array("os","sst_to")])->order(["date"=>"ASC"])->hydrate(false)->toArray();
					}
					else
					{
						// $result = $history_tbl->find("all")->where([$or,"type !="=>"os","type !="=>"sst_to"])->order(["date"=>"ASC",'FIELD(type, "grn","is","rbn","mrn","sst_from")'])->hydrate(false)->toArray();
						$result = $history_tbl->find("all")->where([$or,"type NOT IN"=>array("os","sst_to")])->order(["date"=>"ASC"])->hydrate(false)->toArray();
					}
				}
				
				$this->set("stockledger",$result);
				// debug($result);die;
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "stock.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
		}
		
		
    }
	public function previewpo($po_id)
    {
		$erp_inve_po = TableRegistry::get('erp_inventory_po'); 
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail'); 
		$erp_po_details = $erp_inve_po->get($po_id);
		$this->set('erp_po_details',$erp_po_details);  
		$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$po_id));
		$this->set('previw_list',$previw_list);   
    }
	
	public function previewpo2($po_id) {
		$erp_inve_po = TableRegistry::get('erp_inventory_po'); 
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail'); 
		$erp_po_details = $erp_inve_po->get($po_id);
		$this->set('erp_po_details',$erp_po_details);  
		$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$po_id));
		$this->set('previw_list',$previw_list);   
    }
	
	public function previewgrn($grn_id,$stock = NULL){
		$erp_inve_grn = TableRegistry::get('erp_inventory_grn'); 
		$erp_inve_grn_details = TableRegistry::get('erp_inventory_grn_detail'); 
		$erp_grn_details = $erp_inve_grn->get($grn_id);
		$this->set('erp_grn_details',$erp_grn_details);
		if($stock == "stock")
		{
			$previw_list = $erp_inve_grn_details->find()->where(array('grn_id'=>$grn_id,"approved"=>1));
		}
		else
		{
			$previw_list = $erp_inve_grn_details->find()->where(array('grn_id'=>$grn_id,"approved"=>0));
		}
		
		$this->set('previw_list',$previw_list);   
    }
	
	public function previewapprovedgrn($grn_id,$stock = NULL)
    {
		$erp_inve_grn = TableRegistry::get('erp_inventory_grn'); 
		$erp_inve_grn_details = TableRegistry::get('erp_inventory_grn_detail'); 
		$erp_grn_details = $erp_inve_grn->get($grn_id);
		$this->set('erp_grn_details',$erp_grn_details);  
		$previw_list = $erp_inve_grn_details->find()->where(array('grn_id'=>$grn_id,"approved"=>1));
		$this->set('previw_list',$previw_list); 
		$this->set('stock',$stock); 
    }
	
	public function auditgrnchanges($grn_id,$stock = NULL)
    {
		$erp_inve_grn = TableRegistry::get('erp_inventory_grn'); 
		$erp_inve_grn_details = TableRegistry::get('erp_inventory_grn_detail'); 
		$erp_grn_details = $erp_inve_grn->get($grn_id);
		$this->set('erp_grn_details',$erp_grn_details);  
		$previw_list = $erp_inve_grn_details->find()->where(array('grn_id'=>$grn_id,"approved"=>1));
		$this->set('previw_list',$previw_list); 
		$this->set('stock',$stock); 
    }
	
	public function previewis($is_id,$searched_project= null)
    {
		$erp_inve_is = TableRegistry::get('erp_inventory_is'); 
		$erp_inve_is_details = TableRegistry::get('erp_inventory_is_detail'); 
		$erp_is_details = $erp_inve_is->get($is_id);
		$this->set('erp_is_details',$erp_is_details);  
		$previw_list = $erp_inve_is_details->find()->where(array('is_id'=>$is_id));
		$this->set('previw_list',$previw_list); 
    }
	
	public function previewapprovedis($is_id,$stock = NULL)
    {
		$erp_inve_is = TableRegistry::get('erp_inventory_is'); 
		$erp_inve_is_details = TableRegistry::get('erp_inventory_is_detail'); 
		$erp_is_details = $erp_inve_is->get($is_id);
		$this->set('erp_is_details',$erp_is_details);  
		$previw_list = $erp_inve_is_details->find()->where(array('is_id'=>$is_id,'approved'=>1));
		$this->set('previw_list',$previw_list); 
		$this->set('stock',$stock); 
    }
	
	public function auditischanges($is_id,$stock = NULL)
    {
		$erp_inve_is = TableRegistry::get('erp_inventory_is'); 
		$erp_inve_is_details = TableRegistry::get('erp_inventory_is_detail'); 
		$erp_is_details = $erp_inve_is->get($is_id);
		$this->set('erp_is_details',$erp_is_details);  
		$previw_list = $erp_inve_is_details->find()->where(array('is_id'=>$is_id,'approved'=>1));
		$this->set('previw_list',$previw_list); 
		$this->set('stock',$stock); 
    }
	
	public function printis($is_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');		
		$erp_inve_is = TableRegistry::get('erp_inventory_is'); 
		$erp_inve_is_details = TableRegistry::get('erp_inventory_is_detail'); 
		$erp_is_details = $erp_inve_is->get($is_id);
		$this->set('data',$erp_is_details);  
		$previw_list = $erp_inve_is_details->find()->where(array('is_id'=>$is_id));
		$this->set('previw_list',$previw_list);			
	}
	
	public function printapprovedis($is_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');		
		$erp_inve_is = TableRegistry::get('erp_inventory_is'); 
		$erp_inve_is_details = TableRegistry::get('erp_inventory_is_detail'); 
		$erp_is_details = $erp_inve_is->get($is_id);
		$this->set('data',$erp_is_details);  
		$previw_list = $erp_inve_is_details->find()->where(array('is_id'=>$is_id,"approved"=>1));
		$this->set('previw_list',$previw_list);			
	}
	
	public function previewmrn($mrn_id)
    {
		$erp_inve_mrn = TableRegistry::get('erp_inventory_mrn'); 
		$erp_inve_mrn_details = TableRegistry::get('erp_inventory_mrn_detail'); 
		$erp_mrn_details = $erp_inve_mrn->get($mrn_id);
		$this->set('erp_mrn_details',$erp_mrn_details);  
		$previw_list = $erp_inve_mrn_details->find()->where(array('mrn_id'=>$mrn_id));
		$this->set('previw_list',$previw_list); 
    }
	
	public function previewapprovedmrn($mrn_id)
    {
		$erp_inve_mrn = TableRegistry::get('erp_inventory_mrn'); 
		$erp_inve_mrn_details = TableRegistry::get('erp_inventory_mrn_detail'); 
		$erp_mrn_details = $erp_inve_mrn->get($mrn_id);
		$this->set('erp_mrn_details',$erp_mrn_details);  
		$previw_list = $erp_inve_mrn_details->find()->where(array('mrn_id'=>$mrn_id));
		$this->set('previw_list',$previw_list); 
    }
	
	public function previewrbn($rbn_id)
    {
		$erp_inve_rbn = TableRegistry::get('erp_inventory_rbn'); 
		$erp_inve_rbn_details = TableRegistry::get('erp_inventory_rbn_detail'); 
		$erp_rbn_details = $erp_inve_rbn->get($rbn_id);
		$this->set('erp_rbn_details',$erp_rbn_details);  
		$previw_list = $erp_inve_rbn_details->find()->where(array('rbn_id'=>$rbn_id,'approved'=>0));
		$this->set('previw_list',$previw_list); 

    }
	
	public function previewapprovedrbn($rbn_id,$stock = NULL)
    {
		$erp_inve_rbn = TableRegistry::get('erp_inventory_rbn'); 
		$erp_inve_rbn_details = TableRegistry::get('erp_inventory_rbn_detail'); 
		$erp_rbn_details = $erp_inve_rbn->get($rbn_id);
		$this->set('erp_rbn_details',$erp_rbn_details);  
		$previw_list = $erp_inve_rbn_details->find()->where(array('rbn_id'=>$rbn_id,'approved'=>1));
		$this->set('previw_list',$previw_list); 

    }
	
	public function printrbn($rbn_id)
    {
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_inve_rbn = TableRegistry::get('erp_inventory_rbn'); 
		$erp_inve_rbn_details = TableRegistry::get('erp_inventory_rbn_detail'); 
		$erp_rbn_details = $erp_inve_rbn->get($rbn_id);
		$this->set('data',$erp_rbn_details);  
		$previw_list = $erp_inve_rbn_details->find()->where(array('rbn_id'=>$rbn_id,'approved'=>0));
		$this->set('previw_list',$previw_list); 

    }
	
	public function printapprovedrbn($rbn_id)
    {
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_inve_rbn = TableRegistry::get('erp_inventory_rbn'); 
		$erp_inve_rbn_details = TableRegistry::get('erp_inventory_rbn_detail'); 
		$erp_rbn_details = $erp_inve_rbn->get($rbn_id);
		$this->set('data',$erp_rbn_details);  
		$previw_list = $erp_inve_rbn_details->find()->where(array('rbn_id'=>$rbn_id,'approved'=>1));
		$this->set('previw_list',$previw_list); 

    }
	
	public function previewsst($sst_id)
    {
		$erp_inve_sst = TableRegistry::get('erp_inventory_sst'); 
		$erp_inve_sst_details = TableRegistry::get('erp_inventory_sst_detail'); 
		$erp_sst_details = $erp_inve_sst->get($sst_id);
		$this->set('erp_sst_details',$erp_sst_details);  
		$previw_list = $erp_inve_sst_details->find()->where(array('sst_id'=>$sst_id));
		$this->set('previw_list',$previw_list); 
    }
	
	public function previewapprovedsst($sst_id)
    {
		$erp_inve_sst = TableRegistry::get('erp_inventory_sst'); 
		$erp_inve_sst_details = TableRegistry::get('erp_inventory_sst_detail'); 
		$erp_sst_details = $erp_inve_sst->get($sst_id);
		$this->set('erp_sst_details',$erp_sst_details);  
		$previw_list = $erp_inve_sst_details->find()->where(array('sst_id'=>$sst_id));
		$this->set('previw_list',$previw_list); 
    }
	
	public function editpreparepo($po_id)
	{
		ini_set('memory_limit', '500M');
		$this->set('selected_pl',true);
		//debug($po_materials);
		// $erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		// $prno_list = $erp_inventory_purhcase_request->find();
		// $this->set('prno_list',$prno_list);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		// $raise_from = $this->ERPfunction->get_mm_constructionmanager();
		
		$erp_agency = TableRegistry::get('erp_agency'); 
		$agency_list = $erp_agency->find();
		$this->set('agency_list',$agency_list);
		
		/* $projects = $this->Usermanage->access_project_ongoing($this->user_id); */
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		// $this->set('raise_from',$raise_from);
		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);
		
		$user_action = 'edit';			
		// $user_data = $users_table->get($user_id);			
		// $this->set('user_data',$user_data);
		$this->set('user_action',$user_action);
		$this->set('form_header','Edit Purchase Order (PO)');
		$this->set('button_text','Update Purchase Order (PO)');	
		
		$erp_inve_po = TableRegistry::get('erp_inventory_po'); 
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail'); 
		$erp_po_details = $erp_inve_po->get($po_id);
		//var_dump($erp_po_details);
		$this->set('erp_po_details',$erp_po_details);
		// $previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$po_id));
		// $this->set('previw_list',$previw_list);   
		
		
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		// $prids = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$erp_po_details["project_id"],"erp_inventory_pr_material.approved" => 0])->select(["prno","pr_id"]);
		// $prids = $prids->leftjoin(["erp_inventory_pr_material"=>"erp_inventory_pr_material"],
								  // ["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id"])
								  // ->group("erp_inventory_purhcase_request.prno")
								  // ->select($mat_tbl)->hydrate(false)->toArray();
		
		// $pridso = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$erp_po_details["project_id"]])->select(["prno","pr_id"]);
		// $potbl = $pridso->leftjoin(["erp_inventory_po"=>"erp_inventory_po"],
								  // ["erp_inventory_po.pr_id = erp_inventory_purhcase_request.pr_id"])
								  // ->group("erp_inventory_purhcase_request.prno")
								  // ->select($erp_inve_po)->hydrate(false)->toArray();
								  
		// $prlist = null;
		// foreach($prids as $prno)
		// {
			// $prlist.="<option value='{$prno['pr_id']}' prno='{$prno['prno']}' ".(($erp_po_details['pr_id'] == $prno['pr_id']) ? 'selected':'').">{$prno['prno']}</option>";
		// }
		// $prnoslist = null;
		// foreach($potbl as $prno)
		// {
			// $prnoslist.="<option value='{$prno['pr_id']}' prno='{$prno['prno']}' ".(($erp_po_details['pr_id'] == $prno['pr_id']) ? 'selected':'').">{$prno['prno']}</option>";
			// if($erp_po_details['pr_id'] == $prno['pr_id'])
			// {
				// $prnoslist.="<option value='{$prno['pr_id']}' prno='{$prno['prno']}' selected>{$prno['prno']}</option>";
			// }
			
		// }
		// $this->set('prlist',$prlist);   
		// $this->set('prnoslist',$prnoslist);   
		$this->set('po_id',$po_id); 

		$data = $erp_inve_po_details->find()->where(["po_id"=>$po_id,"approved"=>0])->hydrate(false)->toArray();	
		//debug($data);
		$i = 0;
		$row='';
		if(!empty($data))
		{
			foreach($data as $material)
			{
				
				//$po_id = $post["selected_po_id_{$material['id']}"];
				//$pr_id = $po_tbl->find()->where(["po_id"=>$post["selected_po_id_{$material['id']}"]])->select(["pr_id"])->hydrate(false)->toArray();
				$m_code = is_numeric($material['material_id'])?$this->ERPfunction->get_material_item_code_bymaterialid($material['material_id']):$material['m_code'];
				
				// $mt = is_numeric($material['material_id'])?$this->ERPfunction->get_material_title
				// ($material['material_id']):$material['material_id'];
				
				// $brnd = is_numeric($material['brand_id'])?$this->ERPfunction->get_brand_name($material["brand_id"]):$material["brand_id"];
				
				$unit = is_numeric($material['material_id'])?$this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material['material_id'])):$material['static_unit'];
				$m_code_row = '';
				$m_row = '';
				$b_row = '';
				$unit_row = '';
				if(is_numeric($material['material_id']))
				{
					$m_code_row .='<td style="display:none;"><span id="material_code_'.$i.'">'.$m_code.'</span>
					<input type="hidden" value="" name="material[m_code][]" id="m_code_'.$i.'">
					<input type="hidden" value="'.$i.'" name="row_number" class="row_number">
					<input type="hidden" value="'.$material["id"].'" name="material[detail_id][]">
					</td>';
					
					$m_row .= '<select class="select2 material_id" style="width:130px;" name="material[material_id][]" id="material_id_'.$i.'" data-id='.$i.'>
						<option value="">Select Material</Option>';
						   foreach($material_list as $retrive_data)
						   {
							   $selected = ($retrive_data['material_id'] == $material['material_id']) ? "selected" : "";
								$m_row .=  '<option value="'.$retrive_data['material_id'].'"'.$selected.'>'.
								 $retrive_data['material_title'].'</option>';
						   }
					$m_row .= '</select>';
					if($material['description'] != NULL) {
						$m_row .= '<input type="text" value="'.$material["description"].'" placeholder="Description Here" class="desc_textfield" name="material[description][]" value=""  id="descriptionTextfield_'.$i.'" >';
					}else {
						$m_row .= '<input type="text" value="'.$material["description"].'" placeholder="Description Here" class="desc_textfield" name="material[description][]" value="" id="descriptionTextfield_'.$i.'" style="display:none;">';
					}
					
					$b_row .= '<select class="select2 brand_id"  required="true"   name="material[brand_id][]" style="width:130px;" id="brand_id_'.$i.'" data-id='.$i.'>';
					$brands = $this->ERPfunction->get_brands_by_material_id($material["material_id"]);
					if($brands != "") {
						foreach($brands as $brand) {
							$b_row .= '<option value="'.$brand['brand_id'].'"'.$this->ERPfunction->selected($brand['brand_id'],$material['brand_id']).'>'.$brand['brand_name'].'</option>';
						}
					}
					
					$b_row .= '</select>';
					
					$unit_row .= '<td><span id="unit_name_'.$i.'">'.$unit.'</span>
					<input type="hidden" value="" name="material[static_unit][]" id="static_unit_'.$i.'" class="form-control" style="width:80px;">
					</td>';
				}
				else
				{
					$m_code_row .='<td style="display:none;"><span id="material_code_'.$i.'">'.$m_code.'</span>
					<input type="hidden" value="'.$m_code.'" name="material[m_code][]" id="m_code_'.$i.'">
					<input type="hidden" value="1" name="material[is_custom][]">
					<input type="hidden" value="'.$i.'" name="row_number" class="row_number">
					<input type="hidden" value="'.$material["id"].'" name="material[detail_id][]">
					</td>';
					
					$m_row .= '<input type="text" name="material[material_id][]" value="'.htmlspecialchars($material["material_id"]).'" id="material_id_'.$i.'" data-id="'.$i.'" class="form-control material_id" style="width:120px;"/>';
					$b_row .= '<input type="text" name="material[brand_id][]" value="'.htmlspecialchars($material["brand_id"]).'" id="brand_id_'.$i.'" class="form-control" style="width:120px;"/>';
					$unit_row .= '<td><input type="text" value="'.htmlspecialchars($unit).'" name="material[static_unit][]" id="static_unit_'.$i.'" class="form-control" class="form-control" style="width:120px;"></td>';
				}
				
				$row .= '<tr class="cpy_row" id="row_id_'.$i.'">
							'.$m_code_row.'
							<td>'.$m_row.'
							</td>';
							// <td>'.$this->ERPfunction->get_materialitem_desc($material['material_id']).'</td>
					$row .=	'<td>'
							.$b_row.'</td>
							<td><input type="text" name="material[quantity][]" data-id="'.$i.'" class="quantity" value="'.$material["quantity"].'" id="quantity_'.$i.'" style="width:60px"/></td>
							'.$unit_row.'
							<td><input type="text" name="material[unit_rate][]" class="unit_rate" value="'.$material["unit_price"].'" data-id="'.$i.'" id="unit_rate_'.$i.'" style="width:80px"/>
							<input type="hidden" value="'.$material["pr_mid"].'" name="material[pr_mid][]"></td>
							<td><input type="text" name="material[discount][]" value="'.$material["discount"].'" class="tx_count" id="dc_'.$i.'" data-id="'.$i.'" style="width:55px"></td>
							<td><input type="text" name="material[gst][]" value="'.$material["gst"].'" class="tx_count" id="gst_'.$i.'"  data-id="'.$i.'" style="width:55px"></td>																									
							<td><input type="text" name="material[amount][]" class="amount" value="'.$material["amount"].'" id="amount_'.$i.'" style="width:90px" /></td>
							<td><input type="text" name="material[single_amount][]" value="'.$material["single_amount"].'" id="single_amount_'.$i.'" style="width:90px"/></td>
							
							<input type="hidden" name="po_mid[]" value="'.$material["id"].'">
							<td>
								<a href="javascript:void(0)" class="btn btn-primary add_textfield" onClick="insertRow('.$i.')" id="textfield_'.$i.'" data-id="'.$material["id"].'" value="textfield">Textfield</a>
								<a href="javascript:void(0)" class="btn btn-danger del_parent" data-id="'.$material["id"].'">Delete</a>
							</td>
						</tr>';
						
					$i++;
			}
		}
		//debug($row);
		$this->set("row",$row);
		
		if($this->request->is('post'))
		{
			$this->request->data['last_edit']=date('Y-m-d H:i:s');			
			$this->request->data['last_edit_by']=$this->request->session()->read('user_id');	
			$this->request->data['po_date']=date('Y-m-d',strtotime($this->request->data['po_date']));
			// $this->request->data['delivery_date']=date('Y-m-d',strtotime($this->request->data['delivery_date']));
			
			$this->request->data['taxes_duties']=isset($this->request->data['taxes_duties'])?$this->request->data['taxes_duties']:'0';
			$this->request->data['loading_transport']=isset($this->request->data['loading_transport'])?$this->request->data['loading_transport']:'0';
			$this->request->data['unloading']=isset($this->request->data['unloading'])?$this->request->data['unloading']:'0';
			
			$entity_data = $erp_inve_po->get($po_id);
			$po_type = $entity_data->po_purchase_type;
			$post_data=$erp_inve_po->patchEntity($entity_data,$this->request->data);
			if($erp_inve_po->save($post_data)) {
				$this->ERPfunction->edit_inventory_po_detail($this->request->data['material'],$po_id,$po_type);
				$this->ERPfunction->edit_inventory_po_grn_detail($this->request->data['material']);
				$this->Flash->success(__('Record Update Successfully', null), 
					'default', 
					array('class' => 'success'));
			}
			$this->redirect(array("controller" => "Inventory","action" => "approvepo"));
		}
		
	}
	
	public function setpoapprove()
	{
		$this->autoRender = false;
		$post = $this->request->data;
		$tbl = TableRegistry::get("erp_inventory_po");
		$row = $tbl->get($post["po_id"]);
		$row->approved_status = 1;
		$tbl->save($row);
		$this->redirect(["action"=>"approvepo"]);
	}
	
	public function printporecord($eid,$mail = NULL)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		
		if($mail == "mail")
		{
			$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid,'currently_approved'=>1));
		}
		else
		{
			$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid));
		}
		
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
	}
	
	// public function printporecord2($eid,$mail = NULL)
	public function printporecord2($eid) {
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		
		// $is_mail = 0;
		// if($mail == "mail")
		// {
		// 	$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid,'currently_approved'=>1));
		// 	// $is_mail = 1;
		// }
		// else
		// {
			$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid));
			// $is_mail = 0;
		// }
		
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
		// $this->set("is_mail",$is_mail);			
	}

	public function mailporecord2($po_id) {
		// debug($po_id);die;
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		$previw_list = $erp_inve_po_details->find()->where(['po_id'=>$po_id,'currently_approved'=>1]);
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($po_id);	
		// debug($previw_list);die;
		$this->set("data",$data->toArray());			
	}
	
	public function printapprovedporecord($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		
		
		 // $session = $this->request->session();
		 // $mpoid = $this->request->session()->read('mpoid');
		 // $session->delete('mpoid');
		//var_dump($session);die;
		 // $previw_list = $erp_inve_po_details->find()->where(array('id IN'=>$mpoid));
		 // debug($previw_list);
		 // die;
		
		
		$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid,'currently_approved'=>1));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());				
	}
	
	public function printporecordemail($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		$session = $this->request->session();
		$po_mid = $session->read("ids");		
		
		/* $previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid)); */
		$previw_list = $erp_inve_po_details->find()->where(array('id IN'=>$po_mid));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
		$this->set("approve_id",$this->user_id);			
	}
	
	public function printporecordnorate($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		
		$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid,'currently_approved'=>1));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
	}
	
	public function printporecordnorate2($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		
		$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid,'currently_approved'=>1));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
	}
	
	public function printporecordnorate2inventory($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		
		$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());
	}
	
	public function printporecordnorateemail($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		
		$session = $this->request->session();
		$po_mid = $session->read("ids");
		$session->delete("ids");
		
		/* $previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid)); */
		$previw_list = $erp_inve_po_details->find()->where(array('id IN'=>$po_mid));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
	}
	public function printgrnrecord($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_grn");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_grn_detail');
		$previw_list = $erp_inve_po_details->find()->where(array('grn_id'=>$eid));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("erp_grn_details",$data->toArray());			
	}
	
	public function printapprovedgrnrecord($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_grn");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_grn_detail');
		$previw_list = $erp_inve_po_details->find()->where(array('grn_id'=>$eid,"approved"=>1));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("erp_grn_details",$data->toArray());			
	}
	
	public function setpraprove()
	{
		if(isset($this->request->data["approve_list"]))
			{
				$post = $this->request->data;				
				$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
				$pr_data = $pr_tbl->find()->where(["prno"=>$post["prno"]])->hydrate(false)->toArray();
				$this->set("data",$post);
				$this->set("pr_data",$pr_data[0]);
				// debug($this->request->data);    
			}
		
	}
	
	public function printmrn($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_mrn");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_mrn_detail');
		$previw_list = $erp_inve_po_details->find()->where(array('mrn_id'=>$eid));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
	}
	
	public function printapprovedmrn($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_inventory_mrn");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_mrn_detail');
		$previw_list = $erp_inve_po_details->find()->where(array('mrn_id'=>$eid));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
	}
	
	public function printsst($sst_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$erp_inventory_sst = TableRegistry::get("erp_inventory_sst");
		$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail');
		$previw_list = $erp_inventory_sst_detail->find()->where(array('sst_id'=>$sst_id));
		$this->set('previw_list',$previw_list);
		$data = $erp_inventory_sst->get($sst_id);
		$this->set("data",$data->toArray());			
	}
	
	public function printapprovedsst($sst_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$erp_inventory_sst = TableRegistry::get("erp_inventory_sst");
		$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail');
		$previw_list = $erp_inventory_sst_detail->find()->where(array('sst_id'=>$sst_id));
		$this->set('previw_list',$previw_list);
		$data = $erp_inventory_sst->get($sst_id);
		$this->set("data",$data->toArray());			
	}
	
	public function printpr($pid)
	{
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$erp_pr_material = TableRegistry::get('erp_inventory_pr_material'); 	  
		$previw_list = $erp_pr_material->find()->where(array('pr_id'=>$pid));
		
		
		$data = $pr_tbl->get($pid);
		$this->viewBuilder()->options([
			'pdfConfig' => [
				'orientation' => 'landscape',
				'filename' => 'Invoice_' . $pid
			]
		]);
		$this->viewBuilder()->setClassName('CakePdf.Pdf');
		$this->set("pr_material",$data->toArray());
		$this->set('previw_list',$previw_list); 
					
	}
	
	
	public function printprapproved($pid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$erp_pr_material = TableRegistry::get('erp_inventory_pr_material'); 	  
		$previw_list = $erp_pr_material->find()->where(array('pr_id'=>$pid,"OR"=>[["approved"=>1],["show_in_purchase IN"=>[1,3]]]))->hydrate(false)->toArray();
		$this->set('previw_list',$previw_list); 
		
		$data = $pr_tbl->get($pid);
		$this->set("data",$data->toArray());				
	}
	
	public function unapprovepr($pr_id)
	{
		/* $tbl = TableRegistry::get("erp_inventory_purhcase_request"); */
		$tbl = TableRegistry::get("erp_inventory_pr_material");
		// $row = $tbl->get($pr_id);		
		// $row->approved_for_grnwithoutpo = 0;
		// $row->approved = 0;	
		// $row->approved_by = 0;	
		// $row->approved_date = null;
		// $row->show_in_purchase = 0;
		
		$query = $tbl->query();
		$update = $query->update()
				->set(['approved_for_grnwithoutpo'=>0,"approved"=>0,"approved_by"=>0,"approved_date"=>null,"show_in_purchase"=>0])
				->where(['pr_id' => $pr_id])
				->execute();
				
		if($update)
		{
			$this->Flash->success(__('Record Deleted Successfully', null), 
							'default', 
							array('class' => 'success'));			
		}
		$this->redirect(["action"=>"viewpr"]);		
	}
	
	public function deletepr($pr_id,$pr_mid)
	{
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$pr_detail_tbl = TableRegistry::get("erp_inventory_pr_material");
				
		$qry = $pr_detail_tbl->query();
		$qry->delete()->where(["pr_material_id"=>$pr_mid])->execute();
		
		$cnt = $pr_detail_tbl->find()->where(["pr_id"=>$pr_id])->count();
		if($cnt == 0)
		{
			$qry = $pr_tbl->query();
			$qry->delete()->where(["pr_id"=>$pr_id])->execute();
		}
		
		$this->Flash->success(__('Record Deleted Successfully', null), 
							'default', 
							array('class' => 'success'));
		$this->redirect(["action"=>"approvedpr"]);		
	}
	
	public function unapprovegrn($grn_detail_id)
	{
		$grn_detail_tbl=TableRegistry::get("erp_inventory_grn_detail");
		$pr_detail_tbl=TableRegistry::get("erp_inventory_pr_material");
		$po_detail_tbl=TableRegistry::get("erp_inventory_po_detail");
		
		$g_row = $grn_detail_tbl->get($grn_detail_id);
		$qty = $g_row->actual_qty;
		$material_id = $g_row->material_id;
		$material_name = $g_row->material_name;
		$grn_id = $g_row->grn_id;
		$po_detail_id = $g_row->po_detail_id;
		
		$grn_tbl = TableRegistry::get("erp_inventory_grn");
		$row = $grn_tbl->get($grn_id);
		$pr_id = $row->pr_id;
		$po_id = $row->po_id;
		
		// back process to add quentity in po detail record when grn delete
		if($po_detail_id)
		{
			$po_detail_row = $po_detail_tbl->get($po_detail_id);
			$po_detail_row->grn_remain_qty = $po_detail_row->grn_remain_qty + $qty;
			$po_detail_row->approved = 1;
			$po_detail_tbl->save($po_detail_row);
		}
		if($pr_id != null || $pr_id != "")
		{ //grnwithoutpo
			$row = $pr_detail_tbl->find()->where(["pr_id"=>$pr_id,"material_id"=>$material_id])->hydrate(false)->toArray();
			$pr_mid = $row[0]["pr_material_id"];
			$used_qty = $row[0]["used_qty"];
			if($used_qty > 0)
			{
				$new_used_qty = $used_qty - $qty;
			}else{
				$new_used_qty = $used_qty;
			}
			
			
			$row = $pr_detail_tbl->get($pr_mid);
			$row->used_qty = $new_used_qty;	
			$row->approved_for_grnwithoutpo = 1;
			$row->approved = 0;
			$row->approved_by = "";
			$row->approved_date = null;
			if($pr_detail_tbl->save($row))
			{
				//$grn_detail_tbl->delete($g_row);
				if($grn_detail_tbl->delete($g_row))
				{
					$count = $grn_detail_tbl->find()->where(["grn_id"=>$grn_id])->count();
					if($count == 0)
					{	
						$grn_delete_row = $grn_tbl->get($grn_id);
						$grn_tbl->delete($grn_delete_row);
					}
				}
			}
		}
		else if($po_id != null || $po_id != "")
		{
			//preparegrn
			if($material_id)
			{
				$row = $po_detail_tbl->find()->where(["po_id"=>$po_id,"material_id"=>$material_id])->hydrate(false)->toArray();
			}
			else
			{
				$row = $po_detail_tbl->find()->where(["po_id"=>$po_id,"material_id"=>$material_name])->hydrate(false)->toArray();
			}
			if(isset($row[0]))
			{
				$po_mid = $row[0]["id"];			
				$used_qty = $row[0]["used_qty"];
				if($used_qty > 0)
				{
					$new_used_qty = $used_qty - $qty;
				}else{
					$new_used_qty = $used_qty;
				}
				
				$row = $po_detail_tbl->get($po_mid);
				$row->used_qty = $new_used_qty;
				if($row->approved == 2)
				{
					$row->approved = 1;
				}
				
				if($po_detail_tbl->save($row))
				{
					if($grn_detail_tbl->delete($g_row))
					{
						$count = $grn_detail_tbl->find()->where(["grn_id"=>$grn_id])->count();
						if($count == 0)
						{	
							$grn_delete_row = $grn_tbl->get($grn_id);
							$grn_tbl->delete($grn_delete_row);
						}
					}
				}
			}else
			{
				if($grn_detail_tbl->delete($g_row))
					{
						$count = $grn_detail_tbl->find()->where(["grn_id"=>$grn_id])->count();
						if($count == 0)
						{				
							$grn_tbl->delete($row);
						}
					}
			}
		}
		else
		{
			if($grn_detail_tbl->delete($g_row))
				{
					$count = $grn_detail_tbl->find()->where(["grn_id"=>$grn_id])->count();
					if($count == 0)
					{				
						$grn_tbl->delete($row);
					}
				}
		}
		
		if($grn_detail_tbl->save($row))
		{
			$this->Flash->success(__('Record Unapproved Successfully', null), 
							'default', 
							array('class' => 'success'));
		$this->redirect(["action"=>"approvegrn"]);		
		}
	}
	
	public function deleteapprovedgrn($detail_id)
	{
		$grn_tbl = TableRegistry::get("erp_inventory_grn");
		$grn_detail_tbl = TableRegistry::get("erp_inventory_grn_detail");
		
		$single_row = $grn_detail_tbl->get($detail_id);
		$grn_id = $single_row->grn_id;
		
		$grn_row = $grn_tbl->get($grn_id);
		$project_id = $grn_row->project_id;
		$grn_row->approved_status = 0;
		$grn_row->show_in_account = 0;
		
		if($grn_id)
		{
			$all_detail_row = $grn_detail_tbl->find()->where(["grn_id"=>$grn_id])->hydrate(false)->toArray();
			/* Redirect back and do not unapprove if stock going nagative after unapprove*/
			foreach($all_detail_row as $detail_row)
			{
				$available_stock = $this->ERPfunction->get_current_stock($project_id,$detail_row['material_id']);
				$unapprovable_qty = $detail_row['actual_qty'];
				$stock_after = $available_stock - $unapprovable_qty;
				if($stock_after < 0)
				{
					$m = $this->ERPfunction->get_material_title($detail_row['material_id']);
					$this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
					return $this->redirect($this->referer());
				}
			}
			/* Redirect back and do not unapprove if stock going nagative after unapprove*/
			
			foreach($all_detail_row as $detail_row)
			{
				$row = $grn_detail_tbl->get($detail_row['grndetail_id']);
				if($row->material_id)
				{
					if(is_numeric($row->material_id))
					{
						$material_id = $row->material_id;
					}
					else
					{
						$material_id = $row->material_name;
					}
				}
				else
				{
					$material_id = $row->material_name;
				}
				$row->approved = 0;
				$row->approved_by = 0;
				$row->approved_time = null;
				$row->approved_date = null;
				$grn_detail_tbl->save($row);
				
				$this->ERPfunction->delete_stock_entry("grn",$grn_id,$project_id,$material_id);
			}
		}
				
		if($grn_tbl->save($grn_row))
		{
			/* Delete GRN Audit record */
			$erp_audit_grn = TableRegistry::get("erp_audit_grn");
			$erp_audit_grn_detail = TableRegistry::get("erp_audit_grn_detail");
		
			$audit_data = $erp_audit_grn->find()->where(['grn_id'=>$grn_id])->first();
			if(!empty($audit_data))
			{
				$audit_id = $audit_data->audit_id;
				
				$delete_ok = $erp_audit_grn_detail->deleteAll(["audit_id"=>$audit_id]);
				if($delete_ok)
				{
					$audit_row = $erp_audit_grn->get($audit_id);
					$erp_audit_grn->delete($audit_row);
					
				}
			}
			/* Delete GRN Audit record */
		}
		$this->Flash->success(__('Record Unapproved Successfully', null), 
							'default', 
							array('class' => 'success'));
		$this->redirect(["action"=>"viewgrn"]);
	}
	
	public function canceldebit($debit_id)
	{
		$erp_debit_note = TableRegistry::get('erp_inventory_debit_note');
		$erp_debit_note_detail = TableRegistry::get('erp_inventory_debit_note_detail');
		$erp_stock_history = TableRegistry::get('erp_stock_history');
		
		$query = $erp_debit_note_detail->query();
		$unapproved = $query->update()
		->set(['second_approved'=>0,
		"second_approved_date"=>'',
		"second_approved_by"=>0])
		->where(['debit_id' => $debit_id])
		->execute();
		if($unapproved)
		{
			$delete_ok = $erp_stock_history->deleteAll(["type IN"=>["debit","debit_party"],"type_id"=>$debit_id]);
		}
			
		$this->Flash->success(__('Record Unapproved Successfully', null), 
							'default', 
							array('class' => 'success'));
		$this->redirect(["action"=>"inventorydebitrecords"]);
	}
	
	public function deleteis($is_id)
	{
		$is_tbl = TableRegistry::get("erp_inventory_is");
		$is_detail_tbl = TableRegistry::get("erp_inventory_is_detail");
		
		$row = $is_tbl->get($is_id);
		$is_tbl->delete($row);
		
		$query = $is_detail_tbl->query();
		$query->delete()->where(["is_id"=>$is_id])->execute();
		
		$this->Flash->success(__('Record Deleted Successfully', null), 
							'default', 
							array('class' => 'success'));
		$this->redirect(["action"=>"approveis"]);
	}
	
	public function unapproveis($is_detail_id)
	{
		$is_tbl = TableRegistry::get("erp_inventory_is");
		$is_detail_tbl = TableRegistry::get("erp_inventory_is_detail");
				
		$row = $is_detail_tbl->get($is_detail_id);
		$is_id = $row->is_id;
		$material_id = $row->material_id;		
		$row->approved = 0;
		if($is_detail_tbl->save($row)){
			/* Delete IS Audit record */
			$erp_is_audit = TableRegistry::get("erp_is_audit");
			$erp_audit_is_detail = TableRegistry::get("erp_audit_is_detail");
		
			$audit_data = $erp_audit_is_detail->find()->where(['is_detail_id'=>$is_detail_id])->first();
			if(!empty($audit_data))
			{
				$is_id = $audit_data->is_id;
				$is_audit_id = $audit_data->is_audit_id;
				$is_audit_detail_id = $audit_data->is_audit_detail_id;
				
				$is_audit_detail_row = $erp_audit_is_detail->get($is_audit_detail_id);
				$delete_ok = $erp_audit_is_detail->delete($is_audit_detail_row);
				if($delete_ok)
				{
					$total_records = $erp_audit_is_detail->find()->where(['is_id'=>$is_id])->count();
					if($total_records == 0)
					{
						$audit_row = $erp_is_audit->get($is_audit_id);
						$erp_is_audit->delete($audit_row);
					}
				
					
				}
			}
			/* Delete IS Audit record */
		}
		
		
		$row = $is_tbl->get($is_id);
		$project_id = $row->project_id;
		
		$this->ERPfunction->delete_stock_entry("is",$is_id,$project_id,$material_id);
		
		$this->Flash->success(__('Record Deleted Successfully', null), 
							'default', 
							array('class' => 'success'));
		$this->redirect(["action"=>"viewis"]);
	}

	public function deleterbn($rbn_detail_id)
	{
		$detail_tbl = TableRegistry::get("erp_inventory_rbn_detail");
		$row = $detail_tbl->get($rbn_detail_id);
		$rbn_id = $row->rbn_id;
		if($detail_tbl->delete($row))
		{
			$count = $detail_tbl->find()->where(["rbn_id"=>$rbn_id])->count();
			if($count == 0)
			{				
				$tbl = TableRegistry::get("erp_inventory_rbn");
				$row = $tbl->get($rbn_id);
				$tbl->delete($row);
			}
			
			// echo $count; die;
			$this->Flash->success(__('Record Deleted Successfully', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(["action"=>"approverbn"]);
		}
		
	}
	
	public function deletemrn($mrn_detail_id)
	{
		$detail_tbl = TableRegistry::get("erp_inventory_mrn_detail");
		$row = $detail_tbl->get($mrn_detail_id);
		$mrn_id = $row->mrn_id;
		$material_id = $row->material_id;
		if($detail_tbl->delete($row))
		{
			$count = $detail_tbl->find()->where(["mrn_id"=>$mrn_id])->count();
			if($count == 0)
			{				
				$tbl = TableRegistry::get("erp_inventory_mrn");
				$row = $tbl->get($mrn_id);
				$project_id = $row->project_id;
				$tbl->delete($row);
			}
			$this->ERPfunction->delete_stock_entry("mrn",$mrn_id,$project_id,$material_id);
			// echo $count; die;
			$this->Flash->success(__('Record Deleted Successfully', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(["action"=>"approvemrn"]);
		}
	}
	
	public function deletesst($sst_detail_id)
	{
		$detail_tbl = TableRegistry::get("erp_inventory_sst_detail");
		$row = $detail_tbl->get($sst_detail_id);
		$sst_id = $row->sst_id;
		if($detail_tbl->delete($row))
		{
			$count = $detail_tbl->find()->where(["sst_id"=>$sst_id])->count();
			if($count == 0)
			{				
				$tbl = TableRegistry::get("erp_inventory_sst");
				$row = $tbl->get($sst_id);
				$tbl->delete($row);
			}
			
			// echo $count; die;
			$this->Flash->success(__('Record Deleted Successfully', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(["action"=>"approvesst"]);
		}
		
	}
	
	public function managestock()
	{
		$erp_project_opening_stock = TableRegistry::get('erp_stock_history');
		$this->autoRender = false ;
		
			if(isset($this->request->data['manage']))
			{
			$data = $this->request->data();
			if(is_numeric($data["material"]))
			{
				$old_data = $erp_project_opening_stock->find()->where(["project_id"=>$data["project"],'material_id'=>$data["material"],'type'=>'os'])->hydrate(false)->toArray();
			}
			else
			{
				$old_data = $erp_project_opening_stock->find()->where(["project_id"=>$data["project"],'material_name'=>$data["material"],'type'=>'os'])->hydrate(false)->toArray();
			}
			
			if(!empty($old_data))
			{
									
					$save_data = $erp_project_opening_stock->get($old_data[0]['stock_id']);
					
							//$save_data['material_id'] =  $material_items['material_id'][$key];
					//$save_data->quantity =  $material_items['quantity'][$key];
					 $save_data->max_quantity =  $data["max_quentity"];
					 $save_data->min_quantity =  $data["min_quentity"];
					//$save_data->note =  $material_items['note'][$key];	
						//debug($save_data);die;
					$check = $erp_project_opening_stock->save($save_data);
					
			}
			else
			{
				
						$save_data['created_date']=date('Y-m-d H:i:s');			
						$save_data['created_by']=$this->request->session()->read('user_id');
						$save_data['project_id'] =  $data["project"];
						if(is_numeric($data["material"]))
						{
						$save_data['material_id'] =  $data["material"];
						}
						else
						{
							$save_data['material_id'] =  0;
							$save_data['material_name'] =  $data["material"];
						}
						//$save_data['quantity'] =  $material_items['quantity'][$key];
						 $save_data['max_quantity'] =  $data["max_quentity"];
						 $save_data['min_quantity'] =  $data["min_quentity"];
						$save_data['type'] =  "os";
						//$save_data['note'] =  $material_items['note'][$key];	
							
						$entity_data = $erp_project_opening_stock->newEntity();			
						$material_data=$erp_project_opening_stock->patchEntity($entity_data,$save_data);
						$check = $erp_project_opening_stock->save($material_data);
					
			}
			if($check)
			{
				$this->Flash->success(__('Insert Successfully.'));
				return $this->redirect(['action'=>'viewrecords']);
			}
			}
		
	}
	
	public function prstatus($id= null)
    {	
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == 'deputymanagerelectric'){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		
		
		$this->set('projects',$projects);
		if($this->request->is('post'))
		{		
			$request_data = $this->request->data;	
			
			if(isset($request_data["go"]))
			{
				$this->set('request_data',$request_data);
				$project_id = $request_data['project_id'];
				
				// if($this->request->data['from_date'] != '')
				// $this->request->data['from_date'] =  date('Y-m-d',strtotime($this->request->data['from_date']));
				// if($this->request->data['to_date'] != '')
				// $this->request->data['to_date'] = date('Y-m-d',strtotime($this->request->data['to_date']));
			
				$pr_list = $this->Usermanage->fetch_approve_pr_prstatus($this->user_id,$this->request->data);
			
				$this->set('pr_list',$pr_list);
				
			}
			else if(isset($request_data["approve_list"]))
			{				
				$pr_mat_tbl = TableRegistry::get("erp_inventory_pr_material");
				foreach($request_data["approved_list"] as $prmid)
				{
					$row = $pr_mat_tbl->get($request_data["pr_mid_{$prmid}"]);
					$row->show_in_purchase = 2; //approved in Purchase tab's PR Alert
					$pr_mat_tbl->save($row);
				}
				// debug($request_data);
				// debug($request_data["approve_list"]);die;
			}
			else if(isset($request_data['export_csv']))
			{
				$post = $this->request->data;
				$projects_ids = $this->Usermanage->users_project($this->user_id);
				// debug($post);die;
				#############################
				$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
				$pr_mat_tbl = TableRegistry::get("erp_inventory_pr_material");
				$or = array();
				
				$or["erp_inventory_purhcase_request.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All")?$post["project_id"]:NULL;
				if($role =='deputymanagerelectric')
				{
					$material_ids = $this->get_deputymanagerelectric_material();
					$material_ids = json_decode($material_ids);
					$or["erp_inventory_pr_material.material_id IN"] = $material_ids;
				}
				
				if($or["erp_inventory_purhcase_request.project_id"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_inventory_purhcase_request.project_id IN"] = $projects_ids;
					}
				}
				$keys = array_keys($or,"");				
						foreach ($keys as $k)
						{unset($or[$k]);}
						
						
				$or["erp_inventory_pr_material.approved ="] = 0;
				$or["erp_inventory_pr_material.show_in_purchase ="] = 1;
				
				// debug($or);die;
				$result = $pr_tbl->find()->select($pr_tbl);
				$pr_list = $result->innerjoin(
					["erp_inventory_pr_material"=>"erp_inventory_pr_material"],
					["erp_inventory_purhcase_request.pr_id = erp_inventory_pr_material.pr_id"])
					->where($or)->select($pr_mat_tbl)->order(["date(erp_inventory_pr_material.approved_date) DESC","erp_inventory_purhcase_request.project_id ASC"])->hydrate(false)->toArray();
				
				
				#############################
				
				$i=1;
				$rows = array();
				$rows[] = array("Project Name","P.R No","Date","Time","Matireal Code","Matiral Name","Make/Source","Quantity","Unit","Delivery Date");
				if(!empty($pr_list))
				{
					foreach($pr_list as $retrive_data)
					{	
						//debug($retrive_data);die;
						if(isset($retrive_data["erp_inventory_pr_material"]))
						{	
							$retrive_data= array_merge($retrive_data,$retrive_data["erp_inventory_pr_material"]);
						}
					
						if($retrive_data['material_id'] != 0)
						{
							$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
							$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
							$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
							$mcode = $this->ERPfunction->get_material_item_code_bymaterialid($retrive_data['material_id']);
						}
						else
						{
							$mt = $retrive_data['material_name'];
							$brnd = $retrive_data['brand_name'];
							$static_unit = $retrive_data['static_unit'];
							$static_unit = $retrive_data['static_unit'];
							$mcode = $retrive_data['m_code'];
						}
						$csv = array();
						$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
						$csv[] = $retrive_data['prno'];
						$csv[] =(date("d-m-Y",strtotime($retrive_data['approved_date'])));
						$csv[] = $retrive_data['pr_time'];
						$csv[] = $mcode;
						$csv[] = $mt;
						$csv[] = $brnd;
						$csv[] = $retrive_data['quantity'];
						$csv[] = $static_unit;
						$csv[] = (date("d-m-Y",strtotime($retrive_data['delivery_date'])));
						$rows[] = $csv;
						
						$i++;
					}
								
				 $filename = "inventoryPRstatus.csv";
				 $this->ERPfunction->export_to_csv($filename,$rows);
				}
			}
		}
		else{
			
			// $pr_list = $this->Usermanage->fetch_approve_pr($this->user_id);
		}
		// debug($pr_list->fetch("assoc"));die;
		// $this->set('pr_list',$pr_list);		
    }
	
	public function approveauditgrn()
    {
		$this->autoRender = false;
		$data = $this->request->data();
		// debug($_REQUEST['audit_id']);die;
		// $audit_id = $_REQUEST['audit_id'];
		// $projectId = $_REQUEST['project_id'];
		// $grnType = $_REQUEST['grn_type'];
		$erp_inventory_grn = TableRegistry::get("erp_inventory_grn");
		$erp_inventory_grn_detail = TableRegistry::get("erp_inventory_grn_detail");
		$erp_grn_history = TableRegistry::get("erp_inventory_grn_history");
		$erp_grn_detail_history = TableRegistry::get("erp_inventory_grn_detail_history");
		$erp_audit_grn = TableRegistry::get("erp_audit_grn");
		$erp_audit_grn_detail = TableRegistry::get("erp_audit_grn_detail");
		
		foreach($data['auditid'] as $audit_id)
		{
			$audit_record = $erp_audit_grn->get($audit_id);
			$grn_id = $audit_record->grn_id;
			$project_id = $audit_record->project_id;
			$grn_date = $audit_record->grn_date;
			
			/* Redirect back and do not update if stock going nagative after edit*/
			$audit_detail_data = $erp_audit_grn_detail->find()->where(["grn_id"=>$grn_id])->hydrate(false)->toArray();
			
			foreach($audit_detail_data as $auditd_retrive)
			{
				$available_stock = $this->ERPfunction->get_current_stock($project_id,$auditd_retrive['material_id']);

				$grnd_id = $auditd_retrive["grndetail_id"];
				$grnd_record = $erp_inventory_grn_detail->get($grnd_id);
				$old_qty = $grnd_record->actual_qty;
				$difference = $old_qty - $auditd_retrive['actual_qty'];
				
				$stock_after = $available_stock - $difference;
				
				if($stock_after < 0)
				{
					$m = $this->ERPfunction->get_material_title($auditd_retrive['material_id']);
					$this->Flash->error(__("ERROR : Stock is going negative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
					// return $this->redirect(["action"=>"grnaudit"]);
					return $this->redirect(array("controller" => "inventory","action" => "grnaudit", '?' => array('project' => $data['project'],'grn_type'=> $data['grn_type'])));
				}
			}
			/* Redirect back and do not update if stock going nagative after edit*/
				
			$grn_record = $erp_inventory_grn->get($grn_id);
			$grn_record-> approved_status = 1;
			/* First store GRN original record in history table */
			$grn_record_new = $grn_record->toArray();
			$grn_histiry_data = $erp_grn_history->newEntity($grn_record_new);			
					
			if($erp_grn_history->save($grn_histiry_data))
			{
				$grn_detail_data = $erp_inventory_grn_detail->find()->where(["grn_id"=>$grn_id])->hydrate(false)->toArray();
				foreach($grn_detail_data as $history_retrive)
				{
					$detail_id = $history_retrive["grndetail_id"];
					$grn_detail_record = $erp_inventory_grn_detail->get($detail_id);
					$grn_detail_record_new = $grn_detail_record->toArray();
					$grn_detail_histiry_data = $erp_grn_detail_history->newEntity($grn_detail_record_new);
					$erp_grn_detail_history->save($grn_detail_histiry_data);
				}
			}
			/* First store GRN original record in history table */
			
			/* overwite GRN Audit record on original GRn */
			
			$grn_record->project_id = $audit_record->project_id;
			$grn_record->grn_no = $audit_record->grn_no;
			$grn_record->grn_date = $audit_record->grn_date;
			$grn_record->grn_time = $audit_record->grn_time;
			$grn_record->vendor_userid = $audit_record->vendor_userid;
			$grn_record->vendor_id = $audit_record->vendor_id;
			$grn_record->pr_id = $audit_record->pr_id;
			$grn_record->entered_pr_id = $audit_record->entered_pr_id;
			$grn_record->po_id = $audit_record->po_id;
			$grn_record->manualpo_no = $audit_record->manualpo_no;
			$grn_record->local_po_id = $audit_record->local_po_id;
			$grn_record->payment_method = $audit_record->payment_method;
			$grn_record->challan_no = $audit_record->challan_no;
			$grn_record->challan_date = $audit_record->challan_date;
			$grn_record->security_gate_pass_no = $audit_record->security_gate_pass_no;
			$grn_record->gate_pass_date = $audit_record->gate_pass_date;
			$grn_record->driver_name = $audit_record->driver_name;
			$grn_record->vehicle_no = $audit_record->vehicle_no;
			$grn_record->purchase_amt = $audit_record->purchase_amt;
			$grn_record->freight = $audit_record->freight;
			$grn_record->unloading = $audit_record->unloading;
			$grn_record->vouchar_no = $audit_record->vouchar_no;
			$grn_record->total_amt = $audit_record->total_amt;
			$grn_record->challan_bill = $audit_record->challan_bill;
			$grn_record->gate_pass = $audit_record->gate_pass;
			$grn_record->attach_label = $audit_record->attach_label;
			$grn_record->attach_file = $audit_record->attach_file;
			$grn_record->remarks = $audit_record->remarks;
			$grn_record->show_in_account = $audit_record->show_in_account;
			$grn_record->changes = $audit_record->changes;
			$grn_record->changes_status = $audit_record->changes_status;
			if($erp_inventory_grn->save($grn_record))
			{
				$audit_detail_data = $erp_audit_grn_detail->find()->where(["grn_id"=>$grn_id])->hydrate(false)->toArray();
				
				foreach($audit_detail_data as $audit_retrive)
				{
					$auditDetail_id = $audit_retrive["auditdetail_id"];
					$grnDetail_id = $audit_retrive["grndetail_id"];
					$audit_detail_record = $erp_audit_grn_detail->get($auditDetail_id);
					
					$grn_detail_row = $erp_inventory_grn_detail->get($grnDetail_id);
					$po_detail_id = $grn_detail_row->po_detail_id;
					$old_grn_qty = $grn_detail_row->actual_qty;
					$h_material_id = $grn_detail_row->material_id;
					$grn_detail_row->po_detail_id = $audit_detail_record->po_detail_id;
					$grn_detail_row->material_id = $audit_detail_record->material_id;
					$grn_detail_row->brand_id = $audit_detail_record->brand_id;
					$grn_detail_row->is_static = $audit_detail_record->is_static;
					$grn_detail_row->material_name = $audit_detail_record->material_name;
					$grn_detail_row->brand_name = $audit_detail_record->brand_name;
					$grn_detail_row->m_code = $audit_detail_record->m_code;
					$grn_detail_row->static_unit = $audit_detail_record->static_unit;
					$grn_detail_row->quantity = $audit_detail_record->quantity;
					$grn_detail_row->actual_qty = $audit_detail_record->actual_qty;
					$grn_detail_row->difference_qty = $audit_detail_record->difference_qty;
					$grn_detail_row->remarks = $audit_detail_record->remarks;
					$grn_detail_row->changes = $audit_detail_record->changes;
					$grn_detail_row->changes_status = $audit_detail_record->changes_status;
					if($erp_inventory_grn_detail->save($grn_detail_row))
					{
						
						$history_tbl = TableRegistry::get("erp_stock_history");
					
						$hstry_query = $history_tbl->query();
						$hstry_query->update()
							->set(["project_id"=>$project_id,"date"=>date("Y-m-d",strtotime($grn_date)),"material_id"=>$audit_detail_record->material_id,"quantity"=>$audit_detail_record->actual_qty,"stock_in"=>$audit_detail_record->actual_qty])
							->where(["type_id"=>$grn_id,"material_id"=>$h_material_id,"type"=>'grn'])
							->execute();
						
						/* Update GRN Remaining quentity in PO in case update GRN actual qty and decrease it */
						$diff = $old_grn_qty - $audit_detail_record->actual_qty;
						if($po_detail_id)
						{
							$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
							$pod_row = $erp_inventory_po_detail->get($po_detail_id);
							$pod_row->grn_remain_qty = $pod_row->grn_remain_qty + $diff;
							$erp_inventory_po_detail->save($pod_row);
						}
						/* Update GRN Remaining quentity in PO in case update GRN actual qty and decrease it */
					}
				}
			}
			/* overwite GRN Audit record on original GRN */
			
			/* Delete Audit GRN Record */
			$delete_ok = $erp_audit_grn_detail->deleteAll(["grn_id"=>$grn_id]);
			if($delete_ok)
			{
				$audit_row = $erp_audit_grn->get($audit_id);
				$ok = $erp_audit_grn->delete($audit_row);
				
			}
			/* Delete Audit GRN Record */
		}
		
		$this->Flash->success(__('Record Approve Successfully', null), 
							'default', 
							array('class' => 'success'));
		// $this->redirect(["action"=>"grnaudit"]);
		return $this->redirect(array("controller" => "inventory","action" => "grnaudit", '?' => array('project' => $data['project'],'grn_type'=> $data['grn_type'])));
	}
	
	public function updateisaudit($is_id)
	{
		$erp_is_audit = TableRegistry::get("erp_is_audit");
		$erp_audit_is_detail = TableRegistry::get("erp_audit_is_detail");
		$data = $erp_is_audit->get($is_id);
		$this->set('data',$data);
		
		$materials = $erp_audit_is_detail->find()->where(["is_audit_id"=>$is_id])->hydrate(false)->toArray();
		$this->set('materials',$materials);
				
		$this->set('form_header','Update ISSUE SLIP (I.S.)');
		$this->set('button_text','Update I.S');
		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);	
		 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);

		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find()->toArray();

		// $agency_tbl = TableRegistry::get("erp_agency");
		// $agency_list = $agency_tbl->find()->toArray();
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find()->toArray();
		$ast_tbl = TableRegistry::get("erp_assets");
		$assets = $ast_tbl->find()->toArray();
		$this->set('agency_list',$agency_list);

		$this->set('vendor_list',$vendor_list);
		$this->set('assets',$assets);

		if($this->request->is("post"))
		{
			$post = $this->request->data;
			// debug($post);die;
			
			$post["is_date"] = date("Y-m-d",strtotime($post["is_date"]));
			$row = $erp_is_audit->get($is_id);
		
			$save_row = $erp_is_audit->patchEntity($row,$post);
			$diff = $save_row->extract($save_row->visibleProperties(), true);
			
			
			unset($diff['project_code']);
			unset($diff['material']);
			
			if(empty($row["changes"]))
			{
				/* Add user detail who make changes */
				if(!empty($diff))
				{
					$diff["edit_by"] = $this->request->session()->read('user_id');				
					$diff = json_encode($diff);
					$changes[date("Y-m-d H:i:s")] = $diff;
					$save_row["changes"] = json_encode($changes);
					
					$save_row["changes_status"] = 1;
				}
				/* Add user detail who make changes */
			}else{
				if(!empty($diff))
				{
					$diff["edit_by"] = $this->request->session()->read('user_id');				
					$diff = json_encode($diff);
					$changes[date("Y-m-d H:i:s")] = $diff;
					$changes = json_encode($changes);
					// debug($changes);die;
					$save_row["changes"] = json_encode(array_merge(json_decode($changes, true),json_decode($row["changes"], true)));
					$save_row["changes_status"] = 1;
				}
			}
			// debug($save_row);die;
			if($erp_is_audit->save($save_row))
			{
				$updated = 0;
				$material_items = $post["material"];
				foreach($material_items['material_id'] as $key => $data)
				{		
					$changes1 = array();
					$save_data['material_id'] =  $material_items['material_id'][$key];
					$save_data['quantity'] =  $material_items['quantity'][$key];
					$save_data['balance'] =  $material_items['balance'][$key];
					$save_data['name_of_foreman'] =  $material_items['name_of_foreman'][$key];
					$save_data['time_issue'] =  $material_items['time_issue'][$key];
					
					$entity_data = $erp_audit_is_detail->get($material_items['detail_id'][$key]);
					
					$material_data=$erp_audit_is_detail->patchEntity($entity_data,$save_data);
					$diff1 = $material_data->extract($material_data->visibleProperties(), true);
			
					if(empty($entity_data["changes"]))
					{
						/* Add user detail who make changes */
						if(!empty($diff1))
						{
							$diff1["edit_by"] = $this->request->session()->read('user_id');				
							$diff1 = json_encode($diff1);
							$changes1[date("Y-m-d H:i:s")] = $diff1;
							$material_data["changes"] = json_encode($changes1);
							$updated = 1;
							$material_data["changes_status"] = 1;
						}
						/* Add user detail who make changes */
					}else{
						if(!empty($diff1))
						{
							$diff1["edit_by"] = $this->request->session()->read('user_id');				
							$diff1 = json_encode($diff1);
							$changes1[date("Y-m-d H:i:s")] = $diff1;
							$changes1 = json_encode($changes1);
												
							$material_data["changes"] = json_encode(array_merge(json_decode($changes1, true),json_decode($material_data["changes"], true)));
							$updated = 1;
							$material_data["changes_status"] = 1;
						}
					}
					// debug($material_data);die;
					$erp_audit_is_detail->save($material_data);
						
				}
				if($updated > 0)
				{
					$record = $erp_is_audit->get($is_id);
					$record->changes_status = 1;
					$erp_is_audit->save($record);
				}
			}
			$this->Flash->success(__('Record Updated Successfully', null), 
							'default', 
							array('class' => 'success'));			
			// die;
			// $this->redirect(array("controller" => "Inventory","action" => "approveis"));
			echo "<script>opener.location.reload();</script>";
			echo "<script>window.close();</script>";
		}
	}
	
	public function approveauditis()
    {		
		$this->autoRender = false;
		$data = $this->request->data();
		
		$erp_inventory_is = TableRegistry::get("erp_inventory_is");
		$erp_inventory_is_detail = TableRegistry::get("erp_inventory_is_detail");
		$erp_inventory_is_history = TableRegistry::get("erp_inventory_is_history");
		$erp_inventory_is_detail_history = TableRegistry::get("erp_inventory_is_detail_history");
		$erp_is_audit = TableRegistry::get("erp_is_audit");
		$erp_audit_is_detail = TableRegistry::get("erp_audit_is_detail");
		
		foreach($data['auditid'] as $audit_id)
		{
			$audit_record = $erp_is_audit->get($audit_id);
			$is_id = $audit_record->is_id;
			$project_id = $audit_record->project_id;
			$is_date = $audit_record->is_date;
			
			/* Redirect back and do not update if stock going nagative after edit*/
			$audit_detail_data = $erp_audit_is_detail->find()->where(["is_id"=>$is_id])->hydrate(false)->toArray();
			
			foreach($audit_detail_data as $auditd_retrive)
			{
				$available_stock = $this->ERPfunction->get_current_stock($project_id,$auditd_retrive['material_id']);

				$isd_id = $auditd_retrive["is_detail_id"];
				$isd_record = $erp_inventory_is_detail->get($isd_id);
				$old_qty = $isd_record->quantity;
				$difference = $old_qty - $auditd_retrive['quantity'];
				
				$stock_after = $available_stock + $difference;
				
				if($stock_after < 0)
				{
					$m = $this->ERPfunction->get_material_title($auditd_retrive['material_id']);
					$this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
					// return $this->redirect(["action"=>"isaudit"]);
					return $this->redirect(array("controller" => "inventory","action" => "isaudit", '?' => array('project' => $data['project'])));
				}
			}
			
			/* Redirect back and do not update if stock going nagative after edit*/
				
			$is_record = $erp_inventory_is->get($is_id);
						
			/* First store IS original record in history table */
			$is_record_new = $is_record->toArray();
			$is_histiry_data = $erp_inventory_is_history->newEntity($is_record_new);			
					
			if($erp_inventory_is_history->save($is_histiry_data))
			{
				$is_detail_data = $erp_inventory_is_detail->find()->where(["is_id"=>$is_id])->hydrate(false)->toArray();
				foreach($is_detail_data as $history_retrive)
				{
					$detail_id = $history_retrive["is_detail_id"];
					$is_detail_record = $erp_inventory_is_detail->get($detail_id);
					$is_detail_record_new = $is_detail_record->toArray();
					$is_detail_histiry_data = $erp_inventory_is_detail_history->newEntity($is_detail_record_new);
					$erp_inventory_is_detail_history->save($is_detail_histiry_data);
				}
			}
			/* First store GRN original record in history table */
			
			/* overwite GRN Audit record on original GRn */
			
			$is_record->project_id = $audit_record->project_id;
			$is_record->is_no = $audit_record->is_no;
			$is_record->is_date = $audit_record->is_date;
			$is_record->agency_name = $audit_record->agency_name;
			$is_record->approved_status = $audit_record->approved_status;
			$is_record->approved_date = $audit_record->approved_date;
			$is_record->approve_by = $audit_record->approve_by;
			$is_record->quantity_check_by = $audit_record->quantity_check_by;
			$is_record->issue_by = $audit_record->issue_by;
			$is_record->received_by = $audit_record->received_by;
			$is_record->changes = $audit_record->changes;
			$is_record->changes_status = $audit_record->changes_status;
			if($erp_inventory_is->save($is_record))
			{
				$audit_detail_data = $erp_audit_is_detail->find()->where(["is_id"=>$is_id])->hydrate(false)->toArray();
				
				foreach($audit_detail_data as $audit_retrive)
				{
					$auditDetail_id = $audit_retrive["is_audit_detail_id"];
					$isDetail_id = $audit_retrive["is_detail_id"];
					$audit_detail_record = $erp_audit_is_detail->get($auditDetail_id);
					
					$is_detail_row = $erp_inventory_is_detail->get($isDetail_id);
					$h_material_id = $is_detail_row->material_id;
					$is_detail_row->material_id = $audit_detail_record->material_id;
					$is_detail_row->quantity = $audit_detail_record->quantity;
					$is_detail_row->balance = $audit_detail_record->balance;
					$is_detail_row->name_of_receiver = $audit_detail_record->name_of_receiver;
					$is_detail_row->name_of_foreman = $audit_detail_record->name_of_foreman;
					$is_detail_row->time_issue = $audit_detail_record->time_issue;
					$is_detail_row->site_reference = $audit_detail_record->site_reference;
					$is_detail_row->changes = $audit_detail_record->changes;
					$is_detail_row->changes_status = $audit_detail_record->changes_status;
					if($erp_inventory_is_detail->save($is_detail_row))
					{
						$history_tbl = TableRegistry::get("erp_stock_history");
					
						$hstry_query = $history_tbl->query();
						$hstry_query->update()
							->set(["project_id"=>$project_id,"date"=>date("Y-m-d",strtotime($is_date)),"material_id"=>$audit_detail_record->material_id,"quantity"=>$audit_detail_record->quantity,"stock_in"=>$audit_detail_record->quantity])
							->where(["type_id"=>$is_id,"material_id"=>$h_material_id,"type"=>'is'])
							->execute();
					}
				}
			}
			/* overwite GRN Audit record on original GRN */
			
			/* Delete Audit GRN Record */
			$delete_ok = $erp_audit_is_detail->deleteAll(["is_id"=>$is_id]);
			if($delete_ok)
			{
				$audit_row = $erp_is_audit->get($audit_id);
				$ok = $erp_is_audit->delete($audit_row);
				
			}
			/* Delete Audit GRN Record */
		}
		
		$this->Flash->success(__('Record Approve Successfully', null), 
							'default', 
							array('class' => 'success'));
		return $this->redirect(array("controller" => "inventory","action" => "isaudit", '?' => array('project' => $data['project'])));
	}
	
	public function rbnaudit()
    {
		$project = isset($_REQUEST['project'])?$_REQUEST['project']:'';
		$erp_audit_rbn = TableRegistry::get('erp_audit_rbn'); 
		$erp_audit_rbn_detail = TableRegistry::get('erp_audit_rbn_detail'); 
		if($project != '')
		{
			$user = $this->request->session()->read('user_id');
			$projects_ids = $this->Usermanage->users_project($user);
			$role = $this->role;
			// debug($post);die;
			$or = array();				
			
			$or["erp_audit_rbn.project_id"] = (!empty($project) && $project != "All" )?$project:NULL;
			
			if($or["erp_audit_rbn.project_id"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["erp_audit_rbn.project_id IN"] = $projects_ids;
				}
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			############################# Inner Join Query #############################################
			if(!empty($or))
			{
				$result = $erp_audit_rbn->find()->select($erp_audit_rbn)->order(['erp_audit_rbn.rbn_date'=>'DESC']);
				$result = $result->innerjoin(
					["erp_audit_rbn_detail"=>"erp_audit_rbn_detail"],
					["erp_audit_rbn.audit_id = erp_audit_rbn_detail.audit_id"])
					->where([$or])->select($erp_audit_rbn_detail)->hydrate(false)->toArray();
			}else{
				$result = $erp_audit_rbn->find()->select($erp_audit_rbn)->order(['erp_audit_rbn.rbn_date'=>'DESC']);
				$result = $result->innerjoin(
					["erp_audit_rbn_detail"=>"erp_audit_rbn_detail"],
					["erp_audit_rbn.audit_id = erp_audit_rbn_detail.audit_id"])
					->select($erp_audit_rbn_detail)->hydrate(false)->toArray();
			}
			
				
			$new_array = array();
			foreach($result as $retrive)
			{
				if(isset($new_array[$retrive['rbn_no']]))
				{
					$new_array[$retrive['rbn_no']]['erp_audit_rbn_detail'][] = $retrive['erp_audit_rbn_detail'];
				}else{
					$a = $retrive["erp_audit_rbn_detail"];
					unset($retrive["erp_audit_rbn_detail"]);
					$new_array[$retrive["rbn_no"]] = $retrive;
					$new_array[$retrive["rbn_no"]]['erp_audit_rbn_detail'][] = $a;
				}
				
			}
			$rbn_list = $new_array;
			############################# Inner Join Query #############################################
			
			
			// if(!empty($or)){
				// $rbn_list = $erp_audit_rbn->find()->where([$or])->order(['rbn_date'=>'DESC'])->hydrate(false)->toArray();
			// }else{
				// $rbn_list = $erp_audit_rbn->find()->order(['rbn_date'=>'DESC'])->hydrate(false)->toArray();
			// }
			$this->set("rbn_list",$rbn_list);
			$this->set("project",$project);
		}
		$this->set('role',$this->role);	
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$conn = ConnectionManager::get('default');
		
		
		if($this->request->is("post"))
		{ 
			$post = $this->request->data;
			$user = $this->request->session()->read('user_id');
			$projects_ids = $this->Usermanage->users_project($user);
			$role = $this->role;
			// debug($post);die;
			$or = array();				
			
			$or["erp_audit_rbn.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All" )?$post["project_id"]:NULL;
			
			if($or["erp_audit_rbn.project_id"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["erp_audit_rbn.project_id IN"] = $projects_ids;
				}
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			############################# Inner Join Query #############################################
			if(!empty($or))
			{
				$result = $erp_audit_rbn->find()->select($erp_audit_rbn)->order(['erp_audit_rbn.rbn_date'=>'DESC']);
				$result = $result->innerjoin(
					["erp_audit_rbn_detail"=>"erp_audit_rbn_detail"],
					["erp_audit_rbn.audit_id = erp_audit_rbn_detail.audit_id"])
					->where([$or])->select($erp_audit_rbn_detail)->hydrate(false)->toArray();
			}else{
				$result = $erp_audit_rbn->find()->select($erp_audit_rbn)->order(['erp_audit_rbn.rbn_date'=>'DESC']);
				$result = $result->innerjoin(
					["erp_audit_rbn_detail"=>"erp_audit_rbn_detail"],
					["erp_audit_rbn.audit_id = erp_audit_rbn_detail.audit_id"])
					->select($erp_audit_rbn_detail)->hydrate(false)->toArray();
			}
			
				
			$new_array = array();
			foreach($result as $retrive)
			{
				if(isset($new_array[$retrive['rbn_no']]))
				{
					$new_array[$retrive['rbn_no']]['erp_audit_rbn_detail'][] = $retrive['erp_audit_rbn_detail'];
				}else{
					$a = $retrive["erp_audit_rbn_detail"];
					unset($retrive["erp_audit_rbn_detail"]);
					$new_array[$retrive["rbn_no"]] = $retrive;
					$new_array[$retrive["rbn_no"]]['erp_audit_rbn_detail'][] = $a;
				}
				
			}
			$rbn_list = $new_array;
			############################# Inner Join Query #############################################
			
			
			// if(!empty($or)){
				// $rbn_list = $erp_audit_rbn->find()->where([$or])->order(['rbn_date'=>'DESC'])->hydrate(false)->toArray();
			// }else{
				// $rbn_list = $erp_audit_rbn->find()->order(['rbn_date'=>'DESC'])->hydrate(false)->toArray();
			// }
			$this->set("rbn_list",$rbn_list);
			$this->set("project",$post['project_id']);
			 			
		}		
			
    }
	
	public function updaterbnaudit($audit_id)
	{
		$user_action = 'edit';
		$this->set('form_header','Edit Returned Back Note (RBN) Audit');
		$this->set('button_text','Update Audit R.B.N.');
		$this->set('user_action',$user_action);	
				
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);
		 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);		
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_list',$agency_list);
		
		$erp_audit_rbn = TableRegistry::get("erp_audit_rbn");
		$erp_audit_rbn_detail = TableRegistry::get("erp_audit_rbn_detail");
		
		$materials = $erp_audit_rbn->find("all")->where(["erp_audit_rbn.audit_id"=>$audit_id])->select($erp_audit_rbn);
		$materials = $materials->rightjoin(
						["erp_audit_rbn_detail"=>"erp_audit_rbn_detail"],
						["erp_audit_rbn.audit_id = erp_audit_rbn_detail.audit_id"])->select($erp_audit_rbn_detail)->hydrate(false)->toArray();
		
		foreach($materials as $mat)
		{
			$items["erp_audit_rbn_detail"][] = $mat["erp_audit_rbn_detail"];			
		}
		
		$this->set("items",$items);
		$this->set("rbndata",$materials[0]);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			// debug($post);die;
						
			$post["rbn_date"] = date("Y-m-d",strtotime($post["rbn_date"]));
			$row = $erp_audit_rbn->get($audit_id);
		
			$save_row = $erp_audit_rbn->patchEntity($row,$post);
			$diff = $save_row->extract($save_row->visibleProperties(), true);
			
			
			unset($diff['project_code']);
			unset($diff['material']);
			
			if(empty($row["changes"]))
			{
				/* Add user detail who make changes */
				if(!empty($diff))
				{
					$diff["edit_by"] = $this->request->session()->read('user_id');				
					$diff = json_encode($diff);
					$changes[date("Y-m-d H:i:s")] = $diff;
					$save_row["changes"] = json_encode($changes);
					
					$save_row["changes_status"] = 1;
				}
				/* Add user detail who make changes */
			}else{
				if(!empty($diff))
				{
					$diff["edit_by"] = $this->request->session()->read('user_id');				
					$diff = json_encode($diff);
					$changes[date("Y-m-d H:i:s")] = $diff;
					$changes = json_encode($changes);
					// debug($changes);die;
					$save_row["changes"] = json_encode(array_merge(json_decode($changes, true),json_decode($row["changes"], true)));
					$save_row["changes_status"] = 1;
				}
			}
			
			if($erp_audit_rbn->save($save_row))
			{
				$updated = 0;
				$material_items = $post["material"];
				foreach($material_items['material_id'] as $key => $data)
				{		
					$changes1 = array();
					$save_data['material_id'] =  $material_items['material_id'][$key];
					$save_data['brand_id'] =  $material_items['brand_id'][$key];
					$save_data['quantity_reurn'] =  $material_items['quantity_reurn'][$key];
					$save_data['name_of_foreman'] =  $material_items['name_of_foreman'][$key];
					$save_data['time_of_return'] =  $material_items['time_of_return'][$key];
					
					$entity_data = $erp_audit_rbn_detail->get($material_items['detail_id'][$key]);
					
					$material_data=$erp_audit_rbn_detail->patchEntity($entity_data,$save_data);
					$diff1 = $material_data->extract($material_data->visibleProperties(), true);
			
					if(empty($entity_data["changes"]))
					{
						/* Add user detail who make changes */
						if(!empty($diff1))
						{
							$diff1["edit_by"] = $this->request->session()->read('user_id');				
							$diff1 = json_encode($diff1);
							$changes1[date("Y-m-d H:i:s")] = $diff1;
							$material_data["changes"] = json_encode($changes1);
							$updated = 1;
							$material_data["changes_status"] = 1;
						}
						/* Add user detail who make changes */
					}else{
						if(!empty($diff1))
						{
							$diff1["edit_by"] = $this->request->session()->read('user_id');				
							$diff1 = json_encode($diff1);
							$changes1[date("Y-m-d H:i:s")] = $diff1;
							$changes1 = json_encode($changes1);
												
							$material_data["changes"] = json_encode(array_merge(json_decode($changes1, true),json_decode($material_data["changes"], true)));
							$updated = 1;
							$material_data["changes_status"] = 1;
						}
					}
					// debug($material_data);die;
					$erp_audit_rbn_detail->save($material_data);
						
				}
				if($updated > 0)
				{
					$record = $erp_audit_rbn->get($audit_id);
					$record->changes_status = 1;
					$erp_audit_rbn->save($record);
				}
			}
			
			echo "<script>opener.location.reload();</script>";
			echo "<script>window.close();</script>";
		}
								
	}
	
	public function approveauditrbn()
    {
		$this->autoRender = false;
		$data = $this->request->data();
		$erp_inventory_rbn = TableRegistry::get("erp_inventory_rbn");
		$erp_inventory_rbn_detail = TableRegistry::get("erp_inventory_rbn_detail");
		$erp_inventory_rbn_history = TableRegistry::get("erp_inventory_rbn_history");
		$erp_inventory_rbn_detail_history = TableRegistry::get("erp_inventory_rbn_detail_history");
		$erp_audit_rbn = TableRegistry::get("erp_audit_rbn");
		$erp_audit_rbn_detail = TableRegistry::get("erp_audit_rbn_detail");
		
		foreach($data['auditid'] as $audit_id)
		{
			$audit_record = $erp_audit_rbn->get($audit_id);
			$rbn_id = $audit_record->rbn_id;
			$project_id = $audit_record->project_id;
			$rbn_date = $audit_record->rbn_date;
			
			$party_id = $audit_record->agency_name;
			
			/* Redirect back and do not update if stock going nagative after edit*/
			$audit_detail_data = $erp_audit_rbn_detail->find()->where(["rbn_id"=>$rbn_id])->hydrate(false)->toArray();
			
			foreach($audit_detail_data as $auditd_retrive)
			{
				$available_stock = $this->ERPfunction->get_current_stock($project_id,$auditd_retrive['material_id']);

				$rbnd_id = $auditd_retrive["rbn_detail_id"];
				$rbnd_record = $erp_inventory_rbn_detail->get($rbnd_id);
				$old_qty = $rbnd_record->quantity_reurn;
				$difference = $old_qty - $auditd_retrive['quantity_reurn'];
				
				$stock_after = $available_stock - $difference;
				
				if($stock_after < 0)
				{
					$m = $this->ERPfunction->get_material_title($auditd_retrive['material_id']);
					$this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
					// return $this->redirect(["action"=>"rbnaudit"]);
					return $this->redirect(array("controller" => "inventory","action" => "rbnaudit", '?' => array('project' => $data['project'])));
				}
			}
			
			/* Redirect back and do not update if stock going nagative after edit*/
			
			/* Redirect back if return quantity more than till date issued quantity */
			
			foreach($audit_detail_data as $auditd_retrive)
			{
				$till_date_issued_qty = $this->ERPfunction->get_rbntilldate_quantity($project_id,$rbn_date,$party_id,$auditd_retrive['material_id'],$rbn_id);
				
				$return_qty = $auditd_retrive['quantity_reurn'];
								
				if($return_qty > $till_date_issued_qty)
				{
					$m = $this->ERPfunction->get_material_title($auditd_retrive['material_id']);
					$this->Flash->error(__("ERROR : You can't return quantity more than still date issued for material {$m},Please Try again", null), 'default',array('class' => 'success'));
					// return $this->redirect(["action"=>"rbnaudit"]);
					return $this->redirect(array("controller" => "inventory","action" => "rbnaudit", '?' => array('project' => $data['project'])));
				}
			}
			
			/* Redirect back if return quantity more than till date issued quantity */
				
			$rbn_record = $erp_inventory_rbn->get($rbn_id);
						
			/* First store IS original record in history table */
			$rbn_record_new = $rbn_record->toArray();
			$rbn_histiry_data = $erp_inventory_rbn_history->newEntity($rbn_record_new);			
					
			if($erp_inventory_rbn_history->save($rbn_histiry_data))
			{
				$rbn_detail_data = $erp_inventory_rbn_detail->find()->where(["rbn_id"=>$rbn_id])->hydrate(false)->toArray();
				foreach($rbn_detail_data as $history_retrive)
				{
					$detail_id = $history_retrive["rbn_detail_id"];
					$rbn_detail_record = $erp_inventory_rbn_detail->get($detail_id);
					$rbn_detail_record_new = $rbn_detail_record->toArray();
					$rbn_detail_histiry_data = $erp_inventory_rbn_detail_history->newEntity($rbn_detail_record_new);
					$erp_inventory_rbn_detail_history->save($rbn_detail_histiry_data);
				}
			}
			/* First store GRN original record in history table */
			
			/* overwite GRN Audit record on original GRn */
			
			$rbn_record->project_id = $audit_record->project_id;
			$rbn_record->rbn_no = $audit_record->rbn_no;
			$rbn_record->rbn_date = $audit_record->rbn_date;
			$rbn_record->agency_name = $audit_record->agency_name;
			$rbn_record->quantity_checkby = $audit_record->quantity_checkby;
			$rbn_record->accepted_by = $audit_record->accepted_by;
			$rbn_record->approved_by = $audit_record->approved_by;
			$rbn_record->return_backby = $audit_record->return_backby;
			$rbn_record->approved_status = $audit_record->approved_status;
			$rbn_record->approved_date = $audit_record->approved_date;
			$rbn_record->changes = $audit_record->changes;
			$rbn_record->changes_status = $audit_record->changes_status;
			if($erp_inventory_rbn->save($rbn_record))
			{
				$audit_detail_data = $erp_audit_rbn_detail->find()->where(["rbn_id"=>$rbn_id])->hydrate(false)->toArray();
				
				foreach($audit_detail_data as $audit_retrive)
				{
					$auditDetail_id = $audit_retrive["audit_detail_id"];
					$rbnDetail_id = $audit_retrive["rbn_detail_id"];
					$audit_detail_record = $erp_audit_rbn_detail->get($auditDetail_id);
					
					$rbn_detail_row = $erp_inventory_rbn_detail->get($rbnDetail_id);
					$h_material_id = $rbn_detail_row->material_id;
					$rbn_detail_row->material_id = $audit_detail_record->material_id;
					$rbn_detail_row->brand_id = $audit_detail_record->brand_id;
					$rbn_detail_row->quantity_reurn = $audit_detail_record->quantity_reurn;
					$rbn_detail_row->return_by = $audit_detail_record->return_by;
					$rbn_detail_row->name_of_foreman = $audit_detail_record->name_of_foreman;
					$rbn_detail_row->time_of_return = $audit_detail_record->time_of_return;
					$rbn_detail_row->return_reason = $audit_detail_record->return_reason;
					$rbn_detail_row->changes = $audit_detail_record->changes;
					$rbn_detail_row->changes_status = $audit_detail_record->changes_status;
					if($erp_inventory_rbn_detail->save($rbn_detail_row))
					{
						$history_tbl = TableRegistry::get("erp_stock_history");
					
						$hstry_query = $history_tbl->query();
						$hstry_query->update()
							->set(["project_id"=>$project_id,"date"=>date("Y-m-d",strtotime($rbn_date)),"material_id"=>$audit_detail_record->material_id,"quantity"=>$audit_detail_record->quantity_reurn,"return_back"=>$audit_detail_record->quantity_reurn])
							->where(["type_id"=>$rbn_id,"material_id"=>$h_material_id,"type"=>'rbn'])
							->execute();
					}
				}
			}
			/* overwite GRN Audit record on original GRN */
			
			/* Delete Audit GRN Record */
			$delete_ok = $erp_audit_rbn_detail->deleteAll(["rbn_id"=>$rbn_id]);
			if($delete_ok)
			{
				$audit_row = $erp_audit_rbn->get($audit_id);
				$ok = $erp_audit_rbn->delete($audit_row);
				
			}
			/* Delete Audit GRN Record */
		}
		
		$this->Flash->success(__('Record Approve Successfully', null), 
							'default', 
							array('class' => 'success'));
		return $this->redirect(array("controller" => "inventory","action" => "rbnaudit", '?' => array('project' => $data['project'])));
	}
	
	public function auditrbnchanges($rbn_id)
    {
		$erp_inve_rbn = TableRegistry::get('erp_inventory_rbn'); 
		$erp_inve_rbn_details = TableRegistry::get('erp_inventory_rbn_detail'); 
		$erp_rbn_details = $erp_inve_rbn->get($rbn_id);
		$this->set('erp_rbn_details',$erp_rbn_details);  
		$previw_list = $erp_inve_rbn_details->find()->where(array('rbn_id'=>$rbn_id,'approved'=>1));
		$this->set('previw_list',$previw_list); 

    }
	
	public function inventorypreparedebit()
	{
		$erp_inventory_debit_note = TableRegistry::get('erp_inventory_debit_note');
		
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_material = TableRegistry::get('erp_material');
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_code !="=>17,"material_id IN"=>$material_ids,"project_id"=>0]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id"=>0]);
		}
		$this->set('material_list',$material_list);
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		$this->set('vendor_assets',$vendor_list);
		
		$user = $this->request->session()->read('user_id');
		
		if($this->request->is('post'))
		{
			$data = $this->request->data;
			if(isset($data["debit_doc"]))
			{
				$file =$data["debit_doc"]["name"];
				
				$ext = $this->ERPfunction->check_valid_extension($file);
				// debug($ext);die;
				if($ext != 0) {
					
					
					// debug($data);die;
					$entity_data = $erp_inventory_debit_note->newEntity();
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
					$entity_data['debit_to'] = $data['agency_name'];
					$entity_data['receiver_name'] = $data['receiver_name'];
					$entity_data['reason'] = $data['reason'];
					$entity_data['created_by'] = $user;
					$entity_data['created_date'] = date('Y-m-d');
					
					if($erp_inventory_debit_note->save($entity_data))
					{
						 $debit_id = $entity_data->debit_id;
						 $this->ERPfunction->add_inventory_debit_detail($data['debit'],$debit_id,$data['total_amount'],$data['total_words']);
						 $this->Flash->success(__('Record Insert Successfully.'));
						 return $this->redirect(['action' => 'inventorypreparedebit']);
					 }
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
					
					// debug($data);die;
					$entity_data = $erp_inventory_debit_note->newEntity();
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
					$entity_data['debit_to'] = $data['agency_name'];
					$entity_data['receiver_name'] = $data['receiver_name'];
					$entity_data['reason'] = $data['reason'];
					$entity_data['created_by'] = $user;
					$entity_data['created_date'] = date('Y-m-d');
					
					if($erp_inventory_debit_note->save($entity_data))
					{
						 $debit_id = $entity_data->debit_id;
						 $this->ERPfunction->add_inventory_debit_detail($data['debit'],$debit_id,$data['total_amount'],$data['total_words']);
						 $this->Flash->success(__('Record Insert Successfully.'));
						 return $this->redirect(['action' => 'inventorypreparedebit']);
					 }

			}
		
		}
	}
	
	public function inventorydebitnotealert()
	{
		$erp_inventory_debit_note = TableRegistry::get("erp_inventory_debit_note");
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
		
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids))
			{
				$result = $erp_inventory_debit_note->find()->where(['erp_inventory_debit_note.project_id in'=>$projects_ids])->hydrate(false)->toArray();
			}
			else
			{
				$result=array();
			}
		}
		else
		{
			$result = $erp_inventory_debit_note->find()->hydrate(false)->toArray();
		}
		$this->set('debit_list',$result);
		
		if($this->request->is("post"))
		{
			$erp_debit_note_detail = TableRegistry::get("erp_debit_note_detail");
			$post = $this->request->data;
			$or = array();				
			
			$or["erp_inventory_debit_note.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			
			$or["erp_inventory_debit_note.debit_to"] = (!empty($post["party_id"]) && $post["party_id"] != "All")?$post["party_id"]:NULL;
			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			 // debug($post);
			 // debug($or);die;
			
		
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_ids))
				{
					if(!empty($or))
					{
						$result = $erp_inventory_debit_note->find()->where(['erp_inventory_debit_note.project_id in'=>$projects_ids,$or])->select($erp_inventory_debit_note)->hydrate(false)->toArray();
					}else{
						$result = $erp_inventory_debit_note->find()->where(['erp_inventory_debit_note.project_id in'=>$projects_ids])->select($erp_inventory_debit_note)->hydrate(false)->toArray();
					}
					
				}
				else
				{
					$result=array();
				}
			}
			else
			{
				if(!empty($or))
				{
					$result = $erp_inventory_debit_note->find()->where($or)->select($erp_inventory_debit_note)->hydrate(false)->toArray();
				}else{
					$result = $erp_inventory_debit_note->find()->select($erp_inventory_debit_note)->hydrate(false)->toArray();
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
			$this->render("inventory_debit_alertpdf");
		}
		
	}
	
	public function ponorate()
    {
		ini_set('memory_limit', '-1');
		$this->set("user_role",$this->role);
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
		
		$erp_material = TableRegistry::get('erp_material');
		if($role == "deputymanagerelectric")
		{	
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_id IN"=>$material_ids]);
		}else{
			$material_list = $erp_material->find();
		}
		$this->set('material_list',$material_list);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		$erp_material_brand = TableRegistry::get('erp_material_brand');
		$brand_list = $erp_material_brand->find();
		$this->set('brand_list',$brand_list);
		
		$user = $this->request->session()->read('user_id');
		$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		
		$this->set("back","index");
		
		if($this->request->is('post'))
		{	
			if(isset($this->request->data["export_csv"]))
			{
				$erp_inventory_po = TableRegistry::get("erp_inventory_po");
				$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
				// debug($this->request->data);die;
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_material_id"] = (!empty($post["e_material_id"]))?explode(",",$post["e_material_id"]):NULL;
				$post["e_brand_id"] = (!empty($post["e_brand_id"]))?explode(",",$post["e_brand_id"]):NULL;
				$post["e_vendor_userid"] = (!empty($post["e_vendor_userid"]))?explode(",",$post["e_vendor_userid"]):NULL;
				
				$or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All")?$post["e_brand_id"]:NULL;
				$or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"]))?$post["e_po_no"]:NULL;
				$or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				$or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				
				if($or["erp_inventory_po.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_inventory_po.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_inventory_po_detail.approved !="] = 0;
				
				$result = $erp_inventory_po->find()->select($erp_inventory_po);
				$result = $result->innerjoin(
					["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
					["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
					->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","PO Type");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_inventory_po_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_po_detail"]);
					}
					if(is_numeric($retrive_data['material_id']))
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
						$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					}
					else
					{
						$mt = $retrive_data['material_id'];
						$brnd = $retrive_data['brand_id'];
						$static_unit = $retrive_data['static_unit'];
					}
					$po_type=$retrive_data['po_type'];
					if($po_type == "po")
					{
						$type_name = "PO";
					}elseif($po_type == "manual_po")
					{
						$type_name = "Manual PO";
					}elseif($po_type == "local_po"){
						$type_name = "Local PO";
					}
					
					$csv = array();
					$csv[] = $retrive_data['po_no'];
					$csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $retrive_data['quantity'];
					$csv[] = $static_unit;
					$csv[] = $type_name;
					$rows[] = $csv;
				}
				
				$filename = "po_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$erp_inventory_po = TableRegistry::get("erp_inventory_po");
				$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
				// debug($this->request->data);die;
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_material_id"] = (!empty($post["e_material_id"]))?explode(",",$post["e_material_id"]):NULL;
				$post["e_brand_id"] = (!empty($post["e_brand_id"]))?explode(",",$post["e_brand_id"]):NULL;
				$post["e_vendor_userid"] = (!empty($post["e_vendor_userid"]))?explode(",",$post["e_vendor_userid"]):NULL;
				
				$or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All")?$post["e_brand_id"]:NULL;
				$or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"]))?$post["e_po_no"]:NULL;
				$or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				$or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				
				if($or["erp_inventory_po.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_inventory_po.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_inventory_po_detail.approved !="] = 0;
				
				$result = $erp_inventory_po->find()->select($erp_inventory_po);
				$result = $result->innerjoin(
					["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
					["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
					->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","PO Type");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_inventory_po_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_po_detail"]);
					}
					if(is_numeric($retrive_data['material_id']))
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
						$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					}
					else
					{
						$mt = $retrive_data['material_id'];
						$brnd = $retrive_data['brand_id'];
						$static_unit = $retrive_data['static_unit'];
					}
					$po_type=$retrive_data['po_type'];
					if($po_type == "po")
					{
						$type_name = "PO";
					}elseif($po_type == "manual_po")
					{
						$type_name = "Manual PO";
					}elseif($po_type == "local_po"){
						$type_name = "Local PO";
					}
					
					$csv = array();
					$csv[] = $retrive_data['po_no'];
					$csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $retrive_data['quantity'];
					$csv[] = $static_unit;
					$csv[] = $type_name;
					$rows[] = $csv;
				}
				$this->set("rows",$rows);
				$this->render("ponoraterecordpdf");
			}
		}
    }
	
	public function wonorate()
	{
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
		
		$this->set('role',$this->role);
		$this->set('wo_date',$result);
		
		if($this->request->is('post'))
		{			
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
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract");
				
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
					// $csv[] = $retrive_data['amount'];
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
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract");
				
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
					// $csv[] = $retrive_data['amount'];
					$rows[] = $csv;
				}
				
				$this->set("rows",$rows);
				$this->render("worecordspdf");
			}
		}
	}

	public function planningWoNoRate() {
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
		
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		
		if($this->role == "deputymanagerelectric")
		{
			$result = $wod_table->find()->select($wod_table)->where(["approved"=>1,"project_id IN"=>$projects_ids]);
			$result = $result->innerjoin(
			["erp_planning_work_order"=>"erp_planning_work_order"],
			["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_detail_id"])
			->select($wo_table)->order(['erp_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
		}else{
			$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
			$result = $result->innerjoin(
			["erp_planning_work_order"=>"erp_planning_work_order"],
			["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_detail_id"])
			->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
		}
		
		$this->set('role',$this->role);
		$this->set('wo_date',$result);
		
		if($this->request->is('post'))
		{			
			if(isset($this->request->data["export_csv"]))
			{
				// debug($this->request->data);die;
				$erp_work_order = TableRegistry::get("erp_planning_work_order");
				$erp_work_order_detail = TableRegistry::get("erp_planning_work_order_detail");
				
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
				$or["erp_planning_work_order.last_wo ="] = 1;
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
				
				// $result = $erp_work_order->find()->select($erp_work_order);
				// $result = $result->innerjoin(
					// ["erp_work_order_detail"=>"erp_work_order_detail"],
					// ["erp_work_order.wo_id = erp_work_order_detail.wo_id"])
					// ->where($or)->select("sum(erp_work_order_detail.amount)")->order(['erp_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
				
				$query = $wod_table->find();
				$result = $query->select(['amount' => $query->func()->sum('erp_planning_work_order_detail.amount')])->GROUP(["erp_planning_work_order_detail.wo_id"]);
				$result = $result->innerjoin(
					["erp_planning_work_order"=>"erp_planning_work_order"],
					["erp_planning_work_order.wo_id = erp_planning_work_order_detail.wo_id"])
					->where($or)->select($wo_table)->order(['erp_planning_work_order.wo_date'=>'DESC'])->hydrate(false)->toArray();
					
				$rows = array();
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract");
				
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
					// $csv[] = $retrive_data['amount'];
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
				$or["erp_planning_work_order.last_wo ="] = 1;
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
				$rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract");
				
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
					// $csv[] = $retrive_data['amount'];
					$rows[] = $csv;
				}
				
				$this->set("rows",$rows);
				$this->render("worecordspdf");
			}
		}
	}
	
	public function previewpo2norate($po_id)
    {
		$erp_inve_po = TableRegistry::get('erp_inventory_po'); 
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail'); 
		$erp_po_details = $erp_inve_po->get($po_id);
		$this->set('erp_po_details',$erp_po_details);  
		$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$po_id));
		$this->set('previw_list',$previw_list);   
    }
	
	public function previewapprovedwonorate($wo_id)
	{
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>1]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}

	public function planningpreviewapprovedwonorate($wo_id)
	{
		
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>1]);
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
	public function printapprovedworecordnorate($wo_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		$wo_data = $wo_table->get($wo_id);
		$wod_data = $wod_table->find()->where(['wo_id'=>$wo_id,"approved"=>1]);
		$this->set('wo_data',$wo_data);
		$this->set('wod_data',$wod_data);
	}
	
	public function viewinventorydebit($debit_id)
	{
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$this->set('agency_list',$agency_list);
		
		$erp_debit_note = TableRegistry::get("erp_inventory_debit_note");
		$erp_debit_note_detail = TableRegistry::get("erp_inventory_debit_note_detail");
		
		$debit_list = $erp_debit_note->get($debit_id)->toArray();
		$this->set('debit_list',$debit_list);
		
		
		$detail_data = $erp_debit_note_detail->find("all")->where(["debit_id"=>$debit_id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);	
		
		$user = $this->request->session()->read('user_id');
	}
	
	public function printinventorydebit($debit_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$erp_debit_note = TableRegistry::get("erp_inventory_debit_note");
		$erp_debit_note_detail = TableRegistry::get("erp_inventory_debit_note_detail");
		$detail_list = $erp_debit_note_detail->find()->where(array('debit_id'=>$debit_id));
		$this->set('detail_list',$detail_list);
		$data = $erp_debit_note->get($debit_id);
		$this->set("debit_list",$data->toArray());			
	}
	
	public function editinventorydebit($debit_id)
	{		
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
				
		$erp_material = TableRegistry::get('erp_material');
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_code !="=>17,"material_id IN"=>$material_ids]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17]);
		}
		$this->set('material_list',$material_list);
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		$this->set('vendor_assets',$vendor_list);
		
		$erp_debit_note = TableRegistry::get("erp_inventory_debit_note");
		$erp_debit_note_detail = TableRegistry::get("erp_inventory_debit_note_detail");
		
		$debit_list = $erp_debit_note->get($debit_id)->toArray();
		$this->set('debit_list',$debit_list);
		
		
		$detail_data = $erp_debit_note_detail->find("all")->where(["debit_id"=>$debit_id])->hydrate(false)->toArray();
		$this->set("detail_data",$detail_data);	
		
		$user = $this->request->session()->read('user_id');
		
		if($this->request->is('post'))
		{
			$data = $this->request->data;
			
			if(isset($data["debit_doc"]))
			{
				$file =$data["debit_doc"]["name"];
				
				$ext = $this->ERPfunction->check_valid_extension($file);
				// debug($ext);die;
				if($ext != 0) {
				
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
					$entity_data['debit_to'] = $data['agency_name'];
					$entity_data['receiver_name'] = $data['receiver_name'];
					$entity_data['reason'] = $data['reason'];
					
					if($erp_debit_note->save($entity_data))
					{
						 $this->ERPfunction->edit_inventory_debit_detail($data['debit'],$debit_id,$data['total_amount'],$data['total_words']);
						 $this->Flash->success(__('Record Update Successfully.'));
						 echo "<script>window.close();</script>";
					 }
					
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
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
					$entity_data['debit_to'] = $data['agency_name'];
					$entity_data['receiver_name'] = $data['receiver_name'];
					$entity_data['reason'] = $data['reason'];
					
					if($erp_debit_note->save($entity_data))
					{
						 $this->ERPfunction->edit_inventory_debit_detail($data['debit'],$debit_id,$data['total_amount'],$data['total_words']);
						 $this->Flash->success(__('Record Update Successfully.'));
						 echo "<script>window.close();</script>";
					 }
			}
			
			// debug($data);die;
			
		}
	}
	
	public function deleteinventorydebit($debit_id)
	{
		$erp_debit_note_detail = TableRegistry::get('erp_inventory_debit_note_detail');
		$delete_ok = $erp_debit_note_detail->deleteAll(["debit_id"=>$debit_id]);
		if($delete_ok)
		{				
			$tbl = TableRegistry::get("erp_inventory_debit_note");
			$row = $tbl->get($debit_id);
			if($tbl->delete($row))
			{
				$this->Flash->success(__('Record delete Successfully.'));
				return $this->redirect(['action'=>'debitnotealert']);
			}
		}
	}
	
	public function inventorydebitrecords()
	{
		$erp_debit_note = TableRegistry::get("erp_inventory_debit_note");
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
					
		$user = $this->request->session()->read('user_id');
		$projects_ids = $this->Usermanage->users_project($user);
		
		$role = $this->role;
		$this->set('role',$role);
	}
	
	public function previewdebit($debit_id,$stock = NULL)
    {
		$erp_inventory_debit_note = TableRegistry::get('erp_inventory_debit_note'); 
		$erp_inventory_debit_note_detail = TableRegistry::get('erp_inventory_debit_note_detail'); 
		$debit_detail = $erp_inventory_debit_note->get($debit_id);
		$this->set('debit_detail',$debit_detail);  
		$previw_list = $erp_inventory_debit_note_detail->find()->where(['debit_id'=>$debit_id]);
		$this->set('previw_list',$previw_list); 
		$this->set('stock',$stock); 
    }
	
	public function previewauditis($audit_is_id)
    {
		$erp_inve_is = TableRegistry::get('erp_is_audit'); 
		$erp_inve_is_details = TableRegistry::get('erp_audit_is_detail'); 
		$erp_is_details = $erp_inve_is->get($audit_is_id);
		$this->set('erp_is_details',$erp_is_details);  
		$previw_list = $erp_inve_is_details->find()->where(array('is_audit_id'=>$audit_is_id,'approved'=>1));
		$this->set('previw_list',$previw_list);
    }
	
	public function prinauditis($audit_is_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');		
		$erp_inve_is = TableRegistry::get('erp_is_audit'); 
		$erp_inve_is_details = TableRegistry::get('erp_audit_is_detail'); 
		$erp_is_details = $erp_inve_is->get($audit_is_id);
		$this->set('data',$erp_is_details);  
		$previw_list = $erp_inve_is_details->find()->where(array('is_audit_id'=>$audit_is_id,"approved"=>1));
		$this->set('previw_list',$previw_list);			
	}
	
	public function previewauditrbn($audit_id,$stock = NULL)
    {
		$erp_inve_rbn = TableRegistry::get('erp_audit_rbn'); 
		$erp_inve_rbn_details = TableRegistry::get('erp_audit_rbn_detail'); 
		$erp_rbn_details = $erp_inve_rbn->get($audit_id);
		$this->set('erp_rbn_details',$erp_rbn_details);  
		$previw_list = $erp_inve_rbn_details->find()->where(array('audit_id'=>$audit_id,'approved'=>1));
		$this->set('previw_list',$previw_list); 

    }
	
	public function printauditrbn($audit_id)
    {
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_inve_rbn = TableRegistry::get('erp_audit_rbn'); 
		$erp_inve_rbn_details = TableRegistry::get('erp_audit_rbn_detail'); 
		$erp_rbn_details = $erp_inve_rbn->get($audit_id);
		$this->set('data',$erp_rbn_details);  
		$previw_list = $erp_inve_rbn_details->find()->where(array('audit_id'=>$audit_id,'approved'=>1));
		$this->set('previw_list',$previw_list); 

    }
	
	public function previewauditgrn($audit_id)
    {
		$erp_inve_grn = TableRegistry::get('erp_audit_grn'); 
		$erp_inve_grn_details = TableRegistry::get('erp_audit_grn_detail'); 
		$erp_grn_details = $erp_inve_grn->get($audit_id);
		$this->set('erp_grn_details',$erp_grn_details);  
		$previw_list = $erp_inve_grn_details->find()->where(array('audit_id'=>$audit_id,"approved"=>1));
		$this->set('previw_list',$previw_list); 
    }
	
	public function printauditgrnrecord($audit_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_inve_grn = TableRegistry::get('erp_audit_grn'); 
		$erp_inve_grn_details = TableRegistry::get('erp_audit_grn_detail'); 
		$erp_grn_details = $erp_inve_grn->get($audit_id);
		$this->set('erp_grn_details',$erp_grn_details);  
		$previw_list = $erp_inve_grn_details->find()->where(array('audit_id'=>$audit_id,"approved"=>1));
		$this->set('previw_list',$previw_list); 		
	}
	
	public function inventorypostatus()
    {
		$this->set("user_role",$this->role);
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == "deputymanagerelectric"){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}	
		//$projects = $this->ERPfunction->get_projects();
		$this->set('projects',$projects);
		
		$erp_material = TableRegistry::get('erp_material');
		if($role == "deputymanagerelectric")
		{	
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_id IN"=>$material_ids]);
		}else{
			$material_list = $erp_material->find();
		}
		$this->set('material_list',$material_list);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		$erp_material_brand = TableRegistry::get('erp_material_brand');
		$brand_list = $erp_material_brand->find();
		$this->set('brand_list',$brand_list);
		
		$user = $this->request->session()->read('user_id');
		//var_dump($user);die;
		$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		
		$this->set("back","index");
		$this->set("projects_id",'');
		$this->set("from",'');
		$this->set("to",'');
		
		if($this->request->is('post'))
		{	
			if(isset($this->request->data["go1"]))
			{
				$erp_inventory_po = TableRegistry::get("erp_inventory_po");
				$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
				$post = $this->request->data;	
				$or = array();				
				
				if($post['po_type'] == "po")
				{
				$or["po_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["po_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["erp_inventory_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All")?$post["material_id"]:NULL;
				$or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All" )?$post["brand_id"]:NULL;
				$or["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All")?$post["vendor_userid"]:NULL;
				$or["po_no"] = (!empty($post["po_no"]))?$post["po_no"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				 //debug($post);
				 //debug($or);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($role =='projectdirector' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager' || $role =='projectcoordinator' || $role =='siteaccountant' || $role == "deputymanagerelectric")
				{
					if(!empty($projects_ids))
					{
						$result = $erp_inventory_po->find()->select($erp_inventory_po)->where(["project_id IN"=>$projects_ids]);
						$result = $result->innerjoin(
							["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
							["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved !="=>0])
							->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
							//var_dump($result);die;
						//$this->set('grn_list',$result);
					}
					else
					{
						$result=array();
					}
				}
				else
				{
					$result = $erp_inventory_po->find()->select($erp_inventory_po);
						$result = $result->innerjoin(
							["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
							["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved !="=>0])
							->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				$this->set('po_list',$result);
				$this->set('manual_po',array());
				}else{
				// For manual po search
				
				$erp_manual_po = TableRegistry::get("erp_manual_po");
				$erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");	
				$or1 = array();				
				
				$or1["po_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or1["po_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or1["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or1["erp_manual_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All")?$post["material_id"]:NULL;
				$or1["erp_manual_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All" )?$post["brand_id"]:NULL;
				$or1["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All")?$post["vendor_userid"]:NULL;
				$or1["po_no"] = (!empty($post["po_no"]))?$post["po_no"]:NULL;
				if($post['po_type'] == "manualpolocal")
				{
					//for manual po on base of grn search
					$or1["is_grn_base"] = 1;
				}else{
					$or1["is_grn_base !="] = 1;
				}
				$keys = array_keys($or1,"");				
				foreach ($keys as $k)
				{unset($or1[$k]);}
				// debug($post);
				// debug($or1);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($role =='projectdirector' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager' || $role =='projectcoordinator' || $role =='siteaccountant' || $role == "deputymanagerelectric")
				{
					if(!empty($projects_ids))
					{
						$manual_po_list = $erp_manual_po->find()->select($erp_manual_po)->where(["project_id IN"=>$projects_ids]);
						$manual_po_list = $manual_po_list->innerjoin(
							["erp_manual_po_detail"=>"erp_manual_po_detail"],
							["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved !="=>0])
							->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();
							//var_dump($result);die;
						//$this->set('grn_list',$result);
					}
					else
					{
						$manual_po_list=array();
					}
				}
				else
				{
					$manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
						$manual_po_list = $manual_po_list->innerjoin(
							["erp_manual_po_detail"=>"erp_manual_po_detail"],
							["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved !="=>0])
							->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				
				$this->set('po_list',array());
				$this->set('manual_po',$manual_po_list);
				}
			}
			if(isset($this->request->data["export_csv"]))
			{
				$erp_inventory_po = TableRegistry::get("erp_inventory_po");
				$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
				// debug($this->request->data);die;
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_material_id"] = (!empty($post["e_material_id"]))?explode(",",$post["e_material_id"]):NULL;
				$post["e_brand_id"] = (!empty($post["e_brand_id"]))?explode(",",$post["e_brand_id"]):NULL;
				$post["e_vendor_userid"] = (!empty($post["e_vendor_userid"]))?explode(",",$post["e_vendor_userid"]):NULL;
				
				$or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All")?$post["e_brand_id"]:NULL;
				$or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"]))?$post["e_po_no"]:NULL;
				$or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				$or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				
				if($or["erp_inventory_po.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_inventory_po.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_inventory_po_detail.approved !="] = 0;
				
				$result = $erp_inventory_po->find()->select($erp_inventory_po);
				$result = $result->innerjoin(
					["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
					["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
					->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","Final Rate","Amount","PO Type");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_inventory_po_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_po_detail"]);
					}
					if(is_numeric($retrive_data['material_id']))
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
						$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					}
					else
					{
						$mt = $retrive_data['material_id'];
						$brnd = $retrive_data['brand_id'];
						$static_unit = $retrive_data['static_unit'];
					}
					$po_type=$retrive_data['po_type'];
					if($po_type == "po")
					{
						$type_name = "PO";
					}elseif($po_type == "manual_po")
					{
						$type_name = "Manual PO";
					}elseif($po_type == "local_po"){
						$type_name = "Local PO";
					}
					
					$csv = array();
					$csv[] = $retrive_data['po_no'];
					$csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $retrive_data['quantity'];
					$csv[] = $static_unit;
					$csv[] = $retrive_data['single_amount'];
					$csv[] = $retrive_data['amount'];
					$csv[] = $type_name;
					$rows[] = $csv;
				}
				
				$filename = "po_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$erp_inventory_po = TableRegistry::get("erp_inventory_po");
				$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
				// debug($this->request->data);die;
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_material_id"] = (!empty($post["e_material_id"]))?explode(",",$post["e_material_id"]):NULL;
				$post["e_brand_id"] = (!empty($post["e_brand_id"]))?explode(",",$post["e_brand_id"]):NULL;
				$post["e_vendor_userid"] = (!empty($post["e_vendor_userid"]))?explode(",",$post["e_vendor_userid"]):NULL;
				
				$or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All")?$post["e_brand_id"]:NULL;
				$or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"]))?$post["e_po_no"]:NULL;
				$or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				$or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				
				if($or["erp_inventory_po.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_inventory_po.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_inventory_po_detail.approved !="] = 0;
				
				$result = $erp_inventory_po->find()->select($erp_inventory_po);
				$result = $result->innerjoin(
					["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
					["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
					->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","Final Rate","Amount","PO Type");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_inventory_po_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_po_detail"]);
					}
					if(is_numeric($retrive_data['material_id']))
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
						$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					}
					else
					{
						$mt = $retrive_data['material_id'];
						$brnd = $retrive_data['brand_id'];
						$static_unit = $retrive_data['static_unit'];
					}
					$po_type=$retrive_data['po_type'];
					if($po_type == "po")
					{
						$type_name = "PO";
					}elseif($po_type == "manual_po")
					{
						$type_name = "Manual PO";
					}elseif($po_type == "local_po"){
						$type_name = "Local PO";
					}
					
					$csv = array();
					$csv[] = $retrive_data['po_no'];
					$csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $retrive_data['quantity'];
					$csv[] = $static_unit;
					$csv[] = $retrive_data['single_amount'];
					$csv[] = $retrive_data['amount'];
					$csv[] = $type_name;
					$rows[] = $csv;
				}
				$this->set("rows",$rows);
				$this->render("porecordpdf");
			}
		}
		
			
		//// debug($result);
		//// debug($manual_po_list);die;
		//$this->set('po_list',$result);
		// $this->set('manual_po',$manual_po_list);
		//$this->set('manual_po',array());
    }
	
	public function inventorypodeliveryrecords()
    {
		$this->set("user_role",$this->role);
		$role = $this->role;
		if($role == "erpoperator")
		{
			$projects = $this->Usermanage->all_access_project($this->user_id);
		}elseif($role == "deputymanagerelectric"){
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}	
		//$projects = $this->ERPfunction->get_projects();
		$this->set('projects',$projects);
		
		$erp_material = TableRegistry::get('erp_material');
		if($role == "deputymanagerelectric")
		{	
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_id IN"=>$material_ids]);
		}else{
			$material_list = $erp_material->find();
		}
		$this->set('material_list',$material_list);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		
		$erp_material_brand = TableRegistry::get('erp_material_brand');
		$brand_list = $erp_material_brand->find();
		$this->set('brand_list',$brand_list);
		
		$user = $this->request->session()->read('user_id');
		//var_dump($user);die;
		$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		
		$this->set("back","index");
		$this->set("projects_id",'');
		$this->set("from",'');
		$this->set("to",'');
		
		if($this->request->is('post'))
		{	
			if(isset($this->request->data["go1"]))
			{
				$erp_inventory_po = TableRegistry::get("erp_inventory_po");
				$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
				$post = $this->request->data;	
				$or = array();				
				
				if($post['po_type'] == "po")
				{
				$or["po_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or["po_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["erp_inventory_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All")?$post["material_id"]:NULL;
				$or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All" )?$post["brand_id"]:NULL;
				$or["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All")?$post["vendor_userid"]:NULL;
				$or["po_no"] = (!empty($post["po_no"]))?$post["po_no"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				 //debug($post);
				 //debug($or);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($role =='projectdirector' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager' || $role =='projectcoordinator' || $role =='siteaccountant' || $role == "deputymanagerelectric")
				{
					if(!empty($projects_ids))
					{
						$result = $erp_inventory_po->find()->select($erp_inventory_po)->where(["project_id IN"=>$projects_ids]);
						$result = $result->innerjoin(
							["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
							["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved !="=>0])
							->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
							//var_dump($result);die;
						//$this->set('grn_list',$result);
					}
					else
					{
						$result=array();
					}
				}
				else
				{
					$result = $erp_inventory_po->find()->select($erp_inventory_po);
						$result = $result->innerjoin(
							["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
							["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved !="=>0])
							->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				$this->set('po_list',$result);
				$this->set('manual_po',array());
				}else{
				// For manual po search
				
				$erp_manual_po = TableRegistry::get("erp_manual_po");
				$erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");	
				$or1 = array();				
				
				$or1["po_date >="] = ($post["from_date"] != "")?date("Y-m-d",strtotime($post["from_date"])):NULL;
				$or1["po_date <="] = ($post["to_date"] != "")?date("Y-m-d",strtotime($post["to_date"])):NULL;
				$or1["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or1["erp_manual_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All")?$post["material_id"]:NULL;
				$or1["erp_manual_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All" )?$post["brand_id"]:NULL;
				$or1["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All")?$post["vendor_userid"]:NULL;
				$or1["po_no"] = (!empty($post["po_no"]))?$post["po_no"]:NULL;
				if($post['po_type'] == "manualpolocal")
				{
					//for manual po on base of grn search
					$or1["is_grn_base"] = 1;
				}else{
					$or1["is_grn_base !="] = 1;
				}
				$keys = array_keys($or1,"");				
				foreach ($keys as $k)
				{unset($or1[$k]);}
				// debug($post);
				// debug($or1);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($role =='projectdirector' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager' || $role =='projectcoordinator' || $role =='siteaccountant' || $role == "deputymanagerelectric")
				{
					if(!empty($projects_ids))
					{
						$manual_po_list = $erp_manual_po->find()->select($erp_manual_po)->where(["project_id IN"=>$projects_ids]);
						$manual_po_list = $manual_po_list->innerjoin(
							["erp_manual_po_detail"=>"erp_manual_po_detail"],
							["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved ="=>2])
							->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();
							//var_dump($result);die;
						//$this->set('grn_list',$result);
					}
					else
					{
						$manual_po_list=array();
					}
				}
				else
				{
					$manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
						$manual_po_list = $manual_po_list->innerjoin(
							["erp_manual_po_detail"=>"erp_manual_po_detail"],
							["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved ="=>2])
							->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				
				$this->set('po_list',array());
				$this->set('manual_po',$manual_po_list);
				}
			}
			if(isset($this->request->data["export_csv"]))
			{
				$erp_inventory_po = TableRegistry::get("erp_inventory_po");
				$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
				// debug($this->request->data);die;
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_material_id"] = (!empty($post["e_material_id"]))?explode(",",$post["e_material_id"]):NULL;
				$post["e_brand_id"] = (!empty($post["e_brand_id"]))?explode(",",$post["e_brand_id"]):NULL;
				$post["e_vendor_userid"] = (!empty($post["e_vendor_userid"]))?explode(",",$post["e_vendor_userid"]):NULL;
				
				$or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All")?$post["e_brand_id"]:NULL;
				$or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"]))?$post["e_po_no"]:NULL;
				$or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				$or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				
				if($or["erp_inventory_po.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_inventory_po.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_inventory_po_detail.approved ="] = 2;
				
				$result = $erp_inventory_po->find()->select($erp_inventory_po);
				$result = $result->innerjoin(
					["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
					["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
					->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","Final Rate","Amount","PO Type");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_inventory_po_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_po_detail"]);
					}
					if(is_numeric($retrive_data['material_id']))
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
						$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					}
					else
					{
						$mt = $retrive_data['material_id'];
						$brnd = $retrive_data['brand_id'];
						$static_unit = $retrive_data['static_unit'];
					}
					$po_type=$retrive_data['po_type'];
					if($po_type == "po")
					{
						$type_name = "PO";
					}elseif($po_type == "manual_po")
					{
						$type_name = "Manual PO";
					}elseif($po_type == "local_po"){
						$type_name = "Local PO";
					}
					
					$csv = array();
					$csv[] = $retrive_data['po_no'];
					$csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $retrive_data['quantity'];
					$csv[] = $static_unit;
					$csv[] = $retrive_data['single_amount'];
					$csv[] = $retrive_data['amount'];
					$csv[] = $type_name;
					$rows[] = $csv;
				}
				
				$filename = "po_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$erp_inventory_po = TableRegistry::get("erp_inventory_po");
				$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
				// debug($this->request->data);die;
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_material_id"] = (!empty($post["e_material_id"]))?explode(",",$post["e_material_id"]):NULL;
				$post["e_brand_id"] = (!empty($post["e_brand_id"]))?explode(",",$post["e_brand_id"]):NULL;
				$post["e_vendor_userid"] = (!empty($post["e_vendor_userid"]))?explode(",",$post["e_vendor_userid"]):NULL;
				
				$or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All")?$post["e_brand_id"]:NULL;
				$or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"]))?$post["e_po_no"]:NULL;
				$or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				$or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				
				if($or["erp_inventory_po.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_inventory_po.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_inventory_po_detail.approved !="] = 0;
				
				$result = $erp_inventory_po->find()->select($erp_inventory_po);
				$result = $result->innerjoin(
					["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
					["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
					->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","Final Rate","Amount","PO Type");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_inventory_po_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_po_detail"]);
					}
					if(is_numeric($retrive_data['material_id']))
					{
						$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
						$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
						$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
					}
					else
					{
						$mt = $retrive_data['material_id'];
						$brnd = $retrive_data['brand_id'];
						$static_unit = $retrive_data['static_unit'];
					}
					$po_type=$retrive_data['po_type'];
					if($po_type == "po")
					{
						$type_name = "PO";
					}elseif($po_type == "manual_po")
					{
						$type_name = "Manual PO";
					}elseif($po_type == "local_po"){
						$type_name = "Local PO";
					}
					
					$csv = array();
					$csv[] = $retrive_data['po_no'];
					$csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
					$csv[] = $mt;
					$csv[] = $brnd;
					$csv[] = $retrive_data['quantity'];
					$csv[] = $static_unit;
					$csv[] = $retrive_data['single_amount'];
					$csv[] = $retrive_data['amount'];
					$csv[] = $type_name;
					$rows[] = $csv;
				}
				$this->set("rows",$rows);
				$this->render("porecordpdf");
			}
		}
		
			
		//// debug($result);
		//// debug($manual_po_list);die;
		//$this->set('po_list',$result);
		// $this->set('manual_po',$manual_po_list);
		//$this->set('manual_po',array());
    }
	
	public function mixdesign()
	{
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_names =$asset_table->find()->group("asset_name");
		$this->set('asset_names',$asset_names);
		
		$erp_material = TableRegistry::get('erp_material');
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_code !="=>17,"material_id IN"=>$material_ids,"project_id"=>0]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id"=>0]);
		}
		$this->set('material_list',$material_list);
		
		if($this->request->is('post'))
		{
			$erp_inventory_mix_design = TableRegistry::get('erp_inventory_mix_design');
			$erp_inventory_mix_detail = TableRegistry::get('erp_inventory_mix_detail');
			$post = $this->request->data();
			$row = $erp_inventory_mix_design->newEntity();
			$row->project_id = $post['project_id'];
			$row->asset_id = $post['asset_id'];
			$row->concrete_grade = $post['concrete_grade'];
			$row->created_by = $this->request->session()->read('user_id');
			$row->created_date = date("Y-m-d");
			if($erp_inventory_mix_design->save($row))
			{
				$material = $post['material'];
				foreach($material['material_id'] as $key=>$value)
				{
					$detail_row = $erp_inventory_mix_detail->newEntity();
					$detail_row->mix_id = $row->id;
					$detail_row->material_id = $material['material_id'][$key];
					$detail_row->consumption = $material['consumption'][$key];
					$detail_row->created_by = $this->request->session()->read('user_id');
					$detail_row->created_date = date("Y-m-d");
					if($erp_inventory_mix_detail->save($detail_row))
					{
						$this->Flash->success(__('Record inserted Successfully.'));
						return $this->redirect(['action'=>'mixdesign']);
					}
				}
			}
			
			
			
		}
	}
	
	public function mixdesignlisting()
	{
		$erp_inventory_mix_design = TableRegistry::get('erp_inventory_mix_design');
		$mix_design_records = $erp_inventory_mix_design->find()->hydrate(false)->toArray();
		$this->set('mix_records',$mix_design_records);
		// debug($mix_design_records);die;
	}
	
	public function previewmixdesign($id)
	{
		$erp_inventory_mix_design = TableRegistry::get('erp_inventory_mix_design');
		$erp_inventory_mix_detail = TableRegistry::get('erp_inventory_mix_detail');
		$mix_row = $erp_inventory_mix_design->get($id);
		$this->set('mix_row',$mix_row);
		$mix_details = $erp_inventory_mix_detail->find()->where(['mix_id'=>$id])->hydrate(false)->toArray();
		$this->set('mix_details',$mix_details);
	}
	
	public function printmixdesign($id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$erp_inventory_mix_design = TableRegistry::get('erp_inventory_mix_design');
		$erp_inventory_mix_detail = TableRegistry::get('erp_inventory_mix_detail');
		$mix_row = $erp_inventory_mix_design->get($id);
		$this->set('mix_row',$mix_row);
		$mix_details = $erp_inventory_mix_detail->find()->where(['mix_id'=>$id])->hydrate(false)->toArray();
		$this->set('mix_details',$mix_details);			
	}
	
	public function prepareinventoryrmc()
	{
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_names =$asset_table->find()->group("asset_name");
		$this->set('asset_names',$asset_names);
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		$this->set('vendor_list',$vendor_list);
		
		$erp_inventory_mix_design = TableRegistry::get("erp_inventory_mix_design");
		$concrete_grade = $erp_inventory_mix_design->find()->select(['id','concrete_grade'])->hydrate(false)->toArray();
		$this->set('concrete_grade',$concrete_grade);
		if($this->request->is('post'))
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
					$post = $this->request->data;
			
					// Create RMC No. code start
					$project_id = $post['project_id'];
					$project_code = $this->ERPfunction->get_projectcode($project_id);
					
					$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_rmc","id","rmc_no");
				
					$new_rmcno = sprintf("%09d", $number1);
					$rmc_no = $project_code.'/RMC/'.$new_rmcno;
					// Create RMC No. code end
					
					$erp_inventory_rmc = TableRegistry::get('erp_inventory_rmc');
					$row = $erp_inventory_rmc->newEntity();
					$row->project_id = $post['project_id'];
					$row->rmc_no = $rmc_no;
					$row->rmc_date = date("Y-m-d",strtotime($post['rmc_date']));
					$row->asset_id = $post['asset_id'];
					$row->agency_id = $post['agency_name'];
					$row->operators_name = $post['operator_name'];
					$row->order_by = $post['order_by'];
					$row->rmc_usage = $post['usage'];
					$row->concrete_grade = $post['concrete_grade'];
					$row->total_quantity_supplied = $post['total_quantity'];
					$row->start_time = $post['start_time'];
					$row->end_time = $post['end_time'];
					$row->created_by = $this->request->session()->read('user_id');
					$row->created_date = date("Y-m-d");
					
					@$row->attach_label = trim(json_encode($post["attach_label"]),'\"');
					$all_files = array();
					if(isset($_FILES["attach_file"]["name"]))
					{
						$file = $this->ERPfunction->upload_file("attach_file");	
						if(!empty($file))
							foreach($file as $attachment_file) {
								$all_files[] = $attachment_file;
							}					
					}
					$row->attach_file = json_encode($all_files);
					if($erp_inventory_rmc->save($row)) {
						$this->Flash->success(__('Record inserted with RMC NO.'.$rmc_no));
						return $this->redirect(['action'=>'prepareinventoryrmc']);
					}
				}else {
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				$post = $this->request->data;
			
				// Create RMC No. code start
				$project_id = $post['project_id'];
				$project_code = $this->ERPfunction->get_projectcode($project_id);
				
				$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_rmc","id","rmc_no");
			
				$new_rmcno = sprintf("%09d", $number1);
				$rmc_no = $project_code.'/RMC/'.$new_rmcno;
				// Create RMC No. code end
				
				$erp_inventory_rmc = TableRegistry::get('erp_inventory_rmc');
				$row = $erp_inventory_rmc->newEntity();
				$row->project_id = $post['project_id'];
				$row->rmc_no = $rmc_no;
				$row->rmc_date = date("Y-m-d",strtotime($post['rmc_date']));
				$row->asset_id = $post['asset_id'];
				$row->agency_id = $post['agency_name'];
				$row->operators_name = $post['operator_name'];
				$row->order_by = $post['order_by'];
				$row->rmc_usage = $post['usage'];
				$row->concrete_grade = $post['concrete_grade'];
				$row->total_quantity_supplied = $post['total_quantity'];
				$row->start_time = $post['start_time'];
				$row->end_time = $post['end_time'];
				$row->created_by = $this->request->session()->read('user_id');
				$row->created_date = date("Y-m-d");
				
				@$row->attach_label = trim(json_encode($post["attach_label"]),'\"');
				$all_files = array();
				if(isset($_FILES["attach_file"]["name"])) {
					$file = $this->ERPfunction->upload_file("attach_file");	
					if(!empty($file))
						foreach($file as $attachment_file)
						{
							$all_files[] = $attachment_file;
						}					
				}
				$row->attach_file = json_encode($all_files);
				if($erp_inventory_rmc->save($row))
				{
					$this->Flash->success(__('Record inserted with RMC NO.'.$rmc_no));
					return $this->redirect(['action'=>'prepareinventoryrmc']);
				}
			}
			
		}
	}
	
	public function inventoryrmcalert()
	{
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_names =$asset_table->find()->group("asset_name");
		$this->set('asset_names',$asset_names);
		
		$erp_inventory_rmc = TableRegistry::get('erp_inventory_rmc');
		$rmc_data = $erp_inventory_rmc->find()->where(["approved"=>0])->hydrate(false)->toArray();
		$this->set('rmc_data',$rmc_data);
		
		if($this->request->is('post'))
		{
			$post = $this->request->data();
			// debug($post);die;
			$or = array();
			$or["project_id ="] = (!empty($post["project_id"]))?$post["project_id"]:NULL;
			$or["asset_id ="] = (!empty($post["asset_id"]))?$post["asset_id"]:NULL;
			
			$keys = array_keys($or,"");
			foreach($keys as $k)
			{unset($or[$k]);}
			$or["approved ="] = 0;
			if(!empty($or))
			{
				$rmc_data = $erp_inventory_rmc->find()->where($or)->hydrate(false)->toArray();
			}else{
				$rmc_data = $erp_inventory_rmc->find()->hydrate(false)->toArray();
			}
			$this->set('rmc_data',$rmc_data);
		}
	}
	
	public function editinventoryrmc($id)
	{
		$erp_inventory_rmc = TableRegistry::get('erp_inventory_rmc');
		$row = $erp_inventory_rmc->get($id);
		$this->set('row',$row);
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_names =$asset_table->find()->group("asset_name");
		$this->set('asset_names',$asset_names);
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find()->select(["user_id","vendor_name"])->hydrate(false)->toArray();
		$this->set('vendor_list',$vendor_list);
		
		$erp_inventory_mix_design = TableRegistry::get("erp_inventory_mix_design");
		$concrete_grade = $erp_inventory_mix_design->find()->select(['id','concrete_grade'])->hydrate(false)->toArray();
		$this->set('concrete_grade',$concrete_grade);
		
		if($this->request->is('post'))
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
					
					$post = $this->request->data;
			
			$save = $erp_inventory_rmc->get($id);
			$save->project_id = $post['project_id'];
			$save->rmc_date = date("Y-m-d",strtotime($post['rmc_date']));
			$save->asset_id = $post['asset_id'];
			$save->agency_id = $post['agency_name'];
			$save->operators_name = $post['operator_name'];
			$save->order_by = $post['order_by'];
			$save->rmc_usage = $post['usage'];
			$save->concrete_grade = $post['concrete_grade'];
			$save->total_quantity_supplied = $post['total_quantity'];
			$save->start_time = $post['start_time'];
			$save->end_time = $post['end_time'];
			$save->updated_by = $this->request->session()->read('user_id');
			$save->updated_date = date("Y-m-d");
			
			$old_files = array();
			if(isset($this->request->data["old_attach_file"]))
			{
				$old_files = $this->request->data["old_attach_file"];				
			}
			@$save->attach_label = trim(json_encode($this->request->data["attach_label"]),'\"');
			if(isset($_FILES["attach_file"]["name"]))
			{
				$file = $this->ERPfunction->upload_file("attach_file");	
				if(!empty($file))
				foreach($file as $attachment_file)
				{
					$old_files[] = $attachment_file;
				}					
			}
			$save->attach_file = json_encode($old_files);
			
			if($erp_inventory_rmc->save($save))
			{
				$this->Flash->success(__('Record updated successfully.'));
				return $this->redirect(['action'=>'inventoryrmcalert']);
			}
				
				}
				else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				$post = $this->request->data;
			
			$save = $erp_inventory_rmc->get($id);
			$save->project_id = $post['project_id'];
			$save->rmc_date = date("Y-m-d",strtotime($post['rmc_date']));
			$save->asset_id = $post['asset_id'];
			$save->agency_id = $post['agency_name'];
			$save->operators_name = $post['operator_name'];
			$save->order_by = $post['order_by'];
			$save->rmc_usage = $post['usage'];
			$save->concrete_grade = $post['concrete_grade'];
			$save->total_quantity_supplied = $post['total_quantity'];
			$save->start_time = $post['start_time'];
			$save->end_time = $post['end_time'];
			$save->updated_by = $this->request->session()->read('user_id');
			$save->updated_date = date("Y-m-d");
			
			$old_files = array();
			if(isset($this->request->data["old_attach_file"]))
			{
				$old_files = $this->request->data["old_attach_file"];				
			}
			@$save->attach_label = trim(json_encode($this->request->data["attach_label"]),'\"');
			if(isset($_FILES["attach_file"]["name"]))
			{
				$file = $this->ERPfunction->upload_file("attach_file");	
				if(!empty($file))
				foreach($file as $attachment_file)
				{
					$old_files[] = $attachment_file;
				}					
			}
			$save->attach_file = json_encode($old_files);
			
			if($erp_inventory_rmc->save($save))
			{
				$this->Flash->success(__('Record updated successfully.'));
				return $this->redirect(['action'=>'inventoryrmcalert']);
			}
				
			}
			
		}
	}
	
	public function viewinventoryrmc($id)
	{
		$erp_inventory_rmc = TableRegistry::get('erp_inventory_rmc');
		$row = $erp_inventory_rmc->get($id);
		$this->set('row',$row);
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_names =$asset_table->find()->group("asset_name");
		$this->set('asset_names',$asset_names);
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor_list = $vendor_tbl->find()->select(["user_id","vendor_name"])->hydrate(false)->toArray();
		$this->set('vendor_list',$vendor_list);
		
		$erp_inventory_mix_design = TableRegistry::get("erp_inventory_mix_design");
		$concrete_grade = $erp_inventory_mix_design->find()->select(['id','concrete_grade'])->hydrate(false)->toArray();
		$this->set('concrete_grade',$concrete_grade);
	}
	
	public function deleteinventoryrmc($id)
	{
		$erp_inventory_rmc = TableRegistry::get('erp_inventory_rmc');		
		$row = $erp_inventory_rmc->get($id);
		if($erp_inventory_rmc->delete($row))
		{
			$this->Flash->success(__('Record delete Successfully.'));
			return $this->redirect(['action'=>'inventoryrmcalert']);
		}
	}
	
	public function inventoryrmcrecords()
	{
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_names =$asset_table->find()->group("asset_name");
		$this->set('asset_names',$asset_names);
		
		$erp_inventory_mix_design = TableRegistry::get("erp_inventory_mix_design");
		$concrete_grade = $erp_inventory_mix_design->find()->select(['id','concrete_grade'])->hydrate(false)->toArray();
		$this->set('concrete_grade',$concrete_grade);
		
		if($this->request->is('post'))
		{
			if(isset($this->request->data["export_csv"]))
			{
				$post = $this->request->data();	
				$or = array();	
				
				$or["project_id ="] = (!empty($post["e_pro_id"]) && $post["e_pro_id"][0] != "All" )?$post["e_pro_id"]:NULL;
				$or["rmc_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["rmc_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				
				$or["rmc_no ="] = (!empty($post["e_rmc_no"]))?$post["e_rmc_no"]:NULL;
				$or["concrete_grade ="] = (!empty($post["e_concrete_grade"]) && $post["e_concrete_grade"][0] != "All")?$post["e_concrete_grade"]:NULL;
				$or["asset_id ="] = (!empty($post["e_asset_id"]) && $post["e_asset_id"][0] != "All")?$post["e_asset_id"]:NULL;
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["approved ="] = 1;
				
				$erp_inventory_rmc = TableRegistry::get("erp_inventory_rmc");
				
				$result = $erp_inventory_rmc->find()->select($erp_inventory_rmc)->where($or)->order(['rmc_date'=>'DESC'])->hydrate(false)->toArray();		
				$rows = array();
				$rows[] = array("Date","RMC. L. No","Asset Name","Order By","Concrete Grade","Qty. Supplied(Cum)","Usage","Start Time","End Time");
			
				foreach($result as $retrive_data)
				{					
					$csv = array();
					$csv[] = date('d-m-Y',strtotime($retrive_data['rmc_date']));
					$csv[] = $retrive_data['rmc_no'];
					$csv[] = $this->ERPfunction->get_asset_name($retrive_data['asset_id']);
					$csv[] = $retrive_data['order_by'];
					$csv[] = $this->ERPfunction->get_concrete_grade_name($retrive_data['concrete_grade']);
					$csv[] = $retrive_data['total_quantity_supplied'];
					$csv[] = $retrive_data['rmc_usage'];
					$csv[] = $retrive_data['start_time'];
					$csv[] = $retrive_data['end_time'];
					$rows[] = $csv;
				}
			
				$filename = "approvermclist.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$post = $this->request->data();	
				$or = array();	
				
				$or["project_id ="] = (!empty($post["e_pro_id"]) && $post["e_pro_id"][0] != "All" )?$post["e_pro_id"]:NULL;
				$or["rmc_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["rmc_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				
				$or["rmc_no ="] = (!empty($post["e_rmc_no"]))?$post["e_rmc_no"]:NULL;
				$or["concrete_grade ="] = (!empty($post["e_concrete_grade"]) && $post["e_concrete_grade"][0] != "All")?$post["e_concrete_grade"]:NULL;
				$or["asset_id ="] = (!empty($post["e_asset_id"]) && $post["e_asset_id"][0] != "All")?$post["e_asset_id"]:NULL;
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["approved ="] = 1;
				
				$erp_inventory_rmc = TableRegistry::get("erp_inventory_rmc");
				
				$result = $erp_inventory_rmc->find()->select($erp_inventory_rmc)->where($or)->order(['rmc_date'=>'DESC'])->hydrate(false)->toArray();		
				$rows = array();
				$rows[] = array("Date","RMC. L. No","Asset Name","Order By","Concrete Grade","Qty. Supplied(Cum)","Usage","Start Time","End Time");
			
				foreach($result as $retrive_data)
				{					
					$csv = array();
					$csv[] = date('d-m-Y',strtotime($retrive_data['rmc_date']));
					$csv[] = $retrive_data['rmc_no'];
					$csv[] = $this->ERPfunction->get_asset_name($retrive_data['asset_id']);
					$csv[] = $retrive_data['order_by'];
					$csv[] = $this->ERPfunction->get_concrete_grade_name($retrive_data['concrete_grade']);
					$csv[] = $retrive_data['total_quantity_supplied'];
					$csv[] = $retrive_data['rmc_usage'];
					$csv[] = $retrive_data['start_time'];
					$csv[] = $retrive_data['end_time'];
					$rows[] = $csv;
				}
				$this->set("rows",$rows);
				$this->render("rmcrecordpdf");
			}
		}
	}
	
	public function unapprovedrmc($id)
	{
		$erp_stock_history = TableRegistry::get("erp_stock_history");
		$delete_ok = $erp_stock_history->deleteAll(["type"=>'rmc',"type_id"=>$id]);
		if($delete_ok)
		{
			$erp_inventory_rmc = TableRegistry::get("erp_inventory_rmc");
			$row = $erp_inventory_rmc->get($id);
			$row->approved = 0;
			if($erp_inventory_rmc->save($row))
			{
				$this->Flash->success(__('Record unapproved Successfully.'));
				return $this->redirect(['action'=>'inventoryrmcrecords']);
			}
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
