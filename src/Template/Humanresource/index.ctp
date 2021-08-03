<div class="col-md-10 user_manage">
<div class="row">

	<!-- Candidate-->
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/employee-manage.png" class="img-circle img-thumbnail" style="margin-top: 8px;">				
		         </div>
				 <h4>Candidate Management</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Humanresource/addcandidate" class="list-group-item">Add Candidate </a>
				<a href="<?php echo $this->request->base;?>/Humanresource/candidatelist" class="list-group-item">Candidate Management</a>	
				
			</div>
		</div>
	</div>

	<!-- End Candidate -->



	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/employee-manage.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Personnel Management</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Humanresource/addemployee" class="list-group-item">Add Personnel </a>
				<a href="<?php echo $this->request->base;?>/Humanresource/personnel" class="list-group-item">Personnel Information</a>
				<a href="<?php echo $this->request->base;?>/Humanresource/emplyeelist" class="list-group-item">Personnel Management</a>	
					
				<a href="<?php echo $this->request->base;?>/Humanresource/notworkingemplyeelist" class="list-group-item">Non - Working Employee</a>	
			</div>
		</div>
	</div>
	<!--<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Time Logs (Thumb Logs)</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Humanresource/attendance" class="list-group-item">Manual Thumb</a>
				<a href="<?php echo $this->request->base;?>/Attendance/timelog" class="list-group-item">Personnel Time Logs</a>
			<--<a href="<?php //echo $this->request->base;?>/Humanresource/viewattendance" class="list-group-item">View Attendance</a> 
			</div>		   
		</div>
	</div>-->
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Attendance Management</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Attendance/addattendance" class="list-group-item">Add Attendance</a>
				<a href="<?php echo $this->request->base;?>/Attendance/attendancealert" class="list-group-item">Attendance Alert</a>
				<a href="<?php echo $this->request->base;?>/Attendance/attendancerecord" class="list-group-item">Attendance Records</a>
				<?php
					$role = $this->request->session()->read("role");
					if($role == "erphead" || $role == "hrmanager" || $role == "erpmanager" || $role == "erpoperator")
					{ ?>
						<a href="<?php echo $this->request->base;?>/Attendance/generaterecords" class="list-group-item">Generate Personnel Records</a>
					<?php } ?>
			</div>		   
		</div>
	</div>
</div>

	<div class="row">
		<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/salary.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Pay</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Humanresource/salaryslip" class="list-group-item">Pay Slip</a>
				<a href="<?php echo $this->request->base;?>/Humanresource/salarystatement" class="list-group-item">Pay Slip Approval</a>
				<a href="<?php echo $this->request->base;?>/Humanresource/salaryrecords" class="list-group-item">Pay Records</a>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/salary.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Loan System</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Humanresource/addloan" class="list-group-item">Add Loan</a>
				
				<a href="<?php echo $this->request->base;?>/Humanresource/loanlist" class="list-group-item"> Loan Status </a>
				
				<a href="<?php echo $this->request->base;?>/Humanresource/loanpending" class="list-group-item"> Loan Records </a>
				
			</div>
		</div>
	</div>		
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		    <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Records</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Humanresource/viewrecords" class="list-group-item">View Records</a>
			</div>
		</div>
	</div>	
		
</div>
	
	<div class="row">
		<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		    <div class="user bg-default bg-light-rtl">
		         <div class="info">             
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Bonus Management</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
			<a href="<?php echo $this->request->base;?>/Humanresource/bonusalert" class="list-group-item">Bonus Alert</a>
			<a href="<?php echo $this->request->base;?>/Humanresource/createexgracia" class="list-group-item">Create Exgracia</a>
			<a href="<?php echo $this->request->base;?>/Humanresource/viewbonusrecord" class="list-group-item">View Bonus Record</a>
			</div>
		</div>
	</div>	

	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		    <div class="user bg-default bg-light-rtl">
		         <div class="info">             
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Expenditure Claim Record</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
			<a href="<?php echo $this->request->base;?>/Humanresource/expenditure" class="list-group-item">Add Expenditure Claim Record</a>
			<a href="<?php echo $this->request->base;?>/Humanresource/viewexpenditure" class="list-group-item">View Expenditure</a>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>File Manager</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/humanresource/filemanager#hr" class="list-group-item">File Manager</a>
			 </div>
		</div>
	</div>
	</div>

</div>