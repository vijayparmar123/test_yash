<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('#material_form').validationEngine();	
	
	
	jQuery("body").on("click", "#save", function(event){	 
	  var type_of_contract  = jQuery("#type_of_contract").val();
	  var work_head_code  = jQuery("#work_head_code").val();
	  var work_head_title  = jQuery("#work_head_title").val();
	  
	  var arr = work_head_code.split('/');
	  var new_number = parseInt(arr[1]) + 1;
	  var next_work_head_code = 'WH/'+new_number;
	  
	  if(type_of_contract == '' || work_head_code == '' || work_head_title == '')
	  {
		  alert('Please fill all field');
		  return false;
	  }
	  
	   var curr_data = {
	 					type_of_contract : type_of_contract, work_head_code : work_head_code , work_head_title :work_head_title				
	 					};	 				
	 	 jQuery.ajax({
	 	 	headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addworkhead'));?>",
                data:curr_data,
                async:false,
                success: function(response){
						
					if(response == 'duplicate')
					{
						alert('Duplicate entry , Please try again.');
						jQuery('#type_of_contract').select2("val", "");
						jQuery("#work_head_code").val(work_head_code);
						jQuery("#work_head_title").val('');
					}
					else
					{
						jQuery('select.work_head').append(response);
						jQuery('#type_of_contract').select2("val", "");
						jQuery("#work_head_code").val(next_work_head_code);
						jQuery("#work_head_title").val('');
					}
					return false;
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
	<h4 class="modal-title"> Add Work Head</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form id="workhead_form" method="post" class="form-horizontal">

						<div class="form-row">
                            <div class="col-md-4">Type of Contract<span class="require-field">*</span></div>
                            <div class="col-md-8">
								<select class="select2"  required="true"   style="width: 100%;" name="type_of_contract" id="type_of_contract">
									<option value="">--Select Contract--</Option>
									<?php 
										$contract_list = $this->ERPfunction->contract_type_list();
									   foreach($contract_list as $retrive_data)
									   {
											 echo '<option value="'.$retrive_data['id'].'">'.
											 $retrive_data['title'].'</option>';
									   }
									?>
								</select>
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-4">Work Head Code<span class="require-field">*</span></div>
							<div class="col-md-8">
								<input type="text" name="work_head_code" id="work_head_code" class="form-control"
								value="<?php echo $this->ERPfunction->generate_auto_id_work_head(); ?>" readonly="true"/>
							</div>
						</div>
		
						<div class="form-row">
							<div class="col-md-4">Head Name<span class="require-field">*</span></div>
                            <div class="col-md-8">
								<input type="text" name="work_head_title" id="work_head_title" class="form-control validate[required]"/>
							</div>
                        </div>
		
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="button" value="Add" id="save" name="go" class="btn btn-primary"/>
			</div>
		</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>