	<?php
//$this->extend('/Common/menu')
?>
<?php 
use Cake\Routing\Router;
?>


<?php 

$material_code=isset($material_data['material_code'])?$material_data['material_code']:'';
$brand_id=isset($material_data['brand_id'])?$material_data['brand_id']:'';
$material_item_code=isset($material_data['material_item_code'])?$material_data['material_item_code']:'';
$material_title=isset($material_data['material_title'])?$material_data['material_title']:'';
$unit_id=isset($material_data['unit_id'])?$material_data['unit_id']:'';
$desciption=isset($material_data['desciption'])?$material_data['desciption']:'';
$project_id=isset($material_data['project_id'])?$material_data['project_id']:'';
$consume=isset($material_data['consume'])?$material_data['consume']:'';
$cost_group=isset($material_data['cost_group'])?$material_data['cost_group']:'';

?>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{ ?>              
<div class="col-md-12">
<div class="row">
 <div class="block block-fill-white">
				
                <div class="block block-fill-white">					
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Purchase',$back);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Material / Item Group </div>
                            <div class="col-md-4">
								<select name="material_code"  style="width: 100%;" class="select2" required="true"  id="material_code" disabled>
								<option value="">--Select Item Group--</option>
									<?php 
								foreach($category as $key => $retrive_data)
								{ 
									echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$material_code).'>'.$this->ERPfunction->get_vendor_group_name($retrive_data['id']).'</option>';
								}
								?>
								</select>
							</div> 
							<div class="col-md-2">Material Title</div>
                            <div class="col-md-4">
								<input type="text" name="material_title" value="<?php echo htmlspecialchars($material_title);?>" class="form-control validate[required]" value="" disabled />
							</div>
                        </div> <!--
						 <div class="form-row">
							<div class="col-md-2">Brand List </div>
							<div class="col-md-4">
								<select type="text" name="brand_id" id="brand_id" class="form-control">
									
								</select>
							</div>
						</div> -->
					   <div class="form-row">
							<div class="col-md-2">Material Code </div>
							<div class="col-md-4">
								<input type="text" name="material_item_code" id="material_item_code" value="<?php echo $material_item_code;?>" class="form-control" disabled />
							</div>
							<div class="col-md-2">Unit </div>
							 <div class="col-md-3">
								  <select class="select2" required="true" style="width: 100%;"id="unit" name="unit_id" disabled >
									<option value=""><?php echo __('--Unit--'); ?></option>
									<?php
                                    if(isset($unitlist)){
                                        foreach($unitlist as $unit_info){
                                        ?>
                                   <option value="<?php echo $unit_info['cat_id'];?>" <?php                                            
                                                if($unit_id == $unit_info['cat_id']){
                                                    echo 'selected="selected"';
                                                }else{
                                                    echo '';
                                                }
                                            
                                        
                                        ?> ><?php echo $unit_info['category_title'];?></option>
                                            <?php             
                                        }
                                    }
                                   ?>
								</select>
							</div> 
							
						</div>
						<div class="form-row">
								<div class="col-md-2">Project </div>
								<div class="col-md-4">
								  <select class="select2" disabled style="width: 100%;" id="project" name="project_id">
									<option value="0">All</option>
									<?php
                                    if(isset($projects)){
                                        foreach($projects as $project){
                                        ?>
                                   <option value="<?php echo $project['project_id'];?>" <?php                                            
                                                if($project_id == $project['project_id']){
                                                    echo 'selected="selected"';
                                                }else{
                                                    echo '';
                                                }
                                            
                                        
                                        ?> ><?php echo $project['project_name'];?></option>
                                            <?php             
                                        }
                                    }
                                   ?>
								</select>
							</div> 
								<div class="col-md-2">Consume Type </div>
								<div class="col-md-3">
								  <select class="select2" disabled style="width: 100%;" id="consume" name="consume">
									<option value="1" <?php echo ($consume === 1)?"selected":"" ?>>Consumable</option>
									<option value="0" <?php echo ($consume === 0)?"selected":"" ?>>Retunable / Non-consumable</option>
								</select>
							</div> 							
						</div>
						<div class="form-row">
								<div class="col-md-2">Material Description </div>
								<div class="col-md-4">
									<textarea name="desciption" class="form-control" disabled > <?php echo $desciption;?></textarea>
								</div>
								<div class="col-md-2">Cost Group </div>
							<div class="col-md-3">
								<select class="select2" disabled style="width: 100%;" id="cost_group" name="cost_group">
									<option value="a" <?php echo ($cost_group === 'a')?"selected":"" ?>>A</option>
									<option value="b" <?php echo ($cost_group === 'b')?"selected":"" ?>>B</option>
									<option value="c" <?php echo (($cost_group === 'c') || ($cost_group != 'a' && $cost_group != 'b'))?"selected":"" ?>>C</option>
								</select>
							</div>
						</div>
						
				</div>
				 </div>
			</div>
			 </div>
			</div>
<?php } ?>
        </div>
