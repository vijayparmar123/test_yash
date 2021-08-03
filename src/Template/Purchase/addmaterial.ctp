<?php
//$this->extend('/Common/menu')
?>
<?php 
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('#material_form').validationEngine();
	
	jQuery("body").on("click", "#save-sub-category-value", function(){
		var material_code_id = jQuery("#sub-category-material-code").val();
		var sub_category_value = jQuery("#sub-category-value").val();
		
		if(sub_category_value == "")
		{
			alert("Please Enter Sub Category Name.");
			return false;
		}
		
		var curr_data = { material_code_id: material_code_id , sub_category_value : sub_category_value };
					
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'savematerialsubgroup'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				var json_obj = jQuery.parseJSON(response);
				jQuery('#sub-category-value').val('');		
				jQuery('#sub-group-listing tbody').append(json_obj['listing_data']);		
				jQuery('#material-sub-category').append(json_obj['dropdown_data']);		
			},
			error: function (tab) {
				alert('error');
			}
		});
	});
	
	jQuery("body").on("click", "#add_more_subcategory", function(){		
		var material_code  = jQuery('#material_code').val();
		
		if(material_code == '')
		{
			alert("Please Select Material / Group Item First.");
			return false;
		}
		var curr_data = { material_code: material_code };
				
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addmaterialsubgroup'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				jQuery('#load_modal_subcategory .modal-content').html(response);		
			},
			error: function (tab) {
				alert('error');
			}
		});
	});
	
	

	 jQuery("body").on("change", "#material_code", function(event){	 
	  var material_code  = jQuery(this).val();
	  var material_item_code  = jQuery("#material_item_code").val();
	  
	  /* jQuery("#brand_id").html(""); */
		/* alert(product_id);
		return false; */
	   var curr_data = {
	 					material_code : material_code,material_item_code : material_item_code
	 					};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
				<?php if($user_action == 'edit'){?>
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'generatematerialcodeedit'));?>",
				<?php }else{ ?>
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'generatematerialcode'));?>",
				<?php } ?>
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
	
	jQuery('.viewmodal').click(function(){
			
			payid=jQuery(this).attr('id');
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html('');
			 var model  = jQuery(this).attr('data-type') ;
		//alert(model);
		//return false;
	   var curr_data = {type : model};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'categorylist'));?>",
                data:curr_data,
                async:false,
                success: function(response){                    
					jQuery('#load_modal .modal-content').html(response);
					jQuery('.select2').select2();
                },
                beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
		        error: function(e) {
		                console.log(e);
		                 }
            });			
		});
		jQuery('.add_group').click(function(){
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html(''); 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'materialitemgroup'));?>",
				async:false,
				success: function(response){                    
					jQuery('#load_modal1 .modal-content').html(response);
					jQuery('.select2').select2();
				},
				beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
				error: function(e) {
						console.log(e);
						 }
			});			
		});
	 jQuery("body").on("click", "#btn-add-category", function(){		
		var category_name  = jQuery('#category_name').val() ;
		var model  = jQuery(this).attr('model');	
		/* alert(category_name + ' ' + model);
		return false; */
		if(category_name != "")
		{
			var curr_data = {					
					model : model,
					category_name: category_name				
					};
					
					jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addcategory'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					/* //alert(category_name + ' ' + model + ' ' + response);
		//return false; */
                     var json_obj = jQuery.parseJSON(response);					
						jQuery('.table').append(json_obj[0]);
						jQuery('#category_name').val("");						
						jQuery("#"+model).append(json_obj[1]);	
						jQuery('.select2').select2();
						return false;		
                },
                error: function (tab) {
                    alert('error');
                }
            });
					
					
		
		}
		else
		{
			alert("Please enter Category Name.");
		}
	});
	
	jQuery("body").on("click", ".btn-delete-cat", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var cat_id  = jQuery(this).attr('id') ;
	  var model  = jQuery(this).attr('model') ;

	if(confirm("Are you sure want to delete this record?"))
		{
	   var curr_data = {	 						 					
	 					cat_id : cat_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'removecategory'));?>",
                data:curr_data,
                async:false,
                success: function(response){
						jQuery("#"+model+" option[value='"+cat_id+"']").remove();
                    	jQuery('tr#cat-'+cat_id).hide();
						jQuery('.select2').select2();
							return true; 	
                },
                error: function (tab) {
                    alert('error');
                }
            });
		}
	
  });
  
  jQuery("body").on("click", ".btn-edit-cat", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var cat_id  = jQuery(this).attr('id') ;
	  var model  = jQuery(this).attr('model');

	   var curr_data = {	 						 					
	 					cat_id : cat_id,
						model : model,
	 					};	 				
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editcategory'));?>",
			data:curr_data,
			async:false,
			success: function(response){
					// jQuery('#term-'+term_id)
					jQuery('tr#cat-'+cat_id).html(response);
			},
			error: function (tab) {
				alert('error');
			}
		});
  });

  jQuery("body").on("click", ".btn-cat-update", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var cat_id  = jQuery(this).attr('id') ;
	  var model  = jQuery(this).attr('model');
	  var cat_name  = jQuery('#cat_name').val();
	 
	   var curr_data = {	 						 					
	 					cat_id : cat_id,
						cat_name : cat_name,
						model : model,
	 					};	 				
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updatecategory'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				jQuery('tr#cat-'+cat_id).html(response);
			},
			error: function (tab) {
				alert('error');
			}
		});
  }); 
  
    jQuery("body").on("click", ".btn-cat-update-cancel", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var cat_id  = jQuery(this).attr('id') ;
	  var model  = jQuery(this).attr('model');
	  var cat_name  = jQuery('#cat_name').val();
	 
	   var curr_data = {	 						 					
	 					cat_id : cat_id,
						cat_name : cat_name,
						model : model,
	 					};	 				
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'cancelcatsave'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				jQuery('tr#cat-'+cat_id).html(response);
			},
			error: function (e) {
				alert('error');
				console.log(e.responseText);
			}
		});
  });
  
  jQuery("body").on("click", ".btn-edit-item", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var group_id  = jQuery(this).attr('id') ;

	   var curr_data = {	 						 					
	 					group_id : group_id,
	 					};	 				
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editmaterialgroup'));?>",
			data:curr_data,
			async:false,
			success: function(response){
					// jQuery('#term-'+term_id)
					jQuery('tr#cat-'+group_id).html(response);
			},
			error: function (tab) {
				alert('error');
			}
		});
  });
  
  jQuery("body").on("click", ".btn-group-update-cancel", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var group_id  = jQuery(this).attr('id') ;
	 
	   var curr_data = {	 						 					
	 					group_id : group_id,
	 					};	 				
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'cancelgroupsave'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				jQuery('tr#cat-'+group_id).html(response);
			},
			error: function (e) {
				alert('error');
				console.log(e.responseText);
			}
		});
  });
  
  jQuery("body").on("click", ".btn-group-update", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var group_id  = jQuery(this).attr('id') ;
	  var group_code  = jQuery('#cat-'+group_id+' #group_code').val();
	  var group_title  = jQuery('#cat-'+group_id+' #group_title').val();
		
	   var curr_data = {	 						 					
	 					group_id : group_id,
						group_code : group_code,
						group_title : group_title,
	 					};	 				
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updatematerialitem'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				jQuery('tr#cat-'+group_id).html(response);
				// $('#material_code').select2('refresh');
				// $('#material_code').trigger('change');
				// $('#material_code').val(group_id).trigger('change');
				$('#material_code option[value="'+group_id+'"]').detach();
				var newOption = new Option(group_title, group_id, false, false);
				$('#material_code').append(newOption).trigger('change');
			},
			error: function (tab) {
				alert('error');
			}
		});
  });
  
  jQuery("body").on("click", "#btn-add-group", function(){		
		var item_code  = jQuery('#item_code1').val() ;
		var item_name  = jQuery('#item_name1').val() ;
		var model  = jQuery(this).attr('model');	
		/* alert(category_name + ' ' + model);
		return false; */
		if(item_code != "" && item_name != "")
		{
			var curr_data = {					
					item_code : item_code,
					item_name: item_name				
					};
					
					jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addmaterialgroup'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					/* //alert(category_name + ' ' + model + ' ' + response);
		//return false; */
                     var json_obj = jQuery.parseJSON(response);					
						jQuery('.table').append(json_obj['row']);
						jQuery('#item_code1').val("");						
						jQuery('#item_name1').val("");						
						jQuery("#material_code").append(json_obj['options']);	
						jQuery('.select2').select2();
						return false;		
                },
                error: function (tab) {
                    alert('error');
                }
            });
					
					
		
		}
		else
		{
			alert("Please fill all the fields.");
		}
	});
});
</script>	
<?php 

