<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>

<script type="text/javascript">

var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
			
			jQuery('body').on('click','.viewmodal',function(){
			
				id = jQuery(this).attr('data_id');
				role = jQuery(this).attr('role');
				jQuery('#modal-view').html();
			    urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'billpaymentdetail'));?>";
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
			
			jQuery('.datepick').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
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
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Bill Records</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
				<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">		
						<div class="form-row">
							<div class="col-md-2 text-right">Inward Date Form</div>
							<div class="col-md-4"><input name="date_from" class="datepick" /></div>
							<div class="col-md-2 text-right">Inward Date To</div>
							<div class="col-md-4"><input name="date_to" class="datepick" /></div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Bill Date Form</div>
							<div class="col-md-4"><input name="bill_date_from" class="datepick" /></div>
							<div class="col-md-2 text-right">Bill Date To</div>
							<div class="col-md-4"><input name="bill_date_to" class="datepick" /></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">Party's Name</div>
							<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="party_id" id="party_id" >
							<option value="All">-- Select Party --</option>
							<?php
							if($vendor_info){
                            				foreach($vendor_info as $vendor_row){
                            					?>
													<option value="<?php echo $vendor_row['user_id']; ?>" dataid="<?php echo $vendor_row['vendor_id'];?>"  ><?php echo $vendor_row['vendor_name'];?></option>

                            					<?php
                            				}
                            			}?>
										</select>
							</div>
							<div class="col-md-2 text-right" id="pr_left">Project Name</div>
							<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="all">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{ ?>
										<option value="<?php echo $retrive_data['project_id'];?>"  >
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php										
									} ?>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">Type of Bill</div>
                            <div class="col-md-4">
								<select name="bill_type" class="select2" style="width:100%;" >

									<?php 
										$billtype=array(
															// 'Material/Item'=>'Material/Item',
															// 'Labour'=>'Labour',
															// 'Labour with Material/Item'=>'Labour with Material/Item',
															// 'Asset'=>'Asset',
															// "Others" => "Others"
														
															'All'=>'All',
															'Material/Item'=>'Material/Item',
															'Labour'=>'Labour',
															'Labour with Material/Item'=>'Labour with Material/Item',
															'Asset Maintenance'=>'Asset Maintenance',
															'Asset Purchase'=>'Asset Purchase',
															'Transport'=>'Transport',
															'Other'=>'Other',
															'Debit Note'=>'Debit Note',
															'Credit Note'=>'Credit Note',
															'Sub-Contract'=>'Sub-Contract',
															'YNEC Sales Bill'=>'YNEC Sales Bill',
															'YNEC E-way Bill'=>'YNEC E-way Bill',
															'Consultation'=>'Consultation',
															'Safety Material'=>'Safety Material'
												);

									
									foreach($billtype as $bill_key => $bill_value){
										?>
									<option value="<?php echo $bill_key ;?>"  ><?php echo $bill_value; ?></option>
									<?php
								}
								?>
								</select>
							</div>
							
								<div class="col-md-2 text-right" id="pr_left">Payment Method</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="payment_mod" id="payment_type" >
								<option value="All">-- Select Payment --</Option>
								<option value="cheque">Cheque</Option>
								<option value="cash">Cash</Option>
								
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Inward Bill No.</div>
							<div class="col-md-4"><input name="bill_no" class="" /></div>
							<div class="col-md-2 text-right">Invoice No.</div>
							<div class="col-md-4"><input name="invoice_no" class="form-control"></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">P.O./W.O. No.</div>
							<div class="col-md-4"><input name="powono" class="form-control"></div>
							
							<div class="col-md-2 text-right">Pay Status</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="pay_status" id="payment_type" >
								<option value="All">-- Select Status --</Option>
								<option value="accept">Accept</Option>
								<option value="pending">Pending</Option>
								</select>
							</div>
						</div>
						
						
						<div class="form-row">
							<div class="col-md-2 col-md-offset-2">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
					</div>
					<?php echo $this->Form->end();?>	
			
