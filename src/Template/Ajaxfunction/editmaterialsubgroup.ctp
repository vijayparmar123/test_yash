<?php 
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery("body").on("click", "#update-sub-category-value", function(){
		
		var subgroup_id = jQuery("#sub-category-id-value").val();
		var subgroup_title = jQuery("#sub-category-name-value").val();
		
		if(subgroup_title == "")
		{
			alert("Please Enter Sub Category Name.");
			return false;
		}
		var curr_data = { subgroup_id: subgroup_id , subgroup_title : subgroup_title };
					
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updatematerialsubgroup'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				if(response)
				{
					$('#material-sub-category option[value="' + subgroup_id + '"]').text(subgroup_title);
					jQuery('#load_modal_edit_subgroup .modal-content').html('');
					jQuery("#sub_group_name_"+subgroup_id).html(subgroup_title);
					jQuery("#load_modal_edit_subgroup").modal('hide');		
					jQuery("#load_modal_subcategory").modal('show');
				}	
			},
			error: function (tab) {
				alert('error');
			}
		});
	});
});
</script>

<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> <?php echo "Add Material Sub-Category"; ?></h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	
	<form name="edit_subgroup_form" action="" method="post" class="form-horizontal" id="edit_subgroup_form">
		<div class="form-row">
			<label class="col-md-4" for="transfer_to" >
				Edit Sub-Category
			</label>
			<div class="col-md-6">
				<input type="hidden" id="sub-category-id-value" name="subcategory_id" value="<?php echo $row->sub_group_id; ?>">
				<input name="sub-category-name-value" id="sub-category-name-value" value="<?php echo $row->sub_group_title; ?>" class="validate[required] form-control"/>
			</div>
		</div>
		<div class="form-row">
			<div class="col-sm-4">
				<input type="button" value="Update" name="update-sub-category-value" class="btn btn-primary" id="update-sub-category-value"/>
			</div>
		</div>
	</form>
	
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>