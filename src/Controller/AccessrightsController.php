<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\Mailer\Email;
use mPDF;
use Cake\Datasource\ConnectionManager;

class AccessrightsController extends AppController
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
		$this->set('role',$this->role);
   }
	
	public function index()
    {
				
    }
	
	public function rights($accessrole=null,$modulename=null)
    {
		$conn = ConnectionManager::get('default');
		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		
		$role = $this->Usermanage->get_user_role($this->user_id);
		$designations = $this->ERPfunction->designation_list();
		$this->set('designations',$designations);
		
		$module = $this->ERPfunction->module_list();
		$this->set('modules',$module);
		
	
		$findvalue=array();
		$notificationlist=array();
		$alloted_data=0;
		$post['access']=array();
		$notification_data = array();
		if($this->request->is('post')){
			$post = $this->request->data();
			
			if(isset($post['go'])){
				$find=$erp_accessrights_tbl->find()->where(['role'=>$post['role']])->hydrate(false)->toArray();
				foreach($find as $finddata)

				{
					$findvalue=json_decode($finddata['accessrights']);
					$alloted_data=$finddata['Alloted'];
					$notificationlist=json_decode($finddata['notificationlist']);
				}
				$findvalue=(array)$findvalue;
				$notificationlist=(array)$notificationlist;	
				$accessdata= isset($findvalue[$post['module']])?$findvalue[$post['module']]:array();
			
				
				
					$accessrole=$post['role'];
					$modulename=$post['module'];
								
				$this->set('get_accessdata',$accessdata);
				$this->set('notificationlist',$notificationlist);
				
				$this->set('alloted_data',$alloted_data);
			}
			
			$accessrole=$post['role'];
			$modulename=$post['module'];
			 $data=array();
			
			$find=$erp_accessrights_tbl->find()->where(['role'=>$accessrole])->hydrate(false)->toArray();
			
			foreach($find as $finddata)
			{
				$findvalue=json_decode($finddata['accessrights']);
				$alloted_data=$finddata['Alloted'];
				$notificationlist=json_decode($finddata['notificationlist']);
			}
			
			$findvalue=(array)$findvalue;	
			$notificationlist=(array)$notificationlist;				
			$accessdata= isset($findvalue[$post['module']])?$findvalue[$post['module']]:array();			
			
			$this->set('get_accessdata',$accessdata);
			
			$this->set('alloted_data',$alloted_data);
			$this->set('notificationlist',$notificationlist);
			
			//$this->set('get_accessdata',$find);
			
			if(isset($post['submit'])){
			// if(isset($post['access'])){
			$post['access']=isset($post['access'])?$post['access']:array();
			$post['notification']=isset($post['notification'])?$post['notification']:array();
			
			if(isset($findvalue[$modulename])){
				$findvalue[$modulename]=array();
				
			}
			
			foreach($post['access'] as $key=>$value){
				$findvalue[$modulename][]=$key;
			}
			foreach($post['notification'] as $key=>$value){
				$notification_data[]=$key;
			}
			$result_data=json_encode($findvalue);
			
			
				if(!empty($find))
				{
					foreach($find as $finddata)
					{
						$id=$finddata['Id'];
					}
					$save_access=$erp_accessrights_tbl->get($id);
				}
				else
				{
					$save_access=$erp_accessrights_tbl->newEntity();
					$save_access->created_by=$this->user_id;
					$save_access->created_date=date('Y-m-d');
				}
			$save_access['role']=$accessrole;
			$save_access->accessrights=$result_data;
			$save_access->update_by=$this->user_id;
			$save_access->update_date=date('Y-m-d');
			if(isset($post['alloted'])){
				if($post['alloted']=="on"){
					$save_access->Alloted=1;
				}
				else{
					$save_access->Alloted=0;
				}
			}
			else{
					$save_access->Alloted=0;
				}
				
			$save_access->notificationlist=json_encode($notification_data);
			if($erp_accessrights_tbl->save($save_access))
			{

				$this->Flash->success(__('Right has been updated.'));
				return $this->redirect(['action'=>'rights',$accessrole,$modulename]);

			}
			// }
		}
		} 
		else{
			$find=$erp_accessrights_tbl->find()->where(['role'=>$accessrole])->hydrate(false)->toArray();
			
			foreach($find as $finddata)
			{
				$findvalue=json_decode($finddata['accessrights']);
				$alloted_data=$finddata['Alloted'];
				$notificationlist=json_decode($finddata['notificationlist']);
			}
			$findvalue=(array)$findvalue;		
			$notificationlist=(array)$notificationlist;		
			$accessdata= isset($findvalue[$modulename])?$findvalue[$modulename]:array();					
			$this->set('get_accessdata',$accessdata);
			$this->set('notificationlist',$notificationlist);
			$this->set('alloted_data',$alloted_data);
		}
		$this->set('accessrole',$accessrole);
		$this->set('modulename',$modulename);
    }

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
	
	
}

?>