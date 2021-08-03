<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("body").on("click", "#to-recover", function(event){
		alert('Please contact ERP head');
		return false;
	});
});
</script>
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register">
  <div class="login-box" style='margin-top:4% !important'>
    <div class="white-box">
     <!-- <form class="form-horizontal form-material" id="loginform" action="index.html"> -->
	  <?php echo $this->Form->Create('form1',['id'=>'loginform','class'=>'form_horizontal form-material',
	'method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'login']]);?>
		<?php 
		$user_id = $this->request->session()->read('user_id');
		
		if(isset($flag) && $flag ==0)
		{?>
			<div class="alert alert-danger">
				<b>Fail!</b> Please Enter Correct Email Id Or Password!
				<button data-dismiss="alert" class="close" type="button">×</button>
			</div>
		<?php
		}
		?>		
       <h3 class="box-title m-b-20">
	   
	   <div class="user" style="text-align:center;">
			<div class="">                                                                                
      <img src="<?php echo $this->request->base .'/img/logo/ERP_Banner.png'; ?>" class="" />
			  <!-- <div class="user-change-button">
					<span class="icon-off"></span>
				</div> -->
			</div>                            
        </div>
	   </h3>
        <div class="form-group ">
          <div class="col-xs-12">
            <input class="form-control" name="username" type="text" required="true" placeholder="Username">
          </div>
        </div>
		<div class="form-group">
          <div class="col-xs-12">
            <input class="form-control" name="old_password" type="password" required="true" placeholder="Old Password">
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12">
            <input class="form-control" name="new_password" type="password" required="true" placeholder="New Password">
          </div>
        </div>
		<div class="form-group">
          <div class="col-xs-12">
            <input class="form-control" name="confirm_password" type="password" required="true" placeholder="Confirm Pssword">
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
           <!-- <div class="checkbox checkbox-primary pull-left p-t-0">
              <input id="checkbox-signup" type="checkbox">
              <label for="checkbox-signup"> Remember me </label>
            </div> -->
            <!-- <a href="<?php //echo $this->ERPfunction->action_link('Forgotpassword');?>" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div> -->
			<!-- <p class="message"><a href="<?php echo $this->request->base;?>/users/resetPassword/"><?php echo __("Forgot Password");?></a></p> -->

        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">submit</button>
          </div>
        </div>       
      </form>
      <form class="form-horizontal" id="recoverform" action="index.html">
        <div class="form-group ">
          <div class="col-xs-12">
            <h3>Recover Password</h3>
            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
          </div>
        </div>
        <div class="form-group ">
          <div class="col-xs-12">
            <input class="form-control" type="text" required="" placeholder="Email">
          </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
          </div>
        </div>
		<?php $this->Form->end(); ?>
      <!-- </form> -->
    </div>
  </div>
  <!-- <span class="cst-footer">Copyright &COPY; Yashnand 2016. All rights reserved.</span> -->
</section>