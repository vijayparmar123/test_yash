<?php
use Cake\Routing\Router;
use Google\Cloud\Storage\StorageClient;
?>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

	jQuery(document).ready(function() {
		jQuery('.viewmodal').click(function(){	
			payid=jQuery(this).attr('id');
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html('');
			var model  = jQuery(this).attr('data-type') ;
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
		
		jQuery("body").on("click", "#btn-add-category", function() {		
			var category_name  = jQuery('#category_name').val() ;
			var model  = jQuery(this).attr('model');	
			if(category_name != "") {
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
                	success: function(response) {
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
			} else {
				alert("Please enter Category Name.");
			}
		});
	
		jQuery("body").on("click", ".btn-edit-cat", function(event) {
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
				success: function(response) {
					jQuery('tr#cat-'+cat_id).html(response);
				},
				error: function (tab) {
					alert('error');
				}
			});
  		});
  
  		jQuery("body").on("click", ".btn-cat-update-cancel", function(event) {
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
		
		jQuery("body").on("click", ".btn-delete-cat", function(event){
	  		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  		var docHeight = jQuery(document).height(); //grab the height of the page
	  		var scrollTop = jQuery(window).scrollTop();
	  		var cat_id  = jQuery(this).attr('id') ;
		  	var model  = jQuery(this).attr('model') ;

			if(confirm("Are you sure want to delete this record?")) {
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
  
		jQuery("body").on("change", "#project_id", function(event){ 
	 
	  		var project_id  = jQuery(this).val() ;
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
					jQuery('.inward_no').val(json_obj['project_code']+'/IW/'+ json_obj['short_name'] + '/' + json_obj['auto2']);
                },
                error: function (e) {
                    alert('Error');
                }
            });	
		});
		jQuery("body").on("change", "#project_id", function(event){ 
			var project_id = $(this).val();
			if(project_id == 2) {
				$(".dep_pro_div").css("display","block");
			}else{
				$(".dep_pro_div").css("display","none");
			}
		});
		jQuery('#user_form').validationEngine();
			jQuery('#date_of_birth,#as_on_date').datepicker({
				dateFormat: "dd-mm-yy",
		  		changeMonth: true,
	        	changeYear: true,
	        	yearRange:'-65:+0',
				maxDate: new Date(),
	        	onChangeMonthYear: function(year, month, inst) {
	            	jQuery(this).val(month + "-" + year);
	        	}                    
            }); 
				
		jQuery("body").on("change", ".dep_pro", function(event){ 
			var type = $(this).val();
			if(type == 'department')
			{
				$(".department_div").css("display","block");
				$(".second_project_div").css("display","none");
			}else if(type == 'project'){
				$(".second_project_div").css("display","block");
				$(".department_div").css("display","none");
			}
		});
	
		// Add Agency Name
		jQuery("body").on("click", "#btn-add-group", function(){		
			var item_code  = jQuery('#item_code1').val() ;
			var item_name  = jQuery('#item_name1').val() ;
			var model  = jQuery(this).attr('model');	
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
        	        url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addinwardagencyname'));?>",
            	    data:curr_data,
                	async:false,
                	success: function(response) {
					    var json_obj = jQuery.parseJSON(response);					
						jQuery('.table').append(json_obj['row']);
						// jQuery('#item_code1').val("");						
						jQuery('#item_name1').val("");						
						jQuery("#agency_name").append(json_obj['options']);	
						jQuery('.select2').select2();
						return false;		
                	},
                	error: function (tab) {
	                    alert(tab);
    	            }
            	});
			} else {
				alert("Please fill all the fields.");
			}
		});
		jQuery('.add_group').click(function(){
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html(''); 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getinwardagencyname'));?>",
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

		// Edit Agency Name
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
		   		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editinwardagencyname'));?>",
		   		data:curr_data,
				async:false,
				success: function(response){
					jQuery('tr#cat-'+group_id).html(response);
			   },
		   		error: function (tab) {
					alert('error');
		   		}
	   		});
 		});

		//Cancel edit agency name
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
		   		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'cancelinwardagencysave'));?>",
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

		//  Update agency name
		jQuery("body").on("click", ".btn-group-update", function(event){
	 
			event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			var docHeight = jQuery(document).height(); //grab the height of the page
			var scrollTop = jQuery(window).scrollTop();
			var group_id  = jQuery(this).attr('id') ;
			var group_title  = jQuery('#cat-'+group_id+' #group_title').val();
			var curr_data = {	 						 					
				group_id : group_id,
				group_title : group_title,
			};	 				
			jQuery.ajax({
	   			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateinwardagency'));?>",
				data:curr_data,
				async:false,
				success: function(response){
		   			jQuery('tr#cat-'+group_id).html(response);
		   			$('#material_code option[value="'+group_id+'"]').detach();
		   			var newOption = new Option(group_title, group_id, false, false);
		   			$('#material_code').append(newOption).trigger('change');
				},
				error: function (tab) {
				   alert('error');
		   		}
	   		});
 		});
		// Add Written by
		jQuery("body").on("click", "#btn-add-writtenby", function(){		
			var item_code  = jQuery('#item_code1').val() ;
			var item_name  = jQuery('#item_name1').val() ;
			var model  = jQuery(this).attr('model');
			if(item_code != "" && item_name != "") {
				var curr_data = {					
					item_code : item_code,
					item_name: item_name				
				};
						
				jQuery.ajax({
					headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addinwardwrittenby'));?>",
					data:curr_data,
					async:false,
					success: function(response){
						var json_obj = jQuery.parseJSON(response);					
						jQuery('.table').append(json_obj['row']);
						// jQuery('#item_code1').val("");						
						jQuery('#item_name1').val("");						
						jQuery("#written_by").append(json_obj['options']);	
						jQuery('.select2').select2();
						return false;		
						},
						error: function (tab) {
							alert(tab);
						}
					});		
			} else {
				alert("Please fill all the fields.");
			}
		});
		jQuery('.add_writtenby').click(function(){
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html(''); 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getinwardwrittenby'));?>",
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
		// Edit written by
		jQuery("body").on("click", ".btn-edit-writtenby", function(event){
	 		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	 		var docHeight = jQuery(document).height(); //grab the height of the page
	 		var scrollTop = jQuery(window).scrollTop();
			var group_id  = jQuery(this).attr('id');
	  		var curr_data = {	 						 					
				group_id : group_id,
			};	 				
	   		jQuery.ajax({
		   		headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
		   		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editinwardwrittenby'));?>",
		   		data:curr_data,
				async:false,
				success: function(response){
					jQuery('tr#cat-'+group_id).html(response);
			   },
		   		error: function (tab) {
					alert('error');
		   		}
	   		});
		});
		//Cancel edit written by
		jQuery("body").on("click", ".btn-writttenby-update-cancel", function(event){
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
		   		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'cancelinwardwrittenby'));?>",
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
		 //  Update written by
		jQuery("body").on("click", ".btn-writtenby-update", function(event) {
			event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			var docHeight = jQuery(document).height(); //grab the height of the page
			var scrollTop = jQuery(window).scrollTop();
	 		var group_id  = jQuery(this).attr('id') ;
	 		var group_title  = jQuery('#cat-'+group_id+' #group_title').val();
	 		var curr_data = {	 						 					
		 		group_id : group_id,
		 		group_title : group_title,
	 		};	 				
	 		jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			 	url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateinwardwrittenby'));?>",
		 		data:curr_data,
			 	async:false,
			 	success: function(response){
					jQuery('tr#cat-'+group_id).html(response);
					$('#material_code option[value="'+group_id+'"]').detach();
					var newOption = new Option(group_title, group_id, false, false);
					$('#material_code').append(newOption).trigger('change');
		 		},
		 		error: function (tab) {
					alert('error');
				}
			});
		});
		// Designation Start
