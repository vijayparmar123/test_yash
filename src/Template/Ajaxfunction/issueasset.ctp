<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#issue_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
			maxDate: new Date(),
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
    }); 
	// jQuery('.select2').select2();
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
	<h4 class="modal-title"> Asset Issue</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<?php echo $this->Form->Create('medicinecat_form',['url'=>['controller'=>'Assets', 'action'=>'issueasset'],'class'=>'form_horizontal formsize','method'=>'post','id'=>'medicinecat_form','enctype'=>'multipart/form-data']);?>
  	 	<input type="hidden" name="asset_id" value="<?php echo $asset_id;?>">
				
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
				Project
			</label>
			<div class="col-sm-6">
				<input type="hidden" name="project_id" value="<?php echo $asset_data['deployed_to']; ?>">
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
			<label class="col-sm-4 control-label" for="issue_to">
				Issued To
			</label>
			<div class="col-sm-6">
				<input class="form-control" name="issue_to"/>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label">
				Issue Date
			</label>
			<div class="col-sm-6">
				<input id="issue_date" class="form-control text-input" onkeydown="return false" type="text" 
				value="" name="issue_date">
			</div>			
		</div>
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Issue" name="issue_aaset" class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
  	<?php echo $this->Form->end(); ?>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>