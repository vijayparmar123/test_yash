<?php
use Cake\Routing\Router;
?>
<style>
div.checker span.checked:before {   
     top: -6px;
	 color: white;
}
</style>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{

?>
<div class="row">
	<div class="col-md-12">
		<div class="block" style="width:auto;">
			<div class="head bg-default bg-light-rtl">
				<h2>P.R Alert </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		<div class="content">
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
		jQuery(document).ready(function() {
		jQuery("#user_form").validationEngine();
		jQuery('#from_date,#to_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	jQuery("body").on("change", "#project_id", function(event){ 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'ingrnprojectdetaillppo'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					return false;
                },
                error: function (e) {
                     alert('Error');
					 console.log(e.responseText);
                }
            });	
	});
		jQuery('#pr_list').DataTable({"order": [[ 1, "desc" ]]});
		
		jQuery("body").on("change", ".approve", function(event){
				var pr_id = jQuery(this).val();
				
				if(confirm('Are you Sure approve this PR?'))
				{
				var curr_data = {	 						 					
	 					pr_id : pr_id,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvepr'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					 location.reload();
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
		});}
			else
			{				
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
				//jQuery(this).prop('checked', true);
			}
			});		
		} );
	
	function check_select()
	{
		//check item is actually selected or not.
		return true;
	}
</script>

		<div class="col-md-12 filter-form">
			<?php 
@$project_id = isset($request_data['project_id'])?$request_data['project_id']: $this->request->params["pass"]["0"];
$from_date = isset($request_data['from_date'])?$request_data['from_date']:'';
$to_date = isset($request_data['to_date'])?$request_data['to_date']:'';
?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">  
						<div class="col-md-2">Project Code:</div>
                            <div class="col-md-3"><input type="text" name="project_code" id="project_code" value="<?php echo (isset($selected_pl))?$this->ERPfunction->get_projectcode($project_id):"";?>"
							class="form-control" value="" readonly="true"/></div>                        
					<div class="col-md-2">Select Project</div>
						<div class="col-md-3">
							<select class="select2" style="width: 100%;" name="project_id" id="project_id">
								<!-- <option value="">--Select Project--</Option> -->
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($project_id,$retrive_data['project_id']).'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
						</div>
					 <div class="col-md-2"><!--  Date From : --></div>
                        <div class="col-md-2"><input type="hidden" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="form-control"/></div>
						<div class="col-md-2"><!-- Date To : --></div>
                        <div class="col-md-2"><input type="hidden" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="form-control"/></div>

					<div class="col-md-2"><input type="submit" name="go" id="go" class="btn btn-primary" value="Go" /></div>
						<br>
						<br>
					</div>
				</form>			
			</div>
			</div>
			<div class="content "> <!-- list custom-btn-clean -->
			<?php
			if(isset($pr_list))
			{
				/* echo $this->Form->Create('form2',['id'=>'app_frm','method'=>'post','url'=>['action'=>'preparepo2']]); */
				echo $this->Form->Create('form2',['id'=>'app_frm','method'=>'post',"onsubmit"=>"return check_select()",'url'=>['controller'=>"Purchase",'action'=>'setstatus']]);
			
			?>
				<table id="pr_list"  class="dataTables_wrapper table table-striped table-bordered">
					<thead>
						<tr>
							<!-- <th>Project Code</th>-->
							<th>P.R. No</th>						
							<th>Date</th>						
							<th>Time</th>						
							<th colspan="9" style="padding:0;">
								<table class='table-bordered' style='width:100%;'>
									<tr>
														
									<th>Material Name</th>						
									<th>Make/<br>Source</th>						
									<th>Current Balance</th>
									<th>Min. Stock Level</th>
									<th>Quantity</th>
									<th>Unit</th>
									<th>Delivery Date</th>
									<!--<th>Mode of<br>Purchase</th>-->
									<?php
									if($this->ERPfunction->retrive_accessrights($role,'editpreparepr')==1 || $this->ERPfunction->retrive_accessrights($role,'deletepr')==1 || $this->ERPfunction->retrive_accessrights($role,'previewpr')==1)
									{
									?>
									<th>View</th>
									<?php
									}
									if($this->ERPfunction->retrive_accessrights($role,'approvepralert_inv')==1)
									{ ?>
									<th>Approve</th>
									<?php } ?>
									</tr>
								</table>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$searched_project_id = (isset($_POST["project_id"])) ? $_POST["project_id"] : $this->request->params["pass"]["0"];
						$i = 1;
						$prno = null;
						$show = 0;
						// if(isset($pr_list))
						// {
							foreach($pr_list as $retrive_data)
							{ $prno = $retrive_data['prno'];
							?>
								<tr class="rmv_<?php echo $i;?>">								
									<!-- <td><?php /*echo $this->ERPfunction->get_projectcode($retrive_data['project_id']);*/?></td> -->
									<td><?php echo $retrive_data['prno'];?></td>								
									<td><?php echo $this->ERPfunction->get_date($retrive_data['pr_date']);?></td>								
									<td><?php echo $retrive_data["pr_time"]?></td>								
									<td colspan="9">
									<?php 
										$materials_data = $this->ERPfunction->get_pr_materials($retrive_data["pr_id"],$searched_project_id);
										if($materials_data != "None")
										{
											$show = 1;
											echo $materials_data;
										}else{
											echo "No Pending Records Found.";
											 echo "<script>											
											$('.rmv_".$i."').remove();
											</script>"; 
										}
									?>
									</td>								
								</tr>
							<?php
							$i++;
							}
							// else{
							// echo "<tr><td colspan='5'>Data Not Available</td></tr>";
						 // }
						?>
					</tbody>
				</table>
				<?php if($show)
				{ ?>
				<div class="row">
					<?php
					if($this->ERPfunction->retrive_accessrights($role,'approvepralert_inv')==1)
					{
					?>
					<div class="col-md-1 pull-right">
					<input type="radio" name="purchase_mod" value='central' checked >Central Purchase
					</div>
					<div class="col-md-1 pull-right">
						<input type="radio" name="purchase_mod" value='local'>Local Purchase
					</div>	
					<div class="col-md-2 pull-right">
					
					<input type="submit" class="btn btn-success" name="approve_list" value="Approve">
					<?php
					}
					?>
					</div>
					<input type="hidden" name="project_id" value="<?php echo $project_id;?>">
					<input type="hidden" name="prno" value="<?php echo $prno;?>">
					<input type="hidden" id="data-url" value="<?php echo $this->request->base;?>">				
				</div>
				<?php } echo $this->Form->end();
			
			}
			
			?>
		</div>
		</div>
	</div>
</div>
<?php }?>
</div>
<script>
$("input[type='radio']").change(function (){
	var val = $(this).val();
	var url = $("#data-url").val();
	$(".purchase_mod option[value='"+val+"']").attr("selected","selected");
	if(val == "local")
	{
		/* $('#app_frm').attr("action",url+"/inventory/preparegrnwithoutpo"); */
		$('#app_frm').attr("action",url+"/purchase/setpraprove");
	}else{
		/* $('#app_frm').attr("action",url+"/inventory/preparepo2"); */
		$('#app_frm').attr("action",url+"/Purchase/setstatus");
	}
});
</script>