<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">

</script>	
<?php 
/* 
$asset_code=isset($asset_data['asset_code'])?$asset_data['asset_code']:'';
$user_id=isset($asset_data['user_id'])?$asset_data['user_id']:''; */
$asset_group=isset($asset_data['asset_group'])?$asset_data['asset_group']:'';
$asset_code=isset($asset_data['asset_code'])?$asset_data['asset_code']:'';
$asset_name=isset($asset_data['asset_name'])?$asset_data['asset_name']:'';
$capacity=isset($asset_data['capacity'])?$asset_data['capacity']:'';
$asset_make=isset($asset_data['asset_make'])?$asset_data['asset_make']:'';
$purchase_quantity=isset($asset_data['purchase_quantity'])?$asset_data['purchase_quantity']:'';
$quantity=isset($asset_data['quantity'])?$asset_data['quantity']:'';
$unit=isset($asset_data['unit'])?$asset_data['unit']:'';
$model_no=isset($asset_data['model_no'])?$asset_data['model_no']:'';
$vehicle_no=isset($asset_data['vehicle_no'])?$asset_data['vehicle_no']:'';
$purchase_date=isset($asset_data['purchase_date'])?$asset_data['purchase_date']:'';
$purchase_amount=isset($asset_data['purchase_amount'])?$asset_data['purchase_amount']:'';
$po_no=isset($asset_data['po_no'])?$asset_data['po_no']:'';
$warranty_period=isset($asset_data['warranty_period'])?$asset_data['warranty_period']:'';
$payment=isset($asset_data['payment'])?$asset_data['payment']:'';
$voucher_no=isset($asset_data['voucher_no'])?$asset_data['voucher_no']:'';
$deployed_to=isset($asset_data['deployed_to'])?$asset_data['deployed_to']:'';  
$description=isset($asset_data['description'])?$asset_data['description']:''; 
$vendor_name=isset($asset_data['vendor_name'])?$asset_data['vendor_name']:''; 
$vendor_id=isset($asset_data['vendor_id'])?$asset_data['vendor_id']:''; 
$rto_reg_no=isset($asset_data['rto_reg_no'])?$asset_data['rto_reg_no']:'';  
$due_date_reg=isset($asset_data['due_date_reg'])?$asset_data['due_date_reg']->format("Y-m-d"):'';  
$insurance_company=isset($asset_data['insurance_company'])?$asset_data['insurance_company']:'';  
$due_date_insurance=isset($asset_data['due_date_insurance'])?$asset_data['due_date_insurance']->format("Y-m-d"):'';  
$operational_status=isset($asset_data['operational_status'])?$asset_data['operational_status']:'';  


?>
<style>
.add-make .modal-body{
	 max-height: 350px;
    overflow-y: scroll;
}
</style>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content add-make"></div>
    </div>
