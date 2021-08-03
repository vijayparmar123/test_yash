<?php
use Cake\Routing\Router;
?>

<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('.datepick').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
	jQuery('.viewmodal').click(function(){
		var project_id = jQuery("#project_id").val();
		jQuery('#modal-view').html('hello');
		if(project_id == '')
		{
			alert('Please Select Project.');
			return false;
		}
		//alert(model);
		//return false;
	    var curr_data = {project_id : project_id};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addreference'));?>",
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
	
	jQuery("body").on("change", ".input-file[type=file]", function () {
		var file = this.files[0];
		var file_id = jQuery(this).attr('id');
		var ext = $(this).val().split('.').pop().toLowerCase();
		//Extension Check
		if($.inArray(ext, ['gif','png','jpg','jpeg','dwg','dxf','pdf']) == -1) {
			alert('invalid extension! , '+ext+' file not allowed');
			$(this).replaceWith('<input type="file" name="drawing[attach_file][]" class="validate[required] input-file" id="'+file_id+'" />');
			return false;
		}
		//File Size Check
		if (file.size > 20480000) {
			alert("Too large file Size. Only file smaller than 10MB can be uploaded.");
			$(this).replaceWith('<input type="file" name="drawing[attach_file][]" class="validate[required] input-file" id="'+file_id+'" />');
			return false;
		}
	});
	
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectdetailpr'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);											
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
	
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectreference'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#reference').empty();											
					jQuery('#reference').append(json_obj['reference']);											
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
	
	jQuery("#add_row").click(function(){
		var row_len = jQuery(".row_number").length;
		if(row_len > 0)
		{
			var num = jQuery(".row_number:last").val();
			var row_id = parseInt(num) + 1;
		}
		else
		{
			var row_id = 0;
		}
		
		jQuery.ajax({
					 headers: {
						'X-CSRF-Token': csrfToken
					},
						type: 'POST',
						url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "referencerow"]);?>',
						data : {row_id:row_id},
						success: function (response)
						{	
							jQuery("tbody").append(response);
							jQuery('.datepick').datepicker({
								 changeMonth: true,
							  changeYear: true,
							  dateFormat: "dd-mm-yy"
							});
							return false;
						},
						error: function(e) {
						alert("An error occurred: " + e.responseText);
						console.log(e);
						}
					});
	});
  
  jQuery('body').on('click','.trash',function(){
	  
		/* var row_id = jQuery(this).attr('data-id');		
		jQuery('table tr#row_id_'+row_id).remove();	 */
		jQuery(this).parents("tr").remove();
		return false;
	});
} );
</script>	
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
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
						<h2>Create New Drawing Record</h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
						
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name*</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Drawing Type*</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="drawing_type" id="drawing_type">
								<option value="">--Select Drawing--</Option>
								<?php 
									foreach($drawing_type as $retrive_data)
									{
										echo '<option value="'.$retrive_data['id'].'">'.
										$retrive_data['title'].'</option>';
									}
								?>
								</select>
							</div>
                        
                            <div class="col-md-2">Drawing No*</div>
                            <div class="col-md-4">
								<input type="text" name="drawing_no" value="" class="form-control validate[required]"/>
							</div>
                        </div>
					
						<div class="form-row">
							<div class="col-md-2">Drawing Title*</div>
                            <div class="col-md-10">
								<input type="text" name="drawing_title" value="" class="form-control validate[required]"/>
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2">Building / Other Reference </div>
							 <div class="col-md-4">
								  <select class="select2" style="width: 100%;"id="reference" name="building_reference">
									<option value=""><?php echo __('Select Reference'); ?></option>
									<?php
                                    
									?>
								</select>
							</div> 
							<div class="col-md-1">
								<button type="button" id="add_reference" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal" style="">Add</button>							
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2">Issued By</div>
                            <div class="col-md-4">
								<input type="text" name="issued_by" value="" class="form-control"/>
							</div>
						</div>
						<div class="form-row">
						 
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th>Revision No</th>
									<th>Date of Receipt</th>
									<th>Remarks</th>
									<th>Name of Attachment</th>
									<th>Attachment</th>
									<th>Delete</th>
									</tr>
								</thead>
								<tbody>
									<tr id="row_id_0">
										<td>
											<input type="text" value="" name="drawing[revision_no][]" class="validate[required]" id="revision_no_0">
											<input type="hidden" value="0" name="row_number" class="row_number">
										</td>
										<td>
											<input type="text" value="" name="drawing[receipt_date][]" class="datepick" id="receipt_date_0">
										</td>
										<td>
											<input type="text" value="" name="drawing[remark][]" id="remark_0">
										</td>
										<td>
											<input type="text" value="" name="drawing[attach_name][]" class="validate[required]" id="attach_name_0">
										</td>
										<td>
											<input type="file" name="drawing[attach_file][]" class="validate[required] input-file" id="attach_file_0" />
										</td>
										<td>
											<span class="trash btn btn-danger" data-id="0"><i class="fa fa-trash"></i> Delete</span>
										</td>
									</tr>
								</tbody>
							</table>
							<button type="button" id="add_row" class="btn btn-default">Add New </button>
							
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary">Create</button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>
