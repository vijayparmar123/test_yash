<div class="col-md-10" >
		<?php 
		// error_reporting(0);
// if(!$is_capable)
	// {
		// $this->ERPfunction->access_deniedmsg();
	// }
// else
// {
?>
<style>
select[multiple], select[size] {
    height: 2px !important;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".datep").datepicker({changeMonth: true,changeYear:true,dateFormat: 'MM yy'});
	jQuery("#userlist").DataTable();

});
</script>

<div class="row">
	<div class="col-md-12">
		<div class="block">		
			<div class="head bg-default bg-light-rtl">
				<h2>Payment Summary Report </h2>
				<div class="pull-right">
				<a href="" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			<div class="content">
			
			</div>
			
		<div class="content list custom-btn-clean">
		<br>
			<div class="col-md-12" style="color:black;">
				<div class="col-md-6">
					<div class="col-md-12">
						<h5>Emp.Code - <?php echo $this->ERPfunction->get_user_pf_ref_no($user_id); ?></h5>
					</div>
					<div class="col-md-12">
						<h5>Emp.Name - <?php echo $this->ERPFunction->get_user_name($user_id); ?></h5>
					</div>
				</div>
				<div class="col-md-6 text-right">
					<div class="col-md-12">
						<h5>From : <?php echo date("M-Y",strtotime($from_date)); ?> UPTO <?php echo date("M-Y",strtotime($to_date)); ?></h5>
					</div>
					<div class="col-md-12">
						<h5>As on Date : <?php echo date("d-m-Y"); ?></h5>
					</div>
				</div>
			</div>
			<div style="overflow-x:scroll;" class="col-md-12">
			<table class="col-md-12 table-bordered" style="color:black;font-size:13px;">
				<thead>
					<tr>
						<th>Heading</th>
						<?php 
						$rows = array();
						$header[] = "Heading";
						foreach ($month_range as $m) 
						{
							$date = date("M'y",strtotime($m));
							echo "<th>{$date}</th>";
							$header[] = $date;
						}
						$header[] = "Total";
						
						$rows[] = $header;
						?>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					if(!empty($salary_statement))
					{
						foreach ($salary_statement as $key=>$val)
						{	
							$csv = array(); 
							$total = 0;
							
							echo "<tr>";
							echo "<td>".($csv[] = $key)."</td>";
							
							foreach ($month_range as $m) 
							{
								if(isset($salary_statement[$key][$m]))
								{
									$total += $salary_statement[$key][$m];
									echo "<td>".($csv[] = $salary_statement[$key][$m])."</td>";
								}else{
									$total += 0;
									$csv[] = "";
									echo "<td></td>";
								}
							}
							echo "<td>".($csv[] = $total)."</td>";
							echo "</tr>";
							
							$rows[] = $csv;
						}
					}
				?>
				</tbody>
			</table>
			</div>
			<?php
			if(isset($salary_statement))
			{
			  if(!empty($salary_statement))
				{
			?>
			<div class="content">
				<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				<div class="col-md-2">
					<?php 
						echo $this->Form->create('export_csv',['method'=>'post','url'=>['action'=>'exportSalaryStatement']]);
					?>
						<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
						<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
					<?php $this->Form->end(); ?>
				</div>
				<div class="col-md-2">
					<?php
						echo $this->Form->create('export_pdf',['method'=>'post','url'=> ['action' => 'printSalaryStatement']]); 
					?>
						<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
						<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
						<input type="hidden" name="from_date" value="<?php echo date("M-Y",strtotime($from_date)); ?>">
						<input type="hidden" name="to_date" value="<?php echo date("M-Y",strtotime($to_date)); ?>">
						<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
					<?php $this->Form->end(); ?>
				</div>
			</div>
			<?php } } ?>
		</div>
		</div>
	</div>
</div>
<?php 
// }
 ?>
</div>