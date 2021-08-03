<?php
	use Cake\Routing\Router;
?>
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
				<h2>W.O. Records</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		<div class="content ">
			<script>
			var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

				jQuery(document).ready(function() {
					var f_date_from  = jQuery("#f_date_from").val();
					var f_date_to  = jQuery("#f_date_to").val();
					var f_pro_id  = jQuery("#f_pro_id").val();
					var f_party_userid  = jQuery("#f_party_userid").val();
					var f_contract_type  = jQuery("#f_contract_type").val();
					var f_wo_no  = jQuery("#f_wo_no").val();
					var f_payment_method  = jQuery("#f_payment_method").val();
			
					var selected = [];
					var table = jQuery('#wo_list').DataTable({
						"pageLength": 10,
						"order": [[ 0, "desc" ]],
						columnDefs: [{
							searchable: false,
							targets:   6,
						}],
						"columns": [
							{ "visible": true },
							{ "visible": true },
							{ "visible": true },
							{ "visible": true },
							{ "visible": true },
							{ "visible": true },
							{ "visible": true },
							{ "visible": true },
							{ "visible": true },
							{ "visible": true },
						],
						"responsive" : true,
						"processing": true,
						"serverSide": true,
						//"ajax": "../Ajaxfunction/billrecordsdata",
						"ajax": {
							// "url": "../Ajaxfunction/porecords",
							url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'planningworecords'));?>",
							"data": function ( d ) {
								d.myKey = "myValue";
								d.date_from = f_date_from;
								d.date_to = f_date_to;
								d.pro_id = f_pro_id;
								d.party_userid = f_party_userid;
								d.contract_type = f_contract_type;
								d.wo_no = f_wo_no;
								d.payment_method = f_payment_method;
							}
						},
						"rowCallback": function( row, data ) {					
							if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
								jQuery(row).addClass('selected');
							}
						},
					});
			
					jQuery('#from_date,#to_date').datepicker({
						dateFormat: "dd-mm-yy",
						changeMonth: true,
						changeYear: true,
						yearRange:'-65:+0',
						onChangeMonthYear: function(year, month, inst) {
							jQuery(this).val(month + "-" + year);
						}                    
					});
				
					jQuery("body").on("click", "#ammend_approve", function(event){
						var wo_id = jQuery(this).val();	
						if(confirm('Are you Sure approve this W.O.?')) {
							var curr_data = {	 						 					
								wo_id : wo_id,	 					
							};	 				
							jQuery.ajax({
								headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
								url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approveammendwo'));?>",
								data:curr_data,
								async:false,
								success: function(response){
										if(response == "email_issue")
										{
											alert("There is a problem with email format");
											location.reload();
											return false;
										}else{
											alert("WO approve successfully.");
											location.reload();
											return false;
										}
								},
								error: function (e) {
									alert('Error');
								}
							});
						}else {				
							jQuery(this).removeAttr('checked');
							jQuery(this).parent().removeClass('checked');
						}
					});
				
					jQuery('body').on('click','.cancelwo',function(event){
						// alert("ASd");
						// event.preventDefault();
						var del = false;
						if(confirm('1.Are you sure want to cancel w.o. ?')) {
							if(confirm('1.Are you sure want to cancel w.o. ?')) {
								if(confirm('1.Are you sure want to cancel w.o. ?')) {
									del = true;
								}
							}
						}
						if(del) {
							return true;
						}else {
							return false;
						}
					});
					jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadvendor'));?>",
						async:false,
						success: function(response){
							// alert(response);return false;
							jQuery('#party_userid').empty();
							jQuery('#party_userid').append(response);
							return false;
						},
						error: function (e) {
							alert('Error');
						}
					});	
					jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loaduserprojects'));?>",
						async:false,
						success: function(response){
							jQuery('select#project_id').empty();
							jQuery('select#project_id').append(response);
							return false;
						},
						error: function (e) {
							alert('Error');
						}
					});
				});
		</script>
			<div class="col-md-12 filter-form">
			<?php 
			$project_id = isset($request_data['project_id'])?$request_data['project_id']:'';
			$from_date = isset($request_data['from_date'])?$request_data['from_date']:'';
			$to_date = isset($request_data['to_date'])?$request_data['to_date']:'';
			?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-4"><input type="text" name="from_date" id="from_date" value="" class="form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-4"><input type="text" name="to_date" id="to_date" value="" class="form-control"/></div>
					</div>
					<div class="form-row">	
					<div class="col-md-2">Party Name</div>
                        <div class="col-md-4">
							<select class="select2 party_userid"  style="width: 100%;" name="party_userid[]" id="party_userid" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									// if($vendor_info){
                            		// 		foreach($vendor_info as $vendor_row){
                            					?>
													<!-- <option value="<?php //echo $vendor_row['user_id']; ?>" dataid="vendor"  -->
													<?php 
														// if(isset($update_inward)){
														// 	if($update_inward['party_name'] == $vendor_row['user_id']){
														// 		echo 'selected="selected"';
														// 	}
														// }
													?> 
													<!-- > -->
													<!-- <?php //echo $vendor_row['vendor_name'];?></option> -->
                            					<?php
                            			// 	}
                            			// }
										// if(!empty($agency_list))
										// {
										// 	foreach($agency_list as $agency){ ?>
												<!-- <option value="<?php //echo $agency['agency_id']; ?>" dataid="agency"  -->
												<?php 
													// if(isset($update_inward)){
													// 	if($update_inward['party_name'] == $agency['agency_id']){
													// 		echo 'selected="selected"';
													// 	}
													// }
													?>
													<!-- ><?php //echo $agency['agency_name'];?></option> -->
											<?php	
										// 	}
										// }
										?>
							</select>
						</div>
                
						<div class="col-md-2">Project Name</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									// foreach($projects as $retrive_data)
									// {
									// 	echo '<option value="'.$retrive_data['project_id'].'">'.$retrive_data['project_name'].'</option>';
									// }
								?>
							</select>
						</div>
                    </div>
					
					<div class="form-row">
							<div class="col-md-2">Type of Contract:</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" name="type_of_contract[]" id="type_of_contract" multiple="multiple">
									<option value="All">All</Option>
									<?php 
										$contract_list = $this->ERPfunction->contract_type_list();
									   foreach($contract_list as $retrive_data)
									   {
											 echo '<option value="'.$retrive_data['id'].'">'.
											 $retrive_data['title'].'</option>';
									   }
									?>
								</select>
							</div>
							
                            <div class="col-md-2">Payment Method:</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" name="payment_method" id="payment_method" >
									<option value="All">--Select Payment Method--</Option>
									<option value="cash">Cash</Option>
									<option value="cheque">Cheque</Option>							
								</select>
							</div>
                        </div>
					<div class="form-row">	
						<div class="col-md-2">W.O.No</div>
                        <div class="col-md-4">
							<input type="text" name="wo_no" id="wo_no" value="" class="form-control"/>
						</div>		
					</div>			
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
						
					</div>
				<?php $this->Form->end(); ?>