</div>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>               <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
							<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
									
                    <div class="header">
                        <h2><u>Assets Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'asset_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				
					<input type="hidden" name="asset_action" class="form-control" value="<?php echo $asset_action;?>" />	
					
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Asset Group<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								
								<select disabled  style="width: 100%;" class="select2" required="true"  name="asset_group" id="asset_group" disabled>
								<option value="">Select Asset Group</option>
								<?php 
								foreach($asset_groups as $key => $retrive_data)
								{
									echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$asset_group).'>'.$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
								}
								?>
								</select>
										
							</div>
                        
                            <div class="col-md-2">Asset ID</div>
                            <div class="col-md-4"><input type="text" readonly="true" id="asset_code" name="asset_code" value="<?php echo $asset_code;?>" class="form-control" disabled /></div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Asset Name<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="asset_name" value="<?php echo $asset_name;?>" class="form-control validate[required]" disabled /></div>
							<div class="col-md-2">Asset Capacity</div>
                            <div class="col-md-4"><input type="text" name="capacity" value="<?php echo $capacity;?>" class="form-control" disabled /></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Make:</div>
                            <div class="col-md-6">	
								<select disabled  style="width: 100%;" class="select2" required="true"  name="asset_make" id="make_in">
								<option>--Select Make --</option>
								<?php 
								 if(isset($makelist)){
                                        foreach($makelist as $make_info){
                                        ?>
                                   <option value="<?php echo $make_info['cat_id'];?>" <?php                                            
                                                if($asset_make == $make_info['cat_id']){
                                                    echo 'selected="selected"';
                                                }else{
                                                    echo '';
                                                }
                                            
                                        
                                        ?> ><?php echo $make_info['category_title'];?></option>
                                            <?php             
                                        }
                                    }
								?>
								</select> 
							</div>
							
                        </div>
						<div class="form-row">						
                            <div class="col-md-2">Vendor's Name *</div>
                            <div class="col-md-4">
							<?php 
								echo $this->Form->select("vendor_name",$vendor_list,["empty"=>"Select Vendor Name","default"=>$vendor_name,"class"=>"select2","id"=>"vendor_list","style"=>"width:100%"]);
							?>	
							</div>
							<div class="col-md-2">Vendor's ID</div>
                            <div class="col-md-4"><input type="text" readonly name="vendor_id" value="<?php echo $vendor_id;?>" class="form-control" id="vendor_id" value="" disabled /></div>
							
                        </div>
						<div class="form-row">						
                            <div class="col-md-2">Purchased Quantity *</div>
                            <div class="col-md-4"><input type="text" name="purchase_quantity" id="purchase_quantity" value="<?php echo $purchase_quantity;?>" class="form-control validate[required]" disabled /></div>
							<div class="col-md-2">Unit *</div>
                            <div class="col-md-4"><input type="text" name="unit" value="<?php echo $unit;?>" class="form-control validate[required]" value="" placeholder="Nos." disabled /></div>
							
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Model No</div>
                            <div class="col-md-4"><input type="text" name="model_no" value="<?php echo $model_no;?>" class="form-control" disabled /></div>
							<div class="col-md-2">Identity / Veh. No.</div>
                            <div class="col-md-4"><input type="text" name="vehicle_no" value="<?php echo $vehicle_no;?>" class="form-control" disabled /></div>
                        </div>						
						
						<div class="form-row">
                            <div class="col-md-2">Date of Purchase *</div>
                            <div class="col-md-4"><input id="date_of_purchase" type="text" name="purchase_date" value="<?php echo $purchase_date;?>" class="form-control validate[required]" disabled /></div>
                        
                            <div class="col-md-2">Amount of Purchase *</div>
                            <div class="col-md-4">
								 <input type="text" name="purchase_amount" value="<?php echo $purchase_amount;?>" class="form-control validate[required]" disabled />
							</div>
						</div>
                        
						<div class="form-row">
                             <div class="col-md-2">P.O. No.</div>
                            <div class="col-md-4"><input type="text" name="po_no" value="<?php echo $po_no;?>" class="form-control" disabled /></div>
                        
                            <div class="col-md-2">Warranty Period</div>
                            <div class="col-md-4">
							 <input type="text" name="warranty_period" value="<?php echo $warranty_period;?>" class="form-control" disabled /> 
							</div>
						</div>
						<div class="form-row">
                             <div class="col-md-2">RTO Registration No.</div>
                            <div class="col-md-4"><input type="text" name="rto_reg_no" value="<?php echo $rto_reg_no;?>" class="form-control" disabled /></div>
                        
                            <div class="col-md-2">Due Date of Registration</div>
                            <div class="col-md-4">
							 <input type="text" name="due_date_reg" value="<?php echo $due_date_reg;?>" class="datepick form-control" disabled /> 
							</div>
						</div>
						<div class="form-row">
                             <div class="col-md-2">Insurance Company</div>
                            <div class="col-md-4"><input type="text" name="insurance_company" value="<?php echo $insurance_company;?>" class="form-control" disabled /></div>
                        
                            <div class="col-md-2">Due Date of Insurance</div>
                            <div class="col-md-4">
							 <input type="text" name="due_date_insurance" value="<?php echo $due_date_insurance;?>" class="datepick form-control" disabled /> 
							</div>
						</div>
						
						<div class="form-row">
                             <div class="col-md-2">Payment *</div>
                            <div class="col-md-4">
								<select disabled  name="payment" class="form-control">
									<option value="cash">Cash</option>
									<option value="cheque" selected>Cheque</option>
								</select>
							</div>
                        
                            <div class="col-md-2">Voch. No. / Inw. No. *</div>
                            <div class="col-md-4">
								<input type="text" name="voucher_no" value="<?php echo $voucher_no;?>" class="form-control validate[required]" disabled /> 
							</div>
                         </div>
						<div class="form-row">
                            <div class="col-md-2">Deployed To *</div>
                            <div class="col-md-10">
								<select disabled  style="width: 100%;" class="select2" required="true"  name="deployed_to" id="deployed_to">
								<option value="">Select Project</option>
								<?php 
							 
								foreach($project_data as $key => $retrive_data)
								{
									echo $retrive_data['project_id'];
									echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($retrive_data['project_id'],$deployed_to).'>'.$this->ERPfunction->get_projectname($retrive_data['project_id']).'</option>';
								}  
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">						
                            <div class="col-md-2">Deployed Quantity *</div>
                            <div class="col-md-4"><input type="text" name="quantity" id="deployed_quantity" value="<?php echo $quantity;?>" class="form-control validate[required]" disabled /></div>
						</div>
						
						<div class="form-row">
                             <div class="col-md-2">Operational Status</div>
							 <div class="col-md-4">
								<select disabled  name="operational_status" class="form-control">
									<option value="working" <?php echo ($operational_status == "working")?"selected":"";?>>Working</option>
									<option value="notworking" <?php echo ($operational_status == "notworking")?"selected":"";?>>Not Working</option>
								</select>
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Description</div>
                            <div class="col-md-10">
						
								<input type="text" name="description" value="<?php echo $description;?>" class="form-control" disabled /> 
							</div>
                        </div>	
						<div class="form-row add_field">
						<?php 
						if($asset_action == "edit")
						{
						$attached_files = json_decode($asset_data["attach_file"]);
						$attached_label = json_decode(stripcslashes($asset_data['attach_label']));						
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
										<div class='col-md-4'><a href="<?php echo $this->ERPfunction->get_signed_url($file);?>" class="btn btn-primary" target="_blank">View File</a>
										<input type='hidden' name='old_attach_file[]' value='<?php echo $file;?>' class='form-control'></div>
										
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
