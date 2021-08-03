<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();  
});
</script>

<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>				
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2> ADD EXPENSE HEAD  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php ?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Name of Expense Head<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="expense_head_name" id="expense_head_name" value=""
							class="form-control validate[required]" value="" /></div>
							
                            
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Type of Expenses under above Head<span class="require-field">*</span></div>
                            <div class="col-md-10">
								<textarea name="expense_type" id="expense_type" class="form-control validate[required]"></textarea>
							</div>
                        </div>
						
						
					<!-- <div class="form-row">
                            <div class="col-md-2">Raised From:</div>
                            <div class="col-md-4">
								<?php 
								
								?>
								<select class="select2"  required="true"   style="width: 100%;" name="raise_from" id="raise_from">
								<option value="">--Select Project--</Option>
								<?php 
									// foreach($raise_from as $key => $data)
								// {
									// echo '<optgroup label="'.$this->ERPfunction->get_rolename($key).'" style = "text-transform: capitalize;">';
									// foreach($data as $user_data)
									// {
										// echo '<option value="'.$user_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($user_data['user_id']).'</option>';									
									// }
									// echo '</optgroup>';
								// }
								?>
								</select>
							</div>
                        
                             <div class="col-md-2">Contact No: (1)</div>
                            <div class="col-md-4">
								<input type="text" name="contact_no1" value="" class="form-control" value=""/>
							</div>
                        </div> -->
					<!-- <div class="form-row">
                            <div class="col-md-2">Forwarded To:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="forword_to">
								<option value="">--Select Project--</Option>
								<?php 
									// foreach($purchase_department as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									// }
								?>
								</select>
							</div>
                        
                            <div class="col-md-2">Contact No: (2)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no2" value="" class="form-control" value=""/>
							</div>
                        </div> -->
						
						
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary">Add Expanse Head</button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>
