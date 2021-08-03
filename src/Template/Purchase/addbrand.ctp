<?php
//$this->extend('/Common/menu')
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	//jQuery('#user_form').validationEngine();	
} );
</script>	
<?php 

$material_type=isset($brand_data['material_type'])?$brand_data['material_type']:'1';
$brand_name=isset($brand_data['brand_name'])?$brand_data['brand_name']:'';


?>

<div class="col-md-10" >
	<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>   	
<div class="col-md-12">
<div class="row">		
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
		
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Material/Item Group<span class="require-field">*</span> </div>
                            <div class="col-md-4">
								<select name="material_type" class="select2" required="true"  style='width:100%'>
									<?php
										/* foreach($category as $key => $retrive_data)
										{
											echo '<option value="'.$key.'">'.$retrive_data['material_code'].'</option>';
										} */
										/*Material Group list*/
										foreach($category as $key => $retrive_data)
										{ 
											echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$material_type).'>'.$this->ERPfunction->get_vendor_group_name($retrive_data['id']).'</option>';
										}
									?>
									
								</select>
							</div>                          
                        </div>
                       <div class="form-row">
                            <div class="col-md-2">Brand Name</div>
                            <div class="col-md-4">
								<input type="text" name="brand_name" value="<?php echo $brand_name;?>" class="form-control" value=""/>
							</div>
						</div>
						
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
				</div>
				<?php $this->Form->end(); ?>
			</div>
			</div>
			</div>
			<?php } ?>
         </div>