// Add Designation
jQuery("body").on("click", "#btn-add-designation", function(){		
			var item_code  = jQuery('#item_code1').val() ;
			var item_name  = jQuery('#item_name1').val() ;
			var model  = jQuery(this).attr('model');
			if(item_code != "" && item_name != "") {
				var curr_data = {					
					item_code : item_code,
					item_name: item_name				
				};
						
				jQuery.ajax({
					headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addinwarddesignation'));?>",
					data:curr_data,
					async:false,
					success: function(response){
						var json_obj = jQuery.parseJSON(response);					
						jQuery('.table').append(json_obj['row']);
						// jQuery('#item_code1').val("");						
						jQuery('#item_name1').val("");						
						jQuery("#designation").append(json_obj['options']);	
						jQuery('.select2').select2();
						return false;		
						},
						error: function (tab) {
							alert(tab);
						}
					});		
			} else {
				alert("Please fill all the fields.");
			}
		});
		jQuery('.add_designation').click(function(){
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html(''); 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getinwarddesignation'));?>",
				async:false,
				success: function(response){                    
					jQuery('#load_modal2 .modal-content').html(response);
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
		// Edit designation
		jQuery("body").on("click", ".btn-edit-designation", function(event){
	 		event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	 		var docHeight = jQuery(document).height(); //grab the height of the page
	 		var scrollTop = jQuery(window).scrollTop();
			var group_id  = jQuery(this).attr('id');
	  		var curr_data = {	 						 					
				group_id : group_id,
			};	 				
	   		jQuery.ajax({
		   		headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
		   		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editinwarddesignation'));?>",
		   		data:curr_data,
				async:false,
				success: function(response){
					jQuery('tr#cat-'+group_id).html(response);
			   },
		   		error: function (tab) {
					alert('error');
		   		}
	   		});
		});
		//Cancel edit Designation
		jQuery("body").on("click", ".btn-designation-update-cancel", function(event){
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
		   		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'cancelinwarddesignation'));?>",
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
		 //  Update desiganation 
		jQuery("body").on("click", ".btn-designation-update", function(event) {
			event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			var docHeight = jQuery(document).height(); //grab the height of the page
			var scrollTop = jQuery(window).scrollTop();
	 		var group_id  = jQuery(this).attr('id') ;
	 		var group_title  = jQuery('#cat-'+group_id+' #group_title').val();
			// alert(group_title);return false; 
			 var curr_data = {	 						 					
		 		group_id : group_id,
		 		group_title : group_title,
	 		};	 				
	 		jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			 	url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateinwarddesignation'));?>",
		 		data:curr_data,
			 	async:false,
			 	success: function(response){
					jQuery('tr#cat-'+group_id).html(response);
					$('#material_code option[value="'+group_id+'"]').detach();
					var newOption = new Option(group_title, group_id, false, false);
					$('#material_code').append(newOption).trigger('change');
		 		},
		 		error: function (tab) {
					alert('error');
				}
			});
		});
		// Designation End
		
	});
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
	$created_by = isset($update_inward['created_by'])?$this->ERPfunction->get_user_name($update_inward['created_by']):'NA';
	$last_edit = isset($update_inward['last_edit'])?date("m-d-Y H:i:s",strtotime($update_inward['last_edit'])):'NA';
	$last_edit_by = isset($update_inward['last_edit_by'])?$this->ERPfunction->get_user_name($update_inward['last_edit_by']):'NA';
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
<div class="modal fade " id="load_modal2" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>

