<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\View\Helper;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenDate;
use DateTime;
use DatePeriod;
use DateInterval;

use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;
/* TEST */
class AjaxfunctionController extends AppController
{


	public function initialize()
    {
		parent::initialize();		
		$this->loadComponent('Flash');
		$this->user_id=$this->request->session()->read('user_id');
		$this->role = $this->Usermanage->get_user_role($this->user_id);
		$this->loadComponent('ERPfunction');
		require_once(ROOT . DS .'vendor' . DS  . 'pendindbills' . DS . 'pendingbill_load_class.php');	
		require_once(ROOT . DS .'vendor' . DS  . 'acceptbills' . DS . 'acceptbill_load_class.php');	
		require_once(ROOT . DS .'vendor' . DS  . 'billrecords' . DS . 'billrecords_load_class.php');	
		require_once(ROOT . DS .'vendor' . DS  . 'grn' . DS . 'viewgrn_load_class.php');		
		require_once(ROOT . DS .'vendor' . DS  . 'assetmaintenance' . DS . 'assetmaintenance_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'equipmentlogown' . DS . 'equipmentlogown_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'prrecords' . DS . 'prrecords_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'purchaseorder' . DS . 'viewpo_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'purchaseammendorder' . DS . 'viewammendpo_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'ponoraterecords' . DS . 'viewponorate_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'workorder' . DS . 'viewwo_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'planningworkorder' . DS . 'viewplanningwo_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'planningammendedworkorder' . DS . 'viewplanningammendwo_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'wonoraterecords' . DS . 'viewwonorate_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'planningwonoraterecords' . DS . 'planning_viewwonorate_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'inventorydebitrecords' . DS . 'debitrecords_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'postatusrecords' . DS . 'viewpostatus_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'podeliveryrecords' . DS . 'viewpodelivery_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'inventorypostatusrecords' . DS . 'viewinventorypostatus_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'inventorypodeliveryrecords' . DS . 'viewinventorypodelivery_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'materiallist' . DS . 'viewmaterial_load_class.php');
		$role = $this->role;
		require_once(ROOT . DS .'vendor' . DS  . 'accountsgrn' . DS . 'viewaccountsgrn_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'assetmanagement' . DS . 'assetmanagement_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'assetrecords' . DS . 'assetrecords_load_class.php');
		$this->set('role',$role);
		require_once(ROOT . DS .'vendor' . DS  . 'inventoryrmc' . DS . 'viewinventoryrmc_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'assetpurchaseorder' . DS . 'viewassetpo_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'letterofintent' . DS . 'viewloi_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'rbn' . DS . 'viewrbn_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'mrn' . DS . 'viewmrn_load_class.php');
		require_once(ROOT . DS .'vendor' . DS  . 'sst' . DS . 'viewsst_load_class.php');
    }

    /* Candidate Ajax Controller*/
	
    public function getcandidate($id=null)
	{
		$this->autoRender = false;
		
		if($this->request->is('ajax'))
		{

			$id = $this->request->data["id"];
			
			/*This Data For Ajax To Fill Form Start*/
			$candidate = TableRegistry::get('erp_candidate');
			$find = $candidate->get($id);
			$result[]=array (
						'first_name'=>$find->first_name,
						'middle_name'=>$find->middle_name,			
						'last_name'=>$find->last_name,
						'date_of_birth'=>$find->date_of_birth,
						'education'=>$find->education,
						'year_of_passing'=>$find->year_of_passing,
						'gender'=>$find->gender,
						'marital_status'=>$find->marital_status,
						'passport_no'=>$find->passport_no,			
						'pan_card_no'=>$find->pan_card_no,
						'driving_licence_no'=>$find->driving_licence_no,
						'adhaar_card_no'=>$find->adhaar_card_no,
						'new_image_nmae'=>$find->new_image_nmae,
						'mobile_no'=>$find->mobile_no,

						'email_id'=>$find->email_id,
						'employee_address'=>$find->employee_address,			
						'interview'=>$find->interview,
						'interview_by'=>$find->interview_by,
						'inerview_grade'=>$find->inerview_grade,
						'interview_comment'=>$find->interview_comment,
						'ctc_year'=>$find->ctc_year,

						'remark'=>$find->remark,
						'aadhar_card_att'=>$find->aadhar_card_att,			
						'pan_card_att'=>$find->pan_card_att,
						'driving_licence_att'=>$find->driving_licence_att,
						'cancel_cheque_att'=>$find->cancel_cheque_att,
						'resume_att'=>$find->resume_att,
						'qualification_doc'=>$find->qualification_doc,
						'other_doc'=>$find->other_doc,
						'attach_label'=>$find->attach_label,
						'attachment'=>$find->attachment
						);
				echo json_encode($result);
		
			/*End Amount History*/
		}
	}


    /*End Candidate Ajax Controller*/

    /*Monthly Bonus Start*/
    public function getMonthly($id=null)
    {
    	$this->autoRender = false;
    	if($this->request->is('ajax'))
    	{
    		$id = $this->request->data['id'];
    	
    		
    		$user = TableRegistry::get('erp_users');
    		$find =  $user->get($id);
			$designation = $this->ERPfunction->get_category_title($find->designation);
			$employee_at = $this->ERPfunction->get_user_employee_at($id);
		//	$employee_at = $this->ERPFunction->get_projectname($find->employee_at);
			
			
    		$result[] = array(
    			'employee_no'=>$find->employee_no,
    			'designation'=>$designation,
    			//'employee_at'=>$find->employee_at,
    			'employee_at'=>$employee_at,
    			'pay_type'=>$find->pay_type,

    		); 
    		echo json_encode($result);
    	}
    }
    /*Monthly Bonus Over*/

    /*Salarey List Start*/
    public function Salareylist($id=null,$date=null)
    {
    	$this->autoRender = false;

    	if($this->request->is('ajax'))
    	{
    		$user_id = $this->request->data['id'];
    		$date = $this->request->data['date'];

    		$year  =(int) date('Y',strtotime($date));
    		$month  = date('m',strtotime($date));

    		$exgrica_tbl = TableRegistry::get('exgrica_record');
			$previous_year = '';
			$current_year = '';

			if($month > '03')
			{
				$previous_year = ($year); //current year
				$current_year = ($year + 1); //next year
			}
			else
			{
				$previous_year = ($year - 1); //Previous year
				$current_year = ($year); //current year
			}
			
			$exgrecia_data = $exgrica_tbl->find()->where(["AND"=>[["user_id"=>$user_id],["OR"=>[["substr(month, 2,1) >"=>3,"Year"=>$previous_year],["substr(month, 2,1) <"=>4,"Year"=>$current_year]]]]])->hydrate(false)->toArray();
			
			$salary_data = array();
			$total_bonus = 0;
			foreach ($exgrecia_data as $record)
			{
				$salary_data[$record['month'].'_'.$record['Year']] = $record['bonus'];
				$total_bonus += $record['bonus'];
			}

			$result['exgracia_data'] = $salary_data;
			$result['total_bonus'] = $total_bonus;
    		echo json_encode($result);
		
    	}
    }
    /*Salarey List End*/

     /*Genrate Date Start*/
    public function getDateformat($date=null)
    {
    	$this->autoRender = false;
    	if($this->request->is('ajax'))
    	{
    		
    		$date = $this->request->data['date'];

 		   	$year  =(int) date('Y',strtotime($date));
    		$month  = date('m',strtotime($date));

    		$previous_year = '';
			$current_year = '';

			if($month > '03')
			{
				$previous_year = ($year); //current year
				$current_year = ($year + 1); //next year
			}
			else
			{
				$previous_year = ($year - 1); //Previous year
				$current_year = ($year); //current year
			}

    		$start = new DateTime($previous_year.'-04-01');
			$interval = new DateInterval('P1M');
			$end = new DateTime($current_year.'-03-31');


		
			$period = new DatePeriod($start, $interval, $end);
			$financial_data = array();
			
			foreach ($period as $dt) 
			{
				$financial_data[$dt->format('m')] = $dt->format('Y');
				// $financial_data['year'][] = $dt->format('Y');
			}

			// debug($financial_data);die;
			// foreach($financial_data as $data)
			// {
				// debug($data);
			// }
			
			echo json_encode($financial_data);
    		
    	}	
    }
    /*Generate Date End*/

    /*Genrate Salarey Data*/
    public function getDateformatMonth()
    {
    	$this->autoRender = false;
    	
    	if($this->request->is('ajax'))
    	{
    		
    		$date = $this->request->data['date'];

 		   	$year  =(int) date('Y',strtotime($date));
    		$month  = date('m',strtotime($date));

    		$previous_year = '';
			$current_year = '';

			if($month > '03')
			{
				$previous_year = ($year); //current year
				$current_year = ($year + 1); //next year
			}
			else
			{
				$previous_year = ($year - 1); //Previous year
				$current_year = ($year); //current year
			}

    		$start = new DateTime($previous_year.'-04-01');
			$interval = new DateInterval('P1M');
			$end = new DateTime($current_year.'-03-31');

			$period = new DatePeriod($start, $interval, $end);
			$financial_data = array();
			
			foreach ($period as $dt) 
			{
				$financial_data[] = $dt->format('m').'_'.$dt->format('Y');  
			}
			
			echo json_encode($financial_data);

    	}
    }

    /*Genrate Salarey Data End*/


   
   /* Category list*/
	public function userlist()
	{
		$users_table = TableRegistry::get('erp_user_role'); 
		$user_list = $users_table->find()->where(['status'=>1])->hydrate(false)->toArray();
		// $user_list = $users_table->find();
		$this->set('user_list',$user_list);
		if($this->request->is('post')){
			$post = $this->request->data();
			//var_dump($_REQUEST); die;
			
		}
		
	}
	
	public function addDesignation()
	{
		$category_master_Table = TableRegistry::get('erp_user_role');
		
		$value = preg_replace("/[^a-zA-Z]/", "",  $_REQUEST['des_name']);
		$value = strtolower($value); 
		$vendor_list = $category_master_Table->find()->where(['value'=>$value])->count();
		
		if($vendor_list < 1){
			$category_master = $category_master_Table->newEntity();
		$category_master->title = $_REQUEST['des_name'];
		
		$category_master->value = $value;
		$category_master->status = 1;
		$category_master->code = "IN";

		if ($result=$category_master_Table->save($category_master)) {
			// The $article entity contains the id now
			$id = $result->id;
		
		
			$row1 = '<tr id="cat-'.$id.'"><td>'.$_REQUEST['des_name'].'</td><td id='.$id.'>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-edit-cat badge badge-info" href="#" id='.$id.'><i class="icon-edit"></i></a></tr>
			
			<tr id="cat-update-'.$id.'" style="display:none; "> 
        		<td><input type="text" name="des_name" value="'.$_REQUEST['des_name'].'" id="category_'.$id.'"></td><td id='.$id.'>
				<a class="btn-cat-update-cancel btn btn-danger"  href="#" id='.$id.'>Cancel</a>
				<a class="btn-cat-update btn btn-primary"  href="#" id='.$id.'>Save</a>
				</td></tr>';
			$row2='<option value="'.$value.'">'.$_REQUEST['des_name'].'</option>';
		
		}
		$array_var[] = $row1;
		$array_var[] = $row2;
		echo json_encode($array_var);
		}
		
		
		die();		
	}
	public function updateDesignation()
	{
		$category_master_Table = TableRegistry::get('erp_user_role');
		
		/* $value = preg_replace("/[^a-zA-Z]/", "",  $_REQUEST['des_name']);
		$value = strtolower($value);
		 */
		 $value = $_REQUEST['des_name'];
		$vendor_list = $category_master_Table->find()->where(['title'=>$value])->count();
		if($vendor_list < 1){
			$category_master = $category_master_Table->get($_REQUEST['id']);
			$category_master->title = $_REQUEST['des_name'];
			
			

			if ($category_master_Table->save($category_master)) {
				// The $article entity contains the id now
				$id = $category_master->cat_id;
			}
			
				$row1 = '<td>'.$_REQUEST['des_name'].'</td><td id="'.$_REQUEST['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-edit-cat badge badge-info"  id="'.$_REQUEST['id'].'"><i class="icon-edit"></i></a><td>';
			
			$row2=$category_master->value;
			$array_var[] = $row1;
			$array_var[] = $row2;
		echo json_encode($array_var);
		}
		else{
			echo "";
		}
		
		
		die();		
	}

	// Get workDescriptionList 
	public function getworkdescriptionlist() {
		$categoryMasterTable = TableRegistry::get('erp_category_master');
		$erpWorkGroup = $categoryMasterTable->find("list",["keyField"=>"cat_id","valueField"=>"category_title"])->where(['type'=>'subcontractbill_option']);
		$this->set('erpWorkGroup',$erpWorkGroup);
		$model = $_REQUEST['type'];
		$this->set("type",$model);
	}

	// enanle project in work description
	public function enableworkdescriptioninproject() {
		$this->autoRender = false;
		$enableWorkDescription = $_REQUEST['enableDescription'];
		$projectId = $_REQUEST['project_id'];
		$categoryMasterTable = TableRegistry::get('erp_category_master');
		$row = $categoryMasterTable->get($enableWorkDescription);
		if(!empty($row)) {				
			// Project Enable
			$oldProject = json_decode($row->project_id);
			$newProject = array((string)$projectId);
			$mergedProject = array_merge($oldProject,$newProject);
			$mergedProject = array_unique($mergedProject);
			$formattedProject = json_encode($mergedProject);

			$row->project_id = $formattedProject;

			// Save new project id merged in description
			$save = $categoryMasterTable->save($row);
		}
		echo "Completed";
		die;
	}

	// check project inArray or not and append in work_description dropdown
	public function enableprojectappend() {
		$projectId = $_REQUEST['project_id'];
		$enableDescription = $_REQUEST['enableDescription'];
		// $woId = $_REQUEST['woId'];

		$erpCategoryMasterTable = TableRegistry::get('erp_category_master');
		$erpPlanningWorkOrderDetails = TableRegistry::get('erp_planning_work_order_detail');
		// $workOrderData = $erpPlanningWorkOrderDetails->find()->where(['wo_id'=>$woId])->hydrate(false)->toArray();
		$erpWorkGroup = $erpCategoryMasterTable->find()->where(['type'=>'subcontractbill_option','cat_id' => $enableDescription])->hydrate(false);
		foreach($erpWorkGroup as $retrive_data) {
			if(in_array($projectId,json_decode($retrive_data['project_id']))) {
				$description_list = ""; 
				if(!empty($retrive_data)) {
					$description_list .= "<option value='{$retrive_data['cat_id']}'>{$retrive_data['category_title']}</option>";
				}
			}
		}
		// Encoding array to JSON Format
		echo json_encode($description_list);die;
	}
	
	public function appendtypeofcontract() {
		$this->autoRender = false;
		$selectedValue = $_REQUEST['selectedValue'];
		$erp_work_head = TableRegistry::get('erp_planning_work_head'); 
		$head_list = $erp_work_head->find()->where(["work_head_id" => $selectedValue])->hydrate(false)->toArray();
		foreach($head_list as $headList) {
			$type_of_contract = $headList['type_of_contract'];
			$contract_list = $this->ERPfunction->contract_type_list();
			foreach($contract_list as $retrive_data) {
				if($retrive_data['id'] == $type_of_contract){
					echo '<option value="'.$retrive_data['id'].'">'.
					$retrive_data['title'].'</option>';
				}
			}
		}
	}

	public function getprojectbasedworkdescription() {
		$this->autoRender = false;
		$projectId = $_REQUEST['projectId'];
		$descriptionOptions = "";
		$tableCategory = TableRegistry::get("erp_category_master");
		$descriptionValue = $tableCategory->find()->where(['type' => "subcontractbill_option"])->select(["cat_id","category_title","project_id"])->hydrate(false)->toArray();
		foreach($descriptionValue as $data) {
			$formattedProject = json_decode($data['project_id']);
			if($projectId != '' && $formattedProject != '') {
				if(in_array($projectId,$formattedProject)) {
					$conn = ConnectionManager :: get('default');
					$descriptionOptions = $conn->execute("SELECT cat_id,category_title FROM `erp_category_master` WHERE JSON_CONTAINS(`project_id`,'\"$projectId\"')")->fetchAll("assoc");
					// debug($descriptionOptions);die;
				}
			}
		}
		foreach($descriptionOptions as $data) {
			echo '<option value="'.$data['cat_id'].'">'. $data['category_title'].'</option>';
		}
	}

	public function categorylist() {
		$model = $_REQUEST['type'];
		switch($model)
		{		
			case 'unit':
				$title = __("Unit");
				$table_header_title =  __("Unit Name");
				$button_text=  __("Add Unit");
				$label_text =  __("Unit Name");
				break;
			case 'designation':
				$title = __("Designation");
				$table_header_title =  __("Designation Name");
				$button_text=  __("Add Designation");
				$label_text =  __("Designation Name");
				break;
			case 'make_in':
				$title = __("Make in");
				$table_header_title =  __("Make in Name");
				$button_text=  __("Add Make in");
				$label_text =  __("Make in Name");
				break;
			case 'department':
				$title = __("Department");
				$table_header_title =  __("Department Name");
				$button_text=  __("Add Department");
				$label_text =  __("Department Name");
				break;
			case 'subcontractbill_option':
				$title = __("Description");
				$table_header_title =  __("Description");
				$button_text=  __("Add Description");
				$label_text =  __("Description");
				break;
			default:
				$title = "Title here";
				$table_header_title ="Table head";
				$button_text= "Button Text"; 
				$label_text = "Label Text";			
		}		
		$category_master_Table = TableRegistry::get('erp_category_master');
		if($model == 'subcontractbill_option')
		{
			$result = $category_master_Table->find()->where(['type'=>$model,'project_id'=>$_REQUEST['project_id']]);
		}else{
			$result = $category_master_Table->find()->where(['type'=>$model]);
		}
		
		if($model == 'subcontractbill_option')
		{
			$project_id = $_REQUEST['project_id'];
			$this->set('project_id',$project_id);
		}
		$categoryMasterTable = TableRegistry::get('erp_work_group');
		$erpWorkGroup = $categoryMasterTable->find()->where(['type'=>'subcontractbill_option']);
		$this->set('erpWorkGroup',$erpWorkGroup);

		$this->set('cat_result',$result);		
		$this->set('title',$title);
		$this->set('table_header_title',$table_header_title);
		$this->set('button_text',$button_text);
		$this->set('label_text',$label_text);
		$this->set('model',$model);
	}

	// Get WorkSubGroup dropdown data
	public function getworksubgroup() {
		$material_code = $_REQUEST['material_code'];
		$erpCategoryMaster = TableRegistry::get('erp_work_sub_group');
		$worSubGrups = $erpCategoryMaster->find()->where(["work_group_id"=>$material_code])->hydrate(false)->toArray();
		
		$content = '<option>-- Sub Category --</option>';
		foreach($worSubGrups as $data) {
			// $content .= '<option>-- Sub Category --</option>';
			$content .= '<option value ="'.$data['sub_work_group_id'].'">'.$data['sub_work_group_title'].'</option>';
		}
		echo $content;die;
	}
		
	public function editcategory()
	{		
		$cat_id = $_REQUEST['cat_id'];
		$model = $_REQUEST['model'];
		$cat_tbl = TableRegistry::get('erp_category_master');
		$retrieved_data = $cat_tbl->get($cat_id);
		
		//echo '<td>'.$i.'</td>';
		echo '<td><input type="text" name="term_name" value="'.$retrieved_data->category_title.'" id="cat_name"></td>';
		if($model == "designation")
		{
			echo '<td><input type="text" name="term_category" value="'.$retrieved_data->category.'" id="category"></td>';
		}
		if($model == "subcontractbill_option")
		{
			echo '<td></td>';
			echo '<td><input type="text" name="unit" value="'.$retrieved_data->unit.'" id="unit"></td>';
		}
		echo '<td id='.$retrieved_data->cat_id.'>
		<a class="btn-cat-update-cancel btn btn-danger" model ='.$model.' href="#" id='.$retrieved_data->cat_id.'>Cancel</a>
		<a class="btn-cat-update btn btn-primary" model ='.$model.' href="#" id='.$retrieved_data->cat_id.'>Save</a>
		</td>';
		die();
	}
	
	public function cancelcatsave()
	{
		$cat_id = $_REQUEST['cat_id'];
		$model = $_REQUEST['model'];
		$cat_tbl = TableRegistry::get('erp_category_master');
		$retrieved_data = $cat_tbl->get($cat_id);
		
		echo '<td>'.$retrieved_data->category_title .'</td>';
		if($model == "designation")
		{
			echo '<td>'.strtoupper($retrieved_data->category).'</td>';
		}
		if($model == "subcontractbill_option")
		{
			echo '<td>'.$this->ERPfunction->get_projectname($retrieved_data->project_id).'</td>';
			echo '<td>'.$retrieved_data->unit.'</td>';
		}
		echo '<td id='.$retrieved_data->cat_id .'>
		<a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data->cat_id.'><i class="icon-trash"></i></a>';
		if($model == "unit" || $model == "designation" || $model == "department" || $model == "subcontractbill_option")
		{
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-edit-cat badge badge-info" model='.$model.' href="#" id='.$retrieved_data->cat_id.'><i class="icon-edit"></i></a>';
		}
		echo '</td>';
		die();
	}
	
	public function updatecategory()
	{
		$cat_id = $_REQUEST['cat_id'];
		$model = $_REQUEST['model'];
		$cat_name = $_REQUEST['cat_name'];
		$unit = isset($_REQUEST['unit'])?$_REQUEST['unit']:'';
		$cat_tbl = TableRegistry::get('erp_category_master');
		$retrieved_data = $cat_tbl->get($cat_id);
		$project_id = $retrieved_data->project_id;
		$retrieved_data->category_title = $cat_name;
		if($model == "designation")
		{
			$retrieved_data->category = strtolower($_REQUEST['category']);
		}
		$retrieved_data->category_title = $cat_name;
		if($model == "subcontractbill_option")
		{
			$retrieved_data->unit = $unit;
		}
		$ok = $cat_tbl->save($retrieved_data);
		if($model == "designation")
		{
			if($ok)
			{
				$erp_users = TableRegistry::get('erp_users');
				$query = $erp_users->query();
				$query->update()
				->set(['category'=>strtolower($_REQUEST['category'])])
				->where(['designation' => $cat_id])
				->execute();
			}
		}
		
		echo "<td>{$cat_name}</td>";
		if($model == "designation")
		{
			echo "<td>{$_REQUEST['category']}</td>";
		}
		if($model == "subcontractbill_option")
		{
			echo "<td>{$this->ERPfunction->get_projectname($project_id)}</td>";
			echo "<td>{$retrieved_data->unit}</td>";
		}
		echo '<td><a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$cat_id.'><i class="icon-trash"></i></a>';
		if($model == "unit" || $model == "designation" || $model == "department" || $model == "subcontractbill_option")
		{
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-edit-cat badge badge-info" model='.$model.' href="#" id='.$retrieved_data['cat_id'].'><i class="icon-edit"></i></a>';
		}
		die();
		
	}
	
	public function materialitemgroup()
	{		
		$erp_vendor_groups = TableRegistry::get('erp_vendor_groups'); 
		$result = $erp_vendor_groups->find();
		$this->set('groups',$result);
	}

	public function addinwardagencyname(){
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$row = $erp_vendor_groups->newEntity();
		$row->type = $_REQUEST['item_code'];
		$row->category_title = $_REQUEST['item_name'];
		$row->status= 1;
		$row->created_date = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');
		if ($erp_vendor_groups->save($row)) {
			// The $article entity contains the id now
			$id = $row->cat_id;
		}
		// $id = '12';
		
		$row1 = "<tr id='cat-$id'>";
		// $row1 .= '<td>'.$_REQUEST['item_code'] .'</td>';
		$row1 .= '<td>'.$_REQUEST['item_name'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';
		
		$option = "<option value='$id'>".$_REQUEST['item_name']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}

	public function getinwardagencyname()
	{		
		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$result = $erp_agency_name->find()->where(["type"=>"inward_agency"]);
		$this->set('groups',$result);
	}

	public function editinwardagencyname()
	{		
		$cat_id = $_REQUEST['group_id'];
		
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($cat_id);
		//echo '<td>'.$i.'</td>';
		// echo '<td><input type="text" name="term_code" value="'.$retrieved_data->code.'" id="group_code"></td>';
		echo '<td><input type="text" name="term_title" value="'.$retrieved_data->category_title.'" id="group_title"></td>';
		echo '<td id='.$retrieved_data->cat_id.'>
		<a class="btn-group-update-cancel btn btn-danger" href="#" id='.$retrieved_data->cat_id.'>Cancel</a>
		<a class="btn-group-update btn btn-primary" href="#" id='.$retrieved_data->cat_id.'>Save</a>
		</td>';
		die();
	}

	public function updateinwardagency() {
		$group_id = $_REQUEST['group_id'];
		$group_title = $_REQUEST['group_title'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		$retrieved_data->category_title = $group_title;
		$erp_vendor_groups->save($retrieved_data);
		echo "<td>{$group_title}</td>";
		echo "<td id=".$retrieved_data->cat_id."><a class='btn-edit-item badge badge-info' model='material_group' href='#' id=".$retrieved_data->cat_id."><i class='icon-edit'></i></a>";
		echo '</td>';
		die();
	}

	public function cancelinwardagencysave()
	{
		$group_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		
		// echo '<td>'.$retrieved_data->code .'</td>';
		echo '<td>'.$retrieved_data->category_title .'</td>';
		echo '<td id='.$retrieved_data['id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}

	public function addinwardwrittenby(){
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$row = $erp_vendor_groups->newEntity();
		$row->type = $_REQUEST['item_code'];
		$row->category_title = $_REQUEST['item_name'];
		$row->status= 1;
		$row->created_date = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');
		if ($erp_vendor_groups->save($row)) {
			// The $article entity contains the id now
			$id = $row->cat_id;
		}
		// $id = '12';
		
		$row1 = "<tr id='cat-$id'>";
		// $row1 .= '<td>'.$_REQUEST['item_code'] .'</td>';
		$row1 .= '<td>'.$_REQUEST['item_name'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-item badge badge-info" model="written_by" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';
		
		$option = "<option value='$id'>".$_REQUEST['item_name']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}

	public function getinwardwrittenby()
	{
		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$result = $erp_agency_name->find()->where(["type"=>"inward_writtenby"]);
		$this->set('writtenby',$result);
	}

	public function editinwardwrittenby()
	{		
		$cat_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($cat_id);
		
		//echo '<td>'.$i.'</td>';
		// echo '<td><input type="text" name="term_code" value="'.$retrieved_data->code.'" id="group_code"></td>';
		echo '<td><input type="text" name="term_title" value="'.$retrieved_data->category_title.'" id="group_title"></td>';
		echo '<td id='.$retrieved_data->cat_id.'>
		<a class="btn-writttenby-update-cancel btn btn-danger" href="#" id='.$retrieved_data->cat_id.'>Cancel</a>
		<a class="btn-writtenby-update btn btn-primary" href="#" id='.$retrieved_data->cat_id.'>Save</a>
		</td>';
		die();
	}

	public function updateinwardwrittenby() {
		$group_id = $_REQUEST['group_id'];
		$group_title = $_REQUEST['group_title'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		$retrieved_data->category_title = $group_title;
		$erp_vendor_groups->save($retrieved_data);
		echo "<td>{$group_title}</td>";
		echo "<td id=".$retrieved_data->cat_id."><a class='btn-edit-item badge badge-info' model='material_group' href='#' id=".$retrieved_data->cat_id."><i class='icon-edit'></i></a>";
		echo '</td>';
		die();
	}

	public function cancelinwardwrittenby()
	{
		$group_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		
		// echo '<td>'.$retrieved_data->code .'</td>';
		echo '<td>'.$retrieved_data->category_title .'</td>';
		echo '<td id='.$retrieved_data['cat_id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['cat_id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}
	// Designation Start
	public function addinwarddesignation(){
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$row = $erp_vendor_groups->newEntity();
		$row->type = $_REQUEST['item_code'];
		$row->category_title = $_REQUEST['item_name'];
		$row->status= 1;
		$row->created_date = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');
		if ($erp_vendor_groups->save($row)) {
			// The $article entity contains the id now
			$id = $row->cat_id;
		}
		// $id = '12';
		
		$row1 = "<tr id='cat-$id'>";
		// $row1 .= '<td>'.$_REQUEST['item_code'] .'</td>';
		$row1 .= '<td>'.$_REQUEST['item_name'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-item badge badge-info" model="written_by" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';
		
		$option = "<option value='$id'>".$_REQUEST['item_name']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}

	public function getinwarddesignation()
	{
		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$result = $erp_agency_name->find()->where(["type"=>"inward_designation"]);
		$this->set('designation',$result);
	}

	public function editinwarddesignation()
	{		
		$cat_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($cat_id);
		
		//echo '<td>'.$i.'</td>';
		// echo '<td><input type="text" name="term_code" value="'.$retrieved_data->code.'" id="group_code"></td>';
		echo '<td><input type="text" name="term_title" value="'.$retrieved_data->category_title.'" id="group_title"></td>';
		echo '<td id='.$retrieved_data->cat_id.'>
		<a class="btn-designation-update-cancel btn btn-danger" href="#" id='.$retrieved_data->cat_id.'>Cancel</a>
		<a class="btn-designation-update btn btn-primary" href="#" id='.$retrieved_data->cat_id.'>Save</a>
		</td>';
		die();
	}

	public function updateinwarddesignation() {
		$group_id = $_REQUEST['group_id'];
		$group_title = $_REQUEST['group_title'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		$retrieved_data->category_title = $group_title;
		$erp_vendor_groups->save($retrieved_data);
		echo "<td>{$group_title}</td>";
		echo "<td id=".$retrieved_data->cat_id."><a class='btn-edit-item badge badge-info' model='material_group' href='#' id=".$retrieved_data->cat_id."><i class='icon-edit'></i></a>";
		echo '</td>';
		die();
	}

	public function cancelinwarddesignation()
	{
		$group_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		
		// echo '<td>'.$retrieved_data->code .'</td>';
		echo '<td>'.$retrieved_data->category_title .'</td>';
		echo '<td id='.$retrieved_data['cat_id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['cat_id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}
	// Designation End
	public function editmaterialgroup()
	{		
		$group_id = $_REQUEST['group_id'];	
		$erp_vendor_groups = TableRegistry::get('erp_vendor_groups');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		
		//echo '<td>'.$i.'</td>';
		echo '<td><input type="text" name="term_code" value="'.$retrieved_data->code.'" id="group_code"></td>';
		echo '<td><input type="text" name="term_title" value="'.$retrieved_data->title.'" id="group_title"></td>';
		echo '<td id='.$retrieved_data->cat_id.'>
		<a class="btn-group-update-cancel btn btn-danger" href="#" id='.$retrieved_data->id.'>Cancel</a>
		<a class="btn-group-update btn btn-primary" href="#" id='.$retrieved_data->id.'>Save</a>
		</td>';
		die();
	}

	public function addoutwardagencyname(){
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$row = $erp_vendor_groups->newEntity();
		$row->type = $_REQUEST['item_code'];
		$row->category_title = $_REQUEST['item_name'];
		$row->status= 1;
		$row->created_date = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');
		if ($erp_vendor_groups->save($row)) {
			// The $article entity contains the id now
			$id = $row->cat_id;
		}
		// $id = '12';
		
		$row1 = "<tr id='cat-$id'>";
		// $row1 .= '<td>'.$_REQUEST['item_code'] .'</td>';
		$row1 .= '<td>'.$_REQUEST['item_name'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';
		
		$option = "<option value='$id'>".$_REQUEST['item_name']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}

	public function getoutwardagencyname()
	{		
		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$result = $erp_agency_name->find()->where(["type"=>"outward_agency"]);
		$this->set('outward_agency',$result);
	}

	public function editoutwardagencyname()
	{		
		$cat_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($cat_id);
		
		//echo '<td>'.$i.'</td>';
		// echo '<td><input type="text" name="term_code" value="'.$retrieved_data->code.'" id="group_code"></td>';
		echo '<td><input type="text" name="term_title" value="'.$retrieved_data->category_title.'" id="group_title"></td>';
		echo '<td id='.$retrieved_data->cat_id.'>
		<a class="btn-group-update-cancel btn btn-danger" href="#" id='.$retrieved_data->cat_id.'>Cancel</a>
		<a class="btn-group-update btn btn-primary" href="#" id='.$retrieved_data->cat_id.'>Save</a>
		</td>';
		die();
	}

	public function updateoutwardagency() {
		$group_id = $_REQUEST['group_id'];
		$group_title = $_REQUEST['group_title'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		$retrieved_data->category_title = $group_title;
		$erp_vendor_groups->save($retrieved_data);
		echo "<td>{$group_title}</td>";
		echo "<td id=".$retrieved_data->cat_id."><a class='btn-edit-item badge badge-info' model='material_group' href='#' id=".$retrieved_data->cat_id."><i class='icon-edit'></i></a>";
		echo '</td>';
		die();
	}

	public function canceloutwardagencysave()
	{
		$group_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		
		// echo '<td>'.$retrieved_data->code .'</td>';
		echo '<td>'.$retrieved_data->category_title .'</td>';
		echo '<td id='.$retrieved_data['id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}
	// Outward Written by start
	public function addoutwardwrittenby(){
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$row = $erp_vendor_groups->newEntity();
		$row->type = $_REQUEST['item_code'];
		$row->category_title = $_REQUEST['item_name'];
		$row->status= 1;
		$row->created_date = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');
		if ($erp_vendor_groups->save($row)) {
			// The $article entity contains the id now
			$id = $row->cat_id;
		}
		// $id = '12';
		
		$row1 = "<tr id='cat-$id'>";
		// $row1 .= '<td>'.$_REQUEST['item_code'] .'</td>';
		$row1 .= '<td>'.$_REQUEST['item_name'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-item badge badge-info" model="written_by" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';
		
		$option = "<option value='$id'>".$_REQUEST['item_name']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}

	public function getoutwardwrittenby()
	{
		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$result = $erp_agency_name->find()->where(["type"=>"outward_writtenby"]);
		$this->set('writtenby',$result);
	}

	public function editoutwardwrittenby()
	{		
		$cat_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($cat_id);
		
		//echo '<td>'.$i.'</td>';
		// echo '<td><input type="text" name="term_code" value="'.$retrieved_data->code.'" id="group_code"></td>';
		echo '<td><input type="text" name="term_title" value="'.$retrieved_data->category_title.'" id="group_title"></td>';
		echo '<td id='.$retrieved_data->cat_id.'>
		<a class="btn-writttenby-update-cancel btn btn-danger" href="#" id='.$retrieved_data->cat_id.'>Cancel</a>
		<a class="btn-writtenby-update btn btn-primary" href="#" id='.$retrieved_data->cat_id.'>Save</a>
		</td>';
		die();
	}

	public function updateoutwardwrittenby() {
		$group_id = $_REQUEST['group_id'];
		$group_title = $_REQUEST['group_title'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		$retrieved_data->category_title = $group_title;
		$erp_vendor_groups->save($retrieved_data);
		echo "<td>{$group_title}</td>";
		echo "<td id=".$retrieved_data->cat_id."><a class='btn-edit-item badge badge-info' model='material_group' href='#' id=".$retrieved_data->cat_id."><i class='icon-edit'></i></a>";
		echo '</td>';
		die();
	}

	public function canceloutwardwrittenby()
	{
		$group_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		
		// echo '<td>'.$retrieved_data->code .'</td>';
		echo '<td>'.$retrieved_data->category_title .'</td>';
		echo '<td id='.$retrieved_data['cat_id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['cat_id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}

	// Designation Start
	public function addoutwarddesignation(){
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$row = $erp_vendor_groups->newEntity();
		$row->type = $_REQUEST['item_code'];
		$row->category_title = $_REQUEST['item_name'];
		$row->status= 1;
		$row->created_date = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');
		if ($erp_vendor_groups->save($row)) {
			// The $article entity contains the id now
			$id = $row->cat_id;
		}		
		$row1 = "<tr id='cat-$id'>";
		$row1 .= '<td>'.$_REQUEST['item_name'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-item badge badge-info" model="written_by" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';
		$option = "<option value='$id'>".$_REQUEST['item_name']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}

	public function getoutwarddesignation()
	{
		$erp_agency_name = TableRegistry::get('erp_category_master'); 
		$result = $erp_agency_name->find()->where(["type"=>"outward_designation"]);
		$this->set('designation',$result);
	}

	public function editoutwarddesignation()
	{		
		$cat_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($cat_id);
		
		//echo '<td>'.$i.'</td>';
		// echo '<td><input type="text" name="term_code" value="'.$retrieved_data->code.'" id="group_code"></td>';
		echo '<td><input type="text" name="term_title" value="'.$retrieved_data->category_title.'" id="group_title"></td>';
		echo '<td id='.$retrieved_data->cat_id.'>
		<a class="btn-designation-update-cancel btn btn-danger" href="#" id='.$retrieved_data->cat_id.'>Cancel</a>
		<a class="btn-designation-update btn btn-primary" href="#" id='.$retrieved_data->cat_id.'>Save</a>
		</td>';
		die();
	}

	public function updateoutwarddesignation() {
		$group_id = $_REQUEST['group_id'];
		$group_title = $_REQUEST['group_title'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		$retrieved_data->category_title = $group_title;
		$erp_vendor_groups->save($retrieved_data);
		echo "<td>{$group_title}</td>";
		echo "<td id=".$retrieved_data->cat_id."><a class='btn-edit-item badge badge-info' model='material_group' href='#' id=".$retrieved_data->cat_id."><i class='icon-edit'></i></a>";
		echo '</td>';
		die();
	}

	public function canceloutwarddesignation()
	{
		$group_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_category_master');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		
		// echo '<td>'.$retrieved_data->code .'</td>';
		echo '<td>'.$retrieved_data->category_title .'</td>';
		echo '<td id='.$retrieved_data['cat_id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['cat_id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}
	// Designation End

	public function cancelgroupsave()
	{
		$group_id = $_REQUEST['group_id'];
		$erp_vendor_groups = TableRegistry::get('erp_vendor_groups');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		
		echo '<td>'.$retrieved_data->code .'</td>';
		echo '<td>'.$retrieved_data->title .'</td>';
		echo '<td id='.$retrieved_data['id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}
	
	public function updatematerialitem()
	{
		$group_id = $_REQUEST['group_id'];
		$group_code = $_REQUEST['group_code'];
		$group_title = $_REQUEST['group_title'];
		$erp_vendor_groups = TableRegistry::get('erp_vendor_groups');
		$retrieved_data = $erp_vendor_groups->get($group_id);
		$retrieved_data->code = $group_code;
		$retrieved_data->title = $group_title;
		$erp_vendor_groups->save($retrieved_data);
		
		echo "<td>{$group_code}</td>";
		echo "<td>{$group_title}</td>";
		echo '<td id='.$retrieved_data['id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['id'].'><i class="icon-edit"></i></a>';
				
				
				echo '</td>';
		die();
		
	}

	public function getworkgroup() {
		$erp_work_group = TableRegistry::get('erp_work_group');
		$descriptions = $erp_work_group->find()->where(['type'=>'subcontractbill_option']);
		$this->set('descriptions',$descriptions);
	}

	//Add WorkGroup Method 
	public function addworkgroup() {
		$erp_category_master = TableRegistry::get('erp_work_group');
		$row = $erp_category_master->newEntity();
		$row->work_group_title = $_REQUEST['work_group'];
		$row->type = 'subcontractbill_option';
		// $row->created_date = date('Y-m-d H:i:s');
		// $row->created_by = $this->request->session()->read('user_id');
		if ($erp_category_master->save($row)) {
			// The $row entity contains the id now
			$id = $row->work_group_id;
		}
		$row1 = '<tr id="cat-"'.$id.'">';
		$row1 .= '<td>'.$_REQUEST['work_group'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-workgroup badge badge-info" model="material_group" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';		
		$option = "<option value='$id'>".$_REQUEST['work_group']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}

	//Edit Work Group method 
	public function editworkgroup() {
		$group_id = $_REQUEST['group_id'];	
		$erp_work_group = TableRegistry::get('erp_work_group');
		$retrieved_data = $erp_work_group->get($group_id);
		echo '<td><input type="text" name="work-group" value="'.$retrieved_data->work_group_title.'" id="work-group"></td>';
		echo '<td id='.$retrieved_data->work_group_id.'>
		<a class="btn-workgroup-update-cancel btn btn-danger" href="#" id='.$retrieved_data->work_group_id.'>Cancel</a>
		<a class="btn-workgroup-update btn btn-primary" href="#" id='.$retrieved_data->work_group_id.'>Save</a>
		</td>';
		die();
	}

	// Cancel workkgroup method
	public function cancelworkgroupsave() {
		$group_id = $_REQUEST['group_id'];
		$erp_work_group = TableRegistry::get('erp_work_group');
		$retrieved_data = $erp_work_group->get($group_id);
		echo '<td>'.$retrieved_data->work_group_title .'</td>';
		echo '<td id='.$retrieved_data['work_group_id'].'><a class="btn-edit-workgroup badge badge-info" model="material_group" href="#" id='.$retrieved_data['work_group_id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}

	// Update WorkGroup Item
	public function updateworkgroupitem() {
		$group_id = $_REQUEST['workGroupId'];
		$group_title = $_REQUEST['workGroupTitle'];
		$erp_work_group = TableRegistry::get('erp_work_group');
		$retrieved_data = $erp_work_group->get($group_id);
		$retrieved_data->work_group_title = $group_title;
		$erp_vendor_groups->save($retrieved_data);
		echo "<td>{$group_title}</td>";
		echo '<td id='.$retrieved_data['work_group_id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['work_group_id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}

	// Get SubWorkGroup 
	public function getsubworkgroup() {
		$cat_id = $_REQUEST['material_code'];
		$erpWorkSubGroup = TableRegistry::get('erp_work_sub_group');
		$descriptions = $erpWorkSubGroup->find()->where(["work_group_id"=> $cat_id]);
		$this->set('descriptions',$descriptions);
		$this->set("catId",$cat_id);
	}

	// Add SubWorkGroup Method
	public function addworksubgroup() {
		$cat_id = $_REQUEST['material_code'];
		$erp_work_sub_group = TableRegistry::get('erp_work_sub_group');
		$row = $erp_work_sub_group->newEntity();
		$row -> work_group_id = $cat_id;
		$row -> sub_work_group_title = $_REQUEST['workSubGroup'];
		if ($erp_work_sub_group->save($row)) {
			// The $row entity contains the id now
			$id = $row->sub_work_group_id;
		}
		
		$row1 = '<tr id="cat-'.$id.'">';
		$row1 .= '<td>'.$_REQUEST['workSubGroup'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-worksubgroup badge badge-info" model="material_group" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';		
		$option = "<option value='$id'>".$_REQUEST['workSubGroup']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}

	//Edit Work Group method 
	public function editworksubgroup() {
		$group_id = $_REQUEST['group_id'];	
		$erp_category_master = TableRegistry::get('erp_work_sub_group');
		$retrieved_data = $erp_category_master->get($group_id);
		echo '<td><input type="text" name="work-group" value="'.$retrieved_data->sub_work_group_title.'" id="work_subgroup"></td>';
		echo '<td id='.$retrieved_data->sub_work_group_id.'>
		<a class="btn-worksubgroup-update-cancel btn btn-danger" href="#" id='.$retrieved_data->sub_work_group_id.'>Cancel</a>
		<a class="btn-worksubgroup-update btn btn-primary" href="#" id='.$retrieved_data->sub_work_group_id.'>Save</a>
		</td>';
		die();
	}

	// Cancel workkgroup method
	public function cancelworksubgroupsave() {
		$group_id = $_REQUEST['group_id'];
		$erp_category_master = TableRegistry::get('erp_work_sub_group');
		$retrieved_data = $erp_category_master->get($group_id);
		echo '<td>'.$retrieved_data->sub_work_group_title .'</td>';
		echo '<td id='.$retrieved_data['sub_work_group_id'].'><a class="btn-edit-workgroup badge badge-info" model="material_group" href="#" id='.$retrieved_data['sub_work_group_id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}

	// Update WorkGroup Item
	public function updateworksubgroupitem() {
		$group_id = $_REQUEST['workGroupId'];
		$group_title = $_REQUEST['workGroupTitle'];
		$erp_category_master = TableRegistry::get('erp_work_sub_group');
		$retrieved_data = $erp_category_master->get($group_id);
		$retrieved_data -> sub_work_group_title = $group_title;
		$erp_category_master->save($retrieved_data);
		echo "<td>{$group_title}</td>";
		echo '<td id='.$retrieved_data['sub_work_group_id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['sub_work_group_id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}

	public function addmaterialgroup()
	{
		$erp_vendor_groups = TableRegistry::get('erp_vendor_groups');
		$row = $erp_vendor_groups->newEntity();
		$row->code = $_REQUEST['item_code'];
		$row->title = $_REQUEST['item_name'];
		$row->created_at = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');

		if ($erp_vendor_groups->save($row)) {
			// The $article entity contains the id now
			$id = $row->id;
		}
		//$id = '12';
		
		$row1 = '<tr id="cat-"'.$id.'">';
		$row1 .= '<td>'.$_REQUEST['item_code'] .'</td>';
		$row1 .= '<td>'.$_REQUEST['item_name'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';
		
		$option = "<option value='$id'>".$_REQUEST['item_name']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}
	//asset add-edit
	public function assetgroup()
	{		
		$erp_asset_groups = TableRegistry::get('erp_asset_groups'); 
		$result = $erp_asset_groups->find();
		$this->set('groups',$result);
	}
	
	public function editassetgroup()
	{		
		$group_id = $_REQUEST['group_id'];
		$erp_asset_groups = TableRegistry::get('erp_asset_groups');
		$retrieved_data = $erp_asset_groups->get($group_id);
		
		//echo '<td>'.$i.'</td>';
		echo '<td><input type="text" name="term_code" value="'.$retrieved_data->code.'" id="group_code"></td>';
		echo '<td><input type="text" name="term_title" value="'.$retrieved_data->title.'" id="group_title"></td>';
		echo '<td id='.$retrieved_data->cat_id.'>
		<a class="btn-group-update-cancel btn btn-danger" href="#" id='.$retrieved_data->id.'>Cancel</a>
		<a class="btn-group-update btn btn-primary" href="#" id='.$retrieved_data->id.'>Save</a>
		</td>';
		die();
	}
	
	public function cancelassetgroupsave()
	{
		$group_id = $_REQUEST['group_id'];
		$erp_asset_groups = TableRegistry::get('erp_asset_groups');
		$retrieved_data = $erp_asset_groups->get($group_id);
		
		echo '<td>'.$retrieved_data->code .'</td>';
		echo '<td>'.$retrieved_data->title .'</td>';
		echo '<td id='.$retrieved_data['id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['id'].'><i class="icon-edit"></i></a>';
		echo '</td>';
		die();
	}
	
	public function updateassetgroup()
	{
		$group_id = $_REQUEST['group_id'];
		$group_code = $_REQUEST['group_code'];
		$group_title = $_REQUEST['group_title'];
		$erp_asset_groups = TableRegistry::get('erp_asset_groups');
		$retrieved_data = $erp_asset_groups->get($group_id);
		$retrieved_data->code = $group_code;
		$retrieved_data->title = $group_title;
		$erp_asset_groups->save($retrieved_data);
		
		echo "<td>{$group_code}</td>";
		echo "<td>{$group_title}</td>";
		echo '<td id='.$retrieved_data['id'].'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$retrieved_data['id'].'><i class="icon-edit"></i></a>';
				
				
				echo '</td>';
		die();
		
	}
	public function addassetgroup()
	{
		$erp_asset_groups = TableRegistry::get('erp_asset_groups');
		$row = $erp_asset_groups->newEntity();
		$row->code = $_REQUEST['item_code'];
		$row->title = $_REQUEST['item_name'];
		$row->created_at = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');

		if ($erp_asset_groups->save($row)) {
			// The $article entity contains the id now
			$id = $row->id;
		}
		//$id = '12';
		
		$row1 = '<tr id="cat-"'.$id.'">';
		$row1 .= '<td>'.$_REQUEST['item_code'] .'</td>';
		$row1 .= '<td>'.$_REQUEST['item_name'] .'</td>';
		$row1 .= '<td id='.$id.'><a class="btn-edit-item badge badge-info" model="material_group" href="#" id='.$id.'><i class="icon-edit"></i></a>';
		$row1 .= '</td>';
		$row1 .= '</tr>';
		
		$option = "<option value='$id'>".$_REQUEST['item_name']."</option>";
		$array_var['row'] = $row1;
		$array_var['options'] = $option;
		echo json_encode($array_var);
		die();		
	}
	public function billpaymentdetail()
	{
		$id = $_REQUEST['id'];
		$role = $_REQUEST['role'];
		$erp_inward_bill = TableRegistry::get('erp_inward_bill');
		$find = $erp_inward_bill->get($id);
			$result[]=array (
						'invoice_no'=>$find['invoice_no'],
						'cheque_amount'=>$find['paid_amount'],			
						'cheque_date'=>$find['payment_date'],
						'cheque_no'=>$find['cheque_no'],
						'bank'=>$find['bank'],
						);
		$this->set('result',$result);
	}
	
	public function viewadvance()
	{
		$user = $this->request->session()->read('user_id');
		$projects = $this->Usermanage->access_project($user);
		$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail');
		$id = $_REQUEST['id'];
		$role = $_REQUEST['role'];
		$approval_group = $erp_advance_request_detail->get($id);
		$approval_group = json_decode($approval_group['approval_group']);
		$pro = array();
		foreach($approval_group as $idd)
		{
			$query = $erp_advance_request_detail->find()->where(['id'=>$idd])->select('project_id')->hydrate(false)->toArray();
			if(!empty($query))
			{
			$pro[] = $query[0]['project_id'];
			}
		}
		$project_id = $pro;
		$projects = $projects->fetchAll("assoc");
		foreach($projects as $pid)
		{
			$project_ids[] =  $pid['project_id'];
		}

		if($role =='projectdirector' || $role =='projectcoordinator' || $role =='planningmanager' || $role =='siteaccountant' || $role =='constructionmanager' || $role =='billingengineer' || $role =='materialmanager')
		{ 
			$diff_id = array_diff($project_id,$project_ids);
			
				$sum = 0;
				foreach($diff_id as $diff)
				{
					$qry = $erp_advance_request_detail->find()->where(['id IN'=>$approval_group,'project_id'=>$diff])->select('advance_rs')->hydrate(false)->toArray();
					$rs = $qry[0]['advance_rs'];
					$sum = $rs + $sum;
				}
				$cut_amount = $sum;
				
				$find = $erp_advance_request_detail->get($id);
				$cheque_amount = $find["cheque_amount"];
				$final_amount = $cheque_amount - $cut_amount;
				
				$result[]=array (
						'cheque_date'=>$find['transfer_date'],			
						'transfer_type'=>$find['transfer_type'],
						'cheque_amount'=>$final_amount,
						'bank'=>$find['bank'],
						'cheque_no'=>$find['cheque_no'],
						);
				
		}
		else
		{
			$find = $erp_advance_request_detail->get($id);
			$result[]=array (
						'cheque_date'=>$find['transfer_date'],			
						'transfer_type'=>$find['transfer_type'],
						'cheque_amount'=>$find['cheque_amount'],
						'bank'=>$find['bank'],
						'cheque_no'=>$find['cheque_no'],
						);
		}
		$this->set('result',$result);
	}
	
	public function addcategory()
	{
		$category_master_Table = TableRegistry::get('erp_category_master');
		$category_master = $category_master_Table->newEntity();
		$category_master->type = $_REQUEST['model'];
		if($_REQUEST['model'] == 'subcontractbill_option')
		{
			$enabled_project_data = explode(" ",$_REQUEST['subc_project_id']);
			// debug($enabled_project_data);die;
			$selected_project_ids =  json_encode(array_values($enabled_project_data));
			$category_master->project_id = $selected_project_ids;
			$category_master->unit = $_REQUEST['description_unit'];
		}
		$category_master -> work_group = $_REQUEST['workGroup'];
		$category_master -> work_sub_group = $_REQUEST['workSubGroup'];
		$category_master->category_title = $_REQUEST['category_name'];
		$category_master->category = isset($_REQUEST['designation_category'])?$_REQUEST['designation_category']:NULL;
		$category_master->status = 1;
		$category_master->created_date = date('Y-m-d H:i:s');
		$category_master->created_by = 1;
		// debug($category_master);
		// die;
		if ($category_master_Table->save($category_master)) {
			// The $article entity contains the id now
			$id = $category_master->cat_id;
		}
		//$id = '12';
		if($_REQUEST['model'] == 'designation')
		{
			$row1 = '<tr id="cat-'.$id.'"><td>'.$_REQUEST['category_name'].'</td><td>'.strtoupper($_REQUEST['designation_category']).'</td><td><a class="btn-delete-cat badge badge-delete" href="#" id='.$id.'>X</a></td></tr>';
		}elseif($_REQUEST['model'] == 'subcontractbill_option')
		{
			$row1 = '<tr id="cat-'.$id.'"><td>'.$_REQUEST['category_name'].'</td><td>'.$this->ERPfunction->get_projectname($_REQUEST['subc_project_id']).'</td><td>'.$_REQUEST['description_unit'].'</td><td><a class="btn-delete-cat badge badge-delete" href="#" id='.$id.'>X</a></td></tr>';
		}else{
			$row1 = '<tr id="cat-'.$id.'"><td>'.$_REQUEST['category_name'].'</td><td><a class="btn-delete-cat badge badge-delete" href="#" id='.$id.'>X</a></td></tr>';
		}
		$option = "<option value='$id'>".$_REQUEST['category_name']."</option>";
		$array_var[] = $row1;
		$array_var[] = $option;
		echo json_encode($array_var);
		die();		
	}

	public function removecategory()
	{
		$category_master_Table = TableRegistry::get('erp_category_master');
		
		$cat_id = $_REQUEST['cat_id'];
		$category_data =$category_master_Table->get($cat_id);
		$category_master_Table->delete($category_data);
		die();
	}	
	
	/* End category */
	public function get_last_vendor_id()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select max(user_id) from  erp_vendor');		
		$max = 0;
		foreach($result as $retrive_data)
		{ $max=$retrive_data[0]; }
		return $max;
	}
		
	public function generatevendorid()
	{
		$vendor_group = $_REQUEST['vendor_group'];
		$projectdetail = TableRegistry::get('erp_vendor'); 		
		$prepare_count = $this->get_last_vendor_id();
		$new_prno = sprintf("%09d", $prepare_count + 1);
		$vendor_id = 'YNEC/VD/'.$this->ERPfunction->get_vendor_group_code($vendor_group ).'/'.$new_prno;
		$result_arr['vendor_id'] = $vendor_id;
		echo json_encode($result_arr);
		die();
	}
	
		
	public function generatematerialcode()
	{
		$material_code = $_REQUEST['material_code'];
		if($material_code == 16)
		{
			$number1 = $this->ERPfunction->generate_auto_id_material_temp($material_code);
			$new_prno = sprintf("%09d", $number1);
			$material_item_code = 'YNEC/MT/'.$this->ERPfunction->get_vendor_group_code($material_code ).'/'.$new_prno;
					
		}else{		
			$prepare_count = $this->get_last_material_id();
			$new_prno = sprintf("%09d", $prepare_count + 1);
			$material_item_code = 'YNEC/MT/'.$this->ERPfunction->get_vendor_group_code($material_code ).'/'.$new_prno;
		}
		$result_arr['material_item_code'] = $material_item_code; 
	/*	$material_new_code = $this->ERPfunction->generate_auto_id_material($material_code);
		$new_mno = sprintf("%09d", $material_new_code);
		$new_mno =  $material_item_code = 'YNEC/MT/'.$this->ERPfunction->get_vendor_group_code($material_code )."/".$new_mno;
		$result_arr['material_item_code'] = $new_mno; */
		
		$brand_tbl = TableRegistry::get("erp_material_brand");
		$brands = $brand_tbl->find("list",["keyField"=>"brand_id","valueField"=>"brand_name"])->where(["material_type"=>$material_code]);
		if(!empty($brands))
		{
			$options = "<option value=''>Select Brand</option>";
			foreach($brands as $key=>$value)
			{
				$options .= "<option value='{$key}'>{$value}</option>";
			}
		}else{
			$options = "<option value=''>No Brand Found</option>";
		}
		$result_arr['brands'] = $options;
		echo json_encode($result_arr);
		die();
	}
	public function generatematerialcodeedit()
	{
		$material_code = $_REQUEST['material_code'];
		$material_item_code = $_REQUEST['material_item_code'];
		$material_item_data = explode("/",$material_item_code);
		$old_sequence_number = $material_item_data[3];
		if($material_code == 16)
		{
			$number1 = $this->ERPfunction->generate_auto_id_material_temp($material_code);
			$new_prno = sprintf("%09d", $number1);
			$material_item_code = 'YNEC/MT/'.$this->ERPfunction->get_vendor_group_code($material_code ).'/'.$old_sequence_number;
					
		}else{		
			$prepare_count = $this->get_last_material_id();
			$new_prno = sprintf("%09d", $prepare_count + 1);
			$material_item_code = 'YNEC/MT/'.$this->ERPfunction->get_vendor_group_code($material_code ).'/'.$old_sequence_number;
		}
		$result_arr['material_item_code'] = $material_item_code; 
	/*	$material_new_code = $this->ERPfunction->generate_auto_id_material($material_code);
		$new_mno = sprintf("%09d", $material_new_code);
		$new_mno =  $material_item_code = 'YNEC/MT/'.$this->ERPfunction->get_vendor_group_code($material_code )."/".$new_mno;
		$result_arr['material_item_code'] = $new_mno; */
		
		$brand_tbl = TableRegistry::get("erp_material_brand");
		$brands = $brand_tbl->find("list",["keyField"=>"brand_id","valueField"=>"brand_name"])->where(["material_type"=>$material_code]);
		if(!empty($brands))
		{
			$options = "<option value=''>Select Brand</option>";
			foreach($brands as $key=>$value)
			{
				$options .= "<option value='{$key}'>{$value}</option>";
			}
		}else{
			$options = "<option value=''>No Brand Found</option>";
		}
		$result_arr['brands'] = $options;
		echo json_encode($result_arr);
		die();
	}
	public function get_last_asset_id()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select max(asset_id) from  erp_assets');		
		$max = 0;
		foreach($result as $retrive_data)
		{ $max=$retrive_data[0]; }
		return $max;
	}
	public function generateassetid()
	{
		$asset_id = $_REQUEST['asset_group'];		
		$prepare_count = $this->get_last_asset_id();
		// $new_assetno = sprintf("%09d", $prepare_count + 1);
		
		$number1 = $this->ERPfunction->generate_asset_auto_id($asset_id,"erp_assets","asset_id","asset_code");
		$new_assetno = sprintf("%09d", $number1);
		
		$asset_code = 'YNEC/AST/'.$this->ERPfunction->get_asset_group_code($asset_id ).'/'.$new_assetno;
		$result_arr['asset_code'] = $asset_code;
		echo json_encode($result_arr);
		die();
	}
	
	public function generateassetidedit()
	{
		$asset_id = $_REQUEST['asset_group'];		
		$asset_code = $_REQUEST['asset_code'];		
		
		$asset_data = explode("/",$asset_code);
		$old_sequence_number = $asset_data[3];
				
		$asset_code = 'YNEC/AST/'.$this->ERPfunction->get_asset_group_code($asset_id ).'/'.$old_sequence_number;
		$result_arr['asset_code'] = $asset_code;
		echo json_encode($result_arr);
		die();
	}
	
	public function getlast_prepare_pr()
	{	
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select max(pr_id) from  erp_inventory_purhcase_request');		
		$count = 0;
		foreach($result as $retrive_data)
		{ $count=$retrive_data[0]; }
		return $count;		
	}
	public function getlast_prepare_po()
	{	
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select max(po_id) from  erp_inventory_po');		
		$count = 0;
		foreach($result as $retrive_data)
		{ $count=$retrive_data[0]; }
		return $count;		
	}
	public function getlast_prepare_grn($project_id = Null)
	{	
		$conn = ConnectionManager::get('default');
		 $count = 0;
		if($project_id)
		{
			$result = $conn->execute('select grn_no from  erp_inventory_grn where 
			project_id = '.$project_id.' order by grn_no desc limit 1');		
		$flag = 0;
		foreach($result as $retrive_data)
		{ 
		$flag = 1;
		$count=$retrive_data['grn_no']; 
		if($flag)
		{
			$string1=$count;  
			$newstring=strstr($string1,"/",true);  
			echo $newstring; 
			return $count;	
		}
		else{
			return $count;	
		}
		}
			
		}	
		else{
		$result = $conn->execute('select max(grn_id) from  erp_inventory_grn');		
		$count = 0;
		foreach($result as $retrive_data)
		{ $count=$retrive_data[0]; }
		return $count;	}	
	}
	
	public function getprojectdetail()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		$new_prno = sprintf("%09d", $result_arr['project_code']);
		$pr_no = $result_arr['project_code'].'/PR/'.$new_prno;
		$result_arr['prno'] = $pr_no;		
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_assets_maintenance","maintenace_id","amo_no");
				 
		$auto_amono = sprintf("%09d", $number1);
		$auto_amono = "{$result_arr['project_code']}/{$auto_amono}";				
		$result_arr['amo_no'] = $auto_amono;
		
		$asset_tbl = TableRegistry::get('erp_assets'); 
		$assets = $asset_tbl->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->where(["deployed_to"=>$project_id]);
		$asset_list = "";
		if(!empty($assets))
		{
			foreach($assets as $key=>$value)
			{
				$asset_list .= "<option value='{$key}'>{$value}</option>";
			}
		}else{
			$asset_list .= "<option value=''>NO asset found</option>";
		}
		$result_arr['asset_list'] = $asset_list;
		echo json_encode($result_arr);
		
		
		die();
	}
	
	
	public function projectdetailpr()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		/* $number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_purhcase_request","prno","pr_id"); */
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_purhcase_request","pr_id","prno");

		$new_prno = sprintf("%09d", $number1);
		$pr_no = $result_arr['project_code'].'/PR/'.$new_prno;
		$result_arr['prno'] = $pr_no;
		echo json_encode($result_arr);
		die();
	}
	
	public function projectdetailaccount()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);		
		$result_arr = array();
		
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		/* $number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_purhcase_request","prno","pr_id"); */
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_advance_request","request_id","advance_req_no");

		$new_prno = sprintf("%09d", $number1);
		$pr_no = $result_arr['project_code'].'/ADV/'.$new_prno;
		$result_arr['prno'] = $pr_no;
		echo json_encode($result_arr);
		die();
	}
	
	public function expenceprojectdetail()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);		
		$result_arr = array();
		
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		/* $number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_purhcase_request","prno","pr_id"); */
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_expence_add","id","voucher_no");

		$new_prno = sprintf("%09d", $number1);
		$pr_no = $result_arr['project_code'].'/SE/'.$new_prno;
		$result_arr['prno'] = $pr_no;
		echo json_encode($result_arr);
		die();
	}
	
	public function accountdetail()
	{
		$account_id = $_REQUEST['account_id'];
		$erp_account = TableRegistry::get('erp_account'); 
		$account_data = $erp_account->find()->where(['account_id'=>$account_id]);		
		$result_arr = array();
		
		foreach($account_data as $retrive_data)
		{
			$result_arr['account_no'] = $retrive_data['account_no'];
			$result_arr['bank'] = $retrive_data['bank'];
			$result_arr['branch'] = $retrive_data['branch'];
			$result_arr['ifsc_code'] = $retrive_data['ifsc_code'];
		}
		
		
		echo json_encode($result_arr);
		die();
	}
	
	public function projectdetail()
	{		
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_pr();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}		
		$new_prno = sprintf("%09d", $prepare_count + 1);
		$pr_no = $result_arr['project_code'].'/PR/'.$new_prno;
		$result_arr['prno'] = $pr_no;
		echo json_encode($result_arr);
		die();
	}
	
	public function rmcprojectdetail()
	{		
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_pr();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_rmc_issue","id","isno");
		$new_isno = sprintf("%09d", $number1);
		$is_no = $result_arr['project_code'].'/RMC/'.$new_isno;
		$result_arr['isno'] = $is_no;
				
		$asset_tbl = TableRegistry::get('erp_assets'); 
		$assets = $asset_tbl->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->where(["asset_group"=>1,"deployed_to"=>$project_id])->hydrate(false)->toArray();
		$options = "<option value=''>Select Asset</option>";
		if(!empty($assets))
		{
			foreach($assets as $key=>$value)
			{
				$options .= "<option value='{$key}'>{$value}</option>";
			}
		}
		
		$result_arr['assets'] = $options;
		echo json_encode($result_arr);
		die();
	}
	
	public function vendordetail()
	{
		$vendor_userid = $_REQUEST['vendor_userid'];
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
		
		echo json_encode($result_arr);
		die();
	}
	public function inpoprojectdetail()
	{		
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_po();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		$new_prno = sprintf("%09d", $prepare_count + 1);
		$pr_no = $result_arr['project_code'].'/PO/'.$new_prno;
		$result_arr['po_no'] = $pr_no;		
		echo json_encode($result_arr);
		die();
	}
	
	public function inpoprojectdetailpo()
	{		
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		// $prepare_count = $this->getlast_prepare_po();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
			$result_arr['project_address'] = $retrive_data['project_address'];			
			$result_arr['project_address_2'] = $retrive_data['city'] ."-".$retrive_data['pincode'].",".$retrive_data['district'].",".$retrive_data['state'];		
						
		}

		// $new_prno = sprintf("%09d", $prepare_count + 1);
		$new_prno = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_po","po_id","po_no");
		$new_prno = sprintf("%09d", $new_prno);
		$pr_no = $result_arr['project_code'].'/PO/'.$new_prno;
		$result_arr['po_no'] = $pr_no;
		
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		// $prids = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$project_id,"erp_inventory_pr_material.approved" => 0])->select(["prno","pr_id"]); 
		// $prids = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$project_id,"erp_inventory_pr_material.approved" => 0,"erp_inventory_pr_material.show_in_purchase !="=>1])->select(["prno","pr_id"]);
		$prids = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$project_id,"erp_inventory_pr_material.show_in_purchase" => 2])->select(["prno","pr_id"]);
		$prids = $prids->leftjoin(["erp_inventory_pr_material"=>"erp_inventory_pr_material"],
									["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id"])->group("erp_inventory_purhcase_request.prno")->select($mat_tbl)->hydrate(false)->toArray();
		$prlist = null;
		foreach($prids as $prno)
		{
			$prlist.="<option value='{$prno['pr_id']}' prno='{$prno['prno']}'>{$prno['prno']}</option>";
		}
		$result_arr["pending_pr"] = $prlist;
		echo json_encode($result_arr);
		die();
	}
	
	public function inmanualpoprojectdetail()
	{		
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
			$result_arr['project_address'] = $retrive_data['project_address'];			
			$result_arr['project_address_2'] = $retrive_data['city'] ."-".$retrive_data['pincode'].",".$retrive_data['district'].",".$retrive_data['state'];		
						
		}

		$new_prno = $this->ERPfunction->generate_auto_id($project_id,"erp_manual_po","po_id","po_no");
		$new_prno = sprintf("%09d", $new_prno);
		$pr_no = $result_arr['project_code'].'/MANPO/'.$new_prno;
		$result_arr['po_no'] = $pr_no;
		
		echo json_encode($result_arr);
		die();
	}
	
	public function inwoprojectdetail()
	{		
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
			$result_arr['project_address'] = $retrive_data['project_address'];			
			$result_arr['project_address_2'] = $retrive_data['city'] ."-".$retrive_data['pincode'].",".$retrive_data['district'].",".$retrive_data['state'];		
		}

		$new_prno = $this->ERPfunction->generate_auto_id($project_id,"erp_work_order","wo_id","wo_no");
		$new_prno = sprintf("%09d", $new_prno);
		$pr_no = $result_arr['project_code'].'/WO/'.$new_prno;
		$result_arr['wo_no'] = $pr_no;
		
		echo json_encode($result_arr);
		die();
	}
	
	public function ingrnprojectdetail()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		/* $prepare_count = $this->getlast_prepare_grn(); */
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		/* $new_grnno = sprintf("%09d", $prepare_count + 1); */
		$number1 = $this->ERPfunction->generate_auto_id_grn($project_id,"erp_inventory_grn","grn_id","grn_no","GRNLP");
		$new_grnno = sprintf("%09d", $number1);
		$grn_no = $result_arr['project_code'].'/GRN/'.$new_grnno;
		$grnlp_no = $result_arr['project_code'].'/GRNLP/'.$new_grnno;
		$result_arr['grn_no'] = $grn_no;
		$result_arr['grnlp_no'] = $grnlp_no;
		
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		/* $prids = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$project_id,"erp_inventory_pr_material.approved" => 0])->select(["prno","pr_id"]); */
		$prids = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$project_id,"erp_inventory_pr_material.approved_for_grnwithoutpo" => 1])->select(["prno","pr_id"]);
		$prids = $prids->leftjoin(["erp_inventory_pr_material"=>"erp_inventory_pr_material"],
									["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id"])->group("erp_inventory_purhcase_request.prno")->select($mat_tbl)->hydrate(false)->toArray();
		$prlist = null;
		$prlist = "<option>SELECT</option>";
		foreach($prids as $prno)
		{
			$prlist.="<option value='{$prno['pr_id']}' prno='{$prno['prno']}'>{$prno['prno']} </option>";
		}
		$result_arr["pending_pr"] = $prlist;		
		
		echo json_encode($result_arr);
		die();
	}
	
	public function ingrnprojectdetaillp()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_grn();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		$new_grnno = sprintf("%09d", $prepare_count + 1);
		$grn_no = $result_arr['project_code'].'/GRNLP/'.$new_grnno;
		$result_arr['grn_no'] = $grn_no;
		
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		/* $prids = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$project_id,"erp_inventory_pr_material.approved" => 0])->select(["prno","pr_id"]); */
		$prids = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$project_id,"erp_inventory_pr_material.approved_for_grnwithoutpo" => 1])->select(["prno","pr_id"]);
		$prids = $prids->leftjoin(["erp_inventory_pr_material"=>"erp_inventory_pr_material"],
									["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id"])->group("erp_inventory_purhcase_request.prno")->select($mat_tbl)->hydrate(false)->toArray();
		$prlist = null;
		$prlist .= "<option value=''>SELECT</option>";
		foreach($prids as $prno)
		{
			$prlist.="<option value='{$prno['pr_id']}' prno='{$prno['prno']}'>{$prno['prno']}</option>";
		}
		$result_arr["pending_pr"] = $prlist;		
		
		echo json_encode($result_arr);
		die();
	}
	
	public function ingrnprojectdetaillppo()
	{
		$project_id = $_REQUEST['project_id'];		
		$vendor_id = isset($_REQUEST['vendor_user_id'])?$_REQUEST['vendor_user_id']:0;			
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		/* $prepare_count = $this->getlast_prepare_grn(); */
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}

		$number1 = $this->ERPfunction->generate_auto_id_grn($project_id,"erp_inventory_grn","grn_id","grn_no","GRN");
			$new_grnno = sprintf("%09d", $number1);
		$grn_no = $result_arr['project_code'].'/GRN/'.$new_grnno;
		$result_arr['grn_no'] = $grn_no;
		
		$pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		$mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		$prids = $pr_tbl->find()->where(["erp_inventory_purhcase_request.project_id"=>$project_id,"erp_inventory_pr_material.approved" => 0])->select(["prno","pr_id"]);
		$prids = $prids->leftjoin(["erp_inventory_pr_material"=>"erp_inventory_pr_material"],
									["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id"])->group("erp_inventory_purhcase_request.prno")->select($mat_tbl)->hydrate(false)->toArray();
		$prlist = null;
		foreach($prids as $prno)
		{
			$prlist.="<option value='{$prno['pr_id']}' prno='{$prno['prno']}'>{$prno['prno']}</option>";
		}
		$result_arr["pending_pr"] = $prlist;		
		
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$po_m_tbl = TableRegistry::get("erp_inventory_po_detail");		
		$data = $po_tbl->find("list",["keyField"=>"po_id","valueField"=>"po_no"])->where(["project_id"=>$project_id,"po_purchase_type IN"=>['po'],"vendor_userid"=>$vendor_id])->toArray();
		$option = "";		
		foreach($data as $key=>$value)
		{
			$pending = false;
			$check = $po_m_tbl->find("all")->where(["po_id"=>$key])->hydrate(false)->toArray();
			if(!empty($check))
			{
				
				foreach($check as $chk)
				{
					/* if($chk["approved"] != 2) */
					if($chk["approved"] != 2 && $chk["approved"] != 0) /* same as  approved == 1 */
					{
						$pending = true;
					}
				}
			}
			if($pending)
			{
				$option .= "<option value='{$key}'>{$value}</option>";
			}
		}
		$result_arr["po_data"] = $option;		
		echo json_encode($result_arr);
		die();
	}
	
	public function inisprojectdetail()
	{		
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		/* $prepare_count = $this->getlast_prepare_is(); */
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_is","is_id","is_no");
		
		/* $new_grnno = sprintf("%09d", $prepare_count + 1); */
		$new_grnno = sprintf("%09d", $number1);
		$is_no = $result_arr['project_code'].'/IS/'.$new_grnno;
		$result_arr['is_no'] = $is_no;

		$asset_list = $this->ERPfunction->get_asset_by_project($project_id);
		$asset_data = "";
		if(!empty($asset_list))
		{
			/* $asset_list = array_map(function($val){return "asst_{$val}";},$asset_list); */
			foreach ($asset_list as $key => $val) {
				
				$assets['asst_'.$key] = $val;
				unset($asset_list[$key]);
			}
			
			foreach($assets as $key=>$value)
			{
				$asset_data .= "<option value='{$key}' class='added_asset'>{$value}</option>";
			}		
		}
		
		$result_arr["assets"] = $asset_data;
		
		echo json_encode($result_arr);
		die();
	}
	
	public function inmrnprojectdetail()
	{		
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		/* $prepare_count = $this->getlast_prepare_mrn(); */
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_mrn","mrn_id","mrn_no");
		/* $new_grnno = sprintf("%09d", $prepare_count + 1); */
		$new_grnno = sprintf("%09d", $number1);
		$mrn_no = $result_arr['project_code'].'/MRN/'.$new_grnno;
		$result_arr['mrn_no'] = $mrn_no;
		echo json_encode($result_arr);
		die();
	}
	public function projectdetailrbn()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_rbn();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_rbn","rbn_id","rbn_no");
		$new_grnno = sprintf("%09d", $number1);
		$rbn_no = $result_arr['project_code'].'/RBN/'.$new_grnno;
		$result_arr['rbn_no'] = $rbn_no;
		
		
		
		$asset_list = $this->ERPfunction->get_asset_by_fix_group($project_id);
		$asset_data = "";
		if(!empty($asset_list))
		{
			/* $asset_list = array_map(function($val){return "asst_{$val}";},$asset_list); */
			foreach ($asset_list as $key => $val) {
				
				$assets['asst_'.$key] = $val;
				unset($asset_list[$key]);
			}
			
			foreach($assets as $key=>$value)
			{
				$asset_data .= "<option value='{$key}' class='added_asset'>{$value}</option>";
			}
		
		}
		$result_arr["assets"] = $asset_data;
		
		
		echo json_encode($result_arr);
		die();
	}
	
	public function projectdetailsst()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		/* $prepare_count = $this->getlast_prepare_sst(); */
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		/* $new_grnno = sprintf("%09d", $prepare_count + 1); */
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_sst","sst_id","sst_no");
		$new_grnno = sprintf("%09d", $number1);
		$sst_no = $result_arr['project_code'].'/SST/'.$new_grnno;
		$result_arr['sst_no'] = $sst_no;
		echo json_encode($result_arr);
		die();
	}
	public function stockledgerlist()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
	 
		$materialdetail = TableRegistry::get('erp_stock_history'); 
		$material_data = $materialdetail->find()->where(['project_id'=>$project_id,"type !="=> "os"]);
	 
		$result_arr = array();
		$material_arr = array();
		foreach($material_data as $retrive_data)
		{
			if($retrive_data['material_id'])
			{
				$material_arr[] = $retrive_data['material_id'];
			}
			else
			{
				$material_arr[] = $retrive_data['material_name'];
			}
		}
		$material_arr = array_unique($material_arr); 
		$defaultmsg  =__( 'Select Material ' , 'ticket_mgt');
		$content = '';
		$content .= "<option value=''>".$defaultmsg."</option>";	
		 
		foreach($material_arr as $retrive_data)
		{
			if($retrive_data != 0)
										{
											$value = $retrive_data;
											$name = $this->ERPfunction->get_material_title($retrive_data);
										}
										else
										{
											$value = $retrive_data;
											$name = $retrive_data;
										}
			//$material_name = $this->ERPfunction->get_material_title($retrive_data);
			var_dump($value);
			$content .= '<option value="'.$value.'">'. $name.'</option>';
			 
		}
		
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		 
		$result_arr['material_data'] = $content;
		echo json_encode($result_arr);
		
		die();
	}
	public function stockledgermatcode()
	{
		$material_id = $_REQUEST['material_id'];
		$material_name = $this->ERPfunction->get_materialcode_bymaterialid($material_id);
		
		$result_arr['material_code'] = $material_name;
		echo json_encode($result_arr);
		die();
	}
	
	public function projectdetailtransferto()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}		
		echo json_encode($result_arr);
		die();
	}
	public function getlast_prepare_sst(){
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select max(sst_id) from  erp_inventory_sst');		
		$count = 0;
		foreach($result as $retrive_data)
		{ $count=$retrive_data[0]; }
		return $count;
	}
	public function getlast_prepare_rbn()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select max(rbn_id) from  erp_inventory_rbn');		
		$count = 0;
		foreach($result as $retrive_data)
		{ $count=$retrive_data[0]; }
		return $count;
	}
	public function getlast_prepare_mrn()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select max(mrn_id) from  erp_inventory_mrn');		
		$count = 0;
		foreach($result as $retrive_data)
		{ $count=$retrive_data[0]; }
		return $count;	
	}
	public function getlast_prepare_is()
	{
		$conn = ConnectionManager::get('default');
		$result = $conn->execute('select max(is_id) from  erp_inventory_is');		
		$count = 0;
		foreach($result as $retrive_data)
		{ $count=$retrive_data[0]; }
		return $count;	
	}
	public function addnewrow()
	{
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$row_id = $_REQUEST['row_id'];
		$row_type = $_REQUEST['row_type'];
		$last_code = $_REQUEST['last_code'];
		$erp_material = TableRegistry::get('erp_material');
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_id IN"=>$material_ids,"project_id IN"=>$projectids_in]);
		}else{
			$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		} 
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);
		$this->set('row_type',$row_type);
		$this->set('last_code',$last_code);
	}
	public function addnewrowpo()
	{
		$row_id = $_REQUEST['row_id'];
		$row_type = $_REQUEST['row_type'];
		$last_code = $_REQUEST['last_code'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material');
		
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(['material_id IN'=>$material_ids,"material_code !="=>17,"project_id IN"=>$projectids_in]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id IN"=>$projectids_in]);
		}
	
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);		
		$this->set('row_type',$row_type);
		$this->set('last_code',$last_code);
		
	}
	
	public function addnewrowpomanual()
	{
		$row_id = $_REQUEST['row_id'];
		$row_type = $_REQUEST['row_type'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material');
		
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(['material_id IN'=>$material_ids,"material_code !="=>17,"project_id IN"=>$projectids_in]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id IN"=>$projectids_in]);
		}
	
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);		
		$this->set('row_type',$row_type);
	}
	
	public function addnewrowpotextfield()
	{
		$row_id = $_REQUEST['row_id'];
		// $row_type = $_REQUEST['row_type'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material');
		
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(['material_id IN'=>$material_ids,"material_code !="=>17,"project_id IN"=>$projectids_in]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id IN"=>$projectids_in]);
		}
	
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);		
		// $this->set('row_type',$row_type);
	}

	public function addnewrowgrnwithoutpo()
	{
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);
	}
	public function addnewrowgrnwithpo()
	{
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);
	}
	public function addnewrowgrneditwithpo()
	{
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);
	}
	public function addnewrowgrnwithlocalpo()
	{
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);
	}
	public function addnewline()
	{
		$row_id = $_REQUEST['row_id'];
		$erp_vendor = TableRegistry::get('erp_vendor'); 
		$vendor_list = $erp_vendor->find();
		$this->set('vendor_list',$vendor_list);		
		$this->set('row_id',$row_id);		
	}
	public function addnewexpenserow()
	{
		$row_id = $_REQUEST['row_id'];
		$this->set('row_id',$row_id);
		
		$sr_no = $_REQUEST['sr_no'];
		$this->set('sr_no',$sr_no);
	}
	public function addnewrowopeningstock()
	{
		$row_id = $_REQUEST['row_id'];
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id"=>0]);
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);		
	}
	public function addnewrowinissueslip()
	{
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);
	}
	
	public function addnewrowinmrn()
	{
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);		
	}
	public function addnewrowinrbn()
	{
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);		
	}
	
	public function addnewrowinsst()
	{
		$row_id = $_REQUEST['row_id'];
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);		
	}
	
	public function getmaterialbrandlist() {
		if(is_numeric($_REQUEST['material_id'])) {
			$material_id = $_REQUEST['material_id'];		
			$erp_material = TableRegistry::getTableLocator()->get('erp_material'); 
			$material_data = $erp_material->find()->where(['material_id'=>$material_id])->hydrate(false)->toList();
			$material_code = 0;

			foreach($material_data as $retrive_data)
			{
				$material_code = $retrive_data['material_code'];
				$unit_id = $retrive_data['unit_id'];			
				$material_item_code = $retrive_data['material_item_code'];			
			}
			$erp_material_brand = TableRegistry::get('erp_material_brand'); 
			$brand_data = $erp_material_brand->find()->where(['material_type'=>$material_code]);
			$content = '<option value="">Select Item</option>';
			foreach($brand_data as $retrive_data)
			{
				
				$content .= '<option value = "'.$retrive_data['brand_id'].'">'.$retrive_data['brand_name'].'</option>';
			}
			$material_category = $this->ERPfunction->material_category();
			$returnarray['itemlist'] = $content;

			$returnarray['unit_name'] = $this->ERPfunction->get_category_title($unit_id);
			//$returnarray['material_code'] = $material_category[$material_code]['material_code'];
			$returnarray['material_code'] = $material_item_code;
			$returnarray['opening_stock'] = "None";
			if(isset($_REQUEST['stock']))
			{
				$project_id = $_REQUEST['project_id'];			
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
						$opening_stock = $this->ERPfunction->get_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
					}
				}
				$returnarray['opening_stock'] = $opening_stock;
			}
			
			echo json_encode($returnarray);
			die();
		}else{
			echo "-";
			die;
		}
	}
	
	public function getmaterialbrandlistprojectwise()
	{
		$material_id = $_REQUEST['material_id'];		
		$project_id = $_REQUEST['project_id'];		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_data = $erp_material->find()->where(['material_id'=>$material_id]);
		$material_code = 0;
		foreach($material_data as $retrive_data)
		{
			$material_code = $retrive_data['material_code'];
			$unit_id = $retrive_data['unit_id'];			
			$material_item_code = $retrive_data['material_item_code'];			
		}
		$erp_material_brand = TableRegistry::get('erp_material_brand'); 
		$brand_data = $erp_material_brand->find()->where(['material_type'=>$material_code,'project_id'=>0]);
		$content = '<option value="">Select Item</option>';
		foreach($brand_data as $retrive_data)
		{
			
			$content .= '<option value = "'.$retrive_data['brand_id'].'">'.$retrive_data['brand_name'].'</option>';
		}
		
		if($project_id != '')
		{
			$brand_content = $erp_material_brand->find()->where(['material_type'=>$material_code,'project_id'=>$project_id]);
			foreach($brand_content as $retrive_data)
			{
				
				$content .= '<option value = "'.$retrive_data['brand_id'].'">'.$retrive_data['brand_name'].'</option>';
			}
		}
		
		$material_category = $this->ERPfunction->material_category();
		$returnarray['itemlist'] = $content;
		$returnarray['unit_name'] = $this->ERPfunction->get_category_title($unit_id);
		//$returnarray['material_code'] = $material_category[$material_code]['material_code'];
		$returnarray['material_code'] = $material_item_code;
		$returnarray['opening_stock'] = "None";
		if(isset($_REQUEST['stock']))
		{
			$project_id = $_REQUEST['project_id'];			
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
					$opening_stock = $this->ERPfunction->get_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
				}
			}
			$returnarray['opening_stock'] = $opening_stock;
		}
		
		
		echo json_encode($returnarray);
		die();
	}
	
	public function projectwisematerial()
	{
		$project_id = $_REQUEST['project_id'];		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_data = $erp_material->find()->where(['project_id'=>$project_id]);
		$content = '<option value="">--Select Material--</option>';
		foreach($material_data as $retrive_data)
		{
			$content .= '<option value = "'.$retrive_data['material_id'].'">'.$retrive_data['material_title'].'</option>';
		}
		echo $content;
		die;
	}
	
	public function getvendorlist()
	{
		$vendor_id = $_REQUEST['material_id'];		
		$erp_agency = TableRegistry::get('erp_vendor'); 
		$material_data = $erp_agency->find()->where(['user_id'=>$vendor_id]);
		$material_code = 0;
		foreach($material_data as $retrive_data)
		{
			$material_code = $retrive_data['vendor_id'];				
		}
		//$returnarray['material_code'] = $material_category[$material_code]['material_code'];
		$returnarray['material_code'] = $material_code;
		echo json_encode($returnarray);
		die();
	}
	public function getmaterialunit()
	{
		$material_id = $_REQUEST['material_id'];
		$erp_material = TableRegistry::get('erp_material'); 
		$material_data = $erp_material->find()->where(['material_id'=>$material_id]);
	}
	
	public function loadpritems()
	{
		$pr_id = $_REQUEST['pr_id'];
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request'); 
		$pr_data = $erp_inventory_purhcase_request->find()->where(['pr_id'=>$pr_id]);
		$result_arr = array();
		foreach($pr_data as $retrive_data)
		{
			$result_arr['contact_no1'] = $retrive_data['contact_no1'];			
			$result_arr['contact_no2'] = $retrive_data['contact_no2'];			
			$result_arr['pritems'] = $this->ERPfunction->get_pr_materiallist($retrive_data['pr_id']);			
		}
		
		echo json_encode($result_arr);
		die();
	}
	
	public function loadeditpoitems()
	{
		$pr_id = $_REQUEST['pr_id'];
		$poid = $_REQUEST['poid'];
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request'); 
		$pr_data = $erp_inventory_purhcase_request->find()->where(['pr_id'=>$pr_id]);
		$result_arr = array();
		foreach($pr_data as $retrive_data)
		{
			$result_arr['contact_no1'] = $retrive_data['contact_no1'];			
			$result_arr['contact_no2'] = $retrive_data['contact_no2'];			
			$result_arr['pritems'] = $this->ERPfunction->get_editpo_materiallist($retrive_data['pr_id'],$poid);			
		}
		
		echo json_encode($result_arr);
		die();
	}
	
	public function loadgrnitems()
	{
		$pr_id = $_REQUEST['pr_id'];
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request'); 
		$pr_data = $erp_inventory_purhcase_request->find()->where(['pr_id'=>$pr_id]);
		$result_arr = array();
		foreach($pr_data as $retrive_data)
		{
			$result_arr['contact_no1'] = $retrive_data['contact_no1'];			
			$result_arr['contact_no2'] = $retrive_data['contact_no2'];			
			$result_arr['pritems'] = $this->ERPfunction->getPrMateriallistIngrn($pr_id);			
		}
		
		echo json_encode($result_arr);
		die();
	}
	
		
	public function approvepr()
	{
		$pr_id = $_REQUEST['pr_id'];
		$erp_inventory_purhcase_request = TableRegistry::get('erp_inventory_purhcase_request');
		$pr_data = $erp_inventory_purhcase_request->get($pr_id);
		$post_data['approved_status'] = 1;
		$post_data['approved_date'] = date('Y-m-d H:i:s');
		$post_data['approve_by'] = $this->request->session()->read('user_id');
		$data = $erp_inventory_purhcase_request->patchEntity($pr_data,$post_data);
		$erp_inventory_purhcase_request->save($data);
		die();
	}
	
	public function approvepo()
	{
		$po_id = $_REQUEST['po_id'];
		$po_mode = $_REQUEST['po_mode'];
		$erp_inventory_po = TableRegistry::get('erp_inventory_po');
		$po_data = $erp_inventory_po->get($po_id);
		$post_data['po_mode'] = $po_mode ;
		$post_data['approved_status'] = 1;
		$post_data['approved_date'] = date('Y-m-d H:i:s');
		$post_data['approve_by'] = $this->request->session()->read('user_id');
		$data = $erp_inventory_po->patchEntity($po_data,$post_data);
		$erp_inventory_po->save($data);
		die();
	}
	
	public function transferemployee()
	{
		// $erp_projects = TableRegistry::get('erp_projects'); 
		// $projects = $erp_projects->find();
		$projects = $this->ERPfunction->get_projects();
		$this->set('projects',$projects);
		$this->set('user_id',$_REQUEST['user_id']);
		
	}
	public function resignemployee()
	{
		$this->set('user_id',$_REQUEST['user_id']);
	}
	public function employeedata()
	{
		/* $erp_employee = TableRegistry::get('erp_employee'); 
		$result = $erp_employee->find()->where(['employee_id'=>$_REQUEST['employee_id']]); */
		$erp_employee = TableRegistry::get('erp_users'); 
		$result = $erp_employee->find()->where(['user_id'=>$_REQUEST['employee_id']]);
		$retrun_array = array();
		foreach($result as $retrive_data)
		{
			$retrun_array['employee_no']= $retrive_data['employee_no'];
			$retrun_array['employee_at']= $this->ERPfunction->get_projectname($retrive_data['employee_at']);
			$retrun_array['full_name']= $retrive_data['first_name'].' '.$retrive_data['middle_name'].' '.$retrive_data['last_name'];
			$retrun_array['designation_title']= $this->ERPfunction->get_category_title($retrive_data['designation']);
			$retrun_array['designation']=$retrive_data['designation'];
			
		}
		echo json_encode($retrun_array);
		die();
	}
	
	
	public function transfereasset()
	{	
		$request_asset_id = $_REQUEST['asset_id'];
		$erp_asset = TableRegistry::get('erp_assets'); 
		$asset_data = $erp_asset->get($request_asset_id);
		
		$erp_projects = TableRegistry::get('erp_projects'); 
		$this->user_id=$this->request->session()->read('user_id');
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($role == 'constructionmanager' || $role == 'projectdirector')
		{
			if(!empty($projects_ids))
			{
				$or = array();
				$or["project_id IN"] = $projects_ids;
				$projects = $erp_projects->find()->where($or);
			}else{
				$projects = array();
			}
		}
		else{
			$projects = $erp_projects->find();
		}
		
		$this->set('projects',$projects);
		$this->set('asset_id',$request_asset_id);		
		$this->set('asset_data',$asset_data);		
		$this->set('quantity',$_REQUEST['quantity']);		
	}
	
	public function acceptasset()
	{	
		$request_asset_id = $_REQUEST['asset_id'];
		$erp_asset = TableRegistry::get('erp_assets'); 
		$asset_data = $erp_asset->get($request_asset_id);
		
		$erp_projects = TableRegistry::get('erp_projects'); 
		$this->user_id=$this->request->session()->read('user_id');
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($role == 'constructionmanager' || $role == 'projectdirector')
		{
			if(!empty($projects_ids))
			{
				$or = array();
				$or["project_id IN"] = $projects_ids;
				$projects = $erp_projects->find()->where($or);
			}else{
				$projects = array();
			}
		}
		else{
			$projects = $erp_projects->find();
		}
		
		$this->set('projects',$projects);
		$this->set('asset_id',$request_asset_id);		
		$this->set('asset_data',$asset_data);		
		$this->set('quantity',$_REQUEST['quantity']);		
	}
	
	public function issueasset()
	{	
		$request_asset_id = $_REQUEST['asset_id'];
		$erp_asset = TableRegistry::get('erp_assets'); 
		$asset_data = $erp_asset->get($request_asset_id);
		
		$erp_projects = TableRegistry::get('erp_projects'); 
		$this->user_id=$this->request->session()->read('user_id');
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($role == 'constructionmanager' || $role == 'projectdirector')
		{
			if(!empty($projects_ids))
			{
				$or = array();
				$or["project_id IN"] = $projects_ids;
				$projects = $erp_projects->find()->where($or);
			}else{
				$projects = array();
			}
		}
		else{
			$projects = $erp_projects->find();
		}
		
		$this->set('projects',$projects);
		$this->set('asset_id',$request_asset_id);		
		$this->set('asset_data',$asset_data);		
	}
	
	public function bookingasset()
	{	
		$request_asset_id = $_REQUEST['asset_id'];
		$erp_asset = TableRegistry::get('erp_assets'); 
		$asset_data = $erp_asset->get($request_asset_id);
		
		$erp_projects = TableRegistry::get('erp_projects'); 
		$this->user_id=$this->request->session()->read('user_id');
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		$projects = $erp_projects->find();
		
		$this->set('projects',$projects);
		$this->set('asset_id',$request_asset_id);		
		$this->set('asset_data',$asset_data); 
				
	}
	
	public function advancetransfer()
	{
		$request_id = $this->request->data["request_id"];
		//debug(json_decode($request_id));
		//die;
		$this->set('request_id',$request_id);
		$advance = $this->request->data["advance_rs"];
		
		$advance = json_decode($advance);
		//debug($advance);
		//die;
		$total = 0;
		
		foreach($advance as $rs)
			{
				if($rs != '')
				{
					$total = $total + $rs;
				}
			}
		$this->set('cheque_amount',$total);
		$tds = $total * 1/100;
		$with_tds = $total - $tds;
		$this->set('with_tds',$with_tds);
	}
	
	public function managestock()
	{
		$project_id = $this->request->data["project_id"];
		$material_id = $this->request->data["material_id"];
		
		$this->user_id=$this->request->session()->read('user_id');	
		$role = $this->Usermanage->get_user_role($this->user_id);
		
		$this->set('role',$role);
		$this->set('project_id',$project_id);
		$this->set('material_id',$material_id);
		
		$erp_stock_history = TableRegistry::get('erp_stock_history'); 
		if(is_numeric($material_id))
		{
			$request_data = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$request_data = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		$this->set('data',$request_data);
		// var_dump($request_data);die;
	}
	
	public function purchasemanagestock()
	{
		$project_id = $this->request->data["project_id"];
		$material_id = $this->request->data["material_id"];
		
		$this->user_id=$this->request->session()->read('user_id');	
		$role = $this->Usermanage->get_user_role($this->user_id);
		
		$this->set('role',$role);
		$this->set('project_id',$project_id);
		$this->set('material_id',$material_id);
		
		$erp_stock_history = TableRegistry::get('erp_stock_history'); 
		if(is_numeric($material_id))
		{
			$request_data = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$request_data = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		$this->set('data',$request_data);
		// var_dump($request_data);die;
	}
	
	public function soldasset()
	{
		$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $erp_projects->find();
		$this->set('projects',$projects);
		$vendor_tbl = TableRegistry::get('erp_vendor');
		$vendor_list = $vendor_tbl->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->toArray();
		$this->set('vendor_list',$vendor_list);
		$this->set('asset_id',$_REQUEST['asset_id']);
		$this->set('deployed_to',$_REQUEST['deployed_to']);
		
	}
	public function theftasset()
	{
		$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $erp_projects->find();
		$this->set('projects',$projects);
		$this->set('asset_id',$_REQUEST['asset_id']);
		$this->set('deployed_to',$_REQUEST['deployed_to']);
		
	}
	public function groupbyassets()
	{
		$erp_assets = TableRegistry::get('erp_assets');
		$asset_data=$erp_assets->find()->where(['asset_group'=>$_REQUEST['asset_group']]);
		$result_arr = array();
		$defaultmsg  =__( '-- Select Asset -- ');
		$content = '';
		$content .= "<option value=''>".$defaultmsg."</option>";	
		
		foreach($asset_data as $retrive_data)
		{
			
			$asset_name = $this->ERPfunction->get_asset_title($retrive_data['asset_id']);
			$content .= "<option value=".$retrive_data['asset_id'].">".$asset_name."</option>";
		}
		$result_arr['asset_list'] = $content;
		echo json_encode($result_arr);
		die();
	}
	public function namebyassetdata()
	{
		$erp_assets = TableRegistry::get('erp_assets');
		$asset_data=$erp_assets->get($_REQUEST['asset_name']);	
		$result_arr = array();
		$result_arr['asset_code'] = $asset_data['asset_code'];
		$result_arr['capacity'] = $asset_data['capacity'];
		$result_arr['model_no'] = $asset_data['model_no'];
		$result_arr['vehicle_no'] = $asset_data['vehicle_no'];
		$result_arr['quantity'] = $asset_data['quantity'];
		$result_arr['unit'] = $asset_data['unit'];
		$result_arr['asset_group_id'] = $asset_data['asset_group'];
		$result_arr['asset_group_name'] = $this->ERPfunction->get_asset_group_name($asset_data['asset_group']);
		$result_arr['asset_make'] = $this->ERPfunction->get_category_title($asset_data['asset_make']);
		$result_arr['deployed_to'] = $this->ERPfunction->get_projectname($asset_data['deployed_to']);
		$result_arr['deployed_to_id'] = $asset_data['deployed_to'];
		
		echo json_encode($result_arr);
		die();
	}
	
	public function getassetid()
	{
		$erp_assets = TableRegistry::get('erp_assets');
		$asset_data=$erp_assets->get($_REQUEST['asset_name']);	
		$result_arr = array();
		$result_arr['asset_code'] = $asset_data['asset_code'];		
		echo json_encode($result_arr);
		die();
	}
	
	public function employeedetail()
	{
		/* $erp_employee = TableRegistry::get('erp_employee');  */
		$erp_employee = TableRegistry::get('erp_users'); 
		$result = $erp_employee->get($_REQUEST['employee_id']);	
		/* $result = $erp_employee->find()->where(["user_id"=>$_REQUEST['employee_id'],"employee_no !="=>""])->hydrate(false)->toArray();	 */
		$result_arr = array();
		$result_arr['full_name'] = $result['first_name'].' '.$result['middle_name'].' '.$result['last_name'];
		$result_arr['designation'] = $this->ERPfunction->get_category_title($result['designation']);
		$result_arr['employee_at'] = $this->ERPfunction->get_projectname($result['employee_at']);
		$result_arr['epf_no'] = $result['epf_no'];
		//$result_arr['esi_no'] = $result['epf_no'];
		$result_arr['date_of_birth'] = $this->ERPfunction->get_date($result['date_of_birth']);
		$result_arr['date_of_joining'] = $this->ERPfunction->get_date($result['date_of_joining']);
		$result_arr['pancard_no'] = $result['pan_card_no'];
		$result_arr['payment'] = $result['payment'];
		//$result_arr['da_rate'] = $result['capacity'];
		
		$curr_month = date("n");
		$curr_year = date("Y");
		$month_days = cal_days_in_month(CAL_GREGORIAN, $curr_month, $curr_year);			
		
		$leave_tbl = TableRegistry::get("erp_leavesheet");
		$leave_data = $leave_tbl->find()->where(["employee_no"=>$_REQUEST['employee_id'],"month" => $curr_month,"year"=>$curr_year])->select(["leave_id","leave_detail"])->hydrate(false)->toArray();
		if(!empty($leave_data))
		{
			$result_arr['payable_days'] = $month_days - $leave_data[0]["leave_detail"];
		}else{
				$result_arr['payable_days'] = $month_days;
		}
	
		$result_arr["cons_pay"] = $result["total_salary"];
		$result_arr["pay_rate"] = $result_arr["cons_pay"] / $month_days;
		$result_arr["pay_wa"] = $result_arr["pay_rate"] * $result_arr['payable_days'];
		
		echo json_encode($result_arr);
		die();		
	}
	
	public function approvemaexpenses()
	{
		$maintenace_id = $_REQUEST['maintenace_id'];
		$erp_maintenance= TableRegistry::get('erp_assets_maintenance');
		$maintenance_data = $erp_maintenance->get($maintenace_id);
		$post_data['approved_status'] = 1;
		$post_data['approved_date'] = date('Y-m-d H:i:s');
		$post_data['approve_by'] = $this->request->session()->read('user_id');
		$data = $erp_maintenance->patchEntity($maintenance_data,$post_data);
		$erp_maintenance->save($data);
		die();
	}
	
	public function viewsale()
	{
		$erp_assets = TableRegistry::get('erp_assets'); 
		$erp_soldasset = TableRegistry::get('erp_assets_sold_history');
		$solddata = $erp_soldasset->find()->where(array('asset_id'=>$_REQUEST['asset_id']));
		$assets = $erp_assets->get($_REQUEST['asset_id']);
		$assetname =$assets['asset_name']; 
		$this->set('solddata',$solddata);
		$this->set('assetname',$assetname); 
	
	}
	
	public function viewtheftdetails()
	{
		$erp_assets = TableRegistry::get('erp_assets'); 
		$erp_theftasset = TableRegistry::get('erp_assets_theft_history');
		$theftdata = $erp_theftasset->find()->where(array('asset_id'=>$_REQUEST['asset_id']));
		$assets = $erp_assets->get($_REQUEST['asset_id']);
		$assetname =$assets['asset_name']; 
		$this->set('theftdata',$theftdata);
		$this->set('assetname',$assetname); 
	}
	
	public function viewtransfer()
	{	
		$asset_ids = array();
		$erp_assets = TableRegistry::get('erp_assets'); 
		$erp_transferasset = TableRegistry::get('erp_assets_history');
		$asset_data = $erp_assets->find()->where(array('asset_code'=>$_REQUEST['asset_code']));
		foreach($asset_data as $a_data)
		{
			$asset_ids[] = $a_data["asset_id"];
		}
		$transferdata = $erp_transferasset->find()->where(['asset_id IN'=>$asset_ids])->hydrate(false)->toArray();
		$assets = $erp_assets->get($_REQUEST['asset_id']);
		$assetname =$assets['asset_name']; 
		$purchase_quantity =$assets['purchase_quantity'];
		$this->set('transferdata',$transferdata);
		$this->set('assetname',$assetname); 
		$this->set('purchase_quantity',$purchase_quantity); 
	
	}
	public function viewissuedasset()
	{	
		$asset_id = $_REQUEST['asset_id'];
		$role = $this->role;
		$asset_issued_history = TableRegistry::get('erp_asset_issued_history');
		$issuedata = $asset_issued_history->find()->where(['asset_id'=>$asset_id])->order(["issued_date"=>"desc"])->hydrate(false)->toArray();
		$this->set('role',$role);
		$this->set('issuedata',$issuedata);
		$this->set('asset_id',$asset_id);  
	
	}
	public function viewmaintenancedetials()
	{
		$erp_assets = TableRegistry::get('erp_assets'); 
		$erp_maintenance = TableRegistry::get('erp_assets_maintenance');
		$maintenancedata = $erp_maintenance->find()->where(array('asset_id'=>$_REQUEST['asset_id']));
		$assets = $erp_assets->get($_REQUEST['asset_id']);
		$assetname =$assets['asset_name']; 
		$this->set('maintenancedata',$maintenancedata);
		$this->set('assetname',$assetname); 
	
	}

	public function getreferenceno(){
		$this->autoRender=false;
		
		if($this->request->is('ajax')){
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_grn();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
			$result_arr['short_name'] = ($retrive_data['short_name'] != '')?$retrive_data['short_name']:'-';			
		}
		/* $conn = ConnectionManager::get('default');
		$result = $conn->execute('SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = "erp_contract_inward" ');
		$number='';
		foreach($result as $incre){
				$number=(int)$incre['auto_increment'];
		}
		 */
		
		/* $new_grnno = sprintf("%09d", $number);
		$new_grnno2 = sprintf("%09d", $number);
		$grn_no = $result_arr['project_code'].'/IN/'.$new_grnno;
		$result_arr['reference_no'] = $grn_no;
		$result_arr['auto2'] = $new_grnno2; */
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_contract_inward","inward_id","reference_no");
		$new_grnno = sprintf("%09d", $number1);
		$new_grnno2 = sprintf("%09d", $number1);
		$grn_no = $result_arr['project_code'].'/IN/'.$new_grnno;
		$result_arr['reference_no'] = $grn_no;
		$result_arr['auto2'] = $new_grnno2;
		echo json_encode($result_arr);	
		}
	}



	public function getoutwardno(){
		$this->autoRender=false;
		
		if($this->request->is('ajax')){
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_grn();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		/* $conn = ConnectionManager::get('default');
		$result = $conn->execute('SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = "erp_contract_outward" ');
		$number='';
		foreach($result as $incre){
				$number=(int)$incre['auto_increment'];
		}
		$new_grnno = sprintf("%09d", $number);
		$grn_no = $result_arr['project_code'].'/OW/'.$new_grnno;
		$result_arr['reference_no'] = $grn_no; */
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_contract_outward","outward_id","reference_no");
		$new_grnno = sprintf("%09d", $number1);
		$grn_no = $result_arr['project_code'].'/OW/'.$new_grnno;
		$result_arr['reference_no'] = $grn_no;
		
		echo json_encode($result_arr);
	
		}
	}
	

   public function getrabillproject(){
		$this->autoRender=false;
		
		if($this->request->is('ajax')){
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_grn();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		/* $conn = ConnectionManager::get('default');
		$result = $conn->execute('SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = "erp_contract_rabill" ');
		$number='';
		foreach($result as $incre){
				$number=(int)$incre['auto_increment'];
		}
		$new_grnno = sprintf("%09d", $number); 
		$grn_no = $result_arr['project_code'].'/RA/'.$new_grnno; 
		 */
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_contract_rabill","ra_bill_id","ra_bill_no");
		$new_grnno = sprintf("%02d", $number1);
		$grn_no = $result_arr['project_code'].'/RA/'.$new_grnno;		
		$result_arr['reference_no'] = $grn_no;
		echo json_encode($result_arr);
	
		}
	}

	public function getpricevariationno(){
		$this->autoRender=false;
		
		if($this->request->is('ajax')){
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_grn();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		/* $conn = ConnectionManager::get('default');
		$result = $conn->execute('SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = "erp_contract_pricevariation" ');
		$number='';
		foreach($result as $incre){
				$number=(int)$incre['auto_increment'];
		} */
		/* $new_grnno = sprintf("%09d", $number); */
		/* $grn_no = $result_arr['project_code'].'/PV/'.$new_grnno; */
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_contract_pricevariation","price_variation_id","bill_no");
		$new_grnno = sprintf("%09d", $number1);
		$grn_no = $result_arr['project_code'].'/PV/'.$new_grnno;
		$result_arr['reference_no'] = $grn_no;
		echo json_encode($result_arr);
	
		}
	}

	public function getinwardbill(){
		
		$this->autoRender=false;		
		if($this->request->is('ajax')){
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		$prepare_count = $this->getlast_prepare_grn();
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		/* $conn = ConnectionManager::get('default');
		$result = $conn->execute('SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = "erp_inward_bill" ');
		$number='';
		
		foreach($result as $incre){
				$number=(int)$incre['auto_increment'];
		} */
		
		$number = $this->ERPfunction->generate_auto_id($project_id,"erp_inward_bill","inward_bill_id","inward_bill_no");

		$new_grnno = sprintf("%09d", $number);
		$grn_no = $result_arr['project_code'].'/BIN/'.$new_grnno;
		$powo_no=$result_arr['project_code'].'/P/'.$new_grnno;
		$result_arr['po_no']=$powo_no;
		$result_arr['reference_no'] = $grn_no;
		echo json_encode($result_arr);
	
		}
	}
	
	public function inwardbillchecked(){
		$this->autoRender=false;
		if($this->request->is('ajax')){			
			$request_id = json_decode($this->request->data["request_id"]);
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$user_create=$this->request->session()->read('user_id');
					date_default_timezone_set('asia/kolkata');
					$date=date('Y-m-d H:i:s');
					$table_register_inward_bill=TableRegistry::get('erp_inward_bill');
					$row = $table_register_inward_bill->get($req_id);
					$row['status_inward'] = 'checked';
					$row['checked_date'] = $date;
					$row['checked_by'] = $user_create;
					$check=$table_register_inward_bill->save($row);
				}
			}
		}
	}

	public function inwardbillapprove(){
		$this->autoRender=false;
		if($this->request->is('ajax')){			
			$request_id = json_decode($this->request->data["request_id"]);
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$user_create=$this->request->session()->read('user_id');
					date_default_timezone_set('asia/kolkata');
					$date=date('Y-m-d H:i:s');
					$table_register_inward_bill=TableRegistry::get('erp_inward_bill');
					$row = $table_register_inward_bill->get($req_id);
					// $row['status_inward'] = 'completed';
					$row['status_inward'] = 'approved';
					$row['pending_approve_date'] = $date;
					$row['pending_approve_by'] = $user_create;
					$check=$table_register_inward_bill->save($row);
				}
			}
		}
	}

	public function inwardbillaccept(){
		$this->autoRender=false;
		if($this->request->is('ajax')){			
			$request_id = json_decode($this->request->data["request_id"]);
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$user_create=$this->request->session()->read('user_id');
					date_default_timezone_set('asia/kolkata');
					$date=date('Y-m-d H:i:s');
					$table_register_inward_bill=TableRegistry::get('erp_inward_bill');
					$row = $table_register_inward_bill->get($req_id);
					// $row['status_inward'] = 'completed';
					$row['status_inward'] = 'accept';
					$row['accept_date'] = $date;
					$row['accept_by'] = $user_create;
					$check=$table_register_inward_bill->save($row);
				}
			}
		}
	}


	public function inwardacceptbill(){
		$this->autoRender=false;
		if($this->request->is('ajax')){
			$table_register_inward_bill=TableRegistry::get('erp_inward_bill');
			$inward_id=$this->request->data['i_id'];
			/* $tally_inward_no = $this->request->data['tally']; */
			$paid_amount = $this->request->data['paid_amount'];
			$payment_date = date("Y-m-d");
			$cheque_no = $this->request->data['cheque_no'];
			$bank = $this->request->data['bank'];
			
			
			$user_create=$this->request->session()->read('user_id');
			date_default_timezone_set('asia/kolkata');
			$date=date('Y-m-d H:i:s');
			$query = $table_register_inward_bill->query();
			$query->update()
    		->set(['status_inward'=>'completed',
			/* 'tally_inward_no'=>$tally_inward_no, */
			"paid_amount"=>$paid_amount,
			"payment_date"=>$payment_date,
			"cheque_no"=>$cheque_no,
			"bank"=>$bank,
			'completed_by'=>$user_create,'completed_date'=>$date])
    		->where(['inward_bill_id' => $inward_id])
    		->execute();
    		if($query){
    			
    		}
		}
	}

	
	public function inwardacceptbillmultiple()
	{
		$this->autoRender=false;
		if($this->request->is('ajax')){
			$table_register_inward_bill=TableRegistry::get('erp_inward_bill');
			$inwardids=$this->request->data['i_id'];
			$inward_ids = json_decode($inwardids);
			if(!empty($inward_ids))
			{
				foreach($inward_ids as $inward_id)
				{
					/* $tally_inward_no = $this->request->data['tally']; */
					$paid_amount = $this->request->data['paid_amount'];
					$payment_date = $this->request->data['cheque_date'];
					$cheque_no = $this->request->data['cheque_no'];
					$bank = $this->request->data['bank'];					
					
					$user_create=$this->request->session()->read('user_id');
					date_default_timezone_set('asia/kolkata');
					$date=date('Y-m-d H:i:s');
					$query = $table_register_inward_bill->query();
					$query->update()
					->set(['status_inward'=>'completed',
					/* 'tally_inward_no'=>$tally_inward_no,*/
					"paid_amount"=>$paid_amount,
					"payment_date"=>$payment_date,
					"cheque_no"=>$cheque_no,
					"bank"=>$bank,
					'completed_by'=>$user_create,'completed_date'=>$date])
					->where(['inward_bill_id' => $inward_id])
					->execute();
					if($query){						
					}
				}
				/* MAIL TO PARTY */
				// $total_amount = 0;
				// $designation = ["Project Director","Site Accountant","Construction Manager","Sr.Accountant"];
				// $cat_ids = $this->ERPfunction->get_cat_id_by_title($designation);
				
				// $all_email = array();
				// foreach($inward_ids as $inward_id)
				// {
					// $temp = array();
					// $row = $table_register_inward_bill->get($inward_id);
					// $project_id = $row->project_id;
					
					// $temp = $this->ERPfunction->get_email_id_by_project($project_id,$cat_ids);
					// $all_email = array_merge($temp,$all_email);
					// $invoice_no[] = $row->invoice_no;
					// $invoice_date[] = $row->date->format("d-m-Y");
					// $bill_inward_no[] = $row->inward_bill_no;
					// $total_amount += $row->total_amt;
					// $str = $row->party_name;
					// $chk = strpos($str,"NEC");
					// if($chk == 1) /*Is Agency*/
					// {
						// $emails[] = $this->ERPfunction->get_agency_email($str);
					// }else{
						// $emails[] = $this->ERPfunction->get_vendor_email($str);
					// }
				// }
				
				// $is_agencry = strpos($row->party_name,"NEC");									
				// if(($row->party_name == "0" || $is_agencry == 1 ) && $row->party_type == "old" )
				// {
					// $party_name =  $this->ERPfunction->get_agency_name_by_code($row->party_name);
				// }
				// else if($row->party_type == "new")
				// {
					// $party_name = $row->new_party_name;
				// }
				// else
				// {
					// $party_name = $this->ERPfunction->get_vendor_name($row->party_name);										
				// }
				
				//echo json_encode($temp);
				// $all_email = array_unique($all_email);
				// $emails[] = "vipul.desai@yashnandeng.com";
				// $emails = array_merge($emails,$all_email);
				
				// $email=array_values(array_diff($emails,array("null","")));
				
				// $to = implode(",",$email);
				// $invoices = implode(",",$invoice_no);
				// $bills = implode(",",$bill_inward_no);
				// $invoice_dates = implode(",",$invoice_date);
				// $invoice_dates = implode(",",$invoice_date);
				// $headers = "MIME-Version: 1.0" . "\r\n";
				// $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				// $headers .= 'From: <Yashnand Eng>' . "\r\n";
				
				// $subject = "YashNand: Bill Payment Notification";
				
				// $message = "Sir / Madam,<br />";
				// $message .= "Your bills have been paid.Details for the same are below [After deduction of Advances, Credits, Debits, Retention etc.]";
				// $message .= "<br /><br /><p><strong>Party's Name:</strong> {$party_name}</p>";
				// $message .= "<p><strong>Invoice
				// No:</strong> {$invoices}</p>";
				// $message .= "<p><strong>Invoice Dates:</strong> {$invoice_dates}</p>";
				// $message .= "<p><strong>Total Amount:</strong> {$total_amount}</p>";
				
				// $message .= "<br />";
				// /* $message .= "<p><strong>Bill Inward No.: {$bills}</p>";*/
				// $message .= "<p><strong>Bank:</strong> {$bank}</p>";
				// $message .= "<p><strong>Cheque No:</strong> {$cheque_no}</p>";
				// $message .= "<p><strong>Cheque Date:</strong> {$payment_date}</p>";
				// $message .= "<p><strong>Cheque Amount:</strong> {$paid_amount}</p>";
				// $message .= "<br /><br />";
				// $message .= "<p><strong>Please collect your cheque from Corporate Office during working hours.</strong></p>";
				// $message .= "<br /><br />";			
				// $message .= "Thank You.";
				
				// $message .= "<br /><br />";			
				// $message .= "-------------------------------------------------------------------------------------------------------------";
				// $message .= "<br /><br />";
				
				// $message .= "Please Do Not Reply to this E-mail ID. This E-mail is system generated and may have some problems. For conformation and/or queries,<br />please contact:";
				// $message .= "<p><strong>Contact No: 079-23240202</strong></p>";
				// $message .= "<p><strong>E-mail ID:</strong> <a href='mailto:mahesh.chaudhary@yashnandeng.com'>mahesh.chaudhary@yashnandeng.com</a></p>";
				
				// $message .= "-------------------------------------------------------------------------------------------------------------";
				
				
				// mail($to,$subject,$message,$headers);
				/* MAIL TO PARTY */
    		}
		}
	}
	
	
	public function generateassetidname()
	{
		$this->autoRender = false;
		if($this->request->is('ajax'))
		{
			$asset_tbl = TableRegistry::get("erp_assets");			
			$asset_group = $this->request->data["asset_group"];
			$list = $asset_tbl->find()->where(["asset_group"=>$asset_group])->hydrate(false)->toArray();		
			$data = "";
			if(!empty($list))
			{
				foreach($list as $record)
				{
					$data.="<option value='{$record['asset_id']}'>{$record['asset_name']}</option>";
				}
				$row["name"] = $data;
			}
		}
		$prepare_count = $this->get_last_asset_id();
		$new_assetno = sprintf("%09d", $prepare_count + 1);  
		$asset_code = 'YNEC/AST/'.$this->ERPfunction->get_asset_group_code($asset_group ).'/'.$new_assetno;
		$row['asset_code'] = $asset_code;
		echo json_encode($row);		
	}
	
	public function getvendorid()
	{
		$vendor_userid = $_REQUEST['vendor_id'];
		$usersdetail = TableRegistry::get('erp_vendor'); 
		$user_data = $usersdetail->get($vendor_userid)->toArray();
		echo $user_data["vendor_id"];
		die();
	}
	
	// public function getpoitems()
	// {
		// $po_id = $_REQUEST["po_id"];
		//// $po_id = 33;
	
		// $mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		// $po_tbl = TableRegistry::get("erp_inventory_po");
		
		// $pr_id = $po_tbl->find()->where(["po_id"=>$po_id])->hydrate(false)->toArray();
		
		// if(!empty($pr_id))
		// {
		// $pr_id = $pr_id[0]["pr_id"];
		// $data = $mat_tbl->find()->where(["pr_id"=>$pr_id,"approved"=>1])->hydrate(false)->toArray();
		// $i = 0;
		// $row='';
			// if(!empty($data))
			// {
				// foreach($data as $material)
				// {
					// $row .= '<tr class="cpy_row">
					// <td>'.$material["material_id"].'</td>
						// <td>'.$this->ERPfunction->get_material_title($material["material_id"]).'	<input type="hidden" name="material[material_id][]" readonly = "true" value="'.$material["pr_material_id"].'" id="material_id_'.$i.'"/></td>
						// <td>'.$this->ERPfunction->get_brand_name($material['brand_id']).'</td>
						// <td> <input type="text" name="material[quantity][]" readonly = "true" value="'.$material["quantity"].'" id="quantity_'.$i.'"/></td>
						// <td><input type="text" name="material[actual_qty][]" class="actualy_qty" value="" data-id="'.$i.'" id="actual_qty_'.$i.'"/></td>
						// <td><input type="text" name="material[difference_qty][]" readonly = "true" value="" id="difference_qty_'.$i.'"/></td>
						// <td>'.$this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material["material_id"])).'										
						// <input type="hidden" name="pr_mid[]" value="'.$material["pr_material_id"].'">
						// </td>
					// </tr>';
					// /* <td><input type="text" name="material[remarks][]" value="" id="remarks_'.$i.'"/></td>	 */
					// $i++;
				// }
			// }else{
				// $row = "<tr><td td colspan='7' align='center'>No Record Found.</td></tr>";
			// }
		// }
		// else{
			// $row = "<tr><td colspan='7'>None</td></tr>";
		// }
		// $array_data["po_data"] = $row;
		// echo json_encode($array_data);
		// die;
	// }
	
	public function getpoitems()
	{
		$po_id = $_REQUEST["po_id"];	
		$mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		$po_tbl = TableRegistry::get("erp_inventory_po");
		$pod_tbl = TableRegistry::get("erp_inventory_po_detail");
		
		$pr_id = $po_tbl->find()->where(["po_id"=>$po_id])->select(["pr_id","vendor_userid","vendor_id","po_date"])->hydrate(false)->toArray();
		/* $data = $pod_tbl->find()->where(["po_id"=>$po_id,"approved"=>0])->hydrate(false)->toArray();	 */
		$data = $pod_tbl->find()->where(["po_id"=>$po_id,"approved"=>1])->hydrate(false)->toArray();	
		
		$array_data["vendor"] = $pr_id[0]["vendor_userid"];
		$array_data["vendor_id"] = $pr_id[0]["vendor_id"];
		$array_data["po_date"] = date("d-m-Y",strtotime($pr_id[0]["po_date"]));
		
		$i = 1;
		$row='';
		if(!empty($data))
		{
			foreach($data as $material)
			{
				// debug($material);die;
				if(is_numeric($material['material_id']))
				{
					$m_code = is_numeric($material['material_id'])?$this->ERPfunction->get_material_item_code_bymaterialid($material['material_id']):$material['m_code'];
					
					$hidden_m_code = is_numeric($material['material_id'])?'':$material['m_code'];
					
					$mt = is_numeric($material['material_id'])?$this->ERPfunction->get_material_title
					($material['material_id']):$material['material_id'];
					
					$brnd = is_numeric($material['brand_id'])?$this->ERPfunction->get_brand_name($material["brand_id"]):$material["brand_id"];
					
					$unit = is_numeric($material['material_id'])?$this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material['material_id'])):$material['static_unit'];
					
					$row .= '<tr class="cpy_row">
					<td>'.$m_code.'</td>
						<td>'.$mt.'<input type="hidden" value="'.$i.'" name="row_number" class="row_number" required="">	<input type="hidden" name="material[material_id][]" readonly = "true" value="'.$material["material_id"].'" id="material_id_'.$i.'"/>
						<input type="hidden" name="material[m_code][]" readonly = "true" value="'.$hidden_m_code.'" id="m_code_'.$i.'"/><input type="hidden" name="material[po_detail_id][]" readonly = "true" value="'.$material["id"].'"/></td>
						<td><input type="hidden" name="material[brand_id][]" value="'.$material["brand_id"].'" id="brand_id_'.$i.'"/>'.$brnd.'</td>
						<td> <input style="padding-left:0;padding-right:0" type="text" name="material[grn_remain_qty][]" readonly="true" value="'.$material["grn_remain_qty"].'" class="validate[required,min[0]]" /></td>
						<td> <input style="padding-left:0;padding-right:0" type="text" name="material[quantity][]" data-id="'.$i.'" id="quantity_'.$i.'" class="vendor_quentity validate[required,min[0]]" /></td>
						<td><input style="padding-left:0;padding-right:0" type="text" name="material[actual_qty][]" value="" data-id="'.$i.'" id="actual_qty_'.$i.'" class="actualy_qty validate[required]"/></td>
						<td><input style="padding-left:0;padding-right:0" type="text" name="material[difference_qty][]" readonly="true" value="" id="difference_qty_'.$i.'"/></td>
						<td>'.$unit.'
						<input type="hidden" name="material[static_unit][]" readonly = "true" value="'.$unit.'" id="static_unit_'.$i.'"/>
						 <input type="hidden" name="po_mid[]" value="'.$material["id"].'">
						 <input type="hidden" name="material[total_qty][]" value="'.$material["used_qty"].'" />
						</td>
						<td><input style="padding-left:0;padding-right:0" type="text" name="material[unit_price][]" readonly="true" value="'.$material['unit_price'].'" id="unit_price_'.$i.'"/></td>
						
						<td><input style="padding-left:0;padding-right:0" type="text" name="material[discount][]" readonly="true" value="'.$material['discount'].'" id="dis_'.$i.'"/></td>
						<td><input style="padding-left:0;padding-right:0" type="text" name="material[gst][]" readonly="true" value="'.$material['gst'].'" id="gst_'.$i.'"/></td>
						<td><input style="padding-left:0;padding-right:0" type="text" name="material[amount][]" readonly="true" class="amount" value="'.$material['amount'].'" id="amount_'.$i.'"/></td>
						<td><input style="padding-left:0;padding-right:0" type="text" name="material[single_amount][]" readonly="true" class="single_amount" value="'.$material['single_amount'].'" id="single_amount_'.$i.'"/></td>

						<td><input type="text" name="material[remark][]" value="" id="remark_'.$i.'"/></td>
						<td><a href="javascript::void(0)" class="btn btn-danger del_item" title="Delete">Delete</a></td>
					</tr>';
					/* removed qty and readonly <input type="text" name="material[quantity][]" readonly = "true" value="'.$material["quantity"].'" id="quantity_'.$i.'"/>*/
					
					/*<td>'.$this->ERPfunction->get_brandname_by_po_material($pr_id[0]['pr_id'],$material['material_id']).'</td> 
					<input type="hidden" name="pr_mid[]" value="'.$material["pr_material_id"].'"> */
					/* <td><input type="text" name="material[remarks][]" value="" id="remarks_'.$i.'"/></td>	 */
					$i++;
				}
			}
		}else{
			$row = "<tr><td td colspan='7' align='center'>No Record Found.</td></tr>";
		}
			
		$array_data["po_data"] = $row;
		echo json_encode($array_data);
		die;
	}
		
	public function approvegrn()
	{
		$this->autoRender = false;
		$data = $this->request->data();
		$grn_saved_id = array();
		$audit_id_array = array();
        foreach($data['entry']  as $key => $value)
		{
			$entry = $data["entry"][$key];
			$grn_id = $data["grn_id"][$key];
			$detail_id = $data["detail_id"][$key];
			$project_id = $data["project_id"][$key];
			$project_code = $data["project_code"][$key];
			$material_id = $data["material_id"][$key];
			$quantity = $data["quantity"][$key];
			$actual_qty = $data["actual_qty"][$key];
			$static_unit = $data["static_unit"][$key];
		
			$history_tbl = TableRegistry::get("erp_stock_history");
			$grnd_tbl = TableRegistry::get("erp_inventory_grn_detail");
			$stock_tbl = TableRegistry::get("erp_stock");
			if($material_id != 0 && is_numeric($material_id))
			{
				$check_stock = $stock_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id])->hydrate(false)->toArray();		
				$result = array();
			}
			else
			{
				$check_stock = $stock_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id])->hydrate(false)->toArray();		
				$result = array();
			}
			
			
			if(!empty($check_stock))
			{	
				if($material_id != 0 && is_numeric($material_id))
				{
				$query = $stock_tbl->query();
				$query->update()
					->set(['quantity' => $check_stock[0]["quantity"] + intval($actual_qty)])
					->where(['project_id' => $project_id,'material_id'=>$material_id])
					->execute();
				}
				else
				{
					$query = $stock_tbl->query();
					$query->update()
					->set(['quantity' => $check_stock[0]["quantity"] + intval($actual_qty)])
					->where(['project_id' => $project_id,'material_name'=>$material_id])
					->execute();
				}
				$result[] = "history Row Updated";			
			}else{
				$stock_row = $stock_tbl->newEntity();
				$stock_data["project_id"] = $project_id;
				if($material_id != 0 && is_numeric($material_id))
				{
					$stock_data["material_id"] = $material_id;
				}
				else
				{
					$stock_data["material_name"] = $material_id;
					$stock_data["material_id"] = 0;
				}
				$stock_data["quantity"] = $actual_qty;
				$stock_data["static_unit"] = $static_unit;
				$stock_row = $stock_tbl->patchEntity($stock_row,$stock_data);
				$insert_stock_row = $stock_tbl->save($stock_row);
				$result[] = "stock Row Inserted";
			}
			
			$grn_tbl = TableRegistry::get("erp_inventory_grn");
			$grndata = $grn_tbl->get($grn_id);
			$grn_date = $grndata->grn_date;
			
			$row = $history_tbl->newEntity();
			$insert_data = array();
			$insert_data["date"] = $grn_date;		
			$insert_data["project_id"] = $project_id;
			if($material_id != 0 && is_numeric($material_id))
			{
				$insert_data["material_id"] = $material_id;
			}
			else
			{
				$insert_data["material_name"] = $material_id;
				$insert_data["material_id"] = 0;
			}
			$insert_data["static_unit"] = $static_unit;
			$insert_data["quantity"] = $actual_qty;
			$insert_data["stock_in"] = $actual_qty;
			$insert_data["type"] = $entry;
			$insert_data["type_id"] = $grn_id;
			$row = $history_tbl->patchEntity($row,$insert_data);
			$insert_row = $history_tbl->save($row);
			$result[] = "history Row Inserted";
			
			// GRN Alert (Purchase / Accounts) show record code

			$grn = TableRegistry::get("erp_inventory_grn");				
			// $set_approve = $grn->get($grn_id);
			// $set_approve->approved_status = 1;
			// $grn->save($set_approve);
			// GRN Alert (Purchase / Accounts) show record code end

			if($material_id != 0 && is_numeric($material_id))
			{
				$grn_row = $grnd_tbl->find()->where(["grn_id"=>$grn_id,"material_id"=>$material_id]);
			}
			else
			{
				$grn_row = $grnd_tbl->find()->where(["grn_id"=>$grn_id,"material_name"=>$material_id]);
			}
			
			if($material_id != 0 && is_numeric($material_id))
			{
				$grn_row = $grnd_tbl->query();
				$grn_row->update()
					->set(["approved"=>1,"approved_date"=>date("Y-m-d"),"approved_time"=>date("H:i:s"),"approved_by"=> $this->request->session()->read('user_id')])
					->where(["grn_id"=>$grn_id,"material_id"=>$material_id,"grndetail_id"=>$detail_id])
					->execute();
			}
			else
			{
				$grn_row = $grnd_tbl->query();
				$grn_row->update()
					->set(["approved"=>1,"approved_date"=>date("Y-m-d"),"approved_time"=>date("H:i:s"),"approved_by"=> $this->request->session()->read('user_id')])
					->where(["grn_id"=>$grn_id,"material_name"=>$material_id,"grndetail_id"=>$detail_id])
					->execute();
			}
				
			/* Add Record in GRN Audit Table */
			$erp_audit_grn = TableRegistry::get('erp_audit_grn');
			$erp_audit_grn_detail = TableRegistry::get('erp_audit_grn_detail');
			
			$already_exist_grn = $erp_audit_grn->find()->where(["grn_id"=>$grn_id])->hydrate(false)->toArray();
			if(empty($already_exist_grn))
			{
				$grn_definition_row = $grn_tbl->find()->where(["grn_id"=>$grn_id])->hydrate(false)->toArray();
				$grn_definition_row = $grn_definition_row[0];
				
				$entity_data = $erp_audit_grn->newEntity();			
				$audit_data=$erp_audit_grn->patchEntity($entity_data,$grn_definition_row);
				
				if(!in_array($grn_id, $grn_saved_id))
				{
					if($erp_audit_grn->save($audit_data))			
					{
						$grn_saved_id[] = $grn_id;
						$audit_id_array[$grn_id] = $audit_data->audit_id;
						$grn_value_row = $grnd_tbl->find()->where(["grndetail_id"=>$detail_id])->hydrate(false)->toArray();
						$grn_value_row = $grn_value_row[0];
						$grn_value_row["audit_id"] = $audit_data->audit_id;
						$d_entity_data = $erp_audit_grn_detail->newEntity();			
						$d_audit_data=$erp_audit_grn_detail->patchEntity($d_entity_data,$grn_value_row);
						$erp_audit_grn_detail->save($d_audit_data);
					}
				}else{
					$grn_value_row = $grnd_tbl->find()->where(["grndetail_id"=>$detail_id])->hydrate(false)->toArray();
					$grn_value_row = $grn_value_row[0];
					$grn_value_row["audit_id"] = $audit_id_array[$grn_id];
					$d_entity_data = $erp_audit_grn_detail->newEntity();			
					$d_audit_data=$erp_audit_grn_detail->patchEntity($d_entity_data,$grn_value_row);
					$erp_audit_grn_detail->save($d_audit_data);
				}
			}else{
				$grn_value_row = $grnd_tbl->find()->where(["grndetail_id"=>$detail_id])->hydrate(false)->toArray();
				$grn_value_row = $grn_value_row[0];
				$grn_value_row["audit_id"] = $already_exist_grn[0]['audit_id'];
				$d_entity_data = $erp_audit_grn_detail->newEntity();			
				$d_audit_data=$erp_audit_grn_detail->patchEntity($d_entity_data,$grn_value_row);
				$erp_audit_grn_detail->save($d_audit_data);
			}
			/* Add Record in GRN Audit Table */
			$result[] = "GRN Row updated";
		}		
		
			echo json_encode($result);
			die;
	}
	
	public function accountapprovegrn()
	{
		$grn_id = $this->request->data["grn_id"];		
		$grn_tbl = TableRegistry::get("erp_inventory_grn");
		$row = $grn_tbl->get($grn_id);
		$row->show_in_account = 1;
		$grn_tbl->save($row);
		die;
	}
	
	
	public function approveis()
	{
		$this->autoRender = false;
		
		$is_id = json_decode($this->request->data['is_id']);
		$is_detail_id = json_decode($this->request->data['is_detail_id']);
		$project_id = json_decode($this->request->data['project_id']);
		$material_id = json_decode($this->request->data['material_id']);
		$quantity = json_decode($this->request->data['quantity']);
		
		// $is_id = $_REQUEST['is_id'];
		// $is_detail_id = $_REQUEST['is_detail_id'];
		// $project_id = $_REQUEST['project_id'];		
		// $material_id = $_REQUEST['material_id'];		
		// $quantity = $_REQUEST['quantity'];	
		$i = 0;
		foreach($is_detail_id as $is_detail_id)
		{
			$history_tbl = TableRegistry::get("erp_stock_history");
			$total_qty = 0;
			
			$is_detail_tbl = TableRegistry::get('erp_inventory_is_detail');
			$is_data = $is_detail_tbl->get($is_detail_id);		
			$is_data->approved= 1;
			$is_data->approved_date = date('Y-m-d');
			$is_data->approved_by = $this->request->session()->read('user_id');	
			$is_detail_tbl->save($is_data);

			$stock_tbl = TableRegistry::get("erp_stock");
			$insert = array();
			
			$is_tbl = TableRegistry::get("erp_inventory_is");
			$isdata = $is_tbl->get($is_id[$i]);
			$is_date = $isdata->is_date;
			$history_row = $history_tbl->newEntity();
			$insert["date"] = $is_date;
			$insert["project_id"] = $project_id[$i];
			$insert["material_id"] = $material_id[$i];
			$insert["quantity"] = $quantity[$i];
			$insert["stock_out"] = $quantity[$i];			
			$insert["type"] = "is";
			$insert["type_id"] = $is_id[$i];
			$history_row = $history_tbl->patchEntity($history_row,$insert);
			$history_tbl->save($history_row);			
		
			$check_stock = $stock_tbl->find("all")->where(["project_id"=>$project_id[$i],"material_id"=>$material_id[$i]])->hydrate(false)->toArray();		

			if(!empty($check_stock))
			{			
				$query = $stock_tbl->query();
				$query->update()
					->set(['quantity' => $check_stock[0]["quantity"] - intval($quantity[$i])])
					->where(['project_id' => $project_id[$i],'material_id'=>$material_id[$i]])
					->execute();
			}
			else{
				$stock_row = $stock_tbl->newEntity();
				$stock_data["project_id"] = $project_id[$i];
				$stock_data["material_id"] = $material_id[$i];
				$stock_data["quantity"] = $quantity[$i];			
				$stock_row = $stock_tbl->patchEntity($stock_row,$stock_data);
				$stock_tbl->save($stock_row);
			}	
			$i++;
		}
	}
		
	public function approverbn()
	{
		ini_set('memory_limit', '-1');
		$this->autoRender = false;
		
		$rbn_id = json_decode($this->request->data['rbn_id']);
		$rbn_detail_id = json_decode($this->request->data['rbn_detail_id']);
		$project_id = json_decode($this->request->data['project_id']);
		$material_id = json_decode($this->request->data['material_id']);
		$return_qty = json_decode($this->request->data['return_qty']);
	
		
		$i = 0;
		foreach($rbn_detail_id as $rbn_detail_id)
		{
			$erp_inventory_rbn_detail = TableRegistry::get('erp_inventory_rbn_detail');
			$po_data = $erp_inventory_rbn_detail->get($rbn_detail_id);
			$post_data['approved'] = 1;
			$post_data['approved_date'] = date('Y-m-d');
			$post_data['approved_by'] = $this->request->session()->read('user_id');
			$data = $erp_inventory_rbn_detail->patchEntity($po_data,$post_data);
			$erp_inventory_rbn_detail->save($data);
			
			$rbn_tbl = TableRegistry::get("erp_inventory_rbn");
			$rbndata = $rbn_tbl->get($rbn_id);
			$rbn_date = $rbndata->rbn_date;
			
			$history_tbl = TableRegistry::get("erp_stock_history");
			$history_row = $history_tbl->newEntity();
			$insert["date"] = $rbn_date;
			$insert["project_id"] = $project_id[$i];
			$insert["material_id"] = $material_id[$i];
			$insert["quantity"] = $return_qty[$i];
			$insert["return_back"] = $return_qty[$i];			
			$insert["type"] = "rbn";
			$insert["type_id"] = $rbn_id[$i];
			$history_row = $history_tbl->patchEntity($history_row,$insert);
			$history_tbl->save($history_row);	
			
			$stock_tbl = TableRegistry::get("erp_stock");
			$check_stock = $stock_tbl->find("all")->where(["project_id"=>$project_id[$i],"material_id"=>$material_id[$i]])->hydrate(false)->toArray();		

			if(!empty($check_stock))
			{			
				$query = $stock_tbl->query();
				$query->update()
					->set(['quantity' => $check_stock[0]["quantity"] + intval($return_qty[$i])])
					->where(['project_id' => $project_id[$i],'material_id'=>$material_id[$i]])
					->execute();
			}
			else{
				$stock_row = $stock_tbl->newEntity();
				$stock_data["project_id"] = $project_id[$i];
				$stock_data["material_id"] = $material_id[$i];
				$stock_data["quantity"] = $return_qty[$i];			
				$stock_row = $stock_tbl->patchEntity($stock_row,$stock_data);
				$stock_tbl->save($stock_row);
			}	
			$i++;
		}
	}
	
	public function accountapprovemrn()
	{		
		$mrn_id = $_REQUEST['mrn_id'];
		$data_role = $_REQUEST['data_role'];
		
		$erp_inventory_mrn = TableRegistry::get('erp_inventory_mrn');
		$po_data = $erp_inventory_mrn->get($mrn_id);
		// if($data_role == 'bycm')
		// {/* $post_data['approve_cm'] = 1; */}		
		// if($data_role == 'byac')
		// {
		$po_data->approve_accountant = 1;
		// }
		
		// if($data_role == 'byexecute')
		// {
		$po_data->approve_executives = 1;
		// }
		
		/* $data = $erp_inventory_mrn->patchEntity($po_data,$post_data); */
		$erp_inventory_mrn->save($po_data);
		die();
	}
	
	
	public function approvemrn()
	{		
		$mrn_id = $_REQUEST['mrn_id'];
		$data_role = $_REQUEST['data_role'];
		$mrn_detail_id = $_REQUEST['mrn_detail_id'];
		$project_id = $_REQUEST['project_id'];		
		$material_id = $_REQUEST['material_id'];		
		$quantity = $_REQUEST['quantity'];	
		
		/* Redirect back and do not update if stock going nagative after edit*/
		$available_stock = $this->ERPfunction->get_current_stock($project_id,$material_id);
		$stock_after = $available_stock - $quantity;
		if($stock_after < 0)
		{
			$m = $this->ERPfunction->get_material_title($material_id);
			echo $this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
			die;
		}
		/* Redirect back and do not update if stock going nagative after edit*/
		
		$erp_inventory_mrn_detail = TableRegistry::get('erp_inventory_mrn_detail');
		$po_data = $erp_inventory_mrn_detail->get($mrn_detail_id);
		$post_data['approved'] = 1;
		$post_data['approved_date'] = date('Y-m-d');
		$post_data['approved_by'] = $this->request->session()->read('user_id');
		$data = $erp_inventory_mrn_detail->patchEntity($po_data,$post_data);
		$erp_inventory_mrn_detail->save($data);
		
		$mrn_tbl = TableRegistry::get("erp_inventory_mrn");
		$mrndata = $mrn_tbl->get($mrn_id);
		$mrn_date = $mrndata->mrn_date;
		
		$history_tbl = TableRegistry::get("erp_stock_history");
		$history_row = $history_tbl->newEntity();
		$insert["date"] = $mrn_date;
		$insert["project_id"] = $project_id;
		$insert["material_id"] = $material_id;
		$insert["quantity"] = $quantity;
		$insert["transferred"] = $quantity;			
		$insert["type"] = "mrn";
		$insert["type_id"] = $mrn_id;
		$history_row = $history_tbl->patchEntity($history_row,$insert);
		$history_tbl->save($history_row);	
		
		$stock_tbl = TableRegistry::get("erp_stock");
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
			$stock_data["quantity"] = $quantity;
			$stock_row = $stock_tbl->patchEntity($stock_row,$stock_data);
			$stock_tbl->save($stock_row);
		}		
		die();
	}
	
	
	public function approvesst_old()
	{
		$sst_id = $_REQUEST['sst_id'];
		$data_site = $_REQUEST['data_site'];
		$erp_inventory_sst = TableRegistry::get('erp_inventory_sst');
		$po_data = $erp_inventory_sst->get($sst_id);
		if($data_site == 'site1')
		$post_data['approved_site1'] = 1;
	
		if($data_site == 'site2')
		$post_data['approved_site2'] = 1;
	
		$data = $erp_inventory_sst->patchEntity($po_data,$post_data);
		$erp_inventory_sst->save($data);
		die();
	}
	
	public function approvesstsite1()
	{
		$sst_id = $_REQUEST['sst_id'];
		$sst_detail_id = $_REQUEST['sst_detail_id'];
		/* $data_site = $_REQUEST['data_site']; */		
		$project_id = $_REQUEST['project_id'];		
		$transfer_to = $_REQUEST['transfer_to'];		
		$material_id = $_REQUEST['material_id'];		
		$quantity = $_REQUEST['quantity'];
		
		/* Redirect back and do not update if stock going nagative after edit*/
		$available_stock = $this->ERPfunction->get_current_stock($project_id,$material_id);
		$stock_after = $available_stock - $quantity;
		if($stock_after < 0)
		{
			$m = $this->ERPfunction->get_material_title($material_id);
			echo $this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
			die;
		}
		/* Redirect back and do not update if stock going nagative after edit*/
		
		$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail');
		$po_data = $erp_inventory_sst_detail->get($sst_detail_id);		
		$post_data['approved_site1'] = 1;
		
		$data = $erp_inventory_sst_detail->patchEntity($po_data,$post_data);
		$erp_inventory_sst_detail->save($data);
		
		$sst_tbl = TableRegistry::get("erp_inventory_sst");
		$sstdata = $sst_tbl->get($sst_id);
		$sst_date = $sstdata->sst_date;
		
		$history_tbl = TableRegistry::get("erp_stock_history");
		$history_row = $history_tbl->newEntity();
		$insert["date"] = $sst_date;
		$insert["project_id"] = $project_id;
		$insert["material_id"] = $material_id;
		$insert["quantity"] = $quantity;
		$insert["transferred"] = $quantity;			
		$insert["type"] = "sst_from";
		$insert["type_id"] = $sst_id;
		$history_row = $history_tbl->patchEntity($history_row,$insert);
		$history_tbl->save($history_row);	
		
		
		die();
	}
	
	public function approvesstsite2()
	{
		$sst_id = $_REQUEST['sst_id'];
		$sst_detail_id = $_REQUEST['sst_detail_id'];
		$project_id = $_REQUEST['project_id'];		
		$transfer_to = $_REQUEST['transfer_to'];		
		$material_id = $_REQUEST['material_id'];		
		$quantity = $_REQUEST['quantity'];
		/* $data_site = $_REQUEST['data_site']; */
		
		
		/* $erp_inventory_sst = TableRegistry::get('erp_inventory_sst');
		$po_data = $erp_inventory_sst->get($sst_id);		
		$post_data['approved_site2'] = 1;
		
		$data = $erp_inventory_sst->patchEntity($po_data,$post_data);
		$erp_inventory_sst->save($data); */		
		
		
		$erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail');
		$po_data = $erp_inventory_sst_detail->get($sst_detail_id);
		$post_data['approved_site2'] = 1;
		$post_data['approved_date'] = date('Y-m-d');
		$post_data['approved_by'] = $this->request->session()->read('user_id');
		$data = $erp_inventory_sst_detail->patchEntity($po_data,$post_data);
		$erp_inventory_sst_detail->save($data);
		
		$history_tbl = TableRegistry::get("erp_stock_history");
		/*  $history_row = $history_tbl->newEntity();
		$insert["date"] = date("Y-m-d");
		$insert["project_id"] = $project_id;
		$insert["material_id"] = $material_id;
		$insert["quantity"] = $quantity;
		$insert["transferred"] = $quantity;			
		$insert["type"] = "sst_from";
		$insert["type_id"] = $sst_id;
		$history_row = $history_tbl->patchEntity($history_row,$insert);
		$history_tbl->save($history_row);	 */
		
		$sst_tbl = TableRegistry::get("erp_inventory_sst");
		$sstdata = $sst_tbl->get($sst_id);
		$sst_date = $sstdata->sst_date;
		
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
		
		/* ADD Stock to second project*/
		$stock_data = array();
		$check_stock = $stock_tbl->find("all")->where(["project_id"=>$transfer_to,"material_id"=>$material_id])->hydrate(false)->toArray();		

		if(!empty($check_stock))
		{			
			$query = $stock_tbl->query();
			$query->update()
				->set(['quantity' => $check_stock[0]["quantity"] + intval($quantity)])
				->where(['project_id' => $project_id,'material_id'=>$material_id])
				->execute();
		}
		else{
			$stock_row = $stock_tbl->newEntity();
			$stock_data["project_id"] = $transfer_to;
			$stock_data["material_id"] = $material_id;
			$stock_data["quantity"] = $quantity;
			$stock_row = $stock_tbl->patchEntity($stock_row,$stock_data);
			$stock_tbl->save($stock_row);
		}
		
		die();
	}
	
	public function deploymenthistory()
	{
		$history_tbl = TableRegistry::get("erp_employee_transfer_history");
		$user_tbl = TableRegistry::get("erp_users");
		
		$user_id = $this->request->data["user_id"];
		$this->set("user_id",$user_id);
		
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$data = $history_tbl->find()->where(["employee_id"=>$user_id])->hydrate(false)->toArray();
		
		if(!empty($data))
		{
			$first_project = $data[0]['old_project'];
		}
		else
		{
			$first_project = $user_data[0]['employee_at'];
		}
		
		$this->set("first_project",$first_project);
		$this->set("user_data",$user_data);
		$this->set("data",$data);
	}
	
	
	public function paymenthistory()
	{
		$salary_tbl = TableRegistry::get("erp_salary_slip");
		$user_id = $this->request->data["user_id"];
		$this->set("user_id",$user_id);
		
		/* $data = $salary_tbl->find()->where(["employee_id"=>$user_id])->hydrate(false)->toArray();  ERROR QUERY */
		$data = $salary_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		$this->set("data",$data);		
	}
	
	public function getrmcrow()
	{
		$challan_no = $this->request->data["challan_size"];
		$challan_no = $challan_no + 1;
		?>
		<tr id="cpy_row">
			<td>
			
				<input name="tmno[]" class="form-control">
			</td>
			<td>
				<input name="driver_name[]" class="form-control">
			</td>
			<td>
				<input name="time_in[]" class="form-control">
			</td>
			<td>
				<input name="time_out[]" class="form-control">
			</td>
			<td>
				<input name="quantity[]" class="form-control">
			</td>
			<td>
				<input name="received_by[]" class="form-control">
			</td>
			<td>
				<input type="file" name="challan[<?php echo $challan_no;?>]" class="challan form-control">
			</td>								
		</tr>
		
		<?php	
		die;		
	}
	
	public function approvermc()
	{
		if($this->request->is("Ajax"))
		{
			$rmc_id = $this->request->data["rmc_id"];
			$rmc_tbl = TableRegistry::get("erp_rmc_issue");
			$row = $rmc_tbl->get($rmc_id);
			$row->approved = 1;
			$row->approved_by = $this->request->session()->read('user_id');
			$row->approved_date = date("Y-m-d");			
			$check = $rmc_tbl->save($row);	
		}
		die;
	}
	
	public function editprmaterial()
	{		
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);
		
		$pmid = $this->request->data["pmid"];
		$mid = $this->request->data["mid"];
		$bid = $this->request->data["bid"];
		$quantity = $this->request->data["qty"];
		
		$this->set('pmid',$pmid);
		$this->set('mid',$mid);
		$this->set('bid',$bid);
		$this->set('quantity',$quantity);
	}
	
	public function printgrn()
	{		
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$data = $_GET;
		$this->set("erp_grn_details",$data);
		// debug($data);die;
		// for($i=0;$i<= sizeof($data['material']['material_id']);$i++)
		// {	
			// echo $data['material']['brand_id'][$i];
		// }
		// debug($data);
		// echo sizeof($data['material']['material_id']);
		// die;
	}
	
	public function printgrnwithoutpo()
	{		
		require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
		$data = $_GET;
		// debug($data);die;
		$this->set("erp_grn_details",$data);
		
	}
	
	public function removeprfromgrnwithoutpo()
	{
		if($this->request->is("ajax"))
		{
			$pr_id =  $this->request->data["pr_id"];
			$prm_tbl = TableRegistry::get("erp_inventory_pr_material");
			$grnm_tbl = TableRegistry::get("erp_inventory_grn_detail");
			$materails = $prm_tbl->find("all")->where(["pr_id"=>$pr_id,"approved_for_grnwithoutpo"=>"1"]);
			foreach($materails as $materail)
			{
				$materail->quantity = $materail->used_qty;
				$materail->approved_for_grnwithoutpo = 2;
				$prm_tbl->save($materail);
				
				$grn_material = unserialize($materail->last_grndetail_id);
				if(!empty($grn_material))
				{
					foreach($grn_material as $grmid)
					{
						$row = $grnm_tbl->get($grmid);
						$row->quantity = $row->actual_qty;
						$row->difference_qty = "0 : Less";
						$grnm_tbl->save($row);					
					}
				}				
			}			
		}
		die;
	}
	
	public function removepofromgrn()
	{
		if($this->request->is("ajax"))
		{
			$po_id =  $this->request->data["po_id"];
			$po_tbl = TableRegistry::get("erp_inventory_po_detail");
			$user_id = $this->request->session()->read('user_id');
			
			$query = $po_tbl->query();
			$query = $query->update()->set(["approved"=>2,"approved_by"=>$user_id,"approved_date"=>date("Y-m-d")])->where(["po_id"=>$po_id,"approved"=>1])->execute();
			
		}
		die;
	}
	
	public function getemployeeno()
	{
		$eid = $this->request->data["eid"];
		$emp_tbl = TableRegistry::get("erp_users");
		$data = $emp_tbl->find()->where(["user_id"=>$eid])->hydrate(false)->toArray();
		//$emp_no = $data[0]["employee_no"];
		$emp_no = $data[0]["user_id"];
		echo $emp_no;
		die;
	}
	
	public function changeattendancestatus()
	{
		$status = $this->request->data["status"];
		$detail_id = $this->request->data["detail_id"];
		$data = $this->request->data["data"];
		$man_pl = $this->request->data["man_pl"];
		
		$data = explode("/",$data);
		$user_id = $data[0];
		$day = $data[1];
		$month = $data[2];
		$year = $data[3];
		
		$this->set("user_id",$user_id);
		$this->set("day",$day);
		$this->set("month",$month);
		$this->set("year",$year);
		$this->set("status",$status);	
		$this->set("detail_id",$detail_id);			
		$this->set("man_pl",$man_pl);			
	}
	
	public function changeattendancestatusall()
	{
		$status = $this->request->data["status"];
		$detail_id = $this->request->data["detail_id"];
		$data = $this->request->data["data"];		
		$emp_at = $this->request->data["emp_at"];		
		$man_pl = $this->request->data["man_pl"];
		
		$data = explode("/",$data);
		$user_id = $data[0];
		$day = $data[1];
		$month = $data[2];
		$year = $data[3];
		
		$this->set("user_id",$user_id);
		$this->set("day",$day);
		$this->set("month",$month);
		$this->set("year",$year);
		$this->set("status",$status);	
		$this->set("detail_id",$detail_id);			
		$this->set("emp_at",$emp_at);		
		$this->set("man_pl",$man_pl);
	}
	
	public function addleavebalance()
	{
		$user_id = $this->request->data["user_id"];
		$tbl = TableRegistry::get("erp_users");
		$row = $tbl->get($user_id);
		
		$this->set("balance",$row->leave_balance);
		$this->set("user_id",$user_id);
		
		
	}
	
	public function approvebycm()
	{
		if($this->request->is("ajax"))
		{
			$request_id =  $this->request->data["id"];
			$erp_advance_request_detail = TableRegistry::get("erp_advance_request_detail");
			$record = $erp_advance_request_detail->get($request_id);
			$record['approval_by_cm'] = 1;
			$record['cm_approval_date'] = date("Y-m-d");
			$record['approval_by_pd'] = 1;
			$record['pd_approval_date'] = date("Y-m-d");
			$check = $erp_advance_request_detail->save($record);
		}
	}
	
	public function unapprovebycm()
	{
		if($this->request->is("ajax"))
		{
			$request_id =  $this->request->data["id"];
			$erp_advance_request_detail = TableRegistry::get("erp_advance_request_detail");
			$record = $erp_advance_request_detail->get($request_id);
			$record['approval_by_cm'] = 0;
			$record['cm_approval_date'] = Null;
			$record['approval_by_pd'] = 0;
			$record['pd_approval_date'] = Null;
			$check = $erp_advance_request_detail->save($record);
		}
	}
	
	public function approvebypd()
	{
		if($this->request->is("ajax"))
		{
			$request_id =  $this->request->data["id"];
		
			$erp_advance_request_detail = TableRegistry::get("erp_advance_request_detail");
			$record = $erp_advance_request_detail->get($request_id);
			$record['approval_by_pd'] = 1;
			$record['pd_approval_date'] = date("Y-m-d");
			$check = $erp_advance_request_detail->save($record);
		}
	}
	
	public function approvesalaryslip()
	{
		if($this->request->is("ajax"))
		{
			$hr_emails = $this->ERPfunction->get_hrmanager_email();
			$email_to = (!empty($hr_emails)) ? implode(",",$hr_emails) : "";
			$user_email = $this->request->data["user_email"];
			$email_to .= ",".$user_email;
			
			$slip_id = $this->request->data["slip_id"];
			$date = $this->request->data["date"];
			$name = $this->request->data["name"];
			
			$tbl = TableRegistry::get("erp_salary_slip");
			$row = $tbl->get($slip_id);
			$row->approved = 1;
			$row->approved_date = date("Y-m-d");
			$row->approved_by = $this->request->session()->read('user_id');
			if($tbl->save($row))
			{
				$email_to = trim($email_to,",");
				$this->ERPfunction->mail_salary_slip($slip_id,$date,$name,$email_to);
				
					$this->Flash->success(__('Salary Slip Approved Successfully', null), 
							'default', 
							array('class' => 'success'));
				$this->redirect(["controller"=>"humanresource","action"=>"salaryrecords"]);
			}
		}
		die;
	}
	
	public function multipleapprovesalaryslip()
	{
		$this->autoRender = false;
		
		$val_arr = $this->request->data['val_arr'];
		$mail_check = $this->request->data['mail_check'];
		//$hr_emails = $this->ERPfunction->get_hrmanager_email();
		$hr_emails = $this->ERPfunction->get_mail_list_by_payslip('"payslip_notification"');
		$email_to = (!empty($hr_emails)) ? implode(",",$hr_emails) : "";
		
		$flag = 0;
		foreach($val_arr as $retrive)
		{
			$slip_id = $retrive['slip_id'];
			$date = $retrive['date'];
			$name = $retrive['name'];
			$user_email = $retrive['user_email'];
			if($mail_check == 1)
			{
				$email_to .= ",".$user_email;
			}
			$email_to = trim($email_to,",");
	
			if($slip_id != '')
			{
				$tbl = TableRegistry::get("erp_salary_slip");
				$row = $tbl->get($slip_id);
				$slip_type = $row->salaryslip_type;
				$row->approved = 1;
				$row->approved_date = date("Y-m-d");
				$row->approved_by = $this->request->session()->read('user_id');
				if($tbl->save($row))
				{	
					$email_to = trim($email_to,",");
					if($slip_type == "salary_slip"){
						$this->ERPfunction->mail_salary_slip($slip_id,$date,$name,$email_to);
					}
					
					$flag++;
				}
			}
		}
		if($flag)
		{
			$this->Flash->success(__('Salary Slip Approved Successfully', null), 
							'default', 
							array('class' => 'success'));
			$this->redirect(["controller"=>"humanresource","action"=>"salaryrecords"]);
		}
	}
	
	public function expenceapprovebycm()
	{
		if($this->request->is("ajax"))
		{
			$detail_id =  $this->request->data["id"];
		
			$erp_expence_detail = TableRegistry::get("erp_expence_detail");
			$record = $erp_expence_detail->get($detail_id);
			$record['approval_by_cm'] = 1;
			$record['cm_approval_date'] = date("Y-m-d");
			$check = $erp_expence_detail->save($record);
		}
	}
	
	public function expenceapprovebypd()
	{
		if($this->request->is("ajax"))
		{
			$detail_id =  $this->request->data["id"];
		
			$erp_expence_detail = TableRegistry::get("erp_expence_detail");
			$record = $erp_expence_detail->get($detail_id);
			$record['approval_by_pdmd'] = 1;
			$record['pdmd_approval_date'] = date("Y-m-d");
			$check = $erp_expence_detail->save($record);
		}
	}
	
	public function expenceapprovebyaccountant()
	{
		if($this->request->is("ajax"))
		{
			$detail_id =  $this->request->data["id"];
		
			$erp_expence_detail = TableRegistry::get("erp_expence_detail");
			$record = $erp_expence_detail->get($detail_id);
			$record['approval_by_accountant'] = 1;
			$record['accountant_approval_date'] = date("Y-m-d");
			$check = $erp_expence_detail->save($record);
		}
	}
	
	public function getaccountname()
	{
		$this->autoRender=false;
	}
	
	public function multipleapprovecmpd()
	{
		$this->autoRender=false;
		$user = $this->request->session()->read('user_id');
		if($this->request->is("ajax"))
		{
			$request_id = json_decode($this->request->data["request_id"]);
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$erp_advance_request_detail = TableRegistry::get('erp_advance_request_detail');
					$row = $erp_advance_request_detail->get($req_id);
					$row['cmpd_approval'] = 1;
					$row['cmpd_approval_date'] = date('Y-m-d');
					$row['cmpd_approval_by'] = $user;
					$check=$erp_advance_request_detail->save($row);
				}
			}
		}
	}
	
	public function cmpdmdapprove()
	{
		$this->autoRender=false;
		$user = $this->request->session()->read('user_id');
		if($this->request->is("ajax"))
		{
			$request_id = json_decode($this->request->data["request_id"]);
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$erp_expence_detail = TableRegistry::get('erp_expence_detail');
					$query = $erp_expence_detail->query();
					$query->update()
					->set(['approval_cmpdmd'=>1,
					"cmpdmd_approval_date"=>date('Y-m-d'),
					"approval_by_cmpdmd"=>$user])
					->where(['exp_id' => $req_id])
					->execute();
				}
			}
		}
	}
	
	public function cmpdmdapprovedebit()
	{
		$this->autoRender=false;
		$user = $this->request->session()->read('user_id');
		if($this->request->is("ajax"))
		{
			$request_id = json_decode($this->request->data["request_id"]);
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$erp_debit_note_detail = TableRegistry::get('erp_debit_note_detail');
					$query = $erp_debit_note_detail->query();
					$query->update()
					->set(['first_approved'=>1,
					"first_approved_date"=>date('Y-m-d'),
					"first_approved_by"=>$user])
					->where(['debit_id' => $req_id])
					->execute();
				}
			}
		}
	}
	
	public function cmpdmdapproveinventorydebit()
	{
		$this->autoRender=false;
		$user = $this->request->session()->read('user_id');
		if($this->request->is("ajax"))
		{
			$request_id = json_decode($this->request->data["request_id"]);
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$erp_debit_note_detail = TableRegistry::get('erp_inventory_debit_note_detail');
					$query = $erp_debit_note_detail->query();
					$query->update()
					->set(['first_approved'=>1,
					"first_approved_date"=>date('Y-m-d'),
					"first_approved_by"=>$user])
					->where(['debit_id' => $req_id])
					->execute();
				}
			}
		}
	}
	
	public function accountapprove()
	{
		$this->autoRender=false;
		$user = $this->request->session()->read('user_id');
		if($this->request->is("ajax"))
		{
			$request_id = json_decode($this->request->data["request_id"]);
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					// $erp_expence_detail = TableRegistry::get('erp_expence_detail');
					// $row = $erp_expence_detail->get($req_id);
					// $row['approval_accountant'] = 1;
					// $row['accountant_approval_date'] = date('Y-m-d');
					// $row['approval_by_accountant_id'] = $user;
					// $check=$erp_expence_detail->save($row);
					$erp_expence_detail = TableRegistry::get('erp_expence_detail');
					$query = $erp_expence_detail->query();
					$query->update()
					->set(['approval_accountant'=>1,
					"accountant_approval_date"=>date('Y-m-d'),
					"approval_by_accountant_id"=>$user])
					->where(['exp_id' => $req_id])
					->execute();
				}
			}
		}
	}
	
	public function accountantapprovedebit()
	{
		$this->autoRender=false;
		$user = $this->request->session()->read('user_id');
		if($this->request->is("ajax"))
		{
			$request_id = json_decode($this->request->data["request_id"]);
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$erp_debit_note_detail = TableRegistry::get('erp_debit_note_detail');
					$query = $erp_debit_note_detail->query();
					$query->update()
					->set(['second_approved'=>1,
					"second_approved_date"=>date('Y-m-d'),
					"second_approved_by"=>$user])
					->where(['debit_id' => $req_id])
					->execute();
				}
			}
		}
	}
	
	public function accountantapproveinventorydebit()
	{
		$this->autoRender=false;
		$user = $this->request->session()->read('user_id');
		if($this->request->is("ajax"))
		{
			$erp_debit_note_detail = TableRegistry::get('erp_inventory_debit_note_detail');
			$erp_debit_note = TableRegistry::get('erp_inventory_debit_note');
			$history_tbl = TableRegistry::get('erp_stock_history');
			$request_id = json_decode($this->request->data["request_id"]);
			
			foreach($request_id as $req_id)
			{
				if($req_id != '')
				{
					$query = $erp_debit_note_detail->query();
					$query->update()
					->set(['second_approved'=>1,
					"second_approved_date"=>date('Y-m-d'),
					"second_approved_by"=>$user])
					->where(['debit_id' => $req_id])
					->execute();
					
					/* Make entry in stockledger */
					$debit_row = $erp_debit_note->get($req_id);
					$material_row = $erp_debit_note_detail->find()->where(["debit_id"=>$req_id])->hydrate(false)->toArray();
					foreach($material_row as $retrive)
					{
						$row = $history_tbl->newEntity();
						$insert_data = array();
						$insert_data["date"] = date("Y-m-d",strtotime($debit_row->date));		
						$insert_data["project_id"] = $debit_row->project_id;
						$insert_data["material_id"] = $retrive["material_id"];
						$insert_data["quantity"] = $retrive["quantity"];
						$insert_data["stock_in"] = $actual_qty;
						$insert_data["type"] = 'debit';
						$insert_data["type_id"] = $req_id;
						$insert_data["detail_id"] = $retrive["detail_id"];
						$row = $history_tbl->patchEntity($row,$insert_data);
						$insert_row = $history_tbl->save($row);
						
						$row1 = $history_tbl->newEntity();
						$insert_data1 = array();
						$insert_data1["date"] = date("Y-m-d",strtotime($debit_row->date));		
						$insert_data1["project_id"] = $debit_row->project_id;
						$insert_data1["material_id"] = $retrive["material_id"];
						$insert_data1["quantity"] = $retrive["quantity"];
						$insert_data1["stock_out"] = $actual_qty;
						$insert_data1["type"] = 'debit_party';
						$insert_data1["type_id"] = $req_id;
						$insert_data1["detail_id"] = $retrive["detail_id"];
						$row1 = $history_tbl->patchEntity($row1,$insert_data1);
						$insert_row = $history_tbl->save($row1);
					}
					/* Make entry in stockledger */
				}
			}
		}
	}
	
	public function convertnumbertowords() 
	{
		$this->autoRender=false;
		if($this->request->is("ajax"))
		{
			$num = $this->request->data["amount"];
			 $num    = ( string ) ( ( int ) $num );
   
    if( ( int ) ( $num ) && ctype_digit( $num ) )
    {
        $words  = array( );
       
        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
       
        $list1  = array('','one','two','three','four','five','six','seven',
            'eight','nine','ten','eleven','twelve','thirteen','fourteen',
            'fifteen','sixteen','seventeen','eighteen','nineteen');
       
        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
            'seventy','eighty','ninety','hundred');
       
        $list3  = array('','thousand','million','billion','trillion',
            'quadrillion','quintillion','sextillion','septillion',
            'octillion','nonillion','decillion','undecillion',
            'duodecillion','tredecillion','quattuordecillion',
            'quindecillion','sexdecillion','septendecillion',
            'octodecillion','novemdecillion','vigintillion');
       
        $num_length = strlen( $num );
        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
        $max_length = $levels * 3;
        $num    = substr( '00'.$num , -$max_length );
        $num_levels = str_split( $num , 3 );
       
        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';
           
            if( $tens < 20 )
            {
                $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
            }
            else
            {
                $tens   = ( int ) ( $tens / 10 );
                $tens   = ' ' . $list2[$tens] . ' ';
                $singles    = ( int ) ( $num_part % 10 );
                $singles    = ' ' . $list1[$singles] . ' ';
            }
            $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        }
       
        $commas = count( $words );
       
        if( $commas > 1 )
        {
            $commas = $commas - 1;
        }
       
        $words  = implode( ', ' , $words );
       
        //Some Finishing Touch
        //Replacing multiples of spaces with one space
        $words  = trim( str_replace( ' ,' , ',' , trim( ucwords( $words ) ) ) , ', ' );
        if( $commas )
        {
            $words  = str_replace( ',' , ' and' , $words );
        }
       
        echo $words;
    }
    else if( ! ( ( int ) $num ) )
    {
        echo 'Zero';
    }
    echo '';
    
	}
	}
	
	public function accountbyproject()
	{
		 $this->autoRender=false;
		if($this->request->is("ajax"))
		{
			$project_id =  $this->request->data["project_id"];
			$erp_account = TableRegistry::get("erp_account");
			$account = $erp_account->find("all")->where(["project_id"=>$project_id]);
			$count = $account->count();
			if($count)
			{
				foreach($account as $option)
				{
					echo "<option value='{$option['account_id']}'>{$option['account_name']}</option>";
				}
			}
		}
	}
	
	public function paystructurehistory()
	{
		$user_id = $this->request->data["user_id"];
		$user_tbl = TableRegistry::get("erp_users");
		$tbl = TableRegistry::get("erp_users_history");
		
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		$history = $tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$this->set("user_data",$user_data[0]);
		$this->set("history",$history);
		$this->set("user_id",$user_id);
	}
	
	public function payloanhistory()
	{
		$user_id = $this->request->data['user_id'];

		$erp_loan_tbl = TableRegistry::get('erp_loan');
		$history_tabel = TableRegistry::get('erp_loan_pay_history');


		$result = $erp_loan_tbl->find()->select($erp_loan_tbl)->where(["user_id"=>$user_id]);
		
		$result = $result->innerjoin(
					["erp_loan_pay_history"=>"erp_loan_pay_history"],
					["erp_loan.loan_id = erp_loan_pay_history.loan_id"])
					->select($history_tabel)->hydrate(false)->toArray();

		/*$query_loan = $erp_loan_tbl->find()->where(['user_id'=>$user_id])->hydrate(false)->toArray();
		
		foreach ($query_loan as $row ) {
		
	    	$query = $history_tabel->find()->where(["loan_id"=>$row['loan_id']])->hydrate(false)->toArray();
		}
		*/


			//$query = $history_tabel->get($loan_id);
		
		$this->set("history",$result);
	}
	
	public function payrecords()
	{
		$user_id = $this->request->data["user_id"];
		$salary_tbl = TableRegistry::get("erp_salary_slip");			
		$usr_tbl = TableRegistry::get("erp_users");
		
		$data = $salary_tbl->find()->where(["erp_salary_slip.user_id"=>$user_id])->select($salary_tbl);
		$data = $data->leftjoin(["erp_users" => "erp_users"],
		["erp_salary_slip.user_id = erp_users.user_id"])->select($usr_tbl)->hydrate(false)->toArray();
		
		$this->set("user_id",$user_id);
		$this->set("salary_data",$data);
	}
	
	public function pendingbilllistdata()
	{
		
		// $post = $this->request->data;		
			// $projects_ids = $this->Usermanage->users_project($this->user_id);		
			// $role = $this->role;
			// $or = array();				
			
			// $or["date LIKE"] = (!empty($post["date_from"]))?"%{$post["date_from"]}%":NULL;
			// $or["date LIKE"] = (!empty($post["date_to"]))?"%{$post["date_to"]}%":NULL;
			// $or["bill_date LIKE"] = (!empty($post["bill_date_from"]))?"%{$post["bill_date_from"]}%":NULL;
			// $or["bill_date LIKE"] = (!empty($post["bill_date_to"]))?"%{$post["bill_date_to"]}%":NULL;
			// $or["project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All")?$post["project_id"]:NULL;
			// $or["party_id"] = (!empty($post["party_id"]) && $post["party_id"] != "All")?$post["party_id"]:NULL;
			// $or["bill_type"] = (!empty($post["bill_type"]) && $post["bill_type"] != "All" )?$post["bill_type"]:NULL;
			// $or["payment_method"] = (!empty($post["payment_mod"]) && $post["payment_mod"] != "All")?$post["payment_mod"]:NULL;
			// $or["inward_bill_no LIKE"] = (!empty($post["bill_no"]))?"%{$post["bill_no"]}%":NULL;
			// $or["invoice_no LIKE"] = (!empty($post["invoice_no"]))?"%{$post["invoice_no"]}%":NULL;
			// $or["po_no LIKE"] = (!empty($post["powono"]))?"%{$post["powono"]}%":NULL;
			//var_dump($post["bill_type"]);die;
			// if($or["project_id"] == NULL)
			// {
				// if($role =='projectdirector' || $role =='projectcoordinator' || $role =='planningmanager' || $role =='siteaccountant' || $role =='constructionmanager' || $role =='billingengineer' || $role =='planningengineer' || $role =='materialmanager')
				// { 
					// $or["project_id"] = $projects_ids;
				// }
			// }
			
			// $keys = array_keys($or,"");				
			// foreach ($keys as $k)
			// {unset($or[$k]);}
			//var_dump($or);die;
		// $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		// require_once( $parse_uri[0] . 'wp-load.php' );  
		// DB table to use
		$table = 'erp_inward_bill';
		// Table's primary key
		$primaryKey = 'inward_bill_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'inward_bill_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'date', 'dt' => 0 ),
			array( 'db' => 'time', 'dt' => 1),
			array( 'db' => 'project_id',  'dt' => 2 ),
			array( 'db' => 'inward_bill_no',  'dt' => 3),
			array( 'db' => 'party_name',  'dt' => 4 ),
			array( 'db' => 'bill_type',   'dt' => 5),
			array( 'db' => 'invoice_no',   'dt' => 6),
			array( 'db' => 'payment_method',   'dt' => 7),
			array( 'db' => 'total_amt',   'dt' => 8),
			array( 'db' => 'bill_date',   'dt' => 9),
			array( 'db' => 'credit_period',   'dt' => 10),
			array( 'db' => 'credit_period',   'dt' => 11),
			array( 'db' => 'qty_checked_by',   'dt' => 12),
			array( 'db' => 'rate_checked_by',   'dt' => 13),
			array( 'db' => 'inward_bill_id',   'dt' => 14),
			array( 'db' => 'inward_bill_id',   'dt' => 15),
			array( 'db' => 'party_type',   'dt' => 16),
			array( 'db' => 'new_party_name',   'dt' => 17)

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_Patient();
	
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$this->request->session()->read('user_id') )
		);
die;

	}
	
	public function acceptbilllistdata()
	{
		
			// $post = $this->request->data;
			// debug($post);die;		
			// $projects_ids = $this->Usermanage->users_project($this->user_id);		
			// $role = $this->role;
			// $or = array();				
			
	// $or["date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
	// $or["date <="] = ($post["date_to"] != "")?date("Y-m-d",strtotime($post["date_to"])):NULL;
	// $or["bill_date >="] = ($post["bill_date_from"] != "")?date("Y-m-d",strtotime($post["bill_date_from"])):NULL;
	// $or["bill_date <="] = ($post["bill_date_to"] != "")?date("Y-m-d",strtotime($post["bill_date_to"])):NULL;
	// $or["project_id ="] = (!empty($post["project_id"]) && $post["project_id"] != "all")?$post["project_id"]:NULL;
	// $or["party_id ="] = (!empty($post["party_id"]) && $post["party_id"] != "All")?$post["party_id"]:NULL;
	// $or["bill_type ="] = (!empty($post["bill_type"]) && $post["bill_type"] != "All" )?$post["bill_type"]:NULL;
	// $or["payment_method ="] = (!empty($post["payment_mod"]) && $post["payment_mod"] != "All")?$post["payment_mod"]:NULL;
	// $or["inward_bill_no ="] = (!empty($post["bill_no"]))?$post["bill_no"]:NULL;
	// $or["invoice_no ="] = (!empty($post["invoice_no"]))?$post["invoice_no"]:NULL;
	// $or["po_no ="] = (!empty($post["powono"]))?$post["powono"]:NULL;
			//var_dump($post["bill_type"]);die;
			// if($or["project_id ="] == NULL)
			// {
				// if($role =='projectdirector' || $role =='projectcoordinator' || $role =='planningmanager' || $role =='siteaccountant' || $role =='constructionmanager' || $role =='billingengineer' || $role =='planningengineer' || $role =='materialmanager')
				// { 
					// $or["project_id"] = $projects_ids;
				// }
			// }
			
			// $keys = array_keys($or,"");				
			// foreach ($keys as $k)
			// {unset($or[$k]);}
			// $or["status_inward ="] = 'pending';
			//debug($or);die;
		// $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		// require_once( $parse_uri[0] . 'wp-load.php' );  
		// DB table to use
		$table = 'erp_inward_bill';
		// Table's primary key
		$primaryKey = 'inward_bill_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'inward_bill_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			// array( 'db' => 'inward.date', 'dt' => 0, 'field' => 'date' ),
			// array( 'db' => 'inward.time', 'dt' => 1, 'field' => 'time'),
			// array( 'db' => 'project.project_name',  'dt' => 2, 'field' => 'project_name' ),
			// array( 'db' => 'inward.inward_bill_no',  'dt' => 3, 'field' => 'inward_bill_no'),
			// array( 'db' => 'inward.new_party_name',  'dt' => 4, 'field' => 'new_party_name' ),
			// array( 'db' => 'inward.bill_type',   'dt' => 5, 'field' => 'bill_type'),
			// array( 'db' => 'inward.invoice_no',   'dt' => 6, 'field' => 'invoice_no'),
			// array( 'db' => 'inward.payment_method',   'dt' => 7, 'field' => 'payment_method'),
			// array( 'db' => 'inward.total_amt',   'dt' => 8, 'field' => 'total_amt'),
			// array( 'db' => 'inward.bill_date',   'dt' => 9, 'field' => 'bill_date'),
			// array( 'db' => 'inward.credit_period',   'dt' => 10, 'field' => 'credit_period'),
			// array( 'db' => 'inward.credit_period',   'dt' => 11, 'field' => 'credit_period'),
			// array( 'db' => 'agency.agency_name',   'dt' => 12, 'field' => 'agency_name'),
			// array( 'db' => 'vendor.vendor_name',   'dt' => 13, 'field' => 'vendor_name'),
			// array( 'db' => 'inward.qty_checked_by',   'dt' => 14, 'field' => 'qty_checked_by'),
			// array( 'db' => 'inward.rate_checked_by',   'dt' => 15, 'field' => 'rate_checked_by'),
			// array( 'db' => 'inward.inward_bill_id',   'dt' => 16, 'field' => 'inward_bill_id'),
			// array( 'db' => 'inward.inward_bill_id',   'dt' => 17, 'field' => 'inward_bill_id'),
			// array( 'db' => 'inward.inward_bill_id',   'dt' => 18, 'field' => 'inward_bill_id'),
			// array( 'db' => 'inward.party_type',   'dt' => 19, 'field' => 'party_type'),
			// array( 'db' => 'inward.party_name',   'dt' => 20, 'field' => 'party_name'),
			// array( 'db' => 'inward.status_inward',   'dt' => 21, 'field' => 'status_inward')
		
			array( 'db' => 'inward.date', 'dt' => 0, 'field' => 'date' ),
			array( 'db' => 'project.project_name',  'dt' => 1, 'field' => 'project_name' ),
			array( 'db' => 'inward.inward_bill_no',  'dt' => 2, 'field' => 'inward_bill_no'),
			array( 'db' => 'inward.new_party_name',  'dt' => 3, 'field' => 'new_party_name' ),
			array( 'db' => 'inward.bill_type',   'dt' => 4, 'field' => 'bill_type'),
			array( 'db' => 'inward.bill_date',   'dt' => 5, 'field' => 'bill_date'),
			array( 'db' => 'inward.invoice_no',   'dt' => 6, 'field' => 'invoice_no'),
			array( 'db' => 'inward.total_amt',   'dt' => 7, 'field' => 'total_amt'),
			array( 'db' => 'inward.credit_period',   'dt' => 8, 'field' => 'credit_period'),
			array( 'db' => 'inward.credit_period',   'dt' => 9, 'field' => 'credit_period'),
			array( 'db' => 'agency.agency_name',   'dt' => 10, 'field' => 'agency_name'),
			array( 'db' => 'vendor.vendor_name',   'dt' => 11, 'field' => 'vendor_name'),
			array( 'db' => 'inward.inward_bill_id',   'dt' => 12, 'field' => 'inward_bill_id'),
			array( 'db' => 'inward.inward_bill_id',   'dt' => 13, 'field' => 'inward_bill_id'),
			array( 'db' => 'inward.inward_bill_id',   'dt' => 14, 'field' => 'inward_bill_id'),
			array( 'db' => 'inward.party_type',   'dt' => 15, 'field' => 'party_type'),
			array( 'db' => 'inward.party_name',   'dt' => 16, 'field' => 'party_name'),
			array( 'db' => 'inward.status_inward',   'dt' => 17, 'field' => 'status_inward'),
			array( 'db' => 'inward.remarks',   'dt' => 18, 'field' => 'remarks'),
			array( 'db' => 'inward.accept_bill_remarks', 'dt' => 19 , 'field' => 'accept_bill_remarks')

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_Accept();
		
		$joinQuery = "{$table} AS inward LEFT JOIN erp_projects AS project ON project.project_id = inward.project_id
		LEFT JOIN erp_agency AS agency ON agency.agency_id = inward.party_name
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = inward.party_name";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
die;

	}
	
	public function billrecordsdata()
	{
		
		// $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		// require_once( $parse_uri[0] . 'wp-load.php' );  
		// DB table to use
		$table = 'erp_inward_bill';
		// Table's primary key
		$primaryKey = 'inward_bill_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'inward_bill_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'project.project_name', 'dt' => 0 , 'field' => 'project_name' ),
			//array( 'db' => 'inward.inward_bill_no', 'dt' => 1, 'field' => 'inward_bill_no'),
			array( 'db' => 'inward.date',  'dt' => 1, 'field' => 'date' ),
			array( 'db' => 'inward.bill_date',   'dt' => 2, 'field' => 'bill_date'),
			array( 'db' => 'inward.new_party_name',  'dt' => 3, 'field' => 'new_party_name'),
			array( 'db' => 'inward.invoice_no',  'dt' => 4, 'field' => 'invoice_no' ),
			array( 'db' => 'inward.total_amt',   'dt' => 5, 'field' => 'total_amt'),
			array( 'db' => 'inward.credit_period',   'dt' => 6, 'field' => 'credit_period'),
			array( 'db' => 'inward.bill_type',   'dt' => 7, 'field' => 'bill_type'),
			array( 'db' => 'inward.status_inward',   'dt' => 8, 'field' => 'status_inward'),
			array( 'db' => 'inward.inward_bill_id',   'dt' => 9, 'field' => 'inward_bill_id'),
			array( 'db' => 'agency.agency_name',   'dt' => 10, 'field' => 'agency_name'),
			array( 'db' => 'vendor.vendor_name',   'dt' => 11, 'field' => 'vendor_name'),
			array( 'db' => 'inward.inward_bill_id',   'dt' => 12, 'field' => 'inward_bill_id'),
			// array( 'db' => 'credit_period',   'dt' => 11),
			// array( 'db' => 'inward_bill_id',   'dt' => 12),
			// array( 'db' => 'inward_bill_id',   'dt' => 13),
			array( 'db' => 'inward.party_type',   'dt' => 13, 'field' => 'party_type'),
			array( 'db' => 'inward.party_name',   'dt' => 14, 'field' => 'party_name')

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_bill();
		
		$joinQuery = "{$table} AS inward LEFT JOIN erp_projects AS project ON project.project_id = inward.project_id
		LEFT JOIN erp_agency AS agency ON agency.agency_id = inward.party_name
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = inward.party_name";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function paymentparty()
	{
		$party_type = $this->request->data["party_type"];
		
		$erp_vendor_register=TableRegistry::get('erp_vendor');
    	$get_vendor_info=$erp_vendor_register->find();
    	$this->set('vendor_info',$get_vendor_info);
				
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$this->set('agency_list',$agency_list);	
		
		$erp_inward_bill=TableRegistry::get('erp_inward_bill');
    	$new_party_info=$erp_inward_bill->find()->where(['party_type'=>'new'])->select('new_party_name')
		->hydrate(false)->toArray();
		$new_party = array();
		foreach($new_party_info as $party)
		{
			$new_party[] = $party['new_party_name'];
		}
		$this->set('new_party',$new_party);
		
		$this->set("party_type",$party_type);
	}
	
	public function paymentrow()
	{
		$payment_type = $this->request->data["payment_type"];
		$party_id = $this->request->data["party_id"];
		$party_type = $this->request->data["party_type"];
		$erp_inward_bill = TableRegistry::get('erp_inward_bill');
		if($party_id != '')
		{
			if($party_type == 'oldparty')
			{
				$party_data = $erp_inward_bill->find()->where(['party_name'=>$party_id,'status_inward'=>'accept','party_type'=>'old'])->hydrate(false)->toArray();
			}
			else
			{
				$party_data = $erp_inward_bill->find()->where(['new_party_name'=>$party_id,'status_inward'=>'accept','party_type'=>'new'])->hydrate(false)->toArray();
			}
		}
		else
		{
			$party_data = array();
		}
		
		$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $erp_projects->find();
		$this->set('projects',$projects);
		
		$this->set("payment_type",$payment_type);
		$this->set('party_data',$party_data);
	}
	
	public function inwardpartydetail()
	{
		$party_id = $_REQUEST['party_id'];
		$party_type = $_REQUEST['party_type'];
		$payment_type = $_REQUEST['payment_type'];
		$erp_inward_bill = TableRegistry::get('erp_inward_bill');
		if($party_id != '' && $payment_type != 'advance')
		{
			if($party_type == 'oldparty')
			{
				$party_data = $erp_inward_bill->find()->where(['party_name'=>$party_id,'status_inward'=>'accept','party_type'=>'old'])->hydrate(false)->toArray();
			}
			else
			{
				$party_data = $erp_inward_bill->find()->where(['new_party_name'=>$party_id,'status_inward'=>'accept','party_type'=>'new'])->hydrate(false)->toArray();
			}
			$this->set('party_data',$party_data);
		}
		else
		{
			die;
		}		
		
		
	}
	
	public function deletepodetail()
	{
		$this->autoRender = false ;
		$detail_id = $_REQUEST['detail_id'];
		
		$pom_tbl = TableRegistry::get('erp_inventory_po_detail');
		$delpom_tbl = TableRegistry::get('erp_inventory_deleted_po_detail');
		$get_deleted_po = $pom_tbl->get($detail_id);
		$deleted_po = $get_deleted_po->toArray();
		$deleted_po["deleted_by"] = $this->user_id;
		$deleted_po = $delpom_tbl->newEntity($deleted_po);
		if($delpom_tbl->save($deleted_po))
		{
			$row =$pom_tbl->get($detail_id);
			$pom_tbl->delete($row);
		}
		
		die;
	}
	
	public function deleteassetpodetail()
	{
		$this->autoRender = false ;
		$detail_id = $_REQUEST['detail_id'];
		
		$pom_tbl = TableRegistry::get('erp_asset_po_detail');
		// $delpom_tbl = TableRegistry::get('erp_inventory_deleted_po_detail');
		// $get_deleted_po = $pom_tbl->get($detail_id);
		// $deleted_po = $get_deleted_po->toArray();
		// $deleted_po["deleted_by"] = $this->user_id;
		// $deleted_po = $delpom_tbl->newEntity($deleted_po);
		// if($delpom_tbl->save($deleted_po))
		// {
			$row =$pom_tbl->get($detail_id);
			$pom_tbl->delete($row);
		// }
		
		die;
	}
	
	public function deletemanualpodetail()
	{
		$this->autoRender = false ;
		$detail_id = $_REQUEST['detail_id'];
		
		$pom_tbl = TableRegistry::get('erp_manual_po_detail');
		$row =$pom_tbl->get($detail_id);
		$pom_tbl->delete($row);
		//$delpom_tbl = TableRegistry::get('erp_inventory_deleted_po_detail');
		//$get_deleted_po = $pom_tbl->get($detail_id);
		//$deleted_po = $get_deleted_po->toArray();
		//$deleted_po["deleted_by"] = $this->user_id;
		//$deleted_po = $delpom_tbl->newEntity($deleted_po);
		// if($delpom_tbl->save($deleted_po))
		// {
			// $row =$pom_tbl->get($detail_id);
			// $pom_tbl->delete($row);
		// }
		
		die;
	}
	
	public function addmorematerial()
	{	
		$erp_projects = TableRegistry::get('erp_projects'); 
		$projects = $erp_projects->find();
		$this->set('projects',$projects);
		$erp_material = TableRegistry::get('erp_material');
		$project_id = isset($_REQUEST['project_id'])?$_REQUEST['project_id']:0;
		$category = $this->ERPfunction->vendor_group();
		$this->set('category',$category);
		 $table_category=TableRegistry::get('erp_category_master');
		$unit_list=$table_category->find()->where(array('type'=>'unit'));
		$this->set('unitlist',$unit_list);
		$this->set('project_id',$project_id);
		$this->set("back","index");
	}
	
	public function projectmaterial()
	{	
		$erp_material = TableRegistry::get('erp_material');
		$project_id = isset($_REQUEST['project_id'])?$_REQUEST['project_id']:0;
		$category = $this->ERPfunction->vendor_group();
		$this->set('category',$category);
		 $table_category=TableRegistry::get('erp_category_master');
		$unit_list=$table_category->find()->where(array('type'=>'unit'));
		$this->set('unitlist',$unit_list);
		$this->set('project_id',$project_id);
		$this->set("back","index");
	}
	
	public function addmorebrand()
	{
		$erp_material_brand = TableRegistry::get('erp_material_brand'); 		
		// $category = $this->ERPfunction->material_category();
		// $this->set('category',$category);
		$project_id = isset($_REQUEST['project_id'])?$_REQUEST['project_id']:0;
		$category = $this->ERPfunction->vendor_group();
		$this->set('category',$category);
		$this->set('project_id',$project_id);
		$this->set("back","index");
	}
	
	public function get_last_material_id()
	{
		// $conn = ConnectionManager::get('default');
		// $result = $conn->execute('select max(material_id) from  erp_material');		
		// $max = 0;
		// foreach($result as $retrive_data)
		// { $max=$retrive_data[0]; }
		// return $max;
		
		$this->autoRender = false;
		$conn = ConnectionManager::get('default');	
		$result = $conn->execute('SELECT MAX(RIGHT(material_item_code, 9)) as max
		FROM erp_material where project_id=0')->fetchAll("assoc");		
		$number = (int) $result[0]['max'];
		$new_number = str_pad(++$number,9,'0',STR_PAD_LEFT);
		return $new_number;
		// debug($new_number);
		// debug($result);
		// die;
	}
	
	public function addmaterial()
	{
		//debug($this->request->data);die;
		$this->autoRender = false;
		$material_code = $this->request->data["material_code"];
		$material_title = $this->request->data["material_title"];
		// $material_item_code = $this->request->data["material_item_code"];
		$project_id = $this->request->data["project_id"];
		$unit_id = $this->request->data["unit"];
		$consume = $this->request->data["consume"];
		$cost_group = $this->request->data["cost_group"];
		$material_sub_category = $this->request->data["material_sub_category"];
		
		if($project_id)
		{
			/* Get Next Sequence Number */
			$seq_no = $this->ERPfunction->generate_auto_id($project_id,"erp_material","material_id","material_item_code");
			$seq_no = sprintf("%09d", $seq_no);
			/* Get Next Sequence Number */
			
			/* Get Project Number */
			$project_code = $this->ERPfunction->get_projectcode($project_id);
			$c = explode("/",$project_code);
			$project_code_number = ($c[2])?$c[2]:"000";
			$material_item_code = "YNEC/MT/TMP/{$project_code_number}/{$seq_no}";
			/* Get Project Number */
		}else{
			/* Get Next Sequence Number */
			// $seq_no = $this->ERPfunction->generate_auto_id($project_id,"erp_material","material_id","material_item_code");
			// $seq_no = sprintf("%09d", $seq_no);
			$seq_no = $this->get_last_material_id();
			/* Get Next Sequence Number */
			
			$material_item_code = 'YNEC/MT/'.$this->ERPfunction->get_vendor_group_code($material_code ).'/'.$seq_no;
		}
				
		$erp_material = TableRegistry::get('erp_material');
		
		$check = $erp_material->find("all")->where(["material_code"=>$material_code,"material_title"=>$material_title])->count();
		if($check == 0)
		{				
			$table_field = $erp_material->newEntity();	
			$this->request->data['material_code']=$material_code;
			$this->request->data['material_sub_group']=$material_sub_category;
			$this->request->data['material_title']=$material_title;
			$this->request->data['material_item_code']=$material_item_code;
			$this->request->data['project_id']=$project_id;
			$this->request->data['unit_id']=$unit_id;
			$this->request->data['cost_group']=$cost_group;
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;			
					
			$new_data=$erp_material->patchEntity($table_field,$this->request->data);
			if($erp_material->save($new_data))
			{		
				$last_material = $new_data->material_id;
				echo "<option value='".$last_material."'>{$material_title}</option>";
			}
								 
		}
		else{
				echo 'duplicate';
		}
		die;
	}
	
	public function addprojectmaterial()
	{
		//debug($this->request->data);die;
		$this->autoRender = false;
		$material_code = $this->request->data["material_code"];
		$material_title = $this->request->data["material_title"];
		$brand_title = $this->request->data["brand_title"];
		// $material_item_code = $this->request->data["material_item_code"];
		$project_id = $this->request->data["project_id"];
		$unit_id = $this->request->data["unit"];
		$consume = $this->request->data["consume"];
		$cost_group = $this->request->data["cost_group"];
		
		$erp_material = TableRegistry::get('erp_material');
		if($project_id)
		{
			/* Get Next Sequence Number */
			$seq_no = $this->ERPfunction->generate_auto_id($project_id,"erp_material","material_id","material_item_code");
			$seq_no = sprintf("%09d", $seq_no);
			/* Get Next Sequence Number */
			
			/* Get Project Number */
			$project_code = $this->ERPfunction->get_projectcode($project_id);
			$c = explode("/",$project_code);
			$project_code_number = ($c[2])?$c[2]:"000";
			$material_item_code = "YNEC/MT/TMP/{$project_code_number}/{$seq_no}";
			/* Get Project Number */
		}
		$check = $erp_material->find("all")->where(["material_code"=>$material_code,"material_title"=>$material_title])->count();
		if($check == 0)
		{				
			$table_field = $erp_material->newEntity();	
			$this->request->data['material_code']=$material_code;
			$this->request->data['material_title']=$material_title;
			$this->request->data['material_item_code']=$material_item_code;
			$this->request->data['project_id']=$project_id;
			$this->request->data['unit_id']=$unit_id;
			$this->request->data['consume']=$consume;
			$this->request->data['cost_group']=$cost_group;
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data['created_by']=$this->request->session()->read('user_id');
			$this->request->data['status']=1;			
					
			$new_data=$erp_material->patchEntity($table_field,$this->request->data);
			if($erp_material->save($new_data))
			{	
				$last_material_id = $new_data->material_id;
				if($material_code == 17) /* Add TEMP material to seperate table */
				{
					$tmp_tbl = TableRegistry::get("erp_material_temp");
					$tmp_field = $tmp_tbl->newEntity();
					$this->request->data["material_id"] = $new_data->material_id;
					$tmp_data=$tmp_tbl->patchEntity($tmp_field,$this->request->data);
					$tmp_tbl->save($tmp_data);						
				}
				/* Add brand in brand table */
				$erp_material_brand = TableRegistry::get('erp_material_brand');
				$brand_field = $erp_material_brand->newEntity();	
				$brand_row['material_type']=$material_code;
				$brand_row['brand_name']=$brand_title;
				$brand_row['project_id']=$project_id;
				$brand_row['status']=1;
				$brand_data=$erp_material_brand->patchEntity($brand_field,$brand_row);
				if($erp_material_brand->save($brand_data))
				{
					$last_brand_id = $brand_data->brand_id;
					/* For update brand id in last added material*/
					$erp_material = TableRegistry::get('erp_material');
					$update_row = $erp_material->get($last_material_id);
					$update_row['brand_id'] = $last_brand_id;
					$erp_material->save($update_row);
				}
				
				$last_material = $new_data->material_id;
				echo "<option value='".$last_material."'>{$material_title}</option>";
			}
								 
		}
		else{
				echo 'duplicate';
		}
		die;
	}
	
	public function addbrand()
	{
		$this->autoRender = false;
		$erp_material_brand = TableRegistry::get('erp_material_brand');
		$material_type = $this->request->data["material_type"];
		$brand_name = $this->request->data["brand_name"];				
		$project_id = $this->request->data["project_id"];				
		
		$check = $erp_material_brand->find("all")->where(["material_type"=>$material_type,"brand_name"=>$brand_name])->count();
		// echo $check;die;
		if($check == 0)
		{
			$table_field = $erp_material_brand->newEntity();	
			$this->request->data['material_type']=$material_type;			
			$this->request->data['brand_name']=$brand_name;			
			$this->request->data['project_id']=$project_id;			
			$this->request->data['status']=1;			
			$new_data=$erp_material_brand->patchEntity($table_field,$this->request->data);
			if($erp_material_brand->save($new_data))
			{
				$last_brand = $new_data->brand_id;
				echo "<option value='".$last_brand."'>{$brand_name}</option>";
			}	
		}
		else{
			echo 'duplicate';
		}
	}
	
	public function purchaseraterow()
	{
		$row_id = $_REQUEST['row_id'];
		$erp_material = TableRegistry::get('erp_material'); 
		$material_list = $erp_material->find();
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);
	}
	
	public function approverate()
	{
		$rate_detail_id = $_REQUEST['rate_detail_id'];
		$erp_finalized_rate_detail = TableRegistry::get('erp_finalized_rate_detail');
		$rate_data = $erp_finalized_rate_detail->get($rate_detail_id);
		$post_data['approved'] = 1;
		$post_data['approved_date'] = date('Y-m-d H:i:s');
		$post_data['approved_by'] = $this->request->session()->read('user_id');
		$data = $erp_finalized_rate_detail->patchEntity($rate_data,$post_data);
		$erp_finalized_rate_detail->save($data);
		die();
	}
	
	public function getmaterialrate()
	{
		$project_id = $_REQUEST['project_id'];
		$po_date = date("Y-m-d",strtotime($_REQUEST['po_date']));
		$vendor_id = $_REQUEST['vendor_id'];
		$material_id = $_REQUEST['material_id'];
		$brand_id = $_REQUEST['brand_id'];
		
		$rate_tbl = TableRegistry::get("erp_finalized_rate");
		$rated_tbl = TableRegistry::get("erp_finalized_rate_detail");
		$assign_tbl = TableRegistry::get("erp_rate_assign_project");
		$or = array();				
		
		// $or["erp_finalized_rate_detail.rate_from_date >="] = ($po_date != "")?date("Y-m-d",strtotime($po_date)):NULL;
		// $or["erp_finalized_rate_detail.rate_to_date <="] = ($po_date != "")?date("Y-m-d",strtotime($po_date)):NULL;
		$or["erp_rate_assign_project.project_id"] = ($project_id != "")?$project_id:NULL;
		$or["erp_finalized_rate_detail.material_id"] = ($material_id != "")?$material_id:NULL;
		$or["erp_finalized_rate_detail.brand_id"] = ($brand_id != "")?$brand_id:NULL;
		$or["erp_finalized_rate.vendor_userid"] = ($vendor_id != "")?$vendor_id:NULL;
		$or["erp_finalized_rate_detail.approved"] =	1;			
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		//debug($or);die;
		
		$result = $rated_tbl->find()->select($rated_tbl)->group(['rate_detail_id'])->where(function($exp){
															return $exp
																	->gte("rate_to_date",date("Y-m-d",strtotime($_REQUEST['po_date'])))
																	->lte("rate_from_date",date("Y-m-d",strtotime($_REQUEST['po_date'])));
																											
														});
		$result = $result->leftjoin(
					["erp_finalized_rate"=>"erp_finalized_rate"],
					["erp_finalized_rate_detail.rate_id = erp_finalized_rate.rate_id"]);
		$result = $result->leftjoin(
					["erp_rate_assign_project"=>"erp_rate_assign_project"],
					["erp_finalized_rate.rate_id = erp_rate_assign_project.rate_id"])
					->where($or)->select(["project_id"=>'group_concat(project_id)'])->hydrate(false)->toArray();
		// debug($result);die;
		if($brand_id == '')
		{
			if(!empty($result))
			{
				$result_arr['hsn_code'] = '';
				$result_arr['unit_price'] = 0;
				$result_arr['discount'] = 0;
				$result_arr['transportation'] = 0;
				$result_arr['gst'] = 0;
				$result_arr['other_tax'] = 0;
				$result_arr['final_rate'] = 0;
			}
			else
			{
				$result_arr['hsn_code'] = '';
				$result_arr['unit_price'] = 0;
				$result_arr['discount'] = 0;
				$result_arr['transportation'] = 0;
				$result_arr['gst'] = 0;
				$result_arr['other_tax'] = 0;
				$result_arr['final_rate'] = 0;
			}
		}
		else
		{
			if(!empty($result))
			{
				$result_arr['hsn_code'] = '';
				$result_arr['unit_price'] = $result[0]['unit_price'];
				$result_arr['discount'] = $result[0]['discount'];
				$result_arr['transportation'] = $result[0]['transportation'];
				$result_arr['gst'] = $result[0]['gst'];
				$result_arr['other_tax'] = $result[0]['other_tax'];
				$result_arr['final_rate'] = $result[0]['final_rate'];
			}
			else
			{
				$result_arr['hsn_code'] = '';
				$result_arr['unit_price'] = 0;
				$result_arr['discount'] = 0;
				$result_arr['transportation'] = 0;
				$result_arr['gst'] = 0;
				$result_arr['other_tax'] = 0;
				$result_arr['final_rate'] = 0;
			}
		}
		//debug($result_arr);die;
		echo json_encode($result_arr);
		die();
	}
	
	public function getmultiplematerialrate()
	{
		// debug($_REQUEST);die;
		$project_id = $_REQUEST['project_id'];
		$po_date = date("Y-m-d",strtotime($_REQUEST['po_date']));
		$vendor_id = $_REQUEST['vendor_id'];
		$val_arr = $_REQUEST['val_arr'];
		
		$all_result = array();
		foreach($val_arr as $key => $data)
		{
			$material_id = $val_arr[$key]['material_id'];
			$brand_id = $val_arr[$key]['brand_id'];
			
			$rate_tbl = TableRegistry::get("erp_finalized_rate");
			$rated_tbl = TableRegistry::get("erp_finalized_rate_detail");
			$assign_tbl = TableRegistry::get("erp_rate_assign_project");
			$or = array();				
			
			// $or["erp_finalized_rate_detail.rate_from_date >="] = ($po_date != "")?date("Y-m-d",strtotime($po_date)):NULL;
			// $or["erp_finalized_rate_detail.rate_to_date <="] = ($po_date != "")?date("Y-m-d",strtotime($po_date)):NULL;
			$or["erp_rate_assign_project.project_id"] = ($project_id != "")?$project_id:NULL;
			$or["erp_finalized_rate_detail.material_id"] = ($material_id != "")?$material_id:NULL;
			$or["erp_finalized_rate_detail.brand_id"] = ($brand_id != "")?$brand_id:NULL;
			$or["erp_finalized_rate.vendor_userid"] = ($vendor_id != "")?$vendor_id:NULL;
			$or["erp_finalized_rate_detail.approved"] =	1;			
			$keys = array_keys($or,"");				
			foreach ($keys as $k)
			{unset($or[$k]);}
			//debug($or);die;
			
			$result = $rated_tbl->find()->select($rated_tbl)->group(['rate_detail_id'])->where(function($exp){
																return $exp
																		->gte("rate_to_date",date("Y-m-d",strtotime($_REQUEST['po_date'])))
																		->lte("rate_from_date",date("Y-m-d",strtotime($_REQUEST['po_date'])));
																												
															});
			$result = $result->leftjoin(
						["erp_finalized_rate"=>"erp_finalized_rate"],
						["erp_finalized_rate_detail.rate_id = erp_finalized_rate.rate_id"]);
			$result = $result->leftjoin(
						["erp_rate_assign_project"=>"erp_rate_assign_project"],
						["erp_finalized_rate.rate_id = erp_rate_assign_project.rate_id"])
						->where($or)->select(["project_id"=>'group_concat(project_id)'])->hydrate(false)->toArray();
			// debug($result);die;
			if($brand_id == '')
			{
				if(!empty($result))
				{
					$result_arr['hsn_code'] = '';
					$result_arr['unit_price'] = 0;
					$result_arr['discount'] = 0;
					$result_arr['transportation'] = 0;
					$result_arr['gst'] = 0;
					$result_arr['other_tax'] = 0;
					$result_arr['final_rate'] = 0;
				}
				else
				{
					$result_arr['hsn_code'] = '';
					$result_arr['unit_price'] = 0;
					$result_arr['discount'] = 0;
					$result_arr['transportation'] = 0;
					$result_arr['gst'] = 0;
					$result_arr['other_tax'] = 0;
					$result_arr['final_rate'] = 0;
				}
			}
			else
			{
				if(!empty($result))
				{
					$result_arr['hsn_code'] = $result[0]['hsn_code'];
					$result_arr['unit_price'] = $result[0]['unit_price'];
					$result_arr['discount'] = $result[0]['discount'];
					$result_arr['transportation'] = $result[0]['transportation'];
					$result_arr['gst'] = $result[0]['gst'];
					$result_arr['other_tax'] = $result[0]['other_tax'];
					$result_arr['final_rate'] = $result[0]['final_rate'];
				}
				else
				{
					$result_arr['hsn_code'] = '';
					$result_arr['unit_price'] = 0;
					$result_arr['discount'] = 0;
					$result_arr['transportation'] = 0;
					$result_arr['gst'] = 0;
					$result_arr['other_tax'] = 0;
					$result_arr['final_rate'] = 0;
				}
			}
			$all_result[] = $result_arr;
		}
		//debug($all_result);die;
		echo json_encode($all_result);die;
	}
	
	public function getstategstno()
	{
		$state = $_REQUEST['state'];
		$gst_no = '';
		if($state == 'gujarat')
		{
			$gst_no = '24AABCY0913A1Z1';
		}
		else if($state == 'mp')
		{
			$gst_no = '23AABCY0913A1Z3';
		}
		else if($state == 'maharastra')
		{
			$gst_no = '27AABCY0913A1ZV';
		}
		else if($state == 'haryana')
		{
			$gst_no = '06AABCY0913A1ZZ';
		}
		
		echo $gst_no;
		die;
	}
	
	public function getstatepanno()
	{
		$state = $_REQUEST['state'];
		$pan_no = '';
		if($state == 'gujarat')
		{
			$pan_no = 'AABCY0913A';
		}
		else if($state == 'mp')
		{
			$pan_no = 'AABCY0913A';
		}
		else if($state == 'maharastra')
		{
			$pan_no = 'AABCY0913A';
		}
		else if($state == 'haryana')
		{
			$pan_no = 'AABCY0913A';
		}
		echo $pan_no;
		die;
	}
	
	public function workhead()
	{
		
	}
	
	public function planningworkhead()
	{
		$erp_planning_work_head = TableRegistry::get("erp_planning_work_head");
		$workHead = $erp_planning_work_head->find()->hydrate(false)->toArray();
		$this->set("work_head",$workHead);

		$project_id = $this->request->data['project_id'];
		$this->set("project_id",$project_id);
	}
	
	public function addworkhead()
	{
		//debug($this->request->data);die;
		$this->autoRender = false;
		$type_of_contract = $this->request->data["type_of_contract"];
		$work_head_code = $this->request->data["work_head_code"];
		$work_head_title = $this->request->data["work_head_title"];
		
		$erp_work_head = TableRegistry::get('erp_work_head');
		
		$check = $erp_work_head->find("all")->where(["work_head_title"=>$work_head_title])->count();
		if($check == 0)
		{				
			$table_field = $erp_work_head->newEntity();	
			$this->request->data['type_of_contract']=$type_of_contract;
			$this->request->data['work_head_code']=$work_head_code;
			$this->request->data['work_head_title']=$work_head_title;
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data['created_by']=$this->request->session()->read('user_id');			
					
			$new_data=$erp_work_head->patchEntity($table_field,$this->request->data);
			if($erp_work_head->save($new_data))
			{		
				$last_head = $new_data->work_head_id;
				echo "<option value='".$last_head."'>{$work_head_title}</option>";
			}
								 
		}
		else{
				echo 'duplicate';
		}
		die;
	}
	
	public function addplanningworkhead()
	{
		$this->autoRender = false;
		$work_head_code = $this->request->data["work_head_code"];
		$work_head_title = $this->request->data["work_head_title"];
		$project_id = $this->request->data["project_id"];
		
		$erp_work_head = TableRegistry::get('erp_planning_work_head');
		
		$check = $erp_work_head->find("all")->where(["work_head_title"=>$work_head_title,"project_id"=>$project_id])->count();
		if($check == 0)
		{				
			$table_field = $erp_work_head->newEntity();	
			$this->request->data['project_id']=$project_id;
			$this->request->data['work_head_code']=$work_head_code;
			$this->request->data['work_head_title']=$work_head_title;
			$this->request->data['created_date']=date('Y-m-d H:i:s');
			$this->request->data['created_by']=$this->request->session()->read('user_id');			
					
			$new_data=$erp_work_head->patchEntity($table_field,$this->request->data);
			if($erp_work_head->save($new_data))
			{		
				$last_head = $new_data->work_head_id;
				echo "<option value='".$last_head."'>{$work_head_title}</option>";
			}
								 
		}
		else{
				echo 'duplicate';
		}
		die;
	}
	
	public function addnewrowwo()
	{
		$row_id = $_REQUEST['row_id'];		
		$this->set('row_id',$row_id);
		$erp_work_head = TableRegistry::get('erp_work_head'); 
		$head_list = $erp_work_head->find();
		$this->set('work_head_list',$head_list);
	}
	
	public function addnewrowplanningwo() {
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$this->set('row_id',$row_id);
		$description_options = "";
		$table_category=TableRegistry::get('erp_category_master');
		$descriptionValue = $table_category->find()->where(['type' => "subcontractbill_option"])->select(['cat_id',"category_title","project_id"])->hydrate(false)->toArray();
		foreach($descriptionValue as $data){
			$formattedProject = json_decode($data['project_id']);
			if($project_id != '' && $formattedProject != ''){
				if(in_array($project_id,$formattedProject)){
					$conn = ConnectionManager::get('default');
					$description_options = $conn->execute("SELECT cat_id,category_title FROM `erp_category_master` WHERE JSON_CONTAINS(`project_id`, '\"$project_id\"')")->fetchAll("assoc");
				}
			}	
		}
		$this->set('description_options',$description_options);
	}

	public function addnewrowplanningwonew()
	{
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$this->set('row_id',$row_id);
		$table_category=TableRegistry::get('erp_category_master');
		$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option','project_id'=>$project_id));
		$this->set('description_options',$description_options);
	}

	public function editrowplanningwo() {
		$id = $_REQUEST['id'];
		$project_id=$_REQUEST['project_id'];
		$table_planning = TableRegistry::get('erp_planning_work_order_detail');
		$data = $table_planning->find()->where(['wo_detail_id'=>$id])->first()->toArray();
		$this->set('data',$data);
		$table_category=TableRegistry::get('erp_category_master');
		$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option','project_id'=>$project_id));
		$this->set('description_options',$description_options);
	}

	public function saverowplanningwo() {
		// $id = $_REQUEST['detailId'];
		$projectId = $_REQUEST['projectId'];
		$contractId = $_REQUEST['contractId'];
		$woId = $_REQUEST['woId'];
		$materialName = $_REQUEST['materialName'];
		$detailDescription = $_REQUEST['detailDescription'];
		$quantityThisWo = $_REQUEST['quantityThisWo'];
		$quantityPreviousWo = $_REQUEST['quantityPreviousWo'];
		$tillDateWoQuantity = $_REQUEST['tillDateWoQuantity'];
		$unit = $_REQUEST['unit'];
		$unitRate = $_REQUEST['unitRate'];
		$amount = $_REQUEST['amount'];
		$amountTillDate = $_REQUEST['amountTillDate'];

		$cgstPercentageAmount = $_REQUEST['cgstPercentage0'];
		$cgstAmount = $_REQUEST['cgstAmount'];
		$cgstTillDateAmount = $_REQUEST['cgstTillDateAmount'];
		$sgstPercentageAmount = $_REQUEST['sgstPercentage0'];
		$sgstAmount = $_REQUEST['sgstAmount'];
		$sgstTillDateAmount = $_REQUEST['sgstTillDateAmount'];
		$igstPercentageAmount = $_REQUEST['igstPercentage0'];
		$igstAmount = $_REQUEST['igstAmount'];
		$igstTillDateAmount = $_REQUEST['igstTillDateAmount'];
		$netAmount = $_REQUEST['netAmount'];
		$tillDateNetAmount = $_REQUEST['tillDateNetAmount'];
		$woTotalAmount = $_REQUEST['woTotalAmount'];
		$tillDateWoTotalAmount = $_REQUEST['tillDateWoTotalAmount'];

		$erpPlanningWorkOrderDetail = TableRegistry::get('erp_planning_work_order_detail');
		$retrivedData = $erpPlanningWorkOrderDetail->newEntity();
		$retrivedData -> wo_id = $woId;
		$retrivedData -> contract_no = $contractId;
		$retrivedData -> material_name = $materialName;
		$retrivedData -> detail_description = $detailDescription;
		$retrivedData -> quentity = $quantityThisWo;
		$retrivedData -> quantity_upto_previous = $quantityPreviousWo;
		$retrivedData -> till_date_quantity = $tillDateWoQuantity;
		$retrivedData -> unit_rate = $unitRate;
		$retrivedData -> unit = $unit;
		$retrivedData -> amount = $amount;
		$retrivedData -> amount_till_date = $amountTillDate;

		$tablePlanningWorkOrder = TableRegistry :: get('erp_planning_work_order');
		$retrivedData = $tablePlanningWorkOrder->newEntity();
		$retrivedData -> cgst_percentage =$cgstPercentageAmount;
		$retrivedData -> cgst = $cgstAmount;
		$retrivedData -> igst_percentage = $igstPercentageAmount;
		$retrivedData -> igst = $igstAmount;
		$retrivedData -> sgst_percentage = $sgstPercentageAmount;
		$retrivedData -> till_date_cgst =$cgstTillDateAmount;
		$retrivedData -> till_date_igst = $igstTillDateAmount;
		$retrivedData -> till_date_sgst = $sgstTillDateAmount;
		$retrivedData -> sub_total = $woTotalAmount;
		$retrivedData -> till_date_sub_total = $tillDateWoTotalAmount;
		$retrivedData -> net_amount = $netAmount;
		$retrivedData -> till_date_net_amount = $tillDateNetAmount;
		$tablePlanningWorkOrder->save($retrivedData);

		$id = '';
		if($erpPlanningWorkOrderDetail -> save($retrivedData)) {
			$id = $retrivedData-> wo_detail_id;
		}
		$this->set('row_id',$id);
		$table_planning = TableRegistry::get('erp_planning_work_order_detail');
		$data = $table_planning->find()->where(['wo_detail_id'=>$id])->first()->toArray();
		$this->set('data',$data);
		$table_category=TableRegistry::get('erp_category_master');
		$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option','project_id'=>$projectId));
		$this->set('description_options',$description_options);
	} 

	public function updaterowplanningwo() {
		$id = $_REQUEST['detailId'];
		$projectId = $_REQUEST['projectId'];
		$woId = $_REQUEST['woId'];
		$contractId = $_REQUEST['contractId'];
		$materialName = $_REQUEST['materialName'];
		$detailDescription = $_REQUEST['detailDescription'];
		$quantityThisWo = $_REQUEST['quantityThisWo'];
		$quantityPreviousWo = $_REQUEST['quantityPreviousWo'];
		$tillDateWoQuantity = $_REQUEST['tillDateWoQuantity'];
		$unit = $_REQUEST['unit'];
		$unitRate = $_REQUEST['unitRate'];
		$amount = $_REQUEST['amount'];
		$amountTillDate = $_REQUEST['amountTillDate'];
		$cgstPercentageAmount = $_REQUEST['cgstPercentage0'];
		$cgstAmount = $_REQUEST['cgstAmount'];
		$cgstTillDateAmount = $_REQUEST['cgstTillDateAmount'];
		$sgstPercentageAmount = $_REQUEST['sgstPercentage0'];
		$sgstAmount = $_REQUEST['sgstAmount'];
		$sgstTillDateAmount = $_REQUEST['sgstTillDateAmount'];
		$igstPercentageAmount = $_REQUEST['igstPercentage0'];
		$igstAmount = $_REQUEST['igstAmount'];
		$igstTillDateAmount = $_REQUEST['igstTillDateAmount'];
		$netAmount = $_REQUEST['netAmount'];
		$tillDateNetAmount = $_REQUEST['tillDateNetAmount'];
		$woTotalAmount = $_REQUEST['woTotalAmount'];
		$tillDateWoTotalAmount = $_REQUEST['tillDateWoTotalAmount'];
		//Edit row save data in planning work order detail table
		$erpPlanningWorkOrderDetail = TableRegistry::get('erp_planning_work_order_detail');
		$retrivedData = $erpPlanningWorkOrderDetail->get($id);
		$retrivedData -> wo_detail_id = $id;
		$retrivedData -> wo_id = $woId;
		$retrivedData -> contract_no = $contractId;
		$retrivedData -> material_name = $materialName;
		$retrivedData -> detail_description = $detailDescription;
		$retrivedData -> quentity = $quantityThisWo;
		$retrivedData -> quantity_upto_previous = $quantityPreviousWo;
		$retrivedData -> till_date_quantity = $tillDateWoQuantity;
		$retrivedData -> unit = $unit;
		$retrivedData -> unit_rate = $unitRate;
		$retrivedData -> amount = $amount;
		$retrivedData -> amount_till_date = $amountTillDate;
		$erpPlanningWorkOrderDetail->save($retrivedData);
		
		$table_planning = TableRegistry::get('erp_planning_work_order_detail');
		$data = $table_planning->find()->where(['wo_detail_id'=>$id])->first()->toArray();
		$this->set('data',$data);
		$table_category=TableRegistry::get('erp_category_master');
		$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option','project_id'=>$projectId));
		$this->set('description_options',$description_options);
		
		$tablePlanningWorkOrder = TableRegistry :: get('erp_planning_work_order');
		$planningWorkOrderData = $tablePlanningWorkOrder->get($woId);
		$planningWorkOrderData -> cgst_percentage = $cgstPercentageAmount;
		$planningWorkOrderData -> cgst = $cgstAmount;
		$planningWorkOrderData -> igst_percentage = $igstPercentageAmount;
		$planningWorkOrderData -> igst = $igstAmount;
		$planningWorkOrderData -> sgst_percentage = $sgstPercentageAmount;
		$planningWorkOrderData -> sgst = $sgstAmount;
		$planningWorkOrderData -> till_date_cgst = $cgstTillDateAmount;
		$planningWorkOrderData -> till_date_igst = $igstTillDateAmount;
		$planningWorkOrderData -> till_date_sgst = $sgstTillDateAmount;
		$planningWorkOrderData -> sub_total = $woTotalAmount;
		$planningWorkOrderData -> till_date_sub_total = $tillDateWoTotalAmount;
		$planningWorkOrderData -> net_amount = $netAmount;
		$planningWorkOrderData -> till_date_net_amount = $tillDateNetAmount;
		$tablePlanningWorkOrder->save($planningWorkOrderData);
	}
	
	public function vendoragencydetail()
	{
		$party_id = $_REQUEST['party_id'];
		$party_type = $_REQUEST['party_type'];
		
		$result_arr['party_id'] = '';			
		$result_arr['address'] = '';		
		$result_arr['delivery_place'] = '';		
		$result_arr['contact_no1'] = '';		
		$result_arr['contact_no2'] = '';		
		$result_arr['email_id'] = '';		
		$result_arr['pancard_no'] = '';		
		$result_arr['gst_no'] = '';
		
		if($party_type == 'vendor')
		{
			$vendor_userid = $party_id;
			$usersdetail = TableRegistry::get('erp_vendor'); 
			$user_data = $usersdetail->find()->where(['user_id'=>$vendor_userid]);
			
			foreach($user_data as $retrive_data)
			{
				$result_arr['party_id'] = $retrive_data['vendor_id'];			
				$result_arr['address'] = $retrive_data['vendor_billing_address'];			
				$result_arr['delivery_place'] = $retrive_data['vendor_billing_address'];		
				$result_arr['contact_no1'] = $retrive_data['contact_no1'];		
				$result_arr['contact_no2'] = $retrive_data['contact_no2'];		
				$result_arr['email_id'] = $retrive_data['email_id'];		
				$result_arr['pancard_no'] = $retrive_data['pancard_no'];		
				$result_arr['gst_no'] = $retrive_data['gst_no'];		
			}
		}
		else
		{
			$agency_id = $party_id;
			$erp_agency = TableRegistry::get('erp_agency'); 
			$user_data = $erp_agency->find()->where(['agency_id'=>$agency_id]);
			
			foreach($user_data as $retrive_data)
			{
				$result_arr['party_id'] = $retrive_data['agency_id'];			
				$result_arr['address'] = $retrive_data['agency_billing_address'];			
				$result_arr['delivery_place'] = $retrive_data['agency_billing_address'];		
				$result_arr['contact_no1'] = $retrive_data['contact_no'];		
				$result_arr['email_id'] = $retrive_data['email_id'];		
				$result_arr['pancard_no'] = $retrive_data['pancard_no'];		
				$result_arr['gst_no'] = $retrive_data['gst_no'];		
			}
		}
		echo json_encode($result_arr);
		die();
	}
	
	public function firstapprovewo()
	{
		$wo_id = $_REQUEST['wo_id'];
		
		$wod_tbl = TableRegistry::get('erp_work_order_detail');
		
		$date=date('Y-m-d H:i:s');
		$user = $this->request->session()->read('user_id');
		$query = $wod_tbl->query();
		$approve = $query->update()
						->set(['first_approved'=>1,
						"first_approved_date"=>$date,
						'first_approved_by'=>$user])
						->where(['wo_id' => $wo_id])
						->execute();
		die;
	}
	
	public function firstapproveplanningwo()
	{
		$wo_id = $_REQUEST['wo_id'];
		
		$wod_tbl = TableRegistry::get('erp_planning_work_order_detail');
		
		$date=date('Y-m-d H:i:s');
		$user = $this->request->session()->read('user_id');
		$query = $wod_tbl->query();
		$approve = $query->update()
						->set(['first_approved'=>1,
						"first_approved_date"=>$date,
						'first_approved_by'=>$user])
						->where(['wo_id' => $wo_id])
						->execute();
		die;
	}
	
	public function verifyewo()
	{
		$wo_id = $_REQUEST['wo_id'];
		
		$wod_tbl = TableRegistry::get('erp_work_order_detail');
		
		$date=date('Y-m-d H:i:s');
		$user = $this->request->session()->read('user_id');
		$query = $wod_tbl->query();
		$approve = $query->update()
						->set(['verified'=>1,
						"verified_date"=>$date,
						'verified_by'=>$user])
						->where(['wo_id' => $wo_id])
						->execute();
		die;
	}
	
	public function verifyeplanningwo()
	{
		$wo_id = $_REQUEST['wo_id'];
		
		$wod_tbl = TableRegistry::get('erp_planning_work_order_detail');
		
		$date=date('Y-m-d H:i:s');
		$user = $this->request->session()->read('user_id');
		$query = $wod_tbl->query();
		$approve = $query->update()
						->set(['verified'=>1,
						"verified_date"=>$date,
						'verified_by'=>$user])
						->where(['wo_id' => $wo_id])
						->execute();
		die;
	}
	
	public function approvewo()
	{
		$wo_id = $_REQUEST['wo_id'];
		
		$wod_tbl = TableRegistry::get('erp_work_order_detail');
		
		$date=date('Y-m-d H:i:s');
		$user = $this->request->session()->read('user_id');
		$query = $wod_tbl->query();
		$approve = $query->update()
						->set(['approved'=>1,
						"approved_date"=>$date,
						'approved_by'=>$user])
						->where(['wo_id' => $wo_id])
						->execute();
						
		if($approve)
		{	
			$party_emails = array();
			$wo_tbl = TableRegistry::get('erp_work_order');
			$row = $wo_tbl->get($wo_id);
			$party_user_id = $row->party_userid;
			if(is_numeric($party_user_id))
			{
				$party_email = $this->ERPfunction->get_vendor_email($party_user_id);
			}else{
				$party_email = $this->ERPfunction->get_agency_email($party_user_id);
			}
			$party_email = explode(",",$party_email);
			if(!empty($party_email))
			{
				foreach($party_email as $mail)
				{
					$party_emails[] = $mail;
				}
			}
			
			$project_id = $row->project_id;
			$row['approved_status'] = 1;
			$row['approved_date'] = $date;
			$row['approved_by'] = $user;
			
			if($wo_tbl->save($row))
			{
				$mail_enable = $this->ERPfunction->get_wo_mail_status($wo_id);
				$email_list = $this->ERPfunction->get_mail_list_by_project_wo($project_id,$mail_enable,'"wo_notification"');
				
				if($mail_enable == 1 || $mail_enable == 2 )
				{
					$emails = array_merge($email_list,$party_emails);
					$emails = array_unique($emails);
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					$emails = array_filter($emails, function($value) { return $value !== NULL; });
				}
				else{
					$emails = array_unique($email_list);
				}
				
												
				// Check the party email format are correct or not? code start
				$email_correct = 1;
				$wrong_email = array();
				foreach($party_emails as $value)
				{
					if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
					 
					} else {
						$email_correct = 0;
						$wrong_email[] = $value;
					}
				}
				
				// Check the party email format are correct or not? code end
				if($email_correct)
				{
					if(!empty($emails))
					{
						$emails = implode(",",$emails);		
						$this->ERPfunction->wo_approve_mail($emails,$wo_id);
						
						$this->Flash->success(__('Work Order Approved Successfully', null), 
								'default', 
								array('class' => 'success'));
						$this->redirect(["controller"=>"contract","action"=>"approvewo"]);
					}
				}else{
					$query1 = $wod_tbl->query();
					$disapprove = $query1->update()
									->set(['approved'=>0,
									"approved_date"=>'',
									'approved_by'=>''])
									->where(['wo_id' => $wo_id])
									->execute();
					if($disapprove)
					{
						$row1 = $wo_tbl->get($wo_id);
						$row1['approved_status'] = 0;
						$row1['approved_date'] = '';
						$row1['approved_by'] = '';
						$wo_tbl->save($row1);
					}
					// debug($wrong_email);die;
					echo "email_issue";die;
				}
			}
			
		}
		
		die;
	}
	
	public function getprojectwisestate() {
		$project_id = $_REQUEST['project_id'];
		$erpProjects = TableRegistry::get('erp_projects');
		$erpProjectDetails = $erpProjects->find()->select('state')->where(["project_id"=>$project_id]);
		if(!empty($erpProjectDetails)) {
			foreach($erpProjectDetails as $retrive_data) {
				$state = $retrive_data['state'];
			}
		}
		$this->response->body($state);
		return $this->response;
		die;
	}

	public function getprojectwisematerial()
	{
		$project_id = $_REQUEST['project_id'];
		$erp_material = TableRegistry::get('erp_material');
		
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find("list",["keyField"=>"material_id","valueField"=>"material_title"])->where(["project_id"=>0,'material_id IN'=>$material_ids]);
		}else{
			$material_list = $erp_material->find("list",["keyField"=>"material_id","valueField"=>"material_title"])->where(["project_id"=>0]);
		}
		
		$options = "<option value=''>--Select Material--</option>";
		if(!empty($material_list))
		{
			foreach($material_list as $key=>$value)
			{
				$options .= "<option value='{$key}'>{$value}</option>";
			}
		}
		
		if($project_id != '')
		{ 
			// $material_data = $erp_material->find("list",["keyField"=>"material_id","valueField"=>"material_title"])->where(['project_id'=>$project_id]);
			if($this->role == "deputymanagerelectric")
			{
				// $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
				// $material_ids = json_decode($material_ids);
				$material_data = $erp_material->find("list",["keyField"=>"material_id","valueField"=>"material_title"])->where(["project_id"=>$project_id]);
				// $material_data = $erp_material->find("list",["keyField"=>"material_id","valueField"=>"material_title"])->where(["project_id"=>$project_id,'material_id IN'=>$material_ids]);
			}else{
				$material_data = $erp_material->find("list",["keyField"=>"material_id","valueField"=>"material_title"])->where(["project_id"=>$project_id]);
			}
			if(!empty($material_data))
			{
				foreach($material_data as $key=>$value)
				{
					$options .= "<option value='{$key}'>{$value}</option>";
				}
			}
		}
		echo $options;
		die;
	}
	
	public function multipleapproveattendance()
	{
		$this->autoRender=false;
		$user = $this->request->session()->read('user_id');
		
		$request_id = json_decode($this->request->data["request_id"]);
		foreach($request_id as $req_id)
		{
			if($req_id != '')
			{
				$tbl = TableRegistry::get("erp_attendance_detail");
				$row = $tbl->get($req_id);
				$row->approved = 1;
				$row->approved_by = $user;
				$row->approved_date = date("Y-m-d");
				$tbl->save($row);
			}
		}
	}
	
	public function designationwisecategory()
	{
		$this->autoRender=false;
		$id = $_REQUEST['designation_id'];
		$category_master_Table = TableRegistry::get('erp_category_master'); 
		$result = $category_master_Table->find()->where(['cat_id'=>$id])->select('category')->hydrate(false)->toArray();
		$category = '';
		if(!empty($result))
		{
			$category = $result[0]['category'];
		}
		echo $category;
	}
	
	public function addreference()
	{
		$project_id = $_REQUEST['project_id'];
		$this->set('project_id',$project_id);
	}
	
	public function projectreference()
	{
		$project_id = $_REQUEST['project_id'];
		$erp_reference = TableRegistry::get("erp_reference");
		$reference = $erp_reference->find("list",["keyField"=>"reference_id","valueField"=>"title"])->where(["project_id"=>$project_id]);
		if(!empty($reference))
		{
			$options = "<option value=''>Select Reference</option>";
			foreach($reference as $key=>$value)
			{
				$options .= "<option value='{$key}'>{$value}</option>";
			}
		}else{
			$options = "<option value=''>No Reference Found</option>";
		}
		$result_arr['reference'] = $options;
		echo json_encode($result_arr);
		die();
	}
	
	public function referencerow()
	{
		$row_id = $_REQUEST['row_id'];		
		$this->set('row_id',$row_id);	
	}
	
	public function deletedrawingdetail()
	{
		$this->autoRender = false ;
		$detail_id = $_REQUEST['detail_id'];
		
		$dtl_tbl = TableRegistry::get('erp_drawing_detail');
		
		$row =$dtl_tbl->get($detail_id);
		$drawing_id = $row->drawing_id;
		if($dtl_tbl->delete($row))
		{
			$count = $dtl_tbl->find()->where(['drawing_id'=>$drawing_id])->count();
			if($count == 0)
			{
				$erp_drawing = TableRegistry::get('erp_drawing');
				$data = $erp_drawing->get($drawing_id);
				$erp_drawing->delete($data);
			}
		}
		die;
	}
	
	public function joinmaterial()
	{
		$material_id = $_REQUEST['material_id'];
		$erp_material = TableRegistry::get('erp_material');
		
		$material_list = $erp_material->find("list",["keyField"=>"material_id","valueField"=>"material_title"])->where(["material_id !=" => $material_id,'project_id'=>0]);
		
		$this->set("material_list",$material_list);
		$this->set("material_id",$material_id);
	}

	public function joinvendor()
	{
		$vendorChildId = $_REQUEST['vendorChildId'];

		$erpVendor = TableRegistry::get('erp_vendor');
		
		$vendorList = $erpVendor->find("list",["keyField"=>"user_id","valueField"=>"vendor_name"])->where(["user_id !=" => $vendorChildId]);
		
		$this->set("vendor_list",$vendorList);
		$this->set("vendor_id",$vendorChildId);
	}
	
	public function debitnoteprojectdetail()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);		
		$result_arr = array();
		
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		/* $number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_purhcase_request","prno","pr_id"); */
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_debit_note","debit_id","debit_note_no");

		$new_no = sprintf("%09d", $number1);
		$debitno = 'YNEC/P/'.$result_arr['project_code'].'/DN/'.$new_no;
		$result_arr['debitno'] = $debitno;
		echo json_encode($result_arr);
		die();
	}
	
	public function addnewedebitrow()
	{
		$row_id = $_REQUEST['row_id'];
		$this->set('row_id',$row_id);
		
		$sr_no = $_REQUEST['sr_no'];
		$this->set('sr_no',$sr_no);
	}
	
	public function addnewinventorydebitrow()
	{
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		
		$erp_material = TableRegistry::get('erp_material');
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_id IN"=>$material_ids,"project_id IN"=>$projectids_in]);
		}else{
			$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		} 
		$this->set('material_list',$material_list);
		
		$row_id = $_REQUEST['row_id'];
		$this->set('row_id',$row_id);
		
		$sr_no = $_REQUEST['sr_no'];
		$this->set('sr_no',$sr_no);
	}
	
	public function deletewodetail()
	{
		$this->autoRender = false ;
		$detail_id = $_REQUEST['detail_id'];
		
		$wom_tbl = TableRegistry::get('erp_work_order_detail');
		$row =$wom_tbl->get($detail_id);
		$wom_tbl->delete($row);
		die;
	}

	public function deleteplanningwodetail()
	{
		$this->autoRender = false ;
		$detail_id = $_REQUEST['detail_id'];
		
		$wom_tbl = TableRegistry::get('erp_planning_work_order_detail');
		$row =$wom_tbl->get($detail_id);
		$wom_tbl->delete($row);
		die;
	}
	
	public function addquentity()
	{
		$row = $_REQUEST['row'];
		$add_in = $_REQUEST['add_in'];
		
		$this->set('row',$row);
		$this->set('add_in',$add_in);
	}
	
	public function checkSameMonthPaystructureHistory()
	{
		$this->autoRender = false;
		$user_id = $this->request->data["employee_id"];
		$change_date = $this->request->data["date"];
		
		$modified_date = date("Y-m",strtotime($change_date));
		$modified_date = $modified_date."-01";
			
		$tbl = TableRegistry::get("erp_users_history");
		
		$history = $tbl->find()->where(["user_id"=>$user_id,"change_date"=>$modified_date])->count();
		if($history)
		{
			echo "true";
		}else{
			echo "false";
		}
	}
	
	public function addnewrowsubcontract()
	{
		$row_id = $_REQUEST['row_id'];		
		$project_id = $_REQUEST['project_id'];		
		$this->set('row_id',$row_id);
		$table_category=TableRegistry::get('erp_category_master');
		$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option','project_id'=>$project_id));
		$this->set('description_options',$description_options);
	}
	
	public function getsubcontractoldrow()
	{
		$this->autoRender = false;
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$party_id = $_REQUEST['party_id'];
		
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		
		$data = $erp_sub_contract->find()->where(["project_id"=>$project_id,"party_id"=>$party_id])->order(["id"=>"desc"])->first();
		
		
		$html = "";
		$debit_till_date = 0;
		$reconciliation_till_date = 0;
		if(!empty($data))
		{
			$table_category=TableRegistry::get('erp_category_master');
			$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option'));
			$this->set('description_options',$description_options);
		
			$contract_id = $data->id;
						
			$detail_data = $erp_sub_contract_detail->find()->where(["sub_contract_id"=>$contract_id,"approval"=>1])->order("item_no","ASC")->hydrate(false)->toArray();
			
			if(!empty($detail_data))
			{
				$debit_till_date = $data->debit_till_date;
				$reconciliation_till_date = $data->reconciliation_till_date;
				foreach($detail_data as $detail)
				{
					$html .= "<tr id='row_id_{$row_id}'>
								<td>
									<input type='text' name='bill[item_no][]' value='{$detail['item_no']}' id='item_no_{$row_id}' class='item_no validate[required]' data-id='{$row_id}' style='width:80px;'>
									<input type='hidden' value='{$row_id}' name='row_number' class='row_number'>
								</td>

								<td>
									<input type='hidden' name='bill[description][]' value='{$detail['description']}'>
									{$this->ERPfunction->get_category_title($detail['description'])}
								</td>

								<td>
									<input type='text' name='bill[unit][]' readonly='true' id='unit_{$row_id}' value='{$detail['unit']}' class='unit validate[required]' data-id='{$row_id}' style='width:80px;'>
								</td>

								<td> 
									<input type='text' name='bill[quantity_this_bill][]' value='0' id='quantity_this_bill_{$row_id}' class='quantity_this_bill validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
								</td>

								<td>
									<input type='text' name='bill[quantity_previous_bill][]' readonly='true' value='{$detail['quantity_till_date']}' id='quantity_previous_bill_{$row_id}' class='quantity_previous_bill validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value='0'>
								</td>

								<td>
									<input type='text' name='bill[quantity_till_date][]' readonly='true' value='{$detail['quantity_till_date']}' id='quantity_till_date_{$row_id}' class='quantity_till_date validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
								</td>

								<td>
									<input type='number' min='1' name='bill[rate][]' value='{$detail['rate']}' id='rate_{$row_id}' class='rate validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
								</td>
								
								<td>
									<input type='number' min='1' name='bill[full_rate][]' value='{$detail['full_rate']}' id='full_rate_{$row_id}' class='full_rate validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
								</td>

								<td> 
									<input type='text' name='bill[amount_this_bill][]' readonly='true' value='' id='amount_this_bill_{$row_id}' class='amount_this_bill validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
								</td>

								<td>
									<input type='text' name='bill[amount_previous_bill][]' readonly='true' value='{$detail['amount_till_date']}' id='amount_previous_bill_{$row_id}' class='amount_previous_bill validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value='0'>
								</td>

								<td>
									<input type='text' name='bill[amount_till_date][]' readonly='true' value='{$detail['amount_till_date']}' id='amount_till_date_{$row_id}' class='amount_till_date validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
								</td>

								<td>
									<a href='javascript:void(0)' class='btn btn-danger del_parent'>Delete</a>
								</td>
							</tr>";
							$row_id++;
				}
				
			}
			$array = array();
			$array['rows'] = $html;
			$array['type_of_work'] = $data->type_of_work;
			$array['debit_till_date'] = $debit_till_date;
			$array['reconciliation_till_date'] = $reconciliation_till_date;
			echo json_encode($array);
		}
	}

	public function loadplanningworecords()
	{
		$this->autoRender = false;
		$row_id = $_REQUEST['row_id'];
		$project_id = $_REQUEST['project_id'];
		$party_id = $_REQUEST['party_id'];
		
		$wo_table = TableRegistry::get('erp_planning_work_order');
		$wod_table = TableRegistry::get('erp_planning_work_order_detail');
		
		$data = $wo_table->find()->where(["project_id"=>$project_id,"party_userid"=>$party_id,'ammend_approve'=>1])->order(["wo_id"=>"desc"])->first();;
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		
		$bill_mode = '';
		$debit_this_bill = 0;
		$reconciliation_this_bill = 0;
		$debit_till_date = 0;
		$reconciliation_till_date = 0;
		$this_bill_amount = 0;
		$billdata = $erp_sub_contract->find()->where(["project_id"=>$project_id,"party_id"=>$party_id,"approval"=>1])->order(["id"=>"desc"])->first();
		if(!empty($billdata))
		{
			$debit_this_bill = $billdata->debit_this_bill;
			$reconciliation_this_bill = $billdata->reconciliation_this_bill;
			$this_bill_amount = $billdata->this_bill_amount;
			$debit_till_date = $billdata->debit_till_date;
			$reconciliation_till_date = $billdata->reconciliation_till_date;
			$contract_id = $billdata->id;
			$billdetail_data = $erp_sub_contract_detail->find()->where(["sub_contract_id"=>$contract_id,"approval"=>1])->order("item_no","ASC")->hydrate(false)->toArray();
		}

		$html = "";
		if(!empty($data))
		{
			$table_category=TableRegistry::get('erp_category_master');
			$description_options=$table_category->find()->where(array('type'=>'subcontractbill_option'));
			$this->set('description_options',$description_options);
			
			$bill_mode = $data->bill_mode;
			$wo_id = $data->wo_id;		
			$detail_data = $wod_table->find()->where(["wo_id"=>$wo_id,"approved"=>1])->order("contract_no","ASC")->hydrate(false)->toArray();
			
			if(!empty($detail_data))
			{
				$i = 0;
				foreach($detail_data as $detail)
				{
					$html .= "<tr id='row_id_{$row_id}'>
					<td>
						<input type='text' name='bill[item_no][]' readonly='true' value='{$detail['contract_no']}' id='item_no_{$row_id}' class='item_no validate[required]' data-id='{$row_id}' style='width:80px;'>
						<input type='hidden' value='{$row_id}' name='row_number' class='row_number'>
					</td>

					<td>
						<input type='hidden' name='bill[description][]' value='{$detail['material_name']}'>
						{$this->ERPfunction->get_category_title($detail['material_name'])}
					</td>

					<td>
						<input type='text' name='bill[unit][]'  id='unit_{$row_id}' readonly='true' value='{$detail['unit']}' class='unit validate[required]' data-id='{$row_id}' style='width:80px;'>
					</td>

					<td> 
						<input type='text' name='bill[quantity_this_bill][]' value='0' id='quantity_this_bill_{$row_id}' class='quantity_this_bill validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
					</td>

					<td>
						<input type='text' name='bill[quantity_previous_bill][]' readonly='true' value='".((isset($billdetail_data[$i]['quantity_till_date']))?$billdetail_data[$i]['quantity_till_date']:0)."' id='quantity_previous_bill_{$row_id}' class='quantity_previous_bill validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;'>
					</td>

					<td>
						<input type='text' name='bill[quantity_till_date][]' readonly='true' value='".((isset($billdetail_data[$i]['quantity_till_date']))?$billdetail_data[$i]['quantity_till_date']:0)."' id='quantity_till_date_{$row_id}' class='quantity_till_date validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
					</td>

					<td>
						<input type='text' name='bill[wo_quantity][]' readonly='true' id='wo_quantity_{$row_id}' class='wo_quantity' data-id='{$row_id}' style='width:80px;' value='{$detail['till_date_quantity']}'>
					</td>

					<td>
						<input type='number' min='1' name='bill[rate][]' value='".((isset($billdetail_data[$i]['rate']))?$billdetail_data[$i]['rate']:'')."' id='rate_{$row_id}' class='rate validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;'>
					</td>
					
					<td>
						<input type='number' min='1' name='bill[full_rate][]' readonly='true' value='{$detail['unit_rate']}' id='full_rate_{$row_id}' class='full_rate validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
					</td>

					<td class='labour_with_material_hide'> 
						<input type='text' name='bill[amount_this_bill][]'  value='' id='amount_this_bill_{$row_id}' class='amount_this_bill validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
					</td>

					<td class='labour_with_material_hide'>
						<input type='text' name='bill[amount_previous_bill][]' readonly='true' value='".((isset($billdetail_data[$i]['amount_till_date']))?$billdetail_data[$i]['amount_till_date']:0)."' id='amount_previous_bill_{$row_id}' class='amount_previous_bill validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value='0'>
					</td>

					<td>
						<input type='text' name='bill[amount_till_date][]' readonly='true' value='".((isset($billdetail_data[$i]['amount_till_date']))?$billdetail_data[$i]['amount_till_date']:0)."' id='amount_till_date_{$row_id}' class='amount_till_date validate[required,custom[number]]' data-id='{$row_id}' style='width:80px;' value=''>
					</td>

					<td>
						<a href='javascript:void(0)' class='btn btn-danger del_parent'>Delete</a>
					</td>
				</tr>";
							$row_id++;
							$i++;
				}
				
			}
			$array = array();
			$array['rows'] = $html;
			$array['contract_type'] = $this->ERPfunction->get_contract_title($data->contract_type);
			$array['type_of_work'] = $data->type_of_work;
			$array['igst_percentage'] = $data->igst_percentage;
			$array['cgst_percentage'] = $data->cgst_percentage;
			$array['sgst_percentage'] = $data->sgst_percentage;
			$array['this_bill_amount'] = $this_bill_amount;
			$array['debit_till_date'] = $debit_till_date;
			$array['reconciliation_till_date'] = $reconciliation_till_date;
			$array['debit_this_bill'] = $debit_this_bill;
			$array['reconciliation_this_bill'] = $reconciliation_this_bill;
			$array['bill_mode'] = $bill_mode;
			echo json_encode($array);
			die;
		}else{
			echo 'empty';
			die;
		}
	}
	
	public function firstapprovesubcontract()
	{
		$this->autoRender = false;
		$id = $_REQUEST['id'];
		
		$user = $this->request->session()->read('user_id');
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		
		$row = $erp_sub_contract->get($id);
		$row->first_approval = 1;
		$row->first_approval_by = $user;
		$row->first_approval_date = date("Y-m-d");
		if($erp_sub_contract->save($row))
		{
			$date=date('Y-m-d');
			$query = $erp_sub_contract_detail->query();
			$approve = $query->update()
						->set(['first_approve'=>1,
						"first_approve_date"=>$date,
						'first_approve_by'=>$user])
						->where(['sub_contract_id' => $id])
						->execute();
		}
	}
	
	public function approvesubcontract()
	{
		$this->autoRender = false;
		$id = $_REQUEST['id'];
		
		$user = $this->request->session()->read('user_id');
		$erp_sub_contract = TableRegistry::get('erp_sub_contract');
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		
		$row = $erp_sub_contract->get($id);
		$row->approval = 1;
		$row->approval_by = $user;
		$row->approval_date = date("Y-m-d");
		if($erp_sub_contract->save($row))
		{
			$date=date('Y-m-d');
			$query = $erp_sub_contract_detail->query();
			$approve = $query->update()
						->set(['approval'=>1,
						"approval_date"=>$date,
						'approval_by'=>$user])
						->where(['sub_contract_id' => $id])
						->execute();
		}
	}
	
	public function deletesubcontractdetail()
	{
		$this->autoRender = false ;
		$detail_id = $_REQUEST['detail_id'];
		
		$erp_sub_contract_detail = TableRegistry::get('erp_sub_contract_detail');
		$row =$erp_sub_contract_detail->get($detail_id);
		$erp_sub_contract_detail->delete($row);
		die;
	}
	
	public function checkSameMonthDesignationHistory()
	{
		$this->autoRender = false;
		$user_id = $this->request->data["employee_id"];
		$change_date = $this->request->data["date"];
		
		$modified_date = date("Y-m",strtotime($change_date));
		$modified_date = $modified_date."-01";
			
		$tbl = TableRegistry::get("erp_designation_history");
		
		$history = $tbl->find()->where(["user_id"=>$user_id,"change_date"=>$modified_date])->count();
		if($history)
		{
			echo "true";
		}else{
			echo "false";
		}
	}
	
	public function designationhistory()
	{
		$user_id = $this->request->data["user_id"];
		$user_tbl = TableRegistry::get("erp_users");
		$tbl = TableRegistry::get("erp_designation_history");
		
		$user_data = $user_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		$history = $tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		$this->set("user_data",$user_data[0]);
		$this->set("history",$history);
		$this->set("user_id",$user_id);
		
	}
	
	
	public function salarystatement()
	{
		$user_id = $this->request->data["user_id"];
		$this->set("user_id",$user_id);
	}
	
	public function loadmaterial()
	{		
		$erp_material = TableRegistry::get('erp_material');
		if($this->role == "deputymanagerelectric")
		{
			$meterial_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$meterial_ids = json_decode($meterial_ids);
			$material_data = $erp_material->find()->select(['material_id','material_title'])->where(["material_id IN"=>$meterial_ids]);
		}else{
			$material_data = $erp_material->find()->select(['material_id','material_title']);
		}
		
		$content = '';
		foreach($material_data as $retrive_data)
		{
			$content .= '<option value = "'.$retrive_data['material_id'].'">'.$retrive_data['material_title'].'</option>';
		}
		echo $content;
		die;
	}
	
	public function loaduserprojects()
	{	
		if($this->role == "deputymanagerelectric")
		{
			$projects = $this->Usermanage->access_project_ongoing($this->user_id);
		}else{
			$projects = $this->Usermanage->access_project($this->user_id);
		}
		
		$content = '';
		foreach($projects as $retrive_data)
		{
			$content .= '<option value = "'.$retrive_data['project_id'].'">'.$retrive_data['project_name'].'</option>';
		}
		echo $content;
		die;
	}
	
	public function loadvendor()
	{		
		$users_table = TableRegistry::get('erp_vendor');
		$vendor_department = $users_table->find()->select('user_id');
		$content = '';
		foreach($vendor_department as $retrive_data)
		{
			$content .= '<option value = "'.$retrive_data['user_id'].'">'.$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';
		}
		echo $content;
		die;
	}
	
	public function loadstockledgermaterial()
	{		
		$erp_stock_tab = TableRegistry::get('erp_stock');
		$result_stockdata = $erp_stock_tab->find();
		$content = '';
		foreach($result_stockdata as $retrive_data)
		{
			if($retrive_data['material_id'] != 0)
			{
				$value = $retrive_data['material_id'];
				$name = $this->ERPfunction->get_material_title($retrive_data['material_id']);
			}
			else
			{
				$value = $retrive_data['material_name'];
				$name = $retrive_data['material_name'];
			}
			$content .= '<option value="'.$value.'" '.(($material_id == $value)?"selected":"").'>'.$name.'</option>';
		}
		echo $content;
		die;
	}
	
	public function loadagency()
	{		
		$agency_tbl = TableRegistry::get("erp_agency");
		$agency_list = $agency_tbl->find("All")->toArray();
		$content = '';
		foreach($agency_list as $retrive_data)
		{
			$content .= '<option value="'.$retrive_data['id'].'">'.
			$retrive_data['agency_name'].'</option>';
		}
		echo $content;
		die;
	}
	
	public function viewgrndata()
	{  
		// DB table to use
		$table = 'erp_inventory_grn';
		// Table's primary key
		$primaryKey = 'grn_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'grn.grn_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'grn.project_id', 'dt' => 0 , 'field' => 'project_id' ),
			array( 'db' => 'grn.grn_no',  'dt' => 1, 'field' => 'grn_no' ),
			array( 'db' => 'grn.grn_date',   'dt' => 2, 'field' => 'grn_date'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'grn.challan_no',  'dt' => 4, 'field' => 'challan_no' ),
			array( 'db' => 'grn_detail.is_static',   'dt' => 5, 'field' => 'is_static'),
			array( 'db' => 'grn_detail.is_static',   'dt' => 6, 'field' => 'is_static'),
			array( 'db' => 'material.material_code',   'dt' => 7, 'field' => 'material_code'),
			array( 'db' => 'grn_detail.quantity',   'dt' => 8, 'field' => 'quantity'),
			array( 'db' => 'grn_detail.actual_qty',   'dt' => 9, 'field' => 'actual_qty'),
			array( 'db' => 'grn_detail.difference_qty',   'dt' => 10, 'field' => 'difference_qty'),
			array( 'db' => 'grn.attach_file',   'dt' => 11, 'field' => 'attach_file'),
			array( 'db' => 'grn.grn_id',   'dt' => 12, 'field' => 'grn_id'),
			array( 'db' => 'grn_detail.material_name',   'dt' => 13, 'field' => 'material_name'),
			array( 'db' => 'grn_detail.brand_name',   'dt' => 14, 'field' => 'brand_name'),
			array( 'db' => 'material.material_title',   'dt' => 15, 'field' => 'material_title'),
			array( 'db' => 'grn.attach_label',   'dt' => 16, 'field' => 'attach_label'),
			array( 'db' => 'grn_detail.brand_id',   'dt' => 17, 'field' => 'brand_id'),
			array( 'db' => 'grn.payment_method',   'dt' => 18, 'field' => 'payment_method'),
			array( 'db' => 'grn.po_id',   'dt' => 19, 'field' => 'po_id'),
			array( 'db' => 'grn_detail.grndetail_id',   'dt' => 20, 'field' => 'grndetail_id'),
			array( 'db' => 'grn.changes_status',   'dt' => 21, 'field' => 'changes_status'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_grn();
		
		$joinQuery = "{$table} AS grn 
		LEFT JOIN erp_inventory_grn_detail AS grn_detail ON grn.grn_id = grn_detail.grn_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = grn.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = grn_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function viewisdata()
	{
		require_once(ROOT . DS .'vendor' . DS  . 'is' . DS . 'viewis_load_class.php');
		// $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		// require_once( $parse_uri[0] . 'wp-load.php' );  
		// DB table to use
		$table = 'erp_inventory_is';
		// Table's primary key
		$primaryKey = 'is_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'erp_is.is_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'erp_is.project_id', 'dt' => 0 , 'field' => 'project_id' ),
			array( 'db' => 'erp_is.is_no',  'dt' => 1, 'field' => 'is_no' ),
			array( 'db' => 'erp_is.agency_name',   'dt' => 2, 'field' => 'agency_name'),
			array( 'db' => 'erp_is.is_date',  'dt' => 3, 'field' => 'is_date'),
			array( 'db' => 'material.material_title',   'dt' => 4, 'field' => 'material_title'),
			array( 'db' => 'is_detail.quantity',   'dt' => 5, 'field' => 'quantity'),
			array( 'db' => 'is_detail.material_id',   'dt' => 6, 'field' => 'material_id'),
			array( 'db' => 'is_detail.name_of_foreman',   'dt' => 7, 'field' => 'name_of_foreman'),
			array( 'db' => 'erp_is.is_id',   'dt' => 8, 'field' => 'is_id'),
			array( 'db' => 'is_detail.is_detail_id',   'dt' => 9, 'field' => 'is_detail_id'),
			array( 'db' => 'erp_is.changes_status',   'dt' => 10, 'field' => 'changes_status')

		);
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_is();
		
		$joinQuery = "{$table} AS erp_is 
		LEFT JOIN erp_inventory_is_detail AS is_detail ON erp_is.is_id = is_detail.is_id
		LEFT JOIN erp_material AS material ON material.material_id = is_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function inventoryrecords()
	{
		require_once(ROOT . DS .'vendor' . DS  . 'inventoryrecords' . DS . 'viewrecords_load_class.php');
		// $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		// require_once( $parse_uri[0] . 'wp-load.php' );  
		// DB table to use
		$table = 'erp_stock_history';
		// Table's primary key
		$primaryKey = 'stock_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'stock_history.stock_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'project.project_name', 'dt' => 0 , 'field' => 'project_name' ),
			array( 'db' => 'stock_history.material_id',  'dt' => 1, 'field' => 'material_id' ),
			array( 'db' => 'stock_history.material_id',   'dt' => 2, 'field' => 'material_id'),
			array( 'db' => 'material.consume',   'dt' => 3, 'field' => 'consume'),
			array( 'db' => 'material.cost_group',   'dt' => 4, 'field' => 'cost_group'),
			array( 'db' => 'stock_history.max_quantity',  'dt' => 5, 'field' => 'max_quantity'),
			array( 'db' => 'SUM(stock_history.stock_in) as total_stock_in',   'dt' => 6, 'field' => 'total_stock_in'),
			array( 'db' => 'stock_history.max_quantity',   'dt' => 7, 'field' => 'max_quantity'),
			array( 'db' => 'SUM(stock_history.stock_out) as total_stock_out',   'dt' => 8, 'field' => 'total_stock_out'),
			array( 'db' => 'stock_history.project_id',   'dt' => 9, 'field' => 'project_id'),
			array( 'db' => 'stock_history.project_id',   'dt' => 10, 'field' => 'project_id'),
			array( 'db' => 'stock_history.min_quantity',   'dt' => 11, 'field' => 'min_quantity'),
			array( 'db' => 'stock_history.material_id',   'dt' => 12, 'field' => 'material_id'),
			array( 'db' => 'stock_history.material_id',   'dt' => 13, 'field' => 'material_id'),
			array( 'db' => 'material.material_title',   'dt' => 14, 'field' => 'material_title'),
			array( 'db' => 'stock_history.material_name',   'dt' => 15, 'field' => 'material_name')

		);
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_records();
		
		$joinQuery = "{$table} AS stock_history
		LEFT JOIN erp_projects AS project ON project.project_id = stock_history.project_id
		LEFT JOIN erp_material AS material ON material.material_id = stock_history.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function inventoryurgentrequirment()
	{
		require_once(ROOT . DS .'vendor' . DS  . 'inventoryurgentrequirment' . DS . 'minstock_load_class.php');
		// $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		// require_once( $parse_uri[0] . 'wp-load.php' );  
		// DB table to use
		$table = 'erp_stock_history';
		// Table's primary key
		$primaryKey = 'stock_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'stock_history.stock_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'project.project_name', 'dt' => 0 , 'field' => 'project_name' ),
			array( 'db' => 'stock_history.material_id',  'dt' => 1, 'field' => 'material_id' ),
			array( 'db' => 'stock_history.material_id',   'dt' => 2, 'field' => 'material_id'),
			array( 'db' => 'material.consume',   'dt' => 3, 'field' => 'consume'),
			array( 'db' => 'material.cost_group',   'dt' => 4, 'field' => 'cost_group'),
			array( 'db' => 'stock_history.max_quantity',  'dt' => 5, 'field' => 'max_quantity'),
			array( 'db' => 'SUM(stock_history.stock_in) as total_stock_in',   'dt' => 6, 'field' => 'total_stock_in'),
			array( 'db' => 'stock_history.max_quantity',   'dt' => 7, 'field' => 'max_quantity'),
			array( 'db' => 'SUM(stock_history.stock_out) as total_stock_out',   'dt' => 8, 'field' => 'total_stock_out'),
			array( 'db' => 'stock_history.project_id',   'dt' => 9, 'field' => 'project_id'),
			array( 'db' => 'stock_history.project_id',   'dt' => 10, 'field' => 'project_id'),
			array( 'db' => 'stock_history.min_quantity',   'dt' => 11, 'field' => 'min_quantity'),
			array( 'db' => 'stock_history.material_id',   'dt' => 12, 'field' => 'material_id'),
			array( 'db' => 'stock_history.material_id',   'dt' => 13, 'field' => 'material_id'),
			array( 'db' => 'material.material_title',   'dt' => 14, 'field' => 'material_title'),
			array( 'db' => 'stock_history.material_name',   'dt' => 15, 'field' => 'material_name')

		);
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_records();
		
		$joinQuery = "{$table} AS stock_history
		LEFT JOIN erp_projects AS project ON project.project_id = stock_history.project_id
		LEFT JOIN erp_material AS material ON material.material_id = stock_history.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function inventoryoverpurchasedstock()
	{
		require_once(ROOT . DS .'vendor' . DS  . 'inventoryoverpurchasedstock' . DS . 'maxstock_load_class.php');
		// $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		// require_once( $parse_uri[0] . 'wp-load.php' );  
		// DB table to use
		$table = 'erp_stock_history';
		// Table's primary key
		$primaryKey = 'stock_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'stock_history.stock_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'project.project_name', 'dt' => 0 , 'field' => 'project_name' ),
			array( 'db' => 'stock_history.material_id',  'dt' => 1, 'field' => 'material_id' ),
			array( 'db' => 'stock_history.material_id',   'dt' => 2, 'field' => 'material_id'),
			array( 'db' => 'material.consume',   'dt' => 3, 'field' => 'consume'),
			array( 'db' => 'material.cost_group',   'dt' => 4, 'field' => 'cost_group'),
			array( 'db' => 'stock_history.max_quantity',  'dt' => 5, 'field' => 'max_quantity'),
			array( 'db' => 'SUM(stock_history.stock_in) as total_stock_in',   'dt' => 6, 'field' => 'total_stock_in'),
			array( 'db' => 'stock_history.max_quantity',   'dt' => 7, 'field' => 'max_quantity'),
			array( 'db' => 'SUM(stock_history.stock_out) as total_stock_out',   'dt' => 8, 'field' => 'total_stock_out'),
			array( 'db' => 'stock_history.project_id',   'dt' => 9, 'field' => 'project_id'),
			array( 'db' => 'stock_history.project_id',   'dt' => 10, 'field' => 'project_id'),
			array( 'db' => 'stock_history.min_quantity',   'dt' => 11, 'field' => 'min_quantity'),
			array( 'db' => 'stock_history.material_id',   'dt' => 12, 'field' => 'material_id'),
			array( 'db' => 'stock_history.material_id',   'dt' => 13, 'field' => 'material_id'),
			array( 'db' => 'material.material_title',   'dt' => 14, 'field' => 'material_title'),
			array( 'db' => 'stock_history.material_name',   'dt' => 15, 'field' => 'material_name')

		);
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_records();
		
		$joinQuery = "{$table} AS stock_history
		LEFT JOIN erp_projects AS project ON project.project_id = stock_history.project_id
		LEFT JOIN erp_material AS material ON material.material_id = stock_history.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function projectwisematerialdetail()
	{
		$project_id = $_REQUEST['project_id'];
		$material_id = $_REQUEST['material_id'];
		$erp_stock_history = TableRegistry::get("erp_stock_history");
		if(is_numeric($material_id))
		{
			$result = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$result = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		$current_stock = bcdiv($this->ERPfunction->get_current_stock($project_id,$material_id),1,3);
		$return['current_stock'] = $current_stock;
		if(!empty($result))
		{
			$return['min_stock_level'] = $result[0]['min_quantity'];
		}else{
			$return['min_stock_level'] = '';
		}
		
		echo json_encode($return);die;
	}
	
	public function checkiselectricmaterial()
	{
		$this->autoRender = false;
		$materials = $_REQUEST['materials'];
		$materials = array_filter($materials, function($value) { return $value !== ''; });
		$materials = array_unique($materials); 
		$erp_material = TableRegistry::get("erp_material");
		foreach($materials as $material)
		{
			if(is_numeric($material))
			{
				$row = $erp_material->get($material);
				if(in_array($row->material_code,['6','7','10']))
				{
					echo "1";die;
				}
			}
		}
		echo "0";
	}
	
	public function temporaryempdetail()
	{
		$temp_emp_id = $_REQUEST['temp_emp_id'];
		
		$result_arr['party_id'] = '';			
		$result_arr['address'] = '';		
		$result_arr['delivery_place'] = '';		
		$result_arr['contact_no1'] = '';		
		$result_arr['contact_no2'] = '';		
		$result_arr['email_id'] = '';		
		$result_arr['pancard_no'] = '';		
		$result_arr['gst_no'] = '';
		
		$erp_user = TableRegistry::get('erp_users'); 
		$user_data = $erp_user->get($temp_emp_id);
			
		if(!empty($user_data))
		{
			$result_arr['party_id'] = $user_data->employee_no;			
			$result_arr['address'] = '';			
			$result_arr['delivery_place'] = $user_data->employee_address;		
			$result_arr['contact_no1'] = $user_data->mobile_no;		
			$result_arr['contact_no2'] = '';		
			$result_arr['email_id'] = '';		
			$result_arr['pancard_no'] = $user_data->pan_card_no;		
			$result_arr['gst_no'] = '';		
		}
		
		echo json_encode($result_arr);
		die();
	}
	
	public function getassetrecords()
	{
		$asset_tbl = TableRegistry::get('erp_assets'); 
		$assets = $asset_tbl->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->hydrate(false)->toArray();
		$options = "";
		if(!empty($assets))
		{
			foreach($assets as $key=>$value)
			{
				$options .= "<option value='asst_{$key}'>{$value}</option>";
			}
		}
		echo $options;die;
	}
	
	public function checkassetreturn()
	{
		$asset_id = $_REQUEST['asset_id'];
		$erp_asset_issued_history = TableRegistry::get("erp_asset_issued_history");
		$count = $erp_asset_issued_history->find()->where(["asset_id"=>$asset_id,"return_date IS"=> null])->count();
		echo $count;die;
	}
	
	public function returnassetdate()
	{
		$asset_id = $_REQUEST['asset_id'];
		$this->set("asset_id",$asset_id);
	}
	
	public function updateassetreturndate()
	{
		$asset_id = $_REQUEST['return_asset_id'];
		$return_date = $_REQUEST['return_date'];
		$erp_asset_issued_history = TableRegistry::get("erp_asset_issued_history");
		$data = $erp_asset_issued_history->find()->where(["asset_id"=>$asset_id,"return_date IS"=> null])->first();
		
		if(!empty($data)){
			$this->autoRender = false;
			$row = $erp_asset_issued_history->get($data->id);
			$row->return_date = date("Y-m-d",strtotime($return_date));
			if($erp_asset_issued_history->save($row)){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
	}
	
	public function updateissuedasset()
	{	
		$asset_id = $_REQUEST['asset_id'];
		$history_record_id = $_REQUEST['history_record_id'];
		$erp_asset_issued_history = TableRegistry::get('erp_asset_issued_history'); 
		$history_data = $erp_asset_issued_history->get($history_record_id);
		
		$erp_projects = TableRegistry::get('erp_projects');
		$projects = $erp_projects->find();
		
		
		
		$this->set('projects',$projects);
		$this->set('asset_id',$asset_id);		
		$this->set('history_data',$history_data);		
		$this->set('history_record_id',$history_record_id);		
	}
	
	public function assetmaintenancerecords()
	{  
		// DB table to use
		$table = 'erp_assets_maintenance';
		// Table's primary key
		$primaryKey = 'maintenace_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'maintenance.maintenace_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'maintenance.project_id', 'dt' => 0 , 'field' => 'project_id' ),
			array( 'db' => 'maintenance.maintenance_date',  'dt' => 1, 'field' => 'maintenance_date' ),
			array( 'db' => 'maintenance.amo_no',   'dt' => 2, 'field' => 'amo_no'),
			array( 'db' => 'maintenance.asset_group',  'dt' => 3, 'field' => 'asset_group'),
			array( 'db' => 'erp_assets.asset_code',  'dt' => 4, 'field' => 'asset_code' ),
			array( 'db' => 'erp_assets.asset_name',   'dt' => 5, 'field' => 'asset_name'),
			array( 'db' => 'erp_assets.capacity',   'dt' => 6, 'field' => 'capacity'),
			array( 'db' => 'maintenance.vehicle_no',   'dt' => 7, 'field' => 'vehicle_no'),
			array( 'db' => 'maintenance.maintenance_type',   'dt' => 8, 'field' => 'maintenance_type'),
			array( 'db' => 'maintenance.expense_amount',   'dt' => 9, 'field' => 'expense_amount'),
			array( 'db' => 'maintenance.payment_by',   'dt' => 10, 'field' => 'payment_by'),
			array( 'db' => 'maintenance.maintenace_id',   'dt' => 11, 'field' => 'maintenace_id'),
		);
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_maintenance();
		
		$joinQuery = "{$table} AS maintenance 
		LEFT JOIN erp_assets AS erp_assets ON maintenance.asset_id = erp_assets.asset_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function loadasset()
	{
		$asset_table = TableRegistry::get('erp_assets');
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($role =='projectdirector' || $role =='projectcoordinator' || $role =='planningmanager' || $role =='siteaccountant' || $role =='constructionmanager' || $role =='billingengineer' || $role =='planningengineer' || $role =='materialmanager' || $role =='erpoperator')
		{
			if(!empty($projects_ids)){
						$asset_name = $asset_table->find()->where(["deployed_to IN"=>$projects_ids])->select(["asset_id","asset_name"])->toArray();	
			}else{
				$asset_data=array();
			}
		}else{
			$asset_data = $asset_table->find()->select(["asset_id","asset_name"])->toArray();
		}
		
		$content = '';
		foreach($asset_data as $retrive_data)
		{
			$content .= '<option value = "'.$retrive_data['asset_id'].'">'.$retrive_data['asset_name'].'</option>';
		}
		echo $content;die;
	}
	
	public function loadassetissuestore()
	{
		$asset_table = TableRegistry::get('erp_assets');
		$role = $this->Usermanage->get_user_role($this->user_id);
		if($role =='projectdirector' || $role =='projectcoordinator' || $role =='planningmanager' || $role =='siteaccountant' || $role =='constructionmanager' || $role =='billingengineer' || $role =='planningengineer' || $role =='materialmanager' || $role =='erpoperator')
		{
			if(!empty($projects_ids)){
						$asset_name = $asset_table->find()->where(["deployed_to IN"=>$projects_ids])->select(["asset_id","asset_name"])->toArray();	
			}else{
				$asset_data=array();
			}
		}else{
			$asset_data = $asset_table->find()->select(["asset_id","asset_name"])->toArray();
		}
		
		$content = '';
		foreach($asset_data as $retrive_data)
		{
			$content .= '<option value = "'."asst_".$retrive_data['asset_id'].'">'.$retrive_data['asset_name'].'</option>';
		}
		echo $content;die;
	}
	
	public function equipmentlogownrecords()
	{  
		// DB table to use
		$table = 'erp_equipmentown_log';
		// Table's primary key
		$primaryKey = 'id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'equipment.id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'equipment.date', 'dt' => 0 , 'field' => 'date' ),
			array( 'db' => 'equipment.el_no',  'dt' => 1, 'field' => 'el_no' ),
			array( 'db' => 'erp_assets.asset_name',   'dt' => 2, 'field' => 'asset_name'),
			array( 'db' => 'equipment.asset_identity',  'dt' => 3, 'field' => 'asset_identity'),
			array( 'db' => 'equipment.working_status',  'dt' => 4, 'field' => 'working_status' ),
			array( 'db' => 'equipment.usage_km',   'dt' => 5, 'field' => 'usage_km'),
			array( 'db' => 'equipment.usage_hr',   'dt' => 6, 'field' => 'usage_hr'),
			array( 'db' => 'equipment.driver_name',   'dt' => 7, 'field' => 'driver_name'),
			array( 'db' => 'equipment.id',   'dt' => 8, 'field' => 'id'),
		);
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_equipmentlogown();
		
		$joinQuery = "{$table} AS equipment 
		LEFT JOIN erp_assets AS erp_assets ON equipment.asset_id = erp_assets.asset_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function viewstoreissue()
	{
		require_once(ROOT . DS .'vendor' . DS  . 'storeissue' . DS . 'viewstore_issue_load_class.php');
		// $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		// require_once( $parse_uri[0] . 'wp-load.php' );  
		// DB table to use
		$table = 'erp_inventory_is';
		// Table's primary key
		$primaryKey = 'is_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'erp_is.is_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'erp_is.is_date', 'dt' => 0 , 'field' => 'is_date' ),
			array( 'db' => 'erp_is.agency_name',  'dt' => 1, 'field' => 'agency_name' ),
			array( 'db' => 'erp_is.agency_name',   'dt' => 2, 'field' => 'agency_name'),
			array( 'db' => 'erp_is.agency_name',  'dt' => 3, 'field' => 'agency_name'),
			array( 'db' => 'erp_is.agency_name',  'dt' => 4, 'field' => 'agency_name'),
			array( 'db' => 'material.material_title',   'dt' => 5, 'field' => 'material_title'),
			array( 'db' => 'is_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'is_detail.material_id',   'dt' => 7, 'field' => 'material_id'),
			array( 'db' => 'erp_is.is_id',   'dt' => 8, 'field' => 'is_id'),
		);
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_storeissue();
		
		$joinQuery = "{$table} AS erp_is 
		LEFT JOIN erp_inventory_is_detail AS is_detail ON erp_is.is_id = is_detail.is_id
		LEFT JOIN erp_material AS material ON material.material_id = is_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function updateassetissuedhistory()
	{
		$history_record_id = $_REQUEST["history_id"];
		$issued_date = date("Y-m-d",strtotime($_REQUEST["issue_date"]));
		$issued_to = $_REQUEST["issue_to"];
		$erp_asset_issued_history = TableRegistry::get('erp_asset_issued_history'); 
		$record = $erp_asset_issued_history->get($history_record_id);
		$record->issued_to = $issued_to;
		$record->issued_date = $issued_date;
		$record->updated_by = $this->user_id;
		$record->updated_date = date("Y-m-d");
		if($erp_asset_issued_history->save($record)){
			echo true;
		}else{
			echo false;
		}
		die;
	}
	
	public function deleteassetissuedhistory()
	{
		$history_record_id = $_REQUEST["history_record_id"];
		$erp_asset_issued_history = TableRegistry::get('erp_asset_issued_history'); 
		$record = $erp_asset_issued_history->get($history_record_id);
		if($erp_asset_issued_history->delete($record)){
			echo true;
		}else{
			echo false;
		}
		die;
	}
	
	public function assetbookinghistorylist()
	{	
		$asset_id = $_REQUEST['asset_id'];
		$role = $this->role;
		$erp_asset_booking_history = TableRegistry::get('erp_asset_booking_history');
		$bookingdata = $erp_asset_booking_history->find()->where(['asset_id'=>$asset_id])->order(["entry_date"=>"desc"])->hydrate(false)->toArray();
		$this->set('role',$role);
		$this->set('bookingdata',$bookingdata);
		$this->set('asset_id',$asset_id);  
	
	}

	public function efficiencyhistorylist()
	{
		$asset_id = $_REQUEST['asset_id'];
		
		$role = $this->role;

		$erp_equipmentown_log = TableRegistry::get('erp_equipmentown_log');
		
		$efficiencydata = $erp_equipmentown_log->find('all')->where(['asset_id'=>$asset_id]);
		
		$efficiencydata1 = $efficiencydata->select(['asset_id','date','total_usage_km' => $efficiencydata->func()->sum('usage_km'),'total_usage_hr' => $efficiencydata->func()->sum('usage_hr')])->group(['date' => 'MONTH(date)'])->hydrate(false)->toArray();

		
		
		

	/*	$erp_inventory_is = TableRegistry::get('erp_inventory_is'); 
		$erp_inventory_is_detail = TableRegistry::get('erp_inventory_is_detail');


		$result = $erp_inventory_is->find()->select($erp_inventory_is);
		$result = $result->innerjoin(
						["erp_inventory_is"=>"erp_inventory_is"],
						["erp_inventory_is_detail.is_id = erp_inventory_is.is_id "])->where(['	material_id'=>90])
						->select($erp_inventory_is)->hydrate(false)->toArray();
		debug($result);*/
		/*foreach ($efficiencydata1 as $row) {

			


			$asst_id = 'asst_'.$row['asset_id'];

			$month = date('m',strtotime($row['date']));
			
			$erp_inventory_data = $erp_inventory_is->find()->where(['is_date'=>$month])->hydrate(false)->toArray();
			


			# YNEC/AST/PL/000000002
		}die;*/

		//$efficiencydata = $erp_equipmentown_log->find('all')->where(['asset_id'=>$asset_id])group(['date' => 'MONTH(date)'])->hydrate(false)->toArray();
		
		/*$data_efficiency;
		foreach ($efficiencydata as $row) {
				$data_efficiency['usage_km'] += $row['usage_km'];
				$data_efficiency['usage_hr'] += $row['usage_hr'];

		}*/

		$this->set('role',$role);
		$this->set('efficiencydata',$efficiencydata1);
		$this->set('asset_id',$asset_id);
	}
	
	public function editassetbooking()
	{	
		$asset_id = $_REQUEST['asset_id'];
		$history_record_id = $_REQUEST['history_record_id'];
		$erp_asset_booking_history = TableRegistry::get('erp_asset_booking_history'); 
		$history_data = $erp_asset_booking_history->get($history_record_id);
		
		$erp_projects = TableRegistry::get('erp_projects');
		$projects = $erp_projects->find();
		
		$this->set('projects',$projects);
		$this->set('asset_id',$asset_id);		
		$this->set('history_data',$history_data);		
		$this->set('history_record_id',$history_record_id);		
	}
	
	public function updateassetbookinghistory()
	{
		$history_record_id = $_REQUEST["history_id"];
		$requirement_date = date("Y-m-d",strtotime($_REQUEST["requirement_date"]));
		$erp_asset_booking_history = TableRegistry::get('erp_asset_booking_history'); 
		$record = $erp_asset_booking_history->get($history_record_id);
		$record->requirment_date = $requirement_date;
		$record->updated_by = $this->user_id;
		$record->updated_date = date("Y-m-d");
		if($erp_asset_booking_history->save($record)){
			echo true;
		}else{
			echo false;
		}
		die;
	}
	
	public function deleteassetbookinghistory()
	{
		$history_record_id = $_REQUEST["history_record_id"];
		$erp_asset_booking_history = TableRegistry::get('erp_asset_booking_history'); 
		$record = $erp_asset_booking_history->get($history_record_id);
		if($erp_asset_booking_history->delete($record)){
			echo true;
		}else{
			echo false;
		}
		die;
	}
	
	public function viewprrecords()
	{  
		// DB table to use
		$table = 'erp_inventory_purhcase_request';
		// Table's primary key
		$primaryKey = 'pr_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'purchase_request.pr_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'purchase_request.project_id', 'dt' => 0 , 'field' => 'project_id' ),
			array( 'db' => 'purchase_request.prno',  'dt' => 1, 'field' => 'prno' ),
			array( 'db' => 'purchase_request.pr_date',   'dt' => 2, 'field' => 'pr_date'),
			array( 'db' => 'purchase_request.attach_file',  'dt' => 3, 'field' => 'attach_file'),
			array( 'db' => 'purchase_request.pr_id',  'dt' => 4, 'field' => 'pr_id' ),
			array( 'db' => 'purchase_request.attach_label',   'dt' => 5, 'field' => 'attach_label'),
		);
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_pr();
		
		$joinQuery = "{$table} AS purchase_request 
		LEFT JOIN erp_inventory_pr_material AS pr_material ON purchase_request.pr_id = pr_material.pr_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function prpurchaseremark()
	{
		$pr_detail_id = $this->request->data["pr_detail_id"];
		$project_id = $this->request->data["project_id"];
				
		$erp_inventory_pr_material = TableRegistry::get('erp_inventory_pr_material'); 
		if(!empty($pr_detail_id))
		{
			$row = $erp_inventory_pr_material->get($pr_detail_id);
			$remark_data = $row->purchase_remarks;
		}
		else
		{
			$remark_data = "";
		}
		$this->set('pr_detail_id',$pr_detail_id);
		$this->set('project_id',$project_id);
		$this->set('remark_data',$remark_data);
		// var_dump($request_data);die;
	}

	public function viewratehistory() {
		$poDetailId = $this->request->data["pr_detail_id"];
		$materialId = $this->request->data["materialId"];
		$projectId = $this->request->data["project_id"];
		if(!empty($projectId) && !empty($poDetailId)){
			$erp_inventory_po = TableRegistry::get('erp_inventory_po');
			$erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail'); 

			$or = array();
			$or['erp_inventory_po.project_id IN'] =  $projectId;
			$or['erp_inventory_po_detail.material_id'] = $materialId;
			$or['erp_inventory_po_detail.approved ='] = 1;

			$keys = array_keys($or,"");				
			foreach ($keys as $k){
				unset($or[$k]);
			}
			$result = $erp_inventory_po->find()->select($erp_inventory_po);
			$result = $result->innerjoin(["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
				["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
				->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->hydrate(false)->toArray();
				// debug($result);die;
			$this->set("result",$result);
		}
	}
	
	public function prpurchasedoneremark()
	{
		$pr_detail_id = $this->request->data["pr_detail_id"];
		$project_id = $this->request->data["project_id"];
		
		$this->set('project_id',$project_id);
		$this->set('pr_detail_id',$pr_detail_id);
	}

	public function poammendrecords()
	{  
		// DB table to use
		$table = 'erp_inventory_po';
		// Table's primary key
		$primaryKey = 'po_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'po.po_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'po.po_no', 'dt' => 0 , 'field' => 'po_no' ),
			array( 'db' => 'po.po_date',  'dt' => 1, 'field' => 'po_date' ),
			array( 'db' => 'po.project_id',   'dt' => 2, 'field' => 'project_id'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'po_detail.material_id',  'dt' => 4, 'field' => 'material_id' ),
			array( 'db' => 'po_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'po_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'po_detail.material_id',   'dt' => 7, 'field' => 'material_id'),
			array( 'db' => 'po_detail.single_amount',   'dt' => 8, 'field' => 'single_amount'),
			array( 'db' => 'po_detail.amount',   'dt' => 9, 'field' => 'amount'),
			array( 'db' => 'po.po_purchase_type',   'dt' => 10, 'field' => 'po_purchase_type'),
			array( 'db' => 'po.po_id',   'dt' => 11, 'field' => 'po_id'),
			array( 'db' => 'po_detail.static_unit',   'dt' => 12, 'field' => 'static_unit'),
			array( 'db' => 'material.material_title',   'dt' => 13, 'field' => 'material_title'),
			array( 'db' => 'po.updated', 'dt' => 14, 'field' => 'updated'),
			array( 'db' => 'po.ammend_approve', 'dt'=> 15, 'field' => 'ammend_approve'),
			array( 'db' => 'po_detail.po_id', 'dt'=>16, 'field' => 'po_id'),
			array( 'db' => 'po_detail.id', 'dt' => 17, 'field' => 'id'),
		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';

		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_poammend();
		
		$joinQuery = "{$table} AS po 
		LEFT JOIN erp_inventory_po_detail AS po_detail ON po.po_id = po_detail.po_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = po.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = po_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function porecords()
	{  
		// DB table to use
		$table = 'erp_inventory_po';
		// Table's primary key
		$primaryKey = 'po_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'po.po_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'po.po_no', 'dt' => 0 , 'field' => 'po_no' ),
			array( 'db' => 'po.po_date',  'dt' => 1, 'field' => 'po_date' ),
			array( 'db' => 'po.project_id',   'dt' => 2, 'field' => 'project_id'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'po_detail.material_id',  'dt' => 4, 'field' => 'material_id' ),
			array( 'db' => 'po_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'po_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'po_detail.material_id',   'dt' => 7, 'field' => 'material_id'),
			array( 'db' => 'po_detail.single_amount',   'dt' => 8, 'field' => 'single_amount'),
			array( 'db' => 'po_detail.amount',   'dt' => 9, 'field' => 'amount'),
			array( 'db' => 'po.po_purchase_type',   'dt' => 10, 'field' => 'po_purchase_type'),
			array( 'db' => 'po.po_id',   'dt' => 11, 'field' => 'po_id'),
			array( 'db' => 'po_detail.static_unit',   'dt' => 12, 'field' => 'static_unit'),
			array( 'db' => 'material.material_title',   'dt' => 13, 'field' => 'material_title'),
			array( 'db' => 'po.updated', 'dt' => 14, 'field' => 'updated'),
			array( 'db' => 'po.ammend_approve', 'dt'=> 15, 'field' => 'ammend_approve'),
			array( 'db' => 'po_detail.po_id', 'dt'=>16, 'field' => 'po_id'),
			array( 'db' => 'po_detail.id', 'dt' => 17, 'field' => 'id'),
		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';

		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();
		


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_po();
		
		$joinQuery = "{$table} AS po 
		LEFT JOIN erp_inventory_po_detail AS po_detail ON po.po_id = po_detail.po_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = po.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = po_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function worecords()
	{  
		// DB table to use
		$table = 'erp_work_order';
		// Table's primary key
		$primaryKey = 'wo_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'wo.wo_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'wo.wo_date',  'dt' => 0, 'field' => 'wo_date' ),
			array( 'db' => 'wo.wo_no',  'dt' => 1, 'field' => 'wo_no' ),
			array( 'db' => 'project.project_name',   'dt' => 2, 'field' => 'project_name'),
			array( 'db' => 'wo.party_userid',  'dt' => 3, 'field' => 'party_userid'),
			array( 'db' => 'wo.contract_type',  'dt' => 4, 'field' => 'contract_type' ),
			array( 'db' => 'SUM(wo_detail.amount) as amount',   'dt' => 5, 'field' => 'amount'),
			array( 'db' => 'wo.wo_id',   'dt' => 6, 'field' => 'wo_id'),
			array( 'db' => 'agency.agency_name',   'dt' => 7, 'field' => 'agency_name'),
			array( 'db' => 'vendor.vendor_name',   'dt' => 8, 'field' => 'vendor_name'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_wo();
		
		$joinQuery = "{$table} AS wo 
		LEFT JOIN erp_work_order_detail AS wo_detail ON wo.wo_id = wo_detail.wo_id
		LEFT JOIN erp_projects AS project ON project.project_id = wo.project_id
		LEFT JOIN erp_agency AS agency ON agency.agency_id = wo.party_userid
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = wo.party_userid";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}

	public function planningammendedworecords()
	{  
		// DB table to use
		$table = 'erp_planning_work_order';
		// Table's primary key
		$primaryKey = 'wo_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'wo.wo_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'wo.wo_date',  'dt' => 0, 'field' => 'wo_date' ),
			array( 'db' => 'wo.wo_no',  'dt' => 1, 'field' => 'wo_no' ),
			array( 'db' => 'project.project_name',   'dt' => 2, 'field' => 'project_name'),
			array( 'db' => 'wo.party_userid',  'dt' => 3, 'field' => 'party_userid'),
			array( 'db' => 'wo.contract_type',  'dt' => 4, 'field' => 'contract_type' ),
			array( 'db' => 'wo.till_date_net_amount as amount',   'dt' => 5, 'field' => 'amount'),
			array( 'db' => 'wo.wo_id',   'dt' => 6, 'field' => 'wo_id'),
			array( 'db' => 'wo.updated',   'dt' => 7, 'field' => 'updated'),
			array( 'db' => 'agency.agency_name',   'dt' => 8, 'field' => 'agency_name'),
			array( 'db' => 'vendor.vendor_name',   'dt' => 9, 'field' => 'vendor_name'),
			array( 'db' => 'wo.ammend_approve',   'dt' => 10, 'field' => 'ammend_approve'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_pawo();
		
		$joinQuery = "{$table} AS wo 
		LEFT JOIN erp_planning_work_order_detail AS wo_detail ON wo.wo_id = wo_detail.wo_id
		LEFT JOIN erp_projects AS project ON project.project_id = wo.project_id
		LEFT JOIN erp_agency AS agency ON agency.agency_id = wo.party_userid
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = wo.party_userid";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}

	public function planningworecords()
	{  
		// DB table to use
		$table = 'erp_planning_work_order';
		// Table's primary key
		$primaryKey = 'wo_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'wo.wo_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'wo.wo_date',  'dt' => 0, 'field' => 'wo_date' ),
			array( 'db' => 'wo.wo_no',  'dt' => 1, 'field' => 'wo_no' ),
			array( 'db' => 'project.project_name',   'dt' => 2, 'field' => 'project_name'),
			array( 'db' => 'wo.party_userid',  'dt' => 3, 'field' => 'party_userid'),
			array( 'db' => 'wo.contract_type',  'dt' => 4, 'field' => 'contract_type' ),
			array( 'db' => 'wo.till_date_net_amount as amount',   'dt' => 5, 'field' => 'amount'),
			array( 'db' => 'wo.wo_id',   'dt' => 6, 'field' => 'wo_id'),
			array( 'db' => 'wo.updated',   'dt' => 7, 'field' => 'updated'),
			array( 'db' => 'agency.agency_name',   'dt' => 8, 'field' => 'agency_name'),
			array( 'db' => 'vendor.vendor_name',   'dt' => 9, 'field' => 'vendor_name'),
			array( 'db' => 'wo.ammend_approve',   'dt' => 10, 'field' => 'ammend_approve'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';

		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_pwo();
		
		$joinQuery = "{$table} AS wo 
		LEFT JOIN erp_planning_work_order_detail AS wo_detail ON wo.wo_id = wo_detail.wo_id
		LEFT JOIN erp_projects AS project ON project.project_id = wo.project_id
		LEFT JOIN erp_agency AS agency ON agency.agency_id = wo.party_userid
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = wo.party_userid";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function donegrnaudit()
	{
		$this->autoRender = false;
		$audit_id = $_REQUEST['audit_id'];
		$grn_no = $_REQUEST['grn_no'];
		// GRN Alert (Purchase / Accounts) show record code
		// debug($audit_id);
		// debug($grn_no);die;
		$grn = TableRegistry::get("erp_inventory_grn");	
		$query = $grn->query();
		$query->update()
			->set(['approved_status' => 1 ])
			->where(['grn_no' => $grn_no])
			->execute();
		// GRN Alert (Purchase / Accounts) show record code end

		$erp_audit_grn = TableRegistry::get("erp_audit_grn");
		$erp_audit_grn_detail = TableRegistry::get("erp_audit_grn_detail");
		
		$delete_ok = $erp_audit_grn_detail->deleteAll(["audit_id"=>$audit_id]);
		if($delete_ok)
		{
			$row = $erp_audit_grn->get($audit_id);
			$ok = $erp_audit_grn->delete($row);
			if($ok)
			{
				echo true;die;
			}else{
				echo false;die;
			}
		}
	}
	
	public function doneisaudit()
	{
		$this->autoRender = false;
		$audit_id = $_REQUEST['audit_id'];
		
		$erp_is_audit = TableRegistry::get("erp_is_audit");
		$erp_audit_is_detail = TableRegistry::get("erp_audit_is_detail");
		
		$delete_ok = $erp_audit_is_detail->deleteAll(["is_audit_id"=>$audit_id]);
		if($delete_ok)
		{
			$row = $erp_is_audit->get($audit_id);
			$ok = $erp_is_audit->delete($row);
			if($ok)
			{
				echo true;die;
			}else{
				echo false;die;
			}
		}
	}
	
	public function donerbnaudit()
	{
		$this->autoRender = false;
		$audit_id = $_REQUEST['audit_id'];
		
		$erp_audit_rbn = TableRegistry::get("erp_audit_rbn");
		$erp_audit_rbn_detail = TableRegistry::get("erp_audit_rbn_detail");
		
		$delete_ok = $erp_audit_rbn_detail->deleteAll(["audit_id"=>$audit_id]);
		if($delete_ok)
		{
			$row = $erp_audit_rbn->get($audit_id);
			$ok = $erp_audit_rbn->delete($row);
			if($ok)
			{
				echo true;die;
			}else{
				echo false;die;
			}
		}
	}
	
	public function inventorydebitnoteprojectdetail()
	{
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);		
		$result_arr = array();
		
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		
		$number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_debit_note","debit_id","debit_note_no");

		$new_no = sprintf("%09d", $number1);
		$debitno = 'YNEC/P/'.$result_arr['project_code'].'/DN/'.$new_no;
		$result_arr['debitno'] = $debitno;
		echo json_encode($result_arr);
		die();
	}
	
	public function getrbntilldatequantity()
	{
		$till_date_quantity = 0;
		$project_id = $_REQUEST['project_id'];
		$rbn_date = date("Y-m-d",strtotime($_REQUEST['rbn_date']));
		$party_id = $_REQUEST['party_id'];
		$material_id = $_REQUEST['material_id'];
		
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
							->where(["erp_inventory_rbn.agency_name"=>$party_id]);
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
		
		echo $till_date_quantity;die;
	}
	
	public function getmaterialstock()
	{
		$project_id = $_REQUEST['project_id'];
		$material_id = $_REQUEST['material_id'];
		$stock = $this->ERPfunction->getmaterialbrandlist($material_id,$project_id);
		echo $stock;die;
	}
	
	public function addmaterialsubgroup()
	{
		$material_code = $_REQUEST['material_code'];
		
		$erp_material_sub_group = TableRegistry::get('erp_material_sub_group');
		$subgroup_data = $erp_material_sub_group->find()->where(["material_group_id"=>$material_code])->hydrate(false)->toArray(); 
		$this->set('subgroup_data',$subgroup_data);
		$this->set('material_code',$material_code);
	}
	
	public function addqtycheckedby()
	{
		$erp_category_master = TableRegistry::get('erp_category_master');
		$checkedby_data = $erp_category_master->find()->where(["type"=>'qty_checkdeby'])->hydrate(false)->toArray(); 
		$this->set('checkedby_data',$checkedby_data);
	}
	
	public function addratecheckedby()
	{
		$erp_category_master = TableRegistry::get('erp_category_master');
		$checkedby_data = $erp_category_master->find()->where(["type"=>'rate_checkdeby'])->hydrate(false)->toArray(); 
		$this->set('checkedby_data',$checkedby_data);
	}
	
	public function savematerialsubgroup()
	{
		$result = array();
		$material_code = $_REQUEST['material_code_id'];
		$sub_category_value = $_REQUEST['sub_category_value'];
		$erp_material_sub_group = TableRegistry::get('erp_material_sub_group');
		$row = $erp_material_sub_group->newEntity();
		$row->material_group_id = $material_code;
		$row->sub_group_title = $sub_category_value;
		if($erp_material_sub_group->save($row))
		{
			$result['dropdown_data'] = "<option value='{$row->sub_group_id}'>{$sub_category_value}</option>";
			$result['listing_data'] = "<tr>
			<td id='sub_group_name_{$row->sub_group_id}'>{$sub_category_value}</td><td>{$this->ERPfunction->get_vendor_group_name($material_code)}</td>
			<td><a class='btn-edit-subgroup badge badge-info' data-toggle='modal' data-target='#load_modal_edit_subgroup' href='#' data-id='{$row->sub_group_id}' id='edit-subgroup'><i class='icon-edit'></i></a></td>
			</tr>";
			echo json_encode($result);die;
		}
		
	}
	
	public function saveqtycheckedby()
	{
		$result = array();
		$category_title = $_REQUEST['category_title'];
		$erp_category_master = TableRegistry::get('erp_category_master');
		$row = $erp_category_master->newEntity();
		$row->type = 'qty_checkdeby';
		$row->category_title = $category_title;
		$row->status = 1;
		$row->created_date = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');
		if($erp_category_master->save($row))
		{
			$result['dropdown_data'] = "<option value='{$row->cat_id}'>{$category_title}</option>";
			$result['listing_data'] = "<tr>
			<td id='sub_group_name_{$row->cat_id}'>{$category_title}</td>
			</tr>";
			echo json_encode($result);die;
		}
		
	}
	
	public function saveratecheckedby()
	{
		$result = array();
		$category_title = $_REQUEST['category_title'];
		$erp_category_master = TableRegistry::get('erp_category_master');
		$row = $erp_category_master->newEntity();
		$row->type = 'rate_checkdeby';
		$row->category_title = $category_title;
		$row->status = 1;
		$row->created_date = date('Y-m-d H:i:s');
		$row->created_by = $this->request->session()->read('user_id');
		if($erp_category_master->save($row))
		{
			$result['dropdown_data'] = "<option value='{$row->cat_id}'>{$category_title}</option>";
			$result['listing_data'] = "<tr>
			<td id='sub_group_name_{$row->cat_id}'>{$category_title}</td>
			</tr>";
			echo json_encode($result);die;
		}
		
	}
	
	public function editmaterialsubgroup()
	{
		$subgroup_id = $_REQUEST['subgroup_id'];
		$erp_material_sub_group = TableRegistry::get('erp_material_sub_group');
		$row = $erp_material_sub_group->get($subgroup_id);
		$this->set("row",$row);
	}
	
	public function updatematerialsubgroup()
	{
		$subgroup_id = $_REQUEST['subgroup_id'];
		$subgroup_title = $_REQUEST['subgroup_title'];
		$erp_material_sub_group = TableRegistry::get('erp_material_sub_group');
		$row = $erp_material_sub_group->get($subgroup_id);
		$row->sub_group_title = $subgroup_title;
		if($erp_material_sub_group->save($row))
		{
			echo true;die;
		}else{
			echo false;die;
		}
		
	}
	
	public function getmaterialsubgroup()
	{
		$material_code = $_REQUEST['material_code'];
		$erp_material_sub_group = TableRegistry::get('erp_material_sub_group');
		$subgroups = $erp_material_sub_group->find()->where(["material_group_id"=>$material_code])->hydrate(false)->toArray();
		
		$content = '';
		foreach($subgroups as $retrive_data)
		{
			$content .= '<option value ="'.$retrive_data['sub_group_id'].'">'.$retrive_data['sub_group_title'].'</option>';
		}
		echo $content;die;
	}
	
	public function ponoraterecords()
	{  
		// DB table to use
		$table = 'erp_inventory_po';
		// Table's primary key
		$primaryKey = 'po_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'po.po_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'po.po_no', 'dt' => 0 , 'field' => 'po_no' ),
			array( 'db' => 'po.po_date',  'dt' => 1, 'field' => 'po_date' ),
			array( 'db' => 'po.project_id',   'dt' => 2, 'field' => 'project_id'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'po_detail.material_id',  'dt' => 4, 'field' => 'material_id' ),
			array( 'db' => 'po_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'po_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'po_detail.material_id',   'dt' => 7, 'field' => 'material_id'),
			// array( 'db' => 'po_detail.single_amount',   'dt' => 8, 'field' => 'single_amount'),
			// array( 'db' => 'po_detail.amount',   'dt' => 9, 'field' => 'amount'),
			array( 'db' => 'po.po_purchase_type',   'dt' => 8, 'field' => 'po_purchase_type'),
			array( 'db' => 'po.po_id',   'dt' => 9, 'field' => 'po_id'),
			array( 'db' => 'po_detail.static_unit',   'dt' => 10, 'field' => 'static_unit'),
			array( 'db' => 'material.material_title',   'dt' => 11, 'field' => 'material_title'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_ponorate();
		
		$joinQuery = "{$table} AS po 
		LEFT JOIN erp_inventory_po_detail AS po_detail ON po.po_id = po_detail.po_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = po.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = po_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function wonoraterecords()
	{  
		// DB table to use
		$table = 'erp_work_order';
		// Table's primary key
		$primaryKey = 'wo_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'wo.wo_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'wo.wo_date',  'dt' => 0, 'field' => 'wo_date' ),
			array( 'db' => 'wo.wo_no',  'dt' => 1, 'field' => 'wo_no' ),
			array( 'db' => 'project.project_name',   'dt' => 2, 'field' => 'project_name'),
			array( 'db' => 'wo.party_userid',  'dt' => 3, 'field' => 'party_userid'),
			array( 'db' => 'wo.contract_type',  'dt' => 4, 'field' => 'contract_type' ),
			// array( 'db' => 'SUM(wo_detail.amount) as amount',   'dt' => 5, 'field' => 'amount'),
			array( 'db' => 'wo.wo_id',   'dt' => 5, 'field' => 'wo_id'),
			array( 'db' => 'agency.agency_name',   'dt' => 6, 'field' => 'agency_name'),
			array( 'db' => 'vendor.vendor_name',   'dt' => 7, 'field' => 'vendor_name'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_wonorate();
		
		$joinQuery = "{$table} AS wo 
		LEFT JOIN erp_work_order_detail AS wo_detail ON wo.wo_id = wo_detail.wo_id
		LEFT JOIN erp_projects AS project ON project.project_id = wo.project_id
		LEFT JOIN erp_agency AS agency ON agency.agency_id = wo.party_userid
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = wo.party_userid";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}

	public function planningwonoraterecords()
	{  
		// DB table to use
		$table = 'erp_planning_work_order';
		// Table's primary key
		$primaryKey = 'wo_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'wo.wo_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'wo.wo_date',  'dt' => 0, 'field' => 'wo_date' ),
			array( 'db' => 'wo.wo_no',  'dt' => 1, 'field' => 'wo_no' ),
			array( 'db' => 'project.project_name',   'dt' => 2, 'field' => 'project_name'),
			array( 'db' => 'wo.party_userid',  'dt' => 3, 'field' => 'party_userid'),
			array( 'db' => 'wo.contract_type',  'dt' => 4, 'field' => 'contract_type' ),
			// array( 'db' => 'SUM(wo_detail.amount) as amount',   'dt' => 5, 'field' => 'amount'),
			array( 'db' => 'wo.wo_id',   'dt' => 5, 'field' => 'wo_id'),
			array( 'db' => 'agency.agency_name',   'dt' => 6, 'field' => 'agency_name'),
			array( 'db' => 'vendor.vendor_name',   'dt' => 7, 'field' => 'vendor_name'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_planningWoNoRateRecords();
		
		$joinQuery = "{$table} AS wo 
		LEFT JOIN erp_planning_work_order_detail AS wo_detail ON wo.wo_id = wo_detail.wo_id
		LEFT JOIN erp_projects AS project ON project.project_id = wo.project_id
		LEFT JOIN erp_agency AS agency ON agency.agency_id = wo.party_userid
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = wo.party_userid";
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function getmaterialstilldatestock()
	{
		$material_id = $_REQUEST['material_id'];
		$project_id = $_REQUEST['project_id'];
		$date = date("Y-m-d",strtotime($_REQUEST['date']));
		$excluding_record = $_REQUEST['excluding_record'];
		
		$history_tbl = TableRegistry::get("erp_stock_history");
		
		/* Excluding record means does not include that record in stock count */
		if($excluding_record == "yes")
		{
			$type = $_REQUEST['type'];
			$record_id = $_REQUEST['record_id'];
			
			$opening_stock = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os","date <="=>$date])->hydrate(false)->toArray();
		
			$data = $history_tbl->find("all")->where(["AND"=>["project_id"=>$project_id,"material_id"=>$material_id,"type NOT IN"=>array("os","sst_to"),"date <="=>$date],["OR"=>['type !='=>$type,'type_id !='=>$record_id]]])->hydrate(false)->toArray();
		}else{
			$opening_stock = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os","date <="=>$date])->hydrate(false)->toArray();
		
			$data = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type NOT IN"=>array("os","sst_to"),"date <="=>$date])->hydrate(false)->toArray();
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
				$opening_stock = $this->ERPfunction->get_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		echo $opening_stock;
		die();
	}
	
	public function getdebittilldatequantity()
	{
		$till_date_quantity = 0;
		$project_id = $_REQUEST['project_id'];
		$date = date("Y-m-d",strtotime($_REQUEST['debit_date']));
		$party_id = $_REQUEST['party_id'];
		$material_id = $_REQUEST['material_id'];
		
		$erp_stock_history = TableRegistry::get('erp_stock_history'); 
		
		/* Get Issued quantity of still date */
		$query = $erp_stock_history->find()->where(['erp_stock_history.project_id'=>$project_id,"erp_stock_history.material_id"=>$material_id,"erp_stock_history.date <="=>$date,"type"=>"is"])->select(["quantity"]);
		
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
		$query1 = $erp_stock_history->find()->where(['erp_stock_history.project_id'=>$project_id,"erp_stock_history.material_id"=>$material_id,"erp_stock_history.date <="=>$date,"type"=>"rbn"])->select(["quantity"]);
		
		$query = $query1->innerjoin(
							["erp_inventory_rbn"=>"erp_inventory_rbn"],
							["erp_inventory_rbn.rbn_id = erp_stock_history.type_id"])
							->where(["erp_inventory_rbn.agency_name"=>$party_id]);
		$rbn_data = $query1->select(['sum' => $query1->func()->sum('quantity')])->hydrate(false)->toArray();
		$rbn_quantity = $rbn_data[0]["sum"];
		if($rbn_quantity != null)
		{
			$till_date_quantity = $till_date_quantity - $rbn_quantity;
		}
		/* Get RBN quantity of still date */
		
		/* Get Debit quantity of still date */
		$query2 = $erp_stock_history->find()->where(['erp_stock_history.project_id'=>$project_id,"erp_stock_history.material_id"=>$material_id,"erp_stock_history.date <="=>$date,"type"=>"debit"])->select(["quantity"]);
		
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
		echo $till_date_quantity;die;
	}
	
	public function inventorydebitrecords()
	{  
		// DB table to use
		$table = 'erp_inventory_debit_note';
		// Table's primary key
		$primaryKey = 'debit_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'debit.debit_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'project.project_name',   'dt' => 0, 'field' => 'project_name'),
			array( 'db' => 'debit.debit_note_no',  'dt' => 1, 'field' => 'debit_note_no' ),
			array( 'db' => 'debit.date',  'dt' => 2, 'field' => 'date' ),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'debit.receiver_name',  'dt' => 4, 'field' => 'receiver_name' ),
			array( 'db' => 'SUM(debit_detail.amount) as amount',   'dt' => 5, 'field' => 'amount'),
			array( 'db' => 'debit.debit_id',   'dt' => 6, 'field' => 'debit_id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_inventorydebit();
		
		$joinQuery = "{$table} AS debit 
		LEFT JOIN erp_inventory_debit_note_detail AS debit_detail ON debit.debit_id = debit_detail.debit_id
		LEFT JOIN erp_projects AS project ON project.project_id = debit.project_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = debit.debit_to";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function updateholiday()
	{
		$month = $_REQUEST['month'];
		$year = $_REQUEST['year'];
		$month_holiday = TableRegistry::get('month_holiday');
		$row = $month_holiday->find()->where(["month"=>$month,"year"=>$year])->first();
		$holiday = (!empty($row))?$row->holiday:0;
		$this->set("holiday",$holiday);
		$this->set("month",$month);
		$this->set("year",$year);
	}
	
	public function updateholidayvalue()
	{
		$month = $_REQUEST['month'];
		$year = $_REQUEST['year'];
		$holiday = $_REQUEST['holiday'];
		
		$month_holiday = TableRegistry::get('month_holiday');
		$row = $month_holiday->find()->where(["month"=>$month,"year"=>$year])->first();
		
		if(!empty($row))
		{
			$holiday_record = $month_holiday->get($row->id);
			$holiday_record->holiday = $holiday;
			$save = $month_holiday->save($holiday_record);
		}else{
			$holiday_record = $month_holiday->newEntity();
			$holiday_record->month = $month;
			$holiday_record->year = $year;
			$holiday_record->holiday = $holiday;
			$save = $month_holiday->save($holiday_record);
		}
		echo $save;die;
	}
	
	public function postatusrecords()
	{  
		// DB table to use
		$table = 'erp_inventory_po';
		// Table's primary key
		$primaryKey = 'po_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'po.po_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'po.po_no', 'dt' => 0 , 'field' => 'po_no' ),
			array( 'db' => 'po.po_date',  'dt' => 1, 'field' => 'po_date' ),
			array( 'db' => 'po.project_id',   'dt' => 2, 'field' => 'project_id'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'po_detail.material_id',  'dt' => 4, 'field' => 'material_id' ),
			array( 'db' => 'po_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'po_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'po_detail.quantity',   'dt' => 7, 'field' => 'quantity'),
			array( 'db' => 'po_detail.grn_remain_qty',   'dt' => 8, 'field' => 'grn_remain_qty'),
			array( 'db' => 'po_detail.material_id',   'dt' => 9, 'field' => 'material_id'),
			array( 'db' => 'po.remarks',   'dt' => 10, 'field' => 'remarks'),
			array( 'db' => 'po.po_id',   'dt' => 11, 'field' => 'po_id'),
			array( 'db' => 'po_detail.static_unit',   'dt' => 12, 'field' => 'static_unit'),
			array( 'db' => 'material.material_title',   'dt' => 13, 'field' => 'material_title'),
			array( 'db' => 'po_detail.id',   'dt' => 14, 'field' => 'id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_postatus();
		
		$joinQuery = "{$table} AS po 
		LEFT JOIN erp_inventory_po_detail AS po_detail ON po.po_id = po_detail.po_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = po.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = po_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function podeliveryhistory()
	{	
		$po_detail_id = $_REQUEST['po_detail_id'];
		$manually_received_po = TableRegistry::get('manually_received_po');
		$erp_inventory_grn = TableRegistry::get('erp_inventory_grn');
		$erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail');
				
		$result = $erp_inventory_grn_detail->find()->select(["erp_inventory_grn_detail.actual_qty","erp_inventory_grn_detail.grndetail_id"])->where(["po_detail_id"=>$po_detail_id]);
		$result = $result->innerjoin(
			["erp_inventory_grn"=>"erp_inventory_grn"],
			["erp_inventory_grn_detail.grn_id = erp_inventory_grn.grn_id"])
			->select(["erp_inventory_grn.grn_id","erp_inventory_grn.grn_no","erp_inventory_grn.grn_date"])->hydrate(false)->toArray();
		// debug($result);
		$manual_data = $manually_received_po->find()->where(["po_detail_id"=>$po_detail_id])->hydrate(false)->toArray();
		
		$this->set('po_detail_id',$po_detail_id); 
		$this->set('grn_data',$result); 
		$this->set('manual_data',$manual_data); 
	}
	
	public function receivepoquantitymanual()
	{
		$po_detail_id = $_REQUEST['po_detail_id'];
		$erp_inventory_po = TableRegistry::get('erp_inventory_po');
		$erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail');
				
		$result = $erp_inventory_po_detail->find()->where(["id"=>$po_detail_id])->select(["erp_inventory_po_detail.material_id"]);
		$result = $result->innerjoin(
			["erp_inventory_po"=>"erp_inventory_po"],
			["erp_inventory_po_detail.po_id = erp_inventory_po.po_id"])
			->select(["erp_inventory_po.po_id","erp_inventory_po.po_no"])->first();
		$this->set('po_data',$result);
		$this->set('po_detail_id',$po_detail_id);
	}
	
	public function podeliveryrecords()
	{  
		// DB table to use
		$table = 'erp_inventory_po';
		// Table's primary key
		$primaryKey = 'po_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'po.po_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'po.po_no', 'dt' => 0 , 'field' => 'po_no' ),
			array( 'db' => 'po.po_date',  'dt' => 1, 'field' => 'po_date' ),
			array( 'db' => 'po.project_id',   'dt' => 2, 'field' => 'project_id'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'po_detail.material_id',  'dt' => 4, 'field' => 'material_id' ),
			array( 'db' => 'po_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'po_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'po_detail.quantity',   'dt' => 7, 'field' => 'quantity'),
			array( 'db' => 'po_detail.grn_remain_qty',   'dt' => 8, 'field' => 'grn_remain_qty'),
			array( 'db' => 'po_detail.material_id',   'dt' => 9, 'field' => 'material_id'),
			array( 'db' => 'po.remarks',   'dt' => 10, 'field' => 'remarks'),
			array( 'db' => 'po.po_id',   'dt' => 11, 'field' => 'po_id'),
			array( 'db' => 'po_detail.static_unit',   'dt' => 12, 'field' => 'static_unit'),
			array( 'db' => 'material.material_title',   'dt' => 13, 'field' => 'material_title'),
			array( 'db' => 'po_detail.id',   'dt' => 14, 'field' => 'id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_podelivery();
		
		$joinQuery = "{$table} AS po 
		LEFT JOIN erp_inventory_po_detail AS po_detail ON po.po_id = po_detail.po_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = po.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = po_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function inventorypostatusrecords()
	{  
		// DB table to use
		$table = 'erp_inventory_po';
		// Table's primary key
		$primaryKey = 'po_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'po.po_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'po.po_no', 'dt' => 0 , 'field' => 'po_no' ),
			array( 'db' => 'po.po_date',  'dt' => 1, 'field' => 'po_date' ),
			array( 'db' => 'po.project_id',   'dt' => 2, 'field' => 'project_id'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'po_detail.material_id',  'dt' => 4, 'field' => 'material_id' ),
			array( 'db' => 'po_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'po_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'po_detail.quantity',   'dt' => 7, 'field' => 'quantity'),
			array( 'db' => 'po_detail.grn_remain_qty',   'dt' => 8, 'field' => 'grn_remain_qty'),
			array( 'db' => 'po_detail.material_id',   'dt' => 9, 'field' => 'material_id'),
			array( 'db' => 'po.remarks',   'dt' => 10, 'field' => 'remarks'),
			array( 'db' => 'po.po_id',   'dt' => 11, 'field' => 'po_id'),
			array( 'db' => 'po_detail.static_unit',   'dt' => 12, 'field' => 'static_unit'),
			array( 'db' => 'material.material_title',   'dt' => 13, 'field' => 'material_title'),
			array( 'db' => 'po_detail.id',   'dt' => 14, 'field' => 'id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_inventorypostatus();
		
		$joinQuery = "{$table} AS po 
		LEFT JOIN erp_inventory_po_detail AS po_detail ON po.po_id = po_detail.po_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = po.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = po_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function inventorypodeliveryrecords()
	{  
		// DB table to use
		$table = 'erp_inventory_po';
		// Table's primary key
		$primaryKey = 'po_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'po.po_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'po.po_no', 'dt' => 0 , 'field' => 'po_no' ),
			array( 'db' => 'po.po_date',  'dt' => 1, 'field' => 'po_date' ),
			array( 'db' => 'po.project_id',   'dt' => 2, 'field' => 'project_id'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'po_detail.material_id',  'dt' => 4, 'field' => 'material_id' ),
			array( 'db' => 'po_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'po_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'po_detail.quantity',   'dt' => 7, 'field' => 'quantity'),
			array( 'db' => 'po_detail.grn_remain_qty',   'dt' => 8, 'field' => 'grn_remain_qty'),
			array( 'db' => 'po_detail.material_id',   'dt' => 9, 'field' => 'material_id'),
			array( 'db' => 'po.remarks',   'dt' => 10, 'field' => 'remarks'),
			array( 'db' => 'po.po_id',   'dt' => 11, 'field' => 'po_id'),
			array( 'db' => 'po_detail.static_unit',   'dt' => 12, 'field' => 'static_unit'),
			array( 'db' => 'material.material_title',   'dt' => 13, 'field' => 'material_title'),
			array( 'db' => 'po_detail.id',   'dt' => 14, 'field' => 'id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_inventorypodelivery();
		
		$joinQuery = "{$table} AS po 
		LEFT JOIN erp_inventory_po_detail AS po_detail ON po.po_id = po_detail.po_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = po.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = po_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function materialrecords()
	{  
		// DB table to use
		$table = 'erp_material';
		// Table's primary key
		$primaryKey = 'material_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'material.material_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'material.material_item_code', 'dt' => 0 , 'field' => 'material_item_code' ),
			array( 'db' => 'material.material_code',  'dt' => 1, 'field' => 'material_code' ),
			array( 'db' => 'material.material_sub_group',   'dt' => 2, 'field' => 'material_sub_group'),
			array( 'db' => 'material.material_title',  'dt' => 3, 'field' => 'material_title'),
			array( 'db' => 'material.desciption',  'dt' => 4, 'field' => 'desciption' ),
			array( 'db' => 'material.unit_id',   'dt' => 5, 'field' => 'unit_id'),
			array( 'db' => 'material.project_id',   'dt' => 6, 'field' => 'project_id'),
			array( 'db' => 'material.material_id',   'dt' => 7, 'field' => 'material_id'),
			array( 'db' => 'material.material_id',   'dt' => 8, 'field' => 'material_id'),
		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_material();
		
		$joinQuery = "{$table} AS material";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function accountsgrndata()
	{  
		// DB table to use
		$table = 'erp_inventory_grn';
		// Table's primary key
		$primaryKey = 'grn_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'grn.grn_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'grn.grn_no',  'dt' => 0, 'field' => 'grn_no' ),
			array( 'db' => 'grn.grn_date',   'dt' => 1, 'field' => 'grn_date'),
			array( 'db' => 'grn.vendor_userid',  'dt' => 2, 'field' => 'vendor_userid'),
			array( 'db' => 'grn.challan_no',  'dt' => 3, 'field' => 'challan_no' ),
			array( 'db' => 'grn_detail.material_id',   'dt' => 4, 'field' => 'material_id'),
			array( 'db' => 'grn_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'grn_detail.actual_qty',   'dt' => 6, 'field' => 'actual_qty'),
			array( 'db' => 'grn_detail.material_id',   'dt' => 7, 'field' => 'material_id'),
			array( 'db' => 'grn.grn_id',   'dt' => 8, 'field' => 'grn_id'),
			array( 'db' => 'grn.grn_id',   'dt' => 9, 'field' => 'grn_id'),
			array( 'db' => 'grn_detail.static_unit',   'dt' => 10, 'field' => 'static_unit'),
			array( 'db' => 'grn_detail.material_name',   'dt' => 11, 'field' => 'material_name'),
			array( 'db' => 'grn_detail.brand_name',   'dt' => 12, 'field' => 'brand_name'),
			array( 'db' => 'grn.project_id',   'dt' => 13, 'field' => 'project_id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';

		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();
		


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_accountgrn();
		
		$joinQuery = "{$table} AS grn 
		LEFT JOIN erp_inventory_grn_detail AS grn_detail ON grn.grn_id = grn_detail.grn_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function assetmanagement()
	{  
		// DB table to use
		$table = 'erp_assets';
		// Table's primary key
		$primaryKey = 'asset_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'asset.asset_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'asset.asset_group',  'dt' => 0, 'field' => 'asset_group' ),
			array( 'db' => 'asset.asset_code',   'dt' => 1, 'field' => 'asset_code'),
			array( 'db' => 'asset.asset_name',  'dt' => 2, 'field' => 'asset_name'),
			array( 'db' => 'asset.capacity',  'dt' => 3, 'field' => 'capacity' ),
			array( 'db' => 'asset.asset_make',   'dt' => 4, 'field' => 'asset_make'),
			array( 'db' => 'asset.vehicle_no',   'dt' => 5, 'field' => 'vehicle_no'),
			array( 'db' => 'asset.operational_status',   'dt' => 6, 'field' => 'operational_status'),
			array( 'db' => 'asset.deployed_to',   'dt' => 7, 'field' => 'deployed_to'),
			array( 'db' => 'asset.asset_id',   'dt' => 8, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 9, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 10, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 11, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 12, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 13, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 14, 'field' => 'asset_id'),
			array( 'db' => 'asset.quantity',   'dt' => 15, 'field' => 'quantity'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_assetmanagement();
		
		$joinQuery = "{$table} AS asset";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function assetrecords()
	{  
		// DB table to use
		$table = 'erp_assets';
		// Table's primary key
		$primaryKey = 'asset_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'asset.asset_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'asset.asset_code',   'dt' => 0, 'field' => 'asset_code'),
			array( 'db' => 'asset.asset_name',  'dt' => 1, 'field' => 'asset_name'),
			array( 'db' => 'asset.capacity',  'dt' => 2, 'field' => 'capacity' ),
			array( 'db' => 'asset.asset_make',   'dt' => 3, 'field' => 'asset_make'),
			array( 'db' => 'asset.vehicle_no',   'dt' => 4, 'field' => 'vehicle_no'),
			array( 'db' => 'asset.purchase_date',   'dt' => 5, 'field' => 'purchase_date'),
			array( 'db' => 'asset.operational_status',   'dt' => 6, 'field' => 'operational_status'),
			array( 'db' => 'asset.deployed_to',   'dt' => 7, 'field' => 'deployed_to'),
			array( 'db' => 'asset.asset_id',   'dt' => 8, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 9, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 10, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 11, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 12, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 13, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 14, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 15, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 16, 'field' => 'asset_id'),
			array( 'db' => 'asset.asset_id',   'dt' => 17, 'field' => 'asset_id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_assetrecords();
		
		$joinQuery = "{$table} AS asset";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function loadassetmake()
	{	
		$table_category=TableRegistry::get('erp_category_master');
		$make_list=$table_category->find()->where(array('type'=>'make_in'));
		
		$content = '';
		foreach($make_list as $retrive_data)
		{
			$content .= '<option value = "'.$retrive_data['cat_id'].'">'.$retrive_data['category_title'].'</option>';
		}
		echo $content;
		die;
	}
	
	public function loadassetlist()
	{
		$asset_table = TableRegistry::get('erp_assets'); 
		$projects_ids = $this->Usermanage->users_project($this->user_id);	
		$role = $this->Usermanage->get_user_role($this->user_id);
		$this->set('role',$role);
		if($this->Usermanage->project_alloted($role)==1){ 
			if(!empty($projects_ids))
			{
				$asset_name = $asset_table->find("list",["keyField"=>"asset_name","valueField"=>"asset_name"])->where(["deployed_to IN"=>$projects_ids]);
			}else{
				$asset_name = array();
			}
		}
		else{
			$asset_name = $asset_table->find("list",["keyField"=>"asset_name","valueField"=>"asset_name"])->toArray();
		}
		
		$content = '';
		foreach($asset_name as $key=>$value)
		{
			$content .= '<option value = "'.$key.'">'.$value.'</option>';
		}
		echo $content;
		die;
	}
	 /*  Expenditure Start*/
    public function getExpenditure($id=null)
    {
    	$this->autoRender = false;
    	if($this->request->is('ajax'))
    	{
    		$id = $this->request->data['id'];

    		$user = TableRegistry::get('erp_users');
    		$find =  $user->get($id);
			$designation = $this->ERPfunction->get_category_title($find->designation);
			$employee_at = $this->ERPfunction->get_user_employee_at($id);
		//	$employee_at = $this->ERPFunction->get_projectname($find->employee_at);
			if($find->pay_type == 'consultant')
			{
				$pay_type = 'labour';
			}
			else
			{
				$pay_type = $find->pay_type;
			}
			
    		$result[] = array(
    			'employee_no'=>$find->employee_no,
    			'designation'=>$designation,
    			//'employee_at'=>$find->employee_at,
    			'employee_at'=>$employee_at,
    			'pay_type'=>$pay_type,
				'contact_no'=>$find->mobile_no,

    		); 
    		echo json_encode($result);
    	}
    }

    public function expenditurehistory()
    {
    	if($this->request->is('post')) {
    		$user_id = $this->request->data['id'];
			$user_tbl = TableRegistry::get('erp_users');
			$user_data = $user_tbl->get($user_id);
			
			$this->set('user_data',$user_data);

			$expenditure_tbl = TableRegistry::get('expenditure_clam');
			$data = $expenditure_tbl->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
			
			$this->set('user_id',$user_id);
			$this->set('data',$data);
		}
    }

   
    /*Expenditure Over*/
	public function newrowrmcinventory()
	{
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$row_id = $_REQUEST['row_id'];
		$erp_material = TableRegistry::get('erp_material');
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(["material_id IN"=>$material_ids,"project_id IN"=>$projectids_in]);
		}else{
			$material_list = $erp_material->find()->where(["project_id IN"=>$projectids_in]);
		} 
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);
	}
	
	public function viewinventoryrmcrecords()
	{  
		// DB table to use
		$table = 'erp_inventory_rmc';
		// Table's primary key
		$primaryKey = 'id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'rmc.id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'rmc.project_id', 'dt' => 0 , 'field' => 'project_id' ),
			array( 'db' => 'rmc.rmc_date', 'dt' => 1 , 'field' => 'rmc_date' ),
			array( 'db' => 'rmc.rmc_no',  'dt' => 2, 'field' => 'rmc_no' ),
			array( 'db' => 'asset.asset_name',   'dt' => 3, 'field' => 'asset_name'),
			array( 'db' => 'rmc.order_by',  'dt' => 4, 'field' => 'order_by'),
			array( 'db' => 'rmc.concrete_grade',  'dt' => 5, 'field' => 'concrete_grade' ),
			array( 'db' => 'rmc.total_quantity_supplied',   'dt' => 6, 'field' => 'total_quantity_supplied'),
			array( 'db' => 'rmc.rmc_usage',   'dt' => 7, 'field' => 'rmc_usage'),
			array( 'db' => 'rmc.start_time',   'dt' => 8, 'field' => 'start_time'),
			array( 'db' => 'rmc.end_time',   'dt' => 9, 'field' => 'end_time'),
			array( 'db' => 'rmc.id',   'dt' => 10, 'field' => 'id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_inventoryrmc();
		
		$joinQuery = "{$table} AS rmc 
		LEFT JOIN erp_assets AS asset ON rmc.asset_id = asset.asset_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function approveinventoryrmc()
	{
		$rmc_id = $_REQUEST['rmc_id'];
		$erp_inventory_rmc = TableRegistry::get('erp_inventory_rmc');
		$rmc_row = $erp_inventory_rmc->get($rmc_id);
		$project_id = $rmc_row->project_id;
		$rmc_date = $rmc_row->rmc_date;
		$concrete_grade = $rmc_row->concrete_grade;
		$total_quantity_supplied = $rmc_row->total_quantity_supplied;
		
		$erp_inventory_mix_detail = TableRegistry::get('erp_inventory_mix_detail');
		$material_row = $erp_inventory_mix_detail->find()->where(["mix_id"=>$concrete_grade])->hydrate(false)->toArray();
		
		foreach($material_row as $m_row)
		{
			$material_id = $m_row['material_id'];
			$consumption = $m_row['consumption'];
			$quantity = $total_quantity_supplied * $consumption;
			/* Redirect back and do not update if stock going nagative after edit*/
			$available_stock = $this->ERPfunction->get_current_stock($project_id,$material_id);
			$stock_after = $available_stock - $quantity;
			if($stock_after < 0)
			{
				$m = $this->ERPfunction->get_material_title($material_id);
				echo $this->Flash->error(__("ERROR : Stock is going nagative after this action for material {$m},Please Try again", null), 'default',array('class' => 'success'));
				die;
			}
			/* Redirect back and do not update if stock going nagative after edit*/
		}
		$erp_inventory_rmc = TableRegistry::get('erp_inventory_rmc');
		$rmc_data = $erp_inventory_rmc->get($rmc_id);		
		$post_data['approved'] = 1;
		$post_data['approved_by'] = $this->request->session()->read('user_id');
		$post_data['approved_date'] = date('Y-m-d');
		
		$data = $erp_inventory_rmc->patchEntity($rmc_data,$post_data);
		$erp_inventory_rmc->save($data);
		
		foreach($material_row as $m_row)
		{
			$material_id = $m_row['material_id'];
			$consumption = $m_row['consumption'];
			$quantity = $total_quantity_supplied * $consumption;
			
			$history_tbl = TableRegistry::get("erp_stock_history");
			$history_row = $history_tbl->newEntity();
			$insert["date"] = $rmc_date;
			$insert["project_id"] = $project_id;
			$insert["material_id"] = $material_id;
			$insert["quantity"] = $quantity;
			$insert["stock_out"] = $quantity;			
			$insert["type"] = "rmc";
			$insert["type_id"] = $rmc_id;
			$history_row = $history_tbl->patchEntity($history_row,$insert);
			$history_tbl->save($history_row);	
		}
		echo $this->Flash->success(__("RMC Record approves successfully.", null), 'default',array('class' => 'success'));
		die();
	}
	
	/*  Filemanager Function */
	public function getcloudfiles()
	{
		$allow_delete = true;
		$file = rawurldecode($_REQUEST['file']) ?: '.';
		// if (is_dir($file)) {
		// var_dump($file);die;
		$directory = $file;
		$result = [];
		// $files = array_diff(scandir($directory), ['.','..']);
		$storageClient = new StorageClient([
			'projectId' => 'gym-management-system-188906',
			'keyFilePath' => WWW_ROOT .'/nghome/gym-management-system-188906-8be3c1fa2801.json',
		]);
		
		$bucket = $storageClient->bucket('gym-management-system-188906.appspot.com');

		$adapter = new GoogleStorageAdapter($storageClient, $bucket);
		$filesystem = new Filesystem($adapter);

		// debug(file_get_contents($attachment['tmp_name']));die;
		$filesystem = new Filesystem($adapter);
		$files = $filesystem->listContents("/$file", false); //Listing
		// echo "<pre>";
			// print_r($files);
			// echo "<pre>";die;
		foreach ($files as $entry)
		{
		if (!self::is_entry_ignored($entry, 'true', 'php')) {
		$i = $directory . '/' . $entry['path'];
		$stat = stat($i);
		// var_dump($stat);die;
	        $result[] = [
	        	'type' => $entry['type'],
	        	'mtime' => $entry['timestamp'],
	        	'size' => $entry['size'],
	        	'name' => $entry['basename'],
	        	// 'path' => preg_replace('@^\./@', '', $i),
	        	'path' => $entry['path'],
	        	'is_dir' => ($entry['type']=='dir')?true:false,
	        	'is_deleteable' => true,
	        	// 'is_readable' => is_readable($i),
	        	'is_readable' => true,
	        	// 'is_writable' => is_writable($i),
	        	'is_writable' => true,
	        	// 'is_executable' => is_executable($i),
	        	'is_executable' => true,
	        ];
			
	    }
		}
		// var_dump($result);die;
	// } else {
		// err(412,"Not a Directory");
	// }
	// var_dump($result);die;
	echo json_encode(['success' => true, 'is_writable' => is_writable($file), 'results' =>$result]);
	die;
	exit;
	}
	
	function is_entry_ignored($entry, $allow_show_folders, $hidden_extensions) {
		error_reporting(0);
		if ($entry === basename(__FILE__)) {
			return true;
		}

		if (is_dir($entry) && !$allow_show_folders) {
			return true;
		}

		$ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
		if (in_array($ext, $hidden_extensions)) {
			return true;
		}

		return false;
	}
	/*  Filemanager Function */
	
	public function assetmaintenancerow()
	{
		$row_id = $_REQUEST['row_id'];
		$this->set('row_id',$row_id);
	}
	
	public function inassetpoprojectdetail()
	{		
		$project_id = $_REQUEST['project_id'];
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);
		
		$result_arr = array();
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_code'] = $retrive_data['project_code'];			
			$result_arr['project_address'] = $retrive_data['project_address'];			
			$result_arr['project_address_2'] = $retrive_data['city'] ."-".$retrive_data['pincode'].",".$retrive_data['district'].",".$retrive_data['state'];		
						
		}

		$new_prno = $this->ERPfunction->generate_auto_id($project_id,"erp_manual_po","po_id","po_no");
		$new_prno = sprintf("%09d", $new_prno);
		$pr_no = $result_arr['project_code'].'/MANPO/'.$new_prno;
		$result_arr['po_no'] = $pr_no;
		
		$asset_tbl = TableRegistry::get('erp_assets'); 
		$assets = $asset_tbl->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->where(["deployed_to"=>$project_id]);
		$asset_list = "";
		if(!empty($assets))
		{
			foreach($assets as $key=>$value)
			{
				$asset_list .= "<option value='{$key}'>{$value}</option>";
			}
		}else{
			$asset_list .= "<option value=''>NO asset found</option>";
		}
		$result_arr['asset_list'] = $asset_list;
		
		echo json_encode($result_arr);
		die();
	}
	
	public function addnewrowpoasset()
	{
		$row_id = $_REQUEST['row_id'];
		$row_type = $_REQUEST['row_type'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material');
		
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(['material_id IN'=>$material_ids,"material_code !="=>17,"project_id IN"=>$projectids_in]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id IN"=>$projectids_in]);
		}
	
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);		
		$this->set('row_type',$row_type);
	}
	
	public function assetporecords()
	{  
		// DB table to use
		$table = 'erp_asset_po';
		// Table's primary key
		$primaryKey = 'po_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'po.po_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'po.po_no', 'dt' => 0 , 'field' => 'po_no' ),
			array( 'db' => 'po.po_date',  'dt' => 1, 'field' => 'po_date' ),
			array( 'db' => 'po.project_id',   'dt' => 2, 'field' => 'project_id'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'po_detail.material_id',  'dt' => 4, 'field' => 'material_id' ),
			array( 'db' => 'po_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'po_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'po_detail.material_id',   'dt' => 7, 'field' => 'material_id'),
			array( 'db' => 'po_detail.single_amount',   'dt' => 8, 'field' => 'single_amount'),
			array( 'db' => 'po_detail.amount',   'dt' => 9, 'field' => 'amount'),
			// array( 'db' => 'po.po_purchase_type',   'dt' => 10, 'field' => 'po_purchase_type'),
			array( 'db' => 'po.po_id',   'dt' => 10, 'field' => 'po_id'),
			array( 'db' => 'po_detail.static_unit',   'dt' => 11, 'field' => 'static_unit'),
			array( 'db' => 'material.material_title',   'dt' => 12, 'field' => 'material_title'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_assetpo();
		
		$joinQuery = "{$table} AS po 
		LEFT JOIN erp_asset_po_detail AS po_detail ON po.po_id = po_detail.po_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = po.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = po_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function addnewrowloi()
	{
		$row_id = $_REQUEST['row_id'];
		$row_type = $_REQUEST['row_type'];
		$project_id = $_REQUEST['project_id'];
		$projectids_in = array();
		if($project_id)
		{
			$projectids_in[] = $project_id; 
			$projectids_in[] = "0"; 
		}else
		{ 
			$projectids_in[] = "0"; 
		}
		$erp_material = TableRegistry::get('erp_material');
		
		if($this->role == "deputymanagerelectric")
		{
			$material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
			$material_ids = json_decode($material_ids);
			$material_list = $erp_material->find()->where(['material_id IN'=>$material_ids,"material_code !="=>17,"project_id IN"=>$projectids_in]);
		}else{
			$material_list = $erp_material->find()->where(["material_code !="=>17,"project_id IN"=>$projectids_in]);
		}
	
		$this->set('material_list',$material_list);		
		$this->set('row_id',$row_id);		
		$this->set('row_type',$row_type);
	}
	
	public function deleteloidetail()
	{
		$this->autoRender = false ;
		$detail_id = $_REQUEST['detail_id'];
		
		$erp_letter_content_detail = TableRegistry::get('erp_letter_content_detail');
		// $delpom_tbl = TableRegistry::get('erp_inventory_deleted_po_detail');
		// $get_deleted_po = $erp_letter_content_detail->get($detail_id);
		// $deleted_po = $get_deleted_po->toArray();
		// $deleted_po["deleted_by"] = $this->user_id;
		// $deleted_po = $delpom_tbl->newEntity($deleted_po);
		// if($delpom_tbl->save($deleted_po))
		// {
			$row =$erp_letter_content_detail->get($detail_id);
			$erp_letter_content_detail->delete($row);
		// }
		
		die;
	}
	
	public function loirecords()
	{  
		// DB table to use
		$table = 'erp_letter_content';
		// Table's primary key
		$primaryKey = 'id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'loi.id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'loi.loi_no', 'dt' => 0 , 'field' => 'loi_no' ),
			array( 'db' => 'loi.loi_date',  'dt' => 1, 'field' => 'loi_date' ),
			array( 'db' => 'loi.project_id',   'dt' => 2, 'field' => 'project_id'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name'),
			array( 'db' => 'loi_detail.material_id',  'dt' => 4, 'field' => 'material_id' ),
			array( 'db' => 'loi_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'loi_detail.quantity',   'dt' => 6, 'field' => 'quantity'),
			array( 'db' => 'loi_detail.material_id',   'dt' => 7, 'field' => 'material_id'),
			array( 'db' => 'loi_detail.single_amount',   'dt' => 8, 'field' => 'single_amount'),
			array( 'db' => 'loi_detail.amount',   'dt' => 9, 'field' => 'amount'),
			// array( 'db' => 'po.po_purchase_type',   'dt' => 10, 'field' => 'po_purchase_type'),
			array( 'db' => 'loi.id',   'dt' => 10, 'field' => 'id'),
			array( 'db' => 'loi_detail.static_unit',   'dt' => 11, 'field' => 'static_unit'),
			array( 'db' => 'material.material_title',   'dt' => 12, 'field' => 'material_title'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_loi();
		
		$joinQuery = "{$table} AS loi 
		LEFT JOIN erp_letter_content_detail AS loi_detail ON loi.id = loi_detail.loi_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = loi.vendor_userid
		LEFT JOIN erp_material AS material ON material.material_id = loi_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function viewrbndata()
	{  
		// DB table to use
		$table = 'erp_inventory_rbn';
		// Table's primary key
		$primaryKey = 'rbn_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'rbn.rbn_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'rbn.project_id', 'dt' => 0 , 'field' => 'project_id' ),
			array( 'db' => 'rbn.rbn_no',  'dt' => 1, 'field' => 'rbn_no' ),
			array( 'db' => 'rbn.rbn_date',   'dt' => 2, 'field' => 'rbn_date'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name' ),
			array( 'db' => 'material.material_title',   'dt' => 4, 'field' => 'material_title'),
			array( 'db' => 'rbn_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			array( 'db' => 'rbn_detail.quantity_reurn',   'dt' => 6, 'field' => 'quantity_reurn'),
			array( 'db' => 'rbn_detail.material_id',   'dt' => 7, 'field' => 'material_id'),
			array( 'db' => 'rbn_detail.name_of_foreman',   'dt' => 8, 'field' => 'name_of_foreman'),
			array( 'db' => 'rbn.rbn_id',   'dt' => 9, 'field' => 'rbn_id'),
			array( 'db' => 'rbn.changes_status',   'dt' => 10, 'field' => 'changes_status'),

			// array( 'db' => 'rbn.project_id', 'dt' => 0 , 'field' => 'project_id' ),
			// array( 'db' => 'rbn.rbn_no',  'dt' => 1, 'field' => 'rbn_no' ),
			// array( 'db' => 'rbn.rbn_date',   'dt' => 2, 'field' => 'rbn_date'),
			// array( 'db' => 'vendor.vendor_name',  'dt' => 3, 'field' => 'vendor_name' ),
			// array( 'db' => 'material.material_title',   'dt' => 4, 'field' => 'material_title'),
			// array( 'db' => 'rbn_detail.brand_id',   'dt' => 5, 'field' => 'brand_id'),
			// array( 'db' => 'rbn_detail.quantity_reurn',   'dt' => 6, 'field' => 'quantity_reurn'),
			// array( 'db' => 'rbn_detail.material_id',   'dt' => 7, 'field' => 'material_id'),
			// array( 'db' => 'rbn_detail.name_of_foreman',   'dt' => 8, 'field' => 'name_of_foreman'),
			// array( 'db' => 'rbn.rbn_id',   'dt' => 9, 'field' => 'rbn_id'),
			// array( 'db' => 'rbn.changes_status',   'dt' => 10, 'field' => 'changes_status'),
		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_rbn();
		
		$joinQuery = "{$table} AS rbn 
		LEFT JOIN erp_inventory_rbn_detail AS rbn_detail ON rbn.rbn_id = rbn_detail.rbn_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = rbn.agency_name
		LEFT JOIN erp_material AS material ON material.material_id = rbn_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function loadrmcprojectasset()
	{		
		$project_id = $_REQUEST['project_id'];
		$result_arr = array();

		$asset_tbl = TableRegistry::get('erp_assets'); 
		$assets = $asset_tbl->find("list",["keyField"=>"asset_id","valueField"=>"asset_name"])->where(["deployed_to"=>$project_id,"asset_group"=>1]);
		
		$asset_list = "";
		if(!empty($assets))
		{
			$asset_list .= '<option value="">--Select Asset--</option>';
			foreach($assets as $key=>$value)
			{

				$asset_list .= "<option value='{$key}'>{$value}</option>";
			}
		}else{
			$asset_list .= "<option value=''>NO asset found</option>";
		}
		$result_arr[] = $asset_list;
		echo json_encode($result_arr);
		die();
	}
	
	public function viewmrndata()
	{  
		// DB table to use
		$table = 'erp_inventory_mrn';
		// Table's primary key
		$primaryKey = 'mrn_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'mrn.mrn_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'mrn.project_id', 'dt' => 0 , 'field' => 'project_id' ),
			array( 'db' => 'mrn.mrn_no',  'dt' => 1, 'field' => 'mrn_no' ),
			array( 'db' => 'mrn.mrn_date',   'dt' => 2, 'field' => 'mrn_date'),
			array( 'db' => 'mrn.mrn_time',   'dt' => 3, 'field' => 'mrn_time'),
			array( 'db' => 'vendor.vendor_name',  'dt' => 4, 'field' => 'vendor_name'),
			array( 'db' => 'material.material_title',   'dt' => 5, 'field' => 'material_title'),
			array( 'db' => 'mrn_detail.brand_id',   'dt' => 6, 'field' => 'brand_id'),
			array( 'db' => 'mrn_detail.quantity',   'dt' => 7, 'field' => 'quantity'),
			array( 'db' => 'mrn_detail.material_id',   'dt' => 8, 'field' => 'material_id'),
			array( 'db' => 'mrn.mrn_id',   'dt' => 9, 'field' => 'mrn_id'),
			array( 'db' => 'mrn_detail.mrn_detail_id',   'dt' => 10, 'field' => 'mrn_detail_id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_mrn();
		
		$joinQuery = "{$table} AS mrn 
		LEFT JOIN erp_inventory_mrn_detail AS mrn_detail ON mrn.mrn_id = mrn_detail.mrn_id
		LEFT JOIN erp_vendor AS vendor ON vendor.user_id = mrn.vendor_user
		LEFT JOIN erp_material AS material ON material.material_id = mrn_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function viewsstdata()
	{  
		// DB table to use
		$table = 'erp_inventory_sst';
		// Table's primary key
		$primaryKey = 'sst_id';

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes + the primary key column for the id
		$columns = array(
			array(
				'db' => 'sst.sst_id',
				'dt' => 'DT_RowId',
				'formatter' => function( $d, $row ) {
					// Technically a DOM id cannot start with an integer, so we prefix
					// a string. This can also be useful if you have multiple tables
					// to ensure that the id is unique with a different prefix
					return 'row_'.$d;
				}
			),
			array( 'db' => 'sst.project_id', 'dt' => 0 , 'field' => 'project_id' ),
			array( 'db' => 'sst.transfer_to', 'dt' => 1 , 'field' => 'transfer_to' ),
			array( 'db' => 'sst.sst_no',  'dt' => 2, 'field' => 'sst_no' ),
			array( 'db' => 'sst.sst_date',   'dt' => 3, 'field' => 'sst_date'),
			array( 'db' => 'sst.sst_time',   'dt' => 4, 'field' => 'sst_time'),
			array( 'db' => 'material.material_title',   'dt' => 5, 'field' => 'material_title'),
			array( 'db' => 'sst_detail.brand_id',   'dt' => 6, 'field' => 'brand_id'),
			array( 'db' => 'sst_detail.quantity',   'dt' => 7, 'field' => 'quantity'),
			array( 'db' => 'sst_detail.material_id',   'dt' => 8, 'field' => 'material_id'),
			array( 'db' => 'sst.sst_id',   'dt' => 9, 'field' => 'sst_id'),
			array( 'db' => 'sst_detail.sst_detail_id',   'dt' => 10, 'field' => 'sst_detail_id'),

		);//echo $userimage=get_user_meta(90, 'hmgt_user_avatar', true);
		//exit;
		//$table_usermeta = $wpdb->prefix . 'usermeta';
		
		// SQL server connection information
		$sql_details = $this->ERPfunction->ajax_db_config();


		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		$obj = new \SSP_sst();
		
		$joinQuery = "{$table} AS sst 
		LEFT JOIN erp_inventory_sst_detail AS sst_detail ON sst.sst_id = sst_detail.sst_id
		LEFT JOIN erp_material AS material ON material.material_id = sst_detail.material_id";
		
		echo json_encode(
			$obj->simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$this->request->session()->read('user_id') )
		);
		die;
		
	}
	
	public function getprojectpartywisewo()
	{		
		$project_id = $_REQUEST['project_id'];
		$party_id = $_REQUEST['party_id'];
		
		$result_arr = array();

		$erp_work_order = TableRegistry::get('erp_work_order'); 
		$wo = $erp_work_order->find("list",["keyField"=>"wo_no","valueField"=>"wo_no"])->where(["project_id"=>$project_id,"party_userid"=>$party_id]);
		$wo_list = "";
		if(!empty($wo))
		{
			$wo_list .= "<option value=''>--Select WO--</option>";
			foreach($wo as $key=>$value)
			{
				$wo_list .= "<option value='{$key}'>{$value}</option>";
			}
		}
		$result_arr[] = $wo_list;
		
		echo json_encode($result_arr);
		die();
	}
	
	public function getsubcontractdescriptionunit()
	{
		$category_id = $_REQUEST['category_id'];
		$category_master_table = TableRegistry::get('erp_category_master');
		$description = $category_master_table->find()->where(['cat_id'=>$category_id])->first();
		if(!empty($description))
		{
			echo $description->unit;
		}else{
			echo 'NA';
		}
		die;
	}
	
	public function getprojectwisesubcontractdescription()
	{		
		$project_id = $_REQUEST['project_id'];
		
		$result_arr = array();

		$erp_category_master = TableRegistry::get('erp_category_master'); 
		$descriptions = $erp_category_master->find("list",["keyField"=>"cat_id","valueField"=>"category_title"])->where(["project_id"=>$project_id]);
		$description_list = "";
		if(!empty($descriptions))
		{
			$description_list .= "<option value=''>--Select Option--</option>";
			foreach($descriptions as $key=>$value)
			{
				$description_list .= "<option value='{$key}'>{$value}</option>";
			}
		}
		$result_arr[] = $description_list;
		
		echo json_encode($result_arr);
		die();
	}
	
	public function generatesubontractbillno()
	{
		$project_id = $_REQUEST['project_id'];
		$party_id = $_REQUEST['party_id'];
		
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id])->first();		
		$result_arr = array();
		
		$result_arr['project_code'] = $project_data->project_code;
		
		/* $number1 = $this->ERPfunction->generate_auto_id($project_id,"erp_inventory_purhcase_request","prno","pr_id"); */
		$number1 = $this->ERPfunction->generate_auto_id_subcontractbill($project_id,$party_id,"erp_sub_contract","id","our_abstract_no");
		
		if(is_numeric($party_id))
		{
			$vendor_number = json_decode($this->ERPfunction->vendordetail($party_id));
			$vendor_number = $vendor_number->vendor_id;
			$split = explode("/",$vendor_number);
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$start = strlen($last_id) - 4;
  
			// substr returns the new string. 
			$last_digit = substr($last_id, $start); 
			$party_number = $split[1].'/'.$last_digit;
		}else{
			$agency_number = $party_id;
			$split = explode("/",$agency_number);
			$find = sizeof($split) - 1;
			$last_id = $split[$find];
			$start = strlen($last_id) - 4;
  
			// substr returns the new string. 
			$last_digit = substr($last_id, $start); 
			$party_number = $split[1].'/'.$last_digit;
		}
	
		$new_no = sprintf("%03d", $number1);
		$abstract_no = 'SCB/'.$party_number.'/'.$new_no;
		echo $abstract_no;
		die();
	}
	
	public function prpurchasefirstapprove() {
		$this->autoRender = false;
		$pr_id = $this->request->data["pr_id"];
		// debug($pr_id);die;
		$due_date = $this->request->data["due_date"];
		// debug(date('Y-m-d',strtotime($due_date)));die;
		$mt_tbl = TableRegistry::get("erp_inventory_pr_material");
		$query = $mt_tbl->query();
			$query->update()
    		->set(['purchase_first_approve'=>1,
			"purchase_first_approveby"=>$this->user_id,
			"purchase_first_approve_date"=>date("Y-m-d"),
			"due_date"=>date('Y-m-d',strtotime($due_date))])
    		->where(['pr_id' => $pr_id])
    		->execute();
		if($query) {
			echo true;
		}else {
			echo false;
		}
	}

	public function getdateapprove2date() {
		$pr_id = $this->request->data["pr_id"];
		$erp_inventory_pr_material = TableRegistry::get('erp_inventory_pr_material'); 
		if(!empty($pr_id)) {
			$row = $erp_inventory_pr_material->find()->where(["pr_id"=>$pr_id])->first();
			$due_date = $row->due_date;
		}else {
			$due_date = "";
		}
		$this->set("pr_id",$pr_id);
		$this->set("due_date",$due_date);
	}
	
	public function checkgrncreated()
	{		
		$material_id = $_REQUEST['material_id'];
		
		$erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail'); 
		$count = $erp_inventory_grn_detail->find()->where(["material_id"=>$material_id])->count();
		if($count)
		{
			echo true;
		}else{
			echo false;
		}
		die();
	}
	
	public function planningwoworktype()
	{		
		$project_id = $_REQUEST['project_id'];
		
		$result_arr = array();

		$erp_planning_work_head = TableRegistry::get('erp_planning_work_head'); 
		$result = $erp_planning_work_head->find("list",["keyField"=>"work_head_id","valueField"=>"work_head_title"])->where(["project_id"=>$project_id]);
		$work_types = "";
		if(!empty($result))
		{
			$work_types .= "<option value=''>--Select Option--</option>";
			foreach($result as $key=>$value)
			{
				$work_types .= "<option value='{$key}'>{$value}</option>";
			}
		}
		$result_arr[] = $work_types;
		
		echo json_encode($result_arr);
		die();
	}

	public function joinworkdescription() {
		$material_id = $_REQUEST['material_id'];
		$erp_planning_work_head = TableRegistry::get('erp_category_master');
		// Query copy from material joinmaterial() Function
		$description_list = $erp_planning_work_head->find("list",["keyField"=>"cat_id","valueField"=>"category_title"])->where(["cat_id !=" => $material_id,"type =" => "subcontractbill_option"]);
		
		$this->set("description_list",$description_list);
		$this->set("material_id",$material_id);
	}

	public function joinworksubgroup() {
		$material_id = $_REQUEST['material_id'];
		$erp_planning_work_head = TableRegistry::get('erp_planning_work_head');
		// Query copy from material joinworkdescription() Function
		$description_list = $erp_planning_work_head->find("list",["keyField"=>"work_head_id","valueField"=>"work_head_title"])->where(["work_head_id !=" => $material_id]);
		
		$this->set("description_list",$description_list);
		$this->set("material_id",$material_id);
	}


	public function approveplanningwo()
	{
		$wo_id = $_REQUEST['wo_id'];
		
		$wod_tbl = TableRegistry::get('erp_planning_work_order_detail');
		
		$date=date('Y-m-d H:i:s');
		$user = $this->request->session()->read('user_id');
		$query = $wod_tbl->query();
		$approve = $query->update()
						->set(['approved'=>1,
						"approved_date"=>$date,
						'approved_by'=>$user])
						->where(['wo_id' => $wo_id])
						->execute();
						
		if($approve)
		{	
			$party_emails = array();
			$wo_tbl = TableRegistry::get('erp_planning_work_order');
			$row = $wo_tbl->get($wo_id);
			$party_user_id = $row->party_userid;
			if(is_numeric($party_user_id))
			{
				$party_email = $this->ERPfunction->get_vendor_email($party_user_id);
			}else{
				$party_email = $this->ERPfunction->get_agency_email($party_user_id);
			}
			$party_email = explode(",",$party_email);
			if(!empty($party_email))
			{
				foreach($party_email as $mail)
				{
					$party_emails[] = $mail;
				}
			}
			
			$project_id = $row->project_id;
			$row['approved_status'] = 1;
			$row['updated'] = 0;
			$row['ammend_approve'] = 1;
			$row['approved_date'] = $date;
			$row['approved_by'] = $user;
			
			if($wo_tbl->save($row))
			{
				$mail_enable = $this->ERPfunction->get_planningwo_mail_status($wo_id);
				$email_list = $this->ERPfunction->get_mail_list_by_project_wo($project_id,$mail_enable,'"wo_notification"');
				
				if($mail_enable == 1 || $mail_enable == 2 )
				{
					$emails = array_merge($email_list,$party_emails);
					$emails = array_unique($emails);
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					$emails = array_filter($emails, function($value) { return $value !== NULL; });
				}
				else{
					$emails = array_unique($email_list);
				}
												
				// Check the party email format are correct or not? code start
				$email_correct = 1;
				$wrong_email = array();
				foreach($party_emails as $value)
				{
					if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
					 
					} else {
						$email_correct = 0;
						$wrong_email[] = $value;
					}
				}
				
				// Check the party email format are correct or not? code end
				if($email_correct)
				{
					if(!empty($emails))
					{
						$emails = implode(",",$emails);		
						$this->ERPfunction->planningwo_approve_mail($emails,$wo_id);
						
						$this->Flash->success(__('Work Order Approved Successfully', null), 
								'default', 
								array('class' => 'success'));
						$this->redirect(["controller"=>"contract","action"=>"approvewo"]);
					}
				}else{
					$query1 = $wod_tbl->query();
					$disapprove = $query1->update()
									->set(['approved'=>0,
									"approved_date"=>'',
									'approved_by'=>''])
									->where(['wo_id' => $wo_id])
									->execute();
					if($disapprove)
					{
						$row1 = $wo_tbl->get($wo_id);
						$row1['approved_status'] = 0;
						$row1['approved_date'] = '';
						$row1['approved_by'] = '';
						$wo_tbl->save($row1);
					}
					debug($wrong_email);die;
					echo "email_issue";die;
				}
			}
			
		}
		
		die;
	}

	public function approveammendwo()
	{
		$wo_id = $_REQUEST['wo_id'];
		
		$wo_tbl = TableRegistry::get('erp_planning_work_order');
		$wod_tbl = TableRegistry::get('erp_planning_work_order_detail');
		
		$date=date('Y-m-d H:i:s');
		$user = $this->request->session()->read('user_id');
		$query = $wo_tbl->query();
		$approve = $query->update()
						->set(['ammend_approve'=>1,
						"ammend_approve_date"=>$date,
						'ammend_approve_by'=>$user,
						'updated'=>0])
						->where(['wo_id' => $wo_id])
						->execute();
						
		if($approve)
		{	
			$party_emails = array();
			$wo_tbl = TableRegistry::get('erp_planning_work_order');
			$row = $wo_tbl->get($wo_id);
			$party_user_id = $row->party_userid;
			if(is_numeric($party_user_id))
			{
				$party_email = $this->ERPfunction->get_vendor_email($party_user_id);
			}else{
				$party_email = $this->ERPfunction->get_agency_email($party_user_id);
			}
			$party_email = explode(",",$party_email);
			if(!empty($party_email))
			{
				foreach($party_email as $mail)
				{
					$party_emails[] = $mail;
				}
			}
			
			// $project_id = $row->project_id;
			$row['approved_status'] = 1;
			$row['approved_date'] = $date;
			$row['approved_by'] = $user;
			
			if($wo_tbl->save($row))
			{
				$mail_enable = $this->ERPfunction->get_planningwo_mail_status($wo_id);
				$email_list = $this->ERPfunction->get_mail_list_by_project_wo($project_id,$mail_enable,'"wo_notification"');
				
				if($mail_enable == 1 || $mail_enable == 2 )
				{
					$emails = array_merge($email_list,$party_emails);
					$emails = array_unique($emails);
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					$emails = array_filter($emails, function($value) { return $value !== NULL; });
				}
				else{
					$emails = array_unique($email_list);
				}
				
												
				// Check the party email format are correct or not? code start
				$email_correct = 1;
				$wrong_email = array();
				foreach($party_emails as $value)
				{
					if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
					 
					} else {
						$email_correct = 0;
						$wrong_email[] = $value;
					}
				}
				
				// Check the party email format are correct or not? code end
				if($email_correct)
				{
					if(!empty($emails))
					{
						$emails = implode(",",$emails);		
						$this->ERPfunction->planningwo_approve_mail($emails,$wo_id);
						
						$this->Flash->success(__('Work Order Approved Successfully', null), 
								'default', 
								array('class' => 'success'));
						$this->redirect(["controller"=>"contract","action"=>"approvewo"]);
					}
				}else{
					$query1 = $wod_tbl->query();
					$disapprove = $query1->update()
									->set(['approved'=>1,
									"approved_date"=>'',
									'approved_by'=>''])
									->where(['wo_id' => $wo_id])
									->execute();
					if($disapprove)
					{
						$row1 = $wo_tbl->get($wo_id);
						$row1['approved_status'] = 0;
						$row1['approved_date'] = '';
						$row1['approved_by'] = '';
						$wo_tbl->save($row1);
					}
					debug($wrong_email);die;
					echo "email_issue";die;
				}
			}
			
		}
		
		die;
	}

	public function approveammendpo() {
		$po_id = $_REQUEST['po_id'];
		
		$po_tbl = TableRegistry::get('erp_inventory_po');
		$pod_tbl = TableRegistry::get('erp_inventory_po_detail');
		
		$date=date('Y-m-d H:i:s');
		$user = $this->request->session()->read('user_id');
		$query = $po_tbl->query();
		$approve = $query->update()
			->set(['ammend_approve'=>1,
			"ammend_approve_date"=>$date,
			'ammend_approve_by'=>$user,
			'updated'=>0])
			->where(['po_id' => $po_id])
			->execute();
		$podQuery = $pod_tbl->query();
		$verified = $podQuery->update()
			->set(['verified'=>1,
			"verified_date"=>$date,
			'verified_by'=>$user])
			->where(['po_id' => $po_id])
			->execute();	
		if($approve && $verified)
		{	
			$party_emails = array();
			$po_tbl = TableRegistry::get('erp_inventory_po');
			$row = $po_tbl->get($po_id);
			$project_id = $row -> project_id;
			$po_date = $row-> po_date;
			$party_user_id = $row -> vendor_userid;
			$po_no = $row -> po_no;
			if(is_numeric($party_user_id))
			{
				$party_email = $this->ERPfunction->get_vendor_email($party_user_id);
			}else{
				$party_email = $this->ERPfunction->get_agency_email($party_user_id);
			}
			$party_email = explode(",",$party_email);
			if(!empty($party_email))
			{
				foreach($party_email as $mail)
				{
					$party_emails[] = $mail;
				}
			}
			
			// $project_id = $row->project_id;
			$row['approved_status'] = 1;
			$row['approved_date'] = $date;
			$row['approved_by'] = $user;
			
			$mm_email = $this->ERPfunction->get_email_of_mm_by_project($project_id);
			$billingeng_email = $this->ERPfunction->get_email_of_billingengineer_by_project($project_id);
			$mm_email = array_merge($mm_email,$billingeng_email);
			
			$emails_norate = array_merge($mm_email,$emails_norate);
			$mm_email = array_unique($emails_norate); /*remove duplicate email ids */
			$mm_email = array_filter($mm_email, function($value) { return $value !== ''; });
			$po_vendor_email = $this->ERPfunction->get_po_vendor_id($po_id);

			if($po_tbl->save($row))
			{
				$mail_enable = $this->ERPfunction->get_planningpo_mail_status($po_id);
				$email_list = $this->ERPfunction->get_mail_list_by_project($project_id,$po_id,$mail_enable,'"po_notification"');
				
				if($mail_enable == 1 || $mail_enable == 2 )
				{
					$emails = array_merge($email_list,$party_emails);
					$emails = array_unique($emails);
					$emails = array_filter($emails, function($value) { return $value !== ''; });
					$emails = array_filter($emails, function($value) { return $value !== NULL; });
				}
				else{
					$emails = array_unique($email_list);
				}
				
												
				// Check the party email format are correct or not? code start
				$email_correct = 1;
				$wrong_email = array();
				foreach($party_emails as $value)
				{
					if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
					 
					} else {
						$email_correct = 0;
						$wrong_email[] = $value;
					}
				}
				// Check the party email format are correct or not? code end

				// if($email_correct)
				// {
				// 	if(!empty($emails))
				// 	{
				// 		$emails = implode(",",$emails);		
				// 		$this->ERPfunction->planningwo_approve_mail($emails,$po_id);
						
				// 		$this->Flash->success(__('Work Order Approved Successfully', null), 
				// 				'default', 
				// 				array('class' => 'success'));
				// 		$this->redirect(["controller"=>"contract","action"=>"approvewo"]);
				// 	}
				// }
				if($email_correct) {
					if(!empty($email_list)) {
						$pdpmcm_email = implode(",",$email_list);		
						$view_po = $po_id;
						$this->ERPfunction->mail_po_withrateammend($pdpmcm_email,$view_po,$po_no,$project_id,$po_date);
					}
					if($mail_enable!=0) {
						if(!empty($mm_email)) {
							$mm_email = implode(",",$mm_email);		
							$view_po = $po_id;		
							$this->ERPfunction->mail_po_withoutrateammend($mm_email,$view_po,$po_no,$project_id,$po_date);
						}
					}
				}else{
					$query1 = $pod_tbl->query();
					$disapprove = $query1->update()
									->set(['approved'=>1,
									"approved_date"=>'',
									'approved_by'=>''])
									->where(['po_id' => $po_id])
									->execute();
					if($disapprove)
					{
						$row1 = $po_tbl->get($po_id);
						$row1['approved_status'] = 0;
						$row1['approved_date'] = '';
						$row1['approved_by'] = '';
						$po_tbl->save($row1);
					}
					debug($wrong_email);die;
					echo "email_issue";die;
				}
			}
			
		}
		
		die;
	}

	public function getplanningwodetails()
	{
		$project_id = $_REQUEST['project_id'];
		$party_id = $_REQUEST['party_id'];
		
		$wo_table = TableRegistry::get('erp_planning_work_order'); 
		$wo_data = $wo_table->find()->where(['project_id'=>$project_id,'party_userid'=>$party_id,'approved_status'=>1,'ammend_approve'=>1])->first();

		$result_arr = array();
		if(!empty($wo_data))
		{
			$result_arr['wo_no'] = $wo_data->wo_no;
			$result_arr['work_type'] = $this->ERPfunction->get_planning_work_head_title($wo_data->work_type);
		}else{
			$result_arr['wo_no'] = "";
			$result_arr['work_type'] = "";
		}		
		
		echo json_encode($result_arr);
		die();
	}

	public function deletepurchasepoalert() {
		$this->autoRender = false;
        $poId = $_REQUEST['poId'];
		$tbl_po = TableRegistry::get("erp_inventory_po_detail");
		// $data = $tbl_po->get($poId);
		$data = $tbl_po->find()->where(["po_id" =>$poId])->hydrate(false)->toArray();
		// debug($data);die;
		$result = $tbl_po->delete($data);
		if($result) {
			$this->Flash->success(__('P.O. Deleted Successfully.', null),
            'default',
            array('class' => 'success'));
	        // $this->redirect(["controller" => "Purchase","action" => "approvedpr"]);
		}
    }

	public function getvendorgstno() {
		$this->autoRender = false;
		$gstNo = $_REQUEST['gstNo'];
		$erpVendor = TableRegistry::get("erp_vendor");
		$vendor_data = $erpVendor->find()->select(['gst_no'])->where(['gst_no' =>$gstNo])->hydrate(false);
		foreach($vendor_data as $data) {
			$gstno = $data['gst_no'];
		}
		$this->response->body(($gstno));
		return $this->response;
	}

	public function partygstno() {
		$this->autoRender = false;
		$partyId = $_REQUEST['party_id'];
		$erpVendor = TableRegistry::get("erp_vendor");
		$vendorData = $erpVendor->find()->select(['gst_no'])->where(['user_id' => $partyId])->hydrate(false)->limit(1);
		foreach($vendorData as $data) {
			$gstNo = $data['gst_no'];
		}
		$this->response->body(($gstNo));
		return $this->response;
	}

	public function acceptbillsremark() {
		$uid = $_REQUEST['uid'];
		$erpInwardBill = TableRegistry::get("erp_inward_bill"); 
		if(!empty($uid)) {
			$row = $erpInwardBill->get($uid);
			$remark_data = $row->accept_bill_remarks;
		}else {
			$remark_data = "";
		}
		$this->set("accept_bill_remarks",$remark_data);
		$this->set("uid",$uid);
	}

	public function viewpendingdelivery(){
		$projectId = $_REQUEST['project_id'];
		$materialId = $_REQUEST['material_id'];
		$erp_inventory_po = TableRegistry::get('erp_inventory_po');
			$erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail'); 

			$or = array();
			$or['erp_inventory_po.project_id IN'] =  $projectId;
			$or['erp_inventory_po_detail.material_id'] = $materialId;
			$or['erp_inventory_po_detail.approved ='] = 1;
			$or['erp_inventory_po.po_purchase_type ='] = 'po';

			$keys = array_keys($or,"");				
			foreach ($keys as $k){
				unset($or[$k]);
			}
			$result = $erp_inventory_po->find()->select($erp_inventory_po);
			$result = $result->innerjoin(["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
				["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
				->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date'=>'DESC'])->limit(10)->hydrate(false)->toArray();
				// var_dump($result);die;
			$this->set("result",$result);
		// debug($rowData);die;
	}

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}

?>