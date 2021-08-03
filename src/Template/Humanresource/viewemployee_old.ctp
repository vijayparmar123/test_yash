.<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#date_of_birth,#as_on_date,#date_of_joining').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
    }); 
	jQuery('.viewmodal').click(function(){
			
			payid=jQuery(this).attr('id');
			jQuery('#modal-view').html('hello');
			 var model  = jQuery(this).attr('data-type') ;
		//alert(model);
		//return false;
	   var curr_data = {type : model};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'categorylist'));?>",
                data:curr_data,
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
		
	 jQuery("body").on("click", "#btn-add-category", function(){		
		var category_name  = jQuery('#category_name').val() ;
		var model  = jQuery(this).attr('model');	
		/* alert(category_name + ' ' + model);
		return false; */
		if(category_name != "")
		{
			var curr_data = {					
					model : model,
					category_name: category_name				
					};
					
					jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addcategory'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					/* //alert(category_name + ' ' + model + ' ' + response);
		//return false; */
                     var json_obj = jQuery.parseJSON(response);					
						jQuery('.table').append(json_obj[0]);
						jQuery('#category_name').val("");						
						jQuery("#"+model).append(json_obj[1]);	
						jQuery('.select2').select2();
						return false;		
                },
                error: function (tab) {
                    alert('error');
                }
            });	
		}
		else
		{
			alert("Please enter Category Name.");
		}
	});
	
	jQuery("body").on("click", ".btn-delete-cat", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var cat_id  = jQuery(this).attr('id') ;
	  var model  = jQuery(this).attr('model') ;

	if(confirm("Are you sure want to delete this record?"))
		{
	   var curr_data = {	 						 					
	 					cat_id : cat_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'removecategory'));?>",
                data:curr_data,
                async:false,
                success: function(response){
						jQuery("#"+model+" option[value='"+cat_id+"']").remove();
                    	jQuery('tr#cat-'+cat_id).hide();
						jQuery('.select2').select2();
							return true; 	
                },
                error: function (tab) {
                    alert('error');
                }
            });
		}
	
  });
} );
</script>

<?php 

$employee_id=isset($employee_data['employee_id'])?$employee_data['employee_id']:'';
$employee_no=isset($employee_data['employee_no'])?$employee_data['employee_no']:$employee_no;
$date_of_joining=isset($employee_data['date_of_joining'])?$this->ERPfunction->get_date($employee_data['date_of_joining']):'';
$employee_at=isset($employee_data['employee_at'])?$employee_data['employee_at']:'';
$first_name=isset($employee_data['first_name'])?$employee_data['first_name']:'';
$middle_name=isset($employee_data['middle_name'])?$employee_data['middle_name']:'';
$last_name=isset($employee_data['last_name'])?$employee_data['last_name']:'';
$date_of_birth=isset($employee_data['date_of_birth'])?$this->ERPfunction->get_date($employee_data['date_of_birth']):'';
$education=isset($employee_data['education'])?$employee_data['education']:'';
$year_of_passing=isset($employee_data['year_of_passing'])?$employee_data['year_of_passing']:'';
$gender=isset($employee_data['gender'])?$employee_data['gender']:'Male';
$experience=isset($employee_data['experience'])?$employee_data['experience']:'';
$as_on_date=isset($employee_data['as_on_date'])?$this->ERPfunction->get_date($employee_data['as_on_date']):'';
$pan_card_no=isset($employee_data['pan_card_no'])?$employee_data['pan_card_no']:'';
$driving_licence_no=isset($employee_data['driving_licence_no'])?$employee_data['driving_licence_no']:'';
$epf_no=isset($employee_data['epf_no'])?$employee_data['epf_no']:'';
$image_url=isset($employee_data['image_url'])?$employee_data['image_url']:'';
$mobile_no=isset($employee_data['mobile_no'])?$employee_data['mobile_no']:'';
$email_id=isset($employee_data['email_id'])?$employee_data['email_id']:'';
$name1=isset($employee_data['name1'])?$employee_data['name1']:'';
$relationship1=isset($employee_data['relationship1'])?$employee_data['relationship1']:'';
$contactno1=isset($employee_data['contactno1'])?$employee_data['contactno1']:'';
$name2=isset($employee_data['name2'])?$employee_data['name2']:'';
$relationship2=isset($employee_data['relationship2'])?$employee_data['relationship2']:'';
$contactno2=isset($employee_data['contactno2'])?$employee_data['contactno2']:'';
$blood_group=isset($employee_data['blood_group'])?$employee_data['blood_group']:'';
$payment=isset($employee_data['payment'])?$employee_data['payment']:'';
$designation=isset($employee_data['designation'])?$employee_data['designation']:'';
$basic_salary=isset($employee_data['basic_salary'])?$employee_data['basic_salary']:'';
$incentive=isset($employee_data['incentive'])?$employee_data['incentive']:'';
$total_salary=isset($employee_data['total_salary'])?$employee_data['total_salary']:'';
$ac_no=isset($employee_data['ac_no'])?$employee_data['ac_no']:'';
$bank=isset($employee_data['bank'])?$employee_data['bank']:'';
$ifsc_code=isset($employee_data['ifsc_code'])?$employee_data['ifsc_code']:'';
$extra_payment=isset($employee_data['extra_payment'])?explode(',',$employee_data['extra_payment']):array();
$incentive_includes=isset($employee_data['incentive_includes'])?explode(',',$employee_data['incentive_includes']):array();



