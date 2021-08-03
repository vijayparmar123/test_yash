<?php
use Cake\Routing\Router;

$bill_mode = isset($wo_data['bill_mode'])?$wo_data['bill_mode']:"";
$taxes_duties = (($wo_data['taxes_duties'] == 1)?"checked":"");
$loading_transport = (($wo_data['loading_transport'] == 1)?"checked":"");
$unloading = (($wo_data['unloading'] == 1)?"checked":"");
$guarantee = (($wo_data['guarantee'] == 1)?"checked":"");
$guarantee_time = isset($wo_data['guarantee_time'])?$wo_data['guarantee_time']:"";
$warranty = (($wo_data['warrenty'] == 1)?"checked":"");
$warranty_time = isset($wo_data['warrenty_time'])?$wo_data['warrenty_time']:"";
$gstno = ($wo_data['gstno']?$wo_data['gstno']:"");
$payment_days = ($wo_data['payment_days']?$wo_data['payment_days']:"");
$remarks = ($wo_data['remarks']?$wo_data['remarks']:"");
$target_date = ($wo_data["target_date"] != NULL)?date("d-m-Y",strtotime($wo_data["target_date"])):date("d")+1 . "-".date("m")."-".date("Y");
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

	jQuery(document).ready(function() {
		checkGstStatus();
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
	
		$(".create_field").click(function(){
			var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'></div><div class='col-md-4'><input type='file' name='attach_file[]' class='input-file'></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
			$(".add_field").append(field);
		});
	
		$("body").on("click",".del_file",function(){
			$(this).parentsUntil('.del_parent').remove();
		});
	
		jQuery("body").on("change", ".input-file[type=file]", function () {
			var file = this.files[0];
			//var file_id = jQuery(this).attr('id');
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
	
		jQuery("body").on("change", "#project_id", function(event){
			var project_id = $(this).val();
			var curr_data = {	 						 					
				project_id : project_id,	 					
			};
			jQuery("select.material_name").html('');
			$(".material_name").select2("val", "");					
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getprojectwisesubcontractdescription'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					var desc = JSON.parse(response);
					jQuery("select.material_name").append(desc);	
				},
				error: function (tab) {
					alert('error');
				}
			});
		});
	
		jQuery("body").on("change", "#project_id", function(event){
			var project_id = $(this).val();
			var curr_data = {	 						 					
				project_id : project_id,	 					
			};
			jQuery("select.work_head").html('');
			$(".work_head").select2("val", "");					
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'planningwoworktype'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					var desc = JSON.parse(response);
					jQuery("select.work_head").append(desc);	
				},
				error: function (tab) {
					alert('error');
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

		jQuery('.add_option').click(function(){
			var project_id = $("#project_id").val();
			if(!project_id) {
				alert('Please select project first');
				return false;
			}
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html(''); 
			var type = $(this).attr('data-type');
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
					jQuery('#load_modal .modal-content').html(response);
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
			var unit  = jQuery('#subc_description_unit').val() ;
			var subc_project_id  = jQuery('#subc_project_id').val() ;
			var model  = jQuery(this).attr('model');
			if(category_name != "" && unit != "") {
				var curr_data = {					
					model : model,
					category_name: category_name,				
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
						jQuery('#subc_project_id').val("");						
						jQuery("select.material_name").append(json_obj[1]);
						return false;		
                	},
                	error: function (tab) {
                 	   alert('error');
                	}
            	});
			} else {
				alert("Please fill all the fields.");
			}
		});

		jQuery("body").on("click", ".btn-delete-cat", function(event) {
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
				success: function(response){
					// jQuery('#term-'+term_id)
					jQuery('tr#cat-'+cat_id).html(response);
				},
				error: function (tab) {
					alert('error');
				}
			});
		});

		jQuery("body").on("click", ".btn-cat-update", function(event) {
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

		jQuery('.viewmodal').click(function() {
			var project_id = $("#project_id").val();
			if(project_id == '') {
				alert("Please select project first");
				return false;
			}
			jQuery('#modal-view').html('hello');
			var model  = jQuery(this).attr('data-type') ;
			var curr_data = {type : model,project_id : project_id};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'planningworkhead'));?>",
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
					jQuery('#wo_no').val(json_obj['wo_no']);
					jQuery('#project_address').val(json_obj['project_address'] + "," + json_obj['project_address_2']);
					return false;
				},
				error: function (e) {
					alert('Error');
					console.log(e.responseText);
				}
			});	
		});
	
		jQuery("body").on("change", ".bill_mode", function(){
			var state  = jQuery(this).val() ;
			if(state == 'gujarat') {
				$(".gj_address").css("display","block");
				$(".mp_address").css("display","none");
				$(".mh_address").css("display","none");
				$(".haryana_address").css("display","none");
			}else if(state == 'mp') {
				$(".mp_address").css("display","block");
				$(".gj_address").css("display","none");
				$(".mh_address").css("display","none");
				$(".haryana_address").css("display","none");
			}else if(state == 'maharastra') {
				$(".gj_address").css("display","none");
				$(".mp_address").css("display","none");
				$(".mh_address").css("display","block");
				$(".haryana_address").css("display","none");
			}else if(state == 'haryana') {
				$(".gj_address").css("display","none");
				$(".mp_address").css("display","none");
				$(".mh_address").css("display","none");
				$(".haryana_address").css("display","block");
			}
			var curr_data = {	 						 					
				state : state,	 					
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
					jQuery('.gstno').val(response);	
					return false;
				},
				error: function (e) {
					alert('Error');
					console.log(e.responseText);
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
			// var this_bill_total = $(".this_bill_amount").val();
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
		jQuery("body").on("change", ".bill_mode", function() {
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
				success: function(response) {									
											
					jQuery('.pan_no').html(response);	
					return false;
				},
				error: function (e) {
						alert('Error');
						console.log(e.responseText);
				}
			});
		});
	
		jQuery("body").on("change", "#type_of_contract", function() {
			var type_id  = jQuery(this).val() ;
			if(type_id == 1 || type_id == 3 || type_id == 4) {
				$("#remark_1").css("display","block");
				$("#remark_2").css("display","none");
			} else if(type_id == 5 || type_id == 6 || type_id == 7 || type_id == 2) {
				$("#remark_2").css("display","block");
				$("#remark_1").css("display","none"); 
			} else {
				$("#remark_1").css("display","block");
				$("#remark_2").css("display","none");
			}				
		});
	
		jQuery("body").on("change", "#party_id", function(event) {
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
				success: function(response) {					
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
	
		jQuery("#add_newrow").click(function() {
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
				type: 'POST',
				url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowplanningwonew"]);?>',
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

		jQuery('.target_date').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd-mm-yy"
		});
	
		jQuery("body").on("change", ".material_id", function(event) { 
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
	
		jQuery('body').on('click','.trash',function() {
			var row_id = jQuery(this).attr('data-id');
			jQuery('table tr#row_id_'+row_id).remove();	
			return false;
		});
	
		$("body").on("click",".del_parent",function() {
			var detail_id = jQuery(this).attr('detail-id');
			if(detail_id) {
				if(confirm('Are you Sure Delete this Row?')) {
					if(confirm('Are you Sure Delete this Row?')) {
						if(confirm('Are you Sure Delete this Row?')) {
							var curr_data = {	 						 					
								detail_id : detail_id,	 					
							};	 				
							jQuery.ajax({
								headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
								url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'deleteplanningwodetail'));?>",
								data:curr_data,
								async:false,
								success: function(response){
										
								},
								error: function (e) {
									alert('Error');
								}
							});
							$(this).parents("tr").remove();
						}
					}
				}
			}else {
				$(this).parents("tr").remove();
			}
			countAllRowAmount();
		});
		
		jQuery("body").on("change",".othertax",function() {
			var sid = jQuery(this).attr("sid");
			if(sid == "other") {
				jQuery("#other_text").css("display","block");			
			}else {
				jQuery("#other_text").css("display","none");
			}
		});
	
		$("body").on("blur","#other_text",function() {
			var other_tx = $(this).val();
			$(".othertax").val(other_tx);
		});
	
		$("body").on("change","#loading",function() {
			var check = $(this).attr("checked");		
			if(check) {
				$("#show_loading").css("display","none");
			}else{
				$("#show_loading").css("display","block");
			}
		});

		// Edit Row 
		jQuery("body").on('click','.edit_parent',function(){
			var detail_id = jQuery(this).attr('detail-id');
			var project_id = $("#project_id").val();
			jQuery.ajax({
				type: 'POST',
				url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "editrowplanningwo"]);?>',
				data : {id:detail_id,project_id:project_id},
				success: function (response) {	
					jQuery("#row_id_"+detail_id).html(response);
					jQuery('select.select2').select2();
					return false;
				},
				error: function(e) {
					alert("An error occurred: " + e.responseText);
					console.log(e);
				}
			});
		});

		// Update Data of Table
		jQuery("body").on('click','.update_parent',function(){
			var detailId = $(this).attr('detail-id');
			var projectId = $("#project_id").val();
			var woId= <?php echo $wo_id; ?>;
			var contractId = $('#contract_no_'+detailId).val();
			var materialName = $('#material_name_'+detailId).val();
			var detailDescription = $('#detail_description_'+detailId).val();
			var quantityThisWo = $('#quantity_this_wo_'+detailId).val();
			var quantityPreviousWo = $('#quantity_previous_wo_'+detailId).val();
			var tillDateWoQuantity = $('#quantity_this_wo_'+detailId).val();
			var unit = $('#unit_'+detailId).val();
			var unitRate = $('#unit_rate_'+detailId).val();
			var amount = $('#amount_'+detailId).val();
			var amountTillDate = $('#amount_till_date_'+detailId).val();
			var cgstPercentage0 = $('#cgst_percentage_0').val();
			var cgstAmount = $('#cgst_0').val();
			var cgstTillDateAmount = $('#cgst_till_date').val();
			var sgstPercentage0 = $('#sgst_percentage_0').val();
			var sgstAmount = $('#sgst_0').val();
			var sgstTillDateAmount = $('#sgst_till_date').val();
			var igstPercentage0 = $('#igst_percentage_0').val();
			var igstAmount = $('#igst_0').val();
			var igstTillDateAmount = $('#igst_till_date').val();
			var netAmount = $('.net_amount').val();
			var tillDateNetAmount = $('.till_date_net_amount').val();
			var woTotalAmount = $('#total_wo_amount').html();
			var tillDateWoTotalAmount = $('#sub_total_till_date').html();
			// alert(igstTillDateAmount);return false;			
			if(projectId != '') {
				if(contractId != '') {
					if(materialName != '') {
						if(detailDescription != '') {
							if(quantityThisWo != '') {
								if(quantityPreviousWo != '') {
									if(tillDateWoQuantity != '') {
										if(unit != '') {
											if(unitRate != '') {
												if(amount != '') {
													if(amountTillDate != '') {
														jQuery.ajax({
															type: 'POST',
															url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "updaterowplanningwo"]);?>',
															data : {
																detailId : detailId,
																projectId : projectId,
																woId : woId,
																contractId : contractId,
																materialName : materialName,
																detailDescription : detailDescription,
																quantityThisWo :quantityThisWo, 
																quantityPreviousWo : quantityPreviousWo,
																tillDateWoQuantity : tillDateWoQuantity,
																unit : unit,
																unitRate : unitRate,
																amount : amount,
																amountTillDate : amountTillDate,
																cgstPercentage0 : cgstPercentage0,
																cgstAmount : cgstAmount,
																cgstTillDateAmount : cgstTillDateAmount,
																sgstPercentage0 : sgstPercentage0,
																sgstAmount : sgstAmount,
																sgstTillDateAmount : sgstTillDateAmount,
																igstPercentage0 : igstPercentage0,
																igstAmount : igstAmount,
																igstTillDateAmount : igstTillDateAmount,
																netAmount : netAmount,
																tillDateNetAmount : tillDateNetAmount,
																woTotalAmount : woTotalAmount,
																tillDateWoTotalAmount : tillDateWoTotalAmount,
															},
															success: function (response) {	
																jQuery("#row_id_"+detailId).html(response);
															},
															error: function(e) {
																alert("An error occurred: " + e.responseText);
																console.log(e);
															}
														});
													}else {
														alert("Please check Till Date WO Amount (Rs.)...");
														$('#amount_till_date_'+detailId).focus();
													}
												}else {
													alert("Please check This WO Amount (Rs.)...");
													$('#amount_'+detailId).focus();
												}
											}else {
												alert("Please fill Unit Rate...")
												$('#unit_rate_'+detailId).focus();
											}
										}else {
											alert("Please check Unit field...");
											$('#unit_'+detailId).focus();
										}
									}else {
										alert("Please fill Till Date WO Quantity...");
										$('#quantity_this_wo_'+detailId).focus();
									}
								}else {
									alert("Please fill Upto Previous WO Quantity...");
									$('#quantity_previous_wo_'+detailId).focus();
								}
							}else {
								alert("Please fill This WO Quantity...");
								$('#quantity_this_wo_'+detailId).focus();
							}
						}else {
							alert("Please fill Detail Description...");
							$('#detail_description_'+detailId).focus();
						}
					}else {
						alert("Please select work description...");
					}
				}else {
					alert("Please fill Contract Item No...");
					$('#contract_no_'+detailId).focus();
				}
			}else {
				alert("Please Select Project...");
			}
		});	
		// Add new row update
		jQuery("body").on('click','.add_parent',function(){
			var detailId = $(this).attr('detail-id');
			var projectId = $("#project_id").val();
			var woId= <?php echo $wo_id; ?>;
			var contractId = $('#contract_no_'+detailId).val();
			var materialName = $('#material_name_'+detailId).val();
			var detailDescription = $('#detail_description_'+detailId).val();
			var quantityThisWo = $('#quantity_this_wo_'+detailId).val();
			var quantityPreviousWo = $('#quantity_previous_wo_'+detailId).val();
			var tillDateWoQuantity = $('#quantity_this_wo_'+detailId).val();
			var unit = $('#unit_'+detailId).val();
			var unitRate = $('#unit_rate_'+detailId).val();
			var amount = $('#amount_'+detailId).val();
			var amountTillDate = $('#amount_till_date_'+detailId).val();
			// var totalAmount = $('#total_wo_amount').val();
			// alert(totalAmount);return false;

			if(projectId != '') {
				if(contractId != '') {
					if(materialName != '') {
						if(detailDescription != '') {
							if(quantityThisWo != '') {
								if(quantityPreviousWo != '') {
									if(tillDateWoQuantity != '') {
										if(unit != '') {
											if(unitRate != '') {
												if(amount != '') {
													if(amountTillDate != '') {
														jQuery.ajax({
															type: 'POST',
															url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "saverowplanningwo"]);?>',
															data : {
																detailId : detailId,
																projectId : projectId,
																woId : woId,
																contractId : contractId,
																materialName : materialName,
																detailDescription : detailDescription,
																quantityThisWo :quantityThisWo, 
																quantityPreviousWo : quantityPreviousWo,
																tillDateWoQuantity : tillDateWoQuantity,
																unit : unit,
																unitRate : unitRate,
																amount : amount,
																amountTillDate : amountTillDate
															},
															success: function (response) {	
																jQuery("#row_id_"+detailId).replaceWith(response);
															},
															error: function(e) {
																alert("An error occurred: " + e.responseText);
																console.log(e);
															}
														});
													}else {
														alert("Please check Till Date WO Amount (Rs.)...");
														$('#amount_till_date_'+detailId).focus();
													}
												}else {
													alert("Please check This WO Amount (Rs.)...");
													$('#amount_'+detailId).focus();
												}
											}else {
												alert("Please fill Unit Rate...")
												$('#unit_rate_'+detailId).focus();
											}
										}else {
											alert("Please check Unit field...");
											$('#unit_'+detailId).focus();
										}
									}else {
										alert("Please fill Till Date WO Quantity...");
										$('#quantity_this_wo_'+detailId).focus();
									}
								}else {
									alert("Please fill Upto Previous WO Quantity...");
									$('#quantity_previous_wo_'+detailId).focus();
								}
							}else {
								alert("Please fill This WO Quantity...");
								$('#quantity_this_wo_'+detailId).focus();
							}
						}else {
							alert("Please fill Detail Description...");
							$('#detail_description_'+detailId).focus();
						}
					}else {
						alert("Please select work description...");
					}
				}else {
					alert("Please fill Contract Item No...");
					$('#contract_no_'+detailId).focus();
				}
			}else {
				alert("Please Select Project...");
			}
		});
	});
