<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#accept_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
			maxDate: new Date(),
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
    });
	jQuery('#release_date').datepicker({
			dateFormat: "dd-mm-yy",
			  changeMonth: true,
				changeYear: true,
				yearRange:'-65:+4',
				onChangeMonthYear: function(year, month, inst) {
					jQuery(this).val(month + "-" + year);
				}
						
		});	
	jQuery('.select2').select2();
	jQuery('.transferform').validationEngine();
	
		
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getoutwardno'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);	
					
					jQuery('#project_code').val(json_obj['project_code']);						
					//jQuery('#prno').val(json_obj['prno']);	
					$('#reference_no').attr('value',json_obj.reference_no);


					//return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	
});
</script>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Asset Transfer</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form name="medicinecat_form" action="acceptasset" method="post" class="form-horizontal acceptform">
  	 	<input type="hidden" name="asset_id" value="<?php echo $asset_id;?>">
		<input type="hidden" name="available_qty" value=<?php echo $quantity;?>>
		<input type="hidden" name="transfer_qty" value="1"/>
		<!--<div class="form-row">
			<label class="col-sm-4 " for="transfer_to" >
				Available Quantity
			</label>
			<div class="col-sm-4">
				<span><?php //echo $quantity;?></span>				
			</div>
		</div>-->
		
		<!--<div class="form-row">
			<label class="col-sm-4 " for="transfer_to" >
				Transfer Quantity
			</label>
			<div class="col-sm-4">
				<input name="transfer_qty" class="form-control validate[required,custom[integer],max[<?php //echo $quantity;?>]]" data-errormessage-range-overflow="Incorrect Quantity" />
			</div>			
		</div>-->
		
		<div class="form-row">
			<label class="col-sm-4 ">
				Asset Id
			</label>
			<div class="col-sm-6">
				<input disabled value="<?php echo $asset_data['asset_code'] ?>" class="form-control"/>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 ">
				Asset Name
			</label>
			<div class="col-sm-6">
				<input disabled value="<?php echo $asset_data['asset_name'] ?>" class="form-control"/>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_to">
				Transferred From
			</label>
			<div class="col-sm-6">
				<select class="" disabled style="width:100%;">
					<option value="">--Select Project--</Option>
					<?php 
						foreach($projects as $retrive_data)
						{
							$selected = ($retrive_data['project_id'] == $asset_data['deployed_to'])?"selected":""; 
							echo '<option value="'.$retrive_data['project_id'].'"'.$selected.'>'. $retrive_data['project_name'].'</option>';
						}
					?>
				</select>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_to">
				Transfer To
			</label>
			<div class="col-sm-6">
				<select class="" required="true" disabled style="width: 100%;" name="transfer_to" id="project_id">
					<option value="">--Select Project--</Option>
					<?php 
						$transfer_to_project = $this->ERPfunction->get_asset_last_transfer_project($asset_id);
						foreach($projects as $retrive_data)
						{
							$selected = ($transfer_to_project == $retrive_data['project_id'])?"selected":"";
							echo '<option value="'.$retrive_data['project_id'].'"'.$selected.' >'. $retrive_data['project_name'].'</option>';
						}
					?>
				</select>
			</div>			
		</div>
		<!--<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_date">
				Project Code
			</label>
			<div class="col-sm-4">
			<input type="text" name="project_code" id="project_code" value="" class="form-control validate[required]" value="" readonly="true"/>
			</div>
		</div>-->
		<div class="form-row">
			<label class="col-sm-4 control-label">
				Accept Date
			</label>
			<div class="col-sm-6">
				<input id="accept_date"  class="form-control text-input" onkeydown="return false" type="text" 
				value="" name="accept_date">
			</div>			
		</div>
		<div class="form-row">
			<label class="col-sm-4 control-label">
				Remarks
			</label>
			<div class="col-sm-6">
				<input id="remarks"  class="form-control text-input" type="text" 
				value="" name="remarks">
			</div>			
		</div>
		<div class="form-row">
			<label class="col-sm-4 control-label">
				Tentative Date of Release
			</label>
			<div class="col-sm-6">
				<input id="release_date"  class="form-control text-input" onkeydown="return false" type="text" 
				value="" name="release_date">
			</div>			
		</div>
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Accept" name="accept" class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>