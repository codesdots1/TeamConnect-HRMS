<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a class="text-white"><?= lang('employee_leave_heading_list') ?></a></li>
		</ul>
	</div>
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">7 People</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<li class="nav-item pl-3">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding deleteRecord"><i
							class="fa fa-trash"></i></button>
			</li>
			<li class="nav-item pl-3">
				<a href="<?= site_url('EmployeeLeaveType/manage'); ?>"
				   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><i
							class="fa fa-plus"></i> <?= lang('add_employee_leave_type') ?></a>

			</li>

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

			<table id="employeeLeaveTypeTable" class="table " cellspacing="0" width="100%">
				<thead>
				<tr>
					<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
					<th><?= lang('name') ?></th>
					<th><?= lang('leave_type') ?></th>
					<th><?= lang('leave_from_date') ?></th>
					<th><?= lang('leave_to_date') ?></th>
					<th><?= lang('apply_date') ?></th>
					<th><?= lang('status') ?></th>
					<th><?= lang('action') ?></th>
				</tr>
				</thead>
			</table>
		</div>
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
			var endDate  = $("#end_date").val();
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
		
		// End Search with date range
		var role= '<?php $this->session->userdata['role']; ?>';
		dt_DataTable = $('#employeeLeaveTypeTable').DataTable({
			dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
			language: {
				search: '<span>Filter:</span> _INPUT_',
				searchPlaceholder: 'Type to filter',
				lengthMenu: '<span>Show:</span> _MENU_',
				paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
			},
			"processing": true,
			"serverSide": true,
			
			"ajax": {
				"url": "<?= site_url('EmployeeLeaveType/getEmployeeLeaveListing'); ?>",
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
			"order": [[6, "DESC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "leave_id"},
				{"data": "name"},
				{"data": "leave_type"},
				{"data": "leave_from_date"},
				{"data": "leave_to_date"},
				{"data": "apply_date"},
				{"data": "is_active",
					"render": function (data, type, row) {
						var is_checked = '';
						var id = row['leave_id'];
						var rejected = row['is_rejected'];
							var html = '';
							if (rejected == 1) {
								html = '<button   class="btn btn-danger btn-lg disabled ">Rejected</button>';
							} else{
								if (data == 1){
									html = '<a href="#" class="btn btn-success btn-lg disabled" role="button" aria-disabled="true">Approved</a>';
								}
								else{
									html = '<a href="#" class="btn btn-dark btn-lg disabled" role="button" aria-disabled="true">Pending</a> ';
								}
							}
							return html;
					},
					
				},
			
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['leave_id'];
						html += "<a  href='<?= site_url('EmployeeLeaveType/manage/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('edit_employee_leave_type') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('edit_employee_leave_type') ?>' class='btn btn-outline-warning btn-sm mr-1'><i class='fa fa-pencil'></i></a>";
						html += "&nbsp";
						html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_employee_leave_type') ?>' title='<?= lang('delete_employee_leave_type') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
						html += "&nbsp";
						html += "<a href='<?= site_url('EmployeeLeaveType/getLeaveDataListing/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('view_employee_leave') ?>' title='<?= lang('view_employee_leave') ?>' class='btn btn-outline-success btn-sm showEmployee'><i class='fa fa-eye'></i></button>";
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
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['leave_id'] + '" name="ids[]" value="' + row['leave_id'] + '"/></label>';
					},
					"sortable": false,
					"searchable": false
				}
			],

			fnDrawCallback: function (oSettings) {
				// Switchery
				// Initialize multiple switches
				CheckboxKeyGen();
				CustomToolTip();
				ScrollToTop();
				DtSwitcheryKeyGen();
			}
		});
	});

</script>

<script>

	//Delete Time Cancel button click to remove checked value
	$(document).on('click', '.cancel', function () {
		$('#employeeLeaveTypeTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
	});

	$(document).on('click', '.isActive', function () {
		var employeeLeaveTypeId   = $(this).attr('id');
		var isActive   			  = $(this).data("status");
		$.ajax({
			type: "post",
			url: "<?= site_url("EmployeeLeaveType/changeActive")?>",
			dataType: "json",
			data: {leave_id: employeeLeaveTypeId, status: isActive},
			success: function (data) {
				if (data) {
					swal({
						title: "<?= ucwords(lang('success')); ?>",
						text: data['msg'],
						confirmButtonColor: "<?= BTN_SUCCESS; ?>",
						type: "<?= lang('success'); ?>"
					},function(){
						dt_DataTable.ajax.reload(null,false);
					});
				} else {
					swal({
						title: "<?= ucwords(lang('error')); ?>",
						text: data['msg'],
						type: "<?= lang('error'); ?>",
						confirmButtonColor: "<?= BTN_ERROR; ?>"
					});
				}
			}
		});
	});

	$(document).ready(function () {
		// Switchery
		// Initialize multiple switches
		DtSwitcheryKeyGen();
		CheckboxKeyGen('checkAll');
		CustomToolTip();

		///$('#checkAll').prop('checked', false);
		$('#checkAll').click(function () {
			var checkedStatus = this.checked;
			$('#employeeLeaveTypeTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});

	//Delete function
	function DeleteRecord(employeeLeaveTypeId) {
		$('#employeeLeaveTypeTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + employeeLeaveTypeId).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}


	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#employeeLeaveTypeTable tbody input[class="dt-checkbox styled"]:checked');
		var selectedLength = deleteElement.size();
		if (selectedLength == 0) {
			swal({
				title: "<?= ucwords(lang('info')); ?>",
				text: "<?= lang('delete_info'); ?>",
				confirmButtonColor: "<?= BTN_DELETE_INFO; ?>",
				type: "<?= lang('info'); ?>"
			},function(){
				return false;
			});
		} else {
			var deleteId = [];
			$.each(deleteElement, function (i, ele) {
				deleteId.push($(ele).val());
			});

			swal({
					title: "<?= ucwords(lang('delete')); ?>",
					text: "<?= lang('delete_warning'); ?>",
					type: "<?= lang('warning'); ?>",
					showCancelButton: true,
					closeOnConfirm: false,
					confirmButtonColor: "<?= BTN_DELETE_WARNING; ?>",
					showLoaderOnConfirm: true
				},
				function () {
					$.ajax({
						type: "post",
						url: "<?= site_url("EmployeeLeaveType/delete")?>",
						dataType: "json",
						data: {deleteId: deleteId},
						success: function (data) {
							if (data['success']) {
								swal({
									title: "<?= ucwords(lang('success'))?>",
									text: data['msg'],
									type: "<?= lang('success')?>",
									confirmButtonColor: "<?= BTN_SUCCESS; ?>",
								});
								dt_DataTable.ajax.reload();
								//$('#checkAll').prop('checked', false);
								CheckboxKeyGen('checkAll');
							} else {
								swal({
									title: "<?= ucwords(lang('error')); ?>",
									text: data['msg'],
									type: "<?= lang('error'); ?>",
									confirmButtonColor: "<?= BTN_ERROR; ?>"
								});
								dt_DataTable.ajax.reload();
								CheckboxKeyGen('checkAll');
							}
						}
					});
				});
		}
	});


	//Edit function
	function EditRecord(employeeLeaveTypeId) {
		$('#employeeLeaveTypeTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + employeeLeaveTypeId).prop('checked', true);
		//$('.editRecord').click();
		CheckboxKeyGen();

	}
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
