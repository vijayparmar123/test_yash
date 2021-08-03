<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
		jQuery("body").on("change", ".vendor_quentity, .actualy_qty ", function(event){
			var row = $(this).attr("data-id");
			CheckActualAndVendorQTY(row);
		});
	
		function CheckActualAndVendorQTY(row) {
			var grn_type = $('.grn_type:checked').val();
			var body_name = '';
			
			switch (grn_type) { 
				case 'without_po': 
					body_name = 'static_record';
					break;
				case 'with_po': 
					body_name = 'pending_po_material';
					break;
				case 'with_localpo': 
					body_name = 'local_purchase_material';
					break;
				default:
					body_name = '';
			}
				
			var vendor_qty = parseFloat($('.'+body_name+' #quantity_'+row).val());
			var actual_qty = parseFloat($('.'+body_name+' #actual_qty_'+row).val());
			
			if(vendor_qty != "" && actual_qty != "") {
				if(actual_qty > vendor_qty) {
					$('.'+body_name+' #actual_qty_'+row).val('');
					alert("Not allow actual quantity greater than vendor quantity.");
					return false;
				}
			}
		}

		$("#user_form").submit(function(e) {
			if($("#user_form").validationEngine('validate')){
				$("#user_form").submit();
				$("#save_btn").attr("disabled","disabled");
			}
		});
	
		// $('#save_btn').click(function(e){
			// if($("#user_form").validationEngine('validate')){
				// $("#user_form").submit();
				// $("#save_btn").attr("disabled","disabled");
			// }
		// });
	
		jQuery('.viewmodal').click(function(){
			jQuery('.modal-content').html('');
			var project_id = jQuery('#project_id').val();
			if(project_id == '') {
				alert('Please select project.');
				return false;
			}
			var curr_data = {project_id : project_id};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectmaterial'));?>",
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
		
		jQuery('#user_form').validationEngine();
		jQuery('#grn_date').datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			maxDate: new Date(),
			minDate: -7,
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "-" + year);
			},
			onSelect: function (date) {
				var dt2 = $('.gate_pass_date');
				var startDate = $(this).datepicker('getDate');
				var minDate = $(this).datepicker('getDate');
				dt2.datepicker('setDate', minDate);
				startDate.setDate(startDate.getDate() + 30);
				//sets dt2 maxDate to the last day of 30 days window
				// dt2.datepicker('option', 'maxDate', startDate);
				// dt2.datepicker('option', 'minDate', minDate);
				// $(this).datepicker('option', 'minDate', minDate);
			}
		});
	
		jQuery('.challan_date').datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			maxDate: new Date(),
			minDate: -45,
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "-" + year);
			}                    
		});
		
		jQuery('.gate_pass_date').datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			minDate: -7,
			maxDate: new Date(),
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "-" + year);
			}                    
		});
	
		jQuery("body").on("change", "#row_type", function(event){
			var option = jQuery(this).val();
			if(option == 'withoutpr') {
				//jQuery("#pr_div").css('visibility', 'hidden');
				jQuery("#pr_div").hide();
				jQuery(".pr_record").hide();
				jQuery(".static_record").show();
				jQuery("#add_newrow").show();
				jQuery(".pr_record").empty();
				$("#pr_id").val($("#pr_id option:first").val());
			}else {
				jQuery("#pr_div").show();
				jQuery(".pr_record").show();
				jQuery(".static_record").hide();
				jQuery("#add_newrow").hide();
				jQuery(".static_record").empty();
			}
		});
	
		jQuery("#add_newrow").click(function(){
			var grn_type = $('.grn_type:checked').val();
			var body_name = '';
			var ajax_path = '';
			switch (grn_type) { 
				case 'without_po': 
					body_name = 'static_record';
					ajax_path = '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowgrnwithoutpo"]);?>';
					break;
				case 'with_localpo': 
					body_name = 'local_purchase_material';
					ajax_path = '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowgrnwithlocalpo"]);?>';
					break;
				case 'with_po':
					body_name = 'pending_po_material';
					ajax_path = '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowgrnwithpo"]);?>';
					break;
				default:
					body_name = '';
					ajax_path = '';
			}
			var row_len = jQuery("."+body_name+" .row_number").length;
			var project_id = $("#project_id").val();
			
			if(row_len > 0) {
				var num = jQuery(".row_number:last").val();
				var row_id = parseInt(num) + 1;
			}else {
				var row_id = 0;
			}
			var action = 'add_newrow';
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type: 'POST',
				url: ajax_path,
				data : {row_id:row_id,project_id:project_id},
				success: function (response) {	
					jQuery("."+body_name).append(response);
					jQuery('.delivery_date').datepicker({
						changeMonth: true,
					changeYear: true,
					dateFormat: "dd-mm-yy"
					});
					jQuery('.'+body_name+' #material_id_'+row_id).select2();
					jQuery('.'+body_name+' #brand_id_'+row_id).select2();
					return false;
				},
				error: function(e) {
					alert("An error occurred: " + e.responseText);
					console.log(e);
				}
			});
		});
	
		jQuery("body").on("change", ".material_id", function(event){
			var grn_type = $('.grn_type:checked').val();
			var body_name = '';
			
			switch (grn_type) { 
				case 'without_po': 
					body_name = 'static_record';
					break;
				case 'with_po': 
					body_name = 'pending_po_material';
					break;
				case 'with_localpo': 
					body_name = 'local_purchase_material';
					break;
				default:
					body_name = '';
			}
			var row_id = jQuery(this).attr('data-id');
			var material_id  = jQuery(this).val();
			var project_id = jQuery('#project_id').val();
			/* alert(material_id);
			return false;  */  
			var curr_data = {	 						 					
				material_id : material_id, project_id : project_id	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialbrandlist'));?>",
				// url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialbrandlistprojectwise'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);
					jQuery('.'+body_name+' #brand_id_'+row_id).html();
					jQuery('.'+body_name+' #brand_id_'+row_id).html(json_obj['itemlist']);
					jQuery('.'+body_name+' #brand_id_'+row_id).select2();
					jQuery('.'+body_name+' #unit_name_'+row_id).html();
					jQuery('.'+body_name+' #unit_name_'+row_id).html(json_obj['unit_name']);
					jQuery('.'+body_name+' #material_code_'+row_id).html();
					jQuery('.'+body_name+' #material_code_'+row_id).html(json_obj['material_code']);					
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});
		});
  
		jQuery("body").on("change", ".material_id", function(event){
			var material_id  = jQuery(this).val() ;
			var row_id  = jQuery(this).attr('data-id');
			var ids = [];
			$('select.material_id').not(this).each(function( index, value ) {
				if(jQuery(this).attr('value') != '') {
					ids.push(jQuery(this).attr('value'));
				}
			});
			if(jQuery.inArray( material_id, "["+ids+"]" ) >  -1) {
				alert("You can't select same material again");
				$(this).select2('val', '');
				$("#material_code_"+row_id).html('');
				$("#unit_name_"+row_id).html('');
			}else{
				// alert('not selected');
			}
		});
  
		// jQuery("body").on("change", "#project_id", function(event){ 
			
		// 	var project_id  = jQuery(this).val() ;
		// 		/* alert(material_id);
		// 		return false;  */  
		// 	var curr_data = {	 						 					
		// 		project_id : project_id,	 					
		// 	};	 				
		// 	jQuery.ajax({
		// 		type:"POST",
		// 		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectwisematerial'));?>",
		// 		data:curr_data,
		// 		async:false,
		// 		success: function(response){
		// 			jQuery('select.material_id').select2("val", "");
		// 			jQuery('select.material_id').empty();
		// 			jQuery('select.material_id').append(response);
		// 			jQuery('.material_id').html(response);
		// 			jQuery('#brand_id_'+row_id).select2();
		// 			jQuery('#unit_name_'+row_id).html();
		// 			jQuery('#unit_name_'+row_id).html(json_obj['unit_name']);
		// 			jQuery('#material_code_'+row_id).html();
		// 			jQuery('#material_code_'+row_id).html(json_obj['material_code']);					
		// 			return false;
		// 		},
		// 		error: function (e) {
		// 			alert('Error');
		// 		}
		// 	});
		// });
	
		jQuery("body").on("change", "#project_id", function(event){
			var project_id  = jQuery(this).val() ;
			jQuery('#pr_id').html("");
			var selectedValue = document.querySelector('input[name="grn_type"]:checked'); 
			if(!selectedValue) {
				alert("Please Select GRN Type first ..");
			}	
			var curr_data = {	 						 					
				project_id : project_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'ingrnprojectdetail'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);
					jQuery('#pr_id').append(json_obj['pending_pr']);
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});	
		});
	
		jQuery("body").on("change", "#project_id", function(event){ 
			loadPendingPO();
			var project_id  = jQuery(this).val() ;
			var curr_data = {	 						 					
				project_id : project_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getprojectwisematerial'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					jQuery('select.material_id').empty();
					jQuery('select.material_id').append(response);
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});	
		});
		jQuery("body").on("change", "#pr_id", function(){ 
			var pr_id  = jQuery(this).val();
			var curr_data = {	 						 					
				pr_id : pr_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadgrnitems'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);
					jQuery('#contact_no1').val(json_obj['contact_no1']);						
					jQuery('#contact_no2').val(json_obj['contact_no2']);						
					return false;
				},
				error: function (e) {
					alert('Error');
					console.log(e.responseText);
				}
			});	
		});
		jQuery("body").on("change", "#vendor_userid", function(event){
			loadPendingPO();
			var vendor_userid  = jQuery(this).val() ;
			var curr_data = {	 						 					
				vendor_userid : vendor_userid,	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'vendordetail'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#vendor_id').val(json_obj['vendor_id']);						
					jQuery('#vendor_address').val(json_obj['address_1']);						
					jQuery('#vendor_delivery_address').val(json_obj['delivery_place']);						
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});	
		});

		// jQuery('.brand_add').click(function(){
		// 	jQuery('.modal-content').html('');
		// 	var project_id = jQuery('#project_id').val();
		// 	if(project_id == '') {
		// 		alert('Please select project.');
		// 		return false;
		// 	}
		// 	var curr_data = {project_id : project_id};	 				
		// 	jQuery.ajax({
		// 		type:"POST",
		// 		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addmorebrand'));?>",
		// 		data:curr_data,
		// 		async:false,
		// 		success: function(response){                    
		// 			jQuery('.modal-content').html(response);
		// 			jQuery('.select2').select2();
		// 		},
		// 		beforeSend:function(){
		// 			jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
		// 		},
		// 		error: function(e) {
		// 			console.log(e);
		// 		}
		// 	});			
		// });

		jQuery('.delivery_date').datepicker({
			changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy"
		});
	
		// jQuery('body').on('blur','.vendor_quentity',function(){ 
		// 	var row_id = jQuery(this).attr('data-id');
		// 	var qty = jQuery(this).val();
		// 	var actual_qty = jQuery('#actual_qty'+row_id).val();
		// 	alert(actual_qty);
		// 	if(actual_qty == 'undefined') {
		// 		var actual_qty = 0;
		// 	}
		// 	var amount = 0;
		// 	var diff =  actual_qty - qty;
		// 	if(diff > 0)
		// 	{
		// 		jQuery('#difference_qty_'+row_id).val(diff + " : More");
		// 	}else{
		// 		jQuery('#difference_qty_'+row_id).val(diff + " : Less");			
		// 	}
		// });
	
		jQuery('body').on('blur','.actualy_qty',function(){
			
			var grn_type = $('.grn_type:checked').val();
			var body_name = '';
			switch (grn_type) { 
				case 'without_po': 
					body_name = 'static_record';
					break;
				case 'with_po': 
					body_name = 'pending_po_material';
					break;
				case 'with_localpo': 
					body_name = 'local_purchase_material';
					break;
				default:
					body_name = '';
			}
			var row_id = jQuery(this).attr('data-id');
			var qty = jQuery('.'+body_name+' #quantity_'+row_id).val();
			var actual_qty = jQuery(this).val();
			var amount = 0;
			var diff =  actual_qty - qty;
			if(diff > 0) {
				jQuery('.'+body_name+' #difference_qty_'+row_id).val(diff + " : More");
			}else {
				jQuery('.'+body_name+' #difference_qty_'+row_id).val(diff + " : Less");			
			}
		});
	
		jQuery("body").on("change", "input[type=radio][name=payment_method]", function(event){
			var payment_method = jQuery(this).val();
			if(payment_method == 'Cash') {
				jQuery(".paymeny_block").fadeIn('slow');
			}else {
				jQuery(".paymeny_block").fadeOut('slow');
			}
		});
		
		jQuery("body").on("click",".del_item",function(){
			jQuery(this).parents("tr").remove();
		});
	
	
		jQuery("#remove_pr").click(function(){
			if(confirm("Are you sure you want to remove selected P.R.'s remaining quantity.")) {
				if(confirm("Are you sure you want to remove selected P.R.'s remaining quantity.")) {
					var pr_id = $("#pr_id").val();
					var url = '<?php echo $this->request->base . "/ajaxfunction/removeprfromgrnwithoutpo";?>';
					data = {pr_id : pr_id};
					$.ajax({
						url : url,
						type : "POST",
						data : data,
						success : function(result){
							alert("P.R. quantity removed successfully.");
							$("#pr_id option:selected").remove();
							$("#pr_id").change();
						},
						error : function(e){
							console.log(e.responseText);
						}
					});
				}
			}
		});
	
		jQuery("body").on("change", ".grn_type", function(){
			var grn_type  = jQuery(this).val() ;
			if(grn_type == 'with_po') {
				$("#po_list").prop('required',true);
				$("#po_list").prop('disabled',false);
				$(".pending_po_div").css('display',"block");
				// $("#add_newrow").css('display',"none");
				$(".pending_po_material").css("visibility","visible");
				$(".pending_po_material").css("position","relative");
				
				$(".local_purchase_material").css("visibility","hidden");
				$(".local_purchase_material").css("position","absolute");
				
				/* All three GRN material table head code start */
				$(".static_record_head").css('visibility',"hidden");
				$(".static_record_head").css('position',"absolute");
				$(".local_purchase_material_head").css('visibility',"hidden");
				$(".local_purchase_material_head").css('position',"absolute");
				$(".pending_po_material_head").css('visibility',"visible");
				$(".pending_po_material_head").css('position',"relative");
				/* All three GRN material table head code end */
				
				/* All three GRN material table head code end */
				$(".local_po_footer").css('visibility',"hidden");
				$(".local_po_footer").css('position',"absolute");
				$("#total_po_amount").html('');
				/* Local PO Footer hide/show code start */
				$(".local_po_div").hide();
				$(".manual_po_div").hide();
				$(".static_record").hide();
				$(".static_record input,.static_record select").prop('required',false);
				$(".static_record input,.static_record select").prop('disabled',true);
			}
			else if(grn_type == 'without_po') {
				$("#po_list").prop('required',false);
				$("#po_list").prop('disabled',true);
				$(".pending_po_div").css('display',"none");
				$("#add_newrow").css('display',"block");
				
				/* All three GRN material table head code start */
				$(".pending_po_material_head").css('visibility',"hidden");
				$(".pending_po_material_head").css('position',"absolute");
				$(".local_purchase_material_head").css('visibility',"hidden");
				$(".local_purchase_material_head").css('position',"absolute");
				$(".static_record_head").css('visibility',"visible");
				$(".static_record_head").css('position',"relative");
				/* All three GRN material table head code end */
				
				/* All three GRN material table head code end */
				$(".local_po_footer").css('visibility',"hidden");
				$(".local_po_footer").css('position',"absolute");
				$("#total_po_amount").html('');
				/* Local PO Footer hide/show code start */
				
				// $(".local_purchase_material").css("visibility","hidden");
				$(".local_purchase_material").empty();
				$(".pending_po_material").css("visibility","hidden");
				$(".pending_po_material .cpy_row").remove();
				$(".local_po_div").hide();
				$(".manual_po_div").show();
				$(".static_record").show();
				$(".static_record input,.static_record select").prop('required',true);
				$(".static_record input,.static_record select").prop('disabled',false);
			}else if(grn_type == 'with_localpo') {
				$("#po_list").prop('required',false);
				$("#po_list").prop('disabled',true);
				$(".pending_po_div").css('display',"none");
				$("#add_newrow").css('display',"block");
				
				/* All three GRN material table head code start */
				$(".pending_po_material_head").css('visibility',"hidden");
				$(".pending_po_material_head").css('position',"absolute");
				$(".static_record_head").css('visibility',"hidden");
				$(".static_record_head").css('position',"absolute");
				$(".local_purchase_material_head").css('visibility',"visible");
				$(".local_purchase_material_head").css('position',"relative");
				
				/* All three GRN material table head code end */
				$(".local_po_footer").css('visibility',"visible");
				$(".local_po_footer").css('position',"relative");
				/* Local PO Footer hide/show code start */
				
				/* Local PO Footer hide/show code end */
				
				$(".pending_po_material").css("visibility","hidden");
				$(".pending_po_material .cpy_row").remove();
				$(".local_purchase_material").css('visibility',"visible");
				$(".local_purchase_material").css('position',"relative");
				$(".local_po_div").show();
				$(".static_record").hide();
				$(".manual_po_div").hide();
				$(".static_record input,.static_record select").prop('required',false);
				$(".static_record input,.static_record select").prop('disabled',true);
				
				/* Ajax Call for add row for local po code start */
				var project_id = $("#project_id").val();
				var row_id = 0;
				
				jQuery.ajax({
					headers: {
						'X-CSRF-Token': csrfToken
					},
					type: 'POST',
					url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowgrnwithlocalpo"]);?>',
					data : {row_id:row_id,project_id:project_id},
					success: function (response) {	
						jQuery(".local_purchase_material").append(response);
						jQuery('.delivery_date').datepicker({
							changeMonth: true,
						changeYear: true,
						dateFormat: "dd-mm-yy"
						});
						jQuery('.local_purchase_material #material_id_'+row_id).select2();
						jQuery('.local_purchase_material #brand_id_'+row_id).select2();
						return false;
					},
					error: function(e) {
						alert("An error occurred: " + e.responseText);
						console.log(e);
					}
				});
				/* Ajax Call for add row for local po code end */
			}
			loadPendingPO()
		});
	
		jQuery("#remove_po").click(function(){
			if(confirm("Are you sure you want to remove selected P.O.'s remaining quantity.")) {
				if(confirm("Are you sure you want to remove selected P.O.'s remaining quantity.")) {
					var po_id = $("#po_list").val();
					var url = '<?php echo $this->request->base . "/ajaxfunction/removepofromgrn";?>';
					data = {po_id : po_id};
					$.ajax({
						url : url,
						type : "POST",
						data : data,
						success : function(result){
							alert("P.O. quantity removed successfully.");
							$("#pr_id option:selected").remove();
							$("#po_list").change();
						},
						error : function(e){
							console.log(e.responseText);
						}
					});
				}
			}
		});
	
		jQuery("body").on("change","#po_list",function(){
			jQuery('.pending_po_material').html("");
			var po_id  = jQuery(this).val() ;
			var curr_data = {
				po_id : po_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getpoitems'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('.pending_po_material').append(json_obj['po_data']);
					jQuery("#po_date").val(json_obj['po_date']);
					$("#add_newrow").css('display',"block");
					return false;
				},
				error: function (e) {
					console.log(e.responseText);
					alert('Error');
				}
			});
		});
	
		function loadPendingPO() {
			var grn_type = $('input[name=grn_type]:checked').val();
			var project_id = $('#project_id').val();
			jQuery('#po_list').html("<option value='' selected>Select PO</option>");
			jQuery('#vendor_id').val('');
			var vendor_user_id = jQuery("#vendor_userid").val();
			jQuery("#po_list").select2("val","");
			$(".pending_po_material .cpy_row").remove();
			if(grn_type == "with_po" && project_id != 0 && vendor_user_id != 0){
				var curr_data = {	 						 					
					project_id : project_id,vendor_user_id : vendor_user_id
				};	 				
				jQuery.ajax({
					headers: {
						'X-CSRF-Token': csrfToken
					},
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'ingrnprojectdetaillppo'));?>",
					data:curr_data,
					async:false,
					success: function(response){					
						var json_obj = jQuery.parseJSON(response);											
						jQuery('#po_list').append(json_obj['po_data']);						
						return false;
					},
					error: function (e) {
						alert('Error');
						console.log(e.responseText);
					}
				});	
			}
		}
	
		/* Row calculation code for local po material row start */
		jQuery('body').on('blur','.actualy_qty',function(){
			var row_id = jQuery(this).attr('data-id');
			var unit_rate = jQuery("#unit_price_"+row_id).val();
			var discount = jQuery("#dis_"+row_id).val();
			var gst = jQuery("#gst_"+row_id).val();
			count_total(row_id);
			count_total_withpo(row_id,unit_rate,discount,gst);
			
		});
	
		jQuery("body").on("change",".tx_count",function(){
			var row_id = jQuery(this).attr('data-id');
			var unit_rate = jQuery("#unit_price_"+row_id).val();
			var discount = jQuery("#dis_"+row_id).val();
			var gst = jQuery("#gst_"+row_id).val();
			count_total(row_id);
			count_total_withpo(row_id,unit_rate,discount,gst);
		});
		
		jQuery("body").on("change",".unit_rate",function(){
			var row_id = jQuery(this).attr('data-id');
			var unit_rate = jQuery("#unit_price_"+row_id).val();
			var discount = jQuery("#dis_"+row_id).val();
			var gst = jQuery("#gst_"+row_id).val();
			count_total(row_id);
			count_total_withpo(row_id,unit_rate,discount,gst);
		});

		jQuery("body").on("change",".gst",function(){
			var row_id = jQuery(this).attr('data-id');
			var unit_rate = jQuery("#unit_price_"+row_id).val();
			var discount = jQuery("#dis_"+row_id).val();
			var gst = jQuery("#gst_"+row_id).val();
			count_total(row_id);
			count_total_withpo(row_id,unit_rate,discount,gst);
		});
	
		function count_total(row_id) {
			var qty = jQuery('.local_purchase_material #actual_qty_'+row_id).val();
			var price = jQuery('.local_purchase_material #unit_rate_'+row_id).val();			
			if(price == '') {
				price = 0;
			}
			var single_amount = price;
			var dc = parseFloat($(".local_purchase_material #dc_"+row_id).val());		
			if(dc != '') {			
				dc = parseFloat((100-dc)/100);
				single_amount = parseFloat(price * dc);
			}
			// var tr = parseFloat($(".local_purchase_material #tr_"+row_id).val()); /* CGST */ 
			// var ex = parseFloat($(".local_purchase_material #ex_"+row_id).val()); /* SGST */
			var other = parseFloat($(".local_purchase_material #gst_"+row_id).val()); /* IGST */
			var total_gst = parseFloat(other);
			if(total_gst > 0) {
				var gst_count = 1 + parseFloat(total_gst / 100);
				single_amount = parseFloat(single_amount * gst_count)
			}
			var new_amount = parseFloat(qty*single_amount);
			var single_amt = parseFloat(single_amount);
			jQuery('.local_purchase_material #amount_'+row_id).val(new_amount.toFixed(2));
			jQuery('.local_purchase_material #single_amount_'+row_id).val(single_amt.toFixed(2));
			var po_sum = 0;
			jQuery('.amount').each(function(){
				var single_po_amount = jQuery(this).val();
				po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
			});
			jQuery('#total_po_amount').html();
			jQuery('#total_po_amount').html(po_sum.toFixed(2));
		}

		function count_total_withpo(row_id,unit_rate,discount,gst) {
			if(unit_rate == 0 && discount == 0 && gst == 0){
				var qty = jQuery('.pending_po_material #actual_qty_'+row_id).val();
				var price = jQuery('.pending_po_material #unit_price_'+row_id).val();
				if(price == '') {
					price = 0;
				}
				var single_amount = price;
				
				var dc = parseFloat($(".pending_po_material #discount_"+row_id).val());
				if(dc != '') {			
					dc = parseFloat((100-dc)/100);
					single_amount = parseFloat(price * dc);
				}
				
				// var tr = parseFloat($(".local_purchase_material #tr_"+row_id).val()); /* CGST */ 
				// var ex = parseFloat($(".local_purchase_material #ex_"+row_id).val()); /* SGST */
				var gst = parseFloat($(".pending_po_material #gst_"+row_id).val()); /* GST */
				var total_gst = parseFloat(gst);
				
				if(total_gst > 0) {
					var gst_count = 1 + parseFloat(total_gst / 100);
					single_amount = parseFloat(single_amount * gst_count);
				}
				
				var new_amount = parseFloat(qty*single_amount);
				var single_amt = parseFloat(single_amount);
				
				jQuery('.pending_po_material #amount_'+row_id).val(new_amount.toFixed(2));
				jQuery('.pending_po_material #single_amount_'+row_id).val(single_amt.toFixed(2));
				
				var po_sum = 0;
				jQuery('.amount').each(function(){
					var single_po_amount = jQuery(this).val();
					po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
				});
				jQuery('#total_po_amount').html();
				jQuery('#total_po_amount').html(po_sum.toFixed(2));
			}else {
				var qty = jQuery('.pending_po_material #actual_qty_'+row_id).val();
				var price = unit_rate;
				if(price == '') {
					price = 0;
				}
				var single_amount = price;
				
				var dc = parseFloat(discount);
				if(dc != '') {			
					dc = parseFloat((100-dc)/100);
					single_amount = parseFloat(price * dc);
				}
				
				// var tr = parseFloat($(".local_purchase_material #tr_"+row_id).val()); /* CGST */ 
				// var ex = parseFloat($(".local_purchase_material #ex_"+row_id).val()); /* SGST */
				var gst_amount = parseFloat(gst); /* GST */
				var total_gst = parseFloat(gst_amount);
				if(total_gst > 0) {
					var gst_count = 1 + parseFloat(total_gst / 100);
					single_amount = parseFloat(single_amount * gst_count);
				}
				
				var new_amount = parseFloat(qty*single_amount);
				var single_amt = parseFloat(single_amount);
				
				jQuery('.pending_po_material #amount_'+row_id).val(new_amount.toFixed(2));
				jQuery('.pending_po_material #single_amount_'+row_id).val(single_amt.toFixed(2));
				
				var po_sum = 0;
				jQuery('.amount').each(function(){
					var single_po_amount = jQuery(this).val();
					po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
				});
				jQuery('#total_po_amount').html();
				jQuery('#total_po_amount').html(po_sum.toFixed(2));
			}
		}

		/* Row calculation code for local po material row end */
		jQuery("body").on("change", "#usage", function(){
			usage_name = jQuery(this).val();
			if(usage_name == 'for_agency') {
				jQuery("#agency_div").show();
				jQuery("#agency_id").attr('required', true);
			}else if(usage_name == 'for_self') {
				jQuery("#agency_div").hide();
				jQuery("#agency_id").attr('required', false);
			}
		});
	});
