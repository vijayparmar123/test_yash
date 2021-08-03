<?php
use Cake\Routing\Router;
?>

<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Edit Material</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form name="medicinecat_form" action="editprmaterial" method="post" class="form-horizontal transferform">
  	 	<input type="hidden" name="pr_material_id" value="<?php echo $pmid;?>">		
		<div class="form-row">
			<label class="col-sm-4 " for="transfer_to" >
				Select Material
			</label>
			<div class="col-sm-8">
				<select class="select2 material_id" style="width: 100%;" required="true" name="material_id" id="material_id_0" data-id="0">
					<option value="">--Select Material--</Option>
					<?php 
						foreach($material_list as $retrive_data)
						{
							$selected = ($retrive_data['material_id'] == $mid)?"selected":"";
							echo '<option value="'.$retrive_data['material_id'].'"'.$selected.'>'.
							$retrive_data['material_title'].'</option>';
						}
					?>
				</select>				
			</div>
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 " for="transfer_to" >
				Make Source
			</label>
			<div class="col-sm-8">
				<?php $brands = $this->ERPfunction->get_brands_by_material_id($mid); ?>
				<select class="select2"  required="true"   name="brand_id" style="width: 100%;" id="brand_id">
					<option value="">--Select Item--</Option>
					<?php 
						if($brands != "")
						{
							foreach($brands as $brand)
							{
								echo '<option value="'.$brand['brand_id'].'"'.$this->ERPfunction->selected($brand['brand_id'],$bid).'>'.$brand['brand_name'].'</option>';
							}
						}
					?>												
				</select>
			</div>			
		</div>
		<div class="form-row">
			<label class="col-sm-4 " for="transfer_to" >
				Quantity
			</label>
			<div class="col-sm-8">
				<input class="form-control" name="quantity" value="<?php echo $quantity;?>">
			</div>			
		</div>
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Update Material" name="editprmaterial " class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>