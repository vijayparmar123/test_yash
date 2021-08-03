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
						<h2> Add Advance  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php ?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name *</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Adv.R.No</div>
                            <div class="col-md-4">
								<input type="text" name="prno" id="prno" value="" class="form-control" value=""/>
							</div>
                        
                            <div class="col-md-1 text-right">Date</div>
                            <div class="col-md-2"><input type="text" name="pr_date" id="pr_date" 
							value="<?php echo $this->ERPfunction->get_date(date('Y-m-d'));?>" class="form-control" value=""/></div>
							 <div class="col-md-1 text-right">Time</div>
                            <div class="col-md-2"><input type="text" name="pr_time" id="pr_time" 
							value="<?php echo date('H:i');?>" class="form-control" value=""/></div>
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
						 
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th>Agency Id</th>
									<th>Agency Name</th>
									<th rowspan="2">No. of Labours on Site</th>
									<th rowspan="2">Advance (Rs.)</th>
									
									<th rowspan="2">Action</th>
									</tr>
									<tr>
									
									
																	
									</tr>
								</thead>
								<tbody>
									<tr id="row_id_0">
										<td><span id="material_code_0"></span>
										<input type="hidden" value="0" name="row_number" class="row_number">
										</td>
										<td>
											<select class="select2 material_id" style="width: 100%;" name="agency[agency_id][]" id="material_id_0" data-id="0">
												<option value="">--Select Vendor--</Option>
												<?php 
													// foreach($agency_list as $retrive_data)
													// {
													// 	echo '<option value="'.$retrive_data['id'].'">'.
													// 	$retrive_data['agency_name'].'</option>';
													// }
													foreach($vendor_list as $retrive_data)
													{
														echo '<option value="'.$retrive_data['user_id'].'">'.
														$retrive_data['vendor_name'].'</option>';
													}
												?>
											</select>
										</td>
										<td>
											<input type="text" name="agency[labors][]" id="" value="" class="form-control"/>
										</td>
										<td>
										<input type="text" name="agency[advance_rs][]" id="" value="" class="form-control"/>
										</td>
										
										<td>
											<span class="trash btn btn-danger" data-id="0"><i class="fa fa-trash"></i> Delete</span>
										</td>
										<!-- <td><span id="unit_name_0"></span></td> -->
			
										
									</tr>
								</tbody>
							</table>
							<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary">PREPARE Adv.R.</button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>
<?php
  } 
 ?>