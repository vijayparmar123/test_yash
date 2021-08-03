<?php


namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry; 
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;

class TemporaryController extends AppController
{

	public function initialize(){
		parent::initialize();		
		$this->loadComponent('Flash');
		$this->loadComponent('ERPfunction');
		$this->user_id=$this->request->session()->read('user_id');
		$this->rights = $this->Usermanage->temporary_access_right();
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$action = $this->request->action;
		if(isset($this->rights[$action][$this->role]))
		{
			$is_capable = $this->rights[$action][$this->role];	
		}
		else
			$is_capable = 0;	
		
		$this->set('is_capable',$is_capable);
	}

    public function index(){
		$role = $this->role;
		$this->set('role',$role);
    }
	public function madefound(){
		ini_set('memory_limit', '-1');
		$role = $this->role;
		$this->set('role',$role);
		
		$erp_temporary=TableRegistry::get('erp_temporary');
    	$codes=$erp_temporary->find()->select('OLD_Code')->hydrate(false)->toArray();
		
		$old_codes = array();
		foreach($codes as $code)
		{
			$old_codes[$code['OLD_Code']] = $code['OLD_Code'];
		}
		$old_codes = array_unique($old_codes);
    	$this->set('old_codes',$old_codes);
		
		$new_codes=$erp_temporary->find()->select('NEW_Code')->hydrate(false)->toArray();
		
		$newly_codes = array();
		foreach($new_codes as $code)
		{
			$newly_codes[$code['NEW_Code']] = $code['NEW_Code'];
		}
		$newly_codes = array_unique($newly_codes);
    	$this->set('newly_codes',$newly_codes);
		
		$erp_temporary=TableRegistry::get('erp_temporary');
    	$temp_data=$erp_temporary->find()->hydrate(false)->toArray();
    	$this->set('temporary_data',$temp_data);
		
		if($this->request->is("post"))
		{
			if(isset($this->request->data["go"]))
			{
				// debug($this->request->data);die;
				$post = $this->request->data;		
				$or = array();
				
				$or[$post['stock']."_Width"] = ($post["width"] != "")?$post["width"]:NULL;
				$or[$post['stock']."_Code"] = ($post[$post['stock']."_code"] != "")?$post[$post['stock']."_code"]:NULL;
				$or[$post['stock']."_Length"] = ($post["length"] != "")?$post["length"]:NULL;
				
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$temp_data = $erp_temporary->find()->where($or)->hydrate(false)->toArray();
				$this->set('temporary_data',$temp_data);
				
			}
			
			if(isset($this->request->data["export_csv"]))
			{
				$rows = unserialize(base64_decode($this->request->data["rows"]));
				$filename = "temporary_data.csv";
				$this->ERPfunction->export_to_csv($filename,$rows);
			}
		}
    }
	
	public function addquentity(){
		$this->autoRender = false;
		$post = $this->request->data;
		$field = $post['add_in'];
		$erp_temporary=TableRegistry::get('erp_temporary');
		$row = $erp_temporary->get($post['row']);
		$row->$field = ($row->$field)?$row->$field+$post['quantity']:$post['quantity'];
		
		if($erp_temporary->save($row))
		{
			if($post['add_in'] == "Made")
			{
				$row1 = $erp_temporary->get($post['row']);
				if($row1->Made >= $row1->NEW_Qty)
				{
					$row1->Remarks = 'OK';
					$erp_temporary->save($row1);
				}
			}
			$this->redirect(array("controller" => "Temporary","action" => "madefound"));
		}
	}

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}
