<?php
	if($party_type == "oldparty")
	{
?>
		<div class="col-md-2" style="padding-bottom:15px;">Party's Name*</div>
		<div class="col-md-10" style="padding-bottom:15px;">
			<select name="party_id" class="select2" id="party_id" required="true" style="width: 100%;">
					<option value="">--select party--</option>
					<?php
                            			if($vendor_info){
                            				foreach($vendor_info as $vendor_row){
                            					?>
													<option value="<?php echo $vendor_row['user_id']; ?>" dataid="<?php echo $vendor_row['email_id'];?>" party-name="vandor" <?php 
																if(isset($update_inward)){
																	if($update_inward['party_name'] == $vendor_row['user_id']){
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
												<option value="<?php echo $agency['agency_id']; ?>" dataid="<?php echo $agency['email_id'];?>" party-name="agency" <?php 
																if(isset($update_inward)){
																	if($update_inward['party_name'] == $agency['agency_id']){
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
		<div class="col-md-2" style="padding-bottom:15px;">Party's E-mail ID</div>
		<div class="col-md-10" style="padding-bottom:15px;">
			<input type="text" name="party_email" id="party_email" class="form-control">
			<input type="hidden" name="party_name" id="party_name" class="form-control">
		</div>
		
<?php
	}
	else
	{
?>
		<div class="col-md-2" style="padding-bottom:15px;">Party's Name*</div>
		<div class="col-md-10" style="padding-bottom:15px;">
			<select name="party_id" class="select2" id="party_id" required="true" style="width: 100%;">
					<option value="">--select party--</option>
					<?php
                            			if($new_party){
                            				foreach($new_party as $retrive_data){
                            					?>
												<option value="<?php echo $retrive_data; ?>"><?php echo $retrive_data; ?></option>

                            					<?php
                            				}
                            			}
					?>
			</select>
		</div>
		<div class="col-md-2">Party's E-mail ID</div>
		<div class="col-md-10">
			<input type="text" name="party_email" id="party_email" class="form-control">
		</div>
<?php
	}
?>