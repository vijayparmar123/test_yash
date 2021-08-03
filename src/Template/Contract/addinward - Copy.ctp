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
$date=isset($update_inward['date'])?date('Y-m-d',strtotime($update_inward['date'])):'';
$agency_name=isset($update_inward['agency_name'])?$update_inward['agency_name']:'';
$written_by=isset($update_inward['written_by'])?$update_inward['written_by']:'';
$agency_client_name=isset($update_inward['agency_client_name'])?$update_inward['agency_client_name']:'';
$designation=isset($update_inward['designation'])?$update_inward['designation']:'';
$subject=isset($update_inward['subject'])?$update_inward['subject']:'';
$enclosures=isset($update_inward['enclosures'])?$update_inward['enclosures']:'';
$out_inward_no=isset($update_inward['out_inward_no'])?$update_inward['out_inward_no']:'';
$inward_date=isset($update_inward['inward_date'])?date('Y-m-d',strtotime($update_inward['inward_date'])):'';
$comment=isset($update_inward['comment'])?$update_inward['comment']:'';
$image_old=(isset($update_inward['attachment']))?$update_inward['attachment']:'';


?>

<div class="col-md-10" >
				
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Contract','index');?>" class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
						</div>
					</div>				
                    
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					
					<div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code; ?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
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

				?>  ><?php echo $retrive_data['project_code'].' '.$retrive_data['project_name']; ?> </option>
										<?php
										
									}
								?>
								</select>
							</div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Our Inward No</div>
                            <div class="col-md-4"><input type="text" name="out_inward_no" value="<?php echo $out_inward_no; ?>" class="form-control inward_no"/></div>
                        
                            <div class="col-md-2">Inward Date</div>
                            <div class="col-md-4"><input type="text" name="inward_date" id="as_on_date" value="<?php echo $inward_date; ?>" class="form-control"/></div>
                        </div>
						
                        <div class="form-row">
                            <div class="col-md-2">Their Ref. No<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="reference_no" value="<?php echo $reference_no; ?>" id="reference_no" class="form-control validate[required]" /></div>
                        
                            <div class="col-md-2">Their Date</div>
                            <div class="col-md-4"><input type="text" name="date" value="<?php echo $date; ?>" id = "date_of_birth" class="form-control"/></div>
                        </div>						
						
						 <div class="form-row">						
                            <div class="col-md-2">Agency Name :</div>
                            <div class="col-md-4">
							<?php  echo $this->Form->select("agency_name",$agency_list,["empty"=>" ","default"=>$agency_name,"class"=>"form-control","id"=>""]);?>
							<!-- <input type="text" name="agency_name" value="<?php echo $agency_name; ?>" id = "" class="form-control" /> -->
							</div>
							<div class="col-md-2">Type of Agency</div>
                            <div class="col-md-4">
								<select name="agency_client_name" class="select2" required="true"  style="width:100%;">

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
							
                            <div class="col-md-2">Written By</div>
                            <div class="col-md-4"><input type="text" name="written_by" value="<?php echo $written_by; ?>" class="form-control"/></div>
							<div class="col-md-2">Designation</div>
                            <div class="col-md-4"><input type="text" name="designation" id="" value="<?php echo $designation; ?>" class="form-control"/></div>							
                        </div>	
						<div class="form-row">
                            <div class="col-md-2">Subject</div>
                            <div class="col-md-10"><input type="text" name="subject" 
							value="<?php echo $subject; ?>" class="form-control"/></div>                        
                        </div>					
						<div class="form-row">
                            <div class="col-md-2">Enclosures</div>
                            <div class="col-md-10"><input type="text" name="enclosures" 
							value="<?php echo $enclosures; ?>" class="form-control"/></div>                        
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Comment Box</div>
                            <div class="col-md-4">
							<textarea name="comment" class="form-control"><?php echo $comment; ?></textarea>
							</div>
                        </div>						
						
						<div class="form-row">
							<div class="col-md-2">Attach Document</div>
                            <div class="col-md-4">
                            	<input type="hidden" value="<?php echo $image_old; ?>" name="old_image">
								<input type="file" name="image_url[]" class="form-control"/>
							</div>
							<div class="col-md-2">
								<a href="javascript:void(0);" class="create_field btn btn-default">Add Attachment</a>
							</div>
						</div>
						
						<div class="form-row add_field">
						<?php 
						if($user_action == "edit")
						{
							// $attached_files = json_decode($update_inward["attachment"]);
							$attached_files = json_decode($update_inward["attachment"]);							
							if(!empty($attached_files))
							{							
								$i = 1;
								foreach($attached_files as $file)
								{ 
								   if(!empty($file))
								   { ?>
									<div class='del_parent'>
										<div class='form-row'>
											<div class='col-md-2'>Attachment : <?php echo $i; ?></div>											
											<div class='col-md-4'>
												<input type='hidden' name='old_image_url[]' value='<?php echo $file;?>' class='form-control'>
												<a href="<?php echo $this->request->base;?>/img/users_images/<?php echo $file;?>" target="_blank" class="btn btn-info">View Attachment</a>
												<span class='del_file btn btn-danger'>x Remove</span>
											</div>
										</div>
									</div>
							<?php $i++;
								   }
								}
						  }
						}
						?>
						</div>
						
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
				</div>
				<?php $this->Form->end(); ?>
			</div>
         </div>
<script>
$(".create_field").click(function(){
	var label = $(".add_label").val();
	$(".add_label").val("");
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>Attach Document</div><div class='col-md-4'><input type='file' name='image_url[]'></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
	$(".add_field").append(field);
});
$("body").on("click",".del_file",function(){
	$(this).parentsUntil('.del_parent').remove();
});

</script>