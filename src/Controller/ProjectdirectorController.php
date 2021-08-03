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
class ProjectdirectorController extends AppController
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
		$user_list = $users_table->find()->where(array('role'=>'projectdirector'));
		$this->set('user_list',$user_list);
    }
	public function add($user_id=Null)
    {
		$users_table = TableRegistry::get('erp_users'); 
		$user_identy_id = $this->ERPfunction->generate_autoid('PD-');
		if(isset($user_id))
		{
			
			$user_action = 'edit';
			
			$user_data = $users_table->get($user_id);
			
			$this->set('user_data',$user_data);
			$this->set('form_header','Edit Project Director');
			$this->set('button_text','Update Project Director');
			
		}
		else
		{
			$user_action = 'insert';
			$this->set('user_identy_id',$user_identy_id);
			$this->set('form_header','Add Project Director');
			$this->set('button_text','Add Project Director');
		}
		
		
		$this->set('user_action',$user_action);
		
		
		if($this->request->is('post'))
		{	$this->set('user_data',$this->request->data);
	
			
			$this->request->data['date_of_birth']=date('Y-m-d',strtotime($this->request->data['date_of_birth']));
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;
			$image=$this->ERPfunction->upload_image('image_url',$this->request->data['old_image']);
			$this->request->data['image_url']=$image;
			
			if($user_action == 'edit')
			{
				if(isset($_POST['password']) && $_POST['password']!="")
				$this->request->data['password']= md5($_POST['password']);
				$post_data = $this->request->data;
				$user_data = $users_table->patchEntity($user_data,$post_data);
				if($users_table->save($user_data))
				{
					$this->Flash->success(__('Record Update Successfully', null), 
							'default', 
							array('class' => 'success'));
				
				}
				
			}
			else
			{
				$check_email = $users_table->find()->where(['email_id'=>$this->request->data['email_id']]);		
				if(!$check_email->isEmpty())
				{
					$this->Flash->success(__('Dublicate Email id', null), 
							'default', 
							array('class' => 'success'));
						
				}
				else{
			$user_field = $users_table->newEntity();
			
			$user_field=$users_table->patchEntity($user_field,$this->request->data);
			if($users_table->save($user_field))
			{
				$this->Flash->success(__('Project Director Insert Successfully', null), 
							'default', 
							array('class' => 'success'));
				}
				}
			}
			$this->redirect(array("controller" => "Projectdirector","action" => "index"));		
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

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}