<div class="col-md-10" >
	<?php 
		if(!$is_capable) {
			$this->ERPfunction->access_deniedmsg();
		} else {
	?>			
    <div class="block block-fill-white">
		<div class="head bg-default bg-light-rtl">
			<h2><?php echo $form_header;?></h2>
			<div class="pull-right">
				<?php
					if(isset($update_inward)){
				?>
				<a href="<?php //echo $this->ERPfunction->action_link('Contract',$back);?>" onclick = "javascript:window.close();" class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
				<?php
					}else {
				?>
				<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
				<?php } ?>
			</div>
		</div>				      
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		<div class="content controls">
			<div class="form-row">
                <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code; ?>"
					class="form-control validate[required]" value="" readonly="true"/></div>
				<div class="col-md-2">Project Name:*</div>
                <div class="col-md-4">
					<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
						<option value="">--Select Project--</Option>
							<?php 
								foreach($projects as $retrive_data) {
							?>
						<option value="<?php echo $retrive_data['project_id'];?>" 
							<?php 
								if(isset($update_inward)){
									if($update_inward['project_id'] == $retrive_data['project_id']){
										echo 'selected="selected"';
									}
								}
							?>
						>
							<?php echo $retrive_data['project_name']; ?>
						</option>
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
                            <label><input type="radio" name="dep_pro" class="dep_pro" value="department" <?php echo (isset($update_inward) && $update_inward['inward_from'] == 'department')?'checked':''; ?> /> Department</label>
                        </div>
                        <div class="radiobox-inline" style="padding:0 50px;">
                            <label><input type="radio" name="dep_pro" value="project" class="dep_pro" <?php echo (isset($update_inward) && $update_inward['inward_from'] == 'project')?'checked':''; ?>/>Project</label>
                        </div>
                    </div>				
				</div>
				<div class="form-row department_div" style="<?php echo (isset($update_inward) && $update_inward['inward_from'] == 'department')?'display:block':'display:none'; ?>">
					<div class="col-md-2">Department:*</div>
                    	<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="department" id="department">
								<option value="">--Select Department--</Option>
									<?php 
										foreach($department_list as $retrive_data) {
									?>
								<option value="<?php echo $retrive_data['cat_id'];?>"
									<?php 
										if(isset($update_inward)){
											if($update_inward['department_id'] == $retrive_data['cat_id']){
												echo 'selected="selected"';
											}
										}
									?>
								>
									<?php echo $retrive_data['category_title']; ?> 
								</option>
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
								<select class="select2" style="width: 100%;" name="sub_project" id="sub_project">
									<option value="">--Select Project--</Option>
										<?php 
											foreach($projects as $retrive_data) {
										?>
									<option value="<?php echo $retrive_data['project_id'];?>" 
										<?php 
											if(isset($update_inward)){
												if($update_inward['sub_project_id'] == $retrive_data['project_id']){
													echo 'selected="selected"';
												}
											}

										?>
									>
										<?php echo $retrive_data['project_name']; ?> 
									</option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
						
			<div class="form-row">
                <div class="col-md-2">Our Inward No</div>
                <div class="col-md-4"><input type="text" readonly="true" name="out_inward_no" value="<?php echo $out_inward_no; ?>" class="form-control inward_no validate[required]"/></div>           
                    <div class="col-md-2">Inward Date*</div>
                	    <div class="col-md-4"><input type="text" name="inward_date" id="as_on_date" value="<?php echo $inward_date; ?>" class="form-control validate[required]"/></div>
                    </div>
						
            <div class="form-row">
                <div class="col-md-2">Their Ref. No *</div>
                    <div class="col-md-4">
						<input type="text" name="reference_no" value="<?php echo $reference_no; ?>" id="reference_no" class="form-control validate[required]" />
					</div>            
                    <div class="col-md-2">Their Date *</div>
                    <div class="col-md-4">
						<input type="text" name="date" value="<?php echo $date; ?>" id = "date_of_birth" class="form-control validate[required]"/>
					</div>
                </div>						
			<div class="form-row">
				<div class="col-md-2">Type of Agency *</div>
                	<div class="col-md-4">
						<select name="agency_client_name" class="select2" required="true"  style="width:100%;">
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
							<option value="<?php echo $client_key ;?>"
								<?php 
									if(isset($update_inward)){
										if($client_key == $update_inward['agency_client_name']){
											echo 'selected="selected"';
										}
									}
								?> 
							>
								<?php echo $client_value; ?>
							</option>
								<?php 
									}
								?>
						</select>
					</div>
				</div>
			<div class="form-row">						
                <div class="col-md-2">Agency Name *</div>
                <div class="col-md-4">
					<!-- <input type="text" name="agency_name" value="<?php echo $agency_name; ?>" id = "" class="form-control validate[required]" /> -->
					<select name="agency_name"  style="width: 100%;" class="select2" required="true"  id="agency_name">
					 	<option value="">--Select Agency Name--</option>
						 	<?php 
						 		foreach($result as $retrive_data) {
									echo '<option value="'.$retrive_data['cat_id'].'" '.$this->ERPfunction->selected($retrive_data['cat_id'],$agency_name).'>'.$retrive_data['category_title'].'</option>';
								}
							?>
					</select>
				</div>
				<div class="col-md-1">
					<button type="button" id="material_group" data-type="groups" data-toggle="modal" 
						data-target="#load_modal" class="btn btn-default add_group" style="">Add More </button>							
				</div>
            </div>
			<div class="form-row">
                <div class="col-md-2">Written By *</div>
                <div class="col-md-4">
					<!-- <input type="text" name="written_by" value="<?php echo $written_by; ?>" class="form-control validate[required]"/> !-->
					<select name="written_by"  style="width: 100%;" class="select2" required="true" id="written_by">
						<option value="">--Select Written By--</option>
				 		<?php
								foreach($writtenby as $retrive_data) {
									echo '<option value="'.$retrive_data['cat_id'].'" '.$this->ERPfunction->selected($retrive_data['cat_id'],$written_by).'>'.$retrive_data['category_title'].'</option>';
								}
							?>
				 	</select>
				</div>
				<div class="col-md-1">
					<button type="button" id="written_by" data-type="writtenby" data-toggle="modal" 
						data-target="#load_modal1" class="btn btn-default add_writtenby" style="">Add More </button>							
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Designation *</div>
                <div class="col-md-4">
								<!-- <input type="text" name="designation" id="" value="<?php echo $designation; ?>" class="form-control validate[required]"/> -->
					<select name="designation"  style="width: 100%;" class="select2" required="true" id="designation">
					 	<option value="">--Select Designation Name--</option>
							<?php 
						 		foreach($inward_designation as $retrive_data) {
									echo '<option value="'.$retrive_data['cat_id'].'" '.$this->ERPfunction->selected($retrive_data['cat_id'],$designation).'>'.$retrive_data['category_title'].'</option>';
								}
							?>
					</select>
				</div>	
				<div class="col-md-1">
					<button type="button" id="designation" data-type="designation" data-toggle="modal" 
						data-target="#load_modal2" class="btn btn-default add_designation" style="">Add More </button>							
				</div>						
            </div>
							
			<div class="form-row">
                <div class="col-md-2">Subject *</div>
                <div class="col-md-10"><input type="text" name="subject" value="<?php echo $subject; ?>" class="form-control  validate[required]"/></div>                        
            </div>					
			<div class="form-row">
                <div class="col-md-2">Enclosures</div>
                    <div class="col-md-10">
						<input type="text" name="enclosures" value="<?php echo $enclosures; ?>" class="form-control"/>
					</div>                        
                </div>
						
			<div class="form-row">
                <div class="col-md-2">Comment Box</div>
                    <div class="col-md-4">
						<textarea name="comment" class="form-control"><?php echo $comment; ?></textarea>
					</div>
                </div>						
					<!--	
						<div class="form-row">
							<div class="col-md-2">Attach Document</div>
                            <div class="col-md-4">
                            	<input type="hidden" value="<?php echo $image_old; ?>" name="old_image">
								<input type="file" name="image_url[]" class="form-control"/>
							</div>
							<div class="col-md-2">
								<a href="javascript:void(0);" class="create_field btn btn-default">Add Attachment</a>
							</div>
						</div>	-->			
						
			<div class="form-row">							
                <div class="col-md-2"> Attach Documents</div>
                    <div class="col-md-4">
						<input class="add_label form-control">
					</div>
					<div class="col-md-1">
						<a href="javascript:void(0)" class="create_field form-control">+&nbsp;Add</a>
					</div>
				</div>
				<div class="form-row add_field">
					<?php 
						if($user_action == "edit") {
							$attached_files = json_decode($update_inward["attachment"]);
							$attached_label = json_decode(stripcslashes($update_inward['attach_label']));
													
							if(!empty($attached_files)) {							
								$i = 0;
								foreach($attached_files as $file) {
					?>
					<div class='del_parent'>
						<div class='form-row'>
							<div class='col-md-2'>
								<?php echo $attached_label[$i];?>
								<input type='hidden' name='attach_label[]' value='<?php echo $attached_label[$i];?>' class='form-control'>
							</div>
							<div class='col-md-4'><a href="<?php echo $this->ERPfunction->get_signed_url($file)?>" class="btn btn-primary" target="_blank">View File</a>	
							<input type='hidden' name='old_image_url[]' value='<?php echo $file;?>' class='form-control'></div>
								<div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div>
								</div>
							</div>							
							<?php
										$i++;
										}
									}
								}
							?>
						</div>						
			<div class="form-row">
                <div class="col-md-2"></div>
                    <div class="col-md-4"><button type="submit" class="btn btn-primary" onclick="return ValidateExtension()"><?php echo $button_text;?></button></div>
                </div>
			
				<?php $this->Form->end(); ?>
				<div class="row" style="font-style:italic;color:gray;">							
					<div class="col-md-6 pull-right">
						<div class="col-md-4">
							<?php echo "Created By:{$created_by}"; ?>
						</div>
						<div class="col-md-4">
							<?php echo "Last Edited On:{$last_edit}"; ?>
						</div>
						<div class="col-md-4">
							<?php echo "Last Edited By:{$last_edit_by}"; ?>
						</div> 
					</div>
				</div>
			  </div>
			</div>
			<?php } ?>
        </div>
<script>
	$(".create_field").click(function(){
		var label = $(".add_label").val();
		$(".add_label").val("");
		var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='image_url[]' class='imageUpload'><span class='required red notice'></span></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
		$(".add_field").append(field);
	});

	$("body").on("click",".del_file",function(){
		$(this).parentsUntil('.del_parent').remove();
	});
	
function ValidateExtension(){
		m=0;
		$('.imageUpload').each(function(){
			if($(this).val() != '') {
				var imageUpload=$(this).val();
				var allowedFiles = ["jpeg","jpg","png","pdf","csv"];
				
				var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
				if (!regex.test(imageUpload.toLowerCase())) {
					$(this).siblings('.notice').html("<?php echo $this->request->session()->read('image_validation'); ?>");
					m++;
				}
				else{
					$(this).siblings('.notice').html(" ");
				}
			}
		});
			if(m>0){
			return false;
			}
        }
</script>