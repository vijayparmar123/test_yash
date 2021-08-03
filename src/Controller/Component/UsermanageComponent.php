<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use  Cake\Utility\Xml;
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;

class UsermanageComponent extends Component
{
	public $projects;
	public function users_project($user_id)
	{
		$erp_projects_assign = TableRegistry::get('erp_projects_assign'); 
		$result = $erp_projects_assign->find()->where(['user_id'=>$user_id]);
		$projects_id = array();
		foreach($result as $retrive_data)
		{
			$projects_id[] = $retrive_data['project_id'];
		}

		return $projects_id;
	}
	public function get_user_role($user_id)
	{
		$erp_users = TableRegistry::get('erp_users'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$res_array = array();
		foreach($user_data as $retrive_data)
		{
			$res_array['role'] = $retrive_data['role'];
		}
		if(isset($res_array['role']))
		return $res_array['role'];
	}
	
	public function access_project($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		
		/* if($role !='ceo' && $role !='erphead'){  */
		if($this->project_alloted($role)==1){ 
			if(!empty($projects_ids)){ 
				/* $result = $conn->execute('select * from  erp_projects where project_id in ('.implode(',',$projects_ids ).')');			 */
				$result = $conn->execute('select * from  erp_projects where project_id in ('.implode(',',$projects_ids ).') and project_status != "Fully Completed"');			
					
			}else{
				$result=array();
			}
		}else{
			$result = $conn->execute('select * from  erp_projects where project_status != "Fully Completed"');	
		}
		return $result;
	}
	
	public function all_access_project($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		
		/* if($role !='ceo' && $role !='erphead'){  */
		if($this->project_alloted($role)==1){  
			if(!empty($projects_ids)){ 
				/* $result = $conn->execute('select * from  erp_projects where project_id in ('.implode(',',$projects_ids ).')');			 */
				$result = $conn->execute('select * from  erp_projects where project_id in ('.implode(',',$projects_ids ).') and actual_amount = 0');			
					
			}else{
				$result=array();
			}
		}else{
			$result = $conn->execute('select * from  erp_projects where actual_amount = 0');	
		}
		return $result;
	}
	
	public function access_project_ongoing($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		
		/* if($role !='ceo' && $role !='erphead'){  */
		if($this->project_alloted($role)==1){ 
			if(!empty($projects_ids)){ 
				/* $result = $conn->execute('select * from  erp_projects where project_id in ('.implode(',',$projects_ids ).')');			 */
				$result = $conn->execute('select * from  erp_projects where project_id in ('.implode(',',$projects_ids ).') and project_status = "On Going"');			
					
			}else{
				$result=array();
			}
		}else{
			$result = $conn->execute('select * from  erp_projects where project_status = "On Going"');	
		}
		return $result;
	}
	
	public function fetch_approve_pr($user_id,$data = array())
	{
		$role = $this->get_user_role($user_id);
		$conn = ConnectionManager::get('default');
		$parts = array();
		
		if(!empty($data))
		{
			if($data['project_id'] != '')
			{
				$parts[] = 'project_id = '.$data['project_id'];
			}
			else
			{
				/* if($role !='ceo') */
				if($this->project_alloted($role)==1){  
					$projects_ids = $this->users_project($user_id);
					$parts[] = 'project_id in ('.implode(',',$projects_ids ).')';
				}
			}
			if($role =='deputymanagerelectric')
			{
				$material_ids = $this->get_deputymanagerelectric_material();
				$material_ids = json_decode($material_ids);
				$erp_inventory_pr_material = TableRegistry::get("erp_inventory_pr_material");
				$pr_ids = $erp_inventory_pr_material->find()->where(["material_id IN"=>$material_ids])->select('pr_id')->hydrate(false)->toArray();
				$pr_array = array();
				foreach($pr_ids as $pr_id)
				{
					$pr_array[] = $pr_id['pr_id'];
				}
				if(!empty($pr_array))
				{
					$parts[] = 'pr_id in ('.implode(',',$pr_array ).')';
				}
			}
			// if($data['from_date'] != '' && $data['to_date'] != '')
			// {
				// $parts[] = 'pr_date  BETWEEN "'.$data['from_date'].'" AND "'.$data['to_date'].'"';
			// }
			// elseif($data['from_date'] != '')
			// {
				// $parts[] = 'pr_date = "'.$data['from_date'].'"';
			// }
			// elseif($data['to_date'] != '')
			// {
				// $parts[] = 'pr_date = "'.$data['to_date'].'"';
			// }
			/* if($role !='ceo') */
			if($this->project_alloted($role)==1){  
				if(!empty($parts))
				{
					$sql = "select * from  erp_inventory_purhcase_request where  approved_status = 0 AND ";
				}else{
					$sql = "select * from  erp_inventory_purhcase_request where  approved_status = 0";
				}
			}
			else{
				if(!empty($parts))
				{
					$sql = "select * from  erp_inventory_purhcase_request where  approved_status = 0 AND ";
				}else{
					$sql = "select * from  erp_inventory_purhcase_request where  approved_status = 0";
				}
			}
			if(!empty($parts))
			{
				$sql .= implode(' AND ',$parts);
			}
			// debug($sql);die;
			$result = $conn->execute($sql);	
			
		}
		else{		
		
		/* if($role !='ceo' && $role != "erphead") */
		if($this->project_alloted($role)==1){  
			$projects_ids = $this->users_project($user_id);
			if(!empty($projects_ids)){
				$result = $conn->execute('select * from  erp_inventory_purhcase_request where  approved_status = 0 AND	project_id in ('.implode(',',$projects_ids ).')');	
			}else{
				$result=array();
			}
		
		}
		else{
			$result = $conn->execute('select * from  erp_inventory_purhcase_request where  approved_status = 0');		
			// $result = $conn->execute('select * from  erp_inventory_purhcase_request as a 
					// left join erp_inventory_pr_material as b on b.pr_id = a.pr_id where  a.approved_status = 0');		
			}
		}
		return $result;
	}
	public function export_pr_status($user_id,$data=array())
	{
		
	}
	
	public function fetch_approve_pr_prstatus($user_id,$data = array())
	{
		
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		$parts = array();
		/* New Query*/
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$pr_mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		$or = array();
		
		$or["erp_inventory_purhcase_request.project_id"] = (!empty($data["project_id"]) && $data["project_id"] != "All")?$data["project_id"]:NULL;
		if($role =='deputymanagerelectric')
		{
			$material_ids = $this->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$or["erp_inventory_pr_material.material_id IN"] = $material_ids;
		}
		if($or["erp_inventory_purhcase_request.project_id"] == NULL)
		{
			if($this->project_alloted($role)==1)
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
		$result = $result->innerjoin(
			["erp_inventory_pr_material"=>"erp_inventory_pr_material"],
			["erp_inventory_purhcase_request.pr_id = erp_inventory_pr_material.pr_id"])
			->where($or)->select($pr_mat_tbl)->order(["date(erp_inventory_pr_material.approved_date) DESC","erp_inventory_purhcase_request.project_id ASC"])->hydrate(false)->toArray();
		// debug($result);die;
		$new_array = array();
		foreach($result as $retrive)
		{
			if(isset($new_array[$retrive['prno']]))
			{
				$new_array[$retrive['prno']]['erp_inventory_pr_material'][] = $retrive['erp_inventory_pr_material'];
			}else{
				$a = $retrive["erp_inventory_pr_material"];
				unset($retrive["erp_inventory_pr_material"]);
				$retrive["date_of_approve"] = $a["approved_date"];
				$new_array[$retrive["prno"]] = $retrive;
				$new_array[$retrive["prno"]]['erp_inventory_pr_material'][] = $a;
			}
			
		}
		return $new_array;die;
		// debug($new_array);die;
		// /* New Query*/
		// if(!empty($data))
		// {
			// if($data['project_id'] != '')
			// {
				// $parts[] = 'project_id = '.$data['project_id'];
			// }
			// else
			// {
				// /* if($role !='ceo') */
				// if($this->project_alloted($role)==1){ 
					// $parts[] = 'project_id in ('.implode(',',$projects_ids ).')';
				// }
			// }
			// if($role =='deputymanagerelectric')
			// {
				// $material_ids = $this->get_deputymanagerelectric_material();
				// $material_ids = json_decode($material_ids);
				// $erp_inventory_pr_material = TableRegistry::get("erp_inventory_pr_material");
				// $pr_ids = $erp_inventory_pr_material->find()->where(["material_id IN"=>$material_ids])->select('pr_id')->hydrate(false)->toArray();
				// $pr_array = array();
				// foreach($pr_ids as $pr_id)
				// {
					// $pr_array[] = $pr_id['pr_id'];
				// }
				// if(!empty($pr_array))
				// {
					// $parts[] = 'pr_id in ('.implode(',',$pr_array ).')';
				// }
			// }
			// if($data['from_date'] != '' && $data['to_date'] != '')
			// {
				// $parts[] = 'pr_date  BETWEEN "'.$data['from_date'].'" AND "'.$data['to_date'].'"';
			// }
			// elseif($data['from_date'] != '')
			// {
				// $parts[] = 'pr_date = "'.$data['from_date'].'"';
			// }
			// elseif($data['to_date'] != '')
			// {
				// $parts[] = 'pr_date = "'.$data['to_date'].'"';
			// }
			// /* if($role !='ceo') */
			// if($this->project_alloted($role)==1){ 
				// if(!empty($parts))
				// {
					// $sql = "select * from  erp_inventory_purhcase_request where  approved_status = 0 AND ";
				// }else{
					// $sql = "select * from  erp_inventory_purhcase_request where  approved_status = 0 ORDER BY project_id ASC,pr_date";
				// }
			// }
			// else{
				// if(!empty($parts))
				// {
					// $sql = "select * from  erp_inventory_purhcase_request where  approved_status = 0 AND ";
				// }else{
					// $sql = "select * from  erp_inventory_purhcase_request where  approved_status = 0 ORDER BY project_id ASC,pr_date";
				// }
			// }
			// if(!empty($parts))
			// {
				// $sql .= implode(' AND ',$parts);
				// $sql .= " ORDER BY project_id ASC,pr_date";
			// }
			// debug($sql);die;
			// $result = $conn->execute($sql);	
			
		// }
		// else{		
		
		// /* if($role !='ceo' && $role != "erphead") */
		// if($this->project_alloted($role)==1){ 
			// if(!empty($projects_ids)){
// $result = $conn->execute('select * from  erp_inventory_purhcase_request where  approved_status = 0 AND	project_id in ('.implode(',',$projects_ids ).')');	
			// }else{
				// $result=array();
			// }
		
		// }
		// else{
			// $result = $conn->execute('select * from  erp_inventory_purhcase_request where  approved_status = 0');		
			//$result = $conn->execute('select * from  erp_inventory_purhcase_request as a 
					// left join erp_inventory_pr_material as b on b.pr_id = a.pr_id where  a.approved_status = 0');		
			// }
		// }
		// return $result;
	}
	
	public function fetch_view_pr($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role != "erphead") */
		if($this->project_alloted($role)==1){ 
			if(!empty($projects_ids)){
				$result = $conn->execute('select * from  erp_inventory_purhcase_request where  approved_status = 1 AND	project_id in ('.implode(',',$projects_ids ).')');	
			}else{
				$result=array();
			}
		
	}else{
		$result = $conn->execute('select * from  erp_inventory_purhcase_request where  approved_status = 1');
	}
					
		return $result;
	}
	
	public function fetch_view_pr_material($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role != "erphead") */
		if($this->project_alloted($role)==1){ 
			if(!empty($projects_ids)){
				// $result = $conn->execute('select * from  erp_inventory_pr_material where  (approved = 1 OR show_in_purchase = 1 ) AND	project_id in ('.implode(',',$projects_ids ).')');	
				$result = $conn->execute('select * from  erp_inventory_pr_material a 
				inner join 
				erp_inventory_purhcase_request b 
				on a.pr_id = b.pr_id
				where  (a.approved = 1 OR a.show_in_purchase = 1 ) AND	b.project_id in ('.implode(',',$projects_ids ).')');	
			}else{
				$result=array();
			}		
	}else{
		$result = $conn->execute('select * from  erp_inventory_pr_material a 
		RIGHT JOIN erp_inventory_purhcase_request b ON a.pr_id = b.pr_id where a.approved = 1 OR a.show_in_purchase = 1');
	}
					
		return $result;
	}
	
	public function fetch_view_advance_request($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$projects = $this->access_project($user_id);
		$projects_array = array();
		foreach($projects as $pro)
		{
			$projects_array[] = $pro['project_id'];
		}
		//var_dump($projects_array);die;
		$conn = ConnectionManager::get('default');
		// $result = $conn->execute('select * from  erp_advance_request a 
				// inner join 
				// erp_advance_request_detail b 
				// on a.request_id = b.request_id where b.approval_export = 0');
				// return $result;
		/* if($role !='ceo' && $role != "erphead") */
		if($this->project_alloted($role)==1){ 
			if(!empty($projects_ids)){
				//$result = $conn->execute('select * from  erp_inventory_pr_material where  (approved = 1 OR show_in_purchase = 1 ) AND	project_id in ('.implode(',',$projects_ids ).')');	
				$result = $conn->execute('select * from  erp_advance_request a 
				inner join 
				erp_advance_request_detail b 
				on a.request_id = b.request_id where b.approval_export = 0 AND a.project_id in ('.implode(',',$projects_ids ).')');	
			}
			else
			{
				$result=array();
			}
			
		}
		else
		{
			$result = $conn->execute('select * from  erp_advance_request a 
				inner join 
				erp_advance_request_detail b 
				on a.request_id = b.request_id where b.approval_export = 0 AND a.project_id in ('.implode(',',$projects_array ).')');	
		}
		return $result;		
		
	}
	
	public function fetch_view_expence_detail($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select * from  erp_expence_add a 
				inner join 
				erp_expence_detail b 
				on a.id = b.exp_id');
				return $result;	
	}
	
	public function fetch_approve_po($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role != "erphead") */
		if($this->project_alloted($role)==1){ 
			if(!empty($projects_ids)){
				$result = $conn->execute('select * from  erp_inventory_po where  approved_status = 0 AND	project_id in ('.implode(',',$projects_ids ).')');	
			}else{
				$result=array();
			}
		
		}else{
			$result = $conn->execute('select * from  erp_inventory_po where  approved_status = 0');		
		}
		return $result;
	}
	
	public function fetch_view_po($user_id,$data = array())
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		$parts = array();
		if(!empty($data))
		{			
			if($data['project_id'] != '')
			{
				$parts[] = 'project_id = '.$data['project_id'];
			}
			else
			{
				/* if($role !='ceo' && $role !='erphead') */
				if($this->project_alloted($role)==1){  
					$parts[] = 'project_id in ('.implode(',',$projects_ids ).')';
				}		
			}
			if($data['from_date'] != '' && $data['to_date'] != '')
			{
				$parts[] = 'po_date  BETWEEN "'.$data['from_date'].'" AND "'.$data['to_date'].'"';
			}
			elseif($data['from_date'] != '')
			{
				$parts[] = 'po_date = "'.$data['from_date'].'"';
			}
			elseif($data['to_date'] != '')
			{
				$parts[] = 'po_date = "'.$data['to_date'].'"';
			}			
			/* if($role !='ceo' && $role !='erphead') */
			if($this->project_alloted($role)==1){ 
				$sql = "select * from  erp_inventory_po where  approved_status = 1 AND ";
			}
			else
				$sql = "select * from  erp_inventory_po where  approved_status = 1 AND ";
			
			$sql .= implode(' AND ',$parts);
			$sql_data = "select * from  erp_inventory_po where  approved_status = 1";
			
			if($data['project_id'] != 'All')
				$result = $conn->execute($sql);
			else
				$result = $conn->execute($sql_data);
			
		}
		else{
			
		/* if($role !='ceo' && $role != "erphead") */
			if($this->project_alloted($role)==1){ 
			if(!empty($projects_ids)){
			$result = $conn->execute('select * from  erp_inventory_po where  approved_status = 1 AND	project_id in ('.implode(',',$projects_ids ).')');		
			}else{
				$result=array();
			}
			
		}else{
			$result = $conn->execute('select * from  erp_inventory_po where  approved_status = 1');	
		}

		}
			
		return $result;
	}	
	
	public function fetch_view_po_new($user_id)
	{
	  	$role = $this->get_user_role($user_id);
		$conn = ConnectionManager::get('default');
		$projects_ids = $this->users_project($user_id);
		$erp_inventory_po = TableRegistry::get("erp_inventory_po");
		$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
		
		if($this->project_alloted($role)==1)
		{
			if(!empty($projects_ids))
			{
				if($role == "deputymanagerelectric")
				{
					$material_ids = $this->get_deputymanagerelectric_material();
					$material_ids = json_decode($material_ids);
					$result = $erp_inventory_po->find()->select($erp_inventory_po)->where(["project_id IN"=>$projects_ids]);
					$result = $result->innerjoin(
						["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
						["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved !="=>0])
						->select($erp_inventory_po_detail)->where(["material_id IN"=>$material_ids])->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
						//var_dump($result);die;
					//$this->set('grn_list',$result);
				}else{
					$material_ids = $this->get_deputymanagerelectric_material();
					$material_ids = json_decode($material_ids);
					$result = $erp_inventory_po->find()->select($erp_inventory_po)->where(["project_id IN"=>$projects_ids]);
					$result = $result->innerjoin(
						["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
						["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved !="=>0])
						->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				}
			}
			else
			{
				$result=array();
			}
		}
		else
		{
			if($role == "deputymanagerelectric")
			{
				$result = $erp_inventory_po->find()->select($erp_inventory_po);
				$result = $result->innerjoin(
					["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
					["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved !="=>0])
					->select($erp_inventory_po_detail)->where(["material_id IN"=>$material_ids])->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
			}else{
				$result = $erp_inventory_po->find()->select($erp_inventory_po);
				$result = $result->innerjoin(
					["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
					["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved !="=>0])
					->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
			}				
		}
			
					
		
		//return ($result->fetchAll("assoc"));
		return $result;
			
	}
	
	public function fetch_view_po_manual($user_id)
	{
	  	$role = $this->get_user_role($user_id);
		$conn = ConnectionManager::get('default');
		$projects_ids = $this->users_project($user_id);
		
		$erp_manual_po = TableRegistry::get("erp_manual_po");
		$erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
		
		if($this->project_alloted($role)==1){ 
			if(!empty($projects_ids))
			{
				if($role == "deputymanagerelectric")
				{
					$material_ids = $this->get_deputymanagerelectric_material();
					$material_ids = json_decode($material_ids);
					$result = $erp_manual_po->find()->select($erp_manual_po)->where(["project_id IN"=>$projects_ids]);
					$result = $result->innerjoin(
					["erp_manual_po_detail"=>"erp_manual_po_detail"],
					["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved !="=>0])
					->select($erp_manual_po_detail)->where(["material_id IN"=>$material_ids])->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				}else{
					$result = $erp_manual_po->find()->select($erp_manual_po)->where(["project_id IN"=>$projects_ids]);
					$result = $result->innerjoin(
						["erp_manual_po_detail"=>"erp_manual_po_detail"],
						["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved !="=>0])
						->select($erp_manual_po_detail)->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				}
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
			if($role == "deputymanagerelectric")
			{
				$result = $erp_manual_po->find()->select($erp_manual_po);
				$result = $result->innerjoin(
					["erp_manual_po_detail"=>"erp_manual_po_detail"],
					["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved !="=>0])
					->select($erp_manual_po_detail)->where(["material_id IN"=>$material_ids])->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();
			}else{
				$result = $erp_manual_po->find()->select($erp_manual_po);
				$result = $result->innerjoin(
					["erp_manual_po_detail"=>"erp_manual_po_detail"],
					["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved !="=>0])
					->select($erp_manual_po_detail)->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();
			}
					//var_dump($result);die;
				
		}
			
					
		
		//return ($result->fetchAll("assoc"));
		return $result;
			
	}
	
	// public function fetch_view_po_new($user_id,$data = array())
	// {
	  	// $role = $this->get_user_role($user_id);
		// $conn = ConnectionManager::get('default');
		// $projects_ids = $this->users_project($user_id);		
		// if(empty($data) || $data[0] == "All")
		// {
			// if($role == "projectdirector" || $role == "contractadmin" || $role == "constructionmanager" || $role == "billingengineer" || $role == "materialmanager" || $role == 'projectcoordinator') 
			// {
				// $parts = 'project_id in ('.implode(',',$projects_ids ).')'; 
				// $result = $conn->execute("select a.*,b.* from  erp_inventory_po as a left join erp_inventory_po_detail as b ON a.po_id = b.po_id AND b.approved = 1 where {$parts}");	

			// }else{
				// $result = $conn->execute("select a.*,b.* from  erp_inventory_po as a left join erp_inventory_po_detail as b ON a.po_id = b.po_id AND b.approved = 1");	
			// }
			
		// }
		// else
		// {			
			// $parts = 'project_id in ('.implode(',',$data ).')'; 
			// $result = $conn->execute("select a.*,b.* from  erp_inventory_po as a left join erp_inventory_po_detail as b ON a.po_id = b.po_id AND b.approved = 1 where {$parts}");	
		// }
		
		// return ($result->fetchAll("assoc"));
			
	// }
	
	
	public function fetch_approve_grn($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role != 'erphead') */
			if($this->project_alloted($role)==1){  
				if(!empty($projects_ids)){
					$result = $conn->execute('select * from erp_inventory_grn_detail as grndetail INNER JOIN erp_inventory_grn as grn on grn.grn_id = grndetail.grn_id where grndetail.approved = 0  AND	grn.project_id in ('.implode(',',$projects_ids ).') group by grn.grn_id');	
				}else{
					$result=array();
				}
		
		}else{
			$result = $conn->execute('select * from erp_inventory_grn_detail as grndetail INNER JOIN erp_inventory_grn as grn on grn.grn_id = grndetail.grn_id where grndetail.approved = 0 group by grn.grn_id ');		
		}
		return $result;
	}
	public function fetch_approve_grn_no($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role != 'erphead') */
		if($this->project_alloted($role)==1){  
				if(!empty($projects_ids)){
					$result = $conn->execute('select * from  erp_inventory_grn where  approved_status = 0 AND	project_id in ('.implode(',',$projects_ids ).')');	
				}else{
					$result=array();
				}
		
		}else{
			$result = $conn->execute('select * from  erp_inventory_grn where  approved_status = 0');		
		}
		return $result;
	}
	
	public function fetch_approve_grn_account()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select * from  erp_inventory_grn where approved_status = 1 AND show_in_account=0');		
		return $result;
	}
	
	public function fetch_view_grn($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role != "erphead" ) */
		if($this->project_alloted($role)==1){  
			if(!empty($projects_ids))
			{
				// $result = $conn->execute('select * from  erp_inventory_grn where  approved_status = 1 AND	project_id in ('.implode(',',$projects_ids ).')');	
				$result = $conn->execute('select * from erp_inventory_grn a
				inner join erp_inventory_grn_detail b
				on a.grn_id = b.grn_id
				where b.approved = 1 AND a.project_id in ('.implode(',',$projects_ids ).') ORDER BY a.grn_id desc');	
			}
			else{
				$result=array();
			}
		
		}else{
			$result = $conn->execute('select * from  erp_inventory_grn a
				inner join erp_inventory_grn_detail b
				on a.grn_id = b.grn_id
				where b.approved= 1  ORDER BY a.grn_id desc');	
			}	
		return $result;
	}
	
	public function fetch_approve_is($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo') */
		if($this->project_alloted($role)==1){  
			if(!empty($projects_ids)){
				
			}else{$result=array();}
		$result = $conn->execute('select * from  erp_inventory_is where  approved_status = 0 AND	project_id in ('.implode(',',$projects_ids ).')');	
		}else{
			$result = $conn->execute('select * from  erp_inventory_is where  approved_status = 0');		
		}
		return $result;
	}
	
	public function fetch_approve_is_details($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role != "erphead") */
		if($this->project_alloted($role)==1){  
			if(!empty($projects_ids)){
				$result = $conn->execute('select * from  erp_inventory_is right join 
				erp_inventory_is_detail ON erp_inventory_is.is_id = erp_inventory_is_detail.is_id
				where erp_inventory_is.project_id in ('.implode(',',$projects_ids ).') and erp_inventory_is_detail.approved = 0');
			}else{$result=array();}				
		}else{
			$result = $conn->execute('select * from  erp_inventory_is_detail left join erp_inventory_is ON erp_inventory_is_detail.is_id = erp_inventory_is.is_id where erp_inventory_is_detail.approved = 0');		
		}

		return $result;
	}
	
	
	public function fetch_view_is($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role != "erphead" ) */
			if($this->project_alloted($role)==1){  
				if(!empty($projects_ids)){
					$result = $conn->execute('select * from  erp_inventory_is a
											right join erp_inventory_is_detail b on a.is_id = b.is_id
											where  b.approved = 1 and a.project_id in ('.implode(',',$projects_ids ).')');	
				}else{
						$result=array();
					}
		
		}else{
			$result = $conn->execute('select * from  erp_inventory_is a right join erp_inventory_is_detail b on 
									a.is_id = b.is_id where  b.approved = 1');		
		}
		return $result;
	}	
	public function fetch_approve_mrn($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role !='erphead') */
		if($this->project_alloted($role)==1)
			$result = $conn->execute('select * from  erp_inventory_mrn where  approve_executives = 0 AND	project_id in ('.implode(',',$projects_ids ).')');	
		else
			$result = $conn->execute('select * from  erp_inventory_mrn as a left join erp_inventory_mrn_detail as b ON a.mrn_id = b.mrn_id where approved = 0');		
		return $result;
	}
	
	public function account_fetch_approve_mrn()
	{
		$conn = ConnectionManager::get('default');		
		$result = $conn->execute('select * from  erp_inventory_mrn as a left join erp_inventory_mrn_detail as b ON a.mrn_id = b.mrn_id where b.approved = 0 and a.approve_accountant = 0 and a.approve_executives = 0');		
		return $result;
	}
	
	public function fetch_view_mrn($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role !='erphead') */
			if($this->project_alloted($role)==1){  
				if(!empty($projects_ids)){
				$result = $conn->execute('select * from  erp_inventory_mrn where  approve_executives = 1 AND	project_id in ('.implode(',',$projects_ids ).')');			
				}else{
					$result=array();
				}
			
		}else{
			$result = $conn->execute('select * from  erp_inventory_mrn where  approve_executives = 1');	
		}	
		return $result;
	}	
	
	public function fetch_approve_rbn($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role != "erphead") */
			if($this->project_alloted($role)==1){  
			if(!empty($projects_ids))
			{
				/* $result = $conn->execute('select * from  erp_inventory_rbn where  approved_status = 0 AND	project_id in ('.implode(',',$projects_ids ).')');			 */
				$result = $conn->execute('select * from  erp_inventory_rbn a right join erp_inventory_rbn_detail b 
										ON a.rbn_id = b.rbn_id
										where  b.approved = 0 AND a.project_id in ('.implode(',',$projects_ids ).')');			
			}else{
				$result=array();
			}
		
		}else{
			/* $result = $conn->execute('select * from  erp_inventory_rbn where  approved_status = 0');	 */
			$result = $conn->execute('select * from  erp_inventory_rbn_detail a right join erp_inventory_rbn b ON a.rbn_id = b.rbn_id where a.approved = 0');		
		}
		return $result;
	}

	public function fetch_approve_rbn_by_project($project_id)
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select * from  erp_inventory_rbn a right join erp_inventory_rbn_detail b 
										ON a.rbn_id = b.rbn_id
										where  b.approved = 0 AND a.project_id = '.$project_id .'');			
		
		return $result;
	}

	public function fetch_view_rbn($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo') */
			if($this->project_alloted($role)==1){  
			if(!empty($projects_ids)){
			$result = $conn->execute('select * from  erp_inventory_rbn a
											right join erp_inventory_rbn_detail b ON a.rbn_id = b.rbn_id 
								where  b.approved = 1 AND a.project_id in ('.implode(',',$projects_ids ).')');			
			}else{
				$result=array();
			}
		
		}else{
			$result = $conn->execute('select * from  erp_inventory_rbn a right join erp_inventory_rbn_detail b
										ON a.rbn_id = b.rbn_id where  b.approved = 1');	
		}	
		return $result;
	}
	
	public function fetch_approve_sst($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role !='erphead') */
			if($this->project_alloted($role)==1){  
			if(!empty($projects_ids)){
			$result = $conn->execute('select * from  erp_inventory_sst_detail as a 
			left join erp_inventory_sst as b ON a.sst_id = b.sst_id 
			where  a.approved_site2 = 0 AND b.approved_status = 0 AND	
			( b.project_id in ('.implode(',',$projects_ids ).') OR b.transfer_to in ('.implode(',',$projects_ids ).'))');			
			}else{
					$result=array();
			}
		
		}else{
			$result = $conn->execute('select * from  erp_inventory_sst_detail as a 
			left join erp_inventory_sst as b ON a.sst_id = b.sst_id 
			where  a.approved_site2 = 0');		
		}
		return $result;
	}
	
	public function fetch_approve_sst_byproject($project_id)
	{
		// $role = $this->get_user_role($user_id);
		// $projects_ids = $this->users_project($user_id);
		
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo' && $role !='erphead') */
			// if($role =='projectdirector' || $role =='contractadmin' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager' || $role =='projectcoordinator')
		// {
			// if(!empty($projects_ids)){
			$result = $conn->execute('select * from  erp_inventory_sst_detail as a 
			left join erp_inventory_sst as b ON a.sst_id = b.sst_id 
			where  a.approved_site2 = 0 AND b.approved_status = 0 AND	
			( b.project_id = ' .$project_id.' OR b.transfer_to = '.$project_id.')');			
			// }else{
					// $result=array();
			// }
		
		// }else{
			// $result = $conn->execute('select * from  erp_inventory_sst_detail as a 
			// left join erp_inventory_sst as b ON a.sst_id = b.sst_id 
			// where  a.approved_site2 = 0');		
		// }
		return $result;
	}
	
	public function fetch_view_sst($user_id)
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		/* if($role !='ceo') */
			if($this->project_alloted($role)==1){  
			if(!empty($projects_ids)){
				$result = $conn->execute('select * from  erp_inventory_sst where  approved_status = 1 AND	
				(project_id in ('.implode(',',$projects_ids ).') OR 
				 transfer_to in ('.implode(',',$projects_ids ).')
				) ');	
			}else{
				$result=array();
			}
		
		}else{
			$result = $conn->execute('select * from  erp_inventory_sst where  approved_status = 1');		
		}
		return $result;
	}	
	
	
	
	public function inventory_access_right()
	{
		
		$this->user_id=$this->request->session()->read('user_id');
			$role = $this->get_user_role($this->user_id);
				$rights = array('preparepr'=>$this->retrive_accessrights($role,'preparepr'),
						
						'editpreparepr'=>$this->retrive_accessrights($role,'editpreparepr'),
						
						'approvedpr'=>$this->retrive_accessrights($role,'approvedpr_inve'),
						/* 'viewpr'=>array('erphead'=>1,'ceo'=>1,'materialmanager'=>1,'constructionmanager'=>1,'accountant'=>1), */
						'viewpr'=>$this->retrive_accessrights($role,'viewpr'),
						'prstatus'=>$this->retrive_accessrights($role,'prstatus'),
						
						'previewpr'=>$this->retrive_accessrights($role,'previewpr'),
						
						"approvepo"=>$this->retrive_accessrights($role,'approvepo'),
						
						"previewpo2"=>$this->retrive_accessrights($role,'approvepo'),
						
						'editpreparepo'=>$this->retrive_accessrights($role,'editpreparepo'),
						
						'preparepo'=>array('erphead'=>1,'erpmanager'=>1,'md'=>1,'purchasehead'=>1,"purchasemanager"=>1),
						
						'preparepo2'=>array('erphead'=>1,'erpmanager'=>1,'erpoperator'=>1,'purchasehead'=>1,'purchasemanager'=>1,'deputymanagerelectric'=>1),
						
						'viewpo'=>array('erphead'=>1,'ceo'=>1,'materialmanager'=>1,'constructionmanager'=>1,'accountant'=>1),
						'preparegrn'=>array('erphead'=>1,'erpmanager'=>1,'erpoperator'=>1,'materialmanager'=>1,"assethead"=>1,'asset-inventoryhead'=>1),
						
						'updategrn'=>$this->retrive_accessrights($role,'updategrn'),
						'updateapprovedgrn'=>array('erphead'=>1),
						
						'preparegrnwithoutpo'=>$this->retrive_accessrights($role,'preparegrnwithoutpo'),
						
						'approvegrn'=>$this->retrive_accessrights($role,'approvegrn'),
						
						'grnaudit'=>$this->retrive_accessrights($role,'grnaudit'),
						
						'updateauditgrn'=>$this->retrive_accessrights($role,'updateauditgrn'),
						
						'viewgrn'=>$this->retrive_accessrights($role,'viewgrn'),
						
						'auditgrnchanges'=>$this->retrive_accessrights($role,'auditgrnchanges'),
						'previewapprovedgrn'=>$this->retrive_accessrights($role,'previewapprovedgrn'),
						
						'prepareis'=>$this->retrive_accessrights($role,'prepareis'),
						
						'ponorate'=>$this->retrive_accessrights($role,'ponorate'),
						
						'wonorate'=>$this->retrive_accessrights($role,'wonorate'),
						
						'inventorypostatus'=>$this->retrive_accessrights($role,'inventorypostatus'),
						
						'updateis'=>$this->retrive_accessrights($role,'updateis'),
						
						'isaudit'=>$this->retrive_accessrights($role,'isaudit'),
						'updateisaudit'=>$this->retrive_accessrights($role,'updateisaudit'),
						
						'approveis'=>array("erphead"=>1,"erpmanager"=>1,'md'=>1,'projectdirector'=>1,"constructionmanager"=>1,"materialmanager"=>1),
						
						"previewapprovedis"=>$this->retrive_accessrights($role,'previewapprovedis'),
						
						'previewis'=>array('erphead'=>1,'erpmanager'=>1,'erpoperator'=>1,'ceo'=>1,'md'=>1,'projectdirector'=>1,'constructionmanager'=>1,'billingengineer'=>1,'materialmanager'=>1,'asset-inventoryhead'=>1),
						
						'auditischanges'=>$this->retrive_accessrights($role,'auditischanges'),
						
						'printis'=>array('erphead'=>1,'erpmanager'=>1,'erpoperator'=>1,'ceo'=>1,'md'=>1,'projectdirector'=>1,'constructionmanager'=>1,'billingengineer'=>1,'materialmanager'=>1,'asset-inventoryhead'=>1),
						
						'viewis'=>$this->retrive_accessrights($role,'viewis'),
						
						'printapprovedis'=>array('erphead'=>1,'projectcoordinator'=>1,'projectdirector'=>1,'constructionmanager'=>1,'erpmanager'=>1,'materialmanager'=>1,'erpoperator'=>1),
						
						'preparemrn'=>$this->retrive_accessrights($role,'preparemrn'),
						
						'approvemrn'=>$this->retrive_accessrights($role,'approvemrn'),
						
						'viewmrn'=>$this->retrive_accessrights($role,'viewmrn'),
						'editmrn'=>$this->retrive_accessrights($role,'editmrn'),
						'updategrnapproved'=>$this->retrive_accessrights($role,'updategrnapproved'),
						'previewapprovedmrn'=>$this->retrive_accessrights($role,'previewapprovedmrn'),
						
						'preparerbn'=>$this->retrive_accessrights($role,'preparerbn'),
						
						'editrbn'=>$this->retrive_accessrights($role,'editrbn'),
						
						'rbnaudit'=>$this->retrive_accessrights($role,'rbnaudit'),
						
						'updaterbnaudit'=>$this->retrive_accessrights($role,'updaterbnaudit'),
						
						'approverbn'=>array("erphead"=>1,'erpmanager'=>1,'md'=>1,'projectdirector'=>1,'constructionmanager'=>1,'materialmanager'=>1),
						/* 'printrbn'=>array("erphead"=>1,'projectcoordinator'=>1,'projectdirector'=>1,'constructionmanager'=>1), */
						'viewrbn'=>$this->retrive_accessrights($role,'viewrbn'),
						'previewapprovedrbn'=>$this->retrive_accessrights($role,'previewapprovedrbn'),
						
						'auditrbnchanges'=>$this->retrive_accessrights($role,'auditrbnchanges'),
						
						'previewrbn'=>array("erphead"=>1,'erpmanager'=>1,'erpoperator'=>1,'ceo'=>1,'md'=>1,'projectdirector'=>1,'projectcoordinator'=>1,'billingengineer'=>1,'constructionmanager'=>1,'materialmanager'=>1),
						
						'printrbn'=>array("erphead"=>1,'erpmanager'=>1,'erpoperator'=>1,'ceo'=>1,'md'=>1,'projectdirector'=>1,'projectcoordinator'=>1,'billingengineer'=>1,'constructionmanager'=>1,'materialmanager'=>1),
						
						'preparesst'=>$this->retrive_accessrights($role,'preparesst'),
						
						'approvesst'=>$this->retrive_accessrights($role,'approvesst'),
						
						'editsst'=>$this->retrive_accessrights($role,'editsst'),
						
						'viewsst'=>$this->retrive_accessrights($role,'viewsst'),
						
						'previewapprovedsst'=>$this->retrive_accessrights($role,'previewapprovedsst'),
						
						'grnalert'=>array('erphead'=>1,'ceo'=>1,'materialmanager'=>1,'constructionmanager'=>1,'accountant'=>1),
						'mrnalert'=>array('erphead'=>1,'ceo'=>1,'materialmanager'=>1,'constructionmanager'=>1,'accountant'=>1),
						
						"stockledger"=>$this->retrive_accessrights($role,'stockledger'),
						
						"viewrecords"=>$this->retrive_accessrights($role,'viewrecords_inv'),
						
						"urgentstockrequirment"=>$this->retrive_accessrights($role,'urgentstockrequirment'),
						
						"overpurchasedstock"=>$this->retrive_accessrights($role,'overpurchasedstock'),
						
						"inventorydebitnotealert"=>$this->retrive_accessrights($role,'inventorydebitnotealert'),
						"inventorypreparedebit"=>$this->retrive_accessrights($role,'inventorypreparedebit'),
						"editinventorydebit"=>$this->retrive_accessrights($role,'editinventorydebit'),
						"previewdebit"=>$this->retrive_accessrights($role,'previewdebit'),
						
						"inventorydebitrecords"=>$this->retrive_accessrights($role,'inventorydebitrecords'),
						
						'inventorypodeliveryrecords'=>$this->retrive_accessrights($role,'inventorypodeliveryrecords'),
						'mixdesign'=>$this->retrive_accessrights($role,'mixdesign'),
						'prepareinventoryrmc'=>$this->retrive_accessrights($role,'prepareinventoryrmc'),
						'inventoryrmcalert'=>$this->retrive_accessrights($role,'inventoryrmcalert'),
						'inventoryrmcrecords'=>$this->retrive_accessrights($role,'inventoryrmcrecords'),
						'editinventoryrmc'=>$this->retrive_accessrights($role,'editinventoryrmc'),
						'mixdesignlisting'=>$this->retrive_accessrights($role,'mixdesignlisting'),
						"filemanager"=>$this->retrive_accessrights($role,'inventoryfilemanager'),
						);
		return $rights;
	}

/* array("erphead"=>0,"erpmanager"=>0,"ceo"=>1,"MD"=>1,"contractadmin"=>1,"projectdirector"=>1,"constructionmanager"=>0,"materialmanager"=>0,"billingengineer"=>0,"accountant"=>0,"pmm"=>0,"accounthead"=>0,"senioraccount"=>0,"humanresource"=>0,"purchasehead"=>0,"purchasemanager"=>0) */
	public function usermanage_access_right()
	{
		$this->user_id=$this->request->session()->read('user_id');
			$role = $this->get_user_role($this->user_id);
		
		$rights = array(
					"add"=>$this->retrive_accessrights($role,'adduser'),
					"userlist"=>$this->retrive_accessrights($role,'userlist'),
					"view"=>$this->retrive_accessrights($role,'viewuserlist'),		
					"viewprojectlist"=>$this->retrive_accessrights($role,'viewprojectlist_user'),
					"openingstock"=>$this->retrive_accessrights($role,'openingstock'),
				);
		return $rights;
	}
	
	public function projects_access_right()
	{
		$this->user_id=$this->request->session()->read('user_id');
			$role = $this->get_user_role($this->user_id);
		$rights = array(
				"add"=>$this->retrive_accessrights($role,'add'),
				
				"edit"=>$this->retrive_accessrights($role,'edit'),
				"viewprojectlist"=>$this->retrive_accessrights($role,'viewprojectlist'),
				
				"viewproject"=>$this->retrive_accessrights($role,'viewprojectlist'),
				
				"printproject"=>$this->retrive_accessrights($role,'viewprojectlist'),
				
				"contractnotificationlist"=>$this->retrive_accessrights($role,'contractnotificationlist'),
				
				"addcontractnotification"=>$this->retrive_accessrights($role,'addcontractnotification'),
				
				"editcontractnotification"=>$this->retrive_accessrights($role,'editcontractnotification'),
				
				"viewcontractnotification"=>$this->retrive_accessrights($role,'contractnotificationlist'),
				
				"personalnotificationlist"=>$this->retrive_accessrights($role,'personalnotificationlist'),
				
				"addpersonalnotification"=>$this->retrive_accessrights($role,'addpersonalnotification'),
				
				"editpersonalnotification"=>$this->retrive_accessrights($role,'editpersonalnotification'),
				
				"viewpersonalnotification"=>$this->retrive_accessrights($role,'personalnotificationlist'),
				"filemanager"=>$this->retrive_accessrights($role,'projectfilemanager'),
					);
		return $rights;
	}
	
	public function contract_access_right()
	{
		$this->user_id=$this->request->session()->read('user_id');
			$role = $this->get_user_role($this->user_id);
		
		$rights = array(
				"addinward"=>$this->retrive_accessrights($role,'addinward'),
				
				"inwardlist"=>array('erphead'=>1,'contractadmin'=>1,'projectcoordinator'=>1,"projectdirector"=>1),
				
				"viewinwardlist"=>$this->retrive_accessrights($role,'viewinwardlist'),
				
				"viewaddinward"=>$this->retrive_accessrights($role,'viewinwardlist'),
				
				"addoutward"=>$this->retrive_accessrights($role,'addoutward'),
				
				"outwardlist"=>array('erphead'=>1,'contractadmin'=>1,'projectcoordinator'=>1,"projectdirector"=>1),
				
				"viewoutwardlist"=>$this->retrive_accessrights($role,'viewoutwardlist'),
				
				"viewaddoutward"=>$this->retrive_accessrights($role,'viewoutwardlist'),
				
				"addrabill"=>$this->retrive_accessrights($role,'addrabill'),
				
				"editrabill"=>$this->retrive_accessrights($role,'addrabill'), 
				
				"viewrabill"=>$this->retrive_accessrights($role,'viewrabill'),
				
				"viewaddrabill"=>$this->retrive_accessrights($role,'viewrabill'),
				
				"addpricevariation"=>$this->retrive_accessrights($role,'addpricevariation'), 
				
				"editpricevariation"=>$this->retrive_accessrights($role,'addpricevariation'), 
				
				"viewpricevariation"=>$this->retrive_accessrights($role,'viewpricevariation'), 
				
				"viewaddpricevariation"=>$this->retrive_accessrights($role,'viewpricevariation'), 
				
				"addagency"=>$this->retrive_accessrights($role,'addagency'),
				"agencylist"=>$this->retrive_accessrights($role,'agencylist'),
				"viewagency"=>$this->retrive_accessrights($role,'agencylist'),
				"editagency"=>$this->retrive_accessrights($role,'editagency'),
				
				"workheadlist"=>$this->retrive_accessrights($role,'workheadlist'),
				
				"editworkhead"=>$this->retrive_accessrights($role,'editworkhead'),
				
				"viewworkhead"=>$this->retrive_accessrights($role,'workheadlist'),
				
				"approvewo"=>$this->retrive_accessrights($role,'approvewo'),
				
				"preparewo"=>$this->retrive_accessrights($role,'preparewo'),
				
				"editpreparewo"=>$this->retrive_accessrights($role,'editpreparewo'),
				
				"worecords"=>$this->retrive_accessrights($role,'worecords'),
				
				"adddrawing"=>$this->retrive_accessrights($role,'adddrawing'),
				
				"drawingrecords"=>$this->retrive_accessrights($role,'drawingrecords'),
				
				"editdrawing"=>$this->retrive_accessrights($role,'editdrawing'),
				
				"viewdrawing"=>$this->retrive_accessrights($role,'drawingrecords'),
				
				"addsubcontractbill"=>$this->retrive_accessrights($role,'addsubcontractbill'),
				
				"subcontractbillalert"=>$this->retrive_accessrights($role,'subcontractbillalert'),
				
				"editsubcontractbill"=>$this->retrive_accessrights($role,'editsubcontractbill'),
				
				"subcontractrecords"=>$this->retrive_accessrights($role,'subcontractrecords'),
				
				"previewsubcontract"=>$this->retrive_accessrights($role,'subcontractrecords'),
				"filemanager"=>$this->retrive_accessrights($role,'contractfilemanager'),

				"planningworkheadlist"=>$this->retrive_accessrights($role,'planningworkheadlist'),
				"viewplanningworkhead"=>$this->retrive_accessrights($role,'planningworkheadlist'),
				"editplanningworkhead"=>$this->retrive_accessrights($role,'editplanningworkhead'),
				"planningpreparewo"=>$this->retrive_accessrights($role,'planningpreparewo'),
				"planningapprovewo"=>$this->retrive_accessrights($role,'planningapprovewo'),
				"planningammendapprovewo"=>$this->retrive_accessrights($role,'planningammendapprovewo'),
				"previewplanningwo"=>$this->retrive_accessrights($role,'planningapprovewo'),
				"editplanningwo"=>$this->retrive_accessrights($role,'editplanningwo'),
				"planningworecords"=>$this->retrive_accessrights($role,'planningworecords'),
				"workdescription"=>$this->retrive_accessrights($role,'workdescription'),
				"editworkdescription"=>$this->retrive_accessrights($role,'editworkdescription'),
				"ammendworkorder"=>$this->retrive_accessrights($role,'ammendworkorder'),
				);
		return $rights;
	}
	
	public function asset_access_right()
	{
		$this->user_id=$this->request->session()->read('user_id');
			$role = $this->get_user_role($this->user_id);
		$rights = array(
				"add"=>$this->retrive_accessrights($role,'addasset'),
				"trasnsferaccept"=>$this->retrive_accessrights($role,'trasnsferaccept'),
				"soldtheft"=>$this->retrive_accessrights($role,'soldtheft'),
				
				"assetrecord"=>$this->retrive_accessrights($role,'assetrecord'),
				"viewaddasset"=>$this->retrive_accessrights($role,'viewaddasset'),
				"addmaintenance"=>$this->retrive_accessrights($role,'addmaintenance'),
				"viewaddmaintenance"=>$this->retrive_accessrights($role,'viewaddmaintenance'),
				"aprovemaintenance"=>$this->retrive_accessrights($role,'aprovemaintenance'),
				"maintenancerecords"=>$this->retrive_accessrights($role,'maintenancerecords'),
				"equipmentlog"=>$this->retrive_accessrights($role,'equipmentlog'),
				"editeqrecord"=>$this->retrive_accessrights($role,'editeqrecord'),
				"equipmentlogown"=>$this->retrive_accessrights($role,'equipmentlogown'),
				"equipmentlogrecord"=>$this->retrive_accessrights($role,'equipmentlogrecord'),
				"equipmentlogownrecord"=>$this->retrive_accessrights($role,'equipmentlogownrecord'),
				"editequipmentlogowned"=>$this->retrive_accessrights($role,'editequipmentlogowned'),
				"viewequipmentlog"=>$this->retrive_accessrights($role,'equipmentlogrecord'),
				"rmcissueslip"=>$this->retrive_accessrights($role,'rmcissueslip'),
				"editrmcrecord"=>$this->retrive_accessrights($role,'editrmcrecord'),
				"rmcissuealert"=>$this->retrive_accessrights($role,'rmcissuealert'),
				"rmcissuerecord"=>$this->retrive_accessrights($role,'rmcissuerecord'),
				"viewrmcissueslip"=>$this->retrive_accessrights($role,'viewrmcissueslip'),
				"addmaintenancenotification"=>$this->retrive_accessrights($role,'addmaintenancenotification'),
				"editmaintenancenotification"=>$this->retrive_accessrights($role,'editmaintenancenotification'),
				"maintenancenotificationlist"=>$this->retrive_accessrights($role,'maintenancenotificationlist'),
				"viewmaintainancenotification"=>$this->retrive_accessrights($role,'maintenancenotificationlist'),
				"storeissue"=>$this->retrive_accessrights($role,'storeissue'),
				"ViewEfficiencyHistory"=>$this->retrive_accessrights($role,'ViewEfficiencyHistory'),
				"viewStoreIsuueHistory"=>$this->retrive_accessrights($role,'viewStoreIsuueHistory'),
				"assetpo"=>$this->retrive_accessrights($role,'assetpo'),
				"assetpoalert"=>$this->retrive_accessrights($role,'assetpoalert'),
				"editassetpo"=>$this->retrive_accessrights($role,'editassetpo'),
				"viewassetporecords"=>$this->retrive_accessrights($role,'viewassetporecords'),
				"filemanager"=>$this->retrive_accessrights($role,'assetfilemanager'),
				/* "viewrmcissueslip"=>array('erphead'=>1,'erpmanager'=>1,'materialmanager'=>1,"ceo"=>1,"md"=>1,'projectcoordinator'=>1,'accounthead'=>1,'senioraccountant'=>1,"projectdirector"=>1,"constructionmanager"=>1,"pmm"=>1) */
					);
		return $rights;
	}
	
	public function purchase_access_right()
	{
		$this->user_id=$this->request->session()->read('user_id');
			$role = $this->get_user_role($this->user_id);
		
		$rights = array(
					/* "addmaterial"=>array('erphead'=>1,'erpmanager'=>1,'ceo'=>1,'md'=>1,'erpoperator'=>1,'purchasehead'=>1,"purchasemanager"=>1,'deputymanagerelectric'=>1,'asset-inventoryhead'=>1), */
					
					"addmaterial"=>$this->retrive_accessrights($role,'addmaterial'),		
					
					"viewmaterial"=>$this->retrive_accessrights($role,'viewmaterial'),
					
					"addbrand"=>$this->retrive_accessrights($role,'addbrand'),			
					
					"brandlist"=>$this->retrive_accessrights($role,'brandlist'),
					
					"addrate"=>$this->retrive_accessrights($role,'addrate'),
					
					"ratealert"=>$this->retrive_accessrights($role,'ratealert'),
					
					"viewaddrate"=>$this->retrive_accessrights($role,'ratealert'),
					
					"raterecords"=>$this->retrive_accessrights($role,'raterecords'),
					
					"editrate"=>$this->retrive_accessrights($role,'editrate'),
					
					"approvedpr"=>$this->retrive_accessrights($role,'approvedpr'),
					
					'manualpreparepo'=>$this->retrive_accessrights($role,'manualpreparepo'),
					
					'viewammendporecords' => $this->retrive_accessrights($role,'viewammendporecords'),

					'viewporecords'=>$this->retrive_accessrights($role,'viewporecords'),
					
					'postatus'=>$this->retrive_accessrights($role,'postatus'),
					
					'trackpr'=>$this->retrive_accessrights($role,'trackpr'),
					
					'podeliveryrecords'=>$this->retrive_accessrights($role,'podeliveryrecords'),
					
					"viewaddmaterial"=>$this->retrive_accessrights($role,'viewmaterial'),	
					
					'manualapprovepo'=>$this->retrive_accessrights($role,'manualapprovepo'),
					
					'manualpreviewpo'=>$this->retrive_accessrights($role,'manualapprovepo'),
					
					'editmanualpreparepo'=>$this->retrive_accessrights($role,'editmanualpreparepo'),
					
					"addvendor"=>$this->retrive_accessrights($role,'addvendor'),				
					
					"viewvendor"=>$this->retrive_accessrights($role,'viewvendor'),				
					
					"viewaddvendor"=>$this->retrive_accessrights($role,'viewvendor'),				
					"prepareloi"=>$this->retrive_accessrights($role,'prepareloi'),				
					"loialert"=>$this->retrive_accessrights($role,'loialert'),				
					"editloi"=>$this->retrive_accessrights($role,'editloi'),				
					"loirecords"=>$this->retrive_accessrights($role,'loirecords'),				
					
					'viewpo'=>array("erphead"=>1,'ceo'=>1,'md'=>1,'purchasehead'=>1,'purchasemanager'=>1,"projectdirector"=>1,"accounthead"=>1,"senioraccountant"=>1,"contractadmin"=>1),
					
					'manualapprovepolocal'=>array('erphead'=>1,'erpmanager'=>1,'erpoperator'=>1,"ceo"=>1,"md"=>1,'purchasehead'=>1,'purchasemanager'=>1,'deputymanagerelectric'=>1,"projectdirector"=>1),
					"filemanager"=>$this->retrive_accessrights($role,'purchasefilemanager'),
					);
		return $rights;
	}
	public function account_access_right()
	{
		$this->user_id=$this->request->session()->read('user_id');
		$role = $this->get_user_role($this->user_id);
		
		$rights = array(
					"grnalert"=>$this->retrive_accessrights($role,'grnalert'),
					"accountpreviewgrn"=>$this->retrive_accessrights($role,'grnalert'),
					
					"mrnalert"=>$this->retrive_accessrights($role,'mrnalert'),
					"previewmrn"=>$this->retrive_accessrights($role,'mrnalert'),
					
					"addinwardbill"=>$this->retrive_accessrights($role,'addinwardbill'),
					
					"acceptbills"=>$this->retrive_accessrights($role,'acceptbills'),
					
					"inwardpayment"=>$this->retrive_accessrights($role,'inwardpayment'),
					
					"pendingbills"=>$this->retrive_accessrights($role,'pendingbills'),
					
					"accountlist"=>$this->retrive_accessrights($role,'accountlist'),
					"viewbill"=>$this->retrive_accessrights($role,'accountlist'),
					
					"advancerequest"=>$this->retrive_accessrights($role,'advancerequest'),
					"editrequest"=>$this->retrive_accessrights($role,'editrequest'),
					"viewrequest"=>$this->retrive_accessrights($role,'viewrequest'),
					
					"viewadvance"=>$this->retrive_accessrights($role,'viewadvance'),
					
					"createaccount"=>$this->retrive_accessrights($role,'createaccount'),
					
					"expensehead"=>$this->retrive_accessrights($role,'expensehead'),
					"viewexpensehead"=>$this->retrive_accessrights($role,'viewexpensehead'),
					"editexpensehead"=>$this->retrive_accessrights($role,'editexpensehead'),
					
					"amountissued"=>$this->retrive_accessrights($role,'amountissued'),
					
					"addexpence"=>$this->retrive_accessrights($role,'addexpence'),
					
					"expencealert"=>$this->retrive_accessrights($role,'expencealert'),
					"viewexpence"=>$this->retrive_accessrights($role,'expencealert'),
					"editexpence"=>$this->retrive_accessrights($role,'editexpence'),
					
					"sitetransactions"=>$this->retrive_accessrights($role,'sitetransactions'),
					"viewamountissued"=>$this->retrive_accessrights($role,'viewamountissued'),
					"viewexpence"=>$this->retrive_accessrights($role,'viewexpence'),
					
					"adddebitnote"=>$this->retrive_accessrights($role,'adddebitnote'),
					
					"debitnotealert"=>$this->retrive_accessrights($role,'debitnotealert'),
					"viewdebit"=>$this->retrive_accessrights($role,'debitnotealert'),
					
					"editdebit"=>$this->retrive_accessrights($role,'editdebit'),
					
					"debitnoterecord"=>$this->retrive_accessrights($role,'debitnoterecord'),
					"filemanager"=>$this->retrive_accessrights($role,'accountfilemanager'),
					);
		return $rights; 
	}
	
	public function hr_access_right()
	{
		$this->user_id=$this->request->session()->read('user_id');
		$role = $this->get_user_role($this->user_id);
		
		$rights = array(
					"addemployee"=>$this->retrive_accessrights($role,'addemployee'),
					"personaleditemployee"=>$this->retrive_accessrights($role,'personalEmployeeEdit'),
					
					"emplyeelist"=>$this->retrive_accessrights($role,'emplyeelist'),
					
					"viewemployee"=>$this->retrive_accessrights($role,'emplyeelist'),
					
					"paystructure"=>$this->retrive_accessrights($role,'paystructure'),
					"changedesignation"=>$this->retrive_accessrights($role,'changedesignation'),
					
					"notworkingemplyeelist"=>$this->retrive_accessrights($role,'notworkingemplyeelist'),
					"Rejoin"=>$this->retrive_accessrights($role,'Rejoin'),
					
					"attendance"=>$this->retrive_accessrights($role,'attendance'),
					
					"leavesheet"=>array('erphead'=>1,'hrmanager'=>1,"constructionmanager"=>1),
					"leavesummary"=>array('erphead'=>1,"ceo"=>1,"md"=>1,"projectdirector"=>1,'hrmanager'=>1),
					"salaryslip"=>$this->retrive_accessrights($role,'salaryslip'),
					"editsalaryslip"=>$this->retrive_accessrights($role,'editsalaryslip'),
					"salarystatement"=>$this->retrive_accessrights($role,'salarystatement'),
					"salaryrecords"=>$this->retrive_accessrights($role,'salaryrecords'),
					"viewsalaryslip"=>$this->retrive_accessrights($role,'salarystatement'),
					"printsalaryslip"=>$this->retrive_accessrights($role,'printsalaryslip'),
					"history_clam" => $this->retrive_accessrights($role,'history_clam'),
					
					"viewattendance"=>array('erphead'=>1,"hrmanager"=>1),
					"viewrecords"=>$this->retrive_accessrights($role,'viewrecords'),
					"generatesalaryslip"=>$this->retrive_accessrights($role,'generatesalaryslip'),
					"addloan"=>$this->retrive_accessrights($role,'addloan'),
					"editloan"=>$this->retrive_accessrights($role,'editloan'),
					"loanlist"=>$this->retrive_accessrights($role,'loanlist'),
					"loanpending"=>$this->retrive_accessrights($role,'loanpending'),

					"addcandidate"=>$this->retrive_accessrights($role,'addcandidate'),
					"candidatelist"=>$this->retrive_accessrights($role,'candidatelist'),
					"personnel"=>$this->retrive_accessrights($role,'personnel'),

					"createexgracia"=>$this->retrive_accessrights($role,'createexgracia'),

					"bonusalert"=>$this->retrive_accessrights($role,'bonusalert'),
					"generatebonus"=>$this->retrive_accessrights($role,'generatebonus'),
					'viewbonus'=>$this->retrive_accessrights($role,'viewbonus'),
					'viewexgraciarecord'=>$this->retrive_accessrights($role,'viewexgraciarecord'),
					'viewbonusrecord'=> $this->retrive_accessrights($role,'viewbonusrecord'),

					'expenditure'=>$this->retrive_accessrights($role,'expenditure'),
					'viewexpenditure'=>$this->retrive_accessrights($role,'viewexpenditure'),
					'expenditurelist'=>$this->retrive_accessrights($role,'expenditurelist'),
					'historyexpenditure'=>$this->retrive_accessrights($role,'historyexpenditure'),
					"filemanager"=>$this->retrive_accessrights($role,'hrfilemanager'),
					);
		return $rights;
	}
	
	public function attendance_access_right()
	{
		$this->user_id=$this->request->session()->read('user_id');
		$role = $this->get_user_role($this->user_id);
		$rights = array(
					"timelog"=>$this->retrive_accessrights($role,'timelog'),
					"viewlog"=>$this->retrive_accessrights($role,'timelog'),
					"attendancealert"=>$this->retrive_accessrights($role,'attendancealert'),
					"editattendance"=>$this->retrive_accessrights($role,'editattendance'),
					"vieweditattendance"=>$this->retrive_accessrights($role,'attendancealert'),
					"attendancerecord"=>$this->retrive_accessrights($role,'attendancerecord'),
					"viewrecord"=>$this->retrive_accessrights($role,'viewrecord'),
					"generaterecords"=>$this->retrive_accessrights($role,'generaterecords'),
					);
		return $rights;
	}
	
	public function temporary_access_right()
	{
		$rights = array(
					"madefound"=>array('erphead'=>1,'erpoperator'=>1),
					);
		return $rights; 
	}
	
	
	public function is_capable($action,$role)
	{
		$right = $this->inventory_access_right();
		if(isset($right[$action][$role]))
			return TRUE;
		else
			return false;
	}
	public function get_deputymanagerelectric_material()
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(['material_code IN'=>['6','7','10','15']]);
		$material_ids = array();
		foreach($results as $material)
		{
			$material_ids[] = $material->material_id;
		}
		return json_encode($material_ids);
	}
	public function retrive_accessrights($role,$pagename){
	
		$selected=-1;
		$data=array();		
		$findvalue=array();		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		
		$find=$erp_accessrights_tbl->find()->where(['role'=>$role])->hydrate(false)->toArray();
		foreach($find as $finddata)
		{
			$findvalue=json_decode($finddata['accessrights']);
			
		}
		$findvalue=(array)$findvalue;	
		$flag=0;
		foreach($findvalue as $result){
			
			$selected = in_array($pagename,$result);
			if($selected==1){
				$flag=1;
				break;
			}
		}
		
		if($flag==1){
			$data=array($role=>1);
		}
		return $data;
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
	
	public function fetch_approve_pr_prtrack($user_id,$data = array())
	{
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		$conn = ConnectionManager::get('default');
		$parts = array();
		
		/* Join Query*/
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$pr_mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		$or = array();
		
		$or["erp_inventory_purhcase_request.project_id"] = (!empty($data["project_id"]) && $data["project_id"] != "All")?$data["project_id"]:NULL;
		if($role =='deputymanagerelectric')
		{
			$material_ids = $this->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$or["erp_inventory_pr_material.material_id IN"] = $material_ids;
		}
		if($or["erp_inventory_purhcase_request.project_id"] == NULL)
		{
			if($this->project_alloted($role)==1)
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
		$result = $result->innerjoin(
			["erp_inventory_pr_material"=>"erp_inventory_pr_material"],
			["erp_inventory_purhcase_request.pr_id = erp_inventory_pr_material.pr_id"])
			->where($or)->select($pr_mat_tbl)->order(["date(erp_inventory_pr_material.approved_date) DESC","erp_inventory_purhcase_request.project_id ASC"])->hydrate(false)->toArray();
		// debug($result);die;
		$new_array = array();
		foreach($result as $retrive)
		{
			if(isset($new_array[$retrive['prno']]))
			{
				$new_array[$retrive['prno']]['erp_inventory_pr_material'][] = $retrive['erp_inventory_pr_material'];
			}else{
				$a = $retrive["erp_inventory_pr_material"];
				unset($retrive["erp_inventory_pr_material"]);
				$retrive["date_of_approve"] = $a["approved_date"];
				$new_array[$retrive["prno"]] = $retrive;
				$new_array[$retrive["prno"]]['erp_inventory_pr_material'][] = $a;
			}
			
		}
		return $new_array;die;
	}
}
?>