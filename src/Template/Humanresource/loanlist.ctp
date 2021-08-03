<div class="col-md-10" >
	<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
	
	$emp_no = (isset($_REQUEST["employee_no"])) ? $_REQUEST["employee_no"] :"";
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".datep").datepicker({changeMonth: true,changeYear:true,dateFormat: 'MM yy'});
	jQuery("#userlist").DataTable();	
});
</script>
<style>
/*
table.ui-datepicker-calendar {
    display: none;
}*/
select[multiple], select[size] {
    height: 2px !important;
}
</style>
<?php 
$date = (isset($_POST["date"])) ? $_POST["date"] : "";
$project_id = (isset($_POST["project_id"])) ? $_POST["project_id"] : "";
?>
<div class="row">
<div class="col-md-12">
	<div class="block ">
		<div class="head bg-default bg-light-rtl">
			<h2>Loan Records</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		
			<div class="content">
			<div class="col-md-12 filter-form">
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
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
					<div class="col-md-1">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">
					</div>
				</div>
			<?php echo $this->Form->end();?>
			</div>
			</div>
		
		<div class="content list custom-btn-clean">	
			<table id="userlist"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Employee No</th>					
						<th>Name of Employee</th>					
						<th>Loan Amount</th>			
						<th>Outstanding<br/>Amount</th>
						<th>Loan Approve by</th>					
						<th>Remarks</th>
						<?php  if($role != "hrmanager"){ ?>
						<th>Action</th>			
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php 
					if(!empty($loan_data))
					{				
						foreach($loan_data as $user)
						{
							echo "<tr>";
							// echo "<td>{$this->ERPfunction->get_employee_no($user['user_id'])}</td>";
							echo "<td>{$user['user_id']}</td>";
							echo "<td>{$this->ERPfunction->get_user_name($user['user_id'])}</td>";
							echo "<td>{$user["amount"]}</td>";
							echo "<td>{$user["outstanding"]}</td>";
							echo "<td>{$user["approved_by"]}</td>";
							echo "<td>{$user["remarks"]}</td>";
							if($role != "hrmanager")
							{  echo "<td>";	
						
							if($this->ERPfunction->retrive_accessrights($role,'editloan')==1)
							{
								echo "<a href='{$this->request->base}/humanresource/editloan/{$user['loan_id']}' class='btn btn-clean btn-info'><i class='icon-edit'></i>Edit</a>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'deleteloan')==1)
							{
								echo"<a href='{$this->request->base}/humanresource/deleteloan/{$user['loan_id']}' class='btn btn-danger btn-clean action-btn'><i class='icon-trash'></i>Delete</a>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'payloanlisthistory')==1 )
							{
								echo "
								<a class='btn btn-primary' id='pay_loan_history' href='javascript:void(0);' user_id='{$user['user_id']}' data-url='{$this->request->base}/Ajaxfunction/payloanhistory'><i class='icon-eye-open'></i>History</a>";
							}	
								echo "</td></tr>";
							}
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

<script>
	$("body").on("click","#pay_loan_history",function(){
		
	var user_id = $(this).attr("user_id");

	var url = $(this).attr("data-url");

	var curr_data = {user_id:user_id};

	$.ajax({
		url : url,
		data : {user_id:user_id}	,
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
</script>


<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>