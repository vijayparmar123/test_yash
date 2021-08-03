<?php
use Cake\Routing\Router;
?>
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
				<h2>Candident Management </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Humanresource','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
			<div class="content">
				<div class="col-md-12 filter-form">
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				<div class="form-row">
					
					<div class="col-md-2 text-right">Name</div>
					<div class="col-md-4">
						<select class="select2" style="width: 100%;" id="user_id" name="user_id[]" multiple="multiple">
					   <option value="All" selected>All</option> 
							<?php

							if(isset($name_list)){

								foreach($name_list as $retrive_data){
								?>
						   <option value="<?php echo $retrive_data['user_id'];?>"><?php echo $retrive_data['first_name'];?></option>
									<?php             
								}
							} ?>
						</select>
					</div>
					
					<div class="col-md-2 text-right">Employee No</div>
					<div class="col-md-4">
						<input name="user_identy_id" class="form-control">
					</div>

				</div>
				
				<div class="form-row">
					<div class="col-md-2 text-right">Mobile No</div>
					<div class="col-md-4">
						<input name="mobile_no" class="form-control">
					</div>
					<div class="col-md-1">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">
					</div>
				</div>
			<?php echo $this->Form->end();?>
			</div>
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
			<table id="user_list" class="dataTables_wrapper table table-striped table-hover" style="width:100%;">
				<thead>
					<tr>
						<!--  <th>Image</th> 
						<th class="text-center" style="min-width:200px;">Full Name</th>
						<th>Enroll No.</th>-->
						<th>Candidate No.</th>
						<th>First Name</th>						
						<th>Middle Name</th>
						<th>Last Name</th> 
						<th>Mobile</th>
						<th>Education</th>
						<th>CTC Year</th>
						
						<!-- <th>Status<br> of<br> Employee</th>	-->										
						<th></th>						
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php

						$i = 1;
						foreach($user_list as $retrive_data)
						{ ?>
							<tr>								
								<?php 
									/*echo $this->Html->image($this->ERPfunction->get_employee_image($retrive_data['user_id']),
				array('class'=>'img-circle','height'=>'50px','width'=>'50px'));*/ ?>							
								<!--<td><?php //echo $retrive_data['user_id'];?></td>-->
								<td><?php echo $retrive_data['user_identy_id'];?></td>
								<td><?php echo $retrive_data['first_name'];?></td>	
								<td><?php echo $retrive_data['middle_name'];?></td>
								<td><?php echo $retrive_data['last_name'];?></td>
								<td><?php echo $retrive_data['mobile_no'];?></td>
								<td><?php echo $retrive_data['education'];?></td>
								<td><?php echo $retrive_data['ctc_year'];?></td>	
								
								<td></td>								
								<td>
								<?php
 								
 								if($this->ERPfunction->retrive_accessrights($role,'candidatelist')==1)
								{						
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewcandidate', $retrive_data['user_id']),
								array('class'=>'btn btn-success btn-clean','escape'=>false,"target"=>"_blank"));
							    }

							    if($this->ERPfunction->retrive_accessrights($role,'addcandidate')==1)
								{	
								echo ' ';
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addcandidate', $retrive_data['user_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false,"target"=>"_blank"));
								}
								
								if($this->ERPfunction->retrive_accessrights($role,'deletecandidate')==1)
								{
									echo' ';
									echo $this->Html->link("<i class='icon-pencil'></i> Delete",array('action' => 'deletecandidate', $retrive_data['user_id']),
									array('class'=>'btn  btn-danger btn-clean','escape'=>false,
									'confirm' => 'Are you sure you wish to delete this Record?'));
								}
								
									
						
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
<?php  } ?>
</div>