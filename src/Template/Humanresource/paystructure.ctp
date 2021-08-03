<?php
use Cake\Routing\Router;
$last_edit_by = isset($employee_data['paystructure_change_by'])?$this->ERPfunction->get_user_name($employee_data['paystructure_change_by']):'NA';
$last_edit = isset($employee_data['paystructure_change_date'])?date("m-d-Y",strtotime($employee_data['paystructure_change_date'])):'NA';
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	
	jQuery('#paystructure_approved_on').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			maxDate: new Date(),
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
	 
	
	jQuery("body").on("change", "#is_epf", function(){
		var epf_val = jQuery(this).val();
		
		if(epf_val == 'yes')
		{
			jQuery("#epf_section").css("display","block");
		}
		else {
			jQuery("#epf_section").css("display","none"); 
		}
	});	
	
	jQuery("body").on("change", "#is_esi", function(){
		var esi_val = jQuery(this).val();
		
		if(esi_val == 'yes')
		{
			jQuery("#esi_section").css("display","block");
		}
		else {
			jQuery("#esi_section").css("display","none"); 
		}
	});
	
	jQuery("body").on("change", "#eligible_bonus", function(){
		var eligible_bonus = jQuery(this).val();
		
		if(eligible_bonus == 'yes')
		{
			jQuery("#bonus_section").css("display","block");
		}
		else {
			jQuery("#bonus_section").css("display","none"); 
		}
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'checkSameMonthPaystructureHistory'));?>",
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
	
	$("body").on("click","#pay_structure_history",function(){
		var user_id = $(this).attr("user_id");
		var curr_data = {user_id:user_id};

		$.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
			url : "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'paystructurehistory'));?>",
			data : curr_data,
			type : "POST",
			async:false,
			success : function(response){
				$('.modal-content').html('');
				$('.modal-content').html(response);	
				$('#load_modal').modal('show');
			},
			beforeSend:function(){
				jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
			},
			error : function(e){
				console.log(e.responseText);
			}
		});
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
$employee_at=isset($employee_data['employee_at'])?$employee_data['employee_at']:'';
$first_name=isset($employee_data['first_name'])?$employee_data['first_name']:'';
$middle_name=isset($employee_data['middle_name'])?$employee_data['middle_name']:'';
$last_name=isset($employee_data['last_name'])?$employee_data['last_name']:'';
$date_of_birth=isset($employee_data['date_of_birth'])?$this->ERPfunction->get_date($employee_data['date_of_birth']):'';
$education=isset($employee_data['education'])?$employee_data['education']:'';
$year_of_passing=isset($employee_data['year_of_passing'])?$employee_data['year_of_passing']:'';
$gender=isset($employee_data['gender'])?$employee_data['gender']:'Male';
$marital_status=isset($employee_data['marital_status'])?$employee_data['marital_status']:'Single';
$experience=isset($employee_data['experience'])?$employee_data['experience']:'';
$as_on_date=isset($employee_data['as_on_date'])?$this->ERPfunction->get_date($employee_data['as_on_date']):'';
$pan_card_no=isset($employee_data['pan_card_no'])?$employee_data['pan_card_no']:'';
$driving_licence_no=isset($employee_data['driving_licence_no'])?$employee_data['driving_licence_no']:'';
$epf_no=isset($employee_data['epf_no'])?$employee_data['epf_no']:'';
$image_url=isset($employee_data['image_url'])?$employee_data['image_url']:'';
$mobile_no=isset($employee_data['mobile_no'])?$employee_data['mobile_no']:'';
$email_id=isset($employee_data['email_id'])?$employee_data['email_id']:'';
$name1=isset($employee_data['name1'])?$employee_data['name1']:'';
$relationship1=isset($employee_data['relationship1'])?$employee_data['relationship1']:'';
$contactno1=isset($employee_data['contactno1'])?$employee_data['contactno1']:'';
$name2=isset($employee_data['name2'])?$employee_data['name2']:'';
$relationship2=isset($employee_data['relationship2'])?$employee_data['relationship2']:'';
$contactno2=isset($employee_data['contactno2'])?$employee_data['contactno2']:'';
$blood_group=isset($employee_data['blood_group'])?$employee_data['blood_group']:'';
$payment=isset($employee_data['payment'])?$employee_data['payment']:'';
$designation=isset($employee_data['designation'])?$employee_data['designation']:'';
$basic_salary=isset($employee_data['basic_salary'])?$employee_data['basic_salary']:0;
$incentive=isset($employee_data['incentive'])?$employee_data['incentive']:'';
$total_salary=isset($employee_data['total_salary'])?$employee_data['total_salary']:0;
$monthly_pay=isset($employee_data['monthly_pay'])?$employee_data['monthly_pay']:0;
$cheque_name=isset($employee_data['cheque_name'])?$employee_data['cheque_name']:'';
$ac_no=isset($employee_data['ac_no'])?$employee_data['ac_no']:'';
$bank=isset($employee_data['bank'])?$employee_data['bank']:'';
$ifsc_code=isset($employee_data['ifsc_code'])?$employee_data['ifsc_code']:'';
$extra_payment=isset($employee_data['extra_payment'])?explode(',',$employee_data['extra_payment']):array();
$incentive_includes=isset($employee_data['incentive_includes'])?explode(',',$employee_data['incentive_includes']):array();
$adhaar_card_no = isset($employee_data['adhaar_card_no'])?$employee_data['adhaar_card_no']:"";
$da = isset($employee_data['da'])?$employee_data['da']:0;
$medical_allowance = isset($employee_data['medical_allowance'])?$employee_data['medical_allowance']:0;
$other_allowance = isset($employee_data['other_allowance'])?$employee_data['other_allowance']:0;
$hra = isset($employee_data['hra'])?$employee_data['hra']:0;
$food_allowance = isset($employee_data['food_allowance'])?$employee_data['food_allowance']:0;
$acco_allowance = isset($employee_data['acco_allowance'])?$employee_data['acco_allowance']:0;
$trans_allowance = isset($employee_data['trans_allowance'])?$employee_data['trans_allowance']:0;
$mobile_allowance = isset($employee_data['mobile_allowance'])?$employee_data['mobile_allowance']:0;
$mobile_cug = isset($employee_data['mobile_cug'])?$employee_data['mobile_cug']:0;
$ctc = isset($employee_data['ctc'])?$employee_data['ctc']:0;
$branch = isset($employee_data['branch'])?$employee_data['branch']:"";
$esi_no = isset($employee_data['esi_no'])?$employee_data['esi_no']:"";
$uan_no = isset($employee_data['uan_no'])?$employee_data['uan_no']:"";
$pay_type = isset($employee_data['pay_type'])?$employee_data['pay_type']:"";
$category = isset($employee_data['category'])?$employee_data['category']:"";
$change_date = isset($employee_data['change_date'])?date("F Y",strtotime($employee_data['change_date'])):"";

