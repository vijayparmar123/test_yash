<?php
	use Cake\Routing\Router;

	$taxes_duties = (($erp_inve_po_data['taxes_duties'] == 1)?"checked":"");
	$loading_transport = (($erp_inve_po_data['loading_transport'] == 1)?"checked":"");
	$extra_transport = isset($erp_inve_po_data['extra_transport'])?$erp_inve_po_data['extra_transport']:"";
	$unloading = (($erp_inve_po_data['unloading'] == 1)?"checked":"");
	$warranty = isset($erp_inve_po_data['warranty'])?$erp_inve_po_data['warranty']:"";
	$bill_address = isset($erp_inve_po_data['bill_address'])?$erp_inve_po_data['bill_address']:"";
	$bill_mode = isset($erp_inve_po_data['bill_mode'])?$erp_inve_po_data['bill_mode']:"";
	$gstno = ($erp_inve_po_data['gstno']?$erp_inve_po_data['gstno']:"");
	$vatno = ($erp_inve_po_data['vatno']?$erp_inve_po_data['vatno']:"");
	$cstno = ($erp_inve_po_data['cstno']?$erp_inve_po_data['cstno']:"");
	$payment_days = ($erp_inve_po_data['payment_days']?$erp_inve_po_data['payment_days']:"");
	$remarks = ($erp_inve_po_data['remarks']?$erp_inve_po_data['remarks']:"");
	$delivery_date = ($erp_inve_po_data["delivery_date"] != NULL)?date("d-m-Y",strtotime($erp_inve_po_data["delivery_date"])):date("d")+1 . "-".date("m")."-".date("Y");
	$po_type = $erp_inve_po_data["po_purchase_type"];
