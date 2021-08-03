<script>
	jQuery("body").ready(function(){
	jQuery("#user_form").validationEngine();
	jQuery("#datepick").datepicker({changeYear:true,changeMonth:true,dateFormat: 'dd-mm-yy',maxDate: new Date()});
	
});
</script>
<?php 
$created_by = ($edit)?$this->ERPfunction->get_full_user_name($data["created_by"]):'';
$last_edit_by = ($edit)?$this->ERPfunction->get_full_user_name($data["last_edited_by"]):'';
?>
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
	<div class="block block-fill-white">
		<div class="head bg-default bg-light-rtl">
			<h2><?php echo $form_header;?> </h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		<div class="content controls">		
			<div class="form-row">
				<div class="col-md-2">Employee Name *</div>
				<div class="col-md-4">
					<?php echo $this->form->select("user_id",$employees,["default"=>($edit)?[$data["user_id"]]:"","empty"=>"Select Employee","class"=>"select2 employees","style"=>"width:100%","required"=>true]);?>
				</div>
			</div>
			<div class="form-row">
					<div class="col-md-2">Loan Amount *</div>
					<div class="col-md-4">
						<input type="text" name="amount" value="<?php echo ($edit)? $data["amount"]:""?>" class="form-control validate[required,custom[number],min[0]]" />
					</div>
			</div>
			<div class="form-row">
					<div class="col-md-2">Given Date </div>
					<div class="col-md-4">
						<input type="text" name="given_date" value="<?php echo ($edit)? date('d-m-Y',strtotime($data["given_date"])):""?>" id="datepick" class="form-control" />
					</div>
			</div>
			<div class="form-row">
					<div class="col-md-2">Loan Approved By</div>
					<div class="col-md-4">
						<input type="text" name="approved_by" value="<?php echo ($edit)? $data["approved_by"]:""?>" class="form-control" />
					</div>
			</div>
			<div class="form-row">
					<div class="col-md-2">Remarks</div>
					<div class="col-md-4">
						<input type="text" name="remarks" value="<?php echo ($edit)? $data["remarks"]:""?>" class="form-control" />
					</div>
			</div>
			<div class="form-row">
					<div class="col-md-2">Installment *</div>
					<div class="col-md-4">
						<input type="text" name="installment" value="<?php echo ($edit)? $data["installment"]:""?>" class="form-control validate[required,custom[number],min[0]]" />
					</div>
			</div>
			
			
			<div class="form-row">
				<div class="col-md-2"></div>
				<div class="col-md-4"><button type="submit" name="load_attendance" class="btn btn-primary">Save Loan</button></div>
			</div>		

		<?php echo $this->form->end(); ?>	
			<?php
			if($edit)
			{
			?>
			<div class='col-md-3'>
				<?php echo "Created By :".$created_by; ?>
			</div>
			<div class='col-md-4'>
				<?php echo "Last Edited By :".$last_edit_by; ?>
			</div>
			<?php
			}
			?>
			
	</div>
<?php } ?>     
</div>
						