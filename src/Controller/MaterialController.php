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
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class MaterialController extends AppController
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
	}
    public function index()
    {
		$users_table = TableRegistry::get('erp_users'); 
		$user_list = $users_table->find()->where(array('role'=>'ceo'));
		$this->set('user_list',$user_list);
    }
	public function category()
    {
		//$category = $this->ERPfunction->material_category();
		$erp_material_cats = TableRegistry::get('erp_material_cat'); 
		$category = $erp_material_cats->find();
		$this->set('category',$category);
    }
	public function addmaterial($material_id=Null)
    {
		$erp_material = TableRegistry::get('erp_material'); 
		$category = $this->ERPfunction->material_category();
		$this->set('category',$category);
		 $table_category=TableRegistry::get('erp_category_master');
		$unit_list=$table_category->find()->where(array('type'=>'unit'));
		$this->set('unitlist',$unit_list);
		
		$material_item_code = $this->ERPfunction->generate_autoid('MT-');
		if(isset($material_id))
		{
			
			$user_action = 'edit';
			
			$material_data = $erp_material->get($material_id);
			
			$this->set('material_data',$material_data);
			$this->set('form_header','Edit Material');
			$this->set('button_text','Update Material');
			
		}
		else
		{
			$user_action = 'insert';
			$this->set('material_item_code',$material_item_code);
			$this->set('form_header','Add Material');
			$this->set('button_text','Add Material');
		}	
		$this->set('user_action',$user_action);
		if($this->request->is('post'))
		{
			if($user_action == 'edit')
			{
				$updated_data = $this->request->data;
					$material_data = $erp_material->patchEntity($material_data,$updated_data);
					if($erp_material->save($material_data))
					{
					$this->Flash->success(__('Record Update Successfully', null), 
								'default', 
								array('class' => 'success'));
					
					}
					$this->redirect(array("controller" => "Material","action" => "viewmaterial"));	
			}
			else
			{
				$table_field = $erp_material->newEntity();	
				$this->request->data['created_date']=date('Y-m-d H:i:s');
				$this->request->data['created_by']=$this->request->session()->read('user_id');
				$this->request->data['status']=1;			
						
				$new_data=$erp_material->patchEntity($table_field,$this->request->data);
				if($erp_material->save($new_data))
				{
					$this->Flash->success(__('Record Insert Successfully', null), 
								'default', 
								array('class' => 'success'));
				}
				
				$this->redirect(array("controller" => "Material","action" => "viewmaterial"));	
			}
		}
		
    }
	 public function viewmaterial($material_code=NULL)
    {
		
		$erp_material = TableRegistry::get('erp_material'); 
		
		if($material_code){
			$erp_material_cats = TableRegistry::get('erp_material_cat'); 
			$material_data = $erp_material_cats->get($material_code);
			$material_list = $erp_material->find()->where(['material_code' => $material_code]);
		}else{
			$material_list = $erp_material->find();
		} 
		$this->set('material_list',$material_list);
		
		$category = $this->ERPfunction->material_category();
		$this->set('category',$category);
    }
	public function deletematerial($material_id)
	{
		
		$erp_material = TableRegistry::get('erp_material'); 
		$row_delte=$erp_material->get($material_id);
		if($erp_material->delete($row_delte)){
			$this->Flash->success(__('Record Successfully Deleted'));
			return $this->redirect(['controller'=>'Material','action'=>'viewmaterial']);
		}
	}
	public function addbrand($brand_id = NULL)
    {
		$erp_material_brand = TableRegistry::get('erp_material_brand'); 		
		$category = $this->ERPfunction->material_category();
		$this->set('category',$category);
		if(isset($brand_id))
		{
			
			$user_action = 'edit';
			
			$brand_data = $erp_material_brand->get($brand_id);
			
			$this->set('brand_data',$brand_data);
			$this->set('form_header','Edit Brand');
			$this->set('button_text','Update Brand');
			
		}
		else
		{
			$user_action = 'insert';
			
			$this->set('form_header','Add Brand');
			$this->set('button_text','Add Brand');
		}		
		$this->set('user_action',$user_action);
		if($this->request->is('post'))
		{
			if($user_action == 'edit')
			{
				$updated_data = $this->request->data;
					$brand_data = $erp_material_brand->patchEntity($brand_data,$updated_data);
					if($erp_material_brand->save($brand_data))
					{
					$this->Flash->success(__('Record Update Successfully', null), 
								'default', 
								array('class' => 'success'));
					
					}
					$this->redirect(array("controller" => "Material","action" => "viewbrand"));	
			}
			else
			{
				$table_field = $erp_material_brand->newEntity();	
				$this->request->data['status']=1;			
				$new_data=$erp_material_brand->patchEntity($table_field,$this->request->data);
				if($erp_material_brand->save($new_data))
				{
					$this->Flash->success(__('Record Insert Successfully', null), 
								'default', 
								array('class' => 'success'));
				}
				
				$this->redirect(array("controller" => "Material","action" => "viewbrand"));	
			}
		}
				
    }	
	public function brandlist($brand_id = null)
	{
		if($brand_id)
		{
			$erp_material_brand = TableRegistry::get('erp_material_brand'); 		
		$brand_list = $erp_material_brand->find()->where(array('material_type'=>$brand_id));
		$this->set('brand_list',$brand_list);		
		}
		else
		{
			$erp_material_brand = TableRegistry::get('erp_material_brand'); 		
			$brand_list = $erp_material_brand->find();
			$this->set('brand_list',$brand_list);
		}		
		$category = $this->ERPfunction->material_category();
		$this->set('category',$category);
		
	}
	public function viewbrand($brand_id = null)
	{
		if($brand_id)
		{
			$erp_material_brand = TableRegistry::get('erp_material_brand'); 		
		$brand_list = $erp_material_brand->find()->where(array('material_type'=>$brand_id));
		$this->set('brand_list',$brand_list);		
		}
		else
		{
			$erp_material_brand = TableRegistry::get('erp_material_brand'); 		
			$brand_list = $erp_material_brand->find();
			$this->set('brand_list',$brand_list);
		}		
		$category = $this->ERPfunction->material_category();
		$this->set('category',$category);
		
	}
	public function deletebrand($id){
			$this->request->is(['post','delete']);
			$erp_material_brand = TableRegistry::get('erp_material_brand'); 
			$row_delte=$erp_material_brand->get($id);
			if($erp_material_brand->delete($row_delte)){
			$this->Flash->success(__('Record Successfully Deleted'));
			return $this->redirect(['controller'=>'Material','action'=>'viewbrand']);
		}
    }
	
	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}
