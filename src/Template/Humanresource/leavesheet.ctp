<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery("body").on("change", "#employee_no", function(event){ 
	 
	  var employee_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					employee_id : employee_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'employeedata'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#employee_at').val(json_obj['employee_at']);						
					jQuery('#full_name').val(json_obj['full_name']);						
					jQuery('#designation').val(json_obj['designation_title']);						
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
$employee_no=isset($employee_data['employee_no'])?$employee_data['employee_no']:'';
$employee_at=isset($employee_data['employee_at'])?$employee_data['employee_at']:'';
$full_name=isset($employee_data['full_name'])?$employee_data['full_name']:'';
$designation=isset($employee_data['designation'])?$employee_data['designation']:'';
$month=isset($employee_data['month'])?$employee_data['month']:'';
$year=isset($employee_data['year'])?$employee_data['year']:'';
$leave_detail=isset($employee_data['leave_detail'])?$employee_data['leave_detail']:'';
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
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Humanresource','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
                    
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
									  
					<div class="content controls">
						
						<div class="form-row">
                            <div class="col-md-2">Employee No</div>
                            <div class="col-md-6">
								<select name="employee_no" class="select2" required="true"  style="width:100%" id="employee_no">
								<option value="">-Select Employee No-</option>
									<?php 
										foreach($eployees as $retrive_data)
										{
											/* echo '<option value="'.$retrive_data['employee_id'].'" '.$this->ERPfunction->selected($retrive_data['employee_id'],$employee_no).'>'.$retrive_data['employee_no'].'</option>'; */
											if($retrive_data['employee_no'] != "")
											{
												echo '<option value="'.$retrive_data['user_id'].'" '.$this->ERPfunction->selected($retrive_data['user_id'],$employee_no).'>'.$retrive_data['employee_no'].'</option>';
											}
										}
									?>
									
								</select>
							</div>                        
					   </div>
						<div class="form-row">
                            <div class="col-md-2">Employed at</div>
                            <div class="col-md-6"><input type="text" name="employee_at" id="employee_at" value="<?php echo $employee_at;?>"
							class="form-control validate[required]" /></div>                        
					   </div>
						<div class="form-row">
                            <div class="col-md-2">Full Name</div>
                            <div class="col-md-6"><input type="text" name="full_name" id="full_name" value="<?php echo $full_name;?>"
							class="form-control validate[required]" /></div>                        
					   </div>						   
					   <div class="form-row">
                            <div class="col-md-2">Designation</div>
                            <div class="col-md-6">
						<!--	<input type="text" name="" id="designation" value="<?php// echo $designation;?>" class="form-control validate[required]" /> -->
								<select name="designation" class="select2" required="true"  style="width: 100%;">
										<option value="">--Select Designation--</Option>
										<?php 
											/* foreach($designations as $retrive_data)
											{
												echo '<option value="'.$retrive_data['role'].'" 
												'.$this->ERPfunction->selected($retrive_data['role'],$designation).'>'.
												$retrive_data['title'].'</option>';
											} */
											
									 foreach($designations as $unit_info){
                                        ?>
											<option value="<?php echo $unit_info['cat_id'];?>" <?php                                            
                                                if($designation == $unit_info['cat_id']){
                                                    echo 'selected="selected"';
                                                }else{
                                                    echo '';
                                                }
                                            
                                        
                                        ?> ><?php echo $unit_info['category_title'];?></option>
                                            <?php             
                                        }
										?>
								</select>
							
							</div>                        
							
					   </div>		
					   <div class="form-row">
                            <div class="col-md-2">Month</div>
                            <div class="col-md-2">
								<select name="month" class="select2" required="true"  style="width:100%" id="month">
									<?php 
										foreach($months as $key => $value)
										{
											echo '<option value="'.$key .'" '.(($key == $month)?'selected':'').'>'.$value['name'].'</option>';
										}
									?>
									
								</select>
							</div>  
							<div class="col-md-1"> </div>
							<div class="col-md-1"> Year</div>
                            <div class="col-md-2">
								<select name="year" class="select2" required="true"  style="width:100%" id="year">
									<?php 
										for($i=2000;$i<2050;$i++)
										{
											echo '<option value="'.$i.'" '.(($i == $year)?'selected':'').'>'.$i.'</option>';
										}
									?>
									
								</select>
							</div>  
					   </div>		
					   <div class="form-row">
                            <div class="col-md-2">Leave excluding Holidays, PL, SL & C<span class="require-field">*</span> :</div>
                            <div class="col-md-6">							
							<textarea name="leave_detail"><?php echo $leave_detail;?></textarea>
							</div>                        
					   </div>		
					   
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
					</div>
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>