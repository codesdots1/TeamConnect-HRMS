<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a class="text-white"><?= lang('employee_attendance_report') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('Report/EmployeeLeaveReport'); ?>"><?= lang('employee_leave_report') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('Report/EmployeeTaskReport'); ?>"><?= lang('employee_task_report') ?></a></li>
		</ul>
	</div>
</div>

<div class="panel panel-flat">
	<div class="table-responsive">
		<br/>
		<div class="row">
			<div class="input-daterange col-md-12">
				<div class="col-md-2" style="padding-left:20px;">
					Date Range:
				</div>
				<div class="col-md-5">
					<input type="text" name="start_date" id="start_date" class="form-control" placeholder="Select Start Date" />
				</div>

				<div class="col-md-5" >
					<input type="text" name="end_date" id="end_date" class="form-control" placeholder="Select End Date" />
				</div>
			</div>
		</div><br/>

		<?php if(strtolower($this->session->userdata('role')) != 'employee'){ ?>
			<div class="">
				<div class="col-md-2" style="padding-left:20px;">
					<?= lang('employee') ?>:
				</div>
				<div class="col-md-6 dropdown">
					<select name="emp_id" id="emp_id" class="form-control dropdown" data-placeholder="Select <?= lang('employee') ?> ">
						<option value=""></option>
					</select>
				</div>
				<div class="col-md-12 set_x_employee">
					<input type="checkbox" name="ex_emp" id="ex_emp" value="no" />
					<span style="margin-left:20px">Set X-Employee ( It will reset above Employee List)</span>
				</div>
			</div>
		<?php } ?>

		<div class="row col-md-12 group_button">
			<div class="form-group">
				<div class="col-md-6" >
					<form method="post" action="<?php echo base_url(); ?>Report/exportToExcelAttendance" id="export-data">
						<button name="export" id="export" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
								style="border-radius: 8px;padding: 5px 15px;margin-left:177px;" data-popup='custom-tooltip' data-original-title="<?= lang('export_to_excel') ?>"
								title="<?= lang('export_to_excel') ?>" ><i class="fa fa-file-excel-o"></i>&nbsp; <?= lang('export_to_excel');?></button>
					</form>
				</div>
				<div class="col-md-4">
					<button name="clear" id="clear" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
							style="border-radius: 8px;padding: 5px 15px;margin-left:70px;" data-popup='custom-tooltip' data-original-title="<?= lang('clear_filter') ?>"
							title="<?= lang('clear_filter') ?>" >&#10006; <?= lang('clear_filter'); ?></button>
					<button name="filter" id="filter" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
							style="border-radius: 8px;padding: 5px 15px;margin-left:5px;" data-popup='custom-tooltip' data-original-title="<?= lang('search_employee_attendance_details') ?>"
							title="<?= lang('search_employee_attendance_details') ?>" >&#10004; <?= lang('filter_record'); ?></button>
				</div>
			</div>
		</div>

		<table id="employeeAttendanceTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><?= lang('employee_name') ?></th>
				<th><?= lang('login_time') ?></th>
				<th><?= lang('logout_time') ?></th>
				<th><?= lang('total_time') ?></th>
				<th><?= lang('attendance_date') ?></th>
			</tr>
			</thead>
		</table>

	</div>
</div>


<?php
if(!isset($empId)){
	$empId = "";
}

?>