// debug($erp_inve_po_data);die;
    $can_update = 0;
		if($erp_inve_po_data->updated == 1 && $erp_inve_po_data->ammend_approve == 0)
		{
			$can_update = 1;
		}else{
			$can_update = 0;
		}
		$this->set('can_update',$can_update);

		if($can_update)
		{
			$po_no = $erp_inve_po_data->po_no;
		}else{
			$old_po = $erp_inve_po_data->po_no;
			$split = explode('/',$old_po);
			$po_no = $split[0].'/'.$split[1].'/'.$split[2].'/'.$split[3].'/'.$split[4].'/'.'Rev1'.'/'.date("d-m-Y");
		}
		// $this->set('wo_no',$po_no);
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
		jQuery("body").on("click","#editpo",function() {
			if(confirm("Are you sure,you want to Ammend PO?")) {
				if(confirm("Are you sure,you want to Ammend PO?")) {
					return true;
				}else {
					return false;
				}
			}else {
				return false;
			}
		});

		usage_name = jQuery("#usage").val();
		if(usage_name == 'for_agency') {
			jQuery("#agency_id").attr('required', true);
		}else if(usage_name == 'for_self') {
			jQuery("#agency_id").attr('required', false);
		}

		var po_sum = 0;
		jQuery('.amount').each(function() {
			var single_po_amount = jQuery(this).val();
			po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
		});
		jQuery('#total_po_amount').html();
		jQuery('#total_po_amount').html(po_sum.toFixed(2));
		jQuery('.poedit_date').datepicker({
			dateFormat: "Y-m-d",
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "-" + year);
			}                    
		});

		jQuery("body").on("change", "#project_id", function(event) {
			var project_id  = jQuery(this).val() ;
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
					jQuery('#po_no').val(json_obj['po_no']);						
					jQuery('#pr_id').append(json_obj['pending_pr']);					
					return false;
                },
                error: function (e) {
                    alert('Error');
                }
            });	
		});

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
					jQuery('#ven_contact_no1').val(json_obj['contact_no1']);												
					jQuery('#ven_contact_no2').val(json_obj['contact_no2']);												
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
			var row_type = jQuery(".row_type:checked").val();
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
			var project_id = $("#project_id").val();
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

		jQuery('body').on('click','.del_parent',function() {
			var detail_id = jQuery(this).attr('data-id');
			if(detail_id) {
				if(confirm('Are you Sure Delete this Material?')) {
					if(confirm('Are you Sure Delete this Material?')) {
						if(confirm('Are you Sure Delete this Material?')) {
							var curr_data = {	 						 					
								detail_id : detail_id,	 					
							};	 				
							jQuery.ajax({
									headers: {
										'X-CSRF-Token': csrfToken
									},
									type:"POST",
									url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'deletepodetail'));?>",
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
			var po_sum = 0;
			jQuery('.amount').each(function(){
				var single_po_amount = jQuery(this).val();
				po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
			});
			jQuery('#total_po_amount').html();
			jQuery('#total_po_amount').html(po_sum.toFixed(2));
			checkiselectricmaterial();
			return false;
		});

		jQuery('body').on('blur','.quantity',function() { 			
			var row_id = jQuery(this).attr('data-id');
			countTotalGST(row_id);
		});

		jQuery('body').on('blur','.unit_rate',function() { 			
			var row_id = jQuery(this).attr('data-id');
			countTotalGST(row_id);
		});

		jQuery("body").on("change",".tx_count",function() {
			var row_id = jQuery(this).attr('data-id');
			countTotalGST(row_id);
		});

		$("body").on("change",".change_add",function() {
			var id = $(this).val();
			if(id == "mp") {
				$("#mp_address").css("display","block");
				$("#gj_address").css("display","none");
			}else {
				$("#gj_address").css("display","block");
				$("#mp_address").css("display","none");
			}
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

		jQuery("body").on("change", "#usage", function() {
			usage_name = jQuery(this).val();
			if(usage_name == 'for_agency') {
				jQuery("#agency_div").show();
				jQuery("#agency_id").attr('required', true);
			}else if(usage_name == 'for_self') {
				jQuery("#agency_div").hide();
				jQuery("#agency_id").attr('required', false);
			}
		});

		jQuery("body").on("change", ".delivery_type", function() {
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
					return false;
				},
				error: (e) => {
					alert('Error');
				}
			});	
		};

		// Arrow Function called
		getOPtionValue();

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

		jQuery("body").on("change", "#delivery_project", function() {
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

		jQuery("body").on("change", ".bill_mode", function() {
				var state  = jQuery(this).val() ;
				if(state == 'gujarat') {
					$("#gj_address").css("display","block");
					$("#mp_address").css("display","none");
					$("#mh_address").css("display","none");
					$("#haryana_address").css("display","none");
				}else if(state == 'mp') {
					$("#mp_address").css("display","block");
					$("#gj_address").css("display","none");
					$("#mh_address").css("display","none");
					$("#haryana_address").css("display","none");
				}else if(state == 'maharastra') {
					$("#gj_address").css("display","none");
					$("#mp_address").css("display","none");
					$("#mh_address").css("display","block");
					$("#haryana_address").css("display","none");
				}else if(state == 'haryana') {
					$("#gj_address").css("display","none");
					$("#mp_address").css("display","none");
					$("#mh_address").css("display","none");
					$("#haryana_address").css("display","block");
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
					success: function(response) {						
						jQuery('#gstno').val(response);	
						return false;
					},
					error: function (e) {
						alert('Error');
						console.log(e.responseText);
					}
				});
			});

			jQuery("body").on("change", ".bill_mode", function() {
				var state  = jQuery(this).val();	
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
						jQuery('#pan_no').html(response);	
						return false;
					},
					error: function (e) {
						alert('Error');
						console.log(e.responseText);
					}
				});
			});
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
					if(response == 1)
					{
						$("#enabled_mail").attr('checked',false);
						$("#enableddeputymanager_mail").attr('checked',true);

						$( "#enabled_mail" ).closest( "span" ).removeClass( "checked" );
						$( "#enableddeputymanager_mail" ).closest( "span" ).addClass( "checked" );
					}else{
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
						<h2><?php echo $form_header;?>  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link("Purchase","viewporecords");?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	

					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $this->ERPfunction->get_projectcode($erp_inve_po_data['project_id']);?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true" disabled style="width: 100%;" name="project_id" id="project_id">
								<?php 
									foreach($projects as $retrive_data) {
										if($erp_inve_po_data['project_id'] == $retrive_data['project_id'])
										{
											echo '<option value="'.$retrive_data['project_id'].'" '.(($erp_inve_po_data['project_id'] == $retrive_data['project_id'])?'selected':'').'>
											 '.$retrive_data['project_name'].'</option>';
										}	
									}
								?>
								</select>
                                <input type="hidden" name="project_id" value="<?php echo $erp_inve_po_data['project_id']; ?>">
							</div>
                        </div>						
						<div class="form-row">
							<div class="col-md-2">Mode of Billing: </div>
                            <div class="col-md-10">
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" class="bill_mode" id="gujarat" value="gujarat" <?php echo ($erp_inve_po_data['bill_mode'] == 'gujarat')?'checked':''; ?> /> Gujarat
									</label>
                                </div>
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" value="mp" id="mp" class="bill_mode" <?php echo ($erp_inve_po_data['bill_mode'] == 'mp')?'checked':''; ?> />Madhya Pradesh</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" value="maharastra" id="maharastra" class="bill_mode" <?php echo ($erp_inve_po_data['bill_mode'] == 'maharastra')?'checked':''; ?> />Maharastra</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="bill_mode" value="haryana" id="haryana" class="bill_mode" <?php echo ($erp_inve_po_data['bill_mode'] == 'haryana')?'checked':''; ?> />Haryana</label>
                                </div>
                            </div>

						</div>
						<div class="form-row">
                            <div class="col-md-2">Usage:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true" name="usage_name" style="width:100%;" id="usage">
									<option value="for_self" <?php echo ($erp_inve_po_data['usage_name'] == 'for_self')?'selected':''; ?> >For Self Use</Option>							
									<option value="for_agency" <?php echo ($erp_inve_po_data['usage_name'] == 'for_agency')?'selected':''; ?>>For Agency</Option>									
								</select>
							</div>

							<div id="agency_div" style="display:<?php echo ($erp_inve_po_data['usage_name'] == 'for_agency')?'':'none'; ?>"> 
							<div class="col-md-2">Debit from Agency:</div>
                            <div class="col-md-4">
								<select class="select2 agency_id" style="width: 100%;" name="agency_id" id="agency_id">
									<option value="">--Select Agency--</Option>
									<?php 
										foreach($agency_list as $retrive_data)
										{
											$selected = ($retrive_data['id'] == $erp_inve_po_data['agency_id'])?'selected':'';
											echo '<option value="'.$retrive_data['id'].'"'.$selected.'>'.
											$retrive_data['agency_name'].'</option>';
										}
									?>
								</select>
							</div>
							</div>
						</div>

						<div class="form-row">
                            <div class="col-md-2">P.O.No:</div>
                            <div class="col-md-4">							
								<input type="text" readonly name="po_no" id="po_no" value="<?php echo $po_no;?>" class="form-control" value=""/>
							</div>

                            <div class="col-md-1">Date:</div>
                            <div class="col-md-2"><input type="text" readonly="true" name="po_date" id="po_date" 
							value="<?php echo date('d-m-Y',strtotime($erp_inve_po_data["po_date"]));?>" class="form-control" value=""/></div>
							 <div class="col-md-1">Time:</div>
                            <div class="col-md-2"><input type="text" readonly name="po_time" id="po_time" value="<?php echo $erp_inve_po_data["po_time"];?>" class="form-control" value=""/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Vendor Name:</div>
                            <div class="col-md-4">
								<?php 

								?>
								<select class="select2" disabled  required="true" style="width: 100%;" name="vendor_userid" id="vendor_userid">
								<option value="">--Select Vendor--</Option>
								<?php 
									foreach($vendor_department as $retrive_data)
								{echo '<option value="'.$retrive_data['user_id'].'" '.(($erp_inve_po_data["vendor_userid"] == $retrive_data["user_id"])?"selected":"").'>'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';									

								}
								?>
								</select>
                                <input type="hidden" name="vendor_userid" value="<?php echo $erp_inve_po_data["vendor_userid"]; ?>">
							</div>

                             <div class="col-md-2">Vendor ID: </div>
                            <div class="col-md-4">
								<input type="text" name="vendor_id" id="vendor_id" class="form-control" value="<?php echo $erp_inve_po_data["vendor_id"]; ?>" readonly="true" />
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Vendor Addresss:</div>
                            <div class="col-md-8">
								<input type="text" name="vendor_address"  id="vendor_address" class="form-control" value="<?php echo $erp_inve_po_data["vendor_address"]; ?>" readonly="true" />
							</div>
                        </div>
						<div class="form-row">						
                            <div class="col-md-2">PAN No:</div>
							<div class="col-md-4">
								<input type="text" name="custom_pan" id="custom_pan" class="form-control" value="<?php echo $this->ERPfunction->get_vendor_detail($erp_inve_po_data['vendor_userid'],'pancard_no');?>" readonly="true" />
							</div>

                            <div class="col-md-2">GST No:</div>
							<div class="col-md-4">
								<input type="text" name="custom_gst" id="custom_gst" value="<?php echo $this->ERPfunction->get_vendor_detail($erp_inve_po_data['vendor_userid'],'gst_no'); ?>" class="form-control" readonly="true" />
							</div>
						</div>
						<div class="form-row">						
                            <div class="col-md-2">Contact No: (1)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no1" id="ven_contact_no1" class="form-control" value="<?php echo $this->ERPfunction->get_vendor_contact($erp_inve_po_data["vendor_userid"],"one");?>" readonly="true" />
							</div>

                            <div class="col-md-2">Contact No: (2)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no2" id="ven_contact_no2" value="<?php echo $this->ERPfunction->get_vendor_contact($erp_inve_po_data["vendor_userid"],"two");?>" class="form-control" readonly="true" />
							</div>
						</div>

						<div class="form-row">
                            <div class="col-md-2">Vendor E-Mail:</div>
                            <div class="col-md-10">
								<input type="text" name="vendor_email"  id="vendor_email" class="form-control" 
								value="<?php echo $erp_inve_po_data["vendor_email"]; ?>" readonly="true" />
							</div>
                        </div>

						 <div class="form-row">
							<div class="col-md-2">Delivery Type: </div>
                            <div class="col-md-4">
                                <div class="radiobox-inline">
                                    <label><input type="radio"  name="delivery_type" class="delivery_type" value="direct" <?php echo ($erp_inve_po_data['delivery_type'] == 'direct')?'checked':''; ?>/> Direct Delivery</label>
                                </div>
                                <div class="radiobox-inline">
                                    <label><input type="radio" name="delivery_type" class="delivery_type" value="via" 
									<?php echo ($erp_inve_po_data['delivery_type'] == 'via')?'checked':''; ?>/> Delivery Via</label>
                                </div>                                                              
                            </div>

							<div id="delivery_project_div" style="display:<?php echo ($erp_inve_po_data['delivery_type'] == 'via')?'':'none'; ?>">
							<div class="col-md-2">Delivery Project:</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" name="delivery_project" id="delivery_project">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										$selected = ($retrive_data['project_id']==$erp_inve_po_data['delivery_project'] )?'selected':'';
										echo '<option value="'.$retrive_data['project_id'].'"'.$selected.'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
							</div>

						 </div>

						<div class="form-row">
                            <div class="col-md-2">Place of Delivery:</div>
                            <div class="col-md-8">
								<input type="text" name="vendor_delivery_address" id="vendor_delivery_address" class="form-control" value="<?php echo $this->ERPfunction->get_projectaddress(($erp_inve_po_data['delivery_type'] == 'via')?$erp_inve_po_data["delivery_project"]:$erp_inve_po_data["project_id"]); ?>" readonly="true" />
								<input type="hidden" name="project_address" id="project_address" class="form-control" value="<?php echo $this->ERPfunction->get_projectaddress($erp_inve_po_data["project_id"]); ?>"/>
							</div>
                        </div>
						<div class="form-row">

                            <div class="col-md-2">Contact No: (1)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no1" id="contact_no1" class="form-control" value="<?php echo $erp_inve_po_data["contact_no1"]; ?>" readonly="true" />
							</div>
							<div class="col-md-2">Contact No: (2)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no2" id="contact_no2" value="<?php echo $erp_inve_po_data["contact_no2"]; ?>" class="form-control" readonly="true" />
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Payment Method:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="payment_method" id="payment_method">
									<option value="">--Select Payment Method--</Option>
									<option value="cash" <?php echo ($erp_inve_po_data["payment_method"] == "cash")?"selected":"";?>>Cash</Option>
									<option value="cheque" <?php echo ($erp_inve_po_data["payment_method"] == "cheque")?"selected":"";?>>Cheque</Option>							
								</select>
							</div>
							<div class="col-md-2">Mode of GST:</div>
                            <div class="col-md-4">
								<input type="text" readonly="true" name="mode_of_gst" id="mode_of_gst" value="<?php echo $erp_inve_po_data['mode_of_gst']; ?>" class="form-control"/>
							</div>

						<div class="form-row" style="padding:15px 0 0 0;overflow:scroll">						
                            <table class="table table-bordered">
								<thead>
									<tr>
										<th rowspan="2" style="display:none;">Material Code</th>
										<th colspan="7">Material / Item</th>
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
										<th>GST<br>(%)</th>
									</tr>
								</thead>
								<tbody>

								<?php 
									echo $row;
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

						<button type="button" id="add_newrow" class="btn btn-default" style="float:left;">Add New </button>
						<div style="<?php echo ($po_type == "po")?'display:none':''; ?>;float:left;margin-top:6px;">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="row_type" type="radio" checked="checked" class="row_type" value="dropdown">Dropdown
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
						<div class="form-row" style="padding:15px 0 0 0;">
                            <div class="col-md-2">Remarks/Note:</div>
                            <div class="col-md-8">
							<p>1) The above mentioned Amount includes following: </p>
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" name="taxes_duties" <?php echo $taxes_duties;?>/> All Taxes & Duties</label>
								</div>
							</p>
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" id="loading"  name="loading_transport" <?php echo $loading_transport;?>/> Loading & Transportation - F. O. R. at Place of Delivery</label>
								</div>
							</p>
							<p> 
								<div class="checkbox">
										<label><input type="checkbox" value="1" name="unloading" <?php echo $unloading;?>/>Unloading</label>
								</div>
							</p>
							<p> 
								<div class="checkbox">
									<label><input type="checkbox" value="1" name="warranty_check" <?php echo ($warranty != "")?"checked":"";?>/>Replacement Warrenty up to</label>
									<input name="warranty" style="width:150px;float:none;display: inline;" value="<?php echo $warranty;?>">
								</div>

							</p>
							<p>2) Material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this PO will be considered as void.
							</p>
							<p>3) Manufacturer's Test Certificates are required for each batch of supply.</p>
							<p>4) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance. </p>
							<p>5) If you will not revert back within 48 hrs, this PO will be considered as accepted by you.</p>
							<p>6) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</p>
							<p>7) All disputes subject to Ahmedabad Jurisdiction only.</p>
							<p>8) Party will have to send <strong>Invoice and Purchase Order (PO) along with Material / Item.</strong> Payment will be processed after receiving approval from project authorities, <strong>Goods Receipt Note and/or Weight Pass.</strong></p>
							<p id="gj_address" <?php echo ($bill_mode == "gujarat")?'':'style="display:none;"';?>><strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</p>
							<p id="mp_address" <?php echo ($bill_mode == "mp")?'':'style="display:none;"';?>><strong>Billing Address:</strong><?php echo $this->ERPfunction->getmpbilladdress($erp_inve_po_data["po_date"]); ?></p>
							<p id="mh_address" <?php echo ($bill_mode == "maharastra")?'':'style="display:none;"';?>><strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</p>
							<p id="haryana_address" <?php echo ($bill_mode == "haryana")?'':'style="display:none;"';?>><strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</p>
							<p><strong>PAN No.:</strong><span id="pan_no"><?php echo $this->ERPfunction->getstatepanno($bill_mode,$erp_inve_po_data["po_date"]); ?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<strong>GST No.:  </strong>
								<input readonly name="gstno" id="gstno" value="<?php echo $gstno;?>"  style="width:150px;float:none;display: inline;"/>
							</p>
							<p>9) <?php echo $this->ERPfunction->getconditionofpowo($erp_inve_po_data["po_date"],$bill_mode); ?></p>
							<p>10) Payment will be done <input type="text" name="payment_days" id="payment_days" value="<?php echo $payment_days;?>" style="width:60px;float:none;display: inline;" /> days after date of delivery on site or bill submission which ever is later.</p>


							</div>
                        </div>
						 <div class="form-row">
                            <div class="col-md-2">Remarks/Note</div>
                            <div class="col-md-10"><textarea name="remarks" class="form-control"><?php echo $remarks; ?></textarea></div>
                        </div>

						<div class="form-row">
							<div class="col-md-2">Approve Mail </div>
                            <div class="col-md-10">
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="mail_check" class="mail_check" value="1" id="enabled_mail" <?php echo ($erp_inve_po_data['mail_check'] == 1)?'checked':''; ?>/> Enable</label>
                                </div>
                                <div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="mail_check" value="0" class="mail_check" id="disabled_mail" <?php echo ($erp_inve_po_data['mail_check'] == 0)?'checked':''; ?> />Disable</label>
                                </div>
								<div class="radiobox-inline" style="padding:0 50px;">
                                    <label><input type="radio" name="mail_check" value="2" class="mail_check" id="enableddeputymanager_mail" <?php echo ($erp_inve_po_data['mail_check'] == 2)?'checked':''; ?>/>Enable + Dy. Manager (Ele.)</label>
                                </div>
                            </div>
						</div>

						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" id="editpo" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
					</div>
				<?php $this->Form->end(); ?>
				<input type="hidden" value="<?php echo $po_id;?>" id="poid">
			</div>
			</div>
<?php //}?>
         </div>
<script>

	// Add Textbox code
	var index = 1;
	function insertRow(){
		$(".desc_textfield").css("display","block");
	}
</script> 