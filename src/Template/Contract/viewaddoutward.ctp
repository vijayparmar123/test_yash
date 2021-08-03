<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery("body").on("change", "#project_id", function(event){ 
	 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getoutwardno'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);	
					
					jQuery('#project_code').val(json_obj['project_code']);						
					//jQuery('#prno').val(json_obj['prno']);	
					$('#reference_no').attr('value',json_obj.reference_no);


					//return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	//jQuery('#user_form').validationEngine();
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

$project_code=isset($update_outward['project_code'])?$update_outward['project_code']:'';
$project_id=isset($update_outward['project_id'])?$update_outward['project_id']:'';
$reference_no=isset($update_outward['reference_no'])?$update_outward['reference_no']:'';
$date=isset($update_outward['date'])?date('d-m-Y',strtotime($update_outward['date'])):'';
$agency_name=isset($update_outward['agency_name'])?$update_outward['agency_name']:'';
$written_by=isset($update_outward['written_by'])?$update_outward['written_by']:'';
$agency_client_name=isset($update_outward['agency_client_name'])?$update_outward['agency_client_name']:'';
$designation=isset($update_outward['designation'])?$update_outward['designation']:'';
$subject=isset($update_outward['subject'])?$update_outward['subject']:'';
$enclosures=isset($update_outward['enclosures'])?$update_outward['enclosures']:'';
$our_outward_no=isset($update_outward['our_outward_no'])?$update_outward['our_outward_no']:'';
$outward_date=isset($update_outward['outward_date'])?date('d-m-Y',strtotime($update_outward['outward_date'])):'';
$comment=isset($update_outward['comment'])?$update_outward['comment']:'';
$image_old=(isset($update_outward['attachment']))?$update_outward['attachment']:'';
$created_by = isset($update_outward['created_by'])?$this->ERPfunction->get_user_name($update_outward['created_by']):'NA';
$last_edit = isset($update_outward['last_edit'])?date("d-m-Y H:i:s",strtotime($update_outward['last_edit'])):'NA';
$last_edit_by = isset($update_outward['last_edit_by'])?$this->ERPfunction->get_user_name($update_outward['last_edit_by']):'NA';



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
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php //echo $this->ERPfunction->action_link('Contract','viewoutwardlist');?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
                    <div class="header">
                        <h2><u>Personal Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code; ?>"
							class="form-control validate[required]" value="" readonly="true" disabled /></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id" disabled >
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										?>
<option value="<?php echo $retrive_data['project_id'];?>" <?php 
														if(isset($update_outward)){
												if($update_outward['project_id'] == $retrive_data['project_id']){
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
                            <div class="col-md-2">Our Ref. No<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="reference_no" value="<?php echo $reference_no; ?>" id="reference_no" class="form-control validate[required]"  disabled /></div>
                        
                            <div class="col-md-2">Our Date</div>
                            <div class="col-md-4"><input type="text" name="date" value="<?php echo $date; ?>" id = "date_of_birth" class="form-control" disabled /></div>
                        </div>						
						
						 <div class="form-row">						
                             <div class="col-md-2">Agency Name :</div>
                            <div class="col-md-4">
							<?php // echo $this->Form->select("agency_name",$agency_list,["empty"=>" ","default"=>$agency_name,"class"=>"form-control","id"=>"","disabled"=>"disabled"]);?>
							<input type="text" name="agency_name" value="<?php echo $this->ERPfunction->get_category_title($agency_name); ?>" id = "" class="form-control" disabled />
							
							</div>
							<div class="col-md-2">Type of Agency</div>
                            <div class="col-md-4">
								<select name="agency_client_name" class="select2" required="true"  style="width:100%;" disabled >

									<?php 
										$client_name=array(
															'Client'=>'Client',
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
							
                            <div class="col-md-2">Written To</div>
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
                            <div class="col-md-2">Their Ref. No</div>
                            <div class="col-md-4"><input type="text" name="our_outward_no" value="<?php echo $our_outward_no; ?>" class="form-control" disabled /></div>
                        
                            <div class="col-md-2">Their Ref. Date</div>
                            <div class="col-md-4"><input type="text" name="outward_date" id="as_on_date" value="<?php echo $outward_date; ?>" class="form-control" disabled /></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Comment Box</div>
                            <div class="col-md-4">
							<textarea name="comment" class="form-control" disabled ><?php echo $comment; ?></textarea>
							</div>
                        </div>
						
						<div class="header">
                        <h2><u>Attached Documents</u></h2>
                    </div>
						<div class="form-row add_field">
						<?php 
						if($user_action == "edit")
						{
						$attached_files = json_decode($update_outward["attachment"]);
						$attached_label = json_decode(stripcslashes($update_outward['attach_label']));						
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
						  <a href="../printoutward/<?php echo $update_outward['outward_id'];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
						</div>
					</div>
				</div>
			   </div>
			</div>
	<?php } ?>
         </div>
