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
			<h2>Attendace</h2>
			<div class="pull-right">
			<a href="<?php echo $_SERVER["HTTP_REFERER"];?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		<div class="content controls">			
			<div class="form-row">
				<div class="col-md-2">Employee No :</div>
				<div class="col-md-4">
					<input type="text" name="employee_no" value="" class="form-control validate[required]" readonly="true"/>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Employee Name :</div>
				<div class="col-md-4">
					<?php echo $this->form->select("user_id",$employees,["empty"=>"Select Employee","class"=>"select2 validate[required]","style"=>"width:100%"]);?>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2"></div>
				<div class="col-md-4"><button type="submit" name="load_attendance" class="btn btn-primary">Go</button></div>
			</div>			
			
			<div class="form-row"><hr/></div>
			
			<?php 
			if(isset($this->request->data["load_attendance"]))
			{ ?>
				
				<div class="form-row">
					<div class="col-md-2">Attendace Date :</div>
					<div class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date("d-m-Y");?></div>
				</div>
				<div class="form-row">
					<div class="col-md-2">Day In :</div>
					<div class="col-md-4">
						<?php
						if($day_started)
						{
							echo $day_in_time;
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
							echo $day_out_time;
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
							echo $working_hours;
						}else{
							echo "00:00:00";
						} ?>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2"></div>
					<div class="col-md-4"><br><button type="submit" name="save_attendance" class="btn btn-primary"><?php echo $button_text;?></button></div>
				</div>
	  <?php } ?>
		</div>
		<?php echo $this->form->end(); ?>			
	</div>
<?php } ?>     
</div>
						