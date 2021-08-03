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
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>  
<div class="col-md-10  user_manage">

<div class="row">
<div class="col-md-12">
<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Exgracia / Arrears</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php 
		echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
	
		<div class="content">
			<div class="col-md-12 filter-form">
				
				<!-- FIRST -->
				<div class="form-row">
					<div class="col-md-2 text-right">Date Of Issue :</div>
					<div class="col-md-3">
						<input name="bonus_date" required="true" id="date" class="form-control  date validate[required] datep" value="<?php echo date("F Y"); ?>">
					</div>
					
					<div class="col-md-2 text-right">Employee No :</div>
					<div class="col-md-3">
					<input name="employee_no"  id="employee_no" type="text" disabled>
					</div>
				</div>
				<!--End First -->

				<!-- SECOND -->
				<div class="form-row">
					<div class="col-md-2 text-right">Employee Name :</div>
					<div class="col-md-3">
						<?php echo $this->form->select("user_id",$employees,["empty"=>"Select Employee","id"=>"name","class"=>"select2 employees ","required"=>true]);?>
					</div>
					
					<div class="col-md-2 text-right">Designation :</div>
					<div class="col-md-3">
					<input name="designation"  type="text" id="designation"  disabled>
					</div>
				</div>
				<!--End SECOND -->


				<!-- THREE -->
				<div class="form-row">
					<div class="col-md-2 text-right">Employee At :</div>
					<div class="col-md-3">
					<input name="employee_at"  id="employee_at" type="text"  disabled>
					</div>
					<div class="col-md-2 text-right">Pay Type :</div>
					<div class="col-md-3">
					<input name="pay_type" id="pay_type" type="text"  disabled>
					</div>
				</div>
				<!--End THREE -->
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
					<div class="header" style="background:#FABF8F">
						  <p class="filter-title"><b>Salarey Head</b></p>
						  <p class="heads"><b>Amount (Rs.)</b></p>
					</div>
                    <div class="content" style="background:#FBD4B4">
					<p class="filter-title"><b>	Ex-gracia / Arrears</b></p>
					<input type="text" id="bonus" name="bonus" class="inputs" required="true" >
					</div> 
					<div class="footer" style="background: #FABF8F;">
						<p class="filter-title"><b>Total</b></p>
						<input type="hidden" id="bonusvalue1" name="total_bonus">
						 <p class="heads"><b><span  id="bonusvalue" name="total_bonus"></span></b></p> 
					</div>
                </div>    

                 <div class="block block-drop-shadow">
                 	<div class="header" style="background:#CCC0D9">
						<h2 style="width: 100%;text-align: center;">EXGRACIA / ARREARS HISTORY	</h2>
					</div>

					<?php 
					/*foreach($salary_data as $key=>$value)
					{
							debug($key);
					  foreach($value as $key1=>$value1)
					  {
							debug($key1); 
							debug($value1);
					  }	
					}*/
					?>
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
					<p class="filter-title"><b><?php echo $monthName; ?></b>-<span class="year" id="<?php echo $financial_data['month'][$key]; ?>"><?php echo $financial_data['year'][$key];?></span></p>
					<p class="heads bonus_amount <?php echo $financial_data['month'][$key]; ?>" id="<?php echo $financial_data['month'][$key].'_'.$financial_data['year'][$key]?>"> 0</p>
					<?php } ?>
					
					</div> 
					<div class="footer" style="background: #CCC0D9;">
						<p class="filter-title"><b>Total</b></p>
						  <p class="heads"><b id="total_bonus">(Rs.)</b></p>
					</div>
                </div>  
			</div>
		</div>
			<div class="row submit">
					<input type="submit" name="submit" id="submit"  value="submit" class="btn btn-success" style='width:20%;'>
			</div>
			<?php $this->Form->end(); ?>
	   </div>
	 <?php  } ?>
</div>	

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
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	$(document).ready(function() {
		 $("#name").change(function(){
		 	var id = $('#name option:selected').val();
   			 $.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
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
			var bonus1 = $(this).val();

			
		 	$('#bonusvalue').html(bonus);
			$('#bonusvalue1').val(bonus1);
		 });
		
		$("#date").change(function(){
		 		var date = $('#date').val();
				$(".bonus_amount").each(function() {
					$(this).html(0);
				});
		 		$.ajax({
					headers: {
						'X-CSRF-Token': csrfToken
					},
		 			type : "post",
   			 		url :"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getDateformat'));?>",
   			 		data:{date:date},
   			 		success:function(response){
   			 			var obj = JSON.parse(response);
						$.each(obj, function(idx, response){
						$("#"+idx).html(response);
						
						$('.'+idx).attr('id', idx+"_"+response);
					});
   			 		}
		 		});
		 	});

			$("#name,#date").change(function(){
				$(".bonus_amount").each(function() {
					$(this).html(0);
				});
		 		var id = $('#name option:selected').val();
		 		var date = $("#date").val();
		 		if(id == '' || date == '') {
					return false;
				}
		 		$.ajax({
					headers: {
						'X-CSRF-Token': csrfToken
					},
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
