<?php
//$this->extend('/Common/menu')
?>
<?php 
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	//jQuery('#material_form').validationEngine();	
	 
	jQuery('.viewmodal').click(function(){
			
			payid=jQuery(this).attr('id');
			jQuery('#modal-view').html('hello');
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
					jQuery('.modal-content').html(response);
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
} );
</script>	
<?php 

$material_code=isset($material_data['material_code'])?$material_data['material_code']:'';
$material_item_code=isset($material_data['material_item_code'])?$material_data['material_item_code']:$material_item_code;
$material_title=isset($material_data['material_title'])?$material_data['material_title']:'';
$unit_id=isset($material_data['unit_id'])?$material_data['unit_id']:'';
$desciption=isset($material_data['desciption'])?$material_data['desciption']:'';


?>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="col-md-10" >
				
                <div class="block block-fill-white">					
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?>  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Material','viewmaterial');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'material_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Material Code<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<select name="material_code" class="form-control">
									<?php
										foreach($category as $key => $retrive_data)
										{
											echo '<option value="'.$key.'" '.$this->ERPfunction->selected($key,$material_code).'>'.$retrive_data['material_code'].'</option>';
										}
									?>
								</select>
							</div> 
							<div class="col-md-2">Material Title<span class="require-field">*</span>:</div>
                            <div class="col-md-4">
								<input type="text" name="material_title" value="<?php echo $material_title;?>" class="form-control validate[required]" value=""/>
							</div>
                        </div>
					   <div class="form-row">
							<div class="col-md-2">Material Code :</div>
							<div class="col-md-4">
								<input type="text" name="material_item_code" value="<?php echo $material_item_code;?>" class="form-control" value=""/>
							</div>
							<div class="col-md-2">Unit :</div>
							 <div class="col-md-3">
								  <select class="select2 validate[required]" style="width: 100%;"id="unit" name="unit_id">
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
								<div class="col-md-2">Material Description :</div>
								<div class="col-md-4">
									<textarea name="desciption" class="form-control"> <?php echo $desciption;?></textarea>
									
								</div>
								
							</div>
						
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
				</div>
				<?php $this->Form->end(); ?>
			</div>
         </div>