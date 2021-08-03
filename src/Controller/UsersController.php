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
use Cake\Auth\DefaultPasswordHasher;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController
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
		return $this->redirect(["action"=>"login"]);
    }	

    public function login() {	
		// $user = (isset($_GET['user']))?$_GET['user']:"";
		// if($user != "das"){
		// 	echo "<!doctype html>
		// 	<title>Site Maintenance</title>
		// 	<style>
		// 	body { text-align: center; padding: 150px; }
		// 	h1 { font-size: 50px; }
		// 	body { font: 20px Helvetica, sans-serif; color: #333; }
		// 	article { display: block; text-align: left; width: 650px; margin: 0 auto; }
		// 	a { color: #dc8100; text-decoration: none; }
		// 	a:hover { color: #333; text-decoration: none; }
		// 	</style>
			
		// 	<article>
		// 		<h1>We&rsquo;ll be back soon!</h1>
		// 		<div>
		// 			<p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. If you need to you can always <a href='mailto:#'>contact us</a>, otherwise we&rsquo;ll be back online shortly!</p>
		// 			<p>&mdash; The YNEC </p>
		// 		</div>
		// 	</article>";die;
		// }
		$this->viewBuilder()->layout("registration");
		if ($this->request->is('post')) {
			if($this->request->data['g-recaptcha-response']=="")
			{
				$this->Flash->error(__('Your Must Select reCAPTCHA Code.', null),[
						'params' => [
						'class' => 'alert alert-error'
						]
				]);
			}
			
			else
			{
			
				$http_user_agent = $this->request->env('HTTP_USER_AGENT');
				if ($http_user_agent == 'wget' && $http_user_agent =='curl' ) {
					return 403;
				 }else{
					if ($this->request->is('post')) {
						$users = $this->Auth->identify();
						// if($users['username'] == "Manan Patel" || 
						// $users['username'] == "Dhaval Patel" || 
						// $users['username'] == "Anil Shrivastava" ||
						// $users['username'] == "Dwarka Prasad" ||
						// $users['username'] == "Gaurav Giri" ||
						// $users['username'] == "Jayesh Gelani" ||
						// $users['username'] == "Nasir Khan" || 
						// $users['username'] == "Nilesh Sahu" || 
						// $users['username'] == "Pramod Sahu" ||
						// $users['username'] == "Ramjivan Dubey" ||
						// $users['username'] == "Satyabhan Singh" ||
						// $users['username'] == "Ved Prakash" ||
						// $users['username'] == "Vinod Raval") {

							if($users) {
								$this->Auth->setUser($users);
								$session_data = $this->request->session();
								$user_id = $session_data->read('Auth.User.user_id');
								$username = $session_data->read('Auth.User.username');
								$user_role_data = $session_data->read('Auth.User.role');
								$session_data->write('user_id',$user_id);
								$session_data->write('role',$user_role_data);
								$session_data->write('image_validation',"Please upload files having extensions:<b> jpeg, jpg, png, pdf, csv </b>only.");
								
								$erp_activitylog = TableRegistry::get('erp_activitylog'); 
								// Store the IP address
								$vister_ip = $this->ERPfunction->getVisIPAddr();
								
								$erp_activitylog_data = $erp_activitylog->newEntity();
								$erp_activitylog_data['username']=$username;
								$erp_activitylog_data['ip_address']=$vister_ip;
								$erp_activitylog_data['activityname']="login";
								$erp_activitylog_data['created_date']= date('Y-m-d H:i:s');
								$erp_activitylog->save($erp_activitylog_data);
								
								return $this->redirect($this->Auth->redirectUrl());
							}else{
								$this->Flash->error(__('Please reset your password first'));
							}
						// }else {
						// 	$this->Flash->error(__("Not Authorized"));
						// 	return $this->redirect(['controller' => 'Users','action'=>'index']);
						// }
						
					}
				}
			}
		}
    }

	public function logout()
    {
		$session = $this->request->session();
		$session->delete('User');		
		$session->destroy();		
		return $this->redirect($this->Auth->logout());
    }	

	public function resetPassword() {
		$this->viewBuilder()->layout("registration");
		if($this->request->is('post')){

			###############################################################################

			// Reset password with cakephp default hash 
			$username = $this->request->data('username');
			$oldPassword = $this->request->data('old_password');
			
			$userTable = TableRegistry::get('erp_users');
			$user = $userTable->find()->where(['username'=> $username])->first();
			
			$hasher = new DefaultPasswordHasher();
			$oldPasswordConfirm = $hasher->check($oldPassword,$user->password);
			if($oldPasswordConfirm) {
				$newPassword = $this->request->data('new_password');
				$confirmPassword = $this->request->data('confirm_password');

				// Validate password strength
				$uppercase = preg_match('@[A-Z]@', $newPassword);
				$lowercase = preg_match('@[a-z]@', $newPassword);
				$number    = preg_match('@[0-9]@', $newPassword);
				$specialChars = preg_match('@[^\w]@', $newPassword);

				if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newPassword) < 8) {
					$this->Flash->error(__('Sorry! Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.'));
					return $this->redirect(["action"=>"login"]);
				}

				if($newPassword != $confirmPassword)
				{
					$this->Flash->error(__('Sorry! Password and confirm password does not match.'));
					return $this->redirect(["action"=>"login"]);
				}

				$newPassword = $hasher->hash($newPassword);		
				$user->password = $newPassword;
				$user->is_exist = 1;
				if($userTable->save($user)) {
					$this->Flash->success(__('Your Password has been changed successfully.'));
					return $this->redirect(['controller'=>'Users','action'=>'login']);
				}else{
					$this->Flash->error(__('Sorry! Something went wrong, contact administrator.'));
					return $this->redirect(["action"=>"login"]);	
				}
			}else {
				$this->Flash->error(__('Sorry! The Username and Old Password does not match, contact administrator.'));
				return $this->redirect(["action"=>"login"]);		
			}

			###############################################################################

			// Reset password with old md5 hash 
			// $username = $this->request->data('username');
			// $hashedPassword = md5($this->request->data('old_password'));
			
			// $newPassword = $this->request->data('new_password');
			// $confirmPassword = $this->request->data('confirm_password');
			// $userTable = TableRegistry::get('erp_users');
			
			// $exists = $userTable->exists(['username'=>$username,'password'=>$hashedPassword,"status"=>1]);
			
			// if($exists) {
			// 	if($this->request->is('post')) {
			// 		$hasher = new DefaultPasswordHasher();
			// 		$myPass=$hasher->hash($newPassword);
			// 		$user = $userTable->find('all')->where(['username'=>$username])->first();		
			// 		$user->password= $myPass;
			// 		$user->is_exist = 1;
			// 		if($userTable->save($user)) {
			// 			$this->Flash->success(__('Your Password has been changed successfully.'));
			// 			return $this->redirect(['controller'=>'Users','action'=>'login']);
			// 		}
			// 	}
			// }else {
			// 	$this->Flash->error(__('Sorry! The Username and Old Password does not match.'));
			// 	return $this->redirect(["action"=>"login"]);		
			// }
		}
		
	}

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}
}
