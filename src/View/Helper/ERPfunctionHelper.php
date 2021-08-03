<?php
namespace App\View\Helper;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;
use Cake\Controller\Component;
use Google\Cloud\Storage\StorageClient;

class ERPfunctionHelper extends Helper
{

	public function get_signed_url($file) {
		$storage  = new StorageClient([
			'projectId' => 'yashnand-erp-2021',
			'keyFilePath' => WWW_ROOT .'/nghome/yashnand-erp-2021-67d9ca62a2c8.json',
		]);
		$bucketName = 'yashnand_2021_attachment';
		$bucket = $storage->bucket($bucketName);
		$object = $bucket->object($file);
		$url = $object->signedUrl(
			# This URL is valid for 24 hour
			new \DateTime('1440 min'),
			[
				'version' => 'v4',
			]
		);
		return $url;
	}

	public function get_formate_date($date)
	{
		return date('d-m-Y',strtotime($date));
	}
	
	public function getTotalFual($asset_id,$date)
	{	
		
		$dates = date('Y-m-d',strtotime($date));
		$erp_inventory_is = TableRegistry::get('erp_inventory_is'); 
		$erp_inventory_is_detail = TableRegistry::get('erp_inventory_is_detail');
		$asst_id = 'asst_'.$asset_id; 
		
		$date =  date('m-Y',(strtotime($dates)));
		$month =date('m',(strtotime($dates)));
		$year = date('Y',(strtotime($dates)));
		$sum = 0;

		$result = $erp_inventory_is->find()->where(['agency_name ='=>$asst_id,'MONTH(erp_inventory_is.is_date) ='=>$month,'YEAR(erp_inventory_is.is_date) ='=>$year]);

		
		
			$query = $result->innerjoin(
						["erp_inventory_is_detail"=>"erp_inventory_is_detail"],
						["erp_inventory_is_detail.is_id = erp_inventory_is.is_id"]);
					/*$sum = $query
					->select(['sum' =>'erp_inventory_is_detail.quantity'])
					->where(['erp_inventory_is_detail.material_id'=>90])->hydrate(false)->toArray();*/
				
				$data = $query
					->select(['sum' => $query->func()->sum('erp_inventory_is_detail.quantity')])
					->where(['erp_inventory_is_detail.material_id'=>90])->hydrate(false)->toArray();
				
					
					foreach ($data as $row) {
						$sum = $row['sum'];
					}
					if($sum == null)
					{
						$sum = 0;
					}
			return $sum;
		//return $sum[0]['sum'];
	}
	public function checkTotalFuel($asset_id,$date)
	{ 

		$dates = date('Y-m-d',strtotime($date));
		$erp_inventory_is = TableRegistry::get('erp_inventory_is'); 
		$erp_inventory_is_detail = TableRegistry::get('erp_inventory_is_detail');
		$asst_id = 'asst_'.$asset_id;
		$date =  date('m-Y',(strtotime($dates)));
		$month =date('m',(strtotime($dates)));
		$year = date('Y',(strtotime($dates)));
 		

		//$result = $erp_inventory_is->find()->where(['MONTH(erp_inventory_is.is_date) ='=>$month,'YEAR(erp_inventory_is.is_date) ='=>$year]);
		$result = $erp_inventory_is->find()->where(['agency_name ='=>$asst_id,'erp_inventory_is.is_date ='=>$dates]);
		$query = $result->innerjoin(
						["erp_inventory_is_detail"=>"erp_inventory_is_detail"],
						["erp_inventory_is_detail.is_id = erp_inventory_is.is_id"]);
		
		
		$data = $query
			->select(['sum' =>'SUM(erp_inventory_is_detail.quantity)'])
			->where(['erp_inventory_is_detail.material_id'=>90])->group(['erp_inventory_is.is_date'])->first();
		if(!empty($data))
		{
			return $data->sum;
		}else{
			return 0;
		}
		// $sum = 0;
		// foreach ($data as $key => $value) {
			// $sum += $value['sum'];
		// }
		//debug($sum);die;
		/*foreach ($data as $row )
		{
			$sum = $row['sum'];
		}*/
		// return $sum;
		// die;
	}



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
	public function count_user_byrole($role)
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select count(user_id) from  erp_users where role ="'.$role.'"');		
		$max = 0;
		foreach($result as $retrive_data)
		{ $max=$retrive_data[0]; }
		return $max;
	}
	
	public function count_users()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select count(user_id) from  erp_users where `status` = 1 and `employee_no` = "" ');		
		$max = 0;
		foreach($result as $retrive_data)
		{ $max=$retrive_data[0]; }
		return $max;
	}
	public function getRejoinInfo($id,&$string)
	{
		
		$erp_user = TableRegistry::get('erp_users');
		
		$deviceOverspeed = $erp_user->find()->select(['non_working_id','rejoin_date'])->where(['user_id'=>$id])->hydrate(false)->toArray();
	
		/* $non_working_id = array();
		 $rejoin_date = array();
	
		 if(!empty($deviceOverspeed)) 
		 {
			foreach($deviceOverspeed as $deviceOverspeed)
			{
			 foreach($deviceOverspeed as $key=>$value)
			 {	 
				 $stringid .= $value['non_working_id'];
				 $stringdate .= $value['rejoin_date'].',';
			 }
			}
			
			 $value['non_working_id'] = 0;
			 $value['rejoin_date'] = 0;
			 $non_working_id[$value['non_working_id']] = $this->getRejoinInfo($value['non_working_id'],$stringid);
			 debug($non_working_id);die;
			 $rejoin_date[$value['rejoin_date']] = $this->getRejoinInfo($value['rejoin_date'],$stringdate);
		 }

		 return $non_working_id;*/
		//$erp_user = TableRegistry::get('erp_users');
		//$query = $erp_user->get($id);
		//debug($query);die;
		//foreach($query as $data);
		//{
			
		//}
			
		
	}
	public function count_projects()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select count(project_id) from  erp_projects');		
		$max = 0;
		foreach($result as $retrive_data)
		{ $max=$retrive_data[0]; }
		return $max;
	}
	
	public function count_agency()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select count(id) from  erp_agency');		
		$max = 0;
		foreach($result as $retrive_data)
		{ $max=$retrive_data[0]; }
		return $max;
	}
	
	public function count_assets()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select count(asset_id) from  erp_assets');		
		$max = 0;
		foreach($result as $retrive_data)
		{ $max=$retrive_data[0]; }
		return $max;
	}
	
	public function count_inward_pending_bills()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select count(inward_bill_id) from  erp_inward_bill where status_inward = "pending"');	
		$max = 0;
		foreach($result as $retrive_data)
		{ $max=$retrive_data[0]; }
		return $max;
	}
	
	public function count_vendors()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select count(user_id) from erp_vendor');		
		$max = 0;
		foreach($result as $retrive_data)
		{ $max=$retrive_data[0]; }
		return $max;
	}
	
	public function get_user_name($user_id)
	{
		if(is_numeric($user_id)){
			$erp_users = TableRegistry::get('erp_users'); 
			$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
			$res_array = array();
			foreach($user_data as $retrive_data)
			{
				$res_array['first_name'] = $retrive_data['first_name'];
				$res_array['last_name'] = $retrive_data['last_name'];
				$res_array['email_id'] = $retrive_data['email_id'];
				$res_array['username'] = $retrive_data['username'];
			}
			if(isset($res_array['first_name']))
			{
				if($res_array['first_name'] == "" && $res_array['last_name'] == "" && $res_array['username'] == "")
				{
					return $res_array['email_id'];
				}else if($res_array['username'] != "")
				{
					return $res_array['username'];
				}
				else{
					return $res_array['first_name'].' '.$res_array['last_name'];
				}
			}
			else { 
				return ''; 
			}
		}else {
			return '-';
		}
	}
	
	public function get_full_user_name($user_id)
	{
		$erp_users = TableRegistry::get('erp_users'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$res_array = array();
		foreach($user_data as $retrive_data)
		{
			$res_array['first_name'] = $retrive_data['first_name'];
			$res_array['last_name'] = $retrive_data['last_name'];
			$res_array['email_id'] = $retrive_data['email_id'];
			$res_array['username'] = $retrive_data['username'];
		}
		if(isset($res_array['first_name']))
		{
			if($res_array['first_name'] == "" && $res_array['last_name'] == "")
			{
				/* return $res_array['email_id']; */
				return ucwords($res_array['username']);
			}else{
				return $res_array['first_name'].' '.$res_array['last_name'];
			}
		}
		else
		{ return 'User No Exist More'; }
	}
	
	public function get_vendor_name($user_id)
	{
		$erp_users = TableRegistry::get('erp_vendor'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$res_array = array();
		foreach($user_data as $retrive_data)
		{
			$res_array['first_name'] = $retrive_data['vendor_name'];
			
		}
		if(isset($res_array['first_name']))
			// return $res_array['first_name'].' '.$res_array['last_name'];
			return $res_array['first_name'];
		else
			return '-';
	}
	
	public function get_vendor_id($vendor_name)
	{
		$erp_users = TableRegistry::get('erp_vendor'); 
		$user_data = $erp_users->find()->where(['vendor_name'=>$vendor_name])->first();
		if(!empty($user_data)){
			return $user_data->user_id;
		}else{
			return 0;
		}
	}
	
	public function get_vendor_code($user_id)
	{
		$erp_users = TableRegistry::get('erp_vendor'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id])->first();
		if(!empty($user_data)){
			return $user_data->vendor_id;
		}else{
			return '';
		}
	}
	
	public function get_user_image($user_id)
	{
		$erp_users = TableRegistry::get('erp_users'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$imagepath = '';
		// debug($user_data->hydrate(false)->toArray());die;
		foreach($user_data as $retrive_data)
		{
			$imagepath = $retrive_data['image_url'];
			$gender = $retrive_data["gender"];
		}
		
		if($imagepath != '')
			return $imagepath;
		else if($gender == "Male" || $gender == "")
			return 'male1.png';  /*default_userimage.png*/
		else
			return "female1.jpg";
	}
	public function get_user_status($user_id)
	{
		$erp_users = TableRegistry::get('erp_users'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$status = 0;		
		foreach($user_data as $retrive_data)
		{
			$status = $retrive_data['status'];			
		}
		if($status)
			return 'Active';
		else
			return 'Removed';		
	}
	public function getMonth($month)
	{
		$month = (int)$month;
		switch ($month) {
			case 1:
				$month = 'January';
				break;
			case 2:
				$month = 'February';
				break;
			case 3:
				$month = 'March';
				break;
			case 4:
				$month = 'April';
				break;
			case 5:
				$month = 'May';
				break;
			case 6:
				$month = 'June';
				break;
			case 7:
				$month = 'July';
				break;
			case 8:
				$month = 'August';
				break;
			case 9:
				$month = 'September';
				break;
			case 10:
				$month = 'October';
				break;
			case 11:
				$month = 'November';
				break;
			case 12:
				$month = 'December';
				break;
			default:
				$month = 'Not a valid month!';
				break;
		}
		return $month;
	}
	public function get_user_remove_date($user_id)
	{
		$erp_users = TableRegistry::get('erp_users'); 
		$status = $this->get_user_status($user_id);
		if($status == 'Removed')
		{
			$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
			$status = 0;		
			foreach($user_data as $retrive_data)
			{
				$remove_date = $retrive_data['remove_date'];			
			}
			return $this->get_date($remove_date);
		}
		else
			return ' - ';
			
	}
	
	public function get_vendor_image($user_id)
	{
		$erp_users = TableRegistry::get('erp_vendor'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$imagepath = '';
		foreach($user_data as $retrive_data)
		{
			$imagepath = $retrive_data['image_url'];
			
		}
		
		if($imagepath != '')
			return $imagepath;
		else
			return 'default_userimage.png';
	}
	public function get_vendor_status($user_id)
	{
		$erp_users = TableRegistry::get('erp_vendor'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$status = 0;		
		foreach($user_data as $retrive_data)
		{
			$status = $retrive_data['status'];			
		}
		if($status)
			return 'Active';
		else
			return 'Removed';		
	}
	public function get_vendor_remove_date($user_id)
	{
		$erp_users = TableRegistry::get('erp_vendor'); 
		$status = $this->get_vendor_status($user_id);
		if($status == 'Removed')
		{
			$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
			$status = 0;		
			foreach($user_data as $retrive_data)
			{
				$remove_date = $retrive_data['remove_date'];			
			}
			return $this->get_date($remove_date);
		}
		else
			return ' - ';
			
	}
	
	public function get_employee_image($employee_id)
	{
		$erp_users = TableRegistry::get('erp_users');  
		$user_data = $erp_users->find()->where(['user_id'=>$employee_id])->hydrate(false)->toArray();
		$imagepath = '';
		$gender = "Male";
		$url = '';
		foreach($user_data as $retrive_data)
		{
			$imagepath = $retrive_data['image_url'];
			$gender = $retrive_data['gender'];
			$storage  = new StorageClient([
				'projectId' => 'yashnand-erp-2021',
				'keyFilePath' => WWW_ROOT .'/nghome/yashnand-erp-2021-67d9ca62a2c8.json',
			]);
			$bucketName = 'yashnand_2021_attachment';
			$bucket = $storage->bucket($bucketName);
			$object = $bucket->object($retrive_data['image_url']);
			$url = $object->signedUrl(
				# This URL is valid for 24 hour
				new \DateTime('1440 min'),
				[
					'version' => 'v4',
				]
			);
		}
		if($url != '')
			return $url;
		else if($gender == "Male" || $gender == "")
			return 'male1.png';  /*default_userimage.png*/
		else
			return "female1.jpg";
		
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
	
	public function get_rolename($role_name)
	{
		$roles = array('accounts'=>'Accounts',
						'ceo'=>'C. E. O.',
						'constructionmanager'=>'Construction Manager',
						'humanresource'=>'H. R.',
						'inventorystaff'=>'Inventory',
						'materialmanager'=>'Material Manager(M.M)',
						'md'=>'M. D.',
						'projectdirector'=>'Project Directors',
						'purchasehead'=>'Purchase(P.H)',
						'vendor'=>'Vendor');
		return $roles[$role_name];
	}
	
	public function get_date($date)
	{
			return date('d-m-Y',strtotime($date));
	}
	public function selected($option,$value)
	{	
		if($option == $value)
			return 'selected';
		else
			return '';
	}
	public function multiselected($value,$options)
	{
		if(in_array($value,$options))
			return 'selected';
		else
			return '';
	}
	public function multichecked($value,$options)
	{
		if(in_array($value,$options))
			return 'checked=checked';
		else
			return '';
	}
	public function checked($option,$value)
	{
		if($option === $value)
			return 'checked=checked';
		else
			return '';
	}
	public function action_link($controllername,$action='index')
	{
		return Router::url(["controller" => $controllername,"action" => $action]);
	}
	public function get_category_title($cat_id)
	{
		if(is_numeric($cat_id)){
			$erp_category_master = TableRegistry::get('erp_category_master'); 
			$category_data = $erp_category_master->find()->where(['cat_id'=>$cat_id]);
			$res_array = array();
			foreach($category_data as $retrive_data)
			{
				$res_array['category_title'] = $retrive_data['category_title'];
				$res_array['cat_id'] = $retrive_data['cat_id'];
			}
			if(isset($res_array['category_title']))
				return $res_array['category_title'];
			else
				return '';
		}
		else {return '-';}
	}
	
	public function get_projectcode($project_id)
	{	
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);	
		$result_arr = array();
		$result_arr['project_code'] = '-';
		if(!empty($project_data)){
		
			foreach($project_data as $retrive_data){
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		}
		return $result_arr['project_code'];
	}
	public function get_projectname($project_id)
	{	
		if(is_numeric($project_id)){
			$projectdetail = TableRegistry::get('erp_projects'); 
			$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);	
			$result_arr = array();
			$result_arr['project_name'] = '-';
			if(!empty($project_data)){
				foreach($project_data as $retrive_data)
				{
					$result_arr['project_name'] = $retrive_data['project_name'];			
				}
			}
			return $result_arr['project_name'];
		}
		else {return '-';}
	}
	
	public function get_projectname_by_code($project_code)
	{	
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_code'=>$project_code]);	
		$result_arr = array();
		$result_arr['project_name'] = '-';
		if(!empty($project_data)){
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_name'] = $retrive_data['project_name'];			
		}
	}
		return $result_arr['project_name'];
	}
	
	public function get_projectaddress($project_id)
	{	
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);	
		$result_arr = array();
		$result_arr['project_address'] = "";
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_address'] = $retrive_data['project_address'];			
		}
		if($result_arr['project_address'] != "")
			return $result_arr['project_address'];
	}
	public function get_projectcity($project_id)
	{	
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);	
		$result_arr = array();
		$result_arr['city'] = "";
		foreach($project_data as $retrive_data)
		{
			$result_arr['city'] = $retrive_data['city'];			
		}
		if($result_arr['city'] != "")
			return $result_arr['city'];
	}
	public function get_materialcode_bymaterialid($material_id)
	{
		$erp_material = TableRegistry::get('erp_material'); 
		$material_data = $erp_material->find()->where(['material_id'=>$material_id]);
		$cnt = $material_data->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$material_data = $erp_material->find()->where(['material_id'=>$material_id]);
		}
		$material_code = 0;
		foreach($material_data as $retrive_data)
		{
			$material_code = $retrive_data['material_code'];
			$unit_id = $retrive_data['unit_id'];			
		}
		// $material_category = $this->ERPfunction->material_category();
		$material_category = $this->material_category();
		$returnarray['material_code'] = $material_category[$material_code]['material_code'];
	}
	
	public function material_category()
	{
		$category  = array();
		$category['1']= array('material_code'=>'YNEC/MT/PC',
							'category_name'=>'Packed Cement');
		$category['2']= array('material_code'=>'YNEC/MT/LC',
							'category_name'=>'Loos Cement');
		$category['3']= array('material_code'=>'YNEC/MT/ST',
							'category_name'=>'Still');
		$category['4']= array('material_code'=>'YNEC/MT/CL',
							'category_name'=>'Civil');
		$category['5']= array('material_code'=>'YNEC/MT/PL',
							'category_name'=>'Plumbing ');
		$category['6']= array('material_code'=>'YNEC/MT/EL',
							'category_name'=>'Electrical');
		$category['7']= array('material_code'=>'YNEC/MT/OT',
							'category_name'=>'Other');
		return $category;
	}
	
	public function get_material_item_code_bymaterialid($material_id)
	{
		$erp_material = TableRegistry::get('erp_material'); 
		$material_data = $erp_material->find()->where(['material_id'=>$material_id]);
		$cnt = $material_data->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$material_data = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		$material_code = 0;
		$material_item_code = "-";
		foreach($material_data as $retrive_data)
		{
			$material_item_code = $retrive_data['material_item_code'];
				
		}		
		return $material_item_code;
		
	}
	
	public function get_material_item_hsncode_bymaterialid($material_id)
	{
		$erp_material = TableRegistry::get('erp_material'); 
		$material_data = $erp_material->find()->where(['material_id'=>$material_id]);
		// $cnt = $material_data->count();
		// if($cnt == 0)
		// {
			// $tmp_tbl = TableRegistry::get("erp_material_temp");
			// $material_data = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		// }
		// $material_code = 0;
		$material_item_code = "-";
		foreach($material_data as $retrive_data)
		{
			$material_item_code = $retrive_data['hsn_code'];
				
		}		
		return $material_item_code;
		
	}
	
	public function get_material_title($material_id)
	{
		if(is_numeric($material_id))
		{
			$erp_material = TableRegistry::get('erp_material');
			$results = $erp_material->find()->where(array('material_id'=>$material_id));
			$cnt = $results->count();
			if($cnt == 0)
			{
				$tmp_tbl = TableRegistry::get("erp_material_temp");
				$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
			}
			$material_title = "-";
			foreach($results as $retrive_data)
			{
				$material_title = $retrive_data['material_title'];					
			}
			return $material_title;
		}else{
			return '-';
		}
		
	}
	
	public function get_materialitemcode($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$cnt = $results->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		$material_code = "";
		foreach($results as $retrive_data)
		{
			$material_code = $retrive_data['material_item_code'];					
		}
		return $material_code;
	}
	public function get_materialitem_desc($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$cnt = $results->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		$material_desc = "";
		foreach($results as $retrive_data)
		{
			$material_desc = $retrive_data['desciption'];					
		}
		return $material_desc;
	}
	public function get_brandname($brand_id)
	{
		$erp_material = TableRegistry::get('erp_material_brand');
		$results = $erp_material->find()->where(array('brand_id'=>$brand_id));
		$brand_name = ' - ';
		foreach($results as $retrive_data)
		{
			$brand_name = $retrive_data['brand_name'];					
		}
		return $brand_name;
	}
	public function get_items_units($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$cnt = $results->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		$units_title = "-";
		foreach($results as $retrive_data)
		{
			$mat_unitid = $retrive_data['unit_id'];
			if(!empty($mat_unitid)){
				$units_title = $this->get_category_title($mat_unitid);
				
			}
		}
		return $units_title;
		 
	}
	
	public function get_items_consumetype($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$row = $erp_material->find()->where(["material_id"=>$material_id])->first();
		if(!empty($row))
		{
			$results = $erp_material->get($material_id);
			$consume_type = '';
			if(!empty($results))
			{
				$consume_type = $results->consume;
			}
			return $consume_type;
		}else{
			return "Retunable / Non-consumable";
		} 
	}
	
	public function get_items_costgroup($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$row = $erp_material->find()->where(["material_id"=>$material_id])->first();
		if(!empty($row))
		{
			$results = $erp_material->get($material_id);
			
			if(!empty($results))
			{
				return ucfirst($results->cost_group);
			}else{
				return "";
			}
		}else{
			return "C";
		}
		
		 
	}
	
	public function get_items_amount($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$cnt = $results->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		$units_title = "-";
		foreach($results as $retrive_data)
		{
			$mat_unitid = $retrive_data['unit_id'];
			if(!empty($mat_unitid)){
				$units_title = $this->get_category_title($mat_unitid);
				
			}
		}
		return $units_title;
		 
	}
	
	public function get_pr_no($pr_id)
	{
		$erp_material = TableRegistry::get('erp_inventory_purhcase_request');
		$results = $erp_material->find()->where(array('pr_id'=>$pr_id));
		$pr_no_text = "";
		foreach($results as $retrive_data)
		{
			$pr_no_text = $retrive_data['prno'];
		}
		if($pr_no_text != "")
			return $pr_no_text;
	}
	public function get_po_no($po_id)
	{
		$erp_material = TableRegistry::get('erp_inventory_po');
		$results = $erp_material->find()->where(array('po_id'=>$po_id));
		$po_no_text = "";
		foreach($results as $retrive_data)
		{
			$po_no_text = $retrive_data['po_no'];
		}
		if($po_no_text != "")
			return $po_no_text;
	}
	public function designation_list()
	{
		$erp_user_role = TableRegistry::get('erp_user_role');
		
		$role_list = $erp_user_role->find()->where(['status'=>1])->hydrate(false)->toArray();
		//$designation[] = array('role'=>'sr-billingengineer','code'=>'IM','title'=>'Sr.Billing Engineer');
		
		return $role_list;
		
	}
	public function get_designation($role)
	{
		$designations = $this->designation_list();
		foreach($designations as $key => $value)
		{
			if($role == $value['value'])
			{
				return $value['title'];
			}
		}
	}
	public function vendor_group()
	{
		// $vendor_group[1] = array('id'=>'1','code'=>'LC','title'=>'Loose Cement');
		// $vendor_group[2] = array('id'=>'2','code'=>'PC','title'=>'Packed Cement');
		// $vendor_group[3] = array('id'=>'3','code'=>'ST','title'=>'Steel');
		// $vendor_group[4] = array('id'=>'4','code'=>'CV','title'=>'Civil');
		// $vendor_group[5] = array('id'=>'5','code'=>'PL','title'=>'Plumbing, Drainage & Sanitory');
		// $vendor_group[6] = array('id'=>'6','code'=>'EC','title'=>'Electric');
		// $vendor_group[7] = array('id'=>'7','code'=>'EL','title'=>'Electronic');
		// $vendor_group[8] = array('id'=>'8','code'=>'SP','title'=>'Spares');
		// $vendor_group[9] = array('id'=>'9','code'=>'AS','title'=>'Hardware');
		// $vendor_group[10] = array('id'=>'10','code'=>'HV','title'=>'HVAC');
		// $vendor_group[11] = array('id'=>'11','code'=>'FF','title'=>'Fire Fighting');
		// $vendor_group[12] = array('id'=>'12','code'=>'IN','title'=>'Interior');
		// $vendor_group[13] = array('id'=>'13','code'=>'DS','title'=>'Fuel');
		// $vendor_group[14] = array('id'=>'14','code'=>'SF','title'=>'Safety');
		////$vendor_group[16] = array('id'=>'16','code'=>'TMP','title'=>'Temporary');
		// $vendor_group[15] = array('id'=>'15','code'=>'OT','title'=>'Others');
		// $vendor_group[17] = array('id'=>'17','code'=>'TEMP','title'=>'Temp');
		
		$erp_vendor_groups = TableRegistry::get("erp_vendor_groups");
		$groups = $erp_vendor_groups->find();
		$vendor_group = array();
		foreach($groups as $group)
		{
			$vendor_group[$group->id] = array('id'=>$group->id,'code'=>$group->code,'title'=>$group->title);
		}
		
		return $vendor_group;
	}
	public function get_vendor_group_code($id)
	{
		$vendor_group = $this->vendor_group();
		
		return $vendor_group[$id]['code'];
	}
	
	public function get_vendor_group_name($id)
	{
		$vendor_group = $this->vendor_group();
		return $vendor_group[$id]['title'];
	}
	public function asset_group()
	{
		// $asset_group[1] = array('id'=>'1','code'=>'PL','title'=>'Plant');
		// $asset_group[2] = array('id'=>'2','code'=>'MA','title'=>'Machine');
		// $asset_group[3] = array('id'=>'3','code'=>'HV','title'=>'Heavy Vehicle');
		// $asset_group[4] = array('id'=>'4','code'=>'SV','title'=>'Small Vehicle');
		// $asset_group[5] = array('id'=>'5','code'=>'EQ','title'=>'Shuttering');
		// $asset_group[6] = array('id'=>'6','code'=>'FR','title'=>'Furniture');
		// $asset_group[7] = array('id'=>'7','code'=>'EL','title'=>'Electronics');
		// $asset_group[8] = array('id'=>'8','code'=>'TL','title'=>'Tools');
		// $asset_group[9] = array('id'=>'9','code'=>'OT','title'=>'Others');
		// $asset_group[10] = array('id'=>'10','code'=>'EC','title'=>'Electric');
		$erp_asset_groups = TableRegistry::get("erp_asset_groups");
		$groups = $erp_asset_groups->find();
		$asset_group = array();
		foreach($groups as $group)
		{
			$asset_group[$group->id] = array('id'=>$group->id,'code'=>$group->code,'title'=>$group->title);
		}
		return $asset_group;
	}
	
	public function get_asset_group_name($id)
	{
		$asset_group = $this->asset_group();
		return $asset_group[$id]['title'];
	}
	public function month_names()
	{
		$month[1]=array('name'=>'January');
		$month[2]=array('name'=>'February');
		$month[3]=array('name'=>'March');
		$month[4]=array('name'=>'April');
		$month[5]=array('name'=>'May');
		$month[6]=array('name'=>'June');
		$month[4]=array('name'=>'July');
		$month[8]=array('name'=>'August');
		$month[9]=array('name'=>'September');
		$month[10]=array('name'=>'October');
		$month[11]=array('name'=>'November');
		$month[12]=array('name'=>'December');
		return $month;
	}
	
	public function get_month_name($month_key)
	{
		$months = $this->month_names();
		if($month_key != "")
		{
			$month_array = $months[$month_key];
			return $month_array['name']; 
		}else{
			return "";
		}
	}
	public function get_employee_no($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		/* $user_data = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$user_data = $erp_employee->find()->where(['user_id'=>$employee_id]);
		$status = 0;
		$employee_no = "";
		foreach($user_data as $retrive_data)
		{
			$employee_no = $retrive_data['employee_no'];			
		}
		return $employee_no;		
	}
	
	public function get_user_birthdate($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		/* $user_data = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$user_data = $erp_employee->find()->where(['user_id'=>$employee_id,'employee_no !='=>'']);
		$birth_date = "";
		foreach($user_data as $retrive_data)
		{
			$birth_date = $retrive_data['date_of_birth'];			
		}
		return $birth_date;		
	}
	
	public function get_user_joindate($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		/* $user_data = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$user_data = $erp_employee->find()->where(['user_id'=>$employee_id,'employee_no !='=>'']);
		$join_date = "";
		foreach($user_data as $retrive_data)
		{
			$join_date = $retrive_data['date_of_joining'];			
		}
		return $join_date;		
	}
	
	public function get_user_is_epf($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		/* $user_data = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$user_data = $erp_employee->find()->where(['user_id'=>$employee_id,'employee_no !='=>'']);
		$is_epf = "";
		foreach($user_data as $retrive_data)
		{
			$is_epf = $retrive_data['is_epf'];			
		}
		return $is_epf;		
	}
	
	public function get_user_is_esi($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		/* $user_data = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$user_data = $erp_employee->find()->where(['user_id'=>$employee_id,'employee_no !='=>'']);
		$is_esi = "";
		foreach($user_data as $retrive_data)
		{
			$is_esi = $retrive_data['is_esi'];			
		}
		return $is_esi;		
	}
	
	public function get_user_epf_no($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		/* $user_data = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$user_data = $erp_employee->find()->where(['user_id'=>$employee_id,'employee_no !='=>'']);
		$epf_no = "";
		foreach($user_data as $retrive_data)
		{
			$epf_no = $retrive_data['epf_no'];			
		}
		return $epf_no;		
	}
	
	public function get_user_uan_no($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		/* $user_data = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$user_data = $erp_employee->find()->where(['user_id'=>$employee_id,'employee_no !='=>'']);
		$uan_no = "";
		foreach($user_data as $retrive_data)
		{
			$uan_no = $retrive_data['uan_no'];			
		}
		return $uan_no;		
	}
	
	public function get_user_esi_no($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		/* $user_data = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$user_data = $erp_employee->find()->where(['user_id'=>$employee_id,'employee_no !='=>'']);
		$esi_no = "";
		foreach($user_data as $retrive_data)
		{
			$esi_no = $retrive_data['esi_no'];			
		}
		return $esi_no;		
	}
	
	public function get_asset_code($asset_id){
		$erp_asset = TableRegistry::get('erp_assets');
		/* $results = $erp_asset->get($asset_id); */
		$results = $erp_asset->find()->where(["asset_id"=>$asset_id])->hydrate(false)->toArray();
		return $results[0]['asset_code'];
	}
	public function get_asset_capacity($asset_id){
		$erp_asset = TableRegistry::get('erp_assets');
		$results = $erp_asset->get($asset_id);
		return $results['capacity'];
	}
	
	public function get_asset_make($asset_id){
		$erp_asset = TableRegistry::get('erp_assets');
		$results = $erp_asset->get($asset_id);
		$assstmake = $this->get_category_title($results['asset_make']);
		return $assstmake;
	}
	
	public function get_asset_name($asset_id){
		$erp_asset = TableRegistry::get('erp_assets');
		$results = $erp_asset->get($asset_id)->toArray();		
		return $results["asset_name"];
	}
	
	public function get_project_code($project_id){
		$erp_projects = TableRegistry::get('erp_projects');
		$results = $erp_projects->get($project_id)->toArray();		
		return $results["project_code"];
	}
	
	public function get_projectname_by_asset($asset_id){
		$erp_asset = TableRegistry::get('erp_assets');
		$results = $erp_asset->get($asset_id);
		$projectname = $this->get_projectname($results['deployed_to']);
		return $projectname;
	}
	public function access_deniedmsg()
	{ ?>
		<div class="block-error">			
			<img src="<?php echo $this->request->base;?>/img/access-denied.jpg" class="img-responsive" >
		</div>
		
	<?php }
	public function payment_method()
	{
		$payments[1] = array('id'=>'1','title'=>'Cash');
		$payments[2] = array('id'=>'2','title'=>'Cheque');
		return $payments;
	}
	public function get_payment_method($payment_id)
	{
		$pay_mathod = $this->payment_method($payment_id);
		return $pay_mathod[$payment_id]['title'];
	}
	
	public function get_asset_expense($asset_id)
	{
		$maint_tbl = TableRegistry::get("erp_assets_maintenance");
		$expenses = $maint_tbl->find()->where(["asset_id"=>$asset_id])->hydrate(false)->toArray();
		$total = 0;
		if(!empty($expenses))
		{		
			foreach($expenses as$expense)
			{
				$total += $expense["expense_amount"];
			}
			return $total;
		}else{
			return "NA";
		}
	}
	
	public function get_pr_materials($pid,$project_id = null)
	{
		$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		$data = $mt_tbl->find()->where(["pr_id"=>$pid,"approved"=>0,"show_in_purchase"=>0])->hydrate(false)->toArray();
		
		if(!empty($data))
		{
			$table = "<table class='table-bordered' style='width:100%;border:0;'>";
			foreach($data as $row)
			{ 
				if(is_numeric($row['material_id']) && $row['material_id'] != 0)
				{
					$mt = $this->get_material_title($row['material_id']);
					$brnd = $this->get_brandname($row['brand_id']);
					$unit = $this->get_items_units($row['material_id']);
					$current_balance = bcdiv($this->get_current_stock($project_id,$row['material_id']),1,3);
					$min_stock_level = $this->getmaterialminstocklevel($project_id,$row["material_id"]);
				}
				else
				{
					$mt = $row['material_name'];
					$brnd = $row['brand_name'];
					$unit = $row['static_unit'];
					$current_balance = "";
					$min_stock_level = "";
				}
				$table .= "<tr>
				
				<td>{$mt}</td>
				<td>{$brnd}</td>
				<td>{$current_balance}</td>
				<td>{$min_stock_level}</td>
				<td>{$row['quantity']}</td>
				<td>{$unit}</td>
				<td>{$this->get_date($row['delivery_date'])}</td>";
				// <td>
					// <select class='purchase_mod'>
						// <option value='central'>Central</option>
						// <option value='local'>Local</option>
					// </select>
				// </td>
				$user_id = $this->request->session()->read('user_id');
				$role = $this->get_user_role($user_id);
				if($this->retrive_accessrights($role,'editpreparepr')==1 || $this->retrive_accessrights($role,'deletepr')==1 || $this->retrive_accessrights($role,'previewpr')==1)
				{
					$table .= "<td>";
					if($this->retrive_accessrights($role,'editpreparepr')==1)
					{
						$table .= "<a href='{$this->request->base}/Inventory/editpreparepr/{$pid}/{$project_id}' class='btn btn-sm btn-success'>Edit</a>";
					}
					if($this->retrive_accessrights($role,'previewpr')==1)
					{
						$table .= "<a href='{$this->request->base}/Inventory/previewpr/{$pid}/{$project_id}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
					}
					if($this->retrive_accessrights($role,'deletepr')==1)
					{
						$table .= "<a href='{$this->request->base}/Inventory/deletepr/{$pid}/{$row['pr_material_id']}' class='btn btn-sm btn-danger'>Delete</a>";
					}
					$table .= "</td>";
				}
					
				if($this->retrive_accessrights($role,'approvepralert_inv')==1)
				{	
						$table .="<td>";
					// if($row['is_custom'])
					// {
						// $table .= "<a href='{$this->request->base}/Inventory/printpr/{$row['pr_id']}' class='btn btn-info' id='print_this' target='_blank'><i class='icon-print'></i> Print</a>";
					// }
					// else
					// {
						$table .= "<div class='checkbox'>
							<label><input type='checkbox' value='{$row['pr_material_id']}' name='approved_list[]'/></label>
						</div>";
					//}
					$table .= "</td>";
				}
				
				$table .= "<input type='hidden' name='mcode_{$row['pr_material_id']}' value='{$this->get_material_item_code_bymaterialid($row['material_id'])}'>
				<input type='hidden' name='mtitle_{$row['pr_material_id']}' value='{$this->get_material_title($row['material_id'])}'>
				<input type='hidden' name='brand_id_{$row['pr_material_id']}' value='{$row['brand_id']}'>
				<input type='hidden' name='brand_name_{$row['pr_material_id']}' value='{$this->get_brandname($row['brand_id'])}'>
				<input type='hidden' name='quantity_{$row['pr_material_id']}' value='{$row['quantity']}'>
				<input type='hidden' name='unit_{$row['pr_material_id']}' value='{$this->get_items_units($row['material_id'])}'>
				<input type='hidden' name='del_date_{$row['pr_material_id']}' value='{$row['delivery_date']->format('Y-m-d')}'>
				<input type='hidden' name='pr_mid_{$row['pr_material_id']}' value='{$row['pr_material_id']}'>
				</tr>";
			}
			$table .= "</table>";
		}else{
			return "None";
		}		
		return $table;		
	}
	
	// public function get_purchase_pr_materials($pid,$i=null,$project_id=null)
	// {
	// 	$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
	// 	$data = $mt_tbl->find()->where(["pr_id"=>$pid,"approved"=>0,"show_in_purchase"=>1])->hydrate(false)->toArray();
	// 	if(!empty($data))
	// 	{
	// 		foreach($data as $row)
	// 		{ 
	// 			$date= date('Y-m-d');
	// 			$fetchedDate = strtotime($row['due_date']);
   	// 			$date_formated = date('Y-m-d',$fetchedDate);
	// 			// echo $date_formated;
	// 			// echo $date;die;
	// 			if($row['approved'] == 0 && $date_formated < $date) {
	// 				// echo "Hello";die;
	// 				$table = "<table class='table-bordered' style='background-color:grey;' style='width:100%;border:0;'>";
	// 			}else {
	// 				$table = "<table class='table-bordered' style='width:100%;border:0;'>";
	// 			}
	// 			$user_id = $this->request->session()->read('user_id');
	// 			$role = $this->get_user_role($user_id);
			
	// 			if(is_numeric($row['material_id']) && $row['material_id'] != 0)
	// 			{
	// 				$mcode = $this->get_material_item_code_bymaterialid($row['material_id']);
	// 				$mt = $this->get_material_title($row['material_id']);
	// 				$brnd = $this->get_brandname($row['brand_id']);
	// 				$unit = $this->get_items_units($row['material_id']);
	// 			}
	// 			else
	// 			{
	// 				$mcode = $row['m_code'];
	// 				$mt = $row['material_name'];
	// 				$brnd = $row['brand_name'];
	// 				$unit = $row['static_unit'];
	// 			}
				
	// 			$table .= "<tr>
	// 			<td>{$mcode}</td>
	// 			<td>{$mt}</td>
	// 			<td>{$brnd}</td>
	// 			<td>{$row['quantity']}</td>
	// 			<td>{$unit}</td>
	// 			<td>{$row['delivery_date']->format('d-m-Y')}</td>
	// 			<td style='width:10px;'>";
	// 				if($this->retrive_accessrights($role,'approvedpr')==1)
	// 				{
	// 					$table .="<a href='{$this->request->base}/Inventory/previewprapprove/{$pid}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
	// 				}
	// 				if($this->retrive_accessrights($role,'editratepralert')==1){
	// 					if($row['material_id'])
	// 					{
	// 						$table .="<button pmid='{$row['pr_material_id']}' mid='{$row['material_id']}' bid='{$row['brand_id']}' qty='{$row['quantity']}' data-toggle='modal' data-target='#load_modal' class='btn btn-success editprmaterial'>Edit</button>";
	// 					}
	// 				}
									
	// 				if($this->retrive_accessrights($role,'deletepralert')==1){
	// 					$table .= "<a href='{$this->request->base}/Purchase/unapprovepr/{$row['pr_material_id']}' target='_blank' class='btn btn-sm btn-danger'>Delete</a>";
	// 				}
	// 			$table .= "</td><td>";
	// 			$checked = ($row['purchase_first_approve'] == 1)?'checked':'';
	// 			// $first_disabled = ($row['purchase_first_approve'] == 1)?'disabled':'';
				
	// 			if($this->retrive_accessrights($role,'purchaseprfirstapprove')==1){
	// 			$table .= "<div class='checkbox'>
	// 								<label><input type='checkbox' data-toggle='modal' data-target='#load_modal2' class='first_approve' {$checked} value='{$pid}' name='first_approved_list[]'/> </label>
	// 								</div>";
	// 			}					
	// 			$table .= "</td><td>";
	// 			if(!$row['material_id'])
	// 			{
	// 				//$table .= "<a href='{$this->request->base}/Inventory/printpr/{$row['pr_id']}' class='btn btn-info' id='print_this' target='_blank'><i class='icon-print'></i> Print</a>";
	// 				// if($role == 'erphead' || $role == 'erpmanager' || $role == 'erpoperator' || $role == 'md' || $role == 'ceo')
	// 				// {
	// 					// $table .= "<a href='{$this->request->base}/Purchase/removemanualpr/{$row['pr_material_id']}' class='btn btn-success' id='done_manual'>Done</a>";
	// 				// }
	// 			}
	// 			else
	// 			{
					
	// 				// $material_code = $this->get_material_code($row['material_id']);
	// 				// if($material_code != 17){
	// 					if($row['po_completed'] != 3){
	// 							$first_disabled = ($row['purchase_first_approve'] == 1)?'':'disabled';
	// 							if($this->retrive_accessrights($role,'approvepralert')==1){
								
	// 								$table .= "<div class='checkbox'>
	// 								<label><input type='checkbox' {$first_disabled} value='{$row['pr_material_id']}' name='approved_list[]'/> </label>
	// 								</div>";
	// 							}
	// 							//$table .= "<a href='{$this->request->base}/Inventory/printpr/{$row['pr_id']}' class='btn btn-info' id='print_this' target='_blank'><i class='icon-print'></i> Print</a>";
	// 							// if($role == 'erphead' || $role == 'erpmanager' || $role == 'erpoperator' || $role == 'md' || $role == 'ceo')
	// 							// {
	// 								// $table .= "<a href='{$this->request->base}/Purchase/removemanualpr/{$row['pr_material_id']}' class='btn btn-success' id='done_manual'>Done</a>";
	// 							// }
							
	// 					}
	// 				// }
	// 				else{
	// 					//$table .= "<a href='{$this->request->base}/Inventory/printpr/{$row['pr_id']}' class='btn btn-info' id='print_this' target='_blank'><i class='icon-print'></i> Print</a>";
						
	// 				}
					
	// 			}
	// 			$table .= "</td><td>";
	// 			if($this->retrive_accessrights($role,'donepralert')==1){
	// 				// $table .= "<a href='{$this->request->base}/Purchase/removemanualpr/{$row['pr_material_id']}' class='btn btn-success' id='done_manual'>Done</a>";
	// 				$table .= "<button type='button' data-toggle='modal' data-target='#load_modal' class='btn btn-success done_remarks' pr_detail_id='{$row['pr_material_id']}'>Done</button>";
	// 			}
						
	// 				if($this->retrive_accessrights($role,'exportpralert')==1){
	// 				$table .= "</br></br><a href='{$this->request->base}/purchase/potoxls/{$row['pr_id']}' class='btn btn-info'>Export</a>";
	// 			}
	// 			if($project_id != null && is_numeric($row['material_id']))
	// 			{	
	// 				if($this->retrive_accessrights($role,'managepralert')==1){
	// 					$table .= "</br></br><button type='button' data-toggle='modal' data-target='#load_modal1' class='btn btn-info viewmodal' m_id='{$row['material_id']}' p_id='{$project_id}'>Manage Stock</button>";
	// 				}
	// 			}
	// 			if($this->retrive_accessrights($role,'remarkpralert')==1){
	// 				$table .= "</br></br><button type='button' data-toggle='modal' data-target='#load_modal' class='btn btn-info add_remarks' pr_detail_id='{$row['pr_material_id']}' m_id='{$row['material_id']}' p_id='{$project_id}'>Remark</button>";
	// 			}
	// 			$table .= "</td><td>{$row['purchase_remarks']}</td>
	// 			<input type='hidden' name='mcode_{$row['pr_material_id']}' value='{$this->get_material_item_code_bymaterialid($row['material_id'])}'>
	// 			<input type='hidden' name='mtitle_{$row['pr_material_id']}' value='{$this->get_material_title($row['material_id'])}'>
	// 			<input type='hidden' name='brand_id_{$row['pr_material_id']}' value='{$row['brand_id']}'>
	// 			<input type='hidden' name='brand_name_{$row['pr_material_id']}' value='{$this->get_brandname($row['brand_id'])}'>
	// 			<input type='hidden' name='quantity_{$row['pr_material_id']}' value='{$row['quantity']}'>
	// 			<input type='hidden' name='unit_{$row['pr_material_id']}' value='{$this->get_items_units($row['material_id'])}'>
	// 			<input type='hidden' name='del_date_{$row['pr_material_id']}' value='{$row['delivery_date']->format('Y-m-d')}'>
	// 			<input type='hidden' name='pr_mid_{$row['pr_material_id']}' value='{$row['pr_material_id']}'>
	// 			<script>
	// 				var i = {$i};
	// 				var approved_date = '".((strtotime($row['approved_date']) != '') ? date('d-m-Y',strtotime($row['approved_date'])) :'NA' )."';
	// 				var approved_time = '".((strtotime($row['approved_date']) != '') ? date('H:i',strtotime($row['approved_date'])) :'NA' )."';
	// 				$('#app_date_'+i).html(approved_date);
	// 				$('#app_time_'+i).html(approved_time);
	// 			</script>
				
	// 			</tr>";
	// 		}
	// 		$table .= "</table>";
	// 	}else{
	// 		return "None";
	// 	}		
	// 	return $table;			
	// }

	public function get_purchase_pr_materials($pid,$i=null,$project_id=null,$prDate) {
		$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		$data = $mt_tbl->find()->where(["pr_id"=>$pid,"approved"=>0,"show_in_purchase"=>1])->hydrate(false)->toArray();
		if(!empty($data)) {
			// debug($data['due_date']);
			$table = "<table class='table-bordered' style='width:100%;border:0;'>";
			$user_id = $this->request->session()->read('user_id');
			$role = $this->get_user_role($user_id);
			foreach($data as $row)
			{
				$date= date('Y-m-d');
				$fetchedDate = strtotime($row['due_date']);
   				$date_formated = date('Y-m-d',$fetchedDate);
				if($row['approved'] == 0 && $date_formated < $date) {
					$table .= "<table class='table-bordered' style='background-color:grey;' style='width:100%;border:0;'>";
				}else {
					$table .= "<table class='table-bordered' style='width:100%;border:0;'>";
				}
				if(is_numeric($row['material_id']) && $row['material_id'] != 0) {
					$mcode = $this->get_material_item_code_bymaterialid($row['material_id']);
					$mt = $this->get_material_title($row['material_id']);
					$brnd = $this->get_brandname($row['brand_id']);
					$unit = $this->get_items_units($row['material_id']);
				}else {
					$mcode = $row['m_code'];
					$mt = $row['material_name'];
					$brnd = $row['brand_name'];
					$unit = $row['static_unit'];
				}
				$due_date = isset($row['due_date'])?$row['due_date']->format('d-m-Y'):"NA";
				$table .= "<tr>
				<td>{$mcode}</td>
				<td>{$mt}</td>
				<td>{$brnd}</td>
				<td>{$row['quantity']}</td>
				<td>{$unit}</td>
				<td>{$due_date}</td>
				<td style='width:10px;'>";
					if($this->retrive_accessrights($role,'approvedpr')==1) {
						$table .="<a href='{$this->request->base}/Inventory/previewprapprove/{$pid}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
					}
					// if($this->retrive_accessrights($role,'editratepralert')==1) {
					// 	if($row['material_id']) {
					// 		$table .="<button pmid='{$row['pr_material_id']}' mid='{$row['material_id']}' bid='{$row['brand_id']}' qty='{$row['quantity']}' data-toggle='modal' data-target='#load_modal' class='btn btn-success editprmaterial'>Edit</button>";
					// 	}
					// }
				if($row['purchase_first_approve'] == 1) {
					if($this->retrive_accessrights($role,'deletepralert')==1) {
						$table .= "<a href='{$this->request->base}/Purchase/unapprovepr/{$row['pr_material_id']}' target='_blank' class='btn btn-sm btn-danger'>Delete</a>";
					}
				}				
					
				$table .= "</td><td>";
				$checked = ($row['purchase_first_approve'] == 1)?'checked':'';
				$first_disabled = ($row['purchase_first_approve'] == 1)?'disabled':'';
				if($this->retrive_accessrights($role,'purchaseprfirstapprove')==1){
				if($row['purchase_first_approve'] == 1) {
					$table .= "<div class='checkbox'>
					<label><input type='checkbox' data-toggle='modal' data-target='#load_modal2' class='first_approve' {$first_disabled} {$checked} value='{$pid}' name='first_approved_list[]'/> </label>
									</div>";
				}else {
					$table .= "<div class='checkbox'>
					<label><input type='checkbox' data-toggle='modal' data-target='#load_modal2' class='first_approve' {$checked} value='{$pid}' name='first_approved_list[]'/> </label>
									</div>";
				}
				
				}					
				$table .= "</td><td>";
				if(!$row['material_id'])
				{
					//$table .= "<a href='{$this->request->base}/Inventory/printpr/{$row['pr_id']}' class='btn btn-info' id='print_this' target='_blank'><i class='icon-print'></i> Print</a>";
					// if($role == 'erphead' || $role == 'erpmanager' || $role == 'erpoperator' || $role == 'md' || $role == 'ceo')
					// {
						// $table .= "<a href='{$this->request->base}/Purchase/removemanualpr/{$row['pr_material_id']}' class='btn btn-success' id='done_manual'>Done</a>";
					// }
				}
				else
				{
					
					// $material_code = $this->get_material_code($row['material_id']);
					// if($material_code != 17){
						if($row['po_completed'] != 3){
								$first_disabled = ($row['purchase_first_approve'] == 1)?'':'disabled';
								if($this->retrive_accessrights($role,'approvepralert')==1){
								
									$table .= "<div class='checkbox'>
									<label><input type='checkbox' {$first_disabled} value='{$row['pr_material_id']}' name='approved_list[]'/> </label>
									</div>";
								}
								//$table .= "<a href='{$this->request->base}/Inventory/printpr/{$row['pr_id']}' class='btn btn-info' id='print_this' target='_blank'><i class='icon-print'></i> Print</a>";
								// if($role == 'erphead' || $role == 'erpmanager' || $role == 'erpoperator' || $role == 'md' || $role == 'ceo')
								// {
									// $table .= "<a href='{$this->request->base}/Purchase/removemanualpr/{$row['pr_material_id']}' class='btn btn-success' id='done_manual'>Done</a>";
								// }
							
						}
					// }
					else{
						//$table .= "<a href='{$this->request->base}/Inventory/printpr/{$row['pr_id']}' class='btn btn-info' id='print_this' target='_blank'><i class='icon-print'></i> Print</a>";
						
					}
					
				}
				$table .= "</td><td>";
				if($this->retrive_accessrights($role,'donepralert')==1){
					// $table .= "<a href='{$this->request->base}/Purchase/removemanualpr/{$row['pr_material_id']}' class='btn btn-success' id='done_manual'>Done</a>";
					$table .= "<button type='button' data-toggle='modal' data-target='#load_modal' class='btn btn-success done_remarks' pr_detail_id='{$row['pr_material_id']}'>Done</button>";
				}
						
				// 	if($this->retrive_accessrights($role,'exportpralert')==1){
				// 	$table .= "</br></br><a href='{$this->request->base}/purchase/potoxls/{$row['pr_id']}' class='btn btn-info'>Export</a>";
				// }
				if($project_id != null && is_numeric($row['material_id']))
				{	
					if($this->retrive_accessrights($role,'managepralert')==1){
						$table .= "</br></br><button type='button' data-toggle='modal' data-target='#load_modal1' class='btn btn-info viewmodal' m_id='{$row['material_id']}' p_id='{$project_id}'>Manage Stock</button>";
					}
				}
				if($this->retrive_accessrights($role,'remarkpralert')==1){
					$table .= "</br></br><button type='button' data-toggle='modal' data-target='#load_modal' class='btn btn-info add_remarks' pr_detail_id='{$row['pr_material_id']}' m_id='{$row['material_id']}' p_id='{$project_id}'>Remark</button>";
				}
				
				$table .= "</td><td>{$row['purchase_remarks']}</td>
				<input type='hidden' name='mcode_{$row['pr_material_id']}' value='{$this->get_material_item_code_bymaterialid($row['material_id'])}'>
				<input type='hidden' name='mtitle_{$row['pr_material_id']}' value='{$this->get_material_title($row['material_id'])}'>
				<input type='hidden' name='brand_id_{$row['pr_material_id']}' value='{$row['brand_id']}'>
				<input type='hidden' name='brand_name_{$row['pr_material_id']}' value='{$this->get_brandname($row['brand_id'])}'>
				<input type='hidden' name='quantity_{$row['pr_material_id']}' value='{$row['quantity']}'>
				<input type='hidden' name='unit_{$row['pr_material_id']}' value='{$this->get_items_units($row['material_id'])}'>
				<input type='hidden' name='del_date_{$row['pr_material_id']}' value='{$row['delivery_date']->format('Y-m-d')}'>
				<input type='hidden' name='pr_mid_{$row['pr_material_id']}' value='{$row['pr_material_id']}'>
				<script>
					var i = {$i};
					var approved_date = '".((strtotime($row['approved_date']) != '') ? date('d-m-Y',strtotime($row['approved_date'])) :'NA' )."';
					var approved_time = '".((strtotime($row['approved_date']) != '') ? date('H:i',strtotime($row['approved_date'])) :'NA' )."';
					$('#app_date_'+i).html(approved_date);
					$('#app_time_'+i).html(approved_time);
				</script>
				
				</tr>";
			}
			$table .= "</table>";
		}else{
			return "None";
		}		
		return $table;			
	}
	
	public function get_pr_staus_materials($data,$pid,$i=null,$project_id=null)
	{
		
		// $mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		// $data = $mt_tbl->find()->where(["pr_id"=>$pid,"approved"=>0,"show_in_purchase"=>1])->hydrate(false)->toArray();
		if(!empty($data))
		{
			$table = "<table class='table-bordered' style='width:100%;border:0;'>";
			$user_id = $this->request->session()->read('user_id');
			$role = $this->get_user_role($user_id);
			foreach($data as $row)
			{ 
				if(is_numeric($row['material_id']) && $row['material_id'] != 0)
				{
					$mcode = $this->get_material_item_code_bymaterialid($row['material_id']);
					$mt = $this->get_material_title($row['material_id']);
					$brnd = $this->get_brandname($row['brand_id']);
					$unit = $this->get_items_units($row['material_id']);
				}
				else
				{
					$mcode = $row['m_code'];
					$mt = $row['material_name'];
					$brnd = $row['brand_name'];
					$unit = $row['static_unit'];
				}
				
				$table .= "<tr>
				<td>{$mcode}</td>
				<td>{$mt}</td>
				<td>{$brnd}</td>
				<td>{$row['quantity']}</td>
				<td>{$unit}</td>
				<td>".date("d-m-Y",strtotime($row['delivery_date']))."</td>
				<td style='width:10px;'>";
					if($this->retrive_accessrights($role,'previewpr')==1)
					{
						$table .="<a href='{$this->request->base}/Inventory/previewpr/{$pid}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
						$table .= "</br></br><a href='{$this->request->base}/purchase/potoxls/{$row['pr_id']}' class='btn btn-info'>Export</a>";
					}
				$table .= "</td>";
				if(!$row['material_id'])
				{
					//$table .= "<div style='color:red'><b>Pending</b></div>";
				}
				else
				{
					$material_code = $this->get_material_code($row['material_id']);
					if($material_code != 17){
						if($row['po_completed'] != 3){
								if($this->project_alloted($role)==1){ 
									//$table .= "<div style='color:red'><b>Pending</b></div>";
								}
							
						}else{
							//$table .= "<div style='color:blue'><b>Pending PO Approve<b></div>";
						}
					}
					else{
						//$table .= "<div style='color:red'><b>Pending</b></div>";
					}
					
				}
				$table .= "<td>{$row['purchase_remarks']}</td>";
				// $table .= "<td>";
				// if($role == 'erphead' || $role == 'erpmanager' || $role == 'erpoperator' || $role == 'md' || $role == 'ceo')
				// {
					// $table .= "<a href='{$this->request->base}/Purchase/removemanualpr/{$row['pr_material_id']}' class='btn btn-success' id='done_manual'>Done</a>";
				// }
						
				// if($role == 'erphead' || $role == 'erpmanager' || $role == 'erpoperator' ||$role == 'md' ||$role == 'ceo' || $role == 'purchasehead' ||$role == 'purchasemanager' ||$role == 'deputymanagerelectric')
				// {
					// $table .= "</br></br><a href='{$this->request->base}/purchase/potoxls/{$row['pr_id']}' class='btn btn-info'>Export</a>";
				// }
				// if($project_id != null && is_numeric($row['material_id']))
				// {
					// $table .= "</br></br><button type='button' data-toggle='modal' data-target='#load_modal' class='btn btn-info viewmodal' m_id='{$row['material_id']}' p_id='{$project_id}'>Manage Stock</button>";
				// }
				// $table .= "</td>";
				$table .= "
				
				<script>
					var i = {$i};
					var approved_date = '".((strtotime($row['approved_date']) != '') ? date('d-m-Y',strtotime($row['approved_date'])) :'NA' )."';
					var approved_time = '".((strtotime($row['approved_date']) != '') ? date('H:i',strtotime($row['approved_date'])) :'NA' )."';
					$('#app_date_'+i).html(approved_date);
					$('#app_time_'+i).html(approved_time);
				</script>
				
				</tr>";
			}
			$table .= "</table>";
		}else{
			return "None";
		}		
		return $table;			
	}
	
	public function get_po_materials($pid)
	{
		$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		$data = $mt_tbl->find()->where(["pr_id"=>$pid,"approved"=>1])->hydrate(false)->toArray();
		if(!empty($data))
		{
			$table = "<table class='table-bordered' style='width:100%;border:0;'>";
			foreach($data as $row)
			{
				$table .= "<tr>
				<td>{$this->get_material_item_code_bymaterialid($row['material_id'])}</td>
				<td>{$this->get_material_title($row['material_id'])}</td>
				<td>{$this->get_brandname($row['brand_id'])}</td>
				<td>{$row['quantity']}</td>
				<td>{$this->get_items_units($row['material_id'])}</td>
				<td>{$row['delivery_date']->format('Y-m-d')}</td>
				<td>Central</td>
				<td>
					<a href='{$this->request->base}/Inventory/previewpr/{$pid}' target='_blank' class='btn btn-sm btn-primary'>View</a>
				</td>
				<td>
					<div class='checkbox'>
						<label><input type='checkbox' value='{$row['material_id']}' name='approved_list[]'/> </label>
					</div>
				</td>
				<input type='hidden' name='mcode_{$row['material_id']}' value='{$this->get_material_item_code_bymaterialid($row['material_id'])}'>
				<input type='hidden' name='mtitle_{$row['material_id']}' value='{$this->get_material_title($row['material_id'])}'>
				<input type='hidden' name='brand_id_{$row['material_id']}' value='{$row['brand_id']}'>
				<input type='hidden' name='brand_name_{$row['material_id']}' value='{$this->get_brandname($row['brand_id'])}'>
				<input type='hidden' name='quantity_{$row['material_id']}' value='{$row['quantity']}'>
				<input type='hidden' name='unit_{$row['material_id']}' value='{$this->get_items_units($row['material_id'])}'>
				<input type='hidden' name='del_date_{$row['material_id']}' value='{$row['delivery_date']->format('Y-m-d')}'>
				<input type='hidden' name='pr_mid_{$row['material_id']}' value='{$row['pr_material_id']}'>
				</tr>";
			}
			$table .= "</table>";
		}else{
			return "None";
		}		
		return $table;		
	}
	
	public function get_edit_po($project_id)
	{		
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$pod_tbl = TableRegistry::get("erp_inventory_po_detail");
		$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		if($project_id != "")
		{ 		
			/* $data = $po_tbl->find()->where(["project_id"=>$project_id])->group(['project_id','po_no'])->hydrate(false)->toArray(); */
			$data = $po_tbl->find()->where(["project_id"=>$project_id])->group(['project_id','po_no'])->hydrate(false)->toArray();
		}else{
			/* $data = $po_tbl->find()->group(['project_id','po_no'])->hydrate(false)->toArray(); */
			$data = $po_tbl->find()->where(["approved_status"=>0])->group(['project_id','po_no'])->hydrate(false)->toArray();
		}
		//debug($data);die;
		$i = 0;
		$row='';
		if(!empty($data))
		{
			foreach($data as $material)
			{
				//$po_id = $post["selected_po_id_{$material['id']}"];
				//$pr_id = $po_tbl->find()->where(["po_id"=>$post["selected_po_id_{$material['id']}"]])->select(["pr_id"])->hydrate(false)->toArray();
				$row .= '<tr class="cpy_row">
							<td>'.$this->get_material_item_code_bymaterialid($material['material_id']).'</td>
							<td>'.$this->get_material_title($material['material_id']).'	<input type="hidden" name="material[material_id][]" readonly = "true" value="'.$material["material_id"].'" id="material_id_'.$i.'"/></td>
							<td><input type="hidden" name="material[brand_id][]" value="'.$material["brand_id"].'" id="brand_id_'.$i.'"/>'.$this->get_brandname_by_po_material($row['pr_id'],$material['material_id']).'</td>
							<td><input type="text" name="material[quantity][]" value="'.$material['quantity'].'" id="quantity_'.$i.'"/></td>
							<td><input type="text" name="material[actual_qty][]" class="actualy_qty" value="'.$material["actual_qty"].'" data-id="'.$i.'" id="actual_qty_'.$i.'"/></td>
							<td><input type="text" name="material[difference_qty][]" readonly="true" value="'.$material["difference_qty"].'" id="difference_qty_'.$i.'"/></td>
							<td><input type="text" name="material[delivery_date][]" readonly="true" value="'.$materials['delivery_date'].'" id="delivery_date_'.$i.'"/></td>										
								 <input type="hidden" name="po_mid[]" value="'.$material["grndetail_id"].'">
							</td>
						 </tr>';
						
					$i++;
			}
		}			
		return $row;
	}
	
	public function get_po_alerts($project_id,$po_type)
	{		
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$pod_tbl = TableRegistry::get("erp_inventory_po_detail");
		$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		
		#################### New Query #########################################
		$or = array();
		
		$or["erp_inventory_po.project_id"] = (!empty($project_id) && $project_id != "All")?$project_id:NULL;
		$or["erp_inventory_po_detail.po_type"] = (!empty($po_type) && $po_type != "All")?$po_type:NULL;
		if($role =='deputymanagerelectric')
		{
			$material_ids = $this->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$or["erp_inventory_po_detail.material_id IN"] = $material_ids;
		}
		if($or["erp_inventory_po.project_id"] == NULL)
		{
			if($this->project_alloted($role)==1)
			{
				$or["erp_inventory_po.project_id IN"] = $projects_ids;
			}
		}
		$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
		$or["erp_inventory_po_detail.approved ="] = 0;
		// debug($or);die;
		$result = $po_tbl->find()->select($po_tbl);
		$result = $result->innerjoin(
			["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
			["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
			->where($or)->select($pod_tbl)->hydrate(false)->toArray();
		// debug($result);die;
		
		$new_array = array();
		foreach($result as $retrive)
		{
			if(isset($new_array[$retrive['po_no']]))
			{
				$new_array[$retrive['po_no']]['erp_inventory_po_detail'][] = $retrive['erp_inventory_po_detail'];
			}else{
				$a = $retrive["erp_inventory_po_detail"];
				unset($retrive["erp_inventory_po_detail"]);
				$new_array[$retrive["po_no"]] = $retrive;
				$new_array[$retrive["po_no"]]['erp_inventory_po_detail'][] = $a;
			}
			
		}
		// debug($new_array);die;
		$data = $new_array;
		#################### New Query #########################################		
				
		
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		############################## Accessright ############################################
		$editpreparepo = $this->retrive_accessrights($role,'editpreparepo');
		$approvepo = $this->retrive_accessrights($role,'approvepo');
		$deletepurchasepoalert = $this->retrive_accessrights($role,'deletepurchasepoalert');
		$verifypurchasepoalert = $this->retrive_accessrights($role,'verifypurchasepoalert');
		$approve1purchasepoalert = $this->retrive_accessrights($role,'approve1purchasepoalert');
		$approve2purchasepoalert = $this->retrive_accessrights($role,'approve2purchasepoalert');
		$rateHistory = $this->retrive_accessrights($role,'rateHistory');
		############################## Accessright ############################################
		if(!empty($data))
		{
			$table ="";	$x = 1;
			foreach($data as $row)
			{ 
				
				$po_type1=$row['po_purchase_type'];
				if($po_type1 == "po")
				{
					$type_name = "PO";
				}elseif($po_type1 == "manual_po")
				{
					$type_name = "Manual PO";
				}elseif($po_type1 == "local_po"){
					$type_name = "Local PO";
				}
				foreach($row['erp_inventory_po_detail'] as $materials) {
					$startdate =$row['po_date'];
					$expire = strtotime($startdate. ' + 3 days');
					$today = strtotime("today midnight");
					if($materials['approved'] == 0 && $today >= $expire) {
						$table .= "<tr class='data_row' id='dd_{$x}' style='background-color: gray;'>";	
					}else {
						$table .= "<tr class='data_row' id='dd_{$x}'>";
					}
				}
				$table .= "				
				<td>{$this->get_projectname($row['project_id'])}</td>
				<td>{$row['po_no']}</td>
				<td>{$type_name}</td>
				<td>{$this->get_date($row['po_date'])}</td>
				<td>{$row['po_time']}</td>
			    <td> ";	/*  <form action='setpoapprove' method='post'> preparegrn		 */
				$first_disabled = '';
				$first_checked = '';
				$verify_disabled = '';
				$verify_checked = '';
				$second_disabled = '';
				if(!empty($row['erp_inventory_po_detail']))
				{
					$table .="<table class='table-bordered' style='width:100%'>";
					foreach($row['erp_inventory_po_detail'] as $materials)
					{
						
					$firstApproved = $materials['first_approved'];
					$first_checked = ($materials['first_approved']==1) ? 'checked' : '';
					$first_disabled = ($materials['verified']==0 || $materials['first_approved']==1) ? 'DISABLED' : '';
					
					$verify_checked = ($materials['verified']==1) ? 'checked' : '';
					$verify_disabled = ($materials['verified']==1) ? 'DISABLED' : '';
					
					$second_disabled = ($materials['first_approved']==1) ? '' : 'DISABLED';
						
					$mt = is_numeric($materials['material_id'])?$this->get_material_title($materials['material_id']):$materials['material_id'];
					$brnd = is_numeric($materials['brand_id'])?$this->get_brandname($materials['brand_id']):$materials['brand_id'];
					$unit_name = is_numeric($materials['material_id'])?
					$this->get_items_units($materials['material_id']):$materials['static_unit'];
					
						$table .= "<tr>							
													
							<td>{$this->get_vendor_name($row['vendor_userid'])}</td>
							
							<td>{$mt}</td>
							<td>{$brnd}</td>
							<td>{$materials['quantity']}</td>
							<td>{$unit_name}</td>
							<td>{$materials['single_amount']}</td>
							<td>{$materials['amount']}</td>";
							// <td>Central<br>Purchase</td>
							if($rateHistory == 1) {
							$table .= "<td>";
							
								$table .= "<button type='button' data-toggle='modal' data-target='#load_modal' class='btn btn-info rate_history' po_detail_id='{$row["po_id"]}' m_id='{$materials["material_id"]}' p_id='{$row['project_id']}'>Rate History</button></td>";
							}
						$table .= "</tr>";
					}
					$table .= "</table>";
				}
				else{
					$table .= "None Record Found.
					<script>var size = $('#dd_'+".$x.").remove();</script>
					";
				}
				$table .= "<td>";
				// debug($materials);
				
					if($firstApproved == 0) {
						if($editpreparepo==1)
						{
							$table .= "<a href='{$this->request->base}/inventory/editpreparepo/{$row["po_id"]}' id='edit-btn{$row['po_id']}' class='btn btn-sm btn-success'>Edit</a>&nbsp";
						}
					}else {
						if($editpreparepo==1)
						{
							$table .= "<a style='display:none;' href='{$this->request->base}/inventory/editpreparepo/{$row["po_id"]}' id='edit-btn{$row['po_id']}' class='btn btn-sm btn-success'>Edit</a>&nbsp";
						}
					}
					
					if($approvepo==1)
					{
						if(date('Y-m-d',strtotime($row['po_date'])) > date('Y-m-d',strtotime('01-07-2017')))
						{
							$table .= "<a href='{$this->request->base}/inventory/previewpo2/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
						}
						else
						{
							$table .= "<a href='{$this->request->base}/inventory/previewpo/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
						}
					}	
				if($firstApproved == 0) {	
					if($deletepurchasepoalert==1)
					{
						// $table .= "<a href='{$this->request->base}/purchase/deletepurchasepoalert/{$materials["pr_mid"]}/{$materials["id"]}' class='btn btn-sm btn-danger'>Delete</a></td>";
						$table .= "<a href='{$this->request->base}/purchase/deletepurchasepoalert/{$materials["pr_mid"]}/{$row['po_id']}' class='btn btn-sm btn-danger' id='deletepoalert'>Delete</a></td>";
						
					}
				}else {
					if($deletepurchasepoalert==1)
					{
						$table .= "<a href='{$this->request->base}/purchase/deletepurchasefirstpoalert/{$materials["pr_mid"]}/{$materials["id"]}/{$row['po_id']}' class='btn btn-sm btn-danger' id='deletefirstpurchase'>Delete</a>";
					}
				}

				// if($firstApproved == 0) {	
				// 	if($deletepurchasepoalert==1)
				// 	{
				// 		$table .= "<a href='{$this->request->base}/purchase/deletepurchasepoalert/{$row['po_id']}' data-id = '{$row['po_id']}' class='btn btn-sm btn-danger deletepoalert' id='deletepoalert'>Delete</a></td>";
				// 	}
				// }else {
				// 	if($deletepurchasepoalert==1)
				// 	{
				// 		$table .= "<a href='{$this->request->base}/purchase/deletepurchasefirstpoalert/{$row['po_id']}' data-id= '{$row['po_id']}' class='btn btn-sm btn-danger deletefirstpurchase' id='deletefirstpurchase'>Delete</a>";
				// 	}
				// }
				
				if($verifypurchasepoalert==1)
				{
					$table .= "<td>
						<div class='checkbox'>
							<label><input type='checkbox' value='{$row["po_id"]}' {$verify_disabled} {$verify_checked} name='verify_list[]'/> </label>
						</div>
					</td>";
				}else {
					$table .= "<td>
						<div class='checkbox'>
							<label><input type='checkbox' disabled='true' value='{$row["po_id"]}' {$verify_disabled} {$verify_checked} name='verify_list[]'/> </label>
						</div>
					</td>";
				}
				if($approve1purchasepoalert==1)
				{
					$table .= "<td>
						<div class='checkbox'>
							<label><input type='checkbox' class='approved_list1' id='approved_list1' value='{$row["po_id"]}' {$first_disabled} {$first_checked} name='approved_list1[]'/> </label>
						</div>
					</td>";
				}else {
					$table .= "<td>
						<div class='checkbox'>
							<label><input type='checkbox' disabled='true' class='approved_list1' id='approved_list1' value='{$row["po_id"]}' {$first_disabled} {$first_checked} name='approved_list1[]'/> </label>
						</div>
					</td>";
				}
				
				if($approve2purchasepoalert==1)
				{
					$table .= "<td>
						<div class='checkbox'>
							<label><input type='checkbox' {$second_disabled} value='{$row["po_id"]}' name='approved_list[]'/> </label>
						</div>
					</td>";
				}else {
					$table .= "<td>
						<div class='checkbox'>
							<label><input type='checkbox' {$second_disabled} value='{$row["po_id"]}' name='approved_list[]'/> </label>
						</div>
					</td>";
				}
				$table .= "<td> <input type='button' name='approve_po' po_no='{$row['po_no']}' value='Go' class='btn btn-success go_btn'> 	</td></td>
				</tr>";
				$x++;
			}		
			return $table;
		}
	}

	// public function get_po_alerts($project_id,$po_type)
	// {		
	// 	$po_tbl = TableRegistry::get("erp_inventory_po");
	// 	$pod_tbl = TableRegistry::get("erp_inventory_po_detail");
	// 	$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		
	// 	$user_id = $this->request->session()->read('user_id');
	// 	$role = $this->get_user_role($user_id);
	// 	$projects_ids = $this->users_project($user_id);
		
	// 	#################### New Query #########################################
	// 	$or = array();
		
	// 	$or["erp_inventory_po.project_id"] = (!empty($project_id) && $project_id != "All")?$project_id:NULL;
	// 	$or["erp_inventory_po_detail.po_type"] = (!empty($po_type) && $po_type != "All")?$po_type:NULL;
	// 	if($role =='deputymanagerelectric')
	// 	{
	// 		$material_ids = $this->get_deputymanagerelectric_material();
	// 		$material_ids = json_decode($material_ids);
	// 		$or["erp_inventory_po_detail.material_id IN"] = $material_ids;
	// 	}
	// 	if($or["erp_inventory_po.project_id"] == NULL)
	// 	{
	// 		if($this->project_alloted($role)==1)
	// 		{
	// 			$or["erp_inventory_po.project_id IN"] = $projects_ids;
	// 		}
	// 	}
	// 	$keys = array_keys($or,"");				
	// 			foreach ($keys as $k)
	// 			{unset($or[$k]);}
	// 	$or["erp_inventory_po_detail.approved ="] = 0;
	// 	// debug($or);die;
	// 	$result = $po_tbl->find()->select($po_tbl);
	// 	$result = $result->innerjoin(
	// 		["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
	// 		["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
	// 		->where($or)->select($pod_tbl)->hydrate(false)->toArray();
	// 	// debug($result);die;
		
	// 	$new_array = array();
	// 	foreach($result as $retrive)
	// 	{
	// 		if(isset($new_array[$retrive['po_no']]))
	// 		{
	// 			$new_array[$retrive['po_no']]['erp_inventory_po_detail'][] = $retrive['erp_inventory_po_detail'];
	// 		}else{
	// 			$a = $retrive["erp_inventory_po_detail"];
	// 			unset($retrive["erp_inventory_po_detail"]);
	// 			$new_array[$retrive["po_no"]] = $retrive;
	// 			$new_array[$retrive["po_no"]]['erp_inventory_po_detail'][] = $a;
	// 		}
			
	// 	}
	// 	// debug($new_array);
	// 	$data = $new_array;
	// 	#################### New Query #########################################		
	// 	// if($role == "deputymanagerelectric")
	// 	// {
	// 		// $materials_ids = $this->get_deputymanagerelectric_material();
	// 		// $materials_ids = json_decode($materials_ids);
	// 		// $po_ids = $pod_tbl->find()->where(["material_id IN"=>$materials_ids])->select('po_id')->hydrate(false)->toArray();
	// 		// $po_ids_array = array();
	// 		// foreach($po_ids as $po_id)
	// 		// {
	// 			// $po_ids_array[] = $po_id['po_id'];
	// 		// }
	// 		// if($project_id != "")
	// 		// { 		
	// 			// /* $data = $po_tbl->find()->where(["project_id"=>$project_id])->group(['project_id','po_no'])->hydrate(false)->toArray(); */
	// 			// $data = $po_tbl->find()->where(["project_id"=>$project_id,'po_id IN'=>$po_ids_array])->group(['project_id','po_no'])->order(['po_date' => 'DESC'])->hydrate(false)->toArray();
	// 		// }else{
	// 			// /* $data = $po_tbl->find()->group(['project_id','po_no'])->hydrate(false)->toArray(); */
	// 			// $data = $po_tbl->find()->where(["approved_status"=>0,'po_id IN'=>$po_ids_array])->group(['project_id','po_no'])->hydrate(false)->toArray();
	// 		// }
	// 	// }else{
	// 		// if($project_id != "")
	// 		// { 		
	// 			// /* $data = $po_tbl->find()->where(["project_id"=>$project_id])->group(['project_id','po_no'])->hydrate(false)->toArray(); */
	// 			// $data = $po_tbl->find()->where(["project_id"=>$project_id])->group(['project_id','po_no'])->order(['po_date' => 'DESC'])->hydrate(false)->toArray();
	// 		// }else{
	// 			// if($this->project_alloted($role)==1)
	// 			// {
	// 				// $data = $po_tbl->find()->where(["approved_status"=>0,"project_id IN"=>$projects_ids])->group(['project_id','po_no'])->hydrate(false)->toArray();
	// 			// }else{
	// 				// $data = $po_tbl->find()->where(["approved_status"=>0])->group(['project_id','po_no'])->hydrate(false)->toArray();
	// 			// }
	// 			// /* $data = $po_tbl->find()->group(['project_id','po_no'])->hydrate(false)->toArray(); */
				
	// 		// }
	// 	// }
		
	// 	$user_id = $this->request->session()->read('user_id');
	// 	$role = $this->get_user_role($user_id);
	// 	############################## Accessright ############################################
	// 	$editpreparepo = $this->retrive_accessrights($role,'editpreparepo');
	// 	$approvepo = $this->retrive_accessrights($role,'approvepo');
	// 	$deletepurchasepoalert = $this->retrive_accessrights($role,'deletepurchasepoalert');
	// 	$verifypurchasepoalert = $this->retrive_accessrights($role,'verifypurchasepoalert');
	// 	$approve1purchasepoalert = $this->retrive_accessrights($role,'approve1purchasepoalert');
	// 	$approve2purchasepoalert = $this->retrive_accessrights($role,'approve2purchasepoalert');
	// 	$rateHistory = $this->retrive_accessrights($role,'rateHistory');
	// 	############################## Accessright ############################################
	// 	if(!empty($data))
	// 	{
	// 		$table ="";	$x = 1;
	// 		foreach($data as $row)
	// 		{ 
	// 			// if($po_type != '')
	// 			// {
	// 				// $po_materials = $pod_tbl->find()->where(["po_id"=>$row["po_id"],"approved"=>0,"po_type"=>$po_type])->hydrate(false)->toArray();
	// 			// }else{
	// 				// $po_materials = $pod_tbl->find()->where(["po_id"=>$row["po_id"],"approved"=>0])->hydrate(false)->toArray();
	// 			// }
	// 			$po_type1=$row['po_purchase_type'];
	// 			if($po_type1 == "po")
	// 			{
	// 				$type_name = "PO";
	// 			}elseif($po_type1 == "manual_po")
	// 			{
	// 				$type_name = "Manual PO";
	// 			}elseif($po_type1 == "local_po"){
	// 				$type_name = "Local PO";
	// 			}
	// 			/*<td>{$this->get_projectcode($row['project_id'])}</td>
	// 			<td>{$this->get_prno_by_prid($row['pr_id'])}</td> */
	// 			foreach($row['erp_inventory_po_detail'] as $materials) {
	// 				$startdate =$row['po_date'];
	// 				$expire = strtotime($startdate. ' + 3 days');
	// 				$today = strtotime("today midnight");
	// 				if($materials['approved'] == 0 && $today >= $expire) {
	// 					$table .= "<tr class='data_row' id='dd_{$x}' style='background-color: gray;'>";	
	// 				}else {
	// 					$table .= "<tr class='data_row' id='dd_{$x}'>";
	// 				}
	// 			}
	// 			// $table .= "<tr class='data_row' id='dd_{$x}'>
	// 			$table .= "				
	// 			<td>{$this->get_projectname($row['project_id'])}</td>
	// 			<td>{$row['po_no']}</td>
	// 			<td>{$type_name}</td>
	// 			<td>{$this->get_date($row['po_date'])}</td>
	// 			<td>{$row['po_time']}</td>
	// 		    <td> ";	/*  <form action='setpoapprove' method='post'> preparegrn		 */
	// 			$first_disabled = '';
	// 			$first_checked = '';
	// 			$verify_disabled = '';
	// 			$verify_checked = '';
	// 			$second_disabled = '';
	// 			if(!empty($row['erp_inventory_po_detail']))
	// 			{
	// 				$table .="<table class='table-bordered' style='width:100%'>";
	// 				foreach($row['erp_inventory_po_detail'] as $materials)
	// 				{
						
	// 				$firstApproved = $materials['first_approved'];
	// 				$first_checked = ($materials['first_approved']==1) ? 'checked' : '';
	// 				$first_disabled = ($materials['verified']==0 || $materials['first_approved']==1) ? 'DISABLED' : '';
					
	// 				$verify_checked = ($materials['verified']==1) ? 'checked' : '';
	// 				$verify_disabled = ($materials['verified']==1) ? 'DISABLED' : '';
					
	// 				$second_disabled = ($materials['first_approved']==1) ? '' : 'DISABLED';
						
	// 				$mt = is_numeric($materials['material_id'])?$this->get_material_title($materials['material_id']):$materials['material_id'];
	// 				$brnd = is_numeric($materials['brand_id'])?$this->get_brandname($materials['brand_id']):$materials['brand_id'];
	// 				$unit_name = is_numeric($materials['material_id'])?
	// 				$this->get_items_units($materials['material_id']):$materials['static_unit'];
					
	// 					$table .= "<tr>							
													
	// 						<td>{$this->get_vendor_name($row['vendor_userid'])}</td>
							
	// 						<td>{$mt}</td>
	// 						<td>{$brnd}</td>
	// 						<td>{$materials['quantity']}</td>
	// 						<td>{$unit_name}</td>
	// 						<td>{$materials['single_amount']}</td>
	// 						<td>{$materials['amount']}</td>";
	// 						// <td>Central<br>Purchase</td>
	// 						if($rateHistory == 1) {
	// 						$table .= "<td>";
							
	// 							$table .= "<button type='button' data-toggle='modal' data-target='#load_modal' class='btn btn-info rate_history' po_detail_id='{$row["po_id"]}' m_id='{$materials["material_id"]}' p_id='{$project_id}'>Rate History</button></td>";
	// 						}
	// 					$table .= "</tr>";
	// 				}
	// 				$table .= "</table>";
	// 			}
	// 			else{
	// 				$table .= "None Record Found.
	// 				<script>var size = $('#dd_'+".$x.").remove();</script>
	// 				";
	// 			}
	// 			$table .= "<td>";
	// 			// debug($materials);
				
	// 				if($firstApproved == 0) {
	// 					if($editpreparepo==1)
	// 					{
	// 						$table .= "<a href='{$this->request->base}/inventory/editpreparepo/{$row["po_id"]}' id='edit-btn{$row['po_id']}' class='btn btn-sm btn-success'>Edit</a>&nbsp";
	// 					}
	// 				}else {
	// 					if($editpreparepo==1)
	// 					{
	// 						$table .= "<a style='display:none;' href='{$this->request->base}/inventory/editpreparepo/{$row["po_id"]}' id='edit-btn{$row['po_id']}' class='btn btn-sm btn-success'>Edit</a>&nbsp";
	// 					}
	// 				}
					
	// 				if($approvepo==1)
	// 				{
	// 					if(date('Y-m-d',strtotime($row['po_date'])) > date('Y-m-d',strtotime('01-07-2017')))
	// 					{
	// 						$table .= "<a href='{$this->request->base}/inventory/previewpo2/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
	// 					}
	// 					else
	// 					{
	// 						$table .= "<a href='{$this->request->base}/inventory/previewpo/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
	// 					}
	// 				}	
	// 			// if($firstApproved == 0) {	
	// 			// 	if($deletepurchasepoalert==1)
	// 			// 	{
	// 			// 		$table .= "<a href='{$this->request->base}/purchase/deletepurchasepoalert/{$materials["pr_mid"]}/{$materials["id"]}' class='btn btn-sm btn-danger'>Delete</a></td>";
	// 			// 	}
	// 			// }else {
	// 			// 	if($deletepurchasepoalert==1)
	// 			// 	{
	// 			// 		$table .= "<a href='{$this->request->base}/purchase/deletepurchasefirstpoalert/{$materials["pr_mid"]}/{$materials["id"]}' class='btn btn-sm btn-danger'>Delete</a>";
	// 			// 	}
	// 			// }

	// 			if($firstApproved == 0) {	
	// 				if($deletepurchasepoalert==1)
	// 				{
	// 					$table .= "<a href='{$this->request->base}/purchase/deletepurchasepoalert/{$row["po_id"]}' class='btn btn-sm btn-danger'>Delete</a></td>";
	// 				}
	// 			}else {
	// 				if($deletepurchasepoalert==1)
	// 				{
	// 					$table .= "<a href='{$this->request->base}/purchase/deletepurchasefirstpoalert/{$row["po_id"]}' class='btn btn-sm btn-danger'>Delete</a>";
	// 				}
	// 			}
				
	// 			if($verifypurchasepoalert==1)
	// 			{
	// 				$table .= "<td>
	// 					<div class='checkbox'>
	// 						<label><input type='checkbox' value='{$row["po_id"]}' {$verify_disabled} {$verify_checked} name='verify_list[]'/> </label>
	// 					</div>
	// 				</td>";
	// 			}else {
	// 				$table .= "<td>
	// 					<div class='checkbox'>
	// 						<label><input type='checkbox' disabled='true' value='{$row["po_id"]}' {$verify_disabled} {$verify_checked} name='verify_list[]'/> </label>
	// 					</div>
	// 				</td>";
	// 			}
	// 			if($approve1purchasepoalert==1)
	// 			{
	// 				$table .= "<td>
	// 					<div class='checkbox'>
	// 						<label><input type='checkbox' class='approved_list1' id='approved_list1' value='{$row["po_id"]}' {$first_disabled} {$first_checked} name='approved_list1[]'/> </label>
	// 					</div>
	// 				</td>";
	// 			}else {
	// 				$table .= "<td>
	// 					<div class='checkbox'>
	// 						<label><input type='checkbox' disabled='true' class='approved_list1' id='approved_list1' value='{$row["po_id"]}' {$first_disabled} {$first_checked} name='approved_list1[]'/> </label>
	// 					</div>
	// 				</td>";
	// 			}
				
	// 			if($approve2purchasepoalert==1)
	// 			{
	// 				$table .= "<td>
	// 					<div class='checkbox'>
	// 						<label><input type='checkbox' {$second_disabled} value='{$row["po_id"]}' name='approved_list[]'/> </label>
	// 					</div>
	// 				</td>";
	// 			}else {
	// 				$table .= "<td>
	// 					<div class='checkbox'>
	// 						<label><input type='checkbox' {$second_disabled} value='{$row["po_id"]}' name='approved_list[]'/> </label>
	// 					</div>
	// 				</td>";
	// 			}
	// 			$table .= "<td> <input type='button' name='approve_po' po_no='{$row['po_no']}' value='Go' class='btn btn-success go_btn'> 	</td></td>
	// 			</tr>";
	// 			$x++;
	// 		}		
	// 		return $table;
	// 	}
	// }
	
	public function get_manualpo_alerts($project_id)
	{		
		$po_tbl = TableRegistry::get("erp_manual_po");
		$pod_tbl = TableRegistry::get("erp_manual_po_detail");
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		if($role == "deputymanagerelectric")
		{
			$materials_ids = $this->get_deputymanagerelectric_material();
			$materials_ids = json_decode($materials_ids);
			$po_ids = $pod_tbl->find()->where(["material_id IN"=>$materials_ids])->select('po_id')->hydrate(false)->toArray();
			$po_ids_array = array();
			foreach($po_ids as $po_id)
			{
				$po_ids_array[] = $po_id['po_id'];
			}
			if($project_id != "")
			{ 
				$data = $po_tbl->find()->where(["project_id"=>$project_id,"po_id IN"=>$po_ids_array,"is_grn_base !="=>1])->group(['project_id','po_no'])->order(['po_date' => 'DESC'])->hydrate(false)->toArray();
			}else{
				$data = $po_tbl->find()->where(["project_id IN"=>$projects_ids,"approved_status"=>0,"po_id IN"=>$po_ids_array,"is_grn_base !="=>1])->group(['project_id','po_no'])->hydrate(false)->toArray();
			}
		}else{
			if($project_id != "")
			{ 
				$data = $po_tbl->find()->where(["project_id"=>$project_id,"is_grn_base !="=>1])->group(['project_id','po_no'])->order(['po_date' => 'DESC'])->hydrate(false)->toArray();
			}else{
				if($this->project_alloted($role)==1){ 
					$data = $po_tbl->find()->where(["project_id IN"=>$projects_ids,"approved_status"=>0,"is_grn_base !="=>1])->group(['project_id','po_no'])->hydrate(false)->toArray();
				}else{
					$data = $po_tbl->find()->where(["approved_status"=>0,"is_grn_base !="=>1])->group(['project_id','po_no'])->hydrate(false)->toArray();
				}
				
			}
		}
		
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		
		if(!empty($data))
		{
			$table ="";	$x = 1;
			foreach($data as $row)
			{ 
				$po_materials = $pod_tbl->find()->where(["po_id"=>$row["po_id"],"approved"=>0])->hydrate(false)->toArray();
				
				$table .= "<tr class='data_row' id='dd_{$x}'>
				<td>{$this->get_projectname($row['project_id'])}</td>
				<td>{$row['po_no']}</td>
				<td>{$this->get_date($row['po_date'])}</td>
				<td>{$row['po_time']}</td>
			    <td> ";	
				
				if(!empty($po_materials))
				{
					$table .="<table class='table-bordered' style='width:100%'>";
					foreach($po_materials as $materials)
					{
					$first_checked = ($materials['first_approved']==1) ? 'checked' : '';
					$first_disabled = ($materials['first_approved']==1) ? 'DISABLED' : '';
					
					$second_disabled = ($materials['first_approved']==1) ? '' : 'DISABLED';
						
					$mt = is_numeric($materials['material_id'])?$this->get_material_title($materials['material_id']):$materials['material_id'];
					$brnd = is_numeric($materials['material_id'])?$this->get_brandname_by_manualpo_material($row['po_id'],$materials['material_id']):$materials['brand_id'];
					$unit_name = is_numeric($materials['material_id'])?
					$this->get_items_units($materials['material_id']):$materials['static_unit'];
					
						$table .= "<tr>							
													
							<td>{$this->get_vendor_name($row['vendor_userid'])}</td>
							
							<td>{$mt}</td>
							<td>{$brnd}</td>
							<td>{$materials['quantity']}</td>
							<td>{$unit_name}</td>
							<td>{$materials['single_amount']}</td>
							<td>{$materials['amount']}</td>";
						$table .= "<td>";
						if($this->retrive_accessrights($role,'editmanualpreparepo')==1)
						{
							$table .= "<a href='{$this->request->base}/purchase/editmanualpreparepo/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-success'>Edit</a>&nbsp";
						}
						if($this->retrive_accessrights($role,'manualapprovepo')==1)
						{
							$table .= "<a href='{$this->request->base}/purchase/manualpreviewpo/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
						}		
						if($this->retrive_accessrights($role,'deletemanualpoalert')==1)
						{
							$table .= "<a href='{$this->request->base}/purchase/deletemanualpoalert/{$materials["id"]}' class='btn btn-sm btn-danger'>Delete</a></td>";
						}
						if($this->retrive_accessrights($role,'approve1manualpoalert')==1)
						{
							$table .= "<td> 
								<div class='checkbox'>
									<label><input type='checkbox' value='{$materials['id']}' {$first_disabled} {$first_checked} name='approved_list1[]'/> </label>
									<input type='hidden' name='first_selected_po_id_{$materials['id']}' value='{$materials['po_id']}'>
									<input type='hidden' name='po_id1' value='{$materials['po_id']}'>
									<input type='hidden' name='first_project_id_{$materials['id']}' value='{$row['project_id']}'>
								</div>
							</td>";
						}
						if($this->retrive_accessrights($role,'approve2manualpoalert')==1)
						{
							$table .= "<td> 
								<div class='checkbox'>
									<label><input type='checkbox' {$second_disabled} value='{$materials['id']}' name='approved_list[]'/> </label>
									<input type='hidden' name='selected_po_id_{$materials['id']}' value='{$materials['po_id']}'>
									<input type='hidden' name='po_id' value='{$materials['po_id']}'>
									<input type='hidden' name='project_id_{$materials['id']}' value='{$row['project_id']}'>
								</div>
							</td>";
						}
						$table .= "</tr>";
					}
					$table .= "</table>";
				}
				else{
					$table .= "None Record Found.
					<script>var size = $('#dd_'+".$x.").remove();</script>
					";
				}
				$table .= "<td> <input type='button' name='approve_po' po_no='{$row['po_no']}' value='Go' class='btn btn-success go_btn'> 	</td></td>
				</tr>";
				$x++;
			}			
			return $table;
		}
	}
	
	public function get_manualpolocal_alerts($project_id)
	{		
		$po_tbl = TableRegistry::get("erp_manual_po");
		$pod_tbl = TableRegistry::get("erp_manual_po_detail");
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		if($role == "deputymanagerelectric")
		{
			$materials_ids = $this->get_deputymanagerelectric_material();
			$materials_ids = json_decode($materials_ids);
			$po_ids = $pod_tbl->find()->where(["material_id IN"=>$materials_ids])->select('po_id')->hydrate(false)->toArray();
			$po_ids_array = array();
			foreach($po_ids as $po_id)
			{
				$po_ids_array[] = $po_id['po_id'];
			}
			if($project_id != "")
			{ 
				$data = $po_tbl->find()->where(["project_id"=>$project_id,"po_id IN"=>$po_ids_array,"is_grn_base"=>1])->group(['project_id','po_no'])->order(['po_date' => 'DESC'])->hydrate(false)->toArray();
			}else{
				$data = $po_tbl->find()->where(["project_id IN"=>$projects_ids,"approved_status"=>0,"po_id IN"=>$po_ids_array,"is_grn_base"=>1])->group(['project_id','po_no'])->hydrate(false)->toArray();
			}
		}else{
			if($project_id != "")
			{ 
				$data = $po_tbl->find()->where(["project_id"=>$project_id,"is_grn_base"=>1])->group(['project_id','po_no'])->order(['po_date' => 'DESC'])->hydrate(false)->toArray();
			}else{
				if($this->project_alloted($role)==1){ 
					$data = $po_tbl->find()->where(["project_id IN"=>$projects_ids,"approved_status"=>0,"is_grn_base"=>1])->group(['project_id','po_no'])->hydrate(false)->toArray();
				}else{
					$data = $po_tbl->find()->where(["approved_status"=>0,"is_grn_base"=>1])->group(['project_id','po_no'])->hydrate(false)->toArray();
				}
				
			}
		}
		
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		
		if(!empty($data))
		{
			$table ="";	$x = 1;
			foreach($data as $row)
			{ 
				$po_materials = $pod_tbl->find()->where(["po_id"=>$row["po_id"],"approved"=>0])->hydrate(false)->toArray();
				
				$table .= "<tr class='data_row' id='dd_{$x}'>
				<td>{$this->get_projectname($row['project_id'])}</td>
				<td>{$row['po_no']}</td>
				<td>{$this->get_date($row['po_date'])}</td>
				<td>{$row['po_time']}</td>
			    <td> ";	
				
				if(!empty($po_materials))
				{
					$table .="<table class='table-bordered' style='width:100%'>";
					foreach($po_materials as $materials)
					{
					$first_checked = ($materials['first_approved']==1) ? 'checked' : '';
					$first_disabled = ($materials['first_approved']==1) ? 'DISABLED' : '';
					
					$second_disabled = ($materials['first_approved']==1) ? '' : 'DISABLED';
						
					$mt = is_numeric($materials['material_id'])?$this->get_material_title($materials['material_id']):$materials['material_id'];
					$brnd = is_numeric($materials['material_id'])?$this->get_brandname_by_manualpo_material($row['po_id'],$materials['material_id']):$materials['brand_id'];
					$unit_name = is_numeric($materials['material_id'])?
					$this->get_items_units($materials['material_id']):$materials['static_unit'];
					
						$table .= "<tr>							
													
							<td>{$this->get_vendor_name($row['vendor_userid'])}</td>
							
							<td>{$mt}</td>
							<td>{$brnd}</td>
							<td>{$materials['quantity']}</td>
							<td>{$unit_name}</td>
							<td>{$materials['single_amount']}</td>
							<td>{$materials['amount']}</td>";
						$table .= "<td>";
						if($role == "erphead" || $role == "ceo" || $role == "md" || $role == "purchasehead" || $role == "erpmanager" || $role == "purchasemanager" || $role == "erpoperator" || $role == "deputymanagerelectric")
						{
							$table .= "<a href='{$this->request->base}/purchase/editmanualpreparepo/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-success'>Edit</a>&nbsp";
						}
						
							$table .= "<a href='{$this->request->base}/purchase/manualpreviewpo/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
								
						if($role == "erphead" || $role == "ceo" || $role == "md" || $role == "purchasehead" || $role == "erpoperator" || $role == "erpmanager")
						{
							$table .= "<a href='{$this->request->base}/purchase/deletemanualpoalert/{$materials["id"]}' class='btn btn-sm btn-danger'>Delete</a></td>";
						}
						if($role == "erphead" || $role == "ceo" || $role == "md" || $role == "purchasehead" || $role == "erpmanager" || $role == "projectdirector")
						{
							$table .= "<td> 
								<div class='checkbox'>
									<label><input type='checkbox' value='{$materials['id']}' {$first_disabled} {$first_checked} name='approved_list1[]'/> </label>
									<input type='hidden' name='first_selected_po_id_{$materials['id']}' value='{$materials['po_id']}'>
									<input type='hidden' name='po_id1' value='{$materials['po_id']}'>
									<input type='hidden' name='first_project_id_{$materials['id']}' value='{$row['project_id']}'>
								</div>
							</td>";
						}
						if($role == "erphead" || $role == "ceo" || $role == "md" || $role == "erpmanager")
						{
							$table .= "<td> 
								<div class='checkbox'>
									<label><input type='checkbox' {$second_disabled} value='{$materials['id']}' name='approved_list[]'/> </label>
									<input type='hidden' name='selected_po_id_{$materials['id']}' value='{$materials['po_id']}'>
									<input type='hidden' name='po_id' value='{$materials['po_id']}'>
									<input type='hidden' name='project_id_{$materials['id']}' value='{$row['project_id']}'>
								</div>
							</td>";
						}
						$table .= "</tr>";
					}
					$table .= "</table>";
				}
				else{
					$table .= "None Record Found.
					<script>var size = $('#dd_'+".$x.").remove();</script>
					";
				}
				$table .= "<td> <input type='button' name='approve_po' po_no='{$row['po_no']}' value='Go' class='btn btn-success go_btn'> 	</td></td>
				</tr>";
				$x++;
			}			
			return $table;
		}
	}
	
	// public function get_po_alerts($project_id)
	// {
		// $po_tbl = TableRegistry::get("erp_inventory_po");
		// $pod_tbl = TableRegistry::get("erp_inventory_po_detail");
		// $mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		// $data = $po_tbl->find()->where(["project_id"=>$project_id])->group(['project_id','po_no'])->hydrate(false)->toArray();
		// debug($data);
		// if(!empty($data))
		// {
			// $table ="";
			// foreach($data as $row)
			// { 
				// $prids_db = $po_tbl->find()->where(["project_id"=>$row["project_id"],"po_no"=>$row["po_no"]])->select(["pr_id"])->hydrate(false)->toArray();
				// foreach($prids_db  as $prdata)
				// {
						// $prids[]=$prdata["pr_id"];
				// }
				// $mat_details = $mt_tbl->find()->where(['pr_id IN'=>$prids,"approved"=>1])->hydrate(false)->toArray();
				// //debug($mat_details);
				// $table .= "<tr>
				// <td>{$this->get_projectcode($row['project_id'])}</td>
				// <td>{$this->get_prno_by_prid($row['pr_id'])}</td>
				// <td>{$row['po_no']}</td>
				// <td>{$row['po_date']}</td>
				// <td>{$row['po_time']}</td>
				// <td>";
				// if(!empty($mat_details))
				// {
					// $table .="<table class='table-bordered' style='width:100%'>";
					// foreach($mat_details as $materials)
					// {
						// $table .= "<tr>
							// <td>Vendor COde</td>
							// <td>Vendor id</td>							
							// <td>{$this->get_material_item_code_bymaterialid($materials['material_id'])}</td>
							// <td>{$this->get_material_title($materials['material_id'])}</td>
							// <td>{$this->get_brandname($materials['brand_id'])}</td>
							// <td>{$materials['quantity']}</td>
							// <td>{$this->get_items_units($materials['material_id'])}</td>
							// <td>{$materials['delivery_date']->format('Y-m-d')}</td>
							// <td>Central<br>Purchase</td>
							// <td>Edit/View</td>
							// <td>
								// <div class='checkbox'>
									// <label><input type='checkbox' value='{$materials['material_id']}' name='approved_list[]'/> </label>
								// </div>
							// </td>
						// </tr>";
					// }				
					// $table .= "</table>";
				// }
				// else{
					// $table .= "None Record Found.";
				// }
				// $table .= "</td>
				// </tr>";
			// }
			// return $table;
		// }
	// }
	
	public function get_prid_by_prno($prno)
	{
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$row = $pr_tbl->find()->where(["prno"=>$prno])->select(["pr_id"])->hydrate(false)->toArray();
		return $row[0]["pr_id"];
	}
	
	public function get_prno_by_prid($prid)
	{
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$row = $pr_tbl->find()->where(["pr_id"=>$prid])->select(["prno"])->hydrate(false)->toArray();
		if(!empty($row))
		{
			return $row[0]["prno"];
		}
		else{
			return "None";
		}
	}
	
	public function get_brandname_by_pr_material($pr_id,$material_id)
	{
		$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		$brand_id = $mt_tbl->find()->where(["pr_id"=>$pr_id,"material_id"=>$material_id])->select("brand_id")->hydrate(false)->toArray();
		if(!empty($brand_id))
		{
			return $this->get_brandname($brand_id[0]["brand_id"]);
		}else{
			return "-";
		}
		
	}
	
	public function get_brandname_by_po_material($po_id,$material_id)
	{
		$mt_tbl = TableRegistry::get("erp_inventory_po_detail");
		$brand_id = $mt_tbl->find()->where(["po_id"=>$po_id,"material_id"=>$material_id])->select("brand_id")->hydrate(false)->toArray();
		if(!empty($brand_id))
		{
			return $this->get_brandname($brand_id[0]["brand_id"]);
		}else{
			return "-";
		}
		
	}
	
	public function get_brandname_by_manualpo_material($po_id,$material_id)
	{
		$mt_tbl = TableRegistry::get("erp_manual_po_detail");
		$brand_id = $mt_tbl->find()->where(["po_id"=>$po_id,"material_id"=>$material_id])->select("brand_id")->hydrate(false)->toArray();
		if(!empty($brand_id))
		{
			return $this->get_brandname($brand_id[0]["brand_id"]);
		}else{
			return "-";
		}
		
	}
	
	public function get_grn_details($grn_details,$po_id,$vendor_userid,$payment_method,$challan,$project_code,$project_id,$i)
	{
		// $grn_tbl = TableRegistry::get("erp_inventory_grn_detail");
		// $grn_details = $grn_tbl->find()->where(["grn_id"=>$grn_id,"approved"=>0])->hydrate(false)->toArray();
		$rows = "<table class='table-bordered'>";

		if(!empty($grn_details))
		{	
			############################## Accessright #################################################
			$user_id = $this->request->session()->read('user_id');
			$role = $this->get_user_role($user_id);
			$updategrn = $this->retrive_accessrights($role,'updategrn');
			$approvegrn = $this->retrive_accessrights($role,'approvegrn');
			$unapprovegrn = $this->retrive_accessrights($role,'unapprovegrn');
			$approvegrnalert_inv = $this->retrive_accessrights($role,'approvegrnalert_inv');
			############################## Accessright #################################################
			
			foreach($grn_details as $grn_data)
			{
				if($grn_data['is_static'])
				{
					$mt = $grn_data['material_name'];
					$brnd = $grn_data['brand_name'];
					$mt_id = $grn_data['material_name'];
					$unit_name = $grn_data['static_unit'];
				}
				else
				{
					$mt = $this->get_material_title($grn_data['material_id']);
					$brnd = $this->get_brandname($grn_data['brand_id']);
					$mt_id = $grn_data['material_id'];
					$unit_name = $this->get_items_units($grn_data['material_id']);
				}
				$rows .= "<tr>			
				<td>{$this->get_vendor_name($vendor_userid)}</td>
				<td>{$challan}</td>
				<td>{$mt}</td>
				<td>{$brnd}</td>
				<td>{$grn_data['quantity']}</td>
				<td>{$grn_data['actual_qty']}</td>
				<td>";
				if($grn_data['difference_qty'] != "")
				{
					$num = explode(":",$grn_data['difference_qty']);
					$rounded_num = round($num[0],2);
					$num[0] = $rounded_num;
					$rows .= implode(":",$num);
				}
				$rows .= "</td>
				<td>{$unit_name}</td>				
				<td>".(($po_id != "")?'Central':'Local')."</td>
				<td>{$payment_method}</td><td>";
				
				if($updategrn==1)
				{
					$rows .= "<a class='btn btn-success' href='updategrn/".$grn_data['grn_id']."'>Edit</a>";
				}
				if($approvegrn==1)
				{
					$rows .= "<a class='btn btn-primary' target='_blank' href='previewgrn/".$grn_data['grn_id']."'>View</a>";
				 }
				if($unapprovegrn==1)
				{
					$rows .= "<a href='{$this->request->base}/inventory/unapprovegrn/{$grn_data['grndetail_id']}' class='btn btn-danger'>Delete</a>";
				}
				$rows .= "</td>";
				if($approvegrnalert_inv==1)
				{
				$rows .="<td>";
				
					$rows .= "<input type='checkbox' class='approve_grn'  entry='grn' grn_id='{$grn_data['grn_id']}' project_id='{$project_id}' detail_id='{$grn_data['grndetail_id']}' project_code='{$project_code}' material_id='{$mt_id}' static_unit='{$grn_data['static_unit']}' quantity='{$grn_data['quantity']}' actual_qty='{$grn_data['actual_qty']}'> </td>	";
				}				
				$rows .= "	
				</tr>";				
			}
			$rows .= "</table>";			
		}
		else{
			$rows = "No Records Found.";
			$rows .= "<script>var size = $('#dd_'+".$i.").remove();</script>";
			
		}
		return $rows;
	}
	
	public function get_grn_audit_details($grn_details,$po_id,$vendor_userid,$payment_method,$challan,$i)
	{
		// $grn_tbl = TableRegistry::get("erp_audit_grn_detail");
		// $grn_details = $grn_tbl->find()->where(["grn_id"=>$grn_id,"audit_status"=>0])->hydrate(false)->toArray();
		$rows = "<table class='table-bordered'>";
		
		if(!empty($grn_details))
		{			
			foreach($grn_details as $grn_data)
			{
				if($grn_data['is_static'])
				{
					$mt = $grn_data['material_name'];
					$brnd = $grn_data['brand_name'];
					$mt_id = $grn_data['material_name'];
					$unit_name = $grn_data['static_unit'];
				}
				else
				{
					$mt = $this->get_material_title($grn_data['material_id']);
					$brnd = $this->get_brandname($grn_data['brand_id']);
					$mt_id = $grn_data['material_id'];
					$unit_name = $this->get_items_units($grn_data['material_id']);
				}
				$rows .= "<tr>
									
				<td>{$this->get_vendor_name($vendor_userid)}</td>
				<td>{$challan}</td>
							
				<td>{$mt}</td>
				<td>{$brnd}</td>
				<td>{$grn_data['quantity']}</td>
				<td>{$grn_data['actual_qty']}</td>
				<td>";
				if($grn_data['difference_qty'] != "")
				{
					$num = explode(":",$grn_data['difference_qty']);
					$rounded_num = round($num[0],2);
					$num[0] = $rounded_num;
					$rows .= implode(":",$num);
				}
				$rows .= "</td>
				<td>{$unit_name}</td>				
				<td>".(($po_id != "")?'Central':'Local')."</td>
				<td>{$payment_method}</td><td>";
				$user_id = $this->request->session()->read('user_id');
				$role = $this->get_user_role($user_id);
				 if($this->retrive_accessrights($role,'updateauditgrn')==1)
				{
					$rows .= "<a class='btn btn-success' target='_blank' href='updateauditgrn/".$grn_data['audit_id']."'>Edit</a>";
					
					$rows .= "<a class='btn btn-primary' target='_blank' href='previewauditgrn/".$grn_data['audit_id']."'>View</a>";
				}
				 
				
				$rows .= "</td>";
								
				$rows .= "</tr>";				
			}
			$rows .= "</table>";			
		}
		else{
			$rows = "No Records Found.";
			$rows .= "<script>var size = $('#dd_'+".$i.").remove();</script>";
			
		}
		return $rows;
	}
	
	public function get_agency_name($id)
	{
		$ag_tbl = TableRegistry::get("erp_agency");
		$agency = $ag_tbl->find()->where(["id"=>$id])->hydrate(false)->toArray();
		if(!empty($agency))
		{
			return $agency[0]["agency_name"];
		}else{
			return "-";
		}
	}
	
	public function get_agency_bank($id)
	{
		$ag_tbl = TableRegistry::get("erp_agency");
		$agency = $ag_tbl->find()->where(["id"=>$id])->hydrate(false)->toArray();
		if(!empty($agency))
		{
			return $agency[0]["bank_name"];
		}else{
			return "-";
		}
	}
	
	public function get_agency_transfer_type($id)
	{
		$ag_tbl = TableRegistry::get("erp_agency");
		$agency = $ag_tbl->find()->where(["id"=>$id])->hydrate(false)->toArray();
		if(!empty($agency))
		{
			return $agency[0]["transfer_type"];
		}else{
			return "-";
		}
	}
	
	public function get_agency_name_by_code($code)
	{
		$ag_tbl = TableRegistry::get("erp_agency");
		$agency = $ag_tbl->find()->where(["agency_id"=>$code])->hydrate(false)->toArray();
		if(!empty($agency))
		{
			return $agency[0]["agency_name"];
		}else{
			return "-";
		}
	}

	public function get_vendor_name_by_code($code)
	{
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$vendor = $vendor_tbl->find()->where(["user_id"=>$code])->hydrate(false)->toArray();
		if(!empty($vendor))
		{
			return $vendor[0]["vendor_name"];
		}else{
			return "-";
		}
	}
	
	public function get_agency_code($id)
	{
		$ag_tbl = TableRegistry::get("erp_agency");
		$agency = $ag_tbl->find()->where(["id"=>$id])->hydrate(false)->toArray();
		if(!empty($agency))
		{
			return $agency[0]["agency_id"];
		}else{
			return "-";
		}
	}
	
	public function dashboard_project_list()
	{
		$pr_tbl = TableRegistry::get("erp_projects");
		$projects = $pr_tbl->find()->where(['project_status'=>'On Going'])->limit(5)->order(["created_date"=>"DESC"])->hydrate(false)->toArray();
		return $projects;
	}
		public function dashboard_alert_list($role)
	{
		$pr_tbl = TableRegistry::get("erp_inventory_pr_material");
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$po_det_tbl = TableRegistry::get("erp_inventory_po_detail");
		$grn_tbl = TableRegistry::get("erp_inventory_grn");
		$grn_tbl_det = TableRegistry::get("erp_inventory_grn_detail");
		$mrn_tbl = TableRegistry::get("erp_inventory_mrn");
		$rbn_tbl = TableRegistry::get("erp_inventory_rbn_detail");
		$sst_tbl = TableRegistry::get("erp_inventory_sst");
		$sst_tbl_det = TableRegistry::get("erp_inventory_sst_detail");
		$is_tbl = TableRegistry::get("erp_inventory_is_detail");
		$pr_purches = TableRegistry::get("erp_inventory_purhcase_request");
		$maintenace_table = TableRegistry::get('erp_assets_maintenance');
		$rmc_tbl = TableRegistry::get("erp_rmc_issue");	
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_debit_note = TableRegistry::get("erp_debit_note");
		$erp_debit_note_det = TableRegistry::get('erp_debit_note_detail');
		$erp_advance_request = TableRegistry::get("erp_advance_request");
		$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail');
		$erp_expence_add = TableRegistry::get("erp_expence_add");
		$erp_expence_detail = TableRegistry::get("erp_expence_detail");
		
		$po_manual_tbl = TableRegistry::get("erp_manual_po");
		$po_manual_det_tbl = TableRegistry::get("erp_manual_po_detail");
		$conn = ConnectionManager::get('default');
		
		$alerts = array();
		$project_id="";
		
		if($this->project_alloted($role)==1){ 
			$user_id=$this->request->session()->read('user_id');
			$projects=$this->get_user_assign_projects_id($user_id);
			$or['project_id']=$projects;
			
			$alerts["grn_alert"] = $grn_tbl->find()->where(["approved_status"=>0,"project_id IN"=>$or['project_id']])->count();
			$alerts["mrn_alert"] = $mrn_tbl->find()->where(["approve_executives"=>0,"project_id IN"=>$or['project_id']])->count();
			$sst_alert = $sst_tbl->find()->where(["approved_status"=>0,"project_id IN"=>$or['project_id']]);
			
			$alerts["sst_alert"] = $sst_alert->INNERJOIN(["erp_inventory_sst_detail"=>"erp_inventory_sst_detail"],["erp_inventory_sst_detail.sst_id = erp_inventory_sst.sst_id",'erp_inventory_sst_detail.approved_site2'=>0])->group("erp_inventory_sst_detail.sst_id")->count();
			
			$alerts["maintenace_list"] = $maintenace_table->find()->where(['approved_status'=>0,"project_id IN"=>$or['project_id']])->count();
			$alerts["rmc_issue"] =  $rmc_tbl->find()->where(["approved"=>0,"project_id IN"=>$or['project_id']])->count();
			
			$alerts["sub_contract_alerts"] = $erp_sub_contract->find()->where(["approval"=>0,"project_id IN"=>$or['project_id']])->count();
			
			$erp_debit = $erp_debit_note->find()->where(["project_id IN"=>$or['project_id']]);
			$alerts["debit_note"] = $erp_debit->INNERJOIN(["erp_debit_note_detail"=>"erp_debit_note_detail"],["erp_debit_note_detail.debit_id = erp_debit_note.debit_id",'erp_debit_note_detail.second_approved'=>0])->group("erp_debit_note_detail.debit_id")->count();
			
			$erp_advance = $erp_advance_request->find()->where(["erp_advance_request.project_id IN"=>$or['project_id']]);
			$alerts["advance_alert"] = $erp_advance->INNERJOIN(["erp_advance_request_detail"=>"erp_advance_request_detail"],["erp_advance_request_detail.request_id = erp_advance_request.request_id",'erp_advance_request_detail.approval_export'=>0])->group("erp_advance_request_detail.request_id")->count();
			
			$erp_expence = $erp_expence_add->find()->where(["project_id IN"=>$or['project_id']]);
			
			$alerts["expense_alert"] = $erp_expence->INNERJOIN(["erp_expence_detail"=>"erp_expence_detail"],["erp_expence_detail.exp_id = erp_expence_add.id",'erp_expence_detail.approval_accountant'=>0])->group("erp_expence_detail.exp_id")->count();
			
			
			
			
			$pur_request = $pr_purches->find()->where(["erp_inventory_purhcase_request.approved_status"=>0,"project_id IN"=>$or['project_id']])->select($pr_purches)->group('erp_inventory_purhcase_request.prno');
			
			$alerts["pr_alert_purches"] = $pur_request->INNERJOIN(["erp_inventory_pr_material"=>"erp_inventory_pr_material"],["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id",'erp_inventory_pr_material.approved'=>0,'erp_inventory_pr_material.show_in_purchase'=>1])->count();
			
			$alerts["pr_alert_inv"] = $pur_request->INNERJOIN(["erp_inventory_pr_material"=>"erp_inventory_pr_material"],["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id",'erp_inventory_pr_material.approved'=>0,'erp_inventory_pr_material.show_in_purchase'=>0])->count();
			
			$inv_po= $po_tbl->find()->where(["erp_inventory_po.approved_status"=>0,"erp_inventory_po.project_id IN"=>$or['project_id']])->select($po_tbl)->group('erp_inventory_po.project_id,erp_inventory_po.po_no');
			
			$alerts["po_alert"]=$inv_po->INNERJOIN(["erp_inventory_po_detail"=>"erp_inventory_po_detail"],["erp_inventory_po_detail.po_id = erp_inventory_po.po_id",'erp_inventory_po_detail.approved'=>0])->group(['erp_inventory_po_detail.po_id'])->count();
			
			
			$po_manual= $po_manual_tbl->find()->where(['erp_manual_po.approved_status'=>0,"erp_manual_po.project_id IN"=>$or['project_id']])->group(['erp_manual_po.po_no,erp_manual_po.project_id'])->select($po_manual_tbl);
			
			$alerts["po_manual_alert"]=$po_manual->INNERJOIN(["erp_manual_po_detail"=>"erp_manual_po_detail"],["erp_manual_po_detail.po_id = erp_manual_po.po_id",'erp_manual_po_detail.approved'=>0])->group(['erp_manual_po_detail.po_id'])->select($po_manual_det_tbl)->count();
			
			
			
		}
		else{
			
			
			$alerts["grn_alert"] = $grn_tbl->find()->where(["approved_status"=>0])->count();
			$alerts["mrn_alert"] = $mrn_tbl->find()->where(["approve_executives"=>0])->count();
			$sst_alert = $sst_tbl->find()->where(["approved_status"=>0]);
			
			$alerts["sst_alert"] = $sst_alert->INNERJOIN(["erp_inventory_sst_detail"=>"erp_inventory_sst_detail"],["erp_inventory_sst_detail.sst_id = erp_inventory_sst.sst_id",'erp_inventory_sst_detail.approved_site2'=>0])->group("erp_inventory_sst_detail.sst_id")->count();
			
			$alerts["maintenace_list"] = $maintenace_table->find()->where(['approved_status'=>0])->count();
			$alerts["rmc_issue"] =  $rmc_tbl->find()->where(["approved"=>0])->count();
			
			$alerts["sub_contract_alerts"] = $erp_sub_contract->find()->where(["approval"=>0])->count();
			
			$erp_debit = $erp_debit_note->find();
			$alerts["debit_note"] = $erp_debit->INNERJOIN(["erp_debit_note_detail"=>"erp_debit_note_detail"],["erp_debit_note_detail.debit_id = erp_debit_note.debit_id",'erp_debit_note_detail.second_approved'=>0])->group("erp_debit_note_detail.debit_id")->count();
			
			$erp_advance = $erp_advance_request->find();
			$alerts["advance_alert"] = $erp_advance->INNERJOIN(["erp_advance_request_detail"=>"erp_advance_request_detail"],["erp_advance_request_detail.request_id = erp_advance_request.request_id",'erp_advance_request_detail.approval_export'=>0])->group("erp_advance_request_detail.request_id")->count();
			
			$erp_expence = $erp_expence_add->find();
			
			$alerts["expense_alert"] = $erp_expence->INNERJOIN(["erp_expence_detail"=>"erp_expence_detail"],["erp_expence_detail.exp_id = erp_expence_add.id",'erp_expence_detail.approval_accountant'=>0])->group("erp_expence_detail.exp_id")->count();
			
			
			
			
			$pur_request = $pr_purches->find()->where(["erp_inventory_purhcase_request.approved_status"=>0])->select($pr_purches)->group('erp_inventory_purhcase_request.prno');
			
			$alerts["pr_alert_purches"] = $pur_request->INNERJOIN(["erp_inventory_pr_material"=>"erp_inventory_pr_material"],["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id",'erp_inventory_pr_material.approved'=>0,'erp_inventory_pr_material.show_in_purchase'=>1])->count();
			
			$alerts["pr_alert_inv"] = $pur_request->INNERJOIN(["erp_inventory_pr_material"=>"erp_inventory_pr_material"],["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id",'erp_inventory_pr_material.approved'=>0,'erp_inventory_pr_material.show_in_purchase'=>0])->count();
			
			$inv_po= $po_tbl->find()->where(["erp_inventory_po.approved_status"=>0])->select($po_tbl)->group('erp_inventory_po.project_id,erp_inventory_po.po_no');
			
			$alerts["po_alert"]=$inv_po->INNERJOIN(["erp_inventory_po_detail"=>"erp_inventory_po_detail"],["erp_inventory_po_detail.po_id = erp_inventory_po.po_id",'erp_inventory_po_detail.approved'=>0])->group(['erp_inventory_po_detail.po_id'])->count();
			
			
			$po_manual= $po_manual_tbl->find()->where(['erp_manual_po.approved_status'=>0])->group(['erp_manual_po.po_no'])->select($po_manual_tbl);
			
			$alerts["po_manual_alert"]=$po_manual->INNERJOIN(["erp_manual_po_detail"=>"erp_manual_po_detail"],["erp_manual_po_detail.po_id = erp_manual_po.po_id",'erp_manual_po_detail.approved'=>0])->group(['erp_manual_po_detail.po_id'])->select($po_manual_det_tbl)->count();
			

			
		}
		
		
		
		return $alerts;
	}
	public function dashboard_project($project_id=null)
	{
		$user_id=$this->request->session()->read('user_id');
		
		$erp_projects = TableRegistry::get('erp_projects');
		$project_list = $erp_projects->find()->where(['project_id'=>$project_id,"actual_amount"=>0])->hydrate(false)->toArray();
		
		/* var_dump($project_list);
		die; */ 
		return($project_list);
		
	}
	public function dashboard_project_expense($project_id=null,$type){
		
		$total=0;
		
		$erp_inward_bill_register = TableRegistry::get('erp_inward_bill');
		if($type!="Other"){
			$childid= explode( ',', $type ) ;
			
				$inward_bill_list = $erp_inward_bill_register->find()->where(['project_id'=>$project_id,'bill_type In'=>$childid]);
			
			$query=$inward_bill_list->select(['total_amt' => $inward_bill_list->func()->sum('total_amt')])->hydrate(false)->toArray();
			
			
		}
		else{
			$type1="Material/Item,Labour with Material/Item,Labour";
			$childid= explode( ',', $type1 ) ;
			$inward_bill_list = $erp_inward_bill_register->find()->where(['project_id'=>$project_id,'bill_type NOT IN'=>$childid]);
			
			$query=$inward_bill_list->select(['total_amt' => $inward_bill_list->func()->sum('total_amt')])->hydrate(false)->toArray();
		}
		if(!empty($query)){
			$total=$query[0]['total_amt'];
		}
		return($total);
		
		
	}
	public function dashboard_po_list($project_id, $from,$to)
	{
		$po_tbl = TableRegistry::get("erp_inventory_po");
		
		$conn = ConnectionManager::get('default');
		//FOLLOWING QUERY NOT WORKING IN PHP 5.7 SO CHANGE MODE USING QUERY -> mysql > SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
		$po_data = $conn->execute(
		'select *,COUNT(DISTINCT po.po_id) as count from erp_inventory_po as po
		left join erp_inventory_po_detail as pod on po.po_id = pod.po_id
		where pod.approved = 1 and po.project_id= '.$project_id.' and pod.approved_date >= "'.date('Y-m-d',strtotime($from)).'" and pod.approved_date <= "'.date('Y-m-d',strtotime($to)).'"
		GROUP BY date(po.created_date),po.po_id
		ORDER BY po.created_date DESC
		
		');
		
		
		
		// $po_data = $conn->execute(
		// 'select *,COUNT(po_id) as count from erp_inventory_po
		// GROUP BY date(created_date)
		// ORDER BY created_date DESC
		// limit 5
		// ');
		
		// $po_data = $po_tbl->find()
					// ->limit(5)
					// ->group('created_date')
					// ->order(["created_date"=>"DESC"])
					// ->hydrate(false)->toArray();
		//$po_data->po_count	=$po_count;		
		//debug($po_data);
		return $po_data;
	}
	public function dashboard_po_manual_list($project_id, $from,$to)
	{
		$po_tbl = TableRegistry::get("erp_inventory_po");
		
		$erp_manual_po = TableRegistry::get("erp_manual_po");
		$erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");	
		
		$or1["erp_manual_po_detail.approved_date >="] = date('Y-m-d',strtotime($from));
				$or1["erp_manual_po_detail.approved_date <="] = date('Y-m-d',strtotime($to));
				$or1["project_id"] = $project_id;
				$keys = array_keys($or1,"");				
				foreach ($keys as $k)
				{unset($or1[$k]);}
		
		$manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
						$manual_po_list1 = $manual_po_list->innerjoin(
							["erp_manual_po_detail"=>"erp_manual_po_detail"],
							["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved ="=>1])
							->where($or1)->select($erp_manual_po_detail)->group('erp_manual_po.po_no')->order(['erp_manual_po.po_date'=>'DESC'])->count();
		return $manual_po_list;
	}
	public function dashboard_po_count($project_id, $from,$to)
	{
		$erp_inventory_po = TableRegistry::get("erp_inventory_po");
		$erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
		
		$erp_manual_po = TableRegistry::get("erp_manual_po");
		$erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");	
		$total=0;
		$or1 = array();				
				
				$or["erp_inventory_po_detail.approved_date >="] = date('Y-m-d',strtotime($from));
				$or["erp_inventory_po_detail.approved_date <="] = date('Y-m-d',strtotime($to));
				$or["project_id"] = $project_id;
				$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
				$or1["erp_manual_po_detail.approved_date >="] = date('Y-m-d',strtotime($from));
				$or1["erp_manual_po_detail.approved_date <="] = date('Y-m-d',strtotime($to));
				$or1["project_id"] = $project_id;
				$keys = array_keys($or1,"");				
				foreach ($keys as $k)
				{unset($or1[$k]);}
		
		$result = $erp_inventory_po->find()->select($erp_inventory_po);
		$result1 = $result->innerjoin(
			["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
			["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved ="=>1])->where($or)->select($erp_inventory_po_detail)->group('erp_inventory_po.po_no')->order(['erp_inventory_po_detail.approved_date'=>'DESC'])->count();
		
		$manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
						$manual_po_list1 = $manual_po_list->innerjoin(
							["erp_manual_po_detail"=>"erp_manual_po_detail"],
							["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved ="=>1])
							->where($or1)->select($erp_manual_po_detail)->group('erp_manual_po.po_no')->order(['erp_manual_po.po_date'=>'DESC'])->count();
		
	
		$total= (int)$result1+(int)$manual_po_list1;
		
		// $po_data = $conn->execute(
		// 'select *,COUNT(po_id) as count from erp_inventory_po
		// GROUP BY date(created_date)
		// ORDER BY created_date DESC
		// limit 5
		// ');
		
		// $po_data = $po_tbl->find()
					// ->limit(5)
					// ->group('created_date')
					// ->order(["created_date"=>"DESC"])
					// ->hydrate(false)->toArray();
		//$po_data->po_count	=$po_count;		
		return $total;
	}
	public function get_po_amount($po_id, $from,$to)
	{
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$total=0;
		$po_data=array();
		$conn = ConnectionManager::get('default');
		//FOLLOWING QUERY NOT WORKING IN PHP 5.7 SO CHANGE MODE USING QUERY -> mysql > SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
		$po_data = $conn->execute(
		'select SUM(pod.amount) as total from erp_inventory_po_detail as pod where pod.po_id = '.$po_id.' and pod.approved_date >= "'.date('Y-m-d',strtotime($from)).'" and pod.approved_date <= "'.date('Y-m-d',strtotime($to)).'"')->fetchAll('assoc');	

		if(!empty($po_data)){
			$total=$po_data[0]['total'];
		}
		return($total);
		
		
	}
	public function get_po_manual_amount($po_id, $from,$to)
	{
		//$po_tbl = TableRegistry::get("erp_inventory_po");
		$total=0;
		$po_data=array();
		$conn = ConnectionManager::get('default');
		//FOLLOWING QUERY NOT WORKING IN PHP 5.7 SO CHANGE MODE USING QUERY -> mysql > SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
		$po_data = $conn->execute(
		'select SUM(pod.amount) as total from erp_manual_po_detail as pod where pod.po_id = '.$po_id.' and pod.approved_date >= "'.date('Y-m-d',strtotime($from)).'" and pod.approved_date <= "'.date('Y-m-d',strtotime($to)).'"')->fetchAll('assoc');	
		
		if(!empty($po_data)){
			$total=$po_data[0]['total'];
		}
		return($total);
		
		
	}
	public function get_wo_amount($wo_id, $from,$to)
	{
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$total=0;
		$po_data=array();
		$conn = ConnectionManager::get('default');
		//FOLLOWING QUERY NOT WORKING IN PHP 5.7 SO CHANGE MODE USING QUERY -> mysql > SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
		$po_data = $conn->execute(
		'select SUM(pod.amount) as total from erp_work_order_detail as pod where pod.wo_id = '.$wo_id.' and pod.approved_date >= "'.date('Y-m-d',strtotime($from)).'" and pod.approved_date <= "'.date('Y-m-d',strtotime($to)).'"')->fetchAll('assoc');	

		if(!empty($po_data)){
			$total=$po_data[0]['total'];
		}
		return($total);
		
		
	}
	public function dashboard_wo_list($project_id, $from,$to)
	{
		$wo_table = TableRegistry::get('erp_work_order');
		$wod_table = TableRegistry::get('erp_work_order_detail');
		
		$or = array();	
		 $or["erp_work_order.approved_date >="] =date('Y-m-d',strtotime($from)) ;
		$or["erp_work_order.approved_date <="] = date('Y-m-d',strtotime($to)); 
		$or["project_id"] = $project_id;
		
		$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
				
		$result = $wod_table->find()->select($wod_table)->where(["approved"=>1]);
		
		$result = $result->innerjoin(
			["erp_work_order"=>"erp_work_order"],
			["erp_work_order.wo_id = erp_work_order_detail.wo_id"])
			->where($or)->select($wo_table)->order(['erp_work_order.approved_date'=>'DESC'])->group('erp_work_order.wo_no')->hydrate(false)->toArray();
		return $result;
				
	}
	public function dashboard_ra_list()
	{
		$ra_tbl = TableRegistry::get("erp_contract_rabill");
		$ra_data = $ra_tbl->find()->limit(5)->order(["create_date"=>"DESC"])->hydrate(false)->toArray();
		return $ra_data;
	}
	public function dashboard_price_variation_list()
	{
		$price_variation_tbl = TableRegistry::get("erp_contract_pricevariation");
		$price_variation_data = $price_variation_tbl->find()->limit(5)->order(["create_date"=>"DESC"])->hydrate(false)->toArray();
		return $price_variation_data;
	}
	public function dashboard_Asset_Purchase_Transfer_list($project_id, $from,$to)
	{
		$Asset_Purchase_Transfer_tbl = TableRegistry::get("erp_assets");
		$Asset_history_tbl = TableRegistry::get("erp_assets_history");
		
		$or = array();	
		 $or["transfer_date >="] =date('Y-m-d',strtotime($from)) ;
		$or["transfer_date <="] = date('Y-m-d',strtotime($to)); 
		
		
		$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
		
		$Asset_Purchase_Transfer_data = $Asset_Purchase_Transfer_tbl->find()->where(['deployed_to'=> $project_id])->order(["erp_assets.created_date"=>"DESC"])->select($Asset_Purchase_Transfer_tbl);
		
		$data= $Asset_Purchase_Transfer_data->innerjoin(
			["erp_assets_history"=>"erp_assets_history"],
			["erp_assets_history.asset_id = erp_assets.asset_id"])
			->where([$or])->select($Asset_history_tbl)->limit(5)->hydrate(false)->toArray();
		
		return $data;
	}
	public function dashboard_inventory_list($project_id, $from,$to){
		$stock_tbl = TableRegistry::get("erp_stock_history");
		$erp_material = TableRegistry::get("erp_material");
		
		$or = array();				
				
		//$or["erp_stock_history.date >="] = date('Y-m-d',strtotime($from));
		//$or["erp_stock_history.date <="] = date('Y-m-d',strtotime($to));
		
		$or["erp_stock_history.project_id"] = $project_id;
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
		$stock_tbl_data = $stock_tbl->find()->where([$or])->select($stock_tbl);
		
		$stock_tbl_data_total = $stock_tbl_data->select(['total_in' => $stock_tbl_data->func()->sum('stock_in'),'total_out' => $stock_tbl_data->func()->sum('stock_out')])->group('erp_stock_history.material_id')->group('erp_stock_history.material_id');
		
		$stock_tbl_data1 = $stock_tbl_data_total->innerjoin(
							["erp_material"=>"erp_material"],
							["erp_material.material_id = erp_stock_history.material_id","erp_material.cost_group"=>"a"])->hydrate(false)->toArray();
		
		//debug($stock_tbl_data1);
		return $stock_tbl_data1;
	}
	public function dashboard_inventory_total($project_id, $from,$to,$type,$material_id){
		$stock_tbl = TableRegistry::get("erp_stock_history");
		$erp_material = TableRegistry::get("erp_material");
		$total=0.00;
		$or = array();				
				
		$or["erp_stock_history.date >="] = date('Y-m-d',strtotime($from));
		$or["erp_stock_history.date <="] = date('Y-m-d',strtotime($to));
		
		$or["erp_stock_history.project_id"] = $project_id;
		$or["erp_stock_history.project_id"] = $material_id;
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
		$stock_tbl_data = $stock_tbl->find()->where([$or]);
		
		$stock_tbl_data_total = $stock_tbl_data->select(['total_in' => $stock_tbl_data->func()->sum('stock_in'),'total_out' => $stock_tbl_data->func()->sum('stock_out')])->group('erp_stock_history.material_id')->group('erp_stock_history.material_id');
		
		$stock_tbl_data1 = $stock_tbl_data_total->innerjoin(
							["erp_material"=>"erp_material"],
							["erp_material.material_id = erp_stock_history.material_id","erp_material.cost_group"=>"a"])->hydrate(false)->toArray();
		
		if(!empty($stock_tbl_data1)){
			$total=$stock_tbl_data1[0][$type];
		}
		return($total);
		//return $stock_tbl_data1;
	}
	
	public function dashboard_inventory_stock($project_id,$materialid,$type){
		$stock_tbl = TableRegistry::get("erp_stock_history");
		$erp_material = TableRegistry::get("erp_material");
		$account_bill=0;
		$or = array();	
		
		$or["erp_stock_history.project_id"] = $project_id;
		$or["erp_stock_history.	material_id"] = $materialid;
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
		$stock_tbl_data = $stock_tbl->find()->where([$or])->select($stock_tbl);
		
		$stock_tbl_data_total = $stock_tbl_data->select(['total' => $stock_tbl_data->func()->sum($type)])->group('erp_stock_history.material_id');
		
		$stock_tbl_data1 = $stock_tbl_data_total->innerjoin(
							["erp_material"=>"erp_material"],
							["erp_material.material_id = erp_stock_history.material_id","erp_material.cost_group"=>"a"])->hydrate(false)->toArray();
		
		if(!empty($stock_tbl_data1)){
			$account_bill=$stock_tbl_data1[0]['total'];
		}
		return $account_bill;
		
	}
	
	public function dashboard_accounts($project_id, $from,$to){
		$inward_tbl = TableRegistry::get("erp_inward_bill");
		
		$or = array();				
		$account_bill = 0;				
		$inward_data1 = array();				
				
		$or["date >="] = date('Y-m-d',strtotime($from));
		$or["date <="] = date('Y-m-d',strtotime($to));
		
		$or["project_id"] = $project_id;
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
		$inward_data = $inward_tbl->find()->where([$or]);
		$inward_data1 = $inward_data->select(['total_amt' => $inward_data->func()->sum('total_amt')])->group('project_id')->hydrate(false)->toArray();
		
		if(!empty($inward_data1)){
			$account_bill=$inward_data1[0]['total_amt'];
		}
		return $account_bill;
		
	}
	public function dashboard_view_site($project_id, $from,$to){
		$erp_expence_add = TableRegistry::get("erp_expence_add");
				$erp_expence_detail = TableRegistry::get("erp_expence_detail");
				
		$or = array();				
		$account_bill = 0;				
		$inward_data1 = array();				
				
		$or["date >="] = date('Y-m-d',strtotime($from));
		$or["date <="] = date('Y-m-d',strtotime($to));
		
		$or["project_id"] = $project_id;
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
		/* $inward_data = $erp_amount_issue->find()->where([$or]);
		$inward_data1 = $inward_data->select(['total_amt' => $inward_data->func()->sum('total_amt')])->group('project_id')->hydrate(false)->toArray(); */
		
		$result1 = $erp_expence_add->find();
		$result2 = $result1->innerjoin(
					["erp_expence_detail"=>"erp_expence_detail"],
					["erp_expence_add.id = erp_expence_detail.exp_id"])
					->where($or)->select(['total_amt' => $result1->func()->sum('expence_total')])->group('erp_expence_detail.exp_id');
		
		$inward_data1 = $result2->select(['total_amt' => $result2->func()->sum('expence_total')])->group('project_id')->hydrate(false)->toArray();
		//debug($inward_data1);
		if(!empty($inward_data1)){
			$account_bill=$inward_data1[0]['total_amt'];
		}
		return $account_bill;
		
	}
	public function dashboard_advance($project_id, $from,$to){
		$erp_advance_request = TableRegistry::get('erp_advance_request'); 
		$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail'); 
		
		$or = array();				
		$account_bill = 0;				
		$erp_advance_data1 = array();				
				
		$or["transfer_date >="] = date('Y-m-d',strtotime($from));
		$or["transfer_date <="] = date('Y-m-d',strtotime($to));
		
		
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
		
		$result = $erp_advance_request->find()->select($erp_advance_request)->where(['erp_advance_request.project_id'=>$project_id]);
		$result1 = $result->innerjoin(
			["erp_advance_request_detail"=>"erp_advance_request_detail"],
			["erp_advance_request.request_id = erp_advance_request_detail.request_id","erp_advance_request_detail.approval_export"=>1])
			->where($or)->select($erp_advance_request_detail);
				
		$erp_advance_data1 = $result1->select(['total_amt' => $result1->func()->sum('cheque_amount')])->group('erp_advance_request.project_id')->hydrate(false)->toArray();

		if(!empty($erp_advance_data1)){
			$account_bill=$erp_advance_data1[0]['total_amt'];
		}

		return $account_bill;
		
	}
	public function dashboard_debitnots($project_id, $from,$to){
		$erp_debit_note = TableRegistry::get("erp_debit_note");
		$erp_debit_note_detail = TableRegistry::get('erp_debit_note_detail'); 
		
		$or = array();				
		$account_bill = 0;				
		$erp_advance_data1 = array();				
				
		$or["date >="] = date('Y-m-d',strtotime($from));
		$or["date <="] = date('Y-m-d',strtotime($to));
		
		
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
		
		$result = $erp_debit_note->find()->where(['erp_debit_note.project_id'=>$project_id]);
		$result1 = $result->innerjoin(
			["erp_debit_note_detail"=>"erp_debit_note_detail"],
			["erp_debit_note_detail.debit_id = erp_debit_note.debit_id",'second_approved'=>1])
			->where($or);
			
		$erp_advance_data1 = $result1->select(['total_amount' => $result1->func()->sum('amount')])->hydrate(false)->toArray();

		
		//debug($erp_advance_data1);
		if(!empty($erp_advance_data1)){
			$account_bill=$erp_advance_data1[0]['total_amount'];
		}

		return $account_bill;
		
	}
	public function dashboard_payslip_alerts($project_id){
		$salary_tbl = TableRegistry::get("erp_salary_slip");			
			$usr_tbl = TableRegistry::get("erp_users");		
		
		
			$salary_data = $salary_tbl->find()->where(['erp_salary_slip.employee_at'=>$project_id])->select($salary_tbl);
			$salary_data = $salary_data->rightjoin(
						["erp_users"=>"erp_users"],
						["erp_users.user_id = erp_salary_slip.user_id"]
						)->select($usr_tbl)->count();
		
		return $salary_data;
		
	}
	public function dashboard_Latest_GRN_list()
	{
		$GRN_tbl = TableRegistry::get("erp_inventory_grn");
		$GRN_data = $GRN_tbl->find()->limit(5)->order(["created_date"=>"DESC"])->hydrate(false)->toArray();
		return $GRN_data;
	}
	public function dashboard_Latest_SST_list()
	{
		$SST_tbl = TableRegistry::get("erp_inventory_sst");
		$SST_data = $SST_tbl->find()->limit(5)->order(["created_date"=>"DESC"])->hydrate(false)->toArray();
		return $SST_data;
	}
	public function get_approveis_details($is_id)
	{
		$is_tbl = TableRegistry::get("erp_inventory_is_detail");
		$data = $is_tbl->find()->where(["is_id"=>$is_id])->first();
		return $data;
	}
	
	public function get_stockledger_description($type,$project_id,$material_id) /* NO USE*/
	{		
		$history_tbl = TableRegistry::get("erp_stock_history");
		$type_data = $history_tbl->find()->where(["type"=>$type,"project_id"=>$project_id,"material_id"=>$material_id])->select(["type_id"])->hydrate(false)->toArray();
		$desc["desc"] = "";
		$desc["code"] = "";
		
		if(!empty($type_data))
		{
			$type_id = $type_data[0]["type_id"];
		
			$grn_tbl = TableRegistry::get("erp_inventory_grn");
			$is_tbl = TableRegistry::get("erp_inventory_is");
			$sst_tbl = TableRegistry::get("erp_inventory_sst");
			$mrn_tbl = TableRegistry::get("erp_inventory_mrn");
			$rbn_tbl = TableRegistry::get("erp_inventory_rbn");
		
			switch($type)
			{
				CASE "grn":
					$desc = $grn_tbl->get($type_id)->toArray();
					$desc["desc"] = $this->get_vendor_name($desc["vendor_userid"]);
					$desc["code"] = $desc["grn_no"];
				break;
				
				CASE "grnlp":
					$desc = $grn_tbl->get($type_id)->toArray();
					$desc["desc"] = $this->get_vendor_name($desc["vendor_userid"]);
					$desc["code"] = $desc["grn_no"];
				break;
				
				CASE "is":
					$desc = $is_tbl->get($type_id)->toArray();
					$desc["desc"] = $this->get_vendor_name($desc["agency_name"]);
					$desc["code"] = $desc["is_no"];
				break;
				
				CASE "sst":
				
				break;
				
				CASE "mrn":
					$desc = $mrn_tbl->get($type_id)->toArray();
					$desc["desc"] = $this->get_vendor_name($desc["vendor_user"]);
					$desc["code"] = $desc["mrn_no"];
				break;
				
				CASE "rbn":
					$desc = $rbn_tbl->get($type_id)->toArray();
					$desc["desc"] = $this->get_vendor_name($desc["agency_name"]);
					$desc["code"] = $desc["rbr_no"];
				break;				
			}			
		}
		return $desc;
	}
	
	public function get_stockledger_description_code($type,$type_id) 
	{	
		$grn_tbl = TableRegistry::get("erp_inventory_grn");
		$is_tbl = TableRegistry::get("erp_inventory_is");
		$sst_tbl = TableRegistry::get("erp_inventory_sst");
		$mrn_tbl = TableRegistry::get("erp_inventory_mrn");
		$rbn_tbl = TableRegistry::get("erp_inventory_rbn");
		$debit_tbl = TableRegistry::get("erp_inventory_debit_note");
		$erp_inventory_rmc = TableRegistry::get("erp_inventory_rmc");
		$desc["desc"] = "";
		$desc["code"] = "";
		
		switch($type)
		{
			CASE "grn":
				$desc = $grn_tbl->get($type_id)->toArray();
				$desc["desc"] = $this->get_vendor_name($desc["vendor_userid"]);
				$desc["code"] = $desc["grn_no"];
			break;
			
			CASE "grnlp":
				$desc = $grn_tbl->get($type_id)->toArray();
				$desc["desc"] = $this->get_vendor_name($desc["vendor_userid"]);
				$desc["code"] = $desc["grn_no"];
			break;
			
			CASE "is":
				$desc = $is_tbl->get($type_id)->toArray();
				$is_asset = explode("_",$desc['agency_name']);
				if(isset($is_asset[1]))
				{
					$desc["desc"] = $this->get_asset_name($is_asset[1]);
				}else{
					$desc["desc"] = $this->get_vendor_name($desc['agency_name']); 
				}
				/* $desc["desc"] = $this->get_agency_name($desc["agency_name"]); */
				$desc["code"] = $desc["is_no"];
			break;
			
			CASE "sst_to":
				$desc = $sst_tbl->get($type_id)->toArray();
				/* $desc["desc"] = $this->get_projectcode($desc["transfer_to"]); */
				$desc["desc"] = $this->get_projectcode($desc["project_id"]);
				$desc["code"] = $desc["sst_no"];
			break;
			
			CASE "sst_from":
				$desc = $sst_tbl->get($type_id)->toArray();
				/* $desc["desc"] = $this->get_projectcode($desc["project_id"]); */
				$desc["desc"] = $this->get_projectcode($desc["transfer_to"]);
				$desc["code"] = $desc["sst_no"];
			break;
			
			CASE "mrn":
				$desc = $mrn_tbl->get($type_id)->toArray();
				$desc["desc"] = $this->get_vendor_name($desc["vendor_user"]);
				$desc["code"] = $desc["mrn_no"];
			break;
			
			CASE "rbn":
				$desc = $rbn_tbl->get($type_id)->toArray();
				$desc["desc"] = $this->get_vendor_name($desc["agency_name"]);
				$desc["code"] = $desc["rbn_no"];
			break;
			CASE "debit":
				$desc = $debit_tbl->get($type_id)->toArray();
				$desc["desc"] = "Debit Note - ".$this->get_vendor_name($desc["debit_to"]);
				$desc["code"] = $desc["debit_note_no"];
			break;
			CASE "debit_party":
				$desc = $debit_tbl->get($type_id)->toArray();
				$desc["desc"] = "Debit Note";
				$desc["code"] = $desc["debit_note_no"];
			break;
			CASE "rmc":
				$desc = $erp_inventory_rmc->get($type_id)->toArray();
				$desc["desc"] = $this->get_vendor_name($desc["agency_id"]);
				$desc["code"] = $desc["rmc_no"];
			break;
		}			
	
		return $desc;
	}
	
	public function get_stock_balance($type,$old_stock,$new_stock)
	{		
		switch($type)
		{
			CASE "is":
				return $old_stock - $new_stock;
			break;
			
			CASE "rmc":
				return $old_stock - $new_stock;
			break;
			
			CASE "mrn":
				return $old_stock - $new_stock;
			break;
			
			CASE "rbn":
				return $old_stock + $new_stock;
			break;
			
			CASE "grn":
				return $old_stock + $new_stock;
			break;
			
			CASE "sst_from":
				return $old_stock - $new_stock;
			break;
			CASE "sst_to":
				return $old_stock + $new_stock;
			break;
			CASE "debit":
				return $old_stock + $new_stock;
			break;
			CASE "debit_party":
				return $old_stock - $new_stock;
			break;
			
			default :
				return $old_stock + $new_stock;
		}
	}
	
	public function get_symbolic_stock_balance($type,$old_symbolic_stock,$new_stock)
	{		
		switch($type)
		{
			// CASE "is":
				// return $old_symbolic_stock - $new_stock;
			// break;
			
			CASE "mrn":
				return $old_symbolic_stock - $new_stock;
			break;
			
			// CASE "rbn":
				// return $old_symbolic_stock + $new_stock;
			// break;
			
			CASE "grn":
				return $old_symbolic_stock + $new_stock;
			break;
			
			CASE "sst_from":
				return $old_symbolic_stock - $new_stock;
			break;
			// CASE "sst_to":
				// return $old_symbolic_stock + $new_stock;
			// break;
			
			default :
				return $old_symbolic_stock;
		}
	}
	
	public function old_project($user_id)
	{
		$projects = array();
		$erp_projects_assign = TableRegistry::get('erp_projects_assign');
		$results = $erp_projects_assign->find()->where(array('user_id'=>$user_id));
		foreach($results as $retrive_data)
		{
			$projects[] = $retrive_data['project_id'];
		}
		return $projects;
	}
	
	public function get_user_projects($user_id)
	{
		$assign_projects = $this->old_project($user_id);
		$projects = null ;
		if(!empty($assign_projects))
		{
			foreach($assign_projects as $project_id)
			{
				$projects .= "<span class='label label-info' style='font-size:11px;'>".$this->get_projectname($project_id)."</span> &nbsp;";				
			}
			$projects = trim($projects,",-");
			return $projects;
		}else{
			return "-";
		}		
	}
	
	public function get_user_assign_projects_id($user_id)
	{
		$assign_projects = $this->old_project($user_id);
		$projects = array();
		if(!empty($assign_projects))
		{
			foreach($assign_projects as $project_id)
			{
				$projects[] = $project_id;				
			}
		}
		return $projects;
	}
	
	public function get_total_rabills($project_id)
	{
		$total_amount = 0;
		$rabill_tbl = TableRegistry::get("erp_contract_rabill");
		$data = $rabill_tbl->find()->where(["project_id"=>$project_id])->select("total_bill_amt")->hydrate(false)->toArray();
		if(!empty($data))
		{
			foreach($data as $row)
			{
				$total_amount += intval($row["total_bill_amt"]);
			}
		}
		return number_format($total_amount,2,'.','');
	}
	
	public function get_total_pricevariation($project_id)
	{
		$total_amount = 0;
		$price_tbl = TableRegistry::get("erp_contract_pricevariation");
		$data = $price_tbl->find()->where(["project_id"=>$project_id])->select("paid_amt")->hydrate(false)->toArray();
		if(!empty($data))
		{
			foreach($data as $row)
			{
				echo $total_amount += intval($row["paid_amt"]);
			}
		}
		
		return number_format($total_amount,2,'.','');
	}
	
	public function get_total_work_done($project_id,$total_ra_bills = null,$revise_amt = null)
	{		
		$total = 0;
		if($total_ra_bills != 0 && $revise_amt != 0)
		{ 
			$total = ($total_ra_bills / $revise_amt) * 100;
		}
		return $total;
	}
	public function work_done($total_work_done = null,$revise_amt = null)
	{
		$total = 0;
		if($total_work_done != 0 && $revise_amt != 0)
		{ 
			$total = ($total_work_done / $revise_amt) * 100;
		}
		return number_format($total,2,'.','');
	}
	public function get_total_salary($employee_id)
	{
		$user_tbl = TableRegistry::get("erp_users");
		$data = $user_tbl->find()->where(["user_id"=>$employee_id])->select("total_salary")->hydrate(false)->toArray();
		return $data[0]["total_salary"];
	}
	
	public function get_po_records($po_id="")
	{
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$po_row = $po_tbl->get($po_id)->toArray();
		
		if(!empty($po_row))
			return $po_row;
		else
			return null;
	}
	
	public function get_pr_records($pr_id="")
	{
		if($pr_id != "")
		{
			$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
			$pr_row = $pr_tbl->get($pr_id)->toArray();
			
			if(!empty($pr_row))
				return $pr_row;
			else
				return null;
		}else{
			return null;
		}
	}
	
	public function get_current_stock($project_id,$material_id)
	{		
		$history_tbl = TableRegistry::get("erp_stock_history");
		$opening_stock = 0;
		if(is_numeric($material_id))
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		
		if(!empty($opening_stock_data))
		{
			$opening_stock = $opening_stock_data[0]["quantity"];			
		}
		
		if(is_numeric($material_id))
		{
		$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type !="=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id,"type !="=>"os"])->hydrate(false)->toArray();
		}
		if(isset($stockledger))
		{
			foreach($stockledger as $retrive_data)
			{
				$opening_stock = $this->get_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
	}
	
	public function get_pr_material_id($pmid)
	{
		$tbl = TableRegistry::get("erp_inventory_pr_material");
		$data = $tbl->find()->where(["pr_material_id"=>$pmid])->hydrate(false)->toArray();
		if(!empty($data))
		{
			return $data[0]["material_id"];
		}else{return "na";}
	}
	
	public function get_brands_by_material_id($material_id)
	{
		$mtbl = TableRegistry::get("erp_material");
		$mbtbl = TableRegistry::get("erp_material_brand");		
		
		$mat_type = $mtbl->find()->where(["material_id"=>$material_id])->select("material_code");		
		$cnt = $mat_type->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$mat_type = $tmp_tbl->find()->where(['material_id'=>$material_id])->select("material_code");
		}
		
		$mat_type = $mat_type->hydrate(false)->toArray();
		$brands = $mbtbl->find("all")->where(["material_type"=>"{$mat_type[0]['material_code']}"])->hydrate(false)->toArray();
		
		if(!empty($brands))
		{
			return $brands;
		}		
		return "";	
	}
	
	public function viewheader($date = null)
	{
		if($date != null){
			$date = date("Y-m-d",strtotime($date));
			if($date >= "2019-03-01")
			{
				return "<img src='{$this->request->base}/img/logo/header_new.jpg' style='width:100%;height:55%;padding-right:28px;'>";
			}else{
				return "<img src='{$this->request->base}/img/logo/header.jpg' style='width:100%;height:55%;padding-right:28px;'>";
			}
		}else{
			return "<img src='{$this->request->base}/img/logo/header_new.jpg' style='width:100%;height:55%;padding-right:28px;'>";
		}
		
	}
	public function viewheader_po($date,$state)
	{
		$date = date("Y-m-d",strtotime($date));
		if($state != 'mp'){
			if($date >= "2019-03-01")
			{
				return "<img src='{$this->request->base}/img/logo/header_new.jpg' style='width:100%;height:55%;padding-right:28px;'>";
			}else{
				return "<img src='{$this->request->base}/img/logo/header.jpg' style='width:100%;height:55%;padding-right:28px;'>";
			}
		}else{
			if($date >= "2019-03-08")
			{
				return "<img src='{$this->request->base}/img/logo/header_new.jpg' style='width:100%;height:55%;padding-right:28px;'>";
			}else{
				return "<img src='{$this->request->base}/img/logo/header.jpg' style='width:100%;height:55%;padding-right:28px;'>";
			}
		}
	}
	public function viewheader_pdf($date = null)
	{
		// if($date != null)
		// {
		// 	$date = date("Y-m-d",strtotime($date));
		// 	if($date >= "2019-03-01")
		// 	{
		// 		return "<img src='{$this->request->base}/img/logo/header_new.jpg' style='width:100%;height:55%;padding-right:28px;'>";
		// 	}else{
		// 		return "<img src='{$this->request->base}/img/logo/header.jpg' style='width:100%;height:55%;padding-right:28px;'>";
		// 	}
		// }else{
		// 	return "<img src='{$this->request->base}/img/logo/header_new.jpg' style='width:100%;height:55%;padding-right:28px;'>";
		// }
		return "<img src='{$this->request->base}/img/logo/header_new.jpg' style='width:100%;height:55%;padding-right:28px;'>";
	}
	
	public function get_employee_name($eid)
	{
		$tbl = TableRegistry::get("erp_users");
		$name = $tbl->find()->where(["user_id"=>$eid])->select(["first_name","last_name"])->hydrate(false)->toArray();
		$name = $name[0]["first_name"] . " " . $name[0]["last_name"];
		return $name;
	}
	
	public function get_user_designation($uid)
	{
		$tbl = TableRegistry::get("erp_users");
		$cat_id = $tbl->find()->where(["user_id"=>$uid])->select(["designation"])->hydrate(false)->toArray();
		$cat_id = $cat_id[0]["designation"];
		$designation = $this->get_category_title($cat_id);
		return $designation;
	}
	
	public function get_employee_in_time($user_id,$date)
	{
		$tbl = TableRegistry::get("erp_attendance");
		$in_time = $tbl->find()->where(["user_id"=>$user_id,"attendance_date"=>$date])->select("day_in_time")->hydrate(false)->toArray();
		if(!empty($in_time))
		{
			return date("H:i",strtotime($in_time[0]["day_in_time"]));
		}else{
			return "-";
		}		
	}
	
	public function get_employee_out_time($user_id,$date)
	{
		$tbl = TableRegistry::get("erp_attendance");
		$out_time = $tbl->find()->where(["user_id"=>$user_id,"attendance_date"=>$date])->hydrate(false)->toArray();	
		if(!empty($out_time))
		{ 
			$out_time = $out_time[0]["day_out_time"];
			if($out_time == null || $out_time == "")
			{ 
				return "-";
			}else{	
				$out_time = date("H:i",strtotime($out_time));	
				return $out_time;
			}
		}else{
			return "-";
		}		
	}
	
	public function get_employee_total_time($user_id,$date)
	{
		$tbl = TableRegistry::get("erp_attendance");
		$in_time = $tbl->find()->where(["user_id"=>$user_id,"attendance_date"=>$date])->select("working_hours")->hydrate(false)->toArray();
		if(!empty($in_time))
		{
			return $in_time[0]["working_hours"];
		}else{
			return "-";
		}		
	}
	
	public function get_loan_outstanding($user_id)
	{

		$tbl = TableRegistry::get("erp_loan");

		$user = $tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$loan_os = 0;
		
		if(!empty($user))
		{
			foreach ($user as $users)
			{
				$loan_os += $users["outstanding"];
			}
		}
		
		
		return $loan_os;
		return $installment_amount;
	}	
	public function get_installment($user_id)
	{
		
		$tbl = TableRegistry::get('erp_loan');
	
		$user = $tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();


	
		$installment_amount =0 ;
		$loan_os=0;
		
		if(!empty($user))
		{
			foreach ($user as $users)
			{
				$installment= $users["installment"];
				$loan_os = $users["outstanding"];
				if($loan_os < $installment)
				{
					$installment_amount += $users["outstanding"];
				}
				else
				{
					$installment_amount += $users["installment"];
				}
			}
			
		}
		
		return $installment_amount;
	}
	
	public function expence_head_name($head_id)
	{
		$erp_expense = TableRegistry::get("erp_expense");
		$head_name = $erp_expense->find()->where(["expence_id"=>$head_id])->select('expence_head_name')->hydrate(false)->toArray();
		if(!empty($head_name))
		{
			$head = $head_name[0]["expence_head_name"];
		}
		return $head;
	}

	public function getmaterialbrandlist($material_id,$project_id)
	{
	
		$returnarray['opening_stock'] = "None";	

		$history_tbl = TableRegistry::get("erp_stock_history");
		/* $data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray(); 
		if(!empty($data))
		{$returnarray['opening_stock'] = $data[0]["quantity"];}
		*/
		$opening_stock = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		
		$data = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type !="=>"os"])->hydrate(false)->toArray();
		
		/* $opening_stock = 0;  MOVED TO ELSE*/
		if(!empty($opening_stock))
		{
			$opening_stock = $opening_stock[0]["quantity"];
		}else{
			
			$opening_stock = 0;
		}
		
		if(!empty($data))
		{
			foreach($data as $retrive_data)
			{
				$opening_stock = $this->get_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		/* $returnarray['opening_stock'] = $opening_stock; */
		
		return $opening_stock;
		
	}
	
	public function get_vendor_contact($vendor_id,$field)
	{
		$tbl = TableRegistry::get("erp_vendor");
		$row = $tbl->find()->where(["user_id"=>$vendor_id])->hydrate(false)->toArray();
		$contact = "NA";
		if(!empty($row))
		{
			if($field == "one")
			{
				$contact = $row[0]["contact_no1"];
			}
			if($field == "two")
			{
				$contact = $row[0]["contact_no2"];
			}
		}
		return $contact;
	}
	
	public function account_name($account_id)
	{
		$erp_account = TableRegistry::get("erp_account");
		$account_name = $erp_account->find()->where(["account_id"=>$account_id])->select('account_name')->hydrate(false)->toArray();
		if(!empty($account_name))
		{
			$account = $account_name[0]["account_name"];
		}
		return $account;
	}
	
	public function get_pr_contact($pr_mid,$field)
	{
		// var_dump($pr_mid);
		// var_dump($field);die;
		$erp_material = TableRegistry::get('erp_inventory_purhcase_request');
		$erp_inventory_pr_material = TableRegistry::get('erp_inventory_pr_material');
		if($pr_mid != 0)
		{
		$pr_material_detail = $erp_inventory_pr_material->find()->where(['pr_material_id'=>$pr_mid])->hydrate(false)->toArray();
		$pr_id = $pr_material_detail[0]['pr_id'];
		$row = $erp_material->find()->where(["pr_id"=>$pr_id])->hydrate(false)->toArray();
		$contact = "NA";
		if(!empty($row))
		{
			if($field == "one")
			{
				$contact = $row[0]["contact_no1"];
			}
			if($field == "two")
			{
				$contact = $row[0]["contact_no2"];
			}
		}
		return $contact;
		}
		else
		{
			return "";
		}
	}
	public function get_user_history($user_id,$check_ch_date)
	{
		$h_tbl = TableRegistry::get("erp_users_history");
		$user_data = $h_tbl->find()->where(["user_id"=>$user_id,"change_date"=>$check_ch_date])->hydrate(false)->toArray();
		return $user_data[0];
	}
	
	public function get_user_designation_by_date($user_id,$date)
	{
		$user_tbl = TableRegistry::get("erp_users");
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$date = date("Y-m-d",strtotime($date));
		
		$pay_change = ($user_data[0]["is_pay_structure_change"] == 1) ? true : false ;
		$change_date = $user_data[0]["change_date"];
		if($pay_change)
		{
			$change_month = date("n",strtotime($change_date));
			$change_date = $change_date->format("Y-m-d");
			$curr_date_stamp = strtotime($date);
			$change_date_stamp =  strtotime($change_date);
			
			if($curr_date_stamp < $change_date_stamp) //NO NEED TO CHECK YEAR. LOAD OLD DATA FROM HISTORY TABLE
			{
				$check_ch_date = $change_date;//->format("Y-m-d");
				$h_tbl = TableRegistry::get("erp_users_history");
				$user_data = $h_tbl->find()->where(["user_id"=>$user_id,"change_date"=>$check_ch_date])->hydrate(false)->toArray();
			}
		}
		
		return $this->get_category_title($user_data[0]['designation']);
	}
	
	public function generate_auto_id_prepare_po($last_code)
	{
		$date = date('d-m-Y');
		if($last_code)
		{
			$number = explode("/",$last_code);
			$digit = (int) $number[4];
			$new_no = $digit + 1;
		}
		else
		{
			$detail_tbl= TableRegistry::get("erp_inventory_po_detail");
			$desc_fld = "m_code";
			$code = "YNEC/MT/TEMP/{$date}/";
			$data = $detail_tbl->find()->where(["m_code LIKE"=>"%YNEC/MT/TEMP/{$date}%"])->hydrate(false)->toArray();
			
			if(empty($data))
			{
				$new_no = 1;
			}
			else
			{
				foreach($data as $code)
				{
				$number = explode("/",$code["m_code"]);
				$cd[] = (int) $number[4];
				}
				$mx = max($cd);
				$new_no = $mx + 1;
			}
		}
		return "YNEC/MT/TEMP/{$date}/{$new_no}";
	}
	
	public function generate_auto_id_prepare_pr($last_code)
	{
		$date = date('d-m-Y');
		if($last_code)
		{
			$number = explode("/",$last_code);
			$digit = (int) $number[4];
			$new_no = $digit + 1;
		}
		else
		{
			$detail_tbl= TableRegistry::get("erp_inventory_pr_material");
			$desc_fld = "m_code";
			$code = "YNEC/MT/TEMP/{$date}/";
			$data = $detail_tbl->find()->where(["m_code LIKE"=>"%YNEC/MT/TEMP/{$date}%"])->hydrate(false)->toArray();
			
			if(empty($data))
			{
				$new_no = 1;
			}
			else
			{
				foreach($data as $code)
				{
				$number = explode("/",$code["m_code"]);
				$cd[] = (int) $number[4];
				}
				$mx = max($cd);
				$new_no = $mx + 1;
			}
		}
		return "YNEC/MT/TEMP/{$date}/{$new_no}";
	}
	
	public function get_attendance_detail($user_id,$month,$year,$field)
	{
		$detail_tbl= TableRegistry::get("erp_attendance_detail");
		$data = $detail_tbl->find()->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year])->select($field)->hydrate(false)->toArray();
		
		if(!empty($data))
		{
			return $data[0][$field];
		}
		else
		{
			return '-';
		}
	}
	
	public function get_vendor_by_rate($rate_id)
	{
		$erp_finalized_rate= TableRegistry::get("erp_finalized_rate");
		$data = $erp_finalized_rate->find()->where(["rate_id"=>$rate_id])->hydrate(false)->toArray();
		
		if(!empty($data))
		{
			return $data[0]['vendor_userid'];
		}
		else
		{
			return '-';
		}
	}
	
	public function get_rate_assign_project($rate_id)
	{
		$erp_rate_assign_project= TableRegistry::get("erp_rate_assign_project");
		$row = $erp_rate_assign_project->find()->where(["rate_id"=>$rate_id])->hydrate(false)->toArray();
		$project = array();
		if(!empty($row))
		{
			foreach($row as $data)
			{
				$project[] = $data['project_id'];
			}
		}
		
		return $project;
	}
	
	public function get_multiple_projectname($project_group)
	{	
		$project_ids = explode(',',$project_group);
		$project_name = array();
		foreach($project_ids as $project_id)
		{
			$project_name[] = $this->get_projectname($project_id);
		}
		return implode(',',$project_name);
	}
	
	public function contract_type_list()
	{
		
		// $contract[] = array('id'=>'1','title'=>'Labour');
		// $contract[] = array('id'=>'2','title'=>'Operation');
		// $contract[] = array('id'=>'3','title'=>'Maintenance');
		// $contract[] = array('id'=>'4','title'=>'Operation & Maintenance(without Material)');
		// $contract[] = array('id'=>'5','title'=>'Material / Job Work');
		// $contract[] = array('id'=>'6','title'=>'Labour with Material');
		// $contract[] = array('id'=>'7','title'=>'Operation & Maintenance(with Material)');
		
		$contract[] = array('id'=>'1','title'=>'Labour');
		$contract[] = array('id'=>'6','title'=>'Labour with Material');
		$contract[] = array('id'=>'5','title'=>'Material / Job Work');
		$contract[] = array('id'=>'3','title'=>'Maintenance');
		$contract[] = array('id'=>'2','title'=>'Maintenance with Material');
		$contract[] = array('id'=>'4','title'=>'Operation & Maintenance(without Material)');
		$contract[] = array('id'=>'7','title'=>'Operation & Maintenance(with Material)');
		
		return $contract;
	}
	
	public function get_contract_title($contract_id)
	{
		$contract = $this->contract_type_list();
		foreach($contract as $key => $value)
		{
			if($contract_id == $value['id'])
			{
				return $value['title'];
			}
		}
	}
	
	public function generate_auto_id_work_head()
	{
		$erp_work_head= TableRegistry::get("erp_work_head");
		$desc_fld = "work_head_code";
		$data = $erp_work_head->find('all')->hydrate(false)->toArray();
		
		if(!empty($data))
		{
			foreach($data as $code)
			{
			$number = explode("/",$code["work_head_code"]);
			$cd[] = (int) $number[1];
			}
			$mx = max($cd);
			$new_no = $mx + 1;
		}
		else
		{
			$new_no = 0;
		}
		
		return "WH/{$new_no}";
	}
	
	public function generate_auto_id_planning_work_head()
	{
		$erp_work_head= TableRegistry::get("erp_planning_work_head");
		$desc_fld = "work_head_code";
		$data = $erp_work_head->find('all')->hydrate(false)->toArray();
		
		if(!empty($data))
		{
			foreach($data as $code)
			{
			$number = explode("/",$code["work_head_code"]);
			$cd[] = (int) $number[1];
			}
			$mx = max($cd);
			$new_no = $mx + 1;
		}
		else
		{
			$new_no = 0;
		}
		
		return "WH/{$new_no}";
	}
	
	public function get_work_head_title($head_id)
	{
		$work_head = TableRegistry::get('erp_work_head');
		$results = $work_head->find()->where(array('work_head_id'=>$head_id));
		
		$head_title = "-";
		foreach($results as $retrive_data)
		{
			$head_title = $retrive_data['work_head_title'];					
		}
		return $head_title;
	}
	
	public function get_planning_work_head_title($head_id)
	{
		$work_head = TableRegistry::get('erp_planning_work_head');
		$results = $work_head->find()->where(array('work_head_id'=>$head_id));
		
		$head_title = "-";
		foreach($results as $retrive_data)
		{
			$head_title = $retrive_data['work_head_title'];					
		}
		return $head_title;
	}
	
	public function get_total_expence($expence_id)
	{
		$detail_tbl = TableRegistry::get('erp_expence_detail');
		$data = $detail_tbl->find()->where(["exp_id"=>$expence_id])->select(["expence_total"])->first();
		if(!empty($data))
		{
			return $data->expence_total;
		}
		else{
			return "";
		}
	}
	
	public function get_total_debit($debit_id)
	{
		$detail_tbl = TableRegistry::get('erp_debit_note_detail');
		$data = $detail_tbl->find()->where(["debit_id"=>$debit_id])->select(["total_amount"])->first();
		if(!empty($data))
		{
			return $data->total_amount;
		}
		else{
			return "";
		}
	}
	
	public function check_expence_approve($expence_id)
	{
		$detail_tbl = TableRegistry::get('erp_expence_detail');
		$data = $detail_tbl->find()->where(["exp_id"=>$expence_id,'approval_accountant'=>1]);
		$cnt = $data->count();
		if($cnt == 0)
		{
			$is_approve = 1;
		}else {
			$is_approve = 0;
		}
		
		return $is_approve;
	}
	
	public function check_debit_approve($debit_id)
	{
		$detail_tbl = TableRegistry::get('erp_debit_note_detail');
		$data = $detail_tbl->find()->where(["debit_id"=>$debit_id,'second_approved'=>0]);
		$cnt = $data->count();
		if($cnt == 0)
		{
			$is_approve = 1;
		}else {
			$is_approve = 0;
		}
		
		return $is_approve;
	}
	
	public function check_expence_approve_by_cmpdmd($expence_id)
	{
		$detail_tbl = TableRegistry::get('erp_expence_detail');
		$data = $detail_tbl->find()->where(["exp_id"=>$expence_id,'approval_cmpdmd'=>1]);
		$cnt = $data->count();
		if($cnt == 0)
		{
			$is_approve = 0;
		}else {
			$is_approve = 1;
		}
		
		return $is_approve;
	}
	
	public function check_expence_approve_by_accountant($expence_id)
	{
		$detail_tbl = TableRegistry::get('erp_expence_detail');
		$data = $detail_tbl->find()->where(["exp_id"=>$expence_id,'approval_accountant'=>1]);
		$cnt = $data->count();
		if($cnt == 0)
		{
			$is_approve = 0;
		}else {
			$is_approve = 1;
		}
		
		return $is_approve;
	}
	
	public function check_debit_approve_first_step($debit_id)
	{
		$detail_tbl = TableRegistry::get('erp_debit_note_detail');
		$data = $detail_tbl->find()->where(["debit_id"=>$debit_id,'first_approved'=>1]);
		$cnt = $data->count();
		if($cnt == 0)
		{
			$is_approve = 0;
		}else {
			$is_approve = 1;
		}
		
		return $is_approve;
	}
	
	public function check_debit_approve_second_step($debit_id)
	{
		$detail_tbl = TableRegistry::get('erp_debit_note_detail');
		$data = $detail_tbl->find()->where(["debit_id"=>$debit_id,'second_approved'=>1]);
		$cnt = $data->count();
		if($cnt == 0)
		{
			$is_approve = 0;
		}else {
			$is_approve = 1;
		}
		
		return $is_approve;
	}
	
	public function check_inventory_debit_approve_second_step($debit_id)
	{
		$detail_tbl = TableRegistry::get('erp_inventory_debit_note_detail');
		$data = $detail_tbl->find()->where(["debit_id"=>$debit_id,'second_approved'=>1]);
		$cnt = $data->count();
		if($cnt == 0)
		{
			$is_approve = 0;
		}else {
			$is_approve = 1;
		}
		
		return $is_approve;
	}
	
	public function drawing_type_list()
	{
		$drawing[] = array('id'=>'1','title'=>'Architecture');
		$drawing[] = array('id'=>'2','title'=>'Structural');
		$drawing[] = array('id'=>'3','title'=>'Interior');
		$drawing[] = array('id'=>'4','title'=>'Plumbing');
		$drawing[] = array('id'=>'5','title'=>'Internal Electrical');
		$drawing[] = array('id'=>'6','title'=>'Electronics');
		$drawing[] = array('id'=>'7','title'=>'Road & Other External Infrastructure');
		$drawing[] = array('id'=>'8','title'=>'Horticulture');
		$drawing[] = array('id'=>'9','title'=>'HVAC');
		$drawing[] = array('id'=>'10','title'=>'CCTV');
		$drawing[] = array('id'=>'11','title'=>'Fire Alarm & Detection');
		$drawing[] = array('id'=>'12','title'=>'Fire Fighting');
		$drawing[] = array('id'=>'13','title'=>'External Water Supply & Drainage');
		$drawing[] = array('id'=>'14','title'=>'STP');
		$drawing[] = array('id'=>'15','title'=>'Street Light & External Electrical');
		$drawing[] = array('id'=>'16','title'=>'Lift');
		$drawing[] = array('id'=>'17','title'=>'Electric Sub-Station');
		$drawing[] = array('id'=>'18','title'=>'Others');
		
		return $drawing;
	}
	
	public function get_drawing_type($id)
	{
		$drowing = $this->drawing_type_list();
		foreach($drowing as $key => $value)
		{
			if($id == $value['id'])
			{
				return $value['title'];
			}
		}
	}
	
	function total_sundays($month,$year)
	{
		$sundays=0;
		$total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($i=1;$i<=$total_days;$i++)
		if(date('N',strtotime($year.'-'.$month.'-'.$i))==7)
		$sundays++;
		return $sundays;
	}
	
	function total_day_of_month($month,$year)
	{
		$total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
		return $total_days;
	}
	
	public function get_user_ctc_month($user_id)
	{
		$erp_users = TableRegistry::get('erp_users');
		$data = $erp_users->get($user_id);
		$salary = $data->total_salary;
		return $salary;
	}
	
	public function get_building_reference($id)
	{
		$erp_reference = TableRegistry::get('erp_reference');
		if($id!=''){
			$data = $erp_reference->get($id);
			$title = $data->title;
			return $title;
		}
		else{
			return "-";
		}
	}
	
	public function get_building_reference_by_project($project_id)
	{
		$erp_reference = TableRegistry::get('erp_reference');
		$data = $erp_reference->find()->where(['project_id'=>$project_id])->hydrate(false)->toArray();
		if(!empty($data))
		{
			return $data;
		}
		else{
			return array();
		}
	}
	
	public function get_pay_type($pay_type)
	{
		$data = '';
		if($pay_type === 'employee')
		{
			$data = 'Employee';
		}
		else if($pay_type === 'consultant')
		{
			$data = 'Labour';
		}
		else if($pay_type === 'temporary')
		{
			$data = 'Temporary';
		}
		return $data;
	}
	
	public function get_wo_firststep_approve_value($wo_id)
	{
		$dtl_table = TableRegistry::get('erp_work_order_detail');
		$cnt = $dtl_table->find()->where(['wo_id'=>$wo_id,'first_approved'=>0])->count();
		if($cnt)
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}
	public function get_planningwo_firststep_approve_value($wo_id)
	{
		$dtl_table = TableRegistry::get('erp_planning_work_order_detail');
		$cnt = $dtl_table->find()->where(['wo_id'=>$wo_id,'first_approved'=>0])->count();
		if($cnt)
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}
	public function get_wo_verified_value($wo_id)
	{
		$dtl_table = TableRegistry::get('erp_work_order_detail');
		$cnt = $dtl_table->find()->where(['wo_id'=>$wo_id,'verified'=>0])->count();
		if($cnt)
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}
	public function get_planningwo_verified_value($wo_id)
	{
		$dtl_table = TableRegistry::get('erp_planning_work_order_detail');
		$cnt = $dtl_table->find()->where(['wo_id'=>$wo_id,'verified'=>0])->count();
		if($cnt)
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}
	public function get_vendor_detail($user_id,$field)
	{
		$erp_users = TableRegistry::get('erp_vendor'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id])->select($field)->hydrate(false)->toArray();
		$data = "";
		
		if(!empty($user_data))
		{
			$data = $user_data[0][$field];
		}
		return $data;		
	}
	
	public function get_user_pf_ref_no($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		/* $user_data = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$user_data = $erp_employee->find()->where(['user_id'=>$employee_id,'pf_ref_no !='=>'']);
		$pf_ref_no = "";
		foreach($user_data as $retrive_data)
		{
			$pf_ref_no = $retrive_data['pf_ref_no'];			
		}
		return $pf_ref_no;		
	}
	
	public function get_material_code($material_id)
	{
		$erp_material = TableRegistry::get('erp_material'); 
		$material_data = $erp_material->find()->where(['material_id'=>$material_id]);
		$material_code = 0;
		foreach($material_data as $retrive_data)
		{
			$material_code = $retrive_data['material_code'];
				
		}		
		return $material_code;
	}
	
	public function getmaterialminstocklevel($project_id,$material_id)
	{
		$erp_stock_history = TableRegistry::get("erp_stock_history");
		
		if(is_numeric($material_id))
		{
			$result = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$result = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		if(!empty($result))
		{
			return $result[0]['min_quantity'];;
		}else{
			return "";
		}
		
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
	
	public function get_consume_type($material_id)
	{
		$consume_value = $this->get_items_consumetype($material_id);
		$consume_type = "";
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
		return $consume_type;
	}
	
	public function get_user_material_id($user_id)
	{
		$assign_projects = $this->get_user_assign_projects_id($user_id);
		$assign_projects = array_merge($assign_projects,[0]);
		$material_ids = array();
		if(!empty($assign_projects))
		{
			$erp_material = TableRegistry::get('erp_material');
			$results = $erp_material->find()->where(['project_id IN'=>$assign_projects])->select(['material_id']);
			
			foreach($results as $material)
			{
				$material_ids[] = $material->material_id;
			}
		}
		return json_encode($material_ids);
	}
	
	public function is_asset_accept_remain($asset_id){
		$erp_assets_history = TableRegistry::get('erp_assets_history');
		$count = $erp_assets_history->find()->where(["asset_id"=>$asset_id,"accepted"=>0])->count();
		return $count;
	}
	
	public function get_asset_last_transfer_project($asset_id){
		$erp_assets_history = TableRegistry::get('erp_assets_history');
		$row = $erp_assets_history->find()->where(["asset_id"=>$asset_id,"accepted"=>0])->first();
		if(!empty($row))
		{
			return $row->new_project;
		}else{
			return "";
		}
	}
	
	public function get_asset_release_date($asset_id){
		$erp_assets_history = TableRegistry::get('erp_assets_history');
		$row = $erp_assets_history->find()->where(["asset_id"=>$asset_id])->order(['history_id' => 'desc'])->first();
		if(!empty($row))
		{
			return ($row->release_date != "")?date("d-m-Y",strtotime($row->release_date)):'NA';
		}else{
			return "NA";
		}
	}
	
	public function get_asset_last_issueto($asset_id){
		$erp_asset_issued_history = TableRegistry::get('erp_asset_issued_history');
		$row = $erp_asset_issued_history->find()->where(["asset_id"=>$asset_id])->order(['id' => 'desc'])->first();
		if(!empty($row))
		{
			return $row->issued_to;
		}else{
			return "NA";
		}
	}
	
	public function get_pono_by_grnid($grn_id){
		$erp_manual_po = TableRegistry::get('erp_manual_po');
		$row = $erp_manual_po->find()->where(["related_grn_id"=>$grn_id])->first();
		if(!empty($row))
		{
			return $row->po_no;
		}else{
			return "";
		}
	}
	
	public function get_asset_tentativerelease_date($asset_id){
		$erp_assets_history = TableRegistry::get('erp_assets_history');
		$row = $erp_assets_history->find()->where(["asset_id"=>$asset_id,"release_date IS NOT"=> null,"release_date !="=> "0000-00-00"])->order(['history_id' => 'desc'])->first();
		// debug($row);die;
		if(!empty($row))
		{
			return date("d-m-Y",strtotime($row->release_date));
		}else{
			return "NA";
		}
	}
	public function get_modulewise_tab($module)
	{
		$data=array();
		if($module=="purches"){
			// Material
		
			$data['material']=array(
			 
			 'Material List'=>array('View'=>'viewmaterial',
								'Add/Edit'=>'addmaterial',
								'Join'=>'Joinmaterial'),
			); 
			//Brand
			 
			 $data['brand']=array(
			 
			'Brand List'=>array('View'=>'brandlist',
								'Add/Edit'=>'addbrand',)
			); 
			//finalized-purchase-rate
			 // $data['finalized-purchase-rate']=array(
		
			// 'Finalized Purchase Rate Alert'=>array('View'=>'ratealert',
													// 'Add'=>'addrate',
													// 'Edit'=>'editrate',
													// 'Approve'=>'approverate'),
			// 'Finalized Purchase Rate Record'=>array(
												// 'View'=>'raterecords',
												// 'Delete'=>'deleteraterecords',
												// ),
			// );
			//purchase
			$data['Letter of Intent']=array(
				'LOI Alert'=>array(
								'View'=>'loialert',
								'Add'=>'prepareloi',
								'Edit'=>'editloi',
								'Delete'=>'deleteloi',
								'Verify'=>'verifyloi',
								'Aprrove1'=>'approve1loi',
								'Aprrove2'=>'approve2loi',
								),		  
				'P.O. Records'=>array('View'=>'loirecords',
													'Cancel'=>'cancelloi',
										),
			);
			 $data['PURCHASE-REQUEST/ PURCHASE -ORDER']=array(
				'P.R. Alert'=>array(
					'View'=>'approvedpr',
					// 'Edit'=>'editratepralert',
					'Delete'=>'deletepralert',
					'Approve1'=>'purchaseprfirstapprove',
					'Approve2'=>'approvepralert',
					'Print'=>'printpr',
					'Done'=>'donepralert',
					'Export'=>'exportpralert',
					'Manage'=>'managepralert',
					'Remark'=>'remarkpralert',
				),
				'P.O. Alert'=>array(
					'View'=>'approvepo',
					'Add'=>'manualpreparepo',
					'Edit'=>'editpreparepo',
					'Delete'=>'deletepurchasepoalert',
					'Verify'=>'verifypurchasepoalert',
					'Aprrove1'=>'approve1purchasepoalert',
					'Aprrove2'=>'approve2purchasepoalert',
					'RateHistory' => 'rateHistory',
				),
													
				// 'P.O. Manual '=>array(	 //'Add'=>'manualpreparepo',
				// 						),
				/*  PO  MANUAl Alert
					'Edit'=>'editmanualpreparepo',
					'Delete'=>'deletemanualpoalert',
					'Aprrove1'=>'approve1manualpoalert',
					'Aprrove2'=>'approve2manualpoalert',
				*/
				'Ammended P.O. Records' => array(
					'view' => 'viewammendporecords',
				),
										
				'P.O. Records'=>array(
					'View'=>'viewporecords',
					'Cancel'=>'cancelpo',
					'Ammend'=>'ammendporecord',
					'RateHistory' => 'porateHistory',
					'Delivery Status' => 'deliveryStatus',
				),
			); 
			// PO Status
			$data['Central Purchase Track']=array(
				'P.R. Status'=>array('View'=>'trackpr'),							
				'Purchase Order Status'=>array(
					'View'=>'postatus',
					'Manual Entry'=>'manualentry',
					'Delivery Status'=>'deliverystatus',
					'Delete'=>'removepofromgrn'
				),
				'Purchase Order Delivery Records'=>array(
					'View'=>'podeliveryrecords',
					'Delivery Status'=>'trackpodelivery'
				),
			);
			//Work order
			$data['Work Order']=array(
				'Work Head'=>array(
					'View'=>'workheadlist',
					'Edit'=>'editworkhead',
					'Approve'=>'approveworkhead'
				),
				'W.O. Alert'=>array(
					'View'=>'approvewo',
					'Add'=>'preparewo',
					'Edit'=>'editpreparewo',
					'Delete'=>'deletewo',
					'Verify'=>'verifypurchasewoalert',
					'Aprrove1'=>'approve1woalert',
					'Aprrove2'=>'approve2woalert',
				),
				'W.O. Records'=>array(
					'View'=>'worecords',
					'Cancel'=>'cancelwo',
					),
				);
				
				$data['File Manager']=array(
					'File Manager'=>array('View'=>'purchasefilemanager',
					'Upload'=>'purchasefileadd',
					'Download'=>'purchasefiledownload',
					'Delete'=>'purchasefiledelete'),
				);
			}
		
		if($module=="contractadmin"){
		
			$data['inwardlist']=array(
			 
			 'Inward List'=>array('View'=>'viewinwardlist',
								'Add/Edit'=>'addinward',),
			); 
			 
			 $data['outwardlist']=array(
			 
			'Outward List'=>array('View'=>'viewoutwardlist',
								'Add/Edit'=>'addoutward',)
			); 
			 $data['rabills']=array(
		
			'R.A Bills'=>array('View'=>'viewrabill',
								'Add/Edit'=>'addrabill'),
								
			'Price Variation'=>array('View'=>'viewpricevariation',
										'Add/Edit'=>'addpricevariation',
												),
			);
			 $data['drawingrecords']=array(
		
			'Drawing Records'=>array('View'=>'drawingrecords',
										'Add'=>'adddrawing',
										'Edit'=>'editdrawing',
										'Delete'=>'deletedrawing'),
										
			); 
			 $data['subcontractbillalert']=array(
			 
			'Sub Contract Bill Alert'=>array('View'=>'subcontractbillalert',
													'Add'=>'addsubcontractbill',
													'Edit'=>'editsubcontractbill',
													'Delete'=>'deletesubcontractbill',
													'Approve1'=>'approve1subcontractbill',
													'Approve2'=>'approve2subcontractbill'),
			'Sub Contract Bill Record'=>array('View'=>'subcontractrecords',
												'Delete'=>'reversesubcontract'),
			
			);
			
			$data['File Manager']=array(
				'File Manager'=>array('View'=>'contractfilemanager',
									  'Upload'=>'contractfileadd',
									  'Download'=>'contractfiledownload',
									  'Delete'=>'contractfiledelete'),
			);
		}
		
		if($module=="planning"){
			$data['inwardlist']=array(
			 
			 'Inward List'=>array('View'=>'viewinwardlist',
								'Add/Edit'=>'addinward',),
			); 
			 
			 $data['outwardlist']=array(
			 
			'Outward List'=>array('View'=>'viewoutwardlist',
								'Add/Edit'=>'addoutward',)
			);

			$data['Work Order']=array(
			'Work Description'=>array('View'=>'workdescription',
											'Edit'=>'editworkdescription',
											'Delete'=>'deletedescription'), 
			'Work Head'=>array('View'=>'planningworkheadlist',
													'Edit'=>'editplanningworkhead',
													'Approve'=>'approveworkhead'),
			'W.O. Alert'=>array('View'=>'planningapprovewo',
												'Add'=>'planningpreparewo',
												'Edit'=>'editplanningwo',
												'Delete'=>'deleteplanningwo',
												'Verify'=>'verifyplanningwoalert',
												'Aprrove1'=>'approve1planningwoalert',
												'Aprrove2'=>'approve2planningwoalert',
												),
			'Ammended W.O. Records' => array('view' => 'planningammendapprovewo',),
			'W.O. Records'=>array('View'=>'planningworecords',
												'Cancel'=>'cancelplanningwo',
												'Ammend'=>'ammendworkorder',
								),
			);
		}

		if($module=="billing"){
			$data['drawingrecords']=array(

			'Drawing Records'=>array('View'=>'drawingrecords',
										'Add'=>'adddrawing',
										'Edit'=>'editdrawing',
										'Delete'=>'deletedrawing'),
										
			);
			
			$data['rabills']=array(

			'R.A Bills'=>array('View'=>'viewrabill',
								'Add/Edit'=>'addrabill'),
								
			'Price Variation'=>array('View'=>'viewpricevariation',
										'Add/Edit'=>'addpricevariation',
												),
			);
			
			$data['subcontractbillalert']=array(
			 
			'Sub Contract Bill Alert'=>array('View'=>'subcontractbillalert',
													'Add'=>'addsubcontractbill',
													'Edit'=>'editsubcontractbill',
													'Delete'=>'deletesubcontractbill',
													'Approve1'=>'approve1subcontractbill',
													'Approve2'=>'approve2subcontractbill'),
			'Sub Contract Bill Record'=>array('View'=>'subcontractrecords',
												'Delete'=>'reversesubcontract'),
			
			);
		}

		if($module=="account"){
			// Material
		
			$data['Bills']=array(
			 
			 'Bill List'=>array('View'=>'acceptbills',
								'Add/Edit'=>'addinwardbill',
								'Delete'=>'deleteinwardbill',
								'Checked'=>'checkedinwardbill',
								'Accepted' => 'acceptinwardbill',
								'Approve'=>'approveinwardbill',),
			'Pending Bill'=>array('View'=>'pendingbills',
								'Edit'=>'editpendingbill',
								'Delete'=>'deletependingbill',
								'Accepted'=>'acceptpendingbill',),
			); 
			//Brand
			 
			 $data['Alerts']=array(
			 
			'G.R.N Alert List'=>array('View'=>'grnalert',
								'Aprrove'=>'approvegrnalert',),
			'M.R.N Alert List'=>array('View'=>'mrnalert',
								'Aprrove'=>'approvemrnalert',)
			); 
			//finalized-purchase-rate
			 $data['Records']=array(
		
			'Bill Records'=>array('View'=>'accountlist',
								'Delete'=>'billdisapprove'),
								
			'Payment Notification'=>array('Send Notification'=>'inwardpayment',),
			);
			//purchase
			 $data['Debit Note']=array(
		
			'Debit Note Alert'=>array('View'=>'debitnotealert',
										'Add'=>'adddebitnote',
										'Edit'=>'editdebit',
										'Delete'=>'deletedebit',
										'Approval by C.M.,M.D,P.D'=>'approve_debit_cm_md_pd',
										'Approval by Sr. A/C or A/C Head'=>'approve_debit_sr_ac'),
										
			'View Debit Note'=>array('View'=>'debitnoterecord',
										'Delete'=>'deletedebitnote'),
										
			); 
			//Work order
			//  $data['AGENCY']=array(
			 
			// 'Agency List'=>array('View'=>'agencylist',
			// 						'Add'=>'addagency',
			// 						'Edit'=>'editagency',),
			
			
			// );
			
			 $data['Manage Vendor']=array(
			 
				'Vendor List'=>array(
					'View'=>'viewvendor',
					'Add' => 'addvendor',
					'Edit' => 'addvendor',
					'Join' => 'joinvendor',
					'Print' => 'printVendor',
			),
			
			
			);
			$data['Advance']=array(
			 
			'Advance Alert'=>array('View'=>'viewrequest',
									'Add'=>'advancerequest',
									'Edit'=>'editrequest',
									'Delete'=>'deleterequest',
									'Approval by C.M. or P.D'=>'approve_advance_cm_md_pd',
									'Approval &amp; Export by Sr. A/C or A/C Head'=>'approve_advance_sr_ac',),
			
			'View Advance'=>array('View'=>'viewadvance',
									'Delete'=>'deleteadvance',
									'Export'=>'exportadvance',),
			
			
			);
			
			$data['Manage Site Accounts']=array(
			 
			'View Expense Head'=>array('View'=>'viewexpensehead',
									'Add'=>'expensehead',
									'Edit'=>'editexpensehead',
									'Add Account'=>'createaccount',
									),
			);
			
			$data['Site Transactions']=array(
			 
			'Amount Issued'=>array('Add'=>'amountissued'),
			
			'Expence Alert'=>array('View'=>'expencealert',
									'Add'=>'addexpence',
									'Edit'=>'editexpence',
									'Delete'=>'deleteexpense',
									'Approval by C.M.,M.D,P.D'=>'approve_expense_cm_md_pd',
									'Approval by Sr. A/C or A/C Head'=>'approve_expense_sr_ac',
									),
			'View Site Transactions'=>array('View'=>'sitetransactions',
									'View Income'=>'viewamountissued',
									'Delete Income'=>'incomedelete',
									'View Expense'=>'viewexpence',
									'Delete Expense'=>'expensedelete',
									),
			
			);
			
			$data['File Manager']=array(
				'File Manager'=>array('View'=>'accountfilemanager',
									  'Upload'=>'accountfileadd',
									  'Download'=>'accountfiledownload',
									  'Delete'=>'accountfiledelete'),
			);
		}
		
		if($module=="project"){
			// Material
		
			$data['Project']=array(
			 
			 'Project List'=>array('View'=>'viewprojectlist',
								'Add'=>'add',
								'Edit'=>'edit'),
			); 
			//Brand
			 
			 $data['Tender/Contract Notification']=array(
			 
			'Tender/Contract Notification Records'=>array('View'=>'contractnotificationlist',
															'Add'=>'addcontractnotification',
															'Edit'=>'editcontractnotification',
															'Delete'=>'deletecontractnotification',)
			); 
			//finalized-purchase-rate
			 $data['Personal Notification']=array(
		
			'Personal Notification Records'=>array('View'=>'personalnotificationlist',
													'Add/Edit'=>'addpersonalnotification',
													'Edit'=>'editpersonalnotification',
													'Delete'=>'deletepersonalnotification'),
			
			);
			
			$data['File Manager']=array(
				'File Manager'=>array('View'=>'projectfilemanager',
									  'Upload'=>'projectfileadd',
									  'Download'=>'projectfiledownload',
									  'Delete'=>'projectfiledelete'),
			);
			
		}
		
		if($module=="humanresource"){
			// Material
		

			$data['Candidate Management'] = array(
				
				'Candidate Management'=>array('View'=>'candidatelist',
				'Add/Edit'=>'addcandidate',
				'Delete'=>'deletecandidate',),
			);

			$data['Personnel Management']=array(
			 
				'Personnel Management'=>array('View'=>'emplyeelist',
								'Add/Edit'=>'addemployee',
								'Transfer'=>'transferemployee',
								'Resign'=>'resignemployee',
								'Delete'=>'deleteemployee',
								'Change Pay'=>'paystructure',
								'Change Designation'=>'changedesignation'),
				'Personnel Information'=>array('View'=>'personnel','Edit'=>'personalEmployeeEdit'),
				'Non - Working Employee'=>array('View'=>'notworkingemplyeelist','Delete'=>'deleteemplyeelist','Rejoin'=>'Rejoin',),
			); 
			//Brand
			 
			 $data['Time Logs (Thumb Logs)']=array(
			 
			'Manual Thumb'=>array('Add'=>'attendance'),
			
			'Personnel Time Logs'=>array('View'=>'timelog'),
			); 
			
			$data['Attendance Management']=array(
			 
			 'Attendance Alert'=>array('View'=>'attendancealert',
								'Edit'=>'editattendance',
								'Update Attendance'=>'chage_attendance',
								'Approve'=>'approve_attendace',), 
								
			'Attendance Records'=>array('View'=>'attendancerecord',
								'View Time Log'=>'viewrecord',), 
								
			'Generate Personnel Records'=>array('View'=>'generaterecords',),
			); 
			
			$data['Pay']=array(
			 
			 'Pay Slip'=>array('View'=>'salaryslip',
								'Generate Salary Slip'=>'generatesalaryslip',
								'Unapprove'=>'unapproveattendance',
								), 
								
			'Pay Slip Approval'=>array('View'=>'salarystatement',
								'Edit'=>'editsalaryslip',
								'Delete'=>'deletesalaryslip',
								'Approve'=>'approvesalaryslip',
								'Print'=>'printsalaryslip',
								), 
								
			'Pay Records'=>array('View'=>'salaryrecords',
								'Unapprove'=>'unapprovesalaryslip',
								),
			); 
			
			$data['Loan System']=array(
								
			'View Status'=>array('View'=>'loanlist',
								
								'Add'=>'addloan',
								'Edit'=>'editloan',
								'Delete'=>'deleteloan',
								'History'=>'payloanlisthistory'
								), 
			'View Record'=>array('View'=>'loanpending',
										 'View Data' => 'viewloan',
										 'Loan History'=>'payloanhistory',
										),
					);
			$data['Records']=array(
			'View Status'=>array('View'=>'viewrecords',
								'Personal Details'=>'viewemployee',
								'Transfer History'=>'deploymenthistory',
								'Pay Structure History'=>'paystructurehistory',
								'Pay Records'=>'payrecords',
								'Designation History'=>'designationhistory',
								'Expenditure Claim History'=>'history_clam',
								), 
				); 
			$data['Bonus Management'] = array(
			'Bonus Alert'=>array('view'=>'bonusalert',
								'Generate Bonus' => 'generatebonus',
				),
			'Create Exgracia'=>array('view'=>'createexgracia'),
			'View Bonus Record'=>array('view'=>'viewbonusrecord',
										'View Bonus'=>'viewbonus',
										'View Exgracia'=> 'viewexgraciarecord',
									  )
			);
			$data['Expenditure Clam'] = array(
				'Add Expenditure'=> array('Add Expenditure Clam '=>'expenditure',
										 'History'=>'historyexpenditure',
									),
				'View Expenditure' => array('view'=>'viewexpenditure',
											'Delete'=>'deleteexpenditure',
											'View Record'=>'expenditurelist',
										)
			);
			
			$data['File Manager']=array(
				'File Manager'=>array('View'=>'hrfilemanager',
									  'Upload'=>'hrfileadd',
									  'Download'=>'hrfiledownload',
									  'Delete'=>'hrfiledelete'),
			);
		}
		
		if($module=="asset"){
			// Material
		
			$data['Assets']=array(
			 
			 'Asset Management'=>array('View'=>'trasnsferaccept',
								'Add/Edit'=>'addasset',
								'Issued To'=>'isseuasset',
								'Booking'=>'bookingassest',
								'Transfer'=>'transferasset',
								'Accept'=>'acceptasset',
								),
			 'Sold/Theft Asset'=>array('View'=>'soldtheft',
								'Sold'=>'soldasset',
								'Theft'=>'theftasset',
								),
			);
			
			$data['Assets Maintenance']=array(
			 
			 'Asset Maintenance Alert'=>array('View'=>'aprovemaintenance',
								'Add/Edit'=>'addmaintenance',
								'Delete'=>'deletemaintenance',
								'Approve'=>'aprroveassetmaintence',
								'View Maintenance'=>'viewaddmaintenance',
								),
			 'Asset Maintenance Records'=>array('View'=>'maintenancerecords',
								'Delete'=>'unapprovemaintenance',
								),
			);
			
			$data['Equipment Log']=array(
			 
			 'Equipment Log - Owned'=>array('View'=>'equipmentlogownrecord',
								'Add'=>'equipmentlogown',
								'Edit'=>'editequipmentlogowned',
								'Delete'=>'deleteequipmentlogown',
								
								),
			 'Equipment Log Records - Rent'=>array('View'=>'equipmentlogrecord',
								'Add'=>'equipmentlog',
								'Edit'=>'editeqrecord',
								),
			);
			
			// $data['R.M.C Issue Slip']=array(
			 
			 // 'Rmc Issue Alert'=>array('View'=>'rmcissuealert',
								// 'Add'=>'rmcissueslip',
								// 'Edit'=>'editrmcrecord',
								// 'Remove'=>'deletermc',
								// 'Approve'=>'aprrovermc',
								// ),
			 // 'Equipment Log Records - Rent'=>array('View'=>'rmcissuerecord',
								// 'Remove'=>'unapproveermc',
								// ),
			// );
			
			$data['P&M Notification']=array(
			 
			 'P&M Notification Records'=>array('View'=>'maintenancenotificationlist',
								'Add'=>'addmaintenancenotification',
								'Edit'=>'editmaintenancenotification',
								'Remove'=>'deletemaintainancenotification',
								),
			 
			);
			
			$data['Records']=array(
			 
			 'Asset Records'=>array('View'=>'assetrecord',
								'ViewPurchaseHistory'=>'viewaddasset',
								'ViewTransferHistory'=>'ViewTransferHistory',
								'ViewIssuedHistory'=>'ViewIssuedHistory',
								'ViewSalesDetails'=>'ViewSalesDetails',
								'ViewTheftDetails'=>'ViewTheftDetails',
								'TotalMaintenanceExpence'=>'TotalMaintenanceExpence',
								'ViewBookingHistory'=>'ViewBookingHistory',
								),
			 'Asset Record'=>array(
								'ViewEfficiencyHistory'=>'ViewEfficiencyHistory',
								),
			 'View Store Issue Records'=>array(
			 		'View'=>'storeissue',
			 ),
			
			'Asset Issued Details'=>array('Edit'=>'edit-issued-history',
								'Delete'=>'delete-issued-history',
								),
			'Asset Booking Details'=>array('Edit'=>'edit-booking-history',
								'Delete'=>'delete-booking-history',
								),
			);
			
			$data['PURCHASE -ORDER']=array(
				'P.O. Alert'=>array(
								'View'=>'assetpoalert',
								'Add'=>'assetpo',
								'Edit'=>'editassetpo',
								'Delete'=>'deleteassetpoalert',
								'Verify'=>'verifyassetpoalert',
								'Aprrove1'=>'approve1assetpoalert',
								'Aprrove2'=>'approve2assetpoalert',
								),		  
				'P.O. Records'=>array('View'=>'viewassetporecords',
													'Cancel'=>'cancelassetpo',
										),
			);
			
			$data['File Manager']=array(
				'File Manager'=>array('View'=>'assetfilemanager',
									  'Upload'=>'assetfileadd',
									  'Download'=>'assetfiledownload',
									  'Delete'=>'assetfiledelete'),
			);
			
		}
			
		
		if($module=="user"){
			// Material
		
			$data['User']=array(
			 
			 'Manage User'=>array('View'=>'userlist',
								'Add/Edit'=>'adduser',
								'Remove'=>'removeuser',
								),
			 'User Records'=>array('View'=>'viewuserlist',),
			'Opening Stock'=>array('View'=>'viewprojectlist_user',
								'Add Opeing Stock'=>'openingstock',
								),
			);
		}
		
		if($module=="inventory"){
			// Material
		
			$data['Purchase Request (P.R.)']=array(
			 
			 'P.R Alert'=>array('View'=>'approvedpr_inve',
								'Add'=>'preparepr',
								'Edit'=>'editpreparepr',
								'Delete'=>'deletepr',
								'Approve'=>'approvepralert_inv',
								'View Button'=>'previewpr',
								),
			'View P.R.'=>array('View'=>'viewpr',
								'Delete'=>'unapprovepr',
								),
			
			);
			$data['Cental Purchase Track']=array(
			 
			'P.O. Records'=>array('View'=>'ponorate',
								),
			'W.O. Records'=>array('View'=>'wonorate',
								),
			'P. R. Status'=>array('View'=>'prstatus',
								),
			'Purchase Order Status'=>array('View'=>'inventorypostatus',
										   'Delivery Status'=>'inventorydeliverystatus'),
										   
			'Purchase Order Delivery Records'=>array('View'=>'inventorypodeliveryrecords',
													'Delivery Status'=>'inventorytrackpodelivery'),
			);
			$data['Goods Receipt Note (G.R.N.)']=array(
			 
			 'G.R.N. Alert'=>array('View'=>'approvegrn',
								'Add'=>'preparegrnwithoutpo',
								'Edit'=>'updategrn',
								'Delete'=>'unapprovegrn',
								'Approve'=>'approvegrnalert_inv',
								),
			'View G.R.N.'=>array('View'=>'viewgrn',
								'Edit'=>'updategrnapproved',
								'Delete'=>'deleteapprovedgrn',
								'View Button'=>'previewapprovedgrn',
								'Changes Status'=>'auditgrnchanges',
								),
			
			'G.R.N. Audit'=>array('View'=>'grnaudit',
								'Edit'=>'updateauditgrn',
								'Done'=>'doneauditgrn',
								'Approve'=>'approveauditgrn',
								),
			
			);
			$data['Issue Slip (I.S.)']=array(
			 
			'View Issue Slip'=>array('View'=>'viewis',
								'Add'=>'prepareis',
								'Edit'=>'updateis',
								'Delete'=>'unapproveis',
								'View Button'=>'previewapprovedis',
								'Changes Status'=>'auditischanges',
								),
			'I.S Audit'=>array('View'=>'isaudit',
								'Edit'=>'updateisaudit',
								'Done'=>'doneisaudit',
								'Approve'=>'approveisaudit',
								),
			
			);
			$data['Debit Note']=array(
			 
			'Debit Note Alert'=>array('View'=>'inventorydebitnotealert',
								'Add'=>'inventorypreparedebit',
								'Edit'=>'editinventorydebit',
								'Delete'=>'deleteinventorydebit',
								// 'Approval by C.M.,M.D,P.D'=>'approve_debitinv_cm_md_pd',
								'Approve'=>'approve_debitinv_sr_ac'),
								
			'Debit Note Records'=>array('View'=>'inventorydebitrecords',
								'View Button'=>'previewdebit',
								'Delete'=>'cancelinvdebit',
								),
			
			);
			$data['Return Back Note (R.B.N.)']=array(
			'View R.B.N'=>array('View'=>'viewrbn',
								'Add'=>'preparerbn',
								'Edit'=>'editrbn',
								'Delete'=>'unapproverbn',
								'View Button'=>'previewapprovedrbn',
								'Changes Status'=>'auditrbnchanges',
								),
								
			'R.B.N Audit'=>array('View'=>'rbnaudit',
								'Edit'=>'updaterbnaudit',
								'Done'=>'donerbnaudit',
								'Approve'=>'approverbnaudit',
								),
			
			);
			$data['R.M.C Management']=array(
			 
			 'Mix Design'=>array('View'=>'mixdesignlisting',
								'Add'=>'mixdesign'
								),
			'R.M.C Issue Alert'=>array('View'=>'inventoryrmcalert',
								'Add'=>'prepareinventoryrmc',
								'Edit'=>'editinventoryrmc',
								'Delete'=>'deleteinventoryrmc',
								'Approve'=>'approveinventoryrmc',
								),
			'R.M.C Issue Records'=>array('View'=>'inventoryrmcrecords',
								'Delete'=>'unapprovedrmc',
								),
			
			);
			$data['Material Return Note (M.R.N.)']=array(
			 
			 'M.R.N Alert'=>array('View'=>'approvemrn',
								'Add'=>'preparemrn',
								'Edit'=>'editmrn',
								'Delete'=>'deletemrn',
								
								),
			'View M.R.N.'=>array('View'=>'viewmrn',
								'Delete'=>'unapprovemrn',
								'Approve'=>'approvemrn_inv',
								'View Button'=>'previewapprovedmrn',
								),
			
			
			);
			$data['Site to Site Transfer (S.S.T.)']=array(
			 
			 'S.S.T. Alert'=>array('View'=>'approvesst',
								'Add'=>'preparesst',
								'Edit'=>'editsst',
								'Delete'=>'deletesst',
								'Approve1'=>'approvesst1',
								'Approve2'=>'approvesst2',
								),
			'S.S.T. List'=>array('View'=>'viewsst',
								'Delete'=>'unapprovesst',
								'View Button'=>'previewapprovedsst',
								),
			
			
			);
			$data['Stock Ledger']=array(
			 'Stock Ledger'=>array('View'=>'stockledger',
								),
			);
			$data['Records']=array(
			 
			 'View Records'=>array('View'=>'viewrecords_inv',
								'Manage Stock'=>'managestock',
								'Max Stock'=>'max_stock',
								'Min Stock'=>'min_estock',
								),
			 'View Records - Urgent Purchase Requirement'=>array('View'=>'urgentstockrequirment',
								'Manage Stock'=>'managestockurgent',
								),
			'View Records - Over Purchased Stock'=>array('View'=>'overpurchasedstock',
								'Manage Stock'=>'managestockover',
								),
			);
			
			$data['File Manager']=array(
				'File Manager'=>array('View'=>'inventoryfilemanager',
									  'Upload'=>'inventoryfileadd',
									  'Download'=>'inventoryfiledownload',
									  'Delete'=>'inventoryfiledelete'),
			);
		}
		
		return $data;
	}
	
	public function get_asset_operational_status($asset_id)
	{
		$erp_equipmentown_log = TableRegistry::get('erp_equipmentown_log'); 
		$record = $erp_equipmentown_log->find()->where(['asset_id'=>$asset_id])->order(["id"=>"desc"])->first();
		if(!empty($record))
		{
			if($record->working_status == "working")
			{
				return "Working";
			}elseif($record->working_status == "breakdown"){
				return "Break Down";
			}elseif($record->working_status == "idle"){
				return "Idle";
			}else{
				return "Working";
			}
		}else{
			return "NA";
		}
	}
	
	public function get_pono_by_pr_material($id){
		$erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail');
		$po_data = $erp_inventory_po_detail->find()->where(["pr_mid"=>$id])->select(["po_id"])->hydrate(false)->toArray();
		
		$po_ids = array_column($po_data, 'po_id');
		
		if(!empty($po_ids))
		{
			$erp_inventory_po = TableRegistry::get('erp_inventory_po');
			$po_no = $erp_inventory_po->find()->where(["po_id IN"=>$po_ids])->select(["po_no"])->hydrate(false)->toArray();
			
			$po_nos = array_column($po_no, 'po_no');
			return implode(",<br>",$po_nos);
		}else{
			return "";
		}
	}
	
	public function get_purchase_remarks_of_pr_material($id){
		$erp_inventory_pr_material = TableRegistry::get('erp_inventory_pr_material');
		$pr_data = $erp_inventory_pr_material->get($id);
		
		if(!empty($pr_data))
		{
			if($pr_data->done_remarks != "")
			{
				return $pr_data->done_remarks."<br>";
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	
	public function get_pr_purchased_quantity_by_po($prm_id)
	{
		$po_tbl = TableRegistry::get('erp_inventory_po_detail');
		$quantity = $po_tbl->find()->where();
		$query = $po_tbl->find(); 
		$result = $query->select(['sum' => $query->func()->sum('quantity')])
			    ->where(['pr_mid' => $prm_id,'approved !='=>0])
			    ->first();
		if(!empty($result))
		{
			return $result->sum;
		}else{
			return "";
		}
	}
	
	public function retrive_accessrights($role,$pagename){
		
		$data=0;		
		$findvalue=array();		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		
		$find=$erp_accessrights_tbl->find()->where(['role'=>$role])->first();
		
		if(!empty($find))
		{
			$findvalue=json_decode($find->accessrights);
		}
		
		$findvalue=(array)$findvalue;	
		foreach($findvalue as $result){
			
			$selected = in_array($pagename,$result);
			if($selected==1){
				$data=1;
				return $data;
				break;
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
	
	public function getstatepanno($state,$date)
	{
		$date = date("Y-m-d",strtotime($date));
		$pan_no = '';
		if($state == 'gujarat')
		{
			if($date >= "2019-03-01")
			{
				$pan_no = 'AABCY0913A';
			}else{
				$pan_no = 'AAAFY3210E';
			}
		}
		else if($state == 'mp')
		{
			if($date >= "2019-03-08")
			{
				$pan_no = 'AABCY0913A';
			}else{
				$pan_no = 'AAAFY3210E';
			}
			
		}
		else if($state == 'maharastra')
		{
			if($date >= "2019-03-01")
			{
				$pan_no = 'AABCY0913A';
			}else{
				$pan_no = 'AAAFY3210E';
			}
		}
		else if($state == 'haryana')
		{
			$pan_no = 'AABCY0913A';
		}
		
		return $pan_no;
	}

	public function getletterheadsign($date,$state)
	{
		$date = date("Y-m-d",strtotime($date));
		if($state != "mp")
		{
			if($date >= "2019-03-01")
			{
				$sign = 'For, YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD.';
			}else{
				$sign = 'For, YashNand Engineers & Contractors';
			}
		}else{
			if($date >= "2019-03-08")
			{
				$sign = 'For, YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD.';
			}else{
				$sign = 'For, YashNand Engineers & Contractors';
			}
		}
		return $sign;
	}
	
	public function getmpbilladdress($date)
	{
		$date = date("Y-m-d",strtotime($date));
		if($date >= "2019-03-08")
		{
			$address = 'House No - MF 04/72 MIG,Shivaji Parisar,Nehrunagar, Bhopal,Madhya Pradesh - 462016.';
		}else{
			$address = 'A-312, The Bellaire Campus, Abbas Nagar Road, Near Asharam Square, Gandhinagar, Bhopal,M.P. - 462036.';
		}
		return $address;
	}
	
	public function getconditionofpowo($date,$state)
	{
		$date = date("Y-m-d",strtotime($date));
		if($state != "mp")
		{
			if($date >= "2019-03-01")
			{
				$sign = 'YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD. has right to cancel order any time without any prior notice.';
			}else{
				$sign = 'YashNand Engineers & Contractors has right to cancel order any time without any prior notice.';
			}
		}else{
			if($date >= "2019-03-08")
			{
				$sign = 'YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD. has right to cancel order any time without any prior notice.';
			}else{
				$sign = 'YashNand Engineers & Contractors has right to cancel order any time without any prior notice.';
			}
		}
		return $sign;
	}
	
	public function get_is_audit_details($is_details,$audit_is_id,$agency_id,$i)
	{
		// $erp_audit_is_detail = TableRegistry::get("erp_audit_is_detail");
		// $is_details = $erp_audit_is_detail->find()->where(["is_audit_id"=>$audit_is_id])->hydrate(false)->toArray();
		$rows = "<table class='table-bordered'>";
		
		if(!empty($is_details))
		{			
			foreach($is_details as $is_data)
			{
								
				$agency_name = "";
				$is_asset = explode("_",$agency_id);
				if(isset($is_asset[1]))
				{
					$agency_name = $this->get_asset_name($is_asset[1]);
				}else{
					$agency_name = $this->get_vendor_name($agency_id);
				} 
										
				$rows .= "<tr>
									
				<td>{$agency_name}</td>
				<td>{$this->get_material_title($is_data['material_id'])}</td>
				<td style='width: 80px;'>{$is_data['quantity']}</td>
				<td>{$this->get_items_units($is_data["material_id"])}</td>
				<td style='width: 80px;'>{$is_data['name_of_foreman']}</td>			
				<td>";
				$user_id = $this->request->session()->read('user_id');
				$role = $this->get_user_role($user_id);
				if($this->retrive_accessrights($role,'updateisaudit')==1)
				{
					$rows .= "<a class='btn btn-success' target='_blank' href='updateisaudit/".$is_data['is_audit_id']."'>Edit</a>";
				}
				 $rows .= "<a class='btn btn-primary' target='_blank' href='previewauditis/".$is_data['is_audit_id']."'>View</a>";
				
				$rows .= "</td>";
								
				$rows .= "</tr>";				
			}
			$rows .= "</table>";			
		}
		else{
			$rows = "No Records Found.";
			$rows .= "<script>var size = $('#dd_'+".$i.").remove();</script>";
			
		}
		return $rows;
	}
	
	public function get_rbn_audit_details($rbn_details,$audit_rbn_id,$agency_id,$i)
	{
		// $erp_audit_rbn_detail = TableRegistry::get("erp_audit_rbn_detail");
		// $rbn_details = $erp_audit_rbn_detail->find()->where(["audit_id"=>$audit_rbn_id])->hydrate(false)->toArray();
		$rows = "<table class='table-bordered'>";
		
		if(!empty($rbn_details))
		{			
			foreach($rbn_details as $rbn_data)
			{
								
				$agency_name = "";
				$rbn_asset = explode("_",$agency_id);
				if(isset($rbn_asset[1]))
				{
					$agency_name = $this->get_asset_name($rbn_asset[1]);
				}else{
					$agency_name = $this->get_vendor_name($agency_id);
				} 
										
				$rows .= "<tr>
									
				<td>{$agency_name}</td>
				<td>{$this->get_material_title($rbn_data['material_id'])}</td>
				<td>{$this->get_brandname($rbn_data['brand_id'])}</td>
				<td style='width: 80px;'>{$rbn_data['quantity_reurn']}</td>
				<td>{$this->get_items_units($rbn_data["material_id"])}</td>
				<td style='width: 80px;'>{$rbn_data['name_of_foreman']}</td>			
				<td>";
				$user_id = $this->request->session()->read('user_id');
				$role = $this->get_user_role($user_id);
				if($this->retrive_accessrights($role,'updaterbnaudit')==1)
				{
					$rows .= "<a class='btn btn-success' target='_blank' href='updaterbnaudit/".$rbn_data['audit_id']."'>Edit</a>";
				}
				$rows .= "<a class='btn btn-success' target='_blank' href='previewauditrbn/".$rbn_data['audit_id']."'>View</a>";
				
				$rows .= "</td>";
								
				$rows .= "</tr>";				
			}
			$rows .= "</table>";			
		}
		else{
			$rows = "No Records Found.";
			$rows .= "<script>var size = $('#dd_'+".$i.").remove();</script>";
			
		}
		return $rows;
	}
	
	public function check_inventory_debit_approve($debit_id)
	{
		$detail_tbl = TableRegistry::get('erp_inventory_debit_note_detail');
		$data = $detail_tbl->find()->where(["debit_id"=>$debit_id,'second_approved'=>0]);
		$cnt = $data->count();
		if($cnt == 0)
		{
			$is_approve = 1;
		}else {
			$is_approve = 0;
		}
		
		return $is_approve;
	}
	
	public function get_total_debit_inventory($debit_id)
	{
		$detail_tbl = TableRegistry::get('erp_inventory_debit_note_detail');
		$data = $detail_tbl->find()->where(["debit_id"=>$debit_id])->select(["total_amount"])->first();
		if(!empty($data))
		{
			return $data->total_amount;
		}
		else{
			return "";
		}
	}
	
	public function get_material_subgroup($group_id)
	{
		$erp_material_sub_group = TableRegistry::get('erp_material_sub_group');
		$subgroups = $erp_material_sub_group->find()->where(["material_group_id"=>$group_id])->hydrate(false)->toArray();
		return $subgroups;
	}
	
	public function get_material_subgroup_title($sub_group_id)
	{
		$erp_material_sub_group = TableRegistry::get('erp_material_sub_group');
		$title = "";
		if($sub_group_id)
		{
			$row = $erp_material_sub_group->get($sub_group_id);
			$title = $row->sub_group_title;
		}
		return $title;
	}
	
	public function get_material_stilldate_stock($project_id,$material_id,$date,$excluding_record,$type=null,$record_id=null)
	{
		$date = date("Y-m-d",strtotime($date));
		
		$history_tbl = TableRegistry::get("erp_stock_history");
		
		/* Excluding record means does not include that record in stock count */
		if($excluding_record == "yes")
		{			
			$opening_stock = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os","date <="=>$date])->hydrate(false)->toArray();
		
			$data = $history_tbl->find("all")->where(["AND"=>["project_id"=>$project_id,"material_id"=>$material_id,"type !="=>"os","date <="=>$date],["OR"=>['type !='=>$type,'type_id !='=>$record_id]]])->hydrate(false)->toArray();
		}else{
			$opening_stock = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os","date <="=>$date])->hydrate(false)->toArray();
		
			$data = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type !="=>"os","date <="=>$date])->hydrate(false)->toArray();
		}
		
		
		
		
		/* $opening_stock = 0;  MOVED TO ELSE*/
		if(!empty($opening_stock))
		{
			$opening_stock = $opening_stock[0]["quantity"];
		}else{
			
			$opening_stock = 0;
		}
		
		if(!empty($data))
		{
			foreach($data as $retrive_data)
			{
				$opening_stock = $this->get_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
	}
	
	public function get_material_stock_previous_date($project_id,$material_id,$date)
	{
		$date = date("Y-m-d",strtotime($date));
		
		$history_tbl = TableRegistry::get("erp_stock_history");
		
		$opening_stock = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os","date <"=>$date])->hydrate(false)->toArray();
		
		$data = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type NOT IN"=>array("os","sst_to"),"date <"=>$date])->hydrate(false)->toArray();
		
		/* $opening_stock = 0;  MOVED TO ELSE*/
		if(!empty($opening_stock))
		{
			$opening_stock = $opening_stock[0]["quantity"];
		}else{
			
			$opening_stock = 0;
		}
		
		if(!empty($data))
		{
			foreach($data as $retrive_data)
			{
				$opening_stock = $this->get_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
	}
	
	public function check_inventory_debit_approve_first_step($debit_id)
	{
		$detail_tbl = TableRegistry::get('erp_inventory_debit_note_detail');
		$data = $detail_tbl->find()->where(["debit_id"=>$debit_id,'first_approved'=>1]);
		$cnt = $data->count();
		if($cnt == 0)
		{
			$is_approve = 0;
		}else {
			$is_approve = 1;
		}
		
		return $is_approve;
	}
	
	public function get_leave_balance($user_id,$month,$year)
	{
		$tbl_user = TableRegistry::get("erp_users");
		$tbl_att = TableRegistry::get("erp_attendance_detail");
		
		$leave_balance = "";
		if($month == 1)
		{
			$month = 12;
			$year = $year - 1;
		}else{
			$month = $month - 1;
		}
		
		$prv_data = $tbl_att->find()->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year]);
		$cnt = $prv_data->count();
		if($cnt == 1)
		{
			$leave_data = $prv_data->hydrate(false)->toArray();
			if($leave_data[0]["remaining_pl"] == "")
			{
				$leave_balance  = 0;
			}else{
				$leave_balance = $leave_data[0]["remaining_pl"];
			}
		}
		else
		{
			$bal = $tbl_user->find()->where(["user_id"=>$user_id])->select("leave_balance")->hydrate(false)->toArray();
			if(!empty($bal))
			{
				$leave_balance = $bal[0]["leave_balance"];
			}
		}
		// debug($leave_balance);die;
		return $leave_balance;
	}
	
	function total_sundays_category_wise($category,$month,$year)
	{
		if($category == 'a')
		{
			$sundays=0;
			$total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
			for($i=1;$i<=$total_days;$i++)
			if(date('N',strtotime($year.'-'.$month.'-'.$i))==7)
			$sundays++;
			return $sundays;
		}else{
			return 0;
		}
	}
	
	public function get_holiday_of_month($month,$year)
	{
		$month_holiday = TableRegistry::get('month_holiday');
		$data = $month_holiday->find()->where(["month"=>$month,'year'=>$year])->first();
		if(!empty($data))
		{
			return $data->holiday;
		}else {
			return 0;
		}
	}
	
	public function get_user_employee_at($uid)
	{
		$tbl = TableRegistry::get("erp_users");
		$cat_id = $tbl->find()->where(["user_id"=>$uid])->select(["employee_at"])->hydrate(false)->toArray();
		$project_id = $cat_id[0]["employee_at"];
		$employee_at = $this->get_projectname($project_id);
		return $employee_at;
	}
	
	public function get_user_category($uid)
	{
		$tbl = TableRegistry::get("erp_users");
		$cat_id = $tbl->find()->where(["user_id"=>$uid])->select(["category"])->hydrate(false)->toArray();
		$user_category = $cat_id[0]["category"];
		return $user_category;
	}
	
	public function get_user_ctc_year($user_id)
	{
		$erp_users = TableRegistry::get('erp_users');
		$data = $erp_users->get($user_id);
		$salary = $data->ctc;
		return $salary;
	}
	
	public function get_user_bank_name($user_id)
	{
		$erp_users = TableRegistry::get('erp_users');
		$data = $erp_users->get($user_id);
		$salary = $data->cheque_name;
		return $salary;
	}
	
	public function get_user_pan_card($user_id)
	{
		$erp_users = TableRegistry::get('erp_users');
		$data = $erp_users->get($user_id);
		$pan_card_no = $data->pan_card_no;
		return $pan_card_no;
	}
	
	public function get_user_contact_no($user_id)
	{
		$erp_users = TableRegistry::get('erp_users');
		$data = $erp_users->get($user_id);
		$contactno1 = $data->contactno1;
		return $contactno1;
	}
	
	public function check_grn_material_approved($grn_detial_id)
	{
		$erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail');
		$data = $erp_inventory_grn_detail->get($grn_detial_id);
		return $data->approved;
	}
	
	public function get_pr_track_materials($data,$pid,$i=null,$project_id=null)
	{
		if(!empty($data))
		{
			$table = "<table class='table-bordered' style='width:100%;border:0;'>";
			$user_id = $this->request->session()->read('user_id');
			$role = $this->get_user_role($user_id);
			foreach($data as $row)
			{ 
				if(is_numeric($row['material_id']) && $row['material_id'] != 0)
				{
					$mcode = $this->get_material_item_code_bymaterialid($row['material_id']);
					$mt = $this->get_material_title($row['material_id']);
					$brnd = $this->get_brandname($row['brand_id']);
					$unit = $this->get_items_units($row['material_id']);
				}
				else
				{
					$mcode = $row['m_code'];
					$mt = $row['material_name'];
					$brnd = $row['brand_name'];
					$unit = $row['static_unit'];
				}
				
				$table .= "<tr>
				<td>{$mcode}</td>
				<td>{$mt}</td>
				<td>{$brnd}</td>
				<td>{$row['quantity']}</td>
				<td>{$unit}</td>
				<td>".date("d-m-Y",strtotime($row['delivery_date']))."</td>
				<td style='width:10px;'>";
				$table .="<a href='{$this->request->base}/Inventory/previewpr/{$pid}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
				$table .= "</td>";
				if(!$row['material_id'])
				{
					//$table .= "<div style='color:red'><b>Pending</b></div>";
				}
				else
				{
					
					$material_code = $this->get_material_code($row['material_id']);
					if($material_code != 17){
						if($row['po_completed'] != 3){
								if($this->project_alloted($role)==1){ 
									//$table .= "<div style='color:red'><b>Pending</b></div>";
								}
							
						}else{
							//$table .= "<div style='color:blue'><b>Pending PO Approve<b></div>";
						}
					}
					else{
						//$table .= "<div style='color:red'><b>Pending</b></div>";
					}
					
				}
				$table .= "<td>{$row['purchase_remarks']}</td>";
				
				$table .= "
				
				<script>
					var i = {$i};
					var approved_date = '".((strtotime($row['approved_date']) != '') ? date('d-m-Y',strtotime($row['approved_date'])) :'NA' )."';
					var approved_time = '".((strtotime($row['approved_date']) != '') ? date('H:i',strtotime($row['approved_date'])) :'NA' )."';
					$('#app_date_'+i).html(approved_date);
					$('#app_time_'+i).html(approved_time);
				</script>
				
				</tr>";
			}
			$table .= "</table>";
		}else{
			return "None";
		}		
		return $table;			
	}
	
	public function get_user_monthly_pay($user_id)
	{
		$erp_users = TableRegistry::get('erp_users');
		$data = $erp_users->get($user_id);
		$monthly_pay = $data->monthly_pay;
		return $monthly_pay;
	}
	
	public function get_user_identy_number($user_id)
	{
		$erp_users = TableRegistry::get('erp_users');
		$data = $erp_users->get($user_id);
		$user_identy_number = $data->user_identy_number;
		return $user_identy_number;
	}
	
	public function get_concrete_grade_name($id)
	{
		$erp_inventory_mix_design = TableRegistry::get('erp_inventory_mix_design');
		$data = $erp_inventory_mix_design->get($id);
		$concrete_grade = $data->concrete_grade;
		return $concrete_grade;
	}
	
	public function get_total_stockin($project_id,$material_id)
	{		
		$history_tbl = TableRegistry::get("erp_stock_history");
		$opening_stock = 0;
		if(is_numeric($material_id))
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		
		if(!empty($opening_stock_data))
		{
			$opening_stock = $opening_stock_data[0]["quantity"];			
		}
		
		if(is_numeric($material_id))
		{
		$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type IN"=>array("grn","mrn","sst_from")])->hydrate(false)->toArray();
		}
		else
		{
			$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id,"type IN"=>array("grn","mrn","sst_from")])->hydrate(false)->toArray();
		}
		// debug($stockledger);die;
		if(isset($stockledger))
		{
			foreach($stockledger as $retrive_data)
			{
				$opening_stock = $this->get_stockin_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
	}
	
	public function get_stockin_balance($type,$old_stock,$new_stock)
	{		
		switch($type)
		{
			// CASE "is":
				// return $old_stock - $new_stock;
			// break;
			
			CASE "mrn":
				return $old_stock + ( - $new_stock);
			break;
			
			// CASE "rbn":
				// return $old_stock + $new_stock;
			// break;
			
			CASE "grn":
				return $old_stock + $new_stock;
			break;
			
			CASE "sst_from":
				return $old_stock + ( - $new_stock);
			break;
			// CASE "sst_to":
				// return $old_stock + $new_stock;
			// break;
			
			default :
				return $old_stock + $new_stock;
		}
	}
	
	public function get_assetpo_alerts($project_id)
	{		
		$po_tbl = TableRegistry::get("erp_asset_po");
		$pod_tbl = TableRegistry::get("erp_asset_po_detail");
		
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		
		#################### New Query #########################################
		$or = array();
		
		$or["erp_asset_po.project_id"] = (!empty($project_id) && $project_id != "All")?$project_id:NULL;
		if($role =='deputymanagerelectric')
		{
			$material_ids = $this->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$or["erp_asset_po_detail.material_id IN"] = $material_ids;
		}
		if($or["erp_asset_po.project_id"] == NULL)
		{
			if($this->project_alloted($role)==1)
			{
				$or["erp_asset_po.project_id IN"] = $projects_ids;
			}
		}
		$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
		$or["erp_asset_po_detail.approved ="] = 0;
		// debug($or);die;
		$result = $po_tbl->find()->select($po_tbl);
		$result = $result->innerjoin(
			["erp_asset_po_detail"=>"erp_asset_po_detail"],
			["erp_asset_po.po_id = erp_asset_po_detail.po_id"])
			->where($or)->select($pod_tbl)->hydrate(false)->toArray();
		// debug($result);die;
		
		$new_array = array();
		foreach($result as $retrive)
		{
			if(isset($new_array[$retrive['po_no']]))
			{
				$new_array[$retrive['po_no']]['erp_asset_po_detail'][] = $retrive['erp_asset_po_detail'];
			}else{
				$a = $retrive["erp_asset_po_detail"];
				unset($retrive["erp_asset_po_detail"]);
				$new_array[$retrive["po_no"]] = $retrive;
				$new_array[$retrive["po_no"]]['erp_asset_po_detail'][] = $a;
			}
			
		}
		$data = $new_array;
		#################### New Query #########################################		
		// if($role == "deputymanagerelectric")
		// {
			// $materials_ids = $this->get_deputymanagerelectric_material();
			// $materials_ids = json_decode($materials_ids);
			// $po_ids = $pod_tbl->find()->where(["material_id IN"=>$materials_ids])->select('po_id')->hydrate(false)->toArray();
			// $po_ids_array = array();
			// foreach($po_ids as $po_id)
			// {
				// $po_ids_array[] = $po_id['po_id'];
			// }
			// if($project_id != "")
			// { 		
				// /* $data = $po_tbl->find()->where(["project_id"=>$project_id])->group(['project_id','po_no'])->hydrate(false)->toArray(); */
				// $data = $po_tbl->find()->where(["project_id"=>$project_id,'po_id IN'=>$po_ids_array])->group(['project_id','po_no'])->order(['po_date' => 'DESC'])->hydrate(false)->toArray();
			// }else{
				// /* $data = $po_tbl->find()->group(['project_id','po_no'])->hydrate(false)->toArray(); */
				// $data = $po_tbl->find()->where(["approved_status"=>0,'po_id IN'=>$po_ids_array])->group(['project_id','po_no'])->hydrate(false)->toArray();
			// }
		// }else{
			// if($project_id != "")
			// { 		
				// /* $data = $po_tbl->find()->where(["project_id"=>$project_id])->group(['project_id','po_no'])->hydrate(false)->toArray(); */
				// $data = $po_tbl->find()->where(["project_id"=>$project_id])->group(['project_id','po_no'])->order(['po_date' => 'DESC'])->hydrate(false)->toArray();
			// }else{
				// if($this->project_alloted($role)==1)
				// {
					// $data = $po_tbl->find()->where(["approved_status"=>0,"project_id IN"=>$projects_ids])->group(['project_id','po_no'])->hydrate(false)->toArray();
				// }else{
					// $data = $po_tbl->find()->where(["approved_status"=>0])->group(['project_id','po_no'])->hydrate(false)->toArray();
				// }
				// /* $data = $po_tbl->find()->group(['project_id','po_no'])->hydrate(false)->toArray(); */
				
			// }
		// }
		
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		############################## Accessright ############################################
		$editpreparepo = $this->retrive_accessrights($role,'editassetpo');
		$approvepo = $this->retrive_accessrights($role,'assetpoalert');
		$deleteassetpoalert = $this->retrive_accessrights($role,'deleteassetpoalert');
		$verifyassetpoalert = $this->retrive_accessrights($role,'verifyassetpoalert');
		$approve1assetpoalert = $this->retrive_accessrights($role,'approve1assetpoalert');
		$approve2assetpoalert = $this->retrive_accessrights($role,'approve2assetpoalert');
		############################## Accessright ############################################
		if(!empty($data))
		{
			$table ="";	$x = 1;
			foreach($data as $row)
			{ 
				// if($po_type != '')
				// {
					// $po_materials = $pod_tbl->find()->where(["po_id"=>$row["po_id"],"approved"=>0,"po_type"=>$po_type])->hydrate(false)->toArray();
				// }else{
					// $po_materials = $pod_tbl->find()->where(["po_id"=>$row["po_id"],"approved"=>0])->hydrate(false)->toArray();
				// }
				
				/*<td>{$this->get_projectcode($row['project_id'])}</td>
				<td>{$this->get_prno_by_prid($row['pr_id'])}</td> */
				$table .= "<tr class='data_row' id='dd_{$x}'>				
				<td>{$this->get_projectname($row['project_id'])}</td>
				<td>{$row['po_no']}</td>
				<td>{$this->get_date($row['po_date'])}</td>
				<td>{$row['po_time']}</td>
			    <td> ";	/*  <form action='setpoapprove' method='post'> preparegrn		 */
				$first_disabled = '';
				$first_checked = '';
				$verify_disabled = '';
				$verify_checked = '';
				$second_disabled = '';
				if(!empty($row['erp_asset_po_detail']))
				{
					$table .="<table class='table-bordered' style='width:100%'>";
					foreach($row['erp_asset_po_detail'] as $materials)
					{
					$first_checked = ($materials['first_approved']==1) ? 'checked' : '';
					$first_disabled = ($materials['verified']==0 || $materials['first_approved']==1) ? 'DISABLED' : '';
					
					$verify_checked = ($materials['verified']==1) ? 'checked' : '';
					$verify_disabled = ($materials['verified']==1) ? 'DISABLED' : '';
					
					$second_disabled = ($materials['first_approved']==1) ? '' : 'DISABLED';
						
					$mt = is_numeric($materials['material_id'])?$this->get_material_title($materials['material_id']):$materials['material_id'];
					$brnd = is_numeric($materials['brand_id'])?$this->get_brandname($materials['brand_id']):$materials['brand_id'];
					$unit_name = is_numeric($materials['material_id'])?
					$this->get_items_units($materials['material_id']):$materials['static_unit'];
					
						$table .= "<tr>							
													
							<td>{$this->get_vendor_name($row['vendor_userid'])}</td>
							
							<td>{$mt}</td>
							<td>{$brnd}</td>
							<td>{$materials['quantity']}</td>
							<td>{$unit_name}</td>
							<td>{$materials['single_amount']}</td>
							<td>{$materials['amount']}</td>";
							// <td>Central<br>Purchase</td>
						$table .= "<td>";
						if($editpreparepo==1)
						{
							$table .= "<a href='{$this->request->base}/assets/editassetpo/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-success'>Edit</a>&nbsp";
						}
						if($approvepo==1)
						{
							$table .= "<a href='{$this->request->base}/assets/previewassetpo/{$row["po_id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
							
						}		
						if($deleteassetpoalert==1)
						{
							$table .= "<a href='{$this->request->base}/assets/deleteassetpoalert/{$materials["id"]}' class='btn btn-sm btn-danger'>Delete</a></td>";
						}
						// if($this->retrive_accessrights($role,'approve1purchasepoalert')==1)
						// {
							// $table .= "<td> 
								// <div class='checkbox'>
									// <label><input type='checkbox' value='{$materials['id']}' {$first_disabled} {$first_checked} name='approved_list1[]'/> </label>
									// <input type='hidden' name='first_selected_po_id_{$materials['id']}' value='{$materials['po_id']}'>
									// <input type='hidden' name='po_id1' value='{$materials['po_id']}'>
									// <input type='hidden' name='first_project_id_{$materials['id']}' value='{$row['project_id']}'>
								// </div>
							// </td>";
						// }
						// if($this->retrive_accessrights($role,'verifypurchasepoalert')==1)
						// {
						// $table .= "<td> 
								// <div class='checkbox'>
									// <label><input type='checkbox' value='{$materials['id']}' {$verify_disabled} {$verify_checked} name='verify_list[]'/> </label>
									// <input type='hidden' name='verify_po_id_{$materials['id']}' value='{$materials['po_id']}'>
									// <input type='hidden' name='verify_id1' value='{$materials['po_id']}'>
									// <input type='hidden' name='verify_project_id_{$materials['id']}' value='{$row['project_id']}'>
								// </div>
							// </td>";
						// }
						// if($this->retrive_accessrights($role,'approve2purchasepoalert')==1)
						// {
							// $table .= "<td> 
								// <div class='checkbox'>
									// <label><input type='checkbox' {$second_disabled} value='{$materials['id']}' name='approved_list[]'/> </label>
									// <input type='hidden' name='selected_po_id_{$materials['id']}' value='{$materials['po_id']}'>
									// <input type='hidden' name='po_id' value='{$materials['po_id']}'>
									// <input type='hidden' name='project_id_{$materials['id']}' value='{$row['project_id']}'>
								// </div>
							// </td>";
						// }
						$table .= "</tr>";
					}
					$table .= "</table>";
				}
				else{
					$table .= "None Record Found.
					<script>var size = $('#dd_'+".$x.").remove();</script>
					";
				}
				if($verifyassetpoalert==1)
				{
				$table .= "<td>
					<div class='checkbox'>
						<label><input type='checkbox' value='{$row["po_id"]}' {$verify_disabled} {$verify_checked} name='verify_list[]'/> </label>
					</div>
				</td>";
				}
				if($approve1assetpoalert==1)
				{
					$table .= "<td>
					<div class='checkbox'>
						<label><input type='checkbox' value='{$row["po_id"]}' {$first_disabled} {$first_checked} name='approved_list1[]'/> </label>
					</div>
					</td>";
				}
				
				if($approve2assetpoalert==1)
				{
				$table .= "<td>
					<div class='checkbox'>
						<label><input type='checkbox' {$second_disabled} value='{$row["po_id"]}' name='approved_list[]'/> </label>
					</div>
				</td>";
				}
				$table .= "<td> <input type='button' name='approve_po' po_no='{$row['po_no']}' value='Go' class='btn btn-success go_btn'> 	</td></td>
				</tr>";
				$x++;
			}		
			return $table;
		}
	}
	
	public function get_loi_alerts($project_id)
	{		
		$erp_letter_content = TableRegistry::get("erp_letter_content");
		$erp_letter_content_detail = TableRegistry::get("erp_letter_content_detail");
		
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		$projects_ids = $this->users_project($user_id);
		
		#################### New Query #########################################
		$or = array();
		
		$or["erp_letter_content.project_id"] = (!empty($project_id) && $project_id != "All")?$project_id:NULL;
		if($role =='deputymanagerelectric')
		{
			$material_ids = $this->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$or["erp_letter_content_detail.material_id IN"] = $material_ids;
		}
		if($or["erp_letter_content.project_id"] == NULL)
		{
			if($this->project_alloted($role)==1)
			{
				$or["erp_letter_content.project_id IN"] = $projects_ids;
			}
		}
		$keys = array_keys($or,"");				
				foreach ($keys as $k)
				{unset($or[$k]);}
		$or["erp_letter_content_detail.approved ="] = 0;
		// debug($or);die;
		$result = $erp_letter_content->find()->select($erp_letter_content);
		$result = $result->innerjoin(
			["erp_letter_content_detail"=>"erp_letter_content_detail"],
			["erp_letter_content.id = erp_letter_content_detail.loi_id"])
			->where($or)->select($erp_letter_content_detail)->hydrate(false)->toArray();
		// debug($result);die;
		
		$new_array = array();
		foreach($result as $retrive)
		{
			if(isset($new_array[$retrive['loi_no']]))
			{
				$new_array[$retrive['loi_no']]['erp_letter_content_detail'][] = $retrive['erp_letter_content_detail'];
			}else{
				$a = $retrive["erp_letter_content_detail"];
				unset($retrive["erp_letter_content_detail"]);
				$new_array[$retrive["loi_no"]] = $retrive;
				$new_array[$retrive["loi_no"]]['erp_letter_content_detail'][] = $a;
			}
			
		}
		$data = $new_array;
				
		$user_id = $this->request->session()->read('user_id');
		$role = $this->get_user_role($user_id);
		############################## Accessright ############################################
		$editloi = $this->retrive_accessrights($role,'editloi');
		$loialert = $this->retrive_accessrights($role,'loialert');
		$deleteloi = $this->retrive_accessrights($role,'deleteloi');
		$verifyloi = $this->retrive_accessrights($role,'verifyloi');
		$approve1loi = $this->retrive_accessrights($role,'approve1loi');
		$approve2loi = $this->retrive_accessrights($role,'approve2loi');
		############################## Accessright ############################################
		if(!empty($data))
		{
			$table ="";	$x = 1;
			foreach($data as $row)
			{
				$table .= "<tr class='data_row' id='dd_{$x}'>				
				<td>{$this->get_projectname($row['project_id'])}</td>
				<td>{$row['loi_no']}</td>
				<td>{$this->get_date($row['loi_date'])}</td>
				<td>{$row['loi_time']}</td>
			    <td> ";	/*  <form action='setpoapprove' method='post'> preparegrn		 */
				$first_disabled = '';
				$first_checked = '';
				$verify_disabled = '';
				$verify_checked = '';
				$second_disabled = '';
				if(!empty($row['erp_letter_content_detail']))
				{
					$table .="<table class='table-bordered' style='width:100%'>";
					foreach($row['erp_letter_content_detail'] as $materials)
					{
					$first_checked = ($materials['first_approved']==1) ? 'checked' : '';
					$first_disabled = ($materials['verified']==0 || $materials['first_approved']==1) ? 'DISABLED' : '';
					
					$verify_checked = ($materials['verified']==1) ? 'checked' : '';
					$verify_disabled = ($materials['verified']==1) ? 'DISABLED' : '';
					
					$second_disabled = ($materials['first_approved']==1) ? '' : 'DISABLED';
						
					$mt = is_numeric($materials['material_id'])?$this->get_material_title($materials['material_id']):$materials['material_id'];
					$brnd = is_numeric($materials['brand_id'])?$this->get_brandname($materials['brand_id']):$materials['brand_id'];
					$unit_name = is_numeric($materials['material_id'])?
					$this->get_items_units($materials['material_id']):$materials['static_unit'];
					
						$table .= "<tr>							
													
							<td>{$this->get_vendor_name($row['vendor_userid'])}</td>
							<td>{$mt}</td>
							<td>{$brnd}</td>
							<td>{$materials['quantity']}</td>
							<td>{$unit_name}</td>
							<td>{$materials['single_amount']}</td>
							<td>{$materials['amount']}</td>";
							// <td>Central<br>Purchase</td>
						$table .= "<td>";
						if($editloi==1)
						{
							$table .= "<a href='{$this->request->base}/purchase/editloi/{$row["id"]}' target='_blank' class='btn btn-sm btn-success'>Edit</a>&nbsp";
						}
						if($loialert==1)
						{
							$table .= "<a href='{$this->request->base}/purchase/previewloi/{$row["id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
							
						}		
						if($deleteloi==1)
						{
							$table .= "<a href='{$this->request->base}/purchase/deleteloi/{$materials["id"]}' class='btn btn-sm btn-danger'>Delete</a></td>";
						}
						$table .= "</tr>";
					}
					$table .= "</table>";
				}
				else{
					$table .= "None Record Found.
					<script>var size = $('#dd_'+".$x.").remove();</script>
					";
				}
				if($verifyloi==1)
				{
				$table .= "<td>
					<div class='checkbox'>
						<label><input type='checkbox' value='{$row["id"]}' {$verify_disabled} {$verify_checked} name='verify_list[]'/> </label>
					</div>
				</td>";
				}
				if($approve1loi==1)
				{
					$table .= "<td>
					<div class='checkbox'>
						<label><input type='checkbox' value='{$row["id"]}' {$first_disabled} {$first_checked} name='approved_list1[]'/> </label>
					</div>
					</td>";
				}
				
				if($approve2loi==1)
				{
				$table .= "<td>
					<div class='checkbox'>
						<label><input type='checkbox' {$second_disabled} value='{$row["id"]}' name='approved_list[]'/> </label>
					</div>
				</td>";
				}
				$table .= "<td> <input type='button' name='approve_po' loi_no='{$row['loi_no']}' value='Go' class='btn btn-success go_btn'> 	</td></td>
				</tr>";
				$x++;
			}		
			return $table;
		}
	}
	
	public function get_material_unit_id($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$cnt = $results->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		$unit_id = "";
		foreach($results as $retrive_data)
		{
			$unit_id = $retrive_data['unit_id'];					
		}
		return $unit_id;
	}

	public function getWorkGroupName($id) {
		$erpWorkGroup = TableRegistry::get('erp_work_group');
		$results = $erpWorkGroup->find()->where(['work_group_id' => $id]);
		$res_array = array();
		foreach($results as $retrive_data)
		{
			$res_array['work_group_title'] = $retrive_data['work_group_title'];
			
		}
		if(isset($res_array['work_group_title']))
		// return $res_array['first_name'].' '.$res_array['last_name'];
		return $res_array['work_group_title'];
		else
			return '-';
	}

	public function getWorkSubGroupName($id) {
		if(is_numeric($id)){
			$erpWorkGroup = TableRegistry::get('erp_work_sub_group');
			$results = $erpWorkGroup->find()->where(['sub_work_group_id' => $id]);
			$res_array = array();
			foreach($results as $retrive_data)
			{
				$res_array['sub_work_group_title'] = $retrive_data['sub_work_group_title'];
				
			}
			if(isset($res_array['sub_work_group_title']))
			// return $res_array['first_name'].' '.$res_array['last_name'];
			return $res_array['sub_work_group_title'];
			else
				return '-';
		}
		else{
			return '-';
		}
	}
}
?>