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

use Cake\Datasource\ConnectionManager;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry; 
use Cake\Routing\Router;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ErpapiController extends AppController
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
		$this->loadComponent('ERPfunction');
	}
	
	public function index()
	{
		// echo "yes";
		$this->autoRender = false;			
		$response = array();
		
		if(isset($_GET["erp-api-att"]) && $_GET["erp-api-att"] == "dayin" && isset($_GET["id"]))
		{
			$tbl = TableRegistry::get("erp_attendance");
			$user_id = $_GET["id"];
			$date = date("Y-m-d");
			
			if($user_id != "")
			{							
				$fnd = $tbl->find()->where(["user_id"=>$user_id,"attendance_date"=>$date]);
				$count = $fnd->count();
				if($count == 1)
				{
					$fnd = $fnd ->hydrate(false)->toArray();
					if($fnd[0]['day_out_time'] == "")
					{
						$response["error"] = "Day in already punch at ". date('h:i A',strtotime($fnd[0]['day_in_time']))."";
						$response["status"] = "0";
						$response["result"] = "";
					}else{
						$response["error"] = "Day already ended at ". date('h:i A',strtotime($fnd[0]['day_out_time']))."";
						$response["status"] = "4";
						$response["result"] = "";
					}
				}
				else{
					$row = $tbl->newEntity();
					$data["user_id"] =  $user_id;
					$data["attendance_date"] = $date;
					$data["day_in_time"] = date("h:i A");
					$row = $tbl->patchEntity($row,$data);
					$tbl->save($row);
					$this->ERPfunction->save_attendance_detail($user_id,date("Y-m-d"),"day_in");
					
					$response["error"] = "";
					$response["status"] = "1";
					$response["result"] = "Your Day started successfully";					
				}
				
				// echo json_encode($response);
				// exit;
			}
			else{
				$response["error"] = "Empty user id";
				$response["status"] = "2";
				$response["result"] = "";
				// echo json_encode($response);
			}
		}
		else if(isset($_GET["erp-api-att"]) && $_GET["erp-api-att"] == "dayout" && isset($_GET["id"]))
		{	
			$tbl = TableRegistry::get("erp_attendance");
			$user_id = $_GET["id"];
			$date = date("Y-m-d");
			
			if($user_id != "")
			{							
				$row = $tbl->find()->where(["user_id"=>$user_id,"attendance_date"=>$date]);
				$count = $row->count();
				if($count == 0)
				{
					$response["error"] = "Your day not started yet.";
					$response["status"] = "3";
					$response["result"] = "";					
				}
				else{
					$row = $row->hydrate(false)->toArray();
					if($row[0]["day_out_time"] == "")
					{
						$day_out = date("h:i A");
						$working_hours = $this->counthours($user_id,date("Y-m-d"),$day_out);
						$query = $tbl->find();
						$query->update()->set(["day_out_time"=>$day_out,"working_hours"=>$working_hours])->where(["user_id"=>$user_id,"attendance_date"=>$date])->execute();
						
						$response["error"] = "";
						$response["status"] = "1";
						$response["result"] = "Your Day ended successfully";
						$this->ERPfunction->save_attendance_detail($user_id,date("Y-m-d"),"day_out",$working_hours);
						
					}else{
						$response["error"] = "Your day already ended at {$row[0]["day_out_time"]}";
						$response["status"] = "4";
						$response["result"] = "";	
					}
				}
			}
			else{				
				$response["error"] = "Empty user id";
				$response["status"] = "2";
				$response["result"] = "";
			}
		}
		else if(isset($_GET["erp-api-att"]) && $_GET["erp-api-att"] == "enroll" && isset($_GET["id"]))
		{
			$user_id = $_GET["id"];
			if($user_id == "")
			{
				$response["error"]="Sorry,No User Found.";
				$response["status"]="5";
				$response["result"]= "";
			}
			else{
				$user_tbl = TableRegistry::get("erp_users");
				$getdata = $user_tbl->find("all")->where(["user_id"=>$user_id,"employee_no !="=>""]);
				$cnt= $getdata->count();
				$userdata = $getdata->hydrate(false)->toArray();
				if($cnt > 0 )
				{
					$data["id"] = $user_id;
					// $data["employee_no"] = $userdata[0]["employee_no"];
					$data["first_name"] = $userdata[0]["first_name"];
					$data["last_name"] = $userdata[0]["last_name"];
					$data["email"] = $userdata[0]["email_id"];
					
					$response["error"]="";
					$response["status"]="1";
					$response["result"]=$data;
				}
				else{
					$response["error"]="Sorry,No User Found.";
					$response["status"]="5";
					$response["result"]= "";
				}
			}
			
		}		
		else if(isset($_GET["erp-api-att"]) && $_GET["erp-api-att"] == "upload")
		{
			$response = $this->upload_image($_FILES);
			if(is_array($response))
			{
				echo json_encode($response);
			}
			else
			{
				header("HTTP/1.1 401 Unauthorized");
			}
			die();
		}		
		else if(isset($_GET["erp-api-att"]) && $_GET["erp-api-att"] == "userlist")
		{
			
			$response = $this->get_users();
			if(is_array($response))
			{
				$json = json_encode($response);
				$json = str_replace("\/","/",$json);
				echo $json;
			}
			else
			{
				header("HTTP/1.1 401 Unauthorized");
			}
			die();
		}
		else if(isset($_GET["erp-api-att"]) && $_GET["erp-api-att"] == "delete")
		{			
			
			if(!isset($_POST["id"]) || empty($_POST["id"]))
			{
				$response["error"]="Sorry,No User Found.";
				$response["status"]="5";
				$response["result"]= "";
			}
			else
			{
				$ids = $_POST["id"];
				foreach($ids as $uid)
				{
					$file = WWW_ROOT ."thumbs/TemplateFP_{$uid}_finger0_1.pkc";
					if(file_exists($file))
					{						
						unlink($file);
					}
				}
				$response["error"]="";
				$response["status"]="1";
				$response["result"]= "User thumb entry deleted successfully";
			}
		}
		else{
			
			$response["error"] = "No parameter data provided";
			$response["status"] = "6";
			$response["result"] = "";
			
		}
		echo json_encode($response);
		exit;
	}
	
	private function get_users()
	{
		$user_tbl = TableRegistry::get("erp_users");
		$data = $user_tbl->find("all")->where(["employee_no !="=>""]);
		
		if(!empty($data))
		{ 
			$detail = array();		
			$i = 0 ;
			foreach($data as $emp)
			{
						
				$detail[$i]["id"] = $emp["user_id"];
				$detail[$i]["first_name"] = $emp["first_name"];
				$detail[$i]["last_name"] = $emp["last_name"];
				$detail[$i]["email"] = $emp["email_id"];				
				$url = Router::url('/', true);
				
				/* $file_path = WWW_ROOT ."thumbs\TemplateFP_{$emp["user_id"]}_finger0_1.pkc";  FOR LOCAL TEST*/
				$file_path = WWW_ROOT ."thumbs/TemplateFP_{$emp["user_id"]}_finger0_1.pkc";
				if(file_exists($file_path))
				{
					$file_url = $file_path = $url ."thumbs/TemplateFP_{$emp["user_id"]}_finger0_1.pkc";
					$detail[$i]["pic"] = $file_path;
				}else{
					$detail[$i]["pic"] = "";
				}
				
				$i++;
			}
			
			$response["error"]="";
			$response["status"]="1";
			$response["result"]=$detail;
			
		}
		else{
			$response["error"]="No User Found";
			$response["status"]="0";
			$response["result"]="";
		}
		return $response;
	}
	
	public function counthours($user_id,$date,$day_out_time)
	{
		/* $att_tbl = TableRegistry::get("erp_attendance");
		$row = $att_tbl->find()->where(["user_id"=>$user_id,"attendance_date"=>$date])->hydrate(false)->toArray();
		
		$from_time = strtotime($row[0]["day_in_time"]);
		$to_time = strtotime($day_out_time);
		$temp = round(abs($to_time - $from_time) / 3600,2);
		$hours_diff=str_replace('.',':',$temp);		
		return $hours_diff;
		*/
		
		$att_tbl = TableRegistry::get("erp_attendance");
		$row = $att_tbl->find()->where(["user_id"=>$user_id,"attendance_date"=>$date])->hydrate(false)->toArray();		
		
		$datetime1 = strtotime($row[0]["day_in_time"]);
		$datetime2 = strtotime($day_out_time);
		$dateDiff = intval(($datetime2-$datetime1)/60);
		$hours = intval($dateDiff/60);
		$minutes = $dateDiff%60;
		$hours_diff = $hours.":".$minutes;
		return $hours_diff;
	}
	
	public function upload_image($file)
	{
		if(isset($file['pic']['name']))
		{
			// $new_name = "";
			$file = $file["pic"];
			$img_name = $file["name"];	
			if($img_name != "")
			{
				$tmp_name = $file["tmp_name"];					
				// $ext = substr(strtolower(strrchr($img_name, '.')), 1); 
				// $new_name = time() . "_" . rand(000000, 999999). "." . $ext;		
				if(move_uploaded_file($tmp_name,WWW_ROOT . "/thumbs/".$img_name))
				{
					$response["error"]="";
					$response["status"]="1";
					$response["result"]="File Uploaded successfully";
				}else{
						$response["error"]="Error!File could not uploaded.Try again.";
						$response["status"]="0";
						$response["result"]="";
				}
			}
		}
		else{
				$response["error"]="Provide File Name";
				$response["status"]="0";
				$response["result"]="";
		}
		// return $response;
		return $response;
	}

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}