<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : '';?>">
<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : '';?>">
<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : '';?>">
<input type="hidden" id="f_party_userid" value="<?php echo isset($_POST["party_userid"]) ? implode(",",$_POST["party_userid"]) : "";?>">
<input type="hidden" id="f_contract_type" value="<?php echo isset($_POST["type_of_contract"]) ? implode(",",$_POST["type_of_contract"]) : "";?>">
<input type="hidden" id="f_wo_no" value="<?php echo isset($_POST["wo_no"]) ? $_POST["wo_no"] : "";?>">
<input type="hidden" id="f_payment_method" value="<?php echo isset($_POST["payment_method"]) ? $_POST["payment_method"] : "";?>">
			</div>
			</div>
		<div class="content list custom-btn-clean">
		
			<table id="wo_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Date</th>
						<th>W.O. No</th>
						<th>Project Name</th>
						<th>Party Name</th>						
						<th>Type of<br>Contract</th>						
						<th>Amount</th>					
						<th>Action</th>
						<th>Ammend</th>
						<th class="never">agency</th>
						<th class="never">vendor</th>
					</tr>
				</thead>
				<!--<tbody>
					<?php	
						// $rows = array();
						// $rows[] = array("Date","W.O. No","Project Name","Party Name","Type of Contract","Amount");
						
						// foreach($wo_date as $retrive_data)
						// {	
						// 	$export = array();
						// 	$retrive_data = array_merge($retrive_data,$retrive_data['erp_work_order']);
						?>
							<tr>								
								<td><?php //echo ($export[] = date("d-m-Y",strtotime($retrive_data['wo_date'])));?></td>
								<td><?php //echo ($export[] = $retrive_data['wo_no']);?></td>	
								<td><?php //echo ($export[] = $this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>
								<?php //if(is_numeric($retrive_data['party_userid'])){ ?>
								<td><?php //echo ($export[] = $this->ERPfunction->get_vendor_name($retrive_data['party_userid']));?></td>
								<?php //} else { ?>
								<td><?php //echo ($export[] = $this->ERPfunction->get_agency_name_by_code($retrive_data['party_userid']));?></td>
								<?php } ?>
								<td><?php //echo ($export[] = $this->ERPfunction->get_contract_title($retrive_data['contract_type']));?></td>
								<td><?php //echo ($export[] = $retrive_data['contract_no']);?></td>							
								<td><?php //echo ($export[] = $this->ERPfunction->get_work_head_title($retrive_data['work_head']));?></td>
								<td><?php //echo ($export[] = $retrive_data['quentity']);?></td>
								<td><?php //echo ($export[] = $retrive_data['unit']);?></td>
								<td><?php //echo ($export[] = $retrive_data['unit_rate']);?></td>
								<td><?php //echo ($export[] = $retrive_data['amount']);?></td>
								<td>
								<?php 
								// if($this->ERPfunction->retrive_accessrights($role,'worecords')==1)
								// {
								// 	echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewapprovedwo', $retrive_data['wo_id']),
								// 	array('escape'=>false,'target'=>'blank','class'=>'btn btn-info btn-clean'));
								// 	echo ' ';
								// }
								// if($this->ERPfunction->retrive_accessrights($role,'cancelwo')==1)
								// {
								// 	echo $this->Html->link("<i class='icon-remove'></i> Cancel W.O.",array('action' => 'cancelwo', $retrive_data['wo_id']),
								// 	array('escape'=>false,'class'=>'btn btn-danger btn-clean cancelwo'));
								// 	echo ' ';
								// }
								?>
								</td>
							</tr>
						<?php
						// $rows[] = $export;
						// }
						?>
				</tbody>-->
			</table>
			<div class="content">
				<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				<div class="col-md-2">
				<?php echo $this->Form->create('export_csv',['method'=>'post']); ?>
					<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : '';?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : '';?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : '';?>">
					<input type="hidden" name="e_party_userid" value="<?php echo isset($_POST["party_userid"]) ? implode(",",$_POST["party_userid"]) : "";?>">
					<input type="hidden" name="e_contract_type" value="<?php echo isset($_POST["type_of_contract"]) ? implode(",",$_POST["type_of_contract"]) : "";?>">
					<input type="hidden" name="e_wo_no" value="<?php echo isset($_POST["wo_no"]) ? $_POST["wo_no"] : "";?>">
					<input type="hidden" name="e_payment_method" value="<?php echo isset($_POST["payment_method"]) ? $_POST["payment_method"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php echo $this->Form->end(); ?>
				</div>
				<div class="col-md-2">
				<?php echo $this->Form->create('export_pdf',['method'=>'post']); ?>
					<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : '';?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : '';?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : '';?>">
					<input type="hidden" name="e_party_userid" value="<?php echo isset($_POST["party_userid"]) ? implode(",",$_POST["party_userid"]) : "";?>">
					<input type="hidden" name="e_contract_type" value="<?php echo isset($_POST["type_of_contract"]) ? implode(",",$_POST["type_of_contract"]) : "";?>">
					<input type="hidden" name="e_wo_no" value="<?php echo isset($_POST["wo_no"]) ? $_POST["wo_no"] : "";?>">
					<input type="hidden" name="e_payment_method" value="<?php echo isset($_POST["payment_method"]) ? $_POST["payment_method"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<?php //} ?>
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