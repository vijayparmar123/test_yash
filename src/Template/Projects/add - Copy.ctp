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
$excess_amount=isset($project_data['excess_amount'])?$project_data['excess_amount']:'';
$extra_item_amount=isset($project_data['extra_item_amount'])?$project_data['extra_item_amount']:'';
$revise_amount=isset($project_data['revise_amount'])?$project_data['revise_amount']:'';
$ref_letter_no=isset($project_data['ref_letter_no'])?$project_data['ref_letter_no']:'';
$actual_amount=isset($project_data['actual_amount'])?$project_data['actual_amount']:'';
$actual_amount=isset($project_data['actual_amount'])?$project_data['actual_amount']:'';

$contract_start_date =isset($project_data['contract_start_date'])?$this->ERPfunction->get_date($project_data['contract_start_date']):'';
$contract_end_date =isset($project_data['contract_end_date'])?$this->ERPfunction->get_date($project_data['contract_end_date']):'';
$date_of_information =isset($project_data['date_of_information'])?$this->ERPfunction->get_date($project_data['date_of_information']):'';
$exten_cmp_date =isset($project_data['exten_cmp_date'])?$this->ERPfunction->get_date($project_data['exten_cmp_date']):'';
$ref_date =isset($project_data['ref_date'])?$this->ERPfunction->get_date($project_data['ref_date']):'';
$actual_cmp_date =isset($project_data['actual_cmp_date'])?$this->ERPfunction->get_date($project_data['actual_cmp_date']):'';

?>