</script>
<?php 
	$project_id = ($from_sst)?$sst_data->transfer_to:'';
	$project_code = ($from_sst)?$this->ERPfunction->get_projectcode($sst_data->transfer_to):'';
	$vendor_userid = ($from_sst)?$this->ERPfunction->get_vendor_id('SST'):'';
	$vendor_code = ($from_sst)?$this->ERPfunction->get_vendor_code($vendor_userid):'';
	$challan_no = ($from_sst)?$sst_data->sst_no:'';
	$driver_name = ($from_sst)?$sst_data->driver_name:'';
	$vehicle_no = ($from_sst)?$sst_data->vehicle_no:'';
?>
<div class="modal fade " id="load_modal" role="dialog">
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
    <div class="block block-fill-white">					
		<div class="head bg-default bg-light-rtl">
			<h2><?php echo $form_header;?></h2>
			<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data']);?>
		<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>			
		<div class="content controls">				
			<div class="form-row">
				<?php if(!$from_sst){ ?>
				<div class="col-md-2">GRN Type : </div>
                <div class="col-md-10">
					<!-- <div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" checked name="grn_type" class="grn_type" value="without_po" /> Without PO</label>
					</div> -->
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" name="grn_type" value="with_po" class="grn_type" />With PO</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" name="grn_type" value="with_localpo" class="grn_type" />With Local PO</label>
					</div>
				</div>
				<?php }else{ ?>
				<input type="hidden" name="grn_type" value="without_po">
				<?php } ?>
			</div>
		
			<div class="form-row">
				<div class="col-md-2">Project Code<span class="require-field">*</span> </div>
				<div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code; ?>"
				class="form-control validate[required]" value="" readonly="true"/>
				<input type="hidden" name="sst_id" value="<?php echo $sst_id; ?>">
				<input type="hidden" name="sst_detail_id" value="<?php echo $sst_detail_id; ?>">
				<input type="hidden" name="from_sst" value="<?php echo $from_sst; ?>">
				</div>
				<div class="col-md-2">Project Name *</div>
				<div class="col-md-4">
					<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
					<option value="">--Select Project--</Option>
					<?php 
						foreach($projects as $retrive_data) {
							echo '<option value="'.$retrive_data['project_id'].'" '.(($retrive_data['project_id']==$project_id)?'selected':'').'>'.
							$retrive_data['project_name'].'</option>';
						}
					?>
					</select>
				</div>
			</div>

			<div class="form-row">
				<!--<div class="col-md-2">G. R. N. L. P. No</div>
				<div class="col-md-4">							
					<input type="text" name="grn_no" id="grn_no" value="" class="form-control" value=""/>
				</div>-->                       
				<div class="col-md-2 text-right">Date<span style="color:red;">*</span></div>
				<div class="col-md-2"><input type="text" required onkeydown="return false" name="grn_date" id="grn_date" class="form-control validate[required]" value=""/></div>
					<div class="col-md-2 text-right">Time<span style="color:red;">*</span></div>
				<div class="col-md-2"><input type="text" required name="grn_time" id="grn_time" class="form-control validate[required]" value=""/></div>
			</div>
						
			<!-- <div class="form-row local_po_div" style="display:none;">
				<div class="col-md-2">Mode of Billing: </div>
				<div class="col-md-10">
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" checked name="bill_mode" class="bill_mode" value="gujarat" /> Gujarat</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" name="bill_mode" value="mp" class="bill_mode" />Madhya Pradesh</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" name="bill_mode" value="maharastra" class="bill_mode" />Maharastra</label>
					</div>
				</div>
			</div> -->
						
			<div class="form-row local_po_div" style="display:none;">
				<div class="col-md-2">Usage:</div>
				<div class="col-md-4">
					<select class="select2"  required="true" name="usage_name" style="width:100%;" id="usage">
						<option value="for_self" selected>For Self Use</Option>							
						<option value="for_agency">For Agency</Option>									
					</select>
				</div>
				<div id="agency_div" style="display:none;"> 
				<div class="col-md-2">Debit from Agency:</div>
				<div class="col-md-4">
					<select class="select2 agency_id" style="width: 100%;" name="agency_id" id="agency_id">
						<option value="">--Select Agency--</Option>
						<?php 
							foreach($agency_list as $retrive_data) {
								echo '<option value="'.$retrive_data['id'].'">'.
								$retrive_data['agency_name'].'</option>';
							}
						?>
					</select>
				</div>
				</div>
			</div>

			<div class="form-row">							
					<div class="col-md-2">Vendor ID</div>
				<div class="col-md-4">
					<input type="text" name="vendor_id" id="vendor_id" class="form-control" value="<?php echo $vendor_code; ?>"/>
				</div>
				
				<div class="col-md-2">Vendor Name<span style="color:red;">*</span></div>
				<div class="col-md-4">
					<select class="select2" required style="width: 100%;" name="vendor_userid" id="vendor_userid">
					<option value="">--Select Vendor--</Option>
					<?php 
						foreach($vendor_department as $retrive_data) {
							echo '<option value="'.$retrive_data['user_id'].'" '.(($retrive_data['user_id']==$vendor_userid)?'selected':'').'>'.
							$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';
						}
					?>
					</select>
				</div>
			</div>

			<div class="form-row pending_po_div" style="display:none;">
				<div class="col-md-2">Pending P.O. No.<span style="color:red;">*</span></div>
				<div class="col-md-4">
					<select class="select2" style="width:90%" disabled="disabled" id="po_list" name="po_id">
						<?php if(isset($po_data)){ ?>
						<option value="<?php echo $po_data["po_id"]; ?>"><?php echo $po_data["po_no"] ?></option>
						<?php } ?>
					</select>
					<?php
						if($role == "erphead" || $role == "erpmanager" || $role == "erpoperator" || $role == "asset-inventoryhead") { 
					?>
					<?php } ?>
				</div>
				<div class="col-md-2">P.O. Date</div>
				<div class="col-md-4">
					<input type="text" name="po_date" readonly="true" id="po_date" class="form-control po_date">
				</div>
			</div>
						
			<div class="form-row manual_po_div">
				<div class="col-md-2">Manual P.O. No.</div>
				<div class="col-md-4">
					<input type="text" name="manual_po_no" class="form-control">
				</div>
			</div>
						
			<div class="form-row">
				<div class="col-md-2"></div>
					<div class="col-md-4">
					<!--<div class="radiobox-inline">
						<label><input type="radio" name="row_type" id="row_type" value="withpr" /> With-PR</label>
					</div> 
					<div class="radiobox-inline">
						<label><input type="radio" name="row_type" id="row_type" value="withoutpr" checked="checked" /> Without-PR</label>
					</div> -->
				</div>
			</div>
						
			<div class="form-row" id="pr_div" style="display:none;">    
				<div class="col-md-2">Pending P. R. No</div>
				<div class="col-md-4">
					<select class="select2"  required="true"   style="width: 100%;" name="pr_id" id="pr_id">
						<option value=''>Select Pending PR.No.</option>
						<option value='<?php echo (isset($data))?$this->ERPfunction->get_prid_by_prno($data["prno"]):"";?>' selected><?php echo (isset($data))?$data["prno"]:"";?></option>
					</select>
				</div> 
				<?php
					if($role == "erphead" || $role == "erpmanager" || $role == "constructionmanager") {
				?>
				<a href="javascript:void(0)" id="remove_pr" class="btn btn-danger btn-xs" title="Remove P.R. from list"><span class="icon-trash"></span> </a>
				<?php } ?>
				<!--
				<div class="col-md-2">Attach Challan/bill:</div>
				<div class="col-md-4">
					<input type="file" name="challan_bill" class="form-control">
				</div>
				-->
			</div>
												
						
			<div class="form-row">
				<div class="col-md-2">Security Gate Pass No</div>
				<div class="col-md-4">
					<input type="text" name="security_gate_pass_no" id="security_gate_pass_no" class="form-control"/>
				</div>
				<div class="col-md-2">Gate Pass Date</div>
				<div class="col-md-4">
					<input type="text" name="gate_pass_date" onkeydown="return false" class="form-control gate_pass_date">
				</div>
				
			</div>
						
			<div class="form-row">
				<div class="col-md-2">Challan No<span style="color:red;">*</span></div>
				<div class="col-md-4">
					<input type="text" required name="challan_no" id="challan_no" class="form-control validate[required]" value="<?php echo $challan_no ?>"/>
				</div>
				<div class="col-md-2">Challan Date<span style="color:red;">*</span></div>
				<div class="col-md-4">
					<input type="text" required autocomplete="off" name="challan_date" onkeydown="return false" class="form-control challan_date validate[required]">
				</div>			
             </div>

			<div class="form-row">
				<div class="col-md-2">Driver's Name<span style="color:red;">*</span></div>
				<div class="col-md-4">
					<input type="text" required name="driver_name" id="driver_name" class="form-control validate[required]" value="<?php echo $driver_name; ?>"/>
				</div>
				<div class="col-md-2">Vehicle's No<span style="color:red;">*</span></div>
				<div class="col-md-4">
					<input type="text" required name="vehicle_no" id="vehicle_no" value="<?php echo $vehicle_no; ?>" class="form-control validate[required]" />
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-2">Payment Method</div>
				<div class="col-md-4">
					<div class="radiobox-inline">
						<label><input type="radio" name="payment_method" value="Cheque" class="validate[required]"/> Cheque</label>
					</div>
					<div class="radiobox-inline">
						<label><input type="radio" name="payment_method" value="Cash" class="validate[required]"/> Cash</label>
					</div> 
				</div>
			</div>	

			<div class="form-row">							
				<div class="col-md-2"> Attach Challan, Royalty & Weight Slip</div>
				<div class="col-md-4">
					<input class="add_label form-control">
				</div>
				<div class="col-md-2">
					<a href="javascript:void(0)" class="create_field text-center form-control">+&nbsp;Add</a>
				</div>		
			</div>
			
			<div class="add_field">
				<?php 
					if($user_action == "edit") {
						$attached_files = json_decode($update_inward["attach_file"]);
						$attached_label = json_decode(stripcslashes($update_inward['attach_label']));						
						if(!empty($attached_files)) {							
							$i = 0;
							foreach($attached_files as $file) {?>
								<div class='del_parent'>
									<div class='form-row'>
										<div class='col-md-2'>
											<?php echo $attached_label[$i];?>
											<input type='hidden' name='attach_label[]' value='<?php echo $attached_label[$i];?>' class='form-control'>
										</div>
										<div class='col-md-4'><a href="<?php echo $this->request->base;?>/img/users_images/<?php echo $file;?>" class="btn btn-primary" target="_blank">View File</a>
										<input type='hidden' name='old_attach_file[]' value='<?php echo $file;?>' class='form-control'></div>
										<div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div>
									</div>
								</div>							
								<?php $i++;
							}
						}
					}
				?>
			</div>		

			<div class="paymeny_block" style="display:none">
				<div class="form-row">
					<div class="col-md-2">Purchase Amt (Rs.)</div>
					<div class="col-md-3">
						<input type="text" name="purchase_amt" id="purchase_amt" class="total_amt form-control" value="0"/>
					</div>
					<div class="col-md-1">Freight (Rs.)</div>
					<div class="col-md-2">
						<input type="text" name="freight" id="freight" class="total_amt form-control" value="0"/>
					</div>
					<div class="col-md-1">Unloading(Rs.)</div>
					<div class="col-md-3">
						<input type="text" name="unloading" id="unloading" class="total_amt form-control" value="0"/>
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-2">Voucher No</div>
					<div class="col-md-4">
						<input type="text" name="vouchar_no" id="vouchar_no" class="form-control" value=""/>
					</div>
					<div class="col-md-2">Total Amt Paid (Rs.)</div>
					<div class="col-md-4">
						<input type="text" name="total_amt" id="total_amt" class="form-control" value="0"/>
					</div>
				</div>
				<br>
			</div>						
			<div class="form-row" style="overflow:scroll;">
				<table class="table table-bordered">
					<!-- Without PO Table head start -->
					<thead class="static_record_head">
						<tr>
							<th rowspan="2">Material Group<br>Code</th>
							<th colspan="2">Material / Item</th>
							<th rowspan="2">Vendor's Qty./Weight</th>
							<th rowspan="2">Actual Qty. / Weight</th>
							<th rowspan="2">Difference (+/-)</th>
							<th rowspan="2">Unit</th>
							<th rowspan="2">Remark</th>
							<!-- <th rowspan="2">Remarks by Inspector</th> -->
							<th rowspan="2">Delete</th>
						</tr>
						<tr>
							<th style="width: 400px;">Description</th>
							<th>Make / Source</th>									
						</tr>
					</thead>
					<!-- Without PO Table head end -->
					
					<!-- With PO Table head start -->
					<thead class="pending_po_material_head" style="visibility:hidden;position:absolute;">
						<tr>
							<th rowspan="2">Material Group<br>Code</th>
							<th colspan="2">Material / Item</th>
							<th rowspan="2">PO Remaining</th>
							<th rowspan="2">Vendor's Qty./Weight</th>
							<th rowspan="2">Actual Qty. / Weight</th>
							<th rowspan="2">Difference (+/-)</th>
							<th rowspan="2">Unit</th>
							<th rowspan="2">Unit Rate<br>(Rs.)</th>	
							<th rowspan="2">Dis<br>(%)</th>
							<th rowspan="2">GST<br>(%)</th>
							<th rowspan="2">Amount (Inclusive All)</th>
							<th rowspan="2">Final Rate<br>(Inclusive All)</th>
							<th rowspan="2">Remark</th>
						<!-- <th rowspan="2">Remarks by Inspector</th> -->
							<th rowspan="2">Delete</th>
							</tr>
							<tr>
							<th style="width: 400px;">Description</th>
							<th>Make / Source</th>									
						</tr>
					</thead>
					<!-- With PO Table head end -->
					
					<!-- With Local PO Table head start -->
					<thead class="local_purchase_material_head" style="visibility:hidden;position:absolute;">
						<tr>
							<th rowspan="2">Material Group<br>Code</th>
							<th colspan="2">Material / Item</th>
							<th rowspan="2">Vendor's Qty./Weight</th>
							<th rowspan="2">Actual Qty. / Weight</th>
							<th rowspan="2">Difference (+/-)</th>
							<th rowspan="2">Unit</th>
							<th rowspan="2">Unit Rate<br>(Rs.)</th>	
							<th rowspan="2">Dis<br>(%)</th>
							<!-- <th rowspan="2">CGST<br>(%)</th>
							<th rowspan="2">SGST<br>(%)</th> -->
							<th rowspan="2">GST<br>(%)</th>
							<th rowspan="2">Amount (Inclusive All)</th>
							<th rowspan="2">Final Rate<br>(Inclusive All)</th>
							<th rowspan="2">Remark</th>
						<!-- <th rowspan="2">Remarks by Inspector</th> -->
							<th rowspan="2">Delete</th>
							</tr>
							<tr>
							<th style="width: 400px;">Description</th>
							<th>Make / Source</th>									
						</tr>
					</thead>
					<!-- With Local PO Table head end -->
							
					<tbody id="td_box" class="pr_record">	
						<?php
							if(isset($data["approved_list"])) {
								$i = 0;
								$rows = "";
								foreach($data["approved_list"] as $material) {
									echo '<tr class="cpy_row">
									<td>'.$data["mcode_{$material}"].'</td>
									<td>'.$this->ERPfunction->get_material_title($material).'	<input type="hidden" name="material[material_id][]" readonly = "true" value="'.$material.'" id="material_id_'.$i.'"/></td>
									<td>'.$this->ERPfunction->get_brandname($data["brand_id_{$material}"]).'
										<input type="hidden" name="material[brand_id][]" value="'.$data["brand_id_{$material}"].'">
									</td>
									<td> <input type="text" name="material[quantity][]" readonly = "true" value="'.$data["quantity_{$material}"].'" id="quantity_'.$i.'"/></td>
									<td><input type="text" name="material[actual_qty][]" class="actualy_qty" value="" data-id="'.$i.'" id="actual_qty_'.$i.'"/></td>
									<td><input type="text" name="material[difference_qty][]" readonly = "true" value="" id="difference_qty_'.$i.'"/></td>
									<td>'.$this->ERPfunction->get_items_units($material).'										
									<input type="hidden" name="pr_mid[]" value="'.$data["pr_mid_{$material}"].'">
									</td>
									<td><a href="javascript:void(0)" class="btn btn-danger del_item" title="Delete">Delete</a></td>
									</tr>';
									/* <td><input type="text" name="material[remarks][]" value="" id="remarks_'.$i.'"/></td>	 */
									$i++;
								}
							}
						?>
					</tbody>
					<tbody class="static_record"> 
						<?php 
							if($from_sst) {
								echo $sst_material_row;
							}
							else {
						?>
						<tr id="row_id_0">
							<td><span id="material_code_0"></span>
								<input type="hidden" value="0" name="row_number" class="row_number">
							</td>
							<td>
								<select class="select2 material_id" style="width: 100%;" name="material[material_id][]" id="material_id_0" data-id="0">
									<option value="">--Select Material--</Option>
										<?php 
											foreach($material_list as $retrive_data) {
												echo '<option value="'.$retrive_data['material_id'].'">'.
												$retrive_data['material_title'].'</option>';
											}
										?>
								</select>
							</td>
							<td>
								<select class="select2" name="material[brand_id][]" style="width: 100%;" id="brand_id_0">
									<option value="">--Select Item--</Option>												
								</select>
							</td>
							<td> <input type="text" name="material[quantity][]"  id="quantity_0" class="vendor_quentity" data-id="0"  /></td>
							<td><input type="text" style="padding-left:0;padding-right:0;min-width:53px;" name="material[actual_qty][]" value="" data-id="0" id="actual_qty_0" class="actualy_qty validate[required]" /></td>
							<td><input type="text" name="material[difference_qty][]" readonly = "true" value="" id="difference_qty_0"/></td>
							
							<td><span id="unit_name_0"></span></td>
							<td><input type="text" name="material[remark][]" value="" id="remark_0"/></td>
							<td>
								<a href="javascript:void(0)" class="btn btn-danger del_item" title="Delete">Delete</a>
							</td>
						</tr>
							
						<?php }?>
					</tbody>
					<!-- With PO body start -->
					<tbody class="pending_po_material" style="visibility:hidden;">
					</tbody>
					<!-- With PO body end -->		
					<!-- With Local PO body start -->
					<tbody class="local_purchase_material" style="visibility:hidden;">
					</tbody>
					<!-- With Local PO body end -->		
					<!-- Local PO total count footer code start -->
					<tfoot class="local_po_footer" style="visibility:hidden;position:absolute;">
						<tr>
							<td colspan="10" class="text-right"><b>Total Amount</b></td>
							<td id="total_po_amount" style="padding-left:24px;">0</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tfoot>
					<!-- Local PO total count footer code end -->
				</table>
				<?php if(!$from_sst){ ?>
				<button type="button" id="add_newrow" class="btn btn-default col-md-1">Add New </button>
				<?php } ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
				<button type="button" id="material_add" data-type="material_add" data-toggle="modal" 
				data-target="#load_modal" class="btn btn-default viewmodal" style="">Add Material </button>
				
				<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" id="brand_add" data-type="brand_add" data-toggle="modal" 
				data-target="#load_modal" class="btn btn-default brand_add" style="">Add Brand </button>-->
				</div>
				<!--<div class="form-row">
					<div class="col-md-2 pull-right">
						<a href="javascript:void(0)" id="add_row" class="btn btn-primary">Add</a>
					</div>
				</div> -->
					
				<!-- <div class="form-row local_po_div" style="display:none;">
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
					<p> 
						<div class="checkbox">
							<label><input type="checkbox" value="1" name="material[taxes_duties][]"/> All Taxes & Duties</label>
						</div>
					</p>							
					<p> 
						<div class="checkbox">
							<label><input type="checkbox" value="1" name="material[loading_transport][]" id="loading" /> Loading & Transportation - F. O. R. at Place of Delivery</label>
						</div>
					</p>
					<p> 
						<div class="checkbox">
							<label><input type="checkbox" value="1" name="material[unloading][]"/>Unloading</label>
						</div>
					</p>
					<p> 
						<div class="checkbox">
							<label><input type="checkbox" value="1" name="material[warranty_check][]"/>Replacement Warranty up to</label>
							<input name="warranty" style="width:150px;float:none;display: inline;">
						</div>
					</p>
					<p> 
						<div>
							Credit Period 
							<input name="payment_days" style="width:150px;float:none;display: inline;"> Days
						</div>
					</p>
					</div>
				</div> -->
				
				<!-- <div class="form-row local_po_div" style="display:none;">
					<div class="col-md-2">Remarks/Note</div>
					<div class="col-md-10"><pre style="background:none;border:0px;font-size:15px;padding:0;"><textarea name="po_remarks" class="form-control"></textarea></pre></div>
				</div> -->
				
				<div class="form-row">
					<div class="col-md-2"></div>
					<div class="col-md-4"><button type="submit" class="btn btn-primary" id="save_btn"  onclick="return ValidateExtension()"><?php echo $button_text;?></button></div>
					<!--<div class="col-md-4 pull-right"><a href="javascript:void(0);" data-url='<?php //echo $this->request->base ."/Ajaxfunction/printgrnwithoutpo";?>' id="print_this" class="btn btn-info"><span class="icon-print"></span> Print</a></div> -->
				</div>
			</div>
			<input type="hidden" name="po_mode" value="local">
			<?php $this->Form->end(); ?>
		</div>
	<?php }?>
