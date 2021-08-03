<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
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
jQuery(document).ready(function() {
			
			jQuery('body').on('click','.viewmodal',function(){
			
				id = jQuery(this).attr('data_id');
				role = jQuery(this).attr('role');
				jQuery('#modal-view').html();
			    urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewadvance'));?>";
				var curr_data = {id:id,role:role};
				
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
								console.log(e.responseText);
								 }
					});	
									
			});
		} );
</script>
<div class="col-md-10" >
<?php 
 if(!$is_capable)
	 {
		 $this->ERPfunction->access_deniedmsg();
	 }
else
  { 
?>
<div class="row">
	<div class="col-md-12">
		<div class="block" style="width:auto;">
			<div class="head bg-default bg-light-rtl">
				<h2>View Advance</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
		<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				$project_id = array();
				$agency_id = array();
				$adv_r_no = array();
				 $project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
				 $from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
				 $to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
				 $agency_id = isset($_POST['id'])?$_POST['id']:'';
				 $adv_r_no = isset($_POST['advance_req_no'])?$_POST['advance_req_no']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date of Cheque: -</div>
						<div class="col-md-1">From -</div>
                        <div class="col-md-2"><input type="text" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="datep form-control"/></div>
						<div class="col-md-1">To -</div>
                        <div class="col-md-2"><input type="text" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="datep form-control"/></div>
						<div class="col-md-1">Adv.R.No.</div>
                        <div class="col-md-2">
						<!--<input type="text" name="adv_r_no[]" value="" class="form-control"/>-->
						 <select class="select2" style="width: 100%;" name="adv_r_no[]" id="adv_r_no" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($advance_r_no as $retrive_data)
									{
										$selected = (in_array($retrive_data['advance_req_no'],$adv_r_no)) ? "selected" : "";
										echo '<option value="'.$retrive_data['advance_req_no'].'" '. $selected .'>'.$retrive_data['advance_req_no'].'</option>';
									}
								?>
							</select> 
						</div>
					</div>
					<div class="form-row">	
						<!-- <div class="col-md-2">GRN.No:</div>
                        <div class="col-md-4">
							<input type="text" name="po_no" id="po_no" value="" class="form-control"/>
						</div>					
						-->
						
						
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
						
						<div class="col-md-2">Vendor Name:</div>
                        <div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="agency_id[]" id="agency_id" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($vendor_list as $retrive_data)
									{
										$selected = (in_array($retrive_data['user_id'],$agency_id)) ? "selected" : "";
										echo '<option value="'.$retrive_data['user_id'].'" '.$selected.'>'.
										$retrive_data['vendor_name'].'</option>';									
									
									}
								?>
							</select>
						</div>
                    </div>
					<!--<div class="form-row">	
						 <div class="col-md-2">Material ID:</div>
                        <div class="col-md-4">
							<input type="text" name="po_no" id="po_no" value="" class="form-control"/>
						</div>
						<div class="col-md-2">Material Name:</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All">All</Option>
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
					</div> -->
					
					<!--<div class="form-row">	
						<div class="col-md-2">Mode of Purchase:</div>
                    	<div class="col-md-4">
							<input type="text" name="po_mode" id="po_mode" value="" class="form-control" value=""/>
						</div>
						<div class="col-md-2">Payment Method:</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="payment_method[]" id="payment_method" multiple="multiple">
								<option value="All">All</Option>
								<option value="cash">Cash</Option>
								<option value="cheque">Cheque</Option>									</select>
						</div> 			
					</div>	 -->		
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
			</div>
			</div>
			<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#grn_list').DataTable({responsive: true,aaSorting: [[ 1, "desc" ]]});
		} );
