<?php
	use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

	jQuery(document).ready(function() {
		jQuery('#user_form').validationEngine();
		jQuery('#wo_date').datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "-" + year);
			}                    
		});

		// Arrow Function for append enable project list
		getEnableProject = () => {
			var project_id = $("#project_id").val();
			var enableDescription = $("#work_description").val();
			var curr_data = {
				project_id : project_id,
				enableDescription : enableDescription
			}
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type : "POST",
				url : "<?php echo Router::url(array('controller' => 'Ajaxfunction' , 'action' => 'enableprojectappend')); ?>",
				aync : true,
				data : curr_data,
				success : function(response) {
					var desc = JSON.parse(response);
					jQuery("select.material_name").append(desc);
					jQuery(".select .material_name").val(response);
					jQuery("#load_modal_enable_description").modal("hide");
				},
				error : function(e) {
					console.log(e);
				}
			});
		}

		// Get Description list modal onclick of enable description
		jQuery("body").on("click",".enable_description",function(event) {
			var project_id = $("#project_id").val();
			var type = $(".enable_description").attr('data-type');
			if(!project_id) {
				alert('Please select project first');return false;
			}
			var curr_data = {
				type : type,
				project_id : project_id
			}
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url  : "<?php echo Router::url(array('controller'=> 'Ajaxfunction','action'=>'getworkdescriptionlist')); ?>",
				data: curr_data,
				async:true,
				success : function(response) {
					jQuery('.modal-content').html('');
					jQuery('#load_modal_enable_description .modal-content').html(response);
					jQuery('#load_modal_enable_description').modal('show');
					jQuery("#work_description").select2();
				},
				error : function(e) {
					console.log(e);
				}
			});
		});

		jQuery("body").on("click","#btn-enable-description",function(event) {
			var enableDescription = $("#work_description").val();
			var project_id = $("#project_id").val();
			var curr_data = {
				enableDescription : enableDescription,
				project_id : project_id
			}
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
				type : "POST",
				url : "<?php echo Router::url(array('controller' => 'Ajaxfunction','action' => 'enableworkdescriptioninproject')) ?>",
				data : curr_data,
				async : false,
				success : function (response) {
					getEnableProject(); //Append Work Description called for add option in WO Descritption
				},
				error : function(e) {
					console.log(e);
				}
			});
		});

		// For calling CategoryList create arrow function
		getCategoryList = () => {
			var project_id = $("#project_id").val();
			if(!project_id) {
				alert('Please select project first');return false;
			}
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html(''); 
			// var type = $(".add_option").attr('data-type');
			var type = $(".enable_description").attr("data-type");
			var curr_data = {					
				project_id : project_id,				
				type : type				
			};
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'categorylist'));?>",
				data:curr_data,
				async:false,
				success: function(response){ 
					jQuery("#load_modal_enable_description").modal("hide");                   
					jQuery('#load_modal .modal-content').html(response);
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error: function(e) {
					console.log(e);
				}
			});			
		}

		// getCategorylist function called
		jQuery("body").on("click",".add_option",function(event){
			getCategoryList();
		});

		// Get WorkSubGroup dropdown data
		jQuery("body").on("change", "#material_code", function(event){	
			var material_code  = jQuery(this).val();
			var curr_data = {material_code : material_code};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getworksubgroup'));?>",
				data:curr_data,
				async:false,
				success: function(response){                    
					jQuery('#work_subgroup').html(response);
					jQuery('.select2').select2();
				},
				error: function(e) {
					console.log(e);
				}
			});
		});

		// Categorylist ajaxfile add workgroup btn code
		jQuery("body").on("click", ".add_workgroup", function(){
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html(''); 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getworkgroup'));?>",
				async:false,
				success: function(response){               
					jQuery('#load_modal_workgroup .modal-content').html(response);
					jQuery('.select2').select2();
					// $("#work_group").modal('hide');   
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error: function(e) { 
					console.log(e);
				}
			});			
		});

		// Close of .add_workgroup modal event
		$("#load_modal_workgroup").on("hidden.bs.modal",function(){	
			getCategoryList();	
			// alert("Hello World");return false;
		});

		// Add work group data from addworkgroup file
		jQuery("body").on("click","#btn-add-group",function() {
			var work_group  = jQuery('#work_group').val() ;
			var model  = jQuery(this).attr('model');
			if(work_group != "") {
				var curr_data = {
					work_group: work_group				
				};		 
				jQuery.ajax({
					headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addworkgroup'));?>",
					data:curr_data,
					async:false,
					success: function(response){
						var json_obj = jQuery.parseJSON(response);	
						jQuery(".table.table.table-bordered.table-striped.table-hover").addClass("categoryList");				
						jQuery('.categoryList').append(json_obj['row']);					
						jQuery('#work_group').val("");						
						jQuery("#material_code").append(json_obj['options']);	
						jQuery('.select2').select2();
						return false;		
					},
					error: function (tab) {
						alert('error');
					}
				});
			}else {
				alert("Please fill all the fields.");
			}
		});

		// Edit Work-group Item
		jQuery("body").on("click", ".btn-edit-workgroup", function(event){
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
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editworkgroup'));?>",
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

		// Edit cancel work-group item 
		jQuery("body").on("click", ".btn-workgroup-update-cancel", function(event){
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
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'cancelworkgroupsave'));?>",
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

		// Edit update work-group item
		jQuery("body").on("click", ".btn-workgroup-update", function(event){
			event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			var docHeight = jQuery(document).height(); //grab the height of the page
			var scrollTop = jQuery(window).scrollTop();
			var workGroupId  = jQuery(this).attr('id') ;
			// var group_code  = jQuery('#cat-'+group_id+' #group_code').val();
			var workGroupTitle  = jQuery('#cat-'+workGroupId+' #work-group').val();
			var curr_data = {	 						 					
				workGroupId : workGroupId,
				// group_code : group_code,
				workGroupTitle : workGroupTitle,
			};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateworkgroupitem'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					jQuery('tr#cat-'+workGroupId).html(response);
					$('#material_code option[value="'+workGroupId+'"]').detach();
					var newOption = new Option(workGroupTitle, workGroupId, false, false);
					$('#material_code').append(newOption).trigger('change');
				},
				error: function (tab) {
					alert('error');
				}
			});
		});
	
		// Categorylist ajaxfile get SubWorkGroup btn code
		jQuery("body").on("click", ".add_subworkgroup", function(){
			var material_code  = jQuery('#material_code').val();
			if(material_code != '') {
				jQuery('#modal-view').html('hello');
				jQuery('.modal-content').html(''); 
				var curr_data = {
					material_code : material_code
				}			
				jQuery.ajax({
					headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
					data:curr_data,
					url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getsubworkgroup'));?>",
					async:false,
					success: function(response){               
						jQuery('#load_modal_worksubgroup .modal-content').html(response);
						jQuery('.select2').select2();
						// $("#work_group").modal('hide');   
					},
					beforeSend:function(){
						jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
					},
					error: function(e) { 
						console.log(e);
					}
				});		
			}else {
				alert("Please selct work group");return false;
			}	
		});

		// Add work group data from addworksubgroup file
		jQuery("body").on("click","#btn-add-worksubgroup",function() {
			var material_code  = jQuery('#material_code').val();
			var workSubGroup  = jQuery('#work_subgroup').val() ;
			var model  = jQuery(this).attr('model');
			if(workSubGroup != "") {
				var curr_data = {
					material_code : material_code,
					workSubGroup: workSubGroup				
				};		 
				jQuery.ajax({
					headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addworksubgroup'));?>",
					data:curr_data,
					async:false,
					success: function(response){
						var json_obj = jQuery.parseJSON(response);	
						jQuery(".table.table.table-bordered.table-striped.table-hover").addClass("workSubGroup");				
						jQuery('.workSubGroup').append(json_obj['row']);					
						jQuery('#work_subgroup').val("");						
						jQuery("#material_code").append(json_obj['options']);	
						jQuery('.select2').select2();
						return false;		
					},
					error: function (tab) {
						alert('error');
					}
				});
			}else {
				alert("Please fill all the fields.");return false;
			}
		});

		// Edit WorkSubGroup Item
		jQuery("body").on("click", ".btn-edit-worksubgroup", function(event){
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
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editworksubgroup'));?>",
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

		// Edit cancel work-group item 
		jQuery("body").on("click", ".btn-worksubgroup-update-cancel", function(event){
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
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'cancelworksubgroupsave'));?>",
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

		// Edit update work-group item
		jQuery("body").on("click", ".btn-worksubgroup-update", function(event){
			event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			var docHeight = jQuery(document).height(); //grab the height of the page
			var scrollTop = jQuery(window).scrollTop();
			var workGroupId  = jQuery(this).attr('id') ;
			var workGroupTitle  = jQuery('#cat-'+workGroupId+' #work_subgroup').val();
			// alert(workgroupTitle);return false;
			var curr_data = {	 						 					
				workGroupId : workGroupId,
				workGroupTitle : workGroupTitle
			};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateworksubgroupitem'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					jQuery('tr#cat-'+workGroupId).html(response);
					$('#material_code option[value="'+workGroupId+'"]').detach();
					var newOption = new Option(workGroupTitle, workGroupId, false, false);
					$('#material_code').append(newOption).trigger('change');
				},
				error: function (tab) {
					alert('error');
				}
			});
		});

		// Close of .add_workgroup modal event
		$("#load_modal_worksubgroup").on("hidden.bs.modal",function(){	
			getCategoryList();	
			// alert("Hello World");return false;
		});

		jQuery("body").on("change", "#project_id", function(event){
			var project_id = $(this).val();
			var curr_data = {	 						 					
				project_id : project_id	 					
			};
			jQuery("select.material_name").html('');
			$(".material_name").select2("val", "");						
			$.ajax({
				async:true,
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getprojectwisesubcontractdescription'));?>",
				data:curr_data,
				success: function(response){
					var desc = JSON.parse(response);
					jQuery("select.material_name").append(desc);	
                },
                error: function (tab) {
                    alert('error');
                }
            });
		});
	
		// jQuery("body").on("change", "#project_id", function(event){
		// 	var project_id = $(this).val();
		// 	// var curr_data = {	 						 					
		// 	// 				project_id : project_id,	 					
		// 	// 				};
		// 	jQuery("select.work_head").html('');
		// 	$(".work_head").select2("val", "");						
		// 	jQuery.ajax({
		// 			headers: {
			// 	'X-CSRF-Token': csrfToken
			// },
            //     type:"POST",
		// 			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'planningwoworktype'));?>",
		// 			// data:curr_data,
		// 			async:false,
		// 			success: function(response){
		// 					var desc = JSON.parse(response);
		// 					jQuery("select.work_head").append(desc);	
		// 			},
		// 			error: function (tab) {
		// 				alert('error');
		// 			}
		// 		});
		// });
	
		jQuery("body").on("click", "#btn-add-category", function(){		
			var category_name  = jQuery('#category_name').val() ;
			var unit  = jQuery('#subc_description_unit').val() ;
			var subc_project_id  = parseInt(jQuery('#subc_project_id').val());
			var model  = jQuery(this).attr('model');	
			var workGroup = jQuery("#material_code").val();
			var workSubGroup = jQuery("#work_subgroup").val();
			/* alert(category_name + ' ' + model);
			return false; */
			if(workGroup != "" && workSubGroup != "" && category_name != "" && unit != "") {
				var curr_data = {					
					model : model,
					category_name: category_name,
					workGroup : workGroup,
					workSubGroup : workSubGroup,				
					description_unit: unit,				
					subc_project_id: subc_project_id				
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
						var json_obj = jQuery.parseJSON(response);					
						jQuery('.modal .table').append(json_obj[0]);
						jQuery('#category_name').val("");						
						jQuery('#subc_description_unit').val("");					
						jQuery("select.material_name").append(json_obj[1]);
						$("#load_modal").modal('hide');
						return false;		
					},
					error: function (tab) {
						alert('error');
					}
				});
			}else {
				alert("Please fill all the fields.");
			}
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
						jQuery("select.material_name option[value='"+cat_id+"']").remove();
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
			var unit  = jQuery('#unit').val();
			
			var curr_data = {	 						 					
				cat_id : cat_id,
				cat_name : cat_name,
				unit : unit,
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
	  
		$(".create_field").click(function(){
			// var label = $(".add_label").val();
			// if(label == "")
			// {
				// alert("Please enter file name.");
				// return false;
			// }
			// $(".add_label").val("");
			var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'></div><div class='col-md-4'><input type='file' name='attach_file[]' class='input-file'></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
			$(".add_field").append(field);
		});
			
		$("body").on("click",".del_file",function(){
			$(this).parentsUntil('.del_parent').remove();
		});
			
		jQuery("body").on("change", ".input-file[type=file]", function () {
			var file = this.files[0];
			var ext = $(this).val().split('.').pop().toLowerCase();
			//Extension Check
			if($.inArray(ext, ['pdf']) == -1) {
				alert('invalid extension! , '+ext+' file not allowed');
				$(this).replaceWith('<input type="file" name="attach_file[]" class="input-file">');
				return false;
			}
			//File Size Check
			if (file.size > 20480000) {
				alert("Too large file Size. Only file smaller than 10MB can be uploaded.");
				$(this).replaceWith('<input type="file" name="drawing[attach_file][]" class="validate[required] input-file" id="'+file_id+'" />');
				return false;
			}
		});
			
		jQuery('.brand_add').click(function(){
			var model  = jQuery(this).attr('data-type') ;
			var curr_data = {type : model};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addmorebrand'));?>",
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
	
		jQuery('#add_workhead').click(function(event){
			// var project_id = $("#project_id").val();
			// if(project_id == ''){
			// 	alert("Please select project first");
			// 	return false;
			// }
			
			var project_id = "ALL";

			jQuery('#modal-view').html('hello');
			var type  = jQuery("#add_workhead").attr('data-type');
			var curr_data = {
				type : type,
				project_id : project_id
			};	 				
			$.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'planningworkhead'));?>",
				data:curr_data,
				async:true,
				success: function(response){
					jQuery('.add_workhead').html(response);
					jQuery('#contract_workhead').select2();
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error: function(e) {
					console.log(e);
				}
			});			
		});
			
		jQuery("body").on("change", ".material_id", function(event){ 
			var row_id = jQuery(this).attr('data-id');
			var material_id  = jQuery(this).val() ;
			/* alert(material_id);
			return false;  */  
			var curr_data = {	 						 					
				material_id : material_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialbrandlist'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);
					jQuery('#brand_id_'+row_id).html();
					jQuery('#brand_id_'+row_id).html(json_obj['itemlist']);
					jQuery('#brand_id_'+row_id).select2();
					jQuery('#unit_name_'+row_id).html();
					jQuery('#unit_name_'+row_id).html(json_obj['unit_name']);
					jQuery('#material_code_'+row_id).html();
					jQuery('#material_code_'+row_id).html(json_obj['material_code']);					
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});
		
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
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inwoprojectdetail'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					//jQuery('#wo_no').val(json_obj['wo_no']);
					jQuery('.project_address').val(json_obj['project_address'] + "," + json_obj['project_address_2']);
					return false;
				},
				error: function (e) {
					alert('Error');
					console.log(e.responseText);
				}
			});	
		});
		
		jQuery("body").on("change", "#party_id", function(event){
			var party_type = jQuery("#party_id option:selected").attr('dataid');
			var party_id  = jQuery(this).val();
			var curr_data = {	 						 					
				party_id : party_id,party_type : party_type	 					
			};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'vendoragencydetail'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#party_identy').val(json_obj['party_id']);						
					jQuery('#party_address').val(json_obj['delivery_place']);												
					jQuery('#party_no1').val(json_obj['contact_no1']);												
					jQuery('#party_no2').val(json_obj['contact_no2']);												
					jQuery('#party_email').val(json_obj['email_id']);												
					jQuery('#party_pan_no').val(json_obj['pancard_no']);												
					jQuery('#party_gst_no').val(json_obj['gst_no']);												
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});	
		});

		
	
		// Check GST status
		$("body").on("change",".bill_mode, #party_id",function(){
			checkGstStatus();
		});
	
		function checkGstStatus() {
			var party_gst_no = $("#party_gst_no").val();
			var yashnand_gst_no = $("#gstno").val();
			if(party_gst_no != "" && yashnand_gst_no != "") {
				var party_two_digit = party_gst_no.substr(0, 2);
				var yashnand_two_digit = yashnand_gst_no.substr(0, 2);
				if(jQuery.isNumeric(party_two_digit)) {
					if(party_two_digit == yashnand_two_digit) {
						$(".igst_percentage").val(0);
						$(".igst").html(0);
						$(".igst").val(0);
						
						$("#cgst_row").show();
						$("#sgst_row").show();
						$("#igst_row").hide();
						$("#gross_amount_row").show();
					}else {
						$(".cgst_percentage").val(0);
						$(".cgst").html(0);
						$(".cgst").val(0);
					
						$(".sgst_percentage").val(0);
						$(".sgst").html(0);
						$(".sgst").val(0);
						
						$("#cgst_row").hide();
						$("#sgst_row").hide();
						$("#igst_row").show();
						$("#gross_amount_row").show();
					}
				}else {
					$(".cgst_percentage").val(0);
					$(".cgst").html(0);
					$(".cgst").val(0);
					
					$(".sgst_percentage").val(0);
					$(".sgst").html(0);
					$(".sgst").val(0);
					
					$(".igst_percentage").val(0);
					$(".igst").html(0);
					$(".igst").val(0);
					
					$(".gross_amount").html(0);
					$(".gross_amount").val(0);
					
					$("#cgst_row").hide();
					$("#sgst_row").hide();
					$("#igst_row").hide();
					$("#gross_amount_row").hide();
				}
			}else if(party_gst_no != "") {
				
			}else {
				$("#cgst_row").show();
				$("#sgst_row").show();
				$("#igst_row").show();
				$("#gross_amount_row").show();
			}
			
			countNetAmount();
		}

		// On row input change
		$("body").on("change",".quantity_this_wo,.unit_rate",function(){
			var row_id = $(this).attr("data-id");
			var this_value = $(this).val();
			if(jQuery.isNumeric(this_value)) {
				countSingleRowAmount(row_id);
			}else {
				alert("Please enter numeric value");
				return false;
			}
		});

		function countAllRowAmount() {
			$( ".row_number" ).each(function() {
				row_id = $( this ).val();
				var quantity_this_bill = $("#quantity_this_bill_"+row_id).val();
				var quantity_previous_bill = $("#quantity_previous_bill_"+row_id).val();
				if(jQuery.isNumeric(quantity_this_bill) && jQuery.isNumeric(quantity_previous_bill)) {
					var quantity_till_date = parseFloat(quantity_this_bill) + parseFloat(quantity_previous_bill);
					$("#quantity_till_date_"+row_id).val(quantity_till_date.toFixed(2));
				}
				var rate = $("#rate_"+row_id).val();
				if(jQuery.isNumeric(quantity_this_bill) && jQuery.isNumeric(rate)) {
					var amount_this_bill = parseFloat(quantity_this_bill) * parseFloat(rate);
					$("#amount_this_bill_"+row_id).val(amount_this_bill.toFixed(2));
				}
				var amount_this_bill = $("#amount_this_bill_"+row_id).val();
				var amount_previous_bill = $("#amount_previous_bill_"+row_id).val();
				
				if(jQuery.isNumeric(amount_this_bill) && jQuery.isNumeric(amount_previous_bill)) {
					var amount_till_date = parseFloat(amount_this_bill) + parseFloat(amount_previous_bill);
					$("#amount_till_date_"+row_id).val(amount_till_date.toFixed(2));
				}
			});
			countNetAmount()
		}
		/* Calculation for all row of table */

		// On GST input change
		$("body").on("change",".cgst_percentage, .sgst_percentage, .igst_percentage",function(){
			var this_value = $(this).val();
			countNetAmount();
		});

		/* Calculation for single row of table */
		function countSingleRowAmount(row_id) {
			var quantity_this_wo = $("#quantity_this_wo_"+row_id).val();
			var quantity_previous_wo = $("#quantity_previous_wo_"+row_id).val();
			if(jQuery.isNumeric(quantity_this_wo) && jQuery.isNumeric(quantity_previous_wo)) {
				var quantity_till_date = parseFloat(quantity_this_wo) + parseFloat(quantity_previous_wo);
				$("#quantity_till_date_"+row_id).val(quantity_till_date.toFixed(2));
			}
			var rate = $("#unit_rate_"+row_id).val();
			var quantity_this_wo = $("#quantity_this_wo_"+row_id).val();
			if(jQuery.isNumeric(quantity_this_wo) && jQuery.isNumeric(rate)) {
				var amount_this_wo = parseFloat(quantity_this_wo) * parseFloat(rate);
				$("#amount_"+row_id).val(amount_this_wo.toFixed(2));
			}
			var quantity_till_date = $("#quantity_till_date_"+row_id).val();
			if(jQuery.isNumeric(quantity_till_date) && jQuery.isNumeric(rate)) {
			var amount_till_date = parseFloat(quantity_till_date) * parseFloat(rate);
				$("#amount_till_date_"+row_id).val(amount_till_date.toFixed(2));
			}
			// if(jQuery.isNumeric(amount_till_date) && jQuery.isNumeric(amount_previous_bill))
			// {
			// 	var amount_this_bill = parseFloat(amount_till_date) - parseFloat(amount_previous_bill);
			// 	$("#amount_this_bill_"+row_id).val(amount_this_bill.toFixed(2));
			// }
			countNetAmount() 
		}
	
		/* Calculation for all row of table */
		function countAllRowAmount() {
			$( ".row_number" ).each(function() {
				row_id = $( this ).val();
				var quantity_this_wo = $("#quantity_this_wo_"+row_id).val();
				var quantity_previous_wo = $("#quantity_previous_wo_"+row_id).val();
				if(jQuery.isNumeric(quantity_this_wo) && jQuery.isNumeric(quantity_previous_wo)) {
					var quantity_till_date = parseFloat(quantity_this_wo) + parseFloat(quantity_previous_wo);
					$("#quantity_till_date_"+row_id).val(quantity_till_date.toFixed(2));
				}
				var rate = $("#unit_rate_"+row_id).val();
				var quantity_this_wo = $("#quantity_this_wo_"+row_id).val();
				if(jQuery.isNumeric(quantity_this_wo) && jQuery.isNumeric(rate)) {
					var amount_this_wo = parseFloat(quantity_this_wo) * parseFloat(rate);
					$("#amount_"+row_id).val(amount_this_wo.toFixed(2));
				}
				var quantity_till_date = $("#quantity_till_date_"+row_id).val();
				if(jQuery.isNumeric(quantity_till_date) && jQuery.isNumeric(rate)) {
				var amount_till_date = parseFloat(quantity_till_date) * parseFloat(rate);
					$("#amount_till_date_"+row_id).val(amount_till_date.toFixed(2));
				}
			});
			countNetAmount()
		}

		/* Calculation for net amount of table */
		function countNetAmount() {
			//Count sub total
			var sub_total = 0;
			$( ".amount" ).each(function() {
				var this_value = $(this).val();
				if(jQuery.isNumeric(this_value)) {
					sub_total += parseFloat(this_value);
				}
			});
			$(".sub_total").val(sub_total);
			$("#total_wo_amount").html(sub_total);
			// Amount till date count
			var sub_total_till_date = 0;
			$( ".amount_till_date" ).each(function() {
				var this_value = $(this).val();
				if(jQuery.isNumeric(this_value)) {
					sub_total_till_date += parseFloat(this_value);
				}
			});
			$(".sub_total_till_date").val(sub_total_till_date);
			$("#sub_total_till_date").html(sub_total_till_date);
			// CGST Count
			var cgst_percentage = $(".cgst_percentage").val();
			if(jQuery.isNumeric(sub_total) && jQuery.isNumeric(cgst_percentage) && cgst_percentage != "") {
				var cgst_amount = (parseFloat(sub_total) * parseFloat(cgst_percentage)) / 100;
				$(".cgst").val(cgst_amount.toFixed(2));
				$(".cgst").html(cgst_amount.toFixed(2));
			}else {
				$(".cgst").val(0.00);
				$(".cgst").html(0.00);
			}
			// SGST Count
			var sgst_percentage = $(".sgst_percentage").val();
			if(jQuery.isNumeric(sub_total) && jQuery.isNumeric(sgst_percentage) && sgst_percentage != "") {
				var sgst_amount = (parseFloat(sub_total) * parseFloat(sgst_percentage)) / 100;
				$(".sgst").val(sgst_amount.toFixed(2));
				$(".sgst").html(sgst_amount.toFixed(2));
			}else {
				$(".sgst").val(0.00);
				$(".sgst").html(0.00);
			}
			// IGST Count
			var igst_percentage = $(".igst_percentage").val();
			if(jQuery.isNumeric(sub_total) && jQuery.isNumeric(igst_percentage) && igst_percentage != "") {
				var igst_amount = (parseFloat(sub_total) * parseFloat(igst_percentage)) / 100;
				$(".igst").val(igst_amount.toFixed(2));
				$(".igst").html(igst_amount.toFixed(2));
			}else {
				$(".igst").val(0.00);
				$(".igst").html(0.00);
			}
			// Till date CGST Count
			var cgst_percentage = $(".cgst_percentage").val();
			if(jQuery.isNumeric(sub_total_till_date) && jQuery.isNumeric(cgst_percentage) && cgst_percentage != "") {
				var cgst_amount = (parseFloat(sub_total_till_date) * parseFloat(cgst_percentage)) / 100;
				$("#cgst_till_date").val(cgst_amount.toFixed(2));
				$(".cgst_till_date").html(cgst_amount.toFixed(2));
			}else {
				$("#cgst_till_date").val(0.00);
				$(".cgst_till_date").html(0.00);
			}
			
			// Till date SGST Count
			var sgst_percentage = $(".sgst_percentage").val();
			if(jQuery.isNumeric(sub_total_till_date) && jQuery.isNumeric(sgst_percentage) && sgst_percentage != "") {
				var sgst_amount = (parseFloat(sub_total_till_date) * parseFloat(sgst_percentage)) / 100;
				$("#sgst_till_date").val(sgst_amount.toFixed(2));
				$(".sgst_till_date").html(sgst_amount.toFixed(2));
			}else {
				$("#sgst_till_date").val(0.00);
				$(".sgst_till_date").html(0.00);
			}
			// Till date IGST Count
			var igst_percentage = $(".igst_percentage").val();
			if(jQuery.isNumeric(sub_total_till_date) && jQuery.isNumeric(igst_percentage) && igst_percentage != "") {
				var igst_amount = (parseFloat(sub_total_till_date) * parseFloat(igst_percentage)) / 100;
				$("#igst_till_date").val(igst_amount.toFixed(2));
				$(".igst_till_date").html(igst_amount.toFixed(2));
			}else {
				$("#igst_till_date").val(0.00);
				$(".igst_till_date").html(0.00);
			}
			//Gross Amount Count
			var this_bill_val = sub_total;
			var cgst_val = $(".cgst").val();
			var sgst_val = $(".sgst").val();
			var igst_val = $(".igst").val();
			var gross_amount = parseFloat(this_bill_val);
			// if(jQuery.isNumeric(this_bill_val) && jQuery.isNumeric(cgst_val) && jQuery.isNumeric(sgst_val) && jQuery.isNumeric(igst_val))
			// {
			// 	var gross_amount = parseFloat(this_bill_val) + parseFloat(cgst_val) + parseFloat(sgst_val) + parseFloat(igst_val);
			// }
			if(jQuery.isNumeric(cgst_val)) {
				gross_amount += parseFloat(cgst_val);
			}
			if(jQuery.isNumeric(sgst_val)) {
				gross_amount += parseFloat(sgst_val);
			}
			if(jQuery.isNumeric(igst_val)) {
				gross_amount += parseFloat(igst_val);
			}
			$(".net_amount").val(gross_amount.toFixed(2));
			$("#net_amount").html(gross_amount.toFixed(2));
			// TIll date Gross Amount Count
			var till_date_cgst_val = $("#cgst_till_date").val();
			var till_date_sgst_val = $("#sgst_till_date").val();
			var till_date_igst_val = $("#igst_till_date").val();
			var gross_amount_till_date = parseFloat(sub_total_till_date);
			// if(jQuery.isNumeric(this_bill_val) && jQuery.isNumeric(cgst_val) && jQuery.isNumeric(sgst_val) && jQuery.isNumeric(igst_val))
			// {
			// 	var gross_amount = parseFloat(this_bill_val) + parseFloat(cgst_val) + parseFloat(sgst_val) + parseFloat(igst_val);
			// }
			if(jQuery.isNumeric(till_date_cgst_val)) {
				gross_amount_till_date += parseFloat(till_date_cgst_val);
			}
			if(jQuery.isNumeric(till_date_sgst_val)) {
				gross_amount_till_date += parseFloat(till_date_sgst_val);
			}
			if(jQuery.isNumeric(till_date_igst_val)) {
				gross_amount_till_date += parseFloat(till_date_igst_val);
			}
			$(".till_date_net_amount").val(gross_amount_till_date.toFixed(2));
			$("#till_date_net_amount").html(gross_amount_till_date.toFixed(2));
		}
		/* Calculation for net amount of table */

		jQuery("body").on("change", ".bill_mode", function(){
			var state  = jQuery(this).val() ; 
			var curr_data = {	 						 					
				state : state,	 					
			};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getstatepanno'));?>",
				data:curr_data,
				async:false,
				success: function(response){						
					jQuery('.pan_no').html(response);	
					return false;
				},
				error: function (e) {
					alert('Error');
					console.log(e.responseText);
				}
			});
		});
	
		jQuery("body").on("change", "#type_of_contract", function(){
			var type_id  = jQuery(this).val() ; 
			if(type_id == 1 || type_id == 3 || type_id == 4) {
				$("#remark_1").css("display","block");
				$("#remark_2").css("display","none");
			}
			else if(type_id == 5 || type_id == 6 || type_id == 7 || type_id == 2) {
				$("#remark_2").css("display","block");
				$("#remark_1").css("display","none"); 
			}else {
				$("#remark_1").css("display","block");
				$("#remark_2").css("display","none");
			}				
		});

		jQuery("#add_newrow").click(function(){
			var project_id = $("#project_id").val();
			if(!project_id) {
				alert('Please select project first');
				return false;
			}
			var row_len = jQuery(".row_number").length;
			if(row_len > 0) {
				var num = jQuery(".row_number:last").val();
				var row_id = parseInt(num) + 1;
			}else {
				var row_id = 0;
			}
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
				type: 'POST',
				url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowplanningwo"]);?>',
				data : {row_id:row_id,project_id:project_id},
				success: function (response) {	
					jQuery("tbody").append(response);
					jQuery('select.select2').select2();
					return false;
					jQuery('.target_date').datepicker({
						changeMonth: true,
					changeYear: true,
					dateFormat: "dd-mm-yy"
					});
					jQuery('#work_head_'+row_id).select2();
					return false;
				},
				error: function(e) {
					alert("An error occurred: " + e.responseText);
					console.log(e);
				}
			});
		});

		jQuery("body").on("change", ".material_name", function(event){
			var row = $(this).attr('data-id');
			var category_id = $(this).val();
			var curr_data = {	 						 					
				category_id : category_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getsubcontractdescriptionunit'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					jQuery("#unit_"+row).val(response);	
				},
				error: function (tab) {
					alert('error');
				}
			});
		});

		jQuery('.target_date').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd-mm-yy"
		});
	
		jQuery("body").on("change", ".material_id", function(event){ 
			var row_id = jQuery(this).attr('data-id');
			var material_id  = jQuery(this).val() ;  
			var curr_data = {	 						 					
				material_id : material_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialbrandlist'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);
					jQuery('#brand_id_'+row_id).html();
					jQuery('#brand_id_'+row_id).html(json_obj['itemlist']);
					jQuery('#brand_id_'+row_id).select2();
					jQuery('#unit_name_'+row_id).html();
					jQuery('#unit_name_'+row_id).html(json_obj['unit_name']);
					jQuery('#material_code_'+row_id).html();
					jQuery('#material_code_'+row_id).html(json_obj['material_code']);
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});
		});
   
		function count_total(row_id) {
			var qty = jQuery('#quantity_'+row_id).val();
			var price = jQuery('#unit_rate_'+row_id).val();
			var single_amount = price;
			var dc = parseFloat($("#dc_"+row_id).val());	
			if(dc != '') {			
				dc = parseFloat((100-dc)/100);
				single_amount = parseFloat(price * dc);
			}
			var cgst = parseFloat($("#cgst_"+row_id).val()); /* CGST */ 
			var sgst = parseFloat($("#sgst_"+row_id).val()); /* SGST */
			var igst = parseFloat($("#igst_"+row_id).val()); /* IGST */
			var total_gst = parseFloat(cgst + sgst + igst);
			if(total_gst > 0) {
				var gst_count = 1 + parseFloat(total_gst / 100);
				single_amount = parseFloat(single_amount * gst_count)
			}
			var new_amount = parseFloat(qty*single_amount);
			jQuery('#amount_'+row_id).val(new_amount.toFixed(2));
			var po_sum = 0;
			jQuery('.amount').each(function(){
				var single_po_amount = jQuery(this).val();
				po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
			});
			jQuery('#total_wo_amount').html();
			jQuery('#total_wo_amount').html(po_sum.toFixed(2));
		}

		jQuery('body').on('click','.trash',function(){
			var row_id = jQuery(this).attr('data-id');
			jQuery('table tr#row_id_'+row_id).remove();	
			return false;
		});
	
		jQuery("body").on("change",".tx_count",function(){
			var row_id = jQuery(this).attr('data-id');
			count_total(row_id);
		});

		$("body").on("click",".del_parent",function(){
			$(this).parents("tr").remove();
			countAllRowAmount();
		});
			
		jQuery("body").on("change",".othertax",function(){ 
			var sid = jQuery(this).attr("sid"); 
			if(sid == "other") {
				jQuery("#other_text").css("display","block");			
			}else {
				jQuery("#other_text").css("display","none");
			}
		});
	
		$("body").on("blur","#other_text",function(){
			var other_tx = $(this).val();
			$(".othertax").val(other_tx);
		});
	
		$("body").on("change","#loading",function(){
			var check = $(this).attr("checked");		
			if(check) {
				$("#show_loading").css("display","none");
			}else {
				$("#show_loading").css("display","block");
			}
		});
		
		jQuery("body").on("change", "#project_id", function(event){
			var project_id = $(this).val();
			var curr_data = {	 						 					
				project_id : project_id,	 					
			};			
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getprojectwisestate'));?>",
				data:curr_data,
				async:false,
				success: (response) => {
					if(response == "Gujarat") {
						$("#mp").attr('checked',false);
						$("#maharastra").attr('checked',false);
						$("#haryana").attr('checked',false);
						$("#gujarat").attr('checked',true);
						$("#gujarat").attr('disabled',false);
						$("#gujarat").closest("span").removeClass("disabled");
						$("#mp").attr('disabled',true);
						$("#maharastra").attr('disabled',true);
						$("#haryana").attr('disabled',true);
						$("#mp").closest("span").removeClass("checked");
						$("#maharastra").closest("span").removeClass("checked");
						$("#haryana").closest("span").removeClass("checked");
						$("#gujarat").closest("span").addClass("checked");
						$("#mp").closest("span").addClass("disabled");
						$("#haryana").closest("span").addClass("disabled");
						$("#maharastra").closest("span").addClass("disabled");
					}else if(response == "Madhya Pradesh") {
						$("#gujarat").attr('checked',false);
						$("#maharastra").attr('checked',false);
						$("#haryana").attr('checked',false);
						$("#mp").attr('checked',true);
						$("#mp").attr('disabled',false);
						$("#mp").closest("span").removeClass("disabled");
						$("#gujarat").attr('disabled',true);
						$("#maharastra").attr('disabled',true);
						$("#haryana").attr('disabled',true);
						$("#gujarat").closest("span").removeClass("checked");
						$("#maharastra").closest("span").removeClass("checked");
						$("#haryana").closest("span").removeClass("checked");
						$("#mp").closest("span").addClass("checked");
						$("#gujarat").closest("span").addClass("disabled");
						$("#haryana").closest("span").addClass("disabled");
						$("#maharastra").closest("span").addClass("disabled");
					}else if(response == "Maharashtra") {
						$("#gujarat").attr('checked',false);
						$("#mp").attr('checked',false);
						$("#haryana").attr('checked',false);
						$("#maharastra").attr('checked',true);
						$("#maharastra").attr('disabled',false);
						$("#maharastra").closest("span").removeClass("disabled");
						$("#gujarat").attr('disabled',true);
						$("#mp").attr('disabled',true);
						$("#haryana").attr('disabled',true);
						$("#gujarat").closest("span").removeClass("checked");
						$("#mp").closest("span").removeClass("checked");
						$("#haryana").closest("span").removeClass("checked");
						$("#maharastra").closest("span").addClass("checked");
						$("#gujarat").closest("span").addClass("disabled");
						$("#haryana").closest("span").addClass("disabled");
						$("#mp").closest("span").addClass("disabled");
					}else {
						$("#gujarat").attr('checked',false);
						$("#mp").attr('checked',false);
						$("#maharastra").attr('checked',false);
						$("#haryana").attr('checked',true);
						$("#haryana").attr('disabled',false);
						$("#haryana").closest("span").removeClass("disabled");
						$("#gujarat").attr('disabled',true);
						$("#mp").attr('disabled',true);
						$("#maharastra").attr('disabled',true);
						$("#gujarat").closest("span").removeClass("checked");
						$("#mp").closest("span").removeClass("checked");
						$("#maharastra").closest("span").removeClass("checked");
						$("#haryana").closest("span").addClass("checked");
						$("#gujarat").closest("span").addClass("disabled");
						$("#maharastra").closest("span").addClass("disabled");
						$("#mp").closest("span").addClass("disabled");
					}
				},
				error: function (e) {
					alert('Error');
				}
			});	
		});

		jQuery("body").on("click", "#save", function(event){
			var work_head_code  = jQuery("#work_head_code").val();
			var work_head_title  = jQuery(".work_head_title").val();
			var projectId  = jQuery("#project_id").val();
			var arr = work_head_code.split('/');
			var new_number = parseInt(arr[1]) + 1;
			var next_work_head_code = 'WH/'+new_number;
			if( work_head_code == '' || work_head_title == '' || projectId == '')
			{
				alert('Please fill all field');
				return false;
			}
			
			var curr_data = {
				work_head_code : work_head_code , work_head_title :work_head_title, project_id : projectId				
			};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addplanningworkhead'));?>",
				data:curr_data,
				async:false,
				success: function(response){	
					if(response == 'duplicate') {
						alert('Duplicate entry , Please try again.');
						jQuery('#type_of_contract').select2("val", "");
						jQuery("#work_head_code").val(work_head_code);
						jQuery("#work_head_title").val('');
					}else {
						jQuery('select.work_head').append(response);
						jQuery('#type_of_contract').select2("val", "");
						jQuery("#work_head_code").val(next_work_head_code);
						jQuery("#work_head_title").val('');
						jQuery("#load_modal_add_workhead").modal("hide");
					}
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});	
		});

		// Get Billing Address based on project state selected
		getBillingAddress = () => {
			var selectedValue = document.querySelector('input[name="bill_mode"]:checked');  
        	output = selectedValue.value;
			if(output == 'gujarat') {
				$("#gj_address").css("display","block");
				$("#mp_address").css("display","none");
				$("#mh_address").css("display","none");
				$("#haryana_address").css("display","none");
			}else if(output == 'mp') {
				$("#mp_address").css("display","block");
				$("#gj_address").css("display","none"); 
				$("#mh_address").css("display","none");
				$("#haryana_address").css("display","none");
			}else if(output == 'maharastra') {
				$("#gj_address").css("display","none");
				$("#mp_address").css("display","none");
				$("#haryana_address").css("display","none");
				$("#mh_address").css("display","block");
			}else if(output == 'haryana') {
				$("#gj_address").css("display","none");
				$("#mp_address").css("display","none");
				$("#haryana_address").css("display","block");
				$("#mh_address").css("display","none");
			}
			var curr_data = {	 						 					
				state : output,	 					
			};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getstategstno'));?>",
				data:curr_data,
				async:false,
				success: function(response){						
					jQuery('input#gstno').val(response);	
					return false;
				},
				error: function (e) {
					alert('Error');
					console.log(e.responseText);
				}
			});
		}

		jQuery("body").on("change","#project_id",function(event) {
			// Get Billingadddress function called
			getBillingAddress();

			// Get WorkDescriptio Option based on project selection
			var dataId = jQuery("#material_name_0").attr('data-id');
			var projectId = jQuery("#project_id").val();
			var curr_data = {
				projectId : projectId
			}
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
				type : "POST",
				url : "<?php echo Router::url(array("controller"=>"Ajaxfunction","action" => "getprojectbasedworkdescription")); ?>",
				data : curr_data,
				aync : true,
				success : function(response) {
					jQuery("#material_name_"+dataId).html(response);
					jQuery(".material_name .select2").select2();
				},
				error : function(e) {
					console.log(e);
				}
			});
		});

		// Get Selected Type Of Work Value
		// jQuery("body").on("change",".work_head",function(event){
		// 	var selectedValue = jQuery("#work_head_0").val();
		// 	var curr_data = {
		// 		selectedValue : selectedValue
		// 	};
		// 	jQuery.ajax({
		// 		type : "POST",
		// 		url : "<?php echo Router::url(array('controller'=>"Ajaxfunction","action"=>"appendtypeofcontract"));?>",
		// 		data : curr_data,
		// 		async : true,
		// 		success : function(response) {
		// 			jQuery('#type_of_contract').html(response);
		// 			jQuery('#type_of_contract .select2').select2();
		// 		},
		// 		error : function (e) {
		// 			console.log(e);
		// 		}
		// 	})
		// });

	});
