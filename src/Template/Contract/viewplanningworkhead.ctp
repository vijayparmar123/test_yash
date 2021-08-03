<?php
//$this->extend('/Common/menu')
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#date_of_birth,#as_on_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
        }); 
} );
</script>	

<div class="col-md-10" >	
<?php 
//if(!$is_capable)
	//{
		//$this->ERPfunction->access_deniedmsg();
	//}
//else
//{
?>	

			
                <div class="block block-fill-white">					
					<div class="head bg-default bg-light-rtl">
						<h2>View Work Head</h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Contract','planningworkheadlist');?>" class="btn btn-success"><span class="icon-arrow-left"> </span> Back</a>
						</div>
					</div>
								
				
                    <div class="content controls">
						<div class="form-row">
							<div class="col-md-2">Code<span class="require-field">*</span>  :</div>
                            <div class="col-md-4"><input type="text" name="head_code" value="<?php echo $head_data['work_head_code'];?>" class="form-control validate[required]" readonly="true" /></div>
						</div>
						
                        <div class="form-row">
							<div class="col-md-2">Type of Contract:</div>
                            <div class="col-md-4">
								<select class="select2" disabled style="width: 100%;" name="type_of_contract" id="type_of_contract" >
									<option value="">--Select Contract--</Option>
									<?php 
										$contract_list = $this->ERPfunction->contract_type_list();
									   foreach($contract_list as $retrive_data)
									   {
											$select = ($head_data['type_of_contract'] == $retrive_data['id'])?'selected':'';
											 echo '<option value="'.$retrive_data['id'].'"'.$select.'>'.
											 $retrive_data['title'].'</option>';
									   }
									?>
								</select>
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2">Work Head Title<span class="require-field">*</span>  :</div>
                            <div class="col-md-4"><input type="text" name="head_title" value="<?php echo $head_data['work_head_title'];?>" class="form-control validate[required]" readonly="true"/></div>
						</div>
						
						
				</div>
				
			</div>
<?php //} ?>
         </div>