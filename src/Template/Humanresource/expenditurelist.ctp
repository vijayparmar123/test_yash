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

<div class="modal fade " id="load_modal" role="dialog">
   <div class="modal-dialog modal-md">
     <div class="modal-content"></div>
   </div>
</div>
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
			
			$designation = $this->ERPfunction->get_category_title($user_data->designation);
		
		

	?> 
<div class="col-md-10  user_manage">

 
<div class="row">
<div class="col-md-12">
<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Expenditure Claim </h2>
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
					<div class="col-md-2 text-right">Employee Name :</div>
					<div class="col-md-3">
							<input name="employee_no" value="<?php echo $employees[0]; ?>"  id="employee_no" type="text" disabled>
					</div>
			
					<div class="col-md-2 text-right">Employee No :</div>
					<div class="col-md-3">
					<input name="employee_no"  id="employee_no" value="<?php echo $user_data->employee_no; ?>" type="text" disabled>
					</div>
				</div>
				<!--End First -->

				<!-- SECOND -->
				<div class="form-row">
					<div class = "col-md-2 text-right">Contact No : </div>
					<div class="col-md-3">
						<input name="mobile_no" id="contact_no" type="text" value="<?php echo  $user_data->mobile_no ?>" disabled>
					</div>
					
					<div class="col-md-2 text-right">Designation :</div>
					<div class="col-md-3">
					<input name="designation"  type="text" id="designation" value="<?php echo $designation ?>"  disabled>
					</div>
				</div>
				<!--End SECOND -->


				<!-- THREE -->
				<div class="form-row">
					<div class="col-md-2 text-right">Employee At :</div>
					<div class="col-md-3">
					<input name="employee_at"  id="employee_at" value="<?php echo $employee_at; ?>" type="text"  disabled>
					</div>
					<div class="col-md-2 text-right">Pay Type :</div>
					<div class="col-md-3">
					<input name="pay_type" id="pay_type" type="text" value="<?php echo $user_data->pay_type ?>"  disabled>
					</div>
				</div>
				<!--End THREE -->

				<div class="form-row">
					<div class="col-md-2 ">Expenditure Claim Period :</div>
					<div class="col-md-3">
						<input name="clam_period" id="pay_type" type="text" value="<?php echo $date ?>"  disabled>
					</div>
					
				</div>
				<!--Four->
				
				 End Four -->
				
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
						  <p class="filter-title"><b>Type Of Allowed</b></p>
						  <p class="heads"><b>Amount (Rs.)</b></p>
					</div>
					<?php foreach ($expenditure_record as $row) { ?>
					<input type="hidden" id="id" value="<?php echo $row['user_id']; ?>">
                    <div class="content" style="background:#FBD4B4">
					<p class="filter-title"><b>A) Travel / Transporation</b></p>

					<input type="text" id="travel_charge" name="travel_charge" value="<?php echo $row['travel_charge']; ?>"  class="inputs">
					</div> 
					<div class="content" style="background:#FBD4B4">
					<p class="filter-title"><b>B) House Rent/Hotel</b></p>
					<input type="text" id="house_charge" name="house_charge" value="<?php echo $row['house_charge']; ?>" class="inputs" >
					</div> 
					<div class="content" style="background:#FBD4B4">
					<p class="filter-title"><b>C) Mobile Bill</b></p>
					<input type="text" id="mobile_charge" name="mobile_charge" value="<?php echo $row['mobile_charge']; ?>" class="inputs"  >
					</div> 
					<div class="content" style="background:#FBD4B4">
					<p class="filter-title"><b>D) Food</b></p>
					<input type="text" id="food_charge" name="food_charge" value="<?php echo $row['food_charge']; ?>" class="inputs"  >
					</div> 
					<div class="content" style="background:#FBD4B4">
					<p class="filter-title"><b>E) Other Expense</b></p>
					<input type="text" id="other_charge" name="other_charge" value="<?php echo $row['other_charge']; ?>" class="inputs"  >
					</div> 
					<div class="footer" style="background: #FABF8F;">
						<p class="filter-title"><b>Total Amount</b></p>
						<input type="hidden" id="total_amount" name="total_amount" >
						 <p class="heads"><b><span  id="total_amount1"  name="total_bonus"><?php echo $row['total_amount'];  ?></span></b></p>
					</div>
                </div><?php 
                
             /*   if($this->ERPfunction->retrive_accessrights($role,'historyexpenditure')==1)
				{ */ ?>
                <div class="row submit">
                <?php 
                	 echo "<a class='btn btn-primary' id='history' href='javascript:void(0);'  data-url='{$this->request->base}/Ajaxfunction/ExpenditureHistory'>History</a>";
                ?>
				</div> <?php // }?>
                <div class="remark">
                	<p class="filter-title"><b>Remark :-</b></p>
                	<textarea class="form-control" style="color: black"  name="remark"><?php echo $row['remark'] ?></textarea>
                </div> 
                <?php } ?>   
			</div>
		</div>
			<br><br>
	   </div>
		<?php } ?>
	   </div>

	   


	   <?php $this->Form->end(); ?>

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
		 $("#name").change(function() {
		 	var id = $('#name option:selected').val();
   			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
   			 	type : "post",
   			 	url :"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getExpenditure'));?>",
   			 	data:{id:id},
   			 	success:function(response){
   			 		$.each(JSON.parse(response), function(idx, response) {
						$("#employee_no").val(response.employee_no);
						$("#designation").val(response.designation);
						$("#employee_at").val(response.employee_at);
						$("#pay_type").val(response.pay_type);
						$("#contact_no").val(response.contact_no);
						
					});
   			 	}
   			});
  		});


		$("#travel_charge,#house_charge,#mobile_charge,#food_charge,#other_charge").change(function(){
		 	var travel_charge = $('#travel_charge').val();
			var house_charge = $('#house_charge').val();
			var mobile_charge = $('#mobile_charge').val();
			var food_charge = $('#food_charge').val();
			var other_charge = $('#other_charge').val();
			
			var	total= parseFloat(travel_charge) + parseFloat(house_charge) + parseFloat(mobile_charge) + parseFloat(food_charge) + parseFloat(other_charge);
				 
				var final_amount =  total.toFixed(2);

			$('#total_amount1').html(final_amount);
			$('#total_amount').val(final_amount);
			
		 });

		$("body").on("click","#history",function(){
			var id = $('#id').val();
			var url = $(this).attr("data-url");
			if(id == "") {
				alert("Please Select Employee");
				return false;
			} 
			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				url : url,
				data:{id:id},
				type : "POST",
				async:false,
				success : function(response){
					$('.modal-dialog').css("width","1076px");
					$('.modal-content').html('');
					$('.modal-content').html(response);
					$('#load_modal').modal('show');
				},
				beforeSend:function(){
					jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		});
	});

</script>
