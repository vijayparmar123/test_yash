<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
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
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>LOI RECORDS</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('purchase',$back);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		<div class="content ">
		<script>
		
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
		var f_date_from  = jQuery("#f_date_from").val();
		var f_date_to  = jQuery("#f_date_to").val();
		var f_pro_id  = jQuery("#f_pro_id").val();
		var f_material_id  = jQuery("#f_material_id").val();
		var f_brand_id  = jQuery("#f_brand_id").val();
		var f_vendor_userid  = jQuery("#f_vendor_userid").val();
		var f_loi_no  = jQuery("#f_loi_no").val();

	var selected = [];
	var table = jQuery('#loi_list').DataTable({
		"pageLength": 10,
		"order": [[ 1, "desc" ]],
		columnDefs: [ 
					{
						searchable: false,
						targets:   2,
					},
					{
						searchable: false,
						targets:   4,
					},
					{
						searchable: false,
						targets:   5,
					},
					{
						searchable: false,
						targets:   7,
					},
					{
						searchable: false,
						targets:   10,
					}
					],
			
		"columns": [
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
						  ],
		"responsive" : true,
		"processing": true,
		"serverSide": true,
		//"ajax": "../Ajaxfunction/billrecordsdata",
		"ajax": {
				// "url": "../Ajaxfunction/porecords",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loirecords'));?>",
				"data": function ( d ) {
											d.myKey = "myValue";
											d.date_from = f_date_from;
											d.date_to = f_date_to;
											d.pro_id = f_pro_id;
											d.material_id = f_material_id;
											d.brand_id = f_brand_id;
											d.vendor_userid = f_vendor_userid;
											d.loi_no = f_loi_no;
										}
				},
		"rowCallback": function( row, data ) {
								
								if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
									jQuery(row).addClass('selected');
								}
						},
		});
		
	} );
	</script>
		<script>
		
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
		jQuery(document).ready(function() {
			jQuery('#from_date,#to_date').datepicker({
				dateFormat: "dd-mm-yy",
				changeMonth: true,
				changeYear: true,
				yearRange:'-65:+0',
				onChangeMonthYear: function(year, month, inst) {
					jQuery(this).val(month + "-" + year);
				}                    
			});
			// jQuery('#po_list').DataTable({responsive: true,"ordering": false,});
				
			jQuery('body').on('click','.cancelpo',function(event){
				// alert("ASd");
				
				// event.preventDefault();
				var del = false;
				if(confirm('1.Are you sure want to cancel P.O. ? Only approved materials from P.O. Alert will be cancelled.'))
				{
					if(confirm('2.Are you sure want to cancel P.O. ? Only approved materials from P.O. Alert will be cancelled.'))
					{
						if(confirm('3.Are you sure want to cancel P.O. ? Only approved materials from P.O. Alert will be cancelled.'))
						{
							del = true;
						}
					}
				}
				
				if(del)
				{
					return true;
				}else{
					return false;
				}
			});
		

		
		});
</script>
			<div class="col-md-12 filter-form">
			<?php 
$project_id = isset($request_data['project_id'])?$request_data['project_id']:'';
$from_date = isset($request_data['from_date'])?$request_data['from_date']:'';
$to_date = isset($request_data['to_date'])?$request_data['to_date']:'';
?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-4"><input type="text" name="from_date" id="from_date" value="" class="form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-4"><input type="text" name="to_date" id="to_date" value="" class="form-control"/></div>
					</div>
					<div class="form-row">	
					<div class="col-md-2">Material Name</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($material_list as $retrive_data)
									{
										echo '<option value="'.$retrive_data['material_id'].'">'.
										$retrive_data['material_title'].'</option>';
									}
								?>
							</select>
						</div>
                
						<div class="col-md-2">Project Name</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
						</div>
                    </div>
					
					<div class="form-row">	
						<div class="col-md-2">Make/Source</div>
                        <div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="brand_id[]" id="brand_id" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($brand_list as $retrive_data)
								{echo '<option value="'.$retrive_data['brand_id'].'">'.
										$retrive_data['brand_name'].'</option>';									
									
								}
								?>
							</select>
						</div>
						
						<div class="col-md-2">Vendor Name</div>
                        <div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="vendor_userid[]" id="vendor_userid" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($vendor_department as $retrive_data)
								{echo '<option value="'.$retrive_data['user_id'].'">'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';									
									
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-row">	
						<div class="col-md-2">LOI No</div>
                        <div class="col-md-4">
							<input type="text" name="loi_no" id="loi_no" value="" class="form-control"/>
						</div>
					</div>			
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
						
					</div>
				<?php $this->Form->end(); ?>
				
<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : $from;?>">
<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : $to;?>">
<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : $projects_id;?>">
<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
<input type="hidden" id="f_brand_id" value="<?php echo isset($_POST["brand_id"]) ? implode(",",$_POST["brand_id"]) : "";?>">
<input type="hidden" id="f_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
<input type="hidden" id="f_loi_no" value="<?php echo isset($_POST["loi_no"]) ? $_POST["loi_no"] : "";?>">
			</div>
			</div>
<div class="content list custom-btn-clean">
			<table id="loi_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<th>LOI No</th>	
						<th>LOI Date</th>	
						<th>Project Name</th>
						<th>Vendor Name</th>
						<th>Material Name</th>
						<th>Make/Source</th>
						<th>Quantity</th>
						<th>Unit</th>						
						<th>Final Rate</th>						
						<th>Amount</th>													
						<th>Action</th>
						<th class="never">Material Name</th>
						<th class="never">Material Name</th>
					</tr>
				</thead>
			</table>
			<div class="content">
				<div class="col-md-2">
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data']);?>
					<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : $from;?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : $to;?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : $projects_id;?>">
					<input type="hidden" name="e_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="e_brand_id" value="<?php echo isset($_POST["brand_id"]) ? implode(",",$_POST["brand_id"]) : "";?>">
					<input type="hidden" name="e_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
					<input type="hidden" name="e_loi_no" value="<?php echo isset($_POST["loi_no"]) ? $_POST["loi_no"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php echo $this->Form->end(); ?>
				</div>
				<div class="col-md-2">
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data']);?>
					<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : $from;?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : $to;?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : $projects_id;?>">
					<input type="hidden" name="e_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="e_brand_id" value="<?php echo isset($_POST["brand_id"]) ? implode(",",$_POST["brand_id"]) : "";?>">
					<input type="hidden" name="e_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
					<input type="hidden" name="e_loi_no" value="<?php echo isset($_POST["loi_no"]) ? $_POST["loi_no"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
		</div>
	</div>

<?php } ?>
</div>