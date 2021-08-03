<?php
use Cake\Routing\Router;
?>

<style>
	.user_manage{
		background-color: white;
	}
	.title{
		color: black;
		margin-bottom: 20px;
	}
	.filter
	{
		float: left;
		width: 100%;
	}
	.filter-title
	{
		float: left;
		color: black;
		width: 50%;
		font-size: 12px;
	}
	.filter-title1
	{
		float: left;
		color: black;
		width: 50%;
		font-size: 12px;
	}
	.inputs
	{
		float: right;
		width: 50%;
		color: black!important;
	}
	.heads
	{
		float: right;
		color: black;
	}
	.select2{
		 width: 100%;
	}
	.year{
		color: red;
	}
	.submit{
		text-align:center;
		margin-bottom:20px;
	}

</style>
<?php 
	$designation = $this->ERPfunction->get_category_title($records->designation);
	$employee_at = $this->ERPfunction->get_user_employee_at($records->user_id);
?>
<?php
	//debug($salary_data);die;
	
	foreach ($salary_data as $key => $value) {
		foreach ($value as $key1 => $value1) {
				$dates[] = $key1 .'_'.$key;
		}
		
	}
	// debug($dates);die;
	
?>
	<?php 
 if(!$is_capable)
 {
	 $this->ERPfunction->access_deniedmsg();
 }
 else
 { 
?>
<div class="col-md-10  user_manage">

<div class="row">
<div class="col-md-12">
<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2 class="year">Bonus Financial Year <?php echo $current_year;  ?>-<?php echo $previous_year?> </h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php 
		echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
	
		<div class="content">
			<div class="col-md-12 filter-form">
				
				<div class="form-row">
					<div class="col-md-2 text-right">Date Of Issue :</div>
					<div class="col-md-3">
						<input name="issue_date" required="true" id="date" class="form-control  date validate[required] datep" value="<?php echo date("F Y"); ?>" disabled>
					</div>
					
					<div class="col-md-2 text-right">Employee No :</div>
					<div class="col-md-3">
					<input name="employee_no"  id="employee_no" type="text" value="<?php echo $records->employee_no; ?>" disabled>
					</div>
				</div>
				

			
				<div class="form-row">
					<div class="col-md-2 text-right">Employee Name :</div>
					<div class="col-md-3">
						<?php  echo $this->form->select("user_id",$employees,["id"=>"name","class"=>"select2 employees ","required"=>true,]);?>
					</div>
					
					<div class="col-md-2 text-right">Designation :</div>
					<div class="col-md-3">
					<input name="designation"  type="text" id="designation"  value="<?php  echo $designation?>" disabled>
					</div>
				</div>
			
				<div class="form-row">
					<div class="col-md-2 text-right">Employee At :</div>
					<div class="col-md-3">
					<input name="employee_at"  id="employee_at" type="text" value="<?php echo $employee_at; ?>"  disabled>
					</div>
					<div class="col-md-2 text-right">Pay Type :</div>
					<div class="col-md-3">
					<input name="pay_type" id="pay_type" type="text" value="<?php echo $records->pay_type; ?>"  disabled>
					</div>
				</div>
				
				<!--<div class="form-row">					
					<div class="col-md-1">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go" >
					</div>
				</div>-->
		</div>
		</div>
</div>
</div>
</div>
		<div class="form-row">
		<div class="col-md-3"></div>
		<div class="col-md-6" >
			  
			   <div class="block block-drop-shadow">
					<div class="header" style="background:#D99594">
						  <p class="filter-title"><b>Salarey Head</b></p>
						  <p class="heads"><b>Amount (Rs.)</b></p>
					</div>
                    <div class="content" style="background:#F2DBDB">
                    <p class="filter-title"><b>Bonus (As Per Rules)</b></p>
                    <p class="heads year"><b style="font-weight: 600;" ><b id="tax"><?php echo sprintf("%.2f",$tax); ?></b></p>
                    <input type="hidden" name="bonus" value="<?php echo $tax ;?>">
					<p class="filter-title"><b>Extra Bonus</b></p>
					<input type="text" id="bonus" readonly="true" name="extra_bonus" class="inputs" value="<?php echo $bonus; ?>" >
					</div> 
					<div class="footer" style="background: #D99594;">
						<p class="filter-title"><b>Total</b></p>
						<input type="hidden" id="bonusvalue1" name="total_bonus">
						 <p class="heads"><b><span  id="bonusvalue" name="extra_bonus"><?php echo sprintf("%.2f",$total_bonus); ?></b></p></span></b><span id="rss"></span></p> 
					</div>
                </div>


                 <div class="block block-drop-shadow">
                 	<div class="header" style="background:#CCC0D9">
						<h2 style="width: 100%;text-align: center;">PAY SLIP - SUMMARY	</h2>
					</div>

					
					<div class="header" style="background:#CCC0D9">
						  <p class="filter-title"><b>Month</b></p>
						  <p class="heads"><b>Amount (Rs.)</b></p>
					</div>
                    <div class="content" style="background:#E5DFEC">
					<?php 

						foreach($financial_data['year'] as $key=>$value){
							$dateObj = DateTime::createFromFormat('!m', $financial_data['month'][$key]);
							$monthName = $dateObj->format('F');
					?>


					<p class="filter-title"><b><?php echo $monthName; ?></b>-<span class="year" id="april"><?php echo $financial_data['year'][$key];?></span></p>
					<?php $date = $financial_data['month'][$key].'_'.$financial_data['year'][$key];
					 ?>
					<p class="heads" id="<?php echo $financial_data['month'][$key].'_'.$financial_data['year'][$key]?>"> <?php echo isset($salary_data[$financial_data['year'][$key]][$financial_data['month'][$key]])?$salary_data[$financial_data['year'][$key]][$financial_data['month'][$key]]:0; ?></p>
					<?php } ?>
					</div> 

					<div class="footer" style="background: #CCC0D9;">
						<p class="filter-title"><b>Total</b></p>
						  <p class="heads"><b id="total_bonus"><?php echo $total_earning; ?>(Rs.)</b></p>
					</div>
                </div>  
			</div>
		</div>

		<!--	<div class="row submit">
					<input type="submit" name="submit" id="submit"  value="submit" class="btn btn-success" style='width:20%;'>
			</div> -->
	   </div>
	   <?php $this->Form->end();  ?>

</div>	<?php  } ?>
<script type="text/javascript">
jQuery('.datep,#as_on_date').datepicker({
		dateFormat: 'MM yy',
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			maxDate: new Date(),
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
</script>

<script>
	$(document).ready(function()
	{
		 $("#name").change(function(){
		 	var id = $('#name option:selected').val();
		 	
   			 $.ajax({
   			 	type : "post",
   			 	url :"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getMonthly'));?>",
   			 	data:{id:id},
   			 
   			 	success:function(response){
   			 			
   			 		$.each(JSON.parse(response), function(idx, response) {

						$("#employee_no").val(response.employee_no);
						$("#designation").val(response.designation);
						$("#employee_at").val(response.employee_at);
						$("#pay_type").val(response.pay_type);
						
					});
   			 	}
   			 });
  		});
		 $("#bonus").change(function(){
		 	var bonus = $(this).val();
		 	var tax = $('#tax').html();
			var total = parseInt(bonus) + parseInt(tax);
			//var tax = bonus * 8.33/100;
			//var total = bonus - tax;

			$('#rs').html("  (Rs)");
		 	$('#bonusvalue').html(total);
			$('#bonusvalue1').val(total);
		 });

			$("#name").change(function(){
		 		var id = $('#name option:selected').val();
		 		var date = $("#date").val();
		 		
		 		$.ajax({
   			 	type : "post",
   			 	url :"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'Salareylist'));?>",
   			 	data:{id:id,date:date},
   			 	
   			 
   			 	success:function(response){
					var obj = JSON.parse(response);
					$.each(obj['exgracia_data'], function(idx, response){
						$("#"+idx).html(response);
					});
					 $("#total_bonus").html(obj['total_bonus']);
   			 		
   			 	}
   			 });

		 	});
	});

</script>
