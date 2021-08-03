<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {	
	jQuery('body').on('click','.viewmodal',function(){			
			payid=jQuery(this).attr('id');
			jQuery('#modal-view').html('hello');
			 var model  = jQuery(this).attr('data-type') ;
			 var user_id  = jQuery(this).attr('user_id') ;
			 var urlstring = '';
		
		if(model == 'transferemployee')
		{
			urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'transferemployee'));?>";
		}
		if(model == 'resignemployee')
		{
			urlstring = "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'resignemployee'));?>";
		}
		if(model == 'change_balance')
		{
			urlstring = "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addleavebalance'));?>";
		}
	   var curr_data = {type : model,user_id:user_id};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:urlstring,
                data:curr_data,
                async:false,
                success: function(response){                    
					jQuery('.modal-content').html(response);					
                },
                beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
		        error: function(e) {
		                console.log(e);
		                 }
            });			
	});
	
} );
</script>


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
<?php 
$project_id = array();
$project_id[] = (isset($_POST["project_id"])) ? $_POST["project_id"] : "";
?> 
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>View Expenditure</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Humanresource','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
			<div class="content">
			<div class="col-md-12 filter-form">
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				<div class="form-row">
					
					<div class="col-md-2 text-right">Full Name Personnel</div>
					<div class="col-md-4">
					
						<?php  echo $this->form->select("user_id",$employees,["empty"=>"Select Employee","id"=>"name","class"=>"select2 employees ","style"=>"width: 100%;" ,'multiple'=>"multiple"]);?>
					
					</div>
					<div class="col-md-2 text-right">Designation</div>
					<div class="col-md-4">
						<select class="select2" style="width: 100%;" id="designation" name="designation[]" multiple="multiple">
					  <!-- <option value="All" selected>All</option>  -->
							<?php
							if(isset($designationlist)){
								foreach($designationlist as $unit_info){
								?>
						   <option value="<?php echo $unit_info['cat_id'];?>"><?php echo $unit_info['category_title'];?></option>
									<?php             
								}
							} ?>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2 text-right">Employee No</div>
					<div class="col-md-4">
						<input name="employee_no" class="form-control">
					</div>
				
					<div class="col-md-2 text-right">Employed at</div>
					<div class="col-md-4">
						<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
						<!--<option value="All" selected>All</Option> -->
						<?php 
							foreach($projects as $retrive_data)
							{
								$selected = ($retrive_data['project_id']==$project_id) ? "selected" : "";
								echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
							}
						?>
						</select>
					</div>
					
				</div>
				<div class="form-row">
					<div class="col-md-2 text-right">Expenditure Claim Period </div>
					<div class="col-md-3">
						<input name="clam_period" required="true" id="date" class="form-control  date validate[required] datep" value="<?php echo date("F Y"); ?>">
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-1">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">
					</div>
				</div>
			<?php echo $this->Form->end();?>
			</div>
			</div>
			
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({
			responsive: {
						details: {
							type: 'column',
							target: -2
						}
					},
					columnDefs: [ {
						className: 'control',
						orderable: false,
						targets:   -2
					} ],
		});
		} );

</script>


<script type="text/javascript">
jQuery('.datep,#as_on_date').datepicker({
		dateFormat: 'MM yy',
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			maxDate: new Date(),
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
</script>
			<table id="user_list" class="dataTables_wrapper table table-striped table-hover" style="width:100%;">
				<thead>
					<tr>
						<!--  <th>Image</th> 
						<th class="text-center" style="min-width:200px;">Full Name</th>
						<th>Enroll No.</th>-->
						<th>Expenditure Claim Period</th>
						<th>Employee No.</th>
						<th>First Name</th>						
						<th>Middle Name</th>
						<th>Last Name</th> 
						<th>Designation</th>
						<th>Contact No </th>
						<th>Employed at</th>
						
						<!-- <th>Status<br> of<br> Employee</th>	-->										
						<th></th>						
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						$rows = array();
						if(!empty($user_list))
						{
							$rows[] = array("Claim Period","Employee No","First Name","Middle Name","Last Name","Designation","Contact No ","Employee At");
						foreach($user_list as $retrive_data)
						{	
							$csv = array();
							$csv[] = $retrive_data['clam_period'];
							$csv[] = $retrive_data['erp_users']['pf_ref_no'];
							$csv[] = $retrive_data['erp_users']['first_name'];
							$csv[] = $retrive_data['erp_users']['middle_name'];
							$csv[] = $retrive_data['erp_users']['last_name'];
							$csv[] =  $this->ERPfunction->get_category_title($retrive_data['erp_users']['designation']);
							$csv[] = $retrive_data['erp_users']['mobile_no'];
							$csv[] = $this->ERPFunction->get_projectname($retrive_data['erp_users']['employee_at']);
							


						 ?>
							<tr>								
								<?php 
									/*echo $this->Html->image($this->ERPfunction->get_employee_image($retrive_data['user_id']),
				array('class'=>'img-circle','height'=>'50px','width'=>'50px'));*/ ?>							
								<!--<td><?php //echo $retrive_data['user_id'];?></td>-->
								<td><?php echo $retrive_data['clam_period'];?></td>
								
								<td><?php echo $retrive_data['erp_users']['pf_ref_no'];?></td>
								<td><?php echo $retrive_data['erp_users']['first_name'];?></td>								
								<td><?php echo $retrive_data['erp_users']['middle_name'];?></td>
								<td><?php echo $retrive_data['erp_users']['last_name'];?></td>
								<td><?php echo $this->ERPfunction->get_category_title($retrive_data['erp_users']['designation']);?></td>
								<td><?php echo $retrive_data['erp_users']['mobile_no'];?></td>	
								<td><?php echo $this->ERPFunction->get_projectname($retrive_data['erp_users']['employee_at']); ?></td>
								<td><?php echo $this->ERPFunction->get_pay_type($retrive_data['erp_users']['pay_type']);?></td>
								
															
								<td>
								<?php
 								if($this->ERPfunction->retrive_accessrights($role,'expenditurelist')==1)
								{							
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'expenditurelist', $retrive_data['user_id'],$retrive_data['clam_period']),
								array('class'=>'btn btn-success btn-clean','escape'=>false,"target"=>"_blank"));
								}
								
								
								echo ' ';
							
								
								if($this->ERPfunction->retrive_accessrights($role,'deleteexpenditure')==1)
								{
									echo' ';
									echo $this->Html->link("<i class='icon-pencil'></i> Delete",array('action' => 'deleteexpenditure', $retrive_data['user_id'],$retrive_data['clam_period']),
									array('class'=>'btn  btn-danger btn-clean','escape'=>false,
									'confirm' => 'Are you sure you wish to delete this Record?'));
								}
								
								?>
								</td>
							</tr>
						<?php
						$i++;

							$rows[] = $csv;
						}

					}

					?>

				</tbody>
			</table>
			<div class="content">
			
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			</form>
			</div>
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
			</form>
			</div>
			</div>
		</div>
		</div>
	</div>
</div>
<?php  } ?>

	
</div>

