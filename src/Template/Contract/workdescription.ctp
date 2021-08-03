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
				<h2>Work Description List</h2>
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
						{"bSortable": true,sWidth:"10%"},
						{"bSortable": true,sWidth:"10%"},
						// {"bSortable": true,sWidth:"15%"},
						{"bSortable": true,sWidth:"15%"},
						{"bSortable": true,sWidth:"15%"}]
					});
				});
				$("body").on("click","#join_record",function(){
					var material_id = $(this).attr("material_id");
					// var url = $("#join_material_url").val();
					var curr_data = {material_id:material_id};

					$.ajax({
						headers: {
							'X-CSRF-Token': csrfToken
						},
						type : "POST",
						url :"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'joinworkdescription'));?>",
						data : curr_data,
						async:false,
						success : function(response){
							//$('.modal-dialog').css("width","1076px");
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
			</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Work Group</th>
						<th>Work Sub-group</th>
						<th>Description</th>
						<!-- <th>Project</th> -->
						<th>Unit</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($descriptions as $retrive_data)
						{
							foreach($erpWorkSubGroupData as $data) {}
						?>
							<tr>
								<td>
									<?php
										echo $this->ERPfunction->getWorkGroupName($retrive_data['work_group']);
										// echo $retrive_data['work_group']; 
									?>
								</td>
								<td>
									<?php
										// if($data['work_group_id'] == $retrive_data['cat_id']){
										// 	echo $data['sub_work_group_title'];
										// } 
										echo $this->ERPfunction->getWorkSubGroupName($retrive_data['work_sub_group']);
										// echo $retrive_data['work_sub_group'];
									?>
								</td>
								<td><?php echo $retrive_data['category_title'];?></td>
								<!-- <td><?php echo $this->ERPfunction->get_projectname($retrive_data['project_id']);?></td> -->
								<td><?php echo $retrive_data['unit'];?></td>
								
								<td>
								<?php 
								// if($this->ERPfunction->retrive_accessrights($role,'planningworkheadlist')==1)
								// {
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewworkdescription', $retrive_data['cat_id']),
									array('escape'=>false,'class'=>'btn btn-info btn-clean'));
									echo ' ';
								// }
								if($this->ERPfunction->retrive_accessrights($role,'editworkdescription')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editworkdescription', $retrive_data['cat_id']),
									array('escape'=>false,'class'=>'btn btn-primary btn-clean'));
								}
								if($this->ERPfunction->retrive_accessrights($role,'deletedescription')==1)
								{
									echo $this->Html->link("<i class='icon-remove'></i> Delete",array('action' => 'deleteworkdescription', $retrive_data['cat_id']),
									array('escape'=>false,'class'=>'btn btn-danger btn-clean'));
								}
								// if($this->ERPfunction->retrive_accessrights($role,'Joinmaterial')==1)
								// {
									echo "<a class='btn btn-primary btn-clean' id='join_record' href='javascript:void(0);' material_id='{$retrive_data['cat_id']}'><i class='icon-pencil'></i>Join</a>";
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