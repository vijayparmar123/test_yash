<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.datep').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
});
</script>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>              
<div class="col-md-12">
<div class="row">
	
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Purchase Rate Alert</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Purchase','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
		<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				$project_id = array();
				$material_id_a = array();
				$vendor_userid_a = array();
				 $project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
				 $from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
				 $to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
				 $material_id_a = isset($_POST['material_id'])?$_POST['material_id']:'';
				 $vendor_userid_a = isset($_POST['vendor_userid'])?$_POST['vendor_userid']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Valid Date From -</div>
                        <div class="col-md-4"><input type="text" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">Valid Date To -</div>
                        <div class="col-md-4"><input type="text" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="datep form-control"/></div>
					</div>
					<div class="form-row">	
						<div class="col-md-2">Project Name:</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										$selected = (in_array($retrive_data['project_id'],$project_id)) ? "selected" : "";
										echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-2">Material Name:</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($material_list as $retrive_data)
									{
										$selected = (in_array($retrive_data['material_id'],$material_id_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['material_id'].'" '.$selected.'>'.
										$retrive_data['material_title'].'</option>';
									}
								?>
							</select>
						</div>
                    </div>
					
					<div class="form-row">	
						<div class="col-md-2">Vendor Name:</div>
                        <div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="vendor_userid[]" id="vendor_userid" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($vendor_department as $retrive_data)
									{
										$selected = (in_array($retrive_data['user_id'],$vendor_userid_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['user_id'].'" '.$selected.'>'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';
									}
								?>
							</select>
						</div>
					</div>
					
							
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php $this->Form->end(); ?>
			</div>
			</div>
		
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#rate_list').DataTable({responsive: true});
		} );
</script>
			<table id="rate_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Date From</th>
						<th>Date To</th>
						<th>Project Name</th>
						<th>Vendor Name</th>
						<th>Material Name</th>						
						<th>Final Rate</th>						
						<th>Unit</th>						
						<th>All Taxes & <br> Duties</th>						
						<th>Loading & <br> Transportation <br> (F.O.R)</th>						
						<th>Unloading</th>						
						<th>Action</th>	
					</tr>
				</thead>
				<tbody>
					<?php
						if(isset($rate_record))
						{
						$rows = array();
						$rows[] = array("Date From","Date To","Project Name","Vendor Name","Material Name","Final Rate","Unit","All Taxes & Duties","Loading & Transportation (F.O.R)","Unloading");
						foreach($rate_record as $retrive_data)
						{	
							$retrive_data = array_merge($retrive_data,$retrive_data["erp_finalized_rate"]);
							//debug($retrive_data);die;
							$export = array();
						?>
							<tr>								
								<td><?php echo ($export[] = date("d-m-Y",strtotime($retrive_data['rate_from_date'])));?></td>
								<td><?php echo ($export[] = date("d-m-Y",strtotime($retrive_data['rate_to_date'])));?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_multiple_projectname($retrive_data['project_id']));?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_vendor_name($this->ERPfunction->get_vendor_by_rate($retrive_data['rate_id'])));?></td>								
								<td><?php echo ($export[] = $this->ERPfunction->get_material_title($retrive_data['material_id']));?></td>
								<td><?php echo ($export[] = $retrive_data['final_rate']);?></td>							
								<td><?php echo ($export[] = $this->ERPfunction->get_items_units($retrive_data['material_id']));?></td>
								<td><?php echo ($export[] = ucfirst($retrive_data['text_duties']));?></td>
								<td><?php echo ($export[] = ucfirst($retrive_data['loading_trans']));?></td>
								<td><?php echo ($export[] = ucfirst($retrive_data['unloading']));?></td>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'raterecords')==1)
								{
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewaddrate', $retrive_data['rate_id'],'approve'),
								array('escape'=>false,'class'=>'btn btn-info btn-clean'));
								echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'deleteraterecords')==1)
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> Delete",array('action' => 'deleterate', $retrive_data['rate_detail_id']),
									array('escape'=>false,'class'=>'btn btn-danger btn-clean'));
								}
								?>
								</td>
								
							</tr>
						<?php
						$rows[] = $export;
						}
						}
						?>
				</tbody>
			</table>
			<?php
			if(isset($rate_record))
			{
			 if($rate_record != NULL){
			?>
			<div class="content">
				 <div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				
				<div class="col-md-2">
				<form method="post">
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				</form>
				</div>
				<div class="col-md-2">
				<form method="post">
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				</form>
				</div>
			</div>
		</div>
		<?php }} ?>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>
<script>
jQuery(document).ready(function(){
	jQuery("body").on("click",".approve_rate",function(){		
		var checked = jQuery(this).attr('checked');
		if(checked == "checked" && confirm("Are you sure you want to approve?"))
		{
		if(checked == "checked" && confirm("Are you sure you want to approve?"))
		{
			var rate_detail_id = jQuery(this).val();
						
			var curr_data = {
								rate_detail_id:rate_detail_id
							};
			$.ajax({
				method : "POST",								
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approverate'));?>",
				data : curr_data,
				async:false,
				success: function(response){
					// alert("Success");
					location.reload();
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		}
		else{
			jQuery(this).removeAttr('checked');
			jQuery(this).parent().removeClass('checked');		
		}
		}
		else{
			jQuery(this).removeAttr('checked');
			jQuery(this).parent().removeClass('checked');		
		}
	});
});
</script>