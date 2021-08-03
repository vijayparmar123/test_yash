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
class AssetsController extends AppController
{

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent('Flash');
		$this->loadComponent('ERPfunction');
		$this->user_id=$this->request->session()->read('user_id');
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$this->rights = $this->Usermanage->asset_access_right();
		$action = $this->request->action;
		
		if(isset($this->rights[$action][$this->role]))
		{
			$is_capable = $this->rights[$action][$this->role];	
		}
		else
		{	$is_capable = 0;}
		
		$this->set('is_capable',$is_capable);
		$this->set('role',$this->role);
	}
	public function viewStoreIsuueHistory($id=null,$date=null)
	{
		$asset_id = $id;
		$role = $this->role;

		$dates = date('Y-m-d',strtotime($date));
		$date =  date('m-Y',(strtotime($dates)));
		$month =date('m',(strtotime($dates)));
		$year = date('Y',(strtotime($dates)));

		$erp_equipmentown_log = TableRegistry::get('erp_equipmentown_log');
		$efficiencydata = $erp_equipmentown_log->find('all')->where(['asset_id'=>$asset_id,'MONTH(erp_equipmentown_log.date) ='=>$month,'YEAR(erp_equipmentown_log.date) ='=>$year ])->hydrate(false)->toArray();
		
		$log_id = $efficiencydata[0]['id'];
		$this->set('log_id',$log_id);
		$asset_name = $efficiencydata[0]['asset_make'];
		
		
		$this->set('efficiencydata',$efficiencydata);
		$this->set('asset_id',$id);
		

		$erp_inventory_is = TableRegistry::get('erp_inventory_is'); 

		$erp_inventory_is_detail = TableRegistry::get('erp_inventory_is_detail');
		
		$result = $erp_inventory_is->find()->where(['MONTH(erp_inventory_is.is_date) ='=>$month,'YEAR(erp_inventory_is.is_date) ='=>$year]);
		$query = $result->innerjoin(
						["erp_inventory_is_detail"=>"erp_inventory_is_detail"],
						["erp_inventory_is_detail.is_id = erp_inventory_is.is_id"]) ;

		$record = $query->where(['erp_inventory_is_detail.material_id'=>90])->hydrate(false)->toArray();
		
	}
	
	public function index()
    {
		
    }

	public function assetlist()
	{
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find();
		$this->set('asset_list',$asset_list);
	}
	
	public function add($asset_id=Null)
    {
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_groups = $this->ERPfunction->asset_group();
		$this->set('asset_groups',$asset_groups);
		$table_category=TableRegistry::get('erp_category_master');
		$make_list=$table_category->find()->where(array('type'=>'make_in'));
		$this->set('makelist',$make_list);
		$this->set("back","index");
		
		$project_table = TableRegistry::get('erp_projects'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set("role",$role);
		// if($role == 'constructionmanager' || $role == 'materialmanager' || $role == 'projectcoordinator')
		// {
			// if(!empty($projects_ids))
			// {
				// $or = array();
				// $or["project_id IN"] = $projects_ids;
				// $project_list = $project_table->find()->where($or);
			// }else{
				// $project_list = array();
			// }
		// }
		// else{
			// $project_list = $project_table->find();
		// }

		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('project_data',$projects);
		
		$vendor_tbl = TableRegistry::get('erp_vendor');
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		$this->set('vendor_list',$vendor_list);
		
		if(isset($asset_id))
		{			
			$asset_action = 'edit';			
			$asset_data = $asset_table->get($asset_id);			
			$this->set('asset_data',$asset_data);
			$this->set('form_header','Edit Asset');
			$this->set('button_text','Update Asset');	
			$this->set("back","trasnsferaccept");			
		}
		else
		{
			$asset_action = 'insert';			
			$this->set('form_header','Add Asset');
			$this->set('button_text','Add Asset');
		}	
		$this->set('asset_action',$asset_action);
		
		if($this->request->is('post')){
			$post = $this->request->data();
				
			$this->request->data['passing_registration_status'] = $post['passing_registration_status'];
			$this->request->data['due_date_reg'] = ($post['passing_registration_status'])?date("Y-m-d",strtotime($post['due_date_reg'])):'';
			
			$this->request->data['insurance_status'] = $post['insurance_status'];
			$this->request->data['due_date_insurance'] = ($post['insurance_status'])?date("Y-m-d",strtotime($post['due_date_insurance'])):'';
			
			$this->request->data['road_tax_status'] = $post['road_tax_status'];
			$this->request->data['due_date_road_tax'] = ($post['road_tax_status'])?date("Y-m-d",strtotime($post['due_date_road_tax'])):'';
			
			$this->request->data['fitness_status'] = $post['fitness_status'];
			$this->request->data['due_date_fitness'] = ($post['fitness_status'])?date("Y-m-d",strtotime($post['due_date_fitness'])):'';
			
			$this->request->data['purchase_date']= $this->ERPfunction->set_date($this->request->data['purchase_date']);
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;

			if($asset_action == 'edit')
			{
				
				if(isset($_FILES['attach_file']) || isset($post_data["asset_picture"]))
				{	
					$ext1=1;
					$ext2=1;
					if(isset($_FILES['attach_file'])){
						$file =$_FILES['attach_file']["name"];
						$size = count($file);
						for($i=0;$i<$size;$i++) {
							$parts = pathinfo($_FILES['attach_file']['name'][$i]);
						}
						$ext1 = $this->ERPfunction->check_valid_extension($parts['basename']);
					}
					if(isset($post_data["asset_picture"])){
						$assetfile =$post_data["asset_picture"]['name'];
						$ext2 = $this->ERPfunction->check_valid_extension($assetfile);
						// debug($ext);die;
					}
					
					if($ext1 != 0 && $ext2 != 0) {
					
						$post_data = $this->request->data;
						if(isset($post_data["asset_picture"]) && $post_data["asset_picture"]['tmp_name'] != '')
						{
							$file = $this->ERPfunction->upload_image("asset_picture");	
							if(!empty($file))
							{
								$post_data['asset_image'] =  $file;
							}					
						}
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
						$post_data['last_edited_by'] = $this->request->session()->read('user_id');;
						$post_data['last_edit_date'] = date('Y-m-d');
						
						
						$asset_data = $asset_table->patchEntity($asset_data,$post_data);
						if($asset_table->save($asset_data))
						{
							$this->Flash->success(__('Record Update Successfully', null), 
									'default', 
									array('class' => 'success'));
						
						}
					
					}
					else{
						$this->Flash->error(__("Invalid File Extension, Please Retry."));
					}
				}
				else{
					$post_data = $this->request->data;
						if(isset($post_data["asset_picture"]) && $post_data["asset_picture"]['tmp_name'] != '')
						{
							$file = $this->ERPfunction->upload_image("asset_picture");	
							if(!empty($file))
							{
								$post_data['asset_image'] =  $file;
							}					
						}
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
						$post_data['last_edited_by'] = $this->request->session()->read('user_id');;
						$post_data['last_edit_date'] = date('Y-m-d');
						
						
						$asset_data = $asset_table->patchEntity($asset_data,$post_data);
						if($asset_table->save($asset_data))
						{
							$this->Flash->success(__('Record Update Successfully', null), 
									'default', 
									array('class' => 'success'));
						
						}
				}
				
				
				
			}else{
			
			if(isset($_FILES['attach_file']) || isset($post_data["asset_picture"]))
				{	
					$ext1=1;
					$ext2=1;
					if(isset($_FILES['attach_file'])){
						$file =$_FILES['attach_file']["name"];
						$size = count($file);
						for($i=0;$i<$size;$i++) {
							$parts = pathinfo($_FILES['attach_file']['name'][$i]);
						}
						$ext1 = $this->ERPfunction->check_valid_extension($parts['basename']);
					}
					if(isset($post_data["asset_picture"])){
						$assetfile =$post_data["asset_picture"]['name'];
						$ext2 = $this->ERPfunction->check_valid_extension($assetfile);
						// debug($ext);die;
					}
					
					if($ext1 != 0 && $ext2 != 0) {
						
						$asset_id = $this->request->data['asset_group'];
				
						$number1 = $this->ERPfunction->generate_asset_auto_id($asset_id,"erp_assets","asset_id","asset_code");
						$new_assetno = sprintf("%09d", $number1);
						
						$asset_code = 'YNEC/AST/'.$this->ERPfunction->get_asset_group_code($asset_id ).'/'.$new_assetno;
						
						$asset_field = $asset_table->newEntity();
						
						if(isset($post["asset_picture"]) && $post["asset_picture"]['tmp_name'] != '')
						{
							$file = $this->ERPfunction->upload_image("asset_picture");	
							if(!empty($file))
							{
								$this->request->data['asset_image'] =  $file;
							}					
						}
					
						$this->request->data['asset_code'] = $asset_code;
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
					
						$asset_field=$asset_table->patchEntity($asset_field,$this->request->data);
						if($asset_table->save($asset_field))
						{
						$this->Flash->success(__('Asset Insert Successfully', null), 
									'default', 
									array('class' => 'success'));
						}
								
							
					}
					else{
						$this->Flash->error(__("Invalid File Extension, Please Retry."));
					}
				}
				else{
					$asset_id = $this->request->data['asset_group'];
				
					$number1 = $this->ERPfunction->generate_asset_auto_id($asset_id,"erp_assets","asset_id","asset_code");
					$new_assetno = sprintf("%09d", $number1);
					
					$asset_code = 'YNEC/AST/'.$this->ERPfunction->get_asset_group_code($asset_id ).'/'.$new_assetno;
					
					$asset_field = $asset_table->newEntity();
					
					if(isset($post["asset_picture"]) && $post["asset_picture"]['tmp_name'] != '')
					{
						$file = $this->ERPfunction->upload_image("asset_picture");	
						if(!empty($file))
						{
							$this->request->data['asset_image'] =  $file;
						}					
					}
				
					$this->request->data['asset_code'] = $asset_code;
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
				
					$asset_field=$asset_table->patchEntity($asset_field,$this->request->data);
					if($asset_table->save($asset_field))
					{
					$this->Flash->success(__('Asset Insert Successfully', null), 
								'default', 
								array('class' => 'success'));
					}
					}
				 
			 }
			$this->redirect(array("controller" => "Assets","action" => "index"));	
			
		}	


    }
	
	public function viewasset($asset_id=Null)
    {
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_groups = $this->ERPfunction->asset_group();
		$this->set('asset_groups',$asset_groups);
		$table_category=TableRegistry::get('erp_category_master');
		$make_list=$table_category->find()->where(array('type'=>'make_in'));
		$this->set('makelist',$make_list);
		$this->set("back","index");
		
		$project_table = TableRegistry::get('erp_projects'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		// if($role == 'constructionmanager' || $role == 'materialmanager' || $role == 'projectcoordinator')
		// {
			// if(!empty($projects_ids))
			// {
				// $or = array();
				// $or["project_id IN"] = $projects_ids;
				// $project_list = $project_table->find()->where($or);
			// }else{
				// $project_list = array();
			// }
		// }
		// else{
			// $project_list = $project_table->find();
		// }

		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('project_data',$projects);
		
		$vendor_tbl = TableRegistry::get('erp_vendor');
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		$this->set('vendor_list',$vendor_list);
		
		if(isset($asset_id))
		{			
			$asset_action = 'edit';			
			$asset_data = $asset_table->get($asset_id);			
			$this->set('asset_data',$asset_data);
			$this->set('form_header','View Asset');
			$this->set('button_text','Update Asset');	
			$this->set("back","trasnsferaccept");			
		}	
		$this->set('asset_action',$asset_action);
		
    }
	
	public function printasset($id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$asset_table = TableRegistry::get('erp_assets');
		$asset_data = $asset_table->get($id);			
		$this->set('asset_data',$asset_data);		
	}
	
	public function delete($asset_id)
	{
		$asset_table = TableRegistry::get('erp_assets'); 
		$this->request->is(['post','delete']);
		
		$asset_data =$asset_table->get($asset_id);

		if($asset_table->delete($asset_data))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'index']);
	}
	
	public function transfereasset()
    {
		$erp_assets_history = TableRegistry::get('erp_assets_history'); 
		$history_data['asset_id']=$this->request->data['asset_id'];
		$history_data['old_project']=$this->ERPfunction->get_asset_project($this->request->data['asset_id']);
		$history_data['new_project']=$this->request->data['transfer_to'];
		$history_data['transfer_quantity']=$this->request->data['transfer_qty'];
		$history_data['transfer_date']=$this->ERPfunction->set_date($this->request->data['transfer_date']);
		$history_data['accepted']=0;
		$history_data['created_date']=date('Y-m-d H:i:s');
		$history_data['created_by']=$this->request->session()->read('user_id');
		
		
		$available_qty = $this->request->data["available_qty"];
		$transfer_qty = $this->request->data["transfer_qty"];
		
		
		if($transfer_qty == $available_qty)
		{		
			$asset_field = $erp_assets_history->newEntity();
			$asset_field=$erp_assets_history->patchEntity($asset_field,$history_data);
			if($erp_assets_history->save($asset_field))
			{
				// $this->ERPfunction->update_assetproject($this->request->data['asset_id'],$this->request->data['transfer_to']);
				$this->Flash->success(__('Asset Transfer Successfully', null),'default',array('class' => 'success'));
			}
		}
		
		if($transfer_qty < $available_qty)
		{	
			$remaining_qty = intval($available_qty) - intval($transfer_qty);
			$asset_tbl = TableRegistry::get("erp_assets");
			$update_asset = $asset_tbl->get($history_data["asset_id"]);
			$update_asset->quantity = $remaining_qty;			
			$asset_tbl->save($update_asset);
			
			$asset_field = $erp_assets_history->newEntity();
			$asset_field=$erp_assets_history->patchEntity($asset_field,$history_data);	
			$erp_assets_history->save($asset_field);
			
			// $row = $asset_tbl->newEntity();
			// $update_row = $asset_tbl->get($history_data["asset_id"])->toArray();
			// unset($update_row["asset_id"]);
			// $update_row["quantity"] = $transfer_qty;			
			// $update_row["deployed_to"] = $this->request->data['transfer_to'];			
			// $new = $asset_tbl->patchEntity($row,$update_row);
			// $asset_tbl->save($new);
			$this->Flash->success(__('Asset Transfer Successfully', null),'default',array('class' => 'success'));
		}
		
		return $this->redirect(['action'=>'index']);
    }
	
	public function acceptasset()
    {
		$erp_assets_history = TableRegistry::get('erp_assets_history'); 
		$asset_id = $this->request->data['asset_id'];
		
		$history_data['accepted']=1;
		$history_data['remarks']=$this->request->data['remarks'];
		$history_data['accept_date']=date("Y-m-d",strtotime($this->request->data['accept_date']));
		$history_data['release_date']=date("Y-m-d",strtotime($this->request->data['release_date']));;
		$history_data['updated_date']=date('Y-m-d H:i:s');
		$history_data['updated_by']=$this->request->session()->read('user_id');
		
		$row = $erp_assets_history->find()->where(["asset_id"=>$asset_id,"accepted"=>0])->first();
		
		if(!empty($row))
		{		
			$asset_field = $erp_assets_history->get($row->history_id);
			$transfer_to = $asset_field->new_project;
			$transfer_date = $asset_field->transfer_date;
			
			$asset_field=$erp_assets_history->patchEntity($asset_field,$history_data);
			if($erp_assets_history->save($asset_field))
			{
				/* Set return date of this asset in issued history table code start */
				$return_date = $transfer_date;
				$erp_asset_issued_history = TableRegistry::get("erp_asset_issued_history");
				$data = $erp_asset_issued_history->find()->where(["asset_id"=>$asset_id,"return_date IS"=> null])->first();
				
				if(!empty($data)){
					$row = $erp_asset_issued_history->get($data->id);
					$row->return_date = date("Y-m-d",strtotime($return_date));
					$erp_asset_issued_history->save($row);
				}
				/* Set return date of this asset in issued history table code end */
				
				/* Insert entry in issue table code start */
				$history_data1['asset_id']=$asset_id;
				$history_data1['project_id']=$asset_field->new_project;
				$history_data1['issued_to']=$this->ERPfunction->get_projectname($asset_field->new_project);
				$history_data1['issued_date']=date("Y-m-d",strtotime($this->request->data['accept_date']));
				$history_data1['created_date']=date('Y-m-d H:i:s');
				$history_data1['created_by']=$this->request->session()->read('user_id');
				
				$asset_field = $erp_asset_issued_history->newEntity();
				$asset_field=$erp_asset_issued_history->patchEntity($asset_field,$history_data1);
				$erp_asset_issued_history->save($asset_field);
				/* Insert entry in issue table code end */
				
				$this->ERPfunction->update_assetproject($asset_id,$transfer_to);
				$this->Flash->success(__('Asset Accept Successfully', null),'default',array('class' => 'success'));
			}
		}
		
		return $this->redirect(['action'=>'index']);
    }
	
	public function issueasset()
    {
		$erp_asset_issued_history = TableRegistry::get('erp_asset_issued_history'); 
		$history_data['asset_id']=$this->request->data['asset_id'];
		$history_data['project_id']=$this->request->data['project_id'];
		$history_data['issued_to']=$this->request->data['issue_to'];
		$history_data['issued_date']=$this->ERPfunction->set_date($this->request->data['issue_date']);
		$history_data['created_date']=date('Y-m-d H:i:s');
		$history_data['created_by']=$this->request->session()->read('user_id');
		
		$asset_field = $erp_asset_issued_history->newEntity();
		$asset_field=$erp_asset_issued_history->patchEntity($asset_field,$history_data);
		if($erp_asset_issued_history->save($asset_field)){
			$this->Flash->success(__('Asset Issued Successfully', null),'default',array('class' => 'success'));
		}
		
		return $this->redirect(['action'=>'index']);
    }
	
	public function bookingasset()
    {
		$erp_asset_booking_history = TableRegistry::get('erp_asset_booking_history'); 
		$history_data['asset_id']=$this->request->data['asset_id'];
		$history_data['project_id']=$this->request->data['project_id'];
		$history_data['requirment_date']=date("Y-m-d",strtotime($this->request->data['requirment_date']));
		$history_data['entry_date']=date('Y-m-d H:i:s');
		$history_data['created_date']=date('Y-m-d H:i:s');
		$history_data['created_by']=$this->request->session()->read('user_id');
		
		$asset_field = $erp_asset_booking_history->newEntity();
		$asset_field=$erp_asset_booking_history->patchEntity($asset_field,$history_data);
		if($erp_asset_booking_history->save($asset_field)){
			$this->Flash->success(__('Asset Booking Successfully', null),'default',array('class' => 'success'));
		}
		
		return $this->redirect(['action'=>'index']);
    }
	
	public function trasnsferaccept()
    {
		// $projects = $this->Usermanage->access_project($this->user_id);
		// $this->set('projects',$projects);
		$this->set('user_id',$this->user_id);
		
		// $table_category=TableRegistry::get('erp_category_master');
		// $make_list=$table_category->find()->where(array('type'=>'make_in'));
		// $this->set('makelist',$make_list);
		
		$asset_groups = $this->ERPfunction->asset_group();
		$this->set('asset_groups',$asset_groups);		
		
		// $asset_table = TableRegistry::get('erp_assets'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		// $this->set('role',$role);
		// if($this->Usermanage->project_alloted($role)==1){ 
			// if(!empty($projects_ids))
			// {
				// $asset_name = $asset_table->find("list",["keyField"=>"asset_name","valueField"=>"asset_name"])->where(["deployed_to IN"=>$projects_ids]);
			// }else{
				// $asset_name = array();
			// }
		// }
		// else{
			// $asset_name = $asset_table->find("list",["keyField"=>"asset_name","valueField"=>"asset_name"])->toArray();
		// }
		
		// $this->set('asset_list',$asset_list);
		// $this->set('asset_name',$asset_name);
		if($this->request->is("post"))
		{
			if(isset($this->request->data['go1']))
			{
			$post = $this->request->data;
			// debug($post);die;
			
			$or = array();				
			$erp_assets = TableRegistry::get("erp_assets");
			$or["purchase_date >="] = ($post["purchase_from_date"] != "")?date("Y-m-d",strtotime($post["purchase_from_date"])):NULL;
			$or["purchase_date <="] = ($post["purchase_to_date"] != "")?date("Y-m-d",strtotime($post["purchase_to_date"])):NULL;
			$or["deployed_to IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			$or["asset_make IN"] = (!empty($post["make_id"]) && $post["make_id"][0] != "All" )?$post["make_id"]:NULL;
			$or["asset_group IN"] = (!empty($post["asset_group"]) && $post["asset_group"][0] != "All" )?$post["asset_group"]:NULL;
			$or["asset_name IN"] = (!empty($post["asset_name"]) && $post["asset_name"][0] != "All" )?$post["asset_name"]:NULL;
			$or["asset_code"] = (!empty($post["asset_id"]))?$post["asset_id"]:NULL;
			$or["capacity"] = (!empty($post["asset_capacity"]))?$post["asset_capacity"]:NULL;
			$or["vehicle_no"] = (!empty($post["identity"]))?$post["identity"]:NULL;
			
			
			if($or["deployed_to IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["deployed_to IN"] = $projects_ids;
				}
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			// debug($or);die;
			
			if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids)){
						$asset_list = $erp_assets->find()->where([$or])->hydrate(false)->toArray();
					}else{
						$asset_list=array();
					}
				}
				else
				{
					$asset_list = $erp_assets->find()->where([$or])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
			$this->set("asset_list",$asset_list);
			}
			
			if(isset($this->request->data["export_csv"]) || isset($this->request->data["export_pdf"]))
			{
				$erp_assets = TableRegistry::get("erp_assets");
				
				$post = $this->request->data;
				// debug($post);die;
				$deployed_to = explode(",",$post["e_project_id"]);
				$make_id = explode(",",$post["e_make_id"]);
				$asset_group = explode(",",$post["e_asset_group"]);
				$asset_name = explode(",",$post["e_asset_name"]);
				$or = array();
				$or["purchase_date >="] = ($post["e_purchase_from_date"] != "")?date("Y-m-d",strtotime($post["e_purchase_from_date"])):NULL;
				$or["purchase_date <="] = ($post["e_purchase_to_date"] != "")?date("Y-m-d",strtotime($post["e_purchase_to_date"])):NULL;
				$or["deployed_to IN"] = (!empty($deployed_to) && $deployed_to[0] != "All" )?$post["e_project_id"]:NULL;
				$or["asset_make IN"] = (!empty($make_id) && $make_id[0] != "All" )?$post["e_make_id"]:NULL;
				$or["asset_group IN"] = (!empty($asset_group) && $asset_group[0] != "All" )?$post["e_asset_group"]:NULL;
				$or["asset_name IN"] = (!empty($asset_name) && $asset_name[0] != "All" )?$post["e_asset_name"]:NULL;
				$or["asset_code ="] = (!empty($post["e_asset_id"]))?$post["e_asset_id"]:NULL;
				$or["capacity ="] = (!empty($post["e_asset_capacity"]))?$post["e_asset_capacity"]:NULL;
				$or["vehicle_no ="] = (!empty($post["e_identity"]))?$post["e_identity"]:NULL;
				
				
				if($or["deployed_to IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						$or["deployed_to IN"] = $projects_ids;
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				// debug($or);die;
								
				$result = $erp_assets->find()->where([$or])->order(['asset_code'=>'DESC'])->hydrate(false)->toArray();
					
				$rows = array();
				$rows[] = array("Asset Group","Asset ID","Asset Name","Capacity","Make","Identity/Veh.No","Current Operational Status","Currently Deployed To","Currently Issued To","Tentative Date of Release");
				
				foreach($result as $retrive_data)
				{	
					$csv = array();
					$csv[] = $this->ERPfunction->get_asset_group_name($retrive_data['asset_group']);
					$csv[] = $retrive_data['asset_code'];
					$csv[] = $retrive_data['asset_name'];
					$csv[] = $retrive_data['capacity'];
					$csv[] = $this->ERPfunction->get_category_title($retrive_data['asset_make']);
					$csv[] = $retrive_data['vehicle_no'];
					$csv[] = $retrive_data['operational_status'];
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['deployed_to']);
					$csv[] = $this->ERPfunction->get_asset_last_issueto($retrive_data['asset_id']);
					$csv[] = $this->ERPfunction->get_asset_release_date($retrive_data['asset_id']);
					$rows[] = $csv;
				}
				if(isset($this->request->data["export_csv"])){
					$filename = "assets.csv";
					$this->ERPfunction->export_to_csv($filename,$rows);
				}
				if(isset($this->request->data["export_pdf"])){
					require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
					$this->set("rows",$rows);
					$this->render("assetmanagementpdf");
				}
			}
		}
    }
	
	public function soldtheft()
    {
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$table_category=TableRegistry::get('erp_category_master');
		$make_list=$table_category->find()->where(array('type'=>'make_in'));
		$this->set('makelist',$make_list);
		
		$asset_groups = $this->ERPfunction->asset_group();
		$this->set('asset_groups',$asset_groups);
		
		$asset_table = TableRegistry::get('erp_assets');
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids))
			{
				$asset_list = $asset_table->find()->where(["deployed_to IN"=>$projects_ids]);
				$asset_name = $asset_table->find("list",["keyField"=>"asset_name","valueField"=>"asset_name"])->where(["deployed_to IN"=>$projects_ids]);
			}else{
				$asset_list = array();
				$asset_name = array();
			}
		}
		else{
			$asset_list = $asset_table->find();
			$asset_name = $asset_table->find("list",["keyField"=>"asset_name","valueField"=>"asset_name"])->toArray();
		}
		
		$this->set('asset_list',$asset_list);
		$this->set('asset_name',$asset_name);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data["go"]))
			{
			$post = $this->request->data;
			// debug($post);die;
			
			$or = array();				
			$erp_assets = TableRegistry::get("erp_assets");
			$or["purchase_date >="] = ($post["purchase_from_date"] != "")?date("Y-m-d",strtotime($post["purchase_from_date"])):NULL;
			$or["purchase_date <="] = ($post["purchase_to_date"] != "")?date("Y-m-d",strtotime($post["purchase_to_date"])):NULL;
			$or["deployed_to IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			$or["asset_make IN"] = (!empty($post["make_id"]) && $post["make_id"][0] != "All" )?$post["make_id"]:NULL;
			$or["asset_group IN"] = (!empty($post["asset_group"]) && $post["asset_group"][0] != "All" )?$post["asset_group"]:NULL;
			$or["asset_name IN"] = (!empty($post["asset_name"]) && $post["asset_name"][0] != "All" )?$post["asset_name"]:NULL;
			$or["asset_code"] = (!empty($post["asset_id"]))?$post["asset_id"]:NULL;
			$or["capacity"] = (!empty($post["asset_capacity"]))?$post["asset_capacity"]:NULL;
			$or["vehicle_no"] = (!empty($post["identity"]))?$post["identity"]:NULL;
			
			
			if($or["deployed_to IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){  
					$or["deployed_to IN"] = $projects_ids;
				}
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			// debug($or);die;
			
			if($this->Usermanage->project_alloted($role)==1){ 
					if(!empty($projects_ids)){
						$asset_list = $erp_assets->find()->where([$or])->hydrate(false)->toArray();
					}else{
						$asset_list=array();
					}
				}
				else
				{
					$asset_list = $erp_assets->find()->where([$or])->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
			$this->set("asset_list",$asset_list);
			}
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "assetSold&Theft.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("soldtheftassetpdf");
			}
		}
    }
	
	public function soldasset()
    {
		// debug($this->request->data);die;
		$erp_soldasset_history = TableRegistry::get('erp_assets_sold_history'); 
		$sold_data['asset_id']=$this->request->data['asset_id'];
		$sold_data['sold_price']=$this->request->data['sold_price'];
		$sold_data['sold_to']=$this->request->data['vendor_userid'];
		$sold_data['voucher_no']=$this->request->data['voucher_no'];
		$sold_data['deployed_to']=$this->request->data['deployed_to'];
		
		$sold_data['sold_date']=$this->ERPfunction->set_date($this->request->data['sold_date']);
		$sold_data['accepted']=1;
		$sold_data['created_date']=date('Y-m-d H:i:s');
		$sold_data['created_by']=$this->request->session()->read('user_id');
		$asset_field = $erp_soldasset_history->newEntity();			
		$asset_field=$erp_soldasset_history->patchEntity($asset_field,$sold_data);
		if($erp_soldasset_history->save($asset_field))
		{
			//$this->ERPfunction->update_soldassetproject($this->request->data['asset_id'],$this->request->data['sold_quantity']);
			$this->Flash->success(__('Asset Sold Successfully', null), 
                            'default', 
                             array('class' => 'success'));
		}
		return $this->redirect(['action'=>'soldtheft']);
    }
	
	public function theftasset()
    {
		$erp_theftasset_history = TableRegistry::get('erp_assets_theft_history'); 
		$theft_data['asset_id']=$this->request->data['asset_id'];
		$theft_data['theft_quantity']=$this->request->data['theft_quantity'];
		$theft_data['theft_date']=$this->ERPfunction->set_date($this->request->data['theft_date']);
		$theft_data['deployed_to']=$this->request->data['deployed_to'];
		$theft_data['reason']=$this->request->data['reason'];
		$theft_data['accepted']=1;
		$theft_data['created_date']=date('Y-m-d H:i:s');
		$theft_data['created_by']=$this->request->session()->read('user_id');
		$asset_field = $erp_theftasset_history->newEntity();			
		$asset_field=$erp_theftasset_history->patchEntity($asset_field,$theft_data);
		if($erp_theftasset_history->save($asset_field))
		{
			//$this->ERPfunction->update_theftassetproject($this->request->data['asset_id'],$this->request->data['theft_quantity']);
			$this->Flash->success(__('Asset Theft Successfully', null), 
                            'default', 
                             array('class' => 'success'));
		}
		return $this->redirect(['action'=>'soldtheft']);
    }
	
	public function addmaintenance($maintenace_id=Null)
    {		  
		$maintenace_table = TableRegistry::get('erp_assets_maintenance'); 
		$erp_assets_maintenance_detail = TableRegistry::get('erp_assets_maintenance_detail'); 
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_groups = $this->ERPfunction->asset_group();
		$this->set('asset_groups',$asset_groups);
		$asset_names =$asset_table->find()->group("asset_name");
		$this->set('asset_names',$asset_names);
		$pay_method =$this->ERPfunction->payment_method();
		$this->set('pay_method',$pay_method);
		$users_table = TableRegistry::get('erp_users');
		$superviser = $users_table->find()->where(array('role'=>'accountant'));
		$this->set('superviser',$superviser);
		$this->set("back","aprovemaintenance");
		 
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects); 
		 
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('project_data',$project_list);
		
		/* $conn = ConnectionManager::get('default');
		$result = $conn->execute('SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = "erp_assets_maintenance" ');
		$number='';
		foreach($result as $incre){
				$number=(int)$incre['auto_increment'];
		}
		 */
		
		/* $number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_assets_maintenance","maintenace_id","amo_no");
		$new_grnno = sprintf("%09d", $number1);
		$grn_no = $result_arr['project_code'].'/OW/'.$new_grnno;		
		 
		 
		$auto_amono = sprintf("%09d", $number);
		$auto_amono = "YNEC/P/AUTO/AMO/{$auto_amono}";
		$this->set('auto_amono',$auto_amono); */
		
		if(isset($maintenace_id))
		{			
			$asset_me_action = 'edit';			
			$maintenace_data = $maintenace_table->get($maintenace_id);			
			$maintenace_details = $erp_assets_maintenance_detail->find()->where(["maintenance_id"=>$maintenace_id])->hydrate(false)->toArray();			
			$this->set('maintenace_data',$maintenace_data);
			$this->set('maintenace_details',$maintenace_details);
			$this->set('form_header','Edit Asset Maintenance Expense');
			$this->set('button_text','Update Asset Maintenance Expense');			
		}
		else
		{
			$asset_me_action = 'insert';			
			$this->set('form_header','Add Asset Maintenance Expense');
			$this->set('button_text','Add Asset Maintenance Expense');
		}	
		$this->set('asset_me_action',$asset_me_action);
		
		if($this->request->is('post')){
			$this->request->data['asset_id'] = $this->request->data['asset_name'];
			$this->request->data['maintenance_date']= $this->ERPfunction->set_date($this->request->data['maintenance_date']);
			$this->request->data['status']=1;
			if($asset_me_action == 'edit')
			{
				if(isset($_FILES['image_url']))
					{
						$file =$_FILES['image_url']["name"];
						$size = count($file);
						for($i=0;$i<$size;$i++) {
							$parts = pathinfo($_FILES['image_url']['name'][$i]);
						}
						$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
						// debug($ext);die;
						if($ext != 0) {
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
							
							
							$maintenace_data = $maintenace_table->patchEntity($maintenace_data,$post_data);
							if($maintenace_table->save($maintenace_data))
							{
								if(isset($this->request->data["description"])){
								$description_items = $this->request->data["description"];
								foreach($description_items['material'] as $key => $data)
								{
									if(isset($description_items['detail_id'][$key]))
									{
										$row = $erp_assets_maintenance_detail->get($description_items['detail_id'][$key]);
									}else{
										$row = $erp_assets_maintenance_detail->newEntity();
										$row['maintenance_id'] = $maintenace_id;
									}
									
									$row['material'] = $description_items['material'][$key];
									$row['quantity'] = $description_items['quantity'][$key];
									$row['unit'] = $description_items['unit'][$key];
									$row['rate'] = $description_items['rate'][$key];
									$row['gst'] = $description_items['gst'][$key];
									$row['amount'] = $description_items['amount'][$key];
									$row['created_date'] = date('Y-m-d');			
									$row['created_by']=$this->request->session()->read('user_id');
									$erp_assets_maintenance_detail->save($row);
								}
								}
								$this->Flash->success(__('Record Update Successfully', null), 
										'default', 
										array('class' => 'success'));				
							}
						}
						else{
							$this->Flash->error(__("Invalid File Extension, Please Retry."));
						}
					}
					else{
						
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
							
							
							$maintenace_data = $maintenace_table->patchEntity($maintenace_data,$post_data);
							if($maintenace_table->save($maintenace_data))
							{
								if(isset($this->request->data["description"])){
								$description_items = $this->request->data["description"];
								foreach($description_items['material'] as $key => $data)
								{
									if(isset($description_items['detail_id'][$key]))
									{
										$row = $erp_assets_maintenance_detail->get($description_items['detail_id'][$key]);
									}else{
										$row = $erp_assets_maintenance_detail->newEntity();
										$row['maintenance_id'] = $maintenace_id;
									}
									
									$row['material'] = $description_items['material'][$key];
									$row['quantity'] = $description_items['quantity'][$key];
									$row['unit'] = $description_items['unit'][$key];
									$row['rate'] = $description_items['rate'][$key];
									$row['gst'] = $description_items['gst'][$key];
									$row['amount'] = $description_items['amount'][$key];
									$row['created_date'] = date('Y-m-d');			
									$row['created_by']=$this->request->session()->read('user_id');
									$erp_assets_maintenance_detail->save($row);
								}
								}
								$this->Flash->success(__('Record Update Successfully', null), 
										'default', 
										array('class' => 'success'));				
							}
						
					}
							
			}
			else
			{
				if(isset($_FILES['image_url']))
					{
						$file =$_FILES['image_url']["name"];
						$size = count($file);
						for($i=0;$i<$size;$i++) {
							$parts = pathinfo($_FILES['image_url']['name'][$i]);
						}
						$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
						// debug($ext);die;
						if($ext != 0) {
							
							$maintenace_field = $maintenace_table->newEntity();
				
						$this->request->data['created_date']=date('Y-m-d H:i:s');
						$this->request->data['created_by']=$this->request->session()->read('user_id');
						
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
						
						$maintenace_field=$maintenace_table->patchEntity($maintenace_field,$this->request->data);
						if($maintenace_table->save($maintenace_field))
						{
							$maintenance_id = $maintenace_field->maintenace_id;
							$description_items = $this->request->data["description"];
							foreach($description_items['material'] as $key => $data)
							{
								$row = $erp_assets_maintenance_detail->newEntity();
								$row['maintenance_id'] = $maintenance_id;
								$row['material'] = $description_items['material'][$key];
								$row['quantity'] = $description_items['quantity'][$key];
								$row['unit'] = $description_items['unit'][$key];
								$row['rate'] = $description_items['rate'][$key];
								$row['gst'] = $description_items['gst'][$key];
								$row['amount'] = $description_items['amount'][$key];
								$row['created_date'] = date('Y-m-d');			
								$row['created_by']=$this->request->session()->read('user_id');
								$erp_assets_maintenance_detail->save($row);
							}
							$this->Flash->success(__('Asset Maintenance Expense Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
						}
						
						}
						else{
							$this->Flash->error(__("Invalid File Extension, Please Retry."));
						}
					}
					else{
						$maintenace_field = $maintenace_table->newEntity();
				
						$this->request->data['created_date']=date('Y-m-d H:i:s');
						$this->request->data['created_by']=$this->request->session()->read('user_id');
						
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
						
						$maintenace_field=$maintenace_table->patchEntity($maintenace_field,$this->request->data);
						if($maintenace_table->save($maintenace_field))
						{
							$maintenance_id = $maintenace_field->maintenace_id;
							$description_items = $this->request->data["description"];
							foreach($description_items['material'] as $key => $data)
							{
								$row = $erp_assets_maintenance_detail->newEntity();
								$row['maintenance_id'] = $maintenance_id;
								$row['material'] = $description_items['material'][$key];
								$row['quantity'] = $description_items['quantity'][$key];
								$row['unit'] = $description_items['unit'][$key];
								$row['rate'] = $description_items['rate'][$key];
								$row['gst'] = $description_items['gst'][$key];
								$row['amount'] = $description_items['amount'][$key];
								$row['created_date'] = date('Y-m-d');			
								$row['created_by']=$this->request->session()->read('user_id');
								$erp_assets_maintenance_detail->save($row);
							}
							$this->Flash->success(__('Asset Maintenance Expense Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
					}
			
				
				 
			 }
			$this->redirect(array("controller" => "Assets","action" => "aprovemaintenance"));	
			
		}	
		}
    }
	public function aprovemaintenance()
    {
		$maintenace_table = TableRegistry::get('erp_assets_maintenance');
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		// if($this->Usermanage->project_alloted($role)==1){ 
			// if(!empty($projects_ids)){
				// $maintenace_list = $maintenace_table->find()->where(["project_id IN"=>$projects_ids,'approved_status'=>0]);	
			// }else{
				// $maintenace_list=array();
			// }
		// }else{
			// $maintenace_list = $maintenace_table->find()->where(array('approved_status'=>0));
		// }
		
		$this->set('maintenace_list',array());
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			$or = array();				
			$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			if($or["project_id IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}	
			}
			$or["approved_status ="] = '0';
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
				{unset($or[$k]);}
			// debug($or);die;
			$maintenace_list = $maintenace_table->find()->where([$or])->hydrate(false)->toArray();
			$this->set('maintenace_list',$maintenace_list);
		}
    }
	public function maintenancerecords($asset_id=null)
    {
		$asset_table = TableRegistry::get('erp_assets'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		// if($role =='projectdirector' || $role =='projectcoordinator' || $role =='planningmanager' || $role =='siteaccountant' || $role =='constructionmanager' || $role =='billingengineer' || $role =='planningengineer' || $role =='materialmanager' || $role =='erpoperator')
		// {
			// if(!empty($projects_ids)){
						// $asset_name = $asset_table->find()->where(["deployed_to IN"=>$projects_ids]);	
			// }else{
				// $asset_name=array();
			// }
		// }else{
			// $asset_name = $asset_table->find()->toArray();
		// }	
		
		// $this->set('asset_list',$asset_name);
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_groups = $this->ERPfunction->asset_group();
		$this->set('asset_groups',$asset_groups);	
		$this->set('asset_id',$asset_id);	

		// $table_category=TableRegistry::get('erp_category_master');
		// $make_list=$table_category->find()->where(array('type'=>'make_in'));
		// $this->set('makelist',$make_list);
		
		// $asset_table = TableRegistry::get('erp_assets'); 
		// $vehicle_nos = $asset_table->find("list",["keyField"=>"vehicle_no","valueField"=>"vehicle_no"])->where(["vehicle_no !="=>""])->select("vehicle_no")->group("vehicle_no")->hydrate(false)->toArray();
		// $this->set('vehicle_nos',$vehicle_nos);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data["export_csv"]))
			{
				$post = $this->request->data();	
				$or = array();				
				$projects_ids = $this->Usermanage->users_project($this->user_id);
				
				$or["erp_assets_maintenance.project_id IN"] = (!empty($post["pro_id"]) && $post["pro_id"][0] != "All" )?explode(",",$post["pro_id"]):NULL;
				$or["erp_assets_maintenance.asset_id IN"] = (!empty($post["asset_name"]) && $post["asset_name"][0] != "All" )?explode(",",$post["asset_name"]):NULL;
				$or["erp_assets_maintenance.asset_group IN"] = (!empty($post["asset_group"]) && $post["asset_group"][0] != "All")?explode(",",$post["asset_group"]):NULL;
				$or["erp_assets_maintenance.maintenance_type IN"] = (!empty($post["maintenance_type"]) && $post["maintenance_type"][0] != "All")?explode(",",$post["maintenance_type"]):NULL;
				$or["erp_assets_maintenance.payment_by IN"] = (!empty($post["payment_type"]) && $post["payment_type"][0] != "All")?explode(",",$post["payment_type"]):NULL;
				$or["erp_assets_maintenance.vehicle_no ="] = (!empty($post["identity"]) && $post["identity"][0] != "All" )?$post["identity"]:NULL;
				
				if($or["erp_assets_maintenance.project_id IN"] == NULL)
				{
					if($role =='projectdirector' || $role =='assistantpmm' || $role =='deputymanagerelectric' || $role =='constructionmanager' || $role =='billingengineer' || $role == 'siteaccountant' || $role == 'materialmanager')
					{ 
						$or["erp_assets_maintenance.project_id IN"] = $projects_ids;
					}
				}
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
								
				$or["erp_assets_maintenance.approved_status ="] = 1;
				$erp_assets_maintenance = TableRegistry::get('erp_assets_maintenance');
				
				$result = $erp_assets_maintenance->find()->where($or)->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("Project Name","Date","A.M.O No.","Asset Group","Asset ID","Asset Name","Capacity","Identity/Vehi.No.","Maintenance Type","Amount of Expense","Payment");
			
				foreach($result as $retrive_data)
				{						
					$csv = array();		
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = date('d-m-Y',strtotime($retrive_data['maintenance_date']));
					$csv[] = $retrive_data['amo_no'];
					$csv[] = $this->ERPfunction->get_asset_group_name($retrive_data['asset_group']);
					$csv[] = $this->ERPfunction->get_asset_code($retrive_data['asset_id']);
					$csv[] = $this->ERPfunction->get_asset_name($retrive_data['asset_id']);
					$csv[] = $this->ERPfunction->get_asset_capacity($retrive_data['asset_id']);
					$csv[] = $retrive_data['vehicle_no'];
					$csv[] = ($retrive_data['maintenance_type'])?"Corrective / Breakdown":"Preventive / Routine";
					$csv[] = $retrive_data['expense_amount'];
					$csv[] = ($retrive_data["payment_by"] == 1)?"Cash":"Cheque";
					$rows[] = $csv;
				}
				$filename = "AssetMaintenance.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
				
			}
			if(isset($this->request->data["export_pdf"]))
			{
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$post = $this->request->data();	
				$or = array();				
				$projects_ids = $this->Usermanage->users_project($this->user_id);
				
				$or["erp_assets_maintenance.project_id IN"] = (!empty($post["pro_id"]) && $post["pro_id"][0] != "All" )?explode(",",$post["pro_id"]):NULL;
				$or["erp_assets_maintenance.asset_id IN"] = (!empty($post["asset_name"]) && $post["asset_name"][0] != "All" )?explode(",",$post["asset_name"]):NULL;
				$or["erp_assets_maintenance.asset_group IN"] = (!empty($post["asset_group"]) && $post["asset_group"][0] != "All")?explode(",",$post["asset_group"]):NULL;
				$or["erp_assets_maintenance.maintenance_type IN"] = (!empty($post["maintenance_type"]) && $post["maintenance_type"][0] != "All")?explode(",",$post["maintenance_type"]):NULL;
				$or["erp_assets_maintenance.payment_by IN"] = (!empty($post["payment_type"]) && $post["payment_type"][0] != "All")?explode(",",$post["payment_type"]):NULL;
				$or["erp_assets_maintenance.vehicle_no ="] = (!empty($post["identity"]) && $post["identity"][0] != "All" )?$post["identity"]:NULL;
				
				if($or["erp_assets_maintenance.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){  
						$or["erp_assets_maintenance.project_id IN"] = $projects_ids;
					}
				}
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
								
				$or["erp_assets_maintenance.approved_status"] = 1;
				$erp_assets_maintenance = TableRegistry::get('erp_assets_maintenance');
				
				$result = $erp_assets_maintenance->find()->where($or)->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("Project Name","Date","A.M.O No.","Asset Group","Asset ID","Asset Name","Capacity","Identity/Vehi.No.","Maintenance Type","Amount of Expense","Payment");
			
				foreach($result as $retrive_data)
				{						
					$csv = array();		
					$csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
					$csv[] = date('d-m-Y',strtotime($retrive_data['maintenance_date']));
					$csv[] = $retrive_data['amo_no'];
					$csv[] = $this->ERPfunction->get_asset_group_name($retrive_data['asset_group']);
					$csv[] = $this->ERPfunction->get_asset_code($retrive_data['asset_id']);
					$csv[] = $this->ERPfunction->get_asset_name($retrive_data['asset_id']);
					$csv[] = $this->ERPfunction->get_asset_capacity($retrive_data['asset_id']);
					$csv[] = $retrive_data['vehicle_no'];
					$csv[] = ($retrive_data['maintenance_type'])?"Corrective / Breakdown":"Preventive / Routine";
					$csv[] = $retrive_data['expense_amount'];
					$csv[] = ($retrive_data["payment_by"] == 1)?"Cash":"Cheque";
					$rows[] = $csv;
				}
				
				$this->set("rows",$rows);
				$this->render("assetmaintenancerecordpdf");
			}
		}
	}
	public function viewassets()
    {
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find();
		$this->set('asset_list',$asset_list);
    }
	public function deletemaintenance($maintenace_id)
	{
		$maintenace_table = TableRegistry::get('erp_assets_maintenance'); 
		$this->request->is(['post','delete']);
		
		$maintenace_data =$maintenace_table->get($maintenace_id);

		if($maintenace_table->delete($maintenace_data))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'aprovemaintenance']);
	}
	
	public function unapprovemaintenance($maintenace_id)
	{
		$maintenace_table = TableRegistry::get('erp_assets_maintenance');
		
		$maintenace_data =$maintenace_table->get($maintenace_id);
		$maintenace_data['approved_status'] = 0;
		$maintenace_data['approved_date'] = '';
		$maintenace_data['approve_by'] = '';
		
		if($maintenace_table->save($maintenace_data))
		{
			$this->Flash->success(__('Record Delete Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'maintenancerecords']);
	}
	
	public function equipmentlog()
	{		
		$this->set('form_header','Equipment Logs - Rent');
		$this->set('button_text','Prepare E.L.');
		$this->set("edit",false);
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$el_tbl = TableRegistry::get("erp_equipment");		
		/* $auto = rand(0,999).date("U");
		$chk = $el_tbl->find("all")->where(["elno"=>$auto])->count();
		if($chk > 0)
		{
			$auto = rand(0,999).date("U");
		} */
		// $chk = $el_tbl->find("all")->select(["elno"])->order(["id"=>"DESC"])->hydrate(false)->toArray();
		// if(!empty($chk))
		// {
			// $auto =(float) $chk[0]["elno"];			
			// $auto = $auto + 1;
			// $auto = sprintf("%012s", $auto);			
		// }
		// else{
			// $auto = "000000000001";
		// }
		// $this->set('elno',$auto);		
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_groups = $this->ERPfunction->asset_group();		
		$this->set('asset_groups',$asset_groups);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find();
		$this->set('asset_list',$asset_list);
		
		if($this->request->is("post"))
		{
			$el_tbl = TableRegistry::get("erp_equipment");
			$row = $el_tbl->newEntity();
			/* EL No code start */
			$post = $this->request->data;
			$project_code = $this->ERPfunction->get_projectcode($post['project_id']);
				
			$auto_id = $this->ERPfunction->generate_auto_id($post['project_id'],"erp_equipment","id","elno");
			$new_el_no = sprintf("%09d", $auto_id);
			$el_no = $project_code.'/EL/'.$new_el_no;
			/* EL No code end */
			$this->request->data['elno']=$el_no;
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data["created_by"]=$this->request->session()->read('user_id');
			$row = $el_tbl->patchEntity($row,$this->request->data);
			if($el_tbl->save($row))
			{
				$this->Flash->success(__('Record Saved Successfully', null), 
                            'default', 
                             array('class' => 'success'));
				return $this->redirect(['action'=>'equipmentlogrecord']);
			}
		}
	}
	
	public function equipmentlogown()
	{
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
				
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
		$this->set('asset_list',$asset_list);
		
		$asset_groups = $this->ERPfunction->asset_group();		
		$this->set('asset_groups',$asset_groups);
		if($this->request->is("post"))
		{
			/* EL No code start */
			$post = $this->request->data;
			$project_code = $this->ERPfunction->get_projectcode($post['project_id']);
				
			$auto_id = $this->ERPfunction->generate_auto_id($post['project_id'],"erp_equipmentown_log","id","el_no");
			$new_el_no = sprintf("%09d", $auto_id);
			$el_no = $project_code.'/EL/'.$new_el_no;
			/* EL No code end */
			
			$erp_equipmentown = TableRegistry::get('erp_equipmentown_log');
			
			/* Check the limit of asset with one entry for one day code start */
			$count = $erp_equipmentown->find()->where(["asset_id"=>$post['asset_name'],"date"=>date("Y-m-d",strtotime($post['el_date']))])->count();
			if($count)
			{
				$this->Flash->success(__('Asset reached the limit of entry for same day.'));
				return $this->redirect(['action'=>'equipmentlogown']);
			}
			/* Check the limit of asset with one entry for one day code end */
			
			/* New Record Code Start */
			$record = $erp_equipmentown->newEntity();
			$record['el_no'] = $el_no;
			$record['project_id'] = $post['project_id'];
			$record['date'] = date("Y-m-d",strtotime($post['el_date']));
			$record['ownership'] = $post['ownership'];
			$record['asset_group_id'] = $post['asset_group'];
			$record['asset_code'] = $post['asset_code'];
			$record['asset_id'] = $post['asset_name'];
			$record['asset_make'] = $post['asset_make'];
			$record['asset_capacity'] = $post['capacity'];
			$record['asset_model'] = $post['model_no'];
			$record['asset_identity'] = $post['vehicle_no'];
			$record['working_status'] = $post['working_status'];
			$record['duty_time'] = $post['duty_time'];
			$record['breakdown_time'] = $post['breakdown_time'];
			$record['start_km'] = $post['start_km'];
			$record['stop_km'] = $post['stop_km'];
			$record['usage_km'] = $post['usage_km'];
			$record['start_hr'] = $post['start_hr'];
			$record['stop_hr'] = $post['stop_hr'];
			$record['usage_hr'] = $post['usage_hr'];
			$record['driver_name'] = $post['driver_name'];
			$record['usage_detail'] = $post['usage_detail'];
			$record['approved_by'] = $this->request->session()->read('user_id');
			$record['crated_by'] = $this->request->session()->read('user_id');
			$record['created_date'] = date("Y-m-d");
			
			if($erp_equipmentown->save($record))
			{
				$this->Flash->success(__('Record Saved Successfully with EL No. :'.$el_no));
				return $this->redirect(['action'=>'index']);
			}
			/* New Record Code End */
		}
	}
	
	public function equipmentlogownrecord($asset_id = null)
	{	
		// $this->response->download('export.csv');
		$this->set('form_header','Equipment Log Records - Owned');		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
		$this->set('asset_list',$asset_list);
		
		// Send asset id from asset record operational history
		$this->set('asset_id',$asset_id);
		if($this->request->is("post"))
		{
			if(isset($this->request->data["export_csv"]) || isset($this->request->data["export_pdf"]))
			{
				$request = $this->request->data();
				$post = json_decode($request['export_filter_data']);
				$post = (array) $post;
			
				$or = array();				
				$projects_ids = $this->Usermanage->users_project($this->user_id);
				
				$or["date >="] = (!empty($post["date_from"]))?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["date <="] = (!empty($post["date_to"]))?date("Y-m-d",strtotime($post["date_to"])):NULL;
				$or["el_no"] = (!empty($post["elno"]))?$post["elno"]:NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["asset_id IN"] = (!empty($post["asset_name"]) && $post["asset_name"][0] != "All" )?$post["asset_name"]:NULL;
				$or["asset_code"] = (!empty($post["asset_id"]))?$post["asset_id"]:NULL;
				$or["asset_identity"] = (!empty($post["vehicle_no"]))?$post["vehicle_no"]:NULL;
				$or["ownership"] = (!empty($post["ownership"]) && $post["ownership"] != "All")?$post["ownership"]:NULL;
				
				if($or["project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){  
						$or["project_id IN"] = implode(",",$projects_ids);
					}
				}
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
						
				$erp_equipmentown_log = TableRegistry::get('erp_equipmentown_log');
				if(!empty($or)){
					$result = $erp_equipmentown_log->find()->where($or)->hydrate(false)->toArray();
				}else{
					$result = $erp_equipmentown_log->find()->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Date","E.L No.","Asset Name","Identity/Vehi.No.","Operational Status","Usage(km)","Usage(hr.)","Driver Name");
			
				foreach($result as $retrive_data)
				{						
					$csv = array();		
					$csv[] = date('d-m-Y',strtotime($retrive_data['date']));
					$csv[] = $retrive_data['el_no'];
					$csv[] = $this->ERPfunction->get_asset_name($retrive_data['asset_id']);
					$csv[] = $retrive_data['asset_identity'];
					if($retrive_data['working_status'] == "working")
					{
						$working_status = "Working";
					}elseif($retrive_data['working_status'] == "breakdown"){
						$working_status = "Break Down";
					}elseif($retrive_data['working_status'] == "idle"){
						$working_status = "Idle";
					}else{
						$working_status = "Working";
					}
					$csv[] = $working_status;
					$csv[] = $retrive_data['usage_km'];
					$csv[] = $retrive_data['usage_hr'];
					$csv[] = $retrive_data['driver_name'];
					$rows[] = $csv;
				}
				
				if(isset($this->request->data["export_csv"]))
				{
					$filename = "EquipmentLog-Owned.csv";
					$this->ERPfunction->export_to_csv($filename,$rows);
				}
				if(isset($this->request->data["export_pdf"]))
				{
					require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
					$this->set("rows",$rows);
					$this->render("equipmentlogownrecordpdf");
				}
			}			
		}
	}
	
	public function editequipmentlogowned($log_id)
	{
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
				
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
		$this->set('asset_list',$asset_list);
		
		$asset_groups = $this->ERPfunction->asset_group();		
		$this->set('asset_groups',$asset_groups);
		
		$erp_equipmentown_log = TableRegistry::get('erp_equipmentown_log');
		$record = $erp_equipmentown_log->get($log_id);
		$this->set('record',$record);
		if($this->request->is("post"))
		{
			$post = $this->request->data();
			$erp_equipmentown = TableRegistry::get('erp_equipmentown_log');
						
			/* New Record Code Start */
			$record = $erp_equipmentown->get($log_id);
			$record['project_id'] = $post['project_id'];
			$record['date'] = date("Y-m-d",strtotime($post['el_date']));
			$record['ownership'] = $post['ownership'];
			$record['asset_group_id'] = $post['asset_group'];
			$record['asset_code'] = $post['asset_code'];
			$record['asset_id'] = $post['asset_name'];
			$record['asset_make'] = $post['asset_make'];
			$record['asset_capacity'] = $post['capacity'];
			$record['asset_model'] = $post['model_no'];
			$record['asset_identity'] = $post['vehicle_no'];
			$record['working_status'] = $post['working_status'];
			$record['duty_time'] = $post['duty_time'];
			$record['breakdown_time'] = $post['breakdown_time'];
			$record['start_km'] = $post['start_km'];
			$record['stop_km'] = $post['stop_km'];
			$record['usage_km'] = $post['usage_km'];
			$record['start_hr'] = $post['start_hr'];
			$record['stop_hr'] = $post['stop_hr'];
			$record['usage_hr'] = $post['usage_hr'];
			$record['driver_name'] = $post['driver_name'];
			$record['usage_detail'] = $post['usage_detail'];
			$record['updated_by'] = $this->request->session()->read('user_id');
			$record['updated_date'] = date("Y-m-d");
			
			if($erp_equipmentown->save($record))
			{
				$this->Flash->success(__('Record Updated Successfully'));
				return $this->redirect(['action'=>'index']);
			}
			/* New Record Code End */
		}
	}
	
	public function viewaddequipmentown($log_id)
	{ 
			
		$erp_equipmentown_log = TableRegistry::get('erp_equipmentown_log');
		$record = $erp_equipmentown_log->get($log_id);

		$this->set('record',$record);
	}
	
	public function deleteequipmentlogown($log_id)
	{
		$erp_equipmentown = TableRegistry::get('erp_equipmentown_log');			
		$record = $erp_equipmentown->get($log_id);
		if($erp_equipmentown->delete($record))
		{
			$this->Flash->success(__('Record Deleted Successfully'));
			return $this->redirect(['action'=>'index']);
		}
	}
	
	public function rmcissueslip()
	{
		$this->set('form_header',' R.M.C Issue Slip');
		$this->set('button_text','Prepare RMC. I.S');
		$this->set("edit",false);
		$this->set("back","index");
		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
		$this->set('asset_list',$asset_list);
			
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_list',$agency_list);
		
		$rmc_tbl = TableRegistry::get("erp_rmc_issue");
		$auto = rand(0,999).date("U");
		$chk = $rmc_tbl->find("all")->where(["isno"=>$auto])->count();
		if($chk > 0)
		{
			$auto = rand(0,999).date("U");
		}
		$this->set('isno',$auto);
		
		if($this->request->is("post"))
		{
			$post = $this->request->data;			
			$rmc_tbl = TableRegistry::get("erp_rmc_issue");
			$total_fields = count($post["driver_name"]);
			$post["tmno"] = json_encode($post["tmno"]);			
			$post["driver_name"] = json_encode($post["driver_name"]);
			$post["time_in"] = json_encode($post["time_in"]);
			$post["time_out"] = json_encode($post["time_out"]);
			$post["quantity"] = json_encode($post["quantity"]);
			$post["received_by"] = json_encode($post["received_by"]);
			$post["created_by"] = $this->request->session()->read('user_id');
			$post["created_date"] = date('Y-m-d');
			$post["rmc_date"] = date('Y-m-d',strtotime($post["rmc_date"]));
			//$post["rmc_date"] = date('Y-m-d');
			// if(isset($_FILES["challan"]["name"]))
			// {				
				// $file = $this->ERPfunction->upload_challan("challan");	
				// if(!empty($file))
				// foreach($file as $attachment_file)
				// {
					// $old_files[] = $attachment_file;
				// }					
			// }
			$post["challan"] = json_encode($post["challan"]);	
			$post["created_by"] = $this->user_id;
			$post["created_date"] = date("Y-m-d");
			// debug($post);
			$row = $rmc_tbl->newEntity();
			$row = $rmc_tbl->patchEntity($row,$post);
			if($rmc_tbl->save($row))
			{
				$this->Flash->success(__('Record Saved Successfully'));
				return $this->redirect(['action'=>'index']);
			}			
		}
	}
	
	public function equipmentlogrecord()
	{		
		// $this->response->download('export.csv');
		$this->set('form_header','Equipment Log Records - Rent');		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
		$this->set('asset_list',$asset_list);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$vehicle_nos = $asset_table->find("list",["keyField"=>"vehicle_no","valueField"=>"vehicle_no"])->where(["vehicle_no !="=>""])->select("vehicle_no")->group("vehicle_no")->hydrate(false)->toArray();
		$this->set('vehicle_nos',$vehicle_nos);	
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$el_tbl = TableRegistry::get("erp_equipment");
				$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
				
				if(isset($post["project_id"]))
				{
					foreach($post["project_id"] as $pid)
					{
						if($pid != "All")
						{
							$project_codes[] = $this->ERPfunction->get_projectcode($pid);
						}
					}
				}	
				
				if(!empty($post["project_code"]))
				{
					$project_codes[] = $post["project_code"];
				}
				//$project_codes[] = (!empty($post["project_code"]) && $post["project_code"] != "All")?$post["project_code"]:NULL;
				$or = array();				
				$or["el_date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["el_date <="] = ($post["date_to"] != "")?date("Y-m-d",strtotime($post["date_to"])):NULL;
				// $or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"] != "All")?$post["project_id"]:NULL;
				$or["asset_name IN"] = (!empty($post["asset_name"]) && $post["asset_name"] != "All")?$post["asset_name"]:NULL;
				$or["ownership IN"] = (!empty($post["ownership"]) && $post["ownership"] != "All")?$post["ownership"]:NULL;
				//$or["project_code IN"] = (!empty($post["project_code"]))?"%{$project_codes}%":NULL;
				$or["project_code IN"] = (!empty($post["project_code"]) && $post["project_code"] != "All" || !empty($post["project_id"]) && $post["project_id"] != "All")?$project_codes:NULL;
				$or["asset_code LIKE"] = (!empty($post["asset_id"]))?"%{$post["asset_id"]}%":NULL;
				$or["elno LIKE"] = (!empty($post["elno"]))?"%{$post["elno"]}%":NULL;
				$or["vehicle_no LIKE"] = (!empty($post["vehicle_no"]))?"%{$post["vehicle_no"]}%":NULL;
				$or["driver_name LIKE"] = (!empty($post["driver_name"]))?"%{$post["driver_name"]}%":NULL;
				//$or["ownership IN"] = (!empty($post["ownership"]))?"%{$post["ownership"]}%":NULL;
				
				$or["vehicle_no IN"] = (!empty($post["vehicle_no"]) && $post["vehicle_no"] != "All")?$post["vehicle_no"]:NULL;
				$or["asset_code LIKE"] = (!empty($post["asset_id"]))?"%{$post["asset_id"]}%":NULL;
				$or["approved_by LIKE"] = (!empty($post["approve_by"]))?"%{$post["approve_by"]}%":NULL;									
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				 //debug($post);
				 //debug($or);die;
				
				// $search_data = $el_tbl->find("all")->where(["el_date >= "=>$post["date_from"],"el_date <= "=>$post["date_to"]
						// ,"OR"=>[$project_code,["elno"=>$post["elno"]],["ownership"=>$post["ownership"]],["vehicle_no"=>$post["vehicle_no"]],["asset_code"=>$post["asset_id"]],["asset_name"=>$post["asset_name"]]]]);
				
				$search_data = $el_tbl->find("all")->where($or);
				$search_data = $search_data->hydrate(false)->toArray();
				//var_dump($search_data);die;
				$this->set("search_data",$search_data);
			}
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "equipmentLogRecords.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$this->set("rows",$rows);
				$this->render("equipmentlogrecordpdf");
			}
		}else{
			$el_tbl = TableRegistry::get("erp_equipment");
			$projects_ids = $this->Usermanage->users_project($this->user_id);
			foreach($projects_ids as $pid)
			{
				$project_codes[] = $this->ERPfunction->get_projectcode($pid);
			}
			$role = $this->Usermanage->get_user_role($this->user_id);
			if($this->Usermanage->project_alloted($role)==1){  
				if(!empty($projects_ids)){
					$search_data = $el_tbl->find()->where(["project_code IN"=>$project_codes])->hydrate(false)->toArray();	
				}else{
					$search_data=array();
				}
			}else{
				$search_data = $el_tbl->find("all")->hydrate(false)->toArray();
			}		
			
			$this->set("search_data",$search_data);
		}
	}
	
	public function editeqrecord($id)
    {
		$this->set('form_header','Edit Equipment Log Records');
		$this->set('button_text','Update E.L.');
		$this->set('edit',true);
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		$auto = rand(0,999).date("U");
		$this->set('elno',$auto);		
		 
		$asset_groups = $this->ERPfunction->asset_group();		
		$this->set('asset_groups',$asset_groups);
		
		
		$el_tbl = TableRegistry::get("erp_equipment");
		$row = $el_tbl->get($id);
		$this->set("data",$row->toArray());
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find()->hydrate(false)->toArray();
		$this->set('asset_list',$asset_list);		
		
		if($this->request->is("post"))
		{
			$row=$el_tbl->patchEntity($row,$this->request->data);
			if($el_tbl->save($row))
			{
				$this->Flash->success(__('Record Updated Successfully'));
				// return $this->redirect(['action'=>'equipmentlogrecord']);
			}
		}
		
		
		$this->render("equipmentlog");		
    }
	
	public function viewequipmentlog($id)
    {
		$el_tbl = TableRegistry::get("erp_equipment");
		$row = $el_tbl->get($id);		
		$this->set("data",$row->toArray());
    }
	
	public function printequipmentowned($id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$erp_equipmentown_log = TableRegistry::get('erp_equipmentown_log');
		$record = $erp_equipmentown_log->get($id);
		$this->set('record',$record);
	}
	
	public function printequipmentrent($id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$el_tbl = TableRegistry::get("erp_equipment");
		$row = $el_tbl->get($id);		
		$this->set("data",$row->toArray());
	}
	
	public function rmcissuerecord($projects_id=null,$from=null,$to=null)
	{						
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		$this->set('form_header','RMC Issue Records');		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->where(["asset_group"=>1])->toArray();
		$this->set('asset_list',$asset_list);
		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_list',$agency_list);
		if($projects_id!=null){
			
			$or1 = array();		
			$or1["rmc_date >="] = ($from != null)?date("Y-m-d",strtotime($from)):NULL;
			$or1["rmc_date <="] = ($to != null)?date("Y-m-d",strtotime($to)):NULL;
			$or1["project_id"] = ($projects_id!=null)?$projects_id:NULL;
			$or1["approved"] = 1;
			
			$keys = array_keys($or1,"");				
					foreach ($keys as $k)
					{unset($or1[$k]);}
			$rmc_tbl = TableRegistry::get("erp_rmc_issue");
			if($this->Usermanage->project_alloted($role)==1){  
				if(!empty($projects_ids)){
					$search_data = $rmc_tbl->find()->where([$or1])->hydrate(false)->toArray();
				}else{
					$search_data=array();
				}
			}else{
				 $search_data = $rmc_tbl->find("all")->where([$or1])->hydrate(false)->toArray();
			}	
				
			
			$this->set("search_data",$search_data);
		}
		else{
			$rmc_tbl = TableRegistry::get("erp_rmc_issue");
			if($this->Usermanage->project_alloted($role)==1){  
				if(!empty($projects_ids)){
					$search_data = $rmc_tbl->find()->where(["project_id IN"=>$projects_ids,"approved"=>1])->hydrate(false)->toArray();
				}else{
					$search_data=array();
				}
			}else{
				 $search_data = $rmc_tbl->find("all")->where(["approved"=>1])->hydrate(false)->toArray();
			}	
				
			
			$this->set("search_data",$search_data);
		}
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
				
				$rmc_tbl = TableRegistry::get("erp_rmc_issue");			
				/* $search_data = $rmc_tbl->find("all")->where(["rmc_date >= "=>$post["date_from"],"rmc_date <= "=>$post["date_to"]
						,"OR"=>[$project_code,["isno"=>$post["isno"]],["concrete_grade"=>$post["concrete_grade"]],["agency_name"=>$post["agency_name"]],["asset_code"=>$post["asset_id"]],["asset_name"=>$post["asset_name"]]]]);
				 */
				$or = array();
				
				$or["rmc_date >="] = (!empty($post["date_from"]))?date("Y-m-d",strtotime($post["date_from"])):NULL;
				$or["rmc_date <="] = (!empty($post["date_to"]))?date("Y-m-d",strtotime($post["date_to"])):NULL;
				$or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0]!="All")?$post["project_id"]:NULL;
				$or["asset_name IN"] = (!empty($post["asset_name"]) && $post["asset_name"][0]!="All")?$post["asset_name"]:NULL;
				$or["concrete_grade IN"] = (!empty($post["concrete_grade"]) && $post["concrete_grade"][0]!="All")?$post["concrete_grade"]:NULL;
				$or["agency_name IN"] = (!empty($post["agency_name"]) && $post["agency_name"][0]!="All")?$post["agency_name"]:NULL;
				$or["project_code LIKE"] = (!empty($post["project_code"]))?"%{$post["project_code"]}%":NULL;
				$or["asset_code LIKE"] = (!empty($post["asset_id"]))?"%{$post["asset_id"]}%":NULL;
				$or["rmc_usage LIKE"] = (!empty($post["usage"]))?"%{$post["usage"]}%":NULL;
				$or["order_by LIKE"] = (!empty($post["order_by"]))?"%{$post["order_by"]}%":NULL;
				
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
				
				// $search_data = $rmc_tbl->find("all")->where(["rmc_date >= "=>$post["date_from"],"rmc_date <= "=>$post["date_to"]
						// ,"OR"=>[["project_code LIKE"=>"%{$post["project_code"]}%"],["isno LIKE"=>"%{$post["isno"]}%"],["concrete_grade LIKE"=>"%{$post["concrete_grade"]}%"],["agency_name LIKE"=>"%{$post["agency_name"]}%"],["asset_code LIKE"=>"%{$post["asset_id"]}%"],["asset_name LIKE"=>"%{$post["asset_name"]}%"]]]);
				
				$search_data = $rmc_tbl->find("all")->where([$or,"approved"=>1]);
				
				
				
				$search_data = $search_data->hydrate(false)->toArray();				
				$this->set("search_data",$search_data);
			}
			
		}
	}
	
	public function editrmcrecord($eid)
    {
		$rmc_tbl = TableRegistry::get("erp_rmc_issue");
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());		
		
		$this->set('form_header',' Edit R.M.C Issue Slip');
		$this->set('button_text','Update RMC. I.S');
		$this->set("edit",true);
		$this->set("back","rmcissuealert");
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);		
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
		$this->set('asset_list',$asset_list);
			
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_list',$agency_list);	
	
		$this->render("rmcissueslip");
		
		if($this->request->is("post"))
		{			
			$post = $this->request->data;			
			$total_fields = count($post["challan"]);			
						
			
			$old_tmo = (!empty($post["old_tmno"]))?array_merge($post["old_tmno"],$post["tmno"]):$post["tmno"];
			$old_challan = (!empty($post["old_challan"]))?array_merge($post["old_challan"],$post["challan"]):$post["challan"];
			$old_driver_name = (!empty($post["old_driver_name"]))?array_merge($post["old_driver_name"],$post["driver_name"]):$post["driver_name"];
			$old_time_in = (!empty($post["old_time_in"]))?array_merge($post["old_time_in"],$post["time_in"]):$post["time_in"];
			$old_time_out = (!empty($post["old_time_out"]))?array_merge($post["old_time_out"],$post["time_out"]):$post["time_out"];
			$old_quantity = (!empty($post["old_quantity"]))?array_merge($post["old_quantity"],$post["quantity"]):$post["quantity"];
			$old_received_by = (!empty($post["old_received_by"]))?array_merge($post["old_received_by"],$post["received_by"]):$post["received_by"];
			
			
			$post["tmno"] = json_encode($old_tmo);
			$post["challan"] = json_encode($old_challan);
			$post["driver_name"] = json_encode($old_driver_name);
			$post["time_in"] = json_encode($old_time_in);
			$post["time_out"] = json_encode($old_time_out);
			$post["quantity"] = json_encode($old_quantity);
			$post["received_by"] = json_encode($old_received_by);
			
			$post["tmno"] = str_replace(',""','',$post['tmno']);
			$post["challan"] = str_replace(',""','',$post['challan']);
			$post["driver_name"] = str_replace(',""','',$post['driver_name']);
			$post["time_in"] = str_replace(',""','',$post['time_in']);
			$post["time_out"] = str_replace(',""','',$post['time_out']);
			$post["quantity"] = str_replace(',""','',$post['quantity']);
			$post["received_by"] = str_replace(',""','',$post['received_by']);
			
			$post["last_edit_by"] = $this->request->session()->read('user_id');
			$post["last_edit"] = date('Y-m-d');
			$post["rmc_date"] = date('Y-m-d',strtotime($post["rmc_date"]));
			
			// if(isset($_FILES["challan"]["name"]))
			// {				
				// $file = $this->ERPfunction->upload_challan("challan");	
				// if(!empty($file))
				// foreach($file as $attachment_file)
				// {
					// $new_files[] = $attachment_file;
				// }					
			// }
			// $all_files= (!empty($post["old_challan"]))?array_merge($post["old_challan"],$new_files):$new_files;
			// $post["challan"] = json_encode($all_files);
			
			$row = $rmc_tbl->patchEntity($data,$post);
			if($rmc_tbl->save($row))
			{
				// $this->Flash->success(__('Record Updated Successfully'));
				// return $this->redirect(['action'=>'rmcissuerecord']);
				echo "<script>window.close();</script>";
			}			
		}
    }
	
	public function viewrmcissueslip($vid)
	{
		$rmc_tbl = TableRegistry::get("erp_rmc_issue");
		$data = $rmc_tbl->get($vid);
		$this->set("data",$data->toArray());
		
		$this->set('form_header',' Edit R.M.C Issue Slip');
		$this->set('button_text','Update RMC. I.S');
		$this->set("edit",true);
		$this->set("back","rmcissuerecord");
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_list = $asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
		$this->set('asset_list',$asset_list);
			
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("list",["keyField"=>"id","valueField"=>"agency_name"])->toArray();
		$this->set('agency_list',$agency_list);	
		
	}

	public function assetrecord()
	{	
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'Assets';
			$back_page = 'index';
		}
		
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		$this->set('form_header','Asset Records');
		$this->set("edit",false);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids)){
						$asset_name = $asset_table->find("list",["keyField"=>"asset_name","valueField"=>"asset_name"])->where(["deployed_to IN"=>$projects_ids]);	
			}else{
				$asset_name=array();
			}
		}else{
			$asset_name = $asset_table->find("list",["keyField"=>"asset_name","valueField"=>"asset_name"])->toArray();
		}	
		
		$this->set('asset_name',$asset_name);
		
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_groups = $this->ERPfunction->asset_group();
		$this->set('asset_groups',$asset_groups);	

		$table_category=TableRegistry::get('erp_category_master');
		$make_list=$table_category->find()->where(array('type'=>'make_in'));
		$this->set('makelist',$make_list);
		
		$asset_table = TableRegistry::get('erp_assets'); 
		$vehicle_nos = $asset_table->find("list",["keyField"=>"vehicle_no","valueField"=>"vehicle_no"])->where(["vehicle_no !="=>""])->select("vehicle_no")->group("vehicle_no")->hydrate(false)->toArray();
		$this->set('vehicle_nos',$vehicle_nos);		
		$this->set('role',$this->role);		
		
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data["go1"]))
			{
			$post = $this->request->data;
			// debug($post);die;
			$asset_tbl = TableRegistry::get("erp_assets");
			$maint_tbl = TableRegistry::get("erp_assets_maintenance");
			$sold_tbl = TableRegistry::get("erp_assets_sold_history");
			$theft_tbl = TableRegistry::get("erp_assets_theft_history");
			
			$or = array();				
			/* $or["asset_group LIKE"] = (!empty($post["asset_group"]))?"%{$post["asset_group"]}%":NULL;
			$or["asset_id LIKE"] = (!empty($post["asset_id"]))?"%{$post["asset_id"]}%":NULL;
			$or["asset_name LIKE"] = (!empty($post["asset_name"]))?"%{$post["asset_name"]}%":NULL;
			$or["vehicle_no LIKE"] = (!empty($post["vehicle_no"]))?"%{$post["vehicle_no"]}%":NULL;
			$or["deployed_to LIKE"] = (!empty($post["deployed_to"]))?"%{$post["deployed_to"]}%":NULL; */	
			
			// $or["asset_group IN"] = (!empty($post["asset_group"]) && $post["asset_group"][0] != "All" )?$post["asset_group"]:NULL;
			// $or["asset_id LIKE"] = (!empty($post["asset_id"]))?"%{$post["asset_id"]}%":NULL;
			// $or["asset_name IN"] = (!empty($post["asset_name"]) && $post["asset_name"][0] != "All")?$post["asset_name"]:NULL;
			// $or["vehicle_no in"] = (!empty($post["vehicle_no"]) && $post["vehicle_no"][0] != "All" )?$post["vehicle_no"]:NULL;
			// $or["deployed_to IN"] = (!empty($post["deployed_to"]) && $post["deployed_to"][0] != "All")?$post["deployed_to"]:NULL;					
			// $or["operational_status IN"] = (!empty($post["status"]) && $post["status"][0] != "All")?$post["status"]:NULL;				
			
			$or["purchase_date >="] = ($post["purchase_from_date"] != "")?date("Y-m-d",strtotime($post["purchase_from_date"])):NULL;
			$or["purchase_date <="] = ($post["purchase_to_date"] != "")?date("Y-m-d",strtotime($post["purchase_to_date"])):NULL;
			$or["deployed_to IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
			$or["asset_make IN"] = (!empty($post["make_id"]) && $post["make_id"][0] != "All" )?$post["make_id"]:NULL;
			$or["asset_group IN"] = (!empty($post["asset_group"]) && $post["asset_group"][0] != "All" )?$post["asset_group"]:NULL;
			$or["asset_name IN"] = (!empty($post["asset_name"]) && $post["asset_name"][0] != "All" )?$post["asset_name"]:NULL;
			$or["operational_status IN"] = (!empty($post["status"]) && $post["status"][0] != "All")?$post["status"]:NULL;
			$or["asset_code"] = (!empty($post["asset_id"]))?$post["asset_id"]:NULL;
			$or["capacity"] = (!empty($post["asset_capacity"]))?$post["asset_capacity"]:NULL;
			$or["vehicle_no"] = (!empty($post["identity"]))?$post["identity"]:NULL;
			
			if($or["deployed_to IN"] == NULL)
			{
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["deployed_to IN"] = $projects_ids;
				}
			}
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			
			$search_data = $asset_tbl->find()->where([$or])->hydrate(false)->toArray();
			
			if(!empty($post["status"]) && $post["status"][0] != "All")
			{
				$check_sold = in_array("sold",$post["status"]);
				if($check_sold)
				{
					$asset_ids = $sold_tbl->find()->select(["asset_id"])->hydrate(false)->toArray();
					foreach($asset_ids as $asset)
					{$ids[]=$asset["asset_id"];}
					$in_id = implode(",",$ids);
					$searched_data = $asset_tbl->find()->where(["erp_assets.asset_id IN"=>$ids])->select($asset_tbl);
					$searched_data = $searched_data->leftjoin(["erp_assets_sold_history"=>"erp_assets_sold_history","asset_id IN ({$in_id})"])->select($sold_tbl)->hydrate(false)->toArray();
					foreach($searched_data as $find_row)
					{
						$sold_search[] = array_merge($find_row,$find_row["erp_assets_sold_history"]);
					}
					$search_data = array_merge($search_data,$sold_search);
				}
				
				$check_theft = in_array("theft",$post["status"]);
				
				if($check_theft)
				{
					$asset_ids = $theft_tbl->find()->select(["asset_id"])->hydrate(false)->toArray();
					foreach($asset_ids as $asset)
					{$ids[]=$asset["asset_id"];}				
					$theft_data = $asset_tbl->find()->where(["asset_id IN"=>$ids])->hydrate(false)->toArray();
					$search_data = array_merge($search_data,$theft_data);
				}
				
				// die;
			}
			
			// if(!empty($post["status"]) && $post["status"][0] != "All")
			// {
				// switch ($post["status"])
				// {
					// CASE "working":
						$or["operational_status LIKE"] = "%working%"; // ALL ARE WORKING BY DEFAULT
						// $search_data = $asset_tbl->find()->where([$or])->hydrate(false)->toArray();
					// break;
					// CASE "notworking":
						// $or["operational_status LIKE IN"] = "%notworking%";
						// $search_data = $asset_tbl->find()->where([$or])->hydrate(false)->toArray();
					// break;					
					// CASE "sold":
						// $asset_ids = $sold_tbl->find()->select(["asset_id"])->hydrate(false)->toArray();
						// foreach($asset_ids as $asset)
						// {$ids[]=$asset["asset_id"];}
						// $in_id = implode(",",$ids);
						// $searched_data = $asset_tbl->find()->where(["erp_assets.asset_id IN"=>$ids])->select($asset_tbl);
						// $searched_data = $searched_data->leftjoin(["erp_assets_sold_history"=>"erp_assets_sold_history","asset_id IN ({$in_id})"])->select($sold_tbl)->hydrate(false)->toArray();
						// foreach($searched_data as $find_row)
						// {
							// $search_data[] = array_merge($find_row,$find_row["erp_assets_sold_history"]);
						// }
						
					// break;
					// CASE "theft":
						// $asset_ids = $theft_tbl->find()->select(["asset_id"])->hydrate(false)->toArray();
						// foreach($asset_ids as $asset)
						// {$ids[]=$asset["asset_id"];}				
						// $search_data = $asset_tbl->find()->where(["asset_id IN"=>$ids])->hydrate(false)->toArray();
					// break;
				// } 				
			// }
			// else{
				// $search_data = $asset_tbl->find()->where([$or])->hydrate(false)->toArray();
			// }
			$this->set("search_data",$search_data);
			// debug($search_data);die;
		}
			if(isset($this->request->data["export_csv"]))
			{
				// $rows = unserialize($this->request->data["rows"]);
				$asset_tbl = TableRegistry::get("erp_assets");
				$post = $this->request->data;
				// debug($post);die;
				$deployed_to = (!empty($post["e_project_id"]))?explode(",",$post["e_project_id"]):NULL;
				$make_id = (!empty($post["e_make_id"]))?explode(",",$post["e_make_id"]):NULL;
				$asset_group = (!empty($post["e_asset_group"]))?explode(",",$post["e_asset_group"]):NULL;
				$asset_name = (!empty($post["e_asset_name"]))?explode(",",$post["e_asset_name"]):NULL;
				$status = (!empty($post["e_status"]))?explode(",",$post["e_status"]):array();
				
				$or["purchase_date >="] = ($post["e_purchase_from_date"] != "")?date("Y-m-d",strtotime($post["e_purchase_from_date"])):NULL;
				$or["purchase_date <="] = ($post["e_purchase_to_date"] != "")?date("Y-m-d",strtotime($post["e_purchase_to_date"])):NULL;
				$or["deployed_to IN"] = (!empty($deployed_to) && $deployed_to[0] != "All" )?$deployed_to:NULL;
				$or["asset_make IN"] = (!empty($make_id) && $make_id[0] != "All" )?$make_id:NULL;
				$or["asset_group IN"] = (!empty($asset_group) && $asset_group[0] != "All" )?$asset_group:NULL;
				$or["asset_name IN"] = (!empty($asset_name) && $asset_name[0] != "All" )?$asset_name:NULL;
				$or["asset_code ="] = (!empty($post["e_asset_id"]))?$post["e_asset_id"]:NULL;
				$or["capacity ="] = (!empty($post["e_asset_capacity"]))?$post["e_asset_capacity"]:NULL;
				$or["vehicle_no ="] = (!empty($post["e_identity"]))?$post["e_identity"]:NULL;
				
				
				if($or["deployed_to IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						$or["deployed_to IN"] = $projects_ids;
					}
				}
				$asset_ids = array();
				if(in_array("breakdown",$status))
				{
					$breakdown_asset = $this->ERPfunction->get_breakdown_asset();
					$asset_ids = array_merge($asset_ids,$breakdown_asset);
				}
				
				if(in_array("idle",$status))
				{
					$idle_asset = $this->ERPfunction->get_idle_asset();
					$asset_ids = array_merge($asset_ids,$idle_asset);
				}
				
				if(in_array("sold",$status))
				{
					$sold_asset = $this->ERPfunction->get_sold_asset();
					$asset_ids = array_merge($asset_ids,$sold_asset);
				}
				
				if(in_array("theft",$status))
				{
					$theft_asset = $this->ERPfunction->get_theft_asset();
					$asset_ids = array_merge($asset_ids,$theft_asset);
				}
				
				if(!empty($asset_ids))
				{
					$asset_ids = array_unique($asset_ids);
					$or["asset_id IN"] = implode(",",$asset_ids);
				}
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if(!empty($or)){
					$search_data = $asset_tbl->find()->where([$or])->hydrate(false)->toArray();
				}else{
					$search_data = $asset_tbl->find()->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Asset ID","Asset Name","Capacity","Make","Identity/Veh.No.","Date of purchase","Operational Status","Currently Deployed to","Total Maintenance Expence");
				foreach($search_data as $data)
				{
					$csv = array();
					
					$csv[] = $data['asset_code'];
					$csv[] = $data['asset_name'];
					$csv[] = $data['capacity'];
					$csv[] = $this->ERPfunction->get_category_title($data['asset_make']);
					$csv[] = $data['vehicle_no'];
					$csv[] = $data['purchase_date']->format("Y-m-d");
					$csv[] = $this->ERPfunction->get_asset_operational_status($data['asset_id']);
					$csv[] = $this->ERPfunction->get_projectname($data['deployed_to']);
					$csv[] = $this->ERPfunction->get_asset_expense($data['asset_id']);
					$rows[] = $csv;	
				}
				$filename = "Assets.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				// $rows = unserialize($this->request->data["rows"]);
				$asset_tbl = TableRegistry::get("erp_assets");
				$post = $this->request->data;
				// debug($post);die;
				$deployed_to = (!empty($post["e_project_id"]))?explode(",",$post["e_project_id"]):NULL;
				$make_id = (!empty($post["e_make_id"]))?explode(",",$post["e_make_id"]):NULL;
				$asset_group = (!empty($post["e_asset_group"]))?explode(",",$post["e_asset_group"]):NULL;
				$asset_name = (!empty($post["e_asset_name"]))?explode(",",$post["e_asset_name"]):NULL;
				$status = (!empty($post["e_status"]))?explode(",",$post["e_status"]):array();
				
				$or["purchase_date >="] = ($post["e_purchase_from_date"] != "")?date("Y-m-d",strtotime($post["e_purchase_from_date"])):NULL;
				$or["purchase_date <="] = ($post["e_purchase_to_date"] != "")?date("Y-m-d",strtotime($post["e_purchase_to_date"])):NULL;
				$or["deployed_to IN"] = (!empty($deployed_to) && $deployed_to[0] != "All" )?$deployed_to:NULL;
				$or["asset_make IN"] = (!empty($make_id) && $make_id[0] != "All" )?$make_id:NULL;
				$or["asset_group IN"] = (!empty($asset_group) && $asset_group[0] != "All" )?$asset_group:NULL;
				$or["asset_name IN"] = (!empty($asset_name) && $asset_name[0] != "All" )?$asset_name:NULL;
				$or["asset_code ="] = (!empty($post["e_asset_id"]))?$post["e_asset_id"]:NULL;
				$or["capacity ="] = (!empty($post["e_asset_capacity"]))?$post["e_asset_capacity"]:NULL;
				$or["vehicle_no ="] = (!empty($post["e_identity"]))?$post["e_identity"]:NULL;
				
				
				if($or["deployed_to IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){ 
						$or["deployed_to IN"] = $projects_ids;
					}
				}
				$asset_ids = array();
				if(in_array("breakdown",$status))
				{
					$breakdown_asset = $this->ERPfunction->get_breakdown_asset();
					$asset_ids = array_merge($asset_ids,$breakdown_asset);
				}
				
				if(in_array("idle",$status))
				{
					$idle_asset = $this->ERPfunction->get_idle_asset();
					$asset_ids = array_merge($asset_ids,$idle_asset);
				}
				
				if(in_array("sold",$status))
				{
					$sold_asset = $this->ERPfunction->get_sold_asset();
					$asset_ids = array_merge($asset_ids,$sold_asset);
				}
				
				if(in_array("theft",$status))
				{
					$theft_asset = $this->ERPfunction->get_theft_asset();
					$asset_ids = array_merge($asset_ids,$theft_asset);
				}
				
				if(!empty($asset_ids))
				{
					$asset_ids = array_unique($asset_ids);
					$or["asset_id IN"] = implode(",",$asset_ids);
				}
								
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if(!empty($or)){
					$search_data = $asset_tbl->find()->where([$or])->hydrate(false)->toArray();
				}else{
					$search_data = $asset_tbl->find()->hydrate(false)->toArray();
				}
				
				$rows = array();
				$rows[] = array("Asset ID","Asset Name","Capacity","Make","Identity/Veh.No.","Date of purchase","Operational Status","Currently Deployed to","Total Maintenance Expence");
				foreach($search_data as $data)
				{
					$csv = array();
					
					$csv[] = $data['asset_code'];
					$csv[] = $data['asset_name'];
					$csv[] = $data['capacity'];
					$csv[] = $this->ERPfunction->get_category_title($data['asset_make']);
					$csv[] = $data['vehicle_no'];
					$csv[] = $data['purchase_date']->format("Y-m-d");
					$csv[] = $this->ERPfunction->get_asset_operational_status($data['asset_id']);
					$csv[] = $this->ERPfunction->get_projectname($data['deployed_to']);
					$csv[] = $this->ERPfunction->get_asset_expense($data['asset_id']);
					$rows[] = $csv;	
				}
				$this->set("rows",$rows);
				$this->render("assetlistspdf");
			}
		}
	}
	
	public function rmcissuealert()
	{
		$this->set('form_header','RMC Issue Alert');		
		$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		$this->set('projects',$projects);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		$projects_ids = $this->Usermanage->users_project($this->user_id);
		
		$search_data = array();
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$project_code = (!empty($post["project_code"]))?["project_code"=>$post["project_code"]]:["project_code LIKE"=>"%%"];
				
				$rmc_tbl = TableRegistry::get("erp_rmc_issue");	
				
				$or = array();	
				$or["approved"] = "0";
				$or["project_id LIKE"] = (!empty($post["project_id"]))?"%{$post["project_id"]}%":NULL;
				$or["project_code LIKE"] = (!empty($post["project_code"]))?"%{$post["project_code"]}%":NULL;				
				$or["concrete_grade LIKE"] = (!empty($post["concrete_grade"]))?"%{$post["concrete_grade"]}%":NULL;				
					
				if($this->Usermanage->project_alloted($role)==1){ 
					$or["project_id IN"] = $projects_ids;
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}				
				
				$search_data = $rmc_tbl->find("all")->where([$or]);			
				
				$search_data = $search_data->hydrate(false)->toArray();				
				$this->set("search_data",$search_data);
			}
			
		}else{
			$rmc_tbl = TableRegistry::get("erp_rmc_issue");
			$projects_ids = $this->Usermanage->users_project($this->user_id);		
			
			if($this->Usermanage->project_alloted($role)==1){ 
				if(!empty($projects_ids)){
					$search_data = $rmc_tbl->find()->where(["project_id IN"=>$projects_ids,"approved"=>0]);	
				}else{
					$search_data=array();
				}
			}else{
				 $search_data = $rmc_tbl->find("all")->where(["approved"=>0])->hydrate(false)->toArray();
			}
		
			$this->set("search_data",$search_data);
		}
	}
	
	public function deletermc($id)
	{
		$rmc_tbl = TableRegistry::get("erp_rmc_issue");
		$row = $rmc_tbl->get($id);
		if($rmc_tbl->delete($row))
		{
			$this->Flash->success(__('Record Delete Successfully'));
			return $this->redirect(['action'=>'rmcissuealert']);
		}
		
	}
	
	public function unapproveermc($id)
	{
		$rmc_tbl = TableRegistry::get("erp_rmc_issue");
		$row = $rmc_tbl->get($id);
		$row->approved = 0;
		$row->approved_by = NULL;
		$row->approved_date = NULL;
		if($rmc_tbl->save($row))
		{
			$this->Flash->success(__('Record Unapprove Successfully'));
			return $this->redirect(['action'=>'rmcissuerecord']);
		}
		
	}
	
	public function printrmc($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_rmc_issue");
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
	}
	
	public function viewaddasset($asset_id)
	{
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_groups = $this->ERPfunction->asset_group();
		$this->set('asset_groups',$asset_groups);
		$table_category=TableRegistry::get('erp_category_master');
		$make_list=$table_category->find()->where(array('type'=>'make_in'));
		$this->set('makelist',$make_list);
		
		$project_table = TableRegistry::get('erp_projects'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids))
			{
				$or = array();
				$or["project_id IN"] = $projects_ids;
				$project_list = $project_table->find()->where($or);
			}else{
				$project_list = array();
			}
		}
		else{
			$project_list = $project_table->find();
		}

		
		$this->set('project_data',$project_list);
		
		$vendor_tbl = TableRegistry::get('erp_vendor');
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		$this->set('vendor_list',$vendor_list);
		
		if(isset($asset_id))
		{			
			$asset_action = 'edit';			
			$asset_data = $asset_table->get($asset_id);			
			$this->set('asset_data',$asset_data);
			$this->set('form_header','View Asset');
			$this->set('button_text','Update Asset');			
		}
	
		
		$this->set('asset_action',$asset_action);
		
	}
	
	public function viewaddmaintenance($maintenace_id)
	{
		$maintenace_table = TableRegistry::get('erp_assets_maintenance');
		$erp_assets_maintenance_detail = TableRegistry::get('erp_assets_maintenance_detail');
		if(isset($maintenace_id))
		{						
			$maintenace_data = $maintenace_table->get($maintenace_id);
			$maintenace_details = $erp_assets_maintenance_detail->find()->where(["maintenance_id"=>$maintenace_id])->hydrate(false)->toArray();			
			$this->set('maintenace_data',$maintenace_data);		
			$this->set('maintenace_details',$maintenace_details);		
		}
	}
	
	public function printassetmaintenance($maintenace_id)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$maintenace_table = TableRegistry::get('erp_assets_maintenance');
		$erp_assets_maintenance_detail = TableRegistry::get('erp_assets_maintenance_detail'); 		
		if(isset($maintenace_id))
		{						
			$maintenace_data = $maintenace_table->get($maintenace_id);
			$maintenace_details = $erp_assets_maintenance_detail->find()->where(["maintenance_id"=>$maintenace_id])->hydrate(false)->toArray();			
			$this->set('maintenace_data',$maintenace_data);		
			$this->set('maintenace_details',$maintenace_details);		
		}
	}
	
	public function addmaintenancenotification()
	{
		$this->set("back","index");
		$this->set("form_header","Add P&M Notification");
		
		//Asset List
		$asset_table = TableRegistry::get('erp_assets');
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		if($role == "assistantpmm"){ 
			if(!empty($projects_ids)){
				$asset_names =$asset_table->find()->where(['deployed_to IN'=>$projects_ids])->group("asset_name");
			}else{
				$asset_names=array();
			}
		}else{
			$asset_names =$asset_table->find()->group("asset_name");
		}
		
		$this->set('asset_names',$asset_names);
		
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('project_data',$project_list);
		
		if($this->request->is("post"))
		{
			$data = $this->request->data;
			
			$notification_tbl = TableRegistry::get("erp_maintainance_notification");
			$row = $notification_tbl->newEntity();
			$post['asset_id'] = $data['asset_name'];
			$post["asset_code"] = $data['asset_code'];
			$post["asset_make"] = $data['asset_make'];
			$post["asset_capacity"] = $data['capacity'];
			$post["model_no"] = $data['model_no'];
			$post["identity"] = $data['vehicle_no'];
			$post["deploy_to"] = $data['deploy_to_project'];
			$post["message"] = $data['messages'];
			$post["event_date"] = date("Y-m-d",strtotime($data['event_date']));
			$post["time_before"] = $data['notification_time'];
			$post["event_type"] = $data['event_type'];
			$post["created_by"] = $this->request->session()->read('user_id');
			$post["created_date"] = date("Y-m-d");
			$row = $notification_tbl->patchEntity($row,$post);
			if($notification_tbl->save($row))
			{
				$this->Flash->success(__('Record Saved Successfully', null), 
                            'default', 
                             array('class' => 'success'));
				return $this->redirect(['action'=>'index']);
			}
		}
	}
	
	public function maintenancenotificationlist()
	{
		$erp_notification = TableRegistry::get('erp_maintainance_notification');
		$this->set("form_header","P&M Notification Records");
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set('projects',$projects);
		
		// $asset_table = TableRegistry::get('erp_assets'); 
		// $asset_list = $asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
		// $this->set('asset_list',$asset_list);
		
		//Asset List
		$asset_table = TableRegistry::get('erp_assets');
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		if($role == "assistantpmm"){ 
			if(!empty($projects_ids)){
				$asset_list =$asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->where(['deployed_to IN'=>$projects_ids])->toArray();
			}else{
				$asset_list=array();
			}
		}else{
			$asset_list =$asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
		}
		
		$this->set('asset_list',$asset_list);
		
		// $search_data = $erp_notification->find("all")->where(['OR'=>[["event_type !="=>"single"],["event_date >="=>date("Y-m-d")]]])->hydrate(false)->toArray();	
		// $this->set("search_data",$search_data);
		
		if($role == "assistantpmm"){ 
			if(!empty($projects_ids)){
				$search_data = $erp_notification->find("all")->where([['deploy_to IN'=>$projects_ids],['OR'=>[["event_type !="=>"single"],["event_date >="=>date("Y-m-d")]]]])->hydrate(false)->toArray();
			}else{
				$search_data=array();
			}
		}else{
			// $asset_list =$asset_table->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->toArray();
			$search_data = $erp_notification->find("all")->where(['OR'=>[["event_type !="=>"single"],["event_date >="=>date("Y-m-d")]]])->hydrate(false)->toArray();
		}
		
		$this->set("search_data",$search_data);
		if($this->request->is("post"))
		{
			$post = $this->request->data;
			if(isset($post["search"]))
			{
				$or = array();				
					
				$or["erp_maintainance_notification.asset_id"] = (!empty($post["asset_name"]) && $post["asset_name"] != "All" )?$post["asset_name"]:NULL;
				
				$or["erp_maintainance_notification.deploy_to"] = (!empty($post["deploy_to_project"]) && $post["deploy_to_project"] != "All" )?$post["deploy_to_project"]:NULL;
				
				$or["erp_maintainance_notification.identity"] = ($post["identity"] != "")?$post["identity"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				$search_data = $erp_notification->find("all")->where([$or])->hydrate(false)->toArray();	
				$this->set("search_data",$search_data);
				$this->set("data",$post);
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize($this->request->data["rows"]);
				$filename = "P&M Notification.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$rows = unserialize($this->request->data["rows"]);
				$this->set("rows",$rows);
				$this->render("maintainancenotificationpdf");
			}
		}
	}
	
	public function editmaintenancenotification($id)
	{
		$this->set("back","maintenancenotificationlist");
		$this->set("form_header","Edit P&M Notification");
		
		//Asset List
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_names =$asset_table->find()->group("asset_name");
		$this->set('asset_names',$asset_names);
		
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('project_data',$project_list);
		
		$erp_notification = TableRegistry::get('erp_maintainance_notification');
		$data = $erp_notification->get($id);
		$this->set("data",$data);
		
		if($this->request->is("post"))
		{
			$data = $this->request->data();
			
			$notification_tbl = TableRegistry::get("erp_maintainance_notification");
			$row = $notification_tbl->get($id);
			$post['asset_id'] = $data['asset_name'];
			$post["asset_code"] = $data['asset_code'];
			$post["asset_make"] = $data['asset_make'];
			$post["asset_capacity"] = $data['capacity'];
			$post["model_no"] = $data['model_no'];
			$post["identity"] = $data['vehicle_no'];
			$post["deploy_to"] = $data['deploy_to_project'];
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
			$row = $notification_tbl->patchEntity($row,$post);
			if($notification_tbl->save($row))
			{
				$this->Flash->success(__('Record Update Successfully', null), 
                            'default', 
                             array('class' => 'success'));
				echo "<script>window.close();</script>";
			}
		}
	}
	
	public function viewmaintainancenotification($id)
	{
		$this->set("back","maintenancenotificationlist");
		$this->set("form_header","View P&M Notification");
		
		//Asset List
		$asset_table = TableRegistry::get('erp_assets'); 
		$asset_names =$asset_table->find()->group("asset_name");
		$this->set('asset_names',$asset_names);
		
		//Projects List
		$project_table = TableRegistry::get('erp_projects'); 
		$project_list = $project_table->find();
		$this->set('project_data',$project_list);
		
		$erp_notification = TableRegistry::get('erp_maintainance_notification');
		$data = $erp_notification->get($id);
		$this->set("data",$data);
		
	}
	
	public function deletemaintainancenotification($id)
	{
		$erp_notification = TableRegistry::get('erp_maintainance_notification');
		$row = $erp_notification->get($id);
		if($erp_notification->delete($row))
		{
			$this->Flash->success(__('Record Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			return $this->redirect(['action'=>'maintenancenotificationlist']);
		}
		
	}
	
	public function storeissue()
	{	
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		
		$projects_ids = $this->Usermanage->users_project($this->user_id);		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$this->role);
		if($this->request->is("post"))
		{
			if(isset($this->request->data["export_csv"]) || isset($this->request->data["export_pdf"]))
			{
				$request = $this->request->data();
				$post = json_decode($request['export_filter_data']);
				$post = (array) $post;
				$or = array();				
								
				$or["erp_inventory_is.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				
				$or["erp_inventory_is.agency_name IN"] = (!empty($post["asset_name"]) && $post["asset_name"][0] != "All" )?$post["asset_name"]:NULL;
				
				$or["erp_inventory_is_detail.material_id IN"] = (!empty($post["material_name"]) && $post["material_name"][0] != "All" )?$post["material_name"]:NULL;
				
				if($or["erp_inventory_is.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1){  
						
							$or["erp_inventory_is.project_id IN"] = $projects_ids;
						
					}
				}
			
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_inventory_is.agency_name LIKE"] = "%asst_%";
				
				$is_tbl = TableRegistry::get('erp_inventory_is');
				$is_detail_tbl = TableRegistry::get('erp_inventory_is_detail');
				
				$result = $is_tbl->find()->select($is_tbl);
				$result = $result->innerjoin(
				["erp_inventory_is_detail"=>"erp_inventory_is_detail"],
				["erp_inventory_is.is_id = erp_inventory_is_detail.is_id"])
				->where($or)->select($is_detail_tbl)->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("Date of Issue","Asset ID","Asset Name","Capacity","Asset Make","Material Name","Quantity Issued","Unit");
				foreach($result as $data)
				{
					$data = array_merge($data,$data["erp_inventory_is_detail"]);
					$asset_data = explode("_",$data["agency_name"]);
					$asset_id = $asset_data[1];
					$csv = array();
					
					$csv[] = date("d-m-Y",strtotime($data['is_date']));
					$csv[] = $this->ERPfunction->get_asset_code($asset_id);
					$csv[] = $this->ERPfunction->get_asset_name($asset_id);
					$csv[] = $this->ERPfunction->get_asset_capacity($asset_id);	
					$csv[] = $this->ERPfunction->get_asset_make($asset_id);
					$csv[] = $this->ERPfunction->get_material_title($data["material_id"]);
					$csv[] = $data['quantity'];
					$csv[] = $this->ERPfunction->get_items_units($data['material_id']); 
					$rows[] = $csv;
				}
				
				if(isset($this->request->data["export_csv"]))
				{
					$filename = "storeIssueRecords.csv";
					$this->ERPfunction->export_to_csv($filename,$rows);
				}
				if(isset($this->request->data["export_pdf"]))
				{			
					require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
					$this->set("rows",$rows);
					$this->render("storeissuerecordspdf");
				}
			}
		}
	}
	
	public function exportassetissuedhistory()
	{
		$this->autoRender = false;
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize($this->request->data["issue_rows"]);
			$filename = "assetIssuedHistory.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{	
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize($this->request->data["issue_rows"]);
			$this->set("rows",$rows);
			$this->render("assetissuedhistorypdf");
		}
	}
	
	public function exportassetbookinghistory()
	{
		$this->autoRender = false;
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize($this->request->data["booking_rows"]);
			$filename = "assetBookingHistory.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{	
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize($this->request->data["booking_rows"]);
			$this->set("rows",$rows);
			$this->render("assetbookinghistorypdf");
		}
	}

	public function exportEquipmentownHistory()
	{
		$this->autoRender = false;
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize($this->request->data["efficiency_rows"]);
			$filename = "exportEquipmentownHistory.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{	
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize($this->request->data["efficiency_rows"]);
			$this->set("rows",$rows);
			$this->render("exportEquipmentownHistorypdf");
		}
	}

	
	public function exportassettransferhistory()
	{
		$this->autoRender = false;
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize($this->request->data["transfer_rows"]);
			$filename = "assetTransferHistory.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{	
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize($this->request->data["transfer_rows"]);
			$this->set("rows",$rows);
			$this->render("assettransferhistorypdf");
		}
	}
	
	public function exportassetsoldhistory()
	{
		$this->autoRender = false;
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize($this->request->data["sold_rows"]);
			$filename = "assetSoldHistory.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{	
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize($this->request->data["sold_rows"]);
			$this->set("rows",$rows);
			$this->render("assetsoldhistorypdf");
		}
	}
	
	public function exportassetthefthistory()
	{
		$this->autoRender = false;
		if(isset($this->request->data["export_csv"]))
		{
			$rows = unserialize($this->request->data["theft_rows"]);
			$filename = "assetTheftHistory.csv";
			$this->ERPfunction->export_to_csv($filename,$rows);
		}
		if(isset($this->request->data["export_pdf"]))
		{	
			require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
			$rows = unserialize($this->request->data["theft_rows"]);
			$this->set("rows",$rows);
			$this->render("assetthefthistorypdf");
		}
	}
	
	public function assetpo()
	{
		$erp_asset_po = TableRegistry::get('erp_asset_po');
		
		$erp_material = TableRegistry::get('erp_material'); 
		
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(['material_id IN'=>$material_ids,"material_code !="=>17]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id"=>0]);
		}
		$this->set('material_list',$material_list);
		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
				
		// $erp_agency = TableRegistry::get('erp_agency'); 
		// $agency_list = $erp_agency->find();
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
		$this->set("back","index");
		$this->set("controller","Asset");
		$this->set('form_header','PREPARE Asset PO');
		$this->set('button_text','Add Purchase Order');
		
		if($this->request->is('post'))
		{	
			$data = $this->request->data();
			$code = $this->ERPfunction->get_projectcode($data['project_id']);
			$new_pono = $this->ERPfunction->generate_auto_id($data['project_id'],"erp_asset_po","po_id","po_no");
			$new_pono = sprintf("%09d", $new_pono);
			$new_pono = $code.'/PO/AST/'.$new_pono;
			
			$this->request->data['po_no']=$new_pono;
			$this->request->data['po_purchase_type']="asset_po";
			$this->request->data['po_date']=$this->ERPfunction->set_date($this->request->data['po_date']);
			// $this->request->data['delivery_date']=$this->ERPfunction->set_date($this->request->data['delivery_date']);
			$this->request->data['taxes_duties']=isset($this->request->data['taxes_duties'])?$this->request->data['taxes_duties']:'0';
			$this->request->data['loading_transport']=isset($this->request->data['loading_transport'])?$this->request->data['loading_transport']:'0';
			$this->request->data['unloading']=isset($this->request->data['unloading'])?$this->request->data['unloading']:'0';
			$this->request->data['created_date']=date('Y-m-d H:i:s');			
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			// $this->request->data['custom_pan']=$this->request->data['custom_pan'];
			// $this->request->data['custom_gst']=$this->request->data['custom_gst'];
			$this->request->data['status']=1;
			
			if(!isset($this->request->data["warranty_check"]))
			{
				$this->request->data["warranty_check"] = "";
			}			
			
			$entity_data = $erp_asset_po->newEntity();			
			$post_data=$erp_asset_po->patchEntity($entity_data,$this->request->data);
			// debug($post_data);die;
			if($erp_asset_po->save($post_data))
			{
				$this->Flash->success(__('PO Created Successfully with PO No '.$new_pono, null), 
							'default', 
							array('class' => 'success'));
				$po_id = $post_data->po_id;
				
				$this->ERPfunction->add_asset_po_detail($this->request->data['material'],$po_id);			
			}
			$this->redirect(array("controller" => "purchase","action" => "index"));		
		}
	}
	
	public function assetpoalert()
    {
		$previous_url= $this->referer();
		
		if (strpos($previous_url, 'planning') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}else{
			$back_url = 'purchase';
			$back_page = 'index';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
		// $po_list = $this->Usermanage->fetch_approve_po($this->user_id);
		// $this->set('po_list',$po_list);
		$selected_project = isset($_REQUEST['selected_project'])?$_REQUEST['selected_project']:'';
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
			// $this->set('po_type',$request_data['po_type']);
			$this->set('show_data',1);
		}
		
    }
	
	public function editassetpo($po_id)
	{
		$this->set('selected_pl',true);
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find();
		$this->set('vendor_department',$vendor_department);
		$raise_from = $this->ERPfunction->get_mm_constructionmanager();	
		// $erp_agency = TableRegistry::get('erp_agency'); 
		// $agency_list = $erp_agency->find();
		// $this->set('agency_list',$agency_list);
		/* $projects = $this->Usermanage->access_project_ongoing($this->user_id); */
		$projects = $this->Usermanage->access_project($this->user_id);
		$this->set('projects',$projects);
		$this->set('raise_from',$raise_from);
		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);
		
		$user_action = 'edit';			
		// $user_data = $users_table->get($user_id);			
		// $this->set('user_data',$user_data);
		$this->set('user_action',$user_action);
		$this->set('form_header','Edit Asset Purchase Order (PO)');
		$this->set('button_text','Update Asset Purchase Order (PO)');	
		
		$erp_asset_po = TableRegistry::get('erp_asset_po'); 
		$erp_asset_po_detail = TableRegistry::get('erp_asset_po_detail'); 
		$erp_po_details = $erp_asset_po->get($po_id);
		$asset_name = $erp_po_details->asset_id;
		//var_dump($erp_po_details);
		$this->set('asset_name',$asset_name);
		$this->set('erp_po_details',$erp_po_details);
		$previw_list = $erp_asset_po_detail->find()->where(array('po_id'=>$po_id));
		$this->set('previw_list',$previw_list);   
		   
		$this->set('po_id',$po_id); 

		$data = $erp_asset_po_detail->find()->where(["po_id"=>$po_id,"approved"=>0])->hydrate(false)->toArray();	
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
				
				$mt = is_numeric($material['material_id'])?$this->ERPfunction->get_material_title
				($material['material_id']):$material['material_id'];
				
				$brnd = is_numeric($material['brand_id'])?$this->ERPfunction->get_brand_name($material["brand_id"]):$material["brand_id"];
				
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
					
					$b_row .= '<select class="select2 brand_id"  required="true"   name="material[brand_id][]" style="width:130px;" id="brand_id_'.$i.'" data-id='.$i.'>';
					$brands = $this->ERPfunction->get_brands_by_material_id($material["material_id"]);
													if($brands != "")
													{
														foreach($brands as $brand)
														{
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
							<td><input type="text" name="material[transportation][]" value="'.$material["transportation"].'" class="tx_count" id="tr_'.$i.'" data-id="'.$i.'" style="width:55px"></td>
							<td><input type="text" name="material[exice][]" value="'.$material["exice"].'" class="tx_count" id="ex_'.$i.'"  data-id="'.$i.'" style="width:55px"></td>
							<td><input type="text" name="material[other_tax][]" value="'.$material["other_tax"].'" class="tx_count" id="other_tax_'.$i.'"  data-id="'.$i.'" style="width:55px"></td>													
							<td><input type="text" name="material[amount][]" class="amount" value="'.$material["amount"].'" id="amount_'.$i.'" style="width:90px" /></td>
							<td><input type="text" name="material[single_amount][]" value="'.$material["single_amount"].'" id="single_amount_'.$i.'" style="width:90px"/></td>
							
							<input type="hidden" name="po_mid[]" value="'.$material["id"].'">
							<td><a href="#" class="btn btn-danger del_parent" data-id="'.$material["id"].'">Delete</a></td>
						</tr>';
						
					$i++;
			}
		}
		//debug($row);
		$this->set("row",$row);
		
		if($this->request->is('post'))
		{	
			// debug($this->request->data);die;
			$this->request->data['last_edit']=date('Y-m-d H:i:s');			
			$this->request->data['last_edit_by']=$this->request->session()->read('user_id');	
			$this->request->data['po_date']=date('Y-m-d',strtotime($this->request->data['po_date']));
			// $this->request->data['delivery_date']=date('Y-m-d',strtotime($this->request->data['delivery_date']));
			
			$this->request->data['taxes_duties']=isset($this->request->data['taxes_duties'])?$this->request->data['taxes_duties']:'0';
			$this->request->data['loading_transport']=isset($this->request->data['loading_transport'])?$this->request->data['loading_transport']:'0';
			$this->request->data['unloading']=isset($this->request->data['unloading'])?$this->request->data['unloading']:'0';
			
			$entity_data = $erp_asset_po->get($po_id);
			$po_type = $entity_data->po_purchase_type;
			$post_data=$erp_asset_po->patchEntity($entity_data,$this->request->data);
			if($erp_asset_po->save($post_data))
			{
				
				$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				$this->ERPfunction->edit_asset_po_detail($this->request->data['material'],$po_id,$po_type);			
			}
			//$this->redirect(array("controller" => "Inventory","action" => "approvepo"));
			 echo "<script>window.close();</script>";
		}
		
	}
	
	public function previewassetpo($po_id)
    {
		$erp_asset_po = TableRegistry::get('erp_asset_po'); 
		$erp_asset_po_details = TableRegistry::get('erp_asset_po_detail'); 
		$erp_po_details = $erp_asset_po->get($po_id);
		$this->set('erp_po_details',$erp_po_details);  
		$previw_list = $erp_asset_po_details->find()->where(array('po_id'=>$po_id));
		$this->set('previw_list',$previw_list);   
    }
	
	public function printassetporecord($eid,$mail = NULL)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$erp_asset_po = TableRegistry::get("erp_asset_po");
		$erp_asset_po_details = TableRegistry::get('erp_asset_po_detail');
		
		if($mail == "mail")
		{
			$previw_list = $erp_asset_po_details->find()->where(array('po_id'=>$eid,'currently_approved'=>1));
		}
		else
		{
			$previw_list = $erp_asset_po_details->find()->where(array('po_id'=>$eid));
		}
		
		$this->set('previw_list',$previw_list);
		$data = $erp_asset_po->get($eid);
		$this->set("data",$data->toArray());			
	}
	
	public function deleteassetpoalert($pom_id)
	{
		
		$erp_asset_po_detail = TableRegistry::get("erp_asset_po_detail");
		$data = $erp_asset_po_detail->get($pom_id);
		if($erp_asset_po_detail->delete($data)){
			$this->Flash->success(__('Asset P.O. Deleted Successfully.', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(["action"=>"assetpoalert"]);
		}
	}
	
	public function printassetporecordnorate($eid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$rmc_tbl = TableRegistry::get("erp_asset_po");
		$erp_inve_po_details = TableRegistry::get('erp_asset_po_detail');
		
		$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$eid,'currently_approved'=>1));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($eid);
		$this->set("data",$data->toArray());			
	}
	
	public function showinassetporecords()
	{
		$this->autoRender = false;
		$post = $this->request->data;
		// debug($post);die;
		$po_tbl = TableRegistry::get("erp_asset_po");
		$po_mtb = TableRegistry::get("erp_asset_po_detail");
		if(!empty($post["approved_list1"]) || !empty($post["verify_list"]) || (isset($post["approved_list"]) && !empty($post["approved_list"])) )
		{
		/* for first step approve code start */
		if(!empty($post["approved_list1"]))
		{
			foreach($post["approved_list1"] as $poid)
			{
				$po_no = $this->ERPfunction->get_assetpo_no_by_id($poid);
				
				if($po_no == $post["po"])
				{					
					$query = $po_mtb->query();
					$query = $query->update()->set(["first_approved"=>1,"first_approved_by"=>$this->user_id,"first_approved_date"=>date("Y-m-d")])->where(["po_id"=>$poid])->execute();
				}
			}
		}
		/* first step approve code end */
		
		/* for verify approve code start */
		if(!empty($post["verify_list"]))
		{
			foreach($post["verify_list"] as $poid)
			{
				$po_no = $this->ERPfunction->get_assetpo_no_by_id($poid);
				
				if($po_no == $post["po"])
				{					
					$query = $po_mtb->query();
					$query = $query->update()->set(["verified"=>1,"verified_by"=>$this->user_id,"verified_date"=>date("Y-m-d")])->where(["po_id"=>$poid])->execute();
				}
			}
		}
		/* first verify code end */
		
		if(isset($post["approved_list"]) && !empty($post["approved_list"]))
		{
		
		// $session = $this->request->session();
		// $session->write(["ids"=>$post['approved_list']]);
		// debug($post);die;
		$approved_id = array();
		foreach($post["approved_list"] as $poid)
		{
			// $po_no = $this->ERPfunction->get_po_no_by_id($poid);
			$po_record = $po_tbl->get($poid);
			$project_id = $po_record->project_id;
			$po_date = $po_record->po_date;
			$po_id = $poid;
			$po_no = $po_record->po_no;
			// var_dump($po_no);die;
			if($po_no == $post["po"])
			{
				$material_row = $po_mtb->find()->where(["po_id"=>$poid])->hydrate(false)->toArray();
				foreach($material_row as $m_row)
				{
					$approved_id[] = $m_row['id']; 
					$row = $po_mtb->get($m_row['id']);
					$mail_po_id = $row->po_id;
					$row->approved = 1;
					$row->currently_approved = 1;
					$row->approved_by = $this->user_id;
					$row->approved_date = date("Y-m-d");
					$po_mtb->save($row);
				}
			}
		}	 
		
		
		$mail_enable = $this->ERPfunction->get_assetpo_mail_status($mail_po_id);
		$email_list = $this->ERPfunction->get_mail_list_by_project_assetpo($project_id,$po_id,$mail_enable,'"assetpo_notification"');
		// debug($email_list);die;
		$emails = array();
		$emails_norate = array();
		//foreach($post["approved_list"] as $mid)
		// foreach($approved_id as $mid)
		// {
			$mm_email = $this->ERPfunction->get_email_of_mm_by_project($project_id);
			
			$emails_norate = array_merge($mm_email,$emails_norate);
			$mm_email = array_unique($emails_norate); /*remove duplicate email ids */
			$mm_email = array_filter($mm_email, function($value) { return $value !== ''; });
			$po_vendor_email = $this->ERPfunction->get_assetpo_vendor_id($po_id);
		// }
		
		// Check the vendor email format are correct or not? code start
		$email_correct = 1;
		$wrong_email = array();
		foreach($po_vendor_email as $value)
		{
			if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
			 
			} else {
				$email_correct = 0;
				$wrong_email[] = $value;
			}
		}
		// Check the all email format are correct or not? code end
		// debug($email_list);die;
		if($email_correct)
		{
		
			if(!empty($email_list))
			{
				$pdpmcm_email = implode(",",$email_list);		
				$view_po = $po_id;
				$this->ERPfunction->mail_assetpo_withrate($pdpmcm_email,$view_po,$po_no,$project_id,$po_date);
			}
			if($mail_enable!=0){
				if(!empty($mm_email))
				{
					$mm_email = implode(",",$mm_email);		
					$view_po = $po_id;		
					$this->ERPfunction->mail_assetpo_withoutrate($mm_email,$view_po,$po_no,$project_id,$po_date);
				}
			}
		}else{
			// Un approve before approved record if email format have problem
			foreach($approved_id as $mid)
			{
				$po_no = $this->ERPfunction->get_assetpo_no_by_detailid($mid);
				// var_dump($po_no);die;
				if($po_no == $post["po"])
				{
					$row = $po_mtb->get($mid);
					$row->approved = 0;
					$row->currently_approved = 0;
					$row->approved_by = 0;
					$row->approved_date = 0000-00-00;
					$po_mtb->save($row);
				}
			}
			// debug($wrong_email);die;
			$this->Flash->error(__('There is a problem with vendor email format', null), 
						'default', 
						array('class' => 'success'));

			$this->redirect(array("controller" => "assets","action" => "assetpoalert", '?' => array('selected_project' => $post['selected_project_id'])));
		}
		
		foreach($approved_id as $mid)
		{
			$row = $po_mtb->get($mid);
			$row->currently_approved = 0;
			$po_mtb->save($row);
		}
		
		}
		$this->redirect(array("controller" => "assets","action" => "assetpoalert", '?' => array(
        'selected_project' => $post['selected_project_id'])));
	}
		else
		{
			$this->Flash->error(__('Please select record', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(array("controller" => "assets","action" => "assetpoalert"));
		}
	}
	
	public function viewassetporecords($projects_id=null,$from=null,$to=null)
    {
		$previous_url= $this->referer();
		if (strpos($previous_url, 'planningmenu') !== false) {
			$back_url = 'contract';
			$back_page = 'planningmenu';
		}elseif (strpos($previous_url, 'purchase') !== false) {
			$back_url = 'purchase';
			$back_page = 'index';
		}else{
			$back_url = 'assets';
			$back_page = 'index';
		}
		$this->set('back_url',$back_url);
		$this->set('back_page',$back_page);
		
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
		$this->set("projects_id",$projects_id);
		$this->set("from",$from);
		$this->set("to",$to);
				
		if($this->request->is('post'))
		{	
			if(isset($this->request->data["export_csv"]))
			{
				$erp_asset_po = TableRegistry::get("erp_asset_po");
				$erp_asset_po_detail = TableRegistry::get("erp_asset_po_detail");
				// debug($this->request->data);die;
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_material_id"] = (!empty($post["e_material_id"]))?explode(",",$post["e_material_id"]):NULL;
				$post["e_brand_id"] = (!empty($post["e_brand_id"]))?explode(",",$post["e_brand_id"]):NULL;
				$post["e_vendor_userid"] = (!empty($post["e_vendor_userid"]))?explode(",",$post["e_vendor_userid"]):NULL;
				
				$or["erp_asset_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_asset_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_asset_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All")?$post["e_brand_id"]:NULL;
				$or["erp_asset_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_asset_po.po_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_asset_po.po_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_asset_po.po_no ="] = (!empty($post["e_po_no"]))?$post["e_po_no"]:NULL;
				$or["erp_asset_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				// $or["erp_asset_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				
				if($or["erp_asset_po.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_asset_po.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_asset_po_detail.approved !="] = 0;
				
				$result = $erp_asset_po->find()->select($erp_asset_po);
				$result = $result->innerjoin(
					["erp_asset_po_detail"=>"erp_asset_po_detail"],
					["erp_asset_po.po_id = erp_asset_po_detail.po_id"])
					->where($or)->select($erp_asset_po_detail)->order(['erp_asset_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","Final Rate","Amount");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_asset_po_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_asset_po_detail"]);
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
					$rows[] = $csv;
				}
				
				$filename = "po_records.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
			
			if(isset($this->request->data["export_pdf"]))
			{			
				require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
				$erp_asset_po = TableRegistry::get("erp_asset_po");
				$erp_asset_po_detail = TableRegistry::get("erp_asset_po_detail");
				// debug($this->request->data);die;
				// $rows = unserialize(base64_decode($this->request->data["rows"]));
				$post = $this->request->data;	
				$or = array();
				$post["e_pro_id"] = (!empty($post["e_pro_id"]))?explode(",",$post["e_pro_id"]):NULL;
				$post["e_material_id"] = (!empty($post["e_material_id"]))?explode(",",$post["e_material_id"]):NULL;
				$post["e_brand_id"] = (!empty($post["e_brand_id"]))?explode(",",$post["e_brand_id"]):NULL;
				$post["e_vendor_userid"] = (!empty($post["e_vendor_userid"]))?explode(",",$post["e_vendor_userid"]):NULL;
				
				$or["erp_asset_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All" )?$post["e_pro_id"]:NULL;
				$or["erp_asset_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All")?$post["e_material_id"]:NULL;
				$or["erp_asset_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All")?$post["e_brand_id"]:NULL;
				$or["erp_asset_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All")?$post["e_vendor_userid"]:NULL;
				$or["erp_asset_po.po_date >="] = ($post["e_date_from"] != "")?date("Y-m-d",strtotime($post["e_date_from"])):NULL;
				$or["erp_asset_po.po_date <="] = ($post["e_date_to"] != "")?date("Y-m-d",strtotime($post["e_date_to"])):NULL;
				$or["erp_asset_po.po_no ="] = (!empty($post["e_po_no"]))?$post["e_po_no"]:NULL;
				$or["erp_asset_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				// $or["erp_asset_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All")?$post["e_po_type"]:NULL;
				
				if($or["erp_asset_po.project_id IN"] == NULL)
				{
					if($this->Usermanage->project_alloted($role)==1)
					{
						$or["erp_asset_po.project_id IN"] = implode(",",$projects_ids);
					}
				}
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or["erp_asset_po_detail.approved !="] = 0;
				
				$result = $erp_asset_po->find()->select($erp_asset_po);
				$result = $result->innerjoin(
					["erp_asset_po_detail"=>"erp_asset_po_detail"],
					["erp_asset_po.po_id = erp_asset_po_detail.po_id"])
					->where($or)->select($erp_asset_po_detail)->order(['erp_asset_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				
				$rows = array();
				$rows[] = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","Final Rate","Amount");
			
				foreach($result as $retrive_data)
				{				
					if(isset($retrive_data["erp_asset_po_detail"]))
					{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_asset_po_detail"]);
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
	
	public function cancelassetpo($po_id = null)
	{
		
		$pom_tbl = TableRegistry::get("erp_asset_po_detail");		
		$po_tbl = TableRegistry::get("erp_asset_po");		
		
		if($po_id != null)
		{
			$get_deleted_po = $po_tbl->get($po_id);
			$deleted_po = $get_deleted_po->toArray();
			$mail_check = $deleted_po["mail_check"];
			$del_po_project_id = $deleted_po["project_id"];
			$del_po_no = $deleted_po["po_no"];
			$del_po_project_name = $this->ERPfunction->get_projectname($deleted_po["project_id"]);
			$del_po_party_name = $this->ERPfunction->get_vendor_name($deleted_po["vendor_userid"]);
			
			$pdpmcm_email = $this->ERPfunction->get_email_of_pd_pm_cm_by_project_assetpo($del_po_project_id,$po_id);	
			$mm_email = $this->ERPfunction->get_email_of_mm_by_project($del_po_project_id);
			
			$po_detail = $pom_tbl->find("all")->where(["po_id"=>$po_id,"approved"=>1]);
			
			//Check if this po pr is pending or not
					// $have_pending = 0;
					// foreach($po_detail as $rows)
					// {
						// if($rows["pr_mid"] != 0)
						// {
							// $prm_id = $rows["pr_mid"];
							// $prm_detail = $prm_tbl->get($prm_id);
							// if($prm_detail->po_completed != 0)
							// {
								// $have_pending = 1;
							// }
						// }
					// }
					//If the po have pr material it has pending pr then show entry in purchase pr alert
					
					// if($have_pending)
					// {
						// foreach($po_detail as $rows1)
						// {
							
							// if($rows1["pr_mid"] != 0)
							// {
								// $prm_id = $rows1["pr_mid"];
								// $pr_tbl = TableRegistry::get("erp_inventory_pr_material");
								// $prm_detail = $pr_tbl->get($rows1["pr_mid"]);
								//set po quantity in pr material po approved quentity
								// $prm_detail->po_approved_quantity = $rows1["quantity"];
								//set pr actual quantity po created quentity + pending pr quantity
								// $prm_detail->quantity = $rows1["quantity"] + $prm_detail->po_pending_quantity;
								//set po_completed so it show in purchase pr alert
								// $prm_detail->po_completed = 3;
								//set approved so it show in purchase pr alert
								// $prm_detail->approved = 0;
								// $pr_tbl->save($prm_detail);
							// }
						// }
					// }
			if(!empty($po_detail))
			{
				$query = $pom_tbl->query();
					$query->update()
					->set(['approved'=>0,
					"approved_by"=>"",
					"approved_date"=>""])
					->where(['po_id' => $po_id])
					->execute();
					
				
				
				$get_deleted_po = $po_tbl->get($po_id);
				// $deleted_po = $get_deleted_po->toArray();
				// $deleted_po["deleted_by"] = $this->user_id;
				// $deleted_po = $delpo_tbl->newEntity($deleted_po);
				// $delpo_tbl->save($deleted_po);
				
				// $deleted_details = $po_detail->hydrate(false)->toArray();				
				// foreach($deleted_details as $copy)
				// {
					// $save_d_r = $delpom_tbl->newEntity($copy);
					// $delpom_tbl->save($save_d_r);
				// }
				
				// $pom_tbl->deleteAll(["po_id"=>$po_id,"approved"=>1]);
				
				// $count = $pom_tbl->find("all")->where(["po_id"=>$po_id])->count();
				// if($count == 0) /* Make Sure ALL PO Materials are deleted before deleting po */
				// {
					// $ok = $po_tbl->delete($get_deleted_po);	
				// }
							
				
				// foreach($po_detail as $rows)
				// {
					// if($rows["pr_mid"] != 0)
					// {
						// $prm_id = $rows["pr_mid"];
						// $prm_detail = $prm_tbl->get($prm_id);
						// $prm_detail->approved = 0;
						// $prm_detail->approved_by = 0;
						// $prm_detail->approved_date = null;
						// $prm_tbl->save($prm_detail);
					// }
				// }
				
				 if($query)
				 {
					$projectdetail = TableRegistry::get('erp_projects'); 
					$project_data = $projectdetail->get($del_po_project_id);
					$code = $project_data->project_code;
					 
					$new_pono = $this->ERPfunction->generate_auto_id($del_po_project_id,"erp_asset_po","po_id","po_no");
					$new_pono = sprintf("%09d", $new_pono);
					$new_pono = $code.'/PO/AST/'.$new_pono;
					
					$update_data['po_no'] = $new_pono;
					$data = $po_tbl->patchEntity($get_deleted_po,$update_data);
					$po_tbl->save($data);
					if($mail_check == 1)
					{
						$emails1 = array();
						$emails2 = array();
						// $project_wise_role = ['deputymanagerelectric'];
						// $project_email = $this->ERPfunction->get_email_id_by_project_from_user($del_po_project_id,$project_wise_role);
						// $emails1 = array_merge($emails1,$project_email);
					
						$emails1 = array_merge($pdpmcm_email,$emails1);
						$emails2 = array_merge($mm_email,$emails2);
						$role = ['erphead','erpmanager','erpoperator','ceo'];
						$erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
						$emails2 = array_merge($erp_email,$emails2);
						$pdpmcm_email = array_unique($emails1); /*remove duplicate email ids */		
						$mm_email = array_unique($emails2); /*remove duplicate email ids */
						$pdpmcm_email = array_filter($pdpmcm_email, function($value) { return $value !== ''; });
						$mm_email = array_filter($mm_email, function($value) { return $value !== ''; });
						
						$all_users = array_unique(array_merge($pdpmcm_email,$mm_email));
					}
					elseif($mail_check == 2)
					{
						$emails1 = array();
						$emails2 = array();
						$project_wise_role = ['deputymanagerelectric'];
						$project_email = $this->ERPfunction->get_email_id_by_project_from_user($del_po_project_id,$project_wise_role);
						$emails1 = array_merge($emails1,$project_email);
					
						$emails1 = array_merge($pdpmcm_email,$emails1);
						$emails2 = array_merge($mm_email,$emails2);
						$role = ['erphead','erpmanager','erpoperator','ceo'];
						$erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
						$emails2 = array_merge($erp_email,$emails2);
						$pdpmcm_email = array_unique($emails1); /*remove duplicate email ids */		
						$mm_email = array_unique($emails2); /*remove duplicate email ids */
						$pdpmcm_email = array_filter($pdpmcm_email, function($value) { return $value !== ''; });
						$mm_email = array_filter($mm_email, function($value) { return $value !== ''; });
						
						$all_users = array_unique(array_merge($pdpmcm_email,$mm_email));
					}else{
						$emails = array();
						$role = ['erphead','erpmanager','purchasehead','purchasemanager','md','erpoperator','ceo'];
						$erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
						$emails = array_merge($erp_email,$emails);
						$emails = array_unique($emails); /*remove duplicate email ids */
						$all_users = array_filter($emails, function($value) { return $value !== ''; });
					}
					
					// debug($all_users);die;
					if(!empty($all_users))
					{
						$all_users = implode(",",$all_users);		
						$this->ERPfunction->cancel_assetpo_mail($all_users,$del_po_no,$del_po_project_name,$del_po_party_name);
					}
					
					// if(!empty($pdpmcm_email))
					// {
						// $pdpmcm_email = implode(",",$pdpmcm_email);		
						// $this->ERPfunction->cancel_po_mail($pdpmcm_email,$del_po_no,$del_po_project_name,$del_po_party_name);
					// }
					
					// if(!empty($mm_email))
					// {
						// $mm_email = implode(",",$mm_email);				
						 // $this->ERPfunction->cancel_po_mail($mm_email,$del_po_no,$del_po_project_name,$del_po_party_name);
					// }
				}
			}	
			
			
			 $this->Flash->success(__('P.O. Cancelled Successfully.Record will show in P.O. Alert page.', null), 
							 'default', 
							 array('class' => 'success'));
			 $this->redirect(["action"=>"viewassetporecords"]);
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
