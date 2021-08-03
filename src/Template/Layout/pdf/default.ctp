<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Yashnand ERP';
?>
<!DOCTYPE html>
<html>
<head>
  
</head>
<?php
$user_id=$this->request->session()->read('user_id');
?>

<body class="bg-light-gray"> <!-- bg-img-num1 -->  
 
    <div class="container" id="main-div"> 
		<?php 
		if($user_id)
		{
		?>
		<?php /* Header Top Menu src/Template/Element/header.ctp */?>
		<?php /* $this->element('header')*/ ?>	
		<?php /* End Header Top Menu */?>
		
		<?php /* Flash Message */?>
		<?= $this->Flash->render() ?>
		<?php /* End Flash Message*/?>
		
		<div class="row"><!-- start Content row2 -->
		
		<?php /*Left Menu*/	?>
		<?php 
		$user_role = $this->ERPfunction->get_user_role($user_id);
		/* switch($user_role)
		{
			case 'materialmanager':
				echo $this->element('mm-menu');
				break;
			case 'constructionmanager':
				echo $this->element('cm-menu');
				break;
			default:
				echo $this->element('left-sidebar');
		} */
		?>
				
		<?php /*End Left Menu*/	?>	
		
		<?php /*Content Start //src/Template/Controller.ctp */	?>
		<?= $this->fetch('content') ?>
		<?php /*Content Start*/	?>		
		
		</div><!-- End Content row2 -->
			
		<?php 
		}
		else
		{
		?>
		<?php /*Login*/	?>
		<?= $this->fetch('content'); ?>	
		<?php /*End Login*/	?>	
		<?php
		}		
		?>
	</div><!-- end .container-->    
</body>
</html>
