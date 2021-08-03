<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<!-- Join Modal Start -->
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<!-- Join Modal End -->
<div class="col-md-10" >

<div class="col-md-12" >	
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
				<h2>Work Head List</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Contract','planningmenu');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
		<div class="content list custom-btn-clean">
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
			jQuery(document).ready(function() {
				jQuery('#user_list').DataTable({responsive: true,		 
					"aoColumns":[
						{"bSortable": true,sWidth:"10%"},
						// {"bSortable": true,sWidth:"15%"},
						// {"bSortable": true,sWidth:"15%"},
						{"bSortable": true,sWidth:"15%"},					
						{"bSortable": false,sWidth:"5%"}
					]
				});
				$("body").on("click","#join_record",function(){
					var material_id = $(this).attr("material_id");
					var curr_data = {
						material_id:material_id
					};
					$.ajax({
					 headers: {
				'X-CSRF-Token': csrfToken
			},
						url :"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'joinworksubgroup'));?>",
						data : curr_data,
						type : "POST",
						async:false,
						success : function(response){
							jQuery('.modal-content').html('');
							jQuery('.modal-content').html(response);
							jQuery('#load_modal').modal('show');
						},
						beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
						error : function(e){
							console.log(e.responseText);
						}
					});
				});
			});
		</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Code</th>
						<!-- <th>Project</th> -->
						<!--<th>Type of Contract</th>-->
						<th>Work Head</th>
						<th>Edit / View</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($head_list as $retrive_data)
						{
						?>
							<tr>
								<td><?php echo $retrive_data['work_head_code'];?></td>
								<!-- <td><?php echo $this->ERPfunction->get_projectname($retrive_data['project_id']);?></td> -->
								<!--<td><?php echo $this->ERPfunction->get_contract_title($retrive_data['type_of_contract']);?></td>-->
								<td><?php echo $retrive_data['work_head_title']; ?></td>
								
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'editplanningworkhead')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editplanningworkhead', $retrive_data['work_head_id']),
									array('escape'=>false,'class'=>'btn btn-primary btn-clean'));
								}
								// if($this->ERPfunction->retrive_accessrights($role,'')==1)
								// {
									echo "<a class='btn btn-primary btn-clean' id='join_record' href='javascript:void(0);' material_id='{$retrive_data['work_head_id']}'><i class='icon-pencil'></i>Join</a>";
								// }
								?>
								</td>
							</tr>
						<?php
						$i++;
						}
					?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<?php } ?>

</div>