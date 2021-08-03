<style>
.role_pop_content {
    height: 500px!important;
    overflow-y: scroll!important;
}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"><?php echo "Designation"; ?></h4>
</div>
<div class="modal-body clearfix role_pop_content ">
<table class="table table-bordered table-striped table-hover">
		<thead>
  			<tr>
                <!--  <th>#</th> -->
                <th><?php echo "Designation";?></th>
				
                <th><?php echo __('Action');?></th>
            </tr>
        </thead>
		<?php 
			
        	$i = 1;
        	$model = 1;
        	if(!empty($user_list))
        	{
        		
        		foreach ($user_list as $retrieved_data)
        		{
				//view
        		echo '<tr id="cat-'.$retrieved_data['id'].'">';
        		
        		echo '<td>'.$retrieved_data['title'].'</td>';
				
  				echo '<td id='.$retrieved_data['id'].'>';
				
				echo '&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-edit-cat badge badge-info" model='.$model.' id='.$retrieved_data['id'].'><i class="icon-edit"></i></a>';
				
				
				echo '</td>';
				
        		echo '</tr>';
				// Edit 
				echo '<tr id="cat-update-'.$retrieved_data['id'].'" style="display:none; "> ';
        		
        		echo '<td><input type="text" name="des_name" value="'.$retrieved_data['title'].'" id="category_'.$retrieved_data['id'].'"></td>';
				
  				echo '<td id='.$retrieved_data['id'].'>
				<a class="btn-cat-update-cancel btn btn-danger" model ='.$model.' href="#" id='.$retrieved_data['id'].'>Cancel</a>
				<a class="btn-cat-update btn btn-primary" model ='.$model.' href="#" id='.$retrieved_data['id'].'>Save</a>
				</td>';
				
        		echo '</tr>';
				
        		$i++;		
        		}
        	}
        ?>
</table>
	<div class="col-sm-12">
	<form name="medicinecat_form" action="" method="post" class="form-horizontal" id="form">	
		<div class="form-row">
			<label class="col-sm-4 control-label" for="1"><?php echo "Designation Title";?><span class="require-field">*</span></label>
			<div class="col-sm-4">
				<input id="des_name" class="form-control text-input " type="text" 
				value="" name="des_name">
			</div>
			<div class="col-sm-4">
			<input type="button" value="<?php echo "Save";?>" name="save_category" class="btn btn-primary" model="<?php echo $model;?>" id="btn-add-category"/>
		</div>
		</div>
		
  	</form>
	</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>