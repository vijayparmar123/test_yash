<?php
use Cake\Routing\Router;
?>
<?php

?>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Add Remark</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">

  	<?php echo $this->Form->create('medicinecat_form',['url'=>['controller'=>'Accounts', 'action'=>'updateremarks'],'method'=>'post', 'class'=>'form-horizontal transferform']); ?>
	<div class="form-row">
		<label class="col-sm-3" for="project_name" >Remark :</label>
		<div class="col-sm-8">
			<input type="hidden" name="uid" value="<?php echo $uid ?>">
			<textarea name="remark" id="remark"><?php echo $accept_bill_remarks ?></textarea>
		</div>
	</div>
	
	<div class="form-row">			
		<div class="col-sm-4">
			<input type="submit" value="Submit" name="update_remark" class="btn btn-primary" id="update-remark"/>
		</div>
	</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>