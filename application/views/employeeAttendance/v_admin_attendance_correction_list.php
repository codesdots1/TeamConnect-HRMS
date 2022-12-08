<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a href="<?= site_url('EmployeeAttendance'); ?>" class="text-white"><?= lang('employee_attendance_heading_list') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('EmployeeAttendance/attendanceCorrection'); ?>"><?= lang('employee_attendance_correction') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('EmployeeEntryLog'); ?>"><?= lang('employee_entry_log') ?></a></li>
		</ul>
	</div>
</div>

<div class="panel panel-flat">
	<div class="table-responsive">

		<br/>
		<div class="row">
			<div class="input-daterange">

				<div class="col-md-2" style="padding-left:20px;">
					Date Range:
				</div>
				<div class="col-md-4">
					<input type="text" name="start_date" id="start_date" class="form-control" placeholder="Select Start Date" />
				</div>

				<div class="col-md-4" >
					<input type="text" name="end_date" id="end_date" class="form-control" placeholder="Select End Date" />

				</div>
				<div class="col-md-2" style="padding: 0 40px;">
					<button name="filter" id="filter" style="border-radius: 8px;padding: 5px 15px;" class="btn btn-outline-success">Filter</button>
				</div>

			</div>
			<br/>

		<table id="employeeAttendanceTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('employee_name') ?></th>
				<th><?= lang('new_login_time') ?></th>
				<th><?= lang('new_logout_time') ?></th>
				<th><?= lang('attendance_date') ?></th>
				<th><?= lang('status') ?></th>
				<th><?= lang('action') ?></th>
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
			if(startDate == "" && endDate == ""){
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

		var role = '<?php echo  $this->session->userdata['role'] ?>';
		dt_DataTable = $('#employeeAttendanceTable').DataTable({
			dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
			language: {
				search: '<span>Filter:</span> _INPUT_',
				searchPlaceholder: 'Type to filter...',
				lengthMenu: '<span>Show:</span> _MENU_',
				paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
			},
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?= site_url('EmployeeAttendance/adminCorrectionList'); ?>",
				"type": "post",
				dataType: "json",
				"data": function (d) {
					return $.extend({}, d, {
						"<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>',
						"empId": '<?= $empId; ?>',
						"startDate": $("#start_date").val(),
						"endDate": $("#end_date").val()
					});
				}
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[4, "DESC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "attendance_correction_id"},
				{"data": "emp_name"},
				{"data": "login_time"},
				{"data": "logout_time"},
				{"data": "attendance_date"},
				{"data": "approved",
					"render": function (data, type, row) {
							html = '';
							if (data == 'true') {
								html = '<a href="#" class="btn btn-success btn-lg disabled" role="button" aria-disabled="true">Approved</a>';
							}else{
								html = '<a href="#" class="btn btn-danger btn-lg disabled" role="button" aria-disabled="true">UnApproved</a>';
							}
							return html;
					}
						
				},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['attendance_correction_id'];
						var empId = row['emp_id'];
						var date = row['attendance_date'];
						var newdate = date.split("-");
						
						html += "<a href='<?= site_url('EmployeeAttendance/attendanceCorrectionManage/'); ?>" + empId +"/"+ id+"' data-popup='custom-tooltip' data-original-title='<?= lang('view_employee_attendance') ?>' title='<?= lang('view_employee_attendance') ?>' class='btn btn-outline-success btn-sm showEmployee'><i class='fa fa-eye'></i></button>";
						return html;
					},
					"sortable": false,
					"searchable": false
				},

			],
			"columnDefs": [
				{
					"targets": 0,
					"width": "10%",
					"render": function (data, type, row) {
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['emp_id'] +'_'+row['attendance_date']+'" name="ids[]" value="' + row['emp_id']+'_'+row['attendance_date']+ '"/></label>';
					},
					"sortable": false,
					"searchable": false
				}
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
	});

</script>
<script>
	//Edit function
	function EditRecord(employeeAttendanceId) {
		$('#employeeAttendanceTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + employeeAttendanceId).prop('checked', true);
		//$('.editRecord').click();
		CheckboxKeyGen();

	}
</script>

<script>

	//Delete Time Cancel button click to remove checked value
	$(document).on('click', '.cancel', function () {
		$('#employeeAttendanceTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
	});

	$(document).ready(function () {
		// Switchery
		// Initialize multiple switches
		SwitcheryKeyGen();
		CheckboxKeyGen('checkAll');
		CustomToolTip();

		///$('#checkAll').prop('checked', false);
		$('#checkAll').click(function () {
			var checkedStatus = this.checked;
			$('#employeeAttendanceTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});
</script>
<script type="text/javascript" src="<?= $assets ?>/js/core/libraries/jquery_ui/widgets.min.js"></script>
<style type="text/css">
	.datatable-header .dataTables_filter{
		width: auto;
		margin: 0 0 25px 20px;
	}
	.dataTables_filter input {
		width: 230px;
		padding : 0 15px;
		margin-left: 50px;
	}
	.dataTables_filter > label::after {
		content : none;
	}
</style>
