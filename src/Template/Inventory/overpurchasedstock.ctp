<?php
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
<div class="row">
	<div class="col-md-12">
		<div class="block">			
			<div class="head bg-default bg-light-rtl">
				<h2>View Records - Over Purchased Stock</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			<?php 
				
				 $stock_level = isset($_POST['minimum_stock'])?$_POST['minimum_stock']:'';
				 $purchase_level = isset($_POST['maximum_purchase'])?$_POST['maximum_purchase']:'';
				
				
			?>
			<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="content controls">
						<div class="form-row">
                      
							<div class="col-md-2">Project Name</div>
                            <div class="col-md-3">
								<select class="select2"    style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
							
							<div class="col-md-2">Material  Name</div>
                            <div class="col-md-3">
								<select class="select2" style="width: 100%;" name="sl_mrn_name[]" id="sl_mrn_name" 
multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($sl_data as $retrive_data)
									{
										// echo '<option value="'.$retrive_data['material_id'].'">'.$this->ERPfunction->get_material_title($retrive_data['material_id']).'</option>';
										if($retrive_data['material_id'] != 0)
										{
											$value = $retrive_data['material_id'];
											$name = $this->ERPfunction->get_material_title($retrive_data['material_id']);
										}
										else
										{
											$value = $retrive_data['material_name'];
											$name = $retrive_data['material_name'];
										}
										echo '<option value="'.$value.'">'.$name.'</option>';
									}
								?>
								</select>
							</div>
							
                        </div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">Minimum Stock Level</div>
							<div class="col-md-3"><input name="minimum_stock" class="form-control" /></div>
							<div class="col-md-2 text-right">Maximum Purchase Level</div>
							<div class="col-md-3"><input name="maximum_purchase" class="form-control"></div>
					</div>
					
					<div class="form-row">
                      
							<div class="col-md-2">Consume Type</div>
                            <div class="col-md-3">
								<select class="select2" style="width: 100%;" id="consume" name="consume[]" multiple="multiple">
									<option value="1">Consumable</option>
									<option value="0">Retunable / Non-consumable</option>
									<option value="3">Asset</option>
								</select>
							</div>
							
							<div class="col-md-2">Cost Group</div>
                            <div class="col-md-3">
								<select class="select2" style="width: 100%;" id="cost_group" name="cost_group[]" multiple="multiple">
									<option value="a">A</option>
									<option value="b">B</option>
									<option value="c">C</option>
								</select>
							</div>
							
                        </div>
					
					<div class="form-row">
					<div class="col-md-2"> <div class="col-md-12"><button type="submit" name="go" class="btn btn-primary"><?php echo 'Search';?></button></div></div>
					</div>
						
					</div>
					
				<?php echo $this->Form->end(); ?>
<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["sl_mrn_name"]) ? implode(",",$_POST["sl_mrn_name"]) : "";?>">

