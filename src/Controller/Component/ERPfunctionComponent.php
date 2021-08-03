<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use  Cake\Utility\Xml;
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Routing\Router;

// load GCS library
// require_once 'vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;


class ERPfunctionComponent extends Component
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

	public function check_valid_extension($filename) {
		// debug($filename);die;
		$flag = 2;
		if($filename != '') {
			$flag = 0;
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$valid_extension = ['csv','png','jpg','jpeg','pdf',"",'JPG','PDF','PNG','JPEG'];
			if(in_array($ext,$valid_extension) ) {
				$flag = 1;
			}
		}
		return $flag;
	}

	public function getVisIpAddr() {
      
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	public function ajax_db_config(){
		$sql_details = array(
			 'user' => 'yashnanddb',
			 'pass' => 'xf-b:L>3Q$gh"N2$',
			 'db'   => 'yashnand_erp',
			 'host' => '34.93.247.113'
			//  'user' => 'root',
			//  'pass' => '',
			//  'db'   => 'yashnanderp',
			//  'host' => 'localhost'
		);

		return $sql_details;
	}

	/* Generate unique id code*/
	public function generatePIN($digits = 4)
	{
		$i = 0; //counter
		$pin = ""; //our default pin is blank.
		while($i < $digits){
			//generate a random number between 0 and 9.
			$pin .= mt_rand(0, 9);
			$i++;
		}
		return $pin;
	}
	public function generate_autoid($user='C')
	{
		$random_digit = $this->generatePIN();
		return $user.date('ymd').$random_digit;
	}
	
	public function get_user_employee_at($uid)
	{
		$tbl = TableRegistry::get("erp_users");
		$cat_id = $tbl->find()->where(["user_id"=>$uid])->select(["employee_at"])->hydrate(false)->toArray();
		$project_id = $cat_id[0]["employee_at"];
		$employee_at = $this->get_projectname($project_id);
		return $employee_at;
	}
	/* Image upload code */
	public function upload_image($filename,$old_image='',$image_for = 'users_images')
    {		
		$parts = pathinfo($_FILES[$filename]['name']);
		$file_size = $_FILES[$filename]['size'];
		
		$image_director="img/".$image_for;
			
		$full_path=WWW_ROOT.$image_director;
		
		if($file_size > 0)
		{	
			if (!file_exists($full_path)) {
				mkdir($full_path, 0777, true);
			}
			
			$imgname=$this->generate_autoid('img-').time().'.'.$parts['extension'];
			$return_image = $imgname;
			$image_path=$full_path.'/'.$return_image;
		
			// if($old_image !='') {			
			// 	$image_array = explode('/',$old_image);			
			// 	if (file_exists($full_path.'/'.$image_array[1])) {				
			// 		unlink($full_path.'/'.$image_array[1]);
			// 	}
				
			// }
			
			// if(move_uploaded_file($_FILES[$filename]['tmp_name'],$image_path)) {		
			// 	return $image_for.'/'.$return_image;		
			// }
			require_once "../vendor/autoload.php";
			try {
				$storage  = new StorageClient([
					'projectId' => 'yashnand-erp-2021',
					'keyFilePath' => WWW_ROOT .'/nghome/yashnand-erp-2021-67d9ca62a2c8.json',
				]);
				
				$bucketName = 'yashnand_2021_attachment';
				
				// $storage = new StorageClient();
				$source = $_FILES[$filename]['tmp_name'];
				$objectName = $return_image;
				// debug($objectName);
				$file = fopen($source, 'r');
				$bucket = $storage->bucket($bucketName);
				if($bucket->upload(
					$file, [
						'name' => $objectName
					]
				)){
					$uploads[] = $return_image;
				}
			} catch(Exception $e) {
				echo $e->getMessage();
			}
			return $return_image;
		}
		else
		return $old_image;		
	}

	public function upload_file($filename,$image_for="users_images")
	{

		$file = "";
		$file =  $_FILES[$filename]["name"];		
		$size = count($file);		
		static $uploads = array();
		
		for($i=0;$i<$size;$i++)
		{
			/* $file =  $_FILES[$filename]["name"][$i]; */
			$parts = pathinfo($_FILES[$filename]['name'][$i]);
			$file_size = $_FILES[$filename]['size'][$i];
			
			$image_director="img/".$image_for;			
			$full_path=WWW_ROOT.$image_director;
			
			if($file_size > 0)
			{	
				if (!file_exists($full_path)) {
					mkdir($full_path, 0777, true);
				}
			
				$imgname=$this->generate_autoid('img-').time().'.'.$parts['extension'];
				$return_image = $imgname;
				$image_path=$full_path.'/'.$return_image;
				// debug($imgname);die;
				// if(move_uploaded_file($_FILES[$filename]['tmp_name'][$i],$image_path))
				// {		
					/* $uploads[] = $image_for."/".$return_image;		 */
					// $uploads[] = $return_image;		
				// }		
				require_once "../vendor/autoload.php";
				try {
					$storage  = new StorageClient([
						'projectId' => 'yashnand-erp-2021',
						'keyFilePath' => WWW_ROOT .'/nghome/yashnand-erp-2021-67d9ca62a2c8.json',
					]);
				 
					$bucketName = 'yashnand_2021_attachment';
					
					// $storage = new StorageClient();
					$source = $_FILES[$filename]['tmp_name'][$i];
					$objectName = $return_image;
					// debug($objectName);
					$file = fopen($source, 'r');
					$bucket = $storage->bucket($bucketName);
					if($bucket->upload(
						$file, [
							'name' => $objectName
						]
					)){
						$uploads[] = $return_image;
					}
				} catch(Exception $e) {
					echo $e->getMessage();
				}
			}
		}
		return $uploads;
	}
	/*Genrate Auto Id For Candidate*/

	public function candidate_asset_auto_id($tbl,$desc_fld,$auto_incr_fld)
	{
		
		$tbl= TableRegistry::get($tbl);

		// $data = $tbl->find("all")->where(["asset_group"=>$group_id])->order(["{$desc_fld}"=>"DESC"])->limit(1)->hydrate(false)->toArray();
		
		$data = $tbl->find("all")->order(["{$desc_fld}"=>"DESC"])->limit(1)->hydrate(false)->toArray();


		if(!empty($data))
		{
			$auto_fld = $data[0]["{$auto_incr_fld}"];
			$split = explode("-",$auto_fld);
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$last_number = (int) $last_id;
			$new_no = $last_number + 1;	

			/* $new_id = str_ireplace("{$last_number}","{$new_no}",$last_id); */
		}else{
			$new_no = 1;
		}
		return $new_no;
	}

	/*End Generate Auto Id For Candidate*/
	public function upload_file_wo($filename,$image_for="users_images")
	{
		$file = "";
		$file =  $_FILES[$filename]["name"];		
		$size = count($file);		
		static $uploads = array();
		
		for($i=0;$i<$size;$i++)
		{
			/* $file =  $_FILES[$filename]["name"][$i]; */
			$parts = pathinfo($_FILES[$filename]['name'][$i]);
			$file_size = $_FILES[$filename]['size'][$i];
			
			$image_director="upload";			
			$full_path=WWW_ROOT.$image_director;
			
			if($file_size > 0)
			{	
				if (!file_exists($full_path)) {
					mkdir($full_path, 0777, true);
				}
			
				$imgname=$this->generate_autoid('pdf-').time().'.'.$parts['extension'];
				$return_image = $imgname;
				$image_path=$full_path.'/'.$return_image;
				
				// if(move_uploaded_file($_FILES[$filename]['tmp_name'][$i],$image_path))
				// {		
				// 	/* $uploads[] = $image_for."/".$return_image;		 */
				// 	$uploads[] = $return_image;		
				// }	
				require_once "../vendor/autoload.php";
				try {
					$storage  = new StorageClient([
						'projectId' => 'yashnand-erp-2021',
						'keyFilePath' => WWW_ROOT .'/nghome/yashnand-erp-2021-67d9ca62a2c8.json',
					]);
				 
					$bucketName = 'yashnand_2021_attachment';
					
					// $storage = new StorageClient();
					$source = $_FILES[$filename]['tmp_name'][$i];
					$objectName = $return_image;
					// debug($objectName);
					$file = fopen($source, 'r');
					$bucket = $storage->bucket($bucketName);
					if($bucket->upload(
						$file, [
							'name' => $objectName
						]
					)){
						$uploads[] = $return_image;
					}
				} catch(Exception $e) {
					echo $e->getMessage();
				}	
			}
		}
		return $uploads;
	}
	public function upload_challan($filename,$image_for="users_images")
	{
		$file =  $_FILES[$filename]["name"];		
		$size = count($file);		
		static $uploads = array();
		
		for($i=1;$i<=$size;$i++)
		{
			/* $file =  $_FILES[$filename]["name"][$i]; */
			$parts = pathinfo($_FILES[$filename]['name'][$i]);
			$file_size = $_FILES[$filename]['size'][$i];
			
			$image_director="img/".$image_for;			
			$full_path=WWW_ROOT.$image_director;
			
			if($file_size > 0)
			{	
				if (!file_exists($full_path)) {
					mkdir($full_path, 0777, true);
				}
			
				$imgname=$this->generate_autoid('img-').time().'.'.$parts['extension'];
				$return_image = $imgname;
				$image_path=$full_path.'/'.$return_image;
				
				if(move_uploaded_file($_FILES[$filename]['tmp_name'][$i],$image_path))
				{		
					/* $uploads[] = $image_for."/".$return_image;		 */
					$uploads[] = $return_image;						
				}		
			}else{
				$uploads[] = "";
			}
		}
		return $uploads;
	}
	
	public function upload_inward($filename,$image_for="users_images")
	{
		$file =  $_FILES[$filename]["name"];		
		$size = count($file);		
		static $uploads = array();
		
		for($i=0;$i<$size;$i++)
		{
			/* $file =  $_FILES[$filename]["name"][$i]; */
			$parts = pathinfo($_FILES[$filename]['name'][$i]);
			$file_size = $_FILES[$filename]['size'][$i];
			
			$image_director="img/".$image_for;			
			$full_path=WWW_ROOT.$image_director;
			
			if($file_size > 0)
			{	
				if (!file_exists($full_path)) {
					mkdir($full_path, 0777, true);
				}
			
				$imgname=$this->generate_autoid('img-').time().'.'.$parts['extension'];
				$return_image = $imgname;
				$image_path=$full_path.'/'.$return_image;
				
				if(move_uploaded_file($_FILES[$filename]['tmp_name'][$i],$image_path))
				{		
					/* $uploads[] = $image_for."/".$return_image;*/
					$uploads[] = $return_image;						
				}		
			}else{
				$uploads[] = "";
			}
		}
		return $uploads;
	}
	
	public function get_brand_name($brand_id)
	{
		$erp_material_brand = TableRegistry::get('erp_material_brand'); 
		$brand_data = $erp_material_brand->find()->where(['brand_id'=>$brand_id])->hydrate(false)->toArray();
		
		if(!empty($brand_data))
		{
			/* $brand_array = array();
			foreach($brand_data as $retrive_data)
			{			
				$brand_array['brand_name'] = $retrive_data['brand_name'];
			} */
			return $brand_data[0]['brand_name'];
		}
		else{
			return "-";
		}
	}
	
	// public function get_category_title($cat_id)
	// {
		// $erp_category_master = TableRegistry::get('erp_category_master'); 
		// $category_data = $erp_category_master->find()->where(['cat_id'=>$cat_id]);
		// $res_array = array();
		// foreach($category_data as $retrive_data)
		// {
			// $res_array['category_title'] = $retrive_data['category_title'];
			// $res_array['cat_id'] = $retrive_data['cat_id'];
		// }
		// return $res_array['category_title'];
	// }
	
	public function get_category_title($cat_id)
	{
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
	
	public function set_date($date)
	{ 
		return date('Y-m-d H:i:s',strtotime($date));
	}
	public function get_date($date)
	{
		return date('d-m-Y',strtotime($date));
	}
	
	public function is_duplicate_project_code($project_code)
	{
		$erp_projects = TableRegistry::get('erp_projects'); 		
		$exists = $erp_projects->exists(['project_code'=>$project_code]);
			
		if($exists)
		{
			return true;
		}
		return false;
	}
	public function assign_project($constructionmanager_id,$assign_projects = array())
	{
		$erp_projects_assign = TableRegistry::get('erp_projects_assign');
		$new_projects = $assign_projects;
		$old_projects = $this->old_project($constructionmanager_id);
		$different_insert = array_diff($new_projects,$old_projects);
		$different_delete = array_diff($old_projects,$new_projects);		
		if(!empty($different_insert))		
		{
			foreach($different_insert as $project_id)
			{
				$data['user_id'] =  $constructionmanager_id;
				$data['project_id'] =  $project_id;
				$data['status'] =  1;
				$patch_field = $erp_projects_assign->newEntity();					
				$save_field=$erp_projects_assign->patchEntity($patch_field,$data);
				$erp_projects_assign->save($save_field);
			}
		}	
		if(!empty($different_delete))
		{
			foreach($different_delete as $project_id)
			{				
				$this->delete_project($constructionmanager_id,$project_id);		
			}
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
	
	public function delete_project($user_id,$project_id)
	{
		$erp_projects_assign = TableRegistry::get('erp_projects_assign');
		$results = $erp_projects_assign->find()->where(array('user_id'=>$user_id,'project_id'=>$project_id));
		foreach($results as $retrive_data)
		{
			$assign_id = $retrive_data['assign_id'];
			$user_data =$erp_projects_assign->get($assign_id);
			$erp_projects_assign->delete($user_data);
		}		
	}
	
	public function material_category()
	{
		$category  = array();
		$category['1']= array('material_code'=>'YNEC/MT/PC',
							'category_name'=>'Packed Cement');
		$category['2']= array('material_code'=>'YNEC/MT/LC',
							'category_name'=>'Loose Cement');
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
	
	public function get_mm_constructionmanager()
	{
		$users_table = TableRegistry::get('erp_users'); 
		
		//$user = array('materialmanager','constructionmanager');
		$user = array('materialmanager');
		$all_user = array();
		foreach($user as $user_role)
		{
			$all_user[$user_role] = $users_table->find()->where(array('role'=>$user_role));
		}
		
		$return_user = array();
		foreach($all_user as $key => $user_data)
		{
			$users = array();
			foreach($user_data as $retrive_data)
			{
				$users[] = $retrive_data;
			}
			$return_user[$key] = $users;
		}
		return $return_user;
		
	}
	
	public function add_inventory_po_detail($material_items,$po_id,$pr_mid = array())
	{
		$erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail'); 
		$po_completed = 1;
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['po_id'] =  $po_id;
			if(!empty($pr_mid))
			{
				$save_data['pr_mid'] =  $pr_mid[$key];
			}
			else{
				$save_data['pr_mid'] =  0;
			}
					
			$save_data['material_id'] =  $material_items['material_id'][$key];
			if(isset($material_items['m_code'][$key]))
			{
				$save_data['m_code'] =  $material_items['m_code'][$key];
			}
			if(isset($material_items['static_unit'][$key]))
			{
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
			}
			if(isset($material_items['is_custom'][$key]))
			{
				$save_data['is_custom'] =  $material_items['is_custom'][$key];
			}
			// $save_data['hsn_code'] =  $material_items['hsn_code'][$key];
			$save_data['description'] = $material_items['description'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];
			// $save_data['transportation'] =  $material_items['transportation'][$key];
			// $save_data['exice'] =  $material_items['exice'][$key];
			// $save_data['other_tax'] =  $material_items['other_tax'][$key];
			$save_data['gst'] =  $material_items['gst'][$key];
			$save_data['update_status'] = 1;
			$save_data['amount'] =  $material_items['amount'][$key];
			$save_data['single_amount'] =  $material_items['single_amount'][$key];
			//$save_data['delivery_date'] =  $this->set_date($material_items['delivery_date'][$key]);
			$pr_material_data = $erp_inventory_po_detail->newEntity();			
			$pr_material_data=$erp_inventory_po_detail->patchEntity($pr_material_data,$save_data);
			$ok = $erp_inventory_po_detail->save($pr_material_data);	
			//Update PR Material table for particular row and upate po pending quentity
			if($ok)
			{
				if($pr_mid[$key])
				{
					$m_tbl = TableRegistry::get("erp_inventory_pr_material");
					$row = $m_tbl->get($pr_mid[$key]);
					$row->po_approved_quantity = $material_items['quantity'][$key];
					$row->po_pending_quantity = $row->quantity - $material_items['quantity'][$key];
					if($material_items['quantity'][$key] < $row->quantity)
					{
						$row->po_completed = 3;//3 means half created po and 
						$po_completed = 0;
					}else{
						$row->po_completed = 0;//0 means completed po
					}
					$m_tbl->save($row);
				}
			}
		}
		return $po_completed;	
	}
	
	public function add_manual_po_detail($material_items,$po_id,$pr_mid = array())
	{
		$erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail'); 
		foreach($material_items['material_id'] as $key => $data)
		{
			// debug($material_items['material_id']);die;
			$save_data['po_id'] =  $po_id;		
			$save_data['material_id'] =  $material_items['material_id'][$key];
			if(isset($material_items['static_unit'][$key]))
			{
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
			}
			if(isset($material_items['is_custom'][$key]))
			{
				$save_data['is_custom'] =  $material_items['is_custom'][$key];
			}
			// $save_data['hsn_code'] =  $material_items['hsn_code'][$key];
			// $save_date['mode_of_gst'] = $material_items['mode_of_gst'][$key];
			$save_data['description'] = $material_items['description'][$key];
			$save_data['po_type'] =  "manual_po";
			$save_data['grn_remain_qty'] =  $material_items['quantity'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];
			// $save_data['transportation'] =  $material_items['transportation'][$key];
			// $save_data['exice'] =  $material_items['exice'][$key];
			// $save_data['other_tax'] =  $material_items['other_tax'][$key];
			$save_data['gst'] = $material_items['gst'][$key];
			$save_data['update_status'] = 1;
			$save_data['amount'] =  $material_items['amount'][$key];
			$save_data['single_amount'] =  $material_items['single_amount'][$key];
			//$save_data['delivery_date'] =  $this->set_date($material_items['delivery_date'][$key]);
			// debug($save_data);die;
			$pr_material_data = $erp_inventory_po_detail->newEntity();			
			$pr_material_data=$erp_inventory_po_detail->patchEntity($pr_material_data,$save_data);
			$erp_inventory_po_detail->save($pr_material_data);						
		}		
	}
	
	public function add_inventory_pr_material($material_items,$pr_id,$custom)
	{
		$erp_inventory_pr_materia = TableRegistry::get('erp_inventory_pr_material'); 
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['pr_id'] =  $pr_id;
			if(is_numeric($material_items['material_id'][$key]))
			{
				$save_data['material_id'] =  $material_items['material_id'][$key];
				$save_data['brand_id'] =  $material_items['brand_id'][$key];
			}
			else
			{
				$save_data['material_id'] =  0;
				$save_data['brand_id'] =  0;
				$save_data['material_name'] =  $material_items['material_id'][$key];
				$save_data['brand_name'] =  $material_items['brand_id'][$key];
			}
			$save_data['is_custom'] =  $custom;
			$save_data['m_code'] =  $material_items['m_code'][$key];
			$save_data['static_unit'] =  $material_items['static_unit'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['po_pending_quantity'] =  $material_items['quantity'][$key];
			$save_data['po_approved_quantity'] =  0;
			$save_data['po_completed'] =  2;//2 means po not created
			$save_data['delivery_date'] =  $this->set_date($material_items['delivery_date'][$key]);
			$save_data['name_of_subcontractor'] =  $material_items['name_of_subcontractor'][$key];
			$save_data['usages'] =  $material_items['usage'][$key];
			$pr_material_data = $erp_inventory_pr_materia->newEntity();			
			$pr_material_data=$erp_inventory_pr_materia->patchEntity($pr_material_data,$save_data);
			$erp_inventory_pr_materia->save($pr_material_data);						
		}		
	}
	
	public function add_advance_req_detail($material_items,$pr_id,$project_id)
	{
		$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail'); 
		foreach($material_items['agency_id'] as $key => $data)
		{
			$save_data['request_id'] =  $pr_id;			
			$save_data['agency_id'] =  $material_items['agency_id'][$key];
			$save_data['labor'] =  $material_items['labors'][$key];
			$save_data['advance_rs'] =  $material_items['advance_rs'][$key];
			$save_data['project_id'] =  $project_id;
			$req_data = $erp_advance_request_detail->newEntity();			
			$req_data=$erp_advance_request_detail->patchEntity($req_data,$save_data);
			$erp_advance_request_detail->save($req_data);						
		}		
	}
	
	public function add_expence_detail($expence_item,$exp_id,$total,$total_word,$user)
	{
		$erp_expence_detail = TableRegistry::get('erp_expence_detail'); 
		foreach($expence_item['description'] as $key => $data)
		{
			$save_data['exp_id'] =  $exp_id;			
			$save_data['expence_description'] =  $expence_item['description'][$key];
			$save_data['expence_amount'] =  $expence_item['amount'][$key];
			$save_data['expence_total'] =  $total;
			$save_data['expence_toatl_word'] =  $total_word;
			$save_data['created_by'] =  $user;
			$req_data = $erp_expence_detail->newEntity();			
			$req_data=$erp_expence_detail->patchEntity($req_data,$save_data);
			$erp_expence_detail->save($req_data);						
		}		
	}
	
	public function edit_advance_req_detail($material_items,$pr_id,$project_id)
	{
		$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail'); 
		foreach($material_items['agency_id'] as $key => $data)
		{
			$save_data['request_id'] =  $pr_id;			
			$save_data['agency_id'] =  $material_items['agency_id'][$key];
			$save_data['labor'] =  $material_items['labors'][$key];
			$save_data['advance_rs'] =  $material_items['advance_rs'][$key];
			$save_data['project_id'] = $project_id;
			if(isset($material_items['id'][$key]))
			{
				$req_data = $erp_advance_request_detail->get($material_items['id'][$key]);
			}else
			{
				$req_data = $erp_advance_request_detail->newEntity();
			}
						
			$req_data=$erp_advance_request_detail->patchEntity($req_data,$save_data);
			$erp_advance_request_detail->save($req_data);						
		}		
	}
	
	public function add_inventory_grn_detail($material_items,$grn_id)
	{
		$erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail'); 
		$grndetail_id = array();
		// debug($material_items);
		foreach($material_items['material_id'] as $key => $data)
		{
			if($material_items['material_id'][$key] != '')
			{
			$save_data = array();
			$save_data['grn_id'] =  $grn_id;			
			if(is_numeric($material_items['material_id'][$key]))
			{
				$save_data['material_id'] =  $material_items['material_id'][$key];
				$save_data['brand_id'] =  $material_items['brand_id'][$key];
				$save_data['is_static'] = 0;
				if(isset($save_data['static_unit']))
				{
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
				}
			}
			else
			{
				$save_data['material_id'] =  0;
				$save_data['brand_id'] =  0;
				$save_data['is_static'] = 1;
				$save_data['material_name'] =  $material_items['material_id'][$key];
				$save_data['brand_name'] =  $material_items['brand_id'][$key];
				$save_data['m_code'] =  $material_items['m_code'][$key];
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
			}
			if(isset($material_items['po_detail_id'][$key]))
			{
				$save_data['po_detail_id'] = $material_items['po_detail_id'][$key];
			}
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['remarks'] =  $material_items['remark'][$key];
			$save_data['actual_qty'] =  $material_items['actual_qty'][$key];
			$save_data['difference_qty'] =  $material_items['difference_qty'][$key];
			$save_data['unit_price'] = $material_items['unit_price'][$key];
			$save_data['discount'] = $material_items['discount'][$key];
			$save_data['gst'] = $material_items['gst'][$key];
			$save_data['amount'] = $material_items['amount'][$key];
			$save_data['single_amount'] = $material_items['single_amount'][$key];
			$save_data['approved'] =  0;
			/* $save_data['remarks'] =  $material_items['remarks'][$key]; */
			$entity_data = $erp_inventory_grn_detail->newEntity();			
			$material_data=$erp_inventory_grn_detail->patchEntity($entity_data,$save_data);
			$ok = $erp_inventory_grn_detail->save($material_data);
			if($ok)
			{
				if(isset($material_items['po_detail_id'][$key]))
				{
					if($material_items['po_detail_id'][$key])
					{
						$pod_tbl = TableRegistry::get("erp_inventory_po_detail");
						$row = $pod_tbl->get($material_items['po_detail_id'][$key]);
						$remain_quantity = $row->grn_remain_qty - $material_items['actual_qty'][$key];
						$row->grn_remain_qty = $remain_quantity;
						//if remain quentity is 0 (Zero) then make approve po detail record.
						if($remain_quantity <= 0)
						{
							$row->approved = 2;
						}
						$pod_tbl->save($row);
					}
				}
			}
			$grndetail_id[] =  $material_data->grndetail_id;
			}
		}		
		return $grndetail_id;
	}
	public function edit_inventory_grn_detail($material_items,$grn_id,$approved,$post,$grn_type = NULL,$old_grn_type = NULL)
	{
		$erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail');
		
		if(($grn_type != NULL && $old_grn_type != NULL) && ($grn_type != $old_grn_type) && ($grn_type == "with_po"))
		{
			// Delete all old material record of without po when mode update to with po
			$erp_inventory_grn_detail->deleteAll(["grn_id"=>$grn_id]);
		}
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['grn_id'] =  $grn_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['actual_qty'] =  $material_items['actual_qty'][$key];
			$save_data['difference_qty'] =  $material_items['difference_qty'][$key];
			$save_data['remarks'] =  $material_items['remark'][$key];
			$save_data['unit_price'] = $material_items['unit_price'][$key];
			$save_data['discount'] = $material_items['discount'][$key];
			$save_data['gst'] = $material_items['gst'][$key];
			$save_data['amount'] = $material_items['amount'][$key];
			$save_data['single_amount'] = $material_items['single_amount'][$key];
			if($approved == "approved")
			{
				$save_data['approved'] = 1;
				$save_data['approved_date'] = date("Y-m-d");
				$save_data['approved_time'] = date("H:i:s");
				$save_data['approved_by'] = $this->request->session()->read('user_id');
			}
			if(($grn_type != NULL && $old_grn_type != NULL) && ($grn_type != $old_grn_type) && ($grn_type == "with_po"))
			{
				if(isset($material_items['po_detail_id'][$key]))
				{
					$save_data['po_detail_id'] =  $material_items['po_detail_id'][$key];
				}
			}
			if(isset($material_items['detail_id'][$key]))
			{
				$req_data = $erp_inventory_grn_detail->get($material_items['detail_id'][$key]);
			}else
			{
				$req_data = $erp_inventory_grn_detail->newEntity();
			}
						
			$row=$erp_inventory_grn_detail->patchEntity($req_data,$save_data);
			
			if($erp_inventory_grn_detail->save($row))
			{
				if(($grn_type != NULL && $old_grn_type != NULL) && ($grn_type != $old_grn_type) && ($grn_type == "with_po"))
				{
					/* Manage po quantity when mode change from without po to with po */
					if(isset($material_items['po_detail_id'][$key]))
					{
						if($material_items['po_detail_id'][$key])
						{
							$pod_tbl = TableRegistry::get("erp_inventory_po_detail");
							$row = $pod_tbl->get($material_items['po_detail_id'][$key]);
							$remain_quantity = $row->grn_remain_qty - $material_items['actual_qty'][$key];
							$row->grn_remain_qty = $remain_quantity;
							//if remain quentity is 0 (Zero) then make approve po detail record.
							if($remain_quantity <= 0)
							{
								$row->approved = 2;
							}
							$pod_tbl->save($row);
						}
					}	
					/* Manage po quantity when mode change from without po to with po */
				}
				
				/* Stock update only when update approved GRN */
				if($approved == "approved")
				{
					$history_tbl = TableRegistry::get("erp_stock_history");
					if(isset($material_items['detail_id'][$key]))
					{
						$hstry_query = $history_tbl->query();
						$hstry_query->update()
							->set(["project_id"=>$post['project_id'],"date"=>date("Y-m-d",strtotime($post["grn_date"])),"material_id"=>$material_items['material_id'][$key],"quantity"=>$material_items["actual_qty"][$key],"stock_in"=>$material_items["actual_qty"][$key],"detail_id"=>$material_items['detail_id'][$key]])
							->where(["type_id"=>$grn_id,"material_id"=>$material_items['material_id'][$key],"type"=>'grn'])
							->execute();
						
						
						// For stock table update
						$stock_tbl = TableRegistry::get("erp_stock");
						$check_stock = $stock_tbl->find("all")->where(["project_id"=>$post['project_id'],"material_id"=>$material_items['material_id'][$key]])->hydrate(false)->toArray();
		
						$new_quentity = $material_items["actual_qty"][$key];
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
					}else{
						$new_entry = $history_tbl->newEntity();
						$new_entry['date'] = date("Y-m-d",strtotime($post["grn_date"]));
						$new_entry['project_id'] = $post['project_id'];
						$new_entry['material_id'] = $material_items['material_id'][$key];
						$new_entry['quantity'] = $material_items["actual_qty"][$key];
						$new_entry['stock_in'] = $material_items["actual_qty"][$key];
						$new_entry['type'] = 'grn';
						$new_entry['type_id'] = $grn_id;
						$new_entry['detail_id'] = $row['grndetail_id'];
						$history_tbl->save($new_entry);
					}
				}
				/* Stock update only when update approved GRN */
			}
			$key++;				
		}		
	}
	
	public function edit_inventory_auditgrn_detail($material_items,$audit_id)
	{
		$erp_audit_grn_detail = TableRegistry::get('erp_audit_grn_detail');
		$updated = 0;
		// debug($material_items);die;
		foreach($material_items['material_id'] as $key => $data)
		{
			$changes = array();
			$save_data['audit_id'] =  $audit_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['actual_qty'] =  $material_items['actual_qty'][$key];
			$save_data['difference_qty'] =  $material_items['difference_qty'][$key];
			
			if(isset($material_items['detail_id'][$key]))
			{
				$req_data = $erp_audit_grn_detail->get($material_items['detail_id'][$key]);
			}else
			{
				$req_data = $erp_audit_grn_detail->newEntity();
			}
						
			$row=$erp_audit_grn_detail->patchEntity($req_data,$save_data);
			$diff = $row->extract($row->visibleProperties(), true);
			
			if(empty($row["changes"]))
			{
				/* Add user detail who make changes */
				if(!empty($diff))
				{
					$diff["edit_by"] = $this->request->session()->read('user_id');				
					$diff = json_encode($diff);
					$changes[date("Y-m-d H:i:s")] = $diff;
					$row["changes"] = json_encode($changes);
					$updated = 1;
					$row["changes_status"] = 1;
				}
				/* Add user detail who make changes */
			}else{
				if(!empty($diff))
				{
					$diff["edit_by"] = $this->request->session()->read('user_id');				
					$diff = json_encode($diff);
					$changes[date("Y-m-d H:i:s")] = $diff;
					$changes = json_encode($changes);
										
					$row["changes"] = json_encode(array_merge(json_decode($changes, true),json_decode($row["changes"], true)));
					$updated = 1;
					$row["changes_status"] = 1;
				}
			}
			
			if($erp_audit_grn_detail->save($row))
			{
				
			}
			$key++;				
		}	
		return $updated;
	}
	
	public function edit_inventory_po_detail($material_items,$po_id,$po_type)
	{
		 $erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail');
		foreach($material_items['material_id'] as $key => $data) {
			if(isset($material_items['m_code'][$key])) {
				$save_data['m_code'] =  $material_items['m_code'][$key];
			}
			if(isset($material_items['static_unit'][$key])) {
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
			}
			if(isset($material_items['is_custom'][$key])) {
				$save_data['is_custom'] =  $material_items['is_custom'][$key];
			}
			if(isset($material_items['pr_mid'][$key])) {
				$save_data['pr_mid'] =  $material_items['pr_mid'][$key];
			}else {
				$save_data['pr_mid'] =  0;
			}
			
			if($material_items['unit_rate'][$key] != '') {
				$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			}else {
				$save_data['unit_price'] =  0;
			}
			$save_data['po_id'] =  $po_id;	

			if(isset( $material_items['description'][$key])) {
				$save_data['description'] = $material_items['description'][$key];
			}	
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];		
			$save_data['gst'] = $material_items['gst'][$key];
			$save_data['update_status'] = 1 ;
			$save_data['amount'] =  $material_items['amount'][$key];
			$save_data['single_amount'] =  $material_items['single_amount'][$key];	
			
			if(isset($material_items['detail_id'][$key])) {
				$req_data = $erp_inventory_po_detail->get($material_items['detail_id'][$key]);
				$actual_qty = $req_data->quantity; //po material quentiry before update
				$pr_mid = $req_data->pr_mid; //po material quentiry before update
			}else {
				$req_data = $erp_inventory_po_detail->newEntity();
				$save_data['po_type'] = $po_type;
			}
						
			$row=$erp_inventory_po_detail->patchEntity($req_data,$save_data);
			$ok = $erp_inventory_po_detail->save($row);
			if($ok) {
				if($pr_mid) {
					if($material_items['quantity'][$key] < $actual_qty) {
						$diff = $actual_qty - $material_items['quantity'][$key];
						$pr_material = TableRegistry::get('erp_inventory_pr_material');
						$pr_row = $pr_material->get($pr_mid);
						$pr_row->po_pending_quantity = $pr_row->po_pending_quantity + $diff;
						$pr_row->po_approved_quantity = $pr_row->po_approved_quantity - $diff;
						$pr_row->po_completed =3;
						$pr_row->approved =0;
						$pr_material->save($pr_row);
					}
					
					if($material_items['quantity'][$key] > $actual_qty) {
						$diff = $material_items['quantity'][$key] - $actual_qty;
						$pr_material = TableRegistry::get('erp_inventory_pr_material');
						$pr_row = $pr_material->get($pr_mid);
						$pr_created_quantity = $pr_row->quantity;// PR actual quantity
						$po_pending_quantity = $pr_row->po_pending_quantity;// PR actual quantity
						$po_approved_quantity = $pr_row->po_approved_quantity;//PR actual quantity
						
						$new_approved_quantity = $po_approved_quantity + $diff;
						$new_pending_quantity = $po_pending_quantity - $diff;
						if($new_approved_quantity > $pr_created_quantity) {
							$pr_row->po_approved_quantity = $pr_created_quantity;
							$pr_row->po_pending_quantity = 0;
							$pr_row->po_completed = 0;
							$pr_row->approved = 1;
						}elseif($new_approved_quantity < $pr_created_quantity) {
							$pr_row->po_approved_quantity = $new_approved_quantity;
							$pr_row->po_pending_quantity = $new_pending_quantity;
							$pr_row->po_completed = 3;
							$pr_row->approved = 0;
						}elseif($new_approved_quantity == $pr_created_quantity) {
							$pr_row->po_approved_quantity = $new_approved_quantity;
							$pr_row->po_pending_quantity = $new_pending_quantity;
							$pr_row->po_completed = 0;
							$pr_row->approved = 1;
						}
						
						$pr_material->save($pr_row);
					}
				}
			}
			//$key++;	
		}
		//debug($save_data);die;
	}

	public function edit_inventory_po_grn_detail($material_items) {
		$erp_inventory_grn_detail = TableRegistry::get("erp_inventory_grn_detail");
		foreach($material_items['material_id'] as $key => $data) {
			// debug($material_items['detail_id'][0]);die;
			$grnSaveData['discount'] =  $material_items['discount'][$key];
			$grnSaveData['gst'] = $material_items['gst'][$key];
			$query = $erp_inventory_grn_detail->query();
			
			$query->update()
				->set(['unit_price' => $material_items['unit_rate'][$key],"discount"=> $material_items['discount'][$key],"gst" => $material_items['gst'][$key]])
				->where(['po_detail_id' => $material_items['detail_id'][0]])
				->execute();
		}
	}
	
	public function edit_manual_po_detail($material_items,$po_id)
	{
		$erp_manual_po_detail = TableRegistry::get('erp_manual_po_detail'); 
		
		foreach($material_items['material_id'] as $key => $data)
		{
			if(isset($material_items['static_unit'][$key]))
			{
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
			}
			if(isset($material_items['is_custom'][$key]))
			{
				$save_data['is_custom'] =  $material_items['is_custom'][$key];
			}
			
			if($material_items['unit_rate'][$key] != '')
			{
				$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			}
			else
			{
				$save_data['unit_price'] =  0;
			}
			$save_data['po_id'] =  $po_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['hsn_code'] =  $material_items['hsn_code'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['grn_remain_qty'] =  $material_items['quantity'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];
			$save_data['transportation'] =  $material_items['transportation'][$key];
			$save_data['exice'] =  $material_items['exice'][$key];
			$save_data['other_tax'] =  $material_items['other_tax'][$key];			
			$save_data['amount'] =  $material_items['amount'][$key];
			$save_data['single_amount'] =  $material_items['single_amount'][$key];
			//$save_data['delivery_date'] =  $this->set_date($material_items['delivery_date'][$key]);	
			
			if(isset($material_items['detail_id'][$key]))
			{
				$req_data = $erp_manual_po_detail->get($material_items['detail_id'][$key]);
			}else
			{
				$req_data = $erp_manual_po_detail->newEntity();
			}
						
			$row=$erp_manual_po_detail->patchEntity($req_data,$save_data);
			
			$erp_manual_po_detail->save($row);	
		}

	}
	
	public function add_inventory_is_detail($material_items,$is_id,$project_id = NULL,$is_date)
	{
		$erp_inventory_is_detail = TableRegistry::get('erp_inventory_is_detail'); 
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
			$save_data['approved'] =  1;
			$save_data['approved_date'] =  date('Y-m-d');
			$save_data['approved_by'] =  $this->request->session()->read('user_id');
			$entity_data = $erp_inventory_is_detail->newEntity();			
			$material_data=$erp_inventory_is_detail->patchEntity($entity_data,$save_data);
			$erp_inventory_is_detail->save($material_data);	
			$is_detail_id = $material_data->is_detail_id;
			// For add entry in stock history and stock table
			$history_tbl = TableRegistry::get("erp_stock_history");
			$stock_tbl = TableRegistry::get("erp_stock");
			
			$history_row = $history_tbl->newEntity();
			$insert["date"] = $is_date;
			$insert["project_id"] = $project_id;
			$insert["material_id"] = $material_items['material_id'][$key];
			$insert["quantity"] = $material_items['quantity'][$key];
			$insert["stock_out"] = $material_items['quantity'][$key];		
			$insert["type"] = "is";
			$insert["type_id"] = $is_id;
			$insert["detail_id"] = $is_detail_id;
			$history_row = $history_tbl->patchEntity($history_row,$insert);
			$history_tbl->save($history_row);
			
			$check_stock = $stock_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_items['material_id'][$key]])->hydrate(false)->toArray();		

			if(!empty($check_stock))
			{			
				$query = $stock_tbl->query();
				$query->update()
					->set(['quantity' => $check_stock[0]["quantity"] - intval($material_items['quantity'][$key])])
					->where(['project_id' => $project_id,'material_id'=>$material_items['material_id'][$key]])
					->execute();
			}
			else{
				$stock_row = $stock_tbl->newEntity();
				$stock_data["project_id"] = $project_id;
				$stock_data["material_id"] = $material_items['material_id'][$key];
				$stock_data["quantity"] = $material_items['quantity'][$key];			
				$stock_row = $stock_tbl->patchEntity($stock_row,$stock_data);
				$stock_tbl->save($stock_row);
			}
		}
		
		/* Inser record in IS Audit */
		$erp_inventory_is = TableRegistry::get('erp_inventory_is');
		$erp_is_audit = TableRegistry::get('erp_is_audit');
		$erp_audit_is_detail = TableRegistry::get('erp_audit_is_detail');
		
		$is_row = $erp_inventory_is->get($is_id);
		$is_row = $is_row->toArray();
		$audit_row = $erp_is_audit->newEntity($is_row);
		if($erp_is_audit->save($audit_row))
		{
			$audit_id = $audit_row->audit_is_id;
			$is_detail_rows = $erp_inventory_is_detail->find()->where(["is_id"=>$is_id])->hydrate(false)->toArray();
			
			if(!empty($is_detail_rows))
			{
				foreach($is_detail_rows as $retrive_row)
				{
					$retrive_row["is_audit_id"] = $audit_id;
					$audit_detail_row = $erp_audit_is_detail->newEntity($retrive_row);
					$erp_audit_is_detail->save($audit_detail_row);
				}
			}
		}
		
	}
	
	public function add_inventory_mrn_detail($material_items,$mrn_id)
	{
		$erp_inventory_mrn_detail = TableRegistry::get('erp_inventory_mrn_detail'); 
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['mrn_id'] =  $mrn_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['remarks'] =  $material_items['remarks'][$key];			
			$entity_data = $erp_inventory_mrn_detail->newEntity();			
			$material_data=$erp_inventory_mrn_detail->patchEntity($entity_data,$save_data);
			$erp_inventory_mrn_detail->save($material_data);						
		}		
	}
	
	public function add_inventory_rbn_detail($material_items,$rbn_id,$project_id = NULL,$rbn_date)
	{
		$erp_inventory_rbn_detail = TableRegistry::get('erp_inventory_rbn_detail'); 
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['rbn_id'] =  $rbn_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['quantity_reurn'] =  $material_items['quantity_reurn'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			/* $save_data['return_by'] =  $material_items['return_by'][$key]; */
			$save_data['name_of_foreman'] =  $material_items['name_of_foreman'][$key];			
			$save_data['time_of_return'] =  $material_items['time_of_return'][$key];			
			$save_data['approved'] = 1;			
			$save_data['approved_date'] = date('Y-m-d');			
			$save_data['approved_by'] = $this->request->session()->read('user_id');			
			/* $save_data['return_reason'] =  $material_items['return_reason'][$key];			 */
			$entity_data = $erp_inventory_rbn_detail->newEntity();			
			$material_data=$erp_inventory_rbn_detail->patchEntity($entity_data,$save_data);
			$erp_inventory_rbn_detail->save($material_data);
			$rbn_detail_id = $material_data->rbn_detail_id;

			$history_tbl = TableRegistry::get("erp_stock_history");
			$history_row = $history_tbl->newEntity();
			$insert["date"] = $rbn_date;
			$insert["project_id"] = $project_id;
			$insert["material_id"] = $material_items['material_id'][$key];
			$insert["quantity"] = $material_items['quantity_reurn'][$key];
			$insert["return_back"] = $material_items['quantity_reurn'][$key];			
			$insert["type"] = "rbn";
			$insert["type_id"] = $rbn_id;
			$insert["detail_id"] = $rbn_detail_id;
			$history_row = $history_tbl->patchEntity($history_row,$insert);
			$history_tbl->save($history_row);	
			
			$stock_tbl = TableRegistry::get("erp_stock");
			$check_stock = $stock_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_items['material_id'][$key]])->hydrate(false)->toArray();		

			if(!empty($check_stock))
			{			
				$query = $stock_tbl->query();
				$query->update()
					->set(['quantity' => $check_stock[0]["quantity"] + intval($material_items['quantity_reurn'][$key])])
					->where(['project_id' => $project_id,'material_id'=>$material_items['material_id'][$key]])
					->execute();
			}
			else{
				$stock_row = $stock_tbl->newEntity();
				$stock_data["project_id"] = $project_id;
				$stock_data["material_id"] = $material_items['material_id'][$key];
				$stock_data["quantity"] = $material_items['quantity_reurn'][$key];			
				$stock_row = $stock_tbl->patchEntity($stock_row,$stock_data);
				$stock_tbl->save($stock_row);
			}
		}

		/* Inser record in RBN Audit */
		$erp_inventory_rbn = TableRegistry::get('erp_inventory_rbn');
		$erp_audit_rbn = TableRegistry::get('erp_audit_rbn');
		$erp_audit_rbn_detail = TableRegistry::get('erp_audit_rbn_detail');
		
		$rbn_row = $erp_inventory_rbn->get($rbn_id);
		$rbn_row = $rbn_row->toArray();
		$audit_row = $erp_audit_rbn->newEntity($rbn_row);
		if($erp_audit_rbn->save($audit_row))
		{
			$audit_id = $audit_row->audit_id;
			$rbn_detail_rows = $erp_inventory_rbn_detail->find()->where(["rbn_id"=>$rbn_id])->hydrate(false)->toArray();
			
			if(!empty($rbn_detail_rows))
			{
				foreach($rbn_detail_rows as $retrive_row)
				{
					$retrive_row["audit_id"] = $audit_id;
					$audit_detail_row = $erp_audit_rbn_detail->newEntity($retrive_row);
					$erp_audit_rbn_detail->save($audit_detail_row);
				}
			}
		}
		/* Inser record in RBN Audit */
	}
	
	public function add_inventory_sst_detail($material_items,$sst_id)
	{
		$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail'); 
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['sst_id'] =  $sst_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['intimated_by'] =  $material_items['intimated_by'][$key];
			/* $save_data['transfer_reason'] =  $material_items['transfer_reason'][$key];			 */
						
			$entity_data = $erp_inventory_sst_detail->newEntity();			
			$material_data=$erp_inventory_sst_detail->patchEntity($entity_data,$save_data);
			$erp_inventory_sst_detail->save($material_data);						
		}		
	}
	
	public function edit_inventory_sst_detail($material_items,$sst_id)
	{
		$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail'); 
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['sst_id'] =  $sst_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['intimated_by'] =  $material_items['intimated_by'][$key];
			/* $save_data['transfer_reason'] =  $material_items['transfer_reason'][$key];			 */
						
			$entity_data = $erp_inventory_sst_detail->get($material_items['detail_id'][$key]);			
			$material_data=$erp_inventory_sst_detail->patchEntity($entity_data,$save_data);
			$erp_inventory_sst_detail->save($material_data);						
		}		
	}
	public function get_materialcode_bymaterialid($material_id)
	{
		$erp_material = TableRegistry::get('erp_material'); 
		$material_data = $erp_material->find()->where(['material_id'=>$material_id]);
		$cnt = $material_data->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$material_data = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		
		$material_code = '';
		foreach($material_data as $retrive_data)
		{
			$material_code = $retrive_data['material_code'];
			$unit_id = $retrive_data['unit_id'];			
		}
		// $material_category = $this->material_category();
		// $material_code = $material_category[$material_code]['material_code'];
		$material_code = 'YNEC/VD/'.$this->get_vendor_group_code($material_code );
		
		
		return $material_code;
	}
	public function get_material_title($material_id)
	{
	
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$cnt = $results->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		
		$material_title = "";
		foreach($results as $retrive_data)
		{
			$material_title = $retrive_data['material_title'];					
		}
		
		return $material_title;
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
	public function get_pr_materiallist($pr_id)
	{
		$projects = array();
		$erp_inventory_pr_material = TableRegistry::get('erp_inventory_pr_material');
		$results = $erp_inventory_pr_material->find()->where(array('pr_id'=>$pr_id,"approved"=>0));
		$contant = '';
		$i = 0;
		foreach($results as $retrive_data)
		{
		$contant .= '<tr>
			<td>'.$this->get_materialcode_bymaterialid($retrive_data['material_id']).'</td>
			<td>'.$this->get_material_title($retrive_data['material_id']).'
			<input type="hidden" name="material[material_id][]" 
			value="'.$retrive_data['material_id'].'" id="material_id_'.$i.'"/></td>
			<td><input type="hidden" name="brand_id[]" value="'.$retrive_data['brand_id'].'">'.$this->get_brand_name($retrive_data['brand_id']).'
			<input type="hidden" name="material[brand_id][]" value="'.$retrive_data['brand_id'].'">
			</td>
			<td> <input type="text" name="material[quantity][]" value="'.$retrive_data['quantity'].'" id="quantity_'.$i.'"/></td>
			 <input type="text" name="material[quantity][]" value="'.$retrive_data['quantity'].'" id="quantity_'.$i.'"/>
			<td>'.$this->get_category_title($this->get_material_unit_id($retrive_data['material_id'])).'</td>
			<td><input type="text" name="material[unit_rate][]" class="unit_rate" value="" data-id="'.$i.'" id="unit_rate_'.$i.'" style="width:80px" /></td>
			<td><input type="text" name="material[discount][]" value="0" class="tx_count" id="dc_'.$i.'" data-id="'.$i.'" style="width:55px"></td>
			<td><input type="text" name="material[transportation][]" value="0"  class="tx_count" id="tr_'.$i.'" data-id="'.$i.'" style="width:55px"></td>
			<td><input type="text" name="material[exice][]" class="tx_count" value="0" id="ex_'.$i.'"  data-id="'.$i.'" style="width:55px"></td>
			<td><input type="text" name="material[other_tax][]" class="tx_count" value="0" id="other_tax_'.$i.'"  data-id="'.$i.'" style="width:55px"></td>
			<td><input type="text" name="material[amount][]" value="0" id="amount_'.$i.'" style="width:90px" /></td>
			<td><input type="text" name="material[single_amount][]" value="0" id="single_amount_'.$i.'" style="width:90px"/></td>
			<td><input type="text" name="material[delivery_date][]" value="'.$retrive_data['delivery_date']->format('Y-m-d').'" id="delivery_date_'.$i.'" style="width:90px"/></td>
			<td><a href="#" class="btn btn-danger del_parent">Delete</a>
			<input type="hidden" name="pr_mid[]" value="'.$retrive_data['pr_material_id'].'">
			</td>
		</tr>';
		$i++;
		}
		return $contant;
	}
	
	public function getPrMateriallistIngrn($pr_id)
	{
		$projects = array();
		$erp_inventory_pr_material = TableRegistry::get('erp_inventory_pr_material');
		/* $results = $erp_inventory_pr_material->find()->where(array('pr_id'=>$pr_id,'approved'=>0)); */
		$results = $erp_inventory_pr_material->find()->where(array('pr_id'=>$pr_id,'approved_for_grnwithoutpo'=>1));
		$contant = '';
		$i = 0;
		foreach($results as $retrive_data)
		{
		$contant .= '<tr id="cpy_row">
			<td>'.$this->get_materialcode_bymaterialid($retrive_data['material_id']).'</td>
			<td>'.$this->get_material_title($retrive_data['material_id']).'	<input type="hidden" name="material[material_id][]" readonly = "true" value="'.$retrive_data['material_id'].'" id="material_id_'.$i.'"/></td>
			<td>'.$this->get_brand_name($retrive_data['brand_id']).'
				<input type="hidden" name="material[brand_id][]" value="'.$retrive_data['brand_id'].'" id="brand_id_'.$i.'"/>
			</td>
			<td> <input type="text" name="material[quantity][]" value="'.($retrive_data["quantity"] - $retrive_data["used_qty"]).'" id="quantity_'.$i.'" class="validate[required,max['.($retrive_data["quantity"] - $retrive_data["used_qty"]).'],min[1]]" /></td>
			<td><input type="text" style="padding-left:0;padding-right:0;min-width:53px;" name="material[actual_qty][]" value="" data-id="'.$i.'" id="actual_qty_'.$i.'" class="actualy_qty validate[required]" /></td>
			<td><input type="text" name="material[difference_qty][]" readonly = "true" value="" id="difference_qty_'.$i.'"/></td>
			<td>'.$this->get_category_title($this->get_material_unit_id($retrive_data['material_id'])).'
			<input type="hidden" name="pr_mid[]" value="'.$retrive_data['pr_material_id'].'">
			</td>
			<td>
				<a href="javascript:void(0)" class="btn btn-danger del_item" title="Delete">Delete</a>
			</td>
		</tr>';
		/* removed quantity and readonly <input type="text" name="material[quantity][]" readonly = "true" value="'.$retrive_data['quantity'].'" id="quantity_'.$i.'"/>*/
		/* <td><input type="text" name="material[remarks][]" value="" id="remarks_'.$i.'"/></td>	 */
		$i++;
		}
		return $contant;
	}
	
	public function get_editpo_materiallist($pr_id,$poid)
	{
		
		$projects = array();
		$erp_inventory_pr_material = TableRegistry::get('erp_inventory_pr_material');
		$results = $erp_inventory_pr_material->find()->where(array('pr_id'=>$pr_id,"approved"=>0));
		$contant = '';
		$i = 0;
		foreach($results as $retrive_data)
		{
		$contant .= '<tr>
			<td>'.$this->get_materialcode_bymaterialid($retrive_data['material_id']).'</td>
			<td>'.$this->get_material_title($retrive_data['material_id']).'
			<input type="hidden" name="material[material_id][]" 
			value="'.$retrive_data['material_id'].'" id="material_id_'.$i.'"/></td>
			<td><input type="hidden" name="brand_id[]" value="'.$retrive_data['brand_id'].'">'.$this->get_brand_name($retrive_data['brand_id']).'
			<input type="hidden" name="material[brand_id][]" value="'.$retrive_data['brand_id'].'">
			</td>
			<td> <input type="text" name="material[quantity][]" value="'.$retrive_data['quantity'].'" id="quantity_'.$i.'"/></td>
			 <input type="text" name="material[quantity][]" value="'.$retrive_data['quantity'].'" id="quantity_'.$i.'"/>
			<td>'.$this->get_category_title($this->get_material_unit_id($retrive_data['material_id'])).'</td>
			<td><input type="text" name="material[unit_rate][]" class="unit_rate" value="'.$this->get_material_unit_price($retrive_data['material_id'],$poid).'" data-id="'.$i.'" id="unit_rate_'.$i.'"/></td>
			<td><input type="text" name="material[amount][]" value="" id="amount_'.$i.'"/></td>
			<td><input type="text" name="material[delivery_date][]" value="'.$retrive_data['delivery_date']->format('Y-m-d').'" id="delivery_date_'.$i.'"/></td>
			<td><a href="#" class="btn btn-success">Edit</a></td>
			<td><a href="#" class="btn btn-danger del_parent">Delete</a>
			<input type="hidden" name="pr_mid[]" value="'.$retrive_data['pr_material_id'].'">
			</td>
		</tr>';
		$i++;
		}
		return $contant;
	}
	
	public function get_material_unit_price($mid,$poid)
	{
		/* NOT WORKING   
		$tbl = TableRegistry::get("erp_inventory_po_detail");
		$data = $tbl->find()->where(["po_id"=>$poid,"material_id"=>$mid])->hydrate(false)->toArray();
		return $data["unit_price"]; */
	}
	
	public function get_stock_id($project_id,$material_id)
	{	
		$erp_stock = TableRegistry::get('erp_stock'); 
		
		$exists = $erp_stock->exists(['project_id'=>$project_id,'material_id'=>$material_id]);
		$res_array = array();
		if($exists)
		{
			$product_data = $erp_stock->find()->where(['project_id'=>$project_id,'material_id'=>$material_id]);
			foreach($product_data as $retrive_data)
			{
				$res_array['quantity'] = $retrive_data['quantity'];
				$res_array['project_id'] = $retrive_data['project_id'];
				$res_array['stock_id'] = $retrive_data['stock_id'];
			}
		
		}
		else
		{			
			$warehouse_data =  $erp_stock->newEntity();
				$warehouse_post_data['material_id']=$material_id;
				$warehouse_post_data['project_id']=$project_id;
				$warehouse_post_data['quantity']=0;			
				$warehouse_data= $erp_stock->patchEntity($warehouse_data,$warehouse_post_data);
				$erp_stock->save($warehouse_data);
				$res_array['stock_id'] = $warehouse_data->stock_id;
			
		}		
		
		return $res_array['stock_id'];
	}
	public function get_product_qty($project_id,$material_id)
	{
		
		$erp_stock = TableRegistry::get('erp_stock'); 
		
		$exists = $erp_stock->exists(['project_id'=>$project_id,'material_id'=>$material_id]);
		$res_array = array();
		if($exists)
		{
			$product_data = $erp_stock->find()->where(['project_id'=>$project_id,'material_id'=>$material_id]);
			foreach($product_data as $retrive_data)
		{
			$res_array['quantity'] = $retrive_data['quantity'];
			$res_array['project_id'] = $retrive_data['project_id'];
		}
		}
		else
			$res_array['quantity'] = 0;
		
		return $res_array['quantity'];
	}
	
	public function stock_add($project_id,$material_id,$quantity)
	{
		/* $erp_stock = TableRegistry::get('erp_stock');
		$id = $this->get_stock_id($project_id,$material_id);
		$product_data = $erp_stock->get($id);
		$new_data['material_id'] = $material_id;
		$update_product_qty = $this->get_product_qty($project_id,$material_id) + $quantity;		
		$new_data['quantity'] = $update_product_qty;
		$product_data = $erp_stock->patchEntity($product_data,$new_data);
		$erp_stock->save($product_data); */
		
		$history_tbl = TableRegistry::get("erp_stock_history");
		$check = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"]);
		$count = $check->count();
		$data = $check->hydrate(false)->toArray();		
		if($count == 1)
		{ 
			$query = $history_tbl->query();
			$query->update()->set(["quantity"=> ($data[0]["quantity"] + $quantity)])->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->execute();
		}else
		{	
			$row = $history_tbl->newEntity();
			$insert["date"] = date("Y-m-d");
			$insert["project_id"] = $project_id;
			$insert["material_id"] = $material_id;
			$insert["quantity"] = $quantity;
			$insert["type"] = "os";
			$row = $history_tbl->patchEntity($row,$insert);
			$history_tbl->save($row);
		}
	}
	
	public function deduct_stock($project_id,$material_id,$quantity)
	{
		$erp_stock = TableRegistry::get('erp_stock');
		$id = $this->get_stock_id($project_id,$material_id);
		$product_data = $erp_stock->get($id);
		$new_data['material_id'] = $material_id;
		$update_product_qty = $this->get_product_qty($project_id,$material_id) - $quantity;		
		$new_data['quantity'] = $update_product_qty;
		$product_data = $erp_stock->patchEntity($product_data,$new_data);
		$erp_stock->save($product_data);
	}
	
	public function designation_list()
	{
		$erp_user_role = TableRegistry::get('erp_user_role');
		
		$role_list = $erp_user_role->find()->where(['status'=>1])->hydrate(false)->toArray();
		//$designation[] = array('role'=>'sr-billingengineer','code'=>'IM','title'=>'Sr.Billing Engineer');
		
		return $role_list;
	}
	public function designation_list_old()
	{
		$designation[] = array('role'=>'erphead','code'=>'EH','title'=>'ERP Head');
		$designation[] = array('role'=>'erpmanager','code'=>'EM','title'=>'ERP Manager');
		$designation[] = array('role'=>'erpoperator','code'=>'EO','title'=>'ERP Operator');
		$designation[] = array('role'=>'ceo','code'=>'CEO','title'=>'CEO');
		$designation[] = array('role'=>'md','code'=>'MD','title'=>'MD');
		$designation[] = array('role'=>'projectdirector','code'=>'PD','title'=>'Project Director');
		$designation[] = array('role'=>'purchasehead','code'=>'PH','title'=>'Purchase - Head');
		$designation[] = array('role'=>'purchasemanager','code'=>'PM','title'=>'Purchase Manager');
		//$designation[] = array('role'=>'financehead','code'=>'IM','title'=>'Finance Head');
		$designation[] = array('role'=>'financemanager','code'=>'IM','title'=>'Finance Manager');
		$designation[] = array('role'=>'accounthead','code'=>'ACH','title'=>'Accounts - Head');
		$designation[] = array('role'=>'senioraccountant','code'=>'SA','title'=>'Sr. Accountant');
		$designation[] = array('role'=>'hrmanager','code'=>'HM','title'=>'HR Manager');
		// $designation[] = array('role'=>'hrhead','code'=>'HH','title'=>'HR Head');
		$designation[] = array('role'=>'contractadmin','code'=>'CA','title'=>'Contract Admin');
		$designation[] = array('role'=>'constructionmanager','code'=>'CM','title'=>'Construction Manager');
		$designation[] = array('role'=>'materialmanager','code'=>'MM','title'=>'Material Manager');
		$designation[] = array('role'=>'billingengineer','code'=>'BE','title'=>'Asst. Project Coordinator');
		$designation[] = array('role'=>'projectcoordinator','code'=>'PCO','title'=>'Project Co-ordinator');
		$designation[] = array('role'=>'siteaccountant','code'=>'STA','title'=>'Site Accountant');
		$designation[] = array('role'=>'asset-inventoryhead','code'=>'STA','title'=>'Asset & Inventory-Head');
		
		//$designation[] = array('role'=>'accountant','code'=>'AC','title'=>'Accountant');
		// $designation[] = array('role'=>'humanresource','code'=>'HR','title'=>'HR Manager');
		//$designation[] = array('role'=>'inventorystaff','code'=>'IM','title'=>'Inventory Manager');
		$designation[] = array('role'=>'pmm','code'=>'IM','title'=>'P & M Manager');
		$designation[] = array('role'=>'assistantpmm','code'=>'IM','title'=>'Asst. P & M Manager');
		$designation[] = array('role'=>'deputymanagerelectric','code'=>'IM','title'=>'Deputy Manager - Ele.');
		$designation[] = array('role'=>'newbillingengineer','code'=>'IM','title'=>'Billing Engineer');
		$designation[] = array('role'=>'sr-billingengineer','code'=>'IM','title'=>'Sr.Billing Engineer');
		
		return $designation;
	}
	
	public function module_list()
	{
		$designation[] = array('value'=>'user','title'=>'User');
		$designation[] = array('value'=>'project','title'=>'Project');
		$designation[] = array('value'=>'purches','title'=>'Purchase');
		$designation[] = array('value'=>'account','title'=>'Account');
		$designation[] = array('value'=>'humanresource','title'=>'Human Resource');
		// $designation[] = array('value'=>'contractadmin','title'=>'Contract Admin');
		$designation[] = array('value'=>'planning','title'=>'Planning');
		$designation[] = array('value'=>'billing','title'=>'Billing');
		$designation[] = array('value'=>'asset','title'=>'Asset');
		$designation[] = array('value'=>'inventory','title'=>'Inventory');
		
		return $designation;
	}
	public function get_designation($role)
	{
		$designations = $this->designation_list();
		foreach($designations as $key => $value)
		{
			if($key == $role)
			{
				return $value;
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
	public function rolewise_vendor_group($role)
	{
		if($role != 'deputymanagerelectric')
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
		}else{
			// $vendor_group[6] = array('id'=>'6','code'=>'EC','title'=>'Electric');
			// $vendor_group[7] = array('id'=>'7','code'=>'EL','title'=>'Electronic');
			// $vendor_group[10] = array('id'=>'10','code'=>'HV','title'=>'HVAC');
			// $vendor_group[15] = array('id'=>'15','code'=>'OT','title'=>'Others');
			$erp_vendor_groups = TableRegistry::get("erp_vendor_groups");
			$groups = $erp_vendor_groups->find()->where(['id IN'=>['6','7','10','15']]);
			$vendor_group = array();
			foreach($groups as $group)
			{
				$vendor_group[$group->id] = array('id'=>$group->id,'code'=>$group->code,'title'=>$group->title);
			}
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
		if(isset($vendor_group[$id])){
			return $vendor_group[$id]['title'];
		}
		else{
			return '-';
		}
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
		$assets_group = $this->asset_group();
		return $assets_group[$id]['title'];
	}
	public function get_asset_group_code($id)
	{
		$assets_group = $this->asset_group();
		return $assets_group[$id]['code'];
	}
	
	public function get_asset_by_fix_group($project_id){
		$ast_tbl = TableRegistry::get("erp_assets");
		$data = $ast_tbl->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->where(["asset_group IN"=>[1,2,3,4],"deployed_to"=>$project_id])->toArray();
		return $data;
	}
	
	public function get_asset_by_project($project_id){
		$ast_tbl = TableRegistry::get("erp_assets");
		$data = $ast_tbl->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->where(["deployed_to"=>$project_id])->toArray();
		return $data;
	}
	
	public function get_asset_project($asset_id)
	{
		$erp_assets = TableRegistry::get('erp_assets'); 
		$result = $erp_assets->find()->where(['asset_id'=>$asset_id]);
		$res_array['deployed_to'] = "";
		foreach($result as $retrive_data)
		{
			$res_array['deployed_to'] = $retrive_data['deployed_to'];			
		}	
		return $res_array['deployed_to'];
	}
	public function get_employee_project($employee_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee'); 
		$result = $erp_employee->find()->where(['employee_id'=>$employee_id]); */
		
		$erp_employee = TableRegistry::get('erp_users'); 
		$result = $erp_employee->find()->where(['user_id'=>$employee_id]);
		$res_array['employee_at'] = "";
		foreach($result as $retrive_data)
		{
			$res_array['employee_at'] = $retrive_data['employee_at'];			
		}	
		return $res_array['employee_at'];
	}
	public function update_employeeproject($employee_id,$project_id)
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		$erp_employee = TableRegistry::get('erp_users'); 
		$employee_data = $erp_employee->get($employee_id);
		$post_data['employee_at']=$project_id;
		$employee_data = $erp_employee->patchEntity($employee_data,$post_data);
		$erp_employee->save($employee_data);
	}
	
	public function getlast_employeeid()
	{
		$conn = ConnectionManager::get('default');
		/* $result = $conn->execute('select max(employee_id) from  erp_employee');		 */
		/* $result = $conn->execute('select max(user_id) from  erp_users');	
		
		$count = 0;
		foreach($result as $retrive_data)
		{ $count=$retrive_data[0]; }
		*/
		
		$result = $conn->execute('SELECT Auto_increment FROM information_schema.tables WHERE table_name="erp_users"');		
		$result = $result->fetch("assoc");
		$count = $result["Auto_increment"];
		return $count;	
	}
	
	public function get_last_emp_no()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('SELECT employee_no from erp_users where employee_no != "" order by user_id desc limit 1');
		$result = $result->fetch("assoc");
		if(!empty($result))
		{
			$auto_fld = $result["employee_no"];
			$split = explode("/",$auto_fld);	
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$last_number = (int) $last_id;
			$new_no = $last_number + 1;
		}else{
			$new_no = 1;
		}
		return $new_no;
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
	public function get_projectname($project_id)
	{	
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);	
		$result_arr = array();
		$result_arr['project_name'] = "";
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_name'] = $retrive_data['project_name'];			
		}
		return $result_arr['project_name'];
	}
	
	public function update_assetproject($asset_id,$project_id)
	{
		$erp_asset = TableRegistry::get('erp_assets'); 
		$asset_data = $erp_asset->get($asset_id);
		$post_data['deployed_to']=$project_id;
		$asset_data = $erp_asset->patchEntity($asset_data,$post_data);
		$erp_asset->save($asset_data);
	}
	public function update_soldassetproject($asset_id,$sold_quantity)
	{
		$erp_asset = TableRegistry::get('erp_assets'); 
		$asset_data = $erp_asset->get($asset_id);
		$post_data['quantity']= $asset_data['quantity'] - $sold_quantity;
		$asset_data = $erp_asset->patchEntity($asset_data,$post_data);
		$erp_asset->save($asset_data);
	}
	public function update_theftassetproject($asset_id,$theft_quantity)
	{
		$erp_asset = TableRegistry::get('erp_assets'); 
		$asset_data = $erp_asset->get($asset_id);
		$post_data['quantity']= $asset_data['quantity'] - $theft_quantity;
		$asset_data = $erp_asset->patchEntity($asset_data,$post_data);
		$erp_asset->save($asset_data);
	}
	public function get_asset_title($asset_id){
		$erp_asset = TableRegistry::get('erp_assets'); 
		$result = $erp_asset->get($asset_id);		
		return $result['asset_name'];
	}
	public function payment_method()
	{
		$payments[1] = array('id'=>'1','title'=>'Cash');
		$payments[2] = array('id'=>'2','title'=>'Cheque');
		return $payments;
	}
	public function get_brandname_by_po_material($pr_id,$material_id)
	{
		$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		$brand_id = $mt_tbl->find()->where(["pr_id"=>$pr_id,"material_id"=>$material_id])->select("brand_id")->hydrate(false)->toArray();
		
		if(!empty($brand_id))
		{
			return $this->get_brand_name($brand_id[0]["brand_id"]);
		}else{
			return "-";
		}
		
	}
	public function getlast_prepare_grn()
	{	
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select max(grn_id) from  erp_inventory_grn');		
		$count = 0;
		foreach($result as $retrive_data)
		{ $count=$retrive_data[0]; }
		return $count;		
	}
	
	public function get_opening_stock($project_id,$material_id) /* NO USE*/
	{
		$os_tbl = TableRegistry::get("erp_project_opening_stock");
		$data = $os_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id])->hydrate(false)->toArray();
		if(!empty($data))
		{
			$stock["date"] = $data[0]["created_date"]->format("Y-m-d");
			$stock["quantity"] = $data[0]["quantity"];
			return $stock;
		}else{
			$stock["date"] = "";
			$stock["quantity"] = "";
			return $stock;
		}
		
	}
	
	public function export_to_csv($filename="export.csv",$rows = array())
	{
		
		if(empty($rows))
		{
			return false;
		}
		
		$fp = fopen(TMP .$filename, 'w');
		foreach ($rows as $fields) {
		   fputcsv($fp, $fields);
		}
		
		fclose($fp);
		ob_clean();
		$file= TMP .$filename;//file location
		
		$mime = 'text/plain';
		header('Content-Type:application/force-download');
		header('Pragma: public');       // required
		header('Expires: 0');           // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		// header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		
		readfile($file);		
		exit;
	}
	
	public function get_projects()
	{		
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select * from  erp_projects where actual_amount = 0');			
		return $result;
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
			
			default :
				return $old_stock + $new_stock;
		}
	}
	public function get_material_item_code_bymaterialid($material_id)
	{
		$erp_material = TableRegistry::get('erp_material'); 
		$material_data = $erp_material->find()->where(['material_id'=>$material_id]);
		$material_code = 0;
		$material_item_code = "-";
		foreach($material_data as $retrive_data)
		{
			$material_item_code = $retrive_data['material_item_code'];
				
		}		
		return $material_item_code;
		
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
		$material_desc = " - ";
		foreach($results as $retrive_data)
		{
			$material_desc = $retrive_data['desciption'];					
		}
		return $material_desc;
	}
	
	public function get_items_units($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
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
	public function get_opening_stock_data($material_items,$project_id)
	{
		$erp_project_opening_stock = TableRegistry::get('erp_stock_history'); 
		
		$key = 0;
		//debug($material_items);die;
		foreach($material_items['material_id'] as $key => $data)
		{
			$old_data = $erp_project_opening_stock->find()->where(["project_id"=>$project_id,'material_id'=>$material_items['material_id'][$key],'type'=>'os'])->hydrate(false)->toArray();	
			
			if(!empty($old_data))
			{
				foreach($old_data as $save_data_1)
				{					
					$save_data = $erp_project_opening_stock->get($save_data_1['stock_id']);
					
							//$save_data['material_id'] =  $material_items['material_id'][$key];
					$save_data->quantity =  $material_items['quantity'][$key];
					// $save_data->max_quantity =  $material_items['max_quantity'][$key];
					// $save_data->min_quantity =  $material_items['min_quantity'][$key];
					$save_data->note =  $material_items['note'][$key];	
						//debug($save_data);die;
					$erp_project_opening_stock->save($save_data);
					$key++;
				}
			}
			else
			{
						$save_data = array();
						$save_data['created_date']=date('Y-m-d H:i:s');			
						$save_data['created_by']=$this->request->session()->read('user_id');
						$save_data['project_id'] =  $project_id;			
						$save_data['material_id'] =  $material_items['material_id'][$key];
						$save_data['quantity'] =  $material_items['quantity'][$key];
						// $save_data['max_quantity'] =  $material_items['max_quantity'][$key];
						// $save_data['min_quantity'] =  $material_items['min_quantity'][$key];
						$save_data['type'] =  "os";
						$save_data['note'] =  $material_items['note'][$key];	
							
						$entity_data = $erp_project_opening_stock->newEntity();			
						$material_data=$erp_project_opening_stock->patchEntity($entity_data,$save_data);
						$erp_project_opening_stock->save($material_data);
					
			}			
		}	
		
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
	
	public function generate_auto_id($project_id,$tbl,$desc_fld,$auto_incr_fld)
	{
		$tbl= TableRegistry::get($tbl);
		// $data = $tbl->find("all")->where(["project_id"=>$project_id])->limit(1)->hydrate(false)->toArray()->max('po_no');
		$query = $tbl->find();
		$data = $query
			->select(["{$auto_incr_fld}" => $query->func()->max($auto_incr_fld)])->where(["project_id"=>$project_id]);
		$result = $data->first();
	
		$po_no = $result->$auto_incr_fld;
		if(!empty($po_no))
		{
			// $auto_fld = $data[0]["{$auto_incr_fld}"];
			$auto_fld = $po_no;
			$split = explode("/",$auto_fld);	
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$last_number = (int) $last_id;
			$new_no = $last_number + 1;		
			/* $new_id = str_ireplace("{$last_number}","{$new_no}",$last_id); */
		}else{
			$new_no = 1;
		}
		return $new_no;
	}
	
	public function generate_asset_auto_id($group_id,$tbl,$desc_fld,$auto_incr_fld)
	{
		$tbl= TableRegistry::get($tbl);
		// $data = $tbl->find("all")->where(["asset_group"=>$group_id])->order(["{$desc_fld}"=>"DESC"])->limit(1)->hydrate(false)->toArray();
		
		$data = $tbl->find("all")->order(["{$desc_fld}"=>"DESC"])->limit(1)->hydrate(false)->toArray();
		if(!empty($data))
		{
			$auto_fld = $data[0]["{$auto_incr_fld}"];
			$split = explode("/",$auto_fld);	
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$last_number = (int) $last_id;
			$new_no = $last_number + 1;		
			/* $new_id = str_ireplace("{$last_number}","{$new_no}",$last_id); */
		}else{
			$new_no = 1;
		}
		return $new_no;
	}
	
	public function generate_auto_id_grn($project_id,$tbl,$desc_fld,$auto_incr_fld,$type)
	{
		$tbl= TableRegistry::get($tbl);
		// $data = $tbl->find("all")->where(["project_id"=>$project_id,"{$auto_incr_fld} LIKE"=>"%{$type}%"])->order(["{$desc_fld}"=>"DESC"])->limit(1)->hydrate(false)->toArray();
		
		$data = $tbl->find("all")->where(["project_id"=>$project_id])->order(["{$desc_fld}"=>"DESC"])->limit(1)->hydrate(false)->toArray();
			
		if(!empty($data))
		{
			$auto_fld = $data[0]["{$auto_incr_fld}"];
			$split = explode("/",$auto_fld);	
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$last_number = (int) $last_id;
			$new_no = $last_number + 1;		
			/* $new_id = str_ireplace("{$last_number}","{$new_no}",$last_id); */
		}else{
			$new_no = 1;
		}
		return $new_no;
	}
	
	public function generate_auto_id_grn_not_grnlp($project_id,$tbl,$desc_fld,$auto_incr_fld,$type)
	{
		$tbl= TableRegistry::get($tbl);
		$data = $tbl->find("all")->where(["project_id"=>$project_id,"{$auto_incr_fld} NOT LIKE"=>"%{$type}%"])->order(["{$desc_fld}"=>"DESC"])->limit(1)->hydrate(false)->toArray();
		
		if(!empty($data))
		{
			$auto_fld = $data[0]["{$auto_incr_fld}"];
			$split = explode("/",$auto_fld);	
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$last_number = (int) $last_id;
			$new_no = $last_number + 1;		
			/* $new_id = str_ireplace("{$last_number}","{$new_no}",$last_id); */
		}else{
			$new_no = 1;
		}
		return $new_no;
	}
	
	public function generate_auto_id_local_po($project_id,$tbl,$desc_fld,$auto_incr_fld,$type)
	{
		$tbl= TableRegistry::get($tbl);
		$data = $tbl->find("all")->where(["project_id"=>$project_id,"{$auto_incr_fld} LIKE"=>"%{$type}%"])->order(["{$desc_fld}"=>"DESC"])->limit(1)->hydrate(false)->toArray();
		// debug($data);die;
		// $data = $tbl->find("all")->where(["project_id"=>$project_id])->limit(1)->hydrate(false)->toArray()->max('po_no');
		// $query = $tbl->find();
		// $data = $query
			// ->select(["{$auto_incr_fld}" => $query->func()->max($auto_incr_fld)])->where(["project_id"=>$project_id]);
		// $result = $data->first();
	
		
		if(!empty($data))
		{
			// $po_no = $data->$auto_incr_fld;
			$po_no = $data[0][$auto_incr_fld];
			// $auto_fld = $data[0]["{$auto_incr_fld}"];
			$auto_fld = $po_no;
			$split = explode("/",$auto_fld);	
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$last_number = (int) $last_id;
			$new_no = $last_number + 1;		
			/* $new_id = str_ireplace("{$last_number}","{$new_no}",$last_id); */
		}else{
			$new_no = 1;
		}
		return $new_no;
	}
	
	public function generate_auto_id_material($group_id)
	{
		$tbl= TableRegistry::get("erp_material");
		$desc_fld = "material_id";
		$data = $tbl->find("all")->where(["material_code"=>$group_id])->order(["{$desc_fld}"=>"DESC"])->limit(1)->hydrate(false)->toArray();
		
		if(!empty($data))
		{
			$auto_fld = $data[0]["material_item_code"];
			$split = explode("/",$auto_fld);	
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$last_number = (int) $last_id;
			$new_no = $last_number + 1;		
			/* $new_id = str_ireplace("{$last_number}","{$new_no}",$last_id); */
		}else{
			$new_no = 1;
		}
		return $new_no;
	}
	
	public function generate_auto_id_material_temp($group_id)
	{
		$tbl= TableRegistry::get("erp_material_temp");
		$desc_fld = "material_id";
		$data = $tbl->find("all")->where(["material_code"=>$group_id])->hydrate(false)->toArray();
		
		if(empty($data))
		{
			$new_no = 1;
			return $new_no;
		}
		
		foreach($data as $code)
		{
			$number = explode("/",$code["material_item_code"]);
			$cd[] = (int) $number[3];
		}
		$mx = max($cd);
		$new_no = $mx + 1;
		return $new_no;
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
		$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type NOT IN"=>array("os","sst_to")])->hydrate(false)->toArray();
		}
		else
		{
			$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id,"type NOT IN"=>array("os","sst_to")])->hydrate(false)->toArray();
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
	
	public function get_po_vendor_id($po_id)
	{
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$vendor_id = $po_tbl->find()->where(["po_id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
		$vendor_id = $vendor_id[0]["vendor_userid"];
		$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
		$vendor_emails = array();
		if(!empty($vendor))
		{
			$vendor_email = explode(",",$vendor[0]["email_id"]);
			if(!empty($vendor_email))
			{
				foreach($vendor_email as $em)
				{
					$vendor_emails[] = $em;
				}
			}
		}
		
		return $vendor_emails;
	}
	
	public function get_assetpo_vendor_id($po_id)
	{
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$po_tbl = TableRegistry::get("erp_asset_po");
		$vendor_id = $po_tbl->find()->where(["po_id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
		$vendor_id = $vendor_id[0]["vendor_userid"];
		$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
		$vendor_emails = array();
		if(!empty($vendor))
		{
			$vendor_email = explode(",",$vendor[0]["email_id"]);
			if(!empty($vendor_email))
			{
				foreach($vendor_email as $em)
				{
					$vendor_emails[] = $em;
				}
			}
		}
		
		return $vendor_emails;
	}
	
	public function get_loi_vendor_id($loi_id)
	{
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$erp_letter_content = TableRegistry::get("erp_letter_content");
		$vendor_id = $erp_letter_content->find()->where(["id"=>(int)$loi_id])->select(["vendor_userid"])->hydrate(false)->toArray();
		$vendor_id = $vendor_id[0]["vendor_userid"];
		$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
		$vendor_emails = array();
		if(!empty($vendor))
		{
			$vendor_email = explode(",",$vendor[0]["email_id"]);
			if(!empty($vendor_email))
			{
				foreach($vendor_email as $em)
				{
					$vendor_emails[] = $em;
				}
			}
		}
		
		return $vendor_emails;
	}
	
	public function get_email_of_pd_pm_cm_by_project($project_id,$po_id)
	{
		// $prj_tbl = TableRegistry::get("erp_projects");
		// $user_id = $prj_tbl->find()->where(["project_id"=>$project_id])->select("project_director")->hydrate(false)->toArray();
		$pd_email = "";
		$user_tbl = TableRegistry::get("erp_users");
		// if(!empty($user_id))
		// {
			// $user_id = $user_id[0]["project_director"];			
			// $result = $user_tbl->find()->where(["user_id"=>(int)$user_id])->select("email_id")->hydrate(false)->toArray();
			// if(!empty($result))
			// {
				// $pd_email = $result[0]["email_id"];
			// }
		// }
		
		$asg_tbl = TableRegistry::get("erp_projects_assign");
		$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
		$cm_email = array();
		if(!empty($project_users))
		{  
			$user_tbl = TableRegistry::get("erp_users");
			$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"projectdirector","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids))
			{
				foreach($emailids as $email)
				{
					$cm_email[] = $email["email_id"];
					$cm_email[] = $email["second_email"];
				}
			}
			
			$emailids1 = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"constructionmanager","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids1))
			{
				foreach($emailids1 as $email)
				{
					$cm_email[] = $email["email_id"];
					$cm_email[] = $email["second_email"];
				}
			}
		}	
		
		$cm_emails = $user_tbl->find()->where(["role"=>"purchasemanager","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($cm_emails))
		{
			foreach($cm_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}	
		
		$md_emails = $user_tbl->find()->where(["role"=>"md","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($md_emails))
		{
			foreach($md_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}		
		
		/* $cm_email[] = $pd_email; */
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$vendor_id = $po_tbl->find()->where(["po_id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
		$vendor_id = $vendor_id[0]["vendor_userid"];
		$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
		if(!empty($vendor))
		{
			$vendor_email = explode(",",$vendor[0]["email_id"]);
			if(!empty($vendor_email))
			{
				foreach($vendor_email as $em)
				{
					$cm_email[] = $em;
				}
			}
		}
		
		return $cm_email;
	}
	
	public function get_mail_list_by_project($project_id,$po_id,$status,$type)
	{
		$pd_email = "";
		$user_tbl = TableRegistry::get("erp_users");
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		$type="'[".$type."]'";
		$conn = ConnectionManager::get('default');
		$cm_email = array();
		$result = $conn->execute('SELECT role,Alloted FROM `erp_accessrights` WHERE JSON_CONTAINS((JSON_UNQUOTE(`notificationlist`)), '.$type.')')->fetchAll("assoc");
		
		foreach($result as $data){
			
			if($data['Alloted']==1){
				if($status==1 || $status==2){
					
					$asg_tbl = TableRegistry::get("erp_projects_assign");
					$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
					
					if($data['role'] != 'deputymanagerelectric' && $data['role'] != 'projectdirector' && $data['role'] != 'srengineermep')
					{
						if(!empty($project_users))
						{  
							$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>$data['role'],"status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
							if(!empty($emailids))
							{
								foreach($emailids as $email)
								{
									$cm_email[] = $email["email_id"];
									$cm_email[] = $email["second_email"];
								}
							}
						}
					}
					
				}
				if($data['role'] == 'deputymanagerelectric')
				{			
					if($status==2){
						$asg_tbl = TableRegistry::get("erp_projects_assign");
						$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
						
						if(!empty($project_users))
						{  
							$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"deputymanagerelectric","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
							if(!empty($emailids))
							{
								foreach($emailids as $email)
								{
									$cm_email[] = $email["email_id"];
									$cm_email[] = $email["second_email"];
								}
							}
						}
						
					}
				}
				if($data['role'] == 'projectdirector')
				{			
					if($status==2){
						$asg_tbl = TableRegistry::get("erp_projects_assign");
						$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
						
						if(!empty($project_users))
						{  
							$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"projectdirector","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
							if(!empty($emailids))
							{
								foreach($emailids as $email)
								{
									$cm_email[] = $email["email_id"];
									$cm_email[] = $email["second_email"];
								}
							}
						}
						
					}
				}
				if($data['role'] == 'srengineermep')
				{			
					if($status==2){
						$asg_tbl = TableRegistry::get("erp_projects_assign");
						$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
						
						if(!empty($project_users))
						{  
							$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"srengineermep","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
							if(!empty($emailids))
							{
								foreach($emailids as $email)
								{
									$cm_email[] = $email["email_id"];
									$cm_email[] = $email["second_email"];
								}
							}
						}
						
					}
				}
			}
			if($data['Alloted']==0){
				$cm_emails = $user_tbl->find()->where(["role"=>$data['role'],"status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
				if(!empty($cm_emails))
				{
					foreach($cm_emails as $cemail)
					{
						if($cemail["email_id"] != "")
						{
							$cm_email[] = $cemail["email_id"];
							$cm_email[] = $cemail["second_email"];
						}
					}
				}
			}
			
		}
		
		/* $cm_email[] = $pd_email; */
		if($status!=0){
			$vendor_tbl = TableRegistry::get("erp_vendor");
			$po_tbl = TableRegistry::get("erp_inventory_po");
			$vendor_id = $po_tbl->find()->where(["po_id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
			$vendor_id = $vendor_id[0]["vendor_userid"];
			$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
			if(!empty($vendor))
			{
				$vendor_email = explode(",",$vendor[0]["email_id"]);
				if(!empty($vendor_email))
				{
					foreach($vendor_email as $em)
					{
						$cm_email[] = $em;
					}
				}
			}
		}
		$cm_email = array_unique($cm_email); /*remove duplicate email ids */		
		$cm_email = array_filter($cm_email, function($value) { return $value !== ''; });
		$cm_email = array_filter($cm_email, function($value) { return $value !== NULL; });
		
		return $cm_email;
	}
	
	
	public function get_mail_list_by_project_wo($project_id,$status,$type)
	{
		
		$pd_email = "";
		$user_tbl = TableRegistry::get("erp_users");
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		$type="'[".$type."]'";
		$conn = ConnectionManager::get('default');
		$cm_email = array();
		$result = $conn->execute('SELECT role,Alloted FROM `erp_accessrights` WHERE JSON_CONTAINS((JSON_UNQUOTE(`notificationlist`)), '.$type.')')->fetchAll("assoc");
		foreach($result as $data){
			if($data['Alloted']==1){
				if($status==1 || $status==2 || $status==0){
					
					$asg_tbl = TableRegistry::get("erp_projects_assign");
					$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
					
					if(!empty($project_users))
					{
						if($data['role'] != 'deputymanagerelectric')
						{
							$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>$data['role'],"status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
							if(!empty($emailids))
							{
								foreach($emailids as $email)
								{
									$cm_email[] = $email["email_id"];
									$cm_email[] = $email["second_email"];
								}
							}
						}
					}
					
				}
				if($data['role'] == 'deputymanagerelectric')
				{
					if($status==2){
						$asg_tbl = TableRegistry::get("erp_projects_assign");
						$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
						
						if(!empty($project_users))
						{  
							$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"deputymanagerelectric","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
							if(!empty($emailids))
							{
								foreach($emailids as $email)
								{
									$cm_email[] = $email["email_id"];
									$cm_email[] = $email["second_email"];
								}
							}
						}
					}
				}
			}
			else{
				$cm_emails = $user_tbl->find()->where(["role"=>$data['role'],"status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
				if(!empty($cm_emails))
				{
					foreach($cm_emails as $cemail)
					{
						if($cemail["email_id"] != "")
						{
							$cm_email[] = $cemail["email_id"];
							$cm_email[] = $cemail["second_email"];
						}
					}
				}
			}
		
		}
		$cm_email = array_unique($cm_email);
		return $cm_email;
	}
	public function get_mail_list_by_payslip($type)
	{
		$emails = array();
		$users_table = TableRegistry::get("erp_users");
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		$type="'[".$type."]'";
		$conn = ConnectionManager::get('default');
		$cm_email = array();
		$result = $conn->execute('SELECT role,Alloted FROM `erp_accessrights` WHERE JSON_CONTAINS((JSON_UNQUOTE(`notificationlist`)), '.$type.')')->fetchAll("assoc");
		foreach($result as $data){
	
			$hrheads = $users_table->find()->where(['role'=>$data['role'],'employee_no'=>''])->select(["user_id","email_id","second_email"])->hydrate(false)->toArray();
			
			if(!empty($hrheads))
			{
				foreach($hrheads as $hr)
				{
					$emails[] = $hr["email_id"];
					$emails[] = $hr["second_email"];
				}
			}
		}
		$emails = array_unique($emails);
		$emails = array_filter($emails, function($value) { return $value !== ''; });
		$emails = array_filter($emails, function($value) { return $value !== NULL; });
		// var_dump($emails);
		// die;
		return $emails;
	}
	
	public function get_mail_list_by_payment($type,$status)
	{
		$role = array();
		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		$type="'[".$type."]'";
		$conn = ConnectionManager::get('default');
		
		$result = $conn->execute('SELECT role,Alloted FROM `erp_accessrights` WHERE JSON_CONTAINS((JSON_UNQUOTE(`notificationlist`)), '.$type.')')->fetchAll("assoc");
		foreach($result as $data){
			if($status=="Alloted"){
				if($data['Alloted']==1){
					$role[]=$data['role'];
				}
			}
			else{
				if($data['Alloted']==0){
					$role[]=$data['role'];
				}
			}
			
		}
		return $role;
	}
	public function get_manualpo_vendor_id($po_id)
	{
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$po_tbl = TableRegistry::get("erp_manual_po");
		$vendor_id = $po_tbl->find()->where(["po_id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
		$vendor_id = $vendor_id[0]["vendor_userid"];
		$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
		$vendor_emails = array();
		if(!empty($vendor))
		{
			$vendor_email = explode(",",$vendor[0]["email_id"]);
			if(!empty($vendor_email))
			{
				foreach($vendor_email as $em)
				{
					$vendor_emails[] = $em;
				}
			}
			
		}
		return $vendor_emails;
	}

	public function get_email_of_pd_pm_cm_by_project_of_manualpo($project_id,$po_id)
	{
		// $prj_tbl = TableRegistry::get("erp_projects");
		// $user_id = $prj_tbl->find()->where(["project_id"=>$project_id])->select("project_director")->hydrate(false)->toArray();
		$pd_email = "";
		$user_tbl = TableRegistry::get("erp_users");
		// if(!empty($user_id))
		// {
			// $user_id = $user_id[0]["project_director"];			
			// $result = $user_tbl->find()->where(["user_id"=>(int)$user_id])->select("email_id")->hydrate(false)->toArray();
			// if(!empty($result))
			// {
				// $pd_email = $result[0]["email_id"];
			// }
		// }
		
		$asg_tbl = TableRegistry::get("erp_projects_assign");
		$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
		$cm_email = array();
		if(!empty($project_users))
		{  
			$user_tbl = TableRegistry::get("erp_users");
			$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"projectdirector","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids))
			{
				foreach($emailids as $email)
				{
					$cm_email[] = $email["email_id"];
					$cm_email[] = $email["second_email"];
				}
			}
			
			$emailids1 = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"constructionmanager","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids1))
			{
				foreach($emailids1 as $email)
				{
					$cm_email[] = $email["email_id"];
					$cm_email[] = $email["second_email"];
				}
			}
		}	
		
		$cm_emails = $user_tbl->find()->where(["role"=>"purchasemanager","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($cm_emails))
		{
			foreach($cm_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}	
		
		$md_emails = $user_tbl->find()->where(["role"=>"md","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($md_emails))
		{
			foreach($md_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}		
		
		/* $cm_email[] = $pd_email; */
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$po_tbl = TableRegistry::get("erp_manual_po");
		$vendor_id = $po_tbl->find()->where(["po_id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
		$vendor_id = $vendor_id[0]["vendor_userid"];
		$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
		if(!empty($vendor))
		{
			$vendor_email = explode(",",$vendor[0]["email_id"]);
			if(!empty($vendor_email))
			{
				foreach($vendor_email as $em)
				{
					$cm_email[] = $em;
				}
			}
			
		}
		
		return $cm_email;
	}
	
	public function get_email_of_mm_by_project($project_id)
	{	
		$asg_tbl = TableRegistry::get("erp_projects_assign");
		$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
		$mmcm = array();
		if(!empty($project_users))
		{  
			$user_tbl = TableRegistry::get("erp_users");
			$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"status !="=>0,"OR"=>[["role"=>"materialmanager"]]])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids))
			{
				foreach($emailids as $email)
				{
					$mmcm[] = $email["email_id"];
					$mmcm[] = $email["second_email"];
				}
			}
		}
		return $mmcm;
	}
	
	public function get_email_of_pd_pm_by_project($project_id,$po_id)
	{
		// $prj_tbl = TableRegistry::get("erp_projects");
		// $user_id = $prj_tbl->find()->where(["project_id"=>$project_id])->select("project_director")->hydrate(false)->toArray();
		$pd_email = "";
		$user_tbl = TableRegistry::get("erp_users");
		// if(!empty($user_id))
		// {
			// $user_id = $user_id[0]["project_director"];			
			// $result = $user_tbl->find()->where(["user_id"=>(int)$user_id])->select("email_id")->hydrate(false)->toArray();
			// if(!empty($result))
			// {
				// $pd_email = $result[0]["email_id"];
			// }
		// }
		
		$asg_tbl = TableRegistry::get("erp_projects_assign");
		$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
		$cm_email = array();
		if(!empty($project_users))
		{  
			$user_tbl = TableRegistry::get("erp_users");
			$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"projectdirector"])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids))
			{
				foreach($emailids as $email)
				{
					$cm_email[] = $email["email_id"];
					$cm_email[] = $email["second_email"];
				}
			}
		}	
		
		$cm_emails = $user_tbl->find()->where(["role"=>"purchasemanager"])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($cm_emails))
		{
			foreach($cm_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}	
		
		$md_emails = $user_tbl->find()->where(["role"=>"md"])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($md_emails))
		{
			foreach($md_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}		
		
		/* $cm_email[] = $pd_email; */
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$vendor_id = $po_tbl->find()->where(["po_id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
		$vendor_id = $vendor_id[0]["vendor_userid"];
		$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
		if(!empty($vendor))
		{
			$vendor_email = explode(",",$vendor[0]["email_id"]);
			if(!empty($vendor_email))
			{
				foreach($vendor_email as $em)
				{
					$cm_email[] = $em;
				}
			}
		}
		
		return $cm_email;
	}
	
	
	
	public function get_email_of_cm_mm_by_project($project_id)
	{	
		$asg_tbl = TableRegistry::get("erp_projects_assign");
		$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
		$mmcm = array();
		if(!empty($project_users))
		{  
			$user_tbl = TableRegistry::get("erp_users");
			$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"OR"=>[["role"=>"constructionmanager"],["role"=>"materialmanager"]]])->select("email_id")->hydrate(false)->toArray();
			if(!empty($emailids))
			{
				foreach($emailids as $email)
				{
					$mmcm[] = $email["email_id"];
				}
			}
		}
		return $mmcm;
	}
	
	public function get_po_no_by_id($po_id)
	{
		$tbl = TableRegistry::get("erp_inventory_po");
		$row = $tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		$po_no = "NA";
		if(!empty($row))
		{
			$po_no = $row[0]["po_no"];
		}
		return $po_no;
	}
	
	public function get_assetpo_no_by_id($po_id)
	{
		$tbl = TableRegistry::get("erp_asset_po");
		$row = $tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		$po_no = "NA";
		if(!empty($row))
		{
			$po_no = $row[0]["po_no"];
		}
		return $po_no;
	}
	
	public function get_loi_no_by_id($loi_id)
	{
		$erp_letter_content = TableRegistry::get("erp_letter_content");
		$row = $erp_letter_content->find()->where(["id"=>$loi_id])->hydrate(false)->toArray();
		$po_no = "NA";
		if(!empty($row))
		{
			$loi_no = $row[0]["loi_no"];
		}
		return $loi_no;
	}
	
	public function get_po_date_by_id($po_id)
	{
		$tbl = TableRegistry::get("erp_inventory_po");
		$row = $tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		$po_date = "NA";
		if(!empty($row))
		{
			$po_date = $row[0]["po_date"];
		}
		return $po_date;
	}
	
	public function get_po_project_by_id($po_id)
	{
		$tbl = TableRegistry::get("erp_inventory_po");
		$row = $tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		$po_project = "NA";
		if(!empty($row))
		{
			$po_project = $row[0]["project_id"];
		}
		return $this->get_projectname($po_project);
	}
	
	public function get_po_party_by_id($po_id)
	{
		$tbl = TableRegistry::get("erp_inventory_po");
		$row = $tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		$po_party = "NA";
		if(!empty($row))
		{
			$po_party = $row[0]["vendor_userid"];
		}
		return $this->get_vendor_name($po_party);
	}
	
	public function mail_po_withrateammend($to,$view_po,$po_no,$po_project,$po_date)
	{
		// $po_no = $this->get_po_no_by_id($view_po);
		$po_project = $this->get_projectname($po_project);
		$po_party = $this->get_po_party_by_id($view_po);
		// $po_date = $this->get_po_date_by_id($view_po);
		if(date('Y-m-d',strtotime($po_date)) > date('Y-m-d',strtotime('01-07-2017')))
		{
			$url = Router::url('/', true)."inventory/printporecord2/{$view_po}/mail";
		}
		else
		{
			$url = Router::url('/', true)."inventory/printporecord/{$view_po}/mail";
		}
		//$url = "http://erp.yashnandeng.com/inventory/printapprovedporecord/{$view_po}";
		/* $url = "http://localhost/svn/cakephp/cake_yashnanderp/inventory/printporecord/{$view_po}"; */
		$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Purchase Order (P.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Purchase Order (P.O.) [<strong style='background-color:yellow;color:red;'>P.O. No: {$po_no} (Revised)</strong>] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p style='background-color:yellow;color:red;'><strong>PO NO :</strong> {$po_no} (Revised).</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$po_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$po_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		 // $email_to = "mansi@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to 
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		// $file = fopen($url,'rb');  


		// $contents = file_get_contents($url); // read the remote file
		// touch('temp.pdf'); // create a local EMPTY copy
		// file_put_contents('temp.pdf', $contents);


		// $data = fread($file,filesize("temp.pdf"));  
		// // $data = fread($file,19189);  
		// fclose($file);  
		// $semi_rand = md5(time());  
		// $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		// $headers .= "\nMIME-Version: 1.0\n" .  
		// 			"Content-Type: multipart/mixed;\n" .  
		// 			" boundary=\"{$mime_boundary}\"";
		// $email_message_old = "";					
		// $email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
		// 				"--{$mime_boundary}\n" .  
		// 				"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
		// 			   "Content-Transfer-Encoding: 7bit\n\n" .  
		// $email_message_old .= "\n\n";  
		// // $data = chunk_split(base64_encode($data));   
		// $data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		// $dir_to_save = WWW_ROOT;
		// // echo $dir_to_save;die;
		// $pdf_name=$this->generate_autoid('po-').time().'.pdf';
		// file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		// $email_message_old .= "--{$mime_boundary}\n" .  
		// 				  "Content-Type: {$fileatt_type};\n" .  
		// 				  " name=\"{$fileatt_name}\"\n" .  
		// 				  //"Content-Disposition: attachment;\n" .  
		// 				  //" filename=\"{$fileatt_name}\"\n" .  
		// 				  "Content-Transfer-Encoding: base64\n\n" .  
		// 				 $data .= "\n\n" .  
		// 				  "--{$mime_boundary}--\n"; 
					 		
							$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						//   ->attachments(['Purchase Order' => $dir_to_save.$pdf_name])
						  ->send($email_message);
							if($email)
							{
								// unlink($dir_to_save.$pdf_name);
							}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}

	public function mail_po_withoutrateammend($to,$view_po,$po_no,$po_project,$po_date)
	{
		
		// $po_no = $this->get_po_no_by_id($view_po);
		$po_project = $this->get_projectname($po_project);
		$po_party = $this->get_po_party_by_id($view_po);
		// $po_date = $this->get_po_date_by_id($view_po);
		if(date('Y-m-d',strtotime($po_date)) > date('Y-m-d',strtotime('01-07-2017')))
		{
			$url = Router::url('/', true)."inventory/printporecordnorate2/{$view_po}";
		}
		else
		{
			$url = Router::url('/', true)."inventory/printporecordnorate/{$view_po}";
		}
		
		// $url = "http://192.168.1.31/ashvin/cakephp/yashnanderp/inventory/printporecordnorate/{$view_po}";
		$fileatt = "test.pdf"; // Path to the file                  
		// $fileatt = $url; // Path to the file                  
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Purchase Order (P.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Purchase Order (P.O.) [<strong style='background-color:yellow;color:red;'>P.O. No: {$po_no} (Revise)</strong>] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p style='background-color:yellow;color:red;'><strong>PO NO :</strong> {$po_no} (Revise).</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$po_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$po_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		 // $email_to = "mansi@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to  
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		// $file = fopen($url,'rb');  


		// $contents = file_get_contents($url); // read the remote file
		// touch('temp.pdf'); // create a local EMPTY copy
		// file_put_contents('temp.pdf', $contents);


		// $data = fread($file,filesize("temp.pdf"));  
		// // $data = fread($file,19189);  
		// fclose($file);  
		// $semi_rand = md5(time());  
		// $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		// $headers .= "\nMIME-Version: 1.0\n" .  
		// 			"Content-Type: multipart/mixed;\n" .  
		// 			" boundary=\"{$mime_boundary}\"";
		// $email_message_old = "";
		// $email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
		// 				"--{$mime_boundary}\n" .  
		// 				"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
		// 			   "Content-Transfer-Encoding: 7bit\n\n" .  
		// $email_message_old .= "\n\n";  
		// // $data = chunk_split(base64_encode($data));   
		// // $data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		// $data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		// $dir_to_save = WWW_ROOT;
		// // echo $dir_to_save;die;
		// $pdf_name=$this->generate_autoid('po-').time().'.pdf';
		// file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		// $email_message_old .= "--{$mime_boundary}\n" .  
		// 				  "Content-Type: {$fileatt_type};\n" .  
		// 				  " name=\"{$fileatt_name}\"\n" .  
		// 				  //"Content-Disposition: attachment;\n" .  
		// 				  //" filename=\"{$fileatt_name}\"\n" .  
		// 				  "Content-Transfer-Encoding: base64\n\n" .  
		// 				 $data .= "\n\n" .  
		// 				  "--{$mime_boundary}--\n";  
		$email = new Email('default');
		   $email->from("das@gmail.com")
		   ->emailFormat('html')
		  ->to($email_to)
		  ->subject($email_subject)
		//   ->attachments(['Purchase Order' => $dir_to_save.$pdf_name])
		  ->send($email_message);
			if($email)
			{
				// unlink($dir_to_save.$pdf_name);
			}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}

	public function mail_po_withrate($to,$view_po,$po_no,$po_project,$po_date)
	{
		// $po_no = $this->get_po_no_by_id($view_po);
		$po_project = $this->get_projectname($po_project);
		$po_party = $this->get_po_party_by_id($view_po);
		// $po_date = $this->get_po_date_by_id($view_po);
		// if(date('Y-m-d',strtotime($po_date)) > date('Y-m-d',strtotime('01-07-2017')))
		// {
			$url = Router::url('/', true)."inventory/mailporecord2/{$view_po}";
		// }
		// else
		// {
		// 	$url = Router::url('/', true)."inventory/printporecord/{$view_po}/mail";
		// }
		$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Purchase Order (P.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Purchase Order (P.O.) [<strong>P.O. No: {$po_no}</strong>] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>PO NO :</strong> {$po_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$po_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$po_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		 // $email_to = "mansi@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to 
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		// $file = fopen($url,'rb');  


		// $contents = file_get_contents($url); // read the remote file
		// touch('temp.pdf'); // create a local EMPTY copy
		// file_put_contents('temp.pdf', $contents);


		// $data = fread($file,filesize("temp.pdf"));  
		// // $data = fread($file,19189);  
		// fclose($file);  
		// $semi_rand = md5(time());  
		// $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		// $headers .= "\nMIME-Version: 1.0\n" .  
		// 			"Content-Type: multipart/mixed;\n" .  
		// 			" boundary=\"{$mime_boundary}\"";
		// $email_message_old = "";					
		// $email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
		// 				"--{$mime_boundary}\n" .  
		// 				"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
		// 			   "Content-Transfer-Encoding: 7bit\n\n" .  
		// $email_message_old .= "\n\n";  
		// // $data = chunk_split(base64_encode($data));   
		// $data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		// $dir_to_save = WWW_ROOT;
		// // echo $dir_to_save;die;
		// $pdf_name=$this->generate_autoid('po-').time().'.pdf';
		// file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		// $email_message_old .= "--{$mime_boundary}\n" .  
		// 				  "Content-Type: {$fileatt_type};\n" .  
		// 				  " name=\"{$fileatt_name}\"\n" .  
		// 				  //"Content-Disposition: attachment;\n" .  
		// 				  //" filename=\"{$fileatt_name}\"\n" .  
		// 				  "Content-Transfer-Encoding: base64\n\n" .  
		// 				 $data .= "\n\n" .  
		// 				  "--{$mime_boundary}--\n"; 
					 		
							$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						//   ->attachments(['Purchase Order' => $dir_to_save.$pdf_name])
						  ->send($email_message);
							if($email)
							{
								// unlink($dir_to_save.$pdf_name);
							}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}

	// public function testMail($to,$view_po,$po_no,$po_project,$po_date){
	// 	$po_project = $this->get_projectname($po_project);
	// 	$po_party = $this->get_po_party_by_id($view_po);
	// 	if(date('Y-m-d',strtotime($po_date)) > date('Y-m-d',strtotime('01-07-2017')))
	// 	{
	// 		$url = Router::url('/', true)."inventory/mailporecord2/{$view_po}";
	// 	}
		
	// 	$dir_to_save = WWW_ROOT;		 		
	// 	$email = new Email('default');
	// 	$email->from("das@gmail.com")
	// 	->emailFormat('html')
	// 	->to($email_to)
	// 	->subject($email_subject)
	// 	->attachments(['Purchase Order' => $dir_to_save.'payment-21010673801609924218.pdf'])
	// 	->send($email_message);
	// 	// if($email)
	// 	// {
	// 	// 	unlink($dir_to_save.$pdf_name);
	// 	// }
	// }
	
	
	public function mail_po_withoutrate($to,$view_po,$po_no,$po_project,$po_date)
	{
		
		// $po_no = $this->get_po_no_by_id($view_po);
		$po_project = $this->get_projectname($po_project);
		$po_party = $this->get_po_party_by_id($view_po);
		// $po_date = $this->get_po_date_by_id($view_po);
		if(date('Y-m-d',strtotime($po_date)) > date('Y-m-d',strtotime('01-07-2017')))
		{
			$url = Router::url('/', true)."inventory/printporecordnorate2/{$view_po}";
		}
		else
		{
			$url = Router::url('/', true)."inventory/printporecordnorate/{$view_po}";
		}
		
		// $url = "http://192.168.1.31/ashvin/cakephp/yashnanderp/inventory/printporecordnorate/{$view_po}";
		$fileatt = "test.pdf"; // Path to the file                  
		// $fileatt = $url; // Path to the file                  
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Purchase Order (P.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Purchase Order (P.O.) [<strong>P.O. No: {$po_no}</strong>] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>PO NO :</strong> {$po_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$po_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$po_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		 // $email_to = "mansi@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to  
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		// $file = fopen($url,'rb');  


		// $contents = file_get_contents($url); // read the remote file
		// touch('temp.pdf'); // create a local EMPTY copy
		// file_put_contents('temp.pdf', $contents);


		// $data = fread($file,filesize("temp.pdf"));  
		// // $data = fread($file,19189);  
		// fclose($file);  
		// $semi_rand = md5(time());  
		// $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		// $headers .= "\nMIME-Version: 1.0\n" .  
		// 			"Content-Type: multipart/mixed;\n" .  
		// 			" boundary=\"{$mime_boundary}\"";
		// $email_message_old = "";
		// $email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
		// 				"--{$mime_boundary}\n" .  
		// 				"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
		// 			   "Content-Transfer-Encoding: 7bit\n\n" .  
		// $email_message_old .= "\n\n";  
		// // $data = chunk_split(base64_encode($data));   
		// // $data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		// $data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		// $dir_to_save = WWW_ROOT;
		// // echo $dir_to_save;die;
		// $pdf_name=$this->generate_autoid('po-').time().'.pdf';
		// file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		// $email_message_old .= "--{$mime_boundary}\n" .  
		// 				  "Content-Type: {$fileatt_type};\n" .  
		// 				  " name=\"{$fileatt_name}\"\n" .  
		// 				  //"Content-Disposition: attachment;\n" .  
		// 				  //" filename=\"{$fileatt_name}\"\n" .  
		// 				  "Content-Transfer-Encoding: base64\n\n" .  
		// 				 $data .= "\n\n" .  
		// 				  "--{$mime_boundary}--\n";  
		$email = new Email('default');
		   $email->from("das@gmail.com")
		   ->emailFormat('html')
		  ->to($email_to)
		  ->subject($email_subject)
		  ->attachments(['Purchase Order' => $dir_to_save.$pdf_name])
		  ->send($email_message);
			if($email)
			{
				// unlink($dir_to_save.$pdf_name);
			}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}

	public function cancel_po_mail($to,$po_no,$project_name,$party_name)
	{
		//$po_no = $this->get_po_no_by_id($po_id);
		 //$url = "http://erp.yashnandeng.com/inventory/printporecord/{$view_po}/mail";
		//$url = "http://erp.yashnandeng.com/inventory/printapprovedporecord/{$view_po}";
		/* $url = "http://localhost/svn/cakephp/cake_yashnanderp/inventory/printporecord/{$view_po}"; */
		//$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		//$fileatt_type = "application/pdf"; // File Type  
		//$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Cancel - Purchase Order (P.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please consider previously sent Purchase Order (P.O.) [<strong>P.O. No:</strong> {$po_no}] cancelled.</p><br>";
		$email_message .= "<p>Sorry for the inconvenience.</p><br>";
		$email_message .= "<p><strong>PO NO :</strong> {$po_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$project_name}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$party_name}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		//// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		//$file = fopen($url,'rb');  


		//$contents = file_get_contents($url); // read the remote file
		//touch('temp.pdf'); // create a local EMPTY copy
		//file_put_contents('temp.pdf', $contents);


		//$data = fread($file,filesize("temp.pdf"));  
		//// $data = fread($file,19189);  
		//fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		//// $data = chunk_split(base64_encode($data));   
		//$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  //"Content-Type: {$fileatt_type};\n" .  
						  //" name=\"{$fileatt_name}\"\n" .  
						  ////"Content-Disposition: attachment;\n" .  
						  ////" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 //$data .= "\n\n" .  
						  "--{$mime_boundary}--\n";  
						 // $email = new Email('default');
						  // $email->from("das@gmail.com")
						 // ->to($to)
						 // ->subject($email_subject)
						 // ->send($email_message);

			$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->send($email_message);			 
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function cancel_wo_mail($to,$wo_no,$project_name,$party_name)
	{
		//$po_no = $this->get_po_no_by_id($po_id);
		 //$url = "http://erp.yashnandeng.com/inventory/printporecord/{$view_po}/mail";
		//$url = "http://erp.yashnandeng.com/inventory/printapprovedporecord/{$view_po}";
		/* $url = "http://localhost/svn/cakephp/cake_yashnanderp/inventory/printporecord/{$view_po}"; */
		//$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		//$fileatt_type = "application/pdf"; // File Type  
		//$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Cancel - Work Order (W.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please consider previously sent Work Order (W.O.) [<strong>W.O. No:</strong> {$wo_no}] cancelled.</p><br>";
		$email_message .= "<p>Sorry for the inconvenience.</p><br>";
		$email_message .= "<p><strong>PO NO :</strong> {$wo_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$project_name}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$party_name}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		//// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		//$file = fopen($url,'rb');  


		//$contents = file_get_contents($url); // read the remote file
		//touch('temp.pdf'); // create a local EMPTY copy
		//file_put_contents('temp.pdf', $contents);


		//$data = fread($file,filesize("temp.pdf"));  
		//// $data = fread($file,19189);  
		//fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		//// $data = chunk_split(base64_encode($data));   
		//$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  //"Content-Type: {$fileatt_type};\n" .  
						  //" name=\"{$fileatt_name}\"\n" .  
						  ////"Content-Disposition: attachment;\n" .  
						  ////" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 //$data .= "\n\n" .  
						  "--{$mime_boundary}--\n";  
						
						 $email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->send($email_message);
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function get_manualpo_no_by_id($po_id)
	{
		$tbl = TableRegistry::get("erp_manual_po");
		$row = $tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		$po_no = "NA";
		if(!empty($row))
		{
			$po_no = $row[0]["po_no"];
		}
		return $po_no;
	}
	
	public function get_manualpo_project_by_id($po_id)
	{
		$tbl = TableRegistry::get("erp_manual_po");
		$row = $tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		$po_project = "NA";
		if(!empty($row))
		{
			$po_project = $row[0]["project_id"];
		}
		return $this->get_projectname($po_project);
	}
	
	public function get_manualpo_party_by_id($po_id)
	{
		$tbl = TableRegistry::get("erp_manual_po");
		$row = $tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		$po_party = "NA";
		if(!empty($row))
		{
			$po_party = $row[0]["vendor_userid"];
		}
		return $this->get_vendor_name($po_party);
	}
	
	public function mail_manualpo_withrate($to,$view_po)
	{
		$po_no = $this->get_manualpo_no_by_id($view_po);
		$po_project = $this->get_manualpo_project_by_id($view_po);
		$po_party = $this->get_manualpo_party_by_id($view_po);
		$url = Router::url('/', true)."purchase/printmanualporecord/{$view_po}/mail";
		//$url = "http://erp.yashnandeng.com/inventory/printapprovedporecord/{$view_po}";
		// $url = "http://192.168.1.131/cake_yashnanderp/purchase/printmanualporecord/{$view_po}"; 
		$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Purchase Order (Manual) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Purchase Order (P.O.) [<strong>P.O. No:</strong> {$po_no}] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>PO NO :</strong> {$po_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$po_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$po_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to 
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$dir_to_save = WWW_ROOT;
		// echo $dir_to_save;die;
		$pdf_name=$this->generate_autoid('Manualpo-').time().'.pdf';
		file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n";
		// debug($email_to);die;
		$email = new Email('default');
	   $email->from("das@gmail.com")
	   ->emailFormat('html')
	  ->to($email_to)
	  ->subject($email_subject)
	  ->attachments(['Purchase Order' => $dir_to_save.$pdf_name])
	  ->send($email_message);
		if($email)
		{
			unlink($dir_to_save.$pdf_name);
		}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function mail_manualpo_withoutrate($to,$view_po)
	{
		$po_no = $this->get_manualpo_no_by_id($view_po);
		$po_project = $this->get_manualpo_project_by_id($view_po);
		$po_party = $this->get_manualpo_party_by_id($view_po);
		$url = Router::url('/', true)."purchase/printmanualporecordnorate/{$view_po}";
		
		// $url = "http://192.168.1.31/ashvin/cakephp/yashnanderp/purchase/printmanualporecordnorate/{$view_po}";
		$fileatt = "test.pdf"; // Path to the file                  
		// $fileatt = $url; // Path to the file                  
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Purchase Order (Manual) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Purchase Order (P.O.) [<strong>P.O. No:</strong> {$po_no}] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>PO NO :</strong> {$po_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$po_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$po_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		$email_to = $to; // Who the email is to 
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$dir_to_save = WWW_ROOT;
		// echo $dir_to_save;die;
		$pdf_name=$this->generate_autoid('po-').time().'.pdf';
		file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n"; 
		$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->attachments(['Purchase Order' => $dir_to_save.$pdf_name])
						  ->send($email_message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function cancel_manualpo_mail($to,$po_no,$project_name,$party_name)
	{
		//$po_no = $this->get_manualpo_no_by_id($po_id);
		 //$url = "http://erp.yashnandeng.com/inventory/printporecord/{$view_po}/mail";
		//$url = "http://erp.yashnandeng.com/inventory/printapprovedporecord/{$view_po}";
		/* $url = "http://localhost/svn/cakephp/cake_yashnanderp/inventory/printporecord/{$view_po}"; */
		//$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		//$fileatt_type = "application/pdf"; // File Type  
		//$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Cancel - Manual Purchase Order (P.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please consider previously sent Manual Purchase Order (P.O.) [<strong>P.O. No:</strong> {$po_no}] cancelled.</p><br>";
		$email_message .= "<p>Sorry for the inconvenience.</p><br>";
		$email_message .= "<p><strong>PO NO :</strong> {$po_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$project_name}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$party_name}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		//// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		//$file = fopen($url,'rb');  


		//$contents = file_get_contents($url); // read the remote file
		//touch('temp.pdf'); // create a local EMPTY copy
		//file_put_contents('temp.pdf', $contents);


		//$data = fread($file,filesize("temp.pdf"));  
		//// $data = fread($file,19189);  
		//fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\""; 
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		//// $data = chunk_split(base64_encode($data));   
		//$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  //"Content-Type: {$fileatt_type};\n" .  
						  //" name=\"{$fileatt_name}\"\n" .  
						  ////"Content-Disposition: attachment;\n" .  
						  ////" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 //$data .= "\n\n" .  
						  "--{$mime_boundary}--\n"; 
						  
						 // $email = new Email('default');
						  // $email->from("das@gmail.com")
						  // ->emailFormat('html')
						 // ->to('vijay.parmar@dasinfomedia.com')
						 // ->subject($email_subject)
						 // ->send($email_message);

						 $email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->send($email_message);
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function get_vendor_email($vid)
	{
		$tbl = TableRegistry::get("erp_vendor");
		$record = $tbl->find()->where(["user_id"=>intval($vid)])->select("email_id")->hydrate(false)->toArray();
		$email = "";
		if(!empty($record))
		{
			$email = $record[0]["email_id"];
		}
		return $email;
	}

	public function get_vendor_code($vid)
	{
		$tbl = TableRegistry::get("erp_vendor");
		$record = $tbl->find()->where(["user_id"=>intval($vid)])->select("email_id")->hydrate(false)->toArray();
		$email = "";
		if(!empty($record))
		{
			$vendorCode = $record[0]["vendor_id"];
		}
		return $vendorCode;
	}
	
	public function get_agency_email($a_id)
	{
		$tbl = TableRegistry::get("erp_agency");
		$record = $tbl->find()->where(["agency_id"=>$a_id])->select("email_id")->hydrate(false)->toArray();
		$email = "";
		if(!empty($record))
		{
			$email = $record[0]["email_id"];
		}
		return $email;
	}
	
	public function get_agency_name($id)
	{
		$tbl = TableRegistry::get("erp_vendor");
		$record = $tbl->find()->where(["user_id"=>$id])->select("vendor_name")->hydrate(false)->toArray();
		$name = "";
		if(!empty($record))
		{
			$agency = $record[0]["vendor_name"];
		}
		return $agency;
	}
	
	public function get_day_hours($user_id)
	{
		$pid = $this->get_employee_project($user_id);
		$hours = array();
		if($pid == 2)
		{
			$hours["full"] = 8;
		}else{
			$hours["full"] = 9;
		}		
		$hours["half"] = 4;
		return $hours;
	}
	
	public function get_monthly_paid_leave($user_id)
	{
		$category = $this->get_employee_category($user_id);
		$pl = 0 ;
		switch($category)
		{
			CASE "a":
				$pl = "NA";
			break;
			CASE "b":
				$pl = 4;
			break;
			CASE "c":
				$pl = 2;
			break;			
		}
		
		return $pl;
	}
	
	public function get_employee_category($user_id)
	{
		$tbl = TableRegistry::get("erp_users");
		$category = "";
		$cat = $tbl->find()->where(["user_id"=>$user_id])->select("category")->hydrate(false)->toArray();
		if(!empty($cat))
		{
			$category = $cat[0]["category"];
		}
		return $category;
	}
	
	
	// public function saveremainingpl($user_id,$remaining_pl)
	// {
		// $tbl = TableRegistry::get("erp_users");
		// $row = $tbl->get($user_id);
		// $row->leave_balance = $remaining_pl;
		// $tbl->save($row);
	// }
	
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
	
	public function get_monthly_total_attendace($user_id,$month,$year)
	{
		$tbl = TableRegistry::get("erp_attendance_detail");
		$data = $tbl->find()->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year])->hydrate(false)->toArray();
		unset($data[0]["id"]);
		unset($data[0]["user_id"]);
		unset($data[0]["month"]);
		unset($data[0]["year"]);
		unset($data[0]["total_present"]);
		unset($data[0]["total_absent"]);
		unset($data[0]["total_holidays"]);
		unset($data[0]["total_aa"]);
		unset($data[0]["opening_pl"]);
		unset($data[0]["new"]);
		unset($data[0]["used_pl"]);
		unset($data[0]["man_pl"]);
		unset($data[0]["remaining_pl"]);
		unset($data[0]["payable_days"]);
		unset($data[0]["approved"]);
		unset($data[0]["approved_by"]);
		unset($data[0]["approved_date"]);
		foreach($data[0] as $k=>$v)
		{
			if(is_null($v))
			{
				unset($data[0][$k]);
			}
		}
		$data = $data[0];
		$vals["A"] = 0;
		$vals["P"] = 0;
		$vals["HL"] = 0;
		$vals["H"] = 0;
		$vals["manual_A"] = 0;
		$vals["manual_P"] = 0;
		$vals["manual_H"] = 0;
		$vals["manual_HL"] = 0;
		
		$vals = array_merge($vals,array_count_values($data));
		
		return $vals;
	}
	
	public function is_corporate_emp($user_id)
	{
		$project_id = $this->get_employee_project($user_id);
		$pr_name = $this->get_projectname($project_id);
		if($pr_name == "Corporate Office")
		{
			// return true; //Set apsent on sunday for corporate emp, before it was H
			return false;
		}else{
			return false;			
		}
	}
	
	public function save_attendance_detail($user_id,$date,$action,$working_hours = null)
	{  /*DAYOFMONTH()*/
		$day = date("j",strtotime($date));
		$month = date("n",strtotime($date));
		$year = date("Y",strtotime($date));
		
		$tbl = TableRegistry::get("erp_attendance_detail");
		
		if($action == "day_in")
		{
			$count = $tbl->find()->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year])->count();
			if($count == 1)
			{
				
			}
			else
			{
				$start_date = "01-".$month."-".$year;
				$start_time = strtotime($start_date);

				$end_time = strtotime("+1 month", $start_time);
				for($i=$start_time; $i<$end_time; $i+=86400)
				{
				   // $dates[] = date('Y-m-d D', $i);
				   $day = date('j', $i);
				   $day_name = date('l', $i);
				   if($day_name == "Sunday")
				   {
					 $corpo_emp = $this->is_corporate_emp($user_id);
					 $data["day_{$day}"] = ($corpo_emp) ? "H" : "A";
				   }else{
				   $data["day_{$day}"]= "A";
				   }
				}				
				$data["user_id"] = $user_id;
				$data["month"] = $month;
				$data["year"] = $year;
				$new = $tbl->newEntity();
				$new = $tbl->patchEntity($new,$data);
				$tbl->save($new);				
			}
		}
		else if($action == "day_out")
		{	
			$count = $tbl->find()->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year])->count();
			if($count == 0)
			{
				$start_date = "01-".$month."-".$year;
				$start_time = strtotime($start_date);

				$end_time = strtotime("+1 month", $start_time);
				for($i=$start_time; $i<$end_time; $i+=86400)
				{
				   // $dates[] = date('Y-m-d D', $i);
				   $day = date('j', $i);
				   $day_name = date('l', $i);
				   if($day_name == "Sunday")
				   {
						$corpo_emp = $this->is_corporate_emp($user_id);
						$data["day_{$day}"] = ($corpo_emp) ? "H" : "A";			
				   }else{
						$data["day_{$day}"]= "A";
				   }				   
				}				
				$data["user_id"] = $user_id;
				$data["month"] = $month;
				$data["year"] = $year;
				$new = $tbl->newEntity();
				$new = $tbl->patchEntity($new,$data);
				$tbl->save($new);				
			}
			
			#############################
			
			$office_hours = $this->get_day_hours($user_id);
			$full_day = $office_hours["full"];
			$half_day = $office_hours["half"];
			$working_hours = intval(date("G",strtotime($working_hours)));
			if($working_hours >= $full_day)
			{
				$status = "P";
			}
			else if($working_hours < $full_day && $working_hours >= $half_day)
			{
				$status = "HL";
			}else
			{
				$status = "A";
			}
			
			$day = date('j', strtotime($date));
			$query = $tbl->query();
			$query->update()
				->set(["day_{$day}"=>$status])
				->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year])
				->execute();
				
			##################################################		
			
			$last_month_balance = $this->get_leave_balance($user_id,$month,$year);
			$monthly_leave = $this->get_monthly_paid_leave($user_id);
			
			// $pl_balance = $last_month_balance + $monthly_leave;
			
			$total_present = $this->get_monthly_total_attendace($user_id,$month,$year);			
			$presents = (isset($total_present["P"])) ? $total_present["P"] : 0; /* Default 0 Present for month, same for below*/
			$manual_present = (isset($total_present["manual_P"])) ? $total_present["manual_P"] : 0; /* Default 0 Present for month, same for below*/
			$presents = $presents + $manual_present;
			
			$half_days = (isset($total_present["HL"])) ? $total_present["HL"] : 0;
			$manual_half_days = (isset($total_present["manual_HL"])) ? $total_present["manual_HL"] : 0;
			$half_days = $half_days + $manual_half_days;
			
			$aa = (isset($total_present["AA"])) ? $total_present["AA"] : 0;
			$manual_aa = (isset($total_present["manual_AA"])) ? $total_present["manual_AA"] : 0;
			$aa = $aa + $manual_aa;
			
			$absents = $total_present["A"];
			$manual_absents = (isset($total_present["manual_A"])) ? $total_present["manual_A"] : 0;
			$absents = $absents + $manual_absents + ($half_days * 0.5);
			
			$holidays = isset($total_present["H"]) ? $total_present["H"] : 0;
			$manual_holidays = (isset($total_present["manual_H"])) ? $total_present["manual_H"] : 0;
			$holidays = $holidays + $manual_holidays;
			
				
			if($monthly_leave == "NA")
			{				
				$last_month_balance = 0;
				$monthly_leave = $absents;
				$used_pl = $absents;
				$remaining_pl = 0;
				$date = "{$year}-{$month}-{$day}";
				$date = date("Y-m-d",strtotime($date));
				$payable_days = date("t",strtotime($date));			
			}
			else
			{
			
				$total_present = ($presents != 0  ) ? $presents + $holidays : 0; /* Dont include holiday as present if absent whole month*/
				$total_present = $total_present + ($half_days * 0.5);
				// $total_absents = $absents + ($aa * 1.5);
				$total_absents = $absents + $aa;
				
				$pl_balance = $last_month_balance + $monthly_leave;
				
				$remaining_pl = $pl_balance - $total_absents;
				
				if($remaining_pl >= 0 )
				{
					// $payable_days = date('t'); /* Last day of month 't', Full paybale days*/
					$used_pl = $total_absents;
				}
				else{
					$remaining_pl = 0;
					// $payable_days = $total_present + $remaining_pl;
					$used_pl = $pl_balance;
				}
				$payable_days = $total_present + $used_pl;
			}
				
			$data = array();
			$data["total_present"] = $presents + ($half_days * 0.5);
			$data["total_absent"] = $absents;
			$data["total_holidays"] = $holidays;
			$data["total_aa"] = $aa;
			$data["opening_pl"] = $last_month_balance;
			$data["new"] = $monthly_leave;
			$data["used_pl"] = $used_pl;
			$data["remaining_pl"] = $remaining_pl;
			$data["payable_days"] = $payable_days;
			
			$query = $tbl->query();
			$query->update()
			->set($data)
			->where(["user_id"=>$user_id,"month"=>$month,"year"=>$year])
			->execute();
			// die;
		}
	}
	
public function set_loan_outstanding($paid_amt,$user_id,$month,$year)
{
	
		$tbl = TableRegistry::get("erp_loan");		

		$data = $tbl->find()->where(["user_id"=>$user_id,'loan_status'=> 0]);
		
		$cnt = $data->count();
		
		if($cnt >= 1)
		{

			$total_installment =(int) $paid_amt;


			$loan_ids = $data->hydrate(false)->toArray();

			$i = 0;
			
			while($total_installment > 0)
			{
				$record = $loan_ids[$i];
				
				$id = $record['loan_id'];
				
				$row = $tbl->get($id);
				
				// debug($total_installment);
				if($total_installment > $row->outstanding)
				{
					
						//var_dump($total_installment); var_dump($row->outstanding);die;
					$amount = $row->outstanding;
					$total_installment = $total_installment - $row->outstanding;

					$row->outstanding = 0;
					$row->loan_status = 1;

					if($tbl->save($row))
					{
						$loan_history = TableRegistry::get('erp_loan_pay_history');
						
						$row1['loan_id'] = $row->loan_id;
						$row1['salarey_pay_month'] = $month;
						$row1['salarey_pay_year'] = $year;
						$row1['created_by'] = $row->created_by;
						$row1['paid_amount'] = $amount;
						$row1['created_date'] = $row->created_date;
						$row1['date'] = date("Y-m-d");
				
						$new_row = $loan_history->newEntity();	
						$req_data=$loan_history->patchEntity($new_row,$row1);
						$loan_history->save($req_data);
					}

					//echo "success";die;
					
				}
				else
				{
					
					$row->outstanding = $row->outstanding - $total_installment;

					if($row->outstanding == 0)
					{
						$row->loan_status = 1;
					}

					if($tbl->save($row))
					{
						$loan_history = TableRegistry::get('erp_loan_pay_history');
						$amount = $row->installment;
						$row1['loan_id'] = $row->loan_id;
						$row1['salarey_pay_month'] = $month;
						$row1['salarey_pay_year'] = $year;
						$row1['created_by'] = $row->created_by;
						$row1['paid_amount'] = $total_installment;
						$row1['created_date'] = $row->created_date;
						$row1['date'] = date("Y-m-d");
				

						$new_row = $loan_history->newEntity();	
						$req_data=$loan_history->patchEntity($new_row,$row1);
						$loan_history->save($req_data);
					}
					$total_installment = 0;
				}
				$i++;
			}		
			
		}
	}



/*public function set_loan_outstanding($paid_amt,$user_id,$month,$year)
	{
		
		//var_dump($paid_amt);var_dump($user_id);var_dump($month);var_dump($year);die;

		$tbl = TableRegistry::get("erp_loan");	
		$data = $tbl->find()->where(["user_id"=>$user_id]);
	
		$cnt = $data->count();
				
		if($cnt >= 1)
		{
			$loan_ids = $data->hydrate(false)->toArray();

			//debug($loan_ids);die;
			foreach ($loan_ids as $loan_ids)
			{
				$loan_id = $loan_ids["loan_id"];
				
				//var_dump($loan_id);die;		
				$row = $tbl->get($loan_id);
				
				//debug($row);die;
				if($row->loan_status != 1)
				{
					$row->outstanding = $row->outstanding - $paid_amt;

					//debug($row->outstanding);die;
					$amount = $row->outstanding;
					
					if($row->outstanding == 0 || $row->outstanding < 0)
					{
						//var_dump("completeloan");die;
						$row->loan_status = 1;
						//$row->outstanding = 0;
						$tbl->save($row);
					}
					if($tbl->save($row))
					{
						//var_dump("History");die;
						$loan_history = TableRegistry::get('erp_loan_pay_history');				
						$row1['loan_id'] = $row->loan_id;
						$row1['salarey_pay_month'] = $month;
						$row1['salarey_pay_year'] = $year;
						$row1['created_by'] = $row->created_by;
						$row1['paid_amount'] = $row->installment;
						$row1['created_date'] = $row->created_date;
						$row1['date'] = date("Y-m-d");

				
						$new_row = $loan_history->newEntity();	
						$req_data=$loan_history->patchEntity($new_row,$row1);
						$loan_history->save($req_data);
					}
				}
				
			}
		}
	}*/
	
	public function reverse_loan_payment($reverse_amt,$user_id,$month,$year)
	{	
		if($month < 10)
		{
				$month = '0'.$month;
		}
		$tbl = TableRegistry::get("erp_loan");
		$history = TableRegistry::get('erp_loan_pay_history');

		$result = $tbl->find()->select($tbl)->where(["user_id"=>$user_id]);
		
		$result = $result->innerjoin(
					["erp_loan_pay_history"=>"erp_loan_pay_history"],
					["erp_loan.loan_id = erp_loan_pay_history.loan_id"])
					->where(["erp_loan_pay_history.salarey_pay_month"=>$month,"erp_loan_pay_history.salarey_pay_year"=>$year])->select($history)->hydrate(false)->toArray();
		
		
		foreach ($result as $data)
		{	
			$loan_id = $data['loan_id'];
			$row = $tbl->get($loan_id);
			$paid_amount = $data['erp_loan_pay_history']['paid_amount'];
			$row->outstanding = $row->outstanding + $paid_amount;
			$row->loan_status = 0;
			$id = $data['erp_loan_pay_history']['id'];
			$loan_history = $history->get($id); 
			if($tbl->save($row));
			{	
				$history->delete($loan_history);	
			}
		}
		
	}
	
	public function edit_expence_detail($expence_items,$total_amount,$total_words)
	{
		$erp_expence_detail = TableRegistry::get('erp_expence_detail'); 
		foreach($expence_items['id'] as $key => $data)
		{
			$req_data = $erp_expence_detail->get($expence_items['id'][$key]);			
			$req_data['expence_description'] =  $expence_items['description'][$key];
			$req_data['expence_amount'] =  $expence_items['amount'][$key];
			$req_data['expence_total'] =  $total_amount;
			$req_data['expence_toatl_word'] =  $total_words;
			
			//$updated_data=$erp_expence_detail->patchEntity($req_data,$save_data);
			$erp_expence_detail->save($req_data);						
		}		
	}	
		
	public function delete_stock_entry($type,$type_id,$project_id,$material_id)
	{
		$stock_tbl = TableRegistry::get("erp_stock_history");
		$stock_tbl_deleted = TableRegistry::get("erp_stock_history_deleted");
		if(is_numeric($material_id))
		{
			$row = $stock_tbl->find()->where(["type"=>$type,"type_id"=>$type_id,"project_id"=>$project_id,"material_id"=>$material_id])->hydrate(false)->toArray();
		}
		else
		{
			$row = $stock_tbl->find()->where(["type"=>$type,"type_id"=>$type_id,"project_id"=>$project_id,"material_name"=>$material_id])->hydrate(false)->toArray();
		}
		//$qty = 0; // added for if $row empty then select 0 default
		if(!empty($row))
		{
			$qty = $row[0]["quantity"];
			$rbn_qty = $row[0]["return_back"];
			$transferred = $row[0]["transferred"];
			$damaged_qty = $row[0]["damaged_qty"];
			$stock_in = $row[0]["stock_in"];
			$stock_out = $row[0]["stock_out"];
			
			$del_row = $stock_tbl_deleted->newEntity();
			$del_row = $stock_tbl_deleted->patchEntity($del_row,$row[0]);

			if($stock_tbl_deleted->save($del_row))
			{
				if(is_numeric($material_id))
				{
				$query = $stock_tbl->query();
				$deleted = $query->delete()->where(["type"=>$type,"type_id"=>$type_id,"project_id"=>$project_id,"material_id"=>$material_id])->execute();
				}
				else
				{
				$query = $stock_tbl->query();
				$deleted = $query->delete()->where(["type"=>$type,"type_id"=>$type_id,"project_id"=>$project_id,"material_name"=>$material_id])->execute();
				}
			}	
		}
		
		$stock_tbl = TableRegistry::get("erp_stock");
		if(is_numeric($material_id))
		{
			$check_stock = $stock_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id])->hydrate(false)->toArray();
		}
		else
		{
			$check_stock = $stock_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id])->hydrate(false)->toArray();
		}
		
		if(!empty($check_stock))
		{			
			switch($type)
			{
				CASE "rbn":
					$final_qty = $check_stock[0]["quantity"] - intval($qty);
				break;
				CASE "grn":
					$final_qty = $check_stock[0]["quantity"] - intval($qty);
				break;
				CASE "is":
					$final_qty = $check_stock[0]["quantity"] + intval($qty);
				break;
				CASE "mrn":
					$final_qty = $check_stock[0]["quantity"] + intval($qty);
				break;
				/* SST will be differant function
				CASE "sst_from":
					$final_qty = $check_stock[0]["quantity"] - intval($qty);
				break;
				CASE "sst_to":
					$final_qty = $check_stock[0]["quantity"] + intval($qty);
				break; */
				
			}
			if(is_numeric($material_id))
			{
				$query = $stock_tbl->query();
				$query->update()
					->set(['quantity' => $final_qty])
					->where(['project_id' => $project_id,'material_id'=>$material_id])
					->execute();
			}
			else
			{
				$query = $stock_tbl->query();
				$query->update()
					->set(['quantity' => $final_qty])
					->where(['project_id' => $project_id,'material_name'=>$material_id])
					->execute();
			}
		}
	}
	
	public function get_agency_branch($id)
	{
		$tbl = TableRegistry::get("erp_agency");
		$record = $tbl->find()->where(["id"=>$id])->select("branch_name")->hydrate(false)->toArray();
		$branch_name = "";
		if(!empty($record))
		{
			$branch_name = $record[0]["branch_name"];
		}
		return $branch_name;
	}
	
	public function get_agency_accountno($id)
	{
		$tbl = TableRegistry::get("erp_agency");
		$record = $tbl->find()->where(["id"=>$id])->select("ac_no")->hydrate(false)->toArray();
		$ac_no = "";
		if(!empty($record))
		{
			$ac_no = $record[0]["ac_no"];
		}
		return $ac_no;
	}
	
	public function get_agency_ifs_code($id)
	{
		$tbl = TableRegistry::get("erp_agency");
		$record = $tbl->find()->where(["id"=>$id])->select("ifsc_code")->hydrate(false)->toArray();
		$ifsc_code = "";
		if(!empty($record))
		{
			$ifsc_code = $record[0]["ifsc_code"];
		}
		return $ifsc_code;
	}
	
	public function get_agency_bank($id)
	{
		$tbl = TableRegistry::get("erp_agency");
		$record = $tbl->find()->where(["id"=>$id])->select("bank_name")->hydrate(false)->toArray();
		$bank_name = "";
		if(!empty($record))
		{
			$bank_name = $record[0]["bank_name"];
		}
		return $bank_name;
	}
	
	public function inisprojectdetail($project_id)
	{		
	
		$asset_list = $this->get_asset_by_fix_group($project_id);
		$asset_data = "";
		if(!empty($asset_list))
		{
			/* $asset_list = array_map(function($val){return "asst_{$val}";},$asset_list); */
			foreach ($asset_list as $key => $val) {
				
				$assets['asst_'.$key] = $val;
				unset($asset_list[$key]);
			}
			
			/* foreach($assets as $key=>$value)
			{
				$asset_data .= "<option value='{$key}' class='added_asset'>{$value}</option>";
			} */
		
		}
		$result_arr["assets"] = $asset_data;
		
		return $assets;		
	}
	
	public function get_email_id_by_project($project_id,$designation = array())
	{
		$user_tbl = TableRegistry::get("erp_users");
		$emails = array();
		foreach($designation as $desg)
		{
			$user_data = $user_tbl->find()->where(["employee_at"=>$project_id,"designation"=>$desg]);
			if(!empty($user_data))
			{
				foreach($user_data as $user)
				{
					$emails[] = $user["email_id"];
					$emails[] = $user["second_email"];
				}
			}
		}
		return $emails;
	}
	
	public function get_cat_id_by_title($designation = array())
	{
		$tbl = TableRegistry::get("erp_category_master");
		$cat_ids = array();
		foreach($designation as $desg)
		{
			$row = $tbl->find()->where(["type"=>"designation","category_title"=>$desg])->hydrate(false)->toArray();
			if(!empty($row))
			{
				$cat_ids[] = $row[0]["cat_id"];				
			}
		}
		return $cat_ids;
	}
	
	public function edit_inventory_mrn_detail($material_items,$mrn_id)
	{
		$erp_inventory_mrn_detail = TableRegistry::get('erp_inventory_mrn_detail'); 
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['mrn_id'] =  $mrn_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['remarks'] =  $material_items['remarks'][$key];			
			$entity_data = $erp_inventory_mrn_detail->get($material_items['detail_id'][$key]);	
			$material_data=$erp_inventory_mrn_detail->patchEntity($entity_data,$save_data);
			$erp_inventory_mrn_detail->save($material_data);						
		}		
	}
	
	public function get_po_no_by_detailid($po_id)
    {
        $erp_inventory_po = TableRegistry::get("erp_inventory_po");
        $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
        $row = $erp_inventory_po_detail->find()->where(["id"=>$po_id])->hydrate(false)->toArray();
        $po_id1 = $row[0]["po_id"];
        $row1 = $erp_inventory_po->find()->where(["po_id"=>$po_id1])->hydrate(false)->toArray();
        if(!empty($row))
        {
            return $row1[0]["po_no"];             
        }
        else
        {
            return " - ";
        }
        
    }
	
	public function get_manualpo_no_by_detailid($po_id)
    {
        $erp_manual_po = TableRegistry::get("erp_manual_po");
        $erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
        $row = $erp_manual_po_detail->find()->where(["id"=>$po_id])->hydrate(false)->toArray();
        $po_id1 = $row[0]["po_id"];
        $row1 = $erp_manual_po->find()->where(["po_id"=>$po_id1])->hydrate(false)->toArray();
        if(!empty($row))
        {
            return $row1[0]["po_no"];             
        }
        else
        {
            return " - ";
        }
        
    }
	
	public function get_manualpo_mail_status($po_id)
    {
        $erp_manual_po = TableRegistry::get("erp_manual_po");
        $row = $erp_manual_po->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
        $mail_status = $row[0]["mail_check"];
       
        if(!empty($row))
        {
            return $mail_status;             
        }
        else
        {
            return " - ";
        }
        
    }
	
	public function get_po_mail_status($po_id)
    {
        $erp_inventory_po = TableRegistry::get("erp_inventory_po");
        $row = $erp_inventory_po->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
        $mail_status = $row[0]["mail_check"];
       
        if(!empty($row))
        {
            return $mail_status;             
        }
        else
        {
            return " - ";
        }
        
    }
	
	public function get_assetpo_mail_status($po_id)
    {
        $erp_asset_po = TableRegistry::get("erp_asset_po");
        $row = $erp_asset_po->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
        $mail_status = $row[0]["mail_check"];
       
        if(!empty($row))
        {
            return $mail_status;             
        }
        else
        {
            return " - ";
        }
        
    }
	
	public function get_loi_mail_status($loi_id)
    {
        $erp_letter_content = TableRegistry::get("erp_letter_content");
        $row = $erp_letter_content->find()->where(["id"=>$loi_id])->hydrate(false)->toArray();
        $mail_status = $row[0]["mail_check"];
       
        if(!empty($row))
        {
            return $mail_status;             
        }
        else
        {
            return " - ";
        }
        
    }
	
	public function get_vendor_name($user_id)
	{
		if(is_numeric($user_id))
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
				return 'User No Exist More';
		}
		else{
				return 'User No Exist More';
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
	
	public function check_emp_no_exists($emp_no)
	{
		$tbl = TableRegistry::get("erp_users");
		$count = $tbl->find()->where(["employee_no" => "{$emp_no}"])->count();
		return $count;
	}
	
	
	public function uploadinwardpdf($file)
	{
		$new_name = "";
		$img_name = $file["name"];	
		if(!empty($img_name))
		{
			$tmp_name = $file["tmp_name"];	
			$ext = substr(strtolower(strrchr($img_name, '.')), 1); 
			$new_name = time() . "_" . rand(000000, 999999). "." . $ext;	
			// move_uploaded_file($tmp_name,WWW_ROOT . "/nghome/".$new_name);	
			require_once "../vendor/autoload.php";
			try {
				$storage  = new StorageClient([
					'projectId' => 'yashnand-erp-2021',
					'keyFilePath' => WWW_ROOT .'/nghome/yashnand-erp-2021-67d9ca62a2c8.json',
				]);
				
				$bucketName = 'yashnand_2021_attachment';
				
				// $storage = new StorageClient();
				$source = $tmp_name;
				$objectName = $new_name;
				// debug($objectName);
				$file = fopen($source, 'r');
				$bucket = $storage->bucket($bucketName);
				if($bucket->upload(
					$file, [
						'name' => $objectName
					]
				)){
					$uploads[] = $new_name;
				}
			} catch(Exception $e) {
				echo $e->getMessage();
			}
			return $new_name;
		}
		return $new_name;
	}
	
	public function mail_salary_slip($slip_id,$date,$name,$user_email_id)
	{
		$url = Router::url('/', true)."humanresource/mailsalaryslip/{$slip_id}";
		/* $url = "http://localhost/svn/cakephp/cake_yashnanderp_2/humanresource/mailsalaryslip/{$slip_id}"; */
		$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Payslip.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Pay Slip"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find attached here with your Pay Slip.</p>";
		$email_message .= "<p><strong>Personnel Name : {$name}</strong></p>";
		$email_message .= "<p><strong>Month & Year : {$date}</strong></p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:hr@yashnandeng.com'>hr@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		// Who the email is to  
		// $email_to = $to; // Who the email is to  
		//$hr_emails = $this->get_hrhead_email();
		//$email_to = (!empty($hr_emails)) ? implode(",",$hr_emails) : "";
		//$email_to .= ",".$user_email_id;
		/* $email_to .= ",priyal@dasinfomedia.com"; 
		$email_to .= ",manan.patel@yashnandeng.com"; */ 
		$email_to = $user_email_id;
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$dir_to_save = WWW_ROOT;
		// echo $dir_to_save;die;
		$pdf_name=$this->generate_autoid('Salaryslip-').time().'.pdf';
		file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n";
						  
		$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->attachments(['Salary Slip' => $dir_to_save.$pdf_name])
						  ->send($email_message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
		echo $email;
	}
	
	Public function get_hrhead_email()
	{
		$users_table = TableRegistry::get("erp_users");
		$hrheads = $users_table->find()->where(['role'=>"hrhead",'employee_no'=>''])->select(["user_id","email_id","second_email"])->hydrate(false)->toArray();
		$emails = array();
		if(!empty($hrheads))
		{
			foreach($hrheads as $hr)
			{
				$emails[] = $hr["email_id"];
				$emails[] = $hr["second_email"];
			}
		}
		return $emails;
	}
	
	Public function get_hrmanager_email()
	{
		$users_table = TableRegistry::get("erp_users");
		$hrheads = $users_table->find()->where(['role'=>"hrmanager",'employee_no'=>''])->select(["user_id","email_id","second_email"])->hydrate(false)->toArray();
		$emails = array();
		if(!empty($hrheads))
		{
			foreach($hrheads as $hr)
			{
				$emails[] = $hr["email_id"];
				$emails[] = $hr["second_email"];
			}
		}
		return $emails;
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
	
	public function selected($option,$value)
	{
		if($option == $value)
			return 'selected';
		else
			return '';
	}
	
	public function add_purchase_rate_detail($material_items,$rate_id,$rate_from,$rate_to,$taxes_duties,$loading_trans,$unloading)
	{
		$erp_finalized_rate_detail = TableRegistry::get('erp_finalized_rate_detail');
		foreach($material_items['material_id'] as $key => $data)
		{
					
			$save_data['rate_id'] =  $rate_id;
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['hsn_code'] =  $material_items['hsn_code'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];
			$save_data['transportation'] =  $material_items['transportation'][$key];
			$save_data['gst'] =  $material_items['gst'][$key];
			$save_data['other_tax'] =  $material_items['other_tax'][$key];
			$save_data['final_rate'] =  $material_items['final_rate'][$key];
			$save_data['text_duties'] =  $taxes_duties;
			$save_data['loading_trans'] =  $loading_trans;
			$save_data['unloading'] =  $unloading;
			$save_data['rate_from_date'] =  $this->set_date($rate_from);
			$save_data['rate_to_date'] =  $this->set_date($rate_to);
			$row = $erp_finalized_rate_detail->newEntity();			
			$rate_material_data=$erp_finalized_rate_detail->patchEntity($row,$save_data);
			$erp_finalized_rate_detail->save($rate_material_data);						
		}		
	}
	
	public function edit_purchase_rate_detail($material_items,$rate_id,$rate_from,$rate_to,$taxes_duties,$loading_trans,$unloading)
	{
		$erp_finalized_rate_detail = TableRegistry::get('erp_finalized_rate_detail');
		foreach($material_items['material_id'] as $key => $data)
		{	
			$save_data['rate_id'] =  $rate_id;
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['hsn_code'] =  $material_items['hsn_code'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];
			$save_data['transportation'] =  $material_items['transportation'][$key];
			$save_data['gst'] =  $material_items['gst'][$key];
			$save_data['other_tax'] =  $material_items['other_tax'][$key];
			$save_data['final_rate'] =  $material_items['final_rate'][$key];
			$save_data['text_duties'] =  $taxes_duties;
			$save_data['loading_trans'] =  $loading_trans;
			$save_data['unloading'] =  $unloading;
			$save_data['rate_from_date'] =  $this->set_date($rate_from);
			$save_data['rate_to_date'] =  $this->set_date($rate_to);
			if(isset($material_items['detail_id'][$key]))
			{
				$row = $erp_finalized_rate_detail->get($material_items['detail_id'][$key]);
			}
			else{
				$row = $erp_finalized_rate_detail->newEntity();
			}			
			$rate_material_data=$erp_finalized_rate_detail->patchEntity($row,$save_data);
			$erp_finalized_rate_detail->save($rate_material_data);						
		}		
	}
	
	public function purchase_rate_old_project($rate_id)
	{
		$projects = array();
		$erp_rate_assign_project = TableRegistry::get('erp_rate_assign_project');
		$results = $erp_rate_assign_project->find()->where(array('rate_id'=>$rate_id));
		foreach($results as $retrive_data)
		{
			$projects[] = $retrive_data['project_id'];
		}
		return $projects;
	}
	
	public function purchase_rate_delete_project($rate_id,$project_id)
	{
		$erp_rate_assign_project = TableRegistry::get('erp_rate_assign_project');
		$results = $erp_rate_assign_project->find()->where(array('rate_id'=>$rate_id,'project_id'=>$project_id));
		foreach($results as $retrive_data)
		{
			$assign_id = $retrive_data['assign_id'];
			$user_data =$erp_rate_assign_project->get($assign_id);
			$erp_rate_assign_project->delete($user_data);
		}		
	}
	
	public function add_purchase_rate_project($assign_projects = array(),$rate_id)
	{
		$erp_rate_assign_project = TableRegistry::get('erp_rate_assign_project');
		$new_projects = $assign_projects;
		$old_projects = $this->purchase_rate_old_project($rate_id);
		$different_insert = array_diff($new_projects,$old_projects);
		$different_delete = array_diff($old_projects,$new_projects);		
		if(!empty($different_insert))		
		{
			foreach($different_insert as $project_id)
			{
				$data['rate_id'] =  $rate_id;
				$data['project_id'] =  $project_id;
				$patch_field = $erp_rate_assign_project->newEntity();					
				$save_field=$erp_rate_assign_project->patchEntity($patch_field,$data);
				$erp_rate_assign_project->save($save_field);
			}
		}	
		if(!empty($different_delete))
		{
			foreach($different_delete as $project_id)
			{				
				$this->purchase_rate_delete_project($rate_id,$project_id);		
			}
		}		
	}
	
	public function get_md_email_from_user()
	{
		$user_tbl = TableRegistry::get('erp_users'); 
		
		$md_emails = $user_tbl->find()->where(["role"=>"md","employee_no"=>""])->select(["email_id","second_email"])->hydrate(false)->toArray();
		$mdemail = array();
		if(!empty($md_emails))
		{
			foreach($md_emails as $data)
			{
				if($data["email_id"] != "")
				{
					$mdemail[] = $data["email_id"];
					$mdemail[] = $data["second_email"];
				}
			}
		}
		return $mdemail;
	}
	
	public function get_purchase_head_email_from_user()
	{
		$user_tbl = TableRegistry::get('erp_users'); 
		
		$head_emails = $user_tbl->find()->where(["role"=>"purchasehead","employee_no"=>"","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
		$head = array();
		if(!empty($head_emails))
		{
			foreach($head_emails as $data)
			{
				if($data["email_id"] != "")
				{
					$head[] = $data["email_id"];
					$head[] = $data["second_email"];
				}
			}
		}
		return $head;
	}
	
	public function get_purchase_manager_email_from_user()
	{
		$user_tbl = TableRegistry::get('erp_users'); 
		
		$manager_emails = $user_tbl->find()->where(["role"=>"purchasemanager","employee_no"=>""])->select(["email_id","second_email"])->hydrate(false)->toArray();
		$manager = array();
		if(!empty($manager_emails))
		{
			foreach($manager_emails as $data)
			{
				if($data["email_id"] != "")
				{
					$manager[] = $data["email_id"];
					$manager[] = $data["second_email"];
				}
			}
		}
		return $manager;
	}
	
	public function get_email_id_by_project_from_user($project_id,$role = array())
	{
		
		$user_tbl = TableRegistry::get("erp_users");
		$emails = array();
		foreach($role as $desg)
		{
			//$user_data = $user_tbl->find('all')->where(["employee_at"=>$project_id,"role"=>$desg,"employee_no ="=>""])->hydrate(false)->toArray();
			$user_data = $user_tbl->find('all');
			$user_data = $user_data->leftjoin(
							["erp_projects_assign"=>"erp_projects_assign"],
							["erp_users.user_id = erp_projects_assign.user_id"])
							->where(["erp_users.role"=>$desg,"erp_users.status !="=>0,"erp_users.employee_no"=>"","erp_projects_assign.project_id IN" => $project_id])->hydrate(false)->toArray();
			//debug($user_data);
			if(!empty($user_data))
			{
				foreach($user_data as $user)
				{
					$emails[] = $user["email_id"];
					$emails[] = $user["second_email"];
				}
			}
		}
		return $emails;
	}
	
	public function get_email_id_by_role_from_user($role = array())
	{
		$user_tbl = TableRegistry::get("erp_users");
		$emails = array();
		foreach($role as $desg)
		{
			$user_data = $user_tbl->find()->where(["role"=>$desg,"employee_no"=>"","status !="=>0]);
			if(!empty($user_data))
			{
				foreach($user_data as $user)
				{
					$emails[] = $user["email_id"];
					$emails[] = $user["second_email"];
				}
			}
		}
		return $emails;
	}
	
	public function add_work_order_detail($material_items,$wo_id)
	{
		$wo_detail_tbl = TableRegistry::get('erp_work_order_detail'); 
		foreach($material_items['work_head'] as $key => $data)
		{
			$save_data['wo_id'] = $wo_id;
			$save_data['contract_no'] = $material_items['contract_no'][$key];
			$save_data['work_head'] = $material_items['work_head'][$key];
			$save_data['material_name'] = $material_items['material_name'][$key];
			$save_data['quentity'] = $material_items['quantity'][$key];
			$save_data['unit'] = $material_items['unit'][$key];
			$save_data['unit_rate'] = $material_items['unit_rate'][$key];
			$save_data['discount'] = $material_items['discount'][$key];
			$save_data['cgst'] = $material_items['cgst'][$key];
			$save_data['sgst'] = $material_items['sgst'][$key];
			$save_data['igst'] = $material_items['igst'][$key];
			$save_data['amount'] = $material_items['amount'][$key];
			//$save_data['target_date'] = $this->set_date($material_items['target_date'][$key]);
			
			$wo_material_data = $wo_detail_tbl->newEntity();			
			$record = $wo_detail_tbl->patchEntity($wo_material_data,$save_data);
			$wo_detail_tbl->save($record);						
		}		
	}
	
	public function add_planning_work_order_detail($material_items,$wo_id)
	{
		$wo_detail_tbl = TableRegistry::get('erp_planning_work_order_detail'); 
		foreach($material_items['material_name'] as $key => $data)
		{
			$save_data['wo_id'] = $wo_id;
			$save_data['contract_no'] = $material_items['contract_no'][$key];
			// $save_data['work_head'] = $material_items['work_head'][$key];
			$save_data['material_name'] = $material_items['material_name'][$key];
			$save_data['detail_description'] = $material_items['detail_description'][$key];
			$save_data['quentity'] = $material_items['quantity_this_wo'][$key];
			$save_data['quantity_upto_previous'] = $material_items['quantity_previous_wo'][$key];
			$save_data['till_date_quantity'] = $material_items['quantity_till_date'][$key];
			$save_data['unit'] = $material_items['unit'][$key];
			$save_data['unit_rate'] = $material_items['unit_rate'][$key];
			// $save_data['description'] = $material_items['description'][$key];
			// $save_data['discount'] = $material_items['discount'][$key];
			// $save_data['cgst'] = $material_items['cgst'][$key];
			// $save_data['sgst'] = $material_items['sgst'][$key];
			// $save_data['igst'] = $material_items['igst'][$key];
			$save_data['amount'] = $material_items['amount'][$key];
			$save_data['amount_till_date'] = $material_items['amount_till_date'][$key];
			//$save_data['target_date'] = $this->set_date($material_items['target_date'][$key]);
			
			$wo_material_data = $wo_detail_tbl->newEntity();			
			$record = $wo_detail_tbl->patchEntity($wo_material_data,$save_data);
			$wo_detail_tbl->save($record);						
		}		
	}
	
	public function edit_work_order_detail($material_items,$wo_id)
	{
		$wo_detail_tbl = TableRegistry::get('erp_work_order_detail'); 
		foreach($material_items['work_head'] as $key => $data)
		{
			$save_data['wo_id'] = $wo_id;
			$save_data['contract_no'] = $material_items['contract_no'][$key];
			$save_data['work_head'] = $material_items['work_head'][$key];
			$save_data['material_name'] = $material_items['material_name'][$key];
			$save_data['quentity'] = $material_items['quantity'][$key];
			$save_data['unit'] = $material_items['unit'][$key];
			$save_data['unit_rate'] = $material_items['unit_rate'][$key];
			$save_data['discount'] = $material_items['discount'][$key];
			$save_data['cgst'] = $material_items['cgst'][$key];
			$save_data['sgst'] = $material_items['sgst'][$key];
			$save_data['igst'] = $material_items['igst'][$key];
			$save_data['amount'] = $material_items['amount'][$key];
			//$save_data['target_date'] = $this->set_date($material_items['target_date'][$key]);
			
			if(isset($material_items['wo_detail_id'][$key]))
			{
				$wo_material_data = $wo_detail_tbl->get($material_items['wo_detail_id'][$key]);
			}
			else{
				$wo_material_data = $wo_detail_tbl->newEntity();
			}
						
			$record = $wo_detail_tbl->patchEntity($wo_material_data,$save_data);
			$wo_detail_tbl->save($record);						
		}		
	}

	public function edit_planning_work_order_detail($material_items,$wo_id)
	{
		$wo_detail_tbl = TableRegistry::get('erp_planning_work_order_detail'); 
		foreach($material_items['material_name'] as $key => $data)
		{
			$save_data['wo_id'] = $wo_id;
			$save_data['contract_no'] = $material_items['contract_no'][$key];
			$save_data['material_name'] = $material_items['material_name'][$key];
			$save_data['detail_description'] = $material_items['detail_description'][$key];
			$save_data['quentity'] = $material_items['quantity_this_wo'][$key];
			$save_data['quantity_upto_previous'] = $material_items['quantity_previous_wo'][$key];
			$save_data['till_date_quantity'] = $material_items['quantity_till_date'][$key];
			$save_data['unit'] = $material_items['unit'][$key];
			$save_data['unit_rate'] = $material_items['unit_rate'][$key];
			// $save_data['description'] = $material_items['description'][$key];
			// $save_data['discount'] = $material_items['discount'][$key];
			// $save_data['cgst'] = $material_items['cgst'][$key];
			// $save_data['sgst'] = $material_items['sgst'][$key];
			// $save_data['igst'] = $material_items['igst'][$key];
			$save_data['amount'] = $material_items['amount'][$key];
			$save_data['amount_till_date'] = $material_items['amount_till_date'][$key];
			//$save_data['target_date'] = $this->set_date($material_items['target_date'][$key]);
			
			if(isset($material_items['wo_detail_id'][$key]))
			{
				$wo_material_data = $wo_detail_tbl->get($material_items['wo_detail_id'][$key]);
			}
			else{
				$wo_material_data = $wo_detail_tbl->newEntity();
			}
						
			$record = $wo_detail_tbl->patchEntity($wo_material_data,$save_data);
			$wo_detail_tbl->save($record);						
		}		
	}

	public function add_ammend_work_order_details($material_items,$wo_id,$old_wo_data)
	{
		$wo_detail_tbl = TableRegistry::get('erp_planning_work_order_detail'); 
		foreach($material_items['material_name'] as $key => $data)
		{
			$save_data['wo_id'] = $wo_id;
			$save_data['contract_no'] = $material_items['contract_no'][$key];
			$save_data['material_name'] = $material_items['material_name'][$key];
			$save_data['detail_description'] = $material_items['detail_description'][$key];
			$save_data['quentity'] = $material_items['quantity_this_wo'][$key];
			$save_data['quantity_upto_previous'] = $material_items['quantity_previous_wo'][$key];
			$save_data['till_date_quantity'] = $material_items['quantity_till_date'][$key];
			$save_data['unit'] = $material_items['unit'][$key];
			$save_data['unit_rate'] = $material_items['unit_rate'][$key];
			// $save_data['description'] = $material_items['description'][$key];
			// $save_data['discount'] = $material_items['discount'][$key];
			// $save_data['cgst'] = $material_items['cgst'][$key];
			// $save_data['sgst'] = $material_items['sgst'][$key];
			// $save_data['igst'] = $material_items['igst'][$key];
			$save_data['amount'] = $material_items['amount'][$key];
			$save_data['amount_till_date'] = $material_items['amount_till_date'][$key];
			$save_data['approved'] = 1;
			//$save_data['target_date'] = $this->set_date($material_items['target_date'][$key]);
			
			if($old_wo_data->updated == 1 && $old_wo_data->ammend_approve == 0)
			{
				if(isset($material_items['wo_detail_id'][$key]))
				{
					$wo_material_data = $wo_detail_tbl->get($material_items['wo_detail_id'][$key]);
				}else{
					$wo_material_data = $wo_detail_tbl->newEntity();
				}
			}
			else{
				$wo_material_data = $wo_detail_tbl->newEntity();
			}
						
			$record = $wo_detail_tbl->patchEntity($wo_material_data,$save_data);
			$wo_detail_tbl->save($record);						
		}		
	}

	public function add_ammend_po_details($material_items,$po_id,$old_po_data) {
		$erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail');
		foreach($material_items['material_id'] as $key => $data) {
			// debug($material_items);die;
			// if(isset($material_items['m_code'][$key]))
			// {
			// 	$save_data['m_code'] =  $material_items['m_code'][$key];
			// }
			// if(isset($material_items['static_unit'][$key]))
			// {
			// 	$save_data['static_unit'] =  $material_items['static_unit'][$key];
			// }
			// if(isset($material_items['is_custom'][$key]))
			// {
			// 	$save_data['is_custom'] =  $material_items['is_custom'][$key];
			// }
			// if(isset($material_items['pr_mid'][$key])) {
			// 	$save_data['pr_mid'] =  $material_items['pr_mid'][$key];
			// }else {
			// 	$save_data['pr_mid'] =  0;
			// }
			// if($material_items['unit_rate'][$key] != '')
			// {
			// 	$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			// }else {
			// 	$save_data['unit_price'] =  0;
			// }
			// $save_data['po_id'] =  $po_id;
			// if(isset( $material_items['description'][$key])) {
			// 	$save_data['description'] = $material_items['description'][$key];
			// }
			// $save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['po_id'] = $po_id;
			$save_data['m_code'] = $material_items['m_code'][$key];
			$save_data['material_id'] = $material_items['material_id'][$key];
			$save_data['description'] = $material_items['description'][$key];
			$save_data['brand_id'] = $material_items['brand_id'][$key];
			$save_data['quantity'] = $material_items['quantity'][$key];
			$save_data['static_unit'] = $material_items['static_unit'][$key];
			$save_data['unit_price'] = $material_items['unit_rate'][$key];
			// $save_data['pr_mid'] = $material_items['pr_mid'][$key];
			$save_data['discount'] = $material_items['discount'][$key];
			$save_data['gst'] = $material_items['gst'][$key];
			$save_data['amount'] = $material_items['amount'][$key];
			$save_data['single_amount'] = $material_items['single_amount'][$key];
			$save_data['approved'] = 1;
			// debug($save_data);die;
			if($old_po_data->updated == 1 && $old_po_data->ammend_approve == 0) {
				if(isset($material_items['detail_id'][$key])) {
					$po_material_data = $erp_inventory_po_detail->get($material_items['detail_id'][$key]);
				}else {
					$po_material_data = $erp_inventory_po_detail->newEntity();
				}
			}else {
				$po_material_data = $erp_inventory_po_detail->newEntity();
			}

			$record = $erp_inventory_po_detail->patchEntity($po_material_data,$save_data);
			$erp_inventory_po_detail->save($record);						
		}		
	}
	
	public function get_wo_no_by_id($wo_id)
	{
		$tbl = TableRegistry::get("erp_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_no = "NA";
		if(!empty($row))
		{
			$wo_no = $row[0]["wo_no"];
		}
		return $wo_no;
	}
	
	public function get_wo_date_by_id($wo_id)
	{
		$tbl = TableRegistry::get("erp_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_date = "NA";
		if(!empty($row))
		{
			$wo_date = $row[0]["wo_date"];
		}
		return $wo_date;
	}
	
	public function get_wo_project_by_id($wo_id)
	{
		$tbl = TableRegistry::get("erp_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_project = "NA";
		if(!empty($row))
		{
			$wo_project = $row[0]["project_id"];
		}
		return $this->get_projectname($wo_project);
	}
	
	public function get_wo_party_by_id($wo_id)
	{
		$tbl = TableRegistry::get("erp_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_party_name = "NA";
		if(!empty($row))
		{
			$wo_party = $row[0]["party_userid"];
			if(is_numeric($wo_party)){
				$wo_party_name = $this->get_vendor_name($wo_party);
			}
			else{
				$wo_party_name = $this->get_agency_name_by_code($wo_party);
			}
		}
		
		return $wo_party_name;
	}
	
	public function get_wo_attachment($wo_id)
	{
		$tbl = TableRegistry::get("erp_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_attachment = "NA";
		if(!empty($row))
		{
			$wo_attachment = $row[0]["attachment"];
		}
		return $wo_attachment;
	}
	
	public function wo_approve_mail($to,$wo_id)
	{
		$wo_no = $this->get_wo_no_by_id($wo_id);
		$wo_project = $this->get_wo_project_by_id($wo_id);
		$wo_party = $this->get_wo_party_by_id($wo_id);
		$wo_date = $this->get_wo_date_by_id($wo_id);
		$wo_attachment = $this->get_wo_attachment($wo_id);
		$files = json_decode($wo_attachment);
		$urls = array();
		$attachments = array();
		foreach($files as $file)
		{
			// $urls[] = "http://erp.yashnandeng.com/webroot/upload/{$file}";
			$attachments[] = WWW_ROOT."upload/".$file;
		}
		//$url = "http://erp.yashnandeng.com/contract/printwo/{$wo_id}/mail";
		$url = Router::url('/', true)."contract/printworecord/{$wo_id}";
		// $url = "http://localhost/cake_yashnanderp_2/contract/printworecord/{$wo_id}";
		// array_push($urls,$url1);
		$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "work_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Work Order (W.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Work Order (W.O.) [<strong>W.O. No:</strong> {$wo_no}] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>W.O. No :</strong> {$wo_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$wo_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$wo_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		//Old Code for multiple attachment
		/*
		// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to  
		$headers = "From: ".$email_from;
		
		// boundary 
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
		 
		// headers for attachment 
		$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
		 
		// multipart boundary 
		$email_message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_message . "\n\n"; 
		$email_message .= "--{$mime_boundary}\n";
	
		$i = 1;
		foreach($urls as $url)
		{
			$file = fopen($url,"rb");
			//$data = fread($file,filesize($url));
			$fileatt_name = "attachment".$i.".pdf";
			$contents = file_get_contents($url); // read the remote file
		touch('temp'.$i.'.pdf'); // create a local EMPTY copy
		file_put_contents('temp'.$i.'.pdf', $contents);


		$data = fread($file,filesize('temp'.$i.'.pdf')); 
			fclose($file);
			$data = chunk_split(base64_encode(file_get_contents('temp'.$i.'.pdf')));
			$email_message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$fileatt_name\"\n" . 
			"Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" . 
			"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
			$email_message .= "--{$mime_boundary}\n";
			$i++;
		}  			
						// $email = new Email('default');
						   // $email->from("das@gmail.com")
						   // ->emailFormat('html')
						  // ->to('vijay.parmar@dasinfomedia.com')
						  // ->subject($email_subject)
						  // ->send($email_message);
						  
		$ok = @mail($email_to, $email_subject, $email_message, $headers);
		// $ok = @mail('vijay.parmar@dasinfomedia.com,manan.patel@yashnandeng.com', $email_subject, $email_message, $headers);
		*/
		// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";					
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$dir_to_save = WWW_ROOT;
		// echo $dir_to_save;die;
		$pdf_name=$this->generate_autoid('wo-').time().'.pdf';
		 
		file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		$attachments[] = $dir_to_save.$pdf_name;
		// debug($attachments);
		$email_message_old .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n"; 
					 		
							$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  ->subject($email_subject)
						  ->attachments($attachments)
						  ->send($email_message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
	}
	
	public function get_planningwo_no_by_id($wo_id)
	{
		$tbl = TableRegistry::get("erp_planning_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_no = "NA";
		if(!empty($row))
		{
			$wo_no = $row[0]["wo_no"];
		}
		return $wo_no;
	}
	
	public function get_planningwo_date_by_id($wo_id)
	{
		$tbl = TableRegistry::get("erp_planning_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_date = "NA";
		if(!empty($row))
		{
			$wo_date = $row[0]["wo_date"];
		}
		return $wo_date;
	}
	
	public function get_planningwo_project_by_id($wo_id)
	{
		$tbl = TableRegistry::get("erp_planning_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_project = "NA";
		if(!empty($row))
		{
			$wo_project = $row[0]["project_id"];
		}
		return $this->get_projectname($wo_project);
	}
	
	public function get_planningwo_party_by_id($wo_id)
	{
		$tbl = TableRegistry::get("erp_planning_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_party_name = "NA";
		if(!empty($row))
		{
			$wo_party = $row[0]["party_userid"];
			if(is_numeric($wo_party)){
				$wo_party_name = $this->get_vendor_name($wo_party);
			}
			else{
				$wo_party_name = $this->get_agency_name_by_code($wo_party);
			}
		}
		
		return $wo_party_name;
	}
	
	public function get_planningwo_attachment($wo_id)
	{
		$tbl = TableRegistry::get("erp_planning_work_order");
		$row = $tbl->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
		$wo_attachment = "NA";
		if(!empty($row))
		{
			$wo_attachment = $row[0]["attachment"];
		}
		return $wo_attachment;
	}

	public function planningwo_approve_mail($to,$wo_id)
	{
		$wo_no = $this->get_planningwo_no_by_id($wo_id);
		$wo_project = $this->get_planningwo_project_by_id($wo_id);
		$wo_party = $this->get_planningwo_party_by_id($wo_id);
		$wo_date = $this->get_planningwo_date_by_id($wo_id);
		$wo_attachment = $this->get_planningwo_attachment($wo_id);
		$files = json_decode($wo_attachment);
		$urls = array();
		$attachments = array();
		foreach($files as $file)
		{
			// $urls[] = "http://erp.yashnandeng.com/webroot/upload/{$file}";
			$attachments[] = WWW_ROOT."upload/".$file;
		}
		//$url = "http://erp.yashnandeng.com/contract/printwo/{$wo_id}/mail";
		$url = Router::url('/', true)."contract/mailplanningworecord/{$wo_id}";
		// $url = "http://localhost/cake_yashnanderp_2/contract/printworecord/{$wo_id}";
		// array_push($urls,$url1);
		$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "work_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Work Order (W.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Work Order (W.O.) [<strong>W.O. No:</strong> {$wo_no}] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>W.O. No :</strong> {$wo_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$wo_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$wo_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		//Old Code for multiple attachment
		/*
		// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to  
		$headers = "From: ".$email_from;
		
		// boundary 
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
		 
		// headers for attachment 
		$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
		 
		// multipart boundary 
		$email_message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_message . "\n\n"; 
		$email_message .= "--{$mime_boundary}\n";
	
		$i = 1;
		foreach($urls as $url)
		{
			$file = fopen($url,"rb");
			//$data = fread($file,filesize($url));
			$fileatt_name = "attachment".$i.".pdf";
			$contents = file_get_contents($url); // read the remote file
		touch('temp'.$i.'.pdf'); // create a local EMPTY copy
		file_put_contents('temp'.$i.'.pdf', $contents);


		$data = fread($file,filesize('temp'.$i.'.pdf')); 
			fclose($file);
			$data = chunk_split(base64_encode(file_get_contents('temp'.$i.'.pdf')));
			$email_message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$fileatt_name\"\n" . 
			"Content-Disposition: attachment;\n" . " filename=\"$fileatt_name\"\n" . 
			"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
			$email_message .= "--{$mime_boundary}\n";
			$i++;
		}  			
						// $email = new Email('default');
						   // $email->from("das@gmail.com")
						   // ->emailFormat('html')
						  // ->to('vijay.parmar@dasinfomedia.com')
						  // ->subject($email_subject)
						  // ->send($email_message);
						  
		$ok = @mail($email_to, $email_subject, $email_message, $headers);
		// $ok = @mail('vijay.parmar@dasinfomedia.com,manan.patel@yashnandeng.com', $email_subject, $email_message, $headers);
		*/
		// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";					
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$dir_to_save = WWW_ROOT;
		// echo $dir_to_save;die;
		$pdf_name=$this->generate_autoid('wo-').time().'.pdf';
		 
		file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		$attachments[] = $dir_to_save.$pdf_name;
		// debug($attachments);
		$email_message_old .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n"; 
					 		
							$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						//   ->to('vijay.parmar@dasinfomedia.com')
						  ->subject($email_subject)
						  ->attachments($attachments)
						  ->send($email_message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
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
	
	/* Image upload code */
	public function upload_drawing_file($filename,$old_image='')
    {		
		$parts = pathinfo($filename['name']);
		
		$file_size = $filename['size'];
		
		$image_director="img/";
			
		$full_path=WWW_ROOT.$image_director;
		
		if($file_size > 0) {	
			if (!file_exists($full_path)) {
				mkdir($full_path, 0777, true);
			}
			
			$imgname=$this->generate_autoid('img-').time().'.'.$parts['extension'];
			$return_image = $imgname;
			$image_path=$full_path.'/'.$return_image;
		
			if($old_image !='')
			{			
				$image_array = explode('/',$old_image);			
				if (file_exists($full_path.'/'.$image_array[1])) {				
					unlink($full_path.'/'.$image_array[1]);
				}
				
			}
			
			// if(move_uploaded_file($filename['tmp_name'],$image_path))
			// {		
			// 	return $return_image;		
			// }
			require_once "../vendor/autoload.php";
			try {
				$storage  = new StorageClient([
					'projectId' => 'yashnand-erp-2021',
					'keyFilePath' => WWW_ROOT .'/nghome/yashnand-erp-2021-67d9ca62a2c8.json',
				]);
			 
				$bucketName = 'yashnand_2021_attachment';
				
				// $storage = new StorageClient();
				$source = $filename['tmp_name'];
				$objectName = $return_image;
				// debug($objectName);
				$file = fopen($source, 'r');
				$bucket = $storage->bucket($bucketName);
				if($bucket->upload(
					$file, [
						'name' => $objectName
					]
				)){
					$uploads[] = $return_image;
				}
			} catch(Exception $e) {
				echo $e->getMessage();
			}
			return $return_image;
		}
		else
		return $old_image;		
	}
	
	public function upload_debit_file($filename,$old_image='')
    {		
		$parts = pathinfo($filename['name']);
		// debug($filename['tmp_name']);die;
		$file_size = $filename['size'];
		
		$image_director="img/";
			
		$full_path=WWW_ROOT.$image_director;
		
		if($file_size > 0) {	
			if (!file_exists($full_path)) {
				mkdir($full_path, 0777, true);
			}
			
			$imgname=$this->generate_autoid('img-').time().'.'.$parts['extension'];
			$return_image = $imgname;
			$image_path=$full_path.'/'.$return_image;
		
			if($old_image !='') {			
				$image_array = explode('/',$old_image);			
				if (file_exists($full_path.'/'.$image_array[1])) {				
					unlink($full_path.'/'.$image_array[1]);
				}
			}
			
			// if(move_uploaded_file($filename['tmp_name'],$image_path)) {		
			// 	return $return_image;		
			// }
			require_once "../vendor/autoload.php";
			try {
				$storage  = new StorageClient([
					'projectId' => 'yashnand-erp-2021',
					'keyFilePath' => WWW_ROOT .'/nghome/yashnand-erp-2021-67d9ca62a2c8.json',
				]);
				
				$bucketName = 'yashnand_2021_attachment';
				
				$source = $filename['tmp_name'];
				$objectName = $return_image;
				$file = fopen($source, 'r');
				$bucket = $storage->bucket($bucketName);
				if($bucket->upload(
					$file, [
						'name' => $objectName
					]
				)){
					$uploads[] = $return_image;
				}
			} catch(Exception $e) {
				echo $e->getMessage();
			}
			return $return_image;
		}
		else
		return $old_image;		
	}
	
	public function add_drawing_detail($drawing_items,$drawing_id)
	{
		$dtl_tbl = TableRegistry::get('erp_drawing_detail'); 
		foreach($drawing_items['revision_no'] as $key => $data)
		{
			$save_data['drawing_id'] =  $drawing_id;
			$save_data['revision_no'] =  $drawing_items['revision_no'][$key];
			$save_data['receipt_date'] =  $this->set_date($drawing_items['receipt_date'][$key]);
			$save_data['remarks'] =  $drawing_items['remark'][$key];
			$save_data['attach_name'] =  $drawing_items['attach_name'][$key];
			if(isset($drawing_items["attach_file"][$key]))
			{
				$file = $this->upload_drawing_file($drawing_items["attach_file"][$key]);	
				if(!empty($file))
				{
					$save_data['attach_file'] =  $file;
				}					
			}
			
			$drawing_data = $dtl_tbl->newEntity();			
			$drawing_data=$dtl_tbl->patchEntity($drawing_data,$save_data);
			$dtl_tbl->save($drawing_data);						
		}		
	}
	
	public function edit_drawing_detail($drawing_items,$drawing_id)
	{
		$dtl_tbl = TableRegistry::get('erp_drawing_detail'); 
		foreach($drawing_items['revision_no'] as $key => $data)
		{
			
			$save_data['drawing_id'] =  $drawing_id;
			$save_data['revision_no'] =  $drawing_items['revision_no'][$key];
			$save_data['receipt_date'] =  $this->set_date($drawing_items['receipt_date'][$key]);
			$save_data['remarks'] =  $drawing_items['remark'][$key];
			$save_data['attach_name'] =  $drawing_items['attach_name'][$key];
			if(isset($drawing_items['detail_id'][$key]))
			{
				if($drawing_items["attach_file"][$key]['name'] != '')
				{
					$file = $this->upload_drawing_file($drawing_items["attach_file"][$key]);	
					if(!empty($file))
					{
						$save_data['attach_file'] = $file;
					}					
				}
				else{
					$save_data['attach_file'] = $drawing_items["old_file"][$key];  
				}
			}
			else{
				$file = $this->upload_drawing_file($drawing_items["attach_file"][$key]);	
				if(!empty($file))
				{
					$save_data['attach_file'] = $file;
				}	
			}
			if(isset($drawing_items['detail_id'][$key]))
			{
				$drawing_data = $dtl_tbl->get($drawing_items['detail_id'][$key]);
			}
			else{
				$drawing_data = $dtl_tbl->newEntity();
			}			
			$drawing_data=$dtl_tbl->patchEntity($drawing_data,$save_data);
			$dtl_tbl->save($drawing_data);						
		}		
	}
	
	public function add_debit_detail($debit_item,$debit_id,$total,$total_word)
	{
		$erp_debit_note_detail = TableRegistry::get('erp_debit_note_detail'); 
		foreach($debit_item['reason'] as $key => $data)
		{
			$save_data['debit_id'] =  $debit_id;			
			$save_data['reason'] =  $debit_item['reason'][$key];
			$save_data['quantity'] =  $debit_item['quantity'][$key];
			$save_data['rate'] =  $debit_item['rate'][$key];
			$save_data['amount'] =  $debit_item['single_amount'][$key];
			$save_data['total_amount'] =  $total;
			$save_data['total_word'] =  $total_word;
			$req_data = $erp_debit_note_detail->newEntity();			
			$req_data=$erp_debit_note_detail->patchEntity($req_data,$save_data);
			$erp_debit_note_detail->save($req_data);						
		}		
	}
	
	public function edit_debit_detail($debit_item,$debit_id,$total,$total_word)
	{
		$erp_debit_note_detail = TableRegistry::get('erp_debit_note_detail'); 
		foreach($debit_item['detail_id'] as $key => $data)
		{
			$save_data['debit_id'] =  $debit_id;			
			$save_data['reason'] =  $debit_item['reason'][$key];
			$save_data['quantity'] =  $debit_item['quantity'][$key];
			$save_data['rate'] =  $debit_item['rate'][$key];
			$save_data['amount'] =  $debit_item['single_amount'][$key];
			$save_data['total_amount'] =  $total;
			$save_data['total_word'] =  $total_word;
			$req_data = $erp_debit_note_detail->get($debit_item['detail_id'][$key]);			
			$req_data=$erp_debit_note_detail->patchEntity($req_data,$save_data);
			$erp_debit_note_detail->save($req_data);						
		}		
	}
	
	public function edit_inventory_debit_detail($debit_item,$debit_id,$total,$total_word)
	{
		$erp_debit_note_detail = TableRegistry::get('erp_inventory_debit_note_detail'); 
		foreach($debit_item['detail_id'] as $key => $data)
		{
			$save_data['debit_id'] =  $debit_id;			
			$save_data['material_id'] =  $debit_item['material_id'][$key];
			$save_data['quantity'] =  $debit_item['quantity'][$key];
			$save_data['rate'] =  $debit_item['rate'][$key];
			$save_data['amount'] =  $debit_item['single_amount'][$key];
			$save_data['total_amount'] =  $total;
			$save_data['total_word'] =  $total_word;
			$req_data = $erp_debit_note_detail->get($debit_item['detail_id'][$key]);			
			$req_data=$erp_debit_note_detail->patchEntity($req_data,$save_data);
			$erp_debit_note_detail->save($req_data);						
		}		
	}
	
	public function remove_stock_from_project_bysst($sst_id,$sst_detail_id)
	{
		$sst_tbl = TableRegistry::get("erp_inventory_sst");
		$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail');
		$sst_detail_data = $erp_inventory_sst_detail->find()->where(['sst_id'=>$sst_id])->hydrate(false)->toArray();
		
		$sstdata = $sst_tbl->get($sst_id);
		$project_id = $sstdata->project_id;		
		$transfer_to = $sstdata->transfer_to;
		$sst_date = $sstdata->sst_date;
				
		/* Foreach End */
		foreach($sst_detail_data as $retrive)
		{
			$po_data = $erp_inventory_sst_detail->get($retrive['sst_detail_id']);
			$material_id = $po_data->material_id;		
			$quantity = $po_data->quantity;
			$post_data['approved_site2'] = 1;
			$post_data['approved_date'] = date('Y-m-d');
			$post_data['approved_by'] = $this->request->session()->read('user_id');
			$data = $erp_inventory_sst_detail->patchEntity($po_data,$post_data);
			$erp_inventory_sst_detail->save($data);
			
			$history_tbl = TableRegistry::get("erp_stock_history");
			$insert = array();
			$history_row = $history_tbl->newEntity();
			$insert["date"] = $sst_date;
			$insert["project_id"] = $transfer_to;
			$insert["material_id"] = $material_id;
			$insert["quantity"] = $quantity;
			$insert["stock_in"] = $quantity;			
			$insert["type"] = "sst_to";
			$insert["type_id"] = $sst_id;
			$history_row = $history_tbl->patchEntity($history_row,$insert);
			$history_tbl->save($history_row);	
			
			
			$stock_tbl = TableRegistry::get("erp_stock");
			/* Deduct Stock from first project*/
			$check_stock = $stock_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id])->hydrate(false)->toArray();		

			if(!empty($check_stock))
			{
				$query = $stock_tbl->query();
				$query->update()
					->set(['quantity' => $check_stock[0]["quantity"] - intval($quantity)])
					->where(['project_id' => $project_id,'material_id'=>$material_id])
					->execute();
			}
			else{
				$stock_row = $stock_tbl->newEntity();
				$stock_data["project_id"] = $project_id;
				$stock_data["material_id"] = $material_id;
				$stock_data["quantity"] = gmp_neg($quantity);
				$stock_row = $stock_tbl->patchEntity($stock_row,$stock_data);
				$stock_tbl->save($stock_row);
			}
		}
		/* Foreach End */
	}
	
	public function add_sub_contract_detail($contract_detail,$contract_id)
	{
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail'); 
		foreach($contract_detail['description'] as $key => $data)
		{
			$save_data['sub_contract_id'] =  $contract_id;			
			$save_data['item_no'] =  $contract_detail['item_no'][$key];
			$save_data['description'] =  $contract_detail['description'][$key];
			$save_data['unit'] =  $contract_detail['unit'][$key];
			$save_data['quantity_this_bill'] = $contract_detail['quantity_this_bill'][$key];
			$save_data['quantity_previous_bill'] = $contract_detail['quantity_previous_bill'][$key];
			$save_data['quantity_till_date'] = $contract_detail['quantity_till_date'][$key];
			$save_data['rate'] = $contract_detail['rate'][$key];
			$save_data['full_rate'] = $contract_detail['full_rate'][$key];
			$save_data['amount_this_bill'] = $contract_detail['amount_this_bill'][$key];
			$save_data['amount_previous_bill'] = $contract_detail['amount_previous_bill'][$key];
			$save_data['amount_till_date'] = $contract_detail['amount_till_date'][$key];
			$req_data = $erp_sub_contract_detail->newEntity();			
			$req_data=$erp_sub_contract_detail->patchEntity($req_data,$save_data);
			$erp_sub_contract_detail->save($req_data);						
		}		
	}
	
	public function edit_sub_contract_detail($contract_detail,$contract_id)
	{
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		$delete_ok = $erp_sub_contract_detail->deleteAll(["id NOT IN"=>$contract_detail['detail_id'],"sub_contract_id"=>$contract_id]);
		
		foreach($contract_detail['description'] as $key => $data)
		{
			$save_data['sub_contract_id'] =  $contract_id;			
			$save_data['item_no'] =  $contract_detail['item_no'][$key];
			$save_data['description'] =  $contract_detail['description'][$key];
			$save_data['unit'] =  $contract_detail['unit'][$key];
			$save_data['quantity_this_bill'] = $contract_detail['quantity_this_bill'][$key];
			$save_data['quantity_previous_bill'] = $contract_detail['quantity_previous_bill'][$key];
			$save_data['quantity_till_date'] = $contract_detail['quantity_till_date'][$key];
			$save_data['rate'] = $contract_detail['rate'][$key];
			$save_data['full_rate'] = $contract_detail['full_rate'][$key];
			$save_data['amount_this_bill'] = $contract_detail['amount_this_bill'][$key];
			$save_data['amount_previous_bill'] = $contract_detail['amount_previous_bill'][$key];
			$save_data['amount_till_date'] = $contract_detail['amount_till_date'][$key];
			
			if(isset($contract_detail['detail_id'][$key]))
			{
				$req_data = $erp_sub_contract_detail->get($contract_detail['detail_id'][$key]);
			}
			else{
				$req_data = $erp_sub_contract_detail->newEntity();
			}
						
			$req_data=$erp_sub_contract_detail->patchEntity($req_data,$save_data);
			$erp_sub_contract_detail->save($req_data);						
		}		
	}
	
	public function get_date_month_range($startstring,$endstring)
	{
		$time1  = strtotime($startstring);//absolute date comparison needs to be done here, because PHP doesn't do date comparisons
		$time2  = strtotime($endstring);
		$my1     = date('mY', $time1); //need these to compare dates at 'month' granularity
		$my2    = date('mY', $time2);
		$year1 = date('Y', $time1);
		$year2 = date('Y', $time2);
		$years = range($year1, $year2);
		 
		foreach($years as $year)
		{
			$months[$year] = array();
			while($time1 <= $time2)
			{
				if(date('Y',$time1) == $year)
				{
					$months[$year][] = date('m', $time1);
					$time1 = strtotime(date('Y-m-d', $time1).' +1 month');
				}
				else
				{
					break;
				}
			}
			continue;
		}
		return $months;
	}
	
	public function get_asset_name($asset_id){
		$erp_asset = TableRegistry::get('erp_assets');
		$results = $erp_asset->get($asset_id)->toArray();	
		// debug($results);	
		$name = "";
		if(isset($results['asset_name'])) {
			$name = $results['asset_name'];
		}else {
			$name = "NA";
		}
		// debug($name);
		return $name;

	}
	
	public function get_approveis_details($is_id)
	{
		$is_tbl = TableRegistry::get("erp_inventory_is_detail");
		$data = $is_tbl->find()->where(["is_id"=>$is_id])->first();
		return $data;
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
	
	public function get_symbolic_stock($project_id,$material_id)
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
				$opening_stock = $this->get_symbolic_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
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
	
	public function vendordetail($vendor_userid)
	{
		$usersdetail = TableRegistry::get('erp_vendor'); 
		$user_data = $usersdetail->find()->where(['user_id'=>$vendor_userid]);
		$result_arr = array();
		foreach($user_data as $retrive_data)
		{
			$result_arr['vendor_id'] = $retrive_data['vendor_id'];			
			$result_arr['address_1'] = $retrive_data['vendor_billing_address'];			
			$result_arr['delivery_place'] = $retrive_data['vendor_billing_address'];		
			$result_arr['contact_no1'] = $retrive_data['contact_no1'];		
			$result_arr['contact_no2'] = $retrive_data['contact_no2'];		
			$result_arr['email_id'] = $retrive_data['email_id'];		
			$result_arr['pancard_no'] = $retrive_data['pancard_no'];		
			$result_arr['gst_no'] = $retrive_data['gst_no'];		
		}
		
		return json_encode($result_arr);
	}
	
	public function getstategstno($state)
	{
		$gst_no = '';
		if($state == 'gujarat')
		{
			$gst_no = '24AAAFY3210E1ZS';
		}
		else if($state == 'mp')
		{
			$gst_no = '23AAAFY3210E1ZU';
		}
		else if($state == 'maharastra')
		{
			$gst_no = '27AAAFY3210E1ZM';
		}
		
		return $gst_no;
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
	
	public function get_pay_type($pay_type)
	{
		$data = '';
		if($pay_type === 'employee')
		{
			$data = 'Employee';
		}
		else if($pay_type === 'consultant')
		{
			$data = 'P.T. Employee';
		}
		else if($pay_type === 'temporary')
		{
			$data = 'Temporary';
		}
		return $data;
	}
	public function get_asset_make($asset_id){
		$erp_asset = TableRegistry::get('erp_assets');
		$results = $erp_asset->get($asset_id);
		$assstmake = $this->get_category_title($results['asset_make']);
		return $assstmake;
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
	
	public function get_wo_mail_status($wo_id)
    {
        $erp_work_order = TableRegistry::get("erp_work_order");
        $row = $erp_work_order->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
        $mail_status = $row[0]["mail_check"];
       
        if(!empty($row))
        {
            return $mail_status;             
        }
        else
        {
            return " - ";
        }
        
    }
	
	public function get_planningwo_mail_status($wo_id)
    {
        $erp_work_order = TableRegistry::get("erp_planning_work_order");
        $row = $erp_work_order->find()->where(["wo_id"=>$wo_id])->hydrate(false)->toArray();
        $mail_status = $row[0]["mail_check"];
       
        if(!empty($row))
        {
            return $mail_status;             
        }
        else
        {
            return " - ";
        }
        
	}
	
	public function add_inventory_debit_detail($debit_item,$debit_id,$total,$total_word)
	{
		$erp_inventory_debit_note_detail = TableRegistry::get('erp_inventory_debit_note_detail'); 
		foreach($debit_item['material_id'] as $key => $data)
		{
			$save_data['debit_id'] =  $debit_id;			
			$save_data['material_id'] =  $debit_item['material_id'][$key];
			$save_data['quantity'] =  $debit_item['quantity'][$key];
			$save_data['rate'] =  $debit_item['rate'][$key];
			$save_data['amount'] =  $debit_item['single_amount'][$key];
			$save_data['total_amount'] =  $total;
			$save_data['total_word'] =  $total_word;
			$req_data = $erp_inventory_debit_note_detail->newEntity();			
			$req_data=$erp_inventory_debit_note_detail->patchEntity($req_data,$save_data);
			$erp_inventory_debit_note_detail->save($req_data);						
		}		
	}
	
	public function get_rbntilldate_quantity($project_id,$rbn_date,$party_id,$material_id,$rbn_id)
	{
		$till_date_quantity = 0;
		$rbn_date = date("Y-m-d",strtotime($rbn_date));
		
		$erp_stock_history = TableRegistry::get('erp_stock_history'); 
		
		/* Get Issued quantity of still date */
		$query = $erp_stock_history->find()->where(['erp_stock_history.project_id'=>$project_id,"erp_stock_history.material_id"=>$material_id,"erp_stock_history.date <="=>$rbn_date,"type"=>"is"])->select(["quantity"]);
		
		$query = $query->innerjoin(
							["erp_inventory_is"=>"erp_inventory_is"],
							["erp_inventory_is.is_id = erp_stock_history.type_id"])
							->where(["erp_inventory_is.agency_name"=>$party_id]);
		$is_data = $query->select(['sum' => $query->func()->sum('quantity')])->hydrate(false)->toArray();
		$is_quantity = $is_data[0]["sum"];
		if($is_quantity != null)
		{
			$till_date_quantity = $is_quantity;
		}
		/* Get Issued quantity of still date */
		
		/* Get RBN quantity of still date */
		$query1 = $erp_stock_history->find()->where(['erp_stock_history.project_id'=>$project_id,"erp_stock_history.material_id"=>$material_id,"erp_stock_history.date <="=>$rbn_date,"type"=>"rbn"])->select(["quantity"]);
		
		$query = $query1->innerjoin(
							["erp_inventory_rbn"=>"erp_inventory_rbn"],
							["erp_inventory_rbn.rbn_id = erp_stock_history.type_id"])
							->where(["erp_inventory_rbn.agency_name"=>$party_id,"erp_inventory_rbn.rbn_id !="=>$rbn_id]);
		$rbn_data = $query1->select(['sum' => $query1->func()->sum('quantity')])->hydrate(false)->toArray();
		$rbn_quantity = $rbn_data[0]["sum"];
		if($rbn_quantity != null)
		{
			$till_date_quantity = $till_date_quantity - $rbn_quantity;
		}
		/* Get RBN quantity of still date */
		
		/* Get Debit quantity of still date */
		$query2 = $erp_stock_history->find()->where(['erp_stock_history.project_id'=>$project_id,"erp_stock_history.material_id"=>$material_id,"erp_stock_history.date <="=>$rbn_date,"type"=>"debit"])->select(["quantity"]);
		
		$query2 = $query2->innerjoin(
							["erp_inventory_debit_note"=>"erp_inventory_debit_note"],
							["erp_inventory_debit_note.debit_id = erp_stock_history.type_id"])
							->where(["erp_inventory_debit_note.debit_to"=>$party_id]);
		$debit_data = $query2->select(['sum' => $query2->func()->sum('quantity')])->hydrate(false)->toArray();
		$debit_quantity = $debit_data[0]["sum"];
		if($debit_quantity != null)
		{
			$till_date_quantity = $till_date_quantity - $debit_quantity;
		}
		/* Get Debit quantity of still date */
		
		return $till_date_quantity;die;
	}
	
	public function is_material_projectspecific($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id))->first();
		if(!empty($results))
		{
			return $results->project_id;
		}else{
			return 0;
		}
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
	
	public function get_breakdown_asset()
	{
		$erp_equipmentown_log = TableRegistry::get("erp_equipmentown_log");
		$ids = $erp_equipmentown_log->find()->where(["working_status"=>"breakdown"])->hydrate(false)->toArray();
		$asset_ids = array();
		if(!empty($ids))
		{		
			foreach($ids as $retrive)
			{
				$asset_ids[] = $retrive["asset_id"];
			}
		}
		return $asset_ids;
	}
	
	public function get_idle_asset()
	{
		$erp_equipmentown_log = TableRegistry::get("erp_equipmentown_log");
		$ids = $erp_equipmentown_log->find()->where(["working_status"=>"idle"])->hydrate(false)->toArray();
		$asset_ids = array();
		if(!empty($ids))
		{		
			foreach($ids as $retrive)
			{
				$asset_ids[] = $retrive["asset_id"];
			}
		}
		return $asset_ids;
	}
	
	public function get_sold_asset()
	{
		$erp_assets_sold_history = TableRegistry::get("erp_assets_sold_history");
		$ids = $erp_assets_sold_history->find()->hydrate(false)->toArray();
		$asset_ids = array();
		if(!empty($ids))
		{		
			foreach($ids as $retrive)
			{
				$asset_ids[] = $retrive["asset_id"];
			}
		}
		return $asset_ids;
	}
	
	public function get_theft_asset()
	{
		$erp_assets_theft_history = TableRegistry::get("erp_assets_theft_history");
		$ids = $erp_assets_theft_history->find()->hydrate(false)->toArray();
		$asset_ids = array();
		if(!empty($ids))
		{		
			foreach($ids as $retrive)
			{
				$asset_ids[] = $retrive["asset_id"];
			}
		}
		return $asset_ids;
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
	
	public function get_user_identity_number()
	{
		$user_tbl = TableRegistry::get("erp_users");
		$available_ids = $user_tbl->find()->select(['user_identy_number'])->hydrate(false)->toArray();
		// debug($available_ids);die;
		
		$available_id = array();
		foreach($available_ids as $id)
		{
			if($id['user_identy_number'] != '')
			{
				$available_id[] = $id['user_identy_number'];
			}
			
		} 
		
		$missing_ids = range(1,max($available_id));                                                    

		$missing = array_diff($missing_ids,$available_id);
			
		if(!empty($missing))
		{ 
			$next_number = reset($missing);
			return $next_number;die;
		}else{
			$query = $user_tbl->find();
			$data = $query
				->select(["user_identy_number" => $query->func()->max('user_identy_number')]);
			$result = $data->first();
			return $result->user_identy_number + 1;die;
		}
		
	}
	
	public function get_concrete_grade_name($id)
	{
		$erp_inventory_mix_design = TableRegistry::get('erp_inventory_mix_design');
		$data = $erp_inventory_mix_design->get($id);
		$concrete_grade = $data->concrete_grade;
		return $concrete_grade;
	}
	
	static function get_asset_last_issueto($asset_id){
		$erp_asset_issued_history = TableRegistry::get('erp_asset_issued_history');
		$row = $erp_asset_issued_history->find()->where(["asset_id"=>$asset_id])->order(['id' => 'desc'])->first();
		if(!empty($row))
		{
			return $row->issued_to;
		}else{
			return "NA";
		}
	}
	
	static function get_asset_release_date($asset_id){
		$erp_assets_history = TableRegistry::get('erp_assets_history');
		$row = $erp_assets_history->find()->where(["asset_id"=>$asset_id])->order(['history_id' => 'desc'])->first();
		if(!empty($row))
		{
			return ($row->release_date != "")?date("d-m-Y",strtotime($row->release_date)):'NA';
		}else{
			return "NA";
		}
	}
	
	public function add_asset_po_detail($material_items,$po_id,$pr_mid = array())
	{
		$erp_asset_po_detail = TableRegistry::get('erp_asset_po_detail'); 
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['po_id'] =  $po_id;		
			$save_data['material_id'] =  $material_items['material_id'][$key];
			if(isset($material_items['static_unit'][$key]))
			{
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
			}
			if(isset($material_items['is_custom'][$key]))
			{
				$save_data['is_custom'] =  $material_items['is_custom'][$key];
			}
			// $save_data['hsn_code'] =  $material_items['hsn_code'][$key];
			$save_data['po_type'] =  "asset_po";
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];
			$save_data['transportation'] =  $material_items['transportation'][$key];
			$save_data['exice'] =  $material_items['exice'][$key];
			$save_data['other_tax'] =  $material_items['other_tax'][$key];
			$save_data['amount'] =  $material_items['amount'][$key];
			$save_data['single_amount'] =  $material_items['single_amount'][$key];
			//$save_data['delivery_date'] =  $this->set_date($material_items['delivery_date'][$key]);
			$pr_material_data = $erp_asset_po_detail->newEntity();			
			$pr_material_data=$erp_asset_po_detail->patchEntity($pr_material_data,$save_data);
			$erp_asset_po_detail->save($pr_material_data);						
		}		
	}
	
	public function edit_asset_po_detail($material_items,$po_id,$po_type)
	{
		$erp_asset_po_detail = TableRegistry::get('erp_asset_po_detail');
		foreach($material_items['material_id'] as $key => $data)
		{
			if(isset($material_items['m_code'][$key]))
			{
				$save_data['m_code'] =  $material_items['m_code'][$key];
			}
			if(isset($material_items['static_unit'][$key]))
			{
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
			}
			if(isset($material_items['is_custom'][$key]))
			{
				$save_data['is_custom'] =  $material_items['is_custom'][$key];
			}
			if(isset($material_items['pr_mid'][$key]))
			{
				$save_data['pr_mid'] =  $material_items['pr_mid'][$key];
			}
			else
			{
				$save_data['pr_mid'] =  0;
			}
			
			if($material_items['unit_rate'][$key] != '')
			{
				$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			}
			else
			{
				$save_data['unit_price'] =  0;
			}
			$save_data['po_id'] =  $po_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];
			$save_data['transportation'] =  $material_items['transportation'][$key];
			$save_data['exice'] =  $material_items['exice'][$key];
			$save_data['other_tax'] =  $material_items['other_tax'][$key];			
			$save_data['amount'] =  $material_items['amount'][$key];
			$save_data['single_amount'] =  $material_items['single_amount'][$key];
			//$save_data['delivery_date'] =  $this->set_date($material_items['delivery_date'][$key]);	
			
			if(isset($material_items['detail_id'][$key]))
			{
				$req_data = $erp_asset_po_detail->get($material_items['detail_id'][$key]);
			}else
			{
				$req_data = $erp_asset_po_detail->newEntity();
				$save_data['po_type'] = $po_type;
			}
						
			$row=$erp_asset_po_detail->patchEntity($req_data,$save_data);
			
			$ok = $erp_asset_po_detail->save($row);
				
		}
	}
	
	public function get_assetpo_party_by_id($po_id)
	{
		$tbl = TableRegistry::get("erp_asset_po");
		$row = $tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		$po_party = "NA";
		if(!empty($row))
		{
			$po_party = $row[0]["vendor_userid"];
		}
		return $this->get_vendor_name($po_party);
	}
	
	public function mail_assetpo_withrate($to,$view_po,$po_no,$po_project,$po_date)
	{
		// $po_no = $this->get_po_no_by_id($view_po);
		$po_project = $this->get_projectname($po_project);
		$po_party = $this->get_assetpo_party_by_id($view_po);
		$url = Router::url('/', true)."assets/printassetporecord/{$view_po}/mail";
		
		//$url = "http://erp.yashnandeng.com/inventory/printapprovedporecord/{$view_po}";
		/* $url = "http://localhost/svn/cakephp/cake_yashnanderp/inventory/printporecord/{$view_po}"; */
		$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Asset Purchase Order (P.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Asset Purchase Order (P.O.) [<strong>P.O. No:</strong> {$po_no}] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>PO NO :</strong> {$po_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$po_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$po_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		 // $email_to = "mansi@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to 
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";					
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$dir_to_save = WWW_ROOT;
		// echo $dir_to_save;die;
		$pdf_name=$this->generate_autoid('assetpo-').time().'.pdf';
		file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n"; 
					 		
							$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  // ->to('vijay.parmar@dasinfomedia.com')
						  ->subject($email_subject)
						  ->attachments(['Purchase Order' => $dir_to_save.$pdf_name])
						  ->send($email_message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function mail_assetpo_withoutrate($to,$view_po,$po_no,$po_project,$po_date)
	{
		
		// $po_no = $this->get_po_no_by_id($view_po);
		$po_project = $this->get_projectname($po_project);
		$po_party = $this->get_assetpo_party_by_id($view_po);
		// $po_date = $this->get_po_date_by_id($view_po);
		
		$url = Router::url('/', true)."assets/printassetporecordnorate/{$view_po}";
		// $url = "http://192.168.1.31/ashvin/cakephp/yashnanderp/inventory/printporecordnorate/{$view_po}";
		$fileatt = "test.pdf"; // Path to the file                  
		// $fileatt = $url; // Path to the file                  
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Asset Purchase Order (P.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Asset Purchase Order (P.O.) [<strong>P.O. No:</strong> {$po_no}] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>PO NO :</strong> {$po_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$po_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$po_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		 // $email_to = "mansi@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to  
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		// $data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$dir_to_save = WWW_ROOT;
		// echo $dir_to_save;die;
		$pdf_name=$this->generate_autoid('po-').time().'.pdf';
		file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n";  
		$email = new Email('default');
		   $email->from("das@gmail.com")
		   ->emailFormat('html')
		  // ->to('vijay.parmar@dasinfomedia.com')
		  ->to($email_to)
		  ->subject($email_subject)
		  ->attachments(['Purchase Order' => $dir_to_save.$pdf_name])
		  ->send($email_message);
			if($email)
			{
				unlink($dir_to_save.$pdf_name);
			}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function get_assetpo_no_by_detailid($po_id)
    {
        $erp_asset_po = TableRegistry::get("erp_asset_po");
        $erp_asset_po_detail = TableRegistry::get("erp_asset_po_detail");
        $row = $erp_asset_po_detail->find()->where(["id"=>$po_id])->hydrate(false)->toArray();
        $po_id1 = $row[0]["po_id"];
        $row1 = $erp_asset_po->find()->where(["po_id"=>$po_id1])->hydrate(false)->toArray();
        if(!empty($row))
        {
            return $row1[0]["po_no"];             
        }
        else
        {
            return " - ";
        }
        
    }
	
	public function get_email_of_pd_pm_cm_by_project_assetpo($project_id,$po_id)
	{
		// $prj_tbl = TableRegistry::get("erp_projects");
		// $user_id = $prj_tbl->find()->where(["project_id"=>$project_id])->select("project_director")->hydrate(false)->toArray();
		$pd_email = "";
		$user_tbl = TableRegistry::get("erp_users");
		// if(!empty($user_id))
		// {
			// $user_id = $user_id[0]["project_director"];			
			// $result = $user_tbl->find()->where(["user_id"=>(int)$user_id])->select("email_id")->hydrate(false)->toArray();
			// if(!empty($result))
			// {
				// $pd_email = $result[0]["email_id"];
			// }
		// }
		
		$asg_tbl = TableRegistry::get("erp_projects_assign");
		$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
		$cm_email = array();
		if(!empty($project_users))
		{  
			$user_tbl = TableRegistry::get("erp_users");
			$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"projectdirector","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids))
			{
				foreach($emailids as $email)
				{
					$cm_email[] = $email["email_id"];
					$cm_email[] = $email["second_email"];
				}
			}
			
			$emailids1 = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"constructionmanager","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids1))
			{
				foreach($emailids1 as $email)
				{
					$cm_email[] = $email["email_id"];
					$cm_email[] = $email["second_email"];
				}
			}
		}	
		
		$cm_emails = $user_tbl->find()->where(["role"=>"purchasemanager","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($cm_emails))
		{
			foreach($cm_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}	
		
		$md_emails = $user_tbl->find()->where(["role"=>"md","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($md_emails))
		{
			foreach($md_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}		
		
		/* $cm_email[] = $pd_email; */
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$po_tbl = TableRegistry::get("erp_asset_po");
		$vendor_id = $po_tbl->find()->where(["po_id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
		$vendor_id = $vendor_id[0]["vendor_userid"];
		$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
		if(!empty($vendor))
		{
			$vendor_email = explode(",",$vendor[0]["email_id"]);
			if(!empty($vendor_email))
			{
				foreach($vendor_email as $em)
				{
					$cm_email[] = $em;
				}
			}
		}
		
		return $cm_email;
	}
	
	public function cancel_assetpo_mail($to,$po_no,$project_name,$party_name)
	{
		//$po_no = $this->get_po_no_by_id($po_id);
		 //$url = "http://erp.yashnandeng.com/inventory/printporecord/{$view_po}/mail";
		//$url = "http://erp.yashnandeng.com/inventory/printapprovedporecord/{$view_po}";
		/* $url = "http://localhost/svn/cakephp/cake_yashnanderp/inventory/printporecord/{$view_po}"; */
		//$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		//$fileatt_type = "application/pdf"; // File Type  
		//$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Cancel - Asset Purchase Order (P.O.) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please consider previously sent Purchase Order (P.O.) [<strong>P.O. No:</strong> {$po_no}] cancelled.</p><br>";
		$email_message .= "<p>Sorry for the inconvenience.</p><br>";
		$email_message .= "<p><strong>PO NO :</strong> {$po_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$project_name}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$party_name}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		//// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		//$file = fopen($url,'rb');  


		//$contents = file_get_contents($url); // read the remote file
		//touch('temp.pdf'); // create a local EMPTY copy
		//file_put_contents('temp.pdf', $contents);


		//$data = fread($file,filesize("temp.pdf"));  
		//// $data = fread($file,19189);  
		//fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		//// $data = chunk_split(base64_encode($data));   
		//$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  //"Content-Type: {$fileatt_type};\n" .  
						  //" name=\"{$fileatt_name}\"\n" .  
						  ////"Content-Disposition: attachment;\n" .  
						  ////" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 //$data .= "\n\n" .  
						  "--{$mime_boundary}--\n";  
						 // $email = new Email('default');
						  // $email->from("das@gmail.com")
						 // ->to($to)
						 // ->subject($email_subject)
						 // ->send($email_message);

			$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  // ->to('vijay.parmar@dasinfomedia.com')
						  ->subject($email_subject)
						  ->send($email_message);			 
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function add_letter_content_detail($material_items,$loi_id)
	{
		$erp_letter_content_detail = TableRegistry::get('erp_letter_content_detail'); 
		foreach($material_items['material_id'] as $key => $data)
		{
			$save_data['loi_id'] =  $loi_id;		
			$save_data['material_id'] =  $material_items['material_id'][$key];
			if(isset($material_items['static_unit'][$key]))
			{
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
			}
			if(isset($material_items['is_custom'][$key]))
			{
				$save_data['is_custom'] =  $material_items['is_custom'][$key];
			}
			// $save_data['hsn_code'] =  $material_items['hsn_code'][$key];
			$save_data['loi_type'] =  "loi";
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];
			$save_data['transportation'] =  $material_items['transportation'][$key];
			$save_data['exice'] =  $material_items['exice'][$key];
			$save_data['other_tax'] =  $material_items['other_tax'][$key];
			$save_data['amount'] =  $material_items['amount'][$key];
			$save_data['single_amount'] =  $material_items['single_amount'][$key];
			//$save_data['delivery_date'] =  $this->set_date($material_items['delivery_date'][$key]);
			$pr_material_data = $erp_letter_content_detail->newEntity();			
			$pr_material_data=$erp_letter_content_detail->patchEntity($pr_material_data,$save_data);
			$erp_letter_content_detail->save($pr_material_data);						
		}		
	}
	
	public function edit_letter_intent_detail($material_items,$loi_id)
	{
		 $erp_letter_content_detail = TableRegistry::get('erp_letter_content_detail'); 
		// $erp_data = $erp_inventory_po_detail->find()->where(['po_id'=>$po_id,'approved'=>0]);
	
		//$key = 0;
		//debug($material_items);die;
		// foreach($erp_data as $save_data)
		// {
			//$rowdata = $data['id'];
			//$save_data = $erp_inventory_po_detail->get($rowdata);
		foreach($material_items['material_id'] as $key => $data)
		{
			if(isset($material_items['m_code'][$key]))
			{
				$save_data['m_code'] =  $material_items['m_code'][$key];
			}
			if(isset($material_items['static_unit'][$key]))
			{
				$save_data['static_unit'] =  $material_items['static_unit'][$key];
			}
			if(isset($material_items['is_custom'][$key]))
			{
				$save_data['is_custom'] =  $material_items['is_custom'][$key];
			}
			if(isset($material_items['pr_mid'][$key]))
			{
				$save_data['pr_mid'] =  $material_items['pr_mid'][$key];
			}
			else
			{
				$save_data['pr_mid'] =  0;
			}
			
			if($material_items['unit_rate'][$key] != '')
			{
				$save_data['unit_price'] =  $material_items['unit_rate'][$key];
			}
			else
			{
				$save_data['unit_price'] =  0;
			}
			$save_data['loi_id'] =  $loi_id;			
			$save_data['material_id'] =  $material_items['material_id'][$key];
			// $save_data['hsn_code'] =  $material_items['hsn_code'][$key];
			$save_data['brand_id'] =  $material_items['brand_id'][$key];
			$save_data['quantity'] =  $material_items['quantity'][$key];
			$save_data['discount'] =  $material_items['discount'][$key];
			$save_data['transportation'] =  $material_items['transportation'][$key];
			$save_data['exice'] =  $material_items['exice'][$key];
			$save_data['other_tax'] =  $material_items['other_tax'][$key];			
			$save_data['amount'] =  $material_items['amount'][$key];
			$save_data['single_amount'] =  $material_items['single_amount'][$key];
			//$save_data['delivery_date'] =  $this->set_date($material_items['delivery_date'][$key]);	
			
			if(isset($material_items['detail_id'][$key]))
			{
				$req_data = $erp_letter_content_detail->get($material_items['detail_id'][$key]);
			}else
			{
				$req_data = $erp_letter_content_detail->newEntity();
			}
						
			$row=$erp_letter_content_detail->patchEntity($req_data,$save_data);
			
			$ok = $erp_letter_content_detail->save($row);
			
			//$key++;	
		}
		//debug($save_data);die;
	}
	
	public function get_loi_party_by_id($loi_id)
	{
		$tbl = TableRegistry::get("erp_letter_content");
		$row = $tbl->find()->where(["id"=>$loi_id])->hydrate(false)->toArray();
		$loi_party = "NA";
		if(!empty($row))
		{
			$loi_party = $row[0]["vendor_userid"];
		}
		return $this->get_vendor_name($loi_party);
	}
	
	public function mail_loi_withrate($to,$view_loi,$loi_no,$loi_project,$loi_date)
	{
		// $po_no = $this->get_po_no_by_id($view_po);
		$loi_project = $this->get_projectname($loi_project);
		$loi_party = $this->get_loi_party_by_id($view_loi);
		// $po_date = $this->get_po_date_by_id($view_po);
		$url = Router::url('/', true)."purchase/printloi/{$view_loi}/mail";
		
		//$url = "http://erp.yashnandeng.com/inventory/printapprovedporecord/{$view_po}";
		/* $url = "http://localhost/svn/cakephp/cake_yashnanderp/inventory/printporecord/{$view_po}"; */
		$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "letter_of_intent.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Letter of Intent (LOI) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Letter of Intent (LOI) [<strong>LOI No:</strong> {$loi_no}] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>LOI NO :</strong> {$loi_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$loi_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$loi_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		 // $email_to = "mansi@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to 
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";					
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$dir_to_save = WWW_ROOT;
		// echo $dir_to_save;die;
		$pdf_name=$this->generate_autoid('loi-').time().'.pdf';
		file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n"; 
					 		
							$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  // ->to('vijay.parmar@dasinfomedia.com')
						  ->subject($email_subject)
						  ->attachments(['Letter of Intent' => $dir_to_save.$pdf_name])
						  ->send($email_message);
							if($email)
							{
								unlink($dir_to_save.$pdf_name);
							}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function mail_loi_withoutrate($to,$view_loi,$loi_no,$loi_project,$loi_date)
	{
		
		// $po_no = $this->get_po_no_by_id($view_po);
		$loi_project = $this->get_projectname($loi_project);
		$loi_party = $this->get_loi_party_by_id($view_loi);
		// $po_date = $this->get_po_date_by_id($view_po);
		$url = Router::url('/', true)."purchase/printloinorate/{$view_loi}";
		
		
		// $url = "http://192.168.1.31/ashvin/cakephp/yashnanderp/inventory/printporecordnorate/{$view_po}";
		$fileatt = "test.pdf"; // Path to the file                  
		// $fileatt = $url; // Path to the file                  
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "letter_of_intent.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Letter of Intent (LOI) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please find Letter of Intent (LOI) [<strong>LOI No:</strong> {$loi_no}] attached herewith. Check the attachments for details.</p>";
		$email_message .= "<p><strong>LOI NO :</strong> {$loi_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$po_project}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$loi_party}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		 // $email_to = "mansi@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to  
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		// $data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$dir_to_save = WWW_ROOT;
		// echo $dir_to_save;die;
		$pdf_name=$this->generate_autoid('loi-').time().'.pdf';
		file_put_contents($dir_to_save.$pdf_name,file_get_contents('temp.pdf'));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n";  
		$email = new Email('default');
		   $email->from("das@gmail.com")
		   ->emailFormat('html')
		  ->to($email_to)
		  // ->to('vijay.parmar@dasinfomedia.com')
		  ->subject($email_subject)
		  ->attachments(['Letter of Intent' => $dir_to_save.$pdf_name])
		  ->send($email_message);
			if($email)
			{
				unlink($dir_to_save.$pdf_name);
			}
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function get_loi_no_by_detailid($loi_id)
    {
        $erp_letter_content = TableRegistry::get("erp_letter_content");
        $erp_letter_content_detail = TableRegistry::get("erp_letter_content_detail");
        $row = $erp_letter_content_detail->find()->where(["id"=>$loi_id])->hydrate(false)->toArray();
        $loi_id1 = $row[0]["loi_id"];
        $row1 = $erp_letter_content->find()->where(["id"=>$loi_id1])->hydrate(false)->toArray();
        if(!empty($row))
        {
            return $row1[0]["loi_no"];             
        }
        else
        {
            return " - ";
        }
        
    }
	
	public function get_email_of_pd_pm_cm_by_project_loi($project_id,$loi_id)
	{
		// $prj_tbl = TableRegistry::get("erp_projects");
		// $user_id = $prj_tbl->find()->where(["project_id"=>$project_id])->select("project_director")->hydrate(false)->toArray();
		$pd_email = "";
		$user_tbl = TableRegistry::get("erp_users");
		// if(!empty($user_id))
		// {
			// $user_id = $user_id[0]["project_director"];			
			// $result = $user_tbl->find()->where(["user_id"=>(int)$user_id])->select("email_id")->hydrate(false)->toArray();
			// if(!empty($result))
			// {
				// $pd_email = $result[0]["email_id"];
			// }
		// }
		
		$asg_tbl = TableRegistry::get("erp_projects_assign");
		$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
		$cm_email = array();
		if(!empty($project_users))
		{  
			$user_tbl = TableRegistry::get("erp_users");
			$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"projectdirector","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids))
			{
				foreach($emailids as $email)
				{
					$cm_email[] = $email["email_id"];
					$cm_email[] = $email["second_email"];
				}
			}
			
			$emailids1 = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"constructionmanager","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids1))
			{
				foreach($emailids1 as $email)
				{
					$cm_email[] = $email["email_id"];
					$cm_email[] = $email["second_email"];
				}
			}
		}	
		
		$cm_emails = $user_tbl->find()->where(["role"=>"purchasemanager","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($cm_emails))
		{
			foreach($cm_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}	
		
		$md_emails = $user_tbl->find()->where(["role"=>"md","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
		if(!empty($md_emails))
		{
			foreach($md_emails as $cemail)
			{
				if($cemail["email_id"] != "")
				{
					$cm_email[] = $cemail["email_id"];
					$cm_email[] = $cemail["second_email"];
				}
			}
		}		
		
		/* $cm_email[] = $pd_email; */
		
		$vendor_tbl = TableRegistry::get("erp_vendor");
		$erp_letter_content = TableRegistry::get("erp_letter_content");
		$vendor_id = $erp_letter_content->find()->where(["id"=>(int)$loi_id])->select(["vendor_userid"])->hydrate(false)->toArray();
		$vendor_id = $vendor_id[0]["vendor_userid"];
		$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
		if(!empty($vendor))
		{
			$vendor_email = explode(",",$vendor[0]["email_id"]);
			if(!empty($vendor_email))
			{
				foreach($vendor_email as $em)
				{
					$cm_email[] = $em;
				}
			}
		}
		
		return $cm_email;
	}
	
	public function cancel_loi_mail($to,$loi_no,$project_name,$party_name)
	{
		//$po_no = $this->get_po_no_by_id($po_id);
		 //$url = "http://erp.yashnandeng.com/inventory/printporecord/{$view_po}/mail";
		//$url = "http://erp.yashnandeng.com/inventory/printapprovedporecord/{$view_po}";
		/* $url = "http://localhost/svn/cakephp/cake_yashnanderp/inventory/printporecord/{$view_po}"; */
		//$fileatt = "test.pdf"; // Path to the file                  
		/* $fileatt = $url; // Path to the file */
		//$fileatt_type = "application/pdf"; // File Type  
		//$fileatt_name = "Purchase_order.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "YashNand <noreply@yashnandeng.com>"; // Who the email is from  
		$email_subject = "YashNand: Cancel - Letter of Intent (LOI) Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p>Please consider previously sent Letter of Intent (LOI) [<strong>LOI No:</strong> {$loi_no}] cancelled.</p><br>";
		$email_message .= "<p>Sorry for the inconvenience.</p><br>";
		$email_message .= "<p><strong>LOI NO :</strong> {$loi_no}.</p>";
		$email_message .= "<p><strong>Project Name :</strong> {$project_name}.</p>";
		$email_message .= "<p><strong>Party Name :</strong> {$party_name}.</p>";
		$email_message .= "<p>Thank You.</p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		$email_message .= "<p>Please <strong>Do Not Reply</strong> to this E-mail ID. This E-mail is system generated and may have some problems. For confirmation and/or queries, please contact:</p>";
		$email_message .= "<p><strong>Contact No: 079-23240202 or +91-8347555616</strong></p>";
		$email_message .= "<p><strong>E-mail ID: </strong><a href='mailto:lalit.vedi@yashnandeng.com'>lalit.vedi@yashnandeng.com</a></p>";
		$email_message .= "---------------------------------------------------------------------------------------------------------------";
		
		
		//// $email_to = "priyal@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to
		$email_to = explode(",",$email_to);
		$email_to = array_filter($email_to, function($value) { return $value !== ''; });
		$email_to = array_filter($email_to, function($value) { return $value !== NULL; });
		$headers = "From: ".$email_from;  
		//$file = fopen($url,'rb');  


		//$contents = file_get_contents($url); // read the remote file
		//touch('temp.pdf'); // create a local EMPTY copy
		//file_put_contents('temp.pdf', $contents);


		//$data = fread($file,filesize("temp.pdf"));  
		//// $data = fread($file,19189);  
		//fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";
		$email_message_old = "";
		$email_message_old .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message_old .= "\n\n";  
		//// $data = chunk_split(base64_encode($data));   
		//$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$email_message_old .= "--{$mime_boundary}\n" .  
						  //"Content-Type: {$fileatt_type};\n" .  
						  //" name=\"{$fileatt_name}\"\n" .  
						  ////"Content-Disposition: attachment;\n" .  
						  ////" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 //$data .= "\n\n" .  
						  "--{$mime_boundary}--\n";  
						 // $email = new Email('default');
						  // $email->from("das@gmail.com")
						 // ->to($to)
						 // ->subject($email_subject)
						 // ->send($email_message);

			$email = new Email('default');
						   $email->from("das@gmail.com")
						   ->emailFormat('html')
						  ->to($email_to)
						  // ->to('vijay.parmar@dasinfomedia.com')
						  ->subject($email_subject)
						  ->send($email_message);			 
		// $ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function get_mail_list_by_project_assetpo($project_id,$po_id,$status,$type)
	{
		$pd_email = "";
		$user_tbl = TableRegistry::get("erp_users");
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		$type="'[".$type."]'";
		$conn = ConnectionManager::get('default');
		$cm_email = array();
		$result = $conn->execute('SELECT role,Alloted FROM `erp_accessrights` WHERE JSON_CONTAINS((JSON_UNQUOTE(`notificationlist`)), '.$type.')')->fetchAll("assoc");
		
		foreach($result as $data){
			
			if($data['Alloted']==1){
				if($status==1 || $status==2){
					
					$asg_tbl = TableRegistry::get("erp_projects_assign");
					$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
					
					if($data['role'] != 'deputymanagerelectric')
					{
						if(!empty($project_users))
						{  
							$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>$data['role'],"status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
							if(!empty($emailids))
							{
								foreach($emailids as $email)
								{
									$cm_email[] = $email["email_id"];
									$cm_email[] = $email["second_email"];
								}
							}
						}
					}
					
				}
			if($data['role'] == 'deputymanagerelectric')
			{			
				if($status==2){
					$asg_tbl = TableRegistry::get("erp_projects_assign");
					$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
					
					if(!empty($project_users))
					{  
						$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"deputymanagerelectric","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
						if(!empty($emailids))
						{
							foreach($emailids as $email)
							{
								$cm_email[] = $email["email_id"];
								$cm_email[] = $email["second_email"];
							}
						}
					}
					
				}
			}
			}
			if($data['Alloted']==0){
				$cm_emails = $user_tbl->find()->where(["role"=>$data['role'],"status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
				if(!empty($cm_emails))
				{
					foreach($cm_emails as $cemail)
					{
						if($cemail["email_id"] != "")
						{
							$cm_email[] = $cemail["email_id"];
							$cm_email[] = $cemail["second_email"];
						}
					}
				}
			}
			
		}
		
		/* $cm_email[] = $pd_email; */
		if($status!=0){
			$vendor_tbl = TableRegistry::get("erp_vendor");
			$po_tbl = TableRegistry::get("erp_asset_po");
			$vendor_id = $po_tbl->find()->where(["po_id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
			$vendor_id = $vendor_id[0]["vendor_userid"];
			$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
			if(!empty($vendor))
			{
				$vendor_email = explode(",",$vendor[0]["email_id"]);
				if(!empty($vendor_email))
				{
					foreach($vendor_email as $em)
					{
						$cm_email[] = $em;
					}
				}
			}
		}
		$cm_email = array_unique($cm_email); /*remove duplicate email ids */		
		$cm_email = array_filter($cm_email, function($value) { return $value !== ''; });
		$cm_email = array_filter($cm_email, function($value) { return $value !== NULL; });
		
		return $cm_email;
	}
	
	public function get_mail_list_by_project_loi($project_id,$po_id,$status,$type)
	{
		$pd_email = "";
		$user_tbl = TableRegistry::get("erp_users");
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		$type="'[".$type."]'";
		$conn = ConnectionManager::get('default');
		$cm_email = array();
		$result = $conn->execute('SELECT role,Alloted FROM `erp_accessrights` WHERE JSON_CONTAINS((JSON_UNQUOTE(`notificationlist`)), '.$type.')')->fetchAll("assoc");
		
		foreach($result as $data){
			
			if($data['Alloted']==1){
				if($status==1 || $status==2){
					
					$asg_tbl = TableRegistry::get("erp_projects_assign");
					$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
					
					if($data['role'] != 'deputymanagerelectric')
					{
						if(!empty($project_users))
						{  
							$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>$data['role'],"status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
							if(!empty($emailids))
							{
								foreach($emailids as $email)
								{
									$cm_email[] = $email["email_id"];
									$cm_email[] = $email["second_email"];
								}
							}
						}
					}
					
				}
			if($data['role'] == 'deputymanagerelectric')
			{			
				if($status==2){
					$asg_tbl = TableRegistry::get("erp_projects_assign");
					$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
					
					if(!empty($project_users))
					{  
						$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"role"=>"deputymanagerelectric","status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
						if(!empty($emailids))
						{
							foreach($emailids as $email)
							{
								$cm_email[] = $email["email_id"];
								$cm_email[] = $email["second_email"];
							}
						}
					}
					
				}
			}
			}
			if($data['Alloted']==0){
				$cm_emails = $user_tbl->find()->where(["role"=>$data['role'],"status !="=>0])->select(["email_id","second_email"])->hydrate(false)->toArray();
				if(!empty($cm_emails))
				{
					foreach($cm_emails as $cemail)
					{
						if($cemail["email_id"] != "")
						{
							$cm_email[] = $cemail["email_id"];
							$cm_email[] = $cemail["second_email"];
						}
					}
				}
			}
			
		}
		
		/* $cm_email[] = $pd_email; */
		if($status!=0){
			$vendor_tbl = TableRegistry::get("erp_vendor");
			$po_tbl = TableRegistry::get("erp_letter_content");
			$vendor_id = $po_tbl->find()->where(["id"=>(int)$po_id])->select(["vendor_userid"])->hydrate(false)->toArray();
			$vendor_id = $vendor_id[0]["vendor_userid"];
			$vendor = $vendor_tbl->find()->where(["user_id"=>$vendor_id])->select(["email_id"])->hydrate(false)->toArray();
			if(!empty($vendor))
			{
				$vendor_email = explode(",",$vendor[0]["email_id"]);
				if(!empty($vendor_email))
				{
					foreach($vendor_email as $em)
					{
						$cm_email[] = $em;
					}
				}
			}
		}
		$cm_email = array_unique($cm_email); /*remove duplicate email ids */		
		$cm_email = array_filter($cm_email, function($value) { return $value !== ''; });
		$cm_email = array_filter($cm_email, function($value) { return $value !== NULL; });
		
		return $cm_email;
	}
	
	public function get_email_of_billingengineer_by_project($project_id)
	{	
		$asg_tbl = TableRegistry::get("erp_projects_assign");
		$project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
		$mmcm = array();
		if(!empty($project_users))
		{  
			$user_tbl = TableRegistry::get("erp_users");
			$emailids = $user_tbl->find()->where(["user_id IN"=>$project_users,"status !="=>0,"OR"=>[["role"=>"billingengineer"]]])->select(["email_id","second_email"])->hydrate(false)->toArray();
			if(!empty($emailids))
			{
				foreach($emailids as $email)
				{
					$mmcm[] = $email["email_id"];
					$mmcm[] = $email["second_email"];
				}
			}
		}
		return $mmcm;
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
	
	public function get_total_stockout($project_id,$material_id)
	{		
		$history_tbl = TableRegistry::get("erp_stock_history");
		// $opening_stock = 0;
		// if(is_numeric($material_id))
		// {
			// $opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		// }
		// else
		// {
			// $opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		// }
		
		// if(!empty($opening_stock_data))
		// {
			// $opening_stock = $opening_stock_data[0]["quantity"];			
		// }
		$opening_stock = 0;
		if(is_numeric($material_id))
		{
		$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type IN"=>array("is","rbn","rmc")])->hydrate(false)->toArray();
		}
		else
		{
			$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id,"type IN"=>array("is","rbn","rmc")])->hydrate(false)->toArray();
		}
		// debug($stockledger);die;
		if(isset($stockledger))
		{
			foreach($stockledger as $retrive_data)
			{
				$opening_stock = $this->get_stockout_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
	}
	
	public function get_stockout_balance($type,$old_stock,$new_stock)
	{		
		switch($type)
		{
			CASE "is":
				return $old_stock + $new_stock;
			break;
			
			CASE "rmc":
				return $old_stock + $new_stock;
			break;
			
			// CASE "mrn":
				// return $old_stock + $new_stock;
			// break;
			
			CASE "rbn":
				return $old_stock + ( - $new_stock);
			break;
			
			// CASE "grn":
				// return $old_stock + $new_stock;
			// break;
			
			// CASE "sst_from":
				// return $old_stock + $new_stock;
			// break;
			// CASE "sst_to":
				// return $old_stock + $new_stock;
			// break;
			
			default :
				return $old_stock + $new_stock;
		}
	}
	
	public function get_material_group_name_by_material($material_id)
	{
		$erp_material = TableRegistry::get("erp_material");
		if($material_id)
		{
			$count = $erp_material->find()->where(["material_id",$material_id])->count();
			
			if($count)
			{
				$material = $erp_material->get($material_id);
				
				$vendor_group = $this->vendor_group();
				if(isset($vendor_group[$material->material_code]['title']))
				{
					return $vendor_group[$material->material_code]['title'];
				}else{
					return "NA";
				}
				return $vendor_group[$material->material_code]['title'];
			}else{
				return "NA";
			}
		}else{
			return "NA";
		}
	}
	
	public function generate_auto_id_subcontractbill($project_id,$party_id,$tbl,$desc_fld,$auto_incr_fld)
	{
		$tbl= TableRegistry::get($tbl);
		// $data = $tbl->find("all")->where(["project_id"=>$project_id])->limit(1)->hydrate(false)->toArray()->max('po_no');
		$query = $tbl->find();
		$data = $query
			->select(["{$auto_incr_fld}" => $query->func()->max($auto_incr_fld)])->where(["project_id"=>$project_id,"party_id"=>$party_id]);
		$result = $data->first();
	
		$po_no = $result->$auto_incr_fld;
		if(!empty($po_no))
		{
			// $auto_fld = $data[0]["{$auto_incr_fld}"];
			$auto_fld = $po_no;
			$split = explode("/",$auto_fld);	
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$last_number = (int) $last_id;
			$new_no = $last_number + 1;		
			/* $new_id = str_ireplace("{$last_number}","{$new_no}",$last_id); */
		}else{
			$new_no = 1;
		}
		return $new_no;
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
}
?>