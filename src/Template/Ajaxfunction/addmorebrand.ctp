<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#brand_form').validationEngine();	
	
	
		 jQuery("body").on("click", "#insert", function(){	 
	  var material_type  = jQuery("#material_type").val();
	  var brand_name  = jQuery("#brand_name").val();
	  var project_id  = jQuery("#project_id").val();
		if(material_type == '' || brand_name == '')
		  {
			  alert('Please fill all field');
			  return false;
		  }
		 // alert(material_type);
		// return false; 
	   var curr_data = {
	 					material_type : material_type, brand_name : brand_name , project_id : project_id					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addbrand'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					if(response == 'duplicate')
					{
						alert('Duplicate entry , Please try again.');
						jQuery('#material_type').select2("val", "");
						jQuery("#brand_name").val('');
					}
					else
					{
						jQuery('#material_type').select2("val", "");
						jQuery("#brand_name").val('');
					}
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	});
</script>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Add Brand</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form id="brand_form"  method="post" class="form-horizontal transferform">

	
		
		
						
						
				<div class="form-row">
					<div class="col-md-4">Material/Item Group<span class="require-field">*</span> </div>
					<div class="col-md-8">
						<select name="material_type" id="material_type" class="select2" required="true"  style='width:100%'>
							<option value="">--Select Item Group--</option>
							<?php
								/* foreach($category as $key => $retrive_data)
								{
									echo '<option value="'.$key.'">'.$retrive_data['material_code'].'</option>';
								} */
								/*Material Group list*/
								foreach($category as $key => $retrive_data)
								{ 
									echo '<option value="'.$retrive_data['id'].'">'.$this->ERPfunction->get_vendor_group_name($retrive_data['id']).'</option>';
								}
							?>
							
						</select>
					</div>                          
				</div>
				
			   <div class="form-row">
					<div class="col-md-4">Brand Name</div>
					<div class="col-md-8">
						<input type="text" name="brand_name" id="brand_name" class="form-control"/>
						<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" id="project_id"/>
					</div>
				</div>
		
						<!--<div class="form-row">
								<div class="col-md-4">Material Description </div>
								<div class="col-md-8">
									<textarea name="desciption" class="form-control"></textarea>
								</div>								
						</div>-->
		
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="button" value="Add" name="insert" class="btn btn-primary" id="insert"/>
			</div>
		</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>