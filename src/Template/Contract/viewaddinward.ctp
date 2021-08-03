<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
jQuery("body").on("change", "#project_id", function(event){ 
	 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getreferenceno'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);	
					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('.inward_no').val(json_obj['project_code']+'/IN/'+ json_obj['auto2']);						
					//jQuery('#prno').val(json_obj['prno']);	
					$('#reference_no').attr('value',json_obj.reference_no);


					//return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	jQuery('#user_form').validationEngine();
	jQuery('#date_of_birth,#as_on_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
                }); 
} );
</script>	
<?php 

$project_code=isset($update_inward['project_code'])?$update_inward['project_code']:'';

$project_id=isset($update_inward['project_id'])?$update_inward['project_id']:'';
$reference_no=isset($update_inward['reference_no'])?$update_inward['reference_no']:'';
$date=isset($update_inward['date'])?date('d-m-Y',strtotime($update_inward['date'])):'';
$agency_name=isset($update_inward['agency_name'])?$update_inward['agency_name']:'';
$written_by=isset($update_inward['written_by'])?$update_inward['written_by']:'';
$agency_client_name=isset($update_inward['agency_client_name'])?$update_inward['agency_client_name']:'';
$designation=isset($update_inward['designation'])?$update_inward['designation']:'';
$subject=isset($update_inward['subject'])?$update_inward['subject']:'';
$enclosures=isset($update_inward['enclosures'])?$update_inward['enclosures']:'';
$out_inward_no=isset($update_inward['out_inward_no'])?$update_inward['out_inward_no']:'';
$inward_date=isset($update_inward['inward_date'])?date('d-m-Y',strtotime($update_inward['inward_date'])):'';
$comment=isset($update_inward['comment'])?$update_inward['comment']:'';
$image_old=(isset($update_inward['attachment']))?$update_inward['attachment']:'';
$created_by = isset($update_inward['created_by'])?$this->ERPfunction->get_user_name($update_inward['created_by']):'NA';
$last_edit = isset($update_inward['last_edit'])?date("d-m-Y H:i:s",strtotime($update_inward['last_edit'])):'NA';
$last_edit_by = isset($update_inward['last_edit_by'])?$this->ERPfunction->get_user_name($update_inward['last_edit_by']):'NA';


?>

