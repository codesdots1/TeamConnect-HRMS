<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"><b><?= lang('employee_attendance_correction_heading_list') ?></b></h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
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
				<div class="col-md-2 float-left">
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
	if(isset($empId) && $empId != "")
		$empId = $empId; 
	else
		$empId = $this->session->userdata['emp_id'];
	
	
?>
<!-- Added hiden form to post some variable in details form which will help to set url for back button -->
<form method="post" name="redirect" class="redirect" action="">
	<input type="hidden" class="post" name="postdata" value="listing">
	<input type="submit" style="display: none;">
</form>
<script>
	$(document).ready(function () {
		var role = '<?php echo  $this->session->userdata['role'] ?>';
		var empId = '<?php echo  $empId; ?>';
		

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
				"url": "<?= site_url('EmployeeAttendance/employeeCorrectionList'); ?>",
				"type": "post",
				dataType: "json",
				"data": function (d) {
					return $.extend({}, d, {
						"<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>',
						"empId": empId,
						"startDate": $("#start_date").val(),
						"endDate": $("#end_date").val()
					});
				}
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[4, "DESC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "attendance_correction_id","sortable": false,},
				{"data": "emp_name"},
				{"data": "login_time"},
				{"data": "logout_time"},
				{"data": "attendance_date"},
				{"data": "status",
					"render": function (data, type, row) {
							html = '';
							var dataArray = data.split('|');
							if (dataArray[0] == 'true') {
								html = '<a href="#" class="btn btn-danger btn-lg disabled" role="button" aria-disabled="true">Rejected</a>';
							}else{
								if (dataArray[1] == 'true') 
									html = '<a href="#" class="btn btn-success btn-lg disabled" role="button" aria-disabled="true">Approved</a>';
								else
									html = '<a href="#" class="btn btn-dark btn-lg disabled" role="button" aria-disabled="true">UnApproved</a>';
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
						
						html += "<a href='<?= site_url('EmployeeAttendance/getAttendanceCorrectionData/'); ?>" + empId +"/"+ id+"' data-popup='custom-tooltip' data-original-title='<?= lang('view_employee_attendance') ?>' title='<?= lang('view_employee_attendance') ?>' class='btn btn-outline-success btn-sm showEmployee'><i class='fa fa-eye'></i></button>";
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
	
	//Added hiden form to post some variable in details form which will help to set url for back button
	$(document).on('click', '.showDetails', function(e) {
		e.preventDefault(); 
		var link = $(this).attr('href');
		$(".redirect").attr('action', link);
		$('.post').attr("value",'correctionpage');
		$('.redirect').submit();
	});

</script>

<script>
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
