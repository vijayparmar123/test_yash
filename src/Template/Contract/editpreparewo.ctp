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
	
	var po_sum = 0;
	jQuery('.amount').each(function(){
			var single_po_amount = jQuery(this).val();
			po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
	});
	jQuery('#total_wo_amount').html();
	jQuery('#total_wo_amount').html(po_sum.toFixed(2));
	
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'workhead'));?>",
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
		if(state == 'gujarat')
		{
			$(".gj_address").css("display","block");
			$(".mp_address").css("display","none");
			$(".mh_address").css("display","none");
			$(".haryana_address").css("display","none");
		}
		else if(state == 'mp')
		{
			$(".mp_address").css("display","block");
			$(".gj_address").css("display","none");
			$(".mh_address").css("display","none");
			$(".haryana_address").css("display","none");
		}
		else if(state == 'maharastra')
		{
			$(".gj_address").css("display","none");
			$(".mp_address").css("display","none");
			$(".mh_address").css("display","block");
			$(".haryana_address").css("display","none");
		}
		else if(state == 'haryana')
		{
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
		
		if(type_id == 1 || type_id == 3 || type_id == 4)
		{
			$("#remark_1").css("display","block");
			$("#remark_2").css("display","none");
		}
		else if(type_id == 5 || type_id == 6 || type_id == 7 || type_id == 2)
		{
			$("#remark_2").css("display","block");
			$("#remark_1").css("display","none"); 
		}
		else
		{
			$("#remark_1").css("display","block");
			$("#remark_2").css("display","none");
		}
						
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
	
	jQuery("#add_newrow").click(function(){
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
		
		jQuery.ajax({
                     type: 'POST',
                     url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowwo"]);?>',
                     data : {row_id:row_id},
                     success: function (response)
                        {	
                            jQuery("tbody").append(response);
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
	
	jQuery('body').on('blur','.quantity',function(){
		
		var row_id = jQuery(this).attr('data-id');
		count_total(row_id);
		
    });
		
	jQuery("body").on("change",".unit_rate",function(){
		
		var row_id = jQuery(this).attr('data-id');
		count_total(row_id);
		
	});
	
	jQuery("body").on("change",".tx_count",function(){
		
		var row_id = jQuery(this).attr('data-id');
		count_total(row_id);
		
	});
	
	$("body").on("click",".del_parent",function(){
		var detail_id = jQuery(this).attr('detail-id');
		if(detail_id)
		{
			if(confirm('Are you Sure Delete this Material?'))
				{
					if(confirm('Are you Sure Delete this Material?'))
					{
						if(confirm('Are you Sure Delete this Material?'))
						{
			var curr_data = {	 						 					
	 					detail_id : detail_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'deletewodetail'));?>",
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
		}
		else
		{
			$(this).parents("tr").remove();
		}
		
		var po_sum = 0;
		jQuery('.amount').each(function(){
				var single_po_amount = jQuery(this).val();
				po_sum = parseFloat(parseFloat(po_sum)+parseFloat(single_po_amount));  
		});
		jQuery('#total_wo_amount').html();
		jQuery('#total_wo_amount').html(po_sum.toFixed(2));
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
	
	$("body").on("change","#loading",function(){
		var check = $(this).attr("checked");		
		if(check)
		{
			$("#show_loading").css("display","none");
		}else{$("#show_loading").css("display","block");}
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
                            <div class="col-md-2">W.O.No:</div>
                            <div class="col-md-4">
							
								<input type="text" name="wo_no" id="wo_no" value="<?php echo $wo_data['wo_no']; ?>" class="form-control" value=""/>
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
						
						<!--<div class="form-row">						
                            <div class="col-md-2">Target Date:</div>
							 <div class="col-md-4">
							<input type="text" name="target_date" id="target_date" value="<?php echo $target_date ; ?>" class="form-control target_date"/>
							</div>
						</div>-->
							
						<div class="form-row" style="overflow:scroll">						
                            <table class="table table-bordered">
								<thead>
									<tr>
										<th rowspan="2" class="text-center">Contract Item No</th>
										<th colspan="9" class="text-center">Work/ Item</th>
										<th rowspan="2" class="text-center">Amount (Inclusive All)</th>
										<th rowspan="2" class="text-center">Delete</th>
									</tr>
									<tr>
										<th class="text-center">Work Head</th>
										<th class="text-center">Description</th>
										<th class="text-center">Quantity</th>
										<th class="text-center">Unit</th>	
										<th class="text-center">Unit Rate(As Per Note-1)(Rs.)</th>
										<th class="text-center">Dis<br>(%)</th>
										<th class="text-center">CGST<br>(%)</th>
										<th class="text-center">SGST<br>(%)</th>
										<th class="text-center">IGST<br>(%)</th>
									</tr>
								</thead>
								<tbody>	

		<?php
			$i = 0;
			foreach($wod_data as $data)
			{
		?>
		<tr id="row_id_<?php echo $i; ?>">
			<td>
				<input type="hidden" name="material[wo_detail_id][]" value="<?php echo $data['wo_detail_id']; ?>">
				<input type="text" name="material[contract_no][]" value="<?php echo htmlspecialchars($data['contract_no']); ?>" id="contract_no_<?php echo $i; ?>" class="contract_no" data-id="<?php echo $i; ?>" style="width:130px;">
				<input type="hidden" value="<?php echo $i; ?>" name="row_number" class="row_number">
			</td>
			
			<td>
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
			</td>
			
			<td>
				<input type="text" name="material[material_name][]" value="<?php echo htmlspecialchars($data['material_name']); ?>" class="validate[required]" id="material_name_<?php echo $i; ?>" class="material_name" style="width:120px;">
			</td>
			
			<td> 
				<input type="text" name="material[quantity][]" value="<?php echo htmlspecialchars($data['quentity']); ?>" class="quantity" data-id="<?php echo $i; ?>" id="quantity_<?php echo $i; ?>"/>
			</td>
			
			<td>
				<input type="text" name="material[unit][]" value="<?php echo htmlspecialchars($data['unit']); ?>" id="unit_<?php echo $i; ?>" class="form-control" style="width:80px;">
			</td>
			
			<td>
				<input type="text" name="material[unit_rate][]" value="<?php echo $data['unit_rate']; ?>" class="unit_rate" data-id="<?php echo $i; ?>" id="unit_rate_<?php echo $i; ?>" style="width:80px" />
			</td>
			
			<td>
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
			</td>
			
			<td>
				<input type="text" name="material[amount][]" value="<?php echo $data['amount']; ?>" class="amount" id="amount_<?php echo $i; ?>" style="width:90px" />
			</td>
			
			<td>
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
			<td colspan="10" class="text-right"><b>Total Amount</b></td>
			<td id="total_wo_amount" style="padding-left:24px;">0</td>
			<td></td>
		</tr>
								</tfoot>
							</table>
                        </div>
						
						<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
						
						<button type="button" id="workhead_add" data-type="workhead_add" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal" style="">Insert Work Head </button>
						
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
<?php }?>
         </div>
		