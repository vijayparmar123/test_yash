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
</script>
<script type="text/javascript" >
		jQuery(document).ready(function() {
			jQuery('#asset_list').DataTable({responsive: true});
			
			jQuery('.viewmodal').click(function(){			
				payid=jQuery(this).attr('id');
				jQuery('#modal-view').html('hello');
				var model  = jQuery(this).attr('data-type') ;
				var asset_id  = jQuery(this).attr('asset_id') ;
				var deployed_to  = jQuery(this).attr('deployed_to') ;
				var urlstring = '';
				
				if(model == 'soldasset')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'soldasset'));?>";
				}
				if(model == 'theftasset')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'theftasset'));?>";
				}
			 
				var curr_data = {type : model,asset_id:asset_id,deployed_to:deployed_to};	 				
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
								console.log(e);
								 }
					});			
			});
		} );
</script>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>

<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>    
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Sold / Theft Asset</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
			<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				$project_id = array();
				$make_id_a = array();
				$asset_group = array();
				 $project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
				 $purchase_from_date = isset($_POST['purchase_from_date'])?$_POST['purchase_from_date']:'';
				 $purchase_to_date = isset($_POST['purchase_to_date'])?$_POST['purchase_to_date']:'';
				 $make_id_a = isset($_POST['make_id'])?$_POST['make_id']:'';
				 $asset_group = isset($_POST['asset_group'])?$_POST['asset_group']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date of Purchase From -</div>
                        <div class="col-md-4"><input type="text" name="purchase_from_date" id="purchase_from_date" value="<?php echo $purchase_from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">Date of Purchase To -</div>
                        <div class="col-md-4"><input type="text" name="purchase_to_date" id="purchase_to_date" value="<?php echo $purchase_to_date;?>" class="datep form-control"/></div>
					</div>
					<div class="form-row">	
						
						<div class="col-md-2">Currently Deployed To:</div>
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
						<div class="col-md-2">Make:</div>
                        <div class="col-md-4">
							<select class="select2 make_id" style="width: 100%;" name="make_id[]" id="make_id_0" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($makelist as $retrive_data)
									{
										$selected = (in_array($retrive_data['cat_id'],$make_id_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['cat_id'].'" '.$selected.'>'.
										$retrive_data['category_title'].'</option>';
									}
								?>
							</select>
						</div>
                    </div>
					<div class="form-row">
							<div class="col-md-2 text-right">Asset Group</div>
							<div class="col-md-4">								
								<select style="width: 100%;" class="select2"  name="asset_group[]" id="asset_group" multiple="multiple">
								<option value="All">All</option>
								<?php 
								foreach($asset_groups as $key => $retrive_data)
								{
									//echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$asset_group).'>'.$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
									$selected = (in_array($retrive_data['id'],$asset_group)) ? "selected" : "";
										echo '<option value="'.$retrive_data['id'].'" '.$selected.'>'.
										$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
								}
								?>
								</select>
							</div>
							<div class="col-md-2 text-right">Asset Name</div>
							<div class="col-md-4">
							<?php
								echo $this->Form->select("asset_name",$asset_name,["empty"=>["All"=>"All"],"class"=>"select2","style"=>"width:100%","multiple"=>"multiple"]);
							?>
							</div>
						</div>
					
					<div class="form-row">
							<div class="col-md-2 text-right">Asset ID</div>
							<div class="col-md-4"><input name="asset_id" class="" /></div>
							<div class="col-md-2 text-right">Asset Capacity</div>
							<div class="col-md-4"><input name="asset_capacity" class="" /></div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Identity / Veh. No.</div>
							<div class="col-md-4"><input name="identity" class="" /></div>
						</div>
							
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
			</div>
			</div>
			
			
		<div class="content list custom-btn-clean">


			<table id="asset_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Asset Group</th>
						<th>Asset ID</th>
						<th>Asset Name</th>
						<th>Capacity</th>						
						<th>Make</th>						
						<th>Identity<br>/Veh.No.</th>
						<th>Operational Status</th>						
						<th>Currently Deployed To</th>	
						<!--<th>Deployed Quantity</th>-->
						<th>Unit</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$rows = array();
						$rows[] = array("Asset Group","Asset ID","Asset Name","Capacity","Make","Identity/Veh.No.","Operational Status","Currently Deployed to","Unit");
						$i = 1;
						foreach($asset_list as $retrive_data)
						{
							$csv = array();
						?>
							<tr>
								<td><?php 
									 echo ($csv[] = $this->ERPfunction->get_asset_group_name($retrive_data['asset_group']));?>   
								</td>
								<td><?php echo ($csv[] = $retrive_data['asset_code']); ?></td>
								<td><?php echo ($csv[] = $retrive_data['asset_name']);?></td>
								<td><?php echo ($csv[] = $retrive_data['capacity']);?></td>
								<td><?php echo ($csv[] = $this->ERPfunction->get_category_title($retrive_data['asset_make']));?></td>
								<td><?php echo ($csv[] = $retrive_data['vehicle_no']);?></td>
								<td><?php echo ($csv[] = $this->ERPfunction->get_asset_operational_status($retrive_data['asset_id']));?></td>
								<td><?php echo ($csv[] = $this->ERPfunction->get_projectname($retrive_data['deployed_to']));?></td>								
								<!--<td><?php echo $retrive_data['quantity'];?></td>-->
								<td><?php echo ($csv[] = $retrive_data['unit']);?></td>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'soldtheft')==1)
								{
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewasset',$retrive_data['asset_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'add')==1)
								{
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'add',$retrive_data['asset_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								echo ' ';
								}
								/* echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'delete', $retrive_data['asset_id']),
								array('class'=>'btn  btn-danger btn-clean','escape'=>false, 
								'confirm' => 'Are you sure you wish to Delete this Record?'));	 */
								echo ' ';
								if($this->ERPfunction->retrive_accessrights($role,'soldasset')==1)
								{
									echo '<button type="button"  id="soldasset" data-type="soldasset" data-toggle="modal" 
									data-target="#load_modal" class="btn btn-info viewmodal btn-clean" deployed_to="'.$retrive_data['deployed_to'].'" asset_id="'.$retrive_data['asset_id'].'"><i class="icon-trash"></i>  Sold </button>';
									echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'theftasset')==1)
								{
									echo '<button type="button"  id="theftasset" data-type="theftasset" data-toggle="modal" 
									data-target="#load_modal" class="btn btn-warning viewmodal btn-clean" deployed_to="'.$retrive_data['deployed_to'].'" asset_id="'.$retrive_data['asset_id'].'"><i class="icon-trash"></i>  Theft </button>';
								}
								echo ' ';
								?>
								</td>
							</tr>
						<?php
						$i++;
						$rows[] = $csv;
						}
					?>
				</tbody>
			</table>
			<div class="content">
					<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
					<div class="col-md-2">
					<?php echo $this->Form->Create('form3',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post'],['url'=>['action'=>'']]);?>
						<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
						<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
					<?php $this->Form->end(); ?>
					</div>
					<div class="col-md-2">
					<?php echo $this->Form->Create('form3',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post'],['url'=>['action'=>'']]);?>
						<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
						<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
					<?php $this->Form->end(); ?>
					</div>
				</div>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>