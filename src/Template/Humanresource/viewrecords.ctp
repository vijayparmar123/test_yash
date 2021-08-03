<script>
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	$(document).ready(function(){
		$("body").on("click","#deploy_history",function(){
			var user_id = $(this).attr("user_id");
			var url = $(this).attr("data-url");
			var curr_data = {user_id:user_id};

			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				url : url,
				data : curr_data,
				type : "POST",
				async:false,
				success : function(response){
					$('.modal-content').html('');
					$('.modal-content').html(response);
					$('#load_modal').modal('show');
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		});


		$("body").on("click","#payment_history",function(){
			var user_id = $(this).attr("user_id");
			var url = $(this).attr("data-url");
			var curr_data = {user_id:user_id};

			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				url : url,
				data : curr_data,
				type : "POST",
				async:false,
				success : function(response){
					$('.modal-content').html('');
					$('.modal-content').html(response);
					$('#load_modal').modal('show');
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		});

		$("body").on("click","#pay_structure_history",function(){
			var user_id = $(this).attr("user_id");
			var url = $(this).attr("data-url");
			var curr_data = {user_id:user_id};

			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				url : url,
				data : curr_data,
				type : "POST",
				async:false,
				success : function(response){
					$('.modal-content').html('');
					$('.modal-content').html(response);	
					$('#load_modal').modal('show');
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		});

		$("body").on("click","#designation_history",function(){
			var user_id = $(this).attr("user_id");
			var url = $(this).attr("data-url");
			var curr_data = {user_id:user_id};

			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				url : url,
				data : curr_data,
				type : "POST",
				async:false,
				success : function(response){
					$('.modal-content').html('');
					$('.modal-content').html(response);	
					$('#load_modal').modal('show');
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		});

		$("body").on("click","#salary_statement",function(){
			var user_id = $(this).attr("user_id");
			var url = $(this).attr("data-url");
			var curr_data = {user_id:user_id};

			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				url : url,
				data : curr_data,
				type : "POST",
				async:false,
				success : function(response){
					$('.modal-content').html('');
					$('.modal-content').html(response);	
					$('#load_modal').modal('show');
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		});

		$("body").on("click","#history_clam",function(){
			var user_id = $(this).attr("user_id");
			var id = user_id;
			var url = $(this).attr("data-url");
			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				url : url,
				data:{id:id},
				type : "POST",
				async:false,
				success : function(response){
					$('.modal-dialog').css("width","1076px");
					$('.modal-content').html('');
					$('.modal-content').html(response);
					$('#load_modal').modal('show');
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		});

		$("body").on("click","#pay_record",function(){
			var user_id = $(this).attr("user_id");
			var url = $(this).attr("data-url");
			var curr_data = {user_id:user_id};

			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				url : url,
				data : curr_data,
				type : "POST",
				async:false,
				success : function(response){
					$('.modal-dialog').css("width","1076px");
					$('.modal-content').html('');
					$('.modal-content').html(response);
					$('#load_modal').modal('show');
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		});
	});
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
	<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>View Records</h2>
			<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Humanresource','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>		
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post']);?>
			        <div class="content controls">											
						<div class="form-row">
					
					<div class="col-md-2 text-right">Name</div>
					<div class="col-md-4">
						<select class="select2" style="width: 100%;" id="user_id" name="user_id[]" multiple="multiple">
					  <!-- <option value="All" selected>All</option>  -->
							<?php
							if(isset($name_list)){
								foreach($name_list as $retrive_data){
								?>
						   <option value="<?php echo $retrive_data['user_id'];?>"><?php echo $retrive_data['first_name'];?></option>
									<?php             
								}
							} ?>
						</select>
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
					<div class="col-md-2 text-right">Pay Type</div>
					<div class="col-md-4">
						<select name="pay_type" style="width: 100%;" class="select2">
							<option value="All" selected>All</option>
							<option value="employee">Employee</option>
							<option value="consultant">P.T. Employee</option>
							<option value="temporary">Temporary</option>
						</select>
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
					<div class="col-md-2 text-right">Employee No</div>
					<div class="col-md-4">
						<input name="employee_no" class="form-control">
					</div>
					<div class="col-md-2 text-right">Status</div>
					<div class="col-md-4">
						<select name="status" style="width: 100%;" class="select2">
							<!--<option value="All" selected>All</option>-->
							<option value="working">Working </option>
							<option value="resigned">Resigned</option>
						</select>
					</div>
					
				</div>
					<div class="form-row">
					<div class="col-md-2 text-right">Mobile No</div>
					<div class="col-md-4">
						<input name="mobile_no" class="form-control">
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-1">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">
					</div>
				</div>
					<?php echo $this->Form->end();?>	
					
		<div class="content list" >		
		<script>
				jQuery(document).ready(function() {
					jQuery('#user_list').DataTable({responsive: {
						details: {
							type: 'column',
							target: -1
						}
					},
					columnDefs: [ {
						className: 'control',
						orderable: false,
						targets:   -1
					} ],});
				});
		</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<!-- <th><?php echo __('Enroll No.');?></th>-->	
						<th><?php echo __('Status');?></th>
						<th><?php echo __('Employee No');?></th>						
						<th><?php echo __('First Name');?></th>
						<th><?php echo __('Middle Name');?></th>
						<th><?php echo __('Last Name');?></th>
						<th><?php echo __('Mobile No');?></th>	
						<th><?php echo __('Education');?></th>
						<th><?php echo __('Designation');?></th>						
						<th><?php echo __('Employee At');?></th>								
						<th><?php echo __('Pay Type');?></th>	
						<th><?php echo __('CTC<br>(Month)(Rs.)');?></th>			
						<th><?php echo __('CTC(Year)(Rs.)');?></th>		
						<?php  if($this->ERPfunction->retrive_accessrights($role,'viewemployee')==1 )
						{ ?>
						<th><?php echo __('Personal Details');?></th>	
						<?php } 
						if($this->ERPfunction->retrive_accessrights($role,'deploymenthistory')==1 )
						{
						?>
						<th><?php echo __('Transfer History');?></th>
						<?php } 
						if($this->ERPfunction->retrive_accessrights($role,'paystructurehistory')==1 )
						{
						?>
						<th><?php echo __('Pay Structure History');?></th>
						<?php } 
						if($this->ERPfunction->retrive_accessrights($role,'payrecords')==1 )
						{
						?>
						<th><?php echo __('Pay Records');?></th>
						<?php } 
						if($this->ERPfunction->retrive_accessrights($role,'designationhistory')==1 )
						{
						?>
						<th><?php echo __('Designation History');?></th>
						<?php } 
						if($this->ERPfunction->retrive_accessrights($role,'salarystatement')==1 )
						{
						?>
						<th><?php echo __('Salary Statement');?></th>
						<?php } 
						if($this->ERPfunction->retrive_accessrights($role,'history_clam')==1)
						{
						?>
							<th><?php echo __('Expenditure Claim History');?></th>
						<?php
						} 
						?>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php 
				$rows = array();
					if(!empty($employees))
					{
						$rows[] = array("Status","Employee No","First Name","Middle Name","Last Name","Mobile No","Education","Designation","Employee At","Pay Type","CTC(Month)(Rs.)"
						,"CTC(Year)(Rs.)");
						foreach($employees as $data)
						{
							$csv = array();
							echo "<tr>";							
							?>
							<td><?php echo ($data['is_resign']) ? "<span class='label label-danger'>Resigned</span>" : "<span class='label label-success'>Working</span>"; ?></td>
							<?php 
							$csv[] = $data['is_resign']?'Resigned':'Working';
							echo "
							<td>". ($csv[] = $data['user_identy_number']) ."</td>
							<td>". ($csv[] = $data['first_name']) ."</td>
							<td>". ($csv[] =$data['middle_name']) ."</td>
							<td>". ($csv[] =$data['last_name']) ."</td>
							<td>". ($csv[] =$data['mobile_no']) ."</td>							
							<td>". ($csv[] =$data['education']) ."</td>	
							<td>". ($csv[] =$this->ERPfunction->get_category_title($data['designation'])) ."</td>
							<td>". ($csv[] =$this->ERPfunction->get_projectname($data['employee_at'])) ."</td>							
							<td>". ($csv[] =$this->ERPFunction->get_pay_type($data['pay_type']))."</td>
							<td>". ($csv[] =$data['total_salary']) ."</td>
							<td>". ($csv[] =$data['ctc']) ."</td>";
							 if($this->ERPfunction->retrive_accessrights($role,'viewemployee')==1 )
							{
							echo "<td><a class='btn btn-primary' target='_blank' href='{$this->request->base}/Humanresource/viewemployee/{$data['user_id']}'>View</a></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'deploymenthistory')==1 )
							{
							echo "
							<td><a class='btn btn-primary' id='deploy_history' href='javascript:void(0);' user_id='{$data['user_id']}' data-url='{$this->request->base}/Ajaxfunction/deploymenthistory'>View</a></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'paystructurehistory')==1 )
							{
							echo "
							<td><a class='btn btn-primary' id='pay_structure_history' href='javascript:void(0);' user_id='{$data['user_id']}' data-url='{$this->request->base}/Ajaxfunction/paystructurehistory'>View</a></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'payrecords')==1 )
							{
							echo "
							<td><a class='btn btn-primary' id='pay_record' href='javascript:void(0);' user_id='{$data['user_id']}' data-url='{$this->request->base}/Ajaxfunction/payrecords'>View</a></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'designationhistory')==1 )
							{
							echo "
							<td><a class='btn btn-primary' id='designation_history' href='javascript:void(0);' user_id='{$data['user_id']}' data-url='{$this->request->base}/Ajaxfunction/designationhistory'>View</a></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'salarystatement')==1 )
							{
							echo "
							<td><a class='btn btn-primary' id='salary_statement' href='javascript:void(0);' user_id='{$data['user_id']}' data-url='{$this->request->base}/Ajaxfunction/salarystatement'>View</a></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'history_clam')==1)
							{
								echo "<td><a class='btn btn-primary' id='history_clam' href='javascript:void(0);' user_id='{$data['user_id']}' data-url='{$this->request->base}/Ajaxfunction/ExpenditureHistory'>View</a></td>";
							}
							
							echo "<td></td>							
							</tr>";
							$rows[] = $csv;
							
							
						}
					}
				?>
				</tbody>
			</table>
			<div class="content">
			<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
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
<?php } ?>
</div>