<div class="col-md-10" >
	<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{ ?>			
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
						<a href="<?php //echo $this->ERPfunction->action_link('Contract','viewinwardlist');?>" onclick = "javascript:window.close();" class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
						</div>
					</div>				
                    
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					
					<div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code; ?>"
							class="form-control validate[required]" value="" readonly="true" disabled /></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true" disabled  style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										?>
<option value="<?php echo $retrive_data['project_id'];?>" <?php 
														if(isset($update_inward)){
												if($update_inward['project_id'] == $retrive_data['project_id']){
													echo 'selected="selected"';
												}
			
								}

				?>  ><?php echo $retrive_data['project_name']; ?> </option>
										<?php
										
									}
								?>
								</select>
							</div>
                        </div>
						
						<div class="form-row dep_pro_div" style="<?php echo (isset($update_inward) && $update_inward['project_id'] == 2)?'display:block':'display:none'; ?>">
							<div class="col-md-2">Department/Project: </div>
                            <div class="col-md-10">
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" disabled name="dep_pro" class="dep_pro" value="department" <?php echo (isset($update_inward) && $update_inward['inward_from'] == 'department')?'checked':''; ?> /> Department</label>
                                </div>
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" disabled name="dep_pro" value="project" class="dep_pro" <?php echo (isset($update_inward) && $update_inward['inward_from'] == 'project')?'checked':''; ?>/>Project</label>
                                </div>
                            </div>
							
						</div>
						
						<div class="form-row department_div" style="<?php echo (isset($update_inward) && $update_inward['inward_from'] == 'department')?'display:block':'display:none'; ?>">
							<div class="col-md-2">Department:*</div>
                            <div class="col-md-4">
								<select class="select2" disabled style="width: 100%;" name="department" id="department">
									<option value="">--Select Department--</Option>
									<?php 
									foreach($department_list as $retrive_data)
									{
										?>
<option value="<?php echo $retrive_data['cat_id'];?>" <?php 
														if(isset($update_inward)){
												if($update_inward['department_id'] == $retrive_data['cat_id']){
													echo 'selected="selected"';
												}
			
								}

				?>  ><?php echo $retrive_data['category_title']; ?> </option>
										<?php
										
									}
								?>
								</select>
							</div>
							<div class="col-md-1">
								<button type="button" id="department" data-type="department" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal">Add More </button>							
							</div>
						</div>
						
						<div class="form-row second_project_div" style="<?php echo (isset($update_inward) && $update_inward['inward_from'] == 'project')?'display:block':'display:none'; ?>">
							<div class="col-md-2">Project:*</div>
                            <div class="col-md-4">
								<select class="select2" disabled style="width: 100%;" name="sub_project" id="sub_project">
									<option value="">--Select Project--</Option>
									<?php 
									foreach($projects as $retrive_data)
									{
										?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
														if(isset($update_inward)){
												if($update_inward['sub_project_id'] == $retrive_data['project_id']){
													echo 'selected="selected"';
												}
			
								}

				?>  ><?php echo $retrive_data['project_name']; ?> </option>
										<?php
										
									}
								?>
								</select>
							</div>
						</div>
						
						<div class="form-row">
                            <div class="col-md-2">Our Inward No</div>
                            <div class="col-md-4"><input type="text" name="out_inward_no" value="<?php echo $out_inward_no; ?>" class="form-control inward_no" disabled /></div>
                        
                            <div class="col-md-2">Inward Date</div>
                            <div class="col-md-4"><input type="text" name="inward_date" id="as_on_date" value="<?php echo $inward_date; ?>" class="form-control" disabled /></div>
                        </div>
						
                        <div class="form-row">
                            <div class="col-md-2">Their Ref. No<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="reference_no" value="<?php echo $reference_no; ?>" id="reference_no" class="form-control validate[required]"  disabled /></div>
                        
                            <div class="col-md-2">Their Date</div>
                            <div class="col-md-4"><input type="text" name="date" value="<?php echo $date; ?>" id = "date_of_birth" class="form-control" disabled /></div>
                        </div>						
						
						 <div class="form-row">						
                            <div class="col-md-2">Agency Name :</div>
                            <div class="col-md-4">
							<?php  //echo $this->Form->select("agency_name",$agency_list,["empty"=>" ","default"=>$agency_name,"class"=>"form-control","id"=>"","disabled"=>"disabled"]);?>
							 <input type="text" name="agency_name" value="<?php echo $this->ERPfunction->get_category_title($agency_name); ?>" id = "" class="form-control"  disabled /> 
							</div>
							<div class="col-md-2">Type of Agency</div>
                            <div class="col-md-4">
								<select name="agency_client_name" class="select2" required="true"  style="width:100%;" disabled >

									<?php 
										$client_name=array(
															'Client'=>'Client',
															'Designer / Consultant'=>'Designer / Consultant',
															'PMC/TPI'=>'PMC/TPI',
															'Testing Laboratory'=>'Testing Laboratory',
															'Sub-Contractor'=>'Sub-Contractor',
															'Supplier'=>'Supplier',
															'Others'=>'Others'
														);

									
									foreach($client_name as $client_key => $client_value){
										?>
									<option value="<?php echo $client_key ;?>" <?php 
													if(isset($update_inward)){
												if($client_key == $update_inward['agency_client_name']){
													echo 'selected="selected"';
												}
											}

									?> ><?php echo $client_value; ?></option>
									<?php 
								}
								?>
								</select>
							</div>
							
                        </div>
						 <div class="form-row">
							
                            <div class="col-md-2">Written By</div>
                            <div class="col-md-4"><input type="text" name="written_by" value="<?php echo $this->ERPfunction->get_category_title($written_by); ?>" class="form-control" disabled /></div>
							<div class="col-md-2">Designation</div>
                            <div class="col-md-4"><input type="text" name="designation" id="" value="<?php echo $this->ERPfunction->get_category_title($designation); ?>" class="form-control" disabled /></div>							
                        </div>	
						<div class="form-row">
                            <div class="col-md-2">Subject</div>
                            <div class="col-md-10"><input type="text" name="subject" 
							value="<?php echo $subject; ?>" class="form-control" disabled /></div>                        
                        </div>					
						<div class="form-row">
                            <div class="col-md-2">Enclosures</div>
                            <div class="col-md-10"><input type="text" name="enclosures" 
							value="<?php echo $enclosures; ?>" class="form-control" disabled /></div>                        
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Comment Box</div>
                            <div class="col-md-4">
							<textarea name="comment" class="form-control" disabled ><?php echo $comment; ?></textarea>
							</div>
                        </div>						
						<div class="header"><h2><u>Project Attachment</u></h2></div>
						<div class="form-row">
						<?php 
						if($user_action == "edit")
						{
						$attached_files = json_decode($update_inward["attachment"]);
						$attached_label = json_decode(stripcslashes($update_inward['attach_label']));						
						if(!empty($attached_files))
						{							
							$i = 0;
							foreach($attached_files as $file)
							{?>
								<div class='del_parent'>
									<div class='form-row'>
										<div class='col-md-2'>
											<?php echo $attached_label[$i];?>
											<input type='hidden' name='attach_label[]' value='<?php echo $attached_label[$i];?>' class='form-control'>
										</div>
										<div class='col-md-4'><a href="<?php echo $this->ERPfunction->get_signed_url($file);?>" class="btn btn-primary" target="_blank">View File</a>
										<input type='hidden' name='old_image_url[]' value='<?php echo $file;?>' class='form-control'></div>
										
									</div>
								</div>							
							<?php $i++;
							}
						}
						}
						?>
						</div>					
				
				<?php $this->Form->end(); ?>
					<div class="row" style="font-style:italic;color:gray;">
						<div class="col-md-8 pull-right">
							<div class="col-md-3">
								<?php echo "Created By:{$created_by}"; ?>
							</div>
							<div class="col-md-3">
								<?php echo "Last Edited On:{$last_edit}"; ?>
							</div>
							<div class="col-md-3">
							  <?php echo "Last Edited By:{$last_edit_by}"; ?>
							</div> 
							<div class="col-md-3">						 
						  <a href="../printinward/<?php echo $update_inward['inward_id'];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
						</div>
						</div>
					</div>
			 </div>
			</div>
<?php } ?>
         </div>
