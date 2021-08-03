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
use Cake\Routing\Router;
use Cake\Auth\DefaultPasswordHasher;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class UsermanageController extends AppController
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
		$this->rights = $this->Usermanage->usermanage_access_right();
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$action = $this->request->action;
		if(isset($this->rights[$action][$this->role]))
		{
			$is_capable = $this->rights[$action][$this->role];	
		}
		else
			$is_capable = 0;	
		
		$this->set('is_capable',$is_capable);
		$this->set('role',$this->role);
	}
    public function index()
    {
		
    }
	
	public function userlist()
    { 
		$users_table = TableRegistry::get('erp_users'); 
		$user_list = $users_table->find()->where(['status'=>1,'employee_no'=>'']);
		// $user_list = $users_table->find();
		$this->set('user_list',$user_list);
    }
	
	public function view()
    {
		$designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations);
		
		$user = $this->request->session()->read('user_id');
		//var_dump($user);die;
		$role = $this->Usermanage->get_user_role($user);
		$projects_ids = $this->Usermanage->users_project($user);
		
		$projects = $this->Usermanage->all_access_project($this->user_id);
		$this->set('projects',$projects);
		
		$users_table = TableRegistry::get('erp_users'); 
		$user_list = $users_table->find('all');
		// echo $user_list;die;
		$this->set('user_list',$user_list);
		
		
		
		if($this->request->is("post"))
		{	
		
			if(isset($this->request->data['go']))
			{			
				$erp_users = TableRegistry::get("erp_users");
				$erp_projects_assign = TableRegistry::get("erp_projects_assign");
				$post = $this->request->data;	
				$or = array();				
				
				$or["erp_projects_assign.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All" )?$post["project_id"]:NULL;
				$or["erp_users.role IN"] = (!empty($post["role"]) && $post["role"][0] != "All")?$post["role"]:NULL;
				$or["erp_users.username"] = (!empty($post["user_name"]))?$post["user_name"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				if($post["status"] == "active")
				{
					$or["erp_users.status"] = 1;
				}
				else if($post["status"] == "local")
				{
					$or["erp_users.status"] = 0;
					
				}
				// debug($post);
				// debug($or);die;
				
				/* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
				if($role =='projectdirector')
				{
					if(!empty($projects_ids))
					{
						$result = $erp_users->find()->select($erp_users)->where(['employee_no'=>'']);
						$result = $result->innerjoin(
							["erp_projects_assign"=>"erp_projects_assign"],
							["erp_users.user_id = erp_projects_assign.user_id","erp_projects_assign.project_id in"=>$projects_ids,'erp_users.employee_no'=>''])
							->where($or)->select($erp_projects_assign)->hydrate(false)->toArray();
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
					$result = $erp_users->find()->select($erp_users)->where(['employee_no'=>'']);
						$result = $result->innerjoin(
							["erp_projects_assign"=>"erp_projects_assign"],
							["erp_users.user_id = erp_projects_assign.user_id"])
							->where($or)->select($erp_projects_assign)->hydrate(false)->toArray();
							//var_dump($result);die;
						
				}
				//var_dump($result);die;
				$this->set('user_list',$result);
			}
		}
    }
	
	public function add($user_id=Null)
    {
		$users_table = TableRegistry::get('erp_users'); 
		$user_identy_id = $this->ERPfunction->generate_autoid('CM-');
		$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $erp_projects->find();
		$this->set('projects',$projects);
		$designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations);
		if(isset($user_id))
		{			
			$user_action = 'edit';
			
			$user_data = $users_table->get($user_id);
			$assign_projects = $this->ERPfunction->old_project($user_id);
			
			$this->set('user_data',$user_data);
			$this->set('assign_project',$assign_projects);
			$this->set('form_header','Edit User');
			$this->set('button_text','Update User');			
			
		}
		else
		{
			$user_action = 'insert';
			$this->set('user_identy_id',$user_identy_id);
			$this->set('form_header','Add User');
			$this->set('button_text','Add User');
		}
		
		
		$this->set('user_action',$user_action);
		
		
		if($this->request->is('post'))
		{	$this->set('user_data',$this->request->data);
	
			
			/* $this->request->data['date_of_birth']=date('Y-m-d',strtotime($this->request->data['date_of_birth'])); */
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			/* $this->request->data['status']=1; */
			$this->request->data['active_status']=1;
			/* $image=$this->ERPfunction->upload_image('image_url',$this->request->data['old_image']); */
			/* $this->request->data['image_url']=$image; */
			
			if($user_action == 'edit')
			{
				if(isset($_POST['password']) && $_POST['password']!="")
				{
					$newPassword = $_POST['password'];

					// Validate password strength
					$uppercase = preg_match('@[A-Z]@', $newPassword);
					$lowercase = preg_match('@[a-z]@', $newPassword);
					$number    = preg_match('@[0-9]@', $newPassword);
					$specialChars = preg_match('@[^\w]@', $newPassword);

					if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newPassword) < 8) {
						$this->Flash->error(__('Sorry! Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.'));
						return $this->redirect(["action"=>"add",$user_id]);
					}else{
						$hasher = new DefaultPasswordHasher();
						$hashedPassword = $hasher->hash($newPassword);
						$this->request->data['password']= $hashedPassword;
					}
				}
				else{
					unset($this->request->data['password']);
				}
				$post_data = $this->request->data;
				$user_data = $users_table->patchEntity($user_data,$post_data);
				if($users_table->save($user_data))
				{
					$this->Flash->success(__('User Record Updated Successfully', null), 
							'default', 
							array('class' => 'success'));
				$user_id = $user_data->user_id;
				}
				$constructor_id = $user_id;
				$old_projects = $this->ERPfunction->old_project($constructor_id);
				
				if(isset($this->request->data['assign_projects']) && !empty($this->request->data['assign_projects']))
					$this->ERPfunction->assign_project($constructor_id,$this->request->data['assign_projects']);
				else
				{
					if(!empty($old_projects))
					{
						foreach($old_projects as $project_id)
						{				
							$this->ERPfunction->delete_project($constructor_id,$project_id);		
						}
					}
				}
				$this->redirect(array("controller" => "Usermanage","action" => "index"));	
			}
			else
			{
				$check_email = $users_table->find()->where(['email_id'=>$this->request->data['email_id'],'employee_no'=>'']);		
				if(!$check_email->isEmpty())
				{
					$this->Flash->success(__('Duplicate Email id', null), 
							'default', 
							array('class' => 'success'));
						
				}
				else
				{
					$newPassword = $_POST['password'];

					// Validate password strength
					$uppercase = preg_match('@[A-Z]@', $newPassword);
					$lowercase = preg_match('@[a-z]@', $newPassword);
					$number    = preg_match('@[0-9]@', $newPassword);
					$specialChars = preg_match('@[^\w]@', $newPassword);

					if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newPassword) < 8) {
						$this->Flash->error(__('Sorry! Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.'));
						return $this->redirect(["action"=>"add"]);
					}else{
						$hasher = new DefaultPasswordHasher();
						$hashedPassword = $hasher->hash($newPassword);
						$this->request->data['password']= $hashedPassword;
					}

					$user_field = $users_table->newEntity();	
					$this->request->data['status']=1;
					$user_field=$users_table->patchEntity($user_field,$this->request->data);
					if($users_table->save($user_field))
					{
						$this->Flash->success(__('User Added Successfully', null), 
									'default', 
									array('class' => 'success'));
						$constructor_id = $user_field->user_id;
						if(isset($this->request->data['assign_projects']) && !empty($this->request->data['assign_projects']))
							$this->ERPfunction->assign_project($constructor_id,$this->request->data['assign_projects']);
					}
				}
				$this->redirect(array("controller" => "Usermanage","action" => "add"));	
			}
				
		}		
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
	
	public function remove($user_id)
	{
		$users_table = TableRegistry::get('erp_users'); 		
		
		$user_data =$users_table->get($user_id);
		$this->request->data['status'] =0;
		$this->request->data['remove_date']=date('Y-m-d H:i:s');
		$this->request->data['remove_by']=$this->request->session()->read('user_id');
		$post_data = $this->request->data;
		$user_data = $users_table->patchEntity($user_data,$post_data);
		if($users_table->save($user_data))
		{
			$this->Flash->success(__('Record Remove Successfully', null), 
							'default', 
							array('class' => 'success'));
		}
		return $this->redirect(['action'=>'index']);
	}
	
	public function openingstock($project_id = Null)
    {
		$erp_project_opening_stock = TableRegistry::get('erp_project_opening_stock'); 
		$erp_stock_history = TableRegistry::get('erp_stock_history'); 
		
		$erp_projects = TableRegistry::get('erp_projects'); 
		$result = $erp_projects->find();
		$this->set('projects',$result);
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id"=>0]);
		$this->set('material_list',$material_list);	
		
		$data = $erp_stock_history->find()->where(["project_id"=>$project_id,'type'=>'os'])->hydrate(false)->toArray();	
		//debug($data);
		$i = 0;
		$row='';
		if(!empty($data))
		{
			foreach($data as $material)
			{
				//$po_id = $post["selected_po_id_{$material['id']}"];
				//$pr_id = $po_tbl->find()->where(["po_id"=>$post["selected_po_id_{$material['id']}"]])->select(["pr_id"])->hydrate(false)->toArray();
				$row .= '<tr class="cpy_row">
							<td>'.$this->ERPfunction->get_material_item_code_bymaterialid($material['material_id']).'</td>
							<td>'.$this->ERPfunction->get_material_title($material['material_id']).'</td>
							<input type="hidden" name="material[material_id][]" value="'.$material["material_id"].'" id="material_id_'.$i.'"/>
							<td><input type="text" name="material[quantity][]" value="'.$material["quantity"].'" id="quantity_'.$i.'"/></td>
							
							<td><input type="hidden" name="material[unit][]" value="'.$this->ERPfunction->get_items_units($material['material_id']).'" id="brand_id_'.$i.'"/>'
							.$this->ERPfunction->get_items_units($material["material_id"]).'</td>
			
							<input type="hidden" name="old_material_id[]" value="'.$material["material_id"].'">
							<td><input type="text" name="material[note][]" value="'.$material["note"].'"  id="note_'.$i.'"/></td>
							<td><input type="button" name="material[button][]" class="btn btn-danger" value="Delete" id="button_'.$i.'" style="color:#FFFFFF;"/></td>
						</tr>';
						
					$i++;
			}
		}
		//debug($row);
		$this->set("row",$row);
		
		if(isset($project_id))
		{			
			$user_action = 'edit';			
			$project_data = $erp_projects->get($project_id);			
			$this->set('project_data',$project_data);
			$this->set('form_header','Add Opening Stock');
			$this->set('button_text','Update Inventory');
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
			$opening_stock_data = $erp_project_opening_stock->find()->where(["project_id"=>$project_id])->hydrate(false)->toArray();	
			
			$material_items = $this->request->data('material');
			$old_material_items = $this->request->data('old_material_id');
			// debug($material_items);
			// debug($opening_stock_data);
			// debug($old_material_items);die;
			$this->ERPfunction->get_opening_stock_data($material_items,$project_id);
			$this->Flash->success(__('Record Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(array("controller" => "Usermanage","action" => "viewprojectlist"));
		}
	}
	
	public function printos($pid)
	{
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		
		$erp_stock_history = TableRegistry::get('erp_stock_history');
		$previw_list = $erp_stock_history->find()->where(["project_id"=>$pid,'type'=>'os'])->hydrate(false)->toArray();
		// var_dump();die;
		$this->set('previw_list',$previw_list); 
		$this->set("project_id",$pid);			
	}
	
	public function viewprojectlist()
	{
		$erp_projects = TableRegistry::get('erp_projects'); 
		$result = $erp_projects->find()->where(['project_status'=>'On Going']);
		$this->set('projects',$result);
		
		$projects_list = $this->Usermanage->access_project($this->user_id);
		$this->set('projects_list',$projects_list);

	}
	
	public function filemanagerbackup()
	{
		$baseurl = Router::url( $this->here, true );
		$projects = $this->Usermanage->access_project($this->user_id);	
		$this->set('projects',$projects);
		$location = "";
		$this->set('role',$this->role);
		$this->set('location',$location);
		$this->set('baseurl',$baseurl);
		if($this->request->is("post"))
		{
			// debug($this->request->data);die;
			if($this->request->data["searchbyproject"] == "Search")
			{
				$module = $this->request->data['module_name'];
				$project_name = ($this->request->data["project_id"] != '')?$this->ERPfunction->get_projectname($this->request->data["project_id"]):'';
				$this->set('location',$module."%2F".$project_name);
			}
		}
	}

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
	
}
