<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
		jQuery('#rmc_form').validationEngine();
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
	
jQuery("body").on("change", "#asset_namelist", function(event){	 
	  var asset_name  = jQuery(this).val();
		 
	   var curr_data = {	 						 					
	 					asset_name : asset_name,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getassetid'));?>",
				data:curr_data,
                async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);
					jQuery('#asset_code').val(json_obj['asset_code']);					
					jQuery('.select2').select2();
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	//jQuery('#user_form').validationEngine();
	jQuery('.datepick').datepicker({
		dateFormat: "yy-mm-dd",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
                }); 
} );
</script>
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
						<h2><?php echo $form_header;?>  </h2>
						<div class="pull-right">
						<a href="<?php //echo $this->ERPfunction->action_link('Assets',$back);?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
                    <div class="header">
                        <h2></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'rmc_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo ($edit)?$data["project_code"]:""; ?>"
							class="form-control validate[required]" value="" readonly="true" disabled /></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id" disabled >
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($data)){
												if($data['project_id'] == $retrive_data['project_id'])
												{
													echo 'selected="selected"';
												}
			
											}?> >
											<?php echo $retrive_data['project_code'].' '.$retrive_data['project_name']; ?> </option>
										<?php
										
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">RMC. I.S. No.:</div>
                            <div class="col-md-4">
								<input name="isno" class="form-control" value="<?php echo ($edit)?$data["isno"]:"";?>" disabled>
							</div>							
                            <div class="col-md-2">Date :</div>
                            <div class="col-md-4">
								<input name="rmc_date" class="datepick form-control" value="<?php echo ($edit)?$this->ERPfunction->get_date($data["rmc_date"]):"";?>" disabled>
							</div>	
						</div>						
						<div class="form-row">
                            <div class="col-md-2">Asset Name<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<?php 
								echo $this->Form->select("asset_name",$asset_list,["default"=>($edit)?$data["asset_name"]:"","class"=>"select2","id"=>"asset_namelist","style"=>"width:100%","disabled"=>"disabled"]);
								?>								
							</div>							
                            <div class="col-md-2">Asset ID</div>
                            <div class="col-md-4">
								<input type="text" readonly="true" id="asset_code" name="asset_code" value="<?php echo ($edit)?$data["asset_code"]:"";?>" class="form-control" disabled />
							</div>	
						</div>
						<div class="form-row">
                            <div class="col-md-2">Agency Name<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<?php 
								echo $this->Form->select("agency_name",$agency_list,["default"=>($edit)?$data["agency_name"]:"","class"=>"select2","style"=>"width:100%","disabled"=>"disabled"]);
								?>								
							</div>							                            
						</div>
						<div class="form-row">
                            <div class="col-md-2">Operator's Name<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<input name="operator_name" class="form-control validate[required]" value="<?php echo ($edit)?$data["operator_name"]:"";?>" disabled >							
							</div>
							 <div class="col-md-2">Order By<span class="require-field">*</span> :</div>                            
							<div class="col-md-4">
								<input name="order_by" class="form-control validate[required]" value="<?php echo ($edit)?$data["order_by"]:"";?>" disabled >							
							</div>
						</div>	
						
						<div class="form-row">
                            <div class="col-md-2">Usage<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<input name="rmc_usage" id="asset_list" class="form-control validate[required]" value="<?php echo ($edit)?$data["rmc_usage"]:"";?>" disabled >
							</div>
							<br>
						</div>						
						<div class="form-row">
                            <div class="col-md-2">Concrete Grade<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<?php
								$grade = ["all"=>"All","M7.5"=>"M7.5","M10"=>"M10","M15"=>"M15","M20"=>"M20","M25"=>"M25","M30"=>"M30","M35"=>"M35","M40"=>"M40"];
								echo $this->Form->select("concrete_grade",$grade,["default"=>($edit)?$data["concrete_grade"]:"","class"=>"select2","style"=>"width:100%","disabled"=>"disabled"]);
								?>								
							</div>							
                            <div class="col-md-2">Quantity Ordered</div>
                            <div class="col-md-4">
								<input type="text" id="quantity_ordered" name="quantity_ordered" value="<?php echo ($edit)?$data["quantity_ordered"]:"";?>" class="form-control" disabled />
							</div>	
						</div>
						
						<div class="form-row">
						<table class="table table-bordered">
						<thead>							
							<tr>
								<th>Challan No</th>
								<th style="width: 10%;">TM's No</th>
								<th>Driver's Name</th>
								<th>Time In</th>
								<th>Time Out</th>
								<th>Quantity<br>(In Cum)</th>
								<th>Received By</th>
								
							</tr>
						</thead>
						<tbody>				
						<?php $tm = ["1"=>"#1","2"=>"#2","3"=>"#3"];
						
							if($edit && !empty($data["quantity"]))
							{								
								$driver_name = json_decode($data["driver_name"]);
								$tmo = json_decode($data["tmno"]);
								$time_in = json_decode($data["time_in"]);
								$time_out = json_decode($data["time_out"]);
								$quantity = json_decode($data["quantity"]);
								$received_by = json_decode($data["received_by"]);
								$quantity = json_decode($data["quantity"]);
								$challan = json_decode($data["challan"]);								
								$size = count($tmo);								
								for($i=0;$i<$size;$i++)
								{ ?>
									<tr>
										<td>
											<input name="challan[]" class="form-control" value="<?php echo $challan[$i];?>" disabled />
										</td>
										<td>
											<?php										
												echo $this->Form->select("old_tmno[]",$tm,["default"=>$tmo[$i],"class"=>"form-control","disabled"=>"disabled"]);
											?>
										</td>
										<td>
											<input name="old_driver_name[]" class="form-control" value="<?php echo $driver_name[$i];?>" disabled />
										</td>
										<td>
											<input name="old_time_in[]" class="form-control" value="<?php echo $time_in[$i];?>" disabled />
										</td>
										<td>
											<input name="old_time_out[]" class="form-control" value="<?php echo $time_out[$i];?>" disabled />
										</td>
										<td>
											<input name="old_quantity[]" class="form-control" value="<?php echo $quantity[$i];?>" disabled />
										</td>
										<td>
											<input name="old_received_by[]" class="form-control" value="<?php echo $received_by[$i];?>" disabled />
										</td>
										<!-- <td>
											<?php //if(!empty($challan[$i]))
											//{ 
											?>
											<a href="<?php //echo $this->request->base;?>/img/users_images/<?php //echo $challan[$i];?>" target="_blank" class="btn btn-primary">View</a>
											<input type="hidden" name="old_challan[<?php //echo $i; ?>]" value="<?php //echo $challan[$i];?>" />
										</td> -->							
									</tr>
							<?php //}
								}
							}
							?>
							
							
						</tbody>
						</table>
						</div>					
												
             
			<?php echo $this->Form->end();?>
			<div class="row" style="font-style:italic;color:gray;">							
					<div class="col-md-7 pull-right">
						<br><br><br>
						<div class="col-md-4">
							<?php echo "Created By:{$this->ERPfunction->get_user_name($data['created_by'])}"; ?>
						</div>
						<div class="col-md-4">
							 <?php echo "Last Edited By:{$this->ERPfunction->get_user_name($data['last_edit_by'])}"; ?>
						</div>
						<div class="col-md-4">						 
						  <a href="../printrmc/<?php echo $data["id"];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
						</div> 
					</div>
				</div>
	      </div>
		</div>
<?php } ?>
</div>