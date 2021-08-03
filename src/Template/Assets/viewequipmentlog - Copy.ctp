<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
		
	
} );
</script>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>    
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
                    <div class="header">
                        <h2></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo ($edit)?$data["project_code"]:""; ?>"
							class="form-control validate[required]" value="" disabled /></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id" disabled>
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($data)){
												if($retrive_data['project_code'] == $data['project_code'])
												{
													echo 'selected="selected"';
												}
			
											}?> >
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php
										
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">E.L No.:</div>
                            <div class="col-md-4">
								<input name="elno" disabled class="form-control" value="<?php echo ($edit)?$data["elno"]:$elno;?>">
							</div>							
                            <div class="col-md-2">Date :</div>
                            <div class="col-md-4">
								<input name="el_date" disabled class="datepick form-control" value="<?php echo ($edit)?date('d-m-Y',strtotime($data["el_date"])):"";?>">
							</div>	
						</div>
						<!--<div class="form-row">
                            <div class="col-md-offset-3 col-md-1">
								<input type="radio" name="ownership" class="toggle_box" value="rent" <?php echo($edit && $data["ownership"] == "rent")?"checked":"checked";?>>On Rent
							</div>
							<div class="col-md-4">
								<input type="radio" name="ownership" class="toggle_box" value="owned" <?php echo($edit && $data["ownership"] == "owned")?"checked":"";?>>Owned
							</div>
						</div>-->
						<div id="owned_box">
						<div class="form-row">
                            <div class="col-md-2">Asset Group :</div>
                            <div class="col-md-4">
								<select style="width: 100%;" class="" name="asset_group" disabled >
								<option>--Select Assets Group--</option>
								<?php 
								foreach($asset_groups as $key => $retrive_data)
								{
									echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$data["asset_group"]).'>'.$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
								}
								?>
								</select>								
							</div>							
                            <div class="col-md-2">Asset ID</div>
                            <div class="col-md-4">
								<input type="text" disabled value="<?php echo ($edit)?$data["asset_code"]:"";?>" class="form-control"/>
							</div>	
						</div>
						
						<div class="form-row">
                            <div class="col-md-2">Asset Name :</div>
                            <div class="col-md-4">
								<select name="asset_name" disabled id="asset_list" class="form-control validate[required]">
								<?php 
								if($edit)
								{ 
									foreach($asset_list as $retrive_data)
									{ 
										$selected = ($retrive_data['asset_id'] == $data["asset_name"])?"selected":"";										
										echo '<option value="'.$retrive_data['asset_id'].'" '.$selected.'>'. $retrive_data["asset_name"] .'</option>';
									}
								}
								?>
								</select>
							</div>
						</div>					
						</div>
						<div id="rent_box">
						<div class="form-row">
                            <div class="col-md-2">Asset Name :</div>
                            <div class="col-md-4">
								<input name="asset_name" disabled id="asset_list" class="form-control validate[required]" value="<?php echo ($edit)?$this->ERPfunction->get_asset_name($data["asset_name"]):"";?>">
							</div>							
						</div>	
						</div>						
						<div class="form-row">
							<br>
                            <div class="col-md-2">Driver Name :</div>
                            <div class="col-md-4">
								<input name="driver_name" disabled class="form-control validate[required]" value="<?php echo ($edit)?$data["driver_name"]:"";?>">
							</div>
							<div class="col-md-2">Vehicle No:</div>
                            <div class="col-md-4">
								<input name="vehicle_no" disabled class="form-control validate[required]" value="<?php echo ($edit)?$data["vehicle_no"]:"";?>">
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Usage :</div>
                            <div class="col-md-2">
								<input name="el_usage" disabled class="form-control validate[required]" value="<?php echo ($edit)?$data["el_usage"]:"";?>">
							</div>
							<div class="col-md-2">Unit of Usage :</div>
                            <div class="col-md-2">
								<select name="unit_usage" disabled class="form-control validate[required]">
									<option value="hy" <?php echo ($edit && $data["unit_usage"] == "hy")?"selected":"";?>>Hr.</option>									
									<option value="days" <?php echo ($edit && $data["unit_usage"] == "days")?"selected":"";?>>Days</option>									
									<option value="nos" <?php echo ($edit && $data["unit_usage"] == "nos")?"selected":"";?>>Nos.</option>									
								</select>
							</div>
							<div class="col-md-2">Approved By :</div>
                            <div class="col-md-2">
								<input name="approved_by" disabled class="form-control validate[required]" value="<?php echo ($edit)?$data["approved_by"]:"";?>">
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Details of Usage :</div>
                            <div class="col-md-8">
								<input name="usage_detail" class="form-control" value="<?php echo ($edit)?$data["usage_detail"]:"";?>" disabled>
							</div>					
						</div>						
						
                
			<?php echo $this->Form->end();?>			
			
			<div class="row" style="font-style:italic;color:gray;">				
					<div class="col-md-6 pull-right">
						<br><br>
						<div class="col-md-6">
							<?php echo "Created By : {$this->ERPfunction->get_user_name($data['created_by'])}"; ?>
						</div>
						<div class="col-md-4 pull-right">
							<?php 
							$url = ($data['ownership'] == "owned") ? "printequipmentowned" : "printequipmentrent";
							?>
						  <a href="../<?php echo $url;?>/<?php echo $data["id"];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
						</div> 
					</div>
			</div>
		   </div>
		</div>
<?php } ?>
</div>
<script>
$(document).ready(function(){
	$("#owned_box").hide();
	$("#rent_box input,#rent_box select").attr("disabled", "disabled");
	
	$(".toggle_box").click(function(){
		var box = $(this).val();
		if(box == "rent")
		{
			$("#owned_box").hide();
			$("#rent_box").show();	
				
		}else{
			$("#owned_box").show();
			$("#rent_box").hide();			
		}
	});
});
</script>
<?php
if($edit && $data["ownership"] == "rent")
{?>
<script>
$(document).ready(function(){
	$("#owned_box").hide();
	$("#rent_box").show();	
				
});
</script>
<?php 
}
else{ ?>
<script>
$(document).ready(function(){
	$("#owned_box").show();
	$("#rent_box").hide();	
});
</script>
<?php }
 ?>
 <script>
 
 </script>