<input type="hidden" id="f_minimum_stock" value="<?php echo isset($_POST["minimum_stock"]) ? $_POST["minimum_stock"] : "";?>">
<input type="hidden" id="f_maximum_purchase" value="<?php echo isset($_POST["maximum_purchase"]) ? $_POST["maximum_purchase"] : "";?>">
<input type="hidden" id="f_consume" value="<?php echo isset($_POST["consume"]) ? implode(",",$_POST["consume"]) : "";?>">
<input type="hidden" id="f_cost_group" value="<?php echo isset($_POST["cost_group"]) ? implode(",",$_POST["cost_group"]) : "";?>">
		<div class="content list custom-btn-clean">
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

		jQuery('body').on('click','.viewmodal',function(){
			
				
				var project_id  = jQuery(this).attr('p_id') ;
				var material_id  = jQuery(this).attr('m_id') ;
				
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'managestock'));?>";
				
				var curr_data = {project_id:project_id, material_id:material_id };	 				
					jQuery.ajax({
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
		jQuery(document).ready(function() {
			var f_pro_id  = jQuery("#f_pro_id").val();
			var f_material_id  = jQuery("#f_material_id").val();
			var f_minimum_stock  = jQuery("#f_minimum_stock").val();
			var f_maximum_purchase  = jQuery("#f_maximum_purchase").val();
			var f_cost_group  = jQuery("#f_cost_group").val();
			var f_consume  = jQuery("#f_consume").val();

		var selected = [];
		var table = jQuery('#mrn_list').DataTable({
			"order": [[ 0, "desc" ]],
			columnDefs: [ 
						{
							searchable: false,
							targets:   1,
						},
						{
							searchable: false,
							targets:   2,
						},
						{
							orderable: false,
							searchable: false,
							targets:   6,
						},
						{
							orderable: false,
							searchable: false,
							targets:   8,
						},
						{
							searchable: false,
							targets:   9,
						},
						{
							searchable: false,
							targets:   10,
						},
						{
							searchable: false,
							targets:   12,
						},
						{
							searchable: false,
							targets:   13,
						}					
						],
				
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
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": false },
					{ "visible": false },
							  ],
			"responsive" : true,
			"processing": true,
			"serverSide": true,
			//"ajax": "../Ajaxfunction/billrecordsdata",
			"ajax": {
					"url": "../Ajaxfunction/inventoryoverpurchasedstock",
					"data": function ( d ) {
												d.myKey = "myValue";
												d.pro_id = f_pro_id;
												d.material_id = f_material_id;
												d.minimum_stock = f_minimum_stock;
												d.maximum_purchase = f_maximum_purchase;
												d.consume = f_consume;
												d.cost_group = f_cost_group;
											}
					},
			"rowCallback": function( row, data ) {
									
									if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
										jQuery(row).addClass('selected');
									}
							},
			});
		
			// jQuery('#mrn_list').DataTable({responsive: true});
			jQuery("body").on("change", ".approve", function(event){
				var mrn_id = jQuery(this).val();
				var data_role = jQuery(this).attr('data-role');
				
				if(confirm('Are you Sure approve this M.R.N.?'))
				{
				var curr_data = {	 						 					
	 					mrn_id : mrn_id,
						data_role :data_role,
	 					};	 				
	 	 jQuery.ajax({
               headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvemrn'));?>",
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
				jQuery(this).prop('checked', true);
			}
			});	
		} );
</script>
			<table id="mrn_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project Name</th>						
						<th>Material Code</th>					
						<th>Material Name</th>
						<th>Consume Type</th>
						<th>Cost Group</th>
						<th>Max Purchase Level</th>
						<th>Total Stock In</th>
						<th class="never">% Stock Purchase</th>
						<th>Total Stock Out</th>
						<th>Symbolic Balance</th>
						<th>Current Balance</th>
						<th>Min Stock Level</th>						
						<th>Unit</th>
						<th class="none">View Stock Ledger</th>						
						<th class="never"></th>						
						<th class="never"></th>						
					</tr>
				</thead>
				<!-- <tbody>
					<?php
						$rows = array();
						$rows[] = array("Project Name","Material Code","Material Name","Max Purchase Level","Total Stock In","% Stock Purchase","Total Stock Out","Current Balance","Min Stock Level","Unit");
						$i = 1;
						foreach($result as $retrive_data)
						{
							$csv = array();
							if($retrive_data['material_id'] != 0)
							{
								$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
								$m_id = $retrive_data['material_id'];
							}
							else
							{
								$mt = $retrive_data['material_name'];
								$m_id = $retrive_data['material_name'];
							}
						?>
							<tr <?php  if($stock_level != "" && $purchase_level != ""){ echo ($stock_level > $retrive_data['max_quantity'] && $purchase_level < $retrive_data['min_quantity'])?"class=''":"";}?> <?php  if($stock_level != ""){ echo ($stock_level > $retrive_data['max_quantity'])?"class='show_warning'":"";}?> <?php  if($purchase_level != ""){ echo ($purchase_level < $retrive_data['min_quantity'])?"class='green'":"";}?>>								
							
								<td><?php echo ($csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>								
								<td><?php echo ($csv[] = $this->ERPfunction->get_materialitemcode($retrive_data['material_id']));?></td>														
								<td><?php echo ($csv[] = $mt);?></td>														
								<td><?php echo ($csv[] = $retrive_data['max_quantity']);?></td>										
								<td><?php echo ($csv[] = bcdiv($retrive_data['total_stock_in'],1,3));?></td>
								
								<td><?php echo ($csv[] = ($retrive_data['max_quantity'] != 0) ? bcdiv($retrive_data['total_stock_in']/$retrive_data['max_quantity'],1,3) : "NA");?></td>
								
								<td><?php echo ($csv[] = bcdiv($retrive_data['total_stock_out'],1,3));?></td>
								
								<td><?php echo ($csv[] = bcdiv($this->ERPfunction->get_current_stock($retrive_data['project_id'],$m_id),1,3));?></td>
								
								<td><?php echo ($csv[] = $retrive_data['min_quantity']);?></td>
												
								<td><?php echo ($csv[] = $this->ERPfunction->get_items_units($retrive_data['material_id']));?></td>														
								<td>
								<?php 
								if($retrive_data['material_id'])
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'stockledger',$retrive_data['project_id'],$retrive_data['material_id']),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								}
								else
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'stockledger',$retrive_data['project_id'],$retrive_data['material_name']),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								}
								?>
								<?php
									if($role == "erphead" || $role == "md" || $role == "projectdirector" || $role == "constructionmanager" || $role == "billingengineer" || $role == "planningengineer")
									{
								?>
								<button type="button" data-toggle="modal" p_id="<?php echo $retrive_data['project_id']; ?>" m_id="<?php echo $m_id; ?>"
								data-target="#load_modal" class="btn btn-primary btn-clean viewmodal"><i class='icon-eye-open'></i>Manage Stock </button>
								<?php } ?>
								</td>
									
								</tr>
						<?php
						$i++;
						$rows[] = $csv;
						}
					?>
				</tbody> -->
			</table>
			
			<!--<div class="content">
				<!-- <div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php //echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				-->
				
				<!--<div class="col-md-2">
				<form method="post">
					<input type="hidden" name="rows" value='<?php //echo serialize($rows);?>'>
					<input type="hidden" name="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="f_material_id" value="<?php echo isset($_POST["sl_mrn_name"]) ? implode(",",$_POST["sl_mrn_name"]) : "";?>">

					<input type="hidden" name="f_minimum_stock" value="<?php echo isset($_POST["minimum_stock"]) ? $_POST["minimum_stock"] : "";?>">
					<input type="hidden" name="f_maximum_purchase" value="<?php echo isset($_POST["maximum_purchase"]) ? $_POST["maximum_purchase"] : "";?>">
					<input type="hidden" name="f_consume" value="<?php echo isset($_POST["consume"]) ? implode(",",$_POST["consume"]) : "";?>">
					<input type="hidden" name="f_cost_group" value="<?php echo isset($_POST["cost_group"]) ? implode(",",$_POST["cost_group"]) : "";?>">
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				</form>
				</div>
				<div class="col-md-2">
				<form method="post">
					<input type="hidden" name="rows" value='<?php //echo serialize($rows);?>'>
					<input type="hidden" name="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="f_material_id" value="<?php echo isset($_POST["sl_mrn_name"]) ? implode(",",$_POST["sl_mrn_name"]) : "";?>">

					<input type="hidden" name="f_minimum_stock" value="<?php echo isset($_POST["minimum_stock"]) ? $_POST["minimum_stock"] : "";?>">
					<input type="hidden" name="f_maximum_purchase" value="<?php echo isset($_POST["maximum_purchase"]) ? $_POST["maximum_purchase"] : "";?>">
					
					<input type="hidden" name="f_consume" value="<?php echo isset($_POST["consume"]) ? implode(",",$_POST["consume"]) : "";?>">
					<input type="hidden" name="f_cost_group" value="<?php echo isset($_POST["cost_group"]) ? implode(",",$_POST["cost_group"]) : "";?>">
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				</form>
				</div>
				
			</div>-->
		</div>
		</div>
	</div>
</div>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<?php }?>
</div>