<input type="hidden" id="i_date_from" value="<?php echo isset($_POST["date_from"]) ? $_POST["date_from"] : "";?>">
<input type="hidden" id="i_date_to" value="<?php echo isset($_POST["date_to"]) ? $_POST["date_to"] : "";?>">
<input type="hidden" id="b_date_from" value="<?php echo isset($_POST["bill_date_from"]) ? $_POST["bill_date_from"] : "";?>">
<input type="hidden" id="b_date_to" value="<?php echo isset($_POST["bill_date_to"]) ? $_POST["bill_date_to"] : "";?>">
<input type="hidden" id="party" value="<?php echo isset($_POST["party_id"]) ? $_POST["party_id"] : "";?>">
<input type="hidden" id="pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" id="bill_type" value="<?php echo isset($_POST["bill_type"]) ? $_POST["bill_type"] : "";?>">
<input type="hidden" id="payment" value="<?php echo isset($_POST["payment_mod"]) ? $_POST["payment_mod"] : "";?>">
<input type="hidden" id="bill_no" value="<?php echo isset($_POST["bill_no"]) ? $_POST["bill_no"] : "";?>">
<input type="hidden" id="invoice_no" value="<?php echo isset($_POST["invoice_no"]) ? $_POST["invoice_no"] : "";?>">
<input type="hidden" id="po_wo" value="<?php echo isset($_POST["powono"]) ? $_POST["powono"] : "";?>">			
<input type="hidden" id="p_status" value="<?php echo isset($_POST["pay_status"]) ? $_POST["pay_status"] : "";?>">			
			
		<div class="content list custom-btn-clean">
		<script>
		
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
		jQuery(document).ready(function() {
			var i_date_from  = jQuery("#i_date_from").val();
			var i_date_to  = jQuery("#i_date_to").val();
			var b_date_from  = jQuery("#b_date_from").val();
			var b_date_to  = jQuery("#b_date_to").val();
			var party  = jQuery("#party").val();
			var pr_name  = jQuery("#pro_id").val();
			var bill_type  = jQuery("#bill_type").val();
			var payment  = jQuery("#payment").val();
			var bill_no  = jQuery("#bill_no").val();
			var invoice_no  = jQuery("#invoice_no").val();
			var po_wo  = jQuery("#po_wo").val();
			var pay_status  = jQuery("#p_status").val();
		
		var selected = [];
		jQuery('#user_list').DataTable({
			"order": [[ 1, "desc" ]],
			columnDefs: [ {
						orderable: false,
						targets:   -1,
						} ],
			"columns": [
					{ "visible": true },
					//{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": false },
					{ "visible": false },
					{ "visible": true },
					{ "visible": false },
					{ "visible": false },
							  ],
			
			"processing": true,
			"serverSide": true,
			//"ajax": "../Ajaxfunction/billrecordsdata",
			"ajax": {
					"url": "../Ajaxfunction/billrecordsdata",
					"data": function ( d ) {
												d.myKey = "myValue";
												d.date_from = i_date_from;
												d.date_to = i_date_to;
								 				d.bill_date_from = b_date_from;
								 				d.bill_date_to = b_date_to;
												d.party_id = party;
								 				d.project_id = pr_name;
												d.bill_type = bill_type;
												d.payment_mod = payment;
								 				d.bill_no = bill_no;
												d.invoice_no = invoice_no;
								 				d.powono = po_wo;
								 				d.pay_status = pay_status;
											}
					},
			"rowCallback": function( row, data ) {
		
									if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
										jQuery(row).addClass('selected');
									}
							},
			});
		} );
</script>
 
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>						
						<th>Project Name</th>
						<!--<th>Inward Bill No</th>-->
						<th>Inward Date</th>
						<th>Bill Date</th>
						<th>Party's Name</th>
						<th>Invoice No</th>	
						<th>Bill Amount</th>
						<th>Credit Period</th>
						<th>Type of Bill</th>
						<th>Pay Status</th>
						<th>Action By</th>
						<th>agency</th>
						<th>vendor</th>
						<th>Action</th>
						<th>Action</th>
						<th>Action</th>
					</tr>
				</thead>
				
			</table>
			<div class="content">
			<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
			<div class="col-md-2">
			<?php echo $this->Form->create('export_csv',['method'=>'post']); ?>
				<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
