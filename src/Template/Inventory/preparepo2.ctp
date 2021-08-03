<?php
	use Cake\Routing\Router;
	//var_dump($data);die;
	$contact1 = array();
	$contact2 = array();
	// foreach($data["con1"] as $con1)
	// {
		// $contact1[] = $con1;
	// }
	// foreach($data["con2"] as $con2)
	// {
		// $contact2[] = $con2;
	// }
	if(!empty($data["approved_list"])){
		foreach($data["approved_list"] as $approve)
		{
			$co_1 = $this->ERPfunction->get_pr_contact($approve,"one");
			$contact1[] = $co_1;
			$co_2 = $this->ERPfunction->get_pr_contact($approve,"two");
			$contact2[] = $co_2;
		}
	}
 ?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
		jQuery("body").on("click","#preparepo",function(){
			if(confirm("Are you sure,you want to Prepare Manual PO?")) {
				if(confirm("Are you sure,you want to Prepare Manual PO?")) {
					return true;
				}else {
					return false;
				}
			}else {
				return false;
			}
		});
		
		jQuery('#user_form').validationEngine();
		jQuery('#po_date').datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "-" + year);
			}                    
		});
		
		// jQuery("body").on("change", "#project_id", function(event){ 
		
		// var project_id  = jQuery(this).val() ;
		var project_id  = jQuery("#project_id").val() ;
		jQuery('#pr_id').html("<option value=''>Select Pending PR.No.</option>");
		var curr_data = {	 						 					
			project_id : project_id,	 					
		};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
			type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inpoprojectdetailpo'));?>",
			data:curr_data,
			async:false,
			success: function(response){					
				var json_obj = jQuery.parseJSON(response);					
				jQuery('#project_code').val(json_obj['project_code']);						
				//jQuery('#po_no').val(json_obj['po_no']);						
				jQuery('#pr_id').append(json_obj['pending_pr']);
				jQuery('#vendor_delivery_address').val(json_obj['project_address'] + "," + json_obj['project_address_2']);
				jQuery('#project_address').val(json_obj['project_address'] + "," + json_obj['project_address_2']);
				return false;
			},
			error: function (e) {
				alert('Error');
			}
		});	
		// });
		
		jQuery("body").on("change", "#pr_id", function(event){ 
			var pr_id  = jQuery(this).val();
			var prno = jQuery("#pr_id option:selected").attr('prno');
			jQuery("#curre_pr_id").val(prno);
			var curr_data = {	 						 					
				pr_id : pr_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadpritems'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);
					jQuery('#contact_no1').val(json_obj['contact_no1']);						
					jQuery('#contact_no2').val(json_obj['contact_no2']);						
					jQuery('.table tbody').html('');
					jQuery('.table tbody').html(json_obj['pritems']);						
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});	
		});
		jQuery("body").on("change", "#vendor_userid", function(event){ 
			var vendor_userid  = jQuery(this).val() ;
			/* alert(product_id);
			return false; */
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
					/* jQuery('#vendor_delivery_address').val(json_obj['delivery_place']);*/
					jQuery('#contact_no1').val(json_obj['contact_no1']);												
					jQuery('#contact_no2').val(json_obj['contact_no2']);
					jQuery('#vendor_email').val(json_obj['email_id']);
					jQuery('#custom_pan').val(json_obj['pancard_no']);	
					jQuery('#custom_gst').val(json_obj['gst_no']);	
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});	
		});
		jQuery("#add_newrow").click(function(){
			var project_id = $("#project_id").val();
			//jQuery(this).attr("disabled", "disabled");
			var row_type = jQuery(".row_type:checked").val();
			//var row_id = jQuery("tbody > tr").length;
			var row_len = jQuery(".row_number").length;
			if(row_len > 0) {
				var num = jQuery(".row_number:last").val();
				var row_id = parseInt(num) + 1;
			}else {
				var row_id = 0;
			}
			var action = 'add_newrow';
			if(row_type == "textfield") {
				var class_len = jQuery(".text_data").length;
				if(class_len > 0) {
					var last_code = jQuery(".text_data:last").val();
				}else {
					var last_code = 0;
				}
			}else {
				var last_code = 0;
			}
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type: 'POST',
				url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowpo"]);?>',
				data : {row_id:row_id,row_type:row_type,last_code:last_code,project_id:project_id},
				success: function (response) {	
					jQuery("tbody").append(response);
					jQuery('#material_id_'+row_id).select2();
					jQuery('#brand_id_'+row_id).select2();
					return false;
				},
				error: function(e) {
				alert("An error occurred: " + e.responseText);
					console.log(e);
				}
			});
		});
		
		jQuery('.viewmodal').click(function(){	
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
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addmorematerial'));?>",
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
		
		jQuery('.delivery_date').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd-mm-yy"
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
	
		// jQuery("body").on("change", ".bill_mode", function(){
		// 	var state  = jQuery(this).val() ;
		// 	if(state == 'gujarat') {
		// 		$("#gj_address").css("display","block");
		// 		$("#mp_address").css("display","none");
		// 		$("#mh_address").css("display","none");
		// 		$("#haryana_address").css("display","none");
		// 	}else if(state == 'mp') {
		// 		$("#mp_address").css("display","block");
		// 		$("#gj_address").css("display","none"); 
		// 		$("#mh_address").css("display","none");
		// 		$("#haryana_address").css("display","none");
		// 	}else if(state == 'maharastra') {
		// 		$("#gj_address").css("display","none");
		// 		$("#mp_address").css("display","none");
		// 		$("#haryana_address").css("display","none");
		// 		$("#mh_address").css("display","block");
		// 	}else if(state == 'haryana') {
		// 		$("#gj_address").css("display","none");
		// 		$("#mp_address").css("display","none");
		// 		$("#haryana_address").css("display","block");
		// 		$("#mh_address").css("display","none");
		// 	}
		// 	var curr_data = {	 						 					
		// 		state : state,	 					
		// 	};	 				
		// 	jQuery.ajax({
		// 		type:"POST",
		// 		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getstategstno'));?>",
		// 		data:curr_data,
		// 		async:false,
		// 		success: function(response){						
		// 			jQuery('#gstno').val(response);	
		// 			return false;
		// 		},
		// 		error: function (e) {
		// 			alert('Error');
		// 			console.log(e.responseText);
		// 		}
		// 	});
		// });

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
					jQuery('#pan_no').html(response);	
					return false;
				},
				error: function (e) {
					alert('Error');
					console.log(e.responseText);
				}
			});
		});
			
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
		
		jQuery("body").on("change", ".delivery_type", function(){
			delivery_type = jQuery(this).val();
			if(delivery_type == 'via') {
				jQuery("#delivery_project_div").show();
				jQuery("#delivery_project").attr('required', true);
			}else if(delivery_type == 'direct') {
				jQuery("#delivery_project_div").hide();
				jQuery("#delivery_project").attr('required', false);
				var old_project_address = jQuery("#project_address").val();
				jQuery('#vendor_delivery_address').val(old_project_address);
			}
		});
	
		// Arrow Function created for getting selected dropdown value
		//  Bill Mode change based on Project Selection
		getOPtionValue = () => {
			selectElement =  document.querySelector('#project_id'); 
            output = selectElement.value;
			var curr_data = {	 						 					
				project_id : output,	 					
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
					// jQuery('select.material_id').empty();
					// jQuery('select.material_id').append(response);
					return false;
				},
				error: (e) => {
					alert('Error');
				}
			});	
			
		};

		// Arrow Function called
		getOPtionValue();
		
		// Bill Mode change Based on Delivery via option selection
		jQuery("body").on("change", "#delivery_project", function(event){
			var project_id  = jQuery(this).val() ;
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

		jQuery("body").on("change", "#delivery_project", function(){
			var project_id  = jQuery(this).val() ;
			var curr_data = {	 						 					
				project_id : project_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inpoprojectdetailpo'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);
					jQuery('#vendor_delivery_address').val('');
					jQuery('#vendor_delivery_address').val(json_obj['project_address'] + "," + json_obj['project_address_2']);
					return false;
				},
				error: function (e) {
					alert('Error');
					console.log(e.responseText);
				}
			});	
		});

		// Get Billing Address based on project state selected
		getBillingAddress = () => {
			// selectElement =  document.querySelector('.bill_mode');
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
					jQuery('#gstno').val(response);	
					return false;
				},
				error: function (e) {
					alert('Error');
					console.log(e.responseText);
				}
			});
		}
		getBillingAddress();
	
		jQuery('body').on('click','.trash',function(){
			var row_id = jQuery(this).attr('data-id');
			jQuery('table tr#row_id_'+row_id).remove();	
			return false;
		});
		
		/*jQuery("body").on("change", ".brand_id", function(event){ 
			var row_id = jQuery(this).attr('data-id');
			var project_id  = jQuery("#project_id").val() ;
			var po_date  = jQuery("#po_date").val() ;
			var vendor_id  = jQuery("#vendor_userid").val() ;
			var material_id  = jQuery("#material_id_"+row_id).val() ;
			var brand_id  = jQuery("#brand_id_"+row_id).val() ;
			
			if(project_id == '' || po_date == '' || vendor_id == '' || material_id == '') {
				jQuery('#unit_rate_'+row_id).val(0);
				jQuery('#dc_'+row_id).val(0);
				jQuery('#tr_'+row_id).val(0);
				jQuery('#ex_'+row_id).val(0);
				jQuery('#other_tax_'+row_id).val(0);
				jQuery('#single_amount_'+row_id).val(0);
				count_total(row_id);
				return false;
			}
			var curr_data = {	 						 					
				project_id : project_id,po_date : po_date,vendor_id : vendor_id,material_id : material_id,brand_id : brand_id,	 					
			};	 				
			jQuery.ajax({
				type:"POST",
				url:"<?php //echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialrate'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);	
					jQuery('#unit_rate_'+row_id).val(json_obj['unit_price']);
					jQuery('#dc_'+row_id).val(json_obj['discount']);
					jQuery('#tr_'+row_id).val(json_obj['transportation']);
					jQuery('#ex_'+row_id).val(json_obj['gst']);
					jQuery('#other_tax_'+row_id).val(json_obj['other_tax']);
					jQuery('#single_amount_'+row_id).val(json_obj['final_rate']);
					count_total(row_id);
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});
		});*/
	
		/*jQuery("body").on("change", ".material_id", function(event){ 
			var row_id = jQuery(this).attr('data-id');
			var project_id  = jQuery("#project_id").val() ;
			var po_date  = jQuery("#po_date").val() ;
			var vendor_id  = jQuery("#vendor_userid").val() ;
			var material_id  = jQuery("#material_id_"+row_id).val() ;
			var brand_id  = jQuery("#brand_id_"+row_id).val() ;
			
			if(project_id == '' || po_date == '' || vendor_id == '' || material_id == '') {
				jQuery('#unit_rate_'+row_id).val(0);
				jQuery('#dc_'+row_id).val(0);
				jQuery('#tr_'+row_id).val(0);
				jQuery('#ex_'+row_id).val(0);
				jQuery('#other_tax_'+row_id).val(0);
				jQuery('#single_amount_'+row_id).val(0);
				count_total(row_id);
				return false;
			}
			var curr_data = {	 						 					
				project_id : project_id,po_date : po_date,vendor_id : vendor_id,material_id : material_id,brand_id : brand_id,	 					
			};	 				
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialrate'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);	
					// jQuery('#hsn_code_'+row_id).val(json_obj['hsn_code']);
					jQuery('#unit_rate_'+row_id).val(json_obj['unit_price']);
					jQuery('#dc_'+row_id).val(json_obj['discount']);
					jQuery('#tr_'+row_id).val(json_obj['transportation']);
					jQuery('#ex_'+row_id).val(json_obj['gst']);
					jQuery('#other_tax_'+row_id).val(json_obj['other_tax']);
					jQuery('#single_amount_'+row_id).val(json_obj['final_rate']);
					count_total(row_id);
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});
			
		});*/
		
		/*jQuery("body").on("change", "#vendor_userid", function(event){ 
			var project_id  = jQuery("#project_id").val() ;
			var po_date  = jQuery("#po_date").val() ;
			var vendor_id  = jQuery("#vendor_userid").val() ;
			
			if(project_id == '' || po_date == '' || vendor_id == '') {
				// jQuery('.hsn_code').val('');
				jQuery('.unit_rate').val(0);
				jQuery('.tx_count').val(0);
				jQuery('.single_amount').val(0);
				//count_total(row_id);
				return false;
			}
			var row_id = []; //for get every row id
			i = 0;
			jQuery('.row_id').each(function(){
				var index = jQuery(this).attr('data-id');
				row_id[i++] = jQuery(this).attr('data-id');;
			});
			
			
			var val_arr = []; //for store material_id and brand_id
			a = 0;
			jQuery.each( row_id, function( key, value ) {
				val_arr.push({
					material_id : jQuery("#material_id_"+value).val(),
					brand_id :  jQuery("#brand_id_"+value).val()
				});
			});
			
			var curr_data = {	 						 					
				project_id : project_id,po_date : po_date,vendor_id : vendor_id,val_arr : val_arr
			};
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmultiplematerialrate'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					var json_obj = jQuery.parseJSON(response);
					i = 0;
					jQuery.each( row_id, function( key, value ) {	
						// jQuery('#hsn_code_'+value).val(json_obj[i]['hsn_code']);
						jQuery('#unit_rate_'+value).val(json_obj[i]['unit_price']);
						jQuery('#dc_'+value).val(json_obj[i]['discount']);
						jQuery('#tr_'+value).val(json_obj[i]['transportation']);
						jQuery('#ex_'+value).val(json_obj[i]['gst']);
						jQuery('#other_tax_'+value).val(json_obj[i]['other_tax']);
						jQuery('#single_amount_'+value).val(json_obj[i]['final_rate']);
						// jQuery('#custom_pan').val(json_obj['pancard_no']);	
						// jQuery('#custom_gst').val(json_obj['gst_no']);	
						count_total(value);
						i++;
					});
				},
				error: function (e) {
					alert('Error');
				}
			});
		});*/
		
		/*jQuery("body").on("change", "#project_id", function(event){ 
			var project_id  = jQuery("#project_id").val() ;
			var po_date  = jQuery("#po_date").val() ;
			var vendor_id  = jQuery("#vendor_userid").val() ;
			
			if(project_id == '' || po_date == '' || vendor_id == '') {
				// jQuery('.hsn_code').val('');
				jQuery('.unit_rate').val(0);
				jQuery('.tx_count').val(0);
				jQuery('.single_amount').val(0);
				//count_total(row_id);
				return false;
			}
			
			var row_id = []; //for get every row id
			i = 0;
			jQuery('.row_id').each(function(){
				var index = jQuery(this).attr('data-id');
				row_id[i++] = jQuery(this).attr('data-id');;
			});
			
			
			var val_arr = []; //for store material_id and brand_id
			a = 0;
			jQuery.each( row_id, function( key, value ) {
				val_arr.push({
					material_id : jQuery("#material_id_"+value).val(),
					brand_id :  jQuery("#brand_id_"+value).val()
				});
			});
			
			var curr_data = {	 						 					
				project_id : project_id,po_date : po_date,vendor_id : vendor_id,val_arr : val_arr
			};
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmultiplematerialrate'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					var json_obj = jQuery.parseJSON(response);
					i = 0;
					jQuery.each( row_id, function( key, value ) {	
						// jQuery('#hsn_code_'+value).val(json_obj[i]['hsn_code']);
						jQuery('#unit_rate_'+value).val(json_obj[i]['unit_price']);
						jQuery('#dc_'+value).val(json_obj[i]['discount']);
						jQuery('#tr_'+value).val(json_obj[i]['transportation']);
						jQuery('#ex_'+value).val(json_obj[i]['gst']);
						jQuery('#other_tax_'+value).val(json_obj[i]['other_tax']);
						jQuery('#single_amount_'+value).val(json_obj[i]['final_rate']);
						count_total(value);
						i++;
					});
				},
				error: function (e) {
					alert('Error');
				}
			});
		});*/
		
		/*jQuery("body").on("blur", "#po_date", function(event){ 
			var project_id  = jQuery("#project_id").val() ;
			var po_date  = jQuery("#po_date").val() ;
			var vendor_id  = jQuery("#vendor_userid").val() ;
			
			if(project_id == '' || po_date == '' || vendor_id == '')
			{
				// jQuery('.hsn_code').val('');
				jQuery('.unit_rate').val(0);
				jQuery('.tx_count').val(0);
				jQuery('.single_amount').val(0);
				//count_total(row_id);
				return false;
			}
			
			var row_id = []; //for get every row id
			i = 0;
			jQuery('.row_id').each(function(){
					var index = jQuery(this).attr('data-id');
					row_id[i++] = jQuery(this).attr('data-id');;
			});
			
			
			var val_arr = []; //for store material_id and brand_id
			a = 0;
			jQuery.each( row_id, function( key, value ) {
				val_arr.push({
					material_id : jQuery("#material_id_"+value).val(),
					brand_id :  jQuery("#brand_id_"+value).val()
				});
			});
			
			var curr_data = {	 						 					
				project_id : project_id,po_date : po_date,vendor_id : vendor_id,val_arr : val_arr
			};
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmultiplematerialrate'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					var json_obj = jQuery.parseJSON(response);
					i = 0;
					jQuery.each( row_id, function( key, value ) {	
						// jQuery('#hsn_code_'+value).val(json_obj[i]['hsn_code']);
						jQuery('#unit_rate_'+value).val(json_obj[i]['unit_price']);
						jQuery('#dc_'+value).val(json_obj[i]['discount']);
						jQuery('#tr_'+value).val(json_obj[i]['transportation']);
						jQuery('#ex_'+value).val(json_obj[i]['gst']);
						jQuery('#other_tax_'+value).val(json_obj[i]['other_tax']);
						jQuery('#single_amount_'+value).val(json_obj[i]['final_rate']);
						count_total(value);
						i++;
					});
				},
				error: function (e) {
					alert('Error');
				}
			});
		});*/

		// Dropdown second time value not selection function
		jQuery("body").on("change", ".material_id", function(event) {
			var material_id  = jQuery(this).val() ;
			var row_id  = jQuery(this).attr('data-id') ;
			var ids = [];
			$('select.material_id').not(this).each(function( index, value ) {
				if(jQuery(this).attr('value') != '') {
					ids.push(jQuery(this).attr('value'));
				}
			});
			if(jQuery.inArray( material_id, "["+ids+"]" ) >  -1){
				alert("You can't select same material again");
				$(this).select2('val', '');
				$("#material_code_"+row_id).html('');
				$("#unit_name_"+row_id).html('');
			}else{
				// alert('not selected');
			}
		});

		// Arrow Fuction for check GST Status and add mode of gst
		checkGstStatus = () => {
			var yashnand_gst_no = $("#gstno").val();
			var party_gst_no = $("#custom_gst").val();
			if(party_gst_no != "" && yashnand_gst_no != "") {
				var party_two_digit = party_gst_no.substr(0, 2);
				var yashnand_two_digit = yashnand_gst_no.substr(0, 2);
				if(jQuery.isNumeric(party_two_digit)) {
					if(party_two_digit == yashnand_two_digit) {
						$("#mode_of_gst").val("CGST+SGST");
						$("#gross_amount_row").show();
					}else{
						$("#mode_of_gst").val("IGST");
						$("#gross_amount_row").show();
					}
				}else {
					$("#mode_of_gst").val("NA");
					$("#gross_amount_row").hide();
				}
			}else if(party_gst_no != "") {
				
			}else {
				$("#mode_of_gst").val("CGST+SGST+IGST");
				$("#gross_amount_row").show();
			}	
		}

		// Check GST status
		$("body").on("change","#vendor_userid",function(){
			checkGstStatus();
		});

		// Total GST count Arrow Function
		countTotalGST = (row_id) => {
			var qty = jQuery('#quantity_'+row_id).val();
			var price = jQuery('#unit_rate_'+row_id).val();			
			if(price == '') {
				price = 0;
			}
			var single_amount = price;
			var dc = parseFloat($("#dc_"+row_id).val());		
			if(dc != '') {			
				dc = parseFloat((100-dc)/100);
				single_amount = parseFloat(price * dc);
			}
			var gst = parseFloat($("#gst_"+row_id).val());		
			if(gst > 0) {
				var gst_count = 1 + parseFloat(gst / 100);
				single_amount = parseFloat(single_amount * gst_count)
			}
			var new_amount = parseFloat(qty*single_amount);
			// alert("New Amount :-"+ new_amount);
			var single_amt = parseFloat(single_amount);
			jQuery('#amount_'+row_id).val(new_amount.toFixed(2));
			jQuery('#single_amount_'+row_id).val(single_amt.toFixed(2));
			var po_sum = 0;
			jQuery('.amount').each(function(){
				var single_po_amount = jQuery(this).val();
				po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
			});
			jQuery('#total_po_amount').html();
			jQuery('#total_po_amount').html(po_sum.toFixed(2));
		};
		
		// function count_total(row_id) {
		// 	var qty = jQuery('#quantity_'+row_id).val();
		// 	var price = jQuery('#unit_rate_'+row_id).val();
		// 	if(price == '') {
		// 		price = 0;
		// 	}
		// 	var single_amount = price;
		// 	var dc = parseFloat($("#dc_"+row_id).val());		
		// 	if(dc != '') {			
		// 		dc = parseFloat((100-dc)/100);
		// 		single_amount = parseFloat(price * dc);
		// 	}
			
		// 	var tr = parseFloat($("#tr_"+row_id).val()); /* CGST */ 
		// 	var ex = parseFloat($("#ex_"+row_id).val()); /* SGST */
		// 	var other = parseFloat($("#other_tax_"+row_id).val()); /* IGST */
		// 	var total_gst = parseFloat(tr + ex + other);
			
		// 	if(total_gst > 0) {
		// 		var gst_count = 1 + parseFloat(total_gst / 100);
		// 		single_amount = parseFloat(single_amount * gst_count)
		// 	}
			
		// 	var new_amount = parseFloat(qty*single_amount);
		// 	var single_amt = parseFloat(single_amount);
			
		// 	jQuery('#amount_'+row_id).val(new_amount.toFixed(2));
		// 	jQuery('#single_amount_'+row_id).val(single_amt.toFixed(2));
			
		// 	var po_sum = 0;
		// 	jQuery('.amount').each(function(){
		// 		var single_po_amount = jQuery(this).val();
		// 		po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
		// 	});
		// 	jQuery('#total_po_amount').html();
		// 	jQuery('#total_po_amount').html(po_sum.toFixed(2));
		// }

		jQuery('body').on('blur','.quantity',function(){
			var row_id = jQuery(this).attr('data-id');
			countTotalGST(row_id); //Arrow function called 
		});

		jQuery("body").on("change",".unit_rate",function(){ 
			var row_id = jQuery(this).attr('data-id');
			countTotalGST(row_id); //Arrow function called
		});
		
		jQuery("body").on("change",".tx_count",function(){
			var row_id = jQuery(this).attr('data-id');
			countTotalGST(row_id); //Arrow function called
		});

		$("body").on("click",".del_parent",function(){
			$(this).parents("tr").remove();
			var po_sum = 0;
			jQuery('.amount').each(function(){
				var single_po_amount = jQuery(this).val();
				po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
			});
			jQuery('#total_po_amount').html();
			jQuery('#total_po_amount').html(po_sum.toFixed(2));
			checkiselectricmaterial();
		});
		
		$("body").on("change",".change_add",function(){
			var id = $(this).val();
			// alert(id);
			if(id == "mp") {
				/*$("#mp_address").css("display","block");
				$("#gj_address").css("display","none");	*/	
				$("#vatno").val("23379109713");
				$("#cstno").val("23379109713");	
			}else {
				/*$("#gj_address").css("display","block");
				$("#mp_address").css("display","none");*/	
				$("#cstno").val("24073404329");
				$("#vatno").val("24073404329");
			}
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
		
		// $("body").on("change","#loading",function(){
			// var check = $(this).attr("checked");		
			// if(check)
			// {
				// $("#show_loading").css("display","none");
			// }else{$("#show_loading").css("display","block");}
		// });
		
		// Check for if any electric material then select approve mail radio with Enable + Dy. Manager (Ele.) 
		checkiselectricmaterial();
		
		jQuery("body").on("change", ".material_id", function(event){
			checkiselectricmaterial();
		});
		
		function checkiselectricmaterial() {
			var material_ids = [];
			$('.material_id').each(function() { 
				material_ids.push( $(this).val() );
			});
			
			var curr_data = {	 						 					
				materials : material_ids,	 					
			};
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'checkiselectricmaterial'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					if(response == 1) {
						$("#enabled_mail").attr('checked',false);
						$("#enableddeputymanager_mail").attr('checked',true);
						$( "#enabled_mail" ).closest( "span" ).removeClass( "checked" );
						$( "#enableddeputymanager_mail" ).closest( "span" ).addClass( "checked" );
					}else {
						$("#enableddeputymanager_mail").attr('checked',false);
						$("#enabled_mail").attr('checked',true);
						$( "#enableddeputymanager_mail" ).closest( "span" ).removeClass( "checked" );
						$( "#enabled_mail" ).closest( "span" ).addClass( "checked" );
					}
				},
				error: function (e) {
					alert('Error');
				}
			});
		}
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
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?>  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Purchase','approvedpr');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" disabled="true" required="true"   style="width: 100%;" name="project_id" id="project_id"  required="true" >
								<option value="">--Select Project--</Option>
								<?php 
								foreach($projects as $retrive_data)
								{
									echo '<option value="'.$retrive_data['project_id'].'" '.(($retrive_data['project_id']==$data["project_id"])?'selected':'').'>'.$retrive_data['project_name'].'</option>';
								}
								?>
								</select>
								<input type="hidden" name="project_id" id="project_id" value="<?php echo $data['project_id']; ?>">
							</div>
                        </div>
						<div class="form-row">
							<div class="col-md-2">Mode of Billing: </div>
                            <div class="col-md-10">
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" id="gujarat" class="bill_mode" value="gujarat" /> Gujarat</label>
                                </div>
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" value="mp" id="mp" class="bill_mode" />Madhya Pradesh</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" value="maharastra" id="maharastra" class="bill_mode" />Maharashtra</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" value="haryana" id="haryana" class="bill_mode" />Haryana</label>
                                </div>
                            </div>
							
						</div>
						
						<div class="form-row">
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
										foreach($agency_list as $retrive_data)
										{
											echo '<option value="'.$retrive_data['id'].'">'.
											$retrive_data['agency_name'].'</option>';
										}
									?>
								</select>
							</div>
							</div>
						</div>
						<!--
						<div class="form-row">
                            <div class="col-md-2">Pending P. R. No:</div>
                            <div class="col-md-4">
								<select class="select2"  style="width: 100%;" name="pr_id" id="pr_id" >
									<option value=''>Select Pending PR.No.</option>
								</select>
							</div>
						</div> -->
						<div class="form-row">
                           <!-- <div class="col-md-2">P.O.No:</div>
                            <div class="col-md-4">
							
								<input type="text" name="po_no" id="po_no" value="" class="form-control" value=""/>
							</div>-->
                        
                            <div class="col-md-2">Date:</div>
                            <div class="col-md-4"><input type="text" name="po_date" id="po_date" 
							value="<?php echo $this->ERPfunction->get_date(date('Y-m-d'));?>" class="form-control" value=""/></div>
							 <div class="col-md-2">Time:</div>
                            <div class="col-md-2"><input type="text" name="po_time" id="po_time" 
							value="<?php echo date('H:i');?>" class="form-control" value=""/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Vendor Name:</div>
                            <div class="col-md-4">
								<?php 
								
								?>
								<select class="select2"  required="true"   style="width: 100%;" name="vendor_userid" id="vendor_userid">
								<option value="">--Select Vendor--</Option>
								<?php 
									foreach($vendor_department as $retrive_data)
								{echo '<option value="'.$retrive_data['user_id'].'">'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';									
									
								}
								?>
								</select>
							</div>
                        
                             <div class="col-md-2">Vendor ID:</div>
                            <div class="col-md-4">
								<input type="text" name="vendor_id" id="vendor_id" value="" class="form-control" value=""/>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Vendor Addresss:</div>
                            <div class="col-md-10">
								<input type="text" name="vendor_address"  id="vendor_address" class="form-control" value=""/>
							</div>
                        </div>
						<div class="form-row">						
                            <div class="col-md-2">PAN No:</div>
							<div class="col-md-4">
								<input type="text" name="custom_pan" id="custom_pan" class="form-control" value=""/>
							</div>
							
                            <div class="col-md-2">GST No:</div>
							<div class="col-md-4">
								<input type="text" name="custom_gst" id="custom_gst" value="" class="form-control"/>
							</div>
						</div>
						<div class="form-row">						
                            <div class="col-md-2">Contact No: (1)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no1" id="contact_no1" class="form-control" value=""/>
							</div>
							
                            <div class="col-md-2">Contact No: (2)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no2" id="contact_no2" value="" class="form-control"/>
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Vendor E-Mail:</div>
                            <div class="col-md-10">
								<input type="text" name="vendor_email"  id="vendor_email" class="form-control" value=""/>
							</div>
                        </div>
						
						 <div class="form-row">
							<div class="col-md-2">Delivery Type: </div>
                            <div class="col-md-4">
                                <div class="radiobox-inline">
                                    <label><input type="radio" checked name="delivery_type" class="delivery_type" value="direct"/> Direct Delivery</label>
                                </div>
                                <div class="radiobox-inline">
                                    <label><input type="radio" name="delivery_type" class="delivery_type" value="via"/> Delivery Via</label>
                                </div>                                                              
                            </div>
							<div id="delivery_project_div" style="display:none;">
							<div class="col-md-2">Delivery Project:</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" name="delivery_project" id="delivery_project">
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
						 </div>
						<div class="form-row">
                            <div class="col-md-2">Place of Delivery:</div>
                            <div class="col-md-10">
								<input type="text" name="vendor_delivery_address" id="vendor_delivery_address" class="form-control" value=""/>
								<input type="hidden" name="project_address" id="project_address" class="form-control" value=""/>
							</div>
                        </div>
						
						<div class="form-row">						
                            <div class="col-md-2">Site Contact No (1)</div>
							<div class="col-md-4">
								<input type="text" name="contact1" id="contact1" class="form-control" value="<?php  echo implode(",",array_unique($contact1)); ?>"/>
							</div>
							
                            <div class="col-md-2">Site Contact No (2)</div>
							<div class="col-md-4">
								<input type="text" name="contact2" id="contact2" value="<?php  echo implode(",",array_unique($contact2)); ?>" class="form-control"/>
							</div>
						</div>
						
						<div class="form-row">
                          <!--  <div class="col-md-2">P. R. No:</div>
                            <div class="col-md-4">
									<?php //$sel_prid = $this->ERPfunction->get_prid_by_prno($data["prno"]);?>
								<?php //echo $this->Form->select("pr_id",$prno_list,["default"=>$new,=array($sel_prid),"style"=>"width: 100%","id"=>"pr_id"])?>
							
							</div>
                        -->
                         <!--   <div class="col-md-2">Contact No: (1)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no1" id="contact_no1" class="form-control" value=""/>
							</div> -->
                        </div> 
						<div class="form-row">
                            <div class="col-md-2">Payment Method:</div>
                            <div class="col-md-4">
								<select class="select2"  style="width: 100%;" name="payment_method" id="payment_method">
									<option value="cheque" selected>Cheque</Option>							
									<option value="cash">Cash</Option>
								</select>
							</div>
							<div class="col-md-2">Mode of GST:</div>
                            <div class="col-md-4">
								<input type="text" readonly="true" name="mode_of_gst" id="mode_of_gst" value="" class="form-control"/>
							</div>
							<!--<div class="col-md-2">Delivery Date:</div>
							 <div class="col-md-4">
							<input type="text" name="delivery_date" id="delivery_date" value="<?php echo date("d")+1 . "-".date("m")."-".date("Y"); ?>" class="form-control delivery_date"/>
							</div>-->
                        </div>
						<!--<div class="form-row">                           
							<div class="col-md-2 text-right">Other Taxes:</div>
                            <div class="col-md-1">
								<input name="other_tax" type="radio" class="othertax" value="CST" sid="a" checked="checked">CST
								<br>
								<input name="other_tax" type="radio" class="othertax" value="VAT" sid="a">VAT
							</div>
							<div class="col-md-1 text-right">
								<input name="other_tax" type="radio" class="othertax" value="GST" sid="a">GST
								<br>
								<input name="other_tax" type="radio" class="othertax" value="" sid="other">Other								
							</div>
							<div class="col-md-2 text-right">
								<br>
								<input id="other_text" class="form-control" style="display:none">
							</div>
						</div>-->
						<div class="form-row" style="overflow:scroll">						
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th rowspan="2" style="display:none;text-align:center;">Material Code</th>
									<th colspan="7" style="text-align:center;">Material / Item</th>
									<!-- <th rowspan="2">Amount (Inclusive All)</th> -->
									<th rowspan="2">Amount (Inclusive All)</th>
									<th rowspan="2">Final Rate<br>(Inclusive All)</th>
									<th rowspan="2">Action</th>
									</tr>
									<tr>
									<th>Description</th>
									<!--<th>HSN Code</th>-->
									<th>Make / Source</th>
									<th>Quantity</th>
									<th>Unit</th>	
									<th>Unit Rate<br>(Rs.)</th>	
									<th>Dis<br>(%)</th>
									<!-- <th>CGST<br>(%)</th>
									<th>SGST<br>(%)</th>
									<th>IGST<br>(%)</th> -->																
									<th>GST<br>(%)</th>
									</tr>
								</thead>
								<tbody>	
								<?php
									if(!empty($data["approved_list"]))
									{ 
									$i = 0;
										foreach($data["approved_list"] as $approve)
										{
											echo "<tr id='row_id_{$i}' class='row_id' data-id='".$i."'>
											<td style='display:none;'><span id='material_code_{$i}'>{$data['mcode_'.$approve.'']}</span>
											<input type='hidden' value='' name='material[m_code][]' id='m_code_{$i}'>
											<input type='hidden' value='{$i}' name='row_number' class='row_number'>
											<input type='hidden' value='0' name='material[is_custom][]'>
											</td>
											
											<td>
											<select class='select2 material_id' style='width:180px;' name='material[material_id][]' id='material_id_{$i}' data-id='".$i."'>
												<option value=''>Select Material</Option>";
												 
										   foreach($material_list as $retrive_data)
										   {
												$selected = ($retrive_data['material_id'] == $this->ERPfunction->get_pr_material_id($approve)) ? "selected" : "";
												 echo '<option value="'.$retrive_data['material_id'].'"'.$selected.'>'.
												 $retrive_data['material_title'].'</option>';
										   }
												
											echo "</select>
											<input type='text' placeholder='Description Here' class='desc_textfield' name='material[description][]' value='' id='descriptionTextfield_{$i}' style='display:none;'>
											</td>
											<td>
											<select class='select2 brand_id'  required='true'   name='material[brand_id][]' style='width:130px;' id='brand_id_{$i}' data-id='".$i."'>";
											
											$brands = $this->ERPfunction->get_brands_by_material_id($this->ERPfunction->get_pr_material_id($approve));
													if($brands != "")
													{
														foreach($brands as $brand)
														{
															echo '<option value="'.$brand['brand_id'].'"'.$this->ERPfunction->selected($brand['brand_id'],$data['brand_id_'.$approve.'']).'>'.$brand['brand_name'].'</option>';
														}
													}
											echo "</select></td>
											
											<td><input name='material[quantity][]' value='".$data['quantity_'.$approve.'']."' id='quantity_{$i}' data-id='{$i}' class='quantity'></td>
											<td><span id='unit_name_{$i}'>{$data['unit_'.$approve.'']}</span>
											<input type='hidden' value='' name='material[static_unit][]' id='static_unit_{$i}'class='form-control' style='width:80px;'>
											</td>
											<td><input name='material[unit_rate][]' value='0' class='unit_rate' data-id='{$i}' id='unit_rate_{$i}' style='width:80px'></td>
											<td><input name='material[discount][]' value='0' class='tx_count' id='dc_{$i}' data-id='{$i}' style='width:55px'></td>
											<td><input name='material[gst][]' value='0' class='tx_count' id='gst_{$i}'  data-id='{$i}' style='width:55px'></td>
											<td><input name='material[amount][]' value='0' class='amount' id='amount_{$i}' style='width:90px'></td>
											<td><input name='material[single_amount][]' value='0' class='single_amount' id='single_amount_{$i}' style='width:90px'/></td>			
											
											<td>
												<a href='javascript:void(0)' class='btn btn-primary add_textfield' onClick='insertRow({$i})' id='textfield_{$i}' value='textfield'>Textfield</a>													
												<a href='#' class='btn btn-danger del_parent'>Delete</a>
											</td>";
											echo "<input type='hidden' name='pr_mid[]' value='".$data['pr_mid_'.$approve.'']."'>
											</tr>";
											$i++;
										}
									}
									?>
								</tbody>
								<tfoot>
								<tr>
		<td colspan="7" class="text-right"><b>Total Amount</b></td>
		<td id="total_po_amount" style="padding-left:24px;">0</td>
		<td></td>
		<td></td>
		</tr>
								</tfoot>
							</table>
                        </div>
						<button type="button" id="add_newrow" class="btn btn-default">Add New </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<div style="display:none;">
						<input name="row_type" type="radio" checked="checked" class="row_type" value="dropdown">Dropdown
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<!-- <input name="row_type" type="radio" class="row_type" value="textfield">Text Field -->
						</div>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="button" id="material_add" data-type="material_add" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal" style="">Add Material </button>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="button" id="brand_add" data-type="brand_add" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default brand_add" style="">Add Brand </button>
						
						<div class="form-row">
                            <div class="col-md-2">Remarks/Note:</div>
                            <div class="col-md-8">
							<p>1) The above mentioned amount includes following: </p>
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" name="taxes_duties"/> All Taxes & Duties</label>
								</div>
							</p>							
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" name="loading_transport" id="loading" /> Loading & Transportation - F. O. R. at Place of Delivery</label>
								</div>
							</p>
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" name="unloading"/>Unloading</label>
								</div>
							</p>
							<p> 
								<div class="checkbox">
									<label><input type="checkbox" value="1" name="warranty_check"/>Replacement Warranty up to</label>
									<input name="warranty" style="width:150px;float:none;display: inline;">
								</div>
								
							</p>
							<!--<p id="show_loading">
							1.1) Loading & Transportation will be Paid Extra Amount (Rs.): <input name="extra_transport"  style="width:150px;float:none;display: inline;" />
							</p>-->
							<!-- <p>2) The above mentioned rate includes Note - 4 f. o. r. above mentioned delivery address. </p> -->
							<p>2) Material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this PO will be considered as void.
							</p>
							<p>3) Manufacturer's Test Certificates are required for each batch of supply.</p>
							<p>4) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance. </p>
							<p>5) If you will not revert back within 48 hrs, this PO will be considered as accepted by you.</p>
							<p>6) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</p>
							<p>7) All disputes subject to Ahmedabad Jurisdiction only.</p>
							<p>8) Party will have to send <strong>Invoice and Purchase Order (PO) along with Material / Item.</strong> Payment will be processed after receiving approval from project authorities, <strong>Goods Receipt Note and/or Weight Pass.</strong></strong></p>
							<!--<p> <u>Select Billing Address Location.</u>
								<ul>
									<li><input type="radio" class="change_add" value="gj" name="bill_address" checked>Gujarat</li>
									<li><input type="radio" class="change_add" value="mp" name="bill_address">Madhya Pradesh</li>
								</ul>
							</p>-->
							<p id="gj_address"><strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</p>
							<p id="mp_address" style="display:none;"><strong>Billing Address:</strong>House No - MF 04/72 MIG,Shivaji Parisar,Nehrunagar, Bhopal,Madhya Pradesh - 462016.</p>
							<p id="mh_address" style="display:none;"><strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</p>
							<p id="haryana_address" style="display:none;"><strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</p>
							<!--<p><strong>Courier Address:</strong> Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.							
							</p>-->
							<p><strong>PAN No.:</strong><span id="pan_no"> AABCY0913A </span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<strong>GST No.:  </strong>
								<input readonly name="gstno" id="gstno" value="24AABCY0913A1Z1"  style="width:160px;float:none;display: inline;"/>									
							
							</p>
							<p>9) YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD. has right to cancel order any time without any prior notice.</p>
							<p>10) Payment will be done <input type="text" name="payment_days" id="payment_days" value="" style="width:60px;float:none;display: inline;" /> days after date of delivery on site or bill submission which ever is later.</p>
							
							
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Remarks/Note</div>
                            <div class="col-md-10"><textarea name="remarks" class="form-control"></textarea></div>
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
                            <div class="col-md-4"><button type="submit" id="preparepo" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
					</div>
				<?php $this->Form->end(); ?>
			</div>
<?php //}?>
         </div>
<script>
	// Add Textbox code
	function insertRow(id){
		// alert("Hllo");
		var id = id;
		$("#descriptionTextfield_"+id).css("display","block");
	}
</script>