<?php
use Cake\Routing\Router;
use Cake\Network\Session\DatabaseSession;
$user_r=$this->request->session()->read('role');

?>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>
<div class="row">
	<div class="col-md-12">
		<div class="block" >			
			<div class="head bg-default bg-light-rtl">
			<h2>M.R.N Alert</h2>
			<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<div class="content">
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

		jQuery(document).ready(function() {
		jQuery("#user_form").validationEngine();
		jQuery('#from_date,#to_date').datepicker({
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'ingrnprojectdetaillppo'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					return false;
                },
                error: function (e) {
                     alert('Error');
					 console.log(e.responseText);
                }
            });	
	});
		jQuery('#pr_list').DataTable({"order": [[ 1, "desc" ]]});
		
			
		} );
	
	function check_select()
	{
		//check item is actually selected or not.
		return true;
	}
</script>

		<div class="col-md-12 filter-form">
			<?php 
@$project_id = isset($request_data['project_id'])?$request_data['project_id']: $this->request->params["pass"]["0"];
$from_date = isset($request_data['from_date'])?$request_data['from_date']:'';
$to_date = isset($request_data['to_date'])?$request_data['to_date']:'';
?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">  
						<div class="col-md-2">Project Code:</div>
                            <div class="col-md-3"><input type="text" name="project_code" id="project_code" value="<?php echo (isset($selected_pl))?$this->ERPfunction->get_projectcode($project_id):"";?>"
							class="form-control" value="" readonly="true"/></div>                        
					<div class="col-md-2">Select Project</div>
						<div class="col-md-3">
							<select class="select2" style="width: 100%;" name="project_id" id="project_id">
								<!-- <option value="">--Select Project--</Option> -->
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($project_id,$retrive_data['project_id']).'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
						</div>
					 <div class="col-md-2"><!--  Date From : --></div>
                        <div class="col-md-2"><input type="hidden" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="form-control"/></div>
						<div class="col-md-2"><!-- Date To : --></div>
                        <div class="col-md-2"><input type="hidden" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="form-control"/></div>

					<div class="col-md-2"><input type="submit" name="go" id="go" class="btn btn-primary" value="Go" /></div>
						<br>
						<br>
					</div>
				</form>			
			</div>
			</div>
		
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
			jQuery('#mrn_list').DataTable({responsive: {
									details: {
										type: 'column',
										target: -1
									}
								},
								columnDefs: [ {
									className: 'control',
									orderable: false,
									targets:   -1
								} ]});
			
			jQuery("body").on("change", ".approve", function(event){
				var mrn_id = jQuery(this).val();
				var data_role = jQuery(this).attr('data-role');
				var material_id = jQuery(this).attr("material_id");
				var quantity = jQuery(this).attr("qty");
				var project_id = jQuery(this).attr("data-project_id");
				var mrn_detail_id = jQuery(this).attr("mrn_detail_id");
				
				if(confirm('Are you Sure approve this M.R.N.?'))
				{
				if(confirm('Are you Sure approve this M.R.N.?'))
				{
				
				var curr_data = {	 						 					
	 					mrn_id : mrn_id,
						data_role :data_role,
						material_id : material_id,
						quantity : quantity,
						project_id : project_id,
						mrn_detail_id : mrn_detail_id
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvemrn'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					
					 location.reload();
					return false;
                },
                error: function (e) {
					console.log(e.responseText);
                     alert('Error');
                }
		});}else
			{
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
		   }
			else
			{
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
			});	
		} );
</script>
			<table id="mrn_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<!-- <th>Project Code</th> -->
						<th>M.R.N. No</th>						
						<th>Date</th>					
						<th>Time</th>
						
						<th>Vendor Name</th>
						
						<th>Material Name</th>
						<th>Make/Source</th>
						<th>Returned Quantity</th>
						<th>Unit</th>
						<th>Edit/View</th>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'approvemrn_inv')==1)
						{
						?>
						<th>Approved</th>
						<?php
						}
						?>
						<th></th>
					<!-- <th>Approved by Accounts</th>
						<th>Approved by Executives</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
						if(isset($mrn_list))
						{
						$i = 1;
						foreach($mrn_list as $retrive_data)
						{
						?>
							<tr>								
								<!-- <td><?php /*echo $this->ERPfunction->get_projectcode($retrive_data['project_id']);*/?></td> -->
								<td><?php echo $retrive_data['mrn_no'];?></td>								
								<td><?php echo $this->ERPfunction->get_date($retrive_data['mrn_date']);?></td>														
								<td><?php echo $retrive_data['mrn_time'];?></td>														
																						
								<td><?php echo $this->ERPfunction->get_vendor_name($retrive_data['vendor_user']);?></td>														
																							
								<td><?php echo $this->ERPfunction->get_material_title($retrive_data['material_id']);?></td>													
								<td><?php echo $this->ERPfunction->get_brandname($retrive_data['brand_id']);?></td>													
								<td><?php echo $retrive_data['quantity'];?></td>													
								<td><?php echo $this->ERPfunction->get_items_units($retrive_data['material_id']);?></td>
								<td>
								<?php 
								
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewmrn', $retrive_data['mrn_id']),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								
								if($this->ERPfunction->retrive_accessrights($role,'editmrn')==1)
								{
								echo $this->Html->link("<i class='icon-edit'></i> Edit",array('action' => 'editmrn', $retrive_data['mrn_id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								}
								echo '';
								if($this->ERPfunction->retrive_accessrights($role,'deletemrn')==1)
								{
								echo $this->Html->link('<i class="icon-trash"></i> Remove',array('action' => 'deletemrn', $retrive_data['mrn_detail_id']),
								array('class'=>'btn btn-danger btn-clean action-btn','style'=>'padding-right:35px','escape'=> false,
								'confirm' => 'Are you sure you wish to remove this Record?'));
								}
								?>
								</td>
								<?php
								if($this->ERPfunction->retrive_accessrights($role,'approvemrn_inv')==1)
								{
								?>
								<td>
									<div class="checkbox" style="display: inline-block;">
										<label><input type="checkbox" class="approve" data-role="bycm" 
										qty="<?php echo $retrive_data["quantity"];?>" material_id="<?php echo $retrive_data["material_id"];?>" data-project_id="<?php echo $retrive_data['project_id'];?>" mrn_detail_id="<?php echo $retrive_data["mrn_detail_id"];?>"
										value="<?php echo $retrive_data['mrn_id'];?>" 
										<?php echo $this->ERPfunction->checked($retrive_data['approve_cm'],1);?>
										<?php if($retrive_data['approve_cm'])echo 'disabled="disabled"';?>
										name="Approve" />
										</label>
									</div>
								</td>
								<?php } ?>
								<td></td>
								<!--
								<td>
									<div class="checkbox">
										<label><input type="checkbox" class="approve" data-role="byac" 
										value="<?php echo $retrive_data['mrn_id'];?>" 
										<?php echo $this->ERPfunction->checked($retrive_data['approve_accountant'],1);?>
										<?php if($retrive_data['approve_accountant'])echo 'disabled="disabled"';?>
										name="Approve"/> Approve</label>
									</div>
								</td>	
								<td>
									<div class="checkbox">
										<label><input type="checkbox" class="approve" data-role="byexecute" 
										value="<?php echo $retrive_data['mrn_id'];?>" 
										<?php echo $this->ERPfunction->checked($retrive_data['approve_executives'],1);?>
										<?php if($retrive_data['approve_executives'])echo 'disabled="disabled"';?>
										name="Approve"/> Approve</label>
									</div>
								</td> -->
								</tr>
						<?php
						$i++;
						}
						}
					?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>