</script>
			
			<table id="grn_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Project Name</th>
						<th>Transfer Date</th>
						<th>Agency ID</th>						
						<th>Agency's Name</th>
						<th>Advance(Rs.)</th>
						<th>TDS(Rs.)</th>
						<th>Net Paid Amount(Rs.)</th>
						<th>Transfer Mode</th>
						<!-- <th class="none">Edit</th> -->
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'viewadvance')==1)
						{
						?>
						<th>View / Delete</th>
						<?php } ?>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'exportadvance')==1)
						{
						?>
						<th>Export</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($advance_viewlist))
					{
						$i = 1;
						$rows = array();
						$rows[] = array("Project Name","Transfer Date","Agency ID","Agency's Name","Advance(Rs.)","TDS(Rs.)","Net Paid Amount(Rs.)","Transfer Mode");
					
						foreach($advance_viewlist as $retrive_data)
						{ 
							if(isset($retrive_data["erp_advance_request_detail"]))
							{
								$retrive_data = array_merge($retrive_data,$retrive_data["erp_advance_request_detail"]);
							}
							$export = array();
							$csv = array();
						?>
							<tr>	
																
								<Td><?php echo ($csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id'])); ?></td>
								<td><?php echo ($csv[] = date('d-m-Y', strtotime($retrive_data['transfer_date'])));?></td>							
								<td><?php echo ($csv[] = $retrive_data['agency_id']);?></td>																							
								<td><?php echo  ($csv[] = $this->ERPfunction->get_agency_name($retrive_data['agency_id']));?></td>
                                <td><?php echo ($csv[] = $retrive_data['advance_rs']);?></td>
								<td><?php echo ($csv[] = $tds = $retrive_data['advance_rs'] * 1 /100);?></td>
								<td><?php echo ($csv[] = $retrive_data['advance_rs'] - $tds);?></td>
								<td><?php echo ($csv[] = $retrive_data['transfer_type']);?></td>
																															
								<?php $abc[] = $this->ERPfunction->get_agency_name($retrive_data['agency_id']);?>
								<?php $abc[] = $retrive_data['cheque_amount'];?>
								<?php $abc[] = $retrive_data['cheque_amount'];?>
								<?php $abc[] = $retrive_data['bank'];?>
								<?php $abc[] = $retrive_data['cheque_no'];?>
								<?php $abc[] = $abc; ?>
								
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'viewadvance')==1)
								{
								// echo '<button type="button"  id="transfereasset" data-type="transfereasset" data-toggle="modal" 
								// data-target="#load_modal" class="btn btn-info viewmodal btn-clean" data_id="'.$retrive_data['id'].'" role="'.$role.'"> <i class="icon-eye-open"></i>View </button>';	
								echo $this->Html->link("<i class='icon-pencil'></i> View",array('action' => 'requestview', $retrive_data['request_id'],'viewadvance'),
									array('class'=>'btn btn-primary btn-clean action-btn','escape'=>false));
								}
								if($this->ERPfunction->retrive_accessrights($role,'deleteadvance')==1)
								{
								echo $this->Html->link('<i class="icon-trash"></i> Remove',array('action' => 'deleteadvance', $retrive_data['id']),
								array('class'=>'btn btn-danger btn-clean action-btn','escape'=> false,
								'confirm' => 'Are you sure you wish to Unapprove this Record?'));
								}
								?>
								</td>
								
								<?php
								if($this->ERPfunction->retrive_accessrights($role,'exportadvance')==1)
								{
								?>
								<td>
									<form method="post">
									<input type="hidden" name="rows" value='<?php echo $retrive_data['id'];?>'>
									<input type="submit" class="btn btn-info col-md-12" value="Export To Excel" name="export_csv">
								</form>
								</td>
								<?php
								}
								?>
								</tr>
						<?php
						$rows[] = $csv;
						$i++;
						// $rows[] = $export;
						}
						}
					?>
				</tbody>
			</table>
			<?php
			if(isset($advance_viewlist))
			{
			if($advance_viewlist != NULL)
			{
			?>
			<div class="content">
			<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
			<div class="col-md-2">
			<?php echo $this->Form->create('export_csv',['method'=>'post']); ?>
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv_all">
				<?php echo $this->Form->end(); ?>
			</div>
			<div class="col-md-2">
			<?php echo $this->Form->create('export_pdf',['method'=>'post']); ?>
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf_all">
				<?php echo $this->Form->end(); ?>
			</div>
			</div>
			<?php } } ?>
		</div>
		</div>
	</div>
</div>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<?php 
} 
?>
</div>
