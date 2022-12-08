<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a class="text-white"><?= lang('leave_type_heading_list') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('WorkWeek'); ?>"><?= lang('work_week_heading_list') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('MonthlyWeekDays'); ?>"><?= lang('month_work_week_heading_list') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('EmployeeType'); ?>"><?= lang('employee_type_heading_list') ?></a></li>
		</ul>
	</div>
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"><b><?= lang('leave_type_heading_list') ?></b></h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<li class="nav-item pl-3">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding deleteRecord"><i
							class="fa fa-trash"></i></button>
			</li>
			<li class="nav-item pl-3">
				<a href="<?= site_url('LeaveType/manage'); ?>"
				   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><i
							class="fa fa-plus"></i> <?= lang('add_leave_type') ?></a>
			</li>
		</ul>
	</div>
</div>

<div class="panel panel-flat">
	<div class="table-responsive">
		<table id="leaveTypeTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('leave_type') ?></th>
				<th><?= lang('total_days_type') ?></th>
				<th><?= lang('total_days') ?></th>
				<th><?= lang('increamented_by') ?></th>
				<th><?= lang('payment_status') ?></th>
				<th><?= lang('action') ?></th>
			</tr>
			</thead>
		</table>
	</div>
</div>


<script>
	$(document).ready(function () {
		dt_DataTable = $('#leaveTypeTable').DataTable({
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
				"url": "<?= site_url('LeaveType/getLeaveTypeListing'); ?>",
				"type": "post",
				"data": function (d) {
					return $.extend({}, d, {
						"<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>'
					});
				}
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[1, "ASC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "leave_type_id"},
				{"data": "leave_type"},
				{"data": "days_type"},
				{
					"data": "no_of_days",
					"render": function (data, type, row) {
						var html = '';
						if(data == '500')
							return html = 'Unlimited';
						else
						   return html = data;
					}
				},
				{"data": "increament_days"},
				{"data": "payment_status",
					"render": function (data, type, row) {
						var html = '';
						if(data == 'no')
							return html = '<a href="#" class="btn btn-dark btn-lg disabled" role="button" aria-disabled="true">Leave Without Pay</a>';
						else if (data == 'yes')
						   return html = '<a href="#" class="btn btn-danger btn-lg disabled" role="button" aria-disabled="true">Paid Leave</a>';
					}
				},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['leave_type_id'];
						html += "<a  href='<?= site_url('LeaveType/manage/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('edit_leave_type') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('edit_leave_type') ?>' class='btn btn-outline-warning btn-sm mr-1'><i class='fa fa-pencil'></i></a>";
						html += "&nbsp";
						html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_leave_type') ?>' title='<?= lang('delete_leave_type') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
						return html;
					},
					"sortable": false,
					"searchable": false
				},
				

			],
			"columnDefs": [
				{
					"targets": 0,
					"render": function (data, type, row) {
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['leave_type_id'] + '" name="ids[]" value="' + row['leave_type_id'] + '"/></label>';
					},
										"sortable": false,
					"searchable": false
				},
			
			],

			fnDrawCallback: function (oSettings) {
				// Switchery
				// Initialize multiple switches
				CheckboxKeyGen();
				CustomToolTip();
				ScrollToTop();
			}
		});
	});

</script>

<script>

	//Delete Time Cancel button click to remove checked value
	$(document).on('click', '.cancel', function () {
		$('#leaveTypeTable input[class="dt-checkbox styled"]').prop('checked', false);
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
			$('#leaveTypeTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});

	//Delete function
	function DeleteRecord(leaveTypeId) {
		$('#leaveTypeTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + leaveTypeId).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}


	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#leaveTypeTable tbody input[class="dt-checkbox styled"]:checked');
		var selectedLength = deleteElement.size();
		//  CheckboxKeyGen();
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
						url: "<?= site_url("LeaveType/delete")?>",
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
	function EditRecord(leaveTypeId) {
		$('#leaveTypeTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + leaveTypeId).prop('checked', true);
		//$('.editRecord').click();
		CheckboxKeyGen();

	}
</script>
