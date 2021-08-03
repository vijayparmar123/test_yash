<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#attendance_date').datepicker({
		dateFormat: "dd-mm-yy",
		changeMonth: true,
		maxDate: new Date(),
		minDate: '-30d',
		changeYear: true,
		yearRange:'-65:+0',
		onChangeMonthYear: function(year, month, inst) {
			jQuery(this).val(month + "-" + year);
		}                    
	});
} );
</script>
<script>
	jQuery("#user_form").validationEngine();
		
	jQuery("body").on("change",".employees",function(){
		var eid = jQuery(this).val();
		jQuery(".employee_no").val("");
		if(eid == "")
		{
			return false;
		}
		var data = {eid:eid};
		
		jQuery.ajax({
			type : "POST",
			url : "<?php echo $this->request->base;?>/ajaxfunction/getemployeeno",
			data : data,
			success : function(name)
			{
				jQuery(".employee_no").val(name);
			},
			error : function(e)
			{
				console.log(e.responseText);
			}
		})
	});
</script>
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
				<div class="col-md-2">Employee No :</div>
				<div class="col-md-4">
					<input type="text" name="employee_no" value="<?php echo $emp_no;?>" class="employee_no form-control validate[required]" readonly="true"/>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Employee Name :</div>
				<div class="col-md-4">
					<?php echo $this->form->select("user_id",$employees,["empty"=>"Select Employee","class"=>"select2 employees","style"=>"width:100%","required"=>true]);?>
				</div>
			</div>
			<?php
			if($role != "erphead"){
			?>
			<div class="form-row">
					<div class="col-md-2">Attendace Date :</div>
					<input type="hidden" name="attendance_date" value="<?php echo date("d-m-Y");?>" class="form-control"/>
					<div class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo date("d-m-Y");?></strong></div>
			</div>
			<?php } else { ?>
			<div class="form-row">
				<div class="col-md-2">Attendace Date :</div>
				<div class="col-md-4">
					<input type="text" name="attendance_date" required id="attendance_date" class="form-control" value="<?php echo isset($attendance_date)?$attendance_date:''; ?>"/>
				</div>
			</div>
			<?php } ?>
			<div class="form-row">
				<div class="col-md-2"></div>
				<div class="col-md-4"><button type="submit" name="load_attendance" class="btn btn-primary">Go</button></div>
			</div>			
			
			<div class="form-row"><hr/></div>
			
			<?php 
			if(isset($this->request->data["load_attendance"]))
			{ ?>
				
				<!-- <div class="form-row">
					<div class="col-md-2">Attendace Date :</div>
					<div class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date("d-m-Y");?></div>
				</div> -->
				<div class="form-row">
					<div class="col-md-2">Day In :</div>
					<div class="col-md-4">
						<?php
						if($day_started)
						{
							echo "<strong>Day punch at ".$day_in_time."</strong>";
						}else{?>
							<button type="submit" name="day_in" class="btn btn-info">Day In</button>
						<?php } ?>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">Day Out :</div>
					<div class="col-md-4">
						<?php
						if(!$day_started)
						{
							echo "Day not started yet.";
						}
						else if($day_out_time != "")
						{
							echo "<strong>Day ended at ".$day_out_time."</strong>";
						}
						else
						{ ?>
							<button type="submit" name="day_out" class="btn btn-info">Day Out</button>
						<?php } ?>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2">Total Hours :</div>
					<div class="col-md-4">
						<?php
						if($working_hours != "")
						{
							echo "<strong>".$working_hours."</strong>";
						}else{
							echo "<strong>00:00:00</strong>";
						} ?>
					</div>
				</div>
				<!--
				<div class="form-row">
					<div class="col-md-2"></div>
					<div class="col-md-4"><br><button type="submit" name="save_attendance" class="btn btn-primary"><?php echo $button_text;?></button></div>
				</div> -->
	  <?php } ?>
		</div>
		<?php echo $this->form->end(); ?>			
	</div>
<?php } ?>     
</div>
						