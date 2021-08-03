<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
jQuery("body").on("change", "#project_id", function(event){ 
	 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('#prno').val(json_obj['prno']);						
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	//jQuery('#user_form').validationEngine();
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
$first_name=isset($user_data['first_name'])?$user_data['first_name']:'';
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
$state=isset($user_data['state'])?$user_data['state']:'';
$email_id=isset($user_data['email_id'])?$user_data['email_id']:'';
$pancard_no=isset($user_data['pancard_no'])?$user_data['pancard_no']:'';
$blood_group=isset($user_data['blood_group'])?$user_data['blood_group']:'';
$image_url=isset($user_data['image_url'])?$user_data['image_url']:'';
$role=isset($user_data['role'])?$user_data['role']:'ceo';
?>

<div class="col-md-10" >
				
                <div class="block block-fill-white">
					<div class="header">
						<h1><?php echo $form_header;?> 
						<a href="<?php echo $this->ERPfunction->action_link('Ceo','index');?>" class="btn btn-default">Back</a></h1>
						
					</div>
					
                    <div class="header">
                        <h2><u>Personal Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_identy_id" class="form-control" value="<?php echo $user_identy_id;?>"/>	
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					<input type="hidden" name="role" class="form-control" value="<?php echo $role;?>"/>	
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_code'].' '.$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Their Ref. No<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="last_name" value="<?php echo $last_name;?>" class="form-control validate[required]" /></div>
                        
                            <div class="col-md-2">Their Date</div>
                            <div class="col-md-4"><input type="text" name="date_of_birth" value="<?php echo $date_of_birth;?>" id = "date_of_birth" class="form-control"/></div>
                        </div>						
						
						 <div class="form-row">						
                            <div class="col-md-2">Agency Name :</div>
                            <div class="col-md-4"><input type="text" name="date_of_birth" value="<?php echo $date_of_birth;?>" id = "date_of_birth" class="form-control" /></div>
							<div class="col-md-2">Agency</div>
                            <div class="col-md-4">
								<select name="" class="select2" required="true"  style="width:100%;">
									<option value="Client">Client</option>
									<option value="PMC/TPI">PMC/TPI</option>
									<option value="Testing Laboratory">Testing Laboratory</option>
									<option value="Sub-Contractor">Sub-Contractor</option>
									<option value="Supplier">Supplier</option>
									<option value="Others">Others</option>
								</select>
							</div>
							
                        </div>
						 <div class="form-row">
							
                            <div class="col-md-2">Written By</div>
                            <div class="col-md-4"><input type="text" name="experience" value="<?php echo $experience;?>" class="form-control"/></div>
							<div class="col-md-2">Designation</div>
                            <div class="col-md-4"><input type="text" name="as_on_date" id="as_on_date" value="<?php echo $as_on_date;?>" class="form-control"/></div>							
                        </div>	
						<div class="form-row">
                            <div class="col-md-2">Subject</div>
                            <div class="col-md-10"><input type="text" name="mobile_no" 
							value="<?php echo $mobile_no;?>" class="form-control"/></div>                        
                        </div>					
						<div class="form-row">
                            <div class="col-md-2">Enclosures</div>
                            <div class="col-md-10"><input type="text" name="mobile_no" 
							value="<?php echo $mobile_no;?>" class="form-control"/></div>                        
                        </div>						
						<div class="form-row">
                            <div class="col-md-2">Our Inward No</div>
                            <div class="col-md-4"><input type="text" name="address_1" value="<?php echo $address_1;?>" class="form-control"/></div>
                        
                            <div class="col-md-2">Inward Date</div>
                            <div class="col-md-4"><input type="text" name="address_2" value="<?php echo $address_2;?>" class="form-control"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Comment Box</div>
                            <div class="col-md-4">
							<textarea name="" class="form-control"></textarea>
							</div>
                        
                            <div class="col-md-2">Attach Document</div>
                            <div class="col-md-4">
							<input type="file" name="postal_code" class="form-control"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
				</div>
				<?php $this->Form->end(); ?>
			</div>
         </div>