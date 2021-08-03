<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<div class="col-md-10" >
 <?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
 ?>
 <style>
	
 </style>
 <script type="text/javascript">
        function codeAddress() {
            var am = $("#totalamount").val();
			var ex = $("#totalexpense").val();
			//alert(am);
			jQuery('#total_income').val(am);
			jQuery('#total_expense').val(ex);
			var balance = am - ex;
			jQuery('#balance').val(balance);
        }
        window.onload = codeAddress;
        </script>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
 jQuery(document).ready(function() {
	jQuery("body").on("change","#approv_cm",function(){
	
		//var approve = false;
		if(confirm("Are you sure,you want to approve this record?"))
		{
			if(confirm("Are you sure,you want to approve this record?"))
			{
				if(confirm("Are you sure,you want to approve this record?"))
				{
					//approve = true;
					var id = $(this).attr("data_id");
					var curr_data = {id:id};
					$.ajax({
						headers: {
							'X-CSRF-Token': csrfToken
						},
						type : "POST",
						 url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'expenceapprovebycm'));?>",
						data:curr_data,
						async:false,
						success : function(result)
							{
								
							},
						error : function(e)
							{
								
								console.log(e.responseText);
							}
					});
					
				}			
			}
		}
		
	});
	
	
	jQuery("body").on("change","#approv_pd",function(){
	
		//var approve = false;
		if(confirm("Are you sure,you want to approve this record?"))
		{
			if(confirm("Are you sure,you want to approve this record?"))
			{
				if(confirm("Are you sure,you want to approve this record?"))
				{
					//approve = true;
					var id = $(this).attr("data_id");
					var curr_data = {id:id};
					$.ajax({
						type : "POST",
						 url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'expenceapprovebypd'));?>",
						data:curr_data,
						async:false,
						success : function(result)
							{
								
							},
						error : function(e)
							{
								
								console.log(e.responseText);
							}
					});
					
				}			
			}
		}
		
	});
	
	jQuery("body").on("change","#approv_accountant",function(){
	
		//var approve = false;
		if(confirm("Are you sure,you want to approve this record?"))
		{
			if(confirm("Are you sure,you want to approve this record?"))
			{
				if(confirm("Are you sure,you want to approve this record?"))
				{
					//approve = true;
					var id = $(this).attr("data_id");
					var curr_data = {id:id};
					$.ajax({
						type : "POST",
						 url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'expenceapprovebyaccountant'));?>",
						data:curr_data,
						async:false,
						success : function(result)
							{
								
							},
						error : function(e)
							{
								
								console.log(e.responseText);
							}
					});
					
				}			
			}
		}
		
	});
	
	jQuery(document).ready(function() {
			
			
			// jQuery('#load_modal').on('hidden', function () {
			  // $(this).removeData('modal');
			// });
			
			/* jQuery('.viewmodal').click(function(){			 */
			jQuery('body').on('click','.viewmodal',function(){
			
				if($(".ch_pend").is(":checked")) {
				
				request_id=jQuery('.ch_pend:checked').map(function() {	return this.attributes.data_id.textContent;
																			}).get();
				request_id = JSON.stringify(request_id);
				
				//quantity=jQuery(this).attr('quantity');
				
				jQuery('#modal-view').html();
				var model  = jQuery(this).attr('data-type') ;
				//var asset_id  = jQuery(this).attr('asset_id') ;
				
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'advancetransfer'));?>";
				
				var curr_data = {request_id:request_id};	 				
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
				}					
			});
		} );
		
		jQuery("body").on("change", "#project_id", function(event){ 
	 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'expenceprojectdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					// jQuery('#prno').val(json_obj['prno']);
					// jQuery('#voucher_no').val(json_obj['prno']);
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
	
	jQuery("body").on("change", "#project_id", function(event){ 
	 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'accountbyproject'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					// var json_obj = jQuery.parseJSON(response);					
					// jQuery('#project_code').val(json_obj['project_code']);												
					// return false;
					//jquery('#account_id').
					//$("#account_id").append("<option value=''>select</option>");
					$('#account_id').html(response);
					$("#account_id").prepend("<option value='' selected>--Select Account--</option>");
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
	
	jQuery("body").on("change", "#account_id", function(event){ 
	 
	  var account_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					account_id : account_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'accountdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#account_no').val(json_obj['account_no']);
					jQuery('#bank').val(json_obj['bank']);
					jQuery('#branch').val(json_obj['branch']);
					jQuery('#ifsc_code').val(json_obj['ifsc_code']);
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
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
	});
</script>
<div class="row">
	<div class="col-md-12">
		<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>SITE TRANSACTION</h2>
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
                            <div class="col-md-2">Project Code</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value=""
							class="form-control" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name<span class="require-field">*</span> </div>
                            <div class="col-md-4">
								<select class="select2 validate[required]" required="true"   style="width: 100%;" name="project_id[]" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                    </div>
                    
					<div class="form-row">
                            <div class="col-md-2">Account No</div>
                            <div class="col-md-4"><input type="text" name="account_no" id="account_no" value=""
							class="form-control " value="" readonly="true"/></div>
							<div class="col-md-2">Account Name<span class="require-field">*</span> </div>
                            <div class="col-md-4">
								<select class="select2 validate[required]"  required="true"   style="width: 100%;" name="account_id[]" id="account_id">
								<option value="">--Select Account--</Option>
								 <?php 
									// foreach($account_list as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['account_id'].'">'.
										// $retrive_data['account_name'].'</option>';
									// }
								// ?>
								</select>
							</div>
                    </div>
					
					<div class="form-row">
                            <div class="col-md-2">Voucher No</div>
                            <div class="col-md-4"><input type="text" name="voucher" id="voucher" value=""
							class="form-control"/></div>
							<div class="col-md-2">Expense Head </div>
                            <div class="col-md-4">
								<select class="select2"     style="width: 100%;" name="expence_head[]" id="expence_head">
								<option value="All">--Select Expence Head--</Option>
								<?php 
									foreach($expence_head as $retrive_data)
									{
										echo '<option value="'.$retrive_data['expence_id'].'">'.
										$retrive_data['expence_head_name'].'</option>';
									}
								?>
								</select>
							</div>
                    </div>
					
					<div class="form-row">
						<div class="col-md-2">Inward Date: -</div>
						<div class="col-md-1 ">From -</div>
                        <div class="col-md-2"><input type="text" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="datep form-control"/></div>
						<div class="col-md-1">To -</div>
                        <div class="col-md-2"><input type="text" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="datep form-control"/></div>
						<div class="col-md-1">Payment</div>
                        <div class="col-md-3">
						<!--<input type="text" name="adv_r_no[]" value="" class="form-control"/>-->
						 <select class="select2"  required="true"   style="width: 100%;" name="payment_type[]" id="payment_type" onchange="yesnoCheck(this);">
								<option value="All">Select Payment</Option>
								<option value="cheque">Cheque</Option>
								<option value="cash">Cash</Option>
								
								</select>
						</div>
					</div>
						
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
			</div>
			</div>
					
		<div class="content list custom-btn-clean">
		
		<?php 
		if(isset($transaction))
		 {
		?>
		<table class="col-md-12 table-bordered" style="color:black;">
		
		<?php
			if($transaction != NULL)
			{
			echo "<br><br>";
		?>
		<div class="col-md-12" style="color:black;">
			<div class="col-md-2" style="line-height:30px;">Total Income Till Date:</div>
			<div class="col-md-2"><input type="text" style="color:#333" name="total_income" readonly="true" id="total_income" value="" class="form-control"/></div>
			<div class="col-md-2" style="line-height:30px;">Total Expense Till Date:</div>
			<div class="col-md-2"><input type="text" style="color:#333" name="total_expense" readonly="true" id="total_expense" value="" class="form-control"/></div>
			<div class="col-md-1" style="line-height:30px;">Balance:</div>
			<div class="col-md-2"><input type="text" style="color:#333" name="balance" readonly="true" id="balance" value="" class="form-control"/></div>
		</div>
		<?php 
		echo "<br><br><br><br>";
		}
		?>
		
		<thead>
			<tr>
				<td colspan="5" class="text-center">Income</td>
				<td colspan="6" class="text-center">Expense</td>
				<td rowspan="2">Balance</td>
			</tr>
			<tr>
				<td>Date</td>
				<td>Voucher No</td>
				<td>Amount Issued</td>
				<td>Payment</td>
				<td>View / Delete</td>
				<td>Voucher No</td>
				<td>Expense Head</td>
				<td>Given To</td>
				<td>Total Amount of Expense</td>
				<td>Payment</td>
				<td>View / Delete</td>
			</tr>
			</thead>
			<tbody>
				<?php
					$rows = array();
					$rows[] = array("Date","Voucher No","Amount Issued","Payment","Voucher No","Expense Head","Given To","Total Amount of Expense","Payment","Balance");
					
					$total_amount = 0;
					$total_expence = 0;
					$total = 0;
					foreach($transaction as $retrive)
					{
					if(isset($retrive['date']) && !empty($retrive['date']) || isset($retrive['expense_details']['date']) && !empty($retrive['expense_details']['date']))
					{
					$export = array();
					$amount = @$retrive['amount_issue'];
					?>
						<tr>
							<td><?php echo ($export[] = isset($retrive['date']) ? $this->ERPfunction->get_date($retrive['date']) : $this->ERPfunction->get_date($retrive['expense_details']['date'])); ?></td>
							<td><?php echo ($export[] = isset($retrive['voucher_no']) ? $retrive['voucher_no'] : ""); ?></td>
							<td><?php echo ($export[] = isset($retrive['amount_issue']) ? $retrive['amount_issue'] : ""); ?></td>
							<td><?php echo ($export[] = isset($retrive['payment_type']) ? $retrive['payment_type'] : ""); ?></td>
							
							<td>
							<?php 
								if($this->ERPfunction->retrive_accessrights($role,'viewamountissued')==1)
								{
									if(isset($retrive['issue_id']))
									{
									echo $this->Html->link("<i class='icon-pencil'></i> View",array('action' => 'viewamountissued', $retrive['issue_id']),
									array('class'=>'btn btn-primary btn-clean action-btn','target'=>'blank','escape'=>false));
									}
								}
								if($this->ERPfunction->retrive_accessrights($role,'incomedelete')==1)
								{
									if(isset($retrive['issue_id']))
									{
									echo $this->Html->link('<i class="icon-trash"></i> Remove',array('action' => 'incomedelete', $retrive['issue_id']),
									array('class'=>'btn btn-danger btn-clean action-btn','escape'=> false,
									'confirm' => 'Are you sure you wish to remove this Record?'));
									}
								}
							?>
							</td>
							<td><?php echo ($export[] = isset($retrive['expense_details']['voucher_no']) ? $retrive['expense_details']['voucher_no'] : ""); ?></td>
							<td><?php echo ($export[] = isset($retrive['expense_details']['expence_head'])? $this->ERPfunction->expence_head_name($retrive['expense_details']['expence_head']):""); ?></td>
							<td><?php echo ($export[] = isset($retrive['expense_details']['given_to'])?$retrive['expense_details']['given_to']:""); ?></td>
							<td><?php echo ($export[] = isset($retrive['erp_expense_details']['expence_total'])?$retrive['erp_expense_details']['expence_total']:""); ?></td>
							<td><?php echo ($export[] = isset($retrive['expense_details']['payment_type'])?$retrive['expense_details']['payment_type']:""); ?></td>
							
							<td><?php 
								if($this->ERPfunction->retrive_accessrights($role,'viewexpence')==1)
								{
								if(isset($retrive['expense_details']['id']))
								{
								echo @$this->Html->link("<i class='icon-pencil'></i> View",array('action' => 'viewexpence', $retrive['expense_details']['id']),
								array('class'=>'btn btn-primary btn-clean action-btn','target'=>'blank','escape'=>false));
								}
								}
								if($this->ERPfunction->retrive_accessrights($role,'expensedelete')==1)
								{
								if(isset($retrive['expense_details']['id']))
								{
								echo @$this->Html->link('<i class="icon-trash"></i> Remove',array('action' => 'expensedelete', $retrive['expense_details']['id']),
								array('class'=>'btn btn-danger btn-clean action-btn','escape'=> false,
								'confirm' => 'Are you sure you wish to remove this Record?'));
								}
								}
							?></td>
							<td><?php echo ($export[] = $total = $total + $amount - @$retrive['erp_expense_details']['expence_total']); ?></td>
							<?php
								$total_expence = $total_expence +  @$retrive['erp_expense_details']['expence_total'];
							?>
							
							<?php
								
								$total_amount = $total_amount +  @$retrive['amount_issue'];
							?>
						</tr>
					<?php
					$rows[] = $export;
					//var_dump($rows);
					}
					}
					
					
					
					
				?>
				
				<input type="hidden" name="totalamount" id="totalamount" value="<?php echo $total_amount;  ?>" class="form-control"/>
				<input type="hidden" name="totalexpense" id="totalexpense" value="<?php echo $total_expence;  ?>" class="form-control"/>
				
			</tbody>
		</table>
		<br></br>
		<?php } ?>
		
		
		<?php
		if(isset($transaction)){
			if($transaction != NULL)
			{
		?>
		<div class="col-md-12">
		<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
					<?php
					if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'siteaccountant' || $role == 'financehead' || $role == 'financemanager' || $role == 'constructionmanager')
					{
					?>
			<div class="col-md-2">
				<?php echo $this->Form->create('export_csv',['method'=>'post']); ?>
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php echo $this->Form->end(); ?>
			</div>	
			<div class="col-md-2">
				<?php echo $this->Form->create('export_pdf',['method'=>'post']); ?>
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<?php echo $this->Form->end(); ?>
			</div>	
			<?php }} ?>
			
		</div>
		<br></br>
		<?php
			}
		?>
				
		<?php
		//}
		?>
		</div>
		<div class="content">
			<div class="col-md-2 pull-right">
			
			<div>
				
				</div>
								
			</div>
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
