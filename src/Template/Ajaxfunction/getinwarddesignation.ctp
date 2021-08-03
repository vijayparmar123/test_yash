<?php ?>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title">Add Designation</h4>
</div>
<div class="modal-body clearfix">
<table class="table table-bordered table-striped table-hover">
	<thead>
  			<tr>
                <!--  <th>#</th> -->
                <th>Designation</th>
              
                <th><?php echo __('Action');?></th>
            </tr>
        </thead>
		<?php 

        	$i = 1;
        	if(!empty($designation))
        	{
        		
        		foreach ($designation as $retrieved_data)
        		{
        		// debug($retrieved_data);die;
        		echo '<tr id="cat-'.$retrieved_data['cat_id'].'">';
        		//echo '<td>'.$i.'</td>';
        		echo '<td>'.$retrieved_data['category_title'].'</td>';
				
  				echo '<td id='.$retrieved_data['cat_id'].'><a class="btn-edit-designation badge badge-info" model="material_group" href="#" id='.$retrieved_data['cat_id'].'><i class="icon-edit"></i></a>';
				
				
				echo '</td>';
        		echo '</tr>';
        		$i++;		
        		}
        	}
        ?>
</table>
<div class="controls">
<form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
  	 	<div class="form-row">
			<!-- <label class="col-sm-4 control-label" for="item_code">Type<span class="require-field">*</span></label> -->
			<div class="col-sm-4">
				<input id="item_code1" class="form-control text-input" type="hidden" 
				value="inward_designation" name="item_code">
			</div>
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="item_name">Designation Name<span class="require-field">*</span></label>
			<div class="col-sm-4">
				<input id="item_name1" class="form-control text-input" type="text" 
				value="" name="item_name">
			</div>
		</div>
					
		<div class="form-row">
		<div class="col-sm-4">
				<input type="button" value="Add" name="save_group" class="btn btn-primary" id="btn-add-designation"/>
			</div>
		</div>
		
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>