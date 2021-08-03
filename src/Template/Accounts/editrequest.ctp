<?php
use Cake\Routing\Router;

if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>

<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#pr_date,#as_on_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectdetailaccount'));?>",
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
                     console.log(e.responseText);
                }
            });	
	});
	jQuery("#add_newrow").click(function(){
		//jQuery(this).attr("disabled", "disabled");
		//var row_id = jQuery("tbody > tr").length;
		var row_len = jQuery(".row_number").length;
		if(row_len > 0)
		{
			var num = jQuery(".row_number:last").val();
			var row_id = parseInt(num) + 1;
		}
		else
		{
			var row_id = 0;
		}
		var action = 'add_newrow';
		jQuery.ajax({
			headers: {
						'X-CSRF-Token': csrfToken
					},
                       type: 'POST',
                      url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewline"]);?>',
                     data : {row_id:row_id},
                     success: function (response)
                        {	
                            jQuery("tbody").append(response);
							jQuery('#material_id_'+row_id).select2();
							jQuery('.delivery_date').datepicker({
								 changeMonth: true,
							  changeYear: true,
							  dateFormat: "dd-mm-yy"
							});
							return false;
						},
                    error: function(e) {
                 alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
       });
	});
	jQuery('.delivery_date').datepicker({
		 changeMonth: true,
      changeYear: true,
	  dateFormat: "dd-mm-yy"
	});
	jQuery("body").on("change", ".material_id", function(event){ 
	 var row_id = jQuery(this).attr('data-id');
	  var material_id  = jQuery(this).val() ;
		/* alert(material_id);
		return false;  */  
	   var curr_data = {	 						 					
	 					material_id : material_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getvendorlist'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					
					
					jQuery('#material_code_'+row_id).html();
					jQuery('#material_code_'+row_id).html(json_obj['material_code']);					
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });
	
  });
  
  jQuery('body').on('click','.trash',function(){
	  
		/* var row_id = jQuery(this).attr('data-id');		
		jQuery('table tr#row_id_'+row_id).remove();	 */
		jQuery(this).parents("tr").remove();
		return false;
	});
} );
</script>	

<div class="col-md-10" >
				
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2> Edit Advance Request  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Accounts','viewrequest');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php ?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $this->ERPfunction->get_project_code($request_list['project_id']); ?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name *</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
									?>
										<option value="<?php echo $retrive_data["project_id"];?>" <?php if($request_list['project_id'] == $retrive_data['project_id']) echo "selected=selected"; ?>>
										<?php echo $retrive_data["project_name"];?></option>
									<?php
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Adv.R.No</div>
                            <div class="col-md-4">
								<input type="text" name="prno" id="prno"  class="form-control" value="<?php echo $request_list['advance_req_no']; ?>"/>
							</div>
                        
                            <div class="col-md-1 text-right">Date</div>
                            <div class="col-md-2"><input type="text" name="pr_date" id="pr_date" 
							value="<?php if(isset($request_list['date'])){echo $this->ERPfunction->get_date($request_list['date']); } else{ echo $this->ERPfunction->get_date(date('Y-m-d'));}?>" class="form-control" value=""/></div>
							 <div class="col-md-1 text-right">Time</div>
                            <div class="col-md-2"><input type="text" name="pr_time" id="pr_time" 
							value="<?php if(isset($request_list['time'])){echo date('H:i',strtotime($request_list['time'])); } else { echo date('H:i');}?>" class="form-control" value=""/></div>
                        </div>
					<!-- <div class="form-row">
                            <div class="col-md-2">Raised From:</div>
                            <div class="col-md-4">
								<?php 
								
								?>
								<select class="select2"  required="true"   style="width: 100%;" name="raise_from" id="raise_from">
								<option value="">--Select Project--</Option>
								<?php 
									// foreach($raise_from as $key => $data)
								// {
									// echo '<optgroup label="'.$this->ERPfunction->get_rolename($key).'" style = "text-transform: capitalize;">';
									// foreach($data as $user_data)
									// {
										// echo '<option value="'.$user_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($user_data['user_id']).'</option>';									
									// }
									// echo '</optgroup>';
								// }
								?>
								</select>
							</div>
                        
                             <div class="col-md-2">Contact No: (1)</div>
                            <div class="col-md-4">
								<input type="text" name="contact_no1" value="" class="form-control" value=""/>
							</div>
                        </div> -->
					<!-- <div class="form-row">
                            <div class="col-md-2">Forwarded To:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="forword_to">
								<option value="">--Select Project--</Option>
								<?php 
									// foreach($purchase_department as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									// }
								?>
								</select>
							</div>
                        
                            <div class="col-md-2">Contact No: (2)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no2" value="" class="form-control" value=""/>
							</div>
                        </div> -->
						
						<div class="form-row">
						 <!-- <button type="button" id="add_newrow" class="btn btn-default">Add New </button> -->
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th>Agency Id</th>
									<th>Agency Name</th>
									<th rowspan="2">No. of Labours on Site</th>
									<th rowspan="2">Advance (Rs.)</th>
									
									<!-- <th rowspan="2">Action</th> -->
									</tr>
									<tr>
									
									
																	
									</tr>
								</thead>
								<tbody>
								<?php 
								$i=0;
									foreach($detail_data as $req_data)
									{
									// debug($req_data);die;
								?>
									<tr id="row_id_<?php echo $i; ?>">
										<td><span id="material_code_<?php echo $i; ?>"><?php if(isset($req_data['agency_id'])){ echo $this->ERPfunction->get_vendor_code($req_data['agency_id']); } ?></span>
										<input type="hidden" value="<?php echo $i; ?>" name="row_number" class="row_number">
										</td>
										<td>
											<select class="select2 material_id" style="width: 100%;" name="agency[agency_id][]" id="material_id_<?php echo $i; ?>" data-id="<?php echo $i; ?>">
												<option value="">--Select Vendor--</Option>
												<?php 
													// foreach($agency_list as $retrive_data)
													// {
													// 	echo '<option value="'.$retrive_data['id'].'">'.
													// 	$retrive_data['agency_name'].'</option>';
													// }
													foreach($vendor_list as $retrive_data)
													{ ?>
														<option value="<?php echo $retrive_data['user_id']; ?>" <?php if($req_data['agency_id'] == $retrive_data['user_id']) echo "selected=selected"; ?>>
														<?php echo $retrive_data['vendor_name'] ?></option>
													<?php 
													}
												?>
											</select>
										</td>
										<td>
											<input type="text" name="agency[labors][]" id="" value="<?php if(isset($req_data['labor'])) echo $req_data['labor']; ?>" class="form-control"/>
										</td>
										<td>
										<input type="text" name="agency[advance_rs][]" id="" value="<?php if(isset($req_data['advance_rs'])) echo $req_data['advance_rs']; ?>" class="form-control"/>
										<input type="hidden" name="agency[id][]" id="" value="<?php echo $req_data['id']; ?>" class="form-control"/>
										</td>
										
										<!--<td>
											<span class="trash btn btn-danger" data-id="<?php echo $i; ?>"><i class="fa fa-trash"></i> Delete</span>
										</td> -->
										<!-- <td><span id="unit_name_0"></span></td> -->
										
										<td>
											<span class="trash btn btn-danger" data-id="0"><i class="fa fa-trash"></i> Delete</span>
										</td>
									</tr>
									<?php
									$i++;
									}
									?>
								</tbody>
							</table>
							<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary">UPDATE Adv.R.</button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>

         </div>
<?php
  } 
 ?>