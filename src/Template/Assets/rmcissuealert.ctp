<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
jQuery(".dataTables_wrapper").dataTable();
jQuery('.datepick').datepicker({
		dateFormat: "yy-mm-dd",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
                });
jQuery("body").on("change",".rmc_approve",function(){
	
		var approve = false;
		if(confirm("Are you sure,you want to approve this record?"))
		{
			if(confirm("Are you sure,you want to approve this record?"))
			{
				if(confirm("Are you sure,you want to approve this record?"))
				{
					approve = true;
					var rmc_id = $(this).attr("rmc_id");
					var curr_data = {rmc_id:rmc_id};
					$.ajax({
						headers: {
							'X-CSRF-Token': csrfToken
						},
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvermc'));?>",
						data:curr_data,
						async:false,
						success : function(result)
							{
								$("#rm_"+rmc_id).remove();
							},
						error : function(e)
							{
								alert("Error");
								console.log(e.responseText);
							}
					});
					
				}			
			}
		}
		if(approve == false)
		{
			jQuery(this).removeAttr('checked');
			jQuery(this).parent().removeClass('checked');
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
                <div class="block">		
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					<!--
                    <div class="header">
                        <h2><u>Make Filter & Sort as per your Requirement</u></h2>
                    </div> -->
					<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">											
						<div class="form-row">
							<div class="col-md-2 text-right">Project Code</div>
							<div class="col-md-4"><input name="project_code" class="form-control"></div>
							<div class="col-md-2">Project Name</div>
							<div class="col-md-4">
							<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
								<option value=''>All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($data)){
												if($data['project_id'] == $retrive_data['project_id'])
												{
													echo 'selected="selected"';
												}
			
											}?> >
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php										
									}
								?>
								</select>
							</div>
						</div>
						<div class="form-row">						
							<div class="col-md-2 text-right">Concrete Grade</div>
							<div class="col-md-3">
								<?php
									$grade = [""=>"All","M7.5"=>"M7.5","M10"=>"M10","M15"=>"M15","M20"=>"M20","M25"=>"M25","M30"=>"M30","M35"=>"M35","M40"=>"M40"];
									echo $this->Form->select("concrete_grade",$grade,["class"=>"select2","style"=>"width:100%"]);
								?>
							</div>					
							<div class="col-md-2">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
						
					</div>
					<?php echo $this->Form->end();?>
					
				<div class="content list custom-btn-clean" style="overflow-x:scroll;">
				<table id="asset_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Date</th>
						<th>RMC. I. No.</th>
						<th>Challan No</th>
						<th>Asset Name</th>
						<th>Agency Name</th>
						<th>Order By</th>						
						<th>Concrete Grade</th>						
						<th>Usage</th>						
						<th>Quantity<br>Supplied<br>(Cum)</th>
						<th>Start Time</th>
						<th>End Time</th>
						<?php if($this->ERPfunction->retrive_accessrights($role,'viewrmcissueslip')==1 || $this->ERPfunction->retrive_accessrights($role,'editrmcrecord')==1 || $this->ERPfunction->retrive_accessrights($role,'deletermc')==1)
							{ ?>
						<th>Action</th>
						<?php
							}
						if($this->ERPfunction->retrive_accessrights($role,'aprrovermc')==1)
						{
						?>
						<th>Approve</th>
						<?php
						}
						?>
					</tr>
				</thead>
				<tbody>
				<?php 
				if(!empty($search_data))
				{
					foreach($search_data as $data)
					{
						echo "
						<tr id='rm_{$data['id']}'>
							<td>{$this->ERPfunction->get_date($data['rmc_date'])}</td>
							<td>{$data['isno']}</td>";
							echo "
							<td>";
							$challan = json_decode($data['challan']);
							if(!empty($challan))
							{
								foreach( $challan as $cln)
								{
									$find = 'img';
									$valid = strpos($cln, $find);
									if($valid === false)
									{
									echo "{$cln}<br>";
									}
								}
							}
							echo "</td>
							<td>{$this->ERPfunction->get_asset_name($data['asset_name'])}</td>
							<td>{$this->ERPfunction->get_agency_name($data['agency_name'])}</td>
							<td>{$data['order_by']}</td>
							<td>{$data['concrete_grade']}</td>
							<td>{$data['rmc_usage']}</td>
							<td>";
							$qty = json_decode($data['quantity']);
							if(!empty($qty))
							{
								$sum = 0 ;
								foreach( $qty as $quantity)
								{
									$sum = $sum + $quantity;
								}
								echo $sum;
							}
					    echo "</td>
							<td>";
							$times = json_decode($data['time_in']);
							if(!empty($times))
							{
								foreach( $times as $time)
								{
									echo "{$time}<br>";
								}
							}
						echo "</td>
							<td>";
							$out_times = json_decode($data['time_out']);
							if(!empty($out_times))
							{
								foreach( $out_times as $time)
								{
									echo "{$time}<br>";
								}
							}
						echo "</td>
							<td>";
							if($this->ERPfunction->retrive_accessrights($role,'editrmcrecord')==1)
							{
							echo "<a href='{$this->request->base}/Assets/editrmcrecord/{$data['id']}' class='btn btn-primary btn-clean' target='_blank'><i class='icon-pencil'></i> Edit</a>";
							}
						if($this->ERPfunction->retrive_accessrights($role,'viewrmcissueslip')==1)
							{
							echo "<a href='{$this->request->base}/Assets/viewrmcissueslip/{$data['id']}' class='btn btn-warning btn-clean' target='_blank'><i class='icon-eye-open'></i> View</a>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'deletermc')==1)
							{
							echo "<a href='{$this->request->base}/Assets/deletermc/{$data['id']}' onClick=\"javascript: return confirm('Are you sure,you wish to Delete');\" class='btn btn-danger btn-clean' style='padding-right:35px;'><i class='icon-eye-open'></i> Remove</a>";
							}
							echo "</td>"; 
						if($this->ERPfunction->retrive_accessrights($role,'aprrovermc')==1)
							{
						echo "<td>";
						
						echo "<input type='checkbox' rmc_id='{$data['id']}' class='rmc_approve'>";
					
						echo "</td>";
							}
						// echo "<td>";
						// $challan = json_decode($data["challan"]);						
						// if(!empty($challan))
						// {
							// $i=1;
							// foreach($challan as $file)
							// {
								// echo "<a href='{$this->request->base}/img/users_images/{$file}' download='file' target='_blank' class='btn btn-info btn-clean'><i class='icon-download-alt'></i>File {$i}</a>";
								// $i++;
							// }
						// }
						echo "</tr>";
					}
				}
				?>
				</tbody>
				</table>
								
				<div class="content">
					<div class="col-md-2">
					<button class="btn btn-success">View Full Screen</button>
					</div>
					<div class="col-md-2">
					<button class="btn btn-success">Export TO Excel</button>
					</div>
					<div class="col-md-2">
					<button class="btn btn-success">Export TO Pdf</button>
					</div>
				</div> 
				
				</div>				
				
		</div>
								
<?php } ?>
</div>
						