</div>
<script>
	$(".create_field").click(function(){
		var label = $(".add_label").val();
		if(label == "") {
			alert("Type Challan Name (Challan Date).");
			$(".add_label").focus();
			return false;
		}
		$(".add_label").val("");
		var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='attach_file[]' class='imageUpload'><span class='required red notice'></span></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
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
	
<script>
	$(document).ready(function(){	
		
		$("#print_this").click(function(){		
			var url = $(this).attr('data-url');
			var frm = $('#user_form');
			window.open(url + "?data="+frm.serialize(),"_blank");
		});
		
		
		$("#add_row").click(function(){
			var number = $(".challan").size();
			// var clone = $(".cpy_row").clone();
		/* 	clone.find('input').val('');
			var number = $(".challan").size();
			number = number + 1; */
			/* clone.find('.challan').attr('name','challan['+number+']'); */
			$("#td_box").append(clone);
		});
		
		$(".rem_me").click(function(){
			$(this).parents('tr').remove();
		});
		
		$(".total_amt").change(function(){		
			var purchase = parseInt($("#purchase_amt").val());
			var freight = parseInt($("#freight").val());
			var unloading = parseInt($("#unloading").val());
			var total = purchase + freight + unloading;
			$("#total_amt").val(total);
		});
		
		
		var project_id  = jQuery("#project_id").val() ;
		var curr_data = {	 						 					
			project_id : project_id,	 					
		};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
			type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'ingrnprojectdetaillp'));?>",
			data:curr_data,
			async:false,
			success: function(response){					
				var json_obj = jQuery.parseJSON(response);					
				jQuery('#project_code').val(json_obj['project_code']);						
				jQuery('#grn_no').val(json_obj['grn_no']);
				jQuery('#pr_id').append(json_obj['pending_pr']);
				return false;
			},
			error: function (e) {
				alert('Error');
			}
		});	
		
		
		var pr_id  = jQuery("#pr_id").val() ;
		
		return false; 
		var curr_data = {	 						 					
			pr_id : pr_id,	 					
		};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
			type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadgrnitems'));?>",
			data:curr_data,
			async:false,
			success: function(response){					
				var json_obj = jQuery.parseJSON(response);		
					
				jQuery('#contact_no1').val(json_obj['contact_no1']);						
				jQuery('#contact_no2').val(json_obj['contact_no2']);						
				/* jQuery('.table tbody').html(''); */
				/* jQuery('.table tbody').html(json_obj['pritems']);						 */
				return false;
			},
			error: function (e) {
					alert('Error');
			}
		});
	});
</script>
