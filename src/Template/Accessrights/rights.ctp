<script>
jQuery(document).ready(function() {
	//if($('.controls').find('div.allcheckbox').length === 0)
	// if ( $(".allcheckbox").parents(".controls").length == 1 ) { 
		 
		// $( ".send" ).click(function(){ 
		 
		  // if (!($('.check').is(':checked'))){
		  
			  // alert('Please Select one AccessRights.');
			  // return false;
		  // }
		// });
	// }
	
	$('.main_access').each(function() {
		if(this.checked) {
			// Loop to make all nearest checkbox enabled when main checkbox is checked
			$(this).closest('tr').find("input").each(function() {
				$(this).attr("disabled", false);
			}); 
		}else{
			// Loop to make all nearest checkbox disabled and uncheck when main checkbox is unchecked
			$(this).closest('tr').find("input").each(function() {
				$(this).attr("disabled", true);
				$(this).removeAttr('checked');
				$(this).parent().removeClass('checked');
			});
			$(this).attr("disabled", false);
		} 
	});
	
	$(".main_access").change(function() {
		if(this.checked)
		{
			// Loop to make all nearest checkbox enabled when main checkbox is checked
			$(this).closest('tr').find("input").each(function() {
				$(this).attr("disabled", false);
			});
			$(this).attr("disabled", false);
		}
		else{
			// Loop to make all nearest checkbox disabled and uncheck when main checkbox is unchecked
			$(this).closest('tr').find("input").each(function() {
				$(this).attr("disabled", true);
				$(this).removeAttr('checked');
				$(this).parent().removeClass('checked');
			});
			
			$(this).attr("disabled", false);
		}
	});
});	
</script>	
<?php if ($role=="erphead"){ ?>
<div class="col-md-10" >
    
	<div class="row">
	<?php echo $this->Form->Create('form1',['id'=>'asset_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'rights',$accessrole,$modulename]]);?>
		<div class="col-md-12">
			<div class="block">
				
				<div class="head bg-default bg-light-rtl">
					<h2>View Rights </h2>
					<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Usermanage','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
				</div>
				
				<div class="content">
				
					<div class="col-md-5 filter-form">
						<div class="form-row">						
							<div class="col-md-4">Designation <span class="require-field">*</span></div>
							<div class="col-md-7">
							
								<select class="select2" required="true"  style="width: 100%;" name="role">
									<option value="">--Select Designation--</Option>
									<?php 
										foreach($designations as $retrive_data)
										{ 
											echo '<option value="'.$retrive_data['value'].'" 
											'.$this->ERPfunction->selected($retrive_data['value'],$accessrole).'>'.
											$retrive_data['title'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-5 filter-form">
						<div class="form-row">						
							<div class="col-md-3">Module <span class="require-field">*</span></div>
							<div class="col-md-7">
							
								<select class="select2" required="true"  style="width: 100%;" name="module">
									<option value="">--Select Module--</Option>
									<?php 
										foreach($modules as $modules_data)
										{
											echo '<option value="'.$modules_data['value'].'" 
											'.$this->ERPfunction->selected($modules_data['value'],$modulename).'>'.
											$modules_data['title'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-2 filter-form">
						<div class="form-row">	
							<button type="submit" class="btn btn-primary" name="go">Go</button>
						</div>
					</div>
				
				</div>
			</div>
		</div>
		<div class="block block-fill-white col-md-12 accessrights">
			<div class=" content controls">
				<?php if(isset($modulename)){?>
				<div class=" col-md-12 alloted" style="padding-top:15px">
				<h4 style="float:left;">Alloted Project :  </h4> <input type="checkbox" name="alloted" <?php  if(isset($alloted_data)){if($alloted_data == 1){ ?> checked="checked" <?php } }?>>	   
				</div>
				<div class="col-md-12 ">
				<?php if(isset($notificationlist)){  } else{ $notificationlist=array(); } ?>
					<div class=" alloted head bg-default bg-light-rtl" style="background-color: #133959 !important;">
						<div class="col-sm-2"><h2>PO Notification</h2><input type="checkbox" class=" check" name="notification[po_notification]" <?php  if(in_array('po_notification',$notificationlist)){ ?> checked="checked" <?php } ?>></div>
						<div class="col-sm-2"><h2>WO Notification</h2><input type="checkbox" class=" check" name="notification[wo_notification]" <?php  if(in_array('wo_notification',$notificationlist)){ ?> checked="checked" <?php } ?>></div>
						<div class="col-sm-3"><h2>Payslip Notification</h2><input type="checkbox" class=" check" name="notification[payslip_notification]" <?php  if(in_array('payslip_notification',$notificationlist)){ ?> checked="checked" <?php } ?>></div>
						<div class="col-sm-3"><h2>Payment Notification</h2><input type="checkbox" class=" check" name="notification[payment_notification]" <?php  if(in_array('payment_notification',$notificationlist)){ ?> checked="checked" <?php } ?>></div>
						<div class="col-sm-2" style="padding:0px;"><h2>P&M Notification</h2><input type="checkbox" class=" check" name="notification[p&m_notification]" <?php  if(in_array('p&m_notification',$notificationlist)){ ?> checked="checked" <?php } ?>></div>
						<div class="col-sm-2" style="padding:0px;"><h2>Asset Notification</h2><input type="checkbox" class=" check" name="notification[asset_notification]" <?php  if(in_array('asset_notification',$notificationlist)){ ?> checked="checked" <?php } ?>></div>
						<div class="col-sm-3"><h2>Asset PO Notification</h2><input type="checkbox" class=" check" name="notification[assetpo_notification]" <?php  if(in_array('assetpo_notification',$notificationlist)){ ?> checked="checked" <?php } ?>></div>
						
					</div>
				</div>
				<?php } ?>
				<div class=" col-md-12" style="padding-top:15px">
					<?php 
					
						$module_tab=$this->ERPfunction->get_modulewise_tab($modulename);
						$i=1;
						$j=0;
						
						foreach($module_tab as $key=>$value){ ?>
							<div class="head bg-default bg-light-rtl" style="width:100%; float:left;">
								
									<a class="accordion-toggle <?php if($i!=1) echo "collapsed"; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?php  echo $i;  ?>">
										<h2 class="accessrights_module">&nbsp;&nbsp;
										<?php echo __($key); ?>
										</h2> 
									</a>	
									<span class="righticonn">
										<a class="accordion-toggle <?php if($i!=1) echo "collapsed"; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?php  echo $i;  ?>">
										<i class="icon-plus"></i>
										</a>
									</span>
							</div>
							<div class="col-md-12 allcheckbox"  style="float:left;">
								<div id="collapseOne<?php  echo $i;  ?>" class="panel-collapse collapse <?php if($i==1){ echo "in"; } ?>">
								<?php foreach($value as $key1=>$value1){ 
								?>
									<h4 class="panel-title">
										<?php echo __($key1); ?>
									</h4>
									<table class="pr_list dataTables_wrapper table table-striped">
										<tbody>
										<tr>
										
										<?php foreach($value1 as $key2=>$value2){ ?>
											<td>
												<div class="checkbox_div" style="float:left;">
													<span><?php echo $key2; ?></span><input type="checkbox" class="<?php if($key2=='View'){ echo "main_access"; } ?> check" name="access[<?php echo $value2;?>]"  <?php  if(in_array($value2,$get_accessdata)){ ?> checked="checked" <?php } ?>>	   
												</div>
											</td>
										<?php  $j++; } ?>
										</tr>
										</tbody>
									</table>
							
							<?php  } ?>
							</div>
						</div>
						<?php $i++; } ?>
				<?php if(!empty($module_tab)) { ?>		
					<div class="col-md-2 filter-form">
						<div class="form-row">	
							<button type="submit" class="btn btn-primary send" name="submit">Save</button>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
		<?php $this->Form->end(); ?>
	</div>
</div>
<?php }
else{
		$this->ERPfunction->access_deniedmsg();
}
 ?>