<div class="col-md-10" >
				
                <div class="block block-fill-white">
					<div class="header">
						<h1><?php echo $form_header;?> 
						<a href="<?php echo $this->ERPfunction->action_link('Projects','index');?>" class="btn btn-default">Back</a></h1>
						
					</div>
					
                    <div class="header">
                        <h2><u>Project Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'project_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
						
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>					
                    <div class="content controls">
						<div class="form-row">
							<div class="col-md-2">Project Code<span class="require-field">*</span>:</div>
                            <div class="col-md-4"><input type="text" id="project_code" name="project_code" value="<?php echo $project_code;?>" class="form-controlvalidate[required]" /></div>
                            <div class="col-md-2">Project Name<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_name" value="<?php echo $project_name;?>"
							class="form-control validate[required]" value=""/></div>                          
                        </div>
						<div class="form-row">
							<div class="col-md-2">Client's Name<span class="require-field">*</span>:</div>
                            <div class="col-md-4"><input type="text" id="client_name" name="client_name" value="<?php echo $client_name;?>" class="form-controlvalidate[required]" /></div>
                            <div class="col-md-2">Project Address :</div>
                            <div class="col-md-4"><input type="text" name="project_address" value="<?php echo $project_address;?>" class="form-control"/></div>                          
                        </div>
						<div class="header"><h2><u>Address</u></h2></div>
                        <div class="form-row">                            
                            <div class="col-md-2">City:</div>
                            <div class="col-md-4"><input type="text" name="city" value="<?php echo $city;?>" id = "city" 
							class="form-control" /></div>
							<div class="col-md-2">District:</div>
                            <div class="col-md-4"><input type="text" name="district" value="<?php echo $district;?>" class="form-control"/></div>
                        
                        </div>
						 <div class="form-row">
                            <div class="col-md-2">State:</div>
                            <div class="col-md-4"><input type="text" name="state" value="<?php echo $state;?>" class="form-control"/></div>
                        
                            <div class="col-md-2">Pin code :</div>
                            <div class="col-md-4"><input type="text" name="pincode" value="<?php echo $pincode;?>" id = "pincode" class="form-control"/></div>
                        </div>
						
						<div class="header"><h2><u>Other Info</u></h2></div>
						 <div class="form-row">						
                            <div class="col-md-2">Work Description:</div>
                            <div class="col-md-4"><input type="text" id="work_description" name="work_description" value="<?php echo $work_description;?>" class="form-control"/></div>
							
                        </div>
						 <div class="form-row">						
                            <div class="col-md-2">Contract Start Date:</div>
                            <div class="col-md-4"><input type="text" id="contract_start_date" name="contract_start_date" value="<?php echo $contract_start_date;?>" class="form-control date_picker"/></div>
							<div class="col-md-2">Contract End Date:</div>
                            <div class="col-md-4"><input type="text" id="contract_end_date" name="contract_end_date" value="<?php echo $contract_end_date;?>" class="form-control date_picker"/></div>
							
                        </div>
						 <div class="form-row">						
                            <div class="col-md-2">Contract Amount:</div>
                            <div class="col-md-4"><input type="text" id="contract_amount" name="contract_amount" value="<?php echo $contract_amount;?>" class="form-control"/></div>
							<div class="col-md-2">Defect Liability Period:</div>
                            <div class="col-md-4"><input type="text" id="defect_liability_period" name="defect_liability_period" value="<?php echo $defect_liability_period;?>" class="form-control"/></div>
							
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Project Director:</div>
                            <div class="col-md-4">
								<select style="width: 100%;" class="select2" required="true"  name="project_director">
								<option value="">--Select Project Director--</Option>
								<?php 
									foreach($project_manager as $retrive_data)
									{
										echo '<option value="'.$retrive_data['user_id'].'" '.$this->ERPfunction->selected($retrive_data['user_id'],$project_director).'>'.$this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									}
								?>
								</select>
							</div>   
							<div class="col-md-2">Construction Manager:</div>
                            <div class="col-md-4">
								<select style="width: 100%;" class="select2" required="true"  name="conttruction_manager">
								<option value="">--Select Construction Manager --</Option>
								<?php 
									foreach($constructionmanager as $retrive_data)
									{
										echo '<option value="'.$retrive_data['user_id'].'" '.$this->ERPfunction->selected($retrive_data['user_id'],$conttruction_manager).'>'.$this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									}
								?>
								</select>
							</div>      
                        </div>	
						<div class="header"><h2><u>Project Attachment</u></h2></div>
						<div class="form-row">							
                            <div class="col-md-2">Price Bid:</div>
                            <div class="col-md-4">
							<input type="file" id = "attach_price_bid" name="attach_price_bid"/>
							<input  type="hidden" name="old_attach_price_bid" value="<?php echo $attach_price_bid;?>"/>
							<?php 
								if($attach_price_bid != '')
								{
								?>
								<a href="<?php  echo $this->request->webroot.'img/'.$attach_price_bid;?>" 
								class="btn btn-primary">View Price Bid</a>
								<?php
								}
							?>
							</div>
							
							<div class="col-md-2">Spicification:</div>
                            <div class="col-md-4"><input type="file" name="attach_specification" 
							id="attach_specification"/>
							<?php 
								if($attach_specification != '')
								{
								?>
								<a href="<?php  echo $this->request->webroot.'img/'.$attach_specification;?>" 
								class="btn btn-primary">View Spicification</a>
								<?php
								}
							?>
							</div>
							<input  type="hidden" name="old_attach_specification" value="<?php echo $attach_specification;?>"/>
						</div>	
						<div class="form-row">							
                            <div class="col-md-2">Make List:</div>
                            <div class="col-md-4"><input type="file" id = "attach_makelist" 
							name="attach_makelist"/>
							<?php 
								if($attach_makelist != '')
								{
								?>
								<a href="<?php  echo $this->request->webroot.'img/'.$attach_makelist;?>" 
								class="btn btn-primary">View Make List</a>
								<?php
								}
							?>
							<input  type="hidden" name="old_attach_makelist" value="<?php echo $attach_makelist;?>"/>
							</div>
							<div class="col-md-2">Contrack Document:</div>
                            <div class="col-md-4"><input type="file" name="attach_contract_document" 
							id="attach_contract_document" />
							<input  type="hidden" name="old_attach_contract_document" value="<?php echo $attach_contract_document;?>"/>
							<?php 
								if($attach_contract_document != '')
								{
								?>
								<a href="<?php  echo $this->request->webroot.'img/'.$attach_contract_document;?>" 
								class="btn btn-primary">View Contrack Document</a>
								<?php
								}
							?>
							
							</div>
						</div>	
						<div class="form-row">
                            <div class="col-md-2">As On Date for Following Information</div>
                            <div class="col-md-4"><input type="text" name="date_of_information" 
							value="<?php echo $date_of_information;?>" class="form-control date_picker"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Excess Amount*</div>
                            <div class="col-md-4"><input type="text" name="excess_amount" 
							value="<?php echo $excess_amount;?>" class="form-control validate[required]"/></div>
                        
                            <div class="col-md-2">Extra Item Amount*</div>
                            <div class="col-md-4"><input type="text" id="extra_item_amount" name="extra_item_amount" 
							value="<?php echo $extra_item_amount;?>" class="form-control validate[required]"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Revised Amount*</div>
                            <div class="col-md-4"><input type="text" name="revise_amount" 
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
                            <div class="col-md-2">Actual Amount*</div>
                            <div class="col-md-4"><input type="text" name="actual_amount" 
							value="<?php echo $actual_amount;?>" class="form-control validate[required]"/></div>
                        
                            <div class="col-md-2">Actual Completion Date*</div>
                            <div class="col-md-4"><input type="text" id="actual_cmp_date" name="actual_cmp_date" value="<?php echo $actual_cmp_date;?>" class="form-control date_picker validate[required]"/></div>
                        </div>
										
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
				</div>
				<?php $this->Form->end(); ?>
			</div>
         </div>