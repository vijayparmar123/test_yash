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
<style>
	#main-div
	{
		padding-top:15px;
	}
	
</style>

    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('stylesheets.css') ?>
	<?= $this->Html->css('login/mediacss.css') ?>
    <?= $this->Html->css('styles_side.css') ?>
    <?= $this->Html->css('mystyles.css') ?>
    <?= $this->Html->css('dataTables.responsive.css') ?>
	  <?= $this->Html->css('validationEngine.jquery.css') ?>
	<?php /* JS Include*/?>
	<!--<?= $this->Html->script('plugins/jquery/jquery.min.js')?> old Jquery-->
	<?= $this->Html->script('plugins/jquery/jquery-3.6.0.min.js')?>
	<!-- <?= $this->Html->script('plugins/jquery/jquery-ui.min.js')?> old Jquery-ui --> 
	<?= $this->Html->script('plugins/jquery/jquery-ui-1.12.1.min.js')?>

	<!-- <?= $this->Html->script('plugins/jquery/jquery-migrate.min.js')?> --><!--old Jquery migrate -->
	<?= $this->Html->script('plugins/jquery/jquery-migrate-3.3.2.min.js')?> 

	<!-- <?= $this->Html->script('plugins/jquery/globalize.js')?> Old Globalize -->
	<?= $this->Html->script('plugins/jquery/globalize-1.6.0.min.js')?>

	<?= $this->Html->script('plugins/bootstrap/bootstrap.min.js')?>

	<!-- <?= $this->Html->script('plugins/uniform/jquery.uniform.min.js')?> Old jQuery Uniform-->
	<?= $this->Html->script('plugins/uniform/jquery.uniform.standalone-4.3.0.js')?>

	<?= $this->Html->script('plugins/datatables/jquery.dataTables.min.js')?>
	<!-- <?= $this->Html->script('plugins/datatables/jquery.dataTables-1.10.25.min.js')?> -->

	<?= $this->Html->script('plugins/datatables/dataTables.tableTools.min.js')?>
	<!-- <?= $this->Html->script('plugins/datatables/dataTables.tableTools-2.2.4.min.js')?> -->

	<!-- <?= $this->Html->script('plugins/datatables/dataTables.editor.min.js')?>  -->

	<?= $this->Html->script('plugins/datatables/dataTables.responsive.js')?> 
	<!-- <?= $this->Html->script('plugins/datatables/dataTables.responsive-2.2.3.min.js')?> -->

	<?= $this->Html->script('plugins/select2/select2.min.js')?> 
	<!-- <?= $this->Html->script('plugins/select2/select2-4.0.13.min.js')?> affet on Dropdown -->

	<!-- <?= $this->Html->script('plugins/tagsinput/jquery.tagsinput.min.js')?> Old TagsInput -->
	<?= $this->Html->script('plugins/tagsinput/jquery.tagsinput-1.3.6.min.js')?>

	<!-- <?= $this->Html->script('plugins/jquery/jquery-ui-timepicker-addon.js')?> Old Jquery UI TimePicker -->
	<?= $this->Html->script('plugins/jquery/jquery-ui-timepicker-addon-1.6.3.min.js')?>


	<!-- <?= $this->Html->script('plugins/ibutton/jquery.ibutton.js')?> Old Ibutton -->
	<?= $this->Html->script('plugins/ibutton/jquery.ibutton-1.0.04.min.js')?>
	
	<!-- <?= $this->Html->script('plugins/validationengine/js/languages/jquery.validationEngine-en.js')?> Old JS ValidationEngine EN-->
	<?= $this->Html->script('plugins/validationengine/js/languages/jquery.validationEngine-en-2.6.4.min.js')?>
	
	<!-- <?= $this->Html->script('plugins/validationengine/js/jquery.validationEngine.js')?> Old JS ValidationEngine -->
	<?= $this->Html->script('plugins/validationengine/js/jquery.validationEngine-2.6.4.min.js')?>

	<?= $this->Html->script('plugins.js')?>
	<!--<?= $this->Html->script('actions.js')?>-->
	<!--<?= $this->Html->script('input.js')?>-->
	<!-- <?= $this->Html->script('settings.js')?> -->
	<?= $this->Html->script('custom_fullscreen.js')?>
	<!--<?= $this->Html->script('jquery.nicescroll.min.js')?>-->
	<?= $this->Html->script('jquery.nicescroll.min-3.7.6.js')?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
	
	<!--<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/pagination/input.js"></script>-->  
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
		echo $this->element('left-sidebar');
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
		
		<?php /*Footer*/	?>
		<?= $this->element('footer') ?>			
		<?php /*End Footer*/	?>	
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
