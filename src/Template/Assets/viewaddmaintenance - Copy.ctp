<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">

jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	
	
	
	jQuery('#maintenance_date').datepicker({
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
/* 
$asset_code=isset($maintenace_data['asset_code'])?$maintenace_data['asset_code']:'';
$user_id=isset($maintenace_data['user_id'])?$maintenace_data['user_id']:''; */
 
$asset_group=isset($maintenace_data['asset_group'])?$maintenace_data['asset_group']:'';
$amo_no=isset($maintenace_data['amo_no'])?$maintenace_data['amo_no']:'';
$maintenance_date=isset($maintenace_data['maintenance_date'])?date("d-m-Y",strtotime($maintenace_data['maintenance_date'])):'';
 $asset_name='';
 $asset_code='';
 $capacity='';
 $asset_make='';
 $deployed_to='';
if(isset($maintenace_data['asset_id'])){
$asset_name=$maintenace_data['asset_id'];
$asset_code=$this->ERPfunction->get_asset_code($maintenace_data['asset_id']);
$capacity=$this->ERPfunction->get_asset_capacity($maintenace_data['asset_id']);
$asset_make=$this->ERPfunction->get_asset_make($maintenace_data['asset_id']);
$deployed_to=$this->ERPfunction->get_projectname_by_asset($maintenace_data['asset_id']);
}
 
 
$quantity=isset($maintenace_data['quantity'])?$maintenace_data['quantity']:'';
$unit=isset($maintenace_data['unit'])?$maintenace_data['unit']:'';
$model_no=isset($maintenace_data['model_no'])?$maintenace_data['model_no']:'';
$vehicle_no=isset($maintenace_data['vehicle_no'])?$maintenace_data['vehicle_no']:'';
$expense_amount=isset($maintenace_data['expense_amount'])?$maintenace_data['expense_amount']:'';
$payment_by=isset($maintenace_data['payment_by'])?$maintenace_data['payment_by']:'1';
$supervised_by=isset($maintenace_data['supervised_by'])?$maintenace_data['supervised_by']:'';
$voucher_no=isset($maintenace_data['voucher_no'])?$maintenace_data['voucher_no']:'';
$desc_maintenance=isset($maintenace_data['desc_maintenance'])?$maintenace_data['desc_maintenance']:'';
$reason=isset($maintenace_data['reason'])?$maintenace_data['reason']:'';
$desc_amount=isset($maintenace_data['desc_amount'])?$maintenace_data['desc_amount']:'';
$project_code=isset($maintenace_data['project_code'])?$maintenace_data['project_code']:'';
$project_id=isset($maintenace_data['project_id'])?$maintenace_data['project_id']:'';
$maintenance_type=(isset($maintenace_data['maintenance_type']))?$maintenace_data['maintenance_type']:1;
$party_name=isset($maintenace_data['party_name'])?$maintenace_data['party_name']:'';



?>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>    		
                <div class="block block-fill-white">				
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
			
                    <div class="header">
                        <h2><u>Asset Maintenance Expense Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'maintenance_form','class'=>'form_horizontal formsize','method'=>'post','id'=>'user_form','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				
					<input type="hidden" name="asset_me_action" class="form-control" value="<?php echo $asset_me_action;?>" disabled />	
					
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code; ?>"
							class="form-control validate[required]" value="" readonly="true" disabled /></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select disabled  class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($project_id)){
												if($project_id == $retrive_data['project_id'])
												{
													echo 'selected="selected"';
												}
			
											}?> >
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php
										
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">A. M. O. No.<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="amo_no" value="<?php echo $amo_no;?>" id="amo_no" class="form-control validate[required]" disabled /></div>
							<div class="col-md-2">Date</div>
                            <div class="col-md-4"><input id="maintenance_date" type="text" name="maintenance_date" value="<?php echo $maintenance_date;?>" class="form-control" disabled /></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Asset Group<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<input class="form-control" type="hidden" name="asset_group" id="asset_group_id" value="<?php echo ($asset_group != "")?$asset_group:""; ?>"  disabled />
								<input class="form-control" id="asset_group_name" value="<?php echo ($asset_group != "")?$this->ERPfunction->get_asset_group_name($asset_group):""; ?>"  disabled />
								<!-- <select disabled  style="width: 100%;" class="select2" required="true"  name="asset_group" id="asset_group">
								<option>--Select Assets Group--</option>
								<?php 
							 
								// foreach($asset_groups as $key => $retrive_data)
								// {
									// echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$asset_group).'>'.$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
								// }
								 
								?>
								</select> -->
										
							</div>
                        
                            <div class="col-md-2">Asset ID</div>
                            <div class="col-md-4"><input type="text" readonly="true" id="asset_code" name="asset_code" value="<?php echo $asset_code;?>" class="form-control" disabled /></div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Asset Name<span class="require-field">*</span> :</div>
                            <div class="col-md-10">
								<select disabled  style="width: 100%;" class="select2" required="true"  name="asset_name" id="asset_namelist">
									<option> -- Select Assets List -- </option>
									<?php 
									// foreach($asset_names as $key => $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['asset_id'].'" '.$this->ERPfunction->selected($retrive_data['asset_id'],$asset_name).'>'.$retrive_data['asset_name'].'</option>';
									// }
									?>
								</select>
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Make:</div>
                            <div class="col-md-4">
								<input type="text" id="asset_make" readonly="true" name="asset_make" value="<?php echo $asset_make;?>" class="form-control" disabled />							
						 
							</div>
							<div class="col-md-2">Asset Capacity</div>
                            <div class="col-md-4"><input type="text"  readonly="true" id="capacity" name="capacity" value="<?php echo $capacity;?>" class="form-control" disabled /></div>
                       
							 
							
                        </div>
						<!--<div class="form-row">						
                            <div class="col-md-2">Deployed Quantity</div>
                            <div class="col-md-4"><input type="text" name="quantity" id="quantity" value="<?php echo $quantity;?>" class="form-control" disabled /></div>
							<div class="col-md-2">Unit</div>
                            <div class="col-md-4"><input type="text" name="unit"  id="unit" value="<?php echo $unit;?>" class="form-control" value="" disabled /></div>
							
                        </div>-->
						
						<div class="form-row">
                            <div class="col-md-2">Model No<span class="require-field">*</span>  :</div>
                            <div class="col-md-4"><input type="text" name="model_no" id="model_no" value="<?php echo $model_no;?>" class="form-control validate[required]" disabled /></div>
							<div class="col-md-2">Identity / Veh. No.</div>
                            <div class="col-md-4"><input type="text" name="vehicle_no" id="vehicle_no" value="<?php echo $vehicle_no;?>" class="form-control" disabled /></div>
                        </div>						
					<!-- <div class="form-row">
                            <div class="col-md-2">Deployed To</div>
                            <div class="col-md-10">
							<input type="text" id="deployed_to" readonly="true" name="deployed_to" value="<?php echo $deployed_to;?>" class="form-control" disabled />
							<!-- <select disabled  style="width: 100%;" class="select2" required="true"  name="deployed_to" id="deployed_to">
								<option>--Select Project --</option>
								<?php 
							 
								foreach($project_data as $key => $retrive_data)
								{
									echo $retrive_data['project_id'];
									echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($retrive_data['project_id'],$deployed_to).'>'.$this->ERPfunction->get_projectname($retrive_data['project_id']).'</option>';
								}  
								?>
								</select> -->
						<!--	</div>
                        </div> -->
						 <div class="form-row">
							<div class="col-md-2">Maintenance Type</div>
							<div class="col-md-4">
								<select style="width:100%;" disabled class="select2" name="maintenance_type" id="maintenance_type">
									<option value="0" <?php echo ($maintenance_type == 0)?"selected":""; ?>>Preventive / Routine</option>
									<option value="1" <?php echo ($maintenance_type == 1)?"selected":""; ?>>Corrective / Breakdown</option>
									
								</select>
							</div>
						</div>
						<div class="form-row">
						<div class="col-md-2">Party's Name</div>
						<div class="col-md-10">
							 <input type="text" name="party_name" disabled value="<?php echo $party_name;?>" class="form-control"/>
						</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Amount of Expense *</div>
                            <div class="col-md-4">
								 <input type="text" name="expense_amount" value="<?php echo $expense_amount;?>" class="form-control validate[required]" disabled />
							</div>
							  <div class="col-md-2">Payment</div>
                            <div class="col-md-4">
							<select disabled  style="width: 100%;" class="" name="payment_by" id="payment_by">
								<option> -- Select Payment Method -- </option>
									<?php 
									foreach($pay_method as $key => $retrive_data)
									{
										echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$payment_by).' >'.$retrive_data['title'].'</option>';
									}
									?>
								</select>
								
							</div>
                        
						</div>
                        
						<div class="form-row">
						 <div class="col-md-2">Voch. No. / Inw. No. *</div>
                            <div class="col-md-4">
								<input type="text" name="voucher_no" value="<?php echo $voucher_no;?>" class="form-control validate[required]" disabled /> 
							</div>
                             <div class="col-md-2">Supervised By *</div>
                            <div class="col-md-4">
							
								<input type="text" name="supervised_by" value="<?php echo $supervised_by;?>" class="form-control validate[required]" disabled /> 
							<!--							
							<select disabled  style="width: 100%;" class="select2" required="true"  name="supervised_by">
								<option>--Select User --</option>
								<?php 
							 
								/* foreach($superviser as $key => $retrive_data)
								{
									echo '<option value="'.$retrive_data['user_id'].'" '.$this->ERPfunction->selected($retrive_data['user_id'],$asset_group).'>'.$this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
								} */
								 
								?>
								</select> -->
								</div>
						 
                         </div>
						
						<div class="form-row">
                            <div class="col-md-2">Description</div>
                            <div class="col-md-3">Material / Spares/ Tools / Service / Others</div>
                            <div class="col-md-3">Reason</div>
                            <div class="col-md-3">Amount</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2"> </div>
                            <div class="col-md-3"><textarea name="desc_maintenance" disabled ><?php echo $desc_maintenance; ?></textarea> </div>
                            <div class="col-md-3"><textarea name="reason" disabled><?php echo $reason; ?> </textarea> </div>
                            <div class="col-md-3"><textarea name="desc_amount" disabled> <?php echo $desc_amount; ?></textarea> </div>
                        </div>
					 
						
						
						<div class="form-row add_field">
						<?php 
						if($asset_me_action == "edit")
						{
						$attached_files = json_decode($maintenace_data["attachment"]);
						$attached_label = json_decode(stripcslashes($maintenace_data['attach_label']));						
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
										
									</div>
								</div>							
							<?php $i++;
							}
						}
						}
						?>
						</div>	
					 
				</div>
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>			
</div>