$material_id=isset($material_data['material_id'])?$material_data['material_id']:'';
$material_code=isset($material_data['material_code'])?$material_data['material_code']:'';
$material_sub_group=isset($material_data['material_sub_group'])?$material_data['material_sub_group']:'';
$brand_id=isset($material_data['brand_id'])?$material_data['brand_id']:'';
$material_item_code=isset($material_data['material_item_code'])?$material_data['material_item_code']:'';
$material_title=isset($material_data['material_title'])?$material_data['material_title']:'';
$unit_id=isset($material_data['unit_id'])?$material_data['unit_id']:'';
$desciption=isset($material_data['desciption'])?$material_data['desciption']:'';
$project_id=isset($material_data['project_id'])?$material_data['project_id']:'';
$consume=isset($material_data['consume'])?$material_data['consume']:'';
$cost_group=isset($material_data['cost_group'])?$material_data['cost_group']:'';
// debug($consume);die;

?>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="load_modal1" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="load_modal_subcategory" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade " id="load_modal_edit_subgroup" role="dialog">
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
else{ ?>          
<div class="col-md-12">
<div class="row">
     <div class="block block-fill-white">
				
                <div class="block block-fill-white">					
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<?php if($user_action == 'edit') { ?>
						<a href="<?php //echo $this->ERPfunction->action_link('Purchase',$back);?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						<?php } else {?>
						<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						<?php } ?>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'material_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Material / Item Group <span class="require-field">*</span> </div>
                            <div class="col-md-3">
								<select name="material_code"  style="width: 100%;" class="select2" required="true"  id="material_code">
								<option value="">--Select Item Group--</option>
									<?php 
								foreach($category as $key => $retrive_data)
								{ 
									if($retrive_data['id'] != 17)
									{
									echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$material_code).'>'.$this->ERPfunction->get_vendor_group_name($retrive_data['id']).'</option>';
									}
								}
								?>
								</select>
							</div>
							<div class="col-md-1">
								<button type="button" id="material_group" data-type="groups" data-toggle="modal" 
								data-target="#load_modal1" class="btn btn-default add_group" style="">Add More </button>							
							</div>
							<div class="col-md-2">Material Title<span class="require-field">*</span></div>
                            <div class="col-md-4">
								<input type="text" name="material_title" value="<?php echo htmlspecialchars($material_title);?>" class="form-control validate[required]" value=""/>
							</div>
                        </div> 
						 <div class="form-row">
							<div class="col-md-2">Sub Category </div>
							<div class="col-md-3">
								<select class="select2" required="true" style="width: 100%;" id="material-sub-category" name="material_sub_category">
									<option value=""><?php echo __('--Sub Category--'); ?></option>
									<?php 
									if($user_action == "edit"){
										$subgrouplist = $this->ERPfunction->get_material_subgroup($material_code);
										foreach($subgrouplist as $retrive_data)
										{
											echo '<option value ="'.$retrive_data['sub_group_id'].'"'.$this->ERPfunction->selected($retrive_data['sub_group_id'],$material_sub_group).'>'.$retrive_data['sub_group_title'].'</option>';
										}
									}
										
									?>
								</select>
							</div>
							<div class="col-md-1">
								<button type="button" id="add_more_subcategory" data-type="sub-category" data-toggle="modal" 
								data-target="#load_modal_subcategory" class="btn btn-default add_more_subcategory" style="">Add More </button>							
							</div>
						</div> 
					   <div class="form-row">
							<?php
								if($user_action == 'edit'){
							?>
							<input type="hidden" id="material_id" value="<?php echo $material_id; ?>">
							<div class="col-md-2">Material Code </div>
							<div class="col-md-4">
								<input type="text" name="material_item_code" id="material_item_code" value="<?php echo $material_item_code;?>" class="form-control" readonly />
							</div>
								<?php } ?>
							<div class="col-md-2">Unit </div>
							<div class="col-md-3">
								  <select class="select2" required="true" style="width: 100%;"id="unit" name="unit_id">
									<option value=""><?php echo __('--Unit--'); ?></option>
									<?php
                                    if(isset($unitlist)){
                                        foreach($unitlist as $unit_info){
                                        ?>
                                   <option value="<?php echo $unit_info['cat_id'];?>" <?php                                            
                                                if($unit_id == $unit_info['cat_id']){
                                                    echo 'selected="selected"';
                                                }else{
                                                    echo '';
                                                }
                                            
                                        
                                        ?> ><?php echo $unit_info['category_title'];?></option>
                                            <?php             
                                        }
                                    }
                                   ?>
								</select>
							</div> 
							<div class="col-md-1">
								<button type="button" id="product_units" data-type="unit" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal" style="">Add More </button>							
							</div>
						</div>
						<div class="form-row">
								<?php 
								if($user_action == 'edit'){
								?>
								<input type="hidden" name="old_project_id" value="<?php echo $project_id; ?>">
								<?php
								}
								?>
								<div class="col-md-2">Project </div>
								<div class="col-md-4">
								  <select class="select2" style="width: 100%;" id="project" name="project_id">
									<option value="0">All</option>
									<?php
                                    if(isset($projects)){
                                        foreach($projects as $project){
                                        ?>
                                   <option value="<?php echo $project['project_id'];?>" <?php                                            
                                                if($project_id == $project['project_id']){
                                                    echo 'selected="selected"';
                                                }else{
                                                    echo '';
                                                }
                                            
                                        
                                        ?> ><?php echo $project['project_name'];?></option>
                                            <?php             
                                        }
                                    }
                                   ?>
								</select>
							</div> 
								<div class="col-md-2">Consume Type </div>
								<div class="col-md-3">
								  <select class="select2" style="width: 100%;" id="consume" name="consume">
									<option value="1" <?php echo ($consume === 1)?"selected":"" ?>>Consumable</option>
									<option value="0" <?php echo ($consume === 0)?"selected":"" ?>>Retunable / Non-consumable</option>
									<option value="3" <?php echo ($consume === 3)?"selected":"" ?>>Asset</option>
								</select>
							</div> 							
						</div>
						<div class="form-row">
							<div class="col-md-2">Material Description </div>
							<div class="col-md-4">
								<textarea name="desciption" class="form-control"> <?php echo $desciption;?></textarea>
							</div>
							<div class="col-md-2">Cost Group </div>
							<div class="col-md-3">
								<select class="select2" style="width: 100%;" id="cost_group" name="cost_group">
									<option value="a" <?php echo ($cost_group === 'a')?"selected":"" ?>>A</option>
									<option value="b" <?php echo ($cost_group === 'b')?"selected":"" ?>>B</option>
									<option value="c" <?php echo ($cost_group === 'c')?"selected":"" ?>>C</option>
									<option value="d" <?php echo ($cost_group === 'd')?"selected":"" ?>>D</option>
									<option value="e" <?php echo (($cost_group === 'e') || ($cost_group != 'a' && $cost_group != 'b' && $cost_group != 'c' && $cost_group != 'd'))?"selected":"" ?>>E</option>
								</select>
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="<?php echo ($user_action == 'edit')?'button':'submit'; ?>" class="btn <?php echo ($user_action == 'edit')?'submit_form':''; ?> btn-primary"><?php echo $button_text;?></button></div>
                        </div>
				</div>
				<?php $this->Form->end(); ?>
			</div>
			</div>
			</div>
<?php } ?>
         </div>
		 </div>
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function(){
	 jQuery("body").on("click",".submit_form",function(){
		 var material_id = $("#material_id").val();
		
				var curr_data = {material_id : material_id};	 				
				 jQuery.ajax({
						headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'checkgrncreated'));?>",
						data:curr_data,
						async:false,
						success: function(response){                    
							if(response)
							{
								if(confirm("Are you sure you want to edit this material?"))
								{
									if(confirm("Are you sure you want to edit this material?"))
									{
										if(confirm("Are you sure you want to edit this material?"))
										{
											$("#material_form").submit();
										}
									}
								}
							}else{
								$("#material_form").submit();
							}
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e);
								 }
					});
	 });
});
</script>