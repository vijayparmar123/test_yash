<?php
//$this->extend('/Common/menu')
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#project_form').validationEngine();
	jQuery('.date_picker').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+10',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    }); 
} );
</script>	
<?php 
$project_id=isset($project_data['project_id'])?$project_data['project_id']:'';
$project_code=isset($project_data['project_code'])?$project_data['project_code']:'YNEC/P/';
$short_name=isset($project_data['short_name'])?$project_data['short_name']:'';
$project_name=isset($project_data['project_name'])?$project_data['project_name']:'';
$client_name=isset($project_data['client_name'])?$project_data['client_name']:'';
$project_address=isset($project_data['project_address'])?$project_data['project_address']:'';
$city=isset($project_data['city'])?$project_data['city']:'';
$district=isset($project_data['district'])?$project_data['district']:'';
$state=isset($project_data['state'])?$project_data['state']:'';
$pincode=isset($project_data['pincode'])?$project_data['pincode']:'';
$work_description=isset($project_data['work_description'])?$project_data['work_description']:'';
$contract_amount=isset($project_data['contract_amount'])?$project_data['contract_amount']:'';
$defect_liability_period=isset($project_data['defect_liability_period'])?$project_data['defect_liability_period']:'';
$project_director=isset($project_data['project_director'])?$project_data['project_director']:'';
$conttruction_manager=isset($project_data['conttruction_manager'])?$project_data['conttruction_manager']:'';
$attach_price_bid=isset($project_data['attach_price_bid'])?$project_data['attach_price_bid']:'';
$attach_specification=isset($project_data['attach_specification'])?$project_data['attach_specification']:'';
$attach_makelist=isset($project_data['attach_makelist'])?$project_data['attach_makelist']:'';
$attach_contract_document=isset($project_data['attach_contract_document'])?$project_data['attach_contract_document']:'';
$excess_amount=isset($project_data['excess_amount'])?$project_data['excess_amount']:'0';
$extra_item_amount=isset($project_data['extra_item_amount'])?$project_data['extra_item_amount']:'0';
$revise_amount=isset($project_data['revise_amount'])?$project_data['revise_amount']:'0';
$ref_letter_no=isset($project_data['ref_letter_no'])?$project_data['ref_letter_no']:'NA';
$actual_amount=isset($project_data['actual_amount'])?$project_data['actual_amount']:'0';
/* $actual_amount=isset($project_data['actual_amount'])?$project_data['actual_amount']:'0'; */

$contract_start_date =isset($project_data['contract_start_date'])?$this->ERPfunction->get_date($project_data['contract_start_date']):'';
$contract_end_date =isset($project_data['contract_end_date'])?$this->ERPfunction->get_date($project_data['contract_end_date']):'';
$date_of_information =isset($project_data['date_of_information'])?$this->ERPfunction->get_date($project_data['date_of_information']):'0';
$exten_cmp_date =isset($project_data['exten_cmp_date'])?$this->ERPfunction->get_date($project_data['exten_cmp_date']):'0';
$ref_date =isset($project_data['ref_date'])?$this->ERPfunction->get_date($project_data['ref_date']):'0';
$actual_cmp_date =isset($project_data['actual_cmp_date'])?$project_data['actual_cmp_date']:'0';
$actual_cmp_date =($actual_cmp_date != '0')?$this->ERPfunction->get_date($project_data['actual_cmp_date']):'0';
$created_by = isset($project_data['created_by'])?$this->ERPfunction->get_user_name($project_data['created_by']):'NA';
$last_edit = isset($project_data['last_edit'])?date("m-d-Y H:i:s",strtotime($project_data['last_edit'])):'NA';
$last_edit_by = isset($project_data['last_edit_by'])?$this->ERPfunction->get_user_name($project_data['last_edit_by']):'NA';

?>

<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		/* if(!empty($this->request->pass))
		{
			if($role == "projectdirector")
			{
				
			}
			else{
				$this->ERPfunction->access_deniedmsg();
			}
		}else{ */
			$this->ERPfunction->access_deniedmsg();
		/* } */
		
	}