<script>
	
	$(document).ready(function () {
		var empIds = '<?php if(isset($empIds)){echo json_encode(array_map('trim', $empIds));} ?>';
		var role =  '<?php echo strtolower($this->session->userdata('role')) ?>';
		
		if(jQuery.isEmptyObject(empIds)){
			empIds = [];
		}
		if(role != 'employee')
			employeeNameDD('','#emp_id','true',empIds);
		
		//Search with date range
		$("#start_date").datepicker({
			dateFormat: 'dd-mm-yy',
			todayBtn:  "linked",
			autoclose: true,
			todayHighlight: true,
			onSelect: function(selected) {

				$("#end_date").datepicker("option", "minDate", selected)
			}
		});
		$("#end_date").datepicker({
			dateFormat: 'dd-mm-yy',
			todayBtn:  "linked",
			autoclose: true,
			todayHighlight: true,
			minDate: $("#start_date").val(),
		});
		
		$("#filter").click(function(){
			var startDate = $("#start_date").val();
			var endDate = $("#end_date").val();
			var empId = $("#emp_id").val();
			 if(startDate == "" && endDate == "" && empId == ""){
				 swal({
					title: "<?= ucwords(lang('info')); ?>",
					text: "<?= lang('search_info'); ?>",
					confirmButtonColor: "<?= BTN_DELETE_INFO; ?>",
					type: "<?= lang('info'); ?>"
				},function(){
					return false;
				});
			 }else{
				dt_DataTable.ajax.reload();
			 }
		});
		$("#clear").click(function(){
			$("#start_date").val('');
			$("#end_date").val('');
			var option = new Option("", "", true, true);
			$('#emp_id').append(option).trigger('change');
			dt_DataTable.ajax.reload();
		});
		
		// End Search with date range
		
		var role = '<?php echo  $this->session->userdata['role'] ?>';
		dt_DataTable = $('#employeeAttendanceTable').DataTable({
			dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
			language: {
				lengthMenu: '<span>Show:</span> _MENU_',
				paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
			},
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?= site_url('Report/EmployeeAttendanceDetails'); ?>",
				"type": "post",
				dataType: "json",
				"data": function (d) {
					return $.extend({}, d, {
						"<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>',
						//"empId": '<?= $empId; ?>',
						"startDate": $("#start_date").val(),
						"endDate": $("#end_date").val(),
						"empId" : $("#emp_id").val()
					});
				}
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[4, "DESC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "emp_name"},
				{"data": "login_time"},
				{"data": "logout_time",
				
					"render": function (data, type, row) {
						var outTime = row['out_time'];
						var minOutTime = row['min_out_time'];
						var date = row['attendance_date'];
						var html = '';
						var fullDate  = new Date();
						var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) :(fullDate.getMonth()+1);
						var currentDate = ('0' + (fullDate.getDate())).slice(-2) + "-" + ('0' + twoDigitMonth).slice(-2) + "-" + fullDate.getFullYear();
						
						if((outTime == "00:00:00" || minOutTime == "00:00:00" ) && date == currentDate )
							 html = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In';
						else if((outTime == "00:00:00" || minOutTime == "00:00:00" ) && date != currentDate )
							html = '00:00:00';
						else
							html = data;
						return html;
					}			
				
				
				},
				{"data": "total_time",
				
					"render": function (data, type, row) {
						var html = '';
						if(data.indexOf('-') >  -1)
							html = '00:00:00';
						else
							html = data;
						
						return html;
							
					}
				
				},
				{"data": "attendance_date"},

			],
			
			fnDrawCallback: function (oSettings) {
				// Switchery
				// Initialize multiple switches
				CheckboxKeyGen();
				DtSwitcheryKeyGen();
				CustomToolTip();
				ScrollToTop();
			}
		});
		$("#ex_emp").change(function() {
			if(this.checked) {
				$(this).val('yes');
			}else{
				$(this).val('no');
			}
		});
		
	});

</script>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>
<script type="text/javascript" src="<?= $assets ?>/js/core/libraries/jquery_ui/widgets.min.js"></script>
<style type="text/css">
#employeeAttendanceTable_filter{
	display : none;
}
#employeeAttendanceTable_wrapper .datatable-header, .datatable-footer {
    padding: 10px 20px 0 20px;
}
.filter-part .row{
	margin-bottom : 20px;
}
.datatable-header .dataTables_filter{
	width: auto;
	margin: 0 0 25px 20px;
}
.dataTables_filter input {
	margin-left: 50px;
}

</style>
