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
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PurchaseController extends AppController
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
        $this->user_id = $this->request->session()->read('user_id');
        $this->role = $this->Usermanage->get_user_role($this->user_id);
        $this->rights = $this->Usermanage->purchase_access_right();
        $action = $this->request->action;

        if (isset($this->rights[$action][$this->role]) && $action != "printmanualporecord" && $action != "printmanualporecordnorate" && $action != "mailporecord2") {
            $is_capable = $this->rights[$action][$this->role];
        } else { $is_capable = 0;}
        $this->set('is_capable', $is_capable);
    }

    public function index()
    {

    }

    public function materiallist() /*moved from action index to here*/
    {
        $users_table = TableRegistry::get('erp_users');
        $user_list = $users_table->find()->where(array('role' => 'ceo'));
        $this->set('user_list', $user_list);
    }

    public function category()
    {
        //$category = $this->ERPfunction->material_category();
        $erp_material_cats = TableRegistry::get('erp_material_cat');
        $category = $erp_material_cats->find();
        $this->set('category', $category);
    }
    public function addmaterial($material_id = null)
    {
        ini_set('memory_limit', '-1');
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'inventory') !== false) {
            $back_url = 'inventory';
            $back_page = 'index';
        } else {
            $back_url = 'purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        $erp_projects = TableRegistry::get('erp_projects');
        $projects = $erp_projects->find()->order(array(
            'project_id Desc',
        ));
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        $category = $this->ERPfunction->rolewise_vendor_group($this->role);
        $this->set('category', $category);

        $table_category = TableRegistry::get('erp_category_master');
        $unit_list = $table_category->find()->where(array('type' => 'unit'));
        $this->set('unitlist', $unit_list);
        $this->set("back", "index");

        $stockHistory = TableRegistry::get('erp_stock_history');
        $stockDetails = $stockHistory->find();
        $this->set("stockDetails", $stockDetails);
        // $material_item_code = $this->ERPfunction->generate_autoid('MT-');
        if (isset($material_id)) {

            $user_action = 'edit';

            $material_data = $erp_material->get($material_id);

            $this->set('material_data', $material_data);
            $this->set('form_header', 'Edit Material');
            $this->set('button_text', 'Update Material');
            $this->set("back", "viewmaterial");

        } else {
            $user_action = 'insert';
            // $this->set('material_item_code',$material_item_code);
            $this->set('form_header', 'Add Material');
            $this->set('button_text', 'Add Material');
        }
        $this->set('user_action', $user_action);
        if ($this->request->is('post')) {
            // debug($this->request->data());die;
            if ($user_action == 'edit') {
                $updated_data = $this->request->data;
                // if($updated_data["material_code"] == 16) /* Add TEMP material to seperate table */
                // {
                // $del_row = $erp_material->get($material_id);
                // $erp_material->delete($del_row);

                // $table_field = $erp_material->newEntity();
                // $updated_data['created_date']=date('Y-m-d H:i:s');
                // $updated_data['created_by']=$this->request->session()->read('user_id');
                // $updated_data['status']=1;

                // $new_data=$erp_material->patchEntity($table_field,$updated_data);
                // $erp_material->save($new_data);

                // $tmp_tbl = TableRegistry::get("erp_material_temp");
                // $tmp_field = $tmp_tbl->newEntity();
                // $updated_data["material_id"] = $new_data->material_id;
                // $tmp_data=$tmp_tbl->patchEntity($tmp_field,$updated_data);
                // $tmp_tbl->save($tmp_data);

                // $this->Flash->success(__('Record Update Successfully', null),
                // 'default',
                // array('class' => 'success'));

                // echo "<script>window.close();</script>";
                // }
                // else if($updated_data["material_code"] == 17) /* Add TEMP material to seperate table */
                // {
                // $del_row = $erp_material->get($material_id);
                // $erp_material->delete($del_row);

                // $table_field = $erp_material->newEntity();
                // $updated_data['created_date']=date('Y-m-d H:i:s');
                // $updated_data['created_by']=$this->request->session()->read('user_id');
                // $updated_data['status']=1;

                // $new_data=$erp_material->patchEntity($table_field,$updated_data);
                // $erp_material->save($new_data);

                // $tmp_tbl = TableRegistry::get("erp_material_temp");
                // $tmp_field = $tmp_tbl->newEntity();
                // $updated_data["material_id"] = $new_data->material_id;
                // $tmp_data=$tmp_tbl->patchEntity($tmp_field,$updated_data);
                // $tmp_tbl->save($tmp_data);

                // $this->Flash->success(__('Record Update Successfully', null),
                // 'default',
                // array('class' => 'success'));

                // echo "<script>window.close();</script>";
                // }
                // else
                // {
                $old_project_id = $updated_data['old_project_id'];
                $project_id = $updated_data['project_id'];
                $material_code = $updated_data["material_code"];

                if ($old_project_id != $project_id) {
                    if ($project_id) {
                        /* Get Next Sequence Number */
                        $seq_no = $this->ERPfunction->generate_auto_id($project_id, "erp_material", "material_id", "material_item_code");
                        $seq_no = sprintf("%09d", $seq_no);
                        /* Get Next Sequence Number */

                        /* Get Project Number */
                        $project_code = $this->ERPfunction->get_projectcode($project_id);
                        $c = explode("/", $project_code);
                        $project_code_number = ($c[2]) ? $c[2] : "000";
                        $material_item_code = "YNEC/MT/TMP/{$project_code_number}/{$seq_no}";
                        /* Get Project Number */
                    } else {
                        /* Get Next Sequence Number */
                        $seq_no = $this->get_last_material_id();
                        // $seq_no = $this->ERPfunction->generate_auto_id($project_id,"erp_material","material_id","material_item_code");
                        // $seq_no = sprintf("%09d", $seq_no);
                        /* Get Next Sequence Number */

                        $material_item_code = 'YNEC/MT/' . $this->ERPfunction->get_vendor_group_code($material_code) . '/' . $seq_no;
                    }
                    $updated_data['material_item_code'] = $material_item_code;

                }
                $material_data['material_sub_group'] = $updated_data['material_sub_category'];
                $material_data = $erp_material->patchEntity($material_data, $updated_data);

                if ($erp_material->save($material_data)) {
                    $this->Flash->success(__('Record Update Successfully', null),
                        'default',
                        array('class' => 'success'));
                    echo "<script>window.close();</script>";
                }
                /* $this->redirect(array("controller" => "Purchase","action" => "viewmaterial"));     */
                // }
            } else {
                $material_code = $this->request->data["material_code"];
                $project_id = $this->request->data["project_id"];
                // if($material_code == 16)
                // {
                // $number1 = $this->ERPfunction->generate_auto_id_material_temp($material_code);
                // $new_prno = sprintf("%09d", $number1);
                // $material_item_code = 'YNEC/MT/'.$this->ERPfunction->get_vendor_group_code($material_code ).'/'.$new_prno;

                // }else{
                // $prepare_count = $this->get_last_material_id();
                // $new_prno = sprintf("%09d", $prepare_count + 1);
                // $material_item_code = 'YNEC/MT/'.$this->ERPfunction->get_vendor_group_code($material_code ).'/'.$new_prno;
                // }
                if ($project_id) {
                    /* Get Next Sequence Number */
                    $seq_no = $this->ERPfunction->generate_auto_id($project_id, "erp_material", "material_id", "material_item_code");
                    $seq_no = sprintf("%09d", $seq_no);
                    /* Get Next Sequence Number */

                    /* Get Project Number */
                    $project_code = $this->ERPfunction->get_projectcode($project_id);
                    $c = explode("/", $project_code);
                    $project_code_number = ($c[2]) ? $c[2] : "000";
                    $material_item_code = "YNEC/MT/TMP/{$project_code_number}/{$seq_no}";
                    /* Get Project Number */
                } else {
                    /* Get Next Sequence Number */
                    // $seq_no = $this->ERPfunction->generate_auto_id($project_id,"erp_material","material_id","material_item_code");
                    // $seq_no = sprintf("%09d", $seq_no);
                    $seq_no = $this->get_last_material_id();
                    /* Get Next Sequence Number */

                    $material_item_code = 'YNEC/MT/' . $this->ERPfunction->get_vendor_group_code($material_code) . '/' . $seq_no;
                }

                $material_code = $this->request->data["material_code"];
                $material_title = $this->request->data["material_title"];

                $check = $erp_material->find("all")->where(["material_code" => $material_code, "material_title" => $material_title])->count();
                if ($check == 0) {
                    $table_field = $erp_material->newEntity();
                    $this->request->data['material_item_code'] = $material_item_code;
                    $this->request->data['material_sub_group'] = $this->request->data['material_sub_category'];
                    $this->request->data['created_date'] = date('Y-m-d H:i:s');
                    $this->request->data['created_by'] = $this->request->session()->read('user_id');
                    $this->request->data['status'] = 1;

                    $new_data = $erp_material->patchEntity($table_field, $this->request->data);
                    if ($erp_material->save($new_data)) {
                        if ($material_code == 16) /* Add TEMP material to seperate table */ {
                            $tmp_tbl = TableRegistry::get("erp_material_temp");
                            $tmp_field = $tmp_tbl->newEntity();
                            $this->request->data["material_id"] = $new_data->material_id;
                            $tmp_data = $tmp_tbl->patchEntity($tmp_field, $this->request->data);
                            $tmp_tbl->save($tmp_data);
                        }
                        if ($material_code == 17) /* Add TEMP material to seperate table */ {
                            $tmp_tbl = TableRegistry::get("erp_material_temp");
                            $tmp_field = $tmp_tbl->newEntity();
                            $this->request->data["material_id"] = $new_data->material_id;
                            $tmp_data = $tmp_tbl->patchEntity($tmp_field, $this->request->data);
                            $tmp_tbl->save($tmp_data);
                        }
                        $this->Flash->success(__('Material added with code ' . $material_item_code, null),
                            'default',
                            array('class' => 'success'));
                    }

                    $this->redirect(array("controller" => "Purchase", "action" => "viewmaterial"));
                } else {
                    $this->Flash->success(__('Duplicate record, Please try again.', null),
                        'default',
                        array('class' => 'danger'));
                }
            }
        }

    }
    public function viewmaterial($material_code = null)
    {
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'inventory') !== false) {
            $back_url = 'inventory';
            $back_page = 'index';
        } else {
            $back_url = 'purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        $role = $this->role;
        $user_id = $this->request->session()->read('user_id');
        
        $this->set('role', $this->role);

        $category = $this->ERPfunction->material_category();
        $this->set('category', $category);

        $groups = $this->ERPfunction->vendor_group();
        $this->set('groups', $groups);
        $search_data = array();
        if ($this->request->is("post")) {
            $post = $this->request->data;
            if (isset($post["search1"])) {
                $post = $this->request->data;
                $user_material_id = $this->ERPfunction->get_user_material_id($user_id);
                $user_material_id = json_decode($user_material_id);
                $or = array();
                $or["material_item_code LIKE"] = (!empty($post["material_code"])) ? "%{$post["material_code"]}%" : null;
                $or["material_code IN"] = (!empty($post["material_group"]) && $post["material_group"][0] != "All") ? $post["material_group"] : null;
                $or["material_title LIKE"] = (!empty($post["material_name"])) ? "%{$post["material_name"]}%" : null;
                if ($this->Usermanage->project_alloted($role) == 1) {
                    $or["material_id IN"] = $user_material_id;
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $material_list = $erp_material->find()->where($or);
                $this->set('material_list', $material_list);
            }

            if (isset($this->request->data["export_csv"])) {
                $post = $this->request->data;
                // debug($post);die;

                $or = array();
                $material_group = explode(",", $post["e_material_group"]);
                $or["material_item_code ="] = (!empty($post["e_material_code"])) ? $post["e_material_code"] : null;
                $or["material_title ="] = (!empty($post["e_material_name"])) ? $post["e_material_name"] : null;
                $or["material_code IN"] = (!empty($post["e_material_group"]) && $material_group[0] != "All") ? $material_group : null;

                if ($role == 'deputymanagerelectric') {
                    $or["material_code IN"] = array('6', '7', '10', '15');
                }

                if ($this->Usermanage->project_alloted($role) == 1) {
                    $meterial_ids = $this->ERPfunction->get_user_material_id($user_id);
                    $meterial_ids = json_decode($meterial_ids);
                    $or["material_id IN"] = $meterial_ids;
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                if (!empty($or)) {
                    $material_list = $erp_material->find()->where($or)->hydrate(false)->toArray();
                } else {
                    $material_list = $erp_material->find()->hydrate(false)->toArray();
                }

                $rows = array();
                $rows[] = array("Material Code", "Material Group", "Material Sub-Group", "Material Name", "Material Description", "Unit", "Project", "Consume Type");

                foreach ($material_list as $retrive_data) {
                    $export = array();
                    $export[] = $retrive_data["material_item_code"];
                    $export[] = $this->ERPfunction->get_vendor_group_name($retrive_data['material_code']);
                    $export[] = $this->ERPfunction->get_material_subgroup_title($retrive_data['material_sub_group']);
                    $export[] = $retrive_data['material_title'];
                    $export[] = $retrive_data['desciption'];
                    $export[] = $this->ERPfunction->get_category_title($retrive_data['unit_id']);
                    $export[] = ($retrive_data['project_id']) ? $this->ERPfunction->get_projectname($retrive_data['project_id']) : "All";
                    $export[] = $this->ERPfunction->get_consume_type($retrive_data['material_id']);
                    $rows[] = $export;
                }

                $filename = "material_list.csv";
                $this->ERPfunction->export_to_csv($filename, $rows);
            }

            if (isset($this->request->data["export_pdf"])) {
                require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                // debug($post);die;

                $or = array();
                $material_group = explode(",", $post["e_material_group"]);
                $or["material_item_code ="] = (!empty($post["e_material_code"])) ? $post["e_material_code"] : null;
                $or["material_title ="] = (!empty($post["e_material_name"])) ? $post["e_material_name"] : null;
                $or["material_code IN"] = (!empty($post["e_material_group"]) && $material_group[0] != "All") ? $material_group : null;

                if ($role == 'deputymanagerelectric') {
                    $or["material_code IN"] = array('6', '7', '10', '15');
                }

                if ($this->Usermanage->project_alloted($role) == 1) {
                    $meterial_ids = $this->ERPfunction->get_user_material_id($user_id);
                    $meterial_ids = json_decode($meterial_ids);
                    $or["material_id IN"] = $meterial_ids;
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                if (!empty($or)) {
                    $material_list = $erp_material->find()->where($or)->hydrate(false)->toArray();
                } else {
                    $material_list = $erp_material->find()->hydrate(false)->toArray();
                }

                $rows = array();
                $rows[] = array("Material Code", "Material Group", "Material Sub-Group", "Material Name", "Material Description", "Unit", "Project", "Consume Type");

                foreach ($material_list as $retrive_data) {
                    $export = array();
                    $export[] = $retrive_data["material_item_code"];
                    $export[] = $this->ERPfunction->get_vendor_group_name($retrive_data['material_code']);
                    $export[] = $this->ERPfunction->get_material_subgroup_title($retrive_data['material_sub_group']);
                    $export[] = $retrive_data['material_title'];
                    $export[] = $retrive_data['desciption'];
                    $export[] = $this->ERPfunction->get_category_title($retrive_data['unit_id']);
                    $export[] = ($retrive_data['project_id']) ? $this->ERPfunction->get_projectname($retrive_data['project_id']) : "All";
                    $export[] = $this->ERPfunction->get_consume_type($retrive_data['material_id']);
                    $rows[] = $export;
                }
                $this->set("rows", $rows);
                $this->render("materiallistpdf");
            }
        }

    }

    public function viewaddmaterial($material_id)
    {

        $erp_material = TableRegistry::get('erp_material');
        $category = $this->ERPfunction->vendor_group();
        $this->set('category', $category);
        $table_category = TableRegistry::get('erp_category_master');
        $unit_list = $table_category->find()->where(array('type' => 'unit'));
        $this->set('unitlist', $unit_list);
        $this->set("back", "index");

        $material_data = $erp_material->get($material_id);
        $this->set('material_data', $material_data);
        $this->set('form_header', 'View Material');
        $this->set("back", "viewmaterial");
    }

    public function deletematerial($material_id)
    {

        $erp_material = TableRegistry::get('erp_material');
        $row_delte = $erp_material->get($material_id);
        if ($erp_material->delete($row_delte)) {
            $this->Flash->success(__('Record Successfully Deleted'));
            return $this->redirect(['controller' => 'Purchase', 'action' => 'viewmaterial']);
        }
    }
    public function addbrand($brand_id = null)
    {
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'inventory') !== false) {
            $back_url = 'inventory';
            $back_page = 'index';
        } else {
            $back_url = 'purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        $erp_material_brand = TableRegistry::get('erp_material_brand');
        // $category = $this->ERPfunction->material_category();
        // $this->set('category',$category);

        $category = $this->ERPfunction->rolewise_vendor_group($this->role);
        $this->set('category', $category);
        $this->set("back", "index");
        if (isset($brand_id)) {

            $user_action = 'edit';

            $brand_data = $erp_material_brand->get($brand_id);

            $this->set('brand_data', $brand_data);
            $this->set('form_header', 'Edit Brand');
            $this->set('button_text', 'Update Brand');
            $this->set("back", "brandlist");

        } else {
            $user_action = 'insert';

            $this->set('form_header', 'Add Brand');
            $this->set('button_text', 'Add Brand');
        }
        $this->set('user_action', $user_action);
        if ($this->request->is('post')) {
            if ($user_action == 'edit') {
                $updated_data = $this->request->data;
                $brand_data = $erp_material_brand->patchEntity($brand_data, $updated_data);
                if ($erp_material_brand->save($brand_data)) {
                    $this->Flash->success(__('Record Update Successfully', null),
                        'default',
                        array('class' => 'success'));

                }
                $this->redirect(array("controller" => "Purchase", "action" => "brandlist"));
            } else {
                $material_type = $this->request->data["material_type"];
                $brand_name = $this->request->data["brand_name"];

                $check = $erp_material_brand->find("all")->where(["material_type" => $material_type, "brand_name" => $brand_name])->count();
                // echo $check;die;
                if ($check == 0) {
                    $table_field = $erp_material_brand->newEntity();
                    $this->request->data['status'] = 1;
                    $new_data = $erp_material_brand->patchEntity($table_field, $this->request->data);
                    if ($erp_material_brand->save($new_data)) {
                        $this->Flash->success(__('Record Insert Successfully', null),
                            'default',
                            array('class' => 'success'));
                    }

                    $this->redirect(array("controller" => "Purchase", "action" => "brandlist"));
                } else {
                    $this->Flash->success(__('Duplicate record, Please try again', null),
                        'default',
                        array('class' => 'success'));
                }
            }
        }

    }
    public function brandlist($brand_id = null)
    {
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'inventory') !== false) {
            $back_url = 'inventory';
            $back_page = 'index';
        } else {
            $back_url = 'purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        if ($brand_id) {
            $erp_material_brand = TableRegistry::get('erp_material_brand');
            $brand_list = $erp_material_brand->find()->where(array('material_type' => $brand_id));
            $this->set('brand_list', $brand_list);
        } else {
            $erp_material_brand = TableRegistry::get('erp_material_brand');
            if ($this->role == 'deputymanagerelectric') {
                $brand_list = $erp_material_brand->find()->where(['material_type IN' => ['6', '7', '10', '15']]);
            } else {
                $brand_list = $erp_material_brand->find()->where(['material_type !=' => 17]);
            }
            $this->set('brand_list', $brand_list);
        }
        $category = $this->ERPfunction->material_category();
        $this->set('category', $category);
        $role = $this->role;
        $this->set('role', $role);

        $search_data = array();
        if ($this->request->is("post")) {
            $post = $this->request->data;
            if (isset($post["search"])) {
                $post = $this->request->data;

                $or = array();
                $or["brand_name LIKE"] = (!empty($post["brand_name"])) ? "%{$post["brand_name"]}%" : null;
                $or["material_type IN"] = (!empty($post["material_group"]) && $post["material_group"][0] != "All") ? $post["material_group"] : null;

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $erp_material_brand = TableRegistry::get('erp_material_brand');
                $brand_list = $erp_material_brand->find()->where($or);
                $this->set('brand_list', $brand_list);
            }
        }
    }
    public function viewbrand($brand_id = null)
    {
        if ($brand_id) {
            $erp_material_brand = TableRegistry::get('erp_material_brand');
            $brand_list = $erp_material_brand->find()->where(array('material_type' => $brand_id));
            $this->set('brand_list', $brand_list);
        } else {
            $erp_material_brand = TableRegistry::get('erp_material_brand');
            $brand_list = $erp_material_brand->find();
            $this->set('brand_list', $brand_list);
        }
        $category = $this->ERPfunction->material_category();
        $this->set('category', $category);

    }
    public function deletebrand($id)
    {
        $this->request->is(['post', 'delete']);
        $erp_material_brand = TableRegistry::get('erp_material_brand');
        $row_delte = $erp_material_brand->get($id);
        if ($erp_material_brand->delete($row_delte)) {
            $this->Flash->success(__('Record Successfully Deleted'));
            return $this->redirect(['controller' => 'material', 'action' => 'viewbrand']);
        }
    }
    public function viewpo()
    {
        $erp_inventory_po = TableRegistry::get('erp_inventory_po');
        $po_list = $erp_inventory_po->find()->where(array('status' => 1, 'approved_status' => 1, 'po_mode' => 'central'));
        $this->set('po_list', $po_list);
    }
    public function previewpo($po_id)
    {
        $erp_inve_po = TableRegistry::get('erp_inventory_po');
        $erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
        $erp_po_details = $erp_inve_po->get($po_id);
        $this->set('erp_po_details', $erp_po_details);
        $previw_list = $erp_inve_po_details->find()->where(array('po_id' => $po_id));
        $this->set('previw_list', $previw_list);

    }

    public function previewpo2($po_id)
    {
        $erp_inve_po = TableRegistry::get('erp_inventory_po');
        $erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
        $erp_po_details = $erp_inve_po->get($po_id);
        $this->set('erp_po_details', $erp_po_details);
        $previw_list = $erp_inve_po_details->find()->where(array('po_id' => $po_id));
        $this->set('previw_list', $previw_list);

    }

    public function printporecord2($po_id)
    {
        require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
        $rmc_tbl = TableRegistry::get("erp_inventory_po");
		$erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
		$previw_list = $erp_inve_po_details->find()->where(array('po_id'=>$po_id));
		$this->set('previw_list',$previw_list);
		$data = $rmc_tbl->get($po_id);
		$this->set("data",$data->toArray());	
    }

    public function manualapprovepreviewpo($po_id)
    {
        $erp_manual_po = TableRegistry::get('erp_manual_po');
        $erp_manual_po_detail = TableRegistry::get('erp_manual_po_detail');
        $erp_po_details = $erp_manual_po->get($po_id);
        $this->set('erp_po_details', $erp_po_details);
        $previw_list = $erp_manual_po_detail->find()->where(array('po_id' => $po_id));
        $this->set('previw_list', $previw_list);

    }

    public function manualpreviewpo($po_id)
    {
        $erp_manual_po = TableRegistry::get('erp_manual_po');
        $erp_manual_po_detail = TableRegistry::get('erp_manual_po_detail');
        $erp_po_details = $erp_manual_po->get($po_id);
        $this->set('erp_po_details', $erp_po_details);
        $previw_list = $erp_manual_po_detail->find()->where(array('po_id' => $po_id));
        $this->set('previw_list', $previw_list);
    }

    public function viewvendor()
    {
        ini_set('memory_limit', '-1');
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'contract') !== false) {
            $back_url = 'contract';
            $back_page = 'billingmenu';
        } elseif (strpos($previous_url, 'purchase') !== false) {
            $back_url = 'purchase';
            $back_page = 'index';
        } else {
            $back_url = 'Accounts';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        $users_table = TableRegistry::get('erp_vendor');
        $user_list = $users_table->find();
        $this->set('user_list', $user_list);

        $role = $this->role;
        $this->set('role', $role);

        // $vendor_groups = $this->ERPfunction->vendor_group();
        // $this->set('vendor_groups',$vendor_groups);

        if ($this->request->is("post")) {
            $post = $this->request->data;
            if (isset($post["search"])) {
                $post = $this->request->data;

                $or = array();
                $or["vendor_id LIKE"] = (!empty($post["vendor_id"])) ? "%{$post["vendor_id"]}%" : null;
                // $or["vendor_group IN"] = (!empty($post["vendor_group"]) && $post["vendor_group"][0] != "All")?$post["vendor_group"]:NULL;
                $or["vendor_name LIKE"] = (!empty($post["vendor_name"])) ? "%{$post["vendor_name"]}%" : null;

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $user_list = $users_table->find()->where($or);
                $this->set('user_list', $user_list);
            }

            if (isset($this->request->data["export_csv"])) {
                $rows = unserialize(base64_decode($this->request->data["rows"]));
                $filename = "vendor_list.csv";
                $this->ERPfunction->export_to_csv($filename, $rows);
            }

            if (isset($this->request->data["export_pdf"])) {
                require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';
                $rows = unserialize(base64_decode($this->request->data["rows"]));
                $this->set("rows", $rows);
                $this->render("vendorlistpdf");
            }

        }
    }

    public function addvendor($user_id = null)
    {
        $previous_url = $this->referer();
        if (strpos($previous_url, 'accounts') !== false) {
            $back_url = 'accounts';
            $back_page = 'index';
        } else {
            $back_url = 'purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        $users_table = TableRegistry::get('erp_vendor');

        $vendor_groups = $this->ERPfunction->vendor_group();
        $this->set('vendor_groups', $vendor_groups);
        $this->set("back", "index");
        if (isset($user_id)) {
            $user_action = 'edit';
            $user_data = $users_table->get($user_id);

            $this->set('user_data', $user_data);
            $this->set('form_header', 'Edit Vendor');
            $this->set('button_text', 'Update Vendor');
            $this->set("back", "editvendor");

        } else {
            $user_action = 'insert';
            // $this->set('user_identy_id',$user_identy_id);
            $this->set('form_header', 'Add Vendor');
            $this->set('button_text', 'Add Vendor');

            // $code = $this->get_last_vendor_id();
            // $vdno = "YNEC/VD/". sprintf("%09d", $code + 1);
            // $this->set('vendor_id',$vdno);

        }

        $this->set('user_action', $user_action);

        if ($this->request->is('post')) {
			if(isset($_FILES['attach_file'])){
				$file =$_FILES['attach_file']["name"];
				$size = count($file);
				for($i=0;$i<$size;$i++) {
					$parts = pathinfo($_FILES['attach_file']['name'][$i]);
				}
				$ext = $this->ERPfunction->check_valid_extension($parts['basename']);
				// debug($ext);die;
				if($ext != 0) {

					$this->set('user_data', $this->request->data);
					$this->request->data['status'] = 1;
					// $image=$this->ERPfunction->upload_image('image_url',$this->request->data['old_image']);
					// $this->request->data['image_url']=$image;

					if ($user_action == 'edit') {
						$post_data = $this->request->data;

						$old_files = array();
						if (isset($post_data["old_attach_file"])) {
							$old_files = $post_data["old_attach_file"];
						}
						@$post_data['attach_label'] = trim(json_encode($post_data["attach_label"]), '\"');
						if (isset($_FILES["attach_file"]["name"])) {
							$file = $this->ERPfunction->upload_file("attach_file");
							if (!empty($file)) {
								foreach ($file as $attachment_file) {
									$old_files[] = $attachment_file;
								}
							}

						}
						$post_data['attach_file'] = json_encode($old_files);

						$post_data['last_edit'] = date('Y-m-d H:i:s');
						$post_data['last_edit_by'] = $this->request->session()->read('user_id');

						$user_data = $users_table->patchEntity($user_data, $post_data);
						if ($users_table->save($user_data)) {
							$this->Flash->success(__('Record Update Successfully', null),
								'default',
								array('class' => 'success'));
							echo "<script>window.close();</script>";
						}
					} else {
						$check_email = $users_table->find()->where(['email_id' => $this->request->data['email_id']]);

						$user_field = $users_table->newEntity();

						@$this->request->data['attach_label'] = trim(json_encode($this->request->data["attach_label"]), '\"');
						$all_files = array();
						if (isset($_FILES["attach_file"]["name"])) {
							$file = $this->ERPfunction->upload_file("attach_file");
							if (!empty($file)) {
								foreach ($file as $attachment_file) {
									$all_files[] = $attachment_file;
								}
							}

						}
						$this->request->data['attach_file'] = json_encode($all_files);

						$code = $this->get_last_vendor_id();
						$vdno = "YNEC/VD/" . sprintf("%09d", $code + 1);
						$this->request->data['vendor_id'] = $vdno;
						$this->request->data['created_date'] = date('Y-m-d H:i:s');
						$this->request->data['created_by'] = $this->request->session()->read('user_id');

						$user_field = $users_table->patchEntity($user_field, $this->request->data);
						if ($users_table->save($user_field)) {
							$this->Flash->success(__('Vendor Insert Successfully with Vendor Id ' . $vdno, null),
								'default',
								array('class' => 'success'));
						}
						$this->redirect(array("controller" => "Purchase", "action" => "viewvendor"));
					}
				}else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
				}
			}
			else{
				$this->set('user_data', $this->request->data);
					$this->request->data['status'] = 1;
					// $image=$this->ERPfunction->upload_image('image_url',$this->request->data['old_image']);
					// $this->request->data['image_url']=$image;

					if ($user_action == 'edit') {
						$post_data = $this->request->data;

						$old_files = array();
						if (isset($post_data["old_attach_file"])) {
							$old_files = $post_data["old_attach_file"];
						}
						@$post_data['attach_label'] = trim(json_encode($post_data["attach_label"]), '\"');
						if (isset($_FILES["attach_file"]["name"])) {
							$file = $this->ERPfunction->upload_file("attach_file");
							if (!empty($file)) {
								foreach ($file as $attachment_file) {
									$old_files[] = $attachment_file;
								}
							}

						}
						$post_data['attach_file'] = json_encode($old_files);

						$post_data['last_edit'] = date('Y-m-d H:i:s');
						$post_data['last_edit_by'] = $this->request->session()->read('user_id');

						$user_data = $users_table->patchEntity($user_data, $post_data);
						if ($users_table->save($user_data)) {
							$this->Flash->success(__('Record Update Successfully', null),
								'default',
								array('class' => 'success'));
							echo "<script>window.close();</script>";
						}
					} else {
						$check_email = $users_table->find()->where(['email_id' => $this->request->data['email_id']]);

						$user_field = $users_table->newEntity();

						@$this->request->data['attach_label'] = trim(json_encode($this->request->data["attach_label"]), '\"');
						$all_files = array();
						if (isset($_FILES["attach_file"]["name"])) {
							$file = $this->ERPfunction->upload_file("attach_file");
							if (!empty($file)) {
								foreach ($file as $attachment_file) {
									$all_files[] = $attachment_file;
								}
							}

						}
						$this->request->data['attach_file'] = json_encode($all_files);

						$code = $this->get_last_vendor_id();
						$vdno = "YNEC/VD/" . sprintf("%09d", $code + 1);
						$this->request->data['vendor_id'] = $vdno;
						$this->request->data['created_date'] = date('Y-m-d H:i:s');
						$this->request->data['created_by'] = $this->request->session()->read('user_id');

						$user_field = $users_table->patchEntity($user_field, $this->request->data);
						if ($users_table->save($user_field)) {
							$this->Flash->success(__('Vendor Insert Successfully with Vendor Id ' . $vdno, null),
								'default',
								array('class' => 'success'));
						}
						$this->redirect(array("controller" => "Purchase", "action" => "viewvendor"));
					}
			}
        }
    }

    public function get_last_vendor_id()
    {
        $conn = ConnectionManager::get('default');
        $result = $conn->execute('select max(user_id) from  erp_vendor');
        $max = 0;
        foreach ($result as $retrive_data) {
            $max = $retrive_data[0];
        }
        return $max;
    }

    public function editvendor()
    {
        $users_table = TableRegistry::get('erp_vendor');
        $user_list = $users_table->find()->where(['status' => 1]);
        $this->set('user_list', $user_list);

        // $vendor_groups = $this->ERPfunction->vendor_group();
        // $this->set('vendor_groups',$vendor_groups);

        if ($this->request->is("post")) {
            $post = $this->request->data;
            if (isset($post["search"])) {
                $post = $this->request->data;

                $or = array();
                $or["vendor_id LIKE"] = (!empty($post["vendor_id"])) ? "%{$post["vendor_id"]}%" : null;
                // $or["vendor_group IN"] = (!empty($post["vendor_group"]) && $post["vendor_group"][0] != "All")?$post["vendor_group"]:NULL;
                $or["vendor_name LIKE"] = (!empty($post["vendor_name"])) ? "%{$post["vendor_name"]}%" : null;
                $or["status"] = 1;

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $user_list = $users_table->find()->where($or);
                $this->set('user_list', $user_list);
            }
        }

    }

    public function remove($user_id)
    {
        $users_table = TableRegistry::get('erp_vendor');

        $user_data = $users_table->get($user_id);
        $this->request->data['status'] = 0;
        $this->request->data['remove_date'] = date('Y-m-d H:i:s');
        $this->request->data['remove_by'] = $this->request->session()->read('user_id');
        $post_data = $this->request->data;
        $user_data = $users_table->patchEntity($user_data, $post_data);
        if ($users_table->save($user_data)) {
            $this->Flash->success(__('Record Remove Successfully', null),
                'default',
                array('class' => 'success'));
        }
        return $this->redirect(['action' => 'editvendor']);
    }

    public function viewaddvendor($user_id)
    {
        $users_table = TableRegistry::get('erp_vendor');
        $user_identy_id = $this->ERPfunction->generate_autoid('VD-');
        // $vendor_groups = $this->ERPfunction->vendor_group();
        // $this->set('vendor_groups',$vendor_groups);

        $user_action = 'edit';
        $user_data = $users_table->get($user_id);
        $role = $this->role;
        $this->set('role',$this->role);
        $this->set('user_data', $user_data);
        $this->set('form_header', 'Edit Vendor');
        $this->set('button_text', 'Update Vendor');
        $this->set('user_action', $user_action);
        $this->set("back", "viewvendor");
    }

    public function setstatus()
    {
        $post = $this->request->data;
        $pr_material_id = array();
        if (!empty($post["approved_list"])) {
            foreach ($post["approved_list"] as $row) {
                $pr_material_id[] = $post["pr_mid_" . $row];
            }

            $m_tbl = TableRegistry::get("erp_inventory_pr_material");
            foreach ($pr_material_id as $pr_mid) {
                $mdata = $m_tbl->get($pr_mid);
                $mdata->show_in_purchase = 1;
                $mdata->approved_by = $this->user_id;
                $mdata->approved_date = date("Y-m-d H:i:s");
                /* $mdata->approved = 1; */
                $m_tbl->save($mdata);
            }
        }
        $this->redirect(["controller" => "inventory", "action" => "approvedpr"]);
        $this->autoRender = false;
    }

    public function removemanualpr()
    {
        $m_tbl = TableRegistry::get("erp_inventory_pr_material");
        $pr_material_id = $this->request->data['pr_item_row_id'];
        $done_remarks = $this->request->data['done_remark'];
        $project_id = $this->request->data['project_id'];
        $mdata = $m_tbl->get($pr_material_id);
        $mdata->show_in_purchase = 3;
        $mdata->done_remarks = $done_remarks;
        $m_tbl->save($mdata);

        $this->redirect(["controller" => "Purchase", "action" => "approvedpr", $project_id]);
        // $this->redirect(array("controller" => "Purchase","action" => "approvedpr", '?' => array('selected_project' => $project_id)));
        $this->autoRender = false;
    }

    public function approvedpr($id = null)
    {
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } else {
            $back_url = 'purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == 'deputymanagerelectric') {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }

        $this->set('projects', $projects);
        if ($this->request->is('post')) {
            $request_data = $this->request->data;
            if (isset($request_data["go"])) {
                $this->set('request_data', $request_data);
                if ($this->request->data['from_date'] != '') {
                    $this->request->data['from_date'] = date('Y-m-d', strtotime($this->request->data['from_date']));
                }

                if ($this->request->data['to_date'] != '') {
                    $this->request->data['to_date'] = date('Y-m-d', strtotime($this->request->data['to_date']));
                }

                $pr_list = $this->Usermanage->fetch_approve_pr($this->user_id, $this->request->data);
                $this->set('pr_list', $pr_list);
            } elseif (isset($request_data["approve_list"])) {
                $pr_mat_tbl = TableRegistry::get("erp_inventory_pr_material");
                foreach ($request_data["approved_list"] as $prmid) {
                    $row = $pr_mat_tbl->get($request_data["pr_mid_{$prmid}"]);
                    $row->show_in_purchase = 2; //approved in Purchase tab's PR Alert
                    $pr_mat_tbl->save($row);
                }
                // debug($request_data);
                // debug($request_data["approve_list"]);die;
            }
        } else {
            if ($id) {
                $data = array();
                $data['project_id'] = $id;
                $data['from_date'] = '';
                $data['to_date'] = '';
                $pr_list = $this->Usermanage->fetch_approve_pr($this->user_id, $data);
                $this->set('selected_project', $id);
                $this->set('pr_list', $pr_list);
            }
        }
        // debug($pr_list->fetch("assoc"));die;
        // $this->set('pr_list',$pr_list);
    }

    public function pralertsubmit()
    {

    }

    public function printvendor($eid)
    {
        require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';
        $rmc_tbl = TableRegistry::get("erp_vendor");
        $data = $rmc_tbl->get($eid);
        $this->set("data", $data->toArray());
    }

    public function setpraprove()
    {
        if (isset($this->request->data["approve_list"])) {
            $post = $this->request->data;
            $tbl = TableRegistry::get("erp_inventory_pr_material");

            foreach ($post["approved_list"] as $row) {
                $row = $tbl->get($post["pr_mid_" . $row]);
                $row->approved_for_grnwithoutpo = 1;
                $row->approved = 1;
                $row->approved_by = $this->user_id;
                $row->approved_date = date("Y-m-d H:i:s");
                $tbl->save($row);
            }

        }
        $this->autoRender = false;
        $this->Flash->success(__('Record Approved Successfully and moved to prepare GRN without PO page.', null),
            'default',
            array('class' => 'success'));

        $this->redirect(["controller" => "Inventory", "action" => "approvedpr"]);
    }

    public function showinporecords()
    {
        $this->autoRender = false;
        $post = $this->request->data;
        // debug($post);die;
        $po_tbl = TableRegistry::get("erp_inventory_po");
        $po_mtb = TableRegistry::get("erp_inventory_po_detail");
        if (!empty($post["approved_list1"]) || !empty($post["verify_list"]) || (isset($post["approved_list"]) && !empty($post["approved_list"]))) {
            /* for first step approve code start */
            if (!empty($post["approved_list1"])) {
                foreach ($post["approved_list1"] as $poid) {
                    $po_no = $this->ERPfunction->get_po_no_by_id($poid);

                    if ($po_no == $post["po"]) {
                        $query = $po_mtb->query();
                        $query = $query->update()->set(["first_approved" => 1, "first_approved_by" => $this->user_id, "first_approved_date" => date("Y-m-d")])->where(["po_id" => $poid])->execute();
                    }
                }
            }
            /* first step approve code end */

            /* for verify approve code start */
            if (!empty($post["verify_list"])) {
                foreach ($post["verify_list"] as $poid) {
                    $po_no = $this->ERPfunction->get_po_no_by_id($poid);

                    if ($po_no == $post["po"]) {
                        $query = $po_mtb->query();
                        $query = $query->update()->set(["verified" => 1, "verified_by" => $this->user_id, "verified_date" => date("Y-m-d")])->where(["po_id" => $poid])->execute();
                    }
                }
            }
            /* first verify code end */

            if (isset($post["approved_list"]) && !empty($post["approved_list"])) {

                // $session = $this->request->session();
                // $session->write(["ids"=>$post['approved_list']]);
                // debug($post);die;
                $approved_id = array();
                foreach ($post["approved_list"] as $poid) {
                    // $po_no = $this->ERPfunction->get_po_no_by_id($poid);
                    $po_record = $po_tbl->get($poid);
                    $po_record -> ammend_approve = 1;
                    $po_record -> updated = 0;
                    $po_record -> last_po = 1;
                    $po_tbl->save($po_record);
                    $project_id = $po_record->project_id;
                    $po_date = $po_record->po_date;
                    $po_id = $poid;
                    $po_no = $po_record->po_no;
                    // var_dump($po_no);die;
                    if ($po_no == $post["po"]) {
                        $material_row = $po_mtb->find()->where(["po_id" => $poid])->hydrate(false)->toArray();
                        foreach ($material_row as $m_row) {
                            $approved_id[] = $m_row['id'];
                            $row = $po_mtb->get($m_row['id']);
                            $mail_po_id = $row->po_id;
                            $row->approved = 1;
                            //After approve po goes to grn and grn remain quentity save in grn_remain_qty
                            if ($row->po_type == "po") {
                                $row->grn_remain_qty = $row->quantity;
                            }
                            $row->currently_approved = 1;
                            $row->approved_by = $this->user_id;
                            $row->approved_date = date("Y-m-d");
                            $po_mtb->save($row);
                        }
                    }
                }

                $mail_enable = $this->ERPfunction->get_po_mail_status($mail_po_id);
                $email_list = $this->ERPfunction->get_mail_list_by_project($project_id, $po_id, $mail_enable, '"po_notification"');
                // debug($email_list);die;
                $emails = array();
                $emails_norate = array();
                //foreach($post["approved_list"] as $mid)
                // foreach($approved_id as $mid)
                // {
                $mm_email = $this->ERPfunction->get_email_of_mm_by_project($project_id);
                $billingeng_email = $this->ERPfunction->get_email_of_billingengineer_by_project($project_id);
                $mm_email = array_merge($mm_email, $billingeng_email);

                $emails_norate = array_merge($mm_email, $emails_norate);
                $mm_email = array_unique($emails_norate); /*remove duplicate email ids */
                $mm_email = array_filter($mm_email, function ($value) {return $value !== '';});
                $po_vendor_email = $this->ERPfunction->get_po_vendor_id($po_id);
                // }

                // Check the vendor email format are correct or not? code start
                $email_correct = 1;
                $wrong_email = array();
                foreach ($po_vendor_email as $value) {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {

                    } else {
                        $email_correct = 0;
                        $wrong_email[] = $value;
                    }
                }
                // Check the all email format are correct or not? code end
                // debug($email_list);die;
                if ($email_correct) {

                    if (!empty($email_list)) {
                        $pdpmcm_email = implode(",", $email_list);
                        $view_po = $po_id;
                        $this->ERPfunction->mail_po_withrate($pdpmcm_email, $view_po, $po_no, $project_id, $po_date);
                        // $this->ERPfunction->testMail($pdpmcm_email, $view_po, $po_no, $project_id, $po_date);
                    }
                    if ($mail_enable != 0) {
                        if (!empty($mm_email)) {
                            $mm_email = implode(",", $mm_email);
                            $view_po = $po_id;
                            $this->ERPfunction->mail_po_withoutrate($mm_email, $view_po, $po_no, $project_id, $po_date);
                        }
                    }
                } else {
                    // Un approve before approved record if email format have problem
                    foreach ($approved_id as $mid) {
                        $po_no = $this->ERPfunction->get_po_no_by_detailid($mid);
                        // var_dump($po_no);die;
                        if ($po_no == $post["po"]) {
                            $row = $po_mtb->get($mid);
                            $row->approved = 0;
                            //After approve po goes to grn and grn remain quentity save in grn_remain_qty
                            if ($row->po_type == "po") {
                                $row->grn_remain_qty = $row->grn_remain_qty - $row->quantity;
                            }
                            $row->currently_approved = 0;
                            $row->approved_by = 0;
                            $row->approved_date = 0000 - 00 - 00;
                            $po_mtb->save($row);
                        }
                    }
                    // debug($wrong_email);die;
                    $this->Flash->error(__('There is a problem with vendor email format', null),
                        'default',
                        array('class' => 'success'));

                    $this->redirect(array("controller" => "inventory", "action" => "approvepo", '?' => array('selected_project' => $post['selected_project_id'])));
                }

                foreach ($approved_id as $mid) {
                    $row = $po_mtb->get($mid);

                    //Update and check PR Material, approve full quentity pr material and make
                    $pr_material_id = $row->pr_mid;
                    if ($pr_material_id) {
                        $m_tbl = TableRegistry::get("erp_inventory_pr_material");
                        $pr_material_row = $m_tbl->get($pr_material_id);
                        $po_approved_quentity = $pr_material_row->po_approved_quantity;
                        $pr_actual_quantity = $pr_material_row->quantity;
                        if ($po_approved_quentity < $pr_actual_quantity) {
                            $pr_material_row->quantity = $pr_actual_quantity - $po_approved_quentity;
                            $pr_material_row->po_pending_quantity = $pr_actual_quantity - $po_approved_quentity;
                            $pr_material_row->po_approved_quantity = 0;
                            $pr_material_row->po_completed = 2;
                        } else {
                            $pr_material_row->po_completed = 0;
                            $pr_material_row->approved = 1;
                        }
                        $m_tbl->save($pr_material_row);
                    }

                    //make currently approved 0 in po detail table for only send approved material in mail
                    //$row->approved = 1;

                    $row->currently_approved = 0;
                    //$row->approved_by = $this->user_id;
                    //$row->approved_date = date("Y-m-d");
                    $po_mtb->save($row);

                }

            }
            $this->redirect(array("controller" => "inventory", "action" => "approvepo", '?' => array(
                'selected_project' => $post['selected_project_id'])));
        } else {
            $this->Flash->error(__('Please select record', null),
                'default',
                array('class' => 'success'));
            $this->redirect(array("controller" => "inventory", "action" => "approvepo"));
        }
    }

    public function showinmanualporecords()
    {
        $this->autoRender = false;
        $post = $this->request->data;
        // For which page to redirect back 1) ManualPOAlert 2) ManualPOAlertlocal
        $back_to_page = $post['action_name'];
        $po_mtb = TableRegistry::get("erp_manual_po_detail");
        if (!empty($post["approved_list1"]) || (isset($post["approved_list"]) && !empty($post["approved_list"]))) {
            /* for first step approve code start */
            if (!empty($post["approved_list1"])) {
                foreach ($post["approved_list1"] as $mid) {
                    $po_no = $this->ERPfunction->get_manualpo_no_by_detailid($mid);

                    if ($po_no == $post["po"]) {
                        $row = $po_mtb->get($mid);
                        $row->first_approved = 1;
                        $row->first_approved_by = $this->user_id;
                        $row->first_approved_date = date("Y-m-d");
                        $po_mtb->save($row);
                    }
                }
            }
            /* first step approve code end */

            if (isset($post["approved_list"]) && !empty($post["approved_list"])) {

                $session = $this->request->session();
                $session->write(["ids" => $post['approved_list']]);
                //debug($post);die;
                $approved_id = array();
                foreach ($post["approved_list"] as $mid) {
                    $po_no = $this->ERPfunction->get_manualpo_no_by_detailid($mid);
                    // var_dump($po_no);die;
                    if ($po_no == $post["po"]) {
                        $approved_id[] = $mid;
                        $row = $po_mtb->get($mid);
                        $manualpo_id = $row->po_id;
                        $row->approved = 1;
                        $row->currently_approved = 1;
                        $row->approved_by = $this->user_id;
                        $row->approved_date = date("Y-m-d");
                        $po_mtb->save($row);
                    }
                }
                $mail_enable = $this->ERPfunction->get_manualpo_mail_status($manualpo_id);
                if ($mail_enable == 1) {
                    $emails = array();
                    $emails_norate = array();
                    //foreach($post["approved_list"] as $mid)
                    foreach ($approved_id as $mid) {
                        $project_id = $post["project_id_{$mid}"];
                        $po_id = $post["selected_po_id_{$mid}"];
                        $pdpmcm_email = $this->ERPfunction->get_email_of_pd_pm_cm_by_project_of_manualpo($project_id, $po_id);
                        $mm_email = $this->ERPfunction->get_email_of_mm_by_project($project_id);
                        $emails = array_merge($pdpmcm_email, $emails);
                        $emails_norate = array_merge($mm_email, $emails_norate);
                        $manualpo_vendor_email = $this->ERPfunction->get_manualpo_vendor_id($po_id);
                        $emails = array_merge($manualpo_vendor_email, $emails);
                    }

                    $role = ['erphead', 'erpmanager', 'purchasehead', 'erpoperator', 'ceo'];
                    $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                    $emails = array_merge($erp_email, $emails);

                    $pdpmcm_email = array_unique($emails); /*remove duplicate email ids */
                    $mm_email = array_unique($emails_norate); /*remove duplicate email ids */
                    $pdpmcm_email = array_filter($pdpmcm_email, function ($value) {return $value !== '';});
                    $pdpmcm_email = array_filter($pdpmcm_email, function ($value) {return $value !== null;});
                    $mm_email = array_filter($mm_email, function ($value) {return $value !== '';});
                    $pdpmcm_email[] = "bipin.patel@yashnandeng.com";

                    // Check the vendor email format are correct or not? code start
                    $email_correct = 1;
                    $wrong_email = array();
                    foreach ($manualpo_vendor_email as $value) {
                        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {

                        } else {
                            $email_correct = 0;
                            $wrong_email[] = $value;
                        }
                    }
                    // Check the vendor email format are correct or not? code end

                    if ($email_correct) {
                        if (!empty($pdpmcm_email)) {

                            // $pdpmcm_email = implode(",",$pdpmcm_email);
                            $view_po = $post["approved_list"][0]; /* only single Po attach */
                            $view_po = $post["selected_po_id_{$view_po}"];
                            $this->ERPfunction->mail_manualpo_withrate($pdpmcm_email, $view_po);
                        }

                        if (!empty($mm_email)) {
                            // $mm_email = implode(",",$mm_email);
                            $view_po = $post["approved_list"][0]; /* only single Po attach*/
                            $view_po = $post["selected_po_id_{$view_po}"];
                            $this->ERPfunction->mail_manualpo_withoutrate($mm_email, $view_po);
                        }
                    } else {
                        foreach ($post["approved_list"] as $mid) {
                            $po_no = $this->ERPfunction->get_manualpo_no_by_detailid($mid);
                            // var_dump($po_no);die;
                            if ($po_no == $post["po"]) {
                                $row = $po_mtb->get($mid);
                                $row->approved = 0;
                                $row->currently_approved = 0;
                                $row->approved_by = 0;
                                $row->approved_date = 0000 - 00 - 00;
                                $po_mtb->save($row);
                            }
                        }
                        // debug($wrong_email);die;
                        $this->Flash->error(__('There is a problem with vendor email format', null),
                            'default',
                            array('class' => 'success'));

                        $this->redirect(array("controller" => "purchase", "action" => $back_to_page, '?' => array('selected_project' => $post['selected_project_id'])));
                    }
                } elseif ($mail_enable == 2) {
                    $emails = array();
                    $emails_norate = array();
                    //foreach($post["approved_list"] as $mid)
                    foreach ($approved_id as $mid) {
                        $project_id = $post["project_id_{$mid}"];
                        $po_id = $post["selected_po_id_{$mid}"];
                        $pdpmcm_email = $this->ERPfunction->get_email_of_pd_pm_cm_by_project_of_manualpo($project_id, $po_id);
                        $mm_email = $this->ERPfunction->get_email_of_mm_by_project($project_id);
                        $emails = array_merge($pdpmcm_email, $emails);
                        $emails_norate = array_merge($mm_email, $emails_norate);
                        $project_wise_role = ['deputymanagerelectric'];
                        $project_email = $this->ERPfunction->get_email_id_by_project_from_user($project_id, $project_wise_role);
                        $emails = array_merge($project_email, $emails);
                        $manualpo_vendor_email = $this->ERPfunction->get_manualpo_vendor_id($po_id);
                        $emails = array_merge($manualpo_vendor_email, $emails);
                    }

                    $role = ['erphead', 'erpmanager', 'purchasehead', 'erpoperator', 'ceo'];
                    $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                    $emails = array_merge($erp_email, $emails);

                    $pdpmcm_email = array_unique($emails); /*remove duplicate email ids */
                    $mm_email = array_unique($emails_norate); /*remove duplicate email ids */
                    $pdpmcm_email = array_filter($pdpmcm_email, function ($value) {return $value !== '';});
                    $mm_email = array_filter($mm_email, function ($value) {return $value !== '';});
                    $pdpmcm_email[] = "bipin.patel@yashnandeng.com";
                    // debug($pdpmcm_email);die;
                    // debug($mm_email);die;

                    // Check the vendor email format are correct or not? code start
                    $email_correct = 1;
                    $wrong_email = array();
                    foreach ($manualpo_vendor_email as $value) {
                        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {

                        } else {
                            $email_correct = 0;
                            $wrong_email[] = $value;
                        }
                    }
                    // Check the vendor email format are correct or not? code end

                    if ($email_correct) {
                        if (!empty($pdpmcm_email)) {
                            // $pdpmcm_email = implode(",",$pdpmcm_email);
                            $view_po = $post["approved_list"][0]; /* only single Po attach */
                            $view_po = $post["selected_po_id_{$view_po}"];
                            $this->ERPfunction->mail_manualpo_withrate($pdpmcm_email, $view_po);
                        }

                        if (!empty($mm_email)) {
                            // $mm_email = implode(",",$mm_email);
                            $view_po = $post["approved_list"][0]; /* only single Po attach*/
                            $view_po = $post["selected_po_id_{$view_po}"];
                            $this->ERPfunction->mail_manualpo_withoutrate($mm_email, $view_po);
                        }
                    } else {
                        foreach ($post["approved_list"] as $mid) {
                            $po_no = $this->ERPfunction->get_manualpo_no_by_detailid($mid);
                            // var_dump($po_no);die;
                            if ($po_no == $post["po"]) {
                                $row = $po_mtb->get($mid);
                                $row->approved = 0;
                                $row->currently_approved = 0;
                                $row->approved_by = 0;
                                $row->approved_date = 0000 - 00 - 00;
                                $po_mtb->save($row);
                            }
                        }
                        // debug($wrong_email);die;
                        $this->Flash->error(__('There is a problem with vendor email format', null),
                            'default',
                            array('class' => 'success'));

                        $this->redirect(array("controller" => "purchase", "action" => $back_to_page, '?' => array('selected_project' => $post['selected_project_id'])));
                    }
                } else {
                    $emails = array();
                    // $purchase_heademail = $this->ERPfunction->get_purchase_head_email_from_user();
                    // $purchase_manageremail = $this->ERPfunction->get_purchase_manager_email_from_user();
                    // $md_email = $this->ERPfunction->get_md_email_from_user();
                    // $emails = array_merge($purchase_heademail,$emails);
                    // $emails = array_merge($purchase_manageremail,$emails);
                    // $emails = array_merge($md_email,$emails);
                    $role = ['erphead', 'erpmanager', 'purchasehead', 'purchasemanager', 'md', 'erpoperator'];
                    $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                    $emails = array_merge($erp_email, $emails);
                    $emails = array_unique($emails); /*remove duplicate email ids */
                    $emails = array_filter($emails, function ($value) {return $value !== '';});
                    /*
                    // Check the all email format are correct or not? code start
                    $email_correct = 1;
                    $wrong_email = array();
                    foreach($emails as $value)
                    {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {

                    } else {
                    $email_correct = 0;
                    $wrong_email[] = $value;
                    }
                    }
                    // Check the all email format are correct or not? code end

                    if($email_correct)
                    {*/
                    if (!empty($emails)) {
                        // $emails = implode(",",$emails);
                        $view_po = $post["approved_list"][0]; /* only single Po attach */
                        $view_po = $post["selected_po_id_{$view_po}"];
                        $this->ERPfunction->mail_manualpo_withrate($emails, $view_po);
                    }
                    /*}else{
                foreach($post["approved_list"] as $mid)
                {
                $po_no = $this->ERPfunction->get_manualpo_no_by_detailid($mid);
                // var_dump($po_no);die;
                if($po_no == $post["po"])
                {
                $row = $po_mtb->get($mid);
                $row->approved = 0;
                $row->currently_approved = 0;
                $row->approved_by = 0;
                $row->approved_date = 0000-00-00;
                $po_mtb->save($row);
                }
                }
                // debug($wrong_email);die;
                $this->Flash->success(__('There is a problem with email format', null),
                'default',
                array('class' => 'success'));

                $this->redirect(array("controller" => "purchase","action" => $back_to_page, '?' => array('selected_project' => $post['selected_project_id'])));
                }*/

                }

                foreach ($approved_id as $mid) {
                    $row = $po_mtb->get($mid);
                    $row->currently_approved = 0;
                    $po_mtb->save($row);
                }

            }
            $this->redirect(array("controller" => "purchase", "action" => $back_to_page, '?' => array('selected_project' => $post['selected_project_id'])));
        } else {
            $this->Flash->success(__('Please select record', null),
                'default',
                array('class' => 'success'));
            $this->redirect(array("controller" => "purchase", "action" => "manualapprovepo"));
        }
    }

    public function viewammendporecords($projects_id = null, $from = null, $to = null)
    {
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'contract') !== false) {
            $back_url = 'contract';
            $back_page = 'billingmenu';
        } else {
            $back_url = 'Purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        // ini_set('memory_limit', '-1');
        $this->set("user_role", $this->role);
        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        //$projects = $this->ERPfunction->get_projects();
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        if ($role == "deputymanagerelectric") {
            $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
            $material_ids = json_decode($material_ids);
            $material_list = $erp_material->find()->where(["material_id IN" => $material_ids]);
        } else {
            $material_list = $erp_material->find();
        }
        $this->set('material_list', $material_list);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        $erp_material_brand = TableRegistry::get('erp_material_brand');
        $brand_list = $erp_material_brand->find();
        $this->set('brand_list', $brand_list);

        $user = $this->request->session()->read('user_id');
        //var_dump($user);die;
        $role = $this->Usermanage->get_user_role($user);
        $projects_ids = $this->Usermanage->users_project($user);

        $this->set("projects_id", $projects_id);
        $this->set("from", $from);
        $this->set("to", $to);

        // if($projects_id!=null){

        // $erp_inventory_po = TableRegistry::get("erp_inventory_po");
        // $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");

        // $erp_manual_po = TableRegistry::get("erp_manual_po");
        // $erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
        // $total=0;
        // $or1 = array();

        // $or["erp_inventory_po_detail.approved_date >="] = date('Y-m-d',strtotime($from));
        // $or["erp_inventory_po_detail.approved_date <="] = date('Y-m-d',strtotime($to));
        // $or["project_id"] = $projects_id;
        // $keys = array_keys($or,"");
        // foreach ($keys as $k)
        // {unset($or[$k]);}

        // $or1["erp_manual_po_detail.approved_date >="] = date('Y-m-d',strtotime($from));
        // $or1["erp_manual_po_detail.approved_date <="] = date('Y-m-d',strtotime($to));
        // $or1["project_id"] = $projects_id;
        // $keys = array_keys($or1,"");
        // foreach ($keys as $k)
        // {unset($or1[$k]);}

        // $result = $erp_inventory_po->find()->select($erp_inventory_po);
        // $result1 = $result->innerjoin(
        // ["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
        // ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved ="=>1])->where($or)->select($erp_inventory_po_detail)->group('erp_inventory_po.po_no')->order(['erp_inventory_po_detail.approved_date'=>'DESC'])->hydrate(false)->toArray();

        // $manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
        // $manual_po_list1 = $manual_po_list->innerjoin(
        // ["erp_manual_po_detail"=>"erp_manual_po_detail"],
        // ["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved ="=>1])
        // ->where($or1)->select($erp_manual_po_detail)->group('erp_manual_po.po_no')->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();

        // $this->set('po_list',$result1);
        // $this->set('manual_po',$manual_po_list);
        // $this->set('manual_po',$manual_po_list);
        // }
        // else{
        // $result = $this->Usermanage->fetch_view_po_new($this->user_id);
        // $manual_po_list = $this->Usermanage->fetch_view_po_manual($this->user_id);

        // $this->set('po_list',$result);
        // $this->set('manual_po',$manual_po_list);
        // $this->set('po_list',array());
        // $this->set('manual_po',array());
        // }

        if ($this->request->is('post')) {
            if (isset($this->request->data["go1"])) {
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                $post = $this->request->data;
                $or = array();

                if ($post['po_type'] == "po") {
                    $or["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;

                    $keys = array_keys($or, "");
                    foreach ($keys as $k) {unset($or[$k]);}
                    //debug($post);
                    //debug($or);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $result = $erp_inventory_po->find()->select($erp_inventory_po)->where(["project_id IN" => $projects_ids]);
                            $result = $result->innerjoin(
                                ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                                ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                                ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $result = array();
                        }
                    } else {
                        $result = $erp_inventory_po->find()->select($erp_inventory_po);
                        $result = $result->innerjoin(
                            ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                            ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                            ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }
                    $this->set('po_list', $result);
                    $this->set('manual_po', array());
                } else {
                    // For manual po search

                    $erp_manual_po = TableRegistry::get("erp_manual_po");
                    $erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
                    $or1 = array();

                    $or1["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or1["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or1["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or1["erp_manual_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or1["erp_manual_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or1["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or1["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;
                    if ($post['po_type'] == "manualpolocal") {
                        //for manual po on base of grn search
                        $or1["is_grn_base"] = 1;
                    } else {
                        $or1["is_grn_base !="] = 1;
                    }
                    $keys = array_keys($or1, "");
                    foreach ($keys as $k) {unset($or1[$k]);}
                    // debug($post);
                    // debug($or1);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $manual_po_list = $erp_manual_po->find()->select($erp_manual_po)->where(["project_id IN" => $projects_ids]);
                            $manual_po_list = $manual_po_list->innerjoin(
                                ["erp_manual_po_detail" => "erp_manual_po_detail"],
                                ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved !=" => 0])
                                ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $manual_po_list = array();
                        }
                    } else {
                        $manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
                        $manual_po_list = $manual_po_list->innerjoin(
                            ["erp_manual_po_detail" => "erp_manual_po_detail"],
                            ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved !=" => 0])
                            ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }

                    $this->set('po_list', array());
                    $this->set('manual_po', $manual_po_list);
                }
            }
            if (isset($this->request->data["export_csv"])) {
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"])) ? $post["e_po_no"] : null;
                $or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;
                $or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;

                if ($or["erp_inventory_po.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_inventory_po.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_inventory_po_detail.approved !="] = 0;

                $result = $erp_inventory_po->find()->select($erp_inventory_po);
                $result = $result->innerjoin(
                    ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                    ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
                    ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("P.O. No", "P.O.Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "Quantity", "Unit", "Final Rate", "Amount", "PO Type");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_inventory_po_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_inventory_po_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }
                    $po_type = $retrive_data['po_type'];
                    if ($po_type == "po") {
                        $type_name = "PO";
                    } elseif ($po_type == "manual_po") {
                        $type_name = "Manual PO";
                    } elseif ($po_type == "local_po") {
                        $type_name = "Local PO";
                    }

                    $csv = array();
                    $csv[] = $retrive_data['po_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['single_amount'];
                    $csv[] = $retrive_data['amount'];
                    $csv[] = $type_name;
                    $rows[] = $csv;
                }

                $filename = "po_records.csv";
                $this->ERPfunction->export_to_csv($filename, $rows);
            }

            if (isset($this->request->data["export_pdf"])) {
                require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"])) ? $post["e_po_no"] : null;
                $or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;
                $or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;

                if ($or["erp_inventory_po.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_inventory_po.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_inventory_po_detail.approved !="] = 0;

                $result = $erp_inventory_po->find()->select($erp_inventory_po);
                $result = $result->innerjoin(
                    ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                    ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
                    ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("P.O. No", "P.O.Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "Quantity", "Unit", "Final Rate", "Amount", "PO Type");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_inventory_po_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_inventory_po_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }
                    $po_type = $retrive_data['po_type'];
                    if ($po_type == "po") {
                        $type_name = "PO";
                    } elseif ($po_type == "manual_po") {
                        $type_name = "Manual PO";
                    } elseif ($po_type == "local_po") {
                        $type_name = "Local PO";
                    }

                    $csv = array();
                    $csv[] = $retrive_data['po_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['single_amount'];
                    $csv[] = $retrive_data['amount'];
                    $csv[] = $type_name;
                    $rows[] = $csv;
                }
                $this->set("rows", $rows);
                $this->render("porecordpdf");
            }
        }

        //// debug($result);
        //// debug($manual_po_list);die;
        //$this->set('po_list',$result);
        // $this->set('manual_po',$manual_po_list);
        //$this->set('manual_po',array());
    }


    public function viewporecords($projects_id = null, $from = null, $to = null)
    {
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'contract') !== false) {
            $back_url = 'contract';
            $back_page = 'billingmenu';
        } else {
            $back_url = 'Purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        ini_set('memory_limit', '-1');
        $this->set("user_role", $this->role);
        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        //$projects = $this->ERPfunction->get_projects();
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        if ($role == "deputymanagerelectric") {
            $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
            $material_ids = json_decode($material_ids);
            $material_list = $erp_material->find()->where(["material_id IN" => $material_ids]);
        } else {
            $material_list = $erp_material->find();
        }
        $this->set('material_list', $material_list);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        $erp_material_brand = TableRegistry::get('erp_material_brand');
        $brand_list = $erp_material_brand->find();
        $this->set('brand_list', $brand_list);

        $user = $this->request->session()->read('user_id');
        //var_dump($user);die;
        $role = $this->Usermanage->get_user_role($user);
        $projects_ids = $this->Usermanage->users_project($user);

        $this->set("projects_id", $projects_id);
        $this->set("from", $from);
        $this->set("to", $to);

        // if($projects_id!=null){

        // $erp_inventory_po = TableRegistry::get("erp_inventory_po");
        // $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");

        // $erp_manual_po = TableRegistry::get("erp_manual_po");
        // $erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
        // $total=0;
        // $or1 = array();

        // $or["erp_inventory_po_detail.approved_date >="] = date('Y-m-d',strtotime($from));
        // $or["erp_inventory_po_detail.approved_date <="] = date('Y-m-d',strtotime($to));
        // $or["project_id"] = $projects_id;
        // $keys = array_keys($or,"");
        // foreach ($keys as $k)
        // {unset($or[$k]);}

        // $or1["erp_manual_po_detail.approved_date >="] = date('Y-m-d',strtotime($from));
        // $or1["erp_manual_po_detail.approved_date <="] = date('Y-m-d',strtotime($to));
        // $or1["project_id"] = $projects_id;
        // $keys = array_keys($or1,"");
        // foreach ($keys as $k)
        // {unset($or1[$k]);}

        // $result = $erp_inventory_po->find()->select($erp_inventory_po);
        // $result1 = $result->innerjoin(
        // ["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
        // ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved ="=>1])->where($or)->select($erp_inventory_po_detail)->group('erp_inventory_po.po_no')->order(['erp_inventory_po_detail.approved_date'=>'DESC'])->hydrate(false)->toArray();

        // $manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
        // $manual_po_list1 = $manual_po_list->innerjoin(
        // ["erp_manual_po_detail"=>"erp_manual_po_detail"],
        // ["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved ="=>1])
        // ->where($or1)->select($erp_manual_po_detail)->group('erp_manual_po.po_no')->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();

        // $this->set('po_list',$result1);
        // $this->set('manual_po',$manual_po_list);
        // $this->set('manual_po',$manual_po_list);
        // }
        // else{
        // $result = $this->Usermanage->fetch_view_po_new($this->user_id);
        // $manual_po_list = $this->Usermanage->fetch_view_po_manual($this->user_id);

        // $this->set('po_list',$result);
        // $this->set('manual_po',$manual_po_list);
        // $this->set('po_list',array());
        // $this->set('manual_po',array());
        // }

        if ($this->request->is('post')) {
            if (isset($this->request->data["go1"])) {
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                $post = $this->request->data;
                $or = array();

                if ($post['po_type'] == "po") {
                    $or["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;

                    $keys = array_keys($or, "");
                    foreach ($keys as $k) {unset($or[$k]);}
                    //debug($post);
                    //debug($or);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $result = $erp_inventory_po->find()->select($erp_inventory_po)->where(["project_id IN" => $projects_ids]);
                            $result = $result->innerjoin(
                                ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                                ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                                ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $result = array();
                        }
                    } else {
                        $result = $erp_inventory_po->find()->select($erp_inventory_po);
                        $result = $result->innerjoin(
                            ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                            ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                            ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }
                    $this->set('po_list', $result);
                    $this->set('manual_po', array());
                } else {
                    // For manual po search

                    $erp_manual_po = TableRegistry::get("erp_manual_po");
                    $erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
                    $or1 = array();

                    $or1["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or1["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or1["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or1["erp_manual_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or1["erp_manual_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or1["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or1["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;
                    if ($post['po_type'] == "manualpolocal") {
                        //for manual po on base of grn search
                        $or1["is_grn_base"] = 1;
                    } else {
                        $or1["is_grn_base !="] = 1;
                    }
                    $keys = array_keys($or1, "");
                    foreach ($keys as $k) {unset($or1[$k]);}
                    // debug($post);
                    // debug($or1);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $manual_po_list = $erp_manual_po->find()->select($erp_manual_po)->where(["project_id IN" => $projects_ids]);
                            $manual_po_list = $manual_po_list->innerjoin(
                                ["erp_manual_po_detail" => "erp_manual_po_detail"],
                                ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved !=" => 0])
                                ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $manual_po_list = array();
                        }
                    } else {
                        $manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
                        $manual_po_list = $manual_po_list->innerjoin(
                            ["erp_manual_po_detail" => "erp_manual_po_detail"],
                            ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved !=" => 0])
                            ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }

                    $this->set('po_list', array());
                    $this->set('manual_po', $manual_po_list);
                }
            }
            if (isset($this->request->data["export_csv"])) {
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"])) ? $post["e_po_no"] : null;
                $or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;
                $or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;

                if ($or["erp_inventory_po.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_inventory_po.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_inventory_po_detail.approved !="] = 0;

                $result = $erp_inventory_po->find()->select($erp_inventory_po);
                $result = $result->innerjoin(
                    ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                    ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
                    ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("P.O. No", "P.O.Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "Quantity", "Unit", "Final Rate", "Amount", "PO Type");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_inventory_po_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_inventory_po_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }
                    $po_type = $retrive_data['po_type'];
                    if ($po_type == "po") {
                        $type_name = "PO";
                    } elseif ($po_type == "manual_po") {
                        $type_name = "Manual PO";
                    } elseif ($po_type == "local_po") {
                        $type_name = "Local PO";
                    }

                    $csv = array();
                    $csv[] = $retrive_data['po_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['single_amount'];
                    $csv[] = $retrive_data['amount'];
                    $csv[] = $type_name;
                    $rows[] = $csv;
                }

                $filename = "po_records.csv";
                $this->ERPfunction->export_to_csv($filename, $rows);
            }

            if (isset($this->request->data["export_pdf"])) {
                require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"])) ? $post["e_po_no"] : null;
                $or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;
                $or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;

                if ($or["erp_inventory_po.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_inventory_po.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_inventory_po_detail.approved !="] = 0;

                $result = $erp_inventory_po->find()->select($erp_inventory_po);
                $result = $result->innerjoin(
                    ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                    ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
                    ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("P.O. No", "P.O.Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "Quantity", "Unit", "Final Rate", "Amount", "PO Type");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_inventory_po_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_inventory_po_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }
                    $po_type = $retrive_data['po_type'];
                    if ($po_type == "po") {
                        $type_name = "PO";
                    } elseif ($po_type == "manual_po") {
                        $type_name = "Manual PO";
                    } elseif ($po_type == "local_po") {
                        $type_name = "Local PO";
                    }

                    $csv = array();
                    $csv[] = $retrive_data['po_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['single_amount'];
                    $csv[] = $retrive_data['amount'];
                    $csv[] = $type_name;
                    $rows[] = $csv;
                }
                $this->set("rows", $rows);
                $this->render("porecordpdf");
            }
        }

        //// debug($result);
        //// debug($manual_po_list);die;
        //$this->set('po_list',$result);
        // $this->set('manual_po',$manual_po_list);
        //$this->set('manual_po',array());
    }

    public function ammendporecords($po_id)
    {
        // debug($po_id);die;
        $erp_inve_po = TableRegistry::get('erp_inventory_po');
        $erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
        $erp_inve_po_data = $erp_inve_po->get($po_id);
        $erp_inve_po_details_data = $erp_inve_po_details->find()->where(['po_id' => $po_id, "approved" => 1])->order(['contract_no' => 'asc']);
        $this->set('erp_inve_po_data', $erp_inve_po_data);
        $this->set('erp_inve_po_details_data', $erp_inve_po_details_data);

        $can_update = 0;
        if ($erp_inve_po_data->updated == 1 && $erp_inve_po_data->ammend_approve == 0) {
            $can_update = 1;
        } else {
            $can_update = 0;
        }
        $this->set('can_update', $can_update);

        // debug($erp_inve_po_data);die;
        // $this->set('selected_pl',true);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);
        $projects = $this->Usermanage->access_project($this->user_id);
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        $material_list = $erp_material->find();
        $this->set('material_list', $material_list);

        $user_action = 'edit';
        $this->set('user_action', $user_action);
        $this->set('form_header', 'Edit Purchase Order (PO)');
        $this->set('button_text', 'Update Purchase Order (PO)');

        $this->set('po_id', $po_id);
        $data = $erp_inve_po_details->find()->where(["po_id" => $po_id, "approved" => 1])->hydrate(false)->toArray();
       
        $i = 0;
        $row = '';
        if (!empty($data)) {
            foreach ($data as $material) {
                $m_code = is_numeric($material['material_id']) ? $this->ERPfunction->get_material_item_code_bymaterialid($material['material_id']) : $material['m_code'];
                $unit = is_numeric($material['material_id']) ? $this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material['material_id'])) : $material['static_unit'];
                $m_code_row = '';
                $m_row = '';
                $b_row = '';
                $unit_row = '';
                if (is_numeric($material['material_id'])) {
                    $m_code_row .= '<td style="display:none;"><span id="material_code_' . $i . '">' . $m_code . '</span>
					<input type="hidden" value="" name="material[m_code][]" id="m_code_' . $i . '">
					<input type="hidden" value="' . $i . '" name="row_number" class="row_number">
					<input type="hidden" value="' . $material["id"] . '" name="material[detail_id][]">
					</td>';

                    $m_row .= '<select class="select2 material_id" style="width:130px;" name="material[material_id][]" id="material_id_' . $i . '" data-id=' . $i . '>
						<option value="">Select Material</Option>';
                    foreach ($material_list as $retrive_data) {
                        $selected = ($retrive_data['material_id'] == $material['material_id']) ? "selected" : "";
                        $m_row .= '<option value="' . $retrive_data['material_id'] . '"' . $selected . '>' .
                            $retrive_data['material_title'] . '</option>';
                    }
                    $m_row .= '</select>';
                    if ($material['description'] != null) {
                        $m_row .= '<input type="text" value="' . $material["description"] . '" placeholder="Description Here" class="desc_textfield" name="material[description][]" value=""  id="descriptionTextfield_' . $i . '" >';
                    } else {
                        $m_row .= '<input type="text" value="' . $material["description"] . '" placeholder="Description Here" class="desc_textfield" name="material[description][]" value="" id="descriptionTextfield_' . $i . '" style="display:none;">';
                    }

                    $b_row .= '<select class="select2 brand_id"  required="true"   name="material[brand_id][]" style="width:130px;" id="brand_id_' . $i . '" data-id=' . $i . '>';
                    $brands = $this->ERPfunction->get_brands_by_material_id($material["material_id"]);
                    if ($brands != "") {
                        foreach ($brands as $brand) {
                            $b_row .= '<option value="' . $brand['brand_id'] . '"' . $this->ERPfunction->selected($brand['brand_id'], $material['brand_id']) . '>' . $brand['brand_name'] . '</option>';
                        }
                    }

                    $b_row .= '</select>';

                    $unit_row .= '<td><span id="unit_name_' . $i . '">' . $unit . '</span>
					<input type="hidden" value="" name="material[static_unit][]" id="static_unit_' . $i . '" class="form-control" style="width:80px;">
					</td>';
                } else {
                    $m_code_row .= '<td style="display:none;"><span id="material_code_' . $i . '">' . $m_code . '</span>
					<input type="hidden" value="' . $m_code . '" name="material[m_code][]" id="m_code_' . $i . '">
					<input type="hidden" value="1" name="material[is_custom][]">
					<input type="hidden" value="' . $i . '" name="row_number" class="row_number">
					<input type="hidden" value="' . $material["id"] . '" name="material[detail_id][]">
					</td>';

                    $m_row .= '<input type="text" name="material[material_id][]" value="' . htmlspecialchars($material["material_id"]) . '" id="material_id_' . $i . '" data-id="' . $i . '" class="form-control material_id" style="width:120px;"/>';
                    $b_row .= '<input type="text" name="material[brand_id][]" value="' . htmlspecialchars($material["brand_id"]) . '" id="brand_id_' . $i . '" class="form-control" style="width:120px;"/>';
                    $unit_row .= '<td><input type="text" value="' . htmlspecialchars($unit) . '" name="material[static_unit][]" id="static_unit_' . $i . '" class="form-control" class="form-control" style="width:120px;"></td>';
                }

                $row .= '<tr class="cpy_row" id="row_id_' . $i . '">
							' . $m_code_row . '
							<td>' . $m_row . '
							</td>';
                $row .= '<td>'
                    . $b_row . '</td>
							<td><input type="text" name="material[quantity][]" data-id="' . $i . '" class="quantity" value="' . $material["quantity"] . '" id="quantity_' . $i . '" style="width:60px"/></td>
							' . $unit_row . '
							<td><input type="text" name="material[unit_rate][]" class="unit_rate" value="' . $material["unit_price"] . '" data-id="' . $i . '" id="unit_rate_' . $i . '" style="width:80px"/>
							<input type="hidden" value="' . $material["pr_mid"] . '" name="material[pr_mid][]"></td>
							<td><input type="text" name="material[discount][]" value="' . $material["discount"] . '" class="tx_count" id="dc_' . $i . '" data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[gst][]" value="' . $material["gst"] . '" class="tx_count" id="gst_' . $i . '"  data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[amount][]" class="amount" value="' . $material["amount"] . '" id="amount_' . $i . '" style="width:90px" /></td>
							<td><input type="text" name="material[single_amount][]" value="' . $material["single_amount"] . '" id="single_amount_' . $i . '" style="width:90px"/></td>

							<input type="hidden" name="po_mid[]" value="' . $material["id"] . '">
							<td>
								<a href="javascript:void(0)" class="btn btn-primary add_textfield" onClick="insertRow()" id="textfield_0" data-id="' . $material["id"] . '" value="textfield">Textfield</a>
								<a href="javascript:void(0)" class="btn btn-danger del_parent" data-id="' . $material["id"] . '">Delete</a>
							</td>
						</tr>';

                $i++;
            }
        }
        $this->set("row", $row);

        if ($this->request->is('post')) {
            // debug($this->request->data);
            $post = $this->request->data;
            if ($erp_inve_po_data->updated == 0) {
                $related_po_id[] = $po_id;
                if ($erp_inve_po_data->related_po_id != '' && $erp_inve_po_data->related_po_id != null) {
                    $old_related = explode(",", $erp_inve_po_data->related_po_id);
                    $related_po_id = array_merge($old_related, $related_po_id);
                }
                $save['child_po_id'] = $po_id;
                $save['related_po_id'] = implode(",", $related_po_id);
            }
            $save['project_id'] = $post['project_id'];
            $save['bill_mode'] = $post['bill_mode'];
            $save['usage_name'] = $post['usage_name'];
            $save['agency_id'] = $post['agency_id'];
            $save['po_no'] = $post['po_no'];
            $save['po_date'] = date('Y-m-d', strtotime($post['po_date'])); //$this->ERPfunction->set_date($post['po_date']);
            $save['po_time'] = $post['po_time'];
            $save['vendor_userid'] = $post['vendor_userid'];
            $save['vendor_id'] = $post['vendor_id'];
            $save['vendor_address'] = $post['vendor_address'];
            $save['custom_pan'] = $post['custom_pan'];
            $save['custom_gst'] = $post['custom_gst'];
            $save['contact_no1'] = $post['contact_no1'];
            $save['contact_no2'] = $post['contact_no2'];
            $save['vendor_email'] = $post['vendor_email'];
            $save['delivery_type'] = $post['delivery_type'];
            $save['delivery_project'] = $post['delivery_project'];
            $save['vendor_delivery_address'] = $post['vendor_delivery_address'];
            $save['project_address'] = $post['project_address'];
            $save['payment_method'] = $post['payment_method'];
            $save['mode_of_gst'] = $post['mode_of_gst'];
            $save['taxes_duties'] = isset($post['taxes_duties']) ? $post['taxes_duties'] : '0';
            $save['loading_transport'] = isset($post['loading_transport']) ? $post['loading_transport'] : '0';
            $save['unloading'] = isset($post['unloading']) ? $post['unloading'] : '0';
            $save['warranty_check'] = isset($post['warranty_check']) ? $post['warranty_check'] : '0';
            $save['warranty'] = $post['warranty'];
            $save['gstno'] = $post['gstno'];
            $save['payment_days'] = $post['payment_days'];
            $save['remarks'] = $post['remarks'];
            $save['mail_check'] = $post['mail_check'];
            $save['created_date'] = date('Y-m-d H:i:s');
            $save['created_by'] = $this->request->session()->read('user_id');
            $save['updated'] = 1;
            $save['last_po'] = 1;
            if ($erp_inve_po_data->updated == 1 && $erp_inve_po_data->ammend_approve == 0) {
                $ammend_row = $erp_inve_po->get($po_id);
            } else {
                $ammend_row = $erp_inve_po->newEntity();
            }
            $save_data = $erp_inve_po->patchEntity($ammend_row, $save);
            if ($erp_inve_po->save($save_data)) {

                $po_id = $save_data->po_id;
                $this->ERPfunction->add_ammend_po_details($post['material'], $po_id, $erp_inve_po_data);
                $this->ERPfunction->edit_inventory_po_grn_detail($post['material']);
                // Update old record
                if (!$can_update) {
                    $erp_inve_po_data->last_po = 0;
                    $erp_inve_po->save($erp_inve_po_data);
                }
                $this->Flash->success(__('Data Update Successfully', null),
                    'default',
                    array('class' => 'success'));
                $this->redirect(array("controller" => "Purchase","action" => "viewammendporecords"));	
            }
        }
    }

    public function editprmaterial()
    {
        $this->autoRender = false;
        $post = $this->request->data;
        $pr_mat_tbl = TableRegistry::get("erp_inventory_pr_material");
        $row = $pr_mat_tbl->get($post["pr_material_id"]);
        $row->material_id = $post["material_id"];
        $row->quantity = $post["quantity"];
        $row->brand_id = $post["brand_id"];
        if ($pr_mat_tbl->save($row)) {
            $this->Flash->success(__('Material Updated Successfully', null),
                'default',
                array('class' => 'success'));
            $this->redirect(["action" => "approvedpr"]);
        }
    }

    public function cancelpo($po_id = null)
    {

        $pom_tbl = TableRegistry::get("erp_inventory_po_detail");
        $po_tbl = TableRegistry::get("erp_inventory_po");
        $prm_tbl = TableRegistry::get("erp_inventory_pr_material");
        $delpom_tbl = TableRegistry::get("erp_inventory_deleted_po_detail");
        $delpo_tbl = TableRegistry::get("erp_inventory_deleted_po");

        if ($po_id != null) {
            $get_deleted_po = $po_tbl->get($po_id);
            $deleted_po = $get_deleted_po->toArray();
            $mail_check = $deleted_po["mail_check"];
            $del_po_project_id = $deleted_po["project_id"];
            $del_po_no = $deleted_po["po_no"];
            $del_po_project_name = $this->ERPfunction->get_projectname($deleted_po["project_id"]);
            $del_po_party_name = $this->ERPfunction->get_vendor_name($deleted_po["vendor_userid"]);

            $pdpmcm_email = $this->ERPfunction->get_email_of_pd_pm_cm_by_project($del_po_project_id, $po_id);
            $mm_email = $this->ERPfunction->get_email_of_mm_by_project($del_po_project_id);

            $po_detail = $pom_tbl->find("all")->where(["po_id" => $po_id, "approved" => 1]);

            //Check if this po pr is pending or not
            $have_pending = 0;
            foreach ($po_detail as $rows) {
                if ($rows["pr_mid"] != 0) {
                    $prm_id = $rows["pr_mid"];
                    $prm_detail = $prm_tbl->get($prm_id);
                    if ($prm_detail->po_completed != 0) {
                        $have_pending = 1;
                    }
                }
            }
            //If the po have pr material it has pending pr then show entry in purchase pr alert

            if ($have_pending) {
                foreach ($po_detail as $rows1) {

                    if ($rows1["pr_mid"] != 0) {
                        $prm_id = $rows1["pr_mid"];
                        $pr_tbl = TableRegistry::get("erp_inventory_pr_material");
                        $prm_detail = $pr_tbl->get($rows1["pr_mid"]);
                        //set po quantity in pr material po approved quentity
                        $prm_detail->po_approved_quantity = $rows1["quantity"];
                        //set pr actual quantity po created quentity + pending pr quantity
                        $prm_detail->quantity = $rows1["quantity"] + $prm_detail->po_pending_quantity;
                        //set po_completed so it show in purchase pr alert
                        $prm_detail->po_completed = 3;
                        //set approved so it show in purchase pr alert
                        $prm_detail->approved = 0;
                        $pr_tbl->save($prm_detail);
                    }
                }
            }
            if (!empty($po_detail)) {
                $query = $pom_tbl->query();
                $query->update()
                    ->set(['first_approved' =>0,
                        'verified' => 0,
                        'approved' => 0,
                        "approved_by" => "",
                        "approved_date" => NULL])
                    ->where(['po_id' => $po_id])
                    ->execute();

                $get_deleted_po = $po_tbl->get($po_id);
                // $deleted_po = $get_deleted_po->toArray();
                // $deleted_po["deleted_by"] = $this->user_id;
                // $deleted_po = $delpo_tbl->newEntity($deleted_po);
                // $delpo_tbl->save($deleted_po);

                // $deleted_details = $po_detail->hydrate(false)->toArray();
                // foreach($deleted_details as $copy)
                // {
                // $save_d_r = $delpom_tbl->newEntity($copy);
                // $delpom_tbl->save($save_d_r);
                // }

                // $pom_tbl->deleteAll(["po_id"=>$po_id,"approved"=>1]);

                // $count = $pom_tbl->find("all")->where(["po_id"=>$po_id])->count();
                // if($count == 0) /* Make Sure ALL PO Materials are deleted before deleting po */
                // {
                // $ok = $po_tbl->delete($get_deleted_po);
                // }

                // foreach($po_detail as $rows)
                // {
                // if($rows["pr_mid"] != 0)
                // {
                // $prm_id = $rows["pr_mid"];
                // $prm_detail = $prm_tbl->get($prm_id);
                // $prm_detail->approved = 0;
                // $prm_detail->approved_by = 0;
                // $prm_detail->approved_date = null;
                // $prm_tbl->save($prm_detail);
                // }
                // }

                if ($query) {
                    $projectdetail = TableRegistry::get('erp_projects');
                    $project_data = $projectdetail->get($del_po_project_id);
                    $code = $project_data->project_code;

                    $new_pono = $this->ERPfunction->generate_auto_id($del_po_project_id, "erp_inventory_po", "po_id", "po_no");
                    $new_pono = sprintf("%09d", $new_pono);
                    $new_pono = $code . '/PO/' . $new_pono;

                    $update_data['po_no'] = $new_pono;
                    $data = $po_tbl->patchEntity($get_deleted_po, $update_data);
                    $po_tbl->save($data);
                    if ($mail_check == 1) {
                        $emails1 = array();
                        $emails2 = array();
                        // $project_wise_role = ['deputymanagerelectric'];
                        // $project_email = $this->ERPfunction->get_email_id_by_project_from_user($del_po_project_id,$project_wise_role);
                        // $emails1 = array_merge($emails1,$project_email);

                        $emails1 = array_merge($pdpmcm_email, $emails1);
                        $emails2 = array_merge($mm_email, $emails2);
                        $role = ['erphead', 'erpmanager', 'erpoperator', 'ceo'];
                        $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                        $emails2 = array_merge($erp_email, $emails2);
                        $pdpmcm_email = array_unique($emails1); /*remove duplicate email ids */
                        $mm_email = array_unique($emails2); /*remove duplicate email ids */
                        $pdpmcm_email = array_filter($pdpmcm_email, function ($value) {return $value !== '';});
                        $mm_email = array_filter($mm_email, function ($value) {return $value !== '';});

                        $all_users = array_unique(array_merge($pdpmcm_email, $mm_email));
                    } elseif ($mail_check == 2) {
                        $emails1 = array();
                        $emails2 = array();
                        $project_wise_role = ['deputymanagerelectric'];
                        $project_email = $this->ERPfunction->get_email_id_by_project_from_user($del_po_project_id, $project_wise_role);
                        $emails1 = array_merge($emails1, $project_email);

                        $emails1 = array_merge($pdpmcm_email, $emails1);
                        $emails2 = array_merge($mm_email, $emails2);
                        $role = ['erphead', 'erpmanager', 'erpoperator', 'ceo'];
                        $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                        $emails2 = array_merge($erp_email, $emails2);
                        $pdpmcm_email = array_unique($emails1); /*remove duplicate email ids */
                        $mm_email = array_unique($emails2); /*remove duplicate email ids */
                        $pdpmcm_email = array_filter($pdpmcm_email, function ($value) {return $value !== '';});
                        $mm_email = array_filter($mm_email, function ($value) {return $value !== '';});

                        $all_users = array_unique(array_merge($pdpmcm_email, $mm_email));
                    } else {
                        $emails = array();
                        $role = ['erphead', 'erpmanager', 'purchasehead', 'purchasemanager', 'md', 'erpoperator', 'ceo'];
                        $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                        $emails = array_merge($erp_email, $emails);
                        $emails = array_unique($emails); /*remove duplicate email ids */
                        $all_users = array_filter($emails, function ($value) {return $value !== '';});
                    }

                    // debug($all_users);die;
                    if (!empty($all_users)) {
                        $all_users = implode(",", $all_users);
                        $this->ERPfunction->cancel_po_mail($all_users, $del_po_no, $del_po_project_name, $del_po_party_name);
                    }

                    // if(!empty($pdpmcm_email))
                    // {
                    // $pdpmcm_email = implode(",",$pdpmcm_email);
                    // $this->ERPfunction->cancel_po_mail($pdpmcm_email,$del_po_no,$del_po_project_name,$del_po_party_name);
                    // }

                    // if(!empty($mm_email))
                    // {
                    // $mm_email = implode(",",$mm_email);
                    // $this->ERPfunction->cancel_po_mail($mm_email,$del_po_no,$del_po_project_name,$del_po_party_name);
                    // }
                }
            }

            $this->Flash->success(__('P.O. Cancelled Successfully.Record will show in P.O. Alert page.', null),
                'default',
                array('class' => 'success'));
            $this->redirect(["action" => "viewporecords"]);
        }
    }

    public function potoxls($prid)
    {
        require_once ROOT . DS . 'vendor' . DS . 'PHPExcel' . DS . 'PHPExcel.php';

        $pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
        $prm_tbl = TableRegistry::get("erp_inventory_pr_material");

        $pr_data = $pr_tbl->get($prid)->toArray();
        $this->set("pr_data", $pr_data);
        // debug($pr_data);die;

        $prm_data = $prm_tbl->find()->where(["pr_id" => $prid, "OR" => [["approved" => 1], ["show_in_purchase" => 1]]])->hydrate(false)->toArray();
        $this->set("prm_data", $prm_data);
        // debug($prm_data);die;
    }

    public function unapprovepr($pr_material_id)
    {
        $tbl = TableRegistry::get("erp_inventory_pr_material");
        $row = $tbl->get($pr_material_id);
        $row->approved_for_grnwithoutpo = 0;
        $row->approved = 0;
        $row->approved_by = 0;
        $row->approved_date = null;
        $row->purchase_first_approve = 0;
        $row->purchase_first_approveby = null;
        $row->purchase_first_approve_date = null;
        $row->due_date = null;
        $row->show_in_purchase = 1; //Change 0 to 1 for show in purchase/approvedpr
        $tbl->save($row);
        $this->Flash->success(__('P.R. Unapproved Successfully.', null),
            'default',
            array('class' => 'success'));
        $this->redirect(["action" => "approvedpr"]);
    }

    public function deletepurchasefirstpoalert($pr_mid, $pom_id,$poId)
    {
        // debug($pr_mid);die;
        // if($pr_mid)
        // {
			// debug($pr_mid);die;
			$tbl = TableRegistry::get('erp_inventory_po_detail');
			$query = $tbl->query();
			$query->update()
				->set(['first_approved' => 0, "first_approved_by" => null, "first_approved_date" => null])
				->where(['po_id' => $poId])
				->execute();

			//     $tbl = TableRegistry::get("erp_inventory_pr_material");
			//     $row = $tbl->get($pr_mid);
			//     $row->approved = 0;
			//     $row->po_completed = 2;
			//     $row->po_pending_quantity = $row->po_pending_quantity + $row->po_approved_quantity;
			//     $row->po_approved_quantity = 0;
			//     $row->approved_by = 0;
			//     $row->approved_date = null;
			//     $check = $tbl->save($row);
			//     if(isset($check->approved) && $check->approved == 0)
			//     {
			//         $tbl_po = TableRegistry::get("erp_inventory_po_detail");
			//         $data = $tbl_po->get($pom_id);
			//         $tbl_po->delete($data);
			//     }
		// }else {
            // $tbl_po = TableRegistry::get("erp_inventory_po");
            // $data = $tbl_po->get($pom_id);
            // $tbl_po->delete($data);
        // }
        $this->Flash->success(__('P.O. Reverse Successfully.', null),
            'default',
            array('class' => 'success'));
        $this->redirect(["controller" => "inventory", "action" => "approvepo"]);
    }

    public function deletepurchasepoalert($pr_mid, $po_id) { //pom_id to po_id
        // debug($po_id);die;
        if ($pr_mid) {
            $tbl = TableRegistry::get("erp_inventory_pr_material");
            $row = $tbl->get($pr_mid);
            $row->approved = 0;
            $row->po_completed = 2;
            $row->po_pending_quantity = $row->po_pending_quantity + $row->po_approved_quantity;
            $row->po_approved_quantity = 0;
            $row->approved_by = 0;
            $row->approved_date = null;
            $check = $tbl->save($row);
            if (isset($check->approved) && $check->approved == 0) {
                $tbl_po = TableRegistry::get("erp_inventory_po_detail");
                $po_data = $tbl_po->find()->where(["po_id" =>$po_id])->hydrate(false)->toArray();
                $data = "";
                foreach($po_data as $poData) {
                    $poDetailId = $poData['id'];
                    $data = $tbl_po->get($poDetailId);
                    $tbl_po->delete($data);
                }
                // $data = $tbl_po->get($po_id);
                // $tbl_po->delete($data);
            }
        }else {
            $tbl_po = TableRegistry::get("erp_inventory_po_detail");
            $po_data = $tbl_po->find()->where(["po_id" =>$po_id])->hydrate(false)->toArray();
            $data = "";
            foreach($po_data as $poData) {
                $poDetailId = $poData['id'];
                $data = $tbl_po->get($poDetailId);
                $tbl_po->delete($data);
            }
        }
        $this->Flash->success(__('P.O. Deleted Successfully.', null),
            'default',
            array('class' => 'success'));
        $this->redirect(["action" => "approvedpr"]);
    }

    public function deletemanualpoalert($pom_id)
    {

        $tbl_po = TableRegistry::get("erp_manual_po_detail");
        $data = $tbl_po->get($pom_id);
        $tbl_po->delete($data);

        $this->Flash->success(__('P.O. Deleted Successfully.', null),
            'default',
            array('class' => 'success'));
        $this->redirect(["action" => "manualapprovepo"]);
    }

    public function addrate()
    {
        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        // $projects = $this->Usermanage->all_access_project($this->user_id);
        $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        if ($this->role == 'deputymanagerelectric') {
            $material_list = $erp_material->find()->where(['material_code IN' => ['6', '7', '10', '15']]);
        } else {
            $material_list = $erp_material->find();
        }

        $this->set('material_list', $material_list);

        if ($this->request->is('post')) {
            if (isset($this->request->data["go"])) {
                $post = $this->request->data;
                //debug($post);die;
                $rate_from = $post['rate_from_date'];
                $rate_to = $post['rate_to_date'];
                $taxes_duties = $post['taxes_duties'];
                $loading_trans = $post['loading_trans'];
                $unloading = $post['unloading'];
                $erp_finalized_rate = TableRegistry::get('erp_finalized_rate');

                $row = $erp_finalized_rate->newEntity();
                $row['vendor_userid'] = $post['vendor_userid'];
                $row['vendor_id'] = $post['vendor_id'];
                $row['vendor_address'] = $post['vendor_address'];
                $row['contact_no1'] = $post['contact_no1'];
                $row['contact_no2'] = $post['contact_no2'];
                $row['vendor_email'] = $post['email'];
                $row['pan_card_no'] = $post['pan_card_no'];
                $row['gst_no'] = $post['gst_no'];
                $row['payment_method'] = $post['payment_method'];
                $row['created_date'] = date('Y-m-d H:i:s');
                $row['created_by'] = $this->request->session()->read('user_id');

                if ($erp_finalized_rate->save($row)) {
                    $this->Flash->success(__('Record Insert Successfully', null),
                        'default',
                        array('class' => 'success'));
                    $rate_id = $row->rate_id;

                    $this->ERPfunction->add_purchase_rate_project($post['project_id'], $rate_id);

                    $this->ERPfunction->add_purchase_rate_detail($post['material'], $rate_id, $rate_from, $rate_to, $taxes_duties, $loading_trans, $unloading);
                }
                $this->redirect(array("controller" => "purchase", "action" => "addrate"));
            }
        }
    }

    public function ratealert()
    {
        $detail_tbl = TableRegistry::get('erp_finalized_rate_detail');
        $erp_rate_assign_project = TableRegistry::get('erp_rate_assign_project');
        // $rate_data = $detail_tbl->find()->where(["approved"=>0])->hydrate(false)->toArray();

        $projects_ids = $this->Usermanage->users_project($this->user_id);
        $role = $this->Usermanage->get_user_role($this->user_id);
        $this->set('role', $role);
        $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
        $material_ids = json_decode($material_ids);
        if ($role == 'deputymanagerelectric') {
            if (!empty($projects_ids)) {
                $result = $erp_rate_assign_project->find()->select($erp_rate_assign_project)->where(['project_id IN' => $projects_ids]);
                // debug($result);die;
                $rate_data = $result->innerjoin(
                    ["erp_finalized_rate_detail" => "erp_finalized_rate_detail"],
                    ["erp_finalized_rate_detail.rate_id = erp_rate_assign_project.rate_id", "erp_finalized_rate_detail.approved" => 0])
                    ->where(['erp_finalized_rate_detail.material_id IN' => $material_ids])->select($detail_tbl)->hydrate(false)->toArray();
            } else {
                $rate_data = array();
            }
        } else {
            $rate_data = $detail_tbl->find()->where(["approved" => 0])->hydrate(false)->toArray();
        }

        $this->set("rate_data", $rate_data);
        $this->set("role", $this->role);
    }

    public function viewaddrate($rate_id, $status = null)
    {
        $projects = $this->Usermanage->all_access_project($this->user_id);
        $this->set('projects', $projects);

        $rate_tbl = TableRegistry::get('erp_finalized_rate');
        $rate_data = $rate_tbl->find()->where(["rate_id" => $rate_id])->hydrate(false)->toArray();
        $this->set('rate_data', $rate_data[0]);

        $detail_tbl = TableRegistry::get('erp_finalized_rate_detail');
        if ($status == 'approve') {
            $rate_detail_data = $detail_tbl->find()->where(["rate_id" => $rate_id, "approved" => 1])->hydrate(false)->toArray();
        } else {
            $rate_detail_data = $detail_tbl->find()->where(["rate_id" => $rate_id, "approved" => 0])->hydrate(false)->toArray();
        }
        $this->set("detail_data", $rate_detail_data);
    }

    public function editrate($rate_id)
    {
        $rate_tbl = TableRegistry::get('erp_finalized_rate');
        $rate_data = $rate_tbl->find()->where(["rate_id" => $rate_id])->hydrate(false)->toArray();
        $this->set('rate_data', $rate_data[0]);

        $detail_tbl = TableRegistry::get('erp_finalized_rate_detail');
        $rate_detail_data = $detail_tbl->find()->where(["rate_id" => $rate_id, "approved" => 0])->hydrate(false)->toArray();
        $this->set("detail_data", $rate_detail_data);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        $projects = $this->Usermanage->all_access_project($this->user_id);
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        $material_list = $erp_material->find();
        $this->set('material_list', $material_list);

        if ($this->request->is('post')) {
            if (isset($this->request->data["go"])) {
                $post = $this->request->data;
                $rate_from = $post['rate_from_date'];
                $rate_to = $post['rate_to_date'];
                $taxes_duties = $post['taxes_duties'];
                $loading_trans = $post['loading_trans'];
                $unloading = $post['unloading'];

                $erp_finalized_rate = TableRegistry::get('erp_finalized_rate');
                $row = $erp_finalized_rate->get($rate_id);
                $row['vendor_userid'] = $post['vendor_userid'];
                $row['vendor_id'] = $post['vendor_id'];
                $row['vendor_address'] = $post['vendor_address'];
                $row['contact_no1'] = $post['contact_no1'];
                $row['contact_no2'] = $post['contact_no2'];
                $row['vendor_email'] = $post['email'];
                $row['pan_card_no'] = $post['pan_card_no'];
                $row['gst_no'] = $post['gst_no'];
                $row['payment_method'] = $post['payment_method'];

                if ($erp_finalized_rate->save($row)) {
                    $this->Flash->success(__('Record Update Successfully', null),
                        'default',
                        array('class' => 'success'));
                    $rate_id = $row->rate_id;

                    $this->ERPfunction->add_purchase_rate_project($post['project_id'], $rate_id);

                    $this->ERPfunction->edit_purchase_rate_detail($post['material'], $rate_id, $rate_from, $rate_to, $taxes_duties, $loading_trans, $unloading);
                }
                $this->redirect(array("controller" => "purchase", "action" => "ratealert"));
            }
        }
    }

    public function raterecords()
    {
        $projects_ids = $this->Usermanage->users_project($this->user_id);
        $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        $this->set('projects', $projects);

        $this->set('role', $this->role);
        $role = $this->Usermanage->get_user_role($this->user_id);
        $erp_material = TableRegistry::get('erp_material');
        if ($role == 'deputymanagerelectric') {
            $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
            $material_ids = json_decode($material_ids);
            $material_list = $erp_material->find()->where(['material_id IN' => $material_ids]);
        } else {
            $material_list = $erp_material->find();
        }
        $this->set('material_list', $material_list);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        if ($this->request->is("post")) {
            if (isset($this->request->data['go'])) {
                $rate_tbl = TableRegistry::get("erp_finalized_rate");
                $rated_tbl = TableRegistry::get("erp_finalized_rate_detail");
                $assign_tbl = TableRegistry::get("erp_rate_assign_project");
                $post = $this->request->data;
                $or = array();

                $or["erp_finalized_rate_detail.rate_from_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                $or["erp_finalized_rate_detail.rate_to_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                $or["erp_rate_assign_project.project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                $or["erp_finalized_rate_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                $or["erp_finalized_rate.vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                $or["erp_finalized_rate_detail.approved"] = 1;

                if ($or["erp_rate_assign_project.project_id IN"] == null) {
                    if ($role == 'deputymanagerelectric') {
                        $or["erp_rate_assign_project.project_id IN"] = $projects_ids;
                    }
                }
                if ($or["erp_finalized_rate_detail.material_id IN"] == null) {
                    if ($role == 'deputymanagerelectric') {
                        $or["erp_finalized_rate_detail.material_id IN"] = $material_ids;
                    }
                }
                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $result = $rated_tbl->find()->select($rated_tbl)->group(['rate_detail_id']);
                $result = $result->leftjoin(
                    ["erp_finalized_rate" => "erp_finalized_rate"],
                    ["erp_finalized_rate_detail.rate_id = erp_finalized_rate.rate_id"])->select($rate_tbl);
                $result = $result->leftjoin(
                    ["erp_rate_assign_project" => "erp_rate_assign_project"],
                    ["erp_finalized_rate.rate_id = erp_rate_assign_project.rate_id"])
                    ->where($or)->select(["project_id" => 'group_concat(project_id)'])->hydrate(false)->toArray();
                $this->set('rate_record', $result);
                //debug($result);die;
            }

            if (isset($this->request->data["export_csv"])) {
                $rows = unserialize($this->request->data["rows"]);
                $filename = "approverate.csv";
                $this->ERPfunction->export_to_csv($filename, $rows);
            }
            if (isset($this->request->data["export_pdf"])) {
                require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';
                $rows = unserialize($this->request->data["rows"]);
                $this->set("rows", $rows);
                $this->render("approveratepdf");
            }
        }
    }

    public function deleterate($rate_detail_id)
    {
        $detail_tbl = TableRegistry::get("erp_finalized_rate_detail");
        $row = $detail_tbl->get($rate_detail_id);
        $rate_id = $row->rate_id;
        if ($detail_tbl->delete($row)) {
            $count = $detail_tbl->find()->where(["rate_id" => $rate_id])->count();
            if ($count == 0) {
                $tbl = TableRegistry::get("erp_finalized_rate");
                $row = $tbl->get($rate_id);
                $tbl->delete($row);
            }

            // echo $count; die;
            $this->Flash->success(__('Record Deleted Successfully', null),
                'default',
                array('class' => 'success'));
            $this->redirect(["action" => "raterecords"]);
        }
    }

    public function manualpreparepo($id)
    {
        $this->set("projectId", $id);
        $erp_inventory_po = TableRegistry::get('erp_inventory_po');

        $erp_material = TableRegistry::get('erp_material');
        $array = [0,$id];
        if ($this->role == "deputymanagerelectric") {
            $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
            $material_ids = json_decode($material_ids);
            $material_list = $erp_material->find()->where(['material_id IN' => $material_ids, "material_code !=" => 17]);
        } else {
            $material_list = $erp_material->find()->where(["material_code !=" => 17, "project_id IN" => $array])->hydrate(false)->toArray();
        }
        $this->set('material_list', $material_list);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        $erp_agency = TableRegistry::get('erp_agency');
        $agency_list = $erp_agency->find();
        $this->set('agency_list', $agency_list);

        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        $this->set('projects', $projects);
        $this->set("back", "index");
        $this->set("controller", "purchase");
        $this->set('form_header', 'PREPARE PO (Manual)');
        $this->set('button_text', 'Add Purchase Order (Manual)');

        if ($this->request->is('post')) {
            $data = $this->request->data();
            // debug($data);die;
            $code = $this->ERPfunction->get_projectcode($data['project_id']);
            $new_pono = $this->ERPfunction->generate_auto_id($data['project_id'], "erp_inventory_po", "po_id", "po_no");
            $new_pono = sprintf("%09d", $new_pono);
            $new_pono = $code . '/PO/' . $new_pono;

            $this->request->data['po_no'] = $new_pono;
            $this->request->data['po_purchase_type'] = "manual_po";
            $this->request->data['po_date'] = $this->ERPfunction->set_date($this->request->data['po_date']);
            // $this->request->data['delivery_date']=$this->ERPfunction->set_date($this->request->data['delivery_date']);
            $this->request->data['taxes_duties'] = isset($this->request->data['taxes_duties']) ? $this->request->data['taxes_duties'] : '0';
            $this->request->data['loading_transport'] = isset($this->request->data['loading_transport']) ? $this->request->data['loading_transport'] : '0';
            $this->request->data['unloading'] = isset($this->request->data['unloading']) ? $this->request->data['unloading'] : '0';
            $this->request->data['created_date'] = date('Y-m-d H:i:s');
            $this->request->data['created_by'] = $this->request->session()->read('user_id');
            // $this->request->data['custom_pan']=$this->request->data['custom_pan'];
            // $this->request->data['custom_gst']=$this->request->data['custom_gst'];
            $this->request->data['status'] = 1;

            if (!isset($this->request->data["warranty_check"])) {
                $this->request->data["warranty_check"] = "";
            }

            $entity_data = $erp_inventory_po->newEntity();
            $post_data = $erp_inventory_po->patchEntity($entity_data, $this->request->data);
            // debug($post_data);die;
            if ($erp_inventory_po->save($post_data)) {
                $this->Flash->success(__('PO Created Successfully with PO No ' . $new_pono, null),
                    'default',
                    array('class' => 'success'));
                $po_id = $post_data->po_id;

                $this->ERPfunction->add_manual_po_detail($this->request->data['material'], $po_id);
            }
            $this->redirect(array("controller" => "Inventory", "action" => "approvepo"));
        }
    }

    public function editmanualpreparepo($po_id)
    {
        $this->set('selected_pl', true);
        //debug($po_materials);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        $erp_agency = TableRegistry::get('erp_agency');
        $agency_list = $erp_agency->find();
        $this->set('agency_list', $agency_list);

        $projects = $this->Usermanage->access_project($this->user_id);
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        if ($this->role == "deputymanagerelectric") {
            $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
            $material_ids = json_decode($material_ids);
            $material_list = $erp_material->find()->where(['material_id IN' => $material_ids, "material_code !=" => 17]);
        } else {
            $material_list = $erp_material->find()->where(["material_code !=" => 17]);
        }
        $this->set('material_list', $material_list);

        $user_action = 'edit';
        $this->set('user_action', $user_action);
        $this->set('form_header', 'Edit Purchase Order (Manual)');
        $this->set('button_text', 'Update Purchase Order (Manual)');

        $erp_manual_po = TableRegistry::get('erp_manual_po');
        $erp_manual_po_detail = TableRegistry::get('erp_manual_po_detail');
        $erp_po_details = $erp_manual_po->get($po_id);

        $this->set('erp_po_details', $erp_po_details);
        $previw_list = $erp_manual_po_detail->find()->where(array('po_id' => $po_id));
        $this->set('previw_list', $previw_list);

        $this->set('po_id', $po_id);

        $data = $erp_manual_po_detail->find()->where(["po_id" => $po_id, "approved" => 0])->hydrate(false)->toArray();
        //debug($data);
        $i = 0;
        $row = '';
        if (!empty($data)) {
            foreach ($data as $material) {

                //$po_id = $post["selected_po_id_{$material['id']}"];
                //$pr_id = $po_tbl->find()->where(["po_id"=>$post["selected_po_id_{$material['id']}"]])->select(["pr_id"])->hydrate(false)->toArray();

                $mt = is_numeric($material['material_id']) ? $this->ERPfunction->get_material_title
                ($material['material_id']) : $material['material_id'];

                $brnd = is_numeric($material['brand_id']) ? $this->ERPfunction->get_brand_name($material["brand_id"]) : $material["brand_id"];

                $unit = is_numeric($material['material_id']) ? $this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material['material_id'])) : $material['static_unit'];
                $m_code_row = '';
                $m_row = '';
                $b_row = '';
                $unit_row = '';
                if (is_numeric($material['material_id'])) {
                    $m_code_row .= '<td style="display:none;">
					<input type="hidden" value="' . $i . '" name="row_number" class="row_number">
					<input type="hidden" value="' . $material["id"] . '" name="material[detail_id][]">
					</td>';

                    $m_row .= '<select class="select2 material_id rate_material_id" style="width:180px;" name="material[material_id][]" id="material_id_' . $i . '" data-id=' . $i . '>
						<option value="">Select Material</Option>';
                    foreach ($material_list as $retrive_data) {
                        $selected = ($retrive_data['material_id'] == $material['material_id']) ? "selected" : "";
                        $m_row .= '<option value="' . $retrive_data['material_id'] . '"' . $selected . '>' .
                            $retrive_data['material_title'] . '</option>';
                    }
                    $m_row .= '</select>';

                    $b_row .= '<select class="select2 brand_id"  required="true"   name="material[brand_id][]" style="width:130px;" id="brand_id_' . $i . '" data-id=' . $i . '>';
                    $brands = $this->ERPfunction->get_brands_by_material_id($material["material_id"]);
                    if ($brands != "") {
                        foreach ($brands as $brand) {
                            $b_row .= '<option value="' . $brand['brand_id'] . '"' . $this->ERPfunction->selected($brand['brand_id'], $material['brand_id']) . '>' . $brand['brand_name'] . '</option>';
                        }
                    }

                    $b_row .= '</select>';

                    $unit_row .= '<td><span id="unit_name_' . $i . '">' . $unit . '</span>
					<input type="hidden" value="" name="material[static_unit][]" id="static_unit_' . $i . '" class="form-control" style="width:80px;">
					</td>';
                } else {
                    $m_code_row .= '<td style="display:none;">
					<input type="hidden" value="1" name="material[is_custom][]">
					<input type="hidden" value="' . $i . '" name="row_number" class="row_number">
					<input type="hidden" value="' . $material["id"] . '" name="material[detail_id][]">
					</td>';

                    $m_row .= '<input type="text" name="material[material_id][]" value="' . htmlspecialchars($material["material_id"]) . '" id="material_id_' . $i . '" data-id="' . $i . '" class="form-control material_id" style="width:180px;"/>';
                    $b_row .= '<input type="text" name="material[brand_id][]" value="' . htmlspecialchars($material["brand_id"]) . '" id="brand_id_' . $i . '" class="form-control" style="width:120px;"/>';
                    $unit_row .= '<td><input type="text" value="' . htmlspecialchars($unit) . '" name="material[static_unit][]" id="static_unit_' . $i . '" class="form-control" class="form-control" style="width:120px;"></td>';
                }

                $row .= '<tr class="cpy_row" id="row_id_' . $i . '">
							' . $m_code_row . '
							<td>' . $m_row . '
							</td>
							<td>
							<input type="text" value="' . htmlspecialchars($material['hsn_code']) . '" name="material[hsn_code][]" id="hsn_code_' . $i . '" class="hsn_code" style="width:120px;">
							</td>';
                // <td>'.$this->ERPfunction->get_materialitem_desc($material['material_id']).'</td>
                $row .= '<td>'
                    . $b_row . '</td>
							<td><input type="text" name="material[quantity][]" data-id="' . $i . '" class="quantity" value="' . $material["quantity"] . '" id="quantity_' . $i . '" style="width:60px"/></td>
							' . $unit_row . '
							<td><input type="text" name="material[unit_rate][]" class="unit_rate" value="' . $material["unit_price"] . '" data-id="' . $i . '" id="unit_rate_' . $i . '" style="width:80px"/>
							</td>
							<td><input type="text" name="material[discount][]" value="' . $material["discount"] . '" class="tx_count" id="dc_' . $i . '" data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[transportation][]" value="' . $material["transportation"] . '" class="tx_count" id="tr_' . $i . '" data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[exice][]" value="' . $material["exice"] . '" class="tx_count" id="ex_' . $i . '"  data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[other_tax][]" value="' . $material["other_tax"] . '" class="tx_count" id="other_tax_' . $i . '"  data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[amount][]" class="amount" value="' . $material["amount"] . '" id="amount_' . $i . '" style="width:90px" /></td>
							<td><input type="text" name="material[single_amount][]" value="' . $material["single_amount"] . '" id="single_amount_' . $i . '" style="width:90px"/></td>

							<input type="hidden" name="po_mid[]" value="' . $material["id"] . '">
							<td><a href="#" class="btn btn-danger del_parent" data-id="' . $material["id"] . '">Delete</a></td>
						</tr>';

                $i++;
            }
        }
        //debug($row);
        $this->set("row", $row);

        if ($this->request->is('post')) {
            $this->request->data['last_edit'] = date('Y-m-d H:i:s');
            $this->request->data['last_edit_by'] = $this->request->session()->read('user_id');
            $this->request->data['po_date'] = date('Y-m-d', strtotime($this->request->data['po_date']));
            $this->request->data['delivery_date'] = date('Y-m-d', strtotime($this->request->data['delivery_date']));
            if (!isset($this->request->data['taxes_duties'])) {
                $this->request->data['taxes_duties'] = 0;
            }
            if (!isset($this->request->data['loading_transport'])) {
                $this->request->data['loading_transport'] = 0;
            }
            if (!isset($this->request->data['unloading'])) {
                $this->request->data['unloading'] = 0;
            }

            $entity_data = $erp_manual_po->get($po_id);
            $post_data = $erp_manual_po->patchEntity($entity_data, $this->request->data);
            if ($erp_manual_po->save($post_data)) {

                $this->Flash->success(__('Record Update Successfully', null),
                    'default',
                    array('class' => 'success'));

                $this->ERPfunction->edit_manual_po_detail($this->request->data['material'], $po_id);
            }
            //$this->redirect(array("controller" => "Inventory","action" => "approvepo"));
            echo "<script>window.close();</script>";
        }
    }

    public function manualapprovepo()
    {
        $erp_manual_po = TableRegistry::get('erp_manual_po');
        $role = $this->role;
        $selected_project = isset($_REQUEST['selected_project']) ? $_REQUEST['selected_project'] : '';
        $show_data = isset($_REQUEST['selected_project']) ? 1 : 0;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        $this->set('projects', $projects);
        $this->set('selected_project', $selected_project);
        $this->set('role', $this->role);
        $this->set('show_data', $show_data);
        if ($this->request->is('post')) {
            $request_data = $this->request->data;
            $this->set('request_data', $request_data);
            $this->set('selected_project', $request_data['project_id']);
            $this->set('show_data', 1);
        }
    }

    public function joinvendor() {
		$this->autoRender = false;
		$data = $this->request->data;
		$erpVendor = TableRegistry::get("erp_vendor");
		$join_hstr = TableRegistry::get("erp_join_vendor_history");
		$masterVendorUserId = $data['parent_vendor_id'];
		$baseVendorUserId = $data['base_vendor'];
		// Fetching master vendor id for set Party ID
		$vendorRecords = $erpVendor->get($masterVendorUserId);
		$masterVendorId = $vendorRecords['vendor_id'];

		$masterVendorData = $erpVendor->get($masterVendorUserId);
		$baseVendorData = $erpVendor->get($baseVendorUserId);

		$joinVendor = $baseVendorData->toArray();
		$row = $join_hstr->newEntity($joinVendor);
		$row['join_with_vendor'] = $masterVendorUserId;
		$row['join_by'] = $this->request->session()->read('user_id');
		$row['join_date'] = date("Y-m-d");
		$save_history = $join_hstr->save($row);

		if($save_history) {
			######### Start code for update master vendor id on base vendor id ###########

			//Update Vendor id in erp_planning_work_order
			$woTable = TableRegistry::get('erp_planning_work_order');
			$woDetails = $woTable->query();
			$woDetails->update()->set(["party_userid"=>$masterVendorUserId,"party_id" => $masterVendorId])->where(['party_userid'=>$baseVendorUserId])->execute();

			//Update Vendor id in erp_sub_contract
			$erpSubContract = TableRegistry::get('erp_sub_contract');
			$erpSubContractDetails = $erpSubContract -> query();
			$erpSubContractDetails->update()->set(["party_id"=>$masterVendorUserId,"party_identy" => $masterVendorId])->where(['party_id'=>$baseVendorUserId])->execute();

			//Update Vendor id in erp_letter_content
			$erpLetterContent = TableRegistry::get('erp_letter_content');
			$erpLetterContentDetails = $erpLetterContent -> query();
			$erpLetterContentDetails->update()->set(["vendor_userid"=>$masterVendorUserId,"vendor_id" => $masterVendorId])->where(['vendor_userid'=>$baseVendorUserId])->execute();

			// Update Vendor ID in erp_inventory_po
			$erpInventoryPo = TableRegistry::get('erp_inventory_po');
			$erpInventoryPoDetails = $erpInventoryPo -> query();
			$erpInventoryPoDetails->update()->set(["vendor_userid"=>$masterVendorUserId,"vendor_id" => $masterVendorId])->where(['vendor_userid'=>$baseVendorUserId])->execute();

			//Update Vendor ID in erp_asset_po
			$erpAssetPo = TableRegistry::get('erp_asset_po');
			$erpAssetPoDetails = $erpAssetPo -> query();
			$erpAssetPoDetails->update()->set(['vendor_userid'=>$masterVendorUserId,"vendor_id"=>$masterVendorId])->where(['vendor_userid'=>$baseVendorUserId])->execute();

			//Update Vendor ID in erp_work_order
			$erpWorkOrder = TableRegistry::get('erp_work_order');
			$erpWorkOrderDetails = $erpWorkOrder ->query();
			$erpWorkOrderDetails->update()->set(["party_userid"=>$masterVendorUserId,"party_id" => $masterVendorId])->where(['party_userid'=>$baseVendorUserId])->execute();

			//Update Vendor ID in erp_inward_bill
			$erpInwardBill = TableRegistry::get('erp_inward_bill');
			$erpInwardBillDetails = $erpInwardBill ->query();
			$erpInwardBillDetails->update()->set(["party_name"=>$masterVendorUserId,"party_id" => $masterVendorId])->where(['party_name'=>$baseVendorUserId])->execute();

			//Update Vendor ID in erp_debit_note
			$erpDebitNote = TableRegistry::get("erp_debit_note");
			$erpDebitNoteDetails = $erpDebitNote ->query();
			$erpDebitNoteDetails->update()->set(["debit_to" => $masterVendorUserId])->where(['debit_to'=>$baseVendorUserId])->execute();

			//Update Vendor ID in erp_advance_request_detail
			$erpAdvanceRequest = TableRegistry::get("erp_advance_request_detail");
			$erpAdvanceRequestDetails = $erpAdvanceRequest->query();
			$erpAdvanceRequestDetails->update()->set(["agency_id" => $masterVendorUserId])->where(['agency_id'=>$baseVendorUserId])->execute();

			//Update Vendor ID in erp_assets
			$erpAssetTable = TableRegistry::get('erp_assets'); 
			$erpAssetTableDetails = $erpAssetTable->query();
			$erpAssetTableDetails->update()->set(["vendor_name" => $masterVendorUserId,"vendor_id" => $masterVendorId])->where(['vendor_name'=>$baseVendorUserId])->execute();

			//Update Vendor ID in erp_inventory_grn
			$erpInventoryGrnTable = TableRegistry::get('erp_inventory_grn'); 
			$erpInventoryGrnTableDetails = $erpInventoryGrnTable->query();
			$erpInventoryGrnTableDetails->update()->set(["vendor_userid" => $masterVendorUserId,"vendor_id" => $masterVendorId])->where(['vendor_userid'=>$baseVendorUserId])->execute();

			// Update Vendor ID in erp_inventory_is
			$erpInventoryIs = TableRegistry::get('erp_inventory_is');
			$erpInventoryIsDetails = $erpInventoryIs->query();
			$erpInventoryIsDetails->update()->set(["agency_name" => $masterVendorUserId])->where(['agency_name'=>$baseVendorUserId])->execute();

			// Update Vendor ID in erp_inventory_rbn
			$erpInventoryRbn = TableRegistry::get('erp_inventory_rbn');
			$erpInventoryRbnDetails = $erpInventoryRbn->query();
			$erpInventoryRbnDetails->update()->set(["agency_name" => $masterVendorUserId])->where(['agency_name'=>$baseVendorUserId])->execute();

			// Update Vendor ID in erp_inventory_debit_note
			$erpInventoryDebitNote = TableRegistry::get('erp_inventory_debit_note');
			$erpInventoryDebitNoteDetails = $erpInventoryDebitNote->query();
			$erpInventoryDebitNoteDetails->update()->set(["debit_to" => $masterVendorUserId])->where(['debit_to'=>$baseVendorUserId])->execute();

			// Update Vendor ID in erp_inventory_rmc
			$erpInventoryRmc = TableRegistry::get('erp_inventory_rmc'); 
			$erpInventoryRmcDetails = $erpInventoryRmc->query();
			$erpInventoryRmcDetails->update()->set(["agency_id" => $masterVendorUserId])->where(['agency_id'=>$baseVendorUserId])->execute();

			// Update Vendor Id in erp_inventory_mrn
			$erpInventoryMrnTable = TableRegistry::get('erp_inventory_mrn'); 
			$erpInventoryMrnTableDetails = $erpInventoryMrnTable->query();
			$erpInventoryMrnTableDetails->update()->set(["vendor_user" => $masterVendorUserId,"vendor_id" => $masterVendorId])->where(['vendor_user'=>$baseVendorUserId])->execute();

			// Base Vendor Delete
			$erpVendor->delete($baseVendorData);
			$this->Flash->success(__('Vendor Join Successfully.', null), 
							 'default', 
							 array('class' => 'success'));
		}
		$this->redirect(["action"=>"viewvendor"]);
	}

    public function manualapprovepolocal()
    {
        $erp_manual_po = TableRegistry::get('erp_manual_po');
        $role = $this->role;
        $selected_project = isset($_REQUEST['selected_project']) ? $_REQUEST['selected_project'] : '';
        $show_data = isset($_REQUEST['selected_project']) ? 1 : 0;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        $this->set('projects', $projects);
        $this->set('selected_project', $selected_project);
        $this->set('role', $this->role);
        $this->set('show_data', $show_data);
        if ($this->request->is('post')) {
            $request_data = $this->request->data;
            $this->set('request_data', $request_data);
            $this->set('selected_project', $request_data['project_id']);
            $this->set('show_data', 1);
        }
    }

    public function printmanualporecord($eid, $mail = null)
    {
        require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';

        $erp_manual_po = TableRegistry::get("erp_manual_po");
        $erp_manual_po_detail = TableRegistry::get('erp_manual_po_detail');

        if ($mail == "mail") {
            $previw_list = $erp_manual_po_detail->find()->where(array('po_id' => $eid, 'currently_approved' => 1));
        } else {
            $previw_list = $erp_manual_po_detail->find()->where(array('po_id' => $eid));
        }

        $this->set('previw_list', $previw_list);
        $data = $erp_manual_po->get($eid);
        $this->set("data", $data->toArray());
    }

    public function printmanualporecordnorate($eid)
    {
        require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';

        $erp_manual_po = TableRegistry::get("erp_manual_po");
        $erp_manual_po_detail = TableRegistry::get('erp_manual_po_detail');

        $previw_list = $erp_manual_po_detail->find()->where(array('po_id' => $eid, 'currently_approved' => 1));
        $this->set('previw_list', $previw_list);
        $data = $erp_manual_po->get($eid);
        $this->set("data", $data->toArray());
    }

    public function cancelpomanual($po_id = null)
    {

        $pom_tbl = TableRegistry::get("erp_manual_po_detail");
        $po_tbl = TableRegistry::get("erp_manual_po");

        if ($po_id != null) {
            $get_deleted_po = $po_tbl->get($po_id);
            $deleted_po = $get_deleted_po->toArray();
            $mail_check = $deleted_po["mail_check"];
            $del_po_project_id = $deleted_po["project_id"];
            $del_po_no = $deleted_po["po_no"];
            $del_po_project_name = $this->ERPfunction->get_projectname($deleted_po["project_id"]);
            $del_po_party_name = $this->ERPfunction->get_vendor_name($deleted_po["vendor_userid"]);

            $pdpmcm_email = $this->ERPfunction->get_email_of_pd_pm_cm_by_project_of_manualpo($del_po_project_id, $po_id);
            $mm_email = $this->ERPfunction->get_email_of_mm_by_project($del_po_project_id);

            $po_detail = $pom_tbl->find("all")->where(["po_id" => $po_id, "approved" => 1]);
            if (!empty($po_detail)) {

                //$pom_tbl->deleteAll(["po_id"=>$po_id,"approved"=>1]);

                $query = $pom_tbl->query();
                $query->update()
                    ->set(['approved' => 0,
                        "approved_by" => "",
                        "approved_date" => ""])
                    ->where(['po_id' => $po_id])
                    ->execute();

                // $count = $pom_tbl->find("all")->where(["po_id"=>$po_id])->count();
                // if($count == 0) /* Make Sure ALL PO Materials are deleted before deleting po */
                // {
                // $ok = $po_tbl->delete($get_deleted_po);
                // }

                if ($query) {
                    $projectdetail = TableRegistry::get('erp_projects');
                    $project_data = $projectdetail->get($del_po_project_id);
                    $code = $project_data->project_code;

                    $new_pono = $this->ERPfunction->generate_auto_id($del_po_project_id, "erp_manual_po", "po_id", "po_no");
                    $new_pono = sprintf("%09d", $new_pono);
                    $new_pono = $code . '/MANPO/' . $new_pono;

                    $update_data['po_no'] = $new_pono;
                    $data = $po_tbl->patchEntity($get_deleted_po, $update_data);
                    $po_tbl->save($data);

                    $emails1 = array();
                    $emails2 = array();
                    // $pdpm_email = $this->ERPfunction->get_email_of_pd_pm_by_project($del_po_project_id,$po_id);
                    // $cmmm_email = $this->ERPfunction->get_email_of_cm_mm_by_project($del_po_project_id);
                    if ($mail_check == 2) {
                        $project_wise_role = ['deputymanagerelectric'];
                        $project_email = $this->ERPfunction->get_email_id_by_project_from_user($del_po_project_id, $project_wise_role);
                        $emails1 = array_merge($emails1, $project_email);
                    }
                    $purchase_heademail = $this->ERPfunction->get_purchase_head_email_from_user();

                    $emails1 = array_merge($pdpmcm_email, $emails1);
                    $emails2 = array_merge($mm_email, $emails2);
                    $role = ['erphead', 'erpmanager', 'purchasehead', 'erpoperator', 'ceo'];
                    $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                    $emails2 = array_merge($erp_email, $emails2);
                    $pdpmcm_email = array_unique($emails1); /*remove duplicate email ids */
                    $mm_email = array_unique($emails2); /*remove duplicate email ids */
                    $pdpmcm_email = array_filter($pdpmcm_email, function ($value) {return $value !== '';});
                    $mm_email = array_filter($mm_email, function ($value) {return $value !== '';});

                    $all_users = array_unique(array_merge($pdpmcm_email, $mm_email));
                    // debug($all_users);die;
                    if (!empty($all_users)) {
                        $all_users = implode(",", $all_users);
                        $this->ERPfunction->cancel_manualpo_mail($all_users, $del_po_no, $del_po_project_name, $del_po_party_name);
                    }

                    // if(!empty($pdpmcm_email))
                    // {
                    // $pdpmcm_email = implode(",",$pdpmcm_email);
                    // $this->ERPfunction->cancel_manualpo_mail($pdpmcm_email,$del_po_no,$del_po_project_name,$del_po_party_name);
                    // }

                    // if(!empty($mm_email))
                    // {
                    // $mm_email = implode(",",$mm_email);
                    // $this->ERPfunction->cancel_manualpo_mail($mm_email,$del_po_no,$del_po_project_name,$del_po_party_name);
                    // }
                }
            }

            $this->Flash->success(__('Manual P.O. Cancelled Successfully.', null),
                'default',
                array('class' => 'success'));
            $this->redirect(["action" => "viewporecords"]);
        }
    }

    public function joinmaterial()
    {
        $this->autoRender = false;
        $data = $this->request->data;
        $erp_material = TableRegistry::get("erp_material");
        $join_hstr = TableRegistry::get("erp_join_material_history");
        $master_material = $data['material_id'];
        $base_material = $data['base_material'];

        $master_material_data = $erp_material->get($master_material);
        $base_material_data = $erp_material->get($base_material);

        $join_material = $base_material_data->toArray();
        $row = $join_hstr->newEntity($join_material);
        $row['join_with_material'] = $master_material;
        $row['join_by'] = $this->request->session()->read('user_id');
        $row['join_date'] = date("Y-m-d");
        $save_history = $join_hstr->save($row);

        if ($save_history) {
            ######### Start code for update master material id on base material id ###########

            // Change material opening stock
            $erp_stock_history = TableRegistry::get('erp_stock_history');
            $base_material_opening_stock = $erp_stock_history->find()->where(['material_id' => $base_material, 'type' => 'os'])->hydrate(false)->toArray();

            if (!empty($base_material_opening_stock)) {
                foreach ($base_material_opening_stock as $base_stock) {
                    //Check master material stock history records with base material project
                    $master_project_stock = $erp_stock_history->find()->where(['material_id' => $master_material, 'project_id' => $base_stock['project_id'], 'type' => 'os'])->first();

                    //If !empty then remove base material record and update quentity in master
                    if (!empty($master_project_stock)) {
                        $master_stock_update = $erp_stock_history->get($master_project_stock->stock_id);
                        $total_stock = $master_stock_update->quantity + $base_stock['quantity'];
                        $master_stock_update->quantity = $total_stock;
                        $updated = $erp_stock_history->save($master_stock_update);
                        if ($updated) {
                            $stock_tbl_deleted = TableRegistry::get("erp_stock_history_deleted");
                            $del_row = $stock_tbl_deleted->newEntity();
                            $del_row = $stock_tbl_deleted->patchEntity($del_row, $base_stock);
                            if ($stock_tbl_deleted->save($del_row)) {
                                $delete_stock = $erp_stock_history->get($base_stock['stock_id']);
                                $deleted = $erp_stock_history->delete($delete_stock);
                            }

                        }
                    } else {
                        //if master material have not record with particular project then update material id with master
                        $query12 = $erp_stock_history->query();
                        $query12->update()
                            ->set(['material_id' => $master_material])
                            ->where(['stock_id' => $base_stock['stock_id']])
                            ->execute();
                    }
                }
            }

            //Update material id in erp_stock_history
            $erp_stock_history = TableRegistry::get('erp_stock_history');
            $query = $erp_stock_history->query();
            $query->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_stock_history_deleted
            $erp_stock_history_deleted = TableRegistry::get('erp_stock_history_deleted');
            $query1 = $erp_stock_history_deleted->query();
            $query1->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_finalized_rate_detail
            $erp_finalized_rate_detail = TableRegistry::get('erp_finalized_rate_detail');
            $query2 = $erp_finalized_rate_detail->query();
            $query2->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_inventory_po_detail
            $erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail');
            $query3 = $erp_inventory_po_detail->query();
            $query3->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_manual_po_detail
            $erp_manual_po_detail = TableRegistry::get('erp_manual_po_detail');
            $query4 = $erp_manual_po_detail->query();
            $query4->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_inventory_pr_material
            $erp_inventory_pr_material = TableRegistry::get('erp_inventory_pr_material');
            $query5 = $erp_inventory_pr_material->query();
            $query5->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_inventory_grn_detail
            $erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail');
            $query6 = $erp_inventory_grn_detail->query();
            $query6->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_inventory_is_detail
            $erp_inventory_is_detail = TableRegistry::get('erp_inventory_is_detail');
            $query7 = $erp_inventory_is_detail->query();
            $query7->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_inventory_rbn_detail
            $erp_inventory_rbn_detail = TableRegistry::get('erp_inventory_rbn_detail');
            $query8 = $erp_inventory_rbn_detail->query();
            $query8->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_inventory_mrn_detail
            $erp_inventory_mrn_detail = TableRegistry::get('erp_inventory_mrn_detail');
            $query9 = $erp_inventory_mrn_detail->query();
            $query9->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_inventory_sst_detail
            $erp_inventory_sst_detail = TableRegistry::get('erp_inventory_sst_detail');
            $query10 = $erp_inventory_sst_detail->query();
            $query10->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_audit_grn_detail
            $erp_audit_grn_detail = TableRegistry::get('erp_audit_grn_detail');
            $query10 = $erp_audit_grn_detail->query();
            $query10->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_audit_is_detail
            $erp_audit_is_detail = TableRegistry::get('erp_audit_is_detail');
            $query10 = $erp_audit_is_detail->query();
            $query10->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            //Update material id in erp_audit_rbn_detail
            $erp_audit_rbn_detail = TableRegistry::get('erp_audit_rbn_detail');
            $query10 = $erp_audit_rbn_detail->query();
            $query10->update()
                ->set(['material_id' => $master_material])
                ->where(['material_id' => $base_material])
                ->execute();

            ######### End code for update master material id on base material id ###########

            // $base_material_data['material_code'] = $master_material_data->material_code;
            // $base_material_data['material_item_code'] = $master_material_data->material_item_code;
            // $base_material_data['material_title'] = $master_material_data->material_title;
            // $base_material_data['brand_id'] = $master_material_data->brand_id;
            // $base_material_data['project_id'] = $master_material_data->project_id;
            // $base_material_data['consume'] = $master_material_data->consume;
            // $base_material_data['unit_id'] = $master_material_data->unit_id;
            // $base_material_data['desciption'] = $master_material_data->desciption;
            // $base_material_data['status'] = $master_material_data->status;
            // $join = $erp_material->save($base_material_data);

            // Base material delete
            $erp_material->delete($base_material_data);

            $this->Flash->success(__('Material Join Successfully.', null),
                'default',
                array('class' => 'success'));
        }
        $this->redirect(["action" => "viewmaterial"]);
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
        $number = (int)$result[0]['max'];
        $new_number = str_pad(++$number, 9, '0', STR_PAD_LEFT);
        return $new_number;
        // debug($new_number);
        // debug($result);
        // die;
    }

    public function updateremarks()
    {
        $this->autoRender = false;
        if($this->request->is('post'))
        {
            $post = $this->request->data;
            // debug($this->request->data);die;
            $project_id = $this->request->data["project_id"];
            $row_id = $this->request->data["pr_detail_row_id"];
            $remark = $this->request->data["remark"];

            $erp_inventory_pr_material = TableRegistry::get("erp_inventory_pr_material");
            $update = $erp_inventory_pr_material->get($row_id);
            $update->purchase_remarks = $remark;
            $erp_inventory_pr_material->save($update);
            $this->Flash->success(__('Remarks Added Successfully', null),
                'default',
                array('class' => 'success'));

            $this->redirect(["controller" => "Purchase", "action" => "approvedpr", $project_id]);
        }
    }

    public function postatus()
    {
        ini_set('memory_limit', '250M');
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'inventory') !== false) {
            $back_url = 'inventory';
            $back_page = 'index';
        } else {
            $back_url = 'purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        $this->set("user_role", $this->role);
        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        //$projects = $this->ERPfunction->get_projects();
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        if ($role == "deputymanagerelectric") {
            $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
            $material_ids = json_decode($material_ids);
            $material_list = $erp_material->find()->where(["material_id IN" => $material_ids]);
        } else {
            $material_list = $erp_material->find();
        }
        $this->set('material_list', $material_list);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        $erp_material_brand = TableRegistry::get('erp_material_brand');
        $brand_list = $erp_material_brand->find();
        $this->set('brand_list', $brand_list);

        $user = $this->request->session()->read('user_id');
        //var_dump($user);die;
        $role = $this->Usermanage->get_user_role($user);
        $projects_ids = $this->Usermanage->users_project($user);

        $this->set("projects_id", '');
        $this->set("from", '');
        $this->set("to", '');

        if ($this->request->is('post')) {
            if (isset($this->request->data["go1"])) {
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                $post = $this->request->data;
                $or = array();

                if ($post['po_type'] == "po") {
                    $or["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;

                    $keys = array_keys($or, "");
                    foreach ($keys as $k) {unset($or[$k]);}
                    //debug($post);
                    //debug($or);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $result = $erp_inventory_po->find()->select($erp_inventory_po)->where(["project_id IN" => $projects_ids]);
                            $result = $result->innerjoin(
                                ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                                ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                                ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $result = array();
                        }
                    } else {
                        $result = $erp_inventory_po->find()->select($erp_inventory_po);
                        $result = $result->innerjoin(
                            ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                            ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                            ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }
                    $this->set('po_list', $result);
                    $this->set('manual_po', array());
                } else {
                    // For manual po search

                    $erp_manual_po = TableRegistry::get("erp_manual_po");
                    $erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
                    $or1 = array();

                    $or1["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or1["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or1["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or1["erp_manual_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or1["erp_manual_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or1["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or1["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;
                    if ($post['po_type'] == "manualpolocal") {
                        //for manual po on base of grn search
                        $or1["is_grn_base"] = 1;
                    } else {
                        $or1["is_grn_base !="] = 1;
                    }
                    $keys = array_keys($or1, "");
                    foreach ($keys as $k) {unset($or1[$k]);}
                    // debug($post);
                    // debug($or1);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $manual_po_list = $erp_manual_po->find()->select($erp_manual_po)->where(["project_id IN" => $projects_ids]);
                            $manual_po_list = $manual_po_list->innerjoin(
                                ["erp_manual_po_detail" => "erp_manual_po_detail"],
                                ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved !=" => 0])
                                ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $manual_po_list = array();
                        }
                    } else {
                        $manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
                        $manual_po_list = $manual_po_list->innerjoin(
                            ["erp_manual_po_detail" => "erp_manual_po_detail"],
                            ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved !=" => 0])
                            ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }

                    $this->set('po_list', array());
                    $this->set('manual_po', $manual_po_list);
                }
            }
            if (isset($this->request->data["export_csv"])) {
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"])) ? $post["e_po_no"] : null;
                $or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;
                $or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;

                if ($or["erp_inventory_po.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_inventory_po.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_inventory_po_detail.approved ="] = 1;
                $or["erp_inventory_po.po_purchase_type ="] = "po";
                //debug($post);
                // debug($or);die;
                $result = $erp_inventory_po->find()->select($erp_inventory_po);
                $result = $result->innerjoin(
                    ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                    ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
                    ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("P.O. No", "P.O.Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "PO Quantity", "Received Quantity", "PO's Remaining Quantity", "Unit", "Remarks");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_inventory_po_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_inventory_po_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }
                    $po_type = $retrive_data['po_type'];
                    if ($po_type == "po") {
                        $type_name = "PO";
                    } elseif ($po_type == "manual_po") {
                        $type_name = "Manual PO";
                    } elseif ($po_type == "local_po") {
                        $type_name = "Local PO";
                    }

                    $csv = array();
                    $csv[] = $retrive_data['po_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $retrive_data['quantity'] - $retrive_data['grn_remain_qty'];
                    $csv[] = $retrive_data['grn_remain_qty'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['remarks'];
                    $rows[] = $csv;
                }

                $filename = "po_records.csv";
                $this->ERPfunction->export_to_csv($filename, $rows);
            }

            if (isset($this->request->data["export_pdf"])) {
                require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"])) ? $post["e_po_no"] : null;
                $or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;
                $or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;

                if ($or["erp_inventory_po.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_inventory_po.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_inventory_po_detail.approved ="] = 1;
                $or["erp_inventory_po.po_purchase_type ="] = "po";
                //debug($post);
                // debug($or);die;
                $result = $erp_inventory_po->find()->select($erp_inventory_po);
                $result = $result->innerjoin(
                    ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                    ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
                    ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("P.O. No", "P.O.Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "PO Quantity", "Received Quantity", "PO's Remaining Quantity", "Unit", "Remarks");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_inventory_po_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_inventory_po_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }
                    $po_type = $retrive_data['po_type'];
                    if ($po_type == "po") {
                        $type_name = "PO";
                    } elseif ($po_type == "manual_po") {
                        $type_name = "Manual PO";
                    } elseif ($po_type == "local_po") {
                        $type_name = "Local PO";
                    }

                    $csv = array();
                    $csv[] = $retrive_data['po_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $retrive_data['quantity'] - $retrive_data['grn_remain_qty'];
                    $csv[] = $retrive_data['grn_remain_qty'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['remarks'];
                    $rows[] = $csv;
                }
                $this->set("rows", $rows);
                $this->render("postatuspdf");
            }
        }

        //// debug($result);
        //// debug($manual_po_list);die;
        //$this->set('po_list',$result);
        // $this->set('manual_po',$manual_po_list);
        //$this->set('manual_po',array());
    }

    public function recivedpoquantitymanually()
    {
        $this->autoRender = false;
        $post = $this->request->data;

        $manually_received_po = TableRegistry::get("manually_received_po");
        $row = $manually_received_po->newEntity();
        $row->po_detail_id = $post["po_detail_id"];
        $row->po_id = $post["po_id"];
        $row->material_id = $post["material_id"];
        $row->received_qty = $post["received_qty"];
        $row->received_date = date("Y-m-d", strtotime($post["received_date"]));
        $row->remarks = $post["remarks"];
        $row->created_by = $this->request->session()->read('user_id');
        $row->created_date = date("Y-m-d");
        if ($manually_received_po->save($row)) {
            /* Update PO Material Details GRN remain Quantity to affect on next grn with this po*/
            $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
            $record = $erp_inventory_po_detail->get($post["po_detail_id"]);
            if ($post["received_qty"] >= $record->grn_remain_qty) {
                $record->grn_remain_qty = 0;
                $record->approved = 2;
            } else {
                $record->grn_remain_qty = $record->grn_remain_qty - $post["received_qty"];
            }
            $erp_inventory_po_detail->save($record);
            /* Update PO Material Details GRN remain Quantity to affect on next grn with this po*/

            $this->Flash->success(__('PO Quantity received Successfully', null),
                'default',
                array('class' => 'success'));

            $this->redirect(["controller" => "Purchase", "action" => "postatus"]);
        }

    }

    public function removepofromgrn($po_detail_id)
    {
        $po_tbl = TableRegistry::get("erp_inventory_po_detail");
        $user_id = $this->request->session()->read('user_id');

        $query = $po_tbl->query();
        $query = $query->update()->set(["approved" => 2, "approved_by" => $user_id, "approved_date" => date("Y-m-d")])->where(["id" => $po_detail_id])->execute();
        if ($query) {
            $this->Flash->success(__('PO Removed from list Successfully', null),
                'default',
                array('class' => 'success'));

            $this->redirect(["controller" => "Purchase", "action" => "postatus"]);
        }
    }

    public function podeliveryrecords()
    {
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'inventory') !== false) {
            $back_url = 'inventory';
            $back_page = 'index';
        } else {
            $back_url = 'purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        $this->set("user_role", $this->role);
        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        //$projects = $this->ERPfunction->get_projects();
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        if ($role == "deputymanagerelectric") {
            $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
            $material_ids = json_decode($material_ids);
            $material_list = $erp_material->find()->where(["material_id IN" => $material_ids]);
        } else {
            $material_list = $erp_material->find();
        }
        $this->set('material_list', $material_list);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        $erp_material_brand = TableRegistry::get('erp_material_brand');
        $brand_list = $erp_material_brand->find();
        $this->set('brand_list', $brand_list);

        $user = $this->request->session()->read('user_id');
        //var_dump($user);die;
        $role = $this->Usermanage->get_user_role($user);
        $projects_ids = $this->Usermanage->users_project($user);

        $this->set("back", "index");
        $this->set("projects_id", '');
        $this->set("from", '');
        $this->set("to", '');

        if ($this->request->is('post')) {
            if (isset($this->request->data["go1"])) {
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                $post = $this->request->data;
                $or = array();

                if ($post['po_type'] == "po") {
                    $or["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;

                    $keys = array_keys($or, "");
                    foreach ($keys as $k) {unset($or[$k]);}
                    //debug($post);
                    //debug($or);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $result = $erp_inventory_po->find()->select($erp_inventory_po)->where(["project_id IN" => $projects_ids]);
                            $result = $result->innerjoin(
                                ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                                ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                                ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $result = array();
                        }
                    } else {
                        $result = $erp_inventory_po->find()->select($erp_inventory_po);
                        $result = $result->innerjoin(
                            ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                            ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                            ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }
                    $this->set('po_list', $result);
                    $this->set('manual_po', array());
                } else {
                    // For manual po search

                    $erp_manual_po = TableRegistry::get("erp_manual_po");
                    $erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
                    $or1 = array();

                    $or1["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or1["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or1["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or1["erp_manual_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or1["erp_manual_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or1["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or1["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;
                    if ($post['po_type'] == "manualpolocal") {
                        //for manual po on base of grn search
                        $or1["is_grn_base"] = 1;
                    } else {
                        $or1["is_grn_base !="] = 1;
                    }
                    $keys = array_keys($or1, "");
                    foreach ($keys as $k) {unset($or1[$k]);}
                    // debug($post);
                    // debug($or1);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $manual_po_list = $erp_manual_po->find()->select($erp_manual_po)->where(["project_id IN" => $projects_ids]);
                            $manual_po_list = $manual_po_list->innerjoin(
                                ["erp_manual_po_detail" => "erp_manual_po_detail"],
                                ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved =" => 2])
                                ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $manual_po_list = array();
                        }
                    } else {
                        $manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
                        $manual_po_list = $manual_po_list->innerjoin(
                            ["erp_manual_po_detail" => "erp_manual_po_detail"],
                            ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved =" => 2])
                            ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }

                    $this->set('po_list', array());
                    $this->set('manual_po', $manual_po_list);
                }
            }
            if (isset($this->request->data["export_csv"])) {
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"])) ? $post["e_po_no"] : null;
                $or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;
                $or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;

                if ($or["erp_inventory_po.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_inventory_po.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_inventory_po_detail.approved ="] = 2;
                $or["erp_inventory_po.po_purchase_type ="] = "po";

                $result = $erp_inventory_po->find()->select($erp_inventory_po);
                $result = $result->innerjoin(
                    ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                    ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
                    ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("P.O. No", "P.O.Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "PO Quantity", "Received Quantity", "PO's Remaining Quantity", "Unit", "Remarks");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_inventory_po_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_inventory_po_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }

                    $csv = array();
                    $csv[] = $retrive_data['po_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $retrive_data['quantity'] - $retrive_data['grn_remain_qty'];
                    $csv[] = $retrive_data['grn_remain_qty'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['remarks'];
                    $rows[] = $csv;
                }

                $filename = "po_delivery_records.csv";
                $this->ERPfunction->export_to_csv($filename, $rows);
            }

            if (isset($this->request->data["export_pdf"])) {
                require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_inventory_po.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_inventory_po.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_inventory_po.po_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_inventory_po.po_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_inventory_po.po_no ="] = (!empty($post["e_po_no"])) ? $post["e_po_no"] : null;
                $or["erp_inventory_po.po_purchase_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;
                $or["erp_inventory_po_detail.po_type ="] = (!empty($post["e_po_type"]) && $post["e_po_type"] != "All") ? $post["e_po_type"] : null;

                if ($or["erp_inventory_po.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_inventory_po.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_inventory_po_detail.approved !="] = 0;

                $result = $erp_inventory_po->find()->select($erp_inventory_po);
                $result = $result->innerjoin(
                    ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                    ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id"])
                    ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("P.O. No", "P.O.Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "PO Quantity", "Received Quantity", "PO's Remaining Quantity", "Unit", "Remarks");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_inventory_po_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_inventory_po_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }

                    $csv = array();
                    $csv[] = $retrive_data['po_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['po_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $retrive_data['quantity'] - $retrive_data['grn_remain_qty'];
                    $csv[] = $retrive_data['grn_remain_qty'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['remarks'];
                    $rows[] = $csv;
                }
                $this->set("rows", $rows);
                $this->render("podeliveryrecordspdf");
            }
        }

        //// debug($result);
        //// debug($manual_po_list);die;
        //$this->set('po_list',$result);
        // $this->set('manual_po',$manual_po_list);
        //$this->set('manual_po',array());
    }

    public function trackpr()
    {
        $previous_url = $this->referer();
        if (strpos($previous_url, 'planningmenu') !== false) {
            $back_url = 'contract';
            $back_page = 'planningmenu';
        } elseif (strpos($previous_url, 'inventory') !== false) {
            $back_url = 'inventory';
            $back_page = 'index';
        } else {
            $back_url = 'Purchase';
            $back_page = 'index';
        }
        $this->set('back_url', $back_url);
        $this->set('back_page', $back_page);

        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == 'deputymanagerelectric') {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }

        $this->set('projects', $projects);
        $this->set('role', $role);
        if ($this->request->is('post')) {
            $request_data = $this->request->data;
            if (isset($request_data["go"])) {
                $this->set('request_data', $request_data);

                $pr_list = $this->Usermanage->fetch_approve_pr_prtrack($this->user_id, $this->request->data);
                $this->set('pr_list', $pr_list);

            } elseif (isset($request_data['export_csv'])) {
                $post = $this->request->data;
                $projects_ids = $this->Usermanage->users_project($this->user_id);
                // debug($post);die;
                #############################
                $pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
                $pr_mat_tbl = TableRegistry::get("erp_inventory_pr_material");
                $or = array();

                $or["erp_inventory_purhcase_request.project_id"] = (!empty($post["project_id"]) && $post["project_id"] != "All") ? $post["project_id"] : null;
                if ($role == 'deputymanagerelectric') {
                    $material_ids = $this->get_deputymanagerelectric_material();
                    $material_ids = json_decode($material_ids);
                    $or["erp_inventory_pr_material.material_id IN"] = $material_ids;
                }

                if ($or["erp_inventory_purhcase_request.project_id"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_inventory_purhcase_request.project_id IN"] = $projects_ids;
                    }
                }
                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_inventory_pr_material.approved ="] = 0;
                $or["erp_inventory_pr_material.show_in_purchase ="] = 1;

                // debug($or);die;
                $result = $pr_tbl->find()->select($pr_tbl);
                $pr_list = $result->innerjoin(
                    ["erp_inventory_pr_material" => "erp_inventory_pr_material"],
                    ["erp_inventory_purhcase_request.pr_id = erp_inventory_pr_material.pr_id"])
                    ->where($or)->select($pr_mat_tbl)->order(["date(erp_inventory_pr_material.approved_date) DESC", "erp_inventory_purhcase_request.project_id ASC"])->hydrate(false)->toArray();

                #############################

                $i = 1;
                $rows = array();
                $rows[] = array("Project Name", "P.R No", "Date", "Time", "Matireal Code", "Matiral Name", "Make/Source", "Quantity", "Unit", "Delivery Date");
                if (!empty($pr_list)) {
                    foreach ($pr_list as $retrive_data) {
                        //debug($retrive_data);die;
                        if (isset($retrive_data["erp_inventory_pr_material"])) {
                            $retrive_data = array_merge($retrive_data, $retrive_data["erp_inventory_pr_material"]);
                        }

                        if ($retrive_data['material_id'] != 0) {
                            $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                            $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                            $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                            $mcode = $this->ERPfunction->get_material_item_code_bymaterialid($retrive_data['material_id']);
                        } else {
                            $mt = $retrive_data['material_name'];
                            $brnd = $retrive_data['brand_name'];
                            $static_unit = $retrive_data['static_unit'];
                            $mcode = $retrive_data['m_code'];
                        }
                        $csv = array();
                        $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                        $csv[] = $retrive_data['prno'];
                        $csv[] = (date("d-m-Y", strtotime($retrive_data['approved_date'])));
                        $csv[] = $retrive_data['pr_time'];
                        $csv[] = $mcode;
                        $csv[] = $mt;
                        $csv[] = $brnd;
                        $csv[] = $retrive_data['quantity'];
                        $csv[] = $static_unit;
                        $csv[] = (date("d-m-Y", strtotime($retrive_data['delivery_date'])));

                        $rows[] = $csv;
                    }
                    //debug($rows);die;
                    $filename = "purchasePRstatus.csv";
                    $this->ERPfunction->export_to_csv($filename, $rows);
                }
            } else {

            }
        }
    }

    public function prepareloi()
    {
        $erp_letter_content = TableRegistry::get('erp_letter_content');

        $erp_material = TableRegistry::get('erp_material');

        if ($this->role == "deputymanagerelectric") {
            $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
            $material_ids = json_decode($material_ids);
            $material_list = $erp_material->find()->where(['material_id IN' => $material_ids, "material_code !=" => 17]);
        } else {
            $material_list = $erp_material->find()->where(["material_code !=" => 17, "project_id" => 0]);
        }
        $this->set('material_list', $material_list);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        $erp_agency = TableRegistry::get('erp_agency');
        $agency_list = $erp_agency->find();
        $this->set('agency_list', $agency_list);

        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        $this->set('projects', $projects);
        $this->set("back", "index");
        $this->set("controller", "purchase");
        $this->set('form_header', 'Prepare Letter of Intent');
        $this->set('button_text', 'Add LOI');

        if ($this->request->is('post')) {
            $data = $this->request->data();
            $code = $this->ERPfunction->get_projectcode($data['project_id']);
            $new_loino = $this->ERPfunction->generate_auto_id($data['project_id'], "erp_letter_content", "id", "loi_no");
            $new_loino = sprintf("%09d", $new_loino);
            $new_loino = $code . '/LOI/' . $new_loino;

            $this->request->data['loi_no'] = $new_loino;
            $this->request->data['loi_purchase_type'] = "loi";
            $this->request->data['loi_date'] = $this->ERPfunction->set_date($this->request->data['loi_date']);
            // $this->request->data['delivery_date']=$this->ERPfunction->set_date($this->request->data['delivery_date']);
            $this->request->data['taxes_duties'] = isset($this->request->data['taxes_duties']) ? $this->request->data['taxes_duties'] : '0';
            $this->request->data['loading_transport'] = isset($this->request->data['loading_transport']) ? $this->request->data['loading_transport'] : '0';
            $this->request->data['unloading'] = isset($this->request->data['unloading']) ? $this->request->data['unloading'] : '0';
            $this->request->data['created_date'] = date('Y-m-d H:i:s');
            $this->request->data['created_by'] = $this->request->session()->read('user_id');
            // $this->request->data['custom_pan']=$this->request->data['custom_pan'];
            // $this->request->data['custom_gst']=$this->request->data['custom_gst'];
            $this->request->data['status'] = 1;

            if (!isset($this->request->data["warranty_check"])) {
                $this->request->data["warranty_check"] = "";
            }

            $entity_data = $erp_letter_content->newEntity();
            $post_data = $erp_letter_content->patchEntity($entity_data, $this->request->data);
            // debug($post_data);die;
            if ($erp_letter_content->save($post_data)) {
                $this->Flash->success(__('LOI Created Successfully with LOI No ' . $new_loino, null),
                    'default',
                    array('class' => 'success'));
                $loi_id = $post_data->id;

                $this->ERPfunction->add_letter_content_detail($this->request->data['material'], $loi_id);
            }
            $this->redirect(array("controller" => "purchase", "action" => "loialert"));
        }
    }

    public function loialert()
    {
        // $po_list = $this->Usermanage->fetch_approve_po($this->user_id);
        // $this->set('po_list',$po_list);
        $selected_project = isset($_REQUEST['selected_project']) ? $_REQUEST['selected_project'] : '';
        $po_type = "";
        $show_data = isset($_REQUEST['selected_project']) ? 1 : 0;
        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == 'deputymanagerelectric') {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }

        $this->set('projects', $projects);
        $this->set('selected_project', $selected_project);
        $this->set('po_type', $po_type);
        $this->set('role', $this->role);
        $this->set('show_data', $show_data);
        if ($this->request->is('post')) {
            $request_data = $this->request->data;
            $this->set('request_data', $request_data);
            if ($this->request->data['from_date'] != '') {
                $this->request->data['from_date'] = date('Y-m-d', strtotime($this->request->data['from_date']));
            }

            if ($this->request->data['to_date'] != '') {
                $this->request->data['to_date'] = date('Y-m-d', strtotime($this->request->data['to_date']));
            }

            // $pr_list = $this->Usermanage->fetch_approve_pr($this->user_id,$this->request->data); /* fetch_approve_po */
            // $this->set('pr_list',$pr_list);
            $this->set('selected_project', $request_data['project_id']);
            // $this->set('po_type',$request_data['po_type']);
            $this->set('show_data', 1);
        }

    }

    public function previewloi($loi_id)
    {
        $erp_letter_content = TableRegistry::get('erp_letter_content');
        $erp_letter_content_detail = TableRegistry::get('erp_letter_content_detail');
        $erp_loi_details = $erp_letter_content->get($loi_id);
        $this->set('erp_loi_details', $erp_loi_details);
        $previw_list = $erp_letter_content_detail->find()->where(array('loi_id' => $loi_id));
        $this->set('previw_list', $previw_list);
    }

    public function printloi($eid, $mail = null)
    {
        require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';

        $erp_letter_content = TableRegistry::get("erp_letter_content");
        $erp_letter_content_detail = TableRegistry::get('erp_letter_content_detail');

        if ($mail == "mail") {
            $previw_list = $erp_letter_content_detail->find()->where(array('loi_id' => $eid, 'currently_approved' => 1));
        } else {
            $previw_list = $erp_letter_content_detail->find()->where(array('loi_id' => $eid));
        }

        $this->set('previw_list', $previw_list);
        $data = $erp_letter_content->get($eid);
        $this->set("data", $data->toArray());
    }

    public function editloi($loi_id)
    {
        $this->set('selected_pl', true);
        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();

        $this->set('vendor_department', $vendor_department);
        $raise_from = $this->ERPfunction->get_mm_constructionmanager();

        $erp_agency = TableRegistry::get('erp_agency');
        $agency_list = $erp_agency->find();
        $this->set('agency_list', $agency_list);

        /* $projects = $this->Usermanage->access_project_ongoing($this->user_id); */
        $projects = $this->Usermanage->access_project($this->user_id);
        $this->set('projects', $projects);
        $this->set('raise_from', $raise_from);

        $erp_material = TableRegistry::get('erp_material');
        $material_list = $erp_material->find();
        $this->set('material_list', $material_list);

        $user_action = 'edit';
        $this->set('user_action', $user_action);
        $this->set('form_header', 'Edit Letter of Intent (LOI)');
        $this->set('button_text', 'Update LOI');

        $erp_letter_content = TableRegistry::get('erp_letter_content');
        $erp_letter_content_detail = TableRegistry::get('erp_letter_content_detail');
        $erp_loi_details = $erp_letter_content->get($loi_id);
        //var_dump($erp_po_details);
        $this->set('erp_loi_details', $erp_loi_details);
        $previw_list = $erp_letter_content_detail->find()->where(array('loi_id' => $loi_id));
        $this->set('previw_list', $previw_list);

        $this->set('loi_id', $loi_id);

        $data = $erp_letter_content_detail->find()->where(["loi_id" => $loi_id, "approved" => 0])->hydrate(false)->toArray();
        //debug($data);
        $i = 0;
        $row = '';
        if (!empty($data)) {
            foreach ($data as $material) {

                //$po_id = $post["selected_po_id_{$material['id']}"];
                //$pr_id = $po_tbl->find()->where(["po_id"=>$post["selected_po_id_{$material['id']}"]])->select(["pr_id"])->hydrate(false)->toArray();
                $m_code = is_numeric($material['material_id']) ? $this->ERPfunction->get_material_item_code_bymaterialid($material['material_id']) : $material['m_code'];

                $mt = is_numeric($material['material_id']) ? $this->ERPfunction->get_material_title
                ($material['material_id']) : $material['material_id'];

                $brnd = is_numeric($material['brand_id']) ? $this->ERPfunction->get_brand_name($material["brand_id"]) : $material["brand_id"];

                $unit = is_numeric($material['material_id']) ? $this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($material['material_id'])) : $material['static_unit'];
                $m_code_row = '';
                $m_row = '';
                $b_row = '';
                $unit_row = '';
                if (is_numeric($material['material_id'])) {
                    $m_code_row .= '<td style="display:none;"><span id="material_code_' . $i . '">' . $m_code . '</span>
					<input type="hidden" value="" name="material[m_code][]" id="m_code_' . $i . '">
					<input type="hidden" value="' . $i . '" name="row_number" class="row_number">
					<input type="hidden" value="' . $material["id"] . '" name="material[detail_id][]">
					</td>';

                    $m_row .= '<select class="select2 material_id" style="width:130px;" name="material[material_id][]" id="material_id_' . $i . '" data-id=' . $i . '>
						<option value="">Select Material</Option>';
                    foreach ($material_list as $retrive_data) {
                        $selected = ($retrive_data['material_id'] == $material['material_id']) ? "selected" : "";
                        $m_row .= '<option value="' . $retrive_data['material_id'] . '"' . $selected . '>' .
                            $retrive_data['material_title'] . '</option>';
                    }
                    $m_row .= '</select>';

                    $b_row .= '<select class="select2 brand_id"  required="true"   name="material[brand_id][]" style="width:130px;" id="brand_id_' . $i . '" data-id=' . $i . '>';
                    $brands = $this->ERPfunction->get_brands_by_material_id($material["material_id"]);
                    if ($brands != "") {
                        foreach ($brands as $brand) {
                            $b_row .= '<option value="' . $brand['brand_id'] . '"' . $this->ERPfunction->selected($brand['brand_id'], $material['brand_id']) . '>' . $brand['brand_name'] . '</option>';
                        }
                    }

                    $b_row .= '</select>';

                    $unit_row .= '<td><span id="unit_name_' . $i . '">' . $unit . '</span>
					<input type="hidden" value="" name="material[static_unit][]" id="static_unit_' . $i . '" class="form-control" style="width:80px;">
					</td>';
                } else {
                    $m_code_row .= '<td style="display:none;"><span id="material_code_' . $i . '">' . $m_code . '</span>
					<input type="hidden" value="' . $m_code . '" name="material[m_code][]" id="m_code_' . $i . '">
					<input type="hidden" value="1" name="material[is_custom][]">
					<input type="hidden" value="' . $i . '" name="row_number" class="row_number">
					<input type="hidden" value="' . $material["id"] . '" name="material[detail_id][]">
					</td>';

                    $m_row .= '<input type="text" name="material[material_id][]" value="' . htmlspecialchars($material["material_id"]) . '" id="material_id_' . $i . '" data-id="' . $i . '" class="form-control material_id" style="width:120px;"/>';
                    $b_row .= '<input type="text" name="material[brand_id][]" value="' . htmlspecialchars($material["brand_id"]) . '" id="brand_id_' . $i . '" class="form-control" style="width:120px;"/>';
                    $unit_row .= '<td><input type="text" value="' . htmlspecialchars($unit) . '" name="material[static_unit][]" id="static_unit_' . $i . '" class="form-control" class="form-control" style="width:120px;"></td>';
                }

                $row .= '<tr class="cpy_row" id="row_id_' . $i . '">
							' . $m_code_row . '
							<td>' . $m_row . '
							</td>';
                // <td>'.$this->ERPfunction->get_materialitem_desc($material['material_id']).'</td>
                $row .= '<td>'
                    . $b_row . '</td>
							<td><input type="text" name="material[quantity][]" data-id="' . $i . '" class="quantity" value="' . $material["quantity"] . '" id="quantity_' . $i . '" style="width:60px"/></td>
							' . $unit_row . '
							<td><input type="text" name="material[unit_rate][]" class="unit_rate" value="' . $material["unit_price"] . '" data-id="' . $i . '" id="unit_rate_' . $i . '" style="width:80px"/>
							<input type="hidden" value="' . $material["pr_mid"] . '" name="material[pr_mid][]"></td>
							<td><input type="text" name="material[discount][]" value="' . $material["discount"] . '" class="tx_count" id="dc_' . $i . '" data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[transportation][]" value="' . $material["transportation"] . '" class="tx_count" id="tr_' . $i . '" data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[exice][]" value="' . $material["exice"] . '" class="tx_count" id="ex_' . $i . '"  data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[other_tax][]" value="' . $material["other_tax"] . '" class="tx_count" id="other_tax_' . $i . '"  data-id="' . $i . '" style="width:55px"></td>
							<td><input type="text" name="material[amount][]" class="amount" value="' . $material["amount"] . '" id="amount_' . $i . '" style="width:90px" /></td>
							<td><input type="text" name="material[single_amount][]" value="' . $material["single_amount"] . '" id="single_amount_' . $i . '" style="width:90px"/></td>

							<input type="hidden" name="loi_mid[]" value="' . $material["id"] . '">
							<td><a href="#" class="btn btn-danger del_parent" data-id="' . $material["id"] . '">Delete</a></td>
						</tr>';

                $i++;
            }
        }
        //debug($row);
        $this->set("row", $row);

        if ($this->request->is('post')) {
            // debug($this->request->data);die;
            $this->request->data['last_edit'] = date('Y-m-d H:i:s');
            $this->request->data['last_edit_by'] = $this->request->session()->read('user_id');
            $this->request->data['loi_date'] = date('Y-m-d', strtotime($this->request->data['loi_date']));
            // $this->request->data['delivery_date']=date('Y-m-d',strtotime($this->request->data['delivery_date']));

            $this->request->data['taxes_duties'] = isset($this->request->data['taxes_duties']) ? $this->request->data['taxes_duties'] : '0';
            $this->request->data['loading_transport'] = isset($this->request->data['loading_transport']) ? $this->request->data['loading_transport'] : '0';
            $this->request->data['unloading'] = isset($this->request->data['unloading']) ? $this->request->data['unloading'] : '0';

            $entity_data = $erp_letter_content->get($loi_id);
            $post_data = $erp_letter_content->patchEntity($entity_data, $this->request->data);
            if ($erp_letter_content->save($post_data)) {

                $this->Flash->success(__('Record Update Successfully', null),
                    'default',
                    array('class' => 'success'));

                $this->ERPfunction->edit_letter_intent_detail($this->request->data['material'], $loi_id);
            }
            //$this->redirect(array("controller" => "Inventory","action" => "approvepo"));
            echo "<script>window.close();</script>";
        }

    }

    public function deleteloi($loim_id)
    {
        $erp_letter_content_detail = TableRegistry::get("erp_letter_content_detail");
        $data = $erp_letter_content_detail->get($loim_id);
        if ($erp_letter_content_detail->delete($data)) {
            $this->Flash->success(__('LOI Material Deleted Successfully.', null),
                'default',
                array('class' => 'success'));
            $this->redirect(["action" => "loialert"]);
        }
    }

    public function printloinorate($eid)
    {
        require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';

        $erp_letter_content = TableRegistry::get("erp_letter_content");
        $erp_letter_content_detail = TableRegistry::get('erp_letter_content_detail');

        $previw_list = $erp_letter_content_detail->find()->where(array('loi_id' => $eid, 'currently_approved' => 1));
        $this->set('previw_list', $previw_list);
        $data = $erp_letter_content->get($eid);
        $this->set("data", $data->toArray());
    }

    public function showinloirecords()
    {
        $this->autoRender = false;
        $post = $this->request->data;
        // debug($post);die;
        $erp_letter_content = TableRegistry::get("erp_letter_content");
        $erp_letter_content_detail = TableRegistry::get("erp_letter_content_detail");
        if (!empty($post["approved_list1"]) || !empty($post["verify_list"]) || (isset($post["approved_list"]) && !empty($post["approved_list"]))) {
            /* for first step approve code start */
            if (!empty($post["approved_list1"])) {
                foreach ($post["approved_list1"] as $loiid) {
                    $loi_no = $this->ERPfunction->get_loi_no_by_id($loiid);

                    if ($loi_no == $post["loi"]) {
                        $query = $erp_letter_content_detail->query();
                        $query = $query->update()->set(["first_approved" => 1, "first_approved_by" => $this->user_id, "first_approved_date" => date("Y-m-d")])->where(["loi_id" => $loiid])->execute();
                    }
                }
            }
            /* first step approve code end */

            /* for verify approve code start */
            if (!empty($post["verify_list"])) {
                foreach ($post["verify_list"] as $loiid) {
                    $loi_no = $this->ERPfunction->get_loi_no_by_id($loiid);

                    if ($loi_no == $post["loi"]) {
                        $query = $erp_letter_content_detail->query();
                        $query = $query->update()->set(["verified" => 1, "verified_by" => $this->user_id, "verified_date" => date("Y-m-d")])->where(["loi_id" => $loiid])->execute();
                    }
                }
            }
            /* first verify code end */

            if (isset($post["approved_list"]) && !empty($post["approved_list"])) {

                // $session = $this->request->session();
                // $session->write(["ids"=>$post['approved_list']]);
                // debug($post);die;
                $approved_id = array();
                foreach ($post["approved_list"] as $loiid) {
                    // $po_no = $this->ERPfunction->get_po_no_by_id($poid);
                    $loi_record = $erp_letter_content->get($loiid);
                    $project_id = $loi_record->project_id;
                    $loi_date = $loi_record->loi_date;
                    $loi_id = $loiid;
                    $loi_no = $loi_record->loi_no;
                    // var_dump($po_no);die;
                    if ($loi_no == $post["loi"]) {
                        $material_row = $erp_letter_content_detail->find()->where(["loi_id" => $loiid])->hydrate(false)->toArray();
                        foreach ($material_row as $m_row) {
                            $approved_id[] = $m_row['id'];
                            $row = $erp_letter_content_detail->get($m_row['id']);
                            $mail_loi_id = $row->loi_id;
                            $row->approved = 1;
                            $row->currently_approved = 1;
                            $row->approved_by = $this->user_id;
                            $row->approved_date = date("Y-m-d");
                            $erp_letter_content_detail->save($row);
                        }
                    }
                }

                $mail_enable = $this->ERPfunction->get_loi_mail_status($mail_loi_id);
                $email_list = $this->ERPfunction->get_mail_list_by_project_loi($project_id, $loi_id, $mail_enable, '"po_notification"');
                // debug($email_list);die;
                $emails = array();
                $emails_norate = array();
                //foreach($post["approved_list"] as $mid)
                // foreach($approved_id as $mid)
                // {
                $mm_email = $this->ERPfunction->get_email_of_mm_by_project($project_id);

                $emails_norate = array_merge($mm_email, $emails_norate);
                $mm_email = array_unique($emails_norate); /*remove duplicate email ids */
                $mm_email = array_filter($mm_email, function ($value) {return $value !== '';});
                $loi_vendor_email = $this->ERPfunction->get_loi_vendor_id($loi_id);
                // }

                // Check the vendor email format are correct or not? code start
                $email_correct = 1;
                $wrong_email = array();
                foreach ($loi_vendor_email as $value) {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {

                    } else {
                        $email_correct = 0;
                        $wrong_email[] = $value;
                    }
                }
                // Check the all email format are correct or not? code end
                // debug($email_list);die;
                if ($email_correct) {

                    if (!empty($email_list)) {
                        $pdpmcm_email = implode(",", $email_list);
                        $view_loi = $loi_id;
                        $this->ERPfunction->mail_loi_withrate($pdpmcm_email, $view_loi, $loi_no, $project_id, $loi_date);
                    }
                    if ($mail_enable != 0) {
                        if (!empty($mm_email)) {
                            $mm_email = implode(",", $mm_email);
                            $view_loi = $loi_id;
                            $this->ERPfunction->mail_loi_withoutrate($mm_email, $view_loi, $loi_no, $project_id, $loi_date);
                        }
                    }
                } else {
                    // Un approve before approved record if email format have problem
                    foreach ($approved_id as $mid) {
                        $loi_no = $this->ERPfunction->get_loi_no_by_detailid($mid);
                        // var_dump($po_no);die;
                        if ($loi_no == $post["loi"]) {
                            $row = $erp_letter_content_detail->get($mid);
                            $row->approved = 0;
                            $row->currently_approved = 0;
                            $row->approved_by = 0;
                            $row->approved_date = 0000 - 00 - 00;
                            $erp_letter_content_detail->save($row);
                        }
                    }
                    // debug($wrong_email);die;
                    $this->Flash->error(__('There is a problem with vendor email format', null),
                        'default',
                        array('class' => 'success'));

                    $this->redirect(array("controller" => "purchase", "action" => "loialert", '?' => array('selected_project' => $post['selected_project_id'])));
                }

                foreach ($approved_id as $mid) {
                    $row = $erp_letter_content_detail->get($mid);
                    $row->currently_approved = 0;
                    $erp_letter_content_detail->save($row);
                }

            }
            $this->redirect(array("controller" => "purchase", "action" => "loialert", '?' => array(
                'selected_project' => $post['selected_project_id'])));
        } else {
            $this->Flash->error(__('Please select record', null),
                'default',
                array('class' => 'success'));
            $this->redirect(array("controller" => "purchase", "action" => "loialert"));
        }
    }

    public function loirecords($projects_id = null, $from = null, $to = null)
    {
        // ini_set('memory_limit', '-1');
        $this->set("user_role", $this->role);
        $role = $this->role;
        if ($role == "erpoperator") {
            $projects = $this->Usermanage->all_access_project($this->user_id);
        } elseif ($role == "deputymanagerelectric") {
            $projects = $this->Usermanage->access_project_ongoing($this->user_id);
        } else {
            $projects = $this->Usermanage->access_project($this->user_id);
        }
        //$projects = $this->ERPfunction->get_projects();
        $this->set('projects', $projects);

        $erp_material = TableRegistry::get('erp_material');
        if ($role == "deputymanagerelectric") {
            $material_ids = $this->ERPfunction->get_deputymanagerelectric_material();
            $material_ids = json_decode($material_ids);
            $material_list = $erp_material->find()->where(["material_id IN" => $material_ids]);
        } else {
            $material_list = $erp_material->find();
        }
        $this->set('material_list', $material_list);

        $users_table = TableRegistry::get('erp_vendor');
        $vendor_department = $users_table->find();
        $this->set('vendor_department', $vendor_department);

        $erp_material_brand = TableRegistry::get('erp_material_brand');
        $brand_list = $erp_material_brand->find();
        $this->set('brand_list', $brand_list);

        $user = $this->request->session()->read('user_id');
        //var_dump($user);die;
        $role = $this->Usermanage->get_user_role($user);
        $projects_ids = $this->Usermanage->users_project($user);

        $this->set("back", "index");
        $this->set("projects_id", $projects_id);
        $this->set("from", $from);
        $this->set("to", $to);

        // if($projects_id!=null){

        // $erp_inventory_po = TableRegistry::get("erp_inventory_po");
        // $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");

        // $erp_manual_po = TableRegistry::get("erp_manual_po");
        // $erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
        // $total=0;
        // $or1 = array();

        // $or["erp_inventory_po_detail.approved_date >="] = date('Y-m-d',strtotime($from));
        // $or["erp_inventory_po_detail.approved_date <="] = date('Y-m-d',strtotime($to));
        // $or["project_id"] = $projects_id;
        // $keys = array_keys($or,"");
        // foreach ($keys as $k)
        // {unset($or[$k]);}

        // $or1["erp_manual_po_detail.approved_date >="] = date('Y-m-d',strtotime($from));
        // $or1["erp_manual_po_detail.approved_date <="] = date('Y-m-d',strtotime($to));
        // $or1["project_id"] = $projects_id;
        // $keys = array_keys($or1,"");
        // foreach ($keys as $k)
        // {unset($or1[$k]);}

        // $result = $erp_inventory_po->find()->select($erp_inventory_po);
        // $result1 = $result->innerjoin(
        // ["erp_inventory_po_detail"=>"erp_inventory_po_detail"],
        // ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id","erp_inventory_po_detail.approved ="=>1])->where($or)->select($erp_inventory_po_detail)->group('erp_inventory_po.po_no')->order(['erp_inventory_po_detail.approved_date'=>'DESC'])->hydrate(false)->toArray();

        // $manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
        // $manual_po_list1 = $manual_po_list->innerjoin(
        // ["erp_manual_po_detail"=>"erp_manual_po_detail"],
        // ["erp_manual_po.po_id = erp_manual_po_detail.po_id","erp_manual_po_detail.approved ="=>1])
        // ->where($or1)->select($erp_manual_po_detail)->group('erp_manual_po.po_no')->order(['erp_manual_po.po_date'=>'DESC'])->hydrate(false)->toArray();

        // $this->set('po_list',$result1);
        // $this->set('manual_po',$manual_po_list);
        // $this->set('manual_po',$manual_po_list);
        // }
        // else{
        // $result = $this->Usermanage->fetch_view_po_new($this->user_id);
        // $manual_po_list = $this->Usermanage->fetch_view_po_manual($this->user_id);

        // $this->set('po_list',$result);
        // $this->set('manual_po',$manual_po_list);
        // $this->set('po_list',array());
        // $this->set('manual_po',array());
        // }

        if ($this->request->is('post')) {
            if (isset($this->request->data["go1"])) {
                $erp_inventory_po = TableRegistry::get("erp_inventory_po");
                $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
                $post = $this->request->data;
                $or = array();

                if ($post['po_type'] == "po") {
                    $or["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or["erp_inventory_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or["erp_inventory_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;

                    $keys = array_keys($or, "");
                    foreach ($keys as $k) {unset($or[$k]);}
                    //debug($post);
                    //debug($or);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $result = $erp_inventory_po->find()->select($erp_inventory_po)->where(["project_id IN" => $projects_ids]);
                            $result = $result->innerjoin(
                                ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                                ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                                ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $result = array();
                        }
                    } else {
                        $result = $erp_inventory_po->find()->select($erp_inventory_po);
                        $result = $result->innerjoin(
                            ["erp_inventory_po_detail" => "erp_inventory_po_detail"],
                            ["erp_inventory_po.po_id = erp_inventory_po_detail.po_id", "erp_inventory_po_detail.approved !=" => 0])
                            ->where($or)->select($erp_inventory_po_detail)->order(['erp_inventory_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }
                    $this->set('po_list', $result);
                    $this->set('manual_po', array());
                } else {
                    // For manual po search

                    $erp_manual_po = TableRegistry::get("erp_manual_po");
                    $erp_manual_po_detail = TableRegistry::get("erp_manual_po_detail");
                    $or1 = array();

                    $or1["po_date >="] = ($post["from_date"] != "") ? date("Y-m-d", strtotime($post["from_date"])) : null;
                    $or1["po_date <="] = ($post["to_date"] != "") ? date("Y-m-d", strtotime($post["to_date"])) : null;
                    $or1["project_id IN"] = (!empty($post["project_id"]) && $post["project_id"][0] != "All") ? $post["project_id"] : null;
                    $or1["erp_manual_po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"][0] != "All") ? $post["material_id"] : null;
                    $or1["erp_manual_po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"][0] != "All") ? $post["brand_id"] : null;
                    $or1["vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"][0] != "All") ? $post["vendor_userid"] : null;
                    $or1["po_no"] = (!empty($post["po_no"])) ? $post["po_no"] : null;
                    if ($post['po_type'] == "manualpolocal") {
                        //for manual po on base of grn search
                        $or1["is_grn_base"] = 1;
                    } else {
                        $or1["is_grn_base !="] = 1;
                    }
                    $keys = array_keys($or1, "");
                    foreach ($keys as $k) {unset($or1[$k]);}
                    // debug($post);
                    // debug($or1);die;

                    /* ,array('fields'=>array('sum(stock_in) AS total_stock_in')) */
                    if ($role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'materialmanager' || $role == 'projectcoordinator' || $role == 'siteaccountant' || $role == "deputymanagerelectric") {
                        if (!empty($projects_ids)) {
                            $manual_po_list = $erp_manual_po->find()->select($erp_manual_po)->where(["project_id IN" => $projects_ids]);
                            $manual_po_list = $manual_po_list->innerjoin(
                                ["erp_manual_po_detail" => "erp_manual_po_detail"],
                                ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved !=" => 0])
                                ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                            //var_dump($result);die;
                            //$this->set('grn_list',$result);
                        } else {
                            $manual_po_list = array();
                        }
                    } else {
                        $manual_po_list = $erp_manual_po->find()->select($erp_manual_po);
                        $manual_po_list = $manual_po_list->innerjoin(
                            ["erp_manual_po_detail" => "erp_manual_po_detail"],
                            ["erp_manual_po.po_id = erp_manual_po_detail.po_id", "erp_manual_po_detail.approved !=" => 0])
                            ->where($or1)->select($erp_manual_po_detail)->order(['erp_manual_po.po_date' => 'DESC'])->hydrate(false)->toArray();
                        //var_dump($result);die;

                    }

                    $this->set('po_list', array());
                    $this->set('manual_po', $manual_po_list);
                }
            }
            if (isset($this->request->data["export_csv"])) {
                $erp_letter_content = TableRegistry::get("erp_letter_content");
                $erp_letter_content_detail = TableRegistry::get("erp_letter_content_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_letter_content.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_letter_content_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_letter_content_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_letter_content.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_letter_content.loi_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_letter_content.loi_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_letter_content.loi_no ="] = (!empty($post["e_loi_no"])) ? $post["e_loi_no"] : null;

                if ($or["erp_letter_content.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_letter_content.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_letter_content_detail.approved !="] = 0;

                $result = $erp_letter_content->find()->select($erp_letter_content);
                $result = $result->innerjoin(
                    ["erp_letter_content_detail" => "erp_letter_content_detail"],
                    ["erp_letter_content.id = erp_letter_content_detail.loi_id"])
                    ->where($or)->select($erp_letter_content_detail)->order(['erp_letter_content.loi_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("LOI No", "LOI Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "Quantity", "Unit", "Final Rate", "Amount");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_letter_content_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_letter_content_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }

                    $csv = array();
                    $csv[] = $retrive_data['loi_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['loi_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['single_amount'];
                    $csv[] = $retrive_data['amount'];
                    $rows[] = $csv;
                }

                $filename = "loi_records.csv";
                $this->ERPfunction->export_to_csv($filename, $rows);
            }

            if (isset($this->request->data["export_pdf"])) {
                require_once ROOT . DS . 'vendor' . DS . 'mpdf' . DS . 'mpdf.php';
                $erp_letter_content = TableRegistry::get("erp_letter_content");
                $erp_letter_content_detail = TableRegistry::get("erp_letter_content_detail");
                // debug($this->request->data);die;
                // $rows = unserialize(base64_decode($this->request->data["rows"]));
                $post = $this->request->data;
                $or = array();
                $post["e_pro_id"] = (!empty($post["e_pro_id"])) ? explode(",", $post["e_pro_id"]) : null;
                $post["e_material_id"] = (!empty($post["e_material_id"])) ? explode(",", $post["e_material_id"]) : null;
                $post["e_brand_id"] = (!empty($post["e_brand_id"])) ? explode(",", $post["e_brand_id"]) : null;
                $post["e_vendor_userid"] = (!empty($post["e_vendor_userid"])) ? explode(",", $post["e_vendor_userid"]) : null;

                $or["erp_letter_content.project_id IN"] = (!empty($post["e_pro_id"]) && $post["e_pro_id"] != "All") ? $post["e_pro_id"] : null;
                $or["erp_letter_content_detail.material_id IN"] = (!empty($post["e_material_id"]) && $post["e_material_id"] != "All") ? $post["e_material_id"] : null;
                $or["erp_letter_content_detail.brand_id IN"] = (!empty($post["e_brand_id"]) && $post["e_brand_id"] != "All") ? $post["e_brand_id"] : null;
                $or["erp_letter_content.vendor_userid IN"] = (!empty($post["e_vendor_userid"]) && $post["e_vendor_userid"] != "All") ? $post["e_vendor_userid"] : null;
                $or["erp_letter_content.loi_date >="] = ($post["e_date_from"] != "") ? date("Y-m-d", strtotime($post["e_date_from"])) : null;
                $or["erp_letter_content.loi_date <="] = ($post["e_date_to"] != "") ? date("Y-m-d", strtotime($post["e_date_to"])) : null;
                $or["erp_letter_content.loi_no ="] = (!empty($post["e_loi_no"])) ? $post["e_loi_no"] : null;

                if ($or["erp_letter_content.project_id IN"] == null) {
                    if ($this->Usermanage->project_alloted($role) == 1) {
                        $or["erp_letter_content.project_id IN"] = implode(",", $projects_ids);
                    }
                }

                $keys = array_keys($or, "");
                foreach ($keys as $k) {unset($or[$k]);}

                $or["erp_letter_content_detail.approved !="] = 0;

                $result = $erp_letter_content->find()->select($erp_letter_content);
                $result = $result->innerjoin(
                    ["erp_letter_content_detail" => "erp_letter_content_detail"],
                    ["erp_letter_content.id = erp_letter_content_detail.loi_id"])
                    ->where($or)->select($erp_letter_content_detail)->order(['erp_letter_content.loi_date' => 'DESC'])->hydrate(false)->toArray();

                $rows = array();
                $rows[] = array("LOI No", "LOI Date", "Project Name", "Vendor Name", "Material Name", "Make/Source", "Quantity", "Unit", "Final Rate", "Amount");

                foreach ($result as $retrive_data) {
                    if (isset($retrive_data["erp_letter_content_detail"])) {
                        $retrive_data = array_merge($retrive_data, $retrive_data["erp_letter_content_detail"]);
                    }
                    if (is_numeric($retrive_data['material_id'])) {
                        $mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
                        $brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
                        $static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
                    } else {
                        $mt = $retrive_data['material_id'];
                        $brnd = $retrive_data['brand_id'];
                        $static_unit = $retrive_data['static_unit'];
                    }

                    $csv = array();
                    $csv[] = $retrive_data['loi_no'];
                    $csv[] = $this->ERPfunction->get_date($retrive_data['loi_date']);
                    $csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']);
                    $csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']);
                    $csv[] = $mt;
                    $csv[] = $brnd;
                    $csv[] = $retrive_data['quantity'];
                    $csv[] = $static_unit;
                    $csv[] = $retrive_data['single_amount'];
                    $csv[] = $retrive_data['amount'];
                    $rows[] = $csv;
                }

                $this->set("rows", $rows);
                $this->render("loirecordpdf");
            }
        }

        //// debug($result);
        //// debug($manual_po_list);die;
        //$this->set('po_list',$result);
        // $this->set('manual_po',$manual_po_list);
        //$this->set('manual_po',array());
    }

    public function cancelloi($loi_id = null)
    {

        $erp_letter_content_detail = TableRegistry::get("erp_letter_content_detail");
        $erp_letter_content = TableRegistry::get("erp_letter_content");

        if ($loi_id != null) {
            $get_deleted_loi = $erp_letter_content->get($loi_id);
            $deleted_loi = $get_deleted_loi->toArray();
            $mail_check = $deleted_loi["mail_check"];
            $del_loi_project_id = $deleted_loi["project_id"];
            $del_loi_no = $deleted_loi["loi_no"];
            $del_loi_project_name = $this->ERPfunction->get_projectname($deleted_loi["project_id"]);
            $del_loi_party_name = $this->ERPfunction->get_vendor_name($deleted_loi["vendor_userid"]);

            $pdpmcm_email = $this->ERPfunction->get_email_of_pd_pm_cm_by_project_loi($del_loi_project_id, $loi_id);
            $mm_email = $this->ERPfunction->get_email_of_mm_by_project($del_loi_project_id);

            $loi_detail = $erp_letter_content_detail->find("all")->where(["loi_id" => $loi_id, "approved" => 1]);

            if (!empty($loi_detail)) {
                $query = $erp_letter_content_detail->query();
                $query->update()
                    ->set(['approved' => 0,
                        "approved_by" => "",
                        "approved_date" => ""])
                    ->where(['loi_id' => $loi_id])
                    ->execute();

                $get_deleted_loi = $erp_letter_content->get($loi_id);

                if ($query) {
                    $projectdetail = TableRegistry::get('erp_projects');
                    $project_data = $projectdetail->get($del_loi_project_id);
                    $code = $project_data->project_code;

                    $new_loino = $this->ERPfunction->generate_auto_id($del_loi_project_id, "erp_letter_content", "id", "loi_no");
                    $new_loino = sprintf("%09d", $new_loino);
                    $new_loino = $code . '/LOI/' . $new_loino;

                    $update_data['loi_no'] = $new_loino;
                    $data = $erp_letter_content->patchEntity($get_deleted_loi, $update_data);
                    $erp_letter_content->save($data);
                    if ($mail_check == 1) {
                        $emails1 = array();
                        $emails2 = array();
                        // $project_wise_role = ['deputymanagerelectric'];
                        // $project_email = $this->ERPfunction->get_email_id_by_project_from_user($del_po_project_id,$project_wise_role);
                        // $emails1 = array_merge($emails1,$project_email);

                        $emails1 = array_merge($pdpmcm_email, $emails1);
                        $emails2 = array_merge($mm_email, $emails2);
                        $role = ['erphead', 'erpmanager', 'erpoperator', 'ceo'];
                        $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                        $emails2 = array_merge($erp_email, $emails2);
                        $pdpmcm_email = array_unique($emails1); /*remove duplicate email ids */
                        $mm_email = array_unique($emails2); /*remove duplicate email ids */
                        $pdpmcm_email = array_filter($pdpmcm_email, function ($value) {return $value !== '';});
                        $mm_email = array_filter($mm_email, function ($value) {return $value !== '';});

                        $all_users = array_unique(array_merge($pdpmcm_email, $mm_email));
                    } elseif ($mail_check == 2) {
                        $emails1 = array();
                        $emails2 = array();
                        $project_wise_role = ['deputymanagerelectric'];
                        $project_email = $this->ERPfunction->get_email_id_by_project_from_user($del_loi_project_id, $project_wise_role);
                        $emails1 = array_merge($emails1, $project_email);

                        $emails1 = array_merge($pdpmcm_email, $emails1);
                        $emails2 = array_merge($mm_email, $emails2);
                        $role = ['erphead', 'erpmanager', 'erpoperator', 'ceo'];
                        $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                        $emails2 = array_merge($erp_email, $emails2);
                        $pdpmcm_email = array_unique($emails1); /*remove duplicate email ids */
                        $mm_email = array_unique($emails2); /*remove duplicate email ids */
                        $pdpmcm_email = array_filter($pdpmcm_email, function ($value) {return $value !== '';});
                        $mm_email = array_filter($mm_email, function ($value) {return $value !== '';});

                        $all_users = array_unique(array_merge($pdpmcm_email, $mm_email));
                    } else {
                        $emails = array();
                        $role = ['erphead', 'erpmanager', 'purchasehead', 'purchasemanager', 'md', 'erpoperator', 'ceo'];
                        $erp_email = $this->ERPfunction->get_email_id_by_role_from_user($role);
                        $emails = array_merge($erp_email, $emails);
                        $emails = array_unique($emails); /*remove duplicate email ids */
                        $all_users = array_filter($emails, function ($value) {return $value !== '';});
                    }

                    // debug($all_users);die;
                    if (!empty($all_users)) {
                        $all_users = implode(",", $all_users);
                        $this->ERPfunction->cancel_loi_mail($all_users, $del_loi_no, $del_loi_project_name, $del_loi_party_name);
                    }

                    // if(!empty($pdpmcm_email))
                    // {
                    // $pdpmcm_email = implode(",",$pdpmcm_email);
                    // $this->ERPfunction->cancel_po_mail($pdpmcm_email,$del_po_no,$del_po_project_name,$del_po_party_name);
                    // }

                    // if(!empty($mm_email))
                    // {
                    // $mm_email = implode(",",$mm_email);
                    // $this->ERPfunction->cancel_po_mail($mm_email,$del_po_no,$del_po_project_name,$del_po_party_name);
                    // }
                }
            }

            $this->Flash->success(__('LOI Cancelled Successfully.Record will show in LOI Alert page.', null),
                'default',
                array('class' => 'success'));
            $this->redirect(["action" => "loirecords"]);
        }
    }

    public function filemanager()
    {
        $baseurl = Router::url($this->here, true);
        $projects = $this->Usermanage->access_project($this->user_id);
        $this->set('projects', $projects);
        $location = "";
        $this->set('location', $location);
        $this->set('role', $this->role);
        $this->set('baseurl', $baseurl);

        if ($this->request->is("post")) {
            if (isset($this->request->data["searchbyproject"])) {
                $project_name = ($this->request->data["project_id"] != '') ? $this->ERPfunction->get_projectname($this->request->data["project_id"]) : '';
                $this->set('location', $project_name);
            }
        }
    }

    public function mailporecord2($po_id) {
        require_once(ROOT . DS .'vendor' . DS  . 'mpdf' . DS . 'mpdf.php');
        $rmc_tbl = TableRegistry::get("erp_inventory_po");
        $erp_inve_po_details = TableRegistry::get('erp_inventory_po_detail');
        $previw_list = $erp_inve_po_details->find()->where(['po_id'=>$po_id,'currently_approved'=>1])->hydrate(false);
        $this->set('previw_list',$previw_list);
        $data = $rmc_tbl->get($po_id);
        $this->set("data",$data->toArray());            
    }

    public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}
