<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<div class="col-md-10" >
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
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
			
			jQuery('body').on('change','#stock',function(){
				var stock_type = $(this).val();
				if(stock_type == "NEW")
				{
					$("#old_code").css("display","none");
					$("#new_code").css("display","block");
				}else{
					$("#old_code").css("display","block");
					$("#new_code").css("display","none");
				}					
			});
			
			jQuery('body').on('click','.add_quentity',function(){
				if(confirm("Are you sure,you want to add quantity?"))
				{
					var row = $(this).attr('row');
					var add_in = $(this).attr('add-in');
					$('#load_modal').modal('show');
					
					var curr_data = {row : row , add_in : add_in};
					
					jQuery.ajax({
							type:"POST",
							url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addquentity'));?>",
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
				}
									
			});
		
	});
</script>
<div class="row">
	<div class="col-md-12">
		<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Temporary</h2>
			<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Temporary','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
			<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				 $stock = isset($_POST['stock'])?$_POST['stock']:'';
				 $width = isset($_POST['width'])?$_POST['width']:'';
				 $old_code = isset($_POST['OLD_code'])?$_POST['OLD_code']:'';
				 $new_code = isset($_POST['NEW_code'])?$_POST['NEW_code']:'';
				 $length = isset($_POST['length'])?$_POST['length']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
							
			<div class="form-row">
					<div class="col-md-2">Stock</div>
					<div class="col-md-4">
						<select class="select2" style="width: 100%;" name="stock" id="stock">
							<option value="OLD" <?php echo  ($stock == 'OLD')?'selected':''; ?>>OLD</Option>
							<option value="NEW" <?php echo ($stock == 'NEW')?'selected':''; ?>>NEW</Option>
						</select>
					</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Width</div>
				<div class="col-md-2">
					<input type="text" name="width" value="<?php echo $width; ?>" class="form-control">
				</div>
				
				<div class="col-md-1">Code</div>
				<div class="col-md-2" id="old_code" style="display:<?php echo (!isset($_POST) || $stock != 'NEW')?'block':'none';?>">
					<select class="select2" style="width: 100%;" name="OLD_code">
					<option value="">Select Code</Option>
					<?php
						foreach($old_codes as $key=>$value)
						{
							$selected = ($key == $old_code)?'selected':'';
							echo "<option value='{$key}' {$selected}>{$value}</Option>";
						}
					?>
					</select>
				</div>
				
				<div class="col-md-2" id="new_code" style="display:<?php echo ($stock == 'NEW')?'block':'none';?>">
					<select class="select2" style="width: 100%;" name="NEW_code">
					<option value="">Select Code</Option>
					<?php
						foreach($newly_codes as $key=>$value)
						{
							$selected = ($key == $new_code)?'selected':'';
							echo "<option value='{$key}' {$selected}>{$value}</Option>";
						}
					?>
					</select>
				</div>
				
				<div class="col-md-1">Length</div>
				<div class="col-md-2">
					<input type="text" name="length" value="<?php echo $length; ?>" class="form-control">
				</div>
			</div>
                    	
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
			</div>
			</div>
					
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#temporary_table').DataTable({responsive: true});
		} );
</script>

			<table id="temporary_table"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Ref No</th>
						<th>Stream</th>
						<th>Unit</th>
						<th>OLD Width</th>
						<th>OLD Code</th>
						<th>OLD Length</th>
						<th>OLD Qty</th>
						<th>Found</th>
						<th>Stock</th>
						<th>Modify to</th>
						<th>NEW Width</th>
						<th>NEW Code</th>
						<th>NEW Length</th>
						<th>NEW Rk</th>
						<th>New Qty</th>
						<th>Made</th>
						<th>Remarks</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(!empty($temporary_data))
						{
							$rows = array();
							$rows[] = array("Ref No","Stream","Unit","OLD Width","OLD Code","OLD Length","OLD Qty","Found","Stock","Modify to","NEW Width","NEW Code","NEW Length","NEW Rk","New Qty","Made","Remarks");
						
							foreach($temporary_data as $data)
							{
								$csv = array();
															
							?>
								<tr id="row_<?php echo $data['Ref_No']; ?>">
									<td><?php echo ($csv[] = $data['Ref_No']); ?></td>
									<td><?php echo ($csv[] = $data['Stream']);?></td>
									<td><?php echo ($csv[] = $data['Unit']);?></td>
									<td><?php echo ($csv[] = $data['OLD_Width']);?></td>
									<td><?php echo ($csv[] = $data['OLD_Code']);?></td>
									<td><?php echo ($csv[] = $data['OLD_Length']);?></td>
									<td><?php echo ($csv[] = $data['OLD_Qty']);?></td>
									<td><?php echo ($csv[] = $data['Found']);?></td>
									<td><?php echo ($csv[] = $data['Stock']);?></td>
									<td><?php echo ($csv[] = $data['Modify_to']);?></td>
									<td><?php echo ($csv[] = $data['NEW_Width']);?></td>
									<td><?php echo ($csv[] = $data['NEW_Code']);?></td>
									<td><?php echo ($csv[] = $data['NEW_Length']);?></td>
									<td><?php echo ($csv[] = $data['NEW_Rk']);?></td>
									<td><?php echo ($csv[] = $data['NEW_Qty']);?></td>
									<td><?php echo ($csv[] = $data['Made']);?></td>
									<td><?php echo ($csv[] = $data['Remarks']);?></td>
									<td>
										<?php
											if($data['OLD_Qty'] != $data['Found']){
										?>
										<button type="button" class="btn btn-primary add_quentity" row="<?php echo $data['Ref_No']; ?>" add-in='Found'>Found</button>
										<?php } ?>
										<?php
											if($data['NEW_Qty'] != $data['Made']){
										?>
										<button type="button" class="btn btn-success add_quentity" row="<?php echo $data['Ref_No']; ?>" add-in='Made'>Made</button>
										<?php } ?>
									</td>
								</tr>
							<?php
								$rows[] = $csv;
							}
						}
					?>
				</tbody>
			</table>
		</div>
		<?php
			if(isset($temporary_data))
			{
			if(!empty($temporary_data))
			{
		?>
		<div class="content">
		<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			</form>
			</div>
		</div>
		<?php
		}
		}
		?>
		</div>
	</div>
</div>

<?php
  } 
 ?>
</div>