$is_esi = isset($employee_data['is_esi'])?$employee_data['is_esi']:"";
$is_epf = isset($employee_data['is_epf'])?$employee_data['is_epf']:"";

$eligible_bonus = isset($employee_data['eligible_bonus'])?$employee_data['eligible_bonus']:"";
$bonus = isset($employee_data['bonus'])?$employee_data['bonus']:"";
$payment_mode = isset($employee_data['payment_mode'])?$employee_data['payment_mode']:"";

$food_checked = ($food_allowance != "company_provided" && $food_allowance != "bill_paid")?"checked":"";
$acco_checked = ($acco_allowance != "company_provided" && $acco_allowance != "bill_paid")?"checked":"";
$trans_checked = ($trans_allowance != "company_provided" && $trans_allowance != "bill_paid")?"checked":"";
/* $mobile_checked = ($mobile_allowance != "company_provided" && $mobile_allowance != "bill_paid")?"checked":""; */
$mobile_checked = ($mobile_cug == 0 && $mobile_allowance != "bill_paid")?"checked":"";
$mobile_cug_checked = ($mobile_cug != 0 && $mobile_allowance != "bill_paid")?"checked":"";

$paystructure_approved_by = isset($employee_data['paystructure_approved_by'])?$employee_data['paystructure_approved_by']:"";
$paystructure_approved_on = (isset($employee_data['paystructure_approved_on']) && $employee_data['paystructure_approved_on'] != '')?date("d-m-Y",strtotime($employee_data['paystructure_approved_on'])):"";
$ref_document_no = isset($employee_data['ref_document_no'])?$employee_data['ref_document_no']:"";
// $show_food_box = ($food_checked == "checked") ? "style=display:inline;width:50%;":"style=display:none;width:50%";
// $show_acc_box = ($acco_checked == "checked") ? "style=display:inline;width:50%;":"style=display:none;width:50%";
// $show_trans_box = ($trans_checked == "checked") ? "style=display:inline;width:50%;":"style=display:none;width:50%";
// $show_mobile_box = ($mobile_checked == "checked") ? "style=display:inline;width:50%;":"style=display:none;width:50%";
// $show_mobile_cug_box = ($mobile_cug_checked == "checked") ? "style=display:inline;width:50%;":"style=display:none;width:50%";

