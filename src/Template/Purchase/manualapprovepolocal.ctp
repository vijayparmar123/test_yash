<?php
use Cake\Routing\Router;
?>
<style>
div.checker span.checked:before {
   
     top: -6px;
}
</style>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{

?>

	<div class="col-md-12">
		<div class="block" id="pr-div" style="<?php echo (isset($_REQUEST['go']))?'width:auto':''; ?>">
			<div class="head bg-default bg-light-rtl">
				<h2>P.O. ALERT (Local Purchase)</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Purchase','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		<div class="content">
		<script>
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
		jQuery('#pr_list').DataTable({"order": [[ 1, "desc" ]]});
		
		jQuery("body").on("change", ".approve", function(event){
				var pr_id = jQuery(this).val();
				
				if(confirm('Are you Sure approve this PR?'))
				{
				var curr_data = {	 						 					
	 					pr_id : pr_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvepo'));?>", /*approvepr*/
                data:curr_data,
                async:false,
                success: function(response){					
					 location.reload();
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
		});}
			else
			{				
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
				//jQuery(this).prop('checked', true);
			}
			});		
		} );
</script>

			<div class="col-md-12 filter-form">
			<?php 
$project_id = isset($request_data['project_id'])?$request_data['project_id']:'';
$from_date = isset($request_data['from_date'])?$request_data['from_date']:'';
$to_date = isset($request_data['to_date'])?$request_data['to_date']:'';
?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">					
							<div class="col-md-2">Select Project </div>
						<div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="project_id" id="project_id" class="validate[required]">
								<option value="">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($selected_project,$retrive_data['project_id']).'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>						
							<div class=""><!--  Date From : --></div>
							<div class=""><input type="hidden" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="form-control"/></div>
							<div class=""><!-- Date To : --></div>
							<div class=""><input type="hidden" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="form-control"/></div>
						 </div> 
						
					<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Go"/></div></div>
						<br>
						<br>
					</div> 
				</form>			
			</div>
			<div class="content ">
			<?php 
			// if(($project_id != ""))
			if($show_data)
			{
			/* echo $this->Form->Create('form2',['id'=>'app_frm','method'=>'post','url'=>['action'=>'preparegrn']]); */
			echo $this->Form->Create('form2',['id'=>'app_frm','method'=>'post','url'=>['controller'=>"Purchase",'action'=>'showinmanualporecords']]);
			?>
			<input type="hidden" name="action_name" value="manualapprovepolocal">
			<input type="hidden" name="selected_project_id" value="<?php echo $selected_project; ?>">
			<div id="scrolling-div">
			<table id="pr_list"  class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<!--<th>Project Code</th>
						<th>P.R. No</th>-->				
						<th>Project</th>						
						<th>P.O. No</th>						
						<th>Date</th>						
						<th>Time</th>						
						<th colspan="9" style="padding:0;">
							<table class='table-bordered' style='width:100%;'>
								<tr>
													
								<th>Vendor Name</th>						
													
								<th>Material Name</th>						
								<th>Make/<br>Source</th>						
								<th>Quantity</th>
								<th>Unit</th>
								<th>Final Rate<br>(Incl. All)</th>
								<th>Amount<br>(Incl. All)</th>
								<!-- <th>Mode of<br>Purchase</th> -->
								<th>Edit/View</th>
								<?php
								if($role == "erphead" || $role == "ceo" || $role == "md" || $role == "purchasehead" || $role == "erpmanager" || $role == "projectdirector")
								{
								?>
								<th>Approve</th>
								<?php
								}
								?>
								<?php
								if($role == "erphead" || $role == "ceo" || $role == "md" || $role == "erpmanager")
								{
								?>
								<th>Approve</th>
								<?php
								}
								?>
								<th>GO</th>
								</tr>
							</table>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					// echo ($project_id != "")?$this->ERPfunction->get_manualpo_alerts($project_id):"";
					echo $this->ERPfunction->get_manualpolocal_alerts($selected_project);
					?>
					<input type="hidden" name="po" id="po_text">
				</tbody>
			</table>
			</div>
			<?php }?>
			<div class="form-row">
			<div class="col-md-1 pull-right">
			<!-- <input type="submit" name="approve_po" value="Go" class="btn btn-success"> -->
			<?php  echo $this->Form->end();?>
			</div>
			</div>
			</div>
		</div>
		</div>
	</div>

<?php }?>
</div>
<script>
$("input[type='radio']").change(function (){
	var val = $(this).val();
	var url = $("#data-url").val();
	$(".purchase_mod option[value='"+val+"']").attr("selected","selected");
	if(val == "local")
	{
		$('#app_frm').attr("action",url+"/inventory/preparegrnwithoutpo");
	}else{
		$('#app_frm').attr("action",url+"/inventory/preparepo2");
	}
});
$(".go_btn").click(function(){
        var po_no = $(this).attr('po_no');
		$("#po_text").val(po_no);
		$( "#app_frm" ).submit();
    });
</script>