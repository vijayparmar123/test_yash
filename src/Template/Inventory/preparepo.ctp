<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
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
	
	
	
	jQuery('.delivery_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
		
		
			jQuery('.brand_add').click(function(){
			
			
				var model  = jQuery(this).attr('data-type') ;
		
				var curr_data = {type : model};	 				
				 jQuery.ajax({
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
					jQuery('#vendor_delivery_address').val(json_obj['project_address'] + "," + json_obj['project_address_2']);
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
				$("#gj_address").css("display","block");
				$("#mp_address").css("display","none");
				$("#mh_address").css("display","none");
			}
			else if(state == 'mp')
			{
				$("#mp_address").css("display","block");
				$("#gj_address").css("display","none");
				$("#mh_address").css("display","none");
			}
			else if(state == 'maharastra')
			{
				$("#gj_address").css("display","none");
				$("#mp_address").css("display","none");
				$("#mh_address").css("display","block");
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
											
					jQuery('#gstno').val(response);	
					return false;
                },
                error: function (e) {
                     alert('Error');
					 console.log(e.responseText);
                }
            });
		});
	
	jQuery("body").on("change", "#pr_id", function(event){ 
		var pr_id  = jQuery(this).val();
		
		var prno = jQuery("#pr_id option:selected").attr('prno');
		
		/* jQuery("#curre_pr_id").val(prno); */
		
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
					/* jQuery('#vendor_delivery_address').val(json_obj['delivery_place']); */						
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
		//jQuery(this).attr("disabled", "disabled");
		var row_type = jQuery(".row_type:checked").val();
		//var row_id = jQuery("tbody > tr").length;
		var row_len = jQuery(".row_number").length;
		if(row_len > 0)
			{
				var num = jQuery(".row_number:last").val();
				var row_id = parseInt(num) + 1;
			}
			else
			{
				var row_id = 0;
			}
		var action = 'add_newrow';
		if(row_type == "textfield")
		{
			var class_len = jQuery(".text_data").length;
			if(class_len > 0)
			{
				var last_code = jQuery(".text_data:last").val();
			}
			else
			{
				var last_code = 0;
			}
		}
		else 
		{
			var last_code = 0;
		}
		jQuery.ajax({
                     type: 'POST',
                     url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowpo"]);?>',
                     data : {row_id:row_id,row_type:row_type,last_code:last_code},
                     success: function (response)
                        {	
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
  
  jQuery("body").on("change", ".brand_id", function(event){ 
	 var row_id = jQuery(this).attr('data-id');
	  var project_id  = jQuery("#project_id").val() ;
	  var po_date  = jQuery("#po_date").val() ;
	  var vendor_id  = jQuery("#vendor_userid").val() ;
	  var material_id  = jQuery("#material_id_"+row_id).val() ;
	  var brand_id  = jQuery("#brand_id_"+row_id).val() ;
	  
	  if(project_id == '' || po_date == '' || vendor_id == '' || material_id == '')
	  {
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
               headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialrate'));?>",
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
	
  });
  
  jQuery("body").on("change", ".material_id", function(event){ 
	 var row_id = jQuery(this).attr('data-id');
	  var project_id  = jQuery("#project_id").val() ;
	  var po_date  = jQuery("#po_date").val() ;
	  var vendor_id  = jQuery("#vendor_userid").val() ;
	  var material_id  = jQuery("#material_id_"+row_id).val() ;
	  var brand_id  = jQuery("#brand_id_"+row_id).val() ;
	  
	  if(project_id == '' || po_date == '' || vendor_id == '' || material_id == '')
	  {
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
               headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialrate'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);	
					jQuery('#hsn_code_'+row_id).val(json_obj['hsn_code']);
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
	
  });
  
   jQuery("body").on("change", "#usage", function(){
			usage_name = jQuery(this).val();
			if(usage_name == 'for_agency')
			{
				jQuery("#agency_div").show();
				jQuery("#agency_id").attr('required', true);
			}
			else if(usage_name == 'for_self')
			{
				jQuery("#agency_div").hide();
				jQuery("#agency_id").attr('required', false);
			}
	   });
	   
   jQuery("body").on("change", ".delivery_type", function(){
		delivery_type = jQuery(this).val();
		if(delivery_type == 'via')
		{
			jQuery("#delivery_project_div").show();
			jQuery("#delivery_project").attr('required', true);
		}
		else if(delivery_type == 'direct')
		{
			jQuery("#delivery_project_div").hide();
			jQuery("#delivery_project").attr('required', false);
			var old_project_address = jQuery("#project_address").val();
			jQuery('#vendor_delivery_address').val(old_project_address);
		}
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
  
  jQuery("body").on("change", "#vendor_userid", function(event){ 
	  jQuery(".material_id").select2("val", "");
	  jQuery(".hsn_code").val("");
	  jQuery(".brand_id").select2("val", "");
	  jQuery(".m_code").html("");
	  jQuery(".quantity").val("");
	  jQuery(".unit_rate").val(0);
	  jQuery(".tx_count").val(0);
	  jQuery(".amount").val(0);
	  jQuery(".single_amount").val(0);
	  jQuery(".unit_name").html("");
	  jQuery("#total_po_amount").html(0);
	 
  });
  
  jQuery("body").on("change", "#project_id", function(event){ 
	  jQuery(".material_id").select2("val", "");
	  jQuery(".hsn_code").val("");
	  jQuery(".brand_id").select2("val", "");
	  jQuery(".m_code").html("");
	  jQuery(".quantity").val("");
	  jQuery(".unit_rate").val(0);
	  jQuery(".tx_count").val(0);
	  jQuery(".amount").val(0);
	  jQuery(".single_amount").val(0);
	  jQuery(".unit_name").html("");
	  jQuery("#total_po_amount").html(0);
	 
  });
  
  jQuery("body").on("blur", "#po_date", function(event){ 
	  jQuery(".material_id").select2("val", "");
	  jQuery(".hsn_code").val("");
	  jQuery(".brand_id").select2("val", "");
	  jQuery(".m_code").html("");
	  jQuery(".quantity").val("");
	  jQuery(".unit_rate").val(0);
	  jQuery(".tx_count").val(0);
	  jQuery(".amount").val(0);
	  jQuery(".single_amount").val(0);
	  jQuery(".unit_name").html("");
	  jQuery("#total_po_amount").html(0);
	 
  });
  
	
  
  function count_total(row_id)
  {
		var qty = jQuery('#quantity_'+row_id).val();
		var price = jQuery('#unit_rate_'+row_id).val();
		
		if(price == '')
		{
			price = 0;
		}
		var single_amount = price;
		
		var dc = parseFloat($("#dc_"+row_id).val());		
		if(dc != '')
		{			
			dc = parseFloat((100-dc)/100);
			single_amount = parseFloat(price * dc);
		}
		
		var tr = parseFloat($("#tr_"+row_id).val()); /* CGST */ 
		var ex = parseFloat($("#ex_"+row_id).val()); /* SGST */
		var other = parseFloat($("#other_tax_"+row_id).val()); /* IGST */
		var total_gst = parseFloat(tr + ex + other);
		
		if(total_gst > 0)
		{
			var gst_count = 1 + parseFloat(total_gst / 100);
			single_amount = parseFloat(single_amount * gst_count)
		}
		
		var new_amount = parseFloat(qty*single_amount);
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
  }
  
  
  
  jQuery('body').on('click','.trash',function(){
		var row_id = jQuery(this).attr('data-id');
		
		jQuery('table tr#row_id_'+row_id).remove();	
		return false;
	});
	
	jQuery('body').on('blur','.quantity',function(){

		var row_id = jQuery(this).attr('data-id');
		count_total(row_id);
		
    });
	
		
	jQuery("body").on("change",".tx_count",function(){
		
		var row_id = jQuery(this).attr('data-id');
		count_total(row_id);
		
	});
		
	jQuery("body").on("change",".unit_rate",function(){
		
		var row_id = jQuery(this).attr('data-id');
		count_total(row_id);
		
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
	});
	
	$("body").on("change",".change_add",function(){
		var id = $(this).val();
		// alert(id);
		if(id == "mp")
		{
			 $("#mp_address").css("display","block");
			$("#gj_address").css("display","none"); 	
			/*$("#vatno").val("23379109713");
			$("#cstno").val("23379109713");*/
				
		}else{
			 $("#gj_address").css("display","block");
			$("#mp_address").css("display","none"); 
			/*$("#cstno").val("24073404329");
			$("#vatno").val("24073404329");*/
		}
		
	});
	
	jQuery("body").on("change",".othertax",function(){
		
		var sid = jQuery(this).attr("sid");
		
		if(sid == "other")
		{
			jQuery("#other_text").css("display","block");			
		}
		else{
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
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>		
<div class="col-md-12" >		
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?>  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Purchase',$back);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
					 <div class="content controls">
						
						<div class="form-row">
                            <div class="col-md-2" class="text-right">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
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
						<div class="form-row">
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
						
						<div class="form-row">
                            <div class="col-md-2">P.O.No:</div>
                            <div class="col-md-4">
							
								<input type="text" name="po_no" id="po_no" value="" class="form-control" value=""/>
							</div>
                        
                            <div class="col-md-1">Date:</div>
                            <div class="col-md-2"><input type="text" name="po_date" id="po_date" 
							value="<?php echo $this->ERPfunction->get_date(date('Y-m-d'));?>" class="form-control" value=""/></div>
							 <div class="col-md-1">Time:</div>
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
                            <div class="col-md-2">Contact No: (1)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no1" id="ven_contact_no1" class="form-control" value=""/>
							</div>
							
                            <div class="col-md-2">Contact No: (2)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no2" id="ven_contact_no2" value="" class="form-control"/>
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
						<!--<div class="form-row">
                            <div class="col-md-2">P. R. No:</div>
                            <div class="col-md-4">
								<input class="form-control" style="width: 100%;" name="" id="curre_pr_id">							
							</div>
                        
                        </div> -->
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
                            <div class="col-md-2">Payment Method:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="payment_method" id="payment_method" >
									<option value="">--Select Payment Method--</Option>
									<option value="cash">Cash</Option>
									<option value="cheque">Cheque</Option>							
								</select>
							</div>
							
							<div class="col-md-2">Delivery Date:</div>
							 <div class="col-md-4">
							<input type="text" name="delivery_date" id="delivery_date" value="<?php echo date("d")+1 . "-".date("m")."-".date("Y"); ?>" class="form-control delivery_date"/>
							</div>
                        </div>
						<!--<div class="form-row" id="radiogroup">                           
							<div class="col-md-2 text-right">Other Taxes:</div>
                            <div class="col-md-1">
								<input name="other_tax" type="radio" class="othertax" value="CST" sid="a" checked="checked">CST
								<br>
								<input name="other_tax" type="radio" class="othertax" value="VAT" sid="a">VAT
							</div>
							<div class="col-md-1 text-right" id="do-left">
								<input name="other_tax" type="radio" class="othertax" value="GST" sid="a">GST
								<br>
								<input name="other_tax" type="radio" class="othertax" value="" sid="other">Other								
							</div>
							<div class="col-md-2 text-right">
								<br>
								<input id="other_text" class="form-control" style="display:none">
							</div>
						</div> -->
							
						<div class="form-row" style="overflow:scroll">						
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th rowspan="2" style="display:none;">Material Code</th>
									<th colspan="10">Material / Item</th>
									<!-- <th rowspan="2">Amount (Inclusive All)</th> -->
									<th rowspan="2">Amount (Inclusive All)</th>
									<th rowspan="2">Final Rate<br>(Inclusive All)</th>
									<th rowspan="2">Delete</th>
									</tr>
									<tr>
									<th>Description</th>
									<th>HSN Code</th>
									<th>Make / Source</th>
									<th>Quantity</th>
									<th>Unit</th>	
									<th>Unit Rate<br>(Rs.)</th>	
									<th>Dis<br>(%)</th>
									<th>CGST<br>(%)</th>
									<th>SGST<br>(%)</th>
									<th>IGST<br>(%)</th>												
									
									</tr>
								</thead>
								<tbody>	

			<tr id="row_id_0">
			<td style="display:none;"><span class="m_code" id="material_code_0"></span>
			<input type="hidden" value="" name="material[m_code][]" id="m_code_0">
			<input type="hidden" value="1" name="material[is_custom][]">
			<input type="hidden" value="0" name="row_number" class="row_number">
			</td>
			<td>
		<select class="select2 material_id" style="width:180px;" name="material[material_id][]" id="material_id_0" data-id="0">
			<option value="">Select Material</Option>
			<?php 
			   foreach($material_list as $retrive_data)
			   {
					 echo '<option value="'.$retrive_data['material_id'].'">'.
					 $retrive_data['material_title'].'</option>';
			   }
			?>
		</select>
			</td>
			<td>
			<input type="text" value="" name="material[hsn_code][]" id="hsn_code_0" class="hsn_code" style="width:120px;">
			</td>
			<td>
			<select class="select2 brand_id"  required="true" data-id="0"  name="material[brand_id][]" style="width:130px;" id="brand_id_0">
			<option value="">Select Item</Option>												
			</select>
			
			</td>
			<td> <input type="text" name="material[quantity][]" value="" class="quantity" data-id="0" id="quantity_0"/></td>
			
			<td>
			<span class="unit_name" id="unit_name_0"></span>
			<input type="hidden" value="" name="material[static_unit][]" id="static_unit_0" class="form-control" style="width:80px;">
			</td>
			<td><input type="text" name="material[unit_rate][]" class="unit_rate" value="" data-id="0" id="unit_rate_0" style="width:80px" /></td>
			<td><input type="text" name="material[discount][]" value="0" class="tx_count" id="dc_0" data-id="0" style="width:55px"></td>
			<td><input type="text" name="material[transportation][]" value="0"  class="tx_count" id="tr_0" data-id="0" style="width:55px"></td>
			<td><input type="text" name="material[exice][]" class="tx_count" value="0" id="ex_0"  data-id="0" style="width:55px"></td>
			<td><input type="text" name="material[other_tax][]" class="tx_count" value="0" id="other_tax_0"  data-id="0" style="width:55px"></td>
			<td><input type="text" name="material[amount][]" value="0" class="amount" id="amount_0" style="width:90px" /></td>
			<td><input type="text" name="material[single_amount][]" value="0" class="single_amount" id="single_amount_0" style="width:90px"/></td>
			<td><a href="#" class="btn btn-danger del_parent">Delete</a>
			<input type="hidden" name="pr_mid[]" value="">
			</td>
		</tr>
		
		
								</tbody>
								<tfoot>
								<tr>
		<td colspan="10" class="text-right"><b>Total Amount</b></td>
		<td id="total_po_amount" style="padding-left:24px;">0</td>
		<td></td>
		<td></td>
		</tr>
								</tfoot>
							</table>
                        </div>
						
						<button type="button" id="add_newrow" class="btn btn-default">Add New </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="row_type" type="radio" checked="checked" class="row_type" value="dropdown">Dropdown
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="row_type" type="radio" class="row_type" value="textfield">Text Field
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
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
							<p>2) If material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this PO will be considered as void.
							</p>
							<p>3) Manufacturer's Test Certificates are required for each batch of supply.</p>
							<p>4) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance. </p>
							<p>5) If you will not revert back within 48 hrs, this PO will be considered as accepted by you.</p>
							<p>6) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</p>
							<p>7) All disputes subject to Ahmedabad Jurisdiction only.</p>
							<p>8) For payment party will have to submit <strong>Invoice along with Purchase Order (PO), Gate Pass - Goods Inward, Goods Receipt Note or/and Rejection Memo and/or Weight Pass.</strong></p>
							<!--<p> <u>Select Billing Address Location.</u>
								<ul>
									<li><input type="radio" class="change_add" value="gj" name="bill_address" checked>Gujarat</li>
									<li><input type="radio" class="change_add" value="mp" name="bill_address">Madhya Pradesh</li>
								</ul>
							</p>-->
							<p id="gj_address"><strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</p>
							<p id="mp_address" style="display:none;"><strong>Billing Address:</strong>A-312, The Bellaire Campus, Abbas Nagar Road, Near Asharam Square, Gandhinagar, Bhopal,M.P. - 462036.</p>
							<p id="mh_address" style="display:none;"><strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</p>
							<p><strong>Courier Address:</strong> Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.							
							</p>
							<p><strong>PAN No.:</strong> AAAFY3210E
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<strong>GST No.:  </strong>
								<input readonly name="gstno" id="gstno" value="24AAAFY3210E1ZS"  style="width:160px;float:none;display: inline;"/>									
							
							</p>
							<p>9) YashNand Engineers & Contractors has right to cancel order any time without any prior notice.</p>
							<p>10) Payment will be done <input type="text" name="payment_days" id="payment_days" value="" style="width:60px;float:none;display: inline;" /> days after date of delivery on site or bill submission which ever is later.</p>
							
							
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Remarks/Note</div>
                            <div class="col-md-10"><pre style="background:none;border:0px;font-size:15px;padding:0;"><textarea name="remarks" class="form-control"></textarea></pre></div>
                        </div> 
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
					</div>
				<?php $this->Form->end(); ?>
			</div>
			</div>
<?php }?>
         </div>
		