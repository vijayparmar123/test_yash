<?php
use Cake\Routing\Router;
?>
<style>
    div.checker span.checked:before {

        top: -6px;
    }
  

</style>
<div class="col-md-10">
    <div class="modal fade"  id="load_modal" role="dialog">
        <div class="modal-dialog modal-md" style="width:1000px;">
            <div class="modal-content"></div>
        </div>
    </div>

    <?php 
		if(!$is_capable){
			$this->ERPfunction->access_deniedmsg();
		}
		else
		{
	?>

    <div class="col-md-12">
        <div class="block" id="pr-div" style="<?php echo (isset($_REQUEST['go']))?'width:auto':''; ?>">
            <div class="head bg-default bg-light-rtl">
                <h2>P.O. ALERT</h2>
                <div class="pull-right">
                    <a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>"
                        class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
                </div>
            </div>
            <div class="content">
                <script>
                    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

                    jQuery(document).ready(function () {
                        jQuery("#user_form").validationEngine();
                        jQuery('#from_date,#to_date').datepicker({
                            dateFormat: "dd-mm-yy",
                            changeMonth: true,
                            changeYear: true,
                            yearRange: '-65:+0',
                            onChangeMonthYear: function (year, month, inst) {
                                jQuery(this).val(month + "-" + year);
                            }
                        });
                        jQuery('#pr_list').DataTable({
                            "order": [
                                [1, "desc"]
                            ]
                        });

                        jQuery("body").on("change", ".approve", function (event) {
                            var pr_id = jQuery(this).val();

                            if (confirm('Are you Sure approve this PR?')) {
                                var curr_data = {
                                    pr_id: pr_id,
                                };
                                jQuery.ajax({
                                    headers: {
                                        'X-CSRF-Token': csrfToken
                                    },
                                    type: "POST",
                                    url: "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvepo'));?>",
                                    /*approvepr*/
                                    data: curr_data,
                                    async: false,
                                    success: function (response) {
                                        location.reload();
                                        return false;
                                    },
                                    error: function (e) {
                                        alert('Error');
                                    }
                                });
                            } else {
                                jQuery(this).removeAttr('checked');
                                jQuery(this).parent().removeClass('checked');
                                //jQuery(this).prop('checked', true);
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
                        <div class="col-md-2">Select Project </div>
                        <div class="col-md-3">
                            <select class="select2" style="width: 100%;" name="project_id" id="project_id"
                                class="validate[required]">
                                <option value="">All</Option>
                                <?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($selected_project,$retrive_data['project_id']).'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
                            </select>
                            <div class="">
                                <!--  Date From : -->
                            </div>
                            <div class=""><input type="hidden" name="from_date" id="from_date"
                                    value="<?php echo $from_date;?>" class="form-control" /></div>
                            <div class="">
                                <!-- Date To : -->
                            </div>
                            <div class=""><input type="hidden" name="to_date" id="to_date"
                                    value="<?php echo $to_date;?>" class="form-control" /></div>
                        </div>

                        <div class="col-md-3">
                            <select class="select2" style="width: 100%;" name="po_type" id="po_type"
                                class="validate[required]">
                                <option value="">All</Option>
                                <option value="po">PO</Option>
                                <option value="manual_po">Manual PO</Option>
                                <option value="local_po">Local PO</Option>
                            </select>
                            <div class="">
                                <!--  Date From : -->
                            </div>
                            <div class=""><input type="hidden" name="from_date" id="from_date"
                                    value="<?php echo $from_date;?>" class="form-control" /></div>
                            <div class="">
                                <!-- Date To : -->
                            </div>
                            <div class=""><input type="hidden" name="to_date" id="to_date"
                                    value="<?php echo $to_date;?>" class="form-control" /></div>
                        </div>

                        <div class="col-md-2">
                            <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary"
                                    value="Go" /></div>
                        </div>
                        <br>
                        <br>
                    </div>
                    </form>
                </div>
                <div class="content ">
                    <?php 
			// if(($project_id != ""))
			if($show_data)
			{
			/* echo $this->Form->Create('form2',['id'=>'app_frm','method'=>'post','url'=>['action'=>'preparegrn']]); */
			echo $this->Form->Create('form2',['id'=>'app_frm','method'=>'post','url'=>['controller'=>"Purchase",'action'=>'showinporecords']]);
			?>
                    <input type="hidden" name="selected_project_id" value="<?php echo $selected_project; ?>">
                    <div id="scrolling-div">
                        <table id="pr_list" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <!--<th>Project Code</th>
						<th>P.R. No</th>-->
                                    <th>Project</th>
                                    <th>P.O. No</th>
                                    <th>P.O. Type</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th colspan="9" style="padding:0;">
                                        <table class='table-bordered' style='width:100%;'>
                                            <tr>

                                                <th>Vendor Name</th>

                                                <th>Material Name</th>
                                                <th>Make/<br>Source</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Final Rate<br>(Incl. All)</th>
                                                <th>Amount<br>(Incl. All)</th>
                                                <!-- <th>Mode of<br>Purchase</th> -->
                                                <th>Rate History</th>
                                                <?php
								if($this->ERPfunction->retrive_accessrights($role,'verifypurchasepoalert')==1)
								{
								?>
                                                <th>Verify</th>
                                                <?php
								}
								if($this->ERPfunction->retrive_accessrights($role,'approve1purchasepoalert')==1)
								{
								?>
                                                <th>Approve</th>
                                                <?php
								}
								if($this->ERPfunction->retrive_accessrights($role,'approve2purchasepoalert')==1)
								{
								?>
                                                <th>Final Approve</th>
                                                <?php
								}
								?>
                                                <th>GO</th>
                                            </tr>
                                        </table>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
					// echo ($project_id != "")?$this->ERPfunction->get_po_alerts($project_id):"";
					echo $this->ERPfunction->get_po_alerts($selected_project,$po_type);
					?>
                                <input type="hidden" name="po" id="po_text">
                            </tbody>
                        </table>
                    </div>
                    <?php 
			}
			?>
                    <div class="form-row">
                        <div class="col-md-1 pull-right">
                            <!-- <input type="submit" name="approve_po" value="Go" class="btn btn-success"> -->
                            <?php  echo $this->Form->end();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php }?>
</div>
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

    $("input[type='radio']").change(function () {
        var val = $(this).val();
        var url = $("#data-url").val();
        $(".purchase_mod option[value='" + val + "']").attr("selected", "selected");
        if (val == "local") {
            $('#app_frm').attr("action", url + "/inventory/preparegrnwithoutpo");
        } else {
            $('#app_frm').attr("action", url + "/inventory/preparepo2");
        }
    });
    $(".go_btn").click(function () {
        var po_no = $(this).attr('po_no');
        $("#po_text").val(po_no);
        $("#app_frm").submit();
    });

     // Edit Button hide code
    // $(".approved_list1").click(function() {
    //     var poId = $(this).val();
    //     if($(this).is(":checked")){
    //         $("#edit-btn"+poId).hide();
    //     }
    //     else if($(this).is(":not(:checked)")){
    //         $("#edit-btn"+poId).show();
    //     }
    // });
    jQuery('body').on('click','.rate_history',function(){
        var pr_detail_id  = jQuery(this).attr('po_detail_id');
        var project_id = jQuery(this).attr('p_id');
        var materialId = jQuery(this).attr('m_id');
        var curr_data = {
            pr_detail_id:pr_detail_id,
            project_id:project_id,
            materialId : materialId
        };	 				
        jQuery.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            type:"POST",
            url: "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewratehistory'));?>",
            data:curr_data,
            async:false,
            success: function(response){                    
                jQuery('.modal-content').html(response);					
            },
            error: function(e) {
                console.log(e.responseText);
            }
        });	
                            
    });
    getDate = (retrivedDate) => {
        alert(retrivedDate);return false;
        var date = new Date(retrivedDate);
        var newdate = new Date(date);
        newdate.setDate(newdate.getDate() + 3);
        var dd = newdate.getDate();
        var mm = newdate.getMonth() + 1;
        var y = newdate.getFullYear();
        var formattedDate = dd + '-' + mm + '-' + y;
        return formattedDate;
    }
    
    jQuery("body").on("click","#deletepoalert",function(event) {
        var del = false;
        if(confirm('1.Are you sure want to cancel P.O. ? Only approved materials from P.O. Alert will be cancelled.')) {
            if(confirm('2.Are you sure want to cancel P.O. ? Only approved materials from P.O. Alert will be cancelled.')) {
                if(confirm('3.Are you sure want to cancel P.O. ? Only approved materials from P.O. Alert will be cancelled.')) {
                    del = true;
                }
            }
        }
        if(del) {
            return true;
        }else {
            return false;
        }
    });
</script>