?>

<div class="col-md-10" >
				
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $_SERVER["HTTP_REFERER"];?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
                    
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>" disabled />	
					
                   
				  
					<div class="content controls">
						
						<div class="form-row">
                            <div class="col-md-2">Employee No<span class="require-field">*</span> :</div>
                            <div class="col-md-2"><input type="text" name="employee_no" value="<?php echo $employee_no;?>"
							class="form-control validate[required]" readonly="true" disabled /></div>
                        
                            <div class="col-md-2">Date of Joining:</div>
                            <div class="col-md-2"><input type="text" name="date_of_joining" id="date_of_joining" value="<?php echo $date_of_joining;?>" class="form-control"  disabled /></div>
							
							 <div class="col-md-2">Employee At:</div>
                            <div class="col-md-2">
							
							<select class="select2" required="true"   style="width: 100%;" name="employee_at" disabled >
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" 
										'.$this->ERPfunction->selected($employee_at,$retrive_data['project_id']).' >'.
										$retrive_data['project_code'].' '.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
							</div>
                        
						
						</div>
						 <div class="header">
							<h2><u>Personal Information</u></h2>
						</div>
						<div class="form-row">
                            <div class="col-md-2">First Name<span class="require-field">*</span> :</div>
                            <div class="col-md-2"><input type="text" name="first_name" value="<?php echo $first_name;?>"
							class="form-control validate[required]"  disabled /></div>
                        
                            <div class="col-md-2">Middle Name:</div>
                            <div class="col-md-2"><input type="text" name="middle_name" value="<?php echo $middle_name;?>" class="form-control"  disabled /></div>
							
							<div class="col-md-2">Last name<span class="require-field">*</span> :</div>
                            <div class="col-md-2"><input type="text" name="last_name" value="<?php echo $last_name;?>" class="form-control validate[required]"  disabled /></div>
                        
						
					   </div>
					   <div class="form-row">
                            <div class="col-md-2">Date Of Birth<span class="require-field">*</span> :</div>
                            <div class="col-md-2"><input type="text" name="date_of_birth" id="date_of_birth" value="<?php echo $date_of_birth;?>"
							class="form-control validate[required]"  disabled /></div>
                        
                            <div class="col-md-2">Education</div>
                            <div class="col-md-2"><input type="text" name="education" value="<?php echo $education;?>" class="form-control"  disabled /></div>
							
							<div class="col-md-2">Year Of Passing<span class="require-field">*</span> :</div>
                            <div class="col-md-2"><input type="text" name="year_of_passing" value="<?php echo $year_of_passing;?>" class="form-control validate[required]"  disabled /></div>
                        
						
					   </div>
					   <div class="form-row">
                             <div class="col-md-2">Gender :</div>
                            <div class="col-md-2">
                                <div class="radiobox-inline">
                                    <label><input type="radio" name="gender" value="Male" <?php echo $this->ERPfunction->checked($gender,'Male');?> disabled /> Male</label>
                                </div>
                                <div class="radiobox-inline">
                                    <label><input type="radio" name="gender" value="Female" <?php echo $this->ERPfunction->checked($gender,'Female');?> disabled /> Female</label>
                                </div>                                                              
                            </div>
							<div class="col-md-2">Experience</div>
                            <div class="col-md-2"><input type="text" name="experience" value="<?php echo $experience;?>" class="form-control"  disabled /></div>
							
							<div class="col-md-2">As on Date</div>
                            <div class="col-md-2"><input type="text" name="as_on_date" id="as_on_date" value="<?php echo $as_on_date;?>" class="form-control "  disabled /></div>
                        						
					   </div>
					   <div class="form-row">
                             <div class="col-md-2">PAN Card No</div>
                            <div class="col-md-2"> 
								<input type="text" name="pan_card_no" value="<?php echo $pan_card_no;?>" class="form-control validate[required]"  disabled />
                            </div>
							<div class="col-md-2">Driving Lincence No</div>
                            <div class="col-md-2"><input type="text" name="driving_licence_no" value="<?php echo $driving_licence_no;?>" class="form-control"  disabled /></div>
							
							<div class="col-md-2">EPF No</div>
                            <div class="col-md-2"><input type="text" name="epf_no" value="<?php echo $epf_no;?>" class="form-control"  disabled /></div>
                        						
					   </div>						
						 <!--<div class="form-row">
                            <div class="col-md-2">Image:</div>
                            <div class="col-md-4">
							<?php 
									// echo $this->Html->image($this->ERPfunction->get_employee_image($employee_id),
				// array('class'=>'userimage','height'=>'50px','width'=>'50px')); ?>
                                                             
                            </div>
                        </div> -->
						<div class="header"><h2><u>Contact Information</u></h2></div>
						<div class="form-row">
                            <div class="col-md-2">Mobile no <span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="mobile_no" value="<?php echo $mobile_no;?>" class="form-control validate[required]"  disabled /></div>
                        
                            <div class="col-md-2">E-mail ID</div>
                            <div class="col-md-4"><input type="text" name="email_id" value="<?php echo $email_id;?>" class="form-control"  disabled /></div>
                        </div>
						<div class="form-row">
							<div class="col-md-12"><P>In Case of Emergency Please Contact</P></div>
                            <div class="col-md-2">1) Name</div>
                            <div class="col-md-2"> 
								<input type="text" name="name1" value="<?php echo $name1;?>" class="form-control validate[required]"  disabled />
                            </div>
							<div class="col-md-2">Relationship</div>
                            <div class="col-md-2"><input type="text" name="relationship1" value="<?php echo $relationship1;?>" class="form-control"  disabled /></div>
							
							<div class="col-md-2">Contact No</div>
                            <div class="col-md-2"><input type="text" name="contactno1" value="<?php echo $contactno1;?>" class="form-control"  disabled /></div>
                        		
                        </div>
						<div class="form-row">
					
                            <div class="col-md-2">2) Name</div>
                            <div class="col-md-2"> 
								<input type="text" name="name2" value="<?php echo $name2;?>" class="form-control validate[required]"  disabled />
                            </div>
							<div class="col-md-2">Relationship</div>
                            <div class="col-md-2"><input type="text" name="relationship2" value="<?php echo $relationship2;?>" class="form-control"  disabled /></div>
							
							<div class="col-md-2">Contact No</div>
                            <div class="col-md-2"><input type="text" name="contactno2" value="<?php echo $contactno2;?>" class="form-control"  disabled /></div>
                        		
                        </div>
						<div class="form-row">
						<div class="col-md-2">Blood Group:</div>
                            <div class="col-md-4">
								<select style="width: 100%;" class="select2" required="true"  name="blood_group" disabled >
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
						 <div class="header">
							<h2><u>PAYMENT DETAILS</u></h2>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Payment<span class="require-field">*</span> :</div>
                            <div class="col-md-2">
							<select style="width: 100%;" class="" name="payment" disabled >
								<option>--Select Payment--</option>
								<option value="Cash" <?php echo $this->ERPfunction->selected($payment,'Cash');?>>Cash</option>								
								<option value="Cheque" <?php echo $this->ERPfunction->selected($payment,'Cheque');?>>Cheque</option>							
							</select>							
							</div>
                        
                            <div class="col-md-2">Designation</div>
                            <div class="col-md-2">
							 <select class="validate[required]" style="width: 100%;"id="designation" name="designation" disabled >
									<option value=""><?php echo __('--Designation--'); ?></option>
									<?php
                                    if(isset($designationlist)){
                                        foreach($designationlist as $unit_info){
                                        ?>
                                   <option value="<?php echo $unit_info['cat_id'];?>" <?php                                            
                                                if($designation == $unit_info['cat_id']){
                                                    echo 'selected="selected"';
                                                }else{
                                                    echo '';
                                                }
                                            
                                        
                                        ?> ><?php echo $unit_info['category_title'];?></option>
                                            <?php             
                                        }
                                    }
                                   ?>
								</select>
							</div>							
							
					   </div>
					   <div class="form-row">
                            <div class="col-md-2">Basic Salary<span class="require-field">*</span> :</div>
                            <div class="col-md-2"><input type="text" name="basic_salary" value="<?php echo $basic_salary;?>"
							class="form-control validate[required]"  disabled /></div>
                        
                            <div class="col-md-2">Incentives (Including All)</div>
                            <div class="col-md-2"><input type="text" name="incentive" value="<?php echo $incentive;?>" class="form-control"  disabled /></div>
							 <div class="col-md-2">Total Salary</div>
                            <div class="col-md-2"><input type="text" name="total_salary" value="<?php echo $total_salary;?>" class="form-control"  disabled /></div>
							
					   </div>
					    <div class="form-row">
                            <div class="col-md-2">A/C No<span class="require-field">*</span> :</div>
                            <div class="col-md-2"><input type="text" name="ac_no" value="<?php echo $ac_no;?>"
							class="form-control validate[required]"  disabled /></div>
                        
                            <div class="col-md-2">Bank</div>
                            <div class="col-md-2"><input type="text" name="bank" value="<?php echo $bank;?>" class="form-control"  disabled /></div>
							 <div class="col-md-2">IFSC Code</div>
                            <div class="col-md-2"><input type="text" name="ifsc_code" value="<?php echo $ifsc_code;?>" class="form-control"  disabled /></div>
							
					   </div>					   
					  
						<div class="form-row add_field">
						<?php 
						if($user_action == "edit")
						{
						$attached_files = json_decode($employee_data["attachment"]);
						$attached_label = json_decode(stripcslashes($employee_data['attach_label']));						
						if(!empty($attached_files))
						{							
							$i = 0;
							foreach($attached_files as $file)
							{?>
								<div class='del_parent'>
									<div class='form-row'>
										<div class='col-md-2'>
											<?php echo $attached_label[$i];?>
											<input type='hidden' name='attach_label[]' value='<?php echo $attached_label[$i];?>' class='form-control'>
										</div>
										<div class='col-md-4'><a href="<?php echo $this->request->base;?>/img/users_images/<?php echo $file;?>" class="btn btn-primary" target="_blank">View File</a>
										<input type='hidden' name='old_image_url[]' value='<?php echo $file;?>' class='form-control'></div>
										<div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div>
									</div>
								</div>							
							<?php $i++;
							}
						}
						}
						?>
						</div>
						
						
						
						
						
						<div class="form-row">
                            <div class="col-md-6">
							<div  class="col-md-12"><p>Extra Payment For Follwing (Not Included in above Salary)</p></div>
							<div  class="col-md-12">
							
								 <div class="checkbox">
                                    <label><input type="checkbox" name="extra_payment[]" value="transportation" <?php echo $this->ERPfunction->multichecked('transportation',$extra_payment)?> disabled /> Transportation Expense</label>
                                </div>  
								 <div class="checkbox">
                                    <label><input type="checkbox" name="extra_payment[]" value="accomodation" <?php echo $this->ERPfunction->multichecked('accomodation',$extra_payment)?> disabled />Accomodation</label>
                                </div>  
								 <div class="checkbox">
                                    <label><input type="checkbox" name="extra_payment[]" value="food" <?php echo $this->ERPfunction->multichecked('food',$extra_payment)?> disabled />Foot Expense</label>
                                </div>  
								 <div class="checkbox">
                                    <label><input type="checkbox" name="extra_payment[]" value="mobile_bills" <?php echo $this->ERPfunction->multichecked('mobile_bills',$extra_payment)?> disabled />Mobile Bills</label>
                                </div>  
								 <div class="checkbox">
                                    <label><input type="checkbox" name="extra_payment[]" value="perquisites" <?php echo $this->ERPfunction->multichecked('perquisites',$extra_payment)?> disabled />Perquisites</label>
                                </div> 								
							</div>
							
							</div>                        
                            <div class="col-md-6">
							<div  class="col-md-12"><p>Incentives Includes Follwing</p></div>
							<div  class="col-md-12">
								 <div class="checkbox">
                                    <label><input type="checkbox" name="incentive_includes[]" value="transportation" <?php echo $this->ERPfunction->multichecked('transportation',$incentive_includes)?> disabled /> Transportation Expense</label>
                                </div>  
								 <div class="checkbox">
                                    <label><input type="checkbox" name="incentive_includes[]" value="accomodationself" <?php echo $this->ERPfunction->multichecked('accomodationself',$incentive_includes)?> disabled />Accomodation - Self</label>
                                </div>  
								 <div class="checkbox">
                                    <label><input type="checkbox" name="incentive_includes[]" value="accomodationcmpnyprovide" <?php echo $this->ERPfunction->multichecked('accomodationcmpnyprovide',$incentive_includes)?> disabled />Accomodation - Company Provided</label>
                                </div>  
								 <div class="checkbox">
                                    <label><input type="checkbox" name="incentive_includes[]" value="food" <?php echo $this->ERPfunction->multichecked('food',$incentive_includes)?> disabled />Foot Expense</label>
                                </div>  
								 <div class="checkbox">
                                    <label><input type="checkbox" name="incentive_includes[]" value="mobile_bills" <?php echo $this->ERPfunction->multichecked('mobile_bills',$incentive_includes)?> disabled />Mobile Bills</label>
                                </div> 								 							
							</div>
							</div>
                           
					   </div>
				
				<?php $this->Form->end(); ?>
				<div class="row" style="font-style:italic;color:gray;">							
					<div class="col-md-7 pull-right">
						<br><br><br>
						<div class="col-md-4">
							<?php echo "Created By:{$this->ERPfunction->get_user_name($employee_data['creaded_by'])}"; ?>
						</div>
						<div class="col-md-4">
							 <?php echo "Last Edited By:{$this->ERPfunction->get_user_name($employee_data['last_edit_by'])}"; ?>
						</div>
						<div class="col-md-4">						 
						  <a href="../printemployee/<?php echo $employee_data["user_id"];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
						</div> 
					</div>
				</div> 
			</div>
				
				
			</div>
         </div>

<script>
$(".create_field").click(function(){
	var label = $(".add_label").val();
	$(".add_label").val("");
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='image_url[]'></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
	$(".add_field").append(field);
});

$("body").on("click",".del_file",function(){
	$(this).parentsUntil('.del_parent').remove();
});

</script>