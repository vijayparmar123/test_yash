<?php 
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery("body").on("click", ".btn-edit-subgroup", function(){
		jQuery('#load_modal_edit_subgroup .modal-content').html('');
		jQuery("#load_modal_subcategory").modal('hide');
		var subgroup_id = jQuery(this).attr("data-id");
		
		var curr_data = { subgroup_id: subgroup_id };
					
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editmaterialsubgroup'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				jQuery('#load_modal_edit_subgroup .modal-content').append(response);		
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
	
	<form name="add_subgroup_form" action="" method="post" class="form-horizontal" id="subgroup_form">
		<div class="form-row">
			<label class="col-md-4" for="transfer_to" >
				Sub-Category Name
			</label>
			<div class="col-md-6">
				<input type="hidden" id="sub-category-material-code" name="material_code" value="<?php echo $material_code; ?>">
				<input name="sub-category-value" id="sub-category-value" value="" class="validate[required] form-control"/>
			</div>
		</div>
		<div class="form-row">
			<div class="col-sm-4">
				<input type="button" value="Add" name="save-sub-category-value" class="btn btn-primary" id="save-sub-category-value"/>
			</div>
		</div>
	</form>
	
	<table border='1px' class="table-bordered" id="sub-group-listing">
	<thead>
		<tr>
			<th>Sub Group Name</th>
			<th>Material Group / Item</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($subgroup_data as $retrive){?>
		<tr>
			<td id="sub_group_name_<?php echo $retrive["sub_group_id"]; ?>"><?php echo $retrive["sub_group_title"]; ?></td>
			<td><?php echo $this->ERPfunction->get_vendor_group_name($retrive["material_group_id"]); ?></td>
			<td><a class="btn-edit-subgroup badge badge-info" data-toggle="modal" data-target="#load_modal_edit_subgroup" href="#" data-id="<?php echo $retrive["sub_group_id"]; ?>"><i class="icon-edit"></i></a></td>
		</tr>
		<?php }?>
	</tbody>
	</table>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>