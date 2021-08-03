<?php
use Cake\Routing\Router;
?>
<style type="text/css">
	
table {
 font-family: arial, sans-serif;
 border-collapse: collapse;
 width: 100%;
}

td, th {
 border: 1px solid #dddddd;
 text-align: left;
 padding: 8px;
 color: black;
}

</style>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {	
	jQuery('body').on('click','.viewmodal',function(){			
			payid=jQuery(this).attr('id');
			jQuery('#modal-view').html('hello');
			 var model  = jQuery(this).attr('data-type') ;
			 var user_id  = jQuery(this).attr('user_id') ;
			 var urlstring = '';
		
		if(model == 'transferemployee')
		{
			urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'transferemployee'));?>";
		}
		if(model == 'resignemployee')
		{
			urlstring = "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'resignemployee'));?>";
		}
		if(model == 'change_balance')
		{
			urlstring = "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addleavebalance'));?>";
		}
	   var curr_data = {type : model,user_id:user_id};	 				
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
<!--<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>	-->

<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>  
<?php 
$project_id = array();
$project_id[] = (isset($_POST["project_id"])) ? $_POST["project_id"] : "";
?> 
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Efficiency History</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Humanresource','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		</div>
	</div>
			
		<div class="col-md-12" style="background-color: white;">
			<div class="col-md-6">
				<h6 style="float: left; color: black"><b>Asset ID:</b> <?php echo $this->ERPfunction->get_asset_code($asset_id); ?></h6>
			</div>
			<div class="col-md-6" style="float: right;">
				<h6 style="float: right; color: black"><b>Asset Name: </b><?php echo $this->ERPfunction->get_asset_name($asset_id); ?></h6>
			</div>
		
			
			
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({
			responsive: {
						details: {
							type: 'column',
							target: -2
						}
					},
					columnDefs: [ {
						className: 'control',
						orderable: false,
						targets:   -2
					} ],
		});
		} );
</script>
			<table>
				<thead>
					<tr>
						<!--  <th>Image</th> 
						<th class="text-center" style="min-width:200px;">Full Name</th>
						<th>Enroll No.</th>-->
						<th>Deployed To.</th>
						<th>Date</th>						
						<th>Total Fual Issued(Ltr.)</th>
						<th>Start(km.)</th> 
						<th>Start(hrs.)</th>
						<th>Stop(km.)</th>
						<th>Stop(hrs.)</th>
						<th>Total(km.)</th>
						<th>Total(hrs.)</th>
						<th>Duty Time(hrs.)</th>
						<th>Usage Details</th>
						<th>Working Condition</th>
						<th>Brack Down(hrs.)</th>
						
						
						<!-- <th>Status<br> of<br> Employee</th>	-->										
												
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php

						$i = 1;
						$total_fual = 0;
						$total_kms = 0;
						$total_hrs = 0;
						$brack_down = 0;

						foreach($efficiencydata as $retrive_data)
						{ 
							//debug($retrive_data);
								$date = $retrive_data['date'];
								$dates = date('d-m-Y',strtotime($date));
								$total_km = $retrive_data['stop_km'] - $retrive_data['start_km'];
								$total_hr = $retrive_data['stop_hr'] - $retrive_data['start_hr'] ;
								$total_fual += $this->ERPfunction->checkTotalFuel($asset_id,$dates);
								$total_kms += $total_km;
								$total_hrs += $total_hr;
								$brack_down += $retrive_data['breakdown_time'];
								$fual = $this->ERPfunction->checkTotalFuel( $retrive_data['asset_id'],$retrive_data['date']);
								
							?>
							<tr>								
								<td><?php echo $this->ERPfunction->get_projectname($retrive_data['project_id']); ?></td>
								<td><?php echo $dates?></td>
								<td><?php echo $fual?></td>	
								<td><?php echo $retrive_data['start_km'];?></td>
								<td><?php echo $retrive_data['start_hr'];?></td>
								<td><?php echo $retrive_data['stop_km'];?></td>
								<td><?php echo $retrive_data['stop_hr'];?></td>
								<td><?php echo $total_km;?></td>
								<td><?php echo $total_hr;?></td>
								<td><?php echo $retrive_data['duty_time']; ?></td>	
								<td><?php echo $retrive_data['usage_detail'];?></td>
								<td><?php echo $retrive_data['working_status'];?></td>
								<td><?php echo $retrive_data['breakdown_time'];?></td>
								<td>
								<?php
	 								if($this->ERPfunction->retrive_accessrights($role,'equipmentlogrecord')==1)
									{
										echo "<a href='{$this->request->base}/Assets/viewaddequipmentown/{$retrive_data['id']}' target='_blank' class='btn btn-primary btn-clean'><i class='icon-eye-open'></i> View</a>";
									}
								?>
								</td>						
								
							</tr>
						<?php
						$i++;
						}
						?>
						<td></td>
						<td></td>
						<td style="color: red"><b><?php echo $total_fual;?></b></td>	
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style="color:red"><b><?php echo $total_kms;?></b></td>
						<td style="color: red"><b><?php echo $total_hrs;?></b></td>
						<td></td>	
						<td></td>
						<td></td>
						<td style="color: red"><b><?php echo $brack_down ?></b></td>
														
				
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<?php   } ?>
</div>