<input type="hidden" name="date_from" id="i_date_from" value="<?php echo isset($_POST["date_from"]) ? $_POST["date_from"] : "";?>">
<input type="hidden" name="date_to" id="i_date_to" value="<?php echo isset($_POST["date_to"]) ? $_POST["date_to"] : "";?>">
<input type="hidden" name="bill_date_from" id="b_date_from" value="<?php echo isset($_POST["bill_date_from"]) ? $_POST["bill_date_from"] : "";?>">
<input type="hidden" name="bill_date_to" id="b_date_to" value="<?php echo isset($_POST["bill_date_to"]) ? $_POST["bill_date_to"] : "";?>">
<input type="hidden" name="party_id" id="party" value="<?php echo isset($_POST["party_id"]) ? $_POST["party_id"] : "";?>">
<input type="hidden" name="project_id" id="pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" name="bill_type" id="bill_type" value="<?php echo isset($_POST["bill_type"]) ? $_POST["bill_type"] : "";?>">
<input type="hidden" name="payment_mod" id="payment" value="<?php echo isset($_POST["payment_mod"]) ? $_POST["payment_mod"] : "";?>">
<input type="hidden" name="bill_no" id="bill_no" value="<?php echo isset($_POST["bill_no"]) ? $_POST["bill_no"] : "";?>">
<input type="hidden" name="invoice_no" id="invoice_no" value="<?php echo isset($_POST["invoice_no"]) ? $_POST["invoice_no"] : "";?>">
<input type="hidden" name="powono" id="po_wo" value="<?php echo isset($_POST["powono"]) ? $_POST["powono"] : "";?>">			
<input type="hidden" name="pay_status" id="p_status" value="<?php echo isset($_POST["pay_status"]) ? $_POST["pay_status"] : "";?>">
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php echo $this->Form->end(); ?>
			</div>
			<div class="col-md-2">
			<?php echo $this->Form->create('export_pdf',['method'=>'post']); ?>
				<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
<input type="hidden" name="date_from" id="i_date_from" value="<?php echo isset($_POST["date_from"]) ? $_POST["date_from"] : "";?>">
<input type="hidden" name="date_to" id="i_date_to" value="<?php echo isset($_POST["date_to"]) ? $_POST["date_to"] : "";?>">
<input type="hidden" name="bill_date_from" id="b_date_from" value="<?php echo isset($_POST["bill_date_from"]) ? $_POST["bill_date_from"] : "";?>">
<input type="hidden" name="bill_date_to" id="b_date_to" value="<?php echo isset($_POST["bill_date_to"]) ? $_POST["bill_date_to"] : "";?>">
<input type="hidden" name="party_id" id="party" value="<?php echo isset($_POST["party_id"]) ? $_POST["party_id"] : "";?>">
<input type="hidden" name="project_id" id="pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" name="bill_type" id="bill_type" value="<?php echo isset($_POST["bill_type"]) ? $_POST["bill_type"] : "";?>">
<input type="hidden" name="payment_mod" id="payment" value="<?php echo isset($_POST["payment_mod"]) ? $_POST["payment_mod"] : "";?>">
<input type="hidden" name="bill_no" id="bill_no" value="<?php echo isset($_POST["bill_no"]) ? $_POST["bill_no"] : "";?>">
<input type="hidden" name="invoice_no" id="invoice_no" value="<?php echo isset($_POST["invoice_no"]) ? $_POST["invoice_no"] : "";?>">
<input type="hidden" name="powono" id="po_wo" value="<?php echo isset($_POST["powono"]) ? $_POST["powono"] : "";?>">			
<input type="hidden" name="pay_status" id="p_status" value="<?php echo isset($_POST["pay_status"]) ? $_POST["pay_status"] : "";?>">
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<?php echo $this->Form->end(); ?>
			</div>
			</div>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>