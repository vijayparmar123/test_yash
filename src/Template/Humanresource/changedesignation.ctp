<?php
use Cake\Routing\Router;
$last_edit_by = isset($employee_data['designation_change_by'])?$this->ERPfunction->get_user_name($employee_data['designation_change_by']):'NA';
$last_edit = isset($employee_data['actual_designation_change_date'])?date("m-d-Y",strtotime($employee_data['actual_designation_change_date'])):'NA';
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#date_of_birth,#as_on_date,#date_of_joining').datepicker({
		showButtonPanel: true,
		//minDate: 0,
		dateFormat: "dd-mm-yy",
		changeMonth: true,
	    changeYear: true,
			 // minDate: 2005,
			 // yearRange: '1940:2012',
        maxDate: new Date(),
        // minDate: new Date(2005, 10 - 1, 25),
	        // yearRange:'-65:+0',
	        // onChangeMonthYear: function(year, month, inst) {
	            // jQuery(this).val(month + "-" + year);
	        // }			
			onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));
        }
    }); 
	
	 $("#date_of_birth").focus(function () {
        $(".ui-datepicker-calendar").hide();
        // $("#ui-datepicker-div").position({
            // my: "center top",
            // at: "center bottom",
           // of: $(this)
        // });
    });
	
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
  
  jQuery("body").on("click", ".btn-cat-update", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var cat_id  = jQuery(this).attr('id') ;
	  var model  = jQuery(this).attr('model');
	  var cat_name  = jQuery('#cat_name').val();
	  var category  = jQuery('#category').val();
	 
	   var curr_data = {	 						 					
	 					cat_id : cat_id,
						cat_name : cat_name,
						model : model,
						category : category,
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
				$('#designation option[value="'+cat_id+'"]').detach();
				var newOption = new Option(cat_name, cat_id, false, false);
				$('#designation').append(newOption).trigger('change');
			},
			error: function (tab) {
				alert('error');
			}
		});
  }); 
  
	jQuery("body").on("change", ".desi_list", function(){
		var designation_id  = jQuery(this).val();
		if(designation_id == '')
		{
			jQuery('#designation_cat').val(jQuery('#designation_cat option:first').val());
			jQuery('#designation_cat_hidden').val('');
			return false;
		}
	    var curr_data = {designation_id : designation_id};	 				
	 	jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'designationwisecategory'));?>",
                data:curr_data,
                async:false,
                success: function(response){                 
					jQuery('#designation_cat').val(response);
					jQuery('#designation_cat_hidden').val(response);
                },
		        error: function(e) {
		                console.log(e);
		             }
        });	
	});
		
	 jQuery("body").on("click", "#btn-add-category", function(){		
		var category_name  = jQuery('#category_name').val() ;
		var designation_category  = jQuery('#designation_category').val() ;
		var model  = jQuery(this).attr('model');
		/* alert(category_name + ' ' + model);
		return false; */
		if(category_name != "" && designation_category != "")
		{
			var curr_data = {					
					model : model,
					category_name: category_name,				
					designation_category: designation_category				
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
						jQuery('#designation_category').val(jQuery('#designation_category option:first').val());
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
			alert("Please fill all field.");
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
  
  jQuery("body").on("submit","#user_form",function(){
			var date = $("#date_of_birth").val();
			var employee_id = $("#employee_id").val();
			var flag = "false";
			var curr_data = {	 						 					
	 					date : date,employee_id : employee_id	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'checkSameMonthDesignationHistory'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					
					if(response == "true"){
						if(confirm("You want to overwrite paystrucure record?"))
						{
							if(confirm("You want to overwrite paystrucure record?"))
							{
								flag = "true";
							}
							else{
								return false;
							}
						}
						else{
							return false;
						}
					}else{
						flag = "true";
					}	
                },
                error: function (tab) {
                    alert('error');
                }
            });
			if(flag == "true"){
				return true;
			}else{
				return false;
			}
	});
} );
</script>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>	
<?php 
$employee_id=isset($employee_data['employee_id'])?$employee_data['employee_id']:'';
//$employee_no=(isset($employee_data['employee_no']))?$employee_data['employee_no']:$employee_no;
$employee_no=$id;
$user_identy_number = isset($employee_data['user_identy_number'])?$employee_data['user_identy_number']:'';
$date_of_joining=isset($employee_data['date_of_joining'])?$this->ERPfunction->get_date($employee_data['date_of_joining']):'';
$first_name=isset($employee_data['first_name'])?$employee_data['first_name']:'';
$middle_name=isset($employee_data['middle_name'])?$employee_data['middle_name']:'';
$last_name=isset($employee_data['last_name'])?$employee_data['last_name']:'';
$date_of_birth=isset($employee_data['date_of_birth'])?$this->ERPfunction->get_date($employee_data['date_of_birth']):'';
$as_on_date=isset($employee_data['as_on_date'])?$this->ERPfunction->get_date($employee_data['as_on_date']):'';
$designation=isset($employee_data['designation'])?$employee_data['designation']:'';
$change_date = isset($employee_data['designation_change_date'])?date("F Y",strtotime($employee_data['designation_change_date'])):"";
$category = isset($employee_data['category'])?$employee_data['category']:"";

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
				<?php
				if(isset($employee_data)){
				?>
					<a href="" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				<?php
				}
				else
				{
				?>
					<a href="<?php echo $this->request->base;?>/humanresource/emplyeelist" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				<?php } ?>
			</div>
		</div>
		
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		
		<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
										  
		<div class="content controls">
			<div class="form-row">
				<div class="col-md-2">Employee No:</div>
				<input type="hidden" value="<?php echo $id; ?>" id="employee_id">
				<div class="col-md-3">
					<?php echo $user_identy_number;?>
				</div>
			
				<div class="col-md-4 text-center">Name:&nbsp;&nbsp;&nbsp;<?php echo $first_name ." ".$last_name;?></div>
			</div>
			<div class="form-row"> <hr/>
				<div class="col-md-2">Change Affect Date : <span class="require-field">*</span> </div>
				<div class="col-md-2"><input type="text" name="change_date" value="<?php echo $change_date;?>"
				class="form-control validate[required]" id="date_of_birth" /></div>
				
				<div class="col-md-2">Designation</div>
				<div class="col-md-3">
					<select class="select2 desi_list" required="true" style="width: 100%;" id="designation" name="designation">
						<option value=""><?php echo __('--Designation--'); ?></option>
						<?php
						if(isset($designationlist)){
							foreach($designationlist as $unit_info){
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
						}
					   ?>
					</select>
				</div>
				<?php 
				 if($role != 'hrmanager' && $role != 'erpoperator' && $role != 'erpmanager')
				 {
				?>
					<div class="col-md-1">
						<button type="button" id="designation" data-type="designation" data-toggle="modal" 
						data-target="#load_modal" class="btn btn-default viewmodal">Add More </button>							
					</div>
				 <?php } ?>
			</div>
			
			<div class="form-row">
				<input type="hidden" name="category" value="<?php echo $category ?>" id="designation_cat_hidden">
				<div class="col-md-2">Category*</div>
				<div class="col-md-2">
					<select class="form-control validate[required]" name="category_show" disabled id="designation_cat" style="width: 100%;">
						<option value="" >Select Category</option>
						<option value="a" <?php echo $this->ERPfunction->selected("a",$category);?>>A</option>
						<option value="b" <?php echo $this->ERPfunction->selected("b",$category);?>>B</option>
						<option value="c" <?php echo $this->ERPfunction->selected("c",$category);?>>C</option>
					</select>							
				</div>
			</div>
							
			<div class="form-row">
				<div class="col-md-2"></div>
				<div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
			</div>
		</div>
		<?php $this->Form->end(); ?>
		<div class="row" style="font-style:italic;color:gray;padding-top:15px;">
			<div class="col-md-8 pull-right">
				<div class="col-md-5">
					<?php echo "Last Edited By: {$last_edit_by }"; ?>
				</div>
				<div class="col-md-5">
					<?php echo "Last Edit: {$last_edit}"; ?>
				</div>
				
			</div>
		</div> 
	</div>
<?php } ?>   

</div>
 