else
{
?>			
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Projects','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
                    <div class="header">
                        <h2><u>Project Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'project_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
						
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>					
                    <div class="content controls">
						<div class="form-row">
							<div class="col-md-2">Project Code<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" id="project_code" name="project_code" value="<?php echo $project_code;?>" class="form-controlvalidate[required]" /></div>
                            <div class="col-md-2">Project Name<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="project_name" value="<?php echo $project_name;?>"
							class="form-control validate[required]" value=""/></div>                          
                        </div>
						<div class="form-row">
							<div class="col-md-2">Project Status<span class="require-field">*</span></div>
                            <div class="col-md-4">
								<select name="project_status" class="form-control">
									<option value="On Going">On Going</option>
									<option value="Physically Completed">Physically Completed</option>
									<option value="Fully Completed">Fully Completed</option>
								</select>
							</div>
							<div class="col-md-2">Short Name<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" id="short_name" name="short_name" value="<?php echo $short_name;?>" class="form-controlvalidate[required]" /></div>
                        </div>
						<div class="form-row">
							<div class="col-md-2">Client's Name<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" id="client_name" name="client_name" value="<?php echo $client_name;?>" class="form-control validate[required]" /></div>
                            <div class="col-md-2">Project Address <span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" name="project_address" value="<?php echo $project_address;?>" class="form-control validate[required]"/></div>                          
                        </div>
						<div class="header"><h2><u>Address</u></h2></div>
                        <div class="form-row">                            
                            <div class="col-md-2">City<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" name="city" value="<?php echo $city;?>" id = "city" 
							class="form-control validate[required]" /></div>
							<div class="col-md-2">District<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" name="district" value="<?php echo $district;?>" class="validate[required] form-control"/></div>
                        
                        </div>
						 <div class="form-row">
                            <div class="col-md-2">State<span class="require-field">*</span></div>
                            <div class="col-md-10">
								<!-- <input type="text" name="state" value="<?php echo $state;?>" class="form-control validate[required]"/> -->
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="state" class="bill_mode" value="Gujarat" class="form-control validate[required]"/> Gujarat</label>
                                </div>
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="state" value="Madhya Pradesh" class="bill_mode" class="form-control validate[required]"/>Madhya Pradesh</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="state" value="Maharashtra" class="bill_mode" class="form-control validate[required]"/>Maharashtra</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="state" value="Haryana" class="bill_mode" class="form-control validate[required]"/>Haryana</label>
                                </div>
							</div> 
                        
                            <div class="col-md-2">PIN Code <span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" name="pincode" value="<?php echo $pincode;?>" id = "pincode" class="form-control validate[required]"/></div>
                        </div>
						
						<div class="header"><h2><u>Other Info</u></h2></div>
						 <div class="form-row">						
                            <div class="col-md-2">Work Description<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" id="work_description" name="work_description" value="<?php echo $work_description;?>" class="form-control validate[required]"/></div>
							
                        </div>
						 <div class="form-row">						
                            <div class="col-md-2">Contract Start Date<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" id="contract_start_date" name="contract_start_date" value="<?php echo $contract_start_date;?>" class="form-control validate[required] date_picker"/></div>
							<div class="col-md-2">Contract End Date<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" id="contract_end_date" name="contract_end_date" value="<?php echo $contract_end_date;?>" class="form-control validate[required] date_picker"/></div>
							
                        </div>
						<div class="form-row">						
                            <div class="col-md-2">Contract Amount (Rs.)<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" id="contract_amount" name="contract_amount" value="<?php echo $contract_amount;?>" class="cal_amt form-control validate[required]"/></div>
							<div class="col-md-2">Defect Liability Period<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" id="defect_liability_period" name="defect_liability_period" value="<?php echo $defect_liability_period;?>" class="form-control validate[required]"/></div>
							
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Project Director<span class="require-field">*</span></div>
                            <div class="col-md-4">
							<input type="text"  name="project_director" value="<?php echo is_numeric($project_director)?$this->ERPfunction->get_user_name($project_director):$project_director;?>" class="form-control validate[required]"/>
								<!-- <select style="width: 100%;" class="select2"  name="project_director">
								 <option value="">--Select Project Director--</Option>
								 <?php 
									// foreach($project_manager as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['user_id'].'" '.$this->ERPfunction->selected($retrive_data['user_id'],$project_director).'>'.$this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									// }
								// ?>
								 </select> -->
							</div>   
							<div class="col-md-2">Construction Manager<span class="require-field">*</span></div>
                            <div class="col-md-4">
							<input type="text"  name="conttruction_manager" value="<?php echo is_numeric($contract_amount)?$this->ERPfunction->get_user_name($conttruction_manager):$conttruction_manager;?>" class="form-control validate[required]"/>
								<!--<select style="width: 100%;" class="select2"  name="conttruction_manager">
								<option value="">--Select Construction Manager --</Option>
								<?php 
									//foreach($constructionmanager as $retrive_data)
									//{
									//	echo '<option value="'.$retrive_data['user_id'].'" '.$this->ERPfunction->selected($retrive_data['user_id'],$conttruction_manager).'>'.$this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									//}
								?>
								</select>-->
							</div>      
                        </div>						
						<div class="form-row">
                            <div class="col-md-2">As On Date for Following Information</div>
                            <div class="col-md-4"><input type="text" name="date_of_information" 
							value="<?php echo $date_of_information;?>" class="form-control date_picker"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Net Excess / Saving Amount (Rs.)*</div>
                            <div class="col-md-4"><input type="text" name="excess_amount" id="excess_amount" 
							value="<?php echo $excess_amount;?>" class="cal_amt form-control validate[required]"/></div>
                        
                            <div class="col-md-2">Extra Item Amount (Rs.)*</div>
                            <div class="col-md-4"><input type="text" id="extra_item_amount" name="extra_item_amount" 
							value="<?php echo $extra_item_amount;?>" class="cal_amt form-control validate[required]"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Revised Amount (Rs.)*</div>
                            <div class="col-md-4"><input type="text" name="revise_amount" id="revise_amount" 
							value="<?php echo $revise_amount;?>" class="form-control validate[required]"/></div>
                        
                            <div class="col-md-2">Extended Completion Date*</div>
                            <div class="col-md-4"><input type="text" id="exten_cmp_date" 
							name="exten_cmp_date" value="<?php echo $exten_cmp_date;?>" class="form-control date_picker validate[required]"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Ref. Letter No</div>
                            <div class="col-md-4"><input type="text" name="ref_letter_no" 
							value="<?php echo $ref_letter_no;?>" class="form-control"/></div>
                        
                            <div class="col-md-2">Ref. Date</div>
                            <div class="col-md-4"><input type="text" id="ref_date" name="ref_date" 
							value="<?php echo $ref_date;?>" class="form-control date_picker"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Actual Amount (Rs.)</div>
                            <div class="col-md-4"><input type="text" name="actual_amount" 
							value="<?php echo $actual_amount;?>" class="form-control"/></div>
                        
                            <div class="col-md-2">Actual Completion Date*</div>
                            <div class="col-md-4"><input type="text" id="actual_cmp_date" name="actual_cmp_date" value="<?php echo $actual_cmp_date;?>" class="form-control date_picker validate[required]"/></div>
                        </div>
							
						<div class="header"><h2><u>Project Attachment</u></h2></div>
						<div class="form-row">							
                            <div class="col-md-2"> Attach Documents</div>
                            <div class="col-md-4">
								<input class="add_label form-control" placeholder="Type document name here">
							</div>
							<div class="col-md-1">
								<a href="javascript:void(0)" class="create_field form-control">+&nbsp;Add</a>
							</div>
						</div>
						<div class="form-row add_field">
						<?php 
						if($user_action == "edit")
						{
						$attached_files = json_decode($project_data["attach_file"]);
						$attached_label = json_decode(stripcslashes($project_data['attach_label']));						
						if(!empty($attached_files))
						{							
							$i = 0;
							foreach($attached_files as $file)
							{?>
								<div class='del_parent'>
									<div class='form-row'>
										<div class='col-md-2'><?php echo $attached_label[$i];?><input type='hidden' name='attach_label[]' value='<?php echo $attached_label[$i];?>' class='form-control'></div>
										<div class='col-md-4'><a href="<?php echo $this->request->base;?>/img/users_images/<?php echo $file;?>" class="btn btn-primary" target="_blank">View File</a><input type='hidden' name='old_attach_file[]' value='<?php echo $file;?>' class='form-control'></div>
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
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary" onclick="return ValidateExtension()"><?php echo $button_text;?></button></div>
                        </div>
				
				<?php $this->Form->end(); ?>
				<?php 
				if($user_action == "edit")
				{ ?>
				<div class="row" style="font-style:italic;color:gray;">							
					<div class="col-md-6 pull-right">
						<div class="col-md-4">
							<?php echo "Created By:{$created_by}"; ?>
						</div>
						<div class="col-md-4">
							 <?php echo "Last Edited By:{$last_edit_by}"; ?>
						</div>
						<div class="col-md-4">						 
						  <a href="../printproject/<?php echo $project_id;?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
						</div> 
					</div>
				</div>
				<?php } ?>
			</div>
		  </div>
<?php } ?>			
         </div>
<script>
function ValidateExtension(){
		m=0;
		$('.imageUpload').each(function(){
			if($(this).val() != '') {
				var imageUpload=$(this).val();
				var allowedFiles = ["jpeg","jpg","png","pdf","csv"];
				
				var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
				if (!regex.test(imageUpload.toLowerCase())) {
					$(this).siblings('.notice').html("<?php echo $this->request->session()->read('image_validation'); ?>");
					m++;
				}
				else{
					$(this).siblings('.notice').html(" ");
				}
			}
		});
			if(m>0){
			return false;
			}
        }
$(".create_field").click(function(){
	var label = $(".add_label").val();
	if(label == "")
	{
		alert("Please enter document name");
		return false;
	}
	$(".add_label").val("");
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='attach_file[]' class='imageUpload'><span class='required red notice'></span></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
	$(".add_field").append(field);
});

$("body").on("click",".del_file",function(){
	$(this).parentsUntil('.del_parent').remove();
});

$("body").on("change",".cal_amt",function(){
	var amt1 = $("#excess_amount").val();
	var amt2 = $("#extra_item_amount").val();
	var amt3 = $("#contract_amount").val();
	var total = parseFloat(amt1) + parseFloat(amt2) + parseFloat(amt3);
	$("#revise_amount").val(total);
});
</script>