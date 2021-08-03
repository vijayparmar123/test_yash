<?php
use Cake\Routing\Router;

if($show)
{
	if(isset($this->request->params['pass'][0]))
		$project_id = $this->request->params['pass'][0];
		
	if(isset($this->request->params['pass'][1]))
		$material_id = $this->request->params['pass'][1];
	
	$project_code = $this->ERPfunction->get_projectcode($project_id);
	$material_code = $this->ERPfunction->get_materialitemcode($material_id);
}


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
<?php //echo $this->element('breadcrumbs'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="header bg-default bg-light-rtl">
				<h2 style="color:#FFFFFF;">Stock Ledger </h2>
				<div class="pull-right">
				<?php
						if(isset($this->request->params['pass'][0]) && isset($this->request->params['pass'][1])){
						?>
						<a href="<?php //echo $this->ERPfunction->action_link('Inventory','index');?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						<?php
						}
						else
						{
						?>
					<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
					<?php } ?>
				</div>
			</div>
		
		  <div class="block block-fill-white">
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
		<script>
		jQuery(document).ready(function() {
			jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loaduserprojects'));?>",
                async:false,
                success: function(response){
					var selected_project = $("#selected_project").val();
					
					jQuery('select#project_id').empty();
					jQuery('select#project_id').append(response);
					$("select#project_id").prepend("<option value=''>--select project</option>").val('');
					$("select#project_id").select2("val", selected_project);
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });
			
			jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadvendor'));?>",
                async:false,
                success: function(response){
					var selected_vendor = $("#selected_vendor").val();
					jQuery('select#vendor_userid').empty();
					jQuery('select#vendor_userid').append(response);
					
					$("select#vendor_userid").prepend("<option value='All'>All</option>").val('');
					$("select#vendor_userid").select2("val", selected_vendor);
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });
			
			jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadmaterial'));?>",
                async:false,
                success: function(response){
					var selected_material = $("#selected_material").val();
					jQuery('select#sl_mrn_name').empty();
					jQuery('select#sl_mrn_name').prepend(response);
					$("select#sl_mrn_name").prepend("<option value=''>--select material--</option>").val('');
					$("select#sl_mrn_name").select2("val", selected_material);
					
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });
			
			jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadagency'));?>",
                async:false,
                success: function(response){
					var selected_agency = $("#selected_agency").val();
					jQuery('select#agency_id').empty();
					jQuery('select#agency_id').append(response);
					$("select#agency_id").prepend("<option value='All'>All</option>").val('');
					$("select#agency_id").select2("val", selected_agency);
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });
			
			jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getassetrecords'));?>",
                async:false,
                success: function(response){
					// jQuery('select#agency_id').empty();
					var selected_agency = $("#selected_agency").val();
					jQuery('select#agency_id').append(response);
					// $("select#agency_id").prepend("<option value='All'>All</option>").val('');
					$("select#agency_id").select2("val", selected_agency);
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });
			
			jQuery("body").on("change", "#project_id", function(event){ 
	 
				var project_id  = jQuery(this).val() ;
				/* alert(product_id);
				return false; */
				var curr_data = {	 						 					
								project_id : project_id,	 					
								};	 				
					jQuery.ajax({
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'stockledgerlist'));?>",
						data:curr_data,
						async:false,
						success: function(response){	
							
						var json_obj = jQuery.parseJSON(response);
							jQuery('#project_code').val(json_obj['project_code']);						
							jQuery('#sl_mrn_name').html(json_obj['material_data']);  
							//alert(json_obj['material_data']);
							return false;
						},
						error: function (e) {
							 alert('Error');
						}
					});	
				});
				
				jQuery("body").on("change", "#sl_mrn_name", function(event){ 
	 
				var material_id  = jQuery(this).val() ;
				var curr_data = {	 						 					
								material_id : material_id,	 					
								};	 				
					jQuery.ajax({
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'stockledgermatcode'));?>",
						data:curr_data,
						async:false,
						success: function(response){	
							
						var json_obj = jQuery.parseJSON(response);					
							jQuery('#sl_mrn_code').val(json_obj['material_code']);						
							 
							return false;
						},
						error: function (e) {
							 alert('Error');
						}
					});	
				});
				
				
		} );
		</script>
		<?php 
				
				 $agency_a = isset($_POST['agency_id'])?$_POST['agency_id']:'';
				 $from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
				 $to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
				 $formate_a = isset($_POST['format'])?$_POST['format']:'';
				 $vendor_userid_a = isset($_POST['vendor_userid'])?$_POST['vendor_userid']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="content controls">
					<div class="col-md-12 filter-form">
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-3"><input type="text" name="from_date" id="from_date" value="<?php echo $from_date;?>"  class="datep form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-3"><input type="text" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="datep form-control"/></div>
					</div>
						<div class="form-row">
                           <!-- <div class="col-md-2">Project Code<span class="require-field">*</span> </div>
                            <div class="col-md-3"><input type="text" name="project_code" id="project_code" value="<?php// if($show || $showpost) echo $project_code;?>"
							class="form-control validate[required]" value="" readonly="true"/></div> -->
							<div class="col-md-2">Project Name*</div>
                            <div class="col-md-3">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									// foreach($projects as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['project_id'].'"'.(($project_id == $retrive_data['project_id'])?"selected":"").'>'.
										// $retrive_data['project_name'].'</option>';
									// }
								?>
								</select>
							</div>
							
						<!-- </div>
						<div class="form-row">
							<!-- <div class="col-md-2">Material Code <span class="require-field">*</span> </div>
                            <div class="col-md-3"><input type="text" name="sl_mrn_code" id="sl_mrn_code" value="<?php// if($show || $showpost) {echo $material_code;}?>" class="form-control" value=""/></div> -->
							<div class="col-md-2">Material Name*</div>
                            <div class="col-md-3">
								<select class="select2"  required="true"   style="width: 100%;" name="sl_mrn_name" id="sl_mrn_name">
								<option value="">--Select Material--</Option>
								<?php 
									// foreach($sl_data as $retrive_data)
									// {
										// if($retrive_data['material_id'] != 0)
										// {
											// $value = $retrive_data['material_id'];
											// $name = $this->ERPfunction->get_material_title($retrive_data['material_id']);
										// }
										// else
										// {
											// $value = $retrive_data['material_name'];
											// $name = $retrive_data['material_name'];
										// }
										// echo '<option value="'.$value.'" '.(($material_id == $value)?"selected":"").'>'.$name.'</option>';
									// }
								?>
								</select>
							</div>
							
                        </div>
						<div class="form-row">
							<div class="col-md-2 text-right">Agency / Asset's Name</div>
							<div class="col-md-3">
							<select class="select2 agency_id" style="width: 100%;" name="agency_id" id="agency_id" >
								<option value="All" selected>All</Option>
								<?php 
									// foreach($agency_list as $retrive_data)
									// {
										// $selected = ($retrive_data['id'] == $agency_a) ? "selected" : "";
										// echo '<option value="'.$retrive_data['id'].'" '. $selected .'>'.
										// $retrive_data['agency_name'].'</option>';
									// }
								?>
							</select>
							</div>
							<div class="col-md-2">Vendor Name:</div>
                        <div class="col-md-3">
							<select class="select2"  style="width: 100%;" name="vendor_userid" id="vendor_userid" >
								<option value="All" selected>All</Option>
								<?php 
									// foreach($vendor_department as $retrive_data)
									// {
										// $selected = ($retrive_data['user_id'] == $vendor_userid_a) ? "selected" : "";
										// echo '<option value="'.$retrive_data['user_id'].'" '. $selected .'>'.
										// $this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';									
									
									// }
								?>
							</select>
						</div>
							
						</div>
						
						
						
						<div class="form-row">
							<div class="col-md-2">Format:</div>
							<div class="col-md-3">
								<select class="select2" required="true"   style="width: 100%;" name="format" id="format" >
									<option value="All" selected>All</Option>
									<option value="grn" >GRN</Option>
									<option value="is" <?php if($formate_a == 'is') echo "selected"; ?>>IS</Option>
									<option value="rbn" <?php if($formate_a == 'rbn') echo "selected"; ?>>RBN</Option>
									<option value="mrn" <?php if($formate_a == 'mrn') echo "selected"; ?>>MRN</Option>
									<option value="sst_from" <?php if($formate_a == 'sst_from') echo "selected"; ?>>SST-1 (From)</Option>
									<option value="sst_to" <?php if($formate_a == 'sst_to') echo "selected"; ?>>SST-2 (To)</Option>
									<option value="debit_note" <?php if($formate_a == 'debit_note') echo "selected"; ?>>Debit Note</Option>
								</select>
							</div>
							<div class="col-md-2"><button type="submit" name="go" class="btn btn-primary"><?php echo 'Go';?></button></div>
						</div>
						
					</div>
					</div>
					
				<?php $this->Form->end(); ?>
				<input type="hidden" id="selected_project" value="<?php echo isset($_POST['project_id'])?$_POST['project_id']:'';  ?>">
				<input type="hidden" id="selected_material" value="<?php echo isset($_POST['sl_mrn_name'])?$_POST['sl_mrn_name']:'';  ?>">
				<input type="hidden" id="selected_agency" value="<?php echo isset($_POST['agency_id'])?$_POST['agency_id']:'';  ?>">
				<input type="hidden" id="selected_vendor" value="<?php echo isset($_POST['vendor_userid'])?$_POST['vendor_userid']:'';  ?>">
			<?php 
			if(isset($stockledger))
			{
				$consume_value = $this->ERPfunction->get_items_consumetype($material_id);
				if($consume_value == 1)
				{
					$consume_type = "Consumable";
				}elseif($consume_value == 0){
					$consume_type = "Retunable / Non-consumable";
				}elseif($consume_value == 3){
					$consume_type = "Asset";
				}else{
					$consume_type = "";
				}
				?>
				<div class="content controls">
				<hr/>
				<div class="row">
					<div class="col-md-2 text-right">Min Stock Level </div>
					<div class="col-md-2">
						<input id="min_stock_level" value="<?php echo (isset($opening_stock))? $opening_stock["min_quantity"]:"";?>" <?php echo ($role != "billingengineer" || $role != "constructionmanager" || $role != "planningengineer")?'readonly':''; ?>/>
					</div>
					<div class="col-md-1 text-right">Unit  </div>
					<div class="col-md-2">
						<input readonly value="<?php echo $this->ERPfunction->get_items_units($material_id);?>"/>
					</div>
					<div class="col-md-2 text-right">Consume Type  </div>
					<div class="col-md-3">
						<input readonly value="<?php echo $consume_type;?>"/>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-2 text-right">Max Purchase </div>
					<div class="col-md-2">
						<input id="max_purchase_level" value="<?php echo (isset($opening_stock))?$opening_stock["max_quantity"]:"";?>" <?php echo ($role != "billingengineer" || $role != "constructionmanager" || $role != "planningengineer")?'readonly':''; ?>/>
					</div>
					<div class="col-md-1 text-right">Unit </div>
					<div class="col-md-2">
						<input readonly value="<?php echo $this->ERPfunction->get_items_units($material_id);?>" />
					</div>
					<div class="col-md-2 text-right">Cost Group </div>
					<div class="col-md-3">
						<input readonly value="<?php echo $this->ERPfunction->get_items_costgroup($material_id);?>"/>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-2 text-right">Symbolic Balance </div>
					<div class="col-md-2">
						<input readonly id="symbolic_balance" value=""/>
					</div>
					
					<div class="col-md-2 text-right">Available Stock </div>
					<div class="col-md-2">
						<input id="avail_stock" value=""/>
					</div>
					
					<div class="col-md-1 text-right">Unit </div>
					<div class="col-md-2">
						<input readonly value="<?php echo $this->ERPfunction->get_items_units($material_id);?>"/>
					</div>
				</div>
				
				<hr/>
				
					
				
				<script>
		jQuery(document).ready(function() {
			var table = jQuery('.stockledger_table').DataTable({responsive: {
												// "sorting":false,
												// "ordering": false
												// details: {
													// type: 'column',
													// target: -1
												// }
											},
											// columnDefs: [ {
												// className: 'control',
												// orderable: false,
												// targets:   -1
											// } ],
											aaSorting: false
											});
											$(".stockledger_table").DataTable().page('last').draw('page');
			$('#old_look').on( 'click', function () {
				table.destroy();
				return false;
			} );
			
		});
</script>
				<table class="table table-bordered stockledger_table">
					<thead>
						<tr>
							<th rowspan="2">Date</th>
							<th rowspan="2">Description</th>
							<th rowspan="2">GRN / SST / IS / MRN / RBN No</th>
							<th colspan="1">Stock In</th>
							<th colspan="1">Stock Out</th>
							<th rowspan="2">Symbolic Balance</th>
							<th rowspan="2">Balance</th>
							<th rowspan="2">Unit</th>
							<?php
							if($this->ERPfunction->retrive_accessrights($role,'previewapprovedsst')==1 || $this->ERPfunction->retrive_accessrights($role,'previewapprovedmrn')==1 || $this->ERPfunction->retrive_accessrights($role,'previewapprovedis')==1 || $this->ERPfunction->retrive_accessrights($role,'previewapprovedgrn')==1 || $this->ERPfunction->retrive_accessrights($role,'previewpr')==1)
							{
							?>
							<th rowspan="2">View</th>
							<?php } ?>
						</tr>
						<tr>
							<th>Received</th>
							<!--<th>Returned Back</th>-->
							<th>Issued</th>
							<!--<th>Transferred/<br>Return</th>-->
							<!-- <th>Damaged</th> -->
						</tr>
					</thead>
					<tbody>
					<tr>
						<td><?php 
						// echo (isset($opening_stock))?$opening_stock["date"]->format("Y-m-d"):"";
						echo (isset($opening_stock))?$opening_stock["date"]:"";
						?></td>
						<td>Opening Stock</td>
						<td></td>
						<!--<td></td>
						<td></td>-->
						<td></td>
						<td></td>											
						<td></td>											
						<td><?php 
						/* Previous Date Balance */
						$previous_balance = 0;
						$old_stock = (isset($opening_stock))?intval($opening_stock["quantity"]):"0";
						if($from_date != '')
						{
							$previous_balance = $this->ERPfunction->get_material_stock_previous_date($project_id,$material_id,$from_date);
							$previous_balance = $old_stock + $previous_balance;
						}else{
							$previous_balance = $old_stock;
						}
						/* Previous Date Balance */
						
						echo $previous_balance;?>
							<input type="hidden" id="cos" value="<?php echo $old_stock;?>" />
						</td>
						<td></td>	
						<td></td>
					</tr>
					<?php
					$old_symbolic_stock = $old_stock;
					$previous_balance = 0;
					$t_previous_balance = 0;
					if(!empty($stockledger))
					{	
						/* Previous Date Balance */
						if($from_date != '')
						{
							$previous_balance = $this->ERPfunction->get_material_stock_previous_date($project_id,$material_id,$from_date);
							$previous_balance = $old_stock + $previous_balance;
							$t_previous_balance = $old_stock + $t_previous_balance;
						}else{
							$previous_balance = $old_stock;
							$t_previous_balance = $old_stock;
						}
						/* Previous Date Balance */
						$old_symbolic_stock = $old_stock;
						$i=1;
						$rows = array();
						$rows[] = array("Date","Description","GRN / SST / IS / MRN / RBN No","Received","Issued","Symbolic Balance","Balance","Unit");
						$rows[] = array("","Opening Stock","",$previous_balance,"","",$previous_balance,"");
						foreach($stockledger as $retrive_data)
						{
							$csv = array();
							$desc_code = $this->ERPfunction->get_stockledger_description_code($retrive_data["type"],$retrive_data["type_id"]);
							$warning = false;
							
							if(isset($opening_stock))
							{
								if($opening_stock["max_quantity"] != 0 && $opening_stock["min_quantity"] != 0)
								{
									if($old_stock > $opening_stock["max_quantity"])
									{
										$warning = true;
										$color = "red";
									}
									if($old_stock < $opening_stock["min_quantity"])
									{
										$warning = true;
										$color = "yellow";									
									}
									
								}
							}
							?>
						<tr> 
							<td><?php echo ($csv[] = ($retrive_data["date"] != null) ? $retrive_data["date"]->format("d-m-Y") : ""); ?></td>
							<td><?php echo ($csv[] = $desc_code["desc"]);?></td>
							<td><?php echo ($csv[] = $desc_code["code"]);?></td>
							<td>
							<?php 
							$val = "";
							if($retrive_data["type"] == 'grn' || $retrive_data["type"] == 'mrn' || $retrive_data["type"] == 'sst_from')
							{
								if($retrive_data["type"] == 'grn'){
									$sign = "+";
								}elseif($retrive_data["type"] == 'mrn'){
									$sign = "-";
								}elseif($retrive_data["type"] == 'sst_from'){
									$sign = "-";
								}
								
								echo $val = $sign.$retrive_data['quantity'];
							}
							$csv[] = $val;
							?>
							</td>
							<!--<td><?php //echo ($csv[] = ($retrive_data['return_back']!= 0)?$retrive_data['return_back']:""); ?></td>-->
							<td>
							<?php
							$val1 = "";
							if($retrive_data["type"] == 'is' || $retrive_data["type"] == 'rmc' || $retrive_data["type"] == 'rbn' || $retrive_data["type"] == 'debit' || $retrive_data["type"] == 'debit_party')
							{
								// $sign = ($retrive_data["type"] == 'is')?"+":"-";
								if($retrive_data["type"] == 'is'){
									$sign = "+";
								}elseif($retrive_data["type"] == 'rmc'){
									$sign = "+";
								}elseif($retrive_data["type"] == 'rbn'){
									$sign = "-";
								}elseif($retrive_data["type"] == 'debit'){
									$sign = "+";
								}elseif($retrive_data["type"] == 'debit_party'){
									$sign = "-";
								}
								echo $val1 = $sign.$retrive_data['quantity'];
							}
							$csv[] = $val1;
							?>
							</td>
							<!--<td><?php //echo ($csv[] = ($retrive_data['transferred']!= 0)?$retrive_data['transferred']:"");?></td>-->
							<td>
							<?php
							if($consume_value == 0 || $consume_value == 3)
							{
								// echo $old_symbolic_stock = $this->ERPfunction->get_symbolic_stock_balance($retrive_data["type"],$old_symbolic_stock,$retrive_data["quantity"]);
								echo $old_symbolic_stock = $this->ERPfunction->get_symbolic_stock_balance($retrive_data["type"],$t_previous_balance,$retrive_data["quantity"]);
								
								$csv[] = $t_previous_balance = $this->ERPfunction->get_symbolic_stock_balance($retrive_data["type"],$t_previous_balance,$retrive_data["quantity"]);
							}else{
								// echo $old_symbolic_stock = $this->ERPfunction->get_stock_balance($retrive_data["type"],$old_stock,$retrive_data["quantity"]);
								echo $old_symbolic_stock = $this->ERPfunction->get_stock_balance($retrive_data["type"],$previous_balance,$retrive_data["quantity"]);
								
								$csv[] = $this->ERPfunction->get_stock_balance($retrive_data["type"],$previous_balance,$retrive_data["quantity"]);
							}
							?>
							</td>
						<!-- <td><?php //echo ($retrive_data['damaged_qty']!= 0)?$retrive_data['damaged_qty']:"";?></td> -->
							<td  <?php //echo ($warning)?"bgcolor='{$color}'":"";?>>
							<?php 
							// echo $old_stock = $this->ERPfunction->get_stock_balance($retrive_data["type"],$old_stock,$retrive_data["quantity"]);
							echo $old_stock = $this->ERPfunction->get_stock_balance($retrive_data["type"],$previous_balance,$retrive_data["quantity"]);
							
							$csv[] = $previous_balance = $this->ERPfunction->get_stock_balance($retrive_data["type"],$previous_balance,$retrive_data["quantity"]);
							?>
							</td>
							<td><?php echo ($csv[] = ($retrive_data['material_id']!= 0)?$this->ERPfunction->get_items_units($retrive_data["material_id"]):$retrive_data['static_unit']);?></td>
							
							
							<?php 
							if($this->ERPfunction->retrive_accessrights($role,'previewapprovedsst')==1 || $this->ERPfunction->retrive_accessrights($role,'previewapprovedmrn')==1 || $this->ERPfunction->retrive_accessrights($role,'previewapprovedis')==1 || $this->ERPfunction->retrive_accessrights($role,'previewapprovedgrn')==1 || $this->ERPfunction->retrive_accessrights($role,'previewpr')==1 || $this->ERPfunction->retrive_accessrights($role,'previewapprovedrbn')==1)
							{	echo "<td>";
								$src = "";
								switch($retrive_data["type"])
								{
									CASE "is" :
										if($this->ERPfunction->retrive_accessrights($role,'previewapprovedis')==1)
										$src = $this->request->base ."/inventory/previewapprovedis";
									break;
									
									CASE "grn" :
										if($this->ERPfunction->retrive_accessrights($role,'previewapprovedgrn')==1)
										$src = $this->request->base ."/inventory/previewapprovedgrn";
									break;
									
									CASE "rbn" :
										if($this->ERPfunction->retrive_accessrights($role,'previewapprovedrbn')==1)
										$src = $this->request->base ."/inventory/previewapprovedrbn";
									break;
									
									CASE "mrn" :
										if($this->ERPfunction->retrive_accessrights($role,'previewapprovedmrn')==1)
										$src = $this->request->base ."/inventory/previewapprovedmrn";
									break;
									
									CASE "sst" :
										if($this->ERPfunction->retrive_accessrights($role,'previewapprovedsst')==1)
										$src = $this->request->base ."/inventory/previewapprovedsst";
									break;
									
									CASE "sst_from" :
										if($this->ERPfunction->retrive_accessrights($role,'previewapprovedsst')==1)
										$src = $this->request->base ."/inventory/previewapprovedsst";
									break;
									
									CASE "sst_to" :
										if($this->ERPfunction->retrive_accessrights($role,'previewapprovedsst')==1)
										$src = $this->request->base ."/inventory/previewapprovedsst";
									break;
									
									CASE "debit" :
										if($this->ERPfunction->retrive_accessrights($role,'previewdebit')==1)
										$src = $this->request->base ."/inventory/previewdebit";
									break;
									
									CASE "debit_party" :
									if($this->ERPfunction->retrive_accessrights($role,'previewdebit')==1)
										$src = $this->request->base ."/inventory/previewdebit";
									break;
									
									CASE "rmc" :
									// if($this->ERPfunction->retrive_accessrights($role,'previewdebit')==1)
										$src = $this->request->base ."/inventory/viewinventoryrmc";
									break;
									
								}
							if($src != "")
							{
							?>
							<a href="<?php echo $src."/".$retrive_data["type_id"]; ?>/stock" target="_blank" class="btn btn-sm btn-primary">View</a>
							<?php } 
							else { echo "NA"; }	
							echo "</td>";
							}
							?>
						</tr>						
					<?php 
					$i++; 
					$rows[] = $csv;
					}
						
						
					}else
					{
						echo "<tr><td colspan='11'>No Data Found.</td></tr>";
					}
					?>
					</tbody>
				</table>
				<?php 
				echo "<input type='hidden' value='{$old_symbolic_stock}' id='symbolic_stock'>";
						echo "<input type='hidden' value='{$old_stock}' id='final_stock'>";
				?>
				<br><br><br>
				<?php
				if(isset($stockledger))
				{
				if(!empty($stockledger)){
				?>
				<div class="col-md-2">
					<form method="post">
						<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
						<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
					</form>
				</div>
				<div class="col-md-2">
				<div class="col-md-1 col-xs-offset-5">
					<button class="btn btn-sm btn-primary" id="old_look">Old Look</button>
				</div>
				<?php } } ?>
				<?php
			}
			?>
			
			
			
			
			
			</div> <!-- 2nd content END -->
			
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>
<script>
$(document).ready(function(){
	var final_stock = $("#final_stock").val();
	var symbolic_stock = $("#symbolic_stock").val();
	$("#avail_stock").val(final_stock);
	$("#symbolic_balance").val(symbolic_stock);
	// var cos = $("#cos").val();
	var cos = $("#min_stock_level").val();
	var max_purchase = $("#max_purchase_level").val();
	
	if(parseInt(final_stock) > parseInt(max_purchase))
	{ 
		$("#avail_stock").css("color","red");
	}else{
		if(parseInt(final_stock) < parseInt(cos))
		{ 
			$("#avail_stock").css("background-color","red");
			$("#avail_stock").css("font-weight","bold");
		}
		if(parseInt(final_stock) > parseInt(cos) )
		{ 
			$("#avail_stock").css("background-color","yellow");
			$("#avail_stock").css("font-weight","bold");
			$("#avail_stock").css("color","#000000");
		}
	}
	
});

</script>