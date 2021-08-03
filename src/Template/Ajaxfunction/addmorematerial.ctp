<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#material_form').validationEngine();
	
	jQuery("body").on("change", "#material_code", function(event){	
		var material_code  = jQuery(this).val();
		var curr_data = {material_code : material_code};	 				
	 	jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialsubgroup'));?>",
			data:curr_data,
			async:false,
			success: function(response){                    
				jQuery('#material-sub-category').html(response);
				jQuery('.select2').select2();
			},
			error: function(e) {
					console.log(e);
					 }
        });
	});
	
	 jQuery("body").on("change", "#material_code", function(event){	 
	  var material_code  = jQuery(this).val();
	  /* jQuery("#brand_id").html(""); */
		/* alert(product_id);
		return false; */
	   var curr_data = {
	 					material_code : material_code,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'generatematerialcode'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#material_item_code').val(json_obj['material_item_code']);						
					/* jQuery("#brand_id").html(json_obj['brands']); */
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	 jQuery("body").on("click", "#save", function(event){	 
	  var material_code  = jQuery("#material_code").val();
	  var material_sub_category  = jQuery("#material-sub-category").val();
	 
	  // var material_item_code  = jQuery("#material_item_code").val();
	  var material_title  = jQuery("#material_title").val();
	  var project_id  = jQuery("#project_id").val();
	  var unit  = jQuery("#unit").val();
	  var consume  = jQuery("#consume").val();
	  var cost_group  = jQuery("#cost_group").val();
	  if(material_code == '' || material_title == '' || unit == '' || consume == '' || project_id == '' || cost_group == '' || material_sub_category === null || material_sub_category == '')
	  {
		  alert('Please fill all field');
		  return false;
	  }
	  /* jQuery("#brand_id").html(""); */
		/* alert(product_id);
		return false; */
	   var curr_data = {
	 					material_code : material_code , material_title :material_title , project_id : project_id , unit : unit, consume : consume, cost_group : cost_group, material_sub_category : material_sub_category			
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addmaterial'));?>",
                data:curr_data,
                async:false,
                success: function(response){
						
					if(response == 'duplicate')
					{
						alert('Duplicate entry , Please try again.');
						jQuery('#material_code').select2("val", "");
						// jQuery("#material_item_code").val('');
						jQuery("#material_title").val('');
						jQuery('#unit').select2("val", "");
					}
					else
					{
						jQuery('select.material_id').append(response);
						jQuery('#material_code').select2("val", "");
						// jQuery("#material_item_code").val('');
						jQuery("#material_title").val('');
						jQuery('#unit').select2("val", "");
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
	<h4 class="modal-title"> Add Material</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form id="material_form" method="post" class="form-horizontal transferform">

	
		
		
						<div class="form-row">
                            <div class="col-md-4">Material / Item Group <span class="require-field">*</span> </div>
                            <div class="col-md-8">
								<select name="material_code"  style="width: 100%;" class="select2" required="true"  id="material_code">
								<option value="">--Select Item Group--</option>
									<?php 
								foreach($category as $key => $retrive_data)
								{ 
									echo '<option value="'.$retrive_data['id'].'">'.$this->ERPfunction->get_vendor_group_name($retrive_data['id']).'</option>';
								}
								?>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">Sub Category </div>
							<div class="col-md-8">
								<select class="select2" required="true" style="width: 100%;" id="material-sub-category" name="material_sub_category">
									<option value=""><?php echo __('--Sub Category--'); ?></option>
									
								</select>
							</div>
						</div>
						<!--<div class="form-row">
							<div class="col-md-4">Material Code </div>
							<div class="col-md-8">
								<input type="text" name="material_item_code" id="material_item_code" class="form-control" readonly />
							</div>
						</div>-->
						
						<div class="form-row">
							<div class="col-md-4">Material Title<span class="require-field">*</span></div>
                            <div class="col-md-8">
								<input type="text" name="material_title" id="material_title" class="form-control validate[required]"/>
								<!--<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" id="project_id"/>-->
							</div>
                        </div>
		
						<div class="form-row">
							<div class="col-md-4">Unit * </div>
							 <div class="col-md-8">
								  <select class="select2" required="true" style="width: 100%;"id="unit" name="unit_id">
									<option value="">--Unit--</option>
									<?php
                                    if(isset($unitlist)){
                                        foreach($unitlist as $unit_info){
                                        ?>
                                   <option value="<?php echo $unit_info['cat_id'];?>" ><?php echo $unit_info['category_title'];?></option>
                                            <?php             
                                        }
                                    }
                                   ?>
								</select>
							</div> 
							
						</div>
						<div class="form-row">
							<div class="col-md-4">Project</div>
							<div class="col-md-8">
								<select class="select2" style="width: 100%;" id="project_id">
									<option value="0">All</option>
									<?php
                                    if(isset($projects)){
                                        foreach($projects as $project){
                                        ?>
                                   <option value="<?php echo $project['project_id'];?>" ><?php echo $project['project_name'];?></option>
                                            <?php             
                                        }
                                    }
                                   ?>
								</select>
							</div> 
							
						</div>
						<div class="form-row">
							<div class="col-md-4">Consume Type</div>
							<div class="col-md-8">
								<select class="select2" style="width: 100%;" id="consume" name="consume_type">
									<option value="1">Consumable</option>
									<option value="0" selected>Non-consumable</option>
									<option value="3">Asset</option>
								</select>
							</div> 
							
						</div>
						<div class="form-row">
							<div class="col-md-4">Cost Group </div>
							<div class="col-md-8">
								<select class="select2" style="width: 100%;" id="cost_group" name="cost_group">
									<option value="a">A</option>
									<option value="b">B</option>
									<option value="c">C</option>
									<option value="d">D</option>
									<option value="e" selected>E</option>
								</select>
							</div>								
						</div>
		
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="button" value="Add" id="save" name="go" class="btn btn-primary"/>
			</div>
		</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>