</script>	
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade " id="brand_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="col-md-10" >
<?php 
// if(!$is_capable)
// 	{
// 		$this->ERPfunction->access_deniedmsg();
// 	}
// else
// {
?>		
<div class="col-md-12" >		
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2>Edit Work Order(WO)</h2>
						<div class="pull-right">
						<a href="<?php //echo $this->ERPfunction->action_link('Contract','approvewo');?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
						
					
					 <div class="content controls">
						
						<div class="form-row">
							<div class="col-md-2">Mode of Billing: </div>
                            <div class="col-md-10">
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" <?php echo ($wo_data['bill_mode'] == 'gujarat')?'checked':''; ?> class="bill_mode" value="gujarat" /> Gujarat</label>
                                </div>
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" <?php echo ($wo_data['bill_mode'] == 'mp')?'checked':''; ?> value="mp" class="bill_mode" />Madhya Pradesh</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" <?php echo ($wo_data['bill_mode'] == 'maharastra')?'checked':''; ?> value="maharastra" class="bill_mode" />Maharastra</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" <?php echo ($wo_data['bill_mode'] == 'haryana')?'checked':''; ?> value="haryana" class="bill_mode" />Haryana</label>
                                </div>
                            </div>
							
						</div>
						
						<div class="form-row">
                            <div class="col-md-2" class="text-right">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $this->ERPfunction->get_projectcode($wo_data['project_id']);?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'"'.(($wo_data['project_id'] == $retrive_data['project_id'])?'selected':'').'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>

						<div class="form-row">
                            <div class="col-md-2">Project Address:</div>
                            <div class="col-md-10">
								<input type="text" name="project_address" id="project_address" class="form-control" value="<?php echo $this->ERPfunction->get_projectaddress($wo_data['project_id']); ?>"/>
							</div>
                        </div>
						
						<div class="form-row">
							<div class="col-md-2">YashNand's GST No.:</div>
							<div class="col-md-4">
								<input readonly name="yashnand_gstno" id="gstno" class="gstno form-control" value="<?php echo $wo_data['yashnand_gst_no'];?>"/>
							</div>
						</div>

						<div class="form-row">
                            <div class="col-md-2">W.O.No:</div>
                            <div class="col-md-4">
							
								<input type="text" readonly name="wo_no" id="wo_no" value="<?php echo $wo_data['wo_no']; ?>" class="form-control" value=""/>
							</div>
                        
                            <div class="col-md-2">Date:</div>
                            <div class="col-md-4"><input type="text" name="wo_date" id="wo_date" 
							value="<?php echo $this->ERPfunction->get_date($wo_data['wo_date']);?>" class="form-control" value=""/></div>
							 
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Party's Name:</div>
                            <div class="col-md-4">
								<?php 
								
								?>
								<select class="select2"  required="true"   style="width: 100%;" name="party_id" id="party_id">
								<option value="">--Select Vendor--</Option>
								<?php
                            			if($vendor_info){
                            				foreach($vendor_info as $vendor_row){
                            					?>
													<option value="<?php echo $vendor_row['user_id']; ?>" dataid="vendor" <?php 
																if(isset($wo_data)){
																	if($wo_data['party_userid'] == $vendor_row['user_id']){
																		echo 'selected="selected"';
																	}
																}

													?> ><?php echo $vendor_row['vendor_name'];?></option>

                            					<?php
                            				}
                            			}
										if(!empty($agency_list))
										{
											foreach($agency_list as $agency){ ?>
												<option value="<?php echo $agency['agency_id']; ?>" dataid="agency" <?php 
																if(isset($wo_data)){
																	if($wo_data['party_userid'] == $agency['agency_id']){
																		echo 'selected="selected"';
																	}
																}

													?> ><?php echo $agency['agency_name'];?></option>
											<?php	
											}
										}
										

                            		?>
								</select>
							</div>
                        
                             <div class="col-md-2">party ID:</div>
                            <div class="col-md-4">
								<input type="text" name="party_identy" id="party_identy" value="<?php echo $wo_data['party_id']; ?>" class="form-control" value=""/>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">party Addresss:</div>
                            <div class="col-md-10">
								<input type="text" name="party_address"  id="party_address" class="form-control" value="<?php echo $wo_data['party_address']; ?>"/>
							</div>
                        </div>	
						<div class="form-row">						
                            <div class="col-md-2">Contact No: (1)</div>
							<div class="col-md-4">
								<input type="text" name="party_no1" id="party_no1" class="form-control" value="<?php echo $wo_data['party_no1']; ?>"/>
							</div>
							
                            <div class="col-md-2">Contact No: (2)</div>
							<div class="col-md-4">
								<input type="text" name="party_no2" id="party_no2" value="<?php echo $wo_data['party_no2']; ?>" class="form-control"/>
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">party E-Mail:</div>
                            <div class="col-md-10">
								<input type="text" name="party_email"  id="party_email" class="form-control" value="<?php echo $wo_data['party_email']; ?>"/>
							</div>
                        </div>
						
						 
						<div class="form-row">						
                            <div class="col-md-2">PAN Card No:</div>
							<div class="col-md-4">
								<input type="text" name="party_pan_no" id="party_pan_no" value="<?php echo $wo_data['party_pan_no']; ?>" class="form-control"/>
							</div>
							
                            <div class="col-md-2">GST No:</div>
							<div class="col-md-4">
								<input type="text" name="party_gst_no" id="party_gst_no" value="<?php echo $wo_data['party_gst_no']; ?>" class="form-control"/>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2">Type of Contract:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="type_of_contract" id="type_of_contract" >
									<option value="">--Select Contract--</Option>
									<?php 
										$contract_list = $this->ERPfunction->contract_type_list();
									   foreach($contract_list as $retrive_data)
									   {
											$select = ($wo_data['contract_type'] == $retrive_data['id'])?'selected':'';
											 echo '<option value="'.$retrive_data['id'].'"'.$select.'>'.
											 $retrive_data['title'].'</option>';
									   }
									?>
								</select>
							</div>
                            <div class="col-md-2">Payment Method:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="payment_method" id="payment_method" >
									<option value="">--Select Payment Method--</Option>
									<option value="cash" <?php echo ($wo_data["payment_method"] == "cash")?"selected":"";?>>Cash</Option>
									<option value="cheque" <?php echo ($wo_data["payment_method"] == "cheque")?"selected":"";?>>Cheque</Option>							
								</select>
							</div>
                        
                        </div>

						<div class="form-row">
							<div class="col-md-2">Type of Work:</div>
                            <div class="col-md-4">
								<select class="select2 work_head" required="true" style="width:100%;" name="work_type" id="work_head_0" data-id="0">
									<option value="">Select Work Type</Option>
									<?php 
										foreach($work_type as $retrive_data)
										{
											$selected = ($wo_data['work_type'] == $retrive_data['work_head_id'])?"selected":"";
											echo '<option value="'.$retrive_data['work_head_id'].'"'.$selected.'>'.
											$retrive_data['work_head_title'].'</option>';
										}
									?>
								</select>
							</div>
							<div class="col-md-1">
								<button type="button" id="workhead_add" data-type="workhead_add" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-primary viewmodal" style="">Add More </button>
							</div>
                        </div>
						
						<!--<div class="form-row">						
                            <div class="col-md-2">Target Date:</div>
							 <div class="col-md-4">
							<input type="text" name="target_date" id="target_date" value="<?php echo $target_date ; ?>" class="form-control target_date"/>
							</div>
						</div>-->
							
						<div class="form-row" style="overflow:scroll">						
                            <table id="myTable" class="table table-bordered">
								<thead>
									<tr>
										<th rowspan="2" class="text-center">Contract Item No</th>
										<!--<th colspan="9" class="text-center">Work/ Item</th>-->
										
										<th rowspan="2" class="text-center">Work Description</th>
										<th rowspan="2" class="text-center">Detail Description</th>
										<th colspan="3" class="text-center">Quantity</th>
										<th rowspan="2" class="text-center">Unit</th>	
										<th rowspan="2" class="text-center">Unit Rate</th>
										<th colspan="2" class="text-center">Amount</th>
										<th rowspan="2" class="text-center">Action</th>
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

		<?php
			$i = 0;
			foreach($wod_data as $data)
			{
		?>
		<tr id="row_id_<?php echo $data['wo_detail_id']; ?>">
			<td>
				<input type="hidden" name="material[wo_detail_id][]" value="<?php echo $data['wo_detail_id']; ?>">
				<input type="text" readonly name="material[contract_no][]" value="<?php echo htmlspecialchars($data['contract_no']); ?>" id="contract_no_<?php echo $i; ?>" class="contract_no" data-id="<?php echo $i; ?>" style="width:130px;">
				<input type="hidden" value="<?php echo $i; ?>" name="row_number" class="row_number">
			</td>
			
			<!-- <td>
				<select class="select2 work_head" required="true" style="width:150px;" name="material[work_head][]" id="work_head_<?php echo $i; ?>" data-id="<?php echo $i; ?>">
					<option value="">Select Work Head</Option>
					<?php 
					   foreach($work_head_list as $retrive_data)
					   {
							$select = ($retrive_data['work_head_id'] == $data['work_head'])?'selected':'';
							 echo '<option value="'.$retrive_data['work_head_id'].'"'.$select.'>'.
							 $retrive_data['work_head_title'].'</option>';
					   }
					?>
				</select>
			</td> -->
			
			<td>
				<!--<input type="text" name="material[material_name][]" value="<?php echo htmlspecialchars($data['material_name']); ?>" class="validate[required]" id="material_name_<?php echo $i; ?>" class="material_name" style="width:120px;">-->
				
				<!--<input type="text" name="material[material_name][]" class="validate[required]" id="material_name_0" class="material_name" style="width:120px;">-->
				<!-- <select class="select2 material_name" required="true" style="width: 100%;" name="material[material_name][]" data-id="<?php echo $i; ?>" id="material_name_<?php echo $i; ?>">
					<option value="">--Select Option--</option>
					<?php
						foreach($description_options as $key => $retrive_data)
						{ 
							$selected = ($retrive_data['cat_id'] == $data['material_name'])?"selected":"";
							echo '<option value="'.$retrive_data['cat_id'].'"'.$selected.'>'.$retrive_data['category_title'].'</option>';
						}
					?> -->
					<input type="text" readonly style="width:180px;" value="<?php echo $this->ERPfunction->get_category_title($data['material_name']) ; ?>">
					<input type="hidden" style="width:180px;" name="material[material_name][]" value="<?php echo $data['material_name'] ; ?>">
				</select>
			</td>
			
			<td> 
				<input type="text" readonly name="material[detail_description][]" style="width:160px;" value="<?php echo $data['detail_description']; ?>" class="detail_description" data-id="0" id="detail_description_0"/>
			</td>

			<td> 
				<input type="text" readonly name="material[quantity_this_wo][]" value="<?php echo htmlspecialchars($data['quentity']); ?>" class="quantity_this_wo" data-id="<?php echo $i; ?>" id="quantity_this_wo_<?php echo $i; ?>"/>
			</td>

			<td> 
				<input type="text" name="material[quantity_previous_wo][]" style="width:80px;" value="<?php echo htmlspecialchars($data['quantity_upto_previous']); ?>" readonly="true" class="quantity_previous_wo" data-id="0" id="quantity_previous_wo_<?php echo $i; ?>"/>
			</td>
			<td> 
				<input type="text" name="material[quantity_till_date][]" style="width:80px;" value="<?php echo htmlspecialchars($data['till_date_quantity']); ?>" readonly="true" class="quantity_till_date" data-id="0" id="quantity_till_date_<?php echo $i; ?>"/>
			</td>
			
			<td>
				<input type="text" name="material[unit][]" readonly="true" value="<?php echo htmlspecialchars($data['unit']); ?>" id="unit_<?php echo $i; ?>" class="form-control" style="width:80px;">
			</td>
			
			<td>
				<input type="text" readonly name="material[unit_rate][]" value="<?php echo $data['unit_rate']; ?>" class="unit_rate" data-id="<?php echo $i; ?>" id="unit_rate_<?php echo $i; ?>" style="width:80px" />
			</td>
			
			<!--<td>
				<input type="text" name="material[discount][]" value="<?php echo $data['discount']; ?>" class="tx_count" id="dc_<?php echo $i; ?>" data-id="<?php echo $i; ?>" style="width:55px">
			</td>
			
			<td>
				<input type="text" name="material[cgst][]" value="<?php echo $data['cgst']; ?>"  class="tx_count" id="cgst_<?php echo $i; ?>" data-id="<?php echo $i; ?>" style="width:55px">
			</td>
			
			<td>
				<input type="text" name="material[sgst][]" class="tx_count" value="<?php echo $data['sgst']; ?>" id="sgst_<?php echo $i; ?>"  data-id="<?php echo $i; ?>" style="width:55px">
			</td>
			
			<td>
				<input type="text" name="material[igst][]" class="tx_count" value="<?php echo $data['igst']; ?>" id="igst_<?php echo $i; ?>"  data-id="<?php echo $i; ?>" style="width:55px">
			</td>-->
			
			<td>
				<input type="text" readonly name="material[amount][]" value="<?php echo $data['amount']; ?>" class="amount" id="amount_<?php echo $i; ?>" style="width:90px" />
			</td>

			<td>
				<input type="text" readonly name="material[amount_till_date][]" value="<?php echo $data['amount_till_date']; ?>" class="amount_till_date" id="amount_till_date_<?php echo $i; ?>" style="width:90px" />
			</td>
			
			<td>
				<a href="javascript:void()" class="btn btn-primary edit_parent" detail-id="<?php echo $data['wo_detail_id']; ?>" >Edit</a>
				<a href="#" class="btn btn-danger del_parent" detail-id="<?php echo $data['wo_detail_id']; ?>" >Delete</a>
			</td>
		</tr>
		<?php
			$i++;
			}
		?>
		
	</tbody>
	<tfoot>
		<tr>
			<td colspan="8" class="text-center"><b>Total Amount</b></td>
			<td id="total_wo_amount" style="padding-left:24px;"><?php echo $wo_data['sub_total']; ?>
			</td>
			<td id="sub_total_till_date" style="padding-left:24px;"><?php echo $wo_data['till_date_sub_total']; ?>
			<td>
			
			<input type="hidden" name="sub_total_till_date" class="sub_total_till_date" value="<?php echo $wo_data['till_date_sub_total']; ?>"></td>
			<input type="hidden" name="sub_total" class="sub_total" value="<?php echo $wo_data['sub_total']; ?>" id="sub_total">
			</td>
		</tr>
		<tr id="cgst_row">
			<td colspan="7" class="text-center"><b>CGST (%)</b></td>
			<td>
				<input type="text" name="cgst_percentage" id="cgst_percentage_0" class="cgst_percentage validate[custom[number]]" data-id="0" style="width:80px;" value="<?php echo $wo_data['cgst_percentage'] ?>">
			</td>
			<td colspan="1" style="padding-left:24px;">
				<span class="cgst"><?php echo $wo_data['cgst'];?></span>
				<input type="hidden" name="cgst" id="cgst_0" value="<?php echo $wo_data['cgst']; ?>" class="cgst validate[custom[number]]" data-id="0" style="width:80px;">
			</td>
			<td colspan="1" style="padding-left:24px;">
				<span class="cgst_till_date"></span>
				<input type="hidden" name="cgst_till_date" id="cgst_till_date" class="cgst_till_date validate[custom[number]]" data-id="0" style="width:80px;">
			</td>
			<td></td>
		</tr>
		<tr id="sgst_row">
			<td colspan="7" class="text-center"><b>SGST (%)</b></td>
			<td>
				<input type="text" name="sgst_percentage" id="sgst_percentage_0" class="sgst_percentage validate[custom[number]]" data-id="0" style="width:80px;" value="<?php echo $wo_data['sgst_percentage']; ?>">
			</td>
			<td colspan="1" style="padding-left:24px;">
				<span class="sgst"><?php echo $wo_data['sgst']; ?></span>
				<input type="hidden" name="sgst" id="sgst_0" value="<?php echo $wo_data['sgst']; ?>" class="sgst" data-id="0" style="width:80px;">
			</td>
			<td colspan="1" style="padding-left:24px;">
				<span class="sgst_till_date"></span>
				<input type="hidden" name="sgst_till_date" id="sgst_till_date" class="sgst_till_date" data-id="0" style="width:80px;">
			</td>
			<td></td>
		</tr>
		<tr id="igst_row">
			<td colspan="7" class="text-center"><b>IGST (%)</b></td>
			<td>
				<input type="text" name="igst_percentage" id="igst_percentage_0" class="igst_percentage validate[custom[number]]" data-id="0" style="width:80px;" value="<?php echo $wo_data['igst_percentage']; ?>">
			</td>
			<td colspan="1" style="padding-left:24px;">
				<span class="igst"><?php echo $wo_data['igst']; ?></span>
				<input type="hidden" name="igst" id="igst_0" value="<?php echo $wo_data['igst']; ?>" class="igst" data-id="0" style="width:80px;">
			</td>
			<td colspan="1" style="padding-left:24px;">
				<span class="igst_till_date"></span>
				<input type="hidden" name="igst_till_date" id="igst_till_date" class="igst_till_date" data-id="0" style="width:80px;">
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="8" class="text-center"><b>Net Amount</b></td>
			<td style="padding-left:24px;"><span id="net_amount"><?php echo $wo_data['net_amount'] ?></span>
			<input type="hidden" name="net_amount" value="<?php echo $wo_data['net_amount'] ?>" class="net_amount">
			</td>
			<td style="padding-left:24px;"><span id="till_date_net_amount">0</span>
				<input type="hidden" value="<?php echo $wo_data['till_date_net_amount']; ?>" name="till_date_net_amount" id="till_date_net_amount" class="till_date_net_amount">
			</td>
			<td></td>
		</tr>
	</tfoot>
							</table>
                        </div>
						
						<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
						<button type="button" id="new_option" data-type="subcontractbill_option" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default add_option" style="">Add New Option </button>
						
						<!--<button type="button" id="workhead_add" data-type="workhead_add" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal" style="">Insert Work Head </button>-->
						
						<?php
							$remark_1 = 0;
							$remark_2 = 0;
							if($wo_data['contract_type'] == 1 || $wo_data['contract_type'] == 3 || $wo_data['contract_type'] == 4)
							{
								$remark_1 = 1;
							}
							else
							{
								$remark_2 = 1;
							}
						?>
						<div class="form-row" id="remark_1" style="display:<?php echo ($remark_1)?'block':'none'; ?>">
                            <div class="col-md-2">Remarks/Note:</div>
                            <div class="col-md-8">
							<p>1) The above mentioned amount includes following: </p>
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" name="taxes_duties1" <?php echo $taxes_duties;?>/> All Taxes & Duties</label>
								</div>
							</p>
							<p> 
								<div class="checkbox">
									<label><input type="checkbox" value="1" name="guarantee_check1" <?php echo $guarantee;?>/>Guarantee up to</label>
									<input name="guarantee1" value="<?php echo $guarantee_time;?>" style="width:150px;float:none;display: inline;">
								</div>
								
							</p>
							
							<p>2) You are also binded to Yashnand's Contract Conditions & Specifications with Client;Which are provided to you.
							</p>
							<p>3) If work will found unsatisfactory afterwards; agency/party has to correct it free of cost.</p>
							<p>4) Always get your work checked and verified by Yashnand's Engineer In-charge,PMC/TPI,Client and other consultants. If they ask, make sample and take their approval before starting work. </p>
							<p>5) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance.</p>
							<p>6) If you will not revert back within 48 hrs, this WO will be considered as accepted by you.</p>
							<p>7) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</p>
							<p>8) All disputes subject to Ahmedabad Jurisdiction only.</p>
							<p>9) All Tools, Tackles & Equipment for completing the work need to be procured by you at your cost.</p>
							<p>10) Agency/party needs to maintain and obey all safety rules & standards.</p>
							<p>11) For payment party will have to submit <strong> Invoice along with Work Order (WO), Measurement Sheet along with Abstract duly signed by Construction Manager, Billing Engineer & Site Accountant.</strong></p>
							
							<p id="gj_address" class="gj_address" <?php echo ($bill_mode == "gujarat")?'':'style="display:none;"';?>><strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</p>
							
							<p id="mp_address" class="mp_address" <?php echo ($bill_mode == "mp")?'':'style="display:none;"';?>><strong>Billing Address:</strong><?php echo $this->ERPfunction->getmpbilladdress($wo_data["wo_date"]); ?></p>
							
							<p id="mh_address" class="mh_address" <?php echo ($bill_mode == "maharastra")?'':'style="display:none;"';?>><strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</p>
							
							<p id="haryana_address" class="haryana_address" <?php echo ($bill_mode == "haryana")?'':'style="display:none;"';?>><strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</p>
							
							<p><strong>Courier Address:</strong> Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.							
							</p>
							<p><strong>PAN No.:</strong><span class="pan_no"><?php echo $this->ERPfunction->getstatepanno($bill_mode,$wo_data["wo_date"]); ?></span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<strong>GST No.:  </strong>
								<input readonly name="gstno1" id="gstno" class="gstno" value="<?php echo $gstno;?>"  style="width:160px;float:none;display: inline;"/>									
							
							</p>
							
							<p>12) Your Invoice will be paid after deduction of advances, any type of debit notes, credit notes, retention money / security deposit, taxes etc.
							</p>
							
							<p>13) Retention Money / Security Deposit will be deducted from every bills and will be released after satisfactory work completion.
							</p>
							
							<p>14) <?php echo $this->ERPfunction->getconditionofpowo($wo_data["wo_date"],$bill_mode); ?>
							</p>
							
							<p>15) Payment will be done <input type="text" name="payment_days1" id="payment_days" value="<?php echo $payment_days; ?>" style="width:60px;float:none;display: inline;" /> days after date of delivery on site or bill submission which ever is later.</p>
							
							</div>
                        </div>
						
						<div class="form-row" id="remark_2" style="display:<?php echo ($remark_2)?'block':'none'; ?>">
                            <div class="col-md-2">Remarks/Note:</div>
                            <div class="col-md-8">
							<p>1) The above mentioned amount includes following: </p>
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" name="taxes_duties2" <?php echo $taxes_duties;?>/> All Taxes & Duties</label>
								</div>
							</p>							
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" name="loading_transport2" id="loading" <?php echo $loading_transport;?>/> Loading & Transportation - F. O. R. at Place of Delivery</label>
								</div>
							</p>
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" name="unloading2" <?php echo $unloading;?>/>Unloading</label>
								</div>
							</p>
							<p> 
								<div class="checkbox">
									<label><input type="checkbox" value="1" name="guarantee_check2" <?php echo $guarantee;?>/>Guarantee up to</label>
									<input name="guarantee2" value="<?php echo $guarantee_time; ?>" style="width:150px;float:none;display: inline;">
								</div>
								
							</p>
							<p> 
								<div class="checkbox">
									<label><input type="checkbox" value="1" name="warranty_check2" <?php echo $warranty;?>/>Material Replacement Warranty up to</label>
									<input name="warranty" value="<?php echo $warranty_time; ?>" style="width:150px;float:none;display: inline;">
								</div>
								
							</p>
							<p>2) You are also binded to our Contract Conditions & Specifications with Client; which are provided to you.
							</p>
							<p>3) If work will found unsatisfactory afterwards; agency has to correct it free of cost.</p>
							<p>4) Material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this WO will be considered as void. </p>
							<p>5) Check Material Make / Brand with the make list provided to you and get its sample approved by our Engineer In-charge,PMC/TPI, Client and other consultant.</p>
							<p>6) Manufacturer's Test Certificates are required for each batch of supply.</p>
							<p>7) Always get your work checked and verified by our Engineer In-charge, PMC/TPI, Client and other consultants also take their prior approval before starting work.</p>
							<p>8) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance.</p>
							<p>9)  If you will not revert back within 48 hrs, this WO will be considered as accepted by you.</p>
							<p>10) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</p>
							<p>11) All disputes subject to Ahmedabad Jurisdiction only.</p>
							<p>12) All Tools, Tackles & Equipment for completing the work need to be procured by you at your cost.</p>
							<p>13) Agency/party needs to maintain and obey all safety rules & standards.</p>
							<p>14) For payment party will have to submit Invoice along with Work Order (WO), Measurement Sheet & Abstract duly signed by Construction Manager, Billing Engineer & Site Accountant.</p>
							
							<p id="gj_address" class="gj_address" <?php echo ($bill_mode == "gujarat")?'':'style="display:none;"';?>><strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</p>
							
							<p id="mp_address" class="mp_address" <?php echo ($bill_mode == "mp")?'':'style="display:none;"';?>><strong>Billing Address:</strong><?php echo $this->ERPfunction->getmpbilladdress($wo_data["wo_date"]); ?></p>
							
							<p id="mh_address" class="mh_address" <?php echo ($bill_mode == "maharastra")?'':'style="display:none;"';?>><strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</p>
							
							<p id="haryana_address" class="haryana_address" <?php echo ($bill_mode == "haryana")?'':'style="display:none;"';?>><strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</p>
							
							<p><strong>Courier Address:</strong> Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.							
							</p>
							<p><strong>PAN No.:</strong><span class="pan_no"><?php echo $this->ERPfunction->getstatepanno($bill_mode,$wo_data["wo_date"]); ?></span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<strong>GST No.:  </strong>
								<input readonly name="gstno2" id="gstno" class="gstno" value="<?php echo $gstno ?>"  style="width:160px;float:none;display: inline;"/>									
							
							</p>
							
							<p>15) Your Invoice will be paid after deduction of advances, any type of debit notes, credit notes, retention money / security deposit, taxes etc.
							</p>
							
							<p>16) Retention Money / Security Deposit will be deducted from every bills and will be released after satisfactory work completion.
							</p>
							
							<p>17) <?php echo $this->ERPfunction->getconditionofpowo($wo_data["wo_date"],$bill_mode); ?></p>
							
							<p>18) Payment will be done <input type="text" name="payment_days2" id="payment_days" value="<?php echo $payment_days ?>" style="width:60px;float:none;display: inline;" /> days after date of delivery on site or bill submission which ever is later.</p>
							
							
							</div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Remarks/Note</div>
                            <div class="col-md-10"><pre style="background:none;border:0px;font-size:15px;padding:0;"><textarea name="remarks" class="form-control"><?php echo $remarks; ?></textarea></pre></div>
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
						<?php 
						$attached_files = json_decode($wo_data["attachment"]);			
						if(!empty($attached_files))
						{							
							$i = 0;
							foreach($attached_files as $file)
							{?>
								<div class='del_parent'>
									<div class='form-row'>
										<div class='col-md-2'>
											
										</div>
										<div class='col-md-4'><a href="<?php echo $this->request->base;?>/upload/<?php echo $file;?>" class="btn btn-primary" target="_blank">View File</a>
										<input type='hidden' name='old_attach_file[]' value='<?php echo $file;?>' class='form-control'></div>
										<div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div>
									</div>
								</div>							
							<?php $i++;
							}
						}
						
						?>
						</div>
						
						<div class="form-row">
							<div class="col-md-2">Approve Mail </div>
                            <div class="col-md-10">
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="mail_check" class="mail_check" value="1" id="enabled_mail" <?php echo ($wo_data['mail_check'] == 1)?'checked':''; ?>/> Enable</label>
                                </div>
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="mail_check" value="0" class="mail_check" id="disabled_mail" <?php echo ($wo_data['mail_check'] == 0)?'checked':''; ?> />Disable</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="mail_check" value="2" class="mail_check" id="enableddeputymanager_mail" <?php echo ($wo_data['mail_check'] == 2)?'checked':''; ?>/>Enable + Dy. Manager (Ele.)</label>
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
<?php //}?>
         </div>
		