$food_bill_paid = isset($employee_data['food_bill_paid'])?$employee_data['food_bill_paid']:"";
$acc_bill_paid = isset($employee_data['acc_bill_paid'])?$employee_data['acc_bill_paid']:"";
$trans_bill_paid = isset($employee_data['trans_bill_paid'])?$employee_data['trans_bill_paid']:"";
$mobile_bill_paid = isset($employee_data['mobile_bill_paid'])?$employee_data['mobile_bill_paid']:"";

$trans_bill_checked = ($trans_allowance === "bill_paid")?"checked":"";
$show_trans_bill = ($trans_bill_checked == "checked") ? "style=display:inline;width:50%;":"style=display:none;width:50%";
$food_bill_checked = ($food_allowance === "bill_paid")?"checked":"";
$show_food_bill = ($food_bill_checked == "checked") ? "style=display:inline;width:50%;":"style=display:none;width:50%";
$acc_bill_checked = ($acco_allowance === "bill_paid")?"checked":"";
$show_acc_bill = ($acc_bill_checked == "checked") ? "style=display:inline;width:50%;":"style=display:none;width:50%";
$mobile_bill_checked = ($mobile_allowance === "bill_paid")?"checked":"";
$show_mobile_bill = ($mobile_bill_checked == "checked") ? "style=display:inline;width:50%;":"style=display:none;width:50%";
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
							<div class="col-md-3 text-center"><a class='btn btn-primary' id='pay_structure_history' href='javascript:void(0);' user_id='<?php echo $id ?>'>Pay Structure History</a></div>
						</div>
						<div class="form-row"> <hr/>
                            <div class="col-md-2">Change Affect Date : <span class="require-field">*</span> </div>
                            <div class="col-md-2"><input type="text" name="change_date" value="<?php echo $change_date;?>"
							class="form-control validate[required]" id="date_of_birth" /></div>
						</div>
						
						<div class="header"> 
							<h2><u>PAYMENT DETAILS</u></h2>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Payment<span class="require-field">*</span> </div>
                            <div class="col-md-2">
							<select style="width: 100%;" class="validate[required]" name="payment">
								<option value="">--Select Payment--</option>
								<option value="Cash" <?php echo $this->ERPfunction->selected($payment,'Cash');?>>Cash</option>								
								<option value="Cheque" <?php echo $this->ERPfunction->selected($payment,'Cheque');?>>Cheque</option>							
								</select>							
							</div>
							<!--
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
						 -->
					   </div>
					   <!--<div class="form-row"> 
							<div class="col-md-2">ESI No*</div>
                            <div class="col-md-2"><input type="text" name="esi_no" value="<?php //echo $esi_no;?>" class="form-control validate[required]" /></div>
 
							<div class="col-md-2">EPF No*</div>
                            <div class="col-md-2"><input type="text" name="epf_no" value="<?php //echo $epf_no;?>" class="form-control validate[required]" /></div>
                        </div>-->
						<?php 
						 if($role != 'erpoperator' && $role != 'erpmanager')
						 {
						?>
						<div class="form-row"> 
							<div class="col-md-2">EPF</div>
                            <div class="col-md-2">
							<select style="width: 100%;" name="is_epf" id="is_epf">
								<option value="yes" <?php echo $this->ERPfunction->selected($is_epf,'yes');?>>Yes</option>
								<option value="no" <?php echo $this->ERPfunction->selected($is_epf,'no');?>>No</option>							
							</select>							
							</div>
							<div id="epf_section" style="<?php echo ($is_epf == "no")?"display:none":""; ?>">
							<div class="col-md-2">EPF No</div>
                            <div class="col-md-2"><input type="text" name="epf_no" value="<?php echo $epf_no;?>" class="form-control" /></div>
							
							<div class="col-md-2">UAN No</div>
                            <div class="col-md-2"><input type="text" name="uan_no" value="<?php echo $uan_no;?>" class="form-control" /></div>
							</div>
						</div>
						
						<div class="form-row"> 
							<div class="col-md-2">ESI</div>
                            <div class="col-md-2">
							<select style="width: 100%;" name="is_esi" id="is_esi">
								<option value="yes" <?php echo $this->ERPfunction->selected($is_esi,'yes');?>>Yes</option>
								<option value="no" <?php echo $this->ERPfunction->selected($is_esi,'no');?>>No</option>							
							</select>							
							</div>
							<div id="esi_section" style="<?php echo ($is_esi == "no")?"display:none":""; ?>">
							<div class="col-md-2">ESI No</div>
                            <div class="col-md-2"><input type="text" name="esi_no" value="<?php echo $esi_no;?>" class="form-control" /></div>
							</div>
						</div>
						
						<div class="form-row"> 
							<div class="col-md-2">Pay Type*</div>
                            <div class="col-md-2">
								<select name="pay_type" class="form-control validate[required]">
									<option value="" >Select Type</option>
									<option value="employee" <?php echo $this->ERPfunction->selected("employee",$pay_type);?>>Employee</option>
									<option value="consultant" <?php echo $this->ERPfunction->selected("consultant",$pay_type);?>>Labour</option>
									<option value="temporary" <?php echo $this->ERPfunction->selected("temporary",$pay_type);?>>Temporary</option>
									
								</select>
							</div>
                       
                        </div>
					   <div class="form-row"> <hr/>
                            <div class="col-md-2">Basic Pay(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-2"><input type="text" name="basic_salary" value="<?php echo $basic_salary;?>"
							class="form-control validate[required] basic_salary count_total" /></div>
                        
							<div class="col-md-4 text-right">D.A.(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-2"><input type="text" name="da" value="<?php echo $da;?>"
							class="form-control validate[required] da count_total" /></div>	
							
					   </div>
					   
					   <div class="form-row">                           							
							<div class="col-md-2"> H.R.A. (Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-2">
							<input name="acc_fixed" class="input-sm count_total acco_allowance validate[required,custom[integer],min[0]]" value="<?php echo $acco_allowance;?>">
							</div>
							
							<div class="col-md-4 text-right">Conveyance Allowance(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-2"><input type="text" name="hra" value="<?php echo $hra;?>"
							class="form-control validate[required] hra count_total" /></div>
						</div>
						
						<div class="form-row">                           							
							<div class="col-md-2"> T.A.(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-2">
							<input name="trans_fixed" class="input-sm count_total trans_allowance validate[required,custom[integer],min[0]]" value="<?php echo $trans_allowance;?>">
							</div>
							
							<div class="col-md-4 text-right">Special Allowance(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-2"><input name="food_fixed" class="input-sm fix_radio food_allowance count_total validate[required,custom[integer],min[0]]" value="<?php echo $food_allowance;?>"></div>
						</div>
						
					   <div class="form-row">
                            <div class="col-md-2">Medical Allowance(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-2"><input type="text" name="medical_allowance" value="<?php echo $medical_allowance;?>"
							class="form-control validate[required] medical_allowance count_total" /></div>
														
							<div class="col-md-4 text-right">Mobile Allowance(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-2"><input name="mobile_fixed" class="input-sm count_total mobile_allowance validate[required,custom[integer],min[0]]" value="<?php echo $mobile_allowance;?>"></div>
						</div>	
						
						
						<div class="form-row">                           							
							<div class="col-md-2"> Other Allowance (Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-2"><input type="text" name="other_allowance" value="<?php echo $other_allowance;?>"
							class="form-control validate[required] other_allowance count_total" /></div>
						</div>
						
						<!--<div class="form-row">  <hr/>
							<div class="col-md-3 text-right">Special Allowance(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-3 text-left">								
                                    <input type="radio" name="food_allowance" class="food_rad" value="company_provided" <?php echo $this->ERPfunction->checked($food_allowance,'company_provided');?>/> Provided By Company
									<br>
                                    <input type="radio" class="show_fix_input food_rad" name="food_allowance" value="fixed"  <?php echo $food_checked;?>/> Fix Paid
									<input name="food_fixed" class="input-sm fix_radio food_allowance count_total validate[required,custom[integer],min[0]]" <?php echo $show_food_box; ?> value="<?php echo ($food_checked!="")?$food_allowance:0;?>">
									<br>
									<input type="radio" name="food_allowance"  class="food_rad" value="bill_paid" <?php echo $this->ERPfunction->checked($food_allowance,'bill_paid');?>/> Bill Paid
									<input name="food_bill_paid" class="input-sm count_total food_bill_text validate[required]" <?php echo $show_food_bill;?> value="<?php echo ($food_bill_checked!="")?$food_bill_paid:0;?>">
                            </div>																
						<div class="col-md-3 text-right">H.R.A.(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-3">								
								<input type="radio" style="visibility:hidden" name="acco_allowance" class="acc_rad" value="company_provided" <?php echo $this->ERPfunction->checked($acco_allowance,'company_provided');?>/> Provided By Company
								<br>
								<input type="radio" name="acco_allowance" class="acc_rad" value="fixed" <?php echo $this->ERPfunction->checked($acco_allowance,'fixed');?> <?php echo $acco_checked;?>/> Fix Paid
								<input name="acc_fixed" class="input-sm count_total acco_allowance validate[required,custom[integer],min[0]]" <?php echo $show_acc_box;?> value="<?php echo ($acco_checked!="")?$acco_allowance:0;?>">
								<br>
								<input type="radio" name="acco_allowance" class="acc_rad" value="bill_paid" <?php echo $this->ERPfunction->checked($acco_allowance,'bill_paid');?>/> Bill Paid
								<input name="acc_bill_paid" class="input-sm acc_bill_text count_total validate[required]" <?php echo $show_acc_bill;?> value="<?php echo ($acc_bill_checked!="")?$acc_bill_paid:0;?>">
                                
							</div>
						</div>
					 <div class="form-row">	
							<div class="col-md-3 text-right">Transportation Allowance(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-3 text-left">								
								<input type="radio" name="trans_allowance" class="trans_rad"  value="company_provided" <?php echo $this->ERPfunction->checked($trans_allowance,'company_provided');?>/> Provided By Company
								<br>
								<input type="radio" name="trans_allowance" class="trans_rad" value="fixed" <?php echo $this->ERPfunction->checked($trans_allowance,'fixed');?> <?php echo $trans_checked;?>/> Fix Paid
								<input name="trans_fixed" class="input-sm count_total trans_allowance validate[required,custom[integer],min[0]]" <?php echo $show_trans_box;?> value="<?php echo ($trans_checked!="")?$trans_allowance:0;?>">
								<br>
								<input type="radio" name="trans_allowance" class="trans_rad" value="bill_paid" <?php echo $this->ERPfunction->checked($trans_allowance,'bill_paid');?>/> Bill Paid
								<input name="trans_bill_paid" class="input-sm count_total bill_text validate[required]" <?php echo $show_trans_bill;?> value="<?php echo ($trans_bill_checked!="")?$trans_bill_paid:0;?>">
                            </div>
							<div class="col-md-3 text-right">Mobile Allowance(Rs.)<span class="require-field">*</span> </div>
                            <div class="col-md-3 text-left">								
								<input type="radio" name="mobile_allowance" class="mobile_rad"  value="fixed_cug" id="cug" <?php echo $mobile_cug_checked;?>/> CUG Limit
								<input name="mobile_cug" class="input-sm cug_allowance validate[required,custom[integer],min[0]]" <?php echo $show_mobile_cug_box;?> value="<?php echo ($mobile_cug!=0)?$mobile_cug:0;?>">
								<br>
								<input type="radio" name="mobile_allowance" class="mobile_rad" value="fixed" id="fix" <?php echo $this->ERPfunction->checked($mobile_allowance,'fixed');?> <?php echo $mobile_checked;?>/> Fix Paid
								<input name="mobile_fixed" class="input-sm count_total mobile_allowance validate[required,custom[integer],min[0]]" <?php echo $show_mobile_box;?> value="<?php echo ($mobile_checked!="")?$mobile_allowance:0;?>">
								<br>
								<input type="radio" name="mobile_allowance" class="mobile_rad" value="bill_paid" <?php echo $this->ERPfunction->checked($mobile_allowance,'bill_paid');?>/> Bill Paid
								<input name="mobile_bill_paid" class="input-sm count_total mobile_bill_text validate[required]" <?php echo $show_mobile_bill;?> value="<?php echo ($mobile_bill_checked!="")?$mobile_bill_paid:0;?>">
                            </div>
					   </div>-->
					   <div class="form-row">                
                        <hr/>
							<div class="col-md-2">Monthly Pay(Rs.)*</div>
                            <div class="col-md-2"><input type="text" name="monthly_pay" readonly="true" value="<?php echo $monthly_pay;?>" class="form-control validate[required] monthly_pay count_total" /></div>
                           <!-- <div class="col-md-2">Incentives (Including All)</div>
                            <div class="col-md-2"><input type="text" name="incentive" 
							value=" <?php // echo $incentive;?>" class="form-control incentive count_total" /></div> -->
							 <div class="col-md-2">CTC(Month)(Rs.)*</div>
                            <div class="col-md-2"><input type="text" name="total_salary" readonly="true" value="<?php echo $total_salary;?>" class="form-control validate[required] total_salary count_total" /></div>
							
							<div class="col-md-2 text-right">CTC(Year)(Rs.)*</div>
                            <div class="col-md-2"><input type="text" name="ctc" readonly="true" value="<?php echo $ctc;?>" class="form-control validate[required] ctc count_total" /></div>
							
					   </div>
					   <div class="form-row">
                            <div class="col-md-2">Cheque Name</div>
                            <div class="col-md-2">
							<input type="text" name="cheque_name" value="<?php echo $cheque_name;?>" class="form-control" />
							</div>
							
                            <div class="col-md-2">A/C No </div>
                            <div class="col-md-2"><input type="text" name="ac_no" value="<?php echo $ac_no;?>"
							class="form-control" /></div>
                        
                            <div class="col-md-2 text-right">Bank</div>
                            <div class="col-md-2"><input type="text" name="bank" value="<?php echo $bank;?>" class="form-control" /></div>
					   </div>				  
					    <div class="form-row">						
						 <div class="col-md-2">Branch</div>
                            <div class="col-md-2"><input type="text" name="branch" value="<?php echo $branch;?>" class="form-control" /></div>
						
					    <div class="col-md-2 text-right">IFSC Code</div>
                        <div class="col-md-2"><input type="text" name="ifsc_code" value="<?php echo $ifsc_code;?>" class="form-control" /></div>
						
						<div class="col-md-2">Mode of payment*</div>
						<div class="col-md-2">
							<select name="payment_mode" class="form-control validate[required]">
								<option value="" >Select Mode</option>
								<option value="transfer" <?php echo $this->ERPfunction->selected("transfer",$payment_mode);?>>Transfer</option>
								<option value="neft" <?php echo $this->ERPfunction->selected("neft",$payment_mode);?>>NEFT</option>								
							</select>
						</div>
						</div>
						
						 <?php } ?>	
						
						<div class="form-row"> 
						<hr/>
							<div class="col-md-2">Eligible for Extra Bonus</div>
                            <div class="col-md-2">
							<select style="width: 100%;" name="eligible_bonus" id="eligible_bonus">
								<option value="yes" <?php echo $this->ERPfunction->selected($eligible_bonus,'yes');?>>Yes</option>
								<option value="no" <?php echo $this->ERPfunction->selected($eligible_bonus,'no');?>>No</option>							
							</select>							
							</div>
							<div id="bonus_section" style="<?php echo ($eligible_bonus == "no")?"display:none":""; ?>">
							<div class="col-md-2">Extra Bonus</div>
                            <div class="col-md-2"><input type="text" name="bonus" value="<?php echo $bonus;?>" class="form-control" /></div>
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2">Salary Change Approved By*</div>
                            <div class="col-md-2"><input type="text" name="paystructure_approved_by" value="<?php echo $paystructure_approved_by;?>" class="form-control validate[required]" /></div>
							 <div class="col-md-2">Approved On*</div>
                            <div class="col-md-2"><input type="text" onkeydown="return false" name="paystructure_approved_on" value="<?php echo $paystructure_approved_on;?>" class="form-control validate[required]" id="paystructure_approved_on" /></div>
							
							<div class="col-md-2 text-right">Ref. Doc. No.*</div>
                            <div class="col-md-2"><input type="text" name="ref_document_no" value="<?php echo $ref_document_no;?>" class="form-control validate[required]" /></div>
							
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
 
<script type="text/javascript">
jQuery(document).ready(function() {
	
jQuery('#date_of_birth,#as_on_date,#date_of_joining').datepicker({
		showButtonPanel: true,
		//minDate: 0,
		dateFormat: "dd-mm-yy",
		changeMonth: true,
	    changeYear: true,
			 // minDate: 2005,
			 // yearRange: '1940:2012',
        
        maxDate: +0,
        minDate: new Date(2019, 4 - 1, 25),
	        // yearRange:'-65:+0',
	        // onChangeMonthYear: function(year, month, inst) {
	            // jQuery(this).val(month + "-" + year);
	        // }			
			onClose: function(dateText, inst) {
			count_total_salary();
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
	
$(".create_field").click(function(){
	var label = $(".add_label").val();
	if(label == "")
	{
		alert("Please Enter File Name");
		return false;
	}
	$(".add_label").val("");
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='image_url[]'></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
	$(".add_field").append(field);
});

$("body").on("click",".del_file",function(){
	$(this).parentsUntil('.del_parent').remove();
});

jQuery("body").on("change", "#is_epf", function(){
	count_total_salary();
});	
	
jQuery("body").on("change", "#is_esi", function(){
	count_total_salary();
});
	
$("body").on("blur",".count_total",function(){
	count_total_salary();
});
	
function count_total_salary()
{
	var change_affected_date = $("#date_of_birth").val();
	change_affected_date = new Date(change_affected_date);
    var month = change_affected_date.getMonth();
    var year = change_affected_date.getFullYear();
	
    change_affected_date = new Date(year, month);
	var apply_date = new Date('2019', '6');
	var esi_percentage=4;
	if(change_affected_date >=  apply_date)
	{
		esi_percentage = 4;
	}else{
		esi_percentage = 4.75;
	}
	// alert(esi_percentage);
    // alert(change_affected_date.getMonth() + 1);
	// change_affected_date = 
    // var dS = change_affected_date.split("-");
	// alert(dS);
    // var d1 = new Date(dS[1], (+dS[0] - 1));
	// alert(d1);
	
	var basic = parseFloat($(".basic_salary").val());
	var da = parseFloat($(".da").val());
	var hra = parseFloat($(".hra").val());
	var medical_allowance = parseFloat($(".medical_allowance").val());
	var food_allowance = parseFloat($(".food_allowance").val());
	var acco_allowance = parseFloat($(".acco_allowance").val());
	var trans_allowance = parseFloat($(".trans_allowance").val());
	var mobile_allowance = parseFloat($(".mobile_allowance").val());
	var other_allowance = parseFloat($(".other_allowance").val());
	
	var total_salary = parseFloat(basic + da + hra + medical_allowance + food_allowance + acco_allowance + trans_allowance + mobile_allowance + other_allowance);
	
	$(".monthly_pay").val(Math.round(total_salary));
	var ctc_month = total_salary;
	
	var epf_status = $("#is_epf").val();
	if(epf_status == "yes")
	{
		var epf_effected_amount = parseFloat(Math.round(parseFloat(basic + food_allowance)*12/100));
		epf_effected_amount = parseFloat((epf_effected_amount > 1800) ? 1800 : epf_effected_amount);
		ctc_month = parseFloat(ctc_month + epf_effected_amount);
	}
	
	var esi_status = $("#is_esi").val();
	if(esi_status == "yes")
	{
		var esi_effected_amount = parseFloat(Math.round(total_salary*esi_percentage/100));
		esi_effected_amount = parseFloat((esi_effected_amount > 998) ? 998 : esi_effected_amount);
		ctc_month = parseFloat(ctc_month + esi_effected_amount);
	}
	var extra_amount = Math.round(total_salary*8.33/100);
	ctc_month = ctc_month + extra_amount;
	// var ctc = parseFloat((basic + da + hra + medical_allowance) * 13);
	$(".total_salary").val(ctc_month);
	$(".ctc").val(ctc_month * 12);
}

// $("body").on("change",".food_rad",function(){
	
	// var val = $(this).val();
	// if(val != "fixed")
	// {
		// var prv_val = parseFloat($(".food_allowance").val());
		// $(".food_allowance").val(0);
		// count_total_salary(prv_val)
		// $(".food_allowance").hide();	
	
	// }else{
		// $(".food_allowance").show().css({"display":"inline","width":"50%"});
	// }
	// if(val != "fixed")
	// {
		// var prv_val = parseFloat($(".food_allowance").val());
		// $(".food_allowance").val(0);
		// count_total_salary(prv_val)
		// $(".food_allowance").hide();
		
		// prv_val = parseFloat($(".food_bill_text").val());
		// $(".food_bill_text").val('');
		// count_total_salary(prv_val)
		// $(".food_bill_text").hide();	
	
	// }else{
		// var prv_val = parseFloat($(".food_bill_text").val());
		// $(".food_bill_text").val('');
		// count_total_salary(prv_val)
		// $(".food_bill_text").hide();
		// $(".food_allowance").show().css({"display":"inline","width":"50%"});
	// }
		
	// if(val == "bill_paid")
	// {
		// $(".food_allowance").hide();
		// $(".food_bill_text").show().css({"display":"inline","width":"50%"});
	// }
// });

// $("body").on("change",".acc_rad",function(){
	
	// var val = $(this).val();
	// if(val != "fixed")
	// {
		// var prv_val = parseFloat($(".acco_allowance").val());
		// $(".acco_allowance").val(0);
		// count_total_salary(prv_val)
		// $(".acco_allowance").hide();	
	
	// }else{
		// $(".acco_allowance").show().css({"display":"inline","width":"50%"});
	// }
	// if(val != "fixed")
	// {
		// var prv_val = parseFloat($(".acco_allowance").val());
		// $(".acco_allowance").val(0);
		// count_total_salary(prv_val)
		// $(".acco_allowance").hide();
		
		// var prv_val = parseFloat($(".acc_bill_text").val());
		// $(".acc_bill_text").val('');
		// count_total_salary(prv_val)
		// $(".acc_bill_text").hide();	
	
	// }else{
		// var prv_val = parseFloat($(".acc_bill_text").val());
		// $(".acc_bill_text").val('');
		// count_total_salary(prv_val)
		// $(".acc_bill_text").hide();	
		// $(".acco_allowance").show().css({"display":"inline","width":"50%"});
	// }
		
	// if(val == "bill_paid")
	// {
		// $(".acco_allowance").hide();
		// $(".acc_bill_text").show().css({"display":"inline","width":"50%"});
	// }
// });

// $("body").on("change",".trans_rad",function(){
	
	// var val = $(this).val();
	// if(val != "fixed")
	// {
		// var prv_val = parseFloat($(".trans_allowance").val());
		// $(".trans_allowance").val(0);
		// count_total_salary(prv_val)
		// $(".trans_allowance").hide();	
	
	// }else{
		// $(".trans_allowance").show().css({"display":"inline","width":"50%"});
	// }
	// if(val != "fixed")
	// {
		// var prv_val = parseFloat($(".trans_allowance").val());
		// $(".trans_allowance").val(0);
		// count_total_salary(prv_val)
		// $(".trans_allowance").hide();
		
		// var prv_val = parseFloat($(".bill_text").val());
		// $(".bill_text").val('');
		// count_total_salary(prv_val)
		// $(".bill_text").hide();	
	// }
	// else{
		// var prv_val = parseFloat($(".bill_text").val());
		// $(".bill_text").val('');
		// count_total_salary(prv_val)
		// $(".bill_text").hide();	
		// $(".trans_allowance").show().css({"display":"inline","width":"50%"});
	// }	
	
	// if(val == "bill_paid")
	// {
		// $(".trans_allowance").hide();
		// $(".bill_text").show().css({"display":"inline","width":"50%"});
	// }
// });

// $("body").on("change",".mobile_rad",function(){
	
	// var val = $(this).val();
	// if(val != "fixed" && val != "fixed_cug" )
	// {		
		// var prv_val = parseFloat($(".mobile_allowance").val());
		// $(".mobile_allowance").val(0);
		// $(".cug_allowance").val(0);
		// count_total_salary(prv_val)
		// $(".mobile_allowance").hide();
		// $(".cug_allowance").hide();
	// }else
	// {	
		// var atr = $(this).attr("id");
		// if(atr == "cug")
		// {
			// $(".cug_allowance").show().css({"display":"inline","width":"50%"});
			
			// var prv_val = parseFloat($(".mobile_allowance").val());
			// $(".mobile_allowance").val(0);
			// count_total_salary(prv_val)
			// $(".mobile_allowance").hide();
		// }
		// else
		// { /* atr == fix */
			// $(".mobile_allowance").show().css({"display":"inline","width":"50%"});
			
			// var prv_val = parseFloat($(".cug_allowance").val());
			// $(".cug_allowance").val(0);			
			// $(".cug_allowance").hide();
		// }
		
	// }	
	// if(val != "fixed" && val != "fixed_cug" )
	// {		
		// var prv_val = parseFloat($(".mobile_allowance").val());
		// $(".mobile_allowance").val(0);
		// $(".cug_allowance").val(0);
		// count_total_salary(prv_val)
		// $(".mobile_allowance").hide();
		// $(".cug_allowance").hide();
	// }else
	// {	
		// var atr = $(this).attr("id");
		// if(atr == "cug")
		// {
			// $(".cug_allowance").show().css({"display":"inline","width":"50%"});
			
			// var prv_val = parseFloat($(".mobile_allowance").val());
			// $(".mobile_allowance").val(0);
			// count_total_salary(prv_val)
			// $(".mobile_allowance").hide();
			
			// var prv_val = parseFloat($(".mobile_bill_text").val());
			// $(".mobile_bill_text").val('');
			// count_total_salary(prv_val)
			// $(".mobile_bill_text").hide();
		// }
		// else
		// { /* atr == fix */
			// $(".mobile_allowance").show().css({"display":"inline","width":"50%"});
			
			// var prv_val = parseFloat($(".cug_allowance").val());
			// $(".cug_allowance").val(0);			
			// $(".cug_allowance").hide();
			
			// var prv_val = parseFloat($(".mobile_bill_text").val());
			// $(".mobile_bill_text").val('');
			// count_total_salary(prv_val)
			// $(".mobile_bill_text").hide();
		// }
		
	// }
	
	// if(val == "bill_paid")
	// {
		// $(".cug_allowance").val(0);	
		// $(".cug_allowance").hide();
		// $(".mobile_allowance").val(0);
		// $(".mobile_allowance").hide();
		// $(".mobile_bill_text").show().css({"display":"inline","width":"50%"});
	// }
});
</script>