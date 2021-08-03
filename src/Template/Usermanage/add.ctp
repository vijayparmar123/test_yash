<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#date_of_birth,#as_on_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
        }); 
} );
</script>	
<?php
$user_id=isset($user_data['user_id'])?$user_data['user_id']:'';
$user_identy_id=isset($user_data['user_identy_id'])?$user_data['user_identy_id']:$user_identy_id;
/* $first_name=isset($user_data['first_name'])?$user_data['first_name']:'';
$middle_name=isset($user_data['middle_name'])?$user_data['middle_name']:'';
$last_name=isset($user_data['last_name'])?$user_data['last_name']:'';
$date_of_birth=isset($user_data['date_of_birth'])?$this->ERPfunction->get_date($user_data['date_of_birth']):'';
$gender=isset($user_data['gender'])?$user_data['gender']:'Male';
$degree=isset($user_data['degree'])?$user_data['degree']:'';
$year_of_passing=isset($user_data['year_of_passing'])?$user_data['year_of_passing']:'';
$experience=isset($user_data['experience'])?$user_data['experience']:'';
$as_on_date=isset($user_data['as_on_date'])?$user_data['as_on_date']:'';
$mobile_no=isset($user_data['mobile_no'])?$user_data['mobile_no']:'';
$emergency_no=isset($user_data['emergency_no'])?$user_data['emergency_no']:'';
$address_1=isset($user_data['address_1'])?$user_data['address_1']:'';
$address_2=isset($user_data['address_2'])?$user_data['address_2']:'';
$city=isset($user_data['city'])?$user_data['city']:'';
$postal_code=isset($user_data['postal_code'])?$user_data['postal_code']:'';
$state=isset($user_data['state'])?$user_data['state']:''; */
$username=isset($user_data['username'])?$user_data['username']:'';
$email_id=isset($user_data['email_id'])?$user_data['email_id']:'';
$second_email=isset($user_data['second_email'])?$user_data['second_email']:'';
/* $pancard_no=isset($user_data['pancard_no'])?$user_data['pancard_no']:'';
$blood_group=isset($user_data['blood_group'])?$user_data['blood_group']:'';
$image_url=isset($user_data['image_url'])?$user_data['image_url']:''; */
$assign_projects=isset($assign_project)?$assign_project:array();
$role=isset($user_data['role'])?$user_data['role']:'';

?>

