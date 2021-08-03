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

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class DashboardController extends AppController
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
	}
    public function index()
    {
		$this->user_id=$this->request->session()->read('user_id');
		
        $projects = $this->Usermanage->all_access_project($this->user_id);
		
		$this->set('projects',$projects);
		
	
		$date_from= date('Y-m-d');
		$date_to= date('Y-m-d');
		
		if($this->request->is("post"))
		{
			$data=$this->request->data();
			$project_id= $data['project_id'];
			$date_from= $data['date_from'];
			$date_to= $data['date_to'];
			$pro_code= $data['project_code'];
			$this->set('project_id',$project_id);
			$this->set('pro_code',$pro_code);
		}
		
		$this->set('date_from',$date_from);
		$this->set('date_to',$date_to);
    }

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}
