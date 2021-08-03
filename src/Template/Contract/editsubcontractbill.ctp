<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	var type_of_bill = $("#type_of_bill").val();
	if(type_of_bill == "Labour with Material")
	{
		$('.labour_with_material_hide').hide();
		$('.labour_with_material_show').show();
		// $('.auto_decrease_colspan').attr( 'colspan','1');
		$( '.auto_decrease_colspan' ).each(function( index ) {
			var current_colspan = $(this).attr('colspan');
			var new_colspan = current_colspan - 2;
			$(this).attr( 'colspan',new_colspan);
		});
	}
	
	checkGstStatus();
	
	jQuery('#user_form').validationEngine();
	jQuery('#bill_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
	jQuery("body").on("change", ".description", function(event){
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
						jQuery("select.description option[value='"+cat_id+"']").remove();
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
  
	jQuery('.add_option').click(function(){
			var project_id = $("#project_id").val();
			if(!project_id)
			{
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
		/* alert(category_name + ' ' + model);
		return false; */
		if(category_name != "" && unit != "")
		{
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
					/* //alert(category_name + ' ' + model + ' ' + response);
		//return false; */
                     var json_obj = jQuery.parseJSON(response);					
						jQuery('.modal .table').append(json_obj[0]);
						jQuery('#category_name').val("");						
						jQuery("select.description").append(json_obj[1]);
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
		
	jQuery("body").on("change", "#party_id, #project_id", function () {
		getWONumber();
	});
	
	function getWONumber() {
		var party_id = $("#party_id").val();
		var project_id = $("#project_id").val();
		if(party_id >= 1 && project_id >= 1)
		{
			$("#wo_no").html('');
			var curr_data = {	 						 					
							party_id : party_id,
							project_id : project_id
	 					};
			jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getprojectpartywisewo'));?>",
						data:curr_data,
						async:false,
						success: function(response){
							var josn_object = jQuery.parseJSON(response);
							$("#wo_no").append(josn_object);
						},
						error: function (e) {
							 alert('Error');
							 console.log(e.responseText);
						}
			});
		}
	}
	
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
		//var file_id = jQuery(this).attr('id');
		var ext = $(this).val().split('.').pop().toLowerCase();
		//Extension Check
		if($.inArray(ext, ['pdf','csv','png','jpg','jpeg','xls','xlsx']) == -1) {
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
		if(state == 'gujarat')
		{
			$(".gj_address").css("display","block");
			$(".mp_address").css("display","none");
			$(".mh_address").css("display","none");
		}
		else if(state == 'mp')
		{
			$(".mp_address").css("display","block");
			$(".gj_address").css("display","none");
			$(".mh_address").css("display","none");
		}
		else if(state == 'maharastra')
		{
			$(".gj_address").css("display","none");
			$(".mp_address").css("display","none");
			$(".mh_address").css("display","block");
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
	
	jQuery("body").on("change", "#party_id", function(event){
		jQuery('#party_identy').val("");						
		jQuery('#party_address').val("");												
		jQuery('#party_no1').val("");												
		jQuery('#party_no2').val("");												
		jQuery('#party_email').val("");												
		jQuery('#party_pan_no').val("");												
		jQuery('#party_gst_no').val("");
					
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
	
	jQuery("#add_newrow").click(function(){
		var project_id = $("#project_id").val();
		if(!project_id)
		{
			alert('Please select project first');
			return false;
		}		
		var row_len = jQuery(".row_number").length;
		if(row_len > 0)
		{
			var arr = [];
			$( ".row_number" ).each(function() {
				var m = $(this).val();
				arr.push(m);
			});
			var num = Math.max.apply(Math,arr);
			var row_id = parseInt(num) + 1;
		}
		else
		{
			var row_id = 0;
		}
		
		jQuery.ajax({
                     type: 'POST',
                     url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowsubcontract"]);?>',
                     data : {row_id:row_id,project_id:project_id},
                     success: function (response)
                        {	
                            jQuery("#new_record_data").append(response);
							jQuery('select.select2').select2();
							return false;
						},
                    error: function(e) {
					alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
       });
	});
		
    $("#bill_from_date").datepicker({ 
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy" 
	}).bind("change",function(){
            var minValue = $(this).val();
            minValue = $.datepicker.parseDate("dd-mm-yy", minValue);
            minValue.setDate(minValue.getDate()+1);
            $("#bill_to_date").datepicker( "option", "minDate", minValue );
    })
    
	jQuery('#bill_to_date').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy",
	});
	var minValue = $("#bill_from_date").val();
    minValue = $.datepicker.parseDate("dd-mm-yy", minValue);
    minValue.setDate(minValue.getDate()+1);
	$("#bill_to_date").datepicker( "option", "minDate", minValue );
	   
	function count_total(row_id)
	{
		var qty = jQuery('#quantity_'+row_id).val();
		var price = jQuery('#unit_rate_'+row_id).val();
		
		var single_amount = price;
		
		var dc = parseFloat($("#dc_"+row_id).val());	
		if(dc != '')
		{			
			dc = parseFloat((100-dc)/100);
			single_amount = parseFloat(price * dc);
		}
		
		var cgst = parseFloat($("#cgst_"+row_id).val()); /* CGST */ 
		var sgst = parseFloat($("#sgst_"+row_id).val()); /* SGST */
		var igst = parseFloat($("#igst_"+row_id).val()); /* IGST */
		var total_gst = parseFloat(cgst + sgst + igst);
		
		if(total_gst > 0)
		{
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
	
	$("body").on("click",".del_parent",function(){
		
		$(this).parents("tr").remove();
		countAllRowAmount();
							
		// var detail_id = jQuery(this).attr('detail-id');
		// if(detail_id)
		// {
			// if(confirm('Are you Sure Delete this Record?'))
				// {
					// if(confirm('Are you Sure Delete this Record?'))
					// {
						// if(confirm('Are you Sure Delete this Record?'))
						// {
							// var curr_data = {	 						 					
								// detail_id : detail_id,	 					
								// };	 				
							// jQuery.ajax({
								// headers: {
			// 	'X-CSRF-Token': csrfToken
			// },
            //     type:"POST",
								// url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'deletesubcontractdetail'));?>",
								// data:curr_data,
								// async:false,
								// success: function(response){
									
								// },
								// error: function (e) {
									 // alert('Error');
								// }
							// });
							// $(this).parents("tr").remove();
							// countAllRowAmount();
						// }
					// }
				// }
		// }
		// else
		// {
			// $(this).parents("tr").remove();
		// }
	});
	
	// On row input change
	$("body").on("change",".quantity_this_bill, .quantity_previous_bill, .quantity_till_date, .rate, .amount_previous_bill, .amount_till_date",function(){
		var row_id = $(this).attr("data-id");
		var this_value = $(this).val();
		if(jQuery.isNumeric(this_value))
		{
			countSingleRowAmount(row_id);
		}else{
			alert("Please enter numeric value");
			return false;
		}
	});
	
	// On footer input change
	$("body").on("change",".debit_this_bill, .debit_previous_bill, .debit_till_date, .debit_till_date_labour, .reconciliation_this_bill, .reconciliation_previous_bill, .reconciliation_till_date, .reconciliation_till_date_labour, .material_advance, .retention_percentage",function(){
		var this_value = $(this).val();
		if(jQuery.isNumeric(this_value))
		{
			countNetAmount();
		}else{
			alert("Please enter numeric value");
			return false;
		}
	});
	
	// On GST input change
	$("body").on("change",".cgst_percentage, .sgst_percentage, .igst_percentage",function(){
		var this_value = $(this).val();
		countNetAmount();
	});
	
	/* Calculation for single row of table */
	function countSingleRowAmount(row_id)
	{
		var quantity_this_bill = $("#quantity_this_bill_"+row_id).val();
		var quantity_previous_bill = $("#quantity_previous_bill_"+row_id).val();

		if(jQuery.isNumeric(quantity_this_bill) && jQuery.isNumeric(quantity_previous_bill))
		{
			var quantity_till_date = parseFloat(quantity_this_bill) + parseFloat(quantity_previous_bill);
			$("#quantity_till_date_"+row_id).val(quantity_till_date.toFixed(2));
		}

		var rate = $("#rate_"+row_id).val();
		var quantity_till_date = $("#quantity_till_date_"+row_id).val();
		if(jQuery.isNumeric(quantity_till_date) && jQuery.isNumeric(rate))
		{
		  var amount_till_date = parseFloat(quantity_till_date) * parseFloat(rate);
			$("#amount_till_date_"+row_id).val(amount_till_date.toFixed(2));
		}

		var amount_till_date = $("#amount_till_date_"+row_id).val();
		var amount_previous_bill = $("#amount_previous_bill_"+row_id).val();

		if(jQuery.isNumeric(amount_till_date) && jQuery.isNumeric(amount_previous_bill))
		{
			var amount_this_bill = parseFloat(amount_till_date) - parseFloat(amount_previous_bill);
			$("#amount_this_bill_"+row_id).val(amount_this_bill.toFixed(2));
		}
		
		countNetAmount()
	}
	/* Calculation for single row of table */
	
	/* Calculation for all row of table */
	// countAllRowAmount();
	function countAllRowAmount()
	{
		$( ".row_number" ).each(function() {
		  row_id = $( this ).val();
		  var quantity_this_bill = $("#quantity_this_bill_"+row_id).val();
		  var quantity_previous_bill = $("#quantity_previous_bill_"+row_id).val();
		  
		  if(jQuery.isNumeric(quantity_this_bill) && jQuery.isNumeric(quantity_previous_bill))
		  {
			var quantity_till_date = parseFloat(quantity_this_bill) + parseFloat(quantity_previous_bill);
			$("#quantity_till_date_"+row_id).val(quantity_till_date.toFixed(2));
		  }
		  
		  var rate = $("#rate_"+row_id).val();
		  if(jQuery.isNumeric(quantity_this_bill) && jQuery.isNumeric(rate))
		  {
			  var amount_this_bill = parseFloat(quantity_this_bill) * parseFloat(rate);
			$("#amount_this_bill_"+row_id).val(amount_this_bill.toFixed(2));
		  }
		  
		  var amount_this_bill = $("#amount_this_bill_"+row_id).val();
		  var amount_previous_bill = $("#amount_previous_bill_"+row_id).val();
		  
		  if(jQuery.isNumeric(amount_this_bill) && jQuery.isNumeric(amount_previous_bill))
		  {
			var amount_till_date = parseFloat(amount_this_bill) + parseFloat(amount_previous_bill);
			$("#amount_till_date_"+row_id).val(amount_till_date.toFixed(2));
		  }
		});
		
		countNetAmount()
	}
	/* Calculation for all row of table */
	
	/* Calculation for net amount of table */
	function countNetAmount()
	{
		// Debit Note Count
		var type_of_bill = $('#type_of_bill').val();
		if(type_of_bill == "Labour with Material")
		{
			var grand_total = 0;
			//Count sum c
			var sum_amount_till_date = 0;
			$( ".amount_till_date" ).each(function() {
				var this_value = $(this).val();
				if(jQuery.isNumeric(this_value))
				{
					sum_amount_till_date += parseFloat(this_value);
				}
			});

			if(jQuery.isNumeric(sum_amount_till_date))
			{
				grand_total = parseFloat(sum_amount_till_date) + parseFloat(grand_total);
			}

			var debit_till_date = $(".debit_till_date_labour").val();

			if(jQuery.isNumeric(debit_till_date))
			{
				grand_total = parseFloat(grand_total) - parseFloat(debit_till_date);
			}

			var reconciliation_till_date = $(".reconciliation_till_date_labour").val();
			if(jQuery.isNumeric(reconciliation_till_date))
			{
				grand_total = parseFloat(grand_total) - parseFloat(reconciliation_till_date);
			}
			
			$(".sum_c").html(grand_total.toFixed(2));
			$("#sum_c_0").val(grand_total.toFixed(2));

			var amount_till_date_labour = 0;
			var material_advance = $(".material_advance").val();
			if(jQuery.isNumeric(material_advance))
			{
				var amount_till_date_labour = parseFloat(material_advance) + parseFloat(grand_total);
			}

			$(".amount_till_date_labour").html(amount_till_date_labour.toFixed(2));
			$("#amount_till_date_labour").val(amount_till_date_labour.toFixed(2));

			var this_bill_amount = 0;
			var amount_upto_previous_labour = $("#amount_upto_previous_labour").val();
			if(jQuery.isNumeric(amount_upto_previous_labour))
			{
				var this_bill_amount = parseFloat(amount_till_date_labour) - parseFloat(amount_upto_previous_labour);
			}

			$(".this_bill_amount").html(this_bill_amount.toFixed(2));
			$(".this_bill_amount").val(this_bill_amount.toFixed(2));

			//CGST Count
			var this_bill_total = $(".this_bill_amount").val();
			var cgst_percentage = $(".cgst_percentage").val();
			if(jQuery.isNumeric(this_bill_total) && jQuery.isNumeric(cgst_percentage) && cgst_percentage != "")
			{
				var cgst_amount = (parseFloat(this_bill_total) * parseFloat(cgst_percentage)) / 100;
				$(".cgst").val(cgst_amount.toFixed(2));
				$(".cgst").html(cgst_amount.toFixed(2));
			}else{
				$(".cgst").val(0.00);
				$(".cgst").html(0.00);
			}
			
			//SGST Count
			var this_bill_total = $(".this_bill_amount").val();
			var sgst_percentage = $(".sgst_percentage").val();
			if(jQuery.isNumeric(this_bill_total) && jQuery.isNumeric(sgst_percentage) && sgst_percentage != "")
			{
				var sgst_amount = (parseFloat(this_bill_total) * parseFloat(sgst_percentage)) / 100;
				$(".sgst").val(sgst_amount.toFixed(2));
				$(".sgst").html(sgst_amount.toFixed(2));
			}else{
				$(".sgst").val(0.00);
				$(".sgst").html(0.00);
			}
			
			//IGST Count
			var this_bill_total = $(".this_bill_amount").val();
			var igst_percentage = $(".igst_percentage").val();
			if(jQuery.isNumeric(this_bill_total) && jQuery.isNumeric(igst_percentage) && igst_percentage != "")
			{
				var igst_amount = (parseFloat(this_bill_total) * parseFloat(igst_percentage)) / 100;
				$(".igst").val(igst_amount.toFixed(2));
				$(".igst").html(igst_amount.toFixed(2));
			}else{
				$(".igst").val(0.00);
				$(".igst").html(0.00);
			}
			
			//Gross Amount Count
			var this_bill_val = $(".this_bill_amount").val();
			var cgst_val = $(".cgst").val();
			var sgst_val = $(".sgst").val();
			var igst_val = $(".igst").val();
			
			var gross_amount = parseFloat(this_bill_val);
			
			// if(jQuery.isNumeric(this_bill_val) && jQuery.isNumeric(cgst_val) && jQuery.isNumeric(sgst_val) && jQuery.isNumeric(igst_val))
			// {
				// var gross_amount = parseFloat(this_bill_val) + parseFloat(cgst_val) + parseFloat(sgst_val) + parseFloat(igst_val);
			// }
			
			if(jQuery.isNumeric(cgst_val))
			{
				gross_amount += parseFloat(cgst_val);
			}
			
			if(jQuery.isNumeric(sgst_val))
			{
				gross_amount += parseFloat(sgst_val);
			}
			
			if(jQuery.isNumeric(igst_val))
			{
				gross_amount += parseFloat(igst_val);
			}
			
			$(".gross_amount").val(gross_amount.toFixed(2));
			$(".gross_amount").html(gross_amount.toFixed(2));
			
			//RETENTION MONEY COUNT
			var this_bill_total = $(".this_bill_amount").val();
			var retention_percentage = $(".retention_percentage").val();
			if(jQuery.isNumeric(this_bill_total) && jQuery.isNumeric(retention_percentage))
			{
				var retention_amount = (parseFloat(this_bill_total) * parseFloat(retention_percentage)) / 100;
				$(".retention_money").val(retention_amount.toFixed(2));
				$(".retention_money").html(retention_amount.toFixed(2));
			}
			
			//Net Amount Count
			var this_bill = $(".this_bill_amount").val();
			var cgst_val = $(".cgst").val();
			var sgst_val = $(".sgst").val();
			var igst_val = $(".igst").val();
			
			var gross_amount_val = parseFloat(this_bill) + parseFloat(cgst_val) + parseFloat(sgst_val) + parseFloat(igst_val);
			var retention_money_val = $(".retention_money").val();
			var net_amount = parseFloat(gross_amount_val);
			
			if(jQuery.isNumeric(gross_amount_val) && jQuery.isNumeric(retention_money_val))
			{
				net_amount -= parseFloat(retention_money_val);
			}
			$(".net_amount").val(net_amount.toFixed(2));
			$(".net_amount").html(net_amount.toFixed(2));

		}else{
			var debit_this_bill = $(".debit_this_bill").val();
			var debit_previous_bill = $(".debit_previous_bill").val();

			if(jQuery.isNumeric(debit_this_bill) && jQuery.isNumeric(debit_previous_bill))
			{
				var debit_till_date = parseFloat(debit_this_bill) + parseFloat(debit_previous_bill);
				$(".debit_till_date").val(debit_till_date.toFixed(2));
			}
			
			// Reconciliation Count
			var reconciliation_this_bill = $(".reconciliation_this_bill").val();
			var reconciliation_previous_bill = $(".reconciliation_previous_bill").val();

			if(jQuery.isNumeric(reconciliation_this_bill) && jQuery.isNumeric(reconciliation_previous_bill))
			{
				var reconciliation_till_date = parseFloat(reconciliation_this_bill) + parseFloat(reconciliation_previous_bill);
				$(".reconciliation_till_date").val(reconciliation_till_date.toFixed(2));
			}
			
			//Count sum a
			var sum_a = 0;
			$( ".amount_this_bill" ).each(function() {
				var this_value = $(this).val();
				if(jQuery.isNumeric(this_value))
				{
					sum_a += parseFloat(this_value);
				}
			});
			var debit_this_bill = $(".debit_this_bill").val();
			var reconciliation_this_bill = $(".reconciliation_this_bill").val();
			sum_a = parseFloat(sum_a) - parseFloat(debit_this_bill); 
			sum_a = parseFloat(sum_a) - parseFloat(reconciliation_this_bill);
			$(".sum_a").val(sum_a.toFixed(2));
			$(".sum_a").html(sum_a.toFixed(2));
			
			//Count sum b
			var sum_b = 0;
			$( ".amount_previous_bill" ).each(function() {
				var this_value = $(this).val();
				sum_b += parseFloat(this_value);
			});
			var debit_previous_bill = $(".debit_previous_bill").val();
			var reconciliation_previous_bill = $(".reconciliation_previous_bill").val();
			sum_b = parseFloat(sum_b) - parseFloat(debit_previous_bill); 
			sum_b = parseFloat(sum_b) - parseFloat(reconciliation_previous_bill);
			$(".sum_b").val(sum_b.toFixed(2));
			$(".sum_b").html(sum_b.toFixed(2));
			
			//Count sum c
			var sum_c = 0;
			$( ".amount_till_date" ).each(function() {
				var this_value = $(this).val();
				if(jQuery.isNumeric(this_value))
				{
					sum_c += parseFloat(this_value);
				}
			});
			var debit_till_date = $(".debit_till_date").val();
			var reconciliation_till_date = $(".reconciliation_till_date").val();
			sum_c = parseFloat(sum_c) - parseFloat(debit_till_date); 
			sum_c = parseFloat(sum_c) - parseFloat(reconciliation_till_date);
			$(".sum_c").val(sum_c.toFixed(2));
			$(".sum_c").html(sum_c.toFixed(2));
			
			// This Bill Amount Count
			var this_bill_amount = $(".sum_a").val();
			$(".this_bill_amount").val(this_bill_amount);
			$(".this_bill_amount").html(this_bill_amount);
			
			//CGST Count
			var this_bill_total = $(".this_bill_amount").val();
			var cgst_percentage = $(".cgst_percentage").val();
			if(jQuery.isNumeric(this_bill_total) && jQuery.isNumeric(cgst_percentage) && cgst_percentage != "")
			{
				var cgst_amount = (parseFloat(this_bill_total) * parseFloat(cgst_percentage)) / 100;
				$(".cgst").val(cgst_amount.toFixed(2));
				$(".cgst").html(cgst_amount.toFixed(2));
			}else{
				$(".cgst").val(0.00);
				$(".cgst").html(0.00);
			}
			
			//SGST Count
			var this_bill_total = $(".this_bill_amount").val();
			var sgst_percentage = $(".sgst_percentage").val();
			if(jQuery.isNumeric(this_bill_total) && jQuery.isNumeric(sgst_percentage) && sgst_percentage != "")
			{
				var sgst_amount = (parseFloat(this_bill_total) * parseFloat(sgst_percentage)) / 100;
				$(".sgst").val(sgst_amount.toFixed(2));
				$(".sgst").html(sgst_amount.toFixed(2));
			}else{
				$(".sgst").val(0.00);
				$(".sgst").html(0.00);
			}
			
			//IGST Count
			var this_bill_total = $(".this_bill_amount").val();
			var igst_percentage = $(".igst_percentage").val();
			if(jQuery.isNumeric(this_bill_total) && jQuery.isNumeric(igst_percentage) && igst_percentage != "")
			{
				var igst_amount = (parseFloat(this_bill_total) * parseFloat(igst_percentage)) / 100;
				$(".igst").val(igst_amount.toFixed(2));
				$(".igst").html(igst_amount.toFixed(2));
			}else{
				$(".igst").val(0.00);
				$(".igst").html(0.00);
			}
			
			//Gross Amount Count
			var this_bill_val = $(".this_bill_amount").val();
			var cgst_val = $(".cgst").val();
			var sgst_val = $(".sgst").val();
			var igst_val = $(".igst").val();
			
			var gross_amount = parseFloat(this_bill_val);
			
			// if(jQuery.isNumeric(this_bill_val) && jQuery.isNumeric(cgst_val) && jQuery.isNumeric(sgst_val) && jQuery.isNumeric(igst_val))
			// {
				// var gross_amount = parseFloat(this_bill_val) + parseFloat(cgst_val) + parseFloat(sgst_val) + parseFloat(igst_val);
			// }
			
			if(jQuery.isNumeric(cgst_val))
			{
				gross_amount += parseFloat(cgst_val);
			}
			
			if(jQuery.isNumeric(sgst_val))
			{
				gross_amount += parseFloat(sgst_val);
			}
			
			if(jQuery.isNumeric(igst_val))
			{
				gross_amount += parseFloat(igst_val);
			}
			
			$(".gross_amount").val(gross_amount.toFixed(2));
			$(".gross_amount").html(gross_amount.toFixed(2));
			
			//RETENTION MONEY COUNT
			var this_bill_total = $(".this_bill_amount").val();
			var retention_percentage = $(".retention_percentage").val();
			if(jQuery.isNumeric(this_bill_total) && jQuery.isNumeric(retention_percentage))
			{
				var retention_amount = (parseFloat(this_bill_total) * parseFloat(retention_percentage)) / 100;
				$(".retention_money").val(retention_amount.toFixed(2));
				$(".retention_money").html(retention_amount.toFixed(2));
			}
			
			//Net Amount Count
			var this_bill = $(".this_bill_amount").val();
			var cgst_val = $(".cgst").val();
			var sgst_val = $(".sgst").val();
			var igst_val = $(".igst").val();
			
			var gross_amount_val = parseFloat(this_bill) + parseFloat(cgst_val) + parseFloat(sgst_val) + parseFloat(igst_val);
			var retention_money_val = $(".retention_money").val();
			var net_amount = parseFloat(gross_amount_val);
			
			if(jQuery.isNumeric(gross_amount_val) && jQuery.isNumeric(retention_money_val))
			{
				net_amount -= parseFloat(retention_money_val);
			}
			$(".net_amount").val(net_amount.toFixed(2));
			$(".net_amount").html(net_amount.toFixed(2));
		}
	}
	/* Calculation for net amount of table */
	
	// Check GST status
	$("body").on("change",".bill_mode, #party_id",function(){
		checkGstStatus();
	});
	
	function checkGstStatus()
	{
		var party_gst_no = $("#party_gst_no").val();
		var yashnand_gst_no = $("#gstno").val();
		
		if(party_gst_no != "" && yashnand_gst_no != "")
		{
			var party_two_digit = party_gst_no.substr(0, 2);
			var yashnand_two_digit = yashnand_gst_no.substr(0, 2);
			if(jQuery.isNumeric(party_two_digit))
			{
				if(party_two_digit == yashnand_two_digit)
				{
					$(".igst_percentage").val(0);
					$(".igst").html(0);
					$(".igst").val(0);
					
					$("#cgst_row").show();
					$("#sgst_row").show();
					$("#igst_row").hide();
					$("#gross_amount_row").show();
				}else{
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
			}else{
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
		}else if(party_gst_no != ""){
			
		}else{
			$("#cgst_row").show();
			$("#sgst_row").show();
			$("#igst_row").show();
			$("#gross_amount_row").show();
		}
		
		countNetAmount();
	}
	
	//Fetch old record
	$("body").on("change","#party_id, #project_id",function(){
		$("#old_record_data").html("");
		jQuery('.debit_previous_bill').val(0);
		jQuery('.reconciliation_previous_bill').val(0);
						
		var project_id = $("#project_id").val();
		var party_id = $("#party_id").val();
		
		if(project_id != "" && party_id != "")
		{
			var row_len = jQuery(".row_number").length;
			if(row_len > 0)
			{
				var arr = [];
				$( ".row_number" ).each(function() {
					var m = $(this).val();
					arr.push(m);
				});
				var num = Math.max.apply(Math,arr);
				var row_id = parseInt(num) + 1;
			}
			else
			{
				var row_id = 0;
			}
			var curr_data = {	 						 					
	 					project_id : project_id, party_id : party_id, row_id:row_id 	 					
	 					};	 				
			jQuery.ajax({
					headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getsubcontractoldrow'));?>",
					data:curr_data,
					async:false,
					success: function(response){					
						var json_obj = jQuery.parseJSON(response);
						$("#old_record_data").append(json_obj['rows']);
						jQuery('.debit_previous_bill').val(json_obj['debit_till_date']);
						jQuery('.reconciliation_previous_bill').val(json_obj['reconciliation_till_date']);
						
						countAllRowAmount();
						return false;
					},
					error: function (e) {
						 alert('Error');
						 console.log(e.responseText);
					}
			});
		}
		countAllRowAmount();
	});
});
</script>	
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="col-md-10" >
<?php 
// if(!$is_capable)
	// {
		// $this->ERPfunction->access_deniedmsg();
	// }
// else
// {
?>		
<div class="col-md-12" >		
	<div class="block block-fill-white">
		<div class="head bg-default bg-light-rtl">
			<h2>Edit Sub-Contractor Bills</h2>
			<div class="pull-right">
			<a href="javascript:void(0)" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			
		
		 <div class="content controls">
			<div class="form-row">
				<div class="col-md-2" class="text-right">Project Code:</div>
				<div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $this->ERPfunction->get_projectcode($data['project_id']) ?>"
				class="form-control validate[required]" readonly="true"/></div>
				<div class="col-md-2">Project Name:<span class="require-field">*</span></div>
				<div class="col-md-4">
					<input type="hidden" name="project_id" value="<?php echo $data['project_id']; ?>">
					<select class="select2" required="true" disabled style="width: 100%;" name="project_id" id="project_id">
					<option value="">--Select Project--</Option>
					<?php 
						foreach($projects as $retrive_data)
						{
							$selected = ($data['project_id']==$retrive_data['project_id'])?"selected":"";
							echo '<option value="'.$retrive_data['project_id'].'"'.$selected.'>'.
							$retrive_data['project_name'].'</option>';
						}
					?>
					</select>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Type of Bill:<span class="require-field">*</span></div>
				<div class="col-md-4">
					<!-- <select class="select2" required="true" style="width: 100%;" name="type_of_bill" id="type_of_bill">
						<option value="">--Select Contract--</Option>
						<option value="labour" <?php echo ($data['type_of_bill']=="labour")?"selected":"" ?>>Labour</option>
						<option value="labour_material" <?php echo ($data['type_of_bill']=="labour_material")?"selected":"" ?>>Labour with Material</option>
						<option value="consultation" <?php echo ($data['type_of_bill']=="consultation")?"selected":"" ?>>Consultation</option>
						<option value="others" <?php echo ($data['type_of_bill']=="others")?"selected":"" ?>>Others</option>
					</select> -->
					<input type="text" name="type_of_bill" id="type_of_bill" value="<?php echo $data['type_of_bill']; ?>" readonly="true" class="validate[required]">
				</div>
				<div class="col-md-2">YashNand's GST No.:</div>
				<div class="col-md-4">
					<input readonly name="yashnand_gstno" id="gstno" class="gstno form-control" value="<?php echo $data['yashnand_gst_no']; ?>"/>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2"></div>
				<div class="col-md-10">
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" disabled <?php echo ($data['bill_mode'] == 'gujarat')?'checked':''; ?> class="bill_mode" value="gujarat" /> Gujarat</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" disabled <?php echo ($data['bill_mode'] == 'mp')?'checked':''; ?> value="mp" class="bill_mode" />Madhya Pradesh</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" disabled <?php echo ($data['bill_mode'] == 'maharastra')?'checked':''; ?> value="maharastra" class="bill_mode" />Maharastra</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" disabled <?php echo ($data['bill_mode'] == 'haryana')?'checked':''; ?> value="haryana" class="bill_mode" />Haryana</label>
					</div>
					<input type="hidden" name="bill_mode" value="<?php echo $data['bill_mode']; ?>" class="hidden_state">
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Bill No.:<span class="require-field">*</span></div>
				<div class="col-md-4">
					<input type="text" name="bill_no" id="bill_no" value="<?php echo $data['bill_no']; ?>" class="form-control validate[required]"/>
				</div>
			
				<div class="col-md-2">Bill Date:<span class="require-field">*</span></div>
				<div class="col-md-4">
					<input type="text" name="bill_date" id="bill_date" value="<?php echo date("d-m-Y",strtotime($data['bill_date'])); ?>" class="form-control validate[required]"/>
				</div>
				 
			</div>
			
			<div class="form-row" style="display:none;">
				<div class="col-md-2"></div>
				<div class="col-md-10">
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" readonly="true" <?php echo ($data['party_type'] == "party")?"checked":""; ?> name="party_type_radio" class="party_type_radio" value="party" /> Party</label>
					</div>
					<div class="radiobox-inline" style="padding:0 50px;">
						<label><input type="radio" readonly="true" <?php echo ($data['party_type'] == "temp_emp")?"checked":""; ?> name="party_type_radio" value="temp_emp" class="party_type_radio" />Temp</label>
					</div>
				</div>
			</div>
			
			<div class="form-row">
			<div id="party_div" style="display:<?php echo ($data['party_type'] == "party")?"block":"none"; ?>">
				<div class="col-md-2">Party's Name:<span class="require-field">*</span></div>
				<div class="col-md-4">
					<input type="hidden" name="party_id" value="<?php echo $data['party_id'] ?>">
					<select class="select2" disabled style="width: 100%;" name="party_id" id="party_id">
					<option value="">--Select Party--</Option>
					<?php
						if($vendor_info){
							foreach($vendor_info as $vendor_row){
								?>
									<option value="<?php echo $vendor_row['user_id']; ?>" dataid="vendor" <?php 
												if(isset($data)){
													if($data['party_id'] == $vendor_row['user_id']){
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
												if(isset($data)){
													if($data['party_id'] == $agency['agency_id']){
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
				</div>
				<div id="temp_emp_div" style="display:<?php echo ($data['party_type'] == "temp_emp")?"block":"none"; ?>">
					<div class="col-md-2">Party's Name:<span class="require-field">*</span></div>
					<div class="col-md-4">
					<select class="select2" disabled style="width: 100%;" name="party_id" id="temp_party_id">
					<option value="">--Select Party--</Option>
					<?php
						foreach($temp_employee as $emp)
						{
							$selected = ($emp['user_id'] == $data['party_id'])?"selected":"";
							echo "<option value='{$emp['user_id']}' {$selected}>{$this->ERPfunction->get_user_name($emp['user_id'])}</option>";
						}
					?>
					</select>
					</div>
				</div>
				 <div class="col-md-2">Party ID:</div>
				<div class="col-md-4">
					<input type="text" name="party_identy" readonly="true" id="party_identy" value="<?php echo $data['party_identy']; ?>" class="form-control" value=""/>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Party Addresss:</div>
				<div class="col-md-10">
					<input type="text" name="party_address" readonly="true" id="party_address" class="form-control" value="<?php echo $data['party_address']; ?>"/>
				</div>
			</div>	
			<div class="form-row">						
				<div class="col-md-2">Contact No: (1)</div>
				<div class="col-md-4">
					<input type="text" name="party_no1" readonly="true" id="party_no1" class="form-control" value="<?php echo $data['party_no1']; ?>"/>
				</div>
				
				<div class="col-md-2">Contact No: (2)</div>
				<div class="col-md-4">
					<input type="text" name="party_no2" readonly="true" id="party_no2" value="<?php echo $data['party_no2']; ?>" class="form-control"/>
				</div>
			</div>			
			 
			<div class="form-row">						
				<div class="col-md-2">PAN Card No:</div>
				<div class="col-md-4">
					<input type="text" name="party_pan_no" readonly="true" value="<?php echo $data['party_pan_no']; ?>" id="party_pan_no" class="form-control"/>
				</div>
				
				<div class="col-md-2">GST No:</div>
				<div class="col-md-4">
					<input type="text" name="party_gst_no" readonly="true" id="party_gst_no" value="<?php echo $data['party_gst_no']; ?>" class="form-control"/>
				</div>
			</div>
			
			<div class="form-row">						
				<div class="col-md-2">Our Abstract No:</div>
				<div class="col-md-4">
					<input type="text" name="abstrack_number" id="abstrack_number" value="<?php echo $data['our_abstract_no']; ?>" class="form-control"/>
				</div>
				
				<div class="col-md-2">WO No:</div>
				<div class="col-md-4">
				<input type="text" readonly="true" name="wo_no_list" value="<?php echo $data['wo_no']; ?>" id="wo_no" class="form-control validate[required]"/>
					<!--<select class="select2" multiple="multiple" style="width: 100%;" name="wo_no_list[]" id="wo_no">
						<option value="">--Select Party--</option>
						<?php
							foreach($wo_no_list as $key => $value)
							{
								$selected = (in_array($key, json_decode($data['wo_no'])))?"selected":"";
								echo "<option value='{$key}' $selected>{$value}</option>";
							}
						?>
					</select>-->
				</div>
			</div>
			
			<div class="form-row">	
				<div class="col-md-6"></div>		
				<div class="col-md-2">Bill Duration:</div>
				<div class="col-md-1" style="max-width:40px;">From<span class="require-field">*</span></div>
				<div class="col-md-2" style="max-width:128px;">
					<input type="text" name="bill_from_date" id="bill_from_date" value="<?php echo date("d-m-Y",strtotime($data["bill_from_date"])); ?>" class="form-control bill_from_date"/>
				</div>
				
				<div class="col-md-1" style="max-width:40px;">To<span class="require-field">*</span></div>
				<div class="col-md-2" style="max-width:128px;">
					<input type="text" name="bill_to_date" id="bill_to_date" value="<?php echo date("d-m-Y",strtotime($data["bill_to_date"])); ?>" class="form-control bill_to_date"/>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Type of Work:<span class="require-field">*</span></div>
				<div class="col-md-10">
					<textarea name="type_of_work" cols="5" class="validate[required]"><?php echo $data['type_of_work']; ?></textarea>
				</div>
			</div>
			
			<!--<button type="button" id="add_newrow" class="btn btn-default">Add New </button>-->
			<!--<button type="button" id="new_option" data-type="subcontractbill_option" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default add_option" style="">Add New Option </button>-->
			<div class="form-row" style="overflow:scroll">						
				<table class="table table-bordered">
					<thead>
						<tr>
							<th rowspan="2" class="text-center">Item No</th>
							<th rowspan="2" class="text-center">Description</th>
							<th rowspan="2" class="text-center">Unit</th>
							<th colspan="3" class="text-center">Quantity</th>
							<th rowspan="2" class="text-center">Applied Rate</th>
							<th rowspan="2" class="text-center">Full Rate</th>
							<th colspan="3" class="text-center auto_decrease_colspan">Amount</th>
							<th rowspan="2" class="text-center">Delete</th>
						</tr>
						<tr>
							<th class="text-center">This Bill</th>
							<th class="text-center">Up To Previous Bill</th>
							<th class="text-center">Till Date</th>
							<th class="text-center labour_with_material_hide">This Bill</th>
							<th class="text-center labour_with_material_hide">Up To Previous Bill</th>
							<th class="text-center">Till Date</th>
						</tr>
					</thead>
					<tbody id="old_record_data">
					</tbody>
					<tbody id="new_record_data">	
						<?php
						$i = 0;
						foreach($detail_data as $detail)
						{
						?>
						<tr id="row_id_<?php echo $i; ?>">
							<td>
								<input type="text" name="bill[item_no][]" id="item_no_<?php echo $i; ?>" value="<?php echo $detail['item_no']; ?>" class="item_no validate[required]" data-id="<?php echo $i; ?>" style="width:80px;">
								<input type="hidden" value="<?php echo $i; ?>" name="row_number" class="row_number">
								<input type="hidden" name="bill[detail_id][]" value="<?php echo $detail['id']; ?>">
							</td>

							<td>
							<input type='hidden' name='bill[description][]' value='<?php echo $detail['description']; ?>'>
								<?php echo $this->ERPfunction->get_category_title($detail['description']); ?>
								<!--<select class="select2 description" required="true" style="width: 100%;" name="bill[description][]" class="description" id="description_<?php echo $i; ?>" data-id="<?php echo $i; ?>">
									<option value="">--Select Option--</option>
									<?php
									foreach($description_options as $key => $value)
									{
										$selected = ($key == $detail['description'])?"selected":"";
										echo "<option value='{$key}' $selected>{$value}</option>";
									}
								?>
								</select>-->
							</td>

							<td>
								<input type="text" name="bill[unit][]" value="<?php echo $detail['unit']; ?>" id="unit_<?php echo $i; ?>" class="unit validate[required]" data-id="<?php echo $i; ?>" style="width:80px;">
							</td>

							<td> 
								<input type="text" name="bill[quantity_this_bill][]" value="<?php echo $detail['quantity_this_bill']; ?>" id="quantity_this_bill_<?php echo $i; ?>" class="quantity_this_bill validate[required,custom[number]]" data-id="<?php echo $i; ?>" style="width:80px;">
							</td>

							<td>
								<input type="text" name="bill[quantity_previous_bill][]" value="<?php echo $detail['quantity_previous_bill']; ?>" id="quantity_previous_bill_<?php echo $i; ?>" class="quantity_previous_bill validate[required,custom[number]]" data-id="<?php echo $i; ?>" style="width:80px;" value="0">
							</td>

							<td>
								<input type="text" name="bill[quantity_till_date][]" value="<?php echo $detail['quantity_till_date']; ?>" id="quantity_till_date_<?php echo $i; ?>" class="quantity_till_date validate[required,custom[number]]" data-id="<?php echo $i; ?>" style="width:80px;">
							</td>

							<td>
								<input type="text" name="bill[rate][]" value="<?php echo $detail['rate']; ?>" id="rate_<?php echo $i; ?>" class="rate validate[required,custom[number]]" data-id="<?php echo $i; ?>" style="width:80px;">
							</td>
							
							<td>
								<input type="text" name="bill[full_rate][]" value="<?php echo $detail['full_rate']; ?>" id="full_rate_<?php echo $i; ?>" class="full_rate validate[required,custom[number]]" data-id="<?php echo $i; ?>" style="width:80px;">
							</td>

							<td class="labour_with_material_hide"> 
								<input type="text" name="bill[amount_this_bill][]" value="<?php echo $detail['amount_this_bill']; ?>" readonly="true" id="amount_this_bill_<?php echo $i; ?>" class="amount_this_bill validate[required,custom[number]]" data-id="<?php echo $i; ?>" style="width:80px;">
							</td>

							<td class="labour_with_material_hide">
								<input type="text" name="bill[amount_previous_bill][]" value="<?php echo $detail['amount_previous_bill']; ?>" id="amount_previous_bill_<?php echo $i; ?>" class="amount_previous_bill validate[required,custom[number]]" data-id="<?php echo $i; ?>" style="width:80px;" value="0">
							</td>

							<td>
								<input type="text" name="bill[amount_till_date][]" value="<?php echo $detail['amount_till_date']; ?>" readonly="true" id="amount_till_date_<?php echo $i; ?>" class="amount_till_date validate[required,custom[number]]" data-id="<?php echo $i; ?>" style="width:80px;" value="">
							</td>

							<td>
								<a href="#" class="btn btn-danger del_parent" detail-id="<?php echo $detail['id']; ?>" >Delete</a>
							</td>
						</tr>
						<?php 
						$i++;
						} ?>
					</tbody>
					<tfoot>
					<tr class="labour_with_material_hide" style="<?php echo ($data['type_of_bill'] != "Labour with Material")?'':'display:none'; ?>">
						<td colspan="8" class="text-left"><b>Debit Note</b></td>
						<td> 
							<input type="text" name="debit_this_bill" id="debit_this_bill_0" class="debit_this_bill validate[required,custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['debit_this_bill']; ?>">
						</td>

						<td>
							<input type="text" name="debit_previous_bill" id="debit_previous_bill_0" class="debit_previous_bill validate[required,custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['debit_previous_bill']; ?>">
						</td>

						<td>
							<input type="text" name="debit_till_date" id="debit_till_date_0" class="debit_till_date validate[required,custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['debit_till_date']; ?>">
						</td>
						<td></td>
					</tr>

					<tr class="labour_with_material_show" style="<?php echo ($data['type_of_bill'] == "Labour with Material")?'':'display:none'; ?>">
						<td colspan="8" class="text-left"><b>Debit Note</b></td>
						<td>
							<input type="text" name="debit_till_date_labour" id="debit_till_date_labour_0" class="debit_till_date_labour validate[required,custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['debit_till_date']; ?>">
						</td>
						<td></td>
					</tr>

					<tr class="labour_with_material_hide" style="<?php echo ($data['type_of_bill'] != "Labour with Material")?'':'display:none'; ?>">
						<td colspan="8" class="text-left"><b>Reconciliation / Material Debit Note</b></td>
						<td> 
							<input type="text" name="reconciliation_this_bill" id="reconciliation_this_bill_0" class="reconciliation_this_bill validate[required,custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['reconciliation_this_bill']; ?>">
						</td>

						<td>
							<input type="text" name="reconciliation_previous_bill" id="reconciliation_previous_bill_0" class="reconciliation_previous_bill validate[required,custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['reconciliation_previous_bill']; ?>">
						</td>

						<td>
							<input type="text" name="reconciliation_till_date" id="reconciliation_till_date_0" class="reconciliation_till_date validate[required,custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['reconciliation_till_date']; ?>">
						</td>
						<td></td>
					</tr>
					<tr class="labour_with_material_show" style="<?php echo ($data['type_of_bill'] == "Labour with Material")?'':'display:none'; ?>">
						<td colspan="8" class="text-left"><b>Reconciliation / Material Debit Note</b></td>
						<td>
							<input type="text" name="reconciliation_till_date_labour" id="reconciliation_till_date_labour_0" class="reconciliation_till_date_labour validate[required,custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['reconciliation_till_date']; ?>">
						</td>
						<td></td>
					</tr>
					<tr>
						<td colspan="8" class="text-center"><b>GRAND TOTAL</b></td>
						<td class="labour_with_material_hide">
							<span class="sum_a"><?php echo $data['sum_a']; ?></span>
							<input type="hidden" name="sum_a" id="sum_a_0" class="sum_a" data-id="0" style="width:80px;" value="<?php echo $data['sum_a']; ?>">
						</td>

						<td class="labour_with_material_hide">
							<span class="sum_b"><?php echo $data['sum_b']; ?></span>
							<input type="hidden" name="sum_b" id="sum_b_0" class="sum_b" data-id="0" style="width:80px;" value="<?php echo $data['sum_b']; ?>">
						</td>

						<td>
							<span class="sum_c"><?php echo $data['sum_c']; ?></span>
							<input type="hidden" name="sum_c" id="sum_c_0" class="sum_c" data-id="0" style="width:80px;" value="<?php echo $data['sum_c']; ?>">
						</td>
						<td></td>
					</tr>

					<tr class="labour_with_material_show" style="<?php echo ($data['type_of_bill'] == "Labour with Material")?'':'display:none'; ?>">
						<td colspan="8" class="text-center"><b>MATERIAL ADVANCE OR THIS BILL</b></td>
						<td>
							<input type="text" name="material_advance" id="material_advance" class="material_advance validate[required,custom[number]]" value="<?php echo $data['material_advance']; ?>" data-id="0" style="width:80px;">
						</td>
						<td></td>
					</tr>

					<tr class="labour_with_material_show" style="<?php echo ($data['type_of_bill'] == "Labour with Material")?'':'display:none'; ?>">
						<td colspan="8" class="text-center"><b>AMOUNT - TILL DATE</b></td>
						<td>
							<span class="amount_till_date_labour"></span>
							<input type="hidden" name="amount_till_date_labour" id="amount_till_date_labour" data-id="0" value="value="<?php echo $data['amount_till_date_labour']; ?>"" style="width:80px;">
						</td>
						<td></td>
					</tr>

					<tr class="labour_with_material_show" style="<?php echo ($data['type_of_bill'] == "Labour with Material")?'':'display:none'; ?>">
						<td colspan="8" class="text-center"><b>AMOUNT - UPTO PREVIOUS BILL</b></td>
						<td>
							<span class="amount_upto_previous_labour">0</span>
							<input type="hidden" name="amount_upto_previous_labour" id="amount_upto_previous_labour" value="<?php echo $data['amount_upto_previous_labour']; ?>" data-id="0" style="width:80px;">
						</td>
						<td></td>
					</tr>

					<tr>
						<td colspan="8" class="text-center"><b>THIS BILL AMOUNT</b></td>
						<td colspan="3" class="text-center auto_decrease_colspan">
							<span class="this_bill_amount"><?php echo $data['sum_a']; ?></span>
							<input type="hidden" name="this_bill_amount" id="this_bill_amount_0" class="this_bill_amount" data-id="0" style="width:80px;" value="<?php echo $data['sum_a']; ?>">
						</td>
						<td></td>
					</tr>
					<tr id="cgst_row">
						<td colspan="8" class="text-center"><b>CGST (%)</b></td>
						<td>
							<input type="text" name="cgst_percentage" id="cgst_percentage_0" class="cgst_percentage validate[custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['cgst_percentage']; ?>">
						</td>
						<td colspan="2" class="auto_decrease_colspan">
							<span class="cgst"><?php echo $data['cgst']; ?></span>
							<input type="hidden" name="cgst" id="cgst_0" class="cgst validate[custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['cgst']; ?>">
						</td>
						<td></td>
					</tr>
					<tr id="sgst_row">
						<td colspan="8" class="text-center"><b>SGST (%)</b></td>
						<td>
							<input type="text" name="sgst_percentage" id="sgst_percentage_0" class="sgst_percentage validate[custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['sgst_percentage']; ?>">
						</td>
						<td colspan="2" class="auto_decrease_colspan">
							<span class="sgst"><?php echo $data['sgst']; ?></span>
							<input type="hidden" name="sgst" id="sgst_0" class="sgst" data-id="0" style="width:80px;" value="<?php echo $data['sgst']; ?>">
						</td>
						<td></td>
					</tr>
					<tr id="igst_row">
						<td colspan="8" class="text-center"><b>IGST (%)</b></td>
						<td>
							<input type="text" name="igst_percentage" id="igst_percentage_0" class="igst_percentage validate[custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['igst_percentage']; ?>">
						</td>
						<td colspan="2" class="auto_decrease_colspan">
							<span class="igst"><?php echo $data['igst']; ?></span>
							<input type="hidden" name="igst" id="igst_0" class="igst" data-id="0" style="width:80px;" value="<?php echo $data['igst']; ?>">
						</td>
						<td></td>
					</tr>
					<tr id="gross_amount_row">
						<td colspan="8" class="text-center"><b>GROSS AMOUNT</b></td>
						<td colspan="3" class="text-center auto_decrease_colspan">
							<span class="gross_amount text-center"><?php echo $data['gross_amount']; ?></span>
							<input type="hidden" name="gross_amount" id="gross_amount_0" class="gross_amount" data-id="0" style="width:80px;" value="<?php echo $data['gross_amount']; ?>">
						</td>
						<td></td>
					</tr>
					<tr>
						<td colspan="8" class="text-center"><b>RETENTION MONEY</b></td>
						<td>
							<input type="text" name="retention_percentage" id="retention_percentage_0" class="retention_percentage validate[required,custom[number]]" data-id="0" style="width:80px;" value="<?php echo $data['retention_percentage']; ?>">
						</td>
						<td colspan="2" class="auto_decrease_colspan">
							<span class="retention_money"><?php echo $data['retention_money']; ?></span>
							<input type="hidden" name="retention_money" id="retention_money_0" class="retention_money" data-id="0" style="width:80px;" value="<?php echo $data['retention_money']; ?>">
						</td>
						<td></td>
					</tr>
					<tr>
						<td colspan="8" class="text-center"><b>NET AMOUNT</b></td>
						<td colspan="3" class="text-center auto_decrease_colspan">
							<span class="net_amount"><?php echo $data['net_amount']; ?></span>
							<input type="hidden" name="net_amount" id="net_amount_0" class="net_amount" data-id="0" style="width:80px;" value="<?php echo $data['net_amount']; ?>">
						</td>
						<td></td>
					</tr>
					</tfoot>
				</table>
				
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
						$attached_files = json_decode($data["attachment"]);			
						if(!empty($attached_files))
						{							
							$i = 0;
							foreach($attached_files as $file)
							{?>
								<div class='del_parent'>
									<div class='form-row'>
										<div class='col-md-2'>
											
										</div>
										<div class='col-md-4'><a href="<?php echo $this->ERPfunction->get_signed_url($file);?>" class="btn btn-primary" target="_blank">View File</a>
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
				<div class="col-md-2"></div>
				<div class="col-md-4"><button type="submit" class="btn btn-primary">Prepare</button></div>
			</div>
		</div>
		<?php $this->Form->end(); ?>
	</div>
</div>
<?php 
//}
?>
</div>
		