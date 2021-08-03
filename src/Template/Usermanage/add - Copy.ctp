<?php
//$this->extend('/Common/menu')
?>
<script type="text/javascript">
jQuery(document).ready(function() {
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
$assign_projects=isset($assign_project)?$assign_project:array();
$role=isset($user_data['role'])?$user_data['role']:'';

?>

<div class="col-md-10" >
				
                <div class="block block-fill-white">					
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Usermanage','index');?>" class="btn btn-success"><span class="icon-arrow-left"> </span> Back</a>
						</div>
					</div>
					
					
                    <div class="header">
                        <h2><u>Personal Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_identy_id" class="form-control" value="<?php echo $user_identy_id;?>"/>	
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
				
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">First name<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="first_name" value="<?php echo $first_name;?>"
							class="form-control validate[required]" value=""/></div>
                        
                            <div class="col-md-2">Middle Name:</div>
                            <div class="col-md-4"><input type="text" name="middle_name" value="<?php echo $middle_name;?>" class="form-control" value=""/></div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Last name<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="last_name" value="<?php echo $last_name;?>" class="form-control validate[required]" value=""/></div>
                        
                            <div class="col-md-2">Date of Birth:</div>
                            <div class="col-md-4"><input type="text" name="date_of_birth" value="<?php echo $date_of_birth;?>" id = "date_of_birth" class="form-control" value=""/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Gender :</div>
                            <div class="col-md-4">
                                <div class="radiobox-inline">									
                                    <label><input type="radio" name="gender" value="Male" <?php echo $this->ERPfunction->checked($gender,'Male');?>/> Male</label>
                                </div>
                                <div class="radiobox-inline">
                                    <label><input type="radio" name="gender" value="Female" <?php echo $this->ERPfunction->checked($gender,'Female');?>/> Female</label>
                                </div>                                                              
                            </div>
                        </div>
						<div class="form-row">						
                            <div class="col-md-2">Designation:</div>
                            <div class="col-md-4">
							
								<select class="select2" required="true"  style="width: 100%;" name="role">
									<option value="">--Select Project--</Option>
									<?php 
										foreach($designations as $retrive_data)
										{
											echo '<option value="'.$retrive_data['role'].'" 
											'.$this->ERPfunction->selected($retrive_data['role'],$role).'>'.
											$retrive_data['title'].'</option>';
										}
									?>
								</select>
							</div>
							
                        </div>
						 <div class="form-row">						
                            <div class="col-md-2">Degree:</div>
                            <div class="col-md-4"><input type="text" name="degree" value="<?php echo $degree;?>" class="form-control" value=""/></div>
							<div class="col-md-2">Year Of Pasing:</div>
                            <div class="col-md-4"><input type="text" name="year_of_passing" value="<?php echo $year_of_passing;?>" class="form-control" value=""/></div>
							
                        </div>
						 <div class="form-row">
							
                            <div class="col-md-2">Experience:</div>
                            <div class="col-md-4"><input type="text" name="experience" value="<?php echo $experience;?>" class="form-control" value=""/></div>
							<div class="col-md-2">As On Date:</div>
                            <div class="col-md-4"><input type="text" name="as_on_date" id="as_on_date" value="<?php echo $as_on_date;?>" class="form-control" value=""/></div>
							
                        </div>
						
						
						<div class="header"><h2><u>Contact Information</u></h2></div>
						<div class="form-row">
                            <div class="col-md-2">Mobile no <span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="mobile_no" value="<?php echo $mobile_no;?>" class="form-control validate[required]" value=""/></div>
                        
                            <div class="col-md-2">Emergency no:</div>
                            <div class="col-md-4"><input type="text" name="emergency_no" value="<?php echo $emergency_no;?>" class="form-control" value=""/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Address 1:</div>
                            <div class="col-md-4"><input type="text" name="address_1" value="<?php echo $address_1;?>" class="form-control" value=""/></div>
                        
                            <div class="col-md-2">Address 2:</div>
                            <div class="col-md-4"><input type="text" name="address_2" value="<?php echo $address_2;?>" class="form-control" value=""/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">City:</div>
                            <div class="col-md-4"><input type="text" name="city" value="<?php echo $city;?>" class="form-control" value=""/></div>
                        
                            <div class="col-md-2">Postal Code:</div>
                            <div class="col-md-4"><input type="text" name="postal_code" value="<?php echo $postal_code;?>" class="form-control" value=""/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">State:</div>
                            <div class="col-md-4"><input type="text" name="state" value="<?php echo $state;?>" class="form-control" value=""/></div>
                        
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Email<span class="require-field">*</span>  :</div>
                            <div class="col-md-4"><input type="text" name="email_id" value="<?php echo $email_id;?>" class="form-control validate[required,custom[email]]" value=""/></div>
                        
                            <div class="col-md-2">Password<span class="require-field">*</span>:</div>
                            <div class="col-md-4"><input type="text" name="password" class="form-control validate[required]" value=""/></div>
                        </div>
						
						<div class="header"><h2><u>Other Information</u></h2></div>
						<div class="form-row">
                             <div class="col-md-2">PAN Card No:</div>
                            <div class="col-md-4"><input type="text" name="pancard_no" value="<?php echo $pancard_no;?>" class="form-control" value=""/></div>
                        
                            <div class="col-md-2">Blood Group:</div>
                            <div class="col-md-4">
								<select style="width: 100%;" class="select2" required="true"  name="blood_group">
								<option>--Select Blood Broup--</option>
								<option value="O+" <?php echo $this->ERPfunction->selected($blood_group,'O+');?>>O+</option>								
								<option value="O-" <?php echo $this->ERPfunction->selected($blood_group,'O-');?>>O-</option>
								<option value="A+" <?php echo $this->ERPfunction->selected($blood_group,'A+');?>>A+</option>
								<option value="A-" <?php echo $this->ERPfunction->selected($blood_group,'A-');?>>A-</option>
								<option value="B+" <?php echo $this->ERPfunction->selected($blood_group,'B+');?>>B+</option>
								<option value="B-" <?php echo $this->ERPfunction->selected($blood_group,'B-');?>>B-</option>
								<option value="AB+" <?php echo $this->ERPfunction->selected($blood_group,'AB+');?>>AB+</option>
								<option value="AB-" <?php echo $this->ERPfunction->selected($blood_group,'AB-');?>>AB-</option>
								</select>
							</div>
                         </div>
						 
						 <div class="form-row">
                            <div class="col-md-2">Image:</div>
                            <div class="col-md-4">
							<?php 
									echo $this->Html->image($this->ERPfunction->get_user_image($user_id),
				array('class'=>'userimage','height'=>'50px','width'=>'50px')); ?>
                                <div class="input-group file">                                    
                                    <input type="text" name="new_image_nmae" class="form-control">
                                    <input type="hidden" name="old_image" value="<?php echo $image_url;?>" class="form-control">
                                    
                                    <input type="file" name="image_url">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary">Browse</button>
                                    </span>
                                </div>                               
                            </div>
                        </div>
						<div class="header"><h2><u>Other Information</u></h2></div>
						<div class="form-row">
                            <div class="col-md-2">Assign Project:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  multiple="multiple" style="width: 100%;" name="assign_projects[]">
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
         </div>