<div class="col-md-10" >	
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>	

			
                <div class="block block-fill-white">					
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Usermanage','index');?>" class="btn btn-success"><span class="icon-arrow-left"> </span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_identy_id" class="form-control" value="<?php echo $user_identy_id;?>"/>	
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
				
                    <div class="content controls">
						<div class="form-row">
							<div class="col-md-2">User Name<span class="require-field">*</span>  :</div>
                            <div class="col-md-4"><input type="text" name="username" value="<?php echo $username;?>" class="form-control validate[required]" value=""/></div>
						</div>
                        <div class="form-row">
							<div class="col-md-2">Email<span class="require-field">*</span>  :</div>
                            <div class="col-md-4"><input type="text" name="email_id" value="<?php echo $email_id;?>" class="form-control validate[required]" value=""/></div>
						</div>
						<div class="form-row">
							<div class="col-md-2">Secondary Email :</div>
                            <div class="col-md-4"><input type="text" name="second_email" value="<?php echo $second_email; ?>" class="form-control"/></div>
						</div>
						<div class="form-row">						
                            <div class="col-md-2">Designation:<span class="require-field">*</span></div>
                            <div class="col-md-4">
							
								<select class="select2 addvalue" required="true"  style="width: 100%;" name="role">
									<option value="">--Select Designation--</Option>
									<?php 
										foreach($designations as $retrive_data)
										{
											echo '<option value="'.$retrive_data['value'].'" 
											'.$this->ERPfunction->selected($retrive_data['value'],$role).'>'.
											$retrive_data['title'].'</option>';
										}
									?>
								</select>
							</div>
							<?php 
						 if($role != 'hrmanager' && $role != 'erpoperator' && $role != 'erpmanager')
						 {
						?>
							<div class="col-md-1">
								<button type="button" id="designation" data-type="designation" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal">Add More </button>							
							</div>
						 <?php } ?>
                        </div>
						<?php 
						if($user_action == "insert")
						{?>					
						
						<div class="form-row">
                            <div class="col-md-2">Create Password<span class="require-field">*</span>:</div>
                            <div class="col-md-4"><input type="password" name="create_password" id="create_password" class="form-control validate[required]" value=""/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Confirm Password<span class="require-field">*</span>:</div>
                            <div class="col-md-4"><input type="password" name="password" id="con_pass" class="form-control validate[required,equals[create_password]]" value=""/></div>
                        </div>
						<?php }
						else{ ?>
						<div class="form-row">
                            <div class="col-md-2">Create Password:</div>
                            <div class="col-md-4"><input type="password" name="password" id="" class="form-control" value=""/></div>
                        </div>
						<?php
						}
						?>
						<div class="form-row">
                            <div class="col-md-2">Assign Project:</div>
                            <div class="col-md-4">
								<select class="select2" multiple="multiple" style="width: 100%;" name="assign_projects[]">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" 
										'.$this->ERPfunction->multiselected($retrive_data['project_id'],$assign_projects).'>'.
										$retrive_data['project_code'].' '.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
							</div>
                        
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-success"><?php echo $button_text;?></button></div>
                        </div>
				</div>
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>
		 
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>	
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;		 
 jQuery('.viewmodal').click(function(){
			
			payid=jQuery(this).attr('id');
			jQuery('#modal-view').html('hello');
			 var model  = jQuery(this).attr('data-type') ;
		//alert(model);
		//return false;
	  // var curr_data = {type : model};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'userlist'));?>",
                async:false,
                success: function(response){                    
					jQuery('.modal-content').html(response);
					jQuery('.select2').select2();
                },
                beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
		        error: function(e) {
		                console.log(e);
		             }
            });			
	});
	
	jQuery("body").on("click", ".btn-edit-cat", function(){	
	
		 var cat_id  = jQuery(this).attr('id') ;
		 jQuery('#cat-update-'+cat_id).removeAttr('style');
		 jQuery('#cat-'+cat_id).css('display','none');
	});
	jQuery("body").on("click", ".btn-cat-update-cancel", function(){	
	
		 var cat_id  = jQuery(this).attr('id') ;
		 jQuery('#cat-update-'+cat_id).css('display','none');
		 jQuery('#cat-'+cat_id).removeAttr('style');
		
	});
	
	jQuery("body").on("click", "#btn-add-category", function(){		
		var des_name  = jQuery('#des_name').val() ;
		
		
		if(des_name != "" )
		{
			
			if(des_name.match(/^[a-zA-Z\s.'&-]+$/)!=null){
				var curr_data = {
						des_name: des_name,						
						};
						
						jQuery.ajax({
						headers: {
							'X-CSRF-Token': csrfToken
						},
                type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'add_designation'));?>",
					data:curr_data,
					async:false,
					success: function(response){
						
						var json_obj = jQuery.parseJSON(response);
						
						if(json_obj!=null){
						 var json_obj = jQuery.parseJSON(response);					
							jQuery('.table').append(json_obj[0]);
							jQuery('select.addvalue').append(json_obj[1]);
							jQuery('#des_name').val("");						
							return false;		
						}
						else{
							alert('Opps! Enter Diffirent Title.');
						}
					},
					error: function (tab) {
						alert('error');
					}
				});		
			}
			else{
				alert("Only Enter Charachter Accept.");
			}
		}
		else
		{
			alert("Please fill all field.");
		}
	});
	
	jQuery("body").on("click", ".btn-cat-update", function(){		
		
		var id  = jQuery(this).attr('id') ;
		var des_name  = jQuery('#category_'+id).val() ;
		
		if(des_name != "" )
		{
			if(des_name.match(/^[a-zA-Z\s.'&-]+$/)!=null){
			var curr_data = {
					id: id,						
					des_name: des_name,						
					};
					
					jQuery.ajax({
					headers: {
							'X-CSRF-Token': csrfToken
						},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'update_designation'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					
					if(response!=''){
                     	var json_obj = jQuery.parseJSON(response);		
						jQuery('tr#cat-'+id).html(json_obj[0]);			
						//jQuery('tr#cat-update-'+id).html(response);	
						$('select.addvalue option[value="' + json_obj[1] + '"]').text(des_name);
						jQuery('#cat-update-'+id).css('display','none');
						jQuery('#cat-'+id).removeAttr('style');
						return false;		
					}
					else{
						alert('Opps! Enter Diffirent Title.');
					}
                },
                error: function (tab) {
                    alert('error');
                }
            });
			}			
			else{
				alert("Only Enter Charachter Accept.");
			}
		}
		else
		{
			alert("Please fill all field.");
		}
	});
	</script>