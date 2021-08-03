<div class="col-md-10" >
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".datep").datepicker({changeMonth: true,changeYear:true,dateFormat: 'MM yy',maxDate: new Date()});
	
	jQuery('#employee_id').select2();
	
	jQuery("body").on("change", ".type", function(){
			var type  = jQuery(this).val() ;
			
			if(type == 'all')
			{
				$(".selected_users_div").css('display','none');
				$("#employee_id").prop('required',false);
			}
			else
			{
				$(".selected_users_div").css('display','block');
				$("#employee_id").prop('required',true);
			}				 				
		});
});
</script>
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
			<h2>Generate Attendace Records</h2>
			<div class="pull-right">
			
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		<div class="content controls">			
			<div class="form-row">
				<div class="col-md-2">Month & Year * :</div>
				<div class="col-md-4">
					<input name="date" required="true" class="form-control validate[required] datep" value="<?php echo date("F Y"); ?>">
				</div>
			</div>
			<div class="form-row" >
				<div class="col-md-2"></div>
				<div class="col-md-10">
					<div class="radiobox-inline" style="padding:0 19px;">
						<label><input type="radio" checked name="type" class="type" value="all" /> All</label>
					</div>
					<div class="radiobox-inline" style="padding:0 19px;">
						<label><input type="radio" name="type" value="selected" class="type" />Selected Employee</label>
					</div>
				</div>
			</div>
			<div class="form-row selected_users_div" style="display:none;">
				<div class="col-md-2">Employee * :</div>
				<div class="col-md-4">
					<select class="select2" style="width: 100%;" name="employee_id[]" id="employee_id" multiple="multiple">
						<?php 
							foreach($employee as $retrive_data)
							{
								echo '<option value="'.$retrive_data['user_id'].'">'.
								$this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
							}
						?>
					</select>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2"></div>
				<div class="col-md-4"><button type="submit" name="generate" class="btn btn-primary">Generate</button></div>
			</div>			
			
			<div class="form-row"><hr/></div>
			
		</div>
		<?php echo $this->form->end(); ?>			
	</div>
<?php 
 }
 ?>     
</div>
						