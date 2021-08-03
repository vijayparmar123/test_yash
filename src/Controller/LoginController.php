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
use Cake\Network\Session\DatabaseSession;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry; 
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class LoginController extends AppController
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
		// $user = (isset($_GET['user']))?$_GET['user']:"";
		// if($user != "das")
		// {
		// 	// echo "Site is under maintenance! try after sometime";die;
		// 	echo "<!doctype html>
		// 	<title>Site Maintenance</title>
		// 	<style>
		// 	  body { text-align: center; padding: 150px; }
		// 	  h1 { font-size: 50px; }
		// 	  body { font: 20px Helvetica, sans-serif; color: #333; }
		// 	  article { display: block; text-align: left; width: 650px; margin: 0 auto; }
		// 	  a { color: #dc8100; text-decoration: none; }
		// 	  a:hover { color: #333; text-decoration: none; }
		// 	</style>
			
		// 	<article>
		// 		<h1>We&rsquo;ll be back soon!</h1>
		// 		<div>
		// 			<p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. If you need to you can always <a href='mailto:#'>contact us</a>, otherwise we&rsquo;ll be back online shortly!</p>
		// 			<p>&mdash; The YNEC PVT.LTD</p>
		// 		</div>
		// 	</article>";die;
		// }
		
		$this->viewBuilder()->layout("registration");
		if($this->request->session()->read('user_id'))
		{
			return $this->redirect(['controller'=>'Dashboard']);
		}
		
		$user_id = 0;
		$flag = 0;
		$user_table=TableRegistry::get('erp_users');
		if($this->request->is('post'))		
		{		
			$username=$this->request->data('username');
			$password=md5($this->request->data('password'));
			//$password=$this->request->data('password');
			/* $exists = $user_table->exists(['email_id'=>$email_id,'password'=>$password,"status"=>1]); */
			$exists = $user_table->exists(['username'=>$username,'password'=>$password,"status"=>1]);
			
			if($exists)
			{
				$user_role_data='';
				
				/* $results = $user_table->find('all',array('conditions' => array('email_id' => $email_id, 'password' => $password))); */
				$results = $user_table->find('all',array('conditions' => array('username' => $username, 'password' => $password)));
				
				foreach($results as $data){
					
					$user_id=$data['user_id'];	
					$user_role_data=$data['role'];	
					
				}
				$flag = 1;
			}
			
			if($user_id)
			{
				$session_data = $this->request->session();
				$session_data->write('user_id',$user_id);
				$session_data->write('role',$user_role_data);
				return $this->redirect(['controller'=>'Dashboard']);
			}
			
			$this->set('flag',$flag);
		}	
    }	
	public function logout()
    {
		$session = $this->request->session();
		$session->destroy();
		return $this->redirect(['controller' => 'Login','action'=>'index']);
    }	
}
