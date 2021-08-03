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
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

  

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
	<?= $this->Html->css('bootstrap/bootstrap.min.css') ?>
	<?= $this->Html->css('login/font-awesome.min.css') ?>
	
	<?= $this->Html->css('login/animate.css') ?>
	<?= $this->Html->css('login/mediacss.css') ?>
	<?= $this->Html->css('login/style.css') ?>
	<?= $this->Html->css('login/default.css') ?>
	<?= $this->Html->script('plugins/jquery/jquery-3.6.0.min.js')?>
	<?= $this->Html->script('plugins/bootstrap/bootstrap.min.js')?>

	<?= $this->Html->script('login/sidebar-nav.min.js')?>
	<?= $this->Html->script('login/jquery.slimscroll.js')?>
	<?= $this->Html->script('login/waves.js')?>

	<?= $this->Html->script('login/custom.min.js')?>
	<?= $this->Html->script('login/jQuery.style.switcher.js')?>

	
	<?= $this->fetch('script') ?>
	<style>
	.login-register{
		background:url(<?php echo $this->request->base .'/img/login_bg.jpg'; ?>) !important;
				background-size: cover !important;
	}
	
	.cst-footer{
		    color: #fff;
			width:100%;
			float:left;
			text-align:center;
			bottom: 0;
						
	}
	</style>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
    
    <?= $this->Flash->render() ?>
   
        <?= $this->fetch('content') ?>
    
			
</body>
</html>
