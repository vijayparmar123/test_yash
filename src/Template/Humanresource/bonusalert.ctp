
<div class="col-md-10" >
	<?php 
 if(!$is_capable)
 {
	 $this->ERPfunction->access_deniedmsg();
 }
 else
 {
?>


<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".current_year").datepicker({
    changeYear: true,
    dateFormat: 'MM yy',
    
     onClose: function() {
        var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        
        $(this).datepicker('setDate', new Date(iYear, 3, 1));
		
	
		var date2 = new Date(iYear, 3, 1);
            date2.setMonth(date2.getMonth() - 13);
			// alert(date2);
            $('.previous_year').datepicker('setDate', date2);
            // alert(date2);
            $('.previous_year').datepicker('option', 'minDate', date2);
     },
	  onSelect: function (date) {
		  alert('hi');
		  var date2 = new Date(iYear, 3, 1);
            date2.setMonth(date2.getMonth() - 13);
            $('.previous_year').datepicker('setDate', date2);
            // sets minDate to dt1 date + 1
            $('.previous_year').datepicker('option', 'minDate', date2);
        },
		
     beforeShow: function() {
       if ((selDate = $(this).val()).length > 0) 
       {
          iYear = selDate.substring(selDate.length - 4, selDate.length);
          iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), $(this).datepicker('option', 'monthNames'));
          $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
           $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
       }
    }
    }); 
	
	jQuery(".previous_year").datepicker({
    changeYear: true,
    dateFormat: 'MM yy',
    });
	jQuery("#userlist").DataTable();
});
</script>
<style>
/*
table.ui-datepicker-calendar {
    display: none;
}
.ui-datepicker select.ui-datepicker-month, .ui-datepicker select.ui-datepicker-year {
    width: 49%;
    display: inline-block;
	margin-left:2px;
} */
.ui-datepicker-calendar {
    display: none;
}​

</style>

<div class="row">
<div class="col-md-12">
<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Bonus Alert</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<div class="content">
			<div class="col-md-12 filter-form">
			<?php echo $this->Form->Create('search',['id'=>'search','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			
				<div class="form-row">
					<div class="col-md-2 text-right">Month & Year</div>
					<div class="col-md-3">
						<input name="current_year" class="form-control validate[required] current_year" required="true" value="<?php echo isset($current_month_year)?$current_month_year:""; ?>" 
autocomplete="off">
					</div>
					
					<div class="col-md-2 text-right">Month & Year</div>
					<div class="col-md-3">
						<input name="previous_year" class="form-control validate[required] previous_year" required="true" value="<?php echo isset($previous_month_year)?$previous_month_year:""; ?>" autocomplete="off">
					</div>
				</div>
				
				<div class="form-row">					
					<div class="col-md-2 text-right">Employee At</div>
					<div class="col-md-3">
						<select class="select2" required="true" style="width: 100%;" name="project_id" id="project_id" >
						<?php 
							foreach($projects as $retrive_data)
							{ 
								$selected = ($retrive_data['project_id']==$project_id) ? "selected" : "";
								echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
							}
						?>
					</select>
					</div>
					
					<div class="col-md-1">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">
					</div>
				</div>
				
			<?php $this->Form->end(); ?>
		</div>
		</div>
		<div class="content list custom-btn-clean">
			<table id="userlist"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Employee at</th>
						<th>Employee No</th>								
						<th>Name of Employee</th>					
						<th>Designation</th>		
						<th>Action</th>					
					</tr>
				</thead>
				<tbody>
					<?php 
					if(!empty($users))
					{		
						foreach($users as $user)
						{
							echo "<tr>";
							echo "<td>".$this->ERPfunction->get_projectname($user['employee_at'])."</td>";
							echo "<td>".$user['user_identy_number']."</td>";
							echo "<td>".$user['first_name'].' '.$user['middle_name'].' '.$user['last_name']."</td>";
							echo "<td>".$this->ERPfunction->get_category_title($user['designation'])."</td><td>";
							if($this->ERPfunction->retrive_accessrights($role,'generatebonus')==1)
							 {
							echo "<a href='{$this->request->base}/humanresource/generatebonus/{$user['user_id']}/{$current_year}/{$previous_year}' target='_blank' class='btn btn-clean btn-primary'><i class='icon-money'></i>Generate Bonus</a>";
							 }
							
							echo "</td>";
							echo "</tr>";
								
						}
					}
					?>
				</tbody>
			</table>
			
		</div>
</div>
</div>
</div>
<?php 
} ?>
</div>