<?php
//$this->extend('/Common/menu')
?>
<style>
body.bg-light-gray{
	background:url("img/login_bg.jpg") !important;
}
</style>
<div class="login-block">
            <div class="block block-transparent">
                <div class="head">
                    <div class="user" style="text-align:center;">
                        <div class="">                                                                                
                            <img src="webroot/img/logo/ERP_Banner.png" class="" />
                          <!-- <div class="user-change-button">
                                <span class="icon-off"></span>
                            </div> -->
                        </div>                            
                    </div>
                </div>
                <div class="content controls npt">
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize',
				'method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'login']]);?>
					<?php 
					$user_id = $this->request->session()->read('user_id');
					
					if(isset($flag) && $flag ==0)
					{
					?>
					<div class="alert alert-danger">
                        <b>Fail!</b> Please Enter Correct Email Id Or Password!
                        <button data-dismiss="alert" class="close" type="button">×</button>
                    </div>
					<?php
					}
					?>
                    
					 <div class="form-row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="icon-envelope"></span>
                                </div>
                                <input type="text" class="form-control" name="email_id" placeholder="Email Id"/>
                            </div>
                        </div>
                    </div>   
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="icon-key"></span>
                                </div>
                                <input type="password" class="form-control" name="password" placeholder="Password"/>
                            </div>
                        </div>
                    </div>                        
                    <div class="form-row">                       
                        <div class="col-md-6">
							<button type="submit" name="login" class="btn btn-default btn-block btn-clean"> Log In</button>                            
                        </div>
						<div class="col-md-6">
                            <a href="<?php echo $this->ERPfunction->action_link('Forgotpassword');?>" class="btn btn-link btn-block">Forgot your password?</a>
                        </div>
                    </div>                              
                </div>
				<?php $this->Form->end(); ?>
            </div>
        </div>