</script>	
<!-- Load model Declare Here Start -->
<div class="modal fade " id="load_modal_workgroup" role="dialog" style="z-index:9999999999999;">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="load_modal_worksubgroup" role="dialog" style="z-index:9999999999999;">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<!-- Load model declare Here End -->
	<div class="modal fade " id="load_modal" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content"></div>
		</div>
	</div>
	
<!-- Enable work Description list modal start -->
<div class="modal fade 123 " id="load_modal_enable_description" role="dialog" style="z-index:9999;">
	<div class="modal-dialog modal-md">
		<div class="modal-content"></div>
	</div>
</div>
<!-- Enable Work Description list modal end -->

<!-- Add WorkHead Modal Start -->
<div class="modal fade " id="load_modal_add_workhead" role="dialog">
    <div class="modal-dialog modal-md">
    	<div class="modal-content add_workhead"></div>
    </div>
</div>
<!-- Add WorkHead Modal End -->

<div class="modal fade " id="brand_modal" role="dialog">
    <div class="modal-dialog modal-md">
    	<div class="modal-content"></div>
    </div>
</div>
	<div class="col-md-10" >
	<?php 
		if(!$is_capable) {
				$this->ERPfunction->access_deniedmsg();
		}else {
	?>		
<div class="col-md-12" >		
    <div class="block block-fill-white">
		<div class="head bg-default bg-light-rtl">
			<h2>Work Order</h2>
			<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Contract','planningmenu');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		<div class="content controls">	
			<div class="form-row">
				<div class="col-md-2">Mode of Billing: </div>
				<div class="col-md-10">
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" checked name="bill_mode" class="bill_mode" id="gujarat"  value="gujarat" /> Gujarat</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" name="bill_mode" value="mp" id="mp" class="bill_mode" />Madhya Pradesh</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" name="bill_mode" value="maharastra" id="maharastra" class="bill_mode" />Maharastra</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" name="bill_mode" value="haryana" id="haryana"  class="bill_mode" />Haryana</label>
					</div>
				</div>
			</div>		
			<div class="form-row">
				<div class="col-md-2" class="text-right">Project Code:<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input type="text" name="project_code" id="project_code" value="" class="form-control validate[required]" value="" readonly="true"/>
				</div>
				<div class="col-md-2">Project Name:</div>
				<div class="col-md-4">
					<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
					<option value="">--Select Project--</Option>
					<?php 
						foreach($projects as $retrive_data) {
							echo '<option value="'.$retrive_data['project_id'].'">'.
							$retrive_data['project_name'].'</option>';
						}
					?>
					</select>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Project Address:</div>
				<div class="col-md-10">
					<input type="text" name="project_address" id="project_address" class="form-control project_address" value=""/>
				</div>
			</div>
			<div class="form-row">
				<!--<div class="col-md-2">W.O.No:</div>
				<div class="col-md-4">
				
					<input type="text" name="wo_no" id="wo_no" value="" class="form-control" value=""/>
				</div>-->
				<div class="col-md-2">Date:</div>
				<div class="col-md-4">
					<input type="text" name="wo_date" id="wo_date" value="<?php echo $this->ERPfunction->get_date(date('Y-m-d'));?>" class="form-control" value=""/>
				</div>
				<div class="col-md-2">YashNand's GST No.:</div>
				<div class="col-md-4">
					<input readonly name="yashnand_gstno" id="gstno" class="gstno form-control" value="24AABCY0913A1Z1"/>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Party's Name:</div>
				<div class="col-md-4">
					<select class="select2"  required="true"   style="width: 100%;" name="party_id" id="party_id">
						<option value="">--Select Party--</Option>
						<?php
							if($vendor_info) {
								foreach($vendor_info as $vendor_row) {
						?>
						<option value="<?php echo $vendor_row['user_id']; ?>" dataid="vendor"
							<?php 
								if(isset($update_inward)){
									if($update_inward['party_name'] == $vendor_row['user_id']){
										echo 'selected="selected"';
									}
								}
							?> 
						><?php echo $vendor_row['vendor_name'];?></option>
						<?php
								}
							}
						?>
					</select>
				</div>
                        
				<div class="col-md-2">Party ID:</div>
				<div class="col-md-4">
					<input type="text" name="party_identy" id="party_identy" value="" class="form-control" value=""/>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Party Addresss:</div>
				<div class="col-md-10">
					<input type="text" name="party_address"  id="party_address" class="form-control" value=""/>
				</div>
			</div>	
			<div class="form-row">						
				<div class="col-md-2">Contact No: (1)</div>
				<div class="col-md-4">
					<input type="text" name="party_no1" id="party_no1" class="form-control" value=""/>
				</div>
				<div class="col-md-2">Contact No: (2)</div>
				<div class="col-md-4">
					<input type="text" name="party_no2" id="party_no2" value="" class="form-control"/>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Party E-Mail:</div>
				<div class="col-md-10">
					<input type="text" name="party_email"  id="party_email" class="form-control" value=""/>
				</div>
			</div> 
			<div class="form-row">						
				<div class="col-md-2">PAN Card No:</div>
				<div class="col-md-4">
					<input type="text" name="party_pan_no" id="party_pan_no" class="form-control"/>
				</div>
				<div class="col-md-2">GST No:</div>
				<div class="col-md-4">
					<input type="text" name="party_gst_no" id="party_gst_no" value="" class="form-control"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-2">Type of Contract:</div>
				<div class="col-md-4">
					<select class="select2"  required="true"   style="width: 100%;" name="type_of_contract" id="type_of_contract" >
						<option value="">--Select Contract--</Option>
						<?php 
							$contract_list = $this->ERPfunction->contract_type_list();
							foreach($contract_list as $retrive_data) {
								echo '<option value="'.$retrive_data['id'].'">'.
								$retrive_data['title'].'</option>';
							}
						?>
					</select>
				</div>
				<div class="col-md-2">Payment Method:</div>
				<div class="col-md-4">
					<select class="select2"  required="true"   style="width: 100%;" name="payment_method" id="payment_method" >
						<option value="">--Select Payment Method--</Option>
						<option value="cash">Cash</Option>
						<option value="cheque">Cheque</Option>							
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-2">Type of Work:</div>
				<div class="col-md-4">
					<select class="select2 work_head" required="true" style="width:100%;" name="work_type" id="work_head_0" data-id="0">
						<option value="">Select Work Type</Option>
						<?php 
							foreach($work_head_list as $retrive_data)
							{
								echo '<option value="'.$retrive_data['work_head_id'].'">'.
								$retrive_data['work_head_title'].'</option>';
							}
						?>
					</select>
				</div>
				<div class="col-md-1">
					<button type="button" id="add_workhead" data-type="workhead_add" data-toggle="modal" 
					data-target="#load_modal_add_workhead" class="btn btn-primary viewmodal" style="">Add More </button>
				</div>
			</div>
			
			<!--<div class="form-row">						
				<div class="col-md-2">Target Date:</div>
					<div class="col-md-4">
				<input type="text" name="target_date" id="target_date" value="<?php echo date("d")+1 . "-".date("m")."-".date("Y"); ?>" class="form-control target_date"/>
				</div>
			</div>-->		
			<div class="form-row" style="overflow:scroll">						
				<table class="table table-bordered">
					<thead>
						<tr>
							<th rowspan="2" class="text-center">Contract Item No</th>
							<!--<th colspan="9" class="text-center">Work/ Item</th>-->
							<th rowspan="2" class="text-center">Work Description</th>
							<!-- <th rowspan="2" class="text-center">Detail Description</th> -->
							<th colspan="3" class="text-center">Quantity</th>
							<th rowspan="2" class="text-center">Unit</th>	
							<th rowspan="2" class="text-center">Unit Rate</th>
							<th colspan="2" class="text-center">Amount</th>
							<th rowspan="2" class="text-center">Delete</th>
						</tr>
						<tr>
							<!--<th class="text-center">Work Head</th>-->
							<th class="text-center">This WO Quantity</th>
							<th class="text-center">Upto Previous WO Quantity</th>
							<th class="text-center">Till Date WO Quantity</th>
							<!--<th class="text-center">Dis<br>(%)</th>
							<th class="text-center">CGST<br>(%)</th>
							<th class="text-center">SGST<br>(%)</th>
							<th class="text-center">IGST<br>(%)</th>-->
							<th class="text-center">This WO Amount (Rs.)</th>
							<th class="text-center">Till Date WO Amount (Rs.)</th>
						</tr>
					</thead>
					<tbody>	
						<tr id="row_id_0">
							<td>
								<input type="text" name="material[contract_no][]" id="contract_no_0" class="contract_no validate[required,custom[number]]" data-id="0" style="width:130px;">
								<input type="hidden" value="0" name="row_number" class="row_number">
							</td>							
							<td>
								<select class="select2 material_name"  required="true" style="width: 100%;" name="material[material_name][]" data-id="0" id="material_name_0">
									<option value="">--Select Option--</option>
									<?php
										
										// foreach($description_options as $key => $retrive_data) {
										// 	echo '<option value="'.$retrive_data['cat_id'].'">'.$retrive_data['category_title'].'</option>';
										// }
									?>
								</select>
								<input type="text" placeholder="Description Here" class="desc_textfield" name="material[detail_description][]" value="" id="detail_description_0" style="display:none;">
							</td>
							<!-- <td> 
								<input type="text" name="material[detail_description][]" style="width:200px;" value="" class="detail_description" data-id="0" id="detail_description_0"/>
							</td> -->
							<td> 
								<input type="text" name="material[quantity_this_wo][]" style="width:80px;" value="" class="quantity_this_wo" data-id="0" id="quantity_this_wo_0"/>
							</td>
							<td> 
								<input type="text" name="material[quantity_previous_wo][]" style="width:80px;" value="0" readonly="true" class="quantity_previous_wo" data-id="0" id="quantity_previous_wo_0"/>
							</td>
							<td> 
								<input type="text" name="material[quantity_till_date][]" style="width:80px;" value="" readonly="true" class="quantity_till_date" data-id="0" id="quantity_till_date_0"/>
							</td>
							<td>
								<input type="text" value="" name="material[unit][]" readonly="true" id="unit_0" class="form-control" style="width:80px;">
							</td>
							
							<td>
								<input type="text" name="material[unit_rate][]" class="unit_rate" value="0" data-id="0" id="unit_rate_0" style="width:80px" />
							</td>
							
							<!--<td>
								<input type="text" name="material[discount][]" value="0" class="tx_count" id="dc_0" data-id="0" style="width:55px">
							</td>
							
							<td>
								<input type="text" name="material[cgst][]" value="0"  class="tx_count" id="cgst_0" data-id="0" style="width:55px">
							</td>
							
							<td>
								<input type="text" name="material[sgst][]" class="tx_count" value="0" id="sgst_0"  data-id="0" style="width:55px">
							</td>
							
							<td>
								<input type="text" name="material[igst][]" class="tx_count" value="0" id="igst_0"  data-id="0" style="width:55px">
							</td>-->
							
							<td>
								<input type="text" name="material[amount][]" value="0" class="amount" id="amount_0" style="width:90px" />
							</td>
							
							<td>
								<input type="text" name="material[amount_till_date][]" value="0" class="amount_till_date" id="amount_till_date_0" style="width:90px" />
							</td>

							<td>
								<a href="javascript:void(0)" class="btn btn-primary add_textfield" onClick="insertRow(0)" id="textfield_0" value="textfield">Textfield</a>
								<a href="#" class="btn btn-danger del_parent">Delete</a>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="7" class="text-center"><b>Total Amount</b></td>
							<td id="total_wo_amount" style="padding-left:24px;">0
							</td>
							<td id="sub_total_till_date" style="padding-left:24px;">0
							<td>
								<input type="hidden" name="sub_total_till_date" class="sub_total_till_date" value="0"></td>
								<input type="hidden" name="sub_total" class="sub_total" value="0" id="sub_total">
							</td>
						</tr>
						<tr id="cgst_row">
							<td colspan="6" class="text-center"><b>CGST (%)</b></td>
							<td>
								<input type="text" name="cgst_percentage" id="cgst_percentage_0" class="cgst_percentage validate[custom[number]]" data-id="0" style="width:80px;" value="0">
							</td>
							<td colspan="1" style="padding-left:24px;">
								<span class="cgst"></span>
								<input type="hidden" name="cgst" id="cgst_0" class="cgst validate[custom[number]]" data-id="0" style="width:80px;">
							</td>
							<td colspan="1" style="padding-left:24px;">
								<span class="cgst_till_date"></span>
								<input type="hidden" name="cgst_till_date" id="cgst_till_date" class="cgst_till_date validate[custom[number]]" data-id="0" style="width:80px;">
							</td>
							<td></td>
						</tr>
						<tr id="sgst_row">
							<td colspan="6" class="text-center"><b>SGST (%)</b></td>
							<td>
								<input type="text" name="sgst_percentage" id="sgst_percentage_0" class="sgst_percentage validate[custom[number]]" data-id="0" style="width:80px;" value="0">
							</td>
							<td colspan="1" style="padding-left:24px;">
								<span class="sgst"></span>
								<input type="hidden" name="sgst" id="sgst_0" class="sgst" data-id="0" style="width:80px;">
							</td>
							<td colspan="1" style="padding-left:24px;">
								<span class="sgst_till_date"></span>
								<input type="hidden" name="sgst_till_date" id="sgst_till_date" class="sgst_till_date" data-id="0" style="width:80px;">
							</td>
							<td></td>
						</tr>
						<tr id="igst_row">
							<td colspan="6" class="text-center"><b>IGST (%)</b></td>
							<td>
								<input type="text" name="igst_percentage" id="igst_percentage_0" class="igst_percentage validate[custom[number]]" data-id="0" style="width:80px;" value="0">
							</td>
							<td colspan="1" style="padding-left:24px;">
								<span class="igst"></span>
								<input type="hidden" name="igst" id="igst_0" class="igst" data-id="0" style="width:80px;">
							</td>
							<td colspan="1" style="padding-left:24px;">
								<span class="igst_till_date"></span>
								<input type="hidden" name="igst_till_date" id="igst_till_date" class="igst_till_date" data-id="0" style="width:80px;">
							</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="7" class="text-center"><b>Net Amount</b></td>
							<td style="padding-left:24px;"><span id="net_amount">0</span>
								<input type="hidden" name="net_amount" class="net_amount">
							</td>
							<td style="padding-left:24px;"><span id="till_date_net_amount">0</span>
								<input type="hidden" name="till_date_net_amount" class="till_date_net_amount">
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
            </div>
						
			<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
			<button type="button" id="enable_description" data-type="subcontractbill_option" data-toggle="modal" 
					data-target="#load_modal_enable_description" class="btn btn-default enable_description" style="">Enable Description</button>
			<!-- <button type="button" id="new_option" data-type="subcontractbill_option" data-toggle="modal" 
					data-target="#load_modal" class="btn btn-default add_option" style="">Add New Option </button> -->
			
			<div class="form-row" id="remark_1">
				<div class="col-md-2">Remarks/Note:</div>
				<div class="col-md-8">
					<p>1) The above mentioned amount includes following: </p>
					<p> 
						<div class="checkbox">
							<label><input type="checkbox" value="1" name="taxes_duties1"/> All Taxes & Duties</label>
						</div>
					</p>
					<p> 
						<div class="checkbox">
							<label><input type="checkbox" value="1" name="guarantee_check1"/>Guarantee up to</label>
							<input name="guarantee1" style="width:150px;float:none;display: inline;">
						</div>
					</p>
					<p> 2) You are also binded to Yashnand's Contract Conditions & Specifications with Client;Which are provided to you.</p>
					<p> 3) If work will found unsatisfactory afterwards; agency/party has to correct it free of cost.</p>
					<p> 4) Always get your work checked and verified by Yashnand's Engineer In-charge,PMC/TPI,Client and other consultants. If they ask, make sample and take their approval before starting work. </p>
					<p> 5) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance.</p>
					<p> 6) If you will not revert back within 48 hrs, this WO will be considered as accepted by you.</p>
					<p> 7) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</p>
					<p> 8) All disputes subject to Ahmedabad Jurisdiction only.</p>
					<p> 9) All Tools, Tackles & Equipment for completing the work need to be procured by you at your cost.</p>
					<p> 10) Agency/party needs to maintain and obey all safety rules & standards.</p>
					<p> 11) For payment party will have to submit <strong> Invoice along with Work Order (WO), Measurement Sheet along with Abstract duly signed by Construction Manager, Billing Engineer & Site Accountant.</strong></p>
					<p id="gj_address" class="gj_address"><strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</p>
					<p id="mp_address" class="mp_address" style="display:none;"><strong>Billing Address:</strong>House No - MF 04/72 MIG,Shivaji Parisar,Nehrunagar, Bhopal,Madhya Pradesh - 462016.</p>
					<p id="mh_address" class="mh_address" style="display:none;"><strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</p>
					<p id="haryana_address" class="haryana_address" style="display:none;"><strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</p>
					<p><strong>Courier Address:</strong> Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.</p>
					<p>
						<strong>PAN No.:</strong> <span class="pan_no"> AABCY0913A</span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<strong>GST No.:  </strong>
						<input readonly name="gstno1" id="gstno" class="gstno" value="24AABCY0913A1Z1"  style="width:160px;float:none;display: inline;"/>
					</p>
					<p> 12) Your Invoice will be paid after deduction of advances, any type of debit notes, credit notes, retention money / security deposit, taxes etc.</p>
					<p> 13) Retention Money / Security Deposit will be deducted from every bills and will be released after satisfactory work completion.</p>
					<p> 14) YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD. has right to cancel order any time without any prior notice..</p>					
					<p> 15) Payment will be done <input type="text" name="payment_days1" id="payment_days" value="" style="width:60px;float:none;display: inline;" /> days after date of delivery on site or bill submission which ever is later.</p>
				</div>
			</div>
						
			<div class="form-row" id="remark_2" style="display:none;">
				<div class="col-md-2">Remarks/Note:</div>
					<div class="col-md-8">
						<p>1) The above mentioned amount includes following: </p>
						<p> 
							<div class="checkbox">
								<label><input type="checkbox" value="1" name="taxes_duties2"/> All Taxes & Duties</label>
							</div>
						</p>							
						<p> 
							<div class="checkbox">
								<label><input type="checkbox" value="1" name="loading_transport2" id="loading" /> Loading & Transportation - F. O. R. at Place of Delivery</label>
							</div>
						</p>
						<p> 
							<div class="checkbox">
								<label><input type="checkbox" value="1" name="unloading2"/>Unloading</label>
							</div>
						</p>
						<p> 
							<div class="checkbox">
								<label><input type="checkbox" value="1" name="guarantee_check2"/>Guarantee up to</label>
								<input name="guarantee2" style="width:150px;float:none;display: inline;">
							</div>
						</p>
						<p> 
							<div class="checkbox">
								<label><input type="checkbox" value="1" name="warranty_check2"/>Material Replacement Warranty up to</label>
								<input name="warranty" style="width:150px;float:none;display: inline;">
							</div>
						</p>
						<p> 2) You are also binded to our Contract Conditions & Specifications with Client; which are provided to you.</p>
						<p> 3) If work will found unsatisfactory afterwards; agency has to correct it free of cost.</p>
						<p> 4) Material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this WO will be considered as void. </p>
						<p> 5) Check Material Make / Brand with the make list provided to you and get its sample approved by our Engineer In-charge,PMC/TPI, Client and other consultant.</p>
						<p> 6) Manufacturer's Test Certificates are required for each batch of supply.</p>
						<p> 7) Always get your work checked and verified by our Engineer In-charge, PMC/TPI, Client and other consultants also take their prior approval before starting work.</p>
						<p> 8) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance.</p>
						<p> 9)  If you will not revert back within 48 hrs, this WO will be considered as accepted by you.</p>
						<p> 10) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</p>
						<p> 11) All disputes subject to Ahmedabad Jurisdiction only.</p>
						<p> 12) All Tools, Tackles & Equipment for completing the work need to be procured by you at your cost.</p>
						<p> 13) Agency/party needs to maintain and obey all safety rules & standards.</p>
						<p> 14) For payment party will have to submit Invoice along with Work Order (WO), Measurement Sheet & Abstract duly signed by Construction Manager, Billing Engineer & Site Accountant.</p>
						<p id="gj_address" class="gj_address"><strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</p>
						<p id="mp_address" class="mp_address" style="display:none;"><strong>Billing Address:</strong>House No - MF 04/72 MIG,Shivaji Parisar,Nehrunagar, Bhopal,Madhya Pradesh - 462016.</p>
						<p id="mh_address" class="mh_address" style="display:none;"><strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</p>
						<p id="haryana_address" class="haryana_address" style="display:none;"><strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</p>
						<p><strong>Courier Address:</strong> Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.</p>
						<p>
							<strong>PAN No.:</strong><span class="pan_no"> AABCY0913A</span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<strong>GST No.:  </strong>
							<input readonly name="gstno2" id="gstno" class="gstno" value="24AABCY0913A1Z1"  style="width:160px;float:none;display: inline;"/>
						</p>
						<p> 15) Your Invoice will be paid after deduction of advances, any type of debit notes, credit notes, retention money / security deposit, taxes etc.</p>				
						<p> 16) Retention Money / Security Deposit will be deducted from every bills and will be released after satisfactory work completion.</p>
						<p> 17) YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD. has right to cancel order any time without any prior notice.</p>
						<p> 18) Payment will be done <input type="text" name="payment_days2" id="payment_days" value="" style="width:60px;float:none;display: inline;" /> days after date of delivery on site or bill submission which ever is later.</p>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2">Remarks/Note</div>
					<div class="col-md-10"><pre style="background:none;border:0px;font-size:15px;padding:0;"><textarea name="remarks" class="form-control"></textarea></pre></div>
				</div> 
				<div class="form-row">							
					<div class="col-md-2"> Attach Documents</div>
					<div class="col-md-4">
						<input type="file" name="attach_file[]" class="input-file">
					</div>
					<div class="col-md-1">
						<a href="javascript:void(0)" class="create_field form-control">+&nbsp;Add</a>
					</div>
				</div>
				<div class="form-row add_field">
				</div>
				<div class="form-row">
					<div class="col-md-2">Approve Mail </div>
					<div class="col-md-10">
						<div class="radiobox-inline" style="padding:0 50px;">
							<label><input type="radio" checked name="mail_check" class="mail_check" id="enabled_mail" value="1"/> Enable</label>
						</div>
						<div class="radiobox-inline" style="padding:0 50px;">
							<label><input type="radio" name="mail_check" value="0" class="mail_check" id="disabled_mail" />Disable</label>
						</div>
						<div class="radiobox-inline" style="padding:0 50px;">
							<label><input type="radio" name="mail_check" value="2" class="mail_check" id="enableddeputymanager_mail"/>Enable + Dy. Manager (Ele.)</label>
						</div>
					</div>
				</div>	
				<div class="form-row">
					<div class="col-md-2"></div>
					<div class="col-md-4"><button type="submit" class="btn btn-primary">Prepare W.O.</button></div>
				</div>
			</div>
			<?php $this->Form->end(); ?>
		</div>
	</div>
	<?php }?>
</div>
<script>
	// Add Textbox code
	var index = 1;
	function insertRow(id){
		var id = id; 
		$("#detail_description_"+id).css